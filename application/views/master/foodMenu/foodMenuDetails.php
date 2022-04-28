

<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('food_menu_details'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
                <!-- form start -->
                <div>
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <h5><?php echo lang('name'); ?></h5>
                                <p class=""><?php echo escape_output($food_menu_details->name) ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5><?php echo lang('category'); ?></h5>
                                <p class=""><?php echo escape_output(foodMenucategoryName($food_menu_details->category_id)); ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5><?php echo lang('sale_price'); ?></h5>
                                <p class="">
                                    <?php echo escape_output(getAmt($food_menu_details->sale_price)) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <h5><?php echo lang('is_it_veg'); ?>?</h5>
                                <p class="">
                                    <?php if($food_menu_details->veg_item == "Veg No"){ echo "No"; }else{ echo "Yes"; } ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5><?php echo lang('is_it_beverage'); ?>?</h5>
                                <p class="">
                                    <?php if($food_menu_details->beverage_item == "Bev No"){ echo "No"; }else{ echo "Yes"; } ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5><?php echo lang('is_it_bar'); ?>?</h5>
                                <p class="">
                                    <?php if($food_menu_details->bar_item == "Bar No"){ echo "No"; }else{ echo "Yes"; } ?>
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5><?php echo lang('photo'); ?></h5>
                                <?php if(!empty($food_menu_details->photo)){?>
                                <img class="img-responsive"
                                    src="<?= base_url() . "images/" . $food_menu_details->photo ?>"
                                    alt="Photo">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <h5><?php echo lang('description'); ?></h5>
                                <p class=""><?php echo escape_output($food_menu_details->description) ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                $collect_tax = $this->session->userdata('collect_tax');
                                if(isset($collect_tax) && $collect_tax=="Yes"):
                                ?>
                                <h5><?php echo strtoupper(lang('vat')); ?></h5>
                                <?php if($food_menu_details->tax_information != ''){
                                    $tax_information = json_decode($food_menu_details->tax_information);
                                    foreach ($tax_information as $keys=>$val):
                                    ?>
                                <span class="">
                                    <?php echo escape_output($val->tax_field_name); ?>: <?php echo escape_output($val->tax_field_percentage); ?>%
                                    <?php
                                        if($keys<(sizeof($tax_information)-1)){
                                            echo ", ";
                                        }
                                    ?>
                                </span>
                                <?php
                                endforeach;
                                }
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5><?php echo lang('ingredient_consumptions'); ?></h5>
                            </div>
                        </div>
                    </div>

                    <?php $food_menu_ingredients = foodMenuIngredients($food_menu_details->id); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="ingredient_consumption_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th><?php echo lang('ingredient'); ?></th>
                                            <th><?php echo lang('consumption'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if ($food_menu_ingredients && !empty($food_menu_ingredients)) {
                                            foreach ($food_menu_ingredients as $fmi) {
                                                $i++;
                                                echo "<tr>" .
                                                "<td class='txt_26'><p>" . $i . "</p></td>" .
                                                "<td><span>" . getIngredientNameById($fmi->ingredient_id) . "</span></td>" .
                                                "<td>" . getAmtP($fmi->consumption) . " " . unitName(getUnitIdByIgId($fmi->ingredient_id)) . "</td>" .
                                                "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="row mt-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                    <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/addEditFoodMenu/<?php echo escape_output($encrypted_id); ?>"><?php echo lang('edit'); ?>
                            </a>

                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">

                    <a  class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/foodMenus">
                        <?php echo lang('back'); ?>
                            </a>
                    </div>
                   
                    
                </div>
        </div>
    </div>
   

    <div class="modal fade" id="featuredPhoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="ShortCut">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i class="fa fa-2x">×</i></span></button>
                </div>
                <div class="modal-body">
                    <img class="img-fluid"
                        src="<?= base_url() . "assets/uploads/" . $food_menu_details->pc_original_thumb ?>"
                        alt="featured photo">
                </div>
            </div>
        </div>
    </div>
</section>