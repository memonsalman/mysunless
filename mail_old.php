<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');

if(isset($_SESSION["UserID"])){

$sql = new db();
$stmt = $sql->query("SELECT compimg FROM `CompanyInformation` where createdfk=".$_SESSION["UserID"]);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(!empty($result)){
            if(!empty($result[0]['compimg'])){
                $_SESSION['COMPANY_LOGO'] = "https://mysunless.com/crm/assets/companyimage/".$result[0]['compimg'];
            }else{
                $_SESSION['COMPANY_LOGO'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
            }

        }else{
            $_SESSION['COMPANY_LOGO'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
        }

}else{
            $_SESSION['COMPANY_LOGO'] = "https://mysunless.com/crm/assets/images/mysunless_logo.png";
}

define("ISSMTP", 1);

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
//customer welcome mail
//New company registration mail
function sendCMail($to, $subject, $template_name, $message, $headers, $other=array()){  
    if(@$to != ""){
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            //
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'mysunless.com';
            $mail->SMTPSecure = 'ssl';
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 465;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = 'support@mysunless.com';
            //Password to use for SMTP authentication
            $mail->Password = 'g-WNdMNHG^z6';
            //Set who the message is to be sent from
            $mail->setFrom('support@mysunless.com', 'Mysunless');
            //Set an alternative reply-to address
            $mail->addReplyTo('support@mysunless.com', 'Mysunless');
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            // 
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            ////$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
        }
    }
}

function sendInvoice($to, $subject, $template_name, $message, $headers, $other=array(),$filelocation){  
    
    if($to != ""){
        $db = new db();

        $id=$_SESSION['UserID'];
        if($_SESSION['usertype']!="subcriber"){
            $id = $_SESSION['adminid'];
        }

        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_password = $query_data['smtppassword'];
        if($query_data)
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $query_data['UserID'], PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
           
        }
        else
        {
            $email_username = $query_data['fname'];
        }

        if(empty($email_hostname))
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_hostname = $query_data['email'];
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
            $email_password = $query_data['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            // 
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            //include('global.php');

            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            // echo "<pre>";
            // print_r($GLOBALS);
           
            // echo $GLOBALS['SUB'];
           
            // echo $templatepath.$SUB.$template_name;
          
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            $mail->addAttachment($filelocation);
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                return "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
                // 
                return "Message sent!";
            }
        }
    }
}    

function sendEventMail($to, $subject, $template_name, $message, $headers, $other=array()){  
    
    if($to != ""){
        $db = new db();
        $id=$_SESSION['UserID'];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_password = $query_data['smtppassword'];
        if($query_data)
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $query_data['UserID'], PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
           
        }
        else
        {
            $email_username = $query_data['fname'];
        }

        if(empty($email_hostname))
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_hostname = $query_data['email'];
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
            $email_password = $query_data['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            // 
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            //include('global.php');

            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            // echo "<pre>";
            // print_r($GLOBALS);
           
            // echo $GLOBALS['SUB'];
           
            // echo $templatepath.$SUB.$template_name;
          
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            ////$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                return "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
                // 
                return "Message sent!";
            }
        }
    }
}



