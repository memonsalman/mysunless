<?php
require_once($_SERVER['DOCUMENT_ROOT']."/function.php");
//   require 'vendor/autoload.php';
//   use net\authorize\api\contract\v1 as AnetAPI;
//   use net\authorize\api\controller as AnetController;
//   date_default_timezone_set('America/Los_Angeles');
//   define("AUTHORIZENET_LOG_FILE", "phplog");
// function createSubscription($intervalLength)
// {
//     /* Create a merchantAuthenticationType object with authentication details
//        retrieved from the constants file */
//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//     $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
//     $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
//     // Set the transaction's refId
//     $refId = 'ref' . time();
//     // Subscription Type Info
//     $subscription = new AnetAPI\ARBSubscriptionType();
//     $subscription->setName("Sample Subscription");
//     $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
//     $interval->setLength($intervalLength);
//     $interval->setUnit("days");
//     $paymentSchedule = new AnetAPI\PaymentScheduleType();
//     $paymentSchedule->setInterval($interval);
//     $paymentSchedule->setStartDate(new DateTime('2020-08-30'));
//     $paymentSchedule->setTotalOccurrences("12");
//     $paymentSchedule->setTrialOccurrences("1");
//     $subscription->setPaymentSchedule($paymentSchedule);
//     $subscription->setAmount(rand(1,99999)/12.0*12);
//     $subscription->setTrialAmount("0.00");
//     $creditCard = new AnetAPI\CreditCardType();
//     $creditCard->setCardNumber("4111111111111111");
//     $creditCard->setExpirationDate("2038-12");
//     $payment = new AnetAPI\PaymentType();
//     $payment->setCreditCard($creditCard);
//     $subscription->setPayment($payment);
//     $order = new AnetAPI\OrderType();
//     $order->setInvoiceNumber("1234354");        
//     $order->setDescription("Description of the subscription"); 
//     $subscription->setOrder($order); 
//     $billTo = new AnetAPI\NameAndAddressType();
//     $billTo->setFirstName("John");
//     $billTo->setLastName("Smith");
//     $subscription->setBillTo($billTo);
//     $request = new AnetAPI\ARBCreateSubscriptionRequest();
//     $request->setmerchantAuthentication($merchantAuthentication);
//     $request->setRefId($refId);
//     $request->setSubscription($subscription);
//     $controller = new AnetController\ARBCreateSubscriptionController($request);
//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
//     if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
//     {
//         echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
//      }
//     else
//     {
//         echo "ERROR :  Invalid response\n";
//         $errorMessages = $response->getMessages()->getMessage();
//         echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
//     }
//     return $response;
//   }
//   if(!defined('DONT_RUN_SAMPLES'))
//     createSubscription(23);
// if ($gateway_status == "testing")
//    {
//        $auth_net_url = "https://test.authorize.net/gateway/transact.dll";
//     } 
//     else
//     if($gateway_status == "live") 
//     {
//         $auth_net_url = "https://secure.authorize.net/gateway/transact.dll";
//     }
//      else
//      {
//         die("Undefined Gateway Status. Unable to process trasactions. Contact the system Administrator.");
//     }
$auth_net_tran_key = "94s37273wDGeAJv6";
$auth_net_login_id = "938uG3QyS";
$authnet_values = array(
    "x_login" => $auth_net_login_id,
    "x_version" => "3.1",
    "x_delim_char" => "|",
    "x_delim_data" => "TRUE",
    "x_duplicate_window" => "30",
    "x_url" => "FALSE",
    "x_type" => "AUTH_CAPTURE",
    "x_method" => "CC",
    "x_tran_key" => $auth_net_tran_key,
    "x_relay_response" => "FALSE",
    "x_card_num" => '4242424242424242',
    "x_exp_date" => '06/2018',
    "x_card_code" => '123',
    "x_description" => 'Hi',
    "x_first_name" => 'Memon',
    "x_last_name" => 'SAlman',
    "x_address" => 'abc',
    "x_city" => 'asdsd',
    "x_state" => 'dafdf',
    "x_zip" => '10545',
    "x_amount" => '110',
    "x_email" => 'sadfas@fasf.in',
    "x_Unit" => '10000',
    "x_invoice_num" => session_id()
);
$line_item_num = 0;
$ch = curl_init("https://test.authorize.net/gateway/transact.dll");
// convert authnet_values to fields in post list
$fields = "";
foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
// parse line item array
if(!empty($line_item_array) && is_array(line_item_array)){
    foreach($line_item_array as $key=>$value) {
        if($key != count($line_item_array)-1) {
            $fields.="x_line_item=".$value."&";
        } else {
            $fields.="x_line_item=".$value;
        }
    }
}
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
$resp = curl_exec($ch);
curl_close ($ch);
$response_array = explode("|", $resp);
print_r($response_array);
die();
//     if($response_array[3]=="(TESTMODE) The card code is invalid.")
//     {
//         echo json_encode(['error'=>'sorry something wrong']);die;
//     }
//     elseif($response_array[3]=="(TESTMODE) This transaction has been approved.")
//     {
//             $username=$_POST["username"];
//              $email=$_POST["email"];
//             $userassword=$_POST["password"];
//             $password=md5($userassword);
//             $PackageType=$_POST["item_name"];
//             $amount=$_POST["amount"];
//             $firstname=$_POST["billing_first"];
//             $lastname=$_POST["billing_last"];
//             $primaryaddress=$_POST["billing_street"];
//             $state=$_POST["billing_state"];
//             $city=$_POST["billing_city"];
//             $zipcode=$_POST["billing_zip"];
//             $db=new db();
//             $insert_data=$db->prepare("INSERT INTO users(username,email,password,PackageType,Price,firstname,lastname,primaryaddress,state,city,zipcode) VALUES(:username, :email, :password, :PackageType, :amount, :firstname, :lastname, :primaryaddress, :state, :city, :zipcode)");
//             $insert_data->bindparam(":username",$username);
//             $insert_data->bindparam(":email",$email);
//             $insert_data->bindparam(":password",$password);
//             $insert_data->bindparam(":PackageType",$PackageType);
//             $insert_data->bindparam(":amount",$amount);
//             $insert_data->bindparam(":firstname",$firstname);
//             $insert_data->bindparam(":lastname",$lastname);
//             $insert_data->bindparam(":primaryaddress",$primaryaddress);
//             $insert_data->bindparam(":city",$city);
//             $insert_data->bindparam(":state",$state);
//             $insert_data->bindparam(":zipcode",$zipcode);
//             $insert_data->execute();
//             $userid = $db->lastInsertId();
//             if($insert_data)
//             {
//                 $other['--USERNAME--'] = $username;
//                 $other['--EMAIL--'] = $email;
//                 $other['--PASSWORD--'] = $userassword;
//                 $headers = '';
//                 $message="Hi ";
//                 sendCMail($email, "Welcome to ".$username."!", "comapny-register.php", $message, $headers, $other);
//                 $_SESSION["UserName"] = $username;
//                 $_SESSION["UserID"] = $userid;
//             $insert_data2=$db->prepare("INSERT INTO subscriber(sname) VALUES(:username)");
//             $insert_data2->bindparam(":username",$username);
//             $sid = $db->lastInsertId();
//             $insert_data2->execute();
//                 $_SESSION["sname"] = $username;
//                 $_SESSION["sid"] = $sid;
//             echo json_encode(['resonse'=>'Registration has been done successfully']);die;
//             }
//             else
//             {
//                 echo json_encode(['error'=>'sorry something wrong']);die;
//             }
//     }
