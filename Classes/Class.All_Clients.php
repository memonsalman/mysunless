<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');


class Display{

	public function AjaxDisplay(){
		$db=new db();
		
		$Search = '';
		$User = '';

		$limit = 24;
		if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
		$start_from = ($page-1) * $limit; 

		if(isset($_POST['Search'])){
			$Search = $_POST['Search'];
		}

		if(!empty($_POST['User'])){
			$User = $_POST['User'];
		}else{

			if($_SESSION['usertype']=='Admin'){
				$User = "";
			}else{
				$User = $_SESSION['UserID'];
			}
		}

		if($User){
			$User = " AND createdfk IN ( select id from users where id IN (".$User.") or adminid IN (".$User.") or sid IN (".$User.") ) ";
		}

		// if($_SESSION['usertype']=="Admin")
		// {
		// 	$query = "SELECT * FROM clients WHERE isactive=1 and (FirstName LIKE '%$Search%' OR LastName LIKE '%$Search%' OR CONCAT(FirstName,' ',LastName) LIKE '%$Search%' or email LIKE '%$Search%' )";
		// }
		// else
		// {
		// }
			$query = "SELECT * FROM clients WHERE isactive=1 and (FirstName LIKE '%$Search%' OR LastName LIKE '%$Search%' OR CONCAT(FirstName,' ',LastName) LIKE '%$Search%' or email LIKE '%$Search%')  $User ";

		$LoginQuery = $db->prepare($query);
		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();
		echo json_encode($result);die;	
	}

	public function pagesdata(){
		$db=new db();
		$perpage = 24;
		$sql = $db->prepare("SELECT Count(*) as Total FROM clients");
		$sql->execute();
		$sql->setFetchMode(PDO::FETCH_ASSOC);
		$data= $sql->fetch();
		$totalpage=ceil($data['Total']/$perpage);
		for($i=1;$i<$totalpage;$i++)
		{
			?>
			<li class="page-item"><a class="page-link legitRipple" onclick="pagination(<?= $i ?>)"><?= $i ?></a></li>
			<?php
		}
		die;
	}




			/*public function AjaxDisplay2(){

				$db=new db();

				$id=$_SESSION['UserID'];

			    $LoginQuery = $db->prepare("SELECT * FROM `attechment` WHERE createdfk=:id");

			    $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

			    $LoginQuery->execute();

				$result = $LoginQuery->fetchAll();

				echo json_encode($result);die;	

			}		*/





		}

		?>