

 <section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('add_ingredient'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                 <!-- form start -->
                 <?php echo form_open(base_url('ingredient/addEditIngredient')); ?>
                 <div>
                     <div class="row">
                         <div class="col-sm-12 mb-2 col-md-6">

                             <div class="form-group">
                                 <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                 <input tabindex="1" type="text" name="name" class="form-control"
                                     placeholder="<?php echo lang('name'); ?>" value="<?php echo set_value('name'); ?>">
                             </div>
                             <?php if (form_error('name')) { ?>
                             <div class="callout callout-danger my-2">
                                 <?php echo form_error('name'); ?>
                             </div>
                             <?php } ?>

                             <div class="form-group">
                                 <label><?php echo lang('category'); ?> <span class="required_star">*</span></label>
                                 <select tabindex="2" class="form-control select2 ir_w_100" name="category_id">
                                     <option value=""><?php echo lang('select'); ?></option>
                                     <?php foreach ($categories as $ctry) { ?>
                                     <option value="<?php echo escape_output($ctry->id) ?>"
                                         <?php echo set_select('category_id', $ctry->id); ?>>
                                         <?php echo escape_output($ctry->category_name) ?></option>
                                     <?php } ?>
                                 </select>
                             </div>
                             <?php if (form_error('category_id')) { ?>
                             <div class="callout callout-danger my-2">
                                 <span class="error_paragraph"><?php echo form_error('category_id'); ?>
                                 </span>
                             </div>
                             <?php } ?>

                             <div class="form-group">
                                 <label><?php echo lang('unit'); ?> <span class="required_star">*</span></label>
                                 <select tabindex="3" class="form-control select2 ir_w_100" name="unit_id">
                                     <option value=""><?php echo lang('select'); ?></option>
                                     <?php foreach ($units as $unts) { ?>
                                     <option value="<?php echo escape_output($unts->id) ?>"
                                         <?php echo set_select('unit_id', $unts->id); ?>><?php echo escape_output($unts->unit_name) ?>
                                     </option>
                                     <?php } ?>
                                 </select>
                             </div>
                             <?php if (form_error('unit_id')) { ?>
                             <div class="callout callout-danger my-2">
                                 <span class="error_paragraph"><?php echo form_error('unit_id'); ?>
                                 </span>
                             </div>
                             <?php } ?>


                         </div>
                         <div class="col-sm-12 mb-2 col-md-6">

                             <div class="form-group">
                                 <label><?php echo lang('purchase_price'); ?> <span
                                         class="required_star">*</span></label>
                                <div class="d-flex">
                                    <input tabindex="4" type="text" name="purchase_price"
                                                         class="form-control integerchk"
                                                         placeholder="<?php echo lang('purchase_price'); ?>"
                                                         value="<?php echo set_value('purchase_price'); ?>">

                                    <div class="tooltip_custom">
                                        <i class="fa fa-question fa-2x" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_txt_25'); ?>" class="form_question"></i>
                                     </div>
                                </div>
                             </div>
                             <?php if (form_error('purchase_price')) { ?>
                             <div class="callout callout-danger my-2">
                                 <?php echo form_error('purchase_price'); ?>
                             </div>
                             <?php } ?>

                             <div class="form-group">
                                 <label><?php echo lang('alert_qty'); ?> <span class="required_star">*</span></label>
                                 <input tabindex="5" type="text" name="alert_quantity" class="form-control integerchk"
                                     placeholder="<?php echo lang('alert_qty'); ?>"
                                     value="<?php echo set_value('alert_quantity'); ?>">
                             </div>
                             <?php if (form_error('alert_quantity')) { ?>
                             <div class="callout callout-danger my-2">
                                 <?php echo form_error('alert_quantity'); ?>
                             </div>
                             <?php } ?>

                             <div class="form-group">
                                 <label><?php echo lang('code'); ?></label>
                                 <input tabindex="6" type="text" name="code" class="form-control"
                                     placeholder="<?php echo lang('code'); ?>" value="<?php echo escape_output($autoCode); ?>">
                             </div>
                         </div>

                     </div>
                 </div>
                 <!-- /.box-body -->

                 <div class="row mt-2">
                     <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                         class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                     </div>
                     <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>ingredient/ingredients">
                            <?php echo lang('back'); ?>
                        </a>
                     </div>
                    
                     
                 </div>
                 <?php echo form_close(); ?>
        </div>
    </div>
 </section>