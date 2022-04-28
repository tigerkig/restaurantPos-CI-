<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/pricing_plan.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
<!-- Contenedor -->
<title><?php echo lang('Pricing_Plan')?></title>
<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
<input type="hidden" id="saas_m_ch" value="<?=file_exists(APPPATH.'controllers/Service.php')?'yes':''?>">
<div class="pricing-wrapper clearfix">
    <!-- Titulo -->
    <h1 class="pricing-table-title"><?php echo lang('Select_You_Plan')?></h1>
    <?php
    foreach ($pricingPlans as $value):
    ?>
    <div class="pricing-table <?php echo escape_output($value->is_recommended) && $value->is_recommended=="Yes"?'recommended':''?>">
        <h3 class="pricing-title"><?php echo escape_output($value->plan_name)?></h3>
        <div class="price">$<?php echo !$value->monthly_cost?'0':escape_output($value->monthly_cost)?><sup>/
                <?php
                if($value->trail_days==111){
                    echo lang('month');
                }else{
                    echo escape_output($value->trail_days)." ".lang('days_access');
                }

                ?>
            </sup></div>
        <ul class="table-list">
            <li><?php echo escape_output($value->number_of_maximum_users)?> <span><?php echo lang('users')?></span></li>
            <li><?php echo escape_output($value->number_of_maximum_outlets)?> <span><?php echo lang('outlets')?></span></li>
            <li><?php echo escape_output($value->number_of_maximum_invoices)?> <span><?php echo lang('invoices')?></span></li>
            <li>
                <?php
                if($value->trail_days==111){
                    echo lang('Monthly_Access');
                }else{
                    echo escape_output($value->trail_days)." ".lang('days_access');
                }

                ?>
                </li>
        </ul>
        <div class="table-buy">
            <p>$<?php echo !$value->monthly_cost?'0':escape_output($value->monthly_cost)?><sup>/
                    <?php
                    if($value->trail_days==111){
                        echo lang('month');
                    }else{
                        echo escape_output($value->trail_days)." ".lang('days_access');
                    }

                    ?>
                </sup></p>
            <a href="<?php echo base_url()?>plan/<?=escape_output($value->id)?>" class="pricing-action btn btn-primary"><?php echo lang('Buy_Now')?></a>
        </div>
    </div>
    <?php
    endforeach;
    ?>
    <div



</div>

<div style="clear: both;"><div class="table-buy"><a class="pricing-action btn btn-primary float_left" href="<?php echo base_url() ?>"><?php echo lang('back'); ?></a></div></div>
<script src="<?php echo base_url(); ?>assets/POS/js/media.js"></script>