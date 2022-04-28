<?php
$base_color = '';
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

<section class="content-header">
    <h1>
        <?php echo lang('payOnline'); ?>
    </h1>

</section>
<input type="hidden" id="please_select_payment_method" class="please_select_payment_method" value="<?php echo lang('please_select_payment_method')?>"
<!-- Main content -->
<section class="main-content-wrapper">
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php
                $attributes = array('id' => '#');
                echo form_open_multipart(base_url('#'),$attributes); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="form-group">
                                <?php
                                $paymentSetting = paymentSetting();
                                ?>
                                <label> <?php echo lang('payment_method'); ?> <span class="required_star">*</span></label>
                                <select tabindex="2" class="form-control select2" name="payment_method" id="payment_method">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php if($paymentSetting->field_2==1):?>
                                    <option <?=set_select('payment_method','1')?> value="1">Paypal</option>
                                    <?php
                                    endif;
                                    ?>
                                    <?php if($paymentSetting->field_3==1):?>
                                    <option <?=set_select('payment_method','2')?> value="2">Stripe</option>
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
                        <div class="clearfix"></div>
                        <div class="col-md-4 col-md-offset-4 text-center">
                            <div class="form-group">
                                <div class="box-footer">
                                    <button type="button" name="submit" value="submit" class="btn btn-primary payment_now"><?php echo lang('pay_now'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!--Stripe payment form-->
    <form method="POST" action="<?php echo base_url()?>payment" id="stripe_form">
        <input type="hidden" value="yes" name="check_stripe" id="check_stripe">
        <input type="hidden" value="<?php echo isset($company->monthly_cost) && $company->monthly_cost?number_format($company->monthly_cost,2):0?>" name="total_payable_str" id="total_payable_str">
        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" type="hidden" value="<?php echo isset($company->id) && $company->id?$company->id:0?>">
        <input title="item_description" name="item_description_str" id="item_description_str" type="hidden"
               value="item one, item two">
    </form>

    <!--Paypal payment form-->
    <form method="POST" action="<?php echo base_url()?>payment" id="paypal_form">
        <input type="hidden" value="" name="tax_value" id="tax_value">
        <input type="hidden" value="" name="subtotal_value" id="subtotal_value">
        <input type="hidden" value="" name="discount_value" id="discount_value">
        <input type="hidden" value="<?php echo isset($company->monthly_cost) && $company->monthly_cost?number_format($company->monthly_cost,2):0?>" name="item_price" id="total_payable">
        <input title="item_name" name="item_name" id="item_name" type="hidden" value="Monthly payment for <?php echo isset($company->business_name) && $company->business_name?$company->business_name:"Company Name"?>">
        <input title="payment_company_id" name="payment_company_id" id="payment_company_id" type="hidden" value="<?php echo isset($company->id) && $company->id?$company->id:0?>">
        <input title="item_number" name="item_number" type="hidden" value="0" id="item_number">
        <input title="item_description" name="item_description" type="hidden" value="item one, item two">
    </form>

</section>
<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/online_payment.js'); ?>"></script>