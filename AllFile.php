<?php 
require_once('function.php');
/*echo  $encodeed =  EncodeId(15);
echo "<br>".DecodeId($encodeed)."<br>";die;*/
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
    @$clientcreateprmistion=$result['ClientCreate'];
}
if($clientcreateprmistion==0){
    header("Location: index.php");die;  
}    
if(isset($_GET['did']))
{
    $db=new db();
    $id= base64_decode($_GET['did']);
    $DeleteClient = $db->prepare("delete from `attechment` where id=:id");
    $DeleteClient->bindValue(":id",$id,PDO::PARAM_INT);
    $deletefile=$DeleteClient->execute();
    if(isset($_GET['cid'])){
        header('Location: ViewClient.php?action=view&id='.$_GET['cid'] );die;
    }
    else{
        header('Location: AllFile.php');die;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
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
                                Document List
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
                                                <div class="table-responsive m-t-40">
                                                    <table id="myTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    Document
                                                                </th>
                                                                <th>
                                                                    Title
                                                                </th>
                                                                <th>
                                                                    First Name
                                                                </th>
                                                                <th>
                                                                    Last Name
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "processing" : true,
            "ajax" : {
                "url" : "<?php echo EXEC; ?>Exec_All_attech.php",
                dataSrc : ''
            }
            ,
            "columns" : [ {
                "data": "document",
                "render": function(data, type, row) {
                    console.log(data);
                    if(data!=''){
                        return '<a href="<?php echo $base_url ?>/assets/ClientDocs/'+data+'">View/Download</a>';
                    }
                    else
                    {
                        return '<img src="<?php echo $base_url ?>/assets/images/'+'nofile.jpg'+'" style="height: 70px; width: 110px;" />';
                    }
                }
            }
                         ,{
                             "data" : "fileName"
                         }
                         ,
                         {
                             "data" : "FirstName"
                         }
                         ,
                         {
                             "data" : "LastName"
                         }
                         ,
                         {
                             "data": "id",
                             "render": function(data, type, row) {
                                 return '<a class="btn btn-danger btn-sm delete" title="Delete Data"  id="deleteButton" href="AllFile?action=deletefile&did='+ data +'"><span class="fa fa-trash"><span></a>';
                             }
                         }
                        ]
        }
                               );
        $(document).on('click','#deleteButton',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not recover this document!",
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
