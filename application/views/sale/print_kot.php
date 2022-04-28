<?php 
 $order_number = '';
 $order_type = '';
 if($sale_object->waiter_name){
    $order_type = 'Dine In';
    $order_number = 'A '.$sale_object->sale_no;
 }elseif($sale_object->order_type=='2'){
    $order_type = 'Take Away';
    $order_number = 'B '.$sale_object->sale_no;
 }elseif($sale_object->order_type=='3'){
    $order_type = 'Delivery';
    $order_number = 'C '.$sale_object->sale_no;    
 }



$tables_booked = '';
if(count($sale_object->tables_booked)>0){
    $w = 1;
    foreach($sale_object->tables_booked as $single_table_booked){
        if($w == count($sale_object->tables_booked)){
            $tables_booked .= $single_table_booked->table_name;
        }else{
            $tables_booked .= $single_table_booked->table_name.', ';
        }
        $w++;
    }    
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo lang('Invoice_No'); ?>: <?= escape_output($order_number) ?></title>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/size_80mm.css" media="all">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/print_bill.css" media="all">

</head>

<body>
    <div id="wrapper">
        <div id="receiptData">

            <div id="receipt-data">
                <div class="ir_txt_center">
                    <h3 class="ir_txt_center"><?php echo lang('KOT'); ?></h3>
                  <?php echo lang('Invoice_No'); ?><b>: </b><?= escape_output($order_number) ?><br>
                   <?php echo lang('date'); ?><b>: </b><?= escape_output(date($this->session->userdata('date_format'), strtotime($sale_object->sale_date))); ?>
                    <?= escape_output(date('H:i',strtotime($sale_object->order_time))) ?><br>
                <?php echo lang('customer'); ?><b>: </b>
                    <b><?php echo "$sale_object->customer_name"; ?> <?= escape_output($tables_booked!="" ?lang('table_no').": ".$tables_booked : '') ?><br></b>
                   <?php echo lang('order_type'); ?><b>: </b>
                    <?php echo escape_output($order_type); ?><?= escape_output($sale_object->waiter_name ? lang('waiter').": " . escape_output($sale_object->waiter_name) : '') ?>
                    <br>
                </div>

                <table class="table table-condensed">
                    <thead>
                        <tr class="ir_font_bold">
                            <td class="ir_w_5"><?php echo lang('sn'); ?></td>
                            <td class="ir_w_85"><?php echo lang('item'); ?></td>
                            <td class="ir_w_10 ir_txt_center"><?php echo lang('qty'); ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($sale_object->items)) {
                                $i = 1;
                                $totalItems = 0;
                                foreach ($sale_object->items as $row) {
                                    $totalItems+=$row->tmp_qty;
                                    if($row->tmp_qty):
                                    ?>
                        <tr>
                            <td><?php echo escape_output($i++); ?></td>
                            <td>
                                <?php echo escape_output($row->menu_name) ?> <br>
                                <?php
                                                $modifiers_name = '';
                                                $j=1;
                                                if(count($row->modifiers)>0){
                                                    foreach($row->modifiers as $single_modifier){
                                                        if($j==count($row->modifiers)){
                                                            $modifiers_name .= escape_output($single_modifier->name);
                                                        }else{
                                                            $modifiers_name .= escape_output($single_modifier->name).',';
                                                        }
                                                        $j++;    
                                                    }
                                                    
                                                } 
                                            ?>
                                <?php if($row->modifiers){ echo lang('modifiers').":". $row->modifiers."<br>";}?>
                                <?php if($row->menu_note!=""){ echo lang('note').":".escape_output($row->menu_note); }?>
                            </td>
                            <td class="ir_txt_center"><?php echo escape_output($row->tmp_qty) ?> </td>
                        </tr>
                        <?php endif; }} ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="ir_txt_center" colspan="6"><?php echo lang('Total_Item_s'); ?>: <?php echo escape_output($totalItems); ?></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <div class="ir_clear"></div>
        </div>

        <div id="buttons"  class="no-print ir_pt_tr">
            <hr>
            <span class="pull-right col-xs-12">
                <button onclick="window.print();" class="btn btn-block btn-primary"><?php echo lang('print'); ?></button> </span>
            <div class="ir_clear"></div>
            <div class="col-xs-12 ir_bg_p_c_red">
                <p class="ir_font_txt_transform_none">
                    Please follow these steps before you print for first time:
                </p>
                <p class="ir_font_capitalize">
                    1. Disable Header and Footer in browser's print setting<br>
                    For Firefox: File &gt; Page Setup &gt; Margins &amp; Header/Footer &gt; Headers & Footers &gt; Make
                    all --blank--<br>
                    For Chrome: Menu &gt; Print &gt; Uncheck Header/Footer in More Options
                </p>
            </div>
            <div class="ir_clear"></div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/dist/js/print/jquery-2.0.3.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/print/custom.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/print_on_load.js"></script>
</body>

</html>