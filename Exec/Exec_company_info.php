<?php
    require_once('Exec_Config.php');        
    


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.company.php');
// if(isset($_POST["google_name"])){
//     @$image=stripslashes(strip_tags($_POST["image"])); //$_POST['image']; 
//     $google_name=stripslashes(strip_tags($_POST["google_name"]));
//     $google_email=stripslashes(strip_tags($_POST["google_email"]));
//     $CompanyName=stripslashes(strip_tags($_POST["CompanyName"]));
//     $id=$_SESSION["UserID"];
//     if(!empty($image))
//     {
//         list($type, $image) = explode(';', $image);
//         list(, $image)      = explode(',', $image);
//         $image = base64_decode($image);
//         $ImgObj= new AllFunction;
//         $ImgFileName=$ImgObj->ImgName();
//         $img = $_SERVER["DOCUMENT_ROOT"].'/assets/companyimage/'.$ImgFileName.'.png';
//         file_put_contents($img, $image);
//         $imageinsert=$ImgFileName.'.png';
//     }
//     $db=new db();
//     $total_clients = $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=:id)");
//     $total_clients->bindParam(':google_name', $google_name, PDO::PARAM_INT);
//     $total_clients->bindParam(':google_email', $google_email, PDO::PARAM_INT);
//     $total_clients->bindParam(':CompanyName', $CompanyName, PDO::PARAM_INT);
//     $total_clients->bindParam(':id', $id, PDO::PARAM_INT);
//     $total_clients->execute();
//     $number_of_clients = $total_clients->rowCount();  
//     $all_client=$total_clients->fetch(PDO::FETCH_ASSOC);
//     if($number_of_clients>0)
//     {
//         $MyClient = new Client($all_client['id']);
//         $MyClient->id=$all_client['id'];
//         if(!empty($google_name))
//         {
//             $MyClient->CompanyName = $CompanyName;  
//             $MyClient->LastName = $LastName;  
//         }
//         if(!empty($google_email))
//         {
//             $MyClient->email = $google_email;   
//         }
//         if(!empty($image))
//         {
//             $MyClient->compimg=$imageinsert; 
//         }
//         $MyClient->sid = $_SESSION["UserID"];
//         $MyClient->commit($MyClient->id);
//         if($MyClient)
//         {
//             echo json_encode(['resonse'=>'Company detail Successfully Updated',"mydata"=>$MyClient]);die;
//         }
//     }
//     else
//     {
//         $MyClient = new Client('new');
//         $MyClient->id = 'new';
//         $MyClient->CompanyName = $CompanyName;  
//         $MyClient->LastName = $LastName;  
//         $MyClient->email = $google_email;
//         $MyClient->sid = $_SESSION["UserID"];
//         if(!empty($image))
//         {
//             $MyClient->compimg=$imageinsert; 
//         }
//         $MyClient->commit($MyClient->id);
//         if($MyClient)
//         {
//             echo json_encode(['resonse'=>'Company detail Successfully Updated',"mydata"=>$MyClient]);die; 
//         }
//     }
// }
$MyClient = new Client($_POST["id"]);
$MyClient->id = $_POST["id"];

