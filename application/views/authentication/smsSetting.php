
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
                <?php
                $company_info = json_decode($company->sms_details);
                ?>
                
                <?php echo form_open(base_url() . 'setting/smsSetting/'.(isset($company) && $company->id?$company->id:''), $arrayName = array('id' => 'add_whitelabel','enctype'=>'multipart/form-data')) ?>
                <div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label><?php echo lang('SMS_Type'); ?></label>
                                <select class="form-control select2" name="enable_status" id="enable_status">
                                    <option <?=isset($company) && $company->sms_enable_status=="0"?'selected':''?> <?php echo set_select('enable_status', "0"); ?>  value="0"><?php echo lang('None'); ?></option>
                                    <option  <?=isset($company) && $company->sms_enable_status=="1"?'selected':''?>   <?php echo set_select('enable_status', "1"); ?>   value="1"><?php echo lang('OnnoRokom_SMS'); ?></option>
                                    <option  <?=isset($company) && $company->sms_enable_status=="2"?'selected':''?>   <?php echo set_select('enable_status', "2"); ?>   value="2"><?php echo lang('MiM_SMS'); ?></option>
                                    <option  <?=isset($company) && $company->sms_enable_status=="3"?'selected':''?>   <?php echo set_select('enable_status', "3"); ?>   value="3"><?php echo lang('Textlocal'); ?></option>
                                    <option  <?=isset($company) && $company->sms_enable_status=="4"?'selected':''?>   <?php echo set_select('enable_status', "4"); ?>   value="4"><?php echo lang('Twilio'); ?></option>
                                    <option  <?=isset($company) && $company->sms_enable_status=="5"?'selected':''?>   <?php echo set_select('enable_status', "5"); ?>   value="5"><?php echo lang('Nexmo'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('enable_status')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('enable_status'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div  class="txt_11 row div_hide div_1">
                        <div class="col-md-12 txt_11">
                            <h3><?php echo lang('For_OnnorokomSMS'); ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('SMS_Username'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_1_1" value="<?=(isset($company_info) && $company_info->f_1_1?$company_info->f_1_1:set_value('f_1_1'))?>" placeholder="<?php echo lang('Username'); ?>" id="f_1_1" class="form-control">
                            </div>
                            <?php if (form_error('f_1_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_1_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('password'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_1_2" value="<?=(isset($company_info) && $company_info->f_1_2?$company_info->f_1_2:set_value('f_1_2'))?>" onfocus="select();" placeholder="<?php echo lang('password'); ?>" id="password" class="form-control">
                            </div>
                            <?php if (form_error('f_1_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_1_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div   class="txt_11 row div_hide div_2">
                        <div class="col-md-12 txt_11">
                            <h3><?php echo lang('For_MiM_SMS'); ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('API_Key'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_2_1" value="<?=(isset($company_info) && $company_info->f_2_1?$company_info->f_2_1:set_value('f_2_1'))?>" onfocus="select();" placeholder="<?php echo lang('Username'); ?>" id="f_2_1" class="form-control">
                            </div>
                            <?php if (form_error('f_2_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_2_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Sender_ID'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_2_2" value="<?=(isset($company_info) && $company_info->f_2_2?$company_info->f_2_2:set_value('f_2_2'))?>" onfocus="select();" placeholder="<?php echo lang('password'); ?>" id="password" class="form-control">
                            </div>
                            <?php if (form_error('f_2_2')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_2_2'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="txt_11 row div_hide div_3">
                        <div class="col-md-12 txt_11">
                            <h3><?php echo lang('For_Textlocal'); ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('SMS_Username'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_3_1" value="<?=(isset($company_info) && $company_info->f_3_1?$company_info->f_3_1:set_value('f_3_1'))?>" onfocus="select();" placeholder="<?php echo lang('Username'); ?>" id="f_3_1" class="form-control">
                            </div>
                            <?php if (form_error('f_3_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_3_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('password'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_3_2" value="<?=(isset($company_info) && $company_info->f_3_2?$company_info->f_3_2:set_value('f_3_2'))?>" onfocus="select();" placeholder="<?php echo lang('password'); ?>" id="f_3_2" class="form-control">
                            </div>
                            <?php if (form_error('f_3_2')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_3_2'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('APIKey'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_3_3" value="<?=(isset($company_info) && $company_info->f_3_3?$company_info->f_3_3:set_value('f_3_3'))?>" onfocus="select();" placeholder="<?php echo lang('APIKey'); ?>" id="f_3_3" class="form-control">
                            </div>
                            <?php if (form_error('f_3_3')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_3_3'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div  class="txt_11 row div_hide div_4">
                        <div class="col-md-12 txt_11">
                            <h3><?php echo lang('For_Twilio'); ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('SID'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_4_1" value="<?=(isset($company_info) && $company_info->f_4_1?$company_info->f_4_1:set_value('f_4_1'))?>" onfocus="select();" placeholder="<?php echo lang('SID'); ?>" id="f_4_1" class="form-control">
                            </div>
                            <?php if (form_error('f_4_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_4_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Token'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_4_2" value="<?=(isset($company_info) && $company_info->f_4_2?$company_info->f_4_2:set_value('f_4_2'))?>" onfocus="select();" placeholder="<?php echo lang('Token'); ?>" id="f_4_2" class="form-control">
                            </div>
                            <?php if (form_error('f_4_2')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_4_2'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('Twilio_Number'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_4_3" value="<?=(isset($company_info) && $company_info->f_4_3?$company_info->f_4_3:set_value('f_4_3'))?>" onfocus="select();" placeholder="<?php echo lang('Twilio_Number'); ?>" id="f_4_3" class="form-control">
                            </div>
                            <?php if (form_error('f_4_3')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_4_3'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div  class="txt_11 row div_hide div_5">
                        <div class="col-md-12 txt_11">
                            <h3><?php echo lang('For_Nexmo'); ?></h3>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('API_Key'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_5_1" value="<?=(isset($company_info) && $company_info->f_5_1?$company_info->f_5_1:set_value('f_5_1'))?>" onfocus="select();" placeholder="<?php echo lang('API_Key'); ?>" id="f_5_1" class="form-control">
                            </div>
                            <?php if (form_error('f_5_1')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_5_1'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('API_Secret_Key'); ?> <span class="required_star">*</span></label>
                                <input type="text" name="f_5_2" value="<?=(isset($company_info) && $company_info->f_5_2?$company_info->f_5_2:set_value('f_5_2'))?>" onfocus="select();" placeholder="<?php echo lang('API_Secret_Key'); ?>" id="f_5_2" class="form-control">
                            </div>
                            <?php if (form_error('f_5_2')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('f_5_2'); ?>
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

<script>
    function setDiv() {
        var enable_status = $("#enable_status").val();
        //hide all div on first time
        $(".div_hide").hide();
        $(".div_"+enable_status).show(300)
    }
    setDiv();
    $(document).on('change', '#enable_status', function(e)    {
        setDiv();
    });
</script>
