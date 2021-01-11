<?php

require_once('Exec_Config.php');		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Sales_Report.php');

if(isset($_GET['subscriptionGraph'])) {

	
    $star_time=date("Y").'-01-01';
    $end_time=date("Y").'-12-31';
    
    $query = $db->prepare('SELECT DATE_FORMAT(paytime, "%b") as label,SUM(amount) as amount FROM `payments` where paytime>=DATE_FORMAT(:star_time, "%Y-%m-%d") and paytime<=DATE_FORMAT(:end_time, "%Y-%m-%d") GROUP BY paytime');

    $query->bindParam(":star_time",$star_time);
    $query->bindParam(":end_time",$end_time);
    $query->execute();
    $result = $query->fetchAll();



    $response = ['Package'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Month'=> ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]];

    foreach ($result as $key => $value) {
    	$index = array_search($value['label'], $response['Month']);
    	if($index>-1){
    		$response['Package'][$index]+=$value['amount'];
    	}
    }

    echo json_encode(['response'=>$response]);die;

}


if(isset($_GET['sellchart'])) {

	if(!empty($_POST['user'])){
		$id = $_POST['user'];

		if($_SESSION['usertype']=='Admin'){
			$UserID = $_POST['user'];
			$id = " select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID."  ";
		}

		if($_POST['user']=='Total'){
			$UserID = $_SESSION['UserID'];
			$id = " select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID."  ";
		}

	}else{
		$id = $_SESSION['UserID'];
	}

	$User = new SalseReport;
    $star_time=date("Y").'-01-01';
    $end_time=date("Y").'-12-31';
    $result = $User->particulerdate2($star_time,$end_time,$id);


    $response = ['Service'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Product'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Package'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Gift'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Month'=> ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]];

    foreach ($result as $key => $value) {
    	$index = date("n", strtotime($value['label'])) - 1;
    	$response['Service'][$index] = $value['ServiceFianlPrice'];
    	$response['Product'][$index] = $value['ProductFianlPrice'];
    	$response['Package'][$index] = $value['MembershipPrice'];
    	$response['Gift'][$index] = $value['Ordergift'];
    }

    echo json_encode(['response'=>$response]);die;

}

