<?php

	require_once('Exec_Config.php');		
	

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Brand_Sale_Report.php');

 if(isset($_GET['ProductCategory']))
 {
 	
  	$UpcomingRenewals = new SalseReport();
    $UpcomingRenewals->getalltraction();

}




?>