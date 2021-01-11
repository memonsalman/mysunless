<?php

    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.smssetting.php'); 
$myevent = new event($_POST["id"]);
$myevent->id = $_POST["id"];
$myevent->UserID = $_POST["UserID"];
$sid=$myevent->sid = $_POST["sid"];
$token=$myevent->token = $_POST["token"];
$ph=str_replace("(","",$_POST["Twillo_from"]);
$ph2=str_replace(") ","",$ph);
$ph3=str_replace("-","",$ph2);
$Twillo_from=$myevent->Twillo_from = '+'.$_POST["tnex"].$ph3;
$myevent->commit($myevent->id);
if($myevent)
{
    if($_POST["id"]=="new")
    {
        echo json_encode(['resonse'=>'Twillow setting has been successfully added']);die; 	
    }
    else
    {
        echo json_encode(['resonse'=>'Twillow setting has been successfully updated']);die; 		
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>