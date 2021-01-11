<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
$db=new db();



if(isset($_GET['ServiceTableDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = "Service.createdfk IN (select id from users where id=$id or adminid=$id) AND";
    }


    $query = $db->prepare("SELECT Service.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg,(SELECT GROUP_CONCAT(username) FROM users WHERE FIND_IN_SET(id,Service.Users)) as userbane FROM `Service` join users on Service.createdfk=users.id where $id Service.isactive=0");

    $query->bindParam(':id', $id);

    $query->execute();

    $result = $query->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_REQUEST['ServiceRestore']))
{
    $id = $_POST["id"] ;
    $query = $db->prepare("Update Service set isactive=1 where id IN ($id)");
    $deletefile=$query->execute();
    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Service successfully restore to Service List']);die;
    }
    else
    {
        echo  json_encode(["error"=>'done']);die;
    }
}

if(isset($_GET['UserDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $usertype = "where (adminid NOT IN (SELECT id FROM Delete_User) and usertype='employee') or usertype='subscriber' ";
    }else if ($_SESSION['usertype']=='subscriber'){
        $id = $_SESSION['UserID'];
        $usertype = "where usertype='employee' and adminid=$id ";
    }else
    {
        die;
    }

    $selectQuery = $db->prepare("SELECT * from Delete_User $usertype ");
    
    $selectQuery->execute();
    $result = $selectQuery->fetchAll();
    echo json_encode($result);die;  
}

if(isset($_GET['UserRestore']))
{   
    $id =$_POST["id"];


  $Insert=$db->prepare("Insert users SELECT * from Delete_User where id IN ($id) or adminid IN ($id) ");
  $run = $Insert->execute();  


  if($run){  
    $date = date('Y-m-d H:i:s');
    $Update=$db->prepare("Update users set updated_at=:date where id IN ($id) or adminid IN ($id) ");
    $Update->bindValue(":date",$date);
    $Update->execute(); 

    $Delete=$db->prepare("DELETE from `Delete_User` where id IN ($id) or adminid IN ($id) ");
    $Delete->execute();  
    if($Delete)
    {
        echo  json_encode(["resonse"=>"User successfully restored."]);die;   
    }
  }else{
      echo  json_encode(["error"=>"Something went wrong."]);die;   
  }

}

if(isset($_GET['MembershipPackageDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = " MemberPackage.createdfk IN (select id from users where id=$id or adminid=$id) AND ";

    }



    $selectQuery = $db->prepare("SELECT MemberPackage.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg FROM `MemberPackage` join users on MemberPackage.createdfk=users.id WHERE $id MemberPackage.isactive=0 ");


    $selectQuery->execute();

    $result = $selectQuery->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_GET['MembershipPackageRestore']))
{   
    $id =$_POST["id"];
    $query = $db->prepare("Update `MemberPackage` set isactive=1 where id IN ($id)");
    $deletefile=$query->execute();

    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Package successfully restore to Membership list']);die;
    }
}


if(isset($_GET['ProductTableDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = " Product.createdfk IN (select id from users where id=$id or adminid=$id) AND ";
    }


    $stmt4=$db->prepare("SELECT Product.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg FROM `Product` JOIN users ON Product.createdfk=users.id  WHERE $id Product.isarchive=0 GROUP BY Product.id"); 

    $stmt4->execute();

    $result = $stmt4->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_GET['ProductRestore']))
{   
    $id =$_POST["id"];
    $query = $db->prepare("Update `Product` set isarchive=1 where id IN ($id)");
    $deletefile=$query->execute();

    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Product successfully restore to Product list']);die;
    }
}

if(isset($_GET['ProductCatTableDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = "ProductCategory.createdfk IN (select id from users where id=$id or adminid=$id) AND";
    }

    $stmt4=$db->prepare("SELECT ProductCategory.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg FROM `ProductCategory` JOIN users ON ProductCategory.createdfk=users.id  WHERE $id ProductCategory.isactive=0 GROUP BY ProductCategory.id"); 

    $stmt4->execute();

    $result = $stmt4->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_GET['ProductCatRestore']))
{   
    $id =$_POST["id"];
    $query = $db->prepare("Update `ProductCategory` set isactive=1 where id IN ($id)");
    $deletefile=$query->execute();

    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Category successfully restore to Product Category list']);die;
    }
}