if(isset($_GET['sales_profitchart'])) {

	if(!empty($_POST['user'])){
		$id = $_POST['user'];

		if($_SESSION['usertype']=='Admin'){
			$UserID = $_POST['user'];
			$id = " select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID."  ";
		}

		if($_POST['user']=='Total'){
			$UserID = $_SESSION['UserID'];
			$id = " select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID."  ";
		}

	}else{
		$id = $_SESSION['UserID'];
	}

	
    $star_time=date("Y").'-01-01';
    $end_time=date("Y").'-12-31';
    $fromdate2= date("Y-m-d", strtotime($star_time));
	$todate2=date("Y-m-d", strtotime($end_time));
	

		// $LoginQuery1 = $db->prepare("SELECT DATE_FORMAT(OrderProduct.OrderTime, '%b') AS label, SUM(OrderProduct.ProdcutQuality) as ProductQuantity,
		// SUM(OrderProduct.ProductCostPrice) AS Cost_Price,		
		// SUM(REPLACE(OrderProduct.ProductPrice,'$ ','')) AS ProductPrice,
		// SUM((REPLACE(OrderProduct.ProductPrice,'$ ','')-OrderProduct.ProductCostPrice)) AS profit
		// FROM `OrderProduct` 
		// LEFT JOIN OrderPayment ON OrderProduct.OrderId=OrderPayment.OrderId
		// LEFT JOIN Product ON Product.id=OrderProduct.ProdcutId 
		// WHERE OrderPayment.payment_status='CAPTURED' 
		//  AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
  //     AND OrderProduct.createdfk IN (".$id.")
		// GROUP BY DATE_FORMAT(OrderProduct.OrderTime, '%b')");


		$LoginQuery1 = $db->prepare("SELECT DATE_FORMAT(OrderProduct.OrderTime, '%b') AS label,sum(ProdcutQuality*ProductCostPrice) as Finalcost ,sum(ProductFianlPrice) as finalprise, sum(ProductTaxPrice) as totaltax FROM `OrderProduct` LEFT JOIN OrderPayment ON OrderProduct.OrderId=OrderPayment.OrderId LEFT JOIN Product ON Product.id=OrderProduct.ProdcutId WHERE OrderPayment.payment_status='CAPTURED' AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate AND OrderProduct.createdfk IN ( ".$id.") GROUP BY DATE_FORMAT(OrderProduct.OrderTime, '%b')");

		
		$LoginQuery1->bindParam(':fromdate', $fromdate2);
		$LoginQuery1->bindParam(':todate', $todate2);
		// print_r($LoginQuery1);
		// die();		
		$LoginQuery1->execute();
		$result = $LoginQuery1->fetchAll();
		

    $response = ['TotalSales'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Profit'=>[0,0,0,0,0,0,0,0,0,0,0,0],'Month'=> ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]];

    foreach ($result as $key => $value) {
    	
    	$index = date("n", strtotime($value['label'])) - 1;
    	$response['Finalcost'][$index] = round($value['Finalcost'],2);
    	$response['TotalSales'][$index] = round($value['finalprise'],2);
    	$response['totaltax'][$index] = round($value['totaltax'],2);
    	$response['Profit'][$index] = ( round($value['finalprise'],2) - round($value['Finalcost'],2) - round($value['totaltax'],2));    	
    	
    }

    echo json_encode(['response'=>$response]);die;

}


if(isset($_GET['eventchart'])) {
	
	$data = [];
	$query_string = "";
	$ServiceName="";
	$filter = json_decode($_POST['eventchartdata']);
	$month = $filter->month;
	$services = $filter->service;
	$UserID = $filter->subscriber;



	if($_SESSION['usertype']=='Admin'){

		if(empty($UserID)){
			$user_string = "";
		}else{
			$user_string = " AND event.ServiceProvider IN (select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID.") ";
		}
	}else{
		if(empty($UserID)){
			$UserID = $_SESSION['UserID'];
		}
		$user_string = " AND event.ServiceProvider IN (select id from users where id=".$UserID." or adminid=".$UserID." or sid=".$UserID.") ";
		
	}

	foreach ($services as $key => $service) {

		if($service!="all"){
			$service_string = " AND event.ServiceName=".$service." ";
		}else{
			$service_string = "";
		}

		if(count($month)>0){
			$startdate = '2020-01-01';
			$enddate = '2020-12-31';
			$array = [];
			$string = "";

			foreach ($month as $key => $value) {
				$data[$value]=0;

				$startdate = date("Y-m-01", strtotime($value));
				$enddate = date("Y-m-t", strtotime($startdate));
				$string="(DATE_FORMAT(event.EventDate, '%Y-%m-%d')>='".$startdate."' AND DATE_FORMAT(event.EventDate, '%Y-%m-%d')<='".$enddate."')";

				array_push($array,$string);
			}

			$query_string = implode(" OR ", $array);


		}else{

			$startdate = '2020-01-01';
			$enddate = '2020-12-31';
			$query_string = "(DATE_FORMAT(event.EventDate, '%Y-%m-%d')>='".$startdate."' AND DATE_FORMAT(event.EventDate, '%Y-%m-%d')<='".$enddate."')";

			for($i=1; $i<=12; $i++)
			{
				$index = date('M',strtotime("2020-".$i."-01"));
				$data[$index]=0;
			}
		}			

		if($service!="all"){
			$query = $db->prepare("Select ServiceName from Service where id=:id");
			$query->bindValue(":id",$service);
			$query->execute();
			$ServiceName = $query->fetch();
			$ServiceName = $ServiceName['ServiceName'];
		}

		$query = $db->prepare("SELECT event.*,Service.ServiceName as ServiceName FROM `event` join Service on Service.id=event.ServiceName where event.eventstatus<>'canceled' AND event.Accepted='1' ".$user_string.$service_string." AND (".$query_string.")");
		$query->bindValue(":UserID",$UserID);
		$run = $query->execute();
		$all = $query->fetchAll();

		$query = $db->prepare("SELECT event.eventstatus,COUNT(event.eventstatus) as EventCount FROM `event` join Service on Service.id=event.ServiceName where event.Accepted='1' ".$user_string.$service_string." AND (".$query_string.") group by event.eventstatus");
		$query->bindValue(":UserID",$UserID);
		$run = $query->execute();
		$Status = $query->fetchAll();


		$EventStatusLabel = ['completed','confirmed','in-progress','pending','pending-payment','canceled'];
		$EventStatus = [0,0,0,0,0,0];

		if($run){


			foreach ($all as $key => $value) {

				$date = explode(" ",$value['EventDate'])[0];
				$index = date('M',strtotime($date));
				if(array_key_exists($index, $data)){
					$data[$index] += 1; 
				}else{
					$data[$index] = 1;
				}
			}



			foreach ($Status as $key => $value) {
		
				if(array_search($value['eventstatus'], $EventStatusLabel)>=0){
					$index = array_search($value['eventstatus'], $EventStatusLabel);
					$EventStatus[$index] = $value['EventCount'];

				}
			}


		}

		if($service!="all"){
			$response[$ServiceName]['month'] = array_keys($data);
			$response[$ServiceName]['count'] = array_values($data);
			$response[$ServiceName]['EventCount'] = $EventStatus;
		}else{
			$response[$service]['month'] = array_keys($data);
			$response[$service]['count'] = array_values($data);
			$response[$service]['EventCount'] = $EventStatus;
		}
	}
	
	if($run){
		echo json_encode(['response'=>$response]);die;
	}else{
		echo json_encode(['error'=>'Something went wrong.']);die;
	}
	
}


?>