<?php

    require_once('Exec_Config.php');        
        
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Category.php'); 
if(isset($_REQUEST['viewdata']))
{
    $ClientDisplay=new Category;
    $ClientDisplay->listoffile();   
}
if(isset($_POST["Category"]))
{
    
    $mycategory = new Category($_POST["id"]);
    $mycategory->id =stripslashes(strip_tags($_POST["id"]));// $_POST["id"];
    $mycategory->Category = stripslashes(strip_tags($_POST["Category"]));//$_POST["Category"];
    $mycategory->commit($mycategory->id);
    if($mycategory)
    {
        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new service dategory '.$_POST["Category"] ; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update service category '.$_POST["Category"].' detail' ;      
        }
        $myactivite->commit_acitve($Titile);
        if($_POST['id']=="new")
        {     
            echo json_encode(['resonse'=>'Category has been successfully added']);die;
        }
        else
        {
            echo json_encode(['resonse'=>'Category has been successfully updated']);die;       
        }
    }
    else
    {
        echo json_encode(['error'=>'Sorry something wrong']);die;
    }
}
?>