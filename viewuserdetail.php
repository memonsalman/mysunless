<?php 
require_once('function.php');
require_once('global.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

$Query= $db->prepare("SELECT * from users where id=:id"); 
$Query->bindParam(':id', $_SESSION['UserID']);
$Query->execute();
$EventPermission = $Query->fetch();
$SchedulesCreateData = $EventPermission['SchedulesCreate'];

if(isset($_GET['get_username'])){
  $stmt= $db->prepare("select CONCAT(firstname,' ',lastname) as fullname from users where id=:id"); 
 $stmt->bindParam(':id', $_POST['sid']);
 $stmt->execute();
 $result = $stmt->fetch(); 
 echo json_encode($result);die;
}

if(isset($_REQUEST['action']) && isset($_POST['newstat']))
{

  if($SchedulesCreateData!=1){
    echo json_encode(['error'=>'No Permission! Please contact your administrator.']);die;
  }

  $newstat= $_POST['newstat'];
  $myeventid = $_POST['myeventid'];

  $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
  $stmt->bindparam(":newstat",$newstat);
  $stmt->bindparam(":myeventid",$myeventid);
  $run = $stmt->execute();

  if($stmt)
  {
    echo json_encode(['resonse'=>'Appointment status successfully changed!']);die;
  }else{
    echo json_encode(['error'=>'Something went wrong!']);die;
  }


}

if(isset($_GET['get_all_users_sells_report'])){

  if(!empty($_GET['selectdaterange'])){
    $selectdaterang = explode('-',$_GET['selectdaterange']);
    $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
    $todate = date("Y-m-d", strtotime($selectdaterang[1]));
  }

  $db= new db();
  $getalltraction = $_GET['selectemp'];
  $selectcutomer = $_GET['selectcustomer'];  

  if(empty($fromdate) && empty($todate) && empty($selectcutomer))
  {
    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED' AND users.id IN($getalltraction)");   
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;      
  }
  else if(empty($fromdate) && empty($todate) && !empty($selectcutomer))
  {
    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED' AND users.id IN($getalltraction) AND clients.id IN($selectcutomer)");   
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;      
  }
  else if(!empty($fromdate) && !empty($todate) && empty($selectcutomer))
  {
    $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
      JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
      JOIN clients ON OrderPayment.Cid=clients.id
      JOIN users ON OrderPayment.Cratedfk=users.id  
      WHERE  OrderPayment.payment_status='CAPTURED'AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate AND users.id IN($getalltraction)");  
    $LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
    $LoginQuery->execute();
    $result = $LoginQuery->fetchAll();
    echo json_encode($result);die;      
  }
  else if(!empty($fromdate) && !empty($todate) && !empty($selectcutomer))
  {
   $LoginQuery = $db->prepare("SELECT users.id as UserID,users.firstname,users.lastname,users.username,users.userimg,clients.FirstName,clients.LastName,clients.id as clientid,clients.ProfileImg,OrderMaster.InvoiceNumber,OrderPayment.PaymentType,OrderPayment.Orderdate,OrderPayment.payment_status,OrderPayment.amount,OrderPayment.OrderId,users.usertype FROM `OrderPayment`
    JOIN OrderMaster ON OrderPayment.OrderId=OrderMaster.id 
    JOIN clients ON OrderPayment.Cid=clients.id
    JOIN users ON OrderPayment.Cratedfk=users.id  
    WHERE  OrderPayment.payment_status='CAPTURED' AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')>=:fromdate AND DATE_FORMAT(OrderMaster.datecreated, '%Y-%m-%d')<=:todate AND users.id IN($getalltraction) AND clients.id IN($selectcutomer)");
   $LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
   $LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);
   $LoginQuery->execute();
   $result = $LoginQuery->fetchAll();
   echo json_encode($result);die;      
 } 
}

if(isset($_GET['total_app'])){
 $db= new db();
 @$id=$_POST['user'];

 $stmt_appo= $db->prepare("select eventstatus,count(eventstatus) as count from event join clients on event.cid=clients.id where Accepted<>'2' and ServiceProvider=:id GROUP by eventstatus"); 
 $stmt_appo->bindParam(':id', $id);
 $stmt_appo->execute();
 $result_appo = $stmt_appo->fetchAll(); 

 $query= $db->prepare("select COUNT(*) as TotalC from clients where createdfk=:id"); 
 $query->bindParam(':id', $id);
 $query->execute();
 $result = $query->fetch(); 

 $app = [];
 $app['total'] = 0;
 foreach ($result_appo as $key => $value) {
  $app[$value['eventstatus']] = $value['count'];
  $app['total']+=intval($value['count']);
  }

echo json_encode(['app'=>$app,'TotalC'=>$result['TotalC']]);die;
}

if(isset($_GET['total_sell_by_user'])){
 $db= new db();
 @$id=$_POST['user'];

 $stmt_appo= $db->prepare("SELECT clients.id as cid,CONCAT(FirstName,' ',LastName) as Name,clients.ProfileImg,Sum(OrderPayment.amount) as Total FROM `OrderPayment` JOIN clients ON OrderPayment.Cid=clients.id WHERE OrderPayment.payment_status='CAPTURED' AND OrderPayment.Cratedfk =:id GROUP by clients.id order by Total desc"); 
 $stmt_appo->bindParam(':id', $id);
 $stmt_appo->execute();
 $result_appo = $stmt_appo->fetchAll(); 

echo json_encode($result_appo);die;
}


if(isset($_GET['Appointment_list'])){
 $db = new db();

 $date = "";
 if(!empty($_GET['selectdaterange'])){
  $selectdaterang = explode('-',$_GET['selectdaterange']);
  $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
  $todate = date("Y-m-d", strtotime($selectdaterang[1]));

  $date = "  AND DATE_FORMAT(event.EventDate, '%Y-%m-%d')>='$fromdate' AND DATE_FORMAT(event.EventDate, '%Y-%m-%d')<='$todate' ";
}

@$id=$_GET['appid'];
$stmt_appo= $db->prepare("SELECT CONCAT(clients.FirstName ,' ', clients.LastName ) AS ClientName ,clients.ProfileImg,event.eventstatus, event.ServiceProvider,event.datecreated as newdate,event.title,event.EventDate,event.cid,Service.ServiceName,event.id as eid,OrderMaster.id as OrderID FROM `event` left JOIN OrderMaster on event.id=OrderMaster.eid JOIN Service on event.ServiceName=Service.id JOIN clients on event.cid=clients.id WHERE event.isactive=1 and event.Accepted<>'2' and event.ServiceProvider=:id".$date); 
$stmt_appo->bindParam(':id', $id);
$stmt_appo->execute();
$result_appo = $stmt_appo->fetchAll(); 
echo json_encode($result_appo);die;
}


if($_SESSION['usertype']=="Admin")
{
  $query = $db->prepare("select id,FirstName,LastName from `clients`");
}
else
{
  $query = $db->prepare("select id,FirstName,LastName from `clients` where createdfk=:id");  
}

$query->bindValue(":id",$id,PDO::PARAM_STR);
$query->execute();
$clients=$query->fetchAll(PDO::FETCH_ASSOC);

?>

<style type="text/css">
   .EventStatusUpdate{
        width: 100%;
        border-color: #03a9f3;
        text-align-last: center;
      }
  #ViewUserInfo,.ViewUserInfo{
    cursor: pointer;
    border-radius: 50%;
  }   
  .ViewUserInfo:hover,#ViewUserInfo:hover{
    animation-name: shadow;
    animation-duration: 1s;
    animation-iteration-count: infinite;
    animation-timing-function: ease;
  }
  @keyframes shadow {
    0%   {box-shadow: 0px 0px 1px 0px #0000001a;}
    50%  {box-shadow: 0px 0px 1px 8px #0000001a;}
    100% {box-shadow: 0px 0px 1px 0px #0000001a;}
  }
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/calendar.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?= base_url?>/assets/css/custom.css">


<div class="modal fade" id="myServiceModal"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-lg" style="max-width: 1100px;">
  <div class="modal-content">
   <div class="modal-header">
    <h4 class="modal-title">User Detail</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-tabs customtab" role="tablist">
          <li class="nav-item">
            <a href="#appbook" id="appbook_tab" class="nav-link active" data-toggle="tab" role="tab">
              <span class="hidden-xs-down">Appointment Booked</span>
            </a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link" data-toggle="tab" href="#UserSells" id="UserSells_Tab" role="tab">
              <span class="hidden-xs-down">Sells Info</span>
            </a> 
          </li>

        </ul>



        <div class="tab-content">

          <div class="tab-pane active" id="appbook" role="tabpanel">
            <div class="p-20">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <div id="app_reportrange" style="border-radius: 5px;background: #fff; cursor: pointer; padding: 2px 12px; border: 1px solid #ccc; width: 100%">
                      <i class="fa fa-calendar"></i>&nbsp;
                      <input type="text" id="app_mydaterang" placeholder="Date" readonly="" style="width:88%;border:0">  <i class="fa fa-caret-down"></i>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3">
                  <select data-placeholder="Choose Customer" class="form-control w-200 select2" id="myapp_customer_filter" multiple="multiple"> 
                  <?php
                  $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
                  $eidtUserName3->bindValue(":id",$_SESSION['UserID']);
                  $editfile4=$eidtUserName3->execute();
                  $allua=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($allua as $key => $value) 
                  {
                    ?>
                    <option value="<?php echo $value['FirstName'].' '.$value['LastName']; ?>"><?php echo $value['FirstName'].' '.$value['LastName']; ?></option>
                    <?php
                  }
                  ?>
                  </select>
                </div>


                <div class="col-lg-3">
                  <select data-placeholder="Choose Event Status" class="form-control w-200 select2" id="myapp_status_filter" multiple="multiple"> 
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="canceled">Canceled</option>
                    <option value="pending-payment">Pending Payment</option>
                    <option value="in-progress">In-progress</option>
                  </select>
                </div>

                <div class="col-lg-2">
                  <div class="form-group">
                    <input type="hidden" class="GetUserID">
                    <button type="submit" class="btn btn-info btn-sm" id="app_report_submit"> Submit</button>
                    <button type="submit" class="btn btn-danger btn-sm" name="app_report" id="app_report_reset"> Reset</button>
                  </div>
                </div>

                <div class="card col-lg-4"> 
                  <div style="cursor: pointer;" class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordion1" href="#App_Overview" aria-expanded="false" aria-controls="collapseOne">
                   <h5 class="mb-0">
                    <i class="fas fa-angle-double-right arrow-rotate"></i> Overview 
                  </h5> 
                </div>
                <div id="App_Overview" class="collapse" role="tabpanel" aria-labelledby="headingOne1" style=""> 
                  <div class="p-4 row">
                    <table class="table table-striped table-hover table-sm">
                      <thead  class="thead-dark">
                        <tr>
                          <th style="color: white !important;font-size: 18px;">TITLE</th>
                          <th style="color: white !important;font-size: 18px;">TOTAL</th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr><td style="width: 60%"><i class="fa fa-user-circle-o mr-2" aria-hidden="true"></i>Client Created: </td><td class="total_client">0</td></tr>
                      <tr><td><i class="fa fa-calendar-check-o mr-2" aria-hidden="true"></i>Total Appointment: </td><td class="app_total">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-info"></span>Completed: </td><td class="app_completed">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-success"></span>Confirmed: </td><td class="app_confirmed">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-grey"></span> Pending: </td><td class="app_pending">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-red"></span>Payment-Pending:</td><td class="app_pending-payment">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-important"></span>Canceled: </td><td class="app_canceled">0</td></tr>
                      <tr><td><span class="pull-left event  mr-2 event-warning"></span>In-Progress: </td><td class="app_in-progress">0</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div> 
              </div>

                <div class="table-responsive col-md-12 mt-2">
                  <table id="ViewUserAppTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Eid</th>
                        <th>Client</th>
                        <th>Appointment Detail</th>
                        <th>Service Status</th>
                        <th>Action</th>

                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="UserSells" role="tabpanel">
            <div class="p-20">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <div id="UserSells_reportrange" style="border-radius: 5px;background: #fff; cursor: pointer; padding: 2px 12px; border: 1px solid #ccc; width: 100%">
                      <i class="fa fa-calendar"></i>&nbsp;
                      <input type="text" id="UserSells_mydaterang" placeholder="Date" readonly="" style="width:88%;border:0">  <i class="fa fa-caret-down"></i>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3">
                  <div id="cus" class="d-flex align-items-center">
                    <select class="select2" id="UserSells_selectcutomer" name="selectcutomer[]" style="width: 100%" data-placeholder="Choose Customer" multiple="multiple">                                                

									<?php
									$eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
									$eidtUserName3->bindValue(":id",$_SESSION['UserID']);
									$editfile4=$eidtUserName3->execute();
									$allua=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);
									foreach ($allua as $key => $value) 
									{
										?>
										<option value="<?php echo $value['FirstName'].' '.$value['LastName']; ?>"><?php echo $value['FirstName'].' '.$value['LastName']; ?></option>
										<?php
									}
									?>
                    </select>
                  </div>
                </div>    

                <div class="col-lg-3">
                  <select class="form-control select2 " data-placeholder="Payment Type" id="UserSells_PaymentType" multiple="" >
                    <option value="card">Card</option>
                    <option value="Cash">Cash</option>
                    <option value="Split-Cash">Split-Cash</option>
                    <option value="Split-Card">Split-Card</option>
                    <option value="Cheque">Cheque</option>
                  </select>
                </div>  

                <div class="col-lg-2">
                  <div class="form-group">
                    <input type="hidden" class="GetUserID">
                    <button class="btn btn-info btn-sm" id="get_sells_report_submit"> Submit </button>
                    <button class="btn btn-danger btn-sm" name="get_sells_report" id="get_sells_report_reset"> Reset </button>
                  </div>
                </div>

                <div class="card col-lg-6"> 
                  <div style="cursor: pointer;" class="card-header collapsed" role="tab" data-toggle="collapse" data-parent="#accordion1" href="#Client_Overview" aria-expanded="false" aria-controls="collapseOne">
                   <h5 class="mb-0">
                    <i class="fas fa-angle-double-right arrow-rotate"></i> Overview 
                  </h5> 
                </div>
                <div id="Client_Overview" class="collapse  p-4 row" role="tabpanel" aria-labelledby="headingOne1" style="max-height: 350px;overflow-y: scroll;"> 
                </div>

              </div>
              <div class="clearfix" style="clear: both;"></div>
              <div class="table-responsive">
                <table id="SellTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%!important;">
                  <thead>
                    <tr>
                      <th>Customer Name</th>
                      <th>Invoice Number</th>
                      <th>Type</th>
                      <th>Order Date</th>
                      <th>Amount</th>
                      <th>Action</th>
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
  <div class="modal-footer">
   <div class="form-group" style="margin-bottom: 0px;">
    <button type="submit" class="btn waves-effect waves-light btn-success m-r-10" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
  </div>
</div>

</div>
</div>
</div>


<?php include_once('BookEventModule.php'); ?>
<?php include_once('OrderInvoiceModel.php'); ?>
<?php include_once('viewclientdetail.php'); ?>



<script>

  $(document).ready(function(){


    var sid = "";

    $(document).on('click','.ViewUserInfo',function(event){

      $('#myServiceModal').modal('show');
      sid = window.atob($(this).attr('data-sid'));    
      $(".GetUserID").val(sid);
      $("#appbook_tab").trigger('click'); 
      Load_Customer();
       $.ajax({
          dataType:"json",
          type:"post",
          data:{'sid':sid},
          url:'viewuserdetail?get_username',
          success: function(data)
          {
            $("#myServiceModal .modal-title").text('User Detail ('+data.fullname+')');
          } 
        });
    });

    var app_start = moment().subtract(29, 'days');
    var app_end = moment();

    function app_cb(app_start, app_end) {
      $('#app_mydaterang').val(app_start.format('MMMM D, YYYY') + ' - ' + app_end.format('MMMM D, YYYY'));
      $('#app_mydaterang').trigger('change');
    }

    $('#app_reportrange').daterangepicker({
      startDate: app_start,
      endDate: app_end,
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
   }, app_cb);
    app_cb(app_start, app_end);

    var ViewUserAppEventFilter = {search:'',date:'',user:[],customer:[],status:[]};

    $(document).on('click', '#app_reportrange', function(){
      $(".daterangepicker").addClass("show-calendar");
    });

    $(document).on('change', '#app_mydaterang', function(){
      var selectdaterang = $('#app_mydaterang').val()
      ViewUserAppEventFilter.date = selectdaterang;
    });

    $( "#myapp_status_filter" ).on( 'change', function () {
      ViewUserAppEventFilter.status = $(this).val();
    } );

    $( "#myapp_customer_filter" ).on( 'change', function () {
      ViewUserAppEventFilter.customer = $(this).val();
    } );

     $(document).on('click', '#app_report_submit', function(){
      sid = $(".GetUserID").val();
      ViewUserAppTable(ViewUserAppEventFilter);
    });

    $(document).on('click', '#app_report_reset,#appbook_tab', function(){
      sid = $(".GetUserID").val();
      ViewUserAppEventFilter = {search:'',date:'',user:[sid],customer:[],status:[]};
      ViewUserAppTable(ViewUserAppEventFilter);
      $('#app_mydaterang').val("");
      $("#myapp_status_filter").val("").change();
      $("#myapp_customer_filter").val("").change();
    });


    function UpdateUsersEventOverview(){
      sid = $(".GetUserID").val();
      $.ajax({
        url:"viewuserdetail?total_app",
        data:{user:sid},
        type:'post',
        dataType:'json',
        success:function(data){
         $(".total_client").text(data.TotalC);
          for(i in data.app){
            $(".app_"+i).text(data.app[i]);
          }
        }
      });
    }

    var Event_table;

    function ViewUserAppTable(ViewUserAppEventFilter){
      
      ViewUserAppEventFilter = JSON.stringify(ViewUserAppEventFilter);
      UpdateUsersEventOverview();
      
      Event_table = $('#ViewUserAppTable').DataTable({
       "responsive": true,
       "processing" : true,
       "destroy": true,
       "autoWidth": false,
       "order": [[ 0, 'desc' ]],
       "columnDefs": [
       { "targets" : '_all'},
       { "width": "10%", "targets": 0,"className" : 'text-center', },
       { "width": "20%", "targets": 1,"className" : 'text-center', },
       { "width": "40%", "targets": 2,"className" : 'text-left', },
       { "width": "20%", "targets": 3,"className" : 'text-center', },
       { "width": "10%", "targets": 4,"className" : 'text-center', },
       ],
       "ajax" : {
         url: "<?php echo EXEC; ?>ExecAllEvent?EventFilter="+ViewUserAppEventFilter,
        "dataSrc" : ''
      },
      "columns" : [
      {
        "data" : "id"
      }, 
      {
        "data": {ProfileImg:"ProfileImg", FirstName:"client_firstname", LastName:"client_Lastname",clientid:"clientid" },
        render:function(data,type,row,meta){

          if(data.ProfileImg)
          {

            return '<div class="text-center"> <img class="rounded-circle viewInfo" data-cid="'+btoa(data.clientid)+'" src="<?php echo base_url; ?>/assets/ProfileImages/'+data.ProfileImg+'" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+ data.FirstName +' '+ data.LastName +'</h6></label></div>';

          }
          else
          {
            return '<div class="text-center"><img class="rounded-circle viewInfo" data-cid="'+btoa(data.clientid)+'" src="<?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" height="50px" width="50px"> <br /><label><h6>'+ data.FirstName +' '+ data.LastName +'</h6></label></div>';

          }
        }
      },
      {
        "data" : "title",
        render:function(data,type,row,meta)
        {
          var datelastupdated = moment(row.datelastupdated).format("YYYY-MM-DD hh:mma"); 

           OrderID = '';
        if(row.OrderID!=null && row.eventstatus==='completed'){
          OrderID = '<span> <h6 style="display: inline-block;">Invoice : '+row.InvoiceNumber+'</h6> </span>';
        }

          return  '<div class="activeuserdetial"><h5><b>'+row.title+'</b></h5><span> <h6 style="display: inline-block;">Service Date : </h6> </span>'+row.EventDate+' <br /> <span> <h6 style="display: inline-block;">Service Status : '+row.eventstatus+'</h6> </span><br /> <span> <h6 style="display: inline-block;">Booked Date : '+datelastupdated+'</h6></span> '+OrderID+'</div>';
        }
      }
      ,
      {
        "data" : "eventstatus",
        render:function(data,type,row,meta)
        {
         return  '<select class="EventStatusUpdate EventStatusUpdate'+row.id+'" data-id="'+row.id+'" ><option value="completed">Completed</option> <option value="pending">Pending</option> <option value="confirmed">Confirmed</option> <option value="canceled">Canceled</option> <option value="pending-payment">Pending Payment</option> <option value="in-progress">In-progress</option></select>';    
       }
     }
     , 
     {
      "data": "id",
      "render": function(data,type,row,meta) {

        var encodedId = window.btoa(row.id);
        var encodedOrderId = window.btoa(row.OrderID);

        var action= "<div class=''><a class='btn btn-info btn-xs EditEvent' style='color: white' id='EditEvent' title='Edit Appointment' data-id="+encodedId+"><span class='fa fa-edit'><span></a> <a class='btn btn-danger btn-xs ' style='color: white' id='deleteAppointment' title='Delete Appointment' data-id="+encodedId+" ><span class='fa fa-trash'><span></a>";

        if(row.OrderID!=null && row.eventstatus==='completed')
        {
          action+=' <a href="" id="viewButton" style="color:white;" title="View Invoice" class="viewButton  btn btn-success btn-xs cutbut" data-id="' + encodedOrderId + '"><span class="fa fa-eye"></span></a>';
        }

        action+="</div>";
        return action;

      }
    }
    ]
  });

      Event_table.ajax.reload( function ( data ) {

  if(data.length>1){
    for(var i=0;i<data.length;i++){

      $("#ViewUserAppTable .EventStatusUpdate"+data[i].id).val(data[i].eventstatus);
      $("#ViewUserAppTable .EventStatusUpdate"+data[i].id).attr('data-old',data[i].eventstatus);


    }
  }
});
    }


$(document).on('change','#ViewUserAppTable .EventStatusUpdate',function(){
  element = $(this);
  oldstatus = element.attr('data-old');
  status = element.val();
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
        UpdateUsersEventOverview();
        swal(data.resonse);
      }else{
        swal('',data.error,'error');
         element.val(oldstatus);
      }
    }
  });
});

    $(document).on('click','#deleteAppointment',function(e){
      e.preventDefault();
      $(".Loader").show();
      var parent = $(this).parents(".activedatea");
      var dlink = $(this).attr('data-id');
      swal({
       title: "Temporary Delete?",
         text: "Once deleted, it will move to Archive list!",
        icon: "warning",
        buttons: true,
      }).then((willDelete)=>{   
        if (willDelete){
         $.ajax({
          dataType:"json",
          type:"post",
          data:{'dlink':dlink},
          url:'<?php echo EXEC; ?>/Exec_Edit_Event?DeleteEvent',
          success: function(data)
          {
            if(data.response){
              $(".Loader").hide();
              swal(data.response);
              parent.remove();
              Event_table.ajax.reload(); 
            }
            else if(data.error){
             $(".Loader").hide();
             swal('Something is wrong please try agine')
           }
         }
       });
       }
       else{
        $(".Loader").hide();
        return false ;
      }
    });
    });



    var sell_start = moment().subtract(29, 'days');
    var sell_end = moment();

    function sell_cb(sell_start, sell_end) {
      $('#UserSells_mydaterang').val(sell_start.format('MMMM D, YYYY') + ' - ' + sell_end.format('MMMM D, YYYY'));
      $('#UserSells_mydaterang').trigger('change');
    }

    $('#UserSells_reportrange').daterangepicker({
      startDate: sell_start,
      endDate: sell_end,
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
   }, sell_cb);

    sell_cb(sell_start, sell_end);


  function Load_Customer() {
    
    id = $('.GetUserID').val();
    $.ajax({
      url:"<?= EXEC ?>/Exec_All_Clients?GET_CLIENT="+id,
      dataType:'json',
      success:function(data){

        var CustomerHtml = "";
        var Customer = data.Customer;
        for(i in Customer){
          CustomerHtml+='<option value="'+Customer[i].id+'">'+Customer[i].FirstName+' '+Customer[i].LastName+'</option>';
        }
        $("#UserSells_selectcutomer").html(CustomerHtml);
        $("#myapp_customer_filter").html(CustomerHtml);

      }
    });
  }

    $('#UserSells_selectcutomer').select2({
      placeholder: 'Select Customer',
		  allowClear: true
    });

    var sellTable;

	$( "#UserSells_selectcutomer" ).on( 'change', function () {
		UserOrderFilter.Customer = $(this).val();
	} );

  $( "#UserSells_PaymentType" ).on( 'change', function () {
    UserOrderFilter.Payment = $(this).val();
  } );

    $(document).on('click', '#UserSells_reportrange', function(){
      $(".daterangepicker").addClass("show-calendar");
    });

    UserOrderFilter = {SelectDate:'',User:[],Customer:[],Payment:[]};

    $(document).on('click', '#get_sells_report_submit', function(){
      UserSells_getalltraction(UserOrderFilter);
    });

    $(document).on('change', '#UserSells_mydaterang', function(){
      var selectdaterang=$('#UserSells_mydaterang').val()
      UserOrderFilter.SelectDate = selectdaterang;
    });

    $(document).on('click', '#get_sells_report_reset,#UserSells_Tab', function(){
      UserOrderFilter = {SelectDate:'',User:[],Customer:[],Payment:[]};
      UserSells_getalltraction(UserOrderFilter);
      $('#UserSells_mydaterang').val("");
      $("#UserSells_selectcutomer").val(null).change()
    });


    function UserSells_Overview(data){

      if(data.length>0){
        var temp="";
        var cid = [];
        var Clients = [];

        for(i in data){

          clientID = data[i]['clientID'];

          if(cid.indexOf(clientID)>-1){
            TotalOrderAmount =  parseFloat(data[i]['TotalOrderAmount'].replace("$","").trim());
            Clients[clientID].total+=TotalOrderAmount;
          }else{
            cid.push(clientID);
            TotalOrderAmount =  parseFloat(data[i]['TotalOrderAmount'].replace("$","").trim());
            Clients[clientID] = {ProfileImg:data[i]['ProfileImg'],Client_Fullname:data[i]['Client_Fullname'],total:TotalOrderAmount}
          }
        }

        for(i in Clients){

          if(Clients[i].ProfileImg){
            var img = "<?= base_url?>/assets/ProfileImages/"+Clients[i].ProfileImg;
          }else{
            var img = "<?= base_url?>/assets/ProfileImages/noimage.png";
          }
          temp+="<tr style='text-align:center;'><td style='width:60%'><img class='viewInfo' data-cid='"+btoa(i)+"' width='30px' height='30px' src='"+img+"'><br>"+Clients[i].Client_Fullname+"</td><td style='vertical-align: middle;'>$"+parseFloat(Clients[i].total).toFixed(2)+"</td></tr>";
        }

        $("#Client_Overview").html('<table class="table table-striped table-hover table-sm"> <thead class="thead-dark"> <tr style="text-align:center;"> <th style="color: white !important;font-size: 18px;">CLIENT</th> <th style="color: white !important;font-size: 18px;">TOTAL SOLD</th> </tr> </thead> <tbody>'+temp+'</tbody></table>');

      }else{
        $("#Client_Overview").html('No result found.');
      }

    }


    function UserSells_getalltraction(UserOrderFilter){

      UserOrderFilter = JSON.stringify(UserOrderFilter);
      UserID = $('.GetUserID').val();

      sellTable = $('#SellTable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "autoWidth": false,
        "order": [[ 2, 'desc' ]],
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'},
        { "width": "14%", "targets": 0 },
        { "width": "16%", "targets": 1 },
        { "width": "21%", "targets": 2 },
        { "width": "17%", "targets": 3 },
        { "width": "16%", "targets": 4 },
        ],
        "ajax" : {
          "url" : "<?php echo EXEC; ?>Exec_Edit_Order.php?Completed_Order&OrderFilter="+UserOrderFilter+"&UserID="+UserID,
          "dataSrc": function ( json ) {
            UserSells_Overview(json.aaDataNoLimit);
            return json.aaDataNoLimit;
          }  
        },
        "columns" : [ 
        {
          "data": {ProfileImg:"ProfileImg",Client_Fullname:"Client_Fullname",clientID:"clientID"},
          "render": function(data, type, row) {
            if(data.ProfileImg != '' && data.ProfileImg != null){
              var encodedId = btoa(data.clientID); 
              return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle viewInfo" data-cid="'+btoa(row.clientID)+'" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.Client_Fullname +'</span></a> </div></div>';    
            }
            else
            {
              var encodedId = btoa(data.clientID); 
              return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle viewInfo" data-cid="'+btoa(row.clientID)+'" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.Client_Fullname +'</span></a> </div></div>';       
            }
          }
        },
        {
          "data": "InvoiceNumber",                
        },
        {
          "data": "PaymentType",                
        },
        {
          "data": {Orderdate:"datelastupdated"},
          "render" : function(data,type,row){
            return moment(data.Orderdate).format('MM-DD-YYYY');
          }              
        },
        {
          "data": {TotalOrderAmount : "TotalOrderAmount"}, 
          "render": function(data, type, row) {
            var amount = data.TotalOrderAmount.replace("$","").trim();
            return '<span>$'+amount+'</span>';
          }
        },

        {
          "data":  {orderid : "orderid"},
          "render": function(data, type, row) {
            var encodedId = btoa(data.orderid);
            return '<a href="" title="View Invoice" class="btn btn-success btn-sm cutbut viewButton" data-id="' + encodedId + '"><span class="fa fa-eye"></span></a>' ;
          }
        }
        ]
      });
    }


  });
</script>

