
<section class="main-content-wrapper">
    <?php
    if ($this->session->flashdata('exception')) {

        echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-check"></i>';
        echo escape_output($this->session->flashdata('exception'));
        echo '</p></div></div></section>';
    }
    ?>

    <?php
    if ($this->session->flashdata('exception_1')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-times"></i>';
        echo escape_output($this->session->flashdata('exception_1'));
        echo '</p></div><div></section>';
    }
    ?>

    <?php
    if ($this->session->flashdata('exception_2')) {

        echo '<section class="alert-wrapper"><div class="alert alert-danger alert-dismissible fade show"> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <div class="alert-body">
        <p><i class="m-right fa fa-times"></i>';
        echo escape_output($this->session->flashdata('exception_2'));
        echo '</p></div><div></section>';
    }
    ?>


    <div class="row">
        <?php
        //icon color code array
        $arr_color = array("green","blue","red","#c151fb9c","#5195fb9c","#8be43a9c","#00c0ef9c","#0044cc9c","#f39c129c","#3333339c","#8a6d1c9c");
        foreach ($outlets as $value) {
            ?>
        <div class="row">
            <!-- /.col -->
            <?php
            //icon color code array
            $arr_color = array("green","blue","red","#c151fb","#5195fb","#8be43a","#00c0ef","#0044cc","#f39c12","#333","#8a6d1c");
            foreach ($outlets as $value) {
                ?>
                <div class="col-sm-12 mb-3 col-md-3 <?php echo isset($value->active_status) && $value->active_status=="inactive"?'txt_inactive':''?>">
                    <div class="outlet-box text-center">
                        <i class="fa fa-building fa-3x" style="color:<?=escape_output($arr_color[array_rand($arr_color, 1)])?>"></i>
                        <h3 class="title"><?php echo escape_output($value->outlet_name); ?></h3>
                        <h4 class="outlet_address"><?php echo lang('address'); ?>: <?php echo escape_output($value->address); ?> </h4>
                        <h4 class="outlet_phone"><?php echo lang('phone'); ?>: <?php echo escape_output($value->phone); ?> </h4>
                        <h4 class="outlet_phone"><?php echo lang('email'); ?>: <?php echo escape_output($value->email); ?> </h4>
                    </div>
                </div>
                <?php
            }
            ?>
            <?php
        }?>
    </div>
        <p>&nbsp;</p>
        <a href="<?php echo base_url() ?>Service/companies"><button type="button"
                                                                    class="btn btn-primary"><?php echo lang('back'); ?></button></a>
</section>
