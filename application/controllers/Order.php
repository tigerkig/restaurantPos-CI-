<?php
/*
  ###########################################################
  # PRODUCT NAME: 	iRestora PLUS - Next Gen Restaurant POS
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Door Soft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Order Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Outlet_model');
        $this->load->model('Order_model');
        $this->load->model('Sale_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }
     /**
     * order info
     * @access public
     * @return void
     * @param no
     */
    public function index() {
        $data = array();
        $data['setting_info'] = $this->Order_model->getInfoByID("tbl_settings");
        $data['foodCategory'] = $this->Order_model->getAllByTable("tbl_food_menu_categories");
        $data['foodMenus'] = $this->Sale_model->getAllFoodMenus();
        $data['modifier'] = $this->Sale_model->getAllMenuModifiers();
        $data['info'] = $this->Common_model->getDataById(1, "tbl_online_and_self_order_setting"); 
        $data['outlet_information'] = $this->Common_model->getDataById(1, "tbl_outlets");
        $this->load->view('online_and_self_order/main_online_order', $data);
    }
     /**
     * add sale by ajax
     * @access public
     * @return string
     * @param no
     */
    public function add_sale_by_ajax(){
        $data = array();
        $data['customer_id'] = $this->session->userdata('customer_id');
        $data['total_items'] = trim($this->input->post('totalitem'));
        $data['sub_total'] = trim($this->input->post('sub_total'));
        $data['vat'] = trim($this->input->post('TotalTax'));

        $data['total_payable'] = trim($this->input->post('totalVal'));
        $data['total_item_discount_amount'] = 0;
        $data['sub_total_with_discount'] = trim($this->input->post('sub_total'));
        $data['sub_total_discount_amount'] = 0;
        $data['total_discount_amount'] = 0;
        $data['delivery_charge'] = trim($this->input->post('totalCharge'));;
        $data['sub_total_discount_value'] = 0;
        $data['sub_total_discount_type'] = 'plain';
        $data['user_id'] =1;
        $data['waiter_id'] = 0;
        $data['outlet_id'] = 1;
        $data['sale_date'] = date('Y-m-d');
        $data['date_time'] = date('Y-m-d H:i:s');
        $data['order_time'] = date("H:i:s"); 
        $data['order_status'] = 1;
        $data['sale_vat_objects'] = NULL;
        $data['order_type'] = trim($this->input->post('order_type'));

        $this->db->trans_begin();
        $query = $this->db->insert('tbl_sales', $data);
        $sales_id = $this->db->insert_id();
        $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
        $sale_no_update_array = array('sale_no' => $sale_no);
        $this->db->where('id', $sales_id);
        $this->db->update('tbl_sales', $sale_no_update_array);
        ///////////////////////////////
        $order_table_info = array();
        $order_table_info['persons'] = 1;
        $order_table_info['booking_time'] = date('Y-m-d H:i:s');
        $order_table_info['sale_id'] = $sales_id;
        $order_table_info['sale_no'] = $sale_no;
        $order_table_info['outlet_id'] = 1;
        $order_table_info['table_id'] = 1; 
        $query = $this->db->insert('tbl_orders_table',$order_table_info);
        ///////////////////////////////
        $data_sale_consumptions = array();
        $data_sale_consumptions['sale_id'] = $sales_id;
        $data_sale_consumptions['user_id'] = 1; 
        $data_sale_consumptions['outlet_id'] =1;
        $data_sale_consumptions['del_status'] = 'Live'; 
        $query = $this->db->insert('tbl_sale_consumptions',$data_sale_consumptions);
        $sale_consumption_id = $this->db->insert_id();
        
        $menuids=$this->input->post('menuid');
        $row=0;
        if($sales_id>0 && count($menuids)>0){
            foreach($menuids as $item){
                $food_menu_id=$item;
                $item_date = array();
                $item_data['food_menu_id'] =  $_POST['menuid'][$row];
                $item_data['menu_name'] =  $_POST['menuname'][$row];
                $item_data['qty'] =  $_POST['menuqty'][$row];
                $item_data['menu_price_without_discount'] = $_POST['munitprice'][$row];
                $item_data['menu_price_with_discount'] = $_POST['munitprice'][$row];
                $item_data['menu_unit_price'] = $_POST['munitprice'][$row];
                $item_data['menu_vat_percentage'] = $_POST['taxpercentange'][$row];
                $item_data['menu_taxes'] = $_POST['vat'][$row];
                $item_data['menu_discount_value'] =NULL;
                $item_data['discount_type'] = 'plain';
                $item_data['menu_note'] =$_POST['menu_note'][$row];
                $item_data['discount_amount'] = 0;
                $item_data['item_type'] ='Kitchen Item';
                $item_data['cooking_status'] = "";
                $item_data['cooking_start_time'] = '0000-00-00 00:00:00';
                $item_data['cooking_done_time'] = '0000-00-00 00:00:00';
                $item_data['sales_id'] = $sales_id;
                $item_data['user_id'] = 1;
                $item_data['outlet_id'] = 1;
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_sales_details', $item_data);
                $sales_details_id = $this->db->insert_id();
                ///////////////////////////////
                $food_menu_ingredients = $this->db->query("SELECT * FROM tbl_food_menus_ingredients WHERE food_menu_id=$food_menu_id")->result();

                foreach($food_menu_ingredients as $single_ingredient){                    
                    $data_sale_consumptions_detail = array();
                    $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                    $data_sale_consumptions_detail['consumption'] = $_POST['menuqty'][$row]*$single_ingredient->consumption;
                    $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                    $data_sale_consumptions_detail['sales_id'] = $sales_id;
                    $data_sale_consumptions_detail['food_menu_id'] = $food_menu_id;
                    $data_sale_consumptions_detail['user_id'] = 1;
                    $data_sale_consumptions_detail['outlet_id'] = 1;
                    $data_sale_consumptions_detail['del_status'] = 'Live'; 
                    $query = $this->db->insert('tbl_sale_consumptions_of_menus',$data_sale_consumptions_detail);    
                }

                $modifier_id_array = ($_POST['modifierid'][$row]!="")?explode(",",$_POST['modifierid'][$row]):null;
                $modifier_price_array = ($_POST['modifierPrice'][$row]!="")?explode(",",$_POST['modifierPrice'][$row]):null;

                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id; 
                        $modifier_data['modifier_price'] = $modifier_price_array[$i]; 
                        $modifier_data['food_menu_id'] = $food_menu_id; 
                        $modifier_data['sales_id'] = $sales_id; 
                        $modifier_data['sales_details_id'] = $sales_details_id; 
                        $modifier_data['user_id'] = 1;
                        $modifier_data['outlet_id'] = 1; 
                        $modifier_data['customer_id'] =$this->session->userdata('customer_id');
                        $query = $this->db->insert('tbl_sales_details_modifiers', $modifier_data);
                        
                        $modifier_ingredients = $this->db->query("SELECT * FROM tbl_modifier_ingredients WHERE modifier_id=$single_modifier_id")->result();

                        foreach($modifier_ingredients as $single_ingredient){
                            $data_sale_consumptions_detail = array();
                            $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                            $data_sale_consumptions_detail['consumption'] = $_POST['menuqty'][$row]*$single_ingredient->consumption; 
                            $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                            $data_sale_consumptions_detail['sales_id'] = $sales_id;
                            $data_sale_consumptions_detail['food_menu_id'] = $food_menu_id;
                            $data_sale_consumptions_detail['user_id'] = 1;
                            $data_sale_consumptions_detail['outlet_id'] = 1;
                            $data_sale_consumptions_detail['del_status'] = 'Live'; 
                            $query = $this->db->insert('tbl_sale_consumptions_of_modifiers_of_menus',$data_sale_consumptions_detail);    
                        }

                        $i++;
                    }    
                }
                $row++;
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo "0";
        } else {
            echo escape_output($sales_id);
            $this->db->trans_commit();
        }
        
    }
     /**
     * add New Customer By Ajax
     * @access public
     * @return object
     * @param no
     */
    public function addNewCustomerByAjax() {
        $data['name'] = $_GET['name'];
        $data['phone'] = $_GET['phone'];
        $data['password'] = $_GET['password'];
        $data['address'] = $_GET['address'];
        $this->db->insert('tbl_customers', $data);
        $customer_id = $this->db->insert_id();
        $login_session = array();
        //User Information
        $login_session['customer_id'] =$customer_id;
        $login_session['password'] =$data['password'];
        $login_session['name'] = $data['name'];
        $login_session['phone'] = $data['phone'];
        $login_session['address'] = $data['address']; 
        //Set session
        $this->session->set_userdata($login_session);
        echo json_encode($login_session);
    }
     /**
     * login Customer By Ajax
     * @access public
     * @return object
     * @param no
     */
    public function loginCustomerByAjax() {
        $phone = $_GET['phone'];
        $password = $_GET['password'];
        $this->db->select("*");
        $this->db->from("tbl_customers");
        $this->db->where("phone", $phone);
        $this->db->where("password", $password);
        $this->db->where("del_status", 'Live');
        $info=$this->db->get()->row();
        $login_session = array();
        if($info){
        //User Information
        $login_session['chk'] ='YES';
        $login_session['customer_id'] =$info->id;
        $login_session['password'] =$info->password;
        $login_session['name'] = $info->name;
        $login_session['phone'] = $info->phone;
        $login_session['address'] = $info->address; 
        //Set session
        $this->session->set_userdata($login_session);
        echo json_encode($login_session); 
        }else{
         $login_session['chk'] ='NO'; 
         echo json_encode($login_session);  
        }
        
    }
     /**
     * change Password
     * @access public
     * @return object
     * @param no
     */
    public function changePassword() {
        $oldpass = $_GET['oldpass'];
        $newpass = $_GET['newpass'];
        $confirmpass = $_GET['confirmpass'];
        if($oldpass!=$this->session->userdata('password')){
            $adata['chk'] ="Old Password doesn't match"; 
            echo json_encode($adata); 
        }elseif($newpass!=$confirmpass){
            $adata['chk'] ="New Password & Confirm Password doesn't match"; 
            echo json_encode($adata);
        }else{
            $customer_id=$this->session->userdata('customer_id');
            $data['password'] =$newpass;
            $this->db->where('id', $customer_id);
            $this->db->update('tbl_customers',$data);
            $adata['chk'] ="YES"; 
            $login_session['password'] = $newpass; 
            $this->session->set_userdata($login_session);
            echo json_encode($adata);
        }
    }
     /**
     * check Take Away Code
     * @access public
     * @return object
     * @param no
     */
    public function checkTakeAwayCode() {
        $takeawaycode = $_GET['takeawaycode'];
        $info = $this->Common_model->getDataById(1, "tbl_online_and_self_order_setting");
        if($info->take_away_code==$takeawaycode){
           $adata['chk'] ="YES"; 
           echo json_encode($adata);
        }else{
            $adata['chk'] ="NO"; 
            echo json_encode($adata);
        }        
    }
     /**
     * get Customer Info
     * @access public
     * @return object
     * @param no
     */
    public function getCustomerInfo() {
        $customer_id=$this->session->userdata('customer_id');
        $this->db->select("*");
        $this->db->from("tbl_customers");
        $this->db->where("id", $customer_id);
        $info=$this->db->get()->row();
        $data['name'] = $info->name;
        $data['phone'] = $info->phone;
        $data['address'] = $info->address; 
        echo json_encode($data);
    }
     /**
     * get Order Lists
     * @access public
     * @return void
     * @param no
     */
    public function getOrderLists() {
        $customer_id=$this->session->userdata('customer_id');
        $this->db->select("*");
        $this->db->from("tbl_sales");
        $this->db->where("customer_id", $customer_id);
        $lists=$this->db->get()->result();
        $i=1;
        //generate html content for view
        foreach ($lists as  $value) {
            $type='';
            if($value->order_type==1) $type="Dine-in";
            elseif($value->order_type==2) $type="Take Away";
            else $type="Delivery";
            $status='';
            if($value->order_status==1) $status="New";
            else $status="Delivered";


           echo'<tr>
              <th scope="row">'.$i++.'</th>
              <td>'.$value->sale_no.'</td>
              <td>'.$type.'</td>
              <td>'.findDate($value->sale_date).'</td>
              <td>'.$value->total_payable.'</td>
              <td>'.$status.'</td>
              <td>
                <div class="btn-group dropleft">
                  <a class="dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icofont-ui-settings"></i>
                  </a>
                  <div class="dropdown-menu actionBtnDrop" aria-labelledby="actionButton">
                    <a class="dropdown-item" href="#">Add More Item</a>
                    <a class="dropdown-item" href="#">View Details</a>
                    <a class="dropdown-item" href="#">Cancel</a>
                  </div>
                </div>
              </td>
            </tr>';
        }


    }
     /**
     * change Profile
     * @access public
     * @return object
     * @param no
     */
    public function changeProfile() {
        $data['name'] = $_GET['chname'];
        $data['phone'] = $_GET['chphone'];
        $data['address'] = $_GET['chaddress'];
        $customer_id=$this->session->userdata('customer_id');
        $this->db->where('id', $customer_id);
        $this->db->update('tbl_customers',$data);
        $this->session->set_userdata($data);
        echo json_encode($data);
    }
     /**
     * customer Logout
     * @access public
     * @return object
     * @param no
     */
    public function customerLogout() {
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('password');
        $this->session->unset_userdata('address');
        $this->session->sess_destroy();
        $adata = array();
        $adata['yes'] ='YES';
        echo json_encode($adata);
    }
     /**
     * get Menu Info
     * @access public
     * @return object
     * @param no
     */
    public function getMenuInfo() {
        $customer_id=$this->session->userdata('customer_id');
        $this->db->select("*");
        $this->db->from("tbl_customers");
        $this->db->where("id", $customer_id);
        $info=$this->db->get()->row();
        $data['name'] = $info->name;
        $data['phone'] = $info->phone;
        $data['address'] = $info->address; 

        echo json_encode($data);
    }
     /**
     * online And Self Order Setting
     * @access public
     * @return void
     * @param int
     */
    public function onlineAndSelfOrderSetting($id = '') {
        $encrypted_id = $id = $outlet_id = 1;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $company_id = $this->session->userdata('company_id');        
        $data = array();
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('site_title', 'Site Title', 'required|max_length[50]');
            $this->form_validation->set_rules('base_color', 'Base Color', 'required|max_length[200]');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            $this->form_validation->set_rules('delivery_enable_disable', 'Enable/Disable', 'required');
            if($this->input->post('delivery_enable_disable')=='delivery_enable'){
               $this->form_validation->set_rules('delivery_charge', 'Delivery Charge', 'required'); 
               $this->form_validation->set_rules('delivery_opening_time', 'Delivery Opening Time', 'required'); 
               $this->form_validation->set_rules('delivery_closing_time', 'Delivery Closing Time', 'required'); 
               $this->form_validation->set_rules('delivery_order_user_id', 'Who will get Delivery Order Notification', 'required'); 
            }
            $this->form_validation->set_rules('dine_in_enable_disable', 'Enable/Disable', 'required');
            if($this->input->post('dine_in_enable_disable')=='dine_in_enable'){
               $this->form_validation->set_rules('dine_in_code', 'Dine-in Code', 'required'); 
               $this->form_validation->set_rules('mst', 'Allow to select table manually', 'required'); 
               $this->form_validation->set_rules('dion', 'Order Notification', 'required'); 
               if($this->input->post('dion')=='diony'){
                $this->form_validation->set_rules('dine_in_order_user_id', 'Who will get Dine-in Order Notification', 'required'); 
               }
               
            }
            $this->form_validation->set_rules('take_away_enable_disable', 'Enable/Disable', 'required');
            if($this->input->post('take_away_enable_disable')=='take_away_enable'){
               $this->form_validation->set_rules('take_away_code', 'Take Away Code', 'required'); 
               $this->form_validation->set_rules('take_away_order_user_id', 'Who will get Take Away Order Notification', 'required'); 
            }


            $outlet_info = array();
            $imgchk=1;
            if($_FILES['logo']['name']!=""){
                $config['upload_path'] = './assets_online_order/images/';
                $config['allowed_types'] = 'gif|png|jpg|jpeg';
                $config['max_size'] = '3000';
                $config['max_width'] = '140';
                $config['min_width'] = '140';
                $config['max_height'] = '50';
                $config['min_height'] = '50';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("logo")){
                $upload_info = $this->upload->data();
                $outlet_info['logo']=$upload_info['file_name'];
                }else{
                  $imgchk=2;
                  $error=$this->upload->display_errors();
                  $data['exception_err']=$error;
                }
              }else{
                $imgchk=1;
            }
            $imgchk1=1;
            if($_FILES['favicon']['name']!=""){
                $config['upload_path'] = './assets_online_order/images/';
                $config['allowed_types'] = 'ico';
                $config['max_size'] = '1000';
                $config['max_width'] = '16';
                $config['min_width'] = '16';
                $config['max_height'] = '16';
                $config['min_height'] = '16';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload("favicon")){
                $upload_info = $this->upload->data();
                $outlet_info['favicon']=$upload_info['file_name'];
                }else{
                  $imgchk1=2;
                  $error=$this->upload->display_errors();
                  $data['exception_err1']=$error;
                }
              }else{
                $imgchk1=1;
            }

            if ($this->form_validation->run() == TRUE&&$imgchk==1&&$imgchk1==1) {
                $outlet_info['base_color'] =htmlspecialchars($this->input->post($this->security->xss_clean('base_color')));
                $outlet_info['site_title'] =htmlspecialchars($this->input->post($this->security->xss_clean('site_title')));
                $outlet_info['email'] =htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                $outlet_info['phone_number'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone_number')));
                $outlet_info['facebook_link'] =htmlspecialchars($this->input->post($this->security->xss_clean('facebook_link')));
                $outlet_info['twitter_link'] =htmlspecialchars($this->input->post($this->security->xss_clean('twitter_link')));
                $outlet_info['pinterest_link'] =htmlspecialchars($this->input->post($this->security->xss_clean('pinterest_link')));
                $outlet_info['delivery_enable_disable'] =htmlspecialchars($this->input->post($this->security->xss_clean('delivery_enable_disable')));
                $outlet_info['delivery_charge'] =htmlspecialchars($this->input->post($this->security->xss_clean('delivery_charge')));
                $outlet_info['delivery_opening_time'] =htmlspecialchars($this->input->post($this->security->xss_clean('delivery_opening_time')));
                $outlet_info['delivery_closing_time'] =htmlspecialchars($this->input->post($this->security->xss_clean('delivery_closing_time')));
                $outlet_info['delivery_order_user_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('delivery_order_user_id')));
                $outlet_info['dine_in_enable_disable'] =htmlspecialchars($this->input->post($this->security->xss_clean('dine_in_enable_disable')));
                $outlet_info['dine_in_code'] =htmlspecialchars($this->input->post($this->security->xss_clean('dine_in_code')));
                $outlet_info['mst'] =htmlspecialchars($this->input->post($this->security->xss_clean('mst')));
                $outlet_info['dion'] =htmlspecialchars($this->input->post($this->security->xss_clean('dion')));
                $outlet_info['dine_in_order_user_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('dine_in_order_user_id')));
                $outlet_info['take_away_enable_disable'] =htmlspecialchars($this->input->post($this->security->xss_clean('take_away_enable_disable')));
                $outlet_info['take_away_code'] =htmlspecialchars($this->input->post($this->security->xss_clean('take_away_code')));
                $outlet_info['take_away_order_user_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('take_away_order_user_id')));
               
                if ($id == "") {            
                    $outlet_id = $this->Common_model->insertInformation($outlet_info, "tbl_online_and_self_order_setting");            
                    $this->session->set_flashdata('exception', 'Information has been added successfully!');
                } else {
                    $this->Common_model->updateInformation($outlet_info, $id, "tbl_online_and_self_order_setting");                 
                    $this->session->set_flashdata('exception', 'Information has been updated successfully!');
                }
                redirect('Order/onlineAndSelfOrderSetting');
            } else {
                
                $data['encrypted_id'] = $encrypted_id;
                $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_outlets");
                $data['online_order_setting_information'] = $this->Common_model->getDataById($id, "tbl_online_and_self_order_setting"); 
                $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                $data['main_content'] = $this->load->view('online_and_self_order/online_and_self_order_setting', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {

            $data['encrypted_id'] = $encrypted_id;
            $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users"); 
            $data['online_order_setting_information'] = $this->Common_model->getDataById($id, "tbl_online_and_self_order_setting"); 
            $data['main_content'] = $this->load->view('online_and_self_order/online_and_self_order_setting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
        
    }
     /**
     * set Opening Closing Time
     * @access public
     * @return void
     * @param int
     */
    public function setOpeningClosingTime($id = '') {
        $encrypted_id = $id = $outlet_id = 1;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        
        if ($this->input->post('submit')) {
            $outlet_info = array();
            
            
            $date_name =htmlspecialchars($this->input->post($this->security->xss_clean('date_name')));
            $o_time =htmlspecialchars($this->input->post($this->security->xss_clean('o_time')));
            $c_time =htmlspecialchars($this->input->post($this->security->xss_clean('c_time')));
            $r_arr = array();
            $i = 1;
            foreach ($date_name as $key=>$value){
            $status = "status_".$i;
            $status = isset($_POST[$status]) && $_POST[$status]?$_POST[$status]:'0';
            $data = array();
            $data['date_name'] = $value;
            $data['o_time'] = $o_time[$key];
            $data['c_time'] = $c_time[$key];
            $data['status'] = $status;
            $r_arr[] = $data;
            $i++;
            }
            $data = array();
            $data['opening_closing_time'] = json_encode($r_arr);
            if ($id == "") {
            $this->Common_model->insertInformation($data, "tbl_online_and_self_order_setting");
            $this->session->set_flashdata('exception', 'Information has been added successfully!');
            } else {
            $this->Common_model->updateInformation($data, $id, "tbl_online_and_self_order_setting");
            $this->session->set_flashdata('exception', 'Information has been updated successfully!');
            }
            redirect("Order/setOpeningClosingTime/$id");
        } else {
            $data = array();
            $data['encrypted_id'] = $encrypted_id;
            $data['online_order_setting_information'] = $this->Common_model->getDataById($id, "tbl_online_and_self_order_setting");
            $data['main_content'] = $this->load->view('online_and_self_order/set_opening_closing_time', $data, TRUE);
            $this->load->view('userHome', $data);
        }
        
    }
     /**
     * print Table QR code
     * @access public
     * @return void
     * @param int
     */
    public function printTableQRcode($id = '') {
        $encrypted_id = $id = $outlet_id = 1;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $company_id = $this->session->userdata('company_id');        
        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users"); 
        $data['online_order_setting_information'] = $this->Common_model->getDataById($id, "tbl_online_and_self_order_setting"); 
        $data['tables'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_tables");
        $this->load->view('online_and_self_order/print_table_qr_code', $data);
   }
    /**
     * print Menu Name QRcode
     * @access public
     * @return void
     * @param int
     */
    public function printMenuNameQRcode($id = '') {
        $encrypted_id = $id = $outlet_id = 1;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $company_id = $this->session->userdata('company_id');        
        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users"); 
        $data['online_order_setting_information'] = $this->Common_model->getDataById($id, "tbl_online_and_self_order_setting"); 
        $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
        $this->load->view('online_and_self_order/print_menu_name_qr_code', $data);
        
        
    }
     /**
     * save Outlet Taxes
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveOutletTaxes($outlet_taxes, $outlet_id, $table_name)
    {
        foreach($outlet_taxes as $single_tax){
            $oti = array();
            $oti['tax'] = $single_tax;
            $this->Common_model->insertInformation($oti, "tbl_outlet_taxes");
        }
    }
     /**
     * validate logo
     * @access public
     * @return string
     * @param boolean
     */
    public function validate_logo(){

        if ($_FILES['logo']['name'] != "") {
            $config['upload_path'] = './images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '1000';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("logo")) {

                $upload_info = $this->upload->data();

                $file_name = $upload_info['file_name'];
        
                $this->session->set_userdata('logo', $file_name);

            } else {
                $this->form_validation->set_message('validate_logo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }
     /**
     * get Modifier List
     * @access public
     * @return void
     * @param no
     */
    public function getModifierList(){
        $id = $_GET['id'];
        $modifier = $this->Sale_model->getAllMenuModifiers();
        $html='';
        //generate html content for view
        foreach ($modifier as $rowm) {
            if($rowm->food_menu_id==$id){ 
            $html.='<div class="modifierItem" id="m'.$rowm->modifier_id.'" >
                <span>'.$rowm->price.'</span>
                <button type="button" class="innerModifier innerModifier1" data-selected="unselected">
                  <p>'.$rowm->name.'</p>
                  <div class="addModifierUnitPrice ir_display_none">'.$rowm->price.'</div>
                  <div class="addModifierId ir_display_none">'.$rowm->modifier_id.'</div>
                  <div class="addModifierName ir_display_none">'.$rowm->name.'</div>
                </button>
              </div>';
            }
        }
        /*This variable could not be escaped because this is html content*/
        echo $html;
    }
     /**
     * get Modifier List2
     * @access public
     * @return void
     * @param no
     */
    public function getModifierList2(){
        $modifierids = $_GET['modifierids'];
        $mids=array();
        $mids = explode(",", $modifierids);
        $id = $_GET['id'];
        $modifier = $this->Sale_model->getAllMenuModifiers();
        $html='';
        //generate html content for view
        foreach ($modifier as $rowm) {
            $selected="unselected";
            $style='';
            if($rowm->food_menu_id==$id){ 
            if(in_array($rowm->modifier_id, $mids)){
             $selected="selected";
             $style='style="background-color:#B5D6F6"';
            }
            $html.='<div class="modifierItem" id="m'.$rowm->modifier_id.'" >
                <span>'.$rowm->price.'</span>
                <button type="button" class="innerModifier innerModifier2" '.$style.' data-selected="'.$selected.'">
                  <p>'.$rowm->name.'</p>
                  <div class="editModifierUnitPrice ir_display_none">'.$rowm->price.'</div>
                  <div class="editModifierId ir_display_none">'.$rowm->modifier_id.'</div>
                  <div class="editModifierName ir_display_none">'.$rowm->name.'</div>
                </button>
              </div>';
            }
        }
        /*This variable could not be escaped because this is html content*/
        echo $html;
    }

    
    
}