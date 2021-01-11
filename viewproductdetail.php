
<!-- viewproductdetail.php -->
<style>
	.text_bold{
		font-weight: 600;
		margin-right: 5px;
	}
	.Pro_Img_Div .pro-img-overlay {
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0px;
		left: 0px;
		display: none;
		background: rgba(255, 255, 255, 0.8);
		justify-content: center;
		align-items: center;
	}
	.Pro_Img_Div .pro-img-overlay a {
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		height: 60px;
		width: 60px;
		display: inline-block;
		border-radius: 100%;
		text-align: center;
		padding: 12px 0;
		margin: 0 10px;
		font-size: 28px;
		color: #fff;
	}

	.Pro_Img_Div{
		max-width: 450px;
		height: 200px;
		position: relative;
		margin: auto;
	}
	.Pro_Img {
		width: 100%;
		height: 100%;
		object-fit: contain;
	}
	.Pro_detail p {
		font-size: 20px;
		margin: 0;
	}
	.Pro_sell{
		font-size: 30px;
	}
	.Pro_cost{
		text-decoration: line-through;
		font-size: 20px;
	}

</style>
<div id="viewProductDetail" class="modal fade" role="dialog">
	<div class="modal-dialog" style="max-width:1200px;">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Product Detail</h4>
				<div class="Loader"></div>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row p-b-20">
					<div class="col-md-5">
						<div class="Pro_Img_Div">
							<img class="Pro_Img" src="https://mysunless.com/crm/assets/ProductImage/20200605054003.png">

						</div>
					</div>

					<div class="col-md-6 Pro_detail">

						<h2 class='Pro_Title'></h2>
						<span class='Pro_sell text-success'></span>
						
						<?php if($_SESSION['usertype']!='employee'){ ?>
							<span class='Pro_cost text-muted'></span>
						<?php } ?>
						
						<span class="Pro_tax text-danger"></span>
						<input type="hidden" id="Pro_id">
						<p class="text-muted "><span class="text_bold">Barcode:</span><span class="Pro_Barcode"></span></p>
						<p class="text-muted "><span class="text_bold">Category:</span><span class="Pro_Category"></span></p>
						<p class="text-muted "><span class="text_bold">Brand:</span><span class="Pro_Brand"></span></p>
						<p class="text-muted "><span class="text_bold">Stock:</span><span class="Pro_Stock"></span></p>
						<p class="text-muted "><span class="text_bold">Description:</span><span class="Pro_Desp"></span></p>
					</div>
				</div>

				<hr>

				<div class="row" style="padding: 20px;background: ghostwhite;margin: 10px;">

					<div class="col-md-11">
						<h3 class="text_bold text-muted"><i class="fa fa-list-alt" aria-hidden="true"></i> Product Report</h3>
					</div>

					<div class="text-right col-md-1">
						<a title="Product Report Page" target="_blank" href="https://mysunless.com/crm/Product_Sales"><i class="fa fa-share-square btn-info btn btn-circle m-2" style="font-size: 20px;cursor: pointer;"></i>
						</a>
					</div>

					<div class="col-md-6">
						<h4><span class="text-muted">Total Quantity: </span><span class="text_bold  text-info totalqty"></span></h4>
						<h4><span class="text-muted">Total Selling Price: </span><span class="text_bold text-success totalsell"></span></h4>
						<?php if($_SESSION['usertype']!='employee'){ ?>
							<h4 class=""><span class="text-muted">Total Cost Price: </span><span class="text-warning text_bold totalcost"></span></h4>
							<h4><span class="text-muted">Total Profit/Lose: </span><span class="text_bold totalprofit"></span></h4>
						<?php } ?>
					</div>

					<div class="table-responsive">
						<table id="ProductReportTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%!important;">
							<thead>
								<tr>
									<th>User Info</th>
									<th>Customer</th>
									<th>Invoice ID</th>
									<th>QTY</th>
									<th>Order Date</th>
									<th>Sell Price</th>
									<?php if($_SESSION['usertype']!='employee'){ ?>
										<th>Cost Price</th>
										<th>Profit</th>
									<?php } ?>
								</tr>
							</thead>
						</table>
					</div>


				</div>

			</div>

			<div class="modal-footer"></div>

		</div>
	</div>
</div>

<script>

