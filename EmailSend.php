<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
$db3=new db();
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$From=$result['email'];
    @$user=$result['username'];
}
$db=new db();
$RelatedTo = $db->prepare("SELECT * FROM `clients` WHERE createdfk=:id");
$RelatedTo->bindValue(":id",$id,PDO::PARAM_INT);
$RelatedTo->execute();
$all_client=$RelatedTo->fetchAll(PDO::FETCH_ASSOC);
$db=new db();
$RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk=:id");
$RelatedTo2->bindValue(":id",$id,PDO::PARAM_INT);
$RelatedTo2->execute();
$all_Templeate=$RelatedTo2->fetchAll(PDO::FETCH_ASSOC);
// if(isset($_POST['decodedString']))
// {
//     $db=new db();
//     $id=$_POST['decodedString'];
//        $stmt= $db->prepare("SELECT email FROM `clients` WHERE id IN ($id)"); 
//            $stmt->execute();
//            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//            echo json_encode($result);die;   
// }
$db=new db();
$id=$_SESSION['UserID'];
$EditEvent=$db->prepare("select * from `gmail` where userid=:id");
$EditEvent->bindParam(':id', $id, PDO::PARAM_INT);
$EditEvent->execute();
$GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
$username=$GetEvent['username'];
$olduserid=$GetEvent['userid'];
if(isset($_POST['gpassword']))
{   
    $username=$_POST['username'];
    $gpassword=$_POST['gpassword'];
    $userid=$_SESSION['UserID'];
    $db=new db();
    $RelatedTo = $db->prepare("INSERT INTO gmail (username,gpassword,userid) VALUES (:username, :gpassword,:userid)");
    $RelatedTo->bindValue(":username",$username,PDO::PARAM_INT);
    $RelatedTo->bindValue(":gpassword",$gpassword,PDO::PARAM_INT);
    $RelatedTo->bindValue(":userid",$userid,PDO::PARAM_INT);
    $RelatedTo->execute();
    if($RelatedTo)
    {
        echo  json_encode(["resonse"=>'Your Gmail account successfully enable']);die;
    }
}
if(isset($_POST['disbleid']))
{   
    $db = new db();
    $id=$_POST["disbleid"];
    $query = $db->prepare("DELETE FROM gmail WHERE userid=:id");
    $query->bindValue(':id',$id, PDO::PARAM_STR); 
    $query->execute();
    if($query)
    {
        echo  json_encode(["resonse"=>'Your Gmail account successfully disable']);die;
    }
}
if(isset($_REQUEST['tid']))
{
    $tid=$_POST['tid']; 
    $eidtClient = $db->prepare("select * from `EmailTempleate` where id=:tid");
    $eidtClient->bindValue(":tid",$tid,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link href="../crm/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <style>
        .modal2 {
            display:none;
            position:fixed;
            z-index:1000;
            top:0;
            left:0;
            height:100%;
            width:100%;
            background: rgba( 255, 255, 255, .8) 
                url('crm/assets/images/ajax-loader.gif') 50% 50% no-repeat;
        }
        body.loading .modal2 {
            overflow: hidden;
        }
        body.loading .modal2 {
            display: block;
        }
        li.select2-selection__choice {
            color: white !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            background-color: #42bfd3!important;
            border:1px solid #43c1d4!important;
        }
        /*.select2-container--default .select2-selection--multiple{
        border-bottom: 1px solid #e9ecef!important;
        border-top: 0!important; 
        border-right: 0!important; 
        border-left: 0!important;}*/
        span.select2.select2-container.select2-container--default.select2-container--focus{
            width: 100%!important;
        }
        span.select2.select2-container.select2-container--default.select2-container{
            width: 100%!important;
        }
        input.select2-search__field{
            width: 100%!important;
        }
        .panel-body {
            padding: 15px;
        }
        .panel-heading {
            height: 50px;
            background-color: #f6f8f8;
            border-color: #edf1f2;
        }
        .panel.panel-default {
            min-height: 257px;
            border: 1px solid #dee5e7;
        }
        .panel-desc{
            min-height:85px ;
        }
        .m-b-sm {
            margin-bottom: 10px;
        }
        .m-l-xs {
            margin-left: 5px;
        }
        .eventStart1{
            width: 45%;
            float: left;
            padding: 0 10px;
        }
    </style>
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
                            <?php
if(isset($_GET["id"]))
{
                            ?>
                            <h4 class="text-themecolor">
                                Send Email
                            </h4>
                            <?php       
}
else
{
                            ?>
                            <h4 class="text-themecolor">
                                Send Email
                            </h4>
                            <?php
}
                            ?>
                            <!-- <h4 class="text-themecolor">Add New Event</h4> -->
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
                                    <button type="button" class="pull-right btn btn-info" data-toggle="modal" data-target="#composeModal"> COMPOSE</button>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs customtab" role="tablist">
                                        <!-- <li class="nav-item"> 
<a class="nav-link active" data-toggle="tab" href="#inbox1" role="tab">
<span class="hidden-sm-up"><i class="fa fa-inbox"></i></span> <span class="hidden-xs-down"> Inbox</span>
</a> 
</li> -->
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#sentbox" role="tab">
                                                <span class="hidden-sm-up"><i class="fa fa-send">
                                                    </i></span> <span class="hidden-xs-down"> Sentbox</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#trash" role="tab">
                                                <span class="hidden-sm-up"><i class="fa fa-trash">
                                                    </i></span> <span class="hidden-xs-down"> Trash</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content tabcontent-border">
                                        <!-- <div class="tab-pane active" id="inbox1" role="tabpanel">
<div class="col-lg-12">
<div class="container">
<div class="row">
<div class="col-md-12"> 
<?php 
if(!empty($username))
{
?>
<button type="submit" class="btn waves-effect waves-light btn-danger m-r-10" id="gmail_disable"><i class="fa fa-sign-out-alt"></i> Disable Gmail</button> 
<table id="myTable" class="display" cellspacing="0" width="100%">
<thead>
<tr>
<th>No</th>
<th>Subject</th>
<th>Name</th>
<th>Email</th>
<th>Date</th>
</tr>
</thead>
<tbody id="inbox">
</tbody>
</table>
<div class="modal2"></div>
<?php 
}
else
{?>
<div class="col-lg-4 col-sm-6 col-xs-12">
<div class="panel panel-default">
<div class="panel-heading">
<center>
<img class="m-b-none thumb-transparent thumb-xl" style="height:47px;" src="//doxhze3l6s7v9.cloudfront.net/app/static//img/icons/google-mail-sync.png">
</center>
</div>
<div class="panel-body">
<div class="m-b-sm">
See all emails related to a contact from your Gmail account it own risk.
<form class="form-horizontal " id="add-gmail" method="post">
<input type="hidden" name="olduserid" id="oldusername" class="form-control" placeholder="email" value="
<?php echo $olduserid; ?>">
<div class="form-group">
<label>Email or Phone *</label>
<input type="text" name="username" id="username" class="form-control" placeholder="Gmail Email or Phone" value="">
</div>
<div class="form-group">
<label>Password *</label>
<input type="Password" name="gpassword" id="gpassword" class="form-control" placeholder="Gmail Password" value="">
</div>
</div>
</div>
<center>
<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-gmail"><i class="fa fa-check"></i> Gmail Enable</button>
</center>
</form>
</div>  
</div>  
<?php
}
?>
</div>                  
</div>                  
</div>
-->
                                        <!-- Modal message -->      
                                        <div id="addModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            Message
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body" id="message">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--    </div>
</div> -->
                                        <!--  Start sent box tab -->
                                        <div class="tab-pane active" id="sentbox" role="tabpanel">
                                            <div class="col-lg-12">
                                                <div class="table-responsive m-t-40">
                                                    <table id="myTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    From
                                                                </th>
                                                                <th>
                                                                    To
                                                                </th>
                                                                <th>
                                                                    Subject
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="alert alert-success" id="resonse_delete" style="display: none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-success">
                                                        <i class="fa fa-check-circle">
                                                        </i>
                                                        Success
                                                    </h3>
                                                    <p id="resonsemsg_delete">
                                                    </p>
                                                </div>
                                                <div class="alert alert-danger" id="error_delete" style="display: none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-danger">
                                                        <i class="fa fa-exclamation-circle">
                                                        </i>
                                                        Errors
                                                    </h3>
                                                    <p id="errormsg_delete">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End sent box tab -->
                                        <!--  Start trash box tab -->
                                        <div class="tab-pane" id="trash" role="tabpanel">
                                            <div class="col-lg-12">
                                                <div class="table-responsive m-t-40">
                                                    <table id="trashTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    From
                                                                </th>
                                                                <th>
                                                                    To
                                                                </th>
                                                                <th>
                                                                    Subject
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="alert alert-success" id="resonse_deleteforever" style="display: none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-success">
                                                        <i class="fa fa-check-circle">
                                                        </i>
                                                        Success
                                                    </h3>
                                                    <p id="resonsemsg_deleteforever">
                                                    </p>
                                                </div>
                                                <div class="alert alert-danger" id="error_deleteforever" style="display: none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                    <h3 class="text-danger">
                                                        <i class="fa fa-exclamation-circle">
                                                        </i>
                                                        Errors
                                                    </h3>
                                                    <p id="errormsg_deleteforever">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End trash box tab -->
                                        <!-- Start compose Modal -->
                                        <div id="composeModal" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            New Message
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-horizontal " autocomplete="off" id="NewMail" method="post">
                                                            <input type="hidden" name="UserID" id="UserID" value="
<?php echo $_SESSION['UserID']; ?>">
                                                            <div class="form-group">
                                                                <label>From *</label>
                                                                <input type="text" name="From" id="From" class="form-control" placeholder="From" value="
<?php echo $From; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>To *</label>
                                                                <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                                                                <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Search Contact Email" id="To" name="To[]" multiple data-style="form-control tn-secondary">
                                                                    <?php 
foreach($all_client as $row)
{
                                                                    ?>
                                                                    <option value="<?php echo $row['email']?>"><?php echo $row['FirstName']." ".$row['LastName']; ?></option>
                                                                    <?php
}
                                                                    ?>
                                                                </select>
                                                                <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Template *</label>
                                                                <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                                                                <select class="Templeate form-control"  id="Templeate" name="Templeate">
                                                                    <option value="">Select Email Template</option>
                                                                    <?php 
foreach($all_Templeate as $row)
{
                                                                    ?>
                                                                    <option value="<?php echo $row['id']?>"><?php echo $row['Name']; ?></option>
                                                                    <?php
}
                                                                    ?>
                                                                </select>
                                                                <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Subject *</label>
                                                                <input type="text" name="Subject" id="Subject" class="form-control" placeholder="Subject" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Message *</label>
                                                                <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Message ..." id="Message" name="Message"></textarea>
                                                            </div>
                                                            <div class="modal2">
                                                            </div>
                                                            <div class="form-group">
                                                                <?php
if(isset($_GET["id"]))
{
                                                                ?>
                                                                <button type="submit" name="send" class="btn waves-effect waves-light btn-info m-r-10" id="send"><i class="fa fa-check">
                                                                    </i> Send</button>
                                                                <?php       
}
else
{
                                                                ?>
                                                                <button type="submit" name="send" class="btn waves-effect waves-light btn-info m-r-10" id="send"><i class="fa fa-check">
                                                                    </i> Send</button>
                                                                <?php
}
                                                                ?>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End compose modal -->
                                    </div>
                                </div>
                                <div class="modal2">
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="alert alert-success" id="resonse_mail" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-success">
                                            <i class="fa fa-check-circle">
                                            </i>
                                            Success
                                        </h3>
                                        <p id="resonsemsg_mail">
                                        </p>
                                    </div>
                                    <div class="alert alert-danger" id="error_mail" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-danger">
                                            <i class="fa fa-exclamation-circle">
                                            </i>
                                            Errors
                                        </h3>
                                        <p id="errormsg_mail">
                                        </p>
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
        <!--  Start sent box js -->
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    dom: 'Blfrtip',
                    "processing" : true,
                    "autoWidth": false,
                    "columnDefs": [
                        {
                            "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "20%", "targets": 0 }
                        ,
                        {
                            "width": "20%", "targets": 1 }
                        ,
                        {
                            "width": "50%", "targets": 2 }
                        ,
                        {
                            "width": "10%", "targets": 3 }
                        ,
                    ],
                        "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_Send_Email.php?viewdata",
                        "type":'post',
                        "dataSrc" : ''
                        }
                        ,
                        "columns" : [
                        {
                        "data" : "FromE",
                        }
                        ,
                        {
                        "data" : "ToE",
                        }
                        ,
                        {
                        "data" : "Subject",
                        }
                        ,
                        {
                        "data": "id",
                        "render": function(data, type, row) {
                        return '<a href="#" class="btn btn-primary btn-sm " data-id="'+data+'" id="view" title="View message"><span class="fa fa-eye"><span></a> <a href="<?php echo EXEC; ?>Exec_Send_Email.php?delete" id="deletemail" class="btn btn-primary btn-sm " data-id="'+data+'" title="Remove to Trash"><i class="fa fa-trash"><i></a>';
                        }
                        }
                    ]
                }
                                       );
                $(document).on('click','#view',function(e){
                    e.preventDefault();
                    var data = $(this).data('id');
                    jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:{
                            id:data}
                        ,
                        url:'<?php echo EXEC; ?>Exec_Send_Email.php?viewmsg',
                        beforeSend: function() {
                            $(".modal2").show();
                        }
                        ,
                        success: function(data)
                        {
                            if(data.MessageE)
                            {
                                $(".modal2").show();
                                $('#addModal').modal('toggle');
                                $('#message').html(data.MessageE);
                                $(".modal2").hide();
                            }
                            else if(data.error_mail)
                            {
                                $("#error").show();
                                $('#errormsg').html('<span>'+data.error_mail+'</span>');
                            }
                        }
                    }
                               );
                }
                              );
                // start delete mail from sent box js 
                $(document).ready(function(){
                    $(document).on('click','#deletemail',function(e){
                        e.preventDefault();
                        var link = $(this).attr('href');
                        var id = $(this).attr('data-id');
                        swal({
                            title: "Are you sure?",
                            text: "you are really want to delete this Email !",
                            icon: "warning",
                            buttons: true,
                        }
                            ).then((willDelete)=>{
                            if (willDelete){
                            // window.location.href = link;
                            jQuery.ajax({
                            dataType:"json",
                            type:"post",
                            data:{
                            "id": id}
                                   ,
                                   url:link,
                                   beforeSend: function() {
                            $(".modal2").show();
                        }
                        ,
                            success: function(data)
                        {
                            if(data.resonse)
                            {
                                $("#resonse_delete").show();
                                $('#resonsemsg_delete').html('<span>'+data.resonse+'</span>');
                                $(".modal2").hide();
                                setTimeout(window.location.reload(), 3000);
                            }
                            else if(data.error)
                            {
                                $("#error_delete").show();
                                $('#errormsg_delete').html('<span>'+data.error+'</span>');
                                $(".modal2").hide();
                            }
                        }
                    }
                                  );
                }
                                  else{
                                  return false ;
                                  }
                                  }
                                 );
            }
                             );
            }
            );
            // end delete mail from sent mail js 
            }
            );
        </script>
        <!--  End sent box js -->
        <!--  Start trash box js -->
        <script>
            $(document).ready(function() {
                $('#trashTable').DataTable({
                    dom: 'Blfrtip',
                    "processing" : true,
                    "autoWidth": false,
                    "columnDefs": [
                        {
                            "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "20%", "targets": 0 }
                        ,
                        {
                            "width": "20%", "targets": 1 }
                        ,
                        {
                            "width": "50%", "targets": 2 }
                        ,
                        {
                            "width": "10%", "targets": 3 }
                        ,
                    ],
                        "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_Send_Email.php?trashdata",
                        "type":'post',
                        "dataSrc" : ''
                        }
                        ,
                        "columns" : [
                        {
                        "data" : "FromE",
                        }
                        ,
                        {
                        "data" : "ToE",
                        }
                        ,
                        {
                        "data" : "Subject",
                        }
                        ,
                        {
                        "data": "id",
                        "render": function(data, type, row) {
                        return '<a href="#" class="btn btn-primary btn-sm " data-id="'+data+'" id="view" title="View message"><span class="fa fa-eye"><span></a> <a href="<?php echo EXEC; ?>Exec_Send_Email.php?deletePermanent" id="deletePermanent" class="btn btn-primary btn-sm " data-id="'+data+'" title="Delete forever"><span class="fa fa-trash"><span></a>';
                        }
                        }
                    ]
                }
                                          );
            }
                             );
            // start delete mail forever js 
            $(document).ready(function(){
                $(document).on('click','#deletePermanent',function(e){
                    e.preventDefault();
                    var link = $(this).attr('href');
                    var id = $(this).attr('data-id');
                    swal({
                        title: "Are you sure?",
                        text: "you are really want to delete this Email permanently!",
                        icon: "warning",
                        buttons: true,
                    }
                        ).then((willDelete)=>{
                        if (willDelete){
                        // window.location.href = link;
                        jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:{
                        "id": id}
                               ,
                               url:link,
                               beforeSend: function() {
                        $(".modal2").show();
                    }
                    ,
                        success: function(data)
                    {
                        if(data.resonse)
                        {
                            $("#resonse_deleteforever").show();
                            $('#resonsemsg_deleteforever').html('<span>'+data.resonse+'</span>');
                            $(".modal2").hide();
                            setTimeout(window.location.reload(), 3000);
                        }
                        else if(data.error)
                        {
                            $("#error_deleteforever").show();
                            $('#errormsg_deleteforever').html('<span>'+data.error+'</span>');
                            $(".modal2").hide();
                        }
                    }
                }
                              );
            }
                              else{
                              return false ;
                              }
                              }
                             );
            }
            );
            }
            );
            // end delete mail forever js 
        </script>
        <!--  Emd sent box js -->
        <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
        <script>
            jQuery(document).ready(function() {
                // Switchery
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                $('.js-switch').each(function() {
                    new Switchery($(this)[0], $(this).data());
                }
                                    );
                // For select 2
                $(".select2").select2();
                $('.selectpicker').selectpicker();
                //Bootstrap-TouchSpin
                $(".vertical-spin").TouchSpin({
                    verticalbuttons: true,
                    verticalupclass: 'ti-plus',
                    verticaldownclass: 'ti-minus'
                }
                                             );
                var vspinTrue = $(".vertical-spin").TouchSpin({
                    verticalbuttons: true
                }
                                                             );
                if (vspinTrue) {
                    $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
                }
                $("input[name='tch1']").TouchSpin({
                    min: 0,
                    max: 100,
                    step: 0.1,
                    decimals: 2,
                    boostat: 5,
                    maxboostedstep: 10,
                    postfix: '%'
                }
                                                 );
                $("input[name='tch2']").TouchSpin({
                    min: -1000000000,
                    max: 1000000000,
                    stepinterval: 50,
                    maxboostedstep: 10000000,
                    prefix: '$'
                }
                                                 );
                $("input[name='tch3']").TouchSpin();
                $("input[name='tch3_22']").TouchSpin({
                    initval: 40
                }
                                                    );
                $("input[name='tch5']").TouchSpin({
                    prefix: "pre",
                    postfix: "post"
                }
                                                 );
                // For multiselect
                $('#pre-selected-options').multiSelect();
                $('#optgroup').multiSelect({
                    selectableOptgroup: true
                }
                                          );
                $('#public-methods').multiSelect();
                $('#select-all').click(function() {
                    $('#public-methods').multiSelect('select_all');
                    return false;
                }
                                      );
                $('#deselect-all').click(function() {
                    $('#public-methods').multiSelect('deselect_all');
                    return false;
                }
                                        );
                $('#refresh').on('click', function() {
                    $('#public-methods').multiSelect('refresh');
                    return false;
                }
                                );
                $('#add-option').on('click', function() {
                    $('#public-methods').multiSelect('addOption', {
                        value: 42,
                        text: 'test 42',
                        index: 0
                    }
                                                    );
                    return false;
                }
                                   );
                $(".ajax").select2({
                    ajax: {
                        url: "https://api.github.com/search/repositories",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        }
                        ,
                        processResults: function(data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
                            params.page = params.page || 1;
                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 30) < data.total_count
                                }
                            };
                        }
                        ,
                        cache: true
                    }
                    ,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                    , // let our custom formatter work
                    minimumInputLength: 1,
                    templateResult: formatRepo, // omitted for brevity, see the source of this page
                    templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                }
                                  );
            }
                                  );
        </script>
        <!-- wysuhtml5 Plugin JavaScript -->
        <script src="
