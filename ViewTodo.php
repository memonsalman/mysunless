<?php 
require_once('global.php');
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
$ID = 'ID';
if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$todocreateprmistion=$result['TodoCreate'];
}
$total_todo = $db->prepare("SELECT * FROM `todo` WHERE `createdfk`=:id");
$total_todo->bindParam(':id', $id, PDO::PARAM_INT);
$total_todo->execute();
$number_of_todo = $total_todo->rowCount();
if($todocreateprmistion==0)
{
    header("Location: ../index.php");die;  
}   

if(isset($_GET['EditViewTodo'])) 
{
    
$myevent = base64_decode($_POST["id"]) ;
$total_todo = $db->prepare("SELECT * FROM `todo` WHERE `id`=:myevent");
$total_todo->bindParam(':myevent', $myevent, PDO::PARAM_INT);
$total_todo->execute();
$GetEvent=$total_todo->fetch(PDO::FETCH_ASSOC);

echo json_encode(['resonse'=>$GetEvent]);die; 
}
if(isset($_GET['ViewTodo'])) 
{    
    $myevent = base64_decode($_POST["id"]) ;
    $total_todo = $db->prepare("SELECT * FROM `todo` WHERE `id`=:myevent");
    $total_todo->bindParam(':myevent', $myevent, PDO::PARAM_INT);
    $total_todo->execute();
    $GetEvent=$total_todo->fetch(PDO::FETCH_ASSOC);
echo json_encode(['resonse'=>$GetEvent]);die; 
}
  if(isset($_GET['DelteViewTodo']))
  {

    $myevent = base64_decode($_POST["id"]) ;
    $DeleteClient = $db->prepare("delete from `todo` where id=:myevent");
    $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
    $DeleteClient->execute();
    echo json_encode(['resonse'=>'To-Do has been successfully deleted']);die; 
  }

  $button1= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C59'"); 
    $button1->execute();
    $all_button1 = $button1->fetch(PDO::FETCH_ASSOC);
    $B1=$all_button1['button_name'];

    $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C60'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C61'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];

