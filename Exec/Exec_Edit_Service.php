<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Service.php'); 


if(isset($_POST["serviceChk"]))
{   
    $serName = $_POST["serviceChk"];
    $serType = $_POST["serviceId"];

    $myService = new Service;
    $serRes = $myService->getExistingService($serName);

    if($serType == "new")
    {
        if($serRes == "found")
        {
           
            echo json_encode('Service Already Exists!');die;
           
        }
        else
        { 
            echo json_encode(true);die;

        } 
    }
    else
    {
        echo json_encode(true);die;

    }
        
           

}




$myservie = new Service($_POST["id"]);
$myservie->id = stripslashes(strip_tags($_POST["id"])); //$_POST["id"];
$myservie->ServiceName =stripslashes(strip_tags($_POST["ServiceName"]));
$myservie->Price =stripslashes(strip_tags($_POST["Price"]));
$myservie->Duration =stripslashes(strip_tags($_POST["Duration"])); 
$myservie->starttime =stripslashes(strip_tags($_POST["starttime"]));
$myservie->endtime =stripslashes(strip_tags($_POST["endtime"])); 
//$myservie->Category = stripslashes(strip_tags($_POST["Category"]));
// $myservie->cusmerlimt =stripslashes(strip_tags($_POST["cusmerlimt"]));
//$myservie->asper =stripslashes(strip_tags($_POST["asper"]));
$myservie->Users = implode(',',$_POST["Users"]);

//$myservie->Type =stripslashes(strip_tags($_POST["Type"])); 
$myservie->Info =stripslashes(strip_tags($_POST["Info"]));
$myservie->commit($myservie->id);



if($myservie)
{   
    $myactivite = new Activites();
    if($_POST['id']=="new")
    {
        $Titile=$myactivite->Titile = 'Add new service '.$_POST["ServiceName"]; 
    }
    else
    {
        $Titile=$myactivite->Titile = 'Update Service '.$_POST["ServiceName"].' Details';      
    }
    $myactivite->commit_acitve($Titile);
    if($_POST['id']=="new")
    {
        echo json_encode(['resonse'=>'Service has been successfully added']);die;        
    }       
    else
    {
        echo json_encode(['resonse'=>'Service has been successfully updated']);die;
    }
}
else
{
    echo json_encode(['error'=>'Service something wrong']);die;
}
?>