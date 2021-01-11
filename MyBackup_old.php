<?php 
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once('global.php');

  require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/gmail/settings.php');

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
           @$schcreateprmistion=$result['SchedulesCreate'];
           @$Gmail_value=$result['Gmail_value'];
           @$Gmail_displayName=$result['Gmail_displayName'];
           @$Gmail_url=$result['Gmail_url'];
        }
$action="";
if(isset($_GET["action"])){
    $action=$_GET["action"];
}
        $fmail = "";
        $fname = "";
        $smtphost="";
        $toe="";
        $smtpport="";
        $sa="";
        $smtpusername="";
        $smtppassword="";
    $id=$_SESSION['UserID'];
    $EditEvent=$db->prepare("select * from `EmailSetting` where UserID=:id");
    // $EditEvent->bindValue(":myevent",$myevent, PDO::PARAM_INT);
    $EditEvent->bindParam(':id', $id, PDO::PARAM_INT);
    $EditEvent->execute();
    $GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
    $fmail=$GetEvent['fmail'];
    $fname=$GetEvent['fname'];
    $smtphost=$GetEvent['smtphost'];
    $toe=$GetEvent['toe'];
    $smtpport=$GetEvent['smtpport'];
    $sa=$GetEvent['sa'];
    $smtpusername=$GetEvent['smtpusername'];
    $smtppassword=$GetEvent['smtppassword'];
    $olduserid=$GetEvent['UserID'];
    $newid=$GetEvent['id'];
