<?php
	
require_once('Exec_Config.php');        
		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllUsers.php'); 

if(isset($_GET['deleteuser']))
{
	$id = base64_decode($_POST['sid']);

  $Insert=$db->prepare("Insert Delete_User SELECT * from users where id =:id or adminid =:id");
  $Insert->bindValue(":id",$id);
  $run = $Insert->execute();  



  if($run && $Insert->rowCount()>0){  
    $date = date('Y-m-d H:i:s');
    $Update=$db->prepare("Update Delete_User set updated_at=:date where id=:id or adminid =:id");
    $Update->bindValue(":id",$id);
    $Update->bindValue(":date",$date);
    $Update->execute(); 

  	$Delete=$db->prepare("DELETE from `users` where id=:id or adminid =:id");
  	$Delete->bindValue(":id",$id);
  	$Delete->execute();  
  	if($Delete)
  	{
  		echo  json_encode(["resonse"=>"Subscriber successfully deleted"]);die;   
  	}
  }else{
      echo  json_encode(["error"=>"Something went wrong."]);die;   
  }


}

$UserDisplay=new DisplayUsers;
$UserDisplay->Ajax();
?>