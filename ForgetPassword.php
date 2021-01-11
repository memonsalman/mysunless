<?php
ob_start();
?>
<?php


    require_once('global.php');
    require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php"); 

?>


<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url?>/assets/images/favicon.png">
        <title>
            MySunLess
        </title>
        <!-- page css -->
        <link href="<?= base_url?>/dist/css/pages/login-register-lock.css" rel="stylesheet">
        <link href="<?= base_url?>/assets/mystyle.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?= base_url?>/dist/css/style.min.css" rel="stylesheet">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    </head>
    <?php
if(isset($_SESSION["UserID"]) && !empty($_SESSION["UserID"])){
    header("Location: dashboard.php");
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
                url('<?= base_url?>/assets/images/ajax-loader.gif') 50% 50% no-repeat;
        }
        body.loading .modal {
            overflow: hidden;
        }
        body.loading .modal {
            display: block;
        }
        #btn {
            background: #5bc0de !important;
        }
        #btn:hover{
            background-color:#46b8da  !important;
        }
        section#wrapper{overflow: auto;}

        .login-box.card{position: fixed;overflow: auto;}
        @media only screen and (max-width: 767px) {
            .login-register {
                    overflow: auto;
            }
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
    </style>
    <body>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <!-- <section id="wrapper" class="login-register login-sidebar" style="background-image:url(<?= base_url?>/assets/images/background/login-register.jpg);"> -->
<section id="wrapper" class="login-register login-sidebar" style="background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.7)),url(<?= base_url?>/assets/images/bgimg1.jpg);">
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
                <div class="card-body" style="background: white;">
                    <a href="<?php echo base_url; ?>" class="text-center db"><img style="width: 100%;" src="<?= base_url?>/assets/images/mysunless_logo.png" /></a>
                    <form class="form-horizontal" autocomplete="off" id="forgetpassword" method="post">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>
                                    FORGOT PASSWORD
                                </h3>
                                <p class="text-muted">
                                    Enter your Email and instructions will be sent to you! 
                                </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="email" id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                  <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" id="btn" type="submit">Send</button>
                            </div>
                        </div>

                            <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>
                                    
                                    <a href="<?php echo base_url ?>/index" class="text-info m-l-5"><b>
                                        Sign In
                                        </b></a>
                                </p>
                            </div>
                        </div>
                        <label class="form-check-label">
                            <span class="text-danger align-middle" id="errorMsg"></span>
                        </label>
                    </form>
                    <div class="col-lg-12 col-md-12">
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
                </div>
            </div>
        </section>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="
<?php echo base_url; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="
<?php echo base_url; ?>/dist/js/jquery.validate.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <!--<script src="
<?php echo base_url; ?>/assets/node_modules/popper/popper.min.js"></script>
<script src="
<?php echo base_url; ?>/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>-->
        <script src="
<?php echo base_url; ?>/dist/js/sweetalert.min.js"></script>
        <script src="
<?php echo base_url; ?>/assets/js/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="
<?php echo base_url; ?>/assets/css/sweetalert.css">
        <!--Custom JavaScript -->
  
        <script type="text/javascript">
            $(document).ready(function(){

                  $("#forgetpassword").validate({
                    rules: {
                       
                        email: {required: true}
                    },
                    messages: {
                       
                        email: {required: "Please enter  email"}
                       },
                    submitHandler: function() {
                        $(".modal").show();
                        var data = $("#forgetpassword").serialize();
                        data= data + "&action3=forgetpassword";
                  jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:data,
                        url:'<?php echo EXEC; ?>exec-edit-profile.php',
                        success: function(data) 
                        {
                            if(data.resonse)
                            {
                                $("#resonse").show();
                                $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                $( '#forgetpassword' ).each(function(){
                                    this.reset();
                                }
                                                           );
                                $(".modal").hide();
                                setTimeout(function () {
                                   
                                    window.location.href = "../index.php";
                                }
                                           , 3000)
                            }
                            else if(data.error)
                            {
                                $("#error").show();
                                $('#errormsg').html('<span>'+data.error+'</span>');
                                $(".modal").hide();
                            }
                        }
                    })
                        }
                        });
                  });
                
            

                // jQuery('#forgetpassword').submit(function(event){

                //     $(".modal").show();
                //     event.preventDefault();
                //     var data = $("#forgetpassword").serialize();
                //     data= data + "&action3=forgetpassword";
                //     jQuery.ajax({
                //         dataType:"json",
                //         type:"post",
                //         data:data,
                //         url:'<?php echo EXEC; ?>exec-edit-profile.php',
                //         success: function(data) 
                //         {
                //             if(data.resonse)
                //             {
                //                 $("#resonse").show();
                //                 $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                //                 $( '#forgetpassword' ).each(function(){
                //                     this.reset();
                //                 }
                //                                            );
                //                 $(".modal").hide();
                //                 setTimeout(function () {
                //                     window.location.href = "index.php";
                //                 }
                //                            , 3000)
                //             }
                //             else if(data.error)
                //             {
                //                 $("#error").show();
                //                 $('#errormsg').html('<span>'+data.error+'</span>');
                //                 $(".modal").hide();
                //             }
                //         }
                //     });
                // });
         
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