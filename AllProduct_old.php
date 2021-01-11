<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}




if(isset($_SESSION['UserID']))
{
  $id=$_SESSION['UserID'];

  $query = $db->prepare("SELECT sales_tax FROM CompanyInformation WHERE createdfk=:id");
  $query->bindValue(':id', $id, PDO::PARAM_INT);
  $query->execute();
  $res = $query->fetch(PDO::FETCH_ASSOC);
  $mysalestax = $res['sales_tax'];




  $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  @$clientcreateprmistion=$result['ClientCreate'];
  $UsersLimit=$result['UsersLimit'];
  $ClientsLimit=$result['ClientsLimit'];
  $sid=$result['sid'];
  $usertype=$_SESSION['usertype'];
}
$id=$_SESSION['UserID'];
$total_user = $db->prepare("SELECT sid FROM `clients` WHERE `createdfk`=:id");
$total_user->bindParam(':id', $id, PDO::PARAM_INT);
$total_user->execute();
$all=$total_user->fetch(PDO::FETCH_ASSOC);
$mysid=$all['sid'];
if($mysid!=0)
{
 $id=$_SESSION['UserID'];
 $total_user2 = $db->prepare("SELECT * FROM `clients` WHERE `sid`=:mysid");
 $total_user2->bindParam(':mysid', $mysid, PDO::PARAM_INT);
 $total_user2->execute();
 $number_of_users = $total_user2->rowCount();
 if($ClientsLimit!='full')
 {
  if($number_of_users >= $ClientsLimit)
  {
    header("Location: ../index.php");die;  
  }
}
}

if(isset($_REQUEST['barcode'])){
  $barcode = $_REQUEST['barcode'];
  $stmt = $db->prepare("SELECT * from `Product` WHERE barcode=:barcode");
  $stmt->bindParam(':barcode',$barcode);
  $stmt->execute(); 
  if($stmt->rowCount() > 0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["resonse" => array('found' => 'found' , 'id' => $row['id'])]);die;
  }else{
    echo json_encode(["resonse" => 'not-found']);die;
  }
}

if(isset($_GET["id"])){
  $mycategory = $_GET["id"];
}else{
  $mycategory = "new";
}

if(isset($_GET["id"])){
  $MyCLient = $_GET["id"];
}else{
  $MyCLient = "new";
}



$action="";
if(isset($_GET["action"])){
  $action=$_GET["action"];
}


$ProductTitle="";
$ProductDescription="";
$CompanyCost="";
$SellingPrice="";
$ProductCategory="";
$ProductBrand="";
$ProductImage="";
$NoofPorduct="";
$discountinparst="";
if($action == "edit"){
  $EditClients=$db->prepare("select * from `Product` where id=:MyCLient");
  $EditClients->bindValue(":MyCLient",$MyCLient, PDO::PARAM_INT);
  $EditClients->execute();
  if($EditClients->rowCount() > 0){
    while($GetClients=$EditClients->fetch(PDO::FETCH_ASSOC)){
      $ProductTitle=$GetClients['ProductTitle'];
      $ProductDescription=$GetClients['ProductDescription'];
      $CompanyCost=$GetClients['CompanyCost'];
      $SellingPrice=$GetClients['SellingPrice'];
      $ProductCategory=$GetClients['ProductCategory'];
      $ProductBrand=$GetClients['ProductBrand'];
      $ProductImage = $GetClients['ProductImage'];
      $NoofPorduct=$GetClients['NoofPorduct'];
      $discountinparst=$GetClients['discountinparst'];
    }
  }
}
if($action == "delete"){
  $DeleteClient = $db->prepare("delete from `Product` where id=:MyCLient");
  $DeleteClient->bindValue(":MyCLient",$MyCLient,PDO::PARAM_INT);
  $DeleteClient->execute();
  header('Location: AllClients.php');
}

if($action == "selCategory")
{
  //$mycategory= base64_decode($_POST['editid']);
 $id=$_SESSION['UserID'];

 $SSelectCat=$db->prepare("select Category from ProductCategory where createdfk=:id");
    //$SSelectCat->bindValue(':mycategory',$mycategory,PDO::PARAM_INT);
 $SSelectCat->bindValue(':id',$id,PDO::PARAM_INT);
 $SSelectCat->execute();
 $select = $SSelectCat->fetchAll();
    //$select=$SSelectCat->fetch(PDO::FETCH_ASSOC);
 echo  json_encode(["response"=>$select]);die;

}

if($action == "addNewCategory")
{

  $addNewProduct = $db->prepare("insert into `ProductCategory` (Category,datecreated,datelastupdated,createdfk,updatedfk,isactive) values(?,?,?,?,?,?)");
  $Category = $_POST['newCat'];
  $datecreated = date("Y-m-d H:i:s");
  $datelastupdated = date("Y-m-d H:i:s");
  $createdfk = $_SESSION["UserID"];
  $updatedfk = $_SESSION["UserID"];
  $isactive = 1;
  $addResult = $addNewProduct->execute([$Category,$datecreated,$datelastupdated,$createdfk,$updatedfk,$isactive]);
  $lastInsertId = $db->lastInsertId();
  if($addResult == 1)
  {
    echo  json_encode(["response"=>$lastInsertId]);die;
  }
  else
  {
   echo  json_encode(["error"=>'error']);die;
 }

}



if(isset($_REQUEST['delid']))
{
  $id=$_POST['delid'];
  $isactive=0;
  $DeleteClient = $db->prepare("delete from `Product` where id=:id");
  $DeleteClient->bindValue(":id",$id,PDO::PARAM_INT);
  $deletefile=$DeleteClient->execute();
  if($deletefile)
  {
    echo  json_encode(["resonse"=>'Product Successfully Remove From List']);die;
  }
  else
  {
    echo  json_encode(["error"=>'done']);die;
  }
}
if(isset($_REQUEST['eidtid']))
{
  $id=$_POST['eidtid'];
  $editproducts = $db->prepare("select *  from `Product` where id=:id");
  $editproducts->bindValue(":id",$id,PDO::PARAM_INT);
  $editproducts->execute();
  $editproducts=$editproducts->fetch(PDO::FETCH_ASSOC);
  $catorgrlist = explode(',', $editproducts['ProductCategory']);
  $brandlist = explode(',', $editproducts['ProductBrand']);

  if(!empty($editproducts))
  {
    echo  json_encode(["resonse"=>$editproducts,"catorgrlist"=>$catorgrlist,"brandlist"=>$brandlist]);die;
  }
  else
  {
    echo  json_encode(["error"=>'No Data found']);die;
  }
}
$id=$_SESSION['UserID'];
$statement=$db->prepare("SELECT * FROM `ProductCategory` WHERE `createdfk`=:id And isactive=1");
$statement->bindValue(":id",$id,PDO::PARAM_INT);
$statement->execute();
$ProductCategoryList = $statement->fetchAll(PDO::FETCH_ASSOC);


$id=$_SESSION['UserID'];

