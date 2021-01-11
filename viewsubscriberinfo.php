<?php 
require_once('function.php');
require_once('global.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: ../index.php");die;
}


$query = $db->prepare("SELECT count(clients.id) as totalc ,users.* from ( SELECT users.*,CONCAT(users.firstname,' ',users.lastname) as fullname FROM users WHERE users.adminid=:id or users.id=:id) as users left join clients on users.id = clients.createdfk and clients.isactive=1 GROUP BY users.id");
$query->bindParam(':id', $_POST['sid']);
$query->execute();
$result = $query->fetchAll();
$totalc = 0;

if($query->rowCount()<1){
	echo '<img class="img-fluid m-auto row" src="'.base_url.'/assets/images/empty_client.jpg">';die;
}

foreach ($result as $key => $value) {
	$totalc+=$value['totalc'];
}

?>
<style>
	.display_flex{
		display: flex;
		flex-direction: column;
	}
	.displayr_flex{
		display: flex;
		flex-direction: row;
		justify-content: center;
	}

	.subsData .row{
		margin: 0;
	}
	
	.subsData{
		padding: 10px;
		background: #edf1f5;
	}
	.coin-body{
		text-align: center;
		margin: 0 10px;
	}
	.coin{
		font-size: 28px;
		color: white;
		width: 60px;
		height: 60px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 50%;
		box-shadow: 0px 0px 1px 0px #0000001a;
		cursor: help;
	}
	.coins{
		justify-content: flex-end;
	}
	#client_filter{
		width: 350px;
		margin: 10px;
		position: absolute;
		right: -550px;
		box-shadow: -8px 7px 1px 2px #00000021;
		transition: right 0.5s ease;
		border-width: 0;
		border-right-width: 1px;
		border-bottom-width: 1px;
		z-index: 1;
	}
	.show_client_filter {
		right: 0px!important;
	}

	@media only screen and (max-width: 768px) 
	{
		.coins{
			justify-content: space-between;
		}
	}
</style>

<div class="card" style="width: 100%">	
	<div class="dataTables_filter">		
		<input type="text" placeholder="Search Customer by Name, Email, OR Phone" class="form-control m-0 search_customer">
	</div>
</div>
<?php

