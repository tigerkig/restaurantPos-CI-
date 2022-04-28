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
  # This is Waiter_api_model Model
  ###########################################################
 */
class Waiter_api_model extends CI_Model {
    /**
     * get Notification By Outlet Id
     * @access public
     * @return object
     * @param int
     */
    public function getNotificationByOutletId($waiter_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_notifications');
        $this->db->where('waiter_id', $waiter_id);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get();

        if($result != false){
            return $result->result();
        }else{
            return false;
        }
    }/**
     * get Notification By Outlet Id
     * @access public
     * @return boolean
     * @param int
     */
    public function get_outlet_name($outlet_id)
    {
        $this->db->select('*');
        $this->db->from('tbl_outlets');
        $this->db->where('id', $outlet_id);
        $result = $this->db->get();
        if($result != false){
            return $result->row();
        }else{
            return false;
        }
    }
    /**
     * get User Information
     * @access public
     * @return boolean
     * @param string
     * @param string
     */
    public function getUserInformationWater($email_address, $password) {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("email_address", $email_address);
        $this->db->where("password", md5($password));
        $this->db->where("del_status", 'Live');
        $data =  $this->db->get()->row();
        $return_details['status'] = false;
        if($data){
            if($data->role=="Admin"){
                $return_details['status'] = true;
                $return_details['msg'] = "Successfully login";
                $return_details['data'] = $data;
                return $return_details;
            }else{
                if(!isAccess($data->id) && $data->will_login=="Yes"){
                    $return_details['status'] = false;
                    $return_details['msg'] = "You have not access for Waiter Panel";
                }elseif($data->active_status!="Active"){
                    $return_details['status'] = false;
                    $return_details['msg'] = "Your account is not active, please contact with admin";
                }else if($data->will_login!="Yes"){
                    $return_details['status'] = false;
                    $return_details['msg'] = "Your account is not allow to login, please contact with admin";
                }else if($data->designation!="Waiter"){
                    $return_details['status'] = false;
                    $return_details['msg'] = "Only Admin or Waiter user can login, check documentation for detail";
                }else if($data->designation!="Waiter"){

                    $this->db->select("*");
                    $this->db->from("tbl_companies");
                    $this->db->where("id", $data->company_id);
                    $company_info= $this->db->get()->row();
                    if($company_info->is_active!=1){
                        $return_details['status'] = false;
                        $return_details['msg'] = "Your assigned company is not active, please contact with admin";
                    }
                }else if($data->designation=="Waiter" && $data->will_login=="Yes" && $data->active_status=="Active"){
                    $return_details['status'] = true;
                    $return_details['data'] = $data;
                    $return_details['msg'] = "Successfully login";
                }
            }
        }else{
            $return_details['status'] = false;
            $return_details['msg'] = "Username/Password wrong!";
        }
        return $return_details;
    }
}

