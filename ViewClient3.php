<?php 

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
            $package_ed= date_format($package_ed,"M d,Y");;
            // $Tags = $GetClient['Tags'];
        }
    }
}


    //list of the service when user is editing the appointment
$sid = $_SESSION['UserID'];

// $stmt2= $db->prepare("SELECT Service.* FROM `Service` WHERE Service.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id ) or FIND_IN_SET($sid,Service.Users)"); 

$stmt2= $db->prepare("SELECT sv.* from `Service` AS sv 
    JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) 
    WHERE sv.createdfk IN (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id or u1.sid=u2.id) 
    and (u2.id=u3.adminid or u2.id=u3.id or u2.id=u3.sid) where u1.id=:id GROUP by u3.id) GROUP BY sv.id"); 



$stmt2->bindParam(':id', $sid, PDO::PARAM_INT);
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
$client_id=$_SESSION['UserID'];
$RelatedTo = $db->prepare("SELECT * FROM `clients` WHERE createdfk=:id");
$RelatedTo->bindValue(":id",$client_id,PDO::PARAM_INT);
$RelatedTo->execute();
$all_client=$RelatedTo->fetchAll(PDO::FETCH_ASSOC);
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$schcreateprmistion=$result['SchedulesCreate'];
    $ClientsLimit=$result['ClientsLimit'];
    $sid=$result['sid'];
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
    $id=$_SESSION['UserID'];
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
$createdid=$_SESSION['UserID'];
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
//       $eidtUserName->bindValue(":userid",$_SESSION['UserID'],PDO::PARAM_STR);
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
  $id=$_SESSION['UserID'];
  $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id IN ($UserName,$id)");
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
    $eidtClientcom = $db->prepare("SELECT * FROM `FullCom` WHERE id=:id");
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
  $cratedid=$_SESSION['UserID'];
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
    #orderdata{
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
    }
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
    span#serivetoaltprice{
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
    }
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
                    div#myModal_new2 .modal-body{
                        overflow: scroll;
                        height: 600px;
                    }





                    /*.displayOperation{ display: none ; }*/
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

                        /*.cDocImg{
                            object-fit: contain;
                            height: 80px;
                            width: 80px;
                            }*/
                            .docName{
                                overflow: hidden;
                                white-space: nowrap;
                                text-overflow: ellipsis;
                                width: 100%;
                                margin: 0px;
                            }
                            /*view client docuemnts tab -> delete and download doument image design end*/



                            /*Appointment tab timepicker css new */

        /*.timepicker_wrap{
                display: flex !important;
                top: 10px;
                left: 0px;
                }*/

        /*#eventstardate .prev,.next{
            margin: auto;
            border-radius: 5px
        }
        */  


/*
        #eventstardate .prev,.next{
            all:unset; 
        }
        */


        /*End Appointment tab timepicker css new end*/




/*    @media (max-width: 768px){
        .myfilelist{width: 50% !important;}
        }*/

        .btn-info.focus, .btn-info:focus, .btn-info:not(:disabled):not(.disabled).active:focus, .btn-info:not(:disabled):not(.disabled):active:focus, .show>.btn-info.dropdown-toggle:focus{box-shadow: unset!important;}

        /*Pgination css start */

        #pagination-container{
          float: right;
          margin-bottom: 10px;
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
                                   <!-- <a href="AddClient.php?action=edit&id=<?php echo base64_encode($MyCLient); ?>" class="link" title="Edit customer's detail">
                                                <i class="fa fa-edit">
                                                </i>
                                            </a> -->
                                            <button type="button" class="btn btn-info m-r-10 editcustomer"  id="" value="<?php echo base64_encode($MyCLient); ?>" data-cuid='<?php echo base64_encode($MyCLient); ?>' data-toggle="modal" data-target="#myModal_addclient" ><i class="fa fa-edit"></i></button>
                                        </h4>
                                        <!-- <h6 class="card-subtitle">
                                            <?php
                                            $LoginQuery = $db->prepare("SELECT Name FROM MemberPackage WHERE id=:SelectPackage");
                                            $LoginQuery->bindParam(':SelectPackage', $SelectPackage, PDO::PARAM_INT);
                                            $LoginQuery->execute();
                                            $result = $LoginQuery->fetch(PDO::FETCH_ASSOC);
                                            @$days=$result['Name'];
                                            echo $days ; ?> 
                                            <a href="<?php echo base_url.'/Order?vcid='.$_GET['id']; ?>" class="link card-subtitle" Title="Click for Renew"> 
                                                <font class="font-medium">
                                                    <?php echo @$days == 'NoPackage' ? '': '(Exp.- '.@$package_ed.' ) ' ?></font>
                                                </a>
                                            </h6> -->
                                        </center>
                                    </div>
                                    <div>
                                        <hr class="mb-1">
                                    </div>

<!--                                 <div class="card-body">
                                    <h4 class="card-title m-t-10">
                                        Tags 
                                    </h4>
                                   <?php 
    foreach ($newtag_array as $key => $value)
    {  
    if(!empty($value))
    {
    ?>
                                    <span class="btn btn-secondary">
                                        <span><?php echo $value ;?> </span>&nbsp
                                        <a class="close pull-right" id='deleteTag' title='Delete' href="AddTag.php?action=delete&id=<?php echo  base64_encode($key) ;?>&cid=<?php echo $_GET['id'] ;?> " > ×</a>
                                    </span>
                                    <?php  
    }     
}
                                    ?>
                                    <button id="addTag" name="addTag" style="margin: 5px 0;" class="btn btn-info" data-toggle="modal" data-target="#addTagModel"><i class="fa fa-plus">
                                        </i> <?php echo $B4; ?></button>
                                   
                                    <div class="modal fade" id="addTagModel"
role="dialog"> <div class="modal-dialog modal-sm vertical-align-center"> <div
class="modal-content"> <div class="modal-header"> <h4 class="modal-title"> Add
New Tag </h4> <button type="button" class="close"
data-dismiss="modal">&times;</button> </div> <div class="modal-body"> <div
class="Loader"> </div> <form class="form-horizontal" autocomplete="off"
id="Newtag" method="post"> <input type="hidden" name="allreadytag"
id="allreadytag" value="<?php echo @$tag; ?>"> <input type="hidden" name="id"
id="id" value="new"> <input type="hidden" name="mynewcid" id="mynewcid"
value="<?php echo @$MyCLient; ?>"> <input type="hidden" name="UserID"
id="UserID" value="<?php echo $_SESSION['UserID']; ?>"> <div
class="form-group"> <label><span class="help">Tag Name  *</span></label>
<input type="text" name="tag" id="tag" value="" class="form-control"> </div>
<div class="form-group"> <label><span class="help">Tag Name  *</span></label>
<select class="tokenize-custom-demo1 form-control" data-placeholder="Select
Existing or Enter new Tag " id="tag[]"  name="tag[]" multiple> <?php echo
select_options() ?> </select> </div> <div class="form-group"> <button
type="submit" class="btn waves-effect waves-light btn-info m-r-10"
id="SubmitTag"><i class="fa fa-check"></i> <?php echo $B5; ?></button> <button
type="button" class="btn waves-effect waves-light btn-danger"
data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B6; ?></button>
</div> </form> </div> <div class="modal-footer"> <p style="color: red"> *
Please press enter after writing tag name. </p> </div> </div> </div> </div>
                                   
</div> -->
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
        border-radius: 4px;
        color: #74788d;
        margin: 4px 0px;
        border-width: 0px;
    }
    #side_menu .nav-link:hover{
        background: #f2f3f7;
        color: #00acff;
        font-weight: 600;
    }
    #side_menu .active{
        font-weight: 600;
        background: #f2f3f7;
        border-width: 0px;
        color: #00acff;
    }
    #side_menu .nav-tabs{
     border-width: 0px; 
 }
</style>
<div class="card-body" id="side_menu">
    <div class="nav nav-tabs customtab" role="tablist">

        <a class="nav-link active" data-toggle="tab" href="#NotesTab" role="tab" aria-selected="true"><i class="fas fa-sticky-note"></i>Note</a>


        <a class="nav-link" data-toggle="tab" href="#ServiceTab" role="tab" aria-selected="false"><i class="fas fa-calendar-check"></i>Appointment History</a>


        <a class="nav-link" data-toggle="tab" href="#DocumentsTab" role="tab" aria-selected="false"><i class="fas fa-folder-open"></i>Documents</a>

                                    <!--
                                        <a class="nav-link" data-toggle="tab" href="#MarketingTab" role="tab" aria-selected="false">Marketing</a>
                                    -->

                                    <a class="nav-link" data-toggle="tab" href="#ContactTab" role="tab" aria-selected="false"><i class="far fa-id-card"></i>Contact Client</a>
                                    
                                    <?php 
                                    if(@$_SESSION['usertype']=="subscriber" or @$_SESSION['usertype']=="user" or @$_SESSION['usertype']=="employee")
                                    {
                                        ?>

                                        <a class="nav-link" data-toggle="tab" href="#OrderTab" role="tab" aria-selected="false"><i class="fas fa-history"></i>Order History</a>




                                        <a class="nav-link" data-toggle="tab" href="#PackageTab" role="tab" aria-selected="false"><i class="fas fa-box-open"></i>Package</a>

                                    <?php } ?>
                                </div>

                            </div>

                            <!-- ///////////////////////////////////////////////////////////////////////////////////// -->
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
                                    <!-- <small class="text-muted p-t-30 db">Social Profile</small>
<br>
<button class="btn btn-circle btn-secondary"><i class="fa fa-facebook"></i></button>
<button class="btn btn-circle btn-secondary"><i class="fa fa-twitter"></i></button>
<button class="btn btn-circle btn-secondary"><i class="fa fa-youtube"></i></button>  -->
</div>
<div>
    <hr>
</div>
<!-- <div class="card-body">
    <h4 class="card-title m-t-10">
        Contact Information 
    </h4>
    <small class="text-muted">Email address </small>
    <h6>
        <?php echo $email ; ?> 
    </h6>
    <small class="text-muted p-t-30 db contactPhone">Phone</small>
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
                                    <!-- <small class="text-muted p-t-30 db">Social Profile</small>
<br>
<button class="btn btn-circle btn-secondary"><i class="fa fa-facebook"></i></button>
<button class="btn btn-circle btn-secondary"><i class="fa fa-twitter"></i></button>
<button class="btn btn-circle btn-secondary"><i class="fa fa-youtube"></i></button> 
</div>
<div>
    <hr>
