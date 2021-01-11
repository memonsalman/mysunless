<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
if(isset($_REQUEST['del_id']))
{
    $id=$_REQUEST['del_id'];      
    $id_data=implode(',',$id);
    $db=new db();
    $DeleteClient = $db->prepare("delete from `users` where id in($id_data)");
    $DeleteClient->bindValue(":id",$id,PDO::PARAM_INT);
    $DeleteClient->execute();
    if($DeleteClient)
    {
        echo  json_encode(["resonse"=>'User Delete']);die;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link rel="stylesheet" type="text/css" href="
<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
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
                                Users List
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
                                    <!-- Tab panes -->
                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="home" role="tabpanel">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive m-t-40">
                                                            <table id="myTable" class="table table-bordered table-striped dataTable no-footer">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            User Name
                                                                        </th>
                                                                        <th>
                                                                            User Create
                                                                        </th>
                                                                        <th>
                                                                            Client Create
                                                                        </th>
                                                                        <th>
                                                                            Schedules Create
                                                                        </th>
                                                                        <th>
                                                                            Todo Create
                                                                        </th>
                                                                        <th>
                                                                            Services Create
                                                                        </th>
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
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
        <?php include 'scripts.php'; ?>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
<script src="
<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="
<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
<script>
    $(document).ready(function() {
        dataTable()
        function dataTable()
        {
            $('#myTable').DataTable({
                "processing" : true,
                "destroy": true,
                "ajax" : {
                    "url" : "<?php echo EXEC; ?>Exec_AllPermission.php",
                    dataSrc : ''
                }
                ,
                "columns" : [{
                    "data" : "username"
                }
                             ,
                             {
                                 "data": {
                                     id : "id", UserCreate : "UserCreate"}
                                 ,
                                 "render": function(data, type, row) {
                                     if (data.UserCreate == 1){
                                         return '<input class="toggle_stutus" id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1">' ;
                                     }
                                     else{
                                         return '<input class="toggle_stutus" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0">' ;
                                     }
                                 }
                             }
                             ,
                             {
                                 "data": {
                                     id : "id", ClientCreate : "ClientCreate"}
                                 ,
                                 "render": function(data, type, row) {
                                     if (data.ClientCreate == 1){
                                         return '<input class="toggle_status2"  id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1">' ;
                                     }
                                     else{
                                         return '<input class="toggle_status2" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0">' ;
                                     }
                                 }
                             }
                             ,
                             {
                                 "data": {
                                     id : "id", SchedulesCreate : "SchedulesCreate"}
                                 ,
                                 "render": function(data, type, row) {
                                     if (data.SchedulesCreate == 1){
                                         return '<input class="toggle_status3" id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1">' ;
                                     }
                                     else{
                                         return '<input class="toggle_status3" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0">' ;
                                     }
                                 }
                             }
                             ,
                             {
                                 "data": {
                                     id : "id", TodoCreate : "TodoCreate"}
                                 ,
                                 "render": function(data, type, row) {
                                     if (data.TodoCreate == 1){
                                         return '<input class="toggle_status4" id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1">' ;
                                     }
                                     else{
                                         return '<input class="toggle_status4" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0">' ;
                                     }
                                 }
                             }
                             ,
                             {
                                 "data": {
                                     id : "id", ServicesCreate : "ServicesCreate"}
                                 ,
                                 "render": function(data, type, row) {
                                     if (data.ServicesCreate == 1){
                                         return '<input class="toggle_status5" id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1">' ;
                                     }
                                     else{
                                         return '<input class="toggle_status5" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0">' ;
                                     }
                                 }
                             }
                            ],
                "fnDrawCallback": function() {
                    jQuery('.toggle_stutus').bootstrapToggle();
                    jQuery('.toggle_stutus').parent().addClass('toggleBtn');
                    jQuery('.toggle_status2').bootstrapToggle();
                    jQuery('.toggle_status2').parent().addClass('toggleBtn2');
                    jQuery('.toggle_status3').bootstrapToggle();
                    jQuery('.toggle_status3').parent().addClass('toggleBtn3');
                    jQuery('.toggle_status4').bootstrapToggle();
                    jQuery('.toggle_status4').parent().addClass('toggleBtn4');
                    jQuery('.toggle_status5').bootstrapToggle();
                    jQuery('.toggle_status5').parent().addClass('toggleBtn5');
                }
            }
                                   );
        }
        $(document).on('click','.toggleBtn',function(){
            $.ajax({
                url: "<?php echo EXEC; ?>Exec_AllPermission.php",
                type: 'post',
                data: {
                    id : $(this).children(".toggle_stutus").attr("id"),
                    status: $(this).children(".toggle_stutus").attr("value")
                }
                ,
                success: function(data){
                    alert('Status Update Successfully...');
                    dataTable()
                }
                ,
                error: function(errorThrown) {
                    alert('Sorry!Failed to Update Status');
                }
            }
                  );
        }
                      );
        $(document).on('click','.toggleBtn2',function(){
            $.ajax({
                url: "<?php echo EXEC; ?>Exec_AllPermission.php",
                type: 'post',
                data: {
                    id : $(this).children(".toggle_status2").attr("id"),
                    status2: $(this).children(".toggle_status2").attr("value")
                }
                ,
                success: function(data){
                    swal("Status Update Successfully...","", "success");
                    dataTable()
                }
                ,
                error: function(errorThrown) {
                    swal("Sorry!Failed to Update Status","", "error");
                    dataTable()
                }
            }
                  );
        }
                      );
        $(document).on('click','.toggleBtn3',function(){
            $.ajax({
                url: "<?php echo EXEC; ?>Exec_AllPermission.php",
                type: 'post',
                data: {
                    id : $(this).children(".toggle_status3").attr("id"),
                    status3: $(this).children(".toggle_status3").attr("value")
                }
                ,
                success: function(data){
                    swal("Status Update Successfully...","", "success");
                    dataTable()
                }
                ,
                error: function(errorThrown) {
                    swal("Sorry!Failed to Update Status","", "error");
                    dataTable()
                }
            }
                  );
        }
                      );
        $(document).on('click','.toggleBtn4',function(){
            $.ajax({
                url: "<?php echo EXEC; ?>Exec_AllPermission.php",
                type: 'post',
                data: {
                    id : $(this).children(".toggle_status4").attr("id"),
                    status4: $(this).children(".toggle_status4").attr("value")
                }
                ,
                success: function(data){
                    swal("Status Update Successfully...","", "success");
                    dataTable()
                }
                ,
                error: function(errorThrown) {
                    swal("Sorry!Failed to Update Status","", "error");
                    dataTable()
                }
            }
                  );
        }
                      );
        $(document).on('click','.toggleBtn5',function(){
            $.ajax({
                url: "<?php echo EXEC; ?>Exec_AllPermission.php",
                type: 'post',
                data: {
                    id : $(this).children(".toggle_status5").attr("id"),
                    status5: $(this).children(".toggle_status5").attr("value")
                }
                ,
                success: function(data){
                    swal("Status Update Successfully...","", "success");
                    dataTable()
                }
                ,
                error: function(errorThrown) {
                    swal("Sorry!Failed to Update Status","", "error");
                    dataTable()
                }
            }
                  );
        }
                      );
    }
                     );
</script>
</body>
</html>