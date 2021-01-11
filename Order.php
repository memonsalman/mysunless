<?php 
require_once('global.php');
ob_start();

require_once('function.php');


if(empty($_SESSION["UserID"]) || empty($_SESSION["usertype"]) || $_SESSION["usertype"]=='Admin'){
  header("Location: index.php");die;
}
if(isset($_SESSION['UserID']))
{
  $id=$_SESSION['UserID'];
  $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  @$clientcreateprmistion=$result['ClientCreate'];
  $UsersLimit=$result['UsersLimit'];
  $ClientsLimit=$result['ClientsLimit'];
  $sid=$result['sid'];
  $usertype=$_SESSION['usertype'];
  $points=isset($result['points'])?$result['points']:0;
}

if(isset($_SESSION['UserID']))
{
    $userid=$_SESSION['UserID'];
    $checkstatus=$db->prepare("SELECT OrderCreate from users where id=:userid");
    $checkstatus->bindParam(':userid',$userid);
    $checkstatus->execute();
    $result=$checkstatus->fetch(PDO::FETCH_ASSOC);   
    @$orderpermission=$result['OrderCreate'];
    if($orderpermission!=1)
    {
      header("Location: index.php");die;
    }

}



if(isset($_GET["id"]))
{
  $MyCLient = base64_decode($_GET["id"]) ;
}
else
{
  $MyCLient = "new";
}   

$id=$_SESSION['UserID']; 

if($_SESSION['usertype']=='subscriber'){
    $id=$_SESSION['UserID']; 
    $query = $db->prepare("SELECT sales_tax FROM CompanyInformation WHERE createdfk=$id");
}else{
    $id=$_SESSION['adminid']; 
    $query = $db->prepare("SELECT sales_tax FROM CompanyInformation WHERE createdfk=$id");
}

$query->execute();
$res = $query->fetch(PDO::FETCH_ASSOC);
$sales_tax = $res['sales_tax'];

if(isset($_REQUEST['actionEdit']) && isset($_REQUEST['OrderId'])){


  $actionEDIT = $_POST['actionEdit'];
  $OrderID = $_POST['OrderId'];
  $decodeOrderId = base64_decode($OrderID);
  $OrderId = $decodeOrderId;

  $ordermaster= $db->prepare("SELECT OrderMaster.*,MemberPackage.Name, clients.FirstName, clients.LastName, clients.ProfileImg,clients.Phone,clients.email,clients.SelectPackage,OrderMaster.sales_tax  FROM `OrderMaster`
    LEFT JOIN clients ON OrderMaster.cid = clients.id
    LEFT JOIN MemberPackage ON clients.SelectPackage = MemberPackage.id
    WHERE OrderMaster.id=:myOrder "); 

  $ordermaster->bindParam(':myOrder', $decodeOrderId, PDO::PARAM_INT);
  $ordermaster->execute();
  $result_ordermaster = $ordermaster->fetch(PDO::FETCH_ASSOC);

  $ClientId = $result_ordermaster['cid'];

      
  @$allServiceDetail = array();
  if(!empty($result_ordermaster['ServiceName']))
  {
   $servicemastlist = explode(',', $result_ordermaster['ServiceName']);
   foreach ($servicemastlist as $svalue) 
   {
    $SeriveId=$svalue;

    $serdetail= $db->prepare("SELECT OrderServic.*,Service.ServiceName,Service.CommissionAmount FROM `OrderServic` JOIN Service on OrderServic.SeriveId=Service.id WHERE SeriveId=:SeriveId AND OrderId=:OrderId"); 

    $serdetail->bindParam(':SeriveId', $SeriveId, PDO::PARAM_STR);
    $serdetail->bindParam(':OrderId', $OrderId, PDO::PARAM_STR);
    $serdetail->execute();
    $result_serdetail = $serdetail->fetch(PDO::FETCH_ASSOC);  

    array_push($allServiceDetail, $result_serdetail);

            
  } 
}


@$allProductDetail = array();

if(!empty($result_ordermaster['ProdcutName']))
{
  $prodmastlist = explode(',', $result_ordermaster['ProdcutName']);
  foreach ($prodmastlist as $pvalue) 
  {

    $ProdcutId=$pvalue;
    $producdetail= $db->prepare("SELECT OrderProduct.*,Product.ProductTitle,Product.NoofPorduct,Product.CommissionAmount,Product.sales_tax FROM `OrderProduct` JOIN Product on OrderProduct.ProdcutId=Product.id WHERE ProdcutId=:ProdcutId AND OrderId=:OrderId"); 

    $producdetail->bindParam(':ProdcutId', $ProdcutId, PDO::PARAM_STR);
    $producdetail->bindParam(':OrderId', $OrderId, PDO::PARAM_STR);
    $producdetail->execute();
    $result_producdetail = $producdetail->fetch(PDO::FETCH_ASSOC);
    array_push($allProductDetail, $result_producdetail);

          

  }

}     


@$allMembershipDetail = array();

if(!empty($result_ordermaster['MembershipName']))
{
  $membmlist = explode(',', $result_ordermaster['MembershipName']);
  foreach ($membmlist as $mvalue) 
  {

    $MembershipId=$mvalue;
    $memdetail= $db->prepare("SELECT OrderMembership.*,MemberPackage.Name,MemberPackage.CommissionAmount FROM `OrderMembership` JOIN MemberPackage on OrderMembership.MembershipId=MemberPackage.id WHERE MembershipId=:MembershipId AND OrderId=:OrderId"); 

    $memdetail->bindParam(':MembershipId', $MembershipId, PDO::PARAM_STR);
    $memdetail->bindParam(':OrderId', $OrderId, PDO::PARAM_STR);
    $memdetail->execute();
    $result_memdetail = $memdetail->fetch(PDO::FETCH_ASSOC);  

       
    array_push($allMembershipDetail, $result_memdetail);



  }
}


@$allGiftCardDetail = array();
if(!empty($result_ordermaster['gServiceName']))
{

  $clientId = $result_ordermaster['cid'];
  $orderId = $result_ordermaster['id'];


  $giftdetail= $db->prepare("SELECT * FROM `Ordergift`  WHERE Cid=:clientId AND OrderId=:OrderId"); 

  $giftdetail->bindParam(':clientId', $clientId, PDO::PARAM_STR);
  $giftdetail->bindParam(':OrderId', $OrderId, PDO::PARAM_STR);
  $giftdetail->execute();
  $result_giftdetail = $giftdetail->fetchAll(PDO::FETCH_ASSOC); 

  foreach($result_giftdetail as $giftInfo){

    array_push($allGiftCardDetail, $giftInfo);


  } 
}



echo  json_encode(["serviceResponse"=>@$allServiceDetail,"productResponse"=>@$allProductDetail,"membershipResponse"=>@$allMembershipDetail,"giftResponse"=>@$allGiftCardDetail,"cid"=>@$ClientId,"resultOrderMaster"=>@$result_ordermaster]);die;



}



$stmt2=$db->prepare("SELECT clients.* FROM `clients` JOIN users ON clients.createdfk=users.id WHERE clients.isactive=1 and  clients.createdfk IN (SELECT DISTINCT(u2.id) from users u1 join users u2 join users u3 on u1.id=u2.id or u1.id=u2.adminid or u1.adminid=u2.adminid or u1.adminid=u2.id where u1.id=:id) GROUP BY clients.id order by clients.FirstName"); 

$stmt2->bindParam(':id', $id, PDO::PARAM_INT);
$stmt2->execute();
$result_event2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);


$stmt3=$db->prepare("SELECT sv.* from `Service` AS sv 
    JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) 
    WHERE sv.isactive=1 and sv.createdfk IN (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id or u1.sid=u2.id) 
    and (u2.id=u3.adminid or u2.id=u3.id or u2.id=u3.sid) where u1.id=:id GROUP by u3.id) GROUP BY sv.id");   

$stmt3->bindParam(':id', $id, PDO::PARAM_INT);
$stmt3->execute();
$result_event3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);



$stmt4=$db->prepare("SELECT Product.* FROM `Product` JOIN users ON Product.createdfk=users.id  WHERE Product.isarchive=1 and Product.createdfk IN (SELECT DISTINCT(u2.id) from users u1 join users u2 join users u3 on u1.id=u2.id or u1.id=u2.adminid or u1.adminid=u2.adminid or u1.adminid=u2.id where u1.id=:id) AND Product.NoofPorduct>='1' AND Product.isactive=1 GROUP BY Product.id"); 

$stmt4->bindParam(':id', $id, PDO::PARAM_INT);
$stmt4->execute();
$result_event4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);  


$stmt5=$db->prepare("SELECT MemberPackage.* FROM `MemberPackage` JOIN users ON MemberPackage.createdfk=users.id  WHERE MemberPackage.isactive=1 and MemberPackage.createdfk IN (SELECT DISTINCT(u2.id) from users u1 join users u2 join users u3 on u1.id=u2.id or u1.id=u2.adminid or u1.adminid=u2.adminid or u1.adminid=u2.id where u1.id=:id) AND MemberPackage.isactive=1 GROUP BY MemberPackage.id"); 

$stmt5->bindParam(':id', $id, PDO::PARAM_INT);
$stmt5->execute();
$result_even5 = $stmt5->fetchAll(PDO::FETCH_ASSOC); 

if(isset($_REQUEST['ClientsName']))
{
  $ClientsName=$_POST['ClientsName']; 


  $query = $db->prepare("SELECT id,Cid,OrderMembership.Noofvisit FROM OrderMembership where Cid=:ClientsName and (OrderMembership.package_expire_date >= NOW() or OrderMembership.package_expire_date = 'Never') AND (OrderMembership.Noofvisit > 0)");
  $query->bindValue(":ClientsName",$ClientsName);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_ASSOC);

  foreach ($results as $key => $result) {

    $cid = $result['Cid'];
    $Noofvisit = $result['Noofvisit'];
    $PackageId = $result['id'];


    if($Noofvisit!='Unlimited'){

      $query = $db->prepare("SELECT pservicepackage,pvisit FROM `OrderMaster` WHERE payment_status='CAPTURED' and cid=:cid and  FIND_IN_SET(:PackageId,pservicepackage)");
      $query->bindValue(":cid",$cid);
      $query->bindValue(":PackageId",$PackageId);
      $query->execute();
      $member=$query->fetchAll(PDO::FETCH_ASSOC);

      if($query->rowCount()>0){

        foreach ($member as $key => $value) {
          $id = explode(',',$value['pservicepackage']);
          $visit = explode(',',$value['pvisit']);

          foreach ($id as $i => $j) {
            if($j==$PackageId){
              $Noofvisit-=$visit[$i];
            }
          }
        }

      }
    }

    $queryupdate = $db->prepare("Update OrderMembership SET Noofvisit=:Noofvisit where id=:id");
    $queryupdate->bindValue(":Noofvisit",$Noofvisit);
    $queryupdate->bindValue(":id",$PackageId);
    $queryupdate->execute();

  }

  $eidtClient = $db->prepare("
    SELECT * from (
    (select clients.id as cid,clients.Phone,clients.FirstName,clients.LastName,clients.email,clients.ProfileImg from clients WHERE id=:ClientsName) t1
    left JOIN
    (select OrderMembership.Cid as oid, OrderMembership.*,MemberPackage.Name,OrderMembership.id as cpackagid, clients.giftcardbal from `OrderMembership` 
    JOIN MemberPackage ON OrderMembership.MembershipId=MemberPackage.id
    RIGHT JOIN clients ON OrderMembership.Cid=clients.id 
    WHERE clients.id=:ClientsName AND OrderMembership.Active='1' AND (OrderMembership.package_expire_date >= NOW() or OrderMembership.package_expire_date = 'Never') AND  (OrderMembership.Noofvisit > 0 or OrderMembership.Noofvisit = 'Unlimited') ) t2 on t1.cid=t2.oid
  )");

  $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
  $editfile=$eidtClient->execute();
  $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);

  $gbalance = $db->prepare("select TotalgiftAmount As bal, id from `OrderMaster` WHERE cid=:ClientsName AND payment_status='CAPTURED' AND gstatus='0' ");
  $gbalance->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
  $gbalanc=$gbalance->execute();
  while($gbal=$gbalance->fetch(PDO::FETCH_ASSOC))
  {
    @$gid .= $gbal['id']." ";  
    @$gibal += $gbal['bal'];
  }

  $giftbal = $db->prepare("select * from `totalgiftdata` WHERE cid=:ClientsName");
  $giftbal->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
  $giftbal->execute();
  $giftbaldata=$giftbal->fetch(PDO::FETCH_ASSOC);
  $gibal = $giftbaldata["totalgiftbal"] - $giftbaldata["usedbal"];

  if($editfile && $gbalanc)
  {
    echo  json_encode(["resonse"=>$all , "gibal"=>@$gibal ]);die;
  }
}

if(isset($_REQUEST['data']))
{
 $da = base64_decode($_REQUEST['data']); 
 $d = json_decode($da);
 $client= $d->client;
 $package= $d->package;
 $service= $d->service;
 $sname = $d->sname;
 $remain = $d->remain;
}



if(isset($_REQUEST['Servicename']))
{
 $Servicename=$_POST['Servicename']; 
 $eidtClient = $db->prepare("select Users,Price,CommissionAmount,Duration from `Service` where id=:Servicename");
 $eidtClient->bindValue(":Servicename",$Servicename);
 $editfile=$eidtClient->execute();
 $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

 if($all)
 {
  echo  json_encode(["resonse"=>$all]);die;
}
else
{
  echo  json_encode(["error_servie"=>'No data']);die; 
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
if(isset($_REQUEST['UserName']))
{


 $UserName=$_POST['UserName']; 
 $id=$_SESSION['UserID'];
 $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id IN ($UserName,$id)");
 $eidtUserName->bindValue(":UserName",$UserName,PDO::PARAM_STR);
 $editfile2=$eidtUserName->execute();
 $all2=$eidtUserName->fetchAll(PDO::FETCH_ASSOC);

 if($all2)
 {

  echo  json_encode(["resonse"=>$all2]);die;
}
else
{
  echo  json_encode(["errorrr"=>'Data not']);die;
}



}
if(isset($_REQUEST['Membershipname']))
{
 $Membershipname=$_POST['Membershipname']; 
 $eidtClient2 = $db->prepare("select Price,CommissionAmount,Noofvisit from `MemberPackage` where id=:Membershipname");
 $eidtClient2->bindValue(":Membershipname",$Membershipname,PDO::PARAM_STR);
 $editfile2=$eidtClient2->execute();
 $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
 if($editfile2)
 {
  echo  json_encode(["resonse"=>$all2]);die;
}
}
if(isset($_GET["id"])){
 $myevent = base64_decode($_GET['id']) ;
}else{
 $myevent = "new";
}
if(isset($_REQUEST['Orderid']))
{
 $Orderid=$_POST['Orderid']; 
 $eidtserivce = $db->prepare("select * from `OrderServic` JOIN clients ON OrderServic.Cid=clients.id JOIN Service ON Service.id=OrderServic.SeriveId WHERE OrderServic.OrderId=:Orderid");
 $eidtserivce->bindValue(":Orderid",$Orderid,PDO::PARAM_INT);
 $eidtserivcefile=$eidtserivce->execute();
 $all_serivce=$eidtserivce->fetch(PDO::FETCH_ASSOC);
 $Orderid=$_POST['Orderid']; 
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
  echo  json_encode(["resonse_serive"=>$all_serivce,"resonse_product"=>$all_prodcut,"resonse_membership"=>$all_membership,"resonse_order"=>$all_order]);die;
}
}


$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C42'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C43'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];

$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C44'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];


$button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C45'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$B4=$all_button4['button_name'];

$button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C46'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$B5=$all_button5['button_name'];

$button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C47'"); 
$button6->execute();
$all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
$B6=$all_button6['button_name'];


$button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C48'"); 
$button7->execute();
$all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
$B7=$all_button7['button_name'];

$button7e= $db->prepare("SELECT button_name FROM `ButtonSetting` where page_name='AllClients' AND button_id='C7'"); 
$button7e->execute();
$all_button7e = $button7->fetch(PDO::FETCH_ASSOC);
$B7e=$all_button7e['button_name'];


$button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C49'"); 
$button8->execute();
$all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
$B8=$all_button8['button_name'];


$button9= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C50'"); 
$button9->execute();
$all_button9 = $button9->fetch(PDO::FETCH_ASSOC);
$B9=$all_button9['button_name'];


$button10= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C51'"); 
$button10->execute();
$all_button10 = $button10->fetch(PDO::FETCH_ASSOC);
$B10=$all_button10['button_name'];

$button11= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C52'"); 
$button11->execute();
$all_button11 = $button11->fetch(PDO::FETCH_ASSOC);
$B11=$all_button11['button_name'];

$button12= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C53'"); 
$button12->execute();
$all_button12 = $button12->fetch(PDO::FETCH_ASSOC);
$B12=$all_button12['button_name'];

$button13= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C54'"); 
$button13->execute();
$all_button13 = $button13->fetch(PDO::FETCH_ASSOC);
$B13=$all_button13['button_name'];


$button14= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C55'"); 
$button14->execute();
$all_button14 = $button14->fetch(PDO::FETCH_ASSOC);
$B14=$all_button14['button_name'];

$button15= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C1'"); 
$button15->execute();
$all_button15 = $button15->fetch(PDO::FETCH_ASSOC);
$B15=$all_button15['button_name'];

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='8'"); 
$title1->execute();
$all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
$T1=$all_title1['TitleName'];

$button5e= $db->prepare("SELECT button_name FROM `ButtonSetting` where page_name='AllClients' AND button_id='C5'"); 
$button5e->execute();
$all_button5e = $button5e->fetch(PDO::FETCH_ASSOC);
$B5e=$all_button5e['button_name'];

$button6e= $db->prepare("SELECT button_name FROM `ButtonSetting` where page_name='AllClients' AND button_id='C6'"); 
$button6e->execute();
$all_button6e = $button6e->fetch(PDO::FETCH_ASSOC);
$B6e=$all_button6e['button_name'];

if(isset($_POST['CountrysName']))
{
  $CountrysName=$_POST['CountrysName']; 
  $eidtClient = $db->prepare("SELECT * FROM `countries`JOIN provinces ON countries.cid=provinces.country_id WHERE countries.countries_name=:CountrysName ORDER BY provinces.name");
  $eidtClient->bindValue(":CountrysName",$CountrysName,PDO::PARAM_STR);
  $editfile=$eidtClient->execute();
  $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);

  if($editfile)
  {
    echo  json_encode(["resonse"=>$all]);die;

  }
}

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

if(isset($_REQUEST['pakcagidc']))
{
  $pakcagidc = $_REQUEST['pakcagidc'];
  $cidddd = $_REQUEST['cidddd'];

  $eidtmembership = $db->prepare("select OrderMembership.*,MemberPackage.Name from `OrderMembership` JOIN MemberPackage ON OrderMembership.MembershipId=MemberPackage.id  WHERE Cid=:cidddd");

  $eidtmembership->bindValue(":cidddd",$cidddd,PDO::PARAM_INT);
  $eidtmembershipfile=$eidtmembership->execute();
  $all_membership5456456=$eidtmembership->fetchAll(PDO::FETCH_ASSOC);

  if($all_membership5456456)
  {
    echo  json_encode(["resonse"=>$all_membership5456456]);die;
  }

}


