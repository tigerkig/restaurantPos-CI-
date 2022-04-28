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
  # This is User_model Model
  ###########################################################
 */
class User_model extends CI_Model {
    /**
     * get User Menu Access
     * @access public
     * @return object
     * @param int
     */
    public function getUserMenuAccess($user_id) {
        $this->db->select("tbl_user_menu_access.menu_id");
        $this->db->from("tbl_user_menu_access");
        $this->db->where("user_id", $user_id);
        return $this->db->get()->result();
    }
    /**
     * get Users By Company Id
     * @access public
     * @return object
     * @param int
     */
    public function getUsersByCompanyId($company_id) {
        $language_manifesto = $this->session->userdata('language_manifesto');
        if(str_rot13($language_manifesto)=="eriutoeri"){
            $this->db->select("tbl_users.*,tbl_outlets.outlet_name");
            $this->db->from("tbl_users");
            $this->db->join('tbl_outlets', 'tbl_outlets.id = tbl_users.outlet_id', 'left');
            if($company_id!=1){
                $this->db->where("tbl_users.company_id", $company_id);
            }
            $this->db->where("tbl_users.del_status", 'Live');
            return $this->db->get()->result();
        }else{
            $outlet_id = $this->session->userdata('outlet_id');
            $this->db->select("tbl_users.*,tbl_outlets.outlet_name");
            $this->db->from("tbl_users");
            $this->db->join('tbl_outlets', 'tbl_outlets.id = tbl_users.outlet_id', 'left');
            if($company_id!=1){
                $this->db->where("tbl_users.company_id", $company_id);
            }
            $this->db->where("tbl_users.outlet_id", $outlet_id);
            $this->db->where("tbl_users.del_status", 'Live');
            return $this->db->get()->result();
        }
    }

}

