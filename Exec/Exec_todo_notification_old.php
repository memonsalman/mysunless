<?php
require_once('Exec_Config.php');		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');

if(isset($_GET['del_todo_notification'])){

	
	if($_POST['type']=="all"){
		$query = $db->prepare("DELETE from Notification where createdfk IN (select id from users where id=:id or adminid=:id or sid=:id)");
	}else{
		$query = $db->prepare("DELETE from Notification where id=:id");
	}
	$query->bindParam(":id",$_POST['id']);
	$run = $query->execute();
	if($run){
		echo json_encode(["response"=>true]);
	}else{
		echo json_encode(["error"=>"Something went wrong."]);
	}
}

if(isset($_GET['todo_notification'])){

	if($_SESSION['usertype']=="subscriber"){
		$query = $db->prepare("SELECT t.id as tid,n.id as nid,u.firstname,u.lastname,u.userimg,t.todoTitle,n.type from Notification n join todo t join users u on n.tid=t.id and u.id=t.createdfk where n.table_name ='todo' and (n.type = 'done' or n.type = 'assign_event') and t.status='0' and t.createdfk=:UserID");
	}else{
		$query = $db->prepare("SELECT t.id as tid,n.id as nid,u.firstname,u.lastname,u.userimg,t.todoTitle,n.type from Notification n join todo t join users u on n.tid=t.id and u.id=t.createdfk where n.table_name ='todo' and (n.type = 'assign' or n.type = 'assign_event')  and t.status='0' and t.asignto=:UserID");
	}

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$data_todo = $query->fetchAll();

	if($_SESSION['usertype']=="subscriber"){
		$query = $db->prepare("SELECT t.id as tid,n.id as nid,t.todoTitle,tc.comment,u.firstname,u.lastname,u.userimg,n.type from Notification n join todocomment tc join users u join todo t on n.tid=tc.id and tc.todoid=t.id and tc.createdfk=u.id where n.table_name='todocomment' and n.type='comment' and tc.status='0' and u.adminid=:UserID");
	}
	else
	{
		$query = $db->prepare("SELECT t.id as tid,n.id as nid,t.todoTitle,tc.comment,u.firstname,u.lastname,u.userimg,n.type from Notification n join todocomment tc join users u join todo t on n.tid=tc.id and tc.todoid=t.id and tc.createdfk=u.id where n.table_name='todocomment' and n.type='comment' and t.asignto=:UserID and tc.status='0' and u.id=".$_SESSION['adminid']);
	}

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$data_todocomment = $query->fetchAll();

	echo json_encode(["todo"=>$data_todo,"todocomment"=>$data_todocomment]);die;
}

if(isset($_GET['event_notification'])){

    $db = new db();
    $UserID = $_SESSION['UserID'];

    $query = $db->prepare("SELECT n.id as nid,n.type,c.ProfileImg,e.* from Notification n join event e join clients c on n.tid=e.id and c.id=e.cid where n.table_name ='event' and (n.type = 'pending' or n.type = 'confirmed' or n.type = 'canceled') and (e.ServiceProvider=:id or e.createdfk=:id)");

    $query->bindValue(':id', $UserID, PDO::PARAM_INT);
    $query->execute();
    $result=$query->fetchAll();

    echo json_encode(["user"=>$result]);die;            
}

?>