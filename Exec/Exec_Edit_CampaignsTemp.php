<?php
    require_once('Exec_Config.php');        
  
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.CampaignsTemp.php'); 


if(isset($_REQUEST['viewdata']))
{
    $CampaignsDisplay=new Campaigns;
    $CampaignsDisplay->listoffile();   
}

if(isset($_POST["Campaigns"]))
{
    
    $myCampaigns = new Campaigns($_POST["id"]);
    $myCampaigns->id =stripslashes(strip_tags($_POST["id"]));
    $myCampaigns->Campaigns = stripslashes(strip_tags($_POST["Campaigns"]));
    @$myCampaigns->flowchartdata = $_POST["flowchartdata"];
    $myCampaigns->commit($myCampaigns->id);
    if($myCampaigns)
    {
            $maicamidupd=$db->prepare("UPDATE Mail_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Wait_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Click_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Open_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Tag_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Task_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Note_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

            $maicamidupd=$db->prepare("UPDATE CheckTag_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
          $maicamidupd->execute();

          $maicamidupd=$db->prepare("UPDATE Sms_Campaigns_Tem SET cam_id=$myCampaigns->id WHERE cam_id='new'"); 
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
            echo json_encode(['resonse'=>'Campaigns Template has been successfully added']);die;
        }
        else
        {
            echo json_encode(['resonse'=>'Campaigns Template has been successfully updated']);die;       
        }
    }
    else
    {
        echo json_encode(['error'=>'sorry something wrong']);die;
    }
}
?>