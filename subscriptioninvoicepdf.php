<?php
require_once('function.php');


if(isset($_REQUEST['Orderid'])){
	$Orderid = $_REQUEST['Orderid'];
}

$query = $db->prepare("SELECT users.firstname, users.lastname, users.email, users.phonenumber,users.primaryaddress,users.zipcode,users.city,users.state,users.country, payments.* FROM `payments` join users on payments.userid = users.id where payments.id=:id");
$query->bindParam(':id', $Orderid);
$res = $query->execute();
$result = $query->fetch();


ob_start();
?>
<div style="padding: 10px;;background: #f7f7f7;">
	<div style="width: 100%;">
		<div style="margin-bottom: 20px;color: white;padding: 5px;text-align: center">
			<img src="<?php echo base_url;?>/assets/images/mylog.png" id="compimg" style="width:120px;">
		</div>

		<div style="width: 40%; float: left; padding-bottom:25px;">
			<div id="to">
				<span>
					<h5 style=" background: #4095c7;color: white;padding: 5px;margin-top: 0">
						To:
					</h5>
				</span>
			</div>
			<div>
				<span class="CustomerName"><?= ucfirst($result['firstname'])." ".ucfirst($result['lastname']) ?></span>
			</div>
			<div>
				<span class="CustomerMail"><?= $result['email'] ?></span>
			</div>
			<div>
				<span class="CustomerAdders"><?= $result['primaryaddress'].", <br>".$result['city']."<br>".$result['state'].", ".$result['zipcode']." ".$result['country'] ?></span>
			</div>
			<div>
				<span class="Customerphone"><?= $result['phonenumber'] ?></span>
			</div>
		</div>
		<div style="text-align: right;max-width: 60%;float: right">
			<table align="right">
				<tr>
					<td>Order Date: </td>
					<td class="orderdate" style="text-align:right"><?= $result['paytime'] ?></td>
				</tr>
				<tr>
					<td>Expire Date: </td>
					<td class="orderexpire" style="text-align:right"><?= $result['packend'] ?></td>
				</tr>
				<tr>
					<td>Invoice Number: </td>
					<td class="orderinvoicenumber" style="text-align:right"><?= $result['InvoiceID'] ?></td>
				</tr>
				<tr>
					<td>Transaction Number: </td>
					<td class="orderTransactionID" style="text-align:right"><?= $result['TransactionID'] ?></td>
				</tr>
				<?php if(!empty($result['OrderID'])){ ?>
				<tr>
					<td>Order Number: </td>
					<td class="orderOrderID" style="text-align:right"><?= $result['OrderID'] ?></td>
				</tr>
			<?php } ?>
				<tr>
					<td>Payment API: </td>
					<td class="orderpaymentapi" style="text-align:right"><?= $result['PaymentType'] ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clerfix" style="clear: both;">
	</div>
	<div>
		<table id="carttable" border="1" style="width: 100%;margin:30px 0;border-collapse: collapse;text-align: center;border: 1px solid #c3c3c3;">
			<tr id="order_popup" class="order_popup">
				<td style="background: #2196f3;color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">
					Item
				</td>
				<td style="background: #2196f3;color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">
					Qty
				</td>
				<td style="background: #2196f3;color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">
					Price
				</td>
				<td style="background: #2196f3;color: white;padding: 5px 10px;font-size: 15px;text-transform: UPPERCASE;">
					Description
				</td>
			</tr>
			<tr>
				<td class="orderpackageName" style="padding: 5px 10px;"><?= $result['PackageType'] ?></td>
				<td style="padding: 5px 10px;">1</td>
				<td class="orderpackagePrice" style="padding: 5px 10px;"><?= $result['amount'] ?></td>
				<td class="orderpackagedesc" style="padding: 5px 10px;"><?= $result['packagedesc'] ?></td>
			</tr>
		</table>
	</div>
	<div class="clerfix" style="clear: both;">
	</div>
	<div style="width: 100%">
		<div>
			<div class="notes" id="notes" style=" background: #4095c7;color: white;padding: 5px">
				<h5>
					Other Notes
				</h5>
			</div>
			<div class="notelist">
				<ol type="I">
					<li>
						This is auto computer printed invoice.
					</li>
					<li>
						If you have any problem with this invoice please contact with admin.
					</li>
					<li>
						The goods sold will not be returned.
					</li>
				</ol>
			</div>
		</div>
		<div class="Signature" style="text-align: right; margin-right: 10px; ">
			<span>Signature: MySunless</span>
		</div>
	</div>

	<div style="width: 100%;position: absolute;bottom: 0;">
		<hr>
		<div class="last" style="text-align: center;">
			<h4>
				Thank you MySunless.
			</h4>
		</div>
	</div>
</div>

<?php

$html = ob_get_contents();
ob_end_clean();

if(isset($_REQUEST['PDF'])){


	require_once __DIR__ . '/mpdf/autoload.php';
	$mpdf = new \Mpdf\Mpdf();

	$filename = "Invoice_".date('YmdHis').".pdf";
	$filelocation = $_SERVER["DOCUMENT_ROOT"].'/crm/assets/pdf/'.$filename;

	$mpdf->WriteHTML($html);

	if(isset($_GET['download_pdf'])){
		$mpdf->Output($filelocation,'D');
		unset($filelocation);
		echo "<script>window.close();</script>";die;
	}

	if(isset($_REQUEST['SEND_PDF'])){

		$mpdf->Output($filelocation, 'F');

		$clientname = ucfirst($result['firstname'])." ".ucfirst($result['lastname']);
		$invoice = $result['InvoiceID'];

		$other['--USERNAME--'] = 'To '.$clientname.',<br>Thank you for choosing us as your provider. Please find a detailed copy of your Invoice ('.$invoice.') attached to this email.';

		$headers = '';
		$message="Hi ".ucfirst($result['firstname'])." ".ucfirst($result['lastname']);

		if(isset($_REQUEST['CustomerMail'])){
			$EMAIL = $_REQUEST['CustomerMail'];
		}else{
			$EMAIL = $result['email'];
		}

		$sendinveosudd=sendCMail($EMAIL, "Package Invoice!", "Order.php", $message, $headers, $other,$filelocation);   

		if(isset($_REQUEST['CustomerMail'])){

			if($sendinveosudd===true)
			{
				echo json_encode(['response'=>'Invoice is successful send']);
			}else{
				echo json_encode(['error'=>'Invoice unable send']);
			}
			die;

		}else{

			if($sendinveosudd)
			{	
				$email = $result['email'];
				$email = explode("@",$email);
				$temp_email = str_replace(substr($email[0],(strlen($email[0])/3),strlen($email[0])),"XXXXX",$email[0]).$email[1]; 
				unset($filelocation);
				$_SESSION['viewpackage']='true'; 
				$_SESSION['packagemsg']='You have successfully upgrade your account. We will shortly send you invoice into your '.$temp_email.' mail.';
				header("location:https://mysunless.com/crm/Profile");
			}else{
				$_SESSION['viewpackage']='true'; 
				$_SESSION['packagemsg']='You have successfully upgrade your account.';
				header("location:https://mysunless.com/crm/Profile");
			}
		}

	}
}else{
	echo $html;die;
}
die;
?>