<?php
    require_once('Exec_Config.php');        
        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Product.php'); 

// $MyProduct = new Product(666);
// $getAllBrand = $MyProduct->getAllProductBrand();
// $brand= json_decode($getAllBrand);

// // echo count(json_decode($getAllBrand,true));

// foreach ($brand as $value) {
//     echo $value->Brand;
// }


// die;             




if(isset($_REQUEST['viewdata']))
{
    $Product=new Product;
    $Product->ActiveProduct();   
}
if(isset($_REQUEST['viewdata2']))
{
    $Product=new Product;
    $Product->AllProduct();  
    // $status = new Product;
    // $status->ProductStatus();   
}
if(isset($_REQUEST['set_status'])){
    $status = new Product;
    $status->ProductStatus();      
}
if(isset($_POST["ProductTitle"]))
{   

    
    $MyProduct = new Product($_POST["id"]);
    $MyProduct->id = $_POST["id"];
    $Iname=explode(".",$_FILES["ProductImage"]["name"]);
    if(!empty($_FILES["ProductImage"]["name"]) ){
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        $MyProduct->ProductImage = $ImgFileName.".".$Iname[1]; 
        $path = DOCUMENT_ROOT.ESUB."/assets/ProductImage/";
        $path = $path . basename($MyProduct->ProductImage);
        if(move_uploaded_file($_FILES["ProductImage"]["tmp_name"], $path)) 
        {
        }
        else
        {
            $userimg = "Client Image was not uploaded please try again.";
        }
    }
            $MyProduct->barcode = stripslashes(strip_tags($_POST["barcode"]));
            $ProductTitle=$MyProduct->ProductTitle =stripslashes(strip_tags($_POST["ProductTitle"])); // $_POST["FirstName"];
            $MyProduct->ProductDescription =stripslashes(strip_tags($_POST["ProductDescription"])); 
            $MyProduct->CompanyCost =stripslashes(strip_tags($_POST["CompanyCost"])); 
            $MyProduct->SellingPrice =stripslashes(strip_tags($_POST["SellingPrice"]));
            $MyProduct->ProductCategory =implode(',',$_POST["ProductCategory"]);

        // if(!isset($_POST["ProductBrand"]))
        // {
        //     $MyProduct->ProductBrand = "";       
        // }
        // else
        // {
        //     $MyProduct->ProductBrand = implode(',',$_POST["ProductBrand"]);

        // }


        $brand_response=[];
        if(!isset($_POST["ProductBrand"]) || $_POST["ProductBrand"][0] == "")
        {
            $MyProduct->ProductBrand = "";       
        }
        else
        {   
            
            $getAllBrand = $MyProduct->getAllProductBrand();    
                
           
           $match_count = 0;
           $matchBrandId = 0;

            foreach (json_decode($getAllBrand) as $value) {
                if($value->Brand==$_POST["ProductBrand"][0]){
                    
                    
                    $matchBrandId = $value->id;
                    $match_count = 1;
                }
                
            }


            if($match_count == 1)
            {
                
               $MyProduct->ProductBrand = $matchBrandId;

            }
            else
            {
                $getlastinsertBrand = $MyProduct->insertNewBrand($_POST["ProductBrand"][0]);
                    $insertedId = json_decode($getlastinsertBrand, true);
               

                if($insertedId['response']) 
                {
                   $MyProduct->ProductBrand = $insertedId['response'];
                   $brand_response = ["brand_name"=>$_POST["ProductBrand"][0],"brand_id"=>$insertedId['response']];
                }
            }


            //$MyProduct->ProductBrand = implode(',',$_POST["ProductBrand"]);
            
        }


        
    
    $MyProduct->NoofPorduct =stripslashes(strip_tags($_POST["NoofPorduct"]));
    $MyProduct->discountinparst =@$_POST["discountinparst"]; 



    if(isset($_POST["SellingPricewithouttax"]))
    {
    $MyProduct->SellingPricewithouttax =@$_POST["SellingPricewithouttax"];         
    }

    if(isset($_POST["sales_tax"]) && !empty($_POST["sales_tax"]))
    {
    $MyProduct->sales_tax = @$_POST["sales_tax"];     
    }
    else
    {
        $MyProduct->sales_tax = 0;
    }
    
    $MyProduct->onlytax = $_POST['onlytax'];
        
    $MyProduct->commit($MyProduct->id);

        

    if($MyProduct)
    {
        $myactivite = new Activites(); // This function for data insert in Activities
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new product'.$ProductTitle ;
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'New product successfully created',"mydata"=>$MyProduct,"new_brand"=>$brand_response]);die;		
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update product'.$ProductTitle ;
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Product detail successfully updated',"mydata"=>$MyProduct,"new_brand"=>$brand_response]);die;
        }			
    }
    else
    {
        echo json_encode(['error'=>'Sorry something wrong']);die;
    }
}
?>