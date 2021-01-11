<?php

    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.MembershipsList.php');
if(isset($_GET['UpcomingRenewals'])){
    $UpcomingRenewals = new MembershipList() ;
    $UpcomingRenewals->UpcomingRenewals($_GET['UpcomingRenewals']);
}
if(isset($_GET['CurrentAndPaid'])){
    $CurrentAndPaid = new MembershipList() ;
    $CurrentAndPaid->CurrentAndPaid($_GET['CurrentAndPaid']);
}
if(isset($_GET['NotPaid'])){
    $NotPaid = new MembershipList() ;
    $NotPaid->NotPaid($_GET['NotPaid']);
}

?>