if(isset($_REQUEST['cardpaysetup'])){






  $userID = $_SESSION['UserID'];
  $isActive = 1;
  $selPaymentAPI = $db->prepare("select token from `paymentsetup` where UserID=:userid && isactive=:isActive");


  $selPaymentAPI->bindValue(":userid",$userID,PDO::PARAM_INT);
  $selPaymentAPI->bindValue(":isActive",$isActive,PDO::PARAM_INT);

  $selPaymentAPI->execute();
  $selPaySetup=$selPaymentAPI->fetch(PDO::FETCH_ASSOC);


  if(!empty($selPaySetup))
  {
    echo  json_encode(["response"=>'paymentsetup']);die;
  }
  else if(empty($selPaySetup))
  {
    $userID = $_SESSION['UserID'];

    $seladminId = $db->prepare("select adminid FROM `users` WHERE id=:adminId");
    $seladminId->bindValue(":adminId",$userID,PDO::PARAM_INT);
    $seladminId->execute();
    $selAdminId=$seladminId->fetch(PDO::FETCH_ASSOC);
    $finalSelAdminId = $selAdminId['adminid'];




    $isActive = 1;
    $sel2PaymentAPI = $db->prepare("select token from `paymentsetup` where UserID=:finalSelAdminId && isactive=:isActive");



    $sel2PaymentAPI->bindValue(":finalSelAdminId",$finalSelAdminId,PDO::PARAM_INT);
    $sel2PaymentAPI->bindValue(":isActive",$isActive,PDO::PARAM_INT);

    $sel2PaymentAPI->execute();
    $sel2PaySetup=$sel2PaymentAPI->fetch(PDO::FETCH_ASSOC);


    if(!empty($sel2PaySetup))
    {
      echo  json_encode(["response"=>'paymentsetup']);die;
    }
    else{
      echo  json_encode(["error"=>'No Data found']);die;

    }

  }
  else
  {
    echo  json_encode(["error"=>'No Data found']);die;

  }

}


if(isset($_REQUEST['pakcagidc2']))
{
  $PackageId = $_REQUEST['pakcagidc2'];

  $query = $db->prepare("SELECT OrderMembership.Noofvisit,MembershipId FROM OrderMembership where id=:PackageId");
  $query->bindValue(":PackageId",$PackageId,PDO::PARAM_INT);
  $query->execute();
  $result=$query->fetch(PDO::FETCH_ASSOC);

  $Noofvisit = $result['Noofvisit'];
  $MembershipId = $result['MembershipId'];

  $membership['MembershipId'] = $MembershipId;
  $membership['Noofvisit'] = $Noofvisit;

  $prepareakcagidc2 = $membership['MembershipId'];

  $eidtmembership2 = $db->prepare("select service from `MemberPackage` WHERE id=:pakcagidc2");
  $eidtmembership2->bindValue(":pakcagidc2",$prepareakcagidc2,PDO::PARAM_INT);
  $eidtmembershipfile2=$eidtmembership2->execute();
  $all_membership54564562=$eidtmembership2->fetch(PDO::FETCH_ASSOC);
  $listofservice = $all_membership54564562['service'];


  $eidtmembership3 = $db->prepare("select ServiceName,id from `Service` where FIND_IN_SET(id,:listofservice) ");
  $eidtmembership3->bindValue(":listofservice",$listofservice);
  $eidtmembershipfile3=$eidtmembership3->execute();
  $all_membership54564563=$eidtmembership3->fetchAll(PDO::FETCH_ASSOC);

  if(isset($all_membership54564563))
  {
    echo  json_encode(["resonse"=>$all_membership54564562,"resonse2"=>@$all_membership54564563,"resonse3"=>$membership]);die;
  }
  else
  {
    echo  json_encode(["resonse2"=>"service not available"]);die; 
  }  

}


if(isset($_REQUEST['Remainingcountat']))
{
  $Remainingcountat = $_REQUEST['Remainingcountat'];
  $id = $_REQUEST['memboackja'];
  $Update_stockmemoe=$db->prepare("UPDATE OrderMembership SET Noofvisit=:Remainingcountat WHERE id=:id");
  $Update_stockmemoe->bindparam(":id",$id);
  $Update_stockmemoe->bindparam(":Remainingcountat",$Remainingcountat);
  $Update_stockmemoe->execute();   

  if($Update_stockmemoe)
  { 
    echo json_encode(["resonse"=>'Your package details has been updated']);die;       
  }

}

if(isset($_REQUEST['memboackja_first']))
{
  $memboackja_first = $_REQUEST['memboackja_first'];

  $eidtmembership2_t = $db->prepare("select Noofvisit from `OrderMembership` WHERE id=:memboackja_first");
  $eidtmembership2_t->bindValue(":memboackja_first",$memboackja_first,PDO::PARAM_INT);
  $eidtmembershipfile2_t=$eidtmembership2_t->execute();
  $all_membership54564562_t=$eidtmembership2_t->fetch(PDO::FETCH_ASSOC);
  $orinalvisti = $all_membership54564562_t['Noofvisit'];
  if($orinalvisti=="Unlimited"){
    $orinalvisti = 1;
  }
  echo  json_encode(["resonse"=>$orinalvisti]);die;        
}


if($_SESSION['usertype']!='subscriber'){
  $payID = $_SESSION['adminid'];
}else{
  $payID = $_SESSION['UserID'];
}

$stmt= $db->prepare("SELECT * FROM `paymentsetup2` WHERE UserID =".$payID." "); 
$stmt->execute();
$paymentsetup2 = $stmt->fetch();


$stmt= $db->prepare("SELECT * FROM `paymentsetup` WHERE UserID =".$payID." "); 
$stmt->execute();
$paymentsetup = $stmt->fetch();

?>

<!DOCTYPE html>

<html lang="en" >
<?php
include 'head.php';
include $_SERVER["DOCUMENT_ROOT"].$SUB.'/php_square_payment/square_payment.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/jquery.timepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo  base_url; ?>/assets/css/custom.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">
<style type="text/css">

  .ui-timepicker-wrapper{width: 465px!important;}

  #carttable input,#carttable2 input {
    padding: 3px;
  }

  input#Casesubmit,input#Chequesubmit,button#sq-creditcard{
    background-color: #03a9f3!important;
    border-color: #03a9f3!important;
  }

  button#sq-creditcard:hover {
    background-color: #038fcd!important;
    border-color: #0286c1!important;
  }

/*  @media (min-width: 768px) {
    .modal-dialog {
      width: 600px;
      margin: 30px auto;
    }
  }*/

/*  @media (min-width: 992px) {
    .modal-lg {
      width: 900px;
    }
  }
  @media (min-width: 768px) {
    .modal-xl {
      width: 90%;
      max-width:1200px;
    }
  }

  .customersdetalisone{
    width: 100%!important;
  }
  .customersdetalistwo{
    width: 46% !important;
  }
  .customersdetalistree{
    width: 46% !important;
  }*/


input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
-webkit-appearance: none;
margin: 0;
}
input[type="number"] {
-moz-appearance: textfield;
}

  select#listoftips {
    width: 100%;
    margin: 0;
    height: 25px;
  }
  input#mytips,input#mytipsper{width: 27%;margin:5px;}

  .qty .plus, .qty .minus {
    font-size: 36px;
    vertical-align: bottom;
    border-radius: 50%;
    cursor: pointer;
    user-select: none;
    margin: 5px;
  }
  .qty .plus{
    color:#4caf50;
  }
  .qty .plus:hover{
    color: #468a49;
  }
  .qty .minus{
    color:#F44336;
  }
  .qty .minus:hover{
    color: #d43024;
  }
  .qty2 .plus2 {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 35px;
    height: 35px;
    font: 34px/1 Arial,sans-serif;
    text-align: center;
    
  }
  .qty2 .minus2 {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 35px;
    height: 35px;
    font: 35px/1 Arial,sans-serif;
    text-align: center;
    
    background-clip: padding-box;
  }
  .listofcatagory_price{
    position: relative;
  }
  .ProductTaxPrice{
    width: 65px;
    position: absolute;
    right: 1px;
    background: #31b131;
    color: white;
    text-align: center;
  }
  .SelectPackageliv {
    max-height: 130px;
    overflow-y: scroll;
}

  .NewOrder::-webkit-scrollbar, .sumtagaee::-webkit-scrollbar {
    width: 10px;
    height: 10px;
  }
  .NewOrder::-webkit-scrollbar-thumb, .sumtagaee::-webkit-scrollbar-thumb {
    background: #3cabe1;
    border-radius: 8px;
  }

  @media only screen and (max-width: 1000px) and (min-width: 768px)  
  {
    
    select#listoftips{width: 100% !important; margin:0 !important;}
    
  }
  .order-client .col-md-3:nth-child(2){
    border-right: 1px solid #a6a9b3;
    border-left: 1px solid #a6a9b3;
  }

  .order-client .col-md-5{
    border-right: 1px solid #a6a9b3;
  }
  .order_checkout .pull-right{
    float: right!important;
  }
  .order_popup td input{
    border: 1px solid #b5b5b5!important;
  }
</style>
<style type="text/css">

    .radio { 
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }


    .radio + img {
        cursor: pointer;
    }


    .radio:checked + img {
        outline: 2px solid #f00;
    }

    span.count3{background: #03a9f3;
        padding: 7px;
        color: white;
        border-radius: 3px;

        display: none;}

    </style>
    <body class="skin-default fixed-layout mysunlessI">
     <!-- ==============================================================  -->
     <!-- Preloader - style you can find in spinners.css -->
     <!-- ============================================================== -->
     <div class="preloader">
      <div class="loader">
       <div class="loader__figure"></div>
       <p class="loader__label"><?php echo $_SESSION['UserName']; ?></p>
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
      <?php
      if(isset($_GET["id"]))
      {
       ?>
       <h4 class="text-themecolor"><?php echo $T1 ?></h4>
       <?php       
   }
   else
   {
       ?>
       <h4 class="text-themecolor"><?php echo $T1 ?></h4> 
       <?php
   }
   ?>

</div>
<div class="col-md-7 align-self-center text-right">
  <div class="d-flex justify-content-end align-items-center">
  </div>
</div>
</div>
<div class="row">
 <div class="col-lg-9">
  <div class="card">
   <div class="card-body">
    
     <div class="col-lg-12">
      <div class="col-lg-9 pull-left selecclint">
       <div class="form-group">
        <label><span class="help btst">Select Client</span></label>
        <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Client" id="listofcatagory" name="listofcatagory">
         <option value="">Select Client</option>
         <?php 
         foreach($result_event2 as $row2)
         {
          ?>
          <option value="<?php echo $row2['id']; ?>"><?php echo $row2['FirstName'].' '.$row2['LastName']; ?></option>
          <?php
      }
      ?>
  </select>
  <span style="color: red" id="listofcatagory_error"></span>
</div>
</div>
<div class="col-lg-3 pull-left addnewclinet">


   <a href=" " data-toggle="modal" data-target="#myModal_viewclient" class="btn btn-info m-r-10 " id="newclient_1"> <i class="fa fa-user-plus"></i><?= $B15 ?></a>
   <button class="btn btn-warning btn-sm EditInfo" id="editcustomer" style="display:none!important" >Edit Profile</button>
</div>
</div>

<?php require_once('viewclientdetail.php');?>

<!-- <div class="hidddeforfirst" id="hidddeforfirst">
  <img class="img-responsive order-img" src="" id="clientimage" height="100px" width="100px"> 
  <span id="FirstName"></span>
  <span id="LastName"></span>
  <div class="order-client">
   <div class="row">
    <div class="col-md-2 col-sm-2 col-xs-2">
     <ul>
      <li>Mobile no</li>
      <li><span id="Phone"></span></li>
  </ul>  
</div>
<div class="col-md-3 col-sm-3 col-xs-3">
 <ul>
  <li>Email id</li>
  <li><span id="Email"></span></li>
</ul>
</div>
<div class="col-md-5 col-sm-5 col-xs-5">
 <ul>
  <li>Select Package</li>
  <li class="SelectPackageliv">

  </li>
</ul>
</div>
<div class="col-md-2 col-sm-2 col-xs-2">
 <ul>
  <li>GiftCard Balance </li>
  <li><span id="giftbal" ></span></li>
</ul>  
</div>
</div> 
</div>
</div> -->

<div class="hidddeforfirst row" id="hidddeforfirst" style="padding: 10px">

  <div class="col-md-6 row m-0">
   <div class="col-md-4">
    <img class="img-responsive order-img viewInfo" src="" id="clientimage" data-cid=""> 
  </div>
  <div class="col-md-8">
   <span id="FirstName"></span>
   <span id="LastName"></span>
   <br><i class="fa fa-envelope mr-1" aria-hidden="true"></i><span id="Email"></span>
   <br><i class="fa fa-phone-square mr-1" aria-hidden="true"></i><span id="Phone"></span>
   <br><i class="fa fa-gift mr-1" aria-hidden="true"></i><span id="giftbal"></span>
 </div>


</div>

<div class="col-md-6">
  <h3><i class="fas fa-box-open mr-2"></i>Package</h3>
  <!-- <div class="SelectPackageliv"></div> -->
   <select class="select2 SelectPackageliv" style="width: 100%" data-placeholder="Choose Package" name="SelectPackageliv">
   </select>
</div>

</div>

</form>      



<div class="modal fade" id="myModal_packagedetalsisfs" role="dialog">
 <div class="modal-dialog">
  <div class="Loader"></div>

  <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title">Package details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">

  <div class="form-group">
   Package name : 
   <span class="sselectedpackagename"></span>
</div>

<div class="form-group">
   <label><span class="help">Remaining Visit</span> -  <span class="count3"></span></label>
   <div class="qty2">

    <div class="form-group">    
     <input type="text" class="count2 form-control" style="width: 80%" name="qty2" value="0" name="test" min=1 oninput="validity.valid||(value='');">
     <span class="plus2 bg-dark">+</span>
     <span class="minus2 bg-dark">-</span>
 </div>
</div>
</div>
<div class="form-group">
   <label><span class="help">Select your service</span></label>
   <select class="select2 m-b-10 select2-multiple listofavliaasfpackag2" style="width: 100%"  data-placeholder="Choose service" id="listofavliaasfpackag2" name="listofavliaasfpackag2">
   </select>
</div>