if(isset($_GET['ClientTableDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = "clients.createdfk IN (select id from users where id=$id or adminid=$id) AND";
    }

    $stmt4=$db->prepare("SELECT clients.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg FROM `clients` JOIN users ON clients.createdfk=users.id  WHERE $id clients.isactive=0 GROUP BY clients.id"); 

    $stmt4->execute();

    $result = $stmt4->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_GET['ClientRestore']))
{   
    $id =$_POST["id"];

    $Allusers= $db->prepare ("SELECT id, `email` FROM `clients` WHERE isactive=1 and `email`= (Select email from clients where id IN ($id) ) AND createdfk in (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id) and (u2.id=u3.adminid or u2.id=u3.id) where u1.id=875 GROUP by u3.id) and id NOT IN ($id)");
    $Allusers->execute();

    if ( $Allusers->rowCount() > 0 ){
        echo  json_encode(["error"=>'This email already exists.']);die;
    }

    $query = $db->prepare("Update `clients` set isactive=1 where id IN ($id)");
    $deletefile=$query->execute();

    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Client successfully restore to Clients list']);die;
    }
}


if(isset($_GET['EventTableDisable'])){

    if($_SESSION['usertype']=='Admin'){
        $id = "";
    }else{
        $id = $_SESSION['UserID'];
        $id = "event.createdfk IN (select id from users where id=$id or adminid=$id) AND";
    }

    $stmt4=$db->prepare("SELECT event.*,CONCAT(users.firstname,' ',users.lastname,' (',users.username,')') as Fullname,users.id as UserID,userimg FROM `event` JOIN users ON event.createdfk=users.id  WHERE $id event.isactive=0 GROUP BY event.id"); 

    $stmt4->execute();

    $result = $stmt4->fetchAll();

    echo json_encode($result);die;  
}

