<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');

///test
if(isset($_GET['test'])){


	$other['--USERNAME--'] = 'abhijeetX';
	$other['--FIRSTNAME--'] = 'abhijeet';
	$other['--LASTNAME--'] = 'Roy';
	$other['--TOKENID--'] = base64_encode(1234);
	$other['--LOGINTIME--'] = '2020-12-04 04:13:24'.' (UTC)';
	
	$headers = '';
	$message="";

	$sendinveosudd= SendVerificationMail('abhijeet.dds@gmail.com', "Alert", "Alert.php", $message, $headers, $other,''); 

	if($sendinveosudd===true)
  {
   echo  json_encode(["response"=>"successfully Send!"]);die;
 }else{
  echo  json_encode(["error"=>$sendinveosudd]);die;
}
	
}
///end test

if(@$_SESSION['usertype']!='Admin'){

	$db = new db();
	$stmt = $db->prepare("SELECT emailstatus FROM `users` where id=:id");
	$stmt->bindParam(':id', $_SESSION['UserID']);
	$stmt->execute();
	$result = $stmt->fetch();

	if($result['emailstatus']==1){
		define("EMIALPERMISSION", 1);
	}else{
		define("EMIALPERMISSION", 0);
	}

}else{
		define("EMIALPERMISSION", 1);
}

define("ISSMTP", 1);
///////////Documention//////////////
// //Create a new PHPMailer instance
// $mail = new PHPMailer;
// $mail->CharSet = 'UTF-8';
// //Tell PHPMailer to use SMTP
// //$mail->isSMTP();
// //Enable SMTP debugging
// // 0 = off (for production use)
// // 1 = client messages
// // 2 = client and server messages
// $mail->SMTPDebug = 0;
// //Ask for HTML-friendly debug output
// $mail->Debugoutput = 'html';
// //Set the hostname of the mail server
// $mail->Host = $email_hostname;
// //Set the SMTP port number - likely to be 25, 465 or 587
// //$mail->Port = 25;
// //Whether to use SMTP authentication
// //$mail->SMTPAuth = true;
// //Username to use for SMTP authentication
// $mail->Username = $email_username;
// //Password to use for SMTP authentication
// $mail->Password = $email_password;
// //Set who the message is to be sent from
// $mail->setFrom($email_hostname, $email_username);
// //Set an alternative reply-to address
// $mail->addReplyTo($email_hostname, $email_username);
// //Set who the message is to be sent to
// $mail->addAddress($to, '');
// $mail->AddBCC("test@mysunless.com", "test");
// //Set the subject line
// $mail->Subject = $subject;
// //Read an HTML message body from an external file, convert referenced images to embedded,
// //convert HTML into a basic plain-text alternative body
// $mail->msgHTML($body);
// //Replace the plain text body with one created manually
// //$mail->AltBody = 'This is a plain-text message body';
// //Attach an image file
// // $mail->AddAttachment($filelocation);
// if (!$mail->send()) {
//     return "Mailer Error: " . $mail->ErrorInfo;
// } else {
//     return "Message sent!";
// }
///////////Documention End//////////////


function convert_smart_quotes($string) 
{ 
	$chr_map = array(
        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
        "\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
        "\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
        "\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
        "\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
        // Regular Unicode     // U+0022 quotation mark (")
        // U+0027 apostrophe     (')
        "\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
        "\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
        "\xE2\x80\x98" => "'", // U+2018 left single quotation mark
        "\xE2\x80\x99" => "'", // U+2019 right single quotation mark
        "\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
        "\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9C" => '"', // U+201C left double quotation mark
        "\xE2\x80\x9D" => '"', // U+201D right double quotation mark
        "\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
        "\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
    );
    $chr = array_keys  ($chr_map); // but: for efficiency you should
    $rpl = array_values($chr_map); // pre-calculate these two arrays
    return $str = str_replace($chr, $rpl, html_entity_decode($string, ENT_QUOTES, "UTF-8"));
}

// Used while login alert/ Registration OTP
function SendVerificationMail($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=""){  


		$oldusertype = @$_SESSION['usertype'];
		$_SESSION['usertype']="Admin";

		require_once( 'phpmailer/PHPMailerAutoload.php');
		require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['SUB'].'/EmailTemplate.php');

		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);

		foreach($other as $k => $v) {
			$body = str_replace($k,$v,$body);
		}
		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}

		$_SESSION['usertype']=$oldusertype;

		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return true;
		}

}

/// Company mail after registration
function sendCMail($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=""){  


		// $oldusertype = $_SESSION['usertype'];
		$_SESSION['usertype']="Admin";

		require_once( 'phpmailer/PHPMailerAutoload.php');
		require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['SUB'].'/EmailTemplate.php');

		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);

		foreach($other as $k => $v) {
			$body = str_replace($k,$v,$body);
		}
		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}

		// $_SESSION['usertype']=$oldusertype;

		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return true;
		}

}
///function for sending mail of packages