<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add_memberships_save_order2"><i class="fa fa-shopping-cart"></i> Save</button>
<input type="hidden" name="count4" class="count4">
</div>
</div>
</div>
</div>
<!-- 
<div class="modal fade" id="myModal_viewclient" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 1100px;">

    <div class="modal-content">
      <form class="form-horizontal" action="" autocomplete="off" method="post" id="NewClient">
        <div class="modal-header">
          <h4 class="modal-title">Customer Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="Loader"></div>
          <input type="hidden" name="id" id="id" value="new">
          <input type="hidden" name="clinetid" id="" value="">
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
          <div class="customersdetalisone">
            <div class="form-group">
              <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
              <div class="card">
                <div class="card-body">
                  <input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-allowed-file-extensions='["png", "jpg","jpeg"]' >
                  <input type="hidden" name="ProfileImg"id="oldimage" value="">
                  <input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
                  <input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
                </div>
              </div>
            </div>

                       <button type="button" class="btn btn-default" id="addcusomimagebutton" style="width: 100%; margin-bottom: 20px;"> <?php echo $B5e; ?></button>
                       <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%;margin-bottom: 20px;"> <?php echo $B6e; ?><span class="glyphicon glyphicon-chevron-down"></span></button>


                       <div class="dropdown-menu" style="width: 96%;">
                         <?php
                         $stmta= $db->prepare("SELECT * FROM `listofavtar`"); 
                         $stmta->execute();
                         $stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
                         foreach($stmtall as $row)
                         {
                          ?>
                          <label style="padding: 5px;">
                           <input type="radio" class="radio" name="ProfileImg" value="<?php echo $row['Name']; ?>">
                           <img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px">
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
              <input type="text" autocomplete="nope"  name="Phone" id="phonenumber"  class="form-control" value="" placeholder="1234567890">
          </div>

          <div class="form-group">
              <label for="example-email">Email * <span class="help"></span></label>
              <input type="email" id="example-email" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com" maxlength="30">
          </div>
      </div>

      <div class="customersdetalistree">
         <div class="form-group">
          <label for="example-email">Street Address *</label>
          <input autocomplete="nope" id="autocomplete"  placeholder="Enter your address"  class="form-control" name="Address" type="text" value=""></input>
          <input type="hidden" value="" placeholder="Enter your address"   id="street_number" disabled="true"></input>
          <input type="hidden" value="" placeholder="Enter your address" id="route" disabled="true"></input>  
      </div>



      <div class="form-group">
          <label for="country">Country *</label>

          <input type="text" disabled="" class="form-control" value="United States">
      </div>

      <div class="form-group">
         <label><span class="help">State *</span></label>
         <select class="form-control" id="administrative_area_level_1" autocomplete="nope" name="State">
            <option value="">Select a State</option>
            <?php
            $newstate = "Alabama,Alaska,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,Florida,Georgia,Hawaii,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana,Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Tennessee,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming";
            $stateList = explode(',', $newstate);
            foreach($stateList as $value)
            {
               echo "<option value='".$value."'>".$value."</option>";
           }

           ?>
       </select>
   </div>

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
    <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-client" id="add-client"><i class="fa fa-check"></i> Save Customer </button>
</div>
</div>

</div>

</div>
</div>


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
            <button type="button" class="btn btn-default crop_image" data-dismiss="modal"> Skip </button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->



<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Select Service</h4>
        <button type="button" class="serviceform close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div  class="Loader"></div>

                <form class="form-horizontal form-material" autocomplete="off" id="Newservice" method="post">
                  <div class="form-group"> 
                    <label><span class="help"> Service *</span></label> 
                    <select class="select2 m-b-10 selectservice select2-multiple listofcatagory2" style="width: 100%" data-placeholder="Choose Service" id="listofcatagory2" name="listofcatagory2">
                      <option value="">Select Client</option>
                      <?php 
                      foreach($result_event3 as $row2)
                        {?>
                          <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label><span class="help">Service Provider *</span></label>
                      <select class="select2 m-b-10 selectservice select2-multiple" style="width: 100%"  data-placeholder="Choose Service Provider" id="listofcatagory3" name="listofcatagory3">
                        <option value="">Select Provider</option>
                      </select>
                      <input type="hidden" name="sprice" id="sprice">
                      <input type="hidden" name="sCommissionAmount" id="sCommissionAmount">
                    </div>
                    <div class="form-group">
                      <label for="example-email">Start Time  *<span class="help"></span></label>
                      <div class="form-group">    
                        <input id="scrollDefaultExample" name="ServiceTime" type="text" class="form-control" autocomplete="off" value="" placeholder="Select start time">
                      </div>    
                    </div>
                    <!-- <div class="form-group">
                      <label for="example-email">End Time  *<span class="help"></span></label>
                      <div class="form-group">    
                        <input id="Duration" name="scrollDefaultExample2" type="text" class="form-control time ui-timepicker-input" autocomplete="off" value="" placeholder="Select end time">
                      </div>    
                    </div> -->
                    <div style="display: inline;">
                      <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add_service"><i class="fa fa-shopping-cart"></i> ADD TO CART</button>
                      <a href="<?= base_url?>/viewService" class="btn btn-info m-r-10 "> <i class="fa fa-user-plus"></i><?= $B8 ?></a>
                      
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="myModal-Prodcut" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Select Product</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">

            <form class="form-horizontal form-material" autocomplete="off" id="Newproduct" method="post">

              <table id="product_table" class="table table-bordered table-striped dataTable no-footer">
                <thead>
                  <tr>
                    <td></td>
                    <td>Barcode</td>
                    <td>Product Name</td>

                    <td>Selling Price</td>
                    <td>Sales tax</td>
                    <td>Qty Available</td>  
                  </tr>
                </thead> 
                <tbody>

                  <?php
                  foreach($result_event4 as $row2)
                                {
                                    $pid =  $row2['id'];  
                                   if($row2['sales_tax']==1){
                                    $row2['onlytax'] = $sales_tax;
                                   }
                                    ?>
                                    <tr>
                                      <td>
                                       <input type="checkbox" name="listproduct" id="listproduct<?= $pid ; ?>" value="<?= $pid ; ?>" data-CommissionAmount="<?php echo $row2['CommissionAmount']; ?>" data-product="<?php echo $row2['ProductTitle']; ?>" data-SellingPrice="<?php echo $row2['SellingPrice']; ?>" data-NoofPorduct="<?php echo $row2['NoofPorduct']; ?>" data-sales_tax="<?php echo $row2['sales_tax']; ?>" data-stock="<?php echo $row2['NoofPorduct']; ?>" data-ProductCostPrice="<?php echo $row2['CompanyCost']; ?>"></td>
                                       <td class="barcode"><?php echo $row2['barcode']; ?></td>
                                       <td class="ProductTitle"><?php echo $row2['ProductTitle']; ?></td>

                                       <td class="SellingPrice"><?php echo '$'.$row2['SellingPrice']; ?></td>

                                       <td class="salestax"><?php echo round($row2['onlytax'],2).'%'; ?></td> 

                                       <td class="NoofPorduct"><?php echo $row2['NoofPorduct']; ?></td>
                                   </tr>
                               <?php } ?>
                           </tbody>
                       </table>
                       <span style="color: red" id="listofcatagoryprodcut_error"></span>
                       <div style="padding: 25px 0;"></div>
                       <div style="display: inline;" >
                        <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add_prodcuts"><i class="fa fa-shopping-cart"></i> ADD TO CART</button>
                        <?php
                        echo ' <a href=" '.base_url .'/AllProduct" class="btn btn-info m-r-10 "> <i class="fa fa-user-plus"></i> ' . $B9.'</a>';
                        ?>   
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal-Membership" role="dialog">
   <div class="modal-dialog">

    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title">Select Package</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="Loader"></div>
  <div class="modal-body">
      <form class="form-horizontal" autocomplete="off" id="Newmembership" method="post">

       <table>
        <tr>
         <td><label>Recipient's Name : <label></td>
          <td><span id="reciptname" class="reciptname"></span></td>
      </tr>

      <tr>
          <td>
           <label><span class="help">Package *</span></label> 
       </td>
       <td>
           <div class="form-group"> 

            <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Package" id="listofcatagory4" name="listofcatagory4">
             <option value="">Select Package Name</option>
             <?php 
             foreach($result_even5 as $row2)
              {?>
               <option  value="<?php echo $row2['id']; ?>"><?php echo $row2['Name']; ?></option>
               <?php
           }
           ?>
       </select>



       <input type="hidden" name="mprice" id="mprice">
       <input type="hidden" name="mCommissionAmount" id="mCommissionAmount">
   </div>     


</td>


</tr>

<tr>
   <td><span> Amount : </span></td>
   <td>  
    <div class="form-group">    
     <input type="text" class="PckageAmount form-control" name="PckageAmount" value="" id="PckageAmount" data-packageprice="" >
 </div>
</td>
</tr>


<tr>
   <td><span> # of visits : </span></td>
   <td>  <div class="qty">
    <label style="display: none;" class="unlimitedlabel">Unlimied</label>
    
    <div class="form-group" style="display: flex;">    
     <input type="text" class="form-control count" name="qty" value="1" name="test" min=1 oninput="validity.valid||(value='');" style="width: 68%;">
     <i class="fa fa-plus-circle plus" aria-hidden="true"></i>
     <i class="fa fa-minus-circle minus" aria-hidden="true"></i>

 </div>
</div></td>
</tr>

<tr style="display: none;" >
  <td><span> Auto Renew : </span></td>
  <td>  
    <input type="checkbox" class="Package_Autonew" id="Package_Autonew" name="Package_Autonew" value="yes" >
</td>
</tr> 



<tr class="Renewaltr" style="display: none;">
 <td><span> Renewal on : </span></td>
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
</tr> 

<tr>
 <td><span> Expires on: </span></td>
 <td> 
  <div class="form-group">     
   <input type="text" class="date start form-control" placeholder="Start Date" name="package_expire_date" autocomplete="nope" id="package_expire_date" value="Never" aria-invalid="false">
</div>
</td>
</tr> 





</table>
<div>
 <button type="submit" class="btn waves-effect waves-light btn-info m-1" id="add_memberships"><i class="fa fa-shopping-cart"></i> ADD TO CART</button> 
 <?php 
 echo ' <a href=" '.base_url .'/MembershipPackageList" class="btn btn-info m-1 "> <i class="fa fa-user-plus"></i> ' . $B10.'</a>';
 ?> 
</div>
</form>  
</div>
</div>
</div>
</div>




<div class="modal fade" id="modalgift" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Gift Card</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="Loader"></div>
      <div class="modal-body">
        <form class="form-horizontal" autocomplete="off" id="Newgift" method="post">

          <table>
            <div class="form-group">
              <label>Recipient's Name : <label>
                <span id="rname" class="reciptname"></span>
              </div>
              <div class="form-group">
                <label><span class="help" data-id="#modalgift">Gift Card Amount :</span></label>
                <input type="number" class="giftamount form-control " placeholder="Please Enter Amount" style="width: 77%;"  name="giftamount" value="" id="giftamount" data-packageprice="" >
              </div>
            </table> 
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="addgift"><i class="fa fa-shopping-cart"></i> ADD TO CART</button>
          </form>  

        </div>
      </div>
    </div>
  </div>



  <div class="modal fade" id="myModal-service-package" role="dialog">
    <div class="modal-dialog modal-lg">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Select Product</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal form-material" autocomplete="off" id="Newpservice" method="post">

            <table id="pservice_table" class="table table-bordered table-striped dataTable no-footer" style="min-width: 100%;">
              <thead>
                <tr>
                  <td></td>

                  <td>Service Name</td>
                  <td>Category</td>  
                  <td>Selling Price</td>
                  <td>Duration</td>
                  <td>Info</td>  
                </tr>
              </thead> 
              <tbody>
                <?php
                foreach($result_event3 as $srow2)
                  {?>
                    <tr>
                      <td>
                        <input type="checkbox" name="listpservice" id="listpservice" value="<?php echo $srow2['id']; ?>" data-pserviceCommissionAmount="<?php echo $srow2['CommissionAmount']; ?>" data-pServiceName="<?php echo $srow2['ServiceName']; ?>" data-pServiceCost="<?php echo $srow2['Price']; ?>" data-pServiceduration="<?php echo $srow2['Duration']; ?>"></td>
                        <td class="pServiceName"><?php echo $srow2['ServiceName']; ?></td>
                        <td class="pServiceCategory"><?php echo $srow2['Category']; ?></td>
                        <td class="pServiceCost"><?php echo $srow2['Price']; ?></td>
                        <td class="pServiceduration"><?php echo $srow2['Duration']; ?></td>
                        <td class="pServiceinfo"><?php echo $srow2['Info']; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <span style="color: red" id="listofcatagoryprodcut_error"></span>
                <div style="padding: 25px 0;"></div>
                <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add_pService"><i class="fa fa-check"></i> <?php echo $B6; ?></button>
                <button type="button" class="btn waves-effect waves-light btn-danger m-r-10" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B7; ?> </button>
              </form>
            </div>
            <div class="modal-footer">
              
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="Loader"></div>     
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="col-md-12">
        <div class="finallist_first">
          <center>
            <img src="<?php echo base_url; ?>/assets/images/empty-shopping-cart.png" class="img-responsive">
          </center>
        </div>

        <div class="finallist" style="display: none;">
          <form class="form-horizontal form-material NewOrder" autocomplete="off" action="" method="post" id="NewOrder">
            
            <input type="hidden" name="id" id="id" value="<?php echo $myevent; ?>">
            <input type="hidden" name="cid" id="ocid" value="">
            <input type="hidden" name="GetTotalPoint" id="CommissionAmount2" value="">
            <input type="hidden" name="Remainepoints" id="points" value="<?php echo $points; ?>">
            <input type="hidden" name="eid" id="eid" value="">
            <input type="hidden" name="SEND_EMAIL" id="SEND_EMAIL" value="">

            <table id="carttable">
              <tr>
                <td></td>
                <td>Item</td>
                <td>Qty</td>
                <td>Price($)</td>
                <td>Discount($)</td>
                <td> % </td>
                <td>Total Price($)</td> 
              </tr>
            </table>
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
            <div class="alert alert-danger" id="csrf_error" style="display: none;">
              <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
              <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
            </div>
          </div>




        </div> 
      </div>
    </div> 
    


    <div class="card" style="margin-bottom:0;">
      <div class="card-body">
        <div class="col-md-12 order_service">
          <div class="column pull-left order_button_left">
            <div type="submit" class="btn btn-secondary order_button" id="exit-client">
              <ul>
                <li><i class="fa fa-cog"></i></li>
              </ul>
            </div>
            <p><?php echo $B3; ?></p>
          </div>
          <div class="column pull-left order_button_left">
            <div type="submit" class="btn btn-secondary order_button" id="exit-Prodcut">
              <ul>
                <li><i class="fa fa-shopping-cart"></i></li>

              </ul>
            </div>
            <p><?php echo $B4; ?></p>
          </div>

          <div class="column pull-left order_button_left">
            <div type="submit" class="btn btn-secondary order_button" id="exit-Membership">
              <ul>
                <li><i class="fas fa-box-open"></i></li>

              </ul>
            </div>
            <p><?php echo $B5; ?></p>
          </div>

          <div class="column pull-left order_button_left">
            <div type="submit" class="btn btn-secondary order_button" id="giftcard">
              <ul>
                <li><b style="color: black;" >$</b></li>

              </ul>
            </div>
            <p><?php echo 'Gift Card'; ?></p>
          </div>
        </div> 
      </div>
    </div> 

    </div>
    <div class="col-lg-3">
     <div class="card  recipted" style="height: 100%;">
      <div class="card-body order_checkout" id="set">
       <p>Service Price <span id="finalserciceprice" class="pull-right" ></span></p>
       <p>Gift Card Price <span id="finalgiftprice" class="pull-right" ></span></p>
       <p>Product Price <span id="finalproductprice" class="pull-right "></span></p>
       <p>Package Price <span id="finalmembershipprice" class="pull-right "></span></p>
       <input type="hidden" name="UsePoint" value="" placeholder="0.00" id="point1" class="pull-right">
       <p>Tips $ <input name="mytips" id="mytips" disabled="" type="number">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
        <input name="mytipsper" id="mytipsper" disabled="" type="number"> %
        <select style="display: none;" id="listoftips" class="listoftips"><option>Choose %</option></select><span class="pull-right" id="tipsinpersantage"></span></p>
        <p>*Sales tax <span class="pull-right indoller" style="padding-left:5px; "></span> <span id="salestax" class="pull-right "></span></p>
        <p>Total Amount<span id="totalamount" class="pull-right"></span></p>
        <small>* Already included on the Total.</small>
        <input type="hidden" id="ototalsercieprice" name="TotalseriveAmount" value="">
        <input type="hidden" id="ototalgiftprice" name="TotalgiftAmount" value="">
        <input type="hidden" id="ototalproduct" name="TotalProductAmount" value="">
        <input type="hidden" id="ototalmembership" name="TotalMembershipAmount" value="">
        <input type="hidden" id="ototalorderprice" name="TotalOrderAmount" value="">
        <input type="hidden" id="sales_tax" name="sales_tax" value="0">
        <input type="hidden" id="oldprice" class="oldprice" name="oldprice" value="">
        <input type="hidden" name="tips" id="tips">
        <input type="hidden" name="orderpservice" id="orderpservice" value="0">
        <button type="button" class="hiding btn btn-info" id="total_one"><i class="fa fa-check"></i><?php echo $B1; ?></button>
        <button type="button" class="btn btn-danger remove_all" id="remove_all"><i class="fa fa-times"></i> <?php echo $B2?></button>  

        <div id="SEND_EMAIL_DIV" style="margin-top: 10px;display :none">
         <span>Do want to send a copy of Invoice to Customer?</span><br>

         <input type="radio" name="SEND_EMAIL" value="" checked="checked">
         <span>none</span>

         <br>
         <input id="InvoiceEmailInput" type="radio" name="SEND_EMAIL" value=""> 
         <span id="InvoiceEmail"></span>


         <br>
         <input id='CustomEMail' type="radio" name="SEND_EMAIL" value="" >
         <span> Custom</span>

         <br>
         <input type="text" id="CustomEMailInput" placeholder="Email" style="width: 100%;display: none;">

     </div>

     <div class="Loader"></div>
 </div>    
</div>
</div>
</form>
</div>



<div class="modal fade" id="paymenttypemodel" role="dialog" data-backdrop="static" data-keyboard="false">
 <div class="modal-dialog">

  <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title">Select Payment Type</h4>
    <button type="button" class="closespty close removegiftexist" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <p id="totalpay"></p>
    <div id="accordion">
     <?php if(!empty($paymentsetup['token'])){ ?>
      <div class="card">
       <div class="card-header" id="headingOne">
        <h5 class="mb-0">
         <button class="btn btn-info btn-rounded btn-block cardpay" data-toggle="collapse"  data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fa fa-credit-card" aria-hidden="true"></i> Card (SquareUp)
      </button>
  </h5>
</div>

<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="card-body">
     <div class="ifpayinplus">
      <div class="sq-payment-form">
       <div id="sq-walletbox">
        <button id="sq-google-pay" class="button-google-pay"></button>
        <button id="sq-apple-pay" class="sq-apple-pay"></button>
        <button id="sq-masterpass" class="sq-masterpass"></button>
        <div class="sq-wallet-divider">
         <span class="sq-wallet-divider__text">Or</span>
     </div>
 </div>
 <div id="sq-ccbox">
    <form id="nonce-form" novalidate action="<?= base_url?>/php_square_payment/process-card.php" method="post">
     <input type="hidden" id="totalpay2" class="totalpay2 splitcard" name="totalpay2" value="">
     <input type="hidden" class="ocid" name="ocid" value="">
     <input type="hidden" class="orderid" name="orderid" value="0">
     <input type="hidden" class="paymentype" name="paymentype" value="Card">
     <input type="hidden" class="SquareUpCard" name="SquareUpCard" value="SquareUpCard">
     <div class="sq-field">
      <label class="sq-label">Card Number</label>
      <div id="sq-card-number"></div>
  </div>
  <div class="sq-field-wrapper">
      <div class="sq-field sq-field--in-wrapper">
       <label class="sq-label">CVV</label>
       <div id="sq-cvv"></div>
   </div>
   <div class="sq-field sq-field--in-wrapper">
       <label class="sq-label">Expiration</label>
       <div id="sq-expiration-date"></div>
   </div>
   <div class="sq-field sq-field--in-wrapper">
       <label class="sq-label">Postal</label>
       <div id="sq-postal-code"></div>
   </div>
</div>
<div class="sq-field">
  <input type="submit" id="sq-creditcard" class="sq-button" name='SquareUpCard' value="Pay" onclick="onGetCardNonce(event)">
</div>
<div id="cc_error"></div>
<input type="hidden" id="card-nonce" name="nonce">
</form>
</div> 
</div>  
</div>
</div>
</div>
</div>

<?php } if(!empty($paymentsetup2['AUTHNET_LOGIN'])){ ?>
  <div class="card">
   <div class="card-header" id="headingOne">
    <h5 class="mb-0">
     <button class="btn btn-info btn-rounded btn-block cardpay" data-toggle="collapse"  data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
      <i class="fa fa-credit-card" aria-hidden="true"></i> Card (Authorize.Net)
  </button>
</h5>
</div>
<div id="collapseOne2" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="card-body">
     <div class="ifpayinplus">
      <div class="sq-payment-form">
       <div id="sq-ccbox">
        <form id="authorize_form" novalidate action="<?= base_url.'/Authorize/Order/process-card.php' ?>" method="post">
         <input type="hidden" id="totalpay2" class="totalpay2 splitcard" name="totalpay2" value="">
         <input type="hidden" class="ocid" name="ocid" value="">
         <input type="hidden" class="orderid" name="orderid" value="0">
         <input type="hidden" class="paymentype" name="paymentype" value="Card">
         <div class="sq-field">
          <label class="sq-label">Card Number</label>
          <input type="text" name="card" class="form-control" placeholder="4111111111111111">
      </div>
      <div class="sq-field-wrapper">
          <div class="sq-field sq-field--in-wrapper">
           <label class="sq-label">CVV</label>
           <input type="text" name="cvv" class="form-control" placeholder="123">
       </div>
       <div class="sq-field sq-field--in-wrapper">
           <label class="sq-label">Expire Year</label>
           <input type="text" name="card_exp_year" class="form-control" placeholder="2020">
       </div>
       <div class="sq-field sq-field--in-wrapper">
           <label class="sq-label">Expire Month</label>
           <input type="text" name="card_exp_month" class="form-control" placeholder="12">
       </div>
   </div>
   <div class="sq-field">
      <button class="sq-button" type="submit" name='AuthorizeCard'>
       Pay
   </button>
</div>        
</form>
</div> 
</div>  
</div>
</div>
</div>
</div>

<?php } 
$CC_disable = ""; 
if(empty($paymentsetup['token']) && empty($paymentsetup2['AUTHNET_LOGIN']))
{ 
  $CC_disable = "disabled";
  ?>
  <div class="card">
   <a class="paysetupURL p-2 text-center" href="https://mysunless.com/crm/paymentsetup" target="_blank">Setup Credit-Card Payment API</a>
</div>
<?php } ?>

<div class="card">
  <div class="card-header" id="headingTwo">
   <h5 class="mb-0">
    <button id="ChequeButton" class="btn btn-info btn-rounded btn-block collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
     <i class="fa fa-bank"></i> Cheque
 </button>
</h5>
</div>
<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
   <div class="card-body">

    <form method="post" action="OrderPaymant.php" autocomplete="off" id="Chequeform">
     <input type="hidden" class="ocid" name="ocid" value="">
     <input type="text" id="totalpay2" class="form-control totalpay2" readonly name="totalpay2" value="">
     <div class="form-group">
      <label><span class="help"> Cheque Number * </span></label>
      <input type="text" name="ChequeNumber" id="ChequeNumber" class="form-control" value="" placeholder="0123456789">
  </div>

  <div class="form-group">
      <label><span class="help"> Name Of Bank * </span></label>
      <input type="text" name="bankname" id="bankname" class="form-control" value="" placeholder="Abc Bank">
  </div>

  <div class="form-group">
      <label><span class="help"> Submit Date * </span></label>
      <p id="datepairExample"><input type="text" name="submitdate" id="submitdate" class="date form-control" value="" placeholder="2018-01-01"></p>
  </div>

  <div class="form-group">
      <label><span class="help">Select Cheque Status</span></label>
      <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Status" id="ChequeStatus" name="ChequeStatus">
       <option value=""> Select Cheque Status </option>
       <option value="PROCESSING"> In Process (PROCESSING) </option>
       <option value="CAPTURED"> Clear (CAPTURED) </option>
       <option value="FAILED"> Bounce (FAILED) </option>
   </select>
</div>


<div class="form-group">
  <input type="submit" name="Chequesubmit" id="Chequesubmit" class="btn btn-info m-r-10 " value="<?php echo $B13; ?>">
</div>
</form>

</div>
</div>
</div>
<div class="card">
  <div class="card-header" id="headingThree">
   <h5 class="mb-0">
    <button id="CashButton" class="btn btn-info btn-rounded btn-block collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
     <i class="fa fa-money"></i>  Cash
 </button>
</h5>
</div>
<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
   <div class="card-body">

    <form method="post" action="OrderPaymant.php" autocomplete="off" id="Caseform">
     <input type="hidden" class="ocid" name="ocid" value="">
     <div class="form-group">
      <label><span class="help"> Received Amount * </span></label>
      <input type="text" id="totalpay2" readonly="" class="form-control totalpay2"  name="totalpay2" value="">
      <input type="hidden" id="totalpay2" class="form-control totalpay2"  name="totalpay2" value="">

      <input type="hidden" name="CaseStatus" id="CaseStatus" value="CAPTURED">
  </div>


  <div class="form-group">
     <input type="submit" name="Casesubmit" id="Casesubmit" class="btn btn-info m-r-10 " value="Cash Submit">
 </div>    

</form>

</div>
</div>
</div>


<div class="card">
  <div class="card-header" id="headingThree">
    <h5 class="mb-0">
      <button <?= $CC_disable?> class="btn btn-info btn-rounded btn-block collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapseThree">
        <i class="fa fa-credit-card" aria-hidden="true"></i> + <i class="fa fa-money" aria-hidden="true"></i>  Split Payment (Card + Cash)
      </button>
    </h5>
  </div>
  <div id="collapse4" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" style="">
    <div class="card-body">
      <script type="text/javascript">
        function split(input)
        {
          var total = $(".split").val();
          var t = total.replace(',','');
          var sum = t - input;

          $("#card").val(parseFloat(sum).toFixed(2));
        }
      </script>
      <form method="post" autocomplete="off" id="splitform" >
        <input type="hidden" class="ocid" name="ocid" value="">
        <input type="hidden" id="SplitCashID" name="SplitCashID" value="new">
        <div class="form-group">
          <label><span class="help">Input Amount For Cash</span></label>
          <input type="number" id="cash" onkeyup="split(this.value)"  class="form-control cash" name="cash">
          <p id="err" style="color: red;" ></p>

        </div>
        <div class="form-group">
          <label><span class="help">Card</span></label>
          <input type="text" id="card" readonly="" class="form-control  valid" name="card" value="" aria-invalid="false">
        </div>
        <div class="form-group">
          <label><span class="help">Total</span></label>
          <input type="text" id="totalpay2" readonly=""  class="form-control split totalpay2 valid" name="totalpay2" value="" aria-invalid="false">
          <input type="hidden" id="totalpay2" class="form-control totalpay2" name="totalpay2" value="">
          <input type="hidden" id="splitvalue" class="form-control " name="splitvalue" value="">

          <input type="hidden" name="CaseStatus" id="CaseStatus" value="PENDING">
        </div>
        <div class="form-group">
          <input type="submit" name="splitsubmit" id="splitsubmit" class="btn btn-info m-r-10 " value="Proceed">
        </div>    

      </form>

    </div>
  </div>
</div>

<div class="card" style="display:none;">
<form id="GiftCardPayment" action="<?= base_url?>/OrderPaymant.php" method="post">
 <input type="hidden" id="GiftCard_totalpay2" name="totalpay2" value="">
 <input type="hidden" id="GiftCard_ocid" name="ocid" value="">
 <input type="hidden" id="GiftCard_CaseStatus" name="CaseStatus" value="CAPTURED">
 <input type="hidden" name="Casesubmit" value="Cash">
 </form>
</div>

</div>

<div class="alert alert-success" id="saved_resonse" style="display: none;">
  <button type="button" class="close"> <span aria-hidden="true">×</span> </button>
  <h3 class="text-success">
    <i class="fa fa-check-circle">
    </i>
    Information!
  </h3>
  <p id="saved_resonsemsg"></p>
</div>

<button type="button" class="closespty btn btn-danger removegiftexist" id="closespty"><i class="fa fa-times"></i> Back To Order</button>

</div>
</div>

</div>
</div>


<div class="modal fade" id="myModal_ordersummery" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Order Summary</h4>
        <button type="button" class="close removegiftexist" data-id="0" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

               <div class="sumtagaee" style="overflow: auto;">
                <table id="carttable2" style="margin: auto;">
                 <tr>
                  <td>Item</td>
                  <td>Qty</td>
                  <td>Price($)</td>
                  <td>Discount($)</td>
                  <td> % </td>
                  <td>Total Price($)</td> 
              </tr>
          </table>
      </div>
      <div class="col-lg-4 pull-right">
        <div class="card">
         <div class="card-body order_checkout" id="set">
          <p>Service Price <span id="finalserciceprice" class="pull-right finalserciceprices"></span></p>
          <p>Gift Card Price <span id="finalgiftprice" class="pull-right finalgiftprices"></span></p>
          <p>Product Price <span id="finalproductprice" class="pull-right finalproductprices"></span></p>
          <p>Package Price <span id="finalmembershipprice" class="pull-right finalmembershipprices"></span></p>
          <p>Gift Card Applied <span class="pull-right gift_apply"></span> 
           <p>Tips <span class="pull-right mytipses"></span></p>
           <p>Sales tax<span class="pull-right salestaxs"></span></p>
           <p>Total Amount<span id="totalamount" class="pull-right totalamounts"></span></p>
            <small>* Already included on the Total.</small>
           <button type="button" class="btn btn-info" id="applygift" value=""><i class="fa fa-gift"></i> Apply Gift Card Balance (<span class="giftbal"></span>)</button><br>
           <div class="giftdiv" style="display: none;">
            <input class="form-control" placeholder="Please enter amount" type="text" name="textgift" id="textgift">
            <button class="btn btn-sm btn-info" id="giftapp" type="button" >Apply</button>
        </div>
        <button type="button" style="display: none;" class="btn btn-danger" onclick="removegift()" id="removegift" value="">Remove Gift Card</button>                 
        <button type="button" class="btn btn-info cardpayment" id="total"><i class="fa fa-check"></i> <?php echo $B11; ?></button>
        <button type="button" class="btn btn-info" id="saveforlatter" name="saveforlatter" value="PENDING"><i class="fa fa-save"></i> <?php echo " Save for Later"; ?></button>
        <button type="button" class="btn btn-danger removegiftexist" data-dismiss="modal"><i class="fa fa-times"></i> Back To Order</button>
        <div class="Loader"></div>
    </div>    
</div>
</div>

</div>
</div>
</div>
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
<!-- ==============================================================  -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<?php include 'scripts.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<script>

  $(document).on('click','#addcusomimagebutton',function(){
   $('#ProfileImg').trigger('click'); 
});

  $(document).ready(function(){
     $(document).bind("contextmenu",function(e){
        return false;
     });

     $(document).keydown(function(e){
      if(e.which === 123){
         return false;
      }
    });
});



  var placeSearch, autocomplete;
  var componentForm = {
   street_number: 'short_name',
   route: 'long_name',
   locality: 'long_name',
   administrative_area_level_1: 'long_name',
   country: 'long_name',
   postal_code: 'short_name'
};

$("input[name='SEND_EMAIL']").click(function(){
   var SEND_EMAIL = $('input[name="SEND_EMAIL"]:checked').val();
   $("#SEND_EMAIL").val(SEND_EMAIL);

   if($(this).attr('id')=='CustomEMail'){
    $('#CustomEMailInput').show();
}else{
    $('#CustomEMailInput').hide();
}
});

$('#CustomEMailInput').keyup(function(){
   var val = $(this).val();
   $("#CustomEMail").val(val);
   $("#SEND_EMAIL").val(val);
});

$("#applygift").on("click",function(){
   $(this).css("display","none");
   $(".giftdiv").css("display","block");
});

$("#giftapp").on("click",function(){
   var userinput = $("#textgift").val();
   if($.isNumeric(userinput))
   {
    var st = $("#totalamount").text();

    var st1 = st.replace(/,/g, "");
    var totalamount = st1.replace('$','');
    var bal = $("#giftbal").text();
    var bal1 = bal.replace(',','');
    var giftbal = bal1.replace('$','');
    if(parseInt(userinput) > parseInt(giftbal))
    {
     swal("Sorry!","Amount is greater than Gift-Balance","info");
 }
 else if(parseInt(userinput) > parseInt(totalamount))
 {
     swal("Sorry!","Order amount should be greater than your giftCard balance","info");
 }
 else
 {
     var sum = totalamount - userinput;
     var total = '$'+sum.toFixed(2);

     $("#totalamount").text(total);
     $(".totalamounts").text(total);
     var t = sum.toFixed(2);

     $("#ototalorderprice").val(t);
     $("#applygift").css("display","none");
     $(".giftdiv").css("display","none");
     $("#removegift").css("display","block");
     $(".removegiftexist").attr("data-id","1");
     $(".gift_apply").text("$"+parseFloat(userinput).toFixed(2));
 }
 return false;
}
else
{
   swal("Error!","Please enter only numeric value","error");
   return false;
}
});


function removegift()
{
   var st =$("#totalamount").text();
   var st1 = st.replace(',','');
   var totalamount = st1.replace('$','');
   var giftbal = $("#textgift").val();


   var sum = parseFloat(totalamount) + parseFloat(giftbal); 

   var total = '$'+sum.toFixed(2); 

   $("#totalamount").text(total);
   $(".totalamounts").text(total);
   var t = sum.toFixed(2);   

   $("#ototalorderprice").val(t);
   $("#removegift").css("display","none");
   $("#applygift").css("display","block");
   $(".removegiftexist").attr("data-id","0");
   $(".gift_apply").text('');
}
$(".removegiftexist").on("click",function (){
 var data = $(this).attr("data-id");
 if(data==1)
 {
  removegift();
  $(this).attr("data-id","0");
}
});

</script>


<script type="text/javascript">
  $(document).ready(function(){

      setInterval(function(){$(".checkout").addClass("active");}, 10);
      
      var daf = Math.floor(Math.random() * 40) + 1  

       $(document).on('click','#newclient_1',function(){
          var daf = Math.floor(Math.random() * 40) + 1  

          $('#FirstName').val('')
          $('#cid').val('new')
          $('#LastName').val('')
          $('#phonenumber').val('')
          $('#example-email').val('')
          $('#autocomplete').val('')
          $('#street_number').val('')
          $('#postal_code').val('')
          $('#country').val('') 
          $('#administrative_area_level_1').val('')
          $('#locality').val('')

          $( ".dropify-render img" ).first().remove();
          $('#autocomplete').val('');
          $("#ProfileImg3").val('Layer'+daf+'.png')
          $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");
          $('<img src="<?php echo base_url; ?>/assets/ProfileImages/Layer'+daf+'.png" id="pImage">').appendTo(".dropify-render");
          $('.dropify-filename-inner').text('noimage.png')

          $("#myModal_viewclient").modal('show');

        });

      var acutalaction = '<?php if(isset($_GET['action'])) { echo $_GET['action']; } ?>';

      if(acutalaction=='appo')
      {

        var vcid = '<?php if(!empty($_GET['cid'])) { echo base64_decode($_GET['cid']); }  ?>';

        var Servicename = '<?php if(!empty($_GET['servicename'])) { echo base64_decode($_GET['servicename']); }  ?>';
        var stime = '<?php if(!empty($_GET['stime'])) { echo base64_decode($_GET['stime']); }  ?>';
        var ServiceProvider = '<?php if(!empty($_GET['ServiceProvider'])) { echo base64_decode($_GET['ServiceProvider']); }  ?>';

        var eventid = '<?php if(!empty($_GET['eventid'])) { echo base64_decode($_GET['eventid']); }  ?>';
        $("#eid").val(eventid);

        if(vcid!='')
        {

          $(".Loader").show();
          $("#listofcatagory").val(vcid).trigger('change');

          $.ajax({
            dataType:"json",
            type:"post",
            data: {'ClientsName':vcid},
            url:'?action=editfile',
            success: function(data)
            {
              if(data)
              {
                $(".Loader").hide();
                $(".hidddeforfirst").show();
                $('#listofcatagory_error').hide();
                $(".exit-client-image").show();
                data.resonse[0].FirstName = data.resonse[0].FirstName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                  return letter.toUpperCase();
                });
                $('#FirstName').text(data.resonse[0].FirstName);
                data.resonse[0].LastName = data.resonse[0].LastName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                  return letter.toUpperCase();
                });

                $('#reciptname').text(data.resonse[0].FirstName+' '+data.resonse[0].LastName);    
                $("#rname").text(data.resonse[0].FirstName+' '+data.resonse[0].LastName);
                $('#LastName').text(data.resonse[0].LastName);
                $('#Phone').text(data.resonse[0].Phone);
                $('#Email').text(data.resonse[0].email);
                $('#InvoiceEmail').text(data.resonse[0].email);
                $('#InvoiceEmailInput').val(data.resonse[0].email);
                $('#SEND_EMAIL_DIV').show();

                var bal = data.gibal;
                if(bal == "" || bal == null || bal == 0)
                {
                  $("#giftbal").text('$0');
                  $(".giftbal").text('$0');
                  $("#applygift,#removegift").hide();
                }
                else
                {
                  $("#giftbal").text('$'+bal);
                  $(".giftbal").text('$'+bal);  
                }

                $('.SelectPackageliv').html('');
                $('.SelectPackageliv').append('<option class="SelectPackage">Select Package</option>');
                $.each(data.resonse, function( index, value ) 
         {
           if(value.Name!=null)
           {
            $('.SelectPackageliv').append('<option class="SelectPackage" value="'+value.cpackagid+'">'+value.Name+'</option>');
          }    
        });

           

                $('#cid').text(data.resonse[0].cid);
                $('#ocid').val(data.resonse[0].cid);
                $('.ocid').val(data.resonse[0].cid);
                $('#editcustomer').show();
               $('#editcustomer').val(btoa(data.resonse[0].cid));
                $('#editcustomer').attr('data-cid',btoa(data.resonse[0].cid));
                $(".viewInfo").attr('data-cid',btoa(data.resonse[0].cid));
                if(data.resonse[0].ProfileImg)
                {

                  $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse[0].ProfileImg);
                }
                else
                {

                  $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png'?>"); 
                }
              }
              else if(data.error)
              {
                alert('ok');  
              }
            }
          })

          // $('#myModal').modal('toggle'); 
          $("#listofcatagory2").val(Servicename).trigger('change');

          if(Servicename!='')
          {
            $.ajax({
              dataType:"json",
              type:"post",
              data: {'Servicename':Servicename},
              url:'?action=editfile',
              success: function(data)
              {
                if(data)
                { 

                  if(data.error_servie){
                    swal(data.error_servie);
                    return false;
                  }

                  $('#sprice').val(data.resonse.Price); 
                  $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                  $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                  $('#listofcatagory3').html('');
                  var listarray = data.resonse.Users
                  var i =0;

                           $.ajax({
                              dataType:"json",
                              type:"post",
                              data: {'UserName':listarray},
                              url:'?action=editfile',
                              success: function(data2)
                              {
                                 if(data2)
                                 { 

                                    $.each(data2.resonse, function (key, val) 
                                    { 
                                       if(val.id==ServiceProvider)
                                       {
                                          $('#listofcatagory3').append('<option selected  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                                      }
                                      else
                                      {
                                          $('#listofcatagory3').append('<option  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                                      }
                                  });

                                    $(".Loader").hide();
                                    $( "#Newservice" ).submit();

                                }
                            }
                        });

                       }
                       else if(data.error)
                       {
                           alert('ok');  
                       }
                   }
               })
          }

          $("#scrollDefaultExample").val(stime).trigger('change');

        }

      }




      // $("#NewClient").validate({
      //   rules: {                
      //     FirstName: "required",
      //     LastName: "required",
      //     Phone: {required: true,},
      //     email: "required",
      //     Address: "required",
      //     Zip: "required",
      //     City: "required",
      //     State: "required",
      //     Country:"required",

      //   },
      //   messages: {             
      //     FirstName:  "Please enter firstName",
      //     LastName:  "Please enter lastName",
      //     Phone:  "Please enter phone number",
      //     email:  "Please enter email",
      //     Address:  "Please enter address",
      //     Zip:  "Please enter zipcode",
      //     Country:"Please select country",
      //     City:  "Please enter city", 
      //     State:  "Please enter state",

      //   },
      //   errorPlacement: function( label, element ) {
      //     if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
      //       element.parent().parent().append( label );
      //     } else {
      //       label.insertAfter( element );
      //     }
      //   },
      //   submitHandler: function() {
      //     $(".Loader").show();
      //     var form = $('#NewClient')[0];
      //     var data = new FormData(form);

      //     jQuery.ajax({
      //       dataType:"json",
      //       type:"post",
      //       data:data,
      //       contentType: false, 
      //       processData: false,
      //       url:'<?php echo EXEC; ?>Exec_Edit_Client',
      //       success: function(data)
      //       {
      //        if(data.resonse)
      //        {
      //         $("#resonse").show().delay('3000').fadeOut();; 
      //         $('#resonsemsg').html('<span>'+data.resonse+'</span>')
      //         $( '#NewClient' ).each(function(){
      //           this.reset();
      //         });
      //         $(".Loader").hide();
      //         $("#myModal_viewclient").modal('hide')


      //         $('#listofcatagory').append('<option selected value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
      //         $('#listofcatagory').select2(); 

      //       }
      //       else if(data.error)
      //       {
      //        $("#error").show();
      //        $('#errormsg').html('<span>'+data.error+'</span>');
      //        $(".Loader").hide();
      //        $("#myModal_viewclient").modal('hide')
      //        setTimeout(function () { window.location.reload() }, 2000)

      //      }
      //      else if(data.csrf_error)
      //      {

      //        $("#csrf_error").show();
      //        $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
      //        $(".Loader").hide();
      //        $("#myModal_viewclient").modal('hide')
      //        setTimeout(function () { window.location.reload() }, 2000)
      //      }
      //    }
      //  });
      //   }           
      // });


   //    $('#country').on('change',function(){
   //     $(".Loader").show();
   //     CountrysName=$(this).val();

   //     $.ajax({

   //        dataType:"json",
   //        type:"post",
   //        data: {'CountrysName':CountrysName},
   //        url:'?action=editfile',
   //        success: function(data)
   //        {
   //           if(data)
   //           {

   //              $('#administrative_area_level_1').html('');


   //              var i =0;
   //              $.each(data.resonse, function(k,v)
   //              {

   //                 $('#administrative_area_level_1').append('<option value="'+v.name+'">'+ v.name +'</option>');
   //             });
   //              $(".Loader").hide();

   //          }
   //          else if(data.error)
   //          {
   //              alert('ok');
   //          }

   //      }
   //  })

   // });




      $('#product_table,#pservice_table').DataTable( {
         responsive: true
     } );



      $('.date').datepicker({
          'format': 'yyyy-mm-dd',
          'autoclose': true
      });
      // $('#datepairExample').datepair();

      $(".hidddeforfirst").hide();
      $(".exit-client-image").hide();
      $('#point1').attr("disabled", true) 

      var vcid = '<?php if(!empty($_GET['vcid'])) { echo base64_decode($_GET['vcid']); }  ?>';
      var client = "<?php if(isset($client)){echo $client; } ?>";
      if(client!='')
      {
         vcid= client;
     }
     if(vcid!='')
     {


         $(".Loader").show();
         $("#listofcatagory").val(vcid).trigger('change');

         $.ajax({
          dataType:"json",
          type:"post",
          data: {'ClientsName':vcid},
          url:'?action=editfile',
          success: function(data)
          {
           if(data)
           {
            $(".Loader").hide();
            $(".hidddeforfirst").show();
            $('#listofcatagory_error').hide();
            $(".exit-client-image").show();
            data.resonse[0].FirstName = data.resonse[0].FirstName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
             return letter.toUpperCase();
         });

            $('#FirstName').text(data.resonse[0].FirstName);

            data.resonse[0].LastName = data.resonse[0].LastName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
             return letter.toUpperCase();
         });


            $('#LastName').text(data.resonse[0].LastName);
            $('#Phone').text(data.resonse[0].Phone);
            $('#Email').text(data.resonse[0].email);
            $('#InvoiceEmail').text(data.resonse[0].email);
            $('#InvoiceEmailInput').val(data.resonse[0].email);
            $('#SEND_EMAIL_DIV').show();

            var bal = data.gibal;
            if(bal == "" || bal == null)
            {
             $("#giftbal").text('$0');
             $(".giftbal").text('$0');
             $("#applygift,#removegift").hide();
         }
         else
         {
             $("#giftbal").text('$'+bal); 
             $(".giftbal").text('$'+bal); 
         }

         $('.SelectPackageliv').html('')
         $('.SelectPackageliv').append('<option class="SelectPackage">Select Package</option>');
         $.each(data.resonse, function( index, value ) 
         {
           if(value.Name!=null)
           {
            $('.SelectPackageliv').append('<option class="SelectPackage" value="'+value.cpackagid+'">'+value.Name+'</option>');
          }    
        });

         $('#cid').text(data.resonse[0].cid);
         $('#editcustomer').show();
         $('#editcustomer').val(btoa(data.resonse[0].cid));
$('#editcustomer').attr('data-cid',btoa(data.resonse[0].cid));
$(".viewInfo").attr('data-cid',btoa(data.resonse[0].cid));
         $('#ocid').val(data.resonse[0].cid);
         $('.ocid').val(data.resonse[0].cid);
         if(data.resonse[0].ProfileImg)
         {
             $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse[0].ProfileImg);
         }
         else
         {
             $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>"); 
         }
         var vcid = '<?php if(!empty($_GET['vcid'])) { echo base64_decode($_GET['vcid']); }  ?>';
         if('<?php echo (isset($_GET['addpackage']))?true:false?>'){

             $("#exit-Membership").trigger('click');
         }
     }
     else if(data.error)
     {
         alert('ok');  
     }
 }
})

     }

     $(document).on('change','#listofcatagory',function(e){
       e.preventDefault();
       $(".Loader").show();
       ClientsName=$(this).val();
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
          $(".hidddeforfirst").show();
          $('#listofcatagory_error').hide();
          $(".exit-client-image").show();

          data.resonse[0].FirstName = data.resonse[0].FirstName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
           return letter.toUpperCase();
       });

          $('#FirstName').text(data.resonse[0].FirstName);

          data.resonse[0].LastName = data.resonse[0].LastName.toLowerCase().replace(/\b[a-z]/g, function(letter) {
           return letter.toUpperCase();
       });

          $('#reciptname').text(data.resonse[0].FirstName+' '+data.resonse[0].LastName);
          $("#rname").text(data.resonse[0].FirstName+' '+data.resonse[0].LastName);
          $('#LastName').text(data.resonse[0].LastName);
          $('#Phone').text(data.resonse[0].Phone);
          $('#Email').text(data.resonse[0].email);
          $('#InvoiceEmail').text(data.resonse[0].email);
          $('#InvoiceEmailInput').val(data.resonse[0].email);
          $('#SEND_EMAIL_DIV').show();

          var bal = data.gibal;
          if(bal == "" || bal == null)
          {
           $("#giftbal").text('$0');
           $(".giftbal").text('$0');
           $("#applygift,#removegift").hide();
       }
       else
       {
           $("#giftbal").text('$'+bal); 
           $(".giftbal").text('$'+bal); 
       }

       var model = $(".btst").attr("data-id");
       $(model).modal("show");
       $('.SelectPackageliv').html('')
       $('.SelectPackageliv').append('<option class="SelectPackage">Select Package</option>');
                $.each(data.resonse, function( index, value ) 
         {
           if(value.Name!=null)
           {
            $('.SelectPackageliv').append('<option class="SelectPackage" value="'+value.cpackagid+'">'+value.Name+'</option>');
          }    
        });
  


       $('#cid').text(data.resonse[0].cid);
       $('#ocid').val(data.resonse[0].cid);
       $('.ocid').val(data.resonse[0].cid);
       $('#editcustomer').show();
       $('#editcustomer').val(btoa(data.resonse[0].cid));
