<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
if(isset($_SESSION['UserID']))
{
 $id=$_SESSION['UserID'];
 $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 $stmt->execute();
 $result = $stmt->fetch(PDO::FETCH_ASSOC);
 @$sercreateprmistion=$result['ServicesCreate'];
}
// if($sercreateprmistion==0){
     // header("Location: index.php");die;  
 // }    
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$clientcreateprmistion=$result['ClientCreate'];
    @$schcreateprmistion=$result['SchedulesCreate'];
    @$sercreateprmistion=$result['ServicesCreate'];
    @$todocreateprmistion=$result['TodoCreate'];
}
if(isset($_GET["id"])){
    $myservie = base64_decode($_GET['id']);
}else{
    $myservie = "new";
}
$action="";
if(isset($_GET['action']))
{
    $action=$_GET['action'];
}
$ServiceName="";
$Price="";
$Duration="";
$Category="";
$Users="";
$Type="";
$Info="";
$starttime="";
$endtime="";
$cusmerlimt="";
$asper="";
if($action=='edit'){
    $SelectUser=$db->prepare("select * from Service where id=:myservie");
    $SelectUser->bindValue(':myservie',$myservie,PDO::PARAM_INT);
    $SelectUser->execute();
    if($SelectUser->rowCount() > 0){
        while($select=$SelectUser->fetch(PDO::FETCH_ASSOC)){
            $ServiceName=$select['ServiceName'];
            $Price=$select['Price'];
            $Duration=$select['Duration'];
            $Category=$select['Category'];
            $Users=$select['Users'];
            $Type=$select['Type'];
            $Info=$select['Info'];
            $starttime=$select['starttime'];
            $endtime=$select['endtime'];
            $cusmerlimt=$select['cusmerlimt'];
            $asper=$select['asper'];
        }
    }
}

$id=$_SESSION['UserID'];
$isactive=1;
$Category2 = $db->prepare("select * from `Category` where isactive=:isactive AND createdfk=:id");
$Category2->bindParam(':isactive', $isactive, PDO::PARAM_INT);
$Category2->bindParam(':id', $id, PDO::PARAM_INT);
$Category2->execute();
$allCategory=$Category2->fetchAll();
$id=$_SESSION['UserID'];
$user = $db->prepare("select * from `users` where (adminid =:id AND usertype='employee') OR id=:id");
$user->bindParam(':id', $id, PDO::PARAM_INT);
$user->execute();
$alluser=$user->fetchAll();

$focus=explode(",",$Users);


if(isset($_REQUEST['delid']))
{
    $myservie = base64_decode($_REQUEST["delid"]) ;
    $date = date('Y-m-d h:i:s');
    $DeleteUser = $db->prepare("Update Service set isactive=0, datelastupdated=:date where id=:myservie");
    $DeleteUser->bindValue(":myservie",$myservie);
    $DeleteUser->bindValue(":date",$date);
    $deletefile=$DeleteUser->execute();
    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Service successfully move to Archive List']);die;
    }
    else
    {
        echo  json_encode(["error"=>'done']);die;
    }
}

if(isset($_REQUEST['editid']))
{
    $myservie = base64_decode($_REQUEST["editid"]) ;
    $SelectUser=$db->prepare("select * from Service where id=:myservie");
    $SelectUser->bindValue(':myservie',$myservie,PDO::PARAM_INT);
    $SelectUser->execute();
    $select=$SelectUser->fetch(PDO::FETCH_ASSOC);
    
    if($select)
    {
        echo  json_encode(["resonse"=>$select]);die;
    }
    else
    {
        echo  json_encode(["error"=>'done']);die;
    }
}

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C30'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C31'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];

$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C32'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];


$button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C33'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$B4=$all_button4['button_name'];


$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='11'"); 
$title1->execute();
$all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
$T1=$all_title1['TitleName'];

$button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C34'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$B5=$all_button5['button_name'];

$button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C36'"); 
$button7->execute();
$all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
$B7=$all_button7['button_name'];


$button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C37'"); 
$button8->execute();
$all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
$B8=$all_button8['button_name'];


