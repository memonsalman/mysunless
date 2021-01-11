<?php 
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}

if(isset($_REQUEST['GET_EMP'])){

    $query = $db->prepare("Select * from users where adminid=:id or id=:id ");
    $query->bindparam(":id",$_REQUEST['GET_EMP']);
    $query->execute();
    $result = $query->fetchAll();

    echo json_encode(['Employee'=>$result]);
    die;
}

if(isset($_REQUEST['GET_LOCATION']))
{

    // define('GoogleApiKey','AIzaSyC_b0_oMhH_KxjcJsY2rK3YS_YVQ6y8fAA');
    $ip = $_REQUEST['GET_LOCATION'];

    if($ip){

        $host = 'http://ipinfo.io/'.$ip.'/json';

        if ( function_exists('curl_init') ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $host);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
            $response = curl_exec($ch);
            curl_close ($ch);
        } else if ( ini_get('allow_url_fopen') ) {
            $response = file_get_contents($host, 'r');
        } else {
            echo json_encode(['error'=>'geoPlugin class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ']);die;
        }

        echo $response;

    }else{
        echo json_encode(['error'=>'Empty IP.']);
    }
    die;
}

if(isset($_REQUEST['LoginDetail']) && isset($_REQUEST['Filter'])){

    $Filter = json_decode($_REQUEST['Filter'],true);

    if(!empty($Filter['Date']) ){
        $FilterDate =explode(' - ',$Filter['Date']);
        $fromdate = date("Y-m-d", strtotime($FilterDate[0]));
        $todate = date("Y-m-d", strtotime($FilterDate[1]));
        $date = " AND  DATE_FORMAT(ActiveUser.LoginTime, '%Y-%m-%d')>='".$fromdate."'
        AND DATE_FORMAT(ActiveUser.LoginTime, '%Y-%m-%d')<='".$todate."' ";
    }else{
        $date = "";
    }



    if(isset($_SESSION['usertype']) && $_SESSION['usertype']=='Admin'){

        if(!empty($Filter['SubscriberID']) && empty($Filter['EmployeeID'])){
            $id = $Filter['SubscriberID'];
            $id = " AND ActiveUser.UserId IN (select id from users where id=$id or adminid=$id ) ";
        }else if(!empty($Filter['EmployeeID'])){
            $id = $Filter['EmployeeID'];
            $id = implode(',', $id);
            $id = " AND ActiveUser.UserId IN ($id) ";
        }else{
            $id = "";
        }

    }else if ( isset($_SESSION['usertype']) && $_SESSION['usertype']=='subscriber'){
        if(!empty($Filter['SubscriberID'])){
            $id = $Filter['SubscriberID'];
            $id = " AND ActiveUser.UserId = $id ";
        }else{
            $id = $_SESSION['UserID'];
            $id = " AND ActiveUser.UserId IN (select id from users where id=$id or adminid=$id ) ";
        }
    }



    $query = $db->prepare("Select ActiveUser.*,TIMESTAMPDIFF(SECOND,ActiveUser.LoginTime, ActiveUser.LogoutTime) as RunTime,users.username,users.userimg from ActiveUser join users  where users.id=ActiveUser.UserId $id $date ORDER BY ActiveUser.id desc");
    $query->execute();
    $result = $query->fetchAll();

    echo json_encode($result);

    die;

}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<body class="skin-default fixed-layout mysunlessT">
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
                    <h4 class="text-themecolor">Login Log</h4>
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
                            <!-- Tab panes -->
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="col-lg-12"> 


                                       <i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" style="font-size: 22px;float: right"></i>
                                       <div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width:400px">
                                        <div class="modal-header">
                                          <h4 class="modal-title">Filter</h4>
                                          <button type="button" class="close mysunless_filter_btn" data-dismiss="modal">×</button>
                                      </div>
                                      <div class="modal-body">

                                        <div class="form-group" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%;    border-radius: 5px;">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <input type="text" name="mydaterang" id="mydaterang" style="border: 0;width: 90%;"><i class="fa fa-caret-down"></i>
                                        </div>
                                        <div class="form-group">
                                            <select id="SubscriberID" name="Subscriber" class="select2" data-placeholder="Select User">
                                                <?php
                                                if($_SESSION['usertype']=='Admin'){ 
                                                    $query = $db->prepare("select * from users where usertype<>'employee' order by username ");                                                    
                                                }else{
                                                    $query = $db->prepare("select * from users where id=:id or adminid=:id");
                                                    $query->bindParam(":id",$_SESSION['UserID']);
                                                }
                                                    $query->execute();
                                                    $result = $query->fetchAll();
                                                    echo "<option value=''></option>";
                                                    foreach ($result as $key => $value) {
                                                        echo "<option value='".$value['id']."' >".$value['username']."</option>";
                                                    }

                                                ?>
                                            </select>
                                        </div>

                                        <?php if($_SESSION['usertype']=='Admin'){ ?>
                                            <div class="form-group">
                                            <select id="EmployeeID" name="Employee" class="select2" data-placeholder="Select Employee" multiple="multiple">
                                               >
                                            </select>
                                        </div>
                                        <?php } ?>

                                        <div class="col-lg-12"  >
                                            <button type="submit" class="btn btn-sm waves-effect waves-light btn-info" name="add-client" id="add-client">Report</button>
                                            <button type="submit" class="btn btn-sm waves-effect waves-light btn-danger" name="add-client" id="add-client-all">Reset</button>    
                                        </div>    
                                    </div>
                                </div>

                                <div class="table-responsive">
                                 <table id="LoginDetailTable" class="table table-bordered table-striped dataTable no-footer">
                                   <thead>
                                       <tr>
                                           <th></th>
                                           <th>User Info</th>
                                           <th>Login Time</th>
                                           <th>Logout Session</th>
                                           <th>RunTime</th>
                                           <th>IP</th>
                                           <th>Device</th>
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

        $(document).on('click', '#reportrange', function(){
            $(".daterangepicker").addClass("show-calendar");
        });

        $(".select2").select2({
          allowClear: true
        });

        <?php if($_SESSION['usertype']=='Admin'){ ?>

          $("#SubscriberID").change(function(){
            var id = $(this).val();
            $.ajax({
              url:"?GET_EMP="+id,
              dataType:'json',
              success:function(data){

                var EmpHtml = "";
                var Employee = data.Employee;
                for(i in Employee){
                  EmpHtml+='<option value="'+Employee[i].id+'">'+Employee[i].username+'</option>';
                }
                $("#EmployeeID").html(EmpHtml);

              }
            });

          });

        <?php } ?>

      $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {

            $("#mydaterang").val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $("#mydaterang").trigger("change");
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            maxDate: new Date(),
            ranges: {
             'Today' : [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         }
     }, cb);

        cb(start, end);

    });

      var Filter = {Date:"",SubscriberID:"",EmployeeID:""};

      $("#mydaterang").change(function(){
        Filter.Date = $("#mydaterang").val();
        console.log($("#mydaterang").val());
      });

      $("#SubscriberID").change(function(){
        Filter.SubscriberID = $(this).val();
      });

      $("#EmployeeID").change(function(){
        Filter.EmployeeID = $(this).val();
      });


      $("#add-client").click(function (){
        UserLoginDataTable(Filter);
    });
      $("#add-client-all").click(function (){
        $("#mydaterang").val('');
        $("#SubscriberID").val(null).change();
        $("#EmployeeID").val(null).change();
        UserLoginDataTable();
        
    });

      setTimeout(function(){ $("#add-client-all").trigger("click");  }, 50);

      function UserLoginDataTable(Filter={}){
        Filter = JSON.stringify(Filter);
         $('#LoginDetailTable').DataTable({
            "processing" : true,
            "destroy": true,
            "order": [[ 0, 'desc' ]],
            "ajax" : {
                "url" : "?LoginDetail&Filter="+Filter,
                dataSrc : ''
            },
            "autoWidth": false,
            "columnDefs": [
            {"className" : 'text-center', "targets" : '_all'},
             {
              "targets": [ 0 ],
              "visible": false,
              "searchable": false
            },
            { "width": "15%", "targets": 3 },
            { "width": "15%", "targets": 2 },
            ],
            "columns" : [
            {
                "data":"id"
            },
            {
                "data": {username : "username", userimg : "userimg"}, 
                "render": function(data, type, row) {
                    if(data.userimg){
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ;?>/assets/userimage/'+data.userimg+'" style="height: 50px; width: 50px;" class="img-circle " /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>';
                    }
                    else
                    {
                        return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><img src="<?php echo $base_url ; ?>/assets/images/'+'noimage.png'+'" style="height: 50px; width: 50px;" class="img-circle" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize; padding: 0 5px;">'+data.username+'</span></div></div>'
                    }
                }
            },
            {
                "data" : "LoginTime",
                "render" : function(data,type,row){
                var date =  moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                var UTCdate =  moment(data).format('YYYY-MM-DD HH:mm:ss');
                return "<span>Local: </span>"+UTCdate+"<br><span>UTC: </span>"+date;
              }
            },
            {
                "data" : "LogoutTime",
                "render" : function(data,type,row){
                    if(data){
                        date =  moment.utc(data).local().format('YYYY-MM-DD HH:mm:ss');
                        UTCdate =  moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }else{
                        date = "";
                        UTCdate = "";
                    }
                    return "<span>Local: </span>"+UTCdate+"<br><span>UTC: </span>"+date;
              }
            },
            {
                "data" : "RunTime",
                "render" : function(data,type,row){
                return moment.utc(parseInt(data)*1000).format('HH:mm:ss');  
              }
            },
            {
                "data" : "REMOTE_ADDR",
                "render" : function(data,type,row){
                return '<a href="javascript:void(0)" class="Location" >'+data+'</a>';
              }
            },
            {
                "data" : "HTTP_USER_AGENT",
                "render" : function(data,type,row){
                return data;  
              }
            }   
            ],
        });
     }


$(document).on('click','.Location',function(){
    ip = $(this).text();

    $.ajax({
        url:"?GET_LOCATION="+ip,
        dataType:"JSON",
        success:function(data){
            console.log(data)
            if(data.error){
                swal(data.error.title,data.error.message,'error');
            }else{
                window.open("https://www.google.com/maps/place/"+data.loc,"_blank");
            }

        }
    });
});


 });



</script>
</body>
</html>