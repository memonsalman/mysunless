<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

	class MembershipPackageList{

		public function AjaxDisplay(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

// $selectQuery=$db->prepare("SELECT MemberPackage.* FROM `MemberPackage` JOIN users ON (MemberPackage.createdfk=users.id OR MemberPackage.createdfk=users.adminid OR MemberPackage.createdfk=users.sid) WHERE (users.id=:id OR users.adminid=:id OR users.sid=:id) AND MemberPackage.isactive=1 GROUP BY MemberPackage.id"); 
			    // $selectQuery = $db->prepare("SELECT * FROM `MemberPackage` WHERE `createdfk`=:id");
			    $selectQuery = $db->prepare("SELECT * FROM `MemberPackage` WHERE isactive=1 and `createdfk` IN (select id from users where id=:id or adminid=:id or sid=:id)");

			    $selectQuery->bindParam(':id', $id, PDO::PARAM_INT);

			    $selectQuery->execute();

				$result = $selectQuery->fetchAll();

				echo json_encode($result);die;	

			}

	}

?>

