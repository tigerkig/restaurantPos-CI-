<?php
//get company information
$getCompanyInfo = getCompanyInfo();

$notification_number = 0;
if(count($notifications)>0){
    $notification_number = count($notifications);
}

/*******************************************************************************************************************
 * This secion is to construct menu list****************************************************************************
 *******************************************************************************************************************
 */
$previous_category = 0;

$i = 0;
$menu_to_show = "";
$javascript_obects = "";


function cmp($a, $b)
{
    return strcmp($a->category_id, $b->category_id);
}

if(isset($food_menus) && $food_menus):
    $total_menus = count($food_menus);
    usort($food_menus, "cmp");
    $previous_price = (array)json_decode($outlet_information->food_menu_prices);
foreach($food_menus as $single_menus){
    $sale_price = isset($previous_price["tmp".$single_menus->id]) && $previous_price["tmp".$single_menus->id]?$previous_price["tmp".$single_menus->id]:$single_menus->sale_price;
    //checks that whether its new category or not    
    $is_new_category = false;
    //get current food category
    $current_category = $single_menus->category_id;
    $veg_status1 = "no";
    if($single_menus->veg_item=="Veg Yes"){
        $veg_status1 = "yes";
    }
    $beverage_status = "no";
    if($single_menus->beverage_item=="Bev Yes"){
        $beverage_status = "yes";
    }
    //if it the first time of loop then default previous category is 0
    //if it's 0 then set current category id to $previous category and set first category div
    if($previous_category == 0){
        $previous_category = $current_category;    
        $menu_to_show .= '<div id="category_'.$single_menus->category_id.'" class="specific_category_items_holder">';
    }
    //if previous category and current category is not equal. it means it's a new category 
    if($previous_category!=$current_category){
        
        $previous_category = $current_category;
        $is_new_category = true;
    }

    //if category is new and total menus are not finish yet then set exit to previous category and create new category
    //div
    if($is_new_category==true && $total_menus!=$i){
        $menu_to_show .= '</div>';
        $menu_to_show .= '<div id="category_'.$single_menus->category_id.'" class="specific_category_items_holder">';
    }
    $img_size = "images/".$single_menus->photo;
    if(file_exists($img_size) && $single_menus->photo!=""){
        $image_path = base_url().'images/'.$single_menus->photo;
    }else{
        $image_path = base_url().'images/image_thumb.png';
    }

    //construct new single item content
    $menu_to_show .= '<div class="single_item animate__animated animate__flipInX"  id="item_'.$single_menus->id.'">';
    $menu_to_show .= '<p class="item_price">'.lang('price').': <span id="price_'.$single_menus->id.'">'.getAmtP($sale_price).'</span></p>';
    $menu_to_show .= '<img src="'.$image_path.'" alt="" width="142">';
    $menu_to_show .= '<p class="item_name" data-tippy-content="'.$single_menus->name.' ('.$single_menus->code.'">'.$single_menus->name.' ('.$single_menus->code.')</p>';
    $menu_to_show .= '</div>';
    //if its the last content and there is no more category then set exit to last category
    if($is_new_category==false && $total_menus==$i){
        $menu_to_show .= '</div>';
    }

    //checks and hold the status of veg item
    if($single_menus->veg_item=='Veg Yes'){
        $veg_status = "VEG";
    }else{
        $veg_status = "";
    }

    //checks and hold the status of beverage item
    if($single_menus->beverage_item=='Beverage Yes'){
        $soft_status = "BEV";
    }else{
        $soft_status = "";
    }

    //checks and hold the status of bar item
    if($single_menus->bar_item=='Bar Yes'){
        $bar_status = "BAR";
    }else{
        $bar_status = "";
    }
    //get modifiers if menu id match with menu modifiers table
    $modifiers = '';
    $j=1;
    foreach($menu_modifiers as $single_menu_modifier){
        if($single_menu_modifier->food_menu_id==$single_menus->id){
            if($j==count($menu_modifiers)){
                $modifiers .="{menu_modifier_id:'".$single_menu_modifier->modifier_id."',menu_modifier_name:'".$single_menu_modifier->name."',tax_information:'".$single_menus->tax_information."',menu_modifier_price:'".getAmtP($single_menu_modifier->price)."'}";
            }else{
                $modifiers .="{menu_modifier_id:'".$single_menu_modifier->modifier_id."',menu_modifier_name:'".$single_menu_modifier->name."',tax_information:'".$single_menus->tax_information."',menu_modifier_price:'".getAmtP($single_menu_modifier->price)."'},";
            }
            
        }
        $j++;
    }
    //this portion construct javascript objects, it is used to search item from search input
    if($total_menus==$i){
        $javascript_obects .= "{item_id:'".$single_menus->id."',item_code:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->code)))."',category_name:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->category_name)))."',item_name:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->name)))."',price:'".getAmtP($sale_price)."',image:'".$image_path."',tax_information:'".$single_menus->tax_information."',vat_percentage:'0',veg_item:'".$veg_status."',beverage_item:'".$soft_status."',bar_item:'".$bar_status."',sold_for:'".$single_menus->item_sold."',veg_item_status:'".$veg_status1."',beverage_item_status:'".$beverage_status."',modifiers:[".$modifiers."]}";
    }else{
        $javascript_obects .= "{item_id:'".$single_menus->id."',item_code:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->code)))."',category_name:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->category_name)))."',item_name:'".(str_replace("'", ' ', str_replace('"', ' ', $single_menus->name)))."',price:'".getAmtP($sale_price)."',image:'".$image_path."',tax_information:'".$single_menus->tax_information."',vat_percentage:'0',veg_item:'".$veg_status."',beverage_item:'".$soft_status."',bar_item:'".$bar_status."',sold_for:'".$single_menus->item_sold."',veg_item_status:'".$veg_status1."',beverage_item_status:'".$beverage_status."',modifiers:[".$modifiers."]},";
    }

    //increasing always with the number of loop to check the number of menus
    $i++;    

    
    
}
endif;

$j = 1;
$javascript_obects_modifier = "";
foreach($menu_modifiers as $single_menu_modifier){
    if($j==count($menu_modifiers)){
        $javascript_obects_modifier .="{menu_modifier_id:'".$single_menu_modifier->modifier_id."',menu_modifier_name:'".$single_menu_modifier->name."',tax_information:'".$single_menus->tax_information."',menu_modifier_price:'".getAmtP($single_menu_modifier->price)."'}";
    }else{
        $javascript_obects_modifier .="{menu_modifier_id:'".$single_menu_modifier->modifier_id."',menu_modifier_name:'".$single_menu_modifier->name."',tax_information:'".$single_menus->tax_information."',menu_modifier_price:'".getAmtP($single_menu_modifier->price)."'},";
    }
    $j++;
}
/*******************************************************************************************************************
 * End of This secion is to construct menu list*********************************************************************
 *******************************************************************************************************************
 */

/*******************************************************************************************************************
 * This secion is to construct category ****************************************************************************
 *******************************************************************************************************************
 */
$i = 1;
$cateogry_slide_to_show = '';
foreach($menu_categories as $single_category){
    
    if($i = 1){
        $cateogry_slide_to_show .= '<li><a href="#" class="category_button" id="button_category_'.$single_category->id.'">'.$single_category->category_name.'</a></li>';
                               
    }else{
        $cateogry_slide_to_show .= '<li><a href="#" class="category_button" id="button_category_'.$single_category->id.'">'.$single_category->category_name.'</a></li>';
    }
    
}

/*******************************************************************************************************************
 * End of This secion is to construct category ****************************************************************************
 *******************************************************************************************************************
 */

/********************************************************************************************************************
 * This section is to construct options of customer select input*****************************************************
 * ******************************************************************************************************************
 */
$customers_option = '';
$total_customers = count($customers);
$i = 1;
$customer_objects = '';
$check_walk_in_customer = 1;
foreach ($customers as $customer){
    $selected = "";
    $default_customer = $getCompanyInfo->default_customer;
    if($customer->id==$default_customer){
        $selected = "selected";
    }

    if($customer->id==1){
        $check_walk_in_customer++;
    }
    if($customer->name=='Walk-in Customer'){
        $customers_option = '<option '.$selected.' value="'.$customer->id.'" selected>'.$customer->name.' '.$customer->phone.'</option>'.$customers_option;
    }else{
        $customers_option .= '<option '.$selected.' value="'.$customer->id.'" '.$selected.'>'.$customer->name.' '.$customer->phone.'</option>';
    }

    if($total_customers==$i){
        $customer_objects .= "{customer_id:'".$customer->id."',customer_name:'".(str_replace("'", ' ', str_replace('"', ' ', $customer->name)))."',customer_address:'".(str_replace("'", ' ', str_replace('"', ' ', $customer->address)))."',gst_number:'".$customer->gst_number."'}";
    }else{
        $customer_objects .= "{customer_id:'".$customer->id."',customer_name:'".(str_replace("'", ' ', str_replace('"', ' ', $customer->name)))."',customer_address:'".(str_replace("'", ' ', str_replace('"', ' ', $customer->address)))."',gst_number:'".$customer->gst_number."'},";
    }

    $i++;
}

if($check_walk_in_customer==1 && $customers_option==""){
    $customers_option = '<option selected value="1">Walk-in Customer</option>';
    $customer_objects .= "{customer_id:'1',customer_name:'".(str_replace("'", ' ', str_replace('"', ' ', 'Walk-in Customer')))."',customer_address:'".(str_replace("'", ' ', str_replace('"', ' ', '')))."',gst_number:''}";
}else if($check_walk_in_customer==1 && $customers_option){
    $customers_option = '<option selected value="1">Walk-in Customer</option>';
    $customer_objects .= ",{customer_id:'1',customer_name:'".(str_replace("'", ' ', str_replace('"', ' ', 'Walk-in Customer')))."',customer_address:'".(str_replace("'", ' ', str_replace('"', ' ', '')))."',gst_number:''}";
}

/********************************************************************************************************************
 * This section is to construct options of customer select input*****************************************************
 * ******************************************************************************************************************
 */

/********************************************************************************************************************
 * This section is to construct options of customer select input*****************************************************
 * ******************************************************************************************************************
 */
$waiters_option = '';
$default_waiter_id = 0;
foreach ($waiters as $waiter){
    $selected = "";
    $role = $this->session->userdata('role');
    $user_id = $this->session->userdata('user_id');
    $default_waiter = $getCompanyInfo->default_waiter;
    if($waiter->id==$default_waiter){
        $selected = "selected";
        $default_waiter_id = $waiter->id;
    }else{
        if(isset($role) && $role!="Admin"){
            if($waiter->id==$user_id){
                $selected = "selected";
                $default_waiter_id = $user_id;
            }
        }
    }
    if($waiter->full_name=='Default Waiter'){
        $waiters_option = '<option '.$selected.' value="'.$waiter->id.'">'.$waiter->full_name.'</option>'.$waiters_option;
    }else{
        $waiters_option .= '<option '.$selected.' value="'.$waiter->id.'">'.$waiter->full_name.'</option>';
    }
    
}

/********************************************************************************************************************
 * This section is to construct options of customer select input*****************************************************
 * ******************************************************************************************************************
 */

/********************************************************************************************************************
 * This section is to construct table modal's content****************************************************************
 ********************************************************************************************************************
 */
$tables_modal = '';
foreach($tables as $table){
    $tables_modal .= '<div class="floatleft fix single_order_table" id="single_table_info_holder_'.$table->id.'">';
    $tables_modal .= '<p class="table_name" class="ir_font_bold"><span id="sit_name_'.$table->id.'">'.$table->name.'<span></p>';
    $tables_modal .= '<p class="table_sit_capacity">Sit Capacity: <span id="sit_capacity_number_'.$table->id.'">'.$table->sit_capacity.'<span></p>';
    $tables_modal .= '<p class="table_available">Available: <span id="sit_available_number_'.$table->id.'">'.$table->sit_capacity.'</span></p>';
    $tables_modal .= '<img class="table_image" src="'.base_url().'images/table_icon2.png" alt="">';
    $tables_modal .= '<p class="running_order_in_table">Running orders in table</p>';
    $tables_modal .= '<div class="single_table_order_details_holder fix" id="single_table_order_details_holder_'.$table->id.'">';
    $tables_modal .= '<div class="top fix" id="single_table_order_details_top_'.$table->id.'">';
    $tables_modal .= '<div class="single_row header">';
    $tables_modal .= '<div class="floatleft fix column first_column">Order</div>';
    $tables_modal .= '<div class="floatleft fix column second_column">Time</div>';
    $tables_modal .= '<div class="floatleft fix column third_column">Person</div>';
    $tables_modal .= '<div class="floatleft fix column forth_column">Del</div>';
    $tables_modal .= '</div>';
    if(count($table->orders_table)>0){
        foreach($table->orders_table as $single_order_table){
            $tables_modal .= '<div class="single_row fix">';
            $tables_modal .= '<div class="floatleft fix column first_column">'.$single_order_table->sale_id.'</div>';
            $tables_modal .= '<div class="floatleft fix column second_column">'.$single_order_table->booking_time.'</div>';
            $tables_modal .= '<div class="floatleft fix column third_column">'.$single_order_table->persons.'</div>';
            $tables_modal .= '<div class="floatleft fix column forth_column"><i class="fas fa-trash-alt remove_table_order" id="remove_table_order_'.$single_order_table->id.'"></i></div>';
            $tables_modal .= '</div>';
        }

    }

    $tables_modal .= '</div>';
    $tables_modal .= '<div class="bottom fix" id="single_table_order_details_bottom_'.$table->id.'">';
    $tables_modal .= '<input type="text" name="" placeholder="Order" class="floatleft bottom_order"  id="single_table_order_details_bottom_order_'.$table->id.'" readonly>';
    $tables_modal .= '<input type="text" name="" placeholder="Person" class="floatleft bottom_person" id="single_table_order_details_bottom_person_'.$table->id.'">';
    $tables_modal .= '<button class="floatleft bottom_add" id="single_table_order_details_bottom_add_'.$table->id.'">Add</button>';
    $tables_modal .= '</div>';
    $tables_modal .= '</div>';
    $tables_modal .= '</div>';
}
/********************************************************************************************************************
 * End This section is to construct table modal's content****************************************************************
 ********************************************************************************************************************
 **/
