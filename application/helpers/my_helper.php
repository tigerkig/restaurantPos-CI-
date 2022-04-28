<?php
/**
 * return  characters to HTML entities.
 * @return string
 * @param string
 */
function escape_output($string){
    if($string){
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }else{
        return '';
    }

}
/**
 * get Main Menu
 * @access
 * @return object
 * @param no
 */
function getMainMenu() {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('tbl_admin_user_menus');
    $CI->db->where('is_ignore!=', 1);
    $CI->db->order_by('order_by asc');
    $main_row =  $CI->db->get()->result();
    foreach ($main_row as $key=>$value){
        $main_row[$key]->sub_menus = getAllByCustomId($value->id,"parent_id","tbl_admin_user_menus","");
    }
    return $main_row;

}
/**
 * get Main Menu
 * @access
 * @return boolean
 * @param no
 */
function isServiceAccess($user_id='',$company_id='',$service_type='') {
    $CI = & get_instance();
    $company = getMainCompany();
    $service_type = str_rot13($service_type);
    $status = false;
    if($user_id==''){
        $user_id = $CI->session->userdata('user_id');
    }
    if($company_id==''){
        $company_id = $CI->session->userdata('company_id');
    }
    if($service_type && $service_type =="fTzfWnSWR" && str_rot13($company->language_manifesto) =="fTzfWnSWIR" &&  file_exists(APPPATH.'controllers/Service.php')){
        $result = $CI->db->query("SELECT * FROM tbl_plugins WHERE del_status='Live' AND bestoro='$service_type' AND active_status='Active'")->result();
        if($result){
            if($company_id==1 && $user_id==1){
                $status = true;
            }
        }
    }
    return $status;
}

/**
 * get Main Menu
 * @access
 * @return boolean
 * @param no
 */
function isServiceAccessPlugin($user_id='',$company_id='',$service_type='') {
    $CI = & get_instance();
    $company = getMainCompany();
    $service_type = str_rot13($service_type);
    $status = false;
    if($company_id==''){
        $company_id = $CI->session->userdata('company_id');
    }
    if($service_type && $service_type =="fTzfWnSWR" && str_rot13($company->language_manifesto) =="fTzfWnSWIR" &&  file_exists(APPPATH.'controllers/Service.php')){
        $plugin = $result = $CI->db->query("SELECT * FROM tbl_plugins WHERE del_status='Live' AND bestoro='$service_type' AND active_status='Active'")->row();
        if($plugin){
            if($company_id==1){
                $status = true;
            }
        }
    }
    return $status;

}

/**
 * get Main Menu
 * @access
 * @return boolean
 * @param no
 */
function isServiceAccessOnly($service_type='') {
    $company = getMainCompany();
    $CI = & get_instance();
    $status = false;
    $company_id = $CI->session->userdata("company_id");
    if($company_id==1 && $service_type && $service_type =="fTzfWnSWR" && str_rot13($company->language_manifesto) =="fTzfWnSWIR" &&  file_exists(APPPATH.'controllers/Service.php')){
        return true;
    }else{
        $service_type = str_rot13($service_type);
        if($service_type && $service_type =="fTzfWnSWR" && str_rot13($company->language_manifesto) =="fTzfWnSWIR" &&  file_exists(APPPATH.'controllers/Service.php')){
            $plugin = $result = $CI->db->query("SELECT * FROM tbl_plugins WHERE del_status='Live' AND bestoro='$service_type' AND active_status='Active'")->row();
            if(isset($plugin) && $plugin){
                $status = true;
            }
        }
    }
    return $status;
}
/**
 * get Main Menu
 * @access
 * @return boolean
 * @param no
 */
function isServiceAccessOnlyLogin($service_type='') {
    $CI = & get_instance();
    $company = getMainCompany();
    $status = false;
    $service_type = str_rot13($service_type);
    if($service_type && $service_type =="fTzfWnSWR" && str_rot13($company->language_manifesto) =="fTzfWnSWIR" &&  file_exists(APPPATH.'controllers/Service.php')){
        $plugin = $result = $CI->db->query("SELECT * FROM tbl_plugins WHERE del_status='Live' AND bestoro='$service_type' AND active_status='Active'")->row();
        if(isset($plugin) && $plugin){
            $status = true;
        }
    }
    return $status;
}

/**
 * get Main Menu
 * @access
 * @return object
 * @param no
 */
function getLastSaleId() {
    $CI = & get_instance();
    $outlet_id = $CI->session->userdata('outlet_id');
    $CI->db->select('*');
    $CI->db->from('tbl_sales');
    $CI->db->where('outlet_id', $outlet_id);
    $CI->db->where('del_status', "Live");
    $CI->db->order_by('id desc');
    $last_row =   $CI->db->get()->row();
    return $last_row?$last_row->id:'';
}
/**
 * get Main Menu
 * @access
 * @return object
 * @param no
 */
function returnSaleNo($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('tbl_sales');
    $CI->db->where('id', $id);
    $last_row =   $CI->db->get()->row();
    return $last_row?$last_row->sale_no:'';
}
/**
 * get Main Menu
 * @access
 * @return object
 * @param no
 */
function returnSessionLng() {
    $CI = & get_instance();
    $language = $CI->session->userdata('language');
    return isset($language) && $language?$language:'';
}
/**
 * get All By Custom Id
 * @access public
 * @return boolean
 * @param int
 * @param string
 * @param string
 * @param string
 */
function getAllByCustomId($id,$filed,$tbl,$order=''){
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from($tbl);
    $CI->db->where($filed,$id);
    if($order!=''){
        $CI->db->order_by('id',$order);
    }
    $CI->db->where("del_status", 'Live');
    $result = $CI->db->get();

    if($result != false){
        return $result->result();
    }else{
        return false;
    }
}
/**
 * get Language Manifesto
 * @access public
 * @return array
 * @param no
 */