$('#editcustomer').attr('data-cid',btoa(data.resonse[0].cid));
$(".viewInfo").attr('data-cid',btoa(data.resonse[0].cid));
       if(data.resonse[0].ProfileImg)
       {
           $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse[0].ProfileImg);
       }
       else
       {
           $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>"); 
       }
   }
   else if(data.error)
   {
       alert('ok');  
   }
}
})
   });
//      $(document).on('click','#editcustomer',function(){
//          $('.dropify-render').text('')
//          $('#clid').val('');
//          $('.dropify-filename-inner').text('')
//          $(".Loader").show();
//          event.preventDefault();
//          var customersid = $(this).val();
//          var customersid2 = atob(customersid)
//          $.ajax({
//           dataType:"json",
//           type:"post",
//           data: {'customersid2':customersid2},
//           url:'?action=editfile',
//           success: function(data)
//           {
//            if(data.resonse)
//            { 
//             $('#FirstNameC').val(data.resonse.FirstName)
//             $('#id').val(data.resonse.id)
//             $('#LastNameC').val(data.resonse.LastName)
//             $('#phonenumber').val(data.resonse.Phone)
//             $('#example-email').val(data.resonse.email)
//             $('#autocomplete').val(data.resonse.Address)
//             $('#street_number').val(data.resonse.Address)
//             $('#postal_code').val(data.resonse.Zip)
//             $('#country').val(data.resonse.Country) 
//             $('#administrative_area_level_1').val(data.resonse.State)
//             $('#locality').val(data.resonse.City)
//             $('#oldimage').val(data.resonse.ProfileImg)
//             if(data.resonse.ProfileImg !== '')
//             {
//              $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
//              $('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
//              $('.dropify-filename-inner').text(data.resonse.ProfileImg)
//          }
//          else if(data.resonse.ProfileImg =='')
//          {
//              $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
//              $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
//              $('.dropify-filename-inner').text('noimage.png')
//          }
//          $("#myModal_viewclient").modal('show')
//          $(".Loader").hide();
//      }
//      else if(data.resonse==false)
//      {
//         $(".Loader").hide();
//         swal('No data found')
//     }
// }
// })
//      });

     $('#phonenumber').keyup(function(e){
         var ph = this.value.replace(/\D/g,'').substring(0,10);
         this.value = ph;
     });


     $('#exit-client').on('click',function(){
         var listofcatagory=$('#listofcatagory').val();
         $('#listofcatagory_error').text("");
         $(".btst").attr("data-id","#myModal");
         if(listofcatagory=='')
         {    
          $('#listofcatagory_error').text("Please select client");
      }
      else if(listofcatagory!='')
      {
          $('#myModal').modal('toggle'); 
      }
  });
     $('#listofcatagory2').on('change',function(){
         $(".Loader").show();
         Servicename=$(this).val();
         $.ajax({
          dataType:"json",
          type:"post",
          data: {'Servicename':Servicename},
          url:'?action=editfile',
          success: function(data){
           if(data)
           {
            $('#sprice').val(data.resonse.Price); 
            $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
            $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
            $('#listofcatagory3').html('');
            var listarray = data.resonse.Users;
            var i =0;
            if(listarray!='')
            {
             $.ajax({
              dataType:"json",
              type:"post",
              data: {'UserName':listarray},
              url:'?action=editfile',
              success: function(data2){
               if(data2.resonse)
               { 
                $.each(data2.resonse, function (key, val) 
                {
                 $('#listofcatagory3').append('<option  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                 $(".Loader").hide();   
             });
            }
            else
            {
                $(".Loader").hide();
            }
        }
    });
         }
         else
         {
             $(".Loader").hide();
         }
     }
     else if(data.error)
     {
        alert('ok');  
    }
}
})
     });

     $('#exit-Prodcut').on('click',function(){
         var listofcatagory=$('#listofcatagory').val();
         $('#listofcatagory_error').text("");
         $(".btst").attr("data-id","#myModal-Prodcut");
         if(listofcatagory=='')
         {    
          $('#listofcatagory_error').text("Please select client");
      }
      else if(listofcatagory!='')
      {
          $('#myModal-Prodcut').modal('toggle'); 
      }
  });

     $('#exit-Membership').on('click',function(){
         var listofcatagory=$('#listofcatagory').val();
         var selectpage_detatil =$('#SelectPackage').text();
         $(".btst").attr("data-id","#myModal-Membership");
         $('#listofcatagory_error').text("");
         if(listofcatagory=='')
         {    
          $('#listofcatagory_error').text("Please select client");
      }
      else if(listofcatagory!='')
      {
          $('#myModal-Membership').modal('toggle'); 

      }
  });

     $('#giftcard').on('click',function(){
       var listofcatagory=$('#listofcatagory').val();
       var selectpage_detatil =$('#SelectPackage').text();
       $(".btst").attr("data-id","#modalgift");
       $('#listofcatagory_error').text("");
       if(listofcatagory=='')
       {    
          $('#listofcatagory_error').text("Please select client");
      }
      else if($('input[name="gServiceName[]"] ').length>0){
        swal('Gift Card already added.');
    }
    else 
    {
      $('#modalgift').modal('toggle');
  }
});

     $('#listofcatagory4').on('change',function(){
         $(".Loader").show();
         Membershipname=$(this).val();
         $.ajax({
          dataType:"json",
          type:"post",
          data: {'Membershipname':Membershipname},
          url:'?action=editfile',
          success: function(data){
           if(data)
           {
            $(".Loader").hide();
            $('#mprice').val(data.resonse.Price); 
            $('#mCommissionAmount').val(data.resonse.CommissionAmount);
            $('.PckageAmount').val(data.resonse.Price);
            $('.PckageAmount').data('packageprice',data.resonse.Price); 
            if(data.resonse.Noofvisit=='Unlimited')
            {
              $('.count').val('Unlimited');
              $(".qty .form-group").hide();
              $(".qty .unlimitedlabel").show();
            }
            else
            {
              $('.count').val(data.resonse.Noofvisit);
              $(".qty .form-group").show();
              $(".qty .unlimitedlabel").hide();

            }
        }    
        else if(data.error)
        {
           alert('ok');  
       }
   }
})
     });


     $("#splitform").validate({
         rules: {
          cash : {"required": true,"number": true}
      },
      messages: {
          cash : {
           "required":"Please enter value in it",
           "number":"Enter only numeric value"
       },
   },
   submitHandler: function()
   {
      var totalpay1 = $(".split").val();
      var totalpay = totalpay1.replace(",","");
      var cashval = $(".cash").val(); 
      var cardval = $("#card").val();
      if(parseInt(cashval) == 0 || parseInt(cashval) >= parseInt(totalpay))
      {
       $("#err").html("Please input amount less than Order amount and not to be '0'");
       $("#card").val("");
       return false;
   }
   else
   {  

    data = $("#splitform").serialize();
    $('.Loader').show();
    $.ajax({
     dataType:"json",
     type:"post",
     data: data,
     url:'<?= base_url?>/OrderPaymant',
     success: function(data)
     {
      $('.Loader').hide();

      if(data.response){

       $("#err").html("");
       $('#cash').val('');
       $("#CashButton").hide();
       $("#ChequeButton").hide();
       $("#splitvalue").val(cashval+"-"+cardval);
       $("#SplitCashID").val(data.id);
       swal("Cash Received!","Please provide your card details for rest of the payment","success");
       $(".cardpay").trigger("click");
       $(".totalpay2").val(cardval);
       $("#totalpay").text('$'+cardval);
       $(".orderid").val("1");
       $(".paymentype").val('Split-Card');
     }else{
      swal('Something went wrong');
     }

   }
 });

   }
}
});


     jQuery.validator.addMethod("time", function(value, element) {

      if(value){
      
      if(value.toLowerCase().indexOf('am')>0 || value.toLowerCase().indexOf('pm')>0){
        return moment(value, 'hh:mmA',true).isValid();
      }else{
        return moment(value, 'HH:mm',true).isValid() ;
      }
    }else{
      return true;  
    }

}, "Invalid format! <br> Tip: 01:00PM or 13:00 ");


     $("#Newservice").validate({
         rules: {        
          listofcatagory2: "required",
          ServiceTime: {time:true},

      },
      messages: {       
         listofcatagory2:  "Please select service",

     },submitHandler: function() {
        
        AddServiceFunction();
        $('#myModal').modal('hide');
    }       
});

     var urlParams = new URLSearchParams(window.location.search);
     var action = urlParams.get('action'); 
     var id = urlParams.get('id'); 
     console.log(urlParams);

     if(action == "edit")
     {
        $("#applygift").hide();
        $('.text-themecolor').text("Edit Order");

    }
    else
    {
       $("#applygift").show();
       $("#saveforlatter").show();
   }


 if(action == "edit" && id != "")
 {
     $.ajax({
       dataType:"json",
       type:"post",
       data: {'actionEdit':action,'OrderId':id},
       url:'?action=editfile',
       success: function(data)
       {

          var Cid = data.cid;


          $("#listofcatagory").select2();
          $("#listofcatagory").val(Cid);
          $("#listofcatagory").select2().trigger('change');

          if(data.serviceResponse.length !== 0 || data.giftResponse.length !== 0 || data.productResponse.length !== 0 || data.membershipResponse.length !== 0 || data.resultOrderMaster.pservicepackage)
          { 


            $(".finallist").show();
            $(".finallist_first").hide();

            $('#point1').attr("disabled", false);

            if(data.resultOrderMaster.tips)
            {
              $('#mytips').prop("disabled", false);
              $('#mytips').val(data.resultOrderMaster.tips);

            }

            if(data.resultOrderMaster.pservicepackage)
            {
                var obj = [];
                obj.push(data.resultOrderMaster);
              AddPackage(obj[0]);
        
            }


          if(data.serviceResponse.length != 0)
          {

            var client_id = data.serviceResponse[0].Cid;
            var i;
            for(i = 0; i<data.serviceResponse.length; i++){    
                 

                AddServiceFunction('edit',data.serviceResponse[i]);
            }
        }


        if(data.productResponse.length != 0)
        {
            var i;
            
            for(i = 0;i<data.productResponse.length;i++){

                AddProductFunction(data.productResponse[i]);
            }
        }

        if(data.giftResponse.length !== 0)
        {
            var i;
            for(i = 0;i<data.giftResponse.length;i++){
                AddGiftCard(data.giftResponse[i].gServicePrice);

            }
        }


        if(data.membershipResponse.length !== 0)
        {
            var i;

          for(i = 0;i<data.membershipResponse.length;i++){
                AddMembership(data.membershipResponse[i]);

          }
      }

    }
    addNumbers();
}
});
 }


     function AddServiceFunction(action='add',data=[]){

        if(action=='add'){
        
         var selectedservice = $('#select2-listofcatagory2-container').text();
        var selectedserviceid = $('#listofcatagory2').val();
        var selectedsprice = $('#sprice').val();
        var sselectedCommissionAmount= $('#sCommissionAmount').val();
        var selectedsprovider = $('#listofcatagory3').val();
        var selectedsstime = $('#scrollDefaultExample').val();

        var servicediscount = '0';
        var serviceperc = '0';

    }else{

         var selectedservice =data.ServiceName;
        var selectedserviceid =data.SeriveId;
        var selectedsprice = data.ServicePrice;
        var sselectedCommissionAmount= data.CommissionAmount;
        var selectedsprovider = data.ServicProvider;
        var selectedsstime = data.ServiceStartTime;

        var servicediscount = data.ServiceDiscount;
        var serviceperc = data.ServiceDiscoutInParentage;

    }

        $('#point1').attr("disabled", false);
        $('#carttable tbody').append('<tr class="child order_popup" id="serivertr"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" id="CommissionAmount" class="CommissionAmount" readonly name="serCommissionAmount[]" value="'+sselectedCommissionAmount+'"><td id="selectedservice"><input type="text" name="ServiceName[]" class="serivename names" id="serivename" readonly value="'+selectedservice+'"><input type="hidden" name="ServiceId[]" readonly value="'+selectedserviceid+'"></td><td id="selectedserviceqyt"><input type="text" class="selectedserviceqytext quantities" readonly value=" - "></td><td id="selectedsprice"><input type="text" name="ServicePrice[]" readonly value="'+selectedsprice+'" class="Prices"></td><td id="selectedservicedis"><input type="number" name="ServiceDiscount[]" class=" discounts selectedservicetext'+selectedserviceid+'" id="selectedservicetext" value="'+servicediscount+'"></td><td id="serviecdiscoinpersntag"><input type="number" name="ServiceDiscoutInParentage[]" class="dper'+selectedserviceid+' pdpers" id="dper" value="'+serviceperc+'" placeholder="0.00"></td><td id="totalservicepricewithall"><input type="text" name="ServiceFianlPrice[]" id="totalservicepricewithalltext" class="totalproductpricewithalltexts servictotal order_total totalservicepricewithalltext'+selectedserviceid+'" readonly value=""></td><td><input type="hidden" name="ServicProvider[]" class="serviceprovider" id="serviceprovider" value="'+selectedsprovider+'"></td><td><input type="hidden" name="ServiceStartTime[]" id="servicetime" value="'+selectedsstime+'"></td></tr>');

        $(document).on('keyup','.selectedservicetext'+selectedserviceid+'',function(){
          
            var servicedisc = $(this).val(); 
            if(servicedisc=='')
            {
                var servicedisc=0;
            }
            var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').val(selectedsprice);
            var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedserviceid+'').attr("data-last-ser",selectedsprice); 
            var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').attr("data-last-ser");
            var totalserviceprice2=parseFloat(selectedserivetotalprice) - parseFloat(servicedisc);
            var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').val(totalserviceprice2);
            totalsericeprice=$('.totalservicepricewithalltext'+selectedserviceid+'').val();
            servicetoalprice();
            addNumbers();
            pointcount();     
            var percount = parseFloat(servicedisc)/parseFloat(selectedsprice);
            var finalpercount = parseFloat(percount)*100;
            var dper = $('.dper'+selectedserviceid+'').val(finalpercount.toFixed(2));  
            if (finalpercount > 100)
            { 
                swal("Sorry no discount more then actual price");
                var e = $.Event('keyup');
                var servicedisc = $(this).val(0); 
                $('.selectedservicetext'+selectedserviceid+'').trigger(e);  
            }    
            var fserviedicount=$('.dper'+selectedserviceid+'').val(); 
            $('#oserviedicount').val(fserviedicount); 
            $('#oservieprice').val(totalsericeprice); 
        });

        $(document).on('keyup','.dper'+selectedserviceid+'',function(){
           
            var servicedisc = $(this).val(); 
            if (servicedisc > 100)
            {
                swal("Sorry no discount more then actual price");
                var e = $.Event('keyup');
                var servicedisc = $(this).val(0);
            }
            else
            {
                if(servicedisc == '')
                {
                    var servicedisc = 0;
                }
                var numberToAdd = (parseFloat(selectedsprice) / 100) * parseFloat(servicedisc)
                var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').val(selectedsprice);
                var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedserviceid+'').attr("data-last-ser",selectedsprice);
                var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').attr("data-last-ser");
                var totalserviceprice2=parseFloat(selectedsprice) - parseFloat(numberToAdd);
                var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').val(totalserviceprice2);
                totalsericeprice=$('.totalservicepricewithalltext'+selectedserviceid+'').val();
                servicetoalprice();
                addNumbers();
                pointcount();
                var dper = $('.selectedservicetext'+selectedserviceid+'').val(numberToAdd.toFixed(2));
                var fserviedicount=$('.selectedservicetext'+selectedserviceid+'').val();
                $('#oserviedicount').val(fserviedicount);
                $('#oservieprice').val(totalsericeprice);
            }
        });

        var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedserviceid+'').val(selectedsprice);
        var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedserviceid+'').attr("data-last-ser",selectedsprice);
        var fserviedicount=$('.dper'+selectedserviceid+'').text(); 
        $('#oserviedicount').val(fserviedicount); 
        $('#finalserciceprice').text(selectedsprice); 
        $('#oservieprice').val(selectedsprice); 
        $(".finallist_first").hide();
        $(".finallist").show();
        servicetoalprice();
        addNumbers();
        pointcount();
        $('.selectedservicetext'+selectedserviceid).trigger('keyup');
    }







    $("#Newproduct").submit(function(e){
     e.preventDefault();
     var id=new Array();
     $('#point1').attr("disabled", false)
     var RepeatProductName='';
     var AllProduct = []; 
       $('.ProdcutId').each(function(){
        AllProduct.push($(this).val());
     })


     $('input[name="listproduct"]:checked').each(function(){

      var ProductTitle = $(this).attr("data-product");
      var ProductPrice = $(this).attr("data-SellingPrice");
      var CommissionAmount = $(this).attr("data-CommissionAmount");
      var NoofPorduct = $(this).attr("data-NoofPorduct");
      var sales_tax = $(this).attr("data-sales_tax");
      var ProductCostPrice = $(this).attr("data-ProductCostPrice");
      var ProdcutId = $(this).val();

     
        if(AllProduct.indexOf(ProdcutId)>-1)
        {
            RepeatProductName+=ProductTitle+' | ';
            
        }else{

        id.push({'ProductTitle':ProductTitle,'ProductPrice':ProductPrice,'ProductCostPrice':ProductCostPrice,'ProdcutId':ProdcutId,'CommissionAmount':CommissionAmount,'NoofPorduct':NoofPorduct,'sales_tax':sales_tax,'ProdcutQuality':1,'ProductDiscount':0,'ProductDiscountInParentage':0});
        }

    });

     if(id == '')
     {
         if(RepeatProductName){
        swal("Already Exist: "+RepeatProductName);
      }else{
          swal("Please select product");
      }
    }
    else if(id!='')
    {
     
      $.each(id, function(k,v)
      {
       AddProductFunction(v);
     });

      totalproductprice=$('#totalproductpricewithalltext').val();

      $('#myModal-Prodcut').modal('toggle');  

      $(".finallist_first").hide();
      $(".finallist").show();
        add()
      counsalestax()
      pointcount();
      addNumbers();

      if(RepeatProductName){
        swal("Already Exist: "+RepeatProductName);
      }

    }

  });


  function add() 
    {
        var sum = 0;
        $(".totalproductpricewithalltext").each(function() {
            var num4=$(this).val();
            var num5 = num4.replace("$ ","");
            var num5 = parseFloat(num5);
            sum += +num5;
        });

      var nump = '$' + sum.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
      $('#finalproductprice').html(nump);
    }

