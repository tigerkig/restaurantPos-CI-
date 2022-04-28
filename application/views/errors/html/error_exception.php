<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/error_custom1.css">
<div class="er_txt_1">

    <h4>An uncaught Exception was encountered</h4>

    <p>Type: <?php echo get_class($exception); ?></p>
    <p>Message: <?php echo $message ?></p>
    <p>Filename: <?php echo $exception->getFile(); ?></p>
    <p>Line Number: <?php echo $exception->getLine(); ?></p>

    <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

        <p>Backtrace:</p>
        <?php foreach ($exception->getTrace() as $error): ?>

            <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

                <p class="er_txt_2">
                    File: <?php echo $error['file']; ?><br />
                    Line: <?php echo $error['line']; ?><br />
                    Function: <?php echo $error['function']; ?>
                </p>
            <?php endif ?>

        <?php endforeach ?>

    <?php endif ?>

</div>