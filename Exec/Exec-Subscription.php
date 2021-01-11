<?php

require_once('Exec_Config.php');   
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');



if(isset($_GET['action']) && $_GET['action']=='Invoice'){
	$Orderid = $_POST['Orderid'];

$query = $db->prepare("SELECT users.firstname, users.lastname, users.email, users.phonenumber,users.primaryaddress,users.zipcode,users.city,users.state,users.country, payments.* FROM `payments` join users on payments.userid = users.id where payments.id=:id");
    $query->bindParam(':id', $Orderid);
    $res = $query->execute();
    $result = $query->fetch();

    if($query->rowCount()>0){
	    echo json_encode(['response'=>$result]);die;
    }else{
	    echo json_encode(['error'=>'Data not found.']);die;
    }
}

?>