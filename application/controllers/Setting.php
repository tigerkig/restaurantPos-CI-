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
  # This is Setting Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Outlet_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2',lang('please_click_green_button'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }
    }

     /**
     * setting info
     * @access public
     * @return void
     * @param int
     */
    public function index($id = '') {
        $encrypted_id = $id;
        $company_id = $id = $outlet_id = $this->session->userdata('company_id');
        $language_manifesto = $this->session->userdata('language_manifesto');
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('business_name', lang('business_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('invoice_logo', lang('invoice_logo'), 'callback_validate_invoice_logo|max_length[500]');
            $this->form_validation->set_rules('date_format', lang('date_format'), 'required|max_length[250]');
            $this->form_validation->set_rules('zone_name', lang('Time_Zone'), 'required|max_length[250]');
            $this->form_validation->set_rules('currency', lang('currency'), 'required|max_length[250]');
            $this->form_validation->set_rules('invoice_footer', lang('invoice_footer'), 'max_length[250]');
            if(str_rot13($language_manifesto)!="eriutoeri"):
                $this->form_validation->set_rules('default_waiter', lang('Default_Waiter'), 'required|max_length[11]');
            endif;
            $this->form_validation->set_rules('default_customer', lang('Default_Customer'), 'required|max_length[11]');
            $this->form_validation->set_rules('default_payment', lang('Default_Payment_Method'), 'required|max_length[11]');

            if ($this->form_validation->run() == TRUE) {
                $outlet_info = array();
                $outlet_info['business_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('business_name')));
                if ($_FILES['invoice_logo']['name'] != "") {
                    $outlet_info['invoice_logo'] = $this->session->userdata('invoice_logo');
                    $this->session->unset_userdata('invoice_logo');
                }else{
                    $outlet_info['invoice_logo'] =htmlspecialchars($this->input->post($this->security->xss_clean('invoice_logo_p')));
                }
                $outlet_info['website'] =htmlspecialchars($this->input->post($this->security->xss_clean('website')));
                $outlet_info['date_format'] =htmlspecialchars($this->input->post($this->security->xss_clean('date_format')));
                $outlet_info['zone_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('zone_name')));
                $outlet_info['currency'] =htmlspecialchars($this->input->post($this->security->xss_clean('currency')));
                $outlet_info['currency_position'] =htmlspecialchars($this->input->post($this->security->xss_clean('currency_position')));
                $outlet_info['precision'] =htmlspecialchars($this->input->post($this->security->xss_clean('precision')));
                if(str_rot13($language_manifesto)!="eriutoeri"):
                    $outlet_info['default_waiter'] =htmlspecialchars($this->input->post($this->security->xss_clean('default_waiter')));
                endif;

                $outlet_info['default_customer'] =htmlspecialchars($this->input->post($this->security->xss_clean('default_customer')));
                $outlet_info['default_payment'] =htmlspecialchars($this->input->post($this->security->xss_clean('default_payment')));
                $outlet_info['pre_or_post_payment'] =htmlspecialchars($this->input->post($this->security->xss_clean('pre_or_post_payment')));
                $outlet_info['invoice_footer'] =htmlspecialchars($this->input->post($this->security->xss_clean('invoice_footer')));

                $outlet_info['print_format_invoice'] =htmlspecialchars($this->input->post($this->security->xss_clean('print_format_invoice')));
                $outlet_info['printing_invoice'] = htmlspecialchars($this->input->post('printing_invoice'));
                $outlet_info['receipt_printer_invoice'] = htmlspecialchars($this->input->post('receipt_printer_invoice'));

                $outlet_info['print_format_bill'] =htmlspecialchars($this->input->post($this->security->xss_clean('print_format_bill')));
                $outlet_info['printing_bill'] = htmlspecialchars($this->input->post('printing_bill'));
                $outlet_info['receipt_printer_bill'] = htmlspecialchars($this->input->post('receipt_printer_bill'));

                $outlet_info['print_format_kot'] =htmlspecialchars($this->input->post($this->security->xss_clean('print_format_kot')));
                $outlet_info['printing_kot'] = htmlspecialchars($this->input->post('printing_kot'));
                $outlet_info['receipt_printer_kot'] = htmlspecialchars($this->input->post('receipt_printer_kot'));

                $outlet_info['print_format_bot'] =htmlspecialchars($this->input->post($this->security->xss_clean('print_format_bot')));
                $outlet_info['printing_bot'] = htmlspecialchars($this->input->post('printing_bot'));
                $outlet_info['receipt_printer_bot'] = htmlspecialchars($this->input->post('receipt_printer_bot'));

                $outlet_info['print_server_url_bot'] = htmlspecialchars($this->input->post('print_server_url_bot'));
                $outlet_info['print_server_url_kot'] = htmlspecialchars($this->input->post('print_server_url_kot'));
                $outlet_info['print_server_url_bill'] = htmlspecialchars($this->input->post('print_server_url_bill'));
                $outlet_info['print_server_url_invoice'] = htmlspecialchars($this->input->post('print_server_url_invoice'));
                $outlet_info['service_type'] = htmlspecialchars($this->input->post('service_type'));
                $outlet_info['service_amount'] = htmlspecialchars($this->input->post('service_amount'));


                if(!isServiceAccessOnlyLogin('sGmsJaFJE')):
                    $outlet_info['export_daily_sale'] =htmlspecialchars($this->input->post($this->security->xss_clean('export_daily_sale')));
                    endif;

                if ($id == "") {
                    $outlet_info['starting_date'] = date("Y-m-d"); 
                    $outlet_info['user_id'] = $this->session->userdata('user_id');
                    $outlet_info['company_id'] = $this->session->userdata('company_id');
                    $outlet_info['outlet_code'] = $this->Outlet_model->generateOutletCode();
                }
                if ($id == "") {
                    $this->Common_model->insertInformation($outlet_info, "tbl_companies");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($outlet_info, $id, "tbl_companies");
                    $this->session->set_flashdata('exception', lang('update_success'));
                }
                $this->session->set_userdata($outlet_info);
                redirect('setting');
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
                $data['zone_names'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
                $data['customers'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_customers");
                $data['printers'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_printers");
                if(str_rot13($language_manifesto)!="eriutoeri"):
                    $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
                endif;
                $data['paymentMethods'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_payment_methods");
                $data['main_content'] = $this->load->view('authentication/setting', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['encrypted_id'] = $encrypted_id;
            $data['outlet_information'] = $this->Common_model->getDataById($id, "tbl_companies");
            $data['zone_names'] = $this->Common_model->getAllForDropdown("tbl_time_zone");
            $data['customers'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_customers");
            $data['printers'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_printers");
            if(str_rot13($language_manifesto)!="eriutoeri"):
                $data['waiters'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_users");
            endif;
            $data['paymentMethods'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_payment_methods");
            $data['main_content'] = $this->load->view('authentication/setting', $data, TRUE);
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
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }
     /**
     * smtp Email Setting
     * @access public
     * @return void
     * @param int
     */
    public function smtpEmailSetting($id = '') {
        if ($this->input->post('submit')) {
                $this->form_validation->set_rules('host_name', "Host Name", "required|max_length[300]");
                $this->form_validation->set_rules('port_address', "Port Address", "required|max_length[300]");
                $this->form_validation->set_rules('user_name', "Username", "required|max_length[300]");
                $this->form_validation->set_rules('password', "Password", "required|max_length[300]");
            
            if ($this->form_validation->run() == TRUE) {

                $data['host_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('host_name')));
                $data['port_address'] =htmlspecialchars($this->input->post($this->security->xss_clean('port_address')));
                $data['user_name'] =htmlspecialchars($this->input->post($this->security->xss_clean('user_name')));
                $data['password'] =htmlspecialchars($this->input->post($this->security->xss_clean('password')));
                $data_json['smtp_enable_status'] =htmlspecialchars($this->input->post($this->security->xss_clean('enable_status')));
                $data_json['smtp_details'] = json_encode($data);

                $this->Common_model->updateInformation($data_json, $id, "tbl_companies");
                $this->session->set_flashdata('exception', lang('update_success'));
                redirect('setting/smtpEmailSetting');
            }else{
                $data = array();
                $data['company'] = getCompanyInfo();
                $data['main_content'] = $this->load->view('authentication/smtpEmailSetting', $data, TRUE);
                $this->load->view('userHome', $data);
            }

        } else {
            $data = array();
            $data['company'] = getCompanyInfo();
            $data['main_content'] = $this->load->view('authentication/smtpEmailSetting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    /**
     * menu rearrange
     * @access public
     * @return void
     * @param no
     */
    public function menuRearrange() {
        $data = array();
        $data['menu_access'] = getMainMenu();
        $data['main_content'] = $this->load->view('authentication/menu_rearrange', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * resetTransactional Data
     * @access public
     * @return void
     * @param no
     */
    public function resetTransactionalData() {
        //truncate all transactional data
        $this->db->query("TRUNCATE tbl_sales");
        $this->db->query("TRUNCATE tbl_sales_details");
        $this->db->query("TRUNCATE bl_sales_details_modifiers");
        $this->db->query("TRUNCATE tbl_sale_consumptions");
        $this->db->query("TRUNCATE tbl_sale_consumptions_of_menus");
        $this->db->query("TRUNCATE tbl_sale_consumptions_of_modifiers_of_menus");
        $this->db->query("TRUNCATE tbl_purchase");
        $this->db->query("TRUNCATE tbl_purchase_ingredients");
        $this->db->query("TRUNCATE tbl_holds");
        $this->db->query("TRUNCATE tbl_holds_details");
        $this->db->query("TRUNCATE tbl_holds_details_modifiers");
        $this->db->query("TRUNCATE tbl_holds_table");
        $this->db->query("TRUNCATE tbl_expenses");
        $this->db->query("TRUNCATE tbl_expense_items");
        $this->db->query("TRUNCATE tbl_supplier_payments");
        $this->db->query("TRUNCATE tbl_customer_due_receives");
        $this->session->set_flashdata('exception', lang('truncate_update_success'));
        redirect('setting/index');
    }
     /**
     * tax
     * @access public
     * @return void
     * @param int
     */
    public function tax($id = '') {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('collect_tax', 'Collect Tax', 'required|max_length[10]');
            if ($this->input->post('collect_tax') == "Yes") {
                $this->form_validation->set_rules('tax_title', 'Tax Title', 'required|max_length[50]');
                $this->form_validation->set_rules('tax_registration_no', 'Tax Registration No', 'required|max_length[50]');
                $this->form_validation->set_rules('tax_is_gst', 'Tax is GST', 'required|max_length[50]');
                if ($this->input->post('tax_is_gst') == "Yes") {
                    $this->form_validation->set_rules('state_code', 'State Code', 'required|max_length[50]');
                }
                $this->form_validation->set_rules('taxes[]', 'Taxes', 'required|max_length[10]');
            }

            if ($this->form_validation->run() == TRUE) {
                $outlet_info = array();
                $outlet_info['collect_tax'] =htmlspecialchars($this->input->post($this->security->xss_clean('collect_tax')));
                if ($this->input->post('collect_tax') == "Yes") {
                    $outlet_info['tax_title'] =htmlspecialchars($this->input->post($this->security->xss_clean('collect_tax')));
                    $outlet_info['tax_registration_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('collect_tax')));
                    $outlet_info['tax_is_gst'] =htmlspecialchars($this->input->post($this->security->xss_clean('collect_tax')));
                    if ($this->input->post('collect_tax') == "Yes") {
                        $outlet_info['state_code'] =htmlspecialchars($this->input->post($this->security->xss_clean('state_code')));
                    }
                }
                $outlet_info['tax_title'] =htmlspecialchars($this->input->post($this->security->xss_clean('tax_title')));
                $outlet_info['tax_registration_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('tax_registration_no')));
                $outlet_info['tax_is_gst'] =htmlspecialchars($this->input->post($this->security->xss_clean('tax_is_gst')));
                $outlet_info['state_code'] =htmlspecialchars($this->input->post($this->security->xss_clean('state_code')));

                $this->Common_model->updateInformation($outlet_info, $id, "tbl_companies");

                if(!empty($_POST['taxes'])){
                    $this->saveOutletTaxes($_POST['taxes'], $id, 'tbl_outlet_taxes');
                }
                $this->session->set_flashdata('exception', 'Information has been updated successfully!');

                redirect('setting/tax');
            } else {
                $data = array();
                $data['company'] = getCompanyInfo();
                $data['main_content'] = $this->load->view('authentication/tax', $data, TRUE);
                $this->load->view('userHome', $data);
            }

        } else {
            $data = array();
            $data['company'] = getCompanyInfo();
            $data['main_content'] = $this->load->view('authentication/tax', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * save Outlet Taxes
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveOutletTaxes($outlet_taxes, $company_id, $table_name)
    {
        $main_arr = array();
        $tax_string ='';
        foreach($outlet_taxes as $key=>$single_tax){
            $oti = array();
            if(isset($_POST['p_tax_id'][$key]) && $_POST['p_tax_id'][$key]){
                $oti['id'] = $_POST['p_tax_id'][$key];
            }else{
                $oti['id'] = 1;
            }
            $oti['tax'] = $single_tax;
            $oti['tax_rate'] = $_POST['tax_rate'][$key];
            $main_arr[] = $oti;
            $tax_string.=$single_tax.":";
        }
        $data['tax_setting'] = json_encode($main_arr);
        $data['tax_string'] = $tax_string;
        $this->Common_model->updateInformation($data, $company_id, "tbl_companies");
    }
     /**
     * whats app Setting
     * @access public
     * @return void
     * @param int
     */
    public function whatsappSetting($id = '') {
        if ($this->input->post('submit')) {
                $this->form_validation->set_rules('whatsapp_share_number', "Whatsapp Share Number", "required|max_length[300]");
            if ($this->form_validation->run() == TRUE) {
                $data['whatsapp_share_number'] =htmlspecialchars($this->input->post($this->security->xss_clean('whatsapp_share_number')));
                $this->Common_model->updateInformation($data, $id, "tbl_companies");
                $this->session->set_flashdata('exception', lang('update_success'));
                redirect('setting/whatsappSetting');
            }else{
                $data = array();
                $data['company'] = getCompanyInfo();
                $data['main_content'] = $this->load->view('authentication/whatsappSetting', $data, TRUE);
                $this->load->view('userHome', $data);
            }

        } else {
            $data = array();
            $data['company'] = getCompanyInfo();
            $data['main_content'] = $this->load->view('authentication/whatsappSetting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * sms Setting
     * @access public
     * @return void
     * @param int
     */
    public function smsSetting($id = '') {
        if ($this->input->post('submit')) {

            $enable_status  =htmlspecialchars($this->input->post($this->security->xss_clean('enable_status')));
            $this->form_validation->set_rules('enable_status', "Enable Status", "max_length[50]");
            if($enable_status==1){
                $this->form_validation->set_rules('f_1_1', "User Name", "required|max_length[300]");
                $this->form_validation->set_rules('f_1_2', "Password", "required|max_length[300]");
            }else if($enable_status==2){
                $this->form_validation->set_rules('f_2_1', "User Name", "required|max_length[300]");
                $this->form_validation->set_rules('f_2_2', "Password", "required|max_length[300]");
            }else if($enable_status==3){
                $this->form_validation->set_rules('f_3_1', "User Name", "required|max_length[300]");
                $this->form_validation->set_rules('f_3_2', "Password", "required|max_length[300]");
                $this->form_validation->set_rules('f_3_3', "API Key", "required");
            }else if($enable_status==4){
                $this->form_validation->set_rules('f_4_1', "SID", "required|max_length[300]");
                $this->form_validation->set_rules('f_4_2', "Token", "required");
                $this->form_validation->set_rules('f_4_3', "Twilio Number", "required|max_length[300]");
            }else if($enable_status==5){
                $this->form_validation->set_rules('f_5_1', "API Key", "required|max_length[300]");
                $this->form_validation->set_rules('f_5_2', "API Secret Key", "required|max_length[300]");
            }

            if ($this->form_validation->run() == TRUE) {
                $data = array();
                $data['sms_enable_status'] =htmlspecialchars($this->input->post($this->security->xss_clean('enable_status')));
                $data_json['f_1_1'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_1_1')));
                $data_json['f_1_2'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_1_2')));
                $data_json['f_2_1'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_2_1')));
                $data_json['f_2_2'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_2_2')));
                $data_json['f_3_1'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_3_1')));
                $data_json['f_3_2'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_3_2')));
                $data_json['f_3_3'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_3_3')));
                $data_json['f_4_1'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_4_1')));
                $data_json['f_4_2'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_4_2')));
                $data_json['f_4_3'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_4_3')));
                $data_json['f_5_1'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_5_1')));
                $data_json['f_5_2'] =htmlspecialchars($this->input->post($this->security->xss_clean('f_5_2')));
                $data['sms_details'] = json_encode($data_json);
                $this->Common_model->updateInformation($data, $id, "tbl_companies");
                redirect('setting/smsSetting');
            }else{
                $data = array();
                $data['company'] = getCompanyInfo();
                $company_id = $this->session->userdata('company_id');
                $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
                $data['main_content'] = $this->load->view('authentication/smsSetting', $data, TRUE);
                $this->load->view('userHome', $data);
            }

        } else {
            $data = array();
            $data['company'] = getCompanyInfo();
            $company_id = $this->session->userdata('company_id');
            $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
            $data['main_content'] = $this->load->view('authentication/smsSetting', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
    
    
}
