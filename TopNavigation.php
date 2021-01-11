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
	$NotificationStatus = $result['NotificationStatus'];

	if(!empty($result['NotificationStatus'])){
		$notify_bell_icon = 'fa-bell';
		$notify_switch='checked';

	}else{
		$notify_switch='';
		$notify_bell_icon = 'fa-bell-slash-o';

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

		.company_home_img_div{
			width: 65px;
			height: 65px;
			text-align: center;
		}
		.company_home_img{
			max-width: 100%;
			max-height: 100%;
		}

		.MaintenanceTime{
			color: white;
			padding: 10px 2px;
			border: 1px solid #777777;
			display: flex;
			position: absolute;
			left: 65px;
		}
		.MaintenanceTime span {
			font-size: 25px;
			padding: 0 3px;
		}

		@media (max-width: 767px) {
			.MaintenanceTime{
				padding: 0;
				left: 90px;
				top:45px;

			}
			.MaintenanceTime span {
				font-size: 10px;
				padding: 0 3px;
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
			<a class="navbar-brand company_home_img_div" href="<?php echo base_url; ?>/dashboard">
				<!-- Logo icon -->
				
				<!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
				<!-- Dark Logo icon -->
				<!-- <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" /> -->
				<!-- Light Logo icon -->
				<?php 
				if(!empty($compimg))
				{
					?>
					<img src="<?php echo base_url; ?>/assets/companyimage/<?php echo $compimg; ?>" alt="homepage" class="light-logo company_home_img"/>
					<?php
				}
				else
					{?>
						<img src="<?php echo base_url; ?>/assets/images/smallpart1.png" alt="homepage" class="light-logo company_home_img" />
						<?php
					}
					?>
					
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
								<a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)" ><i class="icon-menu" data-step="1" data-intro="<?php echo $T2; ?>" data-position='right' data-scrollTo='tooltip'>
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
										<?php echo base_url; ?>/AllEvent" title="Appointment List" class="nav-link quickAccessMenu"><span> <i class="fa fa-calendar-check-o" data-step="2" data-intro="<?php echo $T3; ?>" data-position='right' data-scrollTo='tooltip'>
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
											<a  class="nav-link quickAccessMenu" href="<?php echo base_url; ?>/todo" title="To-Do List"  style="cursor: pointer;"><span><i class="fa fa-th-list" data-step="3" data-intro="<?php echo $T4; ?>" data-position='right' data-scrollTo='tooltip'>
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
                   <?php if($_SERVER['PHP_SELF'] == '/Product.php') {?>
                   	<a class="dropdown-item" id="newproduct" data-toggle="modal" data-target="#AddProductModal" style="cursor: pointer;">Product</a>
                   <?php }else{?>
                   	<a class="dropdown-item" href="<?= base_url;?>/Product"  style="cursor: pointer;">Product</a>
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
                   <a class="dropdown-item" href="<?= base_url;?>/Order" style="cursor: pointer;">Checkout</a>
               </div>
           </li>

           <?php if($_SERVER['PHP_SELF'] == '/crm/dashboard.php') {  ?>

           	<li class="nav-item hidden-md-down" >
           		<a  class="nav-link" title="Tutorial" onclick="javascript:introJs().start();" aria-expanded="false"><i class="far fa-question-circle" data-step="4" data-intro="<?php echo $T5; ?>" data-position='right' data-scrollTo='tooltip'>
           		</i>
           	</a>
           </li>

       <?php } ?>

       <?php
   }
   ?>
   <li class="nav-item" >
   	<a  href="<?php echo base_url; ?>/help" class="nav-link" title="Help" aria-expanded="false"><span><i class=" fa fa-info-circle" data-step="5" data-intro="<?php echo $T6; ?>" data-position='right' data-scrollTo='tooltip'>
   	</i></span></a>
   </li>

   <li class="nav-item">
   	<a class="nav-link" title="Archive List" target="_blank" href="<?php echo base_url; ?>/Archive" aria-expanded="false" ><i class="fa fa-archive" data-position='left' data-scrollTo='tooltip' data-step="21" data-intro="Archive List">
   	</i></a>
   </li>

   <?php if (@$_SESSION['usertype']!= 'Admin')
   {
   	?>
   	<li class="nav-item" id="show_notify">
   		<a class="nav-link" title="Notification">
   			<span class="badge badge-success notify_badge">0</span>
   			<i class="fa fa-bell"  data-step="22" data-intro="Notification" data-position='left' data-scrollTo='tooltip'></i>
   		</a>  
   	</li>

   	<div id='notification_block'>
   		<div style="padding-top: 8px;">
   			<label class="switch notification_switch mr-1"> 
   				<input name="notification" value="1" <?= $notify_switch ?> type="checkbox">
   				<span class="slider round_switch"></span> 
   			</label>
   			<h3 style="color: white;"><i class="fa fa-bell mr-2"></i>Notification</h3></div>
   			<div class="card" id="app_notification"><img src="<?= base_url?>/assets/images/no_notification.png"></div>
   		</div>

   	<?php }  ?>
   	

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
   				<span class="hidden-md-down"> <?php echo @$_SESSION['UserName']; ?> &nbsp;<i class="fa fa-angle-down" data-step="6" data-intro="<?php echo $T7; ?>" data-position='right' data-scrollTo='tooltip'>
   				</i></span> 
   			</a>

   			<div class="dropdown-menu dropdown-menu-right animated flipInY">
   				<!-- text-->
   				<a href="<?php echo base_url; ?>/Profile" class="dropdown-item"><i class="ti-user"></i> My Profile</a>


   				<?php if($_SESSION['usertype']=='Admin'){
   					$stmt=$db->prepare("SELECT Maintenance from users where usertype='Admin' ");
					$stmt->execute();
					$MaintenanceResult = $stmt->fetch();
					if($MaintenanceResult['Maintenance']!=0){
						$MaintenanceChecked = 'checked';
					}else{
						$MaintenanceChecked = '';
					}

   					?>
   					<a class="dropdown-item">
   						<input id="Maintenance" name="Maintenance" value="1" type="checkbox" <?= $MaintenanceChecked ?> > Maintenance</a>

   					<?php } ?>

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
		<?php include_once('MaintenanceTimer.php');?>
   		<div class="MaintenanceTime" style="display: none;cursor: help;" title="Maintenance Mode will be active very soon!"></div>



   	</div>
   </nav>
   <?php
   if($ctheme=="2")
   {
   	include 'TopNavigation2.php';              
   }
   ?>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
   <script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
   <?php 
   if($_SESSION['usertype']!='Admin'){ 
   	require_once('Notification.php');
   }
	require_once('SetActivity.php');
   ?>