function AddProductFunction(v){

    if(v.sales_tax==1){
        v.onlytax = "<?= $sales_tax ?>";
        ProductTaxPrice = v.ProductPrice*(parseFloat(v.onlytax)/100);
    }else{
        ProductTaxPrice = 0;
        v.onlytax = 0;
    }

    $('#carttable tbody').append('<tr class="child order_popup" id="prodcuttr" data-pid="'+v.ProdcutId+'"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" class="ProdcutId" readonly name="ProdcutId[]" value="'+v.ProdcutId+'"><input type="hidden" class="NoofPorduct" id="NoofPorduct'+v.ProdcutId+'" readonly name="NoofPorduct[]" value="'+v.NoofPorduct+'"><input type="hidden" id="CommissionAmount'+v.ProdcutId+'" class="CommissionAmount" readonly name="proCommissionAmount[]" value="'+v.CommissionAmount+'"><input type="hidden" id="proonlytax'+v.ProdcutId+'" class="proonlytax" readonly name="proonlytax[]" value="'+v.onlytax+'"><input type="hidden" id="ProductCostPrice'+v.ProdcutId+'" name="ProductCostPrice[]" value="'+v.ProductCostPrice+'"><td id="listofcatagory_product" class="listofcatagory_product"><input type="text" class="productname names" readonly name="ProdcutName[]" value="'+v.ProductTitle+'"></td><td id="selectedproqyt'+v.ProdcutId+'" class="selectedproqyt"><input type="number" name="ProdcutQuality[]" min="1" id="selectedproqytext'+v.ProdcutId+'" class="selectedproqytext quantities" data-previous="'+v.ProdcutQuality+'" value="'+v.ProdcutQuality+'"></td><td id="listofcatagory_price'+v.ProdcutId+'" class="listofcatagory_price"><input type="text" readonly name="ProductPrice[]" value="'+v.ProductPrice+'" class="Prices"><input class="ProductTaxPrice" id="ProductTaxPrice'+v.ProdcutId+'" type="text" readonly name="ProductTaxPrice[]" data-tax="'+ProductTaxPrice+'" value="'+ProductTaxPrice.toFixed(2)+'" class="taxPrice"></td><td id="selectedprodis'+v.ProdcutId+'" class="selectedprodis"><input type="number" name="ProductDiscount[]" id="selectedprodistext'+v.ProdcutId+'" class="discount discounts" value="'+v.ProductDiscount+'"></td><td id="prodiscoinpersntag'+v.ProdcutId+'" class="prodiscoinpersntag"><input type="number" name="ProductDiscountInParentage[]" class="pdper pdpers" id="pdper'+v.ProdcutId+'" placeholder="0.00%" value="'+v.ProductDiscountInParentage+'"></td><td id="totalproductpricewithall'+v.ProdcutId+'" class="totalproductpricewithall"><input type="text" name="ProductFianlPrice[]" id="totalproductpricewithalltext'+v.ProdcutId+'" class="totalproductpricewithalltext  totalproductpricewithalltexts order_total" data-id="'+v.ProdcutId+'" readonly value=""></td></tr>');


    if($('#listproduct'+v.ProdcutId).length>0){
      r = $('#listproduct'+v.ProdcutId).attr('data-stock');
      q = $('#selectedproqytext'+v.ProdcutId+'').val();

      if (r < q)
      { 
        swal(v.ProductTitle+": Please Enter Quantity Less than or equal to " + r);        
        $('#selectedproqytext'+v.ProdcutId+'').val(0);
      }

    }else{
      swal('Sorry, '+v.ProductTitle+' product is Out of Stock.')
      $('#selectedproqytext'+v.ProdcutId+'').val(0);
    }

    $(document).on('keyup','#selectedproqytext'+v.ProdcutId+'',function(){
      
    var oldprocount = $('#NoofPorduct'+v.ProdcutId+'').val();
    var qynt = $(this).val();   
    if (parseFloat(oldprocount) < parseFloat(qynt))
    { 
        swal("Please Enter Quantity Less than or equal to " + oldprocount);
        $(this).val($(this).attr("data-previous"));
        return false;

    }else{
      $(this).attr("data-previous",$(this).val());
    }

    if(qynt=='')
    {
       qynt=1; 
       $(this).val(1);
   }
   var totalproductprice1=parseFloat(qynt)*parseFloat(v.ProductPrice)

   var tax_per = $('#proonlytax'+v.ProdcutId).val();
   var ProductTaxPrice = totalproductprice1*(parseFloat(tax_per)/100);
   $("#ProductTaxPrice"+v.ProdcutId).attr('data-tax',ProductTaxPrice);
   $("#ProductTaxPrice"+v.ProdcutId).val(ProductTaxPrice.toFixed(2));

   totalproductprice1 = totalproductprice1+ProductTaxPrice;

   var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice1);
   var selectedproducttotalpricea = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last",totalproductprice1);
   var produdisc = $('#selectedprodistext'+v.ProdcutId+'').val();
   if(produdisc=='')
   {
       var produdisc=0;
   }
   var selectedproducttotalpricea = $('#totalproductpricewithalltext'+v.ProdcutId+'').val();
   var totalproductprice2=parseFloat(selectedproducttotalpricea) - parseFloat(produdisc);
   var selectedproducttotalpricefinal = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice2);
   totalproductprice=$('#totalproductpricewithalltext'+v.ProdcutId+'').val();

   add()
   pointcount();
   addNumbers();

   var selectedproducttotalprice2 = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last");
   var percount = parseFloat(produdisc)/parseFloat(selectedproducttotalprice2);
   var finalpercount = parseFloat(percount)*100;
   var pper = $('#pdper'+v.ProdcutId+'').val(finalpercount.toFixed(2)+'%');
    
    });





     $(document).on('keyup','#pdper'+v.ProdcutId+'',function(){
      
    var produdisc = $(this).val();
    if(produdisc > 100)
    { 
        swal("Sorry no discount more then actual price");
        var e = $.Event('keyup');
        var produdisc = $(this).val(0); 
    }
    else
    {
        if(produdisc == '')
        {
         var produdisc = 0;  
     }
     var numberToAdd = (parseFloat(v.ProductPrice) / 100) * parseFloat(produdisc) 
     var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last");
     var totalproductprice2=parseFloat(selectedproducttotalprice) - parseFloat(numberToAdd);
     var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice2.toFixed(2));
     totalproductprice=$('#totalproductpricewithalltext'+v.ProdcutId+'').val();        
     add()
     pointcount();
     addNumbers();
     var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last");

     var pper = $('#selectedprodistext'+v.ProdcutId+'').val(numberToAdd.toFixed(2));
    }
    });

   




     $(document).on('keyup','#selectedprodistext'+v.ProdcutId+'',function(){
 
    var produdisc = $(this).val();
    if(produdisc=='')
    {
        var produdisc=0;
    }
    var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last");
    var totalproductprice2=parseFloat(selectedproducttotalprice) - parseFloat(produdisc);
    var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice2);
    totalproductprice=$('#totalproductpricewithalltext'+v.ProdcutId+'').val();

    add()
    pointcount();
    addNumbers();
    var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last");
    var percount = parseFloat(produdisc)/parseFloat(selectedproducttotalprice);
    var finalpercount = parseFloat(percount)*100;
    var pper = $('#pdper'+v.ProdcutId+'').val(finalpercount.toFixed(2));  

    if (finalpercount > 100)
    { 
     swal("Sorry no discount more then total product price");
     var e = $.Event('keyup');
     var produdisc = $(this).val(0); 
     $('#selectedprodistext'+v.ProdcutId+'').trigger(e);  
    }    
    });


     var produdisc = $('#selectedprodistext'+v.ProdcutId+'').val();
     if(produdisc=='')
     {
       var produdisc=0;
        }
          var qynt = $('#selectedproqytext'+v.ProdcutId+'').val();
     var totalproductprice1=parseFloat(qynt)*parseFloat(v.ProductPrice)+parseFloat(ProductTaxPrice);
     var selectedproducttotalprice = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice1);
     var selectedproducttotalpricea = $('#totalproductpricewithalltext'+v.ProdcutId+'').attr("data-last",totalproductprice1);

   var selectedproducttotalpricea = $('#totalproductpricewithalltext'+v.ProdcutId+'').val();
   var totalproductprice2=parseFloat(selectedproducttotalpricea) - parseFloat(produdisc);
   var selectedproducttotalpricefinal = $('#totalproductpricewithalltext'+v.ProdcutId+'').val(totalproductprice2);
   $('#selectedprodistext'+v.ProdcutId).trigger('keyup');

}



