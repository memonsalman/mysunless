<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class Todo{
    public $colorcode;
    public $id;
    public $cid;
    public $asignto;
    public $todoTitle;
    public $todoDesc;
    public $dueDate;
    public $newduedate;
    public function __construct($myid = "new"){
        $db = new db();
        $this->colorcode = "";
        $this->id = $myid;
        $this->cid = "";
        $this->asignto = "";
        $this->todoTitle = "";
        $this->todoDesc = "";
        $this->dueDate = "";
        $this->newduedate = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM todo WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->colorcode = $info["colorcode"];
                $this->todoTitle = $info["todoTitle"];
                $this->cid = $info["catstatus"];
                $this->asignto = $info["asignto"];
                $this->todoDesc = $info["todoDesc"];
                $this->dueDate = $info["dueDate"];
                $this->newduedate = $info["newduedate"];
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
                $query = $db->prepare("INSERT INTO `todo` (`todoTitle`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
               
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE todo SET 
        `colorcode`=:colorcode,
        `catstatus`=:cid,
        `asignto`=:asignto,
        `todoTitle`=:todoTitle,
        `todoDesc`=:todoDesc,
        `dueDate`=:dueDate,
        `newduedate`=:newduedate,
        `datecreated`=:datecreated,
        `datelastupdated`=:datelastupdated,
        `createdfk`=:createdfk,
        `updatedfk`=:updatedfk,
        `isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':colorcode', $this->colorcode, PDO::PARAM_STR);
            $query->bindValue(':todoTitle', $this->todoTitle, PDO::PARAM_STR);
            $query->bindValue(':todoDesc', $this->todoDesc, PDO::PARAM_STR);
            $query->bindValue(':dueDate', $this->dueDate, PDO::PARAM_STR);
            $query->bindValue(':cid', $this->cid, PDO::PARAM_STR);
            $query->bindValue(':asignto', $this->asignto, PDO::PARAM_STR);
            $query->bindValue(':newduedate', $this->newduedate, PDO::PARAM_STR);
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
    public function ActivitesCount($newTodo)
    {
        $db = new db();
        $ClientCreate=$newTodo;
        $Createid=$_SESSION['UserID'];
        $CreatedTime=date("Y-m-d");
        $insert_data_ac=$db->prepare("INSERT INTO CountActivites(TodoCreate,Createid,CreatedTime) VALUES(:ClientCreate,:Createid,:CreatedTime)");
        $insert_data_ac->bindparam(":ClientCreate",$ClientCreate);
        $insert_data_ac->bindparam(":Createid",$Createid);
        $insert_data_ac->bindparam(":CreatedTime",$CreatedTime);
        $insert_data_ac->execute();
    }
}
?>