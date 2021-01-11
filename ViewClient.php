<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
$actual_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$checking_url = 'http://' .$_SERVER['SERVER_NAME'].'/ViewClient';
if ($actual_url == $checking_url) 
{ 
    header('Location: ../index.php'); 
} 
$action = "";
if(isset($_GET["action"])){
    $action=$_GET["action"];
}
$FirstName="";
$LastName="";
$Phone="";
$email="";
$Solution="";
$PrivateNotes="";
$Address="";
$Zip="";
$City="";
$State="";
$ProfileImg="";
$ClientImg="";
$document="";
$fileName="";
if($action == "view"){
    //$MyCLient = $_GET['id'];
    $MyCLient = base64_decode($_GET['id']) ;
    $ViewClient=$db->prepare("select * from `clients` where id=:MyCLient");
    $ViewClient->bindValue(":MyCLient",$MyCLient, PDO::PARAM_INT);
    $ViewClient->execute();
    @$gbalance = $db->prepare("select (totalgiftbal-usedbal) As bal from `totalgiftdata` WHERE cid=:ClientsName");
    // $gbalance = $db->prepare("select SUM(TotalgiftAmount) As bal from `OrderMaster` WHERE cid=:ClientsName AND payment_status='CAPTURED' AND gServiceName='GiftCard' AND gstatus='0'");
    @$gbalance->bindValue(":ClientsName",$MyCLient,PDO::PARAM_INT);
    @$gbalanc=$gbalance->execute();
    @$gbal=$gbalance->fetchAll(PDO::FETCH_ASSOC);
    @$bal=@$gbal[0]['bal'];
    if($ViewClient->rowCount() > 0){
        while($GetClient=$ViewClient->fetch(PDO::FETCH_ASSOC)){
            $cid=$GetClient['id'];
            $FirstName=$GetClient['FirstName'];
            $LastName=$GetClient['LastName'];
            $Phone=$GetClient['Phone'];
            $email=$GetClient['email'];
            $ProfileImg = $GetClient['ProfileImg'];
            $ClientImg = $GetClient['ClientImg'];
            $Solution=$GetClient['Solution'];
            $PrivateNotes=$GetClient['PrivateNotes'];
            $Address=$GetClient['Address'];
            $Zip=$GetClient['Zip'];
            $City=$GetClient['City'];
            $State=$GetClient['State'];
            $Country=$GetClient['Country'];
            $document=$GetClient['document'];
            $fileName=$GetClient['fileName'];
            $ClientId=$GetClient['id']; 
            $tag=$GetClient['Tags']; 
            $SelectPackage =$GetClient['SelectPackage'];
            $package_sd= $GetClient['package_sd'];
            $package_ed=date_create($GetClient['package_ed']);
            $package_ed= date_format($package_ed,"M d,Y");
            $Createdfk = $GetClient['createdfk'];
            // $Tags = $GetClient['Tags'];
        }
    }
}


    //list of the service when user is editing the appointment
// $Createdfk = $Createdfk;

// $stmt2= $db->prepare("SELECT Service.* FROM `Service` WHERE Service.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id ) or FIND_IN_SET($Createdfk,Service.Users)"); 

$stmt2= $db->prepare("SELECT sv.* from `Service` AS sv 
    JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) 
    WHERE sv.createdfk IN (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id or u1.sid=u2.id) 
    and (u2.id=u3.adminid or u2.id=u3.id or u2.id=u3.sid) where u1.id=:id GROUP by u3.id) GROUP BY sv.id"); 



$stmt2->bindParam(':id', $Createdfk, PDO::PARAM_INT);
$stmt2->execute();  
$result_event2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
if(isset($_POST['serpro'])){
    $stmt = $db->prepare("SELECT * FROM `users` WHERE id=?");
    $stmt->execute([$_POST['serpro']]);
    $result = $stmt->fetch();
    echo json_encode(['resonse'=>$result]);die;
}




$db=new db(); 
@$id=base64_decode($_GET['id']);
$stmt= $db->prepare("SELECT clients.Tags,(SELECT GROUP_CONCAT(tag) FROM tag WHERE FIND_IN_SET(id,clients.Tags)) as tg,(SELECT GROUP_CONCAT(ID) FROM tag WHERE FIND_IN_SET(id,clients.Tags)) as tGID FROM `clients` WHERE id=:id"); 
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
@$finaltagforclient=explode(',', $result_tag[0]['tg']);
@$finaltagforclientid=explode(',', $result_tag[0]['tGID']);
@$newtag_array=array_combine($finaltagforclientid,$finaltagforclient);
function select_options($selected = array()){
    $uid=$_SESSION['UserID'];
    $db=new db(); 
    $stmt2= $db->prepare("SELECT * FROM `tag` where createdfk=$uid"); 

    // $$stmt2->bindParam(':uid', $uid, PDO::PARAM_INT);
    // 
    $stmt2->execute();
    $all_result_tag = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $output = '';
    foreach(($all_result_tag) as $item){
        $output.= '<option value="' . $item['tag'] . '"' . (in_array($item['id'], $selected) ? ' selected' : '') . '>' . $item['tag'] . '</option>';
    }
    return $output;
}

//********* fetch event history start

@$id=base64_decode($_GET['id']);
$stmt_appo= $db->prepare("SELECT event.eventstatus, event.ServiceProvider,event.datecreated as newdate,event.title,event.EventDate,event.cid,Service.ServiceName,event.id FROM `event`JOIN Service on event.ServiceName=Service.id WHERE cid=:id ORDER BY `event`.`datecreated` DESC"); 
$stmt_appo->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_appo->execute();
$result_appo = $stmt_appo->fetchAll();  

//********* fetch event history start