$("#Newgift").validate({
    rules: {        
        giftamount:{ "required":true, "number" : true }
    },
    messages: {       
        giftamount: {
            "required": "Please Enter Amount for Giftcard",
            "number" : "Please Enter Only Numeric Value"
        } ,
    },submitHandler: function() {
        AddGiftCard($('#giftamount').val());
         $('#modalgift').modal('toggle');
    }       
});

function AddGiftCard(val){

        var selectedservice = "Giftcard";
        var selectedserviceid = "1";
        var oservicename=$('#oservicename').val(selectedservice);
        var selectedsprice = val;
        var sselectedCommissionAmount= "0";
        var selectedsstime = "0";
        $('#point1').attr("disabled", false)
        $('#carttable tbody').append('<tr class="child order_popup" id="serivertr"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" id="CommissionAmount" class="CommissionAmount" readonly name="gserCommissionAmount[]" value="'+sselectedCommissionAmount+'"><td id="selectedservice"><input type="text" name="gServiceName[]" class="serivename names" id="serivename" readonly value="'+selectedservice+'"><input type="hidden" name="gServiceId[]" readonly value="'+selectedserviceid+'"></td><td id="selectedserviceqyt"><input type="text" class="selectedserviceqytext quantities" readonly value=" - "></td><td id="selectedsprice"><input type="text" name="gServicePrice[]" readonly value="'+selectedsprice+'" class="Prices"></td><td id="selectedservicedis"><input type="number" name="gServiceDiscount[]" class=" discounts selectedservicetext'+selectedserviceid+'" id="selectedservicetext" value="0" readonly></td><td id="serviecdiscoinpersntag"><input type="number" name="gServiceDiscoutInParentage[]" class="dper'+selectedserviceid+' pdpers" id="dper" value="0.00" placeholder="0.00" readonly></td><td id="totalservicepricewithall"><input type="text" name="gServiceFianlPrice[]" id="totalservicepricewithalltext" class="totalproductpricewithalltexts gservictotal order_total totalservicepricewithalltext'+selectedsprice+'" readonly value=""></td></tr>');

//     $(document).on('keyup','.selectedservicetext'+selectedserviceid+'',function(){
       
//       var servicedisc = $(this).val(); 
//       if(servicedisc=='')
//       {
//           var servicedisc=0;
//       }
//       var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').val(selectedsprice);
//       var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedsprice+'').attr("data-last-ser",selectedsprice); 
//       var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').attr("data-last-ser");
//       var totalserviceprice2=parseFloat(selectedserivetotalprice) - parseFloat(servicedisc);
//       var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').val(totalserviceprice2);
//       totalsericeprice=$('.totalservicepricewithalltext'+selectedsprice+'').val();
//       gifttoalprice();
//       addNumbers();
//       pointcount();



//       var percount = parseFloat(servicedisc)/parseFloat(selectedsprice);

    

//       var finalpercount = parseFloat(percount)*100;

//       var dper = $('.dper'+selectedserviceid+'').val(finalpercount.toFixed(2));  
//       if (finalpercount > 100)
//       { 
//           swal("Sorry no discount more then actual price");
//           var e = $.Event('keyup');
//           var servicedisc = $(this).val(0); 
//           $('.selectedservicetext'+selectedserviceid+'').trigger(e);  
//       }    
//       var fserviedicount=$('.dper'+selectedserviceid+'').val(); 

//       $('#oserviedicount').val(fserviedicount); 
//       $('#oservieprice').val(totalsericeprice); 
//   });


//     $(document).on('keyup','.dper'+selectedserviceid+'',function(){

//       var servicedisc = $(this).val(); 

//       if (servicedisc > 100)
//       { 
//           swal("Sorry no discount more then actual price");
//           var e = $.Event('keyup');
//           var servicedisc = $(this).val(0); 
//       }
//       else
//       { 
//           if(servicedisc == '')
//           {
//            var servicedisc = 0;  
//        }

//        var numberToAdd = (parseFloat(selectedsprice) / 100) * parseFloat(servicedisc)
//        var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').val(selectedsprice);
//        var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedsprice+'').attr("data-last-ser",selectedsprice); 
//        var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').attr("data-last-ser");

//        var totalserviceprice2=parseFloat(selectedsprice) - parseFloat(numberToAdd);
//        var selectedserivetotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').val(totalserviceprice2);
//        totalsericeprice=$('.totalservicepricewithalltext'+selectedsprice+'').val();
//        gifttoalprice();
//        addNumbers();
//        pointcount();


//        var dper = $('.selectedservicetext'+selectedserviceid+'').val(numberToAdd.toFixed(2));  
//        var fserviedicount=$('.selectedservicetext'+selectedserviceid+'').val(); 
//        $('#oserviedicount').val(fserviedicount); 
//        $('#oservieprice').val(totalsericeprice); 


//    }


// });           

        var selectedproducttotalprice = $('.totalservicepricewithalltext'+selectedsprice+'').val(selectedsprice);
        var selectedproducttotalpricea = $('.totalservicepricewithalltext'+selectedsprice+'').attr("data-last-ser",selectedsprice); 
        var fserviedicount=$('.dper'+selectedserviceid+'').text(); 
        $('#oserviedicount').val(fserviedicount); 
        $('#finalgiftprice').text(selectedsprice); 
        $('#oservieprice').val(selectedsprice); 
        $(".finallist_first").hide();
        $(".finallist").show();
        gifttoalprice();
        addNumbers();
        pointcount();
         $('.selectedservicetext'+selectedserviceid).trigger('keyup');
}


$("#Newmembership").validate({
    rules: {        
        listofcatagory4: "required",
    },
    messages: {       
        listofcatagory4:  "Please select package",
    },submitHandler: function() {

        if($("#Package_Autonew").prop('checked') == true)
      {
        var Package_Autonew = $('#Package_Autonew').val()
      }
      else
      {
        var Package_Autonew = 'No'; 
      }

      if($("#Pckage_carryford").prop('checked') == true)
      {
        var Pckage_carryford = $('#Pckage_carryford').val()
      }
      else
      {
        var Pckage_carryford = 'No'; 
      }

      var Package_renwal = $('#listofcatagory2_package_renwal').val();
       var Noofvisit = $('.count').val();
      var package_expire_date = $('#package_expire_date').val()
      var MembershipPrice = $('#PckageAmount').val();
      var Name = $('#select2-listofcatagory4-container').text();
      var MembershipId = $('#listofcatagory4').val();
      var omembershipname = $('#omembershipname').val(Name);
      var MembershipPrice = $('#PckageAmount').val();
      var CommissionAmount= $('#mCommissionAmount').val();
      var omembershipacutalprice = $('#omembershipacutalprice').val(MembershipPrice);
      var MembershipDiscount = 0;
      var MemberDiscoutInParentage = 0;

      var data =[];

      data.push({"MembershipId":MembershipId,"CommissionAmount":CommissionAmount,"Name":Name,"Noofvisit":Noofvisit,"package_expire_date":package_expire_date,"MembershipPrice":MembershipPrice,"MembershipDiscount":MembershipDiscount,"MemberDiscoutInParentage":MemberDiscoutInParentage,"Pckage_carryford":Pckage_carryford,"Package_Autonew":Package_Autonew,"Package_renwal":Package_renwal});


        AddMembership(data[0]);
         $('#myModal-Membership').modal('toggle');
  
  }
});



