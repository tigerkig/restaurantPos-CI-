<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<?php
    
    $show_register_report = "";
    if(isset($register_info) && count($register_info)>0){
        
        $i = 1;
        $html_p = '';
        foreach($register_info as $single_register_info){
            $payment_methods_sale = json_decode($single_register_info->payment_methods_sale);
            $html_p = '';
            $j=0;
            $total_used_payment = 0;
            if(isset($payment_methods_sale) && $payment_methods_sale){
                foreach ($payment_methods_sale as $key=>$value){
                    $total_used_payment++;
                }
            }
            if(isset($payment_methods_sale) && $payment_methods_sale){
                foreach ($payment_methods_sale as $key=>$value){
                    $html_p .= $key.": ".getAmtP($value);
                    if($j < ($total_used_payment -1)){
                        $html_p .= ", ";
                    }
                    $j++;
                }
            }

            $show_register_report .= "<tr>";
            $show_register_report .= '<td>'.$i.'</td>';
            $show_register_report .= '<td>'.$single_register_info->opening_balance_date_time.'</td>';
            $show_register_report .= '<td>'.getAmtP($single_register_info->opening_balance).'</td>';
            $show_register_report .= '<td>'.getAmtP($single_register_info->sale_paid_amount).'</td>';
            $show_register_report .= '<td>'.getAmtP($single_register_info->customer_due_receive).'</td>';
            $show_register_report .= '<td>'.$single_register_info->closing_balance_date_time.'</td>';
            $show_register_report .= '<td>'.getAmtP($single_register_info->closing_balance).'</td>';
            $show_register_report .= '<td>'.$html_p.'</td>';
            $show_register_report .= "</tr>";        
            $i++;
        }
    }
    $user_option = '';
    foreach($users as $single_user){
        $user_option .= '<option value="'.$single_user->id.'">'.$single_user->full_name.'</option>';
    }

?>

<section class="main-content-wrapper">


    <section class="content-header px-0">
        <div class="d-flex align-items-center">
            <h3 class="top-left-header text-left">
                <?php echo lang('register_report'); ?>
                <input type="hidden" class="datatable_name" data-id_name="datatable">

            </h3>
            <?php if(isLMni() && isset($outlet_id)):?>
                <p class="mx-2 txt-color-grey my-0"> <?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id))?></p>
            <?php endif;?>
        </div>
        <h4 class="ir_txtCenter_mt0 txt-color-grey"><?php
            if (isset($user_id) && $user_id):
                echo "User: " . userName($user_id) . "</span>";
            endif;
            ?>
        </h4>
        <h4 class="txt-color-grey"><?= isset($start_date) && $start_date && isset($end_date) && $end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) . " - " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?><?= isset($start_date) && $start_date && !$end_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($start_date)) : '' ?><?= isset($end_date) && $end_date && !$start_date ? lang('date').": " . date($this->session->userdata('date_format'), strtotime($end_date)) : '' ?>
        </h4>      
    </section>

    
    <div class="box-wrapper">
    <div class="test-filter-modals mb-2">
        <div class="row">
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">
                <?php echo form_open(base_url() . 'Report/registerReport') ?>
                <div class="form-group">
                    <input tabindex="1" type="text" id="" name="startDate" readonly class="form-control customDatepicker"
                        placeholder="<?php echo lang('start_date'); ?>" value="<?php echo set_value('startDate'); ?>">
                </div>
            </div>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">

                <div class="form-group">
                    <input tabindex="2" type="text" id="endMonth" name="endDate" readonly
                        class="form-control customDatepicker" placeholder="<?php echo lang('end_date'); ?>"
                        value="<?php echo set_value('endDate'); ?>">
                </div>
            </div>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">

                <div class="form-group">
                    <select tabindex="2" class="form-control select2 ir_w_100" id="user_id" name="user_id">
                        <option value=""><?php echo lang('user'); ?></option>
                        <?php
                        foreach ($users as $value) {
                            ?>
                        <option <?php echo set_select('user_id',$value->id) ?> value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->full_name) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php if(isLMni()): ?>
                <div class="col-sm-12 mb-2 col-md-4 col-lg-2">
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
            <?php endif; ?>
            <div class="col-sm-12 mb-2 col-md-4 col-lg-2">
                <div class="form-group">
                    <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn w-100"><?php echo lang('submit'); ?></button>
                </div>
            </div>
        </div>
    </div>
        <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">

                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="title" class="ir_w_5"><?php echo lang('sn'); ?></th>
                                <th class="title" class="ir_w_10"><?php echo lang('opening_date_time'); ?></th>
                                <th class="title" class="ir_w_15"><?php echo lang('opening_balance'); ?></th>
                                <th class="title" class="ir_w_15"><?php echo lang('sale'); ?>
                                    (<?php echo lang('paid_amount'); ?>)</th>
                                <th class="title" class="ir_w_15"><?php echo lang('customer_due_receive'); ?></th>
                                <th class="title" class="ir_w_10"><?php echo lang('closing_date_time'); ?></th>
                                <th class="title" class="ir_w_15"><?php echo lang('closing_balance'); ?></th>
                                <th class="title" class="ir_w_15"><?php echo lang('sale_in_payment_method'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /*This variable could not be escaped because this is base url content*/
                            echo $show_register_report;
                            ?>
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