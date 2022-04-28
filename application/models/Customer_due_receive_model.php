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
  # This is Customer_due_receive_model Model
  ###########################################################
 */
class Customer_due_receive_model extends CI_Model {

    /**
     * get Customer Due
     * @access public
     * @return float
     * @param int
     */
    public function getCustomerDue($customer_id) {
        $outlet_id = $this->session->userdata('outlet_id');
        $customer_due = $this->db->query("SELECT SUM(due_amount) as due FROM tbl_sales WHERE customer_id=$customer_id and outlet_id=$outlet_id and del_status='Live'")->row();
        $customer_payment = $this->db->query("SELECT SUM(amount) as amount FROM tbl_customer_due_receives WHERE customer_id=$customer_id and outlet_id=$outlet_id and del_status='Live'")->row();
        $remaining_due = $customer_due->due - $customer_payment->amount;
        return $remaining_due;
 
    }
    /**
     * generate Reference No
     * @access public
     * @return string
     * @param int
     */
    public function generateReferenceNo($outlet_id) {
        $reference_no = $this->db->query("SELECT count(id) as reference_no
               FROM tbl_customer_due_receives where outlet_id=$outlet_id")->row('reference_no');
        $reference_no = str_pad($reference_no + 1, 6, '0', STR_PAD_LEFT);
        return $reference_no;
    }

}

