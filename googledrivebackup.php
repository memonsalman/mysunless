<?php 
ob_start();
require_once('global.php');
require_once('function.php');

if(empty($_SESSION['last_url_gdrive'])){
	$_SESSION['last_url_gdriv'] = $_SERVER['HTTP_REFERER'];
}

error_reporting(E_ALL);
$db=new db();
$id=$_SESSION['UserID'];
$LoginQuery = $db->prepare("SELECT * FROM `clients` LEFT JOIN MemberPackage ON MemberPackage.id=clients.SelectPackage WHERE clients.createdfk=:id");
$LoginQuery->bindValue(":id",$id,PDO::PARAM_INT);
$LoginQuery->execute();
//$newFileContent="Firstname,Lastname,Phone,Email,Address,Package\n";


$newFileContent="Firstname,Lastname,Phone,email,Solution,PrivateNotes,Address,Zip,City,State,Country,Package\n";

while($row = $LoginQuery->fetch(PDO::FETCH_ASSOC))
{
    //$newFileContent .= $row['FirstName'].",".$row['LastName'].",".$row['Phone'].",".$row['email'].",".$row['Address'].",".$row['Name']."\n";
	
	$newFileContent .= $row['FirstName'].",".$row['LastName'].",".$row['Phone'].",".$row['email'].",".$row['Solution'].",".$row['PrivateNotes'].",".$row['Address'].",".$row['Zip'].",".$row['City'].",".$row['State'].",".$row['Country'].",".$row['Name']."\n";
}
session_start();
$pagename = 'my_sunless_'.date('d_M_y_his').'_'.$_SESSION["UserName"];
$_SESSION["pagename"]='my_sunless_'.date('d_M_y_his').'_'.$_SESSION["UserName"];
$newFileName = './gdrive/files/'.$pagename.".csv";
if (file_put_contents($newFileName, $newFileContent) !== false) {
    echo "File created (" . basename($newFileName) . ")";
    header('Location: ./gdrive');
} else {
    echo "Cannot create file (" . basename($newFileName) . ")";
}
?>