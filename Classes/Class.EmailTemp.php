<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class EmailTemp{
	
	public $id;
	public $Name;
	public $Subject;
	public $TextMassage;
    
public function __construct($myid = "new"){
		$db = new db();
		$this->id = $myid;
		$this->Name = "";
		$this->Subject = "";
		$this->TextMassage = "";
		
		
		if ($myid == "new") {
			$this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
		}else {
			try {
                $query = $db->prepare("SELECT * FROM EmailTempleate WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
				
				$this->Name = $info["Name"];
				$this->Subject = $info["Subject"];
				$this->TextMassage = $info["TextMassage"];
				
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
                $query = $db->prepare("INSERT INTO `EmailTempleate` (`Name`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
			
        }
		try {
            $query = $db->prepare("UPDATE EmailTempleate SET 
			
			 `Name`=:Name,
			 `Subject`=:Subject,
			 `TextMassage`=:TextMassage,
			
			 `datecreated`=:datecreated,
			 `datelastupdated`=:datelastupdated,
			 `createdfk`=:createdfk,
			 `updatedfk`=:updatedfk,
			 `isactive`=:isactive  WHERE id=:myid");
            
              $query->bindValue(':Name', $this->Name, PDO::PARAM_STR);	
			  $query->bindValue(':Subject', $this->Subject, PDO::PARAM_STR);
			  $query->bindValue(':TextMassage', $this->TextMassage, PDO::PARAM_STR);
			
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


	 public function AjaxDisplay(){
		
				$db=new db();
				$id=$_SESSION['UserID'];
			    $LoginQuery= $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk IN (select id from users where id=:id or adminid=:id or sid=:id)");
			    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
			    $LoginQuery->execute();
				$result = $LoginQuery->fetchAll();
				echo json_encode($result);die;	
			}
}

?>