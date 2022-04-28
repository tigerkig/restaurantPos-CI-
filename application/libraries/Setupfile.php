<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setupfile {

  function send($number, $message, $sender, $email_address, $password)
  //function send($number, $message, $sender, $username, $password)
  {
      $ci = & get_instance();
      $data=array("username"=>$email_address,"hash"=>$password,'apikey'=>'aQZlQDLArQU-QJLuWHnsJvLHyRARnjBfOfLYlKtu5m');
      $sender  = $sender;
      $numbers = array($number);
      $ci->load->library('textlocal',$data);

      $response = $ci->textlocal->sendSms($numbers, $message, $sender);
      return $response;
   

    /*try{
     $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
     $paramArray = array(
     'userName' => $username,
     'userPassword' => $password,
     'mobileNumber' => $number,
     'smsText' => $message,
     'type' => "TEXT",
     'maskName' => '',
     'campaignName' => '',
     );
     $value = $soapClient->__call("OneToOne", array($paramArray));
     echo $value->OneToOneResult;
    } catch (Exception $e) {
     echo $e->getMessage();
    }*/
  }
}
