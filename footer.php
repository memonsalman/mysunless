<?php 
require_once('function.php');

if(isset($_REQUEST['submitReport'])){

  $file_name = "";
  if(isset($_FILES['RepImage']['name']) && !empty($_FILES['RepImage']['name'])){
    $errors= array();
    @$file_name = $_FILES['RepImage']['name'];
    $file_size = $_FILES['RepImage']['size'];
    $file_tmp = $_FILES['RepImage']['tmp_name'];
    @$file_type = $_FILES['RepImage']['type'];
    @$file_ext=strtolower(end(explode('.',$file_name)));
    $expensions= array("jpeg","jpg","png","pdf","xlsx","txt","docx");
    if(in_array($file_ext,$expensions) === false){
     $errors[] = "Only PDF, JPEG, PNG, XLSX, TXT, DOCX are allowed please choose one of them for submit report.";
   }

   if($file_size > 2097152) {
     $errors[]='File size must be excately 2 MB';
   }
   if(!empty($errors[0])) {
     echo json_encode(['error'=>"Only PDF, JPEG, PNG, XLSX, TXT, DOCX are allowed please choose one of them for submit report."]);die;
   }
   $file_name = "uploads/".$file_name;
   if(move_uploaded_file($file_tmp,$file_name)){
    $file_name = $_SERVER["DOCUMENT_ROOT"].'/crm/'.$file_name;
    // echo json_encode(['error'=>$file_name]);die;
   }else{
    echo json_encode(['error'=>"Invalid file destination."]);die;
   }


 }


 $Username = $_SESSION['UserName'];
 $headers = '';
 $message="Hi";
 $Subject=stripslashes(strip_tags($_POST['RepName']));
 $Message=$_POST['RepMessage'];
 $other['--TITLE--'] = $Subject;
 $other['--MESSAGE--'] = nl2br($Message);
 $other['--USERNAME--'] = $Username;
 $reportsend2 = sendsmpleMailReport('test@mysunless.com',$Subject, "Report.php", $message, $headers, $other,@$file_name);

 if(!empty($reportsend2))
 {
  if($file_name){
    unlink($file_name);
  }
  echo json_encode(['resonse'=>'Issue has been reported successfully']);die;  
}
die;

}



$button21= $db->prepare("SELECT button_name FROM `ButtonSetting` where page_name='dashboard' AND button_id='C21'"); 
$button21->execute();
$all_button21 = $button21->fetch(PDO::FETCH_ASSOC);
$B21=$all_button21['button_name'];

$button19= $db->prepare("SELECT TutorialMsg FROM `Tutorial` where id='19'"); 
$button19->execute();
$all_button19 = $button19->fetch(PDO::FETCH_ASSOC);
$T19=$all_button19['TutorialMsg'];

?>
<style>
  .mysunless_footer{
    bottom: 0;
    color: #212529;
    left: 0;
    padding: 17px 15px;
    right: 0;
    border-top: 1px solid #e9ecef;
    background: #fff;
    text-align: left;
  }



  #LocalTimezone-fixed-form-container{
    text-align: right;
    margin: 0;
  }

  #LocalTimezone-fixed-form-container hr{
    margin: 3px;
  }

  #LocalTimezone-fixed-form-container .LocalTimezoneButton { 
    font-size: 2.1em;
    cursor: pointer;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 2px;
    border-radius: 50%;
    padding: 0px 10px;
    background-color: #31b131;
    color: #fff;
    display: inline-block;
    text-align: right;
    text-decoration: none;
    box-shadow: -7px 5px 5px 0px rgba(0,0,0,0.3);
  }

  #LocalTimezone-fixed-form-container .LocalTimezoneBody{
   background-color: #fff;
   text-align: right;
   border-radius: 0px 5px 5px 5px;
   border: 2px solid #31b131;
   margin-bottom: 5px;
   padding: 10px;
   -webkit-box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.3);
   -moz-box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.3);
   box-shadow: 4px 4px 5px 0px rgba(0,0,0,0.3);
 }

 @media only screen and (max-width: 414px){

  #LocalTimezone-fixed-form-container {
    right: 8px;
  }

  #LocalTimezone-fixed-form-container .LocalTimezoneButton {
    font-size: 20px;
    padding: 0 7px;
  }

  #Report{
    font-size: 8px;
  }

}
#Copyright_Footer{
    margin-top: 40px;
    text-align: center;
    background: white;
    padding: 15px;
}
</style>
<!-- Footer.php -->
<footer>
  <div id="Copyright_Footer"> © <?php echo date("Y");  ?> All right reserved by <a target="_blank" href="https://mysunless.com">MySunless</a></div>


  <div id="footer_report_button" style="position: fixed; right: 10px; bottom: 20px; z-index: 99999;">


