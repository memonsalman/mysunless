<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class User{
	public function UserDisplay()
	{
				
		$db=new db();
		$id=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT * FROM `users` WHERE `adminid`=:id AND usertype='employee' ");
		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;	
	}
	public function particulerUser()
	{
		$db=new db();
		$id=$_GET['userid'];
		$adminid=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT * FROM `users` WHERE `id`=:userid AND `adminid`= :adminid AND `usertype`='employee' ");
		$LoginQuery->bindValue(':userid', $id, PDO::PARAM_STR);
		$LoginQuery->bindValue(':adminid', $adminid, PDO::PARAM_STR);
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;
			
	}

}
?>