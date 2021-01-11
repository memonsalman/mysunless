<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Category{

	

	public $id;

	public $Category;

	



	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->Category = "";

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

			$this->datelastupdated = date("Y-m-d H:i:s");

			$this->createdfk = $_SESSION["UserID"];

			$this->updatedfk = $_SESSION["UserID"];

			$this->isactive = 1;

		}else {

			try {

				$query = $db->prepare("SELECT * FROM ProductCategory WHERE id=:myid");

				$query->bindValue(':myid', $myid, PDO::PARAM_INT);

				$query->execute();

			} catch (PDOException $e) {
				
				logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

				exit;

			}

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				

				$this->Category = $info["Category"];

				

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

				$query = $db->prepare("INSERT INTO `ProductCategory` (`Category`) VALUES ('New')");

				$query->execute();

				$this->id = $db->lastInsertId();

			} catch (PDOException $e) {
				
				logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

				exit;

			}

			

		}

		try {

			$query = $db->prepare("UPDATE ProductCategory SET 

				`Category`=:Category,



				`datecreated`=:datecreated,

				`datelastupdated`=:datelastupdated,

				`createdfk`=:createdfk,

				`updatedfk`=:updatedfk,

				`isactive`=:isactive  WHERE id=:myid");

			$query->bindValue(':Category', $this->Category, PDO::PARAM_STR);	

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



	public function getExistingCategory($catName,$catid){
		$catid_string = '';
		
			if($catid!='new'){
				$catid_string = ' AND pc.id <> '.$catid.' ';
			}

		$db= new db();
		$id=$_SESSION['UserID'];
		$LoginQuery=$db->prepare("SELECT pc.* from `ProductCategory` AS pc JOIN users ON pc.createdfk=users.id WHERE pc.createdfk IN (SELECT DISTINCT(u2.id) from users u1 join users u2 join users u3 on u1.id=u2.id or u1.id=u2.adminid or u1.adminid=u2.adminid or u1.adminid=u2.id where u1.id=:id) AND pc.Category=:catName $catid_string GROUP BY pc.id"); 



		$LoginQuery->bindParam(':id', $id);

		$LoginQuery->bindParam(':catName', $catName);

		$LoginQuery->execute();



		if($LoginQuery->rowCount() > 0){

			return "found";
		}else{

			return "not found";
		}

	}

	public function listoffile(){


		$db= new db();

		$isactive=1;

		$id=$_SESSION['UserID']; 

		$LoginQuery=$db->prepare("SELECT pc.id,pc.Category,Count(p.id) as pcount FROM ProductCategory pc left join Product p on (FIND_IN_SET(pc.id,p.ProductCategory) and p.isarchive=1)  WHERE  pc.isactive=1 and pc.createdfk IN (Select id from users where id=:id or adminid=:id) GROUP by pc.id"); 

		$LoginQuery->bindParam(':id', $id);

		$LoginQuery->bindParam(':isactive', $isactive);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);				



	}

}



?>