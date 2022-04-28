
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
                <h2 class="top-left-header"><?php echo lang('users'); ?> </h2>
                <input type="hidden" class="datatable_name" data-title="<?php echo lang('users'); ?>" data-id_name="datatable">
            </div>
            <div>
                <a class="btn_list btn bg-blue-btn m-right" href="<?php echo base_url() ?>User/addEditUser">
                    <i data-feather="plus"></i> <?php echo lang('add_user'); ?>
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
                                <th class="ir_w_23"><?php echo lang('name'); ?></th>
                                <th class="ir_w_8"><?php echo lang('designation'); ?></th>
                                <th class="ir_w_8"><?php echo lang('email'); ?></th>
                                <th class="ir_w_7"><?php echo lang('status'); ?></th>
                                <th class="ir_w_27 <?=isset($language_manifesto) && $language_manifesto!="eriutoeri"?'txt_11':''?>"><?php echo lang('outlets'); ?></th>
                                <th class="ir_w_1 ir_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($users && !empty($users)) {
                                $i = count($users);
                            }
                            foreach ($users as $usrs) {
                                if ($usrs->id != $this->session->userdata['user_id']):
                                    ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($usrs->full_name) ?></td>
                                <td><?php echo escape_output($usrs->designation) ?></td>
                                <td><?php echo escape_output($usrs->email_address) ?></td>
                                <td><?php echo escape_output($usrs->active_status) ?></td>
                                <td class="<?=isset($language_manifesto) && $language_manifesto!="eriutoeri"?'txt_11':''?>"><?php echo getOutlets($usrs->outlets); ?></td>

                                <td class="ir_txt_center">
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <?php if ($usrs->role != 'Admin') { ?>
                                            <?php if ($usrs->active_status == 'Active') { ?>
                                            <li>
                                                <a
                                                    href="<?php echo base_url() ?>User/deactivateUser/<?php echo escape_output($this->custom->encrypt_decrypt($usrs->id, 'encrypt')); ?>"><i
                                                        class="fa fa-times tiny-icon"></i><?php echo lang('deactivate'); ?></a>
                                            </li>
                                            <?php } else { ?>
                                            <li>
                                                <a
                                                    href="<?php echo base_url() ?>User/activateUser/<?php echo escape_output($this->custom->encrypt_decrypt($usrs->id, 'encrypt')); ?>"><i
                                                        class="fa fa-check tiny-icon"></i><?php echo lang('activate'); ?></a>
                                            </li>
                                            <?php } ?>
                                            <?php } ?>
                                            <li>
                                                <a
                                                    href="<?php echo base_url() ?>User/addEditUser/<?php echo escape_output($this->custom->encrypt_decrypt($usrs->id, 'encrypt')); ?>"><i
                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                            </li>
                                            <?php if ($usrs->role != 'Admin') { ?>
                                            <li>
                                                <a class="delete"
                                                    href="<?php echo base_url() ?>User/deleteUser/<?php echo escape_output($this->custom->encrypt_decrypt($usrs->id, 'encrypt')); ?>"><i
                                                        class="fa fa-trash tiny-icon"></i><?php echo lang('delete'); ?></a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                endif;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</section><script src="<?php echo base_url(); ?>frequent_changing/js/inventory.js"></script>

<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>