<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

    $button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C94'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];


    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C95'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C96'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];

    $button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C97'"); 
    $button4->execute();
    $all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
    $B4=$all_button4['button_name'];

    $button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C98'"); 
    $button5->execute();
    $all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
    $B5=$all_button5['button_name'];

    $button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C99'"); 
    $button6->execute();
    $all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
    $B6=$all_button6['button_name'];


    $button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C100'"); 
    $button7->execute();
    $all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
    $B7=$all_button7['button_name'];


    $button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C101'"); 
    $button8->execute();
    $all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
    $B8=$all_button8['button_name'];

    

if(isset($_POST['Buttonone']))
{
    
     $button_name = $_POST['Buttonone'];
     $button_name2 = $_POST['Buttonone2'];
     
     


    $buttonupdate= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name WHERE button_id='C94'"); 
    $buttonupdate->bindParam(':button_name', $button_name, PDO::PARAM_STR);
    $buttonupdates = $buttonupdate->execute();

    $buttonupdate2= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name2 WHERE button_id='C95'"); 
    $buttonupdate2->bindParam(':button_name2', $button_name2, PDO::PARAM_STR);
    $buttonupdates2 = $buttonupdate2->execute();

    
    if($buttonupdates)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}

if(isset($_POST['Buttonone4']))
{   
     $button_name3 = $_POST['Buttonone3'];
     $button_name4 = $_POST['Buttonone4'];
     // $button_name5 = $_POST['Buttonone5'];
    
    $buttonupdate3= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name3 WHERE button_id='C96'"); 
    $buttonupdate3->bindParam(':button_name3', $button_name3, PDO::PARAM_STR);
    $buttonupdates3 = $buttonupdate3->execute();
    
    $buttonupdate4= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name4 WHERE button_id='C97'"); 
    $buttonupdate4->bindParam(':button_name4', $button_name4, PDO::PARAM_STR);
    $buttonupdates4 = $buttonupdate4->execute();

    // $buttonupdate5= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name5 WHERE button_id='C93'"); 
    // $buttonupdate5->bindParam(':button_name5', $button_name5, PDO::PARAM_STR);
    // $buttonupdates5 = $buttonupdate5->execute();

   
    if($buttonupdates4)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}

if(isset($_POST['Buttonone5']))
{   
     
      $button_name5 = $_POST['Buttonone5'];
      $button_name6 = $_POST['Buttonone6'];
      $button_name7 = $_POST['Buttonone7'];
      $button_name8 = $_POST['Buttonone8'];
    
    $buttonupdate5= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name5 WHERE button_id='C98'"); 
    $buttonupdate5->bindParam(':button_name5', $button_name5, PDO::PARAM_STR);
    $buttonupdates5 = $buttonupdate5->execute();

    $buttonupdate6= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name6 WHERE button_id='C99'"); 
    $buttonupdate6->bindParam(':button_name6', $button_name6, PDO::PARAM_STR);
    $buttonupdates6 = $buttonupdate6->execute();

    $buttonupdate7= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name7 WHERE button_id='C100'"); 
    $buttonupdate7->bindParam(':button_name7', $button_name7, PDO::PARAM_STR);
    $buttonupdates7 = $buttonupdate7->execute();

    $buttonupdate8= $db->prepare("UPDATE `ButtonSetting` SET button_name=:button_name8 WHERE button_id='C101'"); 
    $buttonupdate8->bindParam(':button_name8', $button_name8, PDO::PARAM_STR);
    $buttonupdates8 = $buttonupdate8->execute();
   
    if($buttonupdate5)
    {
    echo json_encode(['resonse'=>'Button name has been successfully changed']);die;
    } 
}

  $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='30'"); 
  $title1->execute();
  $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
  $T1=$all_title1['TitleName'];

  $titlei2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='31'"); 
  $titlei2->execute();
  $all_titlei2 = $titlei2->fetch(PDO::FETCH_ASSOC);
  $Ti2=$all_titlei2['TitleName'];

  $titlei3= $db->prepare("SELECT TitleName FROM `PageTitle` where id='32'"); 
  $titlei3->execute();
  $all_titlei3 = $titlei3->fetch(PDO::FETCH_ASSOC);
  $Ti3=$all_titlei3['TitleName'];

