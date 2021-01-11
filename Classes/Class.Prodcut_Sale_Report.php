<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class SalseReport{

  public function getalltraction()
  {

    $db= new db();


    $selectdaterang = "";

    if(!empty($_GET['selectdaterang'])  ){

      $selectdaterang =explode(' - ',$_GET['selectdaterang']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $selectdaterang = " AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<='".$todate."' ";

    }



    $selectuser = @$_GET['getalltraction'];

    if($_SESSION['usertype']!='Admin'){

    if(!empty($selectuser)){
      $selectuser = " AND users.id IN ($selectuser) " ;
    }
    else{
      $selectuser = $_SESSION['UserID'];
      $selectuser = " and users.id IN (select id from users where users.id=$selectuser or users.adminid=$selectuser) " ;
    }

  }else{
     if(empty($selectuser)){
      $selectuser = " " ;
    }
    else{

      $selectuserArray = explode(',',$selectuser);
      $temp = [] ;
      foreach ($selectuserArray as $key => $value) {
        array_push($temp," users.id IN (select id from users where users.id=$value or users.adminid=$value) ") ;
      }
      $selectuser = " AND ( ".implode(" or ",$temp)." ) ";
    }
  }


  if(!empty($_GET['selectcutomer'])){
    $selectcutomer = " AND OrderPayment.Cid IN (".$_GET['selectcutomer'].")";
  }else{
    $selectcutomer = '';
  }


  // if($_SESSION['usertype']!='Admin'){
  //   $id = $_SESSION['UserID'];
  //   $id = " and users.id IN (select id from users where users.id=$id or users.adminid=$id) ";
  // }else{
  //   $id="";
  // }

  if(!empty($_GET['product'])){
    $Product = " and Product.id IN ( ".$_GET['product']." ) ";
  }else{
    $Product = "";
  }

  $LoginQuery = $db->prepare("
   SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,OrderProduct.ProductTaxPrice,OrderProduct.ProductPrice,(SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,Product.id as ProductId,Product.ProductTitle,clients.id as clientid,clients.ProfileImg, OrderProduct.ProdcutQuality, OrderProduct.ProductCostPrice,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg ,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-OrderProduct.ProductCostPrice) as profit,OrderProduct.OrderTime  
   FROM `OrderPayment`
   JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
   JOIN Product ON Product.id=OrderProduct.ProdcutId
   JOIN users ON OrderPayment.Cratedfk=users.id
   JOIN clients ON clients.id = OrderPayment.Cid
   WHERE OrderPayment.payment_status='CAPTURED'

   $selectdaterang
   $selectuser
   $selectcutomer
   $Product ");
  $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
  $LoginQuery->execute();   
  
  $result = $LoginQuery->fetchAll();

  echo json_encode($result);die;

}



public function getalltraction2()
{
 $db= new db();

 $add_query = "";

 if(!empty($_GET['selectdaterang'])){

  $selectdaterang =explode(' - ',$_GET['selectdaterang']);
  $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
  $todate = date("Y-m-d", strtotime($selectdaterang[1]));
  $add_query = " AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>='".$fromdate."'
  AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<='".$todate."' ";

}


if(!empty($_GET['getalltraction'])){
 $getalltraction = "( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction'].")";
 $getalltraction = " AND OrderPayment.Cratedfk IN(".$getalltraction.") " ;
}
else{
  $getalltraction="";
}




if(!empty($_GET['selectcutomer'])){
  $selectcutomer = " AND OrderPayment.Cid IN (".$_GET['selectcutomer'].")";
}else{
  $selectcutomer = '';
}

if(!empty($_GET['product'])){
    $Product = " and Product.id = ".$_GET['product']." ";
  }else{
    $Product = "";
  }


$LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost, CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
  FROM `OrderPayment`
  JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
  JOIN Product ON Product.id=OrderProduct.ProdcutId
  JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
  JOIN users ON OrderPayment.Cratedfk=users.id
  JOIN clients ON clients.id = OrderPayment.Cid
  WHERE OrderPayment.payment_status='CAPTURED'
  $getalltraction 
  $selectcutomer
  $add_query 
  $Product ");

$LoginQuery->execute();
$result = $LoginQuery->fetchAll();

echo json_encode($result);die;


}




}



?>