<?php 

ini_set("display_errors", "1");
error_reporting(E_ALL);

require_once('function.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}
    $id= $_SESSION['UserID'];   
    $total_clients = $db->prepare("SELECT * FROM `clients` WHERE `createdfk`=:id");
    $total_clients->bindParam(':id', $id, PDO::PARAM_INT);
    $total_clients->execute();
    $number_of_clients = $total_clients->rowCount();
    $all_client=$total_clients->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($all_client as $key => $value) 
    {
      if(!empty($value['email']))
      {
      $email=$value['email'];
       $id= $value['id'];   
      $total_clients2 = $db->prepare("SELECT * FROM `clients` WHERE `email`=:email AND id<>:id");
     $total_clients2->bindParam(':id', $id, PDO::PARAM_INT);
     $total_clients2->bindParam(':email', $email, PDO::PARAM_INT);
      $total_clients2->execute();
      $all_client2=$total_clients2->fetchAll(PDO::FETCH_ASSOC);
      echo "<pre>";
      print_r($all_client2);


      }
      // echo $value['id'];
      // echo "<br>";
      // echo $value['email'];
      // echo "<br>";
      // echo $value['Phone'];
      // echo "<br>";
    }

    die();



?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style>
/*.modal2 {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
                url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
body.loading .modal2 {overflow: hidden;}
body.loading .modal2 {display: block;}*/
li.token-search{width: 100%!important;}
button.dt-button.buttons-csv.buttons-html5 {cursor: pointer;position: absolute; top: 0; right: 0;border: #02a9f3; background: #02a9f3; height: 35px;}
button.dt-button.buttons-csv.buttons-html5:hover {background-color: #038fcd;}
div#home { position: relative;}
li.select2-selection__choice {color: white !important;}
.select2-container--default .select2-selection--multiple .select2-selection__choice{background-color: #42bfd3!important; border:1px solid #43c1d4!important; }
/*.select2-container--default .select2-selection--multiple{
   border-bottom: 1px solid #e9ecef!important;
   border-top: 0!important;
   border-right: 0!important;
   border-left: 0!important;}*/
span.select2.select2-container.select2-container--default.select2-container--focus{
   width: 100%!important;
}
span.select2.select2-container.select2-container--default.select2-container{
   width: 100%!important;
}
input.select2-search__field{
   width: 100%!important;
}
div.fb-login-button.fb_iframe_widget span {
    height: 27px !important;
}
/*div.fb-login-button.fb_iframe_widget span iframe {
    height: 35px !important;
}*/
.table-striped tbody tr:nth-of-type(odd) {
    background: #a3d3ea1c!important;
}
.table-bordered td, .table-bordered th {
     border-bottom: 1px solid #dee2e6!important;
     border-top : 1px solid #dee2e6!important;
     border-left: 0!important;
     border-right:0!important;
}
input#deleteCheck{box-sizing: border-box;
    padding: 0;
    width: 20px!important;
    height: 20px!important;
    margin-top: 13px!important;}
._5h0d ._5h0i {
    height: 35px !important;
}
._5h0d ._5h0m {
    height: 16px !important;
  }
  .img-circle {
    object-fit: cover;
}

    #viecdetails,#deleteButton,#editcustomer{display: inline-block!important;}
    
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
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
    .lb-outerContainer{width: 80%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
    }
      
    </style>

    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">

</style>
<link href="../assets/css/tokenize2.css" rel="stylesheet" type="text/css" />
<link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />    


<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<link rel="stylesheet" href="../dist/css/lightbox.min.css">


<body class="skin-default fixed-layout mysunlessB">
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
                        <h4 class="text-themecolor">Duplicate customer</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-body">

                              </div>
                            </div>
                            </div>
                      </div>                            
          </div>

                 <!-- Modal -->
  
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

<script src="<?php echo base_url; ?>/assets/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/buttons.print.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/dataTables.select.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>

  
<script>
$(document).ready(function() {
var daf = Math.floor(Math.random() * 40) + 1  
        
  $(document).on('click','#newclient_1',function(){
var daf = Math.floor(Math.random() * 40) + 1  
        
        $('#FirstName').val('')
                   $('#id').val('new')
                   $('#LastName').val('')
                   $('#phonenumber').val('')
                   $('#example-email').val('')
                   $('#autocomplete').val('')
                   $('#street_number').val('')
                   $('#postal_code').val('')
                   $('#country').val('') 
                   $('#administrative_area_level_1').val('')
      $('#locality').val('')

      $( ".dropify-render img" ).first().remove();
      $('#autocomplete').val('');
      $("#ProfileImg3").val('Layer'+daf+'.png')
        $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");
       $('<img src="<?php echo base_url; ?>/assets/ProfileImages/Layer'+daf+'.png" id="pImage">').appendTo(".dropify-render");
      $('.dropify-filename-inner').text('noimage.png')

   });

    $(".Loader").show();
    dataTable()
    function dataTable()
    {
    $('#myTable').DataTable({
        dom: 'Blfrtip',
        "processing": true,
        "responsive": true,
        "destroy": true,
        buttons: [
            // {
            //     extend: 'csv',
            //     text: 'csv All',
            //     exportOptions: {
            //         modifier: {
            //             selected: null
            //         }
            //     }
            // },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-excel-o"></i> Export As CSV'
            }
        ],
        "autoWidth": false,
        "columnDefs": [
          {"orderable": false,"className": 'select-checkbox', "targets": 0},
          {"targets" : '_all'},
          { "width": "5%", "targets": 0,"className": "Customer_info1" },
          { "width": "18%", "targets": 1,"className": "Customer_info2" },
          { "width": "17%", "targets": 2,"className": "Customer_info3" },
          { "width": "18%", "targets": 3,"className": "Customer_info4" },
          { "width": "27%", "targets": 4,"className": "Customer_info5" },
          { "width": "15%", "targets": 5,"className": "Customer_info6" },
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        "processing" : true,
        "ajax" : {
            "type" : "POST",
            "url" : "<?php echo EXEC; ?>Exec_All_Clients.php",
            "dataSrc" : ''
        },
        "columns" : [{
            "data": "id",
            "render": function(data, type, row) {
              var encodedId = window.btoa(data);
                return '<input type="checkbox" name="deleteCheck" id="deleteCheck" class="deleteCheck" value="'+encodedId+'">';
            }
         }, 
         {
      "data": {ProfileImg:"ProfileImg", FirstName:"FirstName", LastName:"LastName" },
      "render": function(data, type, row) {
                if(data.ProfileImg!=''){
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'"" data-lightbox="example-'+data.ProfileImg+'"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></div> </div>';    
                }
                else
                {
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" data-lightbox="example-noimage.png"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></div> </div>';       
                }
         }
        },{
            "data" : "email"
        }, {
            "data" : "Phone"
        },{
            "data": "tg",
            "render": function(data, type, row){
                if (data !== null && data !== '') {
                    var array= data.split(",") ;
                    var text= "";
                    for (var i = 0; i < array.length ; i++) {
                        text += "<span class='btn btn-secondary autoasbae'> "+array[i]+"</span> &nbsp ";
                    }
                    return text ;
                }
                else{
                    return data ;
                } 
            }
         },
        {
      "data": "id",
      "render": function(data, type, row) {
        var encodedId = window.btoa(data);
        return '<button class="btn btn-info btn-sm autoasbae" style="color:white" id="editcustomer" title="Edit Customer" data-cuid="'+encodedId+'" ><span class="fa fa-edit"><span></button> <button class="btn btn-danger btn-sm delete editcustomer autoasbae" id="deleteButton" title="Delete Customer" data-id='+ encodedId +'><span class="fa fa-trash"><span></button> <a class="btn btn-primary btn-sm editcustomer autoasbae" id="viecdetails" title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' "><span class="fa fa-eye"><span></a>'; 
      }
    }]
    });
  }
// $(document).on('click','#myTable tbody tr',function(event){
//  if (event.target.type !== 'checkbox') {
//              $(':checkbox', this).trigger('click');
//          }
// });
    $(".Loader").hide();
    $(document).on('click','#deleteButton',function(e){
        e.preventDefault();
          var id=new Array();
          id.push(window.atob($(this).data("id")));
        swal({
                title: "Are you sure?",
                text: "Once deleted, you will lost all data of this client account!",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{  
                if (willDelete){
                                $.ajax({
                       dataType:"json",
                       type:"post",
                        data:{'del_id':id},
                        url:'?action=deletefile',
                        beforeSend: function() {
                                $(".Loader").show();
                        },
                        success: function(data)
                            {
                            if(data.resonse)
                    {
                        $("#resonse").show();
                          $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                        $(".Loader").hide();
                         dataTable()
                    }
                    else if(data.error)
                    {
                        $("#error").show();
                          $('#errormsg').html('<span>'+data.error+'</span>');
                          dataTable()
                        $(".Loader").hide();
                    // alert('<li>'+data.error+'</li>');
                    }
                    else if(data.resonse==false)
                {
                $(".Loader").hide();
                  swal('Something wrong please try again')
                 
                }
                         }
                    });
                }
                 else{
                     return false ;
                 }
            });
    });



      $(document).on('click','body *',function(){

       var st = $("#street_number").val();
       var ad = $("#route").val();
       var fulladders = st+' '+ad;
      
       $("#autocomplete").val(fulladders);

});

    $(document).on('click','#editcustomer',function(){
      $('.dropify-render').text('')
      $('#clid').val('');
    $('.dropify-filename-inner').text('')
        $(".Loader").show();
       event.preventDefault();
     var customersid = $(this).data('cuid');
     var customersid2 = atob(customersid)

     $.ajax({
              dataType:"json",
              type:"post",
              data: {'customersid2':customersid2},
              url:'?action=editfile',
              success: function(data)
              {
                if(data.resonse)
                { 
                 
                   $('#FirstName').val(data.resonse.FirstName)
                   $('#id').val(data.resonse.id)
                   $('#LastName').val(data.resonse.LastName)
                   $('#phonenumber').val(data.resonse.Phone)
                   $('#example-email').val(data.resonse.email)
                   $('#autocomplete').val(data.resonse.Address)
                   $('#street_number').val(data.resonse.Address)
                   $('#postal_code').val(data.resonse.Zip)
                   $('#country').val(data.resonse.Country) 
                   $('#administrative_area_level_1').val(data.resonse.State)
                   $('#locality').val(data.resonse.City)
                   $('#oldimage').val(data.resonse.ProfileImg)
                    if(data.resonse.ProfileImg !== '')
                      {
                           
                          $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
                           $('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
                           $('.dropify-filename-inner').text(data.resonse.ProfileImg)
                        
                      }
                      else if(data.resonse.ProfileImg =='')
                      {
                           $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
                           $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
                           $('.dropify-filename-inner').text('noimage.png')
                      }

                   $("#myModal_addclient").modal('show')
                    $(".Loader").hide();
                }
                else if(data.resonse==false)
                {
                $(".Loader").hide();
                  swal('No data found')
                 
                }
               }
                })

      });
});
</script>

<script type="text/javascript">
$(document).ready(function() { 
    $(document).on('click','.Limit',function(){
        swal("Your Client Limit is over!!", "For Upgrade your limit contact to Admin", "warning");
    });
    $(document).on('click','.AdminLimit',function(){
        swal("Your Client Limit is over!", "For Upgrade your limit contact Admin", "warning");
    });
});
</script>
<!--  start sending mail js -->
<script type="text/javascript">
$(document).ready(function() { 
    $(document).on('click','#sendMailButton',function(){
        var id=new Array();
        $('input[name="deleteCheck"]:checked').each(function(){
            var value =$(this).closest("tr").find("td:eq(2)").text();
           id.push(value);
        });

        var id2=new Array();
        $('input[name="deleteCheck"]:checked').each(function(){
            var value2 =$(this).val();
           id2.push(value2);
        });


        if(id == '')
        {
           swal("Please select client");
        }
        else if(id!=''){
            //console.log(id);
            $("#composeModal").modal('show');
                var selectedValues = new Array();
                var i =0;
                $.each(id, function(k,v) {
                    selectedValues[i] = v;
                    i++;
                });

                var selectedValues1 = new Array();
                var i =0;
                $.each(id2, function(k,v) {
                    selectedValues1[i] = v;
                    i++;
                });

                console.log(selectedValues1);

                if(selectedValues){
                   $('#To').val(selectedValues).trigger('change');
                   $('#ccid').val(selectedValues1).val();
                }
            $("#NewMail").validate({
                    ignore: ":hidden:not(textarea)",
                    rules: {                
                        From: {required: true,},
                        "To[]": {required: true,},
                        Subject: {required: true,},
                        Message: {required: true,},
                    },
                    messages: {    
                        From: {required: "Please enter your email id"},
                        "To[]": {required: "Please select at least one recipient."},
                        Subject: {required: "Please enter email subject"},
                        Message: {required: "Please enter email message"},
                    },
                    errorPlacement: function( label, element ) {
                        if( element.attr( "name" ) === "Message" || element.attr( "name" ) === "To[]" ) {
                            element.parent().append( label );
                        } else {
                             label.insertAfter( element );
                        }
                    },
                    submitHandler: function() {
                        var data = $("#NewMail").serialize();
                        data= data ;
                        console.log(data);
                        jQuery.ajax({
                           dataType:"json",
                           type:"post",
                            data:data,
                            url:'<?php echo EXEC; ?>Exec_AllMail.php',
                            beforeSend: function() {
                                $(".Loader").show();
                            },
                            success: function(data)
                            {   
                                $(".Loader").hide();
                                $( '#NewMail' ).each(function(){
                                    this.reset();
                                });
                                $("#composeModal").modal('hide');
                                if(data.resonse_mail)
                                {
                                    $("#resonse").show();
                                    $('#resonsemsg').html('<span>'+data.resonse_mail+'</span>');
                                }
                                else if(data.error_mail)
                                {
                                    $("#error").show();
                                    $('#errormsg').html('<span>'+data.error_mail+'</span>');
                                }
                            }
                        });
                    }           
        });
        }
    });
                $('#Templeate').on('change',function(){
         $(".Loader").show();
    tid=$(this).val();
             $.ajax({
                   dataType:"json",
                   type:"post",
                    data: {'tid':tid},
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                {
                    $(".Loader").hide();
                      $('#Subject').val(data.resonse.Subject);
                                // $("textarea").val(data.resonse.TextMassage);       
                $('iframe').contents().find('.wysihtml5-editor').html(data.resonse.TextMassage);
                }
                else if(data.error)
                {
                    alert('ok');  
                }
                     }
                })
});

                      $('#phonenumber').keyup(function(e){
        var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
    });

});
</script>

<!-- End sending mail js  -->
<script>
    $(document).on('click','#addTag',function(){
        var id=new Array();
        $('input[name="deleteCheck"]:checked').each(function(){
            var checkvale = window.atob(this.value)
            id.push(checkvale);
            console.log(id)
            //id.push(this.value);

        });
        if(id == '')
        {
           swal("Please select client");
        }
        else if(id!='')
        {
            $('#addTagModel').modal().show();
            $('#mynewcid2').val(id);
            $("#Newtag").validate({
                rules: {                
                    "tag[]": {required: true,},
                },
                messages: {             
                    "tag[]": {required: "Please Enter Tag Name "},
                },
                submitHandler: function() {
                    $(".Loader").show();
                     var data = $("#Newtag").serialize();
                    data= data + "&Action=old_tag";
                    jQuery.ajax({
                           dataType: "JSON",
                           type:"post",
                            data:data,
                            url:'<?php echo EXEC; ?>Exec_Edit_Tag.php',
                            success: function(data)
                            {
                                if(data.resonse)
                                {
                                    $("#resonse").show();
                                    $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                    $( '#Newtag' ).each(function(){
                                         this.reset();
                                    });
                                    $(".Loader").hide();
                                    $('#addTagModel').modal('toggle');
                                    //setTimeout(function () { window.location.href = "Alltag.php"; }, 1000) ;
                                    window.location.reload();
                                }
                                else if(data.error)
                                { 
                                    $("#error").show();
                                      $('#errormsg').html('<span>'+data.error+'</span>');
                                      $('#addTagModel').modal('toggle');
                                    $(".Loader").hide();
                                // alert('<li>'+data.error+'</li>');
                                }
                            }
                    });
                }           
            });
        }
    });
    </script>
      
    <script type="text/javascript">
          $(document).on('click','#deleteButton3',function(){
        var id=new Array();
        $('input[name="deleteCheck"]:checked').each(function(){
            var checkvale = window.atob(this.value)
            id.push(checkvale);
            
        });
         if(id == '')
         {
           swal("Please select client");
         }
         else if(id!=''){
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will lost all data of these selected clients accounts!",
            icon: "warning",
            buttons: true,
        }).then((isConfirm)=>{ 
              if (isConfirm){ 
                    $.ajax({
                       dataType:"json",
                       type:"post",
                        data:{'del_id':id},
                        url:'?action=deletefile',
                        beforeSend: function() {
                                $(".Loader").show();
                        },
                        success: function(data)
                            {
                            if(data.resonse)
                    {
                        $("#resonse").show();
                          $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                        $(".Loader").hide();
                         location.reload();
                    }
                    else if(data.error)
                    {
                        $("#error").show();
                          $('#errormsg').html('<span>'+data.error+'</span>');
                        $(".Loader").hide();
                    // alert('<li>'+data.error+'</li>');
                    }
                    else if(data.resonse==false)
                {
                $(".Loader").hide();
                  swal('Something wrong please try again')
                 
                }
                         }
                    });
              }
              else{
                  return;
              }
        });
 }
  });


$(document).on('click','#addcusomimagebutton',function(){
  $('#ProfileImg').trigger('click'); 
});
    </script>

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
<!-- start select multiple js -->
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
       //  $('.selectpicker').selectpicker();
        //Bootstrap-select2
         $(".vertical-spin").select2({
            verticalbuttons: true,
            verticalupclass: 'ti-plus',
            verticaldownclass: 'ti-minus'
         });
         var vspinTrue = $(".vertical-spin").select2({
            verticalbuttons: true
         });
         if (vspinTrue) {
            $('.vertical-spin').prev('.bootstrap-select2-prefix').remove();
         }
         $("input[name='tch1']").select2({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
         });
         $("input[name='tch2']").select2({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
         });
         $("input[name='tch3']").select2();
         $("input[name='tch3_22']").select2({
            initval: 40
         });
         $("input[name='tch5']").select2({
            prefix: "pre",
            postfix: "post"
         });
         // For select2
          $('#pre-selected-options').select2();
         $('#optgroup').select2({
            selectableOptgroup: true
         });
         $('#public-methods').select2();
         $('#select-all').click(function() {
            $('#public-methods').select2('select_all');
            return false;
         });
         $('#deselect-all').click(function() {
            $('#public-methods').select2('deselect_all');
            return false;
         });
         $('#refresh').on('click', function() {
            $('#public-methods').select2('refresh');
            return false;
         });
         $('#add-option').on('click', function() {
            $('#public-methods').select2('addOption', {
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
           // templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
</script>
<!-- end select multiple js -->
<!-- wysuhtml5 Plugin JavaScript -->

<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.textarea_editor').wysihtml5();
        });

$(document).ready(function() {
  $('iframe').contents().find('#NewMail .wysihtml5-editor').html('{{ first_name }} {{ last_name }}');

    // $("form#mailcampaf ul.wysihtml5-toolbar").append('<li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode" title="Insert ShortCode" href="javascript:;" unselectable="on">ShortCoad</a></li>');

    $("form#NewMail ul.wysihtml5-toolbar").append('<li class="dropdown"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">ShortCoad</span>&nbsp;<b class="caret"></b></a><ul class="dropdown-menu"><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode1" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ first_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode2" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ last_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode3" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ customer_email }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode4" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ phone }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode5" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.street }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode6" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.city }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode7" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.state }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode8" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.country }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode9" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.zip }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode11" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ owner.signature }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode12" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_phone }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode13" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode14" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_address }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode15" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ next_service }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode16" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ next_service_employee }}</a></li></ul><li>');

    $("#insertshortcode1").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ first_name }}');
});

    $("#insertshortcode2").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ last_name }}');
});

