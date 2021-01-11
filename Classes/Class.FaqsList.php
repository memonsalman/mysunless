<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class  FaqsList{



	public function displayFaqs(){

		

				$db=new db();

				$id=$_SESSION['UserID'];

			    $todoQuery=$db->prepare("SELECT * FROM `faqs` ");

                $todoQuery->execute();

				$result = $todoQuery->fetchAll();

				
				echo json_encode($result);

			}

			

}



?>