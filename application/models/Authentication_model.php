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
  # This is Authentication_model Model
  ###########################################################
 */

class Authentication_model extends CI_Model {
    public function __construct(){
        parent::__construct(); 
        if ($this->session->has_userdata('language')) {
            $language = $this->session->userdata('language');
        }else{
            $language = 'english';
        }  
        $this->lang->load("$language", "$language");
        if($language=='spanish'){
            $this->config->set_item('language', 'spanish');
        }
    }
    /**
     * get White Label
     * @access public
     * @return object
     * @param int
     */
    public function getWhiteLabel($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_setting");
        $this->db->where("company_id", $company_id);
        return $this->db->get()->row();
    }
    /**
     * get User Information
     * @access public
     * @return object
     * @param string
     * @param string
     */
    public function getUserInformation($email_address, $password) {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("email_address", $email_address);
        $this->db->where("password", $password);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->row();
    }

    /**
     * update User Info
     * @access public
     * @return object
     * @param int
     * @param int
     */
    public function updateUserInfo($company_id, $user_id) {
        $this->db->set('company_id', $company_id);
        $this->db->where('id', $user_id);
        $this->db->update('tbl_users');
    }
    /**
     * save Company Info
     * @access public
     * @return int
     * @param array
     */
    public function saveCompanyInfo($company_info) {
        $this->db->insert('tbl_companies', $company_info);
        return $this->db->insert_id();
    }
    /**
     * get Account By Mobile No
     * @access public
     * @return object
     * @param string
     */
    public function getAccountByMobileNo($email_address) {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("email_address", $email_address);
        $this->db->where("del_status", 'Live');
        return $this->db->get()->row();
    }
    /**
     * get Company Information
     * @access public
     * @return object
     * @param int
     */
    public function getCompanyInformation($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_companies");
        $this->db->where("id", $company_id);
        return $this->db->get()->row();
    }
    /**
     * save User Info
     * @access public
     * @return int
     * @param array
     */
    public function saveUserInfo($user_info) {
        $this->db->insert('tbl_users', $user_info);
        return $this->db->insert_id();
    }
    /**
     * password Check
     * @access public
     * @return object
     * @param string
     * @param int
     */
    public function passwordCheck($old_password, $user_id) {
        $row = $this->db->query("SELECT * FROM tbl_users WHERE id=$user_id AND password='$old_password'")->row();
        return $row;
    }
    /**
     * update Password
     * @access public
     * @return void
     * @param string
     * @param int
     */
    public function updatePassword($new_password, $user_id) {
        $this->db->set('password', $new_password);
        $this->db->where('id', $user_id);
        $this->db->update('tbl_users');
    }
    /**
     * get Menu Access Information
     * @access public
     * @return object
     * @param int
     */
    public function getMenuAccessInformation($user_id='') {
        if($user_id!='') {
            $result = $this->db->query("SELECT tbl_admin_user_menus.controller_name as controller_name
          FROM tbl_user_menu_access
          JOIN tbl_admin_user_menus ON tbl_user_menu_access.menu_id =  tbl_admin_user_menus.id
          WHERE tbl_user_menu_access.user_id=$user_id
          ")->result();
        }else{
            $result = $this->db->query("SELECT *
          FROM tbl_admin_user_menus
          ")->result();
        }

        return $result;
    }
    /**
     * save User Access
     * @access public
     * @return void
     * @param int
     */
    public function saveUserAccess($user_id) {
        $this->load->model('Common_model');
        $all_menus = $this->Common_model->getAllByTable("tbl_admin_user_menus");

        foreach ($all_menus as $value) {
            $data = array();
            $data['menu_id'] = $value->id;
            $data['user_id'] = $user_id;
            $this->db->insert('tbl_user_menu_access', $data);
        }
    }
    /**
     * get Setting Information
     * @access public
     * @return object
     * @param no
     */
    public function getSettingInformation() {
        $company_info = getCompanyInfo();
        $getWhiteLabel = json_decode($company_info->white_label);
        return $getWhiteLabel;
    }
    /**
     * get SMS Information
     * @access public
     * @return object
     * @param int
     */
    public function getSMSInformation($company_id) {
        $this->db->select("*");
        $this->db->from("tbl_companies");
        $row = $this->db->get()->row();
        return json_decode($row->sms_details);
    }
    /**
     * get Profile Information
     * @access public
     * @return object
     * @param no
     */
    public function getProfileInformation() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where("id", $user_id);
        return $this->db->get()->row();
    }

}

