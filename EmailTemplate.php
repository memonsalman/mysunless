<?php 
require_once('function.php');


if(isset($_SESSION["usertype"]) && $_SESSION['usertype']!="Admin"){


	if($_SESSION['usertype']=='subscriber'){ 
		$id = $_SESSION['UserID'];
	}else{
		$id = $_SESSION['adminid'];
	}
	$db = new db();
	$stmt = $db->prepare("SELECT compimg,CompanyName FROM `CompanyInformation` where createdfk=:id");
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result = $stmt->fetchAll();

	if(!empty($result)){
		if(!empty($result[0]['compimg'])){
			$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/companyimage/".$result[0]['compimg'];
		}else{
			$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
		}

		if(!empty($result[0]['CompanyName'])){
			$other['--COMPANY_NAME--'] = $result[0]['CompanyName'];
		}else{
			$other['--COMPANY_NAME--'] = "MySunless";
		}

	}else{
		$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
		$other['--COMPANY_NAME--'] = "MySunless";
	}


	$stmt= $db->prepare("SELECT * from EmailSetting join users on users.id=EmailSetting.UserID where UserID=:id"); 
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result1 = $stmt->fetch();
	$icon = $result1['other'];

	$email_username = empty($result1['smtpusername'])?$result1['firstname']:$result1['smtpusername'];
	$email_password = empty($result1['smtppassword'])?$result1['password']:$result1['smtppassword'];

	$email_senderemail = empty($result1['fmail'])?$result1['email']:$result1['fmail'];
	$email_replyemail = $email_senderemail;

	$email_hostname = empty($result1['smtphost'])?'mysunless.com':$result1['smtphost'];

	$email_sendername = empty($result1['fname'])?$result1['username']:$result1['fname'];
	$email_replyname = $email_sendername;

	$temphostname= $email_hostname;

	
	$stmt= $db->prepare("SELECT * from AdminEmailSetting where templatename=:templatename"); 
	$stmt->bindParam(':templatename', $template_name);
	$stmt->execute();
	$result3 = $stmt->fetch();

	$email_smtpport = isset($result3['smtpport'])?$result3['smtpport']:465;
	$bcc_email = $result3['bccemail'];
	$bcc_name = $result3['bccname'];

	$social_icon = '';
	if(!empty($icon)){
		$icon = json_decode($icon);
		foreach ($icon as $key => $value) {
			$icon = key($value);
			$url = $value->$icon;
			$icon = str_replace('_link','',$icon);
			$icon = base_url."/assets/icons/".$icon.".png";
			$social_icon .='<td style="padding: 0 10px;"> <a class="social" href="'.$url.'" target="_blank"><img src="'.$icon.'" height="24" width="24"></a> </td>';

		}
	}
	$other['--social_icon--'] = $social_icon;

}else{
	$db = new db();
	$stmt= $db->prepare("SELECT * from AdminEmailSetting where templatename=:templatename or templatename='default-email.php' "); 
	$stmt->bindParam(':templatename', $template_name);
	$stmt->execute();
	$result = $stmt->fetchAll();

	if(!empty($result[0]['username']) && !empty($result[0]['password'])){

		$email_smtpport = $result[0]['smtpport'];
		$email_hostname = $result[0]['hostname'];
		$email_username = $result[0]['username'];
		$email_password = $result[0]['password'];
		$email_senderemail = $result[0]['senderemail'];
		$email_sendername = $result[0]['sendername'];
		$email_replyemail = $result[0]['replyemail'];
		$email_replyname = $result[0]['replyname'];
		$bcc_email = $result[0]['bccemail'];
		$bcc_name = $result[0]['bccname'];

	}else{
		$email_smtpport = $result[1]['smtpport'];
		$email_hostname = $result[1]['hostname'];
		$email_username = $result[1]['username'];
		$email_password = $result[1]['password'];
		$email_senderemail = $result[1]['senderemail'];
		$email_sendername = $result[1]['sendername'];
		$email_replyemail = $result[1]['replyemail'];
		$email_replyname = $result[1]['replyname'];
		$bcc_email = $result[1]['bccemail'];
		$bcc_name = $result[1]['bccname'];

	}


	$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/logo.png";
	$other['--COMPANY_NAME--'] = "MySunless";


	$stmt= $db->prepare("SELECT * from EmailSetting where UserID= (select id from users where usertype='Admin')"); 
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result1 = $stmt->fetch();
	$icon = $result1['other'];
	$social_icon = '';
	if(!empty($icon)){
		$icon = json_decode($icon);
		foreach ($icon as $key => $value) {
			$icon = key($value);
			$url = $value->$icon;
			$icon = str_replace('_link','',$icon);
			$icon = base_url."/assets/icons/".$icon.".png";
			$social_icon .='<td style="padding: 0 10px;"> <a class="social" href="'.$url.'" target="_blank"><img src="'.$icon.'" height="24" width="24"></a> </td>';

		}
	}
	$other['--social_icon--'] = $social_icon;
}


?>