<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">


<section class="main-content-wrapper">
<section class="content-header">
    <h3 class="top-left-header text-left"><?php echo lang('kitchen_performance_report'); ?></h3>
    <input type="hidden" class="datatable_name" data-title="<?php echo lang('kitchen_performance_report'); ?>" data-id_name="datatable">
    
</section>

    <div class="my-2">
            <?php if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4 class="txt-color-grey"> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4 class="txt-color-grey"><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    
    </div>
    <div class="box-wrapper">
        <div class="row">
            <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                <?php echo form_open(base_url() . 'Report/kitchenPerformanceReport') ?>
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
                  
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('order_number'); ?></th>
                                <th><?php echo lang('type'); ?></th>
                                <th><?php echo lang('order_time'); ?></th>
                                <th><?php echo lang('cooking_start_time'); ?></th>
                                <th><?php echo lang('cooking_end_time'); ?></th>
                                <th><?php echo lang('time_taken'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (isset($kitchenPerformanceReport)):
                                foreach ($kitchenPerformanceReport as $key => $value) { 
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))) ?>
                                </td>
                                <td><?php echo escape_output($value->sale_no) ?></td>
                                <td><?php echo escape_output(getOrderType($value->order_type)) ?></td>
                                <td><?php echo escape_output($value->order_time) ?></td>
                                <td><?php echo escape_output($value->cooking_start_time) ?></td>
                                <td><?php echo escape_output($value->cooking_done_time) ?></td>
                                <td>
                                    <?php 
                                                if($value->cooking_done_time == '0000-00-00 00:00:00'){ 
                                                    echo 'N/A'; 
                                                }else{   
                                                    $cooking_done_time = strtotime($value->cooking_done_time);
                                                    $order_time = strtotime($value->order_time);
                                                    $minute = round(abs($cooking_done_time - $order_time) / 60,2); 
                                                    $hour = round(abs($minute) / 60,2);
                                                    echo escape_output($hour)." ".lang('hour');
                                                }
                                            ?>
                                </td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
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