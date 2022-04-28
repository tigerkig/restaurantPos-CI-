
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
                <div class="col-md-6">
                    <h2 class="top-left-header"><?php echo lang('customers'); ?> </h2>
                    <input type="hidden" class="datatable_name" data-title="<?php echo lang('customers'); ?>" data-id_name="datatable">
                </div>
                <div class="col-md-offset-2 col-md-4">
                    <div class="btn_list m-right d-flex">
                      
                            <a class="btn bg-blue-btn m-right" href="<?php echo base_url() ?>customer/addEditCustomer">
                                <i data-feather="plus"></i> <?php echo lang('add_customer'); ?>
                            </a>
                       
                            <a class="btn bg-blue-btn" href="<?php echo base_url() ?>customer/uploadCustomer">
                            <i data-feather="upload"></i> <?php echo lang('upload_customer'); ?>
                            </a>
                        
                    </div>

                </div>
            </div>
        </section>


        <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th class="ir_w_1"> <?php echo lang('sn'); ?></th>
                                <th class="ir_w_12"><?php echo lang('customer_name'); ?></th>
                                <th class="ir_w_7"><?php echo lang('phone'); ?></th>
                                <th class="ir_w_7"><?php echo lang('email'); ?></th>
                                <th class="ir_w_7"><?php echo lang('dob'); ?></th>
                                <th class="ir_w_7"><?php echo lang('doa'); ?></th>
                                <th class="ir_w_10"><?php echo lang('address'); ?></th>
                                <th class="ir_w_10"><?php echo lang('added_by'); ?></th>
                                <th class="ir_w_1_txt_center"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($customers && !empty($customers)) {
                                $i = count($customers);
                            }
                            foreach ($customers as $cust) {
                                ?>
                            <tr>
                                <td class="ir_txt_center"><?php echo escape_output($i--); ?></td>
                                <td><?php echo escape_output($cust->name) ?></td>
                                <td><?php echo escape_output($cust->phone) ?></td>
                                <td><?php echo escape_output($cust->email) ?></td>
                                <td><?php if($cust->date_of_birth != '1970-01-01'){ echo escape_output($cust->date_of_birth); }?></td>
                                <td><?php if($cust->date_of_anniversary != '1970-01-01'){ echo escape_output($cust->date_of_anniversary); }?>
                                </td>
                                <td><?php echo escape_output($cust->address) ?></td>
                                <td><?php echo userName($cust->user_id); ?></td>
                                <td class="ir_txt_center">
                                    <?php if ($cust->name != "Walk-in Customer") { ?>
                                    <div class="btn-group  actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">

                                            <li><a
                                                    href="<?php echo base_url() ?>customer/addEditCustomer/<?php echo escape_output($this->custom->encrypt_decrypt($cust->id, 'encrypt')); ?>"><i
                                                        class="fa fa-pencil tiny-icon"></i><?php echo lang('edit'); ?></a>
                                            </li>
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>customer/deleteCustomer/<?php echo escape_output($this->custom->encrypt_decrypt($cust->id, 'encrypt')); ?>"><i
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