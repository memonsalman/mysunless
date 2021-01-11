<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Service{

	public $id;
	public $ServiceName;
	public $Price;
	public $Duration;
	public $starttime;
	public $endtime;
	public $Category;
	public $Users;
	public $Type;
	public $Info;


public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;
		$this->UserID = "";
		$this->ServiceName = "";
		$this->Price = "";
		$this->Duration = "";
		$this->starttime = "";
		$this->endtime = "";
		$this->Category = "";
		$this->Info = "";
		$this->Users = "";
		$this->Type = "";
		$this->Info = "";


if ($myid == "new") {

		
		$this->datecreated = date("Y-m-d H:i:s");
		$this->datelastupdated = date("Y-m-d H:i:s");
        $this->createdfk = $_SESSION["UserID"];
        $this->updatedfk = $_SESSION["UserID"];
		$this->isactive = 1;

} else {

	try {

    	$query = $db->prepare("SELECT * FROM Service WHERE id=:myid");
        $query->bindValue(':myid', $myid, PDO::PARAM_INT);
        $query->execute();

} catch (PDOException $e) {

       logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
    	exit;

}


while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

		$this->ServiceName = $info["ServiceName"];
		$this->Price = $info["Price"];
		$this->Duration = $info["Duration"];
		$this->starttime = $info["starttime"];
		$this->endtime = $info["endtime"];
		$this->Category = $info["Category"];
		$this->Users = $info["Users"];
		$this->Type = $info["Type"];
		$this->Info = $info["Info"];
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

     	$query = $db->prepare("INSERT INTO `Service` (`ServiceName`) VALUES ('New')");
		$query->execute();
        $this->id = $db->lastInsertId();
    } catch (PDOException $e) {
			
    	logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
	exit;

	    }


	
}

try {
		
		$query = $db->prepare("UPDATE Service SET 
		`ServiceName`=:ServiceName,
		`Price`=:Price,
		`Duration`=:Duration,
	    `starttime`=:starttime,
		`endtime`=:endtime,
		`Category`=:Category,
		`Users`=:Users,
	    `Type`=:Type,
		`Info`=:Info,
		`datecreated`=:datecreated,
    	`datelastupdated`=:datelastupdated,
		`createdfk`=:createdfk,
		`updatedfk`=:updatedfk,
		`isactive`=:isactive  WHERE id=:myid");

        $query->bindValue(':ServiceName', $this->ServiceName, PDO::PARAM_STR);
        $query->bindValue(':Price', $this->Price, PDO::PARAM_STR);	
		$query->bindValue(':Duration', $this->Duration, PDO::PARAM_STR);	
		$query->bindValue(':starttime', $this->starttime, PDO::PARAM_STR);	
        $query->bindValue(':endtime', $this->endtime, PDO::PARAM_STR);	
 		$query->bindValue(':Category', $this->Category, PDO::PARAM_STR);	
        $query->bindValue(':Users', $this->Users, PDO::PARAM_STR);	
        $query->bindValue(':Type', $this->Type, PDO::PARAM_STR);	
        $query->bindValue(':Info', $this->Info, PDO::PARAM_STR);	
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

return $this->id;
}



	public function getExistingService($serName){

			$db= new db();
			$id=$_SESSION['UserID'];
			 $serviceQuery=$db->prepare("SELECT sv.* from `Service` AS sv JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) WHERE users.id IN(SELECT DISTINCT(u2.id) from users u1 join users u2 join users u3 on u1.id=u2.id or u1.id=u2.adminid or u1.adminid=u2.adminid or u1.adminid=u2.id where u1.id=:id) AND lower(sv.ServiceName)=:serName GROUP BY sv.id"); 



				$serviceQuery->bindParam(':id', $id);

				$serviceQuery->bindParam(':serName', $serName);

				$serviceQuery->execute();

				

				if($serviceQuery->rowCount() > 0){
				  
				    return "found";
				  }else{
				  
				  	return "not found";
				  }
	
		}

}



?>