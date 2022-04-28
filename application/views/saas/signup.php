<?php
$wl = getWhiteLabel();
$site_name = '';
$footer = '';
$system_logo = '';
$base_color = '#7367f0';
if($wl){
    if($wl->site_name){
        $site_name = $wl->site_name;
    }
    if($wl->footer){
        $footer = $wl->footer;
    }
    if($wl->footer){
        $footer = $wl->footer;
    }
    if($wl->system_logo){
        $system_logo = base_url()."images/".$wl->system_logo;
    }
    if(isset($wl->base_color) && $wl->base_color){
        $base_color = $wl->base_color;
    }
}
?>

<?php
if ($this->session->flashdata('exception')) {

    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));
    echo '</p></div></div></section>';
}
?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/userHome.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.css">
<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/components/signin.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/style.css">

<title> <?php echo lang('SignUp'); ?></title>

<input type="hidden" id="saas_m_ch" value="<?=file_exists(APPPATH.'controllers/Service.php')?'yes':''?>">
<input type="hidden" id="ok" value="<?php echo lang('ok'); ?>">
<input type="hidden" id="warning" value="<?php echo lang('alert'); ?>">
<input type="hidden" id="site_title" value="<?php echo escape_output($site_name); ?>">
<input type="hidden" id="site_logo" value="<?php echo escape_output($system_logo); ?>">
<input type="hidden" id="base_color" value="<?php echo escape_output($base_color); ?>">
<input type="hidden" id="front_r_1" value="<?php echo lang('front_r_1'); ?>">
<input type="hidden" id="front_r_2" value="<?php echo lang('front_r_2'); ?>">
<input type="hidden" id="front_r_3" value="<?php echo lang('front_r_3'); ?>">
<input type="hidden" id="front_r_4" value="<?php echo lang('front_r_4'); ?>">
<input type="hidden" id="front_r_5" value="<?php echo lang('front_r_5'); ?>">
<input type="hidden" id="front_r_6" value="<?php echo lang('front_r_6'); ?>">
<input type="hidden" id="front_r_7" value="<?php echo lang('front_r_7'); ?>">
<input type="hidden" id="front_r_8" value="<?php echo lang('front_r_8'); ?>">
<input type="hidden" id="front_r_9" value="<?php echo lang('front_r_9'); ?>">
<input type="hidden" id="front_r_10" value="<?php echo lang('front_r_10'); ?>">
<input type="hidden" id="front_r_11" value="<?php echo lang('front_r_11'); ?>">
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="please_select_payment_method" class="please_select_payment_method" value="<?php echo lang('please_select_payment_method')?>">
<input type="hidden" id="please_select_payment_type" class="please_select_payment_type" value="<?php echo lang('please_select_payment_type')?>">
<input type="hidden" id="Select_You_Plan" class="Select_You_Plan" value="<?php echo lang('Select_You_Plan')?>">
<input type="hidden" value="" id="is_trail_plan">
<!-- Main content -->
<section class="main-content-wrapper main-wrapper">

    <?php $paymentSetting = paymentSetting(); ?>
    <?php $company_details = getMainCompany(); ?>
        <div class="row h-100">

            <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                    <img class="img-fluid" src="<?php echo base_url();?>frequent_changing/newDesign/img/register.svg" alt="Register V2">
                </div>
            </div>

            <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                    <?php $attributes = array('id' => 'singup_company');
                        echo form_open_multipart(base_url('#'),$attributes); ?>

                        <div class="row signup-page">
                            <div class="col-sm-12">
                                <h3 class="auth-title">Adventure starts here</h3>
                                <p class="auth-desc">Make your restaurant management easy and fun!</p>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('business_name'); ?> <span class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="business_name" name="business_name" class="form-control" placeholder="<?php echo lang('business_name'); ?>" value="">
                                </div>
                                <?php if (form_error('business_name')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('business_name'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6">

                                <div class="form-group">
                                    <label><?php echo lang('phone'); ?>  <span class="required_star">*</span></label>
                                    <input tabindex="2" autocomplete="off" type="text" id="phone" name="phone" class="form-control" placeholder="<?php echo lang('phone'); ?>" value="<?php echo escape_output(isset($outlet_information_user->phone) && $outlet_information_user->phone?$outlet_information_user->phone:""); ?>">
                                </div>
                                <?php if (form_error('phone')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('phone'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2">

                                <div class="form-group">
                                    <label><?php echo lang('address'); ?> <span class="required_star">*</span></label>
                                    <textarea tabindex="3" id="address" name="address" class="form-control" placeholder="<?php echo lang('address'); ?>"></textarea>
                                </div>
                                <?php if (form_error('address')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('address'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2">

                                <div class="form-group">
                                    <label><?php echo lang('plan'); ?> <span class="required_star">*</span></label>
                                    <select class="form-control select2" id="plan_id" name="plan_id">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php foreach ($pricingPlans as $value):?>
                                            <option data-trail_days="<?php echo escape_output($value->trail_days)?>" data-total_amount="<?php echo escape_output($value->monthly_cost)?>" data-plan_name="<?php echo escape_output($value->plan_name)?>-<?php echo isset($value->monthly_cost) && $value->monthly_cost?escape_output($value->monthly_cost):"0"?><?php echo escape_output($company_details->currency)?>/Mo-Max User: <?php echo escape_output($value->number_of_maximum_users)?>-Max Outlet: <?php echo escape_output($value->number_of_maximum_outlets)?>" value="<?php echo escape_output($value->id)?>"><?php echo escape_output($value->plan_name)?>-<?php echo isset($value->monthly_cost) && $value->monthly_cost?escape_output($value->monthly_cost):"0"?><?php echo escape_output($company_details->currency)?>/Mo-Max User: <?php echo escape_output($value->number_of_maximum_users)?>-Max Outlet: <?php echo escape_output($value->number_of_maximum_outlets)?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if (form_error('plan_id')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('plan_id'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-6 hide_div_for_free display_none">
                                <div class="form-group">

                                    <label> <?php echo lang('payment_type'); ?> <span class="required_star">*</span></label>
                                    <select tabindex="8" class="form-control select2" name="payment_type" id="payment_type">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <option <?=set_select('payment_type','1')?> value="1">One Time</option>
                                        <option <?=set_select('payment_type','2')?> value="2">Recurring</option>
                                    </select>
                                </div>
                                <?php if (form_error('payment_method')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('payment_method'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6 hide_div_for_free display_none">
                                <div class="form-group">
                                    <label> <?php echo lang('payment_method'); ?> <span class="required_star">*</span></label>
                                    <select tabindex="8" class="form-control select2" name="payment_method" id="payment_method">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php if($paymentSetting->field_2==1):?>
                                            <option <?=set_select('payment_method','1')?> value="1">Paypal</option>
                                            <?php
                                        endif;
                                        ?>
                                        <?php if($paymentSetting->field_3==1):?>
                                            <option class="recurring_hide" <?=set_select('payment_method','2')?> value="2">Stripe</option>
                                            <?php
                                        endif;
                                        ?>
                                        <?php if($paymentSetting->field_5==1):?>
                                            <option class="recurring_hide" <?=set_select('payment_method','3')?> value="3">Razorpay</option>
                                            <?php
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <?php if (form_error('payment_method')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('payment_method'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 my-2">
                                <div class="form-group">
                                    <h4 class="auth-subtitle"><?=lang('For_Admin_Access')?></h4>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 mb-2 col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('admin_name'); ?>  <span class="required_star">*</span></label>
                                    <input tabindex="4" autocomplete="off" type="text" id="admin_name" name="admin_name" class="form-control" placeholder="<?php echo lang('admin_name'); ?>" value="">
                                </div>
                                <?php if (form_error('admin_name')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('admin_name'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-6">

                                <div class="form-group">
                                    <label> <?php echo lang('email'); ?> <span class="required_star">*</span> (<?php echo lang('as_login'); ?>)</label>
                                    <input tabindex="5" autocomplete="off" type="text" name="email" id="email" class="form-control" placeholder="<?php echo lang('email'); ?>" value="">
                                </div>
                                <?php if (form_error('email')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('email'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6">

                                <div class="form-group">
                                    <label><?php echo lang('password'); ?> <span class="required_star">*</span></label>
                                    <input tabindex="6" type="password" name="password" id="password" class="form-control"
                                        placeholder="<?php echo lang('password'); ?>"
                                        value="<?php echo set_value('password'); ?>">
                                </div>
                                <?php if (form_error('password')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <span class="error_paragraph"><?php echo form_error('password'); ?></span>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2 col-md-6">

                                <div class="form-group">
                                    <label><?php echo lang('confirm_password'); ?> <span
                                                class="required_star">*</span></label>
                                    <input tabindex="7" type="password" id="confirm_password" name="confirm_password" class="form-control"
                                        placeholder="<?php echo lang('confirm_password'); ?>"
                                        value="<?php echo set_value('confirm_password'); ?>">
                                </div>
                                <?php if (form_error('confirm_password')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <span class="error_paragraph"><?php echo form_error('confirm_password'); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <button type="button" name="submit" value="submit" class="w-100 btn bg-blue-btn payment_now">
                                        <?php echo lang('submit'); ?>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <a class="w-100 btn bg-blue-btn" href="<?php echo base_url()?>"><?php echo lang('back'); ?></a>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                </div>
              
            </div>

        </div>




    <!--Stripe payment form-->
    <form hidden method="POST" action="<?php echo base_url()?>payment" id="stripe_form">
        <input type="hidden" value="yes" name="check_stripe" id="check_stripe">
        <input type="hidden" value="0" name="total_payable_str" id="total_payable_str">
        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" class="payment_company_id" type="hidden" value="">
        <input title="item_description" name="item_description_str" id="item_description_str" type="hidden"
               value="Plan Name">
    </form>

    <!--Paypal payment form-->
    <form hidden method="POST" action="<?php echo base_url()?>payment" id="paypal_form">
        <input type="hidden" value="" name="tax_value" id="tax_value">
        <input type="hidden" value="" name="subtotal_value" id="subtotal_value">
        <input type="hidden" value="" name="discount_value" id="discount_value">
        <input type="hidden" value="" name="item_price" id="total_payable">
        <input title="item_name" name="item_name" id="item_name" type="hidden" value="">
        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" class="payment_company_id" type="hidden" value="">
        <input title="item_number" name="item_number" type="hidden" value="0" id="item_number">
        <input title="item_description" name="item_description" id="item_description_str_paypal" type="hidden" value="">
    </form>

    <!-- Buy button -->
    <form hidden action="<?php echo escape_output($paymentSetting->url_paypal); ?>"  id="paypal_recurring_form" method="post">
        <!-- Identify your business so that you can collect the payments -->
        <input type="hidden" name="business" value="<?php echo escape_output($paymentSetting->paypal_business_email); ?>">
        <!-- Specify a subscriptions button. -->
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <!-- Specify details about the subscription that buyers will purchase -->
        <input type="hidden" name="item_name" value="" id="item_name_recurring">
        <input type="hidden" name="item_number" value="--">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="a3" id="paypalAmt" value="" name="item_price">
        <input type="hidden" name="p3" id="paypalValid" value="1">
        <input type="hidden" name="t3" value="M">
        <!-- Custom variable user ID -->
        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" class="payment_company_id" type="hidden" value="">
        <!-- Specify urls -->
        <input type="hidden" name="cancel_return" value = "<?php echo base_url()?>paymentStatus?msg=payment_failed">
        <input type="hidden" id="update_success_url" name="return" value="">
        <input type="hidden" name="notify_url" value="<?php echo base_url(); ?>ipn_paypal">
    </form>

    <!-- razorpay JavaScript library -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <input type="hidden"
           value="<?php echo (isset($paymentSetting->field_4_key_1) && $paymentSetting->field_4_key_1?escape_output($paymentSetting->field_4_key_1):'')?>"
           id="key_id_razorpay">
</section>
<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/online_payment_front.js'); ?>"></script>
<script src="<?php echo base_url(); ?>assets/POS/js/media.js"></script>