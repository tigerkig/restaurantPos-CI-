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
  # This is Report Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Report_model');
        $this->load->model('Inventory_model');
        $this->load->model('Sale_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();
        
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }

        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', 'Please click on green Enter button of an outlet');
            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

      /**
     * print Daily Summary Report
     * @access public
     * @return void
     * @param string
     */
    public function printDailySummaryReport($selectedDate = '',$outlet_id){
        $data = array(); 
        $data['result'] = $this->Report_model->dailySummaryReport($selectedDate,$outlet_id);
        $data['selectedDate'] = $selectedDate; 
        $data['outlet_id'] = $outlet_id;

        $this->load->view('report/printDailySummaryReport', $data); 
    }
      /**
     * daily Summary Report
     * @access public
     * @return void
     * @param no
     */
    public function dailySummaryReport() {
        $data = array();
        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $data['outlet_id'] = $outlet_id;

        if ($this->input->post('submit')) {
            if ($this->input->post('date')) {
                $selectedDate = date("Y-m-d", strtotime($this->input->post('date')));
            } else {
                $selectedDate = '';
            }
            $data['result'] = $this->Report_model->dailySummaryReport($selectedDate,$outlet_id);
            $data['selectedDate'] = $selectedDate;
 
        } else {
            $selectedDate = date("Y-m-d");
            $data['result'] = $this->Report_model->dailySummaryReport($selectedDate,$outlet_id);
            $data['selectedDate'] = $selectedDate;
        }
        $data['main_content'] = $this->load->view('report/dailySummaryReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
        /**
     * register Report
     * @access public
     * @return void
     * @param no
     */
    public function registerReport()
    {
        $data = array();

        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $data['outlet_id'] = $outlet_id;
        if ($this->input->post('submit')) {
            $start_date = date("Y-m-d", strtotime($this->input->post('startDate')));
            $end_date = date("Y-m-d", strtotime($this->input->post('endDate')));
            if($start_date=="" || $end_date==""){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            }
            $user_id = $this->input->post('user_id');
            

            $data['register_info'] = $this->Report_model->getRegisterInformation($start_date,$end_date,$user_id,$outlet_id);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['user_id'] = $user_id;
        }
        $data['users'] = $this->Report_model->getUsers($outlet_id);
        $data['main_content'] = $this->load->view('report/registerReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * today Report
     * @access public
     * @return void
     * @param no
     */
    public function todayReport() {
        $data = array();
        $data['dailySummaryReport'] = $this->Report_model->todaySummaryReport('');
        echo json_encode($data['dailySummaryReport']);
    }
      /**
     * today Report Cash Status
     * @access public
     * @return void
     * @param no
     */
    public function todayReportCashStatus() {
        echo json_encode('');
    }
      /**
     * inventory Report
     * @access public
     * @return void
     * @param no
     */
    public function inventoryReport() {
        $data = array();
        $ingredient_id = $this->input->post('ingredient_id');
        $category_id = $this->input->post('category_id');
        $food_id = $this->input->post('food_id');
        $data['ingredient_id'] = $ingredient_id;
        $data['category_id'] = $category_id;
        $data['food_id'] = $food_id;

        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $data['outlet_id'] = $outlet_id;
        $company_id = $this->session->userdata('company_id');
        $data['ingredient_categories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_ingredient_categories");
        $data['ingredients'] = $this->Report_model->getInventory($category_id, $ingredient_id, $food_id,$outlet_id);
        $data['foodMenus'] = $this->Sale_model->getAllFoodMenus();
        $data['inventory'] = $this->Report_model->getInventory($category_id, $ingredient_id, $food_id,$outlet_id);

        $data['main_content'] = $this->load->view('report/inventoryReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * sale Report By Month
     * @access public
     * @return void
     * @param no
     */
    public function saleReportByMonth() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startMonth')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endMonth')));
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $data['user_id'] = $user_id;
            if ($start_date && $end_date) {
                $start_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $start_date = $start_date . '-' . '01';
                $data['start_date'] = $start_date;
                $end_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $end_date = $end_date . '-' . $finalDayByMonth;
                $data['end_date'] = $end_date;
            }
            if ($start_date && !$end_date) {
                $start_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $temp = $start_date . '-' . $finalDayByMonth;
                $start_date = $start_date . '-' . '01';
                $end_date = $temp;
                $data['start_date'] = $start_date;
                $data['end_date'] = $temp;
            }
            if (!$start_date && $end_date) {
                $end_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $temp = $end_date . '-' . '01';
                $start_date = $temp;
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $end_date = $end_date . '-' . $finalDayByMonth;
                $data['start_date'] = $temp;
                $data['end_date'] = $end_date;
            }
            $data['saleReportByMonth'] = $this->Report_model->saleReportByMonth($start_date, $end_date, $user_id);
        }


        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/saleReportByMonth', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * vat Report
     * @access public
     * @return void
     * @param no
     */
    public function vatReport() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $data['start_date'] = $start_date;
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['end_date'] = $end_date;
            $data['vatReport'] = $this->Report_model->vatReport($start_date, $end_date,$outlet_id);
        }
        $data['main_content'] = $this->load->view('report/vatReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * sale Report By Date
     * @access public
     * @return void
     * @param no
     */
    public function saleReportByDate() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $data['start_date'] = $start_date;
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['end_date'] = $end_date;
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $data['user_id'] = $user_id;
            $data['saleReportByDate'] = $this->Report_model->saleReportByDate($start_date, $end_date, $user_id,$outlet_id);
        }
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/saleReportByDate', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * profit Loss Report
     * @access public
     * @return void
     * @param no
     */
    public function profitLossReport() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            if ($start_date || $end_date) {
                $data['saleReportByDate'] = $this->Report_model->profitLossReport($start_date, $end_date,$outlet_id);
            }
        }
        $data['main_content'] = $this->load->view('report/profitLossReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    public function profitLossReportBackup() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            if ($start_date || $end_date) {
                $data['saleReportByDate'] = $this->Report_model->profitLossReportByDate($start_date, $end_date,$outlet_id);
                print("<Pre>");
                print_r($data['saleReportByDate']);exit;
            }
        }
        $data['main_content'] = $this->load->view('report/profitLossReportByDate', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * supplier Report
     * @access public
     * @return void
     * @param no
     */
    public function supplierReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $supplier_id =htmlspecialchars($this->input->post($this->security->xss_clean('supplier_id')));
            $data['supplier_id'] = $supplier_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['supplierReport'] = $this->Report_model->supplierReport($start_date, $end_date, $supplier_id,$outlet_id);
            $data['supplierDuePaymentReport'] = $this->Report_model->supplierDuePaymentReport($start_date, $end_date, $supplier_id,$outlet_id);
        }
        $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_suppliers');
        $data['main_content'] = $this->load->view('report/supplierReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * customer Report
     * @access public
     * @return void
     * @param no
     */
    public function customerReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $customer_id =htmlspecialchars($this->input->post($this->security->xss_clean('customer_id')));
            $data['customer_id'] = $customer_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['customerReport'] = $this->Report_model->customerReport($start_date, $end_date, $customer_id,$outlet_id);
            $data['customerDueReceiveReport'] = $this->Report_model->customerDueReceiveReport($start_date, $end_date, $customer_id,$outlet_id);
        }
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
        $data['main_content'] = $this->load->view('report/customerReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * food Menu Sales
     * @access public
     * @return void
     * @param no
     */
    public function foodMenuSales() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));  
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['outlet_id'] = $outlet_id;
            $data['foodMenuSales'] = $this->Report_model->foodMenuSales($start_date, $end_date,$outlet_id);
        }
        $data['main_content'] = $this->load->view('report/foodMenuSales', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * food Menu Sales
     * @access public
     * @return void
     * @param no
     */
    public function foodMenuSaleByCategories() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $cat_id =htmlspecialchars($this->input->post($this->security->xss_clean('cat_id')));
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['cat_id'] = $cat_id;
            $data['outlet_id'] = $outlet_id;
            $data['foodMenuSales'] = $this->Report_model->foodMenuSaleByCategories($start_date, $end_date,$outlet_id,$cat_id);
        }
        $company_id = $this->session->userdata('company_id');
        $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
        $data['main_content'] = $this->load->view('report/foodMenuSaleByCategories', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * food Menu Sales
     * @access public
     * @return void
     * @param no
     */
    public function foodTransferReport() {
        $data = array();
        if ($this->input->post('submit')) {
            $from_outlet_id  = isset($_POST['from_outlet_id']) && $_POST['from_outlet_id']?$_POST['from_outlet_id']:'';
            $to_outlet_id  = isset($_POST['to_outlet_id']) && $_POST['to_outlet_id']?$_POST['to_outlet_id']:'';

            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['from_outlet_id'] = $from_outlet_id;
            $data['to_outlet_id'] = $to_outlet_id;
            $data['foodTransferReport'] = $this->Report_model->foodTransferReport($start_date, $end_date,$from_outlet_id,$to_outlet_id);
            foreach ($data['foodTransferReport'] as $key=>$value){
                $foods = '';
                $food_list = $this->Common_model->getAllByCustomId($value->id,"transfer_id","tbl_transfer_ingredients",$order='');;
                foreach ($food_list as $keys=>$value1){
                    $foods.=getFoodMenuNameById($value1->ingredient_id)."(".getFoodMenuCodeById($value1->ingredient_id).") - ".$value1->quantity_amount." Pcs";
                    if($keys>sizeof($foods)-1){
                        $foods.="<br>";
                    }
                }
                $data['foodTransferReport'][$key]->foods = $foods;
            }
        }
        $company_id = $this->session->userdata('company_id');
        $data['main_content'] = $this->load->view('report/foodTransferReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * food Menu Sales
     * @access public
     * @return void
     * @param no
     */
    public function foodMenuSaleDetailsByCategories() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $cat_id =htmlspecialchars($this->input->post($this->security->xss_clean('cat_id')));
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['cat_id'] = $cat_id;
            $data['outlet_id'] = $outlet_id;
            $data['foodMenuSales'] = $this->Report_model->foodMenuSaleDetailsByCategories($start_date, $end_date,$outlet_id,$cat_id);
        }
        $company_id = $this->session->userdata('company_id');
        $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
        $data['main_content'] = $this->load->view('report/foodMenuSaleDetailsByCategories', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * consumption Report
     * @access public
     * @return void
     * @param no
     */
    public function consumptionReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));  
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            $data['consumptionMenus'] = $this->Report_model->consumptionMenus($start_date, $end_date,$outlet_id);
            $data['consumptionModifiers'] = $this->Report_model->consumptionModifiers($start_date, $end_date,$outlet_id);
        }
        $data['main_content'] = $this->load->view('report/consumptionReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * detailed Sale Report
     * @access public
     * @return void
     * @param no
     */
    public function detailedSaleReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $waiter_id =htmlspecialchars($this->input->post($this->security->xss_clean('waiter_id')));
            $data['user_id'] = $user_id;
            $data['waiter_id'] = $waiter_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['detailedSaleReport'] = $this->Report_model->detailedSaleReport($start_date, $end_date, $user_id,$outlet_id,$waiter_id);
        }
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/detailedSaleReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * purchase Report By Month
     * @access public
     * @return void
     * @param no
     */
    public function purchaseReportByMonth() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startMonth')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endMonth')));
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $data['user_id'] = $user_id;
            if ($start_date && $end_date) {
                $start_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $start_date = $start_date . '-' . '01';
                $data['start_date'] = $start_date;
                $end_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $end_date = $end_date . '-' . $finalDayByMonth;
                $data['end_date'] = $end_date;
            }
            if ($start_date && !$end_date) {
                $start_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('startMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $temp = $start_date . '-' . $finalDayByMonth;
                $start_date = $start_date . '-' . '01';
                $end_date = $temp;
                $data['start_date'] = $start_date;
                $data['end_date'] = $temp;
            }
            if (!$start_date && $end_date) {
                $end_date = date('Y-m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $temp = $end_date . '-' . '01';
                $start_date = $temp;
                $month = date('m', strtotime($this->input->post($this->security->xss_clean('endMonth'))));
                $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($month);
                $end_date = $end_date . '-' . $finalDayByMonth;
                $data['start_date'] = $temp;
                $data['end_date'] = $end_date;
            }
            $data['purchaseReportByMonth'] = $this->Report_model->purchaseReportByMonth($start_date, $end_date, $user_id);
        }


        $data['users'] = $this->Common_model->getAllByOutletIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/purchaseReportByMonth', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * purchase Report By Date
     * @access public
     * @return void
     * @param no
     */
    public function purchaseReportByDate() {
        $data = array();
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $data['start_date'] = $start_date;
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $data['end_date'] = $end_date;
            $data['purchaseReportByDate'] = $this->Report_model->purchaseReportByDate($start_date, $end_date,$outlet_id);
        }
        $data['main_content'] = $this->load->view('report/purchaseReportByDate', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * purchase Report By Ingredient
     * @access public
     * @return void
     * @param no
     */
    public function purchaseReportByIngredient() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $ingredients_id =htmlspecialchars($this->input->post($this->security->xss_clean('ingredients_id')));
            $data['ingredients_id'] = $ingredients_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['purchaseReportByIngredient'] = $this->Report_model->purchaseReportByIngredient($start_date, $end_date, $ingredients_id);
        }
        /* print('<pre>');
          print_r($data['vatReport']);exit; */
        $data['ingredients'] = $this->Inventory_model->getAllByCompanyIdForDropdown($company_id, 'tbl_ingredients');
        $data['main_content'] = $this->load->view('report/purchaseReportByIngredient', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * detailed Purchase Report
     * @access public
     * @return void
     * @param no
     */
    public function detailedPurchaseReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $data['user_id'] = $user_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['detailedPurchaseReport'] = $this->Report_model->detailedPurchaseReport($start_date, $end_date, $user_id);
        }
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/detailedPurchaseReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * waste Report
     * @access public
     * @return void
     * @param no
     */
    public function wasteReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $user_id =htmlspecialchars($this->input->post($this->security->xss_clean('user_id')));
            $data['user_id'] = $user_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['wasteReport'] = $this->Report_model->wasteReport($start_date, $end_date, $user_id,$outlet_id);
        }
        $data['users'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_users');
        $data['main_content'] = $this->load->view('report/wasteReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * expense Report
     * @access public
     * @return void
     * @param no
     */
    public function expenseReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $expense_item_id =htmlspecialchars($this->input->post($this->security->xss_clean('expense_item_id')));
            $data['expense_item_id'] = $expense_item_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['expenseReport'] = $this->Report_model->expenseReport($start_date, $end_date, $expense_item_id,$outlet_id);
        }
        $data['expense_items'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_expense_items');
        $data['main_content'] = $this->load->view('report/expenseReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * kitchen Performance Report
     * @access public
     * @return void
     * @param no
     */
    public function kitchenPerformanceReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
            if(!$outlet_id){
                $outlet_id = $this->session->userdata('outlet_id');
            }
            $data['outlet_id'] = $outlet_id;
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate'))); 
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['kitchenPerformanceReport'] = $this->Report_model->kitchenPerformanceReport($start_date, $end_date,$outlet_id);
        } 
        $data['main_content'] = $this->load->view('report/kitchenPerformanceReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * supplier Due Report
     * @access public
     * @return void
     * @param no
     */
    public function supplierDueReport() {
        $data = array();
        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $data['outlet_id'] = $outlet_id;
        $data['supplierDueReport'] = $this->Report_model->supplierDueReport($outlet_id);
        $data['main_content'] = $this->load->view('report/supplierDueReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * customer Due Report
     * @access public
     * @return void
     * @param no
     */
    public function customerDueReport() {
        $data = array();
        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $data['outlet_id'] = $outlet_id;
        $data['customerDueReport'] = $this->Report_model->customerDueReport($outlet_id);
        $data['main_content'] = $this->load->view('report/customerDueReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * get Inventory Alert List
     * @access public
     * @return void
     * @param no
     */
    public function getInventoryAlertList() {
        $data = array();
        $data['inventory'] = $this->Report_model->getInventoryAlertList();
        $data['main_content'] = $this->load->view('report/inventoryAlertList', $data, TRUE);
        $this->load->view('userHome', $data);
    }
      /**
     * attendance Report
     * @access public
     * @return void
     * @param no
     */
    public function attendanceReport() {
        $data = array();
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $start_date =htmlspecialchars($this->input->post($this->security->xss_clean('startDate')));
            $end_date =htmlspecialchars($this->input->post($this->security->xss_clean('endDate')));
            $employee_id =htmlspecialchars($this->input->post($this->security->xss_clean('employee_id')));
            $data['employee_id'] = $employee_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['attendanceReport'] = $this->Report_model->attendanceReport($start_date, $end_date, $employee_id);
        }
        $company_id = $this->session->userdata('company_id');
        $data['employees'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_users");
        $data['main_content'] = $this->load->view('report/attendanceReport', $data, TRUE);
        $this->load->view('userHome', $data);
    }    

}
