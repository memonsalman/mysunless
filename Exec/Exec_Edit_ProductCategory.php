<?php
    require_once('Exec_Config.php');        
        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.ProductCategory.php'); 
if(isset($_REQUEST['viewdata']))
{
    $ClientDisplay=new Category;
    $ClientDisplay->listoffile();   
}

if(isset($_POST["categoryChk"]))
{   
    $catName = $_POST["categoryChk"];
    $catid = $_POST["categoryid"];

    $mycategory = new Category;
    $catRes = $mycategory->getExistingCategory($catName,$catid);

    if($catRes == "found")
    {
       
        echo json_encode('Already existed in the Category list or Archive Category list.');die;
       
    }
    else
    { 
        echo json_encode(true);die;

    }
       

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
                $Titile=$myactivite->Titile = 'Add new product category '.$_POST["Category"] ; 
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update product category '.$_POST["Category"].' detail' ;      
            }
            $myactivite->commit_acitve($Titile);
            if($_POST['id']=="new")
            {     
                echo json_encode(['resonse'=>'Product Category has been successfully added']);die;
            }
            else
            {
                echo json_encode(['resonse'=>'Product Category has been successfully updated']);die;       
            }
        }
        else
        {
            echo json_encode(['error'=>'sorry something wrong']);die;
        }
   

   
}
?>