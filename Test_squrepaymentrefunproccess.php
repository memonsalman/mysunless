<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
// require 'vendor/autoload.php';

require_once($_SERVER['DOCUMENT_ROOT']."/squarepayment/vendor/autoload.php"); 

// Configure OAuth2 access token for authorization: oauth2
SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken('sandbox-sq0atb-A5HLxm-lJ2m8dxE9CY5iZw');

$api_instance = new SquareConnect\Api\TransactionsApi();
$location_id = "CBASEBkh9buds3YXrHSGNo0ItjEgAQ"; // string | The ID of the transaction's associated location.
$transaction_id = "MEJ5IXKNDhQPx2oyHNllJUIVkcy1nDQP7gEimC2ZXlSJd5p6SchQGMhO"; // string | The ID of the transaction to retrieve.
$idempotencyKey = uniqid();
$body = new \SquareConnect\Model\CreateRefundRequest(); // \SquareConnect\Model\CreateRefundRequest | An object containing the fields to POST for the request.  See the corresponding object definition for field details.

try {
    //$result = $api_instance->createRefund($location_id, $transaction_id, $body);

    $result = $api_instance->createRefund($location_id, $transaction_id, array(
  'tender_id' => '3bf82111-0fe1-5007-6079-771e9fa5bd5a',
  'amount_money' => array(
    'amount' => 1,
    'currency' => 'USD'
  ),
  'idempotency_key' => $idempotencyKey,
  'reason' => 'Cancelled order'
));


    echo "<pre>";
    print_r($result);
} catch (Exception $e) 
{
    echo 'Exception when calling TransactionsApi->createRefund: ', $e->getMessage(), PHP_EOL;
}
?>