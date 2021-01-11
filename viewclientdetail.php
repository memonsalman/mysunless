<?php
require_once('function.php');


$Query= $db->prepare("SELECT * from users where id=:id"); 
$Query->bindParam(':id', $_SESSION['UserID']);
$Query->execute();
$ClientPermission = $Query->fetch();
$ClientCreateData = $ClientPermission['ClientCreate'];

if(isset($_REQUEST['customersid2']))
{
	$customersid2=$_POST['customersid2']; 
	$eidtClient2 = $db->prepare("select * from `clients` where id=:customersid2");
	$eidtClient2->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
	$editfile2=$eidtClient2->execute();
	$all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);

	$gbalance = $db->prepare("select (totalgiftbal-usedbal) As bal from `totalgiftdata` WHERE cid=:customersid2");
	$gbalance->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
	$gbalanc=$gbalance->execute();
	$gbal=$gbalance->fetch();
	if($gbal['bal']){
		$all2['giftbalance'] = $gbal['bal'];
	}else{
		$all2['giftbalance'] = 0;
	}

	$query = $db->prepare("SELECT  COUNT(*) as TotalEvent FROM `event` WHERE cid=:customersid2 and Accepted='1' and (eventstatus='confirmed' or eventstatus='completed' )");
	@$query->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
	$query->execute();
	@$result=$query->fetch();
	if($result['TotalEvent']){
		$all2['TotalEvent']=$result['TotalEvent'];
	}else{
		$all2['TotalEvent']=0;
	}


	$query = $db->prepare("SELECT SUM(OrderPayment.amount) as TotalOrderAmount FROM `OrderMaster` join OrderPayment on OrderPayment.OrderId = OrderMaster.id WHERE OrderMaster.cid=:customersid2 and OrderPayment.payment_status='CAPTURED'");
	@$query->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
	$query->execute();
	@$result=$query->fetch();
	if($result['TotalOrderAmount']){
		$all2['TotalOrderAmount'] = $result['TotalOrderAmount'];
	}else{
		$all2['TotalOrderAmount'] = 0;
	}


	echo  json_encode(["resonse"=>$all2]);die; 
}


?>
<!-- viewclientdetail.php module -->
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/dropify.min.css">
<style>
	#viewInfo,.viewInfo{
		cursor: pointer;
		border-radius: 50%;
	}
	.viewInfo:hover,#viewInfo:hover{
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
	.clientimage {
		height: 150px;
		width: 150px;
		padding: 2px;
		border: 3px outset #607D8B;
		border-radius: 50%;
	}
	.customer_view {
		border-collapse: collapse;
		width: 100%;
	}
	.customer_view, .customer_view td {
		border: 1px solid #ddd;
		text-align: left;
		padding: 15px;
	}
	.separate_address{
		display: none;
	}
	.address_btn{
		position: absolute;
		right: 0;
		padding: 4px;
	}
	.address_btn:hover{
		background: #2196f3;
		color: white;
	}
</style>

<!-- ========================View client============================== -->
<div class="modal hide fade in" id="ViewClientModal" role="dialog" data-keyboard="false" aria-hidden="true">
	<div class="modal-dialog">	
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Client Detail</h4>
				<button type="button" class="close" onclick="$('.Loader').hide();" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body">
				<div>
					<div style="text-align: center;"><img src="" class="clientimage" id="ViewClient_img"></div>
					<div>
						<button class="btn btn-warning btn-sm EditInfo">Edit Profile</button>
						<a href="" target="_black" class="btn btn-info btn-sm" id="visitprofile">Visit Profile</a>
					</div>
					<hr>
					<table class="customer_view">
						<tbody>
							<tr>
								<td style="width: 30%">Customer Name</td>
								<td id="ViewClient_name"></td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td id="ViewClient_PhoneNumber"></td>
							</tr>
							<tr>
								<td>Email</td>
								<td id="ViewClient_Email"></td>
							</tr>
							<tr>
								<td>Created Date</td>
								<td id="ViewClient_Date"></td>
							</tr>
							<tr class="separate_address">
								<td style="position: relative;">Street Address
									<i class="fa fa-expand address_btn" aria-hidden="true"></i>
								</td>
								<td id="ViewClient_StreetAddress"></td>
							</tr>
							<tr class="separate_address">
								<td>City</td>
								<td id="ViewClient_City"></td>
							</tr>
							<tr class="separate_address">
								<td>State</td>
								<td id="ViewClient_State"></td>
							</tr>
							<tr class="separate_address">
								<td>Country </td>
								<td id="ViewClient_Country"></td>
							</tr>
							<tr class="separate_address">
								<td>Zip Code</td>
								<td id="ViewClient_Zip"></td>
							</tr>
							<tr id="full_address">
								<td style="position: relative;">Address
									<i class="fa fa-expand address_btn" aria-hidden="true"></i>
								</td>
								<td id="ViewClient_Full_address"></td>
							</tr>

						</tbody>
					</table>
					<div class="map-box">
						<iframe id="ViewClient_map" src="" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
					<hr>
					<LABEL>Other Details:</LABEL>
					<table class="customer_view">
						<tbody>
							<tr>
								<td style="width: 50%">Number of Appointment Booked</td>
								<td id="ViewClient_app"></td>
							</tr>
							<tr>
								<td>Total Spent</td>
								<td id="ViewClient_purchase"></td>
							</tr>
							<tr>
								<td>Gift Card Balance</td>
								<td id="ViewClient_giftcard"></td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- ========================Edit client============================== -->