foreach ($result as $data) {
	$encodedId = $data['id'];
	$address = $data['primaryaddress'].", ".$data['city'].", ".$data['state'].", ".$data['zipcode']." ".$data['country'];
	$map = str_replace(" ","+",$address);


	$LoginQuery1 = $db->prepare("SELECT COUNT(*) AS clientc FROM clients where isactive=1 and createdfk=:id ");
	$LoginQuery1->bindParam(':id', $encodedId);
	$LoginQuery1->execute();
	$result1 = $LoginQuery1->fetch();

	$LoginQuery5 = $db->prepare("SELECT  COUNT(*) AS empc FROM users WHERE (adminid=:id AND usertype='employee')");
	$LoginQuery5->bindParam(':id', $encodedId);
	$LoginQuery5->execute();
	$result5 = $LoginQuery5->fetch();


	$LoginQuery3 = $db->prepare("SELECT SUM(appc) as appc from (
select count(eventstatus) as appc from event join clients on event.cid=clients.id where Accepted<>'2' and ServiceProvider IN (SELECT id from users where id=:id or adminid=:id) GROUP by eventstatus ) app");
	$LoginQuery3->bindParam(':id', $encodedId);
	$LoginQuery3->execute();
	$result3 = $LoginQuery3->fetch();
	$appc = !empty($result3['appc'])?$result3['appc']:0;

	if($data['usertype']=='subscriber'){
		$LoginQuery = $db->prepare("SELECT  CompanyInformation.* FROM CompanyInformation WHERE  createdfk=:id");
		$LoginQuery->bindParam(':id', $encodedId);
		$LoginQuery->execute();
		$company = $LoginQuery->fetch();
		$addressC = $company['Address'].", ".$company['City'].", ".$company['State'].", ".$company['Zip']." ".$company['Country'];
		$mapC = str_replace(" ","+",$addressC);

		$LoginQuery = $db->prepare("SELECT * FROM `payments` where userid =:id and status='Active'");
		$LoginQuery->bindParam(':id', $encodedId);
		$LoginQuery->execute();
		$package = $LoginQuery->fetch();


		$InfoClass = 'col-md-5';
		$SubscriberClass = "SubsClass";

		$clientc = $result1['clientc'].'/'.$totalc;


	}else{
		$InfoClass = 'col-md-10';
		$SubscriberClass = "";
		$clientc = $result1['clientc'];
	}


	$LoginQuery2 = $db->prepare("select SUM(OrderPayment.amount) as Total FROM `OrderMaster` 
            JOIN `OrderPayment`  ON  OrderMaster.id=OrderPayment.OrderId 
            JOIN clients ON OrderMaster.cid=clients.id 
            join users on OrderMaster.updatedfk = users.id
            WHERE OrderMaster.payment_status='CAPTURED' AND OrderPayment.payment_status='CAPTURED'
            AND ( users.adminid=:id OR users.id=:id ) ");
	$LoginQuery2->bindParam(':id', $encodedId);
	$LoginQuery2->execute();
	$result2 = $LoginQuery2->fetch();
	$total_sales=!empty($result2['Total'])?$result2['Total']:0;
	setlocale(LC_MONETARY,"en_US");
	$total_sales = money_format("%!i", $total_sales);

	$LoginQuery4 = $db->prepare("SELECT *  FROM clients where isactive=1 and createdfk=:id ");
	$LoginQuery4->bindParam(':id', $encodedId);
	$LoginQuery4->execute();
	$result4 = $LoginQuery4->fetchAll();
	
	?>


	<div class="row UserSection <?= $SubscriberClass ?>">		
		
		<div class="card" style="width: 100%">

			<div class="card-body" >
				<div class="row">
					<div class="col-md-8 d-flex">
						<div class="col-md-2 text-center">


							<?php if ($data['userimg']!='') {?>

								<img title="View Preview" src="<?php echo $base_url ?>/assets/userimage/<?php echo $data['userimg'] ?>" class="img-circle example-image ViewUserInfo " data-sid="<?php echo base64_encode($encodedId); ?>" id="ViewUserInfo" style="height: 80px; width: 80px; vertical-align:middle ;" />


							<?php }	
							else 
							{ 
								?>

								<img title="View Preview" src="<?php echo $base_url ?>/assets/images/noimage.png" class="img-circle example-image ViewUserInfo" data-sid="<?php echo base64_encode($encodedId);; ?>" id="ViewUserInfo" style="height: 80px; width: 80px; vertical-align:middle ;" />



							<?php }	 ?>

							<br><span style="cursor: help" title="Registered Date"><i class="fa fa-calendar-plus-o"></i> <?= date('M d,Y',strtotime($data['created_at']))?></span>
						</div>
						<div class="<?= $InfoClass ?> display_flex m-3">
							<h4><ins>User Information</ins></h4>
							<span> <?php echo ucfirst($data['fullname'])." (".$data['username'].")"; ?></span> 
							<span><i class="fa fa-envelope"></i> <?php echo $data['email']; ?></span> 
							<span><i class="fa fa-phone"></i> <?php echo $data['phonenumber']; ?></span> 
							<span><i class="fa fa-map-marker"></i><a href="https://www.google.com/maps/place/<?= $map?>" target="_blank"> <?php echo $address; ?></a></span> 
						</div>
						
						<?php if($data['usertype']=='subscriber'){ ?>
							<div class="<?= $InfoClass ?> display_flex m-3">
								<h4><ins>Company Information</ins></h4>
								<span> <?php echo ucfirst($company['CompanyName']); ?></span> 
								<span><i class="fa fa-envelope"></i> <?php echo $company['email']; ?></span> 
								<span><i class="fa fa-phone"></i> <?php echo $company['Phone']; ?></span> 
								<span><i class="fa fa-map-marker"></i><a href="https://www.google.com/maps/place/<?= $mapC?>" target="_blank"> <?php echo $addressC; ?></a></span> 
								<span><i class="fas fa-box"></i> 
									<?php echo $package['PackageType'].' - '.date('M d,Y',strtotime($package['paytime'])).' (DOP)'; ?></span> 
							</div>
						<?php } ?>

					</div>

					<div class="col-md-4 row coins">
						<div class="coin-body">
							<div class="coin" title="Total Sales" style="background: #4CAF50;">
								<i class="fas fa-dollar-sign"></i>
							</div>
							<span>$<?php echo $total_sales; ?></span>
						</div>
						<div class="coin-body">
							<div class="coin" title="Total Appointment Booked" style="background: #fec107;">
								<i class="fa fa-calendar-check-o"></i>
							</div>
							<span><?php echo $appc; ?></span>
						</div>
						<div class="coin-body">
							<div class="coin" title="Total Clients" style="background: #2a5ae2;">
								<i class="fa fa-id-badge"></i>
							</div>
							<span><?php echo $clientc ?></span>
						</div>

						<?php if($data['usertype']=='subscriber'){ ?>
							<div class="coin-body">
								<div class="coin" title="Total Employees" style="background: #07aefe;">
									<i class="fa fa-users" aria-hidden="true"></i>
								</div>
								<span><?php echo $result5['empc']; ?></span>
							</div>
							<!-- <div class="dataTables_filter" style="width: 92%;">
								<input type="text" placeholder="Search Customer by Name, Email, OR Phone" class="form-control m-0 search_customer">
							</div> -->
						<?php } ?>
					</div>
				</div>

				<?php 

				if($result4!=[])
					{ ?>
						<hr>
						<div class="" style="padding: 15px 10px;box-shadow: inset 0 0 4px 2px #00000021;position: relative;">
							<div class="row" style="max-height: 400px;overflow-y: scroll;">

								<?php 
								foreach ($result4 as $row) {?>
									<div class="col-md-3 card m-0 customer_div" style="text-align: center; align-items: center; padding: 10px;">
										<?php if (isset($row['ProfileImg']) && !empty($row['ProfileImg'])) {?>

											<img title="View Preview" src="<?php echo $base_url ?>/assets/ProfileImages/<?php echo $row['ProfileImg'] ?>" class="img-circle example-image viewInfo " data-cid="<?php echo base64_encode($row['id']); ?>" id="viewInfo" style="height: 80px; width: 80px; vertical-align:middle ;" />

										<?php }
										else {
											?>

											<img title="View Preview" src="<?php echo $base_url ?>/assets/images/noimage.png?>" class="img-circle example-image viewInfo" data-cid="<?php echo base64_encode($row['id']); ?>" id="viewInfo" style="height: 80px; width: 80px; vertical-align:middle ;" />

										<?php } ?>

										<div class="text-center display_flex customer_info">
											<span  style="text-transform: capitalize;"> <?php echo $row['FirstName'].' '.$row['LastName']; ?></span> 
											<span ><i class="fa fa-envelope"></i> <?php echo $row['email']; ?></span>
											<span ><i class="fa fa-phone"></i> <?php echo $row['Phone']; ?></span> 
										</div>
									</div>
								<?php  	} ?>

							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>

	<?php } ?>

	<script>

		$('.select2').select2();

		$(".client_filter_btn").click(function(){
			$("#client_filter").toggleClass('show_client_filter');
		});

		$(".search_customer").keyup(function(){
			var val = $(this).val();
				if(val==''){
					$(".UserSection").show();
					$(".customer_div").show();
					return false;
				}

			$(".subsData .UserSection").each(function(){
				var flag = 1;
				var UserSection = $(this);

				UserSection.find('.customer_div').each(function(){
					var card = $(this);
					var text = $(this).find('.customer_info').text();
					if(text.indexOf(val)<0){
						card.hide();
					}else{
						flag = 0;
						card.show();
					}

				});

				if(flag){
					if(UserSection.hasClass('SubsClass')===false){
						UserSection.hide();
					}
				}else{
					UserSection.show();
				}

			});

		});
	</script>



















