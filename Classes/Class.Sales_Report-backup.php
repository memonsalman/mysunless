<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class SalseReport{
	public function UserDisplay()
	{
		$db=new db();
		$id=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT OrderMaster.createdfk,users.userimg,users.username,OrderMaster.InvoiceNumber as ino,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,clients.FirstName,clients.LastName,clients.ProfileImg 
			FROM `OrderMaster`
			JOIN clients ON OrderMaster.cid=clients.id
			JOIN users ON OrderMaster.createdfk=users.id
			WHERE users.adminid=:id AND OrderMaster.payment_status='CAPTURED'");
		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;
	}
	public function particulerdate($daterange)
	{
		//working section
		$db=new db();
		$id=$_SESSION['UserID'];
		
		$getalltraction = $_GET["getalltraction"];
		$selectcutomer = $_GET['selectcutomer'];
		$selectdaterang = explode(' - ',$_GET['daterange']);
      	$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      	$todate = date("Y-m-d", strtotime($selectdaterang[1]));

		if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
		{
	      	$LoginQuery = $db->prepare("SELECT 
	      		SUM(OrderMaster.TotalseriveAmount) AS TotalseriveAmount,
	      		SUM(OrderMaster.TotalProductAmount) AS TotalProductAmount,
	      		SUM(OrderMaster.TotalMembershipAmount) AS TotalMembershipAmount, 
	      		SUM(OrderMaster.TotalgiftAmount) AS Totalgiftamount, 
	      		SUM(OrderMaster.ServiceDiscount) AS ServiceDiscount,
	      		SUM(OrderMaster.ProductDiscount) AS ProductDiscount,
	      		SUM(OrderMaster.gServiceDiscount) AS giftdiscount,
	      		SUM(OrderMaster.MembershipDiscount) AS MembershipDiscount,
	      		round(SUM(OrderMaster.sales_tax),2) AS sales_tax, 
	      		SUM(OrderMaster.tips) AS tips,
	      		ROUND(SUM(OrderMaster.TotalseriveAmount)+SUM(OrderMaster.TotalProductAmount)+SUM(OrderMaster.TotalMembershipAmount)+SUM(OrderMaster.TotalgiftAmount)-round(SUM(OrderMaster.sales_tax),2)-SUM(OrderMaster.tips),2) AS Total	
	      		FROM `OrderMaster`  
	      		WHERE OrderMaster.createdfk IN($getalltraction) 
	      		AND OrderMaster.cid IN($selectcutomer)
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$LoginQuery->execute();
	 		$result = $LoginQuery->fetchAll();
	 		$Log = $db->prepare("SELECT 
	      		SUM(TotalgiftAmount) AS giftread FROM `OrderMaster`  
	      		WHERE createdfk IN($getalltraction) 
	      		AND cid IN($selectcutomer)
	      		AND gstatus = 1
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$Log->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$Log->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$Log->execute();
	 		$resul = $Log->fetchAll();
	 		$result[0]["giftread"] = $resul[0]["giftread"];
	 		$result[0][11] = $resul[0]["giftread"];
	 		array_push($result, $result[0]["giftread"],$result[0][11]);
	 		unset($result[1],$result[2]);
	 		echo json_encode($result);die;
		}
		else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
		{
			$LoginQuery = $db->prepare("SELECT 
	      		SUM(OrderMaster.TotalseriveAmount) AS TotalseriveAmount,
	      		SUM(OrderMaster.TotalProductAmount) AS TotalProductAmount,
	      		SUM(OrderMaster.TotalMembershipAmount) AS TotalMembershipAmount,
	      		SUM(OrderMaster.TotalgiftAmount) AS Totalgiftamount, 
	      		SUM(OrderMaster.ServiceDiscount) AS ServiceDiscount,
	      		SUM(OrderMaster.ProductDiscount) AS ProductDiscount,
	      		SUM(OrderMaster.gServiceDiscount) AS giftdiscount,
	      		SUM(OrderMaster.MembershipDiscount) AS MembershipDiscount,
	      		round(SUM(OrderMaster.sales_tax),2) AS sales_tax, 
	      		SUM(OrderMaster.tips) AS tips,
	      		ROUND(SUM(OrderMaster.TotalseriveAmount)+SUM(OrderMaster.TotalProductAmount)+SUM(OrderMaster.TotalMembershipAmount)+SUM(OrderMaster.TotalgiftAmount)-round(SUM(OrderMaster.sales_tax),2)-SUM(OrderMaster.tips),2) AS Total	
	      		FROM `OrderMaster`
	      		WHERE OrderMaster.createdfk IN($getalltraction)
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$LoginQuery->execute();
	 		$result = $LoginQuery->fetchAll();
	 		$Log = $db->prepare("SELECT 
	      		SUM(TotalgiftAmount) AS giftread FROM `OrderMaster`
	      		WHERE createdfk IN($getalltraction) 
	      		AND gstatus = 1
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$Log->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$Log->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$Log->execute();
	 		$resul = $Log->fetchAll();
	 		$result[0]["giftread"] = $resul[0]["giftread"];
	 		$result[0][11] = $resul[0]["giftread"];
	 		array_push($result, $result[0]["giftread"],$result[0][11]);
	 		unset($result[1],$result[2]);
	 		echo json_encode($result);die;	
		}
		else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
		{
			$LoginQuery = $db->prepare("SELECT 
	      		SUM(OrderMaster.TotalseriveAmount) AS TotalseriveAmount,
	      		SUM(OrderMaster.TotalProductAmount) AS TotalProductAmount,
	      		SUM(OrderMaster.TotalMembershipAmount) AS TotalMembershipAmount,
	      		SUM(OrderMaster.TotalgiftAmount) AS Totalgiftamount, 
	      		SUM(OrderMaster.ServiceDiscount) AS ServiceDiscount,
	      		SUM(OrderMaster.ProductDiscount) AS ProductDiscount,
	      		SUM(OrderMaster.gServiceDiscount) AS giftdiscount, 
	      		SUM(OrderMaster.MembershipDiscount) AS MembershipDiscount,
	      		round(SUM(OrderMaster.sales_tax),2) AS sales_tax, 
	      		SUM(OrderMaster.tips) AS tips,
	      		ROUND(SUM(OrderMaster.TotalseriveAmount)+SUM(OrderMaster.TotalProductAmount)+SUM(OrderMaster.TotalMembershipAmount)+SUM(OrderMaster.TotalgiftAmount)-round(SUM(OrderMaster.sales_tax),2)-SUM(OrderMaster.tips),2) AS Total	
	      		FROM `OrderMaster`
	      		WHERE OrderMaster.cid IN($selectcutomer)
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$LoginQuery->execute();
	 		$result = $LoginQuery->fetchAll();
	 		$Log = $db->prepare("SELECT 
	      		SUM(TotalgiftAmount) AS giftread FROM `OrderMaster`  
	      		WHERE
	      		cid IN($selectcutomer)
	      		AND gstatus = 1
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$Log->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$Log->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$Log->execute();
	 		$resul = $Log->fetchAll();
	 		$result[0]["giftread"] = $resul[0]["giftread"];
	 		$result[0][11] = $resul[0]["giftread"];
	 		array_push($result, $result[0]["giftread"],$result[0][11]);
	 		unset($result[1],$result[2]);
	 		echo json_encode($result);die;	
		}
		else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
		{
			/*$LoginQuery = $db->prepare("SELECT OrderMaster.cid
	      		FROM `OrderMaster`
	      		JOIN OrderServic ON OrderMaster.cid = OrderServic.Cid 
	      		Group by OrderMaster.id
	      		JOIN OrderProduct ON OrderMaster.cid = OrderProduct.Cid
	      		JOIN OrderMembership ON OrderMembership.Cid = OrderMaster.cid
	      		JOIN Ordergift ON OrderMaster.cid = Ordergift.Cid ");
	 		$LoginQuery->execute();
	 		$result = $LoginQuery->fetchAll();
	 		print_r($result);
	 		die();
	 		echo json_encode($result);die;*/
			$LoginQuery = $db->prepare("SELECT 
	      		SUM(OrderMaster.TotalseriveAmount) AS TotalseriveAmount,
	      		SUM(OrderMaster.TotalProductAmount) AS TotalProductAmount,
	      		SUM(OrderMaster.TotalMembershipAmount) AS TotalMembershipAmount,
	      		SUM(OrderMaster.TotalgiftAmount) AS Totalgiftamount, 
	      		SUM(OrderMaster.ServiceDiscount) AS ServiceDiscount,
	      		SUM(OrderMaster.ProductDiscount) AS ProductDiscount,
	      		SUM(OrderMaster.gServiceDiscount) AS giftdiscount, 
	      		SUM(OrderMaster.MembershipDiscount) AS MembershipDiscount,
	      		round(SUM(OrderMaster.sales_tax),2) AS sales_tax, 
	      		SUM(OrderMaster.tips) AS tips,
	      		ROUND(SUM(OrderMaster.TotalseriveAmount)+SUM(OrderMaster.TotalProductAmount)+SUM(OrderMaster.TotalMembershipAmount)+SUM(OrderMaster.TotalgiftAmount)-round(SUM(OrderMaster.sales_tax),2)-SUM(OrderMaster.tips),2) AS Total	
	      		FROM `OrderMaster`
	      		WHERE DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
	 		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$LoginQuery->execute();
	 		$result = $LoginQuery->fetchAll();
	 		$Log = $db->prepare("SELECT 
	      		SUM(TotalgiftAmount) AS giftread FROM `OrderMaster`  
	      		WHERE
	      		gstatus = 1
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
	      		AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate ");
	 		$Log->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
	 		$Log->bindParam(':todate', $todate, PDO::PARAM_STR);
	 		$Log->execute();
	 		$resul = $Log->fetchAll();
	 		$result[0]["giftread"] = $resul[0]["giftread"];
	 		$result[0][11] = $resul[0]["giftread"];
	 		array_push($result, $result[0]["giftread"],$result[0][11]);
	 		unset($result[1],$result[2]);
	 		echo json_encode($result);die;
		}

		$id=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT OrderMaster.createdfk,users.userimg,users.username,OrderMaster.InvoiceNumber as ino,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,clients.FirstName,clients.LastName,clients.ProfileImg 
			FROM `OrderMaster`
			JOIN clients ON OrderMaster.cid=clients.id
			JOIN users ON OrderMaster.createdfk=users.id
			WHERE users.adminid=:id
			AND OrderMaster.payment_status='CAPTURED'
			AND  DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate 
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate");
		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;
	}

public function UpcomingRenewals($UpcomingRenewalsDays)

	{
		$db= new db();
		$id=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT OrderMaster.createdfk,users.userimg,users.username,OrderMaster.InvoiceNumber as ino,OrderMaster.datecreated as Orderdate,OrderMaster.id as orderid,clients.FirstName,clients.LastName,clients.ProfileImg 
			FROM `OrderMaster`
			JOIN clients ON OrderMaster.cid=clients.id
			JOIN users ON OrderMaster.createdfk=users.id
			WHERE users.adminid=:id 
			AND OrderMaster.payment_status='CAPTURED'
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<= NOW() 
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>= NOW() - INTERVAL :Days DAY");
		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->bindValue(":Days",$UpcomingRenewalsDays,PDO::PARAM_INT);
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;
	}
	public function UpcomingsDays($UpcomingsDays)
	{
		$db= new db();
		$id=$_SESSION['UserID'];
		$LoginQuery = $db->prepare("SELECT DATE_FORMAT(OrderMaster.datecreated, '%d %M, %Y') AS label,SUM(REPLACE(OrderProduct.ProductFianlPrice, '$', '')) as ProductFianlPrice ,SUM(REPLACE(OrderMembership.MembershipPrice,'$','')) as MembershipPrice,SUM(REPLACE(OrderServic.ServiceFianlPrice, '$', '')) as ServiceFianlPrice
			FROM OrderMaster
			LEFT JOIN OrderProduct ON OrderProduct.OrderId=OrderMaster.id
			LEFT JOIN OrderMembership ON OrderMembership.OrderId=OrderMaster.id
			JOIN users ON OrderMaster.createdfk=users.adminid
			LEFT JOIN OrderServic ON OrderServic.OrderId=OrderMaster.id
			WHERE users.adminid=:id
			AND OrderMaster.payment_status='CAPTURED'
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<= NOW()
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>= NOW() - INTERVAL :Days DAY
			GROUP BY DATE_FORMAT(OrderMaster.datecreated, '%d')");
		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->bindValue(":Days",$UpcomingsDays,PDO::PARAM_INT);
		$LoginQuery->execute();
		$dataPoints1 = $LoginQuery->fetchAll(PDO::FETCH_ASSOC);
		$newdata = array();
		foreach($dataPoints1 as $row)
		{
			$a=array($row['label'],$row['ProductFianlPrice'],$row['MembershipPrice'],$row['ServiceFianlPrice']);
			array_push($newdata,$a);
		}
		echo  json_encode(["resonse"=>$newdata]);die;
	}
	public function particulerdate2($service_star_time2,$service_end_time2)
	{
		$fromdate2= date("Y-m-d", strtotime($service_star_time2));
		$todate2=date("Y-m-d", strtotime($service_end_time2));
		$db=new db();
		$id=$_SESSION['UserID'];
		$LoginQuery1 = $db->prepare("SELECT DATE_FORMAT(OrderMaster.datecreated, '%d %M, %Y') AS label,SUM(REPLACE(OrderProduct.ProductFianlPrice, '$', '')) as ProductFianlPrice ,SUM(REPLACE(OrderMembership.MembershipPrice,'$','')) as MembershipPrice,SUM(REPLACE(OrderServic.ServiceFianlPrice, '$', '')) as ServiceFianlPrice 
			FROM OrderMaster
			LEFT JOIN OrderProduct ON OrderProduct.OrderId=OrderMaster.id
			LEFT JOIN OrderMembership ON OrderMembership.OrderId=OrderMaster.id
			LEFT JOIN OrderServic ON OrderServic.OrderId=OrderMaster.id
			WHERE OrderMaster.createdfk=:id
			AND OrderMaster.payment_status='CAPTURED'
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate2
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate2
			GROUP BY DATE_FORMAT(OrderMaster.datecreated, '%d')");
		$LoginQuery1->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery1->bindParam(':fromdate2', $fromdate2, PDO::PARAM_STR);
		$LoginQuery1->bindParam(':todate2', $todate2, PDO::PARAM_STR);
		$LoginQuery1->execute();
		$dataPoints2=$result1 = $LoginQuery1->fetchAll(PDO::FETCH_ASSOC);
		$newdata = array();
		foreach($dataPoints2 as $row)
		{
			$a=array($row['label'],$row['ProductFianlPrice'],$row['MembershipPrice'],$row['ServiceFianlPrice']);
			array_push($newdata,$a);
		}
		echo  json_encode(["resonse"=>$newdata]);die;
	}
}

?>