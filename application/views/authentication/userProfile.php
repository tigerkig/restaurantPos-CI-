<!-- Main content -->
<section class="main-content-wrapper">
    <!-- general form elements -->
    <div class="box-wrapper">
        <div class="table-box">
            <div class="row">
                <div class="col-sm-12 my-3 col-md-4">
                    <img src="<?=base_url()?>images/avatar.png" alt="" class="w-100" />
                </div>
                
                <div class="col-sm-12 my-2 col-md-8">
                    <h1 class="user_info_header"><?php echo escape_output($this->session->userdata('full_name')); ?></h1>
                    <div class="user_detail_container">
                        <?php
                        $outlet_name = escape_output($this->session->userdata('outlet_name'));
                        if ($this->session->userdata('role') != 'Admin' && $outlet_name) {
                            ?>
                        <p class="user_information">
                            <i class="fa fa-cutlery"></i> <?php echo escape_output($this->session->userdata('outlet_name')); ?> <br />
                        </p>
                        <?php } ?>
                        <p class="user_information">
                            <i data-feather="user" class="me-2"></i> <?php echo escape_output($this->session->userdata('role')); ?><br />
                        </p>
                        <p class="user_information">
                            <i data-feather="phone" class="me-2"></i> <?php echo escape_output($this->session->userdata('phone')); ?> <br />
                        </p>
                        <?php if ($this->session->userdata('email_address') != '') { ?>
                        <p class="user_information">
                            <i data-feather="mail" class="me-2"></i>
                            <?php echo escape_output($this->session->userdata('email_address')); ?>
                        </p>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>