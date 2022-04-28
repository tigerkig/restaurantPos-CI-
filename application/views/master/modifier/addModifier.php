
<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="name_field_required" value="<?php echo lang('name_field_required'); ?>">
<input type="hidden" id="description_field_can_not_exceed" value="<?php echo lang('description_field_can_not_exceed'); ?>">
<input type="hidden" id="price_field_required" value="<?php echo lang('price_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="consumption" value="<?php echo lang('consumption'); ?>">
<input type="hidden" id="alert" value="<?php echo lang('alert'); ?>">
<script src="<?php echo base_url(); ?>frequent_changing/js/add_modifier.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/add_modifier.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add'); ?> <?php echo lang('modifier'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                <?php echo form_open(base_url() . 'modifier/addEditModifier', $arrayName = array('id' => 'food_menu_form', 'enctype' => 'multipart/form-data')) ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" id="name" name="name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>" value="<?php echo set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg name_err_msg_contnr">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('price'); ?> <span class="required_star">*</span></label>
                                <input tabindex="4" type="text" onfocus="this.select();" id="price" name="price"
                                    class="form-control integerchk" placeholder="<?php echo lang('price'); ?>"
                                    value="<?php echo set_value('price'); ?>">
                            </div>
                            <?php if (form_error('price')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('price'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg price_err_msg_contnr">
                                <p id="price_err_msg"></p>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ingredient_consumptions'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="5" class="form-control select2 select2-hidden-accessible ir_w_100"
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
                            <div class="callout callout-danger my-2 error-msg ingredient_err_msg_contnr">
                                <p id="ingredient_id_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="hidden-xs hidden-sm mt-2">&nbsp;</div>
                            <a class="btn bg-red-btn" class="ir_bg_mt5" data-bs-toggle="modal"
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

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <textarea tabindex="3" class="form-control" rows="2" id="description" name="description"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('description')); ?></textarea>
                            </div>
                            <?php if (form_error('description')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('description'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg description_err_msg_contnr">
                                <p id="description_err_msg"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php
                        $collect_tax = $this->session->userdata('collect_tax');
                        if(isset($collect_tax) && $collect_tax=="Yes"):
                        //get company data
                        $company = getCompanyInfo();
                        $tax_setting = json_decode($company->tax_setting);
                        foreach($tax_setting as $tax_field){ ?>

                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label><?php echo escape_output($tax_field->tax) ?></label>
                                    <table class="ir_w_100">
                                        <tr>
                                            <td>
                                                <input tabindex="1" type="hidden" name="tax_field_id[]"
                                                       value="<?php echo escape_output((isset($tax_field->id) && $tax_field->id?$tax_field->id:'')) ?>">
                                                <input tabindex="1" type="hidden" name="tax_field_company_id[]"
                                                       value="<?php echo escape_output($company->id) ?>">
                                                <input tabindex="1" type="hidden" name="tax_field_name[]"
                                                       value="<?php echo escape_output($tax_field->tax) ?>">
                                                <input tabindex="1" type="text" name="tax_field_percentage[]"
                                                       class="form-control integerchk"
                                                       placeholder="<?php echo escape_output($tax_field->tax) ?>" value="<?php echo escape_output($tax_field->tax_rate)?>">
                                            </td>
                                            <td class="txt_27">%</td>
                                        </tr>
                                    </table>


                                </div>
                            </div>
                        <?php }
                        endif;
                        ?>

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
    </div>
  

    <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="noticeModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
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
                                <span
                                    class="txt_17"><?php echo lang('notice_text_4'); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>