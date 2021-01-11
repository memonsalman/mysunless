<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `MemberPackage` WHERE Name=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$todocreateprmistion=$result['MemberPackageCreate'];
}
if(isset($_GET["id"])){
    $myevent = $_GET["id"];
}else{
    $myevent = "new";
}
$action="";
if(isset($_GET["action"])){
    $action=$_GET["action"];
}
$Name = "";
$Price = "";
$Tracking = "";
$Description = "";
if($action == "edit"){
    $EditEvent=$db->prepare("select * from `MemberPackage` where id=:myevent");
    $EditEvent->bindValue(":myevent",$myevent, PDO::PARAM_INT);
    $EditEvent->execute();
    if($EditEvent->rowCount() > 0){
        while($GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC)){
            $Name=$GetEvent['Name'];
            $Price=$GetEvent['Price'];
            $Tracking=$GetEvent['Tracking'];
            $Description=$GetEvent['Description'];
        }
    }
}
if($action == "delete"){

    $DeleteClient = $db->prepare("delete from `MemberPackage` where id=:myevent");
    $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
    $DeleteClient->execute();
    header('Location: MembershipPackageList.php');
}

if(isset($_REQUEST['dlink']))
{

    @$cid=base64_decode($_REQUEST['dlink']);

    $stmt=$db->prepare("UPDATE OrderMembership SET Active='0' WHERE Cid=:cid");
    $stmt->bindparam(":cid",$cid);
    $stmt->execute();
    if($stmt)
    {
        echo json_encode(['resonse'=>'You package has been successfuly Canceled']);die;
    }
}

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C38'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];

    $title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='13'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];

?>

    <?php