$("#insertshortcode3").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ customer_email }}');
});

$("#insertshortcode4").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ phone }}');
});
//
$("#insertshortcode5").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.street }}');
});


$("#insertshortcode6").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.city }}');
});

$("#insertshortcode7").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.state }}');
});

$("#insertshortcode8").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.country }}');
});

$("#insertshortcode9").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.zip }}');
});

$("#insertshortcode10").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ current_date }}');
});

$("#insertshortcode11").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ owner.signature }}');
});

$("#insertshortcode12").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_phone }}');
});

$("#insertshortcode13").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_name }}');
});

$("#insertshortcode14").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_address }}');
});


$("#insertshortcode15").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ next_service }}');
});


$("#insertshortcode16").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ next_service_employee }}');
});


    });
    

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GoogleApiKey; ?>&libraries=places&callback=initAutocomplete" async defer></script>
       <script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
      };
      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});
        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }
      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        for (var component in componentForm) {
          /*document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;*/
        }
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }
      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());

          });
        }
      }
    </script>

    <script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
    <script>
    $(document).ready(function() {
      var daf = Math.floor(Math.random() * 40) + 1  
      
           $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");
        // Basic
        $('.dropify').dropify();
        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });
        // Used events
        var drEvent = $('#input-file-events').dropify();
        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });
        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });
        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });
        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })

                $("#NewClient").validate({
            rules: {                
                FirstName: "required",
                LastName: "required",
                Phone: {required: true,},
                email: "required",
                Address: "required",
                Zip: "required",
                City: "required",
                State: "required",
                Country:"required",
                newlistofSubscriber2: "required",
                // Solution: "required",
                // PrivateNotes: "required",
                // SelectPackage: "required",
                //employeeSold: "required",
                // sd: {required: true,},
                // ed: {required: true,},
            },
            messages: {             
                FirstName:  "Please enter firstName",
                LastName:  "Please enter lastName",
                Phone:  "Please enter phone number",
                email:  "Please enter email",
                Address:  "Please enter address",
                Zip:  "Please enter zipcode",
                Country:"Please select country",
                City:  "Please enter city", 
                State:  "Please enter state",
                newlistofSubscriber: {required: "Please select Subscriber or User"},
                // Solution:"Please Enter Solution Strength",
                // PrivateNotes:"Please Enter Private Notes",
                // SelectPackage :"Please Select Package",
                //employeeSold: "Please Select Employee Who Sold",
                // sd:"Please Select Starting Date &nbsp&nbsp&nbsp&nbsp",
                // ed:"&nbsp&nbsp&nbsp&nbsp Please Select Ending Date",
            },
         errorPlacement: function( label, element ) {
                    if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
                        element.parent().parent().append( label );
                    } else {
                         label.insertAfter( element );
                    }
            },
      submitHandler: function() {
          $(".Loader").show();
               var form = $('#NewClient')[0];
               var data = new FormData(form);
               //var data = $("#NewClient").serialize();
               jQuery.ajax({
                   dataType:"json",
                   type:"post",
                    data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'<?php echo EXEC; ?>Exec_Edit_Client.php',
                    success: function(data)
                {
                        if(data.resonse)
                {
                    $("#resonse").show();
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    $( '#NewClient' ).each(function(){
                         this.reset();
                         });
                    $(".Loader").hide();
                        $("#myModal_addclient").modal('hide')
                        setTimeout(function () { window.location.reload() }, 2000)
  //  setTimeout(function () { window.location.href = "ViewClient.php?action=view&id="+window.btoa(data.mydata.id); }, 2000)

                }
                else if(data.error)
                {
                    $("#error").show();
                      $('#errormsg').html('<span>'+data.error+'</span>');
                    $(".Loader").hide();
                    $("#myModal_addclient").modal('hide')
                    setTimeout(function () { window.location.reload() }, 2000)
                // alert('<li>'+data.error+'</li>');
                }
                else if(data.csrf_error)
                {
                  
                    $("#csrf_error").show();
                    $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    $("#myModal_addclient").modal('hide')
                    setTimeout(function () { window.location.reload() }, 2000)
                }
                 }
                
                 });
                }           
        });

          $(".dropify-clear").click(function(e){
        e.preventDefault();
        $(".dropify-preview").hide();
        
         var data1 = $("#id").val();
         
        data = data1 + "&action5=deleteimage";
                jQuery.ajax({
            dataType:"json",
            url:'<?php echo EXEC; ?>exec-edit-profile.php?action5',
            type:"post",
             data:{"cimyData2":data1},
             success: function(data) 
              {
                        if(data.resonse)
                {
                    location.reload();
                }
                else if(data.error)
                {
                    alert('somening worng')
                }
                     }
      });
});          

    });
    </script>
