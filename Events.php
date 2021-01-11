<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/function.php");
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
$db3=new db();
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$schcreateprmistion=$result['SchedulesCreate'];
}
if($schcreateprmistion==0){
    header("Location: index.php");die;  
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link href="../assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="
<?php echo base_url; ?>/assets/css/calendar.css">
    <style>
        .modal {
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
        body.loading .modal {
            overflow: hidden;
        }
        body.loading .modal {
            display: block;
        }
        ul#eventlist{
            display: block !important;
        }
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
    </style>
    <link rel="stylesheet" href="
<?php echo base_url; ?>/assets/css/dropify.min.css">
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
                                Events
                            </h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                            </div>
                        </div>
                    </div>
                    <div class="page-header">
                        <div class="pull-right form-inline">
                            <div class="btn-group">
                                <button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
                                <button class="btn btn-default" data-calendar-nav="today">Today</button>
                                <button class="btn btn-primary" data-calendar-nav="next">Next >></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-warning" data-calendar-view="year">Year</button>
                                <button class="btn btn-warning active" data-calendar-view="month">Month</button>
                                <button class="btn btn-warning" data-calendar-view="week">Week</button>
                                <button class="btn btn-warning" data-calendar-view="day">Day</button>
                            </div>
                        </div>
                        <h3>
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div id="showEventCalendar">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alleventlist">
                                <div class="eventlisttitle">
                                    <h4>
                                        All Events List
                                    </h4>
                                </div>
                                <div class="eventlist">
                                    <ul id="eventlist" class="nav nav-list">
                                    </ul>
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
   
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script type="text/javascript" src="
<?php echo base_url; ?>/assets/js/calendar.js"></script>
<script type="text/javascript" src="
<?php echo base_url; ?>/assets/js/events.js"></script>
<script type="text/javascript">
    $('#calendar').fullCalendar( 'destroy' );
</script>
 </body>
</html>