include 'head.php';
    ?>
    <style type="text/css">
        .img-circle {
            object-fit: cover;
        }
        select#CurrentAndPaidDays,select#UpcomingRenewalsDays{width: 40%; float: right;}

        @media (max-width: 500px){
        select#CurrentAndPaidDays,select#UpcomingRenewalsDays{width: 100%; float: right;}            
        }
        th { font-weight: bold!important;color:#0b59a2!important;}
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <body class="skin-default fixed-layout mysunlessA14">
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
                                <?php echo $Ti2; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <a href="<?php echo base_url ;?>/MembershipPackageList" class="btn btn-info m-r-10 pull-right"> <?php echo $B1; ?></a>
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item">
   <a class="nav-link" data-toggle="tab" href="#CurrentAndPaidTab" role="tab"><span class="hidden-xs-down"> Active </span></a> 
                                        </li>
                                        <li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#UpcomingRenewalsTab" role="tab"> <span class="hidden-xs-down">Upcoming Renewals </span></a>                                            
                                        </li>
                                        <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#NotPaidTab" role="tab"><span class="hidden-xs-down">Complete/Expired</span></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tabcontent-border">
                                        <!--  start Upcoming Renewals tab -->
                                        <div class="tab-pane active" id="UpcomingRenewalsTab" role="tabpanel">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <select class="custom-select ml-auto" id="UpcomingRenewalsDays">
                                                        <option value="1">IN 1 Day</option>
                                                        <option value="2">In 2 Days</option>
                                                        <option value="3">In 3 Days</option>
                                                        <option value="7">In 7 Days</option>
                                                        <option value="10">In 10 Days</option>
                                                        <option selected value="15">In 15 Days</option>
                                                    </select>
                                                </div>
                                                <div class="table-responsive m-t-40">
                      <table id="UpcomingRenewalsTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    Client Info
                                                                </th>
                                                                <th>
                                                                    Selected Package
                                                                </th>
                                                                <th>
                                                                    Package Start Date
                                                                </th>
                                                                <th>
                                                                    Package End Date
                                                                </th>

                                                                <th>
                                                                   # of service remaining
                                                                </th>
                                                                <th>
                                                                    Employee Sold 
                                                                </th>
                                                                <th>
                                                                    Action 
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End Upcoming Renewals tab -->
                                        <!--  Start Current And Paid tab -->
                                        <div class="tab-pane" id="CurrentAndPaidTab" role="tabpanel">
                                            <div class="col-md-12 col-lg-12">
                                                <!-- <div class="d-flex align-items-center">
                                                    <select class="custom-select  ml-auto" id="CurrentAndPaidDays">
                                                        <option selected value="16">After 16 Days</option>
                                                        <option value="20">After 20 Days</option>
                                                        <option value="25">After 25 Days</option>
                                                        <option value="30">After 1 Month</option>
                                                    </select>
                                                </div> -->
                                                <div class="table-responsive m-t-40">
                       <table id="CurrentAndPaidTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100% !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    Client Info
                                                                </th>
                                                                <th>
                                                                    Selected Package
                                                                </th>
                                                                <th>
                                                                    Package Start Date
                                                                </th>
                                                                <th>
                                                                    Package End Date
                                                                </th>
                                                                <th>
                                                                   # of service remaining
                                                                </th>

                                                                <th>
                                                                    Employee Sold 
                                                                </th>
                                                                <th>
                                                                    Action 
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End Current And Paid tab -->
                                        <!--  start Not Paid tab  -->
                                        <div class="tab-pane" id="NotPaidTab" role="tabpanel">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="table-responsive m-t-40">
                                      <table id="NotPaidTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    Client Info
                                                                </th>
                                                                <th>
                                                                    Selected Package
                                                                </th>
                                                                <th>
                                                                    Package Start Date
                                                                </th>
                                                                <th>
                                                                    Package End Date
                                                                </th>

                                                                <th>
                                                                   # of service remaining
                                                                </th>

                                                                <th>
                                                                    Employee Sold 
                                                                </th>

                                                                <th>
                                                                    Action 
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End Not Paid tab -->
                                    </div>
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
        
        <?php require_once 'viewclientdetail.php'; ?>

        <script src="
<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                //active class
                setInterval(function(){$(".membership").addClass("active");}, 10);

                UpcomingRenewalsDataTable(15);
                $('#UpcomingRenewalsDays').change(function(){
                    var UpcomingRenewalsDays = (this).value ;
                    UpcomingRenewalsDataTable(UpcomingRenewalsDays);
                }
                                                 );
                function UpcomingRenewalsDataTable(UpcomingRenewalsDays){
                    $('#UpcomingRenewalsTable').DataTable({
                        "responsive": true,
                        "processing" : true,
                        "destroy": true,
                        "columnDefs": [
                          {"className" : 'text-center', "targets" : '_all'},
                          ],
                        "ajax" : {
                            "type" : "POST",
                            "url" : "<?php echo EXEC; ?>Exec_Memberships_List.php?UpcomingRenewals="+UpcomingRenewalsDays,
                            "dataSrc" : ''
                        }
                        ,
                        "columns" : [ {
                            "data" : {
                                ProfileImg: "ProfileImg",FirstName: "FirstName",LastName: "LastName",cid:"cid"}
                            ,
                            "render": function(data, row, type) {
                            var  encodedId = btoa(data.cid);
                                
                                if(data.ProfileImg!=''){
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" title="View Customer Profile" data-cid="'+encodedId+'" class="img-circle viewInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></div> </div>';
                                }
                                else
                                {
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" title="View Customer Profile" data-cid="'+encodedId+'" class="img-circle viewInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></div></div>';
                                }
                            }
                        }
                                     ,{
                                         "data" : "Name",
                                     }
                                     ,
                                     {
                                         "data" : {package_start_date:"package_start_date"},
                                         "render": function (data,type,row)
                                         {
                                             return  moment(data.package_start_date).format('MM-DD-YYYY');
                                         }
                                     }
                                     ,
                                     {
                                         "data" : {package_expire_date:"package_expire_date"},
                                         "render": function (data,type,row)
                                         {
                                            if(data.package_expire_date == "Never")
                                            {
                                                return data.package_expire_date;
                                            }
                                            else
                                            {
                                                return  moment(data.package_expire_date).format('MM-DD-YYYY');
                                            }
                                         }
                                     },
                                     {
                                         "data" : "Noofvisit",
                                     },
                                     {
                                         "data": "UserName",
                                     },
                                      {
                                          "data": "cid",
                                           "render": function(data, type, row) {
                                               var encodedId = window.btoa(data);
                                                return '<a class="btn btn-info btn-sm cutbut" title="Add New Package" href=Order?vcid='+ encodedId + '> <span class="fa fa-refresh"><span> </a> <a href="#" id="deleteButton2" title="Cancel" class="btn btn-danger btn-sm delete cutbut" data-id="' + encodedId + '"><span class="fa fa-times"></span></a>' ;
                                           }
                                      }


                                    ]
                    });
                }


                 $(document).on('click','#deleteButton2',function(e){
      e.preventDefault();
      $(".Loader").show();
       var dlink = $(this).attr('data-id');

        swal({  
                title: "Are you sure?",
                text: "Once Canceled, you will lost all data of this Member Package",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                    
                                 $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'dlink':dlink},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                          $(".Loader").hide();
                                        
                                            swal(data.resonse);  
                                            
                                            UpcomingRenewalsDataTable(15);
                                            
                                        }
                                        else if(data.error){
                                            alert("else if");
                                            
                                         $(".Loader").hide();
                                            swal('Something is wrong please try agine')
                                            UpcomingRenewalsDataTable(15);
                                            // alert('<li>'+data.error+'</li>');
                                        }
                                    }
                          });
                }
                 else{
                  $(".Loader").hide();
                     return false ;
                 }
            });
   });

            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                CurrentAndPaidDataTable(1000);
                $('#CurrentAndPaidDays').change(function(){
                    var CurrentAndPaidDays = (this).value ;
                    CurrentAndPaidDataTable(CurrentAndPaidDays);
                }
                                               );
                function CurrentAndPaidDataTable(CurrentAndPaidDays){
                    $('#CurrentAndPaidTable').DataTable({
                        "responsive": true,
                        "processing" : true,
                        "destroy":true,
                        "autoWidth": false,
                        "columnDefs": [
                          {"className" : 'text-center', "targets" : '_all'},
                          { "width": "12%", "targets": 0 },
                          { "width": "0%", "targets": 1 },
                          { "width": "15%", "targets": 2 },
                          { "width": "17%", "targets": 3 },
                          { "width": "16%", "targets": 4 },
                          { "width": "20%", "targets": 5 },
                          { "width": "20%", "targets": 6 },
                          
                          ],
                        "ajax" : {
                            "type" : "POST",
                            "url" : "<?php echo EXEC; ?>Exec_Memberships_List.php?CurrentAndPaid="+ CurrentAndPaidDays,
                            'dataSrc' : ''
                        }
                        ,
                        "columns" : [ {
                            "data" : {
                                ProfileImg: "ProfileImg",FirstName: "FirstName",LastName: "LastName" }
                            , 
                            "render": function(data, row, type) {
                                    var  encodedId = btoa(data.cid);
                                
                                if(data.ProfileImg!=''){
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" title="View Customer Profile" data-cid="'+encodedId+'" class="img-circle viewInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></div> </div>';
                                }
                                else
                                {
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" title="View Customer Profile" data-cid="'+encodedId+'" class="img-circle viewInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></div></div>';
                                }
                            }
                        }
                                     ,{
                                         "data" : "Name",
                                     },
                                     {
                                         "data" : {package_start_date:"package_start_date"},
                                         "render": function (data,type,row)
                                         {
                                             return  moment(data.package_start_date).format('MM-DD-YYYY');
                                         }
                                     }
                                     ,
                                     {
                                         "data" : {package_expire_date:"package_expire_date"},
                                         "render": function (data,type,row)
                                         {
                                            if(data.package_expire_date == "Never")
                                            {
                                                return data.package_expire_date;
                                             /*return  moment(data.package_start_date).format('MM-DD-YYYY');*/
                                            }
                                            else
                                            {
                                                return  moment(data.package_expire_date).format('MM-DD-YYYY');
                                            }
                                         }
                                     },
                                     {
                                         "data" : "Noofvisit",
                                     },
                                     {
                                         "data": "UserName",
                                     },

                                         {
                                          "data": "cid",
                                           "render": function(data, type, row) {
                                               var encodedId = window.btoa(data);
                                                return '<a class="btn btn-info btn-sm cutbut" title="Add New Package" href=Order?vcid='+ encodedId + '> <span class="fa fa-refresh"><span> </a> <a href="#" id="deleteButton2" title="Cancel" class="btn btn-danger btn-sm delete cutbut" data-id="' + encodedId + '"><span class="fa fa-times"></span></a>' ;
                                           }
                                      }



                                    ]
                    });
                }


                 $(document).on('click','#deleteButton',function(e){
      e.preventDefault();
      $(".Loader").show();
       var dlink = $(this).attr('data-id');

        swal({  
                title: "Are you sure?",
                text: "Once Canceled, you will lost all data of this Member Package",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                    
                                 $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'dlink':dlink},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                          $(".Loader").hide();
                                            swal(data.resonse)  

                                            datatable();
                                            
                                        }
                                        else if(data.error){
                                            
                                         $(".Loader").hide();
                                            swal('Something is wrong please try agine')
                                            datatable();
                                            //  alert('<li>'+data.error+'</li>');
                                        }
                                    }
                          });
                }
                 else{
                  $(".Loader").hide();
                     return false ;
                 }
            });
   });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                // NotPaidDataTable(16);
                // $('#NotPaidDays').change(function(){
                //     var NotPaidDays = (this).value ;
                //     NotPaidDataTable(NotPaidDays);
                // });
                // function NotPaidDataTable(NotPaidDays){
                    datatable()
                    function datatable(){
                                    $('#NotPaidTable').DataTable({
                    "responsive": true,
                    "processing" : true,
                    "destroy": true,
                    "columnDefs": [
                          {"className" : 'text-center', "targets" : '_all'},
                          ],
                    "ajax" : {
                        "type" : "POST",
                        "url" : "<?php echo EXEC; ?>Exec_Memberships_List.php?NotPaid=",
                        'dataSrc' : ''
                    }
                    ,
                    "columns" : [ {
                        "data" : {
                            ProfileImg: "ProfileImg",FirstName: "FirstName",LastName: "LastName" }
                        ,   
                        "render": function(data, row, type) {
                                    var  encodedId = btoa(data.cid);
                                
                                if(data.ProfileImg!=''){
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle viewInfo" title="View Customer Profile" data-cid="'+encodedId+'" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></div> </div>';
                                }
                                else
                                {
                                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" title="View Customer Profile" data-cid="'+encodedId+'" class="img-circle viewInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></div></div>';
                                }
                            }
                    }
                                 ,{
                                     "data" : "Name",
                                 }
                                 ,
                                 {
                                         "data" : {package_start_date:"package_start_date"},
                                         "render": function (data,type,row)
                                         {
                                             return  moment(data.package_start_date).format('MM-DD-YYYY');
                                         }
                                     }
                                     ,
                                     {
                                         "data" : {package_expire_date:"package_expire_date"},
                                         "render": function (data,type,row)
                                         {
                                            if(data.package_expire_date == "Never")
                                            {
                                                return data.package_expire_date;
                                             /*return  moment(data.package_start_date).format('MM-DD-YYYY');*/
                                            }
                                            else
                                            {
                                                return  moment(data.package_expire_date).format('MM-DD-YYYY');
                                            }
                                         }
                                     },
                                 {
                                         "data" : "Noofvisit",
                                 },
                                 {
                                     "data": "UserName",
                                 },

                                   {
                                         "data": "cid",
                                          "render": function(data, type, row) {
                                              var encodedId = window.btoa(data);
                                               return '<a class="btn btn-info btn-sm cutbut" title="Add New Package" href=Order?vcid='+ encodedId + '> <span class="fa fa-refresh"><span> </a> <a href="#" id="deleteButton" title="Cancel" class="btn btn-danger btn-sm delete cutbut" data-id="' + encodedId + '"><span class="fa fa-times"></span></a>' ;
                                          }
                                     }
                                ]               });
                        }
        
                // }

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust()
           .responsive.recalc();
    });    

                 $(document).on('click','#deleteButton',function(e){
      e.preventDefault();
      $(".Loader").show();
       var dlink = $(this).attr('data-id');

        swal({  
                title: "Are you sure?",
                text: "Once Canceled, you will lost all data of this Member Package",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                    
                                 $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'dlink':dlink},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                          $(".Loader").hide();
                                            swal(data.resonse)  

                                            datatable();
                                            
                                        }
                                        else if(data.error){
                                            
                                         $(".Loader").hide();
                                            swal('Something is wrong please try agine')
                                            datatable();
                                            // alert('<li>'+data.error+'</li>');
                                        }
                                    }
                          });
                }
                 else{
                  $(".Loader").hide();
                     return false ;
                 }
            });
   });

            });
</script>
</body>
</html>