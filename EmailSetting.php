<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}


if(isset($_GET['get_data'])){
    $stmt= $db->prepare("SELECT * FROM `AdminEmailSetting`"); 
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo json_encode(['response'=>$result]);die;
}

if(isset($_GET['update'])){

    if(!empty($_POST['emailname'])){

        if($_POST['emailname']=='sendForgetMail' || $_POST['emailname']=='sendCMail' || $_POST['emailname']=='AdminDefault'){

            if(empty($_POST['smtpport']) || empty($_POST['hostname']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['senderemail']) || empty($_POST['sendername']) || empty($_POST['replyemail']) || empty($_POST['replyname']) || empty($_POST['templatename']) ){
                echo json_encode(['error'=>'Empty Field']);die;
            }

            $stmt= $db->prepare("UPDATE `AdminEmailSetting` SET `smtpport`=:smtpport,`hostname`=:hostname,`username`=:username,`password`=:password,`senderemail`=:senderemail,`sendername`=:sendername,`replyemail`=:replyemail,`replyname`=:replyname,`bccemail`=:bccemail,`bccname`=:bccname,`templatename`=:templatename,`templateother`=:templateother WHERE `emailname`=:emailname"); 
            $stmt->bindParam(":smtpport",$_POST['smtpport']);
            $stmt->bindParam(":hostname",$_POST['hostname']);
            $stmt->bindParam(":username",$_POST['username']);
            $stmt->bindParam(":password",$_POST['password']);
            $stmt->bindParam(":senderemail",$_POST['senderemail']);
            $stmt->bindParam(":sendername",$_POST['sendername']);
            $stmt->bindParam(":replyemail",$_POST['replyemail']);
            $stmt->bindParam(":replyname",$_POST['replyname']);
            $stmt->bindParam(":bccemail",$_POST['bccemail']);
            $stmt->bindParam(":bccname",$_POST['bccname']);
            $stmt->bindParam(":templatename",$_POST['templatename']);
            $stmt->bindParam(":templateother",$_POST['templateother']);
            $stmt->bindParam(":emailname",$_POST['emailname']);
            $stmt->execute();
            echo json_encode(['response'=>'Successfully Updated.']);die;
        }else{
          $stmt= $db->prepare("UPDATE `AdminEmailSetting` SET `bccemail`=:bccemail,`bccname`=:bccname,`templatename`=:templatename WHERE `emailname`=:emailname"); 
          $stmt->bindParam(":bccemail",$_POST['bccemail']);
          $stmt->bindParam(":bccname",$_POST['bccname']);
          $stmt->bindParam(":templatename",$_POST['templatename']);
          $stmt->bindParam(":emailname",$_POST['emailname']);
          $stmt->execute();
          echo json_encode(['response'=>'Successfully Updated.']); die;
      }
  }else{
    echo json_encode(['error'=>'Something went worng. Please try again.']);die;
}
die;

}


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style type="text/css">
    .ButtonTable{width: 100%}
    .saveButton,.orignalbutton{width: 15%; float: left; padding: 0 10px; }
    .orignalinptut{width: 20%;float: left; padding: 0 10px;}
    form .row {
        margin-bottom: 35px;
    }
    .icon{
        align-items: center;padding: 5px;background: aliceblue;border-radius: 5px 0 0 5px;
    }
