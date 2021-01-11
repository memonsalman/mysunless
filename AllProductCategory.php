<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
            if(isset($_SESSION['UserID']))
            {
           $id=$_SESSION['UserID'];
           $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
           $result = $stmt->fetch(PDO::FETCH_ASSOC);
           @$sercreateprmistion=$result['ServicesCreate'];
        }
// if($sercreateprmistion==0){
     // header("Location: index.php");die;  
 // }    
if(isset($_GET["id"])){
    $mycategory = $_GET["id"];
}else{
    $mycategory = "new";
}
if(isset($_REQUEST['delid']))
{
    $id= base64_decode($_POST['delid']);
        $isactive=0;
    $date = date('Y-m-d h:i:s');

    $DeleteClient = $db->prepare("update `ProductCategory` set isactive=:isactive, datelastupdated=:date where id=:id");
    $DeleteClient->bindValue(":isactive",$isactive,PDO::PARAM_INT);
    $DeleteClient->bindValue(":id",$id,PDO::PARAM_INT);
    $DeleteClient->bindValue(":date",$date);

    $deletefile=$DeleteClient->execute();
    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Product Category Successfully Remove From List']);die;
    }
    else
    {
        echo  json_encode(["error"=>'done']);die;
    }
}

if(isset($_POST['editid']))
{
    $mycategory= base64_decode($_POST['editid']);
    $SSelectCat=$db->prepare("select * from ProductCategory where id=:mycategory");
    $SSelectCat->bindValue(':mycategory',$mycategory,PDO::PARAM_INT);
    $SSelectCat->execute();
    $select=$SSelectCat->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>$select]);die;
}



    $button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C26'"); 
    $button5->execute();
    $all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
    $B5=$all_button5['button_name'];

    $button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C27'"); 
    $button6->execute();
    $all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
    $B6=$all_button6['button_name'];


    $button7= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C28'"); 
    $button7->execute();
    $all_button7 = $button7->fetch(PDO::FETCH_ASSOC);
    $B7=$all_button7['button_name'];


    $button8= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C29'"); 
    $button8->execute();
    $all_button8 = $button8->fetch(PDO::FETCH_ASSOC);
    $B8=$all_button8['button_name'];

$title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='7'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
    <link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    
    <style>
