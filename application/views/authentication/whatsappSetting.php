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
        <?php echo lang('SMS_Setting'); ?>
    </h3>

</section>

    <div class="box-wrapper">
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'setting/whatsappSetting/'.(isset($company) && $company->id?$company->id:''), $arrayName = array('id' => 'add_whitelabel','enctype'=>'multipart/form-data')) ?>
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Whatsapp_Share_Number'); ?> <span class="required_star">*</span></label>
                                <input type="text" onfocus="select();" name="whatsapp_share_number" value="<?=(isset($company) && $company->whatsapp_share_number?escape_output($company->whatsapp_share_number):set_value('whatsapp_share_number'))?>" placeholder="<?php echo lang(' Whatsapp_Share_Number'); ?>" id="whatsapp_share_number" class="form-control">
                            </div>
                            <?php if (form_error('whatsapp_share_number')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('whatsapp_share_number'); ?>
                                </div>
                            <?php } ?>
                        </div>
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