<!--     <section id="LocalTimezone-fixed-form-container">
      <div class="LocalTimezoneButton"><i class="fa fa-globe"></i></div>
      <div class="LocalTimezoneBody LocalTimezone"></div>
    </section> -->

    <?php if($_SESSION['usertype']!='Admin'){?>
      <button type="button" id="Report" class="btn btn-danger" data-step="19" data-intro="<?php echo $T19; ?>" data-position='right' data-scrollTo='tooltip'><?php echo $B21; ?></button>
    <?php } ?>
  </div>

  <script>
    $("#LocalTimezone-fixed-form-container .LocalTimezoneBody").hide();
    $("#LocalTimezone-fixed-form-container .LocalTimezoneButton").click(function () {
      $(this).next("#LocalTimezone-fixed-form-container div").slideToggle(400);
      $(this).toggleClass("expanded");
    });
  </script>

  <div class="modal fade" id="myModal_report" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            Send Report
          </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id='contactus' enctype="multipart/form-data" autocomplete="off" method='post'>
           <div class="Loader"></div>
           <fieldset >
            <div class="form-group">
              <label for='email' >Subject *</label>
              <br/>
              <input class="form-control" placeholder="Title" type="text" id="RepName" name="RepName" value="" required />
              <br>
            </div>
            <div class="form-group">
              <label>Description *</label>
              <textarea class="textarea_editor form-control" rows="5" placeholder="Enter Message ..." id="RepMessage" name="RepMessage"></textarea>
            </div>
            <div class="form-group">
              <label>Upload File </label>
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-body">
                    <input type="file" id="RepImage" name="RepImage" data-allowed-file-extensions='["png", "jpg","jpeg","pdf","xlsx","txt","docx"]' data-max-file-size="2M" />
                  </div>
                </div>
              </div>
              <small>Note: Only PDF, JPEG, PNG, XLSX, TXT, DOCX are allowed. <br> File size should be less than 2MB </small>
            </div>
            <div class="Loader">
            </div>

            <div id="errormsgUpd" role="alert" class="mb-2">
              <div class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                Only PDF, JPEG, PNG, XLSX, TXT, DOCX are allowed please choose one of them for submit Bug report.
              </div>
            </div>

            <div class="form-group">
              <button type="submit" name="Repsend" class="btn waves-effect waves-light btn-info m-r-10" id="send"><i class="fa fa-check">
              </i> Send</button>
              <button type="button" class="btn waves-effect waves-light btn-danger ReportBugClear"><i class="fa fa-times">
              </i> Cancel </button>
            </div>
          </fieldset>
        </form>

      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
   </div>
 </div>
</div>

<script>
  $(document).ready(function() {

    $("#RepImage").dropify();

    $("#RepMessage").ckeditor();

  //   CKEDITOR.replace( 'RepMessage', {
  //   // extraPlugins: 'easyimage',
  //   // cloudServices_tokenUrl: 'https://76338.cke-cs.com/token/dev/3bb36f6d5513b4231c2e25ae7b142bfb26e09cb820383bea724fbd4264c7',
  //   // cloudServices_uploadUrl: 'https://76338.cke-cs.com/easyimage/upload/'
  // } );



    $('#errormsgUpd').hide();

    $(".ReportBugClear").click(function(){
     $( '#contactus' ).each(function(){
       this.reset();
     });
     $('#RepMessage').val('');
     $("#RepImage").next('.dropify-clear').trigger('click');
     $("#RepImage").parents(".dropify-wrapper").removeClass('has-error');
   });

    $("#Report").click(function() {

      $('#myModal_report').modal('show');

      $("#contactus").validate({
        rules: {                
          RepName: "required",
          RepMessage : "required",
        },
        messages: {             
          RepName:  "Please enter title",
          RepMessage:  "Please enter description",
        },

        submitHandler: function() {
          $(".Loader").show();
          var form = $('#contactus')[0];
          var data = new FormData(form);

          jQuery.ajax({
           dataType:"json",
           type:"post",
           data:data,
           contentType: false, 
           processData: false,
           url:'footer.php?submitReport',
           success: function(data)
           {
            $(".Loader").hide(); 
            if(data.resonse)
            {
              $(".ReportBugClear").trigger('click');
             swal("Success!", data.resonse, "success");       
             $('#myModal_report').modal('hide');
           }
           else if(data.error)
           {
             swal("Error!", data.error, "error");       

           }
         }
       });
        }           
      });

    });
  });
</script>
</footer>