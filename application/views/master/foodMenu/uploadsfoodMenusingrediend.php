 <section class="content-header">
     <h1>
         Upload Foodmenu Ingredients
     </h1>

     <?php
    if ($this->session->flashdata('exception')) {

        echo '<div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div>';
    }
    if ($this->session->flashdata('exception_err')) {

        echo '<div class="alert alert-danger alert-dismissible"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception_err'));
        echo '</p></div>';
    }
    ?>
 </section>

 <section class="main-content-wrapper">
     <div class="row">
         <div class="col-md-12">
             <!-- general form elements -->
             <div class="table-box">
                 <!-- form start -->
                 <?php echo form_open_multipart(base_url('foodMenu/ExcelDataAddFoodmenusingredient')); ?>
                 <div class="box-body">
                     <div class="row">
                         <div class="col-md-12">

                             <div class="form-group">
                                 <label><?php echo lang('upload_file'); ?> <span class="required_star">*</span></label>
                                 <input tabindex="1" type="file" name="userfile" class="form-control"
                                     placeholder="Upload file" value="<?php echo set_value('name'); ?>">
                             </div>
                             <?php if (form_error('userfile')) { ?>
                             <div class="callout callout-danger my-2">
                                 <?php echo form_error('userfile'); ?>
                             </div>
                             <?php } ?>
                         </div>

                     </div>
                 </div>
                 <!-- /.box-body -->

                 <div class="box-footer">
                     <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('Submit'); ?></button>
                     <a href="<?php echo base_url() ?>foodMenu/foodMenus"><button type="button"
                             class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                     <a class="btn btn-primary"
                         href="<?php echo base_url() ?>foodMenu/downloadPDF/Food_Menu_Ingredients_Upload.xlsx">
                         <i class="fa fa-save"></i> <?php echo lang('download_sample'); ?></a>
                 </div>
                 <?php echo form_close(); ?>
             </div>
         </div>
     </div>

 </section>