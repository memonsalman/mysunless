<?php 
// ini_set("display_errors", "1");
// error_reporting(E_ALL);
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
   function select_options($selected = array())
   {
            $db=new db(); 
            $stmt2= $db->prepare("SELECT * FROM `tag` "); 
               $stmt2->execute();
               $all_result_tag = $stmt2->fetchAll(PDO::FETCH_ASSOC);
     $output = '';
    foreach(($all_result_tag) as $item){
         $output.= '<option value="' . $item['tag'] . '"' . (in_array($item['id'], $selected) ? ' selected' : '') . '>' . $item['tag'] . '</option>';
     }
     return $output;
}
    $id=$_SESSION['UserID'];
    $RelatedTo = $db->prepare("SELECT * FROM `clients` WHERE createdfk=:id");
    $RelatedTo->bindValue(":id",$id,PDO::PARAM_INT);
    $RelatedTo->execute();
    $all_client=$RelatedTo->fetchAll(PDO::FETCH_ASSOC);
    // subscription expire progressbar
    if(@$_SESSION['usertype'] == 'subscriber'){
        $id= $_SESSION['UserID'];
        $subscription= $db->prepare("SELECT * FROM `payments` WHERE userid=:id ORDER BY `paytime` DESC LIMIT 1 ");
        $subscription->bindParam(':id', $id, PDO::PARAM_INT);
        $subscription->execute();
        $last_subscription= $subscription->fetch();
        $last_payment_date= $last_subscription['paytime'] ;
        $last_payment_date= substr($last_payment_date, 0, 10);
        $todaydate= date('Y-m-d');
        $difference= abs(strtotime($todaydate) - strtotime($last_payment_date));
        $subscription_years = floor($difference / (365*60*60*24));
        $subscription_months = floor(($difference - $subscription_years * 365*60*60*24) / (30*60*60*24));
        $subscription_days = floor(($difference - $subscription_years * 365*60*60*24 - $subscription_months*30*60*60*24)/ (60*60*24));
        $remaining_days = 30 - $subscription_days ;
    }
    // End subscription expire progressbar
    // 

$button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C84'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];


    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C85'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];


    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C86'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];

    $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='23'"); 
  $title1->execute();
  $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
  $T1=$all_title1['TitleName'];

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/tokenize2.css" rel="stylesheet" type="text/css" />
    <style>
/*.modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal {overflow: hidden;}
body.loading .modal {display: block;}
.eventStart1{width: 45%; float: left; padding: 0 10px;}
.timeinput{width: 35%; float: left; padding: 0 15px;}*/
    #noteRelated {border-bottom : 1px solid #e9ecef !important;}
    li.select2-selection__choice {color: white !important;}
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color: #42bfd3!important;
        border:1px solid #43c1d4!important;
     }
   /* .select2-container--default .select2-selection--multiple{
        border-bottom: 1px solid #e9ecef!important; 
        border-top: 0!important; 
        border-right: 0!important; 
        border-left: 0!important;
    }*/
    span.select2.select2-container.select2-container--default.select2-container--focus{
        width: 100%!important;
    }
    span.select2.select2-container.select2-container--default.select2-container{
        width: 100%!important;
    }
    input.select2-search__field{
        width: 100%!important;
    }
    li.token-search{width: 100%!important;}
    .alphabats a{margin: 5px 0;}

