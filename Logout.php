<?php
require_once('function.php');
@$username= $_SESSION['UserName'];
@$id=$_SESSION['UserID'];


@$activeuserid=$_SESSION['activeuserid'];
$Lastout=date("Y-m-d H:i:s");
@$a=date_create($_SESSION['LastLogin']); 
$b=date_create($Lastout); 
$diff   = date_diff( $a, $b );
if($diff->h<=0 && $diff->i<=59)
{
$TotalHours="0.0".$diff->i;
}
else
{
$TotalHours=$diff->h.'.'.$diff->i;	
}		

$stmt=$db->prepare("UPDATE ActiveUser set LogoutTime=:Lastout, TotalHours=:TotalHours where id=:activeuserid AND UserId=:id");
$stmt->bindparam(":Lastout",$Lastout);
$stmt->bindparam(":TotalHours",$TotalHours);
$stmt->bindparam(":activeuserid",$activeuserid);
$stmt->bindparam(":id",$id);
$run = $stmt->execute();

$userlogout=session_destroy() ;
if($userlogout)
{
    $myactivite = new Activites();
    $Titile=$myactivite->Titile = $username.' Logout';	
    $myactivite->commit_acitve($Titile);
}
header("Location: ".base_url."/index.php");die;
?>