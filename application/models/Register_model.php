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
  # This is Register_model Model
  ###########################################################
 */
class Register_model extends CI_Model {

     /**
     * get Menu Access Of This User
     * @access public
     * @return object
     * @param no
     */
    public function getMenuAccessOfThisUser()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('tbl_user_menu_access');
        $this->db->where("user_id", $user_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }
     /**
     * checkAccess
     * @access public
     * @return boolean
     * @param array
     */
    public function checkAccess($records){
        $result = false;
        foreach($records as $single_record){
            if($single_record->menu_id==1 || ($single_record->menu_id>=14 && $single_record->menu_id<=18))
            {
                $result = true;
            }
        }
        return $result;
    }
     /**
     * check Register
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function checkRegister($user_id, $outlet_id)
    {
      $this->db->select("register_status as status");
      $this->db->from('tbl_register');
      $this->db->where("user_id", $user_id);
      $this->db->where("outlet_id", $outlet_id);
      $this->db->order_by('id', 'DESC');
      return $this->db->get()->row(); 
    }
}

