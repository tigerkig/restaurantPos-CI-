<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="" ; charset="UTF-8">
    <meta charset="utf-8">
    <link rel="stylesheet"
          href="<?php echo base_url() ?>frequent_changing/css/print_table_qr_code.css">
</head>

<body>
    <title> Print Table QR Code </title>
    <?php $i=1;
foreach ($tables as  $value) {
 ?>
    <div class="label ir_txt_center_fl_left">
        <?php if($value->name != '' || $value->name != NULL) 
        { ?>
        <img src="<?php echo escape_output($this->Common_model->qcode_function($value->name,'L',10)); ?>" alt="Photo">
        <?php } ?>
        <p class="ir_fs_m_fw_txtCenter">
            <?php if($value->name!='') echo "$value->name"; 
    if($online_order_setting_information->dine_in_enable_disable=='dine_in_enable')
    echo "<br>$online_order_setting_information->dine_in_code"; ?></p>
    </div>
    <?php $i++; } ?>
</body>

</html>