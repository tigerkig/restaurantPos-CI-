<?php
//load library
require __DIR__ . '/escpos-php/autoload.php';
//include helper function
include_once 'include/printer_helper.php';
//include printer load
include_once 'include/printer_load.php';

if(isset($_POST['content_data'])){
    $object = json_decode(($_POST['content_data']));
    if($object){
        if($object->print_type=="invoice"){
            print_receipt($object);
        }else if ($object->print_type=="bill"){
            print_receipt_bill($object);
        }else if ($object->print_type=="kot"){
            print_receipt_kot($object);
        }else if ($object->print_type=="bot"){
            print_receipt_bot($object);
        }
    }
}