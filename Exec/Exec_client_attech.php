<?php

require_once('Exec_Config.php');        


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.client.php'); 


if(isset($_REQUEST['viewdata']))
{
    $ClientDisplay=new Client;
    $ClientDisplay->listoffile();
}
if(!empty($_FILES["document"]["name"]) && !empty($_POST["clinetid"]))
{
    $MyClient = new Client($_POST["id"]);
    $MyClient->id = $_POST["id"];
    $Iname=explode(".",$_FILES["document"]["name"]);
    $ImgObj= new AllFunction;
    $ImgFileName=$ImgObj->ImgName();
    @$MyClient->document = $ImgFileName.".".$Iname[1]; 
    $path = DOCUMENT_ROOT.ESUB."/assets/ClientDocs/";
    $path = $path . basename($MyClient->document);
    if(move_uploaded_file($_FILES["document"]["tmp_name"], $path)) 
    {
    } 
    else
    {
        $clientImage = "Client image was not uploaded please try again.";
    }
    $MyClient->clinetid=$_POST['id'];
    $MyClient->fileName = $_POST["fileName"];
    $MyClient->docattechment();
    //print_r($MyClient);

    if($MyClient)
    {	
        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Upload new document'; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Changed document';      
        }
        $myactivite->commit_acitve($Titile);
        echo json_encode(['resonse2'=>'Thank you! your document successfully uploaded']);die;
    }
    else
    {
        echo json_encode(['error2'=>'sorry something wrong']);die;
    }
}
else
{   
    echo json_encode(['error2'=>'Please fill client detail first']);die;
}
?>