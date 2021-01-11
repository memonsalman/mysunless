<?php 
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
  }
if($_SESSION['usertype']!="Admin")
{
    header("Location: dashboard.php");die;
}

    
    // $deletemnote = $db->prepare("DELETE  FROM Note_Campaigns WHERE cam_id='new'");
    // $deletemnote->execute();

    // $deletemail = $db->prepare("DELETE  FROM Mail_Campaigns WHERE cam_id='new'");
    // $deletemail->execute();

    // $deletewiat = $db->prepare("DELETE  FROM Wait_Campaigns WHERE cam_id='new'");
    // $deletewiat->execute();

    // $deleteopen = $db->prepare("DELETE  FROM Open_Campaigns WHERE cam_id='new'");
    // $deleteopen->execute();

    // $deletetag = $db->prepare("DELETE  FROM Tag_Campaigns WHERE cam_id='new'");
    // $deletetag->execute();

    // $deletetask = $db->prepare("DELETE  FROM Task_Campaigns WHERE cam_id='new'");
    // $deletetask->execute();

    // $deleteclick = $db->prepare("DELETE  FROM Click_Campaigns WHERE cam_id='new'");
    // $deleteclick->execute();

    $id= $_SESSION['UserID'];
    $CampaignsCategory = $db->prepare("SELECT * FROM `CampaignsCategory` WHERE `createdfk` =:id AND isactive=1");
    $CampaignsCategory->bindParam(':id', $id, PDO::PARAM_INT);
    $CampaignsCategory->execute();
    $AllCampaignsCategory=$CampaignsCategory->fetchAll();


    $stmt2= $db->prepare("SELECT * FROM `smssetting` WHERE createdfk=:id"); 
$stmt2->bindParam(':id', $id, PDO::PARAM_INT);
$stmt2->execute();
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$CampaignssmsFrom=$result2['Twillo_from'];
$sid = $result2['sid'] ;
$token = $result2['token'];

    $mytag = $db->prepare("SELECT id,tag FROM `tag` WHERE `createdfk` =:id");
    $mytag->bindParam(':id', $id, PDO::PARAM_INT);
    $mytag->execute();
    $Allmytag=$mytag->fetchAll();
    

      
     $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); 
    @$From=$result['email'];
    @$FromName=$result['username'];

   $RelatedTo2 = $db->prepare("SELECT * FROM `EmailTempleate` WHERE createdfk=:id");
$RelatedTo2->bindValue(":id",$id,PDO::PARAM_INT);
$RelatedTo2->execute();
$all_Templeate=$RelatedTo2->fetchAll(PDO::FETCH_ASSOC);


$RelatedTimezone = $db->prepare("SELECT * FROM `timezone`");
$RelatedTimezone->bindValue(":id",$id,PDO::PARAM_INT);
$RelatedTimezone->execute();
$all_Timezone=$RelatedTimezone->fetchAll(PDO::FETCH_ASSOC);



if(isset($_REQUEST['tid']))
{
    $tid=$_POST['tid']; 
    $eidtClient = $db->prepare("select * from `EmailTempleate` where id=:tid");
    $eidtClient->bindValue(":tid",$tid,PDO::PARAM_INT);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    if($editfile)
    {
        echo  json_encode(["resonse"=>$all]);die;
    }
}

if(isset($_GET["id"]))
{
    $myCampaigns = base64_decode($_GET['id']);
}else
{
    $myCampaigns = "new";
}
$action="";
if(isset($_GET['action']))
{
    $action=$_GET['action'];
}


if($action=='edit'){
    $SSelectCat=$db->prepare("select * from Campaigns_Temp where id=:myCampaigns");
    $SSelectCat->bindValue(':myCampaigns',$myCampaigns,PDO::PARAM_INT);
    $SSelectCat->execute();
    if($SSelectCat->rowCount() > 0){
        while($select=$SSelectCat->fetch(PDO::FETCH_ASSOC)){
            $Campaigns=$select['TempName'];
            $flowchartdata=$select['flowchartdata'];
          
        }
    }

    $StaSelectCat=$db->prepare("DELETE  FROM campaigns_status where camid=:myCampaigns");
    $StaSelectCat->bindValue(':myCampaigns',$myCampaigns,PDO::PARAM_INT);
    $StaSelectCat->execute();

    $StaSelectCat2=$db->prepare("DELETE  FROM Client_Campaigns where cam_id=:myCampaigns");
    $StaSelectCat2->bindValue(':myCampaigns',$myCampaigns,PDO::PARAM_INT);
    $StaSelectCat2->execute();
    
}

 function select_options($selected = array()){
  $uid=$_SESSION['UserID'];
            $db=new db(); 
            $stmt2= $db->prepare("SELECT * FROM `tag` where createdfk=$uid"); 
               $stmt2->execute();
               $all_result_tag = $stmt2->fetchAll(PDO::FETCH_ASSOC);
     $output = '';
    foreach(($all_result_tag) as $item){
         $output.= '<option class="sadf" value="' . $item['tag'] . '"' . (in_array($item['id'], $selected) ? ' selected' : '') . '>' . $item['tag'] . '</option>';
     }
     return $output;
}

@$id=base64_decode($_GET['id']);
$stmt= $db->prepare("SELECT Campaigns.ctag,(SELECT GROUP_CONCAT(tag) FROM tag WHERE FIND_IN_SET(id,Campaigns.ctag)) as tg,(SELECT GROUP_CONCAT(ID) FROM tag WHERE FIND_IN_SET(id,Campaigns.ctag)) as tGID FROM `Campaigns` WHERE id=:id"); 
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
@$finaltagforclient=explode(',', $result_tag[0]['tg']);
@$finaltagforclientid=explode(',', $result_tag[0]['tGID']);
@$newtag_array=array_combine($finaltagforclientid,$finaltagforclient);

