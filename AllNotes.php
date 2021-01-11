<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
$db=new db();
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
    <link href="<?php echo base_url; ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url; ?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?php echo base_url; ?>/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url; ?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <style>
        /*.modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
        url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
        body.loading .modal {overflow: hidden;}
        body.loading .modal {display: block;}
        .eventStart1{width: 45%; float: left; padding: 0 10px;}
        .timeinput{width: 35%; float: left; padding: 0 15px;}*/
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
                                Edit Notes
                            </h4>
                            <?php       
}
else
{
                            ?>
                            <h4 class="text-themecolor">
                                All Notes List
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
                                    <div class="col-lg-12">
                                        <a href=" 
<?php echo base_url;  ?>/AddNote.php" id="addNote" class="btn btn-info m-r-10 ">Add New Note</a>
                                        <div class="table-responsive m-t-40 col-md-12">
                                            <table id="TodoTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Note Title
                                                        </th>
                                                        <th>
                                                            Note Detail
                                                        </th>
                                                        <th>
                                                            Related To
                                                        </th>
                                                        <th>
                                                            Date Created
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
        <script src="
<?php echo base_url; ?>/assets/node_modules/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <!-- for change date format -->
        <script type="text/javascript" src="
<?php echo base_url; ?>/assets/node_modules/jqueryui/jquery-ui.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#TodoTable').DataTable({
                    "processing" : true,
                    "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_Edit_Note.php",
                        "dataSrc" : ''
                    }
                    ,
                    "columns" : [ 
                        {
                            "data" : "noteTitle"
                        }
                        , {
                            "data" : "noteDetail",
                        }
                        , {
                            "data" : "noteRelated",
                        }
                        , {
                            "data" : "datecreated",
                            "render" : function(data,type,row){
                                var date =  ($.datepicker.formatDate('dd M yy',new Date(data) ));
                                return date ;
                            }
                        }
                        ,
                        {
                            "data": "id",
                            "render": function(data, type, row) {
                                return '<a class="btn btn-info btn-sm" title="Edit Event" href=AddNote.php?action=edit&id='+ data + '>' + '<span class="fa fa-edit"><span>' + '</a> <a class="btn btn-danger btn-sm" title="Delete Event" id="deleteNote" href=AddNote.php?action=delete&id='+ data + '>' + '<span class="fa fa-trash"><span>' + '</a>';
                            }
                        }
                    ]
                }
                                         );
            }
                             );
            $(document).ready(function(){
                $(document).on('click','#deleteNote',function(e){
                    e.preventDefault();
                    var link = $(this).attr('href');
                    swal({
                        title: "Are you sure?",
                        text: "you are really want to delete this Note!",
                        icon: "warning",
                        buttons: true,
                    }
                        ).then((willDelete)=>{
                        if (willDelete){
                        window.location.href = link;
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
</script>
</body>
</html>
