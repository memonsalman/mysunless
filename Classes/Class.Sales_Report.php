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

		$db=new db();
		$id=$_SESSION['UserID'];

		$getalltraction = ""; 
		$g_getalltraction = "";
		if(!empty($_GET["getalltraction"])){
			$getalltraction = " AND OrderMaster.createdfk IN (".$_GET['getalltraction'].") ";
			$g_getalltraction = " AND createdfk IN(".$_GET['getalltraction'].") ";
		}

		$selectcutomer = "";
		$g_selectcutomer = "";
		if(!empty($_GET["selectcutomer"])){
			$selectcutomer = " AND OrderMaster.cid IN (".$_GET['selectcutomer'].") ";
			$g_selectcutomer = "AND cid IN(".$_GET['selectcutomer'].") ";
		}

		if(!empty($_GET['daterange'])){
			$selectdaterang = explode(' - ',$_GET['daterange']);
			$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
			$todate = date("Y-m-d", strtotime($selectdaterang[1]));

			$selectdaterang = " AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>='$fromdate' 
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<='$todate' ";
		}else{
			$selectdaterang = "";
		}		

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
			FROM `OrderMaster` JOIN OrderPayment ON OrderPayment.OrderId=OrderMaster.id 
			WHERE 
			OrderMaster.createdfk IN (select id from users where id=$id or adminid=$id) 
			$getalltraction 
			$selectcutomer
			$selectdaterang
			AND OrderMaster.payment_status='CAPTURED'
			AND OrderPayment.payment_status='CAPTURED' 
			");
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();

		$Log = $db->prepare("SELECT 
			SUM(TotalgiftAmount) AS giftread FROM `OrderMaster`

			WHERE  
			OrderMaster.createdfk IN (select id from users where id=$id or adminid=$id) 
			$g_getalltraction
			$g_selectcutomer
			$selectdaterang
			AND gstatus = 1
			");

		$Log->execute();
		$resul = $Log->fetchAll();
		$result[0]["giftread"] = $resul[0]["giftread"];
		$result[0][11] = $resul[0]["giftread"];
		array_push($result, $result[0]["giftread"],$result[0][11]);
		unset($result[1],$result[2]);



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
		public function particulerdate2($service_star_time2,$service_end_time2,$user)
		{
			$fromdate2= date("Y-m-d", strtotime($service_star_time2));
			$todate2=date("Y-m-d", strtotime($service_end_time2));
			$db=new db();
			$id=$user;

		/*$LoginQuery1 = $db->prepare("SELECT DATE_FORMAT(OrderMaster.datecreated, '%b') AS label,SUM(REPLACE(OrderProduct.ProductFianlPrice, '$', '')) as ProductFianlPrice ,SUM(REPLACE(OrderMembership.MembershipPrice,'$','')) as MembershipPrice,SUM(REPLACE(OrderServic.ServiceFianlPrice, '$', '')) as ServiceFianlPrice, SUM(REPLACE(Ordergift.TotalgiftAmount, '$', '')) as Ordergift  
			FROM OrderMaster
			LEFT JOIN OrderProduct ON OrderProduct.OrderId=OrderMaster.id
			LEFT JOIN OrderMembership ON OrderMembership.OrderId=OrderMaster.id
			LEFT JOIN OrderServic ON OrderServic.OrderId=OrderMaster.id
			LEFT JOIN Ordergift ON Ordergift.OrderId=OrderMaster.id
			WHERE OrderMaster.createdfk IN ( ".$id." )
			AND OrderMaster.payment_status='CAPTURED'
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate2
			AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate2
			GROUP BY DATE_FORMAT(OrderMaster.datecreated, '%b')");*/


			$LoginQuery1 = $db->prepare("SELECT DATE_FORMAT(OrderMaster.datecreated, '%b') AS label,
				SUM(REPLACE(OrderMaster.TotalseriveAmount, '$', '')) as ServiceFianlPrice,
				SUM(REPLACE(OrderMaster.TotalProductAmount, '$', '')) as ProductFianlPrice,
				SUM(REPLACE(OrderMaster.TotalMembershipAmount, '$', '')) as MembershipPrice, 
				SUM(REPLACE(OrderMaster.TotalgiftAmount, '$', '')) as Ordergift
				FROM OrderMaster JOIN OrderPayment ON OrderPayment.OrderId=OrderMaster.id 
				WHERE OrderMaster.createdfk IN ( ".$id." ) 
				AND OrderMaster.payment_status='CAPTURED'
				AND OrderPayment.payment_status='CAPTURED'
				AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate2 AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate2
				GROUP BY DATE_FORMAT(OrderMaster.datecreated, '%b')");
		// $LoginQuery1->bindParam(':id', $id);
			$LoginQuery1->bindParam(':fromdate2', $fromdate2);
			$LoginQuery1->bindParam(':todate2', $todate2);
			$LoginQuery1->execute();
			$dataPoints2=$result1 = $LoginQuery1->fetchAll();
			return $dataPoints2;

		}
	}

	?>