$statement=$db->prepare("SELECT DISTINCT(ProductBrand) FROM `Product` WHERE `createdfk`=:id And isactive=1");
$statement->bindValue(":id",$id,PDO::PARAM_INT);
$statement->execute();
$ProductBrandList = $statement->fetchAll(PDO::FETCH_ASSOC);



   // $statement=$db->prepare("SELECT * FROM `ProductBrand` WHERE `createdfk`=:id And isactive=1");
   // $statement->bindValue(":id",$id,PDO::PARAM_INT);
   // $statement->execute();
   // $ProductBrandList = $statement->fetchAll(PDO::FETCH_ASSOC);
   // $id= $_SESSION['UserID'];
   // $user = $db->prepare("SELECT * FROM `users` WHERE `adminid` =:id");
   // $user->bindParam(':id', $id, PDO::PARAM_INT);
   // $user->execute();
   // $alluser=$user->fetchAll();          

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C22'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C23'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];


$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C24'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];


$button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C25'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$B4=$all_button4['button_name'];

$button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C113'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$B5=$all_button5['button_name'];

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='6'"); 
$title1->execute();
$all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
$T1_p=$all_title1['TitleName'];



?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
<link href="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
<link href="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
<link href="<?= base_url?>/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<link rel="stylesheet" href="<?= base_url?>/dist/css/lightbox.min.css">

