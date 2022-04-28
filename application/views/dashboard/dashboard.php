<!-- bootstrap datepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
</script>
<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.slimscroll.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/dashboard.css">
<!-- Content Header (Page header) -->


<input type="hidden" id="purchase" value="<?php echo lang('purchase'); ?>">
<input type="hidden" id="sale" value="<?php echo lang('sale'); ?>">
<input type="hidden" id="waste" value="<?php echo lang('waste'); ?>">
<input type="hidden" id="expense" value="<?php echo lang('expense'); ?>">
<input type="hidden" id="cust_rcv" value="<?php echo lang('cust_rcv'); ?>">
<input type="hidden" id="supp_pay" value="<?php echo lang('supp_pay'); ?>">

<input type="hidden" id="purchase_value" value="<?php echo escape_output(getAmtP($purchase_sum->purchase_sum)) ?>">
<input type="hidden" id="sale_value" value="<?php echo escape_output(getAmtP($sale_sum->sale_sum)) ?>">
<input type="hidden" id="waste_value" value="<?php echo escape_output(getAmtP($waste_sum->waste_sum)) ?>">
<input type="hidden" id="expense_value" value="<?php echo escape_output(getAmtP($expense_sum->expense_sum)) ?>">
<input type="hidden" id="cust_rcv_value" value="<?php echo escape_output(getAmtP($customer_due_receive_sum->customer_due_receive_sum)) ?>">
<input type="hidden" id="supp_pay_value" value="<?php echo escape_output(getAmtP($supplier_due_payment_sum->supplier_due_payment_sum)) ?>">
<input type="hidden" id="dinein_count" value="<?php echo escape_output($dinein_count->dinein_count) ?>">
<input type="hidden" id="take_away_count" value="<?php echo escape_output($take_away_count->take_away_count) ?>">
<input type="hidden" id="delivery_count" value="<?php echo escape_output($delivery_count->delivery_count) ?>">
<!-- Main content -->
<section class="main-content-wrapper">
    
    <section class="content-header dashboard_content_header my-2 <?=returnSessionLng()=="arabic"?'right_aligned"':''?>">
        <h3 class="top-left-header">
            <span><?php echo lang('dashboard'); ?></span>
            <small><?php echo lang('business_intelligence'); ?></small>
        </h3>
        <?php
        if(isLMni()):
        ?>
        <form class="ms-2" method="post" id="outlet_form" action="<?php echo base_url()?>Dashboard/dashboard">
            <select class="select_outlet_dashboard select2 form-control" id="outlet_id_dashboard" name="outlet_id">
                <?php
                foreach ($outlets as $value):
                ?>
                <option <?= set_select('outlet_id',$value->id)?> value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                <?php
                endforeach;
                ?>
            </select>
        </form>
        <?php
        endif;
        ?>
    </section>

    <div class="row">
        
        <div class="col-sm-12 col-md-6 col-lg-3">
            <!-- small box -->
            <div class="small-box box4column">
                <div class="inner">
                    <h3><?php echo isset($food_menu_count) && escape_output($food_menu_count)?$food_menu_count:'0' ?></h3>

                    <p><?php echo lang('food_items'); ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-pizza"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">&nbsp;</a> -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-sm-12 col-md-6 col-lg-3">
            <!-- small box -->
            <div class="small-box box4column">
                <div class="inner">
                    <h3><?php echo isset($ingredient_count) && escape_output($ingredient_count)?$ingredient_count:'0' ?></h3>

                    <p><?php echo lang('ingredients'); ?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-leaf"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">&nbsp;</a> -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-sm-12 col-md-6 col-lg-3">
            <!-- small box -->
            <div class="small-box box4column">
                <div class="inner">
                    <h3><?php echo isset($customer_count->data_count) && escape_output($customer_count->data_count)?$customer_count->data_count:'0' ?></h3>

                    <p><?php echo lang('customers'); ?></p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">&nbsp;</a> -->
            </div>
        </div>
        <!-- ./col -->
        <div class="col-sm-12 col-md-6 col-lg-3">
            <!-- small box -->
            <div class="small-box box4column">
                <div class="inner">
                    <h3><?php echo isset($employee_count->data_count) && escape_output($employee_count->data_count)?$employee_count->data_count:'0' ?></h3>

                    <p><?php echo lang('users'); ?></p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">&nbsp;</a> -->
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- quick email widget -->
    <div class="row">
        <div class="col-lg-7">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="link"></i>
                    <h3 class="box-title"><?php echo lang('quick_links'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 mb-3">
                            <a class="btn icon-btn btn-default-bordered w-100"
                                href="<?php echo base_url(); ?>foodMenu/addEditFoodMenu"><span
                                    class="fa fa-book p-2"></span>+ <?php echo lang('food_menu'); ?></a>
                            <a class="btn icon-btn btn-default-bordered w-100"
                                href="<?php echo base_url(); ?>SupplierPayment/addSupplierPayment"><span
                                    class="fa fa-user p-2"></span>+ <?php echo lang('supplier_payment'); ?></a>
                            <a class="btn icon-btn btn-default-bordered w-100" href="<?php echo base_url(); ?>Sale/POS"><span
                                    class="fa fa-television p-2"></span><?php echo lang('pos'); ?></a>
                            <a class="btn icon-btn btn-default-bordered w-100"
                                href="<?php echo base_url(); ?>Expense/addEditExpense"><span
                                    class="fa fa-money p-2"></span>+ <?php echo lang('expense'); ?></a>
                            <a class="btn icon-btn btn-default-bordered w-100"
                                href="<?php echo base_url(); ?>Purchase/addEditPurchase"><span
                                    class="fa fa-truck p-2"></span>+ <?php echo lang('purchase'); ?></a>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-3">
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Report/dailySummaryReport"><span
                                    class="fa fa-list p-2"></span><?php echo lang('daily_summary_report'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Report/registerReport"><span
                                    class="fa fa-list p-2"></span><?php echo lang('register_report'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Report/profitLossReport"><span
                                    class="fa fa-list p-2"></span><?php echo lang('profit_loss_report'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Report/saleReportByDate"><span
                                    class="fa fa-list p-2"></span><?php echo lang('sales_report'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Report/foodMenuSales"><span
                                    class="fa fa-list p-2"></span><?php echo lang('food_sales_report'); ?></a>
                        </div>
                        <div class="col-sm-12 col-md-4 mb-3">
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Setting/index"><span
                                    class="fa fa-cog p-2"></span><?php echo lang('Setting'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Inventory/index"><span
                                    class="fa fa-cube p-2"></span><?php echo lang('inventory'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Inventory_adjustment/inventoryAdjustments"><span
                                    class="fa fa-adjust p-2"></span><?php echo lang('inventory_adjustment'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Customer_due_receive/customerDueReceives"><span
                                    class="fa fa-users p-2"></span>+ <?php echo lang('customer_receive'); ?></a>
                            <a class="btn icon-btn btn-default-bordered  w-100"
                                href="<?php echo base_url(); ?>Attendance/addEditAttendance"><span
                                    class="fa fa-clock-o p-2"></span>+ <?php echo lang('attendance'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="box box-info h-95">
                <div class="box-header">
                    <i data-feather="briefcase"></i>
                    <h3 class="box-title">
                        <?php echo lang('dine'); ?>/<?php echo lang('take_away'); ?>/<?php echo lang('delivery'); ?>(<?php echo lang('this_month'); ?>)
                    </h3>

                </div>
                <div class="box-body">
                    <div class="chart-responsive ir_height260">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-7">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="truck"></i>
                    <h3 class="box-title">
                        <?php echo lang('operational_comparision'); ?>(<?php echo lang('this_month'); ?>)</h3>
                </div>
                <div class="box-body ir_height280">
                    <div class="chart">
                        <div class="chart ir_height250" id="operational_comparision"></div>
                    </div>
                </div>
            </div>
          </div>

        <div class="col-lg-5">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="alert-triangle"></i>
                    <h3 class="box-title"><?php echo lang('ingredients_alert'); ?>/<?php echo lang('low_stock'); ?>
                        <span class="ir_color_red">(<?= getAlertCount() ?>)</span>
                    </h3>
                </div>
                <div class="box-body ir_height280">
                    <ul class="todo-list">
                        <li class="todo-title">
                            <span class="text" class="ir_font_bold"><?php echo lang('ingredient_name'); ?></span>
                            <div class="ir_fl_right_pr_fw">
                                <span><?php echo lang('current_stock'); ?></span>
                            </div>
                        </li>
                    </ul>
                    <ul class="todo-list ir_txt_overflow" id="low_stock_ingredients">
                        <?php
                        $totalStock = 0;
                        if ($low_stock_ingredients && !empty($low_stock_ingredients)) {
                            $i = count($low_stock_ingredients);
                        }
                        foreach ($low_stock_ingredients as $value) {
                            if($value->id):
                            $totalStock = $value->total_purchase - $value->total_consumption - $value->total_modifiers_consumption - $value->total_waste + $value->total_consumption_plus - $value->total_consumption_minus + $value->total_transfer_plus  - $value->total_transfer_minus  +  $value->total_transfer_plus_2  -  $value->total_transfer_minus_2;
                                if ($totalStock <= $value->alert_quantity):
                            ?>
                            <li>
                                <span class="text"><?= escape_output($value->name . "(" . $value->code . ")") ?></span>
                                <div class="ir_fl_right_c_red_pr_5">
                                    <span><?= ($totalStock) ? escape_output(getAmtP($totalStock)) : '0.0' ?></span>
                                </div>
                            </li>
                        <?php
                            endif;
                        endif;
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="coffee"></i>
                    <h3 class="box-title"><?php echo lang('top_ten_food_this_month'); ?></h3>
                </div>
                <div class="box-body ir_height280">
                    <ul class="todo-list">
                        <li class="todo-title">
                            <div class="ir_font_bold ir_fl_left_pl_5">
                                <span><?php echo lang('sn'); ?></span>
                            </div>
                            <span class="text ir_font_bold"><?php echo lang('food_name'); ?></span>
                            <div class="ir_fl_right_pr_fw">
                                <span><?php echo lang('count'); ?></span>
                            </div>
                        </li>
                    </ul>
                    <ul class="todo-list ir_txt_overflow" id="top_ten_food_menu">
                        <?php 
                if ($top_ten_food_menu && !empty($top_ten_food_menu)) { 
                foreach ($top_ten_food_menu as $key => $value) { 
                  $key++;
                    ?>
                        <li>
                            <div class="ir_fl_left_pl_5">
                                <span><?php echo escape_output($key); ?></span>
                            </div>
                            <span class="text"><?php echo escape_output($value->menu_name); ?></span>
                            <div class="ir_fl_right_c_pr_5">
                                <span><?php echo escape_output($value->totalQty); ?></span>
                            </div>
                        </li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="users"></i>
                    <h3 class="box-title"><?php echo lang('top_ten_customers'); ?></h3>
                </div>
                <div class="box-body txt_28">
                    <ul class="todo-list">
                        <li class="todo-title">
                            <div class="ir_font_bold ir_fl_left_pl_5">
                                <span><?php echo lang('sn'); ?></span>
                            </div>
                            <span class="text" class="ir_font_bold"><?php echo lang('customer_name'); ?></span>
                            <div class="ir_fl_right_pr_fw">
                                <span><?php echo lang('sale_amount'); ?></span>
                            </div>
                        </li>
                    </ul>
                    <ul class="todo-list" id="top_ten_customer">
                        <?php 
                if ($top_ten_customer && !empty($top_ten_customer)) { 
                foreach ($top_ten_customer as $key => $value) { 
                  $key++;
                    ?>
                        <li>
                            <div class="ir_fl_left_pl_5">
                                <span><?php echo escape_output($key); ?></span>
                            </div>
                            <span class="text"><?php echo escape_output($value->name); ?></span>
                            <div class="ir_fl_right_c_pr_5">
                                <span><?php echo escape_output(getAmtP($value->total_payable)) ?></span>
                            </div>
                        </li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="dollar-sign"></i>
                    <h3 class="box-title"><?php echo lang('customer_receiveable'); ?></h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        <li class="todo-title">
                            <div class="ir_font_bold ir_fl_left_pl_5">
                                <span><?php echo lang('sn'); ?></span>
                            </div>
                            <span class="text" class="ir_font_bold"><?php echo lang('customer_name'); ?></span>
                            <div class="ir_fl_right_pr_fw">
                                <span><?php echo lang('due_amount'); ?></span>
                            </div>
                        </li>
                    </ul>
                    <ul class="todo-list ir_txt_overflow" id="customer_receivable">
                        <?php 
                if ($customer_receivable && !empty($customer_receivable)) { 
                foreach ($customer_receivable as $key => $value) { 
                  $key++;
                  if($value->due_amount != '0.00' && $value->due_amount != ''){
                    ?>
                        <li>
                            <div class="ir_fl_left_pl_5">
                                <span><?php echo escape_output($key); ?></span>
                            </div>
                            <span class="text"><?php echo escape_output($value->name); ?></span>
                            <div class="ir_fl_right_c_pr_5">
                                <?php $current_due = $value->due_amount - getCustomerDueReceive($value->customer_id); ?>
                                <span><?php echo escape_output(getAmtP($current_due)) ?></span>
                            </div>
                        </li>
                        <?php } }  } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="dollar-sign"></i>
                    <h3 class="box-title"><?php echo lang('supplier_payable'); ?></h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        <li class="todo-title">
                            <div class="ir_font_bold ir_fl_left_pl_5">
                                <span><?php echo lang('sn'); ?></span>
                            </div>
                            <span class="text ir_font_bold"><?php echo lang('supplier_name'); ?></span>
                            <div class="ir_fl_right_pr_fw">
                                <span><?php echo lang('due_amount'); ?></span>
                            </div>
                        </li>
                    </ul>
                    <ul class="todo-list ir_txt_overflow" id="supplier_payable">
                        <?php 
                if ($supplier_payable && !empty($supplier_payable)) { 
                foreach ($supplier_payable as $key => $value) { 
                  $key++;
                  if($value->due != '0.00' && $value->due != ''){
                    ?>
                        <li>
                            <div class="ir_fl_left_pl_5">
                                <span><?php echo escape_output($key); ?></span>
                            </div>
                            <span class="text"><?php echo escape_output($value->name); ?></span>
                            <div class="ir_fl_right_c_pr_5">
                                <?php $current_due = $value->due - getSupplierDuePayment($value->supplier_id); ?>
                                <span><?php echo escape_output(getAmtP($current_due)) ?></span>
                            </div>
                        </li>
                        <?php } }  } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-info">
                <div class="box-header">
                    <i data-feather="briefcase"></i>
                    <h3 class="box-title"><?php echo lang('monthly_sales_comparision'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <div id="chart_div" class="ir_w_100_h_280"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
</script>

<!-- bootstrap datepicker -->
<link rel="stylesheet"
    href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- ChartJS -->
<script src="<?php echo base_url(); ?>assets/bower_components/chart.js/Chart.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/local/loader.js"></script>

<script src="<?php echo base_url(); ?>assets/bower_components/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/dashboard.js"></script>