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
  # This is Attendance_model Model
  ###########################################################
 */
class Attendance_model extends CI_Model {
    /**
     * generate reference no
     * @access public
     * @return object
     * @param no
     */
    public function generateReferenceNo() {
        $reference_no = $this->db->query("SELECT count(id) as reference_no
               FROM tbl_attendance")->row('reference_no');
        $reference_no = str_pad($reference_no + 1, 6, '0', STR_PAD_LEFT);
        return $reference_no;
    }

}

