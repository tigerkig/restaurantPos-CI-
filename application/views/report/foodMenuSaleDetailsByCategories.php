<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/foodMenuSales.css">

<section class="content-header">
    <h3 class="ir_txtCenter_mt0"><?php echo lang('foodMenuSaleDetailsByCategories'); ?></h3>
    <input type="hidden" class="datatable_name" data-title="<?php echo lang('food_sales_report'); ?>" data-id_name="datatable">
    <hr class="ir_border1">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/foodMenuSaleDetailsByCategories', $arrayName = array('id' => 'foodMenuSales')) ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                       placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                       class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                       value="<?php echo set_value('endDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">
            <select name="cat_id" class="form-control select2">
                <option value=""><?php echo lang('category'); ?></option>
                <?php foreach ($foodMenuCategories as $ctry) { ?>
                    <option value="<?php echo escape_output($ctry->id) ?>" <?php echo set_select('cat_id', $ctry->id); ?>>
                        <?php echo escape_output($ctry->category_name) ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        if(isLMni()):
            ?>
            <div class="col-md-2">
                <div class="form-group">
                    <select tabindex="2" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                        <?php
                        $outlets = getAllOutlestByAssign();
                        foreach ($outlets as $value):
                            ?>
                            <option <?= set_select('outlet_id',$value->id)?>  value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            <?php
        endif;
        ?>
        <div class="col-md-1">
            <div class="form-group">
                <button type="submit" name="submit" value="submit"
                        class="btn btn-block btn-primary pull-left"><?php echo lang('submit'); ?></button>
            </div>
        </div>
        <div class="hidden-lg">
            <div class="clearfix"></div>
        </div>
    </div>
</section>

<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h3><?php echo lang('food_sales_report'); ?></h3>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <br>
                    <table id="datatable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('code'); ?></th>
                            <th><?php echo lang('food_menu'); ?>(<?php echo lang('code'); ?>)</th>
                            <th><?php echo lang('category_name'); ?></th>
                            <th><?php echo lang('sale_price'); ?></th>
                            <th><?php echo lang('quantity'); ?></th>
                            <th><?php echo lang('current_stock'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($foodMenuSales)):
                            foreach ($foodMenuSales as $key => $value) {
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->code) ?></td>
                                    <td><?php echo escape_output($value->menu_name) ?></td>
                                    <td><?php echo escape_output($value->category_name) ?></td>
                                    <td><?php echo escape_output($value->sale_price) ?></td>
                                    <td><?php echo escape_output($value->totalQty) ?></td>
                                    <td><?php echo escape_output($value->totalQty) ?></td>
                                </tr>
                                <?php
                            }
                        endif;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('code'); ?></th>
                            <th><?php echo lang('food_menu'); ?>(<?php echo lang('code'); ?>)</th>
                            <th><?php echo lang('category_name'); ?></th>
                            <th><?php echo lang('sale_price'); ?></th>
                            <th><?php echo lang('quantity'); ?></th>
                            <th><?php echo lang('current_stock'); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>

<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatable_custom/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatable_custom/buttons.dataTables.min.css">
<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>