if(isset($_POST['tagdid']))
{
  $oldid = $_POST['tagdid'];
  $camid2=base64_decode($_REQUEST['taididd']);
  
  $stmt= $db->prepare("SELECT ctag FROM `Campaigns` WHERE id=:camid2"); 
  $stmt->bindParam(':camid2', $camid2, PDO::PARAM_STR);
  $stmt->execute();
  $result_tag = $stmt->fetch(PDO::FETCH_ASSOC);
  $newtagarra = explode(',', $result_tag['ctag']);
  $newtagarra1 = array_flip($newtagarra);
   unset( $newtagarra1[$oldid] );
  $finalnewarray = implode(',', array_flip($newtagarra1));
      $updateCampaignstag=$db->prepare("UPDATE Campaigns SET ctag=:finalnewarray WHERE id=:camid2"); 
          $updateCampaignstag->bindParam(':camid2', $camid2, PDO::PARAM_INT);
          $updateCampaignstag->bindParam(':finalnewarray', $finalnewarray, PDO::PARAM_INT);
    $updatetagfile=$updateCampaignstag->execute();
     if($updatetagfile)
     {
      echo  json_encode(["resonse"=>'Tag Successfuly Remove']);die;
     }

  }

  if(isset($_REQUEST['MailCampaigns']))
  {
    $currentdate = date('d M, Y');
    $createdfk=$_REQUEST['createdfk'];
    $cam_id=$_REQUEST['id'];
    $CampaignsFromName=$_REQUEST['CampaignsFromName'];
    $CampaignsFrom=$_REQUEST['CampaignsFrom'];
    $CampaignsTo=$_REQUEST['CampaignsTo'];
	$Campaignscc=$_REQUEST['Campaignscc'];
    $Campaignsbcc=$_REQUEST['Campaignsbcc'];
    $CampaignsSubject=$_REQUEST['CampaignsSubject'];
    $CampaignsMessage=$_REQUEST['CampaignsMessage'];
    $TrackOpens=$_REQUEST['TrackOpens'];
    $TrackClicks=$_REQUEST['TrackClicks'];
    $SendCampaignsTimezone=$_REQUEST['SendCampaignsTimezone'];
    $companywebsite=$_REQUEST['companywebsite'];
    $OnDay=$_REQUEST['OnDay'];
    $AtTime=$_REQUEST['AtTime'];
    $mykey=$_REQUEST['mailkey'];


    // if($CampaignsTo ='{{ first_name }}' || $CampaignsTo ='{{ last_name }}' || $CampaignsTo ='{{ location.street }}' || $CampaignsTo ='{{ location.city }}' ||  $CampaignsTo ='{{ location.state }}' || $CampaignsTo ='{{ location.country }}' || $CampaignsTo ='{{ location.zip }}' || $CampaignsTo ='{{ {{ owner.signature }} }}' || $CampaignsTo ='{{ company_phone }}' || $CampaignsTo ='{{ phone }}' || $CampaignsTo ='{{ company_name }} ' || $CampaignsTo ='{{ company_address }}')
    if (strpos($CampaignsTo, '{{ first_name }}') !== false || strpos($CampaignsTo, '{{ last_name }}') !== false || strpos($CampaignsTo, '{{ location.street }}') !== false || strpos($CampaignsTo, '{{ location.city }}') !== false || strpos($CampaignsTo, '{{ location.state }}') !== false || strpos($CampaignsTo, '{{ location.country }}') !== false || strpos($CampaignsTo, '{{ location.zip }}') !== false || strpos($CampaignsTo, '{{ owner.signature }}') !== false || strpos($CampaignsTo, '{{ company_address }}') !== false || strpos($CampaignsTo, '{{ company_name }}') !== false || strpos($CampaignsTo, '{{ phone }}') !== false || strpos($CampaignsTo, '{{ company_phone }}') !== false || strpos($CampaignsTo, '{{ first_name }}') !== false )
    {
    	echo  json_encode(["Validemail"=>'Please Select or Enter valid email']);die;
    }
    else if(strpos($Campaignscc, '{{ first_name }}') !== false || strpos($Campaignscc, '{{ last_name }}') !== false || strpos($Campaignscc, '{{ location.street }}') !== false || strpos($Campaignscc, '{{ location.city }}') !== false || strpos($Campaignscc, '{{ location.state }}') !== false || strpos($Campaignscc, '{{ location.country }}') !== false || strpos($Campaignscc, '{{ location.zip }}') !== false || strpos($Campaignscc, '{{ owner.signature }}') !== false || strpos($Campaignscc, '{{ company_address }}') !== false || strpos($Campaignscc, '{{ company_name }}') !== false || strpos($Campaignscc, '{{ phone }}') !== false || strpos($Campaignscc, '{{ company_phone }}') !== false || strpos($Campaignscc, '{{ first_name }}') !== false )
    {
    	echo  json_encode(["Validemail"=>'Please Select or Enter valid email']);die;
    }

        else if(strpos($Campaignsbcc, '{{ first_name }}') !== false || strpos($Campaignsbcc, '{{ last_name }}') !== false || strpos($Campaignsbcc, '{{ location.street }}') !== false || strpos($Campaignsbcc, '{{ location.city }}') !== false || strpos($Campaignsbcc, '{{ location.state }}') !== false || strpos($Campaignsbcc, '{{ location.country }}') !== false || strpos($Campaignsbcc, '{{ location.zip }}') !== false || strpos($Campaignsbcc, '{{ owner.signature }}') !== false || strpos($Campaignsbcc, '{{ company_address }}') !== false || strpos($Campaignsbcc, '{{ company_name }}') !== false || strpos($Campaignsbcc, '{{ phone }}') !== false || strpos($Campaignsbcc, '{{ company_phone }}') !== false || strpos($Campaignsbcc, '{{ first_name }}') !== false )
    {
    	echo  json_encode(["Validemail"=>'Please Select or Enter valid email']);die;
    }
    else
    {
    if($cam_id=='new')
    {
  $Mail_Campaignsinset=$db->prepare("INSERT INTO Mail_Campaigns_Tem(cam_id,CampaignsFromName,CampaignsFrom,CampaignsTo,Campaignscc,Campaignsbcc,CampaignsSubject,CampaignsMessage,TrackOpens,TrackClicks,SendCampaignsTimezone,OnDay,AtTime,mykey,companywebsite,createdfk,currentdate) VALUES(:cam_id,:CampaignsFromName,:CampaignsFrom,:CampaignsTo,:Campaignscc,:Campaignsbcc,:CampaignsSubject,:CampaignsMessage,:TrackOpens,:TrackClicks,:SendCampaignsTimezone,:OnDay,:AtTime,:mykey,:companywebsite,:createdfk,:currentdate)"); 
    $Mail_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Mail_Campaignsinset->bindParam(':CampaignsFromName', $CampaignsFromName, PDO::PARAM_INT);
    $Mail_Campaignsinset->bindParam(':CampaignsFrom', $CampaignsFrom, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsTo', $CampaignsTo, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':Campaignscc', $Campaignscc, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':Campaignsbcc', $Campaignsbcc, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsSubject', $CampaignsSubject, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsMessage', $CampaignsMessage, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':TrackOpens', $TrackOpens, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':TrackClicks', $TrackClicks, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':SendCampaignsTimezone', $SendCampaignsTimezone, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':OnDay', $OnDay, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':AtTime', $AtTime, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':companywebsite', $companywebsite, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':currentdate', $currentdate, PDO::PARAM_STR);
    $maildata =$Mail_Campaignsinset->execute();
    if($maildata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {

    $Mail_Updatedatas_find = $db->prepare("SELECT * FROM `Mail_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $Mail_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Mail_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Mail_Updatedatas_find->execute();
    $Mail_Updatedatas_find_all= $Mail_Updatedatas_find->fetch(PDO::FETCH_ASSOC);
    if(!empty($Mail_Updatedatas_find_all))
    {
    $Mail_Updatedatas = $db->prepare("update `Mail_Campaigns_Tem` set CampaignsFromName=:CampaignsFromName,CampaignsFrom=:CampaignsFrom,CampaignsTo=:CampaignsTo,Campaignscc=:Campaignscc,Campaignsbcc=:Campaignsbcc,CampaignsSubject=:CampaignsSubject,CampaignsMessage=:CampaignsMessage,TrackOpens=:TrackOpens,TrackClicks=:TrackClicks,SendCampaignsTimezone=:SendCampaignsTimezone,OnDay=:OnDay,AtTime=:AtTime,companywebsite=:companywebsite,createdfk=:createdfk,currentdate=:currentdate where cam_id=:cam_id AND mykey=:mykey");

    $Mail_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Mail_Updatedatas->bindParam(':CampaignsFromName', $CampaignsFromName, PDO::PARAM_INT);
    $Mail_Updatedatas->bindParam(':CampaignsFrom', $CampaignsFrom, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':CampaignsTo', $CampaignsTo, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':Campaignscc', $Campaignscc, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':Campaignsbcc', $Campaignsbcc, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':CampaignsSubject', $CampaignsSubject, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':CampaignsMessage', $CampaignsMessage, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':TrackOpens', $TrackOpens, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':TrackClicks', $TrackClicks, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':SendCampaignsTimezone', $SendCampaignsTimezone, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':OnDay', $OnDay, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':AtTime', $AtTime, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':companywebsite', $companywebsite, PDO::PARAM_STR);
    $Mail_Updatedatas->bindParam(':currentdate', $currentdate, PDO::PARAM_STR);
    $updatemaildata =$Mail_Updatedatas->execute();
    if($updatemaildata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $Mail_Campaignsinset=$db->prepare("INSERT INTO Mail_Campaigns_Tem(cam_id,CampaignsFromName,CampaignsFrom,CampaignsTo,Campaignscc,Campaignsbcc,CampaignsSubject,CampaignsMessage,TrackOpens,TrackClicks,SendCampaignsTimezone,OnDay,AtTime,mykey,companywebsite,createdfk,currentdate) VALUES(:cam_id,:CampaignsFromName,:CampaignsFrom,:CampaignsTo,:Campaignscc,:Campaignsbcc,:CampaignsSubject,:CampaignsMessage,:TrackOpens,:TrackClicks,:SendCampaignsTimezone,:OnDay,:AtTime,:mykey,:companywebsite,:createdfk,:currentdate)"); 
    $Mail_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Mail_Campaignsinset->bindParam(':CampaignsFromName', $CampaignsFromName, PDO::PARAM_INT);
    $Mail_Campaignsinset->bindParam(':CampaignsFrom', $CampaignsFrom, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsTo', $CampaignsTo, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':Campaignscc', $Campaignscc, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':Campaignsbcc', $Campaignsbcc, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsSubject', $CampaignsSubject, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':CampaignsMessage', $CampaignsMessage, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':TrackOpens', $TrackOpens, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':TrackClicks', $TrackClicks, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':SendCampaignsTimezone', $SendCampaignsTimezone, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':OnDay', $OnDay, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':AtTime', $AtTime, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':createdfk', $createdfk, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':companywebsite', $companywebsite, PDO::PARAM_STR);
    $Mail_Campaignsinset->bindParam(':currentdate', $currentdate, PDO::PARAM_STR);
    $maildata =$Mail_Campaignsinset->execute();
    if($maildata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }

  }
 }
}
}

  if(isset($_REQUEST['WaitCampaigns']))
  {

    $cam_id=$_REQUEST['id'];
    $WDuration=$_REQUEST['WDuration'];
    $DurationType=$_REQUEST['DurationType'];
    $waitCampaignsTimezone=$_REQUEST['waitCampaignsTimezone'];
    $wAtTime=$_REQUEST['wAtTime'];
    $mykey=$_REQUEST['waitkey'];
    
    if($cam_id=='new')
    {
    $Wait_Campaignsinset=$db->prepare("INSERT INTO Wait_Campaigns_Tem(cam_id,WDuration,DurationType,waitCampaignsTimezone,wAtTime,mykey) VALUES(:cam_id,:WDuration,:DurationType,:waitCampaignsTimezone,:wAtTime,:mykey)"); 
    $Wait_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Wait_Campaignsinset->bindParam(':WDuration', $WDuration, PDO::PARAM_INT);
    $Wait_Campaignsinset->bindParam(':DurationType', $DurationType, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':waitCampaignsTimezone', $waitCampaignsTimezone, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':wAtTime', $wAtTime, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $waitdata =$Wait_Campaignsinset->execute();
    if($waitdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $Wait_Updatedatas_find = $db->prepare("SELECT * FROM `Wait_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $Wait_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Wait_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Wait_Updatedatas_find->execute();
    $Wait_Updatedatas_find_all= $Wait_Updatedatas_find->fetch(PDO::FETCH_ASSOC);
     if(!empty($Wait_Updatedatas_find_all))
     {
    $Wait_Updatedatas = $db->prepare("update `Wait_Campaigns_Tem` set WDuration=:WDuration,DurationType=:DurationType,waitCampaignsTimezone=:waitCampaignsTimezone,wAtTime=:wAtTime where cam_id=:cam_id AND mykey=:mykey");

    $Wait_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Wait_Updatedatas->bindParam(':WDuration', $WDuration, PDO::PARAM_INT);
    $Wait_Updatedatas->bindParam(':DurationType', $DurationType, PDO::PARAM_STR);
    $Wait_Updatedatas->bindParam(':waitCampaignsTimezone', $waitCampaignsTimezone, PDO::PARAM_STR);
    $Wait_Updatedatas->bindParam(':wAtTime', $wAtTime, PDO::PARAM_STR);
    $Wait_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $updatewaitdata =$Wait_Updatedatas->execute();
    if($updatewaitdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
   }
   else
   {

    $Wait_Campaignsinset=$db->prepare("INSERT INTO Wait_Campaigns_Tem(cam_id,WDuration,DurationType,waitCampaignsTimezone,wAtTime,mykey) VALUES(:cam_id,:WDuration,:DurationType,:waitCampaignsTimezone,:wAtTime,:mykey)"); 
    $Wait_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Wait_Campaignsinset->bindParam(':WDuration', $WDuration, PDO::PARAM_INT);
    $Wait_Campaignsinset->bindParam(':DurationType', $DurationType, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':waitCampaignsTimezone', $waitCampaignsTimezone, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':wAtTime', $wAtTime, PDO::PARAM_STR);
    $Wait_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $waitdata =$Wait_Campaignsinset->execute();
    if($waitdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }

   }
  }

  }

if(isset($_REQUEST['clickCampaigns']))
  {
    
   $cam_id=$_REQUEST['id'];
     $clickDurationType=$_REQUEST['clickDurationType'];
    $Maxwaittimeclick=$_REQUEST['Maxwaittimeclick'];
   $mykey=$_REQUEST['clickkey'];
    
    if($cam_id=='new')
    {
    $Click_Campaignsinset=$db->prepare("INSERT INTO Click_Campaigns_Tem(cam_id,clickDurationType,Maxwaittimeclick,mykey) VALUES(:cam_id,:clickDurationType,:Maxwaittimeclick,:mykey)"); 
    $Click_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Click_Campaignsinset->bindParam(':clickDurationType', $clickDurationType, PDO::PARAM_INT);
    $Click_Campaignsinset->bindParam(':Maxwaittimeclick', $Maxwaittimeclick, PDO::PARAM_STR);
    $Click_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $clickdata =$Click_Campaignsinset->execute();
    if($clickdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {

    $click_Updatedatas_find = $db->prepare("SELECT * FROM `Click_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $click_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $click_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $click_Updatedatas_find->execute();
    $click_Updatedatas_find_all= $click_Updatedatas_find->fetch(PDO::FETCH_ASSOC);
    if(!empty($click_Updatedatas_find_all))
    {
   
   $click_Updatedatas = $db->prepare("update `Click_Campaigns_Tem` set Maxwaittimeclick=:Maxwaittimeclick,clickDurationType=:clickDurationType where cam_id=:cam_id AND mykey=:mykey");
    $click_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $click_Updatedatas->bindParam(':clickDurationType', $clickDurationType, PDO::PARAM_INT);
    $click_Updatedatas->bindParam(':Maxwaittimeclick', $Maxwaittimeclick, PDO::PARAM_STR);
    $click_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $updateclickdata =$click_Updatedatas->execute();
    if($updateclickdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $Click_Campaignsinset=$db->prepare("INSERT INTO Click_Campaigns_Tem(cam_id,clickDurationType,Maxwaittimeclick,mykey) VALUES(:cam_id,:clickDurationType,:Maxwaittimeclick,:mykey)"); 
    $Click_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Click_Campaignsinset->bindParam(':clickDurationType', $clickDurationType, PDO::PARAM_INT);
    $Click_Campaignsinset->bindParam(':Maxwaittimeclick', $Maxwaittimeclick, PDO::PARAM_STR);
    $Click_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $clickdata =$Click_Campaignsinset->execute();
  }

  }

  }

  if(isset($_REQUEST['openCampaigns']))
  {
    
   $cam_id=$_REQUEST['id'];
     $OpenDurationType=$_REQUEST['OpenDurationType'];
    $Maxwaittimeopen=$_REQUEST['Maxwaittimeopen'];
    $mykey=$_REQUEST['openkey'];
   
    if($cam_id=='new')
    {
    $open_Campaignsinset=$db->prepare("INSERT INTO Open_Campaigns_Tem(cam_id,OpenDurationType,Maxwaittimeopen,mykey) VALUES(:cam_id,:OpenDurationType,:Maxwaittimeopen,:mykey)"); 
    $open_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $open_Campaignsinset->bindParam(':OpenDurationType', $OpenDurationType, PDO::PARAM_INT);
    $open_Campaignsinset->bindParam(':Maxwaittimeopen', $Maxwaittimeopen, PDO::PARAM_STR);
    $open_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $clickdata=$open_Campaignsinset->execute();
    if($clickdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
}
else
{

  $open_Updatedatas_find = $db->prepare("SELECT * FROM `Open_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $open_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $open_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $open_Updatedatas_find->execute();
    $open_Updatedatas_find_all= $open_Updatedatas_find->fetch(PDO::FETCH_ASSOC);

      if(!empty($open_Updatedatas_find_all))
      {
  $open_Updatedatas = $db->prepare("update `Open_Campaigns_Tem` set Maxwaittimeopen=:Maxwaittimeopen,OpenDurationType=:OpenDurationType where cam_id=:cam_id AND mykey=:mykey");
  $open_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $open_Updatedatas->bindParam(':OpenDurationType', $OpenDurationType, PDO::PARAM_INT);
    $open_Updatedatas->bindParam(':Maxwaittimeopen', $Maxwaittimeopen, PDO::PARAM_STR);
    $open_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $openupdatedata=$open_Updatedatas->execute();
    if($openupdatedata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $open_Campaignsinset=$db->prepare("INSERT INTO Open_Campaigns_Tem(cam_id,OpenDurationType,Maxwaittimeopen,mykey) VALUES(:cam_id,:OpenDurationType,:Maxwaittimeopen,:mykey)"); 
    $open_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $open_Campaignsinset->bindParam(':OpenDurationType', $OpenDurationType, PDO::PARAM_INT);
    $open_Campaignsinset->bindParam(':Maxwaittimeopen', $Maxwaittimeopen, PDO::PARAM_STR);
    $open_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $clickdata=$open_Campaignsinset->execute();
    if($clickdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }

  }

}

  }


  if(isset($_REQUEST['tagCampaigns']))
  {

   $cam_id=$_REQUEST['id'];
   $ctag=implode(',',$_REQUEST['tag']);
   $mykey=$_REQUEST['tagkey'];
$taction=$_REQUEST['taction'];
   
    if($cam_id=='new')
    {
    $tag_Campaignsinset=$db->prepare("INSERT INTO Tag_Campaigns_Tem(cam_id,ctag,mykey,tagaction) VALUES(:cam_id,:ctag,:mykey,:taction)"); 
    $tag_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinset->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':taction', $taction, PDO::PARAM_STR);
    $tagdata =$tag_Campaignsinset->execute();
    if($tagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {

    $tag_Updatedatas_find = $db->prepare("SELECT * FROM `Tag_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $tag_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $tag_Updatedatas_find->execute();
    $tag_Updatedatas_all= $tag_Updatedatas_find->fetch(PDO::FETCH_ASSOC);

    if(!empty($tag_Updatedatas_all))
    {
  $tag_Updatedatas = $db->prepare("update `Tag_Campaigns_Tem` set ctag=:ctag,tagaction=:taction where cam_id=:cam_id AND mykey=:mykey");  
  $tag_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Updatedatas->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $tag_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $tag_Updatedatas->bindParam(':taction', $taction, PDO::PARAM_STR);
    $updatetagdata =$tag_Updatedatas->execute();
    if($updatetagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    } 
  }
  else
  {
    $tag_Campaignsinset=$db->prepare("INSERT INTO Tag_Campaigns_Tem(cam_id,ctag,mykey,tagaction) VALUES(:cam_id,:ctag,:mykey,:taction)"); 
    $tag_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinset->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':taction', $taction, PDO::PARAM_STR);
    $tagdata =$tag_Campaignsinset->execute();
    if($tagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  }

  }

    if(isset($_REQUEST['TagcheckCampaigns']))
  {
 
   $cam_id=$_REQUEST['id'];
   $ctag=implode(',',$_REQUEST['tag2']);
   $mykey=$_REQUEST['checktagkey'];
  
   
    if($cam_id=='new')
    {
    $tag_Campaignsinset=$db->prepare("INSERT INTO CheckTag_Campaigns_Tem(cam_id,ctag,mykey) VALUES(:cam_id,:ctag,:mykey)"); 
    $tag_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinset->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    
    $tagdata =$tag_Campaignsinset->execute();
    if($tagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
}
else
{

  $checktag_Updatedatas_find = $db->prepare("SELECT * FROM `CheckTag_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $checktag_Updatedatas_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $checktag_Updatedatas_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $checktag_Updatedatas_find->execute();
    $checktag_Updatedatas_find_all= $checktag_Updatedatas_find->fetch(PDO::FETCH_ASSOC);
    if(!empty($checktag_Updatedatas_find_all))
    {


  $checktag_Updatedatas = $db->prepare("update `CheckTag_Campaigns_Tem` set ctag=:ctag where cam_id=:cam_id AND mykey=:mykey"); 
  $checktag_Updatedatas->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $checktag_Updatedatas->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $checktag_Updatedatas->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $updatechektagdata =$checktag_Updatedatas->execute();
    if($updatechektagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    } 
  }
  else
  {
    $tag_Campaignsinset=$db->prepare("INSERT INTO CheckTag_Campaigns_Tem(cam_id,ctag,mykey) VALUES(:cam_id,:ctag,:mykey)"); 
    $tag_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinset->bindParam(':ctag', $ctag, PDO::PARAM_STR);
    $tag_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    
    $tagdata =$tag_Campaignsinset->execute();
    if($tagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }

}

  }
//
   if(isset($_REQUEST['SmsCampaigns']))
  {
 
  
   $cam_id=$_REQUEST['id'];
   $mykey=$_REQUEST['smskey'];
   $token=$_REQUEST['token'];
   $sid=$_REQUEST['sid'];
   $CampaignssmsFrom=$_REQUEST['CampaignssmsFrom'];
   $CampaignssmsTo=$_REQUEST['CampaignssmsTo'];
   $CampaignssmsMessage=$_REQUEST['CampaignssmsMessage'];
   $smsTrackClicks=$_REQUEST['smsTrackClicks'];


    if($cam_id=='new')
    {
    $tag_Campaignsinsetsms=$db->prepare("INSERT INTO Sms_Campaigns_Tem(cam_id,mykey,token,sid,CampaignssmsFrom,CampaignssmsTo,CampaignssmsMessage,smsTrackClicks) VALUES(:cam_id,:mykey,:token,:sid,:CampaignssmsFrom,:CampaignssmsTo,:CampaignssmsMessage,:smsTrackClicks)"); 
    $tag_Campaignsinsetsms->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinsetsms->bindParam(':mykey', $mykey, PDO::PARAM_STR);
  $tag_Campaignsinsetsms->bindParam(':token', $token, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':sid', $sid, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsFrom', $CampaignssmsFrom, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsTo', $CampaignssmsTo, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsMessage', $CampaignssmsMessage, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':smsTrackClicks', $smsTrackClicks, PDO::PARAM_STR);
    $smstagdata =$tag_Campaignsinsetsms->execute();
    if($smstagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $tag_Campaignsinsetsms_find = $db->prepare("SELECT * FROM `Sms_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $tag_Campaignsinsetsms_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinsetsms_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $tag_Campaignsinsetsms_find->execute();
    $tag_Campaignsinsetsms_find_all= $tag_Campaignsinsetsms_find->fetch(PDO::FETCH_ASSOC);
    if(!empty($tag_Campaignsinsetsms_find_all))
    {
  $tag_Campaignsinsetsms = $db->prepare("update `Sms_Campaigns_Tem` set token=:token, sid=:sid, CampaignssmsFrom=:CampaignssmsFrom, CampaignssmsTo=:CampaignssmsTo, CampaignssmsMessage=:CampaignssmsMessage, smsTrackClicks=:smsTrackClicks  where cam_id=:cam_id AND mykey=:mykey");  
    $tag_Campaignsinsetsms->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinsetsms->bindParam(':mykey', $mykey, PDO::PARAM_STR);
  $tag_Campaignsinsetsms->bindParam(':token', $token, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':sid', $sid, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsFrom', $CampaignssmsFrom, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsTo', $CampaignssmsTo, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsMessage', $CampaignssmsMessage, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':smsTrackClicks', $smsTrackClicks, PDO::PARAM_STR);
    $smstagupdatedata =$tag_Campaignsinsetsms->execute();
    if($smstagupdatedata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $tag_Campaignsinsetsms=$db->prepare("INSERT INTO Sms_Campaigns_Tem(cam_id,mykey,token,sid,CampaignssmsFrom,CampaignssmsTo,CampaignssmsMessage,smsTrackClicks) VALUES(:cam_id,:mykey,:token,:sid,:CampaignssmsFrom,:CampaignssmsTo,:CampaignssmsMessage,:smsTrackClicks)"); 
    $tag_Campaignsinsetsms->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $tag_Campaignsinsetsms->bindParam(':mykey', $mykey, PDO::PARAM_STR);
  $tag_Campaignsinsetsms->bindParam(':token', $token, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':sid', $sid, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsFrom', $CampaignssmsFrom, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsTo', $CampaignssmsTo, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':CampaignssmsMessage', $CampaignssmsMessage, PDO::PARAM_STR);
    $tag_Campaignsinsetsms->bindParam(':smsTrackClicks', $smsTrackClicks, PDO::PARAM_STR);
    $smstagdata =$tag_Campaignsinsetsms->execute();
    if($smstagdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }

  }

  }

  if(isset($_REQUEST['taskCampaigns']))
  {
    
   $cam_id=$_REQUEST['id'];
   $ctasktitle=$_REQUEST['ctasktitle'];
   $ctaskdec=$_REQUEST['ctaskdec'];
   $ctaskduedate=$_REQUEST['ctaskduedate'];
   $mykey=$_REQUEST['taskkey'];
   
    if($cam_id=='new')
    {
    $task_Campaignsinset=$db->prepare("INSERT INTO Task_Campaigns_Tem(cam_id,ctasktitle,ctaskdec,ctaskduedate,mykey) VALUES(:cam_id,:ctasktitle,:ctaskdec,:ctaskduedate,:mykey)"); 
    $task_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $task_Campaignsinset->bindParam(':ctasktitle', $ctasktitle, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskdec', $ctaskdec, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskduedate', $ctaskduedate, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskdata =$task_Campaignsinset->execute();
    if($taskdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
}
else
{

  $task_Campaignsinset_find = $db->prepare("SELECT * FROM `Task_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $task_Campaignsinset_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $task_Campaignsinset_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $task_Campaignsinset_find->execute();
    $task_Campaignsinset_find_all= $task_Campaignsinset_find->fetch(PDO::FETCH_ASSOC);

    if(!empty($task_Campaignsinset_find_all))
    {

  $task_Campaignsinset = $db->prepare("update `Task_Campaigns_Tem` set ctasktitle=:ctasktitle, ctaskdec=:ctaskdec, ctaskduedate=:ctaskduedate where cam_id=:cam_id AND mykey=:mykey");  
  $task_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $task_Campaignsinset->bindParam(':ctasktitle', $ctasktitle, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskdec', $ctaskdec, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskduedate', $ctaskduedate, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskudatedata =$task_Campaignsinset->execute();
    if($taskudatedata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    } 
  }
  else
  {
    $task_Campaignsinset=$db->prepare("INSERT INTO Task_Campaigns_Tem(cam_id,ctasktitle,ctaskdec,ctaskduedate,mykey) VALUES(:cam_id,:ctasktitle,:ctaskdec,:ctaskduedate,:mykey)"); 
    $task_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $task_Campaignsinset->bindParam(':ctasktitle', $ctasktitle, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskdec', $ctaskdec, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':ctaskduedate', $ctaskduedate, PDO::PARAM_STR);
    $task_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskdata =$task_Campaignsinset->execute();
    if($taskdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }

  }

}

  }


  if(isset($_REQUEST['NoteCampaigns']))
  {
    
   $cam_id=$_REQUEST['id'];
   $camnotetitle=$_REQUEST['camnotetitle'];
   $camnotedec=$_REQUEST['camnotedec'];
   $mykey=$_REQUEST['notekey'];
   
   
    if($cam_id=='new')
    {
    $Note_Campaignsinset=$db->prepare("INSERT INTO Note_Campaigns_Tem(cam_id,camnotetitle,camnotedec,mykey) VALUES(:cam_id,:camnotetitle,:camnotedec,:mykey)"); 
    $Note_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Note_Campaignsinset->bindParam(':camnotetitle', $camnotetitle, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':camnotedec', $camnotedec, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskdata =$Note_Campaignsinset->execute();
    if($taskdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
}
else
{

  $Note_Campaignsinset_find = $db->prepare("SELECT * FROM `Note_Campaigns_Tem` WHERE cam_id=:cam_id AND mykey=:mykey");
    $Note_Campaignsinset_find->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Note_Campaignsinset_find->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $Note_Campaignsinset_find->execute();
    $Note_Campaignsinset_find_all= $Note_Campaignsinset_find->fetch(PDO::FETCH_ASSOC);
    if(!empty($Note_Campaignsinset_find_all))
    {
  $Note_Campaignsinset = $db->prepare("update `Note_Campaigns_Tem` set camnotetitle=:camnotetitle, camnotedec=:camnotedec where cam_id=:cam_id AND mykey=:mykey");  
    $Note_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Note_Campaignsinset->bindParam(':camnotetitle', $camnotetitle, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':camnotedec', $camnotedec, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskupdtedata =$Note_Campaignsinset->execute();
    if($taskupdtedata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }
  }
  else
  {
    $Note_Campaignsinset=$db->prepare("INSERT INTO Note_Campaigns_Tem(cam_id,camnotetitle,camnotedec,mykey) VALUES(:cam_id,:camnotetitle,:camnotedec,:mykey)"); 
    $Note_Campaignsinset->bindParam(':cam_id', $cam_id, PDO::PARAM_INT);
    $Note_Campaignsinset->bindParam(':camnotetitle', $camnotetitle, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':camnotedec', $camnotedec, PDO::PARAM_STR);
    $Note_Campaignsinset->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $taskdata =$Note_Campaignsinset->execute();
    if($taskdata)
    {

       echo  json_encode(["resonse"=>'Done']);die;
    }

  }
}

  }

  if(isset($_REQUEST['findalldata']))
  {
    $mykey=$_REQUEST['key'];
    $cmaid=$_REQUEST['cmaid'];
    $divid=$_REQUEST['divid'];
    if($divid=="myModal5")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Wait_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mywaitdata"=>$taskdata_finafdsdaf]);die;
    } 

    if($divid=="myModal")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Mail_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mymaildata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal2")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Task_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mytaskdata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal3")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Tag_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mytagdata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal4")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Note_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mynotedata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal6")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Open_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"myopendata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal7")
    {
    $finafdsdaf=$db->prepare("SELECT * FROM Click_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finafdsdaf->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finafdsdaf->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finafdsdaf->execute();
    $taskdata_finafdsdaf= $finafdsdaf->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"myclcikdata"=>$taskdata_finafdsdaf]);die;
    }

    if($divid=="myModal9")
    {
    $finamsmsdata=$db->prepare("SELECT * FROM Sms_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finamsmsdata->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finamsmsdata->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finamsmsdata->execute();
    $taskdata_finamsmsdata= $finamsmsdata->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mysmsdata"=>$taskdata_finamsmsdata]);die;
    }

        if($divid=="myModal8")
    {
    $finamchecktag=$db->prepare("SELECT * FROM CheckTag_Campaigns_Tem WHERE cam_id=:cmaid AND mykey=:mykey"); 
    $finamchecktag->bindParam(':cmaid', $cmaid, PDO::PARAM_INT);
    $finamchecktag->bindParam(':mykey', $mykey, PDO::PARAM_STR);
    $finamchecktag->execute();
    $taskdata_finamchecktag= $finamchecktag->fetch(PDO::FETCH_ASSOC);
    echo  json_encode(["resonse"=>'Done',"mycheckdata"=>$taskdata_finamchecktag]);die;
    }
    
    
  }

?>
<!DOCTYPE html>
<html lang="en">
    <?php
include 'head.php';
    ?>
    <link href="<?= base_url?>/assets/css/tokenize2.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url?>/assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?= base_url?>/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <!-- <script src="../release/go.js"></script> -->
    <script src="<?php echo base_url; ?>/assets/js/go.js"></script>
    <style>
     
        .eventStart1{
            width: 45%;
            float: left;
            padding: 0 10px;
        }
        .timeinput{
            width: 35%;
            float: left;
            padding: 0 15px;
        }
        span.select2-selection.select2-selection--single{height: 38px !important;}
        .CampaignsTakslist ul li{display: block;}
        li.token-search{width: 100%!important;}
        .dtp{z-index: 999999999;}
        span.button-checkbox {    display: inline-block;    margin: 5px;}
        ul.wysihtml5-toolbar li:nth-child(4) {    display: none;}
    </style>
    <body class="skin-default fixed-layout mysunless19" onload="init()">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure">
                </div>
                <p class="loader__label">
                    <?php echo $_SESSION['UserName']; ?></p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <header class="topbar">
                <?php include 'TopNavigation.php'; ?>
            </header>
            <!-- ============================================================== -->
            <!-- End Topbar header -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <?php include 'LeftSidebar.php'; ?>
            <!-- ============================================================== -->
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <?php
if(isset($_GET["id"]))
{
                            ?>
                            <h4 class="text-themecolor">
                                Edit Campaigns Template
                            </h4>
                            <?php       
}
else
{
                            ?>
                            <h4 class="text-themecolor">
                                Add New Campaigns Template
                            </h4>
                            <?php
}
                            ?>
                            <!-- <h4 class="text-themecolor">Add New Event</h4> -->
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <!-- Tab panes -->
                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="home" role="tabpanel">
                                            <div class="col-lg-12">
                                                <form class="form-horizontal" autocomplete="off" id="NewCategory" method="post">
                                        <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
                                                    <div class="col-lg-12">                                                
                                                  
                                             <div class="col-lg-2 pull-left">
                                               <div class="form-group">
                                         <label><span class="help">Name  *</span></label>
                                         <?php 
                                         if(!empty($Campaigns))
                                         {?>
                                          <input type="text" autocomplete="nope" name="Campaigns" id="Campaigns" value="<?php echo @$Campaigns; ?>" class="form-control" maxlength="30">
                                          <span id="CampaignsNameError" style="color: red;"></span>
                                          <?php
                                       }else
                                       { 
                                        ?>
                                        <input type="text" autocomplete="nope" name="Campaigns" id="Campaigns" value="" class="form-control" maxlength="30">
                                        <span id="CampaignsNameError" style="color: red;"></span>
                                       <?php
                                     } ?>
                 
                                                    </div> 
                                                         </div>  
                                                    

                                                    <div class="col-lg-6 pull-right">
                                                    <div class="form-group">
                                                        <?php
                                    if(isset($_GET["id"]))
                                   {?>
             <button type="button"  class="btn waves-effect waves-light btn-info m-r-10" id="add-client"><i class="fa fa-check"></i> Update Campaigns Template</button>
                                  <?php }
                                  else
                                    {
                                    ?>
            <button type="button"  class="btn waves-effect waves-light btn-info m-r-10" id="add-client"> <i class="fa fa-check"></i> Submit Campaigns Template</button>
                                                        <?php }  ?>
                                <a href="<?php echo base_url; ?>/AllCamTemp.php" type="button" class="btn waves-effect waves-light btn-danger"><i class="fa fa-times">
                                                            </i> Cancel Campaigns</a>
                                                    </div>
                                            </div> 
                                                     </div> 
                                                         <div class="clerfix" style="clear: both;"></div>

                                                    <div class="col-lg-12 col-md-12" style="padding: 25px 0;">
                                                    <div class="alert alert-success" id="resonse" style="display: none;">
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                        <h3 class="text-success">
                                                            <i class="fa fa-check-circle">
                                                            </i>
                                                            Success
                                                        </h3>
                                                        <p id="resonsemsg">
                                                        </p>
                                                    </div>
                                                    <div class="alert alert-danger" id="error" style="display: none;">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                        <h3 class="text-danger">
                                                            <i class="fa fa-exclamation-circle">
                                                            </i>
                                                            Errors
                                                        </h3>
                                                        <p id="errormsg">
                                                        </p>
                                                    </div>

                                                    <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>
                                                    
                                                </div>


<?php 
if(!empty($flowchartdata))
{ ?>

  <textarea hidden name="flowchartdata" id="mySavedModel" class="mySavedModel" style="width:100%;height:300px">
     <?php echo $flowchartdata; ?>
  </textarea>
<?php
}
else
{?>

<textarea hidden name="flowchartdata" id="mySavedModel" class="mySavedModel" style="width:100%;height:300px">
     { "class": "go.GraphLinksModel",
  "linkFromPortIdProperty": "fromPort",
  "linkToPortIdProperty": "toPort",
  "nodeDataArray": [ {"key":-1, "category":"Start", "loc":"113.0000000000002 -1215", "text":"Start"} ],
  "linkDataArray": []}
  </textarea>

<?php
}
?>

                                                </form>    

<!-- ==================Mail popup===================================== -->

<div id="myModal" class="ChildModal" >
    <div class="modal-dialog">
      
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Send Mail</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="mailcampaf" class="mailcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="mailkey" class="myModal_key" value="">
          <input type="hidden" name="mytype" class="mytype" value="Mail">
          <input type="hidden" name="createdfk" class="createdfk" value="<?php echo $_SESSION['UserID']?>">
               <ul class="nav nav-tabs customtab" role="tablist">
                                 <li class="nav-item"> 
                                    <a class="nav-link active" data-toggle="tab" href="#home2A" role="tab">
                                       <span class="hidden-sm-up"><i class="fa fa-envelope"></i></span> <span class="hidden-xs-down">Info</span>
                                    </a> 
                                 </li>
                                 <li class="nav-item"> 
                                    <a class="nav-link" data-toggle="tab" href="#profile2A" role="tab">
                     <span class="hidden-sm-up"><i class="fa fa-cog"></i></span> <span class="hidden-xs-down">Settings</span>
                                    </a> 
                                 </li>
                              </ul>
                              <div style="float: right;"> 
								<div class="form-group">
                                   <select class="ShortCode form-control"  id="ShortCode" name="ShortCode">
                                   <option value="">Select Short Code</option>	
                                   <option id="" value="{{ first_name }}">{{ first_name }}</option>
                                   <option id="" value="{{ last_name }}">{{ last_name }}</option>
                                   <option id="" value="{{ customer_email }}">{{ customer_email }}</option>
                                   <option id="" value="{{ phone }}">{{ phone }}</option>
                                   <option id="" value="{{ location.street }}">{{ location.street }}</option>
                                   <option id="" value="{{ location.city }}">{{ location.city }}</option>
                                   <option id="" value="{{ location.state }}">{{ location.state }}</option>
                                   <option id="" value="{{ location.country }}">{{ location.country }}</option>
                                   <option id="" value="{{ location.zip }}">{{ location.zip }}</option>
                                   <option id="" value="{{ current_date }}">{{ current_date }}</option>
                                   <option id="" value="{{ owner.signature }}">{{ owner.signature }}</option>
                                   <option id="" value="{{ company_phone }}">{{ company_phone }}</option>
                                   <option id="" value="{{ company_name }}">{{ company_name }}</option>
                                   <option id="" value="{{ company_address }}">{{ company_address }}</option>

                              	   
                                   
                                   </select>
                              </div>

                              </div> 
                              <div class="clearfix" style="clear: both;"></div>
                              <div class="tab-content">
                                <div class="tab-pane active" id="home2A" role="tabpanel">
                                    <div class="p-20">
                  <div class="form-group">
                  <label><span class="help">Name  *</span></label>
                  <input type="text" autocomplete="nope" name="CampaignsFromName" id="CampaignsFromName" value="<?php echo $FromName; ?>" class="form-control" maxlength="30">
                  
                   </div>   

                   <div class="form-group">
                    <label>From *</label>
                    <input type="text" autocomplete="nope" name="CampaignsFrom" id="From" class="form-control" placeholder="From" value="<?php echo $From; ?>" readonly>
                   </div>

                   <div class="form-group">
                    <label>To *</label>
                    <input type="text" autocomplete="nope" name="CampaignsTo" id="CampaignsTo" class="CampaignsTo form-control" placeholder="To" value="<?php echo 
                    @$CampaignsTo ; ?>" >
                    </div>

                    <div class="form-group">
                    <label>CC *</label>
                    <input type="text" autocomplete="nope" name="Campaignscc" id="Campaignscc" class="form-control" placeholder="CC" value="<?php echo 
                    @$Campaignscc ; ?>">
                    </div>

                    <div class="form-group">
                    <label>BCC *</label>
                    <input type="text" autocomplete="nope" name="Campaignsbcc" id="Campaignsbcc" class="form-control" placeholder="BCC" value="<?php echo 
                    @$Campaignsbcc ; ?>">
                    </div>
                   
                   <div class="Loader"></div>
                   <div class="form-group">
                     <label>Template *</label>
                    <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                     <select class="Templeate form-control"  id="Templeate" name="CampaignsTempleate">
                     <option value="">Select Email Template</option>
                     <?php 
                     foreach($all_Templeate as $row)
                     {
                     ?>
                     <option value="<?php echo $row['id']?>"><?php echo $row['Name']; ?></option>
                     <?php
                     }
                     ?>
                     </select>
                     <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                     </div>

                     <div class="form-group">
                      <label>Subject *</label>
                      <input type="text" autocomplete="nope" name="CampaignsSubject" id="CampaignsSubject" class="form-control" placeholder="Subject" value="<?php echo @$CampaignsSubject ; ?>" maxlength="300">
                       </div>
                       <div class="form-group">
                       <label>Message *</label>
 <textarea class="textarea_editor CampaignsMessage form-control" rows="10" placeholder="Enter Message ..." id="CampaignsMessage" name="CampaignsMessage" value=""></textarea>
                     </div>

                     <div class="form-group">
                                    <label for="example-email">Company Website *</label>
<input type="url" autocomplete="nope" id="companywebsite" name="companywebsite" value="" class="form-control" placeholder="https://mysunless.com">
                                    </div>



                                    </div>
                                    </div>

                                <div class="tab-pane " id="profile2A" role="tabpanel">
                                    <div class="p-20">
                                  
                                   <div class="form-group">
                                   <label>Track Opens *</label>
                                   <select class="TrackOpens form-control"  id="TrackOpens" name="TrackOpens">
                                   <?php if(!empty($TrackOpens))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $TrackOpens; ?>"><?php echo $TrackOpens; ?></option>
                                   <?php } ?>
                                   <option value="No">No</option>
                                   <option value="Yes">Yes</option>
                                   </select>
                                  </div>

                                  <div class="form-group">
                                   <label>Track Clicks *</label>
                                   <select class="TrackClicks form-control"  id="TrackClicks" name="TrackClicks">
                                    <?php if(!empty($TrackClicks))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $TrackClicks; ?>"><?php echo $TrackClicks; ?></option>
                                   <?php } ?>
                                   <option value="No">No</option>
                                   <option value="Yes">Yes</option>
                                   <option value="Yes&pushE">Yes &  Push (Email Only)</option>
                                   <option value="Yes&push">Yes &  Push</option>
                                   </select>
                                  </div>

                                  <div class="form-group">
                     <label>TimeZone *</label>
                    <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                     <select class="SendCampaignsTimezone form-control"  id="SendCampaignsTimezone" name="SendCampaignsTimezone">
                                  <?php if(!empty($SendCampaignsTimezone))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $SendCampaignsTimezone; ?>"><?php echo $SendCampaignsTimezone; ?></option>
                                   <?php } ?>
                     <option value="">Select Email TimeZone</option>
                     <?php 
                     foreach($all_Timezone as $row)
                     {
                     ?>
                     <option value="<?php echo $row['TimeZoneName']?>"><?php echo $row['TimeZoneName']; ?></option>
                     <?php
                     }
                     ?>
                     </select>
                     <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                     </div>

                               <div class="form-group">
                                   <label>On Day *</label>
                                   <select class="OnDay form-control"  id="OnDay" name="OnDay">
                                    <?php if(!empty($OnDay))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $OnDay; ?>"><?php echo $OnDay; ?></option>
                                   <?php } ?>
                                   <option value="">Any Day</option>
                                    <option value="Mon-Fri">Mon-Fri</option>
                              <option value="Mon-Sat">Mon-Sat</option>
                                   <option value="Sat-Sun">Sat-Sun</option>
                                   <option value="Mon">Mon</option>
                                   <option value="Tue">Tue</option>
                                   <option value="Wed">Wed</option>
                                   <option value="Thu">Thu</option>
                                   <option value="Fri">Fri</option>
                                   <option value="Sat">Sat</option>
                                   <option value="Sun">Sun</option>
                                   
                                   </select>
                              </div>


                               <div class="form-group">
                                   <label>At Time *</label>
                                   <select class="AtTime form-control"  id="AtTime" name="AtTime">
                                      <?php if(!empty($AtTime))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $AtTime; ?>"><?php echo $AtTime; ?></option>
                                   <?php } ?>
                                      <option value="">Any Time</option>
                                       <option value="09:00">9:00 AM</option>
                                       <option value="09:30">9:30 AM</option>
                                       <option value="10:00">10:00 AM</option>
                                       <option value="10:30">10:30 AM</option>
                                       <option value="11:00">11:00 AM</option>
                                       <option value="11:30">11:30 AM</option>
                                       <option value="12:00">12:00 PM</option>
                                       <option value="12:30">12:30 PM</option>
                                       <option value="13:00">1:00 PM</option>
                                       <option value="13:30">1:30 PM</option>
                                       <option value="14:00">2:00 PM</option>
                                       <option value="14:30">2:30 PM</option>
                                       <option value="15:00">3:00 PM</option>
                                       <option value="15:30">3:30 PM</option>
                                       <option value="16:00">4:00 PM</option>
                                       <option value="16:30">4:30 PM</option>
                                       <option value="17:00">5:00 PM</option>
                                       <option value="17:30">5:30 PM</option>
                                       <option value="18:00">6:00 PM</option>
                                       <option value="18:30">6:30 PM</option>
                                       <option value="19:00">7:00 PM</option>
                                       <option value="19:30">7:30 PM</option>
                                       <option value="20:00">8:00 PM</option>
                                       <option value="20:30">8:30 PM</option>
                                       <option value="21:00">9:00 PM</option>
                                       <option value="21:30">9:30 PM</option>
                                       <option value="22:00">10:00 PM</option>
                                       <option value="22:30">10:30 PM</option>
                                       <option value="23:00">11:00 PM</option>
                                       <option value="23:30">11:30 PM</option>
                                       <option value="00:01">12:00 AM</option>
                                       <option value="00:30">12:30 AM</option>
                                       <option value="01:00">1:00 AM</option>
                                       <option value="01:30">1:30 AM</option>
                                       <option value="02:00">2:00 AM</option>
                                       <option value="02:30">2:30 AM</option>
                                       <option value="03:00">3:00 AM</option>
                                       <option value="03:30">3:30 AM</option>
                                       <option value="04:00">4:00 AM</option>
                                       <option value="04:30">4:30 AM</option>
                                       <option value="05:00">5:00 AM</option>
                                       <option value="05:30">5:30 AM</option>
                                       <option value="06:00">6:00 AM</option>
                                       <option value="06:30">6:30 AM</option>
                                       <option value="07:00">7:00 AM</option>
                                       <option value="07:30">7:30 AM</option>
                                       <option value="08:00">8:00 AM</option>
                                       <option value="08:30">8:30 AM</option>
                                   </select>
                              </div>

                                    </div>
                                    </div>                                    
                                    </div>
        </div>
        <div class="modal-footer">

        	 	<div class="col-lg-10 col-md-10" style="padding: 25px 0;">
                    <div class="alert alert-danger" id="Validemail_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="Validemail_errormsg"></p>
                    </div>
					</div>
					
          <button type="button" id="mailcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>

         
        </div>
    </form>
      </div>
      
    </div>
  </div>
     <!-- <div id="myModal" class="ChildModal">
<div class="CM-content">
    <span class="CM-close">&times;</span>
    <p>This for Mail..</p>
  </div>

</div> -->
<!-- ==================Mail popup===================================== -->





<!-- ==================SMS popup===================================== -->

<div id="myModal9" class="ChildModal" >
    <div class="modal-dialog">
      
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Send SMS</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="smscampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="smskey" class="myModal9_key" value="">
          <input type="hidden" name="mytype" class="mytype" value="Sms">
          <input type="hidden" name="token" class="mytype" value="<?php echo $token;?>">
          <input type="hidden" name="sid" class="mytype" value="<?php echo $sid;?>">
        
                   <div class="form-group">
                    <label>From *</label>
                    <input type="text" name="CampaignssmsFrom" id="From" class="form-control" placeholder="From" value="<?php echo $CampaignssmsFrom; ?>" readonly>
                   </div>

                   <div class="form-group">
                    <label>To *</label>
                    <input type="text" name="CampaignssmsTo" id="CampaignssmsTo" class="form-control" placeholder="To" value="" >
                   </div>

                   
                       <div class="form-group">
                       <label>Message *</label>
               <textarea class="textarea_editor CampaignssmsMessage form-control" rows="10" placeholder="Enter Message ..." id="CampaignssmsMessage" name="CampaignssmsMessage" value=""></textarea>
                     </div>

                              
                              <div class="form-group">
                                   <label>Track Clicks *</label>
                                   <select class="smsTrackClicks form-control"  id="smsTrackClicks" name="smsTrackClicks">
                                    <?php if(!empty($smsTrackClicks))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $smsTrackClicks; ?>"><?php echo $smsTrackClicks; ?></option>
                                   <?php } ?>
                                   <option value="No">No</option>
                                   <option value="Yes">Yes</option>
                                   <option value="Yes&pushE">Yes &  Push (Email Only)</option>
                                   <option value="Yes&push">Yes &  Push</option>
                                   </select>
                                  </div>

        </div>
        <div class="modal-footer">
          <button type="button" id="smscampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
     <!-- <div id="myModal" class="ChildModal">
<div class="CM-content">
    <span class="CM-close">&times;</span>
    <p>This for Mail..</p>
  </div>

</div> -->
<!-- ==================Mail popup===================================== -->


<!-- ==================Task popup===================================== -->

<div id="myModal2" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Task</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="Taskcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="taskkey" class="myModal2_key">
          <input type="hidden" name="mytype" class="mytype" value="Task">
           <div class="form-group">
                                            <label for="ctasktitle">
                                                Title 
                                                
                                            </label>
                                            <br>
 <input type="text" name= "ctasktitle" id="ctasktitle" placeholder="Enter Task Title...." class="form-control" value="<?php echo @$ctasktitle; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ctaskdec">
                                                Description 
                                                </label>
                                            <br>
<textarea class="textarea_editor form-control ctaskdec" rows="10" placeholder="Enter Task Description..." id="ctaskdec" name="ctaskdec"></textarea>
                                        </div>
                                        <div class="form-group" id= "datetimepicker">
                                          
                                            <label for="ctaskduedate">
                                                Due Date
                                                </label>
                                            <br>
 <input type="text" name= "ctaskduedate" placeholder=" Select Due Date...." class="form-control"  id="ctaskduedate" readonly value="<?php echo @$ctaskduedate; ?>">
                                        </div>
                              
        </div>
        <div class="modal-footer">
           
          <button type="button" id="Taskcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
<!-- ==================Task popup===================================== -->



<!-- ==================Note popup===================================== -->

<div id="myModal4" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Note</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="Notecampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="notekey" class="myModal4_key">
          <input type="hidden" name="mytype" class="mytype" value="Note">
           <div class="form-group">
               <label for="camnotetitle">
               Title 
               </label>
               <br>
            <input type="text" name= "camnotetitle" id="camnotetitle" placeholder="Enter Task Title...." class="form-control" value="<?php echo @$camnotetitle; ?>">
               </div>
            
            <div class="form-group">
            <label for="camnotedec">
            Description 
            </label>
            <br>
<textarea class="textarea_editor camnotedec form-control" rows="10" placeholder="Enter Task Description..." id="camnotedec" name="camnotedec"></textarea>
            </div>

                              
        </div>
        <div class="modal-footer">
           
          <button type="button" id="Notecampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
<!-- ==================Note popup===================================== -->



<!-- ==================tag popup===================================== -->
<div id="myModal3" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tag</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="tagcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="tagkey" class="myModal3_key">
          <input type="hidden" name="mytype" class="mytype" value="Tag">


          <div class="form-group">
                                   <label>Action</label>
                                   <select class="taction form-control"  id="taction" name="taction">
                                    <?php if(!empty($OnDay))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $taction; ?>"><?php echo $taction; ?></option>
                                   <?php } ?>
                                   <option value="Add">Add</option>
                                   <option value="Delete">Delete</option>
                                   </select>
                              </div>
<div class="tagdiv">
<span class="tagtitle">SELECTED TAGS</span>
<div class="selectedtag">
  
  <span class="notagselect">No tag selected.</span>   

</div>
<span class="tagtitle">INACTIVE (Click to Activate)</span>
<div class="unselectedtag">
<?php 

if(!empty($Allmytag))
  {
   
   foreach ($Allmytag as $tava) 
  {
    ?>
<span class="button-checkbox">
        <button type="button" class="btn checkedcamtag" data-color="info"><?php echo $tava['tag']; ?></button>
        <input type="checkbox" class="hidden tag" id="tag" name="tag[]" value="<?php echo $tava['id']; ?>" hidden />
         </span> 

  <?php
}

  }
    ?>
</div>


 
        </div>
       </div> 
        <div class="modal-footer">
           
          <button type="button" id="tagcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
  <!-- ==================tag popup===================================== -->

  <!-- ==================check tag popup===================================== -->
<div id="myModal8" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">check Tag</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="checktagcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="checktagkey" class="myModal8_key">
          <input type="hidden" name="mytype" class="mytype" value="CheckTag">

<div class="tagdiv">
<span class="tagtitle">SELECTED TAGS</span>
<div class="selectedtag">
  
  <span class="notagselect">No tag selected.</span>   

</div>
<span class="tagtitle">INACTIVE (Click to Activate)</span>
<div class="unselectedtag">
  
<?php 

if(!empty($Allmytag))
  {
   
   foreach ($Allmytag as $tava) 
  {
    ?>
<span class="button-checkbox">
        <button type="button" class="btn" data-color="info"><?php echo $tava['tag']; ?></button>
        <input type="checkbox" class="hidden tag2" id="tag2" name="tag2[]" value="<?php echo $tava['id']; ?>" hidden />
         </span> 

  <?php
}

  }
    ?>
</div>


 
        </div>

 
        </div>
        <div class="modal-footer">
           
          <button type="button" id="checktagcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
  <!-- ==================check tag popup===================================== -->

<!-- ==================Wait popup===================================== -->
<div id="myModal5" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Wait</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="waitcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="waitkey" class="myModal5_key">
          <input type="hidden" name="mytype" class="mytype" value="Wait">
          
           <ul class="nav nav-tabs customtab" role="tablist">
                                 <li class="nav-item"> 
                                    <a class="nav-link active" data-toggle="tab" href="#home2AB" role="tab">
                                       <span class="hidden-sm-up"><i class="fa fa-envelope"></i></span> <span class="hidden-xs-down">Info</span>
                                    </a> 
                                 </li>
                                 <li class="nav-item"> 
                                    <a class="nav-link" data-toggle="tab" href="#profile2B" role="tab">
                     <span class="hidden-sm-up"><i class="fa fa-cog"></i></span> <span class="hidden-xs-down">Settings</span>
                                    </a> 
                                 </li>
                              </ul>

                              <div class="tab-content">
                                <div class="tab-pane active" id="home2AB" role="tabpanel">
                                    <div class="p-20">
                    
                      <div class="form-group">
               <label for="camnotetitle">
                Duration
               </label>
               <br>
            <input type="Number" name= "WDuration" id="WDuration" placeholder="" class="form-control" value="<?php echo @$WDuration; ?>">
               </div>
                    
                   <div class="Loader"></div>
                   
                      <div class="form-group">
                                   <label>Type *</label>
                                   <select class="DurationType form-control"  id="DurationType" name="DurationType">
                                    <?php if(!empty($DurationType))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $DurationType; ?>"><?php echo $DurationType; ?></option>
                                   <?php } ?>
                                   <!-- <option value="Minutes">Minutes</option>
                                   <option value="Hours">Hours</option>
                                   <option value="Day">Day</option> -->
                                   
                                   </select>
                              </div>


                                    </div>
                                    </div>

                                <div class="tab-pane " id="profile2B" role="tabpanel">
                                    <div class="p-20">
                                  

                                  <div class="form-group">
                     <label>TimeZone *</label>
                    <!-- <input type="text" name="To" id="To" class="form-control" placeholder="To" value=""> -->
                     <select class="waitCampaignsTimezone form-control"  id="waitCampaignsTimezone" name="waitCampaignsTimezone">
                                  <?php if(!empty($waitCampaignsTimezone))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $waitCampaignsTimezone; ?>"><?php echo $waitCampaignsTimezone; ?></option>
                                   <?php } ?>
                     <option value="">Select Email TimeZone</option>
                     <?php 
                     foreach($all_Timezone as $row)
                     {
                     ?>
                     <option value="<?php echo $row['TimeZoneName']?>"><?php echo $row['TimeZoneName']; ?></option>
                     <?php
                     }
                     ?>
                     </select>
                     <!-- https://jsfiddle.net/fr0z3nfyr/uxa6h1jy/ -->
                     </div>


                               <div class="form-group">
                                   <label>At Time *</label>
                                   <select class="wAtTime form-control"  id="wAtTime" name="wAtTime">
                                      <?php if(!empty($wAtTime))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $wAtTime; ?>"><?php echo $wAtTime; ?></option>
                                   <?php } ?>
                                      <option value="">Any Time</option>
                                       <option value="09:00">9:00 AM</option>
                                       <option value="09:30">9:30 AM</option>
                                       <option value="10:00">10:00 AM</option>
                                       <option value="10:30">10:30 AM</option>
                                       <option value="11:00">11:00 AM</option>
                                       <option value="11:30">11:30 AM</option>
                                       <option value="12:00">12:00 PM</option>
                                       <option value="12:30">12:30 PM</option>
                                       <option value="13:00">1:00 PM</option>
                                       <option value="13:30">1:30 PM</option>
                                       <option value="14:00">2:00 PM</option>
                                       <option value="14:30">2:30 PM</option>
                                       <option value="15:00">3:00 PM</option>
                                       <option value="15:30">3:30 PM</option>
                                       <option value="16:00">4:00 PM</option>
                                       <option value="16:30">4:30 PM</option>
                                       <option value="17:00">5:00 PM</option>
                                       <option value="17:30">5:30 PM</option>
                                       <option value="18:00">6:00 PM</option>
                                       <option value="18:30">6:30 PM</option>
                                       <option value="19:00">7:00 PM</option>
                                       <option value="19:30">7:30 PM</option>
                                       <option value="20:00">8:00 PM</option>
                                       <option value="20:30">8:30 PM</option>
                                       <option value="21:00">9:00 PM</option>
                                       <option value="21:30">9:30 PM</option>
                                       <option value="22:00">10:00 PM</option>
                                       <option value="22:30">10:30 PM</option>
                                       <option value="23:00">11:00 PM</option>
                                       <option value="23:30">11:30 PM</option>
                                       <option value="00:01">12:00 AM</option>
                                       <option value="00:30">12:30 AM</option>
                                       <option value="01:00">1:00 AM</option>
                                       <option value="01:30">1:30 AM</option>
                                       <option value="02:00">2:00 AM</option>
                                       <option value="02:30">2:30 AM</option>
                                       <option value="03:00">3:00 AM</option>
                                       <option value="03:30">3:30 AM</option>
                                       <option value="04:00">4:00 AM</option>
                                       <option value="04:30">4:30 AM</option>
                                       <option value="05:00">5:00 AM</option>
                                       <option value="05:30">5:30 AM</option>
                                       <option value="06:00">6:00 AM</option>
                                       <option value="06:30">6:30 AM</option>
                                       <option value="07:00">7:00 AM</option>
                                       <option value="07:30">7:30 AM</option>
                                       <option value="08:00">8:00 AM</option>
                                       <option value="08:30">8:30 AM</option>
                                   </select>
                              </div>

                                    </div>
                                    </div>                                    
                                    </div>
  
        </div>
        <div class="modal-footer">
           
          <button type="button" id="waitcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
  <!-- ==================Wait popup===================================== -->

  <!-- ==================Opend? popup===================================== -->
<div id="myModal6" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Opened?</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="opencampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
                   <input type="hidden" name="openkey" class="myModal6_key">
                   <input type="hidden" name="mytype" class="mytype" value="Open">
                      <div class="form-group">
               <label for="camnotetitle">
                Max wait time
               </label>
               <br>
            <input type="Number" name= "Maxwaittimeopen" id="Maxwaittimeopen" placeholder="" class="form-control" value="<?php echo @$Maxwaittimeopen; ?>">
               </div>
                    
                   <div class="Loader"></div>
                   
                      <div class="form-group">
                                   <label>Type *</label>
                                   <select class="OpenDurationType form-control"  id="OpenDurationType" name="OpenDurationType">
                                    <?php if(!empty($OpenDurationType))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $OpenDurationType; ?>"><?php echo $OpenDurationType; ?></option>
                                   <?php } ?>
                                   <!-- <option value="Minutes">Minutes</option>
                                   <option value="Hours">Hours</option>
                                   <option value="Day">Day</option> -->
                                   
                                   </select>
                              </div>


                                    </div>
                                          
      
        <div class="modal-footer">
           
          <button type="button" id="opencampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
  <!-- ==================Opend? popup===================================== -->

  <!-- ==================Clicked? popup===================================== -->
<div id="myModal7" class="ChildModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Clicked?</h4>
          <span class="CM-close">&times;</span>
        </div>
        <div class="modal-body">
          <form id="clickcampaf">
            <input type="hidden" name="id" id="id" value="<?php echo $myCampaigns; ?>">
          <input type="hidden" name="clickkey" class="myModal7_key">
          <input type="hidden" name="mytype" class="mytype" value="Click">

                       <div class="form-group">
               <label for="camnotetitle">
                Max wait time
               </label>
               <br>
            <input type="Number" name= "Maxwaittimeclick" id="Maxwaittimeclick" placeholder="" class="form-control" value="<?php echo @$Maxwaittimeclick; ?>">
               </div>
                    
                   <div class="Loader"></div>
                   
                      <div class="form-group">
                                   <label>Type *</label>
                                   <select class="clickDurationType form-control"  id="clickDurationType" name="clickDurationType">
                                    <?php if(!empty($clickDurationType))
                                   {
                                    ?>  
                                    <option selected value="<?php echo $clickDurationType; ?>"><?php echo $clickDurationType; ?></option>
                                   <?php } ?>
                                   <!-- <option value="Minutes">Minutes</option>
                                   <option value="Hours">Hours</option>
                                   <option value="Day">Day</option> -->
                                   
                                   </select>
                              </div>

        </div>
        <div class="modal-footer">
           
          <button type="button" id="clickcampa" class="btn btn-default" onclick="save()" data-dismiss="modal">Save</button>
        </div>
    </form>
      </div>
      
    </div>
  </div>
  <!-- ==================Clicked? popup===================================== -->

<!-- ==================Map 2===================================== -->
 <div class="modal fade" id="myModal_map2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Campaigns Template Summmery</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
            <div id="newcart">
            <div id="sample">
  <div style="width:100%; white-space:nowrap;">
    <span style="display: inline-block; vertical-align: top; width:100px">
      <!-- <div id="myPaletteDiv2" style="border: solid 1px black; height: 720px"></div> -->
    </span>

    <span class="medan" style="display: block; vertical-align: top; width:100%; position: relative;">
      <div id="myDiagramDiv2" style="border: solid 1px black; height: 720px"></div>
     <!--    <div id="contextMenu2">
        <ul>
          <li id="delete" onclick="cxcommand(event)"><a href="#" target="_self">Delete</a></li>
          
        </ul>
      </div> -->
    </span>
  </div>

<textarea hidden id="mySavedModel2" style="width:100%;height:300px">
  { "class": "go.GraphLinksModel",
  "linkFromPortIdProperty": "fromPort",
  "linkToPortIdProperty": "toPort",
  "nodeDataArray": [ {"key":-1, "category":"Start", "loc":"113.0000000000002 -1215", "text":"Start"} ],
  "linkDataArray": []}
</textarea>


  </div>  
</div>
   </div>
   <div class="Loader"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="Submit"  class="btn waves-effect waves-light btn-info m-r-10" id="add_client_cam"><i class="fa fa-check"></i> Submit Campaigns Template</button>
        </div>
      </div>
      
    </div>
  </div>

<!-- ==================Map 2===================================== -->

<div id="sample">
  <div style="width:100%; white-space:nowrap;">
    <span style="display: inline-block; vertical-align: top; width:100px">
      <div id="myPaletteDiv" style="border: solid 1px black; height: 720px"></div>
    </span>

    <span class="medan" style="display: inline-block; vertical-align: top; width:80%; position: relative;">
      <div id="myDiagramDiv" style="border: solid 1px black; height: 720px"></div>
        <div id="contextMenu">
        <ul>
          <!-- <li id="cut" onclick="cxcommand(event)"><a href="#" target="_self">Cut</a></li>
          <li id="copy" onclick="cxcommand(event)"><a href="#" target="_self">Copy</a></li>
          <li id="paste" onclick="cxcommand(event)"><a href="#" target="_self">Paste</a></li> -->
          <li id="delete" onclick="cxcommand(event)"><a href="#" target="_self">Delete</a></li>
          <!-- <li id="color" class="hasSubMenu"><a href="#" target="_self">Color</a>
            <ul class="subMenu" id="colorSubMenu">
                <li style="background: crimson;" onclick="cxcommand(event, 'color')"><a href="#" target="_self">Red</a></li>
                <li style="background: chartreuse;" onclick="cxcommand(event, 'color')"><a href="#" target="_self">Green</a></li>
                <li style="background: aquamarine;" onclick="cxcommand(event, 'color')"><a href="#" target="_self">Blue</a></li>
                <li style="background: gold;" onclick="cxcommand(event, 'color')"><a href="#" target="_self">Yellow</a></li>
            </ul>
          </li> -->
        </ul>
      </div>
    </span>
  </div>
  <!-- <p>
  The FlowChart sample demonstrates several key features of GoJS,
  namely <a href="../intro/palette.html">Palette</a>s,
  <a href="../intro/links.html">Linkable nodes</a>, Drag/Drop behavior,
  <a href="../intro/textBlocks.html">Text Editing</a>, and the use of
  <a href="../intro/templateMaps.html">Node Template Maps</a> in Diagrams.
  </p>
  <p>
  Mouse-over a Node to view its ports.
  Drag from these ports to create new Links.
  Selecting Links allows you to re-shape and re-link them.
  Selecting a Node and then clicking its TextBlock will allow
  you to edit text (except on the Start and End Nodes).
  </p> -->
  <!-- <button id="SaveButton" onclick="save()">Save</button>
  <button onclick="load()">Load</button> -->
  <!-- Diagram Model saved in JSON format: -->
  
  <!-- <p>Click the button below to render the current GoJS Diagram into SVG at one-half scale.
     The SVG is not interactive like the GoJS diagram, but can be used for printing or display.
     For more information, see the page on <a href="../intro/makingSVG.html">making SVG</a>.</p>
  <button onclick="makeSVG()">Render as SVG</button>
  <div id="SVGArea"></div> -->
<!-- </div> -->


                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Container fluid  -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php include 'footer.php'; ?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <?php include 'scripts.php'; ?>
        <script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>
  <script src="<?php echo base_url; ?>/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
       
       <script type="text/javascript">
    $(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),

            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});
</script>
        <script>
          $('#CampaignsMessage').wysihtml5();
          $('#CampaignssmsMessage').wysihtml5();
          
          function OpenModal(id,key){


var simple = '<?php echo $myCampaigns; ?>';
  jQuery("#"+id+' .'+id+'_key').val(key);
  jQuery("#"+id).css({"display":"block"});
  
  if(simple!='new')
  {
  $.ajax({
            dataType:"json",
            type:"post",
            data : { key : key, cmaid : simple, divid:id},
            url:'?findalldata',
            success: function(data)
            {
            if(data.mywaitdata)
            {
              
              $("#WDuration").val(data.mywaitdata.WDuration);
              // $('#DurationType').val(data.mywaitdata.DurationType);  
              $('#DurationType').append("<option value='"+data.mywaitdata.DurationType+"'>"+data.mywaitdata.DurationType+"</option>"); 
              $('#waitCampaignsTimezone').val(data.mywaitdata.waitCampaignsTimezone);  
              $('#wAtTime').val(data.mywaitdata.wAtTime);  
            }
             
             else if(data.mymaildata)
             {
              console.log(data.mymaildata);
              $("#CampaignsFrom").val(data.mymaildata.CampaignsFrom);
              $("#CampaignsFromName").val(data.mymaildata.CampaignsFromName);
              $("#CampaignsTo").val(data.mymaildata.CampaignsTo);
              $("#Campaignscc").val(data.mymaildata.Campaignscc);
              $("#Campaignsbcc").val(data.mymaildata.Campaignsbcc);
              $("#CampaignsSubject").val(data.mymaildata.CampaignsSubject);
              
              $('iframe').contents().find('.CampaignsMessage').html(data.mymaildata.CampaignsMessage);
    $('#SendCampaignsTimezone').val(data.mymaildata.SendCampaignsTimezone); 
$('#TrackOpens').val(data.mymaildata.TrackOpens); 
$('#TrackClicks').val(data.mymaildata.TrackClicks); 
$('#OnDay').val(data.mymaildata.OnDay); 
$('#AtTime').val(data.mymaildata.AtTime); 
$('#companywebsite').val(data.mymaildata.companywebsite); 

             }

             else if(data.myclcikdata)
            {
              
              $("#Maxwaittimeclick").val(data.myclcikdata.Maxwaittimeclick);
              // $('#clickDurationType').val(data.myclcikdata.clickDurationType);  
              $('#clickDurationType').append("<option value='"+data.myclcikdata.clickDurationType+"'>"+data.myclcikdata.clickDurationType+"</option>"); 
            }
            else if(data.myopendata)
            {
              
              $("#Maxwaittimeopen").val(data.myopendata.Maxwaittimeopen);
              // $('#OpenDurationType').val(data.myopendata.OpenDurationType);  
              $('#OpenDurationType').append("<option value='"+data.myopendata.OpenDurationType+"'>"+data.myopendata.OpenDurationType+"</option>"); 
            }
      else if(data.mytagdata)
            {
              
              $("#taction").val(data.mytagdata.tagaction);
              var tagval = data.mytagdata.ctag;
                arr = tagval.split(',');
          
               jQuery('.tag').each(function(){
        var categoryValue= jQuery(this).val();
                
                  if(jQuery.inArray(categoryValue, arr) !== -1)
                  {
                    $(this).attr('checked', true);
                    $(this).parent('span').children('button').addClass('btn-info active');

                  
                  }
              });
            }

            else if(data.mytaskdata)
            {
              
              $("#ctasktitle").val(data.mytaskdata.ctasktitle);
              console.log(data.mytaskdata.ctaskdec);
              $('iframe').contents().find('.ctaskdec').html(data.mytaskdata.ctaskdec);
              
              $("#ctaskduedate").val(data.mytaskdata.ctaskduedate);
              
              
            }

            else if(data.mynotedata)
            {
              
              $("#camnotetitle").val(data.mynotedata.camnotetitle);
              $('iframe').contents().find('.camnotedec').html(data.mynotedata.camnotedec);
              
            }

            else if(data.mysmsdata)
            {
              
              $("#CampaignssmsTo").val(data.mysmsdata.CampaignssmsTo);
              $('iframe').contents().find('.CampaignssmsMessage').html(data.mysmsdata.CampaignssmsMessage);
              $("#smsTrackClicks").val(data.mysmsdata.smsTrackClicks);
              
            }


            else if(data.mycheckdata)
            {
              
                var tagval2 = data.mycheckdata.ctag;
                
    arr2 = tagval2.split(',');
          
               jQuery('.tag2').each(function(){
        var categoryValue2= jQuery(this).val();
                
                  if(jQuery.inArray(categoryValue2, arr2) !== -1)
                  {
                    $(this).attr('checked', true);
                    $(this).parent('span').children('button').addClass('btn-info active');

                  
                  }
              });
              
            }

             }
             })
             }  

}

  $(document).ready(function(){
        //active class
        setInterval(function(){$(".camptemp").addClass("active");}, 10);
  	var latinputid;
  	var oldval;
  	 $('.mailcampaf input').focusin(function() {
  	 	latinputid = document.activeElement.id
  	 	oldval=document.activeElement.value
});

	$("#ShortCode").change(function(e) {
    e.preventDefault();
  	var cushor = $(this).val()

  	 $("#"+latinputid).val(cushor+' '+oldval);
// $("#"+latinputid).contents().find('.'latinputid).append(cushor);
});

    $('iframe').contents().find('#mailcampaf .wysihtml5-editor').html('{{ first_name }} {{ last_name }}');

    // $("form#mailcampaf ul.wysihtml5-toolbar").append('<li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode" title="Insert ShortCode" href="javascript:;" unselectable="on">ShortCoad</a></li>');

    $("form#mailcampaf ul.wysihtml5-toolbar").append('<li class="dropdown"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#">ShortCoad</span>&nbsp;<b class="caret"></b></a><ul class="dropdown-menu"><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode1" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ first_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode2" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ last_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode3" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ customer_email }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode5" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.street }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode6" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.city }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode7" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.state }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode8" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.country }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode9" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ location.zip }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode11" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ owner.signature }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode12" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_phone }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode13" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_name }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode14" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ company_address }}</a></li><li class="dropdown shortcode"><a class="btn" data-wysihtml5-command="" id="insertshortcode4" title="Insert ShortCode" href="javascript:;" unselectable="on">{{ phone }}</a></li></ul><li>');



    $("#insertshortcode1").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ first_name }}');
});

    $("#insertshortcode2").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ last_name }}');
});

$("#insertshortcode3").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ customer_email }}');
});

$("#insertshortcode4").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ phone }}');
});
//
$("#insertshortcode5").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.street }}');
});


$("#insertshortcode6").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.city }}');
});

