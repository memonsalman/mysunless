<?php
ini_set("display_errors", "1");
  error_reporting(E_ALL);

    require_once('Exec_Config.php');        
  

require_once($_SERVER['DOCUMENT_ROOT'].ESUB."/function.php");
require_once(Classes.'/Class.Profile.php');
// require_once(ReData."exec-editprofile.php");
$obj=new EditProfile();

if(isset($_GET['action9']))
{   
    
    $obj=new EditProfile();
    $obj->viewpackage();
}

// action for upldate profile.
if(isset($_POST['action']))
{
    $obj=new EditProfile();
    $profile_update=$obj->update();
}
// action2 for change password
if(isset($_POST['action2']))
{
    $obj=new EditProfile();
    $obj->update_password();
}
// belowe action for image uplode
if(isset($_FILES["userimg"]['name']))
{
    $obj=new EditProfile();
    $obj->uplode_user_image();	
}

if(isset($_POST['userimg']))
{
    $obj=new EditProfile();
    $obj->upload_crop_user_image();  
}
// belowe action for compay image uplode
if(isset($_FILES["compimg"]['name']))
{
    $obj=new EditProfile();
    $obj->uplode_comp_image();	
}
// action3 for forget password
if(isset($_POST['action3']))
{
    $obj=new EditProfile();
    $obj->forget_password();
}
// action3 for reset password
if(isset($_POST['action4']))
{
    $obj=new EditProfile();
    $obj->Rest_password();
}
if(isset($_POST['myData']))
{
    $obj=new EditProfile();
    $obj->deleteimage();	
}
if(isset($_POST['cimyData1']))
{
    $obj=new EditProfile();
    $obj->cdeleteimage();	
}
if(isset($_POST['cimyData2']))
{
    $obj=new EditProfile();
    $obj->cdeleteimage();	
}
if(isset($_POST['packageid']))
{
    $obj=new EditProfile();
    $obj->updatepackage();	
}
if(isset($_POST['action8']))
{
    $obj=new EditProfile();
    $obj->Unsubscribed();		
}
if(isset($_POST['signdata']))
{
    $obj=new EditProfile();
    $obj->Addsing();       
}


if(isset($_POST['removeSignData']))
{
     $obj = new EditProfile();
    $obj->removeSignature();
}
?>