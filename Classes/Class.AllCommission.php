<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class User{

	public function UserDisplay()

	{

				

		$db=new db();

		$id=$_SESSION['UserID'];

			$LoginQuery = $db->prepare("SELECT Commission.InvoiceNumber,concat(IFNULL(Commission.serCommissionAmount,''),IFNULL(Commission.proCommissionAmount,''),IFNULL(Commission.memCommissionAmount,'')) as joinedComField, Commission.OrderTime,clients.FirstName,clients.LastName,clients.ProfileImg,users.username,users.userimg,concat(IFNULL(Service.ServiceName,''),IFNULL(Product.ProductTitle,''),IFNULL(MemberPackage.Name,'')) as joinedField FROM `Commission`

	LEFT JOIN users

	ON users.id=Commission.createdfk

	LEFT JOIN Service

	ON Service.id=Commission.SeriveId

	LEFT JOIN MemberPackage

	ON MemberPackage.id=Commission.MembershipId

	LEFT JOIN Product

	ON Product.id=Commission.ProdcutId

	JOIN clients

	ON clients.id=Commission.Cid

	LEFT JOIN OrderMaster

	ON OrderMaster.InvoiceNumber=Commission.InvoiceNumber


	WHERE users.adminid = :id AND users.usertype ='employee' AND OrderMaster.payment_status='CAPTURED'");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;	

	}



	public function particulerdate($service_star_time,$service_end_time)

	{

		

		 $fromdate= date("Y-m-d", strtotime($service_star_time));

		 $todate=date("Y-m-d", strtotime($service_end_time));

		

		$db=new db();

		$id=$_SESSION['UserID'];

		$LoginQuery = $db->prepare("SELECT clients.FirstName,clients.LastName,clients.ProfileImg,Commission.InvoiceNumber,concat(IFNULL(Commission.serCommissionAmount,''),IFNULL(Commission.proCommissionAmount,''),IFNULL(Commission.memCommissionAmount,'')) as joinedComField, Commission.OrderTime,clients.FirstName,users.username,users.userimg,concat(IFNULL(Service.ServiceName,''),IFNULL(Product.ProductTitle,''),IFNULL(MemberPackage.Name,'')) as joinedField FROM `Commission` LEFT JOIN users ON users.id=Commission.createdfk LEFT JOIN Service ON Service.id=Commission.SeriveId LEFT JOIN MemberPackage ON MemberPackage.id=Commission.MembershipId LEFT JOIN Product ON Product.id=Commission.ProdcutId  JOIN clients ON clients.id=Commission.Cid LEFT JOIN OrderMaster ON OrderMaster.InvoiceNumber=Commission.InvoiceNumber  WHERE users.adminid =:id AND users.usertype ='employee' AND Commission.OrderTime >= :fromdate AND Commission.OrderTime <= :todate AND OrderMaster.payment_status='CAPTURED'");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);

		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;		

			

	}



}

?>



