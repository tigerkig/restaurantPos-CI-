
<section class="main-content-wrapper">

    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>

    <section class="content-header">
                <h3 class="top-left-header"><?php echo lang('plugins'); ?> </h3>
    </section>

    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">
                <!-- /.box-header -->
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="ir_w_1"><?php echo lang('sn'); ?></th>
                                <th class="ir_w_9"><?php echo lang('name'); ?></th>
                                <th class="ir_w_9"><?php echo lang('details'); ?></th>
                                <th class="ir_w_9"><?php echo lang('active_status'); ?></th>
                                <th class="ir_w_9"><?php echo lang('installation_date'); ?></th>
                                <th class="ir_w_9"><?php echo lang('version'); ?></th>
                                <th class="ir_w_6 not-export-col"><?php echo lang('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($plugins && !empty($plugins)) {
                                $i = count($plugins);
                            }
                            foreach ($plugins as $spns) {
                                ?>
                            <tr>
                                <td><?php echo escape_output($i--); ?></td>
                                <td>   <?php echo escape_output($spns->name) ?></td>
                                <td>   <?php echo escape_output($spns->details) ?></td>
                                <td>   <?php echo escape_output($spns->active_status) ?></td>
                                <td><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($spns->installation_date))); ?> </td>
                                <td>   <?php echo escape_output($spns->version) ?></td>
                                <td>
                                    <div class="btn-group actionDropDownBtn">
                                        <button type="button" class="btn bg-blue-color dropdown-toggle"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1" role="menu">
                                            <li>
                                                <?php
                                                if($spns->active_status!="Active"):
                                                    ?>
                                                    <a class="delete"
                                                       href="<?php echo base_url() ?>Plugin/OP/Active/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
                                                                class="fa fa-times tiny-icon"></i><?php echo lang('Active'); ?></a>
                                                    <?php
                                                else:
                                                    ?>
                                                    <a class="delete"
                                                       href="<?php echo base_url() ?>Plugin/OP/Inactive/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
                                                                class="fa fa-times tiny-icon"></i><?php echo lang('Inactive'); ?></a>

                                                    <?php
                                                endif;
                                                ?>

                                            </li>
                                            <li><a class="delete"
                                                    href="<?php echo base_url() ?>Plugin/deletePlugin/<?php echo escape_output($this->custom->encrypt_decrypt($spns->id, 'encrypt')); ?>"><i
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
                
            </div>
    </div>
</section>