@$id=base64_decode($_GET['id']);
$stmt_file= $db->prepare("SELECT * FROM `attechment` WHERE UserID=:id ORDER BY `attechment`.`datecreated` DESC"); 
$stmt_file->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_file->execute();
$result_file = $stmt_file->fetchAll();  
@$id=base64_decode($_GET['id']);
$stmt_note= $db->prepare("SELECT * FROM `noteandclient` LEFT JOIN note ON noteandclient.noteid=note.id WHERE noteandclient.clientid=:id AND noteandclient.active='1' ORDER BY `note`.`datecreated` DESC"); 
$stmt_note->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_note->execute();
$result_note = $stmt_note->fetchAll();  
/* Start Display Order Table */
@$id=base64_decode($_GET['id']);
$stmt_Order= $db->prepare("SELECT DISTINCT OrderMaster.Noofvisit,OrderMaster.id as orid,OrderMaster.datecreated as odatecreated ,OrderMaster.InvoiceNumber,Service.ServiceName,Product.ProductTitle,MemberPackage.Name FROM `OrderMaster`
    LEFT  JOIN `OrderServic` ON OrderMaster.ServiceName = OrderServic.SeriveId LEFT JOIN `Service` ON OrderServic.SeriveId = Service.id 
    LEFT JOIN `OrderProduct` ON OrderMaster.ProdcutName = OrderProduct.ProdcutId LEFT JOIN `Product` ON OrderProduct.ProdcutId = Product.id 
    LEFT  JOIN `OrderMembership` ON OrderMaster.MembershipName = OrderMembership.MembershipId LEFT JOIN `MemberPackage` ON OrderMembership.MembershipId = MemberPackage.id
    WHERE OrderMaster.cid=:id AND OrderMaster.payment_status = 'CAPTURED' ORDER by odatecreated DESC ");
$stmt_Order->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_Order->execute();
$result_order = $stmt_Order->fetchAll();  

/* End Display Order Table */


@$id=base64_decode($_GET['id']);
$stmt_Order= $db->prepare("SELECT OrderMembership.Noofvisit,OrderMembership.id,OrderMembership.OrderId as orid,OrderMembership.OrderTime as odatecreated,MemberPackage.Name FROM `OrderMembership` LEFT JOIN `MemberPackage` ON OrderMembership.MembershipId = MemberPackage.id LEFT JOIN `OrderPayment` ON OrderPayment.OrderId = OrderMembership.OrderId WHERE OrderMembership.Cid=:id AND OrderPayment.payment_status='CAPTURED'");
$stmt_Order->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_Order->execute();
$result_order2 = $stmt_Order->fetchAll();  


/* COMMUNICATION HISTORY */
@$id=base64_decode($_GET['id']);
//@$id=$_GET['id'];
$stmt_cominuction= $db->prepare("SELECT * FROM `FullCom` WHERE cid=:id ORDER BY comtime DESC");
$stmt_cominuction->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_cominuction->execute();
$result_cominuction = $stmt_cominuction->fetchAll();  



/* COMMUNICATION HISTORY */
$client_id=$Createdfk;
$RelatedTo = $db->prepare("SELECT * FROM `clients` WHERE createdfk=:id");
$RelatedTo->bindValue(":id",$client_id,PDO::PARAM_INT);
$RelatedTo->execute();
$all_client=$RelatedTo->fetchAll(PDO::FETCH_ASSOC);
if(isset($Createdfk))
{
    $id=$Createdfk;
    $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$schcreateprmistion=$result['SchedulesCreate'];
    $ClientsLimit=$result['ClientsLimit'];
    // $Createdfk=$result['sid'];
    $usertype=$_SESSION['usertype'];
    @$From=$result['email'];
    
    // $stmt2=$db->prepare("SELECT * FROM `Service` WHERE createdfk=:id"); 
    // $stmtservaid2=$db->prepare("SELECT Service.* FROM `Service` JOIN users ON (Service.createdfk=users.id OR Service.createdfk=users.adminid OR Service.createdfk=users.sid) WHERE users.id=:id GROUP BY Service.id"); 
    // $stmtservaid2->bindParam(':id', $id, PDO::PARAM_INT);
    // $stmtservaid2->execute();
    // $result_event2 = $stmtservaid2->fetchAll(PDO::FETCH_ASSOC);
    // $result_noofserveiv=$stmtservaid2->rowCount(); 

    // $stmtcam=$db->prepare("SELECT Campaigns,id FROM `Campaigns` WHERE createdfk=:id and isactive=1"); 
    // $stmtcam->bindParam(':id', $id, PDO::PARAM_INT);
    // $stmtcam->execute();
    // $result_stmtcam = $stmtcam->fetchAll(PDO::FETCH_ASSOC);
    
    @$cdid=base64_decode($_GET['id']);
    $stmt2_doc=$db->prepare("SELECT DISTINCT fileName FROM `attechment` WHERE UserID=:cdid"); 
    $stmt2_doc->bindParam(':cdid', $cdid, PDO::PARAM_INT);
    $stmt2_doc->execute();
    $result_event_doc = $stmt2_doc->fetchAll(PDO::FETCH_ASSOC);
    $id=$Createdfk;
    $stmt= $db->prepare("SELECT * FROM `event_defult` WHERE UserID=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result_event = $stmt->fetch(PDO::FETCH_ASSOC);  
    $googlesync=$result_event['googlesync'];   

}
if(isset($_POST['curntuser']))
{
    $id = $_POST['curntuser'];
    $eidtClient = $db->prepare("SELECT * FROM `clients` WHERE createdfk=:id");
    $eidtClient->bindValue(":id",$id,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
    else
    {
        echo  json_encode(["error"=>"Sorry you have no customer"]);die;              
    }
}
if(isset($_REQUEST['ClientsName']))
{
    $ClientsName=$_POST['ClientsName']; 
    $eidtClient = $db->prepare("SELECT * FROM `clients` WHERE id=:ClientsName");
    $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}
// if(isset($_POST['cid']))
// {
//     $cid=$_POST['cid'];
//     $FirstName=$_POST['FirstName'];
//     $LastName=$_POST['LastName'];
//     $Phone=$_POST['Phone'];
//     $Email=$_POST['Email'];
//     $Address=$_POST['Address'];
//     $Zip=$_POST['Zip'];
//     $City=$_POST['City'];
//     $State=$_POST['State'];
//     $country=$_POST['country'];
//     $stmt=$db->prepare("UPDATE clients SET firstname=:FirstName, lastname=:LastName, email=:Email, Phone=:Phone, Address=:Address, Zip=:Zip, City=:City, State=:State, Country=:country  WHERE id=:cid");
//     $stmt->bindparam(":FirstName",$FirstName);
//     $stmt->bindparam(":LastName",$LastName);
//     $stmt->bindparam(":Email",$Email);
//     $stmt->bindparam(":Phone",$Phone);
//     $stmt->bindparam(":Address",$Address);
//     $stmt->bindparam(":Zip",$Zip);
//     $stmt->bindparam(":City",$City);
//     $stmt->bindparam(":State",$State);
//     $stmt->bindparam(":country",$country);
//     $stmt->bindparam(":cid",$cid);
//     $stmt->execute();
//     if($stmt)
//     {
//         echo json_encode(['resonse'=>'Client profile has been updated']);die;
//     }
//     else
//     {
//         echo json_encode(['error'=>'sorry something wrong']);die;
//     }
// }
@$id=base64_decode($_GET['id']);
$stmt_last_service= $db->prepare("SELECT event.EventDate FROM `event`JOIN Service on event.ServiceName=Service.id WHERE cid=:id ORDER BY `event`.`EventDate` DESC"); 
$stmt_last_service->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_last_service->execute();
$LastServiceDate = $stmt_last_service->fetchAll();  
$statement=$db->prepare("SELECT * FROM `countries` ");
$statement->execute();
$countryList = $statement->fetchAll(PDO::FETCH_ASSOC);
$createdid=$Createdfk;
$RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk=:createdid");
$RelatedTo2->bindValue(":createdid",$createdid,PDO::PARAM_INT);
$RelatedTo2->execute();
$all_Templeate=$RelatedTo2->fetchAll(PDO::FETCH_ASSOC);


// $stmt_cam= $db->prepare("SELECT Campaigns.Campaigns,Campaigns_Result.Operation,Client_Campaigns.cam_status,Campaigns.createdfk,Campaigns_Result.tyoe,Campaigns_Result.datatime,Campaigns_Result.cid,Campaigns_Result.camid FROM `Campaigns_Result` JOIN Campaigns ON Campaigns_Result.camid=Campaigns.id JOIN Client_Campaigns ON Client_Campaigns.cam_id=Campaigns_Result.camid WHERE Campaigns_Result.cid=:id AND Client_Campaigns.cam_status='No' ORDER BY Campaigns_Result.datatime ASC"); 
// $stmt_cam->bindParam(':id', $id, PDO::PARAM_INT);
// $stmt_cam->execute();
// $stmt_cam_Resulete = $stmt_cam->fetchAll();  

if(isset($_REQUEST['tid']))
{
    $tid=$_POST['tid']; 
    $eidtClient = $db->prepare("select * from `EmailTempleate` where id=:tid");
    $eidtClient->bindValue(":tid",$tid,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}
if(isset($_REQUEST['Servicename']))
{
    $Servicename=$_POST['Servicename']; 
    $eidtClient = $db->prepare("select Users,Info,Price,CommissionAmount,Duration from `Service` where id=:Servicename");
    $eidtClient->bindValue(":Servicename",$Servicename,PDO::PARAM_STR);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}

// if(isset($_REQUEST['UserName']))
// {

//        $UserName=$_POST['UserName']; 
//       $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id=:UserName");
//       $eidtUserName->bindValue(":UserName",$UserName,PDO::PARAM_STR);
//       $eidtUserName->bindValue(":userid",$Createdfk,PDO::PARAM_STR);
//       $editfile2=$eidtUserName->execute();
//       $all2=$eidtUserName->fetchAll(PDO::FETCH_ASSOC);

//       if($editfile2)
//       {
//           echo  json_encode(["resonse"=>$all2]);die;

//       }
//     }   

if(isset($_REQUEST['UserName']))
{

  $UserName=$_POST['UserName']; 
  $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id IN ($UserName)");
      // $eidtUserName->bindValue(":UserName",$UserName,PDO::PARAM_STR);
      // $eidtUserName->bindValue(":id",$id,PDO::PARAM_STR);
  $editfile2=$eidtUserName->execute();
  $all2=$eidtUserName->fetchAll(PDO::FETCH_ASSOC);

  if($editfile2)
  {
      echo  json_encode(["resonse"=>$all2]);die;

  }
}    

if(isset($_REQUEST['useradd']))
{
    $UserName=$_POST['useradd']; 
    $eidtUserName2 = $db->prepare("select * from `users` where id=:UserName");
    $eidtUserName2->bindValue(":UserName",$UserName,PDO::PARAM_STR);
    $editfile3=$eidtUserName2->execute();
    $allua=$eidtUserName2->fetch(PDO::FETCH_ASSOC);
    if($editfile3)
    {
        echo  json_encode(["resonse"=>$allua]);die;
    }
}
if(isset($_REQUEST['service_star_time']))
{

    $service_star_time=$_POST['service_star_time'];
    $serivename=$_POST['serivename']; 
    $eidtClient = $db->prepare("select Duration from `Service` where id=:serivename");
    $eidtClient->bindValue(":serivename",$serivename,PDO::PARAM_STR);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    $Duration=$all['Duration'];
    

    if($Duration=='0 Min')
    {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+0 minutes', $time));
        echo  json_encode(["resonse"=>$time]);die;
    }

    if($Duration=='15 Min')
    {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+15 minutes', $time));
        echo  json_encode(["resonse"=>$time]);die;
    }

    if($Duration=='30 Min')
    {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+30 minutes', $time));

        // $time = date("H:i", strtotime('+30 minutes', $time));

        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='1 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='2 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*2;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='3 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*3;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='4 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*4;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='5 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*5;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='6 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*6;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='7 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*7;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }  
    if($Duration=='8 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*8;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='9 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*9;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }  
    if($Duration=='10 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*10;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='11 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*11;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
    if($Duration=='12 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*12;
        $time = date('g:ia',$timestamp);
        echo  json_encode(["resonse"=>$time]);die;
    }
}
if(isset($_POST['CountrysName']))
{
    $CountrysName=$_POST['CountrysName']; 
    $eidtClient = $db->prepare("SELECT * FROM `countries` JOIN provinces ON countries.cid=provinces.country_id WHERE countries.countries_name=:CountrysName");
    $eidtClient->bindValue(":CountrysName",$CountrysName,PDO::PARAM_STR);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}
if(isset($_REQUEST['view_Message']))
{
    $id=$_REQUEST['data']; 
    $eidtClientcom = $db->prepare("SELECT FullCom.*,users.username,users.userimg FROM `FullCom` join users on users.id = FullCom.Createid WHERE FullCom.id=:id");
    $eidtClientcom->bindValue(":id",$id,PDO::PARAM_STR);
    $editfilecom=$eidtClientcom->execute();
    $allcom=$eidtClientcom->fetch(PDO::FETCH_ASSOC);
    if($editfilecom)
    {
        echo  json_encode(["resonse"=>$allcom]);die;
    }
}
if(isset($_REQUEST['Orderid']))
{
    $Orderid=$_POST['Orderid']; 
    $eidtserivce = $db->prepare("select * from `OrderServic` JOIN clients ON OrderServic.Cid=clients.id JOIN Service ON Service.id=OrderServic.SeriveId WHERE OrderServic.OrderId=:Orderid");
    $eidtserivce->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtserivcefile=$eidtserivce->execute();
    $all_serivce=$eidtserivce->fetchAll(PDO::FETCH_ASSOC);
    $Orderid=$_POST['Orderid']; 

    $Orderid=$_POST['Orderid']; 
    $giftcard = $db->prepare("select * from `Ordergift` JOIN clients ON Ordergift.Cid=clients.id WHERE Ordergift.OrderId=:Orderid");
    $giftcard->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $giftcard->execute();
    $allgiftcard=$giftcard->fetchAll(PDO::FETCH_ASSOC);

    $eidtproduct = $db->prepare("select * from `OrderProduct` JOIN clients ON OrderProduct.Cid=clients.id JOIN Product ON Product.id=OrderProduct.ProdcutId WHERE OrderProduct.OrderId=:Orderid");
    $eidtproduct->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtproductfile=$eidtproduct->execute();
    $all_prodcut=$eidtproduct->fetchAll(PDO::FETCH_ASSOC);
    $Orderid=$_POST['Orderid']; 
    $eidtmembership = $db->prepare("select * from `OrderMembership` JOIN clients ON OrderMembership.Cid=clients.id JOIN MemberPackage ON MemberPackage.id=OrderMembership.MembershipId WHERE OrderMembership.OrderId=:Orderid");
    $eidtmembership->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $eidtmembershipfile=$eidtmembership->execute();
    $all_membership=$eidtmembership->fetch(PDO::FETCH_ASSOC);
    $Orderid=$_POST['Orderid']; 
    $order = $db->prepare("select * from `OrderMaster` JOIN clients ON OrderMaster.Cid=clients.id WHERE OrderMaster.id=:Orderid");
    $order->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
    $orderfile=$order->execute();
    $all_order=$order->fetch(PDO::FETCH_ASSOC);
    if($orderfile)
    {
        echo  json_encode(["resonse_serive"=>$all_serivce,"allgiftcard"=>$allgiftcard,"resonse_product"=>$all_prodcut,"resonse_membership"=>$all_membership,"resonse_order"=>$all_order]);die;
    }
}
if(isset($_REQUEST['mytitle']))
{
    @$cdid = base64_decode($_REQUEST['clinetid']);
    $mytitle = $_REQUEST['mytitle'];

    $stmt2_docf=$db->prepare("SELECT * FROM `attechment` WHERE fileName=:mytitle AND UserID=:cdid"); 
    $stmt2_docf->bindParam(':cdid', $cdid, PDO::PARAM_INT);
    $stmt2_docf->bindParam(':mytitle', $mytitle, PDO::PARAM_INT);
    $stmt2_docf->execute();
    $result_event_docf = $stmt2_docf->fetchAll(PDO::FETCH_ASSOC);

    echo  json_encode($result_event_docf);die; 
}
if(isset($_REQUEST['service_star_timed']))
{


    $service_start_time = strtr($_REQUEST['service_star_timed'], '/', '-');
    $service_end_time = strtr($_REQUEST['service_end_timed'], '/', '-');
    

    //$fromdate= date("Y-m-d", strtotime($_REQUEST['service_star_timed']));
    $fromdate= $service_start_time;

    //$todate=date("Y-m-d", strtotime($_REQUEST['service_end_timed']));
    $todate = $service_end_time;
    
    @$cdid = base64_decode($_REQUEST['clinetid']);

    
    //$stmt2_docf = $db->prepare("SELECT *, DATE_FORMAT(datecreated,'%Y-%m-%d') AS niceDate FROM attechment WHERE DATE_FORMAT(datecreated,'%Y-%m-%d')>=:fromdate AND DATE_FORMAT(datecreated,'%Y-%m-%d')<=:todate AND UserID=:cdid"); 
    $stmt2_docf = $db->prepare("SELECT *, DATE_FORMAT(datecreated,'%m-%d-%Y') AS niceDate FROM attechment WHERE DATE_FORMAT(datecreated,'%m-%d-%Y')>=:fromdate AND DATE_FORMAT(datecreated,'%m-%d-%Y')<=:todate AND UserID=:cdid"); 


    $stmt2_docf->bindParam(':cdid', $cdid, PDO::PARAM_INT);
    $stmt2_docf->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $stmt2_docf->bindParam(':todate', $todate, PDO::PARAM_STR);
    $stmt2_docf->execute();
    
    $result_event_docf = $stmt2_docf->fetchAll(PDO::FETCH_ASSOC);  

    echo  json_encode($result_event_docf);die; 
}


if(isset($_REQUEST['Campaigns']))
{    
    // $usertimezone = date_default_timezone_get();
    // 
    $cam_id=$_REQUEST['listofCampaigns'];
    $cid=$_REQUEST['cid_update'];

    $lisofClient_Campaigns=$db->prepare("SELECT * FROM Client_Campaigns WHERE cam_id=:cam_id AND cid=:cid"); 
    $lisofClient_Campaigns->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisofClient_Campaigns->bindParam(':cid',$cid,PDO::PARAM_INT);
    $lisofClient_Campaigns->execute();
    $result_lisofClient_Campaigns = $lisofClient_Campaigns->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($result_lisofClient_Campaigns))
    {
      echo json_encode(["error"=>'Sorry this Campaigns is already assigned']);die;         
  }

  $createdate= date("Y-m-d H:i:s");  
  $cratedid=$Createdfk;
  $insert_dataincmas=$db->prepare("INSERT INTO Client_Campaigns(cam_id,cid,createdate,cratedid) VALUES(:cam_id,:cid,:createdate,:cratedid)");
  $insert_dataincmas->bindparam(":cam_id",$cam_id);
  $insert_dataincmas->bindparam(":cid",$cid);
  $insert_dataincmas->bindparam(":createdate",$createdate);
  $insert_dataincmas->bindparam(":cratedid",$cratedid);
  $insert_dataincmas->execute();      

  $lisofcmadetial=$db->prepare("SELECT * FROM Campaigns WHERE id=:cam_id"); 
  $lisofcmadetial->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
  $lisofcmadetial->execute();
  $result_lisofcmadetial = $lisofcmadetial->fetchAll(PDO::FETCH_ASSOC);

  foreach($result_lisofcmadetial as $row3)
  {

     $jsondata=$row3['flowchartdata'];
     $createdfk=$row3['createdfk'];
     $a = json_decode($jsondata, true);
     $aa =$a['nodeDataArray'];

     foreach ($aa as $key4=>$row4)
     {
        $cameventname=$row4['category'];

        if($cameventname!='Start')
        {       
            if($cameventname=='Mail')
            {

                $lisofmailcam=$db->prepare("SELECT * FROM Mail_Campaigns WHERE cam_id=:cam_id"); 
                $lisofmailcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
                $lisofmailcam->execute();       
                $result_lisofmailcam = $lisofmailcam->fetchAll(PDO::FETCH_ASSOC);

                foreach($result_lisofmailcam as $finalid) 
                {
                 $SendCampaignsTimezone=$finalid['SendCampaignsTimezone'];
                 $OnDay = $finalid['OnDay'];
                 $AtTime = $finalid['AtTime'];
                            $offset = $SendCampaignsTimezone; // GMT offset
                            $is_DST = FALSE; // observing daylight savings?
                            $timezone_name = timezone_name_from_abbr('', $offset * 3600, $is_DST); // e.g. "America/New_York"
                            @date_default_timezone_set($timezone_name);
                            $currentday =  date("D");
                            $datatime = date('Y-m-d h:i');

                            if($currentday==$OnDay || empty($OnDay))
                            {
                               $finalwaittime= date('Y-m-d').' '.$AtTime;
                           }
                           else if($OnDay=='Mon-Fri')
                           {

                              $date = new DateTime();
                              $date->modify('next '.'Mon'); 
                              $finalwaittime=$date->format('Y-m-d').' '.$AtTime; 

                          }
                          else if($OnDay=='Mon-Sat')
                          {

                              $date = new DateTime();
                              $date->modify('next '.'Mon'); 
                              $finalwaittime=$date->format('Y-m-d').' '.$AtTime; 

                          }
                          else if($OnDay=='Sat-Sun')
                          {

                              $date = new DateTime();
                              $date->modify('next '.'Sat'); 
                              $finalwaittime=$date->format('Y-m-d').' '.$AtTime; 

                          }

                          else
                          {

                            $date = new DateTime();
                            $date->modify('next '.$OnDay);
                            $finalwaittime=$date->format('Y-m-d').' '.$AtTime; 

                        }
                             // Create a new DateTime object

                        $mailfinalid=$finalid['id'];   
                        $lisofcmadetialsta=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid AND cameventname='Mail'"); 
                        $lisofcmadetialsta->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
                        $lisofcmadetialsta->bindParam(':cid',$cid,PDO::PARAM_INT);
                        $lisofcmadetialsta->bindParam(':mailfinalid',$mailfinalid,PDO::PARAM_INT);
                        $lisofcmadetialsta->execute();
                        $result_lisofcmadetialsta = $lisofcmadetialsta->fetchAll(PDO::FETCH_ASSOC);

                        if(empty($result_lisofcmadetialsta))
                        {   

                            if($key4==1)
                            {
                             $getformnumberarray=$key4-1;
                             $depandid =$a['linkDataArray'][$getformnumberarray]['from'];
                             @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                             if(empty($depstatus))
                             {
                                $depstatus='Yes';
                            }
                            $updateCampaigns=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                            $updateCampaigns->bindParam(':cid', $cid, PDO::PARAM_INT);
                            $updateCampaigns->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                            $updateCampaigns->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                            $updateCampaigns->bindParam(':mailfinalid', $mailfinalid, PDO::PARAM_STR);
                            $updateCampaigns->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                            $updateCampaigns->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                            $updateCampaigns->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                            $updateCampaigns->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                            $updateCampaigns->execute();
                        }
                        else

                        {

                          $eventnumber=$key4;  
                          $getformnumberarray=$key4-1;
                          $depandid =$a['linkDataArray'][$getformnumberarray]['from'];
                          @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                          if(empty($depstatus))
                          {
                            $depstatus='Yes';
                        }
                        $updateCampaigns=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                        $updateCampaigns->bindParam(':cid', $cid, PDO::PARAM_INT);
                        $updateCampaigns->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                        $updateCampaigns->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':mailfinalid', $mailfinalid, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                        $updateCampaigns->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                        $updateCampaigns->execute();
                    }

                }

            }               
        }

        if($cameventname=='Wait')
        {
            $lisofwaitcam=$db->prepare("SELECT * FROM Wait_Campaigns WHERE cam_id=:cam_id"); 
            $lisofwaitcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
            $lisofwaitcam->execute();       
            $result_lisoflisofwaitcam = $lisofwaitcam->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($finalwaittime))
            {
                $waitstarttime=$finalwaittime;          
            }
            else
            {
                $waitstarttime='';  
            }

            foreach ($result_lisoflisofwaitcam as $finalid2) 
            {

             $mailfinalid2=$finalid2['id']; 
             $WDuration=$finalid2['WDuration']; 
             $DurationType=$finalid2['DurationType']; 
             @$SendCampaignsTimezone=$finalid2['SendCampaignsTimezone'];

                             $offset = $SendCampaignsTimezone; // GMT offset
                             $is_DST = FALSE; // observing daylight savings?
                             $timezone_name = timezone_name_from_abbr('', $offset * 3600, $is_DST); // e.g. "America/New_York"
                             @date_default_timezone_set($timezone_name);

                             if($DurationType=='Day') 
                             {
                               $finalwaittime=date('Y-m-d H:i', strtotime($finalwaittime. ' + '.$WDuration.' days'));     
                           }
                           else if($DurationType=='Minutes')
                           {
                            $time = new DateTime($finalwaittime);
                            $time->add(new DateInterval('PT' . $WDuration . 'M'));
                            $finalwaittime = $time->format('Y-m-d H:i');

                        }
                        else if($DurationType=='Hours')
                        {
                            $time = new DateTime($finalwaittime);
                            $time->add(new DateInterval('PT' . $WDuration . 'H'));
                            $finalwaittime = $time->format('Y-m-d H:i');
                        }
                        else
                        {
                         $finalwaittime=date('Y-m-d H:i');   
                     }

                     $lisofcmadetialsta2=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid2 AND cameventname='Wait'"); 
                     $lisofcmadetialsta2->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
                     $lisofcmadetialsta2->bindParam(':cid',$cid,PDO::PARAM_INT);
                     $lisofcmadetialsta2->bindParam(':mailfinalid2',$mailfinalid2,PDO::PARAM_INT);
                     $lisofcmadetialsta2->execute();
                     $result_lisofcmadetialsta2 = $lisofcmadetialsta2->fetchAll(PDO::FETCH_ASSOC);

                     if(empty($result_lisofcmadetialsta2))
                     {

                        if($key4==1)
                        {
                         $getformnumberarray=$key4-1;
                         $depandid =$a['linkDataArray'][$getformnumberarray]['from'];   
                         @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                         if(empty($depstatus))
                         {
                            $depstatus='Yes';
                        }
                        $updateCampaigns2=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,2,:mailfinalid2,1,:waitstarttime,:depandid,:depstatus,:createdfk)"); 
                        $updateCampaigns2->bindParam(':cid', $cid, PDO::PARAM_INT);
                        $updateCampaigns2->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                        $updateCampaigns2->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                        $updateCampaigns2->bindParam(':mailfinalid2', $mailfinalid2, PDO::PARAM_STR);
                        $updateCampaigns2->bindParam(':waitstarttime', $waitstarttime, PDO::PARAM_STR);
                        $updateCampaigns2->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                        $updateCampaigns2->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                        $updateCampaigns2->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                        $updateCampaigns2->execute();
                    }
                    else
                    {

                       $eventnumber=$key4;
                       $datatime = date('Y-m-d h:i');  
                       $getformnumberarray=$key4-1;
                       $depandid =$a['linkDataArray'][$getformnumberarray]['from'];   
                       @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                       if(empty($depstatus))
                       {
                        $depstatus='Yes';
                    }
                    $updateCampaigns2=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid2,:eventnumber,:waitstarttime,:depandid,:depstatus,:createdfk)"); 
                    $updateCampaigns2->bindParam(':cid', $cid, PDO::PARAM_INT);
                    $updateCampaigns2->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                    $updateCampaigns2->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':mailfinalid2', $mailfinalid2, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':waitstarttime', $waitstarttime, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                    $updateCampaigns2->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                    $updateCampaigns2->execute();
                }
            }       
        }               
    }
    if($cameventname=='Click?')
    {
        $lisofclickcam=$db->prepare("SELECT * FROM Click_Campaigns WHERE cam_id=:cam_id"); 
        $lisofclickcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofclickcam->execute();      
        $result_lisoflisofclickcam = $lisofclickcam->fetchAll(PDO::FETCH_ASSOC);


        foreach ($result_lisoflisofclickcam as $finalid3) 
        {

         $cDurationType= $finalid3['clickDurationType'];
         $cWDuration=$finalid3['Maxwaittimeclick'];
         if($cDurationType=='Day')
         {
           $finalwaittime=date('Y-m-d H:i', strtotime($finalwaittime. ' + '.$cWDuration.' days'));     
       }
       else if($cDurationType=='Minutes')
       {
        $time = new DateTime($finalwaittime);
        $time->add(new DateInterval('PT' . $cWDuration . 'M'));
        $finalwaittime = $time->format('Y-m-d H:i');

    }
    else if($cDurationType=='Hours')
    {
        $time = new DateTime($finalwaittime);
        $time->add(new DateInterval('PT' . $cWDuration . 'H'));
        $finalwaittime = $time->format('Y-m-d H:i');
    }
    else
    {
     $finalwaittime=date('Y-m-d H:i');   
 }


 $mailfinalid3=$finalid3['id'];  
 $lisofcmadetialsta3=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid3 AND cameventname='Click?'"); 
 $lisofcmadetialsta3->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
 $lisofcmadetialsta3->bindParam(':cid',$cid,PDO::PARAM_INT);
 $lisofcmadetialsta3->bindParam(':mailfinalid3',$mailfinalid3,PDO::PARAM_INT);
 $lisofcmadetialsta3->execute();
 $result_lisofcmadetialsta3 = $lisofcmadetialsta3->fetchAll(PDO::FETCH_ASSOC);

 if(empty($result_lisofcmadetialsta3))
 {
    if($key4==1)
    {
      $getformnumberarray=$key4-1;
      $depandid =$a['linkDataArray'][$getformnumberarray]['from']; 
      @$depstatus=$a['linkDataArray'][$getformnumberarray]['text']; 
      if(empty($depstatus))
      {
        $depstatus='Yes';
    } 
    $updateCampaigns3=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid3,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
    $updateCampaigns3->bindParam(':cid', $cid, PDO::PARAM_INT);
    $updateCampaigns3->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $updateCampaigns3->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':mailfinalid3', $mailfinalid3, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':depandid', $depandid, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
    $updateCampaigns3->execute();
}
else
{
    $eventnumber=$key4;
    $getformnumberarray=$key4-1;
    $depandid =$a['linkDataArray'][$getformnumberarray]['from'];   
    @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
    if(empty($depstatus))
    {
        $depstatus='Yes';
    } 
    $updateCampaigns3=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid3,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
    $updateCampaigns3->bindParam(':cid', $cid, PDO::PARAM_INT);
    $updateCampaigns3->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $updateCampaigns3->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':mailfinalid3', $mailfinalid3, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':depandid', $depandid, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
    $updateCampaigns3->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
    $updateCampaigns3->execute();
}
}

}               
}
if($cameventname=='Open?')
{


    $lisofopencam=$db->prepare("SELECT * FROM Open_Campaigns WHERE cam_id=:cam_id"); 
    $lisofopencam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisofopencam->execute();       
    $result_lisoflisofopencam = $lisofopencam->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_lisoflisofopencam as $finalid4) 
    {
        $oDurationType= $finalid4['OpenDurationType'];
        $oWDuration=$finalid4['Maxwaittimeopen'];
        if($oDurationType=='Day')
        {
           $finalwaittime=date('Y-m-d H:i', strtotime($finalwaittime. ' + '.$oWDuration.' days'));     
       }
       else if($oDurationType=='Minutes')
       {
        $time = new DateTime($finalwaittime);
        $time->add(new DateInterval('PT' . $oWDuration . 'M'));
        $finalwaittime = $time->format('Y-m-d H:i');

    }
    else if($oDurationType=='Hours')
    {
        $time = new DateTime($finalwaittime);
        $time->add(new DateInterval('PT' . $oWDuration . 'H'));
        $finalwaittime = $time->format('Y-m-d H:i');
    }
    else
    {
     $finalwaittime=date('Y-m-d H:i');   
 }

 $mailfinalid4=$finalid4['id'];  

 $lisofcmadetialsta4=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid4 AND cameventname='Open?'"); 
 $lisofcmadetialsta4->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
 $lisofcmadetialsta4->bindParam(':cid',$cid,PDO::PARAM_INT);
 $lisofcmadetialsta4->bindParam(':mailfinalid4',$mailfinalid4,PDO::PARAM_INT);
 $lisofcmadetialsta4->execute();
 $result_lisofcmadetialsta4 = $lisofcmadetialsta4->fetchAll(PDO::FETCH_ASSOC);

 if(empty($result_lisofcmadetialsta4))
 {

    if($key4==1)
    {
        $getformnumberarray=$key4-1;
        $depandid =$a['linkDataArray'][$getformnumberarray]['from'];      
        @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];  
        if(empty($depstatus))
        {
            $depstatus='Yes';
        }
        $updateCampaigns4=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid4,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
        $updateCampaigns4->bindParam(':cid', $cid, PDO::PARAM_INT);
        $updateCampaigns4->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
        $updateCampaigns4->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':mailfinalid4', $mailfinalid4, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':depandid', $depandid, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
        $updateCampaigns4->execute();
    }
    else
    {
        $eventnumber=$key4;
        $getformnumberarray=$key4-1;
        $depandid =$a['linkDataArray'][$getformnumberarray]['from'];  
        @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];      
        if(empty($depstatus))
        {
            $depstatus='Yes';
        }
        $updateCampaigns4=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid4,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
        $updateCampaigns4->bindParam(':cid', $cid, PDO::PARAM_INT);
        $updateCampaigns4->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
        $updateCampaigns4->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':mailfinalid4', $mailfinalid4, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':depandid', $depandid, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
        $updateCampaigns4->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
        $updateCampaigns4->execute();
    }
}

}
}
if($cameventname=='Tags')
{
    $lisoftagcam=$db->prepare("SELECT id FROM Tag_Campaigns WHERE cam_id=:cam_id"); 
    $lisoftagcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisoftagcam->execute();        
    $result_lisoflisoftagcam = $lisoftagcam->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_lisoflisoftagcam as $finalid5) 
    {

        $mailfinalid5=$finalid5['id'];  
        $lisofcmadetialsta5=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid5 AND cameventname='Tags'"); 
        $lisofcmadetialsta5->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofcmadetialsta5->bindParam(':cid',$cid,PDO::PARAM_INT);
        $lisofcmadetialsta5->bindParam(':mailfinalid5',$mailfinalid5,PDO::PARAM_INT);
        $lisofcmadetialsta5->execute();
        $result_lisofcmadetialsta5 = $lisofcmadetialsta5->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result_lisofcmadetialsta5))
        {
            if(empty($finalwaittime))
            {
                $finalwaittime=date('Y-m-d H:i');   
            }


            if($key4==1)
            {
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];    
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid5,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
            else
            {
                $eventnumber=$key4;
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];      
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid5,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
        }       
    }
}

