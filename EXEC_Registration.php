<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once('global.php');

//require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/exec-registration.php");
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/Class.Registration.php");
//$obj=new model;

if(isset($_GET['check_email'])){

    $obj = new model;
    echo $obj->Registration_form_validation();die;
}

if(isset($_GET['check_accout'])){
	
    $obj = new model;
    echo $obj->Registration_form_Step1validation();die;
}

if(isset($_GET['registration_insert'])){
	
    $obj = new model;
    echo $obj->insert();die;
}

if(isset($_REQUEST['id']))
{
    $sel = new model;
    $sel->GetUserData();
}
if(isset($_REQUEST['insert']))
{
    $ins =new model;
    $new_reg=$ins->insert();
    if($new_reg)
    {
        $myactivite = new Activites();
        $Titile=$myactivite->Titile = 'New registrion has been done';	
        $myactivite->commit_acitve($Titile);
    }
}
if(isset($_REQUEST['update']))
{
    $ins =new model;
    $ins->update();
}

if(isset($_REQUEST['CheckOTP']) && isset($_REQUEST['EmailOTP']) ){
    $ins =new model;
    $OTP = $_REQUEST['EmailOTP'];
    echo $ins->CheckOTP($OTP);die;
}

if(isset($_GET['check_username'])){
    $obj = new model;

    if(isset($_GET['firstname']) && isset($_GET['lastname'])){

        while(true){


            $rand_no = 200;
            $first = $_GET['firstname'];
            $last = $_GET['lastname'];

            $part1 = substr($first, 0,rand(strlen($first)/2,strlen($first)));  
            $part2 = substr($last, 0,rand(strlen($last)/2,strlen($last)));
            $part3 = rand(0, $rand_no);

            $username = $part1.$part2.$part3;
            // $username_exist_in_db = $obj->check_username($username); 
            //echo $username_exist_in_db;
            if($obj->check_username($username)){

               echo $username;
               return false;die;
            }
        }

    }else if(isset($_GET['username'])){

        $username = $_GET['username'];
        if($obj->check_username($username))
        {
            echo json_encode(true);die;
        }else
        {
            echo json_encode("This Username already exists.");die;
        }

    }

}
?>