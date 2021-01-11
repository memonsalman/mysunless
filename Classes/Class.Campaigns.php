<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Campaigns{
    public $id;
    public $Campaigns;
    public $CampaignsCategory;
    public $CampaignsFromName;
    public $CampaignsFrom;
    public $CampaignsTo;
    public $Campaignscc;
    public $Campaignsbcc;
    public $CampaignsSubject;
    public $CampaignsMessage;
    public $TrackOpens;
    public $TrackClicks;
    public $SendCampaignsTimezone;
    public $OnDay;
    public $AtTime;
    public $flowchartdata;
    public $ctag;
    public $ctasktitle;
    public $ctaskdec;
    public $camnotetitle;
    public $camnotedec;
    public $ctaskduedate;
    
    public $Camnoteid;
    public $Camtaskid;
    public $WDuration;
    public $DurationType;
    public $Maxwaittimeclick;
    public $clickDurationType;
    public $Maxwaittimeopen;
    public $OpenDurationType;





    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->UserID = "";
        $this->Campaigns = "";
        $this->CampaignsCategory = "";
        $this->CampaignsFromName = "";
        $this->CampaignsFrom = "";
        $this->CampaignsTo = "";
        $this->Campaignscc = "";
        $this->Campaignsbcc = "";
        $this->CampaignsSubject = "";
        $this->CampaignsMessage = "";
        $this->TrackOpens = "";
        $this->TrackClicks = "";
        $this->SendCampaignsTimezone = "";
        $this->OnDay = "";
        $this->AtTime = "";
        $this->flowchartdata = "";
        $this->ctag = "";
        $this->ctasktitle = "";
        $this->ctaskdec = "";
        $this->ctaskduedate = "";
        
        $this->camnotetitle = "";
        $this->camnotedec = "";
        $this->Camnoteid = "";
        $this->Camtaskid = "";
        $this->WDuration = "";
        $this->DurationType = "";
        $this->Maxwaittimeclick = "";
        $this->clickDurationType = "";
        $this->Maxwaittimeopen = "";
        $this->OpenDurationType = "";



        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM Campaigns WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->Campaigns = $info["Campaigns"];
                $this->CampaignsCategory = $info["CampaignsCategory"];
                $this->CampaignsFromName = $info["CampaignsFromName"];
                $this->CampaignsFrom = $info["CampaignsFrom"];    
                $this->CampaignsTo = $info["CampaignsTo"];
                $this->Campaignscc = $info["Campaignscc"];
                $this->Campaignsbcc = $info["Campaignsbcc"];
                $this->CampaignsSubject = $info["CampaignsSubject"];
                $this->CampaignsMessage = $info["CampaignsMessage"];
                $this->TrackOpens = $info["TrackOpens"];
                $this->TrackClicks = $info["TrackClicks"];
                $this->SendCampaignsTimezone = $info["SendCampaignsTimezone"];
                $this->OnDay = $info["OnDay"];
                $this->AtTime = $info["AtTime"];
                $this->flowchartdata = $info["flowchartdata"];
                $this->ctag = $info["ctag"];
                $this->datecreated = $info["datecreated"];
                $this->datelastupdated = $info["datelastupdated"];
                $this->createdfk = $info["createdfk"];
                $this->updatedfk = $info["updatedfk"];
                $this->isactive = 1;
                $this->ctasktitle = $info["ctasktitle"];
                $this->ctaskdec = $info["ctaskdec"];
                $this->ctaskduedate = $info["ctaskduedate"];
                
                $this->camnotetitle = $info["camnotetitle"];
                $this->camnotedec = $info["camnotedec"];
                $this->Camnoteid = $info["Camnoteid"];
                $this->Camtaskid = $info["Camtaskid"];
                $this->WDuration = $info["WDuration"];
                $this->DurationType = $info["DurationType"];
                $this->Maxwaittimeclick = $info["Maxwaittimeclick"];
                $this->clickDurationType = $info["clickDurationType"];
                $this->Maxwaittimeopen = $info["Maxwaittimeopen"];
                $this->OpenDurationType = $info["OpenDurationType"];
                
            }
        }
    }
    public function commit(){
        $db = new db();
        if ($this->id == "new") {
            try {
                $query = $db->prepare("INSERT INTO `Campaigns` (`Campaigns`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                echo $e;
                die;

                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE Campaigns SET 
                `Campaigns`=:Campaigns,
                `CampaignsCategory`=:CampaignsCategory,
                `CampaignsFromName`=:CampaignsFromName,
                `CampaignsFrom`=:CampaignsFrom,
                `CampaignsTo`=:CampaignsTo,
                `Campaignscc`=:Campaignscc,
                `Campaignsbcc`=:Campaignsbcc,
                `CampaignsSubject`=:CampaignsSubject,
                `CampaignsMessage`=:CampaignsMessage,
                `TrackOpens`=:TrackOpens,
                `TrackClicks`=:TrackClicks,
                `SendCampaignsTimezone`=:SendCampaignsTimezone,
                `OnDay`=:OnDay,
                `AtTime`=:AtTime,
                `flowchartdata`=:flowchartdata,
                `ctag`=:ctag,
                `ctasktitle`=:ctasktitle,
                `ctaskdec`=:ctaskdec,
                `ctaskduedate`=:ctaskduedate,
                
                `camnotetitle`=:camnotetitle,
                `camnotedec`=:camnotedec,
                `Camnoteid`=:Camnoteid,
                `Camtaskid`=:Camtaskid,
                `WDuration`=:WDuration,
                `DurationType`=:DurationType,
                `Maxwaittimeclick`=:Maxwaittimeclick,
                `clickDurationType`=:clickDurationType,
                `Maxwaittimeopen`=:Maxwaittimeopen,
                `OpenDurationType`=:OpenDurationType,

                `datecreated`=:datecreated,
                `datelastupdated`=:datelastupdated,
                `createdfk`=:createdfk,
                `updatedfk`=:updatedfk,
                `isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':Campaigns', $this->Campaigns, PDO::PARAM_STR);	
            $query->bindValue(':CampaignsCategory', $this->CampaignsCategory, PDO::PARAM_STR); 
            $query->bindValue(':CampaignsFromName', $this->CampaignsFromName, PDO::PARAM_STR); 
            $query->bindValue(':CampaignsFrom', $this->CampaignsFrom, PDO::PARAM_STR); 
            $query->bindValue(':CampaignsTo', $this->CampaignsTo, PDO::PARAM_STR); 
            $query->bindValue(':Campaignscc', $this->Campaignscc, PDO::PARAM_STR); 
            $query->bindValue(':Campaignsbcc', $this->Campaignsbcc, PDO::PARAM_STR); 
            $query->bindValue(':CampaignsSubject', $this->CampaignsSubject, PDO::PARAM_STR); 
            $query->bindValue(':CampaignsMessage', $this->CampaignsMessage, PDO::PARAM_STR); 
            $query->bindValue(':TrackOpens', $this->TrackOpens, PDO::PARAM_STR); 
            $query->bindValue(':TrackClicks', $this->TrackClicks, PDO::PARAM_STR); 
            $query->bindValue(':SendCampaignsTimezone', $this->SendCampaignsTimezone, PDO::PARAM_STR); 
            $query->bindValue(':OnDay', $this->OnDay, PDO::PARAM_STR); 
            $query->bindValue(':AtTime', $this->AtTime, PDO::PARAM_STR); 
            $query->bindValue(':flowchartdata', $this->flowchartdata, PDO::PARAM_STR); 
            $query->bindValue(':ctag', $this->ctag, PDO::PARAM_STR); 
            $query->bindValue(':ctasktitle', $this->ctasktitle, PDO::PARAM_STR); 
            $query->bindValue(':ctaskdec', $this->ctaskdec, PDO::PARAM_STR); 
            $query->bindValue(':ctaskduedate', $this->ctaskduedate, PDO::PARAM_STR); 
            
            $query->bindValue(':camnotetitle', $this->camnotetitle, PDO::PARAM_STR); 
            $query->bindValue(':camnotedec', $this->camnotedec, PDO::PARAM_STR); 
            $query->bindValue(':Camnoteid', $this->Camnoteid, PDO::PARAM_STR); 
            $query->bindValue(':Camtaskid', $this->Camtaskid, PDO::PARAM_STR); 
            $query->bindValue(':WDuration', $this->WDuration, PDO::PARAM_STR); 
            $query->bindValue(':DurationType', $this->DurationType, PDO::PARAM_STR); 
            $query->bindValue(':Maxwaittimeclick', $this->Maxwaittimeclick, PDO::PARAM_STR); 
            $query->bindValue(':clickDurationType', $this->clickDurationType, PDO::PARAM_STR); 
            $query->bindValue(':Maxwaittimeopen', $this->Maxwaittimeopen, PDO::PARAM_STR); 
            $query->bindValue(':OpenDurationType', $this->OpenDurationType, PDO::PARAM_STR); 

            $query->bindValue(':datecreated', $this->datecreated, PDO::PARAM_STR);
            $query->bindValue(':datelastupdated', $this->datelastupdated, PDO::PARAM_STR);
            $query->bindValue(':createdfk', $this->createdfk, PDO::PARAM_STR);
            $query->bindValue(':updatedfk', $this->updatedfk, PDO::PARAM_STR);
            $query->bindValue(':isactive', $this->isactive, PDO::PARAM_STR);
            $query->bindValue(':myid', $this->id, PDO::PARAM_STR);
            $query->execute();
        }catch (PDOException $e) {
            echo $e->getMessage(), $query->queryString, __FILE__, __LINE__;
            exit;
        }
        //return $this->id;
        return $this->id;
    }
    public function listoffile(){
        $db= new db();
        $isactive=1;
        $id=$_SESSION['UserID'];
        $LoginQuery = $db->prepare("SELECT Campaigns.id as cid, CampaignsCategory.CampaignsCategory as CampaignsCategory, Campaigns.Campaigns as Campaigns FROM `Campaigns` Left join CampaignsCategory on Campaigns.CampaignsCategory = CampaignsCategory.id   WHERE Campaigns.isactive=:isactive AND Campaigns.createdfk=:id");
        $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
        $LoginQuery->bindParam(':isactive', $isactive, PDO::PARAM_INT);
        $LoginQuery->execute();
        $result = $LoginQuery->fetchAll();
        echo json_encode($result);				
    }


    public function listoffile2($viewfilerdata){
            

        $db= new db();
        $isactive=1;
        $id=$_SESSION['UserID'];

        if($viewfilerdata=='Uncategorized')
          {

           $LoginQuery = $db->prepare("SELECT Campaigns.id as cid, CampaignsCategory.CampaignsCategory as CampaignsCategory, Campaigns.Campaigns as Campaigns FROM `Campaigns` WHERE Campaigns.isactive=:isactive AND Campaigns.createdfk=:id ANd Campaigns.CampaignsCategory='$viewfilerdata'");   
          }
          else
          {
        $LoginQuery = $db->prepare("SELECT Campaigns.id as cid, CampaignsCategory.CampaignsCategory as CampaignsCategory, Campaigns.Campaigns as Campaigns FROM `Campaigns` Left join CampaignsCategory on Campaigns.CampaignsCategory = CampaignsCategory.id   WHERE Campaigns.isactive=:isactive AND Campaigns.createdfk=:id ANd Campaigns.CampaignsCategory=$viewfilerdata");
        }
        $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
        $LoginQuery->bindParam(':isactive', $isactive, PDO::PARAM_INT);
        $LoginQuery->execute();
        $result = $LoginQuery->fetchAll();
        echo json_encode($result);              
    }
}
?>