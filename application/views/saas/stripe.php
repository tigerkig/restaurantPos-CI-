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
<?php
if ($this->session->flashdata('exception')) {

    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));
    echo '</p></div></div></section>';
}
?>
<title><?php echo escape_output($site_name); ?></title>
<!-- Favicon -->
<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<input type="hidden" id="hidden_alert" value="<?php echo lang('alert')?>">
<input type="hidden" id="hidden_ok" value="<?php echo lang('ok')?>">
<section class="content-header">
    <div class="row">
        <div class="col-md-6">
            <h2 class="top-left-header"><?php echo lang('payOnline')?> </h2>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</section>

<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="<?php echo base_url()?>stripePayment" method="POST" id="paymentFrm">
                        <input type="hidden" name="payable_amount" value="<?php echo isset($company->monthly_cost) && $company->monthly_cost?number_format($company->monthly_cost,2):0?>">
                        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" type="hidden" value="<?php echo isset($company->id) && $company->id?$company->id:0?>">
                        <input type="hidden" name="description" value="Monthly payment for <?php echo isset($company->business_name) && $company->business_name?$company->business_name:"Company Name"?>">

                        <div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <h3 class="text-center"><?php echo lang('Payment'); ?> $<?php echo isset($company->monthly_cost) && $company->monthly_cost?number_format($company->monthly_cost,2):0?> <?php echo lang('with_Stripe'); ?></h3>
                                <div class="form-group">
                                    <label><?php echo lang('business_name'); ?></label>
                                    <input type="text" class="form-control" name="name" readonly id="name" value="<?php echo isset($company->business_name) && $company->business_name?$company->business_name:"Company Name"?>">
                                </div>

                                <input type="hidden" class="form-control"  name="email" id="email" value="no_replay@gmail.com"
                                       placeholder="Enter email">
                                <div class="form-group">
                                    <label><?php echo lang('Card_Number')?></label>
                                    <div id="card_number" class="form-control" ></div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('Expiry_Date')?></label>
                                    <div id="card_expiry" class="form-control" ></div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('CVC_Code')?></label>
                                    <div id="card_cvc" class="form-control" ></div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" id="payBtn"><?php echo lang('Confirm_Again')?></button>
                                </div>

                            </div>
                        </div>

                    </form>

                    <br>
                    <p>&nbsp;</p>
                    <?php
                    //for demo mode
                    if(APPLICATION_MODE == 'demo'):
                        ?>
                        <div class="text-center">
                            <h4 class="txt-31">Simple Data</h4>
                            <p class="txt-32"><b>Card Number: </b>4242424242424242</p>
                                <p class="txt-32"><b>Expiry Date: </b> <span>12/24</span></p>
                            <p class="txt-32"><b>CVC Code: </b>123</p>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>


<!-- Stripe JavaScript library -->
<script src="https://js.stripe.com/v3/"></script>

<?php
//get payment setting
$paymentSetting = paymentSetting();
$stripe_publishable_key = '';
$stripe_publishable_key = $paymentSetting->field_3_key_2;
?>
<script>
    // Create an instance of the Stripe object
    // Set your publishable API key
    //get publishable key
    //use this key in external stripe.js file
    let share_key = "<?php echo escape_output($stripe_publishable_key)?>";
</script>

<script src="<?php echo base_url()?>frequent_changing/js/stripe.js"></script>