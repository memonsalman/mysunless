<?php
$db=new db();
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk = :id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // @$compimg=$result['compimg'];
    @$ctheme=$result['ctheme'];
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
    @$todocreateprmistion=$result['TodoCreate'];
    @$clientcreateprmistion=$result['ClientCreate'];
    @$sercreateprmistion=$result['ServicesCreate'];
    //  @$compimg=$result['compimg'];
}
if($ctheme=="2")
    {?>
        <style type="text/css">
            .sidebartogale,.left-sidebar{
                display: none;
            }
            .page-titles{
                margin-top:61px!important;
            }
            footer.footer,.page-wrapper{
                margin: 0!important;
            }


            /*Update css*/
            .sec-navabr {
                background: white!important;
                padding: 0px 15px;
                /*justify-content: normal;*/
                padding-right: 15px !important;
            }
            .sec-navabr i{
                margin-right:5px;
            }
            .sec-navabr>li,.sec-navabr>.dropdown{
                font-size: 16px;
                padding: 17px 15px;
                border-left: 0;
                border-bottom: 3px solid transparent;
                transition: background 2s;
                transition: border-color 1s;
            }
            .sec-navabr .dropdown-content{
                left:0px;
                top:60px;
                background-color:white!important;
            }
            .sec-navabr .dropdown-content li{
                border-bottom: 3px solid transparent;
                transition: background 2s;
                transition: border-color 1s;
            }
            .sec-navabr li:hover , .sec-navabr .dropdown-content a:hover, .sec-navabr .dropdown:hover{
             color: #3cabe1 !important;
             background: #f3f3f3 !important;
             border-color: #3cabe1;
             font-weight: 600;
         }
         .sec-navabr li:hover a , .sec-navabr li:hover span ,.sec-navabr .dropdown-content a:hover i{
             color: #3cabe1 !important;
         }

         .sec-navabr a,.sec-navabr span{
            color: #878e9e;
            font-size: 15px;
            font-weight: 500;

        }
        .sec-navabr .dropdown-content li{
            text-align: left!important;
        }
        .sec-navabr .active{
            border-color: #3cabe1 !important;
            font-weight: 600;
        }    

        .sec-navabr .dropbtn{
            padding: 0px;
        }
        .dropdown-submenu .dropdown-menu{
            padding: 0px;
        }

    </style>
    <?php
}
?>


<ul class="navbar sec-navabr">
    <li>
        <a href="<?php echo base_url; ?>/dashboard"><i class="fa fa-home">
        </i><span>
            Dashboard 
        </span></a>
    </li>
    <?php 
    if(@$_SESSION['usertype']!=="Admin")
    {   
        if($clientcreateprmistion==1)
        {
            ?>
            <li>
                <a href="<?php echo base_url; ?>/AllClients" aria-expanded="false"><i class="fas fa-users"></i><span>
                    Clients
                </span></a>
            </li>
            <?php
        }
        ?>
        <?php
        if($schcreateprmistion==1)
            ?>
        <li>
            <a href="<?php echo base_url; ?>/AllEvent" aria-expanded="false"><i class="fas fa-calendar-check"></i>
                <span>
                    Appointments
                </span></a>
            </li>
            <?php
        }
        ?>
<!--     <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="javascript:void(0)" aria-expanded="false"><i class="fa fa-money-bill-alt">
                    </i><span>
                    Point of Sale
                    </span></a>
            </li>
        </button>
        <div class="dropdown-content sb-css">
            <li class="dropdown-submenu">
                <a class="" href="<?php echo base_url; ?>/AllProduct.php"><span> Product </span></a>
                
            </li>
            <li class="dropdown-submenu">
                <a class="sb-menu" href="<?php echo base_url; ?>/OrderList.php"><i class="fa fa-shopping-basket has-arrow">
                    </i><span> Order </span></a>
                <ul class="dropdown-menu">
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

        </div>
    </div> -->
