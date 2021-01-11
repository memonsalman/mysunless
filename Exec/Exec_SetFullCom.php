<?php
require_once('Exec_Config.php');  
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllMail.php');

function SetFullCom($to,$MessageE,$Subject,$type='email'){

	$AllMail = new AllMail("new");

	$db = new db();

	$query=$db->prepare("Select id from clients where email=:to and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) ");
	$query->bindparam(":id",$_SESSION['UserID']);
	$query->bindparam(":to",$to);
	$query->execute();
	$result = $query->fetch();

	$AllMail->ccid = $result['id'];
	$AllMail->type = $type; 
	$AllMail->MessageE = $MessageE;
	$AllMail->Subject = $Subject;
	$AllMail->FullCom();

}


?>