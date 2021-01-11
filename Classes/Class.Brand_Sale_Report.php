<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class SalseReport{

	public function getalltraction()
	{
		$db= new db();
		$selectdaterang = "";
		$ProductCategoryText = "";
		$ProductBrand = "";

		if(!empty($_GET['selectdaterang'])){

			$selectdaterang =explode(' - ',$_GET['selectdaterang']);
			$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
			$todate = date("Y-m-d", strtotime($selectdaterang[1]));

			$selectdaterang = " AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>='$fromdate' 
			AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<='$todate' ";

		}

		if(!empty($_GET['ProductBrand'])){
			$ProductBrand = " AND Product.ProductBrand IN (".$_GET['ProductBrand'].") ";

		}


		if(!empty($_GET['ProductCategory'])){
			
			$ProductCategoryArray = explode(',',$_GET['ProductCategory']);
			$ProductCategory = [] ;
			foreach ($ProductCategoryArray as $key => $value) {
				array_push($ProductCategory, " FIND_IN_SET(".$value.",Product.ProductCategory) ") ;
			}

			$ProductCategoryText = " AND ( ".implode(" or ",$ProductCategory)." ) ";

		}

		$selectuser = $_GET['selectuser'];

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
		

		// if($_SESSION['usertype']=='Admin'){
		// 	$id = "";
		// }
		// else{
		// 	$session_id = $_SESSION['UserID'];
		// 	$id = " AND OrderProduct.createdfk IN (select id from users where id=$session_id or adminid=$session_id or sid=$session_id) ";
		// }

		$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
					SUM(OrderProduct.ProdcutQuality) AS Quantity,
					SUM(OrderProduct.ProductTaxPrice) AS ProductTax,
					SUM(OrderProduct.ProductCostPrice) AS Cost_Price,
					SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
					SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-OrderProduct.ProductCostPrice)) AS profit 
					FROM `OrderPayment` 
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
					JOIN Product ON Product.id=OrderProduct.ProdcutId 
				   JOIN users ON OrderPayment.Cratedfk=users.id
				   JOIN clients ON clients.id = OrderPayment.Cid
					WHERE OrderPayment.payment_status='CAPTURED'
			 	$selectdaterang
			 	$ProductBrand
			 	$ProductCategoryText
			 	$selectuser 
			 	GROUP BY Category,Brand");
		$LoginQuery->execute();		
		$result = $LoginQuery->fetchAll();
		// print_r($result);
		// die();
		echo json_encode($result);die;




		}



	}

	?>