<?php
date_default_timezone_set('UTC');
require_once('global.php');

require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/db.class.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/mail.php');

require_once(Classes.'/Class.Activites.php'); 

require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/UserToken.php');

$db=new db();	


if(!empty($_SESSION['activeuserid']) && isset($_COOKIE["SAVEDUSERS"]) && empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  basename($_SERVER['PHP_SELF'])!='Logout.php' ){

    $response = CheckUserToken();


    if($response){
        CreateUserToken();
    }else{
        DestroyUserToken();
        echo '<script>alert("Invalid Token. Please login again."); window.open("Logout.php","_self");</script>';
        header("Location: Logout.php");die;
    }

}
// else{

//     if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

//             header("HTTP/1.0 503 Not Found");die('Invalid Token');

//         }else{
//             echo '<script>alert("Invalid Token");</script>';
//             header("Location: dashboard.php");die;
//         }
// }


$stmt = $db->prepare("SELECT Maintenance FROM `users` where usertype='Admin' ");
$stmt->execute();
$MaintenanceResult = $stmt->fetch();
if($MaintenanceResult['Maintenance']==1){

    if( isset($_SESSION['superusertype']) || ( isset($_SESSION['superusertype']) && isset($_SESSION['oldusertype']) ) || (isset($_SESSION['usertype']) && $_SESSION['usertype']=='Admin' ) || basename($_SERVER['PHP_SELF'])=='index.php' || basename($_SERVER['PHP_SELF'])=='Login_Check.php' ){

    }else{
        if(isset($_SESSION['UserID'])){
            $stmt=$db->prepare("UPDATE users set Maintenance='1' where id=:id");
            $stmt->bindparam(":id",$_SESSION['UserID']);
            $stmt->execute(); 
        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

            header("HTTP/1.0 503 Not Found");die('Service Temporarily Unavailable');

        }else{
            session_destroy();
            header("Location: ".base_url."/Maintenance.php");die;
        }
    }

}


if(!empty($_REQUEST['csrf']))
{

    if($_SESSION['csrf'] != $_REQUEST['csrf'])
    {
        echo json_encode(['csrf_error'=>'There was an error in processing your request. Your token does not match. Please try again.']);die;
    }
}

class AllFunction{
    public function ImgName(){
        $TmpName= date('Y:m:d H:i:s');
        $TmpName=str_replace(':', '', $TmpName);
        $TmpName=str_replace(' ', '', $TmpName);
        return $TmpName;
    }
}
function EncodeId($id,  $salt = "www.mysunless.com"){
    return randomString().base64_encode($id).randomString();
    //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $id, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}
function DecodeId($id,  $salt = "www.mysunless.com"){
    $id = substr($id,1,-1);
    return base64_decode($id);
}
function randomString($length = 1) {
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}
$AllFunction = new AllFunction();
if(!empty($_SESSION['UserID']))
{
    
    $id= $_SESSION['UserID'];   
    $deleteactivity=$db->prepare("DELETE FROM `Activities` where UserID=:userid and createdtime < now() - interval 14 DAY order by createdtime desc");
    $deleteactivity->bindparam(':userid',$id);
    $deleteactivity->execute();

    $pagename = basename($_SERVER['PHP_SELF']); 
    $Titile='';
    $myactivite = new Activites();
    if($pagename=="AllActivites.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Activities';	
    }
    if($pagename=="AddClient.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Client page';	
    }
    if($pagename=="AllClients.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Client List';	
    }
    if($pagename=="AllFile.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Document List';	
    }
    if($pagename=="AddEvent.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Appointment page';	
    }
    if($pagename=="AllEvent.php")
    {
        $Titile=$myactivite->Titile = 'Viewed Appointment List';	
    }
    if($pagename=="EventSettings.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Appointment Setting';	
    }
    if($pagename=="todo.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Todo page';	
    }
    if($pagename=="ViewTodo.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Todo List';	
    }
    if($pagename=="AddCategory.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Category page';	
    }
    if($pagename=="AllCategory.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Category List';	
    }
    if($pagename=="AddService.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Services page';	
    }
    if($pagename=="viewService.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Services List';	
    }
    if($pagename=="Archive.php")
    {
        $Titile=$myactivite->Titile = 'Viewed Archive Page';   
    }
    if($pagename=="Profile.php")
    {
        $Titile=$myactivite->Titile = 'Viewed Profile';	
    }
    if($pagename=="AddUser.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add User page';	
    }
    if($pagename=="Faqs.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All FAQs';	
    }
    if($pagename=="ViewClient.php")
    {
        $Titile=$myactivite->Titile = 'Visited on View Client Profile';	
    }
    if($pagename=="dashboard.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Dashboard';	
    }
    if($pagename=="AddTag.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Tag page';	
    }
    if($pagename=="Alltag.php")
    {
        $Titile=$myactivite->Titile = 'Viewed All Tags List';	
    }
    if($pagename=="AddNote.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Note page';	
    }
    if($pagename=="EmailSend.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Send Mail page';	
    }
    if($pagename=="AllProductCategory.php")
    {
        $Titile=$myactivite->Titile = 'Visited on All Product Category page';	
    }
    if($pagename=="AddProductCategory.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Product Category page';	
    }
    if($pagename=="AllProduct.php")
    {
        $Titile=$myactivite->Titile = 'Visited on All Product page';	
    }
    if($pagename=="Product.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Product page';	
    }
    if($pagename=="Order.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Order page';	
    }
    if($pagename=="OrderList.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Order List page';	
    }
    if($pagename=="EditOrder.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Edit Order page';	
    }
    if($pagename=="Memberships.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Memberships page';	
    }
    if($pagename=="organizational_chart.php")
    {
        $Titile=$myactivite->Titile = 'Visited on organizational chart page';	
    }
    if($pagename=="Commission.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Commission page';	
    }
    if($pagename=="Performance.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Performance page';	
    }
    if($pagename=="Sales.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Sales page';	
    }
    if($pagename=="CompanyInformation.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Company Information page';	
    }
    if($pagename=="MembershipPackageList.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Membership Package List page';	
    }
    if($pagename=="AddMembershipPackage.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Membership Package page';	
    }
    if($pagename=="AllEmailTemp.php")
    {
        $Titile=$myactivite->Titile = 'Visited on All Email Temp page';	
    }
    if($pagename=="AddEmailTempleate.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Email Templeate page';	
    }
    if($pagename=="index.php")
    {
        $Titile=$myactivite->Titile = 'Visited on index page';	
    }
    if($pagename=="Register.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Register page';	
    }
    if($pagename=="ForgetPassword.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Forget Password page';	
    }
    if($pagename=="ResetPassword.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Rese tPassword page';	
    }
    if($pagename=="SmsSendSetting.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Sms Send Setting page';	
    }
    if($pagename=="EmailSendSetting.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Email Send Setting page';	
    }
    if($pagename=="ImportWizard.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Import Wizard page';	
    }
    if($pagename=="MyBackup.php")
    {
        $Titile=$myactivite->Titile = 'Visited on My Backup page';	
    }
    if($pagename=="AllEmployees.php")
    {
        $Titile=$myactivite->Titile = 'Visited on All Employees page';	
    }
    if($pagename=="AddEmployees.php")
    {
        $Titile=$myactivite->Titile = 'Visited on Add Employees page';	
    }
    if($Titile)
    {
        $myactivite->commit_acitve($Titile);
    }
}
?>