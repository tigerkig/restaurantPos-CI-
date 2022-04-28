<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class IR_api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Waiter_api_model');
    }
    /**
     * get new notification
     * @access public
     * @return object
     * @param no
     */
    public function get_notifications_post()
    {
        $waiter_id = isset($_POST['waiter_id']) && $_POST['waiter_id']?$_POST['waiter_id']:'';
        $notifications = $this->Waiter_api_model->getNotificationByOutletId($waiter_id);
        foreach ($notifications as $key=>$value){
            $notifications[$key]->sale_no = returnSaleNo($value->sale_id);
        }
        $this->response($notifications);
    }
    /**
     * waiter login checker
     * @access public
     * @return object
     * @param no
     */
    public function waiter_login_check_post()
    {
        $email = isset($_POST['email']) && $_POST['email']?$_POST['email']:'';
        $password = isset($_POST['password']) && $_POST['password']?$_POST['password']:'';
        $user_information = $this->Waiter_api_model->getUserInformationWater($email, $password);
        $this->response($user_information);
    }
    /**
     * collect notification
     * @access public
     * @return object
     * @param no
     */
    public function collect_notification_post()
    {
        $notification_id = isset($_POST['notification_id']) && $_POST['notification_id']?$_POST['notification_id']:'';
        $this->db->delete('tbl_notifications', array('id' => $notification_id));
        $this->response("Success");
    }
    /**
     * waiter login checker
     * @access public
     * @return object
     * @param no
     */
    public function remove_multiple_notification_post()
    {
        $notification_ids = isset($_POST['notification_ids']) && $_POST['notification_ids']?$_POST['notification_ids']:'';
        $notifications_array = explode(",",$notification_ids);
        foreach($notifications_array as $single_notification){
            $this->db->delete('tbl_notifications', array('id' => $single_notification));
        }
        $this->response("Success");
    }

    /**
     * waiter login checker
     * @access public
     * @return object
     * @param no
     */
    public function push_notification_status_change_post()
    {
        $notification_id = isset($_POST['notification_id']) && $_POST['notification_id']?$_POST['notification_id']:'';
        if($notification_id){
            $this->db->set('push_status', "2");
            $this->db->where('id', $notification_id);
            $this->db->update("tbl_notifications");
        }
        $this->response("Success");
    }
    /**
     * waiter login checker
     * @access public
     * @return object
     * @param no
     */
    public function get_outlet_name_post()
    {
        $outlet_id = isset($_POST['outlet_id']) && $_POST['outlet_id']?$_POST['outlet_id']:'';
        $outlet = $this->Waiter_api_model->get_outlet_name($outlet_id);
        $this->response($outlet);
    }
}

