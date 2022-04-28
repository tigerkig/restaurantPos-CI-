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
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/login.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">

</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="main-content-wrapper">
            <div class="login-logo">
                <a href="#"><img src="<?php echo base_url(); ?>images/logo.png"></a>
            </div>
            <!-- Default box -->
            <div class="box">
                <div class="box-footer">
                    <h4 class="box-title">An auto generated password has been sent to your Email</h4>
                    <h4 class="box-title">Please check your Email</h4>
                </div>
                <p class="ir_p_5"><a href="<?php echo base_url(); ?>Authentication/index">Go to Loign Page</a>
                    <br>
                    <br>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong><?php echo ss_footer; ?></strong>
    </footer>
    <!-- ./wrapper -->
</body>

</html>