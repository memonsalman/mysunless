<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Package.php'); 
$mypackage = new Package($_POST["id"]);
$mypackage->id = $_POST["id"];
$mypackage->UserID = $_POST["UserID"];
$PackageName=$mypackage->PackageName = $_POST["PackageName"];
$Price=$mypackage->Price = $_POST["Price"];
$employeeLimit=$mypackage->employeeLimit = $_POST["employeeLimit"];
$ValidityDay=$mypackage->ValidityDay = $_POST["ValidityDay"];
$ClientsLimit=$mypackage->ClientsLimit = $_POST["ClientsLimit"];
$packagedesc=$mypackage->packagedesc = $_POST["packagedesc"];
$mypackage->commit($mypackage->id);
if($mypackage)
{
    $myactivite = new Activites();
    if($_POST['id']=="new")
    {
        $Titile=$myactivite->Titile = 'Add new package '.$PackageName;	
    }
    else
    {
        $Titile=$myactivite->Titile = 'Update package details '.$PackageName;		
    }
    $myactivite->commit_acitve($Titile);
    if($_POST['id']=="new")
    {
        echo json_encode(['resonse'=>'Package has been successfully created']);die;	
    }	
    else
    {
        echo json_encode(['resonse'=>'Package has been successfully updated']);die;	
    }
}
else
{
    echo json_encode(['error'=>'Something Wrong']);die;
}
?>