<button class="ir_bg_fs_p_c_fl" onclick="printDiv('printableArea')">Print</button>

<script src="<?php echo base_url(); ?>frequent_changing/js/print_daily_summary_report.js"></script>

<section class="main-content-wrapper" id="printableArea">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">


                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <h1> <?php
                        if(isLMni() && isset($outlet_id)):
                            ?>
                            <?php echo escape_output(getOutletNameById($outlet_id))?>
                            <?php
                        endif;
                        ?></h1>
                    <hr>
                    <h3 class="ir_txt_center"><?php echo lang('daily_summary_report'); ?></h3>
                    <h4><?= isset($selectedDate) && $selectedDate ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($selectedDate)) : '' ?>
                    </h4>
                    <hr>
                    <h4 class="ir_fw_ta_mt20"><?php echo lang('purchases'); ?></h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <th><?php echo lang('supplier'); ?></th>
                            <th><?php echo lang('g_total'); ?></th>
                            <th><?php echo lang('paid'); ?></th>
                            <th><?php echo lang('due'); ?></th>
                        </tr>
                        <?php
                        $sum_of_gtotal = 0;
                        $sum_of_paid = 0;
                        $sum_of_due = 0;
                        if (!empty($result['purchases']) && isset($result['purchases'])):
                            foreach ($result['purchases'] as $key => $value):
                                $sum_of_gtotal += $value->grand_total;
                                $sum_of_paid += $value->paid;
                                $sum_of_due += $value->due;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->reference_no); ?></td>
                                    <td><?= escape_output(getSupplierNameById($value->supplier_id)) ?></td>
                                    <td><?php echo escape_output(getAmt($value->grand_total)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->paid)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->due)); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_gtotal)); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_paid)); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_due)); ?></td>
                        </tr>
                    </table>

                    <hr>
                    <h4 class="ir_fw_ta_mt20"><?php echo lang('sales'); ?>
                    </h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <th><?php echo lang('order_type'); ?></th>
                            <th><?php echo lang('table'); ?></th>
                            <th><?php echo lang('customer'); ?></th>
                            <th><?php echo lang('total_payable'); ?></th>
                            <th><?php echo lang('discount'); ?></th>
                            <th><?php echo lang('paid'); ?></th>
                            <th><?php echo lang('due'); ?></th>
                        </tr>
                        <?php
                        $sum_of_stotal_payable = 0;
                        $sum_of_sdisc_actual = 0;
                        $sum_of_spaid_amount = 0;
                        $sum_of_sdue_amount = 0;
                        if (!empty($result['sales']) && isset($result['sales'])):
                            foreach ($result['sales'] as $key => $value):
                                $sum_of_stotal_payable += $value->total_payable;
                                $sum_of_sdisc_actual += $value->total_discount_amount;
                                $sum_of_spaid_amount += $value->paid_amount;
                                $sum_of_sdue_amount += $value->due_amount;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->sale_no); ?></td>
                                    <td><?php echo getOrderType($value->order_type); ?></td>
                                    <td><?php if(!empty($value->table_id)){ echo getTableName($value->table_id); } ?></td>
                                    <td><?= escape_output(getCustomerName($value->customer_id)) ?></td>
                                    <td><?php echo escape_output(getAmt($value->total_payable)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->total_discount_amount)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->paid_amount)); ?></td>
                                    <td><?php echo escape_output(getAmt($value->due_amount)); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_stotal_payable)); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_sdisc_actual)); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_spaid_amount)); ?></td>
                            <td>&nbsp;<?php echo escape_output(getAmt($sum_of_sdue_amount)); ?></td>
                        </tr>
                    </table>


                    <hr>
                    <h4 class="ir_fw_ta_mt20"><?php echo lang('expenses'); ?>
                    </h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('category'); ?></th>
                            <th><?php echo lang('responsible_person'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                        $sum_of_eamount = 0;
                        if (!empty($result['expenses']) && isset($result['expenses'])):
                            foreach ($result['expenses'] as $key => $value):
                                $sum_of_eamount += $value->amount;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->amount); ?></td>
                                    <td><?php echo escape_output(expenseItemName($value->category_id)); ?></td>
                                    <td><?php echo escape_output(employeeName($value->employee_id)); ?></td>
                                    <td><?php echo escape_output($value->note); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output($sum_of_eamount); ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <hr>
                    <h4 class="ir_fw_ta_mt20">
                        <?php echo lang('supplier_due_payments'); ?></h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('supplier'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                        $sum_of_samount = 0;
                        if (!empty($result['supplier_due_payments']) && isset($result['supplier_due_payments'])):
                            foreach ($result['supplier_due_payments'] as $key => $value):
                                $sum_of_samount += $value->amount;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->amount); ?></td>
                                    <td><?php echo escape_output(getSupplierNameById($value->supplier_id)); ?></td>
                                    <td><?php echo escape_output($value->note); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output($sum_of_samount); ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <hr>
                    <h4 class="ir_fw_ta_mt20">
                        <?php echo lang('customer_due_receives'); ?></h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('amount'); ?></th>
                            <th><?php echo lang('customer'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                        $sum_of_camount = 0;
                        if (!empty($result['customer_due_receives']) && isset($result['customer_due_receives'])):
                            foreach ($result['customer_due_receives'] as $key => $value):
                                $sum_of_camount += $value->amount;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->amount); ?></td>
                                    <td><?php echo escape_output(getCustomerName($value->customer_id)); ?></td>
                                    <td><?php echo escape_output($value->note); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output($sum_of_camount); ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <hr>
                    <h4 class="ir_fw_ta_mt20"><?php echo lang('wastes'); ?>
                    </h4>
                    <hr>
                    <table class="ir_w_100 tbl">
                        <tr>
                            <th class="ir_fw_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('ref_no'); ?></th>
                            <th><?php echo lang('loss_amount'); ?></th>
                            <th><?php echo lang('responsible_person'); ?></th>
                            <th><?php echo lang('note'); ?></th>
                        </tr>
                        <?php
                        $sum_of_wamount = 0;
                        if (!empty($result['wastes']) && isset($result['wastes'])):
                            foreach ($result['wastes'] as $key => $value):
                                $sum_of_wamount += $value->total_loss;
                                $key++;
                                ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                                    <td><?php echo escape_output($value->reference_no); ?></td>
                                    <td><?php echo escape_output($value->total_loss); ?></td>
                                    <td><?php echo escape_output(employeeName($value->employee_id)); ?></td>
                                    <td><?php echo escape_output($value->note); ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="ir_fw_txt_right"><?php echo lang('sum'); ?></td>
                            <td>&nbsp;<?php echo escape_output($sum_of_wamount); ?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>