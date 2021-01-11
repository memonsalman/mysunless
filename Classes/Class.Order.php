<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
require_once(Classes.'/Class.Datatable.php'); //Used on Server-Side Datatable

class Order{
    public $id;
    public $cid;
    public $eid;
    public $ServiceName;
    public $ServicProvider;
    public $ServiceStartTime;
    public $ServicePrice;
    public $ServiceDiscount;
    public $ServiceDiscoutInParentage;
    public $ServiceFianlPrice;
    public $ProdcutName; 
    public $ProdcutQuality;
    public $ProductPrice;
    public $ProductCostPrice;
    public $ProductTaxPrice;
    public $ProductDiscount;
    public $ProductDiscountInParentage;
    public $ProductFianlPrice;
    public $MembershipName;
    public $MembershipPrice;
    public $MembershipDiscount;
    public $MemberDiscoutInParentage;
    public $MembershipFianlPrice;
    public $TotalOrderAmount;
    public $TotalseriveAmount;


    public $TotalProductAmount;
    public $TotalMembershipAmount;
    public $GetTotalPoint;
    public $UsePoint;
    public $Remainepoints;
    public $sales_tax;
    public $payment_status;
    public $tips;

    public $Noofvisit;
    public $Package_Autonew;
    public $Package_renwal;
    public $Pckage_carryford;
    public $package_expire_date;

    public $gServiceName;
    public $gServicePrice;
    public $gServiceDiscount;
    public $gServiceDiscoutInParentage;
    public $gServiceFianlPrice;
    public $TotalgiftAmount;
    public $giftbal;

    public $pservicepackage;
    public $pservicename;
    public $pvisit;

  