if(isset($_REQUEST['delid2']))
{
    $id=base64_decode($_POST['delid2']);
    $isactive=0;
    $DeleteClient = $db->prepare("update `Category` set isactive=:isactive where id=:id");
    $DeleteClient->bindValue(":isactive",$isactive,PDO::PARAM_INT);
    $DeleteClient->bindValue(":id",$id,PDO::PARAM_INT);
    $deletefile=$DeleteClient->execute();
    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Category Successfully Remove From List']);die;
    }
    else
    {
        echo  json_encode(["error"=>'done']);die;
    }
}

if(isset($_POST['editid2']))
{
    $mycategory= base64_decode($_POST['editid2']);
    $SSelectCat=$db->prepare("select * from Category where id=:mycategory");
    $SSelectCat->bindValue(':mycategory',$mycategory,PDO::PARAM_INT);
    $SSelectCat->execute();
    $select=$SSelectCat->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>$select]);die;
}
if(isset($_POST['catdata']))
{
    $data = explode(" ", $_POST['catdata']);
    $catid = $data[0];
    $cat = $data[1];
    $DeleteClient = $db->prepare("update `Category` set Category=:cat where id=:catid");
    $DeleteClient->bindValue(":cat",$cat);
    $DeleteClient->bindValue(":catid",$catid);
    $deletefile=$DeleteClient->execute();
    if($deletefile)
    {
        echo  "Success";die;
    }
    else
    {
        echo "Failed";die;
    }
    /*$mycategory= base64_decode($_POST['editid2']);
    $SSelectCat=$db->prepare("select * from Category where id=:mycategory");
    $SSelectCat->bindValue(':mycategory',$mycategory,PDO::PARAM_INT);
    $SSelectCat->execute();
    $select=$SSelectCat->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>$select]);die;*/
}
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
<link href="<?= base_url?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
<link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />

