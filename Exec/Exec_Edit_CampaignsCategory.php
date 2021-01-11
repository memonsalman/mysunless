<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.CampaignsCategory.php'); 

if(isset($_REQUEST['viewdata']))
{

    $ClientDisplay=new Category;
    $ClientDisplay->listoffile();   
}
if(isset($_POST["CampaignsCategory"]))
{
    $mycategory = new Category($_POST["id"]);
    $mycategory->id =stripslashes(strip_tags($_POST["id"]));// $_POST["id"];
    $mycategory->CampaignsCategory = stripslashes(strip_tags($_POST["CampaignsCategory"]));//$_POST["Category"];
    $mycategory->commit($mycategory->id);
    if($mycategory)
    {
        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new campaigns category '.$_POST["CampaignsCategory"] ; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update campaigns category '.$_POST["CampaignsCategory"].' detail' ;      
        }
        $myactivite->commit_acitve($Titile);
        if($_POST['id']=="new")
        {     
            echo json_encode(['resonse'=>'Campaigns category has been successfully added']);die;
        }
        else
        {
            echo json_encode(['resonse'=>'Campaigns category has been successfully updated']);die;       
        }
    }
    else
    {
        echo json_encode(['error'=>'sorry something wrong']);die;
    }
}
?>