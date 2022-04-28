<section class="content-header">
    <h1>
        <?php echo lang('modifier_details'); ?>
    </h1>  
</section>

<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box"> 
                <!-- form start --> 
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <h3><?php echo lang('name'); ?></h3>
                                <p class=""><?php echo escape_output($food_menu_details->name) ?></p>
                            </div> 
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <h3><?php echo lang('price'); ?></h3>
                                <p class=""> <?php echo escape_output($food_menu_details->price) ?></p>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('description'); ?></h3>
                                <p class=""><?php echo escape_output($food_menu_details->description) ?></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                $collect_tax = $this->session->userdata('collect_tax');
                                if(isset($collect_tax) && $collect_tax=="Yes"):
                                ?>
                                <h3><?php echo strtoupper(lang('vat')); ?></h3>
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
                                <h3><?php echo lang('ingredient_consumptions'); ?></h3> 
                            </div>  
                        </div>  
                    </div>  

                    <?php $food_menu_ingredients = modifierIngredients($food_menu_details->id); ?>

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

                <div class="box-footer"> 
                    <a href="<?php echo base_url() ?>modifier/addEditModifier/<?php echo escape_output($encrypted_id); ?>"><button type="button" class="btn btn-primary"><?php echo lang('edit'); ?></button></a>
                    <a href="<?php echo base_url() ?>modifier/modifiers"><button type="button" class="btn btn-primary"><?php echo lang('back'); ?></button></a>
                </div> 
            </div>
        </div>
    </div>
</section>