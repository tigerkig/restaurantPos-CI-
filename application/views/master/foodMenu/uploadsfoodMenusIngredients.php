
 <section class="main-content-wrapper">
    <div class="alert-wrapper">
        <?php
            if ($this->session->flashdata('exception')) {

                echo '<div class="alert alert-success alert-dismissible fade show"> 
                <h4 class="alert-heading">
                    Notice
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </h4>
                <div class="alert-body">
            <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
                echo escape_output($this->session->flashdata('exception'));
                echo '</p></div></div>';
            }
            if ($this->session->flashdata('exception_err')) {

                echo '<div class="alert alert-danger alert-dismissible"> 
                <h4 class="alert-heading">
                    Notice
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </h4>
                <div class="alert-body">
                    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
                echo escape_output($this->session->flashdata('exception_err'));
                echo '</p></div></div>';
            }
        ?>
    </div>

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('upload_food_menu_ingredients'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                 <!-- form start -->
                 <?php echo form_open_multipart(base_url('foodMenu/ExcelDataAddFoodmenusIngredients')); ?>
                 <div>
                     <div class="row">
                         <div class="col-md-12">

                             <div class="form-group">
                                 <label><?php echo lang('upload_file'); ?> <span class="required_star">*</span></label>
                                 <input tabindex="1" type="file" name="userfile" class="form-control"
                                     placeholder="<?php echo lang('upload_file'); ?>"
                                     value="<?php echo set_value('name'); ?>">
                             </div>
                             <div class="checkbox my-3 form-group">
                                 <label class="container">
                                    <input type="checkbox" name="remove_previous"
                                         value="1">
                                         <span><?php echo lang('remove_all_previous_data'); ?></span>
                                         <span class="checkmark"></span>
                                    </label>
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

                 <div class="row my-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                    
                    <button type="submit" name="submit" value="submit"
                         class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/foodMenus">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-3 mb-2">
                        <a class="btn  bg-blue-btn w-100"
                         href="<?php echo base_url() ?>foodMenu/downloadPDF/Food_Menu_Ingredients_Upload.xlsx">
                         <i class="fa fa-save m-right"></i> <?php echo lang('download_sample'); ?></a>
                    </div>
                 </div>
                 <?php echo form_close(); ?>
        </div>
    </div>
    

 </section>