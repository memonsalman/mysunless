<?php

require_once('global.php');
include("db.class.php");
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/function.php');
require($_SERVER["DOCUMENT_ROOT"].$SUB.'/payment/config.inc.php');
require($_SERVER["DOCUMENT_ROOT"].$SUB.'/payment/AuthnetXML.class.php');
class model
{
    private function Regstration_Validation()
    {	
        $Result=array();
        $db=new db();
        $username=$_POST['username'];
        $email=$_POST['email'];
        $stmt= $db ->prepare("SELECT  `username`, `email` FROM `users` WHERE `username`=:username OR `email`=:email "); 
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $a=$stmt->rowCount();
        if($a>0)
        {
            $Allusers=$stmt->fetchAll();
            foreach ($Allusers as $value) {
                if($value['username'] == $username){
                    $Result['allery'] = "UserName already exists. Please try again.";
                }
                if($value['email'] == $email){
                    $Result['allery'] = "This Email already exists. Please try again.";
                }
            }	
        }	
        if(empty($_REQUEST['username']))
        {
            $Result['username'] = "Enter Your Name !";
        }
        if(empty($_REQUEST['email']))
        {
            $Result['email'] = "Enter Your Email !";
        }
        // if(!preg_match("/^[_.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+.)+[a-zA-Z]{2,6}$/i",$_REQUEST['email']))
        // {
        //     $Result['valid email'] = "Please Enter Valid Email !";
        // }
        if(empty($_REQUEST['password']))
        {
            $Result['password'] = "Enter Your Password !";
        }
        if(empty($_REQUEST['confirm_password']))
        {
            $Result['confirm_password'] = "Enter Your confirm Password !";
        }
        if($_REQUEST['confirm_password']!=$_REQUEST['password'])
        {
            $Result['confirm_password'] = "Enter Your Same As Password !";
        }
        return $Result;
    }
    private function Regstration_Validation_for_update()
    {
        $Result=array();
        if(empty($_REQUEST['firstname']))
        {
            $Result['firstname'] = "Enter Your First Name !";
        }
        if(empty($_REQUEST['lastname']))
        {
            $Result['lastname'] = "Enter Your Last Name !";
        }
        if(empty($_REQUEST['phonenumber']))
        {
            $Result['phonenumber'] = "Enter Your Phone Number !";
        }
        if(empty($_REQUEST['companyname']))
        {
            $Result['companyname'] = "Enter Your Company Name !";
        }
        if(empty($_REQUEST['companytype']))
        {
            $Result['companytype'] = "Enter Your Company Type !";
        }
        if(empty($_REQUEST['companywebsite']))
        {
            $Result['companytype'] = "Enter Your Company Website !";
        }
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_REQUEST['companywebsite']))
        {
            $Result['companytype'] = "Please Enter valid Website Url as www.abc.com !";	
        }
        if(empty($_REQUEST['primaryaddress']))
        {
            $Result['primaryaddress'] = "Enter Your Primary Address !";
        }
        if(empty($_REQUEST['zipcode']))
        {
            $Result['zipcode'] = "Enter Your Zipcode !";
        }
        if(empty($_REQUEST['city']))
        {
            $Result['city'] = "Enter Your City !";
        }
        if(empty($_REQUEST['state']))
        {
            $Result['state'] = "Enter Your State !";
        }
        return $Result;
    }
    public function insert(){
        $validation=$this->Regstration_Validation();
        if(!empty($validation))
        {
            $error=implode("\n",$validation);
            echo json_encode(['error'=>$error]);die;
        }
        // $xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, AuthnetXML::USE_DEVELOPMENT_SERVER);
        // $xml->ARBCreateSubscriptionRequest(array(
        //     'refId' => 'Sample',
        //     'subscription' => array(
        //         'name' => 'Sample subscription',
        //         'paymentSchedule' => array(
        //             'interval' => array(
        //                 'length' => '1',
        //                 'unit' => 'months'
        //             ),
        //             'startDate' => date("Y-m-d"),
        //             'totalOccurrences' => '12',
        //             'trialOccurrences' => '1'
        //         ),
        //         'amount' => '29.00',
        //         'trialAmount' => '0.00',
        //         'payment' => array(
        //             'creditCard' => array(
        //                 'cardNumber' => '5105105105105100',
        //                 'expirationDate' => '10/20',
        //                 'cardCode'=>'123'
        //             )
        //         ),
        //         'billTo' => array(
        //             'firstName' => 'Memon',
        //             'lastName' => 'Salman'
        //         )
        //     )
        // ));
        // if($xml->isError())
        // {
        //     echo json_encode(['error'=>'Please Check your payments detail']);die;
        // }
        // elseif($xml->isSuccessful())
        // {
            @$username=stripslashes(strip_tags($_POST["username"]));
            @$email=stripslashes(strip_tags($_POST["email"]));
            @$userassword=stripslashes(strip_tags($_POST["password"]));
            @$password=md5($userassword);
            @$PackageType=stripslashes(strip_tags($_POST["item_name"]));
            @$amount=stripslashes(strip_tags($_POST["amount"]));//$_POST["amount"];
            @$firstname=stripslashes(strip_tags($_POST["billing_first"]));
            @$lastname=stripslashes(strip_tags($_POST["billing_last"]));
            //$primaryaddress=$_POST["billing_street"];
            //$state=$_POST["billing_state"];
            //$city=$_POST["billing_city"];
            // $zipcode=$_POST["billing_zip"];
            @$UsersLimit=50;
            @$ClientsLimit=50;
            @$premisstion=1;
            $LastLogin=date("Y-m-d H:i:s");
             $db=new db();	
            $insert_data=$db->prepare("INSERT INTO users(username,email,password,firstname,lastname,created_at,UsersLimit,ClientsLimit,LastLogin) VALUES(:username, :email, :password,:firstname, :lastname,:LastLogin,:UsersLimit,:ClientsLimit,:LastLogin)");
            $insert_data->bindparam(":username",$username);
            $insert_data->bindparam(":email",$email);
            $insert_data->bindparam(":password",$password);
            $insert_data->bindparam(":firstname",$firstname);
            $insert_data->bindparam(":lastname",$lastname);
            // $insert_data->bindparam(":primaryaddress",$primaryaddress);
            // $insert_data->bindparam(":city",$city);
            // $insert_data->bindparam(":state",$state);
            // $insert_data->bindparam(":zipcode",$zipcode);
            $insert_data->bindparam(":UsersLimit",$UsersLimit);
            $insert_data->bindparam(":ClientsLimit",$ClientsLimit);
            $insert_data->bindparam(":LastLogin",$LastLogin);
            $insert_data->execute();
            $userid = $db->lastInsertId();

            // Edited 

            //  $insert_datap=$db->prepare("INSERT INTO users(adminid,sid,username,email,password,firstname,lastname,created_at,UsersLimit,ClientsLimit,LastLogin,usertype) VALUES(:adminid, :sid, :username, :email, :password,:firstname, :lastname,:LastLogin,:UsersLimit,:ClientsLimit,:LastLogin,:usertype)");
            // $insert_data_email->bindparam(":adminid",$userid);
            // $insert_data_email->bindparam(":sid",$userid);
            // $insert_datap->bindparam(":username",$username);
            // $insert_datap->bindparam(":email",$email);
            // $insert_datap->bindparam(":password",$password);
            // $insert_datap->bindparam(":firstname",$firstname);
            // $insert_datap->bindparam(":lastname",$lastname);
            // // $insert_data->bindparam(":primaryaddress",$primaryaddress);
            // // $insert_data->bindparam(":city",$city);
            // // $insert_data->bindparam(":state",$state);
            // // $insert_data->bindparam(":zipcode",$zipcode);
            // $insert_datap->bindparam(":UsersLimit",$UsersLimit);
            // $insert_datap->bindparam(":ClientsLimit",$ClientsLimit);
            // $insert_datap->bindparam(":LastLogin",$LastLogin);
            // $insert_datap->bindparam(":usertype",'user');
            // $insert_datap->execute();

            // End Edited

            $username=$_POST['username'];
            $email=$_POST['email'];
            $insert_data_email=$db->prepare("INSERT INTO EmailSetting(fmail,fname,UserID) VALUES(:email,:username,:userid)");
            $insert_data_email->bindparam(":username",$username);
            $insert_data_email->bindparam(":email",$email);
            $insert_data_email->bindparam(":userid",$userid);
            $insert_data_email->execute();	
            if($insert_data)
            {
                $other['--USERNAME--'] = $username;
                $other['--EMAIL--'] = $email;
                $other['--PASSWORD--'] = $userassword;
                $headers = '';
                $message="Hi ";
                sendCMail($email, "Welcome to ".$username."!", "comapny-register.php", $message, $headers, $other);
                $_SESSION["UserName"] = $username;
                $_SESSION["UserID"] = $userid;
                $_SESSION['usertype']="subscriber";
                $sid=$userid;
                 $db=new db();
                $insert_data2=$db->prepare("INSERT INTO subscriber(sname,sid) VALUES(:username,:sid)");
                $insert_data2->bindparam(":username",$username);
                $insert_data2->bindparam(":sid",$sid);
                $insert_data2->execute();
                $_SESSION["sid"] = $sid;	
                @$credit_card_number=$_POST['credit_card_number'];
                @$credit_card_exp=$_POST['credit_card_exp'];
               // @$subscriptionId=$xml->subscriptionId;
                @$subscriptionId='test';
                // @$status=$xml->status;
                @$status='Active';
                $paytime= date("Y-m-d");
                $date = strtotime('+3 months');
                $packend= date('Y-m-d', $date);
                $insert_dat_pay=$db->prepare("INSERT INTO payments(subscriptionId,PackageType,amount,userid,paytime,status,packend) VALUES(:subscriptionId,'Trial',0.00,:userid,:paytime,:status,:packend)");
                $insert_dat_pay->bindparam(":subscriptionId",$subscriptionId);
                // $insert_dat_pay->bindparam(":PackageType",$PackageType);
                // $insert_dat_pay->bindparam(":amount",$amount);
                $insert_dat_pay->bindparam(":userid",$userid);
                $insert_dat_pay->bindparam(":status",$status);
                $insert_dat_pay->bindparam(":paytime",$paytime);
                $insert_dat_pay->bindparam(":packend",$packend);
                $insert_dat_pay->execute();	
                $updatedfk=$userid;
                $createdfk=$userid;
                $insert_dat_com=$db->prepare("INSERT INTO CompanyInformation(createdfk,updatedfk) VALUES(:createdfk,:updatedfk)");
                $insert_dat_com->bindparam(":createdfk",$createdfk);
                $insert_dat_com->bindparam(":updatedfk",$updatedfk);
                $insert_dat_com->execute(); 


                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'New Registrion has been done';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Registration has been done successfully']);die;
            }
            else
            {
                echo json_encode(['error'=>'Please Check your Card Detail']);die;
            }
        //}
    }
    public function GetUserData()
    {
        if(isset($_SESSION['UserID']))
        {
            $id=$_SESSION['UserID'];
             $db=new db();
            $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // echo json_encode(['resonse'=>$Result]);
            // print_r($result);
            //  die();		  
        }
    }
    public function update()
    {
        $validation=$this->Regstration_Validation_for_update();
        if(!empty($validation))
        {
            $error=implode("\n",$validation);
            echo json_encode(['error'=>$error]);die;
        }
        if(isset($_SESSION['UserID']))
        {
            $id=$_SESSION['UserID'];
            $username=stripslashes(strip_tags($_POST["username"]));//$_POST['username'];
            $firstname=stripslashes(strip_tags($_POST["firstname"]));
            $lastname=stripslashes(strip_tags($_POST["lastname"]));
            $email=$_POST['email'];
            $phonenumber=stripslashes(strip_tags($_POST["phonenumber"]));
            $companyname=stripslashes(strip_tags($_POST["companyname"]));
            $companytype=stripslashes(strip_tags($_POST["companytype"]));
            $companywebsite=stripslashes(strip_tags($_POST["companywebsite"]));
            $primaryaddress=stripslashes(strip_tags($_POST["primaryaddress"]));
            $secondaryaddress=stripslashes(strip_tags($_POST["secondaryaddress"]));
            $zipcode=stripslashes(strip_tags($_POST["zipcode"]));
            $city=stripslashes(strip_tags($_POST["city"]));
            $state=stripslashes(strip_tags($_POST["state"]));
             $db=new db();
            $stmt=$db->prepare("update users set 
username=:username, firstname=:firstname, lastname=:lastname, email=:email, phonenumber=:phonenumber, companyname=:companyname, companytype=:companytype, companywebsite=:companywebsite, primaryaddress=:primaryaddress, secondaryaddress=:secondaryaddress, zipcode=:zipcode, city=:city, state=:state where id=:id");
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
            $stmt->bindparam(":id",$id);
            $stmt->execute();
            if($stmt)
            {
                $myactivite = new Activites();
                $Titile=$myactivite->Titile = 'Update Profile Details';	
                $myactivite->commit_acitve($Titile);
                echo json_encode(['resonse'=>'Your Profile has been Updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'sorry something wrong']);die;
            }
        }
    }
}
?>