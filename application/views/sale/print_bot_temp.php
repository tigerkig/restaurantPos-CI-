<?php
// $order_number = '';
// if($sale_object->waiter_name){
//    $order_number = 'A '.$sale_object->sale_no;
// }elseif($sale_object->order_type=='2'){
//    $order_number = 'B '.$sale_object->sale_no;
// }elseif($sale_object->order_type=='3'){
//    $order_number = 'C '.$sale_object->sale_no;
// }
$kot_info = $temp_kot_info->temp_kot_info;
$kot_info = json_decode($kot_info);
// echo "<pre>";var_dump($kot_info);echo "</pre>";
// exit;
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo lang('Invoice_No'); ?>: <?php echo escape_output($kot_info->order_number); ?></title>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/size_80mm.css" media="all">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/print_bill.css" media="all">

    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


</head>

<body>
    <div id="wrapper">
        <div id="receiptData">

            <div id="receipt-data">
                <div class="ir_txt_center">
                    <h3><?php echo lang('BOT'); ?></h3>
                  <?php echo lang('Invoice_No'); ?><b>:</b> <?php echo escape_output($kot_info->order_number); ?><br>
                   <?php echo lang('date'); ?><b>:</b> <?= escape_output(date($this->session->userdata('date_format'), strtotime($kot_info->order_date))); ?><br>
                <?php echo lang('customer'); ?><b>: </b>
                    <b><?php echo escape_output("$kot_info->customer_name"); ?> <?= isset($kot_info->table_name) && $kot_info->table_name ?lang('table_no').": ".$kot_info->table_name : '' ?><br></b>
                   <?php echo lang('waiter'); ?><b>:</b> <?php echo escape_output("$kot_info->waiter_name"); ?>, <?php echo lang('order_type'); ?><b>: </b>
                    <?php echo "$kot_info->order_type"; ?><br>
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
                if (isset($kot_info->items)) {
                    $i = 1;
                    $totalItems = 0;
                    foreach ($kot_info->items as $row) {
                        $totalItems+=$row->tmp_qty;
                        if($row->tmp_qty):
                            ?>
                        <tr>
                            <td><?php echo escape_output($i++); ?></td>
                            <td>
                                <?php echo escape_output($row->bot_item_name) ?> <br>
                                <?php if($row->modifiers){ echo lang('modifiers').":". $row->modifiers."<br>";}?>
                            </td>
                            <td class="ir_txt_center"><?php echo escape_output($row->tmp_qty) ?> </td>
                        </tr>
                        <?php
                        endif;
                    }
                }
                ?>
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
    <script type="text/javascript">
    $(window).load(function() {
        window.print();
        return false;
    });
    </script>
</body>

</html>