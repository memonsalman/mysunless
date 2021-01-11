<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}

if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}


   $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='35'"); 
  $title1->execute();
  $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
  $T1=$all_title1['TitleName'];

    if(isset($_POST['Titleone']))
{
    
     $title_name = $_POST['Titleone'];

     $titileupdate= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name WHERE id='35'"); 
    $titileupdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $titileupdates = $titileupdate->execute();

    if($titileupdates)
    {
    echo json_encode(['resonse'=>'Title  has been successfully changed']);die;
    } 
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <style type="text/css">
    	.ButtonTable{width: 100%}
    	.saveButton,.orignalbutton{width: 15%; float: left; padding: 0 10px; }
    	.orignalinptut{width: 20%;float: left; padding: 0 10px;}
    </style>
    <body class="skin-default fixed-layout mysunlessO">
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
                                FAQS Settings
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                     <ul class="nav nav-tabs customtab" role="tablist">

<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i class="fas fa-heading"></i></span> <span class="hidden-xs-down">Page Setting</span></a> </li>
</a> </li>
                            </ul>

                                    <div class="col-lg-12">
                                 
                                        <div class="tab-content">
                                <div class="tab-pane active" id="home3" role="tabpanel">
                                    <div class="p-20">
                            <div class="Loader"></div>
                        <form class="form-horizontal" id="Cbuttonnemae2" method="post">

                                            <div class="form-group">
                                            <label>Faqs Title *</label>
                                            <input type="text"  id="Titleone" name="Titleone" value="<?php echo $T1; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>


                                            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton" id="add-customerbutton"><i class="fa fa-check"></i> Save </button>
            </div>

                                    </form>         

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

<script type="text/javascript">
$(document).ready(function() {
                //sidebar dropdown
                $(".adminpage").trigger("click");
                //active class
                setInterval(function(){$(".setfaq").addClass("active");}, 10);
                $("#Cbuttonnemae2").validate({
            rules: {                
                Titleone: "required",
                       
            },
            messages: {             
                Titleone:  "Please enter Faqs title",
                
                
                
            },
             submitHandler: function() {
          $(".Loader").show();
              var form = $('#Cbuttonnemae2')[0];
               var data = new FormData(form);
              // var data = $("#Cbuttonnemae").serialize();
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'?Buttonone',
                    success: function(data)
                {
                if(data.resonse)
                {
                   $(".Loader").hide();
                   swal(data.resonse)

                }
                else if(data.error)
                {
                   
                }
                
                 }
                
                 });
                }           
        });

});
</script>
        
</body>
</html>