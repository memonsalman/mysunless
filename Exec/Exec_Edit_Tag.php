<?php
    require_once('Exec_Config.php');        


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Tag.php'); 

if(empty(isset($_POST['tag'])))
{
    echo json_encode(['error2'=>'Please enter tag name']);die;  
}
// view client detail page start code

if(isset($_POST['mynewcid']))
{

    foreach (@$_POST["tag"] as $rowtag)
    {
        $db2=new db();
        $tag=$rowtag;
        $stmt= $db2->prepare("SELECT * FROM `tag` WHERE tag=:tag"); 
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
        $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);

        @$oldtagformtag=$result_tag[0]['id'];
        $a=$stmt->rowCount();
        if($a>0)
        { 
            $clientid=$_POST["mynewcid"];
            $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=:clientid"); 
            $stmt->bindParam(':clientid', $clientid, PDO::PARAM_STR);
            $stmt->execute();
            $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
            $db=new db();
            array_push($oldtag,$oldtagformtag);
            $finaltaglist=array_unique($oldtag);
            $tagid=implode(',',$finaltaglist);
            $clientid=$_POST["mynewcid"];
            @$allreadytag=$_POST['allreadytag'];
            $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=:clientid");
            $insert_data->bindparam(":tagid",$tagid);
            $insert_data->bindparam(":clientid",$clientid);
            $insert_data->execute();        
        }
        else
        {           
            $mytag = new Tag($_POST["id"]);  
            $mytag->tag = $rowtag;
            $mytag->cid = $_POST["mynewcid"];
            $mytag->commit($mytag->id);
            $clientid1=$_POST["mynewcid"];
            $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=$clientid1"); 
            $stmt->execute();
            $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
            $db=new db();
            array_push($oldtag,$mytag->id);
            $tagid=implode(',',$oldtag);
            $clientid=$_POST["mynewcid"];
            @$allreadytag=$_POST['allreadytag'];
            $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=$clientid");
            $insert_data->bindparam(":tagid",$tagid);
            $insert_data->execute();  
        }
    }
    $myactivite = new Activites(); // This function for data insert in Activities
    $Titile=$myactivite->Titile = 'Add Tag';
    $myactivite->commit_acitve($Titile);
    echo json_encode(['resonse'=>'Tag has been successfully added']);die;  
}
// view client detail page code end

// all client list page code start
if(isset($_POST['mynewcid2']))
{
    
    $clinetlistarray = explode(',', $_POST['mynewcid2']);
        
        foreach ($clinetlistarray as $value) 
        {
            foreach (@$_POST["tag"] as $rowtag)
            {
                $db2=new db();
                $tag=$rowtag;
                $stmt= $db2->prepare("SELECT * FROM `tag` WHERE tag=:tag"); 
                $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
                $stmt->execute();
                $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);

                @$oldtagformtag=$result_tag[0]['id'];
                $a=$stmt->rowCount();
                if($a>0)
                { 
                    $clientid=$value;
                    $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=:clientid"); 
                    $stmt->bindParam(':clientid', $clientid, PDO::PARAM_STR);
                    $stmt->execute();
                    $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
                    $db=new db();
                    array_push($oldtag,$oldtagformtag);
                    $finaltaglist=array_unique($oldtag);
                    $tagid=implode(',',$finaltaglist);
                    $clientid=$value;
                    @$allreadytag=$_POST['allreadytag'];
                    $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=:clientid");
                    $insert_data->bindparam(":tagid",$tagid);
                    $insert_data->bindparam(":clientid",$clientid);
                    $insert_data->execute();        
                }
                else
                {           
                    $mytag = new Tag($_POST["id"]);  
                    $mytag->tag = $rowtag;
                    $mytag->cid = $value;
                    $mytag->commit($mytag->id);
                    $clientid1=$value;
                    $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=$clientid1"); 
                    $stmt->execute();
                    $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
                    $db=new db();
                    array_push($oldtag,$mytag->id);
                    $tagid=implode(',',$oldtag);
                    $clientid=$value;
                    @$allreadytag=$_POST['allreadytag'];
                    $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=$clientid");
                    $insert_data->bindparam(":tagid",$tagid);
                    $insert_data->execute();  
                }
            }
        }

        $myactivite = new Activites(); // This function for data insert in Activities
        $Titile=$myactivite->Titile = 'Add Tag';
        $myactivite->commit_acitve($Titile);
        echo json_encode(['resonse'=>'Tag has been successfully added']);die;  

}
// all client list page code end

