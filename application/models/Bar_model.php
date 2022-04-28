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
  # This is Bar_model Model
  ###########################################################
 */
class Bar_model extends CI_Model {

    /**
     * get New Orders
     * @access public
     * @return object
     * @param int
     */
    public function getNewOrders($outlet_id){
        $this->db->select("*,tbl_sales.id as sale_id, tbl_customers.name as customer_name, tbl_sales.id as sales_id,tbl_users.full_name as waiter_name,tbl_tables.name as table_name");
        $this->db->from('tbl_sales');
        $this->db->where("tbl_sales.outlet_id", $outlet_id);
        $this->db->where("(order_status='1' OR order_status='2')");
        $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.waiter_id', 'left');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->order_by('tbl_sales.id', 'ASC');
        return $this->db->get()->result();
    }
    /**
     * get total bar type items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_bar_type_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        $this->db->where("item_type", "Bar Item");
        return $this->db->get()->num_rows();    
    }
    /**
     * get total bar type done items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_bar_type_done_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        $this->db->where("item_type", "Bar Item");
        $this->db->where("cooking_status", "Done");
        return $this->db->get()->num_rows();    
    }
    /**
     * get total bar type started cooking items
     * @access public
     * @return object
     * @param int
     */
    public function get_total_bar_type_started_cooking_items($sale_id)
    {
        $this->db->select('id');
        $this->db->from('tbl_sales_details');
        $this->db->where("sales_id", $sale_id);
        $this->db->where("item_type", "Bar Item");
        $this->db->where("cooking_status", "Started Cooking");
        return $this->db->get()->num_rows();    
    }
    /**
     * get Item Info By Previous Id
     * @access public
     * @return object
     * @param int
     */
    public function getItemInfoByPreviousId($previous_id)
    {
        $this->db->select('tbl_sales_details.*,tbl_food_menus.code as code,tbl_food_menus.name as menu_name');
        $this->db->from('tbl_sales_details');
        $this->db->join('tbl_food_menus', 'tbl_food_menus.id = tbl_sales_details.food_menu_id', 'left');
        $this->db->where("previous_id", $previous_id);
        return $this->db->get()->row();   
    }
    /**
     * get Sale By Sale Id
     * @access public
     * @return object
     * @param int
     */
    public function getSaleBySaleId($sales_id){
        $this->db->select("tbl_sales.*,w.full_name as waiter_name,tbl_customers.name as customer_name,tbl_tables.name as table_name,tbl_users.full_name as user_name");
        $this->db->from('tbl_sales');
        $this->db->join('tbl_customers', 'tbl_customers.id = tbl_sales.customer_id', 'left');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_sales.user_id', 'left');
        $this->db->join('tbl_tables', 'tbl_tables.id = tbl_sales.table_id', 'left');
        $this->db->join('tbl_users w', 'w.id = tbl_sales.waiter_id', 'left');
        $this->db->where("tbl_sales.id", $sales_id);
        $this->db->order_by('tbl_sales.id', 'ASC');
        return $this->db->get()->result();
    }
    /**
     * get All Bar Items From Sales Detail By Sales Id
     * @access public
     * @return object
     * @param int
     */
    public function getAllBarItemsFromSalesDetailBySalesId($sales_id){
        $this->db->select("tbl_sales_details.*,tbl_sales_details.id as sales_details_id,tbl_food_menus.code as code,tbl_food_menus.name as menu_name");
        $this->db->from('tbl_sales_details');
        $this->db->join('tbl_food_menus', 'tbl_food_menus.id = tbl_sales_details.food_menu_id', 'left');
        $this->db->where("sales_id", $sales_id);
        $this->db->where("item_type", "Bar Item");
        $this->db->order_by('tbl_sales_details.id', 'ASC');
        return $this->db->get()->result(); 
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
        return $this->db->get()->result(); 
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
      $this->db->from('tbl_notification_bar_kitchen_panel');
      $this->db->where("outlet_id", $outlet_id);
      $this->db->order_by('id', 'ASC');
      return $this->db->get()->result(); 
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
      return $this->db->get()->result();
      
    }
}

