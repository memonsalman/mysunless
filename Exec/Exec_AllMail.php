<?php

require_once('Exec_Config.php');        

ini_set("display_errors", "1");
error_reporting(E_ALL);

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.AllMail.php'); 
if(isset($_POST['send']))
{

   if($_SESSION['usertype']=='subscriber'){ 
       $id = $_SESSION['UserID'];
   }else{
       $id = $_SESSION['adminid'];
   }
   $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);

   @$user=$result['username'];
   $Email=$_POST['To'];
   $Subject=$_POST['Subject'];
   $CampaignsMessage=$_POST['Message'];

   $email_array = [];
   foreach ($Email as $email)
   {
    $email_array = explode(",",$email);
    $key = $email_array[1];
    $value = $email_array[0];
    $getnexteventdetils = $db->prepare("SELECT event.title as next_service,users.username as next_service_employee FROM `event` JOIN users ON event.ServiceProvider=users.id WHERE cid=$key ORDER BY `event`.`datecreated` DESC LIMIT 0,1");
    $getnexteventdetils->execute();
    $result_getnexteventdetils = $getnexteventdetils->fetch(PDO::FETCH_ASSOC);
    $next_service=$result_getnexteventdetils['next_service'];
    $next_service_employee= $result_getnexteventdetils['next_service_employee'];

    $getlistofdcustomerdetail=$db->prepare("SELECT * from clients where id=:id");
    $getlistofdcustomerdetail->bindParam(':id', $key);
    $getlistofdcustomerdetail->execute();
    $result_getlistofdcustomerdetail = $getlistofdcustomerdetail->fetch();
    $clientemail=$result_getlistofdcustomerdetail['email'];
    $Phone=$result_getlistofdcustomerdetail['Phone'];
    $Country=$result_getlistofdcustomerdetail['Country'];
    $Address=$result_getlistofdcustomerdetail['Address'];
    $Zip=$result_getlistofdcustomerdetail['Zip'];
    $City=$result_getlistofdcustomerdetail['City'];
    $State=$result_getlistofdcustomerdetail['State'];


    if($_SESSION['usertype']=='subscriber'){ 
        $id = $_SESSION['UserID'];
    }else{
        $id = $_SESSION['adminid'];
    }
    $getcompanydetail=$db->prepare("Select users.mysign, CompanyInformation.* from users join CompanyInformation on users.id=CompanyInformation.createdfk where users.id=:id");
    $getcompanydetail->bindParam(':id', $id);
    $getcompanydetail->execute();
    $result_getcompanydetail = $getcompanydetail->fetch();
    $mysign = $result_getcompanydetail['mysign']?"<img src='https://mysunless.com/crm/assets/sing/".$result_getcompanydetail['mysign']:"";
    $company_email = $result_getcompanydetail['email'];
    $company_phone=$result_getcompanydetail['Phone'];
    $company_name=$result_getcompanydetail['CompanyName'];
    $user_booking_url = base_url."/Book-now?ref=".base64_encode($id);
    $company_address=$result_getcompanydetail['Address'].', '.$result_getcompanydetail['City'].', '.$result_getcompanydetail['State'].', '.$result_getcompanydetail['Country'].' - '.$result_getcompanydetail['Zip'];

    $currentdate = date('d M, Y');

    $CampaignsMessage = $_POST['Message'];
    @$FullName1 =ucfirst($result_getlistofdcustomerdetail['FirstName']);
    @$FullName2 =ucfirst($result_getlistofdcustomerdetail['LastName']);
    $CampaignsMessage =  str_replace("{{ first_name }}",$FullName1,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ last_name }}",$FullName2,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ customer_email }}",$clientemail,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ phone }}",$Phone,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ location.country }}",$Country,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ location.street }}",$Address,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ location.city }}",$City,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ location.state }}",$State,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ location.zip }}",$Zip,$CampaignsMessage);
    $CampaignsMessage = str_replace("{{ owner.signature }}",$mysign,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ current_date }}",$currentdate,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ company_phone }}",$company_phone,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ company_email }}",$company_email,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ user_booking_url }}",$user_booking_url,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ company_name }}",$company_name,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ company_address }}",$company_address,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ next_service }}",$next_service,$CampaignsMessage);
    $CampaignsMessage =  str_replace("{{ next_service_employee }}",$next_service_employee,$CampaignsMessage);

    $other['--USERNAME--'] = $user;
    $other['--EMAIL--'] = $Email;
    $other['--SUBJEST--'] = $Subject;
    $other['--MESSAGE--'] = $CampaignsMessage;
    $headers = '';
    $message="Hi";
    $mailreturn = sendsmpleMail($value, $Subject, "Compose.php", $CampaignsMessage, $headers, $other);
    
    if($mailreturn===true){

        $AllMail = new AllMail("new");
        $AllMail->id = "new";
        $AllMail->FromE = $_POST['From'];
        $AllMail->ToE = $value;
        $AllMail->Subject = $_POST['Subject'];
        $AllMail->MessageE = $CampaignsMessage;
        $AllMail->UserId = $_POST['UserID'];
        $AllMail->ccid = $key;
        $AllMail->type = $_POST['type'];
        $newmail=$AllMail->commit($AllMail->id);
        if($newmail)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Send Mail to : '.$AllMail->ToE ; 
            $myactivite->commit_acitve($Titile);
            $AllMail->ActivitesCount($newmail); 
        }
        if($AllMail)
        {
            echo  json_encode(["resonse_mail"=>'Mail has been sent successfully.']);die;            
        }
        else
        {
            echo  json_encode(["error_mail"=>'Something Wrong']);die;
        }
    }else{
     echo  json_encode(["error_mail"=>$mailreturn]);die;
 }

}


}

?>