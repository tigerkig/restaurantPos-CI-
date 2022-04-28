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
  # This is Sass Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Pricing_plan_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

        if(!isServiceAccess('','','sGmsJaFJE')){
            redirect('Authentication/index');
        }
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }
     /**
     * Pricing Plans
     * @access public
     * @return void
     * @param no
     */
    public function pricingPlans() {
        $data = array();
        $data['pricingPlans'] = $this->Common_model->getAllByTable("tbl_pricing_plans");
        $data['main_content'] = $this->load->view('saas/pricingPlans', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * setting info
     * @access public
     * @return void
     * @param int
     */
    public function siteSetting($id = '') {
        $encrypted_id = $id;
        $company_id = $id = $outlet_id = $this->session->userdata('company_id');
        $language_manifesto = $this->session->userdata('language_manifesto');
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('site_name', lang('site_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('footer', lang('footer'), 'required|max_length[250]');
            $this->form_validation->set_rules('system_logo', lang('system_logo'), 'callback_validate_system_logo|max_length[500]');
            if ($this->form_validation->run() == TRUE) {
                $outlet_info = array();
                $outlet_info['site_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('site_name')));
                if ($_FILES['system_logo']['name'] != "") {
                    $outlet_info['system_logo'] = $this->session->userdata('system_logo');
                    $this->session->unset_userdata('system_logo');
                }else{
                    $outlet_info['system_logo'] =htmlspecialchars($this->input->post($this->security->xss_clean('system_logo_p')));
                }
                $outlet_info['footer'] =htmlspecialchars($this->input->post($this->security->xss_clean('footer')));
                $return['white_label']  = json_encode($outlet_info);

                $this->Common_model->updateInformation($return, $id, "tbl_companies");
                $this->session->set_flashdata('exception', lang('update_success'));

                $this->session->set_userdata($outlet_info);
                redirect('Service/siteSetting');
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
                $data['main_content'] = $this->load->view('saas/siteSetting', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['encrypted_id'] = $encrypted_id;
            $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
            $data['main_content'] = $this->load->view('saas/siteSetting', $data, TRUE);
            $this->load->view('userHome', $data);
        }

    }
    /**
     * validate invoice logo
     * @access public
     * @return string
     * @param boolean
     */
    public function validate_system_logo() {

        if ($_FILES['system_logo']['name'] != "") {
            $config['upload_path'] = './images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '1000';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("system_logo")) {

                $upload_info = $this->upload->data();

                $file_name = $upload_info['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = './images/' . $file_name;
                $config['maintain_ratio'] = false;
                $config['width'] = 230;
                $config['height'] = 50;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->session->set_userdata('system_logo', $file_name);
            } else {
                $this->form_validation->set_message('validate_system_logo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }
     /**
     * Pricing Plans
     * @access public
     * @return void
     * @param int
     * @param string
     */
    public function companies($company_id='',$status='') {
        $data = array();
        if($company_id){
            $data['outlets'] = $this->Common_model->getAllByCompanyId($company_id,"tbl_outlets");
            $data['main_content'] = $this->load->view('saas/company_outlets', $data, TRUE);
        }else{
            $data['companies'] = $this->Common_model->getServiceCompanies();
            $data['main_content'] = $this->load->view('saas/companies', $data, TRUE);
        }

        $this->load->view('userHome', $data);
    }
     /**
     * payment History
     * @access public
     * @return void
     * @param no
     */
    public function paymentHistory() {
        $company_id = htmlspecialchars($this->input->post($this->security->xss_clean('company_id')));
        $data = array();
        $data['companies'] = $this->Common_model->getServiceCompanies();
        $data['payment_histories'] = $this->Common_model->getPaymentHistory($company_id);
        $data['main_content'] = $this->load->view('saas/payment_histories', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * Pricing Plans
     * @access public
     * @return void
     * @param no
     */
    public function blockAllUser($company_id='',$status='') {
        $company_info['is_block_all_user'] = $status;
        $this->Common_model->updateInformation($company_info, $company_id, "tbl_companies");
        if($status=="Yes"){
            $this->session->set_flashdata('exception', lang('block_msg'));
        }else{
            $this->session->set_flashdata('exception', lang('unblock_msg'));
        }

        redirect('Service/companies');
    }
     /**
     * delete Pricing Plan
     * @access public
     * @return void
     * @param int
     */
    public function deletePricingPlan($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_pricing_plans");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Service/landingPage');
    }
     /**
     * delete Pricing Plan
     * @access public
     * @return void
     * @param int
     */
    public function deletePayment($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_payment_histories");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Service/paymentHistory');
    }
     /**
     * delete Pricing Plan
     * @access public
     * @return void
     * @param int
     */
    public function deleteCompany($id) {
        if($id==1):
            redirect('Service/companies');
        endif;

        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChange($id, "tbl_companies");
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Service/companies');
    }
     /**
     * delete Customer Review
     * @access public
     * @return void
     * @param int
     */
    public function deleteCustomerReview($id) {
        $company = getMainCompany();
        $data_customer_reviewers = json_decode($company->customer_reviewers);
            unset($data_customer_reviewers[$id]);
        $data_customer_reviewers_return['customer_reviewers'] = json_encode($data_customer_reviewers);
        $this->session->set_flashdata('exception', lang('delete_success'));
        $this->Common_model->updateInformation($data_customer_reviewers_return, 1, "tbl_companies");
        redirect('Service/landingPage');
    }
    /**
     * setting info
     * @access public
     * @return void
     * @param int
     */
    public function counter($id = '') {

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('restaurants', lang('restaurants'), 'required|max_length[50]');
            $this->form_validation->set_rules('users', lang('users'), 'required|max_length[50]');
            $this->form_validation->set_rules('reference', lang('reference'), 'required|max_length[50]');
            $this->form_validation->set_rules('daily_transactions', lang('daily_transactions'), 'required|max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $data['restaurants'] =htmlspecialchars($this->input->post($this->security->xss_clean('restaurants')));
                $data['users'] =htmlspecialchars($this->input->post($this->security->xss_clean('users')));
                $data['reference'] =htmlspecialchars($this->input->post($this->security->xss_clean('reference')));
                $data['daily_transactions'] =htmlspecialchars($this->input->post($this->security->xss_clean('daily_transactions')));
                $return_data['counter_details'] = json_encode($data);
                $this->session->set_flashdata('exception', lang('update_success'));
                $this->Common_model->updateInformation($return_data, 1, "tbl_companies");
                redirect('Service/landingPage');
            } else {
                redirect('Service/landingPage');
            }
        } else {
            $data = array();
            $data['is_counter'] = "Yes";
            $data['pricingPlans'] = $this->Common_model->getAllByTable("tbl_pricing_plans");
            $data['main_content'] = $this->load->view('saas/landing_page', $data, TRUE);
        }

    }
    /**
     * setting info
     * @access public
     * @return void
     * @param int
     */
    public function socialLink($id = '') {

        if ($this->input->post('submit')) {
            $data['facebook'] =htmlspecialchars($this->input->post($this->security->xss_clean('facebook')));
            $data['twitter'] =htmlspecialchars($this->input->post($this->security->xss_clean('twitter')));
            $data['instagram'] =htmlspecialchars($this->input->post($this->security->xss_clean('instagram')));
            $data['youtube'] =htmlspecialchars($this->input->post($this->security->xss_clean('youtube')));
            $return_data['social_link_details'] = json_encode($data);
            $this->session->set_flashdata('exception', lang('update_success'));
            $this->Common_model->updateInformation($return_data, 1, "tbl_companies");
            redirect('Service/landingPage');
        } else {
            $data = array();
            $data['is_counter'] = "Yes";
            $data['pricingPlans'] = $this->Common_model->getAllByTable("tbl_pricing_plans");
            $data['main_content'] = $this->load->view('saas/landing_page', $data, TRUE);
        }

    }
     /**
     * add Pricing Plan
     * @access public
     * @return void
     * @param no
     */
    public function addPricingPlan($id='') {
        if ($this->input->post('submit')) {
            $payment_type = htmlspecialchars($this->input->post($this->security->xss_clean('payment_type')));
            $this->form_validation->set_rules('plan_name', "Plan Name", 'required');
            $this->form_validation->set_rules('monthly_cost', "Monthly Cost", 'required');
            $this->form_validation->set_rules('number_of_maximum_users', "Number of Maximum Users", 'required');
            $this->form_validation->set_rules('number_of_maximum_outlets', "Number of Maximum Outlets", 'required');
            $this->form_validation->set_rules('number_of_maximum_invoices', "Number of Maximum Invoices", 'required');
            $this->form_validation->set_rules('trail_days', "Trail Days", 'required');
            $this->form_validation->set_rules('payment_type', "Payment Type", 'required');
            if($payment_type==2){
                //$this->form_validation->set_rules('link_for_paypal', lang('link_for_paypal'), 'required');
                //$this->form_validation->set_rules('link_for_stripe', lang('link_for_stripe'), 'required');
            }
            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['plan_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('plan_name')));
                $data['monthly_cost'] = htmlspecialchars($this->input->post($this->security->xss_clean('monthly_cost')));
                $data['number_of_maximum_users'] = htmlspecialchars($this->input->post($this->security->xss_clean('number_of_maximum_users')));
                $data['number_of_maximum_outlets'] = htmlspecialchars($this->input->post($this->security->xss_clean('number_of_maximum_outlets')));
                $data['number_of_maximum_invoices'] = htmlspecialchars($this->input->post($this->security->xss_clean('number_of_maximum_invoices')));
                $data['trail_days'] = htmlspecialchars($this->input->post($this->security->xss_clean('trail_days')));
                $data['description'] =htmlspecialchars($this->input->post($this->security->xss_clean('description')));
                $data['is_recommended'] =htmlspecialchars($this->input->post($this->security->xss_clean('is_recommended')));
                $data['payment_type'] =htmlspecialchars($this->input->post($this->security->xss_clean('payment_type')));
                $data['link_for_paypal'] =htmlspecialchars($this->input->post($this->security->xss_clean('link_for_paypal')));
                $data['link_for_stripe'] =htmlspecialchars($this->input->post($this->security->xss_clean('link_for_stripe')));

                if ($id == "") {
                    $this->Common_model->insertInformation($data, "tbl_pricing_plans");
                    $this->session->set_flashdata('exception',lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($data, $id, "tbl_pricing_plans");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Service/pricingPlans');
            } else {
                if ($id == "") {
                    $data = array();
                     if($id){
                    $data['title'] = lang('Edit_Pricing_Plan');
                }else{
                    $data['title'] = lang('Add_Pricing_Plan');
                }
                    $data['main_content'] = $this->load->view('saas/addEditPricingPlan', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                     if($id){
                    $data['title'] = lang('Edit_Pricing_Plan');
                }else{
                    $data['title'] = lang('Add_Pricing_Plan');
                }
                    $data['encrypted_id'] = $id;
                    $data['pricingPlans'] = $this->Common_model->getDataById($id, "tbl_pricing_plans");
                    $data['main_content'] = $this->load->view('saas/addEditPricingPlan', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                if($id){
                    $data['title'] = lang('Edit_Pricing_Plan');
                }else{
                    $data['title'] = lang('Add_Pricing_Plan');
                }
                $data['main_content'] = $this->load->view('saas/addEditPricingPlan', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                if($id){
                    $data['title'] = lang('Edit_Pricing_Plan');
                }else{
                    $data['title'] = lang('Add_Pricing_Plan');
                }
                $data['encrypted_id'] = $id;
                $data['pricingPlans'] = $this->Common_model->getDataById($id, "tbl_pricing_plans");
                $data['main_content'] = $this->load->view('saas/addEditPricingPlan', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * add Customer Review
     * @access public
     * @return void
     * @param int
     */
    public function addCustomerReview($id='') {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', lang('name'), 'required');
            $this->form_validation->set_rules('designation',lang('designation'), 'required');
            $this->form_validation->set_rules('description', lang('description'), 'required');
            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $data['designation'] = htmlspecialchars($this->input->post($this->security->xss_clean('designation')));
                $data['description'] = htmlspecialchars($this->input->post($this->security->xss_clean('description')));
                $company = getMainCompany();
                $data_customer_reviewers = array();
                $data_customer_reviewers_return = array();
                if(isset($company) && $company){
                    if($id!=''){
                        $data_customer_reviewers = json_decode($company->customer_reviewers);
                        $tmp_value = json_encode($data);
                        $data_customer_reviewers[$id] = $tmp_value;
                        $data_customer_reviewers_return['customer_reviewers'] = json_encode($data_customer_reviewers);
                        $this->Common_model->updateInformation($data_customer_reviewers_return, 1, "tbl_companies");
                    }else{
                        $data_customer_reviewers = json_decode($company->customer_reviewers);
                        $data_customer_reviewers[] = json_encode($data);
                        $data_customer_reviewers_return['customer_reviewers'] = json_encode($data_customer_reviewers);
                        $this->Common_model->updateInformation($data_customer_reviewers_return, 1, "tbl_companies");
                    }

                }
                redirect('Service/landingPage');
            } else {
                if ($id == "") {
                    $data['encrypted_id'] = $id;
                    $data = array();
                     if($id){
                    $data['title'] = lang('edit_Customer_Review');
                }else{
                    $data['title'] = lang('add_Customer_Review');
                }
                    $data['main_content'] = $this->load->view('saas/addEditCustomerReview', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                     if($id){
                    $data['title'] = lang('edit_Customer_Review');
                }else{
                    $data['title'] = lang('add_Customer_Review');
                }
                    $data['encrypted_id'] = $id;
                    $data['pricingPlans'] = $this->Common_model->getDataById($id, "tbl_pricing_plans");
                    $data['main_content'] = $this->load->view('saas/addEditCustomerReview', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['encrypted_id'] = $id;
                if($id){
                    $data['title'] = lang('edit_Customer_Review');
                }else{
                    $data['title'] = lang('add_Customer_Review');
                }
                $data['main_content'] = $this->load->view('saas/addEditCustomerReview', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                if($id){
                    $data['title'] = lang('edit_Customer_Review');
                }else{
                   $data['title'] = lang('add_Customer_Review');
                }
                $data['encrypted_id'] = $id;
                $company = getMainCompany();
                $customer_reviewers = isset($company->customer_reviewers) && $company->customer_reviewers?json_decode($company->customer_reviewers):'';
                $data['customer_review'] = isset($customer_reviewers[$id]) && $customer_reviewers[$id]?json_decode($customer_reviewers[$id]):'';
                $data['main_content'] = $this->load->view('saas/addEditCustomerReview', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }

     /**
     * add Pricing Plan
     * @access public
     * @return void
     * @param no
     */
    public function addManualPayment($id='') {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('company', "Company", 'required');
            $this->form_validation->set_rules('amount', "Amount", 'required');
            if ($this->form_validation->run() == TRUE) {
                $company_id = htmlspecialchars($this->input->post($this->security->xss_clean('company')));
                $data = array();
                $data['payment_type'] = "Manual Payment";
                $data['company_id'] = $company_id;
                $data['amount'] = htmlspecialchars($this->input->post($this->security->xss_clean('amount')));;
                $data['payment_date'] = date("Y-m-d",strtotime('today'));
                $data['trans_id'] = "--";
                $this->Common_model->insertInformation($data, "tbl_payment_histories");

                //update company table
                $company_details = $this->Common_model->getDataById($company_id, "tbl_companies");
                $company_info = array();
                $company_info['access_day'] = isset($company_details->access_day) && $company_details->access_day?$company_details->access_day:0;
                $company_info['payment_clear'] = "Yes";
                $this->Common_model->updateInformation($company_info, $company_id, "tbl_companies");
                $this->session->set_flashdata('exception',lang('payment_success'));

                redirect('Service/paymentHistory');
            } else {
                if ($id == "") {
                    $data = array();
                     if($id){
                         $data['title'] = "Add Manual Payment";
                }else{
                         $data['title'] = "Add Manual Payment";
                }
                    $data['main_content'] = $this->load->view('saas/addManualPayment', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                     if($id){
                         $data['title'] = "Add Manual Payment";
                }else{
                         $data['title'] = "Add Manual Payment";
                }
                    $data['encrypted_id'] = $id;
                    $data['main_content'] = $this->load->view('saas/addManualPayment', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            $data = array();
            $data['title'] = "Add Manual Payment";
            $data['encrypted_id'] = $id;
            $data['companies'] = $this->Common_model->getServiceCompanies();
            $data['main_content'] = $this->load->view('saas/addManualPayment', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }

     /**
     * add Pricing Plan
     * @access public
     * @return void
     * @param no
     */
    public function manualPayment() {
        if ($this->input->post('submit')) {
            $data = array();
            $data['payment_type'] = "Manual Payment";
            $data['company_id'] = htmlspecialchars($this->input->post($this->security->xss_clean('hidden_company_id')));
            $data['amount'] = htmlspecialchars($this->input->post($this->security->xss_clean('amount')));;
            $data['payment_date'] = date("Y-m-d",strtotime('today'));
            $data['trans_id'] = "--";
            $this->Common_model->insertInformation($data, "tbl_payment_histories");

            //update company table
            $today = date("Y-m-d",strtotime('today'));
            $total_access_day = getTotalDays($today,$this->input->post($this->security->xss_clean('expired_date')));
            $company_info = array();
            $company_info['access_day'] = $total_access_day;
            $company_info['payment_clear'] = "Yes";
            $this->Common_model->updateInformation($company_info, $this->input->post($this->security->xss_clean('hidden_company_id')), "tbl_companies");

            $this->session->set_flashdata('exception',lang('payment_success'));
            redirect('Service/companies');
        } else {
            redirect('Service/companies');
        }
    }

    /**
     * add Pricing Plan
     * @access public
     * @return void
     * @param no
     */
    public function addEditCompany($id='') {
        if($id==1):
            redirect('Service/companies');
        endif;
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('business_name', lang('business_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('invoice_logo', lang('invoice_logo'), 'callback_validate_invoice_logo|max_length[500]');
            $this->form_validation->set_rules('date_format', lang('date_format'), 'required|max_length[250]');
            $this->form_validation->set_rules('zone_name', lang('Time_Zone'), 'required|max_length[250]');
            $this->form_validation->set_rules('currency', lang('currency'), 'required|max_length[250]');
            $this->form_validation->set_rules('invoice_footer', lang('invoice_footer'), 'max_length[250]');
            $this->form_validation->set_rules('access_day', lang('access_day'), 'required');
            $this->form_validation->set_rules('admin_name', lang('admin_name'), 'required');
            $this->form_validation->set_rules('phone', lang('phone'), 'required');
            $this->form_validation->set_rules('plan_id', lang('plan'), 'required');
            if($id == ""){
                $this->form_validation->set_rules('password', lang('password'), 'required|max_length[50]|min_length[6]');
                $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'required|max_length[50]|min_length[6]|matches[password]');
            }

            if ($id != '') {
                $post_email_address =htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                $admin_user = htmlspecialchars($this->input->post($this->security->xss_clean('admin_id')));
                $user_details = $this->Common_model->getDataById($admin_user, "tbl_users");

                $existing_email_address = isset($user_details->email_address) && $user_details->email_address?$user_details->email_address:'';
                if ($post_email_address != $existing_email_address) {
                    $this->form_validation->set_rules('email',  lang('email_address'), "required|max_length[50]|is_unique[tbl_users.email_address]");
                } else {
                    $this->form_validation->set_rules('email',  lang('email_address'), "required|max_length[50]");
                }
            } else {
                $this->form_validation->set_rules('email',  lang('email_address'), "required|max_length[50]|is_unique[tbl_users.email_address]");
            }

            if ($this->form_validation->run() == TRUE) {
                $company_info= array();
                $company_info['business_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('business_name')));
                if ($_FILES['invoice_logo']['name'] != "") {
                    $company_info['invoice_logo'] = $this->session->userdata('invoice_logo');
                    $this->session->unset_userdata('invoice_logo');
                }else{
                    $company_info['invoice_logo'] =htmlspecialchars($this->input->post($this->security->xss_clean('invoice_logo_p')));
                }
                $company_info['website'] =htmlspecialchars($this->input->post($this->security->xss_clean('website')));
                $company_info['date_format'] =htmlspecialchars($this->input->post($this->security->xss_clean('date_format')));
                $company_info['zone_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('zone_name')));
                $company_info['currency'] =htmlspecialchars($this->input->post($this->security->xss_clean('currency')));
                $company_info['currency_position'] =htmlspecialchars($this->input->post($this->security->xss_clean('currency_position')));
                $company_info['is_active'] =htmlspecialchars($this->input->post($this->security->xss_clean('is_active')));
                $company_info['precision'] =htmlspecialchars($this->input->post($this->security->xss_clean('precision')));
                $company_info['pre_or_post_payment'] = htmlspecialchars($this->input->post($this->security->xss_clean('pre_or_post_payment')));
                $company_info['invoice_footer'] =htmlspecialchars($this->input->post($this->security->xss_clean('invoice_footer')));
                $company_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $company_info['address'] =htmlspecialchars($this->input->post($this->security->xss_clean('address')));
                $company_info['plan_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('plan_id')));
                $company_info['access_day'] =htmlspecialchars($this->input->post($this->security->xss_clean('access_day')));

                if ($id == "") {
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
                    $company_info['language_manifesto'] = "revhgbrev";
                    $company_info['white_label'] = isset($main_company->white_label) && $main_company->white_label?$main_company->white_label:'';
                    $company_info['collect_tax'] = isset($main_company->collect_tax) && $main_company->collect_tax?$main_company->collect_tax:'';

                    $plan_id = $this->input->post($this->security->xss_clean('plan_id'));
                    if($plan_id){
                        $plan = $this->Common_model->getDataById($plan_id, "tbl_pricing_plans");
                        if($plan){
                            $company_info['monthly_cost'] = $plan->monthly_cost;
                            $company_info['number_of_maximum_users'] = $plan->number_of_maximum_users;
                            $company_info['number_of_maximum_outlets'] = $plan->number_of_maximum_outlets;
                            $company_info['number_of_maximum_invoices'] = $plan->number_of_maximum_invoices;
                            $company_info['payment_clear'] = "Yes";
                            if($plan->trail_days==111){
                                $company_info['access_day'] =30;
                                $id = $this->Common_model->insertInformation($company_info, "tbl_companies");
                                //update admin info
                                $admin_data = array();
                                $admin_data['full_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('admin_name')));
                                $admin_data['phone'] = htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                                $admin_data['email_address'] = htmlspecialchars($this->input->post($this->security->xss_clean('email')));

                                $password  = $this->input->post($this->security->xss_clean('password'));
                                if($password ==''){

                                }else{
                                    $admin_data['password'] = md5(($this->input->post($this->security->xss_clean('password'))));
                                }
                                $admin_data['designation'] = "Admin User";
                                $admin_data['role'] = "Admin";
                                $admin_data['company_id'] = $id;
                                $admin_data['will_login'] = "Yes";
                                $this->Common_model->insertInformation($admin_data, "tbl_users");

                                $this->session->set_flashdata('exception', lang('insertion_success'));
                                redirect('Service/companies');
                            }else{
                                $company_info['access_day'] =$plan->trail_days;
                                $company_info['payment_clear'] = "Yes";
                                $company_info['del_status'] = "Live";
                            }
                        }
                    }

                    $id = $this->Common_model->insertInformation($company_info, "tbl_companies");
                    //update admin info
                    $admin_data = array();
                    $admin_data['full_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('admin_name')));
                    $admin_data['phone'] = htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                    $admin_data['email_address'] = htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                    $password  = $this->input->post($this->security->xss_clean('password'));
                    if($password ==''){

                    }else{
                        $admin_data['password'] = md5(($this->input->post($this->security->xss_clean('password'))));
                    }
                    $admin_data['designation'] = "Admin User";
                    $admin_data['role'] = "Admin";
                    $admin_data['company_id'] = $id;
                    $admin_data['will_login'] = "Yes";
                    $id1 = $this->Common_model->insertInformation($admin_data, "tbl_users");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                    //send success message for supper admin

                    $company = getMainCompany();
                    $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
                    $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';

                    $business = htmlspecialchars($this->input->post($this->security->xss_clean('business_name')));
                    $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
                    sendEmailOnly($txt,$send_to,$attached='',$business,"Restaurant SignUp Success");

                } else {
                    $this->Common_model->updateInformation($company_info, $id, "tbl_companies");

                    //update admin info
                    $admin_data = array();
                    $admin_data['full_name'] = htmlspecialchars($this->input->post($this->security->xss_clean('admin_name')));
                    $admin_data['phone'] = htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                    $admin_data['email_address'] = htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                    $password  = $this->input->post($this->security->xss_clean('password'));
                    if($password ==''){

                    }else{
                        $admin_data['password'] = md5(($this->input->post($this->security->xss_clean('password'))));
                    }
                    $admin_data['designation'] = "Admin User";
                    $admin_data['role'] = "Admin";
                    $admin_data['company_id'] = $id;
                    $admin_data['will_login'] = "Yes";
                    $admin_user = htmlspecialchars($this->input->post($this->security->xss_clean('admin_id')));
                    $this->Common_model->updateInformation($admin_data, $admin_user, "tbl_users");

                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Service/companies');
            } else {
                $data = array();
                $data['encrypted_id'] = $id;
                if($id){
                    $data['title'] = lang('edit_company');
                }else{
                    $data['title'] = lang('add_company');
                }
                $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
                $data['outlet_information_user'] = $this->Common_model->getAdminInfoForCompany($id);
                $data['zone_names'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
                $data['pricing_plans'] = $this->Common_model->getAllForDropdown("tbl_pricing_plans");
                $data['main_content'] = $this->load->view('saas/addEditCompany', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['encrypted_id'] = $id;
            if($id){
                $data['title'] = lang('edit_company');
            }else{
                $data['title'] = lang('add_company');
            }
            $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
            $data['outlet_information_user'] = $this->Common_model->getAdminInfoForCompany($id);
            $data['zone_names'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
            $data['pricing_plans'] = $this->Common_model->getAllForDropdown("tbl_pricing_plans");
            $data['main_content'] = $this->load->view('saas/addEditCompany', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * validate invoice logo
     * @access public
     * @return string
     * @param boolean
     */
    public function validate_invoice_logo() {

        if ($_FILES['invoice_logo']['name'] != "") {
            $config['upload_path'] = './images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '1000';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("invoice_logo")) {

                $upload_info = $this->upload->data();

                $file_name = $upload_info['file_name'];
                $config['image_library'] = 'gd2';
                $config['source_image'] = './images/' . $file_name;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 100;
                $config['height'] = 100;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->session->set_userdata('invoice_logo', $file_name);

            } else {
                $this->form_validation->set_message('validate_invoice_logo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }
    /**
     * payment setting
     * @access public
     * @return void
     * @param int
     */
    public function paymentSetting($id = '') {
        if ($this->input->post('submit')) {
            $field_2  = htmlspecialchars($this->input->post('field_2'));
            $field_3  = htmlspecialchars($this->input->post('field_3'));
            $field_5  = htmlspecialchars($this->input->post('field_5'));

            $field_2_v  = htmlspecialchars($this->input->post('field_2_v'));
            $field_3_v  = htmlspecialchars($this->input->post('field_3_v'));
            $field_4_v  = htmlspecialchars($this->input->post('field_4_v'));

            if($field_2==1){
                $this->form_validation->set_rules('paypal_business_email', "Paypal Business Amount", "required");
                $this->form_validation->set_rules('field_2_key_1', "Client ID", "required");
                $this->form_validation->set_rules('field_2_key_2', "Secret Key", "required");
            }
            if($field_3==1){
                $this->form_validation->set_rules('field_3_key_1', "Stripe API Key", "required");
                $this->form_validation->set_rules('field_3_key_2', "Stripe Publishable Key", "required");
            }
            if($field_5==1){
                $this->form_validation->set_rules('field_4_key_1', "Key ID", "required");
                $this->form_validation->set_rules('field_4_key_2', "Key Secret", "required");
            }

            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['field_2'] = $field_2;
                $data['field_3'] = $field_3;
                $data['field_5'] = $field_5;

                $data['field_2_v'] = $field_2_v;
                $data['field_3_v'] = $field_3_v;
                $data['field_4_v'] = $field_4_v;

                $data['field_2_key_1']  = htmlspecialchars($this->input->post('field_2_key_1'));
                $data['field_2_key_2']  = htmlspecialchars($this->input->post('field_2_key_2'));
                $data['field_3_key_1']  = htmlspecialchars($this->input->post('field_3_key_1'));
                $data['field_3_key_2']  = htmlspecialchars($this->input->post('field_3_key_2'));
                $data['field_4_key_1']  = htmlspecialchars($this->input->post('field_4_key_1'));
                $data['field_4_key_2']  = htmlspecialchars($this->input->post('field_4_key_2'));
                $data['paypal_business_email']  = htmlspecialchars($this->input->post('paypal_business_email'));
                if($field_2_v=="sandbox"){
                    $data['url_paypal']  = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                }else{
                    $data['url_paypal']  = "https://www.paypal.com/cgi-bin/webscr";
                }

                $data_payment_setting['payment_settings'] = json_encode($data);
                if ($id == "") {
                    $this->Common_model->insertInformation($data_payment_setting, "tbl_companies");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($data_payment_setting, $id, "tbl_companies");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                redirect('Service/paymentSetting');
            }else{
                $data = array();
                $data['paymentSetting'] = paymentSetting();
                $data['main_content'] = $this->load->view('saas/paymentSetting', $data, TRUE);
                $this->load->view('userHome', $data);
            }

        } else {
            $data = array();
            $data['paymentSetting'] = paymentSetting();
            $data['main_content'] = $this->load->view('saas/paymentSetting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * payment setting
     * @access public
     * @return void
     * @param int
     */
    public function emailSetting($id = '') {
        if ($this->input->post('submit')) {
            $enable_status  = htmlspecialchars($this->input->post('enable_status'));
            if($enable_status==1){
                $this->form_validation->set_rules('host_name', lang('SMTPHost'), "required");
                $this->form_validation->set_rules('port_address', lang('PortAddress'), "required");
                $this->form_validation->set_rules('user_name', lang('Username'), "required");
                $this->form_validation->set_rules('password', lang('Password'), "required");

                if ($this->form_validation->run() == TRUE) {
                    $data = array();
                    $data['enable_status']  = htmlspecialchars($this->input->post('enable_status'));
                    $data['host_name']  = htmlspecialchars($this->input->post('host_name'));
                    $data['email_send_to']  = htmlspecialchars($this->input->post('email_send_to'));
                    $data['port_address']  = htmlspecialchars($this->input->post('port_address'));
                    $data['user_name']  = htmlspecialchars($this->input->post('user_name'));
                    $data['password']  = htmlspecialchars($this->input->post('password'));
                    $data_payment_setting['email_settings'] = json_encode($data);
                    $this->Common_model->updateInformation($data_payment_setting, $id, "tbl_companies");
                    $this->session->set_flashdata('exception', lang('update_success'));
                    redirect('Service/emailSetting');
                }else{
                    $data = array();
                    $data['main_content'] = $this->load->view('saas/email_setting', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }else{
                $data = array();
                $data['enable_status']  = htmlspecialchars($this->input->post('enable_status'));
                $data['host_name']  = htmlspecialchars($this->input->post('host_name'));
                $data['port_address']  = htmlspecialchars($this->input->post('port_address'));
                $data['email_send_to']  = htmlspecialchars($this->input->post('email_send_to'));
                $data['user_name']  = htmlspecialchars($this->input->post('user_name'));
                $data['password']  = htmlspecialchars($this->input->post('password'));
                $data_payment_setting['email_settings'] = json_encode($data);
                $this->Common_model->updateInformation($data_payment_setting, $id, "tbl_companies");
                $this->session->set_flashdata('exception', lang('update_success'));

                redirect('Service/emailSetting');
            }



        } else {
            $data = array();
            $data['main_content'] = $this->load->view('saas/email_setting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }

}
