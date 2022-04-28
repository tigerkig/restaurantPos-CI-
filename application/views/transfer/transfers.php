
<!--<link rel="stylesheet" href="--><?php //echo base_url(); ?><!--assets/datatable_custom/jquery.dataTables.min.css">-->
<!-- <link rel="stylesheet" href="--><?php //echo base_url(); ?><!--assets/datatable_custom/buttons.dataTables.min.css">-->

 <!-- <link rel="stylesheet" href="<?php echo base_url()?>/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->



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
             if ($this->session->flashdata('exception_error')) {

                 echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible fade show"> 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <p><i class="icon fa fa-times"></i>';
                 echo escape_output($this->session->flashdata('exception_error'));
                 echo '</p></div></div></section>';
             }
        $plusSVG= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-50 font-small-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>';

             ?>
            <section class="content-header">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <h2 class="top-left-header"><?php echo lang('transfers'); ?> <i class="fad fa-hospital-alt"></i> </h2>
                        <input type="hidden" class="datatable_name" data-title="<?php echo lang('transfers'); ?>" data-id_name="datatable">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <a class="btn_list m-right btn bg-blue-btn" href="<?php echo base_url() ?>Transfer/addEditTransfer">
                            <i data-feather="plus"></i> <?php echo lang('add_transfer'); ?>
                        </a>
                    </div>
                </div>
            </section>

     <div class="box-wrapper">
             <div class="table-box">
                 <!-- /.box-header -->
                 <div class="table-responsive">
                     <table id="datatable" class="table table-responsive">
                         <thead>
                             <tr>
                                 <th class="ir_w_1"><?php echo lang('sn'); ?></th>
                                 <th class="ir_w_11"><?php echo lang('ref_no'); ?></th>
                                 <th class="ir_w_8"><?php echo lang('date'); ?></th>
                                 <th class="ir_w_8"><?php echo lang('to_outlet'); ?></th>
                                 <th class="ir_w_12"><?php echo lang('status'); ?></th>
                                 <th class="ir_w_8"><?php echo lang('received_date'); ?></th>
                                 <th class="ir_w_12"><?php echo lang('added_by'); ?></th>
                                 <th class="ir_w5_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                            if ($transfers && !empty($transfers)) {
                                $i = count($transfers);
                            }
                             $outlet_id = $this->session->userdata('outlet_id');
                            foreach ($transfers as $prchs) {
                                $new_file = '';
                                ?>
                             <tr>
                                 <td><?php echo escape_output($i--); ?>
                                     <?php
                                     if($prchs->status==3 && $outlet_id!=$prchs->from_outlet_id): ?>
                                         <img src="<?=base_url()?>assets/new-transfer.gif">
                                         <?php
                                     endif;
                                     ?>
                                 </td>
                                 <td><?php echo escape_output($prchs->reference_no) ?></td>
                                 <td><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($prchs->date))); ?>
                                 </td>
                                 <td><?php echo escape_output(getOutletNameById($prchs->to_outlet_id)); ?></td>
                                 <td><?php
                                        if($prchs->status==1){
                                            echo escape_output("Received");
                                        }elseif($prchs->status==2){
                                            echo escape_output("Draft");
                                        }elseif($prchs->status==3){
                                            echo escape_output("Sent");
                                        }
                                     ?></td>
                                 <td><?php echo isset($prchs->received_date)?escape_output(date($this->session->userdata('date_format'), strtotime($prchs->received_date))):''; ?>
                                 <td><?php echo escape_output(userName($prchs->user_id)); ?></td>

                                 <td class="ir_txt_center">
                                     <div class="btn-group  actionDropDownBtn">
                                         <button type="button" class="btn bg-blue-color dropdown-toggle"
                                             id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                             <i data-feather="more-vertical"></i>
                                         </button>
                                         <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                             <li><a
                                                     href="<?php echo base_url() ?>Transfer/transferDetails/<?php echo escape_output($this->custom->encrypt_decrypt($prchs->id, 'encrypt')); ?>"><i
                                                         class="fa fa-eye tiny-icon"></i><?php echo lang('view_details'); ?></a>
                                             </li>
                                             <?php
                                                if($prchs->status!=1):
                                             ?>
                                             <li><a
                                                     href="<?php echo base_url() ?>Transfer/addEditTransfer/<?php echo escape_output($this->custom->encrypt_decrypt($prchs->id, 'encrypt')); ?>"><i
                                                         class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                             </li>
                                                    <?php
                                                    endif;
                                                    ?>
                                             <li><a class="delete"
                                                     href="<?php echo base_url() ?>Transfer/deleteTransfer/<?php echo escape_output($this->custom->encrypt_decrypt($prchs->id, 'encrypt')); ?>"><i
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
 </section>
 <script src="<?php echo base_url(); ?>frequent_changing/js/inventory.js"></script>
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