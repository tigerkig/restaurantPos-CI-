<?php
/*
  ###########################################################
  # PRODUCT NAME: 	Door Knock
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Door Soft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is PaymentController Controller
  ###########################################################
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/paypal-php-sdk/paypal/rest-api-sdk-php/sample/bootstrap.php'); // require paypal files


use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Amount;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;

class PaymentController extends CI_Controller
{
    public $_api_context;

    function  __construct()
    {
        parent::__construct();
        $this->load->model('Payment_model');
        $this->load->model('Common_model');
     
        // paypal credentials
        $this->config->load('paypal');
        // Load Stripe library
        $this->load->library('stripe_lib');
    }

	public function payOnline($company_id) {
        $data = array();
        $data['company_id'] = $company_id;
        $data['company'] = $this->Common_model->getDataById($company_id, "tbl_companies");
        $data['main_content'] = $this->load->view('saas/online_payment', $data, TRUE);
        $this->load->view('userHome', $data);
    }
    /**
     * paypal,stripe payment function call
     * @access public
     * @return void
     * @param int
     */
    public function payment()
    {
        $is_front_signup = $this->session->userdata('is_front_signup');
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                redirect('plan');
            }else{
                redirect('Service/companies');
            }
        }
        $check_stripe = isset($_POST['check_stripe']) && $_POST['check_stripe']?$_POST['check_stripe']:'';
        $payment_company_id = isset($_POST['payment_company_id']) && $_POST['payment_company_id']?$_POST['payment_company_id']:'';
        if($check_stripe=="yes"){
            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                $data = array();
                $data['company'] = $this->Common_model->getDataById($payment_company_id, "tbl_companies");
                $this->load->view('saas/stripe_front', $data);
            }else{
                $data = array();
                $data['company'] = $this->Common_model->getDataById($payment_company_id, "tbl_companies");
                $data['main_content'] = $this->load->view('saas/stripe', $data,True);
                $this->load->view('userHome', $data);
            }

        }else{
            // setup PayPal api context
            //get configuration from db
            $config_for_paypal = $this->Payment_model->paymentConfig('paypal');


            $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $config_for_paypal[1], $config_for_paypal[2]
                )
            );
            $data_config_array = $this->config->item('settings');
            $data_config_array['mode'] = $config_for_paypal[0];
            $this->_api_context->setConfig($data_config_array);

// ### Payer
// A resource representing a Payer that funds a payment
// For direct credit card payments, set payment method
// to 'credit_card' and add an array of funding instruments.

            $payer['payment_method'] = 'paypal';

// ### Itemized information
// (Optional) Lets you specify item wise
// information
            //for check last order complete before payment
            $this->session->set_userdata('payment_company_id_p', $payment_company_id);


            $item1["name"] = "".$this->input->post('item_name')."";
            $item1["sku"] = isset($item_number) && $item_number?$item_number:1;  // Similar to `item_number` in Classic API
            $item1["description"] = $this->input->post('item_description');
            $item1["currency"] ="USD";
            $item1["quantity"] =1;
            $item1["price"] = $this->input->post('item_price');
            $itemList = new ItemList();
            $itemList->setItems(array($item1));

// ### Additional payment details
// Use this optional field to set additional
// payment information such as tax, shipping
// charges etc.
            $details['tax'] = 0;
            $details['subtotal'] = $this->input->post('item_price');
// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
            $amount['currency'] = "USD";
            $amount['total'] = $this->input->post('item_price');
            $amount['details'] = $details;
// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it.
            $transaction['description'] ='Payment description';
            $transaction['amount'] = $amount;
            $transaction['invoice_number'] = uniqid();
            $transaction['item_list'] = $itemList;

            // ### Redirect urls
// Set the urls that the buyer must be redirected to after
// payment approval/ cancellation.
            $baseUrl = base_url();
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($baseUrl."paymentStatus")
                ->setCancelUrl($baseUrl."paymentStatus");

