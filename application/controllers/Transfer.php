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
  # This is Transfer Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Transfer_model');
        $this->load->model('Sale_model');
        $this->load->model('Master_model');
        $this->load->model('Common_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', lang('please_click_green_button'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }

        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * transfers info
     * @access public
     * @return void
     * @param no
     */
    public function transfers() {
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['transfers'] = $this->Transfer_model->getAllTrasferData($outlet_id);
        $data['main_content'] = $this->load->view('transfer/transfers', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Transfer
     * @access public
     * @return void
     * @param int
     */
    public function deleteTransfer($id) {
        $role = $this->session->userdata('role');
        if($role=="Admin"){
            $id = $this->custom->encrypt_decrypt($id, 'decrypt');
            $this->Common_model->deleteStatusChangeWithChild($id, $id, "tbl_transfer", "tbl_transfer_ingredients", 'id', 'transfer_id');
            $this->session->set_flashdata('exception', lang('delete_success'));
        }else{
            $this->session->set_flashdata('exception_error', lang('error_transfer'));
        }
        redirect('Transfer/transfers');
    }
     /**
     * add/Edit Transfer
     * @access public
     * @return void
     * @param int
     */
    public function addEditTransfer($encrypted_id = "") {


        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $transfer_info = array();

        if ($id == "") {
            $transfer_info['reference_no'] = $this->Transfer_model->generatePurRefNo($outlet_id);
        } else {
            $transfer_info['reference_no'] = $this->Common_model->getDataById($id, "tbl_transfer")->reference_no;
        }

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('reference_no', lang('ref_no'), 'required|max_length[50]');
            if ($id == "") {
                $this->form_validation->set_rules('to_outlet_id', lang('to_outlet'), 'required|max_length[50]');
            }
            $this->form_validation->set_rules('status', "Status", 'required');
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $transfer_info['reference_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('reference_no')));
                $transfer_info['date'] = date('Y-m-d', strtotime($this->input->post($this->security->xss_clean('date'))));
                $transfer_info['note_for_sender'] =htmlspecialchars($this->input->post($this->security->xss_clean('note_for_sender')));
                $transfer_info['note_for_receiver'] =htmlspecialchars($this->input->post($this->security->xss_clean('note_for_receiver')));
                $transfer_info['status'] =htmlspecialchars($this->input->post($this->security->xss_clean('status')));
                $transfer_info['transfer_type'] =htmlspecialchars($this->input->post($this->security->xss_clean('transfer_type')));
                $transfer_info['user_id'] = $this->session->userdata('user_id');
                if($this->input->post($this->security->xss_clean('received_date'))){
                    $transfer_info['received_date'] =htmlspecialchars($this->input->post($this->security->xss_clean('received_date')));
                }
                if ($id == "") {
                    $transfer_info['from_outlet_id'] = $this->session->userdata('outlet_id');
                    $transfer_info['to_outlet_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('to_outlet_id')));
                    $transfer_info['outlet_id'] = $this->session->userdata('outlet_id');

                    $transfer_id = $this->Common_model->insertInformation($transfer_info, "tbl_transfer");
                    $this->saveTransferIngredients($_POST['ingredient_id'], $transfer_id, $this->session->userdata('outlet_id'),$transfer_info['to_outlet_id'],$transfer_info['status'],'');
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $transfer_details = $this->Common_model->getDataById($id, "tbl_transfer");
                    $outlet_id = $this->session->userdata('outlet_id');
                    if($outlet_id!=$transfer_details->to_outlet_id  && $outlet_id==$transfer_details->outlet_id){
                        $transfer_info['from_outlet_id'] = $this->session->userdata('outlet_id');
                        $transfer_info['to_outlet_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('to_outlet_id')));
                        $transfer_info['outlet_id'] = $this->session->userdata('outlet_id');
                    }
                    $this->Common_model->updateInformation($transfer_info, $id, "tbl_transfer");
                    $this->Common_model->deletingMultipleFormData('transfer_id', $id, 'tbl_transfer_ingredients');
                    $this->Common_model->deletingMultipleFormData('transfer_id', $id, 'tbl_transfer_received_ingredients');
                    $this->saveTransferIngredients($_POST['ingredient_id'], $id, $transfer_details->outlet_id,$transfer_info['to_outlet_id'],$transfer_info['status'],$transfer_details->to_outlet_id);
                    $this->session->set_flashdata('exception',lang('update_success'));
                }

                redirect('Transfer/transfers');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['pur_ref_no'] = $this->Transfer_model->generatePurRefNo($outlet_id);
                    $data['ingredients'] = $this->Transfer_model->getIngredientListWithUnitAndPrice($company_id);
                    $data['outlets'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_outlets");
                    $data['food_menus'] = $this->Common_model->getAllByTable("tbl_food_menus");

                    foreach ($data['food_menus'] as $key=>$value){
                        $total = 0;
                        $all_ings = $this->Transfer_model->getTotalCostAmount($value->id);
                        foreach ($all_ings as $vl){
                            $total+=$vl->purchase_price*$vl->consumption;
                        }
                        $total_return_amount = getTaxAmount($value->sale_price,$value->tax_information);
                        $data['food_menus'][$key]->ings_total_cost = $total;
                        $data['food_menus'][$key]->total_tax = $total_return_amount;
                    }
                    $data['main_content'] = $this->load->view('transfer/addTransfer', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['transfer_details'] = $this->Common_model->getDataById($id, "tbl_transfer");
                    $data['food_details'] = $this->Transfer_model->getFoodDetails($id);
                    $data['ingredients'] = $this->Transfer_model->getIngredientListWithUnitAndPrice($company_id);
                    $data['food_menus'] = $this->Common_model->getAllByTable("tbl_food_menus");

                    foreach ($data['food_menus'] as $key=>$value){
                        $total = 0;
                        $all_ings = $this->Transfer_model->getTotalCostAmount($value->id);
                        foreach ($all_ings as $vl){
                            $total+=$vl->purchase_price*$vl->consumption;
                        }
                        $total_return_amount = getTaxAmount($value->sale_price,$value->tax_information);
                        $data['food_menus'][$key]->ings_total_cost = $total;
                        $data['food_menus'][$key]->total_tax = $total_return_amount;
                    }
                    $data['outlets'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_outlets");
                    $data['main_content'] = $this->load->view('transfer/editTransfer', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['pur_ref_no'] = $this->Transfer_model->generatePurRefNo($outlet_id);
                $data['ingredients'] = $this->Transfer_model->getIngredientListWithUnitAndPrice($company_id);
                $data['outlets'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_outlets");
                $data['food_menus'] = $this->Common_model->getAllByTable("tbl_food_menus");

                foreach ($data['food_menus'] as $key=>$value){
                    $total = 0;
                    $all_ings = $this->Transfer_model->getTotalCostAmount($value->id);
                    foreach ($all_ings as $vl){
                        $total+=$vl->purchase_price*$vl->consumption;
                    }
                    $total_return_amount = getTaxAmount($value->sale_price,$value->tax_information);
                    $data['food_menus'][$key]->ings_total_cost = $total;
                    $data['food_menus'][$key]->total_tax = $total_return_amount;
                }
                $data['main_content'] = $this->load->view('transfer/addTransfer', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['transfer_details'] = $this->Common_model->getDataById($id, "tbl_transfer");
                $data['food_details'] = $this->Transfer_model->getFoodDetails($id);
                $data['ingredients'] = $this->Transfer_model->getIngredientListWithUnitAndPrice($company_id);
                $data['food_menus'] = $this->Common_model->getAllByTable("tbl_food_menus");
                foreach ($data['food_menus'] as $key=>$value){
                    $total = 0;
                    $all_ings = $this->Transfer_model->getTotalCostAmount($value->id);
                    foreach ($all_ings as $vl){
                        $total+=$vl->purchase_price*$vl->consumption;
                    }
                    $total_return_amount = getTaxAmount($value->sale_price,$value->tax_information);
                    $data['food_menus'][$key]->ings_total_cost = $total;
                    $data['food_menus'][$key]->total_tax = $total_return_amount;
                }
                $data['outlets'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_outlets");
                $data['main_content'] = $this->load->view('transfer/editTransfer', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * save Transfer Ingredients
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveTransferIngredients($transfer_ingredients, $transfer_id, $from_outlet,$to_outlet,$status,$to_outlet_id='') {
        foreach ($transfer_ingredients as $row => $ingredient_id):
            $data_sale_consumptions_detail = array();
            $data_sale_consumptions_detail['status'] = $status;
            $data_sale_consumptions_detail['ingredient_id'] = $_POST['ingredient_id'][$row];
            $data_sale_consumptions_detail['quantity_amount'] = $_POST['quantity_amount'][$row];
            $data_sale_consumptions_detail['transfer_type'] = $_POST['transfer_type'];
            $data_sale_consumptions_detail['total_cost'] = $_POST['total_cost'][$row];
            $data_sale_consumptions_detail['single_cost_total'] = $_POST['single_cost_total'][$row];
            $data_sale_consumptions_detail['total_sale_amount'] = $_POST['total_sale_amount'][$row];
            $data_sale_consumptions_detail['total_tax'] = $_POST['total_tax'][$row];
            $data_sale_consumptions_detail['single_total_sale_amount'] = $_POST['single_total_sale_amount'][$row];
            $data_sale_consumptions_detail['single_total_tax'] = $_POST['single_total_tax'][$row];
            $data_sale_consumptions_detail['transfer_id'] = $transfer_id;
            $data_sale_consumptions_detail['from_outlet_id'] = $from_outlet;
            if($to_outlet_id!=''){
                $data_sale_consumptions_detail['to_outlet_id'] = $to_outlet_id;
            }else{
                $data_sale_consumptions_detail['to_outlet_id'] = $to_outlet;
            }
            $data_sale_consumptions_detail['del_status'] = 'Live';

            $this->db->insert('tbl_transfer_ingredients',$data_sale_consumptions_detail);
        endforeach;

        if($_POST['transfer_type']==2){
            foreach ($transfer_ingredients as $row => $ingredient_id):
                $main_ingredients = $this->Common_model->getAllByCustomId($_POST['ingredient_id'][$row],"food_menu_id","tbl_food_menus_ingredients",$order='');;
                foreach ($main_ingredients as $value){
                    $data_sale_consumptions_detail = array();
                    $data_sale_consumptions_detail['status'] = $status;
                    $data_sale_consumptions_detail['ingredient_id'] = $value->ingredient_id;
                    $data_sale_consumptions_detail['quantity_amount'] = ($_POST['quantity_amount'][$row] * $value->consumption);
                    $data_sale_consumptions_detail['transfer_type'] = $_POST['transfer_type'];
                    $data_sale_consumptions_detail['total_cost'] = $_POST['total_cost'][$row];
                    $data_sale_consumptions_detail['single_cost_total'] = $_POST['single_cost_total'][$row];
                    $data_sale_consumptions_detail['total_sale_amount'] = $_POST['total_sale_amount'][$row];
                    $data_sale_consumptions_detail['total_tax'] = $_POST['total_tax'][$row];
                    $data_sale_consumptions_detail['single_total_sale_amount'] = $_POST['single_total_sale_amount'][$row];
                    $data_sale_consumptions_detail['single_total_tax'] = $_POST['single_total_tax'][$row];
                    $data_sale_consumptions_detail['transfer_id'] = $transfer_id;
                    $data_sale_consumptions_detail['from_outlet_id'] = $from_outlet;
                    if($to_outlet_id!=''){
                        $data_sale_consumptions_detail['to_outlet_id'] = $to_outlet_id;
                    }else{
                        $data_sale_consumptions_detail['to_outlet_id'] = $to_outlet;
                    }
                    $data_sale_consumptions_detail['del_status'] = 'Live';
                    $this->db->insert('tbl_transfer_received_ingredients',$data_sale_consumptions_detail);
                }

            endforeach;
        }

    }
     /**
     * transfer Details
     * @access public
     * @return void
     * @param int
     */
    public function transferDetails($id) {
        $encrypted_id = $id;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['transfer_details'] = $this->Common_model->getDataById($id, "tbl_transfer");
        $data['food_details'] = $this->Transfer_model->getFoodDetails($id);
        $data['main_content'] = $this->load->view('transfer/transferDetails', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * add New Supplier By Ajax
     * @access public
     * @return object
     * @param no
     */
    public function addNewSupplierByAjax() {
        $data['name'] = $_GET['name'];
        $data['contact_person'] = $_GET['contact_person'];
        $data['phone'] = $_GET['phone'];
        $data['email'] = $_GET['emailAddress'];
        $data['address'] = $_GET['supAddress'];
        $data['description'] = $_GET['description'];
        $data['user_id'] = $this->session->userdata('user_id');
        $data['company_id'] = $this->session->userdata('company_id');
        $this->db->insert('tbl_suppliers', $data);
        $supplier_id = $this->db->insert_id();
        $data1 = array('supplier_id' => $supplier_id);
        echo json_encode($data1);
    }
     /**
     * get Supplier List
     * @access public
     * @return void
     * @param no
     */
    public function getSupplierList() {
        $company_id = $this->session->userdata('company_id');
        $data1 = $this->db->query("SELECT * FROM tbl_suppliers 
              WHERE company_id=$company_id")->result();
        //generate html content for view
        echo '<option value="">Select</option>';
        foreach ($data1 as $value) {
            echo '<option value="' . $value->id . '" >' . $value->name . '</option>';
        }
        exit;
    }
}
