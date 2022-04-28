<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/add_transfer.css">
<script type="text/javascript" src="<?php echo base_url('frequent_changing/supplier.js'); ?>"></script>

<input type="hidden" id="food_menu_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_food_menu" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="paid_field_required" value="<?php echo lang('paid_field_required'); ?>">
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="alert" value="<?php echo lang('alert'); ?>">
<input type="hidden" id="ingredient" value="<?php echo lang('ingredient'); ?>">
<input type="hidden" id="food_menu" value="<?php echo lang('food_menu'); ?>">
<input type="hidden" id="transfer_id_c" value="<?php echo $encrypted_id?>">


<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/add_transfer.js'); ?>"></script>


<section class="main-content-wrapper">


    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit_transfer'); ?>
        </h3>
    </section>



    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Transfer/addEditTransfer/' . $encrypted_id, $arrayName = array('id' => 'transfer_form')) ?>
                <div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                       class="form-control" placeholder="<?php echo lang('ref_no'); ?>"
                                       value="<?=$transfer_details->reference_no?>">
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
                        <?php
                        $readonly_1 = '';
                        $readonly_11 = '';
                        $display_none_1 = '';
                        $dis_status = '';
                        $received_date = 'none';
                        $outlet_id = $this->session->userdata('outlet_id');
                        if($outlet_id==$transfer_details->to_outlet_id){
                            $readonly_1 = 'disabled';
                            $display_none_1 = 'none';
                            $readonly_11 = 'readonly';
                            $dis_status = 1;
                            $received_date = '';
                        }
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" readonly type="text" id="date" name="date" class="form-control"
                                       placeholder="<?php echo lang('date'); ?>" value="<?=$transfer_details->date?>">
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
                    <div class="col-md-3" style="display: <?=$received_date?>;">
                        <div class="form-group">
                            <label><?php echo lang('received_date'); ?> <span class="required_star">*</span></label>
                            <input tabindex="3" readonly type="text"  name="received_date" class="form-control customDatepicker"
                                   placeholder="<?php echo lang('received_date'); ?>" value="<?=date("Y-m-d",strtotime('today'))?>">
                        </div>
                        <?php if (form_error('received_date')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('received_date'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-3"  style="display: <?=$received_date?>;">
                        <div class="form-group">
                            <label><?php echo lang('from_outlet'); ?> <span class="required_star">*</span></label>
                            <select <?=$readonly_1?> tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="from_outlet_id" id="from_outlet_id">
                                <option value=""><?php echo lang('select'); ?></option>
                                <?php foreach ($outlets as $value) {
                                    $outlet_id = $this->session->userdata('outlet_id');
                                    if($transfer_details->from_outlet_id && $transfer_details->from_outlet_id==$value->id):
                                        ?>
                                        <option  <?=$transfer_details->from_outlet_id && $transfer_details->from_outlet_id==$value->id?'selected':''?>  value="<?php echo escape_output($value->id) ?>"
                                            <?php echo set_select('from_outlet_id', $value->id); ?>>
                                            <?php echo escape_output($value->outlet_name) ?></option>
                                        <?php
                                    endif;
                                } ?>
                            </select>
                        </div>
                        <?php if (form_error('from_outlet_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('from_outlet_id'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-3" style="display: <?=$display_none_1?>">
                        <div class="form-group">
                            <label><?php echo lang('to_outlet'); ?> <span class="required_star">*</span></label>
                            <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="to_outlet_id" id="to_outlet_id">
                                <option value=""><?php echo lang('select'); ?></option>
                                <?php foreach ($outlets as $value) {
                                    $outlet_id = $this->session->userdata('outlet_id');
                                    if($outlet_id!=$value->id):
                                    ?>
                                    <option  <?=$transfer_details->to_outlet_id && $transfer_details->to_outlet_id==$value->id?'selected':''?>  value="<?php echo escape_output($value->id) ?>"
                                        <?php echo set_select('to_outlet_id', $value->id); ?>>
                                        <?php echo escape_output($value->outlet_name) ?></option>
                                <?php
                                endif;
                                } ?>
                            </select>
                        </div>
                        <?php if (form_error('to_outlet_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('to_outlet_id'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('status'); ?> <span class="required_star">*</span></label>
                            <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="status" id="status">
                                <?php
                                    $outlet_id = $this->session->userdata('outlet_id');
                                    if($outlet_id==$transfer_details->from_outlet_id && ($transfer_details->status=="2" || $transfer_details->status=="3")):
                                ?>
                                <option <?=$transfer_details->status && $transfer_details->status=="2"?'selected':''?>  value="2">Draft</option>
                                <option <?=$transfer_details->status && $transfer_details->status=="3"?'selected':''?>  value="3">Sent</option>
                                <option disabled value="1">Received</option>
                                <?php
                                    endif;
                                ?>
                                <?php
                                if($outlet_id!=$transfer_details->from_outlet_id):
                                ?>
                                <option disabled value="2">Draft</option>
                                <option disabled value="3">Sent</option>
                                <option <?=$transfer_details->status && $transfer_details->status=="1"?'selected':''?>  value="1">Received</option>
                                    <?php
                                endif;
                                ?>
                            </select>
                            <?php if (form_error('status')) { ?>
                                <div class="callout callout-danger my-2">
                                    <?php echo form_error('status'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-2"  style="display: <?php echo $display_none_1?>">
                        <div class="form-group">
                            <label> <?php echo lang('transfer_type'); ?></label>
                            <select tabindex="2" class="form-control select2" name="transfer_type" id="transfer_type">
                                <option <?php echo set_select('transfer_type',"1")?> <?=$transfer_details->transfer_type && $transfer_details->transfer_type=="1"?'selected':''?>  value="1"><?php echo lang('ingredient'); ?></option>
                                <option <?php echo set_select('transfer_type',"2")?> <?=$transfer_details->transfer_type && $transfer_details->transfer_type=="2"?'selected':''?>  value="2"><?php echo lang('food_menu'); ?></option>
                            </select>
                        </div>
                        <?php if (form_error('transfer_type')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('transfer_type'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-3 ing_div" style="display: <?php echo $display_none_1?>">

                        <div class="form-group">
                            <label><?php echo lang('ingredients'); ?> <span class="required_star">*</span></label>
                            <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100" id="ingredient_id">
                                <option value=""><?php echo lang('select'); ?></option>
                                <?php foreach ($ingredients as $ingnts) { ?>
                                    <option
                                            value="<?php echo escape_output($ingnts->id . "||" . $ingnts->name . " (" . $ingnts->code . ")||" . $ingnts->purchase_price. "||" . $ingnts->unit_name) ?>"
                                        <?php echo set_select('unit_id', $ingnts->id); ?>>
                                        <?php echo escape_output($ingnts->name . "(" . $ingnts->code . ")") ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if (form_error('ingredient_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('ingredient_id'); ?>
                            </div>
                        <?php } ?>
                        <div class="callout callout-danger my-2 error-msg ingredient_id_err_msg_contnr">
                            <p id="ingredient_id_err_msg"></p>
                        </div>
                    </div>
                    <input type="hidden" id="is_disabled_change" value="<?php echo $dis_status?>">
                    <div class="col-sm-12 col-md-4 col-lg-3 mb-2 display_none men_div"  style="display: <?php echo $display_none_1?>">
                        <div class="form-group">
                            <label><?php echo lang('food_menus'); ?> <span class="required_star">*</span></label>
                            <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100" id="food_menu_id">
                                <option value=""><?php echo lang('select'); ?></option>
                                <?php foreach ($food_menus as $ingnts) { ?>
                                <option
                                        value="<?php echo escape_output($ingnts->id . "||" . $ingnts->name . " (" . $ingnts->code . ")||||".$ingnts->ings_total_cost."||".$ingnts->sale_price."||".$ingnts->total_tax) ?>"
                                    <?php echo set_select('unit_id', $ingnts->id); ?>>
                                    <?php echo escape_output($ingnts->name . "(" . $ingnts->code . ")") ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if (form_error('food_menu_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('food_menu_id'); ?>
                            </div>
                        <?php } ?>
                        <div class="callout callout-danger my-2 error-msg food_menu_id_err_msg_contnr">
                            <p id="food_menu_id_err_msg"></p>
                        </div>
                    </div>



                    <div class="col-md-3" style="display: <?php echo $display_none_1?>">
                        <div class="hidden-xs hidden-sm mt-2">&nbsp;</div>
                        <a class="btn bg-red-btn" data-bs-toggle="modal"
                           data-bs-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                    </div>
                    <div class="hidden-lg hidden-sm">&nbsp;</div>
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
                                    <th><?php echo lang('actions'); ?></th>
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
                                            <td><span><?=$name?></span><input type="hidden" id="ingredient_id_<?=$key?>" name="ingredient_id[]" value="<?=$value->ingredient_id?>"></td>
                                            <td><input type="text" data-countid="1" tabindex="51"  value="<?=$value->quantity_amount?>" id="quantity_amount_1" name="quantity_amount[]" onfocus="this.select();"  <?=$readonly_11?> class="form-control integerchk aligning countID cal_culate" placeholder="Qty/Amount"><span class="label_aligning"><?=$unit?></span></td>
                                            <th class="men_div display_none"> <input type="text" readonly data-countid="1" tabindex="51"  value="<?=$value->total_cost?>" id="total_cost_1" name="total_cost[]" onfocus="this.select();"  <?=$readonly_11?> class="form-control integerchk aligning countID total_cost_c" placeholder="Total Cost"  data-total_cost="<?=$value->single_cost_total?>" data-total_tax="<?=$value->total_tax?>"  data-total_sale_amount="<?=$value->total_sale_amount?>" ><input type="hidden" name="single_cost_total[]" value="<?=$value->single_cost_total?>"> <input type="hidden" class="total_sale_amount_c" name="total_sale_amount[]" value="<?=$value->total_sale_amount?>"><input type="hidden" class="total_tax_c" name="total_tax[]" value="<?=$value->total_tax?>"><input type="hidden"  name="single_total_sale_amount[]" value="<?=$value->single_total_sale_amount?>"><input type="hidden"  name="single_total_tax[]" value="<?=$value->single_total_tax?>"></td>
                                            <td><a style="display: <?=$display_none_1?>" class="btn btn-danger btn-xs row_delete"><i class="custom_txt_8 fa fa-trash"></i> </a></td>
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
                        <?php
                            $readonly_1 = '';
                            $outlet_id = $this->session->userdata('outlet_id');
                            if($outlet_id==$transfer_details->to_outlet_id){
                                $readonly_1 = 'readonly';
                            }
                        ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('note_for_sender'); ?></label>
                            <textarea  <?=$readonly_1?> class="form-control" placeholder="<?php echo lang('note_for_sender'); ?>"  name="note_for_sender"
                                      id="note_for_sender"><?=$transfer_details->note_for_sender?></textarea>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="clearfix"></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <?php
                        $readonly_2 = '';
                        $outlet_id = $this->session->userdata('outlet_id');
                        if($outlet_id==$transfer_details->from_outlet_id){
                            $readonly_2 = 'readonly';
                        }
                        ?>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo lang('note_for_receiver'); ?></label>
                            <textarea  <?=$readonly_2?>  class="form-control" placeholder="<?php echo lang('note_for_receiver'); ?>"  name="note_for_receiver"
                                      id="note_for_receiver"><?php echo set_value('note_for_receiver'); ?><?=$transfer_details->note_for_receiver?></textarea>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>

                <div class="row">

                    <input class="form-control" readonly type="hidden" name="subtotal" id="subtotal">

                </div>
            </div>
            <input type="hidden" name="suffix_hidden_field" id="suffix_hidden_field" />

            <div class="row my-2">
                <div class="col-sm-12 col-md-2 mb-2">
                    <button type="submit" name="submit" value="submit"
                            class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                </div>
                <div class="col-sm-12 col-md-2 mb-2">
                    <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Transfer/transfers">
                        <?php echo lang('back'); ?>
                    </a>
                </div>
            </div>
            <?php echo form_close(); ?>
    </div>
    
</section>

<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="noticeModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true"><i data-feather="x"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 hidden-lg hidden-sm">
                            <p class="foodMenuCartNotice">
                                <strong class="ir_ml39"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-sm">
                            <p class="foodMenuCartNotice">
                                <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                        <div class="col-md-12 hidden-xs hidden-lg">
                            <p class="foodMenuCartNotice">
                                <strong class="ir_m_l_45"><?php echo lang('notice'); ?></strong><br>
                                <?php echo lang('notice_text_1'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
