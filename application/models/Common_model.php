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
  # This is Common_model Model
  ###########################################################
 */
class Common_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        if ($this->session->has_userdata('language')) {
            $language = $this->session->userdata('language');
        }else{
            $language = 'english';
        }
        $this->lang->load("$language", "$language");
        if($language=='spanish'){
            $this->config->set_item('language', 'spanish');
        }
    }
    /**
     * is Open Register
     * @access public
     * @return object
     * @param int
     * @param int
     */

    public function isOpenRegister($user_id, $outlet_id){
        $this->db->select('id');
        $this->db->from('tbl_register');
        $this->db->where("user_id", $user_id);
        $this->db->where("outlet_id", $outlet_id);
        $this->db->where("register_status", 1);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->num_rows();
    }
    /**
     * get Purchase Amount By User And Outlet Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getPurchaseAmountByUserAndOutletId($user_id, $outlet_id){
        $this->db->select('SUM(paid) as total_purchase_amount');
        $this->db->from('tbl_purchase');
        $this->db->where("DATE(date)", date('Y-m-d'));
        $this->db->where("user_id", $user_id);
        $this->db->where("outlet_id", $outlet_id);
        return $this->db->get()->row();
    }
    /**
     * getRestaurantAdminUser
     * @access public
     * @return object
     * @param int
     */
    public function getRestaurantAdminUser($company_id){
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where("del_status", "Live");
        $this->db->where("role", "Admin");
        $this->db->where("company_id", $company_id);
        return $this->db->get()->row();
    }
    /**
     * delete Custom Row
     * @access public
     * @return object
     * @param int
     * @param string
     * @param string
     */
    public function deleteCustomRow($id,$colm,$tbl) {
        $this->db->set('del_status', "Deleted");
        $this->db->where($colm, $id);
        $this->db->update($tbl);
    }
    /**
     * get All By Custom Id
     * @access public
     * @return object
     * @param int
     * @param string
     * @param string
     * @param string
     */
    public function getAllByCustomId($id,$filed,$tbl,$order=''){
        $this->db->select('*');
        $this->db->from($tbl);
        $this->db->where($filed,$id);
        if($order!=''){
            $this->db->order_by('id',$order);
        }
        $this->db->where("del_status", 'Live');
        $result = $this->db->get();

        if($result != false){
            return $result->result();
        }else{
            return false;
        }
    }
    /**
     * get All By Table
     * @access public
     * @return object
     * @param string
     */
    public function getAllByTable($table_name) {
        $this->db->select("*");
        $this->db->from($table_name);
        $this->db->where("del_status", 'Live');
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }
    /**
     * get All By Table
     * @access public
     * @return object
     * @param string
     */
    public function getUserMenus() {
        $this->db->select("*");
        $this->db->from("tbl_admin_user_menus");
        $this->db->where("del_status", 'Live');
        $this->db->where("is_ignore_menu !=",1);
        $this->db->order_by("label", 'ASC');
        return $this->db->get()->result();
    }
    /**
     * get All By Table
     * @access public
     * @return object
     * @param string
     */
    public function getServiceCompanies() {
        $this->db->select("*");
        $this->db->from("tbl_companies");
        $this->db->where("del_status", 'Live');
        $this->db->order_by("payment_clear", 'ASC');
        $this->db->order_by("id", 'DESC');
        return $this->db->get()->result();
    }
    /**
     * get All By Table
     * @access public
     * @return object
     * @param string
     */
    public function getServiceCompaniesYes() {
        $this->db->select("*");
        $this->db->from("tbl_companies");
        $this->db->where("del_status", 'Live');
        $this->db->where("payment_clear", 'Yes');
        $this->db->order_by("payment_clear", 'ASC');
        $this->db->order_by("id", 'DESC');
        return $this->db->get()->result();
    }
    /**
     * get All By Table
     * @access public
     * @return object
     * @param string
     */
    public function getPaymentInfo($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_payment_histories");
        $this->db->where("del_status", 'Live');
        $this->db->where("company_id", $company_id);
        $this->db->order_by("id", 'DESC');
        return $this->db->get()->row();
    }
    /**
     * get AdminInfo For Company
     * @access public
     * @return object
     * @param int
     */
    public function getAdminInfoForCompany($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("del_status", 'Live');
        $this->db->where("role", 'Admin');
        $this->db->where("company_id", $company_id);
        $this->db->order_by("id", 'DESC');
        return $this->db->get()->row();
    }
    /**
     * get AdminInfo For Company
     * @access public
     * @return object
     * @param int
     */
    public function checkExistingAdmin($email) {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("del_status", 'Live');
        $this->db->where("email_address", $email);
        return $this->db->get()->row();
    }
    /**
     * get All Outlest By Assign
     * @access public
     * @return object
     * @param no
     */
    public function getAllOutlestByAssign() {
        $role = $this->session->userdata('role');
        $company_id = $this->session->userdata('company_id');
        $outlets = $this->session->userdata('session_outlets');
        if($role=="Admin"){
            $result = $this->db->query("SELECT * FROM tbl_outlets WHERE del_status='Live' AND company_id = '$company_id'")->result();
        }else{
            $result = $this->db->query("SELECT * FROM tbl_outlets WHERE FIND_IN_SET(`id`, '$outlets') AND del_status='Live' AND company_id = '$company_id'")->result();
        }
        return $result;
    }
    /**
     * get All By Company Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByCompanyId($company_id, $table_name) {
        $language_manifesto = $this->session->userdata('language_manifesto');
        if(str_rot13($language_manifesto)=="eriutoeri"){
            $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
            return $result;
        }else{
            $outlet_id = $this->session->userdata('outlet_id');
            if($table_name=="tbl_tables" || $table_name=="tbl_users"){
                $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
                return $result;
            }else{
                $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
                return $result;
            }
        }



    }
    /**
     * get Food Menu
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getFoodMenu($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY name asc")->result();
        return $result;
    }
    /**
     * get By Company Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getByCompanyId($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY id DESC")->row();
        return $result;
    }
    /**
     * get All By Company Id ForDropdown
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByCompanyIdForDropdown($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY 2")->result();
        return $result;
    }
    /**
     * get All For Dropdown
     * @access public
     * @return object
     * @param string
     */
    public function getAllForDropdown($table_name) {
        $result = $this->db->query("SELECT * 
              FROM $table_name 
              WHERE del_status = 'Live'  
              ORDER BY 2")->result();
        return $result;
    }
    /**
     * get All By Outlet Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByOutletId($outlet_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
        return $result;
    }
    /**
     * get All By Outlet Id For Dropdown
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByOutletIdForDropdown($outlet_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY 2")->result();
        return $result;
    }
    /**
     * get All Food Menus By Category
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllFoodMenusByCategory($category_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE category_id=$category_id AND del_status = 'Live'  
          ORDER BY id DESC")->result();
        return $result;
    }
    /**
     * get All Modifier By CompanyId
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllModifierByCompanyId($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY name ASC")->result();
        return $result;
    }
    /**
     * delete Status Change
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function deleteStatusChange($id, $table_name) {
        $this->db->set('del_status', "Deleted");
        $this->db->where('id', $id);
        $this->db->update($table_name);
    }
    /**
     * delete Status Change With Child
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     * @param string
     * @param string
     * @param string
     */
    public function deleteStatusChangeWithChild($id, $id1, $table_name, $table_name2, $filed_name, $filed_name1) {
        $this->db->set('del_status', "Deleted");
        $this->db->where($filed_name, $id);
        $this->db->update($table_name);

        $this->db->set('del_status', "Deleted");
        $this->db->where($filed_name1, $id1);
        $this->db->update($table_name2);
    }
    /**
     * insert Information
     * @access public
     * @return int
     * @param array
     * @param string
     */
    public function insertInformation($data, $table_name) {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }
    /**
     * get Data By Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getDataById($id, $table_name) {
        $this->db->select("*");
        $this->db->from($table_name);
        $this->db->where("id", $id);
        return $this->db->get()->row();
    }
    public function getCustomDataByParams($field_name,$value, $table_name) {
        $this->db->select("*");
        $this->db->from($table_name);
        $this->db->where($field_name, $value);
        return $this->db->get()->row();
    }
    /**
     * update Information
     * @access public
     * @return void
     * @param array
     * @param int
     * @param string
     */
    public function updateInformation($data, $id, $table_name) {
        $this->db->where('id', $id);
        $this->db->update($table_name, $data);
    }
    /**
     * update Information By Company Id
     * @access public
     * @return void
     * @param array
     * @param int
     * @param string
     */
    public function updateInformationByCompanyId($data, $company_id, $table_name) {
        $this->db->where('company_id', $company_id);
        $this->db->update($table_name, $data);
    }
    /**
     * deleting Multiple Form Data
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function deletingMultipleFormData($field_name, $primary_table_id, $table_name) {
        $this->db->delete($table_name, array($field_name => $primary_table_id));
    }
    /**
     * get All Customers
     * @access public
     * @return object
     * @param no
     */
    public function getAllCustomers() {
        return $this->db->get("tbl_customers")->result();
    }
    /**
     * get Purchase Paid Amount
     * @access public
     * @return object
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
     * @return object
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
     */
    public function top_ten_food_menu($start_date, $end_date) {
        $outlet_id = $this->session->userdata('outlet_id');
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
        return $this->db->get()->result();
    }
    /**
     * get Main Menu
     * @access public
     * @return object
     * @param no
     */
    public function getMainMenu() {
        $this->db->select('*');
        $this->db->from('tbl_admin_user_menus');
        $this->db->where('is_ignore!=', 1);
        $this->db->order_by('order_by asc');
        return $this->db->get()->result();
    }

    /**
     * top ten supplier payable
     * @access public
     * @return object
     * @param no
     */
    public function top_ten_supplier_payable() {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('sum(due) as totalDue,supplier_id,date,name');
        $this->db->from('tbl_purchase');
        $this->db->join('tbl_suppliers', 'tbl_suppliers.id = tbl_purchase.supplier_id', 'left');
        $this->db->order_by('totalDue desc');
        $this->db->where('tbl_purchase.outlet_id', $outlet_id);
        $this->db->where('tbl_purchase.del_status', 'Live');
        $this->db->group_by('tbl_purchase.supplier_id');
        return $this->db->get()->result();
    }
    /**
     * top ten supplier payable
     * @access public
     * @return object
     * @param no
     */
    public function getPaymentHistory($company_id='') {
        $this->db->select('tbl_payment_histories.*,tbl_companies.business_name');
        $this->db->from('tbl_payment_histories');
        $this->db->join('tbl_companies', 'tbl_companies.id = tbl_payment_histories.company_id', 'left');
        if($company_id!=''){
            $this->db->where('tbl_payment_histories.company_id', $company_id);
        }
        $this->db->where('tbl_payment_histories.del_status', 'Live');
        $this->db->order_by('tbl_payment_histories.id',"DESC");
        return $this->db->get()->result();
    }
    /**
     * get Payable Amount By Supplier Id
     * @access public
     * @return float
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
     */
    public function setDefaultTimezone() {
        $this->db->select("zone_name");
        $this->db->from('tbl_companies');
        $this->db->where('del_status', "Live");
        $zoneName = $this->db->get()->row();
        if ($zoneName)
            date_default_timezone_set($zoneName->zone_name);
    }
    /**
     * get custom row depend on parameters
     * @access public
     * @return object
     * @param string
     * @param string
     * @param string
     * @param string
     * @param int
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
     * get custom row array depend on parameters
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
     * qcode function
     * @access public
     * @return string
     * @param string
     * @param string
     * @param int
     */
    public function qcode_function($code,$level='S',$size=2){       
            $this->load->library('ci_qr_code');
            $this->config->load('qr_code');
            $qr_code_config = array(); 
            $qr_code_config['cacheable']    = $this->config->item('cacheable');
            $qr_code_config['cachedir']     = $this->config->item('cachedir');
            $qr_code_config['imagedir']     = $this->config->item('imagedir');
            $qr_code_config['errorlog']     = $this->config->item('errorlog');
            $qr_code_config['ciqrcodelib']  = $this->config->item('ciqrcodelib');
            $qr_code_config['quality']      = $this->config->item('quality');
            $qr_code_config['size']         = $this->config->item('size');
            $qr_code_config['black']        = $this->config->item('black');
            $qr_code_config['white']        = $this->config->item('white');
            $this->ci_qr_code->initialize($qr_code_config);
            $image_name =$code.'.png';
            $params['data'] = $code;
            $params['level'] = 'S';
            $params['size'] =3;
            $params['savename'] = FCPATH.$qr_code_config['imagedir'].$image_name;
            $this->ci_qr_code->generate($params); 
            $qr_code_image_url = base_url().$qr_code_config['imagedir'].$image_name;
            return $qr_code_image_url;
    }
    /**
     * check existing account by email
     * @access public
     * @param string
     */
    public function checkExistingAccountByEmail($email){
        $this->db->select('*');
        $this->db->from("tbl_customers");
        $this->db->where("email", $email);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->row();
    }
}

?>
