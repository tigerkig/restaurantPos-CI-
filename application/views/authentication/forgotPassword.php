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
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">

  </head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><img src="<?php echo base_url(); ?>images/logo.png"></a>
        </div>
        <?php
            if ($this->session->flashdata('exception_1')) {
                echo '<div class="alert alert-danger alert-dismissible"> 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
                echo escape_output($this->session->flashdata('exception_1'));
                echo '</p></div>';
            }
            ?>

        <!-- /.login-logo -->
        <div class="login-box-body">

            <p class="login-box-msg"><?php echo lang('Enter_your_Email'); ?></p>

            <?php echo form_open(base_url('Authentication/sendAutoPassword')); ?>
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="email_address" placeholder="Email Address">
            </div>

            <?php if (form_error('email_address')) { ?>
            <div class="callout callout-danger my-2">
                <?php echo form_error('email_address'); ?>
            </div>
            <?php } ?>

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" name="submit" value="submit"
                        class="btn btn-primary btn-block btn-flat"><?php echo lang('Submit'); ?></button>
                </div>
                <!-- /.col -->
            </div>
            <?php echo form_close(); ?>


        </div>
        <p class="ir_txt_center"><a href="<?php echo base_url(); ?>Authentication/index"><?php echo lang('Go_back_to_Login'); ?></a> </p>
        <!-- /.login-box-body -->
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

</body>

</html>