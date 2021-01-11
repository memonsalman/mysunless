<?php

require_once('global.php');

$db=new db();


if(isset($_SESSION['UserID']))
{

	$id=$_SESSION['UserID'];

//Company Logo
	if($_SESSION['usertype'] == "employee")
	{
		$stmt= $db->prepare("SELECT * from CompanyInformation WHERE createdfk=(SELECT adminid FROM `users` WHERE id=:id)");
	}
	else if($_SESSION['usertype'] == "user")
	{

		$stmt=$db->prepare("SELECT * from CompanyInformation WHERE createdfk=(SELECT sid FROM `users` WHERE id=:id)");

	}
	else
	{

		$stmt=$db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=:id");
	}

	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	@$compimg=$result['compimg'];

//CompanyImage for Invoice
	if(empty($compimg))
	{
		$userimag = base_url.'/assets/images/smallpart1.png';
	}
	else
	{
		$userimag = base_url.'/assets/companyimage/'.$compimg; 
	} 

// Company Sign
	if($_SESSION['usertype'] == "employee")
	{
		$stmt= $db->prepare("SELECT * from users WHERE id=(SELECT adminid FROM `users` WHERE id=:id)");
	}
	else if($_SESSION['usertype'] == "user")
	{
		$stmt=$db->prepare("SELECT * from users WHERE id=(SELECT sid FROM `users` WHERE id=:id)");
	}
	else
	{
		$stmt=$db->prepare("SELECT * FROM `users` WHERE id=:id");
	}

	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

@$mysign = $result['mysign']; //Important for Invoice Sign on whole website


if($mysign){
	$compsign = base_url."/assets/sing/".$mysign;
}else{
	$compsign = base_url."/assets/sing/no_sign.png";
}

// Theme
$stmt_theme=$db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=:id");
$stmt_theme->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_theme->execute();
$result_theme = $stmt_theme->fetch(PDO::FETCH_ASSOC);
@$ctheme=$result_theme['ctheme'];

}

$db3=new db();
if(isset($_SESSION['UserID']))
{

	$id=$_SESSION['UserID'];
	$stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	@$clientcreatex = $result['ClientCreate'];
	@$schcreateprmistion=$result['SchedulesCreate'];
	@$todocreateprmistion=$result['TodoCreate'];


    //  @$compimg=$result['compimg'];

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
    $wcompanyx = $db3->prepare("SELECT * FROM `CompanyInformation` where createdfk=:cfk OR updatedfk=:ufk");
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

    $wpaymentx = $db3->prepare("SELECT * FROM `paymentsetup` where UserID=:pid");
    $wpaymentx->bindParam(':pid',$wuserid,PDO::PARAM_INT);
    $wpaymentx->execute();
    $pdata = $wpaymentx->fetch(PDO::FETCH_ASSOC);
    if($pdata)
    {
    	$wpaymentsetup = 1;
    	$wpercentage = $wpercentage + 20;
    }

    // Category 

    $wcategoryx = $db3->prepare("SELECT * FROM `Category` where createdfk=:catfk OR updatedfk=:uatfk");
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

    $wemployeex = $db3->prepare("SELECT * FROM `users` where adminid=:adminid AND usertype='employee'");
    $wemployeex->bindParam(':adminid',$wuserid,PDO::PARAM_INT);
    $wemployeex->execute();
    $empdata = $wemployeex->fetch(PDO::FETCH_ASSOC);
    if($empdata)
    {
    	$wemployee = 1;
    	$wpercentage = $wpercentage + 5;
    }

    // Sms Setting

    $wsmsx = $db3->prepare("SELECT * FROM `smssetting` where UserID=:smsid");
    $wsmsx->bindParam(':smsid',$wuserid,PDO::PARAM_INT);
    $wsmsx->execute();
    $smsdata = $wsmsx->fetch(PDO::FETCH_ASSOC);
    if($smsdata)
    {
    	$wsms = 1;
    	$wpercentage = $wpercentage + 10;
    }

    // Email Setting

    $wemailx = $db3->prepare("SELECT * FROM `EmailSetting` where UserID=:emailid");
    $wemailx->bindParam(':emailid',$wuserid,PDO::PARAM_INT);
    $wemailx->execute();
    $emaildata = $wemailx->fetch(PDO::FETCH_ASSOC);
    if($emaildata)
    {
    	$wemail = 1;
    	$wpercentage = $wpercentage + 10;
    }
}

