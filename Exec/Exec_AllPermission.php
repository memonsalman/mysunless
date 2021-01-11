<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.All_Permission.php'); 

if(isset($_POST['paytime']))
{
    $id=$_POST['subuserid'];    
    $paytime=$_POST['paytime'];
    $packend=$_POST['packend'];

    $sth=$db->prepare("update payments set paytime=:paytime, packend=:packend where userid=:id"); 
    $sth->bindParam(':paytime',$paytime); 
    $sth->bindParam(':packend',$packend); 
    $sth->bindparam(":id",$id);
    $sth->execute(); 
    
}

if(isset($_POST['status']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateEmp();
}
if(isset($_POST['status2']))
{
    $ChangeStatus = new ChangeStatus;
    $ChangeStatus-> StatusUpdateclient();
}
if(isset($_POST['status3']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateSchedule();
}
if(isset($_POST['status4']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateTodo();
}
if(isset($_POST['status5']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateServie();
}
if(isset($_POST['status6']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> login_permission();
}
if(isset($_POST['status7']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateEmail();
}
if(isset($_POST['status8']))
{
    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> StatusUpdateOrder();
}
if(isset($_POST['subuserid']))
{

    $ChangeStatus = new ChangeStatus ;
    $ChangeStatus-> pakcagelimitupdate();	
}
?>