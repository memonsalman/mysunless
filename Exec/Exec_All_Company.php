<?php

    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.All.Company.php'); 
$ClientDisplay=new Display;
$ClientDisplay->AjaxDisplay();
?>