if(isset($_GET['EventRestore']))
{   
    $id =$_POST["id"];

    $query = $db->prepare("Update `event` set isactive=1 where id IN ($id)");
    $deletefile=$query->execute();

    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Event successfully restore to Event list']);die;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
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
                                Archive List
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                       <ul class="nav nav-tabs customtab" role="tablist">

                                        <li class="nav-item"> 
                                            <a class="nav-link active" data-toggle="tab" href="#ServiceTab" role="tab">
                                                <span>Service</span>
                                            </a> 
                                        </li>

                                        <?php if($_SESSION['usertype']!='employee'){ ?>
                                            <li class="nav-item"> 
                                                <a class="nav-link" data-toggle="tab" href="#UserTab" role="tab">
                                                    <span>Users</span>
                                                </a> 
                                            </li>    
                                        <?php } ?>
                                        
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-toggle="tab" href="#MembershipPackageTab" role="tab">
                                                <span>Membership Package</span>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-toggle="tab" href="#ProductTab" role="tab">
                                                <span>Product</span>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-toggle="tab" href="#ProductCatTab" role="tab">
                                                <span>Product Category</span>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-toggle="tab" href="#ClientTab" role="tab">
                                                <span>Clients</span>
                                            </a> 
                                        </li>
                                        <li class="nav-item"> 
                                            <a class="nav-link" data-toggle="tab" href="#EventTab" role="tab">
                                                <span>Event</span>
                                            </a> 
                                        </li>

                                    </ul>

                                    <div class="tab-content">


                                     <div class="tab-pane active" id="ServiceTab" role="tabpanel">                         
                                        <div class="table-responsive">

                                            <div style="display: flex;justify-content: flex-end;">
                                                <div class="text-right">
                                                    <a id="RestoreServiceAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                                    </a>
                                                </div>
                                                <?php if($_SESSION['usertype']!='Admin'){ ?>
                                                    <div class="text-right">
                                                        <a title="Service Page" target="_blank" href="<?=base_url?>/viewService"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                            </div>

                                            <table id="ServiceTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Service Name</th>
                                                        <th>Price</th>
                                                        <th>Duration</th>
                                                        <th>User</th>
                                                        <th>Created By</th>
                                                        <th>Deleted Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                    <?php if($_SESSION['usertype']!='employee'){ ?>
                                     <div class="tab-pane" id="UserTab" role="tabpanel">                         
                                        <div class="table-responsive">

                                            <div style="display: flex;justify-content: flex-end;">
                                                <div class="text-right">
                                                    <a id="RestoreUserAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                                    </a>
                                                </div>

                                                <div class="text-right">
                                                    <a title="User Page" target="_blank" href="<?=base_url?>/AllSubscriber"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                                    </a>
                                                </div>


                                            </div>

                                            <table id="UserTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>User</th>
                                                        <th>Detail</th>
                                                        <th>Deleted Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                <?php } ?>

                                

                                <div class="tab-pane" id="MembershipPackageTab" role="tabpanel">
                                    <div class="table-responsive">

                                     <div style="display: flex;justify-content: flex-end;">
                                        <div class="text-right">
                                            <a id="RestoreMembershipAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                            </a>
                                        </div>

                                        <?php if($_SESSION['usertype']!='Admin'){ ?>
                                            <div class="text-right">
                                                <a title="Membership Page" target="_blank" href="<?=base_url?>/MembershipPackageList"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <table id="MembershipPackageTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Price
                                                </th>
                                                <th>
                                                    Package date
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                                <th>
                                                    Created By
                                                </th>
                                                <th>
                                                    Deleted Date
                                                </th>
                                                <th>
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="Loader"></div>
                                </div>
                            </div>


                            <div class="tab-pane" id="ProductTab" role="tabpanel">                         
                                <div class="table-responsive">

                                 <div style="display: flex;justify-content: flex-end;">
                                    <div class="text-right">
                                        <a id="RestoreProductAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                        </a>
                                    </div>
                                    <?php if($_SESSION['usertype']!='Admin'){ ?>
                                        <div class="text-right">
                                            <a title="Product Page" target="_blank" href="<?=base_url?>/AllProduct"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <table id="ProductTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Product Name</th>
                                            <th>Cost Price</th>
                                            <th>Selling Price</th>
                                            <th>Quantity</th>
                                            <th>Created By</th>
                                            <th>Deleted Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="ProductCatTab" role="tabpanel">                         
                            <div class="table-responsive">
                             <div style="display: flex;justify-content: flex-end;">
                                <div class="text-right">
                                    <a id="RestoreProductCatAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                    </a>
                                </div>
                                <?php if($_SESSION['usertype']!='Admin'){ ?>
                                    <div class="text-right">
                                        <a title="Product Category Page" target="_blank" href="<?=base_url?>/AllProductCategory"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                            <table id="ProductCatTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Product Category</th>
                                        <th>Created By</th>
                                        <th>Deleted Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="ClientTab" role="tabpanel">                         
                        <div class="table-responsive">
                         <div style="display: flex;justify-content: flex-end;">
                            <div class="text-right">
                                <a id="RestoreClientAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                </a>
                            </div>
                            <div class="text-right">
                                <a title="Product Page" target="_blank" href="<?=base_url?>/AllClients"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                                </a>
                            </div>
                        </div>
                        <table id="ClientTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Client Name</th>
                                    <th>Email/Phone</th>
                                    <th>Address</th>
                                    <th>Created By</th>
                                    <th>Deleted Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="EventTab" role="tabpanel">                         
                    <div class="table-responsive">

                     <div style="display: flex;justify-content: flex-end;">
                        <div class="text-right">
                            <a id="RestoreEventAll" title="Restore All" href="#"><i class="fa fa-history btn-success btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                            </a>
                        </div>
                        <div class="text-right">
                            <a title="Event Page" target="_blank" href="<?=base_url?>/AllEvent"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
                            </a>
                        </div>
                    </div>
                    <table id="EventTableDisable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Client Name</th>
                                <th>Appoinment Info</th>
                                <th>Created By</th>
                                <th>Deleted Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>



        </div>




    </div>
</div>
</div>
</div>
</div>
</div>

<?php include_once('OrderInvoiceModel.php'); ?>
<?php include_once('viewproductdetail.php'); ?>
<?php include_once('viewclientdetail.php'); ?>
<?php include_once('viewuserdetail.php'); ?>


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
<!-- ==============================================================  -->


<?php include 'scripts.php'; ?>

<script>

//Service

ServiceTableDisable()
function ServiceTableDisable()
{
    $('#ServiceTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 6, 'desc' ]],
        "ajax" : {
            "url" : "?ServiceTableDisable",
            dataSrc : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {
            "className" : 'text-center', "targets" : '_all'}

            ],
            "columns" : [ 
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<input type="checkbox" value="'+encodedId+'">';
                }
            }
            ,
            {
                "data" : "ServiceName"
            }
            , {
                "data": {Price:"Price"}
                ,
                "render": function(data, type, row) {
                    return '<span>$'+ data.Price +'</span>';
                }
            }
            ,
            {
                "data" : "Duration"
            }
            ,

            {
                "data" : "userbane"
            },
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,{
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" title="Restore" id="RestoreService" data-id='+ encodedId +'><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreService',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?ServiceRestore',
            success: function(data)
            {
              if(data.resonse){
                $(".Loader").hide();
                swal('',data.resonse,'success');
                ServiceTableDisable();
            }else{
                swal('','Something went wrong','error');
            }
        }
    });
      }
  });
});

