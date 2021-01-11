<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class event{

	

	public $id;
	public $UserID;
	public $token;
	public $type;
	public $appId;
	public $locationId;



	

	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";
		$this->token = "";
		$this->type = "";
	 	$this->appId = "";
	 	$this->locationId = "";


		

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM paymentsetup WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->UserID = $info["UserID"];

					$this->type = $info["type"];
					$this->appId = $info["applicationId"];
				$this->token = $info["token"];

			
	 			
	 			$this->locationId = $info["locationId"];

	

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

                $query = $db->prepare("INSERT INTO `paymentsetup` (`token`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE paymentsetup SET 

			  `UserID`=:UserID,
			`type`=:type,
			
			`applicationId`=:applicationId,
			`token`=:token,
			`locationId`=:locationId,
			
			

			`datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':UserID', $this->UserID, PDO::PARAM_STR);	


			  $query->bindValue(':type', $this->type, PDO::PARAM_STR);
			   $query->bindValue(':applicationId', $this->appId, PDO::PARAM_STR);
			  $query->bindValue(':token', $this->token, PDO::PARAM_STR);
			 
			  $query->bindValue(':locationId', $this->locationId, PDO::PARAM_STR);

			  
			   $query->bindValue(':myid', $this->id, PDO::PARAM_STR);




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

}



?>