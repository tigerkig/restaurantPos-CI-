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
  # This is Inventory_adjustment_model Model
  ###########################################################
 */
class Inventory_adjustment_model extends CI_Model {

     /**
     * get Ingredient List
     * @access public
     * @return object
     * @param no
     */
    public function getIngredientList() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        $this->db->select("tbl_ingredients.id, tbl_ingredients.name, tbl_ingredients.code, tbl_units.unit_name");
        $this->db->from("tbl_ingredients"); 
        $this->db->join("tbl_units", 'tbl_units.id = tbl_ingredients.unit_id', 'left');
        $this->db->order_by("tbl_ingredients.name", "ASC");
        $this->db->where("tbl_ingredients.company_id", $company_id); 
        $result = $this->db->get()->result();
        return $result;
    }
     /**
     * get Inventory Adjustment Ingredients
     * @access public
     * @return object
     * @param int
     */
    public function getInventoryAdjustmentIngredients($id) {
        $this->db->select("*");
        $this->db->from("tbl_inventory_adjustment_ingredients");
        $this->db->order_by('id', 'ASC');
        $this->db->where("inventory_adjustment_id", $id);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }
     /**
     * generate Reference No
     * @access public
     * @return string
     * @param int
     */
    public function generateReferenceNo($outlet_id) {
        $inventory_adjustment_count = $this->db->query("SELECT count(id) as inventory_adjustment_count
               FROM tbl_inventory_adjustment where outlet_id=$outlet_id")->row('inventory_adjustment_count');
        $reference_no = str_pad($inventory_adjustment_count + 1, 6, '0', STR_PAD_LEFT);
        return $reference_no;
    }

}

