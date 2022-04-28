<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="select" value="<?php echo lang('select'); ?>">
<input type="hidden" id="consumption_amount" value="<?php echo lang('consumption_amount'); ?>">
<input type="hidden" id="responsible_person_field_required" value="<?php echo lang('responsible_person_field_required'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="note_field_cannot" value="<?php echo lang('note_field_cannot'); ?>">
<input type="hidden" id="consumption_ingredients" value="<?php echo isset($consumption_ingredients) && $consumption_ingredients?$consumption_ingredients:0 ?>">

<script src="<?php echo base_url(); ?>frequent_changing/js/inventory_adjustment.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/addInventoryAdjustment.css">


<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_inventory_Adjustment'); ?>
        </h3>
    </section>


        <div class="box-wrapper">
        
            <div class="table-box">
                <?php echo form_open(base_url() . 'Inventory_adjustment/addEditInventoryAdjustment', $arrayName = array('id' => 'consumption_form')) ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                    class="form-control" placeholder="Reference No"
                                    value="<?php echo escape_output($reference_no); ?>">
                            </div>
                            <?php if (form_error('reference_no')) { ?>
                            <div class="callout callout-danger error-msg my-2">
                                <?php echo form_error('reference_no'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger error-msg my-2 name_err_msg_contnr">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" id="date" name="date" readonly class="form-control"
                                    placeholder="<?php echo lang('date'); ?>" value="<?php echo date("Y-m-d",strtotime('today')); ?>">
                            </div>
                            <?php if (form_error('date')) { ?>
                            <div class="callout callout-danger error-msg my-2">
                                <?php echo form_error('date'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger error-msg my-2 date_err_msg_contnr">
                                <p id="date_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">

                            <div class="form-group">
                                <label><?php echo lang('responsible_person'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="4" class="form-control ir_w_100 select2 select2-hidden-accessible"
                                    name="employee_id" id="employee_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($employees as $empls) { ?>
                                    <option value="<?php echo escape_output($empls->id) ?>"
                                        <?php echo set_select('employee_id', $empls->id); ?>>
                                        <?php echo escape_output($empls->full_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('employee_id')) { ?>
                            <div class="callout callout-danger error-msg my-2">
                                <?php echo form_error('employee_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger error-msg my-2 employee_id_err_msg_contnr">
                                <p id="employee_id_err_msg"></p>
                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2 col-lg-4">

                            <div class="form-group">
                                <label><?php echo lang('ingredients'); ?> <span class="required_star">*</span></label>
                                <select tabindex="4" class="w-100 form-control select2" name="ingredient_id"
                                    id="ingredient_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php
                                    $ignoreID = array();
                                    foreach ($ingredients as $ingnts) {
                                        if (!in_array($ingnts->id, $ignoreID)) {
                                            $ignoreID[] = $ingnts->id;
                                            ?>
                                    <option
                                        value="<?php echo escape_output($ingnts->id . "|" . $ingnts->name . " (" . $ingnts->code . ")|" . $ingnts->unit_name . "|" . getLastPurchasePrice($ingnts->id)) ?>"
                                        <?php echo set_select('unit_id', $ingnts->id); ?>>
                                        <?php echo escape_output($ingnts->name . " (" . $ingnts->code . ")") ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php if (form_error('ingredient_id')) { ?>
                            <div class="callout callout-danger error-msg my-2">
                                <?php echo form_error('ingredient_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger error-msg my-2 ingredient_id_err_msg_contnr">
                                <p id="ingredient_id_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <div class="hidden-xs hidden-sm mt-2">&nbsp;</div>
                            <a class="btn bg-red-btn" data-bs-toggle="modal"
                                data-bs-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="consumption_cart">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th>
                                                <?php echo lang('ingredient'); ?>(<?php echo lang('code'); ?>)</th>
                                            <th><?php echo lang('quantity_amount'); ?></th>
                                            <th><?php echo lang('consumption_status'); ?></th>
                                            <th><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="3" class="form-control" rows="2" id="note" name="note"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('note')); ?></textarea>
                            </div>
                            <?php if (form_error('note')) { ?>
                            <div class="callout callout-danger error-msg my-2">
                                <?php echo form_error('note'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger error-msg my-2 note_err_msg_contnr">
                                <p id="note_err_msg"></p>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="suffix_hidden_field" id="suffix_hidden_field" />
                    <div class="box-footer mt-3 p-0">
                        <div class="row">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit"
                                class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Inventory_adjustment/inventoryAdjustments">
                                <?php echo lang('back'); ?>
                            </a>
                            </div>
                        </div>
                            
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="noticeModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Notice</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true"><i data-feather="x"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 hidden-lg hidden-sm">
                                <p class="foodMenuCartNotice">
                                    <strong class="ir_ml39"><?php echo lang('notice'); ?></strong><br>
                                    <?php echo lang('notice_text_1'); ?>
                                </p>
                            </div>
                            <div class="col-md-12 hidden-xs hidden-sm">
                                <p class="foodMenuCartNotice">
                                    <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                    <?php echo lang('notice_text_1'); ?>
                                </p>
                            </div>
                            <div class="col-md-12 hidden-xs hidden-lg">
                                <p class="foodMenuCartNotice">
                                    <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                    <?php echo lang('notice_text_1'); ?>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <p class="foodMenuCartInfo">
                                    <a class="ir_font_bold" href="#"
                                        target="_blank"><?php echo lang('click_here'); ?></a>
                                    <?php echo lang('notice_text_2'); ?>
                                    <br>
                                    <br>
                                    <?php echo lang('notice_text_3'); ?>
                                    <br>
                                    <span class="ir_font_bold"><?php echo lang('notice_text_4'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</section>