function sendSubscriptionPackMail($to, $subject, $template_name, $message, $headers, $other=array()){  

		require_once('phpmailer/PHPMailerAutoload.php');
		require($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		// $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);

		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}
		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		// $mail->AddAttachment($filelocation);           
		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return true;
		}

}
/// Company mail after Reset password
function sendForgetMail($to, $subject, $template_name, $message, $headers, $other=array()){  

		require_once( 'phpmailer/PHPMailerAutoload.php');
		require($_SERVER["DOCUMENT_ROOT"].$GLOBALS['SUB'].'/EmailTemplate.php');

		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);

		foreach($other as $k => $v) {
			$body = str_replace($k,$v,$body);
		}
		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		
		if (!$mail->send() || $email_senderemail!='support@mysunless.com') {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return true;
		}

}

///Subscriber
///Compose mail used for user and customer
function sendsmpleMail($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=''){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);

		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}

		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {

			if($_SESSION['usertype']!='Admin'){
				require_once($_SERVER["DOCUMENT_ROOT"].'/crm/Exec/Exec_SetFullCom.php');
				SetFullCom($to,$body,$subject,'email');
			}
			return true;
		}	

	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}

///Subscriber's mail for Order Invoice
function sendInvoice($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=''){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);

		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}

		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {

			if($_SESSION['usertype']!='Admin'){
				require_once($_SERVER["DOCUMENT_ROOT"].'/crm/Exec/Exec_SetFullCom.php');
				SetFullCom($to,$body,$subject,'email');
			}
			return true;
		}	

	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}

//Test Invoice 
function sendInvoiceTest($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=''){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);

		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}

		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return true;
		}	

	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}

///Subscriber's mail for Event booking
function sendEventMail($to, $subject, $template_name, $message, $headers, $other=array()){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}
		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {

			if($_SESSION['usertype']!='Admin'){
				require_once($_SERVER["DOCUMENT_ROOT"].'/crm/Exec/Exec_SetFullCom.php');
				SetFullCom($to,$body,$subject,'email');
			}
			return true;
		}	
	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}


///Subscriber's mail for Event Cancel
function sendEventMailForcanl($to, $subject, $template_name, $message, $headers, $other=array()){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}
		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {

			if($_SESSION['usertype']!='Admin'){
				require_once($_SERVER["DOCUMENT_ROOT"].'/crm/Exec/Exec_SetFullCom.php');
				SetFullCom($to,$body,$subject,'email');
			}
			return true;
		}	
	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}

///Subscriber's mail for Event Reminder
function sendEventRemingMail($to, $subject, $template_name, $message, $headers, $other=array()){  
	if(EMIALPERMISSION==1){
		require_once('phpmailer/PHPMailerAutoload.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
		
		$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
		$body = file_get_contents($templatepath.$template_name);
		
		foreach($other as $k => $v) {
			@$body = str_replace($k,$v,$body);
		}

		$body = wordwrap(trim($body), 70, "\r\n"); 
		$body = convert_smart_quotes($body);

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = $email_hostname;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $email_smtpport;
		$mail->Username = $email_username;
		$mail->Password = $email_password;
		$mail->setFrom($email_senderemail, $email_sendername);
		$mail->addReplyTo($email_replyemail, $email_replyname);
		$mail->addAddress($to, '');
		if(!empty($bcc_email)){
			$mail->AddBCC($bcc_email, $bcc_name);
		}
		$mail->Subject = $subject;
		$mail->msgHTML($body);
		if(!empty($filelocation)){
			$mail->addAttachment($filelocation);
		}
		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {

			if($_SESSION['usertype']!='Admin'){
				require_once($_SERVER["DOCUMENT_ROOT"].'/crm/Exec/Exec_SetFullCom.php');
				SetFullCom($to,$body,$subject,'email');
			}
			return true;
		}	
	}else{
		return 'You have not permission to send an Email/SMS.';
	}
}

///Subscriber's mail for Bug Report/Enquery (Help page)
function sendsmpleMailReport($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation=''){  

	require_once('phpmailer/PHPMailerAutoload.php');
	require_once($_SERVER["DOCUMENT_ROOT"].'/crm/EmailTemplate.php');
	
	$templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/assets/Templates/';
	$body = file_get_contents($templatepath.$template_name);
	
	foreach($other as $k => $v) {
		@$body = str_replace($k,$v,$body);
	}

	$body = wordwrap(trim($body), 70, "\r\n"); 
	$body = convert_smart_quotes($body);

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = $email_hostname;
	$mail->SMTPSecure = 'ssl';
	$mail->Port = $email_smtpport;
	$mail->Username = $email_username;
	$mail->Password = $email_password;
	$mail->setFrom($email_senderemail, $email_sendername);
	$mail->addReplyTo($email_replyemail, $email_replyname);
	$mail->addAddress($to, '');
	if(!empty($bcc_email)){
		$mail->AddBCC($bcc_email, $bcc_name);
	}
	$mail->Subject = $subject;
	$mail->msgHTML($body);

	if(!empty($filelocation)){
		$mail->addAttachment($filelocation);
	}

	if (!$mail->send()) {
		return "Mailer Error: " . $mail->ErrorInfo;
	} else {
		return true;
	}	


}

?>