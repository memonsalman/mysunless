<?php
require_once('Exec_Config.php');
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Todo.php');


@$myevent = new Todo($_POST["id"]);
@$myevent->id = $_POST["id"];


if(empty($_POST["todoTitle"])){
    @$todoTitle = $myevent->todoTitle;
}else{
    @$todoTitle = $myevent->todoTitle = $_POST["todoTitle"];
}

if(empty($_POST["todoDesc"])){
    @$todoDesc = $myevent->todoDesc;
}else{
    $myevent->todoDesc = $_POST["todoDesc"];
}

if(empty($_POST["dueDate"])){
    @$dueDate = $myevent->dueDate;
    @$newduedate = $myevent->newduedate;
}else{
    $myevent->dueDate = $_POST["dueDate"];
    $myevent->newduedate = $_POST["dueDate"];
}

if(empty($_POST["dueDate"])){
    @$dueDate = $myevent->dueDate;
}else{
    $myevent->dueDate = $_POST["dueDate"];
}

if(empty($_POST["colorcode"])){
    @$colorcode = $myevent->colorcode;
}else{
    $myevent->colorcode = $_POST["colorcode"];
}

if(empty($_POST["cid"])){
    @$cid = $myevent->cid;
}else{
    if(@$_POST["cid"] == "todo")
    {
        $_POST["cid"] = "1";   
    }
    else if(@$_POST["cid"] == "")
    {
        $_POST["cid"] = "";
    }

    $myevent->cid =$_POST["cid"];

}


if(empty($_POST["asignto"])){
    @$asignto = $myevent->asignto;
    $asignto = explode(',',$asignto);
}else{
    $myevent->asignto = implode(',',$_POST["asignto"]);
    $asignto = $_POST["asignto"];
}


$newTodo=$myevent->commit($myevent->id);


//Notification


$asignto = array_unique($asignto);

foreach ( $asignto as $key => $value) {

    // assign user
    if($value!=$_SESSION['UserID'] && !empty($_POST["asignto"]) ){
        $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'assign',:createdfk)");
        $sql->bindParam(":tid",$newTodo);
        $sql->bindParam(":createdfk",$value);
        $run = $sql->execute();
    }

    // Due date 
    if(!empty($_POST['dueDate'])){

        $sql = $db->prepare("select * from Notification where tid=:tid and table_name='todo' and type='remained' and createdfk=:createdfk");
        $sql->bindParam(":tid",$newTodo);
        $sql->bindParam(":createdfk",$value);
        $run = $sql->execute();

        if($sql->rowCount()<1){
            
            $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'remained',:createdfk)");
            $sql->bindParam(":tid",$newTodo);
            $sql->bindParam(":createdfk",$value);
            $run = $sql->execute();
        }
    }

}


    // Update title 
    if(!empty($_POST['todoTitle']) && isset($_GET["updateinfo"]) ){

        $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'update_title',:createdfk)");
        $sql->bindParam(":tid",$newTodo);
        $sql->bindParam(":createdfk",$_SESSION['UserID'] );
        $run = $sql->execute();
        
    }

    // Update description 
    if(!empty($_POST['todoDesc']) && isset($_GET["updateinfo"]) ){

        $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'update_desc',:createdfk)");
        $sql->bindParam(":tid",$newTodo);
        $sql->bindParam(":createdfk",$_SESSION['UserID'] );
        $run = $sql->execute();
        
    }


if($myevent)
{

    $myactivite = new Activites();
    if($_POST['id']=="new")
    {
        $myevent->ActivitesCount($newTodo); // This function for data insert in CountActivites.
        $Titile=$myactivite->Titile = 'Add new To-Do '.$todoTitle;	
    }
    else
    {
        $Titile=$myactivite->Titile = 'Update To-Do '.$todoTitle.' details';		
    }
    $myactivite->commit_acitve($Titile);
    if($_POST['id']=="new")
    {
        echo json_encode(['resonse'=>'Task has been successfully added']);die;	
    }	
    else
    {
        echo json_encode(['resonse'=>'Task has been successfully updated']);die;
    }
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>