<style>
  .lb-details{display: none!important;}
  .eventStart1{width: 45%; float: left; padding: 0 10px;}
  .timeinput{width: 35%; float: left; padding: 0 15px;}
  .img-circle { object-fit: cover;}
  label.error{position: inherit!important; z-index: 5!important;}
  input.select2-search__field{width: 100%!important;}
  span.select2.select2-container.select2-container--default.select2-container--focus.select2-container--above{width: 100%!important;}
  span.select2.select2-container.select2-container--default{width: 100%!important;}
  li.select2-selection__choice {color: white !important;}
  .select2-container--default .select2-selection--multiple .select2-selection__choice{background-color: #42bfd3!important; border:1px solid #43c1d4!important; }

  .pac-container.pac-logo{z-index: 99999;}
  .lb-container{    position: absolute;    right: 0;    left: 0; }
  .lb-outerContainer{width: 50%!important;}
  img.lb-image{margin: 0 auto!important;}
  .lb-dataContainer{width: 75%!important; margin: unset!important;}
  .lightbox{top: 150px!important;}

  @media only screen and (max-width: 768px) 
  {
    .lightbox{top: 500px!important}
    .lb-outerContainer{width: 100%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
  }
  label.btn.btn-default.active.toggle-off{
    border: 1px solid;
    border-radius: 5px;
  }
  th{ font-weight: bold!important;color: #0b59a2!important;width: 11px;}

  
  .lblStyle{
    color: #3cabe1;

  }

  .lblStyle:hover{
    color: #f95c2e;
    cursor: pointer;
  }

</style>
<body class="skin-default fixed-layout mysunlessG">
 <!-- ============================================================== -->
 <!-- Preloader - style you can find in spinners.css -->
 <!-- ============================================================== -->
 <div class="preloader">
  <div class="loader">
    <div class="loader__figure"></div>
    <p class="loader__label"><?php echo $_SESSION['UserName']; ?></p>
  </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
  <!-- ============================================================== -->
  <!-- Topbar header - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <header class="topbar">
    <?php include 'TopNavigation.php'; ?>
  </header>
  <!-- ============================================================== -->
  <!-- End Topbar header -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Left Sidebar - style you can find in sidebar.scss  -->
  <!-- ============================================================== -->
  <?php include 'LeftSidebar.php'; ?>
  <!-- ============================================================== -->
  <!-- End Left Sidebar - style you can find in sidebar.scss  -->
  <!-- ============================================================== -->
  <!-- ============================================================== -->
  <!-- Page wrapper  -->
  <!-- ============================================================== -->
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Bread crumb and right sidebar toggle -->
      <!-- ============================================================== -->
      <div class="row page-titles">
        <div class="col-md-5 align-self-center">

          <h4 class="text-themecolor"><?php echo $T1_p; ?></h4> 

          <!-- <h4 class="text-themecolor">Add New Event</h4> -->
        </div>
        <div class="col-md-7 align-self-center text-right">
          <div class="d-flex justify-content-end align-items-center">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <!-- Nav tabs -->
              <!-- Tab panes -->
              <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="home" role="tabpanel">
                  <div class="col-lg-12">
                    <div class="col-lg-12 col-md-12">
                      <div class="alert alert-success" id="resonse" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                      </div>
                      <div class="alert alert-danger" id="error" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                      </div>

                      <div class="alert alert-danger" id="csrf_error" style="display: none;">
                        <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                        <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                      </div>
                    </div>
                    <!-- <a href="#" id="addUser" class="btn btn-info m-r-10 " id="newproduct" data-toggle="modal" data-target="#AddProductModal">Add New Product</a>  -->
                    
                    <!-- <a href="<?php echo base_url; ?>/AllProductBrand" id="newproductbrand"  class="btn btn-info m-r-10" style="margin: 5px 0;"><?php echo $B5; ?></a> -->




                    <div class="table-responsive m-t-40">

                      <div class="Loader"></div>
                      <style>
                        .switch {
                          float: right;
                          position: relative;
                          display: inline-block;
                          width: 40px;
                          height: 24px;
                        }

                        .switch input { 
                          opacity: 0;
                          width: 0;
                          height: 0;
                        }

                        .slider {
                          position: absolute;
                          cursor: pointer;
                          top: 0;
                          left: 0;
                          right: 0;
                          bottom: 0;
                          background-color: #F44336;
                          -webkit-transition: .4s;
                          transition: .4s;
                        }

                        .slider:before {
                          position: absolute;
                          content: "";
                          height: 18px;
                          width: 18px;
                          left: 4px;
                          bottom: 3px;
                          background-color: white;
                          -webkit-transition: .4s;
                          transition: .4s;
                        }

                        input:checked + .slider {
                          background-color: #32c353;
                        }

                        input:focus + .slider {
                          box-shadow: 0 0 1px #2196F3;
                        }

                        input:checked + .slider:before {
                          -webkit-transform: translateX(15px);
                          -ms-transform: translateX(15px);
                          transform: translateX(15px);
                        }

                        /* Rounded sliders */
                        .slider.round_switch {
                          border-radius: 34px;
                        }

                        .slider.round_switch:before {
                          border-radius: 50%;
                        }
                        /*switch*/

                        .product-img {
                          /*width: 170px;*/
                          height: 127px;
                          text-align: center;
                          position: relative;
                        }
                        .product-img img{
                          width: 100%;
                          height: 100%;
                          object-fit: contain;
                        }
                        .product-img .pro-img-overlay {
                          position: absolute;
                          width: 100%;
                          height: 100%;
                          top: 0px;
                          left: 0px;
                          display: none;
                          background: rgba(255, 255, 255, 0.8);
                        }
                        .product-img:hover .pro-img-overlay {
                          display: block;
                        }
                        .product-img .pro-img-overlay a {
                          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                          height: 40px;
                          width: 40px;
                          display: inline-block;
                          border-radius: 100%;
                          -webkit-border-radius: 100%;
                          -o-border-radius: 100%;
                          text-align: center;
                          padding: 11px 0;
                          color: #fff;
                          margin: 20% 5px;
                        }
                        .product-text {
                          border-top: 2px solid #3cabe2;
                          padding-top: 15px;
                          position: relative;
                        }
                        .product-text .pro-price {
                          position: absolute;
                          padding: 0px 5px;
                          background: white;
                          top: -13px;
                          right: 0px;
                          font-size: 16px;
                          color: #3cabe1;
                          font-weight: 600;
                        }
                        .more_info_btn{
                          width: 100%;
                          background: #a8afaf73;
                          text-align: center;
                        }
                        .more_info_btn i{
                          -webkit-transition: all .5s linear;
                          /*transition: all .5s linear;*/
                        }
                        .more_info_content{
                          padding: 1.25rem;
                          padding-top: 0px;
                        }
                        #product .text-success{
                          color: #28a745!important;

                        }
                        #product .badge-success{
                          background-color: #28a745!important;
                        }
                        .product_body{
                          -webkit-transition: all .5s linear;
                          transition: all .5s linear;
                          padding-bottom:0px;
                          height: 280px;
                          overflow-y: hidden;
                        }
                        .product_overflow{
                          height: unset;
                          overflow-y: unset; 
                        }
                        .arrow_down{
                          transform: rotate(180deg);
                        }
                        .text_bold{
                          font-weight: 600;
                          margin-right: 5px;
                        }
                        #product_search {
                          width: 200px;
                          background-size: 0 2px,100% 1px;
                          transition: background 0s ease-out;
                          float: none;
                          box-shadow: none;
                          border-radius: 0;
                          border: 0;
                          background-repeat: no-repeat;
                          background-position: center bottom,center calc(100% - 1px);
                          background-color: transparent;
                          background-image: linear-gradient(#3cabe1,#3cabe1),linear-gradient(#e9ecef,#e9ecef);
                        }
                        #product_search:focus {
                          outline: 0;
                          background-size: 100% 2px,100% 1px;
                          box-shadow: none;
                          transition-duration: .3s;
                        }
                        #product_sort .dropdown-item{
                          padding: 10px!important;
                        }
                        .product_sort_active{
                          background-color: #F44336!important;
                        }
                        #product_sort .dropdown-item i{
                          width: 25px;
                          height: 25px;
                          color: white;
                          background: #03a9f3;
                          text-align: center;
                          line-height: 23px;
                          border-radius: 50%;
                        }
                        #product_sort .dropdown-menu{
                          z-index: 1;
                          width: 208px;
                          padding: 0px!important;
                        }
                        #product .product_head{
                          justify-content: space-between;
                        }


                      </style>

                      <div id="product">
                        <div class="container">
                          <div class="row product_head mb-3">
                            <div>
                              <a href="#" class="btn btn-info m-r-10 " id="newproduct" data-toggle="modal" data-target="#scanProductModal" style="margin: 5px 0;"><?php echo $B1; ?></a>
                              <a href="<?php echo base_url; ?>/AllProductCategory" id="newproductcat"  class="btn btn-info m-r-10" style="margin: 5px 0;"><?php echo $B2; ?></a>
                            </div>
                            <div class="form-inline">
                              <div>
                                <input id="product_search" type="text" name="list_search" value="" placeholder="Search...">
                                <i class="fas fa-search search-icon" style="position: relative;right: 18px;"></i>
                              </div>
                              <div class="dropdown" id="product_sort">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                  Sort
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item">
                                    <span class="text_bold">Product Name</span>
                                    <span class="pull-right">
                                      <i class="fas fa-angle-left mr-2" data-sort-name="ProductTitle" data-sort="ASC"></i>
                                      <i class="fas fa-angle-right" data-sort-name="ProductTitle" data-sort="DESC"></i>
                                    </span>
                                  </a>
                                  <a class="dropdown-item">
                                    <span class="text_bold">Selling cost</span>
                                    <span class="pull-right">
                                      <i class="fas fa-angle-left mr-2" data-sort-name="SellingPrice" data-sort="ASC"></i>
                                      <i class="fas fa-angle-right" data-sort-name="SellingPrice" data-sort="DESC"></i>
                                    </span>
                                  </a>
                                  <a class="dropdown-item">
                                    <span class="text_bold">Stock</span>
                                    <span class="pull-right">
                                      <i class="fas fa-angle-left mr-2" data-sort-name="NoofPorduct" data-sort="ASC"></i>
                                      <i class="fas fa-angle-right" data-sort-name="NoofPorduct" data-sort="DESC"></i>
                                    </span>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row product_list"></div>

                        </div>
                      </div>



                    </div>
                  </div>
                </div>
              </div>

              <div id="scanProductModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                     <h4 class="modal-title">Scan or Enter Barcode</h4>
                     <div class="Loader"></div>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                   </div>
                   <div class="modal-body">
                    <div class="form-group">
                      <input type="text" class="form-control" name="scanbarcode" id="scanbarcode" placeholder="Scan or Enter Barcode">
                      <label id="errorbarcode" style="color:red"></label>
                    </div>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-primary" id="barcodebtn" >Submit Barcode</button>
                 </div>
               </div>
             </div>
           </div>                                


           <div id="AddProductModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->

              <div class="modal-content">
                <div class="modal-header">
                 <h4 class="modal-title">Add New Product</h4>
                 <div class="Loader"></div>
                 <!-- <button class="close" style="background-color:none;border-radius: 50%;color: black;padding: 0;margin-left: 50%;opacity: 1;"><img width="50" height="50" src="<?= base_url;?>/assets/images/barbtn.png" alt="Scan Barcode"></button> -->
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <div class="modal-body">
                 <form class="form-horizontal " autocomplete="off" action="" method="post" id="NewProduct">
                  <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                  <input type="hidden" name="id" id="id" value="">
                  <input type="hidden" name="clinetid" id="clientid" value="<?php echo @$ClientId;?>">
                  <?php 
                  if($usertype=='subscriber')
                  {
                    ?>
                    <input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
                    <?php
                  }
                  else
                  {
                    ?>
                    <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
                    <?php
                  }
                  ?>
                  <?php $_SESSION["ClientID"] = @$ClientId ;?>
                  <div class="form-group">
                    <label for="barcode">Barcode Id: *</label>
                    <input type="text" name="barcode" id="barcode" class="form-control" readonly>
                  </div>
                  <div class="form-group productCategoryNew">

                   <label for="ProductCategory">Product Category * <span class="help"></span></label>
                   <!-- <label class="pull-right cutommoibb"  data-toggle="modal" data-target="#myModal_addcat" id="addUser" >Create New Category</label> -->

                   <label class="pull-right cutommoibb lblStyle" id="addUser" >Create New Category</label>


                   <div class="form-group">

                     <input type="text" name="Category" placeholder="Enter a New Category Name" id="Category" value="" class="form-control newCategoryAdd valid" maxlength="30" aria-invalid="false">
                   </div>

                   <div class="form-group">
                    <label class="error errorCat" style="display: flex">Category Alredy Exist!</label> 
                    <label class="error errorCatEmpty" style="display: flex">Please enter a category!</label> 



                    <button type="button" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> Submit Category</button> 

                    <button type="button" class="btn waves-effect waves-light btn-danger m-r-10" id="cancelCat"> <i class="fa fa-times"></i> Cancel</button>

                  </div>      

                  <select class="select2 m-b-10 select2-multiple form-control" placeholder="Select Category" data-placeholder="Select Category" id="ProductCategory" name="ProductCategory[]" multiple data-style="form-control btn-secondary">
                   <?php
                   foreach($ProductCategoryList as $value)
                   {
                     if($value['Category'] == @$ProductCategory ){
                      echo '<option selected value="'.$value['id'].'">'.$value['Category'].'</option>';
                    }
                    else{
                     echo '<option value="'.$value['id'].'">'.$value['Category'].'</option>';
                   }
                 }
                 ?>
               </select>
             </div>

             <div class="form-group">
               <label for="ProductBrand">Brand<span class="help"></span></label>
               <!-- <label class="pull-right"><a href="<?php echo base_url; ?>/AllProductBrand">Create New Brand</a></label> -->


               <!-- <label class="pull-right lblStyle">Create New Brand</label> -->

               <select class="select2 m-b-10 form-control"  data-placeholder="Select Brand" id="ProductBrand" name="ProductBrand[]" data-style="form-control btn-secondary">
                <option value=""></option>

                <?php

                foreach($ProductBrandList as $value)
                {
                 if($value['ProductBrand'] == @$ProductBrand ){
                  echo '<option selected value="'.$value['ProductBrand'].'">'.$value['ProductBrand'].'</option>';

                }
                else{
                 echo '<option value="'.$value['ProductBrand'].'">'.$value['ProductBrand'].'</option>';
               }
             }
             ?>
           </select>
