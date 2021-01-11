<?php
    require_once('Exec_Config.php');        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'Class.All_Clients.php'); 

if(isset($_REQUEST['GET_CLIENT'])){

  $id = $_GET['GET_CLIENT'];
  if($id){

  $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id IN ($id) ) order by clients.FirstName");
  $editfile4=$eidtUserName3->execute();
  $Customer=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);

	  echo json_encode(['Customer'=>$Customer]); 
  }else{
	  echo json_encode(['Customer'=>[]]); 
  }
  die; 
}



$ClientDisplay=new Display;

if(isset($_POST['page']))
{
		$ClientDisplay->pagesdata();
}

$ClientDisplay->AjaxDisplay();

?>