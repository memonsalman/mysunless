<?php

require_once('Exec_Config.php');   
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Order.php');


if(isset($_REQUEST['viewdata']))
{
    $ClientDisplay=new Order;
    $ClientDisplay->listoffile(); 
    die;  
}

if(isset($_REQUEST['viewdata2']))
{
    $ClientDisplay=new Order;
    $ClientDisplay->listoffile2();   
    die;
}



if(isset($_GET['remove_order_table'])){
    if(isset($_POST['orderid'])){
        $db=new db();
        $oldorderid = $_POST['orderid'];
        $Deleteoldmember = $db->prepare("DELETE from `OrderMembership` where OrderId=$oldorderid");
        @$Deleteoldmember->execute();
        $Deleteoldser = $db->prepare("DELETE from `OrderServic` where OrderId=$oldorderid");
        @$Deleteoldser->execute();
        $Deleteoldpro = $db->prepare("DELETE from `OrderProduct` where OrderId=$oldorderid");
        @$Deleteoldpro->execute();
        $Deleteoldgift = $db->prepare("DELETE from `Ordergift` where OrderId=$oldorderid");
        @$Deleteoldgift->execute();
        $Deleteoldcom = $db->prepare("DELETE from `Commission` where OrderId=$oldorderid");
        @$Deleteoldcom->execute();

        $resetProductQty = productStockRollback($oldorderid);
        
        $Deletemaster = $db->prepare("DELETE from `OrderMaster` where id=$oldorderid");
        @$Deletemaster->execute();

        if($Deleteoldmember && $Deleteoldser && $Deleteoldpro && $Deleteoldgift && $Deleteoldcom && $resetProductQty){
            echo json_encode(['response'=>'Your order has been cancelled.']);die;
        }
    }
}



function productStockRollback($oldorderid)
{ 
    $db=new db();
    $res;

    $findprodcut = $db->prepare("SELECT ProdcutName,ProdcutQuality FROM `OrderMaster` where id =:id");
    $findprodcut->bindParam(':id', $oldorderid, PDO::PARAM_INT);
    $res = $findprodcut->execute();
    $result = $findprodcut->fetch();

    if($findprodcut->rowCount()>0 && $result['ProdcutName']!="" && $result['ProdcutQuality']!=""){

        $productDetail = [];
        $productDetail = array_combine(explode(',',$result['ProdcutName']), explode(',',$result['ProdcutQuality']));

        foreach ($productDetail as $key => $value)
        {  

            $Update_stock=$db->prepare("UPDATE Product SET NoofPorduct=NoofPorduct+:remainstock WHERE id=:ProdcutId");
            $Update_stock->bindparam(":remainstock",$value);
            $Update_stock->bindparam(":ProdcutId",$key);
            $res = $Update_stock->execute();  

        }
    }
    return $res;
}

@$myOrder = new Order($_POST["id"]);
@$oldorderid=$_POST["id"];


if(!empty($oldorderid))
{   

    if($oldorderid != 'new')
    {

        $db=new db();
        $Deleteoldmember = $db->prepare("DELETE from `OrderMembership` where OrderId=$oldorderid");
        @$Deleteoldmember->execute();
        $Deleteoldser = $db->prepare("DELETE from `OrderServic` where OrderId=$oldorderid");
        @$Deleteoldser->execute();
        $Deleteoldpro = $db->prepare("DELETE from `OrderProduct` where OrderId=$oldorderid");
        @$Deleteoldpro->execute();
        $Deleteoldgift = $db->prepare("DELETE from `Ordergift` where OrderId=$oldorderid");
        @$Deleteoldgift->execute();
        $Deleteoldcom = $db->prepare("DELETE from `Commission` where OrderId=$oldorderid");
        @$Deleteoldcom->execute();

    }
}

if(isset($_POST['payment_status']))
{
    $payment_status = $myOrder->payment_status=$_POST['payment_status'];
}

