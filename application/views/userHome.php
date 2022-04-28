<?php
    $wl = getWhiteLabel();
    $system_logo = '';
    if($wl){
        if($wl->site_name){
            $site_name = $wl->site_name;
        }
        if($wl->footer){
            $footer = $wl->footer;
        }
        if($wl->system_logo){
            $system_logo = base_url()."images/".$wl->system_logo;
        }
    }
//get company information
$getCompanyInfo = getCompanyInfo();
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo escape_output($site_name); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/lib/currency-flags/dist/currency-flags.css">

    <!-- InputMask -->
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/custom_css.css">

    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/POS/sweetalert2/dist/sweetalert.min.css">

    <!-- Numpad -->
    <script src="<?php echo base_url(); ?>assets/bower_components/numpad/jquery.numpad.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/numpad/jquery.numpad.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/numpad/theme.css">
    <!--datepicker-->
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datepicker/datepicker.css">

    <!-- bootstrap datepicker -->
    <script src="<?php echo base_url(); ?>assets/bower_components/datepicker/bootstrap-datepicker.js"></script>

    <!-- Bootstrap 5.0.0 -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.min.css">
    <!-- New Admin Panel Design -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/newDesign/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.css">
    
    <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.css"> -->
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>asset/plugins/iCheck/minimal/color-scheme.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/userHome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/common.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/nice-select/css/nice-select.css">
    

    <!-- Google Font -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/local/google_font.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>frequent_changing/css/custom_tooltip.css">
    <script src="<?php echo base_url(); ?>frequent_changing/js/user_home.js"></script>
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/x-icon">
</head>

<?php $uri = $this->uri->segment(2); ?>
<div class="loader"></div>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->

