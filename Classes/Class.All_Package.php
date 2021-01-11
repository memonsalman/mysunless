<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Display{

	public function AjaxDisplay(){

		

		$db=new db();

		$LoginQuery= $db->prepare("SELECT * FROM `package`");
		
		if(isset($_GET['active'])){
			$LoginQuery= $db->prepare("SELECT * FROM `package` where `isactive` = '1' ");
		}

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);	

	}

}

class ChangeStatus{	

	public function StatusUpdate(){

		

		$db=new db();
		$run = false;

		if(isset($_POST['type']) && $_POST['type']=='isactive'){

			if(isset($_POST['status']) && $_POST['status'] == '1'){

				$id=$_POST['id'];

				$disable = $db->prepare("UPDATE `package` SET `isactive` = '0' WHERE `id` = :id");

				$disable->bindParam(':id', $id, PDO::PARAM_INT);

				$run = $disable->execute();

			}

			if(isset($_POST['status']) && $_POST['status'] == '0'){

				$id=$_POST['id'];

				$enable = $db->prepare("UPDATE `package` SET `isactive` = '1' WHERE `id` = :id");

				$enable->bindParam(':id', $id, PDO::PARAM_INT);

				$run = $enable->execute();

			}
		}

		if(isset($_POST['type']) && $_POST['type']=='default_package' && !empty($_POST['id'])){
			
			$id=$_POST['id'];

			$stmt= $db->prepare("SELECT Price FROM `package` WHERE id=:id "); 
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$result = $stmt->fetch();

			if($result['Price']>0){

				$stmt= $db->prepare("SELECT * FROM `paymentsetup2` WHERE UserID = (select id from users where usertype='Admin')"); 
				$stmt->execute();
				$paymentsetup2 = $stmt->fetch();


				$stmt= $db->prepare("SELECT * FROM `paymentsetup` WHERE UserID = (select id from users where usertype='Admin')"); 
				$stmt->execute();
				$paymentsetup = $stmt->fetch();

				if(empty($paymentsetup['token']) && empty($paymentsetup2['AUTHNET_LOGIN'])){
					echo json_encode(['error'=>'Please first setup your payment method.']);die;
				}

			}

			$disable = $db->prepare("UPDATE `package` SET `default_package` = 0 WHERE 1 ");

			$run = $disable->execute();

			$enable = $db->prepare("UPDATE `package` SET `default_package` = 1 WHERE `id` = :id");

			$enable->bindParam(':id', $id);

			$run = $enable->execute();

		}

		if($run){
			echo json_encode(['response'=>'Status Update Successfully...']);die;
		}else{
			echo json_encode(['error'=>'Something went wrong. Please refresh the page and try again.']);die;
		}

	}	

}



?>