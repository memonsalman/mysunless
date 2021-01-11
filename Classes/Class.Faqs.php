<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class  Faqs{

	public $id;
	public $faqTitle;
	public $faqDesc;
	public $faqCategory;


	public function __construct($myid = "new"){
		$db = new db();
	
		$this->id = $myid;
		$this->faqTitle = "";
		$this->faqDesc = "";
		$this->faqCategory="";
		if ($myid == "new") {


			@$this->datecreated = date("Y-m-d H:i:s");
            @$this->datelastupdated = date("Y-m-d H:i:s");
            @$this->createdfk = $_SESSION["UserID"];
            @$this->updatedfk = $_SESSION["UserID"];
            @$this->isactive = 1;
          
           	
           	if($_POST["faqCategoryNew"] != "")
			{
				
				$this->faqCategory = trim($_POST["faqCategoryNew"]," ");
				
			}	
			else if($_POST["faqCategoryOld"] != "")
			{
				
				$this->faqCategory = trim($_POST["faqCategoryOld"]," ");
				
			}
			
		}
		else {
			try {

                $query = $db->prepare("SELECT * FROM faqs WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
					

				@$this->faqTitle = $info["faqTitle"];
				@$this->faqDesc = $info["faqDesc"];
				@$this->datecreated = $info["datecreated"];
				@$this->datelastupdated = $info["datelastupdated"];
				@$this->createdfk = $info["createdfk"];
				@$this->updatedfk = $info["updatedfk"];
				@$this->isactive = 1;
				@$this->faqCategory =$info["faqCategory"]; 	


			}
		}
	}

	 public function commit(){
		  $db = new db();
        if ($this->id == "new") {
            try {
           
                $query = $db->prepare("INSERT INTO `faqs` (`faqTitle`) VALUES ('New')");
               
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
				

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
			
        }
		try {

            $query = $db->prepare("UPDATE faqs SET 
			 `faqTitle`=:faqTitle,
			 `faqDesc`=:faqDesc,
			 `datecreated`=:datecreated,
			 `datelastupdated`=:datelastupdated,
			 `createdfk`=:createdfk,
			 `updatedfk`=:updatedfk,
			 `isactive`=:isactive,
			 `faqCategory`=:faqCategory WHERE id=:myid");
	
			  $query->bindValue(':faqTitle', $this->faqTitle, PDO::PARAM_STR);	
              $query->bindValue(':faqDesc', $this->faqDesc, PDO::PARAM_STR);	
			  $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);
			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);
			  $query->bindValue(':faqCategory', $this->faqCategory, PDO::PARAM_STR);
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