<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class Client{
    public $id;
    public $FirstName;
    public $LastName;
    public $Phone;
    public $email;
    public $ClientImg;
    public $ProfileImg;
    public $Solution;
    public $PrivateNotes; 
    public $Address;
    public $Zip;
    public $City;
    public $State;
    public $Country;
    public $sid;
    public $SelectPackage;
    public $employeeSold;
    public $package_sd;
    public $package_ed;
    // public $fileName;
    // public $document;
    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->FirstName = "";
        $this->LastName = "";
        $this->Phone = "";
        $this->email = "";
        $this->ClientImg = "";
        $this->ProfileImg = "";
        $this->Solution = "";
        $this->PrivateNotes = "";
        $this->Address = "";
        $this->Zip = "";
        $this->City = "";
        $this->State = "";
        $this->Country = "";
        $this->sid = "";
        $this->SelectPackage = "";
        $this->employeeSold = "";
        $this->package_sd = "";
        $this->package_ed = "";
        // $this->fileName = "";
        //$this->document = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");

            if(isset($_SESSION['usertype']) && $_SESSION['usertype']=="Admin")
            {
              $this->createdfk = $_REQUEST["newlistofSubscriber2"];  
            }
            else
            {
            $this->createdfk = (isset($_POST['REF_UserID']) && $_POST['REF_UserID']!="")?$_POST['REF_UserID']:$_SESSION['UserID'];
            }

            
            $this->updatedfk = (isset($_POST['REF_UserID']) && $_POST['REF_UserID']!="")?$_POST['REF_UserID']:$_SESSION['UserID'];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM clients WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->FirstName = $info["FirstName"];
                $this->LastName = $info["LastName"];
                $this->Phone = $info["Phone"];
                $this->email = $info["email"];
                $this->ClientImg = $info["ClientImg"]; 
                $this->ProfileImg = $info["ProfileImg"];
                $this->Solution = $info["Solution"];
                $this->PrivateNotes = $info["PrivateNotes"];
                $this->Address = $info["Address"];
                $this->Zip = $info["Zip"];
                $this->City = $info["City"];
                $this->State = $info["State"];
                $this->Country = $info["Country"];
                $this->sid = $info["sid"];
                $this->SelectPackage = $info["SelectPackage"];
                $this->employeeSold = $info["employeeSold"];
                $this->package_sd = $info["package_sd"];
                $this->package_ed = $info["package_ed"];
                // $this->fileName = $info["fileName"];
                // $this->document = $info["document"];
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
                $query = $db->prepare("INSERT INTO `clients` (`FirstName`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
               
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE clients SET 
            `FirstName`=:FirstName,
            `LastName`=:LastName,
            `Phone`=:Phone,
            `email`=:email,
            `ClientImg`=:ClientImg,
            `ProfileImg`=:ProfileImg,
            `Solution`=:Solution,
            `PrivateNotes`=:PrivateNotes,
            `Address`=:Address,
            `Zip`=:Zip,
            `City`=:City,
            `State`=:State,
            `Country`=:Country,
            `sid`=:sid,
            `SelectPackage`=:SelectPackage,
            `employeeSold`=:employeeSold,
            `package_sd`=:package_sd,
            `package_ed`=:package_ed,
            -- `fileName`=:fileName,
            -- `document`=:document,
            `datecreated`=:datecreated,
            `datelastupdated`=:datelastupdated,
            `createdfk`=:createdfk,
            `updatedfk`=:updatedfk,
            `isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':FirstName', $this->FirstName, PDO::PARAM_STR);
            $query->bindValue(':LastName', $this->LastName, PDO::PARAM_STR);
            $query->bindValue(':Phone', $this->Phone, PDO::PARAM_STR);
            $query->bindValue(':email', $this->email, PDO::PARAM_STR);
            $query->bindValue(':ClientImg', $this->ClientImg, PDO::PARAM_STR);
            $query->bindValue(':ProfileImg', $this->ProfileImg, PDO::PARAM_STR);
            $query->bindValue(':Solution', $this->Solution, PDO::PARAM_STR);
            $query->bindValue(':PrivateNotes', $this->PrivateNotes, PDO::PARAM_STR);
            $query->bindValue(':Address', $this->Address, PDO::PARAM_STR);
            $query->bindValue(':Zip', $this->Zip, PDO::PARAM_STR);
            $query->bindValue(':City', $this->City, PDO::PARAM_STR);
            $query->bindValue(':State', $this->State, PDO::PARAM_STR);
            $query->bindValue(':Country', $this->Country, PDO::PARAM_STR);
            $query->bindValue(':sid', $this->sid, PDO::PARAM_STR);
            $query->bindValue(':SelectPackage', $this->SelectPackage, PDO::PARAM_STR);
            $query->bindValue(':employeeSold', $this->employeeSold, PDO::PARAM_STR);
            $query->bindValue(':package_sd', $this->package_sd, PDO::PARAM_STR);
            $query->bindValue(':package_ed', $this->package_ed, PDO::PARAM_STR);
            // $query->bindValue(':fileName', $this->fileName, PDO::PARAM_STR);
            // $query->bindValue(':document', $this->document, PDO::PARAM_STR);
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
    public function ActivitesCount($newclient)
    {
        $db = new db();
        $ClientCreate=$newclient;
        $Createid=(isset($_POST['REF_UserID']) && $_POST['REF_UserID']!="")?$_POST['REF_UserID']:$_SESSION['UserID'];
        $CreatedTime=date("Y-m-d");
        $insert_data_ac=$db->prepare("INSERT INTO CountActivites(ClientCreate,Createid,CreatedTime) VALUES(:ClientCreate,:Createid,:CreatedTime)");
        $insert_data_ac->bindparam(":ClientCreate",$ClientCreate);
        $insert_data_ac->bindparam(":Createid",$Createid);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->execute();
    }
    public function docattechment()
    {
        $db = new db();

        $datecreated1 = date("Y-m-d H:i:s");
        $createdfk = $_SESSION["UserID"];
        $updatedfk = $_SESSION["UserID"];
        $isactive = 1;
        $query = $db->prepare("INSERT INTO `attechment` (`UserID`,`fileName`,`document`,`datecreated`,`createdfk`,`updatedfk`,`isactive`) VALUES ( :clinetid,:fileName, :document, :datecreated1, :createdfk, :updatedfk, :isactive)");
        $query->bindValue(':clinetid', $this->clinetid, PDO::PARAM_STR);
        $query->bindValue(':fileName', $this->fileName, PDO::PARAM_STR);
        $query->bindValue(':document', $this->document, PDO::PARAM_STR);
        $query->bindValue(':datecreated1',$datecreated1, PDO::PARAM_STR);
        $query->bindValue(':createdfk', $createdfk, PDO::PARAM_STR);
        $query->bindValue(':updatedfk', $updatedfk, PDO::PARAM_STR);
        $query->bindValue(':isactive', $isactive, PDO::PARAM_STR);
        $query->execute();
        $this->id = $db->lastInsertId();	
    }


    
    public function listoffile(){
        $requestData= $_REQUEST;
        $columns = array( 
            0 =>'fileName', 
            1 => 'document',
            2=>'id',
        );
        $db=new db();
        $id=$_SESSION['ClientID'];
        if(empty($requestData['search']['value'])) 
        {
            $LoginQuery = $db->prepare("SELECT * FROM `attechment` WHERE UserID=:id");
            $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
            $LoginQuery->execute();
            $result = $LoginQuery->fetchAll();
            $totalData= $LoginQuery->rowCount();
            $totalFiltered = $totalData;
        }	
        $LoginQuery2 = $db->prepare("SELECT id FROM `attechment` WHERE UserID=:id ");
        $LoginQuery2->bindParam(':id', $id, PDO::PARAM_INT);
        $LoginQuery2->execute();
        $a=$totalData2= $LoginQuery2->rowCount();
        if(!empty($requestData['search']['value'])) 
        {
            $serdata=$requestData['search']['value'];
            $LoginQuery = "SELECT * FROM `attechment` WHERE UserID=:id AND (`fileName` LIKE '%$serdata%') OR (`document` LIKE '%$serdata%')";
            // $LoginQuery .="and (fileName LIKE '".$serdata."%'";
            // 		  $LoginQuery .="or document LIKE '".$serdata."%' )";
            $Val = $db->prepare($LoginQuery);
            $Val->bindValue(":id", $id, PDO::PARAM_INT);
            $Val->execute();
            $result = $Val->fetchAll();
            $totalFiltered=$Val->rowCount();	
            $LoginQuery .=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].",".$requestData['length']." "; 
            $JoinVal =  $db->prepare($LoginQuery);
            $JoinVal->bindValue(":id", $id, PDO::PARAM_INT);
            $JoinVal->execute();
            $result = $JoinVal->fetchAll();
            $totalData= $JoinVal->rowCount();
        }
        $data = array();
        foreach($result as $key => $row){		
            $nestedData=array(); 			
            $nestedData[] = $row['document'];
            $nestedData[] = $row['fileName'];
            $nestedData[] = $row['id'];
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal"    => intval( $totalData ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $data   // total data array
        );
        echo json_encode($json_data); die; // send data as json format	
    }
}
?>