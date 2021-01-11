<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class SalseReport{

	 		public function getalltraction()
	 		{
                //$selectdaterang =explode(' - ',$_GET['selectdaterang']);
                $db= new db();
                //$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
                //$todate = date("Y-m-d", strtotime($selectdaterang[1]));

                $add_query = "";

                if(!empty($_GET['selectdaterang'])){

                $selectdaterang =explode(' - ',$_GET['selectdaterang']);
                $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
                $todate = date("Y-m-d", strtotime($selectdaterang[1]));
                $add_query = " AND  DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')>='".$fromdate."'
                        AND DATE_FORMAT(OrderProduct.OrderTime, '%Y-%m-%d')<='".$todate."' ";
                
                }

                $add_query.="  Order by OrderProduct.id DESC ";

                
                
                 if(!empty($_GET['getalltraction'])){
                    $getalltraction = "( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction']." ) ";
                  }
                
                $selectcutomer = $_GET['selectcutomer'];
               
                
                $id=$_SESSION['UserID'];


                if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
                {

                    $LoginQuery = $db->prepare("
                        SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,Product.ProductTitle,clients.id as clientid,clients.ProfileImg, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg ,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime  
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction) AND OrderPayment.Cid IN($selectcutomer)
                        $add_query");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();

                    echo json_encode($result);die;
                }
                else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction)
                        $add_query");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();
                    echo json_encode($result);die;
                }
                else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
                {

                    $selectcutomer = $_GET['selectcutomer'];
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost, CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cid IN($selectcutomer)
                        $add_query");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);  

                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();
                    echo json_encode($result);die;
                }
                else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("
                        SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle,OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND (users.adminid=:id OR users.id=:id) 
                        $add_query ");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();
                    echo json_encode($result);die;
                }
                else if(empty($fromdate) && empty($todate) && empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("
                        SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle,OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND (users.adminid=:id OR users.id=:id) 
                        $add_query ");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();
                    echo json_encode($result);die;
                }
                 else if(empty($fromdate) && empty($todate) && !empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction)
                        $add_query");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();
                    echo json_encode($result);die;
                }
                else if(empty($fromdate) && empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
                {
                     $LoginQuery = $db->prepare("
                        SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,Product.ProductTitle,clients.id as clientid,clients.ProfileImg, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg ,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime  
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction) AND OrderPayment.Cid IN($selectcutomer)
                        $add_query");
                    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

                    $LoginQuery->bindParam(':selectcutomer', $selectcutomer, PDO::PARAM_STR);
                    $LoginQuery->execute();
                    $result = $LoginQuery->fetchAll();

                    echo json_encode($result);die;
                }
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

                $add_query.="  Order by OrderProduct.id DESC ";

                 if(!empty($_GET['getalltraction'])){
                    $getalltraction = "( select id from users where id=".$_GET['getalltraction']." or adminid=".$_GET['getalltraction']." ) ";
                  }

    			$selectcutomer = $_GET['selectcutomer'];

                if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && !empty($selectcutomer))
                {

                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost, CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction) 
                        AND OrderPayment.Cid IN($selectcutomer)
                        $add_query ");
               
             		$LoginQuery->execute();
             		$result = $LoginQuery->fetchAll();
             		echo json_encode($result);die;
                }

                else if(!empty($fromdate) && !empty($todate) && !empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost, CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cratedfk IN($getalltraction) 
                        $add_query ");

                 		$LoginQuery->execute();
                 		$result = $LoginQuery->fetchAll();
                 		echo json_encode($result);die;
                }
                else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && !empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost, CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg,OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED'
                        AND OrderPayment.Cid IN($selectcutomer)
                        $add_query");

                 		$LoginQuery->execute();
                 		$result = $LoginQuery->fetchAll();
                 		echo json_encode($result);die;
                    }
                else if(!empty($fromdate) && !empty($todate) && empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED' 
                        $add_query
                        ");

                 		$LoginQuery->execute();
                 		$result = $LoginQuery->fetchAll();
                 		echo json_encode($result);die;
                }
                else if(empty($fromdate) && empty($todate) && empty($getalltraction) && empty($selectcutomer))
                {
                    $LoginQuery = $db->prepare("SELECT OrderProduct.OrderId,OrderProduct.InvoiceNumber,ProductCategory.Category,CONCAT(clients.FirstName,' ',clients.LastName) AS custname,clients.id as clientid,clients.ProfileImg,Product.ProductTitle, OrderProduct.ProdcutQuality, Product.CompanyCost,CONCAT(users.firstname, ' ', users.lastname) AS fullname,users.id as UserID,users.username,users.userimg, OrderProduct.ProductFianlPrice,(REPLACE(OrderProduct.ProductFianlPrice,'$ ','')-Product.CompanyCost) as profit,OrderProduct.OrderTime
                        FROM `OrderPayment`
                        JOIN OrderProduct ON OrderPayment.OrderId=OrderProduct.OrderId
                        JOIN Product ON Product.id=OrderProduct.ProdcutId
                        JOIN ProductCategory ON Product.ProductCategory=ProductCategory.id
                        JOIN users ON OrderPayment.Cratedfk=users.id
                        JOIN clients ON clients.id = OrderPayment.Cid
                        WHERE OrderPayment.payment_status='CAPTURED' 
                        $add_query
                        ");

                        $LoginQuery->execute();
                        $result = $LoginQuery->fetchAll();
                        echo json_encode($result);die;
                }
          
			}



	
	}



?>