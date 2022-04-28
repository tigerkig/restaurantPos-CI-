<?php 
    $notification_number = 0;
    if(count($notifications)>0){
        $notification_number = count($notifications);
    }

    /************************************************************************************************************************
 * Construct notification list ***********************************************************************************
 * **********************************************************************************************************************
 */
$notification_list_show = '';

foreach ($notifications as $single_notification){
    $notification_list_show .= '<div class="single_row_notification" id="single_notification_row_'.$single_notification->id.'">';
    $notification_list_show .= '<div class="fix single_notification_check_box">';
    $notification_list_show .= '<input class="single_notification_checkbox" type="checkbox" id="single_notification_'.$single_notification->id.'" value="'.$single_notification->id.'">';
    $notification_list_show .= '</div>';
    $notification_list_show .= '<div class="fix single_notification">'.$single_notification->notification.'</div>';
    $notification_list_show .= '<div class="single_serve_button">';
    $notification_list_show .= '<button class="btn bg-blue-btn" id="notification_serve_button_'.$single_notification->id.'">Delete</button>';
    $notification_list_show .= '</div>';
    $notification_list_show .= '</div>';
    
}

/************************************************************************************************************************
 * End of Construct notification list ***********************************************************************************
 * **********************************************************************************************************************
 */ 
    $show_all_orders = '';
    if(count($getUnReadyOrders)>0){
        
        foreach($getUnReadyOrders as $singleOrder){
            if($singleOrder->order_type==1){
                $order_type = "Dine In";
                $order_name = "A ".$singleOrder->sale_no;
            }elseif($singleOrder->order_type==2){
                $order_type = "Take Away";
                $order_name = "B ".$singleOrder->sale_no;
            }elseif($singleOrder->order_type==3){
                $order_type = "Delivery";
                $order_name = "C ".$singleOrder->sale_no;
            }
            $tables_booked = '';
            if(count($singleOrder->tables_booked)>0){
                $w = 1;
                foreach($singleOrder->tables_booked as $single_table_booked){
                    if($w == count($singleOrder->tables_booked)){
                        $tables_booked .= $single_table_booked->table_name;
                    }else{
                        $tables_booked .= $single_table_booked->table_name.', ';
                    }
                    $w++;
                }    
            }else{
                $tables_booked = 'None';
            }
            $to_time = strtotime(date('Y-m-d H:i:s'));
            $from_time = strtotime($singleOrder->date_time);
            $minutes = round(abs($to_time - $from_time) / 60,2);
            $seconds = abs($to_time - $from_time) % 60;
            $width = 100;
            $total_bar_type_items = $singleOrder->total_bar_type_items;
            $total_bar_type_started_cooking_items = $singleOrder->total_bar_type_started_cooking_items;
            $total_bar_type_done_items = $singleOrder->total_bar_type_done_items;
            $selected_unselected = "unselected"; 

            if($total_bar_type_items!=$total_bar_type_done_items){
                $show_all_orders .= '<div class="fix floatleft single_order" data-order-type="'.$order_type.'" data-selected="'.$selected_unselected.'" id="single_order_'.$singleOrder->sale_id.'">';
                    $show_all_orders .= '<div class="header_portion light-blue-background fix">';
                        $show_all_orders .= '<div class="fix floatleft half">';
                            $show_all_orders .= '<p class="order_number">'.lang('invoice').': '.$order_name.'</p> ';
                            $show_all_orders .= '<p class="order_number">'.lang('table').': '.$tables_booked.'</p> ';
                        $show_all_orders .= '</div>';
                        $show_all_orders .= '<div class="fix floatleft half">';
                            $show_all_orders .= '<p class="order_duration dark-blue-background"><span id="bar_time_minute_'.$singleOrder->sale_id.'">'.str_pad(round($minutes), 2, "0", STR_PAD_LEFT).'</span>:<span id="bar_time_second_'.$singleOrder->sale_id.'">'.str_pad(round($seconds), 2, "0", STR_PAD_LEFT).'</span></p>';
                        $show_all_orders .= '</div>';
                    $show_all_orders .= '</div>';
                    $show_all_orders .= '<div class="fix items_holder">';
                    $items = $singleOrder->items;
                    foreach($items as $single_item){
                        $item_background = '';
                        $font_style = '';
                        $cooking_status = '';
                        if($single_item->cooking_status=="Done"){
                            $item_background = "green-background";
                            $font_style = "color:#fff;";
                            $cooking_status = "Done";
                        }else if($single_item->cooking_status=="Started Preparing"){
                            $item_background = "light-blue-background";
                            $font_style = "color:#fff;";
                            $cooking_status = "Preparing";
                        }

                        $show_all_orders .= '<div data-selected="unselected" class="fix single_item '.$item_background.'" data-order-id="'.$singleOrder->sale_id.'" data-item-id="'.$single_item->previous_id.'" id="detail_item_id_'.$single_item->previous_id.'" data-cooking-status="'.$single_item->cooking_status.'">';
                            $show_all_orders .= '<div class="single_item_left_side fix">';
                                $show_all_orders .= '<div class="fix floatleft item_quantity">';
                                    $show_all_orders .= '<p class="item_quanity_text" style="'.$font_style.'">'.$single_item->qty.'</p>';
                                $show_all_orders .= '</div>';
                                $show_all_orders .= '<div class="fix floatleft item_detail">';
                                    $show_all_orders .= '<p class="item_name" style="'.$font_style.'">'.$single_item->menu_name.'</p>';
                                    $show_all_orders .= '<p class="item_qty" style="font-weight:bold; '.$font_style.'">'.lang('qty').': '.$single_item->qty.'</p>';

                                    $modifiers = $single_item->modifiers;
                                    $modifiers_length = count($modifiers);
                                    $w = 1;
                                    $modifiers_name = '';
                                    foreach($modifiers as $single_modifier){
                                       if($w==$modifiers_length){
                                            $modifiers_name .= $single_modifier->name;
                                       }else{
                                            $modifiers_name .= $single_modifier->name.', ';
                                       }
                                       $w++; 
                                    }
                                    if($modifiers_length>0){
                                        $show_all_orders .= '<p class="modifiers" style="'.$font_style.'">- '.$modifiers_name.'</p>';    
                                    }
                                    if($single_item->menu_note!=""){
                                        $show_all_orders .= '<p class="note" style="'.$font_style.'">- '.$single_item->menu_note.'</p>';    
                                    }
                                $show_all_orders .= '</div>';
                            $show_all_orders .= '</div>';
                            $show_all_orders .= '<div class="single_item_right_side fix">';
                                $show_all_orders .= '<p class="single_item_cooking_status" style="'.$font_style.'">'.$cooking_status.'</p>';
                            $show_all_orders .= '</div>';
                        $show_all_orders .= '</div>';
                    }
                    
                    $show_all_orders .= '</div>';
                    $show_all_orders .= '<div class="single_order_button_holder" id="single_order_button_holder_'.$singleOrder->sale_id.'">';
                        $show_all_orders .= '<button class="select_all_of_an_order" id="select_all_of_an_order_'.$singleOrder->sale_id.'">'.lang('select_all').'</button><button class="unselect_all_of_an_order" id="unselect_all_of_an_order_'.$singleOrder->sale_id.'">'.lang('unselect_all').'</button><button class="start_cooking_button" id="start_cooking_button_'.$singleOrder->sale_id.'">'.lang('prepare').'</button><button class="done_cooking" id="done_cooking_'.$singleOrder->sale_id.'">'.lang('done').'</button>';    
                    $show_all_orders .= '</div>';
                $show_all_orders .= '</div>';
            }

        }
    }

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
<html class="gr__localhost">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval'"> -->
    <title><?php echo escape_output($site_name); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>frequent_changing/bar_panel/css/style.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>frequent_changing/bar_panel/css/sweetalert2.min.css">
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/bower_components/font-awesome/v5/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>asset/plugins/iCheck/minimal/color-scheme.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/login.css">

    <script src="<?php echo base_url()?>frequent_changing/bar_panel/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url()?>frequent_changing/js/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/jquery.slimscroll.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/sweetalert2.all.min.js"></script>
    <script type="text/javascript"
        src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>

    <base data-base="<?php echo base_url(); ?>">
    </base>
    <base data-collect-vat="<?php echo escape_output($this->session->userdata('collect_vat')); ?>">
    </base>
    <base data-currency="">
    </base>
    <base data-role="<?php echo escape_output($this->session->userdata('role')); ?>">
    </base>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/style.css">

