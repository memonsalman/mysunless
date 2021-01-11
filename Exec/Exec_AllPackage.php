<?php

    require_once('Exec_Config.php');        
		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.All_Package.php'); 
if(isset($_GET['data'])){
$ClientDisplay=new Display;
$ClientDisplay->AjaxDisplay();
}

if(isset($_GET['status'])){
$ChangeStatus = new ChangeStatus ;
$ChangeStatus-> StatusUpdate();
}
?>