function AddMembership(v){

      $('#point1').attr("disabled", false);

      $('#carttable tbody').append('<tr class="child order_popup" id="membershiptr"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" id="CommissionAmount" class="CommissionAmount" readonly name="memCommissionAmount" value="'+v.CommissionAmount+'"><td id="selectedmembership"><input type="text" class="names" name="MembershipName" readonly value="'+v.Name+'"><input type="hidden" name="noofvisit[]" readonly value="'+v.Noofvisit+'"><input type="hidden" name="autorrwnew[]" readonly value="'+v.Package_Autonew+'"><input type="hidden" name="renewalon[]" readonly value="'+v.Package_renwal+'"><input type="hidden" name="carryforwed[]" readonly value="'+v.Pckage_carryford+'"><input type="hidden" name="packageexpriydate[]" readonly value="'+v.package_expire_date+'"><input type="hidden" name="selectedsprice[]" readonly value="'+v.MembershipPrice+'"><input type="hidden" name="MembershipId[]" readonly value="'+v.MembershipId+'"></td><td id="selectedmemqyt"><input type="text" class="selectedmemqytext quantities" readonly value=" - "></td><td id="selectedmprice"><input type="text" name="MembershipPrice[]" readonly value="'+v.MembershipPrice+'" class="Prices memberOrgPrice'+ v.MembershipId+'"></td><td id="selectedmemdis"><input type="number" name="MembershipDiscount[]" class="discounts selectedmemberetext'+v.MembershipId+'" id="selectedmemberetext" value="'+v.MembershipDiscount+'"></td><td id="memdiscoinpersntag"><input type="number" class="pdpers mdper'+v.MembershipId+'" id="mdper" name="MemberDiscoutInParentage[]" placeholder="0.00" value="'+v.MemberDiscoutInParentage+'"></td><td id="totalmembershippricewithall"><input type="text" class="membershiptotal order_total totalproductpricewithalltexts totalmemberpricewithalltext'+v.MembershipId+'" id="totalmemberpricewithalltext" name="MembershipFianlPrice[]" readonly value=""></td></tr>');

      $(document).on('keyup','.selectedmemberetext'+v.MembershipId+'',function(){
       
        var memdisc = $(this).val();
        if(memdisc=='')
        {
            var memdisc=0;
        }
        var selectedproducttotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').val(v.MembershipPrice);
        var selectedproducttotalpricea = $('.totalmemberpricewithalltext'+v.MembershipId+'').attr("data-last-ser",v.MembershipPrice);
        var selectedserivetotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').attr("data-last-ser");
        var totalserviceprice2=parseFloat(selectedserivetotalprice) - parseFloat(memdisc);
        var selectedserivetotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').val(totalserviceprice2);
        totalsericeprice=$('.totalmemberpricewithalltext'+v.MembershipId+'').val();
        membershiptoalprice();
        addNumbers();
        pointcount();
        var percount = parseFloat(memdisc)/parseFloat(v.MembershipPrice);
        var finalpercount = parseFloat(percount)*100;
        var dper = $('.mdper'+v.MembershipId+'').val(finalpercount.toFixed(2));    
        if (finalpercount > 100)
        { 
            swal("Sorry no discount more then actual price");
            var e = $.Event('keyup');
            var memdisc = $(this).val(0); 
            $('.selectedmemberetext'+v.MembershipId+'').trigger(e);  
        }    
        var fserviedicount=$('.mdper'+v.MembershipId+'').val(); 
        $('#omemberdiscount').val(fserviedicount); 
        $('#omembershipprice').val(totalsericeprice); 
      });

      $(document).on('keyup','.mdper'+v.MembershipId+'',function(){
       
        var memdisc = $(this).val(); 
        if (memdisc > 100)
        { 
            swal("Sorry no discount more then actual price");
            var e = $.Event('keyup');
            var memdisc = $(this).val(0); 
        }
        else
        { 
            if(memdisc == '')
            {
                var memdisc = 0;  
            }
            var numberToAdd = (parseFloat(v.MembershipPrice) / 100) * parseFloat(memdisc)
            var selectedproducttotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').val(v.MembershipPrice);
            var selectedproducttotalpricea = $('.totalmemberpricewithalltext'+v.MembershipId+'').attr("data-last-ser",v.MembershipPrice);  
            var selectedserivetotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').attr("data-last-ser");
            var totalserviceprice2=parseFloat(v.MembershipPrice) - parseFloat(numberToAdd);
            var selectedserivetotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').val(totalserviceprice2);
            totalsericeprice=$('.totalmemberpricewithalltext'+v.MembershipId+'').val();
            membershiptoalprice();
            addNumbers();
            pointcount();

              var dper = $('.selectedmemberetext'+v.MembershipId+'').val(numberToAdd.toFixed(2));    
              var fserviedicount=$('.mdper'+v.MembershipId+'').val(); 
              $('#omemberdiscount').val(fserviedicount); 
              $('#omembershipprice').val(totalsericeprice); 
          }
      });

      var selectedproducttotalprice = $('.totalmemberpricewithalltext'+v.MembershipId+'').val(v.MembershipPrice);
      var selectedproducttotalpricea = $('.totalmemberpricewithalltext'+v.MembershipId+'').attr("data-last-memb",v.MembershipPrice); 
      var fserviedicount=$('#mdper').val(); 
      $('#omemberdiscount').val(fserviedicount); 
      $('#omembershipprice').val(v.MembershipPrice); 
      $('#finalmembershipprice').text(v.MembershipPrice); 
     
      $(".finallist_first").hide();
      $(".finallist").show();
      membershiptoalprice()
      addNumbers();
      pointcount();
      $(".selectedmemberetext"+v.MembershipId).trigger('keyup');
}


 $(document).on("click","#add_memberships_save_order2",function()
       {
           $('.Loader').show()
           var remainspackage = $('.count4').val();
           var selelctedvalu = $('.count2').val();
           var memboackja_first =  $('.sselectedpackagename').data('id')
           jQuery.ajax({
            dataType:"json",
            type:"post",
            data: {'memboackja_first':memboackja_first},
            url:'?action=Remainingcountat_first',
            success: function(data)
            {
             $('.Loader').hide();
             var orignalvalifromv = data.resonse;
             if(parseInt(selelctedvalu)>parseInt(data.resonse))
             {
              swal("Sorry you can't book more than remaining visit");
              $('.count2').val(0);
              $('.count3').text(data.resonse);
          }

          if($('#listofavliaasfpackag').val()=='')
          {
              swal('Please select package')
          }
          else if($('#listofavliaasfpackag2').val()=='')
          {
              swal('Please select service name')
          }
          else if($('.count2').val()==0)
          {
              swal('Please enter number of visit') 
          }
          else if(parseInt(selelctedvalu)>parseInt(remainspackage))
          {
              swal("Sorry you can't book more than remaining visit");
              $('.count2').val(0);
              $('.count3').text(data.resonse);
          }
          else
          {
              var currenttimeser =  $('#serivename').val()

              $('.finallist').show()
              $('.finallist_first').hide()

              $('#total_one').attr("id","total_one")
              $("#NewOrder").attr("id", "NewOrder");
              addNumbers();


              var pservicename = $('#select2-listofavliaasfpackag2-container').text();



              if(currenttimeser==pservicename)
              {
                 swal("This service already selected")
             }
             else
             {

                 var pservicepackage = $('.sselectedpackagename').attr('data-id');
                 var pvisit = $('.count2').val();
                 var data = [];
                 data.push({"pservicename":pservicename,"pservicepackage":pservicepackage,"pvisit":pvisit});
                 AddPackage(data[0]);

                 $('#myModal_packagedetalsisfs').modal('hide')
             }

         }


     }
 });

       });


       function AddPackage(v){
            
       
        var packID = v.pservicepackage.split(",");
                 var pvisit = v.pvisit.split(",");
                 var pservicename = v.pservicename.split(",");
                 for(i in packID){

                  var packageid = packID[i];
                  var noofvisit = pvisit[i];
                  var pservicename = pservicename[i];
                  $('#carttable tbody').append('<tr class="child order_popup" id="serivertr"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" id="CommissionAmount" class="CommissionAmount" readonly name="serCommissionAmount[]" value="0"><td><input type="text" name="pservicename[]" class="pservicename names" id="pservicename" readonly value="'+pservicename+'"><input type="hidden" name="pservicepackage[]" readonly value="'+packageid+'"></td><td><input type="text" name="pvisit[]" class="selectedserviceqytext quantities" readonly value="'+noofvisit+'"></td><td><input type="text" readonly value="0" class="Prices"></td><td><input class="discounts" type="text" value="0" readonly></td><td><input id="dper" class="pdpers"  type="text" readonly value="0.00"></td><td><input class="order_total totalproductpricewithalltexts" type="text" readonly value="0"></td></tr>');
                }
       }










$(document).on('click','.plus2',function(){
  Servicename_package=$('#listofavliaasfpackag').val();
  if(Servicename_package!='')
  {
    $('.count2').val(parseInt($('.count2').val()) + 1 );
    $('.count3').text(parseInt($('.count3').text()) - 1 );
    if ($('.count3').text() == -1) 
    {
      $('.count2').val(0);
      $('.count3').text($('.sselectedpackagename').data('visit'))
    }
  }
  else
  {
    swal("Please select package")
  }
});


$(document).on('click','.minus2',function(){
  Servicename_package=$('#listofavliaasfpackag').val();
  if(Servicename_package!='')
  {
    $('.count2').val(parseInt($('.count2').val()) - 1 );
    $('.count3').text(parseInt($('.count3').text()) + 1 );
    if ($('.count2').val() == -1) 
    {
      $('.count2').val(0);
      $('.count3').text($('.sselectedpackagename').data('visit'))
    }
  }
  else
  {
    swal("Please select package")
  }
});


$(document).on('keyup','.count2',function(e){
  Servicename_package=$('#listofavliaasfpackag').val();
  if(Servicename_package!='')
  {
    var remainspackage = $('.count4').val();
    var selelctedvalu = $(this).val();
    if(parseInt(selelctedvalu)>parseInt(remainspackage))
    {
      swal("Sorry you can't book more than remaining visit")
      $(this).val(0);
      $('.count3').text(remainspackage);
    }
    else
    {
      var newremainval = parseInt(remainspackage)-parseInt(selelctedvalu)
      $('.count3').text(newremainval);
    }
  }
  else
  {
    swal("Please select package")
  }
  if(selelctedvalu =='')
  {

        $('.count3').text(remainspackage);
    }
});
$(".count2").keypress(function (e) {

  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

    return false;
}
});

$(document).on('click','.plus',function(){

  Servicename=$('#listofcatagory4').val();
  if(Servicename!='')
  {
    var PckageAmount  = $('.PckageAmount').data('packageprice');
    $('.count').val(parseInt($('.count').val()) + 1 );

    }
    else
    {
     swal("Please select service")
 }
});

$(document).on('click','.minus',function(){
  Servicename=$('#listofcatagory4').val();
  if(Servicename!='')
  {
    var PckageAmount  = $('.PckageAmount').data('packageprice');
    $('.count').val(parseInt($('.count').val()) - 1 );

    }
    else
    {
     swal("Please select service")
 }

 if ($('.count').val() == 0) 
 {
     $('.count').val(1);

 }

});

$(document).on('keyup','.count',function(e){
  if($('#listofcatagory4').val()=='')  
  {
    swal('Please select Package name')
  }
  else
  {
    var PckageAmount  = $('.PckageAmount').data('packageprice'); 
    var newamount = parseInt(PckageAmount) * parseInt($('.count').val())
    $('.PckageAmount').val(newamount);
    if ($('.count').val() == '') 
    {
      $('.count').val(1);
      var newamount = parseInt(PckageAmount) * parseInt($('.count').val())
      $('.PckageAmount').val(newamount);
    }
  }
});

$("#Package_Autonew").click( function(){
  if( $(this).is(':checked') )
  {
    $('.Renewaltr').show()
    $('#package_expire_date').attr("disabled", true).val('Unlimited') 
  }
  else
  {
    $('.Renewaltr').hide()
    $('#package_expire_date').attr("disabled", false).val('Never') 
  }
});
$('#listofcatagory2_package_renwal').on('change',function(){
  if($(this).val()!='completed')
  {
    $('.package_carfor_p').show() 
  }
  else
  {
    $('.package_carfor_p').hide()  
  }
})   







$(document).on('keyup','#point1',function(){
  if (/\D/g.test(this.value))
  {
    this.value = this.value.replace(/\D/g, '');
  }
  var alrday_point = '<?php echo $points; ?>';
  var memdisc_point = $(this).val(); 
  if(parseFloat(memdisc_point) > parseFloat(alrday_point))
  { 
    swal("Sorry can't use points more then remaining points");
    var e = $.Event('keyup');
    var memdisc_point = $(this).val(0); 
    $('#point1').trigger(e);  
  }    
  var total = 0;
  $('input.CommissionAmount').each(function() {
    var num = parseInt(this.value, 10);
    if(!isNaN(num))
    {
      total += num;
    }
    $("#CommissionAmount2").val(total);
  });
  var usepoint = $(this).val();
  if(usepoint == '')
  {
    var usepoint=0;
  }
  var alrday = '<?php echo $points; ?>';
  if(!alrday)
  {
    alrday = 0;
  }
  var remainepoint = parseFloat(alrday)-parseFloat(usepoint);
  var getpoint=total;
  if(getpoint=='')
  {
    var getpoint=0;
  }
  var finalpoint = parseFloat(remainepoint)+parseFloat(getpoint);

  $("#points").val(finalpoint);
  addNumbers()
});

var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
$('.js-switch').each(function() {
  new Switchery($(this)[0], $(this).data());
});

$(".select2").select2();
$('.selectpicker').selectpicker();

$(".vertical-spin").TouchSpin({
  verticalbuttons: true,
  verticalupclass: 'ti-plus',
  verticaldownclass: 'ti-minus'
});
var vspinTrue = $(".vertical-spin").TouchSpin({
  verticalbuttons: true
});
if (vspinTrue) {
  $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
}
$("input[name='tch1']").TouchSpin({
  min: 0,
  max: 100,
  step: 0.1,
  decimals: 2,
  boostat: 5,
  maxboostedstep: 10,
  postfix: '%'
});
$("input[name='tch2']").TouchSpin({
  min: -1000000000,
  max: 1000000000,
  stepinterval: 50,
  maxboostedstep: 10000000,
  prefix: '$'
});
$("input[name='tch3']").TouchSpin();
$("input[name='tch3_22']").TouchSpin({
  initval: 40
});
$("input[name='tch5']").TouchSpin({
  prefix: "pre",
  postfix: "post"
});

$('#pre-selected-options').multiSelect();
$('#optgroup').multiSelect({
 selectableOptgroup: true
});
$('#public-methods').multiSelect();
$('#select-all').click(function() {
 $('#public-methods').multiSelect('select_all');
 return false;
});
$('#deselect-all').click(function() {
 $('#public-methods').multiSelect('deselect_all');
 return false;
});
$('#refresh').on('click', function() {
 $('#public-methods').multiSelect('refresh');
 return false;
});
$('#add-option').on('click', function() {
 $('#public-methods').multiSelect('addOption', {
  value: 42,
  text: 'test 42',
  index: 0
});
 return false;
});
$(".ajax").select2({
 ajax: {
  url: "https://api.github.com/search/repositories",
  dataType: 'json',
  delay: 250,
  data: function(params) {
   return {
    q: params.term, 
    page: params.page
};
},
processResults: function(data, params) {

    params.page = params.page || 1;
    return {
     results: data.items,
     pagination: {
      more: (params.page * 30) < data.total_count
  }
};
},
cache: true
},
escapeMarkup: function(markup) {
 return markup;
}, 
minimumInputLength: 1,
templateResult: formatRepo, 
templateSelection: formatRepoSelection 
});

});

// $('#scrollDefaultExample').timepicker({ 'scrollDefault': 'now' });

function servicetoalprice()
{
  var stotal = 0;
  $('input.servictotal').each(function() {

   var num2=$(this).val();
   var num = num2.replace("$ ","");
   var num = parseFloat(num);

   if (!isNaN(num)) {
    stotal += num;
}
}); 

  var nums = '$' + stotal.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

  $('#finalserciceprice').text(nums);
}

function gifttoalprice()
{
  var stotal = 0;
  $('input.gservictotal').each(function() {
    var num2=$(this).val();
    var num = num2.replace("$ ","");
    var num = parseFloat(num);
    if (!isNaN(num)) {
      stotal += num;
    }
  });
  var nums = '$' + stotal.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  $('#finalgiftprice').text(nums);
}

function membershiptoalprice()
{
  var mtotal = 0;
  $('input.membershiptotal').each(function() {

       var num2=$(this).val();
       var num = num2.replace("$ ","");
       var num = parseFloat(num);

       if (!isNaN(num)) {
        mtotal += num;
    }
}); 
  var numm = '$' + mtotal.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  $('#finalmembershipprice').text(numm);
}

function pointcount()
{
  var total = 0;
  $('input.CommissionAmount').each(function() {
   var num = parseInt(this.value, 10);
   if (!isNaN(num)) {
    total += num;
}
$("#CommissionAmount2").val(total);
});     
  var usepoint = $('#point1').val();
  if(usepoint=='')
  {
   var usepoint=0;
}
var alrday = '<?php echo $points; ?>';
if(!alrday)
{
   alrday = 0;
}

var remainepoint = parseFloat(alrday) - parseFloat(usepoint);

var getpoint=total;
if(getpoint=='')
{
   var getpoint=0;
}
var finalpoint = parseFloat(remainepoint)+parseFloat(getpoint);

$("#points").val(finalpoint);
}

function counsalestax()
{

    var staxtotal=0;
    $('.ProdcutId').each(function() {
        var id = $(this).val();
        var tax = parseFloat($("#proonlytax"+id).val());
        var totalprice = parseFloat($("#totalproductpricewithalltext"+id).val().replace('$',''));
        staxtotal = parseFloat(  parseFloat(staxtotal) + totalprice*(tax/100)).toFixed(2);
    });

    $("#salestax").text('$'+staxtotal);
}

function addNumbers(event='mytips')
{   
  var number1 = $('#finalserciceprice').text();
  var items1 = Number(number1.replace(/[^0-9.-]+/g,""));
  var ototalsercieprice = $('#ototalsercieprice').val(items1);

  var number0 = $('#finalgiftprice').text();
  var items0 = Number(number0.replace(/[^0-9.-]+/g,""));
  var ototalgift = $('#ototalgiftprice').val(items0);

  var number2 = $('#finalproductprice').text();
  var items2 = Number(number2.replace(/[^0-9.-]+/g,""));
  var ototalproduct = $('#ototalproduct').val(items2);

  var number3 = $('#finalmembershipprice').text();
  var items3 = Number(number3.replace(/[^0-9.-]+/g,""));
  var ototalmembership = $('#ototalmembership').val(items3);

  var items4 = $('#point1').val();

  if(items1=='')
  {
    items1=0;
  }
  if(items0=='')
  {
    items0=0; 
  } 
  if(items2=='')
  {
    items2=0;
  } 
  if(items3=='')
  {
    items3=0;
  }
  if(items4=='')
  {
    items4=0;
  } 

  var res = $("#salestax").text().replace("$", " ");
  if(res=='')
  {
    res = 0;
  }

  $("#sales_tax").val(res);


  var onlyserandmemberpri = parseFloat(items1) +  parseFloat(items3) + parseFloat(items0) + parseFloat(items2);
  if(onlyserandmemberpri!='')
  {
    $('#mytips').attr("disabled", false) 
    $('#mytipsper').attr("disabled", false) 


    var numberToAdd18 = (onlyserandmemberpri / 100) * parseFloat(18);
    var numberToAdd15 = (onlyserandmemberpri / 100) * parseFloat(15);
    var numberToAdd20 = (onlyserandmemberpri / 100) * parseFloat(20);
    $('#listoftips').html('')
    $('#listoftips').append("<option value='0'>Choose % </option><option value="+numberToAdd15+">15% = "+'$ '+numberToAdd15+"</option><option value="+numberToAdd18+">18% = "+'$ '+numberToAdd18+"</option><option value="+numberToAdd20+">20% = "+'$ '+numberToAdd20+"</option>")

    var mytips = $('#mytips').val()
    var tipsper = $('#mytipsper').val()

    if(event=='mytips'){

      if(mytips=='')
      {
        var mytips=0;
        $('#mytipsper').val(0);
      }
      else
      {
        var tipspersatage = parseFloat(mytips)/parseFloat(onlyserandmemberpri);
        var tipspersatagef = parseFloat(tipspersatage)*100;
        $('#mytipsper').val(tipspersatagef.toFixed(2));

      }
    }else{

      if(tipsper=='')
      {
        $('#mytips').val(0.00);
        var mytips=0;
      }
      else
      {
        var tipspersatage = parseFloat(tipsper)/100;
        var tipspersatagef = parseFloat(tipspersatage)*parseFloat(onlyserandmemberpri);
        $('#mytips').val(tipspersatagef.toFixed(2))
        mytips = tipspersatagef.toFixed(2);

      }
    }

  }
  else
  {
    var mytips=0;
  }

  var total_amount_item1 = parseFloat(items1)+parseFloat(items0) + parseFloat(items2) + parseFloat(items3) + parseFloat(mytips);
  var total_amount_item = parseFloat(total_amount_item1) - parseFloat(items4);
  var num = '$' + total_amount_item.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  $('#totalamount').text(num);
  var ototalorderprice = $('#ototalorderprice').val(total_amount_item);
    counsalestax();
}

$(document).on('keyup','#mytips,#mytipsper',function(e){

  $('#tips').val($(this).val())

  if(e.target.id=="mytips"){
    addNumbers();
  }else{
    addNumbers("mytipsper");
  }

});

$(document).on('click','#mytips,#mytipsper',function(){

  $('#listoftips').show();
});


$(document).on('change','#listoftips',function(){

  $('#mytips').val($(this).val());
  $('#tips').val($(this).val());
  addNumbers()
});