if(!empty($_POST['SEND_EMAIL'])){
    $_SESSION['SEND_EMAIL'] = $_POST['SEND_EMAIL']; // send invoice
}

@$orderpservice = $_POST["orderpservice"];

if($orderpservice == 1)
{
    // $payment_status = $myOrder->payment_status="CAPTURED";
    // $_POST["TotalseriveAmount"] = 0;
    // $_POST["TotalOrderAmount"] = 0;
}



@$cid=$myOrder->cid =stripslashes(strip_tags($_POST["cid"]));

if(isset($_POST["eid"])){
    @$eid=$myOrder->eid =stripslashes(strip_tags($_POST["eid"]));    
}

@$ServiceName=$myOrder->ServiceName =implode(',',$_POST["ServiceId"]);
@$ServicProvider=$myOrder->ServicProvider =implode(',',$_POST["ServicProvider"]);
@$ServiceStartTime=$myOrder->ServiceStartTime =implode(',',$_POST["ServiceStartTime"]);
@$ServicePrice=$myOrder->ServicePrice =implode(',',$_POST["ServicePrice"]);
@$ServiceDiscount=$myOrder->ServiceDiscount =implode(',',$_POST["ServiceDiscount"]);
@$ServiceDiscoutInParentage=$myOrder->ServiceDiscoutInParentage =implode(',',$_POST["ServiceDiscoutInParentage"]);
@$ServiceFianlPrice=$myOrder->ServiceFianlPrice =implode(',',$_POST["ServiceFianlPrice"]);
@$ProdcutName=$myOrder->ProdcutName =implode(',',$_POST["ProdcutId"]);
@$ProdcutQuality=$myOrder->ProdcutQuality =implode(',',$_POST["ProdcutQuality"]);
@$ProductPrice=$myOrder->ProductPrice =implode(',',$_POST["ProductPrice"]);
@$ProductTaxPrice=$myOrder->ProductTaxPrice =implode(',',$_POST["ProductTaxPrice"]);
@$ProductDiscount=$myOrder->ProductDiscount =implode(',',$_POST["ProductDiscount"]);
@$ProductDiscountInParentage=$myOrder->ProductDiscountInParentage =implode(',',$_POST["ProductDiscountInParentage"]);
@$ProductFianlPrice=$myOrder->ProductFianlPrice =implode(',',$_POST["ProductFianlPrice"]);

@$MembershipName=$myOrder->MembershipName = implode(',',$_POST["MembershipId"]);
@$MembershipPrice=$myOrder->MembershipPrice = implode(',',$_POST["MembershipPrice"]);
@$MembershipDiscount=$myOrder->MembershipDiscount = implode(',',$_POST["MembershipDiscount"]);
@$MemberDiscoutInParentage=$myOrder->MemberDiscoutInParentage = implode(',',$_POST["MemberDiscoutInParentage"]);
@$MembershipFianlPrice=$myOrder->MembershipFianlPrice = implode(',',$_POST["MembershipFianlPrice"]);
@$TotalOrderAmount=$myOrder->TotalOrderAmount =stripslashes(strip_tags($_POST["TotalOrderAmount"]));

// @$gServiceName = $myOrder->gServiceName = implode(',',$_POST["gServiceName"]);
// @$gServiceId =  $myOrder->gServiceId = implode(',',$_POST["gServiceId"]);
// @$gServicePrice = $myOrder->gServicePrice = implode(',',$_POST["gServicePrice"]);
// @$gServiceDiscount = $myOrder->gServiceDiscount = implode(',',$_POST["gServiceDiscount"]);
// @$gServiceDiscoutInParentage = $myOrder->gServiceDiscoutInParentage = implode(',',$_POST["gServiceDiscoutInParentage"]);
// @$gServiceFianlPrice = $myOrder->gServiceFianlPrice = implode(',',$_POST["gServiceFianlPrice"]);


