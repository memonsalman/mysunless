<?php
require_once('Exec_Config.php');        
    

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.Subuser.php'); 
// check that user name or email exist
if($_POST["subuserid"]=="new"){
    $username= $_POST["username"];
    $useremail = $_POST["email"] ;
    $db= new db();
    $Allusers= $db->prepare ("SELECT  `username`, `email` FROM `users` WHERE `username`=:username OR `email`=:email ");
    $Allusers->bindparam(':username',$username, PDO::PARAM_STR);
    $Allusers->bindparam(':email',$useremail, PDO::PARAM_STR);
    $Allusers->execute();
    if ( $Allusers->rowCount() > 0 ){
        $Allusers=$Allusers->fetchAll();
        foreach ($Allusers as $value) {
            if($value['username'] == $username){
                echo  json_encode(["error"=>'Username already exists.']);die;
            }
            if($value['email'] == $useremail){
                echo  json_encode(["error"=>'This email already exists.']);die;
            }
        }
    }
}

$subuserid = new SubUser($_POST["subuserid"]);
$subuserid->id = $_POST["subuserid"];
if(isset($_POST["subuserid"])  && empty($_POST['newpassword']) && empty($_POST['confirm_password']) )
{
    if($_POST["subuserid"]=="new" && !empty($_POST['firstname']))
    {
        $subuserid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"])); //$_POST["UsersLimit"];
        $subuserid->sid =stripslashes(strip_tags($_POST["sid"]));
        @$subuserid->usertype =stripslashes(strip_tags($_POST["usertype"]));
        $username=$subuserid->username =stripslashes(strip_tags($_POST["username"]));
        $subuserid->firstname =stripslashes(strip_tags($_POST["firstname"]));
        $subuserid->lastname =stripslashes(strip_tags($_POST["lastname"]));
        $userassword=stripslashes(strip_tags($_POST["Password"]));
        $subuserid->Password = md5($_POST["Password"]);
        $email=$subuserid->email =stripslashes(strip_tags($_POST["email"]));
        $subuserid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"])); 
        $subuserid->companyname =stripslashes(strip_tags($_POST["companyname"]));
        $subuserid->companytype =stripslashes(strip_tags($_POST["companytype"])); 
        $subuserid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"]));
        $subuserid->primaryaddress = stripslashes(strip_tags($_POST["primaryaddress"]));
        $subuserid->secondaryaddress = stripslashes(strip_tags($_POST["secondaryaddress"]));
        $subuserid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
        $subuserid->city =stripslashes(strip_tags($_POST["city"])); 
        $subuserid->state = stripslashes(strip_tags($_POST["state"]));
        $subuserid->country = stripslashes(strip_tags($_POST["country"]));
        $newuser=$subuserid->commit($subuserid->id);
        if($subuserid)	
        {	
            $subuserid->ActivitesCount($newuser); // This function for data insert in CountActivites.
            $myactivite = new Activites();
            if($_POST['subuserid']=="new")
            {
                $Titile=$myactivite->Titile = 'Add new user '.$username;	 	
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update user details '.$username;		
            }
            $myactivite->commit_acitve($Titile);
            $other['--USERNAME--'] = $username;
            $other['--EMAIL--'] = $email;
            $other['--PASSWORD--'] = $userassword;
            $headers = '';
            $message="Hi ";
            sendCMail($email, "Registration!", "comapny-register.php", $message, $headers, $other);
        }
        echo  json_encode(["resonse"=>'User has been Successfully Created']);die;
    }
    elseif($_POST["subuserid"]=="new" && empty($_POST['firstname']) && !empty($_FILES["userimg"]["name"]))
    {
        echo  json_encode(["errorfilldata"=>'Please create user first']);die;
    }
    elseif($_POST["subuserid"]!="new" && !empty($_FILES["userimg"]["name"]))
    {
        // echo "<pre>";
        // echo gettype($_POST["zipcode"]);
        // print_r($_POST);die;
        $Iname=explode(".",$_FILES["userimg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        $subuserid->userimg = $ImgFileName.".".$Iname[1]; 
        $path = DOCUMENT_ROOT.ESUB."/assets/userimage/";
        $path = $path . basename($subuserid->userimg);
        if(move_uploaded_file($_FILES["userimg"]["tmp_name"], $path)) 
        {
        }
        else
        {
            $userimg = "Client Image was not uploaded please try again.";
        }
        @$subuserid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"])); //$_POST["UsersLimit"];
        @$subuserid->sid =stripslashes(strip_tags($_POST["sid"]));
        @$subuserid->usertype =stripslashes(strip_tags(trim($_POST["usertype"]," ")));
        @$subuserid->username =stripslashes(strip_tags($_POST["username"])); 
        @$subuserid->firstname =stripslashes(strip_tags($_POST["firstname"])); 
        @$subuserid->lastname =stripslashes(strip_tags($_POST["lastname"]));
        @$subuserid->Password =stripslashes(strip_tags($_POST["Password"]));
        @$subuserid->email =stripslashes(strip_tags($_POST["email"])); 
        @$subuserid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"]));
        @$subuserid->companyname =stripslashes(strip_tags($_POST["companyname"])); 
        @$subuserid->companytype =stripslashes(strip_tags($_POST["companytype"]));
        @$subuserid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"])); 
        @$subuserid->primaryaddress =stripslashes(strip_tags($_POST["primaryaddress"])); 
        @$subuserid->secondaryaddress =stripslashes(strip_tags($_POST["secondaryaddress"]));
        @$subuserid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
        @$subuserid->city =stripslashes(strip_tags($_POST["city"]));
        @$subuserid->state =stripslashes(strip_tags($_POST["state"]));
        @$subuserid->country = stripslashes(strip_tags($_POST["country"]));
        $subuserid->commit($subuserid->id);	
        $myactivite = new Activites();
        if($_POST['subuserid']=="new")
        {
            $Titile=$myactivite->Titile = 'Upload '.$subuserid->username.' image';	
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update '.$subuserid->username.' image';		
        }
        $myactivite->commit_acitve($Titile);
        echo  json_encode(["resonseimg"=>'User Image has been successfully updated']);die;
    }
    elseif($_POST["subuserid"]!="new" && !empty($_POST['firstname']))
    {   
        @$subuserid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"])); //$_POST["UsersLimit"];
        @$subuserid->sid =stripslashes(strip_tags($_POST["sid"]));
        @$subuserid->usertype =stripslashes(strip_tags(trim($_POST["usertype"]," ")));
        //@$subuserid->usertype =stripslashes(strip_tags(trim($_POST["Usertype"]," ")));
        @$subuserid->username =stripslashes(strip_tags($_POST["username"])); 
        @$subuserid->firstname =stripslashes(strip_tags($_POST["firstname"])); 
        @$subuserid->lastname =stripslashes(strip_tags($_POST["lastname"]));
        @$subuserid->Password =stripslashes(strip_tags($_POST["Password"]));
        @$subuserid->email =stripslashes(strip_tags($_POST["email"])); 
        @$subuserid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"]));
        @$subuserid->companyname =stripslashes(strip_tags($_POST["companyname"])); 
        @$subuserid->companytype =stripslashes(strip_tags($_POST["companytype"]));
        @$subuserid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"])); 
        @$subuserid->primaryaddress =stripslashes(strip_tags($_POST["primaryaddress"])); 
        @$subuserid->secondaryaddress =stripslashes(strip_tags($_POST["secondaryaddress"]));
        @$subuserid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
        @$subuserid->city =stripslashes(strip_tags($_POST["city"]));
        @$subuserid->state =stripslashes(strip_tags($_POST["state"]));
        @$subuserid->country = stripslashes(strip_tags($_POST["country"]));
        @$subuserid->commit($subuserid->id);	 
        $myactivite = new Activites();
        if($_POST['subuserid']=="new")
        {
            $Titile=$myactivite->Titile = 'pdate '.$subuserid->username.' details';	
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update '.$subuserid->username.' details';		
        }
        $myactivite->commit_acitve($Titile);
        echo  json_encode(["resonse"=>'User Details has been successfully updated']);die;
    }
    else
    {
        echo  json_encode(["errorfill"=>'Please fill all data first']);die;	 	
    }
}
if(isset($_POST["subuserid"]) && isset($_POST['current_password']) && !empty($_POST['newpassword']) && !empty($_POST['confirm_password']))
{   

    $currntuser=$_POST['subuserid'];
    $currnt=$_POST["current_password"];
    $email=$_POST['email'];
    $usertype=$_POST['usertype'];
    $newpwd=md5($_POST["confirm_password"]);
    $db2=new db();   

    if($usertype=='employee')
    {

        // $selecteduser=$_POST['subuserid'];    
       
        $db2=new db();
        $checkpwd=$db2 ->prepare("SELECT * from `users` WHERE `email`=:email and `password`=:newpwd and (`id`!=:user_id)");

        $checkpwd->bindparam(':user_id',$currntuser);    
        $checkpwd->bindparam(':newpwd',$newpwd);
        $checkpwd->bindparam(':email',$email);
        $checkpwd->execute();

        if($checkpwd->rowCount()>0)
        {
            echo json_encode(["errorpc"=>"Please Try To another Password for Some Security Reasons."]);
            die();
        }
        else
        {
            $subuserid->Password = $newpwd; 
            $subuserid->commit($subuserid->id);
             if($subuserid)
             {
                $myactivite = new Activites();
                    if($_POST['subuserid']=="new")
                    {
                        $Titile=$myactivite->Titile = 'Change user password';   
                    }
                    else
                    {
                        $Titile=$myactivite->Titile = 'Update user password';       
                    }
                $myactivite->commit_acitve($Titile);
             echo  json_encode(["resonsepac"=>'User password has been successfully Updated.']);die;
            }
          
        }

    }
    else
    {        
        $stmt= $db2 ->prepare("SELECT password FROM `users` WHERE id=:currntuser"); 
        $stmt->bindParam(':currntuser', $currntuser, PDO::PARAM_STR);
        $stmt->execute();
        $result =$stmt->fetchAll();
        $oldpass=$result[0]['password'];    
        if($currnt==$oldpass)
        {
            $subuserid->Password = md5($_POST["newpassword"]);
            $subuserid->commit($subuserid->id); 
            if($subuserid)
            {
                $myactivite = new Activites();
                if($_POST['subuserid']=="new")
                {
                    $Titile=$myactivite->Titile = 'Change user password';   
                }
                else
                {
                    $Titile=$myactivite->Titile = 'Update user password';       
                }
                $myactivite->commit_acitve($Titile);
                echo  json_encode(["resonsepac"=>'User password has been successfully Updated.']);die;
            }
            else
            {
                echo  json_encode(["error"=>'something wrong']);die;
            }
        }
        else
        {
            echo  json_encode(["errorpc"=>'Please enter Correct current password']);die;        
        }
    }    
}
// if(empty($subuserid)){
// 	$return["response"] = "fail";
// 	$return["MSG"] = "User has been Not added";
// 	echo json_encode($return);die;
// }else{
// 	$return["response"] = "success";
// 	$return["MSG"] = "User has been added";
// 	echo json_encode($return);die;
// }
?>