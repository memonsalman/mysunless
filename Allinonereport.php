<?php 
require_once('function.php');
require_once('global.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}

$id=$_SESSION['UserID']; 


if(isset($_GET['GET_CLIENT'])){
   $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
   $eidtUserName3->bindValue(":id",$_GET['GET_CLIENT'],PDO::PARAM_STR);
   $editfile4=$eidtUserName3->execute();
   $allua2=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);
   echo json_encode($allua2); die; 
}

if($_SESSION['usertype']=="Admin")
{
    $eidtUserName2 = $db->prepare("select id,firstname,lastname,username from `users` where  usertype='subscriber'");
}
else
{
    $eidtUserName2 = $db->prepare("select id,firstname,lastname,username from `users` where (adminid=:id AND usertype='employee') or (id=:id) ");
}

$eidtUserName2->bindValue(":id",$id,PDO::PARAM_STR);
$editfile3=$eidtUserName2->execute();
$allua=$eidtUserName2->fetchAll(PDO::FETCH_ASSOC);


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

<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?= base_url?>/assets/css/custom.css">
<style>
    #datepairExample input{width: 40%;}
    div#ui-datepicker-div{background: #d1e9ff;}
    #EmployeeListTable td.text-center{text-align: left!important;}
    .img-circle {
        object-fit: cover;
    }

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
            .last{
                text-align: center;
            }
            .img-circle {
                object-fit: cover;
            }
            .cutbut{margin:5px 0;}
            .select2-container .select2-selection--single{height: 34px;}
            .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:32px;}
            th { font-weight: bold!important;color:#0b59a2!important;}
            
        </style>
        <body class="skin-default fixed-layout mysunlessQ">
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
                            <h4 class="text-themecolor">Transaction List</h4>
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
                                        <div class="p-20">

                                            <i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" style="font-size: 22px;float: right"></i>
                                        <div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width:400px">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Filter</h4>
                                                <button type="button" class="close mysunless_filter_btn" data-dismiss="modal">×</button>
                                            </div>

                                            <div class="modal-body">
                                                
                                            <div class="col-lg-12" style="padding-top: 25px">
                                                <div class="col-lg-12">
                                                  <div class="form-group">
                                                    <div id="reportrange" style="background: #fff;     border-radius: 5px;cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                                        <i class="fa fa-calendar"></i>&nbsp;
                                                        <span id="mydaterang"></span> <i class="fa fa-caret-down"></i>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                              <div class="form-group">
                                                <select class="select2 m-b-10 select2-multiple" id="selectemployee" name="selectemployee[]" multiple="multiple" style="width: 100%" data-placeholder="Choose User">                                                
                                                    <option value="">Select User</option>

                                                    <?php
                                                    foreach ($allua as $key => $value) 
                                                    {
                                                        ?>
                                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['firstname'].' '.$value['lastname']." (".$value['username'].")"; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>      


                                        <div class="col-lg-12">
                                          <div id="cus" class="form-group">
                                            <select class="select2 m-b-10 select2-multiple "  id="selectcutomer" name="selectcutomer[]" style="width: 100%" multiple="multiple" data-placeholder="Choose Customer">                                                
                                                <option value="">select Customer</option>
                                            </select>
                                        </div>
                                    </div>      

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn-sm btn waves-effect waves-light btn-info" name="add-client" id="add-client"><i class="fa fa-search" aria-hidden="true"></i> Report</button>
                                            <button type="submit" class="btn-sm btn waves-effect waves-light btn-danger" name="add-client-all" id="add-client-all">Reset</button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                                <div class="clearfix" style="clear: both;"></div>
                                <div class="table-responsive">
                                    <table id="EmployeeListTable2" class="table table-bordered table-striped dataTable no-footer" style="width: 100%!important;">
                                        <thead>
                                            <tr>
                                                <th>User Info</th>
                                                <!-- <th>User Type</th> -->
                                                <!-- <th>Payment Type</th> -->
                                                <th>Invoice Number</th>
                                                <th>Customer Name</th>
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
    </div>

    <?php include_once('OrderInvoiceModel.php'); ?>
    <?php include_once('viewclientdetail.php'); ?>
    <?php include_once('viewuserdetail.php'); ?>


</div>
<!-- action,date,cutomer,user,produc,price,  -->
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

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript">
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
</script> -->

<!-- For today activities -->

<script>
    //to print the invoice
    
    jQuery(document).ready(function() {
    	//sidebardropdown admin
        var utype = '<?php echo $_SESSION["usertype"]; ?>';
        if(utype == "Admin")
        {
            $(".adminreport").trigger("click");
            setInterval(function(){$(".transaction").addClass("active");}, 10);
        }
        //default data
        setTimeout(function(){ 
          $("#add-client").trigger("click"); 
      }, 50);
    	//sidebar dropdown
      $("#reportdrop").trigger("click");
      setInterval(function(){$(".allinone").addClass("active");}, 10);
        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });
        // For select 2
        $(".select2").select2();

        $("#selectemployee").change(function(){
            var id = $(this).val();
            $.ajax({
                url:"Allinonereport?GET_CLIENT="+id,
                dataType:'json',
                success:function(data){
                    console.log(data);
                    var html = "";
                    for(i in data){
                        html+='<option value="'+data[i].id+'">'+data[i].FirstName+' '+data[i].LastName+'</option>';
                    }
                    $("#selectcutomer").html(html);
                }
            });

        });

    });
