<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}

if(isset($_SESSION['UserID']))
{
    $id=$_SESSION['UserID'];
    $stmt= $db->prepare("SELECT * FROM `MemberPackage` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$todocreateprmistion=$result['PackageCreate'];
}
$total = $db->prepare("SELECT * FROM `MemberPackage` WHERE `Name`=:id"); 
$total->bindParam(':id', $id, PDO::PARAM_INT);
$total->execute();
$number = $total->rowCount();

if(isset($_POST['delid']))
{   
    $myevent =base64_decode($_POST["delid"]);
    $date = date('Y-m-d h:i:s');

    $DeleteClient = $db->prepare("Update `MemberPackage` set isactive=0, datelastupdated=:date  where id=:myevent");
    $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
    $DeleteClient->bindValue(":date",$date);
    
    $deletefile=$DeleteClient->execute();
    
    if($deletefile)
    {
        echo  json_encode(["resonse"=>'Package successfully move to Archive list']);die;
    }
}

if(isset($_REQUEST['eidtid']))
{
    $myevent =base64_decode($_POST["eidtid"]);
    $editproducts=$db->prepare("select * from `MemberPackage` where id=:myevent");
    $editproducts->bindValue(":myevent",$myevent, PDO::PARAM_INT);
    $editproducts->execute();
    $editproducts=$editproducts->fetch(PDO::FETCH_ASSOC);
    
    
    if(!empty($editproducts))
    {
        echo  json_encode(["resonse"=>$editproducts]);die;
    }
    else
    {
        echo  json_encode(["error"=>'No Data found']);die;
    }
}
$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C39'"); 
    $button2->execute();
    $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
    $B2=$all_button2['button_name'];

    $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C40'"); 
    $button3->execute();
    $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
    $B3=$all_button3['button_name'];


    $button4= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C41'"); 
    $button4->execute();
    $all_button4 = $button4->fetch(PDO::FETCH_ASSOC);
    $B4=$all_button4['button_name'];

    $title2= $db->prepare("SELECT TitleName FROM `PageTitle` where id='13'"); 
    $title2->execute();
    $all_title2 = $title2->fetch(PDO::FETCH_ASSOC);
    $Ti2=$all_title2['TitleName'];
    
    $title3= $db->prepare("SELECT TitleName FROM `PageTitle` where id='14'"); 
    $title3->execute();
    $all_title3 = $title3->fetch(PDO::FETCH_ASSOC);
    $Ti3=$all_title3['TitleName'];  

    $id=$_SESSION['UserID'];
      $isactive=1;
      $Category2 = $db->prepare("select * from `Category` where isactive=:isactive AND createdfk=:id");
      $Category2->bindParam(':isactive', $isactive, PDO::PARAM_INT);
      $Category2->bindParam(':id', $id, PDO::PARAM_INT);
     $Category2->execute();
     $allCategory=$Category2->fetchAll();
    $id=$_SESSION['UserID'];
      $user = $db->prepare("select s.id, s.* from `Service` as s JOIN users AS u
        ON  s.createdfk=u.id  OR s.createdfk=u.adminid OR s.createdfk=u.sid
        WHERE u.id=:id or u.adminid=:id OR u.sid=:id GROUP BY s.id");
      $user->bindParam(':id', $id, PDO::PARAM_INT);
     $user->execute();
     $alluser=$user->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <style type="text/css">
        .cubutfom{margin: 3px;}

        li.select2-selection__choice {color: white !important;}

.select2-container--default .select2-selection--multiple .select2-selection__choice{background-color: #42bfd3!important; border:1px solid #43c1d4!important; }

span.select2.select2-container.select2-container--default.select2-container,span.select2.select2-container.select2-container--default.select2-container--focus{width: 100% !important;}

.bootstrap-touchspin .input-group-btn-vertical>.btn{padding: 9px !important;}
th { font-weight: bold!important;color:#0b59a2!important;}

    .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    list-style: none;
    white-space: break-spaces;
    max-width: 120px;
}
    </style>
      <link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

    
    <link href="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <body class="skin-default fixed-layout mysunlessK">

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
                                <?php echo $Ti3; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <button id="addUser" data-toggle="modal" data-target="#myModal_membshipackage" class="btn btn-info m-r-10 "><?php echo $B2; ?></button>
                                        <div class="table-responsive m-t-40 col-md-12">
                       <table id="MembershipPackageTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Name
                                                        </th>
                                                        <th>
                                                            Price
                                                        </th>
                                                        <th>
                                                            Package date
                                                        </th>
                                                        <th>
                                                            Description
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="Loader"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="myModal_membshipackage" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Package</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            
            <form class="form-horizontal " id="MembershipPackage" method="post">
                                <!--  echo $myevent;  -->
                                <input type="hidden" name="id" autocomplete="off" id="id" value="">
                                <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                            <div class="form-group">
                                    <label for="Name"> Package Name *</label><br>
                                    <div class="Loader"></div>
    <input type="text" name= "Name" id="Name" placeholder="Enter Package Name" class="form-control" value="" maxlength="30">
                            </div>
                            <!-- <div class="form-group">
                                    <label for="Price">Price *</label> <span class="help"></span><br>
                                    <input type="number" name= "Price" id="Price" placeholder="Enter Price" class="form-control Price" value="<?php echo $Price ; ?>">
                                    
                            </div>  -->
                            <div class="form-group">
                                   <label for="Price">Price * <span class="help"></span></label>
                                   <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
               <input type="text" id="Price" name="Price" class="form-control" maxlength="10" id="Price" placeholder="Enter Price" value="" style="width: 75%" >
                                    </div>
                            </div>
                             <div class="form-group">
                                <label for="Tracking">Package Expire date * </label>
                                <!-- <select name="Tracking" id="Tracking" class="form-control valid">
                                    <option value="">Select Package Expire date</option>
                                    <option value="Weekly"> Weekly </option>
                                    <option value="Bi-Weekly"> Bi-Weekly </option>
                                    <option value="Monthly"> Monthly </option>
                                    <option value="Yearly"> Yearly </option>
                                    <option value="No Expiration"> No Expiration </option>
                                </select> -->

    <input type="text" class="date start form-control" placeholder="Package Expire date" name="Tracking" autocomplete="nope" id="Tracking" value="No Expiration" aria-invalid="false">
    <span style="float: right;"><input type="checkbox" name="Tracking2" id="Tracking2"  value="No Expiration"> No Expiration   </span>
                        
                            </div> 


                            <div class="form-group">
                                <label for="Description"> Description *</label><br>
<textarea class="textarea_editor form-control" id="MPackageDescription" maxlength="255" rows="10" placeholder="Enter Task Description...." name="Description"></textarea>
                            </div>


                            <div class="form-group">
                                            <label><span class="help"> service  *</span></label>
                                <select class="select2 m-b-10 select2-multiple form-control" data-placeholder="Select service" id="Users" name="Users[]" multiple data-style="form-control btn-secondary">
                                            <option value="">select service</option>
                                                <?php
                                                foreach($alluser as $row)
                                                {
                                                    ?>
                                                      <option  value="<?php echo $row['id']; ?>"><?php echo $row['ServiceName']; ?></option>  
                                                    <?php
                                                
                                                }
                                                ?>
                                            </select>
                                        </div>  

                                    <div class="form-group">
                                    <label for="Name"> # of Visits *</label><br>
                                    <div class="Loader"></div>
                <input type="text" name= "Noofvisit" id="Noofvisit" placeholder="Enter number of Visits" class="form-control" value="" maxlength="10">
                <span style="float: right;"><input type="checkbox" name="Noofvisit2" id="Noofvisit2"  value="Unlimited"> Unlimited   </span>
                <span id="errmsg" style="color: red"></span>
                                    </div>                                        



                            <div class="form-group">
                                <?php
                                    if(isset($_GET["id"]))
                                    {
                                ?>
<button type="submit" name="MemberPackageSubmit" id="MemberPackageSubmit" class="btn btn-info m-r-10"><i class="fa fa-check"></i> Update Package</button>
                                <?php
                                    }
                                    else
                                    {
                                ?>
<button type="submit" name="MemberPackageSubmit" id="MemberPackageSubmit" class="btn btn-info m-r-10"><i class="fa fa-check"></i> <?php echo $B3; ?></button>
                                <?php
                                    }
                                ?>
<!-- <a href="<?php echo base_url; ?>/MembershipPackageList.php" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times"></i> <?php echo $B4; ?></a>  -->
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
        <!-- ==============================================================  -->


        <?php include 'scripts.php'; ?>
        <script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>   

<!-- wysuhtml5 Plugin JavaScript -->


    <script src="<?= base_url?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
    <script src="<?= base_url?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
    <script src="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.js"></script>
    <script src="<?= base_url?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
    <script src="<?= base_url?>/assets/node_modules/dff/dff.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?= base_url?>/assets/node_modules/multiselect/js/jquery.multi-select.js"></script>
    
    <script src="<?= base_url?>/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script>


         $('#Price').keypress(function(event) {
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
       ((event.which < 48 || event.which > 57) &&
       (event.which != 0 && event.which != 8))) {
           event.preventDefault();
    }

    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function() {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
    }      
});

$('#Price').bind("paste", function(e) {
var text = e.originalEvent.clipboardData.getData('Text');
if ($.isNumeric(text)) {
    if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
        e.preventDefault();
        $(this).val(text.substring(0, text.indexOf('.') + 3));
   }
}
else {
        e.preventDefault();
     }
});


            $(document).ready(function() {
                    //sidebar drop down
                    $("#companydrop").trigger("click");
                    //active class
                    setInterval(function(){$(".memlist").addClass("active");}, 10);
                   $('.date').datepicker({
        'format': 'yyyy-mm-dd',
        'autoclose': true
    });

                $("#Noofvisit").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   })

                // $(document).on('click','body *',function(){
                    
                //         if($('#Noofvisit').val()=='')
                //     {
                //      $('#Noofvisit').val('Unlimited')   
                //     }

                // });

                 $("#Noofvisit2").change(function() {
                 if(this.checked) {
                        $('#Noofvisit').val('Unlimited')
                        $("#Noofvisit").prop('disabled', true);
                    }
                    else
                    {
                     $('#Noofvisit').val('')
                     $("#Noofvisit").prop('disabled', false);
                    }
                });
                
                    $("#Tracking2").change(function() {
                 if(this.checked) {
                        $('#Tracking').val('No Expiration')
                        $("#Tracking").prop('disabled', true);
                    }
                    else
                    {
                     $('#Tracking').val('')
                     $("#Tracking").prop('disabled', false);
                    }
                });


                dataTable()
                function dataTable()
    {
                $('#MembershipPackageTable').DataTable({
                    "responsive": true,
                    "processing" : true,
                    "destroy": true,
                    "ajax" : {
                        "url" : "<?php echo EXEC; ?>Exec_MembershipPackageList",
                        "dataSrc" : ''
                    }
                    ,
                    "autoWidth": false,
                    "columnDefs": [
                        {
                            "className" : 'text-center', "targets" : '_all'}
                        ,
                        {
                            "width": "14%", "targets": 0 }
                        ,
                        {
                            "width": "15%", "targets": 1 }
                        ,
                        {
                            "width": "19%", "targets": 2 }
                        ,
                        {
                            "width": "18%", "targets": 3 }
                        ,
                        {
                            "width": "12%", "targets": 4 }
                        ,
                    ],
                        "columns" : [ 
                        {
                        "data" : "Name"
                        }
                        , 
                        {
                        "data": {
                        Price:"Price"}
                        ,
                        "render": function(data, type, row) {
                        return '<span>$'+ data.Price +'</span>';
                        }
                        }
                        ,
                        {
                        "data" : "Tracking",
                        }
                        ,  {
                        "data" : "Description",
                        }
                        ,
                        {
                        "data": "id",
                        "render": function(data, type, row) {
                        var encodedId = window.btoa(data);
                        return '<button class="btn btn-info btn-sm cubutfom" id="editButton" title="Edit Event" data-id='+ encodedId + '>' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-danger btn-sm cubutfom" title="Delete Event" id="deleteButton" data-id='+ encodedId + '>' + '<span class="fa fa-trash"><span>' + '</button>';
                        }
                        }
                    ]
                });
            }

                    $("#MembershipPackage").validate({
            ignore: ":hidden:not(textarea)",
            rules: {                
                Name: {required: true,maxlength:30},
                Price: {required: true,maxlength:10},
                Tracking: {required: true,},
                Description: {required: true,maxlength:255},
                'Users[]' : {required:true},
                Noofvisit : {required:true,maxlength:10},
            },
            messages: {             
                Name: {required: "Please enter package name"},
                Price: {required: "Please enter package price"},
                Tracking: {required: "Please select package tracking"},
                 Description: {required: "Please write short description"},
                'Users[]' : {required: "Please select at leats one service"},
                Noofvisit : {required: "Please select or select Number of Visits"},


            },
            errorPlacement: function( label, element ) {
                    if( element.attr( "name" ) === "Description" ) {
                        element.parent().append( label );
                    } else {
                         label.insertAfter( element );
                    }
            },
                submitHandler: function() {
                $(".Loader").show();
            var data = $("#MembershipPackage").serialize();
            
             data= data + "&LoginAction=Login";
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    url:'<?php echo EXEC; ?>Exec_AddMembershipPackage',
                    success: function(data)
                    {
                        if(data.resonse)
                {
                    $("#resonse").show();
                        $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    $( '#MembershipPackage' ).each(function(){
                        this.reset();
                        });
                    
                    $(".Loader").hide();
                    $("#myModal_membshipackage").modal('hide');
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
                 dataTable()
                }
                     }
                    });
                }           
        });

                                $(document).on('click','#deleteButton',function(e){
                e.preventDefault();
                
                var dlink = $(this).attr('data-id');
                         swal({
                title: "Temporary Delete?",
                text: "Once deleted, it will move to Archive list!",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                          var delid=$(this).attr('data-id');
                          $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'delid':delid},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                           
                                           swal(data.resonse)
                                           dataTable()
                                        }
                                        else if(data.error){
                                            swal('Sorry Somthing wrong please try agine')
                                            
                                            
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
$('#id').val('new');
$('#Name').val('');
$('#Price').val('');
$('#Tracking').val('');
$('#MPackageDescription').val('');
$('.select2-selection__choice').remove();
$('#Noofvisit2').prop("checked",false); 
$('#Tracking2').prop("checked",false); 
   
$('#resonse').hide();
$('#error').hide();
$('#csrf_error').hide();
});

$(document).on('click','#editButton',function(e){   
    $('.dropify-render').text('')
    $('.dropify-filename-inner').text('')
         var eidtid=$(this).attr('data-id');
          $(".Loader").show();
         
        $.ajax({
            dataType:"json",
            type:"post",
            data:{'eidtid':eidtid},
            url:'?action=editfile',
            success: function(data)
            {
               if(data.resonse){
                     $('#myModal_membshipackage').modal('show');
                     $('#Name').val(data.resonse.Name);
                     $('#Price').val(data.resonse.Price);

                     if(data.resonse.Tracking === "No Expiration")
                     {
                       
                        $('#Tracking2').prop("checked",true);
                     }
                     else{
                        $('#Tracking').val(data.resonse.Tracking);

                     }
                     //$('#Tracking').val(data.resonse.Tracking);
                     
                     if(data.resonse.Noofvisit === "Unlimited")
                     {
                        $('#Noofvisit2').prop("checked",true);
                        
                     }
                     else{
                        $('#Noofvisit').val(data.resonse.Noofvisit);
                        
                     }
                     $('#MPackageDescription').val(data.resonse.Description);

                     var user = data.resonse.service.split(',');
                                 var i=0;
                                 var selectedValues = new Array();
                                 $.each(user,function(){
                                    selectedValues[i] = user[i];
                                    i++;
                                 });
                                 $('#Users').val(selectedValues).trigger('change');
                                 
                     $('#id').val(data.resonse.id);
                     $(".Loader").hide();
                 }
               else if(data.error)
               {
                    swal(data.error)
                          // $("#error").show();
                          // $('#errormsg').html('<span>'+data.error+'</span>');
                          $(".Loader").hide();
                          // alert('<li>'+data.error+'</li>');
               }
            }  
        });
   });


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

             });

        </script>
  
</body>
</html>