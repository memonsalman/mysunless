<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class DisplayUsers{

	public function Ajax(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

				$LoginQuery = $db->prepare("SELECT Service.*,(SELECT GROUP_CONCAT(username) FROM users WHERE FIND_IN_SET(id,Service.Users)) as userbane FROM `Service` where Service.isactive=1 and Service.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id)");

			    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

			    $LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);die;	

			}

			

		}

?>