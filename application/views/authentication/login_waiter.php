<?php
$wl = getWhiteLabel(); 
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
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/login.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
</head>

<body class="loginPage">
<input type="hidden" id="site_logo" value="<?php echo base_url(); ?>assets/media/logo.png">
<input type="hidden" id="site_favicon" value="<?php echo base_url(); ?>images/favicon.ico">

    <div class="login-box">
        <div class="main-content-wrapper">
            <div class="bg_overlay"></div>
            <div class="logo-box">
                <img src="<?php echo base_url(); ?>images/logo.png">
            </div>


            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php
                        if ($this->session->flashdata('exception_1')) {
                            echo '<div class="topAlert alert alert-danger alert-dismissible"> 
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <p><i class="icon fa fa-times"></i>';
                            echo escape_output($this->session->flashdata('exception_1'));
                            echo '</p></div>';
                        }
                    ?>
                <?php echo form_open(base_url('waiter-login-check')); ?>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="email_address"
                        placeholder="<?php echo lang('email_address'); ?>"
                        value="">
                </div>

                <?php if (form_error('email_address')) { ?>
                <div class="callout callout-danger my-2">
                    <?php echo form_error('email_address'); ?>
                </div>
                <?php } ?>

                <div class="form-group has-feedback pass">
                    <input type="password" name="password" class="form-control"
                        placeholder="<?php echo lang('password'); ?>"
                        value="">
                </div>

                <?php if (form_error('password')) { ?>
                <div class="callout callout-danger my-2">
                    <?php echo form_error('password'); ?>
                </div>
                <?php } ?>
                <input type="hidden" value="Gl3dd5sd" id="txt_vld" name="txt_vld">
                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <button type="submit" name="submit" value="submit"
                            class="custom-btn"><?php echo lang('login'); ?></button>
                    </div>
                    <!-- /.col -->
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/media.js"></script>
</body>

</html>