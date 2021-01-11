<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class SalseReport{

	public function getalltraction()
	{
		$db= new db();
		$selectdaterang = "";
		$selectcutomer = "";
		$getalltraction = "";

		if(!empty($_GET['selectdaterang'])){

			$selectdaterang =explode(' - ',$_GET['selectdaterang']);
			$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
			$todate = date("Y-m-d", strtotime($selectdaterang[1]));

			$selectdaterang = " AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>='$fromdate' 
			AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<='$todate' ";

		}

		if(!empty($_GET['getalltraction'])){
			$getalltraction = " AND Product.ProductBrand=".$_GET['getalltraction']." ";

		}

		if(!empty($_GET['selectcutomer'])){
			
			$selectcutomer = " AND FIND_IN_SET(".$_GET['selectcutomer'].",Product.ProductCategory) ";
		}

		if($_SESSION['usertype']=='Admin'){
			$id = "";
		}
		else{
			$session_id = $_SESSION['UserID'];
			$id = " AND OrderProduct.createdfk IN (select id from users where id=$session_id or adminid=$session_id or sid=$session_id) ";
		}

		$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
					SUM(OrderProduct.ProdcutQuality) AS Quantity,
					SUM(OrderProduct.ProductCostPrice) AS Cost_Price,
					SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
					SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
					FROM `OrderPayment` 
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
					JOIN Product ON Product.id=OrderProduct.ProdcutId 
					WHERE OrderPayment.payment_status='CAPTURED'
			 	$selectdaterang
			 	$getalltraction
			 	$selectcutomer
			 	$id 
			 	GROUP BY Category,Brand");
		$LoginQuery->execute();
		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;


		if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
		{
     //    	$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
			 	// JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
			 	// JOIN Product ON Product.id=OrderProduct.ProdcutId
			 	// JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
			 	// JOIN users ON OrderPayment.Cratedfk=users.sid
			 	// WHERE OrderPayment.payment_status='CAPTURED'
			 	// AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
			 	// AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
			 	// AND ProductBrand.id=:getalltraction 
			 	// AND Product.ProductCategory=:selectcutomer
			 	// AND users.adminid=:id 
			 	// OR users.sid=:id
			 	// GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");

        	/*$LoginQuery = $db->prepare("SELECT ProductCategory.Category as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
			 	JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
			 	JOIN Product ON Product.id=OrderProduct.ProdcutId
			 	JOIN ProductCategory
			 	-- ON ProductCategory.id IN Product.ProductCategory
			 	-- JOIN users ON OrderPayment.Cratedfk=users.sid
			 	WHERE OrderPayment.payment_status='CAPTURED'
			 	AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
			 	AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
			 	AND Product.ProductBrand=:getalltraction 
			 	AND FIND_IN_SET(:selectcutomer,Product.ProductCategory)
			 	AND ProductCategory.id=:selectcutomer
			 	$id 
			 	GROUP BY Category,Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");*/

			 	$LoginQuery = $db->prepare("SELECT ProductCategory.Category as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
			 		JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
			 		JOIN Product ON Product.id=OrderProduct.ProdcutId
			 		JOIN ProductCategory
			 	-- ON ProductCategory.id IN Product.ProductCategory
			 	-- JOIN users ON OrderPayment.Cratedfk=users.sid
			 	WHERE OrderPayment.payment_status='CAPTURED'
			 	AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
			 	AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
			 	AND Product.ProductBrand=:getalltraction 
			 	AND FIND_IN_SET(:selectcutomer,Product.ProductCategory)
			 	AND ProductCategory.id=:selectcutomer
			 	$id 
			 	GROUP BY Category,Brand");

				// $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT); 	
			 	$LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
			 	$LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
			 	$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
			 	$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
			 	$LoginQuery->execute();
			 	$result = $LoginQuery->fetchAll();

			 	if(empty($result))
			 	{
			 		echo json_encode($result);die;
			 	}


			 	echo json_encode($result);die;			

			 }
			 else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
			 {
    //     	$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
				// JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
				// JOIN Product ON Product.id=OrderProduct.ProdcutId
				// JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
				// JOIN users ON OrderPayment.Cratedfk=users.sid
				// WHERE OrderPayment.payment_status='CAPTURED'
				// AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
				// AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
				// AND ProductBrand.id=:getalltraction 
				// AND users.adminid=:id 
				// OR users.sid=:id
				// GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice 

				// ");

        	/*$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ' ) FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
				SUM(OrderProduct.ProdcutQuality) AS Quantity,
				SUM(Product.CompanyCost) AS Cost_Price,
				SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
				SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
				FROM `OrderPayment` 
				JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
				JOIN Product ON Product.id=OrderProduct.ProdcutId 
				WHERE OrderPayment.payment_status='CAPTURED' 
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
				AND Product.ProductBrand=:getalltraction
				$id
				GROUP BY Category,Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");*/

				$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ' ) FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
					SUM(OrderProduct.ProdcutQuality) AS Quantity,
					SUM(Product.CompanyCost) AS Cost_Price,
					SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
					SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
					FROM `OrderPayment` 
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
					JOIN Product ON Product.id=OrderProduct.ProdcutId 
					WHERE OrderPayment.payment_status='CAPTURED' 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
					AND Product.ProductBrand=:getalltraction
					$id
					GROUP BY Category,Brand");

				// $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT); 	
				$LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();


				if(empty($result))
				{
					echo json_encode($result);die;
				}
				
				
				echo json_encode($result);die;
			}
			else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
			{

			/*$LoginQuery = $db->prepare("SELECT ProductCategory.Category as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
				SUM(OrderProduct.ProdcutQuality) AS Quantity,
				SUM(Product.CompanyCost) AS Cost_Price,
				SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
				SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
				FROM `OrderPayment` 
				JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
				JOIN Product ON Product.id=OrderProduct.ProdcutId 
				JOIN ProductCategory
				WHERE OrderPayment.payment_status='CAPTURED' 
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
				-- AND Product.ProductCategory=:selectcutomer
				AND FIND_IN_SET(:selectcutomer,Product.ProductCategory)
			 	AND ProductCategory.id=:selectcutomer
				$id
				GROUP BY Category,Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");*/

				$LoginQuery = $db->prepare("SELECT ProductCategory.Category as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
					SUM(OrderProduct.ProdcutQuality) AS Quantity,
					SUM(Product.CompanyCost) AS Cost_Price,
					SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
					SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
					FROM `OrderPayment` 
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
					JOIN Product ON Product.id=OrderProduct.ProdcutId 
					JOIN ProductCategory
					WHERE OrderPayment.payment_status='CAPTURED' 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
				-- AND Product.ProductCategory=:selectcutomer
				AND FIND_IN_SET(:selectcutomer,Product.ProductCategory)
				AND ProductCategory.id=:selectcutomer
				$id
				GROUP BY Category,Brand");

				// $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
				$LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();

				// $LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit
				// FROM `OrderPayment`
				// JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
				// JOIN Product ON Product.id=OrderProduct.ProdcutId
				// JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
				// JOIN users ON OrderPayment.Cratedfk=users.sid
				// WHERE OrderPayment.payment_status='CAPTURED'
				// AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
				// AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate  
				// AND Product.ProductCategory=:selectcutomer 
				// AND (users.adminid=:id 
				// OR users.sid=:id) 
				// GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");

				// $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
				// $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
		 	// 	$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
		 	// 	$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				// $LoginQuery->execute();
				// $result = $LoginQuery->fetchAll();
				/*if(!$result[0]['Brand'])
				{
					$result=[];
				}*/

				// if(empty($result))
				// {
				// 	echo json_encode($result);die;
				// }
				// else
				// {
				// 	if($result[0]['Brand']!="" && $result[0]["Quantity"]== "" || $result[0]["Cost_Price"]=="" || $result[0]["ProductFianlPrice"]=="" || $result[0]["profit"] == "" )
				// 	{
				// 		$result=[];
				// 	}
				// }


				//echo $id." ".$selectcutomer." ".$fromdate." ".$todate;
				echo json_encode($result);die;
			}
			else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
			{
			// $LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  
			// 	FROM `OrderPayment`
			// 	JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
			// 	JOIN Product ON Product.id=OrderProduct.ProdcutId
			// 	JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
			// 	JOIN users ON OrderPayment.Cratedfk=users.sid
			// 	WHERE OrderPayment.payment_status='CAPTURED'
			// 	AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate AND users.adminid=:id OR users.sid=:id
			// 	GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice
			// 	");


			/*$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
				SUM(OrderProduct.ProdcutQuality) AS Quantity,
				SUM(Product.CompanyCost) AS Cost_Price,
				SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
				SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
				FROM `OrderPayment` 
				JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
				JOIN Product ON Product.id=OrderProduct.ProdcutId 
				WHERE OrderPayment.payment_status='CAPTURED' 
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
				AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
				$id
				GROUP BY Category,Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");*/


				$LoginQuery = $db->prepare("SELECT (SELECT GROUP_CONCAT(Category separator ', ') FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as Category,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,
					SUM(OrderProduct.ProdcutQuality) AS Quantity,
					SUM(Product.CompanyCost) AS Cost_Price,
					SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice,
					SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit 
					FROM `OrderPayment` 
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId 
					JOIN Product ON Product.id=OrderProduct.ProdcutId 
					WHERE OrderPayment.payment_status='CAPTURED' 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
					$id
					GROUP BY Category,Brand");

				// $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT); 	
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();

				if(empty($result))
				{
					echo json_encode($result);die;
				}

				echo json_encode($result);die;
			}


		}
		public function getalltraction2()
		{
			$selectdaterang =explode(' - ',$_GET['selectdaterang']);
			$db= new db();
			$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
			$todate = date("Y-m-d", strtotime($selectdaterang[1]));
			$getalltraction = $_GET['getalltraction'];
			$selectcutomer = $_GET['selectcutomer'];
			if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
			{
				$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
					JOIN Product ON Product.id=OrderProduct.ProdcutId
					JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
					JOIN users ON OrderPayment.Cratedfk=users.sid
					WHERE OrderPayment.payment_status='CAPTURED'
					AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
					AND ProductBrand.id=:getalltraction 
					AND Product.ProductCategory=:selectcutomer
					GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");
				$LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
				$LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();
				

				if(empty($result))
				{
					echo json_encode($result);die;
				}
				else
				{
					if($result[0]['Brand']!="" && $result[0]["Quantity"]== "" || $result[0]["Cost_Price"]=="" || $result[0]["ProductFianlPrice"]=="" || $result[0]["profit"] == "" )
					{
						$result=[];
					}
				}

				echo json_encode($result);die;			

			}
			else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
			{
				$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
					JOIN Product ON Product.id=OrderProduct.ProdcutId
					JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
					JOIN users ON OrderPayment.Cratedfk=users.sid
					WHERE OrderPayment.payment_status='CAPTURED'
					AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate 
					AND ProductBrand.id=:getalltraction
					GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");
				$LoginQuery->bindParam(':getalltraction', $getalltraction, PDO::PARAM_INT);
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();
				/*if(!$result[0]['Brand'])
				{
					$result=[];
				}*/
				

				if(empty($result))
				{
					echo json_encode($result);die;
				}
				else
				{
					if($result[0]['Brand']!="" && $result[0]["Quantity"]== "" || $result[0]["Cost_Price"]=="" || $result[0]["ProductFianlPrice"]=="" || $result[0]["profit"] == "" )
					{
						$result=[];
					}
				}

				echo json_encode($result);die;
			}
			else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
			{
				$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
					JOIN Product ON Product.id=OrderProduct.ProdcutId
					JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
					JOIN users ON OrderPayment.Cratedfk=users.sid
					WHERE OrderPayment.payment_status='CAPTURED'
					AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate 
					AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate  
					AND Product.ProductCategory=:selectcutomer
					GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");
				$LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();
				/*if(!$result[0]['Brand'])
				{
					$result=[];
				}*/

				if(empty($result))
				{
					echo json_encode($result);die;
				}
				else
				{
					if($result[0]['Brand']!="" && $result[0]["Quantity"]== "" || $result[0]["Cost_Price"]=="" || $result[0]["ProductFianlPrice"]=="" || $result[0]["profit"] == "" )
					{
						$result=[];
					}
				}

				echo json_encode($result);die;
			}
			else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
			{

				$LoginQuery = $db->prepare("SELECT ProductBrand.Brand, SUM(OrderProduct.ProdcutQuality) AS Quantity, SUM(Product.CompanyCost) AS Cost_Price, SUM(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')) AS ProductFianlPrice, SUM((REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost)) AS profit  FROM `OrderPayment`
					JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
					JOIN Product ON Product.id=OrderProduct.ProdcutId
					JOIN ProductBrand ON Product.ProductBrand=ProductBrand.id
					JOIN users ON OrderPayment.Cratedfk=users.sid
					WHERE OrderPayment.payment_status='CAPTURED'
					AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<=:todate
					GROUP BY ProductBrand.Brand,OrderProduct.ProdcutQuality,Product.CompanyCost,OrderProduct.ProductFianlPrice");
				$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
				$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
				$LoginQuery->execute();
				$result = $LoginQuery->fetchAll();
				/*if(!$result[0]['Brand'])
				{
					$result=[];
				}*/


				if(empty($result))
				{
					echo json_encode($result);die;
				}
				else
				{
					if($result[0]['Brand']!="" && $result[0]["Quantity"]== "" || $result[0]["Cost_Price"]=="" || $result[0]["ProductFianlPrice"]=="" || $result[0]["profit"] == "" )
					{
						$result=[];
					}
				}

				echo json_encode($result);die;			

			}

		}



	}

	?>