<style>
/*.modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal {overflow: hidden;}
body.loading .modal {display: block;}*/
.eventStart1{width: 45%; float: left; padding: 0 10px;}
.timeinput{width: 50%; float: left; padding: 15px 5px;}
li.select2-selection__choice {color: white !important;}
.select2-container--default .select2-selection--multiple .select2-selection__choice{background-color: #42bfd3!important; border:1px solid #43c1d4!important; }
span.select2.select2-container.select2-container--default.select2-container,span.select2.select2-container.select2-container--default.select2-container--focus{width: 100%!important;}
.bootstrap-touchspin .input-group-btn-vertical>.btn{padding: 9px!important;}

/*.select2-container--default .select2-selection--multiple{border-bottom: 1px solid #e9ecef!important; border-top: 0!important; border-right: 0!important; 
    border-left: 0!important;}*/
    label.error{padding:0 10px!important;}
    @media only screen and (max-width: 600px) {
        .cubutfoma{width: 100% !important;}

    }
    th { font-weight: bold!important;color:#0b59a2!important;}
</style>
<body class="skin-default fixed-layout mysunlessJ">
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
                        <h4 class="text-themecolor"><?php echo $T1; ?></h4>
                        <?php       
                    }
                    else
                    {
                        ?>
                        <h4 class="text-themecolor"><?php echo $T1; ?></h4> 
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

                           <div class="tab-content tabcontent-border">
                            <div class="tab-pane active" id="home" role="tabpanel">
                                <div class="col-lg-12">

                                    <?php if($sercreateprmistion){ ?>
                                  <button data-toggle="modal" data-target="#myModal_service" id="addUser" class="btn btn-info m-r-10 cubutfoma"><?php echo $B1; ?></button>
                                    <?php } ?>
                                  <!-- <a href="https://mysunless.com/AllCategory" class="btn btn-info m-r-10 cubutfoma"><?php echo $B2; ?></a> -->
                                  <!-- <button id="addUserser" class="btn btn-info m-r-10 cubutfoma" data-toggle="modal" data-target="#myModal_sercategory"><?php echo $B5; ?></button> -->
                                  <a href="<?= base_url?>/MembershipPackageList" class="btn btn-info m-r-10 cubutfoma">Add Package</a>


                                  <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> 
                                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                                         <span class="hidden-sm-up"><i class="fa fa-cog"></i></span> <span class="hidden-xs-down">Service</span>
                                     </a> 
                                 </li>
                                   <!-- <li class="nav-item"> 
                                    <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                                     <span class="hidden-sm-up"><i class="fa fa-picture-o"></i></span> <span class="hidden-xs-down">Service Category</span>
                                 </a> 
                             </li> -->
                         </ul>

                         <div class="tab-content">
                            <div class="tab-pane active" id="home2" role="tabpanel">
                                <div class="p-20">

                                    <div class="table-responsive m-t-40">
                                      <table id="myTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Service Name</th>
                                                <th>Price</th>
                                                <th>Duration</th>
                                                <th>User</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="myModal_service" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content  -->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Service</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>

          </div>
          <div class="modal-body">

            <div class="Loader"></div>
            <form class="form-horizontal " autocomplete="off" id="NewServie" method="post">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                
                <div class="form-group">
                    <label><span class="help">Service Name  *</span></label>
                    <input type="text" name="ServiceName" id="ServiceName" class="form-control" value="" placeholder="Service Name" />
                </div> 
                <div class="form-group">
                    <label><span class="help">Service Price  *</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="text" name="Price" id="Price" class="form-control" value="" placeholder="Service Price">
                    </div>
                </div> 
                <div class="form-group">
                    <label><span class="help">Duration  *</span></label>
                    <select name="Duration" id="Duration" class="form-control valid">
                        <option value="">Select Service Duration</option>
                        <option value="0 Min">0 Min</option>
                        <option value="15 Min">15 Min</option>
                        <option value="30 Min">30 Min</option>
                        <option value="1 h">1 h</option>
                        <option value="2 h">2 h</option>
                        <option value="3 h">3 h</option>
                        <option value="4 h">4 h</option>
                        <option value="5 h">5 h</option>
                        <option value="6 h">6 h</option>
                        <option value="7 h">7 h</option>
                        <option value="8 h">8 h</option>
                        <option value="9 h">9 h</option>
                        <option value="10 h">10 h</option>
                        <option value="10 h">11 h</option>
                        <option value="10 h">12 h</option>
                    </select>
                </div> 
                <div id="startime" style="display: none;">
                    <div class="form-group">
                        <label><span class="help">Service Time *</span></label>
                        <div>
                            <div class="timeinput"><input class="form-control" id="starttime" placeholder="Start time" name="starttime" value=""></div>
                            <div class="timeinput"><input class="form-control" id="endtime" placeholder="End time" name="endtime" value=""></div>
                        </div>
                    </div> 
                </div>



                <div class="form-group">
                    <label><span class="help">User  *</span></label>
                    <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Users" id="Users" name="Users[]" multiple data-style="form-control btn-secondary">
                        <option value="">Select User</option>
                        <?php
                        foreach($alluser as $row)
                        {
                            ?>
                            <option  value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>  
                            <?php

                        }
                        ?>
                    </select>
                </div>  

                <div class="form-group">
                </div>

                <div class="form-group">
                    <label for="example-email">Appointment Instruction  *<span class="details"><i data-toggle="tooltip" title="Any information entered into this section will appear as an appointment instruction on the confirmation email that will be sent upon booking this specific service." class="fa fa-question-circle" aria-hidden="true"></i></span></label>
                    <textarea id="Info" class="textarea_editor form-control" rows="5" name="Info" placeholder="Enter your Info..." class="form-control"></textarea>
                </div> 
                <div class="modal"></div>
                <div class="form-group">
                    <?php
                    if(isset($_GET["id"]))
                    {
                        ?>
                        <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> Update Service</button>
                        <?php       
                    }
                    else
                    {
                        ?>
                        <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> <?php echo $B3; ?></button>
                        <?php
                    }
                    ?>
                   
                </div>
            </form>

        </div>
    </div>

</div>
</div>
<!-- edit cat -->
<div class="modal fade" id="editcatmodal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Service Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
      </div>
      <div class="Loader"></div>
      <div class="modal-body">

       <form class="form-horizontal" id="editcategory" autocomplete="off" method="post">
        <input type="hidden" name="id" id="catid" value="">
        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
        <div class="form-group">
           <label><span class="help">Category Name  *</span></label>
           <input type="text" name="cate" id="cate" value="" class="form-control" maxlength="30">
       </div>
       <div class="modal">
       </div>
       <div>
        <button type="button" class="btn waves-effect waves-light btn-info m-r-10" id="editbtn"><i class="fa fa-check">
        </i> Update Category</button>
    </div>
</form>



</div>
</div>

</div>
</div>
<!-- edit cat -->
<div class="modal fade" id="myModal_sercategory" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Service Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
      </div>
      <div class="Loader"></div>
      <div class="modal-body">

       <form class="form-horizontal" id="NewCategory" autocomplete="off" method="post">
        <input type="hidden" name="id" id="id" value="new">
        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
        <div class="form-group">
           <label><span class="help">Category Name  *</span></label>
           <input type="text" name="Category" id="Category" value="" class="form-control" maxlength="30">
       </div>
       <div class="modal">
       </div>
       <div class="form-group">
        <?php
        if(isset($_GET["id"]))
        {
            ?>
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check">
            </i> Update Category</button>
            <?php       
        }
        else
        {
            ?>
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check">
            </i> <?php echo $B7; ?></button>
            <?php
        }
        ?>
                   
                                                    </div>
                                                </form>



                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="alert alert-success" id="resonse2" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg2"></p>
                                    </div>
                                    <div class="alert alert-danger" id="error2" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg2"></p>
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
    <!-- ==============================================================  -->
    <?php include 'scripts.php'; ?>
    <script src="<?= base_url?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="<?= base_url?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
    <script src="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.js"></script>
    <script src="<?= base_url?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/dff/dff.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= base_url?>/assets/node_modules/multiselect/js/jquery.multi-select.js"></script>
    <script src="<?= base_url?>/assets/node_modules/moment/moment.js"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script type='text/javascript'src='<?php echo base_url ?>/assets/js/timepicki.js'></script>
    <script type="text/javascript">

        $("#Info").ckeditor();

              // For select 2
              $(".select2").select2({
                allowClear: true,
                tags: true
            });

              $('#starttime').timepicki();
              $('#endtime').timepicki();
        ///custom script end
        $('#Price').keypress(function(event) {
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

        $('#Price').bind("paste", function(e) {
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


        $(document).ready(function(){
            //sidebar dropdown
            $("#companydrop").trigger("click");
            setInterval(function(){$(".viewser").addClass("active");}, 10);
            dataTable()
            function dataTable()
            {
                $('#myTable').DataTable({
                    "responsive": true,
                    "processing" : true,
                    "destroy": true,
                    "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_AllService",
                        dataSrc : ''
                    }
                    ,
                    "autoWidth": false,
                    "columnDefs": [
                    {
                        "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "17%", "targets": 0 }
                            ,
                            {
                                "width": "9%", "targets": 1 }
                                ,
                                {
                                    "width": "14%", "targets": 2 }
                                    ,
                                    // {
                                    //     "width": "13%", "targets": 3 }
                                    //     ,

                                    {
                                        "width": "16%", "targets": 3 }
                                        ,
                                        {
                                            "width": "13%", "targets": 4 }
                                            ,
                                            ],
                                            "columns" : [ {
                                                "data" : "ServiceName"
                                            }
                                            , {
                                                "data": {Price:"Price"}
                                                ,
                                                "render": function(data, type, row) {
                                                    return '<span>$'+ data.Price +'</span>';
                                                }
                                            }
                                            ,
                                            {
                                                "data" : "Duration"
                                            }
                                            ,
                                                //  {
                                                //     "data" : "Category"
                                                // },
                // {
                // "data" : "Type"
                // },
                {
                    "data" : "userbane"
                }
                ,{
                    "data": "id",
                    "render": function(data, type, row) {
                        var encodedId = window.btoa(data);
                        <?php if($sercreateprmistion){ ?>
                        return '<button class="btn btn-info btn-sm delete cubutfoma" title="Edit Data" id="editButton" data-id='+ encodedId +'><span class="fa fa-edit"><span></button> <button class="btn btn-danger btn-sm cubutfoma" title="Delete Data" id="deleteButton" data-id='+ encodedId +'><span class="fa fa-trash"><span></button>';
                    <?php }else{ ?>
                        return 'No Permission';
                    <?php } ?>
                    }
                }
                ]
            });
            }

            //dataTable2()


            // function dataTable2()
            // {
            //     $('#myTable2').DataTable({
            //         "responsive": true,
            //         "processing": true,
            //         "destroy": true,
            //         "ajax" : {
            //             "url" : "<?php echo EXEC; ?>Exec_Edit_Category.php?viewdata",
            //             "dataSrc":'',
            //         }
            //         ,
            //         "autoWidth": false,
            //         "columnDefs": [
            //         {
            //             "className" : 'text-center', "targets" : '_all'}
            //             ,
            //             {
            //                 "width": "50%", "targets": 0 }
            //                 ,
            //                 {
            //                     "width": "50%", "targets": 1 }
            //                     ,
            //                     ],
            //                     "columns" : [{
            //                         "data": "Category",                
            //                     }
            //                     ,{
            //                         "data": "id",
            //                         "render": function(data, type, row) {
            //                             var encodedId = window.btoa(data);
            //                             return '<button class="btn btn-info btn-sm " title="Edit Event" id="editcat" data-id='+ encodedId + '> <span class="fa fa-edit"><span> </button> <button id="deleteButton2" title="Delete" class="btn btn-danger btn-sm " data-id="' + encodedId + '"><span class="fa fa-trash"></span></button>' ;
            //                         }
            //                     }
            //                     ]
            //                 }
            //                 );
            // }
    // jQuery.validator.addMethod("time_required", function (value, element) {
    //     duration = $("#Duration").val().split(" ");
    //     if(duration[1]=="h" && value==""){
    //         return false;
    //     }else{
    //         return true;
    //     }
    // },"Required");
    // jQuery.validator.addMethod("time_valid", function (value, element) { 
    //     starttime = $("#starttime").val();
    //     duration = $("#Duration").val().split(" ");
    //     if(starttime){
    //      // current_time = moment(moment().format("hh:mma"), 'hh:mma').diff(moment().startOf('day'), 'seconds');
    //       start_time = moment(starttime, 'hh:mma').diff(moment().startOf('day'), 'seconds');
    //       end_time = moment(value, 'hh:mma').diff(moment().startOf('day'), 'seconds')-moment(duration[0], 'h').diff(moment().startOf('day'), 'seconds');
    //       console.log(start_time+" "+end_time);
    //       if(end_time>=start_time){
    //         return true;
    //       }else{
    //         return false
    //       }
    //     }else{
    //      return true;
    //    }
    //  }, "End time should be more than start time + duration.");
        // let serviceType = $("#id").val();
        // console.log(serviceType);
        $("#NewServie").validate({
            rules: {                
                ServiceName: {

                    required: true,
                    maxlength: 50,
                    remote: {

                        url: "<?php echo EXEC; ?>Exec_Edit_Service.php",
                        type: "post",
                        data: {
                          serviceChk: function() {
                            return $("#ServiceName").val();
                        },
                        serviceId:function(){
                            return $("#id").val();
                        }
                    }
                }
            },
            Price: {required: true,number: true,maxlength: 10},
                    // starttime:{time_required:true},
                    // endtime:{time_required:true,time_valid:true},
                    'Users[]' : {required:true},
                    Info: {required:true},
                    Duration: {required:true,maxlength: 10},
                // Category: {required:true},
               // cusmerlimt: {required:true},
               // Type: {required:true},
           },
           messages: {             
            ServiceName: {required: "Please enter service name"},
            Price: {required: "Please enter service price",
            number: "Please enter valid price",
        },
        'Users[]' : {required: "Please select users"},
        Info : {required: "Please enter service info"},
        Duration : {required: "Please select service duration"},
                // Category : {required: "Please select category"},
               // cusmerlimt : {required: "Please select customer limit"},
               // 
               // Type : {required: "Please select service type"}
           },
           ignore: ":hidden:not(textarea)",
           errorPlacement: function( label, element ) {
            if( element.attr( "name" ) === "Users[]" || element.attr( "name" ) === "Info"  ) {
                element.parent().append( label );
            } else {
               label.insertAfter( element );
           }
       },
       submitHandler: function() {
        $(".Loader").show();
        var data = $("#NewServie").serialize();       
        data= data + "&Action=Category";        
        jQuery.ajax({
         dataType:"json",
         type:"post",
         data:data,
         url:'<?php echo EXEC; ?>Exec_Edit_Service',
         success: function(data)
         {
            if(data.resonse){

                $("#resonse2").show();
                $('#resonsemsg2').html('<span>'+data.resonse+'</span>');
                $("#myModal_service").modal("hide");
                $( '#NewServie' ).each(function(){ this.reset();});
                $(".Loader").hide();
                dataTable()
                // datatable2()
                $("#myModal_service").modal('hide');
                setTimeout(function(){  $("#resonse2").fadeOut(); }, 5000); 

            }
            else if(data.error) {
                $("#error2").show();
                $('#errormsg2').html('<span>'+data.error+'</span>');
                $(".Loader").hide();
                setTimeout(function(){  $("#error2").fadeOut(); }, 5000); 
                                        // alert('<li>'+data.error+'</li>');
                                    }
                                }
                            });
    }           
});

        $(document).on('click','#deleteButton',function(e){
            $(".Loader").show();
            e.preventDefault();
            swal({
                title: "Temporary Delete?",
                text: "Once deleted, it will move to Archive list!",
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
                            swal('',data.resonse,'success');
                            $(".Loader").hide();
                            dataTable()
                        }
                        else if(data.error){
                            swal('',data.error,'error');
                            $(".Loader").hide();

                        }
                    }
                });
              }
              else{
                $(".Loader").hide();

                return false ;


            }
        });
        });

        $(document).on('click','#editButton',function(e){
            $(".Loader").show();
            e.preventDefault();

            var editid=$(this).attr('data-id');
            $.ajax({
                dataType:"json",
                type:"post",
                data:{'editid':editid},
                url:'?action=deletefile',
                success: function(data)
                {
                    if(data.resonse)
                    {

                       $("#myModal_service").modal('show');
                       $("#id").val(data.resonse.id);
                       $("#ServiceName").val(data.resonse.ServiceName);
                       $("#Price").val(data.resonse.Price);
                       $("#Duration").val(data.resonse.Duration);
                       $("#Category").val(data.resonse.Category);
                       var user = data.resonse.Users.split(',');                       
                       var i=0;
                       var selectedValues = new Array();
                       $.each(user,function(){
                        selectedValues[i] = user[i];
                        i++;
                    });
                       $('#Users').val(selectedValues).trigger('change');
                       $("#Type").val(data.resonse.Type);
                       $("#cusmerlimt").val(data.resonse.cusmerlimt);
                       $("#asper").val(data.resonse.asper);
                       $("#starttime").val(data.resonse.starttime);
                       $("#endtime").val(data.resonse.endtime);
                       $('#Info').val(data.resonse.Info);





                       $(".Loader").hide();

                   }
                   else if(data.error)
                   {
                    swal('',data.error,'error');
                    $(".Loader").hide();


                }
            }
        });

        });

        $(document).on('click','#addUserser',function(e){

            $('#Category').val('');
            $('#id').val('new');
        });

        $(document).on('click','#deleteButton2',function(e){
            e.preventDefault();
            $(".Loader").show();
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will lost all data of this Category!",
                icon: "warning",
                buttons: true,
            }
            ).then((willDelete)=>{
                if (willDelete){
                    var delid2=$(this).attr('data-id');
                    $.ajax({
                        dataType:"json",
                        type:"post",
                        data:{
                            'delid2':delid2}
                            ,
                            url:'?action=deletefile',
                            success: function(data)
                            {
                                if(data.resonse){
                                    swal('',data.resonse,'success');
                                    $(".Loader").hide();
                                    dataTable()

                                }
                                else if(data.error){
                                 swal('',data.error,'error');
                                 $(".Loader").hide();

                             }
                         }
                     }
                     );
                }
                else{
                    $(".Loader").hide();
                    return false ;
                }
            }
            );
        });

        $("#NewCategory").validate({
            rules: {
                Category: {
                    required: true,}
                }
                ,
                messages: {
                    Category: {
                        required: "Please enter category"}
                        ,
                    }
                    ,
                    submitHandler: function() {
                        $(".Loader").show();
                        var data = $("#NewCategory").serialize();
                        data= data + "&Action=Category";
                        jQuery.ajax({
                            dataType:"json",
                            type:"post",
                            data:data,
                            url:'<?php echo EXEC; ?>Exec_Edit_Category',
                            success: function(data)
                            {
                                if(data.resonse)
                                {
                                    swal('',data.resonse,'success');
                                    $( '#NewCategory' ).each(function(){
                                        this.reset();
                                    });
                                    $("#myModal_sercategory").modal('hide'); 
                                    $(".Loader").hide();
                                    dataTable()

                                }
                                else if(data.error)
                                {
                                  swal('',data.error,'error');
                                  $(".Loader").hide();

                              }
                          }
                      }
                      );
                    }
                });
        $(document).on('click','#editbuttoon2',function(e){
            $("#id").val('');
            $(".Loader").show();
            var editid2=$(this).attr('data-id');
            $.ajax({
                dataType:"json",
                type:"post",
                data:{'editid2':editid2},
                url:'?action=deletefile',
                success: function(data)
                {
                    if(data.resonse)
                    {
                        $(".Loader").hide();
                        $("#Category").val(data.resonse.Category);
                        $("#id").val(data.resonse.id);
                        $("#myModal_sercategory").modal('show');
                    }
                    else if(data.error)
                    {
                        $("#error").show();
                        $(".Loader").hide();
                    }
                }
            });

        });
        /*edit cat*/
        $(document).on('click','#editcat',function(e){
            $("#id").val('');
            $(".Loader").show();
            var editid2=$(this).attr('data-id');
            $.ajax({
                dataType:"json",
                type:"post",
                data:{'editid2':editid2},
                url:'?action=deletefile',
                success: function(data)
                {
                    console.log(data);
                    if(data.resonse)
                    {
                        $(".Loader").hide();
                        $("#cate").val(data.resonse.Category);
                        $("#catid").val(data.resonse.id);
                        $("#editcatmodal").modal('show');
                    }
                    else if(data.error)
                    {
                        $("#error").show();
                        $(".Loader").hide();
                    }
                }
            });

        });

        $("#editbtn").on("click",function(){
           var catid=$("#catid").val();
           var catval=$("#cate").val();
           var catdata=catid+' '+ catval;
           $.ajax({
            type:"post",
            data:{'catdata':catdata},
            url:'?action=deletefile',
            success: function(data)
            {
                if(data == "Success")
                {   
                    swal("Success","Category Name Updated Successfully","success").then((value) => {
                        location.reload(true);
                    });
                }
                else
                {
                    swal("Failed","Please tru again later","error");
                }
            }
        });
       });
        /*edit cat*/


        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2
        $(".select2").select2();
        $('.selectpicker').selectpicker();
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



    });
</script>

<script>
    jQuery(document).ready(function() {
        // Switchery
        $(document).on('click','#addUser',function(e){
            $("#id").val('new');

            $("#NewServie")[0].reset();
            $('.select2-selection__choice').remove();
        });


    });
</script>

<script type="text/javascript">
    // $('#starttime').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
    // $('#endtime').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
</script>
<script type="text/javascript">
    //$('#Duration').on('change',function(){
    //    var selection = $(this).val();

        // switch(selection)
        // {
        //     case "1 h":
        //     $("#startime").show()
        //     break;
        //     case "2 hh":
        //     $("#startime").show()
        //     break;
        //     case "2 h":
        //     $("#startime").show()
        //     break;
        //     case "3 h":
        //     $("#startime").show()
        //     break;
        //     case "4 h":
        //     $("#startime").show()
        //     break;
        //     case "5 h":
        //     $("#startime").show()
        //     break;
        //     case "6 h":
        //     $("#startime").show()
        //     break;
        //     case "1 Week":
        //     $("#startime").show()
        //     break;
        //     default:
        //     $("#startime").hide()
        // }
    //});
</script>

</body>
</html>