<?php
$wl = getWhiteLabel();
$site_name = '';
$footer = '';
if($wl){
    if($wl->site_name){
        $site_name = $wl->site_name;
    }
    if($wl->footer){
        $footer = $wl->footer;
    }
    if($wl->system_logo){
        $system_logo = base_url()."images/".$wl->system_logo;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo escape_output($site_name); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 5.0.0 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/login.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/login.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/components/signin.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/style.css">
</head>

<body>
<input type="hidden" id="site_logo" value="<?php echo base_url(); ?>assets/media/logo.png">
<input type="hidden" id="site_favicon" value="<?php echo base_url(); ?>images/favicon.ico">
<input type="hidden" id="saas_m_ch" value="<?=file_exists(APPPATH.'controllers/Service.php')?'yes':''?>">


    <div class="login-auth-page">
        <div class="row h-100">

            <div class="d-none d-lg-flex col-lg-8 left-item align-items-center p-5">
                <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                    <img class="img-fluid" src="<?php echo base_url();?>frequent_changing/newDesign/img/login.svg" alt="Login V2">
                </div>
            </div>

            <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                    <div class="login-box-body px-3">
                        <?php
                        if ($this->session->flashdata('exception_1')) {
                            echo '<div class="alert-wrapper"><div class="alert alert-danger alert-dismissible fade show"> 
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="alert-body p-2">
                                <p><i class="m-right fa fa-times"></i>';echo escape_output($this->session->flashdata('exception_1'));
                            echo '</p></div></div></div>';
                        }
                        ?>

                        <?php
                        if ($this->session->flashdata('exception')) {
                            echo '<div class="alert-wrapper"><div class="topAlert alert alert-success alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <div class="alert-body p-2"><p><i class="m-right fa fa-check"></i>';echo escape_output($this->session->flashdata('exception'));
                            echo '</p></div></div></div>';
                        }
                        ?>
                        <?php echo form_open(base_url('Authentication/loginCheck')); ?>
                        <div>
                            <h3 class="auth-title">Welcome to <?php echo escape_output($site_name); ?></h3>
                            <p class="auth-desc">Please sign-in to your account and start the adventure</p>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <div class="form-group has-feedback">
                                <input type="text" class="form-control" name="email_address" id="email_address"
                                    placeholder="<?php echo lang('email_address'); ?>"
                                    value="<?php if(APPLICATION_MODE == 'demo'){ echo "admin@doorsoft.co"; }else{ echo '';} ?>">
                            </div>
                        </div>
                        <?php if (form_error('email_address')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('email_address'); ?>
                            </div>
                        <?php } ?>

                        <div class="col-sm-12 mb-3">
                            <div class="form-group has-feedback pass">
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="<?php echo lang('password'); ?>"
                                    value="<?php if(APPLICATION_MODE == 'demo'){ echo "123456"; }else{ echo '';} ?>">
                            </div>
                        </div>

                        <?php if (form_error('password')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('password'); ?>
                            </div>
                        <?php } ?>

                        <div class="col-sm-12">
                                <button type="submit" name="submit" value="submit"
                                        class="btn bg-blue-btn w-100"><?php echo lang('login'); ?></button>
                        </div>
                        <?php echo form_close();  if(isServiceAccessOnly('sGmsJaFJE')): ?>
                            <p class="text-center my-3">
                                <a class="txt-color-primary" href="<?php echo base_url()?>singup"><?php echo lang('Signup'); ?></a>
                            </p>
                        <?php endif;

                        if(isServiceAccessOnly('sGmsJaFJE') && APPLICATION_MODE == 'demo'): ?>
                        <div class="row">
                            <div class="col-sm-12 mb-2 col-md-6">
                                <button type="button" data-username="admin@doorsoft.co" data-password="123456" name="button" class="btn w-100 bg-blue-btn set_credentials">Supper Admin</button>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6">
                                <button type="button" data-username="foodcorner@gmail.com" data-password="123456" name="button" class="btn w-100 bg-blue-btn set_credentials">Restaurant Admin</button>
                            </div> 
                        </div>
                        <?php endif; if(APPLICATION_MODE == 'demo'): ?>
                                <p class="text-center custom_margin_lg"><a target="_blank" class="btn btn-danger w-100 " href="<?=base_url()?>Authentication/index"><?php echo lang('Click'); ?> <?php echo lang('here'); ?>  <?php echo lang('if_you_face_issue_to_login'); ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <script src="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.bundle.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/media.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/login.js"></script>
</body>

</html>