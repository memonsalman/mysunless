<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
    if(isset($_SESSION['UserID']))
   {
      $id=$_SESSION['UserID'];
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
        header("Location: index.php");die;  
        }
     }
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
   $statement=$db->prepare("SELECT * FROM `ProductBrand` WHERE `createdfk`=:id And isactive=1");
   $statement->bindValue(":id",$id,PDO::PARAM_INT);
   $statement->execute();
   $ProductBrandList = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    $T1x=$all_title1['TitleName'];


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
    <link rel="stylesheet" href="../dist/css/lightbox.min.css">
    
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
                            <?php
                            if(isset($_GET["id"]))
                            {
                            ?>
                               <h4 class="text-themecolor"><?php echo $T1x; ?></h4>
                            <?php       
                                }
                            else
                            {
                                ?>
                                <h4 class="text-themecolor"><?php echo $T1x; ?></h4> 
                                <?php
                            }
                             ?>
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
        <!-- <a href="#" id="addUser" class="btn btn-info m-r-10 " id="newproduct" data-toggle="modal" data-target="#AddProductModal">Add New Product</a> -->
        <a href="#" class="btn btn-info m-r-10 " id="newproduct" data-toggle="modal" data-target="#AddProductModal" style="margin: 5px 0;"><?php echo $B1; ?></a>
        <a href="<?php echo base_url; ?>/AllProductCategory" id="newproductcat"  class="btn btn-info m-r-10" style="margin: 5px 0;"><?php echo $B2; ?></a>
        <a href="<?php echo base_url; ?>/AllProductBrand" id="newproductbrand"  class="btn btn-info m-r-10" style="margin: 5px 0;"><?php echo $B5; ?></a>
                                            <div class="table-responsive m-t-40">
                                              
                                              <div class="Loader"></div>
                             <table id="myTable2" class="table table-bordered table-striped dataTable no-footer text-center" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Product Description</th>
                                                            <th>Product Company Cost</th>
                                                            <th>Product Selling Price</th>
                                                            <th>No of Product</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
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
                                       <label for="ProductCategory">Product Category * <span class="help"></span></label>
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Category" id="ProductCategory" name="ProductCategory[]" multiple data-style="form-control btn-secondary">
                                       <?php
                                          foreach($ProductCategoryList as $value)
                                          {
                                             if($value['Category'] == @$ProductCategory ){
                                                echo '<option selected value="'.$value['Category'].'">'.$value['Category'].'</option>';
                                             }
                                             else{
                                               echo '<option value="'.$value['Category'].'">'.$value['Category'].'</option>';
                                             }
                                          }
                                       ?>
                                       </select>
                                    </div>

                                     <div class="form-group">
                                       <label for="ProductBrand">Brand * <span class="help"></span></label>
                                       <label class="pull-right"><a href="<?php echo base_url; ?>/AllProductBrand">Create New Brand</a></label>
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Brand" id="ProductBrand" name="ProductBrand[]" multiple data-style="form-control btn-secondary">
                                       <?php
                                          foreach($ProductBrandList as $value)
                                          {
                                             if($value['Brand'] == @$ProductBrand ){
                                                echo '<option selected value="'.$value['Brand'].'">'.$value['Brand'].'</option>';
                                             }
                                             else{
                                               echo '<option value="'.$value['Brand'].'">'.$value['Brand'].'</option>';
                                             }
                                          }
                                       ?>
                                       </select>
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
 <input type="text" aria-label="Amount (to the nearest dollar)"  id="SellingPrice" name="SellingPrice" class="form-control" id="SellingPrice" placeholder="i.e. 100 " value="" >
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text">.00</span>
                                </div> -->
                        </div>        
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

                                    <div class="form-group">
                                       <label for="ProductDescription">Discount in Percentage  ( % )</label>
                <input type="text" name="discountinparst" class="form-control" placeholder="Discount in % " value="" id="Commissioninperstantag" readonly>
                                    </div> 

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
<button type="submit" class="btn waves-effect waves-light btn-info" name="addProduct" id="addProduct"><i class="fa fa-check"></i> Update Product Detail</button>
                                 <?php
                                    }else{
                                 ?>
<button type="submit" class="btn waves-effect waves-light btn-info" name="addProduct" id="addProduct"><i class="fa fa-check"></i> <?php echo $B3; ?></button>
                                 <?php 
                                    }
                                 ?>
<a href="<?php echo base_url; ?>/AllProduct.php" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> <?php echo $B4; ?></a>
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
            <!-- ============================================================== -->
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
<script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>