if(isset($_POST["CompanyName"]))
{
    if(empty($_FILES["ClientImg"]["name"]) && empty($_FILES["compimg"]["name"]))
    {
        @$MyClient->CompanyName =stripslashes(strip_tags($_POST["CompanyName"])); // $_POST["CompanyName"];
        @$MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        @$MyClient->email =stripslashes(strip_tags($_POST["email"]));
        @$MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        @$MyClient->Zip = $_POST["Zip"];
        @$MyClient->booking_endpoint = $_POST["booking_endpoint"];
        @$MyClient->City =stripslashes(strip_tags($_POST["City"]));
        @$MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        @$MyClient->Country =stripslashes(strip_tags($_POST["Country"])); 
        @$MyClient->ctheme =stripslashes(strip_tags($_POST["ctheme"])); 
        @$MyClient->sales_tax =stripslashes(strip_tags($_POST["sales_tax"]));  
        if(!empty($_POST["customwidget"]))
        {
            @$MyClient->customwidget =implode(',', $_POST["customwidget"]);    
        }
        $MyClient->commit($MyClient->id);
        if($MyClient)
        {
            $myactivite = new Activites();
            if($_POST['id']=="new")
            {
                $Titile=$myactivite->Titile = 'Add New Client '.$_POST["CompanyName"].' Details';
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update Client '.$_POST["CompanyName"].' Details ';
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);    
            }
            $myactivite->commit_acitve($Titile);die;
            //$db=new db();
        }
        else
        {
            echo json_encode(['error'=>'Sorry Something Wrong']);die;
        }
    }
    elseif(empty($_FILES["ClientImg"]["name"]) && !empty($_FILES["compimg"]["name"]))
    {
        $Iname=explode(".",$_FILES["compimg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->compimg = $ImgFileName.".".$Iname[1];

        $compimgPath = DOCUMENT_ROOT.ESUB."/assets/companyimage/";

        //$path = DOCUMENT_ROOT."/assets/companyimage/";
        
        $path = $compimgPath;
        
        $path = $path . basename($MyClient->compimg);
        if(move_uploaded_file($_FILES["compimg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Company Image was not uploaded please try again.";
        }
        $MyClient->CompanyName =stripslashes(strip_tags($_POST["CompanyName"])); // $_POST["CompanyName"];
        $MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        $MyClient->email =stripslashes(strip_tags($_POST["email"]));
        $MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        $MyClient->Zip = $_POST["Zip"];
        $MyClient->City =stripslashes(strip_tags($_POST["City"]));
        $MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        $MyClient->Country =stripslashes(strip_tags($_POST["Country"]));
        $MyClient->ctheme =stripslashes(strip_tags($_POST["ctheme"]));
        @$MyClient->sales_tax =stripslashes(strip_tags($_POST["sales_tax"]));  
        @$MyClient->booking_endpoint = $_POST["booking_endpoint"];
        $MyClient->commit($MyClient->id);

        if($MyClient)
        {
            $myactivite = new Activites();
            if($_POST['id']=="new")
            {
                $Titile=$myactivite->Titile = 'Upload Client '.$_POST["CompanyName"].' Profile Images';
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update Client '.$_POST["CompanyName"].' Profile Image';   
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);
            }
            $myactivite->commit_acitve($Titile);die;
        }
        else
        {
            echo json_encode(['error'=>'Sorry Something Wrong']);die;
        }
    }
    elseif(!empty($_FILES["ClientImg"]["name"]) && !empty($_FILES["compimg"]["name"]))
    {
        $Iname=explode(".",$_FILES["ClientImg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->ClientImg = $ImgFileName.".".$Iname[1]; 
        $userimgPath = DOCUMENT_ROOT.ESUB."/assets/ClientImages/";
        
        //$path = DOCUMENT_ROOT."/assets/ClientImages/";
        $path = $userimgPath;
        $path = $path . basename($MyClient->ClientImg);
        if(move_uploaded_file($_FILES["ClientImg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Company Image was not uploaded please try again.";
        }
        $Iname=explode(".",$_FILES["compimg"]["name"]);
        $ImgObj= new AllFunction;
        $ImgFileName=$ImgObj->ImgName();
        @$MyClient->compimg = $ImgFileName.".".$Iname[1];
        $compimgPath = DOCUMENT_ROOT.ESUB."/assets/companyimage/";

        $path = $compimgPath;
        //$path = DOCUMENT_ROOT."/assets/companyimage/";
        $path = $path . basename($MyClient->compimg);
        if(move_uploaded_file($_FILES["compimg"]["tmp_name"], $path)) 
        {
        } 
        else
        {
            $clientImage = "Company Image was not uploaded please try again.";
        }
        $MyClient->CompanyName =stripslashes(strip_tags($_POST["CompanyName"])); // $_POST["CompanyName"];
        $MyClient->Phone =stripslashes(strip_tags($_POST["Phone"])); 
        $MyClient->email =stripslashes(strip_tags($_POST["email"]));
        $MyClient->Address =stripslashes(strip_tags($_POST["Address"])); 
        $MyClient->Zip = $_POST["Zip"];
        @$MyClient->booking_endpoint = $_POST["booking_endpoint"];
        $MyClient->City =stripslashes(strip_tags($_POST["City"]));
        $MyClient->State =stripslashes(strip_tags($_POST["State"])); 
        $MyClient->Country =stripslashes(strip_tags($_POST["Country"]));
        $MyClient->ctheme =stripslashes(strip_tags($_POST["ctheme"]));
        @$MyClient->sales_tax =stripslashes(strip_tags($_POST["sales_tax"]));  
        $MyClient->commit($MyClient->id);
        if($MyClient)
        {
            $myactivite = new Activites();
            if($_POST['id']=="new")
            {
                $Titile=$myactivite->Titile = 'Upload Client '.$_POST["CompanyName"].' Profile Images';  
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);
            }
            else
            {
                $Titile=$myactivite->Titile = 'Update Client '.$_POST["CompanyName"].' Profile Image'; 
                echo json_encode(['resonse'=>'Company detail successfully updated',"mydata"=>$MyClient]);  
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