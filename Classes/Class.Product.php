<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



class Product{

	

	public $id;

	public $barcode;

	public $ProductTitle;

	public $ProductDescription;

	public $CompanyCost;

	public $SellingPrice;

	//public $CommissionAmount;

	public $ProductCategory;

	public $ProductBrand;

	public $ProductImage;

	public $NoofPorduct;

    public $discountinparst;

	public $sales_tax;

	public $SellingPricewithouttax;

	public $onlytax;


	



public function __construct($myid = "new"){

		$db = new db();

		$this->id = $myid;

		$this->UserID = "";

		$this->barcode = "";

		$this->ProductTitle = "";

		$this->ProductDescription = "";

		$this->CompanyCost = "";

		$this->SellingPrice = "";

		$this->ProductCategory = "";

		$this->ProductBrand = "";

		$this->ProductImage = "";

		$this->NoofPorduct = "";

		//$this->CommissionAmount = "";

		$this->discountinparst = "";

		$this->sales_tax = "";		

		$this->SellingPricewithouttax = "";		

		$this->onlytax = "";		
		

		if ($myid == "new") {

			$this->datecreated = date("Y-m-d H:i:s");

            $this->datelastupdated = date("Y-m-d H:i:s");

            $this->createdfk = $_SESSION["UserID"];

            $this->updatedfk = $_SESSION["UserID"];

            $this->isactive = 1;

		}else {

			try {

                $query = $db->prepare("SELECT * FROM Product WHERE id=:myid");

                $query->bindValue(':myid', $myid, PDO::PARAM_INT);

                $query->execute();

            } catch (PDOException $e) {

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			while ($info = $query->fetch(PDO::FETCH_ASSOC)) {

				$this->barcode = $info['barcode'];				

				$this->ProductTitle = $info["ProductTitle"];

				$this->ProductDescription = $info["ProductDescription"];

				$this->CompanyCost = $info["CompanyCost"];

				$this->SellingPrice = $info["SellingPrice"];

				//$this->CommissionAmount = $info["CommissionAmount"];

				$this->ProductCategory = $info["ProductCategory"];

				$this->ProductBrand = $info["ProductBrand"];

				$this->ProductImage = $info["ProductImage"];

				$this->NoofPorduct = $info["NoofPorduct"];

				$this->discountinparst = $info["discountinparst"];

				$this->sales_tax = $info["sales_tax"];

				$this->SellingPricewithouttax = $info["SellingPricewithouttax"];

				$this->onlytax = $info["onlytax"];




				

				$this->datecreated = $info["datecreated"];

				$this->datelastupdated = $info["datelastupdated"];

				$this->createdfk = $info["createdfk"];

				$this->updatedfk = $info["updatedfk"];

				$this->isactive = 1;

			}

		}

		

	}

	 public function commit(){

		  $db = new db();

        if ($this->id == "new") {

            try {

                $query = $db->prepare("INSERT INTO `Product` (`ProductTitle`) VALUES ('New')");

                $query->execute();

                $this->id = $db->lastInsertId();

            } catch (PDOException $e) {
				
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);

                exit;

            }

			

        }

		try {

            $query = $db->prepare("UPDATE Product SET 

             `barcode`=:barcode,

			 `ProductTitle`=:ProductTitle,

			 `ProductDescription`=:ProductDescription,

			 `CompanyCost`=:CompanyCost,

			 `SellingPrice`=:SellingPrice,

			 `ProductCategory`=:ProductCategory,

			 `ProductBrand`=:ProductBrand,

			 `ProductImage`=:ProductImage,

			 `NoofPorduct`=:NoofPorduct,

			 `discountinparst`=:discountinparst,

			 `datecreated`=:datecreated,

			 `datelastupdated`=:datelastupdated,

			 `createdfk`=:createdfk,

			 `updatedfk`=:updatedfk,

			 `sales_tax`=:sales_tax,

			 `SellingPricewithouttax`=:SellingPricewithouttax,

			 `onlytax`=:onlytax,

			 `isactive`=:isactive  WHERE id=:myid");

              $query->bindValue(':barcode', $this->barcode, PDO::PARAM_STR);

              $query->bindValue(':ProductTitle', $this->ProductTitle, PDO::PARAM_STR);

              $query->bindValue(':ProductDescription', $this->ProductDescription, PDO::PARAM_STR);	

              $query->bindValue(':CompanyCost', $this->CompanyCost, PDO::PARAM_STR);

              $query->bindValue(':SellingPrice', $this->SellingPrice, PDO::PARAM_STR);

            //  $query->bindValue(':CommissionAmount', $this->CommissionAmount, PDO::PARAM_STR);

              $query->bindValue(':ProductCategory', $this->ProductCategory, PDO::PARAM_STR);

              $query->bindValue(':ProductBrand', $this->ProductBrand, PDO::PARAM_STR);

              $query->bindValue(':ProductImage', $this->ProductImage, PDO::PARAM_STR);

              $query->bindValue(':NoofPorduct', $this->NoofPorduct, PDO::PARAM_STR);

              $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);

			  $query->bindValue(':discountinparst', $this->discountinparst, PDO::PARAM_STR);

			  $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);

			  $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);