function getLanguageManifesto(){
    $CI = & get_instance();
    $language_manifesto = $CI->session->userdata('language_manifesto');
    $outlet_id = $CI->session->userdata('outlet_id');
    if(str_rot13($language_manifesto)=="eriutoeri"){
        return [$language_manifesto,"Outlet/outlets"];
    }else if(str_rot13($language_manifesto)=="fgjgldkfg"){
        return [$language_manifesto,"Outlet/addEditOutlet/".$outlet_id];
    }
}

//SELECT * from sma_sales  desc limit 1
function paymentSetting() {
    $CI = & get_instance();
    $company_id = 1;
    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("id", $company_id);
    $result = $CI->db->get()->row();
    return json_decode($result->payment_settings);

}

//SELECT * from sma_sales  desc limit 1
function getCustomURL() {
    $CI = & get_instance();
    $company_id = 1;
    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("id", $company_id);
    $result = $CI->db->get()->row();
    return $result;

}
/**
 * get Company Info
 * @access public
 * @return object
 * @param no
 */
function getCompanyInfo() {
    $CI = & get_instance();
    $company_id = $CI->session->userdata('company_id');
    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("id", $company_id);
    return $CI->db->get()->row();
}
/**
 * get Company Info
 * @access public
 * @return object
 * @param no
 */
function getCompanyInfoById($company_id='') {
    $CI = & get_instance();
    if($company_id==''){
        $company_id = $CI->session->userdata('company_id');
    }
    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("id", $company_id);
    return $CI->db->get()->row();
}
/**
 * get return percentage value
 * @access public
 * @return object
 * @param no
 */
function getPercentageValue($percentage,$total) {
    $tmp = explode('%',$percentage);
    if(isset($tmp[1])){
        $total_amount  = ($tmp[0]*$total)/100;
        return $total_amount;
    }else{
        return isset($tmp[0]) && $tmp[0]?$tmp[0]:0;
    }
}
function getPlanTextOrP($percentage) {
    $tmp = explode('%',$percentage);
    if(isset($tmp[1])){
        $total_amount  = $percentage;
        return $total_amount;
    }else{
        return isset($tmp[0]) && $tmp[0]?getAmt($tmp[0]):getAmt(0);
    }
}
/**
 * get Company Info
 * @access public
 * @return object
 * @param no
 */
function getPricingPlan() {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from("tbl_pricing_plans");
    $CI->db->order_by('id', 'ASC');
    $CI->db->where("del_status", 'Live');
    return $CI->db->get()->result();
}

/**
 *  get dynamically domain name and return
 * @return string
 * @param string
 */
function getDomain($url){
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
        $tmp = explode('.',$regs['domain']);
        return ucfirst($tmp[0]);
    }
    return FALSE;
}
/**
 * send_email
 * @access public
 * @return boolean
 * @param string
 * @param string
 * @param string
 * @param string
 * @param string
 */
function sendEmailOnly($txt,$to_email,$attached='',$sender_email='',$subject=''){
    echo "################################################";

    $company = getMainCompany();
    $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
    $domain_name = ''.getDomain(base_url()).'';
    $username = $smtEmail->user_name;
    $password = $smtEmail->password;
    if($smtEmail->enable_status==1){
        $CI = &get_instance();
        // Load PHPMailer library
        $CI->load->library('phpmailer_lib');

        // PHPMailer object
        $mail = $CI->phpmailer_lib->load();

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = $smtEmail->host_name;
        $mail->SMTPAuth = true;
        $mail->Username = $smtEmail->user_name;
        $mail->Password = $password;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = $smtEmail->port_address;

        $mail->setFrom($username, $domain_name);
        $mail->addReplyTo($username, $domain_name);
        // Add a recipient
        $mail->addAddress($to_email);
        // Email subject
        $mail->Subject = $subject;
        // Set email format to HTML
        $mail->isHTML(true);
        // Email body content
        $mail->Body = $txt;
        if($attached!=''){
            $mail->AddAttachment($attached);
            //$mail->AddAttachment('pdf_files/', $attached);
        }
        // Send email
        if(!$mail->send()){
            return false;
        }else{
            return true;
        }
    }
    return true;
}
function sendEmailOnlyOld($txt,$to_email,$attached='',$sender_email='',$subject=''){
    $company = getMainCompany();
    $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
    $domain_name = ''.getDomain(base_url()).'';
    if(isset($smtEmail->enable_status) && $smtEmail->enable_status==1){
        $CI = &get_instance();
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => ''.$smtEmail->host_name.'',
            'smtp_port' => ''.$smtEmail->port_address.'',
            'smtp_user' => ''.$smtEmail->user_name.'',
            'smtp_pass' => ''.$smtEmail->password.'',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );
        $CI->load->library('email', $config);
        $CI->email->set_newline("\r\n");
        $CI->email->set_mailtype("html");
        $CI->email->from($domain_name, $domain_name);
        $CI->email->to($to_email, $sender_email);
        $CI->email->subject($subject);
        $CI->email->message($txt);
        //send mail
        if($attached!=''){
            $CI->email->attach($attached);
        }
        $CI->email->send();
    }

    return true;
}
/**
 * get Company Info
 * @access public
 * @return object
 * @param no
 */
function getMainCompany() {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("id", 1);
    return $CI->db->get()->row();
}

if(! function_exists('product_name')) {
    function product_name($name, $size = 0) {
        if (!$size) { $size = 42; }
        return character_limiter($name, ($size-5));
    }
}

if(! function_exists('drawLine')) {
    function drawLine($size) {
        $line = '';
        for ($i = 1; $i <= $size; $i++) {
            $line .= '-';
        }
        return $line."\n";
    }
}