<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<input type="hidden" id="ingredient" value="<?php echo lang('ingredient'); ?>">
<input type="hidden" id="stock_value" value="<?php echo lang('stock_value'); ?>">
<input type="hidden" id="currency" value="<?php echo escape_output($this->session->userdata('currency')); ?>">
<input type="hidden" id="csrf_name_" value="<?php echo escape_output($this->security->get_csrf_token_name()); ?>">
<input type="hidden" id="csrf_value_" value="<?php echo escape_output($this->security->get_csrf_hash()); ?>">
<input type="hidden" id="warning" value="<?php echo lang('alert'); ?>">
<input type="hidden" id="a_error" value="<?php echo lang('error'); ?>">
<input type="hidden" id="ok" value="<?php echo lang('ok'); ?>">
<input type="hidden" id="cancel" value="<?php echo lang('cancel'); ?>">
<input type="hidden" id="are_you_sure" value="<?php echo lang('are_you_sure'); ?>">
<input type="hidden" id="not_closed_yet" value="<?php echo lang('not_closed_yet'); ?>">
<input type="hidden" id="opening_balance" value="<?php echo lang('opening_balance'); ?>">
<input type="hidden" id="paid_amount" value="<?php echo lang('paid_amount'); ?>">
<input type="hidden" id="customer_due_receive" value="<?php echo lang('customer_due_receive'); ?>">
<input type="hidden" id="opening_balance" value="<?php echo lang('opening_balance'); ?>">
<input type="hidden" id="paid_amount" value="<?php echo lang('paid_amount'); ?>">
<input type="hidden" id="customer_due_receive" value="<?php echo lang('customer_due_receive'); ?>">
<input type="hidden" id="in_" value="<?php echo lang('in'); ?>">
<input type="hidden" id="cash" value="<?php echo lang('cash'); ?>">
<input type="hidden" id="paypal" value="<?php echo lang('paypal'); ?>">
<input type="hidden" id="sale" value="<?php echo lang('sale'); ?>">
<input type="hidden" id="card" value="<?php echo lang('card'); ?>">
<input type="hidden" id="register_not_open" value="<?php echo lang('register_not_open'); ?>">
<input type="hidden" id="base_url_" value="<?php echo base_url(); ?>">
<input type="hidden" id="site_logo" value="<?php echo base_url(); ?>assets/media/logo.png">
<input type="hidden" id="site_favicon" value="<?php echo base_url(); ?>images/favicon.ico">
<input type="hidden" id="saas_m_ch" value="<?=file_exists(APPPATH.'controllers/Service.php')?'yes':''?>">
<input type="hidden" id="ir_precision" value="<?php echo escape_output($getCompanyInfo->precision)?>">
<input type="hidden" class="active_menu_tmp" value="<?php echo $this->session->userdata('active_menu_tmp')?>"
<?php $language = $this->session->userdata('language'); ?>

    <div class="main-preloader">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    </div>
    <!-- Site wrapper -->
    <div class="wrapper <?=isset($language) && $language=="arabic"?'arabic-lang"':''?>">

        <header class="main-header <?=isset($language) && $language=="arabic"?'sidebar2_active"':''?>">
            <nav class="navbar navbar-static-top" <?=isset($language) && $language=="arabic"?'style="margin-left:0px"':''?>>
                <div class="wrapper_up_wrapper">
                    <div class="hh_wrapper">
                        <div class="navbar-custom-menu">
                            <div class="menu-trigger-box">
                                <a href="javascript:void(0)" data-toggle="push-menu" class="st"><i data-feather="menu"></i></a>
                                <a href="javascript:void(0)" class="om"><i data-feather="grid"></i></a>
                            </div>
                            <ul class="screen-list">

                                <?php if ($this->session->userdata('outlet_id')) { ?>
                                <!-- User Account: style can be found in dropdown.less -->
                                <li class="dropdown user user-menu">
                                    <a href="<?php echo base_url(); ?>POSChecker/posAndWaiterMiddleman">
                                        <!-- <i class="fa fa-cutlery"></i> -->
                                        <i data-feather="coffee"></i>
                                        <span class="hidden-xs"><?php echo lang('pos'); ?></span>
                                    </a>
                                </li>
                                <li class="dropdown user user-menu">
                                    <a href="<?php echo base_url(); ?>Purchase/addEditPurchase">
                                        <i data-feather="truck"></i>
                                        <span class="hidden-xs"><?php echo lang('add_purchase'); ?></span>
                                    </a>
                                </li>
                                <?php if ($this->session->userdata('role') == "Admin") { ?>
                                <li class="dropdown user user-menu">
                                    <a href="#" onclick="todaysSummary();" class="dropdown-toggle" data-toggle="dropdown">
                                        <i data-feather="truck"></i> <span
                                            class="hidden-xs"><?php echo lang('todays_summary'); ?></span>
                                    </a>
                                </li>
                                <?php
                                        }
                                        $url = $this->uri->segment(2);
                                        if ($url == "addEditSale"):
                                            ?>
                                <li class="dropdown user user-menu">
                                    <a href="#" onclick="shortcut();" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-keyboard-o"></i><span
                                            class="hidden-xs"><?php echo lang('shortcut_keys'); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <li class="dropdown user user-menu">
                                    <a href="#" id="register_details">
                                        <i data-feather="info"></i> <span
                                            class="hidden-xs"><?php echo lang('register_details'); ?></span>
                                    </a>
                                </li>
                                <li class="dropdown user user-menu ir_display_none" id="close_register_button">
                                    <a href="#" id="register_close">
                                        <i data-feather="x-circle"></i> <span
                                            class="hidden-xs"><?php echo lang('close_register'); ?></span>
                                    </a>
                                </li>

                                <?php } ?>
                                </ul>
                        </div>
                        <div class="navbar-custom-menu">
                        
                            <ul class="menu-list">
                                    <li class="lang-dropdown">
                                        <?php $language=$this->session->userdata('language');
                                        $icon = "usd";
                                        if(isset($language) && $language=="english"):
                                              $icon = "usd";
                                        endif;
                                        if(isset($language) && $language=="arabic"):
                                              $icon = "sar";
                                        endif;
                                        if(isset($language) && $language=="french"):
                                              $icon = "xpf";
                                        endif;
                                        if(isset($language) && $language=="spanish"):
                                              $icon = "spn";
                                        endif;
                                        ?>
                                        <button class="show-drop-result"><a href="#"><i class="currency-flag currency-flag-<?php echo $icon?>"></i> <span><?php echo ucfirst($language)?></span></a></button>
                                        <ul class="lang-dropdown-menu">
                                                <?php
                                                $dir = glob("application/language/*",GLOB_ONLYDIR);
                                                foreach ($dir as $value):
                                                    $separete = explode("language/",$value);
                                                    if(isset($separete[1]) && $separete[1]=="english"):
                                                    ?>
                                                    <li data-lang="English" data-icon="usd"><a href="<?php echo base_url()?>Authentication/setlanguage/<?php echo escape_output($separete[1])?>"><i class="currency-flag currency-flag-usd"></i> <span><?php echo ucfirst($separete[1])?></span></a></li>
                                                        <?php
                                                        endif;
                                                      ?>
                                                    <?php
                                                    if(isset($separete[1]) && $separete[1]=="arabic"):
                                                    ?>
                                                        <li data-lang="Arabic" data-icon="sar"><a href="<?php echo base_url()?>Authentication/setlanguage/<?php echo escape_output($separete[1])?>"><i class="currency-flag currency-flag-sar"></i> <span><?php echo ucfirst($separete[1])?></span></a></li>
                                                    <?php
                                                endif;
                                                    ?>

                                                    <?php
                                                    if(isset($separete[1]) && $separete[1]=="french"):
                                                        ?>
                                                        <li data-lang="French" data-icon="xpf"><a href="<?php echo base_url()?>Authentication/setlanguage/<?php echo escape_output($separete[1])?>"><i class="currency-flag currency-flag-xpf"></i> <span><?php echo ucfirst($separete[1])?></span></a></li>
                                                        <?php
                                                    endif;
                                                    ?>

                                                    <?php
                                                    if(isset($separete[1]) && $separete[1]=="spanish"):
                                                        ?>
                                                        <li data-lang="Spanish" data-icon="spn"><a href="<?php echo base_url()?>Authentication/setlanguage/<?php echo escape_output($separete[1])?>"><i class="currency-flag currency-flag-spn"></i> <span><?php echo ucfirst($separete[1])?></span></a></li>
                                                        <?php
                                                    endif;
                                                    ?>
                                                    <?php
                                                endforeach;
                                                ?>
                                        </ul>
                                    </li>
                                    <li class="user-info-box">
                                        <div class="user-profile">
                                            <div class="user-info">
                                                <p class="user-name"><?php echo escape_output($this->session->userdata('full_name')); ?></p>
                                                <?php
                                                    $role = $this->session->userdata('role');
                                                ?>
                                                <span class="user-role"><?php echo $role?></span>
                                            </div>
                                            <img class="user-avatar" src="<?=base_url()?>images/avatar.png" alt="">
                                        </div>
                                        <div class="c-dropdown-menu">
                                            <ul>
                                                <?php
                                                $getAccessURL = ucfirst($this->uri->segment(1));
                                                if (in_array($getAccessURL, $this->session->userdata('menu_access'))):
                                                ?>
                                                <li><a href="<?php echo base_url()?>User/users"><i data-feather="sliders"></i> <?php echo lang('manage_users')?></a></li>
                                                <?php
                                                  endif;
                                                ?>
                                                <li><a href="<?php echo base_url()?>Authentication/changeProfile"><i data-feather="user"></i> <?php echo lang('change_profile')?></a></li>
                                                <li><a href="<?php echo base_url()?>Authentication/changePassword"><i data-feather="key"></i> <?php echo lang('change_password')?></a></li>
                                                <li><a href="<?=base_url()?>Authentication/logOut"><i data-feather="log-out"></i> <?php echo lang('logout')?></a></li>
                                            </ul>
                                        </div>
                                    </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>


        <?php if(isset($language) && $language=="arabic"):?>
            <!-- Right side column. contains the sidebar -->
        <aside class="main-sidebar2" >
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="inner-circle"></span>
            </a>
            <!-- Sidebar toggle button-->
            <a href="#" class="logo-wrapper">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">iR</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <img src="<?php echo escape_output($system_logo); ?>">
                </span>
            </a>
          <!-- Admin Logo Part End -->
          <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left info">
                        <p><?php echo escape_output($this->session->userdata('outlet_name')); ?></p>
                        <p><?php echo escape_output($this->session->userdata('full_name')); ?></p>
                    </div>
                </div>

                <div id="left_menu_to_scroll">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <?php
                        $menu_access = getMainMenu();
                        foreach ($menu_access as $ky=>$value_menu):
                            $base_url_g = base_url().$value_menu->controller_name."/".$value_menu->function_name;
                            if(isset($value_menu->sub_menus) && $value_menu->sub_menus):
                                if($value_menu->label=="Saas" && isServiceAccess('','','sGmsJaFJE')):
                            ?>
                                <li class="active treeview">
                                    <a href="#">
                                        <i data-feather="file-text"></i> <span><?php echo lang($value_menu->label); ?></span>


                                    </a>
                                    <ul class="treeview-menu">
                                        <?php
                                        foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                            $show_status  = '';
                                            if($value_menu1->function_name=="foodTransferReport" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                $show_status  = 'none';
                                            }

                                            if($value_menu1->controller_name=="POS"){
                                                $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                            }else{
                                                $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                            }

                                        ?>
                                            <!--This variable could not be escaped because this is base url content-->
                                            <li style="display: <?php echo $show_status?>;"><a href="<?php echo $base_url_g1?>">
                                                <?php echo lang($value_menu1->label); ?></a></li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                </li>
                            <?php
                                    elseif($value_menu->label!="Saas"):?>
                                        <li class="treeview">
                                            <a href="#">
                                                <i data-feather="file-text"></i>
                                                <span><?php echo lang($value_menu->label); ?></span>

                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                                    $show_status  = '';
                                                    if($value_menu1->function_name=="foodTransferReport" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                        $show_status  = 'none';
                                                    }
                                                    if($value_menu1->controller_name=="POS"){
                                                        $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                                    }else{
                                                        $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                                    }

                                                    ?>
                                                    <!--This variable could not be escaped because this is base url content-->
                                                    <<li style="display: <?php echo $show_status?>;"><a href="<?php echo $base_url_g1?>">
                                                    <?php echo lang($value_menu1->label); ?></a></li>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </li>
                                        <?php
                                        endif;
                            else:
                                    $data_c = getLanguageManifesto();
                                    if($value_menu->controller_name=="Transfer" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                        ?>

                                        <?php
                                    }else if($value_menu->controller_name=="Plugin" && isServiceAccess('','','sGmsJaFJE')){
                                        $label = lang($value_menu->label);
                                        $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;

                                        ?>
                                        <li>
                                            <!--This variable could not be escaped because this is base url content-->
                                             <a href="<?php echo $base_url_g ?>">
                                                <?php
                                                if ($value_menu->icon):
                                                    ?>
                                                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                                                    <?php
                                                endif;
                                                ?>
                                        <span> <?php echo escape_output($label); ?></span>
                                        </a>
                                        </li>
                              <?php }else if($value_menu->controller_name!="Plugin"){?>
                                        <li>
                                            <?php
                                            $data_c = getLanguageManifesto();
                                            $show_status  = '';
                                            if($value_menu->function_name=="inventory_food_menu" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                $show_status  = 'none';
                                            }
                                            $label = lang($value_menu->label);
                                            if ($value_menu->controller_name == "Outlet") {
                                                if (str_rot13($data_c[0]) == "eriutoeri") {
                                                    $label = lang('outlets');
                                                } else if (str_rot13($data_c[0]) == "fgjgldkfg") {
                                                    $label = lang('outlet_setting');
                                                }
                                                $base_url_g = base_url() . $data_c[1];
                                            } else {
                                                $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;
                                            }
                                            ?>
                                            <!--This variable could not be escaped because this is base url content-->
                                            <a  style="display: <?php echo $show_status?>;"  href="<?php echo $base_url_g ?>">
                                                <?php
                                                if ($value_menu->icon):
                                                    ?>
                                                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                                                    <?php
                                                endif;
                                                ?>
                                                <span> <?php echo escape_output($label); ?></span>
                                            </a>
                                        </li>
                                        <?php
                                  }
                                    ?>
                                    <?php
                          endif;
                        endforeach;
                        ?>
                    </ul>

                </div>
            </section>
            <!-- /.sidebar -->
        </aside>
        <?php else:?>
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar" <?=isset($language) && $language=="arabic"?'style="display:none"':''?>>

            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" <?=isset($language) && $language=="arabic"?'style="display:none"':''?>>
                <span class="sr-only">Toggle navigation</span>
                <span class="inner-circle"></span>
            </a>
            <!-- Sidebar toggle button-->
            <a href="#" class="logo-wrapper" <?=isset($language) && $language=="arabic"?'style="display:none"':''?>>
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">iR</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <img src="<?php echo escape_output($system_logo); ?>">
                </span>
            </a>
            <!-- Admin Logo Part End -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left info">
                        <p><?php echo escape_output($this->session->userdata('outlet_name')); ?></p>
                        <p><?php echo escape_output($this->session->userdata('full_name')); ?></p>
                    </div>
                </div>
                <!-- <ul class="sidebar-menu">
                    <li class="header"><?php echo lang('main_navigation'); ?></li>
                </ul> -->
                <div id="left_menu_to_scroll">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <?php
                        $menu_access = getMainMenu(); 
                        foreach ($menu_access as $ky=>$value_menu):
                            $ky++;
                            $base_url_g = base_url().$value_menu->controller_name."/".$value_menu->function_name;
                            if(isset($value_menu->sub_menus) && $value_menu->sub_menus):
                                if($value_menu->label=="Saas" && isServiceAccess('','','sGmsJaFJE')):
                            ?>
                                <li class="treeview menu__cid<?php echo escape_output($ky)?>">
                                    <a href="#">
                                        <i data-feather="file-text"></i> <span><?php echo lang($value_menu->label); ?></span>
                                        
                                        
                                    </a>
                                    <ul class="treeview-menu">
                                        <?php
                                        foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                            $show_status  = '';
                                            if($value_menu1->function_name=="foodTransferReport" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                $show_status  = 'none';
                                            }
                                            if($value_menu1->controller_name=="POS"){
                                                $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                            }else{
                                                $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                            }

                                        ?>
                                            <!--This variable could not be escaped because this is base url content-->
                                            <li style="display: <?php echo $show_status?>;" class="menu_assign_class" data-menu__cid="<?php echo $ky?>"><a href="<?php echo $base_url_g1?>">
                                                <?php echo lang($value_menu1->label); ?></a></li>
                                            <?php
                                        endforeach;
                                        ?>
                                    </ul>
                                </li>
                            <?php
                                    elseif($value_menu->label!="Saas"):?>
                                        <li class="treeview menu__cid<?php echo escape_output($ky)?>">
                                            <a href="#">
                                                <i data-feather="file-text"></i> 
                                                <span><?php echo lang($value_menu->label); ?></span>
                                            
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                foreach ($value_menu->sub_menus as $ky1=>$value_menu1):
                                                $data_c = getLanguageManifesto();
                                                $show_status  = '';
                                                if($value_menu1->function_name=="foodTransferReport" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                    $show_status  = 'none';
                                                }
                                                    if($value_menu1->controller_name=="POS"){
                                                        $base_url_g1 = base_url()."Sale/".$value_menu1->function_name;
                                                    }else{
                                                        $base_url_g1 = base_url().$value_menu1->controller_name."/".$value_menu1->function_name;
                                                    }

                                                    ?>
                                                    <!--This variable could not be escaped because this is base url content-->
                                                    <li style="display: <?php echo $show_status?>;" class="menu_assign_class" data-menu__cid="<?php echo $ky?>"><a href="<?php echo $base_url_g1?>">
                                                    <?php echo lang($value_menu1->label); ?></a></li>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </li>
                                        <?php
                                        endif;
                            else:
                                    $data_c = getLanguageManifesto();
                                    if($value_menu->controller_name=="Transfer" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                        ?>

                                        <?php
                                    }else if($value_menu->controller_name=="Plugin" && isServiceAccess('','','sGmsJaFJE')){
                                        $label = lang($value_menu->label);
                                        $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;

                                        ?>
                                        <li class="menu_assign_class menu__cid<?php echo escape_output($ky)?>" data-menu__cid="<?php echo escape_output($ky)?>">
                                            <!--This variable could not be escaped because this is base url content-->
                                            <a href="<?php echo $base_url_g ?>">
                                                <?php
                                                if ($value_menu->icon):
                                                    ?>
                                                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                                                    <?php
                                                endif;
                                                ?>
                                        <span> <?php echo escape_output($label); ?></span>
                                        </a>
                                        </li>
                            <?php }else if($value_menu->controller_name!="Plugin"){?>
                                        <li class="menu_assign_class menu__cid<?php echo escape_output($ky)?>" data-menu__cid="<?php echo escape_output($ky)?>">
                                            <?php
                                            $data_c = getLanguageManifesto();
                                            $show_status  = '';
                                            if($value_menu->function_name=="inventory_food_menu" && str_rot13($data_c[0]) == "fgjgldkfg") {
                                                $show_status  = 'none';
                                            }
                                            $label = lang($value_menu->label);
                                            if ($value_menu->controller_name == "Outlet") {
                                                if (str_rot13($data_c[0]) == "eriutoeri") {
                                                    $label = lang('outlets');
                                                } else if (str_rot13($data_c[0]) == "fgjgldkfg") {
                                                    $label = lang('outlet_setting');
                                                }
                                                //$base_url_g = base_url() . $data_c[1];
                                                $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;
                                            } else {
                                                $base_url_g = base_url() . $value_menu->controller_name . "/" . $value_menu->function_name;
                                            }
                                            ?>
                                            <!--This variable could not be escaped because this is base url content-->
                                            <a style="display: <?php echo $show_status?>;"  href="<?php echo $base_url_g ?>">
                                                <?php
                                                if ($value_menu->icon):
                                                    ?>
                                                    <i data-feather="<?php echo escape_output($value_menu->icon); ?>"></i>
                                                    <?php
                                                endif;
                                                ?>
                                                <span> <?php echo escape_output($label); ?></span>
                                            </a>
                                        </li>
                                        <?php
                                }
                                    ?>
                                    <?php
                        endif;
                        endforeach;
                        ?>
                    </ul>

                </div>
            </section>
            <!-- /.sidebar -->
            </aside>

        <?php endif; ?>
        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" <?=isset($language) && $language=="arabic"?'style="margin-right:50px;margin-left:0px"':''?>>
            <!-- Main content -->
            <?php
                if (isset($main_content)) {
                    //This variable could not be escaped because this is html content
                    echo $main_content;
                }
            ?>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer"  <?=isset($language) && $language=="arabic"?'style="margin-left:0px"':''?>>
            <div class="row">
                <div class="col-md-12 ir_txt_center">
                    <strong><?php echo escape_output($footer); ?></strong> <span class='pull-right'> <?php echo lang('Version').$this->session->userdata('system_version_number')?></span>
                    <div class="hidden-lg"></div>
                </div>
            </div>
        </footer>
    </div>

    <div class="modal fade" id="todaysSummary" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="ShortCut">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="myModalLabel"><?php echo lang('todays_summary'); ?></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i data-feather="x"></i></span></button>
                </div>
                <div class="modal-body">
                    <div class="box-body table-responsive">
                        <table class="table">
                            <tr>
                                <td class="ir_w_80">
                                    <?php echo lang('purchase'); ?>(<?php echo lang('only_paid_amount'); ?>)</td>
                                <td><span id="purchase_today_"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('sale'); ?>(<?php echo lang('only_paid_amount'); ?>)</td>
                                <td><span id="sale_today"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('total'); ?> <?php echo lang('vat'); ?></td>
                                <td><span id="totalVat"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('expense'); ?></td>
                                <td><span id="Expense"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('supplier_due_payment'); ?></td>
                                <td><span id="supplierDuePayment"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('customer_due_receive'); ?></td>
                                <td><span id="customerDueReceive"></span></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('waste'); ?></td>
                                <td><span id="waste_today"></span></td>
                            </tr>
                            <tr>
                                <td>Balance = (<?php echo lang('sale'); ?> +
                                    <?php echo lang('customer_due_receive'); ?>) - (<?php echo lang('purchase'); ?> +
                                    <?php echo lang('supplier_due_payment'); ?> + <?php echo lang('expense'); ?>))</td>
                                <td><span id="balance"></span></td>
                            </tr>
                        </table>

                        <br>
                        <div id="showCashStatus"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo lang('register_details'); ?> <span
                            id="opening_closing_register_time">(<span id="opening_register_time"></span>
                            <?php echo lang('to'); ?> <span id="closing_register_time"></span>)</span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i data-feather="x"></i></button>
                </div>
                <div class="modal-body" id="register_details_body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue-btn"
                        data-bs-dismiss="modal"><?php echo lang('close'); ?></button>
                </div>
            </div>

        </div>
    </div>


    <!-- Bootstrap 5.0.0 -->
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/css-framework/bootstrap-new/bootstrap.bundle.min.js"></script>

    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/nice-select/js/jquery.nice-select.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/menu.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>
    <!-- custom scrollbar plugin -->
    <script src="<?php echo base_url(); ?>assets/dist/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- material icon -->
    <script src="<?php echo base_url(); ?>assets/dist/js/feather.min.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/user_home_buttom1222021v1.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/js/media.js"></script>
    <script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/new-script.js"></script>
</body>

</html>