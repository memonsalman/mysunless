<?php 


require_once('global.php');

  require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");


//require_once('function.php');
 if(isset($_GET['confirm'])){
    $evnt= $db->prepare("SELECT * FROM `event` WHERE id=:id");  
    $evnt->bindParam(':id', $_GET['confirm'], PDO::PARAM_INT);
    $evnt->execute();
    $row = $evnt->fetch(PDO::FETCH_ASSOC);
    $dt = strtotime($row['EventDate']);
    $endt = strtotime($row['end_date']);

    $provider =  $db->prepare("SELECT * FROM `users` WHERE id=:pro");  
    $provider->bindParam(':pro', $row['ServiceProvider'], PDO::PARAM_INT);
    $provider->execute();
    $rowp = $provider->fetch(PDO::FETCH_ASSOC);

    $service =  $db->prepare("SELECT * FROM `Service` WHERE id=:ser");  
    $service->bindParam(':ser', $row['ServiceName'], PDO::PARAM_INT);
    $service->execute();
    $rows = $service->fetch(PDO::FETCH_ASSOC);

    $_SESSION['event-title'] = $rows['ServiceName'];
    $_SESSION['event-start-time'] = $row['EventDate'];
    $_SESSION['event-end-time'] = $row['end_date'];
 }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <style type="text/css">
    
        table#myTable{
            text-align: center;
        }
        .cubutfoma{margin: 3px;}

    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url; ?>/assets/images/favicon.png">
    <title>
        MySunLess
    </title>
    <!-- This page CSS -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Playfair+Display:400,700,700i,400i"/>
    <!--     <link href="
<?php echo base_url; ?>/assets/node_modules/morrisjs/morris.css" rel="stylesheet"> -->
    <link href="<?php echo base_url; ?>/assets/mystyle.css" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="<?php echo base_url; ?>/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url; ?>/dist/css/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="<?php echo base_url; ?>/dist/css/pages/dashboard1.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <!-- for text editor  -->
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.css" >
    <!-- material datetime picker  -->
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/custom-new.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/custom-mobile.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
CSS TimePicker-->
    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/jquery.timepicker.css">
    <!-- <link href="<?php echo base_url; ?>/assets/intro/demo.css" rel="stylesheet"> -->
    <link href="<?php echo base_url; ?>/assets/intro/introjs.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">

