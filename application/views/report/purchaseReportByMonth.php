<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<section class="content-header">
    <h3 class="ir_txtCenter_mt0"><?php echo lang('Monthly_Purchase_Report'); ?></h3>
    <hr class="ir_border1">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/purchaseReportByMonth') ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startMonth" readonly class="form-control datepicker_months"
                    placeholder="<?php echo lang('Start_Month'); ?>" value="<?php echo set_value('startMonth'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="1" type="text" id="endMonth" name="endMonth" readonly
                    class="form-control datepicker_months" placeholder="<?php echo lang('End_Month'); ?>"
                    value="<?php echo set_value('endMonth'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <select tabindex="2" class="form-control select2 ir_w_100" id="user_id" name="user_id">
                    <option value=""><?php echo lang('User'); ?></option>
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
                    class="btn btn-block btn-primary pull-left"><?php echo lang('Submit'); ?></button>
            </div>
        </div>
        <div class="hidden-lg">
            <div class="clearfix"></div>
        </div>
        <div class="col-md-offset-3 col-md-2">
            <div class="form-group">
                <a target="_blank"
                    href="<?= base_url() . 'PdfGenerator/purchaseReportByMonth/' ?><?= isset($start_date) ? $this->custom->encrypt_decrypt($start_date, 'encrypt') : '0'; ?>/<?= isset($end_date) ? $this->custom->encrypt_decrypt($end_date, 'encrypt') : '0'; ?>/<?= isset($user_id) ? $this->custom->encrypt_decrypt($user_id, 'encrypt') : '0'; ?>"
                    class="btn btn-block btn-primary pull-left"><?php echo lang('Export_PDF'); ?></a>
            </div>
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
                    <h3><?php echo lang('Monthly_Purchase_Report'); ?></h3>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <h4 class="ir_txtCenter_mt0"><?php
                    if (isset($user_id) && $user_id):
                        echo lang('user').": <span class='ir_txt_underline'>" . userName($user_id) . "</span>";
                    else:
                        echo lang('User_All');
                    endif;
                    ?></h4>
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('month')?></th>
                                <th><?php echo lang('Total_Purchase')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            if (isset($purchaseReportByMonth)):
                                foreach ($purchaseReportByMonth as $key => $value) {
                                    $grandTotal+=$value->total_payable;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                <td><?php echo escape_output(getAmt($value->total_payable)) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>

                    <table class="table table-responsive">
                        <tr>
                            <td class="title">
                                <h3 class="ir_txt_center"><?php echo lang('Purchase_Value')?>: <?php echo escape_output($grandTotal); ?></h3>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>