</style>
<body class="skin-default fixed-layout mysunlessO">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure">
            </div>
            <p class="loader__label">
                <?php echo $_SESSION['UserName']; ?></p>
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
                            <h4 class="text-themecolor">
                                Email Setting
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                 <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                       <a class="nav-link active" data-toggle="tab" href="#Config" role="tab">
                                        Email Config
                                    </a> 
                                </li>
                                <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#tempsocial" role="tab">
                                       Social Media Icon
                                   </a> 
                               </li>
                           </ul>

                           <div class="tab-content">
                            <div class="tab-pane" id="tempsocial" role="tabpanel">
                                <h3>Add Social Media links</h3>
                                <small>This will appear on bottom/footer of the mail.</small>
                                <form id="social_form">
                                    <div class="form-group">
                                        <label class="col-form-label" for="facebook_link">Facebook</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/facebook.png" alt="social media icon" title="facebook" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="facebook_link" name="facebook_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="twitter_link">Twitter</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/twitter.png" alt="social media icon" title="twitter" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="twitter_link" name="twitter_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="instagram_link">Instagram</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/instagram.png" alt="social media icon" title="instagram" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="instagram_link" name="instagram_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="googleplus_link">Google+</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/googleplus.png" alt="social media icon" title="googleplus" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="googleplus_link" name="googleplus_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="pinterest_link">Pinterest</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/pinterest.png" alt="social media icon" title="pinterest" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="pinterest_link" name="pinterest_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="youtube_link">Youtube</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/youtube.png" alt="social media icon" title="youtube" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="youtube_link" name="youtube_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="whatsapp_link">Whatsapp</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/whatsapp.png" alt="social media icon" title="whatsapp" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="whatsapp_link" name="whatsapp_link">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="other_link">Other</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend icon">
                                                <img src="<?= base_url?>/assets/icons/other.png" alt="social media icon" title="other" width="35" height="35">
                                            </div>
                                            <input type="text" class="form-control" id="other_link" name="other_link">
                                        </div>
                                    </div>
                                </form>
                                <button id="form_submit" class="btn btn-info">Submit</button>
                            </div>

                            <div class="tab-pane active" id="Config" role="tabpanel">

                                <ul class="nav nav-tabs customtab" role="tablist">
                                  <li class="nav-item">
                                       <a class="nav-link active" data-toggle="tab" href="#AdminDefaultEmail" role="tab">
                                        Admin's Default Email
                                    </a> 
                                </li>
                                    <li class="nav-item">
                                       <a class="nav-link" data-toggle="tab" href="#RegistrationEmail" role="tab">
                                        Register Email
                                    </a> 
                                </li>
                                <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#sendForget" role="tab">
                                       Reset Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#EventMail" role="tab">
                                       Event Email
                                   </a> 
                               </li>


                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#EventMailForcanl" role="tab">
                                       Cancel Event Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#EventRemingMail" role="tab">
                                       Reminder Event Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#Invoice" role="tab">
                                       Invoice Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#smpleMail" role="tab">
                                       Compose Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#smpleMailReport" role="tab">
                                       Report Email
                                   </a> 
                               </li>

                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#Enquiry" role="tab">
                                       FAQ Enquiry Email
                                   </a> 
                               </li>
                               <li class="nav-item">
                                   <a class="nav-link" data-toggle="tab" href="#SubscriptionEmail" role="tab">
                                       Subscription Email
                                   </a> 
                               </li>

                           </ul>

                           <div class="col-lg-12">
                            <div class="tab-content">
                              <div class="tab-pane active" id="AdminDefaultEmail" role="tabpanel">
                                    <small>This email is used as default email.</small>
                                    <div class="p-20">
                                        <div class="Loader"></div>
                                        <form class="form-horizontal admin_form" id="AdminDefault" method="post">
                                            <input type="hidden" name="emailname" value="AdminDefault">
                                            <div class="row">
                                             <div class="col-md-2">
                                                 <label>SMTP PORT</label>
                                                 <input type="number" class="form-control" name="smtpport" placeholder="465" readonly="">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>HOST NAME</label>
                                                 <input type="text" class="form-control" name="hostname" placeholder="mysunless.com">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>USERNAME</label>
                                                 <input type="text" class="form-control" name="username">
                                             </div>
                                             <div class="col-md-4">
                                                 <label>PASSWORD</label>
                                                 <input type="text" class="form-control" name="password">
                                             </div>
                                         </div>

                                         <div class="row">
                                           <div class="col-md-3">
                                             <label>SENDER EMAIL</label>
                                             <input type="text" class="form-control" name="senderemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>SENDER NAME</label>
                                             <input type="text" class="form-control" name="sendername" placeholder="MySunless">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY EMAIL</label>
                                             <input type="text" class="form-control" name="replyemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY NAME</label>
                                             <input type="text" class="form-control" name="replyname" placeholder="MySunless">
                                         </div>
                                     </div>

                                     <div class="row">
                                       <div class="col-md-3">
                                         <label>BCC EMAIL</label>
                                         <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
                                     </div>
                                     <div class="col-md-3">
                                         <label>BCC NAME</label>
                                         <input type="text" class="form-control" name="bccname" placeholder="MySunless">
                                     </div>
                                 </div>

                                 <div class="row">
                                   <div class="col-md-3">
                                     <label>TEMPLATE NAME</label>
                                     <input type="text" class="form-control" name="templatename" value="default-email.php" readonly="">
                                 </div>
                             </div>

                             <div class="form-group">
                                <input type="submit" name="submit" value="Save" class="submit btn btn-info">
                            </div>

                        </form>

                    </div>
                </div>
                                <div class="tab-pane" id="RegistrationEmail" role="tabpanel">
                                    <small>This email is used for Registration.</small>
                                    <div class="p-20">
                                        <div class="Loader"></div>
                                        <form class="form-horizontal admin_form" id="sendCMail" method="post">
                                            <input type="hidden" name="emailname" value="sendCMail">
                                            <div class="row">
                                             <div class="col-md-2">
                                                 <label>SMTP PORT</label>
                                                 <input type="number" class="form-control" name="smtpport" placeholder="465" readonly="">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>HOST NAME</label>
                                                 <input type="text" class="form-control" name="hostname" placeholder="mysunless.com">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>USERNAME</label>
                                                 <input type="text" class="form-control" name="username">
                                             </div>
                                             <div class="col-md-4">
                                                 <label>PASSWORD</label>
                                                 <input type="text" class="form-control" name="password">
                                             </div>
                                         </div>

                                         <div class="row">
                                           <div class="col-md-3">
                                             <label>SENDER EMAIL</label>
                                             <input type="text" class="form-control" name="senderemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>SENDER NAME</label>
                                             <input type="text" class="form-control" name="sendername" placeholder="MySunless">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY EMAIL</label>
                                             <input type="text" class="form-control" name="replyemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY NAME</label>
                                             <input type="text" class="form-control" name="replyname" placeholder="MySunless">
                                         </div>
                                     </div>

                                     <div class="row">
                                       <div class="col-md-3">
                                         <label>BCC EMAIL</label>
                                         <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
                                     </div>
                                     <div class="col-md-3">
                                         <label>BCC NAME</label>
                                         <input type="text" class="form-control" name="bccname" placeholder="MySunless">
                                     </div>
                                 </div>

                                 <div class="row">
                                   <div class="col-md-3">
                                     <label>TEMPLATE NAME</label>
                                     <input type="text" class="form-control" name="templatename" value="comapny-register.php" readonly="">
                                 </div>
                             </div>

                             <div class="form-group">
                                <input type="submit" name="submit" value="Save" class="submit btn btn-info">
                            </div>

                        </form>

                    </div>
                </div>

                <div class="tab-pane" id="sendForget" role="tabpanel">
                    <small>This email is used for Reset Password.</small>
                    <div class="p-20">
                        <form class="form-horizontal admin_form" id="sendForgetMail" method="post">
                            <input type="hidden" name="emailname" value="sendForgetMail">
                            <div class="row">
                             <div class="col-md-2">
                                 <label>SMTP PORT</label>
                                 <input type="number" class="form-control" name="smtpport" placeholder="465" readonly="">
                             </div>
                             <div class="col-md-3">
                                 <label>HOST NAME</label>
                                 <input type="text" class="form-control" name="hostname" placeholder="mysunless.com">
                             </div>
                             <div class="col-md-3">
                                 <label>USERNAME</label>
                                 <input type="text" class="form-control" name="username">
                             </div>
                             <div class="col-md-4">
                                 <label>PASSWORD</label>
                                 <input type="text" class="form-control" name="password">
                             </div>
                         </div>

                         <div class="row">
                           <div class="col-md-3">
                             <label>SENDER EMAIL</label>
                             <input type="text" class="form-control" name="senderemail" placeholder="test@mysunless.com">
                         </div>
                         <div class="col-md-3">
                             <label>SENDER NAME</label>
                             <input type="text" class="form-control" name="sendername" placeholder="MySunless">
                         </div>
                         <div class="col-md-3">
                             <label>REPLY EMAIL</label>
                             <input type="text" class="form-control" name="replyemail" placeholder="test@mysunless.com">
                         </div>
                         <div class="col-md-3">
                             <label>REPLY NAME</label>
                             <input type="text" class="form-control" name="replyname" placeholder="MySunless">
                         </div>
                     </div>

                     <div class="row">
                       <div class="col-md-3">
                         <label>BCC EMAIL</label>
                         <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
                     </div>
                     <div class="col-md-3">
                         <label>BCC NAME</label>
                         <input type="text" class="form-control" name="bccname" placeholder="MySunless">
                     </div>
                 </div>

                 <div class="row">
                   <div class="col-md-3">
                     <label>TEMPLATE NAME</label>
                     <input type="text" class="form-control" name="templatename" value="comapny-register.php" readonly="">
                 </div>
             </div>

             <div class="form-group">
                <input type="submit" name="submit" value="Save" class="submit btn btn-info">
            </div>
        </form>
    </div>