</style>
<body class="skin-default fixed-layout mysunlessZ">
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
                    <div class="col-md-3 align-self-center">
                            <?php
                            if(isset($_GET["id"]))
                            {
                            ?>
                               <h4 class="text-themecolor"><?php echo $T1; ?></h4>
                            <?php       
                                }
                            else
                            {
                                ?>
                                <h4 class="text-themecolor"><?php echo $T1; ?></h4> 
                                <?php
                            }
                             ?>
                    </div>
                </div>
                <div class="row"> 
                <?php
                     if(@$_SESSION['usertype'] == 'subscriber' && @$remaining_days<= '10'){
                ?>
                    <div class="col-lg-12 col-md-12">
                         <div class="alert alert-danger" id="error" style="display: block;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                             <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Your Subscription will be expire in <?php echo $remaining_days ; ?> days. </h3>
                         </div>
                     </div>
                <?
                    }
                ?>       
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="col-md-12">
                                        <div class="col-md-4 pull-right">
 <button class='btn btn-info pull-right' id="addTag" data-toggle="modal" data-target="#addTagModal"> <i class="fa fa-plus"></i> <?php echo $B1; ?></button>
                                            <!-- Add Note Modal -->
                                            <div id="addTagModal" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Add New Tag</h4>
                                                            <div class="Loader"></div>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="form-horizontal " id="Newtag" method="post">
                                                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                                <input type="hidden" name="id" id="id" value="new">
                                                <div class="form-group">
                                                    <label><span class="help">Select Tag *</span></label>
<select class="tokenize-custom-demo1 form-control" id="tag[]"  name="tag[]" multiple>
                                                        <!-- <?php echo select_options() ; ?> -->
                                                    </select>
                                                </div>
                                                <!-- <div class="form-group">
                                                                    <label><span class="help">Related To  *</span></label>