<!--     <li>
        <div class="dropdown">
            <button class="dropbtn">
                <li>
                    <a href="<?php echo base_url; ?>/Order"><span><i class="fas fa-shopping-cart"></i><span>
                        Checkout
                    </span></a>
                </li>
            </button>
         <div class="dropdown-content sb-css">
            <li class="dropdown-submenu">
                <a class="" href="<?php echo base_url; ?>/AllProduct.php"><span> Product </span></a>
                
            </li>
            <li class="dropdown-submenu">
                <a class="sb-menu" href="<?php echo base_url; ?>/OrderList.php"><i class="fa fa-shopping-basket has-arrow">
                    </i><span> Order </span></a>
                <ul class="dropdown-menu">
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

        </div>
    </div>
</li> -->
<div class="dropdown">
    <button class="dropbtn">
        <li>
            <a href="javascript:void(0)"><span><i class="fas fa-shopping-cart"></i><span>
                Checkout
            </span></span></a>
        </li>
    </button>
    <div class="dropdown-content">
        <li>
            <a href="<?php echo base_url; ?>/Order"><span><i class="fa fa-shopping-basket"></i></span> Order</a>
        </li>
        <li>
            <a href="<?php echo base_url; ?>/OrderList"><span><i class="fa fa-list-alt"></i></span> Order List</a>
        </li>
    </div>

</div>

<li>
    <a href="<?php echo base_url; ?>/Memberships" aria-expanded="false"><i class="fas fa-box-open"></i><span>
        Package 
    </span></a>
</li>
<?php
if($todocreateprmistion==1)
{
    ?>
    <li>
        <a href="<?php echo base_url; ?>/todo" aria-expanded="false"><i class="fas fa-tasks"></i><span>
            To-Do
        </span></a>
    </li>
<?php } ?>
<li>
 <a class="allproduct" href="<?php echo base_url; ?>/Product" aria-expanded="false"><i class="fas fa-dolly-flatbed"></i><span class="hide-menu"> Inventory</span></a>