</div>
<div class="tab-pane" id="Invoice" role="tabpanel">
    <small>This email is used for Sending Order's Invoice.</small>
    <div class="p-20">
        <form class="form-horizontal" id="SendInvoice" method="post">
            <input type="hidden" name="emailname" value="SendInvoice">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="Order.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>
<div class="tab-pane" id="EventMail" role="tabpanel">
    <small>This email is used for booking an Appointment.</small>
    <div class="p-20">
        <form class="form-horizontal" id="sendEventMail" method="post">
            <input type="hidden" name="emailname" value="sendEventMail">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="Event.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

<div class="tab-pane" id="EventMailForcanl" role="tabpanel">
    <small>This email is used for cancel an Appointment.</small>
    <div class="p-20">
        <form class="form-horizontal" id="sendEventMailForcanl" method="post">
            <input type="hidden" name="emailname" value="sendEventMailForcanl">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="EventCan.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

<div class="tab-pane" id="EventRemingMail" role="tabpanel">
    <small>This email is used for Appointment remainder.</small>
    <div class="p-20">
        <form class="form-horizontal" id="sendEventRemingMail" method="post">
            <input type="hidden" name="emailname" value="sendEventRemingMail">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="Event_Rminder.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

<div class="tab-pane" id="smpleMail" role="tabpanel">
    <small>This email is used for Compose Email by the User.</small>
    <div class="p-20">
        <form class="form-horizontal" id="sendsmpleMail" method="post">
            <input type="hidden" name="emailname" value="sendsmpleMail">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="sample.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