    public function __construct($myid = "new"){
        $db = new db();
        @$this->id = $myid;
        @$this->cid = "";
        @$this->eid = "";
        @$this->ServiceName = "";
        @$this->ServicProvider = "";
        @$this->ServiceStartTime = "";
        @$this->ServicePrice = "";
        @$this->ServiceDiscount = "";
        @$this->ServiceDiscoutInParentage = "";
        @$this->ServiceFianlPrice = "";
        @$this->ProdcutName = "";
        @$this->ProdcutQuality = "";
        @$this->ProductPrice = "";
        @$this->ProductCostPrice = "";
        @$this->ProductTaxPrice = "";
        @$this->ProductDiscount = "";
        @$this->ProductDiscountInParentage = "";
        @$this->ProductFianlPrice = "";
        @$this->MembershipName = "";
        @$this->MembershipPrice = "";
        @$this->MembershipDiscount = "";
        @$this->MemberDiscoutInParentage = "";
        @$this->MembershipFianlPrice = "";
        @$this->TotalOrderAmount = "";
        @$this->TotalseriveAmount = "";
        @$this->TotalProductAmount = "";
        @$this->TotalMembershipAmount = "";
        @$this->GetTotalPoint = "";
        @$this->UsePoint = "";
        @$this->Remainepoints = "";
        @$this->sales_tax = "";
        @$this->payment_status="";
        @$this->tips="";

        @$this->Noofvisit="";
        @$this->Package_Autonew="";
        @$this->Package_renwal="";
        @$this->Pckage_carryford="";
        @$this->package_expire_date="";

        @$this->gServiceName = "";
        @$this->gServicePrice = "";
        @$this->gServiceDiscount = "";
        @$this->gServiceDiscoutInParentage = "";
        @$this->gServiceFianlPrice = "";
        @$this->TotalgiftAmount = "";
        @$this->giftapp = "";

        @$this->pservicepackage = "";
        @$this->pservicename = "";
        @$this->pvisit = "";


     

        // $this->fileName = "";
        //$this->document = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }
        else 
        {
            try 
            {
                $query = $db->prepare("SELECT * FROM OrderMaster WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } 
            catch (PDOException $e) 
            {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                
                
                @$this->cid = $info["cid"];
                //@$this->eid = 0;
                @$this->eid = $info["eid"];
                $this->ServiceName = $info["ServiceName"];
                $this->ServicProvider = $info["ServicProvider"];
                $this->ServiceStartTime = $info["ServiceStartTime"];
                $this->ServicePrice = $info["ServicePrice"];
                $this->ServiceDiscount = $info["ServiceDiscount"]; 
                $this->ServiceDiscoutInParentage = $info["ServiceDiscoutInParentage"];
                $this->ServiceFianlPrice = $info["ServiceFianlPrice"];
                $this->ProdcutName = $info["ProdcutName"];
                $this->ProdcutQuality = $info["ProdcutQuality"];
                $this->ProductPrice = $info["ProductPrice"];
                $this->ProductCostPrice = $info["ProductCostPrice"];
                $this->ProductTaxPrice = $info["ProductTaxPrice"];
                $this->ProductDiscount = $info["ProductDiscount"];
                $this->ProductDiscountInParentage = $info["ProductDiscountInParentage"];
                $this->ProductFianlPrice = $info["ProductFianlPrice"];
                $this->MembershipName = $info["MembershipName"];
                $this->MembershipPrice = $info["MembershipPrice"];
                $this->MembershipDiscount = $info["MembershipDiscount"];
                $this->MemberDiscoutInParentage = $info["MemberDiscoutInParentage"];
                $this->MembershipFianlPrice = $info["MembershipFianlPrice"];
                $this->TotalOrderAmount = $info["TotalOrderAmount"];
                $this->TotalseriveAmount = $info["TotalseriveAmount"];
                $this->TotalProductAmount = $info["TotalProductAmount"];
                $this->TotalMembershipAmount = $info["TotalMembershipAmount"];
                @$this->GetTotalPoint = $info["GetTotalPoint"];
                @$this->UsePoint = $info["UsePoint"];
                @$this->Remainepoints = @$info["Remainepoints"];
                @$this->datecreated = $info["datecreated"];
                @$this->createdfk = $info["createdfk"];
                // @$this->datelastupdated = $info["datelastupdated"];
                // @$this->updatedfk = $info["updatedfk"];
                @$this->datelastupdated = date("Y-m-d H:i:s");
                @$this->updatedfk = $_SESSION['UserID'];
                @$this->sales_tax = $info["sales_tax"];
                @$this->payment_status = $info["payment_status"];
                @$this->tips = $info["tips"];
                @$this->isactive = 1;

                @$this->Noofvisit=$info["Noofvisit"];
                @$this->Package_Autonew=$info["Package_Autonew"];
                @$this->Package_renwal=$info["Package_renwal"];
                @$this->Pckage_carryford=$info["Pckage_carryford"];
                @$this->package_expire_date=$info["package_expire_date"];

                @$this->gServiceName = $info["gServiceName"];
                @$this->gServicePrice = $info["gServicePrice"];
                @$this->gServiceDiscount = $info["gServiceDiscount"]; 
                @$this->gServiceDiscoutInParentage = $info["gServiceDiscoutInParentage"];
                @$this->gServiceFianlPrice = $info["gServiceFianlPrice"];
                @$this->TotalgiftAmount = $info["TotalgiftAmount"];
                @$this->giftapp = $info["giftapp"];                

                @$this->pservicepackage = $info["pservicepackage"];
                @$this->pservicename = $info["pservicename"];
                @$this->pvisit = $info["pvisit"];

            }
        }
    }
    public function commit($cidnew,$cid){

        $db = new db();
        if ($this->id == "new") {
            try {
                //echo $cid;
                //die;
                $query = $db->prepare("INSERT INTO `OrderMaster` (`cid`) VALUES ('$cid')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                // echo $e;
                // die;

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {

            // echo "innn";
            // echo $this->pservicepackage;
            // die;

            $query = $db->prepare("UPDATE OrderMaster SET 
`cid`=:cid,
`eid`=:eid, 
`pservicename` = :pservicename,
`pservicepackage` = :pservicepackage,
`pvisit` = :pvisit,
`ServiceName`=:ServiceName,
`ServicProvider`=:ServicProvider,
`ServiceStartTime`=:ServiceStartTime,
`ServicePrice`=:ServicePrice,
`ServiceDiscount`=:ServiceDiscount,
`ServiceDiscoutInParentage`=:ServiceDiscoutInParentage,
`ServiceFianlPrice`=:ServiceFianlPrice,
`ProdcutName`=:ProdcutName,
`ProdcutQuality`=:ProdcutQuality,
`ProductPrice`=:ProductPrice,
`ProductCostPrice`=:ProductCostPrice,
`ProductTaxPrice`=:ProductTaxPrice,
`ProductDiscount`=:ProductDiscount,
`ProductDiscountInParentage`=:ProductDiscountInParentage,
`ProductFianlPrice`=:ProductFianlPrice,
`MembershipName`=:MembershipName,
`MembershipPrice`=:MembershipPrice,
`MembershipDiscount`=:MembershipDiscount,
`MemberDiscoutInParentage`=:MemberDiscoutInParentage,
`MembershipFianlPrice`=:MembershipFianlPrice,
`TotalOrderAmount`=:TotalOrderAmount,
`TotalseriveAmount`=:TotalseriveAmount,
`TotalProductAmount`=:TotalProductAmount,
`TotalMembershipAmount`=:TotalMembershipAmount,
`GetTotalPoint`=:GetTotalPoint,
`UsePoint`=:UsePoint,
`Remainepoints`=:Remainepoints,
`datecreated`=:datecreated,
`datelastupdated`=:datelastupdated,
`createdfk`=:createdfk,
`updatedfk`=:updatedfk,
`sales_tax`=:sales_tax,
`payment_status`=:payment_status,
`tips`=:tips,
`Noofvisit`=:Noofvisit,
`Package_Autonew`=:Package_Autonew,
`Package_renwal`=:Package_renwal,
`Pckage_carryford`=:Pckage_carryford,
`package_expire_date`=:package_expire_date,

`gServiceName` = :gServiceName,
`gServicePrice` = :gServicePrice,
`gServiceDiscount` =:gServiceDiscount,
`gServiceDiscoutInParentage` = :gServiceDiscoutInParentage,
`gServiceFianlPrice` =:gServiceFianlPrice, 
`TotalgiftAmount` = :TotalgiftAmount,
`giftapp` = :giftapp,

`isactive`=:isactive  WHERE id=:myid");
        
            

            $query->bindValue(':cid', $this->cid, PDO::PARAM_STR);
            if($this->eid)
            {
                $query->bindValue(':eid', @$this->eid, PDO::PARAM_STR);
            }
            else{
                $query->bindValue(':eid',0, PDO::PARAM_STR);
            }
            
            $query->bindValue(':pservicepackage', $this->pservicepackage, PDO::PARAM_STR);
            $query->bindValue(':pservicename', $this->pservicename, PDO::PARAM_STR);
            $query->bindValue(':pvisit', $this->pvisit,PDO::PARAM_STR);


            $query->bindValue(':ServiceName', $this->ServiceName, PDO::PARAM_STR);
            $query->bindValue(':ServicProvider', $this->ServicProvider, PDO::PARAM_STR);
            $query->bindValue(':ServiceStartTime', $this->ServiceStartTime, PDO::PARAM_STR);
            $query->bindValue(':ServicePrice', $this->ServicePrice, PDO::PARAM_STR);
            $query->bindValue(':ServiceDiscount', $this->ServiceDiscount, PDO::PARAM_STR);
            $query->bindValue(':ServiceDiscoutInParentage', $this->ServiceDiscoutInParentage, PDO::PARAM_STR);
            $query->bindValue(':ServiceFianlPrice', $this->ServiceFianlPrice, PDO::PARAM_STR);
            $query->bindValue(':ProdcutName', $this->ProdcutName, PDO::PARAM_STR);
            $query->bindValue(':ProdcutQuality', $this->ProdcutQuality, PDO::PARAM_STR);
            $query->bindValue(':ProductPrice', $this->ProductPrice, PDO::PARAM_STR);
            $query->bindValue(':ProductCostPrice', $this->ProductCostPrice, PDO::PARAM_STR);
            $query->bindValue(':ProductTaxPrice', $this->ProductTaxPrice, PDO::PARAM_STR);
            $query->bindValue(':ProductDiscount', $this->ProductDiscount, PDO::PARAM_STR);
            $query->bindValue(':ProductDiscountInParentage', $this->ProductDiscountInParentage, PDO::PARAM_STR);
            $query->bindValue(':ProductFianlPrice', $this->ProductFianlPrice, PDO::PARAM_STR);
            $query->bindValue(':MembershipName', $this->MembershipName, PDO::PARAM_STR);
            $query->bindValue(':MembershipPrice', $this->MembershipPrice, PDO::PARAM_STR);
            $query->bindValue(':MembershipDiscount', $this->MembershipDiscount, PDO::PARAM_STR);
            $query->bindValue(':MemberDiscoutInParentage', $this->MemberDiscoutInParentage, PDO::PARAM_STR);
            $query->bindValue(':MembershipFianlPrice', $this->MembershipFianlPrice, PDO::PARAM_STR);
            $query->bindValue(':TotalOrderAmount', $this->TotalOrderAmount, PDO::PARAM_STR);
            $query->bindValue(':TotalseriveAmount', @$this->TotalseriveAmount, PDO::PARAM_STR);
            $query->bindValue(':TotalProductAmount', @$this->TotalProductAmount, PDO::PARAM_STR);
            $query->bindValue(':TotalMembershipAmount', @$this->TotalMembershipAmount, PDO::PARAM_STR);

            //$query->bindValue(':GetTotalPoint', @$this->GetTotalPoint, PDO::PARAM_STR);

            if($this->GetTotalPoint === "")
            {
                $query->bindValue(':GetTotalPoint', 0, PDO::PARAM_STR);
                
            }
            else{
                $query->bindValue(':GetTotalPoint', @$this->GetTotalPoint, PDO::PARAM_STR);

            }


            if($this->UsePoint === "")
            {
                $query->bindValue(':UsePoint', 0, PDO::PARAM_STR);
                
            }
            else{
                $query->bindValue(':UsePoint', @$this->UsePoint, PDO::PARAM_STR);

            }

            //$query->bindValue(':Remainepoints', @$this->Remainepoints, PDO::PARAM_STR);


            if($this->Remainepoints === "")
            {
                $query->bindValue(':Remainepoints', 0, PDO::PARAM_STR);
                
            }
            else{
                $query->bindValue(':Remainepoints', $this->Remainepoints, PDO::PARAM_STR);

            }

            @$query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
            @$query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);
            @$query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
            @$query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
            @$query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);
            @$query->bindValue(':sales_tax', @$this->sales_tax, PDO::PARAM_STR);
            @$query->bindValue(':payment_status', $this->payment_status, PDO::PARAM_STR);
            if(@$this->tips === "")
            {
                @$query->bindValue(':tips', 0, PDO::PARAM_STR);

            }
            else{
                @$query->bindValue(':tips', @$this->tips, PDO::PARAM_STR);

            }
            @$query->bindValue(':myid', $this->id, PDO::PARAM_STR);

            @$query->bindValue(':Noofvisit', $this->Noofvisit, PDO::PARAM_STR);
            @$query->bindValue(':Package_Autonew', $this->Package_Autonew, PDO::PARAM_STR);
            @$query->bindValue(':Package_renwal', $this->Package_renwal, PDO::PARAM_STR);
            @$query->bindValue(':Pckage_carryford', $this->Pckage_carryford, PDO::PARAM_STR);
            @$query->bindValue(':package_expire_date', $this->package_expire_date, PDO::PARAM_STR);
            
            $query->bindValue(':gServiceName', @$this->gServiceName, PDO::PARAM_STR);
            $query->bindValue(':gServicePrice', @$this->gServicePrice, PDO::PARAM_STR);
            if(!@$this->gServiceDiscount || $this->gServiceDiscount === "")
            {
                
                $query->bindValue(':gServiceDiscount', 0, PDO::PARAM_STR);

            }
            else{

               
                $query->bindValue(':gServiceDiscount', $this->gServiceDiscount, PDO::PARAM_STR);

            }

            $query->bindValue(':gServiceDiscoutInParentage', $this->gServiceDiscoutInParentage, PDO::PARAM_STR);
            $query->bindValue(':gServiceFianlPrice', $this->gServiceFianlPrice, PDO::PARAM_STR);
            $query->bindValue(':TotalgiftAmount', $this->TotalgiftAmount, PDO::PARAM_STR);

            if($this->giftapp === "")
            {
                @$query->bindValue(':giftapp', 0, PDO::PARAM_STR);

            }
            else{
                @$query->bindValue(':giftapp', $this->giftapp, PDO::PARAM_STR);

            }

            $insert=$query->execute();
            // if(!empty($_POST["giftapp"]))
            // {
            //     $giftsel = $db->prepare("SELECT *  FROM `totalgiftdata` WHERE cid = :cid ");
            //     @$giftsel->bindValue(':cid', $this->cid);
            //     $giftsel->execute();
            //     $giftseldata = $giftsel->fetch(PDO::FETCH_ASSOC);
            //     $giftapp = $this->giftapp + $giftseldata["usedbal"];

            //     $giftup = $db->prepare("UPDATE `totalgiftdata` SET usedbal = :giftapp WHERE cid = :cid ");
            //     @$giftup->bindValue(':cid', $this->cid);
            //     @$giftup->bindValue(':giftapp', $giftapp);
            //     $giftup->execute();
            // }
            //gift status updation
            if(!empty($_POST['giddata']))
            {
                $status= 1;
                $giddata = explode(' ', $_POST['giddata']);
                for($i=0;$i<count($giddata);$i++)
                {
                    $query = $db->prepare("UPDATE OrderMaster SET gstatus = :status WHERE id = :id ");
                    @$query->bindValue(':status', $status, PDO::PARAM_INT);
                    @$query->bindValue(':id', $giddata[$i], PDO::PARAM_STR);
                    $query->execute();
                }
            }
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
    public function Service($NewOrderId,$InvoiceNumber)
    {   
        $db = new db();
        foreach ($_POST['ServiceId'] as $key => $value)
        {
            $OrderId=$NewOrderId;
            $InvoiceNumber=$InvoiceNumber;
            $cid=$_POST["cid"];
            $createdfk=$_SESSION["UserID"];
            $SeriveId=$_POST["ServiceId"][$key];
            $ServicProvider=$_POST["ServicProvider"][$key];
            $ServiceStartTime=$_POST["ServiceStartTime"][$key];
            $ServicePrice=$_POST["ServicePrice"][$key];
            $ServiceDiscount=$_POST["ServiceDiscount"][$key];
            $ServiceDiscoutInParentage= $_POST["ServiceDiscoutInParentage"][$key];
            $ServiceFianlPrice=$_POST["ServiceFianlPrice"][$key];
            @$serCommissionAmount=$_POST["serCommissionAmount"][$key];
            $OrderTime=date("Y-m-d h:i:sa");
            if($_POST['eid']){
                $eid = $_POST['eid'];
            }else{
                $eid=0;
            }
            $insert_data=$db->prepare("INSERT INTO OrderServic(OrderId,InvoiceNumber,Cid,createdfk,eid,SeriveId,ServicProvider,ServiceStartTime,ServicePrice,ServiceDiscount,ServiceDiscoutInParentage,ServiceFianlPrice,OrderTime) VALUES(:OrderId,:InvoiceNumber, :cid, :createdfk,:eid, :SeriveId,:ServicProvider,:ServiceStartTime,:ServicePrice,:ServiceDiscount,:ServiceDiscoutInParentage,:ServiceFianlPrice,:OrderTime)");
            $insert_data->bindparam(":OrderId",$OrderId);
            $insert_data->bindparam(":InvoiceNumber",$InvoiceNumber);
            $insert_data->bindparam(":cid",$cid);
            $insert_data->bindparam(":createdfk",$createdfk);
            $insert_data->bindparam(":eid",$eid);
            $insert_data->bindparam(":SeriveId",$SeriveId);
            $insert_data->bindparam(":ServicProvider",$ServicProvider);
            $insert_data->bindparam(":ServiceStartTime",$ServiceStartTime);
            $insert_data->bindparam(":ServicePrice",$ServicePrice);
            $insert_data->bindparam(":ServiceDiscount",$ServiceDiscount);
            $insert_data->bindparam(":ServiceDiscoutInParentage",$ServiceDiscoutInParentage);
            $insert_data->bindparam(":ServiceFianlPrice",$ServiceFianlPrice);
            $insert_data->bindparam(":OrderTime",$OrderTime);
            $insert_data->execute();
            $insert_data_inco_s=$db->prepare("INSERT INTO Commission(OrderId,InvoiceNumber,Cid,createdfk,SeriveId,serCommissionAmount,OrderTime) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:SeriveId,:serCommissionAmount,:OrderTime)");
            $insert_data_inco_s->bindparam(":OrderId",$OrderId);
            $insert_data_inco_s->bindparam(":InvoiceNumber",$InvoiceNumber);
            $insert_data_inco_s->bindparam(":cid",$cid);
            $insert_data_inco_s->bindparam(":createdfk",$createdfk);
            $insert_data_inco_s->bindparam(":SeriveId",$SeriveId);
            $insert_data_inco_s->bindparam(":serCommissionAmount",$serCommissionAmount);
            $insert_data_inco_s->bindparam(":OrderTime",$OrderTime);
            $insert_data_inco_s->execute();
        }
    }

     public function gService($NewOrderId,$InvoiceNumber)
    {   
        $db = new db();
        foreach ($_POST['gServiceId'] as $key => $value)
        {
            $OrderId=$NewOrderId;
            $InvoiceNumber=$InvoiceNumber;
            $cid=$_POST["cid"];
            $createdfk=$_SESSION["UserID"];
            $gSeriveId=$_POST["gServiceId"][$key];
            $gServicePrice=$_POST["gServicePrice"][$key];
            $gServiceDiscount=$_POST["gServiceDiscount"][$key];
            $gServiceDiscoutInParentage= $_POST["gServiceDiscoutInParentage"][$key];
            $gServiceFianlPrice=$_POST["gServiceFianlPrice"][$key];
            $TotalgiftAmount = $_POST["TotalgiftAmount"];
            $OrderTime=date("Y-m-d h:i:sa");
            if($_POST['eid']){
                $eid = $_POST['eid'];
            }else{
                $eid=0;
            }
            $insert_data=$db->prepare("INSERT INTO Ordergift(OrderId,InvoiceNumber,Cid,createdfk,eid,gSeriveId,gServicePrice,gServiceDiscount,gServiceDiscoutInParentage,gServiceFianlPrice,TotalgiftAmount,OrderTime) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:eid,:gSeriveId,:gServicePrice,:gServiceDiscount,:gServiceDiscoutInParentage,:gServiceFianlPrice,:TotalgiftAmount,:OrderTime)");
            $insert_data->bindparam(":OrderId",$OrderId);
            $insert_data->bindparam(":InvoiceNumber",$InvoiceNumber);
            $insert_data->bindparam(":cid",$cid);
            $insert_data->bindparam(":createdfk",$createdfk);
            $insert_data->bindparam(":eid",$eid);
            $insert_data->bindparam(":gSeriveId",$gSeriveId);
            $insert_data->bindparam(":gServicePrice",$gServicePrice);
            $insert_data->bindparam(":gServiceDiscount",$gServiceDiscount);
            $insert_data->bindparam(":gServiceDiscoutInParentage",$gServiceDiscoutInParentage);
            $insert_data->bindparam(":gServiceFianlPrice",$gServiceFianlPrice);
            $insert_data->bindparam(":TotalgiftAmount",$TotalgiftAmount);
            $insert_data->bindparam(":OrderTime",$OrderTime);
            $insert_data->execute();
        }

            // $select_totaldata=$db->prepare("select * from totalgiftdata where cid = :cid");
            // $select_totaldata->bindparam(":cid",$cid);
            // // $select_totaldata->bindparam(":userid",$createdfk);
            // $select_totaldata->execute();
            // $data = $select_totaldata->fetch(PDO::FETCH_ASSOC);
            // $count = $select_totaldata->rowCount();
            // if($count > 0)
            // {
            //     $totalgiftbal = $data["totalgiftbal"] + $TotalgiftAmount;
            //     $update_totaldata=$db->prepare("UPDATE `totalgiftdata` SET totalgiftbal = :totalgiftbal where cid=:cid");
            //     $update_totaldata->bindparam(":cid",$cid);
            //     // $update_totaldata->bindparam(":userid",$createdfk);
            //     $update_totaldata->bindparam(":totalgiftbal",$totalgiftbal);
            //     $update_totaldata->execute();   
            // }
            // else
            // {
            //     $cid=$_POST["cid"];
            //     $createdfk=$_SESSION["UserID"];
            //     $TotalgiftAmount = $_POST["TotalgiftAmount"];
            //     $timedate=date("Y-m-d h:i:s");
            //     $insert_totaldata=$db->prepare("INSERT INTO totalgiftdata(userid,cid,totalgiftbal,createddate) VALUES(:userid,:cid,:TotalgiftAmount,:timedate)");
            //     $insert_totaldata->bindparam(":cid",$cid);
            //     $insert_totaldata->bindparam(":userid",$createdfk);
            //     $insert_totaldata->bindparam(":TotalgiftAmount",$TotalgiftAmount);
            //     $insert_totaldata->bindparam(":timedate",$timedate);
            //     $insert_totaldata->execute();
            // }


    }






    public function Membership($NewOrderId,$InvoiceNumber)
    {
         $db = new db();
         
        foreach ($_POST['MembershipId'] as $key => $value)
        {   
         $OrderId=$NewOrderId;
         $cid=$_POST["cid"];
         $createdfk=$_SESSION["UserID"];
         $MembershipId=$_POST['MembershipId'][$key];
           $package_start_date =  date("Y-m-d");
          $MembershipPrice=$_POST["MembershipPrice"][$key];
         $MembershipDiscount=$_POST["MembershipDiscount"][$key];
         $MemberDiscoutInParentage= $_POST["MemberDiscoutInParentage"][$key];
         $MembershipFianlPrice=$_POST["MembershipFianlPrice"][$key];
         @$memCommissionAmount=$_POST["memCommissionAmount"][$key];
         $OrderTime=date("Y-m-d h:i:sa");
         $eid=0;
         $Noofvisit = $_POST["noofvisit"][$key];
         $Package_Autonew = $_POST["autorrwnew"][$key];
         $Package_renwal = $_POST["renewalon"][$key];
         $Pckage_carryford= $_POST["carryforwed"][$key];

         if($Package_Autonew=='No')
         {
            $package_expire_date = $_POST["packageexpriydate"][$key];   
         }
         else
         {
            if($Package_renwal=='Weekly')
            {

            $package_expire_date = strtotime($OrderTime);
            $package_expire_date = strtotime("+7 day", $package_expire_date);
            $package_expire_date =date('Y-n-d', $package_expire_date); 
            }
            elseif($Package_renwal=='Bi-Weekly')
             {
            $package_expire_date = strtotime($OrderTime);
            $package_expire_date = strtotime("+14 day", $package_expire_date);
           $package_expire_date =date('Y-n-d', $package_ed);                
            }
            elseif($Package_renwal=='Monthly')
            {
            if( date('d') == 31 || (date('m') == 1 && date('d') > 28))
            {
                $package_expire_date = strtotime($OrderTime);
            } 
            else
            {
                $package_expire_date = strtotime('+1 months');
            }
            $package_expire_date =date('Y-n-d', $package_expire_date);        
            }

            elseif($Package_renwal=='Yearly')
            {
                $package_expire_date = strtotime($OrderTime);
                $package_expire_date = strtotime("+1 year", $package_expire_date);
                $package_expire_date =date('Y-n-d', $package_ed);
            }
            else
            {
                $package_expire_date=date('Y-n-d');
            }


         }


         $insert_data=$db->prepare("INSERT INTO OrderMembership(OrderId,InvoiceNumber,Cid,createdfk,eid,MembershipId,MembershipPrice,MembershipDiscount,MemberDiscoutInParentage,MembershipFianlPrice,OrderTime,Noofvisit,Package_Autonew,Package_renwal,Pckage_carryford,package_expire_date,package_start_date) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:eid, :MembershipId,:MembershipPrice,:MembershipDiscount,:MemberDiscoutInParentage,:MembershipFianlPrice,:OrderTime,:Noofvisit,:Package_Autonew,:Package_renwal,:Pckage_carryford,:package_expire_date,:package_start_date)");
        $insert_data->bindparam(":OrderId",$OrderId);
        $insert_data->bindparam(":InvoiceNumber",$InvoiceNumber);
        $insert_data->bindparam(":cid",$cid);
        $insert_data->bindparam(":createdfk",$createdfk);
        $insert_data->bindparam(":eid",$eid);
        $insert_data->bindparam(":MembershipId",$MembershipId);
        $insert_data->bindparam(":MembershipPrice",$MembershipPrice);
        $insert_data->bindparam(":MembershipDiscount",$MembershipDiscount);
        $insert_data->bindparam(":MemberDiscoutInParentage",$MemberDiscoutInParentage);
        $insert_data->bindparam(":MembershipFianlPrice",$MembershipFianlPrice);
        $insert_data->bindparam(":OrderTime",$OrderTime);

        $insert_data->bindparam(":Noofvisit",$Noofvisit);
        $insert_data->bindparam(":Package_Autonew",$Package_Autonew);
        $insert_data->bindparam(":Package_renwal",$Package_renwal);
        $insert_data->bindparam(":Pckage_carryford",$Pckage_carryford);
        $insert_data->bindparam(":package_expire_date",$package_expire_date);
        $insert_data->bindparam(":package_start_date",$package_start_date);

        $insert_data->execute();
        $employeeSold=$_SESSION["UserID"];
        $SelectPackage=$MembershipId;
        $package_sd=date('Y-n-d');
        $LoginQuery = $db->prepare("SELECT Tracking FROM MemberPackage WHERE id=:SelectPackage");
        $LoginQuery->bindParam(':SelectPackage', $SelectPackage, PDO::PARAM_INT);
        $LoginQuery->execute();
        $result = $LoginQuery->fetch(PDO::FETCH_ASSOC);
        @$days=$result['Tracking'];
        if($days=='Weekly')
        {
            $package_ed = strtotime($package_sd);
            $package_ed = strtotime("+7 day", $package_ed);
            $package_ed =date('Y-n-d', $package_ed);            
        }
        elseif($days=='Bi-Weekly')
        {
            $package_ed = strtotime($package_sd);
            $package_ed = strtotime("+14 day", $package_ed);
           $package_ed =date('Y-n-d', $package_ed);                
        }
        elseif($days=='Monthly')
        {
            if( date('d') == 31 || (date('m') == 1 && date('d') > 28))
            {
                $package_ed = strtotime($package_sd);
            } 
            else
            {
                $package_ed = strtotime('+1 months');
            }
            $package_ed =date('Y-n-d', $package_ed);        
        }

        elseif($days=='Yearly')
        {
           $package_ed = strtotime($package_sd);
            $package_ed = strtotime("+1 year", $package_ed);
            $package_ed =date('Y-n-d', $package_ed);                 

        }

        else
        {
            $package_ed=date('Y-n-d');
        }

        $findcustomerpackagedetalis = $db->prepare("SELECT package_sd,package_ed FROM clients WHERE id=:cid");
        $findcustomerpackagedetalis->bindparam(":cid",$cid);
        $findcustomerpackagedetalis->execute();
        $result_findcustomerpackagedetalis = $findcustomerpackagedetalis->fetch();   
        $psd = $result_findcustomerpackagedetalis['package_sd'];
        $ped = $result_findcustomerpackagedetalis['package_ed'];
        $date1 = new DateTime($ped);
        $date2 = new DateTime("now");
        $interval = $date1->diff($date2);
        $mynowtime=date("Y-m-d");
        
        if($ped>=$mynowtime)
        {
        $package_ed;
        $addrmainday = $interval->days;
        $package_ed = date('Y-m-d', strtotime($package_ed. ' + '.$addrmainday.' days'));
            
        }
        $Update_cdata=$db->prepare("UPDATE clients SET employeeSold=:employeeSold,SelectPackage=:SelectPackage,package_sd=:package_sd,package_ed=:package_ed WHERE id=:cid");
        $Update_cdata->bindparam(":employeeSold",$employeeSold);
        $Update_cdata->bindparam(":SelectPackage",$SelectPackage);
        $Update_cdata->bindparam(":package_sd",$package_sd);
        $Update_cdata->bindparam(":package_ed",$package_ed);
        $Update_cdata->bindparam(":cid",$cid);
        $Update_cdata->execute();
        $insert_data_inco_m=$db->prepare("INSERT INTO Commission(OrderId,InvoiceNumber,Cid,createdfk,MembershipId,memCommissionAmount,OrderTime) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:MembershipId,:memCommissionAmount,:OrderTime)");
        $insert_data_inco_m->bindparam(":OrderId",$OrderId);
        $insert_data_inco_m->bindparam(":InvoiceNumber",$InvoiceNumber);
        $insert_data_inco_m->bindparam(":cid",$cid);
        $insert_data_inco_m->bindparam(":createdfk",$createdfk);
        $insert_data_inco_m->bindparam(":MembershipId",$MembershipId);
        $insert_data_inco_m->bindparam(":memCommissionAmount",$memCommissionAmount);
        $insert_data_inco_m->bindparam(":OrderTime",$OrderTime);
        $insert_data_inco_m->execute();


    

        $packageid = $SelectPackage;
        $createdby = $employeeSold;
        $Noofvisit = $_POST["noofvisit"][$key];
        

        $insert_data_clientpackage=$db->prepare("INSERT INTO Client_package_details(cid,packageid,createdby,OrderTime,Noofvisit,OrderId) VALUES(:cid,:packageid,:createdby, :OrderTime,:Noofvisit,:OrderId)");
        $insert_data_clientpackage->bindparam(":cid",$cid);
        $insert_data_clientpackage->bindparam(":packageid",$packageid);
        $insert_data_clientpackage->bindparam(":createdby",$createdby);
        $insert_data_clientpackage->bindparam(":OrderTime",$OrderTime);
        $insert_data_clientpackage->bindparam(":Noofvisit",$Noofvisit);
        $insert_data_clientpackage->bindparam(":OrderId",$OrderId);
        $insert_data_clientpackage->execute();


        }   
        
    }
    public function Product($NewOrderId,$InvoiceNumber)
    {
        $db = new db();
       
        foreach ($_POST['ProdcutId'] as $key => $value)
        {           
            $OrderId=$NewOrderId;
            $InvoiceNumber=$InvoiceNumber;
            $cid=$_POST["cid"];
            $createdfk=$_SESSION["UserID"];
            $ProdcutId=$_POST['ProdcutId'][$key];
            $ProdcutQuality=$_POST['ProdcutQuality'][$key];
                
          
            $ProductPrice=$_POST['ProductPrice'][$key];
            $ProductCostPrice=$_POST['ProductCostPrice'][$key];
            $ProductTaxPrice=$_POST['ProductTaxPrice'][$key];
            $ProductDiscount=$_POST['ProductDiscount'][$key];
            $ProductDiscountInParentage=$_POST['ProductDiscountInParentage'][$key];
            $ProductFianlPrice=$_POST['ProductFianlPrice'][$key];
            @$proCommissionAmount=$_POST['proCommissionAmount'][$key];
            $OrderTime=date("Y-m-d h:i:sa");
            $eid=0;
            $insert_data=$db->prepare("INSERT INTO OrderProduct(OrderId,InvoiceNumber,Cid,createdfk,eid,ProdcutId,ProdcutQuality,ProductPrice,ProductCostPrice,ProductTaxPrice,ProductDiscount,ProductDiscountInParentage,ProductFianlPrice,OrderTime) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:eid, :ProdcutId,:ProdcutQuality,:ProductPrice,:ProductCostPrice,:ProductTaxPrice,:ProductDiscount,:ProductDiscountInParentage,:ProductFianlPrice,:OrderTime)");
            $insert_data->bindparam(":OrderId",$OrderId);
            $insert_data->bindparam(":InvoiceNumber",$InvoiceNumber);
            $insert_data->bindparam(":cid",$cid);
            $insert_data->bindparam(":createdfk",$createdfk);
            $insert_data->bindparam(":eid",$eid);
            $insert_data->bindparam(":ProdcutId",$ProdcutId);
            $insert_data->bindparam(":ProdcutQuality",$ProdcutQuality);
            $insert_data->bindparam(":ProductPrice",$ProductPrice);
            $insert_data->bindparam(":ProductCostPrice",$ProductCostPrice);
            $insert_data->bindparam(":ProductTaxPrice",$ProductTaxPrice);
            $insert_data->bindparam(":ProductDiscount",$ProductDiscount);
            $insert_data->bindparam(":ProductDiscountInParentage",$ProductDiscountInParentage);
            $insert_data->bindparam(":ProductFianlPrice",$ProductFianlPrice);
            $insert_data->bindparam(":OrderTime",$OrderTime);
            $insert_data->execute();

            $insert_data_inco=$db->prepare("INSERT INTO Commission(OrderId,InvoiceNumber,Cid,createdfk,ProdcutId,proCommissionAmount,OrderTime) VALUES(:OrderId,:InvoiceNumber,:cid, :createdfk,:ProdcutId,:proCommissionAmount,:OrderTime)");
            $insert_data_inco->bindparam(":OrderId",$OrderId);
            $insert_data_inco->bindparam(":InvoiceNumber",$InvoiceNumber);
            $insert_data_inco->bindparam(":cid",$cid);
            $insert_data_inco->bindparam(":createdfk",$createdfk);
            $insert_data_inco->bindparam(":ProdcutId",$ProdcutId);
            $insert_data_inco->bindparam(":proCommissionAmount",$proCommissionAmount);
            $insert_data_inco->bindparam(":OrderTime",$OrderTime);
            $insert_data_inco->execute();
            
            if(isset($_POST['giftapplied'])){  /// When TotalAmount = 0 after gift card applied
                $findprodcut = $db->prepare("SELECT NoofPorduct FROM Product WHERE id=:ProdcutId");
                $findprodcut->bindParam(':ProdcutId', $ProdcutId, PDO::PARAM_INT);
                $findprodcut->execute();
                $presult = $findprodcut->fetch();   
                
                $oldstock = $presult['NoofPorduct'];
                $remainstock = $oldstock-$ProdcutQuality;
                $Update_stock=$db->prepare("UPDATE Product SET NoofPorduct=:remainstock WHERE id=:ProdcutId");
                $Update_stock->bindparam(":remainstock",$remainstock);
                $Update_stock->bindparam(":ProdcutId",$ProdcutId);
                $Update_stock->execute();  
            } 


        }
    }

        public function Completed_Order(){
        $db= new db();


        $OrderFilter = json_decode($_REQUEST['OrderFilter']);

        $BindData = []; 
        
        if(isset($_REQUEST['start']) && isset($_REQUEST['length'])){

            $start = $_REQUEST['start'];
            $length = $_REQUEST['length'];

            $Limit = " LIMIT $start,$length ";
        }else{
            $Limit = "";
        }

        
        $OrderString = "";
        if(!empty($_REQUEST['order'])){
            $OrderString = DT_OrderBy($_REQUEST['order']);
        }

        $SearchString = "";
        if(!empty($_REQUEST['search']['value'])){

            $SearchString = " where ".DT_Search($_REQUEST['search']['value']);
        }


        if(!empty($OrderFilter->SelectDate) ){

            $selectdaterang =explode(' - ',$OrderFilter->SelectDate);
            $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
            $todate = date("Y-m-d", strtotime($selectdaterang[1]));
            $date = " AND  DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')>='".$fromdate."'
            AND DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')<='".$todate."' ";
        }else{
            $date = "";
        }

        $Customer = implode(',',$OrderFilter->Customer);
        if(!empty($Customer)){
            $Customer = " AND clients.id IN ($Customer) ";
        }else{
            $Customer = "";
        }

        $Payment = implode(',',$OrderFilter->Payment);
        if(@$Payment){
            $Payment = '"'.str_replace(',','","',$Payment).'"';
             $Payment = " AND OrderPayment.PaymentType IN ($Payment) ";

        }else{
            $Payment = "";
        }

        if($_SESSION['usertype']=='Admin'){

                $id = implode(',',$OrderFilter->User);
            if(!empty($id)){
                $id = " AND ( users.adminid IN ($id) OR users.id IN ($id) )";
            }else{
                $id = "";
            }

        }else{
            $id = implode(',',$OrderFilter->User);
            if(!empty($id)){
                $id = " AND ( users.id IN ($id) )";
            }else{
                $id=$_SESSION['UserID'];
                $id = " AND ( users.adminid=$id OR users.id=$id )";
            }
        }


        if(!empty($_REQUEST['UserID'])){  //used in User Detail module
            $id = $_REQUEST['UserID'];
            $id = " and users.id=$id ";
        }

         $Query = "SELECT * from (
            SELECT OrderMaster.InvoiceNumber ino,users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,OrderPayment.PaymentType,OrderPayment.PaymentDetail,OrderPayment.amount as PaymentAmount,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,CONCAT(clients.FirstName,' ',clients.LastName) as Client_Fullname,clients.id as clientID ,clients.ProfileImg ,OrderMaster.*
            FROM `OrderMaster` 
            JOIN `OrderPayment`  ON  OrderMaster.id=OrderPayment.OrderId 
            JOIN clients ON OrderMaster.cid=clients.id 
            join users on OrderMaster.updatedfk = users.id
            WHERE OrderMaster.payment_status='CAPTURED' AND OrderPayment.payment_status='CAPTURED' 
            $date $id $Payment $Customer $OrderString 
            ) as OrderList";

            $response = DT_SQL($Query,$BindData,$SearchString,$Limit);

            echo $response;
           
    }

    public function Pending_Order(){
        $db= new db();

        $PendingFilter = json_decode($_REQUEST['PendingFilter']);

        if(!empty($PendingFilter->SelectDate) ){

            $selectdaterang =explode(' - ',$PendingFilter->SelectDate);
            $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
            $todate = date("Y-m-d", strtotime($selectdaterang[1]));
            $date = " AND  DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')>='".$fromdate."'
            AND DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')<='".$todate."' ";
        }else{
            $date = "";
        }

        $Customer = implode(',',$PendingFilter->Customer);
        if(!empty($Customer)){
            $Customer = " AND clients.id IN ($Customer) ";
        }else{
            $Customer = "";
        }

        if($_SESSION['usertype']=='Admin'){

                $id = implode(',',$PendingFilter->User);
            if(!empty($id)){
                $id = " AND ( users.adminid IN ($id) OR users.id IN ($id) )";
            }else{
                $id = "";
            }

        }else{
            $id = implode(',',$PendingFilter->User);
            if(!empty($id)){
                $id = " AND ( users.id IN ($id) )";
            }else{
                $id=$_SESSION['UserID'];
                $id = " AND ( users.adminid=$id OR users.id=$id )";
            }
        }        

        // if(!empty($_REQUEST['Customer'])){
        //     $Customer = $_REQUEST['Customer'];
        //     $Customer = " AND clients.id IN ($Customer) ";
        // }else{
        //     $Customer = "";
        // }

        // if($_SESSION['usertype']=='Admin'){

        //     if(!empty($_REQUEST['User'])){
        //         $id = $_REQUEST['User'];
        //         $id = " AND ( users.adminid IN ($id) OR users.id IN ($id) )";
        //     }else{
        //         $id = "";
        //     }

        // }else{

        //     if(!empty($_REQUEST['User'])){
        //         $id = $_REQUEST['User'];
        //         $id = " AND ( users.id IN ($id) )";
        //     }else{
        //         $id=$_SESSION['UserID'];
        //         $id = " AND ( users.adminid=$id OR users.id=$id )";
        //     }
        // }

         $LoginQuery = $db->prepare("SELECT OrderMaster.*,OrderMaster.InvoiceNumber as ino,users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,clients.FirstName,clients.LastName,clients.id as cid,clients.ProfileImg,OrderPayment.amount as PaymentAmount,OrderPayment.PaymentType,OrderPayment.payment_status as OrderPayment_status   FROM `OrderMaster` 
            left JOIN OrderPayment on OrderPayment.OrderId=OrderMaster.id and OrderPayment.payment_status<>'EDITED'
            JOIN clients ON OrderMaster.cid=clients.id 
            join users on OrderMaster.updatedfk = users.id
            WHERE OrderMaster.payment_status='PENDING' $date $id $Customer ORDER BY OrderMaster.id DESC");

        $LoginQuery->execute();
        $result = $LoginQuery->fetchAll();
        echo json_encode($result);              
    }

    public function listoffile(){
        $db= new db();

        if(!empty($_REQUEST['SelectDate']) ){

            $selectdaterang =explode(' - ',$_REQUEST['SelectDate']);
            $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
            $todate = date("Y-m-d", strtotime($selectdaterang[1]));
            $date = " AND  DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')>='".$fromdate."'
            AND DATE_FORMAT(OrderMaster.datelastupdated, '%Y-%m-%d')<='".$todate."' ";
        }else{
            $date = "";
        }

        if(!empty($_REQUEST['Customer'])){
            $Customer = $_REQUEST['Customer'];
            $Customer = " AND clients.id IN ($Customer) ";
        }else{
            $Customer = "";
        }

        if(@$_REQUEST['Payment']){

            $Payment = '"'.str_replace(',','","',$_REQUEST['Payment']).'"';
             $Payment = " AND OrderPayment.PaymentType IN ($Payment) ";

        }else{
            $Payment = "";
        }

        if($_SESSION['usertype']=='Admin'){

            if(!empty($_REQUEST['User'])){
                $id = $_REQUEST['User'];
                $id = " AND ( users.adminid IN ($id) OR users.id IN ($id) )";
            }else{
                $id = "";
            }

        }else{

            if(!empty($_REQUEST['User'])){
                $id = $_REQUEST['User'];
                $id = " AND ( users.id IN ($id) )";
            }else{
                $id=$_SESSION['UserID'];
                $id = " AND ( users.adminid=$id OR users.id=$id )";
            }
        }


        if(!empty($_REQUEST['UserID'])){  //used in User Detail module
            $id = $_REQUEST['UserID'];
            $id = " and users.id=$id ";
        }


        $LoginQuery = $db->prepare("SELECT OrderMaster.InvoiceNumber ino,users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,OrderPayment.PaymentType,OrderPayment.PaymentDetail,OrderPayment.amount as PaymentAmount,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,clients.FirstName,clients.LastName,clients.id as cid ,clients.ProfileImg ,OrderMaster.*
            FROM `OrderMaster` 
            JOIN `OrderPayment`  ON  OrderMaster.id=OrderPayment.OrderId 
            JOIN clients ON OrderMaster.cid=clients.id 
            join users on OrderMaster.updatedfk = users.id
            WHERE OrderMaster.payment_status='CAPTURED' AND OrderPayment.payment_status='CAPTURED' 
            $date $id $Payment $Customer ORDER BY OrderMaster.id DESC");

        $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
        $LoginQuery->execute();
        $result = $LoginQuery->fetchAll();
        
        echo json_encode($result);              
    }



}
?>