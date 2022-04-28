
<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?= isset($Units) ?lang('edit') :lang('add') ?> <?php echo lang('unit'); ?>
        </h3>
    </section>

    
    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url('Unit/addEditUnit/' . (isset($Units) ? $this->custom->encrypt_decrypt($Units->id, 'encrypt') : ''))); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 my-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('unit_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="unit_name" class="form-control"
                                    placeholder="<?php echo lang('unit_name'); ?>"
                                    value="<?= isset($Units) && $Units ? $Units->unit_name : set_value('unit_name') ?>">
                            </div>
                            <?php if (form_error('unit_name')) { ?>
                            <div class="callout callout-danger my-2">
                               <?php echo form_error('unit_name'); ?>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 my-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('description'); ?></label>
                                <input tabindex="2" name="description" class="form-control"
                                    value="<?= isset($Units) && $Units ? $Units->description : set_value('description') ?>"
                                    placeholder="<?php echo lang('description'); ?>" />
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
                    <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Unit/Units">
                        <?php echo lang('back'); ?></a>
                    </div>
                 
                  
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
 
        
</section>