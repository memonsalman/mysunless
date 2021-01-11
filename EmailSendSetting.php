<?php 

require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/gmail/settings.php');

if(empty($_SESSION["UserID"]) || $_SESSION['usertype']!="subscriber"){
  header("Location: ../index.php");die;
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
  $username='';
  $email='';  
  $userid=$_POST["disbleid"];
  $insert_data_email=$db->prepare("UPDATE EmailSetting SET 
   `fmail`=:email,
   `fname`=:username,
   smtphost = '',
   toe = '',
   smtpport = '',
   sa = '',
   smtpusername = '',
   smtppassword = ''
   WHERE userid=:userid");
  $insert_data_email->bindparam(":username",$username);
  $insert_data_email->bindparam(":email",$email);
  $insert_data_email->bindparam(":userid",$userid);
  $insert_data_email->execute();  
  if($insert_data_email)
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

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C94'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];


$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C95'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='30'"); 
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
  .modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
    url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
    body.loading .modal {overflow: hidden;}
    body.loading .modal {display: block;}
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
    .googleloginLayout{
     max-width:fit-content;

   }
 </style>
 <body class="skin-default fixed-layout mysunlessA6">
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
         <!--  <?php
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
         ?>    -->
         <div class="col-md-12">
          <div class="card">
            <?php
            if(@$_REQUEST['cmpinfo'] == 1)
            {
              ?>
              <div style="margin-top: 30px;" class="col-md-12">


               <?php 
               if($cdata)
               { 
                ?>
                
                <div class="col-md-2" style="float: left;"> 
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" readonly checked class="custom-control-input" id="customCheck11" >
                    <a href="<?php echo base_url; ?>/SetupWizard.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Company Information</a>
                  </div>
                </div>    

                <? 
              }
              else
              {
                ?>

                <div class="col-md-2" style="float: left;"> 
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" readonly class="custom-control-input" id="customCheck11" >
                    <a href="<?php echo base_url; ?>/SetupWizard.php" class=" active custom-control-label" for="customCheck1" style="padding: 0 20px;">Company Information</a>
                  </div>
                </div>    

                <?php
              }
              ?>
              <?php

              if($pdata)
                { ?>

                  <div class="col-md-2" style="float: left;"> 
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" readonly checked class="custom-control-input" id="customCheck12">
                      <a href="<?php echo base_url; ?>/paymentsetup.php?cmpinfo=1" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Payment Setup</a>
                    </div>
                  </div>

                  <?php
                }
                else
                {
                  ?>  

                  <div class="col-md-2" style="float: left;"> 
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" readonly  class="custom-control-input" id="customCheck12">
                      <a href="<?php echo base_url; ?>/paymentsetup.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Payment Setup</a>
                    </div>
                  </div>

                  <?php
                }
                ?>
                
                <?php
                if($catdata)
                  { ?>

                    <div class="col-md-2" style="float: left;"> 
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" readonly checked class="custom-control-input" id="customCheck13">
                        <a href="<?php echo base_url; ?>/AllCategory.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Category</a>
                      </div>
                    </div>

                    <?php
                  }
                  else
                  {
                    ?>  

                    <div class="col-md-2" style="float: left;"> 
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" readonly class="custom-control-input" id="customCheck13">
                        <a href="<?php echo base_url; ?>/AllCategory.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Category</a>
                      </div>
                    </div>

                    <?php
                  }
                  ?>

                  <?php
                  if($empdata)
                    { ?>

                      <div class="col-md-2" style="float: left;"> 
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" readonly checked class="custom-control-input" id="customCheck14">
                          <a href="<?php echo base_url; ?>/AllEmployees.php?cmpinfo=1" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Employee</a>
                        </div>
                      </div>

                      <?php
                    }
                    else
                    {
                      ?>  

                      <div class="col-md-2" style="float: left;"> 
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" readonly class="custom-control-input" id="customCheck14">
                          <a href="<?php echo base_url; ?>/AllEmployees.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Employee</a>
                        </div>
                      </div>

                      <?php
                    }
                    ?>  

                    <?php
                    if($smsdata)
                      { ?>

                        <div class="col-md-2" style="float: left;"> 
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" readonly checked class="custom-control-input" id="customCheck15">
                            <a href="<?php echo base_url; ?>/SmsSendSetting.php?cmpinfo=1" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Sms Setting</a>
                          </div>
                        </div>

                        <?php
                      }
                      else
                      {
                        ?>  

                        <div class="col-md-2" style="float: left;"> 
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" readonly class="custom-control-input" id="customCheck15">
                            <a href="<?php echo base_url; ?>/SmsSendSetting.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Sms Setting</a>
                          </div>
                        </div>

                        <?php
                      }
                      ?>  

                      <?php
                      if($emaildata)
                        { ?>

                          <div class="col-md-2" style="float: left;"> 
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox"  readonly checked class="custom-control-input" id="customCheck16">
                              <a href="#" class="custom-control-label" for="customCheck1" style="padding: 0 20px;color: #fa744d;">Email Setting</a>
                            </div>
                          </div>

                          <?php
                        }
                        else
                        {
                          ?>  

                          <div class="col-md-2" style="float: left;"> 
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" readonly  class="custom-control-input" id="customCheck16">
                              <a href="<?php echo base_url; ?>/EmailSendSetting.php" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Email Setting</a>
                            </div>
                          </div>

                          <?php
                        }
                        ?>  




                      </div>
                      <?php

                    }
                    ?>
                    <div class="card-body">
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"> 
                          <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                            <span class="hidden-sm-up"><i class="fa fa-cog"></i></span> <span class="hidden-xs-down">Gmail</span>
                          </a> 
                        </li>
                        <li class="nav-item"> 
                          <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                           <span class="hidden-sm-up"><i class="fa fa-picture-o"></i></span> <span class="hidden-xs-down">Custom</span>
                         </a> 
                       </li>
                     </ul>
                     <!-- Tab panes -->
                     <div class="tab-content">
                      <div class="tab-pane active" id="home2" role="tabpanel">
                        <div class="p-20">
                          <?php 
                          if(!empty($Gmail_url))
                          {
                            ?>
                            <div class="col-lg-4 col-sm-6 col-xs-12 googleloginLayout">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <center>
                                    <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="//doxhze3l6s7v9.cloudfront.net/app/static//img/icons/google-mail-sync.png">
                                  </center>
                                </div>
                                <div class="panel-body">
                                  <div class="panel-desc m-b-sm" style="text-align:center;">
                                    <span><img  class="img-circle" src="<?php echo $Gmail_url?>"     width="85px" ></span>
                                    <div class="m-l-xs m-t-15">
                                      <span class="block m-t-sm"><?php echo $Gmail_displayName?></span><br>
                                      <span class="block"><?php echo $Gmail_value?></span>
                                    </div>
                                  </div>
                                  <center>
                                    <button type="submit" class="btn waves-effect waves-light btn-danger m-r-10" id="gmail_disable"> <?php echo $B2; ?></button> 
                                  </center>
                                </div>
                              </div>
                            </div>
                            <?
                          }
                          else
                          {
                            ?>
                            <div class="col-lg-4 col-sm-6 col-xs-12">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <center>
                                    <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="//doxhze3l6s7v9.cloudfront.net/app/static//img/icons/google-mail-sync.png">
                                  </center>
                                </div>
                                <div class="panel-body">
                                  <div class="panel-desc">
                                    <div class="m-b-sm">
                                      See all emails related to a contact from your Gmail account.
                                    </div>
                                  </div>
                                  <center>
                                    <a href="<?= 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>" class="btn btn-info"> <?php echo $B1; ?></a>
                                  </center>
                                </div>
                              </div>
                            </div>
                            <?php
                          }
                          ?>
                        </div>
                      </div>
                      <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                        <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                         <input type="hidden" name="id" id="id" value="<?php echo $myevent; ?>">
                         <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                         <div class="form-group">
                          <label>From Email *</label>
                          <input type="text" name="fmail" id="fmail" class="form-control" placeholder="from Mail" value="<?php echo @$fmail;?>" maxlength="30">
                        </div>
                        <div class="form-group">
                          <label>From Name *</label>
                          <input type="text" name="fname" id="fname" class="form-control" placeholder="from Name" value="<?php echo @$fname;?>"  maxlength="20">
                        </div>
                        <div class="form-group">
                          <label>SMTP Host *</label>
                          <input type="text" name="smtphost" id="smtphost" class="form-control" placeholder="abc.host.com" value="<?php echo @$smtphost;?>">
                        </div>
                        <div class="form-group">
                          <label>Type Of Encription *</label>
                          <select class="form-control" id="toe" name="toe">
                            <?php 
                            if(!empty($toe))
                              {?>
                                <option selected value="<?php echo $toe ?>"><?php echo $toe; ?></option>    
                                <?php
                              }
                              ?>
                              <option value="">Select Type Of Encription</option>
                              <option value="None">None</option>
                              <option value="SSL">SSL</option>
                              <option value="TLS">TLS</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>SMTP Port *</label>
                            <input type="text" name="smtpport" id="smtpport" class="form-control" placeholder="SMTP Port" value="<?php echo @$smtpport;?>">
                          </div>
                          <div class="form-group">
                            <label>SMTP Authentication *</label>
                            <select class="form-control" id="sa" name="sa">
                              <?php 
                              if(!empty($sa))
                                {?>
                                  <option selected value="<?php echo $sa ?>"><?php echo $sa; ?></option>    
                                  <?php
                                }
                                ?>
                                <option value="">Select a Authentication</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>SMTP UserName *</label>
                              <input type="text" name="smtpusername" id="smtpusername" class="form-control" placeholder="SMTP UserName" value="<?php echo @$smtpusername;?>"  maxlength="20">
                            </div>
                            <div class="form-group">
                              <label>SMTP Password *</label>
                              <input type="Password" name="smtppassword" id="smtppassword" class="form-control" placeholder="SMTP Password" value="<?php echo @$smtppassword;?>">
                            </div>
                            <div class="modal"></div>
                            <div class="form-group">
                              <?php
                              if(isset($_GET["id"]))
                              {
                                ?>
                                <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Update</button>
                                <?php       
                              }
                              else
                              {
                                ?>
                                <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Submit</button>
                                <?php
                              }
                              ?>
                              <button type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> Cancel</button>
                            </div>
                          </form>
                          <div class="col-lg-12 col-md-12">
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
        fmail: {required: "Please enter from mail"},
        fname: {required: "Please enter from name "},
        smtphost: {required: "Please enter smtp host "},
        toe: {required: "Please enter to mail "},
        smtpport: {required: "Please enter smtp port "},
        sa: {required: "Please enter  SMTP Authentication"},
        smtpusername: {required: "Please enter SMTP username"},
        smtppassword: {required: "Please enter SMTP password "},
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
      $(".modal").show();
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
          $(".modal").hide();
          setTimeout(function () { window.location.reload(); }, 5000)
        }
        else if(data.error)
        {
          $("#error").show();
          $('#errormsg').html('<span>'+data.error+'</span>');
          $(".modal").hide();
                // alert('<li>'+data.error+'</li>');
              }
            }   
          });
    }           
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
          $(".modal").hide();
          setTimeout(function () { window.location.reload(); }, 1000)
        }
        else if(data.error)
        {
          $("#error").show();
          $('#errormsg').html('<span>'+data.error+'</span>');
          $(".modal").hide();
          alert('<li>'+data.error+'</li>');
        }
      }   
    });
    });
    $('#fname').on('keypress', function (event) {
      var regex = new RegExp("^[a-zA-Z0-9]+$");
      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
      if (!regex.test(key)) {
       event.preventDefault();
       return false;
     }
   });
    $('#smtpusername').on('keypress', function (event) {
      var regex = new RegExp("^[a-zA-Z0-9]+$");
      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
      if (!regex.test(key)) {
       event.preventDefault();
       return false;
     }
   });

 </script>
</body>
</html>