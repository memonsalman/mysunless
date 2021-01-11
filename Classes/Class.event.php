<?php

    
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class event{

    public $id;
    public $UserID;
    public $title;
    public $FirstName;
    public $LastName;
    public $Phone;
    public $Email;
    public $EventDate;
    public $end_date;
    // public $EventTime;
    //    public $EventTime2;
    public $eventstatus;
    public $Address;
    public $Zip;
    public $City;
    public $State;
    public $country;
    public $CostOfService;
    public $EmailInstruction;
    public $ServiceName;
    public $cid;
    public $ServiceProvider;
    public $Location_radio;
    public $Accepted;    

    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->UserID = "";
        $this->title = "";
        $this->FirstName = "";
        $this->LastName = "";
        $this->Phone = "";
        $this->Email = "";
        $this->EventDate = "";
        $this->end_date = "";
        // $this->EventTime = "";
        // $this->EventTime2 = "";
        $this->eventstatus= "";
        $this->Address = "";
        $this->Zip = "";
        $this->City = "";
        $this->State = "";
        $this->country = "";
        $this->CostOfService = "";
        $this->EmailInstruction = "";
        $this->ServiceName = "";
        $this->cid = "";
        $this->Location_radio = "";
        $this->ServiceProvider = "";
        $this->Accepted = ""; 

        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");

            if(isset($_SESSION['usertype']))
            {
                    if($_SESSION['usertype']=="Admin")
                    {
                      $this->createdfk = $_REQUEST["newlistofSubscriber"];  
                    }
                    else
                    {
                        $this->createdfk = $_POST["UserID"];
                    }    
            }
            else
            {
                    $this->createdfk = $_POST["UserID"];

            }

            if(isset($_POST['bookout']))
            {
               $this->updatedfk = $_POST["UserID"];
                $this->isactive = 1;
               $this->Accepted = 0; 
            }
            else
            {
                $this->updatedfk = $_POST["UserID"];
                $this->isactive = 1;
                $this->Accepted = 1; 
            }

        }else {
            try {
           
                $query = $db->prepare("SELECT * FROM event WHERE id=:myid");
                $query->bindValue(':myid', $myid);
                $query->execute();
            } catch (PDOException $e) {
               
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
            
               
               $eDate = $info["EventDate"];

               
               // $date = DateTime::createFromFormat('d-Y-m H:ia', $eDate);
                // $newEventDate = $date->format('Y-m-d H:ia');

               
                $this->UserID = $info["UserID"];
                $this->title = $info["title"];
                $this->FirstName = $info["FirstName"];
                $this->LastName = $info["LastName"];
                $this->Phone = $info["Phone"];
                $this->Email = $info["Email"];
                $this->EventDate = $newEventDate;
                $this->end_date = $info["end_date"];
                $this->ServiceProvider = $info["ServiceProvider"];
                // $this->EventTime2 = $info["EventTime2"];
                $this->eventstatus = $info["eventstatus"] ;
                $this->Address = $info["Address"];
                $this->Zip = $info["Zip"];
                $this->City = $info["City"];
                $this->State = $info["State"];
                $this->country = $info["country"];
                $this->CostOfService = $info["CostOfService"];
                $this->EmailInstruction = $info["EmailInstruction"];
                $this->ServiceName = $info["ServiceName"];
                $this->cid = $info["cid"];
                $this->datecreated = $info["datecreated"];
                $this->datelastupdated = date("Y-m-d H:i:s");
                $this->createdfk = $info["createdfk"];
                $this->updatedfk = $info["updatedfk"];
                $this->Location_radio = $info["Location_radio"];
                $this->isactive = 1;
                $this->Accepted = 1; 

            }
        }
    }
    public function commit(){
        $db = new db();
        if ($this->id == "new") {
            try {
                $query = $db->prepare("INSERT INTO `event` (`FirstName`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                    
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {

          
            $query = $db->prepare("UPDATE event SET 
            `UserID`=:UserID,
            `title`=:title,
            `FirstName`=:FirstName,
            `LastName`=:LastName,
            `Phone`=:Phone,
            `Email`=:Email,
            `EventDate`=:EventDate,
            `end_date`=:end_date,
            `ServiceProvider`=:ServiceProvider,
            -- `EventTime2`=:EventTime2,
            `eventstatus`=:eventstatus,
            `Address`=:Address,
            `Zip`=:Zip,
            `City`=:City,
            `State`=:State,
            `country`=:country,
            `CostOfService`=:CostOfService,
            `EmailInstruction`=:EmailInstruction,
            `ServiceName`=:ServiceName,
            `cid`=:cid,
            `datecreated`=:datecreated,
            `datelastupdated`=:datelastupdated,
            `createdfk`=:createdfk,
            `updatedfk`=:updatedfk,
            `Location_radio`=:Location_radio,
            `isactive`=:isactive,   
            `Accepted`=:Accepted WHERE id=:myid");
            $query->bindValue(':UserID', $this->UserID, PDO::PARAM_STR);	
            $query->bindValue(':title', $this->title, PDO::PARAM_STR);	
            $query->bindValue(':FirstName', $this->FirstName, PDO::PARAM_STR);
            $query->bindValue(':LastName', $this->LastName, PDO::PARAM_STR);
            $query->bindValue(':Phone', $this->Phone, PDO::PARAM_STR);
            $query->bindValue(':Email', $this->Email, PDO::PARAM_STR);
            $query->bindValue(':EventDate', $this->EventDate, PDO::PARAM_STR);
            $query->bindValue(':end_date', $this->end_date, PDO::PARAM_STR);
            $query->bindValue(':ServiceProvider', $this->ServiceProvider, PDO::PARAM_STR);
            // $query->bindValue(':EventTime2', $this->EventTime2, PDO::PARAM_STR);
            $query->bindValue(':eventstatus', $this->eventstatus, PDO::PARAM_STR);
            $query->bindValue(':Address', $this->Address, PDO::PARAM_STR);
            $query->bindValue(':Zip', $this->Zip, PDO::PARAM_STR);
            $query->bindValue(':City', $this->City, PDO::PARAM_STR);
            $query->bindValue(':State', $this->State, PDO::PARAM_STR);
            $query->bindValue(':country', $this->country, PDO::PARAM_STR);
            $query->bindValue(':CostOfService', $this->CostOfService, PDO::PARAM_STR);
            $query->bindValue(':EmailInstruction', $this->EmailInstruction, PDO::PARAM_STR);
            $query->bindValue(':ServiceName', $this->ServiceName, PDO::PARAM_STR);
            $query->bindValue(':cid', $this->cid, PDO::PARAM_STR);
            $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
            $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);
            $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
            $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
            $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);
            $query->bindValue(':Location_radio', $this->Location_radio, PDO::PARAM_STR);
            $query->bindValue(':Accepted', $this->Accepted, PDO::PARAM_STR);
            
            $query->bindValue(':myid', $this->id, PDO::PARAM_STR);
            $query->execute();
        }catch (PDOException $e) {
            echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;
            exit;
        }
        //return $this->id;
        return $this->id;
    }
    public function ActivitesCount($newevent)
    {
        $db = new db();
        $AppointmentCreate=$newevent;

        if(isset($_SESSION['UserID']))
        {
           $Createid=$_SESSION['UserID'];

        }
        else
        {
                
           $Createid = $_POST["UserID"];

        }

        $CreatedTime=date("Y-m-d");
        $insert_data_ac=$db->prepare("INSERT INTO CountActivites(AppointmentCreate,Createid,CreatedTime) VALUES(:AppointmentCreate,:Createid,:CreatedTime)");
        $insert_data_ac->bindparam(":AppointmentCreate",$AppointmentCreate);
        $insert_data_ac->bindparam(":Createid",$Createid);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->execute();
    }



    public function selectNotAcceptAppointment()
    {
       
         $db = new db();
            
          if($_SESSION['usertype']!='Admin'){
            $id = $_SESSION['UserID'];
            $id = " and (createdfk IN (select id from users where id=$id or adminid=$id or sid=$id) or ServiceProvider=$id) ";
          }else{
            $id = '';
          }

            $query = $db->prepare("SELECT event.*,concat(event.Firstname,' ',event.Lastname) as ClientName,concat(users.firstname,' ',users.lastname) as serviceprovider FROM `event` join users on event.ServiceProvider=users.id WHERE Accepted='0' $id ");
            $execRec = $query->execute();
            // print_r($query);
            // die();
            $all=$query->fetchAll(PDO::FETCH_ASSOC);

           return json_encode($all);die;
    }


    public function selectSpecificAppointment($userId)
    {   

       
           $db = new db();
            $userID = $userId;


            $query1 = $db->prepare("SELECT * FROM `event` WHERE id=:myid");
            $query1->bindValue(':myid',$userId);
           
            $query1->execute();
            $oneRes=$query1->fetchAll(PDO::FETCH_ASSOC);
            
            return $oneRes;die;
    }
    

    public function approveAppointment($userId,$statusEvent){

         $db = new db();

        $updUserId = $userId;
        $accepted = $statusEvent;


        $query = $db->prepare("UPDATE event SET eventstatus='pending',`Accepted`=:Accepted where id=:myid");
        $query->bindValue(':Accepted', $accepted, PDO::PARAM_STR);
            
        $query->bindValue(':myid', $updUserId, PDO::PARAM_STR);
        $res =  $query->execute();
        
        return $res;die;

    }

    

    public function selectAppoHistory($ueventid)
    {
       
           $db = new db();
            @$id=base64_decode($ueventid);
            

            $stmt_appo= $db->prepare("SELECT CONCAT(users.firstname ,' ', users.lastname ) AS serviceProviderName ,users.userimg,event.eventstatus, event.ServiceProvider,event.datecreated as newdate,event.title,event.EventDate,event.cid,Service.ServiceName,event.id as eid,OrderMaster.id as OrderID FROM `event` left JOIN OrderMaster on event.id=OrderMaster.eid JOIN Service on event.ServiceName=Service.id JOIN users on event.ServiceProvider=users.id WHERE event.isactive=1 and event.cid=:id"); 
            // $stmt_appo= $db->prepare("SELECT CONCAT(users.firstname ,' ', users.lastname ) AS serviceProviderName ,users.userimg,event.eventstatus, event.ServiceProvider,event.datecreated as newdate,event.title,event.EventDate,event.cid,Service.ServiceName,event.id as eid
            //     FROM `event` 
            //     JOIN Service on event.ServiceName=Service.id
            //     JOIN users on event.ServiceProvider=users.id 
            //     WHERE cid=:id"); 
            $stmt_appo->bindParam(':id', $id);
            $stmt_appo->execute();
            $result_appo = $stmt_appo->fetchAll(); 

            return json_encode($result_appo);die;
    }


    public function selectNoteHistory($unoteid)
    {
       
           $db = new db();
            @$id=base64_decode($unoteid);
            

            $stmt_note= $db->prepare("SELECT note.noteTitle,note.noteDetail,note.datecreated,note.id as noteId,CONCAT(users.firstname ,' ', users.lastname ) AS noteCreaterName,users.userimg FROM `noteandclient` 
                LEFT JOIN note ON noteandclient.noteid=note.id
                 JOIN users ON note.createdfk = users.id
            WHERE noteandclient.clientid=:id AND noteandclient.active='1'"); 

            
            $stmt_note->bindParam(':id', $id);
            $stmt_note->execute();
            $result_note = $stmt_note->fetchAll(); 

            return json_encode($result_note);die;
    }


     public function selectCommunicateHistory($uComid)
    {
       
           $db = new db();
            @$id=base64_decode($uComid);
            

            $stmt_communate= $db->prepare("SELECT FullCom.*,CONCAT(users.firstname ,' ', users.lastname ) AS communicatorName ,users.userimg FROM `FullCom` JOIN clients ON FullCom.cid = clients.id JOIN users on FullCom.Createid=users.id WHERE cid=:id"); 

            
            $stmt_communate->bindParam(':id', $id);
            $stmt_communate->execute();
            $result_communate = $stmt_communate->fetchAll(); 

            return json_encode($result_communate);die;
    }

    

    public function selectOrderHistory($uOrderid)
    {
       
           $db = new db();
            @$id=base64_decode($uOrderid);
            

            $stmt_order = $db->prepare("SELECT DISTINCT OrderMaster.Noofvisit,OrderMaster.id as orid,OrderMaster.datecreated as odatecreated ,OrderMaster.InvoiceNumber,OrderMaster.TotalseriveAmount,OrderMaster.TotalProductAmount,OrderMaster.gServicePrice,OrderMaster.TotalMembershipAmount,Service.ServiceName,Product.ProductTitle,MemberPackage.Name,CONCAT(users.firstname ,' ', users.lastname ) AS orderCreatorName ,users.userimg
                FROM `OrderMaster`
                 LEFT  JOIN `OrderServic` ON OrderMaster.ServiceName = OrderServic.SeriveId LEFT JOIN `Service` ON OrderServic.SeriveId = Service.id 
                LEFT JOIN `OrderProduct` ON OrderMaster.ProdcutName = OrderProduct.ProdcutId LEFT JOIN `Product` ON OrderProduct.ProdcutId = Product.id 
                 LEFT  JOIN `OrderMembership` ON OrderMaster.MembershipName = OrderMembership.MembershipId LEFT JOIN `MemberPackage` ON OrderMembership.MembershipId = MemberPackage.id
                JOIN users ON OrderMaster.createdfk = users.id
                WHERE OrderMaster.cid=:id AND OrderMaster.payment_status = 'CAPTURED' ORDER by odatecreated DESC"); 

            
            $stmt_order->bindParam(':id', $id);
            $stmt_order->execute();
            $result_order = $stmt_order->fetchAll(); 

            return json_encode($result_order);die;
    }



     public function selectPackageHistory($uPackageid)
    {
       
           $db = new db();
            @$id=base64_decode($uPackageid);
            
           
            $stmt_package = $db->prepare("SELECT OrderMembership.Noofvisit,OrderMembership.id,OrderMembership.OrderId as orid,OrderMembership.OrderTime as odatecreated,MemberPackage.Name,CONCAT(users.firstname ,' ', users.lastname ) AS packageCreatorName ,users.userimg 
                FROM `OrderMembership`
                 JOIN `MemberPackage` ON OrderMembership.MembershipId = MemberPackage.id
                 JOIN users ON OrderMembership.createdfk = users.id
                JOIN `OrderPayment` ON OrderPayment.OrderId = OrderMembership.OrderId WHERE OrderMembership.Cid=:id AND OrderPayment.payment_status='CAPTURED'"); 

            
            $stmt_package->bindParam(':id', $id);
            $stmt_package->execute();
            $result_package = $stmt_package->fetchAll(); 

            return json_encode($result_package);die;
    }


       public function selectFileUploadHistory($uFileuploadId)
    {
       
           $db = new db();
            @$id=base64_decode($uFileuploadId);
            
           
            $stmt_files = $db->prepare("SELECT * FROM `attechment` WHERE UserID=:id ORDER BY `attechment`.`datecreated` DESC"); 

            
            $stmt_files->bindParam(':id', $id);
            $stmt_files->execute();
            $result_files = $stmt_files->fetchAll(); 

            return json_encode($result_files);die;
    }


    public function selectEventPrintInfo($priEveId)
    {
        $db = new db();
            @$id=$priEveId;
            

            $stmt_appo= $db->prepare("SELECT CONCAT(users.firstname ,' ', users.lastname ) AS serviceProviderName ,users.userimg,users.mysign,users.order_note,users.username,CompanyInformation.compimg,event.eventstatus, event.ServiceProvider,event.datecreated as newdate,event.title,event.EventDate,event.cid,Service.ServiceName,Service.Price,event.id as eid,CONCAT(event.firstname ,' ', event.lastname ) AS customerName,
                event.Phone,event.Email,CONCAT(event.Address,', ',event.City,', ',event.Zip,' - ',event.country) as address
                FROM `event` 
                JOIN Service on event.ServiceName=Service.id
                JOIN users on event.ServiceProvider=users.id 
                JOIN CompanyInformation on CompanyInformation.createdfk=users.id
                WHERE event.id=:id"); 
            $stmt_appo->bindParam(':id', $id);
            $stmt_appo->execute();
            $result_appo = $stmt_appo->fetchAll(); 

          
            return json_encode($result_appo);die;
    }

    public function fileUploadDelete($fileDelId)
    {
            
             $db = new db();
            @$id=$fileDelId;
            
            $SelFile = $db->prepare("select document from `attechment` where id=:id");
              $SelFile->bindValue(":id",$id,PDO::PARAM_INT);
              $filesel=$SelFile->execute();
              $result = $SelFile->fetch(PDO::FETCH_ASSOC);
              $docName = $result['document'];
            

              if($filesel > 0)
              {
              
                $path = DOCUMENT_ROOT.ESUB."/assets/ClientDocs/".$docName;
                unlink($path);
                 $DeleteFile = $db->prepare("delete from `attechment` where id=:id");
                  $DeleteFile->bindValue(":id",$id,PDO::PARAM_INT);
                  $deletefile=$DeleteFile->execute();
                  if($deletefile)
                  {
                    return json_encode(["resonse"=>'File Successfully Remove From List']);die;
                  }
                  else
                  {
                    return  json_encode(["error"=>'done']);die;
                  }
              }
            

           

    }
}
?>