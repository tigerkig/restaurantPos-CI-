<?php
if(file_exists("../application/installed")){
    die('<p style="text-align:center;padding:25px;font-weight:600;font-size:22px;">The script is already installed, the installer stopped.</p>');
    exit;
}
$indexFile = "../index.php";
$configFolder = "../application/config";
$configFile = "../application/config/config.php";
$dbFile = "../application/config/database.php";

@session_start();

    $step = isset($_GET['step']) ? $_GET['step'] : '';  
    switch ($step) {
        default: ?>
              <div class="panel-group">
                <div class="panel panel-default">
                  <div class="panel-heading">
                            <ul class="list">
                                <li class="active pk">Env. Check</li>
                                <li>DB Config</li>
                                <li>Site Config</li>
                                <li class="last">Complete!</li>
                            </ul>
                  </div>
                  <div class="panel-body">
                            <h3 class="text-center padding_70">Installation Requirements Checklist</h3>
                            <?php
                            $error = FALSE;
                            if (!file_exists('database-response.json')) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> The database dump does not exist. The dump must be named \"<b>database-response.json</b>\" and located in the \"<b>install</b>\" folder.</div>";
                            }
                            else{
                                echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> The database dump \"<b>database-response.json</b>\" exists!</div>";
                            }
                            if (!function_exists('file_get_contents')) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> file_get_contents() function is not enabled in your server !</div>";
                            }
                            if (!is_writeable($configFolder)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Config Folder (application/config/) is not write able!</div>";
                            }
                            if (!is_writeable($configFile)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Config File (application/config/config.php) is not write able!</div>";
                            }
                            if (!is_writeable($dbFile)) {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Database File (application/config/database.php) is not writable!</div>";
                            }
                            if (phpversion() < "7.0") {
                                $error = TRUE;
                                echo "<div class='alert alert-danger'><i class='icon-remove'></i> Your PHP version is ".phpversion()."! PHP 7.0 or higher required!</div>";
                            } else {
                                echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> You are running PHP ".phpversion()."</div>";
                            }
                            if (!extension_loaded('mysqli')) {
                                $error = TRUE;
                                echo "<div class='alert alert-error'><i class='icon-remove'></i> Mysqli PHP extension missing!</div>";
                            } else {
                                echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> Mysqli PHP extension loaded!</div>";
                            }
                            ?>
                            <div class="bottom">
                                <?php if ($error) { ?>
                                <a href="#" class="btn btn-primary button_1">Next</a>
                                <?php } else { ?>
                                <a href="index.php?step=0" class="btn btn-primary button_1">Next</a>
                                <?php } ?>
                            </div>
                  </div>
                </div>
              </div>

        <?php
        break;
        case "0":
        ?>

        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
            <h3 style="margin-left:25px; margin-top: 10px; text-align: center;">Database Configuration</h3>
            <p style="margin-left:25px;">Please create a database in your server. And enter the db information here.</p>
            <form action="index.php?step=1" method="POST" class="form-horizontal">
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_hostname">Database Host</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_hostname" type="text" name="db_hostname" class="input-large" required data-error="DB Host is required" placeholder="DB Host" value="localhost" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_username">Database Username</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_username" type="text" name="db_username" class="input-large" autocomplete="off" required data-error="DB Username is required" placeholder="DB Username" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_password">Database Password</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_password" type="password" name="db_password" class="input-large" autocomplete="off" data-error="DB Password is required" placeholder="DB Password" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="db_name">Database Name</label>
                    <div class="controls">
                        <input style="width: 100%;" id="db_name" type="text" name="db_name" class="input-large" autocomplete="off" required data-error="DB Name is required" placeholder="DB Name" />
                    </div>
                </div>
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Next"/>
                </div>
                </div>
                </div>
                </div>
            </form>
        <?php
        break;
        case "1":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="active">DB Config</li>
                            <li>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
                <h3 style="text-align: center;">Saving database config</h3>
        <?php
        if ($_POST) {
            $db_hostname = $_POST["db_hostname"];
            $db_username = $_POST["db_username"];
            $db_password = $_POST["db_password"];
            $db_name = $_POST["db_name"];
            $link = new mysqli($db_hostname, $db_username, $db_password);
            if (mysqli_connect_errno()) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Could not connect to MYSQL!</div>";
            } else {
                echo '<div class="alert alert-success"><i class="icon-ok"></i> Connection to MYSQL successful!</div>';
                $db_selected = mysqli_select_db($link, $db_name);
                if (!$db_selected) {
                    if (!mysqli_query($link, "CREATE DATABASE IF NOT EXISTS `$db_name`")) {
                        echo "<div class='alert alert-error'><i class='icon-remove'></i> Database " . $db_name . " does not exist and could not be created. Please create the Database manually and retry this step.</div>";
                        return FALSE;
                    } else {
                        echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> Database " . $db_name . " created</div>";
                    }
                }
                mysqli_select_db($link, $db_name);

                require_once('includes/core_class.php');
                $core = new Core();
                $dbdata = array(
                    'db_hostname' => $db_hostname,
                    'db_username' => $db_username,
                    'db_password' => $db_password,
                    'db_name' => $db_name
                    );

                if ($core->write_database($dbdata) == false) {
                    echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write database details to ".$dbFile."</div>";
                } else {
                    echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> Database config written to the database file.</div>";
                }

            }
        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
        ?>
        <div class="bottom">
            <form action="index.php?step=1" method="POST" class="form-horizontal">
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Previous"/>
                </div>
            </form>
            <form action="index.php?step=2" method="POST" class="form-horizontal">
                <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                    <input type="submit" class="btn btn-primary button_1"  value="Next"/> 
                </div>
            </form>
            <br clear="all">
        </div>
        </div>
        </div>
        </div>
        <?php
        break;
        case "2":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="ok"><i class="icon icon-ok"></i>DB Config</li>
                            <li class="active">Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
        <h3 style="margin-left:25px; text-align: center;">Site Config</h3>
        <?php
        if (isset($_POST)) {
            ?>
            <form action="index.php?step=3" method="POST" class="form-horizontal">
                <div class="control-group" style="margin-left:25px; margin-right:25px;">
                    <label class="control-label" for="irestora_version">iRestora Version</label>
                    <div class="controls">
                        <select tabindex="5" class="form-control input-large" id="irestora_version" name="irestora_version" autocomplete="off" required data-error="iRestora Version is required">
                            <option value="">Select a version</option>
                            <?php
                            $versions = ['stwtyqxst' => 'Unique outlet + Whitelabel', 'revhgbrev' => 'Multiple outlets + Whitelabel', 'sGmsJaFJVE' => 'Multiple outlets + Whitelabel + Saas'];
                            foreach ($versions as $key => $name) {
                            ?>
                            <option value="<?= $key ?>"><?= $name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="installation_url">Installation URL</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="installation_url" name="installation_url" class="xlarge" required data-error="Installation URL is required" value="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="Encryption Key">Encryption Key</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="enckey" name="enckey" class="xlarge" required data-error="Encryption Key is required"
                        value="<?php

                        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $charactersLength = strlen($characters);
                        $randomString = '';
                        for ($i = 0; $i < 20; $i++) {
                            $randomString .= $characters[rand(0, $charactersLength - 1)];
                        }

                        echo $randomString;

                        ?>"
                        readonly />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="admin_name">Admin name</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="admin_name" name="admin_name" class="xlarge" required data-error="Admin name is required" value="" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="admin_email">Admin email</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="admin_email" name="admin_email" class="xlarge" required data-error="Admin email is required" value="" />
                    </div>
                </div>
                <div class="control-group" style="margin-left:25px; margin-right: 25px;">
                    <label class="control-label" for="admin_pass">Admin password</label>
                    <div class="controls">
                        <input style="width: 100%;" type="text" id="admin_pass" name="admin_pass" class="xlarge" required data-error="Admin password is required" value="" />
                    </div>
                </div>
                <div class="bottom">
                    <a href="index.php?step=1" class="btn btn-primary button_1">Previous</a>
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Next"/>
                    </div>
                </div>
            </form>
                </div>
                </div>
                </div>

            <?php
        }
        break;
        case "3":
        ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="ok"><i class="icon icon-ok"></i>DB Config</li>
                            <li class="active">Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div> 
                <div class="panel-body">
        <h3 style="margin-left:25px; text-align: center;">Saving site config</h3>
        <?php
        if (isset($_POST)) {
            $admin_name = $_POST['admin_name'];
            $admin_email = $_POST['admin_email'];
            $admin_pass = $_POST['admin_pass'];
            $irestora_version = $_POST['irestora_version'];

            $enckey = $_POST['enckey']; 

            require_once('includes/core_class.php');
            $core = new Core();

            if ($core->write_config($enckey) == false) {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Failed to write config details to ".$configFile."</div>"; 
            } else { 
                echo "<div class='alert alert-success'><i class='icon-ok' style='color:green;'></i> Config details written to the config file.</div>";
            }

        } else { echo "<div class='alert alert-success'><i class='icon-question-sign'></i> Nothing to do...</div>"; }
        ?>
        <div class="bottom">
            <form action="index.php?step=1" method="POST" class="form-horizontal">
                <div class="bottom">
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Previous"/>
                    </div>
                </div>
            </form>
            <form action="index.php?step=4" method="POST" class="form-horizontal">
                <div class="bottom">
                    <div class="bottom" style="width: 100%; margin-left:25px; margin-top: 10px;">
                        <input type="submit" class="btn btn-primary button_1"  value="Next"/>
                        <input type="hidden" name="admin_name"  value="<?php echo $admin_name;?>"/>
                        <input type="hidden" name="admin_email"  value="<?php echo $admin_email;?>"/>
                        <input type="hidden" name="admin_pass"  value="<?php echo $admin_pass;?>"/>
                        <input type="hidden" name="irestora_version"  value="<?php echo $irestora_version;?>"/>
                    </div>
                </div>
            </form>
            <br clear="all">
        </div>
        </div>
        </div>
        </div>

        <?php
        break;
        case "4": ?>
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <ul class="list">
                            <li class="ok"><i class="icon icon-ok"></i>Env. Check</li>
                            <li class="ok"><i class="icon icon-ok"></i>DB Config</li>
                            <li class="ok"><i class="icon icon-ok"></i>Site Config</li>
                            <li class="last">Complete!</li>
                        </ul>
                    </div>
                <div class="panel-body">

        <?php
        if (isset($_POST) && file_exists('database-response.json')) {

            define("BASEPATH", "install/");
            include("../application/config/database.php");

            $buffer = file_get_contents('database-response.json');
            $object = json_decode($buffer);

            if ($object->status == 'success') {
                //need to change
                $admin_name = $_POST['admin_name'];
                $admin_email = $_POST['admin_email'];
                $admin_pass = $_POST['admin_pass'];
                $irestora_version = $_POST['irestora_version'];
                $search = array('{admin_name}','{admin_email}','{admin_pass}','{irestora_version}');
                $replace = array($admin_name, $admin_email, md5($admin_pass), $irestora_version);
                $dbtables = str_replace($search, $replace, $object->database);
                $dbdata = array(
                    'hostname' => $db['default']['hostname'],
                    'username' => $db['default']['username'],
                    'password' => $db['default']['password'],
                    'database' => $db['default']['database'],
                    'dbtables' => $dbtables
                    );
                require_once('includes/database_class.php');
                $database = new Database();
                if ($database->create_tables($dbdata) == false) {
                    $finished = FALSE;
                    echo "<div class='alert alert-warning'><i class='icon-warning'></i> The database tables could not be created, please try again.</div>";
                } else {
                    $finished = TRUE;
                    require_once('includes/core_class.php');
                    $core = new Core();
                    $core->create_rest_api_w();
                }

            } else {
                echo "<div class='alert alert-error'><i class='icon-remove'></i> Error while loading database file!</div>";
            }

        }
        if ($finished) {
            file_put_contents("../application/installed", 'White ass and the seven hands');
            ?>

            <h3 style="margin-left:25px; text-align: center; margin-top: 10px"><i class='icon-ok'></i> Installation completed!</h3>
            <div style="margin-left:25px; margin-right: 50px; width: 90%; padding: 10px; border-radius: 5px; border: 1px solid #b5d6f6;">Please login now using the following credential:<br /><br />
                Email Address: <span style="font-weight:bold; letter-spacing:1px;"><?php echo $admin_email;?></span><br />Password: <span style="font-weight:bold; letter-spacing:1px;"><?php echo $admin_pass;?></span><br /><br />
            </div> 
            <div class="bottom">
                <div class="bottom" style="width: 100%;margin-top: 10px;"> 
                    <a href="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" class="btn btn-primary button_1">Go to Login Page</a>
                </div>
            </div>
            </div>
            </div>
            </div>

            <?php
        }
    } 
?> 