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
  # This is Sale_model Model
  ###########################################################
 */
class Sale_model extends CI_Model {

    /**
     * get Sale List
     * @access public
     * @return object
     * @param int
     */
    public function getSaleList($outlet_id) {
        $result = $this->db->query("SELECT s.*,u.full_name,c.name as customer_name,m.name
          FROM tbl_sales s
          INNER JOIN tbl_customers c ON(s.customer_id=c.id)
          LEFT JOIN tbl_users u ON(s.user_id=u.id)
          LEFT JOIN tbl_payment_methods m ON(s.payment_method_id=m.id) 
          WHERE s.order_status = '3' AND s.del_status = 'Live' AND s.outlet_id=$outlet_id ORDER BY s.id DESC")->result();
        return $result;
    }
    /**
     * get Item Menu Categories
     * @access public
     * @return object
     * @param int
     */
    public function getItemMenuCategories($company_id) {
        $result = $this->db->query("SELECT * 
          FROM tbl_ingredient_categories 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY category_name");
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * export Daily Sales
     * @access public
     * @return object
     * @param no
     */
    public function exportDailySale() {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select('tbl_sales.*,tbl_users.full_name,tbl_payment_methods.name,tbl_customers.name as customer_name');
        $this->db->from('tbl_sales');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.user_id', 'left');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->join('tbl_payment_methods', 'tbl_payment_methods.id = tbl_sales.payment_method_id', 'left');
        $this->db->where('order_status', '3');
        $this->db->where('tbl_sales.outlet_id', $outlet_id);
        $this->db->where('tbl_sales.del_status', 'Live');
        $this->db->order_by('sale_date', 'ASC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    /**
     * get Item Menus
     * @access public
     * @return object
     * @param int
     */
    public function getItemMenus($outlet_id) {
        $result = $this->db->query("SELECT * 
          FROM tbl_ingredients 
          WHERE outlet_id=$outlet_id AND del_status = 'Live'  
          ORDER BY name");
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Item List With Unit
     * @access public
     * @return object
     * @param int
     */
    public function getItemListWithUnit($outlet_id) {
        $result = $this->db->query("SELECT tbl_ingredients.id, tbl_ingredients.name, tbl_units.unit_name 
          FROM tbl_ingredients 
          JOIN tbl_units ON tbl_ingredients.unit_id = tbl_units.id
          WHERE outlet_id=$outlet_id AND tbl_ingredients.del_status = 'Live'  
          ORDER BY tbl_ingredients.name ASC");
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Food Menu Ingredients
     * @access public
     * @return object
     * @param int
     */
    public function getFoodMenuIngredients($id) {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select("*");
        $this->db->from("tbl_food_menus_ingredients");
        $this->db->order_by('id', 'ASC');
        $this->db->where("food_menu_id", $id);
        $this->db->where("del_status", 'Live');
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Item Menu Items
     * @access public
     * @return object
     * @param int
     */
    public function getItemMenuItems($id) {
        $outlet_id = $this->session->userdata('outlet_id');
        $this->db->select("*");
        $this->db->from("tbl_ingredients_items");
        $this->db->order_by('id', 'ASC');
        $this->db->where("ingredient_id", $id);
        $this->db->where("outlet_id", $outlet_id);
        $this->db->where("del_status", 'Live');
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        };
    }
    /**
     * get All Item menus
     * @access public
     * @return object
     * @param no
     */
    public function getAllItemmenus() {
        $company_id = $this->session->userdata('company_id');
        $result = $this->db->query("SELECT tbl_food_menus.id, tbl_food_menus.code, tbl_food_menus.name, tbl_food_menus.sale_price, tbl_food_menus.photo, tbl_food_menu_categories.category_name
          FROM tbl_food_menus 
          LEFT JOIN tbl_food_menu_categories ON tbl_food_menus.category_id = tbl_food_menu_categories.id
          WHERE tbl_food_menus.company_id=$company_id AND tbl_food_menus.del_status = 'Live'  
          ORDER BY tbl_food_menus.name ASC");
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Food Menu Categories
     * @access public
     * @return object
     * @param int
     */
    public function getFoodMenuCategories($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_food_menu_categories");
        $this->db->where("company_id", $company_id);
        $this->db->where("del_status", 'Live');
        $this->db->order_by("category_name", 'ASC');
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Sale Info
     * @access public
     * @return object
     * @param int
     */
    public function getSaleInfo($sales_id) {
        $outlet_id = $this->session->userdata('outlet_id');
        $result = $this->db->query("SELECT s.*,u.full_name,c.name as customer_name,m.name,tbl.name as table_name
          FROM tbl_sales s
          INNER JOIN tbl_customers c ON(s.customer_id=c.id)
          LEFT JOIN tbl_users u ON(s.user_id=u.id)
          LEFT JOIN tbl_payment_methods m ON(s.payment_method_id=m.id)
          LEFT JOIN tbl_tables tbl ON(s.table_id=tbl.id) 
          WHERE s.id=$sales_id AND s.del_status = 'Live' AND s.outlet_id=$outlet_id")->row();
        return $result;
    }
    /**
     * get Sale Details
     * @access public
     * @return boolean
     * @param int
     */
    public function getSaleDetails($sales_id) {
        $outlet_id = $this->session->userdata('outlet_id');
        $result = $this->db->query("SELECT sd.*,fm.code
          FROM tbl_sales_details sd
          LEFT JOIN tbl_food_menus fm ON(sd.food_menu_id=fm.id)
          WHERE sd.sales_id=$sales_id AND sd.outlet_id=$outlet_id AND sd.del_status = 'Live'  
          ORDER BY sd.id ASC");
        $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * generate Token no
     * @access public
     * @return object
     * @param int
     */
    public function generateToken_no($outlet_id) {
        $year = date('ymd', strtotime('today'));
        $sale_count = $this->db->query("SELECT count(id) as sale_count
               FROM tbl_sales where outlet_id=$outlet_id")->row('sale_count');
        $token_no = $year . str_pad($sale_count + 1, 2, '0', STR_PAD_LEFT);
        return $token_no;
    }
    /**
     * get All Food Menus
     * @access public
     * @return boolean
     * @param no
     */
    public function getAllFoodMenus(){
      $outlet_id = $this->session->userdata('outlet_id');
      $getFM = getFMIds($outlet_id);
      $result = $this->db->query("SELECT fm.*,fmc.category_name, COUNT(sd.food_menu_id) as item_sold 
      FROM tbl_food_menus fm  LEFT JOIN (select * from tbl_food_menu_categories where del_status='Live') fmc ON fmc.id = fm.category_id LEFT JOIN (select * from tbl_sales_details where del_status='Live') sd ON sd.food_menu_id = fm.id WHERE FIND_IN_SET(fm.id, '$getFM') AND fm.del_status='Live' GROUP BY fm.id order BY name ASC")->result();
      if($result != false){
        return $result;
      }else{
        return false;
      }
    }
    /**
     * get All Menu Categories
     * @access public
     * @return boolean
     * @param no
     */
    public function getAllMenuCategories(){
      $company_id = $this->session->userdata('company_id');
      $this->db->select("*");
      $this->db->from("tbl_food_menu_categories");
      $this->db->where("company_id", $company_id);
      $this->db->where("del_status", 'Live');
      $this->db->order_by('id', 'ASC');
      $result = $this->db->get();

      if($result != false){
        return $result->result();
      }else{
        return false;
      }
    }
    /**
     * get All Menu Modifiers
     * @access public
     * @return boolean
     * @param no
     */
    public function getAllMenuModifiers(){
     $company_id = $this->session->userdata('company_id');
      $this->db->select("tbl_food_menus_modifiers.*,tbl_modifiers.name,tbl_modifiers.price");
      $this->db->from("tbl_food_menus_modifiers");
      $this->db->join('tbl_modifiers', 'tbl_modifiers.id = tbl_food_menus_modifiers.modifier_id', 'left');
      $this->db->where("tbl_food_menus_modifiers.company_id", $company_id);
      $this->db->where("tbl_food_menus_modifiers.del_status", 'Live');
      $this->db->order_by('id', 'ASC');
      $result = $this->db->get();

      if($result != false){
        return $result->result();
      }else{
        return false;
      }
    }
    /**
     * get Waiters For This Company
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getWaitersForThisCompany($company_id,$table){
        $language_manifesto = $this->session->userdata('language_manifesto');
        if(str_rot13($language_manifesto)=="eriutoeri"){
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where("company_id", $company_id);
            $this->db->where("designation", 'Waiter');
            $this->db->where("del_status", 'Live');
            $this->db->order_by('full_name', 'ASC');
            $result = $this->db->get();
            if($result != false){
                return $result->result();
            }else{
                return false;
            }
        }else{
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where("company_id", $company_id);
            $this->db->where("outlet_id", $outlet_id);
            $this->db->where("designation", 'Waiter');
            $this->db->where("del_status", 'Live');
            $this->db->order_by('full_name', 'ASC');
            $result = $this->db->get();
            if($result != false){
                return $result->result();
            }else{
                return false;
            }
        }

    }
    /**
     * get New Orders
     * @access public
     * @return object
     * @param int
     */
    public function getNewOrders($outlet_id){
        $today_date = date('Y-m-d');
        $is_waiter = $this->session->userdata('is_waiter');
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

      $this->db->select("*,tbl_sales.id as sale_id,tbl_customers.name as customer_name, tbl_sales.id as sales_id,tbl_users.full_name as waiter_name,tbl_tables.name as table_name");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
      $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.waiter_id', 'left');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      if(isset($is_waiter) && $is_waiter=="Yes"){
          $this->db->where("tbl_sales.waiter_id", $user_id);
      }else{
          if(isset($role) && $role!="Admin"){
              $this->db->where("tbl_sales.user_id", $user_id);
          }
      }
        $this->db->where("tbl_sales.outlet_id", $outlet_id);
        $this->db->where("(order_status='1' OR order_status='2')");
        $this->db->where("(future_sale_status='1' OR future_sale_status='3')");
      $this->db->order_by('tbl_sales.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }

    }

    public function future_sales($outlet_id){
        $today_date = date('Y-m-d');
        $this->db->select("tbl_sales.*,tbl_customers.name as customer_name,tbl_tables.name as table_name,tbl_customers.phone");
        $this->db->from('tbl_sales');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
        $this->db->where("tbl_sales.outlet_id", $outlet_id);
        $this->db->where("tbl_sales.del_status", "Live");
        $this->db->where("(order_status='1' OR order_status='2')");
        $this->db->where("(future_sale_status!='1')");
        $this->db->order_by('tbl_sales.id', 'DESC');
        $result = $this->db->get();

        if($result != false){
            return $result->result();
        }else{
            return false;
        }

    }
    /**
     * get All Tables With New Status
     * @access public
     * @return object
     * @param int
     */
    public function getAllTablesWithNewStatus($outlet_id){
      $this->db->select("*");
      $this->db->from('tbl_sales');
      $this->db->where("(order_status='1' OR order_status='2')");
      $this->db->where("outlet_id", $outlet_id);
      $this->db->order_by('id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Sale By Sale Id
     * @access public
     * @return object
     * @param int
     */
    public function getSaleBySaleId($sales_id){
      $this->db->select("tbl_sales.*,w.full_name as waiter_name,tbl_customers.name as customer_name,tbl_customers.address as customer_address,tbl_tables.name as table_name,tbl_users.full_name as user_name,tbl_companies.invoice_footer as invoice_footer");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.user_id', 'left');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
      $this->db->join('tbl_companies', 'tbl_companies.id = tbl_sales.outlet_id', 'left');
      $this->db->join('tbl_users w', 'w.id = tbl_sales.waiter_id', 'left');
      $this->db->where("tbl_sales.id", $sales_id);
      $this->db->order_by('tbl_sales.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Single Sale By Sale Id
     * @access public
     * @return object
     * @param int
     */
    public function getSingleSaleBySaleId($sales_id){
      $this->db->select("tbl_sales.*,w.full_name as waiter_name,tbl_customers.name as customer_name,tbl_users.full_name as user_name,tbl_companies.invoice_footer as invoice_footer");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.user_id', 'left');
      $this->db->join('tbl_companies', 'tbl_companies.id = tbl_sales.outlet_id', 'left');
      $this->db->join('tbl_users w', 'w.id = tbl_sales.waiter_id', 'left');
      $this->db->where("tbl_sales.id", $sales_id);
      $this->db->order_by('tbl_sales.id', 'ASC');
      return $this->db->get()->row();
    }
    /**
     * get Holds By Outlet And User Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getHoldsByOutletAndUserId($outlet_id,$user_id){
      $this->db->select("tbl_holds.*,tbl_customers.name as customer_name,tbl_tables.name as table_name,tbl_customers.phone");
      $this->db->from('tbl_holds');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_holds.customer_id', 'left');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_holds.table_id', 'left');
      $this->db->where("tbl_holds.outlet_id", $outlet_id);
      $this->db->where("tbl_holds.user_id", $user_id);
      $this->db->where("tbl_holds.del_status", "Live");
      $this->db->order_by('tbl_holds.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Last Ten Sales By Outlet And User Id
     * @access public
     * @return object
     * @param int
     */
    public function getLastTenSalesByOutletAndUserId($outlet_id){
      $this->db->select("tbl_sales.*,tbl_customers.name as customer_name,tbl_tables.name as table_name,tbl_customers.phone");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
      $this->db->where("tbl_sales.outlet_id", $outlet_id);
      $this->db->where("tbl_sales.del_status", "Live");
      $this->db->where("(order_status='2' OR order_status='3')");
      $this->db->limit(20);
      $this->db->order_by('tbl_sales.id', 'DESC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get My Todays Sales By Outlet And User Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getMyTodaysSalesByOutletAndUserId($outlet_id,$user_id){
      $this->db->select("tbl_sales.*,tbl_customers.name as customer_name,tbl_tables.name as table_name");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
      $this->db->where("tbl_sales.outlet_id", $outlet_id);
      $this->db->where("tbl_sales.user_id", $user_id);
      $this->db->where("DATE(tbl_sales.date_time)", date('Y-m-d'));
      $this->db->where("tbl_sales.del_status", "Live");
      $this->db->where("(order_status='2' OR order_status='3')");
      $this->db->order_by('tbl_sales.id', 'DESC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get All Items From Sales Detail By Sales Id
     * @access public
     * @return object
     * @param int
     */
    public function getAllItemsFromSalesDetailBySalesId($sales_id){
      $this->db->select("tbl_sales_details.*,tbl_sales_details.id as sales_details_id,tbl_food_menus.code as code");
      $this->db->from('tbl_sales_details');
      $this->db->join('tbl_food_menus', 'tbl_food_menus.id = tbl_sales_details.food_menu_id', 'left');
      $this->db->where("sales_id", $sales_id);
      $this->db->order_by('tbl_sales_details.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Modifiers By Sale And Sale Details Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getModifiersBySaleAndSaleDetailsId($sales_id,$sale_details_id){
      $this->db->select("tbl_sales_details_modifiers.*,tbl_modifiers.name");
      $this->db->from('tbl_sales_details_modifiers');
      $this->db->join('tbl_modifiers', 'tbl_modifiers.id = tbl_sales_details_modifiers.modifier_id', 'left');
      $this->db->where("tbl_sales_details_modifiers.sales_id", $sales_id);
      $this->db->where("tbl_sales_details_modifiers.sales_details_id", $sale_details_id);
      $this->db->order_by('tbl_sales_details_modifiers.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Number Of Holds By User And Outlet Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getNumberOfHoldsByUserAndOutletId($outlet_id,$user_id)
    {
      $this->db->select('id');
      $this->db->from('tbl_holds');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("user_id", $user_id);
      return $this->db->get()->num_rows();
    }
    /**
     * get new sale by table id
     * @access public
     * @return object
     * @param int
     */
    public function get_new_sale_by_table_id($table_id)
    {
      $this->db->select("*");
      $this->db->from('tbl_sales');
      $this->db->where("table_id", $table_id);
      $this->db->where("order_status", 1);
      return $this->db->get()->row();
    }
    /**
     * get hold info by hold id
     * @access public
     * @return object
     * @param int
     */
    public function get_hold_info_by_hold_id($hold_id)
    {
      $this->db->select("tbl_holds.*,tbl_users.full_name as waiter_name,tbl_customers.name as customer_name,tbl_tables.name as table_name");
      $this->db->from('tbl_holds');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_holds.customer_id', 'left');
      $this->db->join('tbl_users', 'tbl_users.id = tbl_holds.waiter_id', 'left');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_holds.table_id', 'left');
      $this->db->where("tbl_holds.id", $hold_id);
      $this->db->order_by('tbl_holds.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get All Items From Holds Detail By Holds Id
     * @access public
     * @return object
     * @param int
     */
    public function getAllItemsFromHoldsDetailByHoldsId($hold_id)
    {
      $this->db->select("tbl_holds_details.*,tbl_holds_details.id as holds_details_id");
      $this->db->from('tbl_holds_details');
      $this->db->join('tbl_food_menus', 'tbl_food_menus.id = tbl_holds_details.food_menu_id', 'left');
      $this->db->where("holds_id", $hold_id);
      $this->db->order_by('tbl_holds_details.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Modifiers By Hold And Holds Details Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getModifiersByHoldAndHoldsDetailsId($hold_id,$holds_details_id)
    {
      $this->db->select("tbl_holds_details_modifiers.*,tbl_modifiers.name");
      $this->db->from('tbl_holds_details_modifiers');
      $this->db->join('tbl_modifiers', 'tbl_modifiers.id = tbl_holds_details_modifiers.modifier_id', 'left');
      $this->db->where("tbl_holds_details_modifiers.holds_id", $hold_id);
      $this->db->where("tbl_holds_details_modifiers.holds_details_id", $holds_details_id);
      $this->db->order_by('tbl_holds_details_modifiers.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Customer Info By Id
     * @access public
     * @return object
     * @param int
     */
    public function getCustomerInfoById($customer_id)
    {
      $this->db->select("*");
      $this->db->from('tbl_customers');
      $this->db->where("id", $customer_id);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->row();
    }
    /**
     * get All Payment Methods
     * @access public
     * @return boolean
     * @param no
     */
    public function getAllPaymentMethods()
    {
      $company_id = $this->session->userdata('company_id');
      $this->db->select('*');
      $this->db->from('tbl_payment_methods');
      $this->db->where("company_id", $company_id);
      $this->db->where("del_status", 'Live');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Summation Of Paid Purchase
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
	public function getSummationOfPaidPurchase($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid) as purchase_paid");
      $this->db->from('tbl_purchase');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date", $date);
      return $this->db->get()->row();
    }
    /**
     * get Summation Of Supplier Payment
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSummationOfSupplierPayment($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(amount) as payment_amount");
      $this->db->from('tbl_supplier_payments');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date", $date);
      return $this->db->get()->row();
    }
    /**
     * get Summation Of Customer Due Receive
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSummationOfCustomerDueReceive($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(amount) as receive_amount");
      $this->db->from('tbl_customer_due_receives');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date>=", $date);
      $this->db->where("date<=", date('Y-m-d H:i:s'));
      return $this->db->get()->row();
    }
    /**
     * get Expense Amount Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getExpenseAmountSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(amount) as amount");
      $this->db->from('tbl_expenses');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date", $date);
      return $this->db->get()->row();
    }
    /**
     * get Sale Paid Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSalePaidSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      return $this->db->get()->row();
    }
    /**
     * get Sale Due Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSaleDueSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(due_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      return $this->db->get()->row();
    }
    /**
     * get Payable Aomount Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getPayableAomountSum($user_id,$outlet_id, $date)
    {
      $this->db->select("SUM(total_payable) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      return $this->db->get()->row();
    }
    /**
     * get SaleIn Cash Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSaleInCashSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      $this->db->where("payment_method_id", 3);
      return $this->db->get()->row();
    }
    /**
     * get SaleIn Cash Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getAllSaleByDateForRegister($date)
    {
      $user_id = $this->session->userdata('user_id');
      $outlet_id = $this->session->userdata('outlet_id');
      $this->db->select("tbl_sales.paid_amount,tbl_sales.payment_method_id,tbl_sales.user_id,tbl_sales.outlet_id,tbl_payment_methods.name as payment_name");
      $this->db->from('tbl_sales');
      $this->db->join('tbl_payment_methods', 'tbl_payment_methods.id = tbl_sales.payment_method_id', 'left');
      $this->db->where("tbl_sales.user_id", $user_id);
      $this->db->where("tbl_sales.outlet_id", $outlet_id);
      $this->db->where("tbl_sales.date_time>=", $date);
      $this->db->where("tbl_sales.date_time<=", date('Y-m-d H:i:s'));
      return $this->db->get()->result();
    }
    /**
     * get Sale In Paypal Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSaleInPaypalSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      $this->db->where("payment_method_id", 5);
      return $this->db->get()->row();
    }
    /**
     * get Sale In Card Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSaleInCardSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("date_time>=", $date);
      $this->db->where("date_time<=", date('Y-m-d H:i:s'));
      $this->db->where("payment_method_id", 4);
      return $this->db->get()->row();
    }
    /**
     * get Sale In Stripe Sum
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getSaleInStripeSum($user_id, $outlet_id, $date)
    {
      $this->db->select("SUM(paid_amount) as amount");
      $this->db->from('tbl_sales');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("sale_date", $date);
      $this->db->where("payment_method_id", null);
      return $this->db->get()->row();
    }
    /**
     * get Opening Balance
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getOpeningBalance($user_id, $outlet_id, $date)
    {
      $this->db->select("opening_balance as amount");
      $this->db->from('tbl_register');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("register_status", 1);
      //$this->db->where("DATE(opening_balance_date_time)", $date);
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->row();
    }
    /**
     * get Opening Date Time
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getOpeningDateTime($user_id, $outlet_id, $date)
    {
      $this->db->select("opening_balance_date_time as opening_date_time");
      $this->db->from('tbl_register');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("register_status", 1);
      $this->db->order_by('id', 'DESC');
      // $this->db->where("DATE(opening_balance_date_time)", $date);
      return $this->db->get()->row();
    }
    /**
     * get Closing Date Time
     * @access public
     * @return object
     * @param int
     * @param int
     * @param string
     */
    public function getClosingDateTime($user_id, $outlet_id, $date)
    {
      $this->db->select("closing_balance_date_time as closing_date_time");
      $this->db->from('tbl_register');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("register_status", 1);
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->row();
    }
    /**
     * get Item Type
     * @access public
     * @return object
     * @param int
     */
    public function getItemType($item_id)
    {
      $this->db->select('bar_item as item_type');
      $this->db->from('tbl_food_menus');
      $this->db->where('id',$item_id);
      return $this->db->get()->row();
    }
    /**
     * get total kitchen type items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_kitchen_type_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        return $this->db->get()->num_rows();
    }
    /**
     * get total kitchen type done items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_kitchen_type_done_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        $this->db->where("cooking_status", "Done");
        return $this->db->get()->num_rows();
    }
    /**
     * get total kitchen type started cooking items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_kitchen_type_started_cooking_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        $this->db->where("cooking_status", "Started Cooking");
        return $this->db->get()->num_rows();
    }
    /**
     * get Notification By Outlet Id
     * @access public
     * @return object
     * @param int
     */
    public function getNotificationByOutletId($outlet_id)
    {
      $this->db->select('*');
      $this->db->from('tbl_notifications');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->order_by('id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Notification By Outlet Id And User Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getNotificationByOutletIdAndUserId($outlet_id,$user_id)
    {
      $this->db->select('*,tbl_notifications.id as notification_id');
      $this->db->from('tbl_notifications');
      $this->db->join('tbl_sales', 'tbl_sales.id = tbl_notifications.sale_id', 'left');
      $this->db->where("tbl_notifications.outlet_id", $outlet_id);
      $this->db->where("tbl_sales.waiter_id", $user_id);
      $this->db->order_by('tbl_notifications.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get temp kot
     * @access public
     * @return object
     * @param int
     */
    public function get_temp_kot($id)
    {
      $this->db->select('*');
      $this->db->from('tbl_temp_kot');
      $this->db->where("id", $id);
      return $this->db->get()->row();
    }
    /**
     * get Tables By Outlet Id
     * @access public
     * @return object
     * @param int
     */
    public function getTablesByOutletId($outlet_id) {
      $this->db->select('*');
      $this->db->from('tbl_tables');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->order_by('id', 'ASC');
      $this->db->where("del_status", "Live");

      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Orders Of Table By Table Id
     * @access public
     * @return object
     * @param int
     */
    public function getOrdersOfTableByTableId($table_id)
    {
      $this->db->select('*');
      $this->db->from('tbl_orders_table');
      $this->db->where("table_id", $table_id);
      $this->db->where("del_status", "Live");

      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Table Availability
     * @access public
     * @return object
     * @param int
     */
    public function getTableAvailability($outlet_id)
    {
      $this->db->select('SUM(persons) as persons_number,table_id');
      $this->db->from('tbl_orders_table');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("del_status", "Live");
      $this->db->group_by('table_id');

      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get all assets
     * @access public
     * @return object
     * @param int
     */
    public function get_all_assets($outlet_id)
    {
      $this->db->select('*');
      $this->db->from('tbl_assets');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("del_status", 'Live');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get Games Of Asset By Asset Id
     * @access public
     * @return object
     * @param int
     */
    public function getGamesOfAssetByAssetId($asset_id)
    {
      $this->db->select('*,tbl_games.name');
      $this->db->from('tbl_assets_games');
      $this->db->join('tbl_games', 'tbl_games.id = tbl_assets_games.game_id', 'left');
      $this->db->where("asset_id", $asset_id);

      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }
    /**
     * get First User Information
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getFirstUserInformationBy($outlet_id,$user_type)
    {
      $this->db->select('*');
      $this->db->from('tbl_users');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->where("role", $user_type);
      $this->db->where("del_status", 'Live');
      $this->db->order_by('id', 'ASC');
      $this->db->limit(1);
      return $this->db->get()->row();
    }
    /**
     * get all tables of a sale items
     * @access public
     * @return object
     * @param int
     */
    public function get_all_tables_of_a_sale_items($sale_id)
    {
      $this->db->select('tbl_tables.name as table_name');
      $this->db->from('tbl_orders_table');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_orders_table.table_id', 'left');
      $this->db->where("sale_id", $sale_id);
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }

    }
    /**
     * get all tables of a sale items
     * @access public
     * @return object
     * @param int
     */
    public function get_all_tables_of_a_hold_items($hold_id)
    {
      $this->db->select('tbl_holds_table.*,tbl_tables.name as table_name');
      $this->db->from('tbl_holds_table');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_holds_table.table_id', 'left');
      $this->db->where("hold_id", $hold_id);
      $result = $this->db->get();
        if($result != false){
          return $result->result();
        }else{
          return false;
        }

    }
    /**
     * get all tables of a last sale
     * @access public
     * @return object
     * @param int
     */
    public function get_all_tables_of_a_last_sale($sale_id)
    {
      $this->db->select('tbl_tables.name as table_name');
      $this->db->from('tbl_orders_table');
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_orders_table.table_id', 'left');
      $this->db->where("sale_id", $sale_id);
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }

    }
    /**
     * delete status orders table
     * @access public
     * @return object
     * @param int
     */
    public function delete_status_orders_table($sale_id)
    {
        $this->db->set('del_status', "Deleted");
        $this->db->where('sale_id', $sale_id);
        $this->db->update('tbl_orders_table');
    }
    /**
     * get Cash Method
     * @access public
     * @return object
     * @param no
     */
    public function getCashMethod()
    {
      $this->db->select('*');
      $this->db->from('tbl_payment_methods');
      $this->db->where("name", 'Cash');
      return $this->db->get()->row();
    }
    /**
     * get Running Orders By Outlet And Waiter Id
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function getRunningOrdersByOutletAndWaiterId($outlet_id,$waiter_id){
      $this->db->select("*,tbl_sales.id as sale_id,tbl_customers.name as customer_name, tbl_sales.id as sales_id,tbl_users.full_name as waiter_name,tbl_tables.name as table_name");
      $this->db->from('tbl_sales');
      $this->db->where("tbl_sales.outlet_id", $outlet_id);
      $this->db->where("tbl_sales.waiter_id", $waiter_id);
      $this->db->where("(order_status='1' OR order_status='2')");
      $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
      $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.waiter_id', 'left');
      $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
      $this->db->order_by('tbl_sales.id', 'ASC');
      $result = $this->db->get();

        if($result != false){
          return $result->result();
        }else{
          return false;
        }
    }


}

