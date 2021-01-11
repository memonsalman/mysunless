<?php

    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.paysetting.php'); 

$myevent = new event($_POST["id"]);
$myevent->id = $_POST["id"];
$myevent->UserID = $_POST["UserID"];

$type = $myevent->type = $_POST["type"];
$appId = $myevent->appId = $_POST["appId"];
$token = $myevent->token = $_POST["token"];
$locationId = $myevent->locationId = $_POST["locationId"];


if($type == "" || empty($appId) || empty($token) || empty($locationId))
{
    echo json_encode(['error'=>'sorry something wrong, Please try again']);die;

}
else
{
    $myevent->commit($myevent->id);    
}


if($myevent)
{
    if($_POST["id"]=="new")
    {
        echo json_encode(['resonse'=>'Square payment setting has been successfully added']);die; 	
    }
    else
    {
        echo json_encode(['resonse'=>'Square payment setting has been successfully updated']);die; 		
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>