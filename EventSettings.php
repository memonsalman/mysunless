<?php 
require_once('function.php');


if(empty($_SESSION["UserID"]) && $_SESSION["usertype"]!="subscriber" ){
  header("Location: ../index.php");die;
}

if(isset($_GET['SetData'])){
  $run = false;
  $id=$_SESSION['UserID'];
  $query=$db->prepare("select * from `EventRemainder` where createdfk=:id");
  $query->bindParam(':id', $id);
  $query->execute();

  $RepeatDay = $_POST['Riminederdate'];
  $RepeatDay = implode(',',$RepeatDay);
  $messages = $_POST['Message'];

  if($query->rowCount()>0){
    $query=$db->prepare("UPDATE EventRemainder set Message=:messages,RepeatDay=:RepeatDay where createdfk=:id");
    $query->bindParam(':id', $id);
    $query->bindParam(':messages', $messages);
    $query->bindParam(':RepeatDay', $RepeatDay);
    $run = $query->execute();
  }else{
    $query=$db->prepare("INSERT into EventRemainder (Message,RepeatDay,createdfk)values(:messages,:RepeatDay,:id)");
    $query->bindParam(':id', $id);
    $query->bindParam(':messages', $messages);
    $query->bindParam(':RepeatDay', $RepeatDay);
    $run = $query->execute();
  }

  if($run){
    echo json_encode(['resonse'=>'Success']);
  }else{
    echo json_encode(['resonse'=>'Something went wrong. Please try again']);
  }
  die;
}

$Message="";
$Riminederdate="";

$id=$_SESSION['UserID'];
$EditEvent=$db->prepare("select * from `EventRemainder` where createdfk=:id");
$EditEvent->bindParam(':id', $id);
$EditEvent->execute();

$GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
$Message=$GetEvent['Message'];
$Riminederdate=$GetEvent['RepeatDay'];
$final= explode(',', $Riminederdate);

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<style>

  .eventStart1{width: 45%; float: left; padding: 0 10px;}
  input#CostOfService{width: 96% !important;}
  .custom-control{
    padding-left: 1.5rem!important;
  }
</style>
<body class="skin-default fixed-layout mysunlessA5">
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
          <h4 class="text-themecolor">Event Remainder Setting</h4>
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
            <!-- Tab panes -->
            <div class="tab-content tabcontent-border">
              <div class="tab-pane active" id="home" role="tabpanel">
                <div class="col-lg-12">
                  <form class="form-horizontal " autocomplete="off" id="EventRem" method="post">
                    <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">

                    <div class="form-group">
                      <label>Send Reminder To Clients *</label>
                      <div class="checkboxgro">

                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd1" value="1" <?php if(in_array("1",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd1">Before 1 day</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd2" value="2" <?php if(in_array("2",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd2">Before 2 day</label>
                        </div> <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd3" value="3" <?php if(in_array("3",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd3">Before 3 day</label>
                        </div> <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd4" value="4" <?php if(in_array("4",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd4">Before 4 day</label>
                        </div> <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd5" value="5" <?php if(in_array("5",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd5">Before 5 day</label>
                        </div> <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd6" value="6" <?php if(in_array("6",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd6">Before 6 day</label>
                        </div> <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="customCheckd7" value="7" <?php if(in_array("7",$final)) {echo "checked='checked'"; }?> name="Riminederdate[]">
                          <label class="custom-control-label" for="customCheckd7">Before 7 day</label>
                        </div>

                      </div>
                    </div>
                    <div class="form-group">
                      <label>Email Reminder Note *</label>
                      <textarea class="textarea_editor2 form-control" rows="10" placeholder="Enter text ..." id="Message" name="Message"><?php echo @$Message;?></textarea>
                    </div>
                    <div class="modal"></div>
                    <div class="form-group">
                     <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i>Save</button>
                   </div>
                 </form>
               </div>
             </div>



           </div>
         </div>
         <div class="col-lg-12 col-md-12">
          <div class="alert alert-success" id="resonse" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
          </div>
          <div class="alert alert-danger" id="error" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
          </div>

          <div class="alert alert-danger" id="csrf_error" style="display: none;">
            <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
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
<script src="<?php echo base_url ?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php  echo base_url ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>


<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){$(".eventset").addClass("active");}, 10);

    $("#Message").ckeditor();

    $("#EventRem").submit(function(e){
      e.preventDefault();
      $(".Loader").show();
      var data = $("#EventRem").serialize();
      jQuery.ajax({
       dataType:"json",
       type:"post",
       data:data,
       url:'?SetData',
       success: function(data)
       {
        if(data.resonse)
        {
         swal("",data.resonse,"success");
         $(".Loader").hide();
       }
       else if(data.error)
       {
        swal("",data.error,"error");
        $(".Loader").hide();
      }
    }   
  });

    });



    $("#NewEvent").validate({
      rules: {                
        CostOfService: {required: true, number: true},
        EmailInstruction: {required: true,},
        EmailReminder: {required: true,},
      },
      messages: {             
        CostOfService: {required: "Please enter cost of service" , number: "Please enter valid price"},
        EmailInstruction: {required: "Please enter email instruction"},
        EmailReminder:{required: "Please enter email reminder"},
      },
      ignore: ":hidden:not(textarea)",
      errorPlacement: function( label, element ) {
        if( element.attr( "name" ) === "EmailInstruction" || element.attr( "name" ) === "EmailReminder" ) {
          element.parent().append( label );
        } else {
         label.insertAfter( element );
       }
     },
     submitHandler: function() {
      $(".Loader").show();
      var data = $("#NewEvent").serialize();
      data= data + "&LoginAction=Login";
      jQuery.ajax({
       dataType:"json",
       type:"post",
       data:data,
       url:'<?php echo EXEC; ?>Exec_Edit_EventSetting.php',
       success: function(data)
       {
        if(data.resonse)
        {
          $("#resonse").show();
          $('#resonsemsg').html('<span>'+data.resonse+'</span>');
          $( '#NewEvent' ).each(function(){
           this.reset();
         });
          $(".Loader").hide();
          setTimeout(function () { window.location.reload(); }, 5000)
        }
        else if(data.error)
        {
          $("#error").show();
          $('#errormsg').html('<span>'+data.error+'</span>');
          $(".Loader").hide();
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
  });
</script>
</body>
</html>