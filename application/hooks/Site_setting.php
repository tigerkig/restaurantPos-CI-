<?php
class Site_setting { 


    function setSetting(){ 

        define('APPLICATION_MODE', 'live');

        if (APPLICATION_MODE == 'demo') {
            # Load the URI core class
            $uri =& load_class('URI', 'core'); 

            # Get the third segment
            $get_second_uri = $uri->segment(2); // returns the id
            $get_first_uri = ucfirst($uri->segment(1)); // returns the id
            $first_six_letter = substr($get_second_uri, 0, 6); 
            if ($first_six_letter == "delete") {
                //There are no view page that's why used the inline css
                echo "<h2 style='color: red; margin-top: 15%; text-align: center;'>Deleting is not allowed in demo mode!</h2>";
                exit;
            }

            if ($get_second_uri == 'setting' || $get_second_uri == 'changeProfile' || $get_second_uri == 'changePassword'  || $get_second_uri == 'TaxSetting'  || $get_second_uri == 'whiteLabel') {
                if (!empty($_POST['submit'])) {
                    //There are no view page that's why used the inline css
                    echo "<h2 style='color: red; margin-top: 15%; text-align: center;'>Not allowed in demo mode!</h2>";
                    exit;
                }
            }
            if ($get_first_uri == 'Update') {
                //There are no view page that's why used the inline css
                echo "<h2 style='color: red; margin-top: 15%; text-align: center;'>Not allowed in demo mode!</h2>";
                exit;
            }
        }

    }
}
?>