$(document).on('click','#RestoreServiceAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#ServiceTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?ServiceRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            ServiceTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#ServiceTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?ServiceRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        ServiceTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});


//User
UserTableDisable();
function UserTableDisable()
{
    $('#UserTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 3, 'desc' ]],
        "ajax" : {
            "url" : '?UserDisable',
            "dataSrc" : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'}
        ],
        "columns" : [ 
        {
            "data": "id",
            "render": function(data, type, row) {
                var encodedId = data;
                return '<input type="checkbox" value="'+encodedId+'">';
            }
        }
        ,
      {
                "data":{ userimg:"userimg",username:"username" },
                "render": function(data, type, row) {
              var encodedId = window.btoa(data.id);
              if(data.userimg)
              {
                return '<div><img src="<?php echo $base_url ?>/assets/userimage/'+data.userimg+'" class="img-circle example-image ViewSubscriberInfo" data-uid="'+ encodedId +'" id="ViewSubscriberInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /><br><span style="text-transform: capitalize;"> '+ data.username +'</span> </div>';    
                

              }
              else {
                return '<div><a class="example-image-link" href="#" ><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image ViewSubscriberInfo" data-uid="'+ encodedId +'" id="ViewSubscriberInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></a><br><span style="text-transform: capitalize;" > '+ data.username +'</span> </div>'; 
              }
          }
      }, 
                    {
        "data" : {email:"email", phonenumber:"phonenumber",usertype:"usertype"},
        "render": function(data, type, row) {
                return '<span class="pull-left"><b>Name: </b>'+row.firstname+' '+row.lastname+'</span><br><span class="pull-left"><b>Company Name: </b>'+data.companyname+'</span><br><span class="pull-left"><b>Email: </b>'+data.email+'</span><br><span class="pull-left"><b>Phone: </b>'+data.phonenumber+'</span><br><span class="pull-left"><b>Usertype: </b>'+data.usertype+'</span>';  
            
        }
      }, 
        
            {
                "data": "updated_at",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" id="RestoreUser" title="Restore" data-id='+ encodedId + '><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreUser',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?UserRestore',
            success: function(data)
            {
              if(data.resonse){
                $(".Loader").hide();
                swal('',data.resonse,'success');
                UserTableDisable();
            }else{
                swal('','Something went wrong','error');
            }
        }
    });
      }
  });
});

$(document).on('click','#RestoreUserAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#UserTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?UserRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            UserTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#UserTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?UserRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        UserTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});


//Membership
MembershipPackageTableDisable()
function MembershipPackageTableDisable()
{
    $('#MembershipPackageTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 6, 'desc' ]],
        "ajax" : {
            "url" : '?MembershipPackageDisable',
            "dataSrc" : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'}
        ],
        "columns" : [ 
        {
            "data": "id",
            "render": function(data, type, row) {
                var encodedId = data;
                return '<input type="checkbox" value="'+encodedId+'">';
            }
        }
        ,
        {
            "data" : "Name"
        }
        , 
        {
            "data": {
                Price:"Price"}
                ,
                "render": function(data, type, row) {
                    return '<span>$'+ data.Price +'</span>';
                }
            }
            ,
            {
                "data" : "Tracking",
            }
            ,  {
                "data" : "Description",
            }
            ,
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" id="RestoreMembership" title="Restore" data-id='+ encodedId + '><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}

$(document).on('click','#RestoreMembership',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?MembershipPackageRestore',
            success: function(data)
            {
              if(data.resonse){
                $(".Loader").hide();
                swal('',data.resonse,'success');
                MembershipPackageTableDisable();
            }else{
                swal('','Something went wrong','error');
            }
        }
    });
      }
  });
});

