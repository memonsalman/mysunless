<?php
require_once('Exec_Config.php');		
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');


if(isset($_GET['check_notification'])){

	$stmt= $db->prepare("SELECT NotificationStatus FROM `users` WHERE id=:id"); 
	$stmt->bindParam(':id', $_SESSION['UserID']);
	$stmt->execute();
	$result = $stmt->fetch();
	if(!empty($result['NotificationStatus'])){
		echo json_encode(["response"=>true]);
	}else{
		echo json_encode(["response"=>false]);
	}
}

if(isset($_GET['update_notification'])){

	$stmt= $db->prepare("Update users set NotificationStatus=:status WHERE id=:id"); 
	$stmt->bindParam(':id', $_SESSION['UserID']);
	$stmt->bindParam(':status', $_GET['status']);
	$stmt->execute();
	echo json_encode(["response"=>true]);
}

if(isset($_GET['del_notification'])){

	$id = $_POST['id'];
	$query = $db->prepare("DELETE from Notification where id IN ($id)");
	$run = $query->execute();
	if($run){
		echo json_encode(["response"=>true]);
	}else{
		echo json_encode(["error"=>"Something went wrong."]);
	}
}

if(isset($_GET['todo_notification'])){

	// TODO assign or done and Update Title or Desc

	$data_todo = [];


	$query = $db->prepare("SELECT t.id as tid,n.id as nid,u.firstname,u.lastname,u.userimg,t.todoTitle,n.type,TIMEDIFF(now(),n.datecreated) as curtime from Notification n join todo t join users u on n.tid=t.id and u.id=n.createdfk where n.table_name ='todo' 
		and ( 
		(n.type = 'assign' or n.type = 'done' or n.type = 'assign_event') and t.status='0' and n.createdfk=:UserID)
		or (
		(n.type='update_title' or n.type='update_desc') and t.status='0' and  ( FIND_IN_SET(:UserID,t.asignto) or t.createdfk=:UserID ) and n.createdfk<>:UserID)
		");

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$data_todo = $query->fetchAll();


	//TODO commment

	$query = $db->prepare("SELECT t.id as tid,n.id as nid,t.todoTitle,tc.comment,u.firstname,u.lastname,u.userimg,n.type,TIMEDIFF(now(),n.datecreated) as curtime from Notification n join todocomment tc join users u join todo t on n.tid=tc.id and tc.todoid=t.id and tc.createdfk=u.id where n.table_name='todocomment' and n.type='comment' and tc.status='0' and t.status='0' and n.createdfk=:UserID");

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$data_todocomment = $query->fetchAll();


	//TODO remainder

	$query = $db->prepare("SELECT t.id as tid,n.id as nid,n.type,t.newduedate,t.todoTitle,TIMEDIFF(now(),n.datecreated) as curtime from Notification n join todo t on n.tid=t.id where n.type='remained' and DATEDIFF(t.newduedate,now())=1 and t.status='0' and n.createdfk=:UserID ");

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$data_todorem = $query->fetchAll();


	echo json_encode(["todo"=>$data_todo,"todocomment"=>$data_todocomment,"todoremind"=>$data_todorem]);die;
}

if(isset($_GET['event_notification'])){

	$db = new db();
	$UserID = $_SESSION['UserID'];


    // EVENT status
	$query = $db->prepare("SELECT n.id as nid,n.type,c.ProfileImg,e.*,TIMEDIFF(now(),n.datecreated) as curtime from Notification n join event e join clients c on n.tid=e.id and c.id=e.cid where n.table_name ='event' and (n.type = 'pending' or n.type = 'confirmed' or n.type = 'canceled') and (e.ServiceProvider=:id or e.createdfk=:id)");

	$query->bindValue(':id', $UserID, PDO::PARAM_INT);
	$query->execute();
	$result=$query->fetchAll();

	echo json_encode(["user"=>$result]);die;            
}

if(isset($_GET['backup_notification'])){

	$query = $db->prepare("SELECT * FROM `BackupReminder` WHERE createdfk=:UserID and status=0 and DATE_ADD(crateddate, INTERVAL duration DAY)=CURDATE()");

	$query->bindParam(":UserID",$_SESSION['UserID']);
	$query->execute();
	$result = $query->fetch();

	if($query->rowCount()>0){
		$date = date('Y-m-d');
		$query = $db->prepare("Update BackupReminder set crateddate=:date where createdfk=:UserID");
		$query->bindParam(":UserID",$_SESSION['UserID']);
		$query->bindParam(":date",$date);
		$query->execute();


		$query = $db->prepare("Insert into Notification (`table_name`, `tid`, `type`, `done`, `createdfk`) values('BackupReminder',:tid,'Backup','0',:UserID ) ");
		$query->bindParam(":UserID",$_SESSION['UserID']);
		$query->bindParam(":tid",$result['id']);
		$query->execute();

		$NotificationID = $db->lastInsertId();

		echo $NotificationID;die;            
	}else{
		echo '';die;
	}




}

?>