if(!empty($olduserid))
{
    $myevent = $newid;
}
else
{
    $myevent = "new";
}
if(isset($_POST['disbleid']))
{
            $id=$_POST["disbleid"];
            $Gmail_value='';
           $Gmail_displayName='';
           $Gmail_url='';
            $query = $db->prepare("UPDATE users SET 
             `Gmail_value`=:Gmail_value,
             `Gmail_displayName`=:Gmail_displayName,
             `Gmail_url`=:Gmail_url
              WHERE id=:id");
              $query->bindValue(':Gmail_value',$Gmail_value, PDO::PARAM_STR);
              $query->bindValue(':Gmail_displayName',$Gmail_displayName, PDO::PARAM_STR);
              $query->bindValue(':Gmail_url',$Gmail_url, PDO::PARAM_STR);
              $query->bindValue(':id',$id, PDO::PARAM_STR); 
              $query->execute();
              if($query)
            {
          echo  json_encode(["resonse"=>'Your Gmail Account Successfly Disalbe']);die;
        }
}
 // subscription expire progressbar
    if(@$_SESSION['usertype'] == 'subscriber'){
        $id= $_SESSION['UserID'];
        $subscription= $db->prepare("SELECT * FROM `payments` WHERE userid=:id ORDER BY `paytime` DESC LIMIT 1 ");
        $subscription->bindParam(':id', $id, PDO::PARAM_INT);
        $subscription->execute();
        $last_subscription= $subscription->fetch();
        $last_payment_date= $last_subscription['paytime'] ;
        $last_payment_date= substr($last_payment_date, 0, 10);
        $todaydate= date('Y-m-d');
        $difference= abs(strtotime($todaydate) - strtotime($last_payment_date));
        $subscription_years = floor($difference / (365*60*60*24));
        $subscription_months = floor(($difference - $subscription_years * 365*60*60*24) / (30*60*60*24));
        $subscription_days = floor(($difference - $subscription_years * 365*60*60*24 - $subscription_months*30*60*60*24)/ (60*60*24));
        $remaining_days = 30 - $subscription_days ;
    }
    // End subscription expire progressbar

    if(isset($_POST['select_day']))
    {

            $duration = $_POST['select_day'];
            $createdfk = $_POST['userid'];
            $crateddate= date('Y-m-d');
        
        $oldbackup= $db->prepare("SELECT * FROM `BackupReminder` WHERE createdfk=:createdfk");
        $oldbackup->bindParam(':createdfk', $createdfk, PDO::PARAM_INT);
        $oldbackup->execute();
        $last_backup= $oldbackup->fetch();
        $last_bacup_duration= $last_backup['duration'] ;
          if(empty($last_bacup_duration))
          {
            $insert_data_email=$db->prepare("INSERT INTO BackupReminder(createdfk,crateddate,duration) VALUES(:createdfk,:crateddate,:duration)");
            $insert_data_email->bindparam(":createdfk",$createdfk);
            $insert_data_email->bindparam(":crateddate",$crateddate);
            $insert_data_email->bindparam(":duration",$duration);
            $insert_data_email->execute();  
              if($insert_data_email)
           {
          echo json_encode(["resonse"=>'Your Backup Reminder Successfly Set']);die;
          }

          }
          else{

            $stmt=$db->prepare("update BackupReminder set crateddate=:crateddate, duration=:duration where createdfk=:createdfk");
            $stmt->bindparam(":createdfk",$createdfk);
            $stmt->bindparam(":crateddate",$crateddate);
            $stmt->bindparam(":duration",$duration);
            $stmt->execute();

                if($stmt)
           {
            echo  json_encode(["resonse"=>'Your Backup Reminder Successfly Update']);die;
          } 
          } 

    }

          $button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C104'"); 
     $button1->execute();
     $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
     $B1=$all_button1['button_name'];

    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C105'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

     $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C106'"); 
    $button3->execute();
     $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
     $B3=$all_button3['button_name'];

   $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='33'"); 
   $title1->execute();
   $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
   $T1=$all_title1['TitleName'];
?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style>
.Loader {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('<?= $SUB?>/assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .Loader{overflow: hidden;}
body.loading .Loader{display: block;}
.eventStart1{width: 45%; float: left; padding: 0 10px;}
.panel-body { padding: 15px;}
.panel-heading {
    height: 50px;
    background-color: #f6f8f8;
    border-color: #edf1f2;
}
.panel.panel-default {
    min-height: 257px;
    border: 1px solid #dee5e7;
}
.panel-desc{min-height:85px ;}
.m-b-sm {margin-bottom: 10px;}
.m-l-xs {margin-left: 5px;}
.integrate-div {
   padding: 30px 20px;
   border: 1px solid rgba(0, 0, 0, 0.1);
   border-radius: 2px;
   text-align: center;
   margin: 10px 10px 10px 70px;
   display: inline-grid;
   background-color: #f6f8f8;
}
.integrate-logo-div{ height: 100px;}
.settingsSubheading{ height: 50px;  }
</style>
<body class="skin-default fixed-layout mysunlessA8">
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
                <?php
                     if(@$_SESSION['usertype'] == 'subscriber' && @$remaining_days<= '10'){
                ?>
                    <div class="col-lg-12 col-md-12">
                         <div class="alert alert-danger" id="error" style="display: block;">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
<h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Your Subscription will be expire in <?php echo $remaining_days ; ?> days. </h3>
                         </div>
                     </div>
                <?
                    }
                ?>   
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                    <div class="col-md-12">

                                      <div class="d-flex align-items-center" style="padding-bottom: 25px;">

                                        <?php
                                          $id =$_SESSION['UserID'];
                                          $oldbackup= $db->prepare("SELECT * FROM `BackupReminder` WHERE createdfk=:id");
                                          $oldbackup->bindParam(':id', $id, PDO::PARAM_INT);
                                          $oldbackup->execute();
                                          $last_backup= $oldbackup->fetch();
                                          @$last_bacup_duration= $last_backup['duration'];
                                          @$last_bacup_date = $last_backup['crateddate'];
                                                
                                                if($last_bacup_duration == '15')
                                                {
                                              for($i=0; $i<=100; $i++)
                                                {
                                                  $last_bacup_date = date('Y-m-d', strtotime($last_bacup_date. ' + 15 days'));
                                                  if($last_bacup_date==date('Y-m-d'))
                                                  {
                                                   ?>
                                                  <div class="col-lg-10 col-md-10">
                                                  <div class="alert alert-success" id="error" style="display: block;">
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-exclamation-circle"></i> Today is your backup day</h3>
                                                  </div>
                                                  </div>
                                                  <?php
                                                }
                                                }
                                              }

                                              if($last_bacup_duration == '7')
                                                {
                                              for($i=0; $i<=100; $i++)
                                                {
                                                  $last_bacup_date = date('Y-m-d', strtotime($last_bacup_date. ' + 7 days'));
                                                  if($last_bacup_date==date('Y-m-d'))
                                                  {
                                                   ?>
                                                  <div class="col-lg-10 col-md-10">
                                                  <div class="alert alert-success" id="error" style="display: block;">
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-exclamation-circle"></i> Today is your backup day</h3>
                                                  </div>
                                                  </div>
                                                  <?php
                                                }
                                                }
                                              }

                                                if($last_bacup_duration == '30')
                                                {
                                              for($i=0; $i<=100; $i++)
                                                {
                                                  $last_bacup_date = date('Y-m-d', strtotime($last_bacup_date. ' + 30 days'));
                                                  if($last_bacup_date==date('Y-m-d'))
                                                  {
                                                   ?>
                                                  <div class="col-lg-10 col-md-10">
                                                  <div class="alert alert-success" id="error" style="display: block;">
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-exclamation-circle"></i> Today is your backup day</h3>
                                                  </div>
                                                  </div>
                                                  <?php
                                                }
                                                }
                                              }



                                        ?>
                                                <select class="custom-select w-25 ml-auto" id="Upcomingbackup">
                                              <?php
                                                  if(!empty($last_bacup_duration))
                                                  {?>
                                                       <option selected value="<?php echo $last_bacup_duration; ?>"><?php echo $last_bacup_duration.' '.'Days'?></option>
                                                  <?php
                                                }
                                                else
                                                {?>

                                                        <option selected value="">Please Select Duration</option>
                                                <?php
                                              }
                                              ?>
                                                    <option value="30">30 Days</option>
                                                    <option  value="15">15 Days</option>
                                                    <option value="7">7 Days</option>
                                                </select>
                                            </div>      
                                              <div class="Loader"></div>
                     <div class="card">
                        <div class="card-body">
                           <div class="col-lg-12 col-md-12">
                              <div class="integrate-div col-md-3 col-lg-3">
                                 <div class="integrate-logo-div">
                                    <img class="quickbooks-integrate" src="<?php echo base_url ;?>/assets/images/integration-google.png" width="60">
                                    <div class="integration-title">Google</div>
                                 </div>
 <a href="<?php echo base_url?>/googledrivebackup" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> <?php echo $B1; ?></a>
                              </div>
                              <div class="integrate-div col-md-3 col-lg-3">
                                 <div class="integrate-logo-div">
                                    <img class="quickbooks-integrate" src="<?php echo base_url ;?>/assets/images/Microsoft.png" width="200">
                                    <div class="integration-title">Outlook</div>
                                 </div>
<!-- <a href="<?php echo base_url?>/php-skydrive-master/example/upload.php" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> <?php echo $B2; ?></a> -->
<a href="<?php echo base_url?>/onedrive/index" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> <?php echo $B2; ?></a>
                              </div>
                                    <div class="integrate-div col-md-3 col-lg-3">
                                 <div class="integrate-logo-div">
                                    <img class="quickbooks-integrate" src="<?php echo base_url ;?>/assets/images/dropbox.png" width="100">
                                    <div class="integration-title">Dropbox</div>
                                 </div>
<a href="<?php echo base_url?>/DropPHP-master/samples/upload-form" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> <?php echo $B3; ?></a>
                              </div>
                           </div>
                        </div>
                        <div class="modal2"></div>
                        <?php if(!empty($_SESSION['backmsg']))
                               {
                                    $msg=$_SESSION['backmsg'];
                            ?>
                            <div class="col-lg-12 col-md-12">
                            <div class="alert alert-success" id="resonse" style="display: block;">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"><?php echo $msg; ?></p>
                           </div>
                            </div>
                        <?php
                        }
                         unset($_SESSION['backmsg']);
                        ?>
                        <?php if(!empty($_SESSION['backmsg_one']))
                               {
                                    $msg2=$_SESSION['backmsg_one'];
                            ?>
                            <div class="col-lg-12 col-md-12">
                            <div class="alert alert-success" id="resonse_one" style="display: block;">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg_one"><?php echo $msg2; ?></p>
                           </div>
                            </div>
                        <?php
                        }
                         unset($_SESSION['backmsg_one']);
                        ?>
                        <?php if(!empty($_SESSION['backmsg_dropbox']))
                               {
                                    $msg3=$_SESSION['backmsg_dropbox'];
                            ?>
                            <div class="col-lg-12 col-md-12">
                            <div class="alert alert-success" id="resonse_one" style="display: block;">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg_one"><?php echo $msg3; ?></p>
                           </div>
                            </div>
                        <?php
                        }
                         unset($_SESSION['backmsg_dropbox']);
                        ?>
                     </div>

                          <div class="col-lg-12 col-md-12" style="padding: 15px 0;">
                                        <div class="alert alert-success" id="resonse" style="display: none;">
                                            <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                                        </div>
                                        <div class="alert alert-danger" id="error" style="display: none;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                                        </div>
                                    </div>
                  </div>
                                <!-- Nav tabs -->
                          <!--       <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> 
                                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                                            <span class="hidden-sm-up"><i class="fa fa-google"></i></span> <span class="hidden-xs-down">Google Drive</span>
                                        </a> 
                                    </li>
                                    <li class="nav-item"> 
                                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                                            <span class="hidden-sm-up"><i class="fa fa-windows"></i></span> <span class="hidden-xs-down">One Drive</span>
                                        </a> 
                                    </li>
                                </ul> -->
                                <!-- Tab panes -->
                          <!--       <div class="tab-content">
                                    <div class="tab-pane active" id="home2" role="tabpanel">
                                        <div class="p-20">
                                                <div class="col-lg-4 col-sm-6 col-xs-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <center>
                                                                <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="<?php echo base_url ;?>/assets/images/googledrive.png">
                                                            </center>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="panel-desc">
                                                                <div class="m-b-sm">
                                                                </div>
                                                            </div>
                                                            <center>
                                                                <a href="<?php echo base_url?>/googledrivebackup.php" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> Backup in Google Drive</a>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                           <?php if(!empty($_SESSION['backmsg']))
                                            {
                                                $msg=$_SESSION['backmsg'];
                                                ?>
                                               <div class="col-lg-12 col-md-12" style="padding: 15px 0;">
                                        <div class="alert alert-success" id="resonse" style="display: block;">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"><?php echo $msg; ?></p>
                                        </div>
                                    </div>
                                                <?php
                                            }
                                            unset($_SESSION['backmsg']);
                                                ?>
                                </div>
                                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                                          <div class="col-lg-4 col-sm-6 col-xs-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <center>
                                                                <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="<?php echo base_url ;?>/assets/images/onedrive.png">
                                                            </center>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="panel-desc">
                                                                <div class="m-b-sm">
                                                                </div>
                                                            </div>
                                                            <center>
                                                                <a href="<?php echo base_url?>/php-skydrive-master/example/upload.php" class="btn btn-info"><i class="fa fa-sign-in-alt"> </i> Backup in One Drive</a>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if(!empty($_SESSION['backmsg_one']))
                                            {
                                                $msg=$_SESSION['backmsg_one'];
                                                ?>
                                               <div class="col-lg-12 col-md-12" style="padding: 15px 0;">
                                        <div class="alert alert-success" id="resonse" style="display: block;">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"><?php echo $msg; ?></p>
                                        </div>
                                    </div>
                                                <?php
                                            }
                                            unset($_SESSION['backmsg_one']);
                                                ?>
                                </div>
                                </div> -->
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
     <script src="<?php echo base_url ?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //sidebar dropdown
    $("#companydrop").trigger("click");
        $("#NewEvent").validate({
            rules: {                
                 fmail: {required: true,},
                 fname: {required: true,},
                 smtphost: {required: true,},
                 toe: {required: true,},
                 smtpport: {required: true,},
                 sa: {required: true,},
                 smtpusername: {required: true,},
                 smtppassword: {required: true,},
            },
            messages: {             
                fmail: {required: "Please Enter "},
                fname: {required: "Please Enter "},
                smtphost: {required: "Please Enter "},
                toe: {required: "Please Enter "},
                smtpport: {required: "Please Enter "},
                sa: {required: "Please Enter "},
                smtpusername: {required: "Please Enter "},
                smtppassword: {required: "Please Enter "},
            },
                ignore: ":hidden:not(textarea)",
                errorPlacement: function( label, element ) {
                    if( element.attr( "name" ) === "EmailInstruction" || element.attr( "name" ) === "EmailReminder" ) {
                        element.parent().append( label );
                    } else {
                         label.insertAfter( element );
                    }
                    if( element.attr( "name" ) === "Riminederdate[]" ) {
                        element.parent().parent().append( label );
                    }
                },
                submitHandler: function() {
                $(".Loader").show();
            var data = $("#NewEvent").serialize();
             data= data + "&LoginAction=Login";
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_Edit_EmaiSetting.php',
                    success: function(data)
                {
                        if(data.resonse)
                {
                    $("#resonse").show();
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    $( '#NewEvent' ).each(function(){
                         this.reset();
                         });
                    $(".Loader").hide();
                    setTimeout(function () { window.location.reload(); }, 5000)
                }
                else if(data.error)
                {
                    $("#error").show();
                      $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                // alert('<li>'+data.error+'</li>');
                }
                     }   
                    });
                }           
        });


        $('#Upcomingbackup').change(function () {
            var select_day = $(this).val();
            var userid = '<?php echo $_SESSION['UserID']; ?>';
            $(".Loader").show();
            jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:{select_day:select_day,userid:userid},
                    url:'?backup',
                    success: function(data)
                {
                        if(data.resonse)
                {
                    $("#resonse").show();
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    
                    $(".Loader").hide();
                    
                }
                else if(data.error)
                {
                    $("#error").show();
                      $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                // alert('<li>'+data.error+'</li>');
                }
                     }   
                    });

        });

 });
</script>
<script type="text/javascript">
  $("#gmail_disable").click(function(){
            var data = '<?php echo $_SESSION['UserID']; ?>';
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:{disbleid:data},
                    url:'EmailSendSetting.php',
                    success: function(data)
                {
                        if(data.resonse)
                {
                    $("#resonse").show();
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    $( '#NewEvent' ).each(function(){
                         this.reset();
                         });
                    $(".Loader").hide();
                    setTimeout(function () { window.location.reload(); }, 1000)
                }
                else if(data.error)
                {
                    $("#error").show();
                      $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                alert('<li>'+data.error+'</li>');
                }
                     }   
                    });
 });
</script>
<script type="text/javascript">
  $(".close").click(function(){
    $("#error").hide();
    $("#resonse").hide();
});
</script>
</body>
</html>