/*.modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal {overflow: hidden;}
body.loading .modal {display: block;}*/
.eventStart1{width: 45%; float: left; padding: 0 10px;}
.timeinput{width: 35%; float: left; padding: 0 15px;}
.dataTables_length select{
            padding-bottom: 0px !important;
}
th{ font-weight: bold!important;color: #0b59a2!important}
</style>
<body class="skin-default fixed-layout mysunlessE">
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
                            <?php
                            if(isset($_GET["id"]))
                            {
                            ?>
                               <h4 class="text-themecolor"><?php echo $Ti2; ?></h4>
                            <?php       
                                }
                            else
                            {
                                ?>
                                <h4 class="text-themecolor"><?php echo $Ti2; ?></h4> 
                                <?php
                            }
                             ?>
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
                                <!-- Tab panes  -->
                                <div class="tab-content tabcontent-border">
                                    <div class="tab-pane active" id="home" role="tabpanel">
                                        <div class="col-lg-12">
                       <button id="addUser" class="btn btn-info m-r-10 cutommoibb" data-toggle="modal" data-target="#myModal_addcat"><?php echo $B5; ?></button>
                       <a href="<?php echo base_url; ?>/Product"  class="btn btn-info m-r-10 cutommoibb"><?php echo $B6; ?></a>
                                            <div class="table-responsive m-t-40">
                                      <table id="myTable2" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>No. Product</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <div class="Loader"></div>
                                            </div>

                                    <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
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
                       <!--      <div class="col-lg-12 col-md-12">
                                <div class="alert alert-success" id="resonse" style="display: none;">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                                </div>
                                <div class="alert alert-danger" id="error" style="display: none;">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="myModal_addcat" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Product Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="Loader"></div>
            
                      <form class="form-horizontal" autocomplete="off" id="NewCategory" method="post">
                                                    <input type="hidden" name="id" id="id" value="">
                                                    <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                    <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                                    <div class="form-group">
                                                        <label><span class="help">Category Name  *</span></label>
                         <input type="text" name="Category" id="Category" value="" class="form-control" maxlength="50">
                                                    </div> 
                                                    <div class="Loader"></div>
                                                      <div class="form-group">
                                                            <?php
                                                            if(isset($_GET["id"]))
                                                            {
                                                            ?>
   <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Update Category</button>
                                                            <?php       
                                                                }
                                                            else
                                                            {
                                                                ?>
 <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> <?php echo $B7; ?></button>
                                                                <?php
                                                            }
                                                             ?>
 <!-- <a href="<?php echo base_url; ?>/AllProductCategory.php" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> <?php echo $B8; ?></a> -->
                                                    </div>
                                            </form>
                     


        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
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
    
<script>
$(document).ready(function() {
    dataTable()
    function dataTable()
    {
        $('#myTable2').DataTable({
            "responsive": true,
            "processing": true,
             "destroy": true,
            "ajax" : {
                "url" : "<?php echo EXEC; ?>Exec_Edit_ProductCategory.php?viewdata",
               "dataSrc":'',
            },
            "autoWidth": false,
            "columnDefs": [
              {"className" : 'text-center', "targets" : '_all'},
            ],
            "columns" : [{
                "data": "Category",                
            },
            {
                "data": "pcount",                
            },
            {
                "data": "id",
                "render": function(data, type, row) {
                    var encodedId = window.btoa(data);
                    return '<button class="btn btn-info btn-sm" id="EditButton" title="Edit Event" data-id="'+ encodedId + '"> <span class="fa fa-edit"><span> </button> <button id="deleteButton" title="Delete" class="btn btn-danger btn-sm" data-id="' + encodedId + '"><span class="fa fa-trash"></span></button>' ;
                }
            }]
        });
    }
    $(document).on('click','#deleteButton',function(e){
       e.preventDefault();
       
        swal({
                 title: "Temporary Delete?",
                text: "Once deleted, it will move to Archive list!",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                          var delid=$(this).attr('data-id');
                          $(".Loader").show();
                          $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'delid':delid},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                            
                                            swal(data.resonse)
                                            $(".Loader").hide();
                                            dataTable()
                                        }
                                        else if(data.error){
                                            swal('',data.error,'error');
                                            $(".Loader").hide();
                                           
                                        }
                                    }
                          });
                }
                else{
                     return false ;
                }
            });
    });

$(document).on('click','#addUser',function(e){
    $("#Category").val('');
    $("#id").val('new');
        });   

    $(document).on('click','#EditButton',function(e){
        $("#id").val('');
        var editid=$(this).attr('data-id');

          $.ajax({
                    dataType:"json",
                    type:"post",
                    data:{'editid':editid},
                    url:'?action=deletefile',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                        $(".Loader").hide();
                        $("#Category").val(data.resonse.Category);
                        $("#id").val(data.resonse.id);
                        $("#myModal_addcat").modal('show');
                        }
                        else if(data.error)
                        {
                         swal('',data.error,'error');
                        $(".Loader").hide();
                        }
                    }
                    });


    });        

      $("#NewCategory").validate({
            rules: {                
                Category: {

                    required: true,
                    maxlength: 50,
                    remote: {
                        url: "<?php echo EXEC; ?>Exec_Edit_ProductCategory.php",
                        type: "post",
                        data: {
                          categoryChk: function() {
                            return $("#Category").val();
                          },
                          categoryid:function(){
                            return $("#NewCategory #id").val();
                          }
                        }
                      }
                }
            },
            messages: {             
                Category: {required: "Please enter category"},

               },
                submitHandler: function() {
                $(".Loader").show();
            var data = $("#NewCategory").serialize();
             data= data + "&Action=Category";

               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_Edit_ProductCategory.php',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                              swal('',data.resonse,'success');
                            $( '#NewCategory' ).each(function(){
                                 this.reset();
                                 });
                            $(".Loader").hide();
                            $("#myModal_addcat").modal('hide');
                            
                           
                            dataTable()
                        }
                        else if(data.error)
                        {
                           swal('',data.error,'error');
                              $("#myModal_addcat").modal('hide');
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