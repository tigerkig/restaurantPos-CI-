
<style type="text/css">
    .required_star{
        color: #dd4b39;
    }

    .radio_button_problem{
        margin-bottom: 19px;
    }
</style>  


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('send'); ?> <?php echo ucfirst($type); ?>  <?php echo lang('sms'); ?>
        </h3>  
    </section>

    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <?php echo form_open(base_url('Short_message_service/sendSMS/'.$type)); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label> <?php echo lang('outlet_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="outlet_name" class="form-control" placeholder="<?php echo lang('outlet_name'); ?>" value="<?php echo escape_output($outlet_name); ?>">

                                <?php if (form_error('outlet_name')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('outlet_name'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                            <?php if($type == "test"){?>
                                <div class="col-sm-12 col-md-6 mb-2">
                                    <div class="form-group">
                                        <label><?php echo lang('number'); ?> <span class="required_star">*</span></label><small> <?php echo lang('must_include_country_code'); ?></small>
                                        <input tabindex="1" type="text" name="number" class="form-control" placeholder="<?php echo lang('number'); ?>" >
                                    </div>
                                    <?php if (form_error('number')) { ?>
                                        <div class="callout callout-danger my-2">
                                            <?php echo form_error('number'); ?>
                                        </div>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                      
                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label><?php echo lang('message'); ?> <span class="required_star">*</span></label>
                                <!--This variable could not be escaped because this is html content-->
                                <textarea tabindex="5" class="form-control" rows="4" name="message" placeholder="<?php echo lang('enter'); ?> ..."><?php echo $message; ?></textarea>
                                <?php if (form_error('message')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('message'); ?>
                                    </div>
                                <?php } ?>

                                <?php if($type == 'birthday' || $type == 'anniversary'){?>
                                    <div class="form-group">
                                        <?php echo lang('there_are'); ?> <b><?php echo count($sms_count); ?></b> <?php echo lang('customer_has'); ?> <?php echo escape_output($type); ?> <?php echo lang('today'); ?>.
                                    </div> 
                                <?php } ?>

                                <?php if($type == 'custom'){?>
                                    <div class="form-group">
                                        <?php echo lang('only'); ?> <b><?php echo count($sms_count); ?></b> <?php echo lang('customer_has_valid'); ?>
                                    </div> 
                                <?php } ?>

                            </div>
                        </div>
                            
                            <div class="form-group">
                                <?php echo lang('your_current_credit'); ?> <label><b><?php echo getAmtP($balance);?></b></label>
                                 <?php echo lang('please_make_sure'); ?>
                            </div>  
                            
                    </div> 
                    <!-- /.box-body --> 
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2">

                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2">

                        <a class="btn bg-blue-btn w-100"href="<?php echo base_url() ?>Short_message_service/smsService">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                   
                   
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
</section>