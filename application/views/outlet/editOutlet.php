<link rel="stylesheet" href="<?= base_url() ?>frequent_changing/css/custom_check_box.css">


<!-- Main content -->
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>
    <section class="content-header">
        <h3 class="top-left-header">
            <?php
            $data_c = getLanguageManifesto();
            if(str_rot13($data_c[0])=="eriutoeri"){
                echo lang('edit_outlet');
            }else if(str_rot13($data_c[0])=="fgjgldkfg"){
                echo lang('outlet_setting');
            }
            ?>
        </h3>

    </section>



    <div class="box-wrapper">
            <div class="table-box">
                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url('Outlet/addEditOutlet/' . $encrypted_id)); ?>
                <div class="box-body">
                    <div class="row">
                        <?php
                        if(str_rot13($data_c[0])=="eriutoeri") {
                            ?>
                            <div class="col-sm-12 mb-2 col-md-6">
                                <div class="form-group">
                                    <label><?php echo lang('outlet_code'); ?> <span
                                                class="required_star">*</span></label>
                                    <input tabindex="1" autocomplete="off" type="text" name="outlet_code"
                                           class="form-control" onfocus="select();"
                                           placeholder="<?php echo lang('outlet_code'); ?>"
                                           value="<?php echo escape_output($outlet_information->outlet_code) ?>"/>
                                </div>
                                <?php if (form_error('outlet_code')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('outlet_code'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('outlet_name'); ?> <span class="required_star">*</span></label>
                                <input tabindex="1" autocomplete="off" type="text" name="outlet_name" class="form-control" placeholder="<?php echo lang('outlet_name'); ?>" value="<?php echo escape_output($outlet_information->outlet_name); ?>">
                            </div>
                            <?php if (form_error('outlet_name')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('outlet_name'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('phone'); ?> <span class="required_star">*</span></label>
                                <input tabindex="4" autocomplete="off" type="text" name="phone" class="form-control" placeholder="<?php echo lang('phone'); ?>" value="<?php echo escape_output($outlet_information->phone); ?>">
                            </div>
                            <?php if (form_error('phone')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('phone'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('email'); ?> </label>
                                <input tabindex="4" autocomplete="off" type="text" name="email" class="form-control" placeholder="<?php echo lang('email'); ?>" value="<?php echo escape_output($outlet_information->email); ?>" />
                            </div>
                            <?php if (form_error('email')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('email'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        
                        <?php
                        $language_manifesto = $this->session->userdata('language_manifesto');
                        if(str_rot13($language_manifesto)=="eriutoeri"):
                            ?>
                        <div class="col-sm-12 mb-2 col-md-6">
                            <div class="form-group">
                                <label><?php echo lang('Active_Status'); ?> <span class="required_star">*</span></label>
                                <select class="form-control select2" name="active_status" id="active_status">
                                    <option <?php echo isset($outlet_information->active_status) && $outlet_information->active_status=="active"?'selected':''?> value="active"><?php echo lang('Active'); ?></option>
                                    <option <?php echo isset($outlet_information->active_status) && $outlet_information->active_status=="inactive"?'selected':''?> value="inactive"><?php echo lang('Inactive'); ?></option>
                                </select>
                            </div>
                            <?php if (form_error('active_status')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('active_status'); ?>
                                </div>
                            <?php } ?>
                        </div>

                            <div class="col-sm-12 mb-2 col-md-6">

                                <div class="form-group">
                                    <label> <?php echo lang('Default_Waiter'); ?></label>
                                    <select tabindex="2" class="form-control select2" name="default_waiter" id="default_waiter">
                                        <option value=""><?php echo lang('select'); ?></option>
                                        <?php
                                        foreach ($waiters as $value):
                                            if($value->designation=="Waiter"):
                                                ?>
                                                <option <?=($outlet_information->default_waiter==$value->id?'selected':'')?>  value="<?=$value->id?>"><?=$value->full_name?></option>
                                                <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <?php if (form_error('default_waiter')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('default_waiter'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-12 mb-2 col-md-6">
                                <div class="form-group">
                                    <label> <?php echo lang('has_kitchen'); ?></label>
                                    <select tabindex="2" class="form-control select2" name="has_kitchen" id="has_kitchen">
                                        <option <?php echo set_select('has_kitchen',"Yes")?> <?php echo isset($outlet_information->has_kitchen) && $outlet_information->has_kitchen=="Yes"?'selected':''?> value="Yes"><?php echo lang('yes'); ?></option>
                                        <option <?php echo set_select('has_kitchen',"No")?> <?php echo isset($outlet_information->has_kitchen) && $outlet_information->has_kitchen=="No"?'selected':''?> value="No"><?php echo lang('no'); ?></option>
                                    </select>
                                </div>
                                <?php if (form_error('has_kitchen')) { ?>
                                    <div class="callout callout-danger my-2">
                                        <?php echo form_error('has_kitchen'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        endif;
                        ?>
                        
                        <div class="col-sm-12 mb-2 col-md-6">

                            <div class="form-group">
                                <label><?php echo lang('address'); ?> <span class="required_star">*</span></label>
                                <textarea tabindex="3" autocomplete="off" name="address" class="form-control" placeholder="<?php echo lang('address'); ?>"><?php echo escape_output($outlet_information->address); ?></textarea>
                            </div>
                            <?php if (form_error('address')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('address'); ?>
                                </div>
                            <?php } ?>

                        </div>

                    </div>

                    <?php
                    $language_manifesto = $this->session->userdata('language_manifesto');
                    if(str_rot13($language_manifesto)=="eriutoeri"):
                    ?>
                    
                    <div class="row my-3">
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('tooltip_txt_26'); ?></label>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6 col-md-4">
                            <label class="container txt_48"> <?php echo lang('select_all'); ?>
                                <input class="checkbox_userAll" type="checkbox" id="checkbox_userAll">
                                <span class="checkmark"></span>
                            </label>
                        </div>    
                    </div>
                    <hr>
                    <div class="row">
                        <?php
                        foreach ($items as $item) {
                            $checked = '';
                            $new_id = $item->id;
                            if (isset($selected_modules_arr)):
                                foreach ($selected_modules_arr as $uma) {
                                    if (in_array($new_id, $selected_modules_arr)) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                                }
                            endif;
                            $previous_price = (array)json_decode($outlet_information->food_menu_prices);
                            $sale_price = isset($previous_price["tmp".$item->id]) && $previous_price["tmp".$item->id]?$previous_price["tmp".$item->id]:$item->sale_price;
                            ?>
                            <div class="col-sm-12 col-md-4 mb-2">
                                <label class="container txt_47"> <?="<span>".$item->name."</span>"?>
                                    <input class="checkbox_user child_class" <?=$checked?> data-name="<?php echo str_replace(' ', '_', $item->name)?>" value="<?=$item->id?>" type="checkbox" name="item_check[]">
                                    <span class="checkmark"></span>
                                </label>
                                <div class="form-group outlet-price-field">
                                    <label class="txt_outlet_1"><?php echo lang('price'); ?></label>
                                    <input  type="text" value="<?php echo escape_output($sale_price)?>" name="price_<?php echo $item->id?>" placeholder="<?php echo lang('price');?>" onfocus="select()" class="form-control txt_outlet_2">
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
                <!-- /.box-body -->
                <?php
                $data_c = getLanguageManifesto();
                ?>
                <div class="row my-3">
                    <div class="col-sm-12 col-md-2">
                        <button type="submit" name="submit" value="submit" class="w-100 btn bg-blue-btn"><?php echo lang('submit'); ?></button>
                    </div>
                    <div class="col-sm-12 col-md-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?><?php echo escape_output($data_c[1])?>">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
    
    
</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/edit_outlet.js"></script>