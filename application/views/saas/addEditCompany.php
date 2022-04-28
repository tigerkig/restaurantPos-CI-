
<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    $base_color = '';
    ?>
    <script type="text/javascript" src="<?php echo base_url('frequent_changing/js/setting.js'); ?>"></script>
    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo escape_output($title); ?>
        </h3>
    </section>

    <!-- left column -->
    <div class="box-wrapper">
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                $attributes = array('id' => 'restaurant_setting_form');
                echo form_open_multipart(base_url('Service/addEditCompany/' . $encrypted_id),$attributes); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('business_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" id="business_name" name="business_name" class="form-control" placeholder="<?php echo lang('business_name'); ?>" value="<?php echo escape_output(isset($outlet_information->business_name) && $outlet_information->business_name?$outlet_information->business_name:set_value('business_name')); ?>">
                            </div>
                            <?php if (form_error('business_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('business_name'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <div class="form-label-action">
                                    <input type="hidden" name="invoice_logo_p" value="<?php echo escape_output(isset($outlet_information->invoice_logo) && $outlet_information->invoice_logo?$outlet_information->invoice_logo:'')?>">
                                    <label><?php echo lang('Invoice_Logo'); ?></label><a data-file_path="<?php echo base_url()?>images/<?php echo escape_output(isset($outlet_information->invoice_logo) && $outlet_information->invoice_logo?$outlet_information->invoice_logo:''); ?>"  data-id="1" class="btn bg-blue-btn show_preview" href="#"><?php echo lang('show'); ?></a>
                                </div>
                                <input type="file" id="logo" name="invoice_logo" class="form-control">
                            </div>
                            <?php if (form_error('invoice_logo')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('invoice_logo'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label><?php echo lang('address'); ?></label>
                                <textarea id="address"   name="address" class="form-control" placeholder="<?php echo lang('address'); ?>"><?php echo escape_output(isset($outlet_information->address) && $outlet_information->address?$outlet_information->address:set_value('address')); ?></textarea>
                            </div>
                            <?php if (form_error('address')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('address'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label><?php echo lang('Website'); ?></label>
                                <input tabindex="1" autocomplete="off" type="text" id="website" name="website" class="form-control" placeholder="<?php echo lang('Website'); ?>" value="<?= isset($outlet_information->website) && $outlet_information->website?$outlet_information->website:set_value('website'); ?>">
                            </div>
                            <?php if (form_error('website')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('website'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label> <?php echo lang('date_format'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="date_format" id="date_format">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <option <?=set_select('date_format','d/m/Y')?> <?= isset($outlet_information) && $outlet_information->date_format == "d/m/Y" ? 'selected' : '' ?> selected value="d/m/Y">D/M/Y</option>
                                    <option <?=set_select('date_format','m/d/Y')?> <?= isset($outlet_information) && $outlet_information->date_format == "m/d/Y" ? 'selected' : '' ?>  value="m/d/Y">M/D/Y</option>
                                    <option <?=set_select('date_format','Y/m/d')?> <?= isset($outlet_information) && $outlet_information->date_format == "Y/m/d" ? 'selected' : '' ?>  value="Y/m/d">Y/M/D</option>
                                </select>
                            </div>
                            <?php if (form_error('date_format')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('date_format'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Time_Zone'); ?> <span class="required_star">*</span></label>
                                <select class="form-control select2" id="zone_name" name="zone_name">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($zone_names as $zone_name) { ?>
                                        <option <?= isset($outlet_information) && $outlet_information->zone_name == $zone_name->zone_name ? 'selected' : ($zone_name->id==51?'selected':'') ?>  value="<?= escape_output($zone_name->zone_name) ?>" <?=set_select('zone_name',$zone_name->zone_name)?> ><?= escape_output($zone_name->zone_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('zone_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('zone_name'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label><?php echo lang('currency'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="currency" class="form-control" placeholder="<?php echo lang('currency'); ?>" value="<?php echo escape_output(isset($outlet_information->currency) && $outlet_information->currency?$outlet_information->currency:set_value('currency')); ?>">
                            </div>
                            <?php if (form_error('currency')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('currency'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label> <?php echo lang('Currency_Position'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="currency_position" id="currency_position">
                                    <option <?= isset($outlet_information) && $outlet_information->date_format == "Before Amount" ? 'selected' : '' ?>  value="Before Amount">Before Amount</option>
                                    <option <?= isset($outlet_information) && $outlet_information->date_format == "After Amount" ? 'selected' : '' ?>  value="After Amount">After Amount</option>
                                </select>
                            </div>
                            <?php if (form_error('currency_position')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('currency_position'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label> <?php echo lang('active'); ?> <?php echo lang('status'); ?><span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="is_active" id="is_active">
                                    <option <?= isset($outlet_information) && $outlet_information->is_active == "1" ? 'selected' : '' ?>  value="1"> <?php echo lang('Active'); ?></option>
                                    <option <?= isset($outlet_information) && $outlet_information->is_active == "2" ? 'selected' : '' ?>  value="2"> <?php echo lang('Inactive'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('is_active')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('is_active'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">

                            <div class="form-group">
                                <label> <?php echo lang('Precision'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="precision" id="precision">
                                    <option <?= isset($outlet_information) && $outlet_information->precision == "2" ? 'selected' : '' ?>  value="2">2 Digit</option>
                                    <option <?= isset($outlet_information) && $outlet_information->precision == "3" ? 'selected' : '' ?>  value="3">3 Digit</option>
                                </select>
                            </div>
                            <?php if (form_error('precision')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('precision'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group radio_button_problem">
                                <div class="d-flex align-items-start">
                                    <label><?php echo lang('pre_or_post_payment'); ?> <span class="required_star">*</span></label>
                                    <div class="tooltip_custom">
                                        <i data-bs-toggle="tooltip" data-ds-placement="top" title="<?php echo lang('tooltip_txt_1'); ?>" data-feather="help-circle"></i>
                                    </div>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input tabindex="5" autocomplete="off" type="radio" name="pre_or_post_payment" id="pre_or_post_payment_post" value="Post Payment"
                                            <?php
                                            if ((isset($outlet_information->pre_or_post_payment) && $outlet_information->pre_or_post_payment?$outlet_information->pre_or_post_payment:'') == "Post Payment") {
                                                echo "checked";
                                            };
                                            ?>
                                        ><?php echo lang('post_payment'); ?> </label>
                                    <label>

                                        <input tabindex="5" autocomplete="off" type="radio" name="pre_or_post_payment" id="pre_or_post_payment_pre" value="Pre Payment"
                                            <?php
                                            if ((isset($outlet_information->pre_or_post_payment) && $outlet_information->pre_or_post_payment?$outlet_information->pre_or_post_payment:'') == "Pre Payment") {
                                                echo "checked";
                                            };
                                            ?>
                                        ><?php echo lang('pre_payment'); ?>
                                    </label>
                                </div>
                            </div>
                            <?php if (form_error('pre_or_post_payment')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('pre_or_post_payment'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('invoice_footer'); ?></label>
                                <textarea id="invoice_footer" rows="6" name="invoice_footer" class="form-control" placeholder="<?php echo lang('invoice_footer'); ?>"><?php echo escape_output(isset($outlet_information->invoice_footer) && $outlet_information->invoice_footer?$outlet_information->invoice_footer:set_value('invoice_footer')); ?></textarea>
                            </div>
                            <?php if (form_error('invoice_footer')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('invoice_footer'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3">

                            <div class="form-group">
                                <label> <?php echo lang('plan'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="plan_id" id="plan_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php
                                        foreach ($pricing_plans as $value):
                                    ?>
                                    <option data-trial_day="<?php echo escape_output($value->trail_days) ?>" <?=set_select('plan_id',$value->id)?> <?= isset($outlet_information) && $outlet_information->plan_id == $value->id ? 'selected' : '' ?> value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->plan_name)?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <?php if (form_error('plan_id')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('plan_id'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3 display_none">

                            <div class="form-group">
                                <label> <?php echo lang('access_day'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="access_day" id="access_day" class="form-control" placeholder="<?php echo lang('access_day'); ?>" value="<?php echo escape_output(isset($outlet_information->access_day) && $outlet_information->access_day?$outlet_information->access_day:"00"); ?>">
                            </div>
                            <?php if (form_error('access_day')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('access_day'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <h4><?=lang('For_Admin_Access')?></h4>
                                </div>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                                <input type="hidden" name="admin_id" value="<?php echo escape_output(isset($outlet_information_user->id) && $outlet_information_user->id?$outlet_information_user->id:""); ?>">
                                <div class="form-group">
                                    <label><?php echo lang('admin_name'); ?>  <span class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="admin_name" name="admin_name" class="form-control" placeholder="<?php echo lang('admin_name'); ?>" value="<?php echo escape_output(isset($outlet_information_user->full_name) && $outlet_information_user->full_name?$outlet_information_user->full_name:""); ?>">
                                </div>
                                <?php if (form_error('admin_name')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('admin_name'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('phone'); ?>  <span class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="phone" name="phone" class="form-control" placeholder="<?php echo lang('phone'); ?>" value="<?php echo escape_output(isset($outlet_information_user->phone) && $outlet_information_user->phone?$outlet_information_user->phone:""); ?>">
                                </div>
                                <?php if (form_error('phone')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('phone'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('email'); ?> <span class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" name="email" id="email" class="form-control" placeholder="<?php echo lang('email'); ?>" value="<?php echo escape_output(isset($outlet_information_user->email_address) && $outlet_information_user->email_address?$outlet_information_user->email_address:""); ?>">
                                </div>
                                <?php if (form_error('email')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('email'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label><?php echo lang('password'); ?> <span class="required_star">*</span> (<?php echo lang('keep_blank_in_edit'); ?>)</label>
                                    <input tabindex="5" type="text" name="password" class="form-control"
                                        placeholder="<?php echo lang('password'); ?>"
                                        value="<?php echo set_value('password'); ?>">
                                </div>
                                <?php if (form_error('password')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <span class="error_paragraph"><?php echo form_error('password'); ?></span>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label><?php echo lang('confirm_password'); ?> <span
                                                class="required_star">*</span> (<?php echo lang('keep_blank_in_edit'); ?>)</label>
                                    <input tabindex="4" type="text" name="confirm_password" class="form-control"
                                        placeholder="<?php echo lang('confirm_password'); ?>"
                                        value="<?php echo set_value('confirm_password'); ?>">
                                </div>
                                <?php if (form_error('confirm_password')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <span class="error_paragraph"><?php echo form_error('confirm_password'); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100"href="<?php echo base_url() ?>Service/companies">
                                <?php echo lang('back'); ?>
                            </a>
                        </div>

                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>

    <div class="modal fade" id="logo_preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo lang('Invoice_Logo'); ?> </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="background-color: <?php echo escape_output($base_color)?>;text-align: center;padding: 10px;">
                            <img src="" id="show_id" alt="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn" data-bs-dismiss="modal"><?php echo lang('cancel'); ?></button>
                </div>
            </div>

        </div>
    </div>

</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/addEditCompany.js"></script>