<?php 
require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body class="skin-default fixed-layout mysunlessU">
 <!-- ============================================================== -->
 <!-- Preloader - style you can find in spinners.css -->
 <!-- ============================================================== -->
 <div class="preloader">
  <div class="loader">
   <div class="loader__figure"></div>
   <p class="loader__label"><?php echo @$_SESSION['UserName']; ?></p>
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

    <style>
      .EventStatusUpdate{
        width: 100%;
        border-color: #03a9f3;
        text-align-last: right;
      }
    </style>
    <div class="row page-titles">
     <div class="col-md-5 align-self-center">
      <h4 class="text-themecolor">Event List</h4>
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

      <ul class="nav nav-tabs profile-tab" role="tablist">
        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#EventListTab" role="tab" id="sellgraphTab">List</a> </li>
        <li class="nav-item"> <a class="nav-link" id="appgraphTab" data-toggle="tab" href="#appgraph" role="tab">Appointment Graph</a> </li>
      </ul>

      <div class="tab-content">
       <div class="tab-pane active m-t-40" id="EventListTab" role="tabpanel">

        <a title="Add New Appointment" href="#" id="addnewappointment" class="btn btn-info"><i class="fa fa-plus"></i> Add</a>

        <i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" style="font-size: 22px;float: right"></i>
        <div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width:400px">
          <div class="modal-header">
            <h4 class="modal-title">Event Filter</h4>
            <button type="button" class="close mysunless_filter_btn" data-dismiss="modal">×</button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <div>
                <div id="EventDate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                  <i class="fa fa-calendar"></i>&nbsp;
                  <input type="text" id="EventDateRange" readonly style="width:90%;border:0" placeholder="Event Date">
                  <i class="fa fa-caret-down"></i>
                </div>
              </div>
            </div>

            <div class="form-group">
              <select class="form-control select2" id="EventFilterUser" data-placeholder="Choose User" multiple="multiple">
                <?php 

                if($_SESSION['usertype']=='Admin'){

                  $query= $db->prepare("SELECT * from users where usertype='subscriber' ");
                  $query->execute();
                  $result = $query->fetchAll();
                  foreach ($result as $key => $value) {

                    echo '<optgroup label="'.$value["firstname"].' '.$value["lastname"].'">';

                    $query1= $db->prepare("SELECT * from users where id=:id or adminid=:id");
                    $query1->bindParam(':id',$value['id']); 
                    $query1->execute();
                    $result1 = $query1->fetchAll();
                    foreach ($result1 as $key1 => $value1) {
                      echo '<option value="'.$value1["id"].'" >'.$value1["firstname"].' '.$value1["lastname"].'</option>';
                    }

                    echo '</optgroup>';

                  }

                }else{

                  $query= $db->prepare("SELECT * from users where id=:id or adminid=:id");
                  $query->bindParam(':id',$_SESSION['UserID']); 
                  $query->execute();
                  $result = $query->fetchAll();
                  foreach ($result as $key => $value) {
                    echo '<option value="'.$value["id"].'" >'.$value["firstname"].' '.$value["lastname"].'</option>';
                  }

                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <select class="select2 form-control" id="EventFilterCustomer" name="selectcutomer[]"  data-placeholder="Choose Customer" multiple="multiple">
                <?php
                $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
                $eidtUserName3->bindValue(":id",$_SESSION['UserID']);
                $editfile4=$eidtUserName3->execute();
                $allua=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);
                foreach ($allua as $key => $value) 
                {
                  ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['FirstName'].' '.$value['LastName']; ?></option>
                  <?php
                }
                ?>
              </select>
            </div>


            <div class="form-group">
              <select data-placeholder="Filter by Event Status" class="form-control select2" id="EventFilterStatus" multiple="multiple"> 
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="canceled">Canceled</option>
                <option value="pending-payment">Pending Payment</option>
                <option value="in-progress">In-progress</option>
              </select>
            </div>

            <div class="form-group">
              <span id="EventDate_Submit" class="btn btn-sm btn-info">Submit</span>
              <span id="EventDate_clear" class="btn btn-sm btn-danger">Reset</span>
            </div>
          </div>
        </div>

        <div class="table-responsive">
         <table id="EventListTable" class="table table-bordered table-striped dataTable no-footer">
          <thead>
           <tr>
            <th></th>
            <th>Service Provider</th>
            <th>Customer</th>
            <th>Customer Info.</th>
            <th>Event Detail</th>
            <th>Booking Date</th>
            <th>Action</th>
          </tr>
        </thead>
      </table>
      <div class="modal"></div>
    </div>
  </div>

  <div class="tab-pane" id="appgraph" role="tabpanel">
    <?php include('AppointmentGraph.php') ?>
  </div>

