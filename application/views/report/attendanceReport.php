<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">



<section class="main-content-wrapper">
        
    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('attendance_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('attendance_report'); ?>" data-id_name="datatable">
        
    </section>
    <div class="my-2">
            <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <h4 class="ir_txtCenter_mt0"><?php
                    if (isset($employee_id) && ($employee_id)){
                        echo lang('employee').": <span class='ir_txt_underline'>" . employeeName($employee_id) . "</span>";
                    } 
                    ?></h4>
    </div>
    <div class="box-wrapper">
        <div class="row mb-2">
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">
                <?php echo form_open(base_url() . 'Report/attendanceReport') ?>
                <div class="form-group">
                    <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                        placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                </div>
            </div>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">

                <div class="form-group">
                    <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                        class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                        value="<?php echo set_value('endDate'); ?>">
                </div>
            </div>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">

                <div class="form-group">
                    <select tabindex="2" class="form-control select2 ir_w_100" id="employee_id" name="employee_id">
                        <option value=""><?php echo lang('employee'); ?></option>
                        <?php
                        foreach ($employees as $value) {
                            ?>
                        <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('employee_id', $value->id); ?>>
                            <?php echo escape_output($value->full_name) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">
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
                                <th class="ir_w_11"><?php echo lang('ref_no'); ?></th>
                                <th class="ir_w_9"><?php echo lang('date'); ?></th>
                                <th class="ir_w_18"><?php echo lang('employee'); ?></th>
                                <th class="ir_w_10"><?php echo lang('in_time'); ?></th>
                                <th class="ir_w_10"><?php echo lang('out_time'); ?></th>
                                <th class="ir_w_14"><?php echo lang('time_count'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_hours = 0;
                            if (!empty($attendanceReport)) {
                                $i = count($attendanceReport); 
                            foreach ($attendanceReport as $value) { 

                                ?>
                            <tr>
                                <td><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($value->reference_no) ?></td>
                                <td><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($value->date))); ?>
                                </td>
                                <td><?php echo escape_output(employeeName($value->employee_id)); ?></td>
                                <td><?php echo escape_output($value->in_time) ?></td>
                                <td>
                                    <?php 
                                        if($value->out_time == '00:00:00'){ 
                                            echo 'N/A<br>';  
                                        }else{ 
                                            echo escape_output($value->out_time);
                                        } 
                                        ?>
                                </td>
                                <td>
                                    <?php  
                                        if($value->out_time == '00:00:00'){ 
                                            echo 'N/A'; 
                                        }else{ 
                                            $to_time = strtotime($value->out_time);
                                            $from_time = strtotime($value->in_time);
                                            $minute = round(abs($to_time - $from_time) / 60,2); 
                                            $hour = round(abs($minute) / 60,2);
                                            echo escape_output($hour)." ".lang('hour');
                                            $total_hours += $hour;
                                        }

                                        ?>
                                </td>
                            </tr>
                            <?php
                            } }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b><?php echo lang('total'); ?> <?php echo lang('hours'); ?></b></td>
                                <td><?php echo escape_output($total_hours) . " ".lang('hours'); ?></td>
                            </tr>
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
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
</script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>