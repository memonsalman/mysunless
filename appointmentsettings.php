<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

    
    $button11= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C11'"); 
    $button11->execute();
    $all_button11 = $button11->fetch(PDO::FETCH_ASSOC);
    $B11=$all_button11['button_name'];

    $button12= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C12'"); 
    $button12->execute();
    $all_button12 = $button12->fetch(PDO::FETCH_ASSOC);
    $B12=$all_button12['button_name'];

    $button13= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C13'"); 
    $button13->execute();
    $all_button13 = $button13->fetch(PDO::FETCH_ASSOC);
    $B13=$all_button13['button_name'];


    $button14= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C14'"); 
    $button14->execute();
    $all_button14 = $button14->fetch(PDO::FETCH_ASSOC);
    $B14=$all_button14['button_name'];


    $button15= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C15'"); 
    $button15->execute();
    $all_button15 = $button15->fetch(PDO::FETCH_ASSOC);
    $B15=$all_button15['button_name'];

    $button16= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C16'"); 
    $button16->execute();
    $all_button16 = $button16->fetch(PDO::FETCH_ASSOC);
    $B16=$all_button16['button_name'];

    $button17= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C17'"); 
    $button17->execute();
    $all_button17 = $button17->fetch(PDO::FETCH_ASSOC);
    $B17=$all_button17['button_name'];

    $button18= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C18'"); 
    $button18->execute();
    $all_button18 = $button18->fetch(PDO::FETCH_ASSOC);
    $B18=$all_button18['button_name'];

    $button19= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C19'"); 
    $button19->execute();
    $all_button19 = $button19->fetch(PDO::FETCH_ASSOC);
    $B19=$all_button19['button_name'];

    $button20= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C20'"); 
    $button20->execute();
    $all_button20 = $button20->fetch(PDO::FETCH_ASSOC);
    $B20=$all_button20['button_name'];

    

if(isset($_POST['Buttonone2']))
{
    
     
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
     


    

    $buttonupdate= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name2 WHERE button_id='C11'"); 
    $buttonupdate->bindParam(':button_name2', $button_name2, PDO::PARAM_STR);
    $buttonupdates = $buttonupdate->execute();

    $buttonupdate2= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name3 WHERE button_id='C12'"); 
    $buttonupdate2->bindParam(':button_name3', $button_name3, PDO::PARAM_STR);
    $buttonupdates2 = $buttonupdate2->execute();


    $buttonupdate3= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name4 WHERE button_id='C13'"); 
    $buttonupdate3->bindParam(':button_name4', $button_name4, PDO::PARAM_STR);
    $buttonupdates3 = $buttonupdate3->execute();


    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name5 WHERE button_id='C14'"); 
    $buttonupdate4->bindParam(':button_name5', $button_name5, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();


    $buttonupdate5= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name6 WHERE button_id='C15'"); 
    $buttonupdate5->bindParam(':button_name6', $button_name6, PDO::PARAM_STR);
    $buttonupdates5 = $buttonupdate5->execute();



    $buttonupdate6= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name7 WHERE button_id='C16'"); 
    $buttonupdate6->bindParam(':button_name7', $button_name7, PDO::PARAM_STR);
    $buttonupdates6 = $buttonupdate6->execute();



    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name8 WHERE button_id='C17'"); 
    $buttonupdate4->bindParam(':button_name8', $button_name8, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();



    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name9 WHERE button_id='C18'"); 
    $buttonupdate4->bindParam(':button_name9', $button_name9, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();



    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name10 WHERE button_id='C19'"); 
    $buttonupdate4->bindParam(':button_name10', $button_name10, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();

    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name11 WHERE button_id='C20'"); 
    $buttonupdate4->bindParam(':button_name11', $button_name11, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();

    
    
    if($buttonupdates)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}

      $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='4'"); 
    $title1->execute();
    $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
    $T1=$all_title1['TitleName'];


    $title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='5'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];


if(isset($_POST['Titleone']))
{
    
     $title_name = $_POST['Titleone'];
     $title_name2 = $_POST['Titleone2'];
     
    $titileupdate= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name WHERE id='4'"); 
    $titileupdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $titileupdates = $titileupdate->execute();

    $titileupdate2= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name2 WHERE id='5'"); 
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
                                Appointment Settings
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
                                            <label>Show Today Button *</label>
                                            <input type="text"  id="Buttonone2" name="Buttonone2" value="<?php echo $B11; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add New Appointment Button *</label>
                                            <input type="text"  id="Buttonone3" name="Buttonone3" value="<?php echo $B12; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>New Client Button *</label>
                                            <input type="text"  id="Buttonone4" name="Buttonone4" value="<?php echo $B13; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>
                                            
                                            <div class="form-group">
                                            <label>Exiting Client *</label>
                                            <input type="text"  id="Buttonone5" name="Buttonone5" value="<?php echo $B14; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add Appointment Button *</label>
                                            <input type="text"  id="Buttonone6" name="Buttonone6" value="<?php echo $B15; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Cancel Appointment Button *</label>
                                            <input type="text"  id="Buttonone7" name="Buttonone7" value="<?php echo $B16; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Also Update Client Button *</label>
                                            <input type="text"  id="Buttonone8" name="Buttonone8" value="<?php echo $B17; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add Client Button *</label>
                                            <input type="text"  id="Buttonone9" name="Buttonone9" value="<?php echo $B18; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Cancel Client Button *</label>
                                            <input type="text"  id="Buttonone10" name="Buttonone10" value="<?php echo $B19; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Back Button *</label>
                                            <input type="text"  id="Buttonone11" name="Buttonone11" value="<?php echo $B20; ?>" class="form-control" autocomplete="nope"></input>
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
                                            <label>Appointment list Title *</label>
                                            <input type="text"  id="Titleone" name="Titleone" value="<?php echo $T1; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Edit appointment Title *</label>
                                            <input type="text"  id="Titleone2" name="Titleone2" value="<?php echo $Ti2; ?>" class="form-control" autocomplete="nope"></input>
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
                setInterval(function(){$(".setappo").addClass("active");}, 10);
                $("#Cbuttonnemae").validate({
            rules: {                
                
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
                
            },
            messages: {             
                
                Buttonone2:  "Please enter show today button name",
                Buttonone3:  "Please enter add new appointment button name",
                Buttonone4:  "Please enter new client button name",
                Buttonone5:  "Please enter exiting client button name",
                Buttonone6:  "Please enter add appointment button name",
                Buttonone7:  "Please enter cancel appointment button name",
                Buttonone8:  "Please enter also update Client button name",
                Buttonone9:  "Please enter add client button name",
                Buttonone10:  "Please enter cancel client button name",
                Buttonone11:  "Please enter back  button name",
                Buttonone12:  "Please enter report bug  button name",
                
                
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
                Titleone:  "Please enter appointment List title",
                Titleone2:  "Please enter edit appointment title",
                
                
                
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