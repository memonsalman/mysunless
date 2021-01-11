<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
class Tag{
    public $id;
    public $tag;
    public $tagRelated;
    public $cid;
    public function __construct($myid = "new"){
        $db = new db();
        $this->id = $myid;
        $this->UserID = "";
        $this->tag = "";
        $this->tagRelated = "";
        $this->cid = "";
        if ($myid == "new") {
            $this->datecreated = date("Y-m-d H:i:s");
            $this->datelastupdated = date("Y-m-d H:i:s");
            $this->createdfk = $_SESSION["UserID"];
            $this->updatedfk = $_SESSION["UserID"];
            $this->isactive = 1;
        }else {
            try {
                $query = $db->prepare("SELECT * FROM tag WHERE id=:myid");
                $query->bindValue(':myid', $myid, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
            while ($info = $query->fetch(PDO::FETCH_ASSOC)) {
                $this->tag = $info["tag"];
                $this->tagRelated = $info["tagRelated"];
                $this->cid = $info["cid"];
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
                $query = $db->prepare("INSERT INTO `tag` (`tag`) VALUES ('New')");
                $query->execute();
                $this->id = $db->lastInsertId();
            } catch (PDOException $e) {
                logError($e->getMessage(), $query->queryString, __FILE__, __LINE__);
                exit;
            }
        }
        try {
            $query = $db->prepare("UPDATE tag SET 
`tag`=:tag,
`tagRelated`=:tagRelated,
`cid`=:cid,
`datecreated`=:datecreated,
`datelastupdated`=:datelastupdated,
`createdfk`=:createdfk,
`updatedfk`=:updatedfk,
`isactive`=:isactive  WHERE id=:myid");
            $query->bindValue(':tag', $this->tag, PDO::PARAM_STR);
            $query->bindValue(':tagRelated', $this->tagRelated, PDO::PARAM_STR);	
            $query->bindValue(':cid', $this->cid, PDO::PARAM_STR);	
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
}
?>