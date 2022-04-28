<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<section class="content-header">
    <h3 class="ir_txtCenter_mt0"><?php echo lang('Detailed_Purchase_Report'); ?></h3>
    <hr class="ir_border1">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/detailedPurchaseReport') ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                    <?php echo lang('Start_Date'); ?> value="<?php echo set_value('startDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                    class="form-control customDatepicker" <?php echo lang('End_Date'); ?>
                    value="<?php echo set_value('endDate'); ?>">
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
                    href="<?= base_url() . 'PdfGenerator/detailedPurchaseReport/' ?><?= isset($start_date) && $start_date ? $this->custom->encrypt_decrypt($start_date, 'encrypt') : '0'; ?>/<?= isset($end_date) && $end_date ? $this->custom->encrypt_decrypt($end_date, 'encrypt') : '0'; ?>/<?= isset($user_id) && $user_id ? $this->custom->encrypt_decrypt($user_id, 'encrypt') : '0'; ?>"
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
                    <h3><?php echo lang('Detailed_Purchase_Report'); ?></h3>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <h4 class="ir_txtCenter_mt0"><?php
                    if (isset($user_id) && $user_id):
                        echo "User: " . userName($user_id);
                    else:
                        echo "User: All";
                    endif;
                    ?></h4>
                    <br>
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('ref_no'); ?></th>
                                <th><?php echo lang('paid'); ?></th>
                                <th><?php echo lang('due'); ?></th>
                                <th><?php echo lang('GTotal'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($detailedPurchaseReport) && !empty($detailedPurchaseReport)) {
                                $i = count($detailedPurchaseReport);
                            }
                            $pGrandTotal = 0;
                            $paidGrandTotal = 0;
                            $dueGrandTotal = 0;
                            $vatGrandTotal = 0;
                            if (isset($detailedPurchaseReport)):
                                foreach ($detailedPurchaseReport as $value) {
                                    $pGrandTotal+=$value->grand_total;
                                    $paidGrandTotal+=$value->paid;
                                    $dueGrandTotal+=$value->due;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                <td><?php echo escape_output($value->reference_no) ?></td>
                                <td><?php echo escape_output(getAmt($value->paid)) ?></td>
                                <td><?php echo escape_output(getAmt($value->due)) ?></td>
                                <td><?php echo escape_output(getAmt($value->grand_total)) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="ir_w2_txt_center"></th>
                            <th></th>
                            <th class="ir_txt_right"><?php echo lang('total'); ?> </th>
                            <th><?php echo escape_output(getAmt($paidGrandTotal)); ?></th>
                            <th><?php echo escape_output(getAmt($dueGrandTotal)); ?></th>
                            <th><?php echo escape_output(getAmt($pGrandTotal)); ?></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>