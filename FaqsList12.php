<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
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
<style type="text/css">
    .delete{margin: 3px;color: white;}
    th{ font-weight: bold!important;color: #0b59a2!important;  }
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
                                                                            <th class="tableTitle">Question</th>
                                                                             <th class="tableDesc">Answer</th>
                                                                             <th class="tableAction">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            
                                                        </div>

                                                         <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add FAQ</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
            
                  <form method="post" id="faqForm" autocomplete="off" class="form-horizontal">
                        <input type="hidden" name="id" id="id" value="new">
                            <div class="form-group">
                                    <label><h2> Question </h2></label><br>
   <input type="text" name= "faqTitle" id="faqTitle" placeholder="Enter Title...." class="form-control" value="" >
                            </div>
                            <div class="form-group">
                                <label><h2> Answer </h2></label><br>
    <textarea class="textarea_editor form-control" rows="10" placeholder="Enter text ..." name="faqDesc" id="faqDesc" >  </textarea>
                            </div>
                            <div class="form-group">
                                <?php if(isset($_GET["id"])) { ?>
         <button type="submit" name="faqSub" id="faqSub" class="btn btn-info"><i class="fa fa-check"></i> Update FAQ</button>
                                <?php  } else { ?>
  <button type="submit" name="faqSub" id="faqSub" class="btn btn-info"><i class="fa fa-check"></i> Add FAQ</button>
                                <?php } ?>
                            </div>
                            <div class="loaderModal"></div>
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
    <script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>

    <script>
        $(document).ready(function() {
             $('.textarea_editor').wysihtml5();
        });
    </script>
<script src="<?php echo base_url; ?>/assets/js/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        //active class
        setInterval(function(){$(".allfaq").addClass("active");}, 10);
            dataTable()

              $("#faqForm").validate({
            ignore: ":hidden:not(textarea)",
            rules: {                
                faqTitle: {required: true,},
                faqDesc: {required: true,}
            },
            messages: {    
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
                $(".loaderModal").show();
            var data = $("#faqForm").serialize();
             data= data + "&LoginAction=Login";
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_Faqs.php',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                            //console.log("model showwwwwwwww");
                            $("#resonse").show();
                            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                            $( '#faqForm' ).each(function(){
                                this.reset();
                                });
                            $(".loaderModal").hide();
                            $('#myModal').modal('hide');
                            dataTable()
                        }
                        else if(data.error)
                        {
                            $("#error").show();
                            $('#errormsg').html('<span>'+data.error+'</span>');
                            $(".loaderModal").hide();
                        // alert('<li>'+data.error+'</li>');
                        }
                    }
                    });
                }           
        });
            
            function dataTable()
    {
            $('#myTable').DataTable({
                "responsive": true,
                "processing" : true,
                "destroy": true,
                "ajax" : {
                    "url" : "<?php echo EXEC; ?>Exec_FaqsList.php",
                    "dataSrc" : ''
                },
                "autoWidth": false,
                "columnDefs": [
                  {"className" : 'text-center', "targets" : '_all'},
                  { "width": "19%", "targets": 0 },
                  { "width": "32%", "targets": 1 },
                  { "width": "10%", "targets": 2 },
                ],
                "columns" : [{
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
                           
                            $('#myModal').modal('show');
                            $('#faqTitle').val(data.resonse.faqTitle);
                            $('#faqDesc').data("wysihtml5").editor.setValue(data.resonse.faqDesc);
                            $('#id').val(data.resonse.id);

                            //console.log("success");
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


 <script>
          $("#addFaqsaddFaqsaddFaqs").click(function(){
            //alert("The paragraph was clicked.");
            $("#id").val("new");

            });
        </script>
</body>
</html>