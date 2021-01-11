<?php

ob_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once "../src/functions.inc.php";
require_once($_SERVER["DOCUMENT_ROOT"].'/crm/function.php');

$token = skydrive_tokenstore::acquire_token(); // Call this function to grab a current access_token, or false if none is available.
if (!$token) 
{ 

	// If no token, prompt to login. Call skydrive_auth::build_oauth_url() to get the redirect URL.
	// echo "<div>";
	// echo "<img src='statics/key-icon.png' width='32px' style='vertical-align: middle;'>&nbsp";
	// echo "<span style='vertical-align: middle;'><a href='".skydrive_auth::build_oauth_url()."'>Login with SkyDrive</a></span>";
	// echo "</div>";

	header("Location:".skydrive_auth::build_oauth_url());	

	
} else 
{
	$db=new db();
$id=$_SESSION['UserID'];
$LoginQuery = $db->prepare("SELECT * FROM `clients` LEFT JOIN MemberPackage ON MemberPackage.id=clients.SelectPackage WHERE clients.createdfk=:id");
$LoginQuery->bindValue(":id",$id,PDO::PARAM_INT);
$LoginQuery->execute();


$newFileContent="Firstname,Lastname,Phone,email,Solution,PrivateNotes,Address,Zip,City,State,Country,Package\n";
 while($row = $LoginQuery->fetch(PDO::FETCH_ASSOC))
  {
   //$newFileContent .= $row['FirstName'].",".$row['LastName'].",".$row['Phone'].",".$row['email'].",".$row['Address'].",".$row['Name']."\n";

   $newFileContent .= $row['FirstName'].",".$row['LastName'].",".$row['Phone'].",".$row['email'].",".$row['Solution'].",".$row['PrivateNotes'].",".$row['Address'].",".$row['Zip'].",".$row['City'].",".$row['State'].",".$row['Country'].",".$row['Name']."\n";

 }

$pagename = 'my_sunless_'.date('d_M_y_his').'_'.$_SESSION["UserName"];
$_SESSION["pagename"]='my_sunless_'.date('radius_demangle_mppe_key(radius_handle, mangled)');

 $newFileName = 'uploads/'.$pagename.".csv";


if (file_put_contents($newFileName, $newFileContent) !== false) {
        
   	$sd = new skydrive($token);
	@$folderid = $_POST['folderid'];

	$set_url = "https://apis.live.net/v5.0/me/skydrive/files?access_token=".$token;
	$base_url = "https://apis.live.net/v5.0/me/skydrive/".$folderid."/HelloWorld.txt?access_token=".$token;
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>	
	<?php
	
	try {
		$response = $sd->put_file($newFileName);
		// File was uploaded, return metadata.
			$_SESSION["backmsg_one"]='Your client data successfully stored in your One drive account';
    		// header('location : ./logout.php');exit;

	} catch (Exception $e) {
		// An error occured, print HTTP status code and description.

		echo "Error: ".$e->getMessage();
		// header('location : logout.php');exit;
	} 


} else {
    echo "Cannot create file (" . basename($newFileName) . ")";
}

}
require_once "footer.inc.php";
?>