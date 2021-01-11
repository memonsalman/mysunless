<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Note{

	

	public $id;

	public $noteTitle;

	public $noteDetail;

	public $noteRelated;

	public $cid;



public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->noteTitle = "";

		$this->noteDetail = "";

		$this->noteRelated = "";

		$this->cid = "";

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->enterdate = date("Y-m-d H:i:s");

            if(@$_SESSION['usertype']=="Admin")
            {
            	if(isset($_REQUEST["newlistofSubscriber3"])){
              		$this->createdfk = $_REQUEST["newlistofSubscriber3"];  
            	}
            }
            else
            {
            $this->createdfk = @$_REQUEST["UserID"];
            }

            $this->createdfk = @$_REQUEST["UserID"];

            $this->updatedfk = @$_REQUEST["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM `note` WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				

				$this->noteTitle = $info["noteTitle"];

				$this->noteDetail = $info["noteDetail"];

				$this->noteRelated = $info["noteRelated"];

				$this->cid = $info["cid"];

				$this->datecreated = $info["datecreated"];				

				$this->enterdate = $info["enterdate"];

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

                $query = $db->prepare("INSERT INTO `note` (`noteTitle`,`noteDetail`,`noteRelated`) VALUES ('New','New','New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {
				
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE `note` SET 

			 `noteTitle`=:noteTitle,

			 `noteDetail`=:noteDetail,

			 `noteRelated`=:noteRelated,

			 `cid`=:cid,

			 

			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 
			 `enterdate`=:enterdate,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':noteTitle', $this->noteTitle, PDO::PARAM_STR);

              $query->bindValue(':noteDetail', $this->noteDetail, PDO::PARAM_STR);

              $query->bindValue(':noteRelated', $this->noteRelated, PDO::PARAM_STR);	

              $query->bindValue(':cid', $this->cid, PDO::PARAM_STR);	

              $query->bindValue(':enterdate', $this->enterdate, PDO::PARAM_STR);

              $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);

			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			  $query->bindValue(':myid', $this->id, PDO::PARAM_STR);

			   $query->execute();

		}catch (PDOException $e) {

           // echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;

		   echo $e->getMessage();

            exit;

        }

		 //return $this->id;

		return $this->id;

	 }





	 public function displayNote(){

		

			$db=new db();

			$id=$_SESSION['UserID'];

		    $AllNote=$db->prepare("SELECT * FROM note WHERE createdfk=:id");

		    $AllNote->bindParam(':id', $id, PDO::PARAM_INT);

            $AllNote->execute();

			$result = $AllNote->fetchAll();

			echo json_encode($result);

	}



}



	





?>