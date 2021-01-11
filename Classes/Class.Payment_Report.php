<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
require_once(Classes.'/Class.Datatable.php'); //Used on Server-Side Datatable


class SalseReport{

  public function PaymentReport()

  {


    $db= new db();

    if($_SESSION['usertype']!='Admin'){
      $id=$_SESSION['UserID'];
      $id = " and (users.adminid=$id OR users.id=$id)";
    }else{
      $id = "";
    }


    $data = json_decode($_REQUEST['PaymentFilter']);

    $date = $data->date;
    $user = implode(',',$data->user);
    $customer = implode(',',$data->customer);
    $status = implode(',',$data->status);

    if(!empty($date) ){
      $selectdaterang =explode(' - ',$date);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $date = " AND  DATE_FORMAT(OrderPayment.Orderdate, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderPayment.Orderdate, '%Y-%m-%d')<='".$todate."' ";
    }else{
      $date = "";
    }

    if(!empty($status)){
      $status = " AND FIND_IN_SET(OrderPayment.PaymentType,'$status') ";
    }else{
      $status = "";
    }

    if(!empty($user)){
      
      $user = " AND OrderPayment.Cratedfk IN ($user) ";
    }

    if(!empty($customer)){
      
      $customer = " AND OrderPayment.Cid IN ($customer) ";
    }

    $OrderString = " order by OrderPayment.id desc ";

    //Server Side datatable

    if(isset($_REQUEST['start']) && isset($_REQUEST['length'])){

      $start = $_REQUEST['start'];
      $length = $_REQUEST['length'];

      $Limit = " LIMIT $start,$length ";
    }else{
      $Limit = "";
    }

    if(!empty($_REQUEST['order'])){
      $OrderString = DT_OrderBy($_REQUEST['order']);
    }

    $SearchString = "";
    if(!empty($_REQUEST['search']['value'])){

      $SearchString = ' where '.DT_Search($_REQUEST['search']['value']);
    }



    $Query = "SELECT * from ( SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,CONCAT(clients.FirstName,' ',clients.LastName) as Client_Fullname,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.*,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id
      WHERE OrderPayment.Cratedfk=users.id $date $id $user $customer $status $OrderString ) as PaymentList";

