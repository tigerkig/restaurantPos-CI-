<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/foodMenuSales.css">


<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h3 class="top-left-header text-left"><?php echo lang('foodTransferReport'); ?></h3>
                    <section class="content-header">
                        <input type="hidden" class="datatable_name" data-title="<?php echo lang('foodTransferReport'); ?>" data-id_name="datatable">

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
                                <?php echo form_open(base_url() . 'Report/foodTransferReport', $arrayName = array('id' => 'foodMenuSales')) ?>
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
                                <div class="form-group">
                                    <select tabindex="2" class="form-control select2" id="from_outlet_id" name="from_outlet_id">
                                        <option value=""><?php echo lang('SendingOutlet')?></option>
                                        <?php
                                        $outlets = getAllOutlestByAssign();
                                        foreach ($outlets as $value):
                                            ?>
                                            <option <?= set_select('from_outlet_id',$value->id)?>  value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                                <div class="form-group">
                                    <select tabindex="2" class="form-control select2" id="to_outlet_id" name="to_outlet_id">
                                        <option value=""><?php echo lang('ReceivingOutlet')?></option>
                                        <?php
                                        $outlets = getAllOutlestByAssign();
                                        foreach ($outlets as $value):
                                            ?>
                                            <option <?= set_select('to_outlet_id',$value->id)?>  value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
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
                                        <th><?php echo lang('received_date'); ?></th>
                                        <th><?php echo lang('SendingOutlet'); ?></th>
                                        <th><?php echo lang('ReceivingOutlet'); ?></th>
                                        <th><?php echo lang('Foods'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (isset($foodTransferReport)):
                                        foreach ($foodTransferReport as $key => $value) {
                                            $key++;
                                            ?>
                                            <tr>
                                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->received_date))) ?></td>
                                                <td><?php echo escape_output(getOutletNameById($value->from_outlet_id)) ?></td>
                                                <td><?php echo escape_output(getOutletNameById($value->to_outlet_id)) ?></td>
                                                <!--this variable contains html content-->
                                                <td><?php echo ($value->foods) ?></td>
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
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>