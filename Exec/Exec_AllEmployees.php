<?php
    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllEmployees.php'); 
if(isset($_GET['userid']))
{
    $id=$_GET['userid'];
    $User = new User;
    $User->particulerUser();
}
else
{
    $User = new User;
    $User->UserDisplay();
}
?>