</div> -->
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
        // will leave the foreach loop and also the if statement
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
       <!--  <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#NotesTab" role="tab" aria-selected="true">Note</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#ServiceTab" role="tab" aria-selected="false">Appointment History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#DocumentsTab" role="tab" aria-selected="false">Documents</a>
            </li>
                                     <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#MarketingTab" role="tab" aria-selected="false">Marketing</a>
                                    </li> 
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#ContactTab" role="tab" aria-selected="false">Contact Client</a>
                                    </li>
                                    <?php 
                                    if(@$_SESSION['usertype']=="subscriber" or @$_SESSION['usertype']=="user" or @$_SESSION['usertype']=="employee")
                                    {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#OrderTab" role="tab" aria-selected="false">Order History</a>
                                        </li>


                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#PackageTab" role="tab" aria-selected="false">Package</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </ul>
                        </ul> -->
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
                                        <button class="btn btn-info pull-right" id="addNote" data-toggle="modal" data-target="#addNoteModal"><i class="fa fa-plus"></i> <?php echo $B7; ?></button>
                                        <div class="clerfix"></div>
                                        <!-- Add Note Modal -->
                                        <div id="addNoteModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            Add New Note
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal" id="NewNote" autocomplete="off" method="post">
                                                            <input type="hidden" class="editnoteid" name="id" id="id" value="new">
                                                            <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                            <div class="form-group">
                                                                <label><span class="help"> Note Title  *</span></label>
                                                                <input type="text" name="noteTitle" id="noteTitle" value="" class="form-control" maxlength="30">
                                                            </div>
                                                            <div class="form-group">
                                                                <label><span class="help"> Note Detail  *</span></label>
                                                                <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Note Detail ..." name="noteDetail" id="noteDetail" >  </textarea>
                                                            </div>
                                                            <div class="form-group">
 <!--  <label><span class="help"> Related To  *</span></label>
<select class="select2 m-b-10 select2-multiple  form-control" data-placeholder="Select Related Client" id="noteRelated" name="noteRelated[]" multiple data-style="form-control btn-secondary">
<?php 
foreach($all_client as $row)
{
?>     
<option value="
<?php echo $row['id']; ?>">
<?php echo $row['FirstName']." ".$row['LastName']; ?></option>
<?php 
}
?>
</select> -->
<input type="hidden" name="noteRelated[]" id="noteRelated" value="<?php echo base64_decode($_GET['id']); ?>" class="form-control">
</div>
<div class="Loader">
</div>
<div class="form-group">
    <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="addNote"> <i class="fa fa-check">
    </i> Submit Note</button>
</div>
</form>
</div>
<div class="modal-footer">
    <div class="col-lg-12 col-md-12">
        <div class="alert alert-success" id="resonse_note" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-success">
                <i class="fa fa-check-circle">
                </i>
                Success
            </h3>
            <p id="resonsemsg_note">
            </p>
        </div>
        <div class="alert alert-danger" id="error_note" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-danger">
                <i class="fa fa-exclamation-circle">
                </i>
                Errors
            </h3>
            <p id="errormsg_note">
            </p>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- / Add Note Modal -->
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
<!-- <button class="accordion"> Appointment </button> -->
<div class="tab-pane panel" id="ServiceTab" role="tabpanel">
    <div class="card-header">
        <i class="fas fa-calendar-check"></i>
        <span class="sub_heading1">
            Appointment History
        </span>
        <span class="sub_heading2">know your appointment history</span>
    </div>
    <div class="card-body" style="padding: 0px;">
        <div id="addServiceDiv">
            <!-- <button class="btn btn-info pull-right" id="addnewappointment" data-toggle="modal" data-target="#myModal_exit2"><i class="fa fa-plus"></i> <?php echo $B9; ?></button> -->
            <!-- Add Service Modal -->


            <div class="modal fade" id="myModal_exit2"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" style="max-width: 1100px;">
                <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Book Appointment with</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                      <input type="hidden" name="id" class="id" id="id" value="new">
                      <input type="hidden" name="Location_radio_value" id="evnet_Location_radio" value="Customer Location">
                      <input type="hidden" name="UserID" id="UserID"  value="<?php echo $_SESSION['UserID']; ?>">

                      <input type="hidden" name="title" id="title" class="form-control" value="" placeholder="Appointment Title" autocomplete="nope" maxlength="20">
                      <input type="hidden" name="cid" id="cid" value="">
                      <input type="hidden" name="FirstName" id="FirstName" class="form-control" value="" placeholder="First Name" autocomplete="nope" maxlength="10">
                      <input type="hidden" name="LastName" id="LastName" class="form-control" value="" placeholder="Last Name" autocomplete="nope" maxlength="10">
                      <input type="hidden" name="Phone" id="Phone" class="form-control" value="" autocomplete="nope" placeholder="(123) 456-7890">
                      <input type="hidden" id="Email" name="Email" class="form-control" value="" autocomplete="nope" placeholder="example@gmail.com" maxlength="30">

                      <input type="hidden" name="Address" id="Address" class="form-control" value="" autocomplete="nope" placeholder="Enter your Address">
                      <input type="hidden" name="Zip" id="Zip" class="form-control" autocomplete="nope" placeholder="Zip" value="" maxlength="10">
                      <input type="hidden" name="country" id="newcountry" class="form-control" autocomplete="nope" placeholder="country" value="">
                      <input type="hidden" name="State" id="State"  autocomplete="nope" class="form-control" placeholder="State" value="">
                      <input type="hidden" name="City" id="City" autocomplete="nope" class="form-control" placeholder="City" value="">
                      <input type="hidden" name="ServiceName" class="form-control" placeholder="ServiceName" id="ServiceName" autocomplete="nope" value="">
                      <input type="hidden" name="ServiceProvider" class="form-control" placeholder="ServiceProvider" id="ServiceProvider" autocomplete="nope" value="">
                      <input type="hidden" name="editServiceProvider" class="form-control" placeholder="editServiceProvider" id="editServiceProvider" autocomplete="nope" value="">
                      <input type="hidden" name="eventstatus" value="confirmed">
                      <input type="hidden" name="wdayshidden" id="wdayshidden" value="">
                      <input type="hidden" name="wdateshidden" id="wdateshidden" value="">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-4 col-sm-12">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="listofcatagory" id="servicewith">Service *</label>
                                  <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service" name="newlistofcatagory" id="newlistofcatagory">
                                      <option value disabled="true" selected="selected">Select Service</option>
                                      <?php 
                                      foreach($result_event2 as $row2)
                                      {
                                          ?>
                                          <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                                      <?php } ?>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group serviceproviderblock">
                              <label for="listofcatagory3" class="listcat_service">Service Provider *</label>

                              <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service Provider" id="listofcatagory3" name="listofcatagory3">

                                <option value="">Select Provider</option>
                            </select>
                            <span style="color: red" id="serviceprovider_error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="example-email">Appointment Date/Time  *<span class="help"></span></label>

                  <p id="datepairExample" style="display: flex">
                    <input type="text" class="date start form-control ml-2" placeholder="Start Date" name="sd" autocomplete="nope"  id="eventstardate" />
                    <!-- <input type="text" placeholder="Start Time" class= "eventTime start form-control" name="st" autocomplete="nope" id="eventstartime" /> -->
<!-- 
                    <input id='eventstartime' type='text' placeholder="Start Time" name='st' class="form-control eventTime eventChange start"
                    autocomplete="nope"
                    /> -->
                    <input id='eventstartime' type='text' placeholder="Click here to select Start Time" name='st' class="form-control eventTime eventChange start"  autocomplete="nope" readonly />

                    <input type="hidden" class="eventTime end form-control" placeholder="End Time" name="et" autocomplete="nope" id="eventendtime"  />
                    <input type="hidden" class="date start form-control" placeholder="End Date" name="ed" autocomplete="nope"  id="eventenddate" />
                </p>
            </div>
            <div class="row" id="time_slot"></div>

            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Cost of Service: * </label> 
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                  </div>
                  <input type="text" name="CostOfService" id="CostOfService" class="form-control" autocomplete="nope" placeholder="" value="<?php if(!empty($result_event['CostOfService'])) { echo $result_event['CostOfService'];}else{ echo @$CostOfService;} ?>" >
              </div>
          </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label>Duration: </label> 
          <div class="input-group mb-3">
            <input type="text" id="duration" readonly="true" class="form-control">
            <div class="input-group-prepend">
              <span class="input-group-text minhour"></span>
          </div>
      </div>
  </div>
</div>
</div>
                <!-- <div class="form-group">
                  <label>Appointment Status  * </label>
                  <select name="eventstatus" id="eventstatus" class="form-control" >
                    <option value=""> Select Appointment current Status </option>
                    <option value="pending"> Pending </option>
                    <option value="confirmed"> Confirmed </option>
                    <option value="canceled"> Canceled </option>
                    <option value="no-show"> No-Show </option>
                    <option value="in-progress"> In Progress </option>
                    <option value="completed"> Completed </option>
                  </select>
              </div> -->
              <label>Location : *</label>
              <div class="row">
                  <div class="col-sm-6 col-md-6 pull-right">
                    <input type="radio" id="Location_radio" name="Location_radio" value="Salon Location" class="locone"> Salon Location
                </div>
                <div class="col-sm-6 col-md-6 pull-right">
                    <input type="radio" id="Location_radio" name="Location_radio" checked="true" value="Customer Location" class="locone2"> Customer Location
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
              <label>Appointment Note  * </label>
              <textarea class="textarea_editor form-control" rows="4" placeholder="Enter note here ..." id="EmailInstruction" autocomplete="nope" name="EmailInstruction"><?php if(!empty($result_event['EmailInstruction'])) { echo $result_event['EmailInstruction'];}else{ echo @$EmailInstruction;} ?></textarea>
          </div>
          <div class="Loader"></div>

          <hr>
          <span id="editspan" style="display: none"></span>
          <div id="repeatdiv">
              <label>Repeat :</label>
              <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist" >
                <li class="nav-item">
                  <a class="repeat nav-link active show" id="offrepeat" data-toggle="pill" href="#pills-off" role="tab" aria-controls="pills-home" aria-selected="true">Off</a>
              </li>
              <li class="nav-item">
                  <a class="repeat nav-link" id="daily" data-toggle="pill" href="#pills-daily" role="tab" aria-controls="pills-profile" aria-selected="false">Daily</a>
              </li>
              <li class="nav-item">
                  <a class="repeat nav-link" id="weekly" data-toggle="pill" href="#pills-weekly" role="tab" aria-controls="pills-contact" aria-selected="false">Weekly</a>
              </li>
              <li class="nav-item">
                  <a class="repeat nav-link" id="monthly" data-toggle="pill" href="#pills-monthly" role="tab" aria-controls="pills-contact" aria-selected="false">Monthly</a>
              </li>
              <li class="nav-item">
                  <a class="repeat nav-link" id="yearly" data-toggle="pill" href="#pills-yearly" role="tab" aria-controls="pills-contact" aria-selected="false">Yearly</a>
              </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-off" role="tabpanel" aria-labelledby="pills-home-tab"></div>
            <div class="tab-pane fade" id="pills-daily" role="tabpanel" aria-labelledby="pills-profile-tab">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="dendate">End Date *</label>
                    <input type="text" class="datepicker form-control" placeholder="End Date" name="dendate" autocomplete="nope"  id="dendate" />
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-weekly" role="tabpanel" aria-labelledby="pills-contact-tab">
      <div class="row">
        <div class="col-md-6">
          <div class="form-gorup">
            <label for="wevery">Every: *</label>
            <input type="number" class="form-control" id="every" value="1" min="1" max="100">
        </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="dendate">End Date: *</label>
        <input type="text" class="datepicker form-control" placeholder="End Date" name="wendate" autocomplete="nope"  id="wendate" />
    </div>
</div>
</div>
<div class="form-group">
  <label for="">Days: *</label>
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="border: 1px solid #dee0e4;border-radius: 5px;">
    <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-right-radius:0px;border-bottom-right-radius: 0px;display: block;">Sun</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Mon</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Tue</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Wed</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Thu</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Fri</a>
  </li>
  <li class="nav-item text-center" style="width:14.28%">
      <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;display: block;">Sat</a>
  </li>
</ul>
</div>
</div>

<div class="tab-pane fade" id="pills-monthly" role="tabpanel" aria-labelledby="pills-home-tab">
  <div class="row">
    <div class="col-md-6">
      <div class="form-gorup">
        <label for="mday">Day: *</label> 
        <input type="number" class="form-control" name="mday" id="mday" value="1" min="1" max="31">
    </div>
</div>
<div class="col-md-6">
  <div class="form-group">
    <label for="mendate">End Date: *</label>
    <input type="text" class="datepicker form-control" placeholder="End Date" name="mendate" autocomplete="nope"  id="mendate" />
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-home-tab">
  <div class="row">
    <div class="col-md-3">
      <div class="form-gorup">
        <label for="ymonth">Month:*</label>
        <select name="ymonth" class="form-control" id="ymonth" style="width:100%;padding-left:2px" tabindex="0">
            <option value="1">Jan</option>
            <option value="2">Feb</option>
            <option value="3">Mar</option>
            <option value="4">Apr</option>
            <option value="5">May</option>
            <option value="6">Jun</option>
            <option value="7">Jul</option>
            <option value="8">Aug</option>
            <option value="9">Sep</option>
            <option value="10">Oct</option>
            <option value="11">Nov</option>
            <option value="12">Dec</option>
        </select>
    </div>
</div>
<!--  !-->
<div class="col-md-3">
  <div class="form-gorup">
    <label for="ydate">Day : *</label>
    <input type="number" class="form-control" value="1" min="1" max="31" maxlength="2" name="ydate" id="ydate">
</div>
</div>
<div class="col-md-6">
  <div class="form-group">
    <label for="yendate">End Date: *</label>
    <input type="text" class="datepicker form-control" placeholder="End Date" name="yendate" autocomplete="nope"  id="yendate" />
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-md-4 col-sm-12">
    <div class="form-group">
      <label for="customer">Customer : </label> <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right editcustomer" value="" id="editcustomer"><b>Edit</b></button>

      <?php
      $db5 = new db();
      $id=$_SESSION['UserID'];
      $total_user = $db5->prepare("SELECT sid FROM `clients` WHERE `createdfk`=:id");
      $total_user->bindParam(':id', $id, PDO::PARAM_INT);
      $total_user->execute();
      $all=$total_user->fetch(PDO::FETCH_ASSOC);
      $mysid=$all['sid'];

      if($mysid!=0)
      {
         $db5 = new db();
         $id=$_SESSION['UserID'];
         $total_user2 = $db5->prepare("SELECT * FROM `clients` WHERE `sid`=:mysid");
         $total_user2->bindParam(':mysid', $mysid, PDO::PARAM_INT);
         $total_user2->execute();
         $number_of_users = $total_user2->rowCount();
     }


     if(@$clientcreatex != 0){
      if($ClientsLimit=='full')
      {
       ?>
       <!-- <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_addclient" ><b><?php echo $B132; ?></b></button> -->

       <?php
   }
   else
   {
     if(@$number_of_users >= @$ClientsLimit)                                            
     {
       ?>
       <!-- <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_addclient" ><b><?php echo $B132; ?></b></button> -->

       <?php
   }
   else
   {
       ?>
       <!-- <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_addclient" ><b><?php echo $B132; ?></b></button>                                        -->

       <?php
   }
}
}
?> 

<select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Customer" name="newlistofclient" id="newlistofclient">
</select>
</div>
<div class="form-group">
  <label for="emaillabel" id="newemail" style="text-decoration: underline;"></label>
</div>
<div class="row" id="clinetdetails">
  <div class="col-md-4 col-lg-4 col-sm-12 col-xs-4">
    <img src="" alt="No Image" id="clientimage" height="100px" width="100px"> 
</div>

<div class="col-lg-8 col-sm-12 col-xs-8">
    <br>
    <label id="newname"></label><br>
    <label id="newphone"></label>
</div>
</div>
</div>
</div>
<div class="row">
  <div class="col-lg-12 col-md-12">
    <div class="alert alert-success" id="resonseAddApp" style="display: none;">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonseAddAppemsg"></p>
  </div>
  <div class="alert alert-danger" id="csrf_error2" style="display: none;">
      <button type="button" data-dismiss="alert" class="close"> <span aria-hidden="true">&times;</span> </button>
      <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg2"></p>
  </div>
</div>
</div>
</div>

<div class="modal-footer">
    <div class="form-group pull-right" style="margin-bottom: 0px;">
      <button type="submit" class="btn waves-effect waves-light btn-info m-r-10"><i class="fa fa-check"></i> <?php echo $B15; ?></button>
      <button type="button" class="btn waves-effect waves-light btn-danger" id="cancelappp" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B16; ?></button>  
  </div>
</div>
</form>
</div>
</div>
</div>

<!-- / Add Service Modal -->


<!-- / Add Service Modal --> 



</div>
<br>
<div class="profiletimeline1">
    <div class="table-responsive m-t-40 col-md-12">
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
<div class="modal fade" id="myModal_app" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    View Invoice
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="col-lg-4 pull-left">
                        <div>
                           <span><img src="<?php echo base_url;?>/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span>
                       </div>
                       <input type="hidden" name="Orderids" value="" id="Orderids_app">
                       <?php $cuanme = ucfirst($_SESSION['UserName']); ?>
                       <div>
                        <span id="Currentuser_app"><?php echo $cuanme; ?></span>
                    </div>
                    <div id="to_app">
                        <span><h5>
                            To:
                        </h5></span>
                    </div>
                    <div>
                        <span id="CustomerName_app"></span>
                    </div>
                    <div>
                        <span id="CustomerMail_app"></span>
                    </div>
                    <div>
                        <span id="CustomerAdders_app"></span>
                    </div>
                    <div>
                        <span id="CustomerAdders2_app"></span>
                    </div>
                    <div>
                        <span id="Customerphone_app"></span>
                    </div>
                </div>
                <div class="col-lg-3 pull-right">
                    <div class="orderprimery_app">
                        <div>
                            <span>Order Date</span>
                            : 
                            <span id="orderdata_app"></span>
                        </div>
                        <div>
                            <span>Invoice Number</span>
                            : 
                            <span id="orderinvoicenumber_app"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clerfix" style="clear: both;">
            </div>
            <div class="col-lg-12">
                <table id="carttable" border="1">
                    <tr id="order_popup" class="order_popup">
                        <td>
                            Item
                        </td>
                        <td>
                            Qty
                        </td>
                        <td>
                            Price
                        </td>
                        <td>
                            Discount
                        </td>
                        <td>
                            % 
                        </td>
                        <td>
                            Total Price
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clerfix" style="clear: both;">
            </div>
            <div class="col-lg-12">
                <div class="col-lg-3 pull-right">
                    <div>
                        <span>Service </span>
                        : 
                        <span id="serivetoaltprice_app"></span>
                    </div>
                    <div>
                        <span>Giftcard </span>
                        : 
                        <span id="giftcardtotal_app"></span>
                    </div>
                    <div>
                        <span>Product </span>
                        : 
                        <span id="producttotalprice_app"></span>
                    </div>
                    <div>
                        <span>Membership </span>
                        : 
                        <span id="membershiptotalprice_app"></span>
                    </div>
                    <div>
                        <span>Sales Tax </span>
                        : 
                        <span id="salestax_app"></span>
                    </div>
                    <div>
                        <span>Tips</span>
                        : 
                        <span id="tips_app"></span>
                    </div>
                    <div>
                        <span>Points </span>
                        : 
                        <span id="userpoint_app"></span>
                    </div>
                    <hr>
                    <div>
                        <span style="font-size: 20px;" >Sub Total :</span> 
                        <span id="toatalprice_app"></span>
                    </div>
                    <hr>
                    <div class="Signature_app">
                        <span>Signature </span>
                    </div>
                </div>
                <div class="col-lg-6 pull-left">
                    <div class="notes_app" id="notes_app">
                        <h5>
                            Other Notes
                        </h5>
                    </div>
                    <div class="notelist_app">
                        <ol type="I">
                            <li>
                                This is auto computer printed invoice.
                            </li>
                            <li>
                                If you have any problem with this invoice please contact with admin.
                            </li>
                            <li>
                                The goods sold will not be returned.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="clerfix" style="clear: both;">
            </div>
            <div class="col-lg-12">
                <hr>
                <div class="last_app">
                    <h4>
                        Thank you MySunless.
                    </h4>
                </div>
            </div>
            <div id="divToPrint_app" style="display:none;">
                <div>
                    <?php echo '<div class="col-lg-12" style="width:100%">
                    <div class="col-lg-4 pull-left" style="width:40%; float: left; padding-bottom:25px;">      
                    <div><span><img src="'.base_url.'/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span></div>
                    <div><span id="Currentuser">'.$cuanme.'</span></div>
                    <div id="to" style="width:50%; padding-top: 25px;"><span><h5 style="background: #4095c7;color: white; padding: 5px ;">To:</h5></span></div>  
                    <div><span class="CustomerName1"></span></div>
                    <div><span class="CustomerMail1"></span></div>
                    <div><span class="CustomerAdders1"></span></div>
                    <div><span class="CustomerAdders21"></span></div>
                    <div><span class="Customerphone1"></span></div>
                    </div>
                    <div class="col-lg-3 pull-right" style="width:40%; float: right;">      
                    <div class="orderprimery">
                    <div><span>Order Date</span> : <span class="orderdata1"></span></div>
                    <div><span>Invoice Number</span> : <span class="orderinvoicenumber1"></span></div>
                    </div>  
                    </div>  
                    </div>             <div class="clerfix" style="clear: both;"></div>
                    <div class="col-lg-12" style="width:100%;">
                    <table id="carttable" border="1">
                    <tr id="order_popup" class="order_popup">
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:25%; font-size: 15px;">Item</td>
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:8%; font-size: 15px;">Qty</td>
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Price</td>
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Discount</td>
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:9%; font-size: 15px;"> % </td>
                    <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Total Price</td>
                    </tr>
                    </table>
                    </div>
                    <div class="clerfix" style="clear: both;"></div>
                    <div class="col-lg-12" style="width:100%;">
                    <div class="col-lg-3 pull-right" style="width:40%; float: right; padding-top:25px;">
                    <div><span>Service </span> : <span style="padding: 0 48px;" class="serivetoaltprice1"></span></div>
                    <div><span>Giftcard </span> : <span style="padding: 0 43px;" class="giftcardtotal1"></span></div>
                    <div><span>Product </span> : <span style=" padding: 0 47px;" class="producttotalprice1"></span></div>
                    <div><span>Membership </span> : <span style="padding: 0 15.4px;" class="membershiptotalprice1"></span></div>
                    <div><span>Sales Tax </span> : <span style="padding: 0 35px;" class="salestax1"></span></div> 
                    <div><span>Tips</span> : <span style="padding: 0 68px;" class="tips1"></span></div> 
                    <div><span>Points </span> : <span style="padding-left: 47px;" class="userpoint1"></span></div> <hr>
                    <div><span>Sub Total </span> : <span  style=" padding: 0 8px;font-weight: bold!important;color: #0b59a2!important;font-size: 20px;" class="toatalprice1"></span></div>
                    <hr>
                    <div style="padding:50px;" class="Signature"><span>Signature </span></div>
                    </div>
                    <div class="col-lg-6 pull-left" style="width:40%;float: left;">
                    <div class="notes" id="notes">
                    <h5 style="background: #4095c7;color: white; padding: 5px ;">Other Notes</h5>
                    </div>
                    <div class="notelist">
                    <ol type="I">
                    <li>This is auto computer printed invoice.</li>
                    <li>If you have any problem with this invoice please contact with admin.</li>
                    <li>The goods sold will not be returned.</li>
                    </ol>
                    </div>
                    </div>
                    </div>
                    <div class="clerfix" style="clear: both;"></div>
                    <div class="col-lg-12">
                    <hr>
                    <div class="last" style="text-align: center;"><h4>Thank you MySunless.</h4></div>
                    </div>'; ?>      
                </div>
            </div>
            <div>
            </div>
                                                                                <!--  <div id="getallservice"></div>
<div id="getallproduct"></div>
<div id="getallmembership"></div> -->
</div>
<div class="modal-footer">
 <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                                <button class="btn btn-primary hidden-print" onclick="PrintDiv();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button><!-- 
                                                                                <button type="submit" id="sendinvoice" class="btn btn-primary hidden-print" onclick=""><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Send</button> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
<!-- <button class="accordion"> Documents </button> -->
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
            <!--  <button class="btn btn-info pull-right" id="addDocuments" data-toggle="modal" data-target="#addDocumentsModal"><i class="fa fa-plus"></i> Add Documents</button>  -->
            <div class="dropdown_cal">
                <button onclick="myFunction2()" class="dropbtn_cal btn-default dropdown-toggle">Filter</button>
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

                                                                            <!--     <div class="col-lg-12 col-md-12">
<div class="card">
<div class="card-body">
<input type="file" id="document input-file-now-custom-1" class="dropify" name="document" data-default-file=""/>
</div>
</div>
</div>          
-->
<div class="Loader"></div>
<!-- <div class="fileNameclass"> -->
    <div class="form-group">
        <label><span class="help"> File Name *</span></label>
        <input type="text" name="fileName" class="form-control" id="fileName" class="fileName" value="" placeholder="File Name">
        <span class="filenmaerror" id="filenmaerror" style="color: red;"></span>
    </div>
    <!-- </div> -->
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
       <!--  <div class="table-responsive m-t-40 col-md-12">
            <table id="myFileUploadHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
              <thead>
                <tr>
                  <th>File Type</th>
                  <th>Files</th>
                  <th>Date Created</th>
                </tr>
              </thead>
            </table>
        </div> --> 
<!-- <?php
        foreach ($result_file as $row) 
        {
            ?>
            <div class="activedatea parent_of_operation myfilelist">
                <div class="activeuserdetial activeuserdetialdocument">

                    <?php 
                    $ext = pathinfo($row['document'], PATHINFO_EXTENSION);
                    if($ext=='xlsx')
                    {
                        ?>
                        <img src="<?php echo base_url; ?>/assets/images/xsl.png" alt="user" class="" height="50px" width="50px">
                        <?php
                    }
                    else if($ext=='pdf')
                    {
                        ?>
                        <img src="<?php echo base_url; ?>/assets/images/pdf.png" alt="user" class="" height="50px" width="50px">
                        <?php
                    }
                    else if($ext=='docx')
                        {?>
                         <img src="<?php echo base_url; ?>/assets/images/msdoc.png" alt="user" class="" height="50px" width="50px">
                         <?php
                     }

                     else if($ext=='doc')
                        {?>
                         <img src="<?php echo base_url; ?>/assets/images/msdoc.png" alt="user" class="" height="50px" width="50px">
                         <?php
                     } 
                     else if($ext=='txt') 
                        {?>
                            <img src="<?php echo base_url; ?>/assets/images/txt.png" alt="user" class="" height="50px" width="50px">
                            <?php
                        }
                        else if($ext=='csv')
                        {
                            ?>
                            <img src="<?php echo base_url; ?>/assets/images/export-csv.png" alt="user" class="" height="50px" width="50px">
                            <?php
                        }
                        else
                        {
                            ?>
                            <img src="<?php echo base_url; ?>/assets/ClientDocs/<?php echo @$row['document']; ?>" alt="user" class="" height="50px" width="50px">
                            <?php
                        }
                        ?>
                        <h5>
                            <b>
                                <a href="<?php echo $base_url.'/assets/ClientDocs/'. @$row['document']; ?>" target="_blank"><?php echo $row['fileName'] ;?></a>
                            </b>
                        </h5>
                    </div>
                </div>
                <?php  }  ?> -->
            </div>
            <div class="clerfix" style="clear: both;">
            </div>
                                                        <!-- <div id="drop-area">
                                                            
                                                        </div> -->
                                                        <!-- <input type="file" class="dropify" data-height="200" id="fileInput" /> -->
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
                                        <!-- <button class="accordion"> Contact </button> -->
                                        <div class="tab-pane panel" id="ContactTab" role="tabpanel">
                                            <div class="card-header">
                                                <i class="far fa-id-card"></i>
                                                <span class="sub_heading1">
                                                    Contact Client
                                                </span>
                                                <span class="sub_heading2">save your client's contact</span>
                                            </div>
                                            <div class="card-body">


                                             <!--start contact info design rahul-->

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




                                    <button class="btn btn-info pull-left m-r-10" id="sendMail" data-toggle="modal" data-target="#composeModal"><i class="fa fa-send">
                                    </i> SEND MAIL</button>
                                    <button class="btn btn-info pull-left m-r-10" id="sendSms" data-toggle="modal" data-target="#sendSmsModal"><i class="fa fa-envelope">
                                    </i> SEND SMS</button>
                                    <?php include('EmailSendModule.php') ?>
                                    <!-- send mail Modal -->
                                    <div id="sendMailModal" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content   -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">
                                                        Send Mail
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="Loader"></div>
                                                    <form class="form-horizontal" autocomplete="off" id="NewMail" method="post">

                                                      <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                      <input type="hidden" name="ccid" value="<?php echo $_GET['id']; ?>">
                                                      <input type="hidden" name="type" value="email">
                                                      <div class="form-group">
                                                        <label>From *</label>
                                                        <input type="text" name="From" id="From" class="form-control" placeholder="From" value="<?php echo $From; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>To *</label>
                                                        <input type="hidden" name="To[]" id="To" value="<?php echo $email.",".base64_decode($_GET['id']) ;?>">
                                                        <input type="text" class="form-control" placeholder="To" value="<?php echo $email ;?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Template *</label>
                                                        <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                                                        <select class="Templeate form-control"  id="Templeate" name="Templeate">
                                                            <option value="">Select Email Template</option>
                                                            <?php 
                                                            foreach($all_Templeate as $row)
                                                            {
                                                                ?>
                                                                <option value="<?php echo $row['id']?>"><?php echo $row['Name']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Subject *</label>
                                                        <input type="text" name="Subject" id="Subject" class="form-control" placeholder="Subject" value="" maxlength="30">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Message *</label>
                                                        <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Message ..." id="Message" name="Message"></textarea>
                                                    </div>
                                                    <div class="Loader">
                                                    </div>
                                                    <div class="form-group">
                                                       <button type="submit" name="send" class="btn waves-effect waves-light btn-info m-r-10" id="send"><i class="fa fa-check">
                                                       </i> Send</button>
                                                       <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times">
                                                       </i> Cancel </button>
                                                   </div>
                                               </form>
                                           </div>
                                           <div class="modal-footer">
                                           </div>

                                           <div class="col-lg-12 col-md-12">

                                            <div class="alert alert-success" id="resonse_mail" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                <h3 class="text-success"><i class="fa fa-check-circle"></i>Success</h3>
                                                <p id="resonsemsg_mail">
                                                </p>
                                            </div>

                                            <div class="alert alert-danger" id="error_mail" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i>Errors</h3>
                                                <p id="errormsg_mail">
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / Send mail Modal -->
                            <!-- send SMS Modal -->
                            <div id="sendSmsModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">
                                                Send SMS
                                            </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="Loader"></div>
                                            <form class="form-horizontal" id="NewSms" autocomplete="off" method="post">
                                                <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                <input type="hidden" name="Country" value="<?php echo $Country; ?>">
                                                <input type="hidden" name="ccid" value="<?php echo base64_decode($_GET['id']); ?>">
                                                <input type="hidden" name="type" value="sms">
                                                <input type="hidden" name="FirstName" value="<?php echo $FirstName.' '.$LastName; ?>">
                                                <div class="form-group">
                                                    <label>To *</label>
                                                    <input type="text" name="smsTo" id="smsTo" class="form-control" placeholder="To" value="<?php echo $Phone ;?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message *</label>
                                                    <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Message ..." id="smsMessage" name="smsMessage"></textarea>
                                                </div>
                                                <div class="Loader">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="smsSend" class="btn waves-effect waves-light btn-info m-r-10" id="smsSend"><i class="fa fa-check">
                                                    </i> Send</button>
                                                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times">
                                                    </i> Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="alert alert-success" id="resonse_sms" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                <h3 class="text-success">
                                                    <i class="fa fa-check-circle">
                                                    </i>
                                                    Success
                                                </h3>
                                                <p id="resonsemsg_sms">
                                                </p>
                                            </div>
                                            <div class="alert alert-danger" id="error_sms" style="display: none;">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                <h3 class="text-danger">
                                                    <i class="fa fa-exclamation-circle">
                                                    </i>
                                                    Errors
                                                </h3>
                                                <p id="errormsg_sms">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div style="padding: 25px 0;">

                            </div>



                            <!--New Design For timeline Start rahul-->
                            <div class="table-responsive m-t-40 col-md-12">
                                <table id="myCommunicationHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                  <thead>
                                    <tr>
                                      <th>User Name</th>
                                      <th>Communication Detail</th>
                                      <th>Datetime</th>
                                  </tr>
                              </thead>
                          </table>
                      </div>


                      <!--New Design For timeline End -->


                      <div class="profiletimeline" id="profileTimelineOld">
                        <?php
                        foreach ($result_cominuction as $row) 
                        {
                            ?>
                            <?php
                            $id=$row['cid'];
                            $stmt= $db->prepare("SELECT * FROM `clients` WHERE id=:id"); 
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            $allresult = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                // $username=@$allresult['FirstName'];
                            $createdfkid=@$allresult['createdfk'];
                            $createdfkstmt= $db->prepare("SELECT * FROM `users` WHERE id=:createdfkid"); 
                            $createdfkstmt->bindParam(':createdfkid', $createdfkid, PDO::PARAM_INT);
                            $createdfkstmt->execute();
                            $createdfkresult = $createdfkstmt->fetch(PDO::FETCH_ASSOC);
                            $img = $createdfkresult['userimg'];
                            $username = $createdfkresult['username'];
                            ?>

                            <div class="pull-right usertime">

                                <div class="modal fade" id="view_Message" role="dialog">
                                    <div class="modal-dialog">
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
                                                        <?php   
                                                        if (empty($img)) 
                                                        {
                                                            ?>
                                                            <a href="javascript:void(0)"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user"  height="50px" width="50px" class="img-circle"></a>



                                                            <?php
                                                        } 
                                                        elseif (file_exists(DOCUMENT_ROOT.'/assets/userimage/'.$img)) 
                                                        {
                                                            ?>

                                                            <a href="javascript:void(0)"><img src="<?php echo base_url; ?>/assets/userimage/<?php echo @$img; ?>" alt="user"  height="50px" width="50px" class="img-circle"></a>


                                                            <?php
                                                        }
                                                        else 
                                                        {
                                                            ?>


                                                            <a href="javascript:void(0)"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user"  height="50px" width="50px" class="img-circle"></a>

                                                            <?php
                                                        }
                                                        ?>        



                                                    </div>
                                                    <div class="p-l-10">
                                                        <h4 class="m-b-0"><?php echo @$email ;?></h4>
                                                        <small class="text-muted">From: <?php echo @$createdfkresult['email']; ?></small>
                                                    </div>
                                                </div>
                                                <p class="fullmsg">
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>


                           <!--   </div> -->
                                                                <!-- <div class="clerfix">
                                                                </div>
                                                                <div class="clerfix">
                                                                </div> -->
                                                            <?php  }  ?>
                                                        </div>
                                                        <!-- / Send SMS Modal -->
                                                    </div>
                                                </div>
                                                <!-- Order Histroy Start -->
                                                <!-- <button class="accordion"> Order History </button> -->
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

                                                            <div class="modal fade" id="myModal_order" role="dialog">
                                                                <div class="modal-dialog modal-xl">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">
                                                                                View Invoice
                                                                            </h4>
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="col-lg-12">
                                                                                <div class="col-lg-4 pull-left">
                                                                                    <div>
                                                                                     <span><img src="<?php echo base_url;?>/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span>
                                                                                 </div>
                                                                                 <input type="hidden" name="Orderids" value="" id="Orderids">
                                                                                 <?php $cuanme = ucfirst($_SESSION['UserName']); ?>
                                                                                 <div>
                                                                                    <span id="Currentuser"><?php echo $cuanme; ?></span>
                                                                                </div>
                                                                                <div id="to">
                                                                                    <span><h5>
                                                                                        To:
                                                                                    </h5></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="CustomerName"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="CustomerMail"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="CustomerAdders"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="CustomerAdders2"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span id="Customerphone"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-3 pull-right">
                                                                                <div class="orderprimery">
                                                                                    <div>
                                                                                        <span>Order Date</span>
                                                                                        : 
                                                                                        <span id="orderdata"></span>
                                                                                    </div>
                                                                                    <div>
                                                                                        <span>Invoice Number</span>
                                                                                        : 
                                                                                        <span id="orderinvoicenumber"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clerfix" style="clear: both;">
                                                                        </div>
                                                                        <div class="col-lg-12">
                                                                            <table id="carttable" border="1">
                                                                                <tr id="order_popup" class="order_popup">
                                                                                    <td>
                                                                                        Item
                                                                                    </td>
                                                                                    <td>
                                                                                        Qty
                                                                                    </td>
                                                                                    <td>
                                                                                        Price
                                                                                    </td>
                                                                                    <td>
                                                                                        Discount
                                                                                    </td>
                                                                                    <td>
                                                                                        % 
                                                                                    </td>
                                                                                    <td>
                                                                                        Total Price
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="clerfix" style="clear: both;">
                                                                        </div>
                                                                        <div class="col-lg-12">
                                                                            <div class="col-lg-3 pull-right">
                                                                                <div>
                                                                                    <span>Service </span>
                                                                                    : 
                                                                                    <span id="serivetoaltprice"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Giftcard </span>
                                                                                    : 
                                                                                    <span id="giftcardtotal"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Product </span>
                                                                                    : 
                                                                                    <span id="producttotalprice"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Membership </span>
                                                                                    : 
                                                                                    <span id="membershiptotalprice"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Sales Tax </span>
                                                                                    : 
                                                                                    <span id="salestax"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Tips</span>
                                                                                    : 
                                                                                    <span id="tips"></span>
                                                                                </div>
                                                                                <div>
                                                                                    <span>Points </span>
                                                                                    : 
                                                                                    <span id="userpoint"></span>
                                                                                </div>
                                                                                <hr>
                                                                                <div>
                                                                                    <span style="font-size: 20px;" >Sub Total :</span> 
                                                                                    <span id="toatalprice"></span>
                                                                                </div>
                                                                                <hr>
                                                                                <div class="Signature">
                                                                                    <span>Signature </span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 pull-left">
                                                                                <div class="notes" id="notes">
                                                                                    <h5>
                                                                                        Other Notes
                                                                                    </h5>
                                                                                </div>
                                                                                <div class="notelist">
                                                                                    <ol type="I">
                                                                                        <li>
                                                                                            This is auto computer printed invoice.
                                                                                        </li>
                                                                                        <li>
                                                                                            If you have any problem with this invoice please contact with admin.
                                                                                        </li>
                                                                                        <li>
                                                                                            The goods sold will not be returned.
                                                                                        </li>
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clerfix" style="clear: both;">
                                                                        </div>
                                                                        <div class="col-lg-12">
                                                                            <hr>
                                                                            <div class="last">
                                                                                <h4>
                                                                                    Thank you MySunless.
                                                                                </h4>
                                                                            </div>
                                                                        </div>
                                                                        <div id="divToPrint" style="display:none;">
                                                                            <div>
                                                                                <?php echo '<div class="col-lg-12" style="width:100%">
                                                                                <div class="col-lg-4 pull-left" style="width:40%; float: left; padding-bottom:25px;">      
                                                                                <div><span><img src="'.base_url.'/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span></div>
                                                                                <div><span id="Currentuser">'.$cuanme.'</span></div>
                                                                                <div id="to" style="width:50%; padding-top: 25px;"><span><h5 style="background: #4095c7;color: white; padding: 5px ;">To:</h5></span></div>  
                                                                                <div><span class="CustomerName1"></span></div>
                                                                                <div><span class="CustomerMail1"></span></div>
                                                                                <div><span class="CustomerAdders1"></span></div>
                                                                                <div><span class="CustomerAdders21"></span></div>
                                                                                <div><span class="Customerphone1"></span></div>
                                                                                </div>
                                                                                <div class="col-lg-3 pull-right" style="width:40%; float: right;">      
                                                                                <div class="orderprimery">
                                                                                <div><span>Order Date</span> : <span class="orderdata1"></span></div>
                                                                                <div><span>Invoice Number</span> : <span class="orderinvoicenumber1"></span></div>
                                                                                </div>  
                                                                                </div>  
                                                                                </div>             <div class="clerfix" style="clear: both;"></div>
                                                                                <div class="col-lg-12" style="width:100%;">
                                                                                <table id="carttable" border="1">
                                                                                <tr id="order_popup" class="order_popup">
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:25%; font-size: 15px;">Item</td>
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:8%; font-size: 15px;">Qty</td>
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Price</td>
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Discount</td>
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:9%; font-size: 15px;"> % </td>
                                                                                <td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Total Price</td>
                                                                                </tr>
                                                                                </table>
                                                                                </div>
                                                                                <div class="clerfix" style="clear: both;"></div>
                                                                                <div class="col-lg-12" style="width:100%;">
                                                                                <div class="col-lg-3 pull-right" style="width:40%; float: right; padding-top:25px;">
                                                                                <div><span>Service </span> : <span style="padding: 0 48px;" class="serivetoaltprice1"></span></div>
                                                                                <div><span>Giftcard </span> : <span style="padding: 0 43px;" class="giftcardtotal1"></span></div>
                                                                                <div><span>Product </span> : <span style=" padding: 0 47px;" class="producttotalprice1"></span></div>
                                                                                <div><span>Membership </span> : <span style="padding: 0 15.4px;" class="membershiptotalprice1"></span></div>
                                                                                <div><span>Sales Tax </span> : <span style="padding: 0 35px;" class="salestax1"></span></div> 
                                                                                <div><span>Tips</span> : <span style="padding: 0 68px;" class="tips1"></span></div> 
                                                                                <div><span>Points </span> : <span style="padding-left: 47px;" class="userpoint1"></span></div> <hr>
                                                                                <div><span>Sub Total </span> : <span  style=" padding: 0 8px;font-weight: bold!important;color: #0b59a2!important;font-size: 20px;" class="toatalprice1"></span></div>
                                                                                <hr>
                                                                                <div style="padding:50px;" class="Signature"><span>Signature </span></div>
                                                                                </div>
                                                                                <div class="col-lg-6 pull-left" style="width:40%;float: left;">
                                                                                <div class="notes" id="notes">
                                                                                <h5 style="background: #4095c7;color: white; padding: 5px ;">Other Notes</h5>
                                                                                </div>
                                                                                <div class="notelist">
                                                                                <ol type="I">
                                                                                <li>This is auto computer printed invoice.</li>
                                                                                <li>If you have any problem with this invoice please contact with admin.</li>
                                                                                <li>The goods sold will not be returned.</li>
                                                                                </ol>
                                                                                </div>
                                                                                </div>
                                                                                </div>
                                                                                <div class="clerfix" style="clear: both;"></div>
                                                                                <div class="col-lg-12">
                                                                                <hr>
                                                                                <div class="last" style="text-align: center;"><h4>Thank you MySunless.</h4></div>
                                                                                </div>'; ?>      
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                        </div>
                                                                                <!--  <div id="getallservice"></div>
<div id="getallproduct"></div>
<div id="getallmembership"></div> -->
</div>
<div class="modal-footer">
   <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                                                                <button class="btn btn-primary hidden-print" onclick="PrintDiv();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button><!-- 
                                                                                <button type="submit" id="sendinvoice" class="btn btn-primary hidden-print" onclick=""><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Send</button> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                

                                                                <div class="table-responsive m-t-40 col-md-12">
                                                                    <table id="myOrderHistory" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                                      <thead>
                                                                        <tr>
                                                                          <th>User Name</th>
                                                                          <th>Order Detail</th>
                                                                          <th>Action</th>
                                                                          <th>orid</th>

                                                                      </tr>
                                                                  </thead>
                                                              </table>
                                                          </div> 




                                                                <!-- <div class="activeuserdetial">
<h3 class="h3">Oops !! No Order Found... </h3>
</div> -->
</div>
</div>
</div>
<!-- Order Histroy End -->




<!-- Package Histroy Start -->
<!-- <button class="accordion"> Package </button> -->
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
            <a href="<?php echo base_url.'/Order?vcid='.$_GET['id']; ?>&addpackage" target="_blank" class="btn btn-info pull-right" style="margin: 10px;">Add Package to Customer</a>
            <br>
            <br>
                                                                <!-- <div class="modal fade" id="myModal_order" role="dialog">
                                                                    <div class="modal-dialog modal-xl">
                                                                        
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title">
                                                                                    View Invoice
                                                                                </h4>
                                                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="col-lg-12">
                                                                                    <div class="col-lg-4 pull-left">
                                                                                        <div>
                 <span><img src="<?php echo base_url;?>/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span>
                                                                                        </div>
                                                                        <input type="hidden" name="Orderids" value="" id="Orderids">
                                                                                        <?php $cuanme = ucfirst($_SESSION['UserName']); ?>
                                                                                        <div>
                                                                                            <span id="Currentuser"><?php echo $cuanme; ?></span>
                                                                                        </div>
                                                                                        <div id="to">
                                                                                            <span><h5>
                                                                                                To:
                                                                                                </h5></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span id="CustomerName"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span id="CustomerMail"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span id="CustomerAdders"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span id="CustomerAdders2"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span id="Customerphone"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-3 pull-right">
                                                                                        <div class="orderprimery">
                                                                                            <div>
                                                                                                <span>Order Date</span>
                                                                                                : 
                                                                                                <span id="orderdata"></span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <span>Invoice Number</span>
                                                                                                : 
                                                                                                <span id="orderinvoicenumber"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="clerfix" style="clear: both;">
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <table id="carttable" border="1">
                                                                                        <tr id="order_popup" class="order_popup">
                                                                                            <td>
                                                                                                Item
                                                                                            </td>
                                                                                            <td>
                                                                                                Qty
                                                                                            </td>
                                                                                            <td>
                                                                                                Price
                                                                                            </td>
                                                                                            <td>
                                                                                                Discount
                                                                                            </td>
                                                                                            <td>
                                                                                                % 
                                                                                            </td>
                                                                                            <td>
                                                                                                Total Price
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                                <div class="clerfix" style="clear: both;">
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="col-lg-3 pull-right">
                                                                                        <div>
                                                                                            <span>Service </span>
                                                                                            : 
                                                                                            <span id="serivetoaltprice"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span>Product </span>
                                                                                            : 
                                                                                            <span id="producttotalprice"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span>Membership </span>
                                                                                            : 
                                                                                            <span id="membershiptotalprice"></span>
                                                                                        </div>
                                                                                        <div>
                                                                                            <span>Points </span>
                                                                                            : 
                                                                                            <span id="userpoint"></span>
                                                                                        </div>
                                                                                        <hr>
                                                                                        <div>
                                                                                            <span>Sub Total </span>
                                                                                            : 
                                                                                            <span id="toatalprice"></span>
                                                                                        </div>
                                                                                        <div class="Signature">
                                                                                            <span>Signature </span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6 pull-left">
                                                                                        <div class="notes" id="notes">
                                                                                            <h5>
                                                                                                Other Notes
                                                                                            </h5>
                                                                                        </div>
                                                                                        <div class="notelist">
                                                                                            <ol type="I">
                                                                                                <li>
                                                                                                    This is auto computer printed invoice.
                                                                                                </li>
                                                                                                <li>
                                                                                                    If you have any problem with this invoice please contact with admin.
                                                                                                </li>
                                                                                                <li>
                                                                                                    The goods sold will not be returned.
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="clerfix" style="clear: both;">
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <hr>
                                                                                    <div class="last">
                                                                                        <h4>
                                                                                            Thank you MySunless.
                                                                                        </h4>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="divToPrint" style="display:none;">
                                                                                    <div>
                                                                                        <?php echo '<div class="col-lg-12" style="width:100%">
<div class="col-lg-4 pull-left" style="width:40%; float: left; padding-bottom:25px;">      
<div><span><img src="'.base_url.'/assets/images/smallpart1.png" alt="homepage" class="light-logo"></span></div>
<div><span id="Currentuser">'.$cuanme.'</span></div>
<div id="to" style="width:50%; padding-top: 25px;"><span><h5 style="background: #4095c7;color: white; padding: 5px ;">To:</h5></span></div>  
<div><span class="CustomerName1"></span></div>
<div><span class="CustomerMail1"></span></div>
<div><span class="CustomerAdders1"></span></div>
<div><span class="CustomerAdders21"></span></div>
<div><span class="Customerphone1"></span></div>
</div>
<div class="col-lg-3 pull-right" style="width:40%; float: right;">      
<div class="orderprimery">
<div><span>Order Date</span> : <span class="orderdata1"></span></div>
<div><span>Invoice Number</span> : <span class="orderinvoicenumber1"></span></div>
</div>  
</div>  
</div>             <div class="clerfix" style="clear: both;"></div>
<div class="col-lg-12" style="width:100%;">
<table id="carttable" border="1">
<tr id="order_popup" class="order_popup">
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:25%; font-size: 15px;">Item</td>
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:8%; font-size: 15px;">Qty</td>
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Price</td>
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Discount</td>
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:9%; font-size: 15px;"> % </td>
<td style="background: #4095c7; color: white;  font-weight: 900;  padding: 5px 10px; width:15%; font-size: 15px;">Total Price</td>
</tr>
</table>
</div>
<div class="clerfix" style="clear: both;"></div>
<div class="col-lg-12" style="width:100%;">
<div class="col-lg-3 pull-right" style="width:40%; float: right; padding-top:25px;">
<div><span>Service </span> : <span class="serivetoaltprice1"></span></div>
<div><span>Product </span> : <span class="producttotalprice1"></span></div>
<div><span>Membership </span> : <span class="membershiptotalprice1"></span></div>
<div><span>Points </span> : <span class="userpoint1"></span></div> <hr>
<div><span>Sub Total </span> : <span  class="toatalprice1"></span></div>
<div class="Signature"><span>Signature </span></div>
</div>
<div class="col-lg-6 pull-left" style="width:40%;float: left;">
<div class="notes" id="notes">
<h5 style="background: #4095c7;color: white; padding: 5px ;">Other Notes</h5>
</div>
<div class="notelist">
<ol type="I">
<li>This is auto computer printed invoice.</li>
<li>If you have any problem with this invoice please contact with admin.</li>
<li>The goods sold will not be returned.</li>
</ol>
</div>
</div>
</div>
<div class="clerfix" style="clear: both;"></div>
<div class="col-lg-12">
<hr>
<div class="last" style="text-align: center;"><h4>Thank you MySunless.</h4></div>
</div>'; ?>      
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> -->


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



                                                                <!-- <div class="activeuserdetial">
<h3 class="h3">Oops !! No Order Found... </h3>
</div> -->
</div>
</div>
</div>
<!-- Package Histroy End -->


<!-- Modal -->
<div class="modal fade" id="packaeditordermodel" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Package detalis</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <form class="form-horizontal form-material" autocomplete="off" id="Newmembership2" method="post" novalidate="novalidate2">

             <table>
                      <!-- <tr>
                      <td><label>Recipient's Name : <label></td>
                      <td><span id="reciptname" class="reciptname"></span></td>
                  </tr> -->

                  <tr>
                      <td>
                         <label style="margin-bottom: 25px; "><span class="help">Package </span></label> 
                     </td>
                     <td>
                        <div class="form-group"> 

                           <input type="text" name="packagename" id="packagename" value="">



                           <input type="hidden" name="mprice" id="mprice">
                           <input type="hidden" name="mCommissionAmount" id="mCommissionAmount">
                           <input type="hidden" name="OrderMembershipid" id="OrderMembershipid">

                       </div>     


                   </td>


               </tr>

               <tr>
                  <td>  <label style="margin-bottom: 25px; "><span class="help">Amount</span></label> </td>
                  <td>  
                    <div class="form-group">    
                        <input type="text" class="PckageAmount" name="PckageAmount" value="" id="PckageAmount" data-packageprice="" >
                    </div>
                </td>
            </tr>


            <tr>
              <td>
                  <label style="margin-bottom: 25px; "><span class="help">Remaining Visit </span></label>
              </td>
              <td> 
               <div class="qty">

                <!-- <input type="number" class="count" name="qty" value="1"> -->
                <div class="form-group">    
                    <input type="number" class="count" name="qty" value="1" name="test" min=1 oninput="validity.valid||(value='');">
                    <span class="plus bg-dark">+</span>
                    <span class="minus bg-dark">-</span>
                </div>
            </div>
        </td>
    </tr>

                     <!-- <tr>
                      
                      <td>

                        <label style="margin-bottom: 25px; "><span> Auto Renew : </span></label>
                        
                      </td>
                      <td>  
                        
                        <input type="checkbox" class="Package_Autonew" id="Package_Autonew" name="Package_Autonew" value="yes" >
                      
                        </td>
                    </tr>   -->



               <!--        <tr class="Renewaltr" style="display: none;">
                      <td>
                        
                        <label style="margin-bottom: 25px; "><span> Renewal on : </span></label>
                      </td>
                      <td>  
                        <div class="form-group">     
                        <select class="select2 m-b-10 select2-multiple listofcatagory2_package_renwal" style="width: 100%" data-placeholder="Choose Service" id="listofcatagory2_package_renwal" name="listofcatagory2_package_renwal">
                          <option value="completed">No of visit completed</option>
                          <option value="Weekly">Weekly</option>
                          <option value="Monthly">Monthly</option>
                          <option value="Yearly">Yearly</option>
                        </select>
                          
                          <p class="package_carfor_p" style="display: none;"><input  type="checkbox" class="Pckage_carryford" id="Pckage_carryford" name="Pckage_carryford" value="yes"> Carry Balance Forward </p>

                        </div>
                       </td>
                   </tr>  -->

                   <tr>
                      <td>

                        <label style="margin-bottom: 25px; "><span> Expires on: </span></label>
                    </td>
                    <td> 
                      <div class="form-group">     
                          <input type="text" class="date start" placeholder="Start Date" name="package_expire_date" autocomplete="nope" id="package_expire_date" value="Never" aria-invalid="false">
                      </div>
                  </td>


              </tr> 

          </table>

          <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add_memberships_save"><i class="fa fa-check"></i> Save</button>
          <button type="button" class="btn waves-effect waves-light btn-danger m-r-10" data-dismiss="modal"><i class="fa fa-times"></i> Cancel </button>

      </form>


  </div>
  <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>

</div>
</div>

<!-- Modal -->
<!-- ========================Add zcx client============================== -->
<div class="modal fade" id="myModal_addclient" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:1100" >
    <div class="modal-dialog modal-lg" style="max-width: 1100px;">


      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Customer Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="Loader"></div>
      <div class="modal-body">
          <div class="Loader"></div>
          <form class="form-horizontal" action="" autocomplete="off" method="post" id="NewClient2">
              <input type="hidden" name="id" id="id" class="mycid" value="<?php echo $MyCLient; ?>">
              <input type="hidden" name="clinetid"  value="<?php echo @$ClientId;?>">
              <?php 
              if($usertype=='subscriber')
              {
                  ?>
                  <input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
                  <?php
              }
              else
              {
                  ?>
                  <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
                  <?php
              }
              ?>
              <?php $_SESSION["ClientID"] = @$ClientId ;?>
              <div class="customersdetalisone">

                <?php   

                if(empty($ProfileImg)) 
                    {?>
                        <div class="form-group dfsadfs">
                            <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-default-file="<?php echo base_url; ?>/assets/images/noimage.png"/>
                                </div>
                            </div>
                        </div>
                        <?php
                    } 
                    elseif (file_exists(DOCUMENT_ROOT.$SUB.'/assets/ProfileImages/'.$ProfileImg)) 
                    {
                      ?>
                      <div class="form-group dfsadfs">
                        <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
                        <div class="card ">
                            <div class="card-body">
                                <input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-default-file="<?php echo base_url; ?>/assets/ProfileImages/<?php echo $ProfileImg ?>" value="<?php echo base_url; ?>/assets/ProfileImages/<?php echo $ProfileImg ?>"/>
                                <input type="hidden" name="ProfileImg" value="<?php echo $ProfileImg ?>">
                                <input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
                                <input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
                            </div>
                        </div>
                    </div>
                    <?php
                }
                else 
                {
                    ?>
                    <div class="form-group dfsadfs">
                        <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
                        <div class="card">
                            <div class="card-body">
                                <input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-default-file="<?php echo base_url; ?>/assets/images/noimage.png"/>
                                <input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
                                <input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?> 


                <button type="button" class="btn btn-default" id="addcusomimagebutton" style="width: 45%; margin-bottom: 20px; float: left; margin-right: 10px;"> <?php echo $B1; ?></button>


                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 45%;margin-bottom: 20px; margin-left: 10px;"> <?php echo $B2; ?><span class="glyphicon glyphicon-chevron-down"></span></button>


                <div class="dropdown-menu" style="width: 96%;">
                    <?php
                    $stmta= $db->prepare("SELECT * FROM `listofavtar`"); 
                    $stmta->execute();
                    $stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
                    foreach($stmtall as $row)
                    {
                      ?>
                      <label style="padding: 5px;">
                          <input type="radio" name="ProfileImg" value="<?php echo $row['Name']; ?>" style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer; outline: 2px solid #f00;" >
                          <img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px" style="cursor: pointer;">
                      </label>
                      <?php

                  }
                  ?>
              </div>

          </div>  
          <div class="customersdetalistwo">

            <div class="form-group">
                <label><span class="help"> First Name *</span></label>
                <input type="text" name="FirstName" id="FirstNameC" class="form-control" placeholder="First Name" autocomplete="nope" value="" maxlength="10">
            </div> 

            <div class="form-group">
                <label><span class="help"> Last Name *</span></label>
                <input type="text" name="LastName" id="LastNameC" class="form-control" autocomplete="nope" value="" placeholder="Last Name" maxlength="10">
            </div>

            <div class="form-group">
                <label><span class="help"> Phone Number *</span></label>
                <input type="text" autocomplete="nope"  name="Phone" id="phonenumber"  class="form-control" value="" placeholder="(123) 456-7890">
            </div>

            <div class="form-group">
                <label for="example-email">Email * <span class="help"></span></label>
                <input type="email" id="example-email" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com" maxlength="30">
            </div>

        </div>

        <div class="customersdetalistree">

            <div class="form-group">
                <label for="example-email">Street Address *</label>
                <!-- <input autocomplete="nope" id="autocomplete"  placeholder="Enter your address"  class="form-control" name="Address"  onFocus="geolocate()" type="text" value=""></input>  -->
                <input autocomplete="nope"  placeholder="Enter your address"  class="form-control" id="autocomplete" name="Address"  type="text" value=""></input> 

                <input type="hidden" value="" id="street_number" disabled="true"></input>
                <input type="hidden" value="" id="route" disabled="true"></input>  
            </div>

            <div class="form-group">
              <label for="country">Country *</label>
              <input type="hidden" id="country" name="Country" value="United States">
              <input type="text" disabled="" value="United States" class="form-control">
          </div>

          <div class="form-group">
            <label><span class="help">State *</span></label>
            <select class="form-control" id="administrative_area_level_1" name="State">
              <option value="">Select a State</option>
              <?php
              $newstate = "Alabama,Alaska,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,Florida,Georgia,Hawaii,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana,Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Tennessee,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming";
              $stateList = explode(',', $newstate);
              foreach($stateList as $value){
                  if($value == $result['state'] ){
                     echo "<option selected value='".$value."'>".$value."</option>";
                 }
                 else{
                     echo "<option value='".$value."'>".$value."</option>";
                 }
             }
             ?>
         </select>
     </div>

<!--             <div class="form-group">
                <label for="country">Country *</label>
                <select class="form-control" id="country" autocomplete="nope" name="Country">
                    <option value="">Select a Country</option>
                    <?php
                    foreach($countryList as $value)
                    {
                        if($value['countries_name'] == $Country)
                        {
                          echo "<option selected value='".$value['countries_name']."'>".$value['countries_name']."</option>";
                      }
                      else
                      {
                       echo "<option value='".$value['countries_name']."'>".$value['countries_name']."</option>";
                   }
               }
               ?>
           </select>
       </div>
       <div class="form-group">
          <label><span class="help">State *</span></label>
          <select class="form-control" id="administrative_area_level_1" autocomplete="nope" name="State">
            <option value="">Select a State</option>
            <?php
            foreach($stateList as $value){
                if($value['name'] == $State){
                   echo "<option selected value='".$value['name']."'>".$value['name']."</option>";
               }
               else{
                   echo "<option value='".$value['name']."'>".$value['name']."</option>";
               }
           }
           ?>
       </select>
   </div> -->

   <div class="cutomercityandzip">
    <div class="cumtercity">   
        <div class="form-group">
            <label for="example-email">City *</label>
            <input  id="locality" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
        </div>
    </div>

    <div class="cumterzip">
        <div class="form-group">
            <label for="example-email">Zip Code *</label>
            <input type="text"  id="postal_code" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="0123456"></input>
        </div>
    </div>
</div>


</div>
<div class="clearfix" style="clear: both;"></div>

</div>
<div class="modal-footer">
    <div class="form-group">
        <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-client" id="add-client"><i class="fa fa-check"></i> <?php echo $B3; ?></button>
    </div>

    <div class="form-group">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
</div>
</form>

</div>

</div>
</div>
<!-- ========================Add client============================== -->


<!-- ========================Crop image============================== -->
<div id="uploadimageModal" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Upload & Crop Image</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 text-center">
              <div id="image_demo"></div>
          </div>
          <div class="col-md-12" style="text-align: center;">
              <br />
              <br />
              <br/>
              <button class="btn btn-success crop_image">Crop & Upload Image</button>
              <button type="button" class="btn btn-default" data-dismiss="modal"> Skip </button>
          </div>
      </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<!-- ========================Crop image============================== -->

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
<!-- hover effect to edit/delete operation -->
                        <!-- <script type="text/javascript">
$(document).ready(function () {
$(".parent_of_operation").mouseenter(function () {
$(this).children().children(".displayOperation").css("display", "block");
});
$(".parent_of_operation").mouseleave(function () {
$('.displayOperation').css("display", "none");
});
});
</script> -->
<!-- start delete tag popup -->
<script id="code">
  var myDiagram = null;
  function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates

    myDiagram =
      $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
      {
          initialContentAlignment: go.Spot.Top,
          allowDrop: true,  // must be true to accept drops from the Palette
          "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
          "LinkRelinked": showLinkLabel,
          "undoManager.isEnabled": true  // enable undo & redo
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

    // helper definitions for node templates

    function nodeStyle() {
      return [

      new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
      {
          // the Node.location is at the center of each node
          locationSpot: go.Spot.Center
      }
      ];
  }


  function makePort(name, align, spot, output, input) {
      var horizontal = align.equals(go.Spot.Top) || align.equals(go.Spot.Bottom);

      return $(go.Shape,
      {
          fill: "transparent",  // changed to a color in the mouseEnter event handler
          strokeWidth: 0,  // no stroke
          width: horizontal ? NaN : 8,  // if not stretching horizontally, just 8 wide
          height: !horizontal ? NaN : 8,  // if not stretching vertically, just 8 tall
          alignment: align,  // align the port on the main Shape
          stretch: (horizontal ? go.GraphObject.Horizontal : go.GraphObject.Vertical),
          portId: name,  // declare this object to be a "port"
          fromSpot: spot,  // declare where links may connect at this port
          fromLinkable: output,  // declare whether the user may draw links from here
          toSpot: spot,  // declare where links may connect at this port
          toLinkable: input,  // declare whether the user may draw links to here
          cursor: "pointer",  // show a different cursor to indicate potential link point
          mouseEnter: function(e, port) {  // the PORT argument will be this Shape
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
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          // { contextMenu: myContextMenu },
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
        // four named ports, one on each side:
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
        // three named ports, one on each side except the top, all output only:
        makePort("L", go.Spot.Left, go.Spot.Left, true, false),
        makePort("R", go.Spot.Right, go.Spot.Right, true, false),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
        ));

    // replace the default Link template in the linkTemplateMap
    myDiagram.linkTemplate =
      $(go.Link,  // the whole link panel
      {
          routing: go.Link.AvoidsNodes,
          curve: go.Link.JumpOver,
          corner: 5, toShortLength: 4,
          relinkableFrom: true,
          relinkableTo: true,
          reshapable: true,
          resegmentable: true,
          // mouse-overs subtly highlight links:
          mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
          mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; },
          selectionAdorned: false
      },
      new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the highlight shape, normally transparent
          { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
        $(go.Shape,  // the link path shape
          { isPanelMain: true, stroke: "gray", strokeWidth: 2 },
          new go.Binding("stroke", "isSelected", function(sel) { return sel ? "dodgerblue" : "gray"; }).ofObject()),
        $(go.Shape,  // the arrowhead
          { toArrow: "standard", strokeWidth: 0, fill: "gray"}),
        $(go.Panel, "Auto",  // the link label, normally not visible
          { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
          new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#F8F8F8", strokeWidth: 0 }),
          $(go.TextBlock, "Yes",  // the label
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

  } // end init

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
                    //$('textarea#mySavedModel').val(data.resonse.flowchartdata);
                    init();
                    $('#myModal_map').modal('show');
                    $(".Loader").hide();

                }
            });   
});

$(document).on('click','body *',function(){

  // var st = $("#street_number").val();
  // var ad = $("#route").val();
  // var fulladders = st+' '+ad;

  // $("#autocomplete").val(fulladders);
});
</script>


<!-- <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GoogleApiKey; ?>&libraries=places&callback=initAutocomplete" async defer></script> -->

<script>

 function PrintDiv()
 {
    var divToPrint = document.getElementById('divToPrint');
    var popupWin = window.open('', '_blank', 'width=950,height=600');
    popupWin.document.open();
    popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
    popupWin.document.close();
}

      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
    };
    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
      }
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
}
      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
          };
          var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
          });
          autocomplete.setBounds(circle.getBounds());
      });
      }
  }
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
                        //console.log(v.id);
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
                                    //console.log(data.resonse);
                                    if(data.resonse){
                                    //console.log("inn");
                                    //$("#resonse").show();
                                    //$('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                    //$(".myfilelist").remove();
                                    $("a[data-id="+delid+"]").parents(".myfilelist").remove();
                                    dataTableFileUpload();
                                    swal("Success!", "Your document deleted!", "success");

                                    //$("#myModal").modal('hide');
                                }
                                else if(data.error){
                                    //$("#error").show();
                                    swal("Something wants wrong please try again!");

                                    //$('#errormsg').html('<span>'+data.error+'</span>');  
                                }
                            }
                        });
  }
  else{
    return false;
}
});
});

                // end for delete document > file 

                // Add new Docuemnt tab start

                $("#NewDocument").submit(function(e) {
                    e.preventDefault();
                    if($("#fileInput-error").text()!=""){
                                    //console.log($("#fileInput-error").text());
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
                                    //var form = $('#NewDocument')[0];
                                    var data = new FormData(form);
                                    var ffName = $('#fileName').val();
                                    //console.log(ffName);

                                    //var data = $("#NewClient").serialize();
                                    jQuery.ajax({
                                        dataType:"json",
                                        type:"post",
                                        data:data,
                                        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                                        processData: false,
                                        url:'<?php echo EXEC; ?>Exec_client_attech',
                                        success: function(data)
                                        {
                                            if(data.resonse2)
                                            {
                                                $("#resonse_document").show();
                                                //$('#resonsemsg_document').html('<span>'+data.resonse2+'</span>');
                                                $( '#NewDocument' ).each(function(){this.reset();});
                                                $(".Loader").hide();
                                                // setTimeout(function () { location. reload(); }, 1000) ;
                                                //location.reload();

                                            }
                                            else if(data.error2)
                                            {
                                                $("#error_document").show();
                                                $('#errormsg_document').html('<span>'+data.error2+'</span>');
                                                $(".Loader").hide();
                                                //location.reload();
                                                // alert('<li>'+data.error+'</li>');
                                            }else{
                                                $("#error_document").show();
                                                $('#errormsg_document').html('<span>'+data.error2+'</span>');
                                                $(".Loader").hide();
                                            }
                                            dataTableFileUpload();
                                            $('#listofcalserd').append('<option value="'+ffName+'">'+ffName+'</option>').trigger('change');
                                            $('#addDocumentsModal').modal('hide');
                                            //location.reload();
                                        }
                                    }
                                    );
                                }
                            });
                // Add new Docuemnt tab end

                function diffYMDHMS(finalDate) {
                  var CurrentDate = moment().format("DD-MM-YYYY HH:mm:ss");

                  var date1 = moment(finalDate,"DD-MM-YYYY HH:mm:ss");
                  var date2 =moment(CurrentDate,"DD-MM-YYYY HH:mm:ss");
                  let years,months,days,hours,minutes,seconds;

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


       //        function getTimeLine(dateDiff)
       //        {
       //         if(dateDiff.minutes < 60 && dateDiff.hours == 0 && dateDiff.days == 0 && dateDiff.months == 0 && dateDiff.years == 0)
       //         {

       //          return '<i class="far fa-clock"></i> ' +dateDiff.minutes + ' Minutes ago ';

       //      }


       //      if(dateDiff.minutes < 60 && dateDiff.hours > 0 && dateDiff.days == 0 && dateDiff.years == 0 && dateDiff.months == 0)
       //      {

       //         return '<i class="far fa-clock"></i> ' +dateDiff.hours + ' Hours ago  ';

       //     }

       //     if(dateDiff.minutes < 60 && dateDiff.hours < 24 && dateDiff.days > 0 && dateDiff.months == 0 && dateDiff.years == 0)
       //     { 

       //         return '<i class="far fa-clock"></i> ' +dateDiff.days + ' Days ago ';

       //     }

       //     if(dateDiff.minutes < 60 && dateDiff.hours < 24 && dateDiff.days < 31 && dateDiff.months > 0 && dateDiff.years == 0)
       //     { 

       //         return '<i class="far fa-clock"></i> ' +dateDiff.months + ' Months ago ';


       //     }

       //     if(dateDiff.minutes < 60 && dateDiff.hours < 24 && dateDiff.days < 31 && dateDiff.months < 12 && dateDiff.years > 0)
       //     { 


       //         return '<i class="far fa-clock"></i> ' +dateDiff.years + ' Years ago ';

       //     }
       // }





        // Start Data table for file Upload history tab


            //     var  fileuploadTable = $('#myFileUploadHistory').DataTable({
            //   "language": {
            //     "emptyTable": "Package history not available!"
            //   },
            //   "processing": false,
            //   "responsive": true,
            //   "processing": true,
            //   "destroy": true,
            // "autoWidth": false,
            //   "columnDefs": [
            //               { "targets" : '_all'},
            //               { "width": "10%", "targets": 0 ,"className" : 'text-center',},
            //               { "width": "1%", "targets": 1 ,"className" : 'text-center',},
            //               { "width": "9%", "targets": 2 ,"className" : 'text-center',},


            //     ],
            //     "ajax" : {
            //     "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?fileuploadHis='+getUid,
            //     "dataSrc" : ''
            //   },
            //   "autoWidth": false,
            //   "columns" : [ 
            //   {
            //     "data" : "fileName",
            //     render:function(data,type,row,meta){
            //        return  "<div>" + row.fileName +"</div>";

            //     }
            //   },
            //   {
            //     "data" : "document",
            //     render:function(data,type,row,meta)
            //     {


            //              var uploadFilesIcon;
            //        //var extension = row.document.substr((row.document.file.lastIndexOf('.') +1));
            //        var extension = row.document.split('.').pop();
            //        console.log(extension);
            //         switch(extension) {
            //             case 'xlsx':
            //                      uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/xsl.png" alt="user" class="" height="50px" width="50px">';
            //                         break;
            //             case 'pdf':
            //                        uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/pdf.png" alt="user" class="" height="50px" width="50px">';

            //                          break;
            //             case 'docx':
            //                         uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/msdoc.png" alt="user" class="" height="50px" width="50px">';
            //                         break;                        
            //             case 'doc':
            //                         uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/msdoc.png" alt="user" class="" height="50px" width="50px">';
            //                         break;
            //             case 'txt':
            //                         uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/txt.png" alt="user" class="" height="50px" width="50px">';
            //                          break;
            //             case 'csv':
            //                         uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/images/export-csv.png" alt="user" class="" height="50px" width="50px">';
            //                         break;
            //             default:
            //                        uploadFilesIcon = '<img src="<?php echo base_url; ?>/assets/ClientDocs/'+row.document+'" alt="user" class="" height="50px" width="50px">';
            //         }

            //         var patFile = "<?php  echo $base_url."/assets/ClientDocs/" ?>";
            //         console.log(patFile);

            //         return '<div class="activedatea parent_of_operation myfilelist"><div class="activeuserdetial activeuserdetialdocument"> '+uploadFilesIcon+' <h5><b><a href="'+patFile+row.document+'" target="_blank">'+row.fileName+' </a></b></h5> </div></div>';


            //     }
            //   }
            //   , 
            //   {
            //     "data": "orid",
            //     "render": function(data,type,row,meta) {


            //           // why i have to perform this kind of stuf beacause of when date is come from
            //           // database the formate is "2020-04-21 05:00:31pm" like this and momentjs is not support this formate


            //        var dbcreatedDate = moment().format(row.datecreated, "DD-MM-YYYY HH:mm:ss"); 
            //       var finalDate = moment(dbcreatedDate).format('DD-MM-YYYY HH:mm:ss');


            //        var dateDiff = diffYMDHMS(finalDate);

            //       var getTimeWidget = getTimeLine(dateDiff);
            //       return "<div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getTimeWidget+"</div></div>";


            //     }
            //   }
            //   ]
            // });


        // End Data table for file upload history tab


            // Start Data table for Package

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
                    var dateFormat = moment(datenew).format('MM-DD-YYYY hh:mm:ss a');

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


                      // why i have to perform this kind of stuf beacause of when date is come from
                      // database the formate is "2020-04-21 05:00:31pm" like this and momentjs is not support this format

                      var datenew = data.replace("pm", " pm");
                      var datenew = data.replace("am", " am");
                      var dateFormat = moment(datenew).format('MM-DD-YYYY HH:mm:ss');


                      var dbcreatedDate = moment().format(dateFormat, "DD-MM-YYYY HH:mm:ss"); 
                      var finalDate = moment(datenew).format('DD-MM-YYYY HH:mm:ss');



                      var dateDiff = diffYMDHMS(finalDate);

                      var getTimeWidget = getTimeLine(dateDiff);
                      return "<div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getTimeWidget+"</div></div>";


                  }
              },
              {
                "data":"orid"
            }
            ]
        });


            // End Data table for Package



