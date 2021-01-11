<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
if(isset($_REQUEST['viewmsg']))
{   
    $db= new db();
    $id=$_POST['id'];
    $LoginQuery2= $db->prepare("SELECT MessageE FROM `emailsend` WHERE id=:id");
    $LoginQuery2->bindParam(':id', $id, PDO::PARAM_INT);
    $LoginQuery2->execute();
    $result2 = $LoginQuery2->fetch();
    echo json_encode($result2);die;	
}
if(isset($_REQUEST['viewdata']))
{
    $db=new db();
    $id=$_SESSION['UserID'];
    $LoginQuery= $db->prepare("SELECT * FROM `emailsend` WHERE userid=:id AND status='1' ");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;	
}
if(isset($_REQUEST['trashdata']))
{
    $db=new db();
    $id=$_SESSION['UserID'];
    $LoginQuery= $db->prepare("SELECT * FROM `emailsend` WHERE userid=:id AND status='0' ");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;	
}
if(isset($_REQUEST['delete']))
{	
    $db=new db();
    $id=$_POST['id'];
    $deletePermanent= $db->prepare("UPDATE `emailsend` SET `status`='0' WHERE id=:id ");
    $deletePermanent->bindParam(':id', $id, PDO::PARAM_INT);
    $deleteForevr = $deletePermanent->execute();
    if($deleteForevr)
    {
        echo  json_encode(["resonse"=>'Email has been removed from sent box.']);die;		
    }
    else
    {
        echo  json_encode(["error"=>'Something Wrong']);die;
    }
}
if(isset($_REQUEST['deletePermanent']))
{	
    $db=new db();
    $id=$_POST['id'];
    $deletePermanent= $db->prepare("DELETE FROM `emailsend` WHERE id=:id ");
    $deletePermanent->bindParam(':id', $id, PDO::PARAM_INT);
    $deleteForevr = $deletePermanent->execute();
    if($deleteForevr)
    {
        echo  json_encode(["resonse"=>'Email has been  delete parmenantly.']);die;		
    }
    else
    {
        echo  json_encode(["error"=>'Something Wrong']);die;
    }
}
?>