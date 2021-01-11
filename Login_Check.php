<?php
require_once('function.php');
require_once('UserToken.php');

if(isset($_REQUEST['SetMaintenance']) )
{
    $SetMaintenance = $_REQUEST['SetMaintenance'];
    $stmt=$db->prepare("update users set Maintenance=:SetMaintenance where usertype='Admin' ");
    $stmt->bindParam(':SetMaintenance', $SetMaintenance);
    $run = $stmt->execute();

    if($run){
        if($SetMaintenance!=0){
            echo json_encode(['response'=>'Maintenance Mode Activated.']);
        }else{
            $stmt=$db->prepare("SELECT * from users where Maintenance='1' and usertype<>'Admin' ");
            $run = $stmt->execute();
            $result  = $stmt->fetchAll();


            $other = [];
            foreach ($result as $key => $value) {

                $other['--FIRSTNAME--'] = $value['firstname'];
                $send = sendCMail($value['email'], "Mysunless Maintenance", "Maintenance.php", '', '', $other,'');

                if($send){
                    $stmt=$db->prepare("update users set Maintenance='0' where id=:id ");
                    $stmt->bindParam(':id', $value['id']);
                    $run = $stmt->execute();
                }

            }

            echo json_encode(['response'=>'Maintenance Mode Deactivated.']);
        }
    }else{
        echo json_encode(['error'=>'Something went wrong.']);
    }
    die;
}

if(isset($_POST['UserName']) && isset($_POST['password']) && isset($_REQUEST['LoginAction']) ){

    $user = $_POST['UserName'];
    $pass = md5($_POST['password']);
    if(empty($user) || empty($pass)) 
    {
        $messeg = "Username/Password can't be empty";
    } 
    else
    {


        $db=new db();
        $status='Active';
        $LoginQuery = $db->prepare("SELECT * FROM `users` WHERE (email=:user AND password=:pass AND status=:status)  OR (username=:user AND password=:pass AND status=:status)");
        $LoginQuery->bindValue(':user', $user);
        $LoginQuery->bindValue(':pass', $pass);
        $LoginQuery->bindValue(':status', $status);
        $LoginQuery->execute();
        $LoginQuery->rowCount();
        if($LoginQuery->rowCount() == 1) 
        {

            $LoginData = $LoginQuery->fetch();
            echo SetLogin($LoginData);


        }else if($LoginQuery->rowCount() > 1){
            echo json_encode(['error'=>'Your credentials might be conflict with other user. Please login with Username or Please contact the site administrator regarding this.']);die;
        }
        else
        {
            echo json_encode(['error'=>'Your credentials might be wrong please enter the correct one or your account has been suspended! Please contact the site administrator regarding this.']);die;
        }
    }

}

if(isset($_REQUEST['LoginToken']) && !empty($_COOKIE["REMEMBERSAVEDUSERS"])){

    $response = CheckUserToken();

    if(isset($response['response'])){

        $id = $response['response'];

    $LoginQuery = $db->prepare("SELECT * FROM `users` where id=:id");
        $LoginQuery->bindValue(':id', $id);
        $LoginQuery->execute();
        $LoginQuery->rowCount();
        if($LoginQuery->rowCount() == 1) 
        {

            $LoginData = $LoginQuery->fetch();
            echo SetLogin($LoginData);


        }else if($LoginQuery->rowCount() > 1){
            echo json_encode(['error'=>'Your credentials might be conflict with other user. Please login with Username or Please contact the site administrator regarding this.']);die;
        }
        else
        {
            echo json_encode(['error'=>'Your credentials might be wrong please enter the correct one or your account has been suspended! Please contact the site administrator regarding this.']);die;
        }

    }else if(isset($response['error'])){
        DestroyUserToken();
        $error = $response['error'];
        echo json_encode(['error'=>$error]);die;
    }else{
        DestroyUserToken();
        echo json_encode(['error'=>'Something went with token.']);die;
    }

}

if(isset($_REQUEST['DeleteToken'])){
    DestroyUserToken();
    echo json_encode(['response'=>true]);die;
}