<!-- 
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Brand" id="ProductBrand" name="ProductBrand[]" multiple data-style="form-control btn-secondary">
                                       <?php
                                          foreach($ProductBrandList as $value)
                                          {
                                             if($value['Brand'] == @$ProductBrand ){
                                                echo '<option selected value="'.$value['id'].'">'.$value['Brand'].'</option>';
                                             }
                                             else{
                                               echo '<option value="'.$value['id'].'">'.$value['Brand'].'</option>';
                                             }
                                          }
                                       ?>
                                     </select>  -->

                                   </div>

                                   <div class="form-group">
                                     <label for="ProductTitle"><span class="help"> Product Name *</span></label>
                                     <input type="text" name="ProductTitle" class="form-control" placeholder="Product Title" value="" id="ProductTitle" maxlength="30">
                                   </div>
                                   <div class="form-group">
                                     <label for="ProductDescription">Product  Description *</label>
                                     <textarea class="form-control" maxlength="150" id="ProductDescription" id="ProductDescription" name="ProductDescription" placeholder="Write Product  Description here.."  rows="5"><?php echo @$ProductDescription;?></textarea>
                                   </div> 
                                   <div class="form-group">
                                     <label for="CompanyCost">Item Cost * <span class="help"></span></label>
                                     <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                      </div>
                                      <!-- <p id="Price"> 0 </p> -->
                                      <input type="text" id="CompanyCost" aria-label="Amount (to the nearest dollar)" name="CompanyCost" class="form-control" id="CompanyCost" placeholder="i.e. 100 " value="" >
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                  </div> -->
                                </div>        
                              </div>
                              <div class="form-group">
                               <label for="SellingPrice">Selling Price * <span class="help"></span></label>
                               <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">$</span>
                                </div>
                                <!-- <p id="Price"> 0 </p> -->
                                <input type="text" aria-label="Amount (to the nearest dollar)"  name="SellingPrice" data-SellingPrice="0" class="form-control SellingPrice" id="SellingPrice" placeholder="i.e. 100 " value="" >
                                <input type="hidden" name="SellingPricewithouttax" value="" id="productpricewithouttax">
                                <input type="hidden" name="onlytax" id="onlytax" class="onlytax"  value="">

                                <!-- <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                  </div> -->
                                </div>        
                              </div>

                              <div class="form-group" style="text-align: right;">           
                               <label for="SellingPrice">Taxable sale : <span class="help"></span></label>
                               <?php
                               if(!$mysalestax)
                               {
                                ?>
                                <button  class="btn btn-default off toggleBtn7" id="toggleBtn7"  data-style="ios"> Off</button>              
                                <?php
                              }

                              else
                              { 
                               ?>
                               <input type="checkbox" data-id="" name="sales_tax" value="0" class="toggle_status6" id="toggle_status6"  data-toggle="toggle" data-style="ios">                
                               <div><a href="#" data-toggle="tooltip" data-placement="left" title="This item will be subject to the sales tax rate you entered in your Company Information page">taxable sale?</a></div>  
                               <?php
                             }
                             ?>


                           </div>


                                   <!--  <div class="form-group">
                                       <label for="CommissionAmount">Commission Amount * <span class="help"></span></label>
                                       <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                    <p id="Price"> 0 </p>
<input type="text" id="CommissionAmount" aria-label="Amount (to the nearest dollar)" name="CommissionAmount" class="form-control" placeholder="i.e. 10" value="" >
                                 <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div> 
                        </div>        
                      </div> -->

                                    <!-- <div class="form-group">
                                       <label for="ProductDescription">Discount in Percentage  ( % )</label>
                <input type="text" name="discountinparst" class="form-control" placeholder="Discount in % " value="" id="Commissioninperstantag" readonly>
              </div>  -->

              <!-- <span class="Commissioninperstantag"></span> -->

              <div class="form-group ">
               <label for="ProductImage">Product  Image (jpg/jpeg)<span class="help"></span></label>
               <div class="card">
                <div class="card-body">
                  <input type="file"  name="ProductImage" class="dropify" id="ProductImage" />
                </div>
              </div>
            </div>
            <div class="form-group">
             <label for="NoofPorduct">No Of Product in Stock * <span class="help"></span></label>
             <input type="text" id="NoofPorduct" name="NoofPorduct" class="form-control" placeholder="i.e. 10" value="<?php echo @$NoofPorduct;?>" >
           </div>
           <div class="form-group">
             <?php
             if($action =='edit'){
               ?>
               <button type="submit" class="btn waves-effect waves-light btn-info buttonproform" name="addProduct" id="addProduct"><i class="fa fa-check"></i> Update Product Detail</button>
               <?php
             }else{
               ?>
               <button type="submit" class="btn waves-effect waves-light btn-info buttonproform" name="addProduct" id="addProduct"><i class="fa fa-check"></i> <?php echo $B3; ?></button>
               <?php 
             }
             ?>
             <a href="<?php echo base_url; ?>/AllProduct.php" type="button" class="btn waves-effect waves-light btn-danger buttonproform"><i class="fa fa-times"></i> <?php echo $B4; ?></a>
           </div>
         </form>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
</div>
</div>
</div>
<!-- ==============================================================  -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- footer -->
<!-- ============================================================== -->
<?php include 'footer.php'; ?>
<!-- ============================================================== -->
<!-- End footer -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<?php include 'scripts.php'; ?>
<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
<script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
<script src="<?= base_url?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>



