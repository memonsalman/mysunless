<?php
require_once('../global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');
class AllTodo{

	public function displayTodo(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

			    $todoQuery=$db->prepare("SELECT * FROM `todo` WHERE createdfk=:id AND isactive=1  ORDER BY dueDate");

			    $todoQuery->bindParam(':id', $id, PDO::PARAM_INT);

                $todoQuery->execute();

				$result = $todoQuery->fetchAll();

				echo json_encode($result);

			}

			

		}



?>