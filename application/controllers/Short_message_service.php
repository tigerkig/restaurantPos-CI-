<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Short_message_service extends Cl_Controller {
 
    public function __construct() {
        parent::__construct(); 

        $this->load->model('Common_model'); 
        $this->load->model('Authentication_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();
        
        $this->load->library('setupfile');

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }

        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', lang('please_click_green_button'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }

        $getAccessURL = $this->uri->segment(1);
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

    public function send(){
    	$this->setupfile->send("8801812391633", "Hello there this is message");
    }

    public function smsService(){ 
        $data = array(); 
        $data['main_content'] = $this->load->view('shortMessageService/smsService', $data, TRUE);
        $this->load->view('userHome', $data);
    }

    public function sendSMS($type=''){
        $data = array(); 
        $data['type'] = $type;

        $credentials = $this->Authentication_model->getSMSInformation(1);
        require(APPPATH.'libraries/Textlocal.php');

        $textlocal = new Textlocal($credentials->email_address, $credentials->password);

        try {
            $result = $textlocal->getBalance(); 
            $data['balance'] = $result['sms'];
        } catch (Exception $e) {
            $data['balance'] = "<span>Connection is not properly established</span>";
        } 

        $today = date('Y-m-d');
        if ($this->input->post('submit')) { 

            if (empty($credentials->email_address)) {
                $this->session->set_flashdata('exception_2', 'Please configure SMS first');
                redirect('Short_message_service/smsService');
            }

            $this->form_validation->set_rules('outlet_name', lang('outlet_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('message', lang('message'), 'required|max_length[200]');
            if ($type == "Test") {
                $this->form_validation->set_rules('number',lang('number'), 'required|max_length[50]'); 
            }
            if ($this->form_validation->run() == TRUE) { 

                $sender = $this->input->post($this->security->xss_clean('outlet_name'));
                $message = $this->input->post($this->security->xss_clean('message')); 
                $numbers = array($this->input->post($this->security->xss_clean('number')));  
 
                if ($type == 'test') { 
                    try {
                        $result = $textlocal->getBalance(); 
                        $data['balance'] = $result['sms'];
                    } catch (Exception $e) { 
                        $this->session->set_flashdata('exception_2', 'Connection is not properly established');
                        redirect('Short_message_service/smsService');
                    }  

                    try {
                        $result = $textlocal->sendSms($numbers, $message, $sender); 
                        $this->session->set_flashdata('exception', 'SMS has been sent successfully!');
                    } catch (Exception $e) {
                        die('Error: ' . $e->getMessage());
                    }
                }else{ 

                    if ($type == 'birthday') {
                        $sms_count = $this->db->query("select * from tbl_customers where `date_of_birth`='". $today."' and concat('',phone * 1) = phone")->result();
                    }elseif ($type =='anniversary') {
                        $sms_count = $this->db->query("select * from tbl_customers where `date_of_anniversary`='". $today."' and concat('',phone * 1) = phone")->result();
                    }elseif ($type =='custom') {
                        $sms_count = $this->db->query("select * from tbl_customers where concat('',phone * 1) = phone")->result();
                    }  

                    if (empty($sms_count)) {
                        $this->session->set_flashdata('exception_2', 'No customer has birthday or anniverysary today or no customer found with valid phone number!');
                        redirect('Short_message_service/smsService');
                    }

                    try {
                        $result = $textlocal->getBalance(); 
                        $data['balance'] = $result['sms'];
                    } catch (Exception $e) { 
                        $this->session->set_flashdata('exception_2', 'Connection is not properly established');
                        redirect('Short_message_service/smsService');
                    }  

                    foreach ($sms_count as $value) {  
                        try {
                            $result = $textlocal->sendSms($numbers, $message, $sender); 
                            $this->session->set_flashdata('exception', 'SMS has been sent successfully!');
                        } catch (Exception $e) {
                            $this->session->set_flashdata('exception_2', 'Connection is not properly established!');
                            die('Error: ' . $e->getMessage());
                        }
                    }
                } 
 
                redirect('Short_message_service/smsService');
            } else {
                $day = '';
                $outlet_name = $this->session->userdata('outlet_name');

                if ($type == 'birthday') {
                    $day = "Birthday";
                }elseif ($type =='anniversary') {
                    $day = "Anniversary";
                }  

                if ($type == 'birthday' || $type == 'anniversary') {
                    $data['message'] = "Wishing you Happy $day from $outlet_name. Please come to our restaurant and enjoy discount in your special day.";
                }else{
                    $data['message'] = "";
                } 

                $data['outlet_name'] = $outlet_name;

                $today = date('Y-m-d');

                if ($type == 'birthday') {
                    $data['sms_count'] = $this->db->query("select * from tbl_customers where `date_of_birth`='". $today."' and concat('',phone * 1) = phone")->result();
                }elseif ($type =='anniversary') {
                    $data['sms_count'] = $this->db->query("select * from tbl_customers where `date_of_anniversary`='". $today."' and concat('',phone * 1) = phone")->result();
                }elseif($type =='custom'){
                    $data['sms_count'] = $this->db->query("select * from tbl_customers where concat('',phone * 1) = phone")->result();
                }    
                
                if ($type == 'balance') {
                    $data['main_content'] = $this->load->view('shortMessageService/checkBalance', $data, TRUE); 
                }else{ 
                    $data['main_content'] = $this->load->view('shortMessageService/sendSMS', $data, TRUE);
                } 
                $this->load->view('userHome', $data); 
            }
        }else{
            $day = '';
            $outlet_name = $this->session->userdata('outlet_name');

            if ($type == 'birthday') {
                $day = "Birthday";
            }elseif ($type =='anniversary') {
                $day = "Anniversary";
            }  

            if ($type == 'birthday' || $type == 'anniversary') {
                $data['message'] = "Wishing you Happy $day from $outlet_name. Please come to our restaurant and enjoy discount in your special day.";
            }else{
                $data['message'] = "";
            } 

            $data['outlet_name'] = $outlet_name;

            $today = date('Y-m-d');

            if ($type == 'birthday') {
                $data['sms_count'] = $this->db->query("select * from tbl_customers where `date_of_birth`='". $today."' and concat('',phone * 1) = phone")->result();
            }elseif ($type =='anniversary') {
                $data['sms_count'] = $this->db->query("select * from tbl_customers where `date_of_anniversary`='". $today."' and concat('',phone * 1) = phone")->result();
            }elseif($type =='custom'){
                $data['sms_count'] = $this->db->query("select * from tbl_customers where concat('',phone * 1) = phone")->result();
            }    

            if ($type == 'balance') {
                $data['main_content'] = $this->load->view('shortMessageService/checkBalance', $data, TRUE); 
            }else{ 
                $data['main_content'] = $this->load->view('shortMessageService/sendSMS', $data, TRUE);
            } 
            $this->load->view('userHome', $data); 
        }
    } 

}

