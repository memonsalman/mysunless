<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/function.php");
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
$db3=new db();
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($result);
    // echo "string";
    // echo $result['username'];
    // die();
}
$db=new db();
$isactive=1;
$package = $db->prepare("select * from `package` where isactive=:isactive");
$package->bindValue(":isactive",$isactive,PDO::PARAM_INT);
$package->execute();
$allpackage=$package->fetchAll();
if(isset($_REQUEST['PackageType']))
{
    $PackageType=$_POST['PackageType']; 
    $eidtClient = $db->prepare("select * from `package` where PackageType=:PackageType");
    $eidtClient->bindValue(":PackageType",$PackageType,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}
$dbpaym=new db();
$id=$_SESSION['UserID'];
$paydetail = $dbpaym->prepare("select * from `payments` where userid=:id");
$paydetail->bindParam(':id',$id,PDO::PARAM_INT);
$paydetail->execute();
$allpaydetail=$paydetail->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <style>
        .modal {
            display:none;
            position:fixed;
            z-index:1000;
            top:0;
            left:0;
            height:100%;
            width:100%;
            background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;
        }
        body.loading .modal {
            overflow: hidden;
        }
        body.loading .modal {
            display: block;
        }
        label.error{
            position: inherit!important;
            z-index: 5!important;
        }
        input#Price {
            padding: 0 5px !important;
        }
        p#Price{
            padding: 5px;
            margin: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
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
                                Package Info
                            </h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="fa fa-cog">
                                                </i></span> <span class="hidden-xs-down">Package Info</span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Package" role="tab"><span class="hidden-sm-up"><i class="fas fa-box">
                                                </i></span> <span class="hidden-xs-down">Update Package</span></a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home2" role="tabpanel">
                                            <div class="p-20">
                                                <div class="form-group">
                                                    <label for="example-email">Current Package<!-- <span class="help"> e.g. "example@gmail.com"</span> --></label>
 <input type="text" id="currentpackage" readonly name="currentpackage" class="form-control" placeholder="Example" value="<?php echo @$allpaydetail[0]['PackageType']; ?>" >
                                                </div>
                                                <h3>
                                                    Paymant info
                                                </h3>
                                                <table border="1" id="tbl" align="center">
                                                    <thead>
                                                        <tr>
                                                            <td>
                                                                Package Name
                                                            </td>
                                                            <td>
                                                                Amount
                                                            </td>
                                                            <td>
                                                                Date
                                                            </td>
                                                            <td>
                                                                Status
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                    <?php
foreach($allpaydetail as $row)
{
                                                    ?>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row['PackageType']; ?></td>
                                                            <td>
                                                                <?php echo $row['amount']; ?></td>
                                                            <td>
                                                                <?php echo $row['paytime']; ?></td>
                                                            <td>
                                                                <?php echo $row['status']; ?></td>
                                                        </tr>
                                                    </tbody>
                                                    <?php
}
                                                    ?>
                                                </table>
                                            </div>
                                            <button type="submit" class="btn waves-effect waves-light btn-info" id="Unsubscribed" name="Unsubscribed">Unsubscribed</button>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="alert alert-success" id="resonse2" style="display: none;">
                                                    <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-success">
                                                        <i class="fa fa-check-circle">
                                                        </i>
                                                        Success
                                                    </h3>
                                                    <p id="resonsemsg2">
                                                    </p>
                                                </div>
                                                <div class="alert alert-danger" id="error2" style="display: none;">
                                                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-danger">
                                                        <i class="fa fa-exclamation-circle">
                                                        </i>
                                                        Errors
                                                    </h3>
                                                    <p id="errormsg2">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane p-20" id="Package" role="tabpanel">
                                            <form class="form-horizontal form-material" autocomplete="off" id="pakcagefrom" method="post">
                                                <div class="form-group">
                                                    <label for="example-email">Current Package<!-- <span class="help"> e.g. "example@gmail.com"</span> --></label>
                        <input type="text" id="currentpackage" readonly name="currentpackage" class="form-control" placeholder="Example" value="<?php echo @$allpaydetail[0]['PackageType']; ?>" >
                                                </div>
                                                <div class="form-group">
                                                    <label><span class="help">Package Type</span></label>
                                                    <select name="PackageType" id="PackageType" class="form-control">
                                                        <option value="">Please Select Package</option>
                                                        <?php
