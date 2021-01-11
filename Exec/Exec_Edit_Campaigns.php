<?php

    require_once('Exec_Config.php');        
    
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Campaigns.php'); 
if(isset($_REQUEST['viewdata']))
{
    $CampaignsDisplay=new Campaigns;
    $CampaignsDisplay->listoffile();   
}

if(isset($_REQUEST['viewfilerdata']))
{
    $viewfilerdata = $_REQUEST['viewfilerdata'];
    $CampaignsDisplay=new Campaigns;
    $CampaignsDisplay->listoffile2($viewfilerdata);   
}
if(isset($_POST["Campaigns"]))
{
    
    $myCampaigns = new Campaigns($_POST["id"]);
    $myCampaigns->id =stripslashes(strip_tags($_POST["id"]));// $_POST["id"];
    $myCampaigns->Campaigns = stripslashes(strip_tags($_POST["Campaigns"]));//$_POST["Category"];
    
    if(!empty($_POST['CampaignsCategory']))
    {
    $myCampaigns->CampaignsCategory = stripslashes(strip_tags($_POST["CampaignsCategory"]));//$_POST["Category"];    
    }
    else
    {
    $myCampaigns->CampaignsCategory = 'Uncategorized';
    }
    @$myCampaigns->CampaignsFromName = $_POST["CampaignsFromName"];//$_POST["Category"];
    @$myCampaigns->CampaignsFrom = $_POST["CampaignsFrom"];//$_POST["Category"];

    @$myCampaigns->CampaignsTo = $_POST["CampaignsTo"];//$_POST["Category"];
    @$myCampaigns->Campaignscc = $_POST["Campaignscc"];//$_POST["Category"];
    @$myCampaigns->Campaignsbcc = $_POST["Campaignsbcc"];//$_POST["Category"];
    @$myCampaigns->CampaignsSubject = $_POST["CampaignsSubject"];//$_POST["Category"];
    @$myCampaigns->CampaignsMessage = $_POST["CampaignsMessage"];//$_POST["Category"];
    @$myCampaigns->TrackOpens = $_POST["TrackOpens"];//$_POST["Category"];
    @$myCampaigns->TrackClicks = $_POST["TrackClicks"];//$_POST["Category"];
    @$myCampaigns->SendCampaignsTimezone = $_POST["SendCampaignsTimezone"];//$_POST["Category"];
    @$myCampaigns->OnDay = $_POST["OnDay"];//$_POST["Category"];
    @$myCampaigns->AtTime = $_POST["AtTime"];//$_POST["Category"];
    @$myCampaigns->flowchartdata = $_POST["flowchartdata"];//$_POST["Category"];
    
    if(!empty($_POST["tag"]))
    {
    $newtaglist = $_POST["tag"];//$_POST["Category"];
    
     $newarraylist=array();
    foreach($newtaglist as $newtagl)
    {
        
        $tag=$newtagl;
        $stmt= $db->prepare("SELECT id FROM `tag` WHERE tag=:tag"); 
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
        $result_tag = $stmt->fetch(PDO::FETCH_ASSOC);
         $oldtagid= $result_tag['id'];
        array_push($newarraylist,$oldtagid); 
       
        $numberoftag =  $stmt->rowCount();
        if($numberoftag<=0)
        {
            $datecreated = date("Y-m-d H:i:s");
            $datelastupdated = date("Y-m-d H:i:s");
            $createdfk = $_SESSION["UserID"];
            $updatedfk = $_SESSION["UserID"];
            $isactive = 1;
        $stmtint= $db->prepare("INSERT INTO tag (tag,datecreated,datelastupdated,createdfk,updatedfk,isactive) VALUES (:tag,:datecreated,:datelastupdated,:createdfk,:updatedfk,:isactive)"); 
        $stmtint->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmtint->bindValue(':datecreated', $datecreated, PDO::PARAM_STR);
        $stmtint->bindValue(':datelastupdated', $datelastupdated, PDO::PARAM_STR);
        $stmtint->bindValue(':createdfk', $createdfk, PDO::PARAM_STR);
        $stmtint->bindValue(':updatedfk', $updatedfk, PDO::PARAM_STR);
        $stmtint->bindValue(':isactive', $isactive, PDO::PARAM_STR);
        $stmtint->execute();
        $newtagid = $db->lastInsertId();
        array_push($newarraylist,$newtagid);
        }
    }
    
        @$finalarray = array_filter($newarraylist);

   }

   if(!empty($_POST['ctasktitle']))
   {
        
    $ctasktitle = stripslashes(strip_tags($_POST["ctasktitle"]));//$_POST["Category"];    
    $ctaskdec = $_POST["ctaskdec"]; 
    $ctaskduedate  = $_POST["ctaskduedate"];//$_POST["Category"];    
    $datecreated = date("Y-m-d H:i:s");
            $datelastupdated = date("Y-m-d H:i:s");
            $createdfk = $_SESSION["UserID"];
            $updatedfk = $_SESSION["UserID"];
            $isactive = 0;
        $stmtint= $db->prepare("INSERT INTO todo (todoTitle,todoDesc,dueDate,datecreated,datelastupdated,createdfk,updatedfk,isactive) VALUES (:ctasktitle,:ctaskdec,:ctaskduedate,:datecreated,:datelastupdated,:createdfk,:updatedfk,:isactive)"); 
        $stmtint->bindParam(':ctasktitle', $ctasktitle, PDO::PARAM_STR);
        $stmtint->bindParam(':ctaskdec', $ctaskdec, PDO::PARAM_STR);
        $stmtint->bindParam(':ctaskduedate', $ctaskduedate, PDO::PARAM_STR);
        $stmtint->bindValue(':datecreated', $datecreated, PDO::PARAM_STR);
        $stmtint->bindValue(':datelastupdated', $datelastupdated, PDO::PARAM_STR);
        $stmtint->bindValue(':createdfk', $createdfk, PDO::PARAM_STR);
        $stmtint->bindValue(':updatedfk', $updatedfk, PDO::PARAM_STR);
        $stmtint->bindValue(':isactive', $isactive, PDO::PARAM_STR);
        $stmtint->execute();
        $newtodoid = $db->lastInsertId();

    @$myCampaigns->Camtaskid = $newtodoid;//$_POST["Category"];    
    @$myCampaigns->ctasktitle = stripslashes(strip_tags($_POST["ctasktitle"]));//$_POST["Category"];    
    @$myCampaigns->ctaskdec = $_POST["ctaskdec"]; 
    @$myCampaigns->ctaskduedate  = $_POST["ctaskduedate"];//$_POST["Category"];    
   
}

if(!empty($_POST['camnotetitle']))
{   
       
    $camnotetitle = stripslashes(strip_tags($_POST["camnotetitle"]));//$_POST["Category"];    
    $camnotedec = $_POST["camnotedec"]; 
    
    $datecreated = date("Y-m-d H:i:s");
            $datelastupdated = date("Y-m-d H:i:s");
            $createdfk = $_SESSION["UserID"];
            $updatedfk = $_SESSION["UserID"];
            $isactive = 0;
        $stmtint= $db->prepare("INSERT INTO note (noteTitle,noteDetail,datecreated,datelastupdated,createdfk,updatedfk,isactive) VALUES (:camnotetitle,:camnotedec,:datecreated,:datelastupdated,:createdfk,:updatedfk,:isactive)"); 
        $stmtint->bindParam(':camnotetitle', $camnotetitle, PDO::PARAM_STR);
        $stmtint->bindParam(':camnotedec', $camnotedec, PDO::PARAM_STR);
        $stmtint->bindValue(':datecreated', $datecreated, PDO::PARAM_STR);
        $stmtint->bindValue(':datelastupdated', $datelastupdated, PDO::PARAM_STR);
        $stmtint->bindValue(':createdfk', $createdfk, PDO::PARAM_STR);
        $stmtint->bindValue(':updatedfk', $updatedfk, PDO::PARAM_STR);
        $stmtint->bindValue(':isactive', $isactive, PDO::PARAM_STR);
        $stmtint->execute();
        $newnoteid = $db->lastInsertId();

                
     $myCampaigns->Camnoteid = $newnoteid;
    $myCampaigns->camnotetitle = stripslashes(strip_tags($_POST["camnotetitle"]));//$_POST["Category"];    
    $myCampaigns->camnotedec = $_POST["camnotedec"]; 
}
    
    @$myCampaigns->WDuration = $_POST["WDuration"]; 
    @$myCampaigns->DurationType = $_POST["DurationType"]; 

    @$myCampaigns->Maxwaittimeclick = $_POST["Maxwaittimeclick"]; 
    @$myCampaigns->clickDurationType = $_POST["clickDurationType"]; 
    @$myCampaigns->Maxwaittimeopen = $_POST["Maxwaittimeopen"]; 
    @$myCampaigns->OpenDurationType = $_POST["OpenDurationType"]; 

    @$finaltagstring = implode(',',@$finalarray);
    
    @$myCampaigns->ctag = $finaltagstring;

    $myCampaigns->commit($myCampaigns->id);
    if($myCampaigns)
    {
          $maicamidupd=$db->prepare("UPDATE Mail_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Wait_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Click_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Open_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Tag_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Task_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Note_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

            $maicamidupd=$db->prepare("UPDATE CheckTag_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Sms_Campaigns SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();
        

        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new campaigns '.$_POST["Campaigns"] ; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update service campaigns '.$_POST["Campaigns"].' detail' ;      
        }
        $myactivite->commit_acitve($Titile);
        if($_POST['id']=="new")
        {     
            echo json_encode(['resonse'=>'Campaigns has been successfully added']);die;
        }
        else
        {
            echo json_encode(['resonse'=>'Campaigns has been successfully updated']);die;       
        }
    }
    else
    {
        echo json_encode(['error'=>'sorry something wrong']);die;
    }
}
?>