$button2= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='2'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$T2=$all_button2['TutorialMsg'];

$button3= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='3'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$T3=$all_button3['TutorialMsg'];

$button4= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='4'"); 
$button4->execute();
$all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
$T4=$all_button4['TutorialMsg'];

$button5= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='5'"); 
$button5->execute();
$all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
$T5=$all_button5['TutorialMsg'];

$button6= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='6'"); 
$button6->execute();
$all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
$T6=$all_button6['TutorialMsg'];

$button7= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='7'"); 
$button7->execute();
$all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
$T7=$all_button7['TutorialMsg'];

?>
<style type="text/css">
	.topbar .top-navbar .profile-pic img{
		max-width: 30px !important;
		max-height: 30px !important;
		height: 30px !important;
		width: 30px !important;
	}
	li.dropdown2{
		padding: 10px 0;
	}
	ul.dropdown-menu2{
		position: absolute;
		background: #3cabe1;
		display: block;
		right: 0;
		top: 68px;
		padding: 10px;
	}
	ul.nav2.navbar-nav2.navbar-right2{
		position:relative;
	}
	ul.dropdown-menu2 li strong{
		text-decoration: none;
		color: white!important;
	}
	ul.dropdown-menu2 li{
		display: inline;
	}
	/*li.quickAccessMenu {
		margin-top: 24%;
		margin-left: 25%;
	}*/
	a.quickAccessMenu {
		color: #959595 ;
		font-size: 17px;
	}
	.quickAccessMenu a:hover {
		color: white;
	}
	ul.navbar.sec-navabr {
		background: aliceblue;
	}
	img.light-logo {
		object-fit: cover;
	}
	img {
		object-fit: cover;
	}
	.shortaddform{top: 15px;}
	#shortaddformdiv{top: 50px;
		left: -100px;
	}
	.nav-link{
		padding: 8px 10px!important;
	}
	.nav-item{
		position: relative!important;
	}
	.notify_badge{
  /*animation: pulse 2s ease-out;*/
  animation-iteration-count: infinite;
}

