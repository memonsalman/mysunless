#!/usr/bin/php -q
<?php
$servername = "localhost";
$username = "mysunles_ddsdev";
$password = "qyOBJXH*VfLp";
$dbname = "mysunles_DbDev";

$db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare("SELECT * FROM `EventRemainder`");
$stmt->execute();
$users = $stmt->fetchAll();

foreach ($users as $key => $user) {
  $id = $user['createdfk'];
  $day = $user['RepeatDay'];
  $other['--NOTE--'] = "";
  if(!empty($user["Message"])){
    $other['--NOTE--'] = '<div style="padding: 5px;background: lightgrey;border-radius: 5px;"><h4 style="margin-top: 0px;"">Note:</h4>'.$user["Message"].'</div>';
  }

  if(!empty($day)){

    $days = explode(',',$day);
    $array = [];

    foreach ($days as $key => $value) {
      $str="( DATE_SUB(DATE_FORMAT(event.EventDate, '%Y-%m-%d') , INTERVAL ".$value." DAY) = CURDATE() )"; 
      array_push($array,$str);
    }

    $RepeatDay = implode(' OR ',$array);
    $RepeatDay = "( ".$RepeatDay." ) ";

    $stmt = $db->prepare("SELECT * FROM `CompanyInformation` where createdfk=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if(!empty($result)){
      if(!empty($result[0]['compimg'])){
        $other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/companyimage/".$result[0]['compimg'];
        $other['--COMPANY_NAME--'] = $result[0]['CompanyName'];
        $other['--COMPNAME--'] = $result[0]['CompanyName'];
        $other['--COMPNUMBER--'] = $result[0]['Phone'];
        $other['--COMPEMAIL--'] = $result[0]['email'];
      }else{
        $other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
        $other['--COMPANY_NAME--'] = "MySunless";
        $other['--COMPNAME--'] = "";
        $other['--COMPNUMBER--'] = "";
        $other['--COMPEMAIL--'] = "";
      }

    }else{
      $other['--COMPANY_LOGO--'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
      $other['--COMPANY_NAME--'] = "MySunless";
      $other['--COMPNAME--'] = "";
      $other['--COMPNUMBER--'] = "";
      $other['--COMPEMAIL--'] = "";
    }


    $stmt= $db->prepare("SELECT * from EmailSetting where UserID=:id"); 
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result1 = $stmt->fetch();
    $icon = $result1['other'];
    $email_hostname = $result1['fmail'];
    $email_username = $result1['fname'];
    $email_password = $result1['smtppassword'];
    if(empty($email_hostname))
    {
      $setting_query2 = $db->prepare("SELECT * FROM `users` WHERE id=:id");
      $setting_query2->bindParam(':id', $id, PDO::PARAM_INT);
      $setting_query2->execute();
      $result2 = $setting_query2->fetch(PDO::FETCH_ASSOC);
      $email_hostname = $result2['email'];
      $email_username = $result2['username'];
      $email_password = $result2['password'];
    }

    $stmt= $db->prepare("SELECT * from AdminEmailSetting where templatename=:templatename"); 
    $stmt->bindParam(':templatename', $template_name);
    $stmt->execute();
    $result3 = $stmt->fetch();
    $bcc_email = $result3['bccemail'];
    $bcc_name = $result3['bccname'];

    $social_icon = '';
    if(!empty($icon)){
      $icon = json_decode($icon);
      foreach ($icon as $key => $value) {
        $icon = key($value);
        $url = $value->$icon;
        $icon = str_replace('_link','',$icon);
        $icon = "https://mysunless.com/crm/assets/icons/".$icon.".png";
        $social_icon .='<td style="padding: 0 10px;"> <a class="social" href="'.$url.'" target="_blank"><img src="'.$icon.'" height="24" width="24"></a> </td>';

      }
    }
    $other['--social_icon--'] = $social_icon;


    $query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
    $query->bindParam(':id', $id);
    $query->execute();
    $result2 = $query->fetch();
    $other['--USERNAME--'] = $result2['username'];


    $EditEvent=$db->prepare("select DISTINCT event.*, users.firstname as fname, users.lastname as lname from event join users on event.ServiceProvider=users.id where users.id IN (select id from users where id=:id or adminid=:id) and event.eventstatus='confirmed' and event.Accepted='1' and ".$RepeatDay);
    $EditEvent->bindParam(':id', $id);
    $EditEvent->execute();
    $GetEvent=$EditEvent->fetchAll();

    foreach($GetEvent as $row)
    {
      $other['--TITLE--'] = ucfirst($row['fname'])." ".ucfirst($row['lname']);
      $other['--SERVICE--'] = $row['title'];

      @$getdate=$row['EventDate'];
      @$stat_data2 = explode(" ",$getdate); 
      @$stat_data = $stat_data2[0];

      $other['--ONLYDATE--'] = date("F-jS-Y", strtotime($stat_data));

      $other['--FIRSTNAME--'] = $row['FirstName'];
      $other['--LASTNAME--'] = $row['LastName'];
      $other['--APP_NO--'] = date('Ymd',strtotime($row['datecreated'])).'-'.$row['id'];
      $other['--EDATA--'] = $stat_data2[1];
      $other['--ENDATA--'] = explode(" ",$row['end_date'])[1];
      $other['--ADD--'] = $row['Address'];
      $other['--ZIP--'] = $row['Zip'];
      $other['--CITY--'] = $row['City'];
      $other['--STA--'] =$row['State'];
      $other['--COUNTRY--'] =$row['country'];
      $other['--EI--'] =$row['EmailInstruction'];
      $other['--PRICE--'] = $row['CostOfService'];
      $other['--MAP--'] = str_replace(" ","+",$other['--ADD--'].",".$other['--CITY--'].",".$other['--STA--'].",".$other['--ZIP--'].",".$other['--COUNTRY--']);

      $headers = '';
      $message="Hi ";
      $Email = $row['Email'];
      $subject = "Event Remainder!";
      $template_name = "Event_Rminder.php";

      if($Email != ""){
        require_once('/home/mysunles/public_html/crm/phpmailer/PHPMailerAutoload.php');

        $templatepath =  '/home/mysunles/public_html/crm/assets/Templates/';
        $body = file_get_contents($templatepath.$template_name);

        foreach($other as $k => $v) {
          @$body = str_replace($k,$v,$body);
        }
        $body = wordwrap(trim($body), 70, "\r\n"); 
        $body = convert_smart_quotes($body);

        $query=$db->prepare("Select id from clients where email=:to and createdfk in ( select id from users where id=:id or adminid=:id or sid=:id) ");
        $query->bindparam(":id",$id);
        $query->bindparam(":to",$Email);
        $query->execute();
        $clientid = $query->fetch();
        $comtime = date("Y-m-d H:i:s");
        
        $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,subject,cid,Createid,comtime) VALUES('email',:message,:Subject,:cid,:Createid,:comtime)");
        $insert_data_fc->bindparam(":message",$body);
        $insert_data_fc->bindparam(":Subject",$subject);
        $insert_data_fc->bindparam(":cid", $clientid['id']);
        $insert_data_fc->bindparam(":Createid",$id);
        $insert_data_fc->bindparam(":comtime",$comtime);

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = $email_hostname;
        $mail->Username = $email_username;
        $mail->Password = $email_password;
        $mail->setFrom($email_hostname, $email_username);
        $mail->addReplyTo($email_hostname, $email_username);
        $mail->addAddress($Email, '');
        if(!empty($bcc_email)){
          $mail->AddBCC($bcc_email, $bcc_name);
        }
        $mail->Subject = $subject;
        $mail->msgHTML($body);    
        $mail->send();  

      }

    }

  }
}


