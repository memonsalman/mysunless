<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

if($_SESSION['usertype'] != "Admin")
{
  header("Location: dashboard.php");die;
}

// if(@$_SESSION['usertype']!="Admin")
// {
//  header("Location: ../index.php");die;   
// }




if(isset($_REQUEST['dlink']))
{
  $myFaqs = base64_decode($_REQUEST["dlink"]);
  $DeleteFaqs = $db->prepare("delete from `faqs` where id=:myFaqs");
  $DeleteFaqs->bindValue(":myFaqs",$myFaqs,PDO::PARAM_INT);
  $DeleteFaqs->execute();

  if($DeleteFaqs)
  {
    echo  json_encode(["resonse"=>'FAQ Successfully Remove From List']);die;
  }
}


if(isset($_REQUEST['elink']))
{

  $myFaqs = base64_decode($_REQUEST["elink"]);
  $EditFaqs=$db->prepare("select * from `faqs` where id=:myFaqs");
  $EditFaqs->bindValue(":myFaqs",$myFaqs, PDO::PARAM_INT);
  $EditFaqs->execute();
  $GetFaqs=$EditFaqs->fetch(PDO::FETCH_ASSOC);


  if($EditFaqs)
  {
    echo  json_encode(["resonse"=>$GetFaqs]);die;

  }

}


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<!-- <link rel="stylesheet" href="<?php echo base_url ?>/css/select2.min.css"> -->

