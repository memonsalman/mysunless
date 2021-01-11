<?php 


require_once('global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: index.php");die;
}
if ($_SESSION['usertype']!="Admin")
{
	header("Location: index.php");die;    
}

if(isset($_REQUEST['delid']))
{
	$mypackage=base64_decode($_REQUEST["delid"]);

	$DeleteClient = $db->prepare("delete from `package` where id=:mypackage");
	$DeleteClient->bindValue(":mypackage",$mypackage,PDO::PARAM_INT);
	$DeleteClient->execute();
	if($DeleteClient)
	{
		echo  json_encode(["resonse"=>'Package Successfully Remove From List']);die;
	}
	else
	{
		echo  json_encode(["error"=>'Someting wrong please try agine']);die;
	}
}


if(isset($_REQUEST['eidtid']))
{
	$mypackage=base64_decode($_REQUEST["eidtid"]);
	$editproducts = $db->prepare("select * from `package` where id=:mypackage");
	$editproducts->bindValue(":mypackage",$mypackage,PDO::PARAM_INT);
	$editproducts->execute();
	$editproducts=$editproducts->fetch();


	if(!empty($editproducts))
	{
		echo  json_encode(["resonse"=>$editproducts]);die;
	}
	else
	{
		echo  json_encode(["error"=>'No Data found']);die;
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link href="<?php echo base_url ?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/css/bootstrap-toggle.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url?>/assets/chartjs/Chart.css">
<script type="text/javascript" src="<?= base_url?>/assets/chartjs/Chart.js"></script>
<style type="text/css">
	.cutbttofoma{margin: 3px;}
	th {font-weight: bold!important;color: #0b59a2!important;}

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
								Package List
							</h4>
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
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="fas fa-box">
											</i></span> <span class="hidden-xs-down">Package</span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#packageHistory" role="tab"><span class="hidden-sm-up"><i class="fas fa-box">
											</i></span> <span class="hidden-xs-down">Package Log</span></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#PackageSellGraph" role="tab"><span class="hidden-sm-up"><i class="fas fa-box">
											</i></span> <span class="hidden-xs-down">Package Sells Graph</span></a>
										</li>
									</ul>
									<div class="tab-content tabcontent-border">
										<div class="tab-pane active" id="home" role="tabpanel">
											<div class="col-lg-12">
												<button id="addnewsubpack" class="btn waves-effect waves-light btn-info m-r-10 mt-2" data-toggle="modal" data-target="#myModal">Add New Package</button>
												<a href="<?= base_url?>/paymentsetup" target="_blank" class="btn waves-effect waves-light btn-info m-r-10 mt-2">Payment Setup</a>
												<div>
													<small>Information:</small>
													<br>
													<small>1. Default column: It is use for make a default package that will 
													use while registration.</small>
												</div>
												<div class="table-responsive">
													<table id="myTable" class="table table-bordered table-striped dataTable no-footer">
														<thead>
															<tr>
																<th>
																	Package Name
																</th>
																<th>
																	Price
																</th>
																<th>
																	Validity Days
																</th>
																<th>
																	Employee Limit
																</th>
																<th>
																	Clients Limit
																</th>
																<th>
																	Action
																</th>
																<th>
																	Status
																</th>
																<th>
																	Default
																</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>

											<div class="modal fade" id="myModal" role="dialog">
												<div class="modal-dialog">
													<!-- Modal content-->
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title">Package</h4>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<div class="modal-body">

															<form class="form-horizontal" autocomplete="off" id="NewPackage" method="post">
																<input type="hidden" name="id" id="id" value="">
																<input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
																<div class="form-group">
																	<label><span class="help">Package Name *</span></label>
																	<input type="text" name="PackageName" id="PackageName" class="form-control" value="" placeholder="Package Name">
																</div>
																<div class="form-group">
																	<label><span class="help">Price *</span></label>
																	<div class="input-group mb-3">
																		<div class="input-group-prepend">
																			<span class="input-group-text">$</span>
																		</div>
																		<input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="Price" id="Price" value="" placeholder="Price">

																	</div>
																</div>
																<div class="form-group">
																	<label><span class="help">Employee Limit *</span></label>
																	<input type="text" name="employeeLimit" id="employeeLimit" class="form-control" value="" placeholder="Employee Limit">
																	<small>Type 'full' for unlimited Employees</small>
																</div>
																<div class="form-group">
																	<label><span class="help">Clients Limit *</span></label>
																	<input type="text" name="ClientsLimit" id="ClientsLimit" class="form-control" value="" placeholder="Clients Limit">
																	<small>Type 'full' for unlimited Clients</small>
																</div>
																<div class="form-group">
																	<label><span class="help">Validity Days*</span></label>
																	<input type="text" name="ValidityDay" id="ValidityDay" class="form-control" value="" placeholder="No. of days">
																</div>
																<div class="form-group">
																	<label><span class="help">Package Description *</span></label>
																	<textarea rows="4" name="packagedesc" id="packagedesc" class="form-control"></textarea>
																	<small>1. Please add all the details of the package including validity days. This description will be shown on the Invoice.</small>
																	<br>
																	<small>2. Package will override the existing package.</small>
																</div>
																<div class="modal">
																</div>
																<div class="form-group">
																	<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="AddnewPackage"><i class="fa fa-check"></i> Submit Package</button>
																	<button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel Package</button>
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
															</div>

														</div>
													</div>

												</div>
											</div>
										</div>

										<div class="tab-pane p-20" id="packageHistory" role="tabpanel">

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
															<small>Date of Buy</small>
														</div>

														<div class="form-group">
															<select class="form-control select2" data-placeholder="Choose User" id="mypackagetabale_Emp_filter" multiple="multiple">
																<?php 
																if($_SESSION['usertype']=='Admin'){
																	$query= $db->prepare("SELECT * from users where usertype='subscriber' ");
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
															<select class="form-control select2" data-placeholder="Choose Package" id="mypackagetabale_package_filter" multiple="multiple">
																<?php 

																$query= $db->prepare("SELECT DISTINCT PackageType FROM `payments`");
																$query->execute();
																$result = $query->fetchAll();
																foreach ($result as $key => $value) {
																	echo '<option value="'.$value["PackageType"].'" >'.$value["PackageType"].'</option>';
																}

																?>
															</select>
														</div>


														<div class="form-group">
															<select class="form-control select2" id="mypackagetabale_payment_status" data-placeholder="Payment Status" >
																<option value=""></option>
																<option value="Active">Active</option>
																<option value="InActive">InActive</option>

															</select>
														</div>

														<div class="form-group">
															<span id="reportrange_clear" class="btn btn-sm btn-danger">Reset</span>
														</div>


													</div>
												</div>
											</div>

											<div class="table-responsive">
												<table id="mypackagetabale" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
													<thead>
														<tr>
															<td></td>
															<td>Subscriber</td>
															<td>Detail</td>
															<td>Amount</td>
															<td>Date of Buy</td>
															<td>Date of Expire</td>
															<td>Status</td>
														</tr>
													</thead>
												</table>
											</div>
										</div>

										<?php include('PackageInvoiceModel.php') ?>

										<div class="tab-pane" id="PackageSellGraph" role="tabpanel">
											<?php include('PackageSellGraph.php') ?>
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

		<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/bootstrap-toggle.min.js"></script>
		<!-- <script src="<?php echo base_url ?>/assets/node_modules/switchery/dist/switchery.min.js"></script> -->

		<script src="<?php echo base_url; ?>/assets/js/sweetalert.min.js"></script>

		<script>
			$(document).ready(function() {
				$(".select2").select2();

				setInterval(function(){$(".package").addClass("active");}, 10);
				var table;

				$( "#mypackagetabale_Emp_filter" ).on( 'change', function () {

					var val = $(this).val();
					if (val.length>0) {
						val = val.toString().replaceAll(",","|");
						table.column(1).search(val, true, false).draw();
					} else {
						table.column(1).search("").draw();
					}
				} );

				$( "#mypackagetabale_package_filter" ).on( 'change', function () {

					var val = $(this).val();
					if (val.length>0) {
						val = val.toString().replaceAll(",","|");
						table.column(2).search(val, true, false).draw();
					} else {
						table.column(2).search("").draw();
					}
				} );

				$( "#mypackagetabale_payment_status" ).on( 'change', function () {

					var val = $(this).val();
					if (val.length>0) {
						val = val.toString().replaceAll(",","|");
						table.column(6).search('^'+val+'$', true, false).draw();
					} else {
						table.column(6).search("").draw();
					}
				} );

				$(document).on('click', '#reportrange_clear', function(){

					$("#mypackagetabale_Emp_filter").val('').change();
					$("#mypackagetabale_package_filter").val('').change();
					$("#mypackagetabale_payment_status").val('').change();
					$('#mydaterang').val('');
					mypackagetabale();
				});

				$(document).on('change', '#mydaterang', function(){
					table.draw();
				});

				$.fn.dataTable.ext.search.push(function( settings, data, dataIndex ) {

					var date = $('#mydaterang').val();

					if(settings.nTable.getAttribute('id')!='mypackagetabale' || date==''){
						return true;
					}

					date = date.split('-');
					var from = date[0].trim();
					var to = date[1].trim();
					var createdAt = data[4] || 0; 

					if( ( from == "" || to == "" ) || ( moment(createdAt).isSameOrAfter(from) && moment(createdAt).isSameOrBefore(to) ) 
						)
					{
						return true;
					}
					return false;


				});
				mypackagetabale()
				function mypackagetabale(){
					table = $('#mypackagetabale').DataTable({
						"responsive": true,
						"processing" : true,
						"destroy": true,
						"order": [[ 0, "desc" ]],
						"ajax" : {
							"url" : "<?php echo EXEC; ?>exec-edit-profile?action9=1&subuserid=all",
							dataSrc : ''
						},
						"autoWidth": false,
						"columnDefs": [
						{ "targets" : '_all'},
						{
							"targets": [ 0 ],
							"visible": false,
							"searchable": false
						},
						{ "width": "15%", "targets": 1 },
						{ "width": "35%", "targets": 2 },
						{ "width": "10%", "targets": 3 },
						{ "width": "15%", "targets": 4 },
						{ "width": "15%", "targets": 5 },
						{ "width": "10%", "targets": 6 },
						],
						"columns" : [
						{
							"data":"id",
						},
						{
							"data": {username:"username",userid:"userid"},
							"render": function(data, type, row) {
								var id = btoa(data.userid);
								return "<a href='<?= base_url ?>/AddSubuserid?action=edit&subuserid="+id+"' target='_blank' >"+data.username+"</a>";    
							}
						},
						{
							"data": {InvoiceID:"InvoiceID",PackageType:"PackageType",TransactionID:"TransactionID",id:"id"},
							"render": function(data, type, row) {
								var packageId = window.btoa(data.id);

								text = '<span class="view_sub_invoice" style="cursor:pointer" title="View Invoice" data-id="'+packageId+'">';
								text+= "Package: <b>"+data.PackageType+"</b>";

								if(data.InvoiceID){
									text+='<br>Invoice: <b>'+data.InvoiceID+'</b>';
								}

								if(data.TransactionID){
									text+="<br>Transaction ID: <b>"+data.TransactionID+"</b>";
								}

								text+='</span>';

								return text;

							}
						},
						{
							"data": {amount:"amount"},
							"render": function(data, type, row) {
								return '<span>$'+ data.amount +'</span>';    
							}
						},
						{
							"data" : "paytime",
						},
						{
							"data" : "packend",
						},
						{
							"data" : "status"
						},
						]
					});

				}

				dataTable()
				function dataTable()
				{
					$('#myTable').DataTable({
						"responsive": true,
						"processing" : true,
						"destroy":true,
						"ajax" : {
							"url" : "<?php echo EXEC; ?>Exec_AllPackage.php?data",
							"type":'post',
							"dataSrc" : ''
						}
						,
						"autoWidth": false,
						"columnDefs": [
						{
							"className" : 'text-center', "targets" : '_all'}
							,
							{
								"width": "17%", "targets": 0 }
								,
								{
									"width": "17%", "targets": 1 }
									,
									{
										"width": "17%", "targets": 2 }
										,
										{
											"width": "17%", "targets": 3 }
											,
											{
												"width": "15%", "targets": 4 }
												,
												{
													"width": "17%", "targets": 5 }
													,
													],
													"columns" : [{
														"data" : "PackageName"
													}
													, 
													{
														"data" : {Price:"Price"},
														"render":function(data,type,row)
														{
															return '$'+data.Price;
														}
													}, 
													{
														"data" : "ValidityDay"
													},
													{
														"data" : "employeeLimit"
													}, 
													{
														"data" : "ClientsLimit"
													},
													{
														"data": {id : "id", default_package:'default_package'},
														"render": function(data, type, row) {
															var encodedId = window.btoa(data.id);
															if(data.default_package==0){
																return '<button id="editButton" class="btn btn-info btn-sm cutbttofoma" title="Edit Event" data-id='+ encodedId + '> <span class="fa fa-edit"><span> </button> <button class="btn btn-danger btn-sm cutbttofoma" title="Delete Event" id="deleteButton" data-id='+ encodedId + '> <span class="fa fa-trash"><span> </button> ';

															}else{
																return '<button id="editButton" class="btn btn-info btn-sm cutbttofoma" title="Edit Event" data-id='+ encodedId + '> <span class="fa fa-edit"><span> </button> ';
															}
														}
													}
													,
													{
														"data": {id : "id", isactive : "isactive", default_package:'default_package'}
														,
														"render": function(data, type, row) {
															if(data.default_package==0){
																if (data.isactive == 1 ){
																	return '<input class="toggle_status" id="'+ data.id +'" type="checkbox" checked data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="1" function="isactive">' ;
																}
																else{
																	return '<input class="toggle_status" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0" function="isactive">' ;
																}
															}else{
																return 'Active';
															}
														}
													},
													{
														"data": {id : "id", isactive : "isactive", default_package:'default_package'}
														,
														"render": function(data, type, row) {
															if (data.isactive == 1 ){
																if (data.default_package == 1){
																	return 'Activated' ;
																}else{
																	return '<input class="toggle_status" id="'+ data.id +'" type="checkbox" data-toggle="toggle" data-on="Enable" data-off="Disable" data-onstyle="info" data-offstyle="danger" value="0" function="default_package" >' ;

																}
															}else{
																return 'Deactivated';
															}
														}
													}

													],
													"fnDrawCallback": function() {
														jQuery('.toggle_status').bootstrapToggle();
														jQuery('.toggle_status').parent().addClass('toggleBtn');
													}
												}
												);
				}
        // hooking event only on buttons, can do tr's as well.
        $(document).on('click','.toggleBtn',function(){
        	$.ajax({
        		url: "<?php echo EXEC; ?>Exec_AllPackage.php?status",
        		type: 'post',
        		dataType: 'json',
        		data: {
        			id : $(this).children(".toggle_status").attr("id"),
        			status: $(this).children(".toggle_status").attr("value"),
        			type: $(this).children(".toggle_status").attr("function"),
        		}
        		,
        		success: function(data){
        			if(data.response){
        				swal("",data.response, "success");
        			}else{
        				swal("",data.error, "error");
        			}
        			dataTable()
        		}
        		,
        		error: function(errorThrown) {
        			swal("Sorry!Failed to Update Status","", "error");
        			dataTable()
        		}
        	}
        	);
        });

        $(document).on('click','#deleteButton',function(e){
        	e.preventDefault();
        	swal({
        		title: "Are you sure?",
        		text: "Once deleted, you will lost all data of this Package!",
        		icon: "warning",
        		buttons: true,
        	}).then((willDelete)=>{
        		if (willDelete)
        		{
        			var delid=$(this).attr('data-id');
        			$.ajax({
        				dataType:"json",
        				type:"post",
        				data:{'delid':delid},
        				url:'?action=deletefile',
        				success: function(data)
        				{
        					if(data.resonse){

        						swal(data.resonse);
        						dataTable()
        						$("#myModal").modal('hide');
        					}
        					else if(data.error){

        						swal(data.error);
        					}
        				}
        			});
        		}
        		else{
        			return false ;

        		}
        	}
        	);
        });

        $(document).on('click','#editButton',function(e){   
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
        				$('#myModal').modal('show');
        				$('#PackageName').val(data.resonse.PackageName);
        				$('#Price').val(data.resonse.Price);
        				$('#ValidityDay').val(data.resonse.ValidityDay);
        				$('#employeeLimit').val(data.resonse.employeeLimit);
        				$('#ClientsLimit').val(data.resonse.ClientsLimit);
        				$('#packagedesc').val(data.resonse.packagedesc);
        				$('#id').val(data.resonse.id);
        				$(".Loader").hide();
        			}
        			else if(data.error){
        				$("#error").show();
        				$('#errormsg').html('<span>'+data.error+'</span>');
        				$(".Loader").hide();
        			}
        		}  
        	});

        });             

        $(document).on('click','#addnewsubpack',function(){
        	$('#id').val('new');
        	$('#PackageName').val('');
        	$('#Price').val('');
        	$('#employeeLimit').val('');
        	$('#ClientsLimit').val('');
        });

        $(document).on('keyup','#Price,#ValidityDay',function(){
        	if (/\D/g.test(this.value))
        	{
        		this.value = this.value.replace(/\D/g, '');
        	}
        });

        jQuery.validator.addMethod("field", function(value, element) {

        	if(value=="full"){
        		return true;
        	}else if(isNaN(value)){
        		return false;
        	}else{
        		return true;
        	}
        }, "Please enter a number or 'full'.");


        $("#NewPackage").validate({
        	ignore: ":hidden:not(textarea)",
        	rules: {
        		PackageName: {required: true, maxlength: 255},
        		Price: {required: true,number: true, maxlength: 10},
        		employeeLimit: {required: true,field: true, maxlength: 10},
        		ClientsLimit: {required: true,field: true, maxlength: 11},
        		ValidityDay: {required:true,number:true, maxlength: 11},
        		packagedesc: {required:true}
        	},
        	messages: {
        		PackageName: {required: "Please Enter Package Name"},
        		Price: {required: "Please Enter Package Price"},
        		employeeLimit: {required: "Please Enter Employee Limit"},
        		ClientsLimit: {required: "Please Enter Clients Limit"},
        		ValidityDay: {required: "Please Enter number of Days"},
        		packagedesc: {required: "Please Enter Description of the package."}
        	},
        	submitHandler: function() {
        		$(".Loader").show();
        		var data = $("#NewPackage").serialize();
        		data= data + "&LoginAction=Login";
        		jQuery.ajax({
        			dataType:"json",
        			type:"post",
        			data:data,
        			url:'<?php echo EXEC; ?>Exec_Edit_Package.php',
        			success: function(data)
        			{
        				if(data.resonse)
        				{
        					swal("",data.resonse,"success");
        					$( '#NewPackage' ).each(function(){
        						this.reset();
        					});
        					$(".Loader").hide();
        					dataTable()
        					$("#myModal").modal('hide');


        				}
        				else if(data.error)
        				{
        					swal("",data.error,"error");
        					$(".Loader").hide();
        					$("#myModal").modal('hide');
        				}
        			}
        		});
        	}
        });


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

    });
</script>

</body>
</html>