function convert_smart_quotes($string) 
{ 
  $chr_map = array(
        // Windows codepage 1252
        "\xC2\x82" => "'", // U+0082⇒U+201A single low-9 quotation mark
        "\xC2\x84" => '"', // U+0084⇒U+201E double low-9 quotation mark
        "\xC2\x8B" => "'", // U+008B⇒U+2039 single left-pointing angle quotation mark
        "\xC2\x91" => "'", // U+0091⇒U+2018 left single quotation mark
        "\xC2\x92" => "'", // U+0092⇒U+2019 right single quotation mark
        "\xC2\x93" => '"', // U+0093⇒U+201C left double quotation mark
        "\xC2\x94" => '"', // U+0094⇒U+201D right double quotation mark
        "\xC2\x9B" => "'", // U+009B⇒U+203A single right-pointing angle quotation mark
        // Regular Unicode     // U+0022 quotation mark (")
        // U+0027 apostrophe     (')
        "\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
        "\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
        "\xE2\x80\x98" => "'", // U+2018 left single quotation mark
        "\xE2\x80\x99" => "'", // U+2019 right single quotation mark
        "\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
        "\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
        "\xE2\x80\x9C" => '"', // U+201C left double quotation mark
        "\xE2\x80\x9D" => '"', // U+201D right double quotation mark
        "\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
        "\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
        "\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
        "\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
      );
    $chr = array_keys  ($chr_map); // but: for efficiency you should
    $rpl = array_values($chr_map); // pre-calculate these two arrays
    return $str = str_replace($chr, $rpl, html_entity_decode($string, ENT_QUOTES, "UTF-8"));
  }
?>