if($cameventname=='Check Tags')
{

    $lisofchecktagcam=$db->prepare("SELECT * FROM CheckTag_Campaigns WHERE cam_id=:cam_id"); 
    $lisofchecktagcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisofchecktagcam->execute();        
    $result_lisofchecktagcam = $lisofchecktagcam->fetchAll(PDO::FETCH_ASSOC);



    foreach ($result_lisofchecktagcam as $finalid8) 
    {

        $mailfinalid5=$finalid8['id'];  
        $lisofcmadetialsta8=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid5 AND cameventname='Check Tags'"); 
        $lisofcmadetialsta8->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofcmadetialsta8->bindParam(':cid',$cid,PDO::PARAM_INT);
        $lisofcmadetialsta8->bindParam(':mailfinalid5',$mailfinalid5,PDO::PARAM_INT);
        $lisofcmadetialsta8->execute();
        $result_lisofcmadetialsta8 = $lisofcmadetialsta8->fetchAll(PDO::FETCH_ASSOC);


        if(empty($result_lisofcmadetialsta8))
        {
            if(empty($finalwaittime))
            {
                $finalwaittime=date('Y-m-d H:i');   
            }


            if($key4==1)
            {
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];    
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid5,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
            else
            {
                $eventnumber=$key4;
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];      
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid5,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
        }       
    }
}


if($cameventname=='Task')
{
    $lisoftaskcam=$db->prepare("SELECT * FROM Task_Campaigns WHERE cam_id=:cam_id"); 
    $lisoftaskcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisoftaskcam->execute();       
    $result_lisoflisoftaskcam = $lisoftaskcam->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_lisoflisoftaskcam as $finalid6) 
    {

        $mailfinalid6=$finalid6['id'];  

        $lisofcmadetialsta6=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid6 AND cameventname='Task'"); 
        $lisofcmadetialsta6->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofcmadetialsta6->bindParam(':cid',$cid,PDO::PARAM_INT);
        $lisofcmadetialsta6->bindParam(':mailfinalid6',$mailfinalid6,PDO::PARAM_INT);
        $lisofcmadetialsta6->execute();

        $result_lisofcmadetialsta6 = $lisofcmadetialsta6->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result_lisofcmadetialsta6))
        {
            if(empty($finalwaittime))
            {
                $finalwaittime=date('Y-m-d H:i');   
            }

            if($key4==1)
            {
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];  
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];    
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }   

                $updateCampaigns6=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid6,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 

                $updateCampaigns6->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns6->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns6->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':mailfinalid6', $mailfinalid6, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns6->execute();
                
            }
            else
            {
                $eventnumber=$key4;
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];  
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];      
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }     
                $updateCampaigns6=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid6,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns6->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns6->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns6->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':mailfinalid6', $mailfinalid6, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns6->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns6->execute();
            }
        }           
    }
}
if($cameventname=='Note')
{
    $lisofnotecam=$db->prepare("SELECT id FROM Note_Campaigns WHERE cam_id=:cam_id"); 
    $lisofnotecam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisofnotecam->execute();       
    $result_lisoflisofnotecam = $lisofnotecam->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result_lisoflisofnotecam as $finalid7) 
    {

        $mailfinalid7=$finalid7['id'];  

        $lisofcmadetialsta7=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid7 AND cameventname='Note'"); 
        $lisofcmadetialsta7->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofcmadetialsta7->bindParam(':cid',$cid,PDO::PARAM_INT);
        $lisofcmadetialsta7->bindParam(':mailfinalid7',$mailfinalid7,PDO::PARAM_INT);
        $lisofcmadetialsta7->execute();
        $result_lisofcmadetialsta7 = $lisofcmadetialsta7->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result_lisofcmadetialsta7))
        {
            if(empty($finalwaittime))
            {
                $finalwaittime=date('Y-m-d H:i');   
            }

            if($key4==1)
            {
              $getformnumberarray=$key4-1;
              $depandid =$a['linkDataArray'][$getformnumberarray]['from']; 
              @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];  
              if(empty($depstatus))
              {
                $depstatus='Yes';
            }     
            $updateCampaigns7=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid7,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
            $updateCampaigns7->bindParam(':cid', $cid, PDO::PARAM_INT);
            $updateCampaigns7->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
            $updateCampaigns7->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':mailfinalid7', $mailfinalid7, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':depandid', $depandid, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
            $updateCampaigns7->execute();
        }
        else
        {
            $eventnumber=$key4;
            $getformnumberarray=$key4-1;
            $depandid =$a['linkDataArray'][$getformnumberarray]['from'];      
            @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
            if(empty($depstatus))
            {
                $depstatus='Yes';
            }
            $updateCampaigns7=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid7,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
            $updateCampaigns7->bindParam(':cid', $cid, PDO::PARAM_INT);
            $updateCampaigns7->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
            $updateCampaigns7->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':mailfinalid7', $mailfinalid7, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':depandid', $depandid, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
            $updateCampaigns7->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
            $updateCampaigns7->execute();
        }

    }       
}

}

if($cameventname=='Sms')
{

    $lisofchecktagcam=$db->prepare("SELECT * FROM Sms_Campaigns WHERE cam_id=:cam_id"); 
    $lisofchecktagcam->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
    $lisofchecktagcam->execute();        
    $result_lisofchecktagcam = $lisofchecktagcam->fetchAll(PDO::FETCH_ASSOC);



    foreach ($result_lisofchecktagcam as $finalid9) 
    {

        $mailfinalid5=$finalid9['id'];  
        $lisofcmadetialsta9=$db->prepare("SELECT * FROM campaigns_status WHERE camid=:cam_id AND cid=:cid AND eventid=:mailfinalid5 AND cameventname='Sms'"); 
        $lisofcmadetialsta9->bindParam(':cam_id',$cam_id,PDO::PARAM_INT);
        $lisofcmadetialsta9->bindParam(':cid',$cid,PDO::PARAM_INT);
        $lisofcmadetialsta9->bindParam(':mailfinalid5',$mailfinalid5,PDO::PARAM_INT);
        $lisofcmadetialsta9->execute();
        $result_lisofcmadetialsta9 = $lisofcmadetialsta9->fetchAll(PDO::FETCH_ASSOC);


        if(empty($result_lisofcmadetialsta9))
        {
            if(empty($finalwaittime))
            {
                $finalwaittime=date('Y-m-d H:i');   
            }


            if($key4==1)
            {
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];    
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,camevenstatus,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,1,:mailfinalid5,1,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
            else
            {
                $eventnumber=$key4;
                $getformnumberarray=$key4-1;
                $depandid =$a['linkDataArray'][$getformnumberarray]['from'];      
                @$depstatus=$a['linkDataArray'][$getformnumberarray]['text'];
                if(empty($depstatus))
                {
                    $depstatus='Yes';
                }
                $updateCampaigns5=$db->prepare("INSERT INTO campaigns_status(cid,camid,cameventname,eventid,eventnumber,datatime,depandid,depstatus,createdfk) VALUES(:cid,:cam_id,:cameventname,:mailfinalid5,:eventnumber,:finalwaittime,:depandid,:depstatus,:createdfk)"); 
                $updateCampaigns5->bindParam(':cid', $cid, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
                $updateCampaigns5->bindParam(':cameventname', $cameventname, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':mailfinalid5', $mailfinalid5, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':eventnumber', $eventnumber, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':finalwaittime', $finalwaittime, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depandid', $depandid, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':depstatus', $depstatus, PDO::PARAM_STR);
                $updateCampaigns5->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
                $updateCampaigns5->execute();
            }
        }       
    }
}
}
}
}


if($insert_dataincmas)
{
 echo json_encode(["resonse"=>'Campaigns added successfully.']);die;         
}
else
{
 echo json_encode(["error"=>'Somthing wrong please try again.']);die;         
}  

}
// $stmt= $db->prepare("SELECT clients.Campaignsid,(SELECT GROUP_CONCAT(Campaigns) FROM Campaigns WHERE FIND_IN_SET(id,clients.Campaignsid)) as Campaignsname,(SELECT GROUP_CONCAT(ID) FROM Campaigns WHERE FIND_IN_SET(id,clients.Campaignsid)) as campID FROM `clients` WHERE id=:id"); 
// $stmt->bindParam(':id', $id, PDO::PARAM_INT);
// $stmt->execute();
// $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
// @$finaltagforclient=explode(',', $result_tag[0]['Campaignsname']);
// @$finaltagforclientid=explode(',', $result_tag[0]['campID']);
// @$marketingtag_array=array_combine($finaltagforclientid,$finaltagforclient);


// $stmt= $db->prepare("SELECT Campaigns.Campaigns,Client_Campaigns.cam_id FROM `Client_Campaigns` JOIN Campaigns ON Client_Campaigns.cam_id=Campaigns.id WHERE Client_Campaigns.cid=:id AND Client_Campaigns.cam_status='No'"); 
//  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
// $stmt->execute();
// $marketingtag_array = $stmt->fetchAll(PDO::FETCH_ASSOC);


// $stmt2cam= $db->prepare("SELECT Campaigns.Campaigns,Campaigns.id as cam_id,Campaigns.flowchartdata as flowchartdata FROM `Client_Campaigns` JOIN Campaigns ON Client_Campaigns.cam_id=Campaigns.id WHERE Client_Campaigns.cid=:id AND Client_Campaigns.cam_status='Yes'"); 
//  $stmt2cam->bindParam(':id', $id, PDO::PARAM_INT);
// $stmt2cam->execute();
// $marketingtag_arraycomlet = $stmt2cam->fetchAll(PDO::FETCH_ASSOC);


function select_options2($selected = array()){
    $db=new db(); 
    $stmt2= $db->prepare("SELECT * FROM `Campaigns` "); 
    $stmt2->execute();
    $all_result_tag = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $output = '';
    foreach(($all_result_tag) as $item){
        $output.= '<option value="' . $item['Campaigns'] . '"' . (in_array($item['id'], $selected) ? ' selected' : '') . '>' . $item['Campaigns'] . '</option>';
    }
    return $output;
}

if(isset($_REQUEST['dcamid']))
{
    $id=$_REQUEST['dcamid'];
    $camcid=$_REQUEST['camcid'];
    $DeleteClientcam = $db->prepare("Delete From `Client_Campaigns` where cam_id=:id and cid=:camcid");
    $DeleteClientcam->bindValue(":id",$id,PDO::PARAM_INT);
    $DeleteClientcam->bindValue(":camcid",$camcid,PDO::PARAM_INT);
    $deletefilecam=$DeleteClientcam->execute();

    $DeleteClientcams = $db->prepare("Delete From `campaigns_status` where camid=:id and cid=:camcid");
    $DeleteClientcams->bindValue(":id",$id,PDO::PARAM_INT);
    $DeleteClientcams->bindValue(":camcid",$camcid,PDO::PARAM_INT);
    $deletefilecams=$DeleteClientcams->execute();

    $DeleteClientcamsr = $db->prepare("Delete From `Campaigns_Result` where camid=:id and cid=:camcid");
    $DeleteClientcamsr->bindValue(":id",$id,PDO::PARAM_INT);
    $DeleteClientcamsr->bindValue(":camcid",$camcid,PDO::PARAM_INT);
    $deletefilecamsr=$DeleteClientcamsr->execute();
    

    if($deletefilecam)
    {
     echo json_encode(["resonse"=>'Campaigns successfully Removed.']);die;         
 }
 else
 {
     echo json_encode(["error"=>'Somthing wrong please try again.']);die;         
 }

}

if(isset($_REQUEST['cammapid']))
{
    $cammapid = $_REQUEST['cammapid'];
    $stmt2cammapid= $db->prepare("SELECT flowchartdata FROM `Campaigns` where id=$cammapid"); 
    $stmt2cammapid->execute();
    $all_result_tag = $stmt2cammapid->fetch(PDO::FETCH_ASSOC);
    if($all_result_tag)
    {
     echo json_encode(["resonse"=>$all_result_tag]);die;         
 }

}


$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C5'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C6'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];   

$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C7'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];   

$button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C9'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$B4=$all_button4['button_name'];   

$button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C85'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$B5=$all_button5['button_name'];   

$button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C86'"); 
$button6->execute();
$all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
$B6=$all_button6['button_name'];   

$button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C87'"); 
$button7->execute();
$all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
$B7=$all_button7['button_name'];   

$button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C88'"); 
$button8->execute();
$all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
$B8=$all_button8['button_name'];   


$button9= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C12'"); 
$button9->execute();
$all_button9 = $button9->fetch(PDO::FETCH_ASSOC);
$B9=$all_button9['button_name'];   

$button10= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C15'"); 
$button10->execute();
$all_button10 = $button10->fetch(PDO::FETCH_ASSOC);
$B10=$all_button10['button_name'];   

$button11= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C16'"); 
$button11->execute();
$all_button11 = $button11->fetch(PDO::FETCH_ASSOC);
$B11=$all_button11['button_name'];   

$button12= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C17'"); 
$button12->execute();
$all_button12 = $button12->fetch(PDO::FETCH_ASSOC);
$B12=$all_button12['button_name'];   

$button13= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C62'"); 
$button13->execute();
$all_button13 = $button13->fetch(PDO::FETCH_ASSOC);
$B13=$all_button13['button_name'];   

$button14= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C56'"); 
$button14->execute();
$all_button14 = $button14->fetch(PDO::FETCH_ASSOC);
$B14=$all_button14['button_name'];   