if(! function_exists('printLine')) {
    function printLine($str, $size, $sep = ":", $space = NULL) {
        $size = $space ? $space : $size;
        $lenght = strlen($str);
        list($first, $second) = explode(":", $str, 2);
        $line = $first . ($sep == ":" ? $sep : '');
        for ($i = 1; $i < ($size - $lenght); $i++) {
            $line .= ' ';
        }
        $line .= ($sep != ":" ? $sep : '') . $second;
        return $line;
    }
}

if(! function_exists('printText')) {
    function printText($text, $size) {
        $line = wordwrap($text, $size, "\\n");
        return $line;
    }
}

if(! function_exists('taxLine')) {
    function taxLine($name, $code, $qty, $amt, $tax, $size) {
        return printLine(printLine(printLine(printLine($name . ':' . $code, 16, '') . ':' . $qty, 22, '') . ':' . $amt, 33, '') . ':' . $tax, $size, '');
    }
}

if ( ! function_exists('character_limiter')) {
    function character_limiter($str, $n = 500, $end_char = '&#8230;') {
        if (mb_strlen($str) < $n) {
            return $str;
        }
        $str = preg_replace('/ {2,}/', ' ', str_replace(array("\r", "\n", "\t", "\x0B", "\x0C"), ' ', $str));
        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val.' ';
            if (mb_strlen($out) >= $n) {
                $out = trim($out);
                return (mb_strlen($out) === mb_strlen($str)) ? $out : $out.$end_char;
            }
        }
    }
}

if ( ! function_exists('word_wrap')) {
    function word_wrap($str, $charlim = 76) {
        is_numeric($charlim) OR $charlim = 76;
        $str = preg_replace('| +|', ' ', $str);
        if (strpos($str, "\r") !== FALSE) {
            $str = str_replace(array("\r\n", "\r"), "\n", $str);
        }
        $unwrap = array();
        if (preg_match_all('|\{unwrap\}(.+?)\{/unwrap\}|s', $str, $matches)) {
            for ($i = 0, $c = count($matches[0]); $i < $c; $i++)
            {
                $unwrap[] = $matches[1][$i];
                $str = str_replace($matches[0][$i], '{{unwrapped'.$i.'}}', $str);
            }
        }

        $str = wordwrap($str, $charlim, "\n", FALSE);
        $output = '';
        foreach (explode("\n", $str) as $line) {
            if (mb_strlen($line) <= $charlim) {
                $output .= $line."\n";
                continue;
            }
            $temp = '';
            while (mb_strlen($line) > $charlim) {
                if (preg_match('!\[url.+\]|://|www\.!', $line)) {
                    break;
                }
                $temp .= mb_substr($line, 0, $charlim - 1);
                $line = mb_substr($line, $charlim - 1);
            }
            if ($temp !== '') {
                $output .= $temp."\n".$line."\n";
            } else {
                $output .= $line."\n";
            }
        }

        if (count($unwrap) > 0) {
            foreach ($unwrap as $key => $val) {
                $output = str_replace('{{unwrapped'.$key.'}}', $val, $output);
            }
        }

        return $output;
    }
}


/**
 * return printer info
 * @access public
 * @return object
 * @param int
 */
function getPrinterInfo($id) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from("tbl_printers");
    $CI->db->where("id", $id);
    $CI->db->order_by("id", "DESC");
    return $CI->db->get()->row();
}
/**
 * get All Outlet By Assign User
 * @access public
 * @return object
 * @param no
 */
function getAllOutlestByAssign() {
    $CI = & get_instance();
    $role = $CI->session->userdata('role');
    $company_id = $CI->session->userdata('company_id');
    $outlets = $CI->session->userdata('session_outlets');
    if($company_id==1){
        $result = $CI->db->query("SELECT * FROM tbl_outlets WHERE del_status='Live'")->result();
    }else{
        if($role=="Admin"){
            $result = $CI->db->query("SELECT * FROM tbl_outlets WHERE FIND_IN_SET(`company_id`, '$company_id') AND del_status='Live'")->result();
        }else{
            $result = $CI->db->query("SELECT * FROM tbl_outlets WHERE FIND_IN_SET(`id`, '$outlets') AND del_status='Live'")->result();
        }

    }
    return $result;
}
/**
 * get Total Lanuage Manifesto
 * @access public
 * @return array
 * @param no
 */
function getTotalLanuageManifesto(){
    $CI = & get_instance();
    $company_id = $CI->session->userdata('company_id');
    $company = $CI->db->query("SELECT * FROM tbl_companies WHERE del_status='Live'")->row();
    $outlet_info1 = $CI->db->query("SELECT * FROM tbl_outlets WHERE del_status='Live'AND company_id='$company_id'")->result();

    if(str_rot13($company->language_manifesto)=="eriutoeri"){
        $return_menu = "Outlet/outlets";
    }else if(str_rot13($company->language_manifesto)=="fgjgldkfg"){
        $outlet_info = $CI->db->query("SELECT * FROM tbl_outlets WHERE del_status='Live'")->row();
        $return_menu = "Outlet/addEditOutlet/".(isset($outlet_info->id) && $outlet_info->id?$outlet_info->id:'');
    }
    return [$company->language_manifesto,sizeof($outlet_info1),$return_menu,$company->outlet_qty];
}
/**
 * get Outlets
 * @access public
 * @return string
 * @param string
 */
function getOutlets($outlets){
    $CI = & get_instance();
    $outlet_info1 = $CI->db->query("SELECT * FROM tbl_outlets WHERE FIND_IN_SET(`id`, '$outlets') AND del_status='Live'")->result();
    $outlet_names = '';
    if($outlet_info1){
        foreach ($outlet_info1 as $key=>$name){
            $outlet_names.= $name->outlet_name;
            if($key < (sizeof($outlet_info1) -1)){
                $outlet_names.=", ";
            }
        }
    }

    return $outlet_names;
}
/**
 * get Outlet Name By Id
 * @access public
 * @return string
 * @param int
 */
