<?php
// error_reporting(E_ALL);
require 'phpmailer/PHPMailerAutoload.php';
function sendEmail($FromEmail,$Subject,$Message,$FromName,$ToEmail) {
    $mail = new PHPMailer();
    $mail->From     = $FromEmail;
    $mail->FromName = $FromName;
    $mail->IsSMTP(); 
    $mail->SMTPAuth = false;     // turn of SMTP authentication
    $mail->Username = "bhaveshmtisariya.dds@gmail.com";  // SMTP username  (Ex: sumithnets@yahoo.com)
    $mail->Password = "bhavesh33"; // SMTP password  (Ex: yahoo email password)
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->SMTPDebug  = 2; // Enables SMTP debug information (for testing, remove this line on production mode)
    // 1 = errors and messages
    // 2 = messages only
    $mail->Sender   =  $FromEmail;// $bounce_email;
    $mail->ConfirmReadingTo  = $FromEmail;
    $mail->AddReplyTo($FromEmail);
    $mail->IsHTML(true); //turn on to send html email
    $mail->Subject = $Subject;
    $mail->Body     =  $Message;
    $mail->AltBody  =  "ALTERNATIVE MESSAGE FOR TEXT WEB BROWSER LIKE SQUIRRELMAIL";
    $mail->AddAddress($ToEmail,$ToEmail);
    if($mail->Send()){
        $mail->ClearAddresses();  
    }
}
$FromEmail	=	'salmandds7@gmail.com'; //sumithnets@yahoo.com
$Subject	=	'EMAIL SUBJECT HERE';  
$Message	=	'EMAIL MESSAGE CONTENTS HERE';  
$FromName	=	'FROM NAME';  // Sumith Harshan
$ToEmail	=	'salmandds7@gmail.com';  //sumith.harshan@gmail.com
$response  = sendEmail($FromEmail,$Subject,$Message,$FromName,$ToEmail);
//print_r($response);
?>