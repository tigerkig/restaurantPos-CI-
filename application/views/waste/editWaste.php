<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="responsible_person_field_required" value="<?php echo lang('responsible_person_field_required'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="note_field_cannot" value="<?php echo lang('note_field_cannot'); ?>">
<input type="hidden" id="wast_amt" value="<?php echo lang('wast_amt'); ?>">
<input type="hidden" id="loss_amt" value="<?php echo lang('loss_amt'); ?>">

<script>
<?php
$ingredient_id_container = "[";
if ($waste_ingredients && !empty($waste_ingredients)) {
    foreach ($waste_ingredients as $wi) {
        $ingredient_id_container .= '"' . $wi->ingredient_id . '",';
    }
}
$ingredient_id_container = substr($ingredient_id_container, 0, -1);
$ingredient_id_container .= "]";
?>

let ingredient_id_container = <?php echo escape_output($ingredient_id_container); ?>;
</script>
<script src="<?php echo base_url(); ?>frequent_changing/js/add_waste.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/editWaste.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit_waste'); ?>
        </h3>
    </section>
    
    <div class="box-wrapper">
            <div class="table-box">
                <?php echo form_open(base_url() . 'Waste/addEditWaste/' . $encrypted_id, $arrayName = array('id' => 'waste_form')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                    class="form-control" placeholder="<?php echo lang('ref_no'); ?>"
                                    value="<?php echo escape_output($waste_details->reference_no) ?>">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" id="date" name="date" class="form-control"
                                    placeholder="<?php echo lang('date'); ?>" readonly
                                    value="<?php echo date('Y-m-d', strtotime($waste_details->date)); ?>">
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

                        <div class="col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('responsible_person'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="employee_id" id="employee_id">
                                    <option value=""><?php echo lang('Select'); ?></option>
                                    <?php foreach ($employees as $empls) { ?>
                                    <option value="<?php echo escape_output($empls->id) ?>" <?php
                                        if ($waste_details->employee_id == $empls->id) {
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ingredients'); ?> <span class="required_star">*</span></label>
                                (<?php echo lang('only_purchase_ingredient'); ?>)
                                <select tabindex="4" class="form-control select2 ir_w_100" name="ingredient_id"
                                    id="ingredient_id">
                                    <option value=""><?php echo lang('Select'); ?></option>
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

                        <div class="col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('food_menu'); ?> </label>
                                <select tabindex="4" class="form-control select2 ir_w_100" name="food_menu_id"
                                    id="food_menu_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php
                                    $ignoreID = array();
                                    foreach ($food_menus as $ingnts) {
                                        if (!in_array($ingnts->id, $ignoreID)) {
                                            $ignoreID[] = $ingnts->id;
                                            ?>
                                    <option <?php if ($ingnts->id == $waste_details->food_menu_id) echo "selected"; ?>
                                        value="<?php echo escape_output($ingnts->id) ?>">
                                        <?php echo escape_output($ingnts->name . " (" . $ingnts->code . ")") ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php if (form_error('food_menu_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('food_menu_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg food_menu_id_err_msg_contnr">
                                <p id="food_menu_id_err_msg"></p>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="hidden-xs hidden-sm">&nbsp;</div>
                            <a class="btn btn-danger" class="ir_bg_mt5" data-toggle="modal"
                                data-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="hidden-lg hidden-sm">&nbsp;</div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('food_menu_waste_quantity'); ?></label>
                                <input tabindex="1" type="text" id="waste_quantity" name="food_menu_waste_qty"
                                    class="form-control" placeholder="<?php echo lang('waste_quantity'); ?>"
                                    value="<?php echo escape_output($waste_details->food_menu_waste_qty) ?>">
                            </div>
                            <?php if (form_error('waste_quantity')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('waste_quantity'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg waste_quantity_err_msg_contnr">
                                <p id="waste_quantity_err_msg"></p>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="waste_cart">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th>
                                                <?php echo lang('ingredient'); ?>(<?php echo lang('code'); ?>)</th>
                                            <th><?php echo lang('quantity_amount'); ?></th>
                                            <th><?php echo lang('loss_amount'); ?></th>
                                            <th><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $i = 0;
                                        if ($waste_ingredients && !empty($waste_ingredients)) {
                                            foreach ($waste_ingredients as $wi) {
                                                $i++;
                                                $unit_price = $wi->loss_amount/$waste_details->food_menu_waste_qty;
                                                echo '<tr class="rowCount" data-id="' . $i . '" id="row_' . $i . '">' .
                                                'class="txt_19"<p id="sl_' . $i . '">' . $i . '</p></td>' .
                                                '<input type="hidden" id="ingredient_id_' . $i . '" name="ingredient_id[]" value="' . $wi->ingredient_id . '"/>' .
                                                '<td class="txt_18">' . getIngredientNameById($wi->ingredient_id) . ' (' . getIngredientCodeById($wi->ingredient_id) . ')</span></td>' .
                                                '<td><input type="text" data-countID="' . $i . '" id="waste_amount_' . $i . '" name="waste_amount[]" onfocus="this.select();" class="form-control integerchk aligning" placeholder="Waste Amt" value="' . $wi->waste_amount . '" onkeyup="return calculateAll();"/> <span class="label_aligning"> ' . unitName(getUnitIdByIgId($wi->ingredient_id)) . '</span><span id="unit_consumption_ingredient_'.$i.'" class="unit_consumption_ingredient ir_display_none">'.($wi->waste_amount/$waste_details->food_menu_waste_qty).'</span></td>' .
                                                '<input type="hidden" id="last_purchase_price_' . $i . '" name="last_purchase_price[]" value="' . $wi->last_purchase_price . '"/>' .
                                                '<td><input type="text" id="loss_amount_' . $i . '" name="loss_amount[]" class="form-control aligning" placeholder="Loss Amt" value="' . $wi->loss_amount . '" readonly /><span id="unit_price_ingredient_'.$i.'" class="unit_price_ingredient ir_display_none">'.$unit_price.'</span></td>' .
                                                '<td><a class="btn btn-danger btn-xs txt_20"> onclick="return deleter(' . $i . ',' . $wi->ingredient_id . ');" ><i class="fa fa-trash"></i> </a></td>' .
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
                                <label><?php echo lang('total_loss'); ?></label>
                                <input class="form-control aligning_total_loss ir_w_100" readonly
                                       type="text" name="total_loss" id="total_loss"
                                       value="<?php echo escape_output($waste_details->total_loss) ?>">
                            </div>
                            <?php if (form_error('total_loss')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('total_loss'); ?>
                            </div>
                            <?php } ?>

                            <div class="callout callout-danger my-2 error-msg total_loss_err_msg_contnr">
                                <p id="total_loss_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="3" class="form-control" rows="2" id="note" name="note"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($waste_details->note) ?></textarea>
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
                <div class="row">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Waste/wastes">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
        

    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="noticeModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo lang('edit_waste'); ?></h5>
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
                                <span class="ir_fw_fs"><?php echo lang('notice_text_4'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="food_menu_waste" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="loan_form" action="#" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><?php echo lang('food_menu_waste'); ?></h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true"><i data-feather="x"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="panel-body">

                            <fieldset>




                                <div class="row">



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo lang('quantity'); ?> <span
                                                    class="required_star">*</span></label>
                                            <input required class="form-control integerchk "
                                                id="food_menu_waste_quantity" name="food_menu_waste_quantity"
                                                type="text" value="<?php echo escape_output($waste_details->food_menu_waste_qty) ?>">
                                        </div>
                                        <?php if (form_error('food_menu_waste_quantity')) { ?>
                                        <div class="callout callout-danger my-2">
                                            <?php echo form_error('food_menu_waste_quantity'); ?>
                                        </div>
                                        <?php } ?>
                                        <div
                                            class="callout callout-danger my-2 error-msg food_menu_waste_quantity_err_msg_contnr">
                                            <p id="food_menu_waste_quantity_err_msg"></p>
                                        </div>
                                    </div>

                                </div>
                                <!--End Row-->





                        </div>

                        </fieldset>

                    </div>
                    <div class="modal-footer">
                        <input id="food_menu_waste_button" type="button" value="submit" class="btn btn-primary">
                        <button type="button" class="btn bg-blue-btn"
                            data-dismiss="modal"><?php echo lang('close'); ?></button>

                    </div>
            </div>

            </form>
        </div>
    </div>



</section>