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
  # This is Authentication Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    public function waiter_login() {
        if (is_mobile_access()) {
            $valid_txt = isset($_GET['txt_vld']) && $_GET['txt_vld']?$_GET['txt_vld']:'';
            if(str_rot13($valid_txt) == "Ty3qq5fq"){
                $this->load->view('authentication/login_waiter');
            }else{
                redirect("access-denied");
            }
        }else{
            redirect("access-denied");
        }
    }
    public function accessDenied() {
        echo "<title>".lang('desktop_access_denied')."</title>";
        echo lang('desktop_access_denied');exit;
    }

    /**
     * check login info
     * @access public
     * @return void
     * @param no
     */
    public function waiter_login_check() {
        if($this->input->post('submit') != 'submit'){
            redirect("waiter-login");
        }
        if (is_mobile_access()) {
            $valid_txt = isset($_POST['txt_vld']) && $_POST['txt_vld']?$_POST['txt_vld']:'';
            if(str_rot13($valid_txt) == "Ty3qq5fq"){

            }else{
                redirect("access-denied");
            }
        }

        $this->form_validation->set_rules('email_address', lang('email_address'), 'required|max_length[50]');
        $this->form_validation->set_rules('password', lang('password'), "required|max_length[25]");
        if ($this->form_validation->run() == TRUE) {
            $email_address =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));
            $password = md5($this->input->post($this->security->xss_clean('password')));
            $user_information = $this->Authentication_model->getUserInformation($email_address, $password);
            //If user exists
            if ($user_information) {

                //If the user is Active
                if ($user_information->active_status == 'Active') {
                    $company_info = $this->Authentication_model->getCompanyInformation($user_information->company_id);
                    if ($user_information->role == 'Admin') {
                        $menu_access_information = $this->Authentication_model->getMenuAccessInformation('');
                    }else{
                        $menu_access_information = $this->Authentication_model->getMenuAccessInformation($user_information->id);
                    }
                    $menu_access_container = array();
                    if (isset($menu_access_information)) {
                        foreach ($menu_access_information as $value) {
                            array_push($menu_access_container, ucfirst($value->controller_name));
                        }
                    }

                    //Menu access information
                    $primary_session_data['menu_access'] = $menu_access_container;
                    $this->session->set_userdata($primary_session_data);

                    $login_session = array();
                    //User Information
                    $login_session['user_id'] = $user_information->id;
                    $login_session['language'] = $user_information->language;
                    $login_session['full_name'] = $user_information->full_name;
                    $login_session['phone'] = $user_information->phone;
                    $login_session['email_address'] = $user_information->email_address;
                    $login_session['role'] = $user_information->role;
                    $login_session['company_id'] = $user_information->company_id;
                    $login_session['session_outlets'] = $user_information->outlets;

                    $company_info_session = array();
                    $company_info_session['currency'] = $company_info->currency;
                    $company_info_session['zone_name'] = $company_info->zone_name;
                    $company_info_session['date_format'] = $company_info->date_format;
                    $company_info_session['business_name'] = $company_info->business_name;
                    $company_info_session['address'] = $company_info->address;
                    $company_info_session['website'] = $company_info->website;
                    $company_info_session['currency_position'] =$company_info->currency_position;
                    $company_info_session['precision'] =$company_info->precision;
                    $company_info_session['default_customer'] =$company_info->default_customer;

                    if(str_rot13($company_info->language_manifesto)!="eriutoeri"):
                        $company_info_session['default_waiter'] =$company_info->default_waiter;
                    endif;
                    $company_info_session['default_payment'] =$company_info->default_payment;
                    $company_info_session['default_payment'] =$company_info->default_payment;
                    $company_info_session['print_format'] = $company_info->print_format;
                    $company_info_session['invoice_footer'] = $company_info->invoice_footer;
                    $company_info_session['invoice_logo'] = $company_info->invoice_logo;
                    $company_info_session['language_manifesto'] = $company_info->language_manifesto;
                    $company_info_session['pre_or_post_payment'] = $company_info->pre_or_post_payment;
                    $company_info_session['collect_tax'] = $company_info->collect_tax;
                    $company_info_session['tax_title'] = $company_info->tax_title;
                    $company_info_session['tax_registration_no'] = $company_info->tax_registration_no;
                    $company_info_session['tax_is_gst'] = $company_info->tax_is_gst;
                    $company_info_session['state_code'] = $company_info->state_code;

                    $outlet_info = $this->db->query("SELECT * FROM tbl_outlets WHERE del_status='Live'")->row();
                    if(str_rot13($company_info->language_manifesto)=="fgjgldkfg"){
                        if ($user_information->role != 'Admin') {
                            if($outlet_info->active_status=="inactive"){
                                $this->session->set_flashdata('exception_1', lang('outlet_not_active'));
                                redirect("waiter-login");
                            }
                        }
                        $this->session->set_userdata($login_session);
                        $this->session->set_userdata($company_info_session);

                        $outlet_session = array();
                        if(isset($outlet_info) && $outlet_info):
                            $outlet_session['outlet_id'] = $outlet_info->id;
                            $outlet_session['outlet_name'] = $outlet_info->outlet_name;
                            $outlet_session['address'] = $outlet_info->address;
                            $outlet_session['phone'] = $outlet_info->phone;
                            $outlet_session['email'] = $outlet_info->email;
                            $outlet_session['outlet_code'] = $outlet_info->outlet_code;
                            if(str_rot13($company_info->language_manifesto)=="eriutoeri"):
                                $outlet_session['default_waiter'] =$outlet_info->default_waiter;
                            endif;
                        endif;
                        $this->session->set_userdata($outlet_session);
                        redirect("Sale/POSWaiter");
                    }else{
                        $this->session->set_userdata($login_session);
                        $this->session->set_userdata($company_info_session);
                        redirect("Sale/POSWaiter");
                    }
                } else {
                    $this->session->set_flashdata('exception_1', lang('user_not_active'));
                    redirect("waiter-login");
                }
            } else {
                $this->session->set_flashdata('exception_1', lang('incorrect_email_password'));
                redirect("waiter-login");
            }
        } else {
            $this->load->view('authentication/login_waiter');
        }
    }
}
