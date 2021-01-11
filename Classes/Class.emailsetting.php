<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class event{

	

	public $id;

	public $UserID;

	public $fmail;

	public $fname;

	public $smtphost;

    public $toe;

	public $smtpport;

    public $sa;

    public $smtpusername;

    public $smtppassword;



	

	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->fmail = "";

		$this->fname = "";

		$this->smtphost = "";

		$this->toe = "";

		$this->smtpport = "";

		$this->sa = "";

		$this->smtpusername = "";

		$this->smtppassword = "";



		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM EmailSetting WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->UserID = $info["UserID"];

				$this->fmail = $info["fmail"];

				$this->fname = $info["fname"];

				$this->smtphost = $info["smtphost"];

				$this->toe = $info["toe"];

				$this->smtpport = $info["smtpport"];

				$this->sa = $info["sa"];

				$this->smtpusername = $info["smtpusername"];

				$this->smtppassword = $info["smtppassword"];



	

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

                $query = $db->prepare("INSERT INTO `EmailSetting` (`fmail`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE EmailSetting SET 

			 `UserID`=:UserID,

			 `fmail`=:fmail,

			 `fname`=:fname,

			 `smtphost`=:smtphost,

			 `toe`=:toe,

			 `smtpport`=:smtpport,

			 `sa`=:sa,

			 `smtpusername`=:smtpusername,

			 `smtppassword`=:smtppassword,

			 

			`datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':UserID', $this->UserID, PDO::PARAM_STR);	

              $query->bindValue(':fmail', $this->fmail, PDO::PARAM_STR);

			  $query->bindValue(':fname', $this->fname, PDO::PARAM_STR);

			  $query->bindValue(':smtphost', $this->smtphost, PDO::PARAM_STR);

			  $query->bindValue(':toe', $this->toe, PDO::PARAM_STR);

			  $query->bindValue(':smtpport', $this->smtpport, PDO::PARAM_STR);

			  $query->bindValue(':sa', $this->sa, PDO::PARAM_STR);

			  $query->bindValue(':smtpusername', $this->smtpusername, PDO::PARAM_STR);

			  $query->bindValue(':smtppassword', $this->smtppassword, PDO::PARAM_STR);

			  

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