<?php
    require_once('Exec_Config.php');        
	

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Service.php'); 
$myservie = new Service($_POST["id"]);
$myservie->id = $_POST["id"];
$myservie->ServiceName = $_POST["ServiceName"];
$myservie->Price = $_POST["Price"];
$myservie->Duration = $_POST["Duration"];
$myservie->Category = $_POST["Category"];
$myservie->Users = implode(',',$_POST["Users"]);
$myservie->Type = $_POST["Type"];
$myservie->Info = $_POST["Info"];	
$myservie->commit($myservie->id);
if($myservie)
{
    echo json_encode(['resonse'=>'Service successfully add']);die;
}
else
{
    echo json_encode(['error'=>'Service something wrong']);die;
}
?>