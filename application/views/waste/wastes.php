

 <section class="main-content-wrapper">
        <?php
            if ($this->session->flashdata('exception')) {

                echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
                <button type="button" class="btn-close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
                echo escape_output($this->session->flashdata('exception'));
                echo '</p></div></div></section>';
            }
            ?>


            <section class="content-header">
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <h2 class="top-left-header"><?php echo lang('wastes'); ?> </h2>
                        <input type="hidden" class="datatable_name" data-title="<?php echo lang('wastes'); ?>" data-id_name="datatable">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <a  class="btn_list m-right btn bg-blue-btn" href="<?php echo base_url() ?>Waste/addEditWaste">
                            <i data-feather="plus"></i> <?php echo lang('add_waste'); ?>
                        </a>
                    </div>
                </div>
            </section>

            <div class="box-wrapper">
                <div class="table-box">
                    <!-- /.box-header -->
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead>
                                <tr>
                                    <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                    <th class="ir_w_11"><?php echo lang('ref_no'); ?></th>
                                    <th class="ir_w_8"><?php echo lang('date'); ?></th>
                                    <th class="ir_w_9"><?php echo lang('total_loss'); ?></th>
                                    <th class="ir_w_13"><?php echo lang('ingredient_count'); ?></th>
                                    <th class="ir_w_15"><?php echo lang('responsible_person'); ?></th>
                                    <th class="ir_w_22"><?php echo lang('note'); ?></th>
                                    <th class="ir_w_12"><?php echo lang('added_by'); ?></th>
                                    <th class="ir_w_6 not-export-col"><?php echo lang('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($wastes && !empty($wastes)) {
                                    $i = count($wastes);
                                }
                                foreach ($wastes as $wsts) {
                                    ?>
                                <tr>
                                    <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                    <td><?php echo escape_output($wsts->reference_no) ?></td>
                                    <td><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($wsts->date))); ?>
                                    </td>
                                    <td><?php echo escape_output(getAmtP($wsts->total_loss)) ?></td>
                                    <td class="ir_txt_center"><?php echo ingredientCount($wsts->id); ?></td>
                                    <td><?php echo escape_output(employeeName($wsts->employee_id)); ?></td>
                                    <td><?php echo escape_output($wsts->note) ?></td>
                                    <td><?php echo escape_output(userName($wsts->user_id)); ?></td>
                                    <td class="ir_txt_center">
                                        <div class="btn-group actionDropDownBtn">
                                            <button type="button" class="btn bg-blue-color dropdown-toggle"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i data-feather="more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                                <li><a
                                                        href="<?php echo base_url() ?>Waste/wasteDetails/<?php echo escape_output($this->custom->encrypt_decrypt($wsts->id, 'encrypt')); ?>"><i
                                                            class="fa fa-eye tiny-icon"></i><?php echo lang('view_details'); ?></a>
                                                </li>
                                                <li><a class="delete"
                                                        href="<?php echo base_url() ?>Waste/deleteWaste/<?php echo escape_output($this->custom->encrypt_decrypt($wsts->id, 'encrypt')); ?>"><i
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
 <!-- DataTables -->

  <!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>
 
 <script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>