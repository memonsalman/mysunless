<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.eventsetting.php'); 
$myevent = new event($_POST["id"]);
$myevent->id = $_POST["id"];
$myevent->UserID = $_POST["UserID"];
$CostOfService=$myevent->CostOfService = $_POST["CostOfService"];
$EmailInstruction=$myevent->EmailInstruction = $_POST["EmailInstruction"];
$EmailReminder=$myevent->EmailReminder = $_POST["EmailReminder"];
$Riminederdate="";
if(isset($_POST['Riminederdate'])){
    $Riminederdate = $_POST["Riminederdate"];
    $Riminederdate=$myevent->Riminederdate = implode(',',$Riminederdate);
}
// $Riminederdate=implode(',',$Riminederdate2);
$myevent->commit($myevent->id);
if($myevent)
{
    if($_POST["id"]=="new")
    {
        echo json_encode(['resonse'=>'Appointment setting has been successfully added']);die; 	
    }
    else
    {
        echo json_encode(['resonse'=>'Appointment setting has been successfully updated']);die; 		
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>