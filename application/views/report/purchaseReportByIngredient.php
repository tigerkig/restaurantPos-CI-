<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<section class="content-header">
    <h3 class="ir_txtCenter_mt0"><?php echo lang('Ingredient_Purchases_Report'); ?></h3>
    <hr class="ir_border1">
    <div class="row">
        <div class="col-md-2">
            <?php echo form_open(base_url() . 'Report/purchaseReportByIngredient', $arrayName = array('id' => 'purchaseReportByIngredient')) ?>
            <div class="form-group">
                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                    placeholder="<?php echo lang('Start_Date'); ?>" value="<?php echo set_value('startDate'); ?>">
            </div>
        </div>
        <div class="col-md-2">

            <div class="form-group">
                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                    class="form-control customDatepicker" placeholder="<?php echo lang('End_Date'); ?>"
                    value="<?php echo set_value('endDate'); ?>">
            </div>
        </div>
        <div class="col-md-3">

            <div class="form-group">
                <select tabindex="2" class="form-control select2 ir_w_100" id="ingredients_id" name="ingredients_id">
                    <option value=""><?php echo lang('ingredients'); ?></option>
                    <?php
                    foreach ($ingredients as $value) {
                        ?>
                    <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('ingredients_id', $value->id); ?>>
                        <?php echo substr(ucwords(strtolower($value->name)), 0, 50) . " <span>(" . $value->code . ")</span>"; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="callout callout-danger my-2 error-msg ingredients_id_err_msg_contnr">
                <p id="ingredients_id_err_msg"></p>
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
        <div class="col-md-offset-2 col-md-2">
            <div class="form-group">
                <a target="_blank"
                    href="<?= base_url() . 'PdfGenerator/purchaseReportByIngredient/' ?><?= isset($start_date) && $start_date ? $this->custom->encrypt_decrypt($start_date, 'encrypt') : '0'; ?>/<?= isset($end_date) && $end_date ? $this->custom->encrypt_decrypt($end_date, 'encrypt') : '0'; ?>/<?= isset($ingredients_id) && $ingredients_id ? $this->custom->encrypt_decrypt($ingredients_id, 'encrypt') : '0'; ?>"
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
                    <h3><?php echo lang('Ingredient_Purchases_Report'); ?></h3>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4 class="ir_txtCenter_mt0"><?php
                    if (isset($ingredients_id) && $ingredients_id):
                        echo "Ingredient: " . (substr(ucwords(strtolower(getIngredientNameById($ingredients_id))), 0, 50)) . getIngredientCodeById($ingredients_id) . "</span>";
                    endif;
                    ?></h4>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <br>
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('Ingredient_Code'); ?></th>
                                <th><?php echo lang('qty'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pGrandTotal = 0;
                            if (isset($purchaseReportByIngredient)):
                                foreach ($purchaseReportByIngredient as $key => $value) {
                                    $pGrandTotal+=$value->totalQuantity_amount;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                <td><?php echo escape_output($value->name) . "(" . $value->code . ")" ?></td>
                                <td><?php echo escape_output($value->totalQuantity_amount) ?></td>
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
                            <th><?php echo escape_output($pGrandTotal); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>