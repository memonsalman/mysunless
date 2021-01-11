<?php
    require_once('Exec_Config.php');        
        
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.client.php'); 


$_POST["Country"] = "United States";

$UserID = isset($_SESSION['UserID'])?$_SESSION['UserID']:"";

if(isset($_POST['REF_UserID']) && $_POST['REF_UserID']!=""){
    $UserID = $_POST['REF_UserID'];
}



$UserID = 875;

if(isset($_REQUEST["googledata"])){

    if($_SESSION['usertype']=='Admin'){
        if(!empty($_SESSION['SetUserForImport'])){
            $UserID = $_SESSION['SetUserForImport'];
            $_REQUEST["newlistofSubscriber2"] = $UserID;
        }else{
            echo json_encode(['error'=>'User not selected.']); die;
        }
    }




    if(!empty($_POST['contacts'])){
        $contacts = json_decode($_POST['contacts'],true);
    }else{
        echo json_encode(['error'=>'User data not found.']); die;
    }

    $error = ['Invalid'=>[],'Exist'=>[]];
    $count = 0;
    $InvalidCount = 0;

    foreach ($contacts as $key => $contact) {

        $contact = json_decode(base64_decode($contact),true);

        if(empty($contact["google_email"])){
            $InvalidCount++;
            $error['Invalid'] = $InvalidCount;
            continue;
        }

        if($contact["image"]=='Photo not found'){
            $contact["image"]= "";
        }

        @$image=stripslashes(strip_tags($contact["image"]));
        @$google_name=stripslashes(strip_tags($contact["google_name"]));
        @$google_email=stripslashes(strip_tags($contact["google_email"]));
        @$FirstName=stripslashes(strip_tags($contact["FirstName"]));
        @$LastName=stripslashes(strip_tags($contact["LastName"]));
        @$Phone=$contact["Phone"];
        @$Address=stripslashes(strip_tags($contact["street"]));
        @$City=stripslashes(strip_tags($contact["city"]));
        @$State=stripslashes(strip_tags($contact["State"]));
        @$Country=stripslashes(strip_tags($contact["Country"]));
        @$Zip=stripslashes(strip_tags($contact["postalCode"]));
        @$id=$UserID;
        if(!empty($image))
        {   
            // $image = base64_decode($image);
            // list($type, $image) = explode(';', $image);
            // list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $ImgObj= new AllFunction;
            $ImgFileName=$ImgObj->ImgName();
            $img = $_SERVER["DOCUMENT_ROOT"].ESUB.'/assets/ProfileImages/'.$ImgFileName.'.png';
            file_put_contents($img, $image);
            $imageinsert=$ImgFileName.'.png';
        }
        $db=new db();
        $total_clients = $db->prepare("SELECT * FROM `clients` WHERE `email`=:google_email AND (`createdfk`=:id)");

        $total_clients->bindParam(':google_email', $google_email);
        $total_clients->bindParam(':id', $id);
        $total_clients->execute();
        $number_of_clients = $total_clients->rowCount();	
        $all_client=$total_clients->fetch(PDO::FETCH_ASSOC);
        if($number_of_clients>0)
        {
            array_push($error['Exist'],$google_email);
            continue;
        }
        else
        {
            $MyClient = new Client('new');
            $MyClient->id = 'new';
            $MyClient->FirstName = $FirstName;	
            $MyClient->LastName = $LastName;	
            $MyClient->email = $google_email;
            $MyClient->sid = $UserID;
            $MyClient->Address = $Address;
            $MyClient->Zip = $Zip;
            $MyClient->City = $City;
            $MyClient->State = $State;
            $MyClient->Country = $Country;
            $MyClient->Phone = $Phone;
            if(!empty($image))
            {
                $MyClient->ProfileImg=$imageinsert;	
            }
            $newclient=$MyClient->commit($MyClient->id);
            
            $MyClient->ActivitesCount($newclient); 
            if($MyClient)
            {	
                $myactivite = new Activites(); 
                $Titile=$myactivite->Titile = 'Add New Client '.$FirstName.' '.$LastName ;
                $myactivite->commit_acitve($Titile);

                $count++;

                // echo json_encode(['resonse'=>'New customer google successfully added',"mydata"=>$MyClient]);die;	
            }
        }
    }

    echo json_encode(['Success'=>$count,'Fail'=>$error]);die;

}