<style>
/*.modal2 {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal2 {overflow: hidden;}
body.loading .modal2 {display: block;}*/
li.token-search{width: 100%!important;}
button.dt-button.buttons-csv.buttons-html5 {cursor: pointer;position: absolute; top: 0; right: 0;border: #02a9f3; background: #02a9f3; height: 35px;}
button.dt-button.buttons-csv.buttons-html5:hover {background-color: #038fcd;}
div#home { position: relative;}
li.select2-selection__choice {color: white !important;}
.select2-container--default .select2-selection--multiple .select2-selection__choice{background-color: #42bfd3!important; border:1px solid #43c1d4!important; }
/*.select2-container--default .select2-selection--multiple{
   border-bottom: 1px solid #e9ecef!important;
   border-top: 0!important;
   border-right: 0!important;
   border-left: 0!important;}*/
span.select2.select2-container.select2-container--default.select2-container--focus{
   width: 100%!important;
}
span.select2.select2-container.select2-container--default.select2-container{
   width: 100%!important;
}
input.select2-search__field{
   width: 100%!important;
}
div.fb-login-button.fb_iframe_widget span {
    height: 27px !important;
}
/*div.fb-login-button.fb_iframe_widget span iframe {
    height: 35px !important;
}*/
.table-striped tbody tr:nth-of-type(odd) {
    background: #a3d3ea1c!important;
}
.table-bordered td, .table-bordered th {
     border-bottom: 1px solid #dee2e6!important;
     border-top : 1px solid #dee2e6!important;
     border-left: 0!important;
     border-right:0!important;
}
input#deleteCheck{box-sizing: border-box;
    padding: 0;
    width: 20px!important;
    height: 20px!important;
    margin-top: 13px!important;}
._5h0d ._5h0i {
    height: 35px !important;
}
._5h0d ._5h0m {
    height: 16px !important;
  }
  .img-circle {
    object-fit: cover;
}

    #viecdetails,#deleteButton,#editcustomer{display: inline-block!important;}
    
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .lb-details{display: none!important;}
      .pac-container.pac-logo{z-index: 99999;}
      .lb-container{    position: absolute;    right: 0;    left: 0; }
      .lb-outerContainer{width: 50%!important;}
      img.lb-image{margin: 0 auto!important;}
      .lb-dataContainer{width: 75%!important; margin: unset!important;}
      .lightbox{top: 150px!important;}

      @media only screen and (max-width: 768px) 
      {
    .lightbox{top: 500px!important}
    .lb-outerContainer{width: 80%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
    }
      
    </style>

    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">

</style>
<link href="<?php echo base_url; ?>/assets/css/tokenize2.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url; ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />    

<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/theme4.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">
<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
    <!--[endif]-->
</head>
    <body class="skin-default fixed-layout mysunlessO">
        <div id="main-wrapper">
            <header class="topbar">
               <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="https://mysunless.com/dashboard">
                            <b>
                                <img src="<?= base_url;?>/assets/images/smallpart1.png" alt="homepage" class="light-logo" >
                            </b>
                        </a>
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto horiz">
                    
                        </ul>
                        <ul class="navbar-nav my-lg-0">
                            <li class="dropdown" style="padding:15px;">
                              <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="https://mysunless.com/" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                MySunLess
                              </a>  
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <div class="page-wrapper" style="min-height: 188px;margin-left: 0px;">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-12 col-sm-12 col-xs-12 align-self-center">
                            <h4 class="text-themecolor">
                                Appointment Confirmed  <a href="<?php echo base_url?>/" class="pull-right">Home</a>              
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding-left: 0px;padding-right: 0px;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-6 offset-sm-3">
                                                <div class="card text-center" style="border-radius:10px;">
                                                  <div class="card-header" style="background-color:red;border-top-left-radius: 10px;border-top-right-radius: 10px;">
                                                    <h2 style="color:white"><?php echo date('M',$dt); ?></h2>
                                                  </div>
                                                  <div class="card-body">
                                                    <h1 class="card-title"><?php echo date('d',$dt); ?></h1>
                                                    <span class="badge" style="padding:20px;color:white;background-color:#3dc431;border-radius:50%;"><i class="fa fa-check fa-2x"></i></span>
                                                  </div>

                                                </div>
                                                <!--  <span class="badge" style="position:absolute;margin-left:75%;margin-top:-20%;padding:20px;color:white;background-color:#3dc431;border-radius:50%;"><i class="fa fa-check fa-2x"></i></span> -->
                                            </div>
                                            <div class="col-lg-6 offset-lg-3 text-center">
                                                <p style="font-size: 30px;">Your Appointment is now confirmed</p>
                                                <p style="font-size: 20px;">You're All Set with <?php echo $rowp['firstname'].' '.$rowp['lastname'];?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row justify-content-md-center">
                                            <div class="col-lg-4 text-center">
                                                <p style="font-size: 30px;"><?php echo date('l',$dt);  ?>,<?php echo date('M',$dt); ?> <?php echo date('d',$dt); ?>,<?php echo date('Y',$dt); ?></p>
                                                <p style="font-size: 30px;"><?php echo date('g:i a',$dt); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-6 offset-sm-3 text-center">
                                                <div class="addeventatc" data-styling="none">
                                                    <div class="date">
                                                        <span class="mon"><?php echo date('M',$dt); ?></span>
                                                        <span class="day"><?php echo date('d',$dt); ?></span>
                                                        <div class="bdr1"></div>
                                                        <div class="bdr2"></div>
                                                    </div>
                                                    <div class="desc">
                                                        <p>
                                                            <strong class="hed"><?php echo $rows['ServiceName'] ?></strong>
                                                            <span class="des">Location: <?php echo $row['Address'].','.$row['City'];?><br />When: <?php echo date('g A',$dt); ?> - <?php echo date('g A',$endt); ?></span>
                                                        </p>
                                                    </div>
                                                    <span class="start"><?php echo $row['EventDate']; ?></span>
                                                    <span class="end"><?php echo $row['end_date']; ?></span>
                                                    <span class="timezone">America/Los_Angeles</span>
                                                    <span class="title"><?php echo $rows['ServiceName'] ?></span>
                                                    <span class="description"><?php echo $row['EmailInstruction'] ?></span>
                                                    <span class="location"><?php echo $row['Address'].','.$row['City'].','.$row['State'].','.$row['country'].','.$row['Zip']; ?></span>
                                                    <span class="organizer">Service Provider :<?php echo $rowp['firstname'].' '.$rowp['lastname'];?></span>
                                                    <span class="organizer_email"><?php echo $rowp['email']?></span>
                                                </div>
                                            </div> -->
                                            <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-6 offset-sm-3 text-center">
                                                <a href="<?php echo base_url?>/googlcel/home" class="btn btn-lg btn-block btn-warning">Add To Calendar</a>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer" style="margin-left: 0px;">
                © 
                <?php echo date('Y'); ?> All right reserved by 
                <a href="https://mysunless.com">Mysunless.com</a>
            </footer>
        
        </div>
        <script src="<?php echo base_url; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<!-- Bootstrap popper Core JavaScript -->
<script src="<?php echo base_url; ?>/assets/node_modules/popper/popper.min.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo base_url; ?>/dist/js/perfect-scrollbar.jquery.min.js"></script>
<!--Wave Effects -->
<script src="<?php echo base_url; ?>/dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?php echo base_url; ?>/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<!-- <script src="<?php echo base_url; ?>/dist/js/custom.min.js"></script> -->
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- Select 2 or more in input-->
<script src="<?php echo base_url; ?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<!--morris JavaScript -->
<script src="<?php echo base_url; ?>/assets/node_modules/raphael/raphael-min.js"></script>
<!-- <script src="<?php echo base_url; ?>/assets/node_modules/morrisjs/morris.min.js"></script> -->
<script src="<?php echo base_url; ?>/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
<!-- Popup message jquery -->
<!--<script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>-->
<!-- Chart JS -->
<script src="<?php echo base_url; ?>/dist/js/dashboard1.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/toast-master/js/jquery.toast.js"></script>
<!-- form validation -->
<script src="<?php echo base_url; ?>/dist/js/jquery.validate.js"></script>
<!-- form dataTable -->
<script src="<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<!-- from sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- jquery.timepicker and bootstrap-datepicker-->
<script src="<?php echo base_url; ?>/assets/js/jquery.timepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- jquery.datepair and datepair-->
<script src="<?php echo base_url; ?>/assets/js/datepair.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/jquery.datepair.js" type="text/javascript"></script>

<script src="<?php echo base_url; ?>/assets/intro/intro.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
</body>
</html>