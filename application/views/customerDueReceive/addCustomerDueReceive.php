<script src="<?php echo base_url()?>frequent_changing/js/customer_due_receive.js"></script>
<input type="hidden" value="<?php echo lang('current_due'); ?>" id="current_due">


<!-- Main content -->
<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_customer_due_receive'); ?>
        </h3>
    </section>

    <div class="box-wrapper">

            <div class="table-box">

                <?php echo form_open(base_url('Customer_due_receive/addCustomerDueReceive')); ?>
                <div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                    class="form-control" placeholder="<?php echo lang('ref_no'); ?>"
                                    value="<?php echo escape_output($reference_no); ?>">
                            </div>
                            <?php if (form_error('reference_no')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('reference_no'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" id="date" readonly name="date" class="form-control"
                                    placeholder="<?php echo lang('date'); ?>" value="<?php echo date("Y-m-d",strtotime('today')); ?>">
                            </div>
                            <?php if (form_error('date')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('date'); ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('customer'); ?> <span class="required_star">*</span></label>
                                <select tabindex="3" class="form-control select2 ir_w_100" id="customer_id"
                                    name="customer_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($customers as $customer) {
                                        if($customer->id!=1):
                                        ?>
                                    <option value="<?php echo escape_output($customer->id) ?>"
                                        <?php echo set_select('customer_id', $customer->id); ?>>
                                        <?php echo escape_output($customer->name ." ". $customer->phone)?></option>
                                    <?php
                                        endif;
                                     } ?>
                                </select>
                            </div>
                            <div class="callout callout-info my-2 txt_11" id="remaining_due"></div>
                            <?php if (form_error('customer_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('customer_id'); ?>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('amount'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="amount" onfocus="this.select();"
                                    class="form-control integerchk ir_w_100" placeholder="<?php echo lang('amount'); ?>"
                                    value="<?php echo set_value('amount'); ?>">
                            </div>
                            <?php if (form_error('amount')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('amount'); ?>
                            </div>
                            <?php } ?>


                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('payment_method'); ?> <span class="required_star">*</span></label>
                                <select tabindex="3" class="form-control select2 ir_w_100" id="payment_id"
                                        name="payment_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($payment_methods as $value) {
                                        ?>
                                        <option value="<?php echo escape_output($value->id) ?>"
                                            <?php echo set_select('payment_id', $value->id); ?>>
                                            <?php echo escape_output($value->name)?></option>
                                        <?php
                                    } ?>
                                </select>
                            </div>
                            <?php if (form_error('payment_id')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('payment_id'); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="5" class="form-control" rows="4" name="note"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($this->input->post('note')); ?></textarea>
                            </div>
                            <?php if (form_error('note')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('note'); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <div class="row my-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Customer_due_receive/customerDueReceives">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>

</section>