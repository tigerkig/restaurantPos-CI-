<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('consumption_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('consumption_report'); ?>" data-id_name="datatable">
    </section>

    <div class="box-wrapper">
        <div class="row">
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                    <?php echo form_open(base_url() . 'Report/consumptionReport') ?>
                    <div class="form-group">
                        <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                            placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                    </div>
                </div>
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">

                    <div class="form-group">
                        <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                            class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                            value="<?php echo set_value('endDate'); ?>">
                    </div>
                </div>
                <?php
                if(isLMni()):
                    ?>
                    <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
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
                <div class="col-sm-12 col-md-4 col-lg-2">
                    <div class="form-group">
                        <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                </div>
        </div>
        <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <h3><?php echo lang('consumption_report_menus'); ?></h3>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ?lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <br>
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('ingredient_name'); ?>(<?php echo lang('code'); ?>)</th>
                                <th><?php echo lang('quantity_amount'); ?></th>
                                <th><?php echo lang('total_cost'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalTK = 0;
                            if (isset($consumptionMenus)):
                                foreach ($consumptionMenus as $key => $value) {
                                    $last_purchase_price = getLastPurchaseAmount($value->ingredient_id);
                                    $totalTK +=$value->total_consumption *  $last_purchase_price;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?php echo escape_output($value->ingredient_name).' '.$value->ingredient_code; ?></td>
                                <td><?php echo escape_output(getAmtP($value->total_consumption)) ?></td>
                                <td><?php echo  escape_output(getAmtP($value->total_consumption * $last_purchase_price)) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="ir_txt_center"></td>
                                <td> </td>
                                <td><?php echo lang('total'); ?></td>
                                <td><?php echo escape_output(getAmtP($totalTK)) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
        </div>
        <div class="table-box">
                    <!-- /.box-header -->
                    <div class="table-responsive">
                        <h3><?php echo lang('consumption_report_modifiers'); ?></h3>
                        <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                        </h4>
                        <br>
                        <table id="datatable2" class="table">
                            <thead>
                                <tr>
                                    <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                    <th><?php echo lang('ingredient_name'); ?>(<?php echo lang('code'); ?>)</th>
                                    <th><?php echo lang('quantity_amount'); ?></th>
                                    <th><?php echo lang('total_cost'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalTK = 0;
                                if (isset($consumptionModifiers)):
                                    foreach ($consumptionModifiers as $key => $value) {
                                        $last_purchase_price = getLastPurchaseAmount($value->ingredient_id);
                                        $totalTK +=$value->total_consumption *  $last_purchase_price;
                                        $key++;
                                        ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->ingredient_name.' '.$value->ingredient_code); ?></td>
                                    <td><?php echo escape_output(getAmtP($value->total_consumption)) ?></td>
                                    <td><?php echo escape_output(getAmtP($value->total_consumption * $last_purchase_price)) ?></td>
                                </tr>
                                <?php
                                    }
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="ir_txt_center"></td>
                                <td> </td>
                                <td><?php echo lang('total'); ?></td>
                                <td><?php echo escape_output(getAmtP($totalTK)) ?></td>
                            </tr>
                            </tfoot>
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
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>


<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>