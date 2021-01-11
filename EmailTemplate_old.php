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
			$other['--COMPANY_NAME--'] = $result[0]['CompanyName'];
		}else{
			$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
			$other['--COMPANY_NAME--'] = "MySunless";
		}

	}else{
		$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
		$other['--COMPANY_NAME--'] = "MySunless";
	}


	/*$stmt= $db->prepare("SELECT * from EmailSetting where UserID=:id"); 
	$stmt->bindParam(':id', $id);
	$stmt->execute();
	$result1 = $stmt->fetch();
	$icon = $result1['other'];
	$email_hostname = $result1['fmail'];
	$email_username = $result1['fname'];
	$email_password = $result1['smtppassword'];
	if(strlen($email_hostname)==0)
	{*/
		$setting_query2 = $db->prepare("SELECT * FROM `users` WHERE id=:id");
		$setting_query2->bindParam(':id', $id);
		$setting_query2->execute();
		$result2 = $setting_query2->fetch();
		$email_hostname = $result2['email'];
		$email_username = $result2['username'];
		$email_password = $result2['password'];
	//}

	$stmt= $db->prepare("SELECT * from AdminEmailSetting where templatename=:templatename"); 
	$stmt->bindParam(':templatename', $template_name);
	$stmt->execute();
	$result3 = $stmt->fetch();
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
	$stmt= $db->prepare("SELECT * from AdminEmailSetting where templatename=:templatename"); 
	$stmt->bindParam(':templatename', $template_name);
	$stmt->execute();
	$result = $stmt->fetch();
	$email_smtpport = $result['smtpport'];
	$email_hostname = $result['hostname'];
	$email_username = $result['username'];
	$email_password = $result['password'];
	$email_senderemail = $result['senderemail'];
	$email_sendername = $result['sendername'];
	$email_replyemail = $result['replyemail'];
	$email_replyname = $result['replyname'];
	$bcc_email = $result['bccemail'];
	$bcc_name = $result['bccname'];
	$other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
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