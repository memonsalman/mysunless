<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Membership{

	

	public $id;

	public $Name;

	public $Price;

	public $Tracking;

	public $Description;

	public $service;

	public $Noofvisit;	

	

	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		

		$this->Name = "";

		$this->Price = "";

		$this->Tracking = "";

		$this->Description = "";

		$this->service = "";

		$this->Noofvisit = "";		

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1; 

			       

		}else {

			try {

                $query = $db->prepare("SELECT * FROM MemberPackage WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				

				$this->Name = $info["Name"];

				$this->Price = $info["Price"];

				$this->Tracking = $info["Tracking"];

				$this->Description = $info["Description"];
				
				$this->service = $info["service"];	

				$this->Noofvisit = $info["Noofvisit"];	
				

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

                $query = $db->prepare("INSERT INTO `MemberPackage` (`Name`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {
				
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }



		try {

			

            $query = $db->prepare("UPDATE `MemberPackage` SET 

			 

			 `Name`=:Name,

			 `Price`=:Price,

			 `Tracking`=:Tracking,

			 `Description`=:Description,

			 `Noofvisit`=:Noofvisit,

			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `service`=:service,

			 `isactive`=:isactive  WHERE id=:myid");

             

   			  $query->bindValue(':Name', $this->Name, PDO::PARAM_STR);

			  $query->bindValue(':Price', $this->Price, PDO::PARAM_STR);

			  $query->bindValue(':Tracking', $this->Tracking, PDO::PARAM_STR);

			  $query->bindValue(':Description', $this->Description, PDO::PARAM_STR);

			  $query->bindValue(':Noofvisit', $this->Noofvisit, PDO::PARAM_STR);

			  $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);

			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			  $query->bindValue(':service', $this->service, PDO::PARAM_STR);

			  $query->bindValue(':myid', $this->id, PDO::PARAM_STR);

			 

			  $query->execute();

		}catch (PDOException $e) {

           echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;

            exit;

        }

		 //return $this->id;

		return $this->id;

	 }

}



?>