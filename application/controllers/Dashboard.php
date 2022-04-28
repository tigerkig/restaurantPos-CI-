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
  # This is Dashboard Controller
  ###########################################################
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Inventory_model');
        $this->load->model('Report_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');
        
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }

        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2',lang('please_click_green_button'));

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
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    public function dashboard() {
        $first_day_this_month = date('Y-m-01');
        $last_day_this_month  = date('Y-m-t');
        $outlet_id  = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        if(!$outlet_id){
            $outlet_id = $this->session->userdata('outlet_id');
        }

        $data['food_menu_count'] =  $this->Dashboard_model->getAllFoodMenus($outlet_id);
        $data['ingredient_count'] = sizeof($this->Dashboard_model->getInventory('', '', '',$outlet_id));
        $data['customer_count'] = $this->Dashboard_model->countData('tbl_customers');
        $data['employee_count'] = $this->Dashboard_model->countData('tbl_users');

        $data['low_stock_ingredients'] = $this->Inventory_model->getInventoryAlertList($outlet_id);
        $data['top_ten_food_menu'] = $this->Dashboard_model->top_ten_food_menu($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['top_ten_customer'] = $this->Dashboard_model->top_ten_customer($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['customer_receivable'] = $this->Dashboard_model->customer_receivable($outlet_id);
        $data['supplier_payable'] = $this->Dashboard_model->supplier_payable($outlet_id);

        $data['dinein_count'] = $this->Dashboard_model->dinein_count($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['take_away_count'] = $this->Dashboard_model->take_away_count($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['delivery_count'] = $this->Dashboard_model->delivery_count($first_day_this_month, $last_day_this_month,$outlet_id);

        $data['purchase_sum'] = $this->Dashboard_model->purchase_sum($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['sale_sum'] = $this->Dashboard_model->sale_sum($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['waste_sum'] = $this->Dashboard_model->waste_sum($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['expense_sum'] = $this->Dashboard_model->expense_sum($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['customer_due_receive_sum'] = $this->Dashboard_model->customer_due_receive_sum($first_day_this_month, $last_day_this_month,$outlet_id);
        $data['supplier_due_payment_sum'] = $this->Dashboard_model->supplier_due_payment_sum($first_day_this_month, $last_day_this_month,$outlet_id);

        $data['outlets'] = $this->Common_model->getAllOutlestByAssign();
        $data['main_content'] = $this->load->view('dashboard/dashboard', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    function operation_comparision_by_date_ajax(){
        $from_this_day = $this->input->post('from_this_day');
        $to_this_day = $this->input->post('to_this_day');
        
        $data = array();

        $data['purchase_sum'] = $this->Dashboard_model->purchase_sum($from_this_day, $to_this_day);  
        $data['sale_sum'] = $this->Dashboard_model->sale_sum($from_this_day, $to_this_day);  
        $data['waste_sum'] = $this->Dashboard_model->waste_sum($from_this_day, $to_this_day);  
        $data['expense_sum'] = $this->Dashboard_model->expense_sum($from_this_day, $to_this_day);  
        $data['customer_due_receive_sum'] = $this->Dashboard_model->customer_due_receive_sum($from_this_day, $to_this_day);  
        $data['supplier_due_payment_sum'] = $this->Dashboard_model->supplier_due_payment_sum($from_this_day, $to_this_day);
        $data['from_this_day'] = $from_this_day;
        $data['to_this_day'] = $to_this_day;
        echo json_encode($data);
    }
     /**
     * bar panel
     * @access public
     * @return void
     * @param no
     */
    function comparison_sale_report_ajax_get() {
        $selectedMonth = $_GET['months'];
        $finalOutput = array();
        for ($i = $selectedMonth - 1; $i >= 0; $i--) {
            $dateCalculate = $i > 0 ? '-' . $i : $i;
            $sqlStartDate = date('Y-m-01', strtotime($dateCalculate . ' month'));
            $sqlEndDate = date('Y-m-31', strtotime($dateCalculate . ' month'));
            $saleAmount = $this->Common_model->comparison_sale_report($sqlStartDate, $sqlEndDate);
            $finalOutput[] = array(
                'month' => date('M-y', strtotime($dateCalculate . ' month')),
                'saleAmount' => !empty($saleAmount) ? $saleAmount->total_amount : 0.0,
            );
        }
        echo json_encode($finalOutput);
    }
}
