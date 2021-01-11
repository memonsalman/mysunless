<?php 


  require_once('global.php');
 
    require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

    
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
    header("Location: trelo.php");die;
    // header("Location: dashboard.php");die;
}

if(isset($_GET['editdata']))
{
    $id = base64_decode($_POST['id']);
    $EditEvent=$db->prepare("SELECT * from `Post` where id=:id");
    $EditEvent->bindValue(":id",$id, PDO::PARAM_INT);
    $EditEvent->execute();  
    $GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
    if($GetEvent)
    {
         echo  json_encode(["resonse"=>$GetEvent]);
         die;
    }
}
if(isset($_GET['viewdata']))
{
    $id = base64_decode($_POST['id']);
    $EditEvent=$db->prepare("SELECT * from `Post` where id=:id");
    $EditEvent->bindValue(":id",$id, PDO::PARAM_INT);
    $EditEvent->execute();  
    $GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
    if($GetEvent)
    {
        
        echo  json_encode(["resonse"=>$GetEvent]);die;
    }
}
if(isset($_GET['deleteddata']))
{
    $id = base64_decode($_POST['id']);
    $deletEvent=$db->prepare("DELETE from `Post` where id=:id");
    $deletEvent->bindValue(":id",$id, PDO::PARAM_INT);
    $deletEvent->execute();  
    if($deletEvent)
    {
     echo  json_encode(["resonse"=>"Post successfully deleted"]);die;   
    }
}
    
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';

    ?>
    <style type="text/css">
        .cubuform{margin: 3px;}
        th{ font-weight: bold!important;color: #0b59a2!important;}
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
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
                                Post
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                         <button type="button" class="btn btn-info m-r-10" id="addNewPost" data-toggle="modal" data-target="#myModal">Add Post</button>

                                        <div class="table-responsive m-t-40 col-md-12">
                                      <table id="TodoTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Title
                                                        </th>
                                                        <th>
                                                            Description
                                                        </th>
                                                        <th>
                                                            Post Date
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Post Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
          
          <form class="form-horizontal " autocomplete="off" id="NewPost" method="post">
            <div class="Loader"></div>
           <input type="hidden" name="id" id="id" value="new">
           <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
           <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
           
           <div class="form-group">
           <label><span class="help">Post Title  *</span></label>
           <input type="text" name="PostTitle" id="PostTitle" value="" class="form-control" maxlength="25">
           <div class="pull-right">Maintenance: <input type="checkbox" id="Check_Maintenance" ></div>
           </div> 
            
            <div class="form-group">
            <label><span class="help">Post Description  *</span></label>
            <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Post Detail ..." name="PostDec" id="PostDec" > </textarea>
            </div> 

            <div class="form-group d-flex">
            	<label>Post Date:</label>
            	<input type="text" name="Post_Date" class="Post_Date form-control  ml-2 col-md-4" placeholder="MM/DD/YYYY">
            	<input type="text" name="Post_Time" class="Post_Time form-control ml-2 col-md-6" placeholder="10:00am">
            </div>
            <small>Default Date/Time will be current Date/Time.</small>

            <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Save</button>
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

                    <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>
                </div>

        </div>
      </div>
      
    </div>
  </div>
  <!-- view post -->
  <div class="modal fade" id="viewmodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Post Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
          
          <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                        <div class="Loader"></div>
                                     <input type="hidden" name="vid" id="vid" value="">
                                        <div style="margin-bottom: inherit;" class="form-group">
                                            <label for="posttitle">
                                                <h3>Title</h3>
                                                <p style="font-size: 16px;"  id="vtitle" ></p>
                                            </label>
                                            <br>
                                        </div>
                                        <div  style="margin-bottom: inherit;" class="form-group">
                                            <label for="todoDesc">
                                                <h3>Description</h3>
                                                <p style="font-size: 16px;" id="vdesc" ></p>
                                            </label>
                                            <br>
                                        </div>
                                    </form>

        </div>
      </div>
      
    </div>
  </div>
  <!-- view post -->
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#PostDec").ckeditor();

                $(".Post_Time").timepicki();

                $(".Post_Date").datepicker({
                	autoclose: true,
			        startDate: '-0d',
                });

                var PostTitle = "";
                $("#Check_Maintenance").click(function(){
                	if($(this).is(":checked")){
                		PostTitle = $("#PostTitle").val();
                		$("#PostTitle").val("Maintenance");
                		$("#PostTitle").attr("readonly",true);
                	}else{
                		$("#PostTitle").val(PostTitle);
                		$("#PostTitle").attr("readonly",false);
                	}
                });

                //active class
                setInterval(function(){$(".allpost").addClass("active");}, 10);
                dataTable()
                function dataTable()
                {
                $('#TodoTable').DataTable({
                    "responsive": true,
                    "processing" : true,
                    "destroy": true,
                    "initComplete": function( settings, json) {
                    	$("#TodoTable_length").before('<button class="btn btn-sm btn-info dataTables_filter PostRefresh">Refresh</button>');
                    },
                    "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_Post.php?viewdata",
                        "dataSrc" : ''
                    }
                    ,
                    "autoWidth": false,
                    "columnDefs": [
                        {
                            "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "27%", "targets": 0 }
                        ,
                        {
                            "width": "33%", "targets": 1 }
                        ,
                        {
                            "width": "20%", "targets": 2 }
                        
                    ],
                        "columns" : [ 
                        {
                            "data" : "PostTitle"
                        },
                        {
                            "data" : {PostDec:"PostDec"},
                            "render" : function(data,type,row)
                            {
                                var len =  data.PostDec.length;
                                if(len >= 100)
                                {
                                    return data.PostDec.substring(0,50)+" [...]";
                                }
                                else
                                {
                                    return data.PostDec;
                                }
                            }
                        },
                        {
	                        "data" : "PostDate"	,
	                        "render": function(data,type,row){
	                        	if(data){
		                        	return  moment.utc(data).local().format("MM/DD/YYYY h:mma")
	                        	}else{
	                        		return '';
	                        	}
	                        }
                        }, 
                        {
                        "data": "id",
                        "render": function(data, type, row) {
                        var encodedId = window.btoa(data);
                        var button = '';
                        if(row.PostTitle=='Maintenance'){
			                if (row.Maintenance == 2){
			          			button =  '<input class="toggle_status6"  type="checkbox" checked data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="0">' ;
			          		}
			          		else{
			          			button =  '<input class="toggle_status6"  type="checkbox" data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="2">' ;
			          		}
                        }else{
                            button = '<button class="btn btn-danger btn-sm cubuform" title="Delete Event" id="deleteButton" data-id='+ encodedId + '>' + '<span class="fa fa-trash"><span>' + '</button>';
                        }

                        return '<button class="btn btn-info btn-sm cubuform" title="Edit Event" id="editbutton" data-id='+ encodedId + '>' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-success btn-sm" style="border-color:#4cae4c;background-color:#5cb85c;color:white;" title="View post" id="viewpost" data-id='+ encodedId + '>' + '<span class="fa fa-eye"><span>' + '</button>'+button;
                        }
                        }
                    ],
          "fnDrawCallback": function() {
          	jQuery('.toggle_status6').bootstrapToggle();
          	jQuery('.toggle_status6').parent().addClass('toggleBtn6');
          }
                });
            }
            
           	$(document).on('click','.PostRefresh',function(){

           		dataTable();
           	});

            $(document).on('click','.toggleBtn6',function(){
            	SetMaintenance = $(this).children(".toggle_status6").attr("value")
            	$.ajax({
            		url: "<?= base_url; ?>/Login_Check.php",
            		type: 'post',
            		dataType: 'json',
            		data: {
            			SetMaintenance:SetMaintenance
            		},
            		success: function(data){
            			if(data.response){
            				swal('',data.response,'success');
            				if(SetMaintenance!=0){
	            				$("#Maintenance").prop('checked',true);
            				}else{
	            				$("#Maintenance").prop('checked',false);
            				}
            			}else{
            				swal('',data.error,'error');
            			}
            			dataTable();
            		}
            	});
            });

            $("#NewPost").validate({
            rules: {                
                PostTitle: {required: true,maxlength:255},
                PostDec: {required: true,},
                
            },
            messages: {             
                PostTitle: {required: "Please enter post title"},
                PostDec: {required: "Please enter post detail"},
                
            },
            ignore: ":hidden:not(textarea)",
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "PostDec" ) 
                {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },
            submitHandler: function() {
                $(".Loader").show();
                var data = $("#NewPost").serialize();

                Post_Date = $(".Post_Date").val();
                Post_Time = $(".Post_Time").val();

                if(Post_Date==''){
                	Post_Date = moment().format("MM/DD/YYYY");
                }

                if(Post_Time==''){
                	Post_Time = moment().format("h:mma");
                }

                PostDate = Post_Date+' '+Post_Time;
                
               	// PostDate = moment(PostDate,"MM/DD/YYYY h:mma").format("YYYY-MM-DD H:mm:ss");
               	PostDate = moment(PostDate,"MM/DD/YYYY h:mma").utc().format("YYYY-MM-DD H:mm:ss");

               	

                data= data + "&Action=note&PostDate="+PostDate;
                jQuery.ajax({
                    dataType:"json",
                    type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_Post.php',
                    success: function(data)
                    {
                        if(data.resonse)
                        {
                        $("#resonse").show();
                        $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                        $( '#NewPost' ).each(function(){
                            this.reset();
                        });
                        $(".Loader").hide();
                        
                         $("#myModal").modal('hide');
                        dataTable()
                        }
                        else if(data.error)
                        {
                            $("#error").show();
                              $('#errormsg').html('<span>'+data.error+'</span>');
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

                $(document).on('click', '#editbutton', function(){
                $("Loader").show();
                $("#id").val('');
                var id = $(this).attr("data-id")
            
                $.ajax({
                    url:"?editdata",  
                    method:"POST",  
                    data:{id:id},  
                    dataType:"json",  
                    success:function(data){

                    	if(data.resonse.PostTitle=='Maintenance'){
                    		$("#Check_Maintenance").prop('checked',true);
                            $('#PostTitle').attr('readonly',true);
                    	}else{
                    		$("#Check_Maintenance").prop('checked',false);
                            $('#PostTitle').attr('readonly',false);
                    	}

                    	PostDate = moment.utc(data.resonse.PostDate).local().format("MM/DD/YYYY h:mma").split(' ');

                    	$(".Post_Date").val(PostDate[0]);
                    	$(".Post_Time").val(PostDate[1]);


                        $('#PostTitle').val(data.resonse.PostTitle);
                         $('#PostDec').val(data.resonse.PostDec);
                         $("#id").val(data.resonse.id);
                         $("Loader").hide();
                         $('#myModal').modal('show');
                        dataTable();
                    }
                });
            });
                $(document).on('click', '#viewpost', function(){
                $("Loader").show();
                $("#id").val('');
                var id = $(this).attr("data-id");
                $.ajax({
                    url:"?viewdata",  
                    method:"POST",  
                    data:{id:id},  
                    dataType:"json",  
                    success:function(data){
                        $('#vtitle').html(data.resonse.PostTitle);
                         $('#vdesc').html(data.resonse.PostDec);
                         /*$('.wysihtml5-sandbox').contents().find('.wysihtml5-editor').html(data.resonse.PostDec);*/
                         /*$("#id").val(data.resonse.id);*/
                         $("Loader").hide();
                         $('#viewmodal').modal('show');

                    }
                });
            });
            $(document).on('click','#deleteButton',function(e){
                e.preventDefault();
                $("Loader").show();
                var id = $(this).attr("data-id")

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will lost this Post!",
                    icon: "warning",
                    buttons: true,
                }).then((willDelete)=>
                {
                    if (willDelete)
                    {
                        $.ajax({
                                url:"?deleteddata",  
                                method:"POST",  
                                data:{id:id},  
                                dataType:"json",  
                                success:function(data)
                                {
                                    if(data.resonse)
                                    {
                                        swal("Post successfully deleted");
                                        $("Loader").hide();
                                        dataTable();
                                    }
                                    else if(data.error){
                                        swal('Something wrong please try agine');
                                        dataTable();
                                    }
                                }
                        });
                    }
                    else
                    {
                         return false;
                    }
                });
            });              

            });
        </script>

        <script>
          $("#addNewPost").click(function(){
            //alert("The paragraph was clicked.");
            $("#id").val("new");

            });
        </script>
        
</body>
</html>