<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/add_pricing_plan.js"></script>
<section class="content-header">
    <h1>
        <?php echo escape_output($title); ?>
    </h1>
</section>

<!-- Main content -->
<section class="main-content-wrapper">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Service/addCustomerReview/' .$encrypted_id, $arrayName = array('id' => 'add_plan')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text"  name="name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>" value="<?php echo isset($customer_review->name) && $customer_review->name?$customer_review->name: set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="alert alert-error ir_text_1 ir_p_5">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('designation'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text"  name="designation" class="form-control"
                                    placeholder="<?php echo lang('designation'); ?>" value="<?php echo isset($customer_review->designation) && $customer_review->designation?$customer_review->designation: set_value('designation'); ?>">
                            </div>
                            <?php if (form_error('designation')) { ?>
                            <div class="alert alert-error ir_text_1 ir_p_5">
                                <?php echo form_error('designation'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('description'); ?> <span class="required_star">*</span></label>
                                <textarea tabindex="3" class="form-control" name="description"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('description')); ?><?php echo isset($customer_review->description) && $customer_review->description?$customer_review->description: set_value('designation'); ?></textarea>
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
                <input type="hidden" name="<?php echo escape_output($this->security->get_csrf_token_name()); ?>"
                    value="<?php echo escape_output($this->security->get_csrf_hash()); ?>" />
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit"
                        class="btn btn-primary"><?php echo lang('submit'); ?></button>
                    <a href="<?php echo base_url() ?>Service/landingPage"><button type="button"
                            class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>