@keyframes pulse {
  40% {
    transform: scale3d(1, 1, 1);
  }

  50% {
    transform: scale3d(1.3, 1.3, 1.3);
  }

  55% {
    transform: scale3d(1, 1, 1);
  }
  
  60% {
    transform: scale3d(1.3, 1.3, 1.3);
  }

  65% {
    transform: scale3d(1, 1, 1);
  }
}
</style>
<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">
<!-- <link href="<?php echo base_url; ?>/assets/intro/demo.css" rel="stylesheet">
	<link href="<?php echo base_url; ?>/assets/intro/introjs.css" rel="stylesheet">  -->
	<nav class="navbar top-navbar navbar-expand-md navbar-dark">
		<!-- ============================================================== -->
		<!-- Logo -->
		<!-- ============================================================== -->
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo base_url; ?>/dashboard">
				<!-- Logo icon -->
				<b>
					<!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
					<!-- Dark Logo icon -->
					<!-- <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" /> -->
					<!-- Light Logo icon -->
					<?php 
					if(!empty($compimg))
					{
						?>
						<img src="<?php echo base_url; ?>/assets/companyimage/<?php echo $compimg; ?>" alt="homepage" class="light-logo" height="65" width="65"/>
						<?php
					}
					else
						{?>
							<img src="<?php echo base_url; ?>/assets/images/smallpart1.png" alt="homepage" class="light-logo" />
							<?php
						}
						?>
					</b>
					<!--End Logo icon -->
					<!-- Logo text --><span>
						<!-- dark Logo text -->
						<!-- <img src="<?php echo base_url; ?>/assets/images/logo2.png" alt="homepage" class="dark-logo" /> -->
						<!-- Light Logo text -->    
						<!-- <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" /></span> </a> -->
						<!-- <img src="<?php echo base_url; ?>/assets/images/logo2.png" class="light-logo" alt="homepage" /></span>  --></a>
					</div>
					<!-- ============================================================== -->
					<!-- End Logo -->
					<!-- ============================================================== -->
					<div class="navbar-collapse">
						<!-- ============================================================== -->
						<!-- toggle and nav items -->
						<!-- ============================================================== -->
						<!-- ========= horiz = horizantal =============== -->
						<ul class="navbar-nav mr-auto horiz">
							<!-- This is  -->
							<li class="nav-item">
								<a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu">
								</i></a>
							</li>
							<li class="nav-item sidebartogale">
								<a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)" ><i class="icon-menu" data-step="2" data-intro="<?php echo $T2; ?>" data-position='right' data-scrollTo='tooltip'>
								</i></a>
							</li>
							<?php 
							if(@$_SESSION['usertype']!=="Admin")
							{   
								if($schcreateprmistion==1) 
								{
									?>
									<li class="nav-item quickAccessMenu">
										<a href="
										<?php echo base_url; ?>/AllEvent" title="Appointment List" class="nav-link quickAccessMenu"><span> <i class="fa fa-calendar-check-o" data-step="3" data-intro="<?php echo $T3; ?>" data-position='right' data-scrollTo='tooltip'>
										</i> </span> </a>
									</li>
									<?php
								}
								if($todocreateprmistion==1)
								{ 
									?>

									<!-- To-Do -->
									<li class="nav-item quickAccessMenu">
										<?php if($_SERVER['PHP_SELF'] != '/crm/todo.php') {?>
											<a  class="nav-link quickAccessMenu" href="<?php echo base_url; ?>/todo" title="To-Do List"  style="cursor: pointer;"><span><i class="fa fa-th-list" data-step="4" data-intro="<?php echo $T4; ?>" data-position='right' data-scrollTo='tooltip'>
											</i></span></a>
										<?php } ?>
									</li>
									<!-- End To-Do -->
									<?php
								}
							}
							?>
							<!-- ============================================================== -->
							<!-- Search -->
							<!-- ============================================================== -->
            <!--        <li class="nav-item">
