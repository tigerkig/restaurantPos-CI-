<link rel="stylesheet" media="screen" type="text/css" href="<?php echo base_url() ?>assets/color/spectrum.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/color/spectrum.js"></script>
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/bower_components/bootstrap-timepicker/css/timepicker.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>frequent_changing/css/set_opening_closing_time.css">
<!-- bootstrap time picker -->
<script src="<?php echo base_url() ?>assets/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
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
    <h1>
        <?php echo "Set Opening and Closing Time for Your Restaurant"; ?>
    </h1>

</section>

<!-- Main content --> 
<section class="main-content-wrapper">
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <div class="table-box"> 
                <!-- /.box-header -->
                <!-- form start -->
                <?php 
                $attributes = array('id' => 'online_and_self_order_setting_form', 'enctype'=>'multipart/form-data');
                echo form_open(base_url('Order/setOpeningClosingTime/' . $encrypted_id),$attributes); ?> 
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                             <p class="online_txt_1">Order Page Setting</p>
                             <hr/>

                        </div>
                    </div>
                    <?php
                    $data = array();
                    $data = json_decode($online_order_setting_information->opening_closing_time);
                    //echo escape_output($online_order_setting_information->opening_closing_time) echo "<br>";
                    //print_r($datas); 

                    ?>
                    <div class="row"> 
                        <div class="col-md-4">
                            <table class="table">
                                <tr>
                                <th>#</th>
                                <th>Day</th>
                                <th>Opening Time</th>
                                <th>Closing Time</th>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[0]->status) && $data[0]->status && $data[0]->status==1?'checked':''?> name="status_1" value="1"> </td>
                                <td><input type="hidden" value="Saturday" class="form-control" name="date_name[]"> Saturday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[0]->o_time) && $data[0]->o_time?$data[0]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[0]->c_time) && $data[0]->c_time?$data[0]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[1]->status) && $data[1]->status && $data[1]->status==1?'checked':''?> name="status_2" value="1"> </td>
                                <td><input type="hidden" value="Sunday" class="form-control" name="date_name[]"> Sunday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[1]->o_time) && $data[1]->o_time?$data[1]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[1]->c_time) && $data[1]->c_time?$data[1]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[2]->status) && $data[2]->status && $data[2]->status==1?'checked':''?> name="status_3" value="1"> </td>
                                <td><input type="hidden" value="Monday" class="form-control" name="date_name[]"> Monday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[2]->o_time) && $data[2]->o_time?$data[2]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[2]->c_time) && $data[2]->c_time?$data[2]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[3]->status) && $data[3]->status && $data[3]->status==1?'checked':''?> name="status_4" value="1"> </td>
                                <td><input type="hidden" value="Tuesday" class="form-control" name="date_name[]"> Tuesday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[3]->o_time) && $data[3]->o_time?$data[3]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[3]->c_time) && $data[3]->c_time?$data[3]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[4]->status) && $data[4]->status && $data[4]->status==1?'checked':''?> name="status_5" value="1"> </td>
                                <td><input type="hidden" value="Wednesday" class="form-control" name="date_name[]"> Wednesday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[4]->o_time) && $data[4]->o_time?$data[4]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[4]->c_time) && $data[4]->c_time?$data[4]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[5]->status) && $data[5]->status && $data[5]->status==1?'checked':''?> name="status_6" value="1"> </td>
                                <td><input type="hidden" value="Thursday" class="form-control" name="date_name[]"> Thursday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[5]->o_time) && $data[5]->o_time?$data[5]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[5]->c_time) && $data[5]->c_time?$data[5]->c_time:''?>"></td>
                                </tr>
                                <tr>
                                <td><input type="checkbox" <?=isset($data[6]->status) && $data[6]->status && $data[6]->status==1?'checked':''?> name="status_7" value="1"> </td>
                                <td><input type="hidden" value="Friday" class="form-control" name="date_name[]"> Friday</td>
                                <td><input type="text" name="o_time[]" class="form-control timepicker" value="<?=isset($data[6]->o_time) && $data[6]->o_time?$data[6]->o_time:''?>"></td>
                                <td><input type="text" name="c_time[]" class="form-control timepicker" value="<?=isset($data[6]->c_time) && $data[6]->c_time?$data[6]->c_time:''?>"></td>
                                </tr>
                            </table>
                        </div>
                    </div>       
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo lang('submit'); ?></button> 
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div> 
</section>
<script src="<?php echo base_url() ?>frequent_changing/js/set_opening_closing_time.js"></script>