// Start Data table for order history table
var orderTable = $('#myOrderHistory').DataTable({
  "language": {
    "emptyTable": "Order history not available!"
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
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?orderHis='+getUid,
    "dataSrc" : ''
},
"autoWidth": false,
"columns" : [ 
{
    "data" : "orderCreatorName",
    render:function(data,type,row,meta){

        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.orderCreatorName+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.orderCreatorName+'</h6></label></div>';


        }
    }
},
{
    "data" : "ProductTitle",
    render:function(data,type,row,meta)
    {

       var dateFormat = moment(row.odatecreated).format('MM-DD-YYYY h:mm a');
       var  orderCreateed = "<h5><b>"+row.odatecreated+"</b></h5>";
       var productTitle="",name="",serviceName="";
       if(row.ProductTitle != "" && row.ProductTitle != null)
       {
        productTitle = "<span><b>Product Name : </b> "+row.ProductTitle+"</span><br />";
    }

    if(row.Name != "" && row.Name != null)
    {
        name = "<span><b>Package Name : </b>"+row.Name+" </span><br />";
    } 

    if(row.ServiceName != "" && row.ServiceName != null)
    {
        serviceName = "<span><b>Service Name : </b> "+row.ServiceName+" </span>";
    }



    return  "<div>" + "<h5><b>" +dateFormat +"</b></h5>"+ productTitle+ name + serviceName +"</div>";



}
}
, 
{
    "data": "orid",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.orid);    

      var dbcreatedDate = moment().format(row.odatecreated, "DD-MM-YYYY HH:mm:ss"); 
      var finalDate = moment(dbcreatedDate).format('DD-MM-YYYY HH:mm:ss');


      var dateDiff = diffYMDHMS(finalDate);

      var getTimeWidget = getTimeLine(dateDiff);
      return "<a class='btn btn-info btn-xs' id='editorder' title='Edit Order' href='EditOrder.php?action=edit&id="+encodedId+"&cid="+getUid+"'><span class='fa fa-edit'></span></a>  <button class='btn btn-success btn-xs' id='viewButton' title='View Invoice' data-id="+encodedId+"><span class='fa fa-eye'></span></button><br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getTimeWidget+"</div></div>";


  }
},
{
    "data":"orid"
}
]
});


