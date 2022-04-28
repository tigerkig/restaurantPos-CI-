<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Database Error</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/error_custom.css">
    </head>
    <body>
        <div id="container">
            <h1><?php echo $heading ?></h1>
            <?php echo $message ?>
        </div>
    </body>
</html>