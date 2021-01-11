<?php
require_once('Exec_Config.php');		


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Payment_Report.php');

if(isset($_GET['PaymentReport']))
{

 $UpcomingRenewals = new SalseReport() ;
 $UpcomingRenewals->PaymentReport();
}

// if(isset($_GET['getalltraction']))
// {
//  $UpcomingRenewals = new SalseReport();
//  $UpcomingRenewals->getalltraction();
// }


// if(isset($_GET['selectdaterang']))
// {

//  $UpcomingRenewals = new SalseReport() ;
//  $UpcomingRenewals->UpcomingRenewals();
// }

// if(isset($_GET['UpcomingRenewals2']))
// {

//  $UpcomingRenewals = new SalseReport() ;
//  $UpcomingRenewals->UpcomingRenewals2($_GET['UpcomingRenewals2']);
// }



?>