$(document).on('click','#RestoreMembershipAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#MembershipPackageTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?MembershipPackageRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            MembershipPackageTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#MembershipPackageTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?MembershipPackageRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        MembershipPackageTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});

//Product

ProductTableDisable()
function ProductTableDisable()
{
    $('#ProductTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 6, 'desc' ]],
        "ajax" : {
            "url" : "?ProductTableDisable",
            dataSrc : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {
            "className" : 'text-center', "targets" : '_all'}

            ],
            "columns" : [ 
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<input type="checkbox" value="'+encodedId+'">';
                }
            }
            ,{
                "data": {ProductTitle:"ProductTitle"},
                "render":function (data,type,row){
                    return '<a href="#" class="viewProduct" data-id="'+row.id+'">'+data.ProductTitle+'</a>';
                }
            }
            , {
                "data": {CompanyCost:"CompanyCost"},
                "render": function(data, type, row) {
                    return '<span>$'+ data.CompanyCost +'</span>';
                }
            }
            ,
            {
                "data": {SellingPrice:"SellingPrice"}
                ,
                "render": function(data, type, row) {
                    return '<span>$'+ data.SellingPrice +'</span>';
                }
            }
            ,

            {
                "data" : "NoofPorduct"
            },
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,{
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" title="Restore" id="RestoreProduct" data-id='+ encodedId +'><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreProduct',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?ProductRestore',
            success: function(data)
            {
              if(data.resonse){
                $(".Loader").hide();
                swal('',data.resonse,'success');
                ProductTableDisable();
            }else{
                swal('','Something went wrong','error');
            }
        }
    });
      }
  });
});

$(document).on('click','#RestoreProductAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#ProductTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?ProductRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            ProductTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#ProductTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?ProductRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        ProductTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});

//ProductCat

ProductCatTableDisable()
function ProductCatTableDisable()
{
    $('#ProductCatTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 3, 'desc' ]],
        "ajax" : {
            "url" : "?ProductCatTableDisable",
            dataSrc : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {
            "className" : 'text-center', "targets" : '_all'}


            ],
            "columns" : [
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<input type="checkbox" value="'+encodedId+'">';
                }
            }
            , {
                "data" : "Category"
            }
            ,
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,{
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" title="Restore" id="RestoreProductCat" data-id='+ encodedId +'><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreProductCat',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?ProductCatRestore',
            success: function(data)
            {
              if(data.resonse){
                $(".Loader").hide();
                swal('',data.resonse,'success');
                ProductCatTableDisable();
            }else{
                swal('','Something went wrong','error');
            }
        }
    });
      }
  });
});

$(document).on('click','#RestoreProductCatAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#ProductCatTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?ProductCatRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            ProductCatTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#ProductCatTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?ProductCatRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        ProductCatTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});

//CLients

ClientTableDisable()
function ClientTableDisable()
{
    $('#ClientTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 5, 'desc' ]],
        "ajax" : {
            "url" : "?ClientTableDisable",
            dataSrc : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {
            "className" : 'text-center', "targets" : '_all'}
            ,

            ],
            "columns" : [ 
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<input type="checkbox" value="'+encodedId+'">';
                }
            }
            ,
            {
                "data": {FirstName:"FirstName",LastName:"LastName", ProfileImg : "ProfileImg",id:"id"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.id);
                    if(data.ProfileImg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/ProfileImages/'+data.ProfileImg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.FirstName+' '+data.LastName+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/ProfileImages/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.FirstName+' '+data.LastName+'</span></div></div>'
                    }
                }
            },
            {
                "data": {email:"email",Phone:"Phone"}
                ,
                "render": function(data, type, row) {
                    return '<span>'+ data.email +'</span><br><span>'+ data.Phone +'</span>';
                }
            }
            ,
            {
                "data": {Address:"Address",City:"City",State:"State",Zip:"Zip",Country:"Country"}
                ,
                "render": function(data, type, row) {
                    return '<span>'+data.Address+',<br>'+data.City+', '+data.State+', <br>'+data.Zip+' '+data.Country+'</span>';
                }
            }
            ,
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,{
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" title="Restore" id="RestoreClient" data-id='+ encodedId +'><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreClient',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?ClientRestore',
            success: function(data)
            {
              if(data.resonse){
                swal('',data.resonse,'success');
                ClientTableDisable();
            }else{
                swal('',data.error,'error');
            }
            $(".Loader").hide();
        }
    });
      }
  });
});


