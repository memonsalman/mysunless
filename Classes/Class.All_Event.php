<?php


require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Display{

	public function AjaxDisplay(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

			    $LoginQuery= $db->prepare("SELECT event.*,clients.ProfileImg FROM `event` LEFT JOIN clients ON event.cid=clients.id WHERE event.UserID=:id");

			    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

			    $LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);die;	

			}

			

		}



?>