function sendEventMailForcanl($to, $subject, $template_name, $message, $headers, $other=array()){  
    
    if($to != ""){
        $db = new db();
        $id=$_SESSION['UserID'];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_password = $query_data['smtppassword'];
        if($query_data)
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $query_data['UserID'], PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
           
        }
        else
        {
            $email_username = $query_data['fname'];
        }

        if(empty($email_hostname))
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_hostname = $query_data['email'];
            $email_username = $query_data['firstname']." ".$query_data['lastname'];
            $email_password = $query_data['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            // 
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            ////$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                return "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
                // 
                return "Message sent!";
            }
        }
    }
}
function sendEventRemingMail($to, $subject, $template_name, $message, $headers, $other=array()){  
    if($to != ""){
        $db = new db();
        $id=$_SESSION['UserID'];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_username = $query_data['fname'];
        $email_password = $query_data['smtppassword'];
        if(empty($email_hostname))
        {
            $setting_query = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query->execute();
            $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
            $email_hostname = $query_data['email'];
            $email_username = $query_data['username'];
            $email_password = $query_data['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            ////$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
        }
    }
}
function sendForgetMail($to, $subject, $template_name, $message, $headers, $other=array()){  
    if($to != ""){
        $db = new db();
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                 echo "1";  die;
            } else { 
                 echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once( 'phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'mysunless.com';
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 465;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = 'support@mysunless.com';
            $mail->SMTPSecure = 'ssl';
            //Password to use for SMTP authentication
            $mail->Password = 'g-WNdMNHG^z6';
            //Set who the message is to be sent from
            $mail->setFrom('support@mysunless.com', 'Mysunless');
            //Set an alternative reply-to address
            $mail->addReplyTo('support@mysunless.com', 'Mysunless');
            $mail->AddBCC("test@mysunless.com", "Reset Password");
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            ////$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
        }
    }
}
function sendsmpleMail($to, $subject, $template_name, $message, $headers, $other=array()){  
    if($to != ""){
        $db = new db();
        $id=$_SESSION['UserID'];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_username = $query_data['fname'];
        $email_password = $query_data['smtppassword'];
        if(empty($email_hostname))
        {
            $setting_query2 = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query2->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query2->execute();
            $query_data2 = $setting_query2->fetch(PDO::FETCH_ASSOC);
            $email_hostname = 'salmandds7@gmail.com';
            $email_username = $query_data2['username'];
            $email_password = $query_data2['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once('phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                @$body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
          //  $mail->AddAttachment('https://mysunless.com/assets/userimage/20180913115040.jpg', '20180913115040.jpg');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
        }
    }
}
function sendsmpleMailReport($to, $subject, $template_name, $message, $headers, $other=array(),$file_name){ 

    if($to != ""){
        $db = new db();
        $id = $_SESSION["UserID"];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $query_data['fmail'];
        $email_username = $query_data['fname'];
        $email_password = $query_data['smtppassword'];
        if(empty($email_hostname))
        {
            
            $setting_query2 = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query2->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query2->execute();
            $query_data2 = $setting_query2->fetch(PDO::FETCH_ASSOC);
            $email_hostname = 'salmandds7@gmail.com';
            $email_username = $query_data2['username'];
            $email_password = $query_data2['password'];
        }
        if(ISSMTP == 0) {
           
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            if (mail($to,$subject,$body,$headers)) { 
                // echo "1";  die;
            } else { 
                // echo "0"; die;
            } 
        } else if(ISSMTP == 1){
           
            require_once('phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                @$body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            
            $mail->Host = $email_hostname;

            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;

            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddBCC("test@mysunless.com", "test");
            //Set the subject line
            $mail->Subject = $subject;
            $mail->addAttachment("uploads/".$file_name);
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->AddAttachment('https://mysunless.com/assets/userimage/20180913115040.jpg', '20180913115040.jpg');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                return "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
                return "Message sent!";
            }
        }
    }
}


function sendCampMail($to, $subject, $template_name, $message, $headers, $other=array(),$CampaignsFrom,$CampaignsFromName,$Campaignscc,$Campaignsbcc){  
    if($to != ""){
        $db = new db();
        @$id=$_SESSION['UserID'];
        $setting_query = $db->prepare("SELECT * FROM `EmailSetting` WHERE UserID=:id");
        $setting_query->bindParam(':id', $id, PDO::PARAM_INT);
        $setting_query->execute();
        $query_data = $setting_query->fetch(PDO::FETCH_ASSOC);
        $email_hostname = $CampaignsFrom;
       $email_username = $CampaignsFromName;
        $email_password = $query_data['smtppassword'];
        if(empty($email_hostname))
        {
            $setting_query2 = $db->prepare("SELECT * FROM `users` WHERE id=:id");
            $setting_query2->bindParam(':id', $id, PDO::PARAM_INT);
            $setting_query2->execute();
            $query_data2 = $setting_query2->fetch(PDO::FETCH_ASSOC);
            $email_hostname = $CampaignsFrom;
            $email_username = $CampaignsFromName;
            $email_password = $query_data2['password'];
        }
        if(ISSMTP == 0) {
            $headers = 'From:$email_hostname' . "\r\n" .'Reply-To:$email_hostname' . "\r\n" .'X-Mailer: PHP/' . phpversion(); 
            $headers .= "MIME-Version: 1.0\r\n"; 
            //$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                $body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            // echo "<pre>";print_r($body);echo"</pre>";die;
            if (mail($to,$subject,$body,$headers)) { 
                echo "1";  die;
            } else { 
                echo "0"; die;
            } 
        } else if(ISSMTP == 1){
            require_once('phpmailer/PHPMailerAutoload.php');
            $templatepath =  $_SERVER['DOCUMENT_ROOT'].$GLOBALS['SUB'].'/Templates/';
            //$templatepath = "https://www.cloud9cumulus.com/customer/Templates/";
            $body = file_get_contents($templatepath.$template_name);
            $other['--TEMPLATE_URL--'] = $templatepath;
            $other['--COMPANY_LOGO--'] = $_SESSION['COMPANY_LOGO'];
            foreach($other as $k => $v) {
                @$body = str_replace($k,$v,$body);
            }
            $body = wordwrap(trim($body), 70, "\r\n"); 
            $body = convert_smart_quotes($body);
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            //Tell PHPMailer to use SMTP
            //$mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $email_hostname;
            //Set the SMTP port number - likely to be 25, 465 or 587
            //$mail->Port = 25;
            //Whether to use SMTP authentication
            //$mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $email_username;
            //Password to use for SMTP authentication
            $mail->Password = $email_password;
            //Set who the message is to be sent from
            $mail->setFrom($email_hostname, $email_username);
            //Set an alternative reply-to address
            $mail->addReplyTo($email_hostname, $email_username);
            //Set who the message is to be sent to
            $mail->addAddress($to, '');
            $mail->AddCC($Campaignscc,"");               
            $mail->AddBCC($Campaignsbcc,"");
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
          //  $mail->AddAttachment('https://mysunless.com/assets/userimage/20180913115040.jpg', '20180913115040.jpg');
            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                //echo "Message sent!";
            }
        }
    }
}



?>