$(document).on('click','#viewProduct,.viewProduct',function(e){  
	e.preventDefault();
	var eidtid=$(this).attr('data-id');
	$(".Loader").show();
	$.ajax({
		dataType:"json",
		url:"<?php echo EXEC; ?>Exec_Edit_Product.php?viewdata2="+eidtid,
		success: function(data)
		{
			sales_tax = data.sales_tax;
			data = data.result;
			if(data.length>0){
				$(".Loader").hide();
				var data = data[0];

				$('.Pro_Img_Div .pro-img-overlay').remove();

				if(data.ProductImage){
					$(".Pro_Img").attr('src',"<?php echo base_url; ?>/assets/ProductImage/"+data.ProductImage);
				}else{
					$(".Pro_Img").attr('src',"<?php echo base_url; ?>/assets/ProductImage/product-default.png");
				}

				$("#Pro_id").val(data.id);
				$('.Pro_Barcode').text(data.barcode);
				$('.Pro_Title').text(data.ProductTitle);
				$('.Pro_sell').text('$'+data.SellingPrice);

				if(data.NoofPorduct>0){
					$('.Pro_Stock').text(data.NoofPorduct+' Left');
					$('.Pro_Stock').addClass('text-success');
					$('.Pro_Stock').removeClass('text-danger');

				}else{
					$('.Pro_Stock').text(0+' Left');
					$('.Pro_Stock').addClass('text-danger');
					$('.Pro_Stock').removeClass('text-success');

				}

				$('.Pro_Desp').html('<br>'+data.ProductDescription);
				$('.Pro_Category').text(data.category);

				var UserID = "<?= $_SESSION['UserID']; ?>";

				if(UserID==data.createdfk){
					$('.Pro_cost').text('$'+data.CompanyCost);
					$('.Pro_Img_Div').append('<div class="pro-img-overlay"><a id="editButton" data-id="'+data.id+'" href="javascript:void(0)" class="bg-info" style="left: 24%;top: 15%;"><i class="ti-marker-alt"></i></a> <a id="deleteButton" data-id="'+data.id+'" href="javascript:void(0)" class="bg-danger" ><i class="ti-trash"></i></a></div>');

				}else{
					$('.Pro_cost').remove();
					$('.Pro_Img_Div').append('<div class="pro-img-overlay"><a id="editStock" data-id="'+data.id+'" href="javascript:void(0)" class="bg-info" ><i class="ti-marker-alt"></i></a><a id="viewProduct" data-id="'+data.id+'" href="javascript:void(0)" class="bg-success" ><i class="ti-eye"></i></a></div>');
				} 

				if(data.Brand){
					$('.Pro_Brand').text(data.Brand);
				}else{
					$('.Pro_Brand').text(' - ');
				}

				if(data.sales_tax==1)
				{	
					$(".Pro_tax").text(parseFloat(sales_tax).toFixed(2)+"% TAX");

				}else{
					$(".Pro_tax").text("0%");

				}


				ProductReportTable(data.id);





				$("#viewProductDetail").modal('show');

			} else{
				$(".Loader").hide();
				swal('','No data found!','error');

			}
		}
	});

});



function ProductReportTable(product){

	var Report = $('#ProductReportTable').DataTable({
		"responsive": true,
		"processing" : true,
		"destroy": true,
		"autoWidth": false,
		"order": [[ 4, 'desc' ]],
		"columnDefs": [
		{"className" : 'text-center', "targets" : '_all'},
		],
		"ajax" : {
			"type" : "POST",
			"url" : "<?php echo EXEC; ?>Exec_prodct_sale_Report?product="+product,
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
		{
			"data": {ProfileImg:"ProfileImg",custname : "custname",clientid:"clientid"},
			"render": function(data, type, row) {
				if(data.ProfileImg != '' && data.ProfileImg != null){
					var encodedId = btoa(data.clientid); 
					return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle " style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.custname +'</span></a> </div></div>';    
				}
				else
				{
					var encodedId = btoa(data.clientid); 
					return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="#" ><img id="viewInfo" data-cid="'+encodedId+'" src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle" style="height: 50px; width: 50px; vertical-align:middle ;" /></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.custname +'</span></a> </div></div>';       
				}
			}
		},
		{
			"data": {InvoiceNumber:"InvoiceNumber"},                
			"render":function (data,type,row){
				return '<a href="#" class="viewProduct" data-id="'+btoa(row.OrderId)+'">'+data.InvoiceNumber+'</a>';
			}
		},

		{
			"data": "ProdcutQuality",                
		},
		{
			"data": {OrderTime:"OrderTime"},                
			"render":function (data,type,row){
				var time = data.OrderTime.replace('am',"");
				time = time.replace('pm',"");
				var time =  moment(time).format('YYYY-MM-DD')
				return time;
			}
		},
		{
			"data": {ProductFianlPrice:"ProductFianlPrice"},
			"render":function (data ,type,row)
			{
				sell = data.ProductFianlPrice.replace(" ","");
				sell = sell.replace("$","");
				return '<span>$'+sell+'</span>';

			}
		},

		<?php if($_SESSION['usertype']!='employee'){ ?>
			{
				"data": {ProductCostPrice : "ProductCostPrice"}, 
				"render": function(data, type, row) {
					return '<span>$'+data.ProductCostPrice+'</span>';
				}
			},	

			{
				"data": {profit:"profit"},
				"render":function(data,type,row)
				{
					return '$'+parseFloat(data.profit).toFixed(2);
				}
			},
		<?php } ?>

		],
	});

	Report.ajax.reload( function ( data ) {

		var totalsell = totalcost = totalprofit = totalqty = 0;

		for(var i=0;i<data.length;i++){

			temp = data[i].ProductFianlPrice;
			temp = temp.replace(" ","");
			temp = parseFloat(temp.replace("$",""));
			totalsell+= temp;

			temp = data[i].profit;
			temp = temp.replace(" ","");
			temp = parseFloat(temp.replace("$",""));
			totalprofit+= temp;

			temp = data[i].ProdcutQuality;
			temp = temp.replace(" ","");
			temp = parseFloat(temp.replace("$",""));
			totalqty+= temp;

			temp = data[i].ProductCostPrice;
			temp = temp.replace(" ","");
			temp = parseFloat(temp.replace("$",""));
			totalcost+= temp;

		}

		$(".totalsell").text('$'+totalsell.toFixed(2));
		$(".totalcost").text('$'+totalcost.toFixed(2));
		$(".totalqty").text(totalqty);

		if(totalsell>=totalcost){
			$(".totalprofit").text('$'+totalprofit.toFixed(2));
			$(".totalprofit").addClass('text-success');
		}else{
			$(".totalprofit").text('-$'+Math.abs(totalprofit.toFixed(2)));
			$(".totalprofit").addClass('text-danger');
		}


	});
}

</script>