$title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='3'"); 
$title2->execute();
$all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
$Ti2=$all_title2['TitleName'];

$button15= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C15'"); 
$button15->execute();
$all_button15 = $button15->fetch(PDO::FETCH_ASSOC);
$B15=$all_button15['button_name'];

$button16= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C16'"); 
$button16->execute();
$all_button16 = $button16->fetch(PDO::FETCH_ASSOC);
$B16=$all_button16['button_name'];

$button132= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C13'"); 
$button132->execute();
$all_button132 = $button132->fetch(PDO::FETCH_ASSOC);
$B132=$all_button132['button_name'];

$button18= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C18'"); 
$button18->execute();
$all_button18 = $button18->fetch(PDO::FETCH_ASSOC);
$B18=$all_button18['button_name'];

$button19= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C19'"); 
$button19->execute();
$all_button19 = $button19->fetch(PDO::FETCH_ASSOC);
$B19=$all_button19['button_name'];

if(isset($_REQUEST['customersid2']))
{
   $customersid2=$_POST['customersid2']; 
   $eidtClient2 = $db->prepare("select * from `clients` where id=:customersid2");
   $eidtClient2->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
   $editfile2=$eidtClient2->execute();
   $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
   if($editfile2)
   {
      echo  json_encode(["resonse"=>$all2]);die;
  }

}

if(isset($_POST['elink']))
{

  $myevent = base64_decode($_POST['elink']);
     // $myevent = $_POST['elink'];

  $EditEvent=$db->prepare("SELECT event.*,clients.ProfileImg FROM `event` LEFT JOIN clients ON event.cid=clients.id WHERE event.id=:myevent");
  $EditEvent->bindValue(":myevent",$myevent, PDO::PARAM_INT);
  $EditEvent->execute();
  $result = $EditEvent->fetch();

  echo json_encode(['resonse'=>$result]);die;
}

if(isset($_POST['dlink']))
{

  $myevent = base64_decode($_POST['dlink']);
  $DeleteClient = $db->prepare("DELETE from `event` where id=:myevent");
  $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
  $DeleteClient->execute();

  $DeleteClientA = $db->prepare("DELETE from `CountActivites` where AppointmentCreate=:myevent");
  $DeleteClientA->bindValue(":myevent",$myevent,PDO::PARAM_INT);
  $DeleteClientA->execute();
  echo json_encode(['resonse'=>'Appointment successfully deleted']);die;

}


if(isset($_POST['dNoteLink']))
{

  $mynote = base64_decode($_POST['dNoteLink']);

  $id=base64_decode($_POST['cliID']);

  $DeleteTag2 = $db->prepare("delete from `noteandclient` where clientid=:id AND noteid=:mynote");
  $DeleteTag2->bindValue(":id",$id,PDO::PARAM_INT);
  $DeleteTag2->bindValue(":mynote",$mynote,PDO::PARAM_INT);
  $DeleteTag2->execute();

  echo json_encode(['resonse'=>'Note successfully deleted']);die;

}




if(isset($_POST['packageorderid']))
{   

    $packagorderid = base64_decode($_POST['packageorderid']);
    $EditPckage=$db->prepare("SELECT MemberPackage.Name, OrderMembership.* FROM `OrderMembership` JOIN MemberPackage ON OrderMembership.MembershipId=MemberPackage.id WHERE OrderMembership.id=:packagorderid");
    $EditPckage->bindValue(":packagorderid",$packagorderid, PDO::PARAM_INT);
    $EditPckage->execute();
    $result_reuslt = $EditPckage->fetch();

    echo json_encode(['resonse'=>$result_reuslt]);die;      

}

if(isset($_POST['remainvisti']))
{
    $Noofvisit = $_POST['remainvisti'];
    $package_expire_date = $_POST['package_expire_date'];
    $OrderMembershipid = $_POST['OrderMembershipid'];
    $stmt=$db->prepare("UPDATE OrderMembership SET Noofvisit=:Noofvisit, package_expire_date=:package_expire_date WHERE id=:OrderMembershipid");
    $stmt->bindparam(":Noofvisit",$Noofvisit);
    $stmt->bindparam(":package_expire_date",$package_expire_date);
    $stmt->bindparam(":OrderMembershipid",$OrderMembershipid);
    $stmt->execute();
    if($stmt)
    {
       echo json_encode(['resonse'=>'Customer Package details has been successfully updated']);die;             
   }

}

if(isset($_POST['packaeditorderviewButtonid']))
{    

    $packaeditorderviewButtonid = base64_decode($_POST['packaeditorderviewButtonid']);
    $stmt=$db->prepare("DELETE  FROM OrderMembership  WHERE id=:packaeditorderviewButtonid");
    $stmt->bindparam(":packaeditorderviewButtonid",$packaeditorderviewButtonid);
    $stmt->execute();

    $cid=base64_decode($_REQUEST['dlinkcid']);
    $stmt2=$db->prepare("UPDATE clients SET SelectPackage='', employeeSold='', package_sd='', package_ed='' WHERE id=:cid");
    $stmt2->bindparam(":cid",$cid);
    $stmt2->execute();
    if($stmt2)
    {
       echo json_encode(['resonse'=>'Customer package has been successfully canceled']);die;             
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>


<script src="<?php echo base_url; ?>/assets/js/go.js"></script>



<style type="text/css">
    .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-right.datepicker-orient-top{z-index: 99999!important;}
    .activeuserdetial.activeuserdetialdocument{width: 100% !important;}
    .myfilelist {
        background: none!important;
        padding: 0!important;
        float: left!important;
    }
    #drop-area_1 .activeuserdetial {
        text-align: center;
    }
    .activeuserdetial.activeuserdetialdocument {
        width: 100% !important;
    }
    .activeuserdetial.activeuserdetialdocument h5{
        display: -webkit-box !important;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .activeuserdetial.activeuserdetialdocument a{
        display: -webkit-box !important;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }


    #drop-area{
        background: lightgray;
        min-height:200px;
        padding:25px;
        color: #00aeef;
        margin: 25px 0;
    }
    h3.drop-text{
        color:#999;
        text-align:center;
        font-size:2em;
    }
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width:1200px;
        }
    }
    span#userpoint{
        padding-left: 51px;
    }
   /* #orderdata{
        padding: 0 28px;
    }
    #to{
        width:50%;
        padding-top: 25px;
    }
    #notes,#to h5{
        background: #4095c7;
        color: white;
        padding: 5px ;
    }
    #carttable{
        margin: 25px auto!important;
        width: 98%!important;
    }
    tr#order_popup td{
        background: #4095c7;
        color: white;
        font-weight: 900;
        padding: 5px 10px;
    }
    .order_popup td input{
        border:0!important;
        margin: 5px;
        padding: 5px;
        }*/
        .dropbtn_cal {
            background-color: #dddddd;
            color: black;
            padding: 7px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            outline: none;
        }
        .dropdown_cal {
            position: relative;
            display: inline-block;
            margin: 0 10px;
            left: 90%;
        }
   /* span#serivetoaltprice{
        padding: 0 48px;
    }
    span#giftcardtotal{
        padding: 0 45px;
    }
    span#producttotalprice{
        padding: 0 47px;
    }
    span#membershiptotalprice{
        padding: 0 18.4px;
    }
    span#salestax{
        padding: 0 36px;
    }
    span#tips{
        padding: 0 70px;
    }
    span#userpoint{
        padding-left: 51px;
    }
    span#toatalprice{
        padding: 0 8px;
        font-weight: bold!important;
        color: #0b59a2!important;
        font-size: 20px;
    }
    .Signature{
        padding: 50px;
    }
    .last{
        text-align: center;
    }
    .img-circle {
        object-fit: cover;
        }*/
/*    .caluserlistd input.date.start.form-control{
        width: 42%;
        }*/


        #myDropdown_cal3{ background: white;    width: 100px;    left: 10px;}
        input.date.end.form-control{/*width: 49%;*/  padding: 0 5px;   }
        input.time.end.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;  }
        input.time.start.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;   }
        input.date.start.form-control{width: 43%;  padding: 0 5px;margin-right: 29px;  }        
        div#caluserlistd{
            background: aliceblue;
            /*height: 40px;*/
        }
        #caluserlistd .form-group{
            margin:0px;
        }
        .dropdown_cal {
            position: relative;
            display: inline-block;
            margin: 0 10px;
        }
        .dropdown-content_cal {
            display: none;
            position: absolute;
            z-index: 10;
            top: -35px;
            width: 185px;
        }
        .dropdown-content_cal button {
            color: black;
            text-decoration: none;
            display: block;
            height: 35px;
            width: 100%;
            margin: 0px 0;
            background-color: #f7f3f3 ;
        }
        .dropdown-content_cal button:hover {
            background-color: #dddddd;
        }
        .show_cal{
            display:inline-grid;
        }
        .customise_title {
            margin: 0 0 10px !important ;
        }
        li.token-search{
            width: 100%!important;
        }
        #drop-area_1 .activedatea{

            background: white;
            padding: 10px 0;
            margin:10px 0;
            box-shadow: 0.5px 0px 3px 1px #d7d7d7;
        }
        .activeusertime{
            text-align: right;
        }
        #addDocumentsDiv .dropdown_cal .btn-default:hover{
            background-color: #6a7af9;
            color: white;
        }
        #addDocumentsDiv .dropdown_cal .btn-default {
            background-color: white;
            border: 1px solid #ebedf2;
        }

/*            .activeuserimage{
                width: 5%;
                float: left;
                }*/
/*                .activeuserimage {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    float: left;
                    flex-wrap: wrap;
                    margin-left: -7%;
                    }*/
                    .activeuserimage img{
                        border-radius: 50%;
                    }
/*                .activeuserdetial {
                    width: 70%;
                    float: left;
                    padding-left: 5%;
                    margin-top: 12px;
                    padding-right: 5%;
                    }*/
                    #drop-area_1 .activeuserdetial h5 {
                        padding: 10px 0;
                    }

                    #drop-area_1{
                        display: flex;
                        margin-bottom: 25px;
                        flex-wrap: wrap;
                        /* justify-content: space-between;*/
                    }
                    div#drop-area_1 > div {
                        width: 21%;
                        margin: 10px !important;
                    }

                    @media (max-width: 768px){

                        .myfilelist {
                            min-width: 40%;
                        }
                    }
                    #drop-area_1 .activeuserdetial{
                        text-align: center;
                    }
                    #drop-area_1 .activeuserdetial h5{
                        padding: 10px 0;
                    }

                    .clerfix {
                        clear: both;
                    }
                    .activeusertime.badge.badge-pill.badge-primary {
                        color: white;
                    }
                    /*a#deleteTag { margin-right: -15%; margin-top: -15%; }*/
                    li.select2-selection__choice {
                        color: white !important;
                    }
                    .select2-container--default .select2-selection--multiple .select2-selection__choice{
                        background-color: #42bfd3!important;
                        border:1px solid #43c1d4!important;
                    }
                    .select2-container--default .select2-selection--multiple{
                        border-bottom: 1px solid #e9ecef!important;
                        border-top: 0!important;
                        border-right: 0!important;
                        border-left: 0!important;
                    }
                    span.select2.select2-container.select2-container--default.select2-container--focus{
                        width: 100%!important;
                    }
                    span.select2.select2-container.select2-container--default.select2-container{
                        width: 100%!important;
                    }
                    input.select2-search__field{
                        width: 100%!important;
                    }

                    .pull-right.usertime {
                        width: 12%;
                    }
                    .table-condensed tr{
                        text-align: center;
                    }
                    span.month,span.year{
                        padding: 5px 10px;
                        cursor: pointer !important;
                    }
                    td.day,th.datepicker-switch, th.next, th.prev{
                        cursor: pointer !important;
                    }
                    .profiletimeline {
                        border-left: none ! important;
                        /*margin-top: 14px ! important;*/
                    }
/*                .profiletimeline {
                    opacity: 1;
                    display: block;
                    width: 90%;
                    transition: .5s ease;
                    backface-visibility: hidden;
                    }*/
                    .profiletimeline {
                        padding: 10px 30px;
                        opacity: 1;
                        display: block;
                        transition: .5s ease;
                        backface-visibility: hidden;
                        position: relative;
                    }
                    .profiletimeline1 {
                        margin-top: 10px;
                        padding: 10px 20px;
                        opacity: 1;
                        display: block;
                        transition: .5s ease;
                        backface-visibility: hidden;
                        position: relative;
                    }

                    #order_history .activedatea.parent_of_operation{
                        border: 1px solid #00000021;
                        border-radius: 5px;
                    }
                    .profiletimeline1 .activedatea.parent_of_operation {
                        border-bottom: 1px solid #0000002e;
                        justify-content: center;
                        margin-bottom: 10px;
                        padding: 10px;
                    }

                    .activedatea.parent_of_operation .row{
                        align-items: center;
                    }
                    .displayOperation {
                        transition: .5s ease;
                        opacity: 0;
                        top: 0%;
                    /*transform: translate(15%, 45%);
                    -ms-transform: translate(15%, 45%);*/
                }
                .profiletimeline:hover .profiletimeline {
                    opacity: 0.3;
                }
                .profiletimeline:hover .displayOperation {
                    opacity: 1;
                }
                .profiletimeline1:hover .profiletimeline1 {
                    opacity: 0.3;
                }
                .profiletimeline1:hover .displayOperation {
                    opacity: 1;
                }
                .h3{
                    color: #1992bdcf;
                    text-align: center;
                }
                /*#editorder{
                    margin-left: 20px;
                    }*/
                    .ctype{
                        color: white;
                        height: 60px;
                        width: 60px;
                        padding: 18px 5px;
                        text-align: center;
                        font-size: 15px;
                        border-radius: 50px;
                        font-weight: bold;
                    }

                    .accordion{display: none;}
                    @media only screen and (max-width: 1024px) {
                        .customtab{display: none;}
                        .accordion {
                            display: block;
                            background-color: #eee;
                            color: #444;
                            cursor: pointer;
                            padding: 18px;
                            width: 100%;
                            border: none;
                            text-align: left;
                            outline: none;
                            font-size: 15px;
                            transition: 0.4s;
                        }

                    }

                    .qty .plus {
                        cursor: pointer;
                        display: inline-block;
                        vertical-align: top;
                        color: white;
                        width: 30px;
                        height: 30px;
                        font: 30px/1 Arial,sans-serif;
                        text-align: center;
                        /*border-radius: 50%;*/
                    }
                    .qty .minus {
                        cursor: pointer;
                        display: inline-block;
                        vertical-align: top;
                        color: white;
                        width: 30px;
                        height: 30px;
                        font: 30px/1 Arial,sans-serif;
                        text-align: center;
                        /*border-radius: 50%;*/
                        background-clip: padding-box;
                    }
                    #uploadimageModal{z-index: 99999999 !important;}
                    td.disabled.day{background: #eeeeee !important;}



                    /* contact tab new  */

                    .timelineTable td{

                        vertical-align: middle;
                        padding: 0.5rem;
                    }

                    .viewMailModel:hover{
                        color: #f95c2e;
                        cursor:pointer;
                        transition:0.5s;

                    }

                    .viewMailModel{
                        color: #3cabe1;

                    }

     /* #profileTimelineOld{
            display:none;

            } */

            .contactInfo{
            /*border: 1px solid #0f10113d ;
                margin: 5px;
               padding: 10px;
            margin-right: 10px;
            border-radius: 5px;*/

            padding: 0px;
            border: 1px solid #0f10113d;
            border-right: 1px solid #0f10113d;
            /*/margin-right: 10px;*/
        }

        .contactInfo p{
            font-size: 14px;
            padding: 7px;
        }


        .contactInfo h4{

            border-bottom: 1px solid #0f10113d;
            padding: 7px;


        }

        /*select2 css*/
        .select2-dropdown{
            padding: 5px;
        }
        .select2-results__option {
            border-radius: 5px;
        }

        .myDropdown_cal2 select2-container {
            padding: 5px;
            background: #f7f3f3;
        }
        /* contact tab new  */

        @media (min-width: 526px) and (max-width: 768px){

            .listcat_service{
                font-size: 13px !important;
            }

            

        }

        @media only screen and (max-device-width: 480px) {
         .product-img {
            height: 50px !important;
        }

        .pro-img-overlay:hover{
            display: flex !important;
            justify-content: space-around;
        }
        .product-img .pro-img-overlay a{
            margin: 20% 0px!important;
        }
    }


    /*view client docuemnts tab -> delete and download doument image design start*/

    .product-img {
     /* width: 110px;*/
     height: 100px;
     text-align: center;
     position: relative;

 }
 .product-img img{
  width: 100%;
  height: 100%;
  object-fit: contain;
  /*  //padding-top: 10px;*/
}
.product-img .pro-img-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  display: none;
  background: rgba(255, 255, 255, 0.8);
}
.product-img:hover .pro-img-overlay {
  display: flex;
  justify-content: center;
}
.product-img .pro-img-overlay a {
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  height: 40px;
  width: 40px;
  display: inline-block;
  border-radius: 100%;
  -webkit-border-radius: 100%;
  -o-border-radius: 100%;
  text-align: center;
  padding: 11px 0;
  color: #fff;
  margin: 20% 5px;
}

#drop-area_1 .activeuserdetial {
    padding: 5px;
    text-align: center;
}


.docName{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 100%;
    margin: 0px;
}


.btn-info.focus, .btn-info:focus, .btn-info:not(:disabled):not(.disabled).active:focus, .btn-info:not(:disabled):not(.disabled):active:focus, .show>.btn-info.dropdown-toggle:focus{box-shadow: unset!important;}

/*Pgination css start */

#pagination-container{
  float: right;
  margin-bottom: 10px;
}

.view_Message_button{
    cursor: pointer;
}

/*Pagination css end*/
</style>
<!--  <link  href="<?php echo  base_url ?>/assets/css/wickedpicker.css" rel="stylesheet" type="text/css" /> -->
<link href="<?php echo  base_url ?>/assets/css/tokenize2.css" rel="stylesheet" type="text/css" />

