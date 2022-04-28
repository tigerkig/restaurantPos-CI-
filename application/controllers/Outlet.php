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
  # This is Outlet Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Outlet extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Outlet_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $language_manifesto = $this->session->userdata('language_manifesto');
        if(str_rot13($language_manifesto)=="fgjgldkfg"){
            $getAccessURL = ucfirst($this->uri->segment(1));
            if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
                redirect('Authentication/userProfile');
            }else{
                $login_session['active_menu_tmp'] = 4;
                $this->session->set_userdata($login_session);
            }
        }else{
            $login_session['active_menu_tmp'] = 4;
            $this->session->set_userdata($login_session);
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
    }

     /**
     * outlets info
     * @access public
     * @return void
     * @param no
     */
    public function outlets() {
        //unset outlet data
        $language_manifesto = $this->session->userdata('language_manifesto');

        if(str_rot13($language_manifesto)=="fgjgldkfg"){
            $outlet_id = $this->session->userdata('outlet_id');
            redirect("Outlet/addEditOutlet/".$outlet_id);
        }
        $data = array();
        $data['outlets'] = $this->Common_model->getAllOutlestByAssign();
        $data['main_content'] = $this->load->view('outlet/outlets', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Outlet
     * @access public
     * @return void
     * @param int
     */
    public function deleteOutlet($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChange($id, "tbl_outlets");

        $this->session->set_flashdata('exception',lang('delete_success'));
        redirect('Outlet/outlets');
    }
     /**
     * add/Edit Outlet
     * @access public
     * @return void
     * @param int
     */
    public function addEditOutlet($encrypted_id = "") {

        if(isServiceAccessOnly('sGmsJaFJE')){
            if($encrypted_id==''){
                if(!checkCreatePermissionOutlet()){
                    $data_c = getLanguageManifesto();
                    $this->session->set_flashdata('exception_1',lang('not_permission_outlet_create_error'));
                    redirect($data_c[1]);
                }
            }

        }
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        $language_manifesto = $this->session->userdata('language_manifesto');


        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('outlet_name',lang('outlet_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('address',lang('address'), 'required|max_length[200]');
            $this->form_validation->set_rules('phone', lang('phone'), 'required');
            if(str_rot13($language_manifesto)=="eriutoeri"):
                $this->form_validation->set_rules('default_waiter', lang('Default_Waiter'), 'max_length[11]');
            endif;
            if ($this->form_validation->run() == TRUE) {
                $outlet_info = array();
                $outlet_info['outlet_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('outlet_name')));
                $c_address =htmlspecialchars($this->input->post($this->security->xss_clean('address'))); #clean the address
                $outlet_info['address'] = preg_replace("/[\n\r]/"," ",$c_address); #remove new line from address
                $outlet_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $outlet_info['email'] =htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                $outlet_info['has_kitchen'] =htmlspecialchars($this->input->post($this->security->xss_clean('has_kitchen')));
                if(str_rot13($language_manifesto)=="eriutoeri"):
                    $outlet_info['default_waiter'] =htmlspecialchars($this->input->post($this->security->xss_clean('default_waiter')));
                    $outlet_info['active_status'] =htmlspecialchars($this->input->post($this->security->xss_clean('active_status')));
                endif;
                $this->session->set_userdata($outlet_info);
                if ($id == "") {
                    $outlet_info['company_id'] = $this->session->userdata('company_id');
                            if(str_rot13($language_manifesto)=="eriutoeri") {
                                $outlet_info['outlet_code'] = htmlspecialchars($this->input->post($this->security->xss_clean('outlet_code')));
                            }
                }
                if ($id == "") {
                    $id = $this->Common_model->insertInformation($outlet_info, "tbl_outlets");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($outlet_info, $id, "tbl_outlets");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                $language_manifesto = $this->session->userdata('language_manifesto');
                if(str_rot13($language_manifesto)=="eriutoeri"):
                    $item_check =$this->input->post($this->security->xss_clean('item_check'));
                    if($item_check){
                        $main_arr = '';
                        $total_selected = sizeof($item_check);
                        $data_price_array = array();
                        for($i=0;$i<$total_selected;$i++){
                            $main_arr.=$item_check[$i];
                            if($i < ($total_selected) -1){
                                $main_arr.=",";
                                $name_generate = "price_".$item_check[$i];
                                $data_price_array["tmp".$item_check[$i]] = htmlspecialchars($this->input->post($this->security->xss_clean($name_generate)));;
                            }
                        }
                        //set food menu for this outlet
                        $data_food_menus['food_menus'] = $main_arr;
                        $data_food_menus['food_menu_prices'] = json_encode($data_price_array);
                        $this->Common_model->updateInformation($data_food_menus, $id, "tbl_outlets");
                    }
                    endif;
                $data_c = getLanguageManifesto();
                redirect($data_c[1]);
            } else {
                if ($id == "") {
                    $data = array();
                    $data['items'] = $this->Common_model->getFoodMenu($company_id, "tbl_food_menus");
                    $data['outlet_code'] = $this->Outlet_model->generateOutletCode();
                     if(str_rot13($language_manifesto)=="eriutoeri"):
                        $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
                     endif;
                    $data['main_content'] = $this->load->view('outlet/addOutlet', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['items'] = $this->Common_model->getFoodMenu($company_id, "tbl_food_menus");
                    $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_outlets");
                     if(str_rot13($language_manifesto)=="eriutoeri"):
                        $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
                     endif;
                    $data['main_content'] = $this->load->view('outlet/editOutlet', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            $language_manifesto = $this->session->userdata('language_manifesto');
            if(str_rot13($language_manifesto)=="fgjgldkfg"){
                $outlet_id = $this->session->userdata('outlet_id');
                if($outlet_id != $id){
                    redirect("Outlet/addEditOutlet/".$outlet_id);
                }
            }
            if ($id == "") {
                $data = array();
                $data['items'] = $this->Common_model->getFoodMenu($company_id, "tbl_food_menus");
                $data['outlet_code'] = $this->Outlet_model->generateOutletCode();
                 if(str_rot13($language_manifesto)=="eriutoeri"):
                        $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
                 endif;
                $data['main_content'] = $this->load->view('outlet/addOutlet', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['items'] = $this->Common_model->getFoodMenu($company_id, "tbl_food_menus");
                $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_outlets");
                 if(str_rot13($language_manifesto)=="eriutoeri"):
                        $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
                 endif;

                $selected_modules =  explode(',',$data['outlet_information']->food_menus);
                $selected_modules_arr = array();
                foreach ($selected_modules as $value) {
                    $selected_modules_arr[] = $value;
                }
                $data['selected_modules_arr'] = $selected_modules_arr;
                $data['main_content'] = $this->load->view('outlet/editOutlet', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * set Outlet Session
     * @access public
     * @return void
     * @param int
     */
    public function setOutletSession($encrypted_id) {
        $outlet_id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $language_manifesto = $this->session->userdata('language_manifesto');
        $outlet_details = $this->Common_model->getDataById($outlet_id, 'tbl_outlets');

        $outlet_session = array();
        $outlet_session['outlet_id'] = $outlet_details->id;
        $outlet_session['outlet_name'] = $outlet_details->outlet_name;
        $outlet_session['address'] = $outlet_details->address;
        $outlet_session['phone'] = $outlet_details->phone;
        $outlet_session['email'] = $outlet_details->email;
        $outlet_session['has_kitchen'] = $outlet_details->has_kitchen;

        if(str_rot13($language_manifesto)=="eriutoeri"):
            $outlet_session['default_waiter'] = $outlet_details->default_waiter;
        else:
            $setting = getCompanyInfo();
            $outlet_session['default_waiter'] = $setting->default_waiter;
            endif;
        $this->session->set_userdata($outlet_session);

        if (!$this->session->has_userdata('clicked_controller')) {
            if ($this->session->userdata('role') == 'Admin') {
                redirect('Dashboard/dashboard');
            } else {
                redirect('POSChecker/posAndWaiterMiddleman');
            }
        } else {
            $clicked_controller = $this->session->userdata('clicked_controller');
            $clicked_method = $this->session->userdata('clicked_method');

            $this->session->unset_userdata('clicked_controller');
            $this->session->unset_userdata('clicked_method');
            redirect($clicked_controller . '/' . $clicked_method);
        }
    }
}
