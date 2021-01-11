<?php

	require_once('Exec_Config.php');		
	

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Prodcut_Sale_Report.php');

//  if(isset($_GET['getalltraction']))
//  {
 	
// }

$UpcomingRenewals = new SalseReport();
$UpcomingRenewals->getalltraction();

?>