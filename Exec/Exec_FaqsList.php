<?php

    require_once('Exec_Config.php');        
	

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.FaqsList.php'); 
$FaqsList = new FaqsList;
$FaqsList->displayFaqs();
?>