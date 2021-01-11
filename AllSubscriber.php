<?php 

require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: ../index.php");die;
}

if($_SESSION['usertype'] != "Admin")
{
	header("Location: dashboard.php");die;
}


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>

<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">

<style>
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
      	.lb-outerContainer{width: 100%!important;}
      	.lb-dataContainer{width: 92%!important; margin: unset!important;}

      }
      .cubutfoma{margin: 2px !important;}
      th {font-weight: bold!important;color: #0b59a2!important;}
    </style>
    <style type="text/css">
     #ViewSubscriberInfo,.ViewSubscriberInfo{
      cursor: pointer;
      border-radius: 50%;
    }   
    .ViewSubscriberInfo:hover,#ViewSubscriberInfo:hover{
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

  <body class="skin-default fixed-layout">
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
  								Users List
  							</h4>
  						</div>
  						<div class="Loader"></div>
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
                              <div class="form-group">
                                <select class="form-control select2" id="EventFilterUser" data-placeholder="Choose User" multiple="multiple">
                                  <?php 

                                  $query= $db->prepare("SELECT * from users where usertype='subscriber' ");
                                  $query->execute();
                                  $result = $query->fetchAll();
                                  foreach ($result as $key => $value) {

                                    echo '<optgroup label="'.$value["username"].'">';

                                    $query1= $db->prepare("SELECT * from users where id=:id or adminid=:id");
                                    $query1->bindParam(':id',$value['id']); 
                                    $query1->execute();
                                    $result1 = $query1->fetchAll();
                                    foreach ($result1 as $key1 => $value1) {
                                      echo '<option value="'.$value1["id"].'" >'.$value1["username"].'</option>';
                                    }

                                    echo '</optgroup>';

                                  }

                                  ?>
                                </select>
                              </div>
                              <div class="form-group">
                                <span id="UserFilterSubmit" class="btn btn-sm btn-info mr-2">Submit</span>
                                <span id="UserFilterSubmitClear" class="btn btn-sm btn-danger">Reset</span>
                              </div>
                            </div>
                          </div>
                          <div class="table-responsive m-t-40">
                           <table id="myTable" class="table table-bordered table-striped dataTable no-footer">
                            <thead>
                             <tr>
                              <th>User Info</th>
                              <th>Contact Info</th>
                              <th>Created Date</th>
                              <th>Customer Profile</th>
                              <th>Active</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table>
                        <div class="modal">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="mySubscriberModal"  role="dialog"  aria-hidden="true">
          <div class="modal-dialog modal-xl"  >
           <div class="modal-content">
            <div class="modal-header">
             <h4 class="modal-title">User Overview</h4>
             <button type="button" class="close" data-dismiss="modal">&times;</button>
           </div>
           <div class="modal-body">
             <div class="Loader"></div>
             <div class="subsData"></div>
           </div>
           <div class="modal-footer">
             <div class="form-group" style="margin-bottom: 0px;">
              <button type="submit" class="btn waves-effect waves-light btn-success m-r-10" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
          </div>
        </div>
      </div>
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

  <?php include_once('viewclientdetail.php'); ?>
  <?php include_once('viewuserdetail.php'); ?>

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>

  <script>
   $(document).ready(function() {

    setInterval(function(){$(".allsub").addClass("active");}, 10);
    $(".Loader").show();
    dataTable()

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

    $("#UserFilterSubmit").click(function(){
      dataTable();
    });

    $("#UserFilterSubmitClear").click(function(){
      $('#EventFilterUser').val(null).change();
      dataTable();
    });

    function dataTable()
    {
      user = $('#EventFilterUser').val().toString();
      $('#myTable').DataTable({
        "responsive": true,
        "processing" : true,
        "ordering": false,
        "destroy": true,
        "ajax" : {
         "url" : "<?php echo EXEC; ?>Exec_AllSubscriber.php?Users="+user,
         dataSrc : ''
       }
       ,
       "autoWidth": false,
       "columnDefs": [
       {"className" : 'text-center', "targets" : '_all'},
       {"className" : 'text-left', "targets" : 1},
       ],

       "columns" : [{
        "data":{ userimg:"userimg",username:"username",usertype:"usertype" },
        "render": function(data, type, row) {
          var encodedId = window.btoa(data.id);
          if(data.userimg)
          {
            image = "<?php echo $base_url ?>/assets/userimage/"+data.userimg;
          }
          else 
          {
            image = "<?php echo $base_url ?>/assets/images/noimage.png";
          }
          return '<div><a class="example-image-link" href="#" ><img src="'+image+'" class="img-circle example-image ViewSubscriberInfo" data-uid="'+ encodedId +'" id="ViewSubscriberInfo" style="height: 50px; width: 50px; vertical-align:middle ;" /></a><br><span style="text-transform: capitalize;" > '+ data.username +'</span><br><span style="text-transform: uppercase;" > ('+ data.usertype +')</span> </div>'; 
        }
      }, 
      {
       "data" : {email:"email", phonenumber:"phonenumber"},
       "render": function(data, type, row) {
         return '<span><b>Name: </b>'+row.firstname+' '+row.lastname+'</span><br><span><b>Company Name: </b>'+data.companyname+'</span><br><span><b>Email: </b>'+data.email+'</span><br><span><b>Phone: </b>'+data.phonenumber+'</span>';  

       }
     },
     {
       "data" : {created_at:"created_at"},
       "render": function(data, type, row)
       {
        return data.created_at;
      }
    },
    {
     "data" : "clientc"
   },
   {   
     "data": {id : "id", login_permisssion : "login_permisssion",LastLogin:"LastLogin"},
     "render": function(data, type, row) {

      date1 = new Date();
      date2 = new Date(moment.utc(data.LastLogin).local());
      var res = Math.abs(date1 - date2) / 1000;

      var month = Math.floor(res / (60 * 60 * 24 * 7 * 4));
      var days = Math.floor(res / 86400);
      var hours = Math.floor(res / 3600) % 24;        
      var minutes = Math.floor(res / 60) % 60;
      var seconds = res % 60;

      if(minutes==0 && hours==0 && days==0)
      {
       LastLogin = '<span>'+ seconds.toFixed(0) +' Seconds Ago </span>'; 
     }
     else if(hours<=0 && minutes>0 && days==0)
     {
       LastLogin = '<span>'+ minutes.toFixed(0) +' Minutes Ago </span>';  
     }
     else if(days<=0)
     {
       LastLogin = '<span>'+ hours.toFixed(0) +' Hours Ago </span>';   
     }
     else if(days>=1 && days<=31)
     {
       LastLogin = '<span>'+ days.toFixed(0) +' Days Ago </span>';   
     }
     else if(month>=1)
     {
       LastLogin = '<span>'+ month.toFixed(0) +' Month Ago </span>';   
     }

     else
     {
       LastLogin = '<span> Login Data N/A </span>';     
     }

     if (data.login_permission == 1){
       return '<input class="toggle_status6" data-id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="1"><br>'+LastLogin ;
     }
     else{
       return '<input class="toggle_status6" data-id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-size="mini" data-on="'+"<i class='fa fa-check'></i>"+'" data-off="'+"<i class='fa fa-times'></i>"+'" data-onstyle="info" data-offstyle="danger" value="0"><br>'+LastLogin ;
     }
   }
 },
 {
   "data": "id",
   "render": function(data, type, row) {

    var encodedId = window.btoa(data);
    return '<a class="btn btn-warning btn-sm ViewSubscriberInfo" data-uid="'+ encodedId +'" title="User Overview" href="javascript:void(0)"><span class="fa fa-eye"><span></a> <a class="btn btn-info btn-sm delete cubutfoma" target="_blank" title="Edit Data" href="AddSubuserid.php?action=edit&subuserid='+ encodedId +' "><span class="fa fa-edit"><span></a> <a class="btn btn-success btn-sm cubutfoma" id="switch" title="switch account" data-id='+ encodedId +' style="border-color: #4cae4c;     background-color: #5cb85c; color:white;"><span class="fa fa-sign-in"><span></a> <a class="btn btn-danger btn-sm cubutfoma" title="Delete user" data-id="'+ encodedId +'" id="deleteButton" style="color:white;" ><span class="fa fa-trash"><span></span></span></a>';
  },

}

],
"fnDrawCallback": function() {

 jQuery('.toggle_status6').bootstrapToggle();
 jQuery('.toggle_status6').parent().addClass('toggleBtn6');
  var api = this.api();
  var rows = api.rows({ page: 'current' }).nodes();
  var last=null;
  api.column(4, { page: 'current' }).data().each(function (group, i) {

      if (group.usertype=='subscriber') {
          $(rows).eq(i).before(
              '<tr class="group"><td colspan="8" style="BACKGROUND-COLOR:rgb(122, 191, 191);font-weight:700;color:#006232;">' + 'Subscriber Group: ' +group.username + '</td></tr>'
          );

          last = group;
      }
  });
}
});

}
$(".Loader").hide();

$(document).on('click', '#switch', function(){
	$(".Loader").show()
	var newlogid = $(this).attr("data-id");

	jQuery.ajax({
		dataType:"json",
		type:"post",
		data:{newlogid:newlogid},
		url:'<?php echo base_url ; ?>/All_Script?Adminswitchuser',
		success: function(data) 
		{
			$(".Loader").hide()
			swal("Account successfully switched");
			setTimeout(function () { window.location.href = "dashboard.php";  }, 1000);
		}
	})                  
});

$(document).on('click','#deleteButton',function(e){
	$(".Loader").show();
	e.preventDefault();
	var sid = $(this).attr("data-id");
	swal({
		title: "Temporary Delete?",
		text: "Once deleted, it will move to Archive list!",
		icon: "warning",
		buttons: true,
	}).then((willDelete)=>{   
		if (willDelete){
			
			jQuery.ajax({
				dataType:"json",
				type:"post",
				data:{sid:sid},
				url:'<?= EXEC?>/Exec_AllUsers?deleteuser',
				success: function(data) 
				{
					$(".Loader").hide()
          if(data.resonse){
           swal('',data.resonse,'success');
           dataTable()
         }else{
          swal('',data.error,'error');
        }
      }
    })

		}
		else{
			$(".Loader").hide()
			return false ;
		}
	});
});

$(document).on('click','.toggleBtn6',function(){
	$.ajax({
		url: "<?php echo EXEC; ?>Exec_AllPermission.php",
		type: 'post',
		data: {
			id : $(this).children(".toggle_status6").attr("data-id"),
			status6: $(this).children(".toggle_status6").attr("value")
		},
		success: function(data){
			swal("Status Update Successfully...","", "success");
			dataTable();
		},
		error: function(errorThrown) {
			swal("Sorry!Failed to Update Status","", "error");
			dataTable();
		}
	});
});

$(document).on('click','.ViewSubscriberInfo',function(event){

	
	sid = window.atob($(this).attr('data-uid'));   
	$(".Loader").show(); 
	$(".subsData").html("");
	$.ajax({
		type:"post",
		data:{'sid':sid},
		url:'viewsubscriberinfo',
		success: function(data)
		{
			
			$(".subsData").html(data);
			$(".Loader").hide();
			
		} 
	});
	$('#mySubscriberModal').modal('show');
})
});
</script>
</body>
</html>