function getOutletNameById($outlet_id){
    $CI = & get_instance();
    $outlet_info1 = $CI->db->query("SELECT * FROM tbl_outlets WHERE id='$outlet_id' AND del_status='Live'")->row();
    if($outlet_info1){
        return $outlet_info1->outlet_name;
    }else{
        return "";
    }
}
/**
 * total Users
 * @access public
 * @return int
 * @param int
 */
function totalUsers($company_id) {
    $CI = & get_instance();
    $total_users = $CI->db->query("SELECT * FROM tbl_users where `company_id`='$company_id'")->num_rows();
    return $total_users;
}
/**
 * get White Label for setting info
 * @access public
 * @return object
 * @param no
 */
function getWhiteLabel() {
    $company_info = getMainCompany();
    $getWhiteLabel = json_decode(isset($company_info->white_label) && $company_info->white_label?$company_info->white_label:'');
    return $getWhiteLabel;
}
/**
 * return food menu id for outlet
 * @access public
 * @return string
 * @param no
 */
function getFMIds($outlet_id) {
    $CI = & get_instance();
    $getCompanyInfo = getCompanyInfo();
    $company_id = $CI->session->userdata('company_id');
    $language_manifesto = $getCompanyInfo->language_manifesto;
    if(str_rot13($language_manifesto)=="fgjgldkfg"){
        $CI->db->select("id");
        $CI->db->from("tbl_food_menus");
        $CI->db->where("company_id",$company_id);
        $CI->db->where("del_status","Live");
        $result = $CI->db->get()->result();
        $main_arr = '';
        if(isset($result) && $result){
            foreach ($result as $keys=>$value){
                $main_arr.=$value->id;
                if($keys < (sizeof($result)) -1){
                    $main_arr.=",";
                }
            }
        }
        return $main_arr;
    }else{
        $CI->db->select("*");
        $CI->db->from("tbl_outlets");
        $CI->db->where("id",$outlet_id);
        $CI->db->where("del_status","Live");
        $result = $CI->db->get()->row();
        $food_menus =  $result->food_menus;

        if(isset($food_menus)&& $food_menus){
            return $food_menus;
        }else{
            $CI->db->select("id");
            $CI->db->from("tbl_food_menus");
            $CI->db->where("company_id",$company_id);
            $CI->db->where("del_status","Live");
            $result = $CI->db->get()->result();
            $main_arr = '';
            if(isset($result) && $result){
                foreach ($result as $keys=>$value){
                    $main_arr.=$value->id;
                    if($keys < (sizeof($result)) -1){
                        $main_arr.=",";
                    }
                }
            }
            return $main_arr;
        }
    }

}
/**
 * return food menu id for outlet
 * @access public
 * @return string
 * @param no
 */
function getFMIdsOutlet($outlet_id) {
    $CI = & get_instance();
    $company_id = $CI->session->userdata('company_id');
    $language_manifesto = $CI->session->userdata('language_manifesto');
    if(str_rot13($language_manifesto)=="fgjgldkfg"){
        $CI->db->select("id");
        $CI->db->from("tbl_food_menus");
        $CI->db->where("company_id",$company_id);
        $CI->db->where("del_status","Live");
        $result = $CI->db->get()->result();
        $main_arr = '';
        if(isset($result) && $result){
            foreach ($result as $keys=>$value){
                $main_arr.=$value->id;
                if($keys < (sizeof($result)) -1){
                    $main_arr.=",";
                }
            }
        }
        return $main_arr;
    }else{
        $CI->db->select("*");
        $CI->db->from("tbl_outlets");
        $CI->db->where("id",$outlet_id);
        $CI->db->where("del_status","Live");
        $result = $CI->db->get()->row();
        $food_menus =  $result->food_menus;

        if(isset($food_menus)&& $food_menus){
            return $food_menus;
        }else{
            $CI->db->select("id");
            $CI->db->from("tbl_food_menus");
            $CI->db->where("company_id",$company_id);
            $CI->db->where("del_status","Live");
            $result = $CI->db->get()->result();
            $main_arr = '';
            if(isset($result) && $result){
                foreach ($result as $keys=>$value){
                    $main_arr.=$value->id;
                    if($keys < (sizeof($result)) -1){
                        $main_arr.=",";
                    }
                }
            }
            return $main_arr;
        }
    }

}
/**
 * is LMni
 * @access public
 * @return boolean
 * @param no
 */
function isLMni() {
    $data_c = getLanguageManifesto();
    if(str_rot13($data_c[0])=="eriutoeri"){
        return true;
    }else{
        return false;
    }
}
/**
 * get FM Id Dashboard
 * @access public
 * @return string
 * @param int
 */
function getFMIdDashboard($outlet_id) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from("tbl_outlets");
    $CI->db->where("id",$outlet_id);
    $CI->db->where("del_status","Live");
    $result = $CI->db->get()->row();
    return $result->food_menus;
}
/**
 * user Name
 * @access public
 * @return string
 * @param int
 */
function userName($user_id) {
    $CI = & get_instance();
    $user_information = $CI->db->query("SELECT * FROM tbl_users where `id`='$user_id'")->row();
    return isset($user_information->full_name) && $user_information->full_name?$user_information->full_name:'';
}
function getExpenseItemName($user_id) {
    $CI = & get_instance();
    $user_information = $CI->db->query("SELECT * FROM tbl_expense_items where `id`='$user_id'")->row();
    return isset($user_information->name) && $user_information->name?$user_information->name:'';
}
/**
 * get Customer Name
 * @access public
 * @return string
 * @param int
 */
function getCustomerName($customer_id) {
    $CI = & get_instance();
    $information = $CI->db->query("SELECT * FROM tbl_customers where `id`='$customer_id'")->row();
    return isset($information->name) && $information->name?$information->name:'';
}
/**
 * get Order Type
 * @access public
 * @return string
 * @param int
 */
