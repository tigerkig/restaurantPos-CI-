<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<section class="main-content-wrapper">


    <section class="content-header">
        <h3 class="text-left top-left-header"><?php echo lang('profit_loss_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('profit_loss_report'); ?>" data-id_name="datatable">
      
    </section>



    <div class="box-wrapper">
            <div class="row">
                        <div class="col-md-4 col-lg-2 col-sm-12 mb-3">
                            <?php echo form_open(base_url() . 'Report/profitLossReport') ?>
                            <div class="form-group">
                                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                                    placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-2 col-sm-12 mb-3">

                            <div class="form-group">
                                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                                    class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                                    value="<?php echo set_value('endDate'); ?>">
                            </div>
                        </div>
                        <?php
                        if(isLMni()):
                            ?>
                            <div class="col-md-4 col-lg-2 col-sm-12 mb-3">
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
                        <div class="col-md-4 col-lg-2 col-sm-12">
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit"
                                    class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                        </div>
            </div>
            <div class="table-box mt-3">
                <div class="table-responsive">
                    <table class="table" id="datatable">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="ir_w_100 text-center">
                                <h4 class="ir_txt_center">
                                    <?php echo lang('profit_loss_report'); ?>
                                </h4>

                                <?php
                                if(isLMni() && isset($outlet_id)):
                                    ?>
                                    <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?>, <?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?></h4>
                                    <?php
                                else:
                                    ?>
                                    <h4 class="ir_txt_center">
                                        <?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                                    </h4>
                                    <?php
                                endif;
                                ?>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="ir_w_40">
                                    <?php echo lang('profit_1'); ?></td>
                                <td><?= isset($saleReportByDate['profit_1']) ? getAmt($saleReportByDate['profit_1']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td> <?php echo lang('profit_2'); ?></td>
                                <td><?= isset($saleReportByDate['profit_2']) ? getAmt($saleReportByDate['profit_2']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td> <?php echo lang('profit_3'); ?></td>
                                <td><?= isset($saleReportByDate['profit_3']) ? getAmt($saleReportByDate['profit_3']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td> <?php echo lang('profit_4'); ?></td>
                                <td><?= isset($saleReportByDate['profit_4']) ? getAmt($saleReportByDate['profit_4']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr class="profit_txt">
                                <td>5</td>
                                <td> <?php echo lang('profit_5'); ?> (1+2) - (3+4)</td>
                                <td> <?= isset($saleReportByDate['profit_5']) ? getAmt($saleReportByDate['profit_5']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td> <?php echo lang('profit_6'); ?></td>
                                <td><?= isset($saleReportByDate['profit_6']) ? getAmt($saleReportByDate['profit_6']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td> <?php echo lang('profit_7'); ?></td>
                                <td><?= isset($saleReportByDate['profit_7']) ? getAmt($saleReportByDate['profit_7']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td> <?php echo lang('profit_8'); ?></td>
                                <td><?= isset($saleReportByDate['profit_8']) ? getAmt($saleReportByDate['profit_8']) : getAmt(0) ?>
                                </td>
                            </tr>
                            <tr class="profit_txt">
                                <td>9</td>
                                <td> <?php echo lang('profit_9'); ?> (5) - (6+7+8)</td>
                                <td><?= isset($saleReportByDate['profit_9']) ? getAmt($saleReportByDate['profit_9']) : getAmt(0) ?>
                                </td>
                            </tr>
                        </tbody>
                       
                    </table>
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
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/no_pagi_report.js"></script>