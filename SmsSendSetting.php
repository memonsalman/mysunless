<?php 
require_once('function.php');
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/gmail/settings.php');

if(empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

if(isset($_SESSION['UserID']))
{
 $id=$_SESSION['UserID'];
 $stmt= $db->prepare("SELECT * FROM `smssetting` WHERE UserID=:id"); 
 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 $stmt->execute();
 $result = $stmt->fetch(PDO::FETCH_ASSOC);
 
 @$newid= $result['id'];
 @$sid2=$result['sid'];
 @$token=$result['token'];
 @$Twillo_from=$result['Twillo_from'];
 $olduserid=$result['UserID'];
 
}
if(!empty($olduserid))
{
  @$myevent = $newid;
}
else
{
  @$myevent = "new";
}

if(isset($_POST['disbleid']))
{   
  $id=$_POST["disbleid"];
  $query = $db->prepare("DELETE FROM smssetting WHERE UserID=:id");
  $query->bindValue(':id',$id, PDO::PARAM_STR); 
  $query->execute();
  if($query)
  {
    echo  json_encode(["resonse"=>'Your Twillo Account Successfully Disable']);die;

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

$statement= $db->prepare("SELECT countries_isd_code FROM `countries`");
$statement->bindParam(':Country', $country, PDO::PARAM_STR, 12);
$statement->execute();
$code_result = $statement->fetchAll(PDO::FETCH_ASSOC);




$button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C92'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$B4=$all_button4['button_name'];

$button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C93'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$B5=$all_button5['button_name'];

$titlei2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='26'"); 
$titlei2->execute();
$all_titlei2 = $titlei2->fetch(PDO::FETCH_ASSOC);
$Ti2=$all_titlei2['TitleName'];

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style>

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
  .nuimvber{width: 100%;}
  input#Twillo_from{width: 78%!important;}
  select#tnex{width: 20%!important;}
</style>
<body class="skin-default fixed-layout mysunlessA4">
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
            <h4 class="text-themecolor"><?php echo $Ti2; ?></h4>
            <?php       
          }
          else
          {
            ?>
            <h4 class="text-themecolor"><?php echo $Ti2; ?></h4> 
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
        <!-- <?php

        
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
       ?>   --> 
       <div  class="col-md-12">
        <div class="card">
         <?php
         if(@$_REQUEST['cmpinfo'] == 1)
         {
          ?>
          <div style="margin-top: 30px;"  class="col-md-12">


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
                        <a href="#" class="custom-control-label" for="customCheck1" style="padding: 0 20px;color: #fa744d;">Sms Setting</a>
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
                          <a href="<?php echo base_url; ?>/EmailSendSetting.php?cmpinfo=1" class="custom-control-label" for="customCheck1" style="padding: 0 20px;">Email Setting</a>
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
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="fa fa-cog"></i></span> <span class="hidden-xs-down">Twilio</span></a> </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div class="tab-pane active" id="home2" role="tabpanel">
                      <div class="p-20">


                        <?php 

                        
                        if(!empty($sid2))
                        {
                          ?>
                          <div class="col-lg-4 col-sm-6 col-xs-12">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <center>
                                  <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="<?php echo base_url; ?>/assets/images/twilio.png">
                                </center>
                              </div>
                              <div class="panel-body">
                                <div class="panel-desc m-b-sm">
                                  <div class="pull-left m-l-xs ">
                                    <span class="block m-t-sm"><?php echo $sid2?></span><br>
                                    <span class="block"><?php echo $token?></span><br>
                                    <span class="block"><?php echo $Twillo_from?></span><br>
                                  </div>
                                </div>
                                <center>
                                  <button type="submit" class="btn waves-effect waves-light btn-danger m-r-10" id="gmail_disable"><i class="fa fa-sign-out"></i> <?php echo $B5; ?></button> 
                                </center>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
                            <div class="alert alert-success" id="resonse" style="display: none;">
                              <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                              <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                            </div>
                            <div class="alert alert-danger" id="error" style="display: none;">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                              <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                            </div>
                          </div>
                          <?php
                        }
                        else
                        {
                          ?>
                          <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                            <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                            <div class="col-lg-4 col-sm-6 col-xs-12">
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <center>
                                    <img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="<?php echo base_url ?>/assets/images/twilio.png">
                                  </center>
                                </div>
                                <div class="panel-body">
                                  <div class="panel-desc">
                                    <div class="m-b-sm">
                                      <input type="hidden" name="id" id="id" value="<?php echo $myevent; ?>">
                                      <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                      <div class="form-group">
                                        <label>Sid *</label>
                                        <input type="text" name="sid" id="sid" class="form-control" placeholder="Sid" value="<?php echo @$sid;?>">
                                      </div>
                                      <div class="form-group">
                                        <label>Token *</label>
                                        <input type="text" name="token" id="token" class="form-control" placeholder="Tokan" value="<?php echo @$token;?>">
                                      </div>
                                      <div class="form-group">
                                        <label>Twilio Number *</label>
                                        <div class="nuimvber">
                                          <select name="tnex" id="tnex" class="form-control">
                                           <option value="">Ex</option>
                                           <?php
                                           foreach($code_result as $row) 
                                           {
                                            ?>
                                            <option value="<?php echo $row['countries_isd_code']; ?>"><?php echo $row['countries_isd_code']; ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>  
                                        <input type="text" name="Twillo_from" id="Twillo_from" class="form-control" placeholder="(123) 456-7890" value="<?php echo @$Twillo_from;?>">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <center>
                                  <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> <?php echo $B4; ?></button>
                                </center>
                              </div>
                            </div>
                          </div>
                        </form>
                        <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
                          <div class="alert alert-success" id="resonse" style="display: none;">
                            <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
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
                        <?php
                      }
                      ?>
                    </div>
                  </div>
                  <div class="Loader"></div>
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
    //sidebar drop down
    $("#companydrop").trigger("click");
    $('#Twillo_from').keyup(function(e){
      var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
            ph=ph;
          }else if(len<3){
            ph='('+ph;
          }else if(len==3){
            ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
            ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
            ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
            ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
        });
    $("#NewEvent").validate({
      rules: {                
       sid: {required: true,},
       token: {required: true,},
       tnex: {required: true,},
       Twillo_from: {required: true,},
     },
     messages: {             
      sid: {required: "Please enter sid "},
      token: {required: "Please enter tokan "},
      tnex: {required: "Please select extensions"},
      Twillo_from: {required: "Please enter your twillo number "},
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
     url:'<?php echo EXEC; ?>Exec_Edit_SmsSetting.php',
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
        setTimeout(function () { window.location.reload(); }, 3000)
      }
      else if(data.error)
      {
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
});
  });
</script>
<script type="text/javascript">
  $("#gmail_disable").click(function(){
    $(".Loader").show();
    var data = '<?php echo $_SESSION['UserID']; ?>';
    jQuery.ajax({
     dataType:"json",
     type:"post",
     data:{disbleid:data},
     url:'SmsSendSetting.php',
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
                            // setTimeout(function () { window.location.reload(); }, 1000);
                            location.reload();
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
</body>
</html>