// End Data table for order history table



//Start Data Table for client communication tab


var communicationTable = $('#myCommunicationHistory').DataTable({
  "language": {
    "emptyTable": "Communication not available!"
},
"order": [[ 2, "desc" ]],
"processing": false,
"responsive": true,
"processing": true,
"destroy": true,
"autoWidth": false,
"columnDefs": [
{ "targets" : '_all'},
{ "width": "22%", "targets": 0 ,"className" : 'text-center',},
{ "width": "0%", "targets": 1 ,"className" : 'text-left',},
{ "width": "30%", "targets": 2 ,"className" : 'text-center',},


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

        if(row.userimg == '' || row.userimg == null || row.userimg === '')
        {


            return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.communicatorName+'</h6></label></div>';

        }
        else
        {
            return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.communicatorName+'</h6></label></div>';
            

        }
    }
},
{
    "data" : "type",
    render:function(data,type,row,meta)
    {
        var tag,subDetail;
        if(row.type == "sms")
        {
            tag = "<span class='label label-warning m-r-10'>SMS</span>";
        }
        else
        {
            tag = "<span class='label label-success m-r-10'>EMAIL</span>";
        }



        if(row.subject != "" && row.subject != null )
        {
            subDetail = row.subject;
        }
        else
        {
            subDetail = "No-Title-Found";
        }

        return  '<span class="view_Message_button viewMailModel" data-id='+row.id+'> '+ tag + subDetail + '</span>';



    }
}
, 
{
    "data": "comtime",
    "render": function(data,type,row,meta) {

      var dateFormat = moment(row.comtime).format('MM-DD-YYYY h:mm a');



      return "<span>"+dateFormat+"</span>";



  }
}
]
});

