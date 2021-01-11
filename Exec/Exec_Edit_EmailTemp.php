<?php
require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.EmailTemp.php'); 

if(isset($_GET['social_icon_data'])){
    $id = $_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * from EmailSetting where UserID=:id"); 
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();
    $data = $result['other'];
    if(!empty($data)){
        echo json_encode(['response'=>$data]);die;
    }else{
        echo json_encode(['response'=>0]);die;
    }
}

if(isset($_GET['social_icon'])){
    if(!empty($_POST['data'])){

    $id = $_SESSION['UserID'];
    $other = $_POST['data'];
    $stmt= $db->prepare("UPDATE EmailSetting set other=:other where UserID=:id"); 
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':other', $other);
    $stmt->execute();
    echo json_encode(['response'=>'Successfully updated.']);die;
    }else{
        echo json_encode(['response'=>'Empty data.']);die;
    }
}

if(isset($_REQUEST['viewdata']))
{
    $EmailTemp=new EmailTemp;
    $EmailTemp->AjaxDisplay();
}

if(!empty($_POST["id"])){
    $myevent = new EmailTemp($_POST["id"]);
    $myevent->id = stripslashes(strip_tags($_POST["id"])); 
    $Name=$myevent->Name =stripslashes(strip_tags($_POST["Name"]));
    $Subject=$myevent->Subject =stripslashes(strip_tags($_POST["Subject"]));
    $TextMassage=$myevent->TextMassage = $_POST["TextMassage"];
    $myevent->commit($myevent->id); 
    if($myevent)
    {
        if($_POST['id']=="new")
        {
            echo json_encode(['resonse'=>'Email template has been successfully added']);die;
        }
        else
        {       
            echo json_encode(['resonse'=>'Email template has been successfully updated']);die;    
        }       
    }
    else
    {
        echo json_encode(['error'=>'Something Wrong']);die;
    }
}


?>