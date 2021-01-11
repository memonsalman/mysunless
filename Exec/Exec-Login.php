<?php

    require_once('Exec_Config.php');        
		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');

require_once(DOCUMENT_ROOT.ESUB.'/Login_Check.php');

if(isset($_REQUEST['LoginAction']))
{
    $ChkLogin=new Insert;
    $ChkLogin->AjaxInsert();
}

if(isset($_REQUEST['SetMaintenance']) && $_SESSION['usertype']=='Admin')
{
	$SetMaintenance = $_REQUEST['SetMaintenance'];
	$stmt=$db->prepare("update users set Maintenance=:SetMaintenance where usertype='Admin' ");
	$stmt->bindParam(':SetMaintenance', $SetMaintenance);
	$run = $stmt->execute();

	if($run){
		if($SetMaintenance==1){
			echo json_encode(['response'=>'Maintenance Mode Activated.']);
		}else{
			echo json_encode(['response'=>'Maintenance Mode Deactivated.']);
		}
	}else{
		echo json_encode(['error'=>'Something went wrong.']);
	}
	die;
}
?>