function getOrderType($order_type_id) {
    if ($order_type_id == 1) {
        return "Dine In";
    }elseif ($order_type_id == 2) {
        return "Take Away";
    }elseif ($order_type_id == 3) {
        return "Delivery";
    }
}
/**
 * get Table Name
 * @access public
 * @return string
 * @param int
 */
function getTableName($table_id) {
    $CI = & get_instance();
    $information = $CI->db->query("SELECT * FROM tbl_tables where `id`='$table_id'")->row();
    return $information->name;
}
/**
 * get Consumption ID
 * @access public
 * @return string
 * @param int
 */
function getConsumptionID($id) {
    $CI = & get_instance();
    $selectRow = $CI->db->query("SELECT * FROM tbl_sale_consumptions where `sale_id`='$id'")->row();
    if (!empty($selectRow)) {
        return $selectRow->id;
    } else {
        return '0';
    }
}
/**
 * vat Name
 * @access public
 * @return string
 * @param int
 */
function vatName($id){
    $CI = & get_instance();
    $expense_item_information = $CI->db->query("SELECT * FROM tbl_vats where `id`='$id'")->row();

    return $expense_item_information->name;
}
/**
 * expense Item Name
 * @access public
 * @return string
 * @param int
 */
function expenseItemName($id) {
    $CI = & get_instance();
    $expense_item_information = $CI->db->query("SELECT * FROM tbl_expense_items where `id`='$id'")->row();
    return $expense_item_information->name;
}
/**
 * employee Name
 * @access public
 * @return string
 * @param int
 */
function employeeName($id) {
    $CI = & get_instance();
    $employee_information = $CI->db->query("SELECT * FROM tbl_users where `id`='$id'")->row();
    if (!empty($employee_information)) {
        return $employee_information->full_name;
    }else{
        return "N/A";
    }
}
/**
 * category Name
 * @access public
 * @return string
 * @param int
 */
function categoryName($category_id) {
    $CI = & get_instance();
    $category_information = $CI->db->query("SELECT * FROM tbl_ingredient_categories where `id`='$category_id'")->row();
    return $category_information->category_name;
}
/**
 * food Menu Category Name
 * @access public
 * @return string
 * @param int
 */
function foodMenucategoryName($category_id) {
    $CI = & get_instance();
    $category_information = $CI->db->query("SELECT * FROM tbl_food_menu_categories where `id`='$category_id'")->row();
    return $category_information->category_name;
}
/**
 * food Menu Name
 * @access public
 * @return string
 * @param int
 */
function foodMenuName($id) {
    $CI = & get_instance();
    $food_information = $CI->db->query("SELECT * FROM tbl_food_menus where `id`='$id'")->row();
    return $food_information->name;
}
/**
 * food Menu Name Code
 * @access public
 * @return string
 * @param int
 */
function foodMenuNameCode($id) {
    $CI = & get_instance();
    $food_information = $CI->db->query("SELECT * FROM tbl_food_menus where `id`='$id'")->row();
    return "(" . $food_information->code . ")";
}
/**
 * unitName
 * @access public
 * @return object
 * @param int
 */
function unitName($unit_id) {
    $CI = & get_instance();
    $unit_information = $CI->db->query("SELECT * FROM tbl_units where `id`='$unit_id'")->row();
    if (!empty($unit_information)) {
        return $unit_information->unit_name;
    } else {
        return '';
    }
}
/**
 * totalIngredients
 * @access public
 * @return int
 * @param int
 */
function totalIngredients($food_menu_id) {
    $CI = & get_instance();
    $total_count = $CI->db->query("SELECT * FROM tbl_food_menus_ingredients where `food_menu_id`='$food_menu_id'")->num_rows();
    return $total_count;
}
/**
 * food Menu Ingredients
 * @access public
 * @return object
 * @param int
 */
function foodMenuIngredients($food_menu_id) {
    $CI = & get_instance();
    $food_menu_ingredients = $CI->db->query("SELECT * FROM tbl_food_menus_ingredients where `food_menu_id`='$food_menu_id'")->result();
    return $food_menu_ingredients;
}
/**
 * modifier Ingredients
 * @access public
 * @return object
 * @param int
 */
function modifierIngredients($modifier_id) {
    $CI = & get_instance();
    $food_menu_ingredients = $CI->db->query("SELECT * FROM tbl_modifier_ingredients where `modifier_id`='$modifier_id'")->result();
    return $food_menu_ingredients;
}
/**
 * get Payment Name
 * @access public
 * @return string
 * @param int
 */
function getPaymentName($id) {
    $CI = & get_instance();
    $getPaymentName = $CI->db->query("SELECT * FROM tbl_payment_methods where `id`='$id'")->row();
    if(isset($getPaymentName->name) && $getPaymentName->name){
        return $getPaymentName->name;
    }else{
        return "";
    }

}
/**
 * get Alert Count
 * @access public
 * @return string
 * @param int
 */
