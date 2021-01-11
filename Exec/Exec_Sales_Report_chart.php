<?php

	require_once('Exec_Config.php');		

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Sales_Report.php');
if(isset($_GET['UpcomingsDays']))
{
    $UpcomingsDays = new SalseReport() ;
    $UpcomingsDays->UpcomingsDays($_GET['UpcomingsDays']);
}
else if(isset($_GET['service_star_time2']))
{
    $User = new SalseReport;
    $service_star_time2=$_GET['service_star_time2'];
    $service_end_time2=$_GET['service_end_time2'];
    $User->particulerdate2($service_star_time2,$service_end_time2);
}
else
{
}
?>