if(isset($_POST["id"]) && $_POST["id"]!="" && isset($_POST["email"]) && $_POST["email"]!="")
{
    $useremail = $_POST["email"];
    $id = $_POST["id"];
    $db= new db();
    $Allusers= $db->prepare ("SELECT `email` FROM `clients` WHERE isactive=1 and `email`=:email AND createdfk in (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id) and (u2.id=u3.adminid or u2.id=u3.id) where u1.id=$UserID GROUP by u3.id) AND id <> :id");
    $Allusers->bindparam(':email',$useremail, PDO::PARAM_STR);
    $Allusers->bindparam(':id',$id);
    $Allusers->execute();
    if ( $Allusers->rowCount() > 0 ){
        echo  json_encode(["error"=>'This email already exists.']);die;
    }
}else{
    echo  json_encode(["error"=>'Something went wrong.']);die;
}

$MyClient = new Client($_POST["id"]);
$MyClient->id = $_POST["id"];
if(isset($_POST["FirstName"]))
{
    if(empty($_FILES["ClientImg"]["name"]) && empty($_FILES["ProfileImg"]["name"]))
    {

        $FirstName=$MyClient->FirstName =stripslashes(strip_tags($_POST["FirstName"])); // $_POST["FirstName"];
        $MyClient->LastName =stripslashes(strip_tags($_POST["LastName"])); 
        $MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        $MyClient->email =stripslashes(strip_tags($_POST["email"]));
        @$MyClient->Solution = stripslashes(strip_tags($_POST["Solution"]));
        @$MyClient->PrivateNotes =stripslashes(strip_tags($_POST["PrivateNotes"]));
        $MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        $MyClient->Zip = $_POST["Zip"];
        $MyClient->City =stripslashes(strip_tags($_POST["City"]));
        $MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        $MyClient->Country =stripslashes(strip_tags($_POST["Country"])); 
        $MyClient->sid = $UserID;



        if(!empty($_POST["ProfileImg"]))
        {
        @$MyClient->ProfileImg = $_POST["ProfileImg"];    
        }
        else
        {
         @$MyClient->ProfileImg = $_POST["ProfileImg3"];       
        }
        @$MyClient->SelectPackage = $_POST["SelectPackage"];
        @$MyClient->employeeSold = $_POST["employeeSold"];
        @$MyClient->package_sd = $_POST["sd"];
        @$MyClient->package_ed = $_POST["ed"];
        
        if(isset($_SESSION['usertype']) && $_SESSION['usertype']=="Admin")
            {
                  $MyClient->sid = $_REQUEST["newlistofSubscriber2"];
            
            }
            else
            {
                
            
               $MyClient->sid = $UserID; 
            }
        $newclient=$MyClient->commit($MyClient->id);
        if($MyClient)
        {
            $myactivite = new Activites(); // This function for data insert in Activities
            if($_POST['id']=="new")
            {
                $MyClient->ActivitesCount($newclient); // This function for data insert in CountActivites.
                $Titile=$myactivite->Titile = 'Add New customer '.$_POST["FirstName"].' '.$_POST["LastName"] ;
                echo json_encode(['resonse'=>'New customer Successfully Added',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update customer '.$_POST["FirstName"].''.$_POST["LastName"].' Details ';
                echo json_encode(['resonse'=>'Thank you, Your Customer detail has been successfully updated',"mydata"=>$MyClient]);		
            }
            $myactivite->commit_acitve($Titile);die;
            //$db=new db();
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
    elseif(empty($_FILES["ClientImg"]["name"]) && !empty($_FILES["ProfileImg"]["name"]))
    {
        
        if(!empty($_POST['ProfileImg2']))
        {
            if(file_exists(DOCUMENT_ROOT.ESUB.'/upload-and-crop-image/CustomerTep/'.$_POST['ProfileImg2']))
            {
                $Iname=explode(".",$_POST['ProfileImg2']);
                $ImgObj= new AllFunction;
                $ImgFileName=$ImgObj->ImgName();
                @$MyClient->ProfileImg = $ImgFileName.".".$Iname[1]; 
                $path = DOCUMENT_ROOT.ESUB."/assets/ProfileImages/";
                $path = $path . basename($MyClient->ProfileImg);
                $fileMoved = rename(DOCUMENT_ROOT.ESUB.'/upload-and-crop-image/CustomerTep/'.$_POST['ProfileImg2'], $path);
                if($fileMoved)
                {
                    @$path2 = $_SERVER['DOCUMENT_ROOT'].ESUB.'/upload-and-crop-image/CustomerTep/'.@$_POST['ProfileImg2'];
                    @unlink($path2);
                }
             }
            else
            {
                 $clientImage = "Customer image was not uploaded please try again.";
            }

        }
        else
        {
         $Iname=explode(".",$_FILES["ProfileImg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->ProfileImg = $ImgFileName.".".$Iname[1]; 
        $path = DOCUMENT_ROOT.ESUB."/assets/ProfileImages/";
        $path = $path . basename($MyClient->ProfileImg);
        if(move_uploaded_file($_FILES["ProfileImg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Customer image was not uploaded please try again.";
        }   
        }

        $MyClient->FirstName =stripslashes(strip_tags($_POST["FirstName"])); // $_POST["FirstName"];
        $MyClient->LastName =stripslashes(strip_tags($_POST["LastName"])); 
        $MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        $MyClient->email =stripslashes(strip_tags($_POST["email"]));
        @$MyClient->Solution = stripslashes(strip_tags($_POST["Solution"]));
        @$MyClient->PrivateNotes =stripslashes(strip_tags($_POST["PrivateNotes"]));
        $MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        $MyClient->Zip = $_POST["Zip"];
        $MyClient->City =stripslashes(strip_tags($_POST["City"]));
        $MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        $MyClient->Country =stripslashes(strip_tags($_POST["Country"]));
        $MyClient->sid = $UserID;

        @$MyClient->SelectPackage = $_POST["SelectPackage"];
        @$MyClient->employeeSold = $_POST["employeeSold"];
        @$MyClient->package_sd = $_POST["sd"];
        @$MyClient->package_ed = $_POST["ed"]; 


        if(isset($_SESSION['usertype']) && $_SESSION['usertype']=="Admin")
            {
               $MyClient->sid = $_REQUEST["newlistofSubscriber2"];
            }
            else
            {
               $MyClient->sid = $_POST["sid"];
            }

        $MyClient->commit($MyClient->id);
        if($MyClient)
        {
            $myactivite = new Activites();
            if($_POST['id']=="new")
            {
                $Titile=$myactivite->Titile = 'Upload customer '.$_POST["FirstName"].' '.$_POST["LastName"].' Profile Images';
                echo json_encode(['resonse'=>'New customer Successfully Added',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update customer '.$_POST["FirstName"].' '.$_POST["LastName"].' Profile Image';		
                echo json_encode(['resonse'=>'Thank you, Your Customer detail has been successfully updated',"mydata"=>$MyClient]);
            }
            $myactivite->commit_acitve($Titile);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
    elseif(!empty($_FILES["ClientImg"]["name"]) && !empty($_FILES["ProfileImg"]["name"]))
    {
        $Iname=explode(".",$_FILES["ClientImg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->ClientImg = $ImgFileName.".".$Iname[1]; 
        $path = DOCUMENT_ROOT.ESUB."/assets/ClientImages/";
        $path = $path . basename($MyClient->ClientImg);
        if(move_uploaded_file($_FILES["ClientImg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Customer image was not uploaded please try again.";
        }
        $Iname=explode(".",$_FILES["ProfileImg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->ProfileImg = $ImgFileName.".".$Iname[1]; 
        $path = DOCUMENT_ROOT.ESUB."/assets/ProfileImages/";
        $path = $path . basename($MyClient->ProfileImg);
        if(move_uploaded_file($_FILES["ProfileImg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Customer image was not uploaded please try again.";
        }

        $MyClient->FirstName =stripslashes(strip_tags($_POST["FirstName"])); // $_POST["FirstName"];
        $MyClient->LastName =stripslashes(strip_tags($_POST["LastName"])); 
        $MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        $MyClient->email =stripslashes(strip_tags($_POST["email"]));
        @$MyClient->Solution = stripslashes(strip_tags($_POST["Solution"]));
        @$MyClient->PrivateNotes =stripslashes(strip_tags($_POST["PrivateNotes"]));
        $MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        $MyClient->Zip = $_POST["Zip"];
        $MyClient->City =stripslashes(strip_tags($_POST["City"]));
        $MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        $MyClient->Country =stripslashes(strip_tags($_POST["Country"])); 
        $MyClient->sid = $UserID;

        @$MyClient->SelectPackage = $_POST["SelectPackage"];
        @$MyClient->employeeSold = $_POST["employeeSold"];
        @$MyClient->package_sd = $_POST["sd"];
        @$MyClient->package_ed = $_POST["ed"];
        if(isset($_SESSION['usertype']) && $_SESSION['usertype']=="Admin")
            {
               $MyClient->sid = $_REQUEST["newlistofSubscriber2"];
            }
            else
            {
               $MyClient->sid = $_POST["sid"];
            }
        $MyClient->commit($MyClient->id);
        if($MyClient)
        {
            $myactivite = new Activites();
            if($_POST['id']=="new")
            {
                $Titile=$myactivite->Titile = 'Upload customer '.$_POST["FirstName"].' '.$_POST["LastName"].' Profile Images';	
                echo json_encode(['resonse'=>'New customer Successfully Added',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update customer '.$_POST["FirstName"].' '.$_POST["LastName"].' Profile Image';	
                echo json_encode(['resonse'=>'Thank you, Your Customer detail has been successfully updated',"mydata"=>$MyClient]);	
            }
            $myactivite->commit_acitve($Titile);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry something wrong']);die;
        }
    }
}	
?>