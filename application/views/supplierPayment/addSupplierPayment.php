<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/add_supplier_payment.js"></script>


<!-- Main content -->
<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_supplier_due_payment'); ?>
        </h3>
    </section>


    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <?php echo form_open(base_url('SupplierPayment/addSupplierPayment')); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">
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

                            <div class="form-group">
                                <label><?php echo lang('supplier'); ?> <span class="required_star">*</span></label>
                                <select tabindex="3" class="form-control select2 ir_w_100" id="supplier_id"
                                    name="supplier_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($suppliers as $splrs) { ?>
                                    <option value="<?php echo escape_output($splrs->id) ?>"
                                        <?php echo set_select('supplier_id', $splrs->id); ?>><?php echo escape_output($splrs->name) ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="callout callout-info my-2 txt_11" id="remaining_due"></div>
                            <?php if (form_error('supplier_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('supplier_id'); ?>
                            </div>
                            <?php } ?>

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
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="5" class="form-control" rows="7" name="note"
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

                <input type="hidden" name="<?php echo escape_output($this->security->get_csrf_token_name()); ?>"
                    value="<?php echo escape_output($this->security->get_csrf_hash()); ?>" />

                <div class="row px-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>SupplierPayment/supplierPayments">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                   
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
 
 
</section>