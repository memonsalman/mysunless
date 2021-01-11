<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class DisplayUsers{

	public function Ajax(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

			    $LoginQuery = $db->prepare("SELECT * FROM `users` WHERE `adminid`=:id AND `usertype`='user'");

			    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

			    $LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);die;	

			}

			

		}

?>