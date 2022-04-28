<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<section class="main-content-wrapper">
  
    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('daily_sale_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if(isLMni() && isset($outlet_id)): ?>
            <h4 class="txt-color-grey"> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
        <?php endif; ?>
        <h4 class="txt-color-grey ir_txtCenter_mt0">
        <?php if (isset($user_id) && $user_id):
            echo lang('user').": " . userName($user_id) . "</span>";
        endif; ?></h4>
        <h4 class="txt-color-grey"><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ?lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
        </h4>
    </div>

    <div class="box-wrapper">
            <div class="row my-2">
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                    <?php echo form_open(base_url() . 'Report/saleReportByDate') ?>
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
                <div class="col-sm-12 mb-3 col-md-4 col-lg-2">

                    <div class="form-group">
                        <select tabindex="2" class="form-control select2 ir_w_100" id="user_id" name="user_id">
                            <option value=""><?php echo lang('user'); ?></option>
                            <option value="<?= escape_output($this->session->userdata['user_id']); ?>">
                                <?= escape_output($this->session->userdata['full_name']); ?></option>
                            <?php
                            foreach ($users as $value) {
                                ?>
                            <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('user_id', $value->id); ?>>
                                <?php echo escape_output($value->full_name) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php if(isLMni()): ?>
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
                <?php endif; ?>
                <div class="col-sm-12 col-md-4 col-lg-2">
                    <div class="form-group">
                        <button type="submit" name="submit" value="submit"
                            class="btn w-100 bg-blue-btn"><?php echo lang('submit'); ?></button>
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
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('total_sale'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            if (isset($saleReportByDate)):
                                foreach ($saleReportByDate as $key => $value) {
                                    $grandTotal+=$value->total_payable;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))) ?>
                                </td>
                                <td><?php echo escape_output(getAmt($value->total_payable)) ?></td>
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