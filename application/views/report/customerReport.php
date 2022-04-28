<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<script src="<?php echo base_url(); ?>frequent_changing/js/customer_report.js"></script>

<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="text-left top-left-header"><?php echo lang('customer_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('customer_report'); ?>" data-id_name="datatable">


    </section>


    <div class="my-2">
        <?php
                    if(isLMni() && isset($outlet_id)):
                        ?>
                        <h4> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></h4>
                        <?php
                    endif;
                    ?>
                    <h4 class="ir_txtCenter_mt0"><?php
                    if (isset($customer_id) && $customer_id):
                        echo "<span>" . escape_output(getCustomerName($customer_id)) . "</span>";
                    endif;
                    ?></h4>
                    <h4><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('report_date') . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
                    </h4>
    </div>

    <div class="box-wrapper">
                <div class="row">
                        <div class="mb-3 col-md-3 col-sm-12">
                            <?php echo form_open(base_url() . 'Report/customerReport', $arrayName = array('id' => 'customerReport')) ?>
                            <div class="form-group">
                                <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                                    placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                            </div>
                        </div>
                        <div class="mb-3 col-md-3 col-sm-12">

                            <div class="form-group">
                                <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                                    class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                                    value="<?php echo set_value('endDate'); ?>">
                            </div>
                        </div>
                        <div class="mb-3 col-md-3 col-sm-12">

                            <div class="form-group">
                                <select tabindex="2" class="form-control select2 ir_w_100" id="customer_id" name="customer_id">
                                    <option value=""><?php echo lang('customers'); ?></option>
                                    <?php
                                    $check_walk_in_customer = 1;
                                    foreach ($customers as $value) {
                                        if($value->id==1){
                                            $check_walk_in_customer++;
                                        }
                                        ?>
                                    <option value="<?php echo escape_output($value->id) ?>" <?php echo set_select('customer_id', $value->id); ?>>
                                        <?php echo escape_output($value->name) ?></option>
                                    <?php }
                                    if($check_walk_in_customer==1){?>
                                        <option  value="1">Walk-in Customer</option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="alert error-msg customer_id_err_msg_contnr ir_p_5">
                                <p id="customer_id_err_msg"></p>
                            </div>
                        </div>
                        <?php
                        if(isLMni()):
                            ?>
                            <div class="mb-3 col-md-3 col-sm-12">
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
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit"
                                    class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                            </div>
                        </div>
            </div>
            <div class="table-box">
                
                <div class="table-responsive">
                
                    <h4 class="my-3 text-left"><?php echo lang('sale'); ?></h4>
                    <table id="datatable" class="datatable table">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('reference'); ?></th>
                                <th><?php echo lang('g_total'); ?></th>
                                <th><?php echo lang('paid'); ?></th>
                                <th><?php echo lang('due'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pGrandTotal = 0;
                            $pPaid = 0;
                            $pDue = 0;
                            if (isset($customerReport)):
                                foreach ($customerReport as $key => $value) {
                                    $pGrandTotal+=$value->total_payable;
                                    $pPaid+=$value->paid_amount;
                                    $pDue+=$value->due_amount;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))) ?>
                                </td>
                                <td><?php echo escape_output($value->sale_no) ?></td>
                                <td><?php echo escape_output(getAmt($value->total_payable)) ?></td>
                                <td><?php echo escape_output(getAmt($value->paid_amount)) ?></td>
                                <td><?php echo escape_output(getAmt($value->due_amount)) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
                    </table>
                    
                    <h4 class="my-3 text-left"><?php echo lang('due_receive'); ?></h4>
                    <table id="datatable2" class="datatable table">
                        <thead>
                            <tr>
                                <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('receive_amount'); ?></th>
                                <th class="ir_w_45"><?php echo lang('note'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = 0;

                            if (isset($customerDueReceiveReport)):
                                foreach ($customerDueReceiveReport as $key => $value) {
                                    $totalAmount+=$value->amount;
                                    $key++;
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->date))) ?></td>
                                <td><?php echo escape_output(getAmt($value->amount)) ?></td>
                                <td><?php echo escape_output($value->note) ?></td>
                            </tr>
                            <?php
                                }
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
    </div>



</section>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report1.js"></script>