$(document).on('click','#RestoreClientAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#ClientTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?ClientRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            ClientTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#ClientTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?ClientRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        ClientTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});


//Event


EventTableDisable()
function EventTableDisable()
{
    $('#EventTableDisable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "order": [[ 5, 'desc' ]],
        "ajax" : {
            "url" : "?EventTableDisable",
            dataSrc : ''
        }
        ,
        "autoWidth": false,
        "columnDefs": [
        {
            "className" : 'text-center', "targets" : '_all'}
            ,
            ],
            "columns" : [ 
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<input type="checkbox" value="'+encodedId+'">';
                }
            }
            ,
            {
                "data": "id"
            },
            {
                "data": {FirstName:"FirstName",LastName:"LastName", ProfileImg : "ProfileImg",cid:"cid"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.cid);
                    if(data.ProfileImg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/ProfileImages/'+data.ProfileImg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.FirstName+' '+data.LastName+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/ProfileImages/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.FirstName+' '+data.LastName+'</span></div></div>'
                    }
                }
            },
            {
                "data": {title:"title",EventDate:"EventDate",eventstatus:"eventstatus"}
                ,
                "render": function(data, type, row) {
                    return '<span>'+data.title+'<br>'+data.EventDate+'<br>'+data.eventstatus+'</span>';
                }
            }
            ,
            {
                "data": {Fullname : "Fullname", userimg : "userimg",UserID:"UserID"},
                "render": function(data, type, row) {
                    var encodedId = btoa(data.UserID);
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.Fullname+'</span></div></div>'
                    }
                }
            },
            {
                "data": "datelastupdated",
                "render": function(data, type, row) {
                    return  moment.utc(data).local().format('YYYY-MM-D HH:mm:ss');
                }
            }
            ,{
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = data;
                    return '<button class="btn btn-success btn-sm" title="Restore" id="RestoreEvent" data-id='+ encodedId +'><i class="fa fa-history" aria-hidden="true"></i></button>';
                }
            }
            ]
        });
}


$(document).on('click','#RestoreEvent',function(e){
    e.preventDefault();
    swal({
        title: "Restore?",
        icon: "warning",
        buttons: true,
    }).then((willDelete)=>{   
        if (willDelete){
          var delid=$(this).attr('data-id');
          $(".Loader").show();
          $.ajax({
            dataType:"json",
            type:"post",
            data:{'id':delid},
            url:'?EventRestore',
            success: function(data)
            {
              if(data.resonse){
                swal('',data.resonse,'success');
                EventTableDisable();
            }else{
                swal('',data.error,'error');
            }
            $(".Loader").hide();
        }
    });
      }
  });
});

$(document).on('click','#RestoreEventAll',function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "All: 'Restore all items' \n Selected: 'Restore selected item.' ",
        icon: "warning",
        buttons: {
            Cancel: 'Cancel',
            RestoreAll:'All',
            Restore: 'Selected',
        },
    }).then((value)=>{
        switch (value) {

            case 'Restore':
            var del_id=[];
            $("#EventTableDisable input").each(function(){
                if($(this).is(':checked')){
                    del_id.push($(this).val());
                }
            });

            if(del_id.length>0){
                $.ajax({
                    data:{id:del_id.toString()},
                    type:'post',
                    dataType:'json',
                    url:'?EventRestore',
                    success: function(data)
                    {
                        if(data.resonse){
                            $(".Loader").hide();
                            swal('',data.resonse,'success');
                            EventTableDisable();
                        }else{
                            swal('','Something went wrong','error');
                        }
                    }

                });
            }else{
                swal('No item was selected.');
            }
            break;

            case 'RestoreAll':
            var del_id=[];
            $("#EventTableDisable input").each(function(){
                del_id.push($(this).val());
            });


            $.ajax({
                data:{id:del_id.toString()},
                type:'post',
                dataType:'json',
                url:'?EventRestore',
                success: function(data)
                {
                    if(data.resonse){
                        $(".Loader").hide();
                        swal('',data.resonse,'success');
                        EventTableDisable();
                    }else{
                        swal('','Something went wrong','error');
                    }
                }

            });
            
            break;


            case 'Cancel':
            return false;
            break;

        }
    });

});

</script>

</body>
</html>