<script>
  $(document).ready(function() {

    var query={"sort_name":"","sort_type":"","search":""};
    dataTable();
///new custom for product display
$(document).on("click","#product_sort .dropdown-item i",function(){


  sort_name = $(this).attr("data-sort-name");
  sort_type = $(this).attr("data-sort");
  query.sort_name = sort_name;
  query.sort_type = sort_type; 
  dataTable(query);
  $("#product_sort .dropdown-item i").removeClass("product_sort_active");
  $(this).addClass("product_sort_active");
});
$("#product_search").keyup(function(e){
  e.preventDefault();
  query.search = $(this).val();
  dataTable(query);
});


function dataTable(query={}){
  JSON.stringify(query);
  $.ajax({
    url:"<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2",
    type:"get",
    data:{query:query},
    success:function(data){
      data = JSON.parse(data);
      $("#product .product_list").html("");
      for(i=0;i<data.length;i++){


        if(data[i].ProductImage){
          product_image = '<?php echo $base_url ?>/assets/ProductImage/'+data[i].ProductImage;
        }else{
          product_image = '<?php echo $base_url ?>/assets/images/noimage.png';
        }

        if(data[i].ProductBrand){
          product_brand='<span class="text-muted"><span class="text_bold">Brand:</span>'+data[i].ProductBrand+'</span><br> ';
        }else{
          product_brand='';
        }

        if(parseInt(data[i].isactive)==1){
          product_status = "checked";
        }else{
          product_status = "";
        }

        if(parseInt(data[i].NoofPorduct)>0){
          stock ='<span class="text-success">In-stock: <span class="badge badge-pill badge-success">'+data[i].NoofPorduct+'</span></span><br>'; 
        }else{
          stock ='<span class="text-danger">Out-of-stock: <span class="badge badge-pill badge-danger">'+data[i].NoofPorduct+'</span></span><br>';
        }

        $("#product .product_list").append('<div class="col-12 col-sm-8 col-md-6 col-lg-3"> <div class="card"> <div class="card-body product_body"> <div class="product-img"> <img src="'+product_image+'"> <div class="pro-img-overlay"><a id="editButton" data-id="'+data[i].id+'" href="javascript:void(0)" class="bg-info"><i class="ti-marker-alt"></i></a> <a id="deleteButton" data-id="'+data[i].id+'" href="javascript:void(0)" class="bg-danger"><i class="ti-trash"></i></a></div> </div> <div class="product-text"> <span class="pro-price">$'+data[i].SellingPrice+'</span> <label class="switch" id="'+data[i].id+'" data-toggle="tooltip" title="Product status"> <input type="checkbox" '+product_status+'> <span class="slider round_switch"></span> </label> <h5 class="card-title m-b-0 mr-5">'+data[i].ProductTitle+'</h5>  '+stock+product_brand+'<span class="text-muted"><span class="text_bold">Category:</span>'+data[i].category+'</span><br> <span class="text-muted text_bold">Product description:</span><br> <span>dsgfdxcvbxcvzxcv</span><br> <span class="text-muted"><span class="text_bold">Product cost:</span>'+parseInt(data[i].CompanyCost)+'</span><br> </div> </div> <div class="more_info_btn"><i class="fas fa-angle-down"></i></div> </div> </div>');

      }
    }
  });
}


$(document).on("click",".more_info_btn",function(){
  $(this).prevAll(".product_body").toggleClass("product_overflow");
  $(this).find("i").toggleClass("arrow_down");
});

$(document).on("click",".switch",function(e){
  e.stopPropagation();
  id = $(this).attr("id");
  if($(this).find("input").is(":checked")){
    status=1;
    $(this).find("input").prop("checked",false);
  }else{
    status=0;
    $(this).find("input").prop("checked",true);
  }

  $.ajax({
   url: "<?php echo EXEC; ?>Exec_Edit_Product.php?set_status",
   type: 'post',
   data: {
     id : id,
     status:status
   },
   success: function(data){
    swal("Status update successfully...","", "success");
    
  },
  error: function(errorThrown) {
   swal("Sorry! Failed to update status","", "error");
   
 }
});
});


///new custom for product display

$("#Category").hide();
$("#add-client").hide();
$("#ProductCategory").show();
$('.productCategoryNew .select2').show();
$("#cancelCat").hide();
$(".errorCat").hide();
$(".errorCatEmpty").hide();



var matchCategoryCount = 0;
          // this is show for overlay model

          $(document).on('show.bs.modal', '.modal', function (event) {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
              $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
          });

     // end overlay model

     // Select All existing Category start

     $('.newCategoryAdd').focusin(function(){

      matchCategoryCount = 0;

    });


      // Add new category Start
      $('#add-client').on('click',function(){

        var  newCategoryName = $.trim($(".newCategoryAdd").val()).toLowerCase();


        if(newCategoryName != "")
        {   
          $(".errorCatEmpty").hide();

          jQuery.ajax({
            dataType:"json",
            type:"post",
            url:'?action=selCategory',
            beforeSend: function() {
              $(".Loader").show();

            },
            success: function(data)
            {
              $(".Loader").hide();


              var j,k;
              for(j = 0; j< data.response.length; j++){    

                                  //console.log(data.response[j].Category.toLowerCase());                          
                                  if(newCategoryName == data.response[j].Category.toLowerCase())
                                  {
                                    matchCategoryCount = 1;
                                  }
                                }
                              //console.log(matchCategoryCount);

                              if(matchCategoryCount == 1)
                              {
                                    //swal("Error!","Category Alredy exist!", "error");
                                    $(".errorCat").show();


                                  }
                                  else
                                  {
                                    $(".errorCat").hide();

                                    $.ajax({
                                      dataType:"json",
                                      type:"post",
                                      url:'?action=addNewCategory',
                                      data:{'newCat' : newCategoryName},
                                      success: function(data)
                                      {

                                        if(data.response)
                                        { 

                                          $("#add-client").hide();
                                          $("#Category").hide();
                                          $("#cancelCat").hide();
                                          $(".errorCat").hide();

                                          $('#ProductCategory').append('<option value="'+data.response+'">'+newCategoryName+'</option>');
                                          swal("Suceess!", "Product Category Added Successfully!", "success", {
                                            button: "ok",
                                          });
                                          $('.productCategoryNew .select2').show();


                                        }


                                      }
                                    });
                                  }
                                }
                              });

        }
        else
        {
          $(".errorCatEmpty").show();
        }
      });

      // Add new Category end


  //Add new category Script start
  $(document).on('click','#addUser',function(e){
    $("#Category").val('');
    $("#id").val('new');
    $("#Category").show();
    $("#add-client").show();
    $("#cancelCat").show();
    $(".errorCat").hide();

    $("#ProductCategory").hide();
    $(".productCategoryNew .select2").hide();


  });   


  $("#cancelCat").on('click',function(){

    $("#Category").hide();
    $("#add-client").hide();
    $("#cancelCat").hide();
    $(".errorCat").hide();
    $(".errorCatEmpty").hide();

    $("#ProductCategory").show();
    $(".productCategoryNew .select2").show();
  })


  $("#NewCategory").validate({
    rules: {                
      Category: {required: true,}
    },
    messages: {             
      Category: {required: "Please enter category"},
    },
    submitHandler: function() {

      if(matchCategoryCount == 0)
      {



        $(".Loader").show();
        var data = $("#NewCategory").serialize();
        data= data + "&Action=Category";
        jQuery.ajax({
         dataType:"json",
         type:"post",
         data:data,
         url:'<?php echo EXEC; ?>Exec_Edit_ProductCategory.php',
         success: function(data)
         {
          if(data.resonse)
          {
            $("#resonse").show();
            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
            $( '#NewCategory' ).each(function(){
             this.reset();
           });
            $(".Loader").hide();
            $("#myModal_addcat").modal('hide');

                            // setTimeout(function () { window.location.href = "AllProductCategory.php"; }, 3000) ;
                            //dataTable()
                          }
                          else if(data.error)
                          {
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            $("#myModal_addcat").modal('hide');
                            $(".Loader").hide();
                        // alert('<li>'+data.error+'</li>');
                      }
                      else if(data.csrf_error)
                      {

                        $("#csrf_error").show();
                        $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                        $(".Loader").hide();
                             //setTimeout(function () { window.location.reload() }, 2000)
                           }
                         }
                       });
      }
    }           
  }); 
  // Add new category Script End



// Scan Barcode script starts

$('#barcodebtn').on('click',function(){

  var barcode = $('#scanbarcode').val();
  $('.Loader').show();
  if(barcode == ''){
    $('.Loader').hide();
    $('#errorbarcode').text('Please Enter the Barcode');
  }else{
    $('#errorbarcode').text('');
    $.ajax({
      dataType:"json",
      type:"post",
      data:{'barcode':barcode},
      url:'?action=deletefile',
      success: function(data){
        if(data){
          $('.Loader').hide();
          if(data.resonse.found == 'found'){
            swal({
              title: "Product Already Exist",
              text: "Do You want to Open it for Edit!",
              icon: "info",
              buttons: true,
            }).then((willUpdate)=>{   
              if (willUpdate){
                var eidtid=data.resonse.id;
                $(".Loader").show();
                $("#pImage").remove();
                $.ajax({
                  dataType:"json",
                  type:"post",
                  data:{'eidtid':eidtid},
                  url:'?action=editfile',
                  success: function(data)
                  {
                   if(data.resonse){
                     $('#scanProductModal').modal('toggle');
                     $('#AddProductModal').modal('show');
                     $('#barcode').val(data.resonse.barcode);
                     $('#ProductTitle').val(data.resonse.ProductTitle);
                     $('#CompanyCost').val(data.resonse.CompanyCost);
                     $('#NoofPorduct').val(data.resonse.NoofPorduct);
                               // $('#ProductCategory').val(data.resonse.ProductCategory);
                               $('#ProductDescription').val(data.resonse.ProductDescription);
                               $('#SellingPrice').val(data.resonse.SellingPrice);
                               $('#Commissioninperstantag').val(data.resonse.discountinparst);
                               $('#id').val(data.resonse.id);
                               if(data.resonse.ProductImage !== '')
                               {

                                 $("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/ProductImage/"+data.resonse.ProductImage+"");
                                 $('<img src="<?php echo base_url; ?>/assets/ProductImage/'+data.resonse.ProductImage+'" id="pImage">').appendTo(".dropify-render");
                                     // $( ".dropify-render img" ).first().css( "display", "none" );
                                     $('.dropify-filename-inner').text(data.resonse.ProductImage)
                                  //    $('#ProductImage').val();
                                }
                                else if(data.resonse.ProductImage =='')
                                {
                                 $("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
                                 $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
                                 $('.dropify-filename-inner').text('noimage.png')

                               }

                               $('#ProductCategory').val(data.catorgrlist).trigger('change');
                               $('#ProductBrand').val(data.brandlist).trigger('change');  
                               $(".Loader").hide();
                             }
                             else if(data.error){
                              $("#error").show();
                              $('#errormsg').html('<span>'+data.error+'</span>');
                              $(".Loader").hide();
                                    // alert('<li>'+data.error+'</li>');
                                  }
                                }  
                              });   
              }
              else{
                $('#scanProductModal').modal('toggle');
                return false ;
              }
            });
          }else{
            $('#barcode').val(barcode);
            $('#AddProductModal').modal('show');
          }
        }
      }
    });
  }

});

// Scan Barcode Script Ends

$('#CompanyCost').keypress(function(event) {
  var $this = $(this);
  if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
   ((event.which < 48 || event.which > 57) &&
     (event.which != 0 && event.which != 8))) {
   event.preventDefault();
}

var text = $(this).val();
if ((event.which == 46) && (text.indexOf('.') == -1)) {
  setTimeout(function() {
    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
      $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
    }
  }, 1);
}

if ((text.indexOf('.') != -1) &&
  (text.substring(text.indexOf('.')).length > 2) &&
  (event.which != 0 && event.which != 8) &&
  ($(this)[0].selectionStart >= text.length - 2)) {
  event.preventDefault();
}      
});

$('#CompanyCost').bind("paste", function(e) {
  var text = e.originalEvent.clipboardData.getData('Text');
  if ($.isNumeric(text)) {
    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
      e.preventDefault();
      $(this).val(text.substring(0, text.indexOf('.') + 3));
    }
  }
  else {
    e.preventDefault();
  }
});



