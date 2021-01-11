<?php
// ini_set("display_errors", "1");
//   error_reporting(E_ALL);

require('../global.php');

require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");
require($_SERVER["DOCUMENT_ROOT"].$SUB.'/payment/config.inc.php');
require($_SERVER["DOCUMENT_ROOT"].$SUB.'/payment/AuthnetXML.class.php');

class EditProfile
{
    public function viewpackage()
    {

        $db=new db(); 

        if(isset($_GET['subuserid']) && $_GET['subuserid']!='all'){
            $id = " where payments.userid=".$_GET['subuserid'];
        }else if(isset($_GET['subuserid']) && $_GET['subuserid']=='all'){
         $id = "";
     }else{
        $id = " where payments.userid=".$_SESSION['UserID'];
    }

    $paydetail = $db->prepare("select payments.*,users.username  from `payments` join users on payments.userid=users.id ".$id." ");
    $paydetail->execute();
    $allpaydetail=$paydetail->fetchAll();
    echo json_encode($allpaydetail);die;	
}
private function Regstration_Validation_for_update()
{
    $Result=array();

    if(empty($_REQUEST['firstname']))
    {
        $Result['firstname'] = "Please enter your first name";
    }
    if(empty($_REQUEST['lastname']))
    {
        $Result['lastname'] = "Please enter your last name";
    }
    if(empty($_REQUEST['phonenumber']))
    {
        $Result['phonenumber'] = "Please enter your phone number";
    }
    if(empty($_REQUEST['companyname']))
    {
        $Result['companyname'] = "Please enter your company name";
    }
        // if(empty($_REQUEST['companytype']))
        // {
        //     $Result['companytype'] = "Please enter your company type";
        // }
        // if(empty($_REQUEST['companywebsite']))
        // {
        //     $Result['companytype'] = "Please enter your company website";
        // }
        // if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_REQUEST['companywebsite']))
        // {
        //     $Result['companytype'] = "Please enter valid website url as www.abc.com ";	
        // }
    if(empty($_REQUEST['Address']))
    {
        $Result['primaryaddress'] = "Please enter your primary address";
    }
    if(empty($_REQUEST['zipcode']))
    {
        $Result['zipcode'] = "Please enter your zipcode";
    }
    if(empty($_REQUEST['city']))
    {
        $Result['city'] = "Please enter your city";
    }
    if(empty($_REQUEST['state']))
    {
        $Result['state'] = "Please enter your state";
    }
    if(empty($_REQUEST['country']))
    {
        $Result['country'] = "Please select your country";
    }
    return $Result;
}
private function Regstration_Validation_for_passwoed_update()
{
    $Result=array();
    $db2=new db();
    @$id=$_SESSION['UserID'];
    $stmt= $db2->prepare("SELECT password FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result =$stmt->fetchAll();
    $a=$result[0]['password'];	
    if($a!=md5($_REQUEST['current_password']))
    {
        $Result['valid_currnet_password'] = "Please enter valid current password";	
    }	
    if(empty($_REQUEST['password']))
    {
        $Result['password'] = "Please enter your password";
    }
    if(empty($_REQUEST['confirm_password']))
    {
        $Result['confirm_password'] = "Please Confirm Your Password ";
    }
    if($_REQUEST['confirm_password']!=$_REQUEST['password'])
    {
        $Result['confirm_password'] = "Please Enter Correct Password ";
    }
    if(empty($_REQUEST['current_password']))
    {
        $Result['current_password'] = "Please Enter Your Current Password";
    }
    return $Result;
}
private function Validation_for_forget_passowrd()
{
    $Result=array();
    $dbforget=new db();
    $email = $_REQUEST['email'];
    $stmt= $dbforget ->prepare("SELECT * FROM `users` WHERE email=:email"); 
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $getdata=$stmt->rowCount();
    if($getdata==0)
    {
        $Result['valid_currnet_password'] = "Sorry! your account does not exist.";	
    }	
    if(empty($_REQUEST['email']))
    {
        $Result['email'] = "Please enter your email";
    }
    return $Result;
}
public function update_password()
{
    $validation=$this->Regstration_Validation_for_passwoed_update();
    if(!empty($validation))
    {
        $error=implode("\n",$validation);
        echo json_encode(['error'=>$error]);die;
    }
    $db5=new db();
    if(isset($_SESSION['UserID']))
    {


        $id=$_SESSION['UserID'];
        $password=md5($_POST['password']);
        $stmt=$db5->prepare("update users set password=:password where id=:id");
        $stmt->bindparam(":password",$password);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        if($stmt)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Changed password';	
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Thank you, your password has been successfully changed']);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
}
public function update()
{
    unset($_SESSION['UserName']);
    $validation=$this->Regstration_Validation_for_update();
    if(!empty($validation))
    {
        $error=implode("\n",$validation);
        echo json_encode(['error'=>$error]);die;
    }

    if(isset($_SESSION['UserID']))
        {       $db4=new db();
            $id=$_SESSION['UserID'];
            $username=stripslashes(strip_tags($_POST["username"]));//$_POST['username'];
            $firstname=stripslashes(strip_tags($_POST["firstname"]));
            $lastname=stripslashes(strip_tags($_POST["lastname"]));
            $email=stripslashes(strip_tags($_POST["email"]));
            $phonenumber=stripslashes(strip_tags($_POST["phonenumber"]));
            $companyname=stripslashes(strip_tags($_POST["companyname"]));
            $companytype=stripslashes(strip_tags($_POST["companytype"]));
            $companywebsite=stripslashes(strip_tags($_POST["companywebsite"]));
            $primaryaddress=stripslashes(strip_tags($_POST["Address"]));
            $secondaryaddress=stripslashes(strip_tags($_POST["secondaryaddress"]));
            $zipcode=stripslashes(strip_tags($_POST["zipcode"]));
            $city=stripslashes(strip_tags($_POST["city"]));
            $state=$_POST['state'];
            $country=$_POST['country'];
            $stmt=$db4->prepare("UPDATE `users` SET 
                username=:username, firstname=:firstname, 
                lastname=:lastname, email=:email, phonenumber=:phonenumber, companyname=:companyname, companytype=:companytype, companywebsite=:companywebsite, companywebsite=:companywebsite, primaryaddress=:primaryaddress, secondaryaddress=:secondaryaddress, zipcode=:zipcode, city=:city, state=:state, country=:country where id=:id");
            $stmt->bindparam(":username",$username);
            $stmt->bindparam(":firstname",$firstname);
            $stmt->bindparam(":lastname",$lastname);
            $stmt->bindparam(":email",$email);
            $stmt->bindparam(":phonenumber",$phonenumber);
            $stmt->bindparam(":companyname",$companyname);
            $stmt->bindparam(":companytype",$companytype);
            $stmt->bindparam(":companywebsite",$companywebsite);
            $stmt->bindparam(":primaryaddress",$primaryaddress);
            $stmt->bindparam(":secondaryaddress",$secondaryaddress);
            $stmt->bindparam(":zipcode",$zipcode);
            $stmt->bindparam(":city",$city);
            $stmt->bindparam(":state",$state);
            $stmt->bindparam(":country",$country);
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            if($stmt)
            {
                $_SESSION["UserName"] = $username;
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'User update profile';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Your profile has been updated successfully']);die;
            }
            else
            {
                echo json_encode(['error'=>'Sorry something wrong']);die;
            }
        }
    }

    public function upload_crop_user_image(){
       if(file_exists(DOCUMENT_ROOT.ESUB.'/upload-and-crop-image/CustomerTep/'.$_POST['userimg']))
       {
        if(isset($_SESSION['UserID']))
        {
            $dbimg=new db();
            $id=$_SESSION['UserID'];
            $Iname=explode(".",$_POST['userimg']);
            $ImgObj= new AllFunction;
            $ImgFileName=$ImgObj->ImgName();
            @$NewImagename = $ImgFileName.".".$Iname[1]; 
            $path = DOCUMENT_ROOT.ESUB."/assets/userimage/";
            $path = $path . basename($NewImagename);
            $fileMoved = rename(DOCUMENT_ROOT.ESUB.'/upload-and-crop-image/CustomerTep/'.$_POST['userimg'], $path);
            if($fileMoved)
            {
                @$path2 = $_SERVER['DOCUMENT_ROOT'].ESUB.'/upload-and-crop-image/CustomerTep/'.@$_POST['userimg'];
                @unlink($path2);
            }

            $sth=$dbimg->prepare("update users set userimg=:userimg where id=:id"); 
            $sth->bindParam(':userimg',$NewImagename); 
            $sth->bindparam(":id",$id);
            $sth->execute(); 
            if($sth)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'User uploade image'; 
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Your profile image has been successfully updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'Sorry something wrong']);die;
            }
        }
    }
}

public function uplode_user_image()
{
    $dbimg=new db();
    if(isset($_SESSION['UserID']))
    {
        $id=$_SESSION['UserID'];
        $folder =DOCUMENT_ROOT.SUB."/assets/userimage/"; 
        $userimg = $_FILES['userimg']['name']; 
        $abc= new AllFunction();
        $xyz=$abc->ImgName();
        $Iname=explode(".",$_FILES["userimg"]["name"]);
        $NewImagename = $xyz.".".$Iname[1]; 
        $path = $folder . $NewImagename ; 
        $target_file=$folder.basename($_FILES["userimg"]["name"]);
        $imageFileType=pathinfo($target_file,PATHINFO_EXTENSION);
        $allowed=array('jpeg','png' ,'jpg'); 
        $filename=$_FILES['userimg']['name']; 
        $ext=pathinfo($NewImagename, PATHINFO_EXTENSION); 
        if(!in_array($ext,$allowed)) 
        { 
            echo "Sorry, only JPG, JPEG, PNG & GIF  files are allowed.";
        }
        else{ 
            move_uploaded_file( $_FILES['userimg'] ['tmp_name'], $path); 
            $sth=$dbimg->prepare("update users set userimg=:userimg where id=:id"); 
            $sth->bindParam(':userimg',$NewImagename); 
            $sth->bindparam(":id",$id);
            $sth->execute(); 
            if($sth)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'User uploade image';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Your profile image has been successfully updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'Sorry something wrong']);die;
            }
        } 
    }
}
public function uplode_comp_image()
{
    $dbimg=new db();
    if(isset($_SESSION['UserID']))
    {
        $id=$_SESSION['UserID'];
        $folder =DOCUMENT_ROOT.SUB."/assets/companyimage/"; 
        $userimg = $_FILES['compimg']['name']; 
            //$path = $folder . $userimg ; 
        $abc= new AllFunction();
        $xyz=$abc->ImgName();
        $Iname=explode(".",$_FILES["compimg"]["name"]);
        $NewImagename = $xyz.".".$Iname[1]; 
        $path = $folder . $NewImagename ; 
        $target_file=$folder.basename($_FILES["compimg"]["name"]);
        $imageFileType=pathinfo($target_file,PATHINFO_EXTENSION);
        $allowed=array('jpeg','png' ,'jpg'); 
        $filename=$_FILES['compimg']['name']; 
        $ext=pathinfo($NewImagename, PATHINFO_EXTENSION); 
        if(!in_array($ext,$allowed)) 
        { 
            echo "Sorry, only JPG, JPEG, PNG & GIF  files are allowed.";
        }
        else{ 
            move_uploaded_file( $_FILES['compimg'] ['tmp_name'], $path); 
            $sth=$dbimg->prepare("update users set compimg=:compimg where id=:id"); 
            $sth->bindParam(':compimg',$NewImagename); 
            $sth->bindparam(":id",$id);
            $sth->execute(); 
            if($sth)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'Company uploade image';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Company image successfully updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'Sorry Something Wrong']);die;
            }
        } 
    }
}
public function forget_password()
{
    $validation=$this->Validation_for_forget_passowrd();
    if(!empty($validation))
    {
        $error=implode("\n",$validation);
        echo json_encode(['error'=>$error]);die;
    }
    if(isset($_REQUEST['email']))
    {
        $email=$_REQUEST['email'];
        $newpassowrd=rand(100000,1000000);
        $mynewpassowrd=md5($newpassowrd);	

        $forgetpassword=new db();
        $stmt= $forgetpassword ->prepare("SELECT * FROM `users` WHERE email=:email"); 
        $stmt->bindparam(":email",$email);
        $stmt->execute();
        $result =$stmt->fetchAll();
        $username=$result[0]['username'];
        $myid=$result[0]['id'];
            // setcookie("mycockid", $myid, time()+30*24*60*60);
        $firstname=$result[0]['firstname'];
        $lastname=$result[0]['lastname'];
        $userid=EncodeId($result[0]['id']);
        $emaisendtime=date('Y-m-d h:i:s');
        $url="https://mysunless.com".SUB."/ResetPassword?ref=$userid";
        $other['--USERNAME--'] = $username;
        $other['--EMAIL--'] = $email;
        $other['--FIRSTNAME--'] = $firstname;
        $other['--LASTNAME--'] = $lastname;
        $other['--URL--'] = $url;
        $headers = '';
        $message="Hi ";
        sendForgetMail($email, "Reset Password Request!", "Forgetpassword.php", $message, $headers, $other);
            // $to = $email;
            //      	$subject = "[My Sunless] Reset password request";
            //      	$url="http://mysunless.com/ResetPassword?ref=$userid";
            //   $message = "<b>Hi $firstname $lastname, <br>We found that someone has been requested for reset password. Please click on bellow link and you will be able to reset password. <br>$url</b>";
            //      $header = "From:salmandds7@gmail.com \r\n";
            //      $header .= "Cc:salmandds7@gmail.com \r\n";
            //      $header .= "MIME-Version: 1.0\r\n";
            //      $header .= "Content-type: text/html\r\n";
            //      $retval = mail ($to,$subject,$message,$header);
            // if( $retval == true ) {
            //    echo "Message sent successfully...";
            // }else {
            //    echo "Message could not be sent...";
            // }
        $emailstatus=1;
        $stmt=$forgetpassword->prepare("update users set emaisendtime=:emaisendtime,emailstatus=:emailstatus where email=:email");
        $stmt->bindparam(":emaisendtime",$emaisendtime);
        $stmt->bindparam(":email",$email);
        $stmt->bindparam(":emailstatus",$emailstatus);
        $stmt->execute();
        if($stmt)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Request to password reset';	
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Reset url has been send on your mail id']);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
}
public function Rest_password()
{
    $validation=$this->Regstration_Validation_for_Rest_password();
    if(!empty($validation))
    {
        $error=implode("\n",$validation);
        echo json_encode(['error'=>$error]);die;
    }
    $db5=new db();
    if(isset($_REQUEST['id']))
    {
        $id=$_REQUEST['id'];
        $password=md5($_POST['password']);
        $emailstatus=0;
        $stmt=$db5->prepare("update users set password=:password,emailstatus=:emailstatus where id=:id");
        $stmt->bindparam(":password",$password);
        $stmt->bindparam(":emailstatus",$emailstatus);
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        if($stmt)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Change Password';	
            $myactivite->commit_acitve($Titile);
                // setcookie("mycockid", "", time()-3600);
            echo json_encode(['resonse'=>'Password successfully changed']);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
}
private function Regstration_Validation_for_Rest_password()
{
    $Result=array();
    $dbforgetpas=new db();
    $id=$_REQUEST['id'];
    $stmt= $dbforgetpas ->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $getdata=$stmt->rowCount();
    $result =$stmt->fetchAll();
    $emaisendtime=$result[0]['emaisendtime'];
    $emailstatus2=$result[0]['emailstatus'];
    $emailid=$result[0]['id'];
    $datetime1 = new DateTime();
    $datetime2 = new DateTime($emaisendtime);
    $interval = $datetime1->diff($datetime2);
    $elapsed = $interval->format('%a');
    if($elapsed>=1)
    {
        $Result['time'] = "Sorry! your password reset link has been expired";
    }
    if($emailstatus2=='0')
    {
       $Result['time'] = "Sorry! your password reset link has been expired";
   }
   if(empty($_REQUEST['password']))
   {
    $Result['password'] = "Please enter your password";
}
if(empty($_REQUEST['confirm_password']))
{
    $Result['confirm_password'] = "Please confirm your password !";
}
if($_REQUEST['confirm_password']!=$_REQUEST['password'])
{
    $Result['confirm_password'] = "Please enter correct password !";
}
return $Result;
}
public function deleteimage()
{
    $dbdelimag=new db();
    $id=$_REQUEST['myData'];
    $userimg='';
    $stmt=$dbdelimag->prepare("update users set userimg=:userimg where id=:id");
    $stmt->bindparam(":userimg",$userimg,PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();		
    if($stmt)
    {
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = 'Remove Profile Image';	
        $myactivite->commit_acitve($Titile);
        echo json_encode(['resonse'=>'User image has been successfully removed']);die;
    }
    else
    {
        echo json_encode(['error'=>'Sorry something wrong']);die;
    }
}
public function cdeleteimage()
{
    if(isset($_REQUEST['cimyData1']))
    {
        $delcimg=new db();
        $id=$_REQUEST['cimyData1'];
        $ClientImg='';
        $stmt=$delcimg->prepare("update clients set ClientImg=:ClientImg where id=:id");
        $stmt->bindparam(":ClientImg",$ClientImg,PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();		
        if($stmt)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Remove client image';	
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Client image has been successfully removed']);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry Something Wrong']);die;
        }
    }
    else
    {
        $delcimg=new db();
        $id=$_REQUEST['cimyData2'];
        $ProfileImg='';
        $stmt=$delcimg->prepare("update clients set ProfileImg=:ProfileImg where id=:id");
        $stmt->bindparam(":ProfileImg",$ProfileImg,PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();			
        if($stmt)
        {
            $myactivite = new Activites();
            $Titile=$myactivite->Titile = 'Remove client profile image';	
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Client image has been successfully removed']);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry Something Wrong']);die;
        }
    }
}
public function updatepackage()
{
        // $validation=$this->Regstration_Validation_for_unsub();
        // if(!empty($validation))
        // {
        //     $error=implode("\n",$validation);
        //     echo json_encode(['error'=>$error]);die;
        // }
    if(isset($_POST['packageid']))
    {
        $dbpays=new db();
        $packageid= $_POST['packageid'];
        $packstmt= $dbpays->prepare("SELECT * FROM `package` WHERE id=:packageid"); 
        $packstmt->bindParam(':packageid', $packageid, PDO::PARAM_STR);
        $packstmt->execute();
        $packresult =$packstmt->fetchAll();
            //$newPrice=$packresult[0]['Price'];
        $id=$_SESSION['UserID'];
        $stmt= $dbpays->prepare("SELECT * FROM `payments` WHERE userid=:id"); 
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->fetchAll();

        $PackageType=$result[0]['PackageType'];
        $amount=$result[0]['amount'];
        $subscriptionId=$result[0]['subscriptionId'];
        $dbpayuser=new db();
        $id=$_SESSION['UserID'];
        $stmt2= $dbpayuser->prepare("SELECT * FROM `users` WHERE id=:id"); 
        $stmt2->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt2->execute();
        $result2 =$stmt2->fetchAll();
        $firstname=$result2[0]['firstname'];
        $lastname=$result2[0]['lastname'];
        $xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, AuthnetXML::USE_DEVELOPMENT_SERVER);
        $xml->ARBUpdateSubscriptionRequest(array(
            'refId' => 'Sample',
            'subscriptionId' => $subscriptionId,
            'subscription' => array(
                'name' => 'Sample subscription',
                    // 'paymentSchedule' => array(
                    // 'interval' => array(
                    //     'length' => '1',
                    //     'unit' => 'months'
                    // ),
                    //     'startDate' => '2018-04-06',
                    //     'totalOccurrences' => '12',
                    //     'trialOccurrences' => '1'
                    // ),
                'amount' =>$packresult[0]['Price'],
                'trialAmount' => '0.00',
                    // 'payment' => array(
                    //     'creditCard' => array(
                    //         'cardNumber' => $credit_card_number,
                    //         'expirationDate' => $credit_card_exp
                    //     )
                    // ),
                'billTo' => array(
                    'firstName' => $firstname,
                    'lastName' => $lastname
                )
            ),
        ));
        if($xml->isError())
        {
            echo json_encode(['error'=>'Please check your payments detail','error_msg'=>$xml->isError()]);die;
        }
        elseif($xml->isSuccessful())
        {
            $uppack2=new db();
            $status="InActive";
            $stmt2=$uppack2->prepare("update payments set status=:status where userid=:id");
            $stmt2->bindparam(":status",$status,PDO::PARAM_STR);
            $stmt2->bindParam(":id",$id,PDO::PARAM_STR);			
            $stmt2->execute();
            $newPackageType=$packresult[0]['PackageName'];
            $Price=$packresult[0]['Price'];
            $uppack=new db();
            $stmt=$uppack->prepare("INSERT INTO payments(PackageType,amount,userid,subscriptionId) VALUES(:newPackageType, :Price,:id,:subscriptionId)");
            $stmt->bindparam(":newPackageType",$newPackageType,PDO::PARAM_STR);
            $stmt->bindparam(":Price",$Price,PDO::PARAM_STR);
            $stmt->bindParam(":id",$id,PDO::PARAM_STR);
            $stmt->bindparam(":subscriptionId",$subscriptionId,PDO::PARAM_STR);
            $stmt->execute();			
            if($stmt)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'Upgrade Package';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Thank you! your package has been successfully updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'sorry something wrong']);die;
            }
        }
    }
}
public function Unsubscribed()
{


    $validation=$this->Regstration_Validation_for_unsub();
    if(!empty($validation))
    {
        $error=implode("\n",$validation);
        echo json_encode(['error2un'=>$error]);die;
    }

    if(isset($_SESSION['UserID']))
    {
        $unsub=new db();
        $id=$_SESSION['UserID'];
        $stmt= $unsub->prepare("SELECT * FROM `payments` WHERE userid=:id"); 
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->fetchAll();

        $subscriptionId=$result[0]['subscriptionId'];
        $xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, AuthnetXML::USE_DEVELOPMENT_SERVER);
        $xml->ARBCancelSubscriptionRequest(array(
            'refId' => 'Sample',
            'subscriptionId' => $subscriptionId
        ));	

        if($xml->isError())
        {
            echo json_encode(['error2un'=>'Please check your subscription status']);die;
        }
        elseif($xml->isSuccessful())
        {
            $newsta='Canceled';
            $unsubpack=new db();
            $stmt=$unsubpack->prepare("update payments set status=:newsta where userid=:id");
            $stmt->bindparam(":newsta",$newsta,PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();			
            $status='InActive';
            $unsubpacks=new db();
            $stmts=$unsubpacks->prepare("update users set status=:status where id=:id");
            $stmts->bindparam(":status",$status,PDO::PARAM_STR);
            $stmts->bindParam(':id', $id, PDO::PARAM_STR);
            $stmts->execute();			
            session_destroy();
            if($stmts)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'Subcriber unsubscribed';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse2un'=>'You are successfully unsubscribed']);die;
            }
            else
            {
                echo json_encode(['error2un'=>'sorry something wrong']);die;
            }
        }
    }
}
private function Regstration_Validation_for_unsub()
{
    $Result=array();
    $unsub2=new db();
    $id=$_SESSION['UserID'];
    $stmt= $unsub2->prepare("SELECT * FROM `payments` WHERE userid=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $result =$stmt->fetchAll();
    $status=$result[0]['status'];
    if($status=="Canceled")
    {
        $Result['status'] = "Sorry! you are unsubscribed";
    }	
    return $Result;
}	

public function Addsing()
{
    $db5=new db();
        @$image=stripslashes(strip_tags($_POST["signdata"])); //$_POST['image']; 
        if(!empty($image))
        {
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $ImgObj= new AllFunction;
            $ImgFileName=$ImgObj->ImgName();
            $img = $_SERVER["DOCUMENT_ROOT"].SUB.'/assets/sing/'.$ImgFileName.'.png';
            file_put_contents($img, $image);
            $imageinsert=$ImgFileName.'.png';

            $id=$_SESSION['UserID'];
            $stmt=$db5->prepare("update users set mysign=:imageinsert where id=:id");
            $stmt->bindparam(":imageinsert",$imageinsert);
            $stmt->bindparam(":id",$id);
            $Sign= $stmt->execute();

            if($Sign)
            {
                echo json_encode(['resonse2Sign'=>'Your signature successfully save']);die;
            }
        }
    }	



    public function removeSignature(){

        $db_sign = new db();
        
        @$removeSignId=stripslashes(strip_tags($_POST["removeSignData"])); 
        
        $mysignDelete = '';
        $stmts=$db_sign->prepare("update users set mysign=:mysignRemove where id=:id");
        $stmts->bindparam(":mysignRemove",$mysignDelete,PDO::PARAM_STR);
        $stmts->bindParam(':id', $removeSignId, PDO::PARAM_STR);
        $stmtRes = $stmts->execute();  

        if($stmtRes > 0)
        {
           echo json_encode(['resonse2Sign'=>'Your signature successfully Removed']);die;

       }
       else
       {
        echo json_encode(['error2un'=>'sorry something wrong']);die;

    }

}
}
?>