// all tag list page code start
if(isset($_POST['tagRelated']))
{ 
    
    $mynewcid2 =implode(',',$_POST['tagRelated']);
    foreach ($_POST["tag"] as $rowtag)
    {
        $db2=new db();
        $tag=$rowtag;
        $stmt= $db2 ->prepare("SELECT * FROM `tag` WHERE tag=:tag"); 
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->execute();
        $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
        @$oldtagformtag=$result_tag[0]['id'];
        $a=$stmt->rowCount();
        if($a>0)
        {
            @$listofclint= explode(',',$mynewcid2);
            foreach ($listofclint as $row) 
            {
                $db2=new db();
                $id = $row;
                $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=:id"); 
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
                $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
                $db=new db();
                array_push($oldtag,$oldtagformtag);
                $finaltaglist=array_unique($oldtag);
                $tagid=implode(',',$finaltaglist);
                $clientid=$mynewcid2;
                $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=:id");
                $insert_data->bindparam(":tagid",$tagid);
                $insert_data->bindparam(":id",$id);
                $insert_data->execute(); 
            }
            $myactivite = new Activites(); // This function for data insert in Activities
            $Titile=$myactivite->Titile = 'Add Tag';
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Tag has been successfully added']);die;  
        }
        else
        {
            $mytag = new Tag($_POST["id"]);
            $mytag->tag = $rowtag;
            $mytag->cid = $mynewcid2;
            $listofclint= explode(',',$mynewcid2);
            $mytag->commit($mytag->id);
            foreach ($listofclint as $row) 
            {
                $db2=new db();
                $id = $row;
                $stmt= $db2->prepare("SELECT Tags FROM `clients` WHERE id=:id"); 
                $stmt->bindParam(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
                $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $oldtag =$result_tag[0]['Tags']!=''?explode(',',$result_tag[0]['Tags']):array();
                $db=new db();
                array_push($oldtag,$mytag->id);
                $tagid=implode(',',$oldtag);
                $clientid=$mynewcid2;
                $insert_data=$db->prepare("UPDATE clients set Tags=:tagid where id=:id");
                $insert_data->bindparam(":tagid",$tagid);
                $insert_data->bindparam(":id",$id);
                $insert_data->execute();        
            }
            $myactivite = new Activites(); // This function for data insert in Activities
            $Titile=$myactivite->Titile = 'Add Tag';
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Tag has been successfully added']);die;  
        }
    }
}




if(isset($_POST['tag']))
{ 
    
    foreach ($_POST["tag"] as $rowtag)
    {
        $db2=new db();
        $tag=$rowtag;
        $uid=$_SESSION['UserID'];
        $stmt= $db2 ->prepare("SELECT * FROM `tag` WHERE tag=:tag AND createdfk=:uid"); 
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_STR);
        $stmt->execute();
        $result_tag = $stmt->fetchAll(PDO::FETCH_ASSOC);

        @$oldtagformtag=$result_tag[0]['id'];
        $a=$stmt->rowCount();
        if($a>0)
        {
            
             echo json_encode(['error2'=>'This Tag allready available']);die;  
        }
        else
        {
            
                
            $mytag = new Tag($_POST["id"]);
            $mytag->tag = $rowtag;
            $mytag->commit($mytag->id);
            $myactivite = new Activites(); // This function for data insert in Activities
            $Titile=$myactivite->Titile = 'Add Tag';
            $myactivite->commit_acitve($Titile);
            echo json_encode(['resonse'=>'Tag has been successfully added']);die;  
        }
    }
}
// all tag list page code end
?>