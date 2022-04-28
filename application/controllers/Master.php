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
  # This is Master Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends Cl_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('excel'); //load PHPExcel library 
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Master_model');
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
     * employees info
     * @access public
     * @return void
     * @param no
     */
    public function employees() {
        $company_id = $this->session->userdata('company_id');

        $data = array();
        $data['employees'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
        $data['main_content'] = $this->load->view('master/employee/employees', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Employee
     * @access public
     * @return void
     * @param no
     */
    public function deleteEmployee($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChange($id, "tbl_users");

        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Master/employees');
    }
     /**
     * add/Edit Employee
     * @access public
     * @return void
     * @param no
     */
    public function addEditEmployee($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', lang('name'), 'required|max_length[50]');
            $this->form_validation->set_rules('designation', lang('description'), 'required|max_length[50]');
            $this->form_validation->set_rules('phone', lang('phone'), 'required|max_length[15]');
            $this->form_validation->set_rules('description', lang('description'), 'max_length[100]');
            if ($this->form_validation->run() == TRUE) {
                $fmc_info = array();
                $fmc_info['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $fmc_info['designation'] =htmlspecialchars($this->input->post($this->security->xss_clean('designation')));
                $fmc_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $fmc_info['description'] =htmlspecialchars($this->input->post($this->security->xss_clean('description')));
                $fmc_info['user_id'] = $this->session->userdata('user_id');
                $fmc_info['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($fmc_info, "tbl_users");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($fmc_info, $id, "tbl_users");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Master/employees');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['main_content'] = $this->load->view('master/employee/addEmployee', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['employee_information'] = $this->Common_model->getDataById($id, "tbl_users");
                    $data['main_content'] = $this->load->view('master/employee/editEmployee', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['main_content'] = $this->load->view('master/employee/addEmployee', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['employee_information'] = $this->Common_model->getDataById($id, "tbl_users");
                $data['main_content'] = $this->load->view('master/employee/editEmployee', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * open Register
     * @access public
     * @return void
     * @param no
     */
    public function openRegister(){
        $data = array();
        $data['main_content'] = $this->load->view('master/register/openRegister', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * get food menu id
     * @access public
     * @return int
     * @param string
     */
    public function get_food_menu_id($foodingredints) {

        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_food_menus WHERE company_id=$company_id and user_id=$user_id and name='" . $foodingredints . "'")->row('id');
        if ($id) {
            return $id;
        } else {
            $id = 0;
            return $id;
        }
    }
     /**
     * get food menu ingredient id
     * @access public
     * @return int
     * @param string
     */
    public function get_foodmenu_ingredient_id($foodingredints) {

        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_ingredients WHERE company_id=$company_id and user_id=$user_id and name='" . $foodingredints . "'")->row('id');
        if ($id) {
            return $id;
        } else {
            $id = 0;
            return $id;
        }
    }
     /**
     * get All Purchases Of Current Date
     * @access public
     * @return float
     * @param no
     */
    public function getAllPurchasesOfCurrentDate()
    {
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');

        $total_purchase_amount_of_this_user = $this->Common_model->getPurchaseAmountByUserAndOutletId($user_id,$outlet_id);
        return $total_purchase_amount_of_this_user->total_purchase_amount;
    }

}
