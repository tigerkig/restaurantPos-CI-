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
  # This is Transfer_model Model
  ###########################################################
 */
class Transfer_model extends CI_Model {
 /**
     * generate Transfer Ref No
     * @access public
     * @return string
     * @param int
     */
    public function generatePurRefNo($outlet_id) {
        $transfer_count = $this->db->query("SELECT count(id) as transfer_count
               FROM tbl_transfer where outlet_id=$outlet_id")->row('transfer_count');
        $ingredient_code = str_pad($transfer_count + 1, 6, '0', STR_PAD_LEFT);
        return $ingredient_code;
    }
 /**
     * get Ingredient List With Unit And Price
     * @access public
     * @return object
     * @param int
     */
    public function getIngredientListWithUnitAndPrice($company_id) {
        $result = $this->db->query("SELECT tbl_ingredients.id, tbl_ingredients.name, tbl_ingredients.code, tbl_ingredients.purchase_price, tbl_units.unit_name
          FROM tbl_ingredients 
          JOIN tbl_units ON tbl_ingredients.unit_id = tbl_units.id
          WHERE tbl_ingredients.company_id=$company_id AND tbl_ingredients.del_status = 'Live'  
          ORDER BY tbl_ingredients.name ASC")->result();
        return $result;
    }
    /**
     * get Transfer Ingredients
     * @access public
     * @return object
     * @param int
     */
    public function getTransferIngredients($id) {
        $this->db->select("*");
        $this->db->from("tbl_transfer_ingredients");
        $this->db->order_by('id', 'ASC');
        $this->db->where("transfer_id", $id);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }
    public function getFoodDetails($id) {
        $this->db->select("*");
        $this->db->from("tbl_transfer_ingredients");
        $this->db->order_by('id', 'ASC');
        $this->db->where("transfer_id", $id);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }

    public function getAllTrasferData($outlet_id){
        $this->db->select("*");
        $this->db->from('tbl_transfer');
        $this->db->where("(outlet_id=$outlet_id OR to_outlet_id= $outlet_id AND (status='1' OR status='3'))");
        $this->db->order_by('id', 'DESC');
        $result = $this->db->get();

        if($result != false){
            return $result->result();
        }else{
            return false;
        }
    }

    public function getTotalCostAmount($food_menu_id) {
        $this->db->select('purchase_price,consumption');
        $this->db->from('tbl_food_menus_ingredients');
        $this->db->join('tbl_ingredients', 'tbl_ingredients.id = tbl_food_menus_ingredients.ingredient_id', 'left');
        $this->db->where('tbl_food_menus_ingredients.food_menu_id', $food_menu_id);
        $this->db->where('tbl_food_menus_ingredients.del_status', 'Live');
        return $this->db->get()->result();
    }

}