</li>
    <!-- <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="javascript:void(0)" aria-expanded="false"><i class="fa fa-handshake-o">
                    </i><span>
                    Marketing
                    </span></a>
            </li>
        </button>
        <div class="dropdown-content">
            <a href="<?php echo base_url; ?>/AllCampaigns.php"><span><i class="fa fa-cubes">
                </i><span> Campaign Management </span></a>
            <a  href="javascript:void(0)"><i class="fa fa-industry">
                </i><span> Offline Marketing  </span></a>
        </div>
    </div> -->
    <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="#" aria-expanded="false"><i class="fas fa-book"></i><span>
                    Reports
                </span></a>
            </li>
        </button>
        <div class="dropdown-content">
            <!-- <a href="<?php echo base_url; ?>/AllActivites.php"><i class="fa fa-clock-o">
                </i><span> Activities </span></a>
            <a href="<?php echo base_url; ?>/organizational_chart.php"><i class="fa fa-sitemap">
                </i><span> Organizational Chart </span></a>
            <a href="<?php echo base_url; ?>/Commission.php"><i class="fa fa-trophy">
            </i><span> Commission </span></a> -->
            <li>
                <a href="<?php echo base_url; ?>/OrderList"><span><i class="fa fa-list-alt"></i></span> Order List</a>
            </li>
            <li>
                <a class="payment" href="<?php echo base_url; ?>/EventList"><i class="fas fa-calendar-alt">
                </i><span> Event List </span></a>
            </li>
            <li>
                <a href="<?php echo base_url; ?>/Product_Sales"><span><i class="fa fa-shopping-cart" aria-hidden="true"></i></span> Product Sales</a>
            </li>
            <li>
                <a class="payment" href="<?php echo base_url; ?>/PaymentList"><span><i class="fa fa-credit-card">
                </i></span> Payment </a>
            </li>
            <li>
                <a href="<?php echo base_url; ?>/Performance"><span><i class="fa fa-tachometer">
                </i></span>  Performance </a>
            </li>
      <!--       <li>
                <a href="<?php echo base_url; ?>/Sales"><span><i class="fa fa-shopping-bag">
                </i></span> Sales</a>
            </li> -->
        </div>
    </div>
    <!-- <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a  href="javascript:void(0)" aria-expanded="false"><i class="fa fa-briefcase">
                    </i><span>
                    Resources
                    </span></a>
            </li>
        </button>
        <div class="dropdown-content">
            <a href="javascript:void(0)"><i class="fa fa-list-ol">
                </i><span> Spray Tanning Tips </span></a>
            <a href="javascript:void(0)"><i class="fa fa-industry">
                </i><span> Marketing Your Business </span></a>
            <a href="javascript:void(0)"><i class="fa fa-support">
                </i><span> Equipment Support </span></a>
            <a href="javascript:void(0)"><i class="fa fa-plus-square">
                </i><span> Other </span></a>
        </div>
    </div> -->
    <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="javascript:void(0)" aria-expanded="false">
                    <i class="fa fa-info-circle">
                    </i>
                    <span>
                        Company Setting
                    </span>
                </a>
            </li>
        </button>
        <div class="dropdown-content sb-css">
            <li>
                <a href="<?php echo base_url; ?>/CompanyInformation"><span><i class="fas fa-cogs"></i></span> Information </a>
            </li>
            <?php 
            if($_SESSION['usertype']=="subscriber"){
                ?>

                <li>
                    <a href="<?php echo base_url; ?>/InvoiceTemplate"><span><i class="fa fa-file"></i></span> Invoice Template</a>
                </li>

            <?php } ?>
            <!-- <li>
                <a href="javascript:void(0)"><i class="fa fa-wrench">
                    </i><span> Employee Settings &amp; Roles </span></a>
                </li> -->
           <!--  <li>
                <a href="javascript:void(0)"><i class="fa fa-file-word-o">
                    </i><span> Release Forms </span></a>
                </li> -->
                <li class="dropdown-submenu">
                 <a href="<?php echo base_url; ?>/viewService"><span><i class="fa fa-list"></i></span> List Of Services</a>
                <!-- <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo base_url; ?>/AllCategory.php"><span><i class="fa fa-list">
                            </i></span> List of Product </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url; ?>/viewService.php"><span><i class="fa fa-list">
                            </i></span> List Of Services</a>
                    </li>
                </ul> -->
            </li>
            <li>
                <a href="<?php echo base_url; ?>/MembershipPackageList"><i class="fa fa-user">
                </i><span> Memberships </span></a>
            </li>
            <!-- <li>
                <a href="javascript:void(0)"><i class="fa fa-calendar-plus-o">
                    </i><span> Appointment / Calendar </span></a>
            </li>
            <li>
                <a href="<?php echo base_url; ?>/Alltag.php"><i class="fa fa-tags">
                    </i><span> Tag Management </span></a>
            </li>
            <li>
                <a href="<?php echo base_url; ?>/AddNote.php"><i class="fa fa-sticky-note">
                    </i><span> Note Management </span></a>
                </li> -->
                <li class="dropdown-submenu">
                    <a class="sb-menu" href="javascript:void(0)"><i class="fa fa-comments has-arrow">
                    </i><span> Customer Communication </span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo base_url; ?>/AllEmailTemp"><span><i class="fa fa-at">
                            </i></span> Email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url; ?>/SmsSendSetting"><span><i class="fa fa-envelope">
                            </i></span> SMS </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url; ?>/EventSettings"><span><i class="fa fa-calendar-alt">
                            </i></span> Appointment </a>
                        </li>
                    <!-- <li>
                        <a href="javascript:void(0)"><span><i class="fa fa-share">
                            </i></span> Point of Sale</a>
                        </li> -->
                    </ul>
                </li>
                <li class="dropdown-submenu">
                    <a class="sb-menu" href="javascript:void(0)"><i class="fa fa-link has-arrow">
                    </i><span> API Links </span></a>
                    <ul class="dropdown-menu apiliup">
                     <?php if($_SESSION['usertype']=="subscriber"){ ?>
                        <li>
                            <a href="<?php echo base_url; ?>/EmailSendSetting"><span><i class="fa fa-envelope">
                            </i></span> Email</a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo base_url; ?>/SmsSendSetting"><span><i class="fa fa-comments">
                        </i></span> SMS </a>
                    </li>
                    <li>
                       <a href="<?php echo base_url; ?>/MyBackup"><i class="fa fa-upload">
                       </i><span> Backup Contacts </span></a>
                   </li>
                   <li>
                    <a href="<?php echo base_url; ?>/ImportWizard"><span><i class="fa fa-download">
                    </i></span> Import Contacts </a>
                </li>
                <li>
                    <a href="<?php echo base_url; ?>/paymentsetup"><span><i class="fa fa-credit-card">
                    </i></span> Payment </a>
                </li>
            </ul>
        </li>
    </div>
</div>
<div class="dropdown">
    <button class="dropbtn">
        <li>
            <a href="javascript:void(0)" aria-expanded="false"><i class="fas fa-user-circle"></i><span>
                My Account
            </span></a>
        </li>
    </button>
    <div class="dropdown-content">
        <a href="<?php echo base_url; ?>/Profile"><i class="fa fa-male">
        </i></span> View / Edit Profile 
    </span>
</a>


<a class="archive"  href="<?php echo base_url; ?>/Archive" aria-expanded="false"><i class="fa fa-archive">
</i><span> Archive List</span></a>


<?php 
if(@$_SESSION['usertype']=="subscriber" or @$_SESSION['usertype']=="user")
{
    ?>
    <a href="<?php echo base_url; ?>/AllEmployees"><i class="fa fa-user">
    </i><span> Employee Management </span></a>
    <?php
}
?>
<!-- <a href="javascript:void(0)"><i class="fa fa-rss"></i></span> Subscription </span></a> -->
<a href="<?php echo base_url; ?>/help"><i class="fa fa-question-circle">
</i></span> Get Help! 
</span>
</a>
</div>
</div> 

<?php

if(@$_SESSION['oldusertype']=="subscriber")
{
    ?>
    <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="#" data-id="<?php echo $_SESSION['olduserid'] ?>" data-username="<?php echo $_SESSION['oldusername'] ?>" data-usertype="<?php echo $_SESSION['oldusertype'] ?>" id="switch2" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu"> Switch Subscriber </span></a>
            </li> 
        </button>

    </div> 
    <?php
}
?>



<?php

if(@$_SESSION['superusertype']=="Admin")
{
    ?>
    <div class="dropdown">
        <button class="dropbtn">
            <li>
                <a href="#" data-id="<?php echo $_SESSION['superuserid'] ?>" data-username="<?php echo $_SESSION['superusername'] ?>" data-usertype="<?php echo $_SESSION['superusertype'] ?>" id="switch2" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu"> Switch Admin </span></a>
            </li> 
        </button>

    </div> 
    <?php
}
?>



</ul>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
    $(document).ready(function(){

        // Active navbar
        var url = location.pathname.split("/");
        url = url[url.length-1];
        $(".sec-navabr li").each(function(){ 
            var link = $(this).find("a").attr("href").split("/");
            link = link[link.length-1].split(".")[0];
            if(link==url){
                if($(this).parents(".dropdown").hasClass("dropdown")){
                    $(this).parents(".dropdown").addClass("active");
                }
                console.log("2");
                $(this).addClass("active");
                return false;
            }
        });


        // console.log(link[link.length-1]);


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
    // this script is for submenu inside submneu
    $(document).ready(function(){
        $('.dropdown-submenu a.sb-menu').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        }
        );
    }
    );
    // end here submenu script
</script>
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
                setTimeout(function () { window.location.href = "dashboard.php";  }, 1000);
            }
        }) 
        });
    });


</script>