<select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select Related Client" id="tagRelated" name="tagRelated[]" multiple data-style="form-control btn-secondary">
                                                                            <?php 
                                                                                foreach($all_client as $row)
                                                                            {
                                                                            ?>
                                                                                 <option value="<?php echo $row['id'] ;?>"><?php echo $row['FirstName']." ".$row['LastName']; ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                    </select>
                                                                </div>  -->
                                                <div class="modal2"></div>
                                                 <div class="form-group">
<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> <?php echo $B2; ?></button>                              
<button type="submit" class="btn waves-effect waves-light btn-danger" data-dismiss="modal" id="add-client"> <i class="fa fa-times"></i> <?php echo $B3; ?></button>                              
                                                 </div>
                                            </form>
                                                 <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
                                        <div class="alert alert-success" id="resonse" style="display: none;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                                        </div>
                                        <div class="alert alert-danger" id="error2" style="display: none;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                            <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg2"></p>
                                        </div>
                                    <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>

                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <p style="color: red">* Please press enter after write Tag name.</p>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- / Add Note Modal -->
                                        </div>
                                        <div class="col-md-8 list of alphabats">
                                            <a href="#A" class='btn btn-secondary anchorGroup'>A</a>&nbsp
                                            <a href="#B" class='btn btn-secondary anchorGroup'>B</a>&nbsp
                                            <a href="#C" class='btn btn-secondary anchorGroup'>C</a>&nbsp
                                            <a href="#D" class='btn btn-secondary anchorGroup'>D</a>&nbsp
                                            <a href="#E" class='btn btn-secondary anchorGroup'>E</a>&nbsp
                                            <a href="#F" class='btn btn-secondary anchorGroup'>F</a>&nbsp
                                            <a href="#G" class='btn btn-secondary anchorGroup'>G</a>&nbsp
                                            <a href="#H" class='btn btn-secondary anchorGroup'>H</a>&nbsp
                                            <a href="#I" class='btn btn-secondary anchorGroup'>I</a>&nbsp
                                            <a href="#J" class='btn btn-secondary anchorGroup'>J</a>&nbsp
                                            <a href="#K" class='btn btn-secondary anchorGroup'>K</a>&nbsp
                                            <a href="#L" class='btn btn-secondary anchorGroup'>L</a>&nbsp
                                            <a href="#M" class='btn btn-secondary anchorGroup'>M</a>&nbsp
                                            <a href="#N" class='btn btn-secondary anchorGroup'>N</a>&nbsp
                                            <a href="#O" class='btn btn-secondary anchorGroup'>O</a>&nbsp
                                            <a href="#P" class='btn btn-secondary anchorGroup'>P</a>&nbsp
                                            <a href="#Q" class='btn btn-secondary anchorGroup'>Q</a>&nbsp
                                            <a href="#R" class='btn btn-secondary anchorGroup'>R</a>&nbsp
                                            <a href="#S" class='btn btn-secondary anchorGroup'>S</a>&nbsp
                                            <a href="#T" class='btn btn-secondary anchorGroup'>T</a>&nbsp
                                            <a href="#U" class='btn btn-secondary anchorGroup'>U</a>&nbsp
                                            <a href="#V" class='btn btn-secondary anchorGroup'>V</a>&nbsp
                                            <a href="#W" class='btn btn-secondary anchorGroup'>W</a>&nbsp
                                            <a href="#X" class='btn btn-secondary anchorGroup'>X</a>&nbsp
                                            <a href="#Y" class='btn btn-secondary anchorGroup'>Y</a>&nbsp
                                            <a href="#Z" class='btn btn-secondary anchorGroup'>Z</a>&nbsp
                                            <a href="#0" class='btn btn-secondary anchorGroup'>0</a>&nbsp
                                            <a href="#1" class='btn btn-secondary anchorGroup'>1</a>&nbsp
                                            <a href="#2" class='btn btn-secondary anchorGroup'>2</a>&nbsp
                                            <a href="#3" class='btn btn-secondary anchorGroup'>3</a>&nbsp
                                            <a href="#4" class='btn btn-secondary anchorGroup'>4</a>&nbsp
                                            <a href="#5" class='btn btn-secondary anchorGroup'>5</a>&nbsp
                                            <a href="#6" class='btn btn-secondary anchorGroup'>6</a>&nbsp
                                            <a href="#7" class='btn btn-secondary anchorGroup'>7</a>&nbsp
                                            <a href="#8" class='btn btn-secondary anchorGroup'>8</a>&nbsp
                                            <a href="#9" class='btn btn-secondary anchorGroup'>9</a>&nbsp

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">        
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <!-- A -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_a= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'a%' AND createdfk=:id"); 
                                            $stmt_a->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_a->execute();
                                            $result_a = $stmt_a->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_a)){
                                        ?>
                                                <div class="col-md-12" id="A">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> A </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_a as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- B -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_b= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'b%' AND createdfk=:id"); 
                                            $stmt_b->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_b->execute();
                                            $result_b = $stmt_b->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_b)){
                                        ?>
                                                <div class="col-md-12" id="B">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> B </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_b as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- C -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_c= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'c%' AND createdfk=:id "); 
                                            $stmt_c->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_c->execute();
                                            $result_c = $stmt_c->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_c)){
                                        ?>
                                                <div class="col-md-12" id="C">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> C </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_c as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- D -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_d= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'd%' AND createdfk=:id"); 
                                            $stmt_d->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_d->execute();
                                            $result_d = $stmt_d->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_d)){
                                        ?>
                                                <div class="col-md-12" id="D">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> D </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_d as $row) {
                                                              echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- E -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_e= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'e%' AND createdfk=:id"); 
                                            $stmt_e->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_e->execute();
                                            $result_e = $stmt_e->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_e)){
                                        ?>
                                                <div class="col-md-12" id="E">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> E </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_e as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- F -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_f= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'f%' AND createdfk=:id"); 
                                            $stmt_f->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_f->execute();
                                            $result_f = $stmt_f->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_f)){
                                        ?>
                                                <div class="col-md-12" id="F">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> F </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_f as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- G -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_g= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'g%' AND createdfk=:id"); 
                                            $stmt_g->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_g->execute();
                                            $result_g = $stmt_g->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_g)){
                                        ?>
                                                <div class="col-md-12" id="G">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> G </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_g as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- H -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_h= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'h%' AND createdfk=:id"); 
                                            $stmt_h->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_h->execute();
                                            $result_h = $stmt_h->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_h)){
                                        ?>
                                                <div class="col-md-12" id="H">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> H </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_h as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- I -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_i= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'i%' AND createdfk=:id"); 
                                            $stmt_i->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_i->execute();
                                            $result_i = $stmt_i->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_i)){
                                        ?>
                                                <div class="col-md-12" id="I">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> I </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_i as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- J -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_j= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'j%' AND createdfk=:id"); 
                                            $stmt_j->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_j->execute();
                                            $result_j = $stmt_j->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_j)){
                                        ?>
                                                <div class="col-md-12" id="J">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> J </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_j as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- K -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_k= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'k%' AND createdfk=:id"); 
                                            $stmt_k->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_k->execute();
                                            $result_k = $stmt_k->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_k)){
                                        ?>
                                                <div class="col-md-12" id="K">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> K </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_k as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- L -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_l= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'l%' AND createdfk=:id"); 
                                            $stmt_l->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_l->execute();
                                            $result_l = $stmt_l->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_l)){
                                        ?>
                                                <div class="col-md-12" id="L">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> L </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_l as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- M -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_m= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'm%' AND createdfk=:id"); 
                                            $stmt_m->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_m->execute();
                                            $result_m = $stmt_m->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_m)){
                                        ?>
                                                <div class="col-md-12" id="M">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> M </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_m as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- N -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_n= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'n%' AND createdfk=:id"); 
                                            $stmt_n->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_n->execute();
                                            $result_n = $stmt_n->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_n)){
                                        ?>
                                                <div class="col-md-12" id="N">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> N </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_n as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- O -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_o= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'o%' AND createdfk=:id"); 
                                            $stmt_o->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_o->execute();
                                            $result_o = $stmt_o->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_o)){
                                        ?>
                                                <div class="col-md-12" id="O">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> O </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_o as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- P -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_p= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'p%' AND createdfk=:id"); 
                                            $stmt_p->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_p->execute();
                                            $result_p = $stmt_p->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_p)){
                                        ?>
                                                <div class="col-md-12" id="P">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> P </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_p as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- Q -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_q= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'q%' AND createdfk=:id"); 
                                            $stmt_q->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_q->execute();
                                            $result_q = $stmt_q->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_q)){
                                        ?>
                                                <div class="col-md-12" id="Q">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> Q </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_q as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- R -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_r= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'r%' AND createdfk=:id"); 
                                            $stmt_r->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_r->execute();
                                            $result_r = $stmt_r->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_r)){
                                        ?>
                                                <div class="col-md-12" id="R">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> R </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_r as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- S -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_s= $db->prepare("SELECT * FROM tag WHERE tag LIKE 's%' AND createdfk=:id"); 
                                            $stmt_s->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_s->execute();
                                            $result_s = $stmt_s->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_s)){
                                        ?>
                                                <div class="col-md-12" id="S">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> S </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_s as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- T -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_t= $db->prepare("SELECT * FROM tag WHERE tag LIKE 't%' AND createdfk=:id"); 
                                            $stmt_t->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_t->execute();
                                            $result_t = $stmt_t->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_t)){
                                        ?>
                                                <div class="col-md-12" id="T">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> T </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_t as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- U -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_u= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'u%' AND createdfk=:id"); 
                                            $stmt_u->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_u->execute();
                                            $result_u = $stmt_u->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_u)){
                                        ?>
                                                <div class="col-md-12" id="U">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> U </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_u as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- V -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_v= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'v%' AND createdfk=:id"); 
                                            $stmt_v->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_v->execute();
                                            $result_v = $stmt_v->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_v)){
                                        ?>
                                                <div class="col-md-12" id="V">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> V </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_v as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- W -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_w= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'w%' AND createdfk=:id"); 
                                            $stmt_w->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_w->execute();
                                            $result_w = $stmt_w->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_w)){
                                        ?>
                                                <div class="col-md-12" id="W">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> W </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_w as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- X -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_x= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'x%' AND createdfk=:id"); 
                                            $stmt_x->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_x->execute();
                                            $result_x = $stmt_x->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_x)){
                                        ?>
                                                <div class="col-md-12" id="X">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> X </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_x as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- Y -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_y= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'y%' AND createdfk=:id"); 
                                            $stmt_y->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_y->execute();
                                            $result_y = $stmt_y->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_y)){
                                        ?>
                                                <div class="col-md-12" id="Y">
                                                    <div class="col-md-2 pull-left" >
                                                        <h4> Y </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php   
                                                           foreach ($result_y as $row) {
                                                                echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                           }
                                                       ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php 
                                            }
                                        ?>
                                    <!-- Z -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_z= $db->prepare("SELECT * FROM tag WHERE tag LIKE 'z%' AND createdfk=:id"); 
                                            $stmt_z->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_z->execute();
                                            $result_z = $stmt_z->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_z)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> Z </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_z as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                               <!-- 0 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_0 = $db->prepare("SELECT * FROM tag WHERE tag LIKE '0%' AND createdfk=:id"); 
                                            $stmt_0->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_0->execute();
                                            $result_0 = $stmt_0->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_0)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 0 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_0 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>



                                        <!--1 vcb -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_1= $db->prepare("SELECT * FROM tag WHERE tag LIKE '1%' AND createdfk=:id"); 
                                            $stmt_1->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_1->execute();
                                            $result_1 = $stmt_1->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_1)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 1 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_1 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                        <!--2 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_2= $db->prepare("SELECT * FROM tag WHERE tag LIKE '2%' AND createdfk=:id"); 
                                            $stmt_2->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_2->execute();
                                            $result_2 = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_2)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 2 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_2 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                        <!--3 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_3= $db->prepare("SELECT * FROM tag WHERE tag LIKE '3%' AND createdfk=:id"); 
                                            $stmt_3->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_3->execute();
                                            $result_3 = $stmt_3->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_3)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 3 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_3 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                        <!-- 4 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_4= $db->prepare("SELECT * FROM tag WHERE tag LIKE '4%' AND createdfk=:id"); 
                                            $stmt_4->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_4->execute();
                                            $result_4 = $stmt_4->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_4)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 4 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_4 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>



                                         <!-- 5 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_5= $db->prepare("SELECT * FROM tag WHERE tag LIKE '5%' AND createdfk=:id"); 
                                            $stmt_5->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_5->execute();
                                            $result_5 = $stmt_5->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_5)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 5 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_5 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                                <!-- 6 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_6= $db->prepare("SELECT * FROM tag WHERE tag LIKE '6%' AND createdfk=:id"); 
                                            $stmt_6->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_6->execute();
                                            $result_6 = $stmt_6->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_6)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 6 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_6 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                              <!-- 7 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_7= $db->prepare("SELECT * FROM tag WHERE tag LIKE '7%' AND createdfk=:id"); 
                                            $stmt_7->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_7->execute();
                                            $result_7 = $stmt_7->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_7)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 7 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_7 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>


                                        <!-- 8 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_8= $db->prepare("SELECT * FROM tag WHERE tag LIKE '8%' AND createdfk=:id"); 
                                            $stmt_8->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_8->execute();
                                            $result_8 = $stmt_8->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_8)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 8 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_8 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>

                                        <!-- 9 -->
                                        <?php 
                                            $id=$_SESSION['UserID'];
                                            $stmt_9= $db->prepare("SELECT * FROM tag WHERE tag LIKE '9%' AND createdfk=:id"); 
                                            $stmt_9->bindParam(':id', $id, PDO::PARAM_INT);
                                            $stmt_9->execute();
                                            $result_9 = $stmt_9->fetchAll(PDO::FETCH_ASSOC);
                                            if(!empty($result_9)){
                                        ?>
                                                <div class="col-md-12" id="Z">
                                                    <div class="col-md-2 pull-left">
                                                        <h4> 9 </h4>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <?php       
                                                            foreach ($result_9 as $row) {
                                                               echo "<span class='btn btn-secondary ' value='".$row['tag']."'>".$row['tag']." </span>
                                                                    &nbsp";
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <hr>
                                        <?php
                                            }
                                        ?>

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
<!-- <script type="text/javascript">
    $(document).ready(function(){
            // $(".displayOperation").hide();
            // $(".TagOperation").on("click", function(){
            //     $('.displayOperation').css('display','none');
            //     var val = $(this).val();
            //     $(this).next(".displayOperation").show();
            // });
            $(document).on('click','#deleteTag',function(e){
                e.preventDefault();
                var link = $(this).attr('href');
                swal({
                    title: "Are you sure?",
                    text: "you are really want to delete this Tag!",
                    icon: "warning",
                    buttons: true,
                }).then((willDelete)=>{   
                    if (willDelete){
                          window.location.href = link;
                    }
                     else{
                         return false ;
                     }
                });
            });
    });
</script> -->
<script>
    $("#Newtag").validate({

            rules: {                
                "tag[]": {required: true,},
                // "tagRelated[]": {required: true,},
            },
            messages: {             
                "tag[]": {required: "Please Enter Tag Name"},
                // "tagRelated[]": {required: "Please Relate the tag to a contact"},
            },
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "tagRelated[]" || element.attr( "name" ) === "tag[]") {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },
            submitHandler: function() {
                $(".Loader").show();
                var data = $("#Newtag").serialize();
                data= data + "&Action=tag";
                jQuery.ajax({
                    dataType:"json",
                    type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_Edit_Tag.php',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                        
                        $("#resonse").show();

                        //console.log(data.resonse);die;
                        $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                        $( '#Newtag' ).each(function(){
                            this.reset();
                        });
                        $(".Loader").hide();
                        setTimeout(function () { window.location.reload(true); }, 1000) ;
                        dataTable()
                        }
                        else if(data.error2)
                        {
                            $("#error2").show();
                              $('#errormsg2').html('<span>'+data.error2+'</span>');
                            $(".Loader").hide();
                        // alert('<li>'+data.error+'</li>');
                        }
                          else if(data.csrf_error)
                {
                  
                    $("#csrf_error").show();
                    $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    setTimeout(function () { window.location.reload() }, 2000)
                }
                    }
                });
            }           
    });

