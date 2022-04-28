<section class="content-header">
    <h1>
        <?php echo lang('send'); ?> <?php echo ucfirst($type); ?> <?php echo lang('sms'); ?>
    </h1>
</section>

<section class="main-content-wrapper">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url('Short_message_service/sendSMS/'.$type)); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label> <?php echo lang('outlet_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="outlet_name" class="form-control"
                                    placeholder="<?php echo lang('outlet_name'); ?>"
                                    value="<?php echo escape_output($outlet_name); ?>">
                            </div>
                            <?php if (form_error('outlet_name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('outlet_name'); ?>
                            </div>
                            <?php } ?>

                            <?php if($type == "test"){?>
                            <div class="form-group">
                                <label><?php echo lang('number'); ?> <span class="required_star">*</span></label><small>
                                    <?php echo lang('must_include_country_code'); ?></small>
                                <input tabindex="1" type="text" name="number" class="form-control"
                                    placeholder="<?php echo lang('number'); ?>">
                            </div>
                            <?php if (form_error('number')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('number'); ?>
                            </div>
                            <?php } ?>
                            <?php } ?>

                            <div class="form-group">
                                <label><?php echo lang('message'); ?> <span class="required_star">*</span></label>
                                <textarea tabindex="5" class="form-control" rows="4" name="message"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($message); ?></textarea>
                            </div>
                            <?php if (form_error('message')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('message'); ?>
                            </div>
                            <?php } ?>

                            <?php if($type == 'birthday' || $type == 'anniversary'){?>
                            <div class="form-group">
                                <?php echo lang('there_are'); ?> <b><?php echo count($sms_count); ?></b>
                                <?php echo lang('customer_has'); ?> <?php echo escape_output($type); ?> <?php echo lang('today'); ?>.
                            </div>
                            <?php } ?>

                            <?php if($type == 'custom'){?>
                            <div class="form-group">
                                <?php echo lang('only'); ?> <b><?php echo count($sms_count); ?></b>
                                <?php echo lang('customer_has_valid'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <?php echo lang('your_current_credit'); ?> <label><b><?php echo escape_output($balance);?></b></label>
                                <?php echo lang('please_make_sure'); ?>
                            </div>


                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit"
                        class="btn btn-primary"><?php echo lang('submit'); ?></button>
                    <a href="<?php echo base_url() ?>Short_message_service/smsService"><button type="button"
                            class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</section>