
<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit_payment_method'); ?>
        </h3>
    </section>

    
        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url('paymentMethod/addEditPaymentMethod/' . $encrypted_id)); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('payment_method_name'); ?> <span
                                        class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="name" class="form-control"
                                    placeholder="<?php echo lang('payment_method_name'); ?>"
                                    value="<?php echo escape_output($payment_method_information->name) ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" type="text" name="description" class="form-control"
                                    placeholder="<?php echo lang('description'); ?>"
                                    value="<?php echo escape_output($payment_method_information->description) ?>">
                            </div>
                            <?php if (form_error('description')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('description'); ?>
                            </div>
                            <?php } ?>

                        </div>

                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>paymentMethod/paymentMethods">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        
</section>