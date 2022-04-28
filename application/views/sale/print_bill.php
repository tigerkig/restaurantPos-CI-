<?php
//get company information
$getCompanyInfo = getCompanyInfo();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo lang('Invoice_No'); ?>: <?php echo escape_output($sale_object->sale_no); ?></title>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/size_80mm.css" media="all">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/print_bill.css" media="all">

</head>

<body>
    <div id="wrapper">
        <div id="receiptData">

            <div id="receipt-data">
                <div class="text-center">
                    <?php
                    $invoice_logo = $this->session->userdata('invoice_logo');
                    if($invoice_logo):
                        ?>
                        <img src="<?=base_url()?>images/<?=escape_output($invoice_logo)?>">
                        <?php
                    endif;
                    ?>
                    <h3>
                        <?php echo escape_output($this->session->userdata('outlet_name')); ?>
                    </h3>
                    <p>
                        <?php
                    $order_type = '';
                    if($sale_object->order_type == 1){
                        $order_type = 'A';
                    }elseif($sale_object->order_type == 2){
                        $order_type = 'B';
                    }elseif($sale_object->order_type == 3){
                        $order_type = 'C';
                    }
                    ?>
                        <?php echo lang('Bill_No'); ?>: <?= escape_output($order_type.' '.$sale_object->sale_no) ?>
                        <br>
                    </p>
                </div>
               <p><?php echo lang('date'); ?>: <?= escape_output(date($this->session->userdata('date_format'), strtotime($sale_object->sale_date))); ?>
                    <?= escape_output(date('H:i',strtotime($sale_object->order_time))) ?><br>
                    <?php echo lang('Sales_Associate'); ?>: <?php echo escape_output($sale_object->user_name) ?><br>
                    <?php echo lang('customer'); ?>: <b><?php echo escape_output("$sale_object->customer_name"); ?></b>
                   <?= ($sale_object->waiter_name ? "<br>".lang('waiter').": <b>" . escape_output($sale_object->waiter_name)."</b>" : '') ?>
                    <?php if($sale_object->customer_address!=NULL  && $sale_object->customer_address!=""){?>
                    <br /><?php echo lang('address'); ?>: <?php echo escape_output("$sale_object->customer_address"); ?>
                    <?php } ?>
                    <?php if($sale_object->tables_booked){?>
                        <br /><?php echo lang('table'); ?>:
                    <b>
                        <?php
                    foreach ($sale_object->tables_booked as $key1=>$val){
                        echo escape_output($val->table_name);
                        if($key1 < (sizeof($sale_object->tables_booked) -1)){
                            echo ", ";
                        }
                    }
                    ?>
                    </b>
                    <?php } ?>
                </p>
                <div class="ir_clear"></div>
                <table class="table table-condensed">
                    <tbody>
                        <?php
                if (isset($sale_object->items)) {
                    $i = 1;
                    $totalItems = 0;
                    foreach ($sale_object->items as $row) {
                        $totalItems+=$row->qty;
                        $menu_unit_price = getAmtP($row->menu_unit_price);
                        ?>

                        <tr>
                            <td class="no-border border-bottom ir_wid_70"># <?php echo escape_output($i++); ?>:
                                &nbsp;&nbsp;<?php echo escape_output($row->menu_name) ?>
                                <small></small> <?php echo "$row->qty X $menu_unit_price"; ?>
                            </td>
                            <td class="no-border border-bottom text-right">
                                <?php echo escape_output(getAmt($row->menu_price_without_discount)); ?>
                            </td>
                        </tr>
                        <?php if(count($row->modifiers)>0){ ?>
                        <tr>
                            <td class="no-border border-bottom"><?php echo lang('modifier'); ?>:
                                <small></small>
                                <?php
                                    $l = 1;
                                    $modifier_price = 0;
                                    foreach($row->modifiers as $modifier){
                                        if($l==count($row->modifiers)){
                                            echo escape_output($modifier->name);
                                        }else{
                                            echo escape_output($modifier->name).',';
                                        }
                                        $modifier_price+=$modifier->modifier_price;
                                        $l++;
                                    }
                                    ?>
                            </td>
                            <td class="no-border border-bottom text-right">
                                <?php echo escape_output(getAmt($modifier_price)); ?>))</td>
                        </tr>
                        <?php } ?>
                        <?php }
                }
                ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th><?php echo lang('Total_Item_s'); ?>: <?php echo escape_output($totalItems); ?></th>
                            <th class="ir_txt_left"></th>
                        </tr>
                        <tr>
                           <th><?php echo lang('sub_total'); ?></th>
                            <th class="text-right">
                                <?php echo escape_output(getAmt($sale_object->sub_total)); ?>
                            </th>
                        </tr>
                        <?php
                        if($sale_object->total_discount_amount && $sale_object->total_discount_amount!="0.00"):
                        ?>
                        <tr>
                        <th><?php echo lang('Disc_Amt_p'); ?>:</th>
                        <th class="text-right">
                            <?php echo escape_output(getAmt($sale_object->total_discount_amount)); ?>
                        </th>
                        </tr>
                        <?php
                        endif;
                        ?>
                        <?php
                        if($sale_object->delivery_charge && $sale_object->delivery_charge!="0.00" && $sale_object->delivery_charge_actual_charge!="0" && $sale_object->delivery_charge_actual_charge):
                        ?>
                        <tr>
                       <th><?php echo lang('Service_Delivery_Charge'); ?></th>
                        <th class="text-right">
                            <?php echo escape_output((getPlanTextOrP($sale_object->delivery_charge))); ?>
                        </th>
                        </tr>
                        <?php
                        endif;
                        ?>

                        <?php
                if ($this->session->userdata('collect_tax')=='Yes' && $sale_object->sale_vat_objects!=NULL):
                    ?>
                        <?php foreach(json_decode($sale_object->sale_vat_objects) as $single_tax){ ?>
                    <?php
                    if($single_tax->tax_field_amount && $single_tax->tax_field_amount!="0.00"):
                        ?>
                        <tr>
                            <th><?php echo escape_output($single_tax->tax_field_type) ?></th>
                            <th class="text-right">
                                <?php echo escape_output(getAmt($single_tax->tax_field_amount)); ?>
                            </th>
                        </tr>
                        <?php
                        endif;
                        ?>
                        <?php } ?>

                        <?php
                endif;
                ?>
                        <tr>
                          <th><?php echo lang('grand_total'); ?></th>
                            <th class="text-right">
                                <?php echo escape_output(getAmt($sale_object->total_payable)); ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-striped table-condensed">
                    <tbody>
                        <tr>
                            <td><?php echo lang('total_payable'); ?></td>
                            <td class="text-right">
                                <?php echo escape_output(getAmt($sale_object->total_payable)); ?>
                            </td>
                        </tr>
                    </tbody>
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
</body>

</html>