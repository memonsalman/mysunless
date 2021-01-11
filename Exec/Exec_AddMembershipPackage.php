<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.AddMembershipPackage.php'); 
$myevent = new Membership($_POST["id"]);

$myevent->id = $_POST["id"];
$Name=$myevent->Name =stripslashes(strip_tags($_POST["Name"])); // $_POST["Name"];
$Price=$myevent->Price =$_POST["Price"];
if(@$_POST["Tracking2"])
{
    @$Tracking=$myevent->Tracking =$_POST["Tracking2"];

}
else{
    @$Tracking=$myevent->Tracking =$_POST["Tracking"];

}

//@$Tracking=$myevent->Tracking =$_POST["Tracking"];

$Description=$myevent->Description =stripslashes(strip_tags($_POST["Description"])); 
$myevent->service = implode(',',$_POST["Users"]);

if(@$_POST["Noofvisit2"])
{
  @$myevent->Noofvisit = $_POST["Noofvisit2"];

}
else{
    
    @$myevent->Noofvisit = $_POST["Noofvisit"];

}



//@$myevent->Noofvisit = $_POST["Noofvisit"];

$myevent->commit($myevent->Name);
if($myevent)
{
    $myactivite = new Activites();
    if($_POST['id']=="new")
    {
        $Titile=$myactivite->Titile = 'Add new Package '.$Name;	
    }
    else
    {
        $Titile=$myactivite->Titile = 'Update Package '.$Name.' details';		
    }
    $myactivite->commit_acitve($Titile);
    if($_POST['id']=="new")
    {
        echo json_encode(['resonse'=>'Package has been successfully added']);die;	
    }	
    else
    {
        echo json_encode(['resonse'=>'Package has been Successfully updated']);die;
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>

