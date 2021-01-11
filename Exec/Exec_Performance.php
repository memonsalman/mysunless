<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllPerformance.php'); 
if(isset($_GET['UpcomingRenewals']))
{   
    $UpcomingRenewals = new User() ;
    echo $UpcomingRenewals->UpcomingRenewals($_GET['UpcomingRenewals']);
}
/*else if(isset($_GET['service_star_time']))
{
    $User = new User;
    $service_star_time=$_GET['service_star_time'];
    $service_end_time=$_GET['service_end_time'];
    $User->particulerdate($service_star_time,$service_end_time);
}*/
else if(isset($_GET['service_star_time']))
{
    $User = new User;
    $daterange=$_GET['service_star_time'];
    $User->particulerdate($daterange);
}
else if (isset($_GET['count_activity'])){
    $User = new User;
    $daterange=$_GET['count_activity'];
    $User->count_activity($daterange);
}
else
{
    $User = new User;
    $User->UserDisplay();
}
?>