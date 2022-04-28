<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/foodMenuSales.css">


<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h3 class="top-left-header text-left"><?php echo lang('foodMenuSaleByCategories'); ?></h3>
    <section class="content-header">
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('food_sales_report'); ?>" data-id_name="datatable">
        
    </section>

    <div class="my-2">
        <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
    </div>

        <div class="box-wrapper">
                <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                            <?php echo form_open(base_url() . 'Report/foodMenuSaleByCategories', $arrayName = array('id' => 'foodMenuSales')) ?>
                            <div class="form-group">
                                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                                    placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-2 mb-3">

                            <div class="form-group">
                                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                                    class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                                    value="<?php echo set_value('endDate'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
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
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                                <div class="form-group">
                                    <select tabindex="2" class="form-control select2" id="outlet_id" name="outlet_id">
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
                        <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit"
                                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                        </div>
            </div>
            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                   
                    
                    <table id="datatable" class="table">
                        <thead>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('code'); ?></th>
                            <th><?php echo lang('food_menu'); ?>(<?php echo lang('code'); ?>)</th>
                            <th><?php echo lang('category_name'); ?></th>
                            <th><?php echo lang('quantity'); ?></th>
                            <th><?php echo lang('SalesValue'); ?></th>
                            <th><?php echo lang('AveSellingPrice'); ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_amount = 0;
                        $total_qty = 0;
                        $total_average = 0;
                        if (isset($foodMenuSales)):
                            foreach ($foodMenuSales as $key => $value) {
                                $total_amount+=($value->menu_unit_price*$value->totalQty);
                                $total_qty+=($value->totalQty);
                                $total_average+=(($value->menu_unit_price*$value->totalQty)/$value->totalQty);
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->code) ?></td>
                                    <td><?php echo escape_output($value->menu_name) ?></td>
                                    <td><?php echo escape_output($value->category_name) ?></td>
                                    <td><?php echo escape_output($value->totalQty) ?></td>
                                    <td><?php echo escape_output(getAmtP($value->menu_unit_price * $value->totalQty)) ?></td>
                                    <td><?php echo escape_output(getAmtP(($value->menu_unit_price*$value->totalQty)/$value->totalQty)) ?></td>
                                </tr>
                                <?php
                            }
                        endif;
                        ?>
                        </tbody>
                        <tr>
                            <th class="ir_w2_txt_center"></th>
                            <th></th>
                            <th></th>
                            <th><?php echo lang('total'); ?>(Amount X Qty)</th>
                            <th><?php echo escape_output($total_qty) ?></th>
                            <th><?php echo escape_output(getAmtP($total_amount)) ?></th>
                            <th><?php echo escape_output(getAmtP($total_average)) ?></th>

                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
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
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>