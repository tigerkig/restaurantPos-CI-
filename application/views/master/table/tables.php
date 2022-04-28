
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper">
        <div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>


    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="top-left-header"><?php echo lang('tables'); ?> </h2>
                <input type="hidden" class="datatable_name" data-title="<?php echo lang('tables'); ?>" data-id_name="datatable">
            </div>
            <div>
                <a class="btn_list m-right btn bg-blue-btn" href="<?php echo base_url() ?>table/addEditTable">
                   <i data-feather="plus"></i> <?php echo lang('add_table'); ?>
                </a>
            </div>
        </div>
    </section>



        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <?php
                $language_manifesto = str_rot13($this->session->userdata('language_manifesto'));
                ?>
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                <th class="ir_w_20"><?php echo lang('table_name'); ?></th>
                                <th class="ir_w_20"><?php echo lang('seat_capacity'); ?></th>
                                <th class="ir_w_10"><?php echo lang('position'); ?></th>
                                <th class="ir_w_20"><?php echo lang('description'); ?></th>
                                <th class="ir_w_20 <?=isset($language_manifesto) && $language_manifesto!="eriutoeri"?'txt_11':''?>"><?php echo lang('outlet'); ?></th>
                                <th class="ir_w_20"><?php echo lang('added_by'); ?></th>
                                <th  class="ir_w_1 ir_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($tables && !empty($tables)) {
                                $i = count($tables);
                            }
                            foreach ($tables as $value) {
                                ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($value->name) ?></td>
                                <td><?php echo escape_output($value->sit_capacity) ?></td>
                                <td><?php echo escape_output($value->position) ?></td>
                                <td><?php echo escape_output($value->description) ?></td>
                                <td class="<?=isset($language_manifesto) && $language_manifesto!="eriutoeri"?'txt_11':''?>"><?php echo getOutletNameById($value->outlet_id); ?></td>
                                <td><?php echo escape_output(userName($value->user_id)); ?></td>
                                <td class="ir_txt_center">
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <li><a
                                                    href="<?php echo base_url() ?>table/addEditTable/<?php echo escape_output($this->custom->encrypt_decrypt($value->id, 'encrypt')); ?>"><i
                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                            </li>
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>table/deleteTable/<?php echo escape_output($this->custom->encrypt_decrypt($value->id, 'encrypt')); ?>"><i
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
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>