<?php echo base_url; ?>/crm/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
        <script src="
<?php echo base_url; ?>/crm/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.textarea_editor').wysihtml5();
            }
                             );
        </script>
        <script src="
<?php echo base_url ; ?>/dist/js/jquery.validate.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#NewMail").validate({
                    ignore: ":hidden:not(textarea)",
                    rules: {
                        From: {
                            required: true,}
                        ,
                        "To[]": {
                            required: true,}
                        ,
                        Subject: {
                            required: true,}
                        ,
                        Message: {
                            required: true,}
                        ,
                    }
                    ,
                    messages: {
                        From: {
                            required: "Please Enter  Your Email Id"}
                        ,
                        "To[]": {
                            required: "Please Select at least one Recipient."}
                        ,
                        Subject: {
                            required: "Please Enter Email Subject"}
                        ,
                        Message: {
                            required: "Please Enter Email message"}
                        ,
                    }
                    ,
                    errorPlacement: function( label, element ) {
                        if( element.attr( "name" ) === "Message" || element.attr( "name" ) === "To[]" ) {
                            element.parent().append( label );
                        }
                        else {
                            label.insertAfter( element );
                        }
                    }
                    ,
                    submitHandler: function() {
                        var data = $("#NewMail").serialize();
                        data= data ;
                        jQuery.ajax({
                            dataType:"json",
                            type:"post",
                            data:data,
                            url:'<?php echo EXEC; ?>Exec_AllMail.php',
                            beforeSend: function() {
                                $(".modal2").show();
                            }
                            ,
                            success: function(data)
                            {
                                $(".modal2").hide();
                                $( '#NewMail' ).each(function(){
                                    this.reset();
                                }
                                                    );
                                $("#composeModal").modal('hide');
                                if(data.resonse_mail)
                                {
                                    $("#resonse_mail").show();
                                    $('#resonsemsg_mail').html('<span>'+data.resonse_mail+'</span>');
                                }
                                else if(data.error_mail)
                                {
                                    $("#error_mail").show();
                                    $('#errormsg_mail').html('<span>'+data.error_mail+'</span>');
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
        <script type="text/javascript">
            var queryString = decodeURIComponent(window.location.search);
            queryString = queryString.substring(1);
            var decodedString = atob(queryString);
            $(document).ready(function(){
                function showRoom(){
                    $.ajax({
                        type:"POST",
                        dataType:"JSON",
                        url:"EmailSend.php",
                        data:{
                            decodedString:decodedString}
                        ,
                        success:function(data){
                            console.log(data);
                            //$('#To').val(data).trigger('change');
                            var selectedValues = new Array();
                            var i =0;
                            $.each(data, function(k,v) {
                                selectedValues[i] = v.email;
                                i++;
                                console.log(v.email);
                            }
                                  );
                            if(selectedValues){
                                $('#To').val(selectedValues).trigger('change');
                            }
                        }
                    }
                          );
                }
                showRoom();
            }
                             );
        </script>
        <!-- <script src="//cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.6.0/src/loadingoverlay.min.js"></script> -->
        <!-- <script src="
<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script>        
$(function() {
var json;
// $.LoadingOverlay("show");
$(".modal2").show();
$.ajax({
type: "POST",
url: "json.php",
data: {
inbox: ""
},
dataType: 'json'
}).done(function(d) {
if(d.status === "success"){
var tbody = "";
json = d.data;
$.each(json, function(i, a) {
tbody += '<tr><td>' + (i + 1) + '</td>';
tbody += '<td><a href="#" data-id="' + i + '" class="view" data-toggle="modal" data-target="#addModal">' + a.subject.substring(0, 20) + '</a></td>';
tbody += '<td>' + (a.from.name === "" ? "[empty]" : a.from.name) + '</td>';
tbody += '<td><a href="mailto:' + a.from.address + '?subject=Re:' + a.subject + '">' + a.from.address + '</a></td>';
tbody += '<td>' + a.date + '</td></tr>';
});
$('#inbox').html(tbody);
$('#myTable').DataTable();
// $.LoadingOverlay("hide");
$(".modal2").hide();
}else{
alert(d.message);
}
});
$('body').on('click', '.view', function () {
var id = $(this).data('id'); 
var message = json[id].message;
var attachments = json[id].attachments;
var attachment = '';
if(attachments.length > 0){
attachment += "<hr>Attachments:";
$.each(attachments, function(i, a) {
var file = json[id].uid + ',' + a.part + ',' + a.file + ',' + a.encoding;
attachment += '<br><a href="#" class="file" data-file="' + file + '">' + a.file + '</a>';
});
}
$('#message').html(message + attachment); 
});
$('body').on('click', '.file', function () {
// $.LoadingOverlay("show");
$(".modal2").show();
var file = $(this).data('file').split(",");
$.ajax({
type: "POST",
url: "json.php",
data: {
uid: file[0],
part: file[1],
file: file[2],
encoding: file[3]
},
dataType: 'json'
}).done(function(d) {
if(d.status === "success"){
// $.LoadingOverlay("hide");
$(".modal2").hide();
window.open(d.path, '_blank');
}else{
alert(d.message);
}
});
});
});
</script>
-->
        <script type="text/javascript">
            $(document).ready(function(){
                $("#add-gmail").validate({
                    rules: {
                        username: {
                            required: true,}
                        ,
                        gpassword: {
                            required: true,}
                        ,
                    }
                    ,
                    messages: {
                        username: {
                            required: "Please Enter Valid Gmail Email or Phone "}
                        ,
                        gpassword: {
                            required: "Please Enter Valid Gmail Password "}
                        ,
                    }
                    ,
                    submitHandler: function() {
                        $(".modal").show();
                        var data = $("#add-gmail").serialize();
                        data= data + "&LoginAction=Login";
                        jQuery.ajax({
                            dataType:"json",
                            type:"post",
                            data:data,
                            url:'EmailSend.php',
                            success: function(data)
                            {
                                if(data.resonse)
                                {
                                    $("#resonse").show();
                                    $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                    $( '#add-gmail' ).each(function(){
                                        this.reset();
                                    }
                                                          );
                                    $(".modal").hide();
                                    setTimeout(function () {
                                        window.location.reload();
                                    }
                                               , 3000)
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
                $('#Templeate').on('change',function(){
                    $(".modal2").show();
                    tid=$(this).val();
                    $.ajax({
                        dataType:"json",
                        type:"post",
                        data: {
                            'tid':tid}
                        ,
                        url:'?action=editfile',
                        success: function(data)
                        {
                            if(data)
                            {
                                $(".modal2").hide();
                                $('#Subject').val(data.resonse.Subject);
                                // $("textarea").val(data.resonse.TextMassage);       
                                $('iframe').contents().find('.wysihtml5-editor').html(data.resonse.TextMassage);
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
            $("#gmail_disable").click(function(){
                $(".modal").show();
                var data = '<?php echo $_SESSION['UserID']; ?>';
                jQuery.ajax({
                    dataType:"json",
                    type:"post",
                    data:{
                        disbleid:data}
                    ,
                    url:'EmailSend.php',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                            $("#resonse").show();
                            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                            $( '#NewEvent' ).each(function(){
                                this.reset();
                            }
                                                 );
                            $(".modal").hide();
                            setTimeout(function () {
                                window.location.reload();
                            }
                                       , 1000)
                        }
                        else if(data.error)
                        {
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            $(".modal").hide();
                            alert('<li>'+data.error+'</li>');
                        }
                    }
                }
                           );
            }
                                     );
</script>
</body>
</html>