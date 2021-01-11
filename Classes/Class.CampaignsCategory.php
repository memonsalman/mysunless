<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class Category{
    public $id;
    public $CampaignsCategory;
    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->UserID = "";
        $this->CampaignsCategory = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM CampaignsCategory WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->CampaignsCategory = $info["CampaignsCategory"];
                $this->datecreated = $info["datecreated"];
                $this->datelastupdated = $info["datelastupdated"];
                $this->createdfk = $info["createdfk"];
                $this->updatedfk = $info["updatedfk"];
                $this->isactive = 1;
            }
        }
    }
    public function commit(){
        $db = new db();
        if ($this->id == "new") {
            try {
                $query = $db->prepare("INSERT INTO `CampaignsCategory` (`CampaignsCategory`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE CampaignsCategory SET 
`CampaignsCategory`=:CampaignsCategory,
`datecreated`=:datecreated,
`datelastupdated`=:datelastupdated,
`createdfk`=:createdfk,
`updatedfk`=:updatedfk,
`isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':CampaignsCategory', $this->CampaignsCategory, PDO::PARAM_STR);	
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
        $LoginQuery = $db->prepare("SELECT * FROM `CampaignsCategory` WHERE isactive=:isactive AND createdfk=:id");
        $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
        $LoginQuery->bindParam(':isactive', $isactive, PDO::PARAM_INT);
        $LoginQuery->execute();
        $result = $LoginQuery->fetchAll();
        echo json_encode($result);				
    }
}
?>