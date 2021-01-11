<?php
    require_once('Exec_Config.php');        
	
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.emailsetting.php'); 
$myevent = new event($_POST["id"]);
$myevent->id = $_POST["id"];
$myevent->UserID = $_POST["UserID"];
$fmail=$myevent->fmail = $_POST["fmail"];
$fname=$myevent->fname = $_POST["fname"];
$smtphost=$myevent->smtphost = $_POST["smtphost"];
$smtpport=$myevent->smtpport = $_POST["smtpport"];
$toe=$myevent->toe = $_POST["toe"];
$sa=$myevent->sa = $_POST["sa"];
$smtpusername=$myevent->smtpusername = $_POST["smtpusername"];
$smtppassword=$myevent->smtppassword = $_POST["smtppassword"];
$myevent->commit($myevent->id);
$db = new db();
$id=$_POST["UserID"];
$Gmail_value='';
$Gmail_displayName='';
$Gmail_url='';
$query = $db->prepare("UPDATE users SET 
`Gmail_value`=:Gmail_value,
`Gmail_displayName`=:Gmail_displayName,
`Gmail_url`=:Gmail_url
WHERE id=:id");
$query->bindValue(':Gmail_value',$Gmail_value, PDO::PARAM_STR);
$query->bindValue(':Gmail_displayName',$Gmail_displayName, PDO::PARAM_STR);
$query->bindValue(':Gmail_url',$Gmail_url, PDO::PARAM_STR);
$query->bindValue(':id',$id, PDO::PARAM_STR); 
$query->execute();
if($myevent)
{
    if($_POST["id"]=="new")
    {
        echo json_encode(['resonse'=>'Email setting has been successfully added']);die;   
    }
    else
    {
        echo json_encode(['resonse'=>'Email setting has been Successfully updated']);die;     
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>