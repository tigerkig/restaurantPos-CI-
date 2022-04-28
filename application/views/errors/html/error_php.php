<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/error_custom1.css">
<div class="er_txt_1">

    <h4>A PHP Error was encountered</h4>

    <p>Severity: <?php echo $severity ?></p>
    <p>Message:  <?php echo $message ?></p>
    <p>Filename: <?php echo $filepath ?></p>
    <p>Line Number: <?php echo $line ?></p>

    <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

        <p>Backtrace:</p>
        <?php foreach (debug_backtrace() as $error): ?>

            <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

                <p class="er_txt_2">
                    File: <?php echo $error['file'] ?><br />
                    Line: <?php echo $error['line'] ?><br />
                    Function: <?php echo $error['function'] ?>
                </p>

            <?php endif ?>

        <?php endforeach ?>

    <?php endif ?>

</div>