$order_list_left = '';
$i = 1;
foreach($new_orders as $single_new_order){
    $width = 100;
    $total_kitchen_type_items = $single_new_order->total_kitchen_type_items;
    $total_kitchen_type_started_cooking_items = $single_new_order->total_kitchen_type_started_cooking_items;
    $total_kitchen_type_done_items = $single_new_order->total_kitchen_type_done_items;
    if($total_kitchen_type_items==0){
        $total_kitchen_type_items = 1;  
    }
    $splitted_width = round($width/$total_kitchen_type_items,2);
    $percentage_for_started_cooking = round($splitted_width*$total_kitchen_type_started_cooking_items,2);
    $percentage_for_done_cooking = round($splitted_width*$total_kitchen_type_done_items,2);
    if($i==1){
        $order_list_left .= '<div data-started-cooking="'.$total_kitchen_type_started_cooking_items.'" data-done-cooking="'.$total_kitchen_type_done_items.'" class="txt_14 single_order fix new_order_'.$single_new_order->sales_id.'" data-selected="unselected" id="order_'.$single_new_order->sales_id.'">';
    }else{
        $order_list_left .= '<div data-started-cooking="'.$total_kitchen_type_started_cooking_items.'" data-done-cooking="'.$total_kitchen_type_done_items.'" class="single_order fix new_order_'.$single_new_order->sales_id.'" data-selected="unselected" id="order_'.$single_new_order->sales_id.'">';
    }
    $order_list_left .='<div class="inside_single_order_container">';
    $order_list_left .='<div class="single_order_content_holder_inside fix">';
    $order_name = '';
    if($single_new_order->order_type=='1'){
        $order_name = 'A '.$single_new_order->sale_no;
    }else if($single_new_order->order_type=='2'){
        $order_name = 'B '.$single_new_order->sale_no;
    }else if($single_new_order->order_type=='3'){
        $order_name = 'C '.$single_new_order->sale_no;
    }
    
    $minutes = $single_new_order->minute_difference;
    $seconds = $single_new_order->second_difference;
    $tables_booked = '';
    if(count($single_new_order->tables_booked)>0){
        $w = 1;
        foreach($single_new_order->tables_booked as $single_table_booked){
            if($w == count($single_new_order->tables_booked)){
                $tables_booked .= $single_table_booked->table_name;
            }else{
                $tables_booked .= $single_table_booked->table_name.', ';
            }
            $w++;
        }    
    }else{
        $tables_booked = 'None';
    }
    
    $order_list_left .= '<span id="open_orders_order_status_'.$single_new_order->sales_id.'" class="ir_display_none">'.$single_new_order->order_status.'</span><p><span class="running_order_customer_name">Cust: '.$single_new_order->customer_name.'</span> <span class="running_order_customer_phone ir_display_none">'.$single_new_order->phone.'</span> </p>  <i class="far fa-chevron-right running_order_right_arrow" id="running_order_right_arrow_'.$single_new_order->sales_id.'"></i>';
    $order_list_left .= '<p>'.lang('table').': <span class="running_order_table_name">'.$tables_booked.'</span></p>';
    $order_list_left .= '<p>'.lang('waiter').': <span class="running_order_waiter_name">'.$single_new_order->waiter_name.'</span></p>';
    $order_list_left .= '<p>Order: <span class="running_order_order_number">'.$order_name.'</span></p>';
    $order_list_left .= '</div>';
    $order_list_left .= '<div class="order_condition">';
    $order_list_left .= '<p class="order_on_processing">'.lang('started_cooking').': '.$total_kitchen_type_started_cooking_items.'/'.$total_kitchen_type_items.'</p>';
    $order_list_left .= '<p class="order_done">'.lang('done').': '.$total_kitchen_type_done_items.'/'.$total_kitchen_type_items.'</p>';
    $order_list_left .= '</div>';
    $order_list_left .= '<div class="order_condition">';
    $order_list_left .= '<p class="txt_16">'.lang('time_count').': <span id="order_minute_count_'.$single_new_order->sales_id.'">'.str_pad(round($minutes), 2, "0", STR_PAD_LEFT).'</span>:<span id="order_second_count_'.$single_new_order->sales_id.'">'.str_pad(round($seconds), 2, "0", STR_PAD_LEFT).'</span> M</p>';
    $order_list_left .= '</div>';
    $order_list_left .= '</div>';
    $order_list_left .= '</div>';
    $i++;
}
/************************************************************************************************************************
 * Construct new orders those are still on processing *******************************************************************
 * **********************************************************************************************************************
 */


/************************************************************************************************************************
 * Construct payment method drop down ***********************************************************************************
 * **********************************************************************************************************************
 */
$payment_method_options = '';

foreach ($payment_methods as $payment_method){
    $selected = "";
    $default_payment = $getCompanyInfo->default_payment;
    if($payment_method->id==$default_payment){
        $selected = "selected";
    }
    $payment_method_options .= '<option '.$selected.' value="'.$payment_method->id.'">'.$payment_method->name.'</option>';
}

/************************************************************************************************************************
 * End of Construct payment method drop down ***********************************************************************************
 * **********************************************************************************************************************
 */


/************************************************************************************************************************
 * Construct notification list ***********************************************************************************
 * **********************************************************************************************************************
 */
$notification_list_show = '';

foreach ($notifications as $single_notification){
    $notification_list_show .= '<div class="single_row_notification fix" id="single_notification_row_'.$single_notification->id.'">';
    $notification_list_show .= '<div class="fix single_notification_check_box">';
    $notification_list_show .= '<input class="single_notification_checkbox" type="checkbox" id="single_notification_'.$single_notification->id.'" value="'.$single_notification->id.'">';
    $notification_list_show .= '</div>';
    $notification_list_show .= '<div class="fix single_notification">'.$single_notification->notification.'</div>';
    $notification_list_show .= '<div class="fix single_serve_button">';
    $notification_list_show .= '<button class="single_serve_b" id="notification_serve_button_'.$single_notification->id.'">'.lang('serve_take_delivery').'</button>';
    $notification_list_show .= '</div>';
    $notification_list_show .= '</div>';
    
}

/************************************************************************************************************************
 * End of Construct notification list ***********************************************************************************
 * **********************************************************************************************************************
 */



?>

<?php
    $wl = getWhiteLabel(); 
    if($wl){
        if($wl->site_name){
            $site_name = $wl->site_name;
        }
        if($wl->footer){
            $footer = $wl->footer;
        }
        if($wl->system_logo){
            $system_logo = base_url()."images/".$wl->system_logo;
        }
    }
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_output($site_name); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/POS/css/stylev1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/POS/css/style2v1.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/POS/css/customModal.css">
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/lib/font_awesomeV5P/css/pro.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font_awesomeV5P/css/pro.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/plugins/iCheck/minimal/color-scheme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/jquery-ui.css">
    <!-- For Tooltips -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/lib/tippy/tippy.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/lib/tippy/scale.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/lib/tippy/theme_light.css">
    <!-- Customer Scrollbar js -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/lib/scrollbar/jquery.scrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/custom_css.css">
    <script src="<?php echo base_url()?>assets/POS/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url()?>assets/POS/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/lib/scrollbar/slimScrollbar.js"></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/css/custom_pos.css">
    <!--notification for waiter panel-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/notify/jquery.notifyBar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/style.css">

    <script type="text/javascript"
        src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/calculator.js"></script>

    <base data-base="<?php echo base_url(); ?>">
    </base>
    <base data-collect-tax="<?php echo escape_output($getCompanyInfo->collect_tax); ?>">
    </base>
    <base data-currency="">
    </base>
    <base data-role="<?php echo escape_output($this->session->userdata('role')); ?>">
    </base>
    <base data-collect-gst="<?php echo escape_output($getCompanyInfo->tax_is_gst); ?>">
    </base>
    <base data-gst-state-code="<?php echo escape_output($this->session->userdata('gst_state_code')); ?>">
    </base>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/POS/css/datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/POS/css/animate.min.css">
    <style>
    <?php $waiter_app_status=$this->session->userdata('is_waiter');
    $waiter_app_status=isset($waiter_app_status) && $waiter_app_status?$waiter_app_status:'';

    if($waiter_app_status=="Yes"): ?>.full-width-for-waiter {
        width: 100% !important;
    }

    .no-need-for-waiter {
        display: none !important;
    }

    <?php endif;
    ?>
    </style>
</head>

