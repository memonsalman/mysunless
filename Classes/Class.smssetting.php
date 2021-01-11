<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class event{

	

	public $id;

	public $UserID;

	public $sid;

	public $token;

	public $Twillo_from;

	



	

	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->sid = "";

		$this->token = "";

		$this->Twillo_from = "";

		

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM smssetting WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->UserID = $info["UserID"];

				$this->sid = $info["sid"];

				$this->token = $info["token"];

				$this->Twillo_from = $info["Twillo_from"];

				

	

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

                $query = $db->prepare("INSERT INTO `smssetting` (`sid`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {

            	

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE smssetting SET 

			 `sid`=:sid,

			 `token`=:token,

			 `Twillo_from`=:Twillo_from,

			 `UserID`=:UserID,

			`datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':UserID', $this->UserID, PDO::PARAM_STR);	

              $query->bindValue(':sid', $this->sid, PDO::PARAM_STR);

			  $query->bindValue(':token', $this->token, PDO::PARAM_STR);

			  $query->bindValue(':Twillo_from', $this->Twillo_from, PDO::PARAM_STR);

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