<div class="modal fade" id="myModal_viewclient" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="max-width: 1100px;">
		<div class="modal-content">
			<form class="form-horizontal" action="" autocomplete="off" method="post" id="NewClient2">
				<div class="modal-header">
					<h4 class="modal-title">Customer Details</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">

					<div class="Loader"></div>


					<input type="hidden" name="id" id="cid" value="new">
					<input type="hidden" name="clinetid" id="" value="">
					<!-- <input type="hidden" name="newlistofSubscriber2" id="newlistofSubscriber2" value=""> -->
					<?php 
					if($usertype=='subscriber')
					{
						?>
						<input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
						<?php
					}
					else
					{
						?>
						<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
						<?php
					}
					?>

					<div class="customersdetalisone">


						<div class="form-group">
							<label for="example-email">Profile Photo<span class="help"></span></label>
							<div class="card">
								<div class="card-body">
									<input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-allowed-file-extensions='["png", "jpg","jpeg"]' width="80%" style="margin: auto;" data-default-file="">
									<input type="hidden" name="ProfileImg" id="oldimage" value="">
									<input type="hidden" id="ProfileImg2view" name="ProfileImg2" class="">
									<input type="hidden" id="ProfileImg3view" name="ProfileImg3" class="">
								</div>
							</div>
						</div>

						<button type="button" class="btn btn-default dropdown-toggle" id="addcusomimagebutton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 45%;margin-bottom: 20px; margin-left: 10px;"> Select Avatar<span class="glyphicon glyphicon-chevron-down"></span></button>

						<style type="text/css">
							.radio { 
								position: absolute;
								opacity: 0;
								width: 0;
								height: 0;
							}
							.radio + img {
								cursor: pointer;
							}
							.radio:checked + img {
								outline: 2px solid #f00;
							}

							</style>
							<div class="dropdown-menu" style="">
								<?php
								$stmta= $db->prepare("SELECT * FROM `listofavtar` order by id DESC"); 
								$stmta->execute();
								$stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
								foreach($stmtall as $row)
								{
									?>
									<label style="padding: 5px;">
										<input type="radio" class="radio" name="ProfileImg" value="<?php echo $row['Name']; ?>">
										<img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px">
									</label>
									<?php

								}
								?>
							</div>

						</div>


						<div class="customersdetalistwo">

							<?php 
							 if(isset($_SESSION['usertype']) && $_SESSION['usertype']=="Admin"){
							 	$qry= $db->prepare("SELECT * FROM `users` where usertype='subscriber'  "); 
								$qry->execute();
								$qryll = $qry->fetchAll();
							 	?>
							<div class="form-group">
								<label><span class="help"> Subscriber</span></label>
								<select name="newlistofSubscriber2" id="newlistofSubscriber2" class="form-control select2">
								<?php 
								foreach($qryll as $row)
								{
									echo '<option value="'.$row["id"].'" >'.$row["username"].'</option>';
								}?>
							</select>

							</div>
							<? } ?>

							<div class="form-group">
								<label><span class="help"> First Name</span></label>
								<input type="text" name="FirstName" id="FirstNameview" class="form-control" placeholder="First Name" autocomplete="nope" value="">
							</div> 

							<div class="form-group">
								<label><span class="help"> Last Name</span></label>
								<input type="text" name="LastName" id="LastNameview" class="form-control" autocomplete="nope" value="" placeholder="Last Name">
							</div>

							<div class="form-group">
								<label><span class="help"> Phone Number</span></label> 
								<input type="number" autocomplete="nope"  name="Phone" id="phonenumberview"  class="form-control" value="" placeholder="1234567890">
							</div>

							<div class="form-group">
								<label for="example-email">Email <span class="help"></span></label>
								<input type="email" id="example-emailview" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com">
							</div>
						</div>

						<div class="customersdetalistree">
							<div class="form-group">

								<label for="example-email">Street Address</label>

								<input id=""  placeholder="Enter your address"  class="form-control" name="Address" type="text" value=""></input>
								<input type="hidden" value="" placeholder="Enter your address"   id="street_number" disabled="true"></input>
								<input type="hidden" value="" placeholder="Enter your address" id="route" disabled="true"></input>  

							</div>

							<div class="form-group">
								<label for="country">Country</label>

								<input type="text" disabled="" class="form-control" value="United States">
							</div>

							<div class="form-group">
								<label><span class="help">State</span></label>
								<input type="text" class="form-control" id="administrative_area_level_1view" placeholder="State" autocomplete="nope" name="State">

							</div>

							<div class="cutomercityandzip">
								<div class="cumtercity"> 
									<div class="form-group">
										<label for="example-email">City</label>
										<input  id="localityview" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
									</div>
								</div>

								<div class="cumterzip">
									<div class="form-group">
										<label for="example-email">Zip Code</label>
										<input type="number"  id="postal_codeview" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="12345"></input>
									</div>
								</div>

							</div>

						</div>
						<div class="clearfix" style="clear: both;"></div>

					</div>
					<div class="modal-footer">
						<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" autocomplete="nope" name="add-client" id="add-client"><i class="fa fa-check"></i>  Save Customer </button>
					</div>
				</form>
			</div>

		</div>
	</div>

	<!-- ========================Crop image============================== -->
	<div id="uploadimageModal" class="modal fade" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Upload & Crop Image</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<div id="image_demo"></div>
						</div>
						<div class="col-md-12" style="text-align: center;">
							<br />
							<br />
							<br/>
							<button class="btn btn-success crop_image">Crop & Upload Image</button>
							<button type="button" class="btn btn-default crop_image" data-dismiss="modal"> Skip </button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- ========================Crop image============================== -->
	<script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
	<script src="<?php echo base_url; ?>/upload-and-crop-image/croppie.js"></script>
	<script>

		<?php if($ClientCreateData!=1){ ?>
			$('#EditInfo,.EditInfo').hide();
		<?php } ?>



		$('.dropify').dropify();
		$(document).ready(function(){

			$(".address_btn").click(function(){
				$(".address_btn").toggleClass('fa-compress');
				$("#full_address").toggle();
				$(".separate_address").toggle();
			});


			$(document).on('click','.AddNewClient',function(){

				<?php if($ClientCreateData!=1){ ?>
					swal('No Permission!','Please contact your administrator','error');
					return false;
				<?php } ?>

				var daf = Math.floor(Math.random() * 40) + 1  

				$('#FirstName').val('')
				$('#cid').val('new')
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

				$("#myModal_viewclient").modal('show');

			});

			$(document).on('click','#viewInfo,.viewInfo',function(event){
				event.preventDefault();
				var customersid = $(this).attr('data-cid');
				var customersid2 = atob(customersid);
				$.ajax({
					dataType:"json",
					type:"post",
					data: {'customersid2':customersid2},
					url:'<?= base_url?>/viewclientdetail.php?action=editfile',
					success: function(data)
					{
						if(data.resonse)
						{ 	
							$('#ViewClientModal').find("#visitprofile").attr('href','<?= base_url?>/ViewClient?action=view&id='+btoa(data.resonse.id));
							$('#ViewClientModal').find('.EditInfo').attr('data-cid',btoa(data.resonse.id));
							$('#ViewClientModal').find("#ViewClient_StreetAddress").text(data.resonse.Address);
							$('#ViewClientModal').find('#ViewClient_name').text(data.resonse.FirstName+' '+data.resonse.LastName)
							$('#ViewClientModal').find('#ViewClient_PhoneNumber').text(data.resonse.Phone)
							$('#ViewClientModal').find('#ViewClient_Email').text(data.resonse.email)
							$('#ViewClientModal').find('#ViewClient_Date').text(moment.utc(data.resonse.datecreated).local().format('YYYY-MM-DD HH:mm:ss'))
							$('#ViewClientModal').find('#ViewClient_Zip').text(data.resonse.Zip)
							$('#ViewClientModal').find('#ViewClient_Country').text(data.resonse.Country) 
							$('#ViewClientModal').find('#ViewClient_State').text(data.resonse.State)
							$('#ViewClientModal').find('#ViewClient_City').text(data.resonse.City)


							$('#ViewClientModal').find("#ViewClient_purchase").text("$"+parseFloat(data.resonse.TotalOrderAmount).toFixed(2));
							$('#ViewClientModal').find("#ViewClient_giftcard").text("$"+parseFloat(data.resonse.giftbalance).toFixed(2));
							$('#ViewClientModal').find("#ViewClient_app").text(data.resonse.TotalEvent);

							var full_address = data.resonse.Address+", "+data.resonse.City+", "+data.resonse.State+", "+data.resonse.Zip+"  "+data.resonse.Country;

							$('#ViewClientModal').find('#ViewClient_Full_address').text(full_address);

							$('#ViewClientModal').find('#ViewClient_map').attr('src','https://maps.google.com/?q='+full_address+'&output=embed');

						if(data.resonse.ProfileImg){
							$('#ViewClientModal').find('#ViewClient_img').attr('src','<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg);
						}else{
							$('#ViewClientModal').find('#ViewClient_img').attr('src','<?php echo base_url; ?>/assets/images/noimage.png');
						}
						$("#ViewClientModal").modal('show');
					}
					else
					{
						swal('','Something went wrong. Please refresh the page.','error');
					}
				}
			});

			});

			$(document).on('click','#EditInfo,.EditInfo',function(event){

				<?php if($ClientCreateData!=1){ ?>
					swal('No Permission!','Please contact your administrator','error');
					return false;
				<?php } ?>

				$("#NewClient2").find('.dropify-render').text('');
				$("#NewClient2").find('.dropify-preview').show();

				$("#NewClient2").find('#clid').val('');
				$("#NewClient2").find('.dropify-filename-inner').text('')
				event.preventDefault();
				var customersid = $(this).attr('data-cid');
				var customersid2 = atob(customersid)

				$.ajax({
					dataType:"json",
					type:"post",
					data: {'customersid2':customersid2},
					url:'<?= base_url?>/viewclientdetail.php?action=editfile',
					success: function(data)
					{
						if(data.resonse)
						{ 
							$("#NewClient2").find("input[name='Address']").val(data.resonse.Address);
							$("#NewClient2").find('#FirstNameview').val(data.resonse.FirstName)
							$("#NewClient2").find('#cid').val(data.resonse.id)
							$("#NewClient2").find('#LastNameview').val(data.resonse.LastName)
							$("#NewClient2").find('#phonenumberview').val(data.resonse.Phone)
							$("#NewClient2").find('#example-emailview').val(data.resonse.email)
							$("#NewClient2").find('#newlistofSubscriber2').val(data.resonse.createdfk).trigger('change')

							$("#NewClient2").find('#postal_codeview').val(data.resonse.Zip)
							$("#NewClient2").find('#countryview').val(data.resonse.Country) 
							$("#NewClient2").find('#administrative_area_level_1view').val(data.resonse.State)
							$("#NewClient2").find('#localityview').val(data.resonse.City)
							$("#NewClient2").find('#oldimage').val(data.resonse.ProfileImg)
							if(data.resonse.ProfileImg !== '' && data.resonse.ProfileImg !== null)
							{
								$("#NewClient2").find(".dropify-clear").show();
								$("#NewClient2").find("#ProfileImgview").attr("src", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
								$('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
								$("#NewClient2").find('.dropify-filename-inner').text(data.resonse.ProfileImg)

							}
							else if(data.resonse.ProfileImg == '' && data.resonse.ProfileImg == null)
							{
								$("#NewClient2").find("#ProfileImgview").attr("src",$('this #image_id').attr('src'));
							}
							$("#myModal_viewclient").modal('show');
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


			$("#NewClient2").validate({
				rules: {                
					FirstName: {required: true,maxlength:50},
					LastName: {required: true,maxlength:50},
					Phone: {required: true,maxlength:20},
					email: {required: true,maxlength:100},
					Address: {required: true},
					Zip: {required:true,number:true,minlength:5,maxlength:11},
					City: {required: true,maxlength:25},
					State: {required: true,maxlength:25},
					Country:{required: true,maxlength:25},
					newlistofSubscriber2: "required",

				},
				messages: {             
					FirstName:  {required:"Please enter firstName"},
					LastName:  {required:"Please enter lastName"},
					Phone:  {required:"Please enter phone number"},
					email:  {required:"Please enter valid email"},
					Address:  "Please enter address",
					Zip:  {required:"Please enter zipcode",number:"Please enter Numeric value",minlength:"Please enter only 5 digits",maxlength:"Please enter only 5 digits"},
					Country:{required:"Please select country"},
					City:  {required:"Please enter city"}, 
					State:  {required:"Please enter state"},
					newlistofSubscriber: {required: "Please select Subscriber or User"},
				},
				errorPlacement: function( label, element ) {
					if( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  ) {
						element.parent().parent().append( label );
					} 
					else
					{
						label.insertAfter( element );
					}
				},
				submitHandler: function() {
					$(".Loader").show();
					var form = $('#NewClient2')[0];

					var data = new FormData(form);

					jQuery.ajax({
						dataType:"json",
						type:"post",
						data:data,
						contentType: false, 
						processData: false,
						url:'<?php echo EXEC; ?>Exec_Edit_Client',
						success: function(data)
						{

							if(data.resonse)
							{
								swal("",data.resonse,"success");
								$( '#NewClient2' ).each(function(){
									this.reset();
								});
								$(".Loader").hide();
								$("#myModal_viewclient").modal('hide')
								$("#ViewClientModal").modal('hide');
								setTimeout(function () { $("#resonse").fadeOut() }, 2000)

							//Order Page
							$('#listofcatagory').append('<option selected value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
							$('#listofcatagory').select2();

			              	//Event Page
			              	$('#newlistofclient').append('<option selected value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
			              	$('#newlistofclient').select2();


			              	

			              }
			              else if(data.error)
			              {
			              	swal("",data.error,"error");
			              	$(".Loader").hide();
			              	setTimeout(function () { $("#error").fadeOut() }, 3000);
			              }
			              else if(data.csrf_error)
			              {
			              	$("#csrf_error").show();
			              	$('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
			              	$(".Loader").hide();
			              	$("#myModal_viewclient").modal('hide')
			              	setTimeout(function () { window.location.reload() }, 3000)
			              }

			              $("#search_client").trigger('change');
			          }

			      });
				}           
			});
		});

if(typeof $image_crop === 'undefined'){

	$image_crop = $('#image_demo').croppie({
		enableExif: true,
		viewport: {
			width:200,
			height:200,
			type:'circle'
		},
		boundary:{
			width:300,
			height:300
		}
	});
}

$("#NewClient2 .dropify-clear").click(function(e){
	e.preventDefault();
	$(".dropify-preview").hide();

	var data1 = $("#NewClient2 #cid").val();

	data = data1 + "&action5=deleteimage";
	jQuery.ajax({
		dataType:"json",
		url:'<?php echo EXEC; ?>exec-edit-profile?action5',
		type:"post",
		data:{"cimyData2":data1},
		success: function(data) 
		{
			if(data.resonse)
			{
				swal("Client Image is deleted.");
				$("#oldimage").val('');
			}
			else if(data.error)
			{
				swal("","Something went wrong.","error");
			}
		}
	});
}); 

$('#ProfileImg').on('change', function(){
	var reader = new FileReader();
	reader.onload = function (event) {
		$image_crop.croppie('bind', {
			url: event.target.result
		}).then(function(){
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
				$("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/"+data.resonse+"");
				$('<img src="<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/'+data.resonse+'" id="pImage">').appendTo(".dropify-render");
				$('.dropify-filename-inner').text(data.resonse);
				$("#ProfileImg2").val(data.resonse);
				$(".dropify-preview").show();
				$('#uploadimageModal').modal('hide');
			}
		});
	})
});



$('.radio').click(function(){
	if ($(this).is(':checked'))
	{
		var myseletimage = $(this).val()
		var myseletimageurl = '<?php echo base_url; ?>/assets/ProfileImages/'+myseletimage

		if(myseletimageurl!='')
		{
			setTimeout(function(){
				$image_crop.croppie('bind', {
					url: myseletimageurl
				}).then(function(){
				});
			},500);
			

		}
		$('#uploadimageModal').modal('show');	
	}
});

</script>