<!-- <link href="../assets/css/demo.css" rel="stylesheet" type="text/css" /> -->
<link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />
<link href="<?php echo  base_url ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">

<body class="skin-default fixed-layout mysunlessA15">

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure">
            </div>
            <p class="loader__label">
                <?php echo $_SESSION['UserName']; ?></p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <header class="topbar">
                <?php include 'TopNavigation.php'; ?>
            </header>
            <!-- ============================================================== -->
            <!-- End Topbar header -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <?php include 'LeftSidebar.php'; ?>
            <!-- ============================================================== -->
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">
                                <?php echo $Ti2; ?>
                            </h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Column start -->
                        <div class="col-lg-3 col-xlg-3 col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <center class="m-t-30">
                                        <?php
                                        if(empty($ProfileImg) || file_exists(base_url.'/assets/ProfileImages/'.$ProfileImg))
                                            {?>
                                             <img src="<?php echo base_url; ?>/assets/images/noimage.png" class="img-circle" width="100px" height="100px" >
                                             <?php
                                         }
                                         else
                                         {
                                            ?>
                                            <img src="<?php echo base_url; ?>/assets/ProfileImages/<?php echo $ProfileImg ; ?>"  class="img-circle" width="100px" height="100px">
                                            <?php
                                        }
                                        ?>
                                        <h4 class="card-title m-t-10">
                                            <?php echo ucfirst($FirstName)." ".ucfirst($LastName)  ?> &nbsp&nbsp 

                                            <button type="button" class="btn btn-info m-r-10 EditInfo" data-cid='<?php echo base64_encode($MyCLient); ?>' ><i class="fa fa-edit"></i></button>
                                        </h4>
                                        
                                    </center>
                                </div>
                                <div>
                                    <hr class="mb-1">
                                </div>


                                <!-- /////////////////////////////////// NAV /////////////////////////////////////////////// -->

                                <style>
                                    #side_menu{
                                        padding: 0px 5px !important;
                                    }
                                    #side_menu .fa{
                                        margin-right: 15px;
                                    }
                                    #side_menu .far{
                                        margin-right: 15px;
                                    }
                                    #side_menu .fas{
                                        margin-right: 15px;
                                    }
                                    #side_menu .nav{
                                        display: block !important;
                                    }
                                    #side_menu .nav-link{
                                        border-radius: 4px!important;
                                        color: #74788d!important;
                                        margin: 4px 0px!important;
                                        border-width: 0px!important;
                                        background: white!important;
                                    }
                                    #side_menu .nav-link:hover{
                                        background: #f2f3f7!important;
                                        color: #00acff!important;
                                        font-weight: 600!important;
                                    }
                                    #side_menu .active{
                                        font-weight: 600!important;
                                        background: #f2f3f7!important;
                                        border-width: 0px!important;
                                        color: #00acff!important;
                                    }
                                    #side_menu .nav-tabs{
                                     border-width: 0px!important; 
                                 }
                             </style>
                             <div class="card-body" id="side_menu">
                                <div class="nav nav-tabs customtab" role="tablist">

                                    <a class="nav-link active" data-toggle="tab" href="#NotesTab" role="tab" aria-selected="true"><i class="fas fa-sticky-note"></i>Note</a>


                                    <a class="nav-link" data-toggle="tab" href="#ServiceTab" role="tab" aria-selected="false"><i class="fas fa-calendar-check"></i> Event History</a>


                                    <a class="nav-link" data-toggle="tab" href="#DocumentsTab" role="tab" aria-selected="false"><i class="fas fa-folder-open"></i>Documents</a>


                                    <a class="nav-link" data-toggle="tab" href="#ContactTab" role="tab" aria-selected="false"><i class="far fa-id-card"></i>Contact Client</a>


                                    <a class="nav-link" data-toggle="tab" href="#OrderTab" role="tab" aria-selected="false"><i class="fas fa-history"></i>Order History</a>

                                    <a class="nav-link" data-toggle="tab" href="#PackageTab" role="tab" aria-selected="false"><i class="fas fa-box-open"></i>Package</a>


                                </div>

                            </div>


                            <div>
                                <hr class="mt-1">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title m-t-10">
                                    Contact Information 
                                </h4>
                                <small class="text-muted">Email address </small>
                                <h6>
                                    <?php echo $email ; ?> 
                                </h6>
                                <small class="text-muted p-t-30 db">Phone</small>
                                <h6>
                                    <?php echo $Phone ; ?> 
                                </h6>
                                <small class="text-muted p-t-30 db">Address</small>
                                <h6>
                                    <?php echo $Address.', '.$City.', '.$State.', '.$Zip.', '.$Country ; ?> 
                                </h6>
                                <div class="map-box">
                                    <iframe src="https://maps.google.com/?q=<?php echo $Address.', '.$City.', '.$State.', '.$Zip.', '.$Country ;?>&output=embed" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                                </div>

                            </div>
                            <div>
                                <hr>
                            </div>

                            <div class="card-body">
                                <h4 id="lastService" class="card-title m-t-10">
                                    Last Service Date 
                                </h4>
                                <h6 class="text-muted">
                                    <?php 
                                    $current_time = new DateTime();
                                    $current_time =  $current_time->format('Y-n-d g:ia')."<br>"; 
                                    foreach($LastServiceDate as $lastService)
                                    {
                                        if ( $lastService['EventDate'] <= $current_time ) {
                                            echo $lastService['EventDate'] ;

                                            break;
                                        }
                                    }
                                    ?> 
                                </h6>
                                <hr>    
                                <h4 id="giftbal"  class="card-title m-t-10" >
                                    Gift Card Balance
                                </h4>
                                <h6 class="text-muted" >
                                    <?php
                                    if($bal == "" || null)
                                    {
                                        echo "$0";
                                    }
                                    else
                                    {
                                        echo "$".$bal;
                                    }
                                    ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column End-->
                    <!-- Column Start-->
                    <div class="col-lg-9 col-xlg-9 col-md-8">
                        <div class="card">
                            <!-- Nav tabs -->

                            <!-- Tab panes -->
                            <style>
                                .card-header i{
                                    margin-right: 5px;
                                    font-size: 25px;
                                    vertical-align: middle;
                                }

                                .tab-pane .card-header{
                                    padding: 0.9rem 2.25rem;
                                    border-bottom: 1px solid #0000001c;
                                }
                                .sub_heading1{
                                    vertical-align: middle;
                                    font-weight: 600;
                                    font-size: 18px;
                                }

                                .sub_heading2{
                                    margin-left: 4px;
                                    font-size: 12px;
                                    color: #74788d;
                                }
                            </style>

                            <div class="tab-content">
                                <!-- <button class="accordion"> Note </button> -->
                                <div class="tab-pane active panel" id="NotesTab" role="tabpanel">
                                    <div class="card-header">
                                        <i class="fas fa-sticky-note"></i>
                                        <span class="sub_heading1">
                                            Note
                                        </span>
                                        <span class="sub_heading2"> Write your Daily Notes </span>
                                    </div>
                                    <div class="card-body">
                                        <div id="addNoteDiv">
                                            <button class="btn btn-info pull-right EditEventClientNoteAdd" data-id="<?php echo base64_decode($_GET['id']); ?>" ><i class="fa fa-plus"></i> <?php echo $B7; ?></button>
                                            <div class="clerfix"></div>       
                                            <!-- View Note Modal -->
                                            <div id="viewNoteModal" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">
                                                                View Note
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div>
                                                                <div class="d-flex">
                                                                    <div style="font-size: 2.2em;" title="Note Title"><span class="fa fa-credit-card"></span></div>
                                                                    <div id="noteTitle_view" style="font-size: 2.2em;text-align: left;padding-left: 25px;"></div>
                                                                </div>
                                                                <hr>
                                                                <div class="d-flex">
                                                                    <div style="font-size: 2.2em;"  title="Note Detail"><span class="fa fa-file-text"></span></div>
                                                                    <div id="noteDetails_view" style="font-size: 1.2em;text-align: left;padding-left: 15px;"></div>
                                                                </div>  
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">


                                                            <button type="button " class="btn btn-success" data-dismiss="modal" aria-label="Close"> Close </button>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- / View Note Modal -->
                                        </div>
                                        <br>
                                        <div class="profiletimeline1" style="padding: 0px;">

                                            <div class="table-responsive m-t-40 col-md-12">
                                                <table id="myAppointmentNote" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                  <thead>
                                                    <tr>
                                                      <th>User Name</th>
                                                      <th>Note Detail</th>
                                                      <th>Action</th>
                                                      <th>noteId</th>
                                                  </tr>
                                              </thead>
                                          </table>
                                      </div>

                                  </div>
                              </div>
                          </div>
                          <!-- second tab -->

                          <div class="tab-pane panel" id="ServiceTab" role="tabpanel">
                            <div class="card-header">
                                <i class="fas fa-calendar-check"></i>
                                <span class="sub_heading1">
                                    Event History
                                </span>
                                <span class="sub_heading2">know your Event History</span>
                            </div>
                            <div class="card-body" style="padding: 0px;">
                                <div id="addServiceDiv"></div>
                                <br>
                                <div class="profiletimeline1">
                                   <div style="width: 200px;float: right;text-align: right;"><label style="font-size: 15px;">Filter by Event Status</label>
                                    <select placeholder="Filter by Event Status" class="form-control w-200" id="myAppoitmentHistory_status_filter" > 
                                        <option value="" selected="">All</option>
                                        <option value="completed">Completed</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="canceled">Canceled</option>
                                        <option value="pending-payment">Pending Payment</option>
                                        <option value="in-progress">In-progress</option>
                                    </select>
                                </div>
                                <div class="table-responsive col-md-12">
                                    <table id="myAppoitmentHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                      <thead>
                                        <tr>
                                          <th>Service provider</th>
                                          <th>Appointment Detail</th>
                                          <th>Service Status</th>
                                          <th>Action</th>
                                          <th>eid</th>

                                      </tr>
                                  </thead>
                              </table>
                          </div>


                      </div>
                  </div>
              </div>
              <!--third tab-->
              <!-- <button class="accordion"> Marketing </button> -->
              <div class="tab-pane panel" id="MarketingTab" role="tabpanel">
                <div class="card-header">
                    <i class="fas fa-sticky-note"></i>
                    <span class="sub_heading1">
                        Marketing
                    </span>
                    <span class="sub_heading2"></span>
                </div>
                <div class="card-body">
                   <button class="btn btn-info pull-right" id="addcampaigns" data-toggle="modal" data-target="#addcampaignsmodal"><i class="fa fa-plus"></i> <?php echo $B13; ?></button>


                   <div class="modal fade" id="addcampaignsmodal" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Select Campaigns</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>

                      </div>
                      <form id="add_campaigns" autocomplete="off">
                        <div class="modal-body">
                            <div class="Loader"></div>
                            <input type="hidden" name="cid_update" id="cid_update" value="<?php echo $MyCLient; ?>">
                            <div class="form-group">
                             <label for="listofCampaigns">Select Campaigns *</label>
                             <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Campaigns" id="listofCampaigns" name="listofCampaigns">
                                 <option value="">Select Campaigns</option>
                                 <?php 
                                 foreach($result_stmtcam as $row2)
                                 {
                                    ?>
                                    <option value="<?php echo $row2['id']; ?>"><?php echo $row2['Campaigns']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span id="listofCampaignserror" style="color: red;"></span>
                        </div>

                        <div class="col-lg-12 col-md-12" style="padding: 15px 0;">
                            <div class="alert alert-success" id="resonse" style="display: none;">
                                <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                                <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                            </div>
                            <div class="alert alert-danger" id="error" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                            </div>
                        </div> 

                    </div>
                    <div class="modal-footer">

                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add</button>
                  </div>
              </form>
          </div>

      </div>
  </div>    
  <div class="Loader"></div>
  <?php 
  if(!empty($marketingtag_arraycomlet))
  {
    ?>


    <div class="inacitvecamp" style="padding: 10px 0;">
        <h6>Completed Campaigns</h6>
        <?php 
        /* Marketing tag */
        foreach ($marketingtag_arraycomlet as $key => $value)
        {  

            if(!empty($value['Campaigns']))
            {
                ?>
                <a href="#" id="viewcammap" class="viewcammap" data-viewmapid='<?php echo  $value['cam_id']; ?>'>
                    <span class="btn btn-secondary">
                        <span><?php echo $value['Campaigns'] ;?> </span>&nbsp
                        <span class="close pull-right deletecam" id='deletecam' title='Delete' data-id='<?php echo  $value['cam_id']; ?>'> ×</span>


                    </span>
                </a>
                <?php  
            }     
        }
        ?>
    </div>
    <?php
}
?>

<div class="modal fade" id="myModal_map" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Campaigns FlowChart</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
      </div>
      <div class="modal-body">
       <div id="sample">
          <div style="width: 100%; display: flex; justify-content: space-between">
            <!-- <div id="myPaletteDiv" style="width: 100px; margin-right: 2px; background-color: whitesmoke; border: solid 1px black"></div> -->
            <div id="myDiagramDiv" style="flex-grow: 1; height: 750px; border: solid 1px black"></div>

        </div>


        <!-- <button id="SaveButton" onclick="save()">Save</button> -->
        <!-- <button onclick="load()">Load</button> -->
        <textarea id="mySavedModel" style="width:100%;height:300px">
            { "class": "go.GraphLinksModel",
            "linkFromPortIdProperty": "fromPort",
            "linkToPortIdProperty": "toPort",
            "nodeDataArray": [ {"key":-1, "category":"Start", "loc":"113.0000000000002 -1215", "text":"Start"} ],
            "linkDataArray": []}
        </textarea>
        <!-- <button onclick="printDiagram()">Print Diagram Using SVG</button> -->
    </div>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>



<?php 
if(!empty($marketingtag_array))
{
    ?>
    <div class="acitvecamp">
        <h6>Acitive Campaigns</h6>
        <?php 
        /* Marketing tag */
        foreach ($marketingtag_array as $key => $value)
        {  

            if(!empty($value['Campaigns']))
            {
                ?>

                <span class="btn btn-secondary">
                    <span id="viewcammap" class="viewcammap" data-viewmapid='<?php echo  $value['cam_id']; ?>'><?php echo $value['Campaigns']; ?> </span>&nbsp
                    <span class="close pull-right deletecam" id='deletecam' title='Delete' data-id='<?php echo  $value['cam_id']; ?>'> ×</span>
                </span>
                <?php  
            }     
        }
        ?>
    </div> 
    <?php
}
?>         








<div class="profiletimeline">
    <?php
    if(!empty($stmt_cam_Resulete))
    {
        foreach ($stmt_cam_Resulete as $row) 
        {
            ?>
            <div class="activedatea parent_of_operation">
                <?php

                $createdfkid=@$row['createdfk'];
                $camid=@$row['camid'];

                $camname=@$row['Campaigns'];
                $ctpye=@$row['tyoe'];
                $createdfkstmt= $db->prepare("SELECT * FROM `users` WHERE id=:createdfkid"); 
                $createdfkstmt->bindParam(':createdfkid', $createdfkid, PDO::PARAM_INT);
                $createdfkstmt->execute();
                $createdfkresult = $createdfkstmt->fetch(PDO::FETCH_ASSOC);
                $img = $createdfkresult['userimg'];
                $username = $createdfkresult['username'];
                ?>
                <?php 
                $start  = date_create($row['datatime']);
    $end    = date_create(); // Current time and date
    $diff   = date_diff( $start, $end );
    ?>
    <div class="usertime">
        <div class="activeusertime  badge badge-pill badge-primary">
            <?php 
            if($diff->i<60 && $diff->h==0 && $diff->d==0 && $diff->m==0 && $diff->y==0)
            { 
                echo '<i class="far fa-clock"></i> ' .  $diff->i . ' Minutes ago ';
            }
            if($diff->i<60 && $diff->h>0 && $diff->d==0)
            { 
                echo '<i class="far fa-clock"></i> ' .  $diff->h . ' Hours ago ';
            }
            if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m==0 && $diff->y==0)
            { 
                echo '<i class="far fa-clock"></i> ' .  $diff->d . ' Days ago ';
            }
            if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m>0 && $diff->y==0)
            { 
                echo '<i class="far fa-clock"></i> ' .  $diff->m . ' Months ago ';
            }
            if($diff->i>0 && $diff->h>0 && $diff->d>0 && $diff->m>0 && $diff->y>0)
            { 
                echo '<i class="far fa-clock"></i> ' .  $diff->y . ' Years ago ';
            }
            ?>
        </div>

    </div>
    <div class="activeuserimage">
        <?php 
        if (empty($img)) 
        {
            ?>   
            <img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px">
            <?php
        } 
        elseif (file_exists(DOCUMENT_ROOT.'/assets/userimage/'.$img)) 
        {
            ?>
            <img src="<?php echo base_url; ?>/assets/userimage/<?php echo @$img; ?>" alt="user" class="" height="50px" width="50px">
            <?php
        }
        else 
        {
            ?>
            <img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px">
            <?php
        }
        ?>
        <br>
        <h6 style="margin-left: -64px;">
            <?php echo ucfirst($username); ?> 
        </h6>
    </div>

    <div class="activeuserimage">

        <div class="smsdiv ctype" style="background: #54a1f8;">
            <span> <?php echo $ctpye; ?> </span>
        </div>
    </div>

    <div class="activeuserdetial">
        <h5>
            <b>
                <?php echo $camname; ?> 
            </b>
        </h5>

        <?php 
        if($ctpye=='Mail')
            {?>
                <span><h6 style="display: inline-block;">
                    Subject : 
                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>   

        <?php 
        if($ctpye=='Wait')
            {?>
                <span><h6 style="display: inline-block;">
                    Wait Duration : 
                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>

        <?php 
        if($ctpye=='Tags')
            {?>
                <span><h6 style="display: inline-block;">
                    Tags : 
                </h6>
            </span>
            <?php 
            $addtaglist= explode(',', $row['Operation']);
            foreach ($addtaglist as $value) 
            {
                $geteventdetail4=$db->prepare("SELECT tag FROM tag WHERE id=:value"); 
                $geteventdetail4->bindParam(':value', $value, PDO::PARAM_INT);
                $geteventdetail4->execute();
                $result_geteventdetail4= $geteventdetail4->fetch(PDO::FETCH_ASSOC);
                echo $result_geteventdetail4['tag'].", ";
            }
            ?>
            <br>
            <?php
        }
        ?>   


        <?php 
        if($ctpye=='Task')
            {?>
                <span><h6 style="display: inline-block;">
                    Task Title : 
                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>       

        <?php 
        if($ctpye=='Note')
            {?>
                <span><h6 style="display: inline-block;">
                    Note Title : 
                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>

        <?php 
        if($ctpye=='Open')
            {?>
                <span><h6 style="display: inline-block;">

                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>

        <?php 
        if($ctpye=='Click')
            {?>
                <span><h6 style="display: inline-block;">

                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>

        <?php 
        if($ctpye=='Sms')
            {?>
                <span><h6 style="display: inline-block;">
                    Send Sms :
                </h6>
            </span>
            <?php echo $row['Operation']; ?>
            <br>
            <?php
        }
        ?>

        <span><h6 style="display: inline-block;">
            Time : 
        </h6>
    </span>
    <?php
    $date=date_create($row['datatime']);
    echo date_format($date,"M d, Y - H:i");
    ?>
    <br>    

</div>
</div>
<div class="clerfix">
</div>
<div class="clerfix">
</div>
<?php  } } ?>
</div>


</div>
</div>
<!--forth tab-->

<div class="tab-pane panel" id="DocumentsTab" role="tabpanel">
    <div class="card-header">
        <i class="fas fa-folder-open"></i>
        <span class="sub_heading1">
            Documents
        </span>
        <span class="sub_heading2">upload your documents</span>
    </div>
    <div class="card-body">
        <div id="addDocumentsDiv">

            <div class="dropdown_cal">
                <button onclick="myFunction2()" class="btn btn-default dropdown-toggle">Filter</button>
                <div id="myDropdown_cal2" class="dropdown-content_cal" style="margin: 35px -128px 0 0;">
                    <div class="btn-group">
                        <button class="btn btn-default" id="refresh_doc">Refresh</button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-default byuser" id="byuserd" >Filter By Date</button>
                    </div>
                    <div class="caluserlistd" id="caluserlistd" style="display: none;">
                        <div class="form-group">
                            <p id="datepairExample">
                                <?php $OrderTime=date("m/d/Y"); ?>
                                <input type="text" class="filter start form-control" placeholder="From" name="sd"  id="eventstardated"  value="" />
                                <input type="text" class="filter start form-control" placeholder="To" name="ed"  value="" id="eventenddated" />
                            </p>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-default byserviced" id="byserviced" >Filter By Title</button>
                    </div>
                    <div class="calserlistd" id="calserlistd" style="display: none;">
                        <select class="select2 m-b-10 select2-multiple notaved" style="width: 100%"  data-placeholder="Choose Title" id="listofcalserd" name="listofcalserd">
                            <option value="">Select Title</option>
                            <?php 
                            foreach($result_event_doc as $row2)
                            {
                                ?>
                                <option value="<?php echo $row2['fileName']; ?>"><?php echo $row2['fileName']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Add Documents Modal -->
            <div id="addDocumentsModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Add New Document
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" enctype="multipart/form-data" autocomplete="off" method="post" id="NewDocument">
                               <input type="hidden" name="clinetid" id="id" value="<?php echo @$ClientId;?>">
                               <input type="hidden" name="id" id="id" value="<?php echo $MyCLient; ?>">
                               <input type="file" class="dropify" data-height="200" name="document" id="fileInput" />


                               <div class="Loader"></div>

                               <div class="form-group">
                                <label><span class="help"> File Name *</span></label>
                                <input type="text" name="fileName" class="form-control" id="fileName" class="fileName" value="" placeholder="File Name">
                                <span class="filenmaerror" id="filenmaerror" style="color: red;"></span>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                  <button type="submit" name="Submit2" id="Submit2" class="btn waves-effect waves-light btn-info"><i class="fa fa-check">
                                  </i> Upload Document</button>
                                  <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times">
                                  </i> Cancel Document</button>
                              </div>
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                  </div>
              </div>
          </div>
      </div>
      <!-- / Add Documents Modal -->
  </div>
  <br>
  <div class="profiletimeline1">
    <div class="clerfix">
    </div>

    <div id="drop-area_1">

    </div>
    <div class="clerfix" style="clear: both;">
    </div>

    <div style="text-align: center; margin-top: 10px; ">
     <button type="button" class="btn btn-info m-r-10" id="drop-file-new"> Upload Document</button>
 </div>  
</div>
<div id="pagination-container"></div>

</div>
<div class="col-lg-12 col-md-12">
    <div class="alert alert-success" id="resonse_document" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h3 class="text-success">
            <i class="fa fa-check-circle ">
            </i>
            Success
        </h3>
        <p id="resonsemsg_document">
        </p>
    </div>
    <div class="alert alert-danger" id="error_document" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h3 class="text-danger">
            <i class="fa fa-exclamation-circle">
            </i>
            Errors
        </h3>
        <p id="errormsg_document">
        </p>
    </div>
</div>
</div>
<!--fifth tab-->

<div class="tab-pane panel" id="ContactTab" role="tabpanel">
    <div class="card-header">
        <i class="far fa-id-card"></i>
        <span class="sub_heading1">
            Contact Client
        </span>
        <span class="sub_heading2">Compose and Check Client's contact data.</span>
    </div>
    <div class="card-body">




     <div class="container" >
       <div class="row" style="margin-bottom: 10px;">
         <div class="col-sm contactInfo" style="border-bottom-left-radius: 5px;border-top-left-radius: 5px;">
          <h4>Email address </h4>
          <p><?php echo $email ; ?></p>


      </div>
      <div class="col-sm contactInfo">

        <h4 class=" db">Phone</h4>
        <p><?php echo $Phone ; ?></p>
    </div>
    <div class="col-sm contactInfo" style="border-bottom-right-radius: 5px;border-top-right-radius: 5px;">

        <h4 class=" db">Address</h4>
        <p><?php echo $Address.', '.$City.', '.$State.', '.$Zip.', '.$Country ; ?></p>
    </div>
</div>
</div>



<?php if($_SESSION['usertype']!='Admin'){ ?>

    <button class="btn btn-info pull-left m-r-10" id="sendMail" data-toggle="modal" data-target="#composeModal"><i class="fa fa-send">
    </i> SEND MAIL</button>
    <button class="btn btn-info pull-left m-r-10" id="sendSmsButton"><i class="fa fa-envelope">
    </i> SEND SMS</button>


<?php  } ?>
    

<!-- send Email/SMS Modal -->

<?php include_once('EmailSendModule.php'); include_once('SMSSendModule.php'); ?>


<!--New Design For timeline Start rahul-->
<div class="table-responsive m-t-40 col-md-12">
    <table id="myCommunicationHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
      <thead>
        <tr>
          <th>User Name</th>
          <th>Type</th>
          <th>Communication Detail</th>
          <th>Datetime</th>
      </tr>
  </thead>
</table>
</div>


<!--New Design For timeline End -->

<div class="modal fade" id="view_Message" role="dialog">
    <div class="modal-dialog" style="max-width: 800px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fullmsgsub">
                    Message
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="d-flex m-b-40">

                    <div>
                        <a href="javascript:void(0)"><img src="" alt="user"  height="50px" width="50px" class="img-circle FromUserImg"></a>
                    </div>
                    <div class="p-l-10 pt-3">
                        <h4 class="m-b-0 FromUser"></h4>
                    </div>
                </div>
                <div class="fullmsg">
                </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
       </div>
   </div>
</div>
</div>
</div>
<!-- Order Histroy Start -->

<div class="tab-pane panel" id="OrderTab" role="tabpanel">
    <div class="card-header">
        <i class="fas fa-history"></i>
        <span class="sub_heading1">
            Order History
        </span>
        <span class="sub_heading2">know your order history</span>
    </div>
    <div class="clerfix" id="order_history">
        <div class="profiletimeline1">

            <a href="<?php echo base_url.'/Order?vcid='.$_GET['id']; ?>" class="btn btn-info pull-right" style="margin: 10px;"><i class="fa fa-plus"></i> <?php echo $B14; ?></a>
            <div class="clerfix">
            </div>



            <div class="table-responsive m-t-40 col-md-12">
                <table id="myOrderHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>User Name</th>
                      <th>Order Detail</th>
                      <th>Sub total</th>
                      <th>Action</th>
                      <th>orid</th>

                  </tr>
              </thead>
          </table>
      </div> 

  </div> 
</div>
</div>

<!-- Order Histroy End -->

<div class="tab-pane panel" id="PackageTab" role="tabpanel">
    <div class="card-header">
        <i class="fas fa-box-open"></i>
        <span class="sub_heading1">
            Package
        </span>
        <span class="sub_heading2">know your package</span>
    </div>
    <div class="clerfix">
        <div class="profiletimeline1">
            <div class="clerfix">
            </div>

            <?php if($_SESSION['usertype']!='Admin'){ ?>
                <a href="<?php echo base_url.'/Order?vcid='.$_GET['id']; ?>&addpackage" target="_blank" class="btn btn-info pull-right" style="margin: 10px;">Add Package to Customer</a>
            <?php } ?>




            <div class="table-responsive m-t-40 col-md-12">
                <table id="myPackageHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>User Name</th>
                      <th>Package Detail</th>
                      <th>Time</th>
                      <th>orid</th>

                  </tr>
              </thead>
          </table>
      </div> 

  </div>
</div>
</div>
<!-- Package Histroy End -->



<?php include_once('BookEventModule.php'); ?>
<?php include_once('OrderInvoiceModel.php'); ?>
<?php include_once('EventTicketModel.php');?>
<?php include_once('viewclientdetail.php'); ?>
<?php include_once('viewuserdetail.php'); ?>




</div>
</div>
</div>
<!-- Column End-->
</div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- footer -->
<!-- ============================================================== -->
<?php include 'footer.php'; ?>
<!-- ============================================================== -->
<!-- End footer -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<?php include 'scripts.php'; ?>

<script id="code">
  var myDiagram = null;
  function init() {
    if (window.goSamples) goSamples();  
    var $ = go.GraphObject.make;  

    myDiagram =
    $(go.Diagram, "myDiagramDiv",  
    {
      initialContentAlignment: go.Spot.Top,
      allowDrop: true,  
      "LinkDrawn": showLinkLabel,  
      "LinkRelinked": showLinkLabel,
      "undoManager.isEnabled": true  
  });


    myDiagram.addDiagramListener("Modified", function(e) {
      var button = document.getElementById("SaveButton");
      if (button) button.disabled = !myDiagram.isModified;
      var idx = document.title.indexOf("*");
      if (myDiagram.isModified) {
        if (idx < 0) document.title += "*";
    } else {
        if (idx >= 0) document.title = document.title.substr(0, idx);
    }
});

    

    function nodeStyle() {
      return [

      new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
      {

          locationSpot: go.Spot.Center
      }
      ];
  }


  function makePort(name, align, spot, output, input) {
      var horizontal = align.equals(go.Spot.Top) || align.equals(go.Spot.Bottom);

      return $(go.Shape,
      {
          fill: "transparent",  
          strokeWidth: 0,  
          width: horizontal ? NaN : 8,  
          height: !horizontal ? NaN : 8,  
          alignment: align,  
          stretch: (horizontal ? go.GraphObject.Horizontal : go.GraphObject.Vertical),
          portId: name,  
          fromSpot: spot,  
          fromLinkable: output,  
          toSpot: spot,  
          toLinkable: input,  
          cursor: "pointer",  
          mouseEnter: function(e, port) {  
            if (!e.diagram.isReadOnly) port.fill = "rgba(255,0,255,0.5)";
        },
        mouseLeave: function(e, port) {
            port.fill = "transparent";
        }
    });
  }

  function textStyle() {
      return {
        font: "bold 9pt Helvetica, Arial, sans-serif",
        stroke: "whitesmoke"
    }
}

    myDiagram.nodeTemplateMap.add("",  // the default category
      $(go.Node, "Table", nodeStyle(),

        $(go.Panel, "Auto",

          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
          {
              margin: 8,
              maxSize: new go.Size(160, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
          },
          new go.Binding("text").makeTwoWay())
          ),

        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
        ));


    myDiagram.nodeTemplateMap.add("Start",
      $(go.Node, "Table", nodeStyle(),{ deletable: false },
        $(go.Panel, "Auto",

          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 40), fill: "#79C900", strokeWidth: 0 }),
          $(go.TextBlock, "Start", textStyle(),
            new go.Binding("text"))
          ),

        makePort("L", go.Spot.Left, go.Spot.Left, true, false),
        makePort("R", go.Spot.Right, go.Spot.Right, true, false),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
        ));

    
    myDiagram.linkTemplate =
    $(go.Link,  
    {
      routing: go.Link.AvoidsNodes,
      curve: go.Link.JumpOver,
      corner: 5,  toShortLength: 4,
      relinkableFrom: true,
      relinkableTo: true,
      reshapable: true,
      resegmentable: true,

      mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
      mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; },
      selectionAdorned: false
  },
  new go.Binding("points").makeTwoWay(),
  $(go.Shape, 
      { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
  $(go.Shape, 
      { isPanelMain: true, stroke: "gray", strokeWidth: 2 },
      new go.Binding("stroke", "isSelected", function(sel) { return sel ? "dodgerblue" : "gray"; }).ofObject()),
  $(go.Shape, 
      { toArrow: "standard", strokeWidth: 0, fill: "gray"}),
  $(go.Panel, "Auto", 
      { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
      new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#F8F8F8", strokeWidth: 0 }),
          $(go.TextBlock, "Yes", 
          {
              textAlign: "center",
              font: "9pt helvetica, arial, sans-serif",
              stroke: "#333333",
              editable: true
          },
          new go.Binding("text").makeTwoWay())
          )
  );
    function showLinkLabel(e) {
     var label = e.subject.findObject("LABEL");
     if (label !== null)
       var val = false;
   var cat = e.subject.fromNode.data.category;
   if( cat === "Click?")
   {
       val =true;
   }else if(cat =="Open?"){
       val =true;
   }
   else if(cat =="Check Tags"){
       val =true;
   }
   label.visible = val;
}

myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

load();  

} 

function load() {
    myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
}



$(document).on('click',"#viewcammap",function(e)
{
    e.preventDefault()

    if($(e.target).attr('id') =="deletecam")
    {
        return false;
    }

    $('#sample').html('');
    $(".Loader").show();
    var cammapid = $(this).attr("data-viewmapid");
    $.ajax({
     type: "POST",
     url: '?cammapid',
     data: {'cammapid':cammapid},
     dataType:'JSON',
     success: function(data)
     {
        $('#sample').append('<div style="width: 100%; display: flex; justify-content: space-between"><div id="myDiagramDiv" style="flex-grow: 1; height: 750px; border: solid 1px black"></div></div><textarea hidden id="mySavedModel" style="width:100%;height:300px">'+data.resonse.flowchartdata+'</textarea>');
        init();
        $('#myModal_map').modal('show');
        $(".Loader").hide();

    }
});   
});


</script>



<script type="text/javascript">
    $(document).ready(function(){

        $("#byuserd").click(function(){
          $("#calserlistd").hide();
          $("#select2-listofcalserd-container").text("");
      });  

        $("#byserviced").click(function(){
          $("#caluserlistd").hide();
          $("#eventstardated").val("");
          $("#eventenddated").val("");
      }); 

        $("#refresh_doc").click(function(){
            $("#calserlistd").hide();
            $("#select2-listofcalserd-container").text("");
            $("#caluserlistd").hide();
            $("#eventstardated").val("");
            $("#eventenddated").val("");
            dataTableFileUpload();
        });


        var getUid = '<?php echo $_GET['id'] ?>';
        

        $(document).on('click','#sendSmsButton',function(){

          var values = [];
        
            var id =window.atob(getUid);

            $("#NewSms option").each(function(){
              value = $(this).val();
              cid = value.split(',')[1];
              if(cid == id){
                values.push(value);
                return;
            }
        }); 

          $("#NewSms #to").val(values).change();
          $("#sendSmsModal").modal('show');
      });

        function isEmpty(obj) {
            for(var key in obj) {
                if(obj.hasOwnProperty(key))
                    return false;
            }
            return true;
        }

        dataTableFileUpload();

        function dataTableFileUpload(query={}){


          $('#pagination-container').pagination({
              className: 'paginationjs-theme-blue paginationjs-big',
              dataSource: function(done){

                if(!isEmpty(query))
                {

                    if(query.service_star_timed && query.service_end_timed)
                    {


                        $.ajax({
                         url:window.location.href+"?action=getfilebyname",
                         type:"post",
                         data:{service_star_timed:query.service_star_timed,service_end_timed:query.service_end_timed,clinetid:getUid},
                         success:function(data){
                          $('#pagination-container').html("");

                          done(JSON.parse(data));

                      }
                  });
                    }
                    else if(query.docTitle)
                    {

                        $.ajax({
                            url:window.location.href+"?action=getfilebyname",
                            type:"post",
                            data:{mytitle:query.docTitle,clinetid:getUid},
                            success:function(data){
                              $('#pagination-container').html("");

                              done(JSON.parse(data));

                          }
                      });
                    }

                }
                else
                {

                 $.ajax({
                    url:'<?php echo base_url; ?>/Exec/Exec_Edit_Event?fileuploadHis='+getUid,
                    type:"get",
                    data:{query:query},
                    success:function(data){
                      $('#pagination-container').html("");
                      done(JSON.parse(data));
                  }
              });
             }


         },
         callback: function(data, pagination) {
          var html = RenderData(data);
          $('#drop-area_1').html(html);
      }
  });

      }

      function RenderData(data){

          $("#drop-area_1").html("");
          $.each(data, function(k,v)
          {
             var fileName = v.document;
             fileExtension = fileName.replace(/^.*\./, '');
             if(fileExtension=='xlsx')
             {
                 $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/images/xsl.png" alt="user" class="" height="50px" width="50px"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.fileName+'</label></b></span></div></div></div>');
             }
             else if(fileExtension=='doc')
             {
                 $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/images/msdoc.png" alt="user" class="" height="50px" width="50px"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.fileName+'</label></b></span></div></div></div>');
             }
             else if(fileExtension=='pdf')
             {
                 $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/images/pdf.png" alt="user" class="" height="50px" width="50px"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.fileName+'</label></b></span></div></div></div>');
             }
             else if(fileExtension=='txt')
             {
                 $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/images/txt.png" alt="user" class="" height="50px" width="50px"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.fileName+'</label></b></span></div></div></div>');
             }
             else if(fileExtension=='csv')
             {
                 $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/images/export-csv.png" alt="user" class="" height="50px" width="50px"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.document+'>'+v.fileName+'</label></b></span></div></div>');
             }
             else
             {  
                $('#drop-area_1').append('<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial"><div class="product-img"><img src="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" alt="user" class="cDocImg"><div class="pro-img-overlay"><a id="" data-id="" href="<?php echo base_url; ?>/assets/ClientDocs/'+v.document+'" class="bg-info" target="_blank" download><i class="fas fa-download"></i></a> <a id="deleteFileButton" data-id="'+v.id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div></div><span><b><label class="docName">'+v.fileName+'</label></b></span></div></div>');
            }
        });
$(".Loader").hide();

}

$("#eventenddated").change(function(){
    var clinetid = '<?php echo $ClientId; ?>';
    var service_star_timed = $("#eventstardated").val();
    var service_end_timed = $(this).val();
    var dateFileFilter = {service_star_timed,service_end_timed};
    dataTableFileUpload(dateFileFilter);

});



$('#listofcalserd').on('change',function(){

    $(".Loader").show();
    var mytitle = {docTitle : this.value};
    var clinetid = '<?php echo $ClientId; ?>';

    dataTableFileUpload(mytitle);


});


$(document).on('click','#deleteFileButton',function(e){

   e.preventDefault();
   swal({
    title: "Are you sure?",
    text: "Once deleted, you will lost  this file!",
    icon: "warning",
    buttons: true,
}).then((willDelete)=>{   
    if (willDelete){
      var delid = $(this).attr('data-id');

      $.ajax({
        dataType:"json",
        type:"post",
        url:'<?php echo base_url; ?>/Exec/Exec_Edit_Event?fileuploadDel='+delid,
        success: function(data)
        {
            if(data.resonse){

                $("a[data-id="+delid+"]").parents(".myfilelist").remove();
                dataTableFileUpload();
                swal("Success!", "Your document deleted!", "success");
            }
            else if(data.error){
                swal("Something wants wrong please try again!");
            }
        }
    });
  }
  else{
    return false;
}
});
});



$("#NewDocument").submit(function(e) {
    e.preventDefault();
    if($("#fileInput-error").text()!=""){
        $(".dropify-preview").hide();
    }
}).validate({
  rules: {
    fileName: 'required',
    document: {
      required: true,
      extension: "jpeg|jpg|xlsx|doc|pdf|txt|csv|png"
  }
}
,
messages: {
    fileName:  "Please Enter File Name",
    document:{
        extension: "Invalid file format",
        required: "Please add file",
    } 
}
,submitHandler: function(form) {
    $(".Loader").show();
    var data = new FormData(form);
    var ffName = $('#fileName').val();

    jQuery.ajax({
        dataType:"json",
        type:"post",
        data:data,
        contentType: false,
        processData: false,
        url:'<?php echo EXEC; ?>Exec_client_attech',
        success: function(data)
        {
            if(data.resonse2)
            {
                $("#resonse_document").show().fadeOut(2000);
                $( '#NewDocument' ).each(function(){this.reset();});
                $(".Loader").hide();

            }
            else if(data.error2)
            {
                $("#error_document").show().fadeOut(2000);
                $('#errormsg_document').html('<span>'+data.error2+'</span>');
                $(".Loader").hide();
            }else{
                $("#error_document").show().fadeOut(2000);
                $('#errormsg_document').html('<span>'+data.error2+'</span>');
                $(".Loader").hide();
            }
            dataTableFileUpload();
            $('#listofcalserd').append('<option value="'+ffName+'">'+ffName+'</option>').trigger('change');
            $('#addDocumentsModal').modal('hide');
        }
    }
    );
}
});

function diffYMDHMS(finalDate) {

  let years,months,days,hours,minutes,seconds;

  var date1 = moment().utc().format("DD-MM-YYYY HH:mm:ss");
  date1 = moment(date1,'DD-MM-YYYY HH:mm:ss');
  var date2 = moment(finalDate).format("DD-MM-YYYY HH:mm:ss");
  date2 = moment(date2,'DD-MM-YYYY HH:mm:ss');

  years =  Math.abs(date1.diff(date2, 'year'));
  date2.add(years, 'years');

  months = Math.abs(date1.diff(date2, 'months'));
  date2.add(months, 'months');

  days = Math.abs(date1.diff(date2, 'days'));
  date2.add(days, 'days');

  hours = Math.abs(date1.diff(date2, 'hours'));
  date2.add(hours, 'hours');

  minutes = Math.abs(date1.diff(date2, 'minutes'));
  date2.add(minutes, 'minutes');

  seconds = Math.abs(date1.diff(date2, 'seconds'));
  date2.add(seconds, 'seconds');


  return { years, months, days, hours, minutes, seconds};
}

function getTimeLine(date){
    if(date.years>0){
        return '<i class="far fa-clock"></i> ' +date.years + ' Years ago ';
    }else if(date.months>0){
        return '<i class="far fa-clock"></i> ' +date.months + ' Months ago ';
    }else if(date.days>0){
        return '<i class="far fa-clock"></i> ' +date.days + ' Days ago ';
    }else if(date.hours>0){
        return '<i class="far fa-clock"></i> ' +date.hours + ' Hours ago  ';
    }else if(date.minutes>0){
        return '<i class="far fa-clock"></i> ' +date.minutes + ' Minutes ago ';
    }else{
        return '<i class="far fa-clock"></i> ' +date.seconds + ' Seconds ago ';
    }
}



var packageTable = $('#myPackageHistory').DataTable({
  "language": {
    "emptyTable": "Package history not available!"
},
"processing": false,
"order": [[ 3, 'desc' ]],
"responsive": true,
"processing": true,
"destroy": true,
"autoWidth": false,
"columnDefs": [
{
    "targets": [ 3 ],
    "visible": false,
    "searchable": false
},
{ "targets" : '_all'},
{ "width": "22%", "targets": 0 ,"className" : 'text-center',},
{ "width": "0%", "targets": 1 ,"className" : 'text-left',},
{ "width": "15%", "targets": 2 ,"className" : 'text-center',},


],
"ajax" : {
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?packageHis='+getUid,
    "dataSrc" : ''
},
"autoWidth": false,
"columns" : [ 
{
    "data" : "packageCreatorName",
    render:function(data,type,row,meta){

        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.packageCreatorName+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.packageCreatorName+'</h6></label></div>';


        }
    }
},
{
    "data" : "Name",
    render:function(data,type,row,meta)
    {

        var datenew =  row.odatecreated.replace("pm", " pm");
        var datenew =  row.odatecreated.replace("am", " am");
        var dateFormat = moment.utc(datenew).local().format('YYYY-MM-DD HH:mm:ss');

        var  orderCreateed = "<h5><b>"+row.odatecreated+"</b></h5>";
        var Name="",name="",serviceName="";


        if(row.Name != "" && row.Name != null)
        {
            name = "<span><b>Package Name : </b>"+row.Name+" </span><br />";
        } 

        if(row.Noofvisit != "" && row.Noofvisit != null)
        {
            serviceName = "<span><b>Service Remaining : </b> "+row.Noofvisit+" </span>";
        }

        return  "<div>" + "<h5><b>" +dateFormat +"</b></h5>"+  name + serviceName +"</div>";



    }
}
, 
{
    "data": "odatecreated",
    "render": function(data,type,row,meta) {

      var datenew = data.replace("pm", " pm");
      var datenew = data.replace("am", " am");
      var dateFormat =moment(datenew).format('YYYY-MM-DD HH:mm:ss');

      var dateDiff = diffYMDHMS(dateFormat);
      var getTimeWidget = getTimeLine(dateDiff);
      return "<div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getTimeWidget+"</div></div>";


  }
},
{
    "data":"orid"
}
]
});





var orderTable = $('#myOrderHistory').DataTable({
  "language": {
    "emptyTable": "Order history not available!"
},
"processing": false,
"order": [[ 4, 'desc' ]],
"responsive": true,
"processing": true,
"destroy": true,
"autoWidth": false,
"columnDefs": [
{
    "targets": [ 4 ],
    "visible": false,
    "searchable": false
},
{ "targets" : '_all'},
{ "width": "22%", "targets": 0 ,"className" : 'text-center',},
{ "width": "0%", "targets": 1 ,"className" : 'text-left',},
{ "width": "15%", "targets": 2 ,"className" : 'text-center',},
{ "width": "15%", "targets": 3 ,"className" : 'text-center',},


],
"ajax" : {
  "url" : "<?php echo EXEC; ?>Exec_Edit_Order.php?viewdata&Customer="+atob(getUid),
  "dataSrc" : ''
},
"autoWidth": false,
"columns" : [ 
{
    "data" : "username",
    render:function(data,type,row,meta){
          var encodedId = btoa(row.ServiceProvider);
        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.username+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" class="" height="50px" width="50px"> <br /><label><h6>'+row.username+'</h6></label></div>';


        }
    }
},
{
    "data" : "ProductTitle",
    render:function(data,type,row,meta)
    {

       var dateFormat = moment.utc(row.datelastupdated).local().format('YYYY-MM-DD h:mm a');
       var  orderCreateed = "<h5><b>"+row.datelastupdated+"</b></h5>";

       var html = "<span><b>Invoice : </b> "+row.ino+"</span><br/>";
       html += "<span><b>Payment Type: </b> "+row.PaymentType+"</span><br/>";



       return  "<div>" + "<h5><b>" +dateFormat +"</b></h5>"+ html +"</div>";



   }
}
, 
{
    "data" : "SubTotal",
    render:function(data,type,row,meta)
    {
        var total = 0;
        if(row.gServicePrice){
            gservice = row.gServicePrice.replace("$","").trim();
            total+=parseFloat(gservice);
        }if(row.TotalseriveAmount){
            service = row.TotalseriveAmount.replace("$","").trim();
            total+=parseFloat(service);
        }if(row.TotalProductAmount){
            product = row.TotalProductAmount.replace("$","").trim();
            total+=parseFloat(product);
        }if(row.TotalMembershipAmount){
            membership = row.TotalMembershipAmount.replace("$","").trim();
            total+=parseFloat(membership);
        }

        return "$"+parseFloat(total).toFixed(2);
    }
}
, 
{
    "data": "orderid",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.orderid);    

      var dateDiff = diffYMDHMS(row.datelastupdated);

      var getTimeWidget = getTimeLine(dateDiff);

      var EditOrder = '';
      <?php if(isset($_SESSION['usertype'])!='Admin'){ ?>
          EditOrder ="<a class='btn btn-info btn-xs' id='editorder' title='Edit Order' href='EditOrder.php?action=edit&id="+encodedId+"&cid="+getUid+"'><span class='fa fa-edit'></span></a>";
      <?php } ?>

      return EditOrder+'<a href="" id="viewButton" style="color:white;" title="View Invoice" class="viewButton  btn btn-success btn-xs cutbut" data-id="' + encodedId + '"><span class="fa fa-eye"></span></a>'+  "<br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getTimeWidget+"</div></div>";


  }
},
{
    "data":"orderid"
}
]
});





$(document).on('click','.communicationTableRefresh',function(){
    communicationTable.ajax.reload();
});

var communicationTable = $('#myCommunicationHistory').DataTable({
  "language": {
    "emptyTable": "Communication not available!"
},
"initComplete": function( settings, json) {
    $("#myCommunicationHistory_length").before('<button class="btn btn-sm btn-info dataTables_filter communicationTableRefresh">Refresh</button>');
},
"order": [[ 3, "desc" ]],
"processing": false,
"responsive": true,
"processing": true,
"destroy": true,
"autoWidth": false,
"columnDefs": [
{ "targets" : '_all'},
{ "width": "30%", "targets": 0 ,"className" : 'text-center',},
{ "width": "10%", "targets": 1 ,"className" : 'text-center',},
{ "width": "30%", "targets": 2 ,"className" : 'text-center',},
{ "width": "30%", "targets": 3 ,"className" : 'text-center',},

],
"ajax" : {
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?communicationHis='+getUid,
    "dataSrc" : ''
},
"autoWidth": false,
"columns" : [ 
{
    "data" : "communicatorName",
    render:function(data,type,row,meta){
         var encodedId = btoa(row.Createid);
        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.communicatorName+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" class="" height="50px" width="50px"> <br /><label><h6>'+row.communicatorName+'</h6></label></div>';
            

        }
    }
},
{
    "data" : "type",
    render:function(data,type,row,meta)
    {   
        var tag;
        if(row.type == "sms")
        {
            tag = "<span class='label label-warning m-r-10 view_Message_button' data-id='"+row.id+"' >SMS</span>";
        }
        else
        {
            tag = "<span class='label label-success m-r-10 view_Message_button' data-id='"+row.id+"'>EMAIL</span>";
        }
        return  tag;
    }
},
{
    "data" : "type",
    render:function(data,type,row,meta)
    {
        var subDetail;

        if(row.subject != "" && row.subject != null )
        {
            subDetail = row.subject;
        }
        else
        {
            subDetail = "No-Subject";
        }

        return  '<span class="view_Message_button viewMailModel" data-id='+row.id+'> ' + subDetail + '</span>';

    }
}
, 
{
    "data": "comtime",
    "render": function(data,type,row,meta) {

      var dateFormat = moment.utc(row.comtime).local().format('MM-DD-YYYY h:mm a');



      return "<span>"+dateFormat+"</span>";



  }
}
]
});




$(document).on("click",".view_Message_button",function(){

    var view_Message  = $(this).attr("data-id");
    jQuery.ajax({
        dataType:"json",
        type:"post",
        data:{data:view_Message},
        url:'?view_Message=view_Message',
        success: function(data)
        {
            $(".fullmsg").html(data.resonse.message);
            if(data.resonse.subject== null)
            {
                $(".fullmsgsub").text('Message');
            }
            else if(data.resonse.subject!='')
            {
                $(".fullmsgsub").html(data.resonse.subject);
            }
            $(".FromUser").text('From: '+data.resonse.username);

            if(data.resonse.userimg){
                $(".FromUserImg").attr('src','<?= base_url?>/assets/userimage/'+data.resonse.userimg);
            }else{
                $(".FromUserImg").attr('src','<?= base_url?>/assets/images/noimage.png');
            }

            $('#view_Message').modal('toggle');

            $(".fullmsg").find(".footer").removeClass("footer");
        }
    })
});



$(document).on('click','#myAppointmentNoteTableRefresh',function(){
    noteTable.ajax.reload();    
});


var noteTable = $('#myAppointmentNote').DataTable({
  "language": {
    "emptyTable": "Note history not available!"
},
 "initComplete": function( settings, json) {
        $("#myAppointmentNote_length").before('<button class="btn btn-sm btn-info dataTables_filter" id="myAppointmentNoteTableRefresh">Refresh</button>');
    },
"order": [[ 3, 'desc' ]],
"processing": true,
"destroy": true,
"columnDefs": [
{
    "targets": [ 3 ],
    "visible": false,
    "searchable": false
},
{ "targets" : '_all'},
{ "width": "30%", "targets": 0 ,"className" : 'text-center',},
{ "width": "50%", "targets": 1 ,"className" : 'text-left',},
{ "width": "20%", "targets": 2 ,"className" : 'text-center',},


],
"ajax" : {
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?noteHis='+getUid,
    "dataSrc" : ''
},
"columns" : [ 
{
    "data" : "noteCreaterName",
    render:function(data,type,row,meta){

        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.noteCreaterName+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.noteCreaterName+'</h6></label></div>';
            

        }
    }
},
{
    "data" : "noteTitle",
    render:function(data,type,row,meta)
    {

     var dateFormat =  moment.utc(row.datecreated).local().format('YYYY-MM-DD HH:mm:ss');
     var encodedId = window.btoa(row.noteId);
     var ret= '<div class="activeuserdetial" ><span><h5><b>'+row.noteTitle+'</b></h5></span><span class="more_text" >';
     var noteDetail = row.noteDetail;
     var showcharacter = 160;
     var ellipsestext = ".....";

     var subdetail = noteDetail.substr(0, showcharacter);

     var remainingDetail = noteDetail.substr(showcharacter,noteDetail.length - showcharacter);
     if(noteDetail.length>showcharacter)
     {
        subdetail = subdetail+'<span class="moreellipses">' + ellipsestext+ '</span>';

    }
    ret+= ''+subdetail+'';
    ret +="</span><br/><div><span> <h6 style='display: inline-block;'>Note created date : </h6> </span>'"+dateFormat+"'</div><div></div>";

    return ret;

}
}
, 
{
    "data": "noteId",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.noteId);

      var noteDateDiff = diffYMDHMS(row.datecreated);

      var getNoteTimeWidget = getTimeLine(noteDateDiff);

      return "<div class=''><a class='btn btn-info btn-xs EditEventClientNoteEdit' style='color: white' id='EditEventClientNoteEdit' title='Edit Note' data-id="+encodedId+"><span class='fa fa-edit'></span></a><a class='btn btn-danger btn-xs ' id='EditEventClientNoteDel' title='Delete Note' data-did="+encodedId+" style='color: white'><span class='fa fa-trash'></span></a><a id='viewNote' style='color:white;'' title='View Note' class='btn btn-success btn-xs viewNote' data-id='" + encodedId + "''><span class='fa fa-eye'></span></a></div><br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getNoteTimeWidget+"</div></div>";



  }
},
{
    "data":"noteId"
}
]
});




