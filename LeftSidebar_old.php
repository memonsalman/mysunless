<?php

$UserName=$_SESSION['UserName'];
$db3=new db();


if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$clientcreateprmistion=$result['ClientCreate'];
    @$schcreateprmistion=$result['SchedulesCreate'];
    @$sercreateprmistion=$result['ServicesCreate'];
    @$todocreateprmistion=$result['TodoCreate'];
    $stmt2= $db3->prepare("SELECT ctheme FROM `CompanyInformation` WHERE createdfk=:id"); 
    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    @$ctheme=$result2['ctheme'];
}
?>
<!-- Setup Wizard -->

<?php 

$dbx = new db();

if(isset($_SESSION['UserID']))
{


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

}

?>

<!-- End Setup Wizrd -->

<!--Start Dropdown menu open top side -->
<style type="text/css">
    ul.dropdown-upward ul {
        top: auto !important;
        bottom: 100%;
    }
    ul.dropdown-upward ul ul {
        bottom: 1px;
    }
    .fa-cogs,.fa-user-circle{
        color: #787f91;
        margin-left: 5px;
    }
    .up{
        top: auto!important;
        bottom:100%;
    }
    button#switch2{    margin: 0 20px;    width: 80%; color: #fff;    background-color: #28a745;    border-color: #28a745;}
</style>
<!--End Dropdown menu open top side -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" style="height: calc(100% - 0px);overflow: scroll;">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" >
            <ul id="sidebarnav">
                <!--<li class="user-pro"> <a  href="javascript:void(0)" aria-expanded="false">-->
                    <?php   
/* 
$img=@$result['userimg'];
if(empty($img) || file_exists('<?php echo base_url; ?>/assets/userimage/'.$img))
{ */?>
                <!--<img src="
                    <?php //echo base_url; ?>/assets/images/noimage.png" alt="user" class="">-->
                    <?php
/*  }
else
{ */
    ?>
                <!-- <img src="
<?php //echo base_url; ?>/assets/userimage/
<?php //echo @$result['userimg']; ?>" alt="user" class="">-->
<?php
/*   } */
?>
                <!--<span class="hide-menu">
                    <?php //echo @$UserName; ?></span></a>-->
                <!--     <ul aria-expanded="false" class="collapse">
<li><a href="javascript:void(0)"><i class="ti-user"></i> My Profile</a></li>
<li><a href="javascript:void(0)"><i class="ti-wallet"></i> My Balance</a></li>
<li><a href="javascript:void(0)"><i class="ti-email"></i> Inbox</a></li>
<li><a href="javascript:void(0)"><i class="ti-settings"></i> Account Setting</a></li>
<li><a href="javascript:void(0)"><i class="fa fa-power-off"></i> Logout</a></li>
</ul> -->
<!--  </li> -->
<li>
    <a class="dashboard"  href="<?php echo base_url; ?>/dashboard" aria-expanded="false"><i class="fa fa-home">
    </i><span class="hide-menu">Dashboard </span></a>
