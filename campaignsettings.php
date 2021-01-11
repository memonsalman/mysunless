<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C62'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];

    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C63'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C64'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];


    $button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C65'"); 
    $button4->execute();
    $all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
    $B4=$all_button4['button_name'];

    $button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C66'"); 
    $button5->execute();
    $all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
    $B5=$all_button5['button_name'];

    $button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C67'"); 
    $button6->execute();
    $all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
    $B6=$all_button6['button_name'];


    $button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C68'"); 
    $button7->execute();
    $all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
    $B7=$all_button7['button_name'];


    $button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C69'"); 
    $button8->execute();
    $all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
    $B8=$all_button8['button_name'];

    $button9= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C70'"); 
    $button9->execute();
    $all_button9 = $button9->fetch(PDO::FETCH_ASSOC);
    $B9=$all_button9['button_name'];

    $button10= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C71'"); 
    $button10->execute();
    $all_button10 = $button10->fetch(PDO::FETCH_ASSOC);
    $B10=$all_button10['button_name'];



if(isset($_POST['Buttonone']))
{
	
	 $button_name = $_POST['Buttonone'];
	 $button_name2 = $_POST['Buttonone2'];
	 $button_name3 = $_POST['Buttonone3'];
	 $button_name4 = $_POST['Buttonone4'];
	 $button_name5 = $_POST['Buttonone5'];
	 $button_name6 = $_POST['Buttonone6'];
	 $button_name7 = $_POST['Buttonone7'];
	 $button_name8 = $_POST['Buttonone8'];
     $button_name9 = $_POST['Buttonone9'];
     $button_name10 = $_POST['Buttonone10'];
	 


	$buttonupdate= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name WHERE button_id='C62'"); 
    $buttonupdate->bindParam(':button_name', $button_name, PDO::PARAM_STR);
    $buttonupdates = $buttonupdate->execute();

    $buttonupdate2= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name2 WHERE button_id='C63'"); 
    $buttonupdate2->bindParam(':button_name2', $button_name2, PDO::PARAM_STR);
    $buttonupdates2 = $buttonupdate2->execute();


    $buttonupdate3= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name3 WHERE button_id='C64'"); 
    $buttonupdate3->bindParam(':button_name3', $button_name3, PDO::PARAM_STR);
    $buttonupdates3 = $buttonupdate3->execute();


    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name4 WHERE button_id='C65'"); 
    $buttonupdate4->bindParam(':button_name4', $button_name4, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();


    $buttonupdate5= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name5 WHERE button_id='C66'"); 
    $buttonupdate5->bindParam(':button_name5', $button_name5, PDO::PARAM_STR);
    $buttonupdates5 = $buttonupdate5->execute();



    $buttonupdate6= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name6 WHERE button_id='C67'"); 
    $buttonupdate6->bindParam(':button_name6', $button_name6, PDO::PARAM_STR);
    $buttonupdates6 = $buttonupdate6->execute();



    $buttonupdate7= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name7 WHERE button_id='C68'"); 
    $buttonupdate7->bindParam(':button_name7', $button_name7, PDO::PARAM_STR);
    $buttonupdates7 = $buttonupdate7->execute();



    $buttonupdate8= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name8 WHERE button_id='C69'"); 
    $buttonupdate8->bindParam(':button_name8', $button_name8, PDO::PARAM_STR);
    $buttonupdates8 = $buttonupdate8->execute();

    $buttonupdate9= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name9 WHERE button_id='C70'"); 
    $buttonupdate9->bindParam(':button_name9', $button_name9, PDO::PARAM_STR);
    $buttonupdates9 = $buttonupdate9->execute();

    $buttonupdate10= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name10 WHERE button_id='C71'"); 
    $buttonupdate10->bindParam(':button_name10', $button_name10, PDO::PARAM_STR);
    $buttonupdates10 = $buttonupdate10->execute();


	if($buttonupdates)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}
  

    $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='16'"); 
    $title1->execute();
    $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
    $T1=$all_title1['TitleName'];


    $title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='17'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];

    $title3= $db->prepare("SELECT TitleName FROM `PageTitle` where id='18'"); 
    $title3->execute();
    $all_title3 = $title3->fetch(PDO::FETCH_ASSOC);
    $Ti3=$all_title3['TitleName'];

    $title4= $db->prepare("SELECT TitleName FROM `PageTitle` where id='19'"); 
    $title4->execute();
    $all_title4 = $title4->fetch(PDO::FETCH_ASSOC);
    $Ti4=$all_title4['TitleName'];

    $title5= $db->prepare("SELECT TitleName FROM `PageTitle` where id='20'"); 
    $title5->execute();
    $all_title5 = $title5->fetch(PDO::FETCH_ASSOC);
    $Ti5=$all_title5['TitleName'];

    if(isset($_POST['Titleone']))
{
    
     $title_name = $_POST['Titleone'];
     $title_name2 = $_POST['Titleone2'];
     $title_name3 = $_POST['Titleone3'];
     $title_name4 = $_POST['Titleone4'];
     $title_name5 = $_POST['Titleone5'];
     
    $titileupdate= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name WHERE id='16'"); 
    $titileupdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $titileupdates = $titileupdate->execute();

    $titileupdate2= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name2 WHERE id='17'"); 
    $titileupdate2->bindParam(':title_name2', $title_name2, PDO::PARAM_STR);
    $titileupdates2 = $titileupdate2->execute();

    $titileupdate3= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name3 WHERE id='18'"); 
    $titileupdate3->bindParam(':title_name3', $title_name3, PDO::PARAM_STR);
    $titileupdates3 = $titileupdate3->execute();

    $titileupdate4= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name4 WHERE id='19'"); 
    $titileupdate4->bindParam(':title_name4', $title_name4, PDO::PARAM_STR);
    $titileupdates4 = $titileupdate4->execute();

    $titileupdate5= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name5 WHERE id='20'"); 
    $titileupdate5->bindParam(':title_name5', $title_name5, PDO::PARAM_STR);
    $titileupdates5 = $titileupdate5->execute();

   
   
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
                                Campaign Settings
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
											<label>Add Campaigns Button *</label>
											<input type="text"  id="Buttonone" name="Buttonone" value="<?php echo $B1; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Add Category  Button *</label>
											<input type="text"  id="Buttonone2" name="Buttonone2" value="<?php echo $B2; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Add Custom Campaign Button *</label>
											<input type="text"  id="Buttonone3" name="Buttonone3" value="<?php echo $B3; ?>" class="form-control" autocomplete="nope"></input>
											</div>
											
											<div class="form-group">
											<label>Submit Campaigns Button *</label>
											<input type="text"  id="Buttonone4" name="Buttonone4" value="<?php echo $B4; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Cancel Campaigns Button *</label>
											<input type="text"  id="Buttonone5" name="Buttonone5" value="<?php echo $B5; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Confirm Campaigns Button *</label>
											<input type="text"  id="Buttonone6" name="Buttonone6" value="<?php echo $B6; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Cancel Button *</label>
											<input type="text"  id="Buttonone7" name="Buttonone7" value="<?php echo $B7; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Add New Category Button *</label>
											<input type="text"  id="Buttonone8" name="Buttonone8" value="<?php echo $B8; ?>" class="form-control" autocomplete="nope"></input>
											</div>

                                            <div class="form-group">
                                            <label>Submit Category Button *</label>
                                            <input type="text"  id="Buttonone9" name="Buttonone9" value="<?php echo $B9; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label> Cancel Category *</label>
                                            <input type="text"  id="Buttonone10" name="Buttonone10" value="<?php echo $B10; ?>" class="form-control" autocomplete="nope"></input>
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
                                            <label>List Of Campaigns Category Title *</label>
                                            <input type="text"  id="Titleone" name="Titleone" value="<?php echo $T1; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>List Of Campaigns Title *</label>
                                            <input type="text"  id="Titleone2" name="Titleone2" value="<?php echo $Ti2; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>List Of Campaigns Template Title *</label>
                                            <input type="text"  id="Titleone3" name="Titleone3" value="<?php echo $Ti3; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add New Campaigns Title *</label>
                                            <input type="text"  id="Titleone4" name="Titleone4" value="<?php echo $Ti4; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Edit Campaigns Title *</label>
                                            <input type="text"  id="Titleone5" name="Titleone5" value="<?php echo $Ti5; ?>" class="form-control" autocomplete="nope"></input>
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
                setInterval(function(){$(".setcamp").addClass("active");}, 10);
                $("#Cbuttonnemae").validate({
            rules: {                
                Buttonone: "required",
                Buttonone2: "required",
                Buttonone3: "required",
                Buttonone4: "required",
                Buttonone5: "required",
                Buttonone6: "required",
                Buttonone7: "required",
                Buttonone8: "required",
                Buttonone9: "required",
                Buttonone10: "required",
                
                
            },
            messages: {             
                Buttonone:  "Please enter add campaigns button name",
                Buttonone2:  "Please enter add category button name",
                Buttonone3:  "Please enter add custom campaign button name",
                Buttonone4:  "Please enter submit campaigns button name",
                Buttonone5:  "Please enter cancel campaigns button name",
                Buttonone6:  "Please enter confirm campaigns button name",
                Buttonone7:  "Please enter cancel button name",
                Buttonone8:  "Please enter add new category button name",
                Buttonone9:  "Please enter submit category button name",
                Buttonone10:  "Please enter cancel category button name",
                
                
                
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
                Titleone3: "required",
                Titleone4: "required",
                Titleone5: "required",                
            },
            messages: {             
                Titleone:  "Please list of campaigns category title",
                Titleone2:  "Please list of campaigns title",
                Titleone3:  "Please list campaigns template title",
                Titleone4:  "Please add new campaigns title",
                Titleone5:  "Please edit campaigns title",
                
                
                
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