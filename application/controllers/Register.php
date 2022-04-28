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
  # This is Register Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends Cl_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('excel'); //load PHPExcel library 
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Master_model');
        $this->load->model('Register_model');
        $this->load->library('form_validation');
        
        $this->Common_model->setDefaultTimezone();
        
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * open Register
     * @access public
     * @return void
     * @param no
     */
    public function openRegister(){
        $data = array();
        $data['main_content'] = $this->load->view('register/openRegister', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * add Balance
     * @access public
     * @return void
     * @param int
     */
    public function addBalance($encrypted_id = ""){
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('opening_balance', lang('opening_balance'), 'required');
            if ($this->form_validation->run() == TRUE) {
                $register_info = array();
                $register_info['opening_balance'] = htmlspecialchars($this->input->post($this->security->xss_clean('opening_balance')));
                $register_info['closing_balance'] = 0.00;
                $register_info['opening_balance_date_time'] = date('Y-m-d H:i:s');
                $register_info['register_status'] = 1;
                $register_info['user_id'] = $this->session->userdata('user_id');
                $register_info['outlet_id'] = $this->session->userdata('outlet_id');
                $register_info['company_id'] = $this->session->userdata('company_id');
                $this->Common_model->insertInformation($register_info, "tbl_register");
                
                if (!$this->session->has_userdata('clicked_controller')) {
                    if ($this->session->userdata('role') == 'Admin') {
                        redirect('Dashboard/dashboard');
                    } else {
                        redirect('Authentication/userProfile');
                    }
                } else {
                    redirect('POSChecker/posAndWaiterMiddleman');
                }
            }else {
                $data = array();
                $data['main_content'] = $this->load->view('register/openRegister', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * check Register Ajax
     * @access public
     * @return void
     * @param no
     */
    public function checkRegisterAjax()
    {
        echo "#############################";
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $checkRegister = $this->Register_model->checkRegister($user_id,$outlet_id);
        if(!is_null($checkRegister)){
            echo escape_output($checkRegister->status);
        }else{
            echo "";
        }
                
    }

}
