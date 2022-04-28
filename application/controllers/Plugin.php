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
  # This is Expense Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
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
     * plugin info
     * @access public
     * @return void
     * @param no
     */
    public function plugins() {
        $data = array();
        $data['plugins'] = $this->Common_model->getAllByTable("tbl_plugins");
        $data['main_content'] = $this->load->view('plugin/plugins', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * plugin info
     * @access public
     * @return void
     * @param no
     */
    public function OP($status,$id) {
        $plugin['active_status'] = $status;
        $this->Common_model->updateInformation($plugin, $id, "tbl_plugins");
        if($status=="Yes"){
            $this->session->set_flashdata('exception', lang('insertion_success'));
        }else{
            $this->session->set_flashdata('exception', lang('update_success'));
        }
        redirect('Plugin/plugins');
    }

}