// ### Payment
// A Payment Resource; create one using
// the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
    /*print("<pre>");
    print_r($this->_api_context);exit;*/
            try {
                $payment->create($this->_api_context);
            } catch (Exception $ex) {
                // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
                echo "Payment Configuration Error";exit;
            }
            foreach($payment->getLinks() as $link) {
                if($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            if(isset($redirect_url)) {
                /** redirect to paypal **/
                redirect($redirect_url);
            }

            $this->session->set_flashdata('success_msg','Unknown error occurred');
            redirect('/');
        }
    }

    /**
     * payment stripe function
     * @access public
     * @return void
     */
    public function stripePayment(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $is_front_signup = $this->session->userdata('is_front_signup');
            $this->session->unset_userdata('is_front_signup');
            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                redirect('plan');
            }else{
                redirect('Service/companies');
            }
        }
        // If payment form is submitted with token
        if($this->input->post('stripeToken')){
            $payment_company_id = $_POST['payment_company_id'];
            $this->session->set_userdata('payment_company_id_p', $payment_company_id);

            // Retrieve stripe token and user info from the posted form data
            $postData = $this->input->post();
            // Make payment
            $paymentID = $this->paymentStripeData($postData);

            // If payment successful
            if($paymentID){
                $is_front_signup = $this->session->userdata('is_front_signup');
                $this->session->unset_userdata('is_front_signup');
                if(isset($is_front_signup) && $is_front_signup=="Yes"){
                    $this->session->set_flashdata('exception', lang('payment_success'));
                    redirect('authentication');
                }else{
                    $this->session->set_flashdata('exception', lang('payment_success'));
                    redirect('Service/companies');
                }
            }else{
                $is_front_signup = $this->session->userdata('is_front_signup');
                $this->session->unset_userdata('is_front_signup');
                if(isset($is_front_signup) && $is_front_signup=="Yes"){
                    $this->session->set_flashdata('exception_1', lang('payment_fail'));
                    redirect('authentication');
                }else{
                    $this->session->set_flashdata('exception_1', lang('payment_fail'));
                    redirect('Service/companies');
                }

            }
        }

    }

    /**
     * payment stripe data
     * @access public

     */
    public  function paymentStripeData($postData){

        // If post data is not empty
        if(!empty($postData)){
            // Retrieve stripe token and user info from the submitted form data
            $token  = $postData['stripeToken'];
            $email = $postData['email'];
            $price = $postData['payable_amount'];
            $description = $postData['description'];
            // Add customer to stripe
            $customer = $this->stripe_lib->addCustomer($email, $token);

            if($customer){
                // Charge a credit or a debit card
                $charge = $this->stripe_lib->createCharge($customer->id, $description, $price);

                if($charge){
                    // Check whether the charge is successful
                    if($charge['amount_refunded'] == 0 && empty($charge['failure_code']) && $charge['paid'] == 1 && $charge['captured'] == 1){
                        // Transaction details
                        $brand =  $charge['payment_method_details']['card']['brand'];
                        $type = $charge['payment_method_details']['type'];

                        $transactionID = $charge['balance_transaction'];
                        $paidAmount = $charge['amount'];
                        $paidAmount = ($paidAmount/100);
                        $payment_status = $charge['status'];
                        // If the order is successful
                        if($payment_status == 'succeeded'){
                            $payment_company_id_p = $this->session->userdata('payment_company_id_p');
                            //payment history
                            $data = array();
                            $data['payment_type'] = "Stripe";
                            $data['company_id'] = $payment_company_id_p;
                            $data['amount'] = $paidAmount;
                            $data['payment_date'] = date("Y-m-d",strtotime('today'));
                            $data['trans_id'] = $transactionID;
                            $this->Common_model->insertInformation($data, "tbl_payment_histories");

                            //update company table
                            $data = array();
                            $data['del_status'] = "Live";
                            $this->db->where('company_id', $payment_company_id_p);
                            $this->db->update('tbl_users', $data);


                            //update company table
                            $data = array();
                            $data['del_status'] = "Live";
                            $data['payment_clear'] = "Yes";
                            $this->Common_model->updateInformation($data, $payment_company_id_p, "tbl_companies");
                            $this->session->unset_userdata('payment_company_id_p');

                            //send success message for supper admin
                            $company = getMainCompany();
                            $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
                            $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
                            $companies_info = $this->Common_model->getDataById($payment_company_id_p, "tbl_companies");

                            $business = $companies_info->business_name;
                            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
                            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");
                            //send success message for restaurant admin
                            $restaurantAdminUser = $this->Common_model->getRestaurantAdminUser($payment_company_id_p);
                            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.
            For active your account- <a target="_blank" href="'.base_url().'authentication/active_company/'.$companies_info->active_code.'">Active Now</a>';

                            $send_to = $restaurantAdminUser->email_address;
                            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");

                            return $payment_company_id_p;
                        }
                    }
                }
            }
        }else{

        }
        return false;
    }

    /**
     * payment status check after payment action done
     * @access public
     * @return void
     */
    public function paymentStatus()
    {
        $msg = isset($_GET['msg']) && $_GET['msg']?$_GET['msg']:'';
        $payment_company_id = isset($_GET['payment_company_id']) && $_GET['payment_company_id']?$_GET['payment_company_id']:'';
        $is_front_signup = $this->session->userdata('is_front_signup');
        $this->session->unset_userdata('is_front_signup');

        if($msg=="payment_failed"){
            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                $this->session->set_flashdata('exception_1', lang('payment_fail'));
                redirect('authentication');
            }else{
                $this->session->set_flashdata('exception_1', lang('payment_fail'));
                redirect('Service/companies');
            }
        }else if($msg=="payment_success"){
            //update company table
            $data = array();
            $data['del_status'] = "Live";
            $data['payment_clear'] = "Yes";
            $this->Common_model->updateInformation($data, $payment_company_id, "tbl_companies");

            //update company table
            $data = array();
            $data['del_status'] = "Live";
            $this->db->where('company_id', $payment_company_id);
            $this->db->update('tbl_users', $data);
            $this->session->unset_userdata('payment_company_id_p');

            //send success message for supper admin
            $company = getMainCompany();
            $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
            $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
            $companies_info = $this->Common_model->getDataById($payment_company_id, "tbl_companies");

            $business = $companies_info->business_name;
            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");
            //send success message for restaurant admin
            $restaurantAdminUser = $this->Common_model->getRestaurantAdminUser($payment_company_id);
            $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.
            For active your account- <a target="_blank" href="'.base_url().'authentication/active_company/'.$companies_info->active_code.'">Active Now</a>';

            $send_to = $restaurantAdminUser->email_address;
            sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");

            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                $this->session->set_flashdata('exception', lang('payment_success'));
                redirect('authentication');
            }else{
                $this->session->set_flashdata('exception', lang('payment_success'));
                redirect('Service/companies');
            }
        }else{
            // paypal credentials
            $config_for_paypal = $this->Payment_model->paymentConfig('paypal');
            $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    $config_for_paypal[1], $config_for_paypal[2]
                )
            );
            $data_config_array = $this->config->item('settings');
            $data_config_array['mode'] = $config_for_paypal[0];
            $this->_api_context->setConfig($data_config_array);

            /** Get the payment ID before session clear **/
            $payment_id = $this->input->get("paymentId") ;
            $PayerID = $this->input->get("PayerID") ;
            $token = $this->input->get("token") ;
            /** clear the session payment ID **/

            if (empty($PayerID) || empty($token)) {
                if(isset($is_front_signup) && $is_front_signup=="Yes"){
                    $this->session->set_flashdata('exception_1', lang('payment_fail'));
                    redirect('authentication');
                }else{
                    $this->session->set_flashdata('exception_1', lang('payment_fail'));
                    redirect('Service/companies');
                }
            }
            $payment = Payment::get($payment_id,$this->_api_context);
            /** PaymentExecution object includes information necessary **/
            /** to execute a PayPal account payment. **/
            /** The payer_id is added to the request query parameters **/
            /** when the user is redirected from paypal back to your site **/
            $execution = new PaymentExecution();
            $execution->setPayerId($this->input->get('PayerID'));

            /**Execute the payment **/
            $result = $payment->execute($execution,$this->_api_context);

            //  DEBUG RESULT, remove it later **/
            if ($result->getState() == 'approved') {
                $trans = $result->getTransactions();

                $relatedResources = $trans[0]->getRelatedResources();
                $sale = $relatedResources[0]->getSale();
                // sale info //
                $saleId = $sale->getId();
                $payment_company_id_p = $this->session->userdata('payment_company_id_p');
                $Total = $sale->getAmount()->getTotal();
                //payment history
                $data = array();
                $data['payment_type'] = "Paypal";
                $data['company_id'] = $payment_company_id_p;
                $data['amount'] = $Total;
                $data['payment_date'] = date("Y-m-d",strtotime('today'));
                $data['trans_id'] = $saleId;
                $this->Common_model->insertInformation($data, "tbl_payment_histories");

                //update company table
                $data = array();
                $data['del_status'] = "Live";
                $data['payment_clear'] = "Yes";
                $this->Common_model->updateInformation($data, $payment_company_id_p, "tbl_companies");

                //update company table
                $data = array();
                $data['del_status'] = "Live";
                $this->db->where('company_id', $payment_company_id_p);
                $this->db->update('tbl_users', $data);


                //send success message for supper admin
                $company = getMainCompany();
                $smtEmail = isset($company->email_settings) && $company->email_settings?json_decode($company->email_settings):'';
                $send_to = isset($smtEmail->email_send_to) && $smtEmail->email_send_to?$smtEmail->email_send_to:'';
                $companies_info = $this->Common_model->getDataById($payment_company_id_p, "tbl_companies");

                $business = $companies_info->business_name;
                $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.';
                sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");
                //send success message for restaurant admin
                $restaurantAdminUser = $this->Common_model->getRestaurantAdminUser($payment_company_id_p);
                $txt = 'Congratulations, "'.$business.'" restaurant sign-up has been successful.
            For active your account- <a target="_blank" href="'.base_url().'authentication/active_company/'.$companies_info->active_code.'">Active Now</a>';

                $send_to = $restaurantAdminUser->email_address;
                sendEmailOnly($txt,trim($send_to),$attached='',$business,"Restaurant SignUp Success");

                $this->session->unset_userdata('payment_company_id_p');

                if(isset($is_front_signup) && $is_front_signup=="Yes"){
                    $this->session->set_flashdata('exception', lang('payment_success'));
                    redirect('authentication');
                }else{
                    $this->session->set_flashdata('exception', lang('payment_success'));
                    redirect('Service/companies');
                }
            }
            if(isset($is_front_signup) && $is_front_signup=="Yes"){
                $this->session->set_flashdata('exception_1', lang('payment_fail'));
                redirect('authentication');
            }else{
                $this->session->set_flashdata('exception_1', lang('payment_fail'));
                redirect('Service/companies');
            }
        }

    }


    /**
     * success view after payment
     * @access public
     * @return void
     */
    public  function success(){
        redirect('purchase/success');
    }

    /**
     * if payment status cancel then show this view
     * @access public
     * @return void
     */
    public function cancel(){
        redirect('purchase/fail');
    }

    /**
     * load refund form view
     * @access public
     * @return void
     */
    public function load_refund_form(){
        $this->load->view('content/Refund_payment_form');
    }
    /**
     * refund_payment
     * @access public
     * @return float
     */
    public function refund_payment(){
        $refund_amount = $this->input->post('refund_amount');
        $saleId = $this->input->post('sale_id');
        $paymentValue =  (string) round($refund_amount,2); ;

// ### Refund amount
// Includes both the refunded amount (to Payer)
// and refunded fee (to Payee). Use the $amt->details
// field to mention fees refund details.
        $amt = new Amount();
        $amt->setCurrency('USD')
            ->setTotal($paymentValue);

// ### Refund object
        $refundRequest = new RefundRequest();
        $refundRequest->setAmount($amt);

// ###Sale
// A sale transaction.
// Create a Sale object with the
// given sale transaction id.
        $sale = new Sale();
        $sale->setId($saleId);
        try {
            // Refund the sale
            // (See bootstrap.php for more on `ApiContext`)
            $refundedSale = $sale->refundSale($refundRequest, $this->_api_context);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            ResultPrinter::printError("Refund Sale", "Sale", null, $refundRequest, $ex);
            exit(1);
        }

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        ResultPrinter::printResult("Refund Sale", "Sale", $refundedSale->getId(), $refundRequest, $refundedSale);

        return $refundedSale;
    }

    public function ipn_paypal(){
        $checkExisting = $this->Common_model->checkExistingAdmin(isset($_POST['payer_email']) && $_POST['payer_email']?$_POST['payer_email']:'');
        if($checkExisting){
            $payment_status = isset($_POST['payment_status']) && $_POST['payment_status']?$_POST['payment_status']:'';
            if($payment_status=="Completed"){
                $data = array();
                $data['payment_type'] = "Paypal";
                $data['company_id'] = $checkExisting->compnay_id;
                $data['amount'] = isset($_POST['mc_gross']) && $_POST['mc_gross']?$_POST['mc_gross']:'';
                $data['payment_date'] = isset($_POST['payment_date']) && $_POST['payment_date']?date("Y-m-d",strtotime($_POST['payment_date'])):'';
                $data['trans_id'] = isset($_POST['txn_id']) && $_POST['txn_id']?$_POST['txn_id']:'';
                $data['json_details'] = json_encode($_POST);
                $this->Common_model->insertInformation($data, "tbl_payment_histories");
            }
        }
    }

}