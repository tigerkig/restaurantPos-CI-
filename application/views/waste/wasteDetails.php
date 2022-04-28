<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/wasteDetails.css">

<section class="main-content-wrapper">


    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('details_waste'); ?>
        </h3>
    </section>

    <div class="box-wrapper">
            <!-- general form elements -->
            <div class="table-box">

                <!-- /.box-header -->
                <!-- form start -->
                <?php echo form_open(base_url() . 'Waste/addEditWaste/' . $encrypted_id, $arrayName = array('id' => 'waste_form')) ?>
                <div>
                    <div class="row">

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('ref_no'); ?></h3>
                                <p class="field_value"><?php echo escape_output($waste_details->reference_no) ?></p>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('date'); ?> </h3>
                                <p class="field_value">
                                    <?php echo escape_output(date($this->session->userdata('date_format'), strtotime($waste_details->date))); ?>
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('responsible_person'); ?> </h3>
                                <p class="field_value"><?php echo escape_output(employeeName($waste_details->employee_id)); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h3><?php echo lang('ingredients'); ?> </h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="waste_cart">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="txt_31"><?php echo lang('sn'); ?></th>
                                            <th class="txt_30">
                                                <?php echo lang('ingredient'); ?>(<?php echo lang('code'); ?>)</th>
                                            <th class="txt_30"><?php echo lang('quantity_amount'); ?></th>
                                            <th class="txt_30"><?php echo lang('loss_amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $i = 0;
                                        if ($waste_ingredients && !empty($waste_ingredients)) {
                                            foreach ($waste_ingredients as $wi) {
                                                $i++;
                                                echo '<tr id="row_' . $i . '">' .
                                                '<td class="txt_24"><p>' . $i . '</p></td>' .
                                                '<td class="ir_w_20 txt_18">' . getIngredientNameById($wi->ingredient_id) . ' (' . getIngredientCodeById($wi->ingredient_id) . ')</span></td>' .
                                                '<td class="ir_w_15">' . getAmtP($wi->waste_amount) . unitName(getUnitIdByIgId($wi->ingredient_id)) . '</td>' .
                                                '<td class="ir_w_15">' . getAmtP($wi->loss_amount) . '</td>' .
                                                '</tr>';
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('total_loss'); ?></h3>
                                <p class="field_value">
                                    <?php echo escape_output(getAmtP($waste_details->total_loss)) ?>
                                <div class="tooltip_custom">
                                    <i data-feather="help-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo lang('tooltip_txt_28'); ?>"></i>
                                </div>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h3><?php echo lang('note'); ?></h3>
                                <p class="field_value"><?php echo escape_output($waste_details->note) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Waste/addEditWaste/<?php echo escape_output($encrypted_id); ?>">
                            <?php echo lang('edit'); ?>
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-2 mb-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>Waste/wastes">
                            <?php echo lang('back'); ?>
                        </a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
        
</section>