<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/add_transfer.css">
<script type="text/javascript" src="<?php echo base_url('frequent_changing/supplier.js'); ?>"></script>

<input type="hidden" id="food_menu_already_remain" value="<?php echo lang('food_menu_already_remain'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_food_menu" value="<?php echo lang('at_least_food_menu'); ?>">
<input type="hidden" id="paid_field_required" value="<?php echo lang('paid_field_required'); ?>">
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="alert" value="<?php echo lang('alert'); ?>">
<input type="hidden" id="ingredient" value="<?php echo lang('ingredient'); ?>">
<input type="hidden" id="food_menu" value="<?php echo lang('food_menu'); ?>">
<input type="hidden" name="transfer_type" id="transfer_type" value="<?=$transfer_details->transfer_type && $transfer_details->transfer_type?$transfer_details->transfer_type:''?>">
<input type="hidden" id="is_disabled_change" value="">

<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/add_transfer.js'); ?>"></script>



<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('details_transfer'); ?>
        </h3>
    </section>

        <div class="box-wrapper">
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Transfer/addEditTransfer/' . $encrypted_id, $arrayName = array('id' => 'transfer_form')) ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <p><?=$transfer_details->reference_no?></p>
                            </div>
                            <?php if (form_error('reference_no')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('reference_no'); ?>
                                </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg name_err_msg_contnr">
                                <p id="name_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?></label>
                                <p>
                                    <?php echo escape_output(date($this->session->userdata('date_format'), strtotime($transfer_details->date))); ?></p>
                            </div>
                            <?php if (form_error('date')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('date'); ?>
                                </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg date_err_msg_contnr"">
                            <p id="date_err_msg">
                            </p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('to_outlet'); ?></label>
                            <p><?php echo escape_output(getOutletNameById($transfer_details->to_outlet_id)); ?></p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('status'); ?></label>
                            <p>
                                <?php
                                if($transfer_details->status==1){
                                    echo escape_output("Received");
                                }elseif($transfer_details->status==2){
                                    echo escape_output("Draft");
                                }elseif($transfer_details->status==3){
                                    echo escape_output("Sent");
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('transfer_type'); ?></label>
                            <p>
                                <?php
                                if($transfer_details->transfer_type==1){
                                    echo escape_output(lang('ingredient'));
                                }elseif($transfer_details->transfer_type==2){
                                    echo escape_output(lang('food_menu'));
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="transfer_cart">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th><?php echo lang('sn'); ?></th>
                                    <th>
                                        <span class="transfer_type_name"><?php echo lang('ingredient'); ?></span>(<?php echo lang('code'); ?>)</th>
                                    <th><?php echo lang('quantity_amount'); ?></th>
                                    <th class="men_div display_none"><?php echo lang('total_cost'); ?></th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($food_details as $key=>$value):
                                    $name = '';
                                    $unit = '';
                                    if($value->transfer_type==1){
                                        $name = getIngredientNameById($value->ingredient_id)." (".getIngredientCodeById($value->ingredient_id).")";
                                        $unit = unitName(getUnitIdByIgId($value->ingredient_id));
                                    }else{
                                        $name = getFoodMenuNameById($value->ingredient_id)." (".getFoodMenuCodeById($value->ingredient_id).")";
                                        $unit = "Pcs";
                                    }
                                    $key++;
                                    ?>
                                    <tr class="rowCount" data-item_id="<?=$value->ingredient_id?>" data-id="<?=$key?>" id="row_<?=$key?>">
                                        <td><p id="sl_<?=$key?>"><?=$key?></p></td>
                                        <td><span><?=$name?> </td>
                                        <td><?=getAmtP($value->quantity_amount)?> <?=$unit?></td>
                                        <td class="men_div display_none"><?=getAmtP($value->total_cost)?></td>

                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="clearfix"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('note_for_sender'); ?></label>
                           <p><?=$transfer_details->note_for_sender?></p>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="clearfix"></div> <div class="col-md-4"></div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('note_for_receiver'); ?></label>
                           <p><?=$transfer_details->note_for_receiver?></p>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="clearfix"></div>
                </div>

                <div class="row">

                    <input class="form-control" readonly type="hidden" name="subtotal" id="subtotal">

                </div>
            </div>
            <input type="hidden" name="suffix_hidden_field" id="suffix_hidden_field" />
            <div class="box-footer">
                <div class="col-sm-12 col-md-2">
                    <a class="btn bg-blue-btn"href="<?php echo base_url() ?>Transfer/transfers">
                        <?php echo lang('back'); ?>
                    </a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
</section>