<div class="tab-pane" id="smpleMailReport" role="tabpanel">
    <small>This email is used for Report Bug.</small>
    <div class="p-20">
        <form class="form-horizontal" id="sendsmpleMailReport" method="post">
            <input type="hidden" name="emailname" value="sendsmpleMailReport">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="Report.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

<div class="tab-pane" id="Enquiry" role="tabpanel">
    <small>This email is used for Enquiry by the User.</small>
    <div class="p-20">
        <form class="form-horizontal" id="Enquirymail" method="post">
            <input type="hidden" name="emailname" value="Enquirymail">
            <div class="row">
               <div class="col-md-3">
                 <label>BCC EMAIL</label>
                 <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
             </div>
             <div class="col-md-3">
                 <label>BCC NAME</label>
                 <input type="text" class="form-control" name="bccname" placeholder="MySunless">
             </div>
         </div>
         <div class="row">
           <div class="col-md-3">
             <label>TEMPLATE NAME</label>
             <input type="text" class="form-control" name="templatename" value="Enquiry.php" readonly="">
         </div>
     </div>
     <div class="form-group">
        <input type="submit" name="submit" value="Save" class="submit btn btn-info">
    </div>
</form>
</div>
</div>

 <div class="tab-pane" id="SubscriptionEmail" role="tabpanel">
                                    <small>This email is used for sending package email.</small>
                                    <div class="p-20">
                                        <div class="Loader"></div>
                                        <form class="form-horizontal admin_form" id="sendSubscriptionEmail" method="post">
                                            <input type="hidden" name="emailname" value="sendSubscriptionEmail">
                                            <div class="row">
                                             <div class="col-md-2">
                                                 <label>SMTP PORT</label>
                                                 <input type="number" class="form-control" name="smtpport" placeholder="465" readonly="">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>HOST NAME</label>
                                                 <input type="text" class="form-control" name="hostname" placeholder="mysunless.com">
                                             </div>
                                             <div class="col-md-3">
                                                 <label>USERNAME</label>
                                                 <input type="text" class="form-control" name="username">
                                             </div>
                                             <div class="col-md-4">
                                                 <label>PASSWORD</label>
                                                 <input type="text" class="form-control" name="password">
                                             </div>
                                         </div>

                                         <div class="row">
                                           <div class="col-md-3">
                                             <label>SENDER EMAIL</label>
                                             <input type="text" class="form-control" name="senderemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>SENDER NAME</label>
                                             <input type="text" class="form-control" name="sendername" placeholder="MySunless">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY EMAIL</label>
                                             <input type="text" class="form-control" name="replyemail" placeholder="test@mysunless.com">
                                         </div>
                                         <div class="col-md-3">
                                             <label>REPLY NAME</label>
                                             <input type="text" class="form-control" name="replyname" placeholder="MySunless">
                                         </div>
                                     </div>

                                     <div class="row">
                                       <div class="col-md-3">
                                         <label>BCC EMAIL</label>
                                         <input type="text" class="form-control" name="bccemail" placeholder="test@mysunless.com">
                                     </div>
                                     <div class="col-md-3">
                                         <label>BCC NAME</label>
                                         <input type="text" class="form-control" name="bccname" placeholder="MySunless">
                                     </div>
                                 </div>

                                 <div class="row">
                                   <div class="col-md-3">
                                     <label>TEMPLATE NAME</label>
                                     <input type="text" class="form-control" name="templatename" value="Subscription.php" readonly="">
                                 </div>
                             </div>

                             <div class="form-group">
                                <input type="submit" name="submit" value="Save" class="submit btn btn-info">
                            </div>

                        </form>

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

