<?php
ini_set('max_execution_time', 300);
// include Imap.Class
include_once('lib/class.imap.php');
require_once('function.php');
$db=new db();
$id=$_SESSION['UserID'];
$EditEvent=$db->prepare("select * from `gmail` where userid=:id");
$EditEvent->bindParam(':id', $id, PDO::PARAM_INT);
$EditEvent->execute();
$GetEvent=$EditEvent->fetch(PDO::FETCH_ASSOC);
$a=$GetEvent['username'];
$b=$GetEvent['gpassword'];
// $a='salmandds7@gmail.com';
//$b='memon00786';
$email = new Imap();
$connect = $email->connect(
    '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX', //host
    $a, //username
    $b //password
);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if($connect){
    if(isset($_POST['inbox'])){
        // inbox array
        $inbox = $email->getMessages('html');
        echo json_encode($inbox, JSON_PRETTY_PRINT);
    }else if(!empty($_POST['uid']) && !empty($_POST['part']) && !empty($_POST['file']) && !empty($_POST['encoding'])){
        // attachments
        $inbox = $email->getFiles($_POST);
        print_r($inbox);
        die();
        echo json_encode($inbox, JSON_PRETTY_PRINT);
    }else {
        echo json_encode(array("status" => "error", "message" => "Not connect."), JSON_PRETTY_PRINT);
    }
}else{
    echo json_encode(array("status" => "error", "message" => "Not connect."), JSON_PRETTY_PRINT);
}