<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="select" value="<?php echo lang('select'); ?>">
<input type="hidden" id="consumption_amount" value="<?php echo lang('consumption_amount'); ?>">
<input type="hidden" id="responsible_person_field_required" value="<?php echo lang('responsible_person_field_required'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="note_field_cannot" value="<?php echo lang('note_field_cannot'); ?>">

<script src="<?php echo base_url(); ?>frequent_changing/js/edit_inventory_adjustment.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/editInventoryAdjustment.css">



<section class="main-content-wrapper">

    <section class="content-header">
        <h2 class="top-left-header">
            <?php echo lang('edit_inventory_Adjustment'); ?>
        </h2>
    </section>


    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <?php echo form_open(base_url() . 'Inventory_adjustment/addEditInventoryAdjustment/' . $encrypted_id, $arrayName = array('id' => 'consumption_form')) ?>
                <div>
                    <div class="row">

                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                    class="form-control" placeholder="Reference No"
                                    value="<?php echo escape_output($inventory_adjustment_details->reference_no) ?>">
                            </div>
                            <?php if (form_error('reference_no')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('reference_no'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg name_err_msg_contnr">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" id="date" name="date" class="form-control"
                                    placeholder="<?php echo lang('date'); ?>" readonly
                                    value="<?php echo date('Y-m-d', strtotime($inventory_adjustment_details->date)); ?>">
                            </div>
                            <?php if (form_error('date')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('date'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg date_err_msg_contnr">
                                <p id="date_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">

                            <div class="form-group">
                                <label><?php echo lang('responsible_person'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="employee_id" id="employee_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($employees as $empls) { ?>
                                    <option value="<?php echo escape_output($empls->id) ?>" <?php
                                        if ($inventory_adjustment_details->employee_id == $empls->id) {
                                            echo "selected";
                                        }
                                        ?>><?php echo escape_output($empls->full_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('employee_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('employee_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg employee_id_err_msg_contnr">
                                <p id="employee_id_err_msg"></p>
                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-2">
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
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('ingredient_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg ingredient_id_err_msg_contnr">
                                <p id="ingredient_id_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-2">
                            <div class="hidden-xs hidden-sm mt-2">&nbsp;</div>
                            <a class="btn bg-red-btn" class="ir_bg_mt5" data-bs-toggle="modal"
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

                                        <?php
                                        $i = 0;
                                        if ($inventory_adjustment_ingredients && !empty($inventory_adjustment_ingredients)) {
                                            foreach ($inventory_adjustment_ingredients as $wi) {
                                                $i++;
                                                echo '<tr class="rowCount" data-item_id="'.$wi->ingredient_id.'" data-id="' . $i . '" id="row_' . $i . '">' .
                                                '<td class="txt_19"><p id="sl_' . $i . '">' . $i . '</p></td>' .
                                                '<input type="hidden" id="ingredient_id_' . $i . '" name="ingredient_id[]" value="' . $wi->ingredient_id . '"/>' .
                                                '<td class="txt_18">' . getIngredientNameById($wi->ingredient_id) . ' (' . getIngredientCodeById($wi->ingredient_id) . ')</span></td>' .
                                                '<td><input type="text" data-countID="' . $i . '" id="consumption_amount_' . $i . '" name="consumption_amount[]" onfocus="this.select();" class="form-control integerchk aligning" placeholder="'.lang('consumption_amount').'" value="' . $wi->consumption_amount . '" onkeyup="return calculateAll();"/> <span class="label_aligning"> ' . unitName(getUnitIdByIgId($wi->ingredient_id)) . '</span></td>' .
                                                '<td><select tabindex="4" class="form-control txt_21" name="consumption_status[]" id="consumption_status_' . $i . '"><option value="">'.lang('select').'</option><option ' . (isset($wi->consumption_status) && $wi->consumption_status == 'Plus' ? 'selected' : '') . ' value="Plus">Plus</option><option ' . (isset($wi->consumption_status) && $wi->consumption_status == 'Minus' ? 'selected' : '') . ' value="Minus">Minus</option></select></td></td>' .
                                                '<td class="d-flex"><a class="btn bg-red-btn" onclick="return deleter(' . $i . ',' . $wi->ingredient_id . ');" ><i class="fa fa-trash"></i> </a></td>' .
                                                '</tr>';
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="3" class="form-control" rows="2" id="note" name="note"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($inventory_adjustment_details->note) ?></textarea>
                            </div>
                            <?php if (form_error('note')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('note'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg note_err_msg_contnr">
                                <p id="note_err_msg"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="suffix_hidden_field" id="suffix_hidden_field" value="<?php echo escape_output($i); ?>" />
                <div class="box-footer p-0 mt-3">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit"
                                class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100"href="<?php echo base_url() ?>Inventory_adjustment/inventoryAdjustments">
                                <?php echo lang('back'); ?>
                                </a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>



    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="noticeModal">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Notice</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i data-feather="x"></i>×</i></span></button>
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
                                <a class="ir_font_bold" href="https://www.convert-me.com/en/convert/"
                                    target="_blank"><?php echo lang('click_here'); ?></a>
                                <?php echo lang('notice_text_2'); ?>
                                <br>
                                <br>
                                <?php echo lang('notice_text_3'); ?>
                                <br>
                                <span class="ir_font_bold">
                                    <?php echo lang('notice_text_4'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>