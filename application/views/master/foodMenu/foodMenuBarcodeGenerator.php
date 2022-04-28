<link rel="stylesheet" href="<?= base_url()?>assets/buttonCSS/checkBotton2.css">



 <section class="main-content-wrapper">

 <?php

if ($this->session->flashdata('exception')) {

    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <p><i class="fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));
    echo '</p></div></div></section>';
}
?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-8">
                <h2 class="top-left-header"><?php echo lang('item_barcode'); ?> </h2>
            </div>
        </div>
    </section>

         <div class="box-wrapper">
             <!-- general form elements -->
             <div class="table-box">
                 <!-- /.box-header -->
                 <?php echo form_open(base_url() . 'foodMenu/foodMenuBarcode', $arrayName = array('id' => 'foodMenuBarcode','enctype'=>'multipart/form-data')) ?>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <select name="barcode_width" class="form-control select2">
                            <option value="1"><?php echo lang('barcode_width'); ?></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <input type="number" value="" onfocus="select()" class="form-control" name="barcode_height" placeholder="Barcode Height(px)">
                        <span class="ms-2">px</span>
                    </div>
                    <div class="clearfix my-2"></div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100 alertClass"><?php echo lang('generate_now'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/foodMenus">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                 
                 <table id="datatable_id" class="table datatable">
                     <thead>
                        <tr>
                            <th hidden></th>
                            <th class="custom_txt_1">
                                <div class="form-group">
                                    <label class="container ir_w_89"> <?php echo lang('select_all'); ?>
                                        <input class="checkbox_userAll" type="checkbox" id="checkbox_userAll">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </th>
                            <th  class="ir_w_2"><?php echo lang('code'); ?></th>
                            <th class="ir_w_16"><?php echo lang('name'); ?></th>
                            <th class="ir_w_10"><?php echo lang('category'); ?></th>
                            <th  class="ir_w_7"><?php echo lang('sale_price'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php
                     if ($foodMenus && !empty($foodMenus)) {
                         $i = count($foodMenus);
                     }
                     $previous_price = (array)json_decode($outlet_information->food_menu_prices);
                     foreach ($foodMenus as $ingrnts) {
                         $sale_price = isset($previous_price["tmp".$ingrnts->id]) && $previous_price["tmp".$ingrnts->id]?$previous_price["tmp".$ingrnts->id]:$ingrnts->sale_price;
                         ?>
                         <tr>
                             <td hidden></td>
                             <td class="ir_txt_center">
                                     <div class="d-flex align-items-center">
                                         <div class="ir_w_25"> 
                                             <input class="ir_w_100 ir_txt_center" disabled type="number" min="1" id="qty<?=$ingrnts->id?>" onfocus="select();" name="qty[]" value="">
                                        </div>
                                         <div class="form-group">  
                                             <label class="container"><?php echo lang('select'); ?>
                                                 <input type="checkbox"  class="checkbox_user" data-menu_id="<?=$ingrnts->id?>" value="<?=$ingrnts->id."|".$ingrnts->name."|".$ingrnts->code."|".$sale_price?>" name="food_menu_id[]"?>
                                                 <span class="checkmark"></span>
                                             </label>
                                            </div>
                                     </div>
                             </td>
                             <td><?php echo escape_output($ingrnts->code); ?></td>
                             <td><?php echo escape_output($ingrnts->name); ?></td>
                             <td><?php echo foodMenucategoryName($ingrnts->category_id); ?></td>
                             <td> <?php echo getAmt($sale_price); ?></td>
                         </tr>
                         <?php
                     }
                     ?>
                     </tbody>
                 </table>
                
                 <div class="row mt-2">
                     <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100 alertClass"><?php echo lang('generate_now'); ?></button>
                     </div>
                     <div class="col-sm-12 col-md-2 mb-2">
                         <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/foodMenus">
                            <?php echo lang('back'); ?>
                        </a>
                     </div>
                 </div> 
                 <?php echo form_close(); ?>
                 <!-- /.box-body -->
             </div>
         </div>
     
 </section>
 <!-- DataTables -->
 <script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
</script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>


<script src="<?php echo base_url(); ?>frequent_changing/js/foodmenubarcode.js"></script>