$_SESSION["TotalOrderAmount"] = $TotalOrderAmount;
@$TotalseriveAmount=$myOrder->TotalseriveAmount =stripslashes(strip_tags($_POST["TotalseriveAmount"]));


@$TotalProductAmount=$myOrder->TotalProductAmount =stripslashes(strip_tags($_POST["TotalProductAmount"]));
@$TotalMembershipAmount=$myOrder->TotalMembershipAmount =stripslashes(strip_tags($_POST["TotalMembershipAmount"]));
@$GetTotalPoint=$myOrder->GetTotalPoint =stripslashes(strip_tags($_POST["GetTotalPoint"]));


if(isset($_POST['mytips']) && $_POST["UsePoint"] != "")
{
    @$UsePoint=$myOrder->UsePoint =stripslashes(strip_tags($_POST["UsePoint"]));
}
else
{
    @$UsePoint=$myOrder->UsePoint = stripslashes(strip_tags(0));

}

@$Remainepoints=$myOrder->Remainepoints =stripslashes(strip_tags($_POST["Remainepoints"]));
$_SESSION["Remainepoints"] = $Remainepoints;
@$serCommissionAmount=$myOrder->serCommissionAmount =stripslashes(strip_tags($_POST["serCommissionAmount"]));
// @$serCommissionAmount=$myOrder->serCommissionAmount =$_POST["serCommissionAmount"]);
@$proCommissionAmount=$myOrder->proCommissionAmount =implode(',',$_POST["proCommissionAmount"]);
// @$memCommissionAmount=$myOrder->memCommissionAmount =$_POST["memCommissionAmount"]);
@$memCommissionAmount=$myOrder->memCommissionAmount =stripslashes(strip_tags($_POST["memCommissionAmount"]));
@$sales_tax=$myOrder->sales_tax=$_POST['sales_tax'];

@$Noofvisit = $myOrder->Noofvisit = implode(',',$_POST["noofvisit"]);
@$Package_Autonew = $myOrder->Package_Autonew = implode(',',$_POST["autorrwnew"]);
@$Package_renwal = $myOrder->Package_renwal = implode(',',$_POST["renewalon"]);
@$Pckage_carryford = $myOrder->Pckage_carryford = implode(',',$_POST["carryforwed"]);
@$package_expire_date = $myOrder->package_expire_date = implode(',',$_POST["packageexpriydate"]);


@$gServiceName=$myOrder->gServiceName =implode(',',$_POST["gServiceName"]);
@$gServicePrice=$myOrder->gServicePrice =implode(',',$_POST["gServicePrice"]);
@$gServiceDiscount=$myOrder->gServiceDiscount =implode(',',$_POST["gServiceDiscount"]);
@$gServiceDiscoutInParentage=$myOrder->gServiceDiscoutInParentage =implode(',',$_POST["gServiceDiscoutInParentage"]);
@$gServiceFianlPrice=$myOrder->gServiceFianlPrice =implode(',',$_POST["gServiceFianlPrice"]);
@$TotalgiftAmount=$myOrder->TotalgiftAmount =stripslashes(strip_tags($_POST["TotalgiftAmount"]));

if(isset($_POST['giftapp']) && $_POST['giftapp'] != "")
{
  @$giftapp= $myOrder->giftapp = $_POST["giftapp"];

}
else
{
  @$giftapp= $myOrder->giftapp = 0;

}

@$pservicename=$myOrder->pservicename =implode(',',$_POST["pservicename"]);
@$pservicepackage=$myOrder->pservicepackage =implode(',',$_POST["pservicepackage"]);
@$pvisit=$myOrder->pvisit =implode(',',$_POST["pvisit"]);
// echo $eid;
// die;

if(isset($_POST['mytips']) && $_POST['mytips'] != "")
{
    @$tips = $myOrder->tips=$_POST['tips'];
}
else
{
   @$tips = $myOrder->tips=0;   
}


