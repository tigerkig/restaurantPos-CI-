<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Inventory_model');
        $this->load->model('Common_model');
        $this->load->model('Sale_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');
    }

    public function index() {
        $is_valid = isset($_POST['is_valid']) && $_POST['is_valid']?$_POST['is_valid']:'';
        if($is_valid){
            $data['is_valid'] = $is_valid;
            $data['base_url'] = base_url();
            echo json_encode($data);
        }else{
            if ($this->session->has_userdata('user_id')) {
                redirect('Authentication/userProfile');
            }else{
                $this->load->view('authentication/login');
            }
        }
    }
    public function contactUs() {
        $first_url = ucfirst($this->uri->segment(1));
        if($first_url=="Authentication" || $first_url=="authentication"){
            $this->load->view('authentication/login');
        }else{
            if(isServiceAccessOnlyLogin('sGmsJaFJE')){
                $data = array();
                $data['main_content'] = $this->load->view('saas/frontend/contact_us', $data, TRUE);
                $this->load->view('saas/frontend/layout', $data);
            }else{
                $this->load->view('authentication/login');
            }
        }
    }

    public function landing() {
        $this->load->view('saas/landing');
    }
    public function singup() {
        $data['pricingPlans'] = $this->Common_model->getAllByTable("tbl_pricing_plans");
        $this->load->view('saas/signup',$data);
    }
    public function sendEmail() {
        $name = $_POST['name']." - ".$_POST['phone'];
        $subject = $_POST['subject'];
        $txt = $name."<br>".$_POST['message'];
        $company = getMainCompany();
        $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
        $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
        $return = sendEmailOnly($txt,$send_to,$attached='',$name,$subject);
        $data['msg'] = lang('wrong_send_email');
        $data['status'] = false;
        if($return){
            $data['msg'] = lang('success_send_email');
            $data['status'] = true;
        }
        echo json_encode($data);
    }

    /**
     * check login info
     * @access public
     * @return void
     * @param no
     */
    public function loginCheck() {
        if($this->input->post('submit') != 'submit'){
            redirect("Authentication/index");
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

                    if($company_info){
                        if($company_info->is_active==1){
                            $is_block = "No";
                            $is_payment_clear = 'Yes';

                            if(!isServiceAccess($user_information->id,$user_information->company_id) && $user_information->company_id!=1){
                                $is_block = $company_info->is_block_all_user;
                                $is_payment_clear = 'No';

                                $due_payment = $this->Common_model->getPaymentInfo($company_info->id);
                                if($due_payment){
                                    if($due_payment->payment_date){
                                        $access_day = $company_info->access_day;
                                        if(!$access_day){
                                            $access_day = 0;
                                        }
                                        $today = date("Y-m-d",strtotime('today'));
                                        $end_date = date("Y-m-d",strtotime($due_payment->payment_date." +".$access_day."day"));
                                        if($today<$end_date){
                                            $is_payment_clear = "Yes";
                                        }
                                    }
                                }else{
                                    $access_day = $company_info->access_day;
                                    if(!$access_day){
                                        $access_day = 0;
                                    }
                                    $today = date("Y-m-d",strtotime('today'));
                                    $end_date = date("Y-m-d",strtotime($company_info->created_date." +".$access_day."day"));
                                    if($today<$end_date){
                                        $is_payment_clear = "Yes";
                                    }
                                }
                            }
                            if($is_payment_clear=="Yes" && $is_block=="No"){

                                if ($user_information->role == 'Admin') {
                                    $menu_access_information = $this->Authentication_model->getMenuAccessInformation('');
                                }else{
                                    $menu_access_information = $this->Authentication_model->getMenuAccessInformation($user_information->id);
                                }

                                $menu_access_container = array();
                                if (isset($menu_access_information)) {
                                    foreach ($menu_access_information as $value) {
                                        if($value->controller_name=="POSChecker"){
                                            array_push($menu_access_container, ucfirst("POS"));
                                        }else{
                                            array_push($menu_access_container, ucfirst($value->controller_name));
                                        }
                                    }
                                }

                                //Menu access information
                                $primary_session_data['menu_access'] = $menu_access_container;
                                $this->session->set_userdata($primary_session_data);


                                // echo "string";

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
                                $login_session['active_menu_tmp'] = 4;

                                //Company Information


                                //Set session

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
                                $company_info_session['export_daily_sale'] =$company_info->export_daily_sale;
                                $company_info_session['printing'] =$company_info->printing;
                                $company_info_session['service_type'] =$company_info->service_type;
                                $company_info_session['service_amount'] =$company_info->service_amount;

                                if(str_rot13($company_info->language_manifesto)!="eriutoeri"):
                                    $company_info_session['default_waiter'] =$company_info->default_waiter;
                                endif;
                                $company_info_session['default_payment'] =$company_info->default_payment;
                                $company_info_session['default_payment'] =$company_info->default_payment;
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
                                            redirect('Authentication/index');
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
                                        $outlet_session['has_kitchen'] = $outlet_info->has_kitchen;
                                        if(str_rot13($company_info->language_manifesto)=="eriutoeri"):
                                            $outlet_session['default_waiter'] =$outlet_info->default_waiter;
                                        endif;
                                    endif;
                                    $this->session->set_userdata($outlet_session);
                                    //for saas module
                                    if(isServiceAccess('','','')){
                                        $all_companies = $this->Common_model->getServiceCompaniesYes();
                                        if($all_companies){
                                            foreach ($all_companies as $value){
                                                $due_payment = $this->Common_model->getPaymentInfo($value->id);
                                                if($due_payment){
                                                    if($due_payment->payment_date){
                                                        $access_day = $value->access_day;
                                                        if(!$access_day){
                                                            $access_day = 0;
                                                        }
                                                        $today = date("Y-m-d",strtotime('today'));
                                                        $end_date = date("Y-m-d",strtotime($due_payment->payment_date." +".$access_day."day"));
                                                        if($today>$end_date){
                                                            $data['payment_clear'] = "No";
                                                            $this->Common_model->updateInformation($data, $value->id, "tbl_companies");
                                                        }
                                                    }
                                                }else{
                                                    $access_day = $value->access_day;
                                                    if(!$access_day){
                                                        $access_day = 0;
                                                    }
                                                    $today = date("Y-m-d",strtotime('today'));
                                                    $end_date = date("Y-m-d",strtotime($value->created_date." +".$access_day."day"));
                                                    if($today>$end_date){
                                                        $data['payment_clear'] = "No";
                                                        $this->Common_model->updateInformation($data, $value->id, "tbl_companies");
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    //attendance insert
                                    $today = date("Y-m-d",strtotime('today'));
                                    $check_and_return_ref = getRefAttendance($today,$user_information->id);
                                    if($check_and_return_ref){
                                        $attendance= array();
                                        $attendance['reference_no'] = $check_and_return_ref;
                                        $attendance['date'] = $today;
                                        $attendance['employee_id'] = $user_information->id;
                                        $attendance['in_time'] = date("H:i:s");
                                        $attendance['out_time'] = "00:00:00";
                                        $attendance['user_id'] = $user_information->id;
                                        $attendance['company_id'] = $company_info->id;
                                        $this->Common_model->insertInformation($attendance, "tbl_attendance");
                                    }

                                    if(str_rot13($company_info->language_manifesto)=="fgjgldkfg"){
                                        redirect("Dashboard/dashboard");
                                        // redirect("Sale/POS");
                                    }
                                    if ($user_information->role == 'Admin') {
                                        redirect("Outlet/outlets");
                                    } else {
                                        redirect("Outlet/outlets");
                                    }
                                }else{
                                    $this->session->set_userdata($login_session);
                                    $this->session->set_userdata($company_info_session);

                                    //for saas module
                                    if(isServiceAccess('','','')){
                                        $all_companies = $this->Common_model->getServiceCompaniesYes();

                                        if($all_companies){
                                            foreach ($all_companies as $value){
                                                $due_payment = $this->Common_model->getPaymentInfo($value->id);
                                                if($due_payment){
                                                    if($due_payment->payment_date){
                                                        $access_day = $value->access_day;
                                                        if(!$access_day){
                                                            $access_day = 0;
                                                        }
                                                        $today = date("Y-m-d",strtotime('today'));
                                                        $end_date = date("Y-m-d",strtotime($due_payment->payment_date." +".$access_day."day"));
                                                        if($today>$end_date){
                                                            $data['payment_clear'] = "No";
                                                            $this->Common_model->updateInformation($data, $value->id, "tbl_companies");
                                                        }
                                                    }
                                                }else{
                                                    $access_day = $value->access_day;
                                                    if(!$access_day){
                                                        $access_day = 0;
                                                    }
                                                    $today = date("Y-m-d",strtotime('today'));
                                                    $end_date = date("Y-m-d",strtotime($value->created_date." +".$access_day."day"));
                                                    if($today>$end_date){
                                                        $data['payment_clear'] = "No";
                                                        $this->Common_model->updateInformation($data, $value->id, "tbl_companies");
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    //attendance insert
                                    $today = date("Y-m-d",strtotime('today'));
                                    $check_and_return_ref = getRefAttendance($today,$user_information->id);
                                    if($check_and_return_ref){
                                        $attendance= array();
                                        $attendance['reference_no'] = $check_and_return_ref;
                                        $attendance['date'] = $today;
                                        $attendance['employee_id'] = $user_information->id;
                                        $attendance['in_time'] = date("H:i:s");
                                        $attendance['out_time'] = "00:00:00";
                                        $attendance['user_id'] = $user_information->id;
                                        $attendance['company_id'] = $company_info->id;
                                        $this->Common_model->insertInformation($attendance, "tbl_attendance");
                                    }

                                    if(str_rot13($company_info->language_manifesto)=="fgjgldkfg"){
                                        redirect("Sale/POS");
                                    }
                                    if ($user_information->role == 'Admin') {
                                        redirect("Outlet/outlets");
                                    } else {
                                        redirect("Outlet/outlets");
                                    }
                                }
                            }else{
                                if($is_block=="Yes"){
                                    $this->session->set_flashdata('exception_1', lang('block_tmp_err'));
                                    redirect('Authentication/index');
                                }else if($is_payment_clear=="No"){
                                    $this->session->set_flashdata('exception_1', lang('payment_not_clear_err'));
                                    redirect('Authentication/index');
                                }
                            }
                        }else{
                            $this->session->set_flashdata('exception_1', lang('company_not_set_err1'));
                            redirect('Authentication/index');
                        }

                    }else{
                        $this->session->set_flashdata('exception_1', lang('company_not_set_err'));
                        redirect('Authentication/index');
                    }
                } else {
                    $this->session->set_flashdata('exception_1', lang('user_not_active'));
                    redirect('Authentication/index');
                }
            } else {

                $this->session->set_flashdata('exception_1', lang('incorrect_email_password'));
                redirect('Authentication/index');
            }
        } else {
            $this->load->view('authentication/login');
        }
    }
    /**
     * check payment clear or not
     * @access public
     * @return void
     * @param no
     */
    public function paymentNotClear() {
        if (!$this->session->has_userdata('customer_id')) {
            redirect('Authentication/index');
        }
        $this->load->view('authentication/paymentNotClear');
    }
    /**
     * user profile data
     * @access public
     * @return void
     * @param no
     */
    public function userProfile() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if($this->session->userdata('role') == 'Kitchen User'){
            redirect("Kitchen/panel");
        }
        if($this->session->userdata('role') == 'Bar User'){
            redirect("Bar/panel");
        }
        if($this->session->userdata('role') == 'Waiter User'){
            redirect("Waiter/panel");
        }
        if($this->session->userdata('role') == 'POS User'){
            redirect("Sale/POS");
        }
        $login_session['active_menu_tmp'] = 1;
        $this->session->set_userdata($login_session);
        $data = array();
        $data['main_content'] = $this->load->view('authentication/userProfile', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * change password
     * @access public
     * @return void
     * @param no
     */
    public function changePassword() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if ($this->input->post('submit') == 'submit') {
            $this->form_validation->set_rules('old_password',lang('old_password'), 'required|max_length[50]');
            $this->form_validation->set_rules('new_password', lang('new_password'), 'required|max_length[50]|min_length[6]');
            if ($this->form_validation->run() == TRUE) {
                $old_password =htmlspecialchars($this->input->post($this->security->xss_clean('old_password')));
                $user_id = $this->session->userdata('user_id');

                $password_check = $this->Authentication_model->passwordCheck(md5($old_password), $user_id);

                if ($password_check) {
                    $new_password =htmlspecialchars($this->input->post($this->security->xss_clean('new_password')));

                    $this->Authentication_model->updatePassword(md5($new_password), $user_id);

                    mail($this->session->userdata['email_address'], "Change Password", "Your new password is : " . $new_password);

                    $this->session->set_flashdata('exception',lang('password_changed'));
                    redirect('Authentication/changePassword');
                } else {
                    $this->session->set_flashdata('exception_1',lang('old_password_not_match'));
                    redirect('Authentication/changePassword');
                }
            } else {
                $data = array();
                $data['main_content'] = $this->load->view('authentication/changePassword', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['main_content'] = $this->load->view('authentication/changePassword', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * forgot password
     * @access public
     * @return void
     * @param no
     */
    public function forgotPassword() {
        $this->load->view('authentication/forgotPassword');
    }
    /**
     * send auto password through email
     * @access public
     * @return void
     * @param no
     */
    public function sendAutoPassword() {
        if ($this->input->post('submit') == 'submit') {
            $this->form_validation->set_rules('email_address', lang('email_address'), 'required|callback_checkEmailAddressExistance');
            if ($this->form_validation->run() == TRUE) {
                $email_address =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));

                $user_details = $this->Authentication_model->getAccountByMobileNo($email_address);

                $user_id = $user_details->id;

                $auto_generated_password = mt_rand(100000, 999999);

                $this->Authentication_model->updatePassword($auto_generated_password, $user_id);

                //Send Password by Email
                $this->load->library('email');

                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $this->email->initialize($config);

                mail($email_address, "Change Password", "Your new password is : " . $auto_generated_password);

                $this->load->view('authentication/forgotPasswordSuccess');
            } else {
                $this->load->view('authentication/forgotPassword');
            }
        } else {
            $this->load->view('authentication/forgotPassword');
        }
    }
    /**
     * check email address that is exist or not
     * @access public
     * @return void
     * @param boolean
     */
    public function checkEmailAddressExistance() {
        $email_address =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));

        $checkEmailAddressExistance = $this->Authentication_model->getAccountByMobileNo($email_address);

        if (count($checkEmailAddressExistance) <= 0) {
            $this->form_validation->set_message('checkEmailAddressExistance', 'Email Address does not exist');
            return false;
        } else {
            return true;
        }
    }
    /**
     * logout from system
     * @access public
     * @return void
     * @param no
     */
    public function logOut() {
        //update attendance
        $user_id = $this->session->userdata('user_id');
        $today = date("Y-m-d",strtotime('today'));
        $check_data = checkAttendance($today,$user_id);
        if($check_data){
            $attendance= array();
            $attendance['out_time'] = date("H:i:s");
            $this->Common_model->updateInformation($attendance, $check_data->id, "tbl_attendance");
        }


        //User Information 
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('full_name');
        $this->session->unset_userdata('phone');
        $this->session->unset_userdata('email_address');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('outlet_name');
        $this->session->unset_userdata('clicked_controller');
        $this->session->unset_userdata('clicked_method');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('company_id');
        $this->session->unset_userdata('printing');
        $this->session->unset_userdata('outlet_id');
        $this->session->unset_userdata('is_waiter');
        $this->session->unset_userdata('active_menu_tmp');
        $this->session->unset_userdata('has_kitchen');

        //Shop Information
        $this->session->unset_userdata('currency');
        $this->session->unset_userdata('zone_name');
        $this->session->unset_userdata('date_format');
        $this->session->unset_userdata('business_name');
        $this->session->unset_userdata('address');
        $this->session->unset_userdata('website');
        $this->session->unset_userdata('currency_position');
        $this->session->unset_userdata('precision');
        $this->session->unset_userdata('default_customer');
        $this->session->unset_userdata('default_waiter');
        $this->session->unset_userdata('default_payment');
        $this->session->unset_userdata('outlet_code');
        $this->session->unset_userdata('default_payment');
        $this->session->unset_userdata('invoice_footer');
        $this->session->unset_userdata('invoice_logo');
        $this->session->unset_userdata('language_manifesto');
        $this->session->unset_userdata('pre_or_post_payment');
        $this->session->unset_userdata('collect_tax');
        $this->session->unset_userdata('tax_title');
        $this->session->unset_userdata('tax_registration_no');
        $this->session->unset_userdata('tax_is_gst');
        $this->session->unset_userdata('state_code');
        $this->session->unset_userdata('menu_access');
        $this->session->unset_userdata('is_waiter');
        $this->session->unset_userdata('service_type');
        $this->session->unset_userdata('service_amount');
        redirect('Authentication/index');
    }
    /**
     * sms setting data
     * @access public
     * @return void
     * @param int
     */
    public function SMSSetting($id='') {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('email_address',lang('email_address'), "required|max_length[50]");
            $this->form_validation->set_rules('password',lang('password'), "required|max_length[50]");
            if ($this->form_validation->run() == TRUE) {
                $sms_info = array();
                $sms_info['email_address'] =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));
                $sms_info['password'] =htmlspecialchars($this->input->post($this->security->xss_clean('password')));

                $data_json['sms_details'] = json_encode($sms_info);
                $this->Common_model->updateInformation($data_json, $id, "tbl_companies");
                $this->session->set_flashdata('exception', lang('update_success'));
                redirect('Authentication/SMSSetting/'.$id);
            } else {
                $data = array();
                $data['sms_information'] = $this->Authentication_model->getSMSInformation($company_id);
                  $data['company_id'] = ($company_id);
                $data['main_content'] = $this->load->view('authentication/sms_setting', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['sms_information'] = $this->Authentication_model->getSMSInformation($company_id);
              $data['company_id'] = ($company_id);
            $data['main_content'] = $this->load->view('authentication/sms_setting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * white label data
     * @access public
     * @return void
     * @param int
     */
    public function whiteLabel($id = '') {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            /*form validation check*/
            $this->form_validation->set_rules('site_name', lang('site_name'), 'required|max_length[300]');
            $this->form_validation->set_rules('footer', lang('footer'), 'required|max_length[300]');
            $this->form_validation->set_rules('system_logo', lang('logo'), 'callback_validate_system_logo');


            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['site_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('site_name')));
                $data['footer'] =htmlspecialchars($this->input->post($this->security->xss_clean('footer')));

                if ($_FILES['system_logo']['name'] != "") {
                    $data['system_logo'] = $this->session->userdata('system_logo');;
                    $this->session->unset_userdata('system_logo');
                    @unlink("./images/".$this->input->post($this->security->xss_clean('old_system_logo')));
                }else{
                    $data['system_logo'] =htmlspecialchars($this->input->post($this->security->xss_clean('old_system_logo')));
                }

                $json_data['white_label'] = json_encode($data);

                $this->Common_model->updateInformation($json_data, $id, "tbl_companies");

                redirect('Authentication/whiteLabel');
            } else {
                $data = array();
                $data['main_content'] = $this->load->view('authentication/whiteLabel', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['main_content'] = $this->load->view('authentication/whiteLabel', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * change profile data
     * @access public
     * @return void
     * @param int
     */
    public function changeProfile($id = '') {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        if ($id != '') {
            $user_details = $this->Common_model->getDataById($id, "tbl_users");
        }

        if ($this->input->post('submit')) {

            if ($id != '') {
                $post_email_address =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));
                $existing_email_address = $user_details->email_address;
                if ($post_email_address != $existing_email_address) {
                    $this->form_validation->set_rules('email_address', lang('email_address'), "required|max_length[50]|is_unique[tbl_users.email_address]");
                } else {
                    $this->form_validation->set_rules('email_address',lang('email_address'), "required|max_length[50]");
                }
            } else {
                $this->form_validation->set_rules('email_address', lang('email_address'), "required|max_length[50]|is_unique[tbl_users.email_address]");
            }
            $this->form_validation->set_rules('full_name', lang('full_name'), "required|max_length[50]");
            $this->form_validation->set_rules('phone', lang('phone'), "required|max_length[50]");

            if ($this->form_validation->run() == TRUE) {
                $user_info = array();
                $user_info['full_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('full_name')));
                $user_info['email_address'] =htmlspecialchars($this->input->post($this->security->xss_clean('email_address')));
                $user_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $this->Common_model->updateInformation($user_info, $id, "tbl_users");
                $this->session->set_flashdata('exception', lang('update_success'));

                $this->session->set_userdata('full_name', $user_info['full_name']);
                $this->session->set_userdata('phone', $user_info['phone']);
                $this->session->set_userdata('email_address', $user_info['email_address']);

                redirect('Authentication/changeProfile');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                    $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                    $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['profile_info'] = $this->Authentication_model->getProfileInformation();
                $data['main_content'] = $this->load->view('authentication/changeProfile', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
    /**
     * valideted system logo data
     * @access public
     * @return boolean
     * @param no
     */
    public function validate_system_logo() {

        if ($_FILES['system_logo']['name'] != "") {
            $config['upload_path'] = './images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '2048';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("system_logo")) {
                $upload_info = $this->upload->data();
                $system_logo = $upload_info['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = './images/' . $system_logo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 230;
                $config['height'] = 50;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->session->set_userdata('system_logo', $system_logo);
            } else {
                $this->form_validation->set_message('validate_system_logo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }
    /**
     * set language on session
     * @access public
     * @return void
     * @param no
     */
    public function setlanguage($value){
    $id=$this->session->userdata('user_id');
    $language=$value;
    if ($language == "") {
        $language = "english";
    }
    $data['language']=$language;
    $this->session->set_userdata('language', $language);
    $this->db->WHERE('id',$id);
    $this->db->update('tbl_users',$data);
    redirect($_SERVER["HTTP_REFERER"]);
   }

    /**
     * set language on session from POS screen
     * @access public
     * @return void
     * @param string
     */
    public function setlanguagePOS($lng){
    $id=$this->session->userdata('user_id');
    $language=$lng;
    if ($language == "") {
        $language = "english";
    }
    $data['language']=$language;
    $this->session->set_userdata('language', $language);
    $this->db->WHERE('id',$id);
    $this->db->update('tbl_users',$data);
    redirect("Sale/POS");
   }

    /**
     * sorting main menu
     * @access public
     * @return object
     * @param no
     */
    public function sortingMainMenu() {
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $menus = $this->input->get('menus');
        $i = 1;
        foreach ($menus as $key=>$value){
            $data = array();
            $data['order_by'] = $i;
            $this->Common_model->updateInformation($data,$menus[$key], "tbl_admin_user_menus");
            $i++;
        }
        echo json_encode('success');
    }
    /**
     * sorting main menu
     * @access public
     * @return object
     * @param no
     */
    public function signupCompany() {
        $is_trail = 1;
        $company_info= array();
        $company_info['business_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('business_name')));
        $company_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
        $company_info['address'] =htmlspecialchars($this->input->post($this->security->xss_clean('address')));
        $company_info['plan_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('plan_id')));
        $company_info['del_status'] = "Deleted";

        $main_company = getMainCompany();
        $company_info['currency_position'] = isset($main_company->currency_position) && $main_company->currency_position?$main_company->currency_position:'';
        $company_info['precision'] = isset($main_company->precision) && $main_company->precision?$main_company->precision:'';
        $company_info['payment_settings'] = isset($main_company->payment_settings) && $main_company->payment_settings?$main_company->payment_settings:'';
        $company_info['sms_setting_check'] = isset($main_company->sms_setting_check) && $main_company->sms_setting_check?$main_company->sms_setting_check:'';
        $company_info['tax_title'] = isset($main_company->tax_title) && $main_company->tax_title?$main_company->tax_title:'';
        $company_info['tax_registration_no'] = isset($main_company->tax_registration_no) && $main_company->tax_registration_no?$main_company->tax_registration_no:'';
        $company_info['tax_is_gst'] = isset($main_company->tax_is_gst) && $main_company->tax_is_gst?$main_company->tax_is_gst:'';
        $company_info['state_code'] = isset($main_company->state_code) && $main_company->state_code?$main_company->state_code:'';
        $company_info['tax_setting'] = isset($main_company->tax_setting) && $main_company->tax_setting?$main_company->tax_setting:'';
        $company_info['tax_string'] = isset($main_company->tax_string) && $main_company->tax_string?$main_company->tax_string:'';
        $company_info['sms_enable_status'] = isset($main_company->sms_enable_status) && $main_company->sms_enable_status?$main_company->sms_enable_status:'';
        $company_info['sms_details'] = isset($main_company->sms_details) && $main_company->sms_details?$main_company->sms_details:'';
        $company_info['smtp_enable_status'] = isset($main_company->smtp_enable_status) && $main_company->smtp_enable_status?$main_company->smtp_enable_status:'';
        $company_info['smtp_details'] = isset($main_company->smtp_details) && $main_company->smtp_details?$main_company->smtp_details:'';
        $company_info['whatsapp_share_number'] = isset($main_company->whatsapp_share_number) && $main_company->whatsapp_share_number?$main_company->whatsapp_share_number:'';
        $company_info['language_manifesto'] = isset($main_company->language_manifesto) && $main_company->language_manifesto?$main_company->language_manifesto:'';
        $company_info['white_label'] = isset($main_company->white_label) && $main_company->white_label?$main_company->white_label:'';
        $company_info['date_format'] = isset($main_company->date_format) && $main_company->date_format?$main_company->date_format:'';
        $company_info['zone_name'] = isset($main_company->zone_name) && $main_company->zone_name?$main_company->zone_name:'';
        $company_info['currency'] = isset($main_company->currency) && $main_company->currency?$main_company->currency:'';
        $company_info['pre_or_post_payment'] = "Post Payment";
        $company_info['is_active'] = 2;
        /*getting active random code*/
        $active_code = uniqid();
        $company_info['active_code'] = $active_code;
        $company_info['invoice_footer'] = isset($main_company->invoice_footer) && $main_company->invoice_footer?$main_company->invoice_footer:'';
        $company_info['collect_tax'] = isset($main_company->collect_tax) && $main_company->collect_tax?$main_company->collect_tax:'';

            $plan_id = $this->input->post($this->security->xss_clean('plan_id'));
            $return_data = array();
            $return_data['id'] = '';
            $return_data['status'] = false;
            $return_data['free_status'] = false;
            $checkExisting = $this->Common_model->checkExistingAdmin(htmlspecialchars($this->input->post($this->security->xss_clean('email'))));
            $id = '';
            if($checkExisting){
                $return_data['msg'] = lang('user_exist_error');
            }else{
                if($plan_id){
                    $plan = $this->Common_model->getDataById($plan_id, "tbl_pricing_plans");
                    if($plan){
                        $company_info['monthly_cost'] = $plan->monthly_cost;
                        $company_info['number_of_maximum_users'] = $plan->number_of_maximum_users;
                        $company_info['number_of_maximum_outlets'] = $plan->number_of_maximum_outlets;
                        $company_info['number_of_maximum_invoices'] = $plan->number_of_maximum_invoices;
                        if($plan->trail_days==111){
                            $company_info['access_day'] = 30;
                            $id = $this->Common_model->insertInformation($company_info, "tbl_companies");
                            if($id){
                                $return_data['status'] = true;
                                $return_data['id']  = $id;
                            }
                            $return_data['free_status'] = false;
                            $this->session->set_userdata('is_front_signup',"Yes");
                        }else{
                            $company_info['payment_clear'] = "Yes";
                            $company_info['del_status'] = "Live";
                            $company_info['access_day'] = $plan->trail_days;
                            $id = $this->Common_model->insertInformation($company_info, "tbl_companies");
                            if($id){
                                $return_data['status'] = true;
                                $return_data['id']  = $id;
                            }
                            $return_data['free_status'] = true;
                            $return_data['msg'] = "Signup successful, Please check your email inbox/spam to verify your email and activate your account";
                            $is_trail++;
                            //send success message for supper admin
                            $company = getMainCompany();
                            $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
                            $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
                            $business =htmlspecialchars($this->input->post($this->security->xss_clean('business_name')));
                            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
                            sendEmailOnly($txt,$send_to,$attached='',$business,"Restaurant SignUp Success");

                            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.
            For active your account- <a href="'.base_url().'authentication/active_company/'.$active_code.'">Active Now</a>';
                            //send success message for restaurant admin
                            $send_to = htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                            sendEmailOnly($txt,$send_to,$attached='',$business,"Restaurant SignUp Success");
                        }
                    }else{
                        $return_data['msg'] = lang('no_plan_select');
                    }
                }else{
                    $return_data['msg'] = lang('no_plan_select');
                }
            }

            if($id){
                //update admin info
                $admin_data = array();
                $admin_data['full_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('admin_name')));
                $admin_data['phone'] = htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $admin_data['email_address'] = htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                $admin_data['password'] = md5(($this->input->post($this->security->xss_clean('password'))));
                $admin_data['designation'] = "Admin User";
                $admin_data['role'] = "Admin";
                $admin_data['company_id'] = $id;
                $admin_data['will_login'] = "Yes";
                if($is_trail==1){
                    $admin_data['del_status'] = "Deleted";
                }
                $this->Common_model->insertInformation($admin_data, "tbl_users");
            }

            echo json_encode($return_data);
    }
    public function subscribeEmail() {
        $email_send_subscribe = $_POST['email_send_subscribe'];
        $data['msg'] = "You did subscribe already!";
        $data['status'] = false;
        $checkExistingAccount = $this->Common_model->checkExistingAccountByEmail($email_send_subscribe);
        if(!$checkExistingAccount){
            $data_db['email'] = $email_send_subscribe;
            $this->Common_model->insertInformation($data_db, "tbl_customers");
            $data['msg'] = "Subscription has been completed!";
            $data['status'] = true;
        }
        echo json_encode($data);
    }
    /**
     * get all information of a sale
     * @access public
     * @return object
     * @param int
     */
    public function get_all_information_of_a_sale($sales_id){
        $sales_information = $this->Sale_model->getSaleBySaleId($sales_id);

        $sales_information[0]->sub_total = getAmtP(isset($sales_information[0]->sub_total) && $sales_information[0]->sub_total?$sales_information[0]->sub_total:0);
        $sales_information[0]->paid_amount = getAmtP(isset($sales_information[0]->paid_amount) && $sales_information[0]->paid_amount?$sales_information[0]->paid_amount:0);
        $sales_information[0]->due_amount = getAmtP(isset($sales_information[0]->due_amount) && $sales_information[0]->due_amount?$sales_information[0]->due_amount:0);
        $sales_information[0]->vat = getAmtP(isset($sales_information[0]->vat) && $sales_information[0]->vat?$sales_information[0]->vat:0);
        $sales_information[0]->total_payable = getAmtP(isset($sales_information[0]->total_payable) && $sales_information[0]->total_payable?$sales_information[0]->total_payable:0);
        $sales_information[0]->total_item_discount_amount = getAmtP(isset($sales_information[0]->total_item_discount_amount) && $sales_information[0]->total_item_discount_amount?$sales_information[0]->total_item_discount_amount:0);
        $sales_information[0]->sub_total_with_discount = getAmtP(isset($sales_information[0]->sub_total_with_discount) && $sales_information[0]->sub_total_with_discount?$sales_information[0]->sub_total_with_discount:0);
        $sales_information[0]->sub_total_discount_amount = getAmtP(isset($sales_information[0]->sub_total_discount_amount) && $sales_information[0]->sub_total_discount_amount?$sales_information[0]->sub_total_discount_amount:0);
        $sales_information[0]->total_discount_amount = getAmtP(isset($sales_information[0]->total_discount_amount) && $sales_information[0]->total_discount_amount?$sales_information[0]->total_discount_amount:0);
        $sales_information[0]->delivery_charge = (isset($sales_information[0]->delivery_charge) && $sales_information[0]->delivery_charge?$sales_information[0]->delivery_charge:0);
        $this_value = $sales_information[0]->sub_total_discount_value;
        $disc_fields = explode('%',$this_value);
        $discP = isset($disc_fields[1]) && $disc_fields[1]?$disc_fields[1]:'';
        if ($discP == "") {
        } else {
            $sales_information[0]->sub_total_discount_value = getAmtP(isset($sales_information[0]->sub_total_discount_value) && $sales_information[0]->sub_total_discount_value?$sales_information[0]->sub_total_discount_value:0);
        }


        $items_by_sales_id = $this->Sale_model->getAllItemsFromSalesDetailBySalesId($sales_id);

        foreach($items_by_sales_id as $single_item_by_sale_id){
            $modifier_information = $this->Sale_model->getModifiersBySaleAndSaleDetailsId($sales_id,$single_item_by_sale_id->sales_details_id);
            $single_item_by_sale_id->modifiers = $modifier_information;
        }
        $sales_details_objects = $items_by_sales_id;
        $sales_details_objects[0]->menu_price_without_discount = getAmtP(isset($sales_details_objects[0]->menu_price_without_discount) && $sales_details_objects[0]->menu_price_without_discount?$sales_details_objects[0]->menu_price_without_discount:0);
        $sales_details_objects[0]->menu_price_with_discount = getAmtP(isset($sales_details_objects[0]->menu_price_with_discount) && $sales_details_objects[0]->menu_price_with_discount?$sales_details_objects[0]->menu_price_with_discount:0);
        $sales_details_objects[0]->menu_unit_price = getAmtP(isset($sales_details_objects[0]->menu_unit_price) && $sales_details_objects[0]->menu_unit_price?$sales_details_objects[0]->menu_unit_price:0);
        $sales_details_objects[0]->menu_vat_percentage = getAmtP(isset($sales_details_objects[0]->menu_vat_percentage) && $sales_details_objects[0]->menu_vat_percentage?$sales_details_objects[0]->menu_vat_percentage:0);
        $sales_details_objects[0]->discount_amount = getAmtP(isset($sales_details_objects[0]->discount_amount) && $sales_details_objects[0]->discount_amount?$sales_details_objects[0]->discount_amount:0);

        $this_value = $sales_details_objects[0]->menu_discount_value;
        $disc_fields = explode('%',$this_value);
        $discP = isset($disc_fields[1]) && $disc_fields[1]?$disc_fields[1]:'';
        if ($discP == "") {
        } else {
            $sales_details_objects[0]->menu_discount_value = getAmtP(isset($sales_details_objects[0]->menu_discount_value) && $sales_information[0]->menu_discount_value?$sales_details_objects[0]->menu_discount_value:0);
        }

        $sale_object = $sales_information[0];
        $sale_object->items = $sales_details_objects;
        $sale_object->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($sales_id);
        return $sale_object;
    }
    public function printSaleByAjax(){
        $sale_id = $this->input->post('sale_id');

        if($sale_id){
            $company_id = $this->session->userdata('company_id');
            $outlet_id = $this->session->userdata('outlet_id');
            $company = $this->Common_model->getDataById($company_id, "tbl_companies");
            $outlet = $this->Common_model->getDataById($outlet_id, "tbl_outlets");

            if(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice){
                if($company->printing_invoice=="direct_print"){
                    $this->load->library('escpos');
                    $this->escpos->load(getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:''));
                }
                $data = array();
                $data['print_type'] = "invoice";
                $data['logo'] = $company->invoice_logo;
                $data['store_name'] = $outlet->outlet_name;
                $data['address'] = $outlet->address;
                $data['phone'] = $outlet->phone;
                $data['collect_tax'] = $company->collect_tax;
                $data['tax_registration_no'] = $company->tax_registration_no;
                $data['invoice_footer'] = $company->invoice_footer;
                //printer config
                $printer = getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:'');
                $data['type'] = $printer->type;
                $data['printer_ip_address'] = $printer->printer_ip_address;
                $data['printer_port'] = $printer->printer_port;
                $data['path'] = $printer->path;
                $data['characters_per_line'] = $printer->characters_per_line;
                $data['profile_'] = $printer->profile_;

                $sale = $this->get_all_information_of_a_sale($sale_id);
                $data['date'] = date($company->date_format, strtotime($sale->sale_date));
                $data['time_inv'] = date('h:i A',strtotime($sale->order_time));

                $order_type = '';
                if($sale->order_type == 1){
                    $order_type = 'Dine In';
                }elseif($sale->order_type == 2){
                    $order_type = 'Take Away';
                }elseif($sale->order_type == 3){
                    $order_type = 'Delivery';
                }

                $data['sale_type'] = $order_type;
                $data['sales_associate'] = $sale->user_name;
                $data['customer_name'] = (isset($sale->customer_name) && $sale->customer_name?$sale->customer_name:'---');
                $data['customer_address'] = '';
                if($sale->customer_address!=NULL  && $sale->customer_address!=""){
                    $data['customer_address'] = (isset($sale->customer_address) && $sale->customer_address?$sale->customer_address:'---');
                }
                $data['waiter_name'] = '';
                if($sale->order_type=='1'){
                    $data['waiter_name']= $sale->waiter_name;
                }
                $data['customer_table'] = '';
                  if($sale->tables_booked){
                    $html_table = '';
                    foreach ($sale->tables_booked as $key1=>$val){
                        $html_table.= escape_output($val->table_name);
                        if($key1 < (sizeof($sale->tables_booked) -1)){
                            $html_table.= ", ";
                        }
                    }
                      $data['customer_table'] = $html_table;
                }

                $items = "\n";
                $printer_tmp = getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:'');
                $count  = 1;
                $totalItems = 0;
                foreach($sale->items as $r=>$value){
                    $totalItems++;
                    $menu_unit_price = getAmtP($value->menu_unit_price);
                    $items .= printText(("#".$count." ".escape_output($value->menu_name)), $printer_tmp->characters_per_line)."\n";
                    $items .= printLine("   ".($value->qty." x ".$menu_unit_price. ":  ". ((getAmt($value->menu_price_without_discount)))), $printer_tmp->characters_per_line, ' ')."\n";
                    $count++;
                    if(count($value->modifiers)>0){
                        $items .= printText(("".lang('modifier').":"), $printer_tmp->characters_per_line)."\n";
                                $l = 1;
                                $modifier_price = 0;
                                foreach($value->modifiers as $modifier){
                                    if($l==count($value->modifiers)){
                                        $items .= printText((escape_output($modifier->name)), $printer_tmp->characters_per_line)."\n";
                                    }else{
                                        $items .= printText((escape_output($modifier->name).", "), $printer_tmp->characters_per_line)."\n";
                                    }
                                    $modifier_price+=$modifier->modifier_price;
                                    $l++;
                                }
                            $items .= printLine("   ".lang('modifier')." ".lang('price'). ":  ". escape_output(getAmt($modifier_price)), $printer_tmp->characters_per_line, ' ')."\n";
                    }
                }
                $order_type = '';
                if($sale->order_type == 1){
                    $order_type = 'A';
                }elseif($sale->order_type == 2){
                    $order_type = 'B';
                }elseif($sale->order_type == 3){
                    $order_type = 'C';
                }
                $data['sale_no_p'] = $order_type." ".$sale->sale_no;
                $data['date_format_p'] = $company->date_format;;
                $data['items'] = $items;
                $currency = $company->currency;
                $totals = "";
                $totals.= printLine("".lang("Total_Item_s"). ": " .  $totalItems, $printer_tmp->characters_per_line)."\n";
                if($sale->sub_total && $sale->sub_total!="0.00"):
                    $totals.= printLine(lang("sub_total"). ": " .(getAmt($sale->sub_total)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->total_discount_amount && $sale->total_discount_amount!="0.00"):
                    $totals.= printLine(lang("Disc_Amt_p"). ": " .(getAmt($sale->total_discount_amount)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->delivery_charge && $sale->delivery_charge!="0.00"):
                    $totals.= printLine(lang("Service_Delivery_Charge"). ": " .(($sale->delivery_charge)), $printer_tmp->characters_per_line)."\n";
                endif;

                if ($company->collect_tax=='Yes' && ($sale->sale_vat_objects!=NULL)):
                    foreach(json_decode($sale->sale_vat_objects) as $single_tax) {
                        if ($single_tax->tax_field_amount && $single_tax->tax_field_amount != "0.00"):
                            $items .= printLine("   " . escape_output($single_tax->tax_field_type) . ":  " . escape_output(getAmt($single_tax->tax_field_amount)), $printer_tmp->characters_per_line, ' ') . "\n";
                        endif;
                    }
                endif;

                if($sale->total_payable && $sale->total_payable!="0.00"):
                    $totals.= printLine(lang("total_payable"). ": " .(getAmt($sale->total_payable)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->paid_amount && $sale->paid_amount!="0.00"):
                    $totals.= printLine(lang("paid_amount"). ": " .(getAmt($sale->paid_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->due_amount && $sale->due_amount!="0.00"):
                    $totals.= printLine(lang("due_amount"). ": " .(getAmt($sale->due_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->given_amount && $sale->given_amount!="0.00"):
                    $totals.= printLine(lang("given_amount"). ": " .(getAmt($sale->given_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->change_amount && $sale->change_amount!="0.00"):
                    $totals.= printLine(lang("change_amount"). ": " .(getAmt($sale->change_amount)), $printer_tmp->characters_per_line)."\n";
                endif;
                $data['totals'] = $totals;
                if($company->printing_invoice=="direct_print"){
                    $this->escpos->print_receipt($data);
                }else{
                    $return_data['printer_server_url'] = $company->print_server_url_invoice;
                    $return_data['content_data'] = $data;
                    echo json_encode($return_data);
                }
            }

        }


    }
    public function printSaleBillByAjax(){
        $sale_id = $this->input->post('sale_id');
        if($sale_id){
            $company_id = $this->session->userdata('company_id');
            $outlet_id = $this->session->userdata('outlet_id');
            $company = $this->Common_model->getDataById($company_id, "tbl_companies");
            $outlet = $this->Common_model->getDataById($outlet_id, "tbl_outlets");
            if(isset($company->receipt_printer_bill) && $company->receipt_printer_bill){
                if($company->printing_bill=="direct_print"){
                    $this->load->library('escpos');
                    $this->escpos->load(getPrinterInfo(isset($company->receipt_printer_bill) && $company->receipt_printer_bill?$company->receipt_printer_bill:''));
                }
                $data = array();
                $data['print_type'] = "bill";
                $data['logo'] = $company->invoice_logo;
                $data['store_name'] = $outlet->outlet_name;
                $data['address'] = $outlet->address;
                $data['phone'] = $outlet->phone;
                $data['collect_tax'] = $company->collect_tax;
                $data['tax_registration_no'] = $company->tax_registration_no;
                $data['invoice_footer'] = $company->invoice_footer;
                //printer config
                $printer = getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:'');
                $data['type'] = $printer->type;
                $data['printer_ip_address'] = $printer->printer_ip_address;
                $data['printer_port'] = $printer->printer_port;
                $data['path'] = $printer->path;
                $data['characters_per_line'] = $printer->characters_per_line;
                $data['profile_'] = $printer->profile_;

                $sale = $this->get_all_information_of_a_sale($sale_id);
                $data['date'] = date($company->date_format, strtotime($sale->sale_date));
                $data['time_inv'] = date('h:i A',strtotime($sale->order_time));
                $order_type = '';
                if($sale->order_type == 1){
                    $order_type = 'Dine In';
                }elseif($sale->order_type == 2){
                    $order_type = 'Take Away';
                }elseif($sale->order_type == 3){
                    $order_type = 'Delivery';
                }

                $data['sale_type'] = $order_type;

                $data['sales_associate'] = $sale->user_name;
                $data['customer_name'] = (isset($sale->customer_name) && $sale->customer_name?$sale->customer_name:'---');
                $data['customer_address'] = '';
                if($sale->customer_address!=NULL  && $sale->customer_address!=""){
                    $data['customer_address'] = (isset($sale->customer_address) && $sale->customer_address?$sale->customer_address:'---');
                }
                $data['waiter_name'] = '';
                if($sale->order_type=='1'){
                    $data['waiter_name']= $sale->waiter_name;
                }
                $data['customer_table'] = '';

                if($sale->tables_booked){
                    $html_table = '';
                    foreach ($sale->tables_booked as $key1=>$val){
                        $html_table.= escape_output($val->table_name);
                        if($key1 < (sizeof($sale->tables_booked) -1)){
                            $html_table.= ", ";
                        }
                    }
                    $data['customer_table'] = $html_table;
                }


                $items = "\n";
                $printer_tmp = getPrinterInfo(isset($company->receipt_printer_bill) && $company->receipt_printer_bill?$company->receipt_printer_bill:'');
                $count  = 1;
                $totalItems = 0;
                $sale = $this->get_all_information_of_a_sale($sale_id);

                foreach($sale->items as $r=>$value){
                    $totalItems++;
                    $menu_unit_price = getAmtP($value->menu_unit_price);
                    $items .= printText(("#".$count." ".escape_output($value->menu_name)), $printer_tmp->characters_per_line)."\n";
                    $items .= printLine("   ".($value->qty." x ".$menu_unit_price. ":  ". ((getAmt($value->menu_price_without_discount)))), $printer_tmp->characters_per_line, ' ')."\n";
                    $count++;
                    if(count($value->modifiers)>0){
                        $items .= printText(("".lang('modifier').":"), $printer_tmp->characters_per_line)."\n";
                        $l = 1;
                        $modifier_price = 0;
                        foreach($value->modifiers as $modifier){
                            if($l==count($value->modifiers)){
                                $items .= printText((escape_output($modifier->name)), $printer_tmp->characters_per_line)."\n";
                            }else{
                                $items .= printText((escape_output($modifier->name).", "), $printer_tmp->characters_per_line)."\n";
                            }
                            $modifier_price+=$modifier->modifier_price;
                            $l++;
                        }
                        $items .= printLine("   ".lang('modifier')." ".lang('price'). ":  ". escape_output(getAmt($modifier_price)), $printer_tmp->characters_per_line, ' ')."\n";
                    }
                }
                $order_type = '';
                if($sale->order_type == 1){
                    $order_type = 'A';
                }elseif($sale->order_type == 2){
                    $order_type = 'B';
                }elseif($sale->order_type == 3){
                    $order_type = 'C';
                }
                $data['sale_no_p'] = $sale->sale_no;
                $data['date_format_p'] = $company->date_format;;
                $data['items'] = $items;
                $currency = $company->currency;
                $totals = "";
                $totals.= printLine("".lang("Total_Item_s"). ": " .  $totalItems, $printer_tmp->characters_per_line)."\n";
                if($sale->sub_total && $sale->sub_total!="0.00"):
                    $totals.= printLine(lang("sub_total"). ": " .(getAmt($sale->sub_total)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->total_discount_amount && $sale->total_discount_amount!="0.00"):
                    $totals.= printLine(lang("Disc_Amt_p"). ": " .(getAmt($sale->total_discount_amount)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->delivery_charge && $sale->delivery_charge!="0.00"):
                    $totals.= printLine(lang("Service_Delivery_Charge"). ": " .(($sale->delivery_charge)), $printer_tmp->characters_per_line)."\n";
                endif;

                if ($company->collect_tax=='Yes' && ($sale->sale_vat_objects!=NULL)):
                    foreach(json_decode($sale->sale_vat_objects) as $single_tax) {
                        if ($single_tax->tax_field_amount && $single_tax->tax_field_amount != "0.00"):
                            $items .= printLine("   " . escape_output($single_tax->tax_field_type) . ":  " . escape_output(getAmt($single_tax->tax_field_amount)), $printer_tmp->characters_per_line, ' ') . "\n";
                        endif;
                    }
                endif;

                if($sale->total_payable && $sale->total_payable!="0.00"):
                    $totals.= printLine(lang("total_payable"). ": " .(getAmt($sale->total_payable)), $printer_tmp->characters_per_line)."\n";
                endif;
                if($sale->paid_amount && $sale->paid_amount!="0.00"):
                    $totals.= printLine(lang("paid_amount"). ": " .(getAmt($sale->paid_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->due_amount && $sale->due_amount!="0.00"):
                    $totals.= printLine(lang("due_amount"). ": " .(getAmt($sale->due_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->given_amount && $sale->given_amount!="0.00"):
                    $totals.= printLine(lang("given_amount"). ": " .(getAmt($sale->given_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                if($sale->change_amount && $sale->change_amount!="0.00"):
                    $totals.= printLine(lang("change_amount"). ": " .(getAmt($sale->change_amount)), $printer_tmp->characters_per_line)."\n";
                endif;

                $data['totals'] = $totals;
                if($company->printing_bill=="direct_print"){
                    $this->escpos->print_receipt_bill($data);
                }else{
                    $return_data['printer_server_url'] = $company->print_server_url_bill;
                    $return_data['content_data'] = $data;
                    echo json_encode($return_data);
                }
            }

        }


    }
    public function printSaleTempBotByAjax(){
        $sale_id = $this->input->post('sale_id');
        if($sale_id){
            $company_id = $this->session->userdata('company_id');
            $outlet_id = $this->session->userdata('outlet_id');
            $company = $this->Common_model->getDataById($company_id, "tbl_companies");
            $outlet = $this->Common_model->getDataById($outlet_id, "tbl_outlets");
            if(isset($company->receipt_printer_bot) && $company->receipt_printer_bot){
                if($company->printing_bot=="direct_print"){
                    $this->load->library('escpos');
                    $this->escpos->load(getPrinterInfo(isset($company->receipt_printer_bot) && $company->receipt_printer_bot?$company->receipt_printer_bot:''));
                }
                $data = array();
                $data['print_type'] = "bot";
                $data['logo'] = $company->invoice_logo;
                $data['store_name'] = lang('BOT');
                $data['address'] = $outlet->address;
                $data['phone'] = $outlet->phone;
                $data['collect_tax'] = $company->collect_tax;
                $data['tax_registration_no'] = $company->tax_registration_no;
                $data['invoice_footer'] = $company->invoice_footer;
                $items = "\n";
                $printer_tmp = getPrinterInfo(isset($company->receipt_printer_bot) && $company->receipt_printer_bot?$company->receipt_printer_bot:'');
                $count  = 1;
                $totalItems = 0;
                $sale  = $this->Sale_model->get_temp_kot($sale_id);
                $this->db->delete('tbl_temp_kot', array('id' => $sale_id));

                $kot_info = $sale->temp_kot_info;
                $kot_info = json_decode($kot_info);
                //printer config
                $printer = getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:'');
                $data['type'] = $printer->type;
                $data['printer_ip_address'] = $printer->printer_ip_address;
                $data['printer_port'] = $printer->printer_port;
                $data['path'] = $printer->path;
                $data['characters_per_line'] = $printer->characters_per_line;
                $data['profile_'] = $printer->profile_;

                $data['date'] = date($company->date_format, strtotime($kot_info->order_date));
                $data['time_inv'] = date('h:i A',strtotime($kot_info->order_date));

                $data['sale_type'] = $kot_info->order_type;

                $data['customer_name'] = (isset($kot_info->customer_name) && $kot_info->customer_name?$kot_info->customer_name:'---');
                $data['customer_address'] = '';
                if($kot_info->customer_address!=NULL  && $kot_info->customer_address!=""){
                    $data['customer_address'] = (isset($kot_info->customer_address) && $kot_info->customer_address?$kot_info->customer_address:'---');
                }
                $data['waiter_name'] = '';
                if($kot_info->waiter_name){
                    $data['waiter_name']= $kot_info->waiter_name;
                }
                $data['customer_table'] = '';
                if($kot_info->table_name){
                    $data['customer_table'] = $kot_info->table_name;
                }
                foreach($kot_info->items as $r=>$value){
                    $totalItems+=$value->tmp_qty;
                    if($value->tmp_qty):
                    $items .= printText(("#".$count." ".escape_output($value->bot_item_name)), $printer_tmp->characters_per_line)."\n";
                    $items .= printLine("   ".($value->tmp_qty), $printer_tmp->characters_per_line, ' ')."\n";
                    $count++;
                    endif;

                }
                $order_type = '';
                $data['sale_no_p'] = $order_type." ".$kot_info->order_number;
                $data['date_format_p'] = $company->date_format;
                $data['items'] = $items;
                $totals = "";
                $totals.= printLine("".lang("Total_Item_s"). ": " .  $totalItems, $printer_tmp->characters_per_line)."\n";
                $data['totals'] = $totals;
                if($company->printing_bot=="direct_print"){
                    $this->escpos->print_receipt_bot($data);
                }else{
                    $return_data['printer_server_url'] = $company->print_server_url_bot;
                    $return_data['content_data'] = $data;
                    echo json_encode($return_data);
                }
            }

        }
    }
    public function printSaleTempKotByAjax(){
        $sale_id = $this->input->post('sale_id');
        if($sale_id){
            $company_id = $this->session->userdata('company_id');
            $outlet_id = $this->session->userdata('outlet_id');
            $company = $this->Common_model->getDataById($company_id, "tbl_companies");
            $outlet = $this->Common_model->getDataById($outlet_id, "tbl_outlets");
            if(isset($company->receipt_printer_kot) && $company->receipt_printer_kot){
                if($company->printing_kot=="direct_print"){
                    $this->load->library('escpos');
                    $this->escpos->load(getPrinterInfo(isset($company->receipt_printer_kot) && $company->receipt_printer_kot?$company->receipt_printer_kot:''));
                }
                $data = array();
                $data['print_type'] = "kot";
                $data['logo'] = $company->invoice_logo;
                $data['store_name'] = lang('KOT');
                $data['address'] = $outlet->address;
                $data['phone'] = $outlet->phone;
                $data['collect_tax'] = $company->collect_tax;
                $data['tax_registration_no'] = $company->tax_registration_no;
                $data['invoice_footer'] = $company->invoice_footer;
                $items = "\n";
                $printer_tmp = getPrinterInfo(isset($company->receipt_printer_kot) && $company->receipt_printer_kot?$company->receipt_printer_kot:'');
                $count  = 1;
                $totalItems = 0;
                $sale  = $this->Sale_model->get_temp_kot($sale_id);
                $this->db->delete('tbl_temp_kot', array('id' => $sale_id));

                $kot_info = $sale->temp_kot_info;
                $kot_info = json_decode($kot_info);
                //printer config
                $printer = getPrinterInfo(isset($company->receipt_printer_invoice) && $company->receipt_printer_invoice?$company->receipt_printer_invoice:'');
                $data['type'] = $printer->type;
                $data['printer_ip_address'] = $printer->printer_ip_address;
                $data['printer_port'] = $printer->printer_port;
                $data['path'] = $printer->path;
                $data['characters_per_line'] = $printer->characters_per_line;
                $data['profile_'] = $printer->profile_;

                $data['date'] = date($company->date_format, strtotime($kot_info->order_date));
                $data['time_inv'] = date('h:i A',strtotime($kot_info->order_date));
                $data['sale_type'] = $kot_info->order_type;
                $data['customer_name'] = (isset($kot_info->customer_name) && $kot_info->customer_name?$kot_info->customer_name:'---');
                $data['customer_address'] = '';
                if($kot_info->customer_address!=NULL  && $kot_info->customer_address!=""){
                    $data['customer_address'] = (isset($kot_info->customer_address) && $kot_info->customer_address?$kot_info->customer_address:'---');
                }
                $data['waiter_name'] = '';
                if($kot_info->waiter_name){
                    $data['waiter_name']= $kot_info->waiter_name;
                }
                $data['customer_table'] = '';
                if($kot_info->table_name){
                    $data['customer_table'] = $kot_info->table_name;
                }

                foreach($kot_info->items as $r=>$value){
                    $totalItems+=$value->tmp_qty;
                    if($value->tmp_qty):
                        $items .= printText(("#".$count." ".escape_output($value->kot_item_name)), $printer_tmp->characters_per_line)."\n";
                        $items .= printLine("   ".($value->tmp_qty), $printer_tmp->characters_per_line, ' ')."\n";
                        $count++;
                    endif;

                }
                $order_type = '';
                $data['sale_no_p'] = $order_type." ".$kot_info->order_number;
                $data['date_format_p'] = $company->date_format;
                $data['items'] = $items;
                $totals = "";
                $totals.= printLine("".lang("Total_Item_s"). ": " .  $totalItems, $printer_tmp->characters_per_line)."\n";

                $data['totals'] = $totals;
                if($company->printing_kot=="direct_print"){
                    $this->escpos->print_receipt_kot($data);
                }else{
                    $return_data['printer_server_url'] = $company->print_server_url_kot;
                    $return_data['content_data'] = $data;
                    echo json_encode($return_data);
                }
            }

        }


    }
    public function checkQty()
    {
        $curr_qty = htmlspecialchars($this->input->post('curr_qty'));
        $product_id = htmlspecialchars($this->input->post('item_id'));
        $value = $this->Inventory_model->checkInventory($product_id);
        $totalStock = $value->total_transfer_plus_2  -  $value->total_transfer_minus_2 - $value->sale_total;
        if ($curr_qty <= $totalStock) {
            echo json_encode('available->'.$totalStock);
        } else {
            echo json_encode('');
        }
    }
    public function active_company($code)
    {
        $companies_info = $this->Common_model->getCustomDataByParams("active_code",$code, "tbl_companies");
        if(isset($companies_info->active_code) && $companies_info->active_code==$code && $companies_info->is_active!=1){
            $data['is_active'] = 1;
            $this->Common_model->updateInformation($data, $companies_info->id, "tbl_companies");
            $this->session->set_flashdata('exception',"Your account successfully activate");
        }else if(isset($companies_info->active_code) && $companies_info->active_code==$code && $companies_info->is_active==1){
            $this->session->set_flashdata('exception_1',"Your account already active");
        }else{
            $this->session->set_flashdata('exception_1',"You clicked URL not valid");
        }
        redirect('Authentication/index');
    }
    /**
     * update order success after payment
     * @access public
     * @return string
     */
    public function updateOrderSuccess() {
        $razorpay_payment_id = htmlspecialchars($this->input->post('razorpay_payment_id'));
        $last_added_company_id = htmlspecialchars($this->input->post('last_added_company_id'));
        $total_amount = htmlspecialchars($this->input->post('total_amount'));
        $return_data['status'] = false;
        $return_data['msg'] = "Something is wrong with processing your payment";

        if($razorpay_payment_id){
            //update success order row
            $data = array();
            $data['del_status'] = "Live";
            $data['payment_clear'] = "Yes";
            $this->Common_model->updateInformation($data, $last_added_company_id, "tbl_companies");

            $data = array();
            $data['del_status'] = "Live";
            $this->db->where('company_id', $last_added_company_id);
            $this->db->update('tbl_users', $data);

            //payment history
            $data = array();
            $data['payment_type'] = "Rezorpay";
            $data['company_id'] = $last_added_company_id;
            $data['amount'] = $total_amount;
            $data['payment_date'] = date("Y-m-d",strtotime('today'));
            $data['trans_id'] = $razorpay_payment_id;
            $this->Common_model->insertInformation($data, "tbl_payment_histories");

            $return_data['status'] = true;
            $return_data['msg'] = "Payment successfully accept, Please check your email inbox/spam for active your account";

            //send email
            //send success message for supper admin
            $company = getMainCompany();
            $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
            $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
            $companies_info = $this->Common_model->getDataById($last_added_company_id, "tbl_companies");

            $business = $companies_info->business_name;
            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");

            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.
            For active your account- <a target="_blank" href="'.base_url().'authentication/active_company/'.$companies_info->active_code.'">Active Now</a>';
            //send success message for restaurant admin
            $restaurantAdminUser = $this->Common_model->getRestaurantAdminUser($last_added_company_id);
            $send_to = $restaurantAdminUser->email_address;
            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");
        }
        echo json_encode($return_data);
    }
}
