<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Client{
    public $id;
    public $CompanyName;
    public $compimg;
    public $compimg2;
    public $Phone;
    public $email;
    public $Address;
    public $Zip;
    public $City;
    public $State;
    public $Country;
    public $ctheme;
    public $customwidget;
    public $sales_tax;  
    public $booking_endpoint;	

    // public $fileName;
    // public $document;
    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->CompanyName = "";
        $this->compimg = "";
        $this->compimg2 = "";
        $this->Phone = "";
        $this->email = "";
        $this->Address = "";
        $this->Zip = "";
        $this->City = "";
        $this->State = "";
        $this->Country = "";
        $this->ctheme ="";
        $this->customwidget ="";
        $this->sales_tax ="";
        $this->booking_endpoint = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM CompanyInformation WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->CompanyName = $info["CompanyName"];
                $this->compimg = $info["compimg"];
                $this->compimg2 = $info["compimg2"];
                $this->Phone = $info["Phone"];
                $this->email = $info["email"];
                $this->Address = $info["Address"];
                $this->Zip = $info["Zip"];
                $this->City = $info["City"];
                $this->State = $info["State"];
                $this->Country = $info["Country"];
                $this->ctheme = $info["ctheme"];
                $this->customwidget = $info["customwidget"];
                $this->datecreated = $info["datecreated"];
                $this->datelastupdated = $info["datelastupdated"];
                $this->createdfk = $info["createdfk"];
                $this->updatedfk = $info["updatedfk"];
                $this->sales_tax = $info["sales_tax"];
                $this->booking_endpoint = $info["booking_endpoint"];
                $this->isactive = 1;
            }
        }
    }
    public function commit(){
        $db = new db();
        if ($this->id == "new") {
            try {
                $query = $db->prepare("INSERT INTO `CompanyInformation` (`CompanyName`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE CompanyInformation SET 
`CompanyName`=:CompanyName,
`compimg`=:compimg,
`compimg2`=:compimg2,
`Phone`=:Phone,
`email`=:email,
`Address`=:Address,
`Zip`=:Zip,
`City`=:City,
`State`=:State,
`Country`=:Country,
`ctheme`=:ctheme,
`customwidget`=:customwidget,
`datecreated`=:datecreated,
`datelastupdated`=:datelastupdated,
`createdfk`=:createdfk,
`updatedfk`=:updatedfk,
`sales_tax`=:sales_tax,
`booking_endpoint`=:booking_endpoint,
`isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':CompanyName', $this->CompanyName, PDO::PARAM_STR);
            $query->bindValue(':compimg', $this->compimg, PDO::PARAM_STR);
            $query->bindValue(':compimg2', $this->compimg2, PDO::PARAM_STR);
            $query->bindValue(':Phone', $this->Phone, PDO::PARAM_STR);
            $query->bindValue(':email', $this->email, PDO::PARAM_STR);
            $query->bindValue(':Address', $this->Address, PDO::PARAM_STR);
            $query->bindValue(':Zip', $this->Zip, PDO::PARAM_STR);
            $query->bindValue(':City', $this->City, PDO::PARAM_STR);
            $query->bindValue(':State', $this->State, PDO::PARAM_STR);
            $query->bindValue(':Country', $this->Country, PDO::PARAM_STR);
            $query->bindValue(':ctheme', $this->ctheme, PDO::PARAM_STR);
            $query->bindValue(':customwidget', $this->customwidget, PDO::PARAM_STR);
            $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
            $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);
            $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
            $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
            $query->bindValue(':sales_tax', $this->sales_tax, PDO::PARAM_STR);
            $query->bindValue(':booking_endpoint', $this->booking_endpoint, PDO::PARAM_STR);
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
    public function docattechment()
    {
        $db = new db();
        $query = $db->prepare("INSERT INTO `attechment` (`UserID`,`fileName`,`document`,`datecreated`,`createdfk`,`updatedfk`,`isactive`,`ctheme`) VALUES ( :clinetid,:fileName, :document, :datecreated, :createdfk, :updatedfk, :isactive,:ctheme)");
        $query->bindValue(':clinetid', $this->clinetid, PDO::PARAM_STR);
        $query->bindValue(':fileName', $this->fileName, PDO::PARAM_STR);
        $query->bindValue(':document', $this->document, PDO::PARAM_STR);
        $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
        $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
        $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
        $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);
        $query->bindValue(':ctheme', $this->ctheme, PDO::PARAM_STR);
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
    public function AddTag()
    {
        // $db = new db();
        // 	 $query = $db->prepare("INSERT INTO `attechment` (`UserID`) VALUES ( :clinetid)");
        //            	  $query->bindValue(':clinetid', $this->clinetid, PDO::PARAM_STR);
        //          	 $query->execute();
        // $this->id = $db->lastInsertId();	
    }
}
?>