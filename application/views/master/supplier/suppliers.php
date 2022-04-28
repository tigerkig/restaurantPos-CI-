
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

    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
                <h2 class="top-left-header"><?php echo lang('suppliers'); ?> </h2>
                <input type="hidden" class="datatable_name" data-title="<?php echo lang('suppliers'); ?>" data-id_name="datatable">
            </div>
            <div>
                <a class="btn_list btn bg-blue-btn m-right" href="<?php echo base_url() ?>supplier/addEditSupplier">
                <i data-feather="plus"></i> <?php echo lang('add_supplier'); ?>
                </a>
            </div>
        </div>
    </section>


        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                <th class="ir_w_14"><?php echo lang('name'); ?></th>
                                <th class="ir_w_14"><?php echo lang('contact_person'); ?></th>
                                <th class="ir_w_9"><?php echo lang('phone'); ?></th>
                                <th class="ir_w_10"><?php echo lang('email'); ?></th>
                                <th class="ir_w_20"><?php echo lang('address'); ?></th>
                                <th class="ir_w_17"><?php echo lang('description'); ?></th>
                                <th class="ir_w_16"><?php echo lang('added_by'); ?></th>
                                <th  class="ir_w_1 ir_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($suppliers && !empty($suppliers)) {
                                $i = count($suppliers);
                            }
                            foreach ($suppliers as $si) {
                                ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($si->name) ?></td>
                                <td><?php echo escape_output($si->contact_person) ?></td>
                                <td><?php echo escape_output($si->phone) ?></td>
                                <td><?php echo escape_output($si->email) ?></td>
                                <td><?php echo escape_output($si->address) ?></td>
                                <td><?php if ($si->description != NULL) echo escape_output($si->description) ?></td>
                                <td><?php echo escape_output(userName($si->user_id)); ?></td>
                                <td class="ir_txt_center">
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <li><a
                                                    href="<?php echo base_url() ?>supplier/addEditSupplier/<?php echo escape_output($this->custom->encrypt_decrypt($si->id, 'encrypt')); ?>"><i
                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                            </li>
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>supplier/deleteSupplier/<?php echo escape_output($this->custom->encrypt_decrypt($si->id, 'encrypt')); ?>"><i
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