<script>
$(document).ready(function() {

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

    dataTable()
    function dataTable()
    {
        $('#myTable2').DataTable({
            "responsive": true,
            "processing": true,
             "destroy": true,
            "ajax" : {
                "url" : "<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2",
               "dataSrc":'',
            },
            "autoWidth": false,
            "columnDefs": [
              { "targets" : '_all'},
              { "width": "10%", "targets": 0,"className" : 'Pro_info1' },
              { "width": "10%", "targets": 1,"className" : 'Pro_info2'  },
              { "width": "10%", "targets": 2,"className" : 'Pro_info3'  },
              { "width": "10%", "targets": 3,"className" : 'Pro_info4'  },
              { "width": "10%", "targets": 4,"className" : 'Pro_info5'  },
              { "width": "5%", "targets": 5,"className" : 'Pro_info6'  },
              { "width": "5%", "targets": 6 ,"className" : 'Pro_info7' },
            ],
            "columns" : [{
            "data": {ProductImage:"ProductImage", ProductTitle:"ProductTitle"},
            "render": function(data, type, row) {
                if(data.ProductImage!=''){
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/ProductImage/'+data.ProductImage+'"" data-lightbox="example-'+data.ProductImage+'"><img src="<?php echo $base_url ?>/assets/ProductImage/'+data.ProductImage+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.ProductTitle +'</span></div> </div>';    
                }
                else
                {
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" data-lightbox="example-noimage.png"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.ProductTitle +'</span></div> </div>';       
                }
             }
          },
            {
                "data": "ProductDescription",                
            },
            {
            "data": {CompanyCost:"CompanyCost"},
            "render": function(data, type, row) {
                  return '<span>$ '+ data.CompanyCost +'</span>';    
             }
          },
          {
            "data": {SellingPrice:"SellingPrice"},
            "render": function(data, type, row) {
                  return '<span>$ '+ data.SellingPrice +'</span>';    
             }
          },
        
            {
                "data": "NoofPorduct",                
            },
            {
                "data": {id: "id",isactive: "isactive"},
                "render": function(data, type, row) {
                    if (data.isactive == 1){
                         return '<input class="toggle_status" id="'+ data.id +'" type="checkbox"  checked data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="1">' ;
                    }
                    else{
                         return '<input class="toggle_status" id="'+ data.id +'" type="checkbox"  data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="0">' ;
                    }
                }
            },
            {
                "data": "id",
                "render": function(data, type, row) {
                    return '<button class="btn btn-info btn-sm" title="Edit Event" id="editButton" data-id="' + data + '"> <span class="fa fa-edit"><span> </button> <button id="deleteButton" title="Delete" class="btn btn-danger btn-sm" data-id="' + data + '"><span class="fa fa-trash"></span></button>' ;
                }
            }],
            "fnDrawCallback": function() {
                            jQuery('.toggle_status').bootstrapToggle();
                            jQuery('.toggle_status').parent().addClass('toggleBtn');
            }
        });
    }
    $(document).on('click','.toggleBtn',function(){
        $.ajax({
           url: "<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2",
           type: 'post',
           data: {
             id : $(this).children(".toggle_status").attr("id"),
             status: $(this).children(".toggle_status").attr("value")
           },
           success: function(data){
                swal("Status update successfully...","", "success");
                dataTable()
           },
          error: function(errorThrown) {
               swal("Sorry! Failed to update status","", "error");
                dataTable()
          }
        });
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
   });
  $("#NewProduct").validate({

      rules: {       
        ProductTitle: "required",
        ProductDescription: "required",
        CompanyCost: {required: true,number: true},
        SellingPrice: {required: true,number: true},
        "ProductCategory[]": "required",
        "ProductBrand[]": "required",
        NoofPorduct:{required: true,number: true},
      },
      messages: {       
        ProductTitle:  "Please enter product title",
        ProductDescription:  "Please enter product short description",
        CompanyCost: {required: "Please enter cost for employee to bring product in",
                        number: "Please enter only number",
                     },
        SellingPrice: {required: "Please enter selling price to consumer",
                        number: "Please enter only number",
                     },
        "ProductCategory[]":  "Please select at least one product category",
        "ProductBrand[]":  "Please select at least one product Brand",
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
                        $("#resonse").show();
                        $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                        $( '#NewProduct' ).each(function(){
                           this.reset();
                        });
                        $(".modal2").hide();
                        $(".Loader").hide();
                        dataTable();
                        $('#AddProductModal').modal('hide');
                     }
                     else if(data.error)
                     {
                        $("#error").show();
                        $('#errormsg').html('<span>'+data.error+'</span>');
                        $(".modal2").hide();
                        $(".Loader").hide();
                     }
                      else if(data.csrf_error)
                {
                  
                    $("#csrf_error").show();
                    $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    $('#AddProductModal').modal('hide');
                    setTimeout(function () { window.location.reload() }, 2000)
                }
               }
         });
      }       
   });
   // switchery
      var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
      $('.js-switch').each(function() {
         new Switchery($(this)[0], $(this).data());
      });
      // For select 2
      $(".select2").select2();
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