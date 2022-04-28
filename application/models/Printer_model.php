<?php
/*
  ###########################################################
  # PRODUCT NAME: 	One Stop
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Doorsoft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Printer_model Model
  ###########################################################
 */
class Printer_model extends CI_Model {
    /**
     * return all printer data
     * @access public
     * @param int
     */
    public function getPrinterByCompanyId($company_id) {
        $this->db->select("tbl_printers.*,tbl_outlets.outlet_name");
        $this->db->from("tbl_printers");
        $this->db->join('tbl_outlets', 'tbl_outlets.id = tbl_printers.outlet_id', 'left');
        $this->db->where("tbl_printers.company_id", $company_id);
        $this->db->where("tbl_printers.del_status", 'Live');
        $this->db->order_by("tbl_printers.id", 'DESC');
        return $this->db->get()->result();
    }
    /**
     * return all printer data
     * @access public
     * @param int
     */
    public function getPrinterByOrder($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_printers");
        $this->db->where("company_id", $company_id);
        $this->db->where("del_status", 'Live');
        $this->db->order_by("order_by", 'ASC');
        return $this->db->get()->result();
    }
    /**
     * return true if total active less then 4 otherwise return false
     * @access public
     */
    public function checkActive() {
        $this->db->select("count(id) as total");
        $this->db->from("tbl_printers");
        $this->db->where("active_status", 'Active');
        $this->db->where("del_status", 'Live');
        $total =  $this->db->get()->row()->total;
        if($total<4){
            return false;
        }else{
            return true;
        }
    }

}

