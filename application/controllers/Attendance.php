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
  # This is Attendance Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model'); 
        $this->load->model('Attendance_model');
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
     * attendances data
     * @access public
     * @return void
     * @param no
     */
    public function attendances() { 
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['attendances'] = $this->db->query("select * from tbl_attendance where company_id=$company_id and del_status='Live' order by id desc")->result(); 
        $data['main_content'] = $this->load->view('attendance/attendances', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete attendances data
     * @access public
     * @return void
     * @param int
     */
    public function deleteAttendance($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_attendance");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Attendance/attendances');
    }
     /**
     * add edit attendances data
     * @access public
     * @return void
     * @param int
     */
    public function addEditAttendance($encrypted_id='') { 
        $encrypted_id = $encrypted_id;
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt'); 

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('reference_no',lang('ref_no'), 'required|max_length[50]');
            $this->form_validation->set_rules('employee_id', lang('employee'), 'required|max_length[50]');
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            $this->form_validation->set_rules('in_time',lang('in_time'), 'required|max_length[50]');

            if ($encrypted_id != '') {
               $this->form_validation->set_rules('out_time', lang('out_time'), 'required|max_length[10]');
            } 

            $this->form_validation->set_rules('note', lang('note'), 'max_length[200]');
            if ($this->form_validation->run() == TRUE) {
                $information = array();
                $information['reference_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('reference_no')));
                $information['date'] = date("Y-m-d", strtotime($this->input->post($this->security->xss_clean('date'))));
                $information['employee_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('employee_id'))); 
                $information['in_time'] =htmlspecialchars($this->input->post($this->security->xss_clean('in_time'))); 
                $information['out_time'] =htmlspecialchars($this->input->post($this->security->xss_clean('out_time'))); 
                $information['note'] =htmlspecialchars($this->input->post($this->security->xss_clean('note')));
                $information['user_id'] = $this->session->userdata('user_id'); 
                $information['company_id'] = $this->session->userdata('company_id'); 

                if ($id == "") {
                    $this->Common_model->insertInformation($information, "tbl_attendance");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($information, $id, "tbl_attendance");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Attendance/attendances');

            } else {
                if ($id=='') {
                    $data = array();
                    $data['encrypted_id'] = '';
                    $data['reference_no'] = $this->Attendance_model->generateReferenceNo();
                    $company_id = $this->session->userdata('company_id');
                    $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                    $data['main_content'] = $this->load->view('attendance/addEditAttendance', $data, TRUE);
                    $this->load->view('userHome', $data);
                }else{

                }

            }
        } else {
            if ($id=='') { 
                $data = array();
                $data['encrypted_id'] = '';
                $data['reference_no'] = $this->Attendance_model->generateReferenceNo();
                $company_id = $this->session->userdata('company_id');
                $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                $data['main_content'] = $this->load->view('attendance/addEditAttendance', $data, TRUE); 
                $this->load->view('userHome', $data);
            }else{
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['reference_no'] = $this->Common_model->getDataById($id, 'tbl_attendance')->reference_no;
                $data['attendance_details'] = $this->Common_model->getDataById($id, 'tbl_attendance');
                $company_id = $this->session->userdata('company_id');
                $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");;
                $data['main_content'] = $this->load->view('attendance/addEditAttendance', $data, TRUE); 
                $this->load->view('userHome', $data);
            }
            
        }
    }
     /**
     * check input In/Out
     * @access public
     * @return void
     * @param no
     */
    public function inOrOut(){
        $employee_id = $_GET['employee_id']; 
        $date = $_GET['date'];  

        $in_or_out = $this->db->query("select * from tbl_attendance where date=$date and employee_id=$employee_id and del_status='Live'")->row(); 

        if (!empty($in_or_out)) {
            echo "Out";
        }else{
            echo "In";
        }
    }
}
