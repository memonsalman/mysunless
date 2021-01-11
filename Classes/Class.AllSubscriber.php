<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class Subscriber{

	public function SubscriberDisplay()
	{

			if(!empty($_REQUEST['Users'])){
				$id = $_REQUEST['Users'];
				$id = " and users.id IN ($id) ";
			}else{
				$id = "";
			}

			$db=new db();			
			$result=[];
			$finalresult=[];
			$finalresult2=[];

			// $LoginQuery = $db->prepare("SELECT  users.*, (SELECT COUNT(id) FROM clients where clients.createdfk=users.id ) AS clientc FROM users WHERE usertype<>'Admin' $id GROUP BY id ORDER BY username");	
			// $LoginQuery->execute();

			// $result = $LoginQuery->fetchAll();
			// echo json_encode($result);die;

		$query= $db->prepare("SELECT * from users where usertype='subscriber' ");
          $query->execute();
          $result1 = $query->fetchAll();
          foreach ($result1 as $key => $value) {             	
          	 
          	 // array_push($result,$value);

            $query1= $db->prepare("SELECT  users.username,users.usertype,users.created_at,users.LastLogin,users.login_permission,users.userimg,users.email,users.id,users.adminid,users.firstname,users.lastname,users.companyname,users.phonenumber, (SELECT COUNT(id) FROM clients where clients.createdfk=users.id ) AS clientc FROM users WHERE usertype<>'Admin' and (id=:id or adminid=:id) $id GROUP BY id");
            $query1->bindParam(':id',$value['id']); 
            $query1->execute();
            $result2 = $query1->fetchAll();
            foreach ($result2 as $key => $value1) {
        	  array_push($result,$value1);
            
            }
          }	

          // $finalresult2=array_merge($result,$finalresult);
          
          echo json_encode($result);
          die();
		}		

	public function particulersub()

		{
			$db=new db();
			$id=$_REQUEST['subuserid'];
			$LoginQuery = $db->prepare("SELECT * FROM `users` WHERE id=$id AND usertype= 'subscriber' ");
         	// $LoginQuery->bindValue(':id', $id, PDO::PARAM_STR);
			$LoginQuery->execute();
			$result = $LoginQuery->fetchAll();

			echo json_encode($result);die;	

		}





}

?>