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
    @$orderpermission=$result['OrderCreate'];
    @$employeepermission=$result['EmployeeCreate'];
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

    ul.dropdown-upward{
        top: auto!important;
        bottom:100%;
    }

    /*Bootstarp sidenav*/
    .sidebar-nav ul li a{
      position: relative;
  }

  .sidebar-nav ul li a::after{
      position: absolute;
      content: '';
      top: 0;
      left: 0;
      width: 0;
      height: 100%;
      background-color: #eaeaea;
      transition: width 0.25s ease;
      z-index: -1;
  }

  .sidebar-nav ul li a:hover::after{
      width:100%;
  }

  .sidebar-nav ul li a:hover span,.sidebar-nav ul li a:hover i{
      color: #3cabe1 !important;
  }

  .angle_down{
    position: absolute;
    right: 10px;
    top: 15px;
    transition: transform 0.5s;
}
.angle_up{
    transform: rotate(180deg) translate(14px,0);
}
#sidebarnav ul li a{
    display: flex!important;
}
#sidebarnav ul li a i{
    width: 25px;
    font-size: 16px;
    display: inline-block;
    vertical-align: middle;
    color: #787f91;
}
#sidebarnav ul li ul{
    padding-left: 15px;
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

                <li>
                    <a class="dashboard"  href="<?php echo base_url; ?>/dashboard" aria-expanded="false"><i class="fa fa-home">
                    </i><span class="hide-menu">Dashboard </span></a>
                </li>


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



                    <li>

                        <a class="has_arrow" href="javascript:void(0)" aria-expanded="false">    <i class="fa fa-shopping-bag"></i><span class="hide-menu">Checkout</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i>
                        </a>
                        <ul aria-expanded="false" class="collapse">

                            <?php
                            if($orderpermission==1)
                            {
                                ?>
                             <li>
                                <a class="checkout" href="<?php echo base_url; ?>/Order" aria-expanded="false"><i class="fa fa-shopping-basket"></i><span> Order </span></a>
                              </li>
                           <?php
                            }
                            ?>                          

                            <li>
                                <a href="<?php echo base_url; ?>/OrderList"><i class="fa fa-list-alt"></i><span>Order List</span></a>
                            </li> 
                        </ul>

                    </li>   

                    <li>
                        <a class="membership" href="<?php echo base_url; ?>/Memberships" aria-expanded="false"><i class="fas fa-box-open">
                        </i><span class="hide-menu"> Package </span></a>
                    </li>
                    <?php
                    if($todocreateprmistion==1)
                    {
                        ?>
                        <li>
                            <a class="todo" href="<?php echo base_url; ?>/todo" aria-expanded="false"><i class="fa fa-tasks">
                            </i><span class="hide-menu">ToDo</span></a>
                        </li>

                    <?php } ?>

                    <li>
                        <a class="allproduct" href="<?php echo base_url; ?>/Product" aria-expanded="false"><i class="fas fa-dolly-flatbed"></i><span class="hide-menu">Inventory</span></a>
                    </li>

                    <li>
                        <a id="reportdrop" class="report has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-table">
                        </i><span class="hide-menu">Reports</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                        <ul aria-expanded="false" class="collapse up">

                           <li>
                            <a class="payment" href="<?php echo base_url; ?>/EventList"><i class="fas fa-calendar-alt">
                            </i><span> Event List </span></a>
                        </li>

                        <li>
                            <a class = "allinone" href="<?php echo base_url; ?>/OrderList"><i class="fa fa-list-alt"></i><span> Order List</span></a>
                        </li>  

                        <li>
                            <a class="prosales" href="<?php echo base_url; ?>/Product_Sales"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Sales Report</span></a>
                        </li>

                        <li>
                            <a class="payment" href="<?php echo base_url; ?>/PaymentList"><i class="fa fa-credit-card">
                            </i><span> Payment List </span></a>
                        </li>
                        
                        <li>
                            <a class="performance" href="<?php echo base_url; ?>/Performance"><i class="fa fa-tachometer">
                            </i><span>  Performance </span></a>
                        </li>
                        <?php if($_SESSION['usertype']=="subscriber"){ ?>
                        <li>
                            <a href="<?php echo base_url; ?>/Login_Info"><i class="fa fa-desktop">
                            </i><span>Login Log</span></a>
                        </li>
                        <?php } ?>
                        </ul>
                    </li>

                    <ul id="sidebarnav" class="dropdown dropdown-horizontal">
                        <li class="scroll">
                            <a id="companydrop" class="has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-cogs ">
                            </i><span class="hide-menu">Company Settings</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                            <ul aria-expanded="false" class="collapse dropdown-upward">
                                <li>

                                    <a class="setup" href="<?php echo base_url; ?>/CompanyInformation"><i class="fa fa-info-circle">

                                    </i><span>Information </span></a>
                                </li>
                                <?php 
                                if($_SESSION['usertype']=="subscriber"){
                                    ?>

                                    <li>
                                        <a href="<?php echo base_url; ?>/InvoiceTemplate"><i class="fa fa-file"></i><span> Invoice Template</span></a>
                                    </li>

                                <?php } ?>

                                <li>
                                    <a class="viewser" href="<?php echo base_url; ?>/viewService"><i class="fa fa-list"></i><span> List Of Services</span></a>

                                </li>
                                <li>
                                    <a class="memlist"  href="<?php echo base_url; ?>/MembershipPackageList" aria-expanded="false"><i class="fa fa-user">
                                    </i> Memberships <span></span></a>
                                </li>

                                <li>
                                    <a id="customerdrop" class="has_arrow" href="javascript:void(0)">
                                        <i class="fa fa-comments">
                                        </i> <span>Customer Communication </span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                                        <ul aria-expanded="false" class="collapse">
                                            <li>
                                                <a class="allemail" href="<?php echo base_url; ?>/AllEmailTemp"><i class="fa fa-at">
                                                </i><span> Email</span></a>
                                            </li>
                                            <?php if($_SESSION['usertype']=="subscriber"){ ?>
                                                <li>
                                                    <a class="smsset" href="<?php echo base_url; ?>/SmsSendSetting"><i class="fa fa-envelope">
                                                    </i><span> SMS </span></a>
                                                </li>
                                                <li>
                                                    <a class="eventset" href="<?php echo base_url; ?>/EventSettings"><i class="fa fa-calendar-alt">
                                                    </i><span> Appointment </span></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <li>
                                        <a id="apidrop" class="has_arrow" href="javascript:void(0)"><i class="fa fa-link">
                                        </i> API Links<span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></span></a>
                                        <ul aria-expanded="false" class="collapse">

                                            <?php if($_SESSION['usertype']=="subscriber"){ ?>
                                                <li>
                                                    <a href="<?php echo base_url; ?>/EmailSendSetting"><i class="fa fa-envelope">
                                                    </i><span> Email </span></a>
                                                </li>

                                                <li>
                                                    <a class="sms" href="<?php echo base_url; ?>/SmsSendSetting"><i class="fa fa-comments">
                                                    </i><span> SMS </span></a>
                                                </li>

                                            <?php } ?>

                                            <li>
                                                <a  class="has_arrow" href="javascript:void(0)"><i class="fa fa-database">
                                                </i> Data Backup<span> <i class="fa fa-angle-down angle_down" aria-hidden="true"></i></span></a>
                                                <ul aria-expanded="false" class="collapse">
                                                    <li>
                                                        <a href="<?php echo base_url; ?>/MyBackup"><i class="fa fa-upload">
                                                        </i><span> Backup Contacts  </span></a>
                                                    </li>
                                                    <li>
                                                        <a class="data" href="<?php echo base_url; ?>/ImportWizard"><i class="fa fa-download">
                                                        </i><span> Import Contacts </span></a>
                                                    </li>
                                                </ul>
                                            </li>


                                            <li>
                                                <a class="apipay" href="<?php echo base_url; ?>/paymentsetup"><i class="fa fa-credit-card">
                                                </i><span class="hide-menu"> Payment </span></a>
                                            </li>

                                        </ul>
                                    </li>

                                </ul>
                            </li>
                            <li>
                                <a id="myaccdrop" class="has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-user-circle">
                                </i><span class="hide-menu">My Account</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                                <ul aria-expanded="false" class="collapse dropdown-upward">
                                    <li>
                                        <a class="editpro" href="<?php echo base_url; ?>/Profile"><i class="fa fa-male">
                                        </i><span> View / Edit Profile </span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="archive"  href="<?php echo base_url; ?>/Archive" aria-expanded="false"><i class="fa fa-archive">
                                    </i><span> Archive List</span></a>
                                </li>
                                
                                <li>
                                    <?php 

                                    if(@$_SESSION['usertype'] != 'employee'){

                                        if($employeepermission==1)
                                        {
                                      ?>
                                      <a class="empmanage"  href="<?php echo base_url; ?>/AllEmployees" aria-expanded="false"><i class="fa fa-users">
                                      </i><span>Manage Employee</span></a>

                                      <?php
                                    }
                                  }   
                                  ?>
                              </li>
                              <li>
                                <a class="gethelp" href="<?php echo base_url; ?>/help"><i class="fa fa-question-circle"></i> <span>Get Help!</span> </a>
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
                    <a class="allevent" href="<?php echo base_url; ?>/AllEvent" aria-expanded="false"><i class="fas fa-calendar-alt"></i><span class="hide-menu">Appointments</span></a>
                </li>
                <li> 
                    <a class="package  waves-effect waves-dark" href="<?php echo base_url; ?>/AllPackage" aria-expanded="false"><i class="fas fa-box"></i><span class="hide-menu">Packages</span></a>
                    <ul aria-expanded="false" class="collapse">

                    </ul>
                </li>        
                <li>
                    <a class="allsub" href="<?php echo base_url; ?>/AllSubscriber.php" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">List of Subscribers</span></a>
                </li> 

                <li>
                    <a class="allpost" href="<?php echo base_url; ?>/AllPost.php" aria-expanded="false"><i class="fas fa-sticky-note"></i><span class="hide-menu"> Posts </span></a>
                </li> 


                <li> 
                    <a  class="allfaq waves-effect waves-dark" href="<?php echo base_url; ?>/FaqsList" aria-expanded="false"><i class="fa fa-question-circle"></i><span class="hide-menu"> Get Help! </span></a>

                </li>

                <li>
                    <a class="adminreport has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-table"></i><span class="hide-menu">Reports</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                    <ul aria-expanded="false" class="collapse dropdown-upward">

                        <li>
                         <a class='payment' href="<?php echo base_url; ?>/SubscriberPaymant"><i class="fas fa-dollar-sign"></i><span>Subscription</span></a>
                     </li>

                     <li>
                        <a class="payment" href="<?php echo base_url; ?>/EventList"><i class="fas fa-calendar-alt">
                        </i><span> Event List </span></a>
                    </li>

                    <li>
                        <a class="transaction" href="<?php echo base_url; ?>/OrderList"><i class="fa fa-list-alt"></i><span> Order List</span></a>
                    </li>  

                    <li>
                        <a class="productsales" href="<?php echo base_url; ?>/Product_Sales"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span> Sales Report</span></a>
                    </li>

                    <li>    
                        <a class="payment " href="<?php echo base_url; ?>/PaymentList"><i class="fa fa-credit-card" aria-hidden="true"></i><span>Payment List</span></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url; ?>/Login_Info"><i class="fa fa-desktop">
                        </i><span>Login Log</span></a>
                    </li>


                </ul>
            </li>   


            <li>
                <a class="adminapi has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cog"></i><span class="hide-menu">API</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                <ul aria-expanded="false" class="collapse">
                    <li>
                        <a class="setemail" href="<?php echo base_url; ?>/EmailSetting" aria-expanded="false"><i class="fa fa-envelope"></i> Email Setting</span></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url; ?>/paymentsetup"><i class="fa fa-credit-card"></i><span class="hide-menu"> Payment Setup</span></a>
                    </li>

                    <li>
                        <a class="sms" href="<?php echo base_url; ?>/SmsSendSetting"><i class="fa fa-comments">
                        </i><span class="hide-menu"> SMS Setup</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="adminpage has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-cog"></i><span class="hide-menu">Page Setting</span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                <ul aria-expanded="false" class="collapse">

                 <li>
                     <a class="setdash"  href="<?php echo base_url; ?>/dashbordsetting"><i class="fas fa-tachometer-alt"></i><span> Dashboard</span></a>
                 </li>   

                 <li>
                     <a class="setclient" href="<?php echo base_url; ?>/clientsetting"><i class="fa fa-address-book"></i><span> Clients</span></a>
                 </li>

                 <li>
                     <a class="setappo" href="<?php echo base_url; ?>/appointmentsettings"><i class="fas fa-calendar-alt"></i><span> Appointment</span></a>
                 </li>

                 <li>
                     <a class="setpro" href="<?php echo base_url; ?>/productcsettings"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span> Product</span></a>
                 </li>

                 <li>
                     <a class="setser"  href="<?php echo base_url; ?>/servicessettings"><i class="fab fa-servicestack"></i><span> Services</span></a>
                 </li>

                 <li>
                     <a class="setmem" href="<?php echo base_url; ?>/membershipsettings"><i class="fa fa-user-plus" aria-hidden="true"></i><span> Membership</span></a>
                 </li>

                 <li>
                    <a class="order" class="has_arrow" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-shopping-basket">
                    </i><span> Order </span><i class="fa fa-angle-down angle_down" aria-hidden="true"></i></a>
                    <ul aria-expanded="false" class="collapse">

                        <li>
                            <a class="setorder" href="<?php echo base_url; ?>/ordersettings"><i class="fa fa-shopping-bag"></i><span> Order</span></a>
                        </li>

                        <li>
                            <a class="setolist" href="<?php echo base_url; ?>/Orderlistsettings"><i class="fa fa-list"></i><span> List of Order</span></a>
                        </li>

                    </ul>
                </li>
                <li>
                    <a class="settodo" href="<?php echo base_url; ?>/todosettings.php" aria-expanded="false"><i class="fa fa-tasks"></i> To-Do</span></a>
                </li>

                <li>
                    <a class="setprofile" href="<?php echo base_url; ?>/profilesetting" aria-expanded="false"><i class="ti-user"></i> Profile</span></a>
                </li>

                <li>
                    <a class="setnote" href="<?php echo base_url; ?>/notesettings" aria-expanded="false"><i class="fa fa-sticky-note"></i> Note</span></a>
                </li>

                <li>
                    <a class="setcom" href="<?php echo base_url; ?>/Communicationsettings" aria-expanded="false"><i class="fa fa-sticky-note"></i> Communication</span></a>
                </li>

                <li>
                    <a class="setapi" href="<?php echo base_url; ?>/apisettings" aria-expanded="false"><i class="fa fa-link"></i> Api</span></a>
                </li>

                <li>
                    <a class="setback" href="<?php echo base_url; ?>/databackupsettings" aria-expanded="false"><i class="fa fa-database"></i> Data Backup</span></a>
                </li>

                <li>
                    <a class="setemp" href="<?php echo base_url; ?>/Employeessettings" aria-expanded="false"><i class="fa fa-user"></i> Employees</span></a>
                </li>

                <li>
                    <a class="setfaq" href="<?php echo base_url; ?>/faqssettings" aria-expanded="false"><i class="fa fa-question-circle"></i> FAQS</span></a>
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



        $(".has_arrow").click(function(){
            
            $(this).find('.angle_down').toggleClass('angle_up');
            setTimeout(function(){
                $("#sidebarnav .has_arrow").each(function(){
                    if($(this).hasClass('active')){
                        $(this).find('.angle_down').addClass('angle_up');
                    }else{
                        $(this).find('.angle_down').removeClass('angle_up');
                    }
                });
            },500);
        });

        setTimeout(function(){$('.has_arrow.active').find('.angle_down').addClass('angle_up')},1000);

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
