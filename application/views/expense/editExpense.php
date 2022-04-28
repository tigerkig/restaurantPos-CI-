

<section class="main-content-wrapper">

    <section class="content-header lang_arabic">
        <h3 class="top-left-header">
            <?php echo lang('edit_expense'); ?>
        </h3>
    </section>


        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url('Expense/addEditExpense/' . $encrypted_id)); ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" type="text" id="date" name="date" readonly class="form-control"
                                    placeholder="<?php echo lang('date'); ?>"
                                    value="<?php echo escape_output(date("Y-m-d", strtotime($expense_information->date))); ?>">
                            </div>
                            <?php if (form_error('date')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('date'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <label><?php echo lang('amount'); ?> <span class="required_star">*</span></label>
                                <input tabindex="2" type="text" name="amount" onfocus="this.select();"
                                    class="form-control integerchk" placeholder="<?php echo lang('amount'); ?>"
                                    value="<?php echo escape_output($expense_information->amount) ?>">
                            </div>
                            <?php if (form_error('amount')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('amount'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <label><?php echo lang('category'); ?> <span class="required_star">*</span></label>
                                <select tabindex="3" class="form-control select2 ir_w_100" name="category_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($expense_categories as $ec) { ?>
                                    <option value="<?php echo escape_output($ec->id) ?>" <?php
                                        if ($expense_information->category_id == $ec->id) {
                                            echo "selected";
                                        }
                                        ?>>
                                        <?php echo escape_output($ec->name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('category_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('category_id'); ?>
                            </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('responsible_person'); ?> <span
                                        class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 ir_w_100" name="employee_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($employees as $empls) { ?>
                                    <option value="<?php echo escape_output($empls->id) ?>" <?php
                                        if ($expense_information->employee_id == $empls->id) {
                                            echo "selected";
                                        }
                                        ?>>
                                        <?php echo escape_output($empls->full_name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (form_error('employee_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('employee_id'); ?>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <label><?php echo lang('note'); ?></label>
                                <textarea tabindex="5" class="form-control" rows="4" name="note"
                                    placeholder="<?php echo lang('enter'); ?> ..."><?php echo escape_output($expense_information->note) ?></textarea>
                            </div>
                            <?php if (form_error('note')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('note'); ?>
                            </div>
                            <?php } ?>


                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="bg-blue-btn w-100" href="<?php echo base_url() ?>Expense/expenses">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
</section>