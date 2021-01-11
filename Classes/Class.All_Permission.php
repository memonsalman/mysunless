<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class ChangeStatus{

	public function StatusUpdate(){
		
				$db=new db();
				if(isset($_POST['status']) && $_POST['status'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `EmployeeCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status']) && $_POST['status'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `EmployeeCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}

	public function StatusUpdateEmp(){
		
				$db=new db();
				if(isset($_POST['status']) && $_POST['status'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `EmployeeCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status']) && $_POST['status'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `EmployeeCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}

	public function StatusUpdateclient(){
		
				$db=new db();
				if(isset($_POST['status2']) && $_POST['status2'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `ClientCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status2']) && $_POST['status2'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `ClientCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}

	public function StatusUpdateSchedule(){
		
				$db=new db();
				if(isset($_POST['status3']) && $_POST['status3'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `SchedulesCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status3']) && $_POST['status3'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `SchedulesCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}	

	public function StatusUpdateTodo(){
		
				$db=new db();
				if(isset($_POST['status4']) && $_POST['status4'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `TodoCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status4']) && $_POST['status4'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `TodoCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}	



	public function StatusUpdateServie(){
		
				$db=new db();
				if(isset($_POST['status5']) && $_POST['status5'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `ServicesCreate` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status5']) && $_POST['status5'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `ServicesCreate` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}

	public function StatusUpdateEmail(){
		
				$db=new db();
				if(isset($_POST['status7']) && $_POST['status7'] == '1'){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `emailstatus` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status7']) && $_POST['status7'] == '0'){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `emailstatus` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}
		/* === Start Login Permission === */
	
	public function login_permission(){
			
				$db=new db();
				
				if(isset($_POST['status6']) && $_POST['status6'] === "1"){
					$id=$_POST['id'];
			 		$disable = $db->prepare("UPDATE `users` SET `login_permission` = '0' WHERE `id` = :id or adminid = :id");
			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$disable->execute();
				}
				if(isset($_POST['status6']) && $_POST['status6'] === "0"){
					$id=$_POST['id'];
					$enable = $db->prepare("UPDATE `users` SET `login_permission` = '1' WHERE `id` = :id or adminid = :id");
			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
			     	$enable->execute();
				}
			    	
	}
		/* === End Login Permission === */

		public function StatusUpdateOrder(){		
		$db=new db();
		if(isset($_POST['status8']) && $_POST['status8'] == '1'){
			$id=$_POST['id'];
	 		$disable = $db->prepare("UPDATE `users` SET `OrderCreate` = '0' WHERE `id` = :id or adminid = :id");
	     	$disable->bindParam(':id', $id, PDO::PARAM_INT);
	     	$disable->execute();
		}
		if(isset($_POST['status8']) && $_POST['status8'] == '0'){
			$id=$_POST['id'];
			$enable = $db->prepare("UPDATE `users` SET `OrderCreate` = '1' WHERE `id` = :id or adminid = :id");
	     	$enable->bindParam(':id', $id, PDO::PARAM_INT);
	     	$enable->execute();
		}
			    	
	}

	// === end Order Permission ===
	
	public function pakcagelimitupdate()
	{

		$db=new db();
		if(isset($_POST['subuserid']))
		{
			$id=$_POST['subuserid'];
			$UsersLimit=$_POST['UsersLimit'];
			$ClientLimits=$_POST['ClientLimits'];
			$EmployeeLimit=$_POST['EmployeeLimit'];
			
			$stmt=$db->prepare("UPDATE users set UsersLimit=:UsersLimit,ClientsLimit=:ClientLimits,employeeLimit=:EmployeeLimit where id=:id");
			$stmt->bindparam(":UsersLimit",$UsersLimit);
			$stmt->bindparam(":ClientLimits",$ClientLimits);
			$stmt->bindparam(":EmployeeLimit",$EmployeeLimit);
			$stmt->bindparam(":id",$id);
			$stmt->execute();

			if($stmt)
			{
				echo json_encode(['resonseforlimt'=>'Limit has been Updated']);die;
			}
			else
			{
				echo json_encode(['errorforlimit'=>'sorry something wrong']);die;
			}

		}

	}	


	

}


?>