<body>
    <!--hidden fields for js usages-->
    <input type="hidden" id="waiter_app_status" value="<?php echo escape_output($waiter_app_status)?>">
    <input type="hidden" id="ur_role" value="<?php echo escape_output($this->session->userdata('role'))?>">
    <input type="hidden" id="ir_precision" value="<?php echo escape_output($this->session->userdata('precision'))?>">
    <input type="hidden" id="register_close" value="<?php echo lang('register_close'); ?>">
    <input type="hidden" id="warning" value="<?php echo lang('alert'); ?>">
    <input type="hidden" id="a_error" value="<?php echo lang('error'); ?>">
    <input type="hidden" id="ok" value="<?php echo lang('ok'); ?>">
    <input type="hidden" id="cancel" value="<?php echo lang('cancel'); ?>">
    <input type="hidden" id="please_select_order_to_proceed"
        value="<?php echo lang('please_select_order_to_proceed'); ?>">
    <input type="hidden" id="exceeciding_seat" value="<?php echo lang('exceeding_sit'); ?>">
    <input type="hidden" id="seat_greater_than_zero" value="<?php echo lang('seat_greater_than_zero'); ?>">
    <input type="hidden" id="are_you_sure_cancel_booking" value="<?php echo lang('are_you_sure_cancel_booking'); ?>">
    <input type="hidden" id="are_you_delete_notification" value="<?php echo lang('are_you_delete_notification'); ?>">
    <input type="hidden" id="no_notification_select" value="<?php echo lang('no_notification_select'); ?>">
    <input type="hidden" id="are_you_delete_all_hold_sale" value="<?php echo lang('are_you_delete_all_hold_sale'); ?>">
    <input type="hidden" id="no_hold" value="<?php echo lang('no_hold'); ?>">
    <input type="hidden" id="sure_delete_this_hold" value="<?php echo lang('sure_delete_this_hold'); ?>">
    <input type="hidden" id="please_select_hold_sale" value="<?php echo lang('please_select_hold_sale'); ?>">
    <input type="hidden" id="delete_only_for_admin" value="<?php echo lang('delete_only_for_admin'); ?>">
    <input type="hidden" id="this_item_is_under_cooking_please_contact_with_admin" value="<?php echo lang('this_item_is_under_cooking_please_contact_with_admin'); ?>">
    <input type="hidden" id="this_item_already_cooked_please_contact_with_admin" value="<?php echo lang('this_item_already_cooked_please_contact_with_admin'); ?>">
    <input type="hidden" id="sure_delete_this_order" value="<?php echo lang('sure_delete_this_order'); ?>">
    <input type="hidden" id="sure_remove_this_order" value="<?php echo lang('sure_remove_this_order'); ?>">
    <input type="hidden" id="sure_cancel_this_order" value="<?php echo lang('sure_cancel_this_order'); ?>">
    <input type="hidden" id="please_select_an_order" value="<?php echo lang('please_select_an_order'); ?>">
    <input type="hidden" id="cart_not_empty" value="<?php echo lang('cart_not_empty'); ?>">
    <input type="hidden" id="cart_not_empty_want_to_clear" value="<?php echo lang('cart_not_empty_want_to_clear'); ?>">
    <input type="hidden" id="progress_or_done_kitchen" value="<?php echo lang('progress_or_done_kitchen'); ?>">
    <input type="hidden" id="order_in_progress_or_done" value="<?php echo lang('order_in_progress_or_done'); ?>">
    <input type="hidden" id="close_order_without" value="<?php echo lang('close_order_without'); ?>">
    <input type="hidden" id="want_to_close_order" value="<?php echo lang('want_to_close_order'); ?>">
    <input type="hidden" id="please_select_open_order" value="<?php echo lang('please_select_open_order'); ?>">
    <input type="hidden" id="cart_empty" value="<?php echo lang('cart_empty'); ?>">
    <input type="hidden" id="select_a_customer" value="<?php echo lang('select_a_customer'); ?>">
    <input type="hidden" id="select_a_waiter" value="<?php echo lang('select_a_waiter'); ?>">
    <input type="hidden" id="delivery_not_possible_walk_in"
        value="<?php echo lang('delivery_not_possible_walk_in'); ?>">
    <input type="hidden" id="delivery_for_customer_must_address"
        value="<?php echo lang('delivery_for_customer_must_address'); ?>">
    <input type="hidden" id="select_dine_take_delivery" value="<?php echo lang('select_dine_take_delivery'); ?>">
    <input type="hidden" id="added_running_order" value="<?php echo lang('added_running_order'); ?>">
    <input type="hidden" id="txt_err_pos_1" value="<?php echo lang('txt_err_pos_1'); ?>">
    <input type="hidden" id="txt_err_pos_2" value="<?php echo lang('txt_err_pos_2'); ?>">
    <input type="hidden" id="txt_err_pos_3" value="<?php echo lang('txt_err_pos_3'); ?>">
    <input type="hidden" id="txt_err_pos_4" value="<?php echo lang('txt_err_pos_4'); ?>">
    <input type="hidden" id="txt_err_pos_5" value="<?php echo lang('txt_err_pos_5'); ?>">
    <input type="hidden" id="fullscreen_1" value="<?php echo lang('fullscreen_1'); ?>">
    <input type="hidden" id="fullscreen_2" value="<?php echo lang('fullscreen_2'); ?>">
    <input type="hidden" id="place_order" value="<?php echo lang('place_order'); ?>">
    <input type="hidden" id="update_order" value="<?php echo lang('update_order'); ?>">
    <input type="hidden" id="price_txt" value="<?php echo lang('price'); ?>">
    <input type="hidden" id="note_txt" value="<?php echo lang('note'); ?>">
    <input type="hidden" id="modifiers_txt" value="<?php echo lang('modifiers'); ?>">
    <input type="hidden" id="item_add_success" value="<?php echo lang('item_add_success'); ?>">
    <input type="hidden" id="default_customer_hidden" value="<?php echo escape_output($getCompanyInfo->default_customer); ?>">
    <input type="hidden" id="default_waiter_hidden" value="<?php echo escape_output($default_waiter_id); ?>">
    <input type="hidden" id="default_payment_hidden"
        value="<?php echo escape_output($getCompanyInfo->default_payment); ?>">
    <input type="hidden" id="selected_invoice_sale_customer" value="">
    <input type="hidden" id="saas_m_ch" value="<?=file_exists(APPPATH.'controllers/Service.php')?'yes':''?>">
    <input type="hidden" id="not_closed_yet" value="<?php echo lang('not_closed_yet'); ?>">
    <input type="hidden" id="opening_balance" value="<?php echo lang('opening_balance'); ?>">
    <input type="hidden" id="paid_amount" value="<?php echo lang('paid_amount'); ?>">
    <input type="hidden" id="customer_due_receive" value="<?php echo lang('customer_due_receive'); ?>">
    <input type="hidden" id="in_" value="<?php echo lang('in'); ?>">
    <input type="hidden" id="cash" value="<?php echo lang('cash'); ?>">
    <input type="hidden" id="paypal" value="<?php echo lang('paypal'); ?>">
    <input type="hidden" id="sale" value="<?php echo lang('sale'); ?>">
    <input type="hidden" id="card" value="<?php echo lang('card'); ?>">
    <div class="modalOverlay"></div>

    <input type="hidden" id="csrf_name_" value="<?php echo escape_output($this->security->get_csrf_token_name()); ?>">
    <input type="hidden" id="csrf_value_" value="<?php echo escape_output($this->security->get_csrf_hash()); ?>">
    <input type="hidden" name="print_status" id="" value="">
    <input type="hidden" name="last_invoice_id" class="last_invoice_id" id="last_invoice_id"
        value="<?php echo escape_output(getLastSaleId()) ?>">
    <input type="hidden" name="last_sale_id" class="last_sale_id" id="last_sale_id" value="">
    <input type="hidden" name="last_future_sale_id" class="last_future_sale_id" id="last_future_sale_id" value="">
    <input type="hidden" name="print_type" class="print_type" id="print_type" value="">
    <input type="hidden" name="print_type_invoice" class="print_type_invoice" id="print_type_invoice" value="<?php echo isset($getCompanyInfo->printing_invoice) && $getCompanyInfo->printing_invoice?$getCompanyInfo->printing_invoice:''; ?>">
    <input type="hidden" name="print_type_bill" class="print_type_bill" id="print_type_bill" value="<?php echo isset($getCompanyInfo->printing_bill) && $getCompanyInfo->printing_bill?$getCompanyInfo->printing_bill:''; ?>">
    <input type="hidden" name="print_type_kot" class="print_type_kot" id="print_type_kot" value="<?php echo isset($getCompanyInfo->printing_kot) && $getCompanyInfo->printing_kot?$getCompanyInfo->printing_kot:''; ?>">
    <input type="hidden" name="print_type_bot" class="print_type_bot" id="print_type_bot" value="<?php echo isset($getCompanyInfo->printing_bot) && $getCompanyInfo->printing_bot?$getCompanyInfo->printing_bot:''; ?>">
    <input type="hidden" name="service_type" class="service_type" id="service_type" value="<?php echo isset($getCompanyInfo->service_type) && $getCompanyInfo->service_type?$getCompanyInfo->service_type:'delivery'; ?>">
    <input type="hidden" name="service_amount" class="service_amount" id="service_amount" value="<?php echo isset($getCompanyInfo->service_amount) && $getCompanyInfo->service_amount?$getCompanyInfo->service_amount:'0'; ?>">
    <div class="preloader">
        <div class="loader">Loading...</div>
    </div>

    <span id="stop_refresh_for_search" class="ir_display_none"><?php echo lang('yes'); ?></span>
    <div class="wrapper">
        <div class="top_header_part">
            <div class="left_item">
                <div class="header_part_middle">
                    <ul class="icon__menu">
                        <li class="has__children">
                            <a href="#" class="header_menu_icon" data-tippy-content="<?php echo lang('main_menu'); ?>">
                                <i class="fal fa-user"></i>
                            </a>
                            <ul class="sub__menu" role="menu">
                                <li><a
                                        href="<?php echo base_url();?>Authentication/userProfile"><?php echo lang('my_profile'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo base_url();?>Authentication/changePassword"><?php echo lang('change_password'); ?></a>
                                </li>
                                <li>
                                    <a
                                        href="<?php echo base_url();?>Authentication/logOut"><?php echo lang('logout'); ?></a>
                                </li>
                            </ul>
                        </li>
                        <li class="has__children">
                            <a href="#" class="header_menu_icon" data-tippy-content="<?php echo lang('language'); ?>">
                                <i class="fal fa-globe"></i>
                            </a>
                            <ul class="sub__menu" role="menu">
                                <?php $language = $this->session->userdata('language');
                                    if(!$language){
                                        $language = "english";
                                    }
                                ?>
                                <?php
                                $dir = glob("application/language/*",GLOB_ONLYDIR);
                                foreach ($dir as $value):
                                    $separete = explode("language/",$value);
                                    ?>
                                <li class="<?=isset($language) && $language==$separete[1]?'active_lng':''?>"><a
                                        href="<?php echo base_url(); ?>Authentication/setlanguagePOS/<?=escape_output($separete[1])?>"><?php echo escape_output(ucfirst($separete[1]))?></a>
                                </li>
                                <?php
                                endforeach;
                                ?>
                            </ul>
                        </li>

                        <li>
                            <a href="#" id="open_hold_sales" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('open_hold_sale'); ?>">
                                <i class="fal fa-folder-open"></i>
                            </a>
                        </li>
                        <li><a href="javascript:void(0)" class="header_menu_icon" id="print_last_invoice"
                                data-tippy-content="<?php echo lang('print_last_invoice'); ?>"><i
                                    class="fal fa-print"></i></a></li>
                        <li>
                            <a href="#" id="last_ten_sales_button" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('recent_sales'); ?>"><i
                                    class="fal fa-history"></i></a>
                        </li>
                        <li>
                            <a href="#" id="last_ten_feature_button" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('feature_sales'); ?>"><i
                                    class="fa fa-history"></i></a>
                        </li>
                        <li>
                            <a href="#" id="help_button" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('read_before_begin'); ?>"><i
                                    class="fal fa-question-circle"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="calculator_button" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('calculator'); ?>"> <i
                                    class="fal fa-calculator"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" id="notification_button" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('kitchen_notification'); ?>">
                                <i class="fal fa-bell"></i>
                                <span id="notification_counter"
                                    class="c_badge <?php echo escape_output($notification_number)?'':'txt_11'?>"><?php echo escape_output($notification_number); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="register_details" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('register'); ?>">
                                <i class="fal fa-registered"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="header_menu_icon" id="go_to_dashboard" data-tippy-content="<?php 
                                if ($this->session->userdata('role') == 'Admin') {
                                    echo lang('dashboard'); 
                                }else{
                                    echo lang('back'); 
                                } 
                                ?>">
                                <i class="fal fa-tachometer-alt-fast"></i>
                            </a>
                        </li>

                    </ul>

                    <ul class="icon__menu">
                        <li><a href="javascript:void(0)" class="time__date"><i class="fal fa-stopwatch"></i></a></li>
                        <li><a href="javascript:void(0)" id="fullscreen" class="header_menu_icon"
                                data-tippy-content="<?php echo lang('fullscreen_1'); ?>"><i
                                    class="fal fa-expand-arrows-alt"></i></a></li>
                        <li>
                            <a href="#" data-tippy-content="<?php echo lang('main_menu'); ?>" id="open__menu"
                                class="header_menu_icon">
                                <i class="fal fa-align-justify"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Header Menu List -->
            <div class="header_part_right">
                <ul class="btn__menu">
                    <li>
                        <a href="#" id="button_category_show_all1" class="bg__blue"><?php echo lang('all'); ?></a>
                    </li>
                    <li class="has__children">
                        <a href="#" class="show__cat__list bg__purple"><?php echo lang('category'); ?></a>
                        <ul class="sub__menu">
                            <!--This variable could not be escaped because this is html content-->
                            <?php echo $cateogry_slide_to_show?>
                        </ul>
                    </li>
                    <li><a href="#" data-status="veg"
                            class="veg_bev_item bg__green"><?php echo lang('vegetarian_items'); ?></a></li>
                    <li><a href="#" data-status="bev"
                            class="veg_bev_item bg__grey"><?php echo lang('beverage_items'); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="top_header_for_mobile">
            <button type="button" data-isActive="false" id="show_running_order" class="bg__red">
                <i class="far fa-bags-shopping"></i> <span>Running Order</span></button>
            <button type="button" id="show_cart_list" class="bg__purple">
                <i class="far fa-list-alt"></i> <span>Cart</span></button>
            <button type="button" id="show_product" class="bg__grey">
                <i class="far fa-th"></i> <span>Products</span></button>
            <button type="button" id="show_all_menu" class="bg__green">
                <i class="fal fa-bars"></i> <span>Others</span></button>
        </div>
        <div id="main_part">
            <div class="left_item">
                <div class="main_left">
                    <div class="holder">
                        <div id="running_order_header">
                            <h3><?php echo lang('running_order'); ?></h3>
                            <span id="refresh_order"><i class="fas fa-sync-alt"></i></span>
                            <input type="text" name="search_running_orders" id="search_running_orders"
                                autocomplete='off' class="ir_h15_m_w90"
                                placeholder="<?php echo lang('customer_waiter_order_table'); ?>" />
                        </div>

                        <div class="order_details scrollbar-macosx" id="order_details_holder">
                            <!--This variable could not be escaped because this is html content-->
                            <?php echo $order_list_left; ?>
                        </div>
                        <div class="ir_pa_b_w100" id="left_side_button_holder_absolute">
                            <?php if($getCompanyInfo->pre_or_post_payment == "Post Payment"){?>
                            <button class="operation_button" id="modify_order"><i
                                    class="fas fa-edit"></i><?php echo lang('modify_order_'); ?></button>
                            <?php } ?>
                            <button class="operation_button fix" id="single_order_details"><i
                                    class="fas fa-info-circle"></i> <?php echo lang('order_details'); ?></button>

                            <div class="ir_flex_jc_pw94">
                                <button class="ir_calc_w98 no-need-for-waiter operation_button fix btn_tip"
                                    id="print_kot"
                                    data-tippy-content="<?php echo lang('print'); ?> <?php echo lang('KOT'); ?>">
                                    <i class="fas fa-print"></i> <?php echo lang('KOT'); ?>
                                </button>

                                <button class="operation_button no-need-for-waiter ir_calc_w98_m5 btn_tip"
                                    id="print_bot"
                                    data-tippy-content="<?php echo lang('print'); ?> <?php echo lang('BOT'); ?>">
                                    <i class="fas fa-print"></i> <?php echo lang('BOT'); ?>
                                </button>

                            </div>


                            <?php if($getCompanyInfo->pre_or_post_payment == "Post Payment"){?>
                            <div class="ir_flex_jc_w94_pr">
                                <button class="ir_calc_w98 operation_button no-need-for-waiter fix"
                                    id="create_invoice_and_close">
                                    <?php echo lang('invoice'); ?>
                                </button>
                                <button
                                    class="operation_button fix ir_calc_w98_m5 no-need-for-waiter btn_tip full-width-for-waiter"
                                    id="create_bill_and_close"
                                    data-tippy-content="<?php echo lang('Print_Bill_for_Customer_Before_Invoicing'); ?>">
                                    <?php echo lang('bill'); ?>
                                </button>

                            </div>
                            <?php } ?>


                            <?php if($getCompanyInfo->pre_or_post_payment == "Pre Payment"){?>
                            <button class="operation_button fix  btn_tip full-width-for-waiter"
                                id="create_bill_and_close"
                                data-tippy-content="<?php echo lang('Print_Bill_for_Customer_Before_Invoicing'); ?>">
                                <?php echo lang('bill'); ?>
                            </button>
                            <button class="operation_button fix" id="print_invoice"><i class="fas fa-file-invoice"></i>
                                <?php echo lang('create_invoice'); ?></button>
                            <button class="operation_button fix" id="close_order_button"><i
                                    class="fas fa-times-circle"></i>
                                <?php echo lang('close_order'); ?></button>
                            <?php } ?>
                            <?php
                                if(isset($waiter_app_status) && $waiter_app_status=="Yes"):
                            ?>
                            <div class="ir_flex_jc_w94_pr">
                                <button class="operation_button fix ir_calc_w98_m5 btn_tip full-width-for-waiter"
                                    id="bill_show_details" data-tippy-content="">
                                    <?php echo lang('bill'); ?>
                                </button>
                            </div>
                            <?php
                                endif;
                            ?>
                            <?php if($getCompanyInfo->pre_or_post_payment == "Post Payment"){?>
                            <button class="operation_button no-need-for-waiter fix" id="cancel_order_button"><i
                                    class="fas fa-ban"></i>
                                <?php echo lang('cancel_order'); ?></button>
                            <?php } ?>

                            <button class="operation_button fix" id="kitchen_status_button"><i
                                    class="fas fa-spinner"></i>
                                <?php echo lang('kitchen_status'); ?></button>
                        </div>

                    </div>
                </div>
                <div class="main_middle">
                    <div class="main_top fix">
                        <!-- Top Btns -->
                        <div class="button_holder no-need-for-waiter">

                            <button
                                data-selected="<?php echo isset($waiter_app_status) && $waiter_app_status=="Yes"?'selected':''?>"
                                id="dine_in_button">
                                <i class="fal fa-table"></i> <?php echo lang('dine'); ?>
                            </button>

                            <button id="take_away_button"><i class="fal fa-shopping-bag"></i>
                                <?php echo lang('take_away'); ?></button>

                            <button id="delivery_button"><i class="fal fa-truck"></i>
                                <?php echo lang('delivery'); ?></button>

                            <button id="table_button"><i class="fal fa-table"></i> <?php echo lang('table'); ?></button>

                        </div>

                        <div class="waiter_customer">
                            <div class="left_item">
                                <?php
                                    if($waiter_app_status=="Yes"):
                                ?>
                                <input type="hidden"
                                    value="<?php echo escape_output($this->session->userdata('user_id'))?>"
                                    id="select_waiter">
                                <button id="table_button" class="half-width-98"><i class="fal fa-table"></i>
                                    <?php echo lang('table'); ?></button>
                                <?php
                                else:
                                ?>
                                <select id="select_waiter" class="select2 select_waiter ir_w92_ml">
                                    <option value=""><?php  echo lang('waiter'); ?></option>
                                    <!--This variable could not be escaped because this is html content-->
                                    <?php echo $waiters_option; ?>
                                </select>
                                <?php
                                endif;
                                ?>
                                <select id="walk_in_customer" id="select_walk_in_customer" class="select2">
                                    <option value=""><?php echo lang('customer'); ?></option>
                                    <!--This variable could not be escaped because this is html content-->
                                    <?php echo $customers_option; ?>
                                </select>
                            </div>
                            <div class="separator">
                                <a href="#" data-tippy-content="<?php echo lang('edit_customer'); ?>"
                                    class="header_menu_icon" id="edit_customer">
                                    <i class="far fa-pencil-alt"></i>
                                </a>

                                <a href="#" id="plus_button" class="header_menu_icon"
                                    data-tippy-content="<?php echo lang('add_customer'); ?>">
                                    <i class="fal fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="main_center fix">
                        <div class="order_table_holder">
                            <div class="order_table_header_row">
                                <div class="single_header_column" id="single_order_item"><?php echo lang('item'); ?>
                                </div>
                                <div class="single_header_column" id="single_order_price"><?php echo lang('price'); ?>
                                </div>
                                <div class="single_header_column" id="single_order_qty"><?php echo lang('qty'); ?></div>
                                <div class="single_header_column" id="single_order_discount">
                                    <?php echo lang('discount'); ?></div>
                                <div class="single_header_column" id="single_order_total"><?php echo lang('total'); ?>
                                </div>
                            </div>
                            <div class="order_holder fix cardIsEmpty">

                            </div>
                        </div>

                    </div>
                    <div id="bottom_absolute">
                        <div class="bottom__info">
                            <div class="footer__content">
                                <div class="item">
                                    <input type="hidden" id="open_invoice_date_hidden"
                                        value="<?php echo date("Y-m-d",strtotime('today')) ?>">
                                    <div><?php echo lang('total_item'); ?>: <span
                                            id="total_items_in_cart_with_quantity">0</span>
                                        <i data-tippy-content="Invoice Date"
                                            class="no-need-for-waiter fal fa-calendar-alt input-group date datepicker_custom calendar_irp"
                                            id="open_date_picker"></i>
                                    </div>
                                    <span id="total_items_in_cart" class="ir_display_none">0</span>
                                </div>
                                <div class="item">
                                    <span><?php echo lang('sub_total'); ?>:</span>
                                    <span id="sub_total_show"><?php echo getAmtP(0)?></span>
                                    <span id="sub_total" class="ir_display_none"><?php echo getAmtP(0)?></span>
                                    <span id="total_item_discount" class="ir_display_none">0</span>
                                    <span id="discounted_sub_total_amount"
                                        class="ir_display_none"><?php echo getAmtP(0)?></span>
                                </div>
                                <div class="item no-need-for-waiter">
                                    <span>
                                        <?php echo lang('discount'); ?>: <i class="fal fa-edit"
                                            id="open_discount_modal"></i> <span
                                            id="show_discount_amount"><?php echo getAmtP(0)?></span>
                                    </span>
                                </div>
                                <div class="item">
                                    <span><?php echo lang('discount'); ?>:</span>
                                    <span id="all_items_discount"><?php echo getAmtP(0)?></span>
                                </div>
                                <div class="item">
                                    <span><?php echo lang('vat'); ?>:</span>
                                    <i class="fal fa-eye no-need-for-waiter" id="open_tax_modal"></i>
                                    <span id="show_vat_modal"><?php echo getAmtP(0)?></span>
                                </div>
                                <div class="item">
                                    <span><?php echo lang('delivery_charge'); ?>: <i
                                            class="fal fa-edit no-need-for-waiter" id="open_charge_modal"></i> <span
                                            id="show_charge_amount"><?php echo getAmtP(0)?></span></span>

                                </div>

                            </div>
                            <div class="payable">
                                <h1><?php echo lang('total_payable'); ?>: <span
                                        id="total_payable"><?php echo getAmtP(0)?></span></h1>
                            </div>
                            <div class="main_bottom">
                                <div class="button_group">

                                    <button id="cancel_button"><i class="fas fa-times"></i>
                                        <?php echo lang('cancel'); ?></button>


                                    <button id="hold_sale"><i class="fas fa-hand-rock"></i>
                                        <?php echo lang('hold'); ?></button>


                                    <button id="direct_invoice" class="no-need-for-waiter"><i
                                            class="fas fa-file-invoice"></i>
                                        <span
                                            id="place_edit_order_direct_invoice"><?php echo lang('direct_invoice'); ?></span></button>


                                    <button class="placeOrderSound" id="place_order_operation"><i
                                            class="fas fa-utensils"></i> <span
                                            id="place_edit_order"><?php echo lang('place_order'); ?></span></button>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="main_right">
                <form autocomplete="off">
                    <input type="text" name="search" id="search"
                        placeholder="<?php echo lang('name_code_cat_veg_bev_bar'); ?>" />
                </form>

                <div class="ir_pos_relative" id="main_item_holder">
                    <div class="category-list scrollbar-macosx">
                        <ul class="list-of-item">
                            <li>
                                <a href="#" id="button_category_show_all1"><?php echo lang('all'); ?></a>
                            </li>
                            <!--This variable could not be escaped because this is html content-->
                            <?php echo $cateogry_slide_to_show?>
                        </ul>
                    </div>

                    <div class="scrollbar-macosx" id="secondary_item_holder">
                        <div class="category_items">
                            <!--This variable could not be escaped because this is html content-->
                            <?php echo $menu_to_show; ?>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Responsive mobile menu -->
    <div class="all__menus">
        <ul class="menu__list">
            <div>
                <li>
                    <a href="#" id="notification_button">
                        <i class="fal fa-bell"></i> <?php echo lang('kitchen_notification'); ?>
                        <span id="notification_counter"
                            class="c_badge <?php echo escape_output($notification_number)?'':'txt_11'?>"><?php echo escape_output($notification_number);?></span>
                    </a>
                </li>
                <li>
                    <a href="#" id="button_category_show_all1">
                        <i class="fal fa-border-all"></i> <?php echo lang('all'); ?>
                    </a>
                </li>
                <li class="it_has_children">
                    <a href="#" class="show__cat__list"><?php echo lang('category'); ?></a>
                    <ul class="sub_menu category__list">
                        <!--This variable could not be escaped because this is html content-->
                        <?php echo $cateogry_slide_to_show?>
                    </ul>
                </li>
                <li><a href="#" data-status="bev" class="veg_bev_item"><?php echo lang('beverage_items'); ?></a></li>
                <li><a href="#" data-status="veg" class="veg_bev_item"><?php echo lang('vegetarian_items'); ?></a></li>
                <li>
                    <a href="#" id="open_hold_sales">
                        <i class="fal fa-folder-open"></i> <?php echo lang('open_hold_sale'); ?>
                    </a>
                </li>
                <li>
                    <a href="#" id="last_ten_sales_button">
                        <i class="fal fa-history"></i> <?php echo lang('recent_sales'); ?>
                    </a>
                </li>
                <li class="it_has_children">
                    <a href="#">
                        <i class="fal fa-globe"></i> <?php echo lang('language'); ?>
                    </a>
                    <ul class="sub_menu" role="menu">
                        <?php $language = $this->session->userdata('language');
                            if(!$language){
                                $language = "english";
                            }
                            ?>
                        <?php $dir = glob("application/language/*",GLOB_ONLYDIR);
                            foreach ($dir as $value): $separete = explode("language/",$value); ?>
                        <li class="<?=isset($language) && $language==$separete[1]?'active_lng':''?>"><a
                                href="<?php echo base_url(); ?>Authentication/setlanguagePOS/<?=escape_output($separete[1])?>"><?php echo escape_output(ucfirst     ($separete[1]))?></a>
                        </li> <?php endforeach; ?>
                    </ul>
                </li>

            </div>
            <!-- End Single Menu Column -->
            <div>
                <li class="it_has_children no-need-for-waiter">
                    <a href="#">
                        <i class="fal fa-user"></i> <?php echo lang('main_menu'); ?>
                    </a>
                    <ul class="sub_menu" role="menu">
                        <li>
                            <a href="<?php echo base_url();?>Authentication/userProfile">
                                <?php echo lang('my_profile'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>Authentication/changePassword">
                                <?php echo lang('change_password'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>Authentication/logOut"><?php echo lang('logout'); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="no-need-for-waiter">
                    <a href="javascript:void(0)" id="print_last_invoice">
                        <i class="fal fa-print"></i> <?php echo lang('print_last_invoice'); ?></a>
                </li>

                <li class="no-need-for-waiter">
                    <a href="#" id="last_ten_feature_button">
                        <i class="fal fa-history"></i> <?php echo lang('feature_sales'); ?>
                    </a>
                </li>
                <li class="no-need-for-waiter">
                    <a href="#" id="help_button">
                        <i class="fal fa-question-circle"></i> <?php echo lang('read_before_begin'); ?>
                    </a>
                </li>
                <li class="no-need-for-waiter">
                    <a href="#" id="calculator_button">
                        <i class="fal fa-calculator"></i> <?php echo lang('calculator'); ?>
                    </a>
                </li>

                <li class="no-need-for-waiter">
                    <a href="#" id="register_close">
                        <i class="fal fa-registered"></i> <?php echo lang('register'); ?>
                    </a>
                </li>
                <li class="no-need-for-waiter">
                    <a href="#" id="go_to_dashboard">
                        <i class="fal fa-tachometer-alt-fast"></i> <?php 
                                    if ($this->session->userdata('role') == 'Admin') {
                                        echo lang('dashboard'); 
                                    }else{
                                        echo lang('back'); 
                                    } 
                                    ?>
                    </a>
                </li>
            </div>
        </ul>
    </div>

    <div class="overlayForCalculator"></div>
    <!-- The Modal -->
    <div class="pos__modal__overlay"></div>

    <!-- Open Discount Modal -->
    <div id="discount_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">

            <h1 id="modal_item_name"><?php echo lang('discount'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="main-content-wrapper">
                <div>
                    <label for="discount_val"><?php echo lang('value'); ?></label>

                    <input type="hidden" class="special_textbox" placeholder="<?php echo lang('value'); ?>"
                        id="sub_total_discount" />

                    <input type="text" class="special_textbox integerchk" placeholder="<?php echo lang('value'); ?>"
                        id="sub_total_discount1" />

                    <span class="ir_display_none" id="sub_total_discount_amount"></span>
                </div>
                <div>
                    <label for="discount_type"><?php echo lang('type'); ?></label>
                    <select class="select2" id="discount_type" name="discount_type">
                        <option value="fixed"><?php echo lang('fixed'); ?></option>
                        <option value="percentage"><?php echo lang('percentage'); ?></option>
                    </select>
                </div>
            </div>
            <div class="btn__box">
                <button type="button" id="submit_discount_custom" class="submit"><?php echo lang('submit'); ?></button>
                <button type="button" id="cancel_discount_modal" class="cancel"><?php echo lang('cancel'); ?></button>
            </div>
        </div>
    </div>

    <!-- Open Service Charge Modal -->
    <div id="charge_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">

            <h1 id="modal_item_name"><?php echo lang('charge'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="main-content-wrapper">
                <div>
                    <?php
                        $service_type = $this->session->userdata('service_type');
                        $service_amount = $this->session->userdata('service_amount');
                    ?>
                    <label for="charge_type"><?php echo lang('type'); ?></label>
                    <select id="charge_type" class="select2">
                        <option <?php echo isset($service_type) && $service_type=="delivery"?'selected':''?> value="delivery"><?php echo lang('delivery'); ?></option>
                        <option <?php echo isset($service_type) && $service_type=="service"?'selected':''?> value="service"><?php echo lang('service'); ?></option>
                    </select>
                </div>
                <div>
                    <label for="charge_amount"><?php echo lang('amount'); ?></label>
                    <input type="text" name="" autocomplete="off" class="special_textbox" onfocus="select();"
                        placeholder="<?php echo lang('amount'); ?>" value="<?php echo isset($service_amount) && $service_amount?escape_output($service_amount):''?>" id="delivery_charge" />
                </div>
            </div>
            <div class="btn__box">
                <button type="button" class="submit"><?php echo lang('submit'); ?></button>
                <button type="button" class="cancel" id="cancel_charge_value"><?php echo lang('cancel'); ?></button>
            </div>
        </div>
    </div>

    <!-- Open Service Charge Modal -->
    <div id="tax_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">

            <h1 id="modal_item_name"><?php echo lang('tax_details'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="main-content-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th><?php echo lang('tax_name'); ?></th>
                            <th><?php echo lang('value'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="tax_row_show">

                    </tbody>
                </table>
            </div>
            <div class="btn__box">
                <button type="button" class="cancel"><?php echo lang('cancel'); ?></button>
            </div>
        </div>
    </div>


    <div id="item_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span id="modal_item_row" class="ir_display_none">0</span>
            <span id="modal_item_id" class="ir_display_none"></span>
            <span id="modal_item_price" class="ir_display_none"></span>
            <span id="modal_item_vat_percentage" class="ir_display_none"></span>
            <h1> <span id="item_name_modal_custom"><?php echo lang('item_name'); ?></span>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="ir_mrx5">
                <div class="section1 fix">
                    <div class="sec1_inside" id="sec1_1"><?php echo lang('quantity'); ?></div>
                    <div class="sec1_inside" id="sec1_2"><i class="fal fa-minus" id="decrease_item_modal"></i>
                        <span id="item_quantity_modal">1</span> <i class="fal fa-plus" id="increase_item_modal"></i>
                    </div>
                    <div class="sec1_inside" id="sec1_3"> <span id="modal_item_price_variable"
                            class="ir_display_none">0</span><span
                            id="modal_item_price_variable_without_discount">0</span><span id="modal_discount_amount"
                            class="ir_display_none">0</span></div>
                </div>
                <div class="section2 fix">
                    <div class="sec2_inside" id="sec2_1"><?php echo lang('modifiers'); ?></div>
                    <div class="sec2_inside" id="sec2_2"> <span id="modal_modifier_price_variable">0</span>
                        <span id="modal_modifiers_unit_price_variable" class="ir_display_none">0</span>
                    </div>
                </div>

                <div class="section3 fix">
                    <div class="modal_modifiers">
                        <p><?php echo lang('cool_haus_1'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('first_scoo_1'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('mg_1'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('modifier_1'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('cool_haus_1'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('first_scoo_2'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('mg-2'); ?></p>
                    </div>
                    <div class="modal_modifiers">
                        <p><?php echo lang('modifier_1'); ?></p>
                    </div>
                </div>

                <div id="modal_discount_section">
                    <p class="ir_fl_m_font_16" id="discount_txt_focus"><?php echo lang('discount'); ?> <i
                            data-tippy-content="<?php echo lang('txt_err_pos_6'); ?>"
                            class="fal fa-question-circle tooltip_modifier"></i></p><input type="text" name=""
                        onfocus="select();"
                        <?php echo isset($waiter_app_status) && $waiter_app_status=="Yes"?"readonly":'' ?>
                        id="modal_discount" placeholder="<?php echo lang('amt_or_p'); ?>" />
                </div>
                <div class="section4 fix"><?php echo lang('total'); ?>&nbsp;&nbsp;&nbsp;
                    <span id="modal_total_price">0</span>
                </div>
            </div>
            <div class="section6 fix">
                <div class="section5"><?php echo lang('note'); ?>:</div>
                <textarea name="item_note" id="modal_item_note" maxlength="50"></textarea>
            </div>
            <div class="section7">
                <div class="sec7_inside" id="sec7_2"><button
                        id="add_to_cart"><?php echo lang('add_to_cart_pos'); ?></button></div>
                <div class="sec7_inside" id="sec7_1"><button
                        id="close_item_modal"><?php echo lang('cancel'); ?></button></div>
            </div>

            <!-- <span class="btn-close">&times;</span> -->
            <!-- <p>Some text in the Modal..</p> -->
        </div>

    </div>

    <!-- The Modal -->
    <div id="add_customer_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="editCustomer1">
            <h1>
                <?php echo lang('add_customer'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>

            <div class="customer_add_modal_info_holder">
                <div class="content">

                    <div class="left-item b">
                        <input type="hidden" id="customer_id_modal" value="">
                        <div class="customer_section">
                            <p class="input_level"><?php echo lang('name'); ?> <span class="ir_color_red">*</span></p>
                            <input type="text" class="add_customer_modal_input" id="customer_name_modal" required>
                        </div>
                        <div class="customer_section">
                            <p class="input_level">
                                <?php echo lang('phone'); ?>
                                <span class="ir_color_red">*</span>
                                <small><?php echo lang('should_have_country_code'); ?></small>
                            </p>

                            <input type="text" class="add_customer_modal_input" id="customer_phone_modal" required>
                        </div>
                        <div class="customer_section">
                            <p class="input_level"><?php echo lang('email'); ?></p>
                            <input type="email" class="add_customer_modal_input" id="customer_email_modal">
                        </div>
                    </div>

                    <div class="right-item b">
                        <div class="customer_section">
                            <p class="input_level"><?php echo lang('dob'); ?></p>
                            <input type="datable" class="add_customer_modal_input" autocomplete="off"
                                id="customer_dob_modal" data-datable="yyyymmdd" data-datable-divider=" - ">
                        </div>
                        <div class="customer_section">
                            <p class="input_level"><?php echo lang('doa'); ?></p>
                            <input type="datable" class="add_customer_modal_input" autocomplete="off"
                                id="customer_doa_modal" data-datable="yyyymmdd" data-datable-divider=" - ">
                        </div>

                        <?php if(collectGST()=="Yes"){?>
                        <div class="customer_section">
                            <p class="input_level"><?php echo lang('gst_number'); ?></p>
                            <input type="text" class="add_customer_modal_input" id="customer_gst_number_modal">

                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="customer_section">
                    <p class="input_level"><?php echo lang('delivery_address'); ?></p>
                    <textarea id="customer_delivery_address_modal"></textarea>
                </div>
            </div>

            <div class="section7">
                <div class="sec7_inside" id="sec7_2"><button id="add_customer"><?php echo lang('submit'); ?></button>
                </div>
                <div class="sec7_inside" id="sec7_1"><button
                        id="close_add_customer_modal"><?php echo lang('cancel'); ?></button></div>
            </div>
            <!-- <span class="btn-close">&times;</span> -->
            <!-- <p>Some text in the Modal..</p> -->
        </div>

    </div>
    <!-- The Modal -->
    <div id="show_tables_modal2" class="modal display_none">

        <!-- Modal content -->
        <div class="modal-content" id="modal_content_show_tables2">
            <h1 class="ir_pos_relative">
                <?php echo lang('tables'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon" id="table_modal_cancel_button2">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <p id="new_or_order_number_table"><?php echo lang('order_number'); ?>: <span
                    id="order_number_or_new_text"><?php echo lang('new'); ?></span></p>
            <div class="select_table_modal_info_holder2 scrollbar-macosx">
                <!--This variable could not be escaped because this is html content-->
                <?php echo $tables_modal;?>
            </div>
            <div class="bottom_button_holder_table_modal">
                <div class="left half">
                    <button id="please_read_table_modal_button"><i class="fas fa-question-circle"></i>
                        <?php echo lang('please_read'); ?></button>
                </div>
                <div class="right half">
                    <button class="floatright" id="submit_table_modal"><?php echo lang('submit'); ?></button>
                    <button class="floatright"
                        id="proceed_without_table_button"><?php echo lang('proceed_without_table'); ?></button>
                    <button class="floatright" id="table_modal_cancel_button"><?php echo lang('cancel'); ?></button>
                </div>
            </div>
            <!-- <span class="btn-close">&times;</span> -->
            <!-- <p>Some text in the Modal..</p> -->
        </div>

    </div>
    <!-- end add customer modal -->

    <!-- The sale hold modal -->
    <div id="show_sale_hold_modal" class="modal">
        <div class="modal-content" id="modal_content_hold_sales">
            <h1 class="main_header fix"><?php echo lang('hold_sale'); ?> <a href="javascript:void(0)"
                    class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a></h1>
            <div class="hold_sale_modal_info_holder">
                <div class="btn__box">
                    <button type="button" class="bg__red" data-selectedBtn="unselected"
                        id="sale_hold_modal_order_details">Order Details</button>
                    <button type="button" class="bg__green" data-selectedBtn="selected"
                        id="sale_hold_modal_order_list">Order List</button>
                </div>
                <div class="detail_hold_sale_holder">
                    <div id="sale_hold_modal_order_info_list" class="hold_sale_left">
                        <label>
                            <input type="text" id="search_hold_sale"
                                placeholder="<?php echo lang('search_customer_name_or_mobile_number'); ?>">
                            <button><i class="far fa-search"></i></button>
                        </label>
                        <div class="hold_list_holder">
                            <div class="header_row">
                                <div class="first_column column"><?php echo lang('hold_number'); ?></div>
                                <div class="second_column column"><?php echo lang('customer'); ?>
                                    (<?php echo lang('phone'); ?>)</div>
                                <div class="third_column column"><?php echo lang('table'); ?></div>
                            </div>
                            <div class="scrollbar-macosx">
                                <div class="detail_holder draft-sale">

                                </div>
                            </div>
                            <div class="delete_all_hold_sales_container">
                                <button
                                    id="delete_all_hold_sales_button"><?php echo lang('delete_all_hold_sale'); ?></button>
                            </div>
                        </div>
                    </div>
                    <div id="sale_hold_modal_order_details_list" class="hold_sale_right">
                        <div class="top fix">
                            <div class="top_middle fix">
                                <h1><?php echo lang('order_details'); ?></h1>
                                <div class="waiter_customer_table fix">
                                    <div class="fix order_type"><span
                                            class="ir_font_bold"><?php echo lang('order_type'); ?>: </span><span
                                            id="hold_order_type"></span><span id="hold_order_type_id"
                                            class="ir_display_none"></span></div>
                                </div>
                                <div class="waiter_customer_table fix">
                                    <div class="waiter fix"><span class="ir_font_bold"><?php echo lang('waiter'); ?>:
                                        </span><span class="ir_display_none" id="hold_waiter_id"></span><span
                                            id="hold_waiter_name"></span></div>
                                    <div class="customer fix"><span
                                            class="ir_font_bold"><?php echo lang('customer'); ?>: </span><span
                                            class="ir_display_none" id="hold_customer_id"></span><span
                                            id="hold_customer_name"></span></div>
                                    <div class="table fix"><span class="ir_font_bold"><?php echo lang('table'); ?>:
                                        </span><span class="ir_display_none" id="hold_table_id"></span><span
                                            id="hold_table_name"></span></div>
                                </div>
                                <div class="item_modifier_details">
                                    <div class="modifier_item_header fix">
                                        <div class="first_column_header column_hold fix"><?php echo lang('item'); ?>
                                        </div>
                                        <div class="second_column_header column_hold fix"><?php echo lang('price'); ?>
                                        </div>
                                        <div class="third_column_header column_hold fix"><?php echo lang('qty'); ?>
                                        </div>
                                        <div class="forth_column_header column_hold fix"><?php echo lang('discount'); ?>
                                        </div>
                                        <div class="fifth_column_header column_hold fix"><?php echo lang('total'); ?>
                                        </div>
                                    </div>
                                    <div class="scrollbar-macosx">
                                        <div class="modifier_item_details_holder">
                                        </div>
                                    </div>
                                    <div class="bottom_total_calculation_hold">
                                        <div class="single_row first">
                                            <div class="item">
                                                <span><?php echo lang('total_item'); ?>: </span>
                                                <span id="total_items_in_cart_hold">0</span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('sub_total'); ?></span>
                                                <span id="sub_total_show_hold"><?php echo getAmtP(0)?></span>
                                                <span id="sub_total_hold"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                                <span id="total_item_discount_hold"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                                <span id="discounted_sub_total_amount_hold"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('discount'); ?></span>
                                                <span>
                                                    <span id="sub_total_discount_hold"></span><span
                                                        id="sub_total_discount_amount_hold"
                                                        class="ir_display_none"><?php echo getAmtP(0)?></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="single_row third">

                                        </div>
                                        <div class="single_row forth">
                                            <div class="item">
                                                <span><?php echo lang('total_discount'); ?>: </span>
                                                <span id="all_items_discount_hold"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('vat'); ?>: </span>
                                                <span id="all_items_vat_hold"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('delivery_charge'); ?></span>
                                                <span id="delivery_charge_hold"><?php echo getAmtP(0)?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="modal_payable">
                                        <span><?php echo lang('total_payable'); ?></span>
                                        <span id="total_payable_hold"><?php echo getAmtP(0)?></span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="button_holder">
                                <div class="single_button_holder">
                                    <button id="hold_edit_in_cart_button"><?php echo lang('edit_in_cart'); ?></button>
                                </div>
                                <div class="single_button_holder">
                                    <button id="hold_delete_button"><?php echo lang('delete'); ?></button>
                                </div>
                                <div class="single_button_holder">
                                    <button id="hold_sales_close_button"><?php echo lang('cancel'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end sale hold modal -->

    <!-- The sale hold modal -->
    <div id="show_last_ten_sales_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_content_last_ten_sales">
            <h1 class="main_header fix"><?php echo lang('recent_sales'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="last_ten_sales_modal_info_holder fix">
                <div class="btn_box">
                    <button type="button" id="recent_sales_order_details" data-selectedBtn="unselected"
                        class="bg__red">Order Details</button>
                    <button type="button" id="recent_sales_order_list" data-selectedBtn="selected"
                        class="bg__green">Order List</button>
                </div>
                <div class="last_ten_sales_holder">

                    <div id="recent_sales_order_info_list" class="hold_sale_left">
                        <label>
                            <input type="text" id="search_sales_custom_modal"
                                placeholder="<?php echo lang('search_customer_name_or_mobile_number'); ?>">
                            <button><i class="far fa-search"></i></button>
                        </label>
                        <div class="hold_list_holder">
                            <div class="header_row">
                                <div class="first_column column"><?php echo lang('sale_no'); ?></div>
                                <div class="second_column column"><?php echo lang('customer'); ?>
                                    (<?php echo lang('phone'); ?>)</div>
                                <div class="third_column column"><?php echo lang('table'); ?></div>
                            </div>
                            <div class="scrollbar-macosx">
                                <div class="detail_holder recent-sales">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="recent_sales_order_details_list" class="hold_sale_right">
                        <div class="top fix">
                            <div class="top_middle fix">
                                <h1><?php echo lang('order_details'); ?></h1>
                                <div class="waiter_customer_table fix">
                                    <div class="fix order_type">
                                        <span class="ir_font_bold"><?php echo lang('order_type'); ?>: </span>
                                        <span id="last_10_order_type" class="ir_w_d_ib">&nbsp;</span>
                                        <span id="last_10_order_type_id" class="ir_display_none"></span>
                                        <span class="ir_font_bold"><?php echo lang('invoice_no'); ?>: </span>
                                        <span id="last_10_order_invoice_no"></span>
                                    </div>
                                </div>
                                <div class="waiter_customer_table fix">
                                    <div class="waiter fix"><span class="ir_font_bold"><?php echo lang('waiter'); ?>:
                                        </span><span class="ir_display_none" id="last_10_waiter_id"></span><span
                                            id="last_10_waiter_name"></span></div>
                                    <div class="customer fix"><span
                                            class="ir_font_bold"><?php echo lang('customer'); ?>: </span><span
                                            class="ir_display_none" id="last_10_customer_id"></span><span
                                            id="last_10_customer_name"></span></div>
                                    <div class="table fix"><span class="ir_font_bold"><?php echo lang('table'); ?>:
                                        </span><span class="ir_display_none" id="last_10_table_id"></span><span
                                            id="last_10_table_name"></span></div>
                                </div>
                                <div class="item_modifier_details fix">
                                    <div class="modifier_item_header fix">
                                        <div class="first_column_header column_hold fix"><?php echo lang('item'); ?>
                                        </div>
                                        <div class="second_column_header column_hold fix"><?php echo lang('price'); ?>
                                        </div>
                                        <div class="third_column_header column_hold fix"><?php echo lang('qty'); ?>
                                        </div>
                                        <div class="forth_column_header column_hold fix"><?php echo lang('discount'); ?>
                                        </div>
                                        <div class="fifth_column_header column_hold fix"><?php echo lang('total'); ?>
                                        </div>
                                    </div>
                                    <div class="scrollbar-macosx">
                                        <div class="modifier_item_details_holder">
                                        </div>
                                    </div>
                                    <div class="bottom_total_calculation_hold">
                                        <div class="single_row first">
                                            <div class="item">
                                                <?php echo lang('total_item'); ?>:
                                                <span id="total_items_in_cart_last_10">0</span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('sub_total'); ?>: </span>
                                                <span id="sub_total_show_last_10"><?php echo getAmtP(0)?></span>
                                                <span id="sub_total_last_10"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                                <span id="total_item_discount_last_10"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                                <span id="discounted_sub_total_amount_last_10"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <span><?php echo lang('discount'); ?> :</span>
                                                <span id="sub_total_discount_last_10"><?php echo getAmtP(0)?></span>
                                                <span id="sub_total_discount_amount_last_10"
                                                    class="ir_display_none"><?php echo getAmtP(0)?></span>
                                            </div>
                                        </div>

                                        <div class="single_row third">

                                        </div>
                                        <div class="single_row forth">
                                            <div class="item">
                                                <?php echo lang('total_discount'); ?> : <span
                                                    id="all_items_discount_last_10"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <?php echo lang('vat'); ?>:
                                                <span id="recent_sale_modal_details_vat"><?php echo getAmtP(0)?></span>
                                            </div>
                                            <div class="item">
                                                <?php echo lang('delivery_charge'); ?>:
                                                <span id="delivery_charge_last_10"><?php echo getAmtP(0)?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <h1 class="modal_payable">
                                        <?php echo lang('total_payable'); ?>: <span
                                            id="total_payable_last_10"><?php echo getAmtP(0)?></span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="button_holder">
                                <div class="single_button_holder">
                                    <button class="no-need-for-waiter"
                                        id="last_ten_print_invoice_button"><?php echo lang('print_invoice'); ?></button>
                                </div>
                                <div class="single_button_holder">
                                    <button id="last_ten_delete_button"
                                        class="ir_font_capitalize no-need-for-waiter"><?php echo lang('delete'); ?></button>
                                </div>
                                <div class="single_button_holder">
                                    <button id="last_ten_sales_close_button"><?php echo lang('cancel'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end sale hold modal -->

    <!-- The sale hold modal -->
    <div id="generate_sale_hold_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_content_generate_hold_sales">
            <h1><?php echo lang('hold'); ?></h1>
            <div class="generate_hold_sale_modal_info_holder fix">
                <p class="ir_m_zero_b"><?php echo lang('hold_number'); ?> <span class="ir_color_red">*</span>
                </p>
                <input type="text" name="" id="hold_generate_input">
            </div>
            <div class="section7 fix">
                <div class="sec7_inside" id="sec7_1"><button id="hold_cart_info"><?php echo lang('submit'); ?></button>
                </div>
                <div class="sec7_inside" id="sec7_2"><button
                        id="close_hold_modal"><?php echo lang('cancel'); ?></button></div>
            </div>
        </div>

    </div>


    <div id="bill_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content" id="editCustomer1">
            <h1>
                <?php echo lang('bill'); ?> <?php echo lang('details'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>

            <div class="main-content show_bill_modal_content">
                <header>
                    <img src="<?=base_url()?>images/logo.png" />
                    <h3 class="title">Door Shop</h3>
                    <p>Bill No: <span id="b_bill_no"></span></p>
                </header>
                <ul class="simple-content">
                    <li>Date: <span id="b_bill_date"></span></li>
                    <li>Sales Associate: <span id="b_bill_creator"></span></li>
                    <li>Customer: <b><span id="b_bill_customer"></span></b></li>
                </ul>
                <ul class="main-content-list">
                    <li>
                        <span># 1: Better Chocolate Chip Cookies (09) 1 X 330.000</span>
                        <span>330.000$</span>
                    </li>
                    <li>
                        <span><b>Total Item(s): <span id="b_bill_total_item"></span></b></span>
                        <span></span>
                    </li>
                    <li>
                        <span>Sub Total</span>
                        <span><b><span id="b_bill_subtotal"></span></b></span>
                    </li>
                    <li>
                        <span>Grand Total</span>
                        <span><b><span id="b_bill_gtotal"></span></b></span>
                    </li>
                    <li>
                        <span>Total Payable</span>
                        <span><span id="b_bill_total_payable"></span></span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="cus_pos_modal" id="register_modal">
        <header class="pos__modal__header">
            <h3 class="pos__modal__title"><?php echo lang('register_details'); ?> <span
                    id="opening_closing_register_time">(<span id="opening_register_time"></span>
                    <?php echo lang('to'); ?> <span id="closing_register_time"></span>)</span></h3>

            <a href="javascript:void(0)" class="pos__modal__close"><i class="fal fa-times"></i></a>
        </header>

        <div class="pos__modal__body">
            <div class="default_inner_body" id="register_details_content">

            </div>
        </div>
        <footer class="pos__modal__footer">
            <div class="right_box">
                <button type="button" id="register_close"><?php echo lang('close_register'); ?></button>
                <button type="button" class="modal_hide"><?php echo lang('cancel'); ?></button>
            </div>
        </footer>
    </div>

    <div class="cus_pos_modal cus_pos_modal_feature_sale_modal" id="customModal">
        <header class="pos__modal__header">
            <h3 class="pos__modal__title"><?php echo lang('feature_sales'); ?></h3>
            <a href="javascript:void(0)" class="pos__modal__close"><i class="fal fa-times"></i></a>
        </header>
        <div class="pos__modal__body">
            <div class="default_inner_body">
                <div class="hold_sale">
                    <div class="left_item">
                        <label class="search__item">
                            <input type="text" id="search_future_custom_modal"
                                placeholder="<?php echo lang('search_customer_name_or_mobile_number'); ?>">
                            <button><i class="far fa-search"></i></button>
                        </label>
                        <div class="scrollbar-macosx position_future_sale_irp">
                            <div class="left_item_list_wrapper">
                                <div class="itemList">
                                    <div class="itemHeader">
                                        <div class="item"><?php echo lang('sale_no'); ?></div>
                                        <div class="item">
                                            <?php echo lang('customer'); ?> (<?php echo lang('phone'); ?>)
                                        </div>
                                        <div class="item"><?php echo lang('date'); ?></div>
                                    </div>
                                    <div class="detail_holder">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right_item">
                        <h3 class="title"><?php echo lang('order_details'); ?></h3>
                        <div class="waiter_customer_table">
                            <div class="fix order_type"><span class="ir_font_bold"><?php echo lang('order_type'); ?>:
                                </span><span id="last_10_order_type_"></span>
                            </div>
                        </div>
                        <div class="waiter_customer_table multiItem">
                            <div class="waiter"><span class="ir_font_bold"><?php echo lang('waiter'); ?>: </span><span
                                    id="last_10_waiter_name_"></span></div>
                            <div class="customer"><span class="ir_font_bold"><?php echo lang('customer'); ?>:
                                </span><span id="last_10_customer_name_"></span></div>
                            <div class="table">
                                <span class="ir_font_bold"><?php echo lang('table'); ?>:</span><span
                                    id="last_10_table_name_"></span>
                            </div>
                        </div>
                        <div class="item_order_details">
                            <header>
                                <div><?php echo lang('item'); ?></div>
                                <div><?php echo lang('price'); ?></div>
                                <div><?php echo lang('qty'); ?></div>
                                <div><?php echo lang('discount'); ?></div>
                                <div><?php echo lang('total'); ?></div>
                            </header>

                            <div class="scrollbar-macosx">
                                <div class="modifier_item_details_holder">

                                </div>
                            </div>

                        </div>


                        <div class="footer__details">

                            <div class="txt__subtotal">
                                <span class="total__item"><?php echo lang('total_item'); ?>: <span
                                        class="total_items_in_cart_last_10_">0</span></span>
                                <p class="txt"> <?php echo lang('total_discount'); ?>: <span
                                        class="all_items_discount_last_10_"><?php echo getAmtP(0)?></span></p>
                            </div>
                            <div class="txt__subtotal">
                                <span><?php echo lang('sub_total'); ?>: <span
                                        class="sub_total_show_last_10_"><?php echo getAmtP(0)?></span></span>

                                <p class="txt"><?php echo lang('vat'); ?>: <span
                                        class="recent_sale_modal_details_vat_"><?php echo getAmtP(0)?></span></p>
                            </div>
                            <div class="txt__subtotal">
                                <span class="discount"><?php echo lang('discount'); ?>: <span
                                        class="sub_total_discount_last_10_"><?php echo getAmtP(0)?></span></span>
                                <p class="txt"><?php echo lang('charge'); ?>: <span
                                        class="delivery_charge_last_10_"><?php echo getAmtP(0)?></span></p>
                            </div>
                        </div>
                        <h3 class="payable">
                            <span class="c-flex"><?php echo lang('total_payable');?>:</span>
                            <span class="total_payable_last_10_"><?php echo getAmtP(0)?></span></h3>
                    </div>
                </div>
            </div>
        </div>
        <footer class="pos__modal__footer">
            <div class="left_box">
                &nbsp;
            </div>
            <div class="right_box">
                <button type="button" id="draft_edit_modal"><?php echo lang('modify_order_'); ?></button>
                <button type="button" id="draft_edit_modal_invoice"><?php echo lang('set_as_running_order'); ?></button>
                <button type="button" class="modal_hide"><?php echo lang('cancel'); ?></button>
            </div>
        </footer>
    </div>

    <!-- end add customer modal -->
    <!-- The order details modal -->
    <div id="order_detail_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_content_sale_details">
            <h1 class="order_detail_title">
                <?php echo lang('order_details'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon" id="order_details_close_button2"><i
                        class="fal fa-times"></i></a>
            </h1>
            <div class="order_detail_modal_info_holder fix">
                <div class="top fix">
                    <div class="top_middle fix">
                        <div class="waiter_customer_table fix">
                            <div class="fix order_type">
                                <span class="ir_font_bold"><?php echo lang('order_type'); ?>: </span>
                                <span id="order_details_type" class="ir_d_block_w229"></span>
                                <span id="order_details_type_id" class="ir_display_none"></span>
                                <span class="ir_font_bold"><?php echo lang('order_number'); ?>: </span>
                                <span id="order_details_order_number"></span>
                            </div>
                        </div>
                        <div class="waiter_customer_table fix">
                            <div class="waiter fix"><span class="ir_font_bold"><?php echo lang('waiter'); ?>:
                                </span><span class="ir_display_none" id="order_details_waiter_id"></span><span
                                    id="order_details_waiter_name"></span></div>
                            <div class="customer fix"><span class="ir_font_bold"><?php echo lang('customer'); ?>:
                                </span><span class="ir_display_none" id="order_details_customer_id"></span><span
                                    id="order_details_customer_name"></span></div>
                            <div class="table fix"><span class="ir_font_bold"><?php echo lang('table'); ?>:
                                </span><span class="ir_display_none" id="order_details_table_id"></span><span
                                    id="order_details_table_name"></span></div>
                        </div>
                        <div class="item_modifier_details fix">
                            <div class="modifier_item_header fix">
                                <div class="first_column_header column_hold"><?php echo lang('item'); ?></div>
                                <div class="second_column_header column_hold"><?php echo lang('price'); ?></div>
                                <div class="third_column_header column_hold"><?php echo lang('qty'); ?></div>
                                <div class="forth_column_header column_hold"><?php echo lang('discount'); ?></div>
                                <div class="fifth_column_header column_hold"><?php echo lang('total'); ?></div>
                            </div>
                            <div class="scrollbar-macosx">
                                <div class="modifier_item_details_holder">
                                </div>
                            </div>
                            <div class="bottom_total_calculation_hold">

                                <div class="item">
                                    <div><?php echo lang('total_item'); ?>: <span
                                            id="total_items_in_cart_order_details">0</span></div>
                                    <div><?php echo lang('sub_total'); ?>:
                                        <span id="sub_total_show_order_details"><?php echo getAmtP(0)?></span>
                                        <span id="sub_total_order_details"
                                            class="ir_display_none"><?php echo getAmtP(0)?></span>
                                        <span id="total_item_discount_order_details"
                                            class="ir_display_none"><?php echo getAmtP(0)?></span>
                                        <span id="discounted_sub_total_amount_order_details"
                                            class="ir_display_none"><?php echo getAmtP(0)?></span>
                                    </div>
                                    <div>
                                        <?php echo lang('discount'); ?>:
                                        <span id="sub_total_discount_order_details"></span><span
                                            id="sub_total_discount_amount_order_details"
                                            class="ir_display_none"><?php echo getAmtP(0)?></span>
                                    </div>
                                </div>
                                <div class="item">
                                    <div>
                                        <?php echo lang('total_discount'); ?>:
                                        <span id="all_items_discount_order_details"><?php echo getAmtP(0)?></span>
                                    </div>
                                    <div>
                                        <?php echo lang('vat'); ?>:
                                        <span id="all_items_vat_order_details"><?php echo getAmtP(0)?></span>
                                    </div>
                                    <div>
                                        <?php echo lang('delivery_charge'); ?>:
                                        <span id="delivery_charge_order_details"><?php echo getAmtP(0)?></span>
                                    </div>
                                </div>
                            </div>
                            <h1 class="modal_payable"><?php echo lang('total_payable'); ?> <span
                                    id="total_payable_order_details"><?php echo getAmtP(0)?></span></h1>
                        </div>
                    </div>
                </div>
                <?php if($getCompanyInfo->pre_or_post_payment == "Pre Payment"){?>
                <div class="create_invoice_close_order_in_order_details" id="order_details_pre_invoice_buttons">
                    <div class="half fix floatleft textcenter">
                        <button id="order_details_create_invoice_button"><i class="fas fa-file-invoice"></i>
                            <?php echo lang('create_invoice'); ?></button>
                    </div>
                    <div class="half fix floatleft textcenter">
                        <button id="order_details_close_order_button"><i class="fas fa-times-circle"></i>
                            <?php echo lang('close_order'); ?></button>
                    </div>
                </div>
                <?php } ?>
                <?php if($getCompanyInfo->pre_or_post_payment == "Post Payment"){?>
                <div class="create_invoice_close_order_in_order_details" id="order_details_post_invoice_buttons">
                    <button class="no-need-for-waiter" id="order_details_create_invoice_close_order_button"><i
                            class="fas fa-file-invoice"></i>
                        <?php echo lang('create_invoice_close'); ?></button>
                </div>
                <?php } ?>
                <div class="create_invoice_close_order_in_order_details">
                    <button class="no-need-for-waiter" id="order_details_print_kot_button"><i
                            class="fas fa-file-invoice"></i>
                        <?php echo lang('print_kot'); ?></button>
                </div>
                <button id="order_details_close_button"><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
    <!-- end add customer modal -->

    <!-- The kitchen status modal -->
    <div id="kitchen_status_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_kitchen_status_details">
            <h1 id="kitchen_status_main_header">
                <?php echo lang('kitchen_status'); ?>
                <a href="javascript:void(0)" class="ir_top22_right0 alertCloseIcon" id="kitchen_status_close_button2"><i
                        class="fal fa-times"></i></a>
            </h1>
            <div class="kitchen_status_modal_info_holder fix">
                <p><span class="ir_font_bold"><?php echo lang('order_number'); ?>:</span> <span
                        id="kitchen_status_order_number"></span> <span
                        class="ir_font_bold"><?php echo lang('order_type'); ?>:</span> <span
                        id="kitchen_status_order_type"></span></p>
                <p class="">
                    <span class="ir_font_bold"><?php echo lang('waiter'); ?>: </span><span
                        id="kitchen_status_waiter_name"></span>
                    <span class="ir_font_bold"><?php echo lang('customer'); ?>: </span><span
                        id="kitchen_status_customer_name"></span>
                    <span class="ir_font_bold"><?php echo lang('order_table'); ?>: </span><span
                        id="kitchen_status_table"></span>
                </p>
                <div id="kitchen_status_detail_holder" class="fix">
                    <div id="kitchen_status_detail_header" class="fix">
                        <div class="fix first"><?php echo lang('item'); ?></div>
                        <div class="fix second"><?php echo lang('quantity'); ?></div>
                        <div class="fix third"><?php echo lang('status'); ?></div>
                    </div>

                    <div id="kitchen_status_item_details">

                    </div>

                </div>
                <h1 id="kitchen_status_order_placed"><?php echo lang('order_placed_at'); ?>: 14:22</h1>
                <h1 id="kitchen_status_time_count"><?php echo lang('time_count'); ?>: <span
                        id="kitchen_status_ordered_minute">23</span>:<span id="kitchen_status_ordered_second">55</span>
                    M</h1>
                <button id="kitchen_status_close_button"><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
    <!-- end kitchen status modal -->

    <!-- The table modal please read -->
    <div id="please_read_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_please_read_details">
            <h1 id="please_read_modal_header" class="ir_color_red">
                <?php echo lang('please_read'); ?>

            </h1>
            <div class="help_modal_info_holder scrollbar-macosx">

                <!-- <p class="para_type_1">How order process works</p> -->
                <p class="para_type_1"><?php echo lang('please_read_text_1'); ?>:</p>
                <p class="para_type_2"><?php echo lang('please_read_text_2'); ?></p>
                <p class="para_type_1"><?php echo lang('please_read_text_3'); ?>:</p>
                <p class="para_type_2"><?php echo lang('please_read_text_4'); ?></p>

            </div>
            <button id="please_read_close_button"><?php echo lang('close'); ?></button>
        </div>
    </div>
    <!-- end table modal please read modal -->

    <!-- The kitchen status modal -->
    <div id="help_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_help_details">
            <h1 id="help_modal_header" class="ir_color_red"><?php echo lang('read_before_begin'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon">
                    <i class="fal fa-times"></i>
                </a>
            </h1>
            <div class="help_modal_info_holder scrollbar-macosx">
                <p class="para_type_1"><?php echo lang('read_help_text_1'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_2'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_3'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_4'); ?></p>

                <p class="para_type_1"><?php echo lang('read_help_text_5'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_6'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_7'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_8'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_9'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_10'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_11'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_12'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_13'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_14'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_15'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_16'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_17'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_18'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_19'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_20'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_21'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_22'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_23'); ?></p>
                <p class="para_type_1"><?php echo lang('read_help_text_24'); ?></p>
                <p class="para_type_2"><?php echo lang('read_help_text_25'); ?></p>
            </div>
            <button id="help_close_button"><?php echo lang('close'); ?></button>

        </div>
    </div>
    <!-- end kitchen status modal -->

    <!-- The Modal -->
    <div id="finalize_order_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_finalize_order_details">
            <h1 id="modal_finalize_header"><?php echo lang('finalize_order'); ?></h1>
            <div class="fo_1 fix">
                <span class="ir_display_none" id="finalize_update_type"></span>
                <div class="half fix floatleft"><?php echo lang('total_payable'); ?></div>
                <div class="half fix floatleft textright"><span
                        id="finalize_total_payable"><?php echo getAmtP(0)?></span></div>
            </div>
            <div class="fo_2 fix">
                <div class="half fix floatleft"><?php echo 'Payment Method';//lang('total_payment'); ?></div>
                <div class="half fix floatleft textright">
                    <select name="finalie_order_payment_method" class="select2" id="finalie_order_payment_method">
                        <option value=""><?php echo lang('payment_method'); ?></option>
                        <!--This variable could not be escaped because this is html content-->
                        <?php echo $payment_method_options; ?>
                    </select>
                </div>

            </div>
            <div class="fo_3 fix">
                <div class="half fix floatleft textleft"><?php echo lang('paid_amount'); ?></div>
                <div class="half fix floatleft textright"><?php echo lang('due_amount'); ?></div>
                <div class="half fix floatleft textleft"><input type="text" name="pay_amount_invoice_modal_input"
                        id="pay_amount_invoice_input"></div>
                <div class="half fix floatleft textright"><input type="text" name="due_amount_invoice_modal_input"
                        id="due_amount_invoice_input" disabled></div>
            </div>
            <div class="fo_3  fix">
                <div class="half fix floatleft textleft"><?php echo lang('given_amount'); ?> <i
                        data-tippy-content="<?php echo lang('txt_err_pos_7'); ?>"
                        class="fal fa-question-circle given_amount_tooltip"></i></div>
                <div class="half fix floatleft textright"><?php echo lang('change_amount'); ?></div>
                <div class="half fix floatleft textleft"><input type="text" name="given_amount_modal_input"
                        id="given_amount_input"></div>
                <div class="half fix floatleft textright"><input type="text" name="change_amount_modal_input"
                        id="change_amount_input" disabled></div>
            </div>
            <div class="bottom_buttons fix">
                <div class="bottom_single_button fix">
                    <button id="finalize_order_button"><?php echo lang('submit'); ?></button>
                </div>
                <div class="bottom_single_button fix">
                    <button id="finalize_order_cancel_button"><?php echo lang('cancel'); ?></button>
                </div>
            </div>
            <!-- <span class="btn-close">&times;</span> -->
            <!-- <p>Some text in the Modal..</p> -->
        </div>

    </div>
    <!-- end of item modal -->

    <!-- The Notification List Modal -->
    <div id="notification_list_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_notification_list_details">
            <h1 id="modal_notification_header">
                <?php echo lang('notification_list'); ?>
                <a href="javascript:void(0)" class="alertCloseIcon" id="notification_close2"><i
                        class="fal fa-times"></i></a>
            </h1>
            <div id="notification_list_header_holder">
                <div class="single_row_notification_header fix ir_h25_bb1">
                    <div class="fix single_notification_check_box">
                        <input type="checkbox" id="select_all_notification">
                    </div>
                    <div class="fix single_notification"><strong><?php echo lang('select_all'); ?></strong></div>
                    <div class="fix single_serve_button">
                    </div>
                </div>
            </div>


            <div id="notification_list_holder" class="fix">
                <!--This variable could not be escaped because this is html content-->
                <?php echo $notification_list_show;?>
            </div>
            <!-- <span class="btn-close">&times;</span> -->
            <!-- <p>Some text in the Modal..</p> -->
            <div id="notification_close_delete_button_holder">
                <button id="notification_remove_all"><?php echo lang('remove'); ?></button>
                <button id="notification_close"><?php echo 'Cancel';//lang('close'); ?></button>
            </div>
        </div>

    </div>
    <!-- end of notification list modal -->


    <!-- The Notification List Modal -->
    <div id="kitchen_bar_waiter_panel_button_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content ir_pos_relative" id="modal_kitchen_bar_waiter_details">
            <p class="cross_button_to_close cCloseIcon" id="kitchen_bar_waiter_modal_close_button_cross">X</p>
            <h1 id="switch_panel_modal_header"><?php echo lang('kitchen_waiter_bar'); ?></h1>
            <div class="ir_p30">

                <a href="<?php echo base_url(); ?>Demo_panel/switchTo/kitchen" target="_blank" class="ir_w32_d_ta">
                    <button class="ir_w_100"><?php echo lang('kitchen_panel'); ?></button>
                </a>
                <a href="<?php echo base_url(); ?>Demo_panel/switchTo/waiter" target="_blank" class="ir_w32_d_ta">
                    <button class="ir_w_100"><?php echo lang('waiter_panel'); ?></button>
                </a>
                <a href="<?php echo base_url(); ?>Demo_panel/switchTo/bar" target="_blank" class="ir_w32_d_ta">
                    <button class="ir_w_100"><?php echo lang('bar_panel'); ?></button>
                </a>
            </div>

        </div>

    </div>
    <!-- end of notification list modal -->

    <!-- The KOT Modal -->
    <div id="kot_list_modal" class="modal">

        <!-- Modal content -->
        <div class="modal-content" id="modal_kot_list_details">
            <h1 id="modal_kot_header">
                <?php echo lang('kot'); ?>
                <a href="javascript:void(0)" class="ir_top5_right_10 alertCloseIcon" id="cancel_kot_modal2"><i
                        class="fal fa-times"></i></a>
            </h1>
            <h2 id="kot_modal_modified_or_not"><?php echo lang('modified'); ?></h2>
            <div id="kot_header_info" class="fix">
                <p><?php echo lang('order_no'); ?>: <span id="kot_modal_order_number"></span></p>
                <p><?php echo lang('date'); ?>: <span id="kot_modal_order_date"></span></p>
                <p><?php echo lang('customer'); ?>: <span id="kot_modal_customer_id"
                        class="ir_display_none"></span><span id="kot_modal_customer_name"></span></p>
                <p><?php echo lang('table'); ?>: <span id="kot_modal_table_name"></span></p>
                <p><?php echo lang('waiter'); ?>: <span id="kot_modal_waiter_name"></span>,
                    <?php echo lang('order_type'); ?>: <span id="kot_modal_order_type"></span></p>
            </div>
            <div id="kot_table_content" class="fix">
                <div class="kot_modal_table_content_header fix">
                    <div class="kot_header_row fix floatleft kot_check_column"><input type="checkbox"
                            id="kot_check_all"></div>
                    <div class="ir_w_405x kot_header_row fix floatleft kot_item_name_column">
                        <?php echo lang('item'); ?></div>
                    <div class="kot_header_row fix floatleft kot_qty_column"><?php echo lang('qty'); ?></div>
                </div>
                <div class="scrollbar-macosx">
                    <div id="kot_list_holder"></div>
                </div>
            </div>
            <div id="kot_bottom_buttons" class="fix">
                <button id="cancel_kot_modal"><?php echo lang('cancel'); ?></button><button
                    id="print_kot_modal"><?php echo lang('print_kot'); ?></button>
            </div>

        </div>

    </div>

    <div id="bot_list_modal" class="modal">

        <!-- Modal Content -->
        <div class="modal-content" id="modal_bot_list_details">
            <h1 id="modal_bot_header">
                <?php echo "BOT"; ?>
                <a href="javascript:void(0)" class="ir_top5_right_10 alertCloseIcon" id="cancel_bot_modal2"><i
                        class="fal fa-times"></i></a>
            </h1>
            <h2 id="bot_modal_modified_or_not"><?php echo lang('modified'); ?></h2>
            <div id="bot_header_info" class="fix">
                <p><?php echo lang('order_no'); ?>: <span id="bot_modal_order_number"></span></p>
                <p><?php echo lang('date'); ?>: <span id="bot_modal_order_date"></span></p>
                <p><?php echo lang('customer'); ?>: <span id="bot_modal_customer_id"
                        class="ir_display_none"></span><span id="bot_modal_customer_name"></span></p>
                <p><?php echo lang('table'); ?>: <span id="bot_modal_table_name"></span></p>
                <p><?php echo lang('waiter'); ?>: <span id="bot_modal_waiter_name"></span>,
                    <?php echo lang('order_type'); ?>: <span id="bot_modal_order_type"></span></p>
            </div>
            <div id="bot_table_content" class="fix">
                <div class="bot_modal_table_content_header fix">
                    <div class="bot_header_row fix floatleft bot_check_column"><input type="checkbox"
                            id="bot_check_all"></div>

                    <div class="ir_w_405x bot_header_row floatleft bot_item_name_column">
                        <?php echo lang('item'); ?>
                    </div>


                    <div class="bot_header_row fix floatleft bot_qty_column"><?php echo lang('qty'); ?></div>
                </div>

                <div class="scrollbar-macosx">
                    <div id="bot_list_holder"></div>
                </div>

            </div>
            <div id="bot_bottom_buttons" class="fix">
                <button id="cancel_bot_modal"><?php echo lang('cancel'); ?></button><button
                    id="print_bot_modal"><?php echo lang('print'); ?> <?php echo lang('BOT'); ?></button>
            </div>

        </div>

    </div>
    <!-- end of KOT modal -->
    <div id="calculator_main">
        <div class="calculator">
            <input type="text" readonly>
            <div class="row">
                <div class="key">1</div>
                <div class="key">2</div>
                <div class="key">3</div>
                <div class="key last">0</div>
            </div>
            <div class="row">
                <div class="key">4</div>
                <div class="key">5</div>
                <div class="key">6</div>
                <div class="key last action instant">cl</div>
            </div>
            <div class="row">
                <div class="key">7</div>
                <div class="key">8</div>
                <div class="key">9</div>
                <div class="key last action instant">=</div>
            </div>
            <div class="row">
                <div class="key action">+</div>
                <div class="key action">-</div>
                <div class="key action">x</div>
                <div class="key last action">/</div>
            </div>
        </div>
    </div>
    <div id="direct_invoice_button_tool_tip" class="ir_d_none_p_m_bg_br_bs">
        <h1 class="title ir_m_fs14_lh25"><?php echo lang('for_fast_food_restaurants'); ?></h1>
    </div>

    <!-- Pos Screen Sidebar  -->
    <aside id="pos__sidebar">
        <div class="brand__logo">
            <img src="<?=base_url()?>images/logo.png">
        </div>
        <ul class="pos__menu__list scrollbar-macosx">


            <?php
            $menu_access = getMainMenu();
            foreach ($menu_access as $ky=>$value_menu):
                $base_url_g = base_url().$value_menu->controller_name."/".$value_menu->function_name;
                if(isset($value_menu->sub_menus) && $value_menu->sub_menus):
                    if($value_menu->label=="Saas" && isServiceAccess('','','sGmsJaFJE')):
                        ?>
            <li class="have_sub_menu">
                <a href="#">
                    <i data-feather="file-text"></i> &nbsp;<span><?php echo lang($value_menu->label); ?></span>
                    <span class="pull-right-container">
                        <i class="far fa-chevron-left"></i>
                    </span>
                </a>
                <ul class="sub__menu__list">
                    <?php
                            foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                if($value_menu1->controller_name=="POS"){
                                    $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                }else{
                                    $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                }
                                ?>
                    <!--This variable could not be escaped because this is base url content-->
                    <li><a href="<?php echo $base_url_g1?>"><i class="fa fa-angle-double-right"></i>
                            <?php echo lang($value_menu1->label); ?></a></li>
                    <?php
                            endforeach;
                            ?>
                </ul>
            </li>
            <?php
                    elseif($value_menu->label!="Saas"):?>
            <li class="have_sub_menu">
                <a href="#">
                    <i data-feather="file-text"></i> &nbsp;<span><?php echo lang($value_menu->label); ?></span>
                    <span class="pull-right-container">
                        <i class="far fa-chevron-left"></i>
                    </span>
                </a>
                <ul class="sub__menu__list">
                    <?php
                                foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                    if($value_menu1->controller_name=="POS"){
                                        $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                    }else{
                                        $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                    }
                                    ?>
                    <!--This variable could not be escaped because this is base url content-->
                    <li><a href="<?php echo $base_url_g1?>"><i class="fa fa-angle-double-right"></i>
                            <?php echo lang($value_menu1->label); ?></a></li>
                    <?php
                                endforeach;
                                ?>
                </ul>
            </li>
            <?php
                    endif;
                else:
                    $data_c = getLanguageManifesto();
                    if($value_menu->controller_name=="Transfer" && str_rot13($data_c[0]) == "fgjgldkfg") {
                        ?>

            <?php
                    }else if($value_menu->controller_name=="Plugin" && isServiceAccess('','','sGmsJaFJE')){
                        $label = lang($value_menu->label);
                        $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;

                        ?>
            <li>
                <!--This variable could not be escaped because this is base url content-->
                <a href="<?php echo $base_url_g ?>">
                    <?php
                                if ($value_menu->icon):
                                    ?>
                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                    <?php
                                endif;
                                ?>
                    <span> &nbsp; <?php echo escape_output($label); ?></span>
                </a>
            </li>
            <?php }else if($value_menu->controller_name!="Plugin"){?>
            <li>
                <?php
                            $label = lang($value_menu->label);
                            if ($value_menu->controller_name == "Outlet") {
                                if (str_rot13($data_c[0]) == "eriutoeri") {
                                    $label = lang('outlets');
                                } else if (str_rot13($data_c[0]) == "fgjgldkfg") {
                                    $label = lang('outlet_setting');
                                }
                                $base_url_g = base_url() . $data_c[1];
                            } else {
                                $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;
                            }
                            ?>
                <!--This variable could not be escaped because this is base url content-->
                <a href="<?php echo $base_url_g ?>">
                    <?php
                                if ($value_menu->icon):
                                    ?>
                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                    <?php
                                endif;
                                ?>
                    <span> &nbsp; <?php echo escape_output($label); ?></span>
                </a>
            </li>
            <?php
                    }
                        ?>
            <?php
                endif;
            endforeach;
            ?>
        </ul>
    </aside>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/marquee.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/items.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/datable.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>
    <!-- For Tooltip -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/lib/tippy/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/lib/tippy/tippy-bundle.umd.min.js">
    </script>
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/POS/js/lib/datepicker.js"></script>
    <!-- Custom Scrollbar -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/lib/scrollbar/jquery.scrollbar.min.js">
    </script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/howler.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/feather.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/pos_script.js"></script>
    <script src="<?php echo base_url(); ?>assets/POS/js/media.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/notify/jquery.notifyBar.js"></script>

    <script type="text/javascript">
    /*This variable could not be escaped because this is building object*/
    window.customers = [<?php echo $customer_objects;?>];
    /*This variable could not be escaped because this is building object*/
    window.items = [<?php echo $javascript_obects;?>];
    /*This variable could not be escaped because this is building object*/
    window.item_modifiers = [<?php echo $javascript_obects_modifier;?>];
    </script>
</body>

</html>