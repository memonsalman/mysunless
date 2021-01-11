<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Brand{

	

	public $id;

	public $Brand;

	



public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->Brand = "";

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM ProductBrand WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				

				$this->Brand = $info["Brand"];

				

				$this->datecreated = $info["datecreated"];

				$this->datelastupdated = $info["datelastupdated"];

				$this->createdfk = $info["createdfk"];

				$this->updatedfk = $info["updatedfk"];

				$this->isactive = 1;

			}

		}

		

	}

	 public function commit(){

		  $db = new db();

        if ($this->id == "new") {

            try {

                $query = $db->prepare("INSERT INTO `ProductBrand` (`Brand`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {
				echo $e;
				die;
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE ProductBrand SET 

			 `Brand`=:Brand,

			 

			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':Brand', $this->Brand, PDO::PARAM_STR);	

              $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);

			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			  $query->bindValue(':myid', $this->id, PDO::PARAM_STR);

			   $query->execute();

		}catch (PDOException $e) {

           echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;

            exit;

        }

		 //return $this->id;

		return $this->id;

	 }





	 public function listoffile(){

				

				$db= new db();



				$isactive=1;



				$id=$_SESSION['UserID'];
             $LoginQuery=$db->prepare("SELECT ProductBrand.* FROM `ProductBrand` JOIN users ON (ProductBrand.createdfk=users.id OR ProductBrand.createdfk=users.adminid OR ProductBrand.createdfk=users.sid) WHERE users.id=:id AND isactive=:isactive  GROUP BY ProductBrand.id"); 
			// $LoginQuery = $db->prepare("SELECT * FROM `ProductBrand` WHERE isactive=:isactive AND createdfk=:id");

				$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

				$LoginQuery->bindParam(':isactive', $isactive, PDO::PARAM_INT);

				$LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);				



		}

}



?>