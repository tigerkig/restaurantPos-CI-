<link rel="stylesheet" href="<?= base_url('assets/') ?>buttonCSS/checkBotton.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_user'); ?>
        </h3>
    </section>

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url() . 'User/addEditUser', $arrayName = array('id' => 'user_form')) ?>
                <div>
                    <div class="row">

                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="full_name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>"
                                    value="<?php echo set_value('full_name'); ?>">
                            </div>
                            <?php if (form_error('full_name')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('full_name'); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('email_address'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" name="email_address" class="form-control"
                                    placeholder="<?php echo lang('email_address'); ?>"
                                    value="<?php echo set_value('email_address'); ?>">
                            </div>
                            <?php if (form_error('email_address')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('email_address'); ?></span>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('phone'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="phone" class="form-control integerchk"
                                    placeholder="<?php echo lang('phone'); ?>"
                                    value="<?php echo set_value('phone'); ?>">
                            </div>
                            <?php if (form_error('phone')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('phone'); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('designation'); ?><span class="required_star"> *</span>
                                    <small><?php echo lang('enter_waiter'); ?></small></label>
                                <input tabindex="2" type="text" name="designation" class="form-control"
                                    placeholder="<?php echo lang('designation'); ?>"
                                    value="<?php echo set_value('designation'); ?>">
                            </div>
                            <?php if (form_error('designation')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('designation'); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                        <?php
                        $language_manifesto = $this->session->userdata('language_manifesto');
                        if(str_rot13($language_manifesto)=="eriutoeri"):
                        ?>
                        <div class="col-sm-12 mb-2 col-md-8">

                            <div class="form-group">
                                <label><?php echo lang('outlets'); ?><span class="required_star"> *</span></label>
                                <br>
                                <?php
                                foreach ($outlets as $value) {
                                ?>
                                    <label class="container mt-2"> 
                                        <input class="outlet_class" type="checkbox" name="outlets[]" <?php echo set_checkbox('outlets[]', $value->id); ?> value="<?php echo escape_output($value->id) ?>"> <?php echo escape_output($value->outlet_name) ?> 
                                        <span class="checkmark"></span>
                                    
                                    </label>
                                <?php } ?>
                                <div class="callout callout-danger my-2 outlet_error txt_11">
                                    <span class="error_paragraph"><?php echo lang('Please_select_at_least_one_outlet'); ?></span>
                                </div>
                            </div>
                        </div>
                            <?php
                        endif;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group radio_button_problem">
                                <label><?php echo lang('will_login'); ?> <span class="required_star">*</span></label>
                                <div class="radio">
                                    <label class="me-5">
                                        <input tabindex="5" type="radio" name="will_login" id="will_login_yes"
                                            value="Yes">Yes </label>
                                    <label>

                                        <input tabindex="6" type="radio" name="will_login" id="will_login_no" value="No"
                                            checked>No
                                    </label>
                                </div>
                            </div>
                            <?php if (form_error('will_login')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('will_login'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="will_login_section" class="txt_11">
                        <div class="row">
                            <div class="col-sm-12 mb-2 col-md-4">

                                <div class="form-group">
                                    <label><?php echo lang('password'); ?> <span class="required_star">*</span></label>
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

                            <div class="col-sm-12 mb-2 col-md-4">

                                <div class="form-group">
                                    <label><?php echo lang('confirm_password'); ?> <span
                                            class="required_star">*</span></label>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group my-2">
                                    <label><?php echo lang('menu_access'); ?> <span
                                                class="required_star">*</span></label>
                                </div>
                                <label class="container my-3"> <?php echo lang('select_all'); ?>
                                    <input type="checkbox" id="checkbox_userAll">
                                    <span class="checkmark"></span>
                                </label>
                                    <hr class="my-2">
                                <?php
                            if (isset($user_menus)) {
                                foreach ($user_menus as $value) {
                                    if($value->controller_name=="Plugin" && isServiceAccessPlugin('','','sGmsJaFJE')):
                                        $n=str_replace(" ","_",$value->label);
                                        $m=strtolower($n);
                                        ?>
                                        <label class="container"><?= lang($m) ?>
                                            <input type="checkbox" class="checkbox_user" value="<?php echo escape_output($value->id); ?>"
                                                   name="menu_id[]" <?= set_checkbox('menu_id[]', $value->id) ?>>
                                            <span class="checkmark"></span>
                                        </label>
                                        <?php
                                        elseif($value->controller_name!="Plugin"):
                                    $n=str_replace(" ","_",$value->label);
                                    $m=strtolower($n);
                                    ?>
                                <label class="container"><?= lang($m) ?>
                                    <input type="checkbox" class="checkbox_user" value="<?php echo escape_output($value->id); ?>"
                                        name="menu_id[]" <?= set_checkbox('menu_id[]', $value->id) ?>>
                                    <span class="checkmark"></span>
                                </label>
                            <?php
                            endif;
                                }
                            }
                            ?>
                            </div>
                                <?php if (form_error('menu_id')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('menu_id'); ?></span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">

                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>User/users">
                            <?php echo lang('back'); ?>
                        </a>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</section>

<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/add_user.js'); ?>"></script>