var myAppoitmentHistory;
myAppoitmentHistory();

$( "#myAppoitmentHistory_status_filter" ).on( 'change', function () {

    if ( myAppoitmentHistory.column(2).search() !== $(this).val() ) {
        myAppoitmentHistory
        .column(2)
        .search( $(this).val() )
        .draw();
    }
} );

$(document).on('click','#myAppoitmentHistoryTableRefresh',function(){
    myAppoitmentHistory.ajax.reload();    
});



function myAppoitmentHistory(){

    customerid = atob(getUid);
  var EventFilter = JSON.stringify({search:'',date:'',user:[],customer:[customerid],status:[]});

  myAppoitmentHistory = $('#myAppoitmentHistory').DataTable({
      "language": {
        "emptyTable": "Appointment history not available!"
    },
    "initComplete": function( settings, json) {
        $("#myAppoitmentHistory_length").before('<button class="btn btn-sm btn-info dataTables_filter" id="myAppoitmentHistoryTableRefresh">Refresh</button>');
    },
    "processing": false,
    "order": [[ 4, 'desc' ]],
    "responsive": true,
    "processing": true,
    "destroy": true,
    "autoWidth": false,
    "columnDefs": [
    {
        "targets": [ 4 ],
        "visible": false,
        "searchable": false
    },
    { "targets" : '_all'},
    { "width": "20%", "targets": 0,"className" : 'text-center', },
    { "width": "40%", "targets": 1,"className" : 'text-left', },
    { "width": "20%", "targets": 2,"className" : 'text-center', },
    { "width": "20%", "targets": 3,"className" : 'text-center', },


    ],
    "ajax" : {
       url: "<?php echo EXEC; ?>ExecAllEvent?EventFilter="+EventFilter,

       "dataSrc" : ''
   },
   "autoWidth": false,
   "columns" : [ 
   {
    "data": {firstname : "firstname", lastname : "lastname",userimg : "userimg",username:'username'}, 
    "render": function(data, type, row) {
      var encodedId = btoa(row.ServiceProvider);
      if(data.userimg){
        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style=" padding: 0 5px;">'+data.User_firstname+' '+data.User_lastname+' <br>('+data.username+')</span></div></div>';
    }
    else
    {
        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style=" padding: 0 5px;">'+data.User_firstname+' '+data.User_lastname+' <br>('+data.username+')</span></div></div>'
    }
}
},
{
    "data" : "title",
    render:function(data,type,row,meta)
    {
      var datelastupdated = moment(row.datelastupdated).format("YYYY-MM-DD hh:mma"); 

      OrderID = '';
      if(row.OrderID!=null && row.eventstatus==='completed'){
          OrderID = '<span> <h6 style="display: inline-block;">Invoice : '+row.InvoiceNumber+'</h6> </span>';
      }

      return  '<div class="activeuserdetial"><h5><b>'+row.title+'</b></h5><span> <h6 style="display: inline-block;">Event ID : </h6> </span>'+row.id+' <br /> <span> <span> <h6 style="display: inline-block;">Service Date : </h6> </span>'+row.EventDate+' <br /> <span> <h6 style="display: inline-block;">Service Status : '+row.eventstatus+'</h6> </span><br /> <span> <h6 style="display: inline-block;">Booked Date : '+datelastupdated+'</h6></span> '+OrderID+'</div>';
  }
}
,
{
    "data" : "eventstatus",
    render:function(data,type,row,meta)
    {
     return  '<div class="activeuserdetial"><span> <h6 style="display: inline-block;">'+row.eventstatus+'<h6> </span></div>';
 }
}
, 
{
    "data": "id",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.id);
      var encodedOrderId = window.btoa(row.OrderID);

      var eventDateDiff = diffYMDHMS(row.datelastupdated);

      var getEventTimeWidget = getTimeLine(eventDateDiff);

      var action= "<div class=''><a class='btn btn-info btn-xs EditEvent' style='color: white' id='EditEvent' title='Edit Appointment' data-id="+encodedId+"><span class='fa fa-edit'><span></a> <a class='btn btn-danger btn-xs ' style='color: white' id='DeleteEvent' title='Delete Appointment' href='<?= base_url?>/Exec/Exec_Edit_Event?DeleteEvent&dlink="+encodedId+"' ><span class='fa fa-trash'><span></a>";

      if(row.OrderID!=null && row.eventstatus==='completed')
      {
        action+=' <a href="" id="viewButton" style="color:white;" title="View Invoice" class="viewButton  btn btn-success btn-xs cutbut" data-id="' + encodedOrderId + '"><span class="fa fa-eye"></span></a>';
    }

    action+="</div><br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getEventTimeWidget+"</div></div>";
    return action;

}
},
{
    "data" : "id"
}
]
});

}