// End Data table for client communication tab



// My comunication model open start 
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
            $('#view_Message').modal('toggle');
        }
    })
});
// my communication model open end




// Start Data Tabale for notes history tab

var noteTable = $('#myAppointmentNote').DataTable({
  "language": {
    "emptyTable": "Note history not available!"
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
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?noteHis='+getUid,
    "dataSrc" : ''
},
"autoWidth": false,
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

       var dateFormat = moment(row.datecreated).format('MM-DD-YYYY h:mm a');

       return  '<div class="activeuserdetial"><h5><b>'+row.noteTitle+'</b></h5><span><h6 style="display: inline-block;">Note detail : </h6> </span><span>'+row.noteDetail +' <br /> </span><span> <h6 style="display: inline-block;">Note created date : </h6> </span>'+dateFormat+'</div>';



   }
}
, 
{
    "data": "noteId",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.noteId);
      var noteCreatedDate = moment().format(row.datecreated, "DD-MM-YYYY HH:mm:ss"); 
      var finalNoteDate = moment(noteCreatedDate).format('DD-MM-YYYY HH:mm:ss');


      var noteDateDiff = diffYMDHMS(finalNoteDate);

      var getNoteTimeWidget = getTimeLine(noteDateDiff);

      return "<div class=''><a class='btn btn-info btn-xs edit_note' style='color: white' id='edit_note' title='Edit Note' data-id="+encodedId+"><span class='fa fa-edit'><span></a> <a class='btn btn-danger btn-xs ' id='deleteNote' title='Delete Note' data-id="+encodedId+" style='color: white'><span class='fa fa-trash'></span></a></div><br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getNoteTimeWidget+"</div></div>";



  }
},
{
    "data":"noteId"
}
]
});



// End Data Tabale for notes history tab

