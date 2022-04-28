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
  # This is SupplierPayment Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class SupplierPayment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Supplier_payment_model');
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
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }


     /**
     * supplier Payments
     * @access public
     * @return void
     * @param no
     */
    public function supplierPayments() {
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['supplierPayments'] = $this->Common_model->getAllByOutletId($outlet_id, "tbl_supplier_payments");
        $data['main_content'] = $this->load->view('supplierPayment/supplierPayments', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Supplier Payment
     * @access public
     * @return void
     * @param int
     */
    public function deleteSupplierPayment($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_supplier_payments");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('SupplierPayment/supplierPayments');
    }
     /**
     * add Supplier Payment
     * @access public
     * @return void
     * @param no
     */
    public function addSupplierPayment() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            $this->form_validation->set_rules('amount', lang('amount'), 'required|max_length[50]');
            $this->form_validation->set_rules('supplier_id', lang('supplier'), 'required|max_length[10]');
            $this->form_validation->set_rules('note', lang('note'), 'max_length[200]');
            if ($this->form_validation->run() == TRUE) {
                $splr_payment_info = array();
                $splr_payment_info['date'] = date("Y-m-d", strtotime($this->input->post($this->security->xss_clean('date'))));
                $splr_payment_info['amount'] =htmlspecialchars($this->input->post($this->security->xss_clean('amount')));
                $splr_payment_info['supplier_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('supplier_id')));
                $splr_payment_info['note'] =htmlspecialchars($this->input->post($this->security->xss_clean('note')));
                $splr_payment_info['user_id'] = $this->session->userdata('user_id');
                $splr_payment_info['outlet_id'] = $this->session->userdata('outlet_id');

                $this->Common_model->insertInformation($splr_payment_info, "tbl_supplier_payments");
                $this->session->set_flashdata('exception', lang('insertion_success'));

                redirect('SupplierPayment/supplierPayments');
            } else {
                $data = array();
                $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_suppliers");
                $data['main_content'] = $this->load->view('supplierPayment/addSupplierPayment', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_suppliers");
            $data['main_content'] = $this->load->view('supplierPayment/addSupplierPayment', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * get Supplier Due
     * @access public
     * @return float
     * @param no
     */
    public function getSupplierDue() {
        $supplier_id = $_GET['supplier_id'];
        $remaining_due = $this->Supplier_payment_model->getSupplierDue($supplier_id);
        echo escape_output(getAmtP($remaining_due));
    }

}