$('#SellingPrice').keypress(function(event) {
  var $this = $(this);
  if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
   ((event.which < 48 || event.which > 57) &&
     (event.which != 0 && event.which != 8))) {
   event.preventDefault();
}

var text = $(this).val();
if ((event.which == 46) && (text.indexOf('.') == -1)) {
  setTimeout(function() {
    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
      $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
    }
  }, 1);
}

if ((text.indexOf('.') != -1) &&
  (text.substring(text.indexOf('.')).length > 2) &&
  (event.which != 0 && event.which != 8) &&
  ($(this)[0].selectionStart >= text.length - 2)) {
  event.preventDefault();
}      
});

$('#SellingPrice').bind("paste", function(e) {
  var text = e.originalEvent.clipboardData.getData('Text');
  if ($.isNumeric(text)) {
    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
      e.preventDefault();
      $(this).val(text.substring(0, text.indexOf('.') + 3));
    }
  }
  else {
    e.preventDefault();
  }
});




//   $('#CommissionAmount').keypress(function(event) {
//     var $this = $(this);
//     if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
//        ((event.which < 48 || event.which > 57) &&
//        (event.which != 0 && event.which != 8))) {
//            event.preventDefault();
//     }

//     var text = $(this).val();
//     if ((event.which == 46) && (text.indexOf('.') == -1)) {
//         setTimeout(function() {
//             if ($this.val().substring($this.val().indexOf('.')).length > 3) {
//                 $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
//             }
//         }, 1);
//     }

//     if ((text.indexOf('.') != -1) &&
//         (text.substring(text.indexOf('.')).length > 2) &&
//         (event.which != 0 && event.which != 8) &&
//         ($(this)[0].selectionStart >= text.length - 2)) {
//             event.preventDefault();
//     }      
// });

// $('#CommissionAmount').bind("paste", function(e) {
// var text = e.originalEvent.clipboardData.getData('Text');
// if ($.isNumeric(text)) {
//     if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
//         e.preventDefault();
//         $(this).val(text.substring(0, text.indexOf('.') + 3));
//    }
// }
// else {
//         e.preventDefault();
//      }
// });


$(document).on('keyup','#NoofPorduct',function(){
 if (/\D/g.test(this.value))
 {
   this.value = this.value.replace(/\D/g, '');
 }
});