function getAlertCount() {
    $CI = & get_instance();
    $company_id = $CI->session->userdata('company_id');
    $outlet_id = $CI->session->userdata('outlet_id');
    $where = '';
    $getFMIds = getFMIds($outlet_id);
    $result = $CI->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
    (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
    (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
    (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
    (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
    (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
            (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
            (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

    FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.id")->result();
    $alertCount = 0;
    foreach ($result as $value) {
        $totalStock = $value->total_purchase - $value->total_consumption - $value->total_modifiers_consumption - $value->total_waste + $value->total_consumption_plus  + $value->total_transfer_plus  - $value->total_transfer_minus  +  $value->total_transfer_plus_2  -  $value->total_transfer_minus_2 - $value->total_consumption_minus + $value->total_transfer_plus  - $value->total_transfer_minus  +  $value->total_transfer_plus_2  -  $value->total_transfer_minus_2;
        if ((int)$totalStock <= (int)$value->alert_quantity) {
            if($value->id):
                $alertCount++;
            endif;
        }
    }
    return $alertCount;
}
/**
 * get Alert Count
 * @access public
 * @return string
 * @param int
 */
function getCurrentStockById($getFMIds) {
    $CI = & get_instance();
    $company_id = $CI->session->userdata('company_id');
    $outlet_id = $CI->session->userdata('outlet_id');
    $where = '';
    $result = $this->db->query("SELECT ingr_tbl.*,i.food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
        (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
        (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
        (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

        FROM tbl_food_menus_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.ingredient_id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id WHERE FIND_IN_SET(`food_menu_id`, '$getFMIds') AND i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.ingredient_id")->row();
    return $result;

    $alertCount = 0;
    $totalStock = $result->total_purchase - $result->total_consumption - $result->total_modifiers_consumption - $result->total_waste + $result->total_consumption_plus  + $result->total_transfer_plus  - $result->total_transfer_minus - $result->total_consumption_minus + $result->total_transfer_plus  - $result->total_transfer_minus;
    return $totalStock;
}
/**
 * collect GST
 * @access public
 * @return string
 * @param int
 */
function collectGST(){
    $CI = & get_instance();
    $outlet_id = $CI->session->userdata('outlet_id');
    if($outlet_id){
        $outlet_info = $CI->db->query("SELECT * FROM tbl_companies where `id`='$outlet_id'")->row();
        return isset($outlet_info->tax_is_gst) && $outlet_info->tax_is_gst?$outlet_info->tax_is_gst:'No';
    }else{
        return "No";
    }
}/**
 * total tax
 * @access public
 * @return string
 * @param int
 */
function getTaxAmount($sale_price,$tax){
    $CI = & get_instance();
    $decode_tax = json_decode($tax);
    $total_return_amount = 0;
    foreach ($decode_tax as $key=>$value){
        if(isset($decode_tax[$key]->tax_field_percentage) && $decode_tax[$key]->tax_field_percentage && $decode_tax[$key]->tax_field_percentage!="0.00"){
            $total_return_amount+=($sale_price*$decode_tax[$key]->tax_field_percentage)/100;
        }

    }
return $total_return_amount;

}
/**
 * get Ingredient Name By Id
 * @access public
 * @return string
 * @param int
 */
function getIngredientNameById($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_ingredients where `id`='$id'")->row();
    if (!empty($ig_information)) {
        return $ig_information->name;
    } else {
        return '';
    }
}
/**
 * get Modifier Name By Id
 * @access public
 * @return string
 * @param int
 */
function getModifierNameById($id) {
    $CI = & get_instance();
    $m_information = $CI->db->query("SELECT * FROM tbl_modifier where `id`='$id'")->row();
    if (!empty($m_information)) {
        return $m_information->name;
    } else {
        return '';
    }
}
function getFoodMenuNameById($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_food_menus where `id`='$id'")->row();
    if (!empty($ig_information)) {
        return $ig_information->name;
    } else {
        return '';
    }
}
/**
 * get Ingredient Code By Id
 * @access public
 * @return string
 * @param int
 */
function getIngredientCodeById($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_ingredients where `id`='$id'")->row();
    return $ig_information->code;
}
/**
 * get Ingredient Code By Id
 * @access public
 * @return string
 * @param int
 */
function getFoodMenuCodeById($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_food_menus where `id`='$id'")->row();
    return $ig_information->code;
}
function getFoodMenuCateCodeById($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_food_menu_categories where `id`='$id'")->row();
    return $ig_information->category_name;
}
/**
 * get Supplier Name By Id
 * @access public
 * @return string
 * @param int
 */
function getSupplierNameById($id) {
    $CI = & get_instance();
    $supplier_information = $CI->db->query("SELECT * FROM tbl_suppliers where `id`='$id'")->row();
    return $supplier_information->name;
}
/**
 * get Unit Id By Ig Id
 * @access public
 * @return string
 * @param int
 */
function getUnitIdByIgId($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_ingredients where `id`='$id'")->row();
    if (!empty($ig_information)) {
        return $ig_information->unit_id;
    } else {
        return '';
    }
}
/**
 * get Last Purchase Amount
 * @access public
 * @return float
 * @param int
 */
function getLastPurchaseAmount($id) {
    $CI = & get_instance();
    $ings = $CI->db->query("SELECT * FROM tbl_ingredients where `id`='$id'")->row();
    $purchase_ingredients = $CI->db->query("SELECT * FROM tbl_purchase_ingredients where `ingredient_id`='$id' ORDER BY id DESC")->row();
    if (!empty($purchase_ingredients)) {
        $returnPrice = $purchase_ingredients->unit_price;
    } else {
        $returnPrice = $ings->purchase_price;
    }
    return $returnPrice;
}
/**
 * get Purchase Ingredients
 * @access public
 * @return string
 * @param int
 */
function getPurchaseIngredients($id) {
    $CI = & get_instance();
    $purchase_ingredients = $CI->db->query("SELECT * FROM tbl_purchase_ingredients where `purchase_id`='$id'")->result();
    if (!empty($purchase_ingredients)) {
        $pur_ingr_all = "";
        $key = 1;
        $pur_ingr_all .= "<b>SN-Ingredient-Qty/Amount-Unit Price-Total</b><br>";
        foreach ($purchase_ingredients as $value) {
            $pur_ingr_all .= $key ."-". getIngredientNameById($value->ingredient_id)."-".$value->quantity_amount ."-". $value->unit_price ."-". $value->total."<br>";
            $key++;
        }
        return $pur_ingr_all;
    }else{
        return "Not found!";
    }
}
/**
 * get Last Purchase Price
 * @access public
 * @return float
 * @param int
 */
function getLastPurchasePrice($ingredient_id) {
    $CI = & get_instance();
    $purchase_info = $CI->db->query("SELECT *
        FROM tbl_purchase_ingredients
        WHERE ingredient_id = $ingredient_id
        ORDER BY id DESC
        LIMIT 1")->row();
    if (!empty($purchase_info)) {
        return $purchase_info->unit_price;
    } else {
        $ig_information = $CI->db->query("SELECT * FROM tbl_ingredients where `id`='$ingredient_id'")->row();
        return $ig_information->purchase_price;
    }
}
/**
 * ingredient Count
 * @access public
 * @return int
 * @param int
 */
function ingredientCount($id) {
    $CI = & get_instance();
    $ingredient_count = $CI->db->query("SELECT COUNT(*) AS ingredient_count
        FROM tbl_waste_ingredients
        WHERE waste_id = $id")->row();
    return $ingredient_count->ingredient_count;
}
/**
 * ingredient Count Consumption
 * @access public
 * @return int
 * @param int
 */
function ingredientCountConsumption($id) {
    $CI = & get_instance();
    $ingredient_count = $CI->db->query("SELECT COUNT(*) AS ingredient_count
        FROM tbl_inventory_adjustment_ingredients
        WHERE inventory_adjustment_id = $id")->row();
    return $ingredient_count->ingredient_count;
}
/**
 * company Information
 * @access public
 * @return object
 * @param int
 */
function companyInformation($company_id) {
    $CI = & get_instance();
    $company_info = $CI->db->query("SELECT * FROM tbl_companies where `id`='$company_id'")->row();
    return $company_info;
}
/**
 * find Date
 * @access public
 * @return string
 * @param int
 */
function findDate($date) {
    $format = null;
    if ($date == '') {
        return '';
    } else {
        $format = 'd/m/Y';
    }
    return date($format, strtotime($date));
}
/**
 * alter Date Format return
 * @access public
 * @return string
 * @param int
 */
function alterDateFormat($date) {
    $query1 = mysql_query("SELECT date_format FROM company_info where id='1'");
    $row = mysql_fetch_array($query1);
    $format = null;
    if ($date != "") {
        $dateSlug = explode('/', $date);
        //return $dateSlug[2].'-'.$dateSlug[1].'-'.$dateSlug[0];
        switch ($row['date_format']) {
            case 'dd/mm/yyyy':
                $format = $dateSlug[2] . '-' . $dateSlug[1] . '-' . $dateSlug[0];
                break;
            case 'mm/dd/yyyy':
                $format = $dateSlug[2] . '-' . $dateSlug[0] . '-' . $dateSlug[1];
                break;
            case 'yyyy/mm/dd':
                $format = $dateSlug[0] . '-' . $dateSlug[1] . '-' . $dateSlug[2];
                break;
            default:
                $format = $dateSlug[2] . '-' . $dateSlug[1] . '-' . $dateSlug[0];
                break;
        }
        return $format;
    } else {
        return "0000-00-00 00:00:00";
    }
}
/**
 * get Customer Due Receive
 * @access public
 * @return float
 * @param int
 */
function getCustomerDueReceive($customer_id){
    $CI = & get_instance();
    $information = $CI->db->query("SELECT sum(amount) as amount FROM tbl_customer_due_receives where `customer_id`='$customer_id' and del_status='Live'")->row();
    return $information->amount;
}
/**
 * getSupplierDuePayment
 * @access public
 * @return float
 * @param int
 */
function getSupplierDuePayment($supplier_id){
    $CI = & get_instance();
    $information = $CI->db->query("SELECT sum(amount) as amount FROM tbl_supplier_payments where `supplier_id`='$supplier_id' and del_status='Live'")->row();
    return $information->amount;
}

/**
 * getSupplierDuePayment
 * @access public
 * @return float
 * @param int
 */
function is_mobile_access(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..
    return false;
}

/**
 * get plan name
 * @access public
 * @return string
 * @param int
 */
function getPlanName($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_pricing_plans where `id`='$id'")->row();
    if (!empty($ig_information)) {
        return $ig_information->plan_name;
    } else {
        return '';
    }
}
/**
 * get plan name
 * @access public
 * @return string
 * @param int
 */
function getLastPaymentDate($id) {
    $CI = & get_instance();
    $ig_information = $CI->db->query("SELECT * FROM tbl_payment_histories where `company_id`='$id' AND del_status='Live' ORDER BY id DESC")->row();
    if (!empty($ig_information)) {
        return (date($CI->session->userdata('date_format'), strtotime($ig_information->payment_date)));
    } else {
        return '';
    }
}
/**
 * return amount format as per setting
 * @access public
 * @return string
 * @param int
 */
function getAmt($amount) {
    if(!is_numeric($amount)){
        $amount = 0;
    }
    $getCompanyInfo = getCompanyInfo();
    $currency_position = $getCompanyInfo->currency_position;
    $currency = $getCompanyInfo->currency;
    $precision = $getCompanyInfo->precision;
    $str_amount = '';
    if(isset($currency_position) && $currency_position!="Before Amount"){
        $str_amount = (number_format(isset($amount) && $amount?$amount:0,$precision,'.','')).$currency;
    }else{
        $str_amount = $currency.(number_format(isset($amount) && $amount?$amount:0,$precision,'.',''));
    }
    return $str_amount;
}
/**
 * return amount format as per setting
 * @access public
 * @return string
 * @param int
 */
function getAmtP($amount) {
    if(!is_numeric($amount)){
        $amount = 0;
    }
    $getCompanyInfo = getCompanyInfo();
    $precision = $getCompanyInfo->precision;
    $str_amount = (number_format(isset($amount) && $amount?$amount:0,$precision,'.',''));
    return $str_amount;
}
/**
 * check outlet create permission
 * @access public
 * @return boolean
 * @param int
 */
function checkCreatePermissionOutlet() {
    $CI = & get_instance();
    $company_id = $CI->session->userdata("company_id");
    if($company_id==1){
        return true;
    }
    $data = $CI->db->query("SELECT * FROM tbl_companies where `id`='$company_id' AND del_status='Live'")->row();
    if ($data) {
        $current_outlet = $CI->db->query("SELECT COUNT(id) as total FROM tbl_outlets where `company_id`='$company_id' AND del_status='Live'")->row();
        $total_outlet = 0;
        if(isset($current_outlet) && $current_outlet){
            $total_outlet = $current_outlet->total;
        }
        if($data->number_of_maximum_users!="111"){
            if($data->number_of_maximum_outlets<=$total_outlet){
                return FALSE;
            }else{
                return true;
            }
        }else{
            return true;
        }

    } else {
        return FALSE;
    }
}

/**
 * check user create permission
 * @access public
 * @return boolean
 * @param int
 */
function checkCreatePermissionUser() {
    $CI = & get_instance();
    $company_id = $CI->session->userdata("company_id");
    if($company_id==1){
        return true;
    }
    $data = $CI->db->query("SELECT * FROM tbl_companies where `id`='$company_id' AND del_status='Live'")->row();
    if ($data) {
        $current_user = $CI->db->query("SELECT COUNT(id) as total FROM tbl_users where `company_id`='$company_id' AND del_status='Live'")->row();
        $total_user = 0;
        if(isset($current_user) && $current_user){
            $total_user = $current_user->total;
        }
        if($data->number_of_maximum_users!="111"){
            if($data->number_of_maximum_users<=$total_user){
                return FALSE;
            }else{
                return true;
            }
        }else{
            return true;
        }

    } else {
        return FALSE;
    }
}
/**
 * check invoice create permission
 * @access public
 * @return boolean
 * @param int
 */
function checkCreatePermissionInvoice() {
    $CI = & get_instance();
    $company_id = $CI->session->userdata("company_id");
    if($company_id==1){
        return true;
    }
    $data = $CI->db->query("SELECT * FROM tbl_companies where `id`='$company_id' AND del_status='Live'")->row();
    if ($data) {
        $current_user = $CI->db->query("SELECT COUNT(id) as total FROM tbl_users where `company_id`='$company_id' AND del_status='Live'")->row();
        $total_user = 0;
        if(isset($current_user) && $current_user){
            $total_user = $current_user->total;
        }
        if($data->number_of_maximum_users<=$total_user){
            return FALSE;
        }else{
            return true;
        }
    } else {
        return FALSE;
    }
}
/**
 * return get Ref Attendance
 * @return string
 * @param string
 * @param int
 */
function getRefAttendance($date,$employee_id) {
    $CI = & get_instance();
    $exist_data = $CI->db->query("SELECT * FROM tbl_attendance where `date`='$date' AND `employee_id`='$employee_id' AND `del_status`='Live'")->row();
    if($exist_data){
        return false;
    }else{
        $reference_no = $CI->db->query("SELECT count(id) as reference_no
               FROM tbl_attendance")->row('reference_no');
        $reference_no = str_pad($reference_no + 1, 6, '0', STR_PAD_LEFT);
        return $reference_no;
    }

}

/**
 * return get Ref Attendance
 * @return string
 * @param string
 * @param int
 */
function checkAttendance($date,$employee_id) {
    $CI = & get_instance();
    $exist_data = $CI->db->query("SELECT * FROM tbl_attendance where `date`='$date' AND `employee_id`='$employee_id' AND `del_status`='Live'")->row();
    if($exist_data){
        return $exist_data;
    }else{
        return false;
    }

}
/**
 * check access
 * @access public
 * @return boolean
 * @param int
 */
function isAccess($user_id) {
    $CI = & get_instance();
    $result = $CI->db->query("SELECT tbl_admin_user_menus.controller_name as controller_name
              FROM tbl_user_menu_access
              JOIN tbl_admin_user_menus ON tbl_user_menu_access.menu_id =  tbl_admin_user_menus.id
              WHERE tbl_user_menu_access.user_id=$user_id AND tbl_admin_user_menus.menu_name='POS'
              ")->row();
    if($result){
        return true;
    }else{
        return false;
    }
}
/**
 * get plan name
 * @access public
 * @return string
 * @param int
 */
function getRemainingAccessDay($id) {

    $CI = & get_instance();

    $CI->db->select("*");
    $CI->db->from("tbl_payment_histories");
    $CI->db->where("del_status", 'Live');
    $CI->db->where("company_id", $id);
    $CI->db->order_by("id", 'DESC');
    $due_payment = $CI->db->get()->row();

    $CI->db->select("*");
    $CI->db->from("tbl_companies");
    $CI->db->where("del_status", 'Live');
    $CI->db->where("id", $id);
    $value = $CI->db->get()->row();
    $total_remaining_day = '0 day(s)';


    if(isset($due_payment) && $due_payment){
        if($due_payment->payment_date){
            $access_day = $value->access_day - 1;
            if(!$access_day){
                $access_day = 0;
            }
            $today = date("Y-m-d",strtotime('today'));
            $end_date = date("Y-m-d",strtotime($due_payment->payment_date." +".$access_day."day"));
            $total_remaining_day = getTotalDays($today,$end_date)." day(s)";
        }
    }else{
        $access_day = $value->access_day - 1;
        if(!$access_day){
            $access_day = 0;
        }

        $today = date("Y-m-d",strtotime('today'));
        $end_date = date("Y-m-d",strtotime($value->created_date." +".$access_day."day"));
        $total_remaining_day = getTotalDays($today,$end_date)." day(s)";
    }


    return $total_remaining_day;
}

/**
 * get total days
 * @access public
 * @return int
 * @param int
 */
function getTotalDays($startDate, $endDate){
    $start = strtotime($startDate);
    $end = strtotime($endDate);
    $total_days = ceil(abs($end - $start) / 86400);
    // Once the loop has finished, return the
    // array of days.
    return $total_days;
}