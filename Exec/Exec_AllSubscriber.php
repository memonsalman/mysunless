<?php
	
    require_once('Exec_Config.php');        
		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllSubscriber.php'); 
if(isset($_REQUEST['subuserid']))
{
	
    $id=$_REQUEST['subuserid'];
    $Subscriber = new Subscriber;
    $Subscriber->particulersub();
}
else
{

    $Subscriber = new Subscriber;
    $Subscriber->SubscriberDisplay();
}
?>