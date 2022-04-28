
<section class="main-content-wrapper">

    <section class="content-header">
        <h1>
            <?php echo lang('upload_customer'); ?>
        </h1>

        <?php
        if ($this->session->flashdata('exception')) {

            echo '<div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
            echo escape_output($this->session->flashdata('exception'));
            echo '</p></div>';
        }
        if ($this->session->flashdata('exception_err')) {

            echo '<div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
            echo escape_output($this->session->flashdata('exception_err'));
            echo '</p></div>';
        }
        ?>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                <!-- form start -->
                <?php echo form_open_multipart(base_url('customer/ExcelDataAddCustomers')); ?>
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> <?php echo lang('upload_file'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="file" name="userfile" class="form-control"
                                    placeholder="<?php echo lang('upload_file'); ?>"
                                    value="<?php echo set_value('name'); ?>">
                            </div>
                            <div class="checkbox my-2 form-group">
                                <label class="container">
                                    <input type="checkbox" name="remove_previous" value="1">
                                    <?php echo lang('remove_all_previous_data'); ?>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <?php if (form_error('userfile')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('userfile'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100">
                            <?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>customer/customers">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-3 mb-2">
                    <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>customer/downloadPDF/Customer_Upload.xlsx">
                            <i class="fa fa-save m-right"></i> <?php echo lang('download_sample'); ?></a>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
  
</section>