<?php 
require_once('Exec_Config.php');   
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
$db=new db();
$id = $_SESSION['UserID'];
$return_arr = array();
if (isset($_POST['topproduct']) && $_POST['topproduct']!='' ) {

    $temphere = $_POST['topproduct'];
    $fromdate1=date('Y').'-'.$temphere.'-'.'1';
    // $todate1=date('Y').'-'.$temphere.'-'.'31';
    $fromdate = date("Y-m-d", strtotime($fromdate1));
    $todate = date("Y-m-t", strtotime($fromdate1));
  }else{

     $fromdate1=date('Y').'-'.date('M').'-'.'1';
    // $todate1=date('Y').'-'.date('M').'-'.'31';
    $fromdate = date("Y-m-d", strtotime($fromdate1));
    $todate = date("Y-m-t", strtotime($fromdate1));

  }
    $stmt_topproduct= $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,
      Product.ProductImage As ProductImage,
      SUM(OrderProduct.ProdcutQuality) AS Quantity,
      SUM(Product.CompanyCost) AS Cost_Price,
      SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
      SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit
      FROM `OrderPayment` 
      JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
      JOIN Product ON Product.id=OrderProduct.ProdcutId 
      WHERE OrderPayment.payment_status='CAPTURED' 
      AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
      AND OrderProduct.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id)
      GROUP BY Category,ProductImage order by profit DESC limit 5"); 
    $stmt_topproduct->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $stmt_topproduct->bindParam(':todate', $todate, PDO::PARAM_STR);
    $stmt_topproduct->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_topproduct->execute();
    $result =$stmt_topproduct->fetchAll();

  //   foreach ($result as $row) {
  //    $Category = $row['Category'];
  //   $Quantity = $row['Quantity'];
  //   $ProductFianlPrice = $row['ProductFianlPrice'];
  //   $profit = $row['profit'];

  //   $return_arr[] = array("Category" => $Category,
  //                   "Quantity" => $Quantity,
  //                   "ProductFianlPrice" => $$ProductFianlPrice,
  //                   "profit" => $profit);


  // }
    
    echo json_encode($result);


  	
?>