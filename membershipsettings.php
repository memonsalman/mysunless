<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

    $button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C38'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];

    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C39'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C40'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];


    $button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C41'"); 
    $button4->execute();
    $all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
    $B4=$all_button4['button_name'];

    


if(isset($_POST['Buttonone']))
{
	
	 $button_name = $_POST['Buttonone'];
	 $button_name2 = $_POST['Buttonone2'];
	 $button_name3 = $_POST['Buttonone3'];
	 $button_name4 = $_POST['Buttonone4'];
	 
	
    $buttonupdate= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name WHERE button_id='C38'"); 
    $buttonupdate->bindParam(':button_name', $button_name, PDO::PARAM_STR);
    $buttonupdates = $buttonupdate->execute();

    $buttonupdate2= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name2 WHERE button_id='C39'"); 
    $buttonupdate2->bindParam(':button_name2', $button_name2, PDO::PARAM_STR);
    $buttonupdates2 = $buttonupdate2->execute();


    $buttonupdate3= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name3 WHERE button_id='C40'"); 
    $buttonupdate3->bindParam(':button_name3', $button_name3, PDO::PARAM_STR);
    $buttonupdates3 = $buttonupdate3->execute();


    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name4 WHERE button_id='C41'"); 
    $buttonupdate4->bindParam(':button_name4', $button_name4, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();


	if($buttonupdates)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}

    
$title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='13'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];  


$title3= $db->prepare("SELECT TitleName FROM `PageTitle` where id='14'"); 
    $title3->execute();
    $all_title3 = $title3->fetch(PDO::FETCH_ASSOC);
    $Ti3=$all_title3['TitleName'];  


   if(isset($_POST['Titleone']))
{
    
     $title_name = $_POST['Titleone'];
     $title_name2 = $_POST['Titleone2'];
     
     
    $titileupdate= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name WHERE id='13'"); 
    $titileupdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $titileupdates = $titileupdate->execute();

    $titileupdate2= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name2 WHERE id='14'"); 
    $titileupdate2->bindParam(':title_name2', $title_name2, PDO::PARAM_STR);
    $titileupdates2 = $titileupdate2->execute();

   
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
                                Membership Settings
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs customtab" role="tablist">
<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="fas fa-tachometer-alt"></i></span> <span class="hidden-xs-down">Button setting</span></a> </li>
<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i class="fas fa-heading"></i></span> <span class="hidden-xs-down">Page Setting</span></a> </li>
</a> </li>
</ul>

   <div class="col-lg-12">
                            <div class="tab-content">
                                <div class="tab-pane active" id="home2" role="tabpanel">
                                    <div class="p-20">
                            <div class="Loader"></div>

                                    	<form class="form-horizontal" id="Cbuttonnemae" method="post">
                                    		
                                    		<div class="form-group">
											<label>Membership Packages Button *</label>
											<input type="text"  id="Buttonone" name="Buttonone" value="<?php echo $B1; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Add New Membership Package Button *</label>
											<input type="text"  id="Buttonone2" name="Buttonone2" value="<?php echo $B2; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Submit Package Button *</label>
											<input type="text"  id="Buttonone3" name="Buttonone3" value="<?php echo $B3; ?>" class="form-control" autocomplete="nope"></input>
											</div>
											
											<div class="form-group">
											<label>Cancel Package Button *</label>
											<input type="text"  id="Buttonone4" name="Buttonone4" value="<?php echo $B4; ?>" class="form-control" autocomplete="nope"></input>
											</div>


			<div class="form-group">
			<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton" id="add-customerbutton"><i class="fa fa-check"></i> Save </button>
			</div>

                                    	</form>
                                        </div>
                                            </div>


                                    <div class="tab-pane " id="home3" role="tabpanel">
                                    <div class="p-20">
                                        <div class="Loader"></div>
                                        <form class="form-horizontal" id="Cbuttonnemae2" method="post">

                                            <div class="form-group">
                                            <label>Memberships Title *</label>
                                            <input type="text"  id="Titleone" name="Titleone" value="<?php echo $Ti2; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>List of Membership Package Title *</label>
                                            <input type="text"  id="Titleone2" name="Titleone2" value="<?php echo $Ti3; ?>" class="form-control" autocomplete="nope"></input>
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
                setInterval(function(){$(".setmem").addClass("active");}, 10);
                $("#Cbuttonnemae").validate({
            rules: {                
                Buttonone: "required",
                Buttonone2: "required",
                Buttonone3: "required",
                Buttonone4: "required",
                
                
            },
            messages: {             
                Buttonone:  "Please enter membership packages button name",
                Buttonone2:  "Please enter add New membership package button name",
                Buttonone3:  "Please enter submit package button name",
                Buttonone4:  "Please enter cancel package button name",
                
                
                
            },
     		 submitHandler: function() {
          $(".Loader").show();
              var form = $('#Cbuttonnemae')[0];
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

                $("#Cbuttonnemae2").validate({
            rules: {                
                Titleone: "required",
                Titleone2: "required",
                
                
            },
            messages: {             
                Titleone:  "Please enter Memberships title",
                Titleone2:  "Please enter List of Membership Package title",
                
                
                
                
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