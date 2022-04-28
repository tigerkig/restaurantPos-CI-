<input type="hidden" id="ingredient_already_remain" value="<?php echo lang('ingredient_already_remain'); ?>">
<input type="hidden" id="supplier_field_required" value="<?php echo lang('supplier_field_required'); ?>">
<input type="hidden" id="date_field_required" value="<?php echo lang('date_field_required'); ?>">
<input type="hidden" id="at_least_ingredient" value="<?php echo lang('at_least_ingredient'); ?>">
<input type="hidden" id="paid_field_required" value="<?php echo lang('paid_field_required'); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="alert" value="<?php echo lang('alert'); ?>">
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/add_purchase.css">
<script type="text/javascript" src="<?php echo base_url('frequent_changing/supplier.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('frequent_changing/js/edit_purchase.js'); ?>"></script>


<section class="main-content-wrapper">
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('edit_purchase'); ?>
        </h3>
    </section>


    
        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <?php echo form_open(base_url() . 'Purchase/addEditPurchase/' . $encrypted_id, $arrayName = array('id' => 'purchase_form')) ?>
                <div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('ref_no'); ?></label>
                                <input tabindex="1" type="text" id="reference_no" readonly name="reference_no"
                                    class="form-control" placeholder="<?php echo lang('ref_no'); ?>"
                                    value="<?php echo escape_output($purchase_details->reference_no) ?>">
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

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('supplier'); ?> <span class="required_star">*</span> </label>
                                <div class="d-flex align-items-center">
                                        <div class="w-100">
                                            <select tabindex="2" class="form-control select2" id="supplier_id"
                                                name="supplier_id">
                                                <option value=""><?php echo lang('select'); ?></option>
                                                <?php
                                                foreach ($suppliers as $splrs) {
                                                    ?>
                                                <option value="<?php echo escape_output($splrs->id) ?>" <?php
                                                    if ($purchase_details->supplier_id == $splrs->id) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo escape_output($splrs->name) ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div> 
                                            <span class="plus-custom p-2 p-cursor" data-bs-toggle="modal" data-bs-target="#supplierModal">
                                                <i data-feather="plus"></i>
                                            </span>
                                        </div>
                                </div>
                            </div>
                            <?php if (form_error('supplier_id')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('supplier_id'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg supplier_id_err_msg_contnr">
                                <p id="supplier_id_err_msg"></p>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo lang('date'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" type="text" id="date" name="date" readonly class="form-control"
                                    placeholder="<?php echo lang('date'); ?>"
                                    value="<?php echo date('Y-m-d', strtotime($purchase_details->date)); ?>">
                            </div>
                            <?php if (form_error('date')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('date'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg date_err_msg_contnr">
                                <p id="date_err_msg"></p>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-4">

                            <div class="form-group">
                                <label><?php echo lang('ingredients'); ?> <span class="required_star">*</span></label>
                                <select tabindex="4" class="form-control select2 select2-hidden-accessible ir_w_100"
                                    name="ingredient_id" id="ingredient_id">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php foreach ($ingredients as $ingnts) { ?>
                                    <option
                                        value="<?php echo escape_output($ingnts->id . "|" . $ingnts->name . " (" . $ingnts->code . ")|" . $ingnts->unit_name . "|" . $ingnts->purchase_price) ?>"
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

                        <div class="col-sm-12 col-md-2">
                            <div class="mt-2 hidden-xs hidden-sm">&nbsp;</div>
                            <a class="btn bg-red-btn" data-bs-toggle="modal"
                                data-bs-target="#noticeModal"><?php echo lang('read_me_first'); ?></a>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="purchase_cart">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('sn'); ?></th>
                                            <th>
                                                <?php echo lang('ingredient'); ?>(<?php echo lang('code'); ?>)</th>
                                            <th><?php echo lang('unit_price'); ?> <span
                                                    class="ir_c_transparent">fdf</span></th>
                                            <th><?php echo lang('quantity_amount'); ?></th>
                                            <th><?php echo lang('total'); ?> <span
                                                    class="ir_c_transparent">Hiddentext</span></th>
                                            <th><?php echo lang('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        if ($purchase_ingredients && !empty($purchase_ingredients)) {
                                            foreach ($purchase_ingredients as $pi) {
                                                $i++;
                                                echo '<tr class="rowCount"  data-id="' . $i . '" id="row_' . $i . '">' .
                                                '<td class="txt_19"> <p id="sl_' . $i . '">' . $i . '</p></td>' .
                                                '<td class="txt_18">' . getIngredientNameById($pi->ingredient_id) . ' (' . getIngredientCodeById($pi->ingredient_id) . ')</span></td>' .
                                                '<input type="hidden" id="ingredient_id_' . $i . '" name="ingredient_id[]" value="' . $pi->ingredient_id . '"/>' .
                                                '<td><input type="text" id="unit_price_' . $i . '" name="unit_price[]" onfocus="this.select();" class="form-control integerchk aligning" placeholder="Unit Price" value="' . $pi->unit_price . '" onkeyup="return calculateAll();"/></td>' .
                                                '<td><input type="text" data-countID="' . $i . '" id="quantity_amount_' . $i . '" name="quantity_amount[]" onfocus="this.select();" class="form-control integerchk aligning countID" class="ir_w_85" placeholder="Qty/Amount" value="' . $pi->quantity_amount . '"  onkeyup="return calculateAll();" ><span class="label_aligning">' . unitName(getUnitIdByIgId($pi->ingredient_id)) . '</span></td>' .
                                                '<td><input type="text" id="total_' . $i . '" name="total[]" class="form-control integerchk aligning" placeholder="Total" value="' . $pi->total . '" readonly/></td>' .
                                                '<td><a class="btn bg-red-btn" onclick="return deleter(' . $i . ',' . $pi->ingredient_id . ');" ><i class="fa fa-trash txt_22"></i> </a></td>' .
                                                '</tr>'
                                                ;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('g_total'); ?> <span class="required_star">*</span></label>
                                <input class="form-control" readonly type="text" name="grand_total"
                                       id="grand_total" value="<?php echo escape_output($purchase_details->grand_total) ?>">
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="clearfix"></div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('paid'); ?> <span class="required_star">*</span></label>
                                <input tabindex="3" class="form-control integerchk" type="text" name="paid"
                                       id="paid" onfocus="this.select();" onkeyup="return calculateAll()"
                                       value="<?php echo escape_output($purchase_details->paid) ?>">
                            </div>
                            <?php if (form_error('paid')) { ?>
                            <div class="callout callout-danger my-2">
                                <?php echo form_error('paid'); ?>
                            </div>
                            <?php } ?>
                            <div class="callout callout-danger my-2 error-msg paid_err_msg_contnr">
                                <p id="paid_err_msg"></p>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="clearfix"></div>
                        <div class="col-md-8"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo lang('due'); ?></label>
                                <input class="form-control aligning_x ir_w_100" type="text" name="due" id="due"
                                       readonly value="<?php echo escape_output($purchase_details->due) ?>">
                            </div>
                        </div>
                    </div>
                    
                </div>

                <input type="hidden" name="suffix_hidden_field" id="suffix_hidden_field" />
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 mb-2">
                            <button type="submit" name="submit" value="submit" class="btn w-100 bg-blue-btn"><?php echo lang('submit'); ?></button>
                        </div>
                        <div class="col-sm-12  col-md-2 mb-2">
                            <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Purchase/purchases">
                                <?php echo lang('back'); ?>
                            </a>
                        </div>
                    </div>
              
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
  
        
</section>

<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
               
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo lang('add_supplier'); ?></h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i data-feather="x"></i>
                        </span>
                    </button>
            </div>

            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('supplier_name'); ?><span
                                        class="ir_color_red"> *</span></label>
                                <div>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="<?php echo lang('supplier_name'); ?>" value="">
                                    <div class="callout callout-danger my-2 error-msg supplier_err_msg_contnr">
                                        <p class="supplier_err_msg"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('contact_person'); ?><span
                                        class="ir_color_red"> *</span></label>
                                <div>
                                    <input type="text" class="form-control" name="contact_person" id="contact_person"
                                        placeholder="<?php echo lang('contact_person'); ?>" value="">
                                    <div class="callout callout-danger my-2 error-msg customer_err_msg_contnr">
                                        <p class="customer_err_msg"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('phone'); ?> <span class="ir_color_red">
                                        *</span></label>
                                <div>
                                    <input type="text" class="form-control integerchk" id="phone" name="phone"
                                        placeholder="<?php echo lang('phone'); ?>" value="">
                                    <div class="callout callout-danger my-2 error-msg customer_mobile_err_msg_contnr">
                                        <p class="customer_mobile_err_msg"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('email'); ?></label>
                                <div>
                                    <input type="text" class="form-control" id="emailAddress" name="emailAddress"
                                        placeholder="<?php echo lang('email'); ?>" value="">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('address'); ?></label>
                                <div>
                                    <textarea tabindex="4" class="form-control" rows="3" name="supAddress"
                                        placeholder="<?php echo lang('address'); ?>"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 mb-2">
                            <div class="form-group">
                                <label class="control-label"><?php echo lang('description'); ?></label>
                                <div>
                                    <textarea tabindex="4" class="form-control" rows="7" name="description"
                                        placeholder="<?php echo lang('enter'); ?> ..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn bg-blue-btn" id="addSupplier">
                    <i class="fa fa-save m-right"></i> <?php echo lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="noticeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Notice</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i data-feather="x"></i>
                    </span>
                </button>
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
<script type="text/javascript" src="<?php echo base_url('frequent_changing/supplier.js'); ?>"></script>