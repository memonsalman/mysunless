<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/sms/twilio-php-master/Twilio/autoload.php');
require_once(Classes.'Class.AllSms.php'); 
use Twilio\Rest\Client;



if( isset($_POST["to"]) && isset($_POST['message']) )
{   

    $to = $_POST["to"];
    $response = '';
    foreach ($to as $key => $value) {
        $value = explode(',',$value);
        $to = $value[0];    
        $cid = $value[1];    
        $return = SendSMS($to,$_POST['message'],$cid);
        $return_array = json_decode($return);
        if(array_key_exists('TwilioSetup', $return_array) || array_key_exists('error', $return_array)  ){
            echo $return;
            die;
        }else{
            $response.=$to." - ".$return_array->response."\n";
        }
    }

    echo json_encode(['response'=>$response]);

}

function SendSMS($to,$message,$cid){

    $db = new db();
    // $id=18; // default

    if(@$_SESSION['usertype']!='Admin'){

        $db = new db();
        $stmt = $db->prepare("SELECT emailstatus FROM `users` where id=:id");
        $stmt->bindParam(':id', $_SESSION['UserID']);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result['emailstatus']==0){
            return json_encode(["error"=>'You have not permission to send an Email/SMS.']);die;
        }

    }

    $id = $_SESSION['UserID'];

    if($_SESSION['usertype']=='employee'){
        $id = $_SESSION['adminid'];
    }


    $stmt2= $db->prepare("SELECT * FROM `smssetting` WHERE createdfk=:id"); 
    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    if($stmt2->rowCount()>0){
        $FromS=$result2['Twillo_from'];
        $sid = $result2['sid'];
        $token = $result2['token'];

        if(empty($FromS) || empty($sid) || empty($token)){
            $TwilioSetupmessage= "\n Click on 'Set' button to Twilio setup for sending SMS to client.";
            return json_encode(["TwilioSetup"=>$TwilioSetupmessage]);
        }

    }else{
        $TwilioSetupmessage= "\n Click on 'Set' button to Twilio setup for sending SMS to client.";
        return json_encode(["TwilioSetup"=>$TwilioSetupmessage]);
    } 

    $statement= $db->prepare("SELECT * FROM `countries` WHERE countries_name = (SELECT country from clients where id = :cid)");
    $statement->bindParam(':cid', $cid);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $isdcode = $result['countries_isd_code'];

    $Phone = $to;
    $ph=str_replace("(","",$Phone);
    $ph2=str_replace(") ","",$ph);
    $ph3=str_replace("-","",$ph2);
    $finalphone = '+'.$isdcode.''.$ph3;
    // $finalphone = '+91'.$ph3;


    $sms = $message;
    $client = new Client($sid, $token);

    $Successfully_send = $client->messages->create($finalphone,array('from' =>$FromS,'body' =>$sms));
    $ssid = $Successfully_send->sid;

    if(!empty($ssid))
    {

        $myactivite = new Activites(); 
        $Titile=$myactivite->Titile = 'Send sms to : '.$to ;	
        $myactivite->commit_acitve($Titile);

        
        //sms data
        $date = date('Y/m/d h:i:s', time());
        $sql = $db->prepare("insert into smsdata(ssid,twillo_from,userid,sms,finalphone,createddate) values(:ssid,:twillo_from,:userid,:sms,:finalphone,:createddate)");
        $sql->bindParam(":ssid",$ssid);
        $sql->bindParam(":createddate",$date);
        $sql->bindParam(":twillo_from",$FromS);
        $sql->bindParam(":userid",$_SESSION['UserID']);
        $sql->bindParam(":sms",$message);
        $sql->bindParam(":finalphone",$finalphone);
        $sql->execute();

        $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,cid,Createid,comtime) VALUES('sms',:message,:cid,:Createid,:comtime)");
        $comtime = date("Y-m-d H:i:s");
        $insert_data_fc->bindparam(":message",$message);
        $insert_data_fc->bindparam(":cid",$cid);
        $insert_data_fc->bindparam(":Createid",$_SESSION['UserID']);
        $insert_data_fc->bindparam(":comtime",$comtime);
        $insert_data_fc->execute();
        return json_encode(["response"=>'SMS has been sent successfully.']);die;			
        
    }
    else
    {
        return json_encode(["error"=>'Invalid SMS credential.']);die;
    }

}
?>