<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/function.php");
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}//else{
//         if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) {
//             $CurrentStatus=0;
//             $db= new db();
//             $id=$_SESSION["UserID"] ;
//             $stmt=$db->prepare("UPDATE users set CurrentStatus=:CurrentStatus where id=:id");
//             $stmt->bindparam(":CurrentStatus",$CurrentStatus);
//             $stmt->bindparam(":id",$id);
//             $stmt->execute();
//             session_unset();     // unset $_SESSION variable for the run-time 
//             session_destroy();   // destroy session data in storage
//             header("Location: index.php");die;
//         }
//         $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
// }
$db3=new db();
$id=$_SESSION['UserID'];
$stmt= $db3->prepare("SELECT COUNT(*) FROM `clients` WHERE createdfk=:id"); 
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute(); 
$number_of_client = $stmt->fetchColumn(); 
$db4=new db();
$id=$_SESSION['UserID'];
$stmt= $db4->prepare("SELECT COUNT(*) FROM `event` WHERE UserID=:id"); 
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute(); 
$number_of_event = $stmt->fetchColumn(); 
$db5 = new db();
$id=$_SESSION['UserID'];
$todoQuery = $db5->prepare("SELECT * FROM `todo` WHERE createdfk=:id  ORDER BY dueDate ASC");
$todoQuery->bindParam(':id', $id, PDO::PARAM_INT);
$todoQuery->execute();
$number_of_todo = $todoQuery->rowCount();
// subscription expire progressbar
if(@$_SESSION['usertype'] == 'subscriber'){
    $db=new db();
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
$CountLimit  = $db5->prepare("SELECT * FROM `users` WHERE `id`=:id ");
$CountLimit->bindParam(':id', $id, PDO::PARAM_INT);
$CountLimit->execute();
$CountLimit = $CountLimit->fetch(PDO::FETCH_ASSOC);
$UsersLimit = $CountLimit['UsersLimit'];
$ClientsLimit = $CountLimit['ClientsLimit'];
$UserCreate = $CountLimit['UserCreate'];
$ClientCreate = $CountLimit['ClientCreate'];
$SchedulesCreate = $CountLimit['SchedulesCreate'];
$TodoCreate = $CountLimit['TodoCreate'];
$ServicesCreate = $CountLimit['ServicesCreate'];
$db8=new db();
$id=$_SESSION['UserID'];
$stmt8= $db8->prepare("SELECT * FROM `users` WHERE id=:id"); 
$stmt8->bindParam(':id', $id, PDO::PARAM_INT);
$stmt8->execute();
$result8 =$stmt8->fetchAll();
$loginuser=$result8[0]['usertype'];
$package= $db8->prepare("SELECT * FROM package ORDER BY `package`.`Price` ASC"); 
$package->bindParam(':id', $id, PDO::PARAM_INT);
$package->execute();
$listofpackage =$package->fetchAll();
$CountSubscriber= $db8->prepare("SELECT COUNT(*) FROM `users` WHERE usertype= 'subscriber' "); 
$CountSubscriber->execute(); 
$number_of_subscriber = $CountSubscriber->fetchColumn(); 
$CountPackage= $db8->prepare("SELECT COUNT(*) FROM `package` "); 
$CountPackage->execute(); 
$number_of_package = $CountPackage->fetchColumn();
if(isset($_POST['view'])){
    if($_POST["view"] != ''){
        $update_notify= $db3->prepare("UPDATE `users` SET `comment_status` = '1' WHERE `comment_status` ='0' "); 
        $update_notify->execute();
    }
    $stmt= $db3->prepare("SELECT * FROM `users` WHERE usertype= 'subscriber' ORDER BY id DESC LIMIT 5"); 
    $stmt->execute();
    $output = '';
    if($stmt->rowCount() > 0) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $output .= '<li class="test"><a href="AddSubuserid?action=edit&subuserid='.$row["id"].'"><strong>'.$row["username"].'</strong><br /></a></li>';
        }    
    }
    else{
        $output .= '<li><a href="#" class="text-bold text-italic">No Notification Found</a></li>';
    }
    $stmt2= $db3->prepare("SELECT * FROM `users` WHERE comment_status='0' AND usertype= 'subscriber' "); 
    $stmt2->execute();
    $count=$stmt2->rowCount();
    $data = array(
        'notification' => $output,
        'unseen_notification'  => $count
    );
    echo json_encode($data);
    die();
}
if(isset($_REQUEST['del_id']))
{
    $id=$_REQUEST['del_id'];      
    $id_data=implode(',',$id);
    $db=new db();
    $DeleteClient = $db->prepare("DELETE FROM `todo` WHERE id IN(:id_data)");
    $DeleteClient->bindValue(":id_data",$id_data,PDO::PARAM_INT);
    $DeleteClient->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link rel="stylesheet" href="
<?php echo base_url; ?>/assets/css/calendar.css">
    <link href="../assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
    <style type="text/css">
        div#cal-slide-box{
            background: #333!important;
        }
        .alleventlist {
            padding: 25px;
        }
        .eventlisttitle{
            background: #3cabe1;
            padding: 10px;
        }
        .eventlist{
            background: antiquewhite;
            padding: 10px;
        }
        .eventlist a{
            color: #333;
            text-decoration: none;
            text-transform: capitalize;
        }
        .d-flex.m-b-40.align-items-center.no-block{
            float: left;
        }
        div#showEventCalendar{
            clear: both;
        }
        .card-body.Activitie{
            overflow: scroll;
        }
        .card.todobloack{
            height: 318px;
        }
        .modal2{
            display:none;
            position:fixed;
            z-index:1000;
            top:0;
            left:0;
            height:100%;
            width:100%;
            background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;
        }
        body.loading .modal2{
            overflow: hidden;
        }
        body.loading .modal2{
            display: block;
        }
        .activedatea{
            width: 100%;
            background: white;
            padding: 10px 0;
            margin:10px 0;
            display: flex;
        }
        .activeuserimage img{
            border-radius: 50%;
        }
        .activeuserimage img {
            height: 25px!important;
            width: 25px!important;
        }
        .activeuserimage{
            width: 21%;
            float: left;
            text-align: center;
        }
        .activeuserdetial{
            width: 70%;
            float: left;
            padding-left: 6%;
        }
        label.rowslimits{
            display: none;
        }
        .badge.badge-pill.badge-primary {
            color: white;
        }
        .clearfix{
            clear: both;
        }
        .todobloack{
            width: 100%;
        }
        .dueDate.pull-right {
            width: 9%;
        }
        * {
            box-sizing: border-box;
        }
        .columns {
            float: left;
            width: 33.3%;
            padding: 8px;
        }
        .price {
            list-style-type: none;
            border: 1px solid #eee;
            margin: 0;
            padding: 0;
            -webkit-transition: 0.3s;
            transition: 0.3s;
        }
        .price:hover {
            box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
        }
        .price .header {
            background-color: #111;
            color: white;
            font-size: 25px;
        }
        .price li {
            border-bottom: 1px solid #eee;
            padding: 20px;
            text-align: center;
        }
        .price .grey {
            background-color: #eee;
            font-size: 20px;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 25px;
            text-align: center;
            text-decoration: none;
            font-size: 18px;
        }
        @media only screen and (max-width: 600px) {
            .columns {
                width: 100%;
            }
        }
        /*for show more/less content*/
        a.morelink {
            text-decoration:none;
            outline: none;
        }
        .morecontent span {
            display: none;
        }
        /* end show more/less content*/
        /*.topbar .top-navbar .profile-pic img{max-width: 30px !important;  max-height: 30px !important;   height: 30px !important;    width: 30px !important;}*/
    </style>
    <body class="skin-default fixed-layout">
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
                                Dashboard
                            </h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <!--  <ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">Dashboard 1</li>
