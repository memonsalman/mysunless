<?php 


require_once('global.php');


require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");
/*echo  $encodeed =  EncodeId(15);
echo "<br>".DecodeId($encodeed)."<br>";die;*/
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C107'"); 
$button1->execute();
$all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
$B1=$all_button1['button_name'];

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='34'"); 
$title1->execute();
$all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
$T1=$all_title1['TitleName'];



if(isset($_GET["userid"]))
{
  $userid = base64_decode($_GET["userid"]);

  $getrecord=$db->prepare("SELECT username from `users` where id=:userid");
  $getrecord->bindparam(':userid',$userid);
  $getrecord->execute();

  $data=$getrecord->fetch();
  $username=$data['username'];
 

}


$query = $db->prepare("SELECT timetable FROM `users` WHERE id=:userid");
$query->bindValue(':userid', $userid, PDO::PARAM_INT);
$query->execute();  
$info = $query->fetch(PDO::FETCH_ASSOC);  

if(!empty($info))
{
  $date = json_decode($info['timetable'], true);  
}
else
{
 $date = array( 
  "0" => array (
   "Monday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "1" => array (
   "Tuesday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "2" => array (
   "Wednesday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "3" => array (
   "Thursday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "4" => array (
   "Friday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "5" => array (
   "Saturday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

  "6" => array (
   "Sunday" => 0,
   "starttime" => "", 
   "endtime" => ""
 ),

);    
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style>

  .switch_status
  {
     margin-left: 16px;
  }

  .img-circle {
    object-fit: cover;
  }
  #map {
    height: 100%;
  }
  /* Optional: Makes the sample page fill the window. */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
  .lb-details{display: none!important;}
  .pac-container.pac-logo{z-index: 99999;}
  .lb-container{    position: absolute;    right: 0;    left: 0; }
  .lb-outerContainer{width: 50%!important;}
  img.lb-image{margin: 0 auto!important;}
  .lb-dataContainer{width: 75%!important; margin: unset!important;}
  .lightbox{top: 150px!important;}

  @media only screen and (max-width: 768px) 
  {
    .lightbox{top: 500px!important}
    .lb-outerContainer{width: 100%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
  }
  .employeedelete,.employeeaccess,.employeesname2,.employeesimage,.employeedetails{float: left;}
  .employeesname{float: right;}
  .toggle.android { border-radius: 0px;}
  .toggle.android .toggle-handle { border-radius: 0px; }
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
  .toggle.btn.ios.btn-default.off{border: 1px solid;}
  .toggle.btn.ios.btn-primary{border: 1px solid;}
  #myModal label.btn.btn-primary.toggle-on{background: green !important; border: 1px solid green !important;}

  .timepicker_wrap {
    top: 50px!important;
  }
  @media (max-width: 525px){

    .timepicker_wrap {
      left: -110px!important;
    }

    .timepicker_wrap .arrow_top{
      left: 120px!important;
    }


  }
  


</style>
<link rel="stylesheet" href="<?php echo base_url ?>/dist/css/lightbox.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<body class="skin-default fixed-layout mysunlessA11">
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
      <!-- ==============================================================  -->
      <div class="row page-titles">
        <div class="col-md-12 align-self-center">
          <h4 class="text-themecolor">Working time table for <span class="badge badge-info"><?php echo strtoupper($username); ?></span></h4>
         

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
              <label style="margin-left: 10px; margin-bottom: 0px;">Set Default Time</label>
              <div class="form-inline">

              <input type="text" placeholder="Start Time" class= "start form-control" name="mondayst" autocomplete="new-password" id="allst" style="margin: 0px 10px 10px 10px; border: solid 1px #999;" value="" /> 
              <input type="text" placeholder="End Time" class= "start form-control" name="mondayst" autocomplete="new-password" id="allet" style="margin: 0px 10px 10px 10px; border: solid 1px #999;" value="" /> 
              <button class="btn btn-info form-control" id="applyAll" style="margin:0px 10px 10px 10px; !important">Apply to all</button>
              <span class="allerror" style="color: red;"></span>
              
              </div>
              <!-- Tab panes -->

              <table class="table">
               
                <tbody>
                 
                </tbody>
            </table>



              <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="home" role="tabpanel">
                 <div class="col-lg-12">
                  <form class="form-horizontal" autocomplete="new-password" id="EmployeeHour" method="post">
                    <input type="hidden"  name="userid" autocomplete="nope" id="userid" value="<?php echo $userid; ?>">
                    <table class="table" style="text-align: center;">
                       <thead class="thead-light">
                          <tr>      
                            <th scope="col">Day</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                          </tr>
                        </thead>

                      <tr>
                        <td>
                          <table>
                            <tr>
                              
                              <td style="width: 60%;">Monday : </td>
                              <td style="width: 40%;">
                                <?php
                                if($date[0]['Monday']==1)
                                {
                                  ?>                               
                                  <label class="switch switch_status"> 
                                        <input id="mondaycheck" name="Monday" class="allbutton" value="1" checked type="checkbox">
                                        <span class="slider round_switch"></span> 
                                  </label>
                                  <?php
                                }
                                else
                                {
                                  ?>
                                  <!-- <input type="checkbox" data-id="" value="1" name="Monday" class="toggle_statusm" id="toggle_statusm" data-toggle="toggle" data-style="ios"> --> 
                                  <label class="switch switch_status"> 
                                        <input id="mondaycheck" name="Monday" class="allbutton" value="1" type="checkbox"  >
                                        <span class="slider round_switch"></span> 
                                  </label>
                                  <?php
                                } 
                                ?>
                              </td>
                            </tr>

                          </table>
                        </td> 
                        <td>
                         <p id="datepairExample">
                          <?php
                          if(!empty($date[0]['starttime']))
                            { ?>
                              <input type="text" placeholder="Start Time" class= "start form-control" name="mondayst" autocomplete="new-password" id="mondayst" value="<?php echo $date[0]['starttime']; ?>" /> 
                              <?php
                            }
                            else
                              {?>
                                <input type="text" placeholder="Start Time" class= "start form-control" disabled="disabled" name="mondayst" autocomplete="new-password" id="mondayst" /> 
                                <?php
                              }
                              ?>

                            </p>
                          </td>
                          <td>
                           <p id="datepairExample">
                             <?php
                             if(!empty($date[0]['endtime']))
                              { ?>
                                <input type="text" placeholder="End Time" class= "start form-control"  name="mondayet" id="mondayet" value="<?php echo $date[0]['endtime']; ?>" /> 
                                <span class="mondayeterror" style="color: red;"></span>
                                <?php
                              }
                              else
                                {?>
                                  <input type="text" placeholder="End Time" class= "start form-control" disabled="disabled" name="mondayet" autocomplete="new-password" id="mondayet"  /> 
                                  <span class="mondayeterror" style="color: red;"></span>
                                  <?php
                                }
                                ?>
                              </p>
                            </td>
                          </tr>


                          <tr>
                            <td>

                              <table>
                                <tr>
                                  <td style="width: 60%;">Tuesday : </td>
                                  <td style="width: 40%;">
                                    <?php
                                    if($date[1]['Tuesday']==1)
                                    {
                                      ?>
                                     <!--  <input type="checkbox" checked data-id="" value="1" name="Tuesday" class="toggle_statust" data-toggle="toggle" data-style="ios"> -->
                                      <label class="switch switch_status"> 
                                        <input id="tuesdaycheck" name="Tuesday" class="allbutton" checked value="1" type="checkbox">
                                        <span class="slider round_switch"></span> 
                                     </label>
                                      <?php
                                    }
                                    else
                                    {
                                      ?>
                                      <!-- <input type="checkbox" data-id="" value="1" name="Tuesday" class="toggle_statust" data-toggle="toggle" data-style="ios"> -->
                                      <label class="switch switch_status"> 
                                        <input id="tuesdaycheck" name="Tuesday" class="allbutton" value="1" type="checkbox">
                                        <span class="slider round_switch"></span> 
                                     </label>
                                      <?php
                                    } 
                                    ?>
                                  </td>
                                </tr>

                              </table>
                            </td> 
                            <td>
                             <p id="datepairExample">
                              <?php
                              if(!empty($date[1]['starttime']))
                                { ?>
                                  <input type="text" placeholder="Start Time" class= "start form-control" name="tuesdayst"  autocomplete="nope" id="tuesdayst" value="<?php echo $date[1]['starttime']; ?>" /> 

                                  <?php
                                }
                                else
                                  {?>
                                    <input type="text" placeholder="Start Time" class= "start form-control" name="tuesdayst" autocomplete="nope" disabled="disabled" id="tuesdayst" /> 
                                    <?php
                                  }
                                  ?>

                                </p>
                              </td>
                              <td>
                               <p id="datepairExample">
                                 <?php
                                 if(!empty($date[1]['endtime']))
                                  { ?>
                                    <input type="text" placeholder="End Time" class= "start form-control" name="tuesdayet"  autocomplete="nope" id="tuesdayet" value="<?php echo $date[1]['endtime']; ?>" /> 
                                    <span class="tuesdayeterror" style="color: red;"></span>
                                    <?php
                                  }
                                  else
                                    {?>
                                      <input type="text" placeholder="End Time" class= "start form-control" name="tuesdayet" disabled="disabled" autocomplete="nope" id="tuesdayet"  />
                                      <span class="tuesdayeterror" style="color: red;"></span>
                                      <?php
                                    }
                                    ?>
                                  </p>
                                </td>
                              </tr>

                              <tr>
                                <td>

                                  <table>
                                    <tr>
                                      <td style="width: 60%;">Wednesday:</td>
                                      <td style="width: 40%;">
                                        <?php
                                        if($date[2]['Wednesday']==1)
                                        {
                                          ?>
                                         <!--  <input type="checkbox" checked data-id="" value="1" name="Wednesday" class="toggle_statusw" data-toggle="toggle" data-style="ios"> -->
                                           <label class="switch switch_status"> 
                                          <input id="wednesdaycheck" class="allbutton" checked name="Wednesday" value="1" type="checkbox">
                                            <span class="slider round_switch"></span> 
                                           </label>
                                          <?php
                                        }
                                        else
                                        {
                                          ?>
                                         <!--  <input type="checkbox" data-id="" value="1" name="Wednesday" class="toggle_statusw" data-toggle="toggle" data-style="ios"> -->
                                          <label class="switch switch_status"> 
                                          <input id="wednesdaycheck" name="Wednesday" class="allbutton" value="1" type="checkbox">
                                            <span class="slider round_switch"></span> 
                                           </label>
                                          <?php
                                        } 
                                        ?>
                                      </td>
                                    </tr>

                                  </table>
                                </td> 
                                <td>
                                 <p id="datepairExample">
                                  <?php
                                  if(!empty($date[2]['starttime']))
                                    { ?>
                                      <input type="text" placeholder="Start Time" class= "start form-control" name="wednesdayst"  autocomplete="nope" id="wednesdayst" value="<?php echo $date[2]['starttime']; ?>" /> 
                                      <?php
                                    }
                                    else
                                      {?>
                                        <input type="text" placeholder="Start Time" class= "start form-control" name="wednesdayst" disabled="disabled" autocomplete="nope" id="wednesdayst" /> 
                                        <?php
                                      }
                                      ?>
                                    </p>
                                  </td>
                                  <td>
                                   <p id="datepairExample">
                                     <?php
                                     if(!empty($date[2]['endtime']))
                                      { ?>
                                        <input type="text" placeholder="End Time" class= "start form-control" name="wednesdayet"  autocomplete="nope" id="wednesdayet" value="<?php echo $date[2]['endtime']; ?>" /> 
                                        <span class="wednesdayeterror" style="color: red;"></span>
                                        <?php
                                      }
                                      else
                                        {?>
                                          <input type="text" placeholder="End Time" class= "start form-control" name="wednesdayet" disabled="disabled" autocomplete="nope" id="wednesdayet"  /> 
                                          <span class="wednesdayeterror" style="color: red;"></span>
                                          <?php
                                        }
                                        ?>
                                      </p>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td>
                                      <table>
                                        <tr>
                                          <td style="width: 60%;">Thursday : </td>
                                          <td style="width: 40%;">
                                            <?php
                                            if($date[3]['Thursday']==1)
                                            {
                                              ?>
                                             <!--  <input type="checkbox" checked data-id="" value="1" name="Thursday" class="toggle_statusth" data-toggle="toggle" data-style="ios"> -->
                                              <label class="switch switch_status"> 
                                          <input id="thursdaycheck" name="Thursday" class="allbutton" checked="" value="1" type="checkbox">
                                            <span class="slider round_switch"></span> 
                                           </label>
                                              <?php
                                            }
                                            else
                                            {
                                              ?>
                                              <!-- <input type="checkbox" data-id="" value="1" name="Thursday" class="toggle_statusth" data-toggle="toggle" data-style="ios"> -->
                                           <label class="switch switch_status"> 
                                          <input id="thursdaycheck" name="Thursday" class="allbutton" value="1" type="checkbox">
                                            <span class="slider round_switch"></span> 
                                           </label>
                                              <?php
                                            } 
                                            ?>
                                          </td>
                                        </tr>

                                      </table>
                                    </td> 
                                    <td>
                                     <p id="datepairExample">
                                      <?php
                                      if(!empty($date[3]['starttime']))
                                        { ?>
                                          <input type="text" placeholder="Start Time" class= "start form-control" name="thursdayst" autocomplete="nope" id="thursdayst" value="<?php echo $date[3]['starttime']; ?>" /> 
                                          <?php
                                        }
                                        else
                                          {?>
                                            <input type="text" placeholder="Start Time" class= "start form-control" name="thursdayst" disabled="disabled" autocomplete="nope" id="thursdayst" /> 
                                            <?php
                                          }
                                          ?>

                                        </p>
                                      </td>
                                      <td>
                                       <p id="datepairExample">
                                         <?php
                                         if(!empty($date[3]['endtime']))
                                          { ?>
                                            <input type="text" placeholder="End Time" class= "start form-control"  name="thursdayet" autocomplete="nope" id="thursdayet" value="<?php echo $date[3]['endtime']; ?>" /> 
                                            <span class="thursdayeterror" style="color: red;"></span>
                                            <?php
                                          }
                                          else
                                            {?>
                                              <input type="text" placeholder="End Time" class= "start form-control" disabled="disabled" name="thursdayet" autocomplete="nope" id="thursdayet"  /> 
                                              <span class="thursdayeterror" style="color: red;"></span>
                                              <?php
                                            }
                                            ?>
                                          </p>
                                        </td>
                                      </tr>


                                      <tr>
                                        <td>

                                          <table>
                                            <tr>
                                              <td style="width: 60%;">Friday : </td>
                                              <td style="width: 40%;">
                                                <?php
                                                if($date[4]['Friday']==1)
                                                {
                                                  ?>
                                                 <!--  <input type="checkbox" checked data-id="" value="1" name="Friday" class="toggle_statusf" data-toggle="toggle" data-style="ios"> -->
                                               <label class="switch switch_status"> 
                                                <input id="fridaycheck" name="Friday" class="allbutton" value="1" checked="" type="checkbox">
                                                  <span class="slider round_switch"></span> 
                                                </label>

                                                  <?php
                                                }
                                                else
                                                {
                                                  ?>
                                                <!--   <input type="checkbox" data-id="" value="1" name="Friday" class="toggle_statusf" data-toggle="toggle" data-style="ios"> -->
                                            <label class="switch switch_status"> 
                                                <input id="fridaycheck" name="Friday" class="allbutton" value="1" type="checkbox">
                                                  <span class="slider round_switch"></span> 
                                           </label>
                                                  <?php
                                                } 
                                                ?>
                                              </td>
                                            </tr>

                                          </table>
                                        </td> 
                                        <td>
                                         <p id="datepairExample">
                                          <?php
                                          if(!empty($date[4]['starttime']))
                                            { ?>
                                              <input type="text" placeholder="Start Time" class= "start form-control" name="fridayst"  autocomplete="nope" id="fridayst" value="<?php echo $date[4]['starttime']; ?>" /> 
                                              <?php
                                            }
                                            else
                                              {?>
                                                <input type="text" placeholder="Start Time" class= "start form-control" name="fridayst" disabled="disabled" autocomplete="nope" id="fridayst" /> 
                                                <?php
                                              }
                                              ?>

                                            </p>
                                          </td>
                                          <td>
                                           <p id="datepairExample">
                                             <?php
                                             if(!empty($date[4]['endtime']))
                                              { ?>
                                                <input type="text" placeholder="End Time" class= "start form-control" name="fridayet"  autocomplete="nope" id="fridayet" value="<?php echo $date[4]['endtime']; ?>" /> 
                                                <span class="fridayeterror" style="color: red;"></span>
                                                <?php
                                              }
                                              else
                                                {?>
                                                  <input type="text" placeholder="End Time" class= "start form-control" name="fridayet" disabled="disabled" autocomplete="nope" id="fridayet"  /> 
                                                  <span class="fridayeterror" style="color: red;"></span>
                                                  <?php
                                                }
                                                ?>
                                              </p>
                                            </td>
                                          </tr>



                                          <tr>
                                            <td>

                                              <table>
                                                <tr>
                                                  <td style="width: 60%;">Saturday : </td>
                                                  <td style="width: 40%;">
                                                    <?php
                                                    if($date[5]['Saturday']==1)
                                                    {
                                                      ?>
                                                    <!--   <input type="checkbox" checked data-id="" value="1" name="Saturday" class="toggle_statuss" data-toggle="toggle" data-style="ios"> -->
                                                      <label class="switch switch_status"> 
                                                     <input id="saturdaycheck" name="Saturday" class="allbutton" checked="" value="1" type="checkbox">
                                                       <span class="slider round_switch"></span> 
                                                      </label>
                                                      <?php
                                                    }
                                                    else
                                                    {
                                                      ?>
                                                    <!--   <input type="checkbox" data-id="" value="1" name="Saturday" class="toggle_statuss" data-toggle="toggle" data-style="ios"> -->
                                                     <label class="switch switch_status"> 
                                                     <input id="saturdaycheck" name="Saturday" class="allbutton" value="1" type="checkbox">
                                                       <span class="slider round_switch"></span> 
                                                      </label>
                                                      <?php
                                                    } 
                                                    ?>
                                                  </td>
                                                </tr>

                                              </table>
                                            </td> 
                                            <td>
                                             <p id="datepairExample">
                                              <?php
                                              if(!empty($date[5]['starttime']))
                                                { ?>
                                                  <input type="text" placeholder="Start Time" class= "start form-control" name="saturdayst"  autocomplete="nope" id="saturdayst" value="<?php echo $date[5]['starttime']; ?>" /> 
                                                  <?php
                                                }
                                                else
                                                  {?>
                                                    <input type="text" placeholder="Start Time" class= "start form-control" name="saturdayst" disabled="disabled" autocomplete="nope" id="saturdayst" /> 
                                                    <?php
                                                  }
                                                  ?>

                                                </p>
                                              </td>
                                              <td>
                                               <p id="datepairExample">
                                                 <?php
                                                 if(!empty($date[5]['endtime']))
                                                  { ?>
                                                    <input type="text" placeholder="End Time" class= "start form-control" name="saturdayet"  autocomplete="nope" id="saturdayet" value="<?php echo $date[5]['endtime']; ?>" /> 
                                                    <span class="saturdayeterror" style="color: red;"></span>
                                                    <?php
                                                  }
                                                  else
                                                    {?>
                                                      <input type="text" placeholder="End Time" class= "start form-control" name="saturdayet" disabled="disabled" autocomplete="nope" id="saturdayet"  /> 
                                                      <span class="saturdayeterror" style="color: red;"></span>
                                                      <?php
                                                    }
                                                    ?>
                                                  </p>
                                                </td>
                                              </tr>


                                              <tr>
                                                <td>

                                                  <table>
                                                    <tr>
                                                      <td style="width: 60%;">Sunday : </td>
                                                      <td style="width: 40%;">
                                                        <?php
                                                        if($date[6]['Sunday']==1)
                                                        {
                                                          ?>
                                                          <!-- <input type="checkbox" checked data-id="" value="1" name="Sunday" class="toggle_statussu" data-toggle="toggle" data-style="ios"> -->
                                                          <label class="switch switch_status"> 
                                                     <input id="sundaycheck" name="Sunday" class="allbutton" value="1" checked="" type="checkbox">
                                                       <span class="slider round_switch"></span> 
                                                      </label>
                                                          <?php
                                                        }
                                                        else
                                                        {
                                                          ?>
                                                          <label class="switch switch_status"> 
                                                     <input id="sundaycheck" class="allbutton" name="Sunday" value="1" type="checkbox">
                                                       <span class="slider round_switch"></span> 
                                                      </label>
                                                          <?php
                                                        } 
                                                        ?>
                                                      </td>
                                                    </tr>

                                                  </table>
                                                </td> 
                                                <td>
                                                 <p id="datepairExample">
                                                  <?php
                                                  if(!empty($date[6]['starttime']))
                                                    { ?>
                                                      <input type="text" placeholder="Start Time" class= "start form-control"  name="sundayst" autocomplete="nope" id="sundayst" value="<?php echo $date[6]['starttime']; ?>" /> 
                                                      <?php
                                                    }
                                                    else
                                                      {?>
                                                        <input type="text" placeholder="Start Time" class= "start form-control" disabled="disabled" name="sundayst" autocomplete="nope" id="sundayst" /> 
                                                        <?php
                                                      }
                                                      ?>

                                                    </p>
                                                  </td>
                                                  <td>
                                                   <p id="datepairExample">
                                                     <?php
                                                     if(!empty($date[6]['endtime']))
                                                      { ?>
                                                        <input type="text" placeholder="End Time" class= "start form-control"  name="sundayet" autocomplete="nope" id="sundayet" value="<?php echo $date[6]['endtime']; ?>" /> 
                                                        <span class="sundayeterror" style="color: red;"></span>
                                                        <?php
                                                      }
                                                      else
                                                        {?>
                                                          <input type="text" placeholder="End Time" class= "start form-control" disabled="disabled" name="sundayet" autocomplete="nope" id="sundayet"  /> 
                                                          <span class="sundayeterror" style="color: red;"></span>
                                                          <?php
                                                        }
                                                        ?>
                                                      </p>
                                                    </td>
                                                  </tr>
                                                </table>
                                                <div class="form-group" style="padding: 15px 0; text-align: right;">
                                                  <p style="text-align: left; color: red; " id="terror"  ></p>
                                                  <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" name="add-client" id="add-client"><i class="fa fa-check"></i> Save</button>

                                                  <a href="<?php echo  base_url ?>/AllEmployees">
                                                    <button type="button" class="btn waves-effect waves-light btn-danger m-r-10"  id="backTo"> Back </button></a>
                                                </div>
                                                <!-- <a href="<?php echo base_url;?>/AllEmployees" class="btn waves-effect waves-light btn-info m-r-10" name="add-client" id="add-client"><i class="fa fa-check"></i> Cancel</button> -->
                                                </form>

                                                <div class="col-lg-12 col-md-12">

                                                 <div class="alert alert-success" id="resonse" style="display: none;">
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                  <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
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
                        <script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
                        <script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
                        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
                        
                        <script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>

                        <script>
                          function myFunction() {
                            var x = document.getElementById("primaryaddress").value;
                            document.getElementById("secondaryaddress").innerHTML = x;
                          }
$(document).ready(function() {

    //timepicki
    $('.start').timepicki();
    $(".timepicker_wrap").hide();

    $("input").attr("autocomplete","new-password");


    jQuery.validator.addMethod("time_valid", function (value, element, param) { 

      start = moment($(param).val(), 'hh:mma').diff(moment().startOf('day'), 'seconds');
      end = moment(value, 'hh:mma').diff(moment().startOf('day'), 'seconds');

      if(end>start){
        return true;
      }else{
        return false  
      }

    }, "End time should be more than Start time");

    
    $(document).on('click','#applyAll',function(e){
      e.preventDefault();
      $('.allerror').text(" ");
      var starttime=$('#allst').val();
      var endtime=$('#allet').val();
      var cond;
      start1 = moment(starttime, 'hh:mma').diff(moment().startOf('day'), 'seconds');
      end1 = moment(endtime, 'hh:mma').diff(moment().startOf('day'), 'seconds');

      if(end1>start1){
        cond=1;
      }else{
        $('.allerror').text("End time should be more than Start time");
      }

      if (starttime!='' && endtime!='' && cond==1)
      {

        $('.allbutton').attr('checked',true);  
        $('.start').attr('disabled',false);         
        $('#mondayst').val(starttime);
        $('#tuesdayst').val(starttime);
        $('#wednesdayst').val(starttime);
        $('#thursdayst').val(starttime);
        $('#fridayst').val(starttime);
        $('#saturdayst').val(starttime);
        $('#sundayst').val(starttime);

        $('#mondayet').val(endtime);
        $('#tuesdayet').val(endtime);
        $('#wednesdayet').val(endtime);
        $('#thursdayet').val(endtime);
        $('#fridayet').val(endtime);
        $('#saturdayet').val(endtime);
        $('#sundayet').val(endtime);
      } 
      else if (starttime=='') {
        $('.allerror').text("Select Start time");
      }
      else if (endtime=='') {
        $('.allerror').text("Select End time");
      }
     })

    $("#EmployeeHour").validate({

      rules: {                
        mondayst:"required",
        mondayet:{required: true,
          time_valid: "#mondayst"
        },
        tuesdayst:"required",
        tuesdayet:{required: true,
          time_valid: "#tuesdayst"
        },
        wednesdayst:"required",
        wednesdayet:{required: true,
          time_valid: "#wednesdayst"
        },
        thursdayst:"required",
        thursdayet:{required: true,
          time_valid: "#thursdayst"
        },
        fridayst:"required",
        fridayet:{required: true,
          time_valid: "#fridayst"
        },
        saturdayst:"required",
        saturdayet:{required: true,
          time_valid: "#saturdayst"
        },
        sundayst:"required",
        sundayet:{required: true,
          time_valid: "#sundayst"
        }
      },
      messages: {             

      },

      submitHandler: function() {

        var er = "Please select From/To time for";
        if($(".toggle_statusm").prop("checked"))
        { 
          if($("#mondayet").val() == "" || $("#mondayst").val() == "")
          {
            $("#terror").html(er +" "+ "Monday.");
            return false;
          }
        }
        if($(".toggle_statust").prop("checked"))
        {
          if($("#tuesdayst").val() == "" || $("#tuesdayet").val() == "")
          {
            $("#terror").html(er +" "+ "Tuesday.");
            return false;
          }
        }
        if($(".toggle_statusw").prop("checked"))
        {
          if($("#wednesdayst").val() == "" || $("#wednesdayet").val() == "")
          {
            $("#terror").html(er +" "+ "Wednesday.");
            return false;
          }
        }
        if($(".toggle_statusth").prop("checked"))
        {
          if($("#thursdayst").val() == "" || $("#thursdayet").val() == "")
          {
            $("#terror").html(er +" "+ "Thursday.");
            return false;
          }
        }
        if($(".toggle_statusf").prop("checked"))
        {
          if($("#fridayst").val() == "" || $("#fridayet").val() == "")
          {
            $("#terror").html(er +" "+ "Friday.");
            return false;
          }
        }
        if($(".toggle_statuss").prop("checked"))
        {
          if($("#saturdayst").val() == "" || $("#saturdayet").val() == "")
          {
            $("#terror").html(er +" "+ "Saturday.");
            return false;
          }
        }
        if($(".toggle_statussu").prop("checked"))
        {
          if($("#sundayst").val() == "" || $("#sundayet").val() == "")
          {
            $("#terror").html(er +" "+ "Sunday.");
            return false;
          }
        }
        $("#terror").html("");
        $(".Loader").show(); 
        var data = $("#EmployeeHour").serialize();
        data= data + "&LoginAction=Login";
        jQuery.ajax({  
          dataType:"json",
          type:"POST",
          data:data,
          url:'<?php echo EXEC ?>Exec_Edit_Employee.php',
          success: function(data)
          { 
            if(data.resonse)
            {
              $("#resonse").show();
              $('#resonsemsg').html('<span>'+data.resonse+'</span>');
              $( '#EmployeeForm' ).each(function(){
                this.reset();
              });
              $(".Loader").hide();
              setTimeout(function () { location.reload();  }, 2000);    
            }
            else if(data.error)
            {

            }
          }
        });
      }           
    });



    // $(".toggle_statusm").change(function (){
    //   // alert(this.prop);
      
    //  console.log($(this).prop("checked"));
    //  if($(this).prop("checked")) 
    //  {
    //    $("#mondayst").removeAttr("disabled");
    //    $("#mondayet").removeAttr("disabled");        
    //  }
    //  else
    //  {
    //    $("#mondayst").attr("disabled", "disabled");
    //    $("#mondayet").attr("disabled", "disabled");
    //  }
    //   console.log($(this).prop("checked"));
    // });

    $("#mondaycheck").change(function(){

      // console.log($(this).prop('checked'));
      if($(this).prop('checked'))
      {
        $("#mondayst").removeAttr("disabled");
        $("#mondayet").removeAttr("disabled"); 
        
      }
      else
      {
        $("#mondayst").attr("disabled", true);
        $("#mondayet").attr("disabled", true);
      }


    });

    $("#tuesdaycheck").change(function(){
      if($(this).prop("checked")) 
      {
        $("#tuesdayst").removeAttr("disabled");
        $("#tuesdayet").removeAttr("disabled");
      }
      else
      {
        $("#tuesdayst").attr("disabled", "disabled");
        $("#tuesdayet").attr("disabled", "disabled");
      }
    });

    $("#wednesdaycheck").change(function (){
      if($(this).prop("checked")) 
      {
        $("#wednesdayst").removeAttr("disabled");
        $("#wednesdayet").removeAttr("disabled");
      }
      else
      {
        $("#wednesdayst").attr("disabled", "disabled");
        $("#wednesdayet").attr("disabled", "disabled");
      }
    });

    $("#thursdaycheck").change(function (){
      if($(this).prop("checked")) 
      {
        $("#thursdayst").removeAttr("disabled");
        $("#thursdayet").removeAttr("disabled");
      }
      else
      {
        $("#thursdayst").attr("disabled", "disabled");
        $("#thursdayet").attr("disabled", "disabled");
      }
    });

    $("#fridaycheck").change(function (){
      if($(this).prop("checked")) 
      {
        $("#fridayst").removeAttr("disabled");
        $("#fridayet").removeAttr("disabled");
      }
      else
      {
        $("#fridayst").attr("disabled", "disabled");
        $("#fridayet").attr("disabled", "disabled");
      }
    });

    $("#saturdaycheck").change(function (){
      if($(this).prop("checked")) 
      {
        $("#saturdayst").removeAttr("disabled");
        $("#saturdayet").removeAttr("disabled");
      }
      else
      {
        $("#saturdayst").attr("disabled", "disabled");
        $("#saturdayet").attr("disabled", "disabled");
      }
    });

    $("#sundaycheck").change(function (){
      if($(this).prop("checked")) 
      {
        $("#sundayst").removeAttr("disabled");
        $("#sundayet").removeAttr("disabled");
      }
      else
      {
        $("#sundayst").attr("disabled", "disabled");
        $("#sundayet").attr("disabled", "disabled");
      }
    });

    $("#mondayet").change(function () 
    {


      $('.mondayeterror').text('');
      var mondayet = $(this).val();
      var mondayst =  $('#mondayst').val()
      var stt = new Date("November 13, 2013 " + mondayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + mondayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.mondayeterror').text('end time always greater then start time');
        $('#mondayet').val('').change();
      }
    });

    $("#tuesdayet").change(function () 
    {
      $('.tuesdayeterror').text('');
      var tuesdayet = $(this).val();
      var tuesdayst =  $('#tuesdayst').val()
      var stt = new Date("November 13, 2013 " + tuesdayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + tuesdayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.tuesdayeterror').text('end time always greater then start time');
        $('#tuesdayet').val('').change();
      }
    });

    $("#wednesdayet").change(function () 
    {
      $('.wednesdayeterror').text('');
      var wednesdayet = $(this).val();
      var wednesdayst =  $('#wednesdayst').val()
      var stt = new Date("November 13, 2013 " + wednesdayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + wednesdayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.wednesdayeterror').text('end time always greater then start time');
        $('#wednesdayet').val('').change();
      }
    });

    $("#thursdayet").change(function () 
    {
      $('.thursdayeterror').text('');
      var thursdayet = $(this).val();
      var thursdayst =  $('#thursdayst').val()
      var stt = new Date("November 13, 2013 " + thursdayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + thursdayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.thursdayeterror').text('end time always greater then start time');
        $('#thursdayet').val('').change();
      }
    });

    $("#fridayet").change(function () 
    {
      $('.fridayeterror').text('');
      var fridayet = $(this).val();
      var fridayst =  $('#fridayst').val()
      var stt = new Date("November 13, 2013 " + fridayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + fridayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.fridayeterror').text('end time always greater then start time');
        $('#fridayet').val('').change();
      }
    });

    $("#saturdayet").change(function () 
    {
      $('.saturdayeterror').text('');
      var saturdayet = $(this).val();
      var saturdayst =  $('#saturdayst').val()
      var stt = new Date("November 13, 2013 " + saturdayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + saturdayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.saturdayeterror').text('end time always greater then start time');
        $('#saturdayet').val('').change();
      }
    });

    $("#sundayet").change(function () 
    {
      $('.sundayeterror').text('');
      var sundayet = $(this).val();
      var sundayst =  $('#sundayst').val()
      var stt = new Date("November 13, 2013 " + sundayst);
      stt = stt.getTime();
      var endt = new Date("November 13, 2013 " + sundayet);
      endt = endt.getTime();
      if(stt > endt) 
      {
        $('.sundayeterror').text('end time always greater then start time');
        $('#sundayet').val('').change();
      }
    });
});

$('#datepairExample .time').timepicker({
  'showDuration': true,
  'timeFormat': 'g:i a'
});

// $('#datepairExample').datepair();
</script>

</body>
</html>