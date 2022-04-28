

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


    <?php
    if ($this->session->flashdata('exception_err')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <p><i class="icon fa fa-times"></i>';
        echo escape_output($this->session->flashdata('exception_err'));
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
        <div class="row">
        <h3 class="top-left-header text-left"><?php echo lang('payment_history')?> </h3>
        <input type="hidden" data-filter="yes" class="datatable_name" data-title="<?php echo lang('payment_history'); ?>" data-id_name="datatable">
    
        <a class="btn_list m-right btn bg-blue-btn" href="<?php echo base_url() ?>Service/addManualPayment">
           <i data-feather="plus"></i> <?php echo lang('Add_Manual_Payment')?>
        </a>
                
        </div>
    </section>

 

        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                
                <!-- /.box-header -->
                <div class=" table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w_1"><?php echo lang('sn'); ?></th>
                                <th class="ir_w_9"><?php echo lang('company_name')?></th>
                                <th class="ir_w_6"><?php echo lang('payment_type')?></th>
                                <th class="ir_w_6"><?php echo lang('payment_date')?></th>
                                <th class="ir_w_6"><?php echo lang('amount')?></th>
                                <th class="ir_w_6"><?php echo lang('trans_id')?></th>
                                <th class="ir_w_6 not-export-col text-center"><?php echo lang('actions')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_amount  = 0;
                            if ($payment_histories && !empty($payment_histories)) {
                                $i = count($payment_histories);
                            }
                            foreach ($payment_histories as $spns) {
                                $total_amount+=$spns->amount;
                                ?>
                            <tr style="background-color: <?php echo isset($spns->payment_clear) && $spns->payment_clear=="No"?'#af1f1f29':''?>">
                                <td><?php echo escape_output($i--); ?></td>
                                <td>   <?php echo escape_output($spns->business_name) ?></td>
                                <td>   <?php echo escape_output($spns->payment_type) ?></td>
                                <td><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($spns->payment_date))); ?>
                                </td>
                                <td>   <?php echo escape_output(getAmtP($spns->amount)) ?></td>
                                <td>   <?php echo escape_output($spns->trans_id) ?></td>
                                <td class="ir_txt_center">
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>Service/deletePayment/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
                                                        class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                            </li>
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>


        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('payment_history')?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <select tabindex="2" class="form-control select2 ir_w_100" id="company_id" name="company_id">
                                <option value=""><?php echo lang('select_company'); ?></option>
                                <?php
                                foreach ($companies as $value):
                                    if($value->id!=1):
                                    ?>
                                    <option <?= set_select('company_id',$value->id)?>  value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->business_name) ?></option>
                                    <?php
                                    endif;
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12">
                            <button type="submit" name="submit" value="submit"
                                    class="btn w-100 bg-blue-btn"><?php echo lang('submit'); ?></button>
                    </div>

                    </div>
                </div>
                </div>
            </div>
        </div>
   
</section>

<div class="modal fade" id="add_payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                <h3 class="modal-title" id="myModalLabel">
                    <?php echo lang('Manual_payment_for'); ?> "<span class="business_name_txt">---</span>"</h3>
            </div>
            <?php echo form_open(base_url() . 'Service/manualPayment', $arrayName = array('id' => 'manualPayment')) ?>
            <input type="hidden" id="hidden_company_id" name="hidden_company_id" value="">
            <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?php echo lang('expired_date')?> <span class="required_star">*</span></label>
                            <div class="tooltip_custom"><i class="fa fa-question fa-lg form_question"></i>
                                <span class="tooltiptext_custom"><?php echo lang('Manual_payment_tooltip'); ?></span>
                            </div>
                            <input type="text" readonly name="expired_date" id="expired_date" value="" placeholder="<?php echo lang('expired_date')?>" class="form-control start_date_today">
                        </div>

                    </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php echo lang('amount')?> <span class="required_star">*</span></label>
                        <input type="text" name="amount" id="amount" value="" placeholder="<?php echo lang('amount')?>" class="form-control integerchk customdatePicker">
                    </div>

                </div>
                <p>&nbsp;</p>
            </div>
            <div class="modal-footer">

                <button type="button"  data-dismiss="modal" aria-label="Close" class="btn btn-primary">
                    <?php echo lang('cancel'); ?></button>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">
                    <?php echo lang('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

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
<script src="<?php echo base_url(); ?>frequent_changing/js/saas_company.js"></script>