function SetLogin($LoginData=[]){

    $db = new db();
    $UserID = $LoginData['id'];


    $stmt = $db->prepare("SELECT Maintenance FROM `users` where usertype='Admin' ");
    $stmt->execute();
    $MaintenanceResult = $stmt->fetch();
    if($MaintenanceResult['Maintenance']==1 && $LoginData['usertype']!='Admin'){

        $stmt=$db->prepare("UPDATE users set Maintenance='1' where id=:id");
        $stmt->bindparam(":id",$UserID);
        $stmt->execute();    

        return json_encode(['error'=>'The website is under maintenance.<br> We will inform you very soon on your registered mail.']);die;
    }





    if($LoginData['login_permission'] == '0' )
    {
        return json_encode(['error'=>'Sorry,your account has been suspended! Please contact the site administrator regarding this.']);die;
    }

    $_SESSION['UserID'] = $UserID;
    $_SESSION['UserName'] = $LoginData['username'];
    $_SESSION['flname'] = $LoginData['firstname']." ".$LoginData['lastname'];
    $_SESSION['fname'] = $LoginData['firstname'];
    $_SESSION['lname'] = $LoginData['lastname'];
    $_SESSION['usertype'] = $LoginData['usertype'];

    $_SESSION['adminid'] = $LoginData['adminid'];

    $id=$_SESSION['UserID'];
    $loginstatus=1;

    $LastLogin=date("Y-m-d H:i:s");
    $sessionid= session_id();
    $stmt=$db->prepare("UPDATE users set loginstatus=:loginstatus,sessionid=:sessionid, LastLogin=:LastLogin where id=:id");
    $stmt->bindparam(":LastLogin",$LastLogin);
    $stmt->bindparam(":loginstatus",$loginstatus);
    $stmt->bindparam(":sessionid",$sessionid);
    $stmt->bindparam(":id",$id);
    $stmt->execute();

    $UserId=$id;
    $LoginTime=$LastLogin;
    $stmt2=$db->prepare("INSERT INTO ActiveUser(UserId,LoginTime,REMOTE_ADDR,HTTP_USER_AGENT) VALUES (:UserId,:LoginTime,:REMOTE_ADDR,:HTTP_USER_AGENT) ");
    $stmt2->bindparam(":UserId",$UserId);
    $stmt2->bindparam(":LoginTime",$LoginTime);
    $stmt2->bindparam(":REMOTE_ADDR",$_SERVER['REMOTE_ADDR']);
    $stmt2->bindparam(":HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT']);
    $stmt2->execute();
    $activeuserid = $db->lastInsertId();
    $_SESSION['activeuserid'] = $activeuserid;
    $_SESSION['LastLogin'] = $LastLogin;



    if(!empty($_POST["RememberUser"])) 
    {
        setcookie("RememberToken", '1' , time() + (86400 * 30), "/");
    }

    if(!empty($_SESSION['activeuserid'])){
        CreateUserToken();
    }



    if($stmt)
    {
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = $_SESSION['UserName'].' Login';   
        $myactivite->commit_acitve($Titile);

        return json_encode(['response'=>'Your are successfully login','id'=>base64_encode($activeuserid)]);die;
    }
    else
    {
        return json_encode(['error'=>'Sorry something wrong']);die;
    }
}

if(isset($_REQUEST['CheckVerificationAccount'])){

    $id = base64_decode($_REQUEST['CheckVerificationAccount']);
    echo SendVerificationAccountMail($id);die;

}

function SendVerificationAccountMail($id){

    $db = new db();

    $stmt=$db->prepare("Select * from ActiveUser where UserId = (SELECT UserId from ActiveUser where id =:id) and HTTP_USER_AGENT = :HTTP_USER_AGENT and Validity='1' order by id desc limit 2 ");
    $stmt->bindparam(":HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT']);
    $stmt->bindparam(":id",$id);
    $stmt->execute();
    $result = $stmt->fetch();


    if($stmt->rowCount()<=1){


        $stmt=$db->prepare("Select * from users where id =:id");
        $stmt->bindparam(":id",$result['UserId']);
        $stmt->execute();
        $Users = $stmt->fetch();

        $other['--USERNAME--'] = $Users['username'];
        $other['--FIRSTNAME--'] = $Users['firstname'];
        $other['--LASTNAME--'] = $Users['lastname'];
        $other['--TOKENID--'] = base64_encode($id);
        $other['--LOGINTIME--'] = $result['LoginTime'].' (UTC)';

        $email = $Users['email'];
        
        $headers = '';
        $message = '';

        $SendResponse = SendVerificationMail($email, "Alert", "Alert.php", $message, $headers, $other,''); 

        return json_encode(['response'=>$SendResponse]);

    }else{
        return json_encode(['response'=>false]);
    }

}

?>