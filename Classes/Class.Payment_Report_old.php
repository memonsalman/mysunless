<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class SalseReport{

  public function UpcomingRenewals($UpcomingRenewalsDays)

  {


    $db= new db();
    $id=$_SESSION['UserID'];

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id
      WHERE OrderPayment.Cratedfk=:id  AND OrderPayment.payment_status='CAPTURED' ");


    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $LoginQuery->execute();

    $result = $LoginQuery->fetchAll();

    echo json_encode($result);die;    


  }

  public function UpcomingRenewals2($UpcomingRenewalsDays)
  {


    $db= new db();
    $id=$_SESSION['UserID'];
    
    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id
      WHERE users.adminid=:id OR users.sid=:id AND OrderPayment.payment_status='CAPTURED'");
    
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
    $LoginQuery->execute();

    $result = $LoginQuery->fetchAll();

    echo json_encode($result);die;    

  }
  

  public function getalltraction()
  {

    //$selectdaterang = explode('-',$_GET['selectdaterang']);
    $db= new db();
    //$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
    //$todate = date("Y-m-d", strtotime($selectdaterang[1]));
    $getalltraction = $_GET['getalltraction'];
    $selectcutomer = $_GET['selectcutomer'];  
    $id=$_SESSION['UserID'];

    $add_query = "";

    if(!empty($_GET['selectdaterang'])){

      $selectdaterang =explode(' - ',$_GET['selectdaterang']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $add_query = " AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='".$todate."' ";

    }

    if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
    {
           // $selectcutomer = $_GET['selectcutomer'];

     $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'
       
        AND users.id IN($getalltraction)
         AND clients.id IN($selectcutomer)
          AND (users.adminid=:id OR users.id=:id)
          $add_query");
     $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);  
            // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
     $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
     //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
     //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
     $LoginQuery->execute();
     $result = $LoginQuery->fetchAll();
     echo json_encode($result);die;      
   }

   else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
   {


    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'
      
      AND users.id IN($getalltraction) AND (users.adminid=:id OR users.id=:id)
      $add_query");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
    // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
    //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;      

    

  }

  else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
  {
    $selectcutomer = $_GET['selectcutomer'];

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'
      AND clients.id IN($selectcutomer) AND (users.adminid=:id OR users.id=:id)
      $add_query");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
    $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
    //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;   

  }
  else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
  {

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'
       AND users.adminid=:id OR users.id=:id
       $add_query");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
    //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();

    echo json_encode($result);die;      
  }
  else if(empty($fromdate) && empty($todate) && empty($getalltraction) && empty($selectcutomer))
  {

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'
      $add_query
      AND users.adminid=:id OR users.id=:id");
    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
    //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();

    echo json_encode($result);die;      
  }



  
}


// admin
public function getalltraction2()
{
  //$selectdaterang =explode(' - ',$_GET['selectdaterang']);
  $db= new db();
  //$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
  //$todate = date("Y-m-d", strtotime($selectdaterang[1]));

  $add_query = "";

  if(!empty($_GET['selectdaterang']) || $_GET['selectdaterang']!='' ){

    $selectdaterang =explode(' - ',$_GET['selectdaterang']);
    $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
    $todate = date("Y-m-d", strtotime($selectdaterang[1]));
    $add_query = " AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='".$fromdate."'
    AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='".$todate."' ";

  }

  if(!empty($_GET['getalltraction'])){
    $getalltraction = "( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction']." ) ";
  }
  $selectcutomer = $_GET['selectcutomer'];

  if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
  {
   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED' 
    AND users.id IN($getalltraction) 
    AND clients.id IN($selectcutomer)
    $add_query");
            // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
            // $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
   $LoginQuery->execute();
   $result = $LoginQuery->fetchAll();
   echo json_encode($result);die;      
 }
 else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
 {

  $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED'
    AND users.id IN($getalltraction)
    $add_query");
        // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
  //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  $LoginQuery->execute();
  $result = $LoginQuery->fetchAll();
  echo json_encode($result);die;      


}
else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
{

  $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED'
    AND clients.id IN($selectcutomer)
    $add_query");
  
    // $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
  //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  $LoginQuery->execute();
  $result = $LoginQuery->fetchAll();
  echo json_encode($result);die;   

}
else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
{

  $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED'
    $add_query");
  //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  $LoginQuery->execute();
  $result = $LoginQuery->fetchAll();
  echo json_encode($result);die;      
}
else if(empty($fromdate) && empty($todate) && empty($getalltraction) && empty($selectcutomer))
{

  $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED'
    $add_query");
  //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  $LoginQuery->execute();
  $result = $LoginQuery->fetchAll();
  echo json_encode($result);die;      
}
else if(empty($_GET['selectdaterang']) && !empty($getalltraction) && empty($selectcutomer))
{

  $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED' 
    AND users.id IN($getalltraction) 
    AND clients.id IN($selectcutomer)
    $add_query");
            // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
            // $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  $LoginQuery->execute();
  $result = $LoginQuery->fetchAll();
  echo json_encode($result);die;    
}




}

}
?>