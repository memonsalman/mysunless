<?php

	require_once('Exec_Config.php');		
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');

		
require_once(Classes.'ClassAll_Event.php'); 
$ClientDisplay=new Display;
$ClientDisplay->AjaxDisplay();
?>