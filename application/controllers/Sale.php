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
  # This is Sale Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Sale_model');
        $this->load->model('Kitchen_model');
        $this->load->model('Bar_model');
        $this->load->model('Waiter_model');
        $this->load->model('Master_model');
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

        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', 'Please click on green Enter button of an outlet');

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }
        $is_waiter = $this->session->userdata('is_waiter');
        //check register is open or not
        if($is_waiter=="No"){
            $user_id = $this->session->userdata('user_id');
            $outlet_id = $this->session->userdata('outlet_id');
            if($this->Common_model->isOpenRegister($user_id,$outlet_id)==0){
                $this->session->set_flashdata('exception_3', 'Register is not open, enter your opening balance!');
                if($this->uri->segment(2)=='registerDetailCalculationToShowAjax' || $this->uri->segment(2)=='closeRegister'){
                    redirect('Register/openRegister');
                }else{
                    $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
                    $this->session->set_userdata("clicked_method", $this->uri->segment(2));
                    redirect('Register/openRegister');
                }

            }
        }

        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * sales info
     * @access public
     * @return void
     * @param no
     */
    public function sales() {
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['lists'] = $this->Sale_model->getSaleList($outlet_id);
        $data['main_content'] = $this->load->view('sale/sales', $data, TRUE);
        $this->load->view('userHome', $data);
    }

     /**
     * sales info
     * @access public
     * @return void
     * @param no
     */
    public function exportDailySales() {
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $fileName = 'Sale Data-'.(date("Y-m-d")).'.xlsx';

        // load excel library
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', lang('customer'));
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', lang('date'));
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', lang('reference'));
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', lang('items'));
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', lang('subtotal'));
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', lang('discount'));
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', lang('vat'));
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', lang('g_total'));
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', lang('payment_method'));
        // set Row
        $rowCount = 2;
        $sales = $this->Sale_model->exportDailySale();
        foreach ($sales as $key=>$value){
            $items = '';
            $details = $this->Sale_model->getAllItemsFromSalesDetailBySalesId($value->id);
            foreach ($details as $key1=>$value1){
                $items.= $value1->menu_name." X ".$value1->qty;
                if($key1 < (sizeof($details) -1)){
                    $items.= "\n";
                }
            }

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, escape_output($value->customer_name));
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, escape_output($value->sale_no));
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $items);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, escape_output(getAmtP($value->sub_total)));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, escape_output(getAmtP($value->total_discount_amount)));
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, escape_output(getAmtP($value->vat)));
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, escape_output(getAmtP($value->total_payable)));
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, escape_output($value->name));
            $rowCount++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);
        $objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save("asset/excel/".$fileName);
        // download file
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."asset/excel/".$fileName);
    }

    /**
     * reset Daily Sales Data
     * @access public
     * @return void
     * @param no
     */
    public function resetDailySales() {
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        //truncate all transactional data
        $this->db->query("TRUNCATE tbl_sales");
        $this->db->query("TRUNCATE tbl_sales_details");
        $this->db->query("TRUNCATE bl_sales_details_modifiers");
        $this->db->query("TRUNCATE tbl_sale_consumptions");
        $this->db->query("TRUNCATE tbl_sale_consumptions_of_menus");
        $this->db->query("TRUNCATE tbl_sale_consumptions_of_modifiers_of_menus");
        $this->session->set_flashdata('exception', lang('truncate_sale_update_success'));
        redirect('Sale/sales');
    }
     /**
     * delete Sale
     * @access public
     * @return void
     * @param int
     */
    public function deleteSale($id) {
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        if($this->session->userdata('role')=='Admin'){
            $isDeleted = $this->delete_specific_order_by_sale_id($id);
            if($isDeleted){
                $this->session->set_flashdata('exception', 'Information has been deleted successfully!');
                redirect('Sale/sales');
            }else{
                $this->session->set_flashdata('exception_2', 'Something went wrong!');
                redirect('Sale/sales');
            }
        }else{
            $this->session->set_flashdata('exception_2', 'Only admin is allowed to delete sale!');
            redirect('Sale/sales');
        }


    }
     /**
     * POS screen
     * @access public
     * @return void
     * @param int
     */
    public function POS($user_id='', $outlet_id=''){
        $is_waiter = $this->session->userdata('is_waiter');
        if(isset($is_waiter) && $is_waiter!="Yes"){
            $getAccessURL1 = ucfirst($this->uri->segment(2));
            if (!in_array($getAccessURL1, $this->session->userdata('menu_access'))) {
                redirect('Authentication/userProfile');
            }

        }

        if(!$user_id || !$outlet_id){
            redirect('POSChecker/posAndWaiterMiddleman');
        }

        $company_id = $this->session->userdata('company_id');

        $outlet_id = $this->session->userdata('outlet_id');

        $data = array();
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);

        $data['tables'] = $this->getTablesDetails($tables);
        $data['categories'] = $this->Sale_model->getFoodMenuCategories($company_id, 'tbl_food_menu_categories');
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
        $data['food_menus'] = $this->Sale_model->getAllFoodMenus();
        $data['menu_categories'] = $this->Sale_model->getAllMenuCategories();
        $data['menu_modifiers'] = $this->Sale_model->getAllMenuModifiers();
        $data['waiters'] = $this->Sale_model->getWaitersForThisCompany($company_id,'tbl_users');
        $data['new_orders'] = $this->get_new_orders();
        $data['outlet_information'] = $this->Common_model->getDataById($outlet_id, "tbl_outlets");
        $data['payment_methods'] = $this->Sale_model->getAllPaymentMethods();
        $data['notifications'] = $this->get_new_notification();
        $this->load->view('sale/POS/main_screen', $data);
    }
     /**
     * get Tables Details
     * @access public
     * @return object
     * @param string
     */
    public function getTablesDetails($tables){
        foreach($tables as $table){
            $table->orders_table = $this->Sale_model->getOrdersOfTableByTableId($table->id);
            foreach($table->orders_table as $order_table){

                $to_time = strtotime(date('Y-m-d H:i:s'));
                $from_time = strtotime($order_table->booking_time);
                $minutes = floor(abs($to_time - $from_time) / 60);
                $seconds = abs($to_time - $from_time) % 60;

                $order_table->booked_in_minute = $minutes;
            }
        }
        return $tables;
    }
     /**
     * Save sales data
     * @access public
     * @return void
     * @param no
     */
    public function Save() {
        $data = array();
        $data['customer_id'] = $this->input->get('customer_id');
        $data['total_items'] = $this->input->get('total_items');
        $data['sub_total'] = $this->input->get('sub_total');
        $data['disc'] = $this->input->get('disc');
        $data['disc_actual'] = $this->input->get('disc_actual');
        $data['vat'] = $this->input->get('vat');
        $data['paid_amount'] = $this->input->get('paid_amount');
        $data['due_amount'] = $this->input->get('due_amount');
        $data['table_id'] = $this->input->get('table_id');
        $data['token_no'] = $this->input->get('token_no');
        if ($this->input->get('due_payment_date')) {
            $data['due_payment_date'] = $this->input->get('due_payment_date');
        } else {
            $data['due_payment_date'] = Null;
        }

        $data['total_payable'] = $this->input->get('total_payable');
        $data['payment_method_id'] = $this->input->get('payment_method_id');
        $data['user_id'] = $this->session->userdata('user_id');
        $data['outlet_id'] = $this->session->userdata('outlet_id');
        $data['sale_date'] = $this->input->get('sale_date');
        $data['sale_time'] = date('h:i A');
        $outlet_id = $this->session->userdata('outlet_id');
        $sale_no = $this->db->query("SELECT count(id) as bno
               FROM tbl_sales WHERE outlet_id=$outlet_id")->row('bno');
        $sale_no = str_pad($sale_no + 1, 6, '0', STR_PAD_LEFT);
        $data['sale_no'] = $sale_no;
        ////////////
        $food_menu_id = $this->input->get('food_menu_id');
        $menu_name = $this->input->get('menu_name');
        $price = $this->input->get('price');
        $qty = $this->input->get('qty');
        $discount_amount = $this->input->get('discountNHiddenTotal');
        $total = $this->input->get('total');
        /////////////////////
        $i = 0;
        $this->db->trans_begin();
        $query = $this->db->insert('tbl_sales', $data);
        $sales_id = $this->db->insert_id();

        $comsump = array();
        $comsump['outlet_id'] = $this->session->userdata('outlet_id');
        $comsump['date'] = date('Y-m-d');
        $comsump['date_time'] = date('h:i A');
        $comsump['user_id'] = $this->session->userdata('user_id');
        $comsump['sale_id'] = $sales_id;
        $query = $this->db->insert('tbl_sale_consumptions', $comsump);
        $sale_consumption_id = $this->db->insert_id();

        //////////////////////////////////
        foreach ($food_menu_id as $value) {
            $data1['food_menu_id'] = $value;
            $data1['sales_id'] = $sales_id;
            $data1['menu_name'] = $menu_name[$i];
            $data1['price'] = $price[$i];
            $data1['qty'] = $qty[$i];
            $data1['discount_amount'] = $discount_amount[$i];
            $data1['total'] = $total[$i];
            $data1['user_id'] = $this->session->userdata('user_id');
            $data1['outlet_id'] = $this->session->userdata('outlet_id');
            $this->db->insert('tbl_sales_details', $data1);
            //////////////////////

            $ingredlist = $this->Sale_model->getFoodMenuIngredients($value);
            foreach ($ingredlist as $inrow) {
                $data3 = array();
                $data3['sale_consumption_id'] = $sale_consumption_id;
                $data3['ingredient_id'] = $inrow->ingredient_id;
                $data3['consumption'] = $inrow->consumption * $qty[$i];
                $data3['user_id'] = $this->session->userdata('user_id');
                $data3['outlet_id'] = $this->session->userdata('outlet_id');
                $this->db->insert('tbl_sale_consumptions_of_menus', $data3);
            }
            //////////////////////
            $i++;
        }
        $returndata = array('sales_id' => $sales_id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            echo json_encode($returndata);
            $this->db->trans_commit();
        }
    }
     /**
     * delete Suspend
     * @access public
     * @return object
     * @param no
     */
    public function deleteSuspend() {
        $suspendID = $this->input->get('minusSuspendID');
        $this->session->unset_userdata('customer_id_' . $suspendID);
        $this->session->unset_userdata('total_item_hidden_' . $suspendID);
        $this->session->unset_userdata('sub_total_' . $suspendID);
        $this->session->unset_userdata('disc_' . $suspendID);
        $this->session->unset_userdata('disc_actual_' . $suspendID);
        $this->session->unset_userdata('vat_' . $suspendID);
        $this->session->unset_userdata('gTotalDisc_' . $suspendID);
        $this->session->unset_userdata('total_payable_' . $suspendID);
        $this->session->unset_userdata('tables_' . $suspendID);
        $this->session->unset_userdata('countSuspend_' . $suspendID);
        $this->session->unset_userdata('countTimeSuspend_' . $suspendID);
        $this->session->unset_userdata('countSuspendCurrent');
        echo json_encode("success");
    }
     /**
     * get Suspend
     * @access public
     * @return object
     * @param no
     */
    public function getSuspend() {
        $suspendID = $this->input->get('suspendID');
        $checkSuspend = $this->session->userdata('countSuspend_' . $suspendID);
        if ($checkSuspend) {
            $data['status'] = true;
            $data['sus_id'] = $suspendID;
            $data['customer_id'] = $this->session->userdata('customer_id_' . $suspendID);
            $data['total_item_hidden'] = $this->session->userdata('total_item_hidden_' . $suspendID);
            $data['sub_total'] = $this->session->userdata('sub_total_' . $suspendID);
            $data['disc'] = $this->session->userdata('disc_' . $suspendID);
            $data['disc_actual'] = $this->session->userdata('disc_actual_' . $suspendID);
            $data['gTotalDisc'] = $this->session->userdata('gTotalDisc_' . $suspendID);
            $data['vat'] = $this->session->userdata('vat_' . $suspendID);
            $data['total_payable'] = $this->session->userdata('total_payable_' . $suspendID);
            $data['tables'] = $this->session->userdata('tables_' . $suspendID);
        } else {
            $data['status'] = false;
        }
        echo json_encode($data);
    }
     /**
     * get Suspend Current
     * @access public
     * @return object
     * @param no
     */
    public function getSuspendCurrent() {

        $checkSuspend = $this->session->userdata('countSuspendCurrent');
        $suspendID = "current";

        $data['status'] = true;
        $data['customer_id'] = $this->session->userdata('customer_id_' . $suspendID);
        $data['total_item_hidden'] = $this->session->userdata('total_item_hidden_' . $suspendID);
        $data['sub_total'] = $this->session->userdata('sub_total_' . $suspendID);
        $data['disc'] = $this->session->userdata('disc_' . $suspendID);
        $data['disc_actual'] = $this->session->userdata('disc_actual_' . $suspendID);
        $data['vat'] = $this->session->userdata('vat_' . $suspendID);
        $data['gTotalDisc'] = $this->session->userdata('gTotalDisc_' . $suspendID);
        $data['total_payable'] = $this->session->userdata('total_payable_' . $suspendID);
        $data['tables'] = $this->session->userdata('tables_' . $suspendID);
        echo json_encode($data);
    }
     /**
     * set Suspend
     * @access public
     * @return object
     * @param no
     */
    public function setSuspend() {
        $check1 = $this->session->userdata('countSuspend_1');
        $check2 = $this->session->userdata('countSuspend_2');
        $check3 = $this->session->userdata('countSuspend_3');

        $checkTime1 = $this->session->userdata('countTimeSuspend_1');
        $checkTime2 = $this->session->userdata('countTimeSuspend_2');
        $checkTime3 = $this->session->userdata('countTimeSuspend_3');

        $times = date('Y-m-d h:i:s');

        if (!$check1) {
            $temp = 1;
            $this->session->set_userdata('countSuspend_1', 1);
            $this->session->set_userdata('countTimeSuspend_1', $times);
        } elseif (!$check2) {
            $temp = 2;
            $this->session->set_userdata('countSuspend_2', 2);
            $this->session->set_userdata('countTimeSuspend_2', $times);
        } elseif (!$check3) {
            $this->session->set_userdata('countSuspend_3', 3);
            $this->session->set_userdata('countTimeSuspend_3', $times);
            $temp = 3;
        } else {

            if ($checkTime1 < $checkTime2) {
                if ($checkTime1 < $checkTime3) {
                    $temp = 1;
                    $this->session->unset_userdata('countSuspend_' . $temp);
                    $this->session->set_userdata('countSuspend_1', 1);
                    $this->session->unset_userdata('countTimeSuspend_' . $temp);
                    $this->session->set_userdata('countTimeSuspend_1', $times);
                } else {
                    $temp = 3;
                    $this->session->unset_userdata('countSuspend_' . $temp);
                    $this->session->set_userdata('countSuspend_3', 3);
                    $this->session->unset_userdata('countTimeSuspend_' . $temp);
                    $this->session->set_userdata('countTimeSuspend_3', $times);
                }
            } else {
                if ($checkTime2 < $checkTime3) {
                    $temp = 2;
                    $this->session->unset_userdata('countSuspend_' . $temp);
                    $this->session->set_userdata('countSuspend_2', 2);
                    $this->session->unset_userdata('countTimeSuspend_' . $temp);
                    $this->session->set_userdata('countTimeSuspend_2', $times);
                } else {
                    $temp = 3;
                    $this->session->unset_userdata('countSuspend_' . $temp);
                    $this->session->set_userdata('countSuspend_3', 3);
                    $this->session->unset_userdata('countTimeSuspend_' . $temp);
                    $this->session->set_userdata('countTimeSuspend_3', $times);
                }
            }
        }

        //set session value
        $i = 0;
        $food_menu_id = $this->input->get('food_menu_id');
        $menu_name = $this->input->get('menu_name');
        $price = $this->input->get('price');
        $qty = $this->input->get('qty');
        $VATHidden = $this->input->get('VATHidden');
        $VATHiddenTotal = $this->input->get('VATHiddenTotal');
        $discountN = $this->input->get('discountN');
        $discountNHidden = $this->input->get('discountNHidden');
        $discountNHiddenTotal = $this->input->get('discountNHiddenTotal');
        $total = $this->input->get('total');
        $tableRow = "";
        foreach ($food_menu_id as $value) {
            $trID = "row_" . $i;
            $inputID = "food_menu_id_" . $i;
            $tableRow .= "<tr data-id='$i' class='clRow' id='row_$i'><input id='food_menu_id_$i' name='food_menu_id[]' value='$value' type='hidden'><input id='$inputID' name='menu_name[]' value='$menu_name[$i]' type='hidden'><input id='discountNHidden_$i' name='discountNHidden[]' value='$discountNHidden[$i]' type='hidden'><input id='discountNHiddenTotal_$i' name='discountNHiddenTotal[]' value='$discountNHiddenTotal[$i]' type='hidden'><input id='VATHidden_$i' name='VATHidden[]' value='$VATHidden[$i]' type='hidden'><input id='VATHiddenTotal_$i' name='VATHiddenTotal[]' value='$VATHiddenTotal[$i]' type='hidden'><td>$menu_name[$i]</td><td><input class='pri-size txtboxToFilter' onfocus='this.select();' id='price_$i' name='price[]' value='$price[$i]' onblur='return calculateRow($i);' onkeyup='return calculateRow($i)' type='text'></td><td><input class='qty-size txtboxToFilter' onfocus='this.select();' min='1' id='qty_$i' name='qty[]' value='$qty[$i]' onmouseup='return helloThere($i)' onblur='return calculateRow($i);' onkeyup='return checkQuantity($i);' onkeydown='return calculateRow($i);' type='number'></td><td><input class='qty-size discount' onfocus='this.select();'  id='discountN_$i' name='discountN[]' value='$discountN[$i]' onmouseup='return helloThere($i)' onblur='return calculateRow($i);' onkeyup='return checkQuantity($i);' onkeydown='return calculateRow($i);' type='text'></td><td><input class='pri-size' readonly='' id='total_$i' name='total[]' style='background-color: #dddddd;border:1px solid #7e7f7f;' value='$total[$i]' type='text'></td><td style='text-align: center'><a class='btn btn-danger btn-xs' onclick='return deleter($i,$value);'><i style='color:white' class='fa fa-trash'></i></a></td></tr>";
            $i++;
        }
        $customer_id = $this->input->get('customer_id');
        $total_item_hidden = $this->input->get('total_items');
        $sub_total = $this->input->get('sub_total');
        $disc = $this->input->get('disc');
        $disc_actual = $this->input->get('disc_actual');
        $vat = $this->input->get('vat');
        $gTotalDisc = $this->input->get('gTotalDisc');
        $total_payable = $this->input->get('total_payable');
        $tables = $tableRow;
        $this->session->set_userdata('customer_id_' . $temp, $customer_id);
        $this->session->set_userdata('total_item_hidden_' . $temp, $total_item_hidden);
        $this->session->set_userdata('sub_total_' . $temp, $sub_total);
        $this->session->set_userdata('disc_' . $temp, $disc);
        $this->session->set_userdata('disc_actual_' . $temp, $disc_actual);
        $this->session->set_userdata('vat_' . $temp, $vat);
        $this->session->set_userdata('gTotalDisc_' . $temp, $gTotalDisc);
        $this->session->set_userdata('total_payable_' . $temp, $total_payable);
        $this->session->set_userdata('tables_' . $temp, $tables);
        $data['suspend_id'] = $temp;
        echo json_encode($data);
    }
     /**
     * set Suspend Current
     * @access public
     * @return object
     * @param no
     */
    public function setSuspendCurrent() {

        $currentStatus = $this->input->get('currentStatus');
        if ($currentStatus == "1") {
            $temp = "current";
            $this->session->set_userdata('countSuspendCurrent', 1);
            //set session value
            $i = 0;
            $ingredient_id = $this->input->get('ingredient_id');
            $menu_name = $this->input->get('menu_name');
            $price = $this->input->get('price');
            $qty = $this->input->get('qty');
            $VATHidden = $this->input->get('VATHidden');
            $VATHiddenTotal = $this->input->get('VATHiddenTotal');
            $discountN = $this->input->get('discountN');
            $discountNHidden = $this->input->get('discountNHidden');
            $discountNHiddenTotal = $this->input->get('discountNHiddenTotal');
            $total = $this->input->get('total');
            $tableRow = "";
            foreach ($ingredient_id as $value) {
                $trID = "row_" . $i;
                $inputID = "ingredient_id_" . $i;
                $tableRow .= "<tr data-id='$i' class='clRow' id='row_$i'><input id='ingredient_id_$i' name='ingredient_id[]' value='$value' type='hidden'><input id='$inputID' name='menu_name[]' value='$menu_name[$i]' type='hidden'><input id='discountNHidden_$i' name='discountNHidden[]' value='$discountNHidden[$i]' type='hidden'><input id='discountNHiddenTotal_$i' name='discountNHiddenTotal[]' value='$discountNHiddenTotal[$i]' type='hidden'><input id='VATHidden_$i' name='VATHidden[]' value='$VATHidden[$i]' type='hidden'><input id='VATHiddenTotal_$i' name='VATHiddenTotal[]' value='$VATHiddenTotal[$i]' type='hidden'><td>$menu_name[$i]</td><td><input class='pri-size txtboxToFilter' onfocus='this.select();' id='price_$i' name='price[]' value='$price[$i]' onblur='return calculateRow($i);' onkeyup='return calculateRow($i)' type='text'></td><td><input class='qty-size txtboxToFilter' onfocus='this.select();' min='1' id='qty_$i' name='qty[]' value='$qty[$i]' onmouseup='return helloThere($i)' onblur='return calculateRow($i);' onkeyup='return checkQuantity($i);' onkeydown='return calculateRow($i);' type='number'></td><td><input class='qty-size discount' onfocus='this.select();'  id='discountN_$i' name='discountN[]' value='$discountN[$i]' onmouseup='return helloThere($i)' onblur='return calculateRow($i);' onkeyup='return checkQuantity($i);' onkeydown='return calculateRow($i);' type='text'></td><td><input class='pri-size' readonly='' id='total_$i' name='total[]' style='background-color: #dddddd;border:1px solid #7e7f7f;' value='$total[$i]' type='text'></td><td style='text-align: center'><a class='btn btn-danger btn-xs' onclick='return deleter($i,$value);'><i style='color:white' class='fa fa-trash'></i></a></td></tr>";
                $i++;
            }
            $customer_id = $this->input->get('customer_id');
            $total_item_hidden = $this->input->get('total_items');
            $sub_total = $this->input->get('sub_total');
            $disc = $this->input->get('disc');
            $disc_actual = $this->input->get('disc_actual');
            $vat = $this->input->get('vat');
            $total_payable = $this->input->get('total_payable');
            $tables = $tableRow;

            $this->session->set_userdata('customer_id_' . $temp, $customer_id);
            $this->session->set_userdata('total_item_hidden_' . $temp, $total_item_hidden);
            $this->session->set_userdata('sub_total_' . $temp, $sub_total);
            $this->session->set_userdata('disc_' . $temp, $disc);
            $this->session->set_userdata('disc_actual_' . $temp, $disc_actual);
            $this->session->set_userdata('vat_' . $temp, $vat);
            $this->session->set_userdata('total_payable_' . $temp, $total_payable);
            $this->session->set_userdata('tables_' . $temp, $tables);
            $data['suspend_id'] = $temp;

            echo json_encode($data);
        }
    }
     /**
     * set Service Session
     * @access public
     * @return object
     * @param no
     */
    public function setServiceSession() {
        $serviceValue = $this->input->get('serviceValue');
        $this->session->set_userdata('serviceSession', $serviceValue);
    }
     /**
     * get Service Session
     * @access public
     * @return object
     * @param no
     */
    public function getServiceSession() {
        $serviceValue = $this->session->userdata['serviceSession'];
        $data['serviceData'] = $serviceValue;
        echo json_encode($data);
    }
     /**
     * view invoice
     * @access public
     * @return void
     * @param int
     */
    public function view($sales_id=3) {
        $sales_id = $this->custom->encrypt_decrypt($sales_id, 'decrypt');
        $data = array();
        $data['info'] = $this->Sale_model->getSaleInfo($sales_id);
        $data['details'] = $this->Sale_model->getSaleDetails($sales_id);
        $this->load->view('sale/print', $data);
    }
     /**
     * view A4 size invoice
     * @access public
     * @return void
     * @param int
     */
    public function view_A4($sales_id) {
        $sales_id = $this->custom->encrypt_decrypt($sales_id, 'decrypt');
        $data = array();
        $data['info'] = $this->Sale_model->getSaleInfo($sales_id);
        $data['details'] = $this->Sale_model->getSaleDetails($sales_id);
        $this->load->view('sale/print_A4', $data);
    }
     /**
     * view invoice
     * @access public
     * @return void
     * @param int
     */
    public function view_invoice($sales_id) {
        $sales_id = $this->custom->encrypt_decrypt($sales_id, 'decrypt');
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['info'] = $this->Sale_model->getSaleInfo($sales_id);
        $data['details'] = $this->Sale_model->getSaleDetails($sales_id);
        $print_format = $this->session->userdata('print_format');
        if($print_format=="80mm"){
            $this->load->view('sale/print_invoice', $data);
        }else{
            $this->load->view('sale/print_invoice_56mm', $data);
        }
    }
     /**
     * save Sales Items
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveSalesItems($item_menu_items, $ingredient_id, $table_name) {
        foreach ($item_menu_items as $row => $ingredient_id):
            $fmi = array();
            $fmi['ingredient_id'] = $ingredient_id;
            $fmi['consumption'] = $_POST['consumption'][$row];
            $fmi['ingredient_id'] = $ingredient_id;
            $fmi['user_id'] = $this->session->userdata('user_id');
            $fmi['outlet_id'] = $this->session->userdata('outlet_id');
            $this->Common_model->insertInformation($fmi, "tbl_sales_items");
        endforeach;
    }
     /**
     * item Menu Details
     * @access public
     * @return void
     * @param int
     */
    public function itemMenuDetails($id) {
        $encrypted_id = $id;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['item_menu_details'] = $this->Common_model->getDataById($id, "tbl_sales");
        $data['main_content'] = $this->load->view('sale/itemMenuDetails', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * add New Customer By Ajax
     * @access public
     * @return object
     * @param no
     */
    public function addNewCustomerByAjax() {
        $data['name'] = $_GET['customer_name'];
        $data['phone'] = $_GET['mobile_no'];
        $data['email'] = $_GET['customerEmail'];
        $data['date_of_birth'] = $_GET['customerDateOfBirth'];
        $data['date_of_anniversary'] = $_GET['customerDateOfAnniversary'];
        $data['address'] = $_GET['customerAddress'];
        $data['user_id'] = $this->session->userdata('user_id');
        $data['company_id'] = $this->session->userdata('company_id');
        $this->db->insert('tbl_customers', $data);
        $customer_id = $this->db->insert_id();
        $data1 = array('customer_id' => $customer_id);
        echo json_encode($data1);
    }
     /**
     * getEncriptValue
     * @access public
     * @return object
     * @param no
     */
    public function getEncriptValue() {
        $id = $this->custom->encrypt_decrypt($_GET['sales_id'], 'encrypt');
        $data['encriptID'] = $id;
        echo json_encode($data);
    }
     /**
     * get Customer List
     * @access public
     * @return object
     * @param no
     */
    public function getCustomerList() {
        $company_id = $this->session->userdata('company_id');
        $data1 = $this->db->query("SELECT * FROM tbl_customers 
              WHERE company_id=$company_id")->result();
        //generate html content for view
        foreach ($data1 as $value) {
            if ($value->name == "Walk-in Customer") {
                echo '<option value="' . $value->id . '" >' . $value->name . '</option>';
            }
        }
        //generate html content for view
        foreach ($data1 as $value) {
            if ($value->name != "Walk-in Customer") {
                echo '<option value="' . $value->id . '" >' . $value->name . ' (' . $value->phone . ')' . '</option>';
            }
        }
        exit;
    }
     /**
     * add customer by ajax
     * @access public
     * @return int
     * @param no
     */
    public function add_customer_by_ajax(){
        $customer_id =htmlspecialchars($this->input->post($this->security->xss_clean('customer_id')));
        $data['name'] = trim(htmlspecialchars($this->input->post($this->security->xss_clean('customer_name'))));
        $data['phone'] = trim(htmlspecialchars($this->input->post($this->security->xss_clean('customer_phone'))));
        $data['email'] = trim($this->input->post($this->security->xss_clean('customer_email')));
        if($this->input->post($this->security->xss_clean('customer_dob'))){
            $data['date_of_birth'] = date('Y-m-d',strtotime($this->input->post($this->security->xss_clean('customer_dob'))));
        }
        if($this->input->post($this->security->xss_clean('customer_doa'))){
            $data['date_of_anniversary'] = date('Y-m-d',strtotime($this->input->post($this->security->xss_clean('customer_doa'))));
        }
        $data['address'] = trim(preg_replace('/\s+/', ' ',htmlspecialchars($this->input->post($this->security->xss_clean('customer_delivery_address')))));
        $data['gst_number'] = trim($this->input->post($this->security->xss_clean('customer_gst_number')));
        $data['user_id'] = $this->session->userdata('user_id');
        $data['company_id'] = $this->session->userdata('company_id');
       $id_return = 0;
        if($customer_id>0 && $customer_id!=""){
            $this->db->where('id', $customer_id);
            $this->db->update('tbl_customers', $data);
            $id_return =  $customer_id;
        }else{
            $this->db->insert('tbl_customers', $data);
            $id_return =  $this->db->insert_id();
        }
        echo $id_return ;
    }
     /**
     * get all customers for this user
     * @access public
     * @return object
     * @param no
     */
    public function get_all_customers_for_this_user(){
        $company_id = $this->session->userdata('company_id');
        $data1 = $this->db->query("SELECT * FROM tbl_customers 
              WHERE company_id=$company_id")->result();
        echo json_encode($data1);
    }
     /**
     * add sale by ajax
     * @access public
     * @return int
     * @param no
     */
    public function add_sale_by_ajax(){
        $order_details = json_decode(json_decode($this->input->post('order')));

        //this id will be 0 when there is new order, but will be greater then 0 when there is modification
        //on previous order
        $sale_id = $this->input->post('sale_id');
        $data = array();
        $data['customer_id'] = trim($order_details->customer_id);
        $data['total_items'] = trim($order_details->total_items_in_cart);
        $data['sub_total'] = trim($order_details->sub_total);
        $data['charge_type'] = trim($order_details->charge_type);
        $data['vat'] = trim($order_details->total_vat);
        $data['total_payable'] = trim($order_details->total_payable);
        $data['total_item_discount_amount'] = trim($order_details->total_item_discount_amount);
        $data['sub_total_with_discount'] = trim($order_details->sub_total_with_discount);
        $data['sub_total_discount_amount'] = trim($order_details->sub_total_discount_amount);
        $data['total_discount_amount'] = trim($order_details->total_discount_amount);
        $data['delivery_charge'] = trim($order_details->delivery_charge);
        $data['delivery_charge_actual_charge'] = trim($order_details->delivery_charge_actual_charge);
        $data['sub_total_discount_value'] = trim($order_details->sub_total_discount_value);
        $data['sub_total_discount_type'] = trim($order_details->sub_total_discount_type);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['waiter_id'] = trim($order_details->waiter_id);
        $data['outlet_id'] = $this->session->userdata('outlet_id');
        $data['company_id'] = $this->session->userdata('company_id');
        $data['sale_date'] = trim(isset($order_details->open_invoice_date_hidden) && $order_details->open_invoice_date_hidden?$order_details->open_invoice_date_hidden:date('Y-m-d'));
        $data['date_time'] = date('Y-m-d H:i:s');
        $data['order_time'] = date("H:i:s");
        $data['order_status'] = trim($order_details->order_status);
        $today_ = date('Y-m-d');
        if($today_<$data['sale_date']){
            //1 is runny sale, 2 is future sales, 3 is future status null
            $data['future_sale_status'] = 2;
        }

        $total_tax = 0;
        if(isset($order_details->sale_vat_objects) && $order_details->sale_vat_objects){
            foreach ($order_details->sale_vat_objects as $keys=>$val){
                $total_tax+=$val->tax_field_amount;
            }
        }
        $data['vat'] = $total_tax;
        $data['sale_vat_objects'] = json_encode($order_details->sale_vat_objects);
        $data['order_type'] = trim($order_details->order_type);
        $this->db->trans_begin();
        if($sale_id>0){
            $data['modified'] = 'Yes';
            $this->db->where('id', $sale_id);
            $this->db->update('tbl_sales', $data);
            //this section sends notification to bar/kitchen panel if there is any modification
            $single_table_information = $this->get_all_information_of_a_sale($sale_id);
            $order_number = '';
            if($single_table_information->order_type==1){
                $order_number = 'A '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==2){
                $order_number = 'B '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==3){
                $order_number = 'C '.$single_table_information->sale_no;
            }
            $notification_message = 'Order:'.$order_number.' has been modified';
            $bar_kitchen_notification_data = array();
            $bar_kitchen_notification_data['notification'] = $notification_message;
            $bar_kitchen_notification_data['outlet_id'] = $this->session->userdata('outlet_id');;
            $query = $this->db->insert('tbl_notification_bar_kitchen_panel', $bar_kitchen_notification_data);
            //end of send notification process
            $this->db->delete('tbl_sales_details', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sales_details_modifiers', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions', array('sale_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions_of_menus', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions_of_modifiers_of_menus', array('sales_id' => $sale_id));

            $sales_id = $sale_id;
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
        }else{
            $query = $this->db->insert('tbl_sales', $data);
            $sales_id = $this->db->insert_id();
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
            $sale_no_update_array = array('sale_no' => $sale_no);
            $this->db->where('id', $sales_id);
            $this->db->update('tbl_sales', $sale_no_update_array);
        }
        foreach($order_details->orders_table as $single_order_table){
            $order_table_info = array();
            $order_table_info['persons'] = $single_order_table->persons;
            $order_table_info['booking_time'] = date('Y-m-d H:i:s');
            $order_table_info['sale_id'] = $sales_id;
            $order_table_info['sale_no'] = $sale_no;
            $order_table_info['outlet_id'] = $this->session->userdata('outlet_id');
            $order_table_info['table_id'] = $single_order_table->table_id;
            $query = $this->db->insert('tbl_orders_table',$order_table_info);
        }
        $data_sale_consumptions = array();
        $data_sale_consumptions['sale_id'] = $sales_id;
        $data_sale_consumptions['user_id'] = $this->session->userdata('user_id');
        $data_sale_consumptions['outlet_id'] = $this->session->userdata('outlet_id');
        $data_sale_consumptions['del_status'] = 'Live';
        $query = $this->db->insert('tbl_sale_consumptions',$data_sale_consumptions);
        $sale_consumption_id = $this->db->insert_id();

        if($sales_id>0 && count($order_details->items)>0){
            foreach($order_details->items as $item){
                $tmp_var_111 = isset($item->p_qty) && $item->p_qty && $item->p_qty!='undefined'?$item->p_qty:0;
                $tmp = $item->item_quantity-$tmp_var_111;
                $tmp_var = 0;
                if($tmp>0){
                    $tmp_var = $tmp;
                }

                $item_date = array();
                $item_data['food_menu_id'] = $item->item_id;
                $item_data['menu_name'] = $item->item_name;
                $item_data['qty'] = $item->item_quantity;
                $item_data['tmp_qty'] = $tmp_var;
                $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                $item_data['menu_unit_price'] = $item->item_unit_price;
                $item_data['menu_taxes'] = json_encode($item->item_vat);
                $item_data['menu_discount_value'] = $item->item_discount;
                $item_data['discount_type'] = $item->discount_type;
                $item_data['menu_note'] = $item->item_note;
                $item_data['discount_amount'] = $item->item_discount_amount;
                $item_data['item_type'] = ($this->Sale_model->getItemType($item->item_id)->item_type=="Bar No")?"Kitchen Item":"Bar Item";
                $item_data['cooking_status'] = ($item->item_cooking_status=="")?NULL:$item->item_cooking_status;
                $item_data['cooking_start_time'] = ($item->item_cooking_start_time=="" || $item->item_cooking_start_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_start_time));
                $item_data['cooking_done_time'] = ($item->item_cooking_done_time=="" || $item->item_cooking_done_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_done_time));
                $item_data['previous_id'] = ($item->item_previous_id=="")?0:$item->item_previous_id;
                $item_data['sales_id'] = $sales_id;
                $item_data['user_id'] = $this->session->userdata('user_id');
                $item_data['outlet_id'] = $this->session->userdata('outlet_id');
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_sales_details', $item_data);
                $sales_details_id = $this->db->insert_id();

                if($item->item_previous_id==""){
                    $previous_id_update_array = array('previous_id' => $sales_details_id);
                    $this->db->where('id', $sales_details_id);
                    $this->db->update('tbl_sales_details', $previous_id_update_array);
                }


                $food_menu_ingredients = $this->db->query("SELECT * FROM tbl_food_menus_ingredients WHERE food_menu_id=$item->item_id")->result();

                foreach($food_menu_ingredients as $single_ingredient){

                    $data_sale_consumptions_detail = array();
                    $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                    $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption;
                    $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                    $data_sale_consumptions_detail['sales_id'] = $sales_id;
                    $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                    $data_sale_consumptions_detail['user_id'] = $this->session->userdata('outlet_id');
                    $data_sale_consumptions_detail['outlet_id'] = $this->session->userdata('outlet_id');
                    $data_sale_consumptions_detail['del_status'] = 'Live';
                    $query = $this->db->insert('tbl_sale_consumptions_of_menus',$data_sale_consumptions_detail);
                }

                $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;
                $modifier_vat_array = (isset($item->modifier_vat) && $item->modifier_vat!="")?explode("|||",$item->modifier_vat):null;
                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $key1=>$single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id;
                        $modifier_data['modifier_price'] = $modifier_price_array[$i];
                        $modifier_data['food_menu_id'] = $item->item_id;
                        $modifier_data['sales_id'] = $sales_id;
                        $modifier_data['sales_details_id'] = $sales_details_id;
                        $modifier_data['menu_taxes'] = isset($modifier_vat_array[$key1]) && $modifier_vat_array[$key1]?$modifier_vat_array[$key1]:'';
                        $modifier_data['user_id'] = $this->session->userdata('user_id');
                        $modifier_data['outlet_id'] = $this->session->userdata('outlet_id');
                        $modifier_data['customer_id'] =$order_details->customer_id;
                        $query = $this->db->insert('tbl_sales_details_modifiers', $modifier_data);

                        $modifier_ingredients = $this->db->query("SELECT * FROM tbl_modifier_ingredients WHERE modifier_id=$single_modifier_id")->result();

                        foreach($modifier_ingredients as $single_ingredient){
                            $data_sale_consumptions_detail = array();
                            $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                            $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption;
                            $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                            $data_sale_consumptions_detail['sales_id'] = $sales_id;
                            $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                            $data_sale_consumptions_detail['user_id'] = $this->session->userdata('user_id');
                            $data_sale_consumptions_detail['outlet_id'] = $this->session->userdata('outlet_id');
                            $data_sale_consumptions_detail['del_status'] = 'Live';
                            $query = $this->db->insert('tbl_sale_consumptions_of_modifiers_of_menus',$data_sale_consumptions_detail);
                        }

                        $i++;
                    }
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            echo escape_output($sales_id);
            $this->db->trans_commit();
        }

    }
     /**
     * add sale by ajax
     * @access public
     * @return int
     * @param no
     */
    public function set_as_running_order(){
        $sale_id = $this->input->post('sale_id');
        $status = $this->input->post('status');
        $data = array();
        $data['future_sale_status'] = $status;
        $this->db->where('id', $sale_id);
        $this->db->update('tbl_sales', $data);
        echo json_encode("success");
    }
     /**
     * get new orders
     * @access public
     * @return object
     * @param no
     */
    public function get_new_orders_ajax(){
        $data1 = $this->get_new_orders();
        echo json_encode($data1);
    }
     /**
     * get new orders
     * @access public
     * @return object
     * @param no
     */
    public function get_new_orders(){
        $outlet_id = $this->session->userdata('outlet_id');
        $data1 = $this->Sale_model->getNewOrders($outlet_id);
        $i = 0;
        for($i;$i<count($data1);$i++){
            $data1[$i]->total_kitchen_type_items = $this->Sale_model->get_total_kitchen_type_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_done_items = $this->Sale_model->get_total_kitchen_type_done_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_started_cooking_items = $this->Sale_model->get_total_kitchen_type_started_cooking_items($data1[$i]->sale_id);
            $data1[$i]->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($data1[$i]->sale_id);

            $to_time = strtotime(date('Y-m-d H:i:s'));
            $from_time = strtotime($data1[$i]->date_time);
            $minutes = floor(abs($to_time - $from_time) / 60);
            $seconds = abs($to_time - $from_time) % 60;

            $data1[$i]->minute_difference = str_pad(floor($minutes), 2, "0", STR_PAD_LEFT);
            $data1[$i]->second_difference = str_pad(floor($seconds), 2, "0", STR_PAD_LEFT);
        }
        return $data1;
    }
     /**
     * get all tables with new status
     * @access public
     * @return object
     * @param no
     */
    public function get_all_tables_with_new_status_ajax(){
        $outlet_id = $this->session->userdata('outlet_id');
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);
        $data1 = new \stdClass();
        $data1->table_details = $this->getTablesDetails($tables);
        $data1->table_availability = $this->Sale_model->getTableAvailability($outlet_id);
        echo json_encode($data1);
    }
     /**
     * get all information of a sale ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_all_information_of_a_sale_ajax(){
        $sales_id = $this->input->post('sale_id');
        $sale_object = $this->get_all_information_of_a_sale($sales_id);
        echo json_encode($sale_object);
    }
     /**
     * get all information of a sale by table id
     * @access public
     * @return object
     * @param no
     */
    public function get_all_information_of_a_sale_by_table_id_ajax()
    {
        $table_id = $this->input->post('table_id');
        $sale_info = $this->Sale_model->get_new_sale_by_table_id($table_id);
        $sale_id = $sale_info->id;
        $sale_object = $this->get_all_information_of_a_sale($sale_id);
        echo json_encode($sale_object);
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
     /**
     * print kot
     * @access public
     * @return void
     * @param int
     */
    public function print_kot($sale_id){
        $data['sale_object'] = $this->get_all_information_of_a_sale($sale_id);
        $print_format = $this->session->userdata('print_format');
         if($print_format=="80mm"){
            $this->load->view('sale/print_kot', $data);
        }else{
            $this->load->view('sale/print_kot_56mm', $data);
        }
    }
     /**
     * print invoice
     * @access public
     * @return void
     * @param int
     */
    public function print_invoice($sale_id){
        $data['sale_object'] = $this->get_all_information_of_a_sale($sale_id);
        $print_format = $this->session->userdata('print_format');
        if($print_format=="80mm"){
            $this->load->view('sale/print_invoice', $data);
        }else{
            $this->load->view('sale/print_invoice_56mm', $data);
        }
    }
     /**
     * print bill
     * @access public
     * @return void
     * @param int
     */
    public function print_bill($sale_id){
        $data['sale_object'] = $this->get_all_information_of_a_sale($sale_id);
        $print_format = $this->session->userdata('print_format');
        if($print_format=="80mm"){
            $this->load->view('sale/print_bill',$data);
        }else{
            $this->load->view('sale/print_bill_56mm',$data);
        }

    }
     /**
     * print bill
     * @access public
     * @return void
     * @param int
     */
    public function getBillDetails(){
        $sale_id = $_POST['sale_id'];
        $sale_object = $this->get_all_information_of_a_sale($sale_id);
        $order_type = '';
        if($sale_object->order_type == 1){
            $order_type = 'A';
        }elseif($sale_object->order_type == 2){
            $order_type = 'B';
        }elseif($sale_object->order_type == 3){
            $order_type = 'C';
        }
        $time = (date('H:i',strtotime($sale_object->order_time)));
        $tables = '';
        if(isset($sale_object->tables_booked) && $sale_object->tables_booked):
            foreach ($sale_object->tables_booked as $key1=>$val){
                $tables .= ($val->table_name);
                if($key1 < (sizeof($sale_object->tables_booked) -1)){
                    $tables .= ", ";
                }
            }
            endif;
        $html = '<header>';
             $invoice_logo = $this->session->userdata('invoice_logo');
              if($invoice_logo):
                $html.='<img src="'.base_url().'images/'.$invoice_logo.'">';
              endif;

              $html.='<h3 class="title">'.($this->session->userdata('outlet_name')).'</h3>
                    <p>'.lang('Bill_No').': <span id="b_bill_no">'.($order_type.' '.$sale_object->sale_no).'</span></p>
                </header>
                <ul class="simple-content">
                    <li>'.lang('date').': <span id="b_bill_date">'.(date($this->session->userdata('date_format'), strtotime($sale_object->sale_date))).' '.$time.' </span></li>
                    <li>'.lang('Sales_Associate').': <span id="b_bill_creator">'.($sale_object->user_name).'</span></li>
                    <li>'.lang('customer').': <b><span id="b_bill_customer">'.("$sale_object->customer_name").'</span></b></li>';
                     if(isset($sale_object->tables_booked) && $sale_object->tables_booked):
                         $html .='<li>'.lang('table').': <b><span id="b_bill_customer">'.$tables.'</span></b></li>';
                         endif;

                $html .='</ul>
                <ul class="main-content-list">';

                if (isset($sale_object->items)) {
                    $i = 1;
                    $totalItems = 0;
                    foreach ($sale_object->items as $row) {
                        $totalItems += $row->qty;
                        $menu_unit_price = getAmtP($row->menu_unit_price);
                        $html .= '<li>
                                <span># '.($i++).': '.$row->menu_name.' '.$row->qty.' X '.$menu_unit_price.'</span>
                                <span>'.(getAmt($row->menu_price_without_discount)).'</span>
                                </li>';
                    }
                }

                if(count($row->modifiers)){
                    $l = 1;
                    $html_modifier = '';
                    $modifier_price = 0;
                    foreach($row->modifiers as $modifier){
                        if($l==count($row->modifiers)){
                            $html_modifier .= escape_output($modifier->name);
                        }else{
                            $html_modifier .= escape_output($modifier->name).',';
                        }
                        $modifier_price+=$modifier->modifier_price;
                        $l++;
                    }
                    $html .= '<li>
                                <span>'.lang('modifier').' : '.$html_modifier.'</span>
                                <span>'.(getAmt($modifier_price)).'</span>
                                </li>';
                }
        $html .= '<li>
                        <span><b>'.lang('Total_Item_s').': <span id="b_bill_total_item">'.$totalItems.'</span></b></span>
                        <span></span>
                    </li>
                    <li>
                        <span>'.lang('sub_total').'</span>
                        <span><b><span id="b_bill_subtotal">'.(getAmt($sale_object->sub_total)).'</span></b></span>
                    </li>
                    <li>
                        <span>'.lang('grand_total').'</span>
                        <span><b><span id="b_bill_gtotal">'.(getAmt($sale_object->total_payable)).'</span></b></span>
                    </li>
                    <li>
                        <span>'.lang('total_payable').'</span>
                        <span><span id="b_bill_total_payable">'.(getAmt($sale_object->total_payable)).'</span></span>
                    </li>
                </ul>';

              echo json_encode($html);

    }
     /**
     * get new hold number
     * @access public
     * @return void
     * @param no
     */
    public function get_new_hold_number_ajax(){
        $number_of_holds_of_this_user_and_outlet = $this->get_current_hold();
        $number_of_holds_of_this_user_and_outlet++;
        /*This variable could not be escaped because this is html content*/
        echo $number_of_holds_of_this_user_and_outlet;
    }
     /**
     * get current hold
     * @access public
     * @return object
     * @param no
     */
    public function get_current_hold(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $number_of_holds = $this->Sale_model->getNumberOfHoldsByUserAndOutletId($outlet_id,$user_id);
        return $number_of_holds;
    }
     /**
     * add hold by ajax
     * @access public
     * @return void
     * @param int
     */
    public function add_hold_by_ajax()
    {
        $order_details = json_decode(json_decode($this->input->post('order')));
        $hold_number = trim($this->input->post('hold_number'));
        $data = array();
        $data['customer_id'] = trim($order_details->customer_id);
        $data['total_items'] = trim($order_details->total_items_in_cart);
        $data['sub_total'] = trim($order_details->sub_total);
        $data['charge_type'] = trim($order_details->charge_type);
        $data['table_id'] = trim($order_details->selected_table);
        $data['total_payable'] = trim($order_details->total_payable);
        $data['total_item_discount_amount'] = trim($order_details->total_item_discount_amount);
        $data['sub_total_with_discount'] = trim($order_details->sub_total_with_discount);
        $data['sub_total_discount_amount'] = trim($order_details->sub_total_discount_amount);
        $data['total_discount_amount'] = trim($order_details->total_discount_amount);
        $data['delivery_charge'] = trim($order_details->delivery_charge);
        $data['sub_total_discount_value'] = trim($order_details->sub_total_discount_value);
        $data['sub_total_discount_type'] = trim($order_details->sub_total_discount_type);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['waiter_id'] = trim($order_details->waiter_id);
        $data['outlet_id'] = $this->session->userdata('outlet_id');
        $data['sale_date'] = trim(isset($order_details->open_invoice_date_hidden) && $order_details->open_invoice_date_hidden?$order_details->open_invoice_date_hidden:date('Y-m-d'));
        $data['sale_time'] = date('Y-m-d h:i A');
        $data['order_status'] = trim($order_details->order_status);

        $total_tax = 0;
        if(isset($order_details->sale_vat_objects) && $order_details->sale_vat_objects){
            foreach ($order_details->sale_vat_objects as $keys=>$val){
                $total_tax+=$val->tax_field_amount;
            }
        }
        $data['vat'] = $total_tax;

        $data['sale_vat_objects'] = json_encode($order_details->sale_vat_objects);
        $data['order_type'] = trim($order_details->order_type);
        if($hold_number===0 || $hold_number===""){
            $current_hold_order = $this->get_current_hold();
            echo "current hold".$current_hold_order."<br/>";
            $hold_number = $current_hold_order+1;
        }
        $data['hold_no'] = $hold_number;
        $query = $this->db->insert('tbl_holds', $data);
        $holds_id = $this->db->insert_id();
        if($holds_id>0 && count($order_details->items)>0){
            foreach($order_details->items as $item){
                $item_data = array();
                $item_data['food_menu_id'] = $item->item_id;
                $item_data['menu_name'] = $item->item_name;
                $item_data['qty'] = $item->item_quantity;
                $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                $item_data['menu_unit_price'] = $item->item_unit_price;
                $item_data['menu_taxes'] = json_encode($item->item_vat);
                $item_data['menu_discount_value'] = $item->item_discount;
                $item_data['discount_type'] = $item->discount_type;
                $item_data['menu_note'] = $item->item_note;
                $item_data['discount_amount'] = $item->item_discount_amount;
                $item_data['holds_id'] = $holds_id;
                $item_data['user_id'] = $this->session->userdata('user_id');
                $item_data['outlet_id'] = $this->session->userdata('outlet_id');
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_holds_details', $item_data);
                $holds_details_id = $this->db->insert_id();

                $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;
                $modifier_vat_array = ($item->modifier_vat!="")?explode("|||",$item->modifier_vat):null;

                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $key1=>$single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id;
                        $modifier_data['modifier_price'] = $modifier_price_array[$i];
                        $modifier_data['food_menu_id'] = $item->item_id;
                        $modifier_data['holds_id'] = $holds_id;
                        $modifier_data['holds_details_id'] = $holds_details_id;
                        $modifier_data['menu_taxes'] = isset($modifier_vat_array[$key1]) && $modifier_vat_array[$key1]?$modifier_vat_array[$key1]:'';
                        $modifier_data['user_id'] = $this->session->userdata('user_id');
                        $modifier_data['outlet_id'] = $this->session->userdata('outlet_id');
                        $modifier_data['customer_id'] =$order_details->customer_id;
                        $query = $this->db->insert('tbl_holds_details_modifiers', $modifier_data);

                        $i++;
                    }
                }
            }
            foreach($order_details->orders_table as $single_order_table){
                $order_table_info = array();
                $order_table_info['persons'] = $single_order_table->persons;
                $order_table_info['booking_time'] = date('Y-m-d H:i:s');
                $order_table_info['hold_id'] = $holds_id;
                $order_table_info['hold_no'] = $hold_number;
                $order_table_info['outlet_id'] = $this->session->userdata('outlet_id');
                $order_table_info['table_id'] = $single_order_table->table_id;
                $this->db->insert('tbl_holds_table',$order_table_info);
            }
        }

        echo escape_output($holds_id);
    }
     /**
     * get all holds ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_all_holds_ajax(){
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $holds_information = $this->Sale_model->getHoldsByOutletAndUserId($outlet_id,$user_id);
        foreach($holds_information as $key=>$single_hold_information){
            $holds_information[$key]->tables_booked = $this->Sale_model->get_all_tables_of_a_hold_items($single_hold_information->id);
        }
        echo json_encode($holds_information);
    }
     /**
     * get last 10 sales ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_last_10_sales_ajax(){
        $outlet_id = $this->session->userdata('outlet_id');
        $sales_information = $this->Sale_model->getLastTenSalesByOutletAndUserId($outlet_id);
        foreach($sales_information as $single_sale_information){
            $single_sale_information->tables_booked = $this->Sale_model->get_all_tables_of_a_last_sale($single_sale_information->id);
        }
        echo json_encode($sales_information);
    }

    public function get_last_10_future_sales_ajax(){
        $outlet_id = $this->session->userdata('outlet_id');
        $sales_information = $this->Sale_model->future_sales($outlet_id);
        foreach($sales_information as $single_sale_information){
            $single_sale_information->tables_booked = $this->Sale_model->get_all_tables_of_a_last_sale($single_sale_information->id);
        }
        echo json_encode($sales_information);
    }
     /**
     * get single hold info by ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_single_hold_info_by_ajax()
    {
        $hold_id = $this->input->post('hold_id');
        $hold_information = $this->Sale_model->get_hold_info_by_hold_id($hold_id);
        $items_by_holds_id = $this->Sale_model->getAllItemsFromHoldsDetailByHoldsId($hold_id);
        foreach($items_by_holds_id as $single_item_by_hold_id){
            $modifier_information = $this->Sale_model->getModifiersByHoldAndHoldsDetailsId($hold_id,$single_item_by_hold_id->holds_details_id);
            $single_item_by_hold_id->modifiers = $modifier_information;
        }
        $holds_details_objects = $items_by_holds_id;
        $hold_object = $hold_information[0];
        $hold_object->items = $holds_details_objects;
        $hold_object->tables_booked = json_encode($this->Sale_model->get_all_tables_of_a_hold_items($hold_id));
        echo json_encode($hold_object);

    }
     /**
     * delete all information of hold by ajax
     * @access public
     * @return object
     * @param no
     */
    public function delete_all_information_of_hold_by_ajax()
    {
        $hold_id = $this->input->post('hold_id');
        $this->db->delete('tbl_holds', array('id' => $hold_id));
        $this->db->delete('tbl_holds_details', array('holds_id' => $hold_id));
        $this->db->delete('tbl_holds_details_modifiers', array('holds_id' => $hold_id));
    }
     /**
     * check customer address ajax
     * @access public
     * @return object
     * @param no
     */
    public function check_customer_address_ajax()
    {
        $customer_id = $this->input->post('customer_id');
        $customer_info = $this->Sale_model->getCustomerInfoById($customer_id);
        echo json_encode($customer_info);
    }
     /**
     * get customer ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_customer_ajax()
    {
        $customer_id = $this->input->post('customer_id');
        $customer_info = $this->Sale_model->getCustomerInfoById($customer_id);
        echo json_encode($customer_info);
    }
     /**
     * cancel particular order
     * @access public
     * @return void
     * @param no
     */
    public function cancel_particular_order_ajax()
    {
        $sale_id = $this->input->post('sale_id');
        $this->delete_specific_order_by_sale_id($sale_id);
        echo "success";
    }
     /**
     * delete specific order by sale id
     * @access public
     * @return boolean
     * @param int
     */
    public function delete_specific_order_by_sale_id($sale_id){
        $this->db->delete('tbl_sales', array('id' => $sale_id));
        $this->db->delete('tbl_sales_details', array('sales_id' => $sale_id));
        $this->db->delete('tbl_sales_details_modifiers', array('sales_id' => $sale_id));
        $this->db->delete('tbl_sale_consumptions', array('sale_id' => $sale_id));
        $this->db->delete('tbl_sale_consumptions_of_menus', array('sales_id' => $sale_id));
        $this->db->delete('tbl_sale_consumptions_of_modifiers_of_menus', array('sales_id' => $sale_id));
        $this->db->delete('tbl_orders_table', array('sale_id' => $sale_id));
        return true;
    }
     /**
     * update order status ajax
     * @access public
     * @return void
     * @param int
     */
    public function update_order_status_ajax()
    {
        $sale_id = $this->input->post('sale_id');
        $close_order = $this->input->post('close_order');
        $paid_amount = $this->input->post('paid_amount');
        $due_amount = $this->input->post('due_amount');
        $given_amount_input = $this->input->post('given_amount_input');
        $change_amount_input = $this->input->post('change_amount_input');
        $payment_method_type = $this->input->post('payment_method_type');
        $is_just_cloase = ($payment_method_type=='0')? true:false;
        if($close_order=='true'){
            $this->Sale_model->delete_status_orders_table($sale_id);
            if($is_just_cloase){
                $order_status = array('order_status' => 3,'given_amount' => $given_amount_input,'change_amount' => $change_amount_input,'close_time'=>date('H:i:s'));
            }else{
                $order_status = array('paid_amount' =>  $paid_amount,'given_amount' => $given_amount_input,'change_amount' => $change_amount_input, 'due_amount' => $due_amount, 'order_status' => 3,'payment_method_id'=>$payment_method_type,'close_time'=>date('H:i:s'));
            }

        }else{
            $order_status = array('paid_amount' => $paid_amount,'given_amount' => $given_amount_input,'change_amount' => $change_amount_input,'due_amount' => $due_amount,'order_status' => 2,'payment_method_id'=>$payment_method_type);
        }

        $this->db->where('id', $sale_id);
        $this->db->update('tbl_sales', $order_status);
        echo escape_output($sale_id);
    }
     /**
     * delete all holds with information by ajax
     * @access public
     * @return int
     * @param no
     */
    public function delete_all_holds_with_information_by_ajax()
    {
        $outlet_id = $this->session->userdata('outlet_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->delete('tbl_holds', array('user_id' => $user_id,'outlet_id' => $outlet_id));
        $this->db->delete('tbl_holds_details', array('user_id' => $user_id,'outlet_id' => $outlet_id));
        $this->db->delete('tbl_holds_details_modifiers', array('user_id' => $user_id,'outlet_id' => $outlet_id));
        echo 1;
    }
     /**
     * change date of a sale ajax
     * @access public
     * @return void
     * @param no
     */
    public function change_date_of_a_sale_ajax()
    {
        $sale_id = $this->input->post('sale_id');
        $change_date = $this->input->post('change_date');
        $data['sale_date'] = date('Y-m-d',strtotime($change_date));
        $data['order_time'] = date("H:i:s");
        $changes = array(
            'sale_date' => date('Y-m-d',strtotime($change_date)),
            'order_time' => date("H:i:s"),
            'date_time' => date('Y-m-d H:i:s',strtotime($change_date.' '.date("H:i:s")))
        );

        $this->db->where('id', $sale_id);
        $this->db->update('tbl_sales', $changes);
    }
     /**
     * get Opening Balance
     * @access public
     * @return float
     * @param no
     */
	public function getOpeningBalance(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $getOpeningBalance = $this->Sale_model->getOpeningBalance($user_id,$outlet_id,$date);
        return isset($getOpeningBalance->amount) && $getOpeningBalance->amount?$getOpeningBalance->amount:'';
    }
     /**
     * get Opening Date Time
     * @access public
     * @return string
     * @param no
     */
    public function getOpeningDateTime(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $getOpeningDateTime = $this->Sale_model->getOpeningDateTime($user_id,$outlet_id,$date);
        return isset($getOpeningDateTime->opening_date_time) && $getOpeningDateTime->opening_date_time?$getOpeningDateTime->opening_date_time:'';
    }
     /**
     * get Closing Date Time
     * @access public
     * @return string
     * @param no
     */
    public function getClosingDateTime(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $getClosingDateTime = $this->Sale_model->getClosingDateTime($user_id,$outlet_id,$date);
        return isset($getClosingDateTime->closing_date_time) && $getClosingDateTime->closing_date_time?$getClosingDateTime->closing_date_time:'';
    }
     /**
     * get Purchase Paid Sum
     * @access public
     * @return float
     * @param no
     */
    public function getPurchasePaidSum(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $summationOfPaidPurchase = $this->Sale_model->getSummationOfPaidPurchase($user_id,$outlet_id,$date);
        return $summationOfPaidPurchase->purchase_paid;
    }
     /**
     * get Supplier Payment Sum
     * @access public
     * @return float
     * @param no
     */
    public function getSupplierPaymentSum(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $summationOfSupplierPayment = $this->Sale_model->getSummationOfSupplierPayment($user_id,$outlet_id,$date);
        return $summationOfSupplierPayment->payment_amount;
    }
     /**
     * get Customer Due Receive Amount Sum
     * @access public
     * @return float
     * @param string
     */
    public function getCustomerDueReceiveAmountSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $summationOfCustomerDueReceive = $this->Sale_model->getSummationOfCustomerDueReceive($user_id,$outlet_id,$date);
        return $summationOfCustomerDueReceive->receive_amount;
    }
     /**
     * get Expense Amount Sum
     * @access public
     * @return float
     * @param no
     */
    public function getExpenseAmountSum(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $getExpenseAmountSum = $this->Sale_model->getExpenseAmountSum($user_id,$outlet_id,$date);
        return $getExpenseAmountSum->amount;
    }
     /**
     * get Sale Paid Sum
     * @access public
     * @return float
     * @param string
     */
    public function getSalePaidSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getSalePaidSum = $this->Sale_model->getSalePaidSum($user_id,$outlet_id,$date);
        return $getSalePaidSum->amount;
    }
     /**
     * get Sale Due Sum
     * @access public
     * @return float
     * @param string
     */
    public function getSaleDueSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getSaleDueSum = $this->Sale_model->getSaleDueSum($user_id,$outlet_id,$date);
        return $getSaleDueSum->amount;
    }
     /**
     * get Sale In Cash Sum
     * @access public
     * @return float
     * @param string
     */
    public function getSaleInCashSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getSaleInCashSum = $this->Sale_model->getSaleInCashSum($user_id,$outlet_id,$date);
        return $getSaleInCashSum->amount;
    }
     /**
     * get Sale In Paypal Sum
     * @access public
     * @return float
     * @param string
     */
    public function getSaleInPaypalSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getSaleInPaypalSum = $this->Sale_model->getSaleInPaypalSum($user_id,$outlet_id,$date);
        return $getSaleInPaypalSum->amount;
    }
     /**
     * get Sale In Card Sum
     * @access public
     * @return float
     * @param string
     */
    public function getSaleInCardSum($date){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getSaleInCardSum = $this->Sale_model->getSaleInCardSum($user_id,$outlet_id,$date);
        return $getSaleInCardSum->amount;
    }
     /**
     * get Sale In Stripe Sum
      * @access public
      * @return float
      * @param string
     */
    public function getSaleInStripeSum(){
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $date = date('Y-m-d');
        $getSaleInStripeSum = $this->Sale_model->getSaleInStripeSum($user_id,$outlet_id,$date);
        return $getSaleInStripeSum->amount;
    }
     /**
     * get Payable Amount Sum
      * @access public
      * @return float
      * @param string
     */
    public function getPayableAomountSum($opening_date_time)
    {
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $getPayableAomountSum = $this->Sale_model->getPayableAomountSum($user_id,$outlet_id,$opening_date_time);
        return $getPayableAomountSum->amount;
    }
     /**
     * register Detail Calculation To Show
     * @access public
     * @return array
     * @param no
     */
    public function registerDetailCalculationToShow(){
        $opening_date_time = $this->getOpeningDateTime();
        $all_payments = $this->Sale_model->getAllSaleByDateForRegister($opening_date_time);
        $payment_details = array();
        foreach ($all_payments as $key=>$value){
            $preview_amount = isset($payment_details[$value->payment_name]) && $payment_details[$value->payment_name]?$payment_details[$value->payment_name]:0;
            $payment_details[$value->payment_name] = $preview_amount + $value->paid_amount;
        }

        $html_p = '';
        $j=0;
        $total_used_payment = 0;
        foreach ($payment_details as $key=>$value){
            $total_used_payment++;
        }
        foreach ($payment_details as $key=>$value){
            $html_p .= "<p>Sale in ".$key.": ".getAmtP($value)."</p>";
            $j++;
        }


        $register_detail = array(
            'opening_date_time' => date('Y-m-d h:m A', strtotime($opening_date_time)),
            'closing_date_time' => $this->getClosingDateTime(),
            'opening_balance' => getAmtP($this->getOpeningBalance()),
            'sale_total_payable_amount' => getAmtP($this->getPayableAomountSum($opening_date_time)),
            'sale_paid_amount' => getAmtP($this->getSalePaidSum($opening_date_time)),
            'sale_due_amount' => getAmtP($this->getSaleDueSum($opening_date_time)),
            'customer_due_receive' => getAmtP($this->getCustomerDueReceiveAmountSum($opening_date_time)),
            'sale_in_cash' => getAmtP($this->getSaleInCashSum($opening_date_time)),
            'sale_in_paypal' => getAmtP($this->getSaleInPaypalSum($opening_date_time)),
            'sale_in_card' => getAmtP($this->getSaleInCardSum($opening_date_time)),
            'payment_html_content' => $html_p,
        );
        return $register_detail;
    }
     /**
     * get Balance
     * @access public
     * @return float
     * @param no
     */
    public function getBalance(){
        $opening_date_time = $this->getOpeningDateTime();
        $balance = $this->getOpeningBalance()+$this->getSalePaidSum($opening_date_time)+$this->getCustomerDueReceiveAmountSum($opening_date_time);
        return  $balance;
    }
     /**
     * register Detail Calculation To Show Ajax
     * @access public
     * @return object
     * @param no
     */
    public function registerDetailCalculationToShowAjax(){
        $all_register_info_values = $this->registerDetailCalculationToShow();
        // return $all_register_info_values;
        echo json_encode($all_register_info_values);
    }
     /**
     * print All Calculation
     * @access public
     * @return void
     * @param no
     */
    public function printAllCalculation()
    {
        //generate html content for view
        echo 'opening balance: '.$this->getOpeningBalance().'<br/>';
        echo 'purchase paid sum: '.$this->getPurchasePaidSum().'<br/>';
        echo 'supplier payment sum: '.$this->getSupplierPaymentSum().'<br/>';
        echo 'customer due receive amount sum: '.$this->getCustomerDueReceiveAmountSum("").'<br/>';
        echo 'expense amount sum: '.$this->getExpenseAmountSum().'<br/>';
        echo 'sale amount sum: '.$this->getSaleAmountSum().'<br/>';
        echo 'sale in cash sum: '.$this->getSaleInCashSum("").'<br/>';
        echo 'sale in paypal sum: '.$this->getSaleInPaypalSum("").'<br/>';
        // echo 'sale in paypal sum: '.$this->getSaleInPaypalSum().'<br/>';
        echo 'sale in card sum: '.$this->getSaleInCardSum("").'<br/>';
        echo 'sale in stripe sum: '.$this->getSaleInStripeSum().'<br/>';
    }
     /**
     * close Register
     * @access public
     * @return void
     * @param no
     */
    public function closeRegister()
    {
        $user_id = $this->session->userdata('user_id');
        $outlet_id = $this->session->userdata('outlet_id');
        $opening_date_time = $this->getOpeningDateTime();
        $all_payments = $this->Sale_model->getAllSaleByDateForRegister($opening_date_time);

        $payment_details = array();
        foreach ($all_payments as $key=>$value){
            $preview_amount = isset($payment_details[$value->payment_name]) && $payment_details[$value->payment_name]?$payment_details[$value->payment_name]:0;
            $payment_details[$value->payment_name] = $preview_amount + $value->paid_amount;
        }
        $changes = array(
            'closing_balance' => $this->getBalance(),
            'closing_balance_date_time' => date("Y-m-d H:i:s"),
            'sale_paid_amount' => $this->getSalePaidSum($opening_date_time),
            'customer_due_receive' => $this->getCustomerDueReceiveAmountSum($opening_date_time),
            'payment_methods_sale' => json_encode($payment_details),
            'register_status' => 2
        );

        $this->db->where('outlet_id', $outlet_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('opening_balance_date_time', $opening_date_time);
        $this->db->where('register_status', 1);
        $this->db->update('tbl_register', $changes);
    }
     /**
     * get new notification
     * @access public
     * @return object
     * @param no
     */
    public function get_new_notification()
    {
        $outlet_id = $this->session->userdata('outlet_id');
        $notifications = $this->Sale_model->getNotificationByOutletId($outlet_id);
        return $notifications;
    }
     /**
     * get new notifications ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_new_notifications_ajax()
    {
        echo json_encode($this->get_new_notification());
    }
     /**
     * remove notification
     * @access public
     * @return int
     * @param no
     */
    public function remove_notication_ajax()
    {
        $notification_id = $this->input->post('notification_id');
        $this->db->delete('tbl_notifications', array('id' => $notification_id));
        echo escape_output($notification_id);
    }
     /**
     * remove multiple notification
     * @access public
     * @return void
     * @param no
     */
    public function remove_multiple_notification_ajax()
    {
        $notifications = $this->input->post('notifications');
        $notifications_array = explode(",",$notifications);
        foreach($notifications_array as $single_notification){
            $this->db->delete('tbl_notifications', array('id' => $single_notification));
        }
    }
     /**
     * add temp kot
     * @access public
     * @return int
     * @param no
     */
    public function add_temp_kot_ajax()
    {
        $order = json_decode($this->input->post('order'));
        $data['temp_kot_info'] = $order;
        $this->db->insert('tbl_temp_kot', $data);
        $temp_kot_id = $this->db->insert_id();
        echo escape_output($temp_kot_id);
    }
     /**
     * add temp bot
     * @access public
     * @return int
     * @param no
     */
    public function add_temp_bot_ajax()
    {
        $order = json_decode($this->input->post('order'));
        $data['temp_kot_info'] = $order;
        $query = $this->db->insert('tbl_temp_kot', $data);
        $temp_kot_id = $this->db->insert_id();
        echo escape_output($temp_kot_id);
    }
     /**
     * print temp kot
     * @access public
     * @return void
     * @param int
     */
    public function print_temp_kot($temp_kot_id){
        $data['temp_kot_info'] = $this->Sale_model->get_temp_kot($temp_kot_id);
        $this->db->delete('tbl_temp_kot', array('id' => $temp_kot_id));
        $print_format = $this->session->userdata('print_format');
        if(isset($data['temp_kot_info']) && $data['temp_kot_info']){
            if($print_format=="80mm"){
                $this->load->view('sale/print_kot_temp', $data);
            }else{
                $this->load->view('sale/print_kot_temp_56mm', $data);
            }
        }else{
            echo lang('txt_err_pos_3');
        }

    }
     /**
     * print temp bot
     * @access public
     * @return void
     * @param int
     */
    public function print_temp_bot($temp_kot_id){
        $data['temp_kot_info'] = $this->Sale_model->get_temp_kot($temp_kot_id);
        $this->db->delete('tbl_temp_kot', array('id' => $temp_kot_id));
        $print_format = $this->session->userdata('print_format');
        if($data['temp_kot_info']){
            if($print_format=="80mm"){
                $this->load->view('sale/print_bot_temp', $data);
            }else{
                $this->load->view('sale/print_bot_temp_56mm', $data);
            }
        }else{
            echo lang('txt_err_pos_3');
        }

    }
     /**
     * remove a table booking ajax
     * @access public
     * @return object
     * @param no
     */
    public function remove_a_table_booking_ajax()
    {
        $orders_table_id = $this->input->post('orders_table_id');
        $orders_table_single_info = $this->Common_model->getDataById($orders_table_id, "tbl_orders_table");
        $this->db->delete('tbl_orders_table', array('id' => $orders_table_id));
        echo json_encode($orders_table_single_info);
    }
     /**
     * get all assets info by ajax
     * @access public
     * @return object
     * @param no
     */
    public function get_all_assets_info_by_ajax()
    {
        $outlet_id = $this->session->userdata('outlet_id');
        // echo $outlet_id;
        $assets = $this->Sale_model->get_all_assets($outlet_id);
        $data = new \stdClass();
        $data->assets_info = $this->assets_details($assets);
        echo json_encode($data);
    }
     /**
     * assets details
     * @access public
     * @return object
     * @param string
     */
    public function assets_details($assets)
    {
        foreach($assets as $asset){
            $asset->asset_games = $this->Sale_model->getGamesOfAssetByAssetId($asset->id);
        }
        return $assets;
    }

}
