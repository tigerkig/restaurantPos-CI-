<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/add_pricing_plan.js"></script>


<!-- Main content -->
<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo escape_output($title); ?>
        </h3>
    </section>

    
    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Service/addPricingPlan/' .(isset($encrypted_id) && $encrypted_id?$encrypted_id:''), $arrayName = array('id' => 'add_plan')) ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('plan_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="plan_name" class="form-control"
                                    placeholder="<?php echo lang('plan_name'); ?>" value="<?php echo isset($pricingPlans->plan_name) && $pricingPlans->plan_name?$pricingPlans->plan_name: set_value('plan_name'); ?>">
                            </div>
                            <?php if (form_error('plan_name')) { ?>
                            <div class="my-2 callout callout-danger">
                                <?php echo form_error('plan_name'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('payment_type'); ?> <span class="required_star">*</span> </label>
                                <select tabindex="2" class="w-100 form-control select2" id="payment_type"
                                        name="payment_type">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <option <?php echo set_select('payment_type',1)?> <?php echo isset($pricingPlans->payment_type) && $pricingPlans->payment_type=="1"?'selected': ''; ?> value="1"><?php echo lang('one_time'); ?></option>
                                    <option <?php echo set_select('payment_type',2)?>  <?php echo isset($pricingPlans->payment_type) && $pricingPlans->payment_type=="2"?'selected': ''; ?>  value="2"><?php echo lang('recurring'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('payment_type')) { ?>
                                <div class="my-2 callout callout-danger ir_p_5">
                                    <?php echo form_error('payment_type'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3 txt_11 show_recurring">
                            <div class="form-group">
                                <label><?php echo lang('link_for_paypal'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="link_for_paypal" class="form-control"
                                       placeholder="<?php echo lang('link_for_paypal'); ?>" value="<?php echo isset($pricingPlans->link_for_paypal) && $pricingPlans->link_for_paypal?$pricingPlans->link_for_paypal: ''; ?>">
                            </div>
                            <?php if (form_error('link_for_paypal')) { ?>
                                <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                    <?php echo form_error('link_for_paypal'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3 txt_11 show_recurring">
                            <div class="form-group">
                                <label><?php echo lang('link_for_stripe'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="link_for_stripe" class="form-control"
                                       placeholder="<?php echo lang('link_for_stripe'); ?>" value="<?php echo isset($pricingPlans->link_for_stripe) && $pricingPlans->link_for_stripe?$pricingPlans->link_for_stripe: ''; ?>">
                            </div>
                            <?php if (form_error('link_for_stripe')) { ?>
                                <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                    <?php echo form_error('link_for_stripe'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('monthly_cost'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="monthly_cost" class="form-control integerchk"
                                       placeholder="<?php echo lang('monthly_cost'); ?>" value="<?php echo isset($pricingPlans->monthly_cost) && $pricingPlans->monthly_cost?$pricingPlans->monthly_cost: '0'; ?>">
                            </div>
                            <?php if (form_error('monthly_cost')) { ?>
                                <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                    <?php echo form_error('monthly_cost'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('number_of_maximum_users'); ?> <span class="required_star">*</span></label>
                                <div class="tooltip_custom">
                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_pricing_plan'); ?>" data-feather="help-circle"></i>
                                </div>
                                <input tabindex="1" type="text"  name="number_of_maximum_users" class="form-control integerchk"
                                    placeholder="<?php echo lang('number_of_maximum_users'); ?>" value="<?php echo isset($pricingPlans->number_of_maximum_users) && $pricingPlans->number_of_maximum_users?$pricingPlans->number_of_maximum_users: set_value('number_of_maximum_users'); ?>">
                            </div>
                            <?php if (form_error('number_of_maximum_users')) { ?>
                            <div class="my-2 callout callout-danger ir_text_1 ir_p_5 ">
                                <?php echo form_error('number_of_maximum_users'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('number_of_maximum_outlets'); ?> <span class="required_star">*</span></label>
                                <div class="tooltip_custom">
                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_pricing_plan'); ?>" data-feather="help-circle"></i>
                                    
                                </div>
                                <input tabindex="1" type="text"  name="number_of_maximum_outlets" class="form-control integerchk"
                                    placeholder="<?php echo lang('number_of_maximum_outlets'); ?>" value="<?php echo isset($pricingPlans->number_of_maximum_outlets) && $pricingPlans->number_of_maximum_outlets?$pricingPlans->number_of_maximum_outlets: set_value('number_of_maximum_outlets'); ?>">
                            </div>
                            <?php if (form_error('number_of_maximum_outlets')) { ?>
                            <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                <?php echo form_error('number_of_maximum_outlets'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('number_of_maximum_invoices'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="number_of_maximum_invoices" class="form-control integerchk"
                                    placeholder="<?php echo lang('number_of_maximum_invoices'); ?>" value="<?php echo isset($pricingPlans->number_of_maximum_invoices) && $pricingPlans->number_of_maximum_invoices?$pricingPlans->number_of_maximum_invoices: set_value('number_of_maximum_invoices'); ?>">
                            </div>
                            <?php if (form_error('number_of_maximum_invoices')) { ?>
                            <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                <?php echo form_error('number_of_maximum_invoices'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('trail_days'); ?> <span class="required_star">*</span></label>
                                <div class="tooltip_custom">
                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_pricing_plan'); ?>" data-feather="help-circle"></i>
                                </div>
                                <input tabindex="1" type="text"  name="trail_days" class="form-control integerchk"
                                    placeholder="<?php echo lang('trail_days'); ?>" value="<?php echo isset($pricingPlans->trail_days) && $pricingPlans->trail_days?$pricingPlans->trail_days: set_value('trail_days'); ?>">
                            </div>
                            <?php if (form_error('trail_days')) { ?>
                            <div class="my-2 callout callout-danger ir_text_1 ir_p_5">
                                <?php echo form_error('trail_days'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3 display_none">
                            <div class="form-group">
                                <label><?php echo lang('is_recommended'); ?> ?</label>
                                <select tabindex="2" class="w-100 form-control select2" id="is_recommended"
                                        name="is_recommended">
                                    <option <?php echo isset($pricingPlans->is_recommended) && $pricingPlans->is_recommended=="No"?'selected': ''; ?> value="No"><?php echo lang('no'); ?></option>
                                    <option  <?php echo isset($pricingPlans->is_recommended) && $pricingPlans->is_recommended=="Yes"?'selected': ''; ?>  value="Yes"><?php echo lang('yes'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('is_recommended')) { ?>
                                <div class="my-2 callout callout-danger ir_p_5">
                                    <?php echo form_error('is_recommended'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <textarea tabindex="5" class="form-control" name="description"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('description')); ?></textarea>
                            </div>
                            <?php if (form_error('description')) { ?>
                            <div class="my-2 callout callout-danger ir_p_5">
                                <?php echo form_error('description'); ?>
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <input type="hidden" name="<?php echo escape_output($this->security->get_csrf_token_name()); ?>"
                    value="<?php echo escape_output($this->security->get_csrf_hash()); ?>" />
                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                    <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Service/pricingPlans">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                 
                
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
  
        
</section>