// echo $cid;
// echo $giftapp;
// echo $tips;
// echo $UsePoint;
// echo "<pre>";
// print_r($myOrder);
// echo $myOrder->id;
// die;


$NewOrderId=$myOrder->commit($myOrder->id,@$cid); 
$_SESSION["oid"] = $myOrder->id;
$OrderTime=date("Ymd");
$db=new db();

@$InvoiceNumber=$cid.$OrderTime.'0'.$NewOrderId;


$id=$NewOrderId;

$stmt=$db->prepare("update OrderMaster set  InvoiceNumber=:InvoiceNumber where id=:id");
$stmt->bindparam(":InvoiceNumber",$InvoiceNumber);
$stmt->bindparam(":id",$id);
$stmt->execute();

if(!empty($ServiceName))
{		
    $Addservie=$myOrder->Service($NewOrderId,$InvoiceNumber); 
}
if(!empty($gServiceName))
{
    $Addgservie=$myOrder->gService($NewOrderId,$InvoiceNumber); 
}
if(!empty($MembershipName))
{	
    $AddMembership=$myOrder->Membership($NewOrderId,$InvoiceNumber); 
}
if(!empty($ProdcutName))
{		
    $AddProdcut=$myOrder->Product($NewOrderId,$InvoiceNumber);
}


/// When TotalAmount = 0 after gift card applied
if(isset($_POST['giftapplied'])){
    //gift card

    $select_totaldata=$db->prepare("select cid,giftapp,TotalgiftAmount from OrderMaster where id=:oldorderid");
    $select_totaldata->bindparam(":oldorderid",$NewOrderId);
    $select_totaldata->execute();
    $data = $select_totaldata->fetch(PDO::FETCH_ASSOC);

    $totalgiftbal = $data["TotalgiftAmount"];
    $cid = $data['cid'];
    $usedbal = $data['giftapp'];


    $query=$db->prepare("select * from totalgiftdata where cid=:cid");
    $query->bindparam(":cid",$cid);
    $query->execute();

    if($query->rowCount() > 0)
    {
      $update_totaldata=$db->prepare("UPDATE `totalgiftdata` SET totalgiftbal = totalgiftbal + :totalgiftbal, usedbal = usedbal + :usedbal where cid=:cid");
      $update_totaldata->bindparam(":cid",$cid);
      $update_totaldata->bindparam(":totalgiftbal",$totalgiftbal);
      $update_totaldata->bindparam(":usedbal",$usedbal);
      $update_totaldata->execute(); 

    }else{
      $createdfk=$_SESSION["UserID"];

      $timedate=date("Y-m-d h:i:s");
      $insert_totaldata=$db->prepare("INSERT INTO totalgiftdata(userid,cid,totalgiftbal,createddate) VALUES(:userid,:cid,:TotalgiftAmount,:timedate)");
      $insert_totaldata->bindparam(":cid",$cid);
      $insert_totaldata->bindparam(":userid",$createdfk);
      $insert_totaldata->bindparam(":TotalgiftAmount",$totalgiftbal);
      $insert_totaldata->bindparam(":timedate",$timedate);
      $insert_totaldata->execute();
    }
    //gift card end
}


if($NewOrderId)
{
    $myactivite = new Activites(); // This function for data insert in Activities
    
    if(isset($_POST['CC_Payment'])){
        // return nothing , It is used because return/echo value cause exception on process-card.php while payment with CC. 
    }else{

        if($_POST['id']=="new")
        {
            //$myOrder->ActivitesCount($NewOrderId); // This function for data insert in CountActivites.
            $Titile=$myactivite->Titile = 'Placed new Order';	
            echo json_encode(['resonse'=>'Thanks you! your order successfully placed','NewOrderId'=>$NewOrderId]);
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update Order';
            echo json_encode(['resonse'=>'Thanks you! your order successfully updated','NewOrderId'=>$NewOrderId]);		
        }
    }
}
?>