<?php 
require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

if(isset($_GET['GET_CLIENT']) && $_SESSION['usertype']=='Admin'){


    $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.username=:id ) order by clients.FirstName");
    $eidtUserName3->bindValue(":id",$_GET['GET_CLIENT']);
    $editfile4=$eidtUserName3->execute();
    $Customer=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['Customer'=>$Customer]); die; 
}

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C57'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];

$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C58'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style>
  .modal {display:none;position:fixed; z-index:1000; top:0; left:0; height:100%;width:100%;background: rgba( 255, 255, 255, .8) 
    url('assets/images/ajax-loader.gif') 50% 50% no-repeat;}
    body.loading .modal {overflow: hidden;}
    body.loading .modal {display: block;}
    #datepairExample2 input,#datepairExample input{width: 40%;}
    div#ui-datepicker-div{background: #d1e9ff;}

    select#UpcomingRenewalsDays2{width: 30%;}
    .img-circle {
      object-fit: cover;
    }
    th { font-weight: bold!important;color:#0b59a2!important;}
    @media (min-width: 768px) {
      .modal-xl {
        width: 90%;
        max-width:1200px;
      }
    }
    .modal2 {
      display:none;
      position:fixed;
      z-index:1000;
      top:0;
      left:0;
      height:100%;
      width:100%;
      background: rgba( 255, 255, 255, .8) 
      url('assets/images/ajax-loader.gif') 50% 50% no-repeat;
      z-index: 1000000;
    }
    body.loading .modal2 {
      overflow: hidden;
    }
    body.loading .modal2 {
      display: block;
    }
    #orderdata{
      padding: 0 28px;
    }
    #to{
      width:50%;
      padding-top: 25px;
    }
    #notes,#to h5{
      background: #4095c7;
      color: white;
      padding: 5px ;
    }
    #carttable{
      margin: 25px auto!important;
      width: 98%!important;
    }
    tr#order_popup td{
      background: #4095c7;
      color: white;
      font-weight: 900;
      padding: 5px 10px;
    }
    .order_popup td input{
      border:0!important;
    }
    span#serivetoaltprice{
      padding: 0 48px;
    }
    span#giftcardtotal{
      padding: 0 45px;
    }
    span#producttotalprice{
      padding: 0 47px;
    }
    span#membershiptotalprice{
      padding: 0 18.4px;
    }
    span#salestax{
      padding: 0 36px;
    }
    span#tips{
      padding: 0 70px;
    }
    span#userpoint{
      padding-left: 51px;
    }
    span#toatalprice{
      padding: 0 8px;
      font-weight: bold!important;
      color: #0b59a2!important;
      font-size: 20px;
    }
        /*.Signature{
            padding: 50px;
            }*/
            .light-logo-contain{
              object-fit: contain;
              width: 40px;
              height: 40px;
            }
            .modal-backdrop{
              position: relative;
            }
            .last{
              text-align: center;
            }
            .order_popup td input{
              margin: 5px;
              padding: 5px;
            }


            #EmployeeListTable2 td{

              vertical-align: middle;

            }

            #EmployeeListTable td{
              vertical-align: middle;

            }
            
        </style>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">

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
                <div class="row page-titles">
                  <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor">Payment History</h4>
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

                        <div class="tab-content">
                          <div class="tab-pane active" id="home2" role="tabpanel">
                            <i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" style="font-size: 22px;float: right"></i>
                            <div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width:400px">
                              <div class="modal-header">
                                <h4 class="modal-title">Payment Filter</h4>
                                <button type="button" class="close mysunless_filter_btn" data-dismiss="modal">×</button>
                              </div>
                              <div class="modal-body">
                                <div>
                                                            <div class="form-group">
                                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%">
                                      <i class="fa fa-calendar"></i>&nbsp;
                                      <input type="text" id="mydaterang" placeholder="Date" readonly="" style="width:90%;border:0"> <i class="fa fa-caret-down"></i>
                                    </div>
                                  </div>

                                                        <div class="form-group">
                                  <select class="form-control select2" data-placeholder="Choose User" id="EmployeeListTable_Emp_filter" data-placeholder="Select User">
                                    <option value="">All</option>
                                    <?php 
                                    if($_SESSION['usertype']=='Admin'){
                                        $query= $db->prepare("SELECT * from users where usertype='subscriber' ");
                                        $query->execute();
                                        $result = $query->fetchAll();
                                        foreach ($result as $key => $value) {
                                            echo '<option value="'.$value["username"].'" >'.$value["username"].'</option>';
                                        }
                                    }else{
                                        $id = $_SESSION['UserID'];
                                        $query= $db->prepare("SELECT * from users where id=$id or adminid=$id");
                                        $query->execute();
                                        $result = $query->fetchAll();
                                        foreach ($result as $key => $value) {
                                            echo '<option value="'.$value["username"].'" >'.$value["username"].'</option>';
                                        }
                                    }
                                    ?>
                                  </select>
                                                        </div>

                                                        <div class="form-group">
                                                         <select class="select2 form-control" id="EmployeeListTable_Customer_filter" name="selectcutomer[]"  data-placeholder="Choose Customer">
                                                            <option value="">All</option>
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


                                                        <div class="form-group">
                                  <select class="form-control select2" data-placeholder="Payment Type" id="EmployeeListTable_payment_filter" data-placeholder="Payment Status">
                                    <option value="">All</option>
                                    <option value="card">Card</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Split">Split</option>
                                                                <option value="Split-Cash">Split-Cash</option>
                                                                <option value="Split-Card">Split-Card</option>
                                    <option value="Cheque">Cheque</option>
                                  </select>
                                                        </div>

                                                            <div class="form-group">
                                                                    <!-- <span id="reportrange_submit" class="btn btn-sm btn-info">Submit</span> -->
                                                                    <span id="reportrange_clear" class="btn btn-sm btn-danger">Reset</span>
                                                                </div>
                                                            
                                  
                                </div>
                              </div>
                            </div>

                            <div class="table-responsive">
                              <table id="EmployeeListTable" class="table table-bordered table-striped dataTable no-footer">
                                <thead>
                                  <tr>
                                                                <th></th>
                                    <th>User Info</th>
                                    <th>Customer</th>
                                    <th>Invoice/Status</th>
                                    <th>Payment</th>
                                    <th>Order Date</th>
                                                                <th>Amount</th>
                                    <th>Type(hide)</th>
                                  </tr>
                                </thead>
                              </table>
                              <div class="modal"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

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



          <script type="text/javascript">
            $('#datepairExample .time').timepicker({
              'showDuration': true,
              'timeFormat': 'g:ia'
            });
            $('#datepairExample .date').datepicker({
              'format': 'yyyy-m-d',
              'autoclose': true
            });
    // initialize datepair
    $('#datepairExample').datepair();
