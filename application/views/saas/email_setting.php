
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
    if ($this->session->flashdata('exception_1')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception_1'));
        echo '</p></div></div></section>';
    }
    ?>
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('email_setting'); ?> <small class="color_red">(<?php echo lang('until_email_setup'); ?>)</small>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                    $company_id = $this->session->userdata('company_id');
                $company = getMainCompany();
                $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
                ?>
                <?php echo form_open(base_url() . 'Service/emailSetting/'.(isset($company_id) && $company_id?$company_id:''), $arrayName = array('id' => 'add_whitelabel','enctype'=>'multipart/form-data')) ?>
           
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('EmailType'); ?> </label>
                                <select class="form-control select2 width_100_p" name="enable_status" id="enable_status">
                                    <option <?php echo isset($smtEmail) && $smtEmail->enable_status=="0"?'selected':''?> <?php echo set_select('enable_status', "0"); ?>  value="0"><?php echo lang('None'); ?></option>
                                    <option  <?php echo isset($smtEmail) && $smtEmail->enable_status=="1"?'selected':''?>   <?php echo set_select('enable_status', "1"); ?>   value="1"><?php echo lang('SMTP'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('enable_status')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('enable_status'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('email_send_to'); ?></label>
                                <input type="text" name="email_send_to" placeholder="<?php echo lang('email_send_to'); ?>"  value="<?php echo isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:set_value('email_send_to')?>" id="email_send_to" class="form-control">
                            </div>
                            <?php if (form_error('email_send_to')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('email_send_to'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('SMTPHost'); ?></label>
                                <input type="text" name="host_name" placeholder="<?php echo lang('SMTPHost'); ?>"  value="<?php echo isset($smtEmail) && $smtEmail->host_name?$smtEmail->host_name:set_value('host_name')?>" id="host_name" class="form-control">
                            </div>
                            <?php if (form_error('host_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('host_name'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('PortAddress'); ?></label>
                                <input type="text" name="port_address" value="<?php echo isset($smtEmail) && $smtEmail->port_address?$smtEmail->port_address:set_value('port_address')?>"  placeholder="<?php echo lang('PortAddress'); ?>" id="port_address" class="form-control">
                            </div>
                            <?php if (form_error('port_address')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('port_address'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Username'); ?></label>
                                <input type="text" name="user_name" value="<?php echo isset($smtEmail) && $smtEmail->user_name?$smtEmail->user_name:set_value('user_name')?>" placeholder="<?php echo lang('Username'); ?>" id="user_name" class="form-control">
                            </div>
                            <?php if (form_error('user_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('user_name'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Password'); ?></label>
                                <input type="text" name="password" value="<?php echo isset($smtEmail) && $smtEmail->password?$smtEmail->password:set_value('password')?>" placeholder="<?php echo lang('Password'); ?>" id="password" class="form-control">
                            </div>
                            <?php if (form_error('password')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('password'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-sm-12 col-md-3">
                            <button type="submit" name="submit" value="submit" class="btn bg-blue-btn"><?php echo lang('submit'); ?></button>
                        </div>
                    </div>
             
                
                <?php echo form_close(); ?>
        </div>
    </div>
    
</section>