if(isset($_POST['Titleone']))
{
    
     $title_name = $_POST['Titleone'];
     $title_name2 = $_POST['Titleone2'];
     $title_name3 = $_POST['Titleone3'];
     

     $titileupdate= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name WHERE id='30'"); 
    $titileupdate->bindParam(':title_name', $title_name, PDO::PARAM_STR);
    $titileupdates = $titileupdate->execute();

    $titileupdate2= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name2 WHERE id='31'"); 
    $titileupdate2->bindParam(':title_name2', $title_name2, PDO::PARAM_STR);
    $titileupdates2 = $titileupdate2->execute();

    $titileupdate3= $db->prepare("UPDATE `PageTitle` SET TitleName=:title_name3 WHERE id='32'"); 
    $titileupdate3->bindParam(':title_name3', $title_name3, PDO::PARAM_STR);
    $titileupdates3 = $titileupdate3->execute();


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
   
<body class="skin-default fixed-layout mysunlessA4">
     <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php echo $_SESSION['UserName']; ?></p>
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
                        <h4 class="text-themecolor"> Api Setting</h4> 
                           
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
                                <!-- Nav tabs -->
                                 <ul class="nav nav-tabs customtab" role="tablist">
<li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="fa fa-at"></i></span> <span class="hidden-xs-down">Gmail</span></a> </li>
<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i class="fas fa-square"></i></span> <span class="hidden-xs-down">Payment</span></a> </li>
<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home4" role="tab"><span class="hidden-sm-up"><i class="fa fa-cloud-download-alt"></i></span> <span class="hidden-xs-down"> Data Storage </span></a> </li>
<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#home5" role="tab"><span class="hidden-sm-up"><i class="fas fa-heading"></i></span> <span class="hidden-xs-down"> Page Setting</span></a> </li>

                            </ul>
                                <!-- Tab wo proingt  panes -->
                                  <div class="tab-content">
                                <div class="tab-pane active" id="home2" role="tabpanel">
                                    <div class="p-20">
                                            
                                 <form class="form-horizontal" id="Cbuttonnemae" method="post">
                                            
                                            <div class="form-group">
                                            <label>Login with Google Button *</label>
                                            <input type="text"  id="Buttonone" name="Buttonone" value="<?php echo $B1; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Logout Button *</label>
                                            <input type="text"  id="Buttonone2" name="Buttonone2" value="<?php echo $B2; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            

                                            



            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton" id="add-customerbutton"><i class="fa fa-check"></i> Save </button>
            </div>

                                        </form>    

                                    </div>
                                </div>

                                <div class="tab-pane " id="home3" role="tabpanel">
                                    <div class="p-20">

                                        <form class="form-horizontal" id="Cbuttonnemae2" method="post">

                                            <div class="form-group">
                                            <label>Square Enable Button *</label>
                                            <input type="text"  id="Buttonone3" name="Buttonone3" value="<?php echo $B3; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                             <div class="form-group">
                                            <label>Disable Square Button *</label>
                                            <input type="text"  id="Buttonone4" name="Buttonone4" value="<?php echo $B4; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <!-- <div class="form-group">
                                            <label>Disable Twillo Button *</label>
                                            <input type="text"  id="Buttonone5" name="Buttonone5" value="<?php echo $B5; ?>" class="form-control" autocomplete="nope"></input>
                                            </div> -->

                                            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton2" id="add-customerbutton2"><i class="fa fa-check"></i> Save </button>
            </div>

                                        </form>
                                    </div>
                                </div>


                                              <div class="tab-pane " id="home4" role="tabpanel">
                                    <div class="p-20">

                                        <form class="form-horizontal" id="Cbuttonnemae3" method="post">

                                          
                                            <div class="form-group">
                                            <label>Import from google Button *</label>
                                            <input type="text"  id="Buttonone5" name="Buttonone5" value="<?php echo $B5; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Import from yahoo Button *</label>
                                            <input type="text"  id="Buttonone6" name="Buttonone6" value="<?php echo $B6; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Import from Outlook Button *</label>
                                            <input type="text"  id="Buttonone7" name="Buttonone7" value="<?php echo $B7; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Import useing Excel Button *</label>
                                            <input type="text"  id="Buttonone8" name="Buttonone8" value="<?php echo $B8; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton2" id="add-customerbutton2"><i class="fa fa-check"></i> Save </button>
            </div>

                                        </form>
                                    </div>
                                </div>


                                        <div class="tab-pane " id="home5" role="tabpanel">
                                    <div class="p-20">


                                             <form class="form-horizontal" id="Cbuttonnemae4" method="post">

                                            <div class="form-group">
                                            <label>Add New Email Default Setting Title *</label>
                                            <input type="text"  id="Titleone" name="Titleone" value="<?php echo $T1; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Import Contacts Title *</label>
                                            <input type="text"  id="Titleone2" name="Titleone2" value="<?php echo $Ti2; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                            <div class="form-group">
                                            <label>Add New Payment Default Setting  Title *</label>
                                            <input type="text"  id="Titleone3" name="Titleone3" value="<?php echo $Ti3; ?>" class="form-control" autocomplete="nope"></input>
                                            </div>

                                   




                                            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-customerbutton2" id="add-customerbutton2"><i class="fa fa-check"></i> Save </button>
            </div>

                                        </form>


                                       </div>
                                       </div>


                <div class="Loader"></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
 </div>
            <!-- ==============================================================   -->
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
                setInterval(function(){$(".setapi").addClass("active");}, 10);
                $("#Cbuttonnemae").validate({
            rules: {                
                Buttonone: "required",
                Buttonone2: "required",
                
                
                
            },
            messages: {             
                Buttonone:  "Please enter login with google button name",
                Buttonone2:  "Please enter logout button name",
                
                
                
                
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
                Buttonone3: "required",
                Buttonone4: "required",
                
                
            },
            messages: {             
                Buttonone3:  "Please enter square enable button name",
                Buttonone4:  "Please enter disable square button name",
                
                
                
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
                    url:'?Buttonone4',
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


          $("#Cbuttonnemae3").validate({
            rules: {                
                Buttonone5: "required",
                Buttonone6: "required",
                Buttonone7: "required",
                Buttonone8: "required",
                
                
            },
            messages: {             
                Buttonone5:  "Please enter import from google button name",
                Buttonone6:  "Please enter import from yahoo button name",
                Buttonone7:  "Please enter import from outlook button name",
                Buttonone8:  "Please enter import useing excel button name",
                
                
                
            },
             submitHandler: function() {
                
          $(".Loader").show();
              var form = $('#Cbuttonnemae3')[0];
               var data = new FormData(form);
              // var data = $("#Cbuttonnemae").serialize();
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'?Buttonone5',
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

                       $("#Cbuttonnemae4").validate({
            rules: {                
                Titleone: "required",
                Titleone2: "required",
                Titleone3: "required",
                
                       
            },
            messages: {             
                Titleone:  "Please enter add new email default setting title",
                Titleone2:  "Please enter import contacts title",
                Titleone3:  "Please enter add new payment default setting title",
                
                
                
                
            },
             submitHandler: function() {
          $(".Loader").show();
              var form = $('#Cbuttonnemae4')[0];
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