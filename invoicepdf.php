<?php
require_once('function.php');
require_once __DIR__ . '/mpdf/autoload.php';


if(isset($_POST['InvoiceData']) && empty($_POST['InvoiceData']) && isset($_POST['CustomerMail']) && empty($_POST['CustomerMail'])){
	echo  json_encode(["error"=>"Something went wrong. Please refresh your page"]);die;
}
$clientname = "";
if(!empty($_POST['clientname'])){
	$clientname = $_POST['clientname'];
}

$mpdf = new \Mpdf\Mpdf();


ob_start();

echo $_POST['InvoiceData'];

$html = ob_get_contents();
ob_end_clean();

$filename = "Invoice_".date('YmdHis').".pdf";
$filelocation = $_SERVER["DOCUMENT_ROOT"].'/crm/assets/pdf/'.$filename;

$mpdf->WriteHTML($html);
$mpdf->Output($filelocation, 'F');

 $other['--USERNAME--'] = 'To '.$clientname.',<br>Thank you for choosing us as your provider. Please find a detailed copy of your bill attached to this email.';
 $headers = '';
 $message="Hi ";
 $sendinveosudd=sendInvoice($_POST['CustomerMail'], "Order Invoice!", "Order.php", $message, $headers, $other,$filelocation);    
 if($sendinveosudd==true)
 {
   unlink($filelocation);
   echo  json_encode(["response"=>"Invoice successfully Send!"]);die;
 }else{
 	echo  json_encode(["error"=>"Something went wrong. Please refresh your page."]);die;
 }
echo  json_encode(["resonse"=>"Message sent"]);die;
?>
