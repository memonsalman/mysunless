<?php

    require_once('Exec_Config.php');        


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.Post.php'); 

if(isset($_POST["PostTitle"]))
{

     $mynote = new AllPost($_POST["id"]);
     $mynote->id = $_POST["id"];
     $mynote->PostTitle =$_POST["PostTitle"];
     $mynote->PostDec =$_POST["PostDec"];

     if($_POST["PostTitle"]=='Maintenance'){

        $query = $db->prepare("Select * from Post where PostTitle='Maintenance' ");
        $query->execute();
        $result = $query->fetch();
        if($query->rowCount()>0 && $_POST["id"]!=$result['id']){
           echo json_encode(['error'=>'Maintenance post already exist.']);die;
        }

     }

     if(!empty($_POST["PostDate"])){
        $mynote->PostDate =$_POST["PostDate"];
     }else{
        $mynote->PostDate = NULL;
     }




     $mynote->commit($mynote->id);

     if($mynote)
    {
        $myactivite = new Activites();
       
     
        if($_POST['id'] == "new")
        {
            $Titile=$myactivite->Titile = 'Add new note '.$_POST["PostTitle"]; 

        }
        else
        {
            $Titile=$myactivite->Titile = 'Update note '.$_POST["PostTitle"].' detail'; 
                 
        }
        $myactivite->commit_acitve($Titile);

        if($_POST['id']=="new")
        {
        	echo json_encode(['resonse'=>'Post has been successfully added']);die;  
        }
        else
        {
            $_POST['id'] = "new";
        	echo json_encode(['resonse'=>'Post has been successfully updated']);die;	
        }
    }
    else
    {
    	   echo json_encode(['error'=>'sorry something wrong']);die;
    }
}

if(isset($_REQUEST['viewdata']))
{
$AllPost = new AllPost;
$AllPost->displayPost();			
}



?>	