<script src="<?php echo base_url; ?>/upload-and-crop-image/croppie.js"></script>
    <script>  
$(document).ready(function(){

  $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'circle' //circle or square
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#ProfileImg').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $('.dropify-render').text('')
    $('.dropify-filename-inner').text('')

      $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url : "<?php echo base_url; ?>/upload-and-crop-image/upload.php",
        type: "POST",
        data:{"image": response},
        dataType:"json",
        success:function(data)
        {
          
          // $('#uploaded_image').html(data);
               $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/"+data.resonse+"");
          $('<img src="<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/'+data.resonse+'" id="pImage">').appendTo(".dropify-render");
          //$( ".dropify-render img" ).first().css( "display", "none" );
          $('.dropify-filename-inner').text(data.resonse)
          $("#ProfileImg2").val(data.resonse)

      $('#uploadimageModal').modal('hide');
        }
      });
    })
  });


  $(function(){
  $('.radio').click(function(){
    if ($(this).is(':checked'))
    {
      var myseletimage = $(this).val()
      var myseletimageurl = '<?php echo base_url; ?>/assets/ProfileImages/'+myseletimage
      
      if(myseletimageurl!='')
      {
      $image_crop.croppie('bind', {
        url: myseletimageurl
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    $('#uploadimageModal').modal('show');

    }
  });
});

});  
</script>



</body>
</html>