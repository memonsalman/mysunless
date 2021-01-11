<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}

  $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='35'"); 
  $title1->execute();
  $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
  $T1=$all_title1['TitleName'];


?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <style type="text/css">
        .links  {
            color: #212529;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            background-color:  #fff;
        }
        .links:hover:not(.active) {
            background-color: #ddd;
            color: #fff;
            background-color: #038fcd;
            border-color: #0286c1;
        }
        .links.selected {
            background-color: #3cabe1;
            color: white;
            border: 1px solid #3cabe1;
        }
    </style>
    <body class="skin-default fixed-layout mysunlessA13">
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
                                <?php echo $T1; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card-body">
                            <div id="result_Faqs">
                                <div id="result_Faqs_message" class="col-lg-12 col-md-12">
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
        <script>
            $(document).ready(function (){
                $("#myaccdrop").trigger("click");
                setInterval(function(){$(".gethelp").addClass("active");}, 10);
            });
            displayRecords(10, 1);
            function displayRecords(limit, start){
                $.ajax({
                    url:"<?php echo EXEC; ?>Exec_Faqs_Pagination.php",
                    method:"POST",
                    data:"limit="+limit+"&start="+start,
                    cache:false,
                    // beforeSend: function(data) {  
                    // $(".modal").show();
                    // },
                    success:function(data)
                    {
                        // $(".modal").hide();
                        if(data == '')
                        {
                            $('#result_Faqs_message').html("<button type='button' disabled class='btn btn-secondary' > No more FAQS Found </button>");
                        }
                        else
                        {
                            $('#result_Faqs').html(data);
                        }
                    }
                }
                      );
            }
</script>
</body>
</html>