			  $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);

			  $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);

			$query->bindValue(':sales_tax', $this->sales_tax, PDO::PARAM_STR);

			$query->bindValue(':SellingPricewithouttax', $this->SellingPricewithouttax, PDO::PARAM_STR);

			$query->bindValue(':onlytax', $this->onlytax, PDO::PARAM_STR);

			  $query->bindValue(':myid', $this->id, PDO::PARAM_STR);

			   $query->execute();

		}catch (PDOException $e) {

           echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;

            exit;

        }

		 //return $this->id;

		return $this->id;

	 }





	 public function ActiveProduct(){

				

				$db= new db();



				$isactive=1;



				$id=$_SESSION['UserID'];

				$LoginQuery = $db->prepare("select * from (SELECT Product.*,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,(SELECT GROUP_CONCAT(Category) FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as category FROM `Product` JOIN users ON (Product.createdfk=users.id OR Product.createdfk=users.adminid OR Product.createdfk=users.sid) WHERE (users.id=:id or users.adminid=:id or users.sid=:id) and  Product.isactive=:isactive GROUP BY Product.id) as products");

				$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

				$LoginQuery->bindParam(':isactive', $isactive, PDO::PARAM_INT);

				$LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);				



		}


		public function getAllProductBrand(){


				$db= new db();
                $id=$_SESSION['UserID'];
           		
             // $LoginQuery=$db->prepare("SELECT ProductBrand.* FROM `ProductBrand` JOIN users ON (ProductBrand.createdfk=users.id OR ProductBrand.createdfk=users.adminid OR ProductBrand.createdfk=users.sid) WHERE users.id=:id AND isactive=:isactive  GROUP BY ProductBrand.id"); 
             $LoginQuery = $db->prepare("SELECT ProductBrand.id,ProductBrand.Brand FROM `ProductBrand` JOIN users ON (ProductBrand.createdfk=users.id OR ProductBrand.createdfk=users.adminid OR ProductBrand.createdfk=users.sid) WHERE (users.id=:id or users.adminid=:id or users.sid=:id) AND ProductBrand.isactive=1 GROUP BY ProductBrand.id");

                $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

               

                $LoginQuery->execute();

                $result = $LoginQuery->fetchAll();
               	
                return json_encode($result);

		}


		public function insertNewBrand($brandname){

			// $this->datecreated = date("Y-m-d H:i:s");

   //          $this->datelastupdated = date("Y-m-d H:i:s");

   //          $this->createdfk = $_SESSION["UserID"];

   //          $this->updatedfk = $_SESSION["UserID"];

   //          $this->isactive = 1;

				$db= new db();
			
			$addNewProduct = $db->prepare("insert into `ProductBrand` (Brand,datecreated,datelastupdated,createdfk,updatedfk,isactive) values(?,?,?,?,?,?)");
				  $brandname = $brandname;
				  $datecreated = date("Y-m-d H:i:s");
				  $datelastupdated = date("Y-m-d H:i:s");
				  $createdfk = $_SESSION["UserID"];
				  $updatedfk = $_SESSION["UserID"];
				  $isactive = 1;
				  $addResult = $addNewProduct->execute([$brandname,$datecreated,$datelastupdated,$createdfk,$updatedfk,$isactive]);
				  $lastInsertId = $db->lastInsertId();
				  if($addResult == 1)
				  {
				    return json_encode(["response"=>$lastInsertId]);die;
				  }
				  else
				  {
				   return json_encode(["error"=>'error']);die;
				 }

		}

			 public function AllProduct(){

				
				$db= new db();

				
 
				$orderby = "";
				$search = "";
				$pid = "";
				if(isset($_REQUEST['query']))
				{
        			$query = $_REQUEST['query'];
        			if($query['sort_name']!=""){
        				$orderby = " order by ".$query['sort_name']." ".$query['sort_type'];
        			}

        			if($query['search']!=""){
        				$search = 'where products.ProductTitle like "%'.$query['search'].'%" or products.ProductDescription like "%'.$query['search'].'%" or products.category like "%'.$query['search'].'%" or products.SellingPrice like "%'.$query['search'].'%" or Brand like "%'.$query['search'].'%" or barcode like "%'.$query['search'].'%" ';
        			}
    			} 

    			if(!empty($_REQUEST['viewdata2'])){
    				$pid = " Product.id=".$_REQUEST['viewdata2'];
    			}else{
    				$id=$_SESSION['UserID'];
    				$pid = " isarchive=1 and (users.id=$id or users.adminid=$id or users.sid=$id) ";
    			}

				$LoginQuery=$db->prepare('select * from (SELECT Product.*,(SELECT GROUP_CONCAT(Brand) FROM ProductBrand WHERE FIND_IN_SET(id,Product.ProductBrand)) as Brand,(SELECT GROUP_CONCAT(Category) FROM ProductCategory WHERE FIND_IN_SET(id,Product.ProductCategory)) as category FROM `Product` JOIN users ON (Product.createdfk=users.id OR Product.createdfk=users.adminid OR Product.createdfk=users.sid) WHERE  '.$pid.' GROUP BY Product.id '.$orderby.') as products '.$search);


				$LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				$LoginQuery=$db->prepare('select sales_tax from CompanyInformation where createdfk=:createdfk');
				$LoginQuery->bindParam(':createdfk',$result[0]['createdfk']);	
				$LoginQuery->execute();
				$sales_tax = $LoginQuery->fetch();

				echo json_encode(['result'=>$result,'sales_tax'=>$sales_tax['sales_tax']]);				



		}



		public function ProductStatus(){

		

				$db=new db();

				

				if(isset($_POST['status']) && $_POST['status'] == '1'){

					$id=$_POST['id'];

			 		$disable = $db->prepare("UPDATE `Product` SET `isactive` = '0' WHERE `id` = :id");

			     	$disable->bindParam(':id', $id, PDO::PARAM_INT);

			     	echo $disable->execute();

				}

				if(isset($_POST['status']) && $_POST['status'] == '0'){

					$id=$_POST['id'];

					$enable = $db->prepare("UPDATE `Product` SET `isactive` = '1' WHERE `id` = :id");

			     	$enable->bindParam(':id', $id, PDO::PARAM_INT);

			     	echo $enable->execute();

				}

			    	

	}	

}



?>