$('#total_one').on('click',function(e){
  e.preventDefault()
  $(".giftdiv").hide();
  $('#carttable2 tr.child.order_popup').html('');
  var paytoken = '<?php echo $token; ?>';
  var listofcatagory=$('#listofcatagory').val();
  var rowCount = $('#carttable tr').length;
  $('#listofcatagory_error').text("");
  if(listofcatagory=='')
  {    
    $('#listofcatagory_error').text("Please select client");
  }
  else if(rowCount<=1)
  {
    swal("Please add service,prodcut or membership in cart");
  }   
  else
  {  
    var SEND_EMAIL = $('input[name="SEND_EMAIL"]:checked').val();
    $("#SEND_EMAIL").val(SEND_EMAIL);
    if( SEND_EMAIL!=''){
      var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if(!regex.test(SEND_EMAIL)) {
        swal("Format Invalid of selected Email for Invoice.");
        return false;
      }   
    }

    point1 =  $("#point1").val();
    point12=  $(".point1").text(point1);
    $("#carttable tr.order_popup").each(function() {
      var names = $(this).find("input.names").val(),
      quantities = $(this).find("input.quantities").val(),
      Prices = $(this).find("input.Prices").val(),
      discounts = $(this).find("input.discounts").val(),
      pdpers = $(this).find("input.pdpers").val(),
      totalproductpricewithalltexts = $(this).find("input.totalproductpricewithalltexts").val(),
      ProductTaxPrice = $(this).find("input.ProductTaxPrice").val();

      if(ProductTaxPrice){
        ProductTaxPriceText = '<input class="ProductTaxPrice" type="text" readonly="" name="ProdcutName[]"  value="'+ProductTaxPrice+'">';
      }else{
        ProductTaxPriceText = "";
      }

      finalserciceprice =  $("#finalserciceprice").text();
      finalserciceprice2 =  $(".finalserciceprices").text(finalserciceprice);

      finalgiftprice =  $("#finalgiftprice").text();
      finalgiftprice2 =  $(".finalgiftprices").text(finalgiftprice);

      finalproductprice =  $("#finalproductprice").text();
      finalproductprice2=  $(".finalproductprices").text(finalproductprice);

      finalmembershipprice =  $("#finalmembershipprice").text();
      finalmembershipprice2=  $(".finalmembershipprices").text(finalmembershipprice);

      totalamount =  $("#totalamount").text();
      totalamount2=  $(".totalamounts").text(totalamount);



      $(".salestaxs").text($("#salestax").text());

      if($("#mytips").val()){
        $(".mytipses").text("$ "+$("#mytips").val());
      }

      $('#carttable2 tbody').append('<tr class="child order_popup"><td><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+names+'"></td><td><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+quantities+'"></td><td style="position:relative"><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+Prices+'">'+ProductTaxPriceText+'</td><td><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+discounts+'"></td><td><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+pdpers+'"></td><td><input type="text" class="productname names" readonly="" name="ProdcutName[]" value="'+totalproductpricewithalltexts+'"></td></tr>') 
    });
    $('#myModal_ordersummery').modal('show'); 
  }
});


// $(".closespty").click(function(){

//      var urlParams = new URLSearchParams(window.location.search);

//      var action = urlParams.get('action'); 

//      if(action!='edit'){
//       delete_order();
//      }


// });

// function delete_order(){


//   $(".Loader").show();
//   var NewOrderId = $('#totalpay').attr('neworderid');
//   $.ajax({
//     dataType:"json",
//     type:"post",
//     data:{id:NewOrderId},
//     url:'<?php echo EXEC; ?>Exec_Edit_Order?remove_order_table',
//     success: function(data)
//     {
//       $(".Loader").hide();
//       if(data.response){
//         swal("",data.response,"info");
//       }
//     }
//   });
// }


$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
  });



$('#total').on('click',function(e){
  e.preventDefault();

  var formid = $('.NewOrder').attr('id');
  if(formid!='NewOrder2')
  {
    var paytoken = '<?php echo $token; ?>';
    var listofcatagory=$('#listofcatagory').val();
    var rowCount = $('#carttable tr').length;            
    $('#listofcatagory_error').text("");
    if(listofcatagory=='')
    {
      $('#listofcatagory_error').text("Please select client");
    }
    else if(rowCount<=1)
    {
      swal("Please Add Service,Prodcut or Membership in cart");
    }
    else
    {
      $(".modal").show();
      $(".Loader").show();
      var data = $("#NewOrder").serialize();

      var myfinalypay = $('#totalamount').text();

      $('#totalpay').text(myfinalypay);
      $('.totalpay2').val(myfinalypay.replace('$',''));

      if($('.totalpay2').val()==0.00){
        data+= "&payment_status=CAPTURED&giftapplied=1";
      }else{
        data+= "&payment_status=PENDING";
      }

      data= data + "&Action=Order";
      textgift = $("#textgift").val();
      data = data + "&giftapp="+textgift;


            $.ajax({
             dataType:"json",
             type:"post",
             data:data,
             url:'<?php echo EXEC; ?>Exec_Edit_Order',
             success: function(data)
             {
              if(data.resonse)
              {
               $('#NewNote').each(function(){
                this.reset();
              });
               $(".Loader").hide();
               $(".modal").hide();

               if($('.totalpay2').val()==0.00){

                var remain_GiftCard = parseFloat($(".giftbal").text().replace("$",""))-parseFloat($(".gift_apply").text().replace("$",""))

                if(remain_GiftCard){
                 swal("Order Successful!","You have $"+remain_GiftCard.toFixed(2)+" amount on your Gift Card","success");
               }

               setTimeout(function () { 
                $("#GiftCard_ocid").val($("#ocid").val());
                $("#GiftCard_totalpay2").val(0.00);
                $("#GiftCardPayment").submit();
              }, 3000);

             }else{

            $('#NewOrder #id').val(data.NewOrderId);
             $("#CashButton").show();
             $("#ChequeButton").show();
            $("#card").val('');
            $("#cash").val('');
            $("#SplitCashID").val('new');
            $('#paymenttypemodel').modal('show');

            $("#saved_resonse").fadeIn().delay(10000).fadeOut();;
            $('#saved_resonsemsg').html('<span>Your order has been saved to \'Save for Later\' section of Orderlist page. To continue your order, please payment now.</span>');
        }
    }
    else if(data.error)
    {
       $("#error").show();
       $('#errormsg').html('<span>'+data.error+'</span>');
       $(".Loader").hide();

   }
   else if(data.csrf_error)
   {
     $("#csrf_error").show();
     $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
     $(".Loader").hide();
     $("#myModal_ordersummery").modal('hide');
     setTimeout(function () { window.location.href="<?= base_url?>/OrderList" }, 3000);
 }
}
});
        }
    }
    else
    {
     var Remainingcountat = $('.count3').text()
     var memboackja =  $('.sselectedpackagename').data('id')
     var package = '<?php if(isset($package)){ echo $package; } ?>';
     var servicename = $(".serivename").val();
     var packagename = $(".sselectedpackagename").text();
     $("#orderpservice").val("1");
     var data = $("#NewOrder2").serialize();
     data = data + "&pservicepackage="+packagename+"&pservicename="+servicename;
     $.ajax({
      dataType:"json",
      type:"post",
      data:data,
      url:'<?php echo EXEC; ?>Exec_Edit_Order',
      success: function(data)
      {
      }
  });


     if(package != '')
     {
       memboackja = package;
   }
   var remain = '<?php if(isset($remain)){ echo $remain; } ?>';
   if(remain != '')
   {
       Remainingcountat = remain;
   }
   $(".Loader").show();
   jQuery.ajax({
       dataType:"json",
       type:"post",
       data: {'Remainingcountat':Remainingcountat,'memboackja':memboackja},
       url:'?action=Remainingcountat',
       success: function(data)
       {
        if(data.resonse)
        {
         $(".Loader").hide();
         swal(data.resonse)
         setTimeout(function () { window.location.href="<?= base_url?>/OrderList" }, 3000);
     }
 }
});
}
});


$('#saveforlatter').on('click',function(e){
  e.preventDefault() 

  var paytoken = '<?php echo $token; ?>';
  var listofcatagory=$('#listofcatagory').val();
  var rowCount = $('#carttable tr').length;            
  $('#listofcatagory_error').text("");
  if(listofcatagory=='')
  {    
    $('#listofcatagory_error').text("Please select client");
  }
  else if(rowCount<=1)
  {
    swal("Please Add Service,Prodcut or Membership in cart");
  }
  else
  {
    $(".modal").show();
    $(".Loader").show();
    var data = $("#NewOrder").serialize();
    data= data + "&Action=Order&payment_status=PENDING";

    jQuery.ajax({
      dataType:"json",
      type:"post",
      data:data,
      url:'<?php echo EXEC; ?>Exec_Edit_Order',
      success: function(data)
      {
        if(data.resonse)
        {
          $("#resonse").show();
          $('#resonsemsg').html('<span>Your Order has been saved.</span>');
          $( '#NewNote' ).each(function(){
            this.reset();
          });
          $(".Loader").hide();
          $(".modal").hide();

                  setTimeout(function () { window.location.href = "OrderList.php" }, 2000) ;
              }
              else if(data.error)
              {
               $("#error").show();
               $('#errormsg').html('<span>'+data.error+'</span>');
               $(".Loader").hide();

           }
           else if(data.csrf_error)
           {
             $("#csrf_error").show();
             $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
             $(".Loader").hide();
             $("#myModal_ordersummery").modal('hide');
             setTimeout(function () { window.location.reload() }, 2000)
         }
     }
 });
  }
});

$(".close").click(function(){
  $("#error").hide();
  $("#resonse").hide();
});

$(".closespty").click(function(){
   $(".modal-backdrop").hide();
   $("#paymenttypemodel").modal('hide');
   $("#myModal_ordersummery").modal('hide');


});

$(document).on('click','#romve_row',function(e){
   e.preventDefault();
   $(this).closest('tr').remove();
   var e = $.Event('keyup');
   $('#finalserciceprice').empty();
   $('#finalgiftprice').empty();
   $('#totalamount').empty();
   $('#finalmembershipprice').empty();
   $('#finalproductprice').empty();
   $('#salestax').empty();

   $('.discounts').each(function(){

    $(this).trigger('keyup');  
}); 
   counsalestax();
   var rowCount = $('#carttable tr').length;
   if(rowCount==1)
   {
    $('#point1').attr("disabled", true) 
    $(".finallist_first").show();
    $(".finallist").hide();
}

});

$(document).on('click','.remove_all',function(e){
   e.preventDefault()
   $("#carttable").find("tr:gt(0)").remove();
   $( '#NewOrder' ).each(function(){
    this.reset();
});
   $( '#Newmembership' ).each(function(){
    this.reset();
});
   $( '#Newgift' ).each(function(){
    this.reset();
});
   $( '#Newservice' ).each(function(){
    this.reset();
});
   $( '#Newproduct' ).each(function(){
    this.reset();
});
   $('#finalserciceprice').empty();
   $('#finalgiftprice').empty();
   $('#totalamount').empty();
   $('#finalmembershipprice').empty();
   $('#finalproductprice').empty();
   $('#select2-listofcatagory2-container').empty();
   $('#select2-listofcatagory3-container').empty();
   $('#select2-listofcatagory4-container').empty();
   $(".finallist_first").show();
   $(".finallist").hide();
});
// $("#scrollDefaultExample").change(function(){
//    var service_star_time = $(this).val(); 

//    if($('#listofcatagory2').val()!='')
//    {

//     var serivename=  $("#listofcatagory2").val();
//     $(".Loader").show();
//     $.ajax({
//      dataType:"json",
//      type:"post",
//      data: {'service_star_time':service_star_time,'serivename':serivename},
//      url:'?action=service_star_time',
//      success: function(data)
//      {
//       if(data)
//       {

//        $(".Loader").hide();
//        $("#Duration").val(data.resonse);
//    }
//    else if(data.error)
//    {
//        alert('ok');  
//    }
// }
// })


// }
// else
// {
//     swal("Please selected service")

// }

// });


$("#Chequeform").validate({
  rules: {        
    ChequeNumber: "required",
    bankname: "required",
    submitdate: "required",
    ChequeStatus: "required",

  },
  messages: {       
    ChequeNumber:  "Please enter cheque number",
    bankname:  "Please enter name Of bank",
    submitdate: "Please select cheque submit date",
    ChequeStatus: "Please select cheque cheque status",
  }
});


$("#Caseform").validate({
  rules: {        
    CaseStatus: "required",

  },
  messages: {       
    CaseStatus:  "Please select select case status",

  }
});


</script>


<script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
<script>
  $(document).ready(function() {
    // var daf = Math.floor(Math.random() * 40) + 1  

    // $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");

    //     $('.dropify').dropify();

    //     $('.dropify-fr').dropify({
    //       messages: {
    //         default: 'Glissez-déposez un fichier ici ou cliquez',
    //         replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
    //         remove: 'Supprimer',
    //         error: 'Désolé, le fichier trop volumineux'
    //       }
    //     });

    //     var drEvent = $('#input-file-events').dropify();
    //     drEvent.on('dropify.beforeClear', function(event, element) {
    //       return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    //     });
    //     drEvent.on('dropify.afterClear', function(event, element) {
    //       alert('File deleted');
    //     });
    //     drEvent.on('dropify.errors', function(event, element) {
    //       console.log('Has Errors');
    //     });
    //     var drDestroy = $('#input-file-to-destroy').dropify();
    //     drDestroy = drDestroy.data('dropify')
    //     $('#toggleDropify').on('click', function(e) {
    //       e.preventDefault();
    //       if (drDestroy.isDropified()) {
    //         drDestroy.destroy();
    //       } else {
    //         drDestroy.init();
    //       }
    //     })

    //     $("#NewClient").validate({
    //       rules: {                
    //         FirstName: "required",
    //         LastName: "required",
    //         Phone: {required: true,},
    //         email: "required",
    //         Address: "required",
    //         Zip: "required",
    //         City: "required",
    //         State: "required",
    //         Country:"required",
    //         newlistofSubscriber2: "required",

    //         },
    //         messages: {             
    //           FirstName:  "Please enter firstName",
    //           LastName:  "Please enter lastName",
    //           Phone:  "Please enter phone number",
    //           email:  "Please enter email",
    //           Address:  "Please enter address",
    //           Zip:  "Please enter zipcode",
    //           Country:"Please select country",
    //           City:  "Please enter city", 
    //           State:  "Please enter state",
    //           newlistofSubscriber: {required: "Please select Subscriber or User"},

    //         },
    //         errorPlacement: function( label, element ) {
    //           if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
    //             element.parent().parent().append( label );
    //           } else {
    //             label.insertAfter( element );
    //           }
    //         },
    //         submitHandler: function() {
    //           $(".Loader").show();
    //           var form = $('#NewClient')[0];
    //           var data = new FormData(form);

    //            jQuery.ajax({
    //             dataType:"json",
    //             type:"post",
    //             data:data,
    //             contentType: false, 
    //             processData: false,
    //             url:'<?php echo EXEC; ?>Exec_Edit_Client',
    //             success: function(data)
    //             {
    //              if(data.resonse)
    //              {
    //               $("#resonse").show();
    //               $('#resonsemsg').html('<span>'+data.resonse+'</span>');
    //               $( '#NewClient' ).each(function(){
    //                this.reset();
    //            });
    //               $(".Loader").hide();
    //               $("#myModal_addclient").modal('hide')
    //               setTimeout(function () { window.location.reload() }, 2000)


    //           }
    //           else if(data.error)
    //           {
    //              $("#error").show();
    //              $('#errormsg').html('<span>'+data.error+'</span>');
    //              $(".Loader").hide();
    //              $("#myModal_addclient").modal('hide')
    //              setTimeout(function () { window.location.reload() }, 2000)

    //          }
    //          else if(data.csrf_error)
    //          {

    //              $("#csrf_error").show();
    //              $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
    //              $(".Loader").hide();
    //              $("#myModal_addclient").modal('hide')
    //              setTimeout(function () { window.location.reload() }, 2000)
    //          }
    //      }

    //  });
    //        }           
    //    });

        // $(".dropify-clear").click(function(e){
        //   e.preventDefault();
        //   $(".dropify-preview").hide();

        //   var data1 = $("#id").val();

        //   data = data1 + "&action5=deleteimage";
        //   jQuery.ajax({
        //     dataType:"json",
        //     url:'<?php echo EXEC; ?>exec-edit-profile?action5',
        //     type:"post",
        //     data:{"cimyData2":data1},
        //     success: function(data) 
        //     {
        //       if(data.resonse)
        //       {
        //         swal("Client Image is deleted.");
        //         $("#oldimage").val('');
        //       }
        //       else if(data.error)
        //       {
        //         swal("","Something went wrong.","error");
        //       }
        //     }
        //   });
        // });          

    });
</script> 

<script>  
  $(document).ready(function(){



        $(document).on('select2:select',".SelectPackageliv",function(e) {
          var data = e.params.data;

           $('.Loader').show()
           var pakcagidc2 = data.id;

           var sselectedpackagename = data.text;

           $('.sselectedpackagename').text(sselectedpackagename);
           $('.sselectedpackagename').attr("data-id",pakcagidc2);
           $('.listofavliaasfpackag2').html('');

           $.ajax({
               url:'?action=editfile',
               type: "POST",
               data:{"pakcagidc2": pakcagidc2},
               dataType:"json",
               success:function(data)
               { 
                $('.Loader').hide();
                if(data.resonse2.length > 0)
                {
                 if(data.resonse3.Noofvisit=="Unlimited"){
                  $("#myModal_packagedetalsisfs .qty2").hide();
                  $("#myModal_packagedetalsisfs .count2").val(1);
              }else{
                  $("#myModal_packagedetalsisfs .qty2").show();
                  $("#myModal_packagedetalsisfs .count2").val(0);
              }

              $('#myModal_packagedetalsisfs').modal('show');
              $('.count3').show()
              $('.count3').text(data.resonse3.Noofvisit);  
              $('.count4').val(data.resonse3.Noofvisit);  
              $('.sselectedpackagename').attr("data-visit",data.resonse3.Noofvisit);

          }

          if(data.resonse2.length > 0)
          {
             $('.listofavliaasfpackag2').append('<option value="">Select service</option>'); 
             $.each(data.resonse2, function( index, value ) 
             {
              $('.listofavliaasfpackag2').append('<option value='+value.id+'>'+value.ServiceName+'</option>');

          });
         }
         else
         {
             swal("service is not available");
         }

     }
 });


       });

        var client = '<?php if(isset($client)) {echo $client;} ?>';
        if(client != "")
        {
           $('.finallist').show()
           $('.finallist_first').hide()
           $('#total_one').attr("id","total_one2")
           $("#NewOrder").attr("id","NewOrder2");
           $('#saveforlatter').hide()
           var selectedservice = "<?php if(isset($sname)) {echo $sname;} ?>";
           var selectedserviceid = "<?php if(isset($service)) {echo $service;}  ?>";
           $('#carttable tbody').append('<tr class="child order_popup" id="serivertr"><td><button id="romve_row" class="btn btn-danger btn-sm">Delete</button></td><input type="hidden" id="CommissionAmount" class="CommissionAmount" readonly name="serCommissionAmount[]" value="'+0+'"><td id="selectedservice"><input type="text" name="ServiceName[]" class="serivename names" id="serivename" readonly value="'+selectedservice+'"><input type="hidden" name="ServiceId[]" readonly value="'+selectedserviceid+'"></td><td id="selectedserviceqyt"><input type="text" class="selectedserviceqytext quantities" readonly value=" - "></td><td id="selectedsprice"><input type="text" name="ServicePrice[]" readonly value="'+'$ '+0+'" class="Prices"></td><td id="selectedservicedis"><input type="text" name="ServiceDiscount[]" class=" discounts selectedservicetext'+selectedserviceid+'" id="selectedservicetext" value="0"></td><td id="serviecdiscoinpersntag"><input type="text" name="ServiceDiscoutInParentage[]" class="dper'+selectedserviceid+' pdpers" id="dper" value="0" placeholder="0.00%"></td><td id="totalservicepricewithall"><input type="text" name="ServiceFianlPrice[]" id="totalservicepricewithalltext" class="totalproductpricewithalltexts servictotal order_total totalservicepricewithalltext'+selectedserviceid+'" readonly value="$ 0"></td><td><input type="hidden" name="ServicProvider[]" class="serviceprovider" id="serviceprovider" value=""></td><td><input type="hidden" name="ServiceStartTime[]" id="servicetime" value=""></td></tr>');
           $('#myModal_packagedetalsisfs').modal('hide')
       }

       $(".hiding").on("click",function(){ 
           if($("#giftbal").text() == "$0")
           { 
            $("#applygift,#removegift").hide(); 
        } 
        else
        {
            $("#applygift").show(); 
        }

    });


      



   });  
</script>
</body>

</html>