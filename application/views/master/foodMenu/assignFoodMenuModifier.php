<link rel="stylesheet" href="<?= base_url('assets/') ?>buttonCSS/checkBotton.css">


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('assign_food_menu_modifier'); ?>
        </h3>
    </section>
    
    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- form start -->
                <?php echo form_open(base_url() . 'foodMenu/assignFoodMenuModifier/' . $encrypted_id, $arrayName = array('id' => 'food_menu_form', 'enctype' => 'multipart/form-data')) ?>

                <div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group mb-2">
                                <label class="container"> <?php echo lang('select_all'); ?>
                                    <input type="checkbox" id="checkbox_userAll">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <hr class="ir_m_p_0">
                            <?php
                            if (isset($modifiers)) {
                                foreach ($modifiers as $modifier) {

                                    $modifier_id_ = $modifier->id;
                                    //$checked='';
                                    if (!empty($food_menu_modifiers)) {
                                        foreach ($food_menu_modifiers as $fmm) {
                                            if (in_array($modifier_id_, $food_menu_modifiers)) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                        }
                                    } else {
                                        $checked = '';
                                    }
                                    ?>
                            <div class="form-group my-2">
                                <label class="container"><?php echo escape_output($modifier->name); ?>
                                    <input type="checkbox" class="checkbox_user" <?php echo escape_output($checked); ?>
                                        value="<?php echo escape_output($modifier->id); ?>" name="modifier_id[]"
                                        <?= set_checkbox('modifier_id[]', $modifier->id) ?>>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <?php
                                }
                            }
                            ?>

                            <?php if (form_error('modifier_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <span class="error_paragraph"><?php echo form_error('modifier_id'); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>




                    <!-- /.box-body -->

                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-2 mb-2">
                        <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>

                        </div>
                        <div class="col-sm-12 col-md-2 mb-2">
                        <a href="<?php echo base_url() ?>foodMenu/foodMenus"><button type="button"
                                class="btn bg-blue-btn w-100"><?php echo lang('back'); ?></button></a>
                        </div>
                        
                        
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
    </div>
        

</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/assign_food_menu.js"></script>