</head>

<body>

    <input type="hidden" id="csrf_name_" value="<?php echo escape_output($this->security->get_csrf_token_name()); ?>">
    <input type="hidden" id="csrf_value_" value="<?php echo escape_output($this->security->get_csrf_hash()); ?>">

    <span class="ir_display_none" id="selected_order_for_refreshing_help"></span>
    <span class="ir_display_none" id="refresh_it_or_not"><?php echo lang('yes'); ?></span>
    <div class="wrapper fix">
        <div class="main_top">
            <div class="row">
                <div class="top_header col-sm-12 col-md-4">
                    <h1><?php echo lang('bar_panel'); ?></h1>
                </div>
                <div class="top_menu col-sm-12 col-md-8 d-flex align-items-center justify-content-end">
                        <?php $language=$this->session->userdata('language'); ?>
                            <?php echo form_open(base_url() . 'Authentication/setlanguage', $arrayName = array('id' => 'language')) ?>
                            <select tabindex="2" class="form-control select2 ir_w_100" name="language"
                                onchange='this.form.submit()'>
                                <option value="english" <?php if(isset($language)){
                                                if ($language == 'english') 
                                                    echo "selected";
                                                }  
                                                ?>>English</option>
                                <option value="spanish" <?php if(isset($language)){
                                                if ($language == 'spanish') 
                                                    echo "selected";
                                                }  
                                                ?>>Spanish</option>
                                <option value="french" <?php if(isset($language)){
                                                if ($language == 'french') 
                                                    echo "selected";
                                                }  
                                                ?>>French</option>
                                <option value="arabic" <?php if(isset($language)){
                                                if ($language == 'arabic') 
                                                    echo "selected";
                                                }  
                                                ?>>Arabic</option>
                            </select>
                        </form>
                        <a class="btn bg-blue-btn me-2" href="<?php echo base_url(); ?>Authentication/userProfile" id="logout_button"><i
                            class="fas fas-caret-square-left"></i><?php echo lang('back'); ?></a>

                        <div class="top_menu_right" id="group_by_order_item_holder ir_h_float_m"></div>

                        <div class="top_menu_right me-2 btn bg-blue-btn">
                            <p class="m-0">
                                <i class="fas fa-sync-alt ir_mouse_pointer" id="refresh_orders_button"></i>
                            </p>
                        </div>
                        
                        <button id="notification_button" data-bs-toggle="modal" data-bs-target="#notification_list_modal" class="btn bg-blue-btn me-2">
                            <i class="fas fa-bell me-2"></i> <?php echo lang('notification'); ?> (<span
                            id="notification_counter"><?php echo escape_output($notification_number); ?></span>)</button>

                        <button id="help_button" data-bs-toggle="modal" data-bs-target="#help_modal" class="me-2 btn bg-blue-btn"><i class="fas me-2 fa-question-circle"></i> <?php echo lang('help'); ?></button>

                        <a class="btn bg-blue-btn me-2 d-flex align-items-center" href="<?php echo base_url(); ?>Authentication/logOut" id="logout_button"><i
                            class="fas me-2 fa-sign-out-alt"></i> <?php echo lang('logout'); ?></a>

                    
                </div>
            </div>
        </div>

        <div class="fix main_bottom">
            <div class="fix order_holder mt-2" id="order_holder">
                <!--This variable could not be escaped because this is html content-->
                <?php echo $show_all_orders ?>
            </div>

        </div>

    </div>

    <!-- The Modal -->
    
    <div class="modal fade" id="help_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('help'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="help_content">
                <?php echo lang('kitchen_help_text_first_para'); ?> 
                </p>
                <p class="help_content">
                <?php echo lang('kitchen_help_text_second_para'); ?>
                </p>
                <p class="help_content">
                    <?php echo lang('kitchen_help_text_third_para'); ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue-btn" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notification_list_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('notification_list'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="notification_list_header_holder">
                    <div class="single_row_notification_header fix ir_h_bm">
                        <div class="fix single_notification_check_box">
                            <input type="checkbox" id="select_all_notification">
                        </div>
                        <div class="fix single_notification"><strong><?php echo lang('select_all'); ?></strong></div>
                        <div class="fix single_serve_button">
                        </div>
                    </div>
                </div>

                <div id="notification_list_holder" class="fix">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn bg-blue-btn" id="notification_remove_all"><?php echo lang('remove'); ?></button>
                <button class="btn bg-blue-btn" data-bs-dismiss="modal" id="notification_close"><?php echo lang('close'); ?></button>
                
            </div>
            </div>
        </div>
    </div>
    <!-- end of notification list modal -->
    <script src="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/marquee.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/datable.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/bar_panel/js/custom.js"></script>
</body>

</html>