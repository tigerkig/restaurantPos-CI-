<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/error_custom.css">
        <meta charset="utf-8">
        <title>Error</title>

    </head>
    <body>
        <div id="container">
            <h1><?php echo $heading ?></h1>
            <?php echo $message ?>
        </div>
    </body>
</html>