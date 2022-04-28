<?php
$base_color = '';
?>
<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/setting.js'); ?>"></script>



<!-- Main content -->
<section class="main-content-wrapper">
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
        <h3 class="top-left-header">
            <?php echo lang('Setting'); ?> 
        </h3>
    </section>
    <div class="box-wrapper">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">
                <div class="table-box">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php
                $attributes = array('id' => 'restaurant_setting_form');
                echo form_open_multipart(base_url('setting/index/' . $encrypted_id),$attributes); ?>
                    <div class="box-body">
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('business_name'); ?> <span
                                            class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="business_name"
                                        name="business_name" class="form-control"
                                        placeholder="<?php echo lang('business_name'); ?>"
                                        value="<?php echo escape_output($outlet_information->business_name); ?>">
                                </div>
                                <?php if (form_error('business_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('business_name'); ?>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <div class="form-label-action">
                                        <input type="hidden" name="invoice_logo_p"
                                            value="<?php echo escape_output($outlet_information->invoice_logo)?>">
                                        <label><?php echo lang('Invoice_Logo'); ?></label>
                                        <a data-file_path="<?php echo base_url()?>images/<?php echo escape_output($outlet_information->invoice_logo); ?>"
                                            data-id="1" class="btn bg-blue-btn show_preview"
                                            href="#"><?php echo lang('show'); ?></a>
                                    </div>
                                    <input tabindex="2" type="file" id="logo" name="invoice_logo" class="form-control">
                                </div>
                                <?php if (form_error('invoice_logo')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('invoice_logo'); ?>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label><?php echo lang('Website'); ?></label>
                                    <input tabindex="3" autocomplete="off" type="text" id="website" name="website"
                                        class="form-control" placeholder="<?php echo lang('Website'); ?>"
                                        value="<?= $outlet_information->website; ?>">
                                </div>
                                <?php if (form_error('website')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('website'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('date_format'); ?> <span
                                            class="required_star">*</span></label>
                                    <select tabindex="4" class="form-control select2" name="date_format"
                                        id="date_format">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->date_format == "d/m/Y" ? 'selected' : '' ?>
                                            selected value="d/m/Y">D/M/Y</option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->date_format == "m/d/Y" ? 'selected' : '' ?>
                                            value="m/d/Y">M/D/Y</option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->date_format == "Y/m/d" ? 'selected' : '' ?>
                                            value="Y/m/d">Y/M/D</option>
                                    </select>
                                </div>
                                <?php if (form_error('date_format')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('date_format'); ?>
                                </div>
                                <?php } ?>
                            </div>
                           
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('Time_Zone'); ?> <span class="required_star">*</span></label>
                                    <select tabindex="5" class="form-control select2" id="zone_name" name="zone_name">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php foreach ($zone_names as $zone_name) { ?>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->zone_name == $zone_name->zone_name ? 'selected' : '' ?>
                                            value="<?= escape_output($zone_name->zone_name) ?>">
                                            <?= escape_output($zone_name->zone_name) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php if (form_error('zone_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('zone_name'); ?>
                                </div>
                                <?php } ?>

                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label><?php echo lang('currency'); ?> <span class="required_star">*</span></label>
                                    <input tabindex="6" autocomplete="off" type="text" name="currency"
                                        class="form-control" placeholder="<?php echo lang('currency'); ?>"
                                        value="<?php echo escape_output($outlet_information->currency); ?>">
                                </div>
                                <?php if (form_error('currency')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('currency'); ?>
                                </div>
                                <?php } ?>

                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('Currency_Position'); ?> <span
                                            class="required_star">*</span></label>
                                    <select tabindex="7" class="form-control select2" name="currency_position"
                                        id="currency_position">
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->currency_position == "Before Amount" ? 'selected' : '' ?>
                                            value="Before Amount">Before Amount</option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->currency_position == "After Amount" ? 'selected' : '' ?>
                                            value="After Amount">After Amount</option>
                                    </select>
                                </div>
                                <?php if (form_error('currency_position')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('currency_position'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('Precision'); ?> <span
                                            class="required_star">*</span></label>
                                    <select tabindex="8" class="form-control select2" name="precision" id="precision">
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->precision == "2" ? 'selected' : '' ?>
                                            value="2">2 Digit</option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->precision == "3" ? 'selected' : '' ?>
                                            value="3">3 Digit</option>
                                    </select>
                                </div>
                                <?php if (form_error('precision')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('precision'); ?>
                                </div>
                                <?php } ?>
                            </div>
                         
                            <?php
                            $language_manifesto = $this->session->userdata('language_manifesto');
                            $check_walk_in_customer = 1;
                            if(str_rot13($language_manifesto)!="eriutoeri"):
                            ?>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('Default_Waiter'); ?> <span
                                            class="required_star">*</span></label>
                                    <select tabindex="9" class="form-control select2" name="default_waiter"
                                        id="default_waiter">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($waiters as $value):
                                        if($value->designation=="Waiter"):
                                        ?>
                                        <option
                                            <?=($outlet_information->default_waiter==$value->id?'selected':($value->id==1?'selected':''))?>
                                            value="<?=escape_output($value->id)?>"><?=escape_output($value->full_name)?>
                                        </option>
                                        <?php
                                        endif;
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('default_waiter')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('default_waiter'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('Default_Customer'); ?><span
                                            class="required_star">*</span></label>
                                    <select tabindex="10" class="form-control select2" name="default_customer"
                                        id="default_customer">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php

                                    foreach ($customers as $value1):
                                        if($value1->id==1){
                                            $check_walk_in_customer++;
                                        }
                                        ?>
                                        <option <?=($outlet_information->default_customer==$value1->id?'selected':'')?>
                                            value="<?=escape_output($value1->id)?>"><?=escape_output($value1->name)?>
                                        </option>
                                        <?php
                                    endforeach;
                                    if($check_walk_in_customer==1){?>
                                        <option selected value="1">Walk-in Customer</option>
                                        <?php
                                    }

                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('default_customer')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('default_customer'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('Default_Payment_Method'); ?> <span
                                            class="required_star">*</span></label>
                                    <select tabindex="11" class="form-control select2" name="default_payment"
                                        id="default_payment">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($paymentMethods as $value):
                                        ?>
                                        <option
                                            <?=($outlet_information->default_payment==$value->id?'selected':($value->name=="Cash"?'selected':''))?>
                                            value="<?=escape_output($value->id)?>"><?=escape_output($value->name)?>
                                        </option>
                                        <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('default_payment')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('default_payment'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <?php
                            $company_id = $this->session->userdata('company_id');
                            if($company_id==1):
                            ?>

                            <?php  endif; ?>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label> <?php echo lang('service_type'); ?>  <div class="tooltip_custom">
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('charge_type_tooltip'); ?>" data-feather="help-circle"></i>
                                        </div></label>
                                    <select tabindex="12" class="form-control select2" name="service_type"
                                        id="service_type">

                                        <option
                                            <?= isset($outlet_information) && $outlet_information->service_type == "delivery" ? 'selected' : '' ?>
                                            value="delivery"><?php echo lang('delivery'); ?></option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->service_type == "service" ? 'selected' : '' ?>
                                            value="service"><?php echo lang('service'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('service_type')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('service_type'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('service_amount'); ?> <div class="tooltip_custom">
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('charge_tooltip'); ?>" data-feather="help-circle"></i>
                                </div></label>
                                    <input tabindex="13" autocomplete="off" type="text" id="service_amount"
                                        name="service_amount" class="form-control"
                                        placeholder="<?php echo lang('service_amount'); ?>"
                                        value="<?php echo escape_output($outlet_information->service_amount); ?>">
                                </div>
                                <?php if (form_error('service_amount')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('service_amount'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="my-2"></div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group radio_button_problem">
                                        <div class="d-flex align-items-start">
                                            <label><?php echo lang('pre_or_post_payment'); ?> <span
                                                    class="required_star">*</span></label>
                                            <div class="tooltip_custom">
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_txt_1'); ?>" data-feather="help-circle"></i>
                                            </div>
                                        </div>

                                        <div class="radio">
                                            <label>
                                                <input tabindex="14" autocomplete="off" type="radio"
                                                    name="pre_or_post_payment" id="pre_or_post_payment_post"
                                                    value="Post Payment" <?php
                                                if ($outlet_information->pre_or_post_payment == "Post Payment") {
                                                    echo "checked";
                                                };
                                                ?>><?php echo lang('post_payment'); ?> </label>
                                            <label>
                                                <input tabindex="15" autocomplete="off" type="radio"
                                                    name="pre_or_post_payment" id="pre_or_post_payment_pre"
                                                    value="Pre Payment" <?php
                                                if ($outlet_information->pre_or_post_payment == "Pre Payment") {
                                                    echo "checked";
                                                };
                                                ?>><?php echo lang('pre_payment'); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if (form_error('pre_or_post_payment')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('pre_or_post_payment'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php  if(!isServiceAccessOnlyLogin('sGmsJaFJE')): ?>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label> <?php echo lang('ExportDailySalesResetAllSales'); ?></label>
                                    <div class="tooltip_custom"><i class="fa fa-question fa-lg form_question"></i>
                                        <span
                                            class="tooltiptext_custom"><?php echo lang('tooltip_txt_export_daily_sale'); ?></span>
                                    </div>
                                    <select tabindex="16" class="form-control select2" name="export_daily_sale"
                                        id="export_daily_sale">
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->export_daily_sale == "disable" ? 'selected' : '' ?>
                                            value="disable"><?php echo lang('disable'); ?></option>
                                        <option
                                            <?= isset($outlet_information) && $outlet_information->export_daily_sale == "enable" ? 'selected' : '' ?>
                                            value="enable"><?php echo lang('enable'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('export_daily_sale')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('export_daily_sale'); ?>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label></label>
                                    <table class="ir_w_100">
                                        <tr>
                                            <td><a class="btn bg-blue-btn delete"
                                                    href="<?php echo base_url(); ?>setting/resetTransactionalData"><?php echo lang('ResetTransactionalData'); ?></a>
                                            </td>
                                            <td class="ir_w_1">
                                                <div class="tooltip_custom"><i
                                                        class="fa fa-question fa-lg form_question"></i>
                                                    <span
                                                        class="tooltiptext_custom"><?php echo lang('set_transactional_data'); ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php  endif; ?>
                            <div class="clearfix"></div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>WhiteLabel"><?php echo lang('WhiteLabel'); ?> </a>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>printer/printers"><?php echo lang('Printers'); ?>
                                </a>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>setting/tax"><?php echo lang('Tax_Setting'); ?></a>
                            </div>
                            <?php if($company_id==1 && 1 == 2): ?>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>Update/index"><?php echo lang('software_update'); ?></a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>Update/UninstallLicense"><?php echo lang('Uninstall_License'); ?></a>
                            </div>
                            <?php endif; ?>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>setting/smtpEmailSetting"><?php echo lang('SMTP_Email_Setting'); ?></a>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>setting/smsSetting"><?php echo lang('SMS_Setting'); ?></a>
                            </div>

                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>setting/whatsappSetting"><?php echo lang('WhatsApp_Setting'); ?></a>
                            </div>
                            
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <a class="btn bg-blue-btn"
                                    href="<?php echo base_url(); ?>setting/menuRearrange"><?php echo lang('Menu_Rearrange'); ?></a>
                            </div>
                            

                        </div>

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label><?php echo lang('invoice_footer'); ?></label>
                                    <textarea id="invoice_footer" rows="6" name="invoice_footer" class="form-control"
                                        placeholder="<?php echo lang('invoice_footer'); ?>"><?php echo escape_output($outlet_information->invoice_footer); ?></textarea>
                                </div>
                                <?php if (form_error('invoice_footer')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('invoice_footer'); ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="my-5"></div>
                        <div class="row">
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('printing'); ?> (<?php echo lang('invoice'); ?>)</label>
                                    <select class="form-control printing select2" id="printing_invoice"
                                        name="printing_invoice">
                                        <option
                                            <?php echo isset($outlet_information->printing_invoice) && $outlet_information->printing_invoice == "web_browser"?"selected":'' ?>
                                            <?php echo set_select('printing_invoice',"web_browser") ?> value="web_browser">
                                            <?php echo lang('web_browser'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_invoice) && $outlet_information->printing_invoice == "direct_print"?"selected":'' ?>
                                            <?php echo set_select('printing_invoice',"direct_print") ?>
                                            value="direct_print"><?php echo lang('direct_print'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_invoice) && $outlet_information->printing_invoice == "live_server_print"?"selected":'' ?>
                                            <?php echo set_select('printing_invoice',"live_server_print") ?>
                                            value="live_server_print"><?php echo lang('live_server_print'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('printer_invoice')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('printer_invoice'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 receipt_printer_div_invoice">
                                <div class="form-group">
                                    <label><?php echo lang('receipt_printer'); ?> <span
                                            class="required_star">*</span></label>
                                    <select class="form-control select2" id="receipt_printer_invoice"
                                        name="receipt_printer_invoice">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($printers as $value):
                                        ?>
                                        <option
                                            <?php echo  isset($outlet_information->receipt_printer_invoice) && $outlet_information->receipt_printer_invoice == $value->id?"selected":'' ?>
                                            <?php echo set_select('receipt_printer_invoice',$value->id) ?>
                                            value="<?php echo escape_output($value->id) ?>">
                                            <?php echo escape_output($value->title) ?>
                                            (<?php echo escape_output($value->path) ?>)</option>
                                        <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('receipt_printer_invoice')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('receipt_printer_invoice'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 txt_11 print_server_url_div_invoice">

                                <div class="form-group">
                                    <label><?php echo lang('print_server_url'); ?> <span
                                            class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="print_server_url_invoice"
                                        name="print_server_url_invoice" class="form-control"
                                        placeholder="<?php echo lang('print_server_url_exm'); ?>"
                                        value="<?= $outlet_information->print_server_url_invoice; ?>">
                                </div>
                                <?php if (form_error('print_server_url_invoice')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_server_url_invoice'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 print_format_div_invoice">
                                <div class="form-group radio_button_problem">
                                    <label><?php echo lang('print_format'); ?> <span class="required_star">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_invoice"
                                                id="print_format_thermal" value="No Print" <?php
                                            if ($outlet_information->print_format_invoice == "No Print") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('No_Print'); ?> </label>

                                        <label>
                                            
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_invoice"
                                                id="print_format_thermal" value="56mm" <?php
                                            if ($outlet_information->print_format_invoice == "56mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('56mm'); ?> </label>
                                        <label>
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_invoice"
                                                id="print_format_a4" value="80mm" <?php
                                            if ($outlet_information->print_format_invoice == "80mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('80mm'); ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (form_error('print_format')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_format'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('printing'); ?> (<?php echo lang('bill'); ?>)</label>
                                    <select class="form-control printing select2" id="printing_bill" name="printing_bill">
                                        <option
                                            <?php echo isset($outlet_information->printing_bill) && $outlet_information->printing_bill == "web_browser"?"selected":'' ?>
                                            <?php echo set_select('printing_bill',"web_browser") ?> value="web_browser">
                                            <?php echo lang('web_browser'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_bill) && $outlet_information->printing_bill == "direct_print"?"selected":'' ?>
                                            <?php echo set_select('printing_bill',"direct_print") ?> value="direct_print">
                                            <?php echo lang('direct_print'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_bill) && $outlet_information->printing_bill == "live_server_print"?"selected":'' ?>
                                            <?php echo set_select('printing_bill',"live_server_print") ?>
                                            value="live_server_print"><?php echo lang('live_server_print'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('printer_bill')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('printer_bill'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 display_none receipt_printer_div_bill">
                                <div class="form-group">
                                    <label><?php echo lang('receipt_printer'); ?> <span
                                            class="required_star">*</span></label>
                                    <select class="form-control select2" id="receipt_printer_bill"
                                        name="receipt_printer_bill">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($printers as $value):
                                        ?>
                                        <option
                                            <?php echo  isset($outlet_information->receipt_printer_bill) && $outlet_information->receipt_printer_bill == $value->id?"selected":'' ?>
                                            <?php echo set_select('receipt_printer_bill',$value->id) ?>
                                            value="<?php echo escape_output($value->id) ?>">
                                            <?php echo escape_output($value->title) ?>
                                            (<?php echo escape_output($value->path) ?>)</option>
                                        <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('receipt_printer_bill')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('receipt_printer_bill'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 txt_11 print_server_url_div_bill">

                                <div class="form-group">
                                    <label><?php echo lang('print_server_url'); ?> <span
                                            class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="print_server_url_bill"
                                        name="print_server_url_bill" class="form-control"
                                        placeholder="<?php echo lang('print_server_url_exm'); ?>"
                                        value="<?= $outlet_information->print_server_url_bill; ?>">
                                </div>
                                <?php if (form_error('print_server_url_bill')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_server_url_bill'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 print_format_div_bill">
                                <div class="form-group radio_button_problem">
                                    <label><?php echo lang('print_format'); ?> <span class="required_star">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bill"
                                                id="print_format_thermal" value="No Print" <?php
                                            if ($outlet_information->print_format_bill == "No Print") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('No_Print'); ?> </label>

                                        <label>
                                            
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bill"
                                                id="print_format_thermal" value="56mm" <?php
                                            if ($outlet_information->print_format_bill == "56mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('56mm'); ?> </label>
                                        <label>

                                            

                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bill"
                                                id="print_format_a4" value="80mm" <?php
                                            if ($outlet_information->print_format_bill == "80mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('80mm'); ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (form_error('print_format_bill')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_format_bill'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('printing'); ?> (<?php echo lang('kot'); ?>)</label>
                                    <select class="form-control printing select2" id="printing_kot" name="printing_kot">
                                        <option
                                            <?php echo isset($outlet_information->printing_kot) && $outlet_information->printing_kot == "web_browser"?"selected":'' ?>
                                            <?php echo set_select('printing_kot',"web_browser") ?> value="web_browser">
                                            <?php echo lang('web_browser'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_kot) && $outlet_information->printing_kot == "direct_print"?"selected":'' ?>
                                            <?php echo set_select('printing_kot',"direct_print") ?> value="direct_print">
                                            <?php echo lang('direct_print'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_kot) && $outlet_information->printing_kot == "live_server_print"?"selected":'' ?>
                                            <?php echo set_select('printing_kot',"live_server_print") ?>
                                            value="live_server_print"><?php echo lang('live_server_print'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('printer_kot')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('printer_kot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 display_none receipt_printer_div_kot">
                                <div class="form-group">
                                    <label><?php echo lang('receipt_printer'); ?> <span
                                            class="required_star">*</span></label>
                                    <select class="form-control select2" id="receipt_printer_kot"
                                        name="receipt_printer_kot">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($printers as $value):
                                        ?>
                                        <option
                                            <?php echo  isset($outlet_information->receipt_printer_kot) && $outlet_information->receipt_printer_kot == $value->id?"selected":'' ?>
                                            <?php echo set_select('receipt_printer_kot',$value->id) ?>
                                            value="<?php echo escape_output($value->id) ?>">
                                            <?php echo escape_output($value->title) ?>
                                            (<?php echo escape_output($value->path) ?>)</option>
                                        <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('receipt_printer_kot')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('receipt_printer_kot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 txt_11 print_server_url_div_kot">
                                <div class="form-group">
                                    <label><?php echo lang('print_server_url'); ?> <span
                                            class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="print_server_url_kot"
                                        name="print_server_url_kot" class="form-control"
                                        placeholder="<?php echo lang('print_server_url_exm'); ?>"
                                        value="<?= $outlet_information->print_server_url_kot; ?>">
                                </div>
                                <?php if (form_error('print_server_url_kot')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_server_url_kot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 print_format_div_kot">
                                <div class="form-group radio_button_problem">
                                    <label><?php echo lang('print_format'); ?> <span class="required_star">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_kot"
                                                id="print_format_thermal" value="No Print" <?php
                                            if ($outlet_information->print_format_kot == "No Print") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('No_Print'); ?> </label>

                                        <label>
                                            
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_kot"
                                                id="print_format_thermal" value="56mm" <?php
                                            if ($outlet_information->print_format_kot == "56mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('56mm'); ?> </label>
                                        <label>

                                            

                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_kot"
                                                id="print_format_a4" value="80mm" <?php
                                            if ($outlet_information->print_format_kot == "80mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('80mm'); ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (form_error('print_format_kot')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_format_kot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label><?php echo lang('printing'); ?> (<?php echo lang('BOT'); ?>)</label>
                                    <select class="form-control printing select2" id="printing_bot" name="printing_bot">
                                        <option
                                            <?php echo isset($outlet_information->printing_bot) && $outlet_information->printing_bot == "web_browser"?"selected":'' ?>
                                            <?php echo set_select('printing_bot',"web_browser") ?> value="web_browser">
                                            <?php echo lang('web_browser'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_bot) && $outlet_information->printing_bot == "direct_print"?"selected":'' ?>
                                            <?php echo set_select('printing_bot',"direct_print") ?> value="direct_print">
                                            <?php echo lang('direct_print'); ?></option>
                                        <option
                                            <?php echo isset($outlet_information->printing_bot) && $outlet_information->printing_bot == "live_server_print"?"selected":'' ?>
                                            <?php echo set_select('printing_bot',"live_server_print") ?>
                                            value="live_server_print"><?php echo lang('live_server_print'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('printing_bot')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('printing_bot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 display_none receipt_printer_div_bot">
                                <div class="form-group">
                                    <label><?php echo lang('receipt_printer'); ?> <span
                                            class="required_star">*</span></label>
                                    <select class="form-control select2" id="receipt_printer_bot"
                                        name="receipt_printer_bot">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                    foreach ($printers as $value):
                                        ?>
                                        <option
                                            <?php echo  isset($outlet_information->receipt_printer_bot) && $outlet_information->receipt_printer_bot == $value->id?"selected":'' ?>
                                            <?php echo set_select('receipt_printer_bot',$value->id) ?>
                                            value="<?php echo escape_output($value->id) ?>">
                                            <?php echo escape_output($value->title) ?>
                                            (<?php echo escape_output($value->path) ?>)</option>
                                        <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <?php if (form_error('receipt_printer_bot')) { ?>
                                <div class="alert alert-error txt-uh-21">
                                    <?php echo form_error('receipt_printer_bot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 txt_11 print_server_url_div_bot">
                                <div class="form-group">
                                    <label><?php echo lang('print_server_url'); ?> <span
                                            class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" id="print_server_url_bot"
                                        name="print_server_url_bot" class="form-control"
                                        placeholder="<?php echo lang('print_server_url_exm'); ?>"
                                        value="<?= $outlet_information->print_server_url_bot; ?>">
                                </div>
                                <?php if (form_error('print_server_url_bot')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_server_url_bot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mb-3 col-sm-12 col-md-4 col-lg-3 print_format_div_bot">
                                <div class="form-group radio_button_problem">
                                    <label><?php echo lang('print_format'); ?> <span class="required_star">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bot"
                                                id="print_format_thermal" value="No Print" <?php
                                            if ($outlet_information->print_format_bot == "No Print") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('No_Print'); ?> </label>

                                        <label>
                                            
                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bot"
                                                id="print_format_thermal" value="56mm" <?php
                                            if ($outlet_information->print_format_bot == "56mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('56mm'); ?> </label>
                                        <label>

                                            

                                            <input tabindex="5" autocomplete="off" type="radio" name="print_format_bot"
                                                id="print_format_a4" value="80mm" <?php
                                            if ($outlet_information->print_format_bot == "80mm") {
                                                echo "checked";
                                            };
                                            ?>><?php echo lang('80mm'); ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (form_error('print_format_bot')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('print_format_bot'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-3">
                                 <button type="submit" name="submit" value="submit"
                                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logo_preview" tabindex="-1" aria-hidden="true" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        <?php echo lang('Invoice_Logo'); ?> </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12"
                            style="background-color: <?php echo escape_output($base_color)?>;text-align: center;padding: 10px;">
                            <img class="img-fluid" src="" id="show_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn"
                        data-dismiss="modal"  data-bs-dismiss="modal"><?php echo lang('cancel'); ?></button>
                </div>
            </div>

        </div>
    </div>

</section>