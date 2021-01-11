<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Sales_Report.php');
if(isset($_GET['UpcomingRenewals']))
{
    $UpcomingRenewals = new SalseReport() ;
    $UpcomingRenewals->UpcomingRenewals($_GET['UpcomingRenewals']);
}/*
else if(isset($_GET['service_star_time']))
{
    $User = new SalseReport;
    $service_star_time=$_GET['service_star_time'];
    $service_end_time=$_GET['service_end_time'];
    $User->particulerdate($service_star_time,$service_end_time);
}*/
else if(isset($_GET['daterange']))
{

    $User = new SalseReport;
    $daterange=$_GET['daterange'];
    $User->particulerdate($daterange);
}
else
{

    $User = new SalseReport;
    $User->UserDisplay();
}
?>