</script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/node_modules/jqueryui/jquery-ui.min.js"></script>
<script>
    $(document).ready(function()
    {

        setTimeout(function(){ 
            $("#add-client-all").trigger("click"); 
        }, 50);

        $.fn.select2.amd.define('select2/selectAllAdapter', [
            'select2/utils',
            'select2/dropdown',
            'select2/dropdown/attachBody'
            ], function (Utils, Dropdown, AttachBody) {

                function SelectAll() { }
                SelectAll.prototype.render = function (decorated) {
                    var self = this,
                    $rendered = decorated.call(this),
                    $selectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square"></i> Select All</button>'
                        ),
                    $unselectAll = $(
                        '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square"></i> Unselect All</button>'
                        ),
                    $btnContainer = $('<div style="margin-top:3px;">').append($selectAll).append($unselectAll);
                    if (!this.$element.prop("multiple")) {
            // this isn't a multi-select -> don't add the buttons!
            return $rendered;
        }
        $rendered.find('.select2-dropdown').prepend($btnContainer);
        $selectAll.on('click', function (e) {
            var $results = $rendered.find('.select2-results__option[aria-selected=false]');
            $results.each(function () {
                self.trigger('select', {
                    data: $(this).data('data')
                });
            });
            $("div#cus ul.select2-selection__rendered").html('<li class="select2-selection__choice" title="All Customers">All Customers</li>');
            self.trigger('close');
            
        });
        $unselectAll.on('click', function (e) {
            var $results = $rendered.find('.select2-results__option[aria-selected=true]');
            $results.each(function () {
                self.trigger('unselect', {
                    data: $(this).data('data')
                });
            });
            self.trigger('close');
        });
        return $rendered;
    };

    return Utils.Decorate(
        Utils.Decorate(
            Dropdown,
            AttachBody
            ),
        SelectAll
        );

});

        $('#selectcutomer').select2({
            placeholder: 'Select',
            dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
        });
/*$('#selectemployee').select2({
    placeholder: 'Select',
    dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
});*/

$(document).on('click', '#reportrange', function(){
    $(".daterangepicker").addClass("show-calendar");
});

$(document).on('click', '#add-client', function(){

    var selectdaterang=$('#mydaterang').text()
    getalltraction(selectdaterang);
    
});

$(document).on('click', '#add-client-all', function(){
    $('#reportrange span').html('');
    getalltraction();

});



function getalltraction(selectdaterang=""){

    var selectemployee = $('#selectemployee').val()
    var selectcutomer = $('#selectcutomer').val()
    var selectdaterang = selectdaterang;

    $('#EmployeeListTable2').DataTable({
        "responsive": true,
        "processing" : true,
        "destroy": true,
        "autoWidth": false,
        "columnDefs": [
        {"className" : 'text-center', "targets" : '_all'},
        { "width": "14%", "targets": 0 },
        { "width": "16%", "targets": 1 },
        { "width": "21%", "targets": 2 },
        { "width": "17%", "targets": 3 },
        { "width": "16%", "targets": 4 },
        { "width": "20%", "targets": 5 },
        ],
        "ajax" : {
            "type" : "POST",
            "url" : "<?php echo EXEC; ?>Exec_payment_Report?getalltraction="+selectemployee+"&selectcutomer="+selectcutomer+"&selectdaterang="+selectdaterang,
            "dataSrc" : ''
        },
        "columns" : [ 
        {
            "data": {username : "username", userimg : "userimg",UserID:"UserID"}, 
            "render": function(data, type, row) {
                if(data.userimg !='' && data.userimg != null){
                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img class="ViewUserInfo" data-sid="'+btoa(data.UserID)+'" src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;" class="img-circle " /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>';
                }
                else
                {
                    return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img class="ViewUserInfo" data-sid="'+btoa(data.UserID)+'" src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;" class="img-circle" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>'
                }
            }
        },
            // {
            //     "data": "usertype",                
            // },
            // {
            //     "data": "PaymentType",                
            // },
            {
                "data": "InvoiceNumber",                
            },
            {
                "data": {ProfileImg:"ProfileImg", FirstName:"FirstName", LastName:"LastName",clientid:"clientid"},
                "render": function(data, type, row) {
                    if(data.ProfileImg != '' && data.ProfileImg != null){
                        var encodedId = btoa(data.clientid); 
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img  id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle " style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></a> </div></div>';    
                    }
                    else
                    {
                        var encodedId = btoa(data.clientid); 
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img  id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span></a> </div></div>';       
                    }
                }
            },
            {
                "data": {Orderdate:"Orderdate"},
                "render" : function(data,type,row){
                    return moment(data.Orderdate).format('YYYY-MM-DD');
                }              
            },
            {
                "data": {amount : "amount"}, 
                "render": function(data, type, row) {
                    var am = data.amount.replace(" ","");
                    return '<span>$'+am+'</span>';
                }
            },

            {
                "data": "OrderId",
                "render": function(data, type, row) {
                    var encodedId = window.btoa(data);
                // return '<button id="viewButton" title="Delete" class="btn btn-primary btn-sm " data-id="' + encodedId + '"><span class="fa fa-eye"></span></button>';

                return '<a href="" title="View Invoice" class="btn btn-primary btn-sm cutbut viewButton" data-id="' + encodedId + '"><span class="fa fa-eye"></span></a>' ;
            }
        }
        ]
    });
}


});
</script>

<script type="text/javascript">
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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
        cb(start, end);

    });
</script>
</body>
</html>