$(document).on('click','#deleteAppointment',function(e){
  e.preventDefault();
  $(".Loader").show();

  var dlink = $(this).attr('data-id');

  swal({
     title: "Temporary Delete?",
     text: "Once deleted, it will move to Archive list!",
     icon: "warning",
     buttons: true,
 }).then((willDelete)=>{   
    if (willDelete){
       $.ajax({
        dataType:"json",
        type:"post",
        data:{'dlink':dlink},
        url:'<?php echo EXEC; ?>/Exec_Edit_Event?DeleteEvent',
        success: function(data)
        {
            if(data.response){
              $(".Loader").hide();
              swal(data.response);
             $("#myAppoitmentHistoryTableRefresh").trigger('click');


          }
          else if(data.error){

           $(".Loader").hide();
           swal('Something is wrong please try agine')


       }
   }
});
   }
   else{
      $(".Loader").hide();
      return false ;
  }
});
});



$(document).on('click','.viewNote',function(e){

  e.preventDefault();

  var mynoteid = $(this).attr('data-id');

  $.ajax({
    dataType:"json",
    type:"post",    
    data:{'mynoteid':mynoteid},
    "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteid="+mynoteid,
    success: function(data)
    {   
        if(data.resonse)
        {
            $('#noteTitle_view').text(data.resonse.noteTitle);
            $('#noteDetails_view').html(data.resonse.noteDetail);
            $('#viewNoteModal').modal('show');
        }
        else if(data.error)
        {

          swal("Sorry something wrong please try agine")
      }
  }
});
});






