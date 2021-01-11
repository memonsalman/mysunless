<?php 
	
		$appid = $_POST['appid'];

		$LoginQuery= $db->prepare("SELECT event.*,clients.ProfileImg FROM `event` LEFT JOIN clients ON event.cid=clients.id WHERE event.UserID=:id");

			    $LoginQuery->bindParam(':id', $appid, PDO::PARAM_INT);

			    $LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);die;	
	
    		echo 'hello';


 ?>