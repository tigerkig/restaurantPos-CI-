<!-- <link rel="stylesheet" href="<?php echo base_url()?>/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->
<!-- <link rel="stylesheet" href="<?php echo base_url()?>/assets/js/dataTable/select.dataTables.min.css"> -->
<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets\frequent_changing\js\dataTable\jquery.dataTables.min.css"> -->
<base data-base="<?php echo base_url(); ?>">
</base>


<section class="main-content-wrapper">

    <?php
if ($this->session->flashdata('exception')) {
    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body">
    <p><i class="m-right fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));
    echo '</p></div></div></section>';
}

$plusSVG= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-50 font-small-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';

?>
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <h2 class="top-left-header"><?php echo lang('sale'); ?> </h2>
            </div>
            <?php if(isServiceAccessOnlyLogin('sGmsJaFJE')): ?>
            <div class="col-sm-12 col-md-4">
                <a class="btn_list m-right btn bg-blue-btn" href="<?php echo base_url() ?>Sale/POS">
                   <?php echo $plusSVG?> <?php echo lang('add_sale'); ?></a>
            </div>
                <?php else:
                $export_daily_sale = $this->session->userdata('export_daily_sale');
                if($export_daily_sale && $export_daily_sale=="enable"): ?>
            <div class="col-md-2">
                <a href="<?php echo base_url() ?>Sale/exportDailySales" class="delete"><button type="button"
                        class="btn btn-block btn-primary pull-right"><?php echo lang('exportDailySales'); ?></button></a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo base_url() ?>Sale/resetDailySales" class="delete"><button type="button"
                        class="btn btn-block btn-primary pull-right"><?php echo lang('resetDailySales'); ?></button></a>
            </div>
            <div class="col-md-2">
                <a href="<?php echo base_url() ?>Sale/POS"><button type="button"
                        class="btn btn-block btn-primary pull-right"><?php echo lang('add_sale'); ?></button></a>
            </div>
            <?php else: ?>
            <div class="col-md-offset-4 col-md-2">
                <a href="<?php echo base_url() ?>Sale/POS"><button type="button"
                        class="btn btn-block btn-primary pull-right"><?php echo lang('add_sale'); ?></button></a>
            </div>

            <?php endif; endif; ?>
        </div>
    </section>

    <div class="box-wrapper">
        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th class="ir_w_8"><?php echo lang('ref_no'); ?></th>
                            <th class="ir_w_8"><?php echo lang('order_type'); ?></th>
                            <th class="ir_w_12"><?php echo lang('date'); ?>(<?php echo lang('time'); ?>)</th>
                            <th class="ir_w_15"><?php echo lang('customer'); ?></th>
                            <th class="ir_w_17"><?php echo lang('total_payable'); ?></th>
                            <th class="ir_w_4"><?php echo lang('payment_method'); ?></th>
                            <th class="ir_w_10"><?php echo lang('added_by'); ?></th>
                            <th class="ir_w5_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                if ($lists && !empty($lists)) {
                                    $i = count($lists);
                                }
                                foreach ($lists as $value) {
                                    $order_type = "";
                                    if($value->order_type=='1'){
                                        $order_type = "Dine In";
                                    }elseif($value->order_type=='2'){
                                        $order_type = "Take Away";
                                    }elseif($value->order_type=='3'){
                                        $order_type = "Delivery";
                                    }
                                    ?>
                        <tr>
                            <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                            <td><?php echo escape_output($value->sale_no) ?></td>
                            <td><?php echo escape_output($order_type); ?></td>
                            <td><?= escape_output(date($this->session->userdata['date_format'], strtotime($value->sale_date))) ?>
                                <?php echo escape_output($value->order_time); ?></td>
                            <td><?php echo escape_output($value->customer_name) ?></td>
                            <td><?php echo escape_output(getAmtP($value->total_payable)); ?></td>
                            <td><?php echo escape_output($value->name) ?></td>
                            <td><?php echo escape_output($value->full_name) ?></td>
                            <td class="ir_txt_center">
                                <div class="btn-group actionDropDownBtn">
                                    <button type="button" class="btn bg-blue-color dropdown-toggle"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="dropdownMenuButton1"
                                        role="menu">
                                        <li><a class="ir_mouse_pointer"
                                                onclick="viewInvoice(<?php echo escape_output($value->id); ?>)"><i
                                                    class="fa fa-eye tiny-icon"></i><?php echo lang('view_invoice'); ?></a>
                                        </li>
                                        <li><a class="ir_mouse_pointer"
                                                onclick="change_date(<?php echo escape_output($value->id); ?>)"><i
                                                    class="fa fa-calendar tiny-icon"></i><?php echo lang('change_date'); ?></a>
                                        </li>
                                        <?php if($this->session->userdata('role')=='Admin'){?>
                                        <li><a class="delete"
                                                href="<?php echo base_url() ?>Sale/deleteSale/<?php echo escape_output($this->custom->encrypt_decrypt($value->id, 'encrypt')); ?>"><i
                                                    class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                        </li>
                                        <?php } ?>
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

</section>

<!-- <div id="change_date_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog ir_w_300x" role="document">
        <div class="modal-content">
            <div class="modal-header ir_bg_blue">
                <h5 class="modal-title ir_fs_ta_c_lh20">
                    <?php echo lang('change_date'); ?></h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="sale_id_hidden" id="sale_id_hidden">
                <input name="change_date_sale" placeholder="<?php echo lang('date')?>" id="change_date_sale_modal"
                    class="ir_w100_height35x">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    id="save_change_date"><?php echo lang('save_changes'); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    id="close_change_date_modal"><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
</div> -->
<!-- DataTables -->

<!-- Modal -->
<div class="modal fade" id="change_date_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('change_date'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="sale_id_hidden" id="sale_id_hidden">
                <input name="change_date_sale" placeholder="<?php echo lang('date')?>" id="change_date_sale_modal"
                    class="ir_w100_height35x">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn bg-blue-btn"
                    id="save_change_date"><?php echo lang('save_changes'); ?></button>
                <button type="button" class="btn bg-red-btn" data-bs-dismiss="modal"
                    id="close_change_date_modal"><?php echo lang('close'); ?></button>
      </div>
    </div>
  </div>
</div>



<!-- <script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script> -->
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

<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/sale.js"></script>