<?php

     require_once('Exec_Config.php');   


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Note.php'); 

if(isset($_POST["noteTitle"]))
{
    $mynote = new Note($_POST["id"]);
    $mynote->id = $_POST["id"];
    $mynote->noteTitle =$_POST["noteTitle"];
    $mynote->noteDetail =$_POST["noteDetail"];
    $related=$_POST["noteRelated"];
    $mynote->noteRelated = implode(',',$_POST["noteRelated"]);
    @$mynote->cid = $_POST["cid"];

    $mynote->commit($mynote->id);
    if($mynote)
    {
        $myactivite = new Activites();
        if($_POST['id']=="new")
        {
            $Titile=$myactivite->Titile = 'Add new note '.$_POST["noteTitle"]; 
        }
        else
        {
            $Titile=$myactivite->Titile = 'Update note '.$_POST["noteTitle"].' detail';      
        }
        $myactivite->commit_acitve($Titile);


        if($_POST['id']=="new")
        {
           
            foreach ($related as $row)
            {
                //$clientid=$row[0];
                $clientid=$related[0];
                $noteid=$mynote->id;
                $cratedata= date("Y-m-d H:i:s");
            
                
                $db=new db();
                $insert_data=$db->prepare("INSERT INTO noteandclient(noteid,clientid,cratedata) VALUES(:noteid,:clientid,:cratedata)");
                    
                $insert_data->bindparam(":noteid",$noteid);
                $insert_data->bindparam(":clientid",$clientid);
                $insert_data->bindparam(":cratedata",$cratedata);
                $insert_data->execute();        
            }
            
            echo json_encode(['resonse'=>'Note has been successfully added',"mydata"=>$mynote]);die;  
        }        
        else
        {   
            
            $db=new db();
            $id=$_POST['id'];
            $DeleteTag2 = $db->prepare("delete from `noteandclient` where noteid=:id");
            $DeleteTag2->bindValue(":id",$id,PDO::PARAM_INT);
            $DeleteTag2->execute();
            foreach ($related as $row)
            {
                $clientid=$related[0];
                $noteid=$mynote->id;
                $cratedata= date("Y-m-d H:i:s");
                $db=new db();
                $insert_data=$db->prepare("INSERT INTO noteandclient(noteid,clientid,cratedata) VALUES(:noteid,:clientid,:cratedata)");
                $insert_data->bindparam(":noteid",$noteid);
                $insert_data->bindparam(":clientid",$clientid);
                $insert_data->bindparam(":cratedata",$cratedata);
                $insert_data->execute();        
            }
            

            echo json_encode(['resonse'=>'Note has been successfully updated',"mydata"=>$mynote]);die;
        }
    }
    else
    {
        echo json_encode(['error'=>'sorry something wrong']);die;
    }
}
else{
    $AllNote = new Note;
    $AllNote->displayNote();  
}
?>