$(document).on('click','#deleteNote',function(e){
 var dNoteLink = $(this).attr('data-id');
             //console.log(dNoteLink);
             e.preventDefault();
             var link = $(this).attr('href');
             var parent = $(this).parents(".activedatea");
             swal({
                title: "Are you sure?",
                text: "you are really want to delete this Note!",
                icon: "warning",
                buttons: true,
            }
            ).then((willDelete)=>{
                if (willDelete){

                 $.ajax({
                    dataType:"json",
                    type:"post",
                    data:{'dNoteLink':dNoteLink,'cliID':getUid},
                    url:window.location.href+'?action=deletefile',
                    success: function(data)
                    {
                        //console.log(data);
                        if(data.resonse){
                          $(".Loader").hide();
                          swal(data.resonse);
                          parent.remove();
                          noteTable.ajax.reload(); 


                      }
                      else if(data.error){

                       $(".Loader").hide();
                       swal('Something is wrong please try agine');

                                                        // alert('<li>'+data.error+'</li>');
                                                    }
                                                }
                                            });
                    //window.location.href = link;


                }
                else{
                 return false ;
             }
         });
        });


        // Start data table for appoitment history tab 

       //console.log(getUid); 

       var table = $('#myAppoitmentHistory').DataTable({
          "language": {
            "emptyTable": "Appointment history not available!"
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
        { "width": "22%", "targets": 0,"className" : 'text-center', },
        { "width": "0%", "targets": 1,"className" : 'text-left', },
        { "width": "15%", "targets": 2,"className" : 'text-center', },
        { "width": "20%", "targets": 3,"className" : 'text-center', },


        ],
        "ajax" : {
            "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?appoitHis='+getUid,
            "dataSrc" : ''
        },
        "autoWidth": false,
        "columns" : [ 
        {
            "data" : "serviceProviderName",
            render:function(data,type,row,meta){

                if(row.userimg == '' || row.userimg == null || row.userimg === '')
                {


                    return '<div class="activeuserimage text-center"><img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.serviceProviderName+'</h6></label></div>';

                }
                else
                {
                    return '<div class="activeuserimage text-center"> <img src="<?php echo base_url; ?>/assets/userimage/'+row.userimg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+row.serviceProviderName+'</h6></label></div>';


                }
            }
        },
        {
            "data" : "title",
            render:function(data,type,row,meta)
            {
           // var dateEventFormat = moment(row.EventDate).format('MM-DD-YYYY h:mm a');
           // console.log(dateEventFormat);

           return  '<div class="activeuserdetial"><h5><b>'+row.title+'</b></h5><span><h6 style="display: inline-block;">Service Name : </h6> </span><span>'+row.ServiceName +' <br /> </span><span> <h6 style="display: inline-block;">Service Date : </h6> </span>'+row.EventDate+' <br /> <span> </div>';


           
       }
   }
   ,
        {
            "data" : "title",
            render:function(data,type,row,meta)
            {
           // var dateEventFormat = moment(row.EventDate).format('MM-DD-YYYY h:mm a');
           // console.log(dateEventFormat);

           return  '<div class="activeuserdetial"><span> <h6 style="display: inline-block;">'+row.eventstatus+'<h6> </span></div>';


           
       }
   }
   , 
   {
    "data": "eid",
    "render": function(data,type,row,meta) {

      var encodedId = window.btoa(row.eid);
       var encodedOrderId = window.btoa(row.OrderID);
      var noteCreatedDate = moment().format(row.newdate, "DD-MM-YYYY HH:mm:ss"); 
      var finalEventDate = moment(noteCreatedDate).format('DD-MM-YYYY HH:mm:ss');


      var eventDateDiff = diffYMDHMS(finalEventDate);

      var getEventTimeWidget = getTimeLine(eventDateDiff);

      var action= "<div class=''><a class='btn btn-info btn-xs EditButton' style='color: white' id='EditButton' title='Edit Appointment' data-id="+encodedId+"><span class='fa fa-edit'><span></a> <a class='btn btn-danger btn-xs ' style='color: white' id='deleteAppointment' title='Delete Appointment' data-id="+encodedId+" ><span class='fa fa-trash'><span></a>";

      if(row.OrderID!=null && row.eventstatus==='completed')
      {
        action+="<button class='btn btn-success btn-xs' id='viewButtoninvoice' title='View Invoice' data-id='"+encodedOrderId+"'><span class='fa fa-eye'></span></button>";
      }

      action+="</div><br /><div class='usertime'><div class='activeusertime  badge badge-pill badge-primary'>"+getEventTimeWidget+"</div></div>";
      return action;

  }
},
{
    "data" : "eid"
}
]
});


        // end data table for appoitment history tab 




        $(document).on('click','#deleteAppointment',function(e){
          e.preventDefault();
          $(".Loader").show();
          var parent = $(this).parents(".activedatea");

          var dlink = $(this).attr('data-id');

          swal({
            title: "Are you sure?",
            text: "Once deleted, you will lost all data of this Client account!",
            icon: "warning",
            buttons: true,
        }).then((willDelete)=>{   
            if (willDelete){
               $.ajax({
                dataType:"json",
                type:"post",
                data:{'dlink':dlink},
                url:'?action=deletefile',
                success: function(data)
                {
                    if(data.resonse){
                      $(".Loader").hide();
                      swal(data.resonse);
                      parent.remove();
                      table.ajax.reload(); 


                  }
                  else if(data.error){

                   $(".Loader").hide();
                   swal('Something is wrong please try agine')

                                                        // alert('<li>'+data.error+'</li>');
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




        $(document).on('click','.edit_note',function(e){

          e.preventDefault();

          var mynoteid = $(this).data('id');

          $.ajax({
            dataType:"json",
            type:"post",    
            data:{'mynoteid':mynoteid},
            "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteid="+mynoteid,
            success: function(data)
            {
                //console.log(data);
                if(data.resonse)
                {
                    $('#noteTitle').val(data.resonse.noteTitle)
                                          // $('#noteDetail').val(data.resonse.noteDetail)
                                          $('#noteDetail').data("wysihtml5").editor.setValue(data.resonse.noteDetail);
                                          $('.editnoteid').val(data.resonse.id);
                                          // NotedataTable(data.resonse.noteRelated)
                                          $('#addNoteModal').modal('show');
                                      }
                                      else if(data.error)
                                      {

                                          swal("Sorry something wrong please try agine")
                                      }
                                  }
                              });
      });

        $('.packaeditorderviewButton').on('click',function(){

            $('.Loader').show()
            var packaeditorderviewButtonid = $(this).data("id");
            var dlinkcid = '<?php if(isset($_GET['id'])) { echo $_GET['id']; }  ?>';
            $.ajax({
              dataType:"json",
              type:"post",
              data: {'packaeditorderviewButtonid':packaeditorderviewButtonid,'dlinkcid':dlinkcid},
              url:'?action=editfile',
              success: function(data)
              {
                  if(data)
                  {

                    swal(data.resonse)
                    setTimeout(function () { window.location.reload(); }, 2000) ; 
                    $('.Loader').hide()
                }

            }
        })

        });
        $('.packaeditorder').on('click',function(){

          $('.Loader').show()

          var packageorderid = $(this).data("id");


          $.ajax({
              dataType:"json",
              type:"post",
              data: {'packageorderid':packageorderid},
              url:'?action=editfile',
              success: function(data)
              {
                  if(data)
                  {

                   $('#packagename').val(data.resonse.Name)
                   $('#PckageAmount').val(data.resonse.MembershipFianlPrice)
                   $('.count').val(data.resonse.Noofvisit)
                   $('#package_expire_date').val(data.resonse.package_expire_date)
                   $('#OrderMembershipid').val(data.resonse.id)
                   $('#packaeditordermodel').modal('show')    
                   $('.Loader').hide()
               }

           }
       })




      });

        $('#add_memberships_save').on('click',function(event){
            event.preventDefault();
            var remainvisti = $('.count').val()
            var package_expire_date = $('#package_expire_date').val()
            var OrderMembershipid = $('#OrderMembershipid').val()


            $.ajax({
              dataType:"json",
              type:"post",
              data: {'remainvisti':remainvisti,'package_expire_date':package_expire_date,'OrderMembershipid':OrderMembershipid},
              url:'?action=editfile',
              success: function(data)
              {
                  if(data)
                  {
                    swal(data.resonse)
                    setTimeout(function () { window.location.reload(); }, 2000) ;

                }

            }
        })


        });

        $(document).on('click','.plus',function(){
            $('.count').val(parseInt($('.count').val()) + 1 );
        });



        $(document).on('click','.minus',function(){


            $('.count').val(parseInt($('.count').val()) - 1 );

            if ($('.count').val() == 0) 
            {
               $('.count').val(1);
           }

       });



        $(document).on('click', '#addnewappointment', function(e){
            var ClientsName = '<?php echo $cid; ?>';

            $('.editcustomer').val(btoa(ClientsName));
            $('#editspan').hide();
            $('#repeatdiv').show();
            curntuser='<?php echo $_SESSION['UserID']; ?>';
            $.ajax({
              dataType:"json",
              type:"post",
              data: {'curntuser':curntuser},
              url:'?action=editfile',
              success: function(data)
              {
                  if(data)
                  {       

                    $('#newlistofclient').html("");
                    $('#newlistofclient').append('<option value disabled="true" selected="selected">Select your Client</option>'); 
                    $.each(data.resonse, function( index, value ) 
                    {
                      $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
                      var cid = '<?php echo $cid; ?>';
                      var cFirstName =  '<?php echo $FirstName; ?>';
                      var cLastName =  '<?php echo $LastName; ?>';
                $('#newlistofclient option:selected').text(cFirstName + ' ' + cLastName).val(cid).select2().trigger('change'); // new
            });
                    $(".Loader").hide();
                }
                else if(data.error)
                {
                    $("#error").show();
                    $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                } 
            }
        })
        });

        $('#newlistofclient').on('change',function(){



         $('#clinetdetails').hide();
         var ClientsName=$(this).val();

         if(ClientsName == null)
         {
            var ClientsName = '<?php echo $cid; ?>';

        }
        $(".Loader").show();   

        if($('#NewEvent input[name="id"]').val() == 'new'){
          var radioValue = $("input[name='Location_radio']:checked").val();
      }else{
        var radioValue = $('#evnet_Location_radio').val();
    //alert(radioValue);
    // 
}


$.ajax({

  dataType:"json",
  type:"post",
  data: {'ClientsName':ClientsName},
  url:'?action=editfile',
  success: function(data)
  {
   if(data)

   {    
    $(".Loader").hide();   
    $('#FirstName').val(data.resonse.FirstName);
    $('#LastName').val(data.resonse.LastName);
    $('#Phone').val(data.resonse.Phone);
    $('#Email').val(data.resonse.email);
    $('#clinetdetails').show();
    $('#newname').html('<b>Name :</b>' + data.resonse.FirstName + ' ' + data.resonse.LastName);
    $('#newphone').html('<b>Cell : </b>' + data.resonse.Phone);
    $('#newemail').text(data.resonse.email);

    if(radioValue=='Customer Location')
    { 

      var useradd = $('#listofcatagory3').val(); 

      if(useradd == null)
      {
         var useradd= '<?php echo $_SESSION['UserID']; ?>';
     }

     $("#ServiceProvider").val(useradd);  
     $('#Address').val(data.resonse.Address);
     $('#Zip').val(data.resonse.Zip);
     $('#City').val(data.resonse.City);
     $('#State').val(data.resonse.State);
     $('#newcountry').val(data.resonse.Country);

 }
 else
 {

   if($('#NewEvent input[name="id"]').val() == 'new'){
      var useradd = $('#listofcatagory3').val(); 
      if(useradd == null)
      {
         var useradd = '<?php echo $_SESSION['UserID']; ?>';
     }

     $.ajax({
      dataType:"json",
      type:"post",
      data: {'useradd':useradd},
      url:'?action=editfile',
      success: function(data3)
      {
        if(data3)
        { 
          $("#ServiceProvider").val(useradd);
          $('#Address').val(data3.resonse.primaryaddress);
          $('#Zip').val(data3.resonse.zipcode);
          $('#City').val(data3.resonse.city);
          $('#State').val(data3.resonse.state);
          $('#newcountry').val(data3.resonse.country);
          $(".Loader").hide();
      }
  }
});

 }

}

$('#cid').val(data.resonse.id);
$('.editcustomer').val(btoa(data.resonse.id));
if(data.resonse.ProfileImg!='')
{
   $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
}
else if(data.resonse.ProfileImg=='')
{
   $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
}
}
else if(data.error)
{
    swal("Oops...", "Something went wrong!", "error");
}

}
})

});


        $('#newlistofcatagory').on('change',function(){

            $('#listofcatagory3').html('');
            var listofcatagory=$(this).val();  
            
            $(".Loader").show();

            var appointmentTitle = $("#newlistofcatagory option:selected").text();
            
            $('#ServiceName').val(listofcatagory); 
            $('#title').val(appointmentTitle);

            Servicename=$(this).val();

            $.ajax({

             dataType:"json",
             type:"post",
             data: {'Servicename':Servicename},
             url:'?action=editfile',
             success: function(data)
             {
                if(data)
                {
                    $('.serviceproviderblock').show()
                    var dur = data.resonse.Duration.split(' ');
                    $('#duration').val(dur[0]);
                    $('.minhour').text(dur[1]);
                    $('#CostOfService').val(data.resonse.Price);          
                    $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                    $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                    $('#EmailInstruction').data("wysihtml5").editor.setValue(data.resonse.Info);
                    var listarray = data.resonse.Users
                    $.ajax({

                      type:"post",
                      data: {'UserName':listarray},
                      url:'?action=editfile',
                      dataType: 'json',
                      success: function(data2)
                      {
                       if(data2)
                       {

                        $.each(data2.resonse, function (key, val) 
                        {
                          if(val.id==$('#editServiceProvider').val())
                          {
                              $('#listofcatagory3').append('<option selected  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');      
                          }
                          else
                          {
                             $('#listofcatagory3').append('<option  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                         }

                         $(".Loader").hide();   

                     });

                    }
                }

            });
                }
                else if(data.error)
                {
                    alert('ok');  
                    $(".Loader").hide();   
                }
            }
        })


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

        // $(document).on('change','.time_pick #eventstartime',function(){
        //     console.log('run1');
        // });

        // $("#eventstartime").change(function(){
        //     var service_star_time = $(this).val();
        //     $("#eventenddate").val(service_star_time);
        //     console.log('run');
        //     // $('input[id$=eventstardate]').datepicker({


        //     // });
        // }
        // );

        $("#eventstardate").change(function(){
            var service_star_time = $(this).val();
            $("#eventenddate").val(service_star_time);
        });



        $(document).on("change","#eventstartime",function(){ 
          setTimeout(function() {
            var service_star_time = $("#eventstartime").val();
            var serivename=  $("#ServiceName").val();
            EventStartFunction(service_star_time,serivename);
        },100);

      });

        function EventStartFunction(service_star_time,serivename){
          $.ajax({

           dataType:"json",
           type:"post",
           data: {'service_star_time':service_star_time,'serivename':serivename},
           url:'?action=service_star_time',
           success: function(data)
           {
            if(data)
            {
                      //console.log(data.resonse);
                      $("#eventendtime").val(data.resonse);
                  }
                  else if(data.error)
                  {
                      //alert('ok');
                      console.log("error in select end date");  
                  }

              }
          });
      }


        //      $(".eventChange").change(function(){
        //     var service_star_time = $(this).val();
        //     console.log("start time in");
        //     console.log(service_star_time);

        //     var serivename=  $("#ServiceName").val();
        //     $(".Loader").show();
        //     $.ajax({
        //         dataType:"json",
        //         type:"post",
        //         data: {'service_star_time':service_star_time,'serivename':serivename},
        //         url:'?action=service_star_time',
        //         success: function(data)
        //         {
        //             if(data)
        //             {
        //                 $(".Loader").hide();
        //                                         //location.reload();
        //                 ("#eventendtime").val(data.resonse);
        //             }
        //             else if(data.error)
        //             {
        //                 alert('ok');
        //             }
        //         }
        //       })
        // }
        // );



        // $("#eventstartime").change(function(){
        //     var service_star_time = $(this).val();
        //     console.log("start time in");
        //     console.log(service_star_time);

        //     var serivename=  $("#ServiceName").val();
        //     $(".Loader").show();
        //     $.ajax({
        //         dataType:"json",
        //         type:"post",
        //         data: {'service_star_time':service_star_time,'serivename':serivename},
        //         url:'?action=service_star_time',
        //         success: function(data)
        //         {
        //             if(data)
        //             {
        //                 $(".Loader").hide();
        //                                         //location.reload();
        //                                         $("#eventendtime").val(data.resonse);
        //                                     }
        //                                     else if(data.error)
        //                                     {
        //                                         alert('ok');
        //                                     }
        //                                 }
        //                             })
        // }
        // );
    }
    );
</script>
<!-- end delete tag popup -->
<script>
    $(document).on('keyup','#CostOfService',function(){
        if (/\D/g.test(this.value))
        {
            this.value = this.value.replace(/\D/g, '');
        }
    }
    );
</script>
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
                                $("#resonse").show();
                                $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                $( '#Newtag' ).each(function(){
                                    this.reset();
                                }
                                );
                                $(".Loader").hide();
                                $('#addTagModel').hide();
                                                // setTimeout(function () { window.location.href = "Alltag.php"; }, 1000) ;
                                                window.location.reload();
                                            }
                                            else if(data.error)
                                            {
                                                $("#error").show();
                                                $('#errormsg').html('<span>'+data.error+'</span>');
                                                $('#addTagModel').hide();
                                                $(".Loader").hide();
                                                // alert('<li>'+data.error+'</li>');
                                            }
                                        }
                                    }
                                    );
                }
            }
            );
        </script>
        <script src="<?php echo base_url ?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
        <script>
            jQuery(document).ready(function() {
                                // Switchery
                                $(".listofclientdiv").hide();
                                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                                $('.js-switch').each(function() {
                                    new Switchery($(this)[0], $(this).data());
                                }
                                );
                                // For select 2
                                $(".select2").select2();
                                // $('.selectpicker').selectpicker();
                                //Bootstrap-select2
                                $(".vertical-spin").select2({
                                    verticalbuttons: true,
                                    verticalupclass: 'ti-plus',
                                    verticaldownclass: 'ti-minus'
                                }
                                );
                                var vspinTrue = $(".vertical-spin").select2({
                                    verticalbuttons: true
                                }
                                );
                                if (vspinTrue) {
                                    $('.vertical-spin').prev('.bootstrap-select2-prefix').remove();
                                }
                                $("input[name='tch1']").select2({
                                    min: 0,
                                    max: 100,
                                    step: 0.1,
                                    decimals: 2,
                                    boostat: 5,
                                    maxboostedstep: 10,
                                    postfix: '%'
                                }
                                );
                                $("input[name='tch2']").select2({
                                    min: -1000000000,
                                    max: 1000000000,
                                    stepinterval: 50,
                                    maxboostedstep: 10000000,
                                    prefix: '$'
                                }
                                );
                                $("input[name='tch3']").select2();
                                $("input[name='tch3_22']").select2({
                                    initval: 40
                                }
                                );
                                $("input[name='tch5']").select2({
                                    prefix: "pre",
                                    postfix: "post"
                                }
                                );
                                // For select2
                                $('#pre-selected-options').select2();
                                $('#optgroup').select2({
                                    selectableOptgroup: true
                                }
                                );
                                $('#public-methods').select2();
                                $('#select-all').click(function() {
                                    $('#public-methods').select2('select_all');
                                    return false;
                                }
                                );
                                $('#deselect-all').click(function() {
                                    $('#public-methods').select2('deselect_all');
                                    return false;
                                }
                                );
                                $('#refresh').on('click', function() {
                                    $('#public-methods').select2('refresh');
                                    return false;
                                }
                                );
                                $('#add-option').on('click', function() {
                                    $('#public-methods').select2('addOption', {
                                        value: 42,
                                        text: 'test 42',
                                        index: 0
                                    }
                                    );
                                    return false;
                                }
                                );
                                $(".ajax").select2({
                                    ajax: {
                                        url: "https://api.github.com/search/repositories",
                                        dataType: 'json',
                                        delay: 250,
                                        data: function(params) {
                                            return {
                                                q: params.term, // search term
                                                page: params.page
                                            };
                                        }
                                        ,
                                        processResults: function(data, params) {
                                            // parse the results into the format expected by select2
                                            // since we are using custom formatting functions we do not need to
                                            // alter the remote JSON data, except to indicate that infinite
                                            // scrolling can be used
                                            params.page = params.page || 1;
                                            return {
                                                results: data.items,
                                                pagination: {
                                                    more: (params.page * 30) < data.total_count
                                                }
                                            };
                                        }
                                        ,
                                        cache: true
                                    }
                                    ,
                                    escapeMarkup: function(markup) {
                                        return markup;
                                    }
                                    , // let our custom formatter work
                                    minimumInputLength: 1,
                                    //  templateResult: formatRepo, // omitted for brevity, see the source of this page
                                    // templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                                }
                                );
                            }
                            );
                        </script>
                        <script src="
                        <?php echo base_url; ?>/assets/js/jquery.validate.js"></script>
                        <script>
                            $(document).ready(function(){
                                $("#NewNote").validate({
                                    rules: {
                                        noteTitle: {
                                            required: true,}
                                            ,
                                            noteDetail: {
                                                required: true,}
                                                ,
                                                "noteRelated[]": {
                                                    required: true,}
                                                    ,
                                                }
                                                ,
                                                messages: {
                                                    noteTitle: {
                                                        required: "Please enter note title"}
                                                        ,
                                                        noteDetail: {
                                                            required: "Please enter note detail"}
                                                            ,
                                                            "noteRelated[]": {
                                                                required: "Please relate the note to a contact"}
                                                                ,
                                                            }
                                                            ,
                                                            ignore: ":hidden:not(textarea)",
                                                            errorPlacement: function( label, element ) {
                                                                if( element.attr( "name" ) === "noteDetail"  || element.attr( "name" ) === "noteRelated[]") {
                                                                    element.parent().append( label );
                                                                }
                                                                else {
                                                                    label.insertAfter( element );
                                                                }
                                                            }
                                                            ,
                                                            submitHandler: function() {
                                                                $(".Loader").show();
                                                                var data = $("#NewNote").serialize();
                                                                $data= data + "&Action=note";
                                                                jQuery.ajax({
                                                                    dataType:"json",
                                                                    type:"post",
                                                                    data:data,
                                                                    url:'<?php echo EXEC; ?>Exec_Edit_Note',
                                                                    success: function(data)
                                                                    {
                                                                        if(data.resonse)
                                                                        {
                                                                            $("#resonse_note").show();
                                                                            $('#resonsemsg_note').html('<span>'+data.resonse+'</span>');
                                                                            $( '#NewNote' ).each(function(){
                                                                                this.reset();
                                                                            });

                                                                            $(".Loader").hide();
                                                                            var noteTable = $('#myAppointmentNote').DataTable();

                                                                            noteTable.ajax.reload();
                                                                            
                                                                            $('#addNoteModal').modal('hide');
                                                                            //setTimeout(function () {location. reload();}, 1000);

                                                                        }
                                                                        else if(data.error)
                                                                        {
                                                                            $("#error_note").show();
                                                                            $('#errormsg_note').html('<span>'+data.error+'</span>');
                                                                            $(".Loader").hide();
                                                    // alert('<li>'+data.error+'</li>');
                                                }
                                            }
                                        }
                                        );
                                                            }
                                                        }
                                                        );
}
);
</script>
<!-- wysuhtml5 Plugin JavaScript -->
<script src="<?php echo  base_url ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo  base_url ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
<script>
    $(document).ready(function() {
        $('#noteDetail').wysihtml5();
        $('#EmailInstruction').wysihtml5();
        $('#smsMessage').wysihtml5();

        $("#sendMail").click(function(){
            var value="<?= $email ?>,<?= $cid ?>";
            $("#To").val(value).trigger("change");
        });

    });

</script>
<script src="<?php echo base_url ?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {
                                // Switchery
                                $(".listofclientdiv").hide();
                                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                                $('.js-switch').each(function() {
                                    new Switchery($(this)[0], $(this).data());
                                }
                                );
                                // For select 2
                                $(".select2").select2();
                                // $('.selectpicker').selectpicker();
                                //Bootstrap-select2
                                $(".vertical-spin").select2({
                                    verticalbuttons: true,
                                    verticalupclass: 'ti-plus',
                                    verticaldownclass: 'ti-minus'
                                }
                                );
                                var vspinTrue = $(".vertical-spin").select2({
                                    verticalbuttons: true
                                }
                                );
                                if (vspinTrue) {
                                    $('.vertical-spin').prev('.bootstrap-select2-prefix').remove();
                                }
                                $("input[name='tch1']").select2({
                                    min: 0,
                                    max: 100,
                                    step: 0.1,
                                    decimals: 2,
                                    boostat: 5,
                                    maxboostedstep: 10,
                                    postfix: '%'
                                }
                                );
                                $("input[name='tch2']").select2({
                                    min: -1000000000,
                                    max: 1000000000,
                                    stepinterval: 50,
                                    maxboostedstep: 10000000,
                                    prefix: '$'
                                }
                                );
                                $("input[name='tch3']").select2();
                                $("input[name='tch3_22']").select2({
                                    initval: 40
                                }
                                );
                                $("input[name='tch5']").select2({
                                    prefix: "pre",
                                    postfix: "post"
                                }
                                );
                                // For select2
                                $('#pre-selected-options').select2();
                                $('#optgroup').select2({
                                    selectableOptgroup: true
                                }
                                );
                                $('#public-methods').select2();
                                $('#select-all').click(function() {
                                    $('#public-methods').select2('select_all');
                                    return false;
                                }
                                );
                                $('#deselect-all').click(function() {
                                    $('#public-methods').select2('deselect_all');
                                    return false;
                                }
                                );
                                $('#refresh').on('click', function() {
                                    $('#public-methods').select2('refresh');
                                    return false;
                                }
                                );
                                $('#add-option').on('click', function() {
                                    $('#public-methods').select2('addOption', {
                                        value: 42,
                                        text: 'test 42',
                                        index: 0
                                    }
                                    );
                                    return false;
                                }
                                );
                                $(".ajax").select2({
                                    ajax: {
                                        url: "https://api.github.com/search/repositories",
                                        dataType: 'json',
                                        delay: 250,
                                        data: function(params) {
                                            return {
                                                q: params.term, // search term
                                                page: params.page
                                            };
                                        }
                                        ,
                                        processResults: function(data, params) {
                                            // parse the results into the format expected by select2
                                            // since we are using custom formatting functions we do not need to
                                            // alter the remote JSON data, except to indicate that infinite
                                            // scrolling can be used
                                            params.page = params.page || 1;
                                            return {
                                                results: data.items,
                                                pagination: {
                                                    more: (params.page * 30) < data.total_count
                                                }
                                            };
                                        }
                                        ,
                                        cache: true
                                    }
                                    ,
                                    escapeMarkup: function(markup) {
                                        return markup;
                                    }
                                    , // let our custom formatter work
                                    minimumInputLength: 1,
                                    //  templateResult: formatRepo, // omitted for brevity, see the source of this page
                                    // templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                                }
                                );
                            }
                            );
                        </script>
                        <script type="text/javascript">

                            $('input[type="radio"]').click(function(){
                                if($(this).prop("checked") == true){
                                    var cjecl=$(this).val();
                                }
                            }
                            );
                            $('.myModal_new').on('click',function(event){
                                event.preventDefault();
                                var listofcatagory=$('#listofcatagory').val();
                                $('#listofcatagory_error').text("");
                                if(listofcatagory=='' || $('input[name=Location]:checked').length<=0)
                                {
                                    $('#listofcatagory_error').text("Please select service and also location");
                                }
                                else if(listofcatagory!='' || $('input[name=Location]:checked').length>0)
                                {
                                    $('#myModal').modal('hide');
                                    $('#myModal_addclient').modal('toggle');
                                    $('#listofcatagory_error').hide();
                                }
                            });

                            
                        </script>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                // formatting phone number

                                jQuery.validator.addMethod("time_valid", function (value, element) { 
                                    var date=$("#eventstardate").val();
                                    if(date==moment().format("MM-DD-YYYY")){
                                      current_time = moment(moment().format("hh:mma"), 'hh:mma').diff(moment().startOf('day'), 'seconds');
                                      set_time = moment(value, 'hh:mma').diff(moment().startOf('day'), 'seconds');
                                      if(set_time>=current_time){
                                        return true;
                                    }else{
                                        return false
                                    }
                                }else{
                                   return true;
                               }
                           }, "Set time after the current time.");

                                $('#Phone').keyup(function(e){
                                    var ph = this.value.replace(/\D/g,'').substring(0,10);
                                    // Backspace and Delete keys
                                    var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
                                    var len = ph.length;
                                    if(len==0){
                                        ph=ph;
                                    }
                                    else if(len<3){
                                        ph='('+ph;
                                    }
                                    else if(len==3){
                                        ph = '('+ph + (deleteKey ? '' : ') ');
                                    }
                                    else if(len<6){
                                        ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
                                    }
                                    else if(len==6){
                                        ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
                                    }
                                    else{
                                        ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
                                    }
                                    this.value = ph;
                                }
                                );
                                // end formatting phone number
 ///Time Slot

 $("#wdateshidden").change(function(){
  $("#time_slot").hide();
  $("#eventstartime").val("");
  $("#eventstartime").attr("placeholder","Click here to select Start Time");
});

 $(document).mouseup(function(e) 
 {
  var container = $("#time_slot");

  if (!container.is(e.target) && container.has(e.target).length === 0) 
  {
    container.hide();
    if($("#eventstartime").val()==""){
      $("#eventstartime").attr("placeholder","Click here to select Start Time");
  }
}
});

 $(document).on("click","#time_slot .close, #newlistofcatagory, #listofcatagory3, #eventstardate",function(){
  $("#time_slot").hide();
  $("#eventstartime").val("");
  $("#eventstartime").attr("placeholder","Click here to select Start Time");
});

 $("#eventstartime").click(function(){

  service_date = "";

  $("#time_slot").hide();
  $("#eventstartime").val("");
  $("#eventstartime").attr("placeholder","Loading available time...");

  if($("#wdateshidden").val()){
    service_date = $("#wdateshidden").val().substring(1,$("#wdateshidden").val().length);
}else{
    if($("#eventstardate").val()){
      date = $("#eventstardate").val().split('-');
      service_date = date[2]+"-"+date[0]+"-"+date[1];
  }
}

$.ajax({
    url:'<?php echo EXEC; ?>Exec_Edit_Event?get_service_time',
    type:"post",
    data:{
      service_provider: $("#listofcatagory3").val(),
      service_date: service_date,
      duration: $("#duration").val()+" "+$('.minhour').text()
  },
  success:function(data){
      $("#time_slot").html("");
      $("#eventstartime").attr("placeholder","Click here to select Start Time");
      data = JSON.parse(data);
      if(data.response){
        $("#time_slot").append("<label class='time_slot_header'><a href='#' class='close' aria-label='close'>&times;</a><h4>Available Time</h4></label>");

        var service_date = $("#eventstardate").val();

        for(i=0;i<data.response.length;i++){

          time = data.response[i].split("-");
          start = time[0];
          end = time[1];

          if(service_date==moment().format("MM-DD-YYYY")){

            current_time = moment(moment().format("hh:mma"), 'hh:mma').diff(moment().startOf('day'), 'seconds');

            set_time = moment(start, 'hh:mma').diff(moment().startOf('day'), 'seconds');

            if(set_time>=current_time){
              $("#time_slot").append("<div class='col-lg-3 time_slot_box' data-id='"+end+"'>"+start+"</div>");
          }

      }else{
         $("#time_slot").append("<div class='col-lg-3 time_slot_box' data-id='"+end+"'>"+start+"</div>");
     }
 }
}else if(data.error1){
  swal({
    text: data.error1,
    icon: "error",
    buttons: true,
}).then((willDelete)=>{   
    if (willDelete){
      window.open('https://mysunless.com/crm/AllEmployees', '_blank');
  }
  else{
      return false;
  }
});
}else if(data.error){
  swal("",data.error,"error");
          //$("#time_slot").html(data.error);
          $("#eventstartime").val("");
      }
      $("#time_slot").css('display','flex');
  }
});
});

 $(document).on("click",".time_slot_box",function(){
  console.log("run");
  $("#eventstartime").val($(this).text());
  $("#eventendtime").val($(this).attr("data-id"));
  $("#time_slot").hide();
  $("#time_slot").html("");
}); 
    ///End Time Slot


    $("#NewEvent").validate({
        ignore: ":hidden:not(textarea)",
        rules: {
            title: {required: true,},
            FirstName: {required: true,},
            LastName: {required: true,},
            Phone: {required: true,},
            sd: {required: true,},
            st: {required: true,
              time_valid: true,
              remote:{
                url:'<?php echo EXEC; ?>Exec_Edit_Event?get_service_time',
                type:"post",
                data:{
                  id:function(){
                    return $("#NewEvent #id").val();
                },
                service_provider: function() {
                    return $("#listofcatagory3").val();
                },
                service_date: function() {
                    if($("#wdateshidden").val()){
                      service_date = $("#wdateshidden").val().substring(1,$("#wdateshidden").val().length);
                  }else{
                      date = $("#eventstardate").val().split('-');
                      service_date = date[2]+"-"+date[0]+"-"+date[1];
                  }
                  return service_date;
              },
              service_time_start: function() {
                return $("#eventstartime").val();
            },
            duration: function(){
                return $("#duration").val()+" "+$('.minhour').text();
            }
        }
    }
},
ed: {required: true,},
et: {required: true,},
eventstatus: {required:true,},
Address: {required: true,},
Zip: {required: true,},
City: {required: true,},
CostOfService: {required: true,number: true},
EmailInstruction: {required: true,},
Email: {required: true,},
State: {required: true,},
country: {required:true},
listofcatagory3: {required:true,},
},

messages: {
    FirstName: {required: "Please enter first name"},
    LastName: {required: "Please enter last name"},
    Phone: {required: "Please enter phone number"},
    sd: {required: "Select start date &nbsp"},
    ed: {required: "Select end date &nbsp"},
    st: {required: "Select start time &nbsp"},
    et: {required: "Select end time &nbsp"},
    eventstatus: {required: "Please select appointment status"},
    Address: {required: "Please Enter Address"},
    Zip: {required: "Please Enter Zip"},
    City: {required: "Please Enter City"},
    State: {required: "Please Enter State"},
    country: "Please Select Client's Country",
    CostOfService: {
        required: "Please enter cost of service",
        number: "Please enter valid price",
    },
    EmailInstruction: {required: "Please enter email instruction"},
    Email: {required: "Please Enter Email"},
    title: {required: "Please Enter Appointment Title"},
    password:  "Please Enter Your Password"},
    listofcatagory3: {required:"Please select Service Provider"},
    errorPlacement: function( label, element ) {
        if( element.attr( "name" ) === "EmailInstruction" ) {
            element.parent().append( label );
        }
        else if ( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed" || element.attr( "name" ) === "et" ) {
            element.parent().parent().append( label );
        } else if ( element.attr( "name" ) === "st" ){
            element.parent().parent().parent().append( label );
        }
        else {
            label.insertAfter( element );
        }
    }

    ,
    submitHandler: function() {

        $(".Loader").show();

                                        // alert(listofcatagory);
                                        var data = $("#NewEvent").serialize();

                                        var newEndTime = jQuery('input[name="st"]').val();

                                        // for (var item in data)
                                        // {
                                        //   if (data[item].name == 'et') {
                                        //     data[item].value = newEndTime;
                                        //   }
                                        // }

                                        // $.each(data, function(key, data)
                                        // {
                                        //     if (this.name == "et") 
                                        //         this.value="papa";
                                        // });

                                        data = data + "&LoginAction=Login";

                                        jQuery.ajax({
                                            dataType:"json",
                                            type:"post",
                                            data:data,
                                            url:'<?php echo EXEC; ?>Exec_Edit_Event',
                                            success: function(data)
                                            {
                                                if(data.resonse)
                                                {
                                                    console.log("ibnnn");

                                                    $("#resonse_service").show();
                                                    $('#resonsemsg_service').html('<span>'+data.resonse+'</span>');

                                                    setTimeout(function () {
                                                        //window.location.href = "AllEvent.php";
                                                        window.location.reload();
                                                    }
                                                    , 1000);


                                                    $( '#NewEvent' ).each(function(){
                                                        this.reset();
                                                    });

                                                    $(".Loader").hide();
                                                    var enewid = $("#id").val();
                                                    var googlesync = '<?php echo $googlesync; ?>';
                                                    if(enewid == "new" && googlesync==1)
                                                    {
                                                        window.location.href = "googlcel/home.php";
                                                    }
                                                    // var enewid = $("#id").val();
                                                    //             if(enewid == "new")
                                                    //             {
                                                    //           swal({
                                                    //               title: "Are you want save appointment data in google calendar",
                                                    //               text: "",
                                                    //               icon: "warning",
                                                    //               buttons: true,
                                                    //           }).then((willDelete)=>{   
                                                    //               if (willDelete){
                                                    //                 window.location.href = "googlcel/home.php";
                                                    //               }
                                                    //                else{
                                                    //                    return false ;
                                                    //                }
                                                    //           });
                                                    //         }
                                                    //  setTimeout(function () { window.location.href = "googlcel/home.php"; }, 3000)
                                                }
                                                else if(data.error)
                                                {
                                                    $("#error_service").show();
                                                    $('#errormsg_service').html('<span>'+data.error+'</span>');
                                                    $(".Loader").hide();
                                                    // alert('<li>'+data.error+'</li>');
                                                }
                                                else if(data.resonse_phone)
                                                {
                                                    swal('Appointment add but messages is not send because '+data.resonse_phone)
                                                    setTimeout(function () { window.location.reload() }, 2000);
                                                }
                                            }
                                        });
}
}
);
$("#eventstardate").change(function(){
    var service_star_time = $(this).val();
    console.log("set date" + service_star_time);
    $("#eventenddate").val(service_star_time);
}
);
$('#country').on('change',function(){
    $(".Loader").show();
    CountrysName=$(this).val();
    $.ajax({
        dataType:"json",
        type:"post",
        data: {'CountrysName':CountrysName}
        ,
        url:'?action=editfile',
        success: function(data)
        {
            if(data)
            {
                $('#State').html('');
                var i =0;
                $.each(data.resonse, function(k,v)
                {
                 $('#State').append('<option value="'+v.name+'">'+ v.name +'</option>');
             }
             );
                $(".Loader").hide();
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
    $(document).ready(function(){
        $('#update-client').on('click',function(){
            var cid='<?php echo base64_decode($_GET['id']); ?>'; 
            var FirstName=$('#FirstName').val();
            var LastName=$('#LastName').val();
            var Phone=$('#Phone').val();
            var Email=$('#Email').val();
            var Address=$('#Address').val();

            var Zip =$('#Zip').val();
            var City=$('#City').val();
            var State=$('#State').val();
            var country=$('#country').val();
                                    // $('#NewEvent').submit();
                                    $.ajax({
                                        dataType:"json",
                                        type:"post",
                                        data: {
                                            'cid':cid,'FirstName':FirstName,'LastName':LastName,'Phone':Phone,'Email':Email,'Address':Address,'Zip':Zip,'City':City,'State':State,'country':country}
                                            ,
                                            url:'?action=editfile',
                                            success: function(data)
                                            {
                                                if(data)
                                                {

                                                    $('#NewEvent').submit();
                                                }
                                                else if(data.error)
                                                {
                                                    alert('ok');
                                                }
                                            }
                                        }
                                        )
                                    // $('#NewEvent').submit();
                                }
                                );
    }
    );
</script>
<script type="text/javascript">
    function myFunction() {
        document.getElementById("myDropdown_cal").classList.toggle("show_cal");
    }
    function myFunction2() {
        document.getElementById("myDropdown_cal2").classList.toggle("show_cal");
    }
                            // Close the dropdown menu if the user clicks outside of it.show
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
                        </script>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click','.ClientLimit',function(){
                                    swal("Your Client Limit is over!!", "For Upgrade your limit contact to Admin", "warning");
                                }
                                );
                            }
                            );
                        </script>
                        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
                        <script type="text/javascript">
                            $(".fileNameclass").hide();
                            $("#drop-file-new").click(function(){
                                $('#addDocumentsModal').modal('toggle');
                                //$(this).css('background', '#D8F9D3');
                            });


                           /* $(document).on('mouseover','.card-body',function(){
                                var newfilenam = $(".dropify-filename-inner").text();
                                // alert(newfilenam);
                                if(newfilenam!='')
                                {
                                    $(".fileNameclass").show();
                                }
                                else if(newfilenam=='')
                                {
                                    $(".fileNameclass").hide();
                                }
                            }
                            );*/



                            
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
                            // $('#datepairExample .time').timepicker({
                            //     'showDuration': true,
                            //      'timeFormat': 'g:i a'  
                            // });


                            //  $('#datepairExample .eventTime').timepicker({
                            //     'showDuration': true,
                            //      'timeFormat': 'g:ia'  
                            // });

                            // $('#eventstartime').timepicki();


                         // $('#datepairExample .time').timepicker({
                         //    timeFormat: 'g:ia',
                         //    minTime: '11:45:00', // 11:45:00 AM,

                         //    startTime: new Date(0,0,0,15,0,0), // 3:00:00 PM - noon
                         //    interval: 15 // 15 minutes
                         //    });

                            // $('#datepairExample .date').datepicker({
                            //     'format': 'm-d-yyyy',
                            //     'autoclose': true
                            // }
                            //                                       );
                            // initialize datepair
         //                    $('#package_expire_date').datepair();

         //                     $('.date').datepicker({
                           //      'format': 'yyyy-m-d',
            //              'autoclose': true
                        // });
                        $('#datepairExample .date').datepicker({
                           format: "mm-dd-yyyy",
                        //format: "yyyy-mm-dd",

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
                                                                                $("#resonse_mail").show();
                                                                                $('#resonsemsg_mail').html('<span>'+data.resonse_mail+'</span>');
                                                                                $( '#NewMail' ).each(function(){
                                                                                    this.reset();
                                                                                }
                                                                                );
                                                                                $('#sendMailModal').modal('toggle');
                                                                                location.reload();
                                                                                $(".Loader").hide();
                                                    // setTimeout(function () { window.location.href = "FaqsList.php"; }, 5000)
                                                }
                                                else if(data.error_mail)
                                                {
                                                    $("#error_mail").show();
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
                                                // $("textarea").val(data.resonse.TextMassage);       
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
<!-- / For send mail -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<!-- For Send SMS -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#NewSms").validate({
            ignore: ":hidden:not(textarea)",
            rules: {
                smsTo: {
                    required: true,}
                    ,
                    smsMessage: {
                        required: true,}
                        ,
                    }
                    ,           
                    messages: {
                        smsTo: {
                            required: "Please select at least one recipient."}
                            ,
                            smsMessage: {
                                required: "Please enter sms decription"}
                                ,               
                            }
                            ,
                            errorPlacement: function( label, element ) {
                                if( element.attr( "name" ) === "smsMessage" ) {
                                    element.parent().append( label );
                                }
                                else {
                                    label.insertAfter( element );
                                }
                            }
                            ,
                            submitHandler: function() {
                                $(".Loader").show();
                                var data = $("#NewSms").serialize();
                                data= data ;
                                jQuery.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:data,
                                    url:'<?php echo EXEC; ?>Exec_Send_SMS',
                                    success: function(data)
                                    {

                                        if(data.resonse)
                                        {
                                            $("#resonse_sms").show();
                                            $('#resonsemsg_sms').html('<span>'+data.resonse+'</span>');
                                            $( '#NewSms' ).each(function(){
                                                this.reset();
                                            }
                                            );
                                            $('#sendSmsModal').modal('toggle');
                                            $(".Loader").hide();
                                            location.reload();
                                                    // setTimeout(function () { window.location.href = "FaqsList.php"; }, 5000)
                                                }
                                                else if(data.resonse_phone)
                                                {

                                                   $("#error_sms").show();
                                                   $('#errormsg_sms').html('<span>'+data.resonse_phone+'</span>');
                                                   $(".Loader").hide();
                                               }
                                               else if(data.error_twilo)
                                               {
                                                $("#error_sms").show();
                                                $('#errormsg_sms').html('<span>'+data.error_twilo+'</span>');
                                                $(".Loader").hide();
                                            }
                                        }
                                    }
                                    );
                            }
                        }
                        );

         $(document).on('click','#viewButtoninvoice',function(e){
            e.preventDefault();
            var encodedId = $(this).attr("data-id");
            var Orderid =window.atob(encodedId);
           
            /*var Orderids=$('#Orderids').val(Orderid);
            var orderdata=$(this).closest("tr").find("td:eq(2)").text();
            var orderinvoicenumber=$(this).closest("tr").find("td:eq(1)").text();
            /*var CustomerName=$(this).closest("tr").find("td:eq(0)").text();*/
            var  CustomerName = "<?php echo ucfirst($FirstName)." ".ucfirst($LastName)  ?>";
            $('#myModal_app').modal('show');
            $(".Loader").show();
            $.ajax({
                dataType:"json",
                type:"post",
                data: {
                    'Orderid':Orderid}
                    ,
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                        {
                            if(data.resonse_order)
                            {  
                                $("#CustomerMail_app"). text(data.resonse_order.email);
                                $(".CustomerMail1"). text(data.resonse_order.email);
                                var dat = moment(data.resonse_order.datecreated).format("MM-DD-YYYY");
                                $("#orderdata_app").text(dat);
                                $(".orderdata1").text(dat);
                                $("#orderinvoicenumber_app").text(data.resonse_order.InvoiceNumber);
                                $(".orderinvoicenumber1").text(data.resonse_order.InvoiceNumber);
                                CustomerName = CustomerName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                });
                                $("#CustomerName_app").text(CustomerName);
                                $(".CustomerName1").text(CustomerName);
                                $("#CustomerAdders_app").text(data.resonse_order.Address);
                                $(".CustomerAdders1").text(data.resonse_order.Address);
                                var CustomerAdders2= data.resonse_order.Country+','+ data.resonse_order.City+','+data.resonse_order.Zip
                                $("#CustomerAdders2_app").text(CustomerAdders2);
                                $(".CustomerAdders21").text(CustomerAdders2);
                                $("#Customerphone_app").text(data.resonse_order.Phone);
                                $(".Customerphone1").text(data.resonse_order.Phone);
                                $("#serivetoaltprice_app").text('$'+data.resonse_order.TotalseriveAmount);
                                $(".serivetoaltprice1").text('$'+data.resonse_order.TotalseriveAmount);
                                
                                $("#giftcardtotal_app").text('$'+data.resonse_order.TotalgiftAmount);
                                $(".giftcardtotal1").text('$'+data.resonse_order.TotalgiftAmount);

                                $("#salestax_app").text('$'+data.resonse_order.sales_tax.replace(" ",""));
                                $(".salestax1").text('$'+data.resonse_order.sales_tax.replace(" ",""));

                                $("#tips_app").text('$'+data.resonse_order.tips.replace(" ",""));
                                $(".tips1").text('$'+data.resonse_order.tips.replace(" ",""));

                                $("#producttotalprice_app").text('$'+data.resonse_order.TotalProductAmount);
                                $(".producttotalprice1").text('$'+data.resonse_order.TotalProductAmount);
                                $("#membershiptotalprice_app").text('$'+data.resonse_order.TotalMembershipAmount);
                                $("#userpoint_app").text('- $'+data.resonse_order.UsePoint);
                                $(".userpoint1").text('- $'+data.resonse_order.UsePoint);
                                $(".membershiptotalprice1").text('$'+data.resonse_order.TotalMembershipAmount);
                                $("#toatalprice_app").text('$'+data.resonse_order.TotalOrderAmount);
                                $(".toatalprice1").text('$'+data.resonse_order.TotalOrderAmount);
                            }
                            $('#carttable tbody').html('');
                            $('#carttable tbody').append('<tr id="order_popup" class="order_popup"><td>Item</td><td>Qty</td><td>Price</td><td>Discount</td><td> % </td><td>Total Price</td></tr>');
                            if(data.resonse_serive)
                            {
                                $.each(data.resonse_serive, function(k,v) 
                                {
                                    v.ServicePrice = v.ServicePrice.replace(" ","");
                                    v.ServiceFianlPrice = v.ServiceFianlPrice.replace(" ","");
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+v.ServiceName+'"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="ServicePrice" readonly value="'+v.ServicePrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+v.ServiceDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.ServiceDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.ServiceFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            if(data.allgiftcard)
                            {
                                $.each(data.allgiftcard, function(k,v) 
                                {
                                    v.gServiceFianlPrice = v.gServiceFianlPrice.replace(" ","");
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="Giftcard"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="ServicePrice" readonly value="'+v.gServicePrice+'"></td><td id=""><input type="text" name="" readonly id="" value="$'+v.gServiceDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.gServiceDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.gServiceFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            if(data.resonse_membership)
                            {
                                data.resonse_membership.MembershipPrice = data.resonse_membership.MembershipPrice.replace(" ","");
                                data.resonse_membership.MembershipFianlPrice = data.resonse_membership.MembershipFianlPrice.replace(" ","");
                                $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+data.resonse_membership.Name+'"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="Membership" readonly value="'+data.resonse_membership.MembershipPrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+data.resonse_membership.MembershipDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+data.resonse_membership.MemberDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+data.resonse_membership.MembershipFianlPrice+'"></td></tr>');
                            }
                            if(data.resonse_product)
                            {
                                $.each(data.resonse_product, function(k,v) 
                                {
                                    v.ProductDiscount = '$'+v.ProductDiscount;
                                    v.ProductFianlPrice = v.ProductFianlPrice.replace(" ",""); 
                                    v.ProductPrice = v.ProductPrice.replace(" ",""); 
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+v.ProductTitle+'"></td><td id=""><input type="text" class="" readonly value="'+v.ProdcutQuality+'"></td><td id=""><input type="text" name="Membership" readonly value="'+v.ProductPrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+v.ProductDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.ProductDiscountInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.ProductFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            
                            $(".Loader").hide();
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


        $(document).on('click','#viewButton',function(e){
            e.preventDefault();
            var encodedId = $(this).attr("data-id");
            var Orderid = window.atob($(this).attr("data-id"));
            
           
            var  CustomerName = "<?php echo ucfirst($FirstName)." ".ucfirst($LastName)  ?>";
            $('#myModal_order').modal('toggle');
            $(".Loader").show();
            $.ajax({
                dataType:"json",
                type:"post",
                data: {
                    'Orderid':Orderid}
                    ,
                    url:'?action=editfile',
                    success: function(data)
                    {    
                        if(data)
                        {
                            if(data.resonse_order)
                            {   
                                $("#CustomerMail"). text(data.resonse_order.email);
                                $(".CustomerMail1"). text(data.resonse_order.email);
                                var dat = moment(data.resonse_order.datecreated).format("MM-DD-YYYY");
                                $("#orderdata").text(dat);
                                $(".orderdata1").text(dat);
                                $("#orderinvoicenumber").text(data.resonse_order.InvoiceNumber);
                                $(".orderinvoicenumber1").text(data.resonse_order.InvoiceNumber);
                                CustomerName = CustomerName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                    return letter.toUpperCase();
                                });
                                $("#CustomerName").text(CustomerName);
                                $(".CustomerName1").text(CustomerName);
                                $("#CustomerAdders").text(data.resonse_order.Address);
                                $(".CustomerAdders1").text(data.resonse_order.Address);
                                var CustomerAdders2= data.resonse_order.Country+','+ data.resonse_order.City+','+data.resonse_order.Zip
                                $("#CustomerAdders2").text(CustomerAdders2);
                                $(".CustomerAdders21").text(CustomerAdders2);
                                $("#Customerphone").text(data.resonse_order.Phone);
                                $(".Customerphone1").text(data.resonse_order.Phone);
                                $("#serivetoaltprice").text('$'+data.resonse_order.TotalseriveAmount);
                                $(".serivetoaltprice1").text('$'+data.resonse_order.TotalseriveAmount);
                                
                                $("#giftcardtotal").text('$'+data.resonse_order.TotalgiftAmount);
                                $(".giftcardtotal1").text('$'+data.resonse_order.TotalgiftAmount);

                                $("#salestax").text('$'+data.resonse_order.sales_tax.replace(" ",""));
                                $(".salestax1").text('$'+data.resonse_order.sales_tax.replace(" ",""));

                                $("#tips").text('$'+data.resonse_order.tips.replace(" ",""));
                                $(".tips1").text('$'+data.resonse_order.tips.replace(" ",""));

                                $("#producttotalprice").text('$'+data.resonse_order.TotalProductAmount);
                                $(".producttotalprice1").text('$'+data.resonse_order.TotalProductAmount);
                                $("#membershiptotalprice").text('$'+data.resonse_order.TotalMembershipAmount);
                                $("#userpoint").text('- $'+data.resonse_order.UsePoint);
                                $(".userpoint1").text('- $'+data.resonse_order.UsePoint);
                                $(".membershiptotalprice1").text('$'+data.resonse_order.TotalMembershipAmount);
                                $("#toatalprice").text('$'+data.resonse_order.TotalOrderAmount);
                                $(".toatalprice1").text('$'+data.resonse_order.TotalOrderAmount);
                            }
                            $('#carttable tbody').html('');
                            $('#carttable tbody').append('<tr id="order_popup" class="order_popup"><td>Item</td><td>Qty</td><td>Price</td><td>Discount</td><td> % </td><td>Total Price</td></tr>');
                            if(data.resonse_serive)
                            {
                                $.each(data.resonse_serive, function(k,v) 
                                {
                                    v.ServicePrice = v.ServicePrice.replace(" ","");
                                    v.ServiceFianlPrice = v.ServiceFianlPrice.replace(" ","");
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+v.ServiceName+'"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="ServicePrice" readonly value="'+v.ServicePrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+v.ServiceDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.ServiceDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.ServiceFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            if(data.allgiftcard)
                            {
                                $.each(data.allgiftcard, function(k,v) 
                                {
                                    v.gServiceFianlPrice = v.gServiceFianlPrice.replace(" ","");
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="Giftcard"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="ServicePrice" readonly value="'+v.gServicePrice+'"></td><td id=""><input type="text" name="" readonly id="" value="$'+v.gServiceDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.gServiceDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.gServiceFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            if(data.resonse_membership)
                            {
                                data.resonse_membership.MembershipPrice = data.resonse_membership.MembershipPrice.replace(" ","");
                                data.resonse_membership.MembershipFianlPrice = data.resonse_membership.MembershipFianlPrice.replace(" ","");
                                $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+data.resonse_membership.Name+'"></td><td id=""><input type="text" class="" readonly value=" - "></td><td id=""><input type="text" name="Membership" readonly value="'+data.resonse_membership.MembershipPrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+data.resonse_membership.MembershipDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+data.resonse_membership.MemberDiscoutInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+data.resonse_membership.MembershipFianlPrice+'"></td></tr>');
                            }
                            if(data.resonse_product)
                            {
                                $.each(data.resonse_product, function(k,v) 
                                {
                                    v.ProductDiscount = '$'+v.ProductDiscount;
                                    v.ProductFianlPrice = v.ProductFianlPrice.replace(" ",""); 
                                    v.ProductPrice = v.ProductPrice.replace(" ",""); 
                                    $('#carttable tbody').append('<tr class="child order_popup" id=""><td><input type="text" readonly value="'+v.ProductTitle+'"></td><td id=""><input type="text" class="" readonly value="'+v.ProdcutQuality+'"></td><td id=""><input type="text" name="Membership" readonly value="'+v.ProductPrice+'"></td><td id=""><input type="text" name="" readonly id="" value="'+v.ProductDiscount+'"></td><td id=""><input type="text" readonly name="" class="dper" id="dper" value="'+v.ProductDiscountInParentage+'" placeholder="0.00%"></td><td id=""><input type="text" name="" id="" readonly value="'+v.ProductFianlPrice+'"></td></tr>');
                                }
                                );
                            }
                            $(".Loader").hide();
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
<!-- / For send SMS -->
<script src="
<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
<script>
    $(document).ready(function() {
                                // Basic
                                $('.dropify').dropify();
                                // Translated
                                $('.dropify-fr').dropify({
                                    messages: {
                                        default: 'Glissez-déposez un fichier ici ou cliquez',
                                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                                        remove: 'Supprimer',
                                        error: 'Désolé, le fichier trop volumineux'
                                    }
                                }
                                );
                                // Used events
                                var drEvent = $('#input-file-events').dropify();
                                drEvent.on('dropify.beforeClear', function(event, element) {
                                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                                }
                                );
                                drEvent.on('dropify.afterClear', function(event, element) {
                                    alert('File deleted');
                                }
                                );
                                drEvent.on('dropify.errors', function(event, element) {
                                    console.log('Has Errors');
                                }
                                );
                                var drDestroy = $('#input-file-to-destroy').dropify();
                                drDestroy = drDestroy.data('dropify')
                                $('#toggleDropify').on('click', function(e) {
                                    e.preventDefault();
                                    if (drDestroy.isDropified()) {
                                        drDestroy.destroy();
                                    }
                                    else {
                                        drDestroy.init();
                                    }
                                })
                                
                                // $(".dropify-clear").click(function(e){
                                //     e.preventDefault();
                                //     $(".dropify-preview").hide();
                                //     $(".dropify-filename-inner").text('');
                                //     $(".fileNameclass").hide();
                                // });

                                $(".dropify-clear").click(function(e){
                                    e.preventDefault();
                                    $(".dropify-preview").hide();
                                    var data1 = <?php echo @$ClientId; ?>
                                    
                                    jQuery.ajax({
                                        dataType:"json",
                                        url:'<?php echo EXEC; ?>exec-edit-profile.php?action5',
                                        type:"post",
                                        data:{"cimyData2":data1},
                                        success: function(data) 
                                        {
                                            if(data.resonse)
                                            {
                                                location.reload();
                                            }
                                            else if(data.error)
                                            {
                                                alert('somening worng')
                                            }
                                        }
                                    });
                                });


                            });

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
                                // uploadFormData(formImage);
                                $("#Submit2").click(function(event){
                                    event.preventDefault();
                                    var formImage = new FormData();
                                    var clinetid = '<?php echo $ClientId; ?>';
                                    formImage.append('clinetid',clinetid);
                                    formImage.append('id',clinetid);
                                    //console.log(image[0]);
                                    
                                    formImage.append('document', image);
                                    var fileName = $('#fileName').val();
                                    formImage.append('fileName',fileName);
                                    //console.log(formImage);
                                   // uploadFormData(formImage);
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
                                        $("#resonse_document").show();
                                        $('#resonsemsg_document').html('<span>'+data.resonse2+'</span>');
                                        $(".Loader").hide();
                                        //location.reload();
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
        //var cval = $(this).val();
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
                $("#resonse").show();
                $('#resonsemsg').html('<span>'+data.resonse+'</span>');                
                $('#addTagModel').hide();                
                // window.location.reload(); 
                setTimeout(function () { window.location.href = "CampaignsScriptclient.php?camex=attime"; }, 2000);
            }
            else if(data.error)
            {
                $("#error").show();
                $('#errormsg').html('<span>'+data.error+'</span>');
                $('#addTagModel').hide();                
                
            }
        },error:function(data){
            $(".Loader").hide();
        }
    });
    }
});


                     $(document).on('click','.editcustomer',function(event){
                       event.preventDefault();

                       $('#NewClient2')[0].reset();
                       $('.dropify-render').text('')
                       $('#clid').val('');
                       $('.dropify-filename-inner').text('')
                       $(".Loader").show();
                       event.preventDefault();
                       var customersid = $(this).val();
                       var customersid2 = atob(customersid)

                       $.ajax({
                          dataType:"json",
                          type:"post",
                          data: {'customersid2':customersid2},
                          url:'?action=editfile',
                          success: function(data)
                          {
                            if(data.resonse)
                            { 

                       // console.log(data.resonse.id)            
                       $('#FirstNameC').val(data.resonse.FirstName)
                       $('.mycid').val(data.resonse.id)
                       $('#LastNameC').val(data.resonse.LastName)
                       $('#phonenumber').val(data.resonse.Phone)
                       $('#example-email').val(data.resonse.email)
                       $('#autocomplete').val(data.resonse.Address)
                       $('#street_number').val(data.resonse.Address)
                       $('#postal_code').val(data.resonse.Zip)
                       $('#country').val(data.resonse.Country) 
                       $('#administrative_area_level_1').val(data.resonse.State)
                       $('#locality').val(data.resonse.City)
                       $('#oldimage').val(data.resonse.ProfileImg)
                       if(data.resonse.ProfileImg !== '')
                       {

                          $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
                          $('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
                          $('.dropify-filename-inner').text(data.resonse.ProfileImg)
                          
                      }
                      else if(data.resonse.ProfileImg =='')
                      {
                         $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
                         $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
                         $('.dropify-filename-inner').text('noimage.png')
                     }

                     $('#myModal_addclient').modal('show');
                     $(".Loader").hide();
                 }
                 else if(data.resonse==false)
                 {
                    $(".Loader").hide();
                    swal('No data found')

                }
            }
        })

                   });




                     $(document).on('click','.EditButton',function(event){
                       event.preventDefault();
                       $('#wdateshidden').val('');
                       $('#repeatdiv').hide();
                       $('#offf').trigger('click');
                       $('#eventdetailmodel').modal('hide');
                       $('.id').val('')
                       $(".Loader").show();
                       $('#clinetdetails').show();
                       var elink = $(this).attr('data-id');
                       $.ajax({
                        dataType:"json",
                        type:"post",
                        data:{'elink':elink},
                        url:'?action=deletefile',
                        success: function(data)
                        {
                            if(data.resonse)
                            { 
                              if(data.resonse.ProfileImg!='')
                              {
                                $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
                            }
                            else if(data.resonse.ProfileImg=='')
                            {
                                $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
                            }
                            var serpro = data.resonse.ServiceProvider;
                            $.ajax({
                                url: '?action=deletefile',
                                type: 'POST',
                                dataType: 'json',
                                data: {'serpro': serpro},
                                success: function(serpro){

                                 $('#myModal_exit2').find('h4').text('Book Appointment with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                               //$('#servicewith').text('Service with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                               $('#editspan').show();
                               $('#editspan').html('<b>Booked With:</b>'+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                           }
                       });

                            $('.editcustomer').val(btoa(data.resonse.cid));
                            $('.id').val(data.resonse.id);
                            $('#evnet_Location_radio').val(data.resonse.Location_radio);
                            $('#UserID').val(data.resonse.UserID);
                            $("#ServiceName").val(data.resonse.ServiceName);

                            $('#newlistofcatagory').val(data.resonse.ServiceName).trigger('change');
                            $("#title").val(data.resonse.title);
                            $("#cid").val(data.resonse.cid);
                            $('#FirstName').val(data.resonse.FirstName);
                            $('#LastName').val(data.resonse.LastName);
                            $('#Phone').val(data.resonse.Phone);
                            $('#Email').val(data.resonse.Email);
                            $('#Address').val(data.resonse.Address);
                            $('#Zip').val(data.resonse.Zip);
                            $('#City').val(data.resonse.City);
                            $('#State').val(data.resonse.State);
                            $('#newcountry').val(data.resonse.Country); 
                            $('#CostOfService').val(data.resonse.CostOfService);

                            $('#newname').html('<b>Name :</b>' + data.resonse.FirstName + ' ' + data.resonse.LastName);
                            $('#newphone').html('<b>Cell : </b>' + data.resonse.Phone);
                            $('#newemail').text(data.resonse.Email);

                            var arr = data.resonse.EventDate.split(' ');

              //console.log(arr);  
              //var dateAr = '2014-01-06'.split('-');
              var dateAr = arr[0].split('-');
              var newDate = dateAr[1] + '-' + dateAr[2] + '-' + dateAr[0];

                //console.log("nnnnnnnnnn" + newDate);
                $('#eventstardate').val(newDate);
            //$('#eventstardate').val(arr[0]);
            $('#eventstartime').val(arr[1]);

            var arr2 = data.resonse.end_date.split(' ');
            
            $('#eventenddate').val(arr2[0]);
            $('#eventendtime').val(arr2[1]);
            //console.log(arr2);
            $('#eventstatus').val(data.resonse.eventstatus);
            $('#ServiceProvider').val(data.resonse.ServiceProvider);
            $('#editServiceProvider').val(data.resonse.ServiceProvider);
                          // $('#EmailInstruction').val(data.resonse.EmailInstruction);
                          $('#EmailInstruction').data("wysihtml5").editor.setValue(data.resonse.EmailInstruction);
                          
                          if(data.resonse.Location_radio == 'Customer Location')
                          {

                              $(".locone2").prop("checked", true);
                              $(".locone").prop("checked", false);
                          }
                          else
                          {

                           $(".locone2").prop("checked", false);
                           $(".locone").prop("checked", true);

                       }

                       var curntuser='<?php echo $_SESSION['UserID']; ?>';
                       $.ajax({
                           dataType:"json",
                           type:"post",
                           data: {'curntuser':curntuser},
                           url:'?action=editfile',
                           success: function(data2)
                           {
                            if(data2)
                            {       
                             $('#newlistofclient').html("");
                                       //$('#newlistofclient').append('<option value="0">Select your Client</option>'); 
                                       $.each(data2.resonse, function( index, value ) {
                                          var sel ='';
                                          if(data.resonse.cid == value.id)
                                          {
                                            sel = 'selected="selected"';
                                        }
                                        $('#newlistofclient').append('<option '+ sel +' value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
                                    });
                                       $(".Loader").hide();
                                   }
                                   else if(data2.error)
                                   {
                                     $("#error").show();
                                     $('#errormsg').html('<span>'+data2.error+'</span>');
                                     $(".Loader").hide();
                                 } 
                             }
                         }); 

                       $(".Loader").hide();
                          // getsponserid(serpro)
                          $('#myModal_exit2').modal('toggle');
                      }
                      else if(data.error)
                      {
                          $(".Loader").hide();
                          swal('Something is wrong please try agine');
                      }
                  }
              });
});

$("#NewClient").validate({
    rules: {                
        FirstName: "required",
        LastName: "required",
        Phone: {required: true, },
        email: "required",
        Address: "required",
        Zip: "required",
        City: "required",
        State: "required",
        Country:"required",
                // Solution: "required",
                // PrivateNotes: "required",
                // SelectPackage: "required",
               //employeeSold: "required",
               // sd: {required: true,},
               // ed: {required: true,},

               
           },
           messages: {             
            FirstName:  "Please enter first name",
            LastName:  "Please enter last name",
            Phone: {required : "Please enter phone number",} ,
            email:  "Please enter an email",
            Address:  "Please enter address",
            Zip:  "Please enter zipcode",
            City:  "Please enter city",
            State:  "Please enter state",
                // Solution:"Please Enter Solution Strength",
                // PrivateNotes:"Please Enter Private Notes",
                // Country:"Please Select Country",
                // SelectPackage :"Please Select Package",
               //employeeSold: "Please Select Employee Who Sold",
               // sd:"Please Select Starting Date &nbsp&nbsp&nbsp&nbsp",
               // ed:"&nbsp&nbsp&nbsp&nbsp Please Select Ending Date",

           },submitHandler: function() {
            $(".Loader").show();
            var form = $('#NewClient')[0];
            var data = new FormData(form);
            //var data = $("#NewClient").serialize();
            jQuery.ajax({

             dataType:"json",
             type:"post",
             data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'<?php echo EXEC; ?>Exec_Edit_Client',
                    success: function(data)
                    {
                        if(data.resonse)
                        {

                            $("#resonse").show();
                            
                            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                            
                            $( '#NewClient' ).each(function(){
                               this.reset();
                           });

                            var radioValue = $("input[name='Location_radio']:checked").val();
                            $("#evnet_Location_radio").val(radioValue)
                            if(radioValue=='Salon Location')
                            {
                                $(".ServiceLocation").hide();
                            }

                            if($('#id').val() == 'new'){
                              $('#listofclient').append('<option value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
                              $('#listofclient').select2(); 
                              $('#newlistofclient').append('<option selected="selected" value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
                          }

                          $('#FirstName').val(data.mydata.FirstName);
                          $('#LastName').val(data.mydata.LastName);
                      $('#newlistofclient option:selected').text(data.mydata.FirstName + ' ' + data.mydata.LastName).select2().trigger('change'); // new
                      $('#newlistofclient').select2(); 
                      $('#Phone').val(data.mydata.Phone);
                      $('#Email').val(data.mydata.email);
                      $('#newemail').text(data.mydata.email);
                      $('#newname').html('<b>Name :</b>' + data.mydata.FirstName + ' ' + data.mydata.LastName);
                      $('#newphone').html('<b>Cell : </b>' + data.mydata.Phone);
                      $('#cid').val(data.mydata.id);

                      if(data.mydata.ProfileImg!='')
                      {
                         $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.mydata.ProfileImg);
                     }
                     else if(data.mydata.ProfileImg=='')
                     {
                        $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
                    }

                    if(radioValue=='Customer Location')
                    {     
                        var useradd = $('#listofcatagory3').val(); 
                        if(useradd==0)
                        {
                            var useradd= '<?php echo $_SESSION['UserID']; ?>';
                        }
                        $("#ServiceProvider").val(useradd);
                        $('#Address').val(data.mydata.Address);
                        $('#Zip').val(data.mydata.Zip);
                        $('#City').val(data.mydata.City);
                        $('#State').val(data.mydata.State);
                        // $('#country').val(data.resonse.Country).attr("selected", "selected");
                        $('#newcountry').val(data.resonse.Country);
                    }
                    else
                    {

                       var useradd = $('#listofcatagory3').val(); 
                       if(useradd==0)
                       {
                          var useradd= '<?php echo $_SESSION['UserID']; ?>';
                      }
                      $.ajax({

                          dataType:"json",
                          type:"post",
                          data: {'useradd':useradd},
                          url:'?action=editfile',
                          success: function(data3)
                          {
                              if(data3)
                              { 

                                $("#ServiceProvider").val(useradd);
                                $('#Address').val(data3.resonse.primaryaddress);
                                $('#Zip').val(data3.resonse.zipcode);
                                $('#City').val(data3.resonse.city);
                                $('#State').val(data3.resonse.state);
                                                // alert(data3.resonse.country);
                                               // $('#country').val(data3.resonse.Country).attr("selected", "selected");
                                               $('#newcountry').val(data3.resonse.country);
                                               
                                               $(".Loader").hide();
                                               
                                           }

                                       }

                                   });
                  }




                  $(".hidddeforfirst").show();
                  $(".exit-client-image").show();

                  $(".Loader").hide();
                  $('#myModal_addclient').modal('toggle');  

                  if($('#id').val() == 'new'){
                      $('#exit-client').trigger('click');
                      $('#listofclient').val(data.mydata.id).trigger('change');
                      $('#listofclient').select2(); 
                  }else{

                  }

                    // location.reload();
                }
                else if(data.error)
                {
                    $("#error").show();
                    
                    $('#errormsg').html('<span>'+data.error+'</span>');
                    
                    $(".Loader").hide();
                    $('#myModal').modal('toggle');
                // alert('<li>'+data.error+'</li>');
            }

            else if(data.csrf_error)
            {

                $("#csrf_error").show();
                $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                $(".Loader").hide();
                setTimeout(function () { window.location.reload() }, 2000)
            }
            
        }
    });

} 

});

$("#NewClient2").validate({
    rules: {                
        FirstName: "required",
        LastName: "required",
        Phone: {required: true,},
        email: "required",
        Address: "required",
        Zip: "required",
        City: "required",
        State: "required",
        Country:"required",
                // Solution: "required",
                // PrivateNotes: "required",
                // SelectPackage: "required",
                //employeeSold: "required",
                // sd: {required: true,},
                // ed: {required: true,},
            },
            messages: {             
                FirstName:  "Please enter firstName",
                LastName:  "Please enter lastName",
                Phone:  "Please enter phone number",
                email:  "Please enter  email",
                Address:  "Please enter address",
                Zip:  "Please enter zipcode",
                Country:"Please select country",
                City:  "Please enter city", 
                State:  "Please enter state",
                // Solution:"Please Enter Solution Strength",
                // PrivateNotes:"Please Enter Private Notes",
                // SelectPackage :"Please Select Package",
                //employeeSold: "Please Select Employee Who Sold",
                // sd:"Please Select Starting Date &nbsp&nbsp&nbsp&nbsp",
                // ed:"&nbsp&nbsp&nbsp&nbsp Please Select Ending Date",
            },
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
                    element.parent().parent().append( label );
                } else {
                   label.insertAfter( element );
               }
           },
           submitHandler: function() {
              $(".Loader").show();
              var form = $('#NewClient2')[0];
              var data = new FormData(form);

               //var data = $("#NewClient").serialize();
               
               jQuery.ajax({
                 dataType:"json",
                 type:"post",
                 data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'<?php echo EXEC; ?>Exec_Edit_Client',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                            $("#resonse").show();
                            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                            $( '#NewClient2' ).each(function(){
                               this.reset();
                           });
                            $(".Loader").hide();
                            //$("#myModal_addclient").toggle();
                            location.reload();
                        }
                        else if(data.error)
                        {
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            $(".Loader").hide();
                            setTimeout(function () { window.location.reload() }, 3000)
                // alert('<li>'+data.error+'</li>');
            }
            else if(data.csrf_error)
            {

                $("#csrf_error").show();
                $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                $(".Loader").hide();
                setTimeout(function () { window.location.reload() }, 2000)
            }
        }
        
    });
           }           
       });
$('#phonenumber').keyup(function(e){
    var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
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


<script src="<?php echo base_url; ?>/upload-and-crop-image/croppie.js"></script>
<script>  
    $(document).ready(function(){



      $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
      type:'circle' //circle or square
  },
  boundary:{
      width:300,
      height:300
  }
});

      $('#ProfileImg').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop.croppie('bind', {
            url: event.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
});

      $(document).on('click','#addcusomimagebutton',function(){
          $('#ProfileImg').trigger('click'); 
      });

      $('.crop_image').click(function(event){
        $('.dropify-render').text('')
        $('.dropify-filename-inner').text('')

        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
      }).then(function(response){
          $.ajax({
            url : "<?php echo base_url; ?>/upload-and-crop-image/upload.php",
            type: "POST",
            data:{"image": response},
            dataType:"json",
            success:function(data)
            {

          // $('#uploaded_image').html(data);
          $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/"+data.resonse+"");
          $('<img src="<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/'+data.resonse+'" id="pImage">').appendTo(".dropify-render");
          //$( ".dropify-render img" ).first().css( "display", "none" );
          $('.dropify-filename-inner').text(data.resonse)
          console.log("image" + data.resonse);
          $("#ProfileImg2").val(data.resonse)

          $('#uploadimageModal').modal('hide');
      }
  });
      })
  });


      $(function(){
          $('.dropdown-menu input[type="radio"]').click(function(){
            if ($(this).is(':checked'))
            {
              var myseletimage = $(this).val()
              var myseletimageurl = '<?php echo base_url; ?>/assets/ProfileImages/'+myseletimage
              
              if(myseletimageurl!='')
              {
                  $image_crop.croppie('bind', {
                    url: myseletimageurl
                }).then(function(){
                    console.log('jQuery bind complete');
                });
            }
            $('#uploadimageModal').modal('show');

        }
    });
      });

      $('#offrepeat').click(function() {
         $("#eventstardate").trigger('change');
     });

      $('#daily').click(function() {
          $("#eventstardate").trigger('change');
      });

      $('#weekly').click(function() {
          $("#eventstardate").trigger('change');
      });

      $('#monthly').click(function() {
          $("#eventstardate").trigger('change');
      });

      $('#yearly').click(function() {
          $("#eventstardate").trigger('change');
      });

      $('#every').on('change',function() {
          emptydata();
      });

      $('#wendate').change(function() {
          var service_star_time = $(this).val(); 
          $("#eventenddate").val(service_star_time);

      // emptying data

      $('.wdays').each(function() {
        $(this).removeClass('wactive');
    });
      $('#wdayshidden').val('');
      $('#wdateshidden').val('');
  });

      $('#mendate').on('change',function() {
          var service_star_time = $(this).val(); 
          $("#eventenddate").val(service_star_time);
          findmdates();
      });

      $('#mday').on('change',function(){
          findmdates();

      });

      function findmdates(){
          var start = $('#eventstardate').val();
          var end = $('#eventenddate').val();
          console.log("findmdate");
          var startDate = new Date(start);
          var endDate = new Date(end);
          var mday = $('#mday').val();
      // var totalSundays = 0;
      var str ='';
      for (var i = startDate; i <= endDate; ){
        if(parseInt(i.getDate()) == parseInt(mday)){
          var day = i.getDate();
          var month = i.getMonth()+1;
          var year = i.getFullYear();
          str = str+','+year + '-' + month + '-' + day;
      } 
      i.setTime(i.getTime() + 1000*60*60*24);
  }
  $('#wdateshidden').val(str);
}

$('#ymonth').on('change',function(){
  findydates();
});

$('#ydate').on('change',function(){
  findydates();
});

$('#yendate').on('change',function(){
 var service_star_time = $(this).val(); 
 $("#eventenddate").val(service_star_time);
 findydates();
});    

function findydates(){
  var start = $('#eventstardate').val();
  var end = $('#eventenddate').val();

  var startDate = new Date(start);
  var endDate = new Date(end);
  var ydate = $('#ydate').val();
  var ymonth = $('#ymonth').val();
      // var totalSundays = 0;
      var str ='';
      for (var i = startDate; i <= endDate; ){
        if(parseInt(i.getDate()) == parseInt(ydate) && i.getMonth()+1 == ymonth){
          var day = i.getDate();
          var month = i.getMonth()+1;
          var year = i.getFullYear();
          str = str+','+year + '-' + month + '-' + day;
      } 
      i.setTime(i.getTime() + 1000*60*60*24);
  }
  $('#wdateshidden').val(str);
}

$("#dendate").change(function(){
    var service_star_time = $(this).val(); 
    $("#eventenddate").val(service_star_time);

    var start = $('#eventstardate').val();
    var end = $('#eventenddate').val();

          // // end - start returns difference in milliseconds 
          // var startdt = new Date(start);
          // var enddt = new Date(end);
          // var millisecondsPerDay = 1000 * 60 * 60 * 24;

          // var millisBetween = enddt.getTime() - startdt.getTime();
          // var days = millisBetween / millisecondsPerDay;

          var startDate = new Date(start);
          var endDate = new Date(end);
          // var totalSundays = 0;
          var str ='';
          for (var i = startDate; i <= endDate; ){

              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              str = str+','+year + '-' + month + '-' + day;
              

              i.setTime(i.getTime() + 1000*60*60*24);
              
          }
          $('#wdateshidden').val(str);

          //alert(totalSundays);
      });

function emptydata(){
  $('#dendate').val('');
  $('#wendate').val('');
  $('#mendate').val('');
  $('#yendate').val('');
  $('.wdays').each(function() {
    $(this).removeClass('wactive');
});
  $('#wdayshidden').val('');
  $('#wdateshidden').val('');

}





});  
</script>


</body>
</html>