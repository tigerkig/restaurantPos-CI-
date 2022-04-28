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
  # This is Inventory_model Model
  ###########################################################
 */
class Inventory_model extends CI_Model {

     /**
     * get Data By Cat Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getDataByCatId($cat_id, $table_name) {
        $this->db->select("id,name,code");
        $this->db->from($table_name);
        $this->db->where("category_id", $cat_id);
        $this->db->order_by("name", "ASC");
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }
     /**
     * get Inventory
     * @access public
     * @return object
     * @param string
     * @param string
     * @param string
     */
    public function getInventory($category_id = "", $ingredient_id = "", $food_id = "") {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $where = '';
        $where1 = '';
        if($food_id!=''){
            $getFMIds = $food_id;
        }else{
            $getFMIds = getFMIds($outlet_id);
        }
        if($category_id!=''){
            $where1.= "  AND ingr_tbl.category_id = '$category_id'";
        }
        if($ingredient_id!=''){
            $where1.= "  AND i.id = '$ingredient_id'";
        }
        //get selected food menu ids
            if($food_id){
                $result = $this->db->query("SELECT ingr_tbl.*,i.food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
        (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
        (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
        (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

        FROM tbl_food_menus_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.ingredient_id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE FIND_IN_SET(`food_menu_id`, '$getFMIds') AND i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.ingredient_id")->result();
                return $result;
            }else{
                $result = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
        (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
        (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
        (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND tbl_transfer_ingredients.status=1  AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

        FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE i.company_id= '$company_id' AND i.del_status='Live' $where1 GROUP BY i.id")->result();
                return $result;
            }

    }
    public function getInventoryFoodMenu($food_id = "",$category_id='') {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        $where = '';
        $where1 = '';
        if($food_id!=''){
            $getFMIds = $food_id;
        }else{
            $getFMIds = getFMIds($outlet_id);
        }
        if($category_id!=''){
            $where1.= "  AND i.category_id = '$category_id'";
        }
        //get selected food menu ids
        $result = $this->db->query("SELECT i.*,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=2) total_transfer_plus_2,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=2) total_transfer_minus_2,
        (select SUM(qty) from tbl_sales_details  where food_menu_id=i.id AND outlet_id=$outlet_id AND del_status='Live') sale_total
         FROM tbl_food_menus i  WHERE FIND_IN_SET(`id`, '$getFMIds') AND i.company_id= '$company_id' AND i.del_status='Live' $where1")->result();
        return $result;
    }
    public function checkInventory($food_id = "") {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        //get selected food menu ids
        $result = $this->db->query("SELECT i.*,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=2) total_transfer_plus_2,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=2) total_transfer_minus_2,
        (select SUM(qty) from tbl_sales_details  where food_menu_id=i.id AND outlet_id=$outlet_id AND del_status='Live') sale_total
         FROM tbl_food_menus i  WHERE id='$food_id' AND i.company_id= '$company_id' AND i.del_status='Live'")->row();
        return $result;
    }
     /**
     * get Inventory Alert List
     * @access public
     * @return object
     * @param no
     */
    public function getInventoryAlertList() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $where = '';
        $getFMIds = getFMIds($outlet_id);

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

FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE  i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.id")->result();
        return $result;
    }
     /**
     * get All By Company Id For Dropdown
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByCompanyIdForDropdown($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY name ASC")->result();
        return $result;
    }

}

?>