$title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='15'"); 
    $title1->execute();
    $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
    $Ti1=$all_title1['TitleName'];  

    
    
                                        
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <style type="text/css">
        .custbutton{margin: 5px 0;}
        th { font-weight: bold!important;color:#0b59a2!important;}
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
                                <?php echo $Ti1; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                            <a href="#" id="newnote" class="btn btn-info m-r-10 " data-toggle="modal" data-target="#myModal"> <?php echo $B1; ?></a>
                                        <div class="table-responsive m-t-40 col-md-12">
                                      <table id="TodoTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Description</th>
                                                        <th>Due date</th>
                                                        <th>Action</th>
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
        <h4 class="modal-title">To-Do</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
           <form class="form-horizontal " autocomplete="off" id="NewEvent" method="post">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                        <div class="Loader"></div>
                                     <input type="hidden" name="id" id="id" value="">
                                        <div class="form-group">
                                            <label for="todoTitle">
                                                <h3>Title</h3>
                                            </label>
                                            <br>
 <input type="text" name= "todoTitle" id="todoTitle" placeholder="Enter Task Title...." class="form-control" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="todoDesc">
                                                <h3>Description</h3>
                                            </label>
                                            <br>
<textarea class="textarea_editor form-control" rows="10" placeholder="Enter Task Description..." id="todoDesc" name="todoDesc"></textarea>
                                        </div>

                                        <div class="form-group" id= "datetimepicker">
                                            <label for="dueDate">
                                                <h3>Due Date</h3>
                                            </label>
                                            <br>

                                            <select name= "dueDate" class="form-control"  id="dueDate">

                                                <option value=""> Select due date </option>

                                                <?php

                                            $curretdate = date("Y-m-d");
                                            $curretdate = strtotime($curretdate);

                                            for ($x = 1; $x <= 30; $x++) 
                                            {
                                               $curretdate = strtotime('1 day', $curretdate);
                                               $newduedate=  date('Y-m-d', $curretdate);
                                               echo "<option value='$newduedate'>$newduedate - $x days</option>";
                                            
                                            }    
    
                                            ?>

                                            </select>
 <!-- <input type="text" name= "dueDate" placeholder=" Select Due Date...." class="form-control"  id="dueDate" readonly value="<?php echo $dueDate; ?>"> -->
                                        </div>
                                        <div class="form-group">
                                            <?php
if(isset($_GET["id"]))
{
                                            ?>
        <button type="submit" name="todoSub" id="todoSub" class="btn btn-info m-r-10"><i class="fa fa-check"></i> Update To-Do </button>
                                            <?php       
}
else
{
                                            ?>
         <button type="submit" name="todoSub" id="todoSub" class="btn btn-info m-r-10"><i class="fa fa-check"></i> <?php echo $B2; ?></button>
                                            <?php
}
                                            ?>
            <!-- <a href="<?php echo base_url; ?>/ViewTodo.php" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> <?php echo $B3; ?></a> -->
                                        </div>
                                    </form>

                                           <div class="col-lg-12 col-md-12">
                                    <div class="alert alert-success" id="resonse" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-success">
                                            <i class="fa fa-check-circle">
                                            </i>
                                            Success
                                        </h3>
                                        <p id="resonsemsg">
                                        </p>
                                    </div>
                                    <div class="alert alert-danger" id="error" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-danger">
                                            <i class="fa fa-exclamation-circle">
                                            </i>
                                            Errors
                                        </h3>
                                        <p id="errormsg">
                                        </p>
                                    </div>

                                    <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>
                                </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>
  </div>



<!--   view data modal -->
 <div class="modal fade" id="viewmodal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">To-Do Detail</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
           <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                        <div class="Loader"></div>
                                     <input type="hidden" name="vid" id="vid" value="">
                                        <div style="margin-bottom: inherit;" class="form-group">
                                            <label for="todoTitle">
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
                                        <div  style="margin-bottom: inherit;" class="form-group" id= "datetimepicker">
                                            <label for="dueDate">
                                                <h3>Due Date</h3>
                                                <p style="font-size: 16px;" id="vddate" ></p>
                                            </label>
                                            <br>
                                        </div>
                                    </form>
                                    <div class="col-lg-12 col-md-12">
                                    <div class="alert alert-success" id="resonse" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-success">
                                            <i class="fa fa-check-circle">
                                            </i>
                                            Success
                                        </h3>
                                        <p id="resonsemsg">
                                        </p>
                                    </div>
                                    <div class="alert alert-danger" id="error" style="display: none;">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-danger">
                                            <i class="fa fa-exclamation-circle">
                                            </i>
                                            Errors
                                        </h3>
                                        <p id="errormsg">
                                        </p>
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
<!-- view data modal -->
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
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
          <script src="../<?= $SUB?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {
                //active class
                setInterval(function(){$(".todo").addClass("active");}, 10);

                dataTable();
    function dataTable()
    {
                $('#TodoTable').DataTable({
                "responsive": true,
                "processing": true,
                "destroy": true,

                    "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_ViewTodo.php",
                        "dataSrc" : ''
                    }
                    ,
                    "autoWidth": false,
                    "columnDefs": [
                        {
                            "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "10%", "targets": 0 }
                        ,
                        {
                            "width": "16%", "targets": 1 }
                        ,
                        {
                            "width": "10%", "targets": 2 }
                        ,
                        {
                            "width": "10%", "targets": 3 }
                        ,
                    ],
                        "columns" : [ 
                        {
                        "data" : "todoTitle"
                        }
                        , 
                        {
                            "data" : {todoDesc:"todoDesc"},
                            "render":function (data,type,row)
                            {
                              var len = data.todoDesc.length;
                              if(len >= 100)
                              {
                                return data.todoDesc.substring(0,50)+" [...]";
                              }
                              else
                              {
                                return data.todoDesc;
                              }
                            }
                        }
                        , 
                        {
                            "data" : {newduedate:"newduedate"},
                            "render":function (data,type,row)
                            {
                              return moment(data.newduedate).format('MM-DD-YYYY');  
                            }
                        }, 
                        {
                            "data": "id",
                            "render": function(data, type, row) {
                            var encodedId = window.btoa(data);
                            return ' <button class="btn btn-info btn-sm edit_data custbutton" title="Edit Event" data-id='+ encodedId +'>' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-danger btn-sm custbutton" title="Delete Event" id="deleteButton" data-did='+ encodedId + '>' + '<span class="fa fa-trash"><span>' + '</button> <button class="btn btn-success btn-sm viewdata"style="border-color:#4cae4c;background-color:#5cb85c;color:white;" title="View event" data-id='+ encodedId +'>' + '<span class="fa fa-eye"><span>' + '</button>';
                            }
                        }
                    ]
                });
            }
                $(document).on('click','#newnote',function(){
                $('#NewEvent')[0].reset();
                $('#id').val('new');
      

   });

               $("#NewEvent").validate({
                    ignore: ":hidden:not(textarea)",
                    rules: {
                        todoTitle: {
                            required: true,}
                        ,
                        todoDesc: {
                            required: true,}
                        ,
                        dueDate: {
                            required: true,}
                    }
                    ,
                    messages: {
                        todoTitle: {
                            required: "Please enter title"}
                        ,
                        todoDesc: {
                            required: "Please enter description"}
                        ,
                        dueDate: {
                            required: "Please select date"}
                        ,
                    }
                    ,
                    errorPlacement: function( label, element ) {
                        if( element.attr( "name" ) === "todoDesc" ) {
                            element.parent().append( label );
                        }
                        else {
                            label.insertAfter( element );
                        }
                    }
                    ,
                    submitHandler: function() {
                        $(".Loader").show();
                        var data = $("#NewEvent").serialize();
                        data= data + "&LoginAction=Login";
                        jQuery.ajax({
                            dataType:"json",
                            type:"post",
                            data:data,
                            url:'<?php echo EXEC; ?>ExecTodo.php',
                            success: function(data)
                            {
                                if(data.resonse)
                                {
                                    $("#resonse").show();
                                    $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                    $( '#NewEvent' ).each(function(){
                                        this.reset();
                                    }
                                                         );
                                    $(".Loader").hide();
                                    $("#myModal").modal('hide');
                                       dataTable()
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

                $(document).on('click', '.viewdata', function(){
                    var id = $(this).data("id")
                    $.ajax({
                    url:"?ViewTodo",  
                    method:"POST",  
                    data:{ id:id},  
                    dataType:"json",  
                    success:function(data){
                        console.log(data);
                        $('#vtitle').html(data.resonse.todoTitle);
                        //alert(todo);
                        $('#vdesc').html(data.resonse.todoDesc);
                        $('#vddate').html(moment(data.resonse.dueDate).format('MM-DD-YYYY'));
                        $('#vid').val(data.resonse.id);
                        $('#viewmodal').modal('show');
                    }
                });
            });
    




                      $(document).on('click', '.edit_data', function(){
                var id = $(this).data("id")
                
                $.ajax({
                    url:"?EditViewTodo",  
                    method:"POST",  
                    data:{ id:id},  
                    dataType:"json",  
                    success:function(data){

                        var todo= $('#todoTitle').val(data.resonse.todoTitle);
                        //alert(todo);
                        $('#todoDesc').val(data.resonse.todoDesc);
                        $('#dueDate').val(data.resonse.dueDate);
                        $('#id').val(data.resonse.id);
                        $('#myModal').modal('show');
                    }
                });
            });

             $(document).on('click','#deleteButton',function(e){
                e.preventDefault();
                var id = $(this).data("did");
                console.log(id);
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will lost this To-Do",
                    icon: "warning",
                    buttons: true,
                }
                    ).then((willDelete)=>{
                    if (willDelete){
                    
                         $.ajax({
                    url:"?DelteViewTodo",  
                    method:"POST",  
                    data:{ id:id},  
                    dataType:"json",  
                    success:function(data)
                    {
                        swal(data.resonse)
                        dataTable()
                    }
                });

                }
                else{
                 return false ;
                 }
                 });
            });             

            });
        </script>
<!--         <script type="text/javascript">
            $(document).on('click', '.edit_data', function(){
                var id = $(this).attr("id");
                $.ajax({
                    url:"<?php// echo EXEC; ?>Exec_ViewTodo.php",  
                    method:"POST",  
                    data:{
                        id:id}
                    ,  
                    dataType:"json",  
                    success:function(data){
                        var todo= $('#todoTitle').val(data.todoTitle);
                        //alert(todo);
                        $('#todoDesc').val(data.todoDesc);
                        $('#dueDate').val(data.dueDate);
                        $('#myModal').modal('show');
                    }
                });
            });

            $(document).on('click','#deleteButton',function(e){
                e.preventDefault();
                var link = $(this).attr('href');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will lost this Todo!",
                    icon: "warning",
                    buttons: true,
                }
                    ).then((willDelete)=>{
                    if (willDelete){
                    window.location.href = link;
                }
                else{
                 return false ;
                 }
                 });
            });
</script> -->
</body>
</html>