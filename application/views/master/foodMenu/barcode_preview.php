<script src="<?php echo base_url(); ?>assets/plugins/barcode/JsBarcode.all.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/inline_priority.css">
<section class="main-content-wrapper">
    
    <div class="box-wrapper">
        <div class="table-box">
            <div class="row">
                <div id="printableArea">
                    <?php
                    for($i=0;$i<sizeof($items);$i++):
                    for ($j=0;$j<$items[$i]['qty'];$j++):
                        ?>
                        <table class="custom_txt_4 width_unset">
                            <tr>
                                <td class="custom_txt_5"> <span><?=$items[$i]['item_name']?></span></td>
                            </tr>
                            <tr>
                                <td class="custom_txt_5"> <span><?=$items[$i]['code']?></span></td>
                            </tr>
                            <tr>
                                <td> <img class="custom_txt_6" id="barcode<?=$items[$i]['id']?><?=$j?>"/></td>
                            </tr>
                            <tr>
                                <td class="custom_txt_7"><span><?=getAmt($items[$i]['sale_price'])?></span></td>
                            </tr>
                        </table>
                    <?php
                    endfor;
                    ?>
                    <?php for ($j=0;$j<$items[$i]['qty'];$j++):
                    ?>
                        <script>JsBarcode("#barcode<?=$items[$i]['id']?><?=$j?>", "<?=$items[$i]['code']?>", {  width: <?=isset($barcode_width) && $barcode_width?$barcode_width:1?>,
                                height: <?=isset($barcode_height) && $barcode_height?$barcode_height:30?>,
                                fontSize:12,
                                textMargin:-18,
                                margin:0,
                                marginTop:0,
                                marginLeft:10,
                                marginRight:10,
                                marginBottom:0,
                                displayValue: false
                            });
                        </script>
                        <?php
                    endfor;
                    endfor;
                    ?>
                </div>
                
                <div class="col-md-2">
                        <a class="btn bg-blue-btn w-100" href="<?php echo base_url() ?>foodMenu/foodMenuBarcode">
                            <?php echo lang('back'); ?>
                        </a>
                </div>

                <div class="col-md-2">
                        <a class="btn bg-blue-btn w-100" onclick="printDiv('printableArea')">Print</a>
                   
                </div>
            </div>
        </div>
    </div>
        
</section>
<script src="<?php echo base_url(); ?>frequent_changing/js/barcode_preview.js"></script>