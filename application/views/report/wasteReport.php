<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<section class="main-content-wrapper">
   
    <section class="content-header">
        <h3 class="text-left top-left-header"><?php echo lang('waste_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('waste_report'); ?>" data-id_name="datatable">
    </section>

    <div class="my-2">

            <?php
                        if(isLMni() && isset($outlet_id)):
                            ?>
                            <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                            <?php
                        endif;
                        ?>
                        <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                        </h4>
                        <h4 class="ir_txtCenter_mt0"><?php
                        if (isset($user_id) && $user_id):
                            echo lang('user').": <span class='ir_txt_underline'>" . userName($user_id) . "</span>";
                        else:
                            echo lang('user').": ".lang('all');
                        endif;
                        ?></h4>
                        
    </div>

    <div class="box-wrapper">
            <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                            <?php echo form_open(base_url() . 'Report/wasteReport') ?>
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
                        <?php
                        if(isLMni()):
                            ?>
                            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
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
                        <div class="col-sm-12 col-md-3 col-lg-2">
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
                                <th class="ir_w_11"><?php echo lang('reference_no'); ?></th>
                                <th class="ir_w_8"><?php echo lang('date'); ?></th>
                                <th class="ir_w_9"><?php echo lang('total_loss'); ?></th>
                                <th class="ir_w_13"><?php echo lang('ingredient_count'); ?></th>
                                <th class="ir_w_15"><?php echo lang('responsible_person'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            $countTotal = 0;
                            if (isset($wasteReport)):
                                foreach ($wasteReport as $key => $value) {
                                    $grandTotal+=$value->total_loss;
                                    $key++;
                                    $countTotal+=ingredientCount($value->id);
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?php echo escape_output($value->reference_no) ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                <td><?php echo escape_output(getAmt($value->total_loss)) ?></td>
                                <td><?php echo escape_output(ingredientCount($value->id)); ?></td>
                                <td><?php echo escape_output($value->EmployeedName) ?></td>
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