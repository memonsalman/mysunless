<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Package{

	

	public $id;

	public $UserID;

	public $PackageName;

	public $ValidityDay;

    public $Price;

    public $employeeLimit;

    public $ClientsLimit;

    public $packagedesc;

    



	

	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->PackageName = "";

		$this->ValidityDay = "";

		$this->Price = "";

		$this->employeeLimit = "";

		$this->ClientsLimit = "";

		$this->packagedesc = "";

		

		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM package WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->PackageName = $info["PackageName"];

				$this->ValidityDay = $info["ValidityDay"];

				$this->Price = $info["Price"];

				$this->employeeLimit = $info["employeeLimit"];

				$this->ClientsLimit = $info["ClientsLimit"];

				$this->ClientsLimit = $info["packagedesc"];

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

                $query = $db->prepare("INSERT INTO `package` (`PackageName`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {

				echo $e;
				die;
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE package SET 

			 `PackageName`=:PackageName,

			 `ValidityDay`=:ValidityDay,

			 `Price`=:Price,

			 `employeeLimit`=:employeeLimit,

			 `ClientsLimit`=:ClientsLimit,

			 `packagedesc`=:packagedesc,
			
			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':PackageName', $this->PackageName);	

			  $query->bindValue(':ValidityDay', $this->ValidityDay);

			  $query->bindValue(':Price', $this->Price);

			  $query->bindValue(':employeeLimit', $this->employeeLimit);

			  $query->bindValue(':ClientsLimit', $this->ClientsLimit);
			  
			  $query->bindValue(':packagedesc', $this->packagedesc);

			  $query->bindValue(':datecreated', $this->datecreated);

			  $query->bindValue(':datelastupdated', $this->datelastupdated);

			  $query->bindValue(':createdfk', $this->createdfk);

			  $query->bindValue(':updatedfk', $this->updatedfk);

			  $query->bindValue(':isactive', $this->isactive);

			  $query->bindValue(':myid', $this->id);

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