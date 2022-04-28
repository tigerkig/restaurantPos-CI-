<?php
if ($this->session->flashdata('exception')) {

    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));
    echo '</p></div></div></section>';
}
?>

<section class="content-header">
    <div class="row">
        <div class="col-md-6">
            <h2 class="top-left-header"><?php echo lang('employees'); ?> </h2>
            <input type="hidden" class="datatable_name" data-title="<?php echo lang('employees'); ?>" data-id_name="datatable">
        </div>
        <div class="col-md-offset-4 col-md-2">
            <a href="<?php echo base_url() ?>Master/addEditEmployee"><button type="button"
                    class="btn btn-block btn-primary pull-right"><?php echo lang('add_employee'); ?></button></a>
        </div>
    </div>
</section>

<section class="main-content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                <th class="ir_w_25"><?php echo lang('name'); ?></th>
                                <th class="ir_w_16"><?php echo lang('designation'); ?></th>
                                <th class="ir_w_11"><?php echo lang('phone'); ?></th>
                                <th class="ir_w_22"><?php echo lang('description'); ?></th>
                                <th class="ir_w_16"><?php echo lang('added_by'); ?></th>
                                <th  class="ir_w_1 ir_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($employees && !empty($employees)) {
                                $i = count($employees);
                            }
                            foreach ($employees as $empls) {
                                ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($empls->name) ?></td>
                                <td><?php echo escape_output($empls->designation) ?></td>
                                <td><?php echo escape_output($empls->phone) ?></td>
                                <td><?php if ($empls->description != NULL) echo escape_output($empls->description) ?></td>
                                <td><?php echo escape_output(userName($empls->user_id)); ?></td>
                                <td class="ir_txt_center">
                                    <?php if ($empls->name != "<?php echo lang('Default_Waiter'); ?>") { ?>
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <li><a
                                                    href="<?php echo base_url() ?>Master/addEditEmployee/<?php echo escape_output($this->custom->encrypt_decrypt($empls->id, 'encrypt')); ?>"><i
                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                            </li>
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>Master/deleteEmployee/<?php echo escape_output($this->custom->encrypt_decrypt($empls->id, 'encrypt')); ?>"><i
                                                        class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo lang('sn'); ?></th>
                                <th><?php echo lang('name'); ?></th>
                                <th><?php echo lang('designation'); ?></th>
                                <th><?php echo lang('phone'); ?></th>
                                <th><?php echo lang('description'); ?></th>
                                <th><?php echo lang('added_by'); ?></th>
                                <th class="ir_txt_center"><?php echo lang('actions'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/inventory.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatable_custom/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatable_custom/buttons.dataTables.min.css">
<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>