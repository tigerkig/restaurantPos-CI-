<?php
$wl = getWhiteLabel();
$company_info = getMainCompany();
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
$company = getMainCompany();
$social_links = isset($company->social_link_details) && $company->social_link_details?json_decode($company->social_link_details):'';
$customer_reviewers = isset($company->customer_reviewers) && $company->customer_reviewers?json_decode($company->customer_reviewers):'';
$counter_details = isset($company->counter_details) && $company->counter_details?json_decode($company->counter_details):'';
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo escape_output($site_name)?></title>
    <link rel=icon href="<?php echo base_url()?>assets/landing/saas/img/favicon.png" sizes="20x20" type="image/png">
    <link rel="icon" href="<?php echo base_url()?>assets/landing/img/favicon.ico">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/landing/saas/css/vendor.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/landing/saas/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/landing/saas/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/landing/saas/css/magnific-popup.css">
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.css">

</head>
<body>
<input type="hidden" id="base_url_ajax" value="<?php echo base_url()?>">
<input type="hidden" name="hidden_alert" id="hidden_alert" class="hidden_alert"
       value="<?php echo lang('alert'); ?>!">
<input type="hidden" name="hidden_ok" id="hidden_ok" class="hidden_ok" value="<?php echo lang('ok'); ?>">
<!-- preloader area start -->
<div class="preloader" id="preloader">
    <div class="preloader-inner">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</div>
<!-- preloader area end -->
<div class="body-overlay" id="body-overlay"></div>

<!-- navbar start -->
<div class="navbar-area bg-one">
    <nav class="navbar navbar-area navbar-expand-lg">
        <div class="container nav-container">
            <div class="responsive-mobile-menu">
                <button class="menu toggle-btn d-block d-lg-none" data-target="#miralax_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="icon-left"></span>
                    <span class="icon-right"></span>
                </button>
            </div>
            <div class="logo">
                <a href="<?php echo base_url() ?>">
                    <!-- Dynamic -->
                    <?php
                    if(isset($system_logo) && $system_logo):
                        ?>
                        <img src="<?php echo escape_output($system_logo)?>" alt="">
                        <?php
                    else:
                        ?>

                        <?php
                    endif;
                    ?>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="miralax_main_menu">
                <ul class="navbar-nav menu-open">
                    <li><a href="<?php echo base_url()?>">Home</a></li>
                    <li><a href="#valuable-feature">Features</a></li>
                    <li><a href="#review">Customer Review</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#faq">Faq</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="<?php echo base_url()?>authentication">Signin</a></li>
                    <li><a href="<?php echo base_url()?>contact-us">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<!-- navbar end -->

<!-- Main content -->
<?php
if (isset($main_content)) {
    //This variable could not be escaped because this is html content
    echo $main_content;
}
?>
<!-- footer area start -->
<footer class="footer-area footer-area-1" style="background: url('assets/landing/saas/img/bg/1.png');">
    <div class="container">
        <div class="subscribe-wrap">
            <div class="subscribe-inner">
                <form autocomplete="off">
                    <input type="email" id="email_send_subscribe" placeholder="Enter Your Email" />
                    <button class="btn btn-base-m send_subscribe">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="footer-inner text-center">
                    <a class="logo" href="<?php echo base_url() ?>">
                        <?php
                        if(isset($system_logo) && $system_logo):
                            ?>
                            <img class="logo" src="<?php echo escape_output($system_logo)?>" alt="">
                            <?php
                        else:
                            ?>

                            <?php
                        endif;
                        ?>
                    </a>
                    <p>Recipe Management, Stock Auto Deduct by Recipe on Sale, Powerful POS, Kitchen/Bar/Waiter Panel, CRM, SMS, Veg & Bar Item Filter</p>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="widget_nav_menu text-center">
                <ul>
                    <li><a href="<?php echo base_url()?>">Home</a></li>
                    <li><a href="#valuable-feature">Features</a></li>
                    <li><a href="#review">Customer Review</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="#faq">Faq</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="<?php echo base_url()?>authentication">Signin</a></li>
                    <li><a href="<?php echo base_url()?>contact-us">Contact Us</a></li>

                </ul>
            </div>
            <div class="row">
                <div class="col-md-7 align-self-center">
                    <div class="copy-right text-center text-md-left">
                        Copyright @<?php echo date("Y",strtotime('today'))?>. All rights reserved
                    </div>
                </div>
                <div class="col-md-5 text-center text-md-right mt-4 mt-md-0">
                    <ul class="social-media">
                        <?php
                        if(isset($social_links) && $social_links->facebook):
                            ?>
                            <li><a class="facebook" target="_blank" href="<?php echo escape_output($social_links->facebook)?>"><i class="fa fa-facebook"></i></a></li>
                            <?php
                        else:
                            ?>
                            <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                            <?php
                        endif;
                        ?>


                        <?php
                        if(isset($social_links) && $social_links->twitter):
                            ?>
                            <li><a class="twitter" href="<?php echo escape_output($social_links->twitter)?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <?php
                        else:
                            ?>
                            <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                            <?php
                        endif;
                        ?>


                        <?php
                        if(isset($social_links) && $social_links->instagram):
                            ?>
                            <li><a class="instagram"  href="<?php echo escape_output($social_links->instagram)?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <?php
                        else:
                            ?>
                            <li><a class="instagram"  href="#"><i class="fa fa-instagram"></i></a></li>
                            <?php
                        endif;
                        ?>

                        <?php
                        if(isset($social_links) && $social_links->youtube):
                            ?>
                            <li><a  class="youtube"  href="<?php echo escape_output($social_links->youtube)?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
                            <?php
                        else:
                            ?>
                            <li><a class="youtube" href="#"><i class="fa fa-youtube"></i></a></li>
                            <?php
                        endif;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end -->

<!-- back to top area start -->
<div class="back-to-top">
    <span class="back-top"><i class="fa fa-angle-up"></i></span>
</div>
<!-- back to top area end -->


<!-- all plugins here -->
<script src="<?php echo base_url()?>assets/landing/saas/js/vendor.js"></script>
<!--popup files-->
<script src="<?php echo base_url()?>assets/landing/saas/js/jquery.magnific-popup.min.js"></script>

<!-- main js  -->
<script src="<?php echo base_url()?>assets/landing/saas/js/main.js"></script>

<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.css">
</body>
</html>