<style type="text/css">
  .delete{margin: 3px;color: white;}
  th{ font-weight: bold!important;color: #0b59a2!important;  }

  .newCat,.newCancel{
    margin-bottom: 12px;
    font-size: 11px;

  }

  .newCat,.newCancel:hover{
    cursor: pointer;

  }
</style>

<body class="skin-default fixed-layout">
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
          <h4 class="text-themecolor">FAQS</h4>
        </div>
      </div>          
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <!-- Tab panes -->
              <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="home" role="tabpanel">
                  <div class="col-lg-12">
                    <a href=" <?php echo base_url;  ?>/AddFaqs.php" data-toggle="modal" data-target="#myModal" id="addFaqsaddFaqsaddFaqs" class="btn btn-info m-r-10 ">Add New FAQ</a>
                    
                    <div class="table-responsive m-t-40">
                      <table id="myTable" class="table table-bordered table-striped dataTable no-footer">
                        <thead>
                          <tr>
                            <th class="tableCategory">Category</th> 

                            <th class="tableTitle">Question</th>
                            <th class="tableDesc">Answer</th>
                            <!-- <th class="tableDesc">Category</th> -->
                            <th class="tableAction">Action</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog" style="max-width:1000px">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Add FAQ</h4>
                            <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>

                          </div>
                          <div class="modal-body" style="margin:0px">

                            <form method="post" id="faqForm" autocomplete="off" class="form-horizontal p-2">
                              <input type="hidden" name="id" id="id" value="new">
                              <div class="row">
                                <div class="col-md-6">

                                  <div class="form-group">
                                    <label><h2> Category </h2></label><br>
                                    <span class="badge badge-primary newCat">Click here to create new category</span>
                                    <span class="badge badge-danger newCancel">Cancel</span>
                                    <input type="text" name="faqCategoryNew" id="faqCategory" placeholder="Enter Category...." class="newCatInput form-control" value="" required="">

                                    <span class="whitespace_err" style="color: red"> don't leave it empty</span>
                                    <span class="categoryExist" style="color: red">Category Already Exists!</span>

                                    <div class="selCat">
                                     <select class="js-example-basic-single" name="faqCategoryOld" id="faqCategory">

                                     </select>
                                   </div>

                                 </div>

                                 <div class="form-group">
                                  <label><h2> Question </h2></label><br>
                                  <input type="text" name= "faqTitle" id="faqTitle" placeholder="Enter Title...." class="form-control" value="" >
                                </div>
                                <div>
                                  <h2>HTML Tips:</h2>
                                  <ul>
                                    <li>Mobile responsive for Image, Video, frame, etc.<br>Add Class 'fixed-frame' to that HTML tag.<br> Example:<br> <code>&lt;iframe class="fixed-frame" src="LINK_OF_THE_VIDEO_FRAME" width="100%" height="300" &gt;&lt;/iframe&gt;</code>
                                    </li>
                                  <li>Use width="100%" for fullscreen/full-width</li>
                                </ul>
                              </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label><h2> Answer </h2></label><br>
                                    <!-- <textarea class="textarea_editor form-control" rows="10" placeholder="Enter text ..." name="faqDesc" id="faqDesc" >  </textarea> -->
                                    <textarea class="" rows="10" placeholder="Enter text ..." name="faqDesc" id="faqDesc" >  </textarea>

                                  </div>
                                </div>
                                <div class="form-group">
                                  <?php if(isset($_GET["id"])) { ?>
                                   <button type="submit" name="faqSub" id="faqSub" class="btn btn-info"><i class="fa fa-check"></i> Update FAQ</button>
                                 <?php  } else { ?>
                                  <button type="submit" name="faqSub" id="faqSub" class="btn btn-info"><i class="fa fa-check"></i> Add FAQ</button>
                                <?php } ?>
                              </div>
                              <div class="Loader"></div>
                            </div>
                          </form>

                          <div class="col-lg-12 col-md-12">
                            <div class="alert alert-success" id="resonse" style="display: none;">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                              <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                            </div>
                            <div class="alert alert-danger" id="error" style="display: none;">
                             <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                             <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                           </div>
                         </div>

                       </div>
                     </div>

                   </div>
                 </div>
                 <!-- view faq -->
                 <div class="modal fade" id="faqmodal" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">FAQ Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                      </div>
                      <div class="modal-body">

                        <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                          <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                          <div class="Loader"></div>
                          <input type="hidden" name="vid" id="vid" value="">
                          <div style="margin-bottom: inherit;" class="form-group">
                            <label for="posttitle">
                              <h3>Category</h3>
                              <p style="font-size: 16px;"  id="vcategory" ></p>
                            </label>
                            <br>
                          </div>    

                          <div style="margin-bottom: inherit;" class="form-group">
                            <label for="posttitle">
                              <h3>Question</h3>
                              <p style="font-size: 16px;"  id="vtitle" ></p>
                            </label>
                            <br>
                          </div>
                          <div  style="margin-bottom: inherit;" class="form-group">
                            <label for="todoDesc">
                              <h3>Answer</h3>
                              <p style="font-size: 16px;" id="vdesc" ></p>
                            </label>
                            <br>
                          </div>
                        </form>

                      </div>
                    </div>

                  </div>
                </div>
                <!-- view faq -->
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
<!-- <script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
-->


<script type="text/javascript" src="<?php echo base_url; ?>/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/ckeditor/adapters/jquery.js"></script>

<script>
  $(document).ready(function() {
   $( 'textarea' ).ckeditor();

       // $('.textarea_editor').wysihtml5();
     });
   </script>
   <script src="<?php echo base_url; ?>/assets/js/sweetalert.min.js"></script>
   <script>
    $(document).ready(function() {


      $(".modelClose").click(function(){

              //alert("asda");
              $('#faqForm').trigger("reset");
              $('#faqDesc').val("");

            });

      $(".whitespace_err").hide();
      $(".categoryExist").hide();
      
      var uniqueNamesCheck = [];
      var f;
      var categoryMatch = 0;
      $(".newCatInput").focus(function(){
        uniqueNamesCheck = [];
        $(".categoryExist").hide();
        f= 0;
        categoryMatch=0;
        jQuery.ajax({
          dataType:"json",
          type:"post",
          url:'<?php echo EXEC; ?>Exec_FaqsList.php',
          success: function(data)
          {
            $('.js-example-basic-single').html("");


                    //var uniqueNames = [];
                    var j;
                    for(j = 0; j< data.length; j++){    
                      if(uniqueNamesCheck.indexOf(data[j].faqCategory) === -1){
                        uniqueNamesCheck.push(data[j].faqCategory);        
                      }        
                    }

                    


                    $(".Loader").hide();

                  }
                });
      });



      $(".newCatInput").focusout(function(){


       var length = $.trim($(".newCatInput").val()).length;
       if(length == 0)
       {
         $(".whitespace_err").show();
         $(".categoryExist").hide();
         
       }
       else
       {
         $(".whitespace_err").hide();

         var  inputVal = $.trim($(".newCatInput").val()).toLowerCase();
         
                 //console.log(inputVal);

                 for(f = 0; f< uniqueNamesCheck.length; f++){   

                  if(uniqueNamesCheck[f] != null)
                  { 
                    if(uniqueNamesCheck[f].toLowerCase() == inputVal)
                    {
                      categoryMatch = 1;
                    }
                    
                            //$('.js-example-basic-single').append('<option value="'+uniqueNames[f]+'">'+uniqueNames[f]+'</option>');

                          }
                        }

                      //if( $("#test").css('display').toLowerCase() == 'block') 
                      
                    // if($(".newCatInput").css('display').toLowerCase() == 'initial')
                    // {
                    //     $(".categoryExist").hide();

                    // }

                    if(categoryMatch == 1)
                    {
                      //console.log("match");

                      $(".whitespace_err").hide();
                      $(".categoryExist").show();

                      
                    } 

                  }
                  
                });

        //active class
        setInterval(function(){$(".allfaq").addClass("active");}, 10);
        dataTable()

        $("#faqForm").validate({
          ignore: ":hidden:not(textarea)",
          rules: {


            faqCategoryOld: {required: true,},                
            faqTitle: {required: true, maxlength: 255},
            faqDesc: {required: true,}
          },
          messages: {

            faqCategoryOld:{required: "Please Enter Category"},  
            faqTitle: {required: "Please Enter  Question"},
            faqDesc: {required: "Please Enter  FAQ Description"},
          },
          errorPlacement: function( label, element ) {
            if( element.attr( "name" ) === "faqDesc" ) {
              element.parent().append( label );
            } else {
             label.insertAfter( element );
           }
         },
         submitHandler: function() {
          $(".Loader").show();
          
          if($(".whitespace_err").css('display').toLowerCase() == 'none' && $(".categoryExist").css('display').toLowerCase() == 'none')
          {
                  //console.log("innn");

                  var data = $("#faqForm").serialize();


                  data = data + "&LoginAction=Login";
                  jQuery.ajax({
                   dataType:"json",
                   type:"post",
                   data:data,
                   url:'<?php echo EXEC; ?>Exec_Faqs.php',
                   success: function(data)
                   {  
                    if(data.resonse)
                    {

                      $("#resonse").show().fadeOut(3000);
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                      $('#faqForm').trigger("reset");
                      $('#faqForm').each(function(){
                        this.reset();
                      });
                      $(".Loader").hide();
                      $(".whitespace_err").hide();
                      $(".categoryExist").hide();


                      $('#myModal').modal('hide');
                      dataTable()
                    }
                    else if(data.error)
                    {
                      $("#error").show().fadeOut(3000);;
                      $('#errormsg').html('<span>'+data.error+'</span>');
                      $(".Loader").hide();
                                  // alert('<li>'+data.error+'</li>');
                                }
                              }
                            });
                }

              }           
            });

        function dataTable()
        {
          $('#myTable').DataTable({
            "responsive": true,
            "processing" : true,
            "order":[[ 0,"desc" ]],
            "destroy": true,
            "ajax" : {
              "url" : "<?php echo EXEC; ?>Exec_FaqsList.php",
              "dataSrc" : ''
            },
            "autoWidth": false,
            "columnDefs": [
            {"className" : 'text-center', "targets" : '_all'},
            { "width": "6%", "targets": 0 },
            { "width": "15%", "targets": 1 },
            { "width": "32%", "targets": 2 },
            { "width": "10%", "targets": 3 }

            ],
            "columns" : [{
              "data" : {faqCategory:"faqCategory"},
              "render":function(data,type,row){
                if(data.faqCategory == null)
                {
                  return "Not-define";
                }
                else
                {
                  return data.faqCategory;
                }
              }
            },{
              "data" : "faqTitle",
            }, {
              "data" : {faqDesc:"faqDesc"},
              "render":function (data,type,row)
              {

                var len =  data.faqDesc.length;
                
                if(len >= 100)
                {
                  return data.faqDesc.substring(0,50)+" [...]";
                }
                else
                {
                  return data.faqDesc;
                }
              }
            },{
              "data": "id",
              "render": function(data, type, row) {
                var encodedId = window.btoa(data);
                return '<button class="btn btn-info btn-sm delete" id="editButton"  title="Edit Data" data-id="'+ encodedId +'"><span class="fa fa-edit "><span></button> <button class="btn btn-danger btn-sm delete" id="deleteButton" title="Delete Data" data-id="'+ encodedId +'"><span class="fa fa-trash"><span></button> <button class="btn btn-success btn-sm" style="border-color:#4cae4c;background-color:#5cb85c;color:white;" title="View FAQ" id="viewfaq" data-id='+ encodedId + '>' + '<span class="fa fa-eye"><span>' + '</button>';
              }
            }]
          });
        }


        $(document).on('click','#deleteButton',function(e){
         e.preventDefault();
         var dlink = $(this).attr('data-id');

         swal({
          title: "Are you sure?",
          text: "Once deleted, you will lost  this FAQ!",
          icon: "warning",
          buttons: true,
        }).then((willDelete)=>{   
          if (willDelete)
          {

            jQuery.ajax({
              dataType:"json",
              type:"post",
              data:{dlink:dlink},
              url:'?deleted=Faq',
              success: function(data)
              {
                if(data.resonse)
                {
                  swal(data.resonse)
                  dataTable();
                }
                else if(data.error)
                {
                  swal('Something wrong please try agine');
                  dataTable();
                }
              }
            });
          }
          else
          {
           return false ;
         }
       });
      });



        $(document).on('click','#editButton',function(e){
         e.preventDefault();
         $('#id').val('');
         var elink = $(this).attr('data-id');

         jQuery.ajax({
           dataType:"json",
           type:"post",
           data:{elink:elink},
           url:'?edit=Faq',
           success: function(data)
           {
            if(data.resonse)
            {
                           //console.log(data.resonse);
                           $('#myModal').modal('show');
                           $('#faqTitle').val(data.resonse.faqTitle);
                            //$('.js-example-basic-single').val(data.resonse.faqCategory);

                            $('.js-example-basic-single').html("");

                            var catList = '<?php 
                            $catstmt=$db->prepare("SELECT DISTINCT faqCategory FROM `faqs`");

                            $catstmt->execute();

                            $result = $catstmt->fetchAll();


                            echo json_encode($result);
                            ?>';

                              //console.log(catList);      
                              var obj = JSON.parse(catList);



                              var i;


                              for (i = 0; i < obj.length; i++) {

                                if(obj[i].faqCategory != null)
                                {
                                  var sel ='';
                                  if(data.resonse.faqCategory == obj[i].faqCategory)
                                  {
                                    sel = 'selected="selected"';
                                  }

                                  $('.js-example-basic-single').append('<option '+ sel +'  value="'+obj[i].faqCategory+'">'+obj[i].faqCategory+'</option>');
                                }

                              }
                              $('.newCatInput').val(data.resonse.faqCategory);




                            // $('#faqDesc').data("wysihtml5").editor.setValue(data.resonse.faqDesc);
                            $('#faqDesc').val(data.resonse.faqDesc);
                            $('#id').val(data.resonse.id);
                            $(".Loader").hide();
                            $("#myModal").modal('hide');
                            dataTable()

                          }
                          else if(data.error)
                          {
                            swal('Something wrong please try again')
                            dataTable()
                          }
                        }
                      });

       });
        /*view faq*/
        $(document).on('click','#viewfaq',function(e){
         e.preventDefault();
         $('#id').val('');
         var elink = $(this).attr('data-id');

         jQuery.ajax({
           dataType:"json",
           type:"post",
           data:{elink:elink},
           url:'?edit=Faq',
           success: function(data)
           {
            if(data.resonse)
            {
              $('#faqmodal').modal('show');
              if(data.resonse.faqCategory == null)
              {

                $('#vcategory').html("Not Define");

              }
              else
              {
                $('#vcategory').html(data.resonse.faqCategory);

              }
              $('#vtitle').html(data.resonse.faqTitle);
              $('#vdesc').html(data.resonse.faqDesc);
            }
            else if(data.error)
            {
              swal('Something wrong please try again')
              dataTable()
            }
          }
        });

       });
        /*view faq*/
      });
    </script>


    <!-- <script src="<?php echo base_url; ?>/js/select2.min.js"></script>  -->

    <script>
      $(document).ready(function() {





        $(".newCatInput").hide();
        $(".newCancel").hide();
            //$(".js-example-basic-single").show();
            $('.js-example-basic-single').select2();

            $(".newCat").click(function(){
              $(".newCatInput").show();
              $(".selCat").hide();
              $(".js-example-basic-single").val([]);
              $(".newCancel").show();
              $(".newCat").hide();

            //$("#select2-faqCategory-container").hide();
          })

            $(".newCancel").click(function(){
              $(".newCatInput").hide();
              $(".selCat").show();
              $(".newCancel").hide();
              $(".newCat").show();
              $(".newCatInput").val('');

              $(".whitespace_err").hide();
              $(".categoryExist").hide();
              getCategory();
            //$("#select2-faqCategory-container").hide();
          })

          });
        </script>
        <script>
          function getCategory()
          {
            jQuery.ajax({
              dataType:"json",
              type:"post",
              url:'<?php echo EXEC; ?>Exec_FaqsList.php',
              success: function(data)
              {
                $('.js-example-basic-single').html("");


                var uniqueNames = [];
                var j,k;
                for(j = 0; j< data.length; j++){    
                  if(uniqueNames.indexOf(data[j].faqCategory) === -1){
                    uniqueNames.push(data[j].faqCategory);        
                  }        
                }

                for(k = 0; k< uniqueNames.length; k++){   

                  if(uniqueNames[k] != null)
                  { 
                    $('.js-example-basic-single').append('<option value="'+uniqueNames[k]+'">'+uniqueNames[k]+'</option>');

                  }
                }


                $(".Loader").hide();

              }
            });

          }

          $("#addFaqsaddFaqsaddFaqs").click(function(){
            //alert("The paragraph was clicked.");
            $("#id").val("new");
            $("#myModal").modal({
              backdrop: 'static',
              keyboard: false
            });


            

            jQuery.ajax({
              dataType:"json",
              type:"post",
              url:'<?php echo EXEC; ?>Exec_FaqsList.php',
              beforeSend: function() {
                $(".Loader").show();
                
              },
              success: function(data)
              {
                $('.js-example-basic-single').html("");

                $(".Loader").hide();
                var uniqueNames = [];
                var j,k;
                for(j = 0; j< data.length; j++){    
                  if(uniqueNames.indexOf(data[j].faqCategory) === -1){
                    uniqueNames.push(data[j].faqCategory);        
                  }        
                }

                for(k = 0; k< uniqueNames.length; k++){   

                  if(uniqueNames[k] != null)
                  { 
                    $('.js-example-basic-single').append('<option value="'+uniqueNames[k]+'">'+uniqueNames[k]+'</option>');

                  }
                }


                        // var i;


                        // for (i = 0; i < data.length; i++) {

                        //   if(data[i].faqCategory != null)
                        //   {
                        //         $('.js-example-basic-single').append('<option value="'+data[i].faqCategory+'">'+data[i].faqCategory+'</option>');
                        //     }

                        // }


                        

                      }
                    });



          });
        </script>
      </body>
      </html>