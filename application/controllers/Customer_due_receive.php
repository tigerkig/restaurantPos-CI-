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
  # This is Customer_due_receive Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_due_receive extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model'); 
        $this->load->model('Customer_due_receive_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

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
        //check register is open or not
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        if($this->Common_model->isOpenRegister($user_id,$outlet_id)==0){
            $this->session->set_flashdata('exception_3', lang('register_not_open'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Register/openRegister');   
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }


     /**
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    public function customerDueReceives() {
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['customerDueReceives'] = $this->Common_model->getAllByOutletId($outlet_id, "tbl_customer_due_receives");

        $data['main_content'] = $this->load->view('customerDueReceive/customerDueReceives', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    public function deleteCustomerDueReceive($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_customer_due_receives");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Customer_due_receive/customerDueReceives');
    }
     /**
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    public function addCustomerDueReceive() {
        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            $this->form_validation->set_rules('reference_no', lang('ref_no'), 'required|max_length[50]');
            $this->form_validation->set_rules('amount', lang('amount'), 'required|max_length[50]');
            $this->form_validation->set_rules('customer_id', lang('customer'), 'required|max_length[10]');
            $this->form_validation->set_rules('note', lang('note'), 'max_length[200]');
            if ($this->form_validation->run() == TRUE) {
                $splr_payment_info = array();
                $splr_payment_info['date'] = date("Y-m-d H:i:s");
                $splr_payment_info['only_date'] = date("Y-m-d");
                // $splr_payment_info['date'] = date("Y-m-d", strtotime($this->input->post($this->security->xss_clean('date'))));
                $splr_payment_info['amount'] =htmlspecialchars($this->input->post($this->security->xss_clean('amount')));
                $splr_payment_info['reference_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('reference_no')));
                $splr_payment_info['customer_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('customer_id')));
                $splr_payment_info['payment_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('payment_id')));
                $splr_payment_info['note'] =htmlspecialchars($this->input->post($this->security->xss_clean('note')));
                $splr_payment_info['user_id'] = $this->session->userdata('user_id');
                $splr_payment_info['outlet_id'] = $this->session->userdata('outlet_id');
                $splr_payment_info['company_id'] = $this->session->userdata('company_id');

                $this->Common_model->insertInformation($splr_payment_info, "tbl_customer_due_receives");
                $this->session->set_flashdata('exception', lang('insertion_success'));

                redirect('Customer_due_receive/customerDueReceives');
            } else {
                $data = array();
                $data['reference_no'] = $this->Customer_due_receive_model->generateReferenceNo($outlet_id);
                $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_customers");
                $data['payment_methods'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_payment_methods");
                $data['main_content'] = $this->load->view('customerDueReceive/addCustomerDueReceive', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['reference_no'] = $this->Customer_due_receive_model->generateReferenceNo($outlet_id);

            $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_customers");
            $data['payment_methods'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_payment_methods");
            $data['main_content'] = $this->load->view('customerDueReceive/addCustomerDueReceive', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * bar panel
     * @access public
     * @return float
     * @param no
     */
    public function getCustomerDue() {
        $customer_id = $_GET['customer_id']; 

        $remaining_due = $this->Customer_due_receive_model->getCustomerDue($customer_id);

        echo (isset($remaining_due) && $remaining_due?getAmtP($remaining_due):getAmtP(0));
    }

}
