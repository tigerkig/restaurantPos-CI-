<?php
/*
  ###########################################################
  # PRODUCT NAME: 	One Stop
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Doorsoft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Printer Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Printer extends Cl_Controller {
    /**
     * load constructor
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
         $this->load->model('Authentication_model');
        $this->load->model('Printer_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

    /**
     * printer list
     * @access public
     * @return void
     */
    public function printers() {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['printer_'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_printers");
        $data['main_content'] = $this->load->view('printer/printers', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * delete printer
     * @access public
     * @return void
     * @param int
     */
    public function deletePrinter($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_printers");
        $this->session->set_flashdata('exception',  lang('delete_success'));
        redirect('printer/printers');
    }
    /**
     * add/edit printer
     * @access public
     * @return void
     * @param int
     */
    public function addEditPrinter($encrypted_id = "") {

        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('title',  lang('title'), 'required|max_length[300]');
            $this->form_validation->set_rules('characters_per_line',  lang('characters_per_line'), 'required|max_length[300]');

            $type = htmlspecialchars($this->input->post('type'));
            $this->form_validation->set_rules('type', lang('type'), 'required|max_length[20]');
            if($type=="linux" || $type=="windows"){
                $this->form_validation->set_rules('path', lang('path'), 'required|max_length[100]');
            }else if($type=="network"){
                $this->form_validation->set_rules('printer_ip_address', lang('printer_ip_address'), 'required|max_length[20]');
                $this->form_validation->set_rules('printer_port', lang('printer_port'), 'required|max_length[20]');
            }

            if ($this->form_validation->run() == TRUE) {
                $data= array();
                $data['path'] = htmlspecialchars($this->input->post('path'));
                $data['title'] = htmlspecialchars($this->input->post('title'));
                $data['type'] = htmlspecialchars($this->input->post('type'));
                $data['profile_'] = htmlspecialchars($this->input->post('profile'));
                $data['printer_ip_address'] = htmlspecialchars($this->input->post('printer_ip_address'));
                $data['printer_port'] = htmlspecialchars($this->input->post('printer_port'));
                $data['characters_per_line'] = htmlspecialchars($this->input->post('characters_per_line'));
                $data['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($data, "tbl_printers");
                    $this->session->set_flashdata('exception',  lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($data, $id, "tbl_printers");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('printer/printers');
            } else {

                if ($id == "") {
                    $data = array();
                    $data['main_content'] = $this->load->view('printer/addPrinter', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['printer_'] = $this->Common_model->getDataById($id, "tbl_printers");
                    $data['main_content'] = $this->load->view('printer/editPrinter', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['main_content'] = $this->load->view('printer/addPrinter', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['printer_'] = $this->Common_model->getDataById($id, "tbl_printers");
                $data['main_content'] = $this->load->view('printer/editPrinter', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
}