</ol> -->
                                <?php 
if($loginuser!="Admin" AND $loginuser!="user") 
{ ?>
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-plus-circle">
                                    </i> Premium Benefits</button>
                                <!-- Modal -->
                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="myModalLabel">
                                                    Premium Benefits 
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php foreach($listofpackage as $row)
{
                                                ?>
                                                <div class="columns">
                                                    <ul class="price">
                                                        <li class="header">
                                                            <?php echo $row['PackageName']; ?></li>
                                                        <li class="grey">
                                                            $ 
                                                            <?php echo $row['Price']; ?>/Month
                                                        </li>
                                                        <li>
                                                            <?php echo $row['UsersLimit']; ?> Users
                                                        </li>
                                                        <li>
                                                            <?php echo $row['ClientsLimit']; ?> Clients
                                                        </li>
                                                        <li>
                                                            Unlimited Appointments
                                                        </li>
                                                        <li>
                                                            Unlimited Services
                                                        </li>
                                                        <li class="grey">
                                                            <button id="updatepackage" data-id="
<?php echo $row['id']; ?>" class="button">Upgrade</button>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <?php
}
                                                ?>
                                                <div class="modal2">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="alert alert-success" id="resonse" style="display: none;">
                            <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-success">
                                <i class="fa fa-check-circle">
                                </i>
                                Success
                            </h3>
                            <p id="resonsemsg">
                            </p>
                        </div>
                        <div class="alert alert-danger" id="error" style="display: none;">
                            <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                            <h3 class="text-danger">
                                <i class="fa fa-exclamation-circle">
                                </i>
                                Errors
                            </h3>
                            <p id="errormsg">
                            </p>
                        </div>
                        <label class="form-check-label">
                            <span class="text-danger align-middle" id="errormsg2"></span>
                        </label>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Info box -->
                    <!-- ============================================================== -->
                    <div class="card-group">
                        <?php
if(@$_SESSION['usertype']=="Admin")
{?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-screen-desktop">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TOTAL SUBSCRIBERS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-primary">
                                                    <?php echo @$number_of_subscriber; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
<?php echo $number_of_subscriber; ?>%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-screen-desktop">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TOTAL PACKAGE
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-primary">
                                                    <?php echo @$number_of_package; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
<?php echo $number_of_package; ?>%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
}
elseif($ClientCreate !== "1")
{ ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Client
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
    if ($SchedulesCreate !== "1")
    {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Appointments
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
    }else{
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-note">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    UPCOMING APPOINTMENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-cyan">
                                                    <?php echo $number_of_event; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 
<?php echo ($number_of_event * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
    }
    if(@$_SESSION['usertype'] == 'subscriber'){
                        ?>    
                        <!--  <div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="d-flex no-block align-items-center">
<div>
<h3><i class="icon-doc"></i></h3>
<p class="text-muted">SUBSCRIPTION EXPIRE<br><small>(in days)</small></p>
</div>
<div class="ml-auto">
<h2 class="counter text-purple">
<?php echo $remaining_days ; ?></h2>
</div>
</div>
</div>
<div class="col-12">
<div class="progress">
<div class="progress-bar bg-purple" role="progressbar" style="width:  
<?php echo ($remaining_days * 100 / 30 ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div>
</div> -->
                        <?php 
    }
    if ($TodoCreate !== "1")
    {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Todo
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
    }else{
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-bag">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TO DO LIST
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-success">
                                                    <?php echo $number_of_todo; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 
<?php echo ($number_of_todo * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
    }
                        ?>
                        <?php
}
elseif($SchedulesCreate !== "1")
{ ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-screen-desktop">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TOTAL CLIENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-primary">
                                                    <?php echo @$number_of_client; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <? if($ClientsLimit== "full"){$ClientsLimit== "100000" ;} ?>
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
<?php echo ($number_of_client * 100 / $ClientsLimit ); ?>%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Appointments
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
 if(@$_SESSION['usertype'] == 'subscriber'){
                        ?>
                        <!-- <div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="d-flex no-block align-items-center">
<div>
<h3><i class="icon-doc"></i></h3>
<p class="text-muted">SUBSCRIPTION EXPIRE<br><small>(in days)</small></p>
</div>
<div class="ml-auto">
<h2 class="counter text-purple">
<?php echo $remaining_days ; ?></h2>
</div>
</div>
</div>
<div class="col-12">
<div class="progress">
<div class="progress-bar bg-purple" role="progressbar" style="width: 
<?php echo ($remaining_days * 100 / 30 ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div>
</div> -->
                        <?php
 } 
 if ($TodoCreate !== "1")
 {
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Todo
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
 }else{
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-bag">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TO DO LIST
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-success">
                                                    <?php echo $number_of_todo; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 
<?php echo ($number_of_todo * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
 }
                        ?>
                        <?php
}
elseif($TodoCreate !== "1")
{ ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-screen-desktop">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TOTAL CLIENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-primary">
                                                    <?php echo @$number_of_client; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <? if($ClientsLimit== "full"){$ClientsLimit== "100000" ;} ?>
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
<?php echo ($number_of_client * 100 / $ClientsLimit ); ?>%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-note">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    UPCOMING APPOINTMENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-cyan">
                                                    <?php echo $number_of_event; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 
<?php echo ($number_of_event * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
 if(@$_SESSION['usertype'] == 'subscriber'){
                        ?>
                        <!-- <div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="d-flex no-block align-items-center">
<div>
<h3><i class="icon-doc"></i></h3>
<p class="text-muted">SUBSCRIPTION EXPIRE<br><small>(in days)</small></p>
</div>
<div class="ml-auto">
<h2 class="counter text-purple">
<?php echo $remaining_days ; ?></h2>
</div>
</div>
</div>
<div class="col-12">
<div class="progress">
<div class="progress-bar bg-purple" role="progressbar" style="width: 
<?php echo ($remaining_days * 100 / 30 ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div>
</div> -->
                        <?php 
 }
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>
                                            <i class="fa fa-ban text-danger">
                                            </i>
                                        </h3>
                                        <h4 class="text-danger">
                                            You have not permission to Add/View Todo
                                        </h4>
                                        <p class="text-success">
                                            For permission contact Admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
}
else
{
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-screen-desktop">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TOTAL CLIENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-primary">
                                                    <?php echo @$number_of_client; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <? if($ClientsLimit== "full"){$ClientsLimit== "100000" ;} ?>
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 
<?php echo ($number_of_client * 100 / $ClientsLimit ); ?>%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-note">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    UPCOMING APPOINTMENTS
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-cyan">
                                                    <?php echo $number_of_event; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-cyan" role="progressbar" style="width: 
<?php echo ($number_of_event * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
    if(@$_SESSION['usertype'] == 'subscriber'){
                        ?>
                        <!-- <div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-12">
<div class="d-flex no-block align-items-center">
<div>
<h3><i class="icon-doc"></i></h3>
<p class="text-muted">SUBSCRIPTION EXPIRE<br><small>(in days)</small></p>
</div>
<div class="ml-auto">
<h2 class="counter text-purple">
<?php echo $remaining_days ; ?></h2>
</div>
</div>
</div>
<div class="col-12">
<div class="progress">
<div class="progress-bar bg-purple" role="progressbar" style="width: 
<?php echo ($remaining_days * 100 / 30 ); ?>%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>
</div>
</div>
</div>
</div> -->
                        <?php 
    } 
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3>
                                                    <i class="icon-bag">
                                                    </i>
                                                </h3>
                                                <p class="text-muted">
                                                    TO DO LIST
                                                    <br>
                                                    <small style="visibility: hidden">(in days)</small>
                                                </p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-success">
                                                    <?php echo $number_of_todo; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 
<?php echo ($number_of_todo * 100 / $UsersLimit ); ?>%; height: 6px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php 
}
                        ?>
                        <!-- Column -->
                        <!-- Column -->
                        <!-- Column -->
                        <!-- Column -->
                        <!-- Column -->
                        <!-- Column -->
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Info box -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Over Visitor, Our income , slaes different and  sales prediction -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <!-- Column -->
                        <?php
if(@$_SESSION['usertype'] == "Admin" || $TodoCreate !== "1")
{?>
                        <div class="col-lg-12 col-md-12">
                            <? 
}else
{?>
                            <div class="col-lg-8 col-md-12">
                                <?php
}
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex m-b-40 align-items-center no-block">
                                            <div class="page-header">
                                                <h3>
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="pull-right form-inline">
                                            <div class="btn-group">
                                                <button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
                                                <button class="btn btn-default" data-calendar-nav="today">Today</button>
                                                <button class="btn btn-primary" data-calendar-nav="next">Next >></button>
                                            </div>
                                        </div>
                                        <div id="showEventCalendar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-lg-4 col-md-12">
                                <div class="row">
                                    <!-- Column -->
                                    <?php
if(@$_SESSION['usertype']!="Admin" && $TodoCreate == "1")
{?>
                                    <div class="card todobloack">
                                        <div class="card-body todolist">
                                            <button name="deleteTags" class="pull-right btn btn-circle btn-danger " title ="Delete" id="deleteTags"><i class="fa fa-trash">
                                                </i></button>
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h5 class="card-title m-b-0">
                                                        TO DO LIST
                                                    </h5>
                                                </div>
                                            </div>
                                            <!-- ============================================================== -->
                                            <!-- To do list widgets -->
                                            <!-- ============================================================== -->
                                            <div class="to-do-widget m-t-20">
                                                <div class="list-task todo-list list-group m-b-0" data-role="tasklist">
                                                    <?php
    if ($todoQuery->rowCount()>0){
                                                    ?>      
                                                    <div class='todolist' data-role='task'>
                                                        <?php 
        while($row = $todoQuery->fetchObject()) {
            $today=date("d-m-Y");
            $dueDate=$row->dueDate;
            $newDate = date("M d,Y", strtotime($dueDate));
            $dueDate=substr($dueDate, 0, 10);
                                                        ?>                
                                                        <div>
                                                            <div class="dueDate pull-right">
                                                                <span style="color:white" class='
<?php echo (strtotime($dueDate) <= strtotime($today) ? 'badge badge-pill badge-danger' : 'badge badge-pill badge-primary') ; ?>'> 
                                                                    <?php echo $newDate ; ?> </span>
                                                            </div>
                                                            <div class='todoTitle'>
                                                                <span><input type="checkbox" name="deleteCheck" id="deleteCheck" class="deleteCheck icheckbox_flat-blue" value="
<?php echo $row->id ;?>">&nbsp <h5><b>
                                                                    <?php echo $row->todoTitle ; ?></b></h5></span>
                                                            </div>
                                                            <div class='todoDesc'>
                                                                <?php echo $row->todoDesc ; ?>
                                                            </div>
                                                            <br><br>
                                                        </div>
                                                        <?php
        } 
                                                        ?>
                                                    </div>
                                                    <?php  
    }else{
                                                    ?>
                                                    <div class='list-group-item todolist' data-role='task'>
                                                        No Todo list found!!!
                                                    </div>
                                                    <?php        
    }
                                                    ?>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card todobloack">
                                        <div class="card-body Activitie">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h5 class="card-title m-b-0">Today Activities</h5>
                                                </div>
                                            </div>
                                            <!-- ============================================================== -->
                                            <!-- To do list widgets -->
                                            <!-- ============================================================== -->
                                            <div class="to-do-widget m-t-20">
                                                <div class="list-task todo-list list-group m-b-0" data-role="tasklist">
                                                    <div class="">
                                                        <div id="result_activities" class="">
                                                        </div>
                                                        <div id="result_activities_message" class="col-lg-12 col-md-12"></div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <!-- Column -->
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- Comment - table -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Table -->
                        <!-- ============================================================== -->
                        <!-- End Comment - chats -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Over Visitor, Our income , slaes different and  sales prediction -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- End Page Content -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Todo, chat, notification -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- End Page Content -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
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
            <?php
$db2=new db();
$id=$_SESSION['UserID'];
$stmt= $db2->prepare("SELECT * FROM `users` WHERE id=:id"); 
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$a=$stmt->rowCount();
$result =$stmt->fetchAll();
$loginstatus=$result[0]['loginstatus'];
if($loginstatus==1)
{?>
            <script type="text/javascript">
                var UserName = "Hi 
                <?php echo ucfirst($_SESSION['UserName']); ?> Welcome to MYSUNLESS";
                $.toast({
                    heading: UserName
                    , text: 'Use the predefined ones, or specify a custom position object.'
                    , position: 'top-right'
                    , loaderBg: '#ff6849'
                    , icon: 'info'
                    , hideAfter: 3500
                    , stack: 6
                })
            </script>
            <?php
 $id=$_SESSION['UserID'];
 $loginstatus=0;
 $stmt=$db2->prepare("update users set loginstatus=:loginstatus where id=:id");
 $stmt->bindparam(":loginstatus",$loginstatus);
 $stmt->bindparam(":id",$id);
 $stmt->execute();
}
            ?>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script type="text/javascript">
                $(document).on('click','#updatepackage',function(e){
                    e.preventDefault();
                    var delid=$(this).attr('data-id');
                    $(".modal2").show();
                    swal({
                        title: "Are you sure?",
                        text: "you really want to Upgrade your package ?",
                        icon: "warning",
                        buttons: true,
                    }).then((willUpgrade)=>{   
                        if (willUpgrade){
                        $.ajax({
                        dataType:"json",
                        type:"post",
                        data:{'packageid':delid},
                            url:'
                            <?php echo EXEC; ?>exec-edit-profile.php',
                            success: function(data)
                    {
                        if(data.resonse)
                        {
                            $("#resonse").show();
                            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                            $(".modal2").hide();
                            location.reload();
                        }
                        else if(data.error)
                        {
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            $(".modal2").hide();
                            // alert('<li>'+data.error+'</li>');
                        }
                    }
                });
                }
                else{
                    $(".modal2").hide();
                    return false ;
                }
                });
                });
            </script>
            <!-- For today activities -->
            <script>
                $(document).ready(function(){
                    var limitToday = 5;
                    var startToday = 0;
                    var action = 'inactive';
                    function displayRecordsToday(limitToday, startToday)
                    {
                        $.ajax({
                            url:"
                            <?php echo EXEC; ?>Exec_Activities_Pagination.php",
                            method:"POST",
                            data:"limitToday="+limitToday+"&startToday="+startToday+"&today",
                            cache:false,
                            beforeSend: function(data) {    
                            $(".modal").show();
                        },
                               success:function(data)
                        {
                            $(".modal").hide();
                            $('#result_activities').append(data);
                            if(data == '')
                            {
                                $('#result_activities_message').html("
                                                                     <button type='button' disabled class='btn btn-secondary col-lg-12 col-md-12' > No Today Records Found </button>");
                                                                     action = 'active';
                                                                     }
                                                                     else
                                                                     {
                                                                     $('#result_activities_message').html("
                                                                                                          <button type='button' id='moreToday' class='btn btn-info col-lg-12 col-md-12'> Show More </button>
                                                                                                          ");
                                                                                                          action = "inactive";
                                                                                                          }
                                                                                                          }
                                                                                                          });
                            }
                            if(action == 'inactive')
                            {
                                action = 'active';
                                displayRecordsToday(limitToday, startToday);
                            }
                            $(document).on('click', '#moreToday', function(){
                                if( action == 'inactive')
                                {
                                    action = 'active';
                                    startToday = startToday + limitToday;
                                    setTimeout(function(){displayRecordsToday(limitToday, startToday);}, 1000);
                                }
                            });
                        });
            </script>
            <!-- /For today activities -->
            <script type="text/javascript">
                $(".close").click(function(){
                    $("#error").hide();
                    $("#resonse").hide();
                }
                                 );
            </script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
            <script type="text/javascript" src="
<?php echo base_url; ?>/assets/js/calendar.js"></script>
            <script type="text/javascript" src="
<?php echo base_url; ?>/assets/js/events.js"></script>
            <!--  For show more/less content -->
            <script>
                $(document).ready(function() {
                    var showChar = 100;
                    var ellipsestext = "...";
                    var moretext = "Show more";
                    var lesstext = "Show less";
                    $('.todoDesc').each(function() {
                        var content = $(this).html();
                        if(content.length > showChar) {
                            var contentshow = content.substr(0, showChar);
                            var contenthide = content.substr(showChar-1, content.length - showChar);
                            var html = contentshow + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span class="hiddencontent">' + contenthide + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                            $(this).html(html);
                        }
                    }
                                       );
                    $(".morelink").click(function(){
                        if($(this).hasClass("less")) 
                        {
                            $(this).removeClass("less");
                            $(this).html(moretext);
                        }
                        else 
                        {
                            $(this).addClass("less");
                            $(this).html(lesstext);
                        }
                        $(this).parent().prev(".moreellipses").toggle();
                        $(this).prev(".hiddencontent").toggle();
                        return false;
                    }
                                        );
                }
                                 );
            </script>
            <script type="text/javascript">
                $(document).on('click','#deleteTags',function(){
                    var id=new Array();
                    $('input[name="deleteCheck"]:checked').each(function(){
                        id.push(this.value);
                    }
                                                               );
                    if(id == '')
                    {
                        swal("Please Select at least one Todo");
                    }
                    else if(id!=''){
                        swal({
                            title: "Are you sure?",
                            text: "Once deleted, you will lost all selected Todo !",
                            icon: "warning",
                            buttons: true,
                        }
                            ).then((isConfirm)=>{
                            if (isConfirm){
                            $.ajax({
                            type:"post",
                            data:{
                            'del_id':id}
                                   ,
                                   url:'?action=deleteTags',
                                   success: function(data) {
                            $('input[name="deleteCheck"]:checked').each(function(){
                                $(this).parent().parent().parent().hide();
                            }
                                                                       );
                        }
                    }
                    );
                    swal("","Selected Todo Successfully deleted!", "success");
                }
                               else{
                               return;
                               }
                               }
                              );
                }
                }
                );
</script>
</body>
</html>