<form class="app-search d-none d-md-block d-lg-block">
<input type="text" class="form-control" placeholder="Search & enter">
</form>
</li> -->
</ul>
<!-- ============================================================== -->
<!-- User profile and search -->
<!-- ============================================================== -->
<ul class="navbar-nav my-lg-0">
	<!-- ============================================================== -->
	<!-- User Profile -->
	<!-- Notification start -->
	<?php if (@$_SESSION['usertype']== 'Admin')
	{
		?>
		<ul class="nav2 navbar-nav2 navbar-right2">
			<li class="dropdown2" id="mysunless" >
				<a href="#" class="dropdown-toggle2" data-toggle="mysunless">
					<span class="label label-pill  count" style="border-radius:10px;"></span> 
					<!-- <span class="fa fa-bell" style="font-size:18px;margin-top: 14px;"></span>  -->
				</a>
				<ul class="dropdown-menu2">
				</ul>
			</li>
		</ul>
		<?php
	}
	?>                  
	<!-- Notification End -->                 
	<!-- ============================================================== -->


	<?php if (@$_SESSION['usertype']!= 'Admin')
	{
		?>

		<li class="dropdown nav-item">
			<a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-plus-circle"></i>
			</a>  
			<div class="dropdown-menu animated flipInY" id="shortaddformdiv" aria-labelledby="dropdownMenuButton">
				<!-- Client -->
				<?php if(@$clientcreatex != 0)
				{
					?>
					<?php if($_SERVER['PHP_SELF'] == '/dashboard.php' OR $_SERVER['PHP_SELF'] == '/AllEvent.php') 
					{
						?>
						<a class="dropdown-item" data-toggle="modal" onclick="$('#headerclick').val('true');" data-target="#myModal_new2" style="cursor: pointer;">Client</a>
					<?php }else if($_SERVER['PHP_SELF'] == '/AllClients.php') { ?>
						<a class="dropdown-item" data-toggle="modal" onclick="$('#headerclick').val('true');" data-target="#myModal_addclient" style="cursor: pointer;">Client</a>
					<?php }else { ?>
						<a class="dropdown-item" onclick="" href="<?= base_url;?>/AllClients" style="cursor: pointer;">Client</a>
					<?php } ?>
					<?php 
				}
				?>
				<!-- End Client -->

				<!-- Appointment -->
				<?php if(@$schcreateprmistion != 0) {?>
					<a class="dropdown-item" href="<?= base_url;?>/AllEvent" style="cursor: pointer;">Appointment</a>
				<?php } ?> 
				<!-- End Appointment -->

				<!-- Membership Package -->
				<?php if($_SERVER['PHP_SELF'] == '/MembershipPackageList.php') {?>
					<a class="dropdown-item" data-toggle="modal" id="addUser" data-target="#myModal_membshipackage" style="cursor: pointer;">Package</a>
				<?php }else{?>
					<a class="dropdown-item" href="<?= base_url;?>/MembershipPackageList"  style="cursor: pointer;">Package</a>
				<?php }?>
				<!-- End Membership Package -->

				<!-- To-Do -->
				<?php if(@$todocreateprmistion != 0){ ?>
					<?php if($_SERVER['PHP_SELF'] == '/todo.php') {?>
						<a class="dropdown-item" id="newnote" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">To-Do</a>
					<?php }else{?>
						<a class="dropdown-item" href="<?= base_url;?>/todo"  style="cursor: pointer;">To-Do</a>
					<?php }?>
				<?php } ?>
				<!-- End To-Do -->

				<!-- Product category -->
				<?php if($_SERVER['PHP_SELF'] == '/AllProductCategory.php') {?>
					<a class="dropdown-item" id="addUser" data-toggle="modal" data-target="#myModal_addcat" style="cursor: pointer;">Product Category</a>
				<?php }else{?>
					<a class="dropdown-item" href="<?= base_url;?>/AllProductCategory"  style="cursor: pointer;">Product Category</a>
				<?php }?>
				<!-- End Product Category -->

				<!-- Product Brand -->
<!--                <?php if($_SERVER['PHP_SELF'] == '/AllProductBrand.php') {?>
                   <a class="dropdown-item" onclick="$('#NewBrand #id').val('new');"  data-toggle="modal" data-target="#myModal_addbrand" style="cursor: pointer;">Brand </a>
               <?php }else{?>
                   <a class="dropdown-item" href="<?= base_url;?>/AllProductBrand" style="cursor: pointer;">Brand </a>
                   <?php }?> -->
                   <!-- End Product Brand-->

                   <!-- Product -->
                   <?php if($_SERVER['PHP_SELF'] == '/AllProduct.php') {?>
                   	<a class="dropdown-item" id="newproduct" data-toggle="modal" data-target="#AddProductModal" style="cursor: pointer;">Product</a>
                   <?php }else{?>
                   	<a class="dropdown-item" href="<?= base_url;?>/AllProduct"  style="cursor: pointer;">Product</a>
                   <?php }?>
                   <!-- End Product -->

                   <!-- Service category -->
<!--                <?php if($_SERVER['PHP_SELF'] == '/AllCategory.php') {?>
                   <a class="dropdown-item" id="addUserser" data-toggle="modal" data-target="#myModal_sercategory" style="cursor: pointer;">Service Category</a>
               <?php }else{?>
                   <a class="dropdown-item" href="<?= base_url;?>/AllCategory" style="cursor: pointer;">Service Category</a>
                   <?php }?> -->
                   <!-- End Service category -->

                   <!-- Service -->
                   <?php if($_SERVER['PHP_SELF'] == '/viewService.php') {?>
                   	<a class="dropdown-item" onclick="$('#NewServie #id').val('new');"  data-toggle="modal" data-target="#myModal_service" style="cursor: pointer;">Service </a>
                   <?php }else{?>
                   	<a class="dropdown-item" href="<?= base_url;?>/viewService" style="cursor: pointer;">Service </a>
                   <?php }?>
                   <!-- End Service-->

                   <!-- Campaign Category -->
                   <?php if($_SERVER['PHP_SELF'] == '/AddCampaignsCategory.php') {?>
                   	<a class="dropdown-item" onclick="$('#NewCategory #id').val('new');"  data-toggle="modal" data-target="#myModal" style="cursor: pointer;">Campaign category </a>
                   <?php }else{?>
                   	<a class="dropdown-item" href="<?= base_url;?>/AddCampaignsCategory" style="cursor: pointer;">Campaign category </a>
                   <?php }?>
                   <!-- End Campaign Category-->

                   <!-- Campaign -->
                   <a class="dropdown-item" href="<?= base_url;?>/AddCampaigns" style="cursor: pointer;">Campaign</a>
                   <!-- End Campaign -->

                   <a class="dropdown-item" href="<?= base_url;?>/Order" style="cursor: pointer;">Checkout</a>



               </div>
           </li>


           <li class="nav-item hidden-md-down" >
           	<a  class="nav-link" title="Tutorial" onclick="javascript:introJs().start();" aria-expanded="false"><i class="far fa-question-circle" data-step="5" data-intro="<?php echo $T5; ?>" data-position='right' data-scrollTo='tooltip'>
           	</i></a>
           </li>
           <?php
       }
       ?>
       <li class="nav-item hidden-md-down" >
       	<a  href="<?php echo base_url; ?>/help" class="nav-link" title="Help" aria-expanded="false"><span class="hidden-md-down" ><i class=" fa fa-info-circle " data-step="6" data-intro="<?php echo $T6; ?>" data-position='right' data-scrollTo='tooltip'>
       	</i></span></a>
       </li>

       <?php if (@$_SESSION['usertype']!= 'Admin')
       {
       	?>
       	<li class="nav-item" id="show_notify">
       		<a class="nav-link">
       			<span class="badge badge-success notify_badge">0</span>
       			<i class="fa fa-bell"></i>
       		</a>  
       	</li>
       	<div class="card" id="app_notification"><img src="<?= base_url?>/assets/images/no_notification.png"></div>
       <?php } ?>

       <li class="nav-item dropdown u-pro" >
       	<a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
       		<?php   
       		$img=@$result['userimg'];
       		if (empty($img)) 
       			{?>
       				<img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="">
       				<?php
       			} 
       			elseif (file_exists(DOCUMENT_ROOT.$SUB.'/assets/userimage/'.$img)) 
       			{
       				?>
       				<img src="<?php echo base_url; ?>/assets/userimage/<?php echo @$result['userimg']; ?>" alt="user" class="">
       				<?php
       			}
       			else 
       			{
       				?>
       				<img src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="">
       				<?php
       			}
       			?>
       			<span class="hidden-md-down"> <?php echo @$_SESSION['UserName']; ?> &nbsp;<i class="fa fa-angle-down" data-step="7" data-intro="<?php echo $T7; ?>" data-position='right' data-scrollTo='tooltip'>
       			</i></span> 
       		</a>

       		<div class="dropdown-menu dropdown-menu-right animated flipInY">
       			<!-- text-->
       			<a href="<?php echo base_url; ?>/Profile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>

                <!--             <?php 
                    if($wpercentage<=100 AND $_SESSION['usertype']!=="Admin"){
                ?>
                    <a href="<?php echo base_url; ?>/SetupWizard" class="dropdown-item"><i class="fa fa-rocket"></i> Account Setup</a>
                    

                     <div class="col-md-12">
                                    <div class="progress">
              <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $wpercentage; ?>%; height: 8px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <?php if($wpercentage>0){?>
                                    <span class="label label-success" style="margin-left: <?= $wpercentage-20;?>%;color:white"><?= $wpercentage; ?>%</span>
                                  <?php }else{ ?>
                                    <span class="label label-success" style=" color:white"><?= $wpercentage; ?>%</span>
                                  <?php }?>
                                </div>
               <?php } ?> 
                 -->        <!-- <?php 
                    if($wpercentage<=100 AND $_SESSION['usertype']!=="Admin"){
                ?>
               
                        <a  href="<?php echo base_url; ?>/SetupWizard.php" aria-expanded="false"><i class="fa fa-rocket"></i><span class="hide-menu">Account Setup</span></a>
               

                     <div class="col-md-12">
                                    <div class="progress">
              <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $wpercentage; ?>%; height: 8px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <?php if($wpercentage>0){?>
                                    <span class="label label-success" style="margin-left: <?= $wpercentage-20;?>%;color:white"><?= $wpercentage; ?>%</span>
                                  <?php }else{ ?>
                                    <span class="label label-success" style=" color:white"><?= $wpercentage; ?>%</span>
                                  <?php }?>
                                </div>
                                <?php } ?> -->

                                <!-- <a href="MyPaymant.php" class="dropdown-item"><i class="ti-user"></i> My Package info</a> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a> -->
                                <!-- text-->
                                <!-- <div class="dropdown-divider"></div> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a> -->
                                <!-- text-->
                                <!-- <div class="dropdown-divider"></div> -->
                                <!-- text-->
                                <a href="<?php echo base_url; ?>/Logout" class="dropdown-item"><i class="fa fa-power-off">
                                </i> Logout</a>
                                <!-- text-->
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End User Profile -->
                        <!-- ============================================================== -->
                        <!--   <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li> -->
                    </ul>
                </div>
            </nav>
            <?php
            if($ctheme=="2")
            {
            	include 'TopNavigation2.php';              
            }
            ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
            <script>
            	$(document).ready(function(){
            		$(".dropdown-menu2").hide();
            		function load_unseen_notification(view = '') {
            			$.ajax({
            				url:"dashboard.php",
            				method:"POST",
            				data:{
            					view:view}
            					,
            					dataType:"json",
            					success:function(data)
            					{
            						$('.dropdown-menu2').html(data.notification);
            						if(data.unseen_notification > 0)
            						{
            							$(".count").addClass('label-danger');
            							$('.count').html(data.unseen_notification);
            						}
            					}
            				}
            				);
            		}
        // load_unseen_notification();
        $(document).on('click', '.dropdown-toggle2', function(){
        	$(".dropdown-menu2").toggle();
        	$('.count').html('');
        	$(".count").removeClass('label-danger');
        	load_unseen_notification('yes');
        }
        );
        // setInterval(function(){ 
        // load_unseen_notification();; 
        // }, 5000);
    }
    );
