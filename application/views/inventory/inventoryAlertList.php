
<section class="main-content-wrapper">
<section class="content-header">
    <div class="col-md-12">
        <h3 class="top-left-header text-left"><?php echo lang('Alert_Inventory'); ?> </h3>
    </div>
    <div class="row">
        <div class="col-md-1">
            <a href="<?= base_url() . 'Inventory/index' ?>"
                class="btn bg-blue-btn m-right btn_list"><strong><?php echo lang('back'); ?></strong></a>
        </div>
        <div class="hidden-lg"><span class="ir_f_c">hidden text</span></div>
    </div>

</section>

    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <input type="hidden" class="datatable_name" data-title="<?php echo lang('Alert_Inventory'); ?>" data-id_name="datatable">
                <div class="box-body table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="title ir_w_5"><?php echo lang('sn'); ?></th>
                                <th class="title ir_w_37"><?php echo lang('Ingredient_Code'); ?></th>
                                <th class="title ir_w_20"><?php echo lang('category'); ?></th>
                                <th class="title ir_w_20"><?php echo lang('Stock_Amount'); ?></th>
                                <th class="title ir_w_20"><?php echo lang('Alert_Amount'); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                        $totalStock = 0;
                        $grandTotal = 0;
                        $totalTK = 0;
                        $i = 1;
                        if (!empty($inventory) && isset($inventory)):
                            foreach ($inventory as $key => $value):
                            if($value->id):
                                $totalStock = $value->total_purchase - $value->total_consumption - $value->total_modifiers_consumption - $value->total_waste + $value->total_consumption_plus - $value->total_consumption_minus + $value->total_transfer_plus  - $value->total_transfer_minus  +  $value->total_transfer_plus_2  -  $value->total_transfer_minus_2;
                                $totalTK = $totalStock * getLastPurchaseAmount($value->id);
                                if ($totalStock <= $value->alert_quantity):
                                    if ($totalStock >= 0) {
                                        $grandTotal = $grandTotal + $totalStock * getLastPurchaseAmount($value->id);
                                    }
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i); ?></td>
                                <td><?= escape_output($value->name . "(" . $value->code . ")") ?></td>
                                <td><?php echo escape_output($value->category_name); ?></td>
                                <td><span
                                        style="<?= ($totalStock <= $value->alert_quantity) ? 'color:red' : '' ?>"><?= ($totalStock) ? getAmtP($totalStock) : getAmtP(0) ?><?= " " . escape_output($value->unit_name) ?></span>
                                </td>
                                <td><?= escape_output(getAmtP($value->alert_quantity) . " " . escape_output($value->unit_name)) ?></td>
                            </tr>
                            <?php
                                    $i++;
                                endif;
                                endif;
                            endforeach;
                        endif;
                        ?>
                        </tbody>
                    </table>
                    <input type="hidden" value="<?php echo escape_output(getAmtP($grandTotal)); ?>" id="grandTotal" name="grandTotal">
                </div>
                <!-- /.box-body -->
            </div>
    </div>
   
</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/inventory_alert.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>


<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>