
<!-- Main content -->
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

    if ($this->session->flashdata('exception_1')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body"><div class="alert-body"><p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception_1'));
        echo '</p></div></div></section>';
    }
    ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h3 class="top-left-header">
            <?php echo lang('switch_version'); ?>
        </h3>
    </section>


    <div class="box-wrapper">
            <div class="table-box">
                <?php echo form_open(base_url('Authentication/switchVersion')); ?>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2 col-md-12">
                            <div class="form-group">
                                <label><?php echo lang('current_version'); ?> <span class="required_star">*</span></label>
                                <select tabindex="5" class="form-control select2" id="irestora_version" name="irestora_version">
                                    <option value=""><?php echo lang('select'); ?></option>
                                    <?php
                                    $versions = ['stwtyqxst' => 'Unique outlet + Whitelabel', 'revhgbrev' => 'Multiple outlets + Whitelabel', 'sGmsJaFJVE' => 'Multiple outlets + Whitelabel + Saas'];
                                    foreach ($versions as $key => $name) {
                                    ?>
                                    <option
                                        <?= isset($current_version) && $current_version == $key ? 'selected' : '' ?>
                                        value="<?= $key ?>">
                                        <?= escape_output($name) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer px-0">
                    <button type="submit" name="submit" value="submit"
                        class="btn bg-blue-btn"><?php echo lang('submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
    </div>
 
</section>