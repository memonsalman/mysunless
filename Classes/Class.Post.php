<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class AllPost{

	public $id;
	public $PostTitle;
	public $PostDec;

	public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->PostTitle = "";
        $this->PostDec = "";
        $this->PostDate = "";
        
        if ($myid == "new") 
        {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }
        else
         {
            try {
                $query = $db->prepare("SELECT * FROM `Post` WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->PostTitle = $info["PostTitle"];
                $this->PostDec = $info["PostDec"];
                $this->PostDate = $info["PostDate"];
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
                $query = $db->prepare("INSERT INTO `Post` (`PostTitle`,`PostDec`) VALUES ('New','New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE `Post` SET 
					`PostTitle`=:PostTitle,
                    `PostDec`=:PostDec,
					`PostDate`=:PostDate,
					`datecreated`=:datecreated,
					`datelastupdated`=:datelastupdated,
					`createdfk`=:createdfk,
					`updatedfk`=:updatedfk,
					`isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':PostTitle', @$this->PostTitle, PDO::PARAM_STR);
            $query->bindValue(':PostDec', @$this->PostDec, PDO::PARAM_STR);
            $query->bindValue(':PostDate', @$this->PostDate, PDO::PARAM_STR);
            $query->bindValue(':datecreated', @$this->datecreated, PDO::PARAM_STR);
            $query->bindValue(':datelastupdated', @$this->datelastupdated, PDO::PARAM_STR);
            $query->bindValue(':createdfk', @$this->createdfk, PDO::PARAM_STR);
            $query->bindValue(':updatedfk', @$this->updatedfk, PDO::PARAM_STR);
            $query->bindValue(':isactive', @$this->isactive, PDO::PARAM_STR);
            $query->bindValue(':myid', @$this->id, PDO::PARAM_STR);
            $query->execute();
        }catch (PDOException $e) {
            // echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;
            echo $e->getMessage();
            exit;
        }
        //return $this->id;
        return $this->id;
    }

	public function displayPost()
	{
		$db=new db();
        $id=$_SESSION['UserID'];
		$todoQuery=$db->prepare("SELECT *,(SELECT Maintenance from users where usertype='Admin' ) as Maintenance FROM `Post`");
		$todoQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$todoQuery->execute();
		$result = $todoQuery->fetchAll();
		echo json_encode($result);
	}

			

		}



?>