<script type="text/javascript">
    $(document).ready(function() {

        jQuery.ajax({
            dataType:"json",
            url:'<?php echo EXEC; ?>Exec_Edit_EmailTemp.php?social_icon_data',
            success: function(data)
            {
                if(data.response){
                    var data = JSON.parse(data.response);
                    for(i in data){
                        var icon = data[i];
                        var id = Object.keys(icon);
                        var value = icon[id];
                        $("#"+id).val(value);
                    }
                }
            }
        });

        $("#form_submit").click(function(e){
            e.preventDefault();
            var data=[];var obj={};
            $("#social_form input").each(function(){
                var value = $(this).val();
                var key = $(this).attr('id');
                if(value){
                    obj={};
                    obj[key]=value;
                    data.push(obj);
                }
            });
            
            $(".Loader").show();
            jQuery.ajax({
                dataType:"json",
                type:"post",
                data:{data:JSON.stringify(data)},
                url:'<?php echo EXEC; ?>Exec_Edit_EmailTemp.php?social_icon',
                success: function(data)
                {
                    $(".Loader").hide();
                }
            });
        });

        $.ajax({
           dataType:"json",
           url:'?get_data',
           success: function(data)
           {
            if(data.response)
            {
                for(var i=0;i<data.response.length;i++){
                    var result = data.response[i];
                    for(key in result){
                        $("#"+result.emailname).find("input[name='"+key+"']").val(result[key]);
                    }
                }
            }
        }
    });

        $(".submit").click(function(e){
            e.preventDefault();

            var form = $(this).parents('form');
            form.validate({
                rules: {                
                    smtpport: "required",
                    hostname: "required",
                    username: "required",
                    password: "required",
                    senderemail: "required",
                    sendername: "required",
                    replyemail: "required",
                    replyname: "required",
                },
            });
            if(form.valid()===false){
                return false;
            }
            $(".Loader").show();
            var form =$(this).parents('form')[0];;
            var data = new FormData(form);

            jQuery.ajax({
               dataType:"json",
               type:"post",
               data:data,
               contentType: false, 
               processData: false,
               url:'?update',
               success: function(data)
               {
                if(data.response)
                {
                   swal("",data.response,"success")
               }
               else if(data.error)
               {
                   swal("",data.error,"error")
               }
               $(".Loader").hide();
           }
       });

        });


    });
</script>

</body>
</html>