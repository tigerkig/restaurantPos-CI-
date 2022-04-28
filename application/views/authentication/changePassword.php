
<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }

    if ($this->session->flashdata('exception_1')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception_1'));
        echo '</p></div></div></section>';
    }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('change_password'); ?>
        </h3>
    </section>


    <div class="box-wrapper">
            <div class="table-box">
                <?php echo form_open(base_url('Authentication/changePassword')); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('old_password'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="password" name="old_password" class="form-control"
                                    placeholder="<?php echo lang('old_password'); ?>">
                            </div>
                            <?php if (form_error('old_password')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('old_password'); ?>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('new_password'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="password" name="new_password" class="form-control"
                                    placeholder="<?php echo lang('new_password'); ?>">
                            </div>
                            <?php if (form_error('new_password')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('new_password'); ?>
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