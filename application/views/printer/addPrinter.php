<link rel="stylesheet" href="<?php echo base_url() ?>frequent_changing/css/custom_check_box.css">

<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('AddPrinter'); ?>
        </h3>  
    </section>

    <div class="box-wrapper">
        
            <div class="table-box">  
            
                <?php 
                $attributes = array('id' => 'add_Printer');
                echo form_open_multipart(base_url('printer/addEditPrinter/'), $attributes); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label><?php echo lang('title'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="title" class="form-control" placeholder="<?php echo lang('title'); ?>" value="<?php echo set_value('title'); ?>">
                            </div>
                            <?php if (form_error('title')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('title'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label><?php echo lang('type'); ?> <span class="required_star">*</span></label>
                                <select class="form-control select2" id="type" name="type">
                                    <option <?php echo set_select('type',"network") ?> value="network"><?php echo lang('network'); ?></option>
                                    <option <?php echo set_select('type',"windows") ?> value="windows"><?php echo lang('windows'); ?></option>
                                    <option <?php echo set_select('type',"linux") ?> value="linux"><?php echo lang('linux'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('type')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('type'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label><?php echo lang('profile'); ?> <span class="required_star">*</span></label>
                                <select class="form-control select2" id="profile" name="profile">
                                    <option <?php echo set_select('profile',"default") ?> value="default"><?php echo lang('default'); ?></option>
                                    <option <?php echo set_select('profile',"simple") ?> value="simple"><?php echo lang('simple'); ?></option>
                                    <option <?php echo set_select('profile',"SP2000") ?> value="SP2000"><?php echo lang('Star_branded'); ?></option>
                                    <option <?php echo set_select('profile',"TEP-200M") ?> value="TEP-200M"><?php echo lang('Espon_Tep'); ?></option>
                                    <option <?php echo set_select('profile',"P822D") ?> value="P822D"><?php echo lang('P822D'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('profile')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('profile'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label><?php echo lang('characters_per_line'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="number" name="characters_per_line" class="form-control" placeholder="<?php echo lang('characters_per_line'); ?>" value="<?php echo set_value('characters_per_line'); ?>">
                            </div>
                            <?php if (form_error('characters_per_line')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('characters_per_line'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3 network_div1">
                            <div class="form-group">
                                <label><?php echo lang('printer_ip_address'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="printer_ip_address" class="form-control" placeholder="<?php echo lang('printer_ip_address'); ?>" value="<?php echo set_value('printer_ip_address'); ?>">
                            </div>
                            <?php if (form_error('printer_ip_address')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('printer_ip_address'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3 network_div1">
                            <div class="form-group">
                                <label><?php echo lang('printer_port'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="printer_port" class="form-control" placeholder="<?php echo lang('printer_port'); ?>" value="<?php echo set_value('printer_port'); ?>">
                            </div>
                            <?php if (form_error('printer_port')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('printer_port'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4 col-lg-3 receipt_printer_div txt_11">
                            <div class="form-group">
                                <label><?php echo lang('path'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="path" class="form-control" placeholder="<?php echo lang('path'); ?>" value="<?php echo set_value('path'); ?>">
                            </div>
                            <?php if (form_error('path')) { ?>
                                <div class="callout callout-danger my-2">
                                    <span class="error_paragraph"><?php echo form_error('path'); ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-2  mb-2">                
                            <button type="submit" name="submit" value="submit" class="w-100 btn bg-blue-btn">
                                <?php echo lang('submit'); ?>
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>printer/printers">
                                <?php  echo lang('back'); ?>
                            </a>                    
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
    </div>
    
</section>
<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/printer.js'); ?>"></script>