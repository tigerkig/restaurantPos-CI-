
<script>
<?php
$ingredient_id_container = "[";
if ($modifier_ingredients && !empty($modifier_ingredients)) {
    foreach ($modifier_ingredients as $fmi) {
        $ingredient_id_container .= '"' . $fmi->ingredient_id . '",';
    }
}
$ingredient_id_container = substr($ingredient_id_container, 0, -1);
$ingredient_id_container .= "]";
?>

let ingredient_id_container = <?php echo escape_output($ingredient_id_container); ?>;
</script>


<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="name_field_required" value="<?php echo lang('name_field_required'); ?>">
<input type="hidden" id="description_field_can_not_exceed" value="<?php echo lang('description_field_can_not_exceed'); ?>">
<input type="hidden" id="price_field_required" value="<?php echo lang('price_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="consumption" value="<?php echo lang('consumption'); ?>">
<input type="hidden" id="alert" value="<?php echo lang('alert'); ?>">
<script src="<?php echo base_url(); ?>frequent_changing/js/edit_modifier.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/add_modifier.css">


<section class="main-content-wrapper">
        
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit'); ?> <?php echo lang('modifier'); ?>
        </h3>
    </section>

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url() . 'modifier/addEditModifier/' . $encrypted_id, $arrayName = array('id' => 'modifier_form', 'enctype' => 'multipart/form-data')) ?>

                <div>

                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" id="name" name="name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>"
                                    value="<?php echo escape_output($modifier_details->name) ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>
                            <div class="error-msg name_err_msg_contnr">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('price'); ?> <span class="required_star">*</span></label>
                                <input tabindex="4" type="text" id="price" name="price"
                                    class="form-control integerchk ir_w_100" placeholder="<?php echo lang('price'); ?>"
                                    value="<?php echo escape_output($modifier_details->price) ?>">
                            </div>
                            <?php if (form_error('price')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('price'); ?>
                            </div>
                            <?php } ?>
                            <div class="error-msg sale_price_err_msg_contnr">
                                <p id="sale_price_err_msg"></p>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ingredient_consumptions'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="5" class="form-control select2 ir_w_100"
                                    name="ingredient_id" id="ingredient_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($ingredients as $ingnts) { ?>
                                    <option
                                        value="<?php echo escape_output($ingnts->id . "|" . $ingnts->name . "|" . $ingnts->unit_name) ?>"
                                        <?php echo set_select('unit_id', $ingnts->id); ?>>
                                        <?php echo escape_output($ingnts->name . "(" . $ingnts->code . ")"); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('ingredient_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('ingredient_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="error-msg ingredient_err_msg_contnr">
                                <p id="ingredient_id_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="hidden-xs hidden-sm mt-2">&nbsp;</div>
                            <a class="btn bg-red-btn" data-bs-toggle="modal"
                                data-bs-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="hidden-lg hidden-sm">&nbsp;</div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive" id="ingredient_consumption_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th><?php echo lang('ingredient'); ?></th>
                                            <th><?php echo lang('consumption'); ?></th>
                                            <th><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $i = 0;
                                        if ($modifier_ingredients && !empty($modifier_ingredients)) {
                                            foreach ($modifier_ingredients as $fmi) {
                                                $i++;
                                                echo '<tr id="row_' . $i . '">' .
                                                '<td  ><p>' . $i . '</p></td>' .
                                                '<td class="ir_w_23"><span class="txt_18">' . (isset($fmi->ingredient_id) && $fmi->ingredient_id ? getIngredientNameById($fmi->ingredient_id) : '') . '</span></td>' .
                                                '<input type="hidden" id="ingredient_id_' . $i . '" name="ingredient_id[]" value="' . $fmi->ingredient_id . '"/>' .
                                                '<td><input type="text" tabindex="' . $i . '" id="consumption_' . $i . '" name="consumption[]" value="' . $fmi->consumption . '" onfocus="this.select();" class="form-control integerchk aligning" class="ir_w_85" placeholder="Consumption"/><span  class="label_aligning">' . (isset($fmi->ingredient_id) && $fmi->ingredient_id ? unitName(getUnitIdByIgId($fmi->ingredient_id)) : '') . '</span></td>' .
                                                '<td class="ir_w_17"><a class="btn btn-danger btn-xs txt_20" onclick="return deleter(' . $i . ',' . $fmi->ingredient_id . ');" ><i class="fa fa-trash"></i> </a></td>' .
                                                '</tr>';
                                            }
                                        }

                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label><?php echo lang('description'); ?></label>
                            <textarea tabindex="3" class="form-control" rows="2" id="description" name="description"
                                placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($modifier_details->description) ?></textarea>
                        </div>
                        <?php if (form_error('description')) { ?>
                        <div class="callout callout-danger my-2">
                            <?php echo form_error('description'); ?>
                        </div>
                        <?php } ?>
                        <div class="error-msg description_err_msg_contnr">
                            <p id="description_err_msg"></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <?php
                        $collect_tax = $this->session->userdata('collect_tax');
                        if(isset($collect_tax) && $collect_tax=="Yes"):
                        $tax_information = json_decode($modifier_details->tax_information);
                        $tax_string_p = ($modifier_details->tax_string);
                        //get company data
                        $company = getCompanyInfo();
                        $tax_setting = json_decode($company->tax_setting);
                        $tax_string_s = ($company->tax_string);

                        if($tax_string_p==$tax_string_s):
                            foreach($tax_information as $tax_field){ ?>

                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label><?php echo escape_output($tax_field->tax_field_name) ?></label>
                                        <table class="ir_w_100">
                                            <tr>
                                                <td>
                                                    <input tabindex="1" type="hidden" name="tax_field_id[]"
                                                           value="<?php echo escape_output((isset($tax_field->tax_field_id) && $tax_field->tax_field_id?$tax_field->tax_field_id:'')) ?>">
                                                    <input tabindex="1" type="hidden"
                                                           name="tax_field_company_id[]"
                                                           value="<?php echo escape_output($tax_field->tax_field_company_id) ?>">
                                                    <input tabindex="1" type="hidden"
                                                           name="tax_field_name[]"
                                                           value="<?php echo escape_output($tax_field->tax_field_name) ?>">
                                                    <input tabindex="1" type="text"
                                                           name="tax_field_percentage[]"
                                                           class="form-control integerchk" placeholder=""
                                                           value="<?php  echo escape_output($tax_field->tax_field_percentage) ?>">
                                                </td>
                                                <td class="txt_27">%</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php
                        else:
                            $arr = array();
                            if($tax_information){
                                foreach($tax_information as $value_){
                                    $arr[$value_->tax_field_name] = $value_->tax_field_percentage;
                                }
                            }
                            foreach($tax_setting as $single_tax){
                                $tax_percentage = '';
                                if(isset($arr[$single_tax->tax]) && $arr[$single_tax->tax]){
                                    $tax_percentage = $arr[$single_tax->tax];
                                }else{
                                    $tax_percentage = $single_tax->tax_rate;
                                }
                                ?>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo escape_output($single_tax->tax) ?></label>
                                        <table class="ir_w_100">
                                            <tr>
                                                <td>
                                                    <input tabindex="1" type="hidden" name="tax_field_id[]"
                                                           value="<?php echo escape_output((isset($single_tax->id) && $single_tax->id?$single_tax->id:'')) ?>">
                                                    <input tabindex="1" type="hidden"
                                                           name="tax_field_company_id[]"
                                                           value="<?php echo escape_output($this->session->userdata('company_id')); ?>">
                                                    <input tabindex="1" type="hidden"
                                                           name="tax_field_name[]"
                                                           value="<?php echo escape_output($single_tax->tax) ?>">
                                                    <input tabindex="1" type="text"
                                                           name="tax_field_percentage[]"
                                                           class="form-control integerchk" placeholder=""
                                                           value="<?php  echo escape_output($tax_percentage) ?>">
                                                </td>
                                                <td class="txt_27">%</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>


                            <?php
                        endif;
                        endif;
                        ?>
                    </div>

                </div>

                <!-- /.box-body -->

                <div class="row mt-3">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>modifier/modifiers">
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 hidden-lg hidden-sm">
                            <p class="modifierCartNotice">
                                <strong class="ir_ml39"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <p class="modifierCartNotice">
                                <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-lg">
                            <p class="modifierCartNotice">
                                <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p class="modifierCartInfo">
                                <a class="ir_font_bold" href="#"
                                    target="_blank"><?php echo lang('click_here'); ?></a>
                                <?php echo lang('notice_text_2'); ?>
                                <br>
                                <br>
                                <?php echo lang('notice_text_2'); ?>
                                <br>
                                <span
                                    class="txt_17"><?php echo lang('notice_text_3'); ?></span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>