</script>
<script type="text/javascript">
$(document).ready(function(){
    $(document).on('click','.anchorGroup',function(e){
        event.preventDefault();
        var hash = this.hash;
        $('html, body').animate({scrollTop: $(hash).offset().top}, 900);
    });
 });
</script>
<script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {
        // Switchery
        $(".listofclientdiv").hide();
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2
        $(".select2").select2();
        $('.selectpicker').selectpicker();
        //Bootstrap-TouchSpin
        $(".vertical-spin").TouchSpin({
            verticalbuttons: true,
            verticalupclass: 'ti-plus',
            verticaldownclass: 'ti-minus'
        });
        var vspinTrue = $(".vertical-spin").TouchSpin({
            verticalbuttons: true
        });
        if (vspinTrue) {
            $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
        }
        $("input[name='tch1']").TouchSpin({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
        });
        $("input[name='tch2']").TouchSpin({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
        });
        $("input[name='tch3']").TouchSpin();
        $("input[name='tch3_22']").TouchSpin({
            initval: 40
        });
        $("input[name='tch5']").TouchSpin({
            prefix: "pre",
            postfix: "post"
        });
        // For multiselect
        $('#pre-selected-options').multiSelect();
        $('#optgroup').multiSelect({
            selectableOptgroup: true
        });
        $('#public-methods').multiSelect();
        $('#select-all').click(function() {
            $('#public-methods').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function() {
            $('#public-methods').multiSelect('deselect_all');
            return false;
        });
        $('#refresh').on('click', function() {
            $('#public-methods').multiSelect('refresh');
            return false;
        });
        $('#add-option').on('click', function() {
            $('#public-methods').multiSelect('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
        });
        $(".ajax").select2({
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
    </script>
    <!-- start Add multiple tag at once js -->
    <script src="../assets/js/tokenize2.js"></script>
     <script>
            $('.tokenize-sample-demo1').tokenize2();
            $('.tokenize-remote-demo1, .tokenize-remote-modal').tokenize2({
                dataSource: 'remote.php'
            });
            $('.tokenize-limit-demo1').tokenize2({
                tokensMaxItems: 5
            });
            $('.tokenize-limit-demo2').tokenize2({
                tokensMaxItems: 1
            });
            $('.tokenize-ph-demo1').tokenize2({
                placeholder: 'Please add new tokens'
            });
            $('.tokenize-sortable-demo1').tokenize2({
                sortable: true
            });
            $('.tokenize-custom-demo1').tokenize2({
                tokensAllowCustom: true
            });
            $('.tokenize-callable-demo1').tokenize2({
                dataSource: function(search, object){
                    $.ajax('remote.php', {
                        data: { search: search, start: 1 },
                        dataType: 'json',
                        success: function(data){
                            var $items = [];
                            $.each(data, function(k, v){
                                $items.push(v);
                            });
                            object.trigger('tokenize:dropdown:fill', [$items]);
                        }
                    });
                }
            });
            $('.tokenize-override-demo1').tokenize2();
            $.extend($('.tokenize-override-demo1').tokenize2(), {
                dropdownItemFormat: function(v){
                    return $('<a />').html(v.text + ' override').attr({
                        'data-value': v.value,
                        'data-text': v.text
                    })
                }
            });
            $('#btnClear').on('mousedown touchstart', function(e){
                e.preventDefault();
                $('.tokenize-demo1, .tokenize-demo2, .tokenize-demo3').tokenize2().trigger('tokenize:clear');
            });
        </script>
<!-- End Add multiple tag at once js -->
     <script src="../assets/js/tokenize2.js"></script>
     <script>
            $('.tokenize-sample-demo1').tokenize2();
            $('.tokenize-remote-demo1, .tokenize-remote-modal').tokenize2({
                dataSource: 'remote.php'
            });
            $('.tokenize-limit-demo1').tokenize2({
                tokensMaxItems: 5
            });
            $('.tokenize-limit-demo2').tokenize2({
                tokensMaxItems: 1
            });
            $('.tokenize-ph-demo1').tokenize2({
                placeholder: 'Please add new tokens'
            });
            $('.tokenize-sortable-demo1').tokenize2({
                sortable: true
            });
            $('.tokenize-custom-demo1').tokenize2({
                tokensAllowCustom: true
            });
            $('.tokenize-callable-demo1').tokenize2({
                dataSource: function(search, object){
                    $.ajax('remote.php', {
                        data: { search: search, start: 1 },
                        dataType: 'json',
                        success: function(data){
                            var $items = [];
                            $.each(data, function(k, v){
                                $items.push(v);
                            });
                            object.trigger('tokenize:dropdown:fill', [$items]);
                        }
                    });
                }
            });
            $('.tokenize-override-demo1').tokenize2();
            $.extend($('.tokenize-override-demo1').tokenize2(), {
                dropdownItemFormat: function(v){
                    return $('<a />').html(v.text + ' override').attr({
                        'data-value': v.value,
                        'data-text': v.text
                    })
                }
            });
            $('#btnClear').on('mousedown touchstart', function(e){
                e.preventDefault();
                $('.tokenize-demo1, .tokenize-demo2, .tokenize-demo3').tokenize2().trigger('tokenize:clear');
            });
        </script>
</body>
</html>