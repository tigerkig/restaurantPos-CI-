<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">



<section class="main-content-wrapper">
        <section class="content-header">
            <h3 class="top-left-header text-left"><?php echo lang('detailed_sale_report'); ?></h3>
            <input type="hidden" class="datatable_name" data-title="<?php echo lang('detailed_sale_report'); ?>" data-id_name="datatable">
           
        </section>

        <div>
                    <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4 class="txt-color-grey"> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4 class="txt-color-grey"><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
                    <h4 class="txt-color-grey ir_txtCenter_mt0"><?php
                    if (isset($user_id) && $user_id):
                        echo lang('user').": " . userName($user_id);
                    else:
                        echo lang('user').": ".lang('all');
                    endif;
                    ?></h4>
                    <h4 class="txt-color-grey ir_txtCenter_mt0"><?php
                        if (isset($waiter_id) && $waiter_id):
                            echo lang('waiter').": " . userName($waiter_id);
                        else:
                            echo lang('waiter').": ".lang('all');
                        endif;
                        ?></h4>
                        
        </div>


        <div class="box-wrapper">
            <div class="row">
                    <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                        <?php echo form_open(base_url() . 'Report/detailedSaleReport') ?>
                        <div class="form-group">
                            <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                                placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3 col-md-4 col-lg-2">

                        <div class="form-group">
                            <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                                class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                                value="<?php echo set_value('endDate'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                        <div class="form-group">
                            <select tabindex="2" class="form-control select2 ir_w_100" id="user_id" name="user_id">
                                <option value=""><?php echo lang('user'); ?></option>
                                <option value="<?= escape_output($this->session->userdata['user_id']); ?>">
                                    <?= escape_output($this->session->userdata['full_name']); ?></option>
                                <?php
                                foreach ($users as $value) {
                                    ?>
                                <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('user_id', $value->id); ?>>
                                    <?php echo escape_output($value->full_name) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                        <div class="form-group">
                            <select tabindex="2" class="form-control select2 ir_w_100" id="waiter_id" name="waiter_id">
                                <option value=""><?php echo lang('waiter'); ?></option>
                            <?php
                                foreach ($users as $value) {
                                    if($value->designation=="Waiter"):
                                    ?>
                                    <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('waiter_id', $value->id); ?>>
                                        <?php echo escape_output($value->full_name) ?></option>
                                <?php
                                    endif;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <?php
                    if(isLMni()):
                        ?>
                        <div class="col-sm-12 mb-3 col-md-4 col-lg-2">
                            <div class="form-group">
                                <select tabindex="2" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                                    <?php
                                    $outlets = getAllOutlestByAssign();
                                    foreach ($outlets as $value):
                                        ?>
                                        <option <?= set_select('outlet_id',$value->id)?>  value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                    endif;
                    ?>
                    <div class="col-sm-12 mb-3 col-md-2">
                        <div class="form-group">
                            <button type="submit" name="submit" value="submit"
                                class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                        </div>
                    </div>
            </div>

            <div class="table-box">
            
                <div class="table-responsive">
                
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('reference'); ?></th>
                                <th><?php echo lang('total_items'); ?></th>
                                <th><?php echo lang('subtotal'); ?></th>
                                <th><?php echo lang('delivery'); ?> <?php echo lang('delivery_charge'); ?></th>
                                <th><?php echo lang('service_charge'); ?></th>
                                <th><?php echo lang('discount'); ?></th>
                                <th><?php echo lang('vat'); ?></th>
                                <th><?php echo lang('g_total'); ?></th>
                                <th><?php echo lang('payment_method'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pGrandTotal = 0;
                            $subGrandTotal = 0;
                            $itemsGrandTotal = 0;
                            $disGrandTotal = 0;
                            $vatGrandTotal = 0;
                            $deliveryTotal = 0;
                            $serviceTotal = 0;
                            if (isset($detailedSaleReport)):
                                foreach ($detailedSaleReport as $key => $value) {
                                    $pGrandTotal+=$value->total_payable;
                                    $subGrandTotal+=$value->sub_total;
                                    $itemsGrandTotal+=$value->total_items;
                                    $disGrandTotal+=$value->total_discount_amount;
                                    $vatGrandTotal+=$value->vat;
                                    $service_row_total = 0;
                                    $delivery_row_total = 0;
                                    if($value->charge_type=="service"){
                                        $service_row_total = getPercentageValue($value->delivery_charge,$value->sub_total);
                                        $serviceTotal+=$service_row_total;

                                    }else{
                                        $delivery_row_total = getPercentageValue($value->delivery_charge,$value->sub_total);
                                        $deliveryTotal+= $delivery_row_total;
                                    }
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))) ?>
                                </td>
                                <td><?php echo escape_output($value->sale_no) ?></td>
                                <td><?php echo escape_output($value->total_items) ?></td>
                                <td><?php echo escape_output(getAmt($value->sub_total)) ?></td>
                                <td><?php echo escape_output(getAmt($delivery_row_total)) ?></td>
                                <td><?php echo escape_output(getAmt($service_row_total)) ?></td>
                                <td><?php echo escape_output(getAmt($value->total_discount_amount)) ?></td>
                                <td><?php echo escape_output(getAmt($value->vat)) ?></td>
                                <td><?php echo escape_output(getAmt($value->total_payable)) ?></td>
                                <td><?php echo escape_output($value->name) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                            <tr>
                                <th class="ir_w2_txt_center"></th>
                                <th></th>
                                <th class="ir_txt_right"><?php echo lang('total'); ?> </th>
                                <th><?= escape_output($itemsGrandTotal) ?></th>
                                <th>
                                    <?php echo escape_output(getAmt($subGrandTotal)) ?></th>
                                <th><?php echo escape_output(getAmt($deliveryTotal)) ?></th>
                                <th><?php echo escape_output(getAmt($serviceTotal)) ?></th>
                                <th>
                                    <?php echo escape_output(getAmt($disGrandTotal)) ?></th>
                                <th>
                                    <?php echo escape_output(getAmt($vatGrandTotal)) ?></th>
                                <th>
                                    <?php echo escape_output(getAmt($pGrandTotal)) ?></th>
                                <th></th>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

</section>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
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

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>