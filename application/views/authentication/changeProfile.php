
<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('change_profile'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
            <div class="table-box">
                
                <?= form_open(base_url('Authentication/changeProfile/' . (isset($profile_info) && $profile_info ? $this->custom->encrypt_decrypt($profile_info->id, 'encrypt') : ''))); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="full_name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>"
                                    value="<?= isset($profile_info) && $profile_info->full_name ? escape_output($profile_info->full_name) : set_value('full_name') ?>">
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
                                    value="<?= isset($profile_info) && $profile_info->email_address ? escape_output($profile_info->email_address) : set_value('email_address') ?>">
                            </div>
                            <?php if (form_error('email_address')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('email_address'); ?></span>
                            </div>
                            <?php } ?>

                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('phone'); ?> </label>
                                <input tabindex="2" type="text" name="phone" class="form-control integerchk"
                                    placeholder="<?php echo lang('phone'); ?>"
                                    value="<?= isset($profile_info) && $profile_info->phone ? escape_output($profile_info->phone) : set_value('phone') ?>">
                            </div>
                            <?php if (form_error('phone')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('phone'); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="box-footer px-0">
                    <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn"><?php echo lang('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
 
</section>