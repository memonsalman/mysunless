<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Deals{

	

	public $id;

    public $Name;

    public $Value;

    public $Probability;

    public $Milestone;

    public $Close_Date;

    public $Deal_Source;

    public $Related_To;

    public $Description; 

	



	public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->Name = "";

		$this->Value = "";

		$this->Probability = "";

		$this->Milestone = "";

		$this->Close_Date = "";

		$this->Deal_Source = "";

		$this->Related_To = "";

		$this->Description = "";

		



		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM deals WHERE did=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->Name = $info["Name"];

				$this->Value = $info["Value"];

				$this->Probability = $info["Probability"];

				$this->Milestone = $info["Milestone"];

				$this->Close_Date = $info["Close_Date"]; 

				$this->Deal_Source = $info["Deal_Source"];

				$this->Related_To = $info["Related_To"];

				$this->Description = $info["Description"];

				

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

                $query = $db->prepare("INSERT INTO `deals` (`Name`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {



			$query = $db->prepare("UPDATE deals SET 

			 `Name`=:Name,

			 `Value`=:Value,

			 `Probability`=:Probability,

			 `Milestone`=:Milestone,

			 `Close_Date`=:Close_Date,

			 `Deal_Source`=:Deal_Source,

			 `Related_To`=:Related_To,

			 `Description`=:Description,

			

			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `isactive`=:isactive  WHERE did=:myid");

			  $query->bindValue(':Name', $this->Name, PDO::PARAM_STR);

			  $query->bindValue(':Value', $this->Value, PDO::PARAM_STR);

			  $query->bindValue(':Probability', $this->Probability, PDO::PARAM_STR);

			  $query->bindValue(':Milestone', $this->Milestone, PDO::PARAM_STR);

			  $query->bindValue(':Close_Date', $this->Close_Date, PDO::PARAM_STR);

			  $query->bindValue(':Deal_Source', $this->Deal_Source, PDO::PARAM_STR);

			  $query->bindValue(':Related_To', $this->Related_To, PDO::PARAM_STR);

			  $query->bindValue(':Description', $this->Description, PDO::PARAM_STR);

			  

			  

			  $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);

			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			  $query->bindValue(':myid', $this->id, PDO::PARAM_STR);

			   $insert=$query->execute();

			   

			   if($insert)

			   	{

			   	 

			    

			   }

			// }

		}catch (PDOException $e) {

           echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;

            exit;

        }

		 return $this->id;

		 // print_r($this->id);

		 // die();

	 }





	public function listoffile(){

				

				 $db=new db();

				$id=$_SESSION['UserID'];

				

					$LoginQuery = $db->prepare("SELECT * FROM `deals`JOIN clients on clients.id=deals.Related_To WHERE deals.createdfk=:id");

					$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

					$LoginQuery->execute();

					$result = $LoginQuery->fetchAll();

					$totalData= $LoginQuery->rowCount();

					echo json_encode($result); die; // send data as json format	



				}





}



?>