    $response = DT_SQL($Query,'',$SearchString,$Limit);
    echo $response; die; 


  }


  public function UpcomingRenewals()

  {


    $db= new db();

 if($_SESSION['usertype']!='Admin'){
            $id=$_SESSION['UserID'];
            $id = " and (users.adminid=$id OR users.id=$id)";
        }else{
            $id = "";
        }


    if(!empty($_GET['selectdaterang']) ){

      $selectdaterang =explode(' - ',$_GET['selectdaterang']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $date = " AND  DATE_FORMAT(OrderPayment.Orderdate, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderPayment.Orderdate, '%Y-%m-%d')<='".$todate."' ";
    }else{
      $date = "";
    }

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.*,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id
      WHERE OrderPayment.Cratedfk=users.id $date $id order by OrderPayment.id desc");

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
    $db= new db();

    if($_SESSION['usertype']!='Admin'){
      $id = $_SESSION['UserID'];
      $id = "AND (users.adminid=$id OR users.id=$id) ";
    }else{
      $id = "";
    }

    if(!empty($_GET['selectdaterang']) ){

      $selectdaterang =explode(' - ',$_GET['selectdaterang']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $date = " AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='".$todate."' ";

    }else{
      $date = "";
    }

    if(!empty($_GET['getalltraction'])){
      $getalltraction = " ( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction']." ) ";
      $getalltraction = ' AND users.id IN('.$getalltraction.') ' ;
    }else{
      $getalltraction = "";
    }

    if(!empty($_GET['selectcutomer'])){
      $selectcutomer = ' AND clients.id IN('.$_GET['selectcutomer'].') ' ;
    }else{
      $selectcutomer = "";
    }

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED' 
      $id
      $getalltraction
      $selectcutomer
      $date Order By OrderPayment.id desc");
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;  


  //   $getalltraction = $_GET['getalltraction'];
  //   $selectcutomer = $_GET['selectcutomer'];  
  //   $id=$_SESSION['UserID'];

  //   $add_query = "";

  //   if(!empty($_GET['selectdaterang'])){

  //     $selectdaterang =explode(' - ',$_GET['selectdaterang']);
  //     $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
  //     $todate = date("Y-m-d", strtotime($selectdaterang[1]));
  //     $add_query = " AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='".$fromdate."'
  //     AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='".$todate."' ";

  //   }

  //   if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
  //   {
  //          // $selectcutomer = $_GET['selectcutomer'];

  //    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
  //     JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
  //     JOIN clients ON OrderPayment.Cid=clients.id
  //     JOIN users ON OrderPayment.Cratedfk=users.id  
  //     WHERE  OrderPayment.payment_status='CAPTURED'

  //       AND users.id IN($getalltraction)
  //        AND clients.id IN($selectcutomer)
  //         AND (users.adminid=:id OR users.id=:id)
  //         $add_query");
  //    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);  
  //           // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
  //    $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
  //    //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //    //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  //    $LoginQuery->execute();
  //    $result = $LoginQuery->fetchAll();
  //    echo json_encode($result);die;      
  //  }

  //  else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
  //  {


  //   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
  //     JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
  //     JOIN clients ON OrderPayment.Cid=clients.id
  //     JOIN users ON OrderPayment.Cratedfk=users.id  
  //     WHERE  OrderPayment.payment_status='CAPTURED'

  //     AND users.id IN($getalltraction) AND (users.adminid=:id OR users.id=:id)
  //     $add_query");
  //   $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
  //   // $LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
  //   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  //   $LoginQuery->execute();
  //   $result = $LoginQuery->fetchAll();
  //   echo json_encode($result);die;      

    

  // }

  // else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
  // {
  //   $selectcutomer = $_GET['selectcutomer'];

  //   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
  //     JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
  //     JOIN clients ON OrderPayment.Cid=clients.id
  //     JOIN users ON OrderPayment.Cratedfk=users.id  
  //     WHERE  OrderPayment.payment_status='CAPTURED'
  //     AND clients.id IN($selectcutomer) AND (users.adminid=:id OR users.id=:id)
  //     $add_query");
  //   $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
  //   $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
  //   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  //   $LoginQuery->execute();
  //   $result = $LoginQuery->fetchAll();
  //   echo json_encode($result);die;   

  // }
  // else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
  // {

  //   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
  //     JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
  //     JOIN clients ON OrderPayment.Cid=clients.id
  //     JOIN users ON OrderPayment.Cratedfk=users.id  
  //     WHERE  OrderPayment.payment_status='CAPTURED'
  //      AND users.adminid=:id OR users.id=:id
  //      $add_query");
  //   $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
  //   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  //   $LoginQuery->execute();
  //   $result = $LoginQuery->fetchAll();

  //   echo json_encode($result);die;      
  // }
  // else if(empty($fromdate) && empty($todate) && empty($getalltraction) && empty($selectcutomer))
  // {

  //   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
  //     JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
  //     JOIN clients ON OrderPayment.Cid=clients.id
  //     JOIN users ON OrderPayment.Cratedfk=users.id  
  //     WHERE  OrderPayment.payment_status='CAPTURED'
  //     $add_query
  //     AND users.adminid=:id OR users.id=:id");
  //   $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);   
  //   //$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
  //   //$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
  //   $LoginQuery->execute();
  //   $result = $LoginQuery->fetchAll();

  //   echo json_encode($result);die;      
  // }




  }


// admin
  public function getalltraction2()
  {

    $db= new db();

    if(!empty($_GET['selectdaterang']) ){

      $selectdaterang =explode(' - ',$_GET['selectdaterang']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));
      $date = " AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='".$fromdate."'
      AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='".$todate."' ";

    }else{
      $date = "";
    }

    if(!empty($_GET['getalltraction'])){
      $getalltraction = " ( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction']." ) ";
      $getalltraction = ' AND users.id IN('.$getalltraction.') ' ;
    }else{
      $getalltraction = "";
    }

    echo $getalltraction;die;

    if(!empty($_GET['selectcutomer'])){
      $selectcutomer = ' AND clients.id IN('.$_GET['selectcutomer'].') ' ;
    }else{
      $selectcutomer = "";
    }

    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED' 
      $getalltraction
      $selectcutomer
      $date Order By OrderPayment.id desc");

    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;  

  }

}
?>