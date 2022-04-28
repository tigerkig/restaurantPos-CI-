
<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit_customer'); ?>
        </h3>
    </section>

    
    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <?php echo form_open(base_url('customer/addEditCustomer/' . $encrypted_id)); ?>
                <div>
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('customer_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="name" class="form-control"
                                    placeholder="<?php echo lang('customer_name'); ?>"
                                    value="<?php echo escape_output($customer_information->name) ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('phone'); ?> <span class="required_star">*</span></label>
                                <small><?php echo lang('should_country_code'); ?></small>
                                <input tabindex="2" type="text" name="phone" class="form-control integerchk"
                                    placeholder="Phone" value="<?php echo escape_output($customer_information->phone) ?>">
                            </div>
                            <?php if (form_error('phone')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('phone'); ?>
                            </div>
                            <?php } ?>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('email'); ?></label>
                                <input tabindex="3" type="text" name="email" class="form-control"
                                    placeholder="<?php echo lang('email'); ?>"
                                    value="<?php echo escape_output($customer_information->email) ?>">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('date_of_birth'); ?></label>
                                <input tabindex="4" type="text" id="date" name="date_of_birth" class="form-control"
                                    placeholder="<?php echo lang('date_of_birth'); ?>"
                                    value="<?php echo escape_output($customer_information->date_of_birth) ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('date_of_anniversary'); ?></label>
                                <input tabindex="5" type="text" id="dates2" name="date_of_anniversary"
                                    class="form-control" placeholder="<?php echo lang('date_of_anniversary'); ?>"
                                    value="<?php echo escape_output($customer_information->date_of_anniversary) ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('address'); ?></label>
                                <textarea tabindex="6" name="address" class="form-control"
                                    placeholder="<?php echo lang('address'); ?>"><?php echo escape_output($customer_information->address) ?></textarea>
                            </div>
                        </div>

                        <?php if(collectGST()=="Yes"){?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('gst_number'); ?></label>
                                <input tabindex="3" type="text" name="gst_number" class="form-control"
                                    placeholder="<?php echo lang('gst_number'); ?>"
                                    value="<?php echo escape_output($customer_information->gst_number) ?>">
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <div class="row mt-3">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>customer/customers">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                 </div>
            <?php echo form_close(); ?>
    </div>
 
        
</section>