</script>

<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
<script>
	$(document).ready(function(){
		var sessionID = window.btoa('<?php echo $_SESSION['UserID'] ?>');
		var emptydata = [];
		event_notification();
		update_notification();
		
		var notification_timer;

		$(window).focus(function() {
			notification_timer = setInterval(event_notification,5000);
		});

		$(window).blur(function() {
			clearInterval(notification_timer);
		});

		function event_notification(){
			
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("pending_appointment") === null || localStorage.getItem("notification_data") === null){
				window.localStorage.setItem('UserID', sessionID);
				window.localStorage.setItem('pending_appointment', JSON.stringify(emptydata));
				window.localStorage.setItem('notification_data', JSON.stringify(emptydata));
				window.localStorage.setItem('notification_blink','false');
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_Edit_Event",
				type:"post",
				data:{pending_appointment:window.localStorage.getItem('pending_appointment')},
				success:function(data){
					data = JSON.parse(data);
					if(data.response=="diffent"){
						window.localStorage.setItem('pending_appointment', JSON.stringify(data.data));

						if(data.user){
							var temp = [];
							temp = JSON.parse(window.localStorage.getItem('notification_data'));
							for(i=0;i<data.user.length;i++)
							{
								temp.push(data.user[i]);
							}

							window.localStorage.setItem('notification_data', JSON.stringify(temp));
							window.localStorage.setItem('notification_blink','true');
							update_notification();
							
						}
					}
				}
			});
		}

		$(document).on("click",".toast__close",function(e){
			e.preventDefault();
			var parent = $(this).parent('.toast');
			parent.fadeOut("slow", function() { $(this).remove(); } );

			var user = JSON.parse(window.localStorage.getItem('notification_data'));
			id = $(this).attr('data-id');
			var data = [];
			for(i in user){
				if(user[i].id!=id){
					data.push(user[i]);
				}
			}
			window.localStorage.setItem('notification_data',JSON.stringify(data));
			update_notification();
		});

		$(document).on("click",".close_all",function(e){
			e.preventDefault();
			var emptydata=[];
			window.localStorage.setItem('notification_data', JSON.stringify(emptydata));
			$("#app_notification").toggleClass('notification_visible');
			update_notification();
		});

		$("#show_notify").click(function(){
			$("#app_notification").toggleClass('notification_visible');
			window.localStorage.setItem('notification_blink','false');
		});

		window.addEventListener('storage',update_all_page_notification);

		function update_all_page_notification(){
			update_notification();
		}

		function update_notification(){

			var user = JSON.parse(window.localStorage.getItem('notification_data'));

			if(window.localStorage.getItem('notification_blink')=='true' && user.length>0){
				$('.notify_badge').css({'animation-name': 'pulse','animation-duration': '2s'});
			}else{
				$('.notify_badge').css({'animation-name': 'none'});
			}
			
			if(user.length>0){
				$(".notify_badge").text(user.length); 

				if($("#toast_notify").length<1)
				{
					$("#app_notification").html('<div class="close_all" title="Clear All"><i class="fa fa-trash"></i></div><div id="toast_notify"><div class="toast_container"></div></div>');
				}

				$(".toast_container").html('');
				for(i in user){
					id = user[i].id;
					fname = user[i].FirstName;
					lname = user[i].LastName;
					clientname = fname.toUpperCase()+' '+lname.toUpperCase();
					title = user[i].title;
					EventDate = user[i].EventDate;
					eventstatus = user[i].eventstatus;
					notify_body="";
					notify_head="";
					if(eventstatus=="confirmed"){
						notify_color = 'toast--green';
					}else if(eventstatus=="canceled"){
						notify_color = 'toast--red';
					}else{
						notify_color = 'toast--yellow';
					}
					if(eventstatus=='pending'){
						eventstatus = 'requested';
					}
					if(user[i].ProfileImg){
						ProfileImg="<?php echo $base_url ?>/assets/ProfileImages/"+user[i].ProfileImg;
					}else{
						ProfileImg="<?php echo $base_url ?>/assets/images/noimage.png";
					}
					$(".toast_container").append('<div class="toast '+notify_color+'"><img src="'+ProfileImg+'" class="toast__icon"><div class="toast__content"> <p class="toast__type">Appointment</p> <p class="toast__message"><b>'+clientname+'</b> has been '+eventstatus+' the '+title+' appointment.<br> @ '+EventDate+' <br><a href="<?php echo $base_url ?>/AllEvent" target="_blank">View Appointment</a></p> </div> <div class="toast__close" data-id="'+id+'">&times; </div> </div>');
				}

				$("#calendar_refresh").trigger("click");
				if($("#myDropdown_cal2").hasClass("show_cal")){
					$("#myDropdown_cal2").removeClass("show_cal");
				}
			}else{
				$(".notify_badge").text('0');
				$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');
			}

		}
	});
</script>
