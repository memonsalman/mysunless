<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class  AllMail{
    public $id;
    public $FromE;
    public $ToE;
    public $Subject;
    public $MessageE;
    public $UserId;
    public $ccid;
    public function __construct($myMail = "new"){
        $this->id = $myMail;
        $this->FromE = "";
        $this->ToE = "";
        $this->Subject = "";
        $this->MessageE = "";
        $this->UserId = "";
        $this->ccid = "";
        if ($myMail == "new") {
            $this->sendtime = date("Y-m-d H:i:s");
        }
        else {
            try {
                $db = new db();
                $query = $db->prepare("SELECT * FROM `emailsend` WHERE userid=:UserId");
                $query->bindValue(':UserId', $UserId, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->FromE = $info["FromE"];
                $this->ToE = $info["ToE"];
                $this->Subject = $info["Subject"];
                $this->MessageE = $info["MessageE"];
                $this->sendtime = $info["sendtime"];
                $this->ccid = $info["ccid"];
            }
        }
    }
    public function commit(){

        try {
            $db = new db();
            $query = $db->prepare("INSERT INTO `emailsend`(`FromE`,`ToE`,`Subject`,`MessageE`,`userid`,`ccid`) VALUES (:FromE , :ToE , :Subject , :MessageE , :UserId, :ccid)");
            $query->bindParam(':FromE', $this->FromE, PDO::PARAM_INT);
            $query->bindParam(':ToE', $this->ToE, PDO::PARAM_INT);
            $query->bindParam(':Subject', $this->Subject, PDO::PARAM_INT);
            $query->bindParam(':MessageE', $this->MessageE, PDO::PARAM_INT);
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
        
        $Createid=$_SESSION['UserID'];
        $comtime = date("Y-m-d H:i:s");
        // $cid = base64_decode($_POST['ccid']);
        // $type = $_POST['type'];	
        // $message=$_POST['Message'];
        // $Subject=$_POST['Subject'];
         $cid = $this->ccid;
        $type = $this->type; 
        $message=$this->MessageE;
        $Subject=$this->Subject;
        $db = new db();
        $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,subject,cid,Createid,comtime) VALUES(:type,:message,:Subject,:cid,:Createid,:comtime)");
        $insert_data_fc->bindparam(":type",$type);
        $insert_data_fc->bindparam(":message",$message);
        $insert_data_fc->bindparam(":Subject",$Subject);
        $insert_data_fc->bindparam(":cid", $cid);
        $insert_data_fc->bindparam(":Createid",$Createid);
        $insert_data_fc->bindparam(":comtime",$comtime);
        $insert_data_fc->execute();				
    }
    public function ActivitesCount($newmail)
    {
        $EmailCreate=$newmail;
        $Createid=$_SESSION['UserID'];
        $CreatedTime=date("Y-m-d");
        $db = new db();
        $insert_data_ac=$db->prepare("INSERT INTO CountActivites(EmailCreate,Createid,CreatedTime) VALUES(:EmailCreate,:Createid,:CreatedTime)");
        $insert_data_ac->bindparam(":EmailCreate",$EmailCreate);
        $insert_data_ac->bindparam(":Createid",$Createid);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->execute();
    }
}
?>