</div>
</div>
</div>
</div>
</div>
</div>

<?php include_once('BookEventModule.php'); ?>
<?php include('OrderInvoiceModel.php'); ?>
<?php include('viewclientdetail.php'); ?>
<?php include('viewuserdetail.php'); ?>


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

    $(".select2").select2({
      allowClear: true
    });


    var EventListTable;

    var EventFilter = {search:'',date:'',user:[],customer:[],status:[]};

    <?php if(isset($_REQUEST['SearchID'] )){ ?>  //used on todo requested event
      var Search_by_ID = "<?= base64_decode($_REQUEST['SearchID']) ?>"; 

      if(Search_by_ID){
        EventFilter.search=Search_by_ID;
      }
    <?php  } ?>


    dataTable(EventFilter)

    <?php if($_SESSION['usertype']=='Admin'){ ?>

      $("#EventFilterUser").change(function(){
        var id = $(this).val();
        $.ajax({
          url:"<?= EXEC ?>/Exec_All_Clients?GET_CLIENT="+id,
          dataType:'json',
          success:function(data){

            var CustomerHtml = "";
            var Customer = data.Customer;
            for(i in Customer){
              CustomerHtml+='<option value="'+Customer[i].id+'">'+Customer[i].FirstName+' '+Customer[i].LastName+'</option>';
            }
            $("#EventFilterCustomer").html(CustomerHtml);

          }
        });

      });

      $('#EventFilterUser').select2({matcher: modelMatcher});

      function modelMatcher (params, data) {
        data.parentText = data.parentText || "";

        if ($.trim(params.term) === '') {
          return data;
        }

        if (data.children && data.children.length > 0) {
          var match = $.extend(true, {}, data);

          for (var c = data.children.length - 1; c >= 0; c--) {
            var child = data.children[c];
            child.parentText += data.parentText + " " + data.text;

            var matches = modelMatcher(params, child);

            if (matches == null) {
              match.children.splice(c, 1);
            }
          }

          if (match.children.length > 0) {
            return match;
          }

          return modelMatcher(params, match);
        }

        var original = (data.parentText + ' ' + data.text).toUpperCase();
        var term = params.term.toUpperCase();


        if (original.indexOf(term) > -1) {
          return data;
        }

        return null;
      }

    <?php } ?>  

    $(document).on('click','#EventDate_clear',function(){

     $("#EventDateRange").val('').change();
     $("#EventFilterUser").val('').change();
     $("#EventFilterCustomer").val('').change();
     $("#EventFilterStatus").val('').change();
     EventFilter = {search:'',date:'',user:[],customer:[],status:[]};
     dataTable(EventFilter);
   });

    $(document).on('click','#EventDate_Submit',function(){
      dataTable(EventFilter);
    });

    $(document).on('change', '#EventDateRange', function(){
      EventFilter.date = $(this).val();
    });


    $(document).on('change', '#EventFilterUser', function(){
      EventFilter.user = $(this).val();
    });


    $(document).on('change', '#EventFilterCustomer', function(){
      EventFilter.customer = $(this).val();
    });


    $(document).on('change', '#EventFilterStatus', function(){
      EventFilter.status = $(this).val();
    });

    $(function() {

      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
        $('#EventDateRange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#EventDateRange').trigger('change');
      }

      $('#EventDate').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: new Date(),
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'Year to Date': [moment().subtract(1, 'year').startOf('day'), moment()]
        }
      }, cb);      
    });

    function dataTable(EventFilter){

      EventFilter = JSON.stringify(EventFilter);

      EventListTable = $('#EventListTable').DataTable({
        "responsive": true,
        "processing" : true,
        'serverSide': true,
        'serverMethod': 'post',
        "destroy": true,
        "autoWidth": false,

        "order": [[ 0, "desc" ]],
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'},
        {
          "targets": [ 0 ],
          "visible": false,
          "searchable": false
        },
        { "width": "15%", "targets": 1 },
        { "width": "15%", "targets": 2 },
        { "width": "20%", "targets": 3 },
        { "width": "25%", "targets": 4 },
        { "width": "20%", "targets": 5 },
        ],
        "ajax" : {
         "type" : "POST",
         url: "<?php echo EXEC; ?>ExecAllEvent?EventDatatable&EventFilter="+EventFilter,
         "dataSrc": function ( json ) {
          SetEventStatus(json.aaData);
          return json.aaData;
        } 
      },
      "columns" : [ 
      {
        "data": "id",
      },
      {
        "data": {username:'username'}, 
        "render": function(data, type, row) {
          var encodedId = btoa(row.ServiceProvider);
          if(row.userimg){
            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+row.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style=" padding: 0 5px;">'+data.username+'</span></div></div>';
          }
          else
          {
            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style=" padding: 0 5px;">'+data.username+'</span></div></div>'
          }
        }
      },
      {
        "data": {FirstName:"client_firstname", LastName:"client_Lastname"},
        "render": function(data, type, row) {
          var encodedId = btoa(row.clientid);
          if(row.ProfileImg){
            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View Customer Profile" id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/ProfileImages/'+row.ProfileImg+'" class="img-circle " style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></div></div>';    
          }
          else
          {
            return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View Customer Profile" id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span> </div></div>';           
          }
        }
      },
      {
        "data":{client_phone:"client_phone", client_email:"client_email"},
        "render": function(data, type, row)
        {
         return data.client_email+'<br>'+data.client_phone;

       }                
     },
     {
       "data": {eventstatus:"eventstatus" },
       "render": function(data, type, row) {

        status= '<select class="EventStatusUpdate'+row.id+' EventStatusUpdate" data-id="'+row.id+'" ><option value="completed">Completed</option> <option value="pending">Pending</option> <option value="confirmed">Confirmed</option> <option value="canceled">Canceled</option> <option value="pending-payment">Pending Payment</option> <option value="in-progress">In-progress</option></select>';

        OrderID = '';
        if(row.OrderID!=null && row.eventstatus==='completed'){
          OrderID = '<div class="row" style="justify-content: space-between;"> <span>Invoice : </span><b title="View Invoice" class="viewButton" data-id="'+btoa(row.OrderID)+'" >'+row.InvoiceNumber+'</b> </div>';
        }

        return '<div> <div class="row" style="justify-content: space-between;"> <span>Appointment ID :</span> <b >'+row.id+'</b> </div><div class="row" style="justify-content: space-between;"> <span>Service : </span><b>'+row.title+'</b> </div><div class="row" style="justify-content: space-between;"> <span>Cost : </span><b>$'+row.CostOfService+'</b> </div> <div class="row" style="justify-content: space-between;"> <span>Date/Time : </span><b>'+row.EventDate+'</b> </div> <div class="row" style="justify-content: space-between;"> <span>Status : </span><b>'+status+'</b> </div><div class="row" style="justify-content: space-between;"> <span>Location : </span><b>'+row.Location_radio+'</b> </div>'+OrderID+' </div>';

      }                
    },
    {
     "data": {datelastupdated:"datelastupdated"},
     "render" : function(data,type,row){
      return moment.utc(data.datelastupdated).local().format('YYYY-MM-DD H:m:s');
    }              
  },
  {
   "data": {id:"id"},
   "render" : function(data,type,row){

    OrderID = '';
    if(row.OrderID){
      OrderID = '<a href="" id="viewButton" style="color:white;" title="View Invoice" class="viewButton  btn btn-success btn-xs" data-id="'+btoa(row.OrderID)+'"><span class="fa fa-eye"></span></a>';
    }
    return '<span class="btn btn-sm btn-info EditEvent" data-id="'+btoa(data.id)+'">Edit</span>'+OrderID+' <a href="<?= base_url?>/Exec/Exec_Edit_Event?DeleteEvent&dlink='+btoa(data.id)+'" id="DeleteEvent" style="color:white;" title="View Invoice" class="DeleteEvent  btn btn-danger btn-xs" ><span class="fa fa-trash"></span></a>';
  }              
},

]
});

function SetEventStatus( data ) {

  setTimeout(function(){
    if(data.length>0){
      for(var i=0;i<data.length;i++){
        $("#EventListTable .EventStatusUpdate"+data[i].id).val(data[i].eventstatus);
        $("#EventListTable .EventStatusUpdate"+data[i].id).attr('data-old',data[i].eventstatus);

      }
    }
  },1000)

}


}


$(document).on('change','#EventListTable .EventStatusUpdate',function(){

  element = $(this);
  oldstatus = element.attr('data-old');
  status = $(this).val();
  id = element.attr('data-id');
  element.attr('disabled',true);

  $.ajax({
    url:'<?= base_url?>/viewuserdetail?action=newstat',
    type:'POST',
    dataType:'json',
    data:{newstat:status,myeventid:id},
    success:function(data){
      element.attr('disabled',false);
      if(data.resonse){
        element.attr('data-old',status)
        swal(data.resonse);
      }else{
       swal('',data.error,'error');
       element.val(oldstatus);
     }
   }
 });


});

});       
</script>

</body>
</html>