</li>

                <!-- <?php 
                    if($wpercentage<=100 AND $_SESSION['usertype']!=="Admin"){
                ?>
                    <li>
                        <a  href="<?php echo base_url; ?>/SetupWizard.php" aria-expanded="false"><i class="fa fa-rocket">
                            </i><span class="hide-menu">Account Setup Wizard </span></a>
                    </li>

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
                                <?php 

                                if($clientcreateprmistion==1)
                                {
                                    ?>
                                    <li>
                                        <a class="clients" href="<?php echo base_url; ?>/AllClients" aria-expanded="false"><i class="fa fa-sitemap">
                                        </i><span class="hide-menu">Clients</span></a>
                                    </li>
                                    <?php
                                } ?>
                <!-- <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-user-circle"></i><span class="hide-menu">Users</span></a>
<ul aria-expanded="false" class="collapse">
<li> 
<a  href="AddUser.php" aria-expanded="false"><i class="fa fa-user-plus"></i><span class="hide-menu">Add User </span></a>
</li>
<li> 
<a  href="ViewUsers.php" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">View Users</span></a>
</li> 
</ul>
</li> -->
<?php
if(@$_SESSION['usertype']!=="Admin")
{   

    if($schcreateprmistion==1)
    {
        ?>
        <li>
            <a class="allevent" href="<?php echo base_url; ?>/AllEvent" aria-expanded="false"><i class="fas fa-calendar-alt"></i><span class="hide-menu">Appointments</span></a>
        </li>
        <?php
    }
    ?>

                <!-- <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-money-bill-alt">
                        </i><span class="hide-menu">Point of Sale</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a class="" href="<?php echo base_url; ?>/AllProduct.php" aria-expanded="false"><i class="fa fa-tag">
                            </i><span> Product </span></a> -->
                            <!-- <ul aria-expanded="false" class="collapse"> -->
                                <!--  <li><a href="<?php echo base_url; ?>/AddProductCategory.php"><span><i class="fa fa-plus-square"></i></span> Add Product Category</a></li> -->
                                <!-- <li>
                                    <a href="<?php echo base_url; ?>/AllProductCategory.php"><span><i class="fa fa-list">
                                        </i></span> List of Product Category</a>
                                    </li> -->
                                    <!--  <li><a href="<?php echo base_url; ?>/Product.php"><span><i class="fa fa-plus-square"></i></span> Add Product</a></li> -->
                                <!-- <li>
                                    <a href="<?php echo base_url; ?>/AllProduct.php"><span><i class="fa fa-list">
                                        </i></span> List Of Product</a>
                                </li>
                            </ul> -->
                     <!--    </li>
                        <li>
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-shopping-basket">
                                </i><span> Order </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li>
                                    <a href="<?php echo base_url; ?>/Order.php"><span><i class="fa fa-shopping-bag">
                                        </i></span> New Order</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url; ?>/OrderList.php"><span><i class="fa fa-list">
                                        </i></span> List of Order</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->

                <li>

                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">    <i class="fa fa-shopping-bag"></i><span class="hide-menu">Checkout</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a class="checkout" href="<?php echo base_url; ?>/Order" aria-expanded="false"><i class="fa fa-shopping-basket"></i><span> Order </span></a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url; ?>/OrderList"><span><i class="fa fa-list-alt"></i></span> Order List</a>
                        </li> 
                    </ul>
                    <!-- <a class="checkout" href="<?php echo base_url; ?>/Order" aria-expanded="false"><i class="fa fa-shopping-bag"></i><span class="hide-menu"> Checkout </span></a> -->
                </li>   

                <li>
                    <a class="membership" href="<?php echo base_url; ?>/Memberships" aria-expanded="false"><i class="fa fa-users">
                    </i><span class="hide-menu"> Package </span></a>
                </li>
                <?php
                if($todocreateprmistion==1)
                {
                    ?>
                    <li>
                        <a class="todo" href="<?php echo base_url; ?>/todo" aria-expanded="false"><i class="fa fa-tasks">
                        </i><span class="hide-menu">To-Do</span></a>
                    </li>

                <?php } ?>
                
                <li>
                    <a class="allproduct" href="<?php echo base_url; ?>/AllProduct" aria-expanded="false"><i class="fas fa-dolly-flatbed"></i><span class="hide-menu"> Inventory</span></a>
                </li>
                <!-- <li>
                    <a  href="javascript:void(0)" aria-expanded="false"><i class="fa fa-handshake-o">
                        </i><span class="hide-menu"> Marketing</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="<?php echo base_url; ?>/AllCampaigns.php"><span><i class="fa fa-cubes">
                                </i></span> Campaign Management</a>

                                
                                
                        </li>
                        <li>
                            <a href="javascript:void(0)"><span><i class="fa fa-industry">
                                </i></span> Offline Marketing </a>
                        </li>
                    </ul>
                </li> -->
                <li>
                    <a id="reportdrop" class="report has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-table">
                    </i><span class="hide-menu">Reports</span></a>
                    <ul aria-expanded="false" class="collapse up">

                        <li>
                            <a class = "allinone" href="<?php echo base_url; ?>/Allinonereport"><span><i class="fas fa-exchange-alt"></i></span> Transaction List</a>
                        </li>  

                        <li>
                            <a class="prosales" href="<?php echo base_url; ?>/productsalereport"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span> Product Sales</a>
                        </li>

                        <li>    
                            <a class="salebybrand" href="<?php echo base_url; ?>/salebybrand"><span><i class="fa fa-id-badge" aria-hidden="true"></i></span> Inventory</a>
                        </li>
                        
                       <!--  <li>
                        <a href="<?php echo base_url; ?>/salebybrand.php"><span><i class="fa fa-crosshairs" aria-hidden="true"></i></span> Sales by Brand</a>
                    </li>   -->

                        <!-- <li>
                            <a href="<?php echo base_url; ?>/AllActivites.php"><span><i class="fa fa-clock-o">
                                </i></span> Activities</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url; ?>/organizational_chart.php"><span><i class="fa fa-sitemap">
                                </i></span>  Organizational Chart </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url; ?>/Commission.php"><span><i class="fa fa-trophy">
                                </i></span>  Commission </a>
                            </li> -->
                            <li>
                                <a class="performance" href="<?php echo base_url; ?>/Performance"><span><i class="fa fa-tachometer">
                                </i></span>  Performance </a>
                            </li>
                            <li>
                                <a class="sales" href="<?php echo base_url; ?>/Sales"><span><i class="fa fa-shopping-bag">
                                </i></span> Sales</a>
                            </li>

                            <li>
                                <a class="payment" href="<?php echo base_url; ?>/Allpayment"><span><i class="fa fa-credit-card">
                                </i></span> Payment </a>
                            </li>

                        <!-- <li><a href="AddGolaes.php"><span><i class="fa fa-clock-o"></i></span> Set a Goal</a></li>
<li><a href="MonthlyGoals"><span><i class="fa fa-bar-chart-o"></i></span> Monthly Goal</a></li>
<li><a href="YearlyGoals.php"><span> <i class="fa fa-bar-chart-o"></i></span> Yearly Goal</a></li> -->
</ul>
</li>
                <!-- <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-briefcase">
                        </i><span class="hide-menu">Resources</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0)"><span><i class="fa fa-list-ol">
                                </i></span> Spray Tanning Tips</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><span><i class="fa fa-industry">
                                </i></span> Marketing Your Business </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><span><i class="fa fa-support">
                                </i></span> Equipment Support </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><span><i class="fa fa-plus-square">
                                </i></span> Other </a>
                        </li>
                    </ul>
                </li> -->
                <!-- <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-file-text"></i><span class="hide-menu">Training Videos</span></a>
<ul aria-expanded="false" class="collapse">
<li><a href="/videos/1"><span><i class="fa fa-clock-o"></i></span> How to Guides</a></li>
<li><a href="/videos/2"><span><i class="fa fa-bar-chart-o"></i></span> Product Guides</a></li>
<li><a href="/videos/3"><span> <i class="fa fa-bar-chart-o"></i></span> Equipment Guides</a></li>
<li><a href="/videos/4"><span><i class="fa fa-list"></i></span> Other</a></li>
</ul>
</li> -->
                <!--  <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-comments"></i><span class="hide-menu"> Communication</span></a>
<ul aria-expanded="false" class="collapse">
<li><a href="
<?php ; ?>/EmailSend.php"><span><i class="fa fa-envelope"></i></span> SEND MAIL</a></li>
<li><a href="
<?php ; ?>/SMSSend.php"><span><i class="fa fa-send"></i></span>  SEND SMS </a></li>
</ul>
</li> -->
<ul id="sidebarnav" class="dropdown dropdown-horizontal dropdown-upward">
    <li class="scroll">
        <a id="companydrop" class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-cogs ">
        </i><span class="hide-menu"> Company Settings </span></a>
        <ul aria-expanded="false" class="collapse">
            <li>
                <!-- <a class="setup" href="<?php echo base_url; ?>/SetupWizard"><span><i class="fa fa-info-circle"> -->
                    <a class="setup" href="<?php echo base_url; ?>/CompanyInformation"><span><i class="fa fa-info-circle">

                    </i></span> Company Information </a>
                </li>
                <?php 
                if($_SESSION['usertype']=="subscriber"){
                    ?>

                    <li>
                        <a href="<?php echo base_url; ?>/InvoiceTemplate"><span><i class="fa fa-file"></i></span> Invoice Template</a>
                    </li>

                <?php } ?>
                            <!-- <li>
                                <a href="javascript:void(0)"><span><i class="fa fa-wrench">
                                    </i></span> Employee Settings &amp; Roles </a>
                                </li> -->
                            <!-- <li>
                                <a href="javascript:void(0)"><span><i class="fa fa-file-word-o">
                                    </i></span> Release Forms </a>
                                </li> -->
                                <li>
                                    <a class="viewser" href="<?php echo base_url; ?>/viewService"><span><i class="fa fa-list"></i></span> List Of Services</a>
                                    <!-- <ul aria-expanded="false" class="collapse"> -->
                                        <!-- <li><a href="<?php echo base_url; ?>/AddCategory.php"><span><i class="fa fa-plus-square"></i></span> Add Category</a></li> -->
                                    <!-- <li>
                                        <a href="<?php echo base_url; ?>/AllCategory.php"><span><i class="fa fa-list">
                                            </i></span> List of Category</a>
                                        </li> -->
                                        <!-- <li><a href="<?php echo base_url; ?>/Product.php"><span><i class="fa fa-shopping-cart"></i></span> Products </a></li> -->
                                        <!-- <li><a href="<?php echo base_url; ?>/AddService.php"><span><i class="fa fa-plus-square"></i></span> Add Services</a></li> -->
                                        <li>
                                        <!-- <a href="<?php echo base_url; ?>/viewService.php"><span><i class="fa fa-list">
                                        </i></span> List Of Services</a> -->
                                    </li>
                                    <!-- </ul> -->
                                </li>
                                <li>
                                    <a class="memlist"  href="<?php echo base_url; ?>/MembershipPackageList" aria-expanded="false"><i class="fa fa-user">
                                    </i><span> Memberships </span></a>
                                </li>
                            <!-- <li>
                                <a  href="javascript:void(0)" aria-expanded="false"><i class="fa fa-calendar-plus-o">
                                    </i><span> Appointment / Calendar</span></a>
                                </li> -->
                            <!-- <li>
                                <a href="<?php echo base_url; ?>/Alltag.php"><span><i class="fa fa-tags">
                                    </i></span> Tag Management </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url; ?>/AddNote.php"><span><i class="fa fa-sticky-note">
                                    </i></span> Note Management </a>
                                </li> -->
                                <li>
                                    <a id="customerdrop" class="has-arrow waves-effect waves-dark" href="javascript:void(0)"><span>
                                        <i class="fa fa-comments">
                                        </i></span> Customer Communication </a>
                                        <ul aria-expanded="false" class="collapse">
                                            <li>
                                                <a class="allemail" href="<?php echo base_url; ?>/AllEmailTemp"><span><i class="fa fa-at">
                                                </i></span> Email</a>
                                            </li>
                                            <li>
                                                <a class="smsset" href="<?php echo base_url; ?>/SmsSendSetting"><span><i class="fa fa-envelope">
                                                </i></span> SMS </a>
                                            </li>
                                            <li>
                                                <a class="eventset" href="<?php echo base_url; ?>/EventSettings"><span><i class="fa fa-calendar-alt">
                                                </i></span> Appointment </a>
                                            </li>
                                    <!-- <li>
                                        <a href="javascript:void(0)"><span><i class="fa fa-share">
                                            </i></span> Point of Sale</a>
                                        </li> -->
                                    </ul>
                                </li>
                                <li>
                                    <a id="apidrop" class="has-arrow waves-effect waves-dark" href="javascript:void(0)"><span><i class="fa fa-link">
                                    </i></span> API Links</a>
                                    <ul aria-expanded="false" class="collapse">
                                        
                                        <?php if($_SESSION['usertype']=="subscriber"){ ?>
                                        <li>
                                            <a href="<?php echo base_url; ?>/EmailSendSetting"><span><i class="fa fa-envelope">
                                            </i></span> Email </a>
                                        </li>
                                        <?php } ?>

                                        <li>
                                            <a class="sms" href="<?php echo base_url; ?>/SmsSendSetting"><span><i class="fa fa-comments">
                                            </i></span> SMS </a>
                                        </li>

                                        <li>
                                            <a  class="has-arrow waves-effect waves-dark" href="javascript:void(0)"><span><i class="fa fa-database">
                                            </i></span> Data Backup </a>
                                            <ul aria-expanded="false" class="collapse">
                                                <li>
                                                    <a href="<?php echo base_url; ?>/MyBackup"><span><i class="fa fa-upload">
                                                    </i></span> Backup Contacts  </a>
                                                </li>
                                                <li>
                                                    <a class="data" href="<?php echo base_url; ?>/ImportWizard"><span><i class="fa fa-download">
                                                    </i></span> Import Contacts </a>
                                                </li>
                                            </ul>
                                        </li>


                                        <li>
                                            <a class="apipay" href="<?php echo base_url; ?>/paymentsetup"><span><i class="fa fa-credit-card">
                                            </i></span> Payment </a>
                                        </li>

                                    </ul>
                                </li>
                                
                            </ul>
                        </li>
                        <li>
                            <a id="myaccdrop" class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-user-circle">
                            </i><span class="hide-menu">  My Account</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li>
                                    <a class="editpro" href="<?php echo base_url; ?>/Profile"><span><i class="fa fa-male">
                                    </i></span> View / Edit Profile 
                                </a>
                            </li>
                            <li>
                                <?php 

                                if(@$_SESSION['usertype'] != 'employee'){
                                  ?>
                                  <a class="empmanage"  href="<?php echo base_url; ?>/AllEmployees" aria-expanded="false"><i class="fa fa-user">
                                  </i><span> Employee Management</span></a>

                                  <?php
                              }   
                              ?>
                          </li>
<li>
    <a class="gethelp" href="<?php echo base_url; ?>/help"><span><i class="fa fa-question-circle"></i></span> Get Help! </a>
</li>
</ul>
</li>

</ul>
<?php

if(@$_SESSION['oldusertype']=="subscriber")
{
    ?>
    <li>
        <a href="#" data-id="<?php echo $_SESSION['olduserid'] ?>" data-username="<?php echo $_SESSION['oldusername'] ?>" data-usertype="<?php echo $_SESSION['oldusertype'] ?>" id="switch2" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu"> Switch Subscriber </span></a>
    </li> 
    <?php
}
?>
<?php 
}
if(@$_SESSION['usertype']=="Admin")
{
    ?>
    <li> 
        <a class="package  waves-effect waves-dark" href="<?php echo base_url; ?>/AllPackage" aria-expanded="false"><i class="fas fa-box"></i><span class="hide-menu">Packages</span></a>
        <ul aria-expanded="false" class="collapse">
<!-- <li><a href="<?php echo base_url; ?>/AddPackage.php"><span><i class="fa fa-plus-square"></i></span> Add Package</a></li>
    <li><a href="<?php echo base_url; ?>/AllPackage.php"><span><i class="fa fa-list"></i></span> List of Package</a></li> -->
</ul>
</li>        
<li>
    <a class="allsub" href="<?php echo base_url; ?>/AllSubscriber.php" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">List of Subscribers</span></a>
</li> 

<!-- <li>
    <a class="camptemp" href="<?php echo base_url; ?>/AllCamTemp" aria-expanded="false"><i class="fa fa-cubes"></i><span class="hide-menu"> Campaign Templates</span></a>
</li>  -->

<li>
    <a class="allpost" href="<?php echo base_url; ?>/AllPost.php" aria-expanded="false"><i class="fas fa-sticky-note"></i><span class="hide-menu"> Posts </span></a>
</li> 


<li> 
    <a  class="allfaq waves-effect waves-dark" href="<?php echo base_url; ?>/FaqsList" aria-expanded="false"><i class="fa fa-question-circle"></i><span class="hide-menu"> Get Help! </span></a>
<!-- <ul aria-expanded="false" class="collapse">
<li><a href="<?php echo base_url; ?>/AddFaqs.php"><span><i class="fa fa-plus-circle"></i></span> Add FAQs</a></li>
<li><a href="<?php echo base_url; ?>/FaqsList.php"><span><i class="fa fa-list"></i></span> All FAQs</a></li>    
</ul> -->
</li>
<li><a href="<?php echo base_url; ?>/paymentsetup"><span><i class="fa fa-credit-card"></i></span> Payment Setup</a></li>



<li>
    <a class="adminreport has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-table"></i><span class="hide-menu">Reports</span></a>
    <ul aria-expanded="false" class="collapse">

        <li>
         <a class='payment' href="<?php echo base_url; ?>/SubscriberPaymant"><span><i class="fas fa-dollar-sign"></i></span> Payment</a>
     </li>

     <li>
        <a class="transaction" href="<?php echo base_url; ?>/Allinonereport"><span><i class="fas fa-exchange-alt"></i></span> Transaction List</a>
    </li>  

    <li>
        <a class="productsales" href="<?php echo base_url; ?>/productsalereport"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span> Product Sales</a>
    </li>

    <li>    
        <a class="inventory " href="<?php echo base_url; ?>/salebybrand"><span><i class="fa fa-id-badge" aria-hidden="true"></i></span> Inventory</a>
    </li>

    <!-- <li>
<a href="<?php echo base_url; ?>/Allinonereport.php"><span><i class="fa fa-globe" aria-hidden="true"></i></span> All in one</a>
</li>   -->


</ul>
</li>   




<li>
    <a class="adminpage has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cog"></i><span class="hide-menu">Page Setting</span></a>
    <ul aria-expanded="false" class="collapse">

     <li>
         <a class="setdash"  href="<?php echo base_url; ?>/dashbordsetting"><span><i class="fas fa-tachometer-alt"></i></span> Dashboard</a>
     </li>   

     <li>
         <a class="setclient" href="<?php echo base_url; ?>/clientsetting"><span><i class="fa fa-address-book"></i></span> Clients</a>
     </li>

     <li>
         <a class="setappo" href="<?php echo base_url; ?>/appointmentsettings"><span><i class="fas fa-calendar-alt"></i></span> Appointment</a>
     </li>

     <li>
         <a class="setpro" href="<?php echo base_url; ?>/productcsettings"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span> Product</a>
     </li>

     <li>
         <a class="setser"  href="<?php echo base_url; ?>/servicessettings"><span><i class="fab fa-servicestack"></i></span> Services</a>
     </li>

     <li>
         <a class="setmem" href="<?php echo base_url; ?>/membershipsettings"><span><i class="fa fa-user-plus" aria-hidden="true"></i></span> Membership</a>
     </li>

     <li>
        <a class="order" class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-shopping-basket">
        </i><span> Order </span></a>
        <ul aria-expanded="false" class="collapse">

            <li>
                <a class="setorder" href="<?php echo base_url; ?>/ordersettings"><span><i class="fa fa-shopping-bag"></i></span> Order</a>
            </li>

            <li>
                <a class="setolist" href="<?php echo base_url; ?>/Orderlistsettings"><span><i class="fa fa-list"></i></span> List of Order</a>
            </li>

        </ul>
    </li>
    <li>
        <a class="settodo" href="<?php echo base_url; ?>/todosettings.php" aria-expanded="false"><i class="fa fa-tasks"></i><span class="hide-menu"> To-Do</span></a>
    </li>

  <!--   <li>
        <a class="setcamp"  href="<?php echo base_url; ?>/campaignsettings" aria-expanded="false"><i class="fa fa-cubes"></i><span class="hide-menu"> Campaign</span></a>
    </li> -->

    <li>
        <a class="setprofile" href="<?php echo base_url; ?>/profilesetting" aria-expanded="false"><i class="ti-user"></i><span class="hide-menu"> Profile</span></a>
    </li>

    <li>
        <a class="setnote" href="<?php echo base_url; ?>/notesettings" aria-expanded="false"><i class="fa fa-sticky-note"></i><span class="hide-menu"> Note</span></a>
    </li>

    <li>
        <a class="setcom" href="<?php echo base_url; ?>/Communicationsettings" aria-expanded="false"><i class="fa fa-sticky-note"></i><span class="hide-menu"> Communication</span></a>
    </li>

     <li>
        <a class="setemail" href="<?php echo base_url; ?>/EmailSetting" aria-expanded="false"><i class="fa fa-envelope"></i><span class="hide-menu"> Email Setting</span></a>
    </li>

    <li>
        <a class="setapi" href="<?php echo base_url; ?>/apisettings" aria-expanded="false"><i class="fa fa-link"></i><span class="hide-menu"> Api</span></a>
    </li>

    <li>
        <a class="setback" href="<?php echo base_url; ?>/databackupsettings" aria-expanded="false"><i class="fa fa-database"></i><span class="hide-menu"> Data Backup</span></a>
    </li>

    <li>
        <a class="setemp" href="<?php echo base_url; ?>/Employeessettings" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu"> Employees</span></a>
    </li>

    <li>
        <a class="setfaq" href="<?php echo base_url; ?>/faqssettings" aria-expanded="false"><i class="fa fa-question-circle"></i><span class="hide-menu"> FAQS</span></a>
    </li>

    
</ul>
</li>   

<li>
    <a class="tutorial waves-dark" href="<?php echo base_url; ?>/Tutorialsettings" aria-expanded="false"><i class="fas fa-graduation-cap"></i><span class="hide-menu">Tutorial</span></a>
    
</li>


<?php
}
if(@$_SESSION['superusertype']=="Admin")
{
    ?>
    <li>
        <a href="#" data-id="<?php echo $_SESSION['superuserid'] ?>" data-username="<?php echo $_SESSION['superusername'] ?>" data-usertype="<?php echo $_SESSION['superusertype'] ?>" id="switch2" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu"> Switch Admin </span></a>
    </li> 
    <?php
}
?>
</ul>
<div class="Loader"></div>
</nav>
<!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
</aside>

<script>
    $(document).ready(function() 
    {

        $(document).on('click', '#switch2', function(){
            $(".Loader").show()
            var newoldid = $(this).attr("data-id");
            var newoldusername = $(this).attr("data-username");
            var newoldusertype = $(this).attr("data-usertype");

            jQuery.ajax({
              dataType:"json",
              type:"post",
              data:{newoldid:newoldid,newoldusername:newoldusername,newoldusertype:newoldusertype},
              url:'<?php echo base_url ; ?>/All_Script?page=left-sidebar',
              success: function(data) 
              {
                $(".Loader").hide()
                swal("Account successfully switched");
                setTimeout(function () { window.location.href = "";  }, 1000);
            }
        }) 
        });
    });
</script>
<?php //include 'newclient.php'; ?>