$("#insertshortcode7").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.state }}');
});

$("#insertshortcode8").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.country }}');
});

$("#insertshortcode9").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ location.zip }}');
});

$("#insertshortcode10").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ current_date }}');
});

$("#insertshortcode11").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ owner.signature }}');
});

$("#insertshortcode12").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_phone }}');
});

$("#insertshortcode13").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_name }}');
});

$("#insertshortcode14").click(function(e){
    e.preventDefault();
  $('iframe').contents().find('.wysihtml5-editor').append('{{ company_address }}');
});

    $('#CampaignssmsTo').keyup(function(e){
        var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
    });

    $("#mailcampaf").validate({     
      rules: {                
          CampaignsFromName: "required",
          CampaignsTo: "required",
          CampaignsSubject: "required",
          CampaignsMessage: "required",
          
          
        },

        messages: 
        {             
            CampaignsFromName: "Please enter From Name",
            CampaignsTo:"Please enter mail to email",
          CampaignsSubject:"Please enter mail subject",
          CampaignsMessage:"Please enter mail massage",
        },

            ignore: ":hidden:not(textarea)",
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "CampaignsMessage") {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },      
     
    });

    $('#mailcampa').on('click',function(e){
        var validationCall=$("#mailcampaf").valid();
        
        if(validationCall==true)
        {
        
        var data = $("#mailcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?MailCampaigns',
            success: function(data)
            {
            	
            if(data.resonse)
            {

                 $( '#mailcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             else if(data.Validemail)
             {
             $("#Validemail_error").show();
			$('#Validemail_errormsg').html('<span>'+data.Validemail+'</span>');
             }
             }
             })
    }
    });


    $("#waitcampaf").validate({     
      rules: {                
          WDuration: "required",
          DurationType: "required",
            
        },

        messages: 
        {             
            WDuration: "Please enter Wait Duration",
            DurationType: "Please select Wait Duration type",
            
        },

     
    });

    $('#waitcampa').on('click',function(e){
        var validationCall2=$("#waitcampaf").valid();
        if(validationCall2==true)
        {

        var data = $("#waitcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?WaitCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#waitcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }

    });

    $("#clickcampaf").validate({      
      rules: {                
          Maxwaittimeclick: "required",
          clickDurationType: "required",
        },
    messages: 
        {             
            Maxwaittimeclick: "Please enter max wait time for click",
            clickDurationType: "Please select Wait type",
        },
    });


        $('#clickcampa').on('click',function(e){
        
        var validationCall3=$("#clickcampaf").valid();
        if(validationCall3==true)
        {
        var data = $("#clickcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?clickCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#clickcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }

    });


            $("#opencampaf").validate({     
      rules: {                
          Maxwaittimeopen: "required",
          
        },
    messages: 
        {             
            Maxwaittimeopen: "Please enter max wait time for open",
            
        },
    });

        $('#opencampa').on('click',function(e){

          var validationCall4=$("#opencampaf").valid();
        if(validationCall4==true)
        {
        
        var data = $("#opencampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?openCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#opencampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }

    });



        $('#tagcampa').on('click',function(e){
          
          
    var data = $("#tagcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?tagCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#tagcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    
  
    

    });
 

  $(document).on('click','.unselectedtag span.button-checkbox',function(){

  $(this).children('input').attr('checked','true');
          $(".notagselect").remove();
          var newhtml = $(this).html();
          $(".selectedtag").append('<span class="button-checkbox">'+newhtml+'</span>');
          $(this).remove();

          
        });

$(document).on('click','.selectedtag span.button-checkbox',function(e){
          e.preventDefault();
           $(this).children('input').removeAttr("checked");
var newhtml2 = $(this).html();
          $(".unselectedtag").append('<span class="button-checkbox">'+newhtml2+'</span>');
      $(this).remove();

          
       });




        $("#Taskcampaf").validate({     
      rules: {                
          ctasktitle: "required",
          ctaskdec: "required",
          ctaskduedate: "required",
        },
    messages: 
        {             
            ctasktitle: "Please enter Task title",
            ctaskdec: "Please enter Task Description",
            ctaskduedate: "Please select due date",
            
        },

             ignore: ":hidden:not(textarea)",
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "ctaskdec") {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },
    });


        $('#Taskcampa').on('click',function(e){
         
            var validationCall6=$("#Taskcampaf").valid();
        if(validationCall6==true)
        {
        var data = $("#Taskcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?taskCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#Taskcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }

    });


     $("#Notecampaf").validate({      
      rules: {                
          camnotetitle: "required",
          camnotedec: "required",
          
        },
    messages: 
        {             
            camnotetitle: "Please enter Note title",
            camnotedec: "Please enter Note Description",
          
            
        },

             ignore: ":hidden:not(textarea)",
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "camnotedec") {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },
    });

$('#Notecampa').on('click',function(e){
        
        var validationCall7=$("#Notecampaf").valid();
        if(validationCall7==true)
        {
        var data = $("#Notecampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?NoteCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#Notecampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }

    });

$('#checktagcampa').on('click',function(e){
        
        var data = $("#checktagcampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?TagcheckCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#checktagcampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })

    });


     $("#smscampaf").validate({     
      rules: {                
          CampaignssmsTo: "required",
          CampaignssmsMessage: "required",
          
        },
    messages: 
        {             
            CampaignssmsTo: "Please enter SMS to Number",
            CampaignssmsMessage: "Please enter sms message",
          
            
        },

             ignore: ":hidden:not(textarea)",
            errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "CampaignssmsMessage") {
                    element.parent().append( label );
                } else {
                     label.insertAfter( element );
                }
            },
    });

$('#smscampa').on('click',function(e){
        
         var validationCall8=$("#smscampaf").valid();
        if(validationCall8==true)
        {

        var data = $("#smscampaf").serialize();
        $.ajax({
            dataType:"json",
            type:"post",
            data:data,
            url:'?SmsCampaigns',
            success: function(data)
            {
            if(data)
            {

                 $( '#smscampaf' ).each(function(){
                                    this.reset();
                                });
                 jQuery(".ChildModal").css({"display":"none"});
            }
             else if(data.error)
             {
             alert('ok');
             }
             }
             })
    }
    });

$("#WDuration").change(function(){
   var WDurationval = $(this).val();
  if(WDurationval<=1)
  {
    $('#DurationType').html(""); 
     $('#DurationType').append("<option value='Day'> Day </option>"); 
     $('#DurationType').append("<option value='Minutes'> Minute </option>"); 
     $('#DurationType').append("<option value='Hours'> Hour </option>"); 
  }
  else
  {
   $('#DurationType').html(""); 
     $('#DurationType').append("<option value='Day'> Days </option>"); 
     $('#DurationType').append("<option value='Minutes'> Minutes </option>"); 
     $('#DurationType').append("<option value='Hours'> Hours </option>"); 
  }
});

$("#Maxwaittimeclick").change(function(){
   var Maxwaittimeclickval = $(this).val();
  if(Maxwaittimeclickval<=1)
  {
    $('#clickDurationType').html(""); 
     $('#clickDurationType').append("<option value='Day'> Day </option>"); 
     $('#clickDurationType').append("<option value='Minutes'> Minute </option>"); 
     $('#clickDurationType').append("<option value='Hours'> Hour </option>"); 
  }
  else
  {
   $('#clickDurationType').html(""); 
     $('#clickDurationType').append("<option value='Day'> Days </option>"); 
     $('#clickDurationType').append("<option value='Minutes'> Minutes </option>"); 
     $('#clickDurationType').append("<option value='Hours'> Hours </option>"); 
  }
});
     
 $("#Maxwaittimeopen").change(function(){
   var Maxwaittimeopenval = $(this).val();
  if(Maxwaittimeopenval<=1)
  {
    $('#OpenDurationType').html(""); 
     $('#OpenDurationType').append("<option value='Day'> Day </option>"); 
     $('#OpenDurationType').append("<option value='Minutes'> Minute </option>"); 
     $('#OpenDurationType').append("<option value='Hours'> Hour </option>"); 
  }
  else
  {
   $('#OpenDurationType').html(""); 
     $('#OpenDurationType').append("<option value='Day'> Days </option>"); 
     $('#OpenDurationType').append("<option value='Minutes'> Minutes </option>"); 
     $('#OpenDurationType').append("<option value='Hours'> Hours </option>"); 
  }
});     


    $('#ctaskdec').wysihtml5();
    $('#camnotedec').wysihtml5();
    $('#ctaskduedate').bootstrapMaterialDatePicker({format : 'DD-MM-YYYY',minDate : new Date(), weekStart : 0, time: false });
    jQuery(".CM-close").click(function(){
    jQuery(".ChildModal").css({"display":"none"});
  });

           
     $('.deleteTag').on('click',function(e){
     e.preventDefault();
     $(this).closest(".taglist").fadeOut(300);
    var tagdid = $(this).attr("data-id");
    var noteid = "<?=isset($_GET['id']) ? $_GET['id'] : "" ?>";
    $.ajax({
        dataType:"json",
        type:"post",
        data: {'tagdid':tagdid,'taididd':noteid},
        url:'?action=editfile',
        success: function(data)
        {
          
        }
        })
}); 

        $('#Templeate').on('change',function(){
                                    $(".Loader").show();
                                    tid=$(this).val();
                                    $.ajax({
                                        dataType:"json",
                                        type:"post",
                                        data: {
                                            'tid':tid}
                                        ,
                                        url:'?action=editfile',
                                        success: function(data)
                                        {
                                            if(data)
                                            {
                                                $(".Loader").hide();
                                                $('#CampaignsSubject').val(data.resonse.Subject);
                                                // $("textarea").val(data.resonse.TextMassage);       
                                          $('iframe').contents().find('.CampaignsMessage').append('<br><br>&nbsp&nbsp&nbsp&nbsp  '+data.resonse.TextMassage);
                                            }
                                            else if(data.error)
                                            {
                                                alert('ok');
                                            }
                                        }
                                    })
                                });

        $("#add_client_cam").click(function(event){
          event.preventDefault();

               // $("#NewCategory").validate({
               //  rules: {
               //      Campaigns: {
               //          required: true,}
               //  }
               //  ,
               //  messages: {
               //      Campaigns: {
               //          required: "Please Enter Campaigns"}
               //      ,
               //  }
               //  ,
               //  submitHandler: function() {
                    $(".Loader").show();
                    var data = $("#NewCategory").serialize();
                    data= data + "&Action=Campaigns";
                    jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:data,
                        url:'<?php echo EXEC; ?>Exec_Edit_CampaignsTemp.php',
                        success: function(data)
                        {
                            if(data.resonse)
                            {
                                $("#resonse").show();
                                $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                $( '#NewCategory' ).each(function(){
                                    this.reset();
                                });
                                $(".Loader").hide();
                                $("#myModal_map2").modal("hide");
                                setTimeout(function () {window.location.href = "AllCamTemp.php";}, 3000) ;
                              
                            }
                            else if(data.error)
                            {
                                $("#error").show();
                                $('#errormsg').html('<span>'+data.error+'</span>');
                                $(".Loader").hide();
                                $("#myModal_map2").modal("hide");
                                // alert('<li>'+data.error+'</li>');
                            }
                            else if(data.csrf_error)
                {
                  
                    $("#csrf_error").show();
                    $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    $("#myModal_map2").modal("hide");
                    setTimeout(function () { window.location.reload() }, 2000)
                }
                        }
            //         });
            // }
            });
            });


          
        // Switchery
         $(".listofclientdiv").hide();
         var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
         $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
         });
        // For select 2
         $(".select2").select2();
       //  $('.selectpicker').selectpicker();
        //Bootstrap-select2
         $(".vertical-spin").select2({
            verticalbuttons: true,
            verticalupclass: 'ti-plus',
            verticaldownclass: 'ti-minus'
         });
         var vspinTrue = $(".vertical-spin").select2({
            verticalbuttons: true
         });
         if (vspinTrue) {
            $('.vertical-spin').prev('.bootstrap-select2-prefix').remove();
         }
         $("input[name='tch1']").select2({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
         });
         $("input[name='tch2']").select2({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
         });
         $("input[name='tch3']").select2();
         $("input[name='tch3_22']").select2({
            initval: 40
         });
         $("input[name='tch5']").select2({
            prefix: "pre",
            postfix: "post"
         });
         // For select2
          $('#pre-selected-options').select2();
         $('#optgroup').select2({
            selectableOptgroup: true
         });
         $('#public-methods').select2();
         $('#select-all').click(function() {
            $('#public-methods').select2('select_all');
            return false;
         });
         $('#deselect-all').click(function() {
            $('#public-methods').select2('deselect_all');
            return false;
         });
         $('#refresh').on('click', function() {
            $('#public-methods').select2('refresh');
            return false;
         });
         $('#add-option').on('click', function() {
            $('#public-methods').select2('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
         });
         $(".ajax").select2({
            ajax: {
               url: "https://api.github.com/search/repositories",
               dataType: 'json',
               delay: 250,
               data: function(params) {
                  return {
                        q: params.term, // search term
                        page: params.page
                  };
               },
               processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                     params.page = params.page || 1;
                     return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                     };
               },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
           // templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

//   $('#Campaigns').on('keypress', function (event) {
//   var regex = new RegExp("^[a-zA-Z0-9]+$");
//   var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
//   if (!regex.test(key)) {
//      event.preventDefault();
//      return false;
//   }
// });   

    });

</script>
<script id="code">
  var myDiagram = null;
  var myDiagram2 = null;
 function init() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates

    myDiagram =
      $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
        {
          initialContentAlignment: go.Spot.Top,
          allowDrop: true,  // must be true to accept drops from the Palette
          "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
          "LinkRelinked": showLinkLabel,
          
          "undoManager.isEnabled": true  // enable undo & redo
        });

      // This is the actual HTML context menu:
  var cxElement = document.getElementById("contextMenu");

  // Since we have only one main element, we don't have to declare a hide method,
  // we can set mainElement and GoJS will hide it automatically
  var myContextMenu = $(go.HTMLInfo, {
    show: showContextMenu,
    mainElement: cxElement
  });


 myDiagram.addDiagramListener("ObjectDoubleClicked",
      function(e) {
        var part = e.subject.part;
        
        finalnodecategory=part.Wd.category;
        finalnodekey=part.Wd.key;
        // console.log(finalnodekey);
        
        if(finalnodecategory=='Mail')
        {
          OpenModal('myModal',finalnodekey)
        }
        else if(finalnodecategory=='Task')
        {
          OpenModal('myModal2',finalnodekey)
        }
        else if(finalnodecategory=='Tags')
        {
          OpenModal('myModal3',finalnodekey)
        }
        else if(finalnodecategory=='Note')
        {
        OpenModal('myModal4',finalnodekey)
        }
        else if(finalnodecategory=='Wait')
        {
           OpenModal('myModal5',finalnodekey)
        }
        else if(finalnodecategory=='Open?')
        {
            OpenModal('myModal6',finalnodekey)
        }
        else if(finalnodecategory=='Click?')
        {
        OpenModal('myModal7',finalnodekey)
        }

        else if(finalnodecategory=='Check Tags')
        {
        OpenModal('myModal8',finalnodekey)
        }
        else if(finalnodecategory=='Sms')
        {
        OpenModal('myModal9',finalnodekey)
        }

        

        // if (!(part instanceof go.Link)) showMessage("Clicked on " + part.data.key);
      });

    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagram.addDiagramListener("Modified", function(e) {
      var button = document.getElementById("SaveButton");
      if (button) button.disabled = !myDiagram.isModified;
      var idx = document.title.indexOf("*");
      if (myDiagram.isModified) {
        if (idx < 0) document.title += "*";
      } else {
        if (idx >= 0) document.title = document.title.substr(0, idx);
      }
    });

    // helper definitions for node templates

    function nodeStyle() {
      return [
        // The Node.location comes from the "loc" property of the node data,
        // converted by the Point.parse static method.
        // If the Node.location is changed, it updates the "loc" property of the node data,
        // converting back using the Point.stringify static method.
        new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
        {
          // the Node.location is at the center of each node
          locationSpot: go.Spot.Center
        }
      ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId,
    // the "align" is used to determine where to position the port relative to the body of the node,
    // the "spot" is used to control how links connect with the port and whether the port
    // stretches along the side of the node,
    // and the boolean "output" and "input" arguments control whether the user can draw links from or to the port.
    function makePort(name, align, spot, output, input) {
      var horizontal = align.equals(go.Spot.Top) || align.equals(go.Spot.Bottom);
      // the port is basically just a transparent rectangle that stretches along the side of the node,
      // and becomes colored when the mouse passes over it
      return $(go.Shape,
        {
          fill: "transparent",  // changed to a color in the mouseEnter event handler
          strokeWidth: 0,  // no stroke
          width: horizontal ? NaN : 8,  // if not stretching horizontally, just 8 wide
          height: !horizontal ? NaN : 8,  // if not stretching vertically, just 8 tall
          alignment: align,  // align the port on the main Shape
          stretch: (horizontal ? go.GraphObject.Horizontal : go.GraphObject.Vertical),
          portId: name,  // declare this object to be a "port"
          fromSpot: spot,  // declare where links may connect at this port
          fromLinkable: output,  // declare whether the user may draw links from here
          toSpot: spot,  // declare where links may connect at this port
          toLinkable: input,  // declare whether the user may draw links to here
          cursor: "pointer",  // show a different cursor to indicate potential link point
          mouseEnter: function(e, port) {  // the PORT argument will be this Shape
            if (!e.diagram.isReadOnly) port.fill = "rgba(255,0,255,0.5)";
          },
          mouseLeave: function(e, port) {
            port.fill = "transparent";
          }
        });
    }

    function textStyle() {
      return {
        font: "bold 9pt Helvetica, Arial, sans-serif",
        stroke: "whitesmoke"
      }
    }

    // define the Node templates for regular nodes

    myDiagram.nodeTemplateMap.add("",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(160, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));


    myDiagram.nodeTemplateMap.add("Mail ",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));

       myDiagram.nodeTemplateMap.add("Wait",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));


              myDiagram.nodeTemplateMap.add("Sms",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));



               myDiagram.nodeTemplateMap.add("Click?",
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(39,39),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
      ));


        myDiagram.nodeTemplateMap.add("Open?",
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(39,39),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
      ));

                myDiagram.nodeTemplateMap.add("Check Tags",
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(39,39),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, go.Spot.Left, true, true),
        makePort("R", go.Spot.Right, go.Spot.Right, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
      ));

           myDiagram.nodeTemplateMap.add("Tags",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));

      myDiagram.nodeTemplateMap.add("Note",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      )); 

            myDiagram.nodeTemplateMap.add("Task",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(60, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));  

    myDiagram.nodeTemplateMap.add("Start",
      $(go.Node, "Table", nodeStyle(),{ deletable: false },
        $(go.Panel, "Auto",

          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 40), fill: "#79C900", strokeWidth: 0 }),
          $(go.TextBlock, "Start", textStyle(),
            new go.Binding("text"))
        ),
        // three named ports, one on each side except the top, all output only:
        makePort("L", go.Spot.Left, go.Spot.Left, true, false),
        makePort("R", go.Spot.Right, go.Spot.Right, true, false),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
      ));

    myDiagram.nodeTemplateMap.add("End",
      $(go.Node, "Table", nodeStyle(),
        $(go.Panel, "Auto",
          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 40), fill: "#DC3C00", strokeWidth: 0 }),
          $(go.TextBlock, "End", textStyle(),
            new go.Binding("text"))
        ),
        // three named ports, one on each side except the bottom, all input only:
        makePort("T", go.Spot.Top, go.Spot.Top, false, true),
        makePort("L", go.Spot.Left, go.Spot.Left, false, true),
        makePort("R", go.Spot.Right, go.Spot.Right, false, true)
      ));

    myDiagram.nodeTemplateMap.add("Comment",
      $(go.Node, "Auto", nodeStyle(),
        $(go.Shape, "File",
          { fill: "#EFFAB4", strokeWidth: 0 }),
        $(go.TextBlock, textStyle(),
          {
            margin: 5,
            maxSize: new go.Size(200, NaN),
            wrap: go.TextBlock.WrapFit,
            textAlign: "center",
            editable: true,
            font: "bold 9pt Helvetica, Arial, sans-serif",
            stroke: '#454545'
          },
          new go.Binding("text").makeTwoWay())
        // no ports, because no links are allowed to connect with a comment
      ));


    // replace the default Link template in the linkTemplateMap
    myDiagram.linkTemplate =
      $(go.Link,  // the whole link panel
        {
          routing: go.Link.AvoidsNodes,
          curve: go.Link.JumpOver,
          corner: 5, toShortLength: 4,
          relinkableFrom: true,
          relinkableTo: true,
          reshapable: true,
          resegmentable: true,
          // mouse-overs subtly highlight links:
          mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
          mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; },
          selectionAdorned: false
        },
        new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the highlight shape, normally transparent
          { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
        $(go.Shape,  // the link path shape
          { isPanelMain: true, stroke: "gray", strokeWidth: 2 },
          new go.Binding("stroke", "isSelected", function(sel) { return sel ? "dodgerblue" : "gray"; }).ofObject()),
        $(go.Shape,  // the arrowhead
          { toArrow: "standard", strokeWidth: 0, fill: "gray"}),
        $(go.Panel, "Auto",  // the link label, normally not visible
          { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
          new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#F8F8F8", strokeWidth: 0 }),
          $(go.TextBlock, "Yes",  // the label
            {
              textAlign: "center",
              font: "9pt helvetica, arial, sans-serif",
              stroke: "#333333",
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        )
      );

    // Make link labels visible if coming out of a "conditional" node.
    // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.

    function showLinkLabel(e) {
     var label = e.subject.findObject("LABEL");
     if (label !== null)
       var val = false;
       var cat = e.subject.fromNode.data.category;
       if( cat === "Click?")
       {
           val =true;
       }else if(cat =="Open?"){
           val =true;
       }
       else if(cat =="Check Tags"){
           val =true;
       }
       label.visible = val;
   }
   
    // function showLinkLabel(e) {
    //   var label = e.subject.findObject("LABEL");
    //   if (label !== null) label.visible = (e.subject.fromNode.data.category === "Click?");
    // }

    // function showLinkLabel(e) {
    //   var label = e.subject.findObject("LABEL");
    //   if (label !== null) label.visible = (e.subject.fromNode.data.category === "Open?");
    // }

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    load();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    myPalette =
      $(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
        {
          
          nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
          model: new go.GraphLinksModel([  // specify the contents of the Palette
            // { category: "Start", text: "Start" },
             { category: "Mail", text: "Mail" },
             { category: "Wait", text: "Wait"},
             { category: "Click?", text: "Click?" },
             { category: "Open?", text: "Open?" },
             { category: "Tags", text: "Tags" },
             { category: "Task", text: "Task" },
             { category: "Note", text: "Note" },
             { category: "Check Tags", text: "Check Tags" },
             { category: "Sms", text: "Sms"},


             
          ])
        });

      myDiagram.contextMenu = myContextMenu;

  // We don't want the div acting as a context menu to have a (browser) context menu!
  cxElement.addEventListener("contextmenu", function(e) {
    e.preventDefault();
    return false;
  }, false);

  function showContextMenu(obj, diagram, tool) {
    
    // Show only the relevant buttons given the current state.
    var cmd = diagram.commandHandler;
    // document.getElementById("cut").style.display = cmd.canCutSelection() ? "block" : "none";
    // document.getElementById("copy").style.display = cmd.canCopySelection() ? "block" : "none";
    // document.getElementById("paste").style.display = cmd.canPasteSelection() ? "block" : "none";
    document.getElementById("delete").style.display = cmd.canDeleteSelection() ? "block" : "none";
    // document.getElementById("color").style.display = (obj !== null ? "block" : "none");

    // Now show the whole context menu element
    cxElement.style.display = "block";
    // we don't bother overriding positionContextMenu, we just do it here:
    var mousePt = diagram.lastInput.viewPoint;
    cxElement.style.left = mousePt.x + "px";
    cxElement.style.top = mousePt.y + "px";
  }
  } // end init

function init2() {
    if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
    var $ = go.GraphObject.make;  // for conciseness in defining templates

    myDiagram2 =
      $(go.Diagram, "myDiagramDiv2",  // must name or refer to the DIV HTML element
        {
          initialContentAlignment: go.Spot.Top,
          allowDrop: true,  // must be true to accept drops from the Palette
          "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
          "LinkRelinked": showLinkLabel,
          
          "undoManager.isEnabled": true  // enable undo & redo
        });

      // This is the actual HTML context menu:
  var cxElement = document.getElementById("contextMenu");

  // Since we have only one main element, we don't have to declare a hide method,
  // we can set mainElement and GoJS will hide it automatically
  var myContextMenu = $(go.HTMLInfo, {
    show: showContextMenu,
    mainElement: cxElement
  });


    // when the document is modified, add a "*" to the title and enable the "Save" button
    myDiagram.addDiagramListener("Modified", function(e) {
      var button = document.getElementById("SaveButton");
      if (button) button.disabled = !myDiagram.isModified;
      var idx = document.title.indexOf("*");
      if (myDiagram.isModified) {
        if (idx < 0) document.title += "*";
      } else {
        if (idx >= 0) document.title = document.title.substr(0, idx);
      }
    });

    // helper definitions for node templates

    function nodeStyle() {
      return [
        // The Node.location comes from the "loc" property of the node data,
        // converted by the Point.parse static method.
        // If the Node.location is changed, it updates the "loc" property of the node data,
        // converting back using the Point.stringify static method.
        new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
        {
          // the Node.location is at the center of each node
          locationSpot: go.Spot.Center
        }
      ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId,
    // the "align" is used to determine where to position the port relative to the body of the node,
    // the "spot" is used to control how links connect with the port and whether the port
    // stretches along the side of the node,
    // and the boolean "output" and "input" arguments control whether the user can draw links from or to the port.
    function makePort(name, align, spot, output, input) {
      var horizontal = align.equals(go.Spot.Top) || align.equals(go.Spot.Bottom);
      // the port is basically just a transparent rectangle that stretches along the side of the node,
      // and becomes colored when the mouse passes over it
      return $(go.Shape,
        {
          fill: "transparent",  // changed to a color in the mouseEnter event handler
          strokeWidth: 0,  // no stroke
          width: horizontal ? NaN : 8,  // if not stretching horizontally, just 8 wide
          height: !horizontal ? NaN : 8,  // if not stretching vertically, just 8 tall
          alignment: align,  // align the port on the main Shape
          stretch: (horizontal ? go.GraphObject.Horizontal : go.GraphObject.Vertical),
          portId: name,  // declare this object to be a "port"
          fromSpot: spot,  // declare where links may connect at this port
          fromLinkable: output,  // declare whether the user may draw links from here
          toSpot: spot,  // declare where links may connect at this port
          toLinkable: input,  // declare whether the user may draw links to here
          cursor: "pointer",  // show a different cursor to indicate potential link point
          mouseEnter: function(e, port) {  // the PORT argument will be this Shape
            if (!e.diagram.isReadOnly) port.fill = "rgba(255,0,255,0.5)";
          },
          mouseLeave: function(e, port) {
            port.fill = "transparent";
          }
        });
    }

    function textStyle() {
      return {
        font: "bold 9pt Helvetica, Arial, sans-serif",
        stroke: "whitesmoke"
      }
    }

    // define the Node templates for regular nodes

    myDiagram2.nodeTemplateMap.add("",  // the default category
      $(go.Node, "Table", nodeStyle(),
        // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
        $(go.Panel, "Auto",
          { contextMenu: myContextMenu },
          $(go.Shape, "Circle",
            { fill: "#00A9C9", strokeWidth: 0 },
            new go.Binding("figure", "figure")),
          $(go.TextBlock, textStyle(),
            {
              margin: 8,
              maxSize: new go.Size(160, NaN),
              wrap: go.TextBlock.WrapFit,
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        ),
        // four named ports, one on each side:
        makePort("T", go.Spot.Top, go.Spot.TopSide, false, true),
        makePort("L", go.Spot.Left, go.Spot.LeftSide, true, true),
        makePort("R", go.Spot.Right, go.Spot.RightSide, true, true),
        makePort("B", go.Spot.Bottom, go.Spot.BottomSide, true, false)
      ));


    myDiagram2.nodeTemplateMap.add("Start",
      $(go.Node, "Table", nodeStyle(),{ deletable: false },
        $(go.Panel, "Auto",

          $(go.Shape, "Circle",
            { minSize: new go.Size(40, 40), fill: "#79C900", strokeWidth: 0 }),
          $(go.TextBlock, "Start", textStyle(),
            new go.Binding("text"))
        ),
        // three named ports, one on each side except the top, all output only:
        makePort("L", go.Spot.Left, go.Spot.Left, true, false),
        makePort("R", go.Spot.Right, go.Spot.Right, true, false),
        makePort("B", go.Spot.Bottom, go.Spot.Bottom, true, false)
      ));

    

    // replace the default Link template in the linkTemplateMap
    myDiagram2.linkTemplate =
      $(go.Link,  // the whole link panel
        {
          routing: go.Link.AvoidsNodes,
          curve: go.Link.JumpOver,
          corner: 5, toShortLength: 4,
          relinkableFrom: true,
          relinkableTo: true,
          reshapable: true,
          resegmentable: true,
          // mouse-overs subtly highlight links:
          mouseEnter: function(e, link) { link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)"; },
          mouseLeave: function(e, link) { link.findObject("HIGHLIGHT").stroke = "transparent"; },
          selectionAdorned: false
        },
        new go.Binding("points").makeTwoWay(),
        $(go.Shape,  // the highlight shape, normally transparent
          { isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT" }),
        $(go.Shape,  // the link path shape
          { isPanelMain: true, stroke: "gray", strokeWidth: 2 },
          new go.Binding("stroke", "isSelected", function(sel) { return sel ? "dodgerblue" : "gray"; }).ofObject()),
        $(go.Shape,  // the arrowhead
          { toArrow: "standard", strokeWidth: 0, fill: "gray"}),
        $(go.Panel, "Auto",  // the link label, normally not visible
          { visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
          new go.Binding("visible", "visible").makeTwoWay(),
          $(go.Shape, "RoundedRectangle",  // the label shape
            { fill: "#F8F8F8", strokeWidth: 0 }),
          $(go.TextBlock, "Yes",  // the label
            {
              textAlign: "center",
              font: "9pt helvetica, arial, sans-serif",
              stroke: "#333333",
              editable: true
            },
            new go.Binding("text").makeTwoWay())
        )
      );

    // Make link labels visible if coming out of a "conditional" node.
    // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.

    function showLinkLabel(e) {
     var label = e.subject.findObject("LABEL");
     if (label !== null)
       var val = false;
       var cat = e.subject.fromNode.data.category;
       if( cat === "Click?")
       {
           val =true;
       }else if(cat =="Open?"){
           val =true;
       }
       else if(cat =="Check Tags"){
           val =true;
       }
       label.visible = val;
   }
   
    // function showLinkLabel(e) {
    //   var label = e.subject.findObject("LABEL");
    //   if (label !== null) label.visible = (e.subject.fromNode.data.category === "Click?");
    // }

    // function showLinkLabel(e) {
    //   var label = e.subject.findObject("LABEL");
    //   if (label !== null) label.visible = (e.subject.fromNode.data.category === "Open?");
    // }

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    load2();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    // myPalette =
    //   $(go.Palette, "myPaletteDiv2",  // must name or refer to the DIV HTML element
    //     {
          
    //       nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
    //       model: new go.GraphLinksModel([  // specify the contents of the Palette
    //         // { category: "Start", text: "Start" },
    //          { category: "Mail", text: "Mail" },
    //          { category: "Wait", text: "Wait"},
    //          { category: "Click?", text: "Click?" },
    //          { category: "Open?", text: "Open?" },
    //          { category: "Tags", text: "Tags" },
    //          { category: "Task", text: "Task" },
    //          { category: "Note", text: "Note" },
    //          { category: "Check Tags", text: "Check Tags" },
    //          { category: "Sms", text: "Sms"},


             
    //       ])
    //     });

      myDiagram.contextMenu = myContextMenu;

  // We don't want the div acting as a context menu to have a (browser) context menu!
  cxElement.addEventListener("contextmenu", function(e) {
    e.preventDefault();
    return false;
  }, false);

  function showContextMenu(obj, diagram, tool) {
    
    // Show only the relevant buttons given the current state.
    var cmd = diagram.commandHandler;
    // document.getElementById("cut").style.display = cmd.canCutSelection() ? "block" : "none";
    // document.getElementById("copy").style.display = cmd.canCopySelection() ? "block" : "none";
    // document.getElementById("paste").style.display = cmd.canPasteSelection() ? "block" : "none";
    document.getElementById("delete").style.display = cmd.canDeleteSelection() ? "block" : "none";
    // document.getElementById("color").style.display = (obj !== null ? "block" : "none");

    // Now show the whole context menu element
    cxElement.style.display = "block";
    // we don't bother overriding positionContextMenu, we just do it here:
    var mousePt = diagram.lastInput.viewPoint;
    cxElement.style.left = mousePt.x + "px";
    cxElement.style.top = mousePt.y + "px";
  }
  } // end init2
function cxcommand(event, val) {
  if (val === undefined) val = event.currentTarget.id;
  var diagram = myDiagram;
  switch (val) {
    // case "cut": diagram.commandHandler.cutSelection(); break;
    // case "copy": diagram.commandHandler.copySelection(); break;
    // case "paste": diagram.commandHandler.pasteSelection(diagram.lastInput.documentPoint); break;
    case "delete": diagram.commandHandler.deleteSelection(); break;
    // case "color": {
    //     var color = window.getComputedStyle(document.elementFromPoint(event.clientX, event.clientY).parentElement)['background-color'];
    //     changeColor(diagram, color); break;
    // }
  }
  diagram.currentTool.stopTool();
}

// A custom command, for changing the color of the selected node(s).
function changeColor(diagram, color) {
  // Always make changes in a transaction, except when initializing the diagram.
  diagram.startTransaction("change color");
  diagram.selection.each(function(node) {
    if (node instanceof go.Node) {  // ignore any selected Links and simple Parts
        // Examine and modify the data, not the Node directly.
        var data = node.data;
        // Call setDataProperty to support undo/redo as well as
        // automatically evaluating any relevant bindings.
        diagram.model.setDataProperty(data, "color", color);
    }
  });
  diagram.commitTransaction("change color");
}


  // Show the diagram's model in JSON format that the user may edit
  function save() { 
    document.getElementById("mySavedModel").value = myDiagram.model.toJson();
    myDiagram.isModified = false;
  }
  function save2() { 
    document.getElementById("mySavedModel2").value = myDiagram.model.toJson();
    myDiagram2.isModified = false;
  }
  function load() {
    myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
  }

  function load2() {
    myDiagram2.model = go.Model.fromJson(document.getElementById("mySavedModel2").value);
  }


  // print the diagram by opening a new window holding SVG images of the diagram contents for each page
  function printDiagram() {
    var svgWindow = window.open();
    if (!svgWindow) return;  // failure to open a new Window
    var printSize = new go.Size(700, 960);
    var bnds = myDiagram.documentBounds;
    var x = bnds.x;
    var y = bnds.y;
    while (y < bnds.bottom) {
      while (x < bnds.right) {
        var svg = myDiagram.makeSVG({ scale: 1.0, position: new go.Point(x, y), size: printSize });
        svgWindow.document.body.appendChild(svg);
        x += printSize.width;
      }
      x = bnds.x;
      y += printSize.height;
    }
    setTimeout(function() { svgWindow.print(); }, 1);
  }


  $("#add-client").click(function(event){
               event.preventDefault();
               var CampaignsName = $("#Campaigns").val()
               if(CampaignsName=='')
               {
                $('#CampaignsNameError').text('Please enter campaigns name');
               }
               else
               {
                $('#CampaignsNameError').hide();
               $('#newcart').html('');
               var newcal= $('#mySavedModel').val();
               console.log(newcal)
               $(".Loader").show();
                //myDiagram2.div = null;
                  $('#newcart').append('<div id="sample"><div style="width:100%; white-space:nowrap;"><span style="display: inline-block; vertical-align: top; width:100px"></span><span class="medan" style="display: block; vertical-align: top; width:100%; position: relative;"><div id="myDiagramDiv2" style="border: solid 1px black; height: 720px"></div></span></div></div></div><textarea hidden  id="mySavedModel2" style="width:100%;height:300px">'+newcal+'</textarea>');
                  init2();  
               $("#myModal_map2").modal("show");
              $(".Loader").hide();
            }
          });

</script>

     <script src="<?= base_url?>/assets/js/tokenize2.js"></script>
     <script>
            $('.tokenize-sample-demo1').tokenize2();
            $('.tokenize-remote-demo1, .tokenize-remote-modal').tokenize2({
                dataSource: 'remote.php'
            });
            $('.tokenize-limit-demo1').tokenize2({
                tokensMaxItems: 5
            });
            $('.tokenize-limit-demo2').tokenize2({
                tokensMaxItems: 1
            });
            $('.tokenize-ph-demo1').tokenize2({
                placeholder: 'Please add new tokens'
            });
            $('.tokenize-sortable-demo1').tokenize2({
                sortable: true
            });
            $('.tokenize-custom-demo1').tokenize2({
                tokensAllowCustom: true
            });
            $('.tokenize-callable-demo1').tokenize2({
                dataSource: function(search, object){
                    $.ajax('remote.php', {
                        data: { search: search, start: 1 },
                        dataType: 'json',
                        success: function(data){
                            var $items = [];
                            $.each(data, function(k, v){
                                $items.push(v);
                            });
                            object.trigger('tokenize:dropdown:fill', [$items]);
                        }
                    });
                }
            });
            $('.tokenize-override-demo1').tokenize2();
            $.extend($('.tokenize-override-demo1').tokenize2(), {
                dropdownItemFormat: function(v){
                    return $('<a />').html(v.text + ' override').attr({
                        'data-value': v.value,
                        'data-text': v.text
                    })
                }
            });
            $('#btnClear').on('mousedown touchstart', function(e){
                e.preventDefault();
                $('.tokenize-demo1, .tokenize-demo2, .tokenize-demo3').tokenize2().trigger('tokenize:clear');
            });


        </script>

<script>
// Warning before leaving the page (back button, or outgoinglink)
// window.onbeforeunload = function() {

//    return "Do you really want to leave our brilliant application?";
//    //if we return nothing here (just calling return;) then there will be no pop-up question at all
//    //return;
// };
</script> 

 
<!-- <a href="http://code-maven.com/">Code Maven</a> -->
 

</body>
</html>
