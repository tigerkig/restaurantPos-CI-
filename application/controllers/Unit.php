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
  # This is Unit Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends Cl_Controller {

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
     * Units
     * @access public
     * @return void
     * @param no
     */
    public function Units() {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['Units'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_units");
        $data['main_content'] = $this->load->view('master/Unit/Units', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Unit
     * @access public
     * @return void
     * @param int
     */
    public function deleteUnit($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_units");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Unit/Units');
    }
     /**
     * add/Edit Unit
     * @access public
     * @return void
     * @param int
     */
    public function addEditUnit($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('unit_name', lang('unit_name'), 'required');
            if ($this->form_validation->run() == TRUE) {
                $vat = array();
                $vat['unit_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('unit_name')));
                $vat['description'] =htmlspecialchars($this->input->post($this->security->xss_clean('description')));
                $vat['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($vat, "tbl_units");
                    $this->session->set_flashdata('exception',lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($vat, $id, "tbl_units");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Unit/Units');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['main_content'] = $this->load->view('master/Unit/addEditUnit', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['Units'] = $this->Common_model->getDataById($id, "tbl_units");
                    $data['main_content'] = $this->load->view('master/Unit/addEditUnit', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['main_content'] = $this->load->view('master/Unit/addEditUnit', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['Units'] = $this->Common_model->getDataById($id, "tbl_units");
                $data['main_content'] = $this->load->view('master/Unit/addEditUnit', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }

}
