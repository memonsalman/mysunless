<?php

    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllCommission.php'); 
if(isset($_GET['service_star_time']))
{	
    $User = new User;
    $service_star_time=$_GET['service_star_time'];
    $service_end_time=$_GET['service_end_time'];
    $User->particulerdate($service_star_time,$service_end_time);
}
else
{
    $User = new User;
    $User->UserDisplay();
}
?>