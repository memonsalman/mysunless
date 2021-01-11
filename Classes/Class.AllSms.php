<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');;

class  AllSms{
    public $id;
    public $FromS;
    public $ToS;
    public $Message;
    public $UserId;
    public $ccid;
    public function __construct($mySms = "new"){
        $db = new db();
        $this->id = $mySms;
        $this->FromS = "";
        $this->ToS = "";
        $this->Message = "";
        $this->UserId = "";
        $this->ccid = "";		
        if ($mySms == "new") {
            $this->sendtime = date("Y-m-d H:i:s");
        }
        else {
            try {
                $query = $db->prepare("SELECT * FROM `SmsSend` WHERE userid=:UserId");
                $query->bindValue(':UserId', $UserId, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $query = $db->prepare("INSERT INTO `SmsSend` (`FromS`,`ToS`,`Message`,`userid`,`ccid`) VALUES (:FromS , :ToS , :Message , :UserId, :ccid)");
                $this->FromS = $info["FromS"];
                $this->ToS = $info["ToS"];
                $this->Message = $info["Message"];
                $this->sendtime = $info["sendtime"];
                $this->ccid = $info["ccid"];
            }
        }
    }
    public function commit(){
        $db = new db();
        try {
            $query = $db->prepare("INSERT INTO `SmsSend` (`FromS`,`ToS`,`Message`,`userid`,`ccid`) VALUES (:FromS, :ToS , :Message , :UserId, :ccid )");
            $query->bindParam(':FromS', $this->FromS, PDO::PARAM_INT);
            $query->bindParam(':ToS', $this->ToS, PDO::PARAM_INT);
            $query->bindParam(':Message', $this->Message, PDO::PARAM_INT);
            $query->bindParam(':UserId', $this->UserId, PDO::PARAM_INT);
            $query->bindParam(':ccid', $this->ccid, PDO::PARAM_INT);
            $query->execute();
            $this->id = $db->lastInsertId();
        } catch (PDOException $e) {
            logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
            exit;
        }
        return $this->id;
    }
    public function FullCom()
    {
        $db = new db();
        $Createid=$_SESSION['UserID'];
        $comtime = date("Y-m-d H:i:s");
        $cid = $_POST['ccid'];
        $type = $_POST['type'];	
        $message=$_POST['smsMessage'];
        $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,cid,Createid,comtime) VALUES(:type,:message,:cid,:Createid,:comtime)");
        $insert_data_fc->bindparam(":type",$type);
        $insert_data_fc->bindparam(":message",$message);
        $insert_data_fc->bindparam(":cid",$cid);
        $insert_data_fc->bindparam(":Createid",$Createid);
        $insert_data_fc->bindparam(":comtime",$comtime);
        $insert_data_fc->execute();				
    }
    public function ActivitesCount($newsms)
    {

        $db = new db();
        $SmsCreate=$newsms;
        $Createid=$_SESSION['UserID'];
        $CreatedTime=date("Y-m-d");
        $insert_data_ac=$db->prepare("INSERT INTO CountActivites(SmsCreate,Createid,CreatedTime) VALUES(:SmsCreate,:Createid,:CreatedTime)");
        $insert_data_ac->bindparam(":SmsCreate",$SmsCreate);
        $insert_data_ac->bindparam(":Createid",$Createid);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->execute();
    }
}
?>