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
  # This is Outlet_model Model
  ###########################################################
 */
class Outlet_model extends CI_Model {
    /**
     * generate Outlet Code
     * @access public
     * @return string
     * @param no
     */
    public function generateOutletCode() {
        $company_id = $this->session->userdata('company_id');
        $count = $this->db->query("SELECT count(id) as count
               FROM tbl_outlets WHERE company_id='$company_id'")->row('count');
        $code = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
        return $code;
    }
    /**
     * outlet count
     * @access public
     * @return object
     * @param no
     */
    public function outlet_count() {
        $this->db->select("*");
        $this->db->from("tbl_outlets");
        $this->db->where("company_id", $this->session->userdata('company_id'));
        $this->db->where("del_status", 'Live');
        return $this->db->get()->num_rows();
    }


}

