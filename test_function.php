<?php
require_once('function.php');

echo json_encode($_SESSION['test']);die;

// $contacts = json_decode($_SESSION['test'],true);

die;
$stmt= $db->prepare("Select * from ActiveUser where UserId=875 order by id desc limit 1"); 
$stmt->execute();
$result = $stmt->fetchAll();

echo json_encode($result);die;

die;

die;

phpinfo();

die;

// echo $_COOKIE['SAVEDUSERS'];



echo $saved_users;

foreach ($array as $key => $value) {
  if(is_array($value)){
    echo in_array('RoayEmp', $value);
  }
  // echo $value['Username']; 
}

die;
require_once('function.php');

$stmt= $db->prepare("Select * from users limit 1"); 
$stmt->execute();
$result = $stmt->fetchAll();
echo json_encode($result);die;
foreach ($result as $key => $value) {

  if(strlen($value['City'])>20){
    $id = $value['id']; 
    $City = substr($value['City'],0,20);
    $update= $db->prepare("Update CompanyInformation set City='$City' where id=$id "); 
    $update->execute();
  }

  // if(strlen($value['Zip'])>11){
  //   $id = $value['id']; 
  //   $update= $db->prepare("Update CompanyInformation set Zip='12345' where id=$id "); 
  //   $update->execute();
  // }

}




die;





// $stmt= $db->prepare("Select Cid,Orderdate from OrderPayment where Cratedfk=875 GROUP by Cid Order by Orderdate DESC ");


 $stmt= $db->prepare("Select * from OrderPayment where PaymentType='Card'"); 
  $stmt->execute();
  $result = $stmt->fetchAll();

  foreach ($result as $key => $value) {
  	if(!empty($value['Transactionid'])){
  		$id = $value['id'];

  		 $card_detail = json_encode(array('card_type' => '',
                                    'card_last_digit' => '',
                                    'TransactionID' => $value['Transactionid'],
                                    'Order_ID' => $value['tender_id'],
                                    'Amount' => $value['amount'],
                                    'Agency' => $value['NameOfBank']));

  	

  $stmt= $db->prepare("Update OrderPayment set PaymentDetail=:PaymentDetail where id=:id "); 
  $stmt->bindParam(":id",$value['id']);
  $stmt->bindParam(":PaymentDetail",$card_detail);
  $stmt->execute();

  	}



  }

  
?>