foreach($allpackage as $row)
{
                                                        ?>
                                                        <option value="<?php echo $row['PackageType']; ?>"><?php echo $row['PackageType']; ?></option>
                                                        <?php
}
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><span class="help">Price</span></label>
                                                    <!-- <select name="Price" id="Price" class="form-control">dfsadfds</select> -->
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">$</span>
                                                        </div>
                                                        <p id="Price">
                                                        </p>
                                                        <input type="hidden" class="form-control" aria-label="Amount (to the nearest dollar)" readonly name="Price" id="Price2" value="" placeholder="Price">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row m-b-0">
                                                    <button type="submit" class="btn waves-effect waves-light btn-info">Update</button>
                                                </div>
                                                <div class="modal">
                                                </div>
                                            </form>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="alert alert-success" id="resonse" style="display: none;">
                                                    <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-success">
                                                        <i class="fa fa-check-circle">
                                                        </i>
                                                        Success
                                                    </h3>
                                                    <p id="resonsemsg">
                                                    </p>
                                                </div>
                                                <div class="alert alert-danger" id="error" style="display: none;">
                                                    <button type="button" class="close" > <span aria-hidden="true">&times;</span> </button>
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
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
    <script>
        $(document).ready(function(){
            $('#PackageType').on('change',function(){
                $(".modal").show();
                PackageType=$(this).val();
                $.ajax({
                    dataType:"json",
                    type:"post",
                    data: {
                        'PackageType':PackageType}
                    ,
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                        {
                            $(".modal").hide();
                            $('#Price').html('<span>'+data.resonse.Price+'</span>');
                            $('#Price2').val(data.resonse.Price);
                        }
                        else if(data.error)
                        {
                            alert('ok');
                        }
                    }
                }
                      )
            }
                                );
        }
                         );
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#pakcagefrom").validate({
                rules: {
                    PackageType : {
                        required: true,}
                    ,
                }
                ,
                messages: 
                {
                    PackageType: {
                        required: "Please Select Package Type"}
                    ,
                }
                ,
                submitHandler: function() {
                    $(".modal").show();
                    var data = $("#pakcagefrom").serialize();
                    data= data + "&action6=update_package";
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
                                $(".modal").hide();
                                location.reload();
                            }
                            else if(data.error)
                            {
                                $("#error").show();
                                $('#errormsg').html('<span>'+data.error+'</span>');
                                $(".modal").hide();
                                // alert('<li>'+data.error+'</li>');
                            }
                        }
                    }
                               );
                }
            }
                                      );
        }
                         );
    </script>
    <script>
        $(document).ready(function(){
            $('#Unsubscribed').on('click',function(){
                if ( confirm( "Are you sure you want to Unsubscribed the Package ?" )){
                    var data = <?php echo $_SESSION['UserID']; ?>;
                    data= data + "&action8=Unsubscribed";
                    jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:data,
                        url:'<?php echo EXEC; ?>exec-edit-profile.php',
                        success: function(data)
                        {
                            if(data.resonse2)
                            {
                                $("#resonse2").show();
                                $('#resonsemsg2').html('<span>'+data.resonse2+'</span>');
                                $(".modal").hide();
                                location.reload();
                            }
                            else if(data.error2)
                            {
                                $("#error2").show();
                                $('#errormsg2').html('<span>'+data.error2+'</span>');
                                $(".modal").hide();
                                // alert('<li>'+data.error+'</li>');
                            }
                        }
                    }
                               );
                }
            }
                                 );
        }
                         );
    </script>
    <script src="
<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl').DataTable();
        }
                         );
    </script>
    <script type="text/javascript">
        $(".close").click(function(){
            $("#error").hide();
            $("#resonse").hide();
            $("#error2").hide();
            $("#resonse2").hide();
        }
                         );
    </script>
</body>
</html>
