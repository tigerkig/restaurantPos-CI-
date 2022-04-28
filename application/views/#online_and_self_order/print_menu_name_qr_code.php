<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content=""; charset="UTF-8">
<meta charset="utf-8">
    <link rel="stylesheet"
          href="<?php echo base_url() ?>frequent_changing/css/print_menu_name_qr_code.css">
</head>

<body>
	<title> Print Menu Name QR Code </title>

<?php $i=1;
foreach ($foodMenus as  $value) {
 ?>
<div class="label text_1_online">
    <?php if($value->name != '' || $value->name != NULL) 
        { ?>
        <img src="<?php echo escape_output($this->Common_model->qcode_function($value->name,'L',10)); ?>" alt="Photo">
        <?php } ?>
        <p class="text_2_online">
    <?php if($value->name!='') echo "escape_output(($value->name)<br>"; echo ":$value->sale_price";  ?></p>
</div>
<?php $i++; } ?>
</body>
</html>