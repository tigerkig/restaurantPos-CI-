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
                <?php echo lang('SMTP_Email_Setting'); ?>
            </h3>
    </section>

    <div class="box-wrapper">
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                $company_info = json_decode($company->smtp_details);
                ?>
                <?php echo form_open(base_url() . 'setting/smtpEmailSetting/'.(isset($company) && $company->id?$company->id:''), $arrayName = array('id' => 'add_whitelabel','enctype'=>'multipart/form-data')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Email_Type'); ?></label>
                                <select class="form-control select2" name="enable_status" id="enable_status">
                                    <option <?=isset($company) && $company->smtp_enable_status=="0"?'selected':''?> <?php echo set_select('enable_status', "0"); ?>  value="0"><?php echo lang('None'); ?></option>
                                    <option  <?=isset($company) && $company->smtp_enable_status=="1"?'selected':''?>   <?php echo set_select('enable_status', "1"); ?>   value="1"><?php echo lang('SMTP'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('enable_status')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('enable_status'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('SMTP_Host'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="host_name" placeholder="<?php echo lang('SMTP_Host'); ?>"  value="<?=isset($company_info) && $company_info->host_name?$company_info->host_name:set_value('host_name')?>" id="host_name" class="form-control">
                            </div>
                            <?php if (form_error('host_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('host_name'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Port_Address'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="port_address" value="<?=isset($company_info) && $company_info->port_address?$company_info->port_address:set_value('port_address')?>"  placeholder="<?php echo lang('Port_Address'); ?>" id="port_address" class="form-control">
                            </div>
                            <?php if (form_error('port_address')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('port_address'); ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Username'); ?> <span  class="required_star">*</span></label>
                                <input type="text" name="user_name" value="<?=isset($company_info) && $company_info->user_name?$company_info->user_name:set_value('user_name')?>" placeholder="<?php echo lang('Username'); ?>" id="user_name" class="form-control">
                            </div>
                            <?php if (form_error('user_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('user_name'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('password'); ?> <span  class="required_star">*</span></label>
                                <input type="text" name="password" value="<?=isset($company_info) && $company_info->password?$company_info->password:set_value('password')?>" placeholder="<?php echo lang('password'); ?>" id="password" class="form-control">
                            </div>
                            <?php if (form_error('password')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('password'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button>
                    <a href="<?php echo base_url() ?>setting"><button type="button" class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
</section>