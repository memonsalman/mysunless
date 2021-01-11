<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
//require_once '/path/to/vendor/autoload.php';

require_once('global.php');

// require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/sms/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
// DANGER! This is insecure. See http://twil.io/secure

// $sid    = "ACeeaaf473786316cf63fd6ac85f9aadb2";
$sid    = "AC52d8a740391ae2ea7722ab24161b948a";
$token  = "ba280693817ac011d07dcb72a66259be";
$twilio = new Client($sid, $token);

$incoming_phone_number = $twilio->incomingPhoneNumbers->create(["phoneNumber" => "15005550006"]);

echo "<pre>";
print_r($incoming_phone_number);
print($incoming_phone_number->sid);