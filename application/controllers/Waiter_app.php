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
  # This is Waiter_app Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waiter_app extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Common_model');
        $this->load->model('Sale_model');
        $this->load->model('Kitchen_model');
        $this->load->model('Bar_model');
        $this->load->model('Waiter_model');
        $this->load->model('Master_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();
    }
    /**
     * get new orders
     * @access public
     * @return object
     * @param no
     */
    public function get_new_orders($outlet_id){
        $data1 = $this->Sale_model->getNewOrders($outlet_id);
        $i = 0;
        for($i;$i<count($data1);$i++){
            $data1[$i]->total_kitchen_type_items = $this->Sale_model->get_total_kitchen_type_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_done_items = $this->Sale_model->get_total_kitchen_type_done_items($data1[$i]->sale_id);
            $data1[$i]->total_kitchen_type_started_cooking_items = $this->Sale_model->get_total_kitchen_type_started_cooking_items($data1[$i]->sale_id);
            $data1[$i]->tables_booked = $this->Sale_model->get_all_tables_of_a_sale_items($data1[$i]->sale_id);

            $to_time = strtotime(date('Y-m-d H:i:s'));
            $from_time = strtotime($data1[$i]->date_time);
            $minutes = floor(abs($to_time - $from_time) / 60);
            $seconds = abs($to_time - $from_time) % 60;

            $data1[$i]->minute_difference = str_pad(floor($minutes), 2, "0", STR_PAD_LEFT);
            $data1[$i]->second_difference = str_pad(floor($seconds), 2, "0", STR_PAD_LEFT);
        }
        return $data1;
    }
    /**
     * get Tables Details
     * @access public
     * @return object
     * @param string
     */
    public function getTablesDetails($tables){
        foreach($tables as $table){
            $table->orders_table = $this->Sale_model->getOrdersOfTableByTableId($table->id);
            foreach($table->orders_table as $order_table){

                $to_time = strtotime(date('Y-m-d H:i:s'));
                $from_time = strtotime($order_table->booking_time);
                $minutes = floor(abs($to_time - $from_time) / 60);
                $seconds = abs($to_time - $from_time) % 60;

                $order_table->booked_in_minute = $minutes;
            }
        }
        return $tables;
    }
    /**
     * get new notification
     * @access public
     * @return object
     * @param no
     */
    public function get_new_notification($outlet_id)
    {
        $notifications = $this->Sale_model->getNotificationByOutletId($outlet_id);
        return $notifications;
    }
    /**
     * POS screen
     * @access public
     * @return void
     * @param int
     */
    public function POS($encrypted_id = "") {
        $company_id = isset($_GET['company_id']) && $_GET['company_id']?$_GET['company_id']:1;
        $outlet_id = isset($_GET['outlet_id']) && $_GET['outlet_id']?$_GET['outlet_id']:'';
        $user_id = isset($_GET['user_id']) && $_GET['user_id']?$_GET['user_id']:'';

        $data = array();
        $tables = $this->Sale_model->getTablesByOutletId($outlet_id);
        $data['tables'] = $this->getTablesDetails($tables);
        $data['categories'] = $this->Sale_model->getFoodMenuCategories($company_id, 'tbl_food_menu_categories');
        $data['customers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_customers');
        $data['food_menus'] = $this->Sale_model->getAllFoodMenus($outlet_id);
        $data['menu_categories'] = $this->Sale_model->getAllMenuCategories();
        $data['menu_modifiers'] = $this->Sale_model->getAllMenuModifiers();
        $data['waiters'] = $this->Sale_model->getWaitersForThisCompany($company_id,'tbl_users');
        $data['new_orders'] = $this->get_new_orders($outlet_id);
        $data['payment_methods'] = $this->Sale_model->getAllPaymentMethods();
        $data['notifications'] = $this->get_new_notification($outlet_id);
        $data['user_id_wp'] = $user_id;
        $data['waiter_app_status'] = "Yes";
        $this->load->view('sale/POS/main_screen', $data);
    }
    public function add_sale_by_ajax(){
        $order_details = json_decode(json_decode($this->input->post('order')));
        //this id will be 0 when there is new order, but will be greater then 0 when there is modification
        //on previous order
        $sale_id = $this->input->post('sale_id');
        $user_id = $this->input->post('user_id');
        $outlet_id = $this->input->post('outlet_id');

        $data = array();
        $data['customer_id'] = trim($order_details->customer_id);
        $data['total_items'] = trim($order_details->total_items_in_cart);
        $data['sub_total'] = trim($order_details->sub_total);
        $data['charge_type'] = trim($order_details->charge_type);
        $data['vat'] = trim($order_details->total_vat);
        $data['total_payable'] = trim($order_details->total_payable);
        $data['total_item_discount_amount'] = trim($order_details->total_item_discount_amount);
        $data['sub_total_with_discount'] = trim($order_details->sub_total_with_discount);
        $data['sub_total_discount_amount'] = trim($order_details->sub_total_discount_amount);
        $data['total_discount_amount'] = trim($order_details->total_discount_amount);
        $data['delivery_charge'] = trim($order_details->delivery_charge);
        $data['sub_total_discount_value'] = trim($order_details->sub_total_discount_value);
        $data['sub_total_discount_type'] = trim($order_details->sub_total_discount_type);
        $data['user_id'] = $user_id;
        $data['waiter_id'] = trim($order_details->waiter_id);
        $data['outlet_id'] = $outlet_id;
        $data['sale_date'] = trim(isset($order_details->open_invoice_date_hidden) && $order_details->open_invoice_date_hidden?$order_details->open_invoice_date_hidden:date('Y-m-d'));
        $data['date_time'] = date('Y-m-d H:i:s');
        $data['order_time'] = date("H:i:s");
        $data['order_status'] = trim($order_details->order_status);
        $today_ = date('Y-m-d');
        if($today_<$data['sale_date']){
            //1 is runny sale, 2 is future sales, 3 is future status null
            $data['future_sale_status'] = 2;
        }

        $total_tax = 0;
        if(isset($order_details->sale_vat_objects) && $order_details->sale_vat_objects){
            foreach ($order_details->sale_vat_objects as $keys=>$val){
                $total_tax+=$val->tax_field_amount;
            }
        }
        $data['vat'] = $total_tax;
        $data['sale_vat_objects'] = json_encode($order_details->sale_vat_objects);
        $data['order_type'] = trim($order_details->order_type);
        $this->db->trans_begin();
        if($sale_id>0){
            $data['modified'] = 'Yes';
            $this->db->where('id', $sale_id);
            $this->db->update('tbl_sales', $data);
            //this section sends notification to bar/kitchen panel if there is any modification
            $single_table_information = $this->get_all_information_of_a_sale($sale_id);
            $order_number = '';
            if($single_table_information->order_type==1){
                $order_number = 'A '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==2){
                $order_number = 'B '.$single_table_information->sale_no;
            }else if($single_table_information->order_type==3){
                $order_number = 'C '.$single_table_information->sale_no;
            }
            $notification_message = 'Order:'.$order_number.' has been modified';
            $bar_kitchen_notification_data = array();
            $bar_kitchen_notification_data['notification'] = $notification_message;
            $bar_kitchen_notification_data['outlet_id'] = $outlet_id;
            $query = $this->db->insert('tbl_notification_bar_kitchen_panel', $bar_kitchen_notification_data);
            //end of send notification process
            $this->db->delete('tbl_sales_details', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sales_details_modifiers', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions', array('sale_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions_of_menus', array('sales_id' => $sale_id));
            $this->db->delete('tbl_sale_consumptions_of_modifiers_of_menus', array('sales_id' => $sale_id));

            $sales_id = $sale_id;
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
        }else{
            $query = $this->db->insert('tbl_sales', $data);
            $sales_id = $this->db->insert_id();
            $sale_no = str_pad($sales_id, 6, '0', STR_PAD_LEFT);
            $sale_no_update_array = array('sale_no' => $sale_no);
            $this->db->where('id', $sales_id);
            $this->db->update('tbl_sales', $sale_no_update_array);
        }
        foreach($order_details->orders_table as $single_order_table){
            $order_table_info = array();
            $order_table_info['persons'] = $single_order_table->persons;
            $order_table_info['booking_time'] = date('Y-m-d H:i:s');
            $order_table_info['sale_id'] = $sales_id;
            $order_table_info['sale_no'] = $sale_no;
            $order_table_info['outlet_id'] = $outlet_id;
            $order_table_info['table_id'] = $single_order_table->table_id;
            $query = $this->db->insert('tbl_orders_table',$order_table_info);
        }
        $data_sale_consumptions = array();
        $data_sale_consumptions['sale_id'] = $sales_id;
        $data_sale_consumptions['user_id'] = $user_id;
        $data_sale_consumptions['outlet_id'] = $outlet_id;
        $data_sale_consumptions['del_status'] = 'Live';
        $query = $this->db->insert('tbl_sale_consumptions',$data_sale_consumptions);
        $sale_consumption_id = $this->db->insert_id();

        if($sales_id>0 && count($order_details->items)>0){
            foreach($order_details->items as $item){
                $tmp_var_111 = isset($item->p_qty) && $item->p_qty && $item->p_qty!='undefined'?$item->p_qty:0;
                $tmp = $item->item_quantity-$tmp_var_111;
                $tmp_var = 0;
                if($tmp>0){
                    $tmp_var = $tmp;
                }
                $item_date = array();
                $item_data['food_menu_id'] = $item->item_id;
                $item_data['menu_name'] = $item->item_name;
                $item_data['qty'] = $item->item_quantity;
                $item_data['tmp_qty'] = $tmp_var;
                $item_data['menu_price_without_discount'] = $item->item_price_without_discount;
                $item_data['menu_price_with_discount'] = $item->item_price_with_discount;
                $item_data['menu_unit_price'] = $item->item_unit_price;
                $item_data['menu_taxes'] = json_encode($item->item_vat);
                $item_data['menu_discount_value'] = $item->item_discount;
                $item_data['discount_type'] = $item->discount_type;
                $item_data['menu_note'] = $item->item_note;
                $item_data['discount_amount'] = $item->item_discount_amount;
                $item_data['item_type'] = ($this->Sale_model->getItemType($item->item_id)->item_type=="Bar No")?"Kitchen Item":"Bar Item";
                $item_data['cooking_status'] = ($item->item_cooking_status=="")?NULL:$item->item_cooking_status;
                $item_data['cooking_start_time'] = ($item->item_cooking_start_time=="" || $item->item_cooking_start_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_start_time));
                $item_data['cooking_done_time'] = ($item->item_cooking_done_time=="" || $item->item_cooking_done_time=="0000-00-00 00:00:00")?'0000-00-00 00:00:00':date('Y-m-d H:i:s',strtotime($item->item_cooking_done_time));
                $item_data['previous_id'] = ($item->item_previous_id=="")?0:$item->item_previous_id;
                $item_data['sales_id'] = $sales_id;
                $item_data['user_id'] = $user_id;
                $item_data['outlet_id'] = $outlet_id;
                $item_data['del_status'] = 'Live';
                $query = $this->db->insert('tbl_sales_details', $item_data);
                $sales_details_id = $this->db->insert_id();

                if($item->item_previous_id==""){
                    $previous_id_update_array = array('previous_id' => $sales_details_id);
                    $this->db->where('id', $sales_details_id);
                    $this->db->update('tbl_sales_details', $previous_id_update_array);
                }


                $food_menu_ingredients = $this->db->query("SELECT * FROM tbl_food_menus_ingredients WHERE food_menu_id=$item->item_id")->result();

                foreach($food_menu_ingredients as $single_ingredient){

                    $data_sale_consumptions_detail = array();
                    $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                    $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption;
                    $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                    $data_sale_consumptions_detail['sales_id'] = $sales_id;
                    $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                    $data_sale_consumptions_detail['user_id'] = $user_id;
                    $data_sale_consumptions_detail['outlet_id'] = $outlet_id;
                    $data_sale_consumptions_detail['del_status'] = 'Live';
                    $query = $this->db->insert('tbl_sale_consumptions_of_menus',$data_sale_consumptions_detail);
                }

                $modifier_id_array = ($item->modifiers_id!="")?explode(",",$item->modifiers_id):null;
                $modifier_price_array = ($item->modifiers_price!="")?explode(",",$item->modifiers_price):null;

                if(!empty($modifier_id_array)>0){
                    $i = 0;
                    foreach($modifier_id_array as $single_modifier_id){
                        $modifier_data = array();
                        $modifier_data['modifier_id'] =$single_modifier_id;
                        $modifier_data['modifier_price'] = $modifier_price_array[$i];
                        $modifier_data['food_menu_id'] = $item->item_id;
                        $modifier_data['sales_id'] = $sales_id;
                        $modifier_data['sales_details_id'] = $sales_details_id;
                        $modifier_data['user_id'] = $user_id;
                        $modifier_data['outlet_id'] = $outlet_id;
                        $modifier_data['customer_id'] =$order_details->customer_id;
                        $query = $this->db->insert('tbl_sales_details_modifiers', $modifier_data);

                        $modifier_ingredients = $this->db->query("SELECT * FROM tbl_modifier_ingredients WHERE modifier_id=$single_modifier_id")->result();

                        foreach($modifier_ingredients as $single_ingredient){
                            $data_sale_consumptions_detail = array();
                            $data_sale_consumptions_detail['ingredient_id'] = $single_ingredient->ingredient_id;
                            $data_sale_consumptions_detail['consumption'] = $item->item_quantity*$single_ingredient->consumption;
                            $data_sale_consumptions_detail['sale_consumption_id'] = $sale_consumption_id;
                            $data_sale_consumptions_detail['sales_id'] = $sales_id;
                            $data_sale_consumptions_detail['food_menu_id'] = $item->item_id;
                            $data_sale_consumptions_detail['user_id'] = $user_id;
                            $data_sale_consumptions_detail['outlet_id'] = $outlet_id;
                            $data_sale_consumptions_detail['del_status'] = 'Live';
                            $query = $this->db->insert('tbl_sale_consumptions_of_modifiers_of_menus',$data_sale_consumptions_detail);
                        }

                        $i++;
                    }
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            echo escape_output($sales_id);
            $this->db->trans_commit();
        }

    }
}
