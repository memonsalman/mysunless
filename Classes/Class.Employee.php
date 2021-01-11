<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Employee{

	

	public $id;

	public $username;

	public $usertype;

    public $firstname;

    public $lastname;

    public $phonenumber;

    public $email;

    public $Password;

    public $companyname;

    public $companytype;

    public $companywebsite; 

	public $primaryaddress;

	public $secondaryaddress;

	public $city;

	public $state;

	public $country;

	public $zipcode;

	public $userimg;

	public $datecreated;

	public $datelastupdated;

	public $confirm_password;

	public $newpassword;

	public $sid;

	public $UsersLimit2;

	public $timetable;

	public function __construct($id = "new"){

		$db = new db();

		$this->id = $id;

		$this->username = "";

		$this->usertype = "";

		$this->firstname = "";

		$this->lastname = "";

		$this->phonenumber = "";

		$this->email = "";

		$this->Password = "";

		$this->companyname = "";

		$this->companytype = "";

		$this->companywebsite = "";

		$this->primaryaddress = "";

		$this->secondaryaddress = "";

		$this->City = "";

		$this->State = "";

		$this->country = "";

		$this->zipcode = "";

		$this->userimg = "";

		$this->datecreated = "";

		$this->datelastupdated = "";

		$this->confirm_password = "";

		$this->newpassword = "";

		$this->sid = "";

		$this->UsersLimit2 = "";

		$this->timetable = "";

		if ($id == "new") {

				$this->usertype = $_POST["usertype"];

				$this->username = $_POST["username"];

				$this->firstname = $_POST["firstname"];

				$this->lastname = $_POST["lastname"];

				$this->phonenumber = $_POST["phonenumber"];

				$this->email = $_POST["email"];

				$this->Password = $_POST["Password"];

				$this->companyname = $_POST["companyname"];

				$this->companytype = $_POST["companytype"];

				$this->companywebsite = $_POST["companywebsite"];

				$this->primaryaddress = $_POST["primaryaddress"];

				$this->secondaryaddress = $_POST["secondaryaddress"];

				$this->zipcode = $_POST["zipcode"];

				$this->city = $_POST["city"];

				$this->state = $_POST["state"];

				$this->country = $_POST["country"];

				$this->sid = $_POST["sid"];

				$this->UsersLimit2 = $_POST["UsersLimit"];

				$this->timetable = $_POST["timetable"];

				$this->isactive = 1;

				$this->datecreated = date("Y-m-d H:i:s");

				$this->datelastupdated = date("Y-m-d H:i:s");

				$this->adminid = $_SESSION["UserID"];

			

		}else {

			try {

                $query = $db->prepare("SELECT * FROM `users` WHERE id=:id");

                $query->bindValue(':id', $id, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

					echo $e->getMessage();

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->usertype = $info["usertype"];

				$this->username = $info["username"];

				$this->firstname = $info["firstname"];

				$this->lastname = $info["lastname"];

				$this->phonenumber = $info["phonenumber"];

				$this->email = $info["email"];

				$this->password = $info["password"];

				$this->companyname = $info["companyname"]; 	

				$this->companytype = $info["companytype"];

				$this->companywebsite = $info["companywebsite"];

				$this->primaryaddress = $info["primaryaddress"];

				$this->secondaryaddress = $info["secondaryaddress"];

				$this->zipcode = $info["zipcode"];

				$this->city = $info["city"];

				$this->state = $info["state"];

				$this->country = $info["country"];

				$this->sid = $info["sid"];

				$this->timetable = $info["timetable"];

				$this->UsersLimit2 = $info["UsersLimit"];

				$this->datecreated = $info["created_at"];

				$this->datelastupdated = date("Y-m-d H:i:s");

				$this->adminid = $info["adminid"];

				$this->isactive = 1;

				$this->userimg =$info["userimg"];

			}

		}

		

	}

	public function insertDataEmailsetting($lastInsId){
			$db = new db();
			$username = $this->username;
            $email = $this->email;
            $userid = $lastInsId;
            $insert_data_email=$db->prepare("INSERT INTO EmailSetting(fmail,fname,UserID) VALUES(:email,:username,:userid)");
            $insert_data_email->bindparam(":username",$username);
            $insert_data_email->bindparam(":email",$email);
            $insert_data_email->bindparam(":userid",$userid);
            $insert_data_email->execute();	
	}

	 public function commit(){

		  $db = new db();

        if ($this->id == "new") {

            try {

            	

                $query = $db->prepare("INSERT INTO `users` (`username`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();
                $lastInsId = $this->id;
                $this->insertDataEmailsetting($lastInsId);
               
               

            } catch (PDOException $e) {
				echo $e;
				die;
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

			

            $query = $db->prepare("UPDATE `users` SET 

			 `username`=:username,

			 `adminid`=:adminid,

			 `firstname`=:firstname,

			 `lastname`=:lastname,

			 `password`=:Password,

			 `phonenumber`=:phonenumber,

			 `email`=:email,

			 `companyname`=:companyname,

			 `companytype`=:companytype,

			 `companywebsite`=:companywebsite,

			 `primaryaddress`=:primaryaddress,

			 `secondaryaddress`=:secondaryaddress,

			 `zipcode`=:zipcode,

			 `city`=:city,

			 `state`=:state,

			 `country`=:country,

			 `usertype`=:usertype,

			 `sid`=:sid,

			 `timetable`=:timetable,

			 `UsersLimit`=:UsersLimit2,

			 `created_at`=:datecreated,

			 `updated_at`=:datelastupdated,

			 `status`=:isactive,

			`userimg`=:userimg WHERE id=:id");

			  $query->bindValue(':username', $this->username, PDO::PARAM_STR);

			  $query->bindValue(':adminid', $this->adminid, PDO::PARAM_STR);

			  $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);

			  $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);

			  $query->bindValue(':Password', $this->Password, PDO::PARAM_STR);

			  $query->bindValue(':phonenumber', $this->phonenumber, PDO::PARAM_STR);

			  $query->bindValue(':email', $this->email, PDO::PARAM_STR);

			  $query->bindValue(':companyname', $this->companyname, PDO::PARAM_STR);

			  $query->bindValue(':companytype', $this->companytype, PDO::PARAM_STR);

			  $query->bindValue(':companywebsite', $this->companywebsite, PDO::PARAM_STR);

			  $query->bindValue(':primaryaddress', $this->primaryaddress, PDO::PARAM_STR);

			  $query->bindValue(':secondaryaddress', $this->secondaryaddress, PDO::PARAM_STR);

			  $query->bindValue(':zipcode', $this->zipcode, PDO::PARAM_STR);

			  $query->bindValue(':city', $this->city, PDO::PARAM_STR);

			  $query->bindValue(':state', $this->state, PDO::PARAM_STR);

			  $query->bindValue(':country', $this->country, PDO::PARAM_STR);

			  $query->bindValue(':usertype', $this->usertype, PDO::PARAM_STR);

			  $query->bindValue(':sid', $this->sid, PDO::PARAM_STR);

			  $query->bindValue(':timetable', $this->timetable, PDO::PARAM_STR);

			  $query->bindValue(':UsersLimit2', $this->UsersLimit2, PDO::PARAM_STR);

			  $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			  $query->bindValue(':userimg', $this->userimg, PDO::PARAM_STR);

			  $query->bindValue(':id', $this->id, PDO::PARAM_STR); 

			   $query->execute();

		}catch (PDOException $e) {

           echo $e->getMessage();

            exit;

        }

		

		return $this->id;

	 

	}	


	public function commit2($id,$finaldata)
	{
				$db = new db();
			$id = $_POST['userid'];
			$timetable = $finaldata;
			
			$query = $db->prepare("UPDATE `users` SET `timetable`=:timetable WHERE id=:id");
			$query->bindValue(':id', $id, PDO::PARAM_STR);
			$query->bindValue(':timetable', $timetable, PDO::PARAM_STR);
			  $query->execute();

	}

	public function ActivitesCount($newemployee)

	 {

	 	$db = new db();

		$EmployeeCreate=$newemployee;

		$Createid=$_SESSION['UserID'];

		$CreatedTime=date("Y-m-d");

		$insert_data_ac=$db->prepare("INSERT INTO CountActivites(EmployeeCreate,Createid,CreatedTime) VALUES(:EmployeeCreate,:Createid,:CreatedTime)");

	            

	            $insert_data_ac->bindparam(":EmployeeCreate",$EmployeeCreate);

	            $insert_data_ac->bindparam(":Createid",$Createid);

	            $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);

	            $insert_data_ac->execute();

	 }


	 
	 public function empgethours()
	 {
	 		$db = new db();
	 		$id = $_POST['Employeeid'];	
	 		$query = $db->prepare("SELECT * FROM `Emptimetable` WHERE createdfk=:id");
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();	
			$info = $query->fetch(PDO::FETCH_ASSOC);	
			echo  json_encode(["resonse"=>$info]);die;
	 }


	 public function AddCompanyInformation($newemployee)
	 {
	 		$db = new db();

	 		$cName=$this->companyname;

	 		$idd = $newemployee;

		$cType = $this->companytype;

		$cWebsite = $this->companywebsite;

                                                                    
       

		$cPrimaryAdd=$this->primaryaddress;

		$cSecAdd = $this->secondaryaddress;

		$cCity = $this->City;

		$cState = $this->State;

		$cCountry =$this->country;

		$cZipcode = $this->zipcode;
		$isactive = 1;


		$CreatedTime=date("Y-m-d H:i:s");

		$insert_data_ac=$db->prepare("INSERT INTO CompanyInformation(CompanyName,Address,Zip,City,State,Country,datecreated,datelastupdated,createdfk,updatedfk,isactive) VALUES(:cName,:cPrimaryAdd,:cZipcode,:cCity,:cState,:cCountry,:CreatedTime,:CreatedTime,:idd,:idd,:isactive)");

	            

	  
 		$insert_data_ac->bindparam(":cName",$cName);

        $insert_data_ac->bindparam(":cPrimaryAdd",$cPrimaryAdd);

        $insert_data_ac->bindparam(":cZipcode",$cZipcode);
        $insert_data_ac->bindparam(":cCity",$cCity);
        $insert_data_ac->bindparam(":cState",$cState);
        $insert_data_ac->bindparam(":cCountry",$cCountry);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->bindparam(":idd",$idd);
        $insert_data_ac->bindparam(":idd",$idd);
        $insert_data_ac->bindparam(":isactive",$isactive);
          $insert_data_ac->execute();

	         

	 }

	  public function checkEmpCount()
	 {
		$UserId=$_SESSION['UserID'];
		

	 		$db = new db();
	 		
	 		$query = $db->prepare("SELECT COUNT(adminid) as totalEmp,(SELECT employeeLimit from users WHERE id=:id) as employeeLimit from users WHERE adminid=:id and usertype='employee' ");
			$query->bindValue(':id', $UserId, PDO::PARAM_INT);
			$query->execute();	
			$info = $query->fetch(PDO::FETCH_ASSOC);	

			return $info;die;
	 }
	

}



?>