$( "#autocomplete" ).focus(function() {
  $("input").attr("autocomplete","nope");
});

$(document).on('click','#deletecam',function(e){
    e.preventDefault();
    console.log($(e.target).attr('class'));
    if($(e.target).attr('id') =="viewcammap")
    {
        return false;
    }


    var dcamid = $(this).attr("data-id");
    var camcid='<?php echo base64_decode($_GET['id']); ?>';

    swal({
        title: "Are you sure?",
        text: "you are really want to delete this Campaigns!",
        icon: "warning",
        buttons: true,
    }
    ).then((willDelete)=>{
        if (willDelete)
        {
            $(this).parent("span").remove();
            $(".Loader").show();
            $.ajax({
                dataType:"json",
                type:"post",
                data: {'dcamid':dcamid,'camcid':camcid},
                url:'?action=dcamid',
                success: function(data)
                {
                    if(data)
                    {

                        swal(data.resonse);
                        $(".Loader").hide();
                        window.location.reload();

                    }
                    else if(data.error)
                    {

                    }
                }
            })

        }
        else
        {

           return false ;
       }
   });

});

$(document).on('click','#deleteTag',function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    swal({
        title: "Are you sure?",
        text: "you are really want to delete this tag!",
        icon: "warning",
        buttons: true,
    }
    ).then((willDelete)=>{
        if (willDelete){
            window.location.href = link;
        }
        else{
         return false ;
     }
 }
 );
}
);


}
);
</script>
<!-- end delete tag popup -->

<script>
    $("#Newtag").validate({
        rules: {
            "tag[]": {
                required: true,}
                ,
            }
            ,
            messages: {
                "tag[]": {
                    required: "Please Enter Tag Name "}
                    ,
                }
                ,
                submitHandler: function() {
                    $(".Loader").show();
                    var data = $("#Newtag").serialize();
                    data= data + "&Action=old_tag";
                    jQuery.ajax({
                        dataType: 'JSON',
                        type:"post",
                        data:data,
                        url:'<?php echo EXEC; ?>Exec_Edit_Tag',
                        success: function(data)
                        {
                            if(data.resonse)
                            {
                                $("#resonse").show().fadeOut(2000);
                                $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                $( '#Newtag' ).each(function(){
                                    this.reset();
                                }
                                );
                                $(".Loader").hide();
                                $('#addTagModel').hide();
                                window.location.reload();
                            }
                            else if(data.error)
                            {
                                $("#error").show().fadeOut(2000);
                                $('#errormsg').html('<span>'+data.error+'</span>');
                                $('#addTagModel').hide();
                                $(".Loader").hide();
                            }
                        }
                    }
                    );
                }
            }
            );
        </script>

<script>
    $(document).ready(function() {
        // $('#noteDetail').wysihtml5();

        // $('#EmailInstruction').wysihtml5();
        // $('#smsMessage').ckeditor();

        $("#sendMail").click(function(){
            var value="<?= $email ?>,<?= $cid ?>";
            $("#To").val(value).trigger("change");
        });

    });
</script>




<script type="text/javascript">
    function myFunction() {
        document.getElementById("myDropdown_cal").classList.toggle("show_cal");
    }
    function myFunction2() {
        document.getElementById("myDropdown_cal2").classList.toggle("show_cal");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn_cal')) {
            var dropdowns = document.getElementsByClassName("dropdown-content_cal");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    $(".fileNameclass").hide();
    $("#drop-file-new").click(function(){
        $('#addDocumentsModal').modal('toggle');
    });





</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','#deleteDocument',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "you are really want to delete this Document!",
                icon: "warning",
                buttons: true,
            }
            ).then((willDelete)=>{
                if (willDelete){
                    window.location.href = link;
                }
                else{
                 return false ;
             }
         }
         );
        }
        );
    }
    );
</script>
<script type='text/javascript'src='<?php echo base_url ?>/assets/js/timepicki.js'></script>
<script>


    $('#datepairExample .date').datepicker({
       format: "mm-dd-yyyy",
       'autoclose': true,
       startDate: '-0d',
   });

    $('#datepairExample .filter').datepicker({
        format: "mm-dd-yyyy",
        'autoclose': true,
        endDate: '-0d',
    });


</script>
<script src="
<?php echo base_url ; ?>/assets/js/tokenize2.js"></script>
<script>
    $('.tokenize-sample-demo1').tokenize2();
    $('.tokenize-remote-demo1, .tokenize-remote-modal').tokenize2({
        dataSource: 'remote.php'
    }
    );
    $('.tokenize-limit-demo1').tokenize2({
        tokensMaxItems: 5
    }
    );
    $('.tokenize-limit-demo2').tokenize2({
        tokensMaxItems: 1
    }
    );
    $('.tokenize-ph-demo1').tokenize2({
        placeholder: 'Please add new tokens'
    }
    );
    $('.tokenize-sortable-demo1').tokenize2({
        sortable: true
    }
    );
    $('.tokenize-custom-demo1').tokenize2({
        tokensAllowCustom: true
    }
    );
    $('.tokenize-callable-demo1').tokenize2({
        dataSource: function(search, object){
            $.ajax('remote.php', {
                data: {
                    search: search, start: 1 }
                    ,
                    dataType: 'json',
                    success: function(data){
                        var $items = [];
                        $.each(data, function(k, v){
                            $items.push(v);
                        }
                        );
                        object.trigger('tokenize:dropdown:fill', [$items]);
                    }
                }
                );
        }
    }
    );
    $('.tokenize-override-demo1').tokenize2();
    $.extend($('.tokenize-override-demo1').tokenize2(), {
        dropdownItemFormat: function(v){
            return $('<a />').html(v.text + ' override').attr({
                'data-value': v.value,
                'data-text': v.text
            }
            )
        }
    }
    );
    $('#btnClear').on('mousedown touchstart', function(e){
        e.preventDefault();
        $('.tokenize-demo1, .tokenize-demo2, .tokenize-demo3').tokenize2().trigger('tokenize:clear');
    }
    );
</script>
<!-- For Send mail -->
<script type="text/javascript">
    $(document).ready(function(){

        $("#NewMail").validate(

        {

            ignore: ":hidden:not(textarea)",
            rules: {
                From: {
                    required: true,}
                    ,
                    "To[]": {
                        required: true,}
                        ,
                        Subject: {
                            required: true,}
                            ,
                            Message: {
                                required: true,}
                                ,
                            }
                            ,
                            messages: {
                                From: {
                                    required: "Please enter  your email id"}
                                    ,
                                    "To[]": {
                                        required: "Please select at least one recipient."}
                                        ,
                                        Subject: {
                                            required: "Please enter email subject"}
                                            ,
                                            Message: {
                                                required: "Please enter email message"}
                                                ,
                                            }
                                            ,
                                            errorPlacement: function( label, element ) {
                                                if( element.attr( "name" ) === "Message" || element.attr( "name" ) === "To[]" ) {
                                                    element.parent().append( label );
                                                }
                                                else {
                                                    label.insertAfter( element );
                                                }
                                            }
                                            ,
                                            submitHandler: function() {
                                                $(".Loader").show();
                                                var data = $("#NewMail").serialize();
                                                data= data ;
                                                jQuery.ajax({
                                                    dataType:"json",
                                                    type:"post",
                                                    data:data,
                                                    url:'<?php echo EXEC; ?>Exec_AllMail',
                                                    success: function(data)
                                                    {
                                                        if(data.resonse_mail)
                                                        {
                                                            $("#resonse_mail").show().fadeOut(2000);
                                                            $('#resonsemsg_mail').html('<span>'+data.resonse_mail+'</span>');
                                                            $( '#NewMail' ).each(function(){
                                                                this.reset();
                                                            }
                                                            );
                                                            $('#sendMailModal').modal('toggle');
                                                            $(".Loader").hide();
                                                            setTimeout(function () {$(".communicationTableRefresh").trigger("click");}, 1000)

                                                        }
                                                        else if(data.error_mail)
                                                        {
                                                            $("#error_mail").show().fadeOut(2000);
                                                            $('#errormsg_mail').html('<span>'+data.error_mail+'</span>');
                                                            $(".Loader").hide();
                                                        }
                                                    }
                                                }
                                                );
                                            }
                                        }
                                        );
$('#Templeate').on('change',function(){
    $(".Loader").show();
    tid=$(this).val();
    $.ajax({
        dataType:"json",
        type:"post",
        data: {
            'tid':tid}
            ,
            url:'?action=editfile',
            success: function(data)
            {
                if(data)
                {
                    $(".Loader").hide();
                    $('#Subject').val(data.resonse.Subject);
                    $('iframe').contents().find('.wysihtml5-editor').html(data.resonse.TextMassage);
                }
                else if(data.error)
                {
                    alert('ok');
                }
            }
        }
        )
}
);
}
);
</script>


<script>


    $(document).ready(function() {
        $("#drop-area").on('dragenter', function (e){
            e.preventDefault();
            $(this).css('background', '#BBD5B8');
        }
        );
        $("#drop-area").on('dragover', function (e){
            e.preventDefault();
        }
        );
        $("#drop-area").on('drop', function (e){
            $('#addDocumentsModal').modal('toggle');
            $(this).css('background', '#D8F9D3');
            e.preventDefault();
            var image = e.originalEvent.dataTransfer.files;
            console.log(image);
            createFormData(image);
        });


    }
    );
    function createFormData(image) {
        $("#Submit2").click(function(event){
            event.preventDefault();
            var formImage = new FormData();
            var clinetid = '<?php echo $ClientId; ?>';
            formImage.append('clinetid',clinetid);
            formImage.append('id',clinetid);
            formImage.append('document', image);
            var fileName = $('#fileName').val();
            formImage.append('fileName',fileName);

        }
        );
    }
    function uploadFormData(formData) {
     var filename = $("#fileName").val();

     if(filename=='')
     {
        $("#filenmaerror").text("Please enter file name")
    }
    else{
        $(".Loader").show();
        $.ajax({
            url: "<?php echo EXEC; ?>Exec_client_attech",
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType:false,
            cache: false,
            processData: false,
            success: function(data){

                $('#addDocumentsModal').modal('toggle');
                $("#resonse_document").show().fadeOut(2000);
                $('#resonsemsg_document').html('<span>'+data.resonse2+'</span>');
                $(".Loader").hide();
            }
        });
    }
}
$("#byuserd").click(function(){
    $("#caluserlistd").toggle()
}
);
$("#byserviced").click(function(){
    $("#calserlistd").toggle()
}
);



</script>
<script type="text/javascript">


 $(document).on('submit',"#add_campaigns",function(e)
 {
    e.preventDefault()
    var tukujaman = $('#listofCampaigns').val()
    if(tukujaman=='')
    {

        $('#listofCampaignserror').text('Please Select Campaigns');
    }
    else
    {
        $('#listofCampaignserror').text('');
        $(".Loader").show();
        var frmData=$(this).serialize();
        $.ajax({
           data: frmData,
           type: "post",
           dataType:'json',
           url: "?Campaigns",
           success: function(data)
           {
               $(".Loader").hide();
               if(data.resonse)
               {
                $("#resonse").show().fadeOut(2000);
                $('#resonsemsg').html('<span>'+data.resonse+'</span>');                
                $('#addTagModel').hide();                
                setTimeout(function () { window.location.href = "CampaignsScriptclient.php?camex=attime"; }, 2000);
            }
            else if(data.error)
            {
                $("#error").show().fadeOut(2000);
                $('#errormsg').html('<span>'+data.error+'</span>');
                $('#addTagModel').hide();                
                
            }
        },error:function(data){
            $(".Loader").hide();
        }
    });
    }
});


</script>
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
      } else {
          panel.style.display = "block";
      }
  });
  }
</script>





</body>
</html>