<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('purchase_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-id_name="datatable">
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
    </div>

    <div class="box-wrapper">

        <div class="row mb-3">
            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                <?php echo form_open(base_url() . 'Report/purchaseReportByDate') ?>
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
                    
                        <table id="datatable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                    <th class="ir_w_10"><?php echo lang('ref_no'); ?></th>
                                    <th class="ir_w_5"><?php echo lang('date'); ?></th>
                                    <th class="ir_w_10"><?php echo lang('supplier'); ?></th>
                                    <th class="ir_w_12"><?php echo lang('grand_total'); ?></th>
                                    <th class="ir_w_7"><?php echo lang('paid'); ?></th>
                                    <th class="ir_w_7"><?php echo lang('due'); ?></th>
                                    <th class="ir_w_32"><?php echo lang('ingredients'); ?></th>
                                    <th class="ir_w_15"><?php echo lang('purchased_by'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sum_of_grand_total = 0;
                                $sum_of_paid = 0;
                                $sum_of_due = 0;

                                if (isset($purchaseReportByDate)):
                                    foreach ($purchaseReportByDate as $key => $value) { 
                                        $sum_of_grand_total += $value->grand_total;
                                        $sum_of_paid += $value->paid;
                                        $sum_of_due += $value->due;
                                        $key++;
                                        ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->reference_no) ?></td>
                                    <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                    <td><?php echo escape_output(getSupplierNameById($value->supplier_id)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->grand_total)) ?></td>
                                    <td><?php echo escape_output(getAmt($value->paid)) ?></td>
                                    <td><?php echo escape_output(getAmt($value->due)) ?></td>
                                    <td><?php print_r(getPurchaseIngredients($value->id)) ?></td>
                                    <td><?php echo escape_output(userName($value->user_id)) ?></td>
                                </tr>
                                <?php
                                    }
                                endif;
                                ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="ir_txt_right"><?php echo lang('total'); ?> </td>
                                    <td><?php echo escape_output(getAmt($sum_of_grand_total)); ?></td>
                                    <td><?php echo escape_output(getAmt($sum_of_paid)); ?></td>
                                    <td><?php echo escape_output(getAmt($sum_of_due)); ?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
        </div>
    </div>


    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <?php echo lang('purchase_report'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i data-feather="x"></i></button>
            </div>
            <div class="modal-body">
               
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
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>