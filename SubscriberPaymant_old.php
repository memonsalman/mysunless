<?php 

require_once('global.php');


require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: ../index.php");die;
}
if($_SESSION['usertype']!="Admin")
{
	header("Location: dashboard.php");die;
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<style type="text/css">
	.img-circle {
		object-fit: cover;
	}
	th{font-weight: bold!important;color: #0b59a2!important;}
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<body class="skin-default fixed-layout mysunlessA14">

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
								Subscription 
							</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<!-- <a href="<?php echo base_url ;?>/MembershipPackageList.php" class="btn btn-info m-r-10 pull-right"> Membership Packages</a> -->
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#UpcomingRenewalsTab" role="tab"> <span class="hidden-xs-down">Upcoming Renewals </span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#CurrentAndPaidTab" role="tab"><span class="hidden-xs-down">Current and Paid </span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#NotPaidTab" role="tab"><span class="hidden-xs-down">Expired</span></a>
										</li>
									</ul>
									<div class="tab-content tabcontent-border">
										<!--  start Upcoming Renewals tab -->
										<small>Note: Date-Format in YYYY-MM-DD</small>
										<div class="tab-pane active" id="UpcomingRenewalsTab" role="tabpanel">
											<div class="col-lg-12 col-md-12 p-2">
												<button class="btn btn-info" id="UpcomingRenewalsTableButton">Select All</button>
												<div class="d-flex align-items-center">
													<select class="custom-select w-25 ml-auto" id="UpcomingRenewalsDays">
														<option value="1">In 1 Day</option>
														<option value="2">In 2 Days</option>
														<option value="3">In 3 Days</option>
														<option value="7">In 7 Days</option>
														<option value="10">In 10 Days</option>
														<option selected value="15">In 15 Days</option>
													</select>
												</div>
												<div class="table-responsive">
													<table id="UpcomingRenewalsTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
														<thead>
															<tr>
																<th></th>
																<th>User Info</th>
																<th>Contact Info</th>
																<th>Package Name</th>
																<th>Start Date</th>
																<th>End Date</th>
																<th>Send</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
										<!--  End Upcoming Renewals tab -->
										<!--  Start Current And Paid tab -->
										<div class="tab-pane" id="CurrentAndPaidTab" role="tabpanel">
											<div class="col-md-12 col-lg-12 p-2">
												<div class="d-flex align-items-center">
													<select class="custom-select w-25 ml-auto" id="CurrentAndPaidDays">
														<option selected value="16">After 16 Days</option>
														<option value="20">After 20 Days</option>
														<option value="25">After 25 Days</option>
														<option value="30">After 1 Month</option>
													</select>
												</div>
												<div class="table-responsive">
													<table id="CurrentAndPaidTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
														<thead>
															<tr>
																<th>User Info</th>
																<th>Contact Info</th>
																<th>Package Name</th>
																<th>Start Date</th>
																<th>End Date</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
										<!--  End Current And Paid tab -->
										<!--  start Not Paid tab -->
										<div class="tab-pane" id="NotPaidTab" role="tabpanel">
											<div class="col-md-12 col-lg-12 p-2">
												<div class="table-responsive">
													<button id="NotPaidTableSubmit" class="btn btn-info">Send</button>
													<div class="progress_box mt-2" id="progress_box" style="display: none;">
														<div class="progress">
															<div id="sendloader" class="progress-bar bg-primary" role="progressbar" style="width: 0%; height: 6px;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="1000"></div>
														</div>
														<span id="CountSend" style="font-size: 20px;"></span>
													</div>
													<table id="NotPaidTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
														<thead>
															<tr>
																<th><input type="checkbox" name="" id="NotPaidTable-select-all"></th>
																<th>User Info</th>
																<th>Contact Info</th>
																<th>Package Name</th>
																<th>Start Date</th>
																<th>End Date</th>
																<th>Send</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
										<!--  End Not Paid tab -->
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
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {

				/*var url = $(location).attr("pathname");*/
                //sidebar drop down
                $(".adminreport").trigger("click");
                /*active class*/
                setInterval(function(){
                	$(".payment").addClass("active");
                }, 10);
                UpcomingRenewalsDataTable(15);
                $('#UpcomingRenewalsDays').change(function(){
                	var UpcomingRenewalsDays = (this).value ;
                	UpcomingRenewalsDataTable(UpcomingRenewalsDays);
                }
                );
                function UpcomingRenewalsDataTable(UpcomingRenewalsDays){
                	$('#UpcomingRenewalsTable').DataTable({
                		"order": [[ 5, 'asc' ]],
                		"processing" : true,
                		"destroy": true,
                		"ajax" : {
                			"type" : "POST",
                			"url" : "<?php echo EXEC; ?>Exec_SubPay.php?UpcomingRenewals="+UpcomingRenewalsDays,
                			"dataSrc" : ''
                		},
                		"columnDefs": [
                		{"className" : 'text-center', "targets" : '_all'},
                		{ "width": "20%", "targets": 1 },
                		{ "width": "30%", "targets": 2 },
                		{ "width": "20%", "targets": 3 },
                		{ "width": "15%", "targets": 4 },
                		{ "width": "15%", "targets": 5 },
                		{ "width": "5%", "targets": 6 },
                		{ "width": "5%", "targets": 0 },
                		],
                		"columns" : [
                		{
                			"data": {UserID:"UserID"},
                			"render":function (data,type,row)
                			{
                				return '<input type="checkbox" value="'+data.UserID+'">';   
                			}
                		},
                		{
                			"data":{ userimg:"userimg",username:"username" },
                			"render": function(data, type, row) {
                				if(data.userimg != '' && data.userimg != null){
                					return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/userimage/'+data.userimg+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';
                				}
                				else
                				{
                					return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';
                				}
                			}
                		},
                		{
                			"data" : {email:"email", phonenumber:"phonenumber"},
                			"render": function(data, type, row) {
                				if(data.phonenumber == "" || data.phonenumber == null)
                				{
                					return '<span class="pull-left"><b>Email: </b>'+data.email;
                				}
                				else
                				{
                					return '<span class="pull-left"><b>Email: </b>'+data.email+'</span><br><span class="pull-left"><b>Phone: </b>'+data.phonenumber+'</span>';
                				}
                			}
                		},
                		{
                			"data": {PackageType:"PackageType"},
                			"render": function(data,type,row)
                			{
                				return data.PackageType;
                			}
                		},
                		{
                			"data": {paytime:"paytime"},
                			"render":function (data,type,row)
                			{
                				return data.paytime;
                			}
                		},
                		{
                			"data": {package_ed:"package_ed"},
                			"render":function (data,type,row)
                			{
                				return data.package_ed;   
                			}
                		},
                		{
                			"data": {UserID:"UserID"},
                			"render":function (data,type,row)
                			{
                				return '<span class="btn btn-sm btn-info fa fa-envelope" data-id="'+data.UserID+'"></span>';   
                			}
                		}
                		]
                	}
                	);
                }
            }
            );
        </script>
        <script type="text/javascript">
        	$(document).ready(function() {
        		CurrentAndPaidDataTable(16);
        		$('#CurrentAndPaidDays').change(function(){
        			var CurrentAndPaidDays = (this).value ;
        			CurrentAndPaidDataTable(CurrentAndPaidDays);
        		});
        		function CurrentAndPaidDataTable(CurrentAndPaidDays){
        			$('#CurrentAndPaidTable').DataTable({
        				"responsive": true,
        				"processing" : true,
        				"destroy":true,
        				"ajax" : {
        					"type" : "POST",
        					"url" : "<?php echo EXEC; ?>Exec_SubPay.php?CurrentAndPaid="+ CurrentAndPaidDays,
        					'dataSrc' : ''
        				},
        				"columns" : [
        				{
        					"data":{ userimg:"userimg",username:"username" },
        					"render": function(data, type, row) {
        						if(data.userimg !='' && data.userimg != null){
        							return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/userimage/'+data.userimg+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';
        						}
        						else
        						{
        							return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';       
        						}
        					}
        				}, 
        				{
        					"data" : {email:"email", phonenumber:"phonenumber"},
        					"render": function(data, type, row) {
        						if(data.phonenumber == "" || data.phonenumber == null) 
        						{
        							return '<span class="pull-left"><b>Email: </b>'+data.email; 
        						}
        						else
        						{
        							return '<span class="pull-left"><b>Email: </b>'+data.email+'</span><br><span class="pull-left"><b>Phone: </b>'+data.phonenumber+'</span>';    
        						}  
        					}
        				},
        				{
        					"data": {PackageType:"PackageType"},
        					"render": function(data,type,row)
        					{
        						return data.PackageType;
        					}
        				},
        				{
        					"data": {paytime:"paytime"},
        					"render": function(data,type,row)
        					{
        						return data.paytime;
        					}
        				},
        				{
        					"data": {package_ed:"package_ed"},
        					"render":function(data,type,row)
        					{
        						return data.package_ed;
        					}
        				}
        				]
        			});
        		}
        	}
        	);
        </script>
        <script type="text/javascript">
        	$(document).ready(function() {


        		var NotPaidTable = $('#NotPaidTable').DataTable({
        			"responsive": true,
        			"processing" : true,
        			"order": [[ 5, 'asc' ]],
        			"ajax" : {
        				"type" : "POST",
        				"url" : "<?php echo EXEC; ?>Exec_SubPay.php?NotPaid=",
        				'dataSrc' : ''
        			},
        			"columnDefs": [
        			{
        				'targets': 0,
        				"width": "5%",
        				'searchable': false,
        				'orderable': false,
        				'className': 'dt-body-center',
        			},
        			{"className" : 'text-center', "targets" : '_all'},
        			{ "width": "20%", "targets": 1 },
        			{ "width": "30%", "targets": 2 },
        			{ "width": "20%", "targets": 3 },
        			{ "width": "15%", "targets": 4 },
        			{ "width": "15%", "targets": 5 },
        			{ "width": "5%", "targets": 6 },
        			],
        			"columns" : [
        			{
        				"data": {UserID:"UserID"},
        				"render":function (data,type,row)
        				{
        					return '<input class="NotPaidTableCheckbox" type="checkbox" value="'+data.UserID+'">';   
        				}
        			},
        			{
        				"data":{ userimg:"userimg",username:"username" },
        				"render": function(data, type, row) {
        					if(data.userimg !='' && data.userimg != null)
        					{
        						return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/userimage/'+data.userimg+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';
        					}
        					else
        					{
        						return '<div class="pull-left"> <img src="<?php echo base_url; ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px;" /> <span style="text-transform: capitalize;">'+data.username+'</span> </div>';       
        					}
        				}
        			}, 
        			{
        				"data" : {email:"email", phonenumber:"phonenumber"},
        				"render": function(data, type, row) {
        					if(data.phonenumber == "" || data.phonenumber == null)
        					{
        						return '<span class="pull-left"><b>Email: </b>'+data.email;
        					}
        					else
        					{
        						return '<span class="pull-left"><b>Email: </b>'+data.email+'</span><br><span class="pull-left"><b>Phone: </b>'+data.phonenumber+'</span>';
        					}
        				}
        			},
        			{
        				"data": {PackageType:"PackageType"},
        				"render": function(data,type,row)
        				{
        					return data.PackageType;
        				}
        			},
        			{
        				"data": {paytime:"paytime"},
        				"render": function(data,type,row)
        				{
        					return data.paytime;
        				}
        			},
        			{
        				"data": {packend:"packend"},
        				"render":function(data,type,row)
        				{
        					return data.packend;
        				}
        			},
        			{
        				"data": {UserID:"UserID"},
        				"render":function (data,type,row)
        				{
        					/*return '<span class="btn btn-sm btn-info fa fa-envelope" data-id="'+data.UserID+'"></span>';   */
        					return '<a href="" class="btn btn-sm btn-info fa fa-envelope sendSubscription" data-id="'+data.UserID+'"></a>' ;  
        				}
        			}
        			]
        		});

        		$('#NotPaidTable tbody').on( 'click', '.sendSubscription', function (e) {
        			e.preventDefault();
        			var data_id= $(this).attr('data-id');
        			$.ajax({
        				url:'sendSubscriptionMail.php',
        				type:'post',
        				data: {user_id:data_id},
        				success: function(data){

        					swal("",data, "");

        				}
        			})

        		});
        		var send;
        		$('#NotPaidTable-select-all').on('click', function(){
        			var rows = NotPaidTable.rows({ 'search': 'applied' }).nodes();
        			$('.NotPaidTableCheckbox', rows).prop('checked', this.checked);

        		});

        		$('#NotPaidTable tbody').on('change', '.NotPaidTableCheckbox', function(){
        			if(!this.checked){
        				var el = $('#NotPaidTable-select-all').get(0);
        				if(el && el.checked && ('indeterminate' in el)){
        					el.indeterminate = true;
        				}
        			}
        		});
        		var ret=0;
        		var totalret = 0; 
        		$('#NotPaidTableSubmit').on('click', function(e){
        			e.preventDefault();
        			var data_idArray = [];
        			countret = 0;
        			$("#progress_box").show();


        			NotPaidTable.$('input[type="checkbox"]').each(function(){
        				if(!$.contains(document, this)){
        					if(this.checked){
        						console.log(this.value);
        					}
        				}else{
        					if(this.checked){
        						//console.log(this.value);
        						data_idArray.push($(this).val());
        					}
        				}
        			});
        			totalret = data_idArray.length;
        			
        			$("#CountSend").text("0/"+totalret);
        			//alert(data_idArray);
        			for(i=0; i<data_idArray.length;i++){
        				$.ajax({
        					url:'sendSubscriptionMail.php',
        					type:'post',
        					data: {data_idArray:data_idArray},
        					success: function(data){
        						ret +=1;
        						$("#CountSend").text(ret+"/"+totalret);
        						temp = ret*100/totalret;
        						loader(temp); 

        						if(ret==totalret){
        							$("#sendloader").css('width','0%');
        							swal("",'Mail Successfully sent!!', "");
        							$("#progress_box").hide();
        							$("#CountSend").text("0/0");
        							$('.NotPaidTableCheckbox').prop('checked',false);
        						}
        					}
        				})
        			}


        		});

        		function loader(ret){
        			$("#sendloader").css('width',ret+"%");
        		}

        		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        			$($.fn.dataTable.tables(true)).DataTable()
        			.columns.adjust()
        			.responsive.recalc();
        		});    
        	});
        </script>
    </body>
    </html>