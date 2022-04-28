


<section class="main-content-wrapper">
    <?php
    if ($this->session->flashdata('exception_3')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception_3'));
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('open_register'); ?>
        </h3>
    </section>


    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url('Register/addBalance')); ?>
                <div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('opening_balance'); ?> <span
                                        class="required_star">*</span></label>
                                <input tabindex="1" type="text" name="opening_balance" class="form-control"
                                    placeholder="<?php echo lang('opening_balance'); ?>"
                                    value="<?php echo set_value('opening_balance'); ?>">
                            </div>
                            <?php if (form_error('opening_balance')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('opening_balance'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer px-0">
                    <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn"><?php echo lang('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>

</section>