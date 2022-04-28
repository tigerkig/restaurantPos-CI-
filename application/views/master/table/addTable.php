

<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_table'); ?>
        </h3>
    </section>

    
        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url('table/addEditTable')); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('table_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="name" class="form-control"
                                    placeholder="<?php echo lang('table_name'); ?>"
                                    value="<?php echo set_value('name'); ?>">
                            </div>
                            <?php if (form_error('name')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('name'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('seat_capacity'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="sit_capacity" class="form-control integerchk"
                                    placeholder="<?php echo lang('seat_capacity'); ?>"
                                    value="<?php echo set_value('sit_capacity'); ?>">
                            </div>
                            <?php if (form_error('sit_capacity')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('sit_capacity'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('position'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="position" class="form-control"
                                       placeholder="<?php echo lang('position'); ?>"
                                       value="<?php echo set_value('position'); ?>">
                            </div>
                            <?php if (form_error('position')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('position'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" type="text" name="description" class="form-control"
                                       placeholder="<?php echo lang('description'); ?>"
                                       value="<?php echo set_value('description'); ?>">
                            </div>
                            <?php if (form_error('description')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('description'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('outlet'); ?></label>
                               <select class="form-control select2" name="outlet">
                                   <?php
                                    foreach ($outlets as $outlet):
                                   ?>
                                   <option value="<?php echo escape_output($outlet->id)?>"><?php echo escape_output($outlet->outlet_name)?></option>
                                   <?php
                                   endforeach;
                                   ?>
                               </select>
                            </div>
                            <?php if (form_error('outlet')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('outlet'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>table/tables">
                            <?php echo lang('back'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

        
</section>