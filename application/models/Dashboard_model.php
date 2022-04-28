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
  # This is Dashboard_model Model
  ###########################################################
 */
class Dashboard_model extends CI_Model {

     /**
     * get Purchase Paid Amount
     * @access public
     * @return float
     * @param string
     */
    public function getPurchasePaidAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $ppaid = $this->db->query("SELECT IFNULL(SUM(p.paid),0) as ppaid
        FROM tbl_purchase p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('ppaid');
        return $ppaid;
    }
    /**
     * get All Food Menus
     * @access public
     * @return boolean
     * @param no
     */
    public function getAllFoodMenus($outlet_id){
        $getFM = getFMIdsOutlet($outlet_id);
       /* echo $getFM;exit;*/
        $result = $this->db->query("SELECT fm.*,fmc.category_name, COUNT(sd.food_menu_id) as item_sold 
FROM tbl_food_menus fm  INNER JOIN (select * from tbl_food_menu_categories where del_status='Live') fmc ON fmc.id = fm.category_id LEFT JOIN (select * from tbl_sales_details where del_status='Live') sd ON sd.food_menu_id = fm.id WHERE FIND_IN_SET(fm.id, '$getFM') AND fm.del_status='Live' GROUP BY fm.id order BY name ASC")->result();
        if($result != false){
            return sizeof($result);
        }else{
            return '0';
        }
    }
     /**
     * get Purchase Amount
     * @access public
     * @return float
     * @param string
     */
    public function getPurchaseAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalPurchase = $this->db->query("SELECT IFNULL(SUM(p.grand_total),0) as totalPurchase
        FROM tbl_purchase p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('totalPurchase');
        return $totalPurchase;
    }
     /**
     * get Supplier Paid Amount
     * @access public
     * @return object
     * @param string
     */
    public function getSupplierPaidAmount($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $partypaid = $this->db->query("SELECT IFNULL(SUM(p.amount),0) as partypaid
        FROM tbl_supplier_payments p  
        WHERE p.outlet_id=$outlet_id AND p.del_status = 'Live'
        AND p.date LIKE '$month%' ")->row('partypaid');
        return $partypaid;
    }
     /**
     * get Sale Paid Amount
     * @access public
     * @return float
     * @param string
     * @param boolean
     */
    public function getSalePaidAmount($month, $payment_method_id = FALSE) {
        $outlet_id = $this->session->userdata('outlet_id');
        $condition = " ";
        if ($payment_method_id != FALSE) {
            $condition = " AND s.payment_method_id=$payment_method_id";
        }
        $totalSale = $this->db->query("SELECT IFNULL(SUM(s.total_payable),0) as totalSale
        FROM tbl_sales s  
        WHERE s.outlet_id=$outlet_id AND s.del_status = 'Live'
        AND s.sale_date LIKE '$month%' $condition")->row('totalSale');
        return $totalSale;
    }
     /**
     * get Menu By Menu Name
     * @access public
     * @return object
     * @param string
     */
    public function getMenuByMenuName($menu_name){
      $this->db->select("*");
      $this->db->from('tbl_food_menus');
      $this->db->where("tbl_food_menus.name", $menu_name);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->row();      
    }
     /**
     * get Ingredient By Ingredient Name
     * @access public
     * @return object
     * @param string
     */
    public function getIngredientByIngredientName($menu_name){
      $this->db->select("*");
      $this->db->from('tbl_ingredients');
      $this->db->where("tbl_ingredients.name", $menu_name);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->row();      
    }
     /**
     * get Sale Vat
     * @access public
     * @return float
     * @param string
     */
    public function getSaleVat($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalSaleVat = $this->db->query("SELECT IFNULL(SUM(s.vat),0) as totalSaleVat
        FROM tbl_sales s  
        WHERE s.outlet_id=$outlet_id AND s.del_status = 'Live'
        AND s.sale_date LIKE '$month%'")->row('totalSaleVat');
        return $totalSaleVat;
    }
     /**
     * get Waste
     * @access public
     * @return float
     * @param string
     */
    public function getWaste($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalWaste = $this->db->query("SELECT IFNULL(SUM(w.total_loss),0) as totalWaste
        FROM tbl_wastes w  
        WHERE w.outlet_id=$outlet_id AND w.del_status = 'Live'
        AND w.date LIKE '$month%'")->row('totalWaste');
        return $totalWaste;
    }
     /**
     * get Expense
     * @access public
     * @return float
     * @param string
     */
    public function getExpense($month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $totalExpense = $this->db->query("SELECT IFNULL(SUM(w.amount),0) as totalExpense
        FROM tbl_expenses w  
        WHERE w.outlet_id=$outlet_id AND w.del_status = 'Live'
        AND w.date LIKE '$month%'")->row('totalExpense');
        return $totalExpense;
    }
     /**
     * current Inventory
     * @access public
     * @return float
     * @param no
     */
    public function currentInventory() {
        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');

        $result = $this->db->query("SELECT i.*,(select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
                (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
                (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
                (select category_name from tbl_ingredient_categories where id=i.category_id  AND del_status='Live') category_name,
                (select unit_name from tbl_units where id=i.unit_id AND del_status='Live') unit_name
                FROM tbl_ingredients i WHERE i.del_status='Live' AND i.company_id= '$company_id' ORDER BY i.name ASC")->result();
        $grandTotal = 0;
        foreach ($result as $value) {
            $totalStock = $value->total_purchase - $value->total_consumption - $value->total_waste;
            if ($totalStock >= 0) {
                $grandTotal = $grandTotal + $totalStock * getLastPurchaseAmount($value->id);
            }
        }
        return $grandTotal;
    }
     /**
     * top ten food menu
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function top_ten_food_menu($start_date, $end_date,$outlet_id) {
        $this->db->select('sum(qty) as totalQty,food_menu_id,menu_name,sale_date');
        $this->db->from('tbl_sales_details');
        $this->db->join('tbl_sales', 'tbl_sales.id = tbl_sales_details.sales_id', 'left');
        $this->db->where('sale_date>=', $start_date);
        $this->db->where('sale_date <=', $end_date); 
        $this->db->order_by('totalQty desc');
        $this->db->where('tbl_sales_details.outlet_id', $outlet_id);
        $this->db->where('tbl_sales_details.del_status', 'Live');
        $this->db->group_by('food_menu_id');
        $this->db->limit(10);
        $result = $this->db->get();   
        
        if($result != false){  
            return $result->result();
        }else{
            return false;
        }
    }
     /**
     * top ten customer
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function top_ten_customer($start_date, $end_date,$outlet_id) {

        $this->db->select('sum(tbl_sales.total_payable) as total_payable, tbl_sales.customer_id, tbl_customers.name, tbl_sales.sale_date');
        $this->db->from('tbl_sales');
        $this->db->join('tbl_customers', 'tbl_sales.customer_id = tbl_customers.id', 'left');
        $this->db->where('tbl_sales.sale_date>=', $start_date);
        $this->db->where('tbl_sales.sale_date <=', $end_date); 
        $this->db->order_by('total_payable desc');
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live');
        $this->db->group_by('customer_id');
        $this->db->limit(10);
        $result = $this->db->get();   
        
        if($result != false){  
            return $result->result();
        }else{
            return false;
        }
    }
     /**
     * customer receivable
     * @access public
     * @return object
     * @param int
     */
    public function customer_receivable($outlet_id) {
        $this->db->select('sum(due_amount) as due_amount, customer_id, name');
        $this->db->from('tbl_sales');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->order_by('due_amount desc');
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live');
        $this->db->group_by('tbl_sales.customer_id');
        $result = $this->db->get();   
        
        if($result != false){  
            return $result->result();
        }else{
            return false;
        }
    }
     /**
     * supplier payable
     * @access public
     * @return object
     * @param int
     */
    public function supplier_payable($outlet_id) {
        $this->db->select('sum(due) as due, supplier_id, name');
        $this->db->from('tbl_purchase');
        $this->db->join('tbl_suppliers', 'tbl_suppliers.id = tbl_purchase.supplier_id', 'left');
        $this->db->order_by('due desc');
        $this->db->where('tbl_purchase.outlet_id', $outlet_id);
        $this->db->where('tbl_purchase.del_status', 'Live');
        $this->db->group_by('tbl_purchase.supplier_id');
        $result = $this->db->get();   
        
        if($result != false){  
            return $result->result();
        }else{
            return false;
        }
    }
     /**
     * dinein count
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function dinein_count($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('count(*) as dinein_count');
        $this->db->from('tbl_sales'); 
        $this->db->where('tbl_sales.sale_date>=', $first_day_this_month);
        $this->db->where('tbl_sales.sale_date <=', $last_day_this_month); 
        $this->db->where('tbl_sales.order_type', 1);
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live'); 
        return $this->db->get()->row();
    }
     /**
     * take away count
     * @access public
     * @return object
     * @param string
     * @param string
     */
    public function take_away_count($first_day_this_month, $last_day_this_month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('count(*) as take_away_count');
        $this->db->from('tbl_sales'); 
        $this->db->where('tbl_sales.sale_date>=', $first_day_this_month);
        $this->db->where('tbl_sales.sale_date <=', $last_day_this_month);         
        $this->db->where('tbl_sales.order_type', 2);
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live'); 
        return $this->db->get()->row();
    }
     /**
     * delivery count
     * @access public
     * @return object
     * @param string
     * @param string
     */
    public function delivery_count($first_day_this_month, $last_day_this_month) {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('count(*) as delivery_count');
        $this->db->from('tbl_sales'); 
        $this->db->where('tbl_sales.sale_date>=', $first_day_this_month);
        $this->db->where('tbl_sales.sale_date <=', $last_day_this_month);         
        $this->db->where('tbl_sales.order_type', 3);
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live'); 
        return $this->db->get()->row();
    }
     /**
     * purchase_sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function purchase_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(paid) as purchase_sum');
        $this->db->from('tbl_purchase');  
        $this->db->where('tbl_purchase.date>=', $first_day_this_month);
        $this->db->where('tbl_purchase.date <=', $last_day_this_month);         
        $this->db->where('tbl_purchase.outlet_id', $outlet_id);
        $this->db->where('tbl_purchase.del_status', 'Live'); 
        $result = $this->db->get()->row();

        if (empty($result->purchase_sum)) {
            $result->purchase_sum = 0;
        }

        return $result;
    }
     /**
     * sale sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function sale_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(paid_amount) as sale_sum');
        $this->db->from('tbl_sales');  
        $this->db->where('tbl_sales.sale_date>=', $first_day_this_month);
        $this->db->where('tbl_sales.sale_date <=', $last_day_this_month);         
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live'); 
        $result = $this->db->get()->row();

        if (empty($result->sale_sum)) {
            $result->sale_sum = 0;
        }

        return $result;
    }
     /**
     * waste sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function waste_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(total_loss) as waste_sum');
        $this->db->from('tbl_wastes');  
        $this->db->where('tbl_wastes.date>=', $first_day_this_month);
        $this->db->where('tbl_wastes.date <=', $last_day_this_month);         
        $this->db->where('tbl_wastes.outlet_id', $outlet_id);
        $this->db->where('tbl_wastes.del_status', 'Live'); 
        $result = $this->db->get()->row(); 
        
        if (empty($result->waste_sum)) {
            $result->waste_sum = 0;
        }

        return $result;
    }
     /**
     * expense sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function expense_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(amount) as expense_sum');
        $this->db->from('tbl_expenses');  
        $this->db->where('tbl_expenses.date>=', $first_day_this_month);
        $this->db->where('tbl_expenses.date <=', $last_day_this_month); 
        $this->db->where('tbl_expenses.outlet_id', $outlet_id);
        $this->db->where('tbl_expenses.del_status', 'Live');  
        $result = $this->db->get()->row();

        if (empty($result->expense_sum)) {
            $result->expense_sum = 0;
        }

        return $result;
    }
     /**
     * customer due receive sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function customer_due_receive_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(amount) as customer_due_receive_sum');
        $this->db->from('tbl_customer_due_receives');  
        $this->db->where('tbl_customer_due_receives.date>=', $first_day_this_month);
        $this->db->where('tbl_customer_due_receives.date <=', $last_day_this_month);         
        $this->db->where('tbl_customer_due_receives.outlet_id', $outlet_id);
        $this->db->where('tbl_customer_due_receives.del_status', 'Live'); 
        $result = $this->db->get()->row();

        if (empty($result->customer_due_receive_sum)) {
            $result->customer_due_receive_sum = 0;
        }

        return $result;
    }
     /**
     * supplier due payment sum
     * @access public
     * @return object
     * @param string
     * @param string
     * @param int
     */
    public function supplier_due_payment_sum($first_day_this_month, $last_day_this_month,$outlet_id) {
        $this->db->select('sum(amount) as supplier_due_payment_sum');
        $this->db->from('tbl_supplier_payments');  
        $this->db->where('tbl_supplier_payments.date>=', $first_day_this_month);
        $this->db->where('tbl_supplier_payments.date <=', $last_day_this_month);         
        $this->db->where('tbl_supplier_payments.outlet_id', $outlet_id);
        $this->db->where('tbl_supplier_payments.del_status', 'Live'); 
        $result = $this->db->get()->row();

        if (empty($result->supplier_due_payment_sum)) {
            $result->supplier_due_payment_sum = 0;
        }

        return $result;
    }
     /**
     * get Payable Amount By Supplier Id
     * @access public
     * @return object
     * @param int
     */
    public function getPayableAmountBySupplierId($id) {
        $this->load->model('Report_model', 'Report_model');
        $month = date('Y-m');
        $monthOnly = date('m', strtotime($month));
        $finalDayByMonth = $this->Report_model->getLastDayInDateMonth($monthOnly);
        $temp = $month . '-' . $finalDayByMonth;
        $start_date = $month . '-' . '01';
        $end_date = $temp;
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(amount) as totalPayment,supplier_id,date');
        $this->db->from('tbl_supplier_payments');
        $this->db->where('date>=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->where('outlet_id', $outlet_id);
        $this->db->where('supplier_id', $id);
        $this->db->where('del_status', 'Live');
        $this->db->group_by('supplier_id');
        $result = $this->db->get()->row();
        if (!empty($result)) {
            return $result->totalPayment;
        } else {
            return 0.0;
        }
    }
     /**
     * comparison sale report
     * @access public
     * @return object
     * @param string
     * @param string
     */
    public function comparison_sale_report($start_date, $end_date) {
        $outlet_id = $this->session->userdata('outlet_id');
        $query = $this->db->query("select year(sale_date) as year, month(sale_date) as month, sum(total_payable) as total_amount from tbl_sales WHERE `sale_date` BETWEEN '$start_date' AND '$end_date' AND outlet_id='$outlet_id' group by year(sale_date), month(sale_date)");
        return $query->row();
    }
     /**
     * set Default Timezone
     * @access public
     * @return void
     * @param no
     */
    public function setDefaultTimezone() {
        $this->db->select("time_zone");
        $this->db->from('tbl_settings');
        $this->db->order_by('id', 'DESC');
        $this->db->where('company_id', $this->session->userdata('company_id'));
        $zoneName = $this->db->get()->row();
        if ($zoneName)
            date_default_timezone_set($zoneName->time_zone);
    }
     /**
     * get row
     * @access public
     * @return object
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     */
    public function get_row($table_name, $where_param, $select_param, $group = "", $limit = "") {
        if (!empty($select_param))
            $this->db->select($select_param);
        if (!empty($where_param))
            $this->db->where($where_param);
        $this->db->group_by($group);
        if (!empty($limit))
            $this->db->limit($limit);
        $result = $this->db->get($table_name);
        return $result->result();
    }
     /**
     * get row array
     * @access public
     * @return object
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param boolean
     * @param boolean
     */
    public function get_row_array($table_name, $where_param, $select_param, $group = "", $limit = "", $order_by = false, $order_value = false) {
        if (!empty($select_param))
            $this->db->select($select_param);
        if (!empty($where_param))
            $this->db->where($where_param);
        if (!empty($group))
            $this->db->group_by($group);
        if (!empty($order_by))
            $this->db->order_by($order_by, $order_value);
        if (!empty($limit))
            $this->db->limit($limit);
        $result = $this->db->get($table_name);
        return $result->result_array();
    }
     /**
     * custom Query
     * @access public
     * @return object
     * @param string
     */
    public function customeQuery($sql) {
        $result = $this->db->query($sql);
        return $result->result_array();
    }
     /**
     * count Data
     * @access public
     * @return object
     * @param string
     */
    public function countData($table_name){
        $company_id = $this->session->userdata('company_id');
        return $data_count = $this->db->query("select count(*) as data_count from $table_name where company_id=$company_id and del_status='Live'")->row();
    }
     /**
     * get Inventory Alert List
     * @access public
     * @return object
     * @param int
     */
    public function getInventoryAlertList($outlet_id) {
        $company_id = $this->session->userdata('company_id');
        $result = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE i.company_id= '$company_id' AND i.del_status='Live'  GROUP BY i.id")->result();
        return $result;
    }
     /**
     * get Inventory
     * @access public
     * @return object
     * @param int
     * @param int
     * @param int
     * @param int
     */
    public function getInventory($category_id = "", $ingredient_id = "", $food_id = "",$outlet_id='') {

        $company_id = $this->session->userdata('company_id');
        $result = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE i.company_id= '$company_id' AND i.del_status='Live' GROUP BY i.id")->result();
        return $result;
    }
     /**
     * count Data Food
     * @access public
     * @return object
     * @param int
     */
    public function countDataFood($outlet_id){
        $data_count = $this->db->query("select * from tbl_outlets where id=$outlet_id and del_status='Live'")->row();
        $tmp  = explode(',',$data_count->food_menus);
        return sizeof($tmp);

    }
} 