</script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
<script src="<?= base_url?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/node_modules/jqueryui/jquery-ui.min.js"></script>
<script>
  $(document).ready(function() {

        $(".select2").select2({
        allowClear: true
      });

    setTimeout(function(){$(".payment").addClass("active");}, 500);
    UpcomingRenewalsDataTable();


    var table;

    $( "#EmployeeListTable_Emp_filter" ).on( 'change', function () {
      if ( table.column(1).search() !== $(this).val() ) {
        table
        .column(1)
        .search( $(this).val() )
        .draw();
      }
    } );

        $( "#EmployeeListTable_Customer_filter" ).on( 'change', function () {
            if ( table.column(2).search() !== $(this).val() ) {
                table
                .column(2)
                .search( $(this).val() )
                .draw();
            }
        } );

        

        $( "#EmployeeListTable_payment_filter" ).on( 'change', function () {

              var val = $(this).val();

              if(val=='Split'){

                if ( table.column(7).search() !== $(this).val() ) {
                    table
                    .column(7)
                    .search( $(this).val() )
                    .draw();
                }

              }else{

                  if (val) {
                    table.column(7).search('^' + val + "$", true, false).draw();
                  } else {
                    table.column(7).search("").draw();
                  }

              }
        } );


                <?php if($_SESSION['usertype']=='Admin'){ ?>

            $("#EmployeeListTable_Emp_filter").change(function(){
                var id = $(this).val();
                $.ajax({
                    url:"?GET_CLIENT="+id,
                    dataType:'json',
                    success:function(data){

                        var CustomerHtml = "";
                        var Customer = data.Customer;
                        CustomerHtml+='<option value="">All</option>';
                        for(i in Customer){
                            CustomerHtml+='<option value="'+Customer[i].FirstName+' '+Customer[i].LastName+'">'+Customer[i].FirstName+' '+Customer[i].LastName+'</option>';
                        }
                        $("#EmployeeListTable_Customer_filter").html(CustomerHtml);

                    }
                });

            });

            $("#EmployeeListTable_Emp_filter1").change(function(){
                var id = $(this).val();
                $.ajax({
                    url:"?GET_CLIENT="+id,
                    dataType:'json',
                    success:function(data){

                        var CustomerHtml = "";
                        var Customer = data.Customer;
                        CustomerHtml+='<option value="">All</option>';
                        for(i in Customer){
                            CustomerHtml+='<option value="'+Customer[i].FirstName+' '+Customer[i].LastName+'">'+Customer[i].FirstName+' '+Customer[i].LastName+'</option>';
                        }
                        $("#EmployeeListTable_Customer_filter1").html(CustomerHtml);

                    }
                });

            });

        <?php } ?>

    $(document).on('change', '#mydaterang', function(){
      var selectdaterang = $('#mydaterang').val();
      UpcomingRenewalsDataTable(selectdaterang);

    });
    $(document).on('click', '#reportrange_clear', function(){

      $("#EmployeeListTable_Emp_filter").val('').change();
            $("#EmployeeListTable_customer_filter").val('').change();
      $("#EmployeeListTable_payment_filter").val('').change();
      $('#mydaterang').val('');
      UpcomingRenewalsDataTable();
    });


    function UpcomingRenewalsDataTable(selectdaterang=""){
      table = $('#EmployeeListTable').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "autoWidth": false,
        "order": [[ 5, "desc" ],[ 0, "desc" ]],
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'},
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [ 7 ],
                    "visible": false,
                    "searchable": true
                },
        { "width": "15%", "targets": 1 },
        { "width": "15%", "targets": 2 },
        { "width": "20%", "targets": 3 },
        { "width": "25%", "targets": 4 },
                { "width": "10%", "targets": 5 },
        { "width": "10%", "targets": 6 },
        ],
        "ajax" : {
          "type" : "POST",
          "url" : "<?php echo EXEC; ?>Exec_payment_Report?selectdaterang="+selectdaterang,
          "dataSrc" : ''
        },
        "columns" : [ 
                {
                            "data": "OrderId",
                },
        {
                "data": {firstname : "firstname", lastname : "lastname", userimg : "userimg",username:'username'}, 
                "render": function(data, type, row) {
                  if(data.userimg !=''){
                    var encodedId = btoa(row.UserID);
                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;"/></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>';
                  }
                  else
                  {
                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img title="View User Profile" class="ViewUserInfo" data-sid="'+encodedId+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;"  /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>'
                  }
                }
            },
            {
                "data": {ProfileImg:"ProfileImg", FirstName:"FirstName", LastName:"LastName",clientid:"clientid" },
                "render": function(data, type, row) {
                    if(data.ProfileImg!=''){
                        var encodedId = btoa(data.clientid);
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a href="#" ><img title="View Customer Profile" id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle " style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></a></div></div>';    
                    }
                    else
                    {
                        var encodedId = btoa(data.clientid);
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a href="#" ><img title="View Customer Profile" id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></a> </div></div>';           
                    }
                }
            },
            {
                "data":{InvoiceNumber:"InvoiceNumber", OrderId:"OrderId",payment_status:"payment_status" },
                "render": function(data, type, row)
                {
                    var encodedId = btoa(data.OrderId);
                    return '<a href="JavaScript:void(0)" class="invoice viewButton" title="View Invoice" data-id="' + encodedId + '"><span>'+data.InvoiceNumber+'</span></a><br>'+data.payment_status ;

                }                
            },
            {
              "data": {PaymentDetail:"PaymentDetail", PaymentType:"PaymentType" },
              "render": function(data, type, row) {


                    if(data.PaymentType=='Cash'){
                        return '<span class="font-weight-bold">'+data.PaymentType+'</span>';

                    }else if(data.PaymentType=='Cheque'){
                        PaymentDetail = JSON.parse(data.PaymentDetail);

                        if(PaymentDetail){

                        text = '<span class="font-weight-bold">Cheque</span>';
                        text+= '<br> Cheque No.: <b>'+PaymentDetail.ChequeNumber+'</b>';
                        text+= '<br> Bank: <b>'+PaymentDetail.Bank+'</b>';
                        text+= '<br> Submit Date: <b>'+PaymentDetail.submitdate+'</b>';
                        text+= '<br> Amount: <b>$'+PaymentDetail.Amount+'</b>';

                        return text;
                        
                        }else{
                            return '<span class="font-weight-bold">Cheque</span>';
                        }

                    }else if(data.PaymentType=='Card' || data.PaymentType=='Split-Card'){
                        PaymentDetail = JSON.parse(data.PaymentDetail);
                      if(PaymentDetail){
                        
                         text = '<span class="font-weight-bold">'+data.PaymentType+'</span>';
                        text+= '<br> API: <b>'+PaymentDetail.Agency+'</b>';
                        text+= '<br> Type: <b>'+PaymentDetail.card_type+'</b>';
                        text+= '<br> Last 4 digit: <b>'+PaymentDetail.card_last_digit+'</b>';
                        text+= '<br> Transaction ID: <b>'+PaymentDetail.TransactionID+'</b>';
                        text+= '<br> Amount: <b>$'+PaymentDetail.Amount+'</b>';

                        return text;
                        }else{
                            return '<span class="font-weight-bold">Card</span>';
                        }
                    }else{
                        return '<span class="font-weight-bold">'+data.PaymentType+'</span>';
                    }

              }                
            },
            {
              "data": {Orderdate:"Orderdate"},
              "render" : function(data,type,row){
                /*var date =  ($.datepicker.formatDate('dd M, yy',new Date(data) ));*/
                return moment(data.Orderdate).format('YYYY-MM-DD');
              }              
            },
            {
              "data": {amount : "amount"}, 
              "render": function(data, type, row) {
                var am = data.amount.replace(' ',"");
                return '<span>$'+am+'</span>';
              }
            },
            {
                "data":"PaymentType",
            }
            ]
        });
}


$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  $($.fn.dataTable.tables(true)).DataTable()
  .columns.adjust()
  .responsive.recalc();
});    
});       
</script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/loader.js"></script>
<script type="text/javascript">
  $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
      $('#mydaterang').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $('#mydaterang').trigger('change');
        }

    $('#reportrange').daterangepicker({
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
</script>
</body>
</html>