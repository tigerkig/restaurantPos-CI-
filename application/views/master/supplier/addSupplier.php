
<section class="main-content-wrapper">

    <section class="content-header">
        <h1>
            <?php echo lang('add_supplier'); ?>
        </h1>
    </section>


    <div class="box-wrapper">
        <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url('supplier/addEditSupplier')); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group mb-2">
                                <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="name" class="form-control"
                                    placeholder="<?php echo lang('name'); ?>" value="<?php echo set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group mb-2">
                                <label><?php echo lang('contact_person'); ?> <span
                                        class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="contact_person" class="form-control"
                                    placeholder="<?php echo lang('contact_person'); ?>"
                                    value="<?php echo set_value('contact_person'); ?>">
                            </div>
                            <?php if (form_error('contact_person')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('contact_person'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group mb-2">
                                <label><?php echo lang('phone'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" name="phone" class="form-control integerchk"
                                    placeholder="<?php echo lang('phone'); ?>"
                                    value="<?php echo set_value('phone'); ?>">
                            </div>
                            <?php if (form_error('phone')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('phone'); ?>
                            </div>
                            <?php } ?>
                            <div class="form-group mb-2">
                                <label><?php echo lang('email'); ?></label>
                                <input tabindex="4" type="text" name="email" class="form-control"
                                    placeholder="<?php echo lang('email'); ?>"
                                    value="<?php echo set_value('email'); ?>">
                            </div>
                            <?php if (form_error('email')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('email'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group mb-2">
                                <label><?php echo lang('address'); ?></label>
                                <textarea tabindex="5" class="form-control" rows="3" name="address"
                                    placeholder="<?php echo lang('address'); ?>"><?php echo escape_output($this->input->post('address')); ?></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label><?php echo lang('description'); ?></label>
                                <textarea tabindex="6" class="form-control" rows="4" name="description"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('description')); ?></textarea>
                            </div>
                            <?php if (form_error('description')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('description'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>supplier/suppliers">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</section>