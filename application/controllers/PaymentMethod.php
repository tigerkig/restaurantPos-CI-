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
  # This is PaymentMethod Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentMethod extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * payment Methods
     * @access public
     * @return void
     * @param no
     */
    public function paymentMethods() {
        $company_id = $this->session->userdata('company_id');

        $data = array();
        $data['paymentMethods'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_payment_methods");
        $data['main_content'] = $this->load->view('master/paymentMethod/paymentMethods', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Payment Method
     * @access public
     * @return void
     * @param int
     */
    public function deletePaymentMethod($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChange($id, "tbl_payment_methods");

        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('paymentMethod/paymentMethods');
    }
     /**
     * add Edit Payment Method
     * @access public
     * @return void
     * @param int
     */
    public function addEditPaymentMethod($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if($this->input->post('submit')) {
            $this->form_validation->set_rules('name', lang('payment_method_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('description', lang('description'), 'max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $fmc_info = array();
                $fmc_info['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $fmc_info['description'] =escape_output($this->input->post($this->security->xss_clean('description')));
                $fmc_info['user_id'] = $this->session->userdata('user_id');
                $fmc_info['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($fmc_info, "tbl_payment_methods");
                    $this->session->set_flashdata('exception',lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($fmc_info, $id, "tbl_payment_methods");
                    $this->session->set_flashdata('exception',lang('delete_success'));
                }
                redirect('paymentMethod/paymentMethods');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['main_content'] = $this->load->view('master/paymentMethod/addPaymentMethod', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['payment_method_information'] = $this->Common_model->getDataById($id, "tbl_payment_methods");
                    $data['main_content'] = $this->load->view('master/paymentMethod/editPaymentMethod', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['main_content'] = $this->load->view('master/paymentMethod/addPaymentMethod', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['payment_method_information'] = $this->Common_model->getDataById($id, "tbl_payment_methods");
                $data['main_content'] = $this->load->view('master/paymentMethod/editPaymentMethod', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }

}