$("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
$('.dropify').dropify();
   // Translated
   $('.dropify-fr').dropify({
    messages: {
      default: 'Glissez-déposez un fichier ici ou cliquez',
      replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
      remove: 'Supprimer',
      error: 'Désolé, le fichier trop volumineux'
    }
  });
   // Used events
   var drEvent = $('#input-file-events').dropify();
   drEvent.on('dropify.beforeClear', function(event, element) {
    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });
   drEvent.on('dropify.afterClear', function(event, element) {
    alert('File deleted');
  });
   drEvent.on('dropify.errors', function(event, element) {
    console.log('Has Errors');
  });
   var drDestroy = $('#input-file-to-destroy').dropify();
   drDestroy = drDestroy.data('dropify')
   $('#toggleDropify').on('click', function(e) {
    e.preventDefault();
    if (drDestroy.isDropified()) {
      drDestroy.destroy();
    } else {
      drDestroy.init();
    }
  })

 //   dataTable()
 //   function dataTable()
 //   {
 //    $('#myTable2').DataTable({
 //      "responsive": true,
 //      "processing": true,
 //      "destroy": true,
 //      "ajax" : {
 //        "url" : "<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2",
 //        "dataSrc":'',
 //      },
 //      "autoWidth": false,

 //      "columns" : [{
 //        "data": {ProductImage:"ProductImage", ProductTitle:"ProductTitle"},
 //        "render": function(data, type, row) {
 //          if(data.ProductImage!=''){
 //            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/ProductImage/'+data.ProductImage+'"" data-lightbox="example-'+data.ProductImage+'"><img src="<?php echo $base_url ?>/assets/ProductImage/'+data.ProductImage+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.ProductTitle +'</span></div> </div>';    
 //          }
 //          else
 //          {
 //            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" data-lightbox="example-noimage.png"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.ProductTitle +'</span></div> </div>';       
 //          }
 //        }
 //      },
 //      {

 //        "data": "ProductBrand",
 //      },
 //      {
 //        "data": "ProductDescription",                
 //      },
 //      {
 //        "data": {CompanyCost:"CompanyCost"},
 //        "render": function(data, type, row) {
 //          return '<span>$'+ data.CompanyCost +'</span>';    
 //        }
 //      },
 //      {
 //        "data": {SellingPrice:"SellingPrice"},
 //        "render": function(data, type, row) {
 //          return '<span>$'+ data.SellingPrice +'</span>';    
 //        }
 //      },

 //      {
 //        "data": "NoofPorduct",                
 //      },
 //      {
 //        "data": {id: "id",isactive: "isactive"},
 //        "render": function(data, type, row) {
 //          if (data.isactive == 1){
 //           return '<input class="toggle_status" id="'+ data.id +'" type="checkbox"  checked data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="1">' ;
 //         }
 //         else{
 //           return '<input class="toggle_status" id="'+ data.id +'" type="checkbox"  data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="0">' ;
 //         }
 //       }
 //     },
 //     {
 //      "data": "id",
 //      "render": function(data, type, row) {
 //        return '<button class="btn btn-info btn-sm" title="Edit Event" id="editButton" data-id="' + data + '"> <span class="fa fa-edit"><span> </button> <button id="deleteButton" title="Delete" class="btn btn-danger btn-sm" data-id="' + data + '"><span class="fa fa-trash"></span></button>' ;
 //      }
 //    }],
 //    "fnDrawCallback": function() {
 //      jQuery('.toggle_status').bootstrapToggle();
 //      jQuery('.toggle_status').parent().addClass('toggleBtn');
 //      jQuery('.toggle_status6').bootstrapToggle();
 //      jQuery('.toggle_status6').parent().addClass('toggleBtn6');
 //                            // jQuery('.toggle_status7').bootstrapToggle();
 //                            // jQuery('.toggle_status7').parent().addClass('toggleBtn7');
 //                          }
 //                        });
 //  }

 //  $(document).on('click','.toggleBtn',function(){
 //    $.ajax({
 //     url: "<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2",
 //     type: 'post',
 //     data: {
 //       id : $(this).children(".toggle_status").attr("id"),
 //       status: $(this).children(".toggle_status").attr("value")
 //     },
 //     success: function(data){
 //      swal("Status update successfully...","", "success");
 //      dataTable()
 //    },
 //    error: function(errorThrown) {
 //     swal("Sorry! Failed to update status","", "error");
 //     dataTable()
 //   }
 // });
 //  });
 $(document).on('click','#deleteButton',function(e){

   e.preventDefault();
   swal({
    title: "Are you sure?",
    text: "Once deleted, you will lost all data of this product!",
    icon: "warning",
    buttons: true,
  }).then((willDelete)=>{   
    if (willDelete){
      var delid=$(this).attr('data-id');
      $.ajax({
        dataType:"json",
        type:"post",
        data:{'delid':delid},
        url:'?action=deletefile',
        success: function(data)
        {
          if(data.resonse){
            $("#resonse").show();
            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
            dataTable()
            $("#myModal").modal('hide');
          }
          else if(data.error){
            $("#error").show();
            $('#errormsg').html('<span>'+data.error+'</span>');

                                            // alert('<li>'+data.error+'</li>');
                                          }
                                        }
                                      });
    }
    else{

     return false ;
   }
 });
});

 $(document).on('click','#newproduct',function(){
  $('#NewProduct')[0].reset();
  $('#id').val('new');
  $("#scanbarcode").val("");
  $("#select2-ProductBrand-container").text("");
  $('.select2-selection__choice').remove();
  $( ".dropify-render img" ).first().remove();
  $("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
  $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
  $('.dropify-filename-inner').text('noimage.png')

});
 $(document).on('click','#deleteButton',function(e){
   e.preventDefault();
   swal({
    title: "Are you sure?",
    text: "Once deleted, you will lost all data of this product!",
    icon: "warning",
    buttons: true,
  }).then((willDelete)=>{   
    if (willDelete){
      var delid=$(this).attr('data-id');
      $.ajax({
        dataType:"json",
        type:"post",
        data:{'delid':delid},
        url:'?action=deletefile',
        success: function(data)
        {
          if(data.resonse){
            $("#resonse").show();
            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
            $(".Loader").hide();
            dataTable()
          }
          else if(data.error){
            $("#error").show();
            $('#errormsg').html('<span>'+data.error+'</span>');
            $(".Loader").hide();
                                            // alert('<li>'+data.error+'</li>');
                                          }

                                          else if(data.csrf_error)
                                          {

                                            $("#csrf_error").show();
                                            $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                                            $(".Loader").hide();
                                            setTimeout(function () { window.location.reload() }, 2000)
                                          }
                                        }
                                      });
    }
    else{
     return false ;
   }
 });
});
 $(document).on('click','#editButton',function(e){  
  $('.dropify-render').text('')
  $('.dropify-filename-inner').text('')
  var eidtid=$(this).attr('data-id');
  $(".Loader").show();
  $("#pImage").remove();
  $.ajax({
    dataType:"json",
    type:"post",
    data:{'eidtid':eidtid},
    url:'?action=editfile',
    success: function(data)
    {
     if(data.resonse){
       $('#AddProductModal').modal('show');
       $('#barcode').val(data.resonse.barcode);
       $('#ProductTitle').val(data.resonse.ProductTitle);
       $('#CompanyCost').val(data.resonse.CompanyCost);
       $('#NoofPorduct').val(data.resonse.NoofPorduct);
                     // $('#ProductCategory').val(data.resonse.ProductCategory);
                     $('#ProductDescription').val(data.resonse.ProductDescription);
                     $('#SellingPrice').val(data.resonse.SellingPrice);
                     $('#Commissioninperstantag').val(data.resonse.discountinparst);
                     $('#id').val(data.resonse.id);
                     $('#toggle_status6').val(data.resonse.sales_tax);
                     $('#onlytax').val(data.resonse.onlytax);
                     $('.SellingPrice').attr('data-SellingPrice',data.resonse.SellingPricewithouttax);
                     if(data.resonse.sales_tax==1)
                     {
                       $('.toggleBtn6').addClass('btn-primary').removeClass('btn-default').removeClass('off'); 
                       $('#toggle_status6').attr('checked','checked');
                     }

                     
                     
                     
                     if(data.resonse.ProductImage !== '')
                     {

                       $("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/ProductImage/"+data.resonse.ProductImage+"");
                       $('<img src="<?php echo base_url; ?>/assets/ProductImage/'+data.resonse.ProductImage+'" id="pImage">').appendTo(".dropify-render");
                           // $( ".dropify-render img" ).first().css( "display", "none" );
                           $('.dropify-filename-inner').text(data.resonse.ProductImage)
                        //    $('#ProductImage').val();
                      }
                      else if(data.resonse.ProductImage =='')
                      {
                       $("#ProductImage").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
                       $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
                       $('.dropify-filename-inner').text('noimage.png')

                     }

                     $('#ProductCategory').val(data.catorgrlist).trigger('change');
                     $('#ProductBrand').val(data.brandlist).trigger('change');
                     $(".Loader").hide();
                   }
                   else if(data.error){
                    $("#error").show();
                    $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                          // alert('<li>'+data.error+'</li>');
                        }
                      }  
                    });
});
 $("#NewProduct").validate({

  rules: {    
    barcode: "required",   
    ProductTitle: "required",
    ProductDescription: "required",
    CompanyCost: {required: true,number: true},
    SellingPrice: {required: true,number: true},
    "ProductCategory[]": "required",

    NoofPorduct:{required: true,number: true},
  },
  messages: {    
    barcode: "Please Enter or scan barcode",   
    ProductTitle:  "Please enter product title",
    ProductDescription:  "Please enter product short description",
    CompanyCost: {required: "Please enter cost for employee to bring product in",
    number: "Please enter only number",
  },
  SellingPrice: {required: "Please enter selling price to consumer",
  number: "Please enter only number",
},
"ProductCategory[]":  "Please select at least one product category",

NoofPorduct: {required: "Please enter no of product in stock",
number: "Please enter only number",
},
},
errorPlacement: function(label, element) {
 if (element.attr("name") == "ProductCategory[]" || element.attr("name") == "ProductBrand[]" || element.attr("name") == "CompanyCost" || element.attr("name") == "SellingPrice") {
  element.parent().append( label );
} else {
  label.insertAfter( element );
}
},
submitHandler: function() {
 $(".Loader").show();
 $(".modal2").show();
 var form = $('#NewProduct')[0];
 var data = new FormData(form);

 jQuery.ajax({
   dataType:"json",
   type:"post",
   data:data,
               contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
               processData: false,
               url:'<?php echo EXEC; ?>Exec_Edit_Product.php',
               success: function(data)
               {
                 if(data.resonse)
                 {
                  swal(data.resonse,"", "success");
                  // $("#resonse").show();
                  // $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                  $( '#NewProduct' ).each(function(){
                   this.reset();
                 });
                  $(".modal2").hide();
                  $(".Loader").hide();
                  dataTable();
                  $('#AddProductModal').modal('hide');
                  $('#scanProductModal').modal('hide');
                  //setTimeout(function () { window.location.reload() }, 2000)

                }
                else if(data.error)
                {
                  swal(data.error,"", "error");

                  // $("#error").show();
                  // $('#errormsg').html('<span>'+data.error+'</span>');
                  $(".modal2").hide();
                  $(".Loader").hide();
                }
                else if(data.csrf_error)
                {

                  swal(data.csrf_error,"", "error");

                  // $("#csrf_error").show();
                  // $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                  $(".Loader").hide();
                  $('#AddProductModal').modal('hide');
                  $('#scanProductModal').modal('hide');
                  setTimeout(function () { window.location.reload() }, 2000)
                }
              }
            });
}       
});
 $(document).on('click','.toggleBtn6',function(){
  var taxstatus = $('#toggle_status6').val()

  if(taxstatus==0)
  {

    var SellingPricewithouttax =  $("#SellingPrice").val()
    $('.SellingPrice').attr('data-SellingPrice',SellingPricewithouttax);
    $('#productpricewithouttax').val(SellingPricewithouttax)
    var res = '<?php echo $mysalestax; ?>';
    var onlytax = parseFloat(SellingPricewithouttax/100) * parseFloat(res); 
    $("#onlytax").val(onlytax)   
    var finalsellingprice = parseFloat(SellingPricewithouttax) + parseFloat(onlytax);
          // $("#SellingPrice").val(finalsellingprice)   
          $('#toggle_status6').val(1)
          $('.toggleBtn6').addClass('btn-primary').removeClass('btn-default').removeClass('off'); 
        }
        else
        {

          var finalsellingprice = $('.SellingPrice').attr('data-SellingPrice');
          $("#SellingPrice").val(finalsellingprice)   
          $('#productpricewithouttax').val(finalsellingprice)
          $('#toggle_status6').val(0)
          $("#onlytax").val(0)   
          $('.toggleBtn6').removeClass('btn-primary').addClass('btn-default').addClass('off'); 
          

        }


      });

 $(document).on('click','.toggleBtn7',function(event){
  event.preventDefault()
  swal("Please enter your sale tax rate in Company Information page")
});

   // switchery
   var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
   $('.js-switch').each(function() {
     new Switchery($(this)[0], $(this).data());
   });
      // For select 2
      $(".select2").select2({
        tags: true,
        allowClear: true
      });
      $('.selectpicker').selectpicker();//
      //Bootstrap-TouchSpin
      $(".vertical-spin").TouchSpin({
       verticalbuttons: true,
       verticalupclass: 'ti-plus',
       verticaldownclass: 'ti-minus'
     });
      var vspinTrue = $(".vertical-spin").TouchSpin({
       verticalbuttons: true
     });
      if (vspinTrue) {
       $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
     }
     $("input[name='tch1']").TouchSpin({
      min: 0,
      max: 100,
      step: 0.1,
      decimals: 2,
      boostat: 5,
      maxboostedstep: 10,
      postfix: '%'
    });
     $("input[name='tch2']").TouchSpin({
      min: -1000000000,
      max: 1000000000,
      stepinterval: 50,
      maxboostedstep: 10000000,
      prefix: '$'
    });
     $("input[name='tch3']").TouchSpin();
     $("input[name='tch3_22']").TouchSpin({
      initval: 40
    });
     $("input[name='tch5']").TouchSpin({
      prefix: "pre",
      postfix: "post"
    });
      // For multiselect
      $('#pre-selected-options').multiSelect();
      $('#optgroup').multiSelect({
        selectableOptgroup: true
      });
      $('#public-methods').multiSelect();
      $('#select-all').click(function() {
        $('#public-methods').multiSelect('select_all');
        return false;
      });
      $('#deselect-all').click(function() {
        $('#public-methods').multiSelect('deselect_all');
        return false;
      });
      $('#refresh').on('click', function() {
        $('#public-methods').multiSelect('refresh');
        return false;
      });
      $('#add-option').on('click', function() {
        $('#public-methods').multiSelect('addOption', {
          value: 42,
          text: 'test 42',
          index: 0
        });
        return false;
      });
      $(".ajax").select2({
        ajax: {
          url: "https://api.github.com/search/repositories",
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
                        q: params.term, // search term
                        page: params.page
                      };
                    },
                    processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                      results: data.items,
                      pagination: {
                        more: (params.page * 30) < data.total_count
                      }
                    };
                  },
                  cache: true
                },
                escapeMarkup: function(markup) {
                  return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
          });  

    });
  </script>
</body>
</html>