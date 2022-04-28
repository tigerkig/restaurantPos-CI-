
<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>
    <?php  
    echo '<section class="alert-wrapper"><div class="alert alert-info alert-dismissible"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
    echo lang('plese_be_informed');
    echo '</p></div></div></section>'; 
    ?>
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('sms_settings'); ?> <small>(<?php echo lang('provide_your_text_local'); ?>)</small>
        </h3>
    </section>



    <div class="box-wrapper">
            <div class="table-box">
            
                <?= form_open(base_url('Authentication/SMSSetting/' . (isset($company_id) && $company_id ? $company_id : ''))); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('email_address'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="email_address" class="form-control"
                                    placeholder="<?php echo lang('email_address'); ?>"
                                    value="<?php echo escape_output($sms_information->email_address) ?>">
                            </div>
                            <?php if (form_error('email_address')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('email_address'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('password'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="password" class="form-control"
                                    placeholder="<?php echo lang('password'); ?>"
                                    value="<?php echo escape_output($sms_information->password) ?>">
                            </div>
                            <?php if (form_error('password')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('password'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row my-2 px-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                         <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100"
                        href="<?php echo base_url();?>Short_message_service/smsService"><?php echo lang('go_to_send_sms_page'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
   
   
</section>