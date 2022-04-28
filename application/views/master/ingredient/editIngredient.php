 <section class="content-header">
     <h1>
         <?php echo lang('edit'); ?> <?php echo lang('ingredient'); ?>
     </h1>
 </section>

 <section class="main-content-wrapper">
     <div class="row">
         <div class="col-md-12">
             <!-- general form elements -->
             <div class="table-box">
                 <!-- form start -->
                 <?php echo form_open(base_url('ingredient/addEditIngredient/' . $encrypted_id)); ?>
                 <div class="box-body">
                     <div class="row">
                         <div class="col-md-6">

                             <div class="form-group">
                                 <label><?php echo lang('name'); ?> <span class="required_star">*</span></label>
                                 <input tabindex="1" type="text" name="name" class="form-control"
                                     placeholder="<?php echo lang('name'); ?>"
                                     value="<?php echo escape_output($ingredient_information->name) ?>">
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
                                     <option value="<?php echo escape_output($ctry->id) ?>" <?php
                                        if ($ctry->id == $ingredient_information->category_id) {
                                            echo "selected";
                                        }
                                        ?>><?php echo escape_output($ctry->category_name) ?></option>
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
                                     <option value="<?php echo escape_output($unts->id) ?>" <?php
                                                if ($unts->id == $ingredient_information->unit_id) {
                                                    echo "selected";
                                                }
                                                ?>><?php echo escape_output($unts->unit_name) ?></option>
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
                         <div class="col-md-6">

                             <div class="form-group">
                                 <label><?php echo lang('purchase_price'); ?> <span
                                         class="required_star">*</span></label>
                                 <div class="row">
                                     <div class="col-md-12">
                                         <table class="ir_w_100">
                                             <tr>
                                                 <td> <input tabindex="4" type="text" name="purchase_price"
                                                         class="form-control integerchk"
                                                         placeholder="<?php echo lang('purchase_price'); ?>"
                                                         value="<?php echo escape_output($ingredient_information->purchase_price) ?>">
                                                 </td>
                                                 <td>
                                                     <div class="tooltip_custom"><i class="fa fa-question fa-lg form_question"></i>
                                                         <span class="tooltiptext_custom"><?php echo lang('tooltip_txt_25'); ?></span>
                                                     </div>
                                                 </td>
                                             </tr>
                                         </table>
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
                                     value="<?php echo escape_output($ingredient_information->alert_quantity) ?>">
                             </div>
                             <?php if (form_error('alert_quantity')) { ?>
                             <div class="callout callout-danger my-2">
                                 <?php echo form_error('alert_quantity'); ?>
                             </div>
                             <?php } ?>

                             <div class="form-group">
                                 <label><?php echo lang('code'); ?></label>
                                 <input tabindex="6" type="text" name="code" class="form-control"
                                     placeholder="<?php echo lang('code'); ?>"
                                     value="<?php echo escape_output($ingredient_information->code) ?>">
                             </div>
                         </div>

                     </div>
                 </div>
                 <!-- /.box-body -->

                 <div class="box-footer">
                     <button type="submit" name="submit" value="submit"
                         class="btn btn-primary"><?php echo lang('submit'); ?></button>
                     <a href="<?php echo base_url() ?>ingredient/ingredients"><button type="button"
                             class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                 </div>
                 <?php echo form_close(); ?>
             </div>
         </div>
     </div>

 </section>