<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}

if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

    $button1= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='1'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['TutorialMsg'];


    $button2= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='2'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['TutorialMsg'];

    $button3= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='3'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['TutorialMsg'];

    $button4= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='4'"); 
    $button4->execute();
    $all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
    $B4=$all_button4['TutorialMsg'];

    $button5= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='5'"); 
    $button5->execute();
    $all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
    $B5=$all_button5['TutorialMsg'];

    $button6= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='6'"); 
    $button6->execute();
    $all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
    $B6=$all_button6['TutorialMsg'];

    $button7= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='7'"); 
    $button7->execute();
    $all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
    $B7=$all_button7['TutorialMsg'];

    $button8= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='8'"); 
    $button8->execute();
    $all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
    $B8=$all_button8['TutorialMsg'];

    $button9= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='9'"); 
    $button9->execute();
    $all_button9 = $button9->fetch(PDO::FETCH_ASSOC);
    $B9=$all_button9['TutorialMsg'];

    $button10= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='10'"); 
    $button10->execute();
    $all_button10 = $button10->fetch(PDO::FETCH_ASSOC);
    $B10=$all_button10['TutorialMsg'];

    $button11= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='11'"); 
    $button11->execute();
    $all_button11 = $button11->fetch(PDO::FETCH_ASSOC);
    $B11=$all_button11['TutorialMsg'];

    $button12= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='12'"); 
    $button12->execute();
    $all_button12 = $button12->fetch(PDO::FETCH_ASSOC);
    $B12=$all_button12['TutorialMsg'];


    $button13= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='13'"); 
    $button13->execute();
    $all_button13 = $button13->fetch(PDO::FETCH_ASSOC);
    $B13=$all_button13['TutorialMsg'];

    $button14= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='14'"); 
    $button14->execute();
    $all_button14 = $button14->fetch(PDO::FETCH_ASSOC);
    $B14=$all_button14['TutorialMsg'];

   $button15= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='15'"); 
    $button15->execute();
    $all_button15 = $button15->fetch(PDO::FETCH_ASSOC);
    $B15=$all_button15['TutorialMsg'];

    $button16= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='16'"); 
    $button16->execute();
    $all_button16 = $button16->fetch(PDO::FETCH_ASSOC);
    $B16=$all_button16['TutorialMsg'];

    $button17= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='17'"); 
    $button17->execute();
    $all_button17 = $button17->fetch(PDO::FETCH_ASSOC);
    $B17=$all_button17['TutorialMsg'];

    $button18= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='18'"); 
    $button18->execute();
    $all_button18 = $button18->fetch(PDO::FETCH_ASSOC);
    $B18=$all_button18['TutorialMsg'];


    $button19= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='19'"); 
    $button19->execute();
    $all_button19 = $button19->fetch(PDO::FETCH_ASSOC);
    $B19=$all_button19['TutorialMsg'];   


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
     $button_name11 = $_POST['Buttonone11'];
     $button_name12 = $_POST['Buttonone12'];
     $button_name13 = $_POST['Buttonone13'];
     $button_name14 = $_POST['Buttonone14'];
     $button_name15 = $_POST['Buttonone15'];
     $button_name16 = $_POST['Buttonone16'];
     $button_name17 = $_POST['Buttonone17'];
     $button_name18 = $_POST['Buttonone18'];
     $button_name19 = $_POST['Buttonone19'];



	$buttonupdate= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name WHERE id='1'"); 
    $buttonupdate->bindParam(':button_name', $button_name, PDO::PARAM_STR);
    $buttonupdates = $buttonupdate->execute();

    $buttonupdate2= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name2 WHERE id='2'"); 
    $buttonupdate2->bindParam(':button_name2', $button_name2, PDO::PARAM_STR);
    $buttonupdates2 = $buttonupdate2->execute();


    $buttonupdate3= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name3 WHERE id='3'"); 
    $buttonupdate3->bindParam(':button_name3', $button_name3, PDO::PARAM_STR);
    $buttonupdates3 = $buttonupdate3->execute();


    $buttonupdate4= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name4 WHERE id='4'"); 
    $buttonupdate4->bindParam(':button_name4', $button_name4, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();


    $buttonupdate5= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name5 WHERE id='5'"); 
    $buttonupdate5->bindParam(':button_name5', $button_name5, PDO::PARAM_STR);
    $buttonupdates5 = $buttonupdate5->execute();



    $buttonupdate6= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name6 WHERE id='6'"); 
    $buttonupdate6->bindParam(':button_name6', $button_name6, PDO::PARAM_STR);
    $buttonupdates6 = $buttonupdate6->execute();



    $buttonupdate7= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name7 WHERE id='7'"); 
    $buttonupdate7->bindParam(':button_name7', $button_name7, PDO::PARAM_STR);
    $buttonupdates7 = $buttonupdate7->execute();



    $buttonupdate8= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name8 WHERE id='8'"); 
    $buttonupdate8->bindParam(':button_name8', $button_name8, PDO::PARAM_STR);
    $buttonupdates8 = $buttonupdate8->execute();



    $buttonupdate9= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name9 WHERE id='9'"); 
    $buttonupdate9->bindParam(':button_name9', $button_name9, PDO::PARAM_STR);
    $buttonupdates9 = $buttonupdate9->execute();


    $buttonupdate10= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name10 WHERE id='10'"); 
    $buttonupdate10->bindParam(':button_name10', $button_name10, PDO::PARAM_STR);
    $buttonupdates10 = $buttonupdate10->execute();

    $buttonupdate11= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name11 WHERE id='11'"); 
    $buttonupdate11->bindParam(':button_name11', $button_name11, PDO::PARAM_STR);
    $buttonupdates11 = $buttonupdate11->execute();


    $buttonupdate12= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name12 WHERE id='12'"); 
    $buttonupdate12->bindParam(':button_name12', $button_name12, PDO::PARAM_STR);
    $buttonupdates12 = $buttonupdate12->execute();


    $buttonupdate13= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name13 WHERE id='13'"); 
    $buttonupdate13->bindParam(':button_name13', $button_name13, PDO::PARAM_STR);
    $buttonupdates13 = $buttonupdate13->execute();

    $buttonupdate14= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name14 WHERE id='14'"); 
    $buttonupdate14->bindParam(':button_name14', $button_name14, PDO::PARAM_STR);
    $buttonupdates14 = $buttonupdate14->execute();

    $buttonupdate15= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name15 WHERE id='15'"); 
    $buttonupdate15->bindParam(':button_name15', $button_name15, PDO::PARAM_STR);
    $buttonupdates15 = $buttonupdate15->execute();

    $buttonupdate16= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name16 WHERE id='16'"); 
    $buttonupdate16->bindParam(':button_name16', $button_name16, PDO::PARAM_STR);
    $buttonupdates16 = $buttonupdate16->execute();

    $buttonupdate17= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name17 WHERE id='17'"); 
    $buttonupdate17->bindParam(':button_name17', $button_name17, PDO::PARAM_STR);
    $buttonupdates17 = $buttonupdate17->execute();

    $buttonupdate18= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name18 WHERE id='18'"); 
    $buttonupdate18->bindParam(':button_name18', $button_name18, PDO::PARAM_STR);
    $buttonupdates18 = $buttonupdate18->execute();

    $buttonupdate19= $db->prepare("UPDATE `Tutorial` SET TutorialMsg=:button_name19 WHERE id='19'"); 
    $buttonupdate19->bindParam(':button_name19', $button_name19, PDO::PARAM_STR);
    $buttonupdates19 = $buttonupdate19->execute();

	if($buttonupdates)
    {
    echo json_encode(['resonse'=>'Tutorial has been successfully changed']);die;
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
                                Dashboard Tutorial
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                      	<div class="Loader"></div>

                                    	<form class="form-horizontal" id="Cbuttonnemae" method="post">
                                    		
                                    		<div class="form-group">
											<label>welcome *</label>
											<input type="text"  id="Buttonone" name="Buttonone" value="<?php echo $B1; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Sidebar toggler *</label>
											<input type="text"  id="Buttonone2" name="Buttonone2" value="<?php echo $B2; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>View list of appointment *</label>
											<input type="text"  id="Buttonone3" name="Buttonone3" value="<?php echo $B3; ?>" class="form-control" autocomplete="nope"></input>
											</div>
											
											<div class="form-group">
											<label>View list Of To-Do *</label>
											<input type="text"  id="Buttonone4" name="Buttonone4" value="<?php echo $B4; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Tutorial *</label>
											<input type="text"  id="Buttonone5" name="Buttonone5" value="<?php echo $B5; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>FAQS *</label>
											<input type="text"  id="Buttonone6" name="Buttonone6" value="<?php echo $B6; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Profile *</label>
											<input type="text"  id="Buttonone7" name="Buttonone7" value="<?php echo $B7; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Number Of customer *</label>
											<input type="text"  id="Buttonone8" name="Buttonone8" value="<?php echo $B8; ?>" class="form-control" autocomplete="nope"></input>
											</div>

											<div class="form-group">
											<label>Number Of appointment *</label>
											<input type="text"  id="Buttonone9" name="Buttonone9" value="<?php echo $B9; ?>" class="form-control" autocomplete="nope"></input>
											</div>

                                            <div class="form-group">
                                            <label>Number Of To-Do *</label>
                                            <input type="text"  id="Buttonone10" name="Buttonone10" value="<?php echo $B10; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Appointment calendar *</label>
                                            <input type="text"  id="Buttonone11" name="Buttonone11" value="<?php echo $B11; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>To-Do *</label>
                                            <input type="text"  id="Buttonone12" name="Buttonone12" value="<?php echo $B12; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Due customers *</label>
                                            <input type="text"  id="Buttonone13" name="Buttonone13" value="<?php echo $B13; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Activities *</label>
                                            <input type="text"  id="Buttonone14" name="Buttonone14" value="<?php echo $B14; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>


                                            <div class="form-group">
                                            <label>Top 5 best customers *</label>
                                            <input type="text"  id="Buttonone15" name="Buttonone15" value="<?php echo $B15; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>List of top employees *</label>
                                            <input type="text"  id="Buttonone16" name="Buttonone16" value="<?php echo $B16; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Admin Post *</label>
                                            <input type="text"  id="Buttonone17" name="Buttonone17" value="<?php echo $B17; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add new widget *</label>
                                            <input type="text"  id="Buttonone18" name="Buttonone18" value="<?php echo $B18; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Send Report or Bug *</label>
                                            <input type="text"  id="Buttonone19" name="Buttonone19" value="<?php echo $B19; ?>" class="form-control" autocomplete="nope"></input>
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
                //active class
                setInterval(function(){$(".tutorial").addClass("active");}, 10);
                
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
                Buttonone11: "required",
                Buttonone12: "required",
                Buttonone13: "required",
                Buttonone14: "required",
                Buttonone15: "required",
                Buttonone16: "required",
                Buttonone17: "required",
                Buttonone18: "required",
                Buttonone19: "required",


                
            },
            messages: {             
                Buttonone:  "Please enter welcome",
                Buttonone2:  "Please enter Sidebar toggler",
                Buttonone3:  "Please enter view list of appointment",
                Buttonone4:  "Please enter view list Of To-Do",
                Buttonone5:  "Please enter Tutorial",
                Buttonone6:  "Please enter FAQS",
                Buttonone7:  "Please enter Profile",
                Buttonone8:  "Please enter number Of customer",
                Buttonone9:  "Please enter number Of appointment",
                Buttonone10:  "Please enter number Of To-Do",
                Buttonone11:  "Please enter Appointment calendar",
                Buttonone12:  "Please enter List Of To-Do",
                Buttonone13:  "Please enter due customers",
                Buttonone14:  "Please enter activities",
                Buttonone15:  "Please enter top 5 best customers",
                Buttonone16:  "Please enter top employees",
                Buttonone17:  "Please enter post from admin",
                Buttonone18:  "Please enter new widget",
                Buttonone19:  "Please enter Send Report or Bug",
                
                
                
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

});
</script>
        
</body>
</html>