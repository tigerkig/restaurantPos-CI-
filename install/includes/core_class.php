<?php

class Core {

    // Function to write the config file
    function write_config($enckey) {

        // Config path
        $template_path 	= 'config/config.php';
        $output_path 	= '../application/config/config.php';

        // Open the file
        $config_file = file_get_contents($template_path);

        $saved  = str_replace("%enckey%",$enckey,$config_file);

        // Write the new config.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$saved)) {
                @chmod($output_path,0644);
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    // Function to write the database file
    function write_database($data) {

        // Config path
        $template_path 	= 'config/database.php';
        $output_path 	= '../application/config/database.php';

        // Open the file
        $database_file = file_get_contents($template_path);

        $saved  = str_replace("%db_hostname%",$data['db_hostname'],$database_file);
        $saved  = str_replace("%db_username%",$data['db_username'],$saved);
        $saved  = str_replace("%db_password%",$data['db_password'],$saved);
        $saved  = str_replace("%db_name%",$data['db_name'],$saved);

        // Write the new database.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$saved)) {
                @chmod($output_path,0644);
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    function create_rest_api_w() {

        $path = "../assets/blueimp/white_label.json";

        $handle = fopen($path, "w");

        if ($handle) {
            $content = '{"is_white_label":"Yes"}';
            // Write the file
            if(fwrite($handle,$content)) {
                @chmod($output_path,0644);
                return true;
            } else {
                return false;
            }

            return true;
        }else{
            return false;
        }
    }

}