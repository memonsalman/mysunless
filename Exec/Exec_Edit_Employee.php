<?php
require_once('Exec_Config.php');        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.Employee.php');

if(@$_POST["userid"] && isset($_POST["username"])){

    if(isset($_POST["username"]) && $_POST["username"]!="" && isset($_POST["email"]) && $_POST["email"]!=""){
        $username= $_POST["username"];
        $useremail = $_POST["email"] ;
        $db= new db();
        $Allusers= $db->prepare ("SELECT  `username`, `email` FROM `users` WHERE (`username`=:username OR `email`=:email) and id <> :userid and id IN (select id from users where (id=:user_id or adminid=:user_id or sid=:user_id)) ");
        $Allusers->bindparam(':username',$username, PDO::PARAM_STR);
        $Allusers->bindparam(':email',$useremail, PDO::PARAM_STR);
        $Allusers->bindparam(':user_id',$_SESSION['UserID']);
        $Allusers->bindparam(':userid',$_POST['userid']);

        $Allusers->execute();

        if ( $Allusers->rowCount() > 0 ){
            $Allusers=$Allusers->fetchAll();
            foreach ($Allusers as $value) {
                if($value['username'] == $username){
                    echo  json_encode(["error"=>'Employee name already exists. please try again.']);die;
                }
                if($value['email'] == $useremail){
                    echo  json_encode(["error"=>'This email already exists. please try again.']);die;
                }
            }
        }
    }else{
        echo json_encode(["error"=>'Empty Username or Email']);die;
    }
}
// End checking


// Check the Email id and Password are not same in Employee Registraion Time.
if(isset($_POST['userid']) && isset($_POST['email']) && isset($_POST['Password']) && $_POST['newpassword']=="" && $_POST['confirm_password']=="")
{
  // echo 'test';
  // echo $_POST['userid'].'<br>';
  // echo $_SESSION['UserID'].'<br>';
  // die();
  $email=$_POST['email'];  
  $db5=new db(); 

  if($_POST['userid']=='new')
  {
    $password=md5($_POST['Password']);
    $Allcheck=$db5->prepare("SELECT * from `users` where `email`=:email and `password`=:password  ");
  }
  else
  {   
    $password=$_POST['Password'];
    $Allcheck=$db5->prepare("SELECT * from `users` where `email`=:email and `password`=:password and (`id`!=:user_id) ");
     $Allcheck->bindparam(':user_id',$_POST['userid']);
  } 
 
  $Allcheck->bindparam(':email',$email);
  $Allcheck->bindparam(':password',$password);
  $Allcheck->execute();

  if($Allcheck->rowCount()>0)
  {
    echo json_encode(["error"=>"Please Change Email Or Password for Some Security Reasons."]);
    die();
  }  

} // End Check email and Password for same as Employee Login.

  // Change Password Check if email and same password are not Exists in Database.
  if(isset($_POST['userid']) && isset($_POST['newpassword']) && isset($_POST['confirm_password']))
  {

    $email= $_POST['email'];
    $userid=$_POST['userid'];
    $newpassword= md5($_POST['confirm_password']);
    
    $db6=new db();
    $passcheck=$db6->prepare("SELECT * from `users` where `email`=:email and `password`=:newpassword and (`id`!=:user_id)");

    $passcheck->bindparam(':user_id',$userid);
    $passcheck->bindparam(':email',$email);
    $passcheck->bindparam(':newpassword',$newpassword);
    $passcheck->execute();
    // echo $passcheck->rowCount();
    // die();
    
    if($passcheck->rowCount()>0)
    {
      echo json_encode(["errorchangepwd"=>"Please Try to Another Password for Some Security Resaons."]);
      die();
    }
  }


if(isset($_POST['LoginAction']))
{

    @$userid = new Employee(@$_POST["userid"]);
    @$userid->id = @$_POST["userid"];

    $date = array( 
        "0" => array (
           "Monday" => @$_POST['Monday'],
           "starttime" => @$_POST['mondayst'], 
           "endtime" => @$_POST['mondayet']
       ),

        "1" => array (
           "Tuesday" => @$_POST['Tuesday'],
           "starttime" => @$_POST['tuesdayst'], 
           "endtime" => @$_POST['tuesdayet']
       ),

        "2" => array (
           "Wednesday" => @$_POST['Wednesday'],
           "starttime" => @$_POST['wednesdayst'], 
           "endtime" => @$_POST['wednesdayet'],
       ),

        "3" => array (
           "Thursday" => @$_POST['Thursday'],
           "starttime" => @$_POST['thursdayst'],
           "endtime" => @$_POST['thursdayet'],
       ),

        "4" => array (
           "Friday" => @$_POST['Friday'],
           "starttime" => @$_POST['fridayst'],
           "endtime" => @$_POST['fridayet'],
       ),

        "5" => array (
           "Saturday" => @$_POST['Saturday'],
           "starttime" => @$_POST['saturdayst'],
           "endtime" => @$_POST['saturdayet'],
       ),

        "6" => array (
           "Sunday" => @$_POST['Sunday'],
           "starttime" => @$_POST['sundayst'],
           "endtime" => @$_POST['sundayet'],
       ),


    );
    $finaldata = json_encode($date);
    $userid->timetable = $finaldata;
    $userid->commit2($userid->id,$finaldata);   
    if($userid)
    {
      echo  json_encode(["resonse"=>'Employee time has been successfully updated']);die;   
  }

  
}

// && empty($_POST["current_password"])
if(isset($_POST["userid"]) && empty($_POST["newpassword"]))
{   

    @$userid = new Employee(@$_POST["userid"]);
    @$userid->id = @$_POST["userid"];
    if($_POST["userid"]=="new" && !empty($_POST['firstname']))
    {

        $userid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"]));
        $userid->sid =stripslashes(strip_tags($_POST["sid"]));
        $userid->usertype =stripslashes(strip_tags($_POST["usertype"]));
        $username= $userid->username =stripslashes(strip_tags($_POST["username"])); 
        $userid->firstname =stripslashes(strip_tags($_POST["firstname"])); 
        $userid->lastname =stripslashes(strip_tags($_POST["lastname"]));
        if(isset($_POST['Password']) && !empty($_POST['Password']))
        {
          $userid->Password = md5($_POST["Password"]);    
      }

      $userid->email =stripslashes(strip_tags($_POST["email"])); 
      $userid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"]));
      $userid->companyname =stripslashes(strip_tags($_POST["companyname"])); 
      $userid->companytype =stripslashes(strip_tags($_POST["companytype"]));
      $userid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"])); 
      $userid->primaryaddress =stripslashes(strip_tags($_POST["primaryaddress"])); 
      $userid->secondaryaddress =stripslashes(strip_tags($_POST["secondaryaddress"]));
      $userid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
      $userid->city =stripslashes(strip_tags($_POST["city"]));
      $userid->state =stripslashes(strip_tags($_POST["state"]));
      $userid->country =stripslashes(strip_tags($_POST["country"]));

      
      if($userid->id == "new")
      {

       $chkEmpCount=$userid->checkEmpCount();
       $empCount = $chkEmpCount['totalEmp'];
       $empCrtLimit = $chkEmpCount['employeeLimit']; 

       if($empCount >= $empCrtLimit)
       {
        echo  json_encode(["warning"=>"Your current package employee' limit has been over"]);die;
    }
    else{
        $newemployee=$userid->commit($userid->id);

        if(!empty($_FILES["userimg"]["name"]))
        {

            $user_id = $newemployee;
            $Iname=explode(".",$_FILES["userimg"]["name"]);
            $ImgObj= new AllFunction;
            $ImgFileName=$ImgObj->ImgName();
            $userimg = $ImgFileName.".".$Iname[1]; 
            $path = DOCUMENT_ROOT.ESUB."/assets/userimage/";
            $path = $path . basename($userimg);


            if(move_uploaded_file($_FILES["userimg"]["tmp_name"], $path)) 
            {
                $sql=$db->prepare("Update users set userimg = :userimg where id=:userid");
                $sql->bindValue(':userid',$user_id,PDO::PARAM_INT);
                $sql->bindValue(':userimg',$userimg,PDO::PARAM_INT);
                if($sql->execute()){

                }else{
                    echo  json_encode(["errorfilldata"=>'Employee Image was not uploaded please try again.']);die;
                }
            }
            else
            {
                echo  json_encode(["errorfilldata"=>'Employee Image was not uploaded please try again.']);die;
            }
        }

        $userid->AddCompanyInformation($newemployee);

        $userid->ActivitesCount($newemployee); 
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = 'Add New Employee '.$username;    
        $myactivite->commit_acitve($Titile);
        echo  json_encode(["resonse"=>'Employee has been successfully created']);die;
    } 
}   



}
else if($_POST["userid"]=="new" && empty($_POST['firstname']) && !empty($_FILES["userimg"]["name"]))
{	
    echo  json_encode(["errorfilldata"=>'Please create employee first']);die;
}
else if($_POST["userid"]!=="new" && !empty($_FILES["userimg"]["name"]))
{
    $user_id = $_POST["userid"];
    $Iname=explode(".",$_FILES["userimg"]["name"]);
    $ImgObj= new AllFunction;
    $ImgFileName=$ImgObj->ImgName();
        // $userid->userimg = $ImgFileName.".".$Iname[1]; 
    $userimg = $ImgFileName.".".$Iname[1]; 
    $path = DOCUMENT_ROOT.ESUB."/assets/userimage/";

        // $path = $path . basename($userid->userimg);
    $path = $path . basename($userimg);


    if(move_uploaded_file($_FILES["userimg"]["tmp_name"], $path)) 
    {
        $sql=$db->prepare("Update users set userimg = :userimg where id=:userid");
        $sql->bindValue(':userid',$user_id,PDO::PARAM_INT);
        $sql->bindValue(':userimg',$userimg,PDO::PARAM_INT);
        if($sql->execute()){
            echo  json_encode(["resonseimg"=>'Employee image has been successfully updated']);die;
        }else{
            echo  json_encode(["errorfilldata"=>'Employee Image was not uploaded please try again.']);die;
        }
    }
    else
    {
        echo  json_encode(["errorfilldata"=>'Employee Image was not uploaded please try again.']);die;
    }
        /*$userid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"])); //$_POST["UsersLimit"];
        $userid->sid =stripslashes(strip_tags($_POST["sid"]));
        $userid->usertype =stripslashes(strip_tags($_POST["usertype"]));
        $userid->username =stripslashes(strip_tags($_POST["username"])); 
        $userid->firstname =stripslashes(strip_tags($_POST["firstname"])); 
        $userid->lastname =stripslashes(strip_tags($_POST["lastname"]));

        if(isset($_POST['Password']))
        {
          $userid->Password = md5($_POST["Password"]);
      }
      $userid->email =stripslashes(strip_tags($_POST["email"])); 
      $userid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"]));
      $userid->companyname =stripslashes(strip_tags($_POST["companyname"])); 
      $userid->companytype =stripslashes(strip_tags($_POST["companytype"]));
      $userid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"])); 
      $userid->primaryaddress =stripslashes(strip_tags($_POST["primaryaddress"])); 
      $userid->secondaryaddress =stripslashes(strip_tags($_POST["secondaryaddress"]));
      $userid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
      $userid->city =stripslashes(strip_tags($_POST["city"]));
      $userid->state =stripslashes(strip_tags($_POST["state"]));
      $userid->country =stripslashes(strip_tags($_POST["country"]));
      $userid->commit($userid->id);	
      $myactivite = new Activites();
      $Titile=$myactivite->Titile = 'Update '.$userid->username.' Image';		
      $myactivite->commit_acitve($Titile);*/
      // echo  json_encode(["resonseimg"=>'Employee image has been successfully updated']);die;
  }
  elseif($_POST["userid"]!=="new" && !empty($_POST['firstname']))
  {

        $userid->UsersLimit2 =stripslashes(strip_tags($_POST["UsersLimit"])); //$_POST["UsersLimit"];
        $userid->sid =stripslashes(strip_tags($_POST["sid"]));
        $userid->usertype =stripslashes(strip_tags($_POST["usertype"]));
        $userid->username =stripslashes(strip_tags($_POST["username"])); 
        $userid->firstname =stripslashes(strip_tags($_POST["firstname"])); 
        $userid->lastname =stripslashes(strip_tags($_POST["lastname"]));
        if(isset($_POST['Password']))
        {
            $userid->Password = $_POST["Password"];
        // $userid->Password = md5($_POST["Password"]);
        }
        $userid->email =stripslashes(strip_tags($_POST["email"])); 
        $userid->phonenumber =stripslashes(strip_tags($_POST["phonenumber"]));
        $userid->companyname =stripslashes(strip_tags($_POST["companyname"])); 
        $userid->companytype =stripslashes(strip_tags($_POST["companytype"]));
        $userid->companywebsite =stripslashes(strip_tags($_POST["companywebsite"])); 
        $userid->primaryaddress =stripslashes(strip_tags($_POST["primaryaddress"])); 
        $userid->secondaryaddress =stripslashes(strip_tags($_POST["secondaryaddress"]));
        $userid->zipcode =stripslashes(strip_tags($_POST["zipcode"]));
        $userid->city =stripslashes(strip_tags($_POST["city"]));
        $userid->state =stripslashes(strip_tags($_POST["state"]));
        $userid->country =stripslashes(strip_tags($_POST["country"]));
        $userid->commit($userid->id);	 
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = 'Update '.$userid->username.' Details';
        $myactivite->commit_acitve($Titile);
        echo  json_encode(["resonse"=>'Employee details has been successfully updated']);die;
    }
    else
    {
        echo  json_encode(["errorfill"=>'Please fill all data first']);die;	 	
    }
}
if($_POST["userid"]!="new" && $_POST['current_password']!="" && $_POST["newpassword"]!="")
{

    @$userid = new Employee(@$_POST["userid"]);
    
    @$userid->id = @$_POST["userid"];
    
    $currntuser=$_POST['userid'];
    $currnt=$_POST["current_password"];
    $db2=new db();
    $stmt= $db2 ->prepare("SELECT password FROM `users` WHERE id=:currntuser"); 
    $stmt->bindParam(':currntuser', $currntuser, PDO::PARAM_STR);
    $stmt->execute();
    $result =$stmt->fetchAll();
    $oldpass=$result[0]['password'];	
    if($currnt==$oldpass)
    {

        $userid->Password = md5($_POST["newpassword"]);
        @$userid->commit($userid->id);	


        if(@$userid)
        {
            $myactivite = new Activites();
            if(@$_POST['userid']=="new")
            {
                $Titile=$myactivite->Titile = 'Change user password';	
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update user password';		
            }
            $myactivite->commit_acitve($Titile);
            echo  json_encode(["resonsepac"=>'Employee password has been successfully updated']);die;
        }
        else
        {
            echo  json_encode(["error"=>'Something Wrong']);die;
        }
    }
    else
    {
        echo  json_encode(["errorpc"=>'Please enter correct current password']);die;	 	
    }

}
else
{
	
 echo  json_encode(["error"=>'Something Wrong']);die;
}


?>