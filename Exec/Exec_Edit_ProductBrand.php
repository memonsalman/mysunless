<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.ProductBrand.php'); 
if(isset($_REQUEST['viewdata']))
{
    $ClientDisplay=new Brand;
    $ClientDisplay->listoffile();   
}
if(isset($_POST["Brand"]))
{
    $myBrand = new Brand($_POST["id"]);
    $myBrand->id =stripslashes(strip_tags($_POST["id"]));// $_POST["id"];
    $myBrand->Brand = stripslashes(strip_tags($_POST["Brand"]));//$_POST["Brand"];
    $myBrand->commit($myBrand->id);
    if($myBrand)
    {
        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new product Brand '.$_POST["Brand"] ; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update product Brand '.$_POST["Brand"].' detail' ;      
        }
        $myactivite->commit_acitve($Titile);
        if($_POST['id']=="new")
        {     
            echo json_encode(['resonse'=>'Product Brand has been successfully added']);die;
        }
        else
        {
            echo json_encode(['resonse'=>'Product Brand has been successfully updated']);die;       
        }
    }
    else
    {
        echo json_encode(['error'=>'sorry something wrong']);die;
    }
}
?>