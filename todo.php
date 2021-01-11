<?php
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: ../index.php");die;
}
$uid = $_SESSION['UserID'];

if(isset($_GET['todolist']))
{
	if(isset($_POST['data']))
	{
		$sql = $db->prepare("UPDATE `CompanyInformation` SET todolist =:list WHERE createdfk=:id");
		$sql->bindParam(':list', $_POST["data"]);
		$sql->bindParam(':id', $uid);
		$run = $sql->execute();
		if($run)
		{
			echo "updated";
			die();
		}

		die();
	}
}

if(isset($_GET['SubmitButton'])){

  $sql = $db->prepare("Select createdfk from todo where id=:tid");
  $sql->bindParam(":tid",$_POST["taskid"]);
  $sql->execute();
  $result = $sql->fetch();


  $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'done',:createdfk)");
  $sql->bindParam(":tid",$_POST["taskid"]);
  $sql->bindParam(":createdfk",$result['createdfk']);
  $run = $sql->execute();

  if($run){
    echo json_encode(["response"=>"true"]);die;
  }else{
    echo json_encode(["error"=>"Something went wrong"]);die;
  }
}

if(isset($_GET['addcategory']))
{

	$date = date('Y/m/d h:i:s', time());
	$catname = $_POST['catname'];
	$sql = $db->prepare("insert into todocat(createdfk,catname,createddate) values(:id,:catname,:date)");
	$sql->bindParam(":id",$uid);
	$sql->bindParam(":catname",$catname);
	$sql->bindParam(":date",$date);
	$fire = $sql->execute();

	if($fire)
	{
		echo json_encode(['success'=>"1","response"=>"Category Added"]);
		die();
	}
	else
	{
		echo json_encode(['success'=>"0","error"=>"Something went wrong"]);
		die();
	}
	die();

}

if(isset($_GET["catstatus"]))
{
	$cat_id = $_POST["cat_id"];
	if($cat_id == "todo")
	{
		$cat_id = "1";
	}
	$task_id = $_POST["task_id"];
	$sql = $db->prepare("update todo set catstatus = :cat_id where id = :task_id");
	$sql->bindParam(":cat_id",$cat_id);
	$sql->bindParam(":task_id",$task_id);
	$run = $sql->execute();


 $data = $_POST['data'];
 foreach ($data as $key => $value) {
  $client = $db->prepare("update todo set position = :position where id = :task_id and catstatus = :cat_id ");
  $client->bindValue(":position",$key);
  $client->bindValue(":task_id",$value);
  $client->bindValue(":cat_id",$cat_id);

  $run = $client->execute();
}


if($run)
{
  echo json_encode(['success'=>"1","response"=>"Task moved successfully"]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong"]);
}
}

if(isset($_GET["updcatname"]))
{
	$catname = $_POST["newcat"];
	$csql = $db->prepare('SELECT * FROM `todocat` WHERE createdfk = :id AND catname = :catname AND status="0"');
	$csql->bindParam(":id",$uid);
	$csql->bindParam(":catname",$catname);
	$csql->execute();
	$row = $csql->rowCount();
	if($row == 0)
	{
		$sql = $db->prepare("update todocat set catname = :catname where createdfk = :id AND id = :cid");
		$sql->bindParam(":catname",$catname);
		$sql->bindParam(":cid",$_POST["id"]);
		$sql->bindParam(":id",$uid);
		$run = $sql->execute();
		if($run)
		{
			echo json_encode(['success'=>"1","response"=>"Categoryname updated successfully"]);
			die();
		}
	}
	else
	{
		echo json_encode(['success'=>"0","error"=>"Categoryname already exists"]);
		die();
	}



}

if(isset($_GET["delcat_all"]))
{

 $catid = $_POST["catid"];
 
 $tasksql = $db->prepare("delete from todo where catstatus = :id");
 $tasksql->bindParam(":id",$catid);
 $run2 = $tasksql->execute();

 $catsql = $db->prepare("delete from todocat where id = :id");
 $catsql->bindParam(":id",$catid);
 $run = $catsql->execute();

 if($run && $run2)
 {
  echo json_encode(['success'=>"1","response"=>"Category closed successfully"]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
  die();
}

}


if(isset($_GET["delcat"]))
{
 $date = date('Y/m/d h:i:s', time());
 $catid = $_POST["catid"];
 $catsql = $db->prepare("update todocat set closedby = :UserID,closeddate = :closeddate,status = '1' where id = :id AND createdfk = :UserID");
 $catsql->bindParam(":id",$catid);
 $catsql->bindParam(":closeddate",$date);
 $catsql->bindParam(":UserID",$uid);
 $run = $catsql->execute();

 $tasksql = $db->prepare("update todo set status = '1',closedby = :UserID,closeddate = :closeddate where catstatus = :id AND status='0' AND createdfk = :UserID");
 $tasksql->bindParam(":id",$catid);
 $tasksql->bindParam(":closeddate",$date);
 $tasksql->bindParam(":UserID",$uid);
 $run2 = $tasksql->execute();
 if($run && $run2)
 {
  echo json_encode(['success'=>"1","response"=>"Category closed successfully"]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
  die();
}

}

if(isset($_GET["loadcmt"]))
{   
  $tid = $_POST['taskid'];
  $commentid = !empty($_POST['comment'])?$_POST['comment']:"''";

  //new comment
  $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username,users.userimg FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todoid=:todoid and todocomment.id NOT IN ($commentid) AND todocomment.status = '0' ");
  $cmtsql->bindParam(":todoid",$tid);
  $cmtsql->execute();
  $cmtdata = $cmtsql->fetchAll();

  //deleted comment
  $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username,users.userimg FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todoid=:todoid and todocomment.id IN ($commentid) AND todocomment.status = '1' ");
  $cmtsql->bindParam(":todoid",$tid);
  $cmtsql->execute();
  $cmt_del_data = $cmtsql->fetchAll();

  // Edited comment
  $createddate = date('Y-m-d H:i:s');
  $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username,users.userimg FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todoid=:todoid and todocomment.createddate>DATE_SUB(:createddate, INTERVAL 3 SECOND) and todocomment.id IN ($commentid) AND todocomment.status = '0' and todocomment.createdfk<>:uid ");
  $cmtsql->bindParam(":todoid",$tid);
  $cmtsql->bindParam(":uid",$_SESSION['UserID']);
  $cmtsql->bindParam(":createddate",$createddate);
  $cmtsql->execute();
  $cmt_edit_data = $cmtsql->fetchAll();

  echo json_encode(["cmtdata"=>$cmtdata,"cmt_del_data"=>$cmt_del_data,"cmt_edit_data"=>$cmt_edit_data]);
  die();

}

if(isset($_GET["addcmt"]))
{
 $taskid = $_POST["taskid"];
 $cmt = $_POST["cmt"];
 $date = date('Y-m-d H:i:s');
 $createdfk = $_SESSION['UserID'];

 $sql = $db->prepare("insert into todocomment(comment,todoid,createddate,createdfk) values(:cmt,:taskid,:date,:createdfk)");
 $sql->bindParam(":cmt",$cmt);
 $sql->bindParam(":taskid",$taskid);
 $sql->bindParam(":date",$date);
 $sql->bindParam(":createdfk",$createdfk);
 $run = $sql->execute();
 $tid = $db->lastInsertId();


 $sql = $db->prepare("SELECT CONCAT(asignto,',',createdfk) as assignto,createdfk FROM `todo` WHERE id=:id");
 $sql->bindParam(":id",$taskid);
 $sql->execute();
 $result = $sql->fetch();

 $assignto = explode(',',$result['assignto']);
 $assignto = array_unique($assignto);

 foreach ($assignto as $value) {

  if($createdfk!=$value){

   $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todocomment',:tid,'comment',:createdfk)");
   $sql->bindParam(":tid",$tid);
   $sql->bindParam(":createdfk",$value);
   $run = $sql->execute();
 }
}

if($run)
{

 $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username,users.userimg FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todocomment.id = :id AND todocomment.status = '0' ");
 $cmtsql->bindParam(":id",$tid);
 $cmtsql->execute();
 $cmtdata = $cmtsql->fetchAll();

 echo json_encode(['success'=>"1","response"=>"Comment posted successfully.","cmtdata"=>$cmtdata]);
 die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something wrong please try again later."]);
  die();
}   

}

if(isset($_GET["delcmt"]))
{
 $cid = $_POST["id"];
 $date = date('Y/m/d h:i:s', time());
 $sql = $db->prepare("update todocomment set status = '1',closedby = :uid,closeddate = :date where id = :cid");
 $sql->bindParam(":date",$date);
 $sql->bindParam(":uid",$uid);
 $sql->bindParam(":cid",$cid);
 $run = $sql->execute();
 if($run)
 {
  echo json_encode(['success'=>"1","response"=>"Comment deleted successfully."]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something wrong please try again later."]);
  die();
}
}

if(isset($_GET["cmtupdate"]))
{
 $cmt = $_POST["cmt"];
 $cmtid = $_POST["cmtid"];
 $date = date("Y-m-d H:i:s");
 $sql = $db->prepare("update todocomment set comment = :cmt,createddate=:date where id = :cmtid");
 $sql->bindParam(":cmt",$cmt);
 $sql->bindParam(":cmtid",$cmtid);
 $sql->bindParam(":date",$date);
 $run = $sql->execute();
 if($run)
 {
  echo json_encode(['success'=>"1","response"=>"Comment updated successfully."]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
  die();
}

}


if(isset($_GET["restore_task"]))
{
 $id = $_POST["id"];

 $sql = $db->prepare("SELECT todocat.status as cat_status,todocat.id FROM todo join `todocat` on todo.catstatus=todocat.id where todo.id=:id ");
 $sql->bindParam(":id",$id);
 $cat = $sql->execute();
 $result = $sql->fetch();

 if($result['cat_status']=='1' && $result['id']!='1'){
  $catid = $result['id'];
  $sql = $db->prepare("update todocat set status = '0' where id = :catid");
  $sql->bindParam(":catid",$catid);
  $sql->execute();

}

$sql = $db->prepare("update todo set status = '0' where id = :id");
$sql->bindParam(":id",$id);
$run = $sql->execute();
if($run)
{
  echo json_encode(['response'=>"1"]);
  die();
}
else
{
  echo json_encode(['error'=>"1"]);
  die();
}

}

if(isset($_GET["del_task"]))
{
 $id = $_POST["id"];

 $sql = $db->prepare("delete from todo where id IN ($id)");
 $run = $sql->execute();
 if($run)
 {
  echo json_encode(['response'=>"1"]);
  die();
}
else
{
  echo json_encode(['error'=>"1"]);
  die();
}

}



if(isset($_GET["gethistory"]))
{
 $sql = $db->prepare("SELECT todo.*,users.firstname,users.lastname,users.UserName,users.userimg,todocat.catname FROM  todo JOIN users on users.id = todo.closedby JOIN todocat on todocat.id = todo.catstatus WHERE (todo.createdfk = :id or todo.asignto = :id) AND todo.status = '1' ORDER BY todo.closeddate DESC");
 $sql->bindParam(":id",$uid);
 $sql->execute();
 $data = $sql->fetchAll(PDO::FETCH_ASSOC);
 echo json_encode(['success'=>"1","response"=>$data]);
 die();
}

$ID = 'ID';
if(isset($_SESSION['UserID']))
{
 $id=$_SESSION['UserID'];
 $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 $stmt->execute();
 $result = $stmt->fetch(PDO::FETCH_ASSOC);
 @$todocreateprmistion=$result['TodoCreate'];
}

$total_todo = $db->prepare("SELECT * FROM `todo` WHERE `createdfk`=:id");
$total_todo->bindParam(':id', $id, PDO::PARAM_INT);
$total_todo->execute();
$number_of_todo = $total_todo->rowCount();

if($todocreateprmistion==0)
{
 header("Location: ../index.php");die;  
}   

if(isset($_GET['EditViewTodo'])) 
{    
 $myevent = $_POST["id"] ;

 $total_todo = $db->prepare("SELECT * FROM `todo` WHERE `id`=:myevent");
 $total_todo->bindParam(':myevent', $myevent, PDO::PARAM_INT);
 $total_todo->execute();
 $GetEvent=$total_todo->fetch(PDO::FETCH_ASSOC);
 echo json_encode(['resonse'=>$GetEvent]);die; 
}

if(isset($_GET['ViewTodo'])) 
{    
 $myevent = $_POST["id"];
 $total_todo = $db->prepare("SELECT * FROM `todo` WHERE `id`=:myevent");
 $total_todo->bindParam(':myevent', $myevent, PDO::PARAM_INT);
 $total_todo->execute();
 $GetEvent=$total_todo->fetch(PDO::FETCH_ASSOC);


 $assignto = $GetEvent['asignto'];
 $query = $db->prepare("SELECT * FROM `users` WHERE `id` IN ($assignto) ");
 $query->execute();
 $users=$query->fetchAll();


 $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username,users.userimg FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todoid = :id AND todocomment.status = '0' ");
 $cmtsql->bindParam(":id",$myevent);
 $cmtsql->execute();
 $cmtdata = $cmtsql->fetchAll(PDO::FETCH_ASSOC);

 echo json_encode(['resonse'=>$GetEvent,"cmtdata"=>$cmtdata,"assigntoUser"=>$users]);die; 
}



if(isset($_GET['DelteViewTodo']))
{
 $date = date('Y/m/d h:i:s', time());
 $myevent = $_POST["id"];
 $DeleteClient = $db->prepare("update todo set status = '1',closedby = :closedby,closeddate = :closeddate where id=:myevent");
 $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
 $DeleteClient->bindValue(":closedby",$uid);
 $DeleteClient->bindValue(":closeddate",$date);
 $run = $DeleteClient->execute();
 if($run)
 {
  echo json_encode(['success'=>"1","response"=>"Task has been successfully closed."]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
  die();
}
}   



if(isset($_GET['update_card_position'])){
 $data = $_POST['data'];
 foreach ($data as $key => $value) {
  $client = $db->prepare("update todocat set position = :position where createdfk=:createdfk and id=:id");
  $client->bindValue(":position",$key,PDO::PARAM_INT);
  $client->bindValue(":createdfk",$uid);
  $client->bindValue(":id",$value);
  $run = $client->execute();
}
if($run)
{
  echo json_encode(['success'=>"1","response"=>"card are update"]);
  die();
}
else
{
  echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
  die();
}
}


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?= base_url?>/assets/css/todo.css">
<style type="text/css">

 @media screen and (max-width:425px){
  .hsy_sub{
   width: 300px;
 }
 .card_header_button {
    right: 0!important;
  }
  .list_edit_button{
    left: 0!important;
  }
  #li{
    margin-left: 50px;
  }
  .card_header_text{
    width: 80%;
  }
}

.custbutton{margin: 5px 0;}
th { font-weight: bold!important;color:#0b59a2!important;}
.hsy_sub{
  position: absolute;
  background: #fff;
  top: 43px;
  right: 0;
  z-index: 2;
  box-shadow: 0 0 6px rgba(0,0,0,0.1);
  max-height: 430px;
  max-width: 450px;
  overflow: scroll;
}
.colorpre{
  width: 50px;
  margin-right: 11px;
  height: 50px;
  padding: 4px;
  border-radius: 0px;
}
li:hover{
  webkit-filter: hue-rotate(-70deg) saturate(1.5) !important;
}

.listarrow {
    position: absolute;
    left: -13px;
    /*top: 42%;*/
    transform: rotate(136deg);
    z-index: 1;
    font-size: 18px;
    color: #383838;
}

.text-row:hover{
  animation-name: list_skew;
  animation-duration: 0.5s;
  animation-iteration-count: 1;
  animation-timing-function: ease;
}

@keyframes list_skew {
  0%   {transform: rotate(0deg)}
  25%  {transform: rotate(-2deg)}
  50%  {transform: rotate(0deg)}
  75%  {transform: rotate(1deg)}
  100% {transform: rotate(0deg)}
}

.status-card .card-header{
  background-color: #181818;
  color: #fff;
  font-size: 15px;
  padding: 12px 15px;
  z-index: 2;
  box-shadow: 0 2px 10px 0px #00000078;
  
}

.card_header:hover .card_header_button{
  right: 0;
}

.text-row:hover .list_edit_button{
  left: 0;
}

.list_edit_button {
  position: absolute;
  display: flex;
  justify-content: space-around;
  align-items: center;
  left: -100px;
  top: 8px;
  background: black;
  border-radius: 0 10px 10px 0;
  box-shadow: -2px 3px 2px 0px #7777775c;
  transition: all 0.25s;
}
.card_header_button {
  position: absolute;
  display: flex;
  justify-content: space-around;
  align-items: center;
  right: -100px;
  top: 8px;
  background: black;
  border-radius: 10px 0 0 10px;
  box-shadow: -2px 3px 2px 0px #7777775c;
  transition: all 0.25s;
}

.status-card .card-footer{
  box-shadow: 0 0px 10px 1px #00000078;
  background-color: #0062ff;
  color: #fff;
  padding: 3px;
}

.status-card .card-footer button{
  background: transparent;
}

.status-card .card-footer button span{
  color:white;
}

.profile_img:hover{
  z-index: 2;
}

.profile_img{
  position: relative;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin-left: -17px;
  z-index: 1;
}
#tasks strong{
  color:white!important;
}
.status-card.sortable_row_connect.ui-sortable-helper{
  transform: rotate(5deg);
}
.status-card li.ui-sortable-helper{
  transform: rotate(5deg);
}

.savebtn,#editcat,#delcat{
  cursor: pointer;
}

.todo_archive_li{
  list-style: none;padding: 10px;align-items: center;border-bottom: 1px solid #cac5c5;
  margin: 0;
}

.todo_archive_1{
  text-align: center;
  width: 10%;
}

.todo_archive_2{
  width: 75%;
  padding: 5px 5px;
  background: aliceblue;
  border-radius: 5px;
  margin: 0 5px;
}

.todo_archive_3{
  display: flex;
  flex-direction: column;
  justify-content: center;
}

#cmtbody {
  padding: 20px;
  padding-top: 20px;
  background: #d5dde4;
  margin-top: 10px;
  border-radius: 5px;
  overflow-y: auto;
  max-height: 600px;
}

.comment {
  padding: 15px;
  list-style: none;
  width: 90%;
  word-break: break-all;
  border-radius: 14px;
  background: white;
  border-color: transparent;
}
.commentbody{
  margin: 10px 0;
}
.commentbody-rev{
  text-align: right;
  direction: rtl;
  margin: 10px 0;
  position: relative;
}
.comment_head{
  display: flex;
}
.comment_user_detail{
  display: flex;
  flex-direction: column;
}
.commentarrow{
  margin-right: 17px;
  width: 0;
  height: 0;
  border-left: 10px solid transparent;
  border-right: 10px solid transparent;
  border-bottom: 10px solid white;
}
.red-button {
  padding: 5px 7px;
  color: white;
  text-decoration: none;
  background: red;
  border-radius: 3px;
}
.red-button:hover {
  color: white;
  background: #ec1818;
}
.blue-button {
  padding: 5px 7px;
  color: white;
  text-decoration: none;
  background: #03a9f3;
  border-radius: 3px;
}
.blue-button:hover {
  color: white;
  background: #218dbd;
}
.green-button {
  padding: 3px 6px;
  color: white;
  text-decoration: none;
  background: #49ca1f;
  border-radius: 3px;
  font-size: 12px;
}
.green-button:hover {
  color: white;
  background: #389c18;
}
.red-close-button{
  background: red;
  color: white!important;
  padding: 5px 7px;
  border-radius: 3px;
  font-size: 12px;
}
.deleteButton,.edit_data{
  color: white;
  cursor: pointer;
}
#addtask, #addtaskself{
  cursor: pointer;
}
.card_header_text{
  font-weight: normal;
}
.choose_red{
  background-color: #DB2828
}
.choose_orange{
  background-color: #F2711C
}
.choose_yellow{
  background-color: #ebb30a
}
.choose_black{
  background-color: #000000;
}
.choose_green{
  background-color: #21BA45
}
.choose_teal{
  background-color: #00B5AD
}
.choose_blue{
  background-color: #0062ff
}
.choose_violet{
  background-color: #6435C9
}
.choose_purple{
  background-color: #A333C8
}
.choose_pink{
  background-color: #E03997
}
#choose_color input[type="radio"] {
  display: none;
} 
#choose_color input[type="radio"]:checked + label span{
  transform: scale(1.25);
}


#choose_color label {
  display: inline-block;
  width: 25px;
  height: 25px;
  margin: 10px;
  cursor: pointer;
}
#choose_color label:hover span{
  transform: scale(1.25);
}
#choose_color{
  justify-content: center;
}

#choose_color span {
  display: block;
  width: 100%;
  height: 100%;
  transition: transform .2s ease-in-out;
}
.new_comment {
    border: 2px dashed green;
    padding: 5px;
    border-radius: 5px;
}

.edit_comment {
    border: 2px dashed #ff6a00!important;
}

.del_comment {
    border-radius: 5px;
    background: #ff00001c;
    position: absolute;
    width: 100%;
    height: 100%;
}

.show_cat_edit{
   position: relative!important;
  top: 0!important;
}

.catin{
  transition: all 0.5s;
    position: absolute;
    top: -100px;
}

textarea#catin{
  box-shadow: 0 0 14px 10px #00000052;
}

</style>
<body class="skin-default fixed-layout mysunlessO">
 <!-- ============================================================== -->
 <!-- Preloader - style you can find in spinners.css -->
 <!-- ============================================================== -->
 <div class="preloader">
  <div class="loader">
   <div class="loader__figure">
   </div>
   <p class="loader__label">
    <?= $_SESSION['UserName']; ?></p>
  </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
 <!-- ============================================================== -->
 <!-- Topbar header - style you can find in pages.scss -->
 <!-- ============================================================== -->
 <header class="topbar">
  <?php include 'TopNavigation.php'; ?>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<?php include 'LeftSidebar.php'; ?>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
  <!-- ============================================================== -->
  <!-- Container fluid  -->
  <!-- ============================================================== -->
  <div class="container-fluid">
   <!-- ============================================================== -->
   <!-- Bread crumb and right sidebar toggle -->
   <!-- ============================================================== -->
   <div class="row page-titles">
    <div class="row col-md-12 " >
     <div class=" col-md-5 align-self-center">
      <h4 class="text-themecolor">To-Do Management</h4>
    </div>
    <div class="col-md-7">
      <a title="Archive List" style="font-size: 34px;position: absolute;right: 10px;bottom: -15px;" class="todo_archive" href="#"><i class="fa fa-archive"></i></a>
    </div>
    <div id="historybody" style="display: none;"  class="hsy_sub animated flipInY show">

    </div>
  </div>
</div>
<div class="row" style="height: auto;" >
  <div class="col-md-12" style="height: auto;" >
   <div class="card" style="height: auto;" >
    <div class="card-body">
     <div class="col-lg-12">
      <a href="#" id="openM" class="btn btn-info m-r-10 " data-toggle="modal" data-target="#addcatmodal" data-backdrop="static" data-keyboard="false">Add Category</a>
    </div>


    <div id="tasks" class="task-board row sortable_row" ></div>

  </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog" style="max-width: 700px;">


   <div class="modal-content" style="border-radius: 0px;border:0px;">
    <div class="modal-header" style="border-radius: 0px;padding: 1.5rem;">
     <label class="modal-title updatetitle" style="font-size: 20px;color: white">Add Task</label>
     <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; text-shadow: unset;">×</button>
   </div>
   <div class="modal-body">
     <form class="form-horizontal " autocomplete="off" id="NewEvent" method="post">

      <input type="hidden" id="cid" name="cid" value="">
      <div class="Loader"></div>
      <input type="hidden" name="id" id="id" value="">
      <div class="form-group">
       <label for="todoTitle">
        <h3>Title*</h3>
      </label>
      <br>
      <input type="text" name= "todoTitle" id="todoTitle" placeholder="Enter Task Title...." class="form-control" value="">
    </div>
    <div class="form-group">
     <label for="todoDesc">
      <h3>Description*</h3>
    </label>
    <br>
    <textarea class="textarea_editor form-control" width="100%" rows="10" placeholder="Enter Task Description..." id="todoDesc" name="todoDesc"></textarea>
  </div>
  <div class="row form-group" >
   <div class=" col-md-6" id= "datetimepicker">
    <label for="dueDate">
     <h3>Due Date*</h3>
   </label>
   <br>
   <input type="text" name= "dueDate" class="form-control"  id="dueDate">
 </div>
 <div class="form-group col-md-6">
  <label for="Assign to">
   <h3>Assign*</h3>
 </label>
 <br>
 <span id="asignto">
   <select name= "asignto[]" class="form-control"   multiple="multiple">
    <option value="<?= $_SESSION["UserID"]; ?>">Self</option>
    <?php
    $emp = $db->prepare("select id,adminid,username,firstname,lastname from users where usertype = 'employee' AND adminid = :id");
    $emp->bindParam(":id",$_SESSION["UserID"]);
    $emp->execute();
    $data = $emp->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $key)
    {
     ?>
     <option value="<?= $key['id']; ?>" ><?= $key['firstname']." ".$key["lastname"]; ?></option>
     <?php
   }
   ?>
 </select>
</span>

<h4 id="asigntoself" style="display: none;">
 Self
 <input type="hidden" name= "asignto[]" class="form-control"  value="<?= $_SESSION["UserID"]; ?>" >
</h4>

</div> 
</div>
<div class="form-group">
<!-- <h3>Color Picker</h3>
 <br>
  <div class="row col-md-12 form-group" >
   <div class="col-md-11" style="display: inline-flex;align-items: center;" >
    <input class="colorpre" type="color" id="colorpre" name="colorcode" value="#0062ff">    
    <div>

     <li class="text-row ui-sortable-handle" id="preview" style="color:white;width: 297px;margin-left: 10px;margin: 0px;list-style: none;background: #0062ff" > 
      <div  style="font-size: 15px;width: 80%;display: inline-block;padding: 15px 10px;">#preview</div>
      <div style="float:right;padding: 15px 10px 15px 0px;" class="close"><span class="fa fa-close fa-sm"></span></div>
      <div style="float:right;padding: 15px 7px 15px 0px;" class="close"><span class="fa fa-edit fa-sm"></span></div>
      <hr style="margin: 0px;">
      <div style="text-align: right;padding: 3px;">
       <span style="float:left;margin-bottom: 0px;color: grey;font-size: 11px;"><strong style="color:white">Jan 01, 2020 </strong></span>
       <span style="margin-bottom: 0px;color: grey;font-size: 11px;"><strong style="color:white">username </strong>
        <img src="https://mysunless.com/crm/assets/images/noimage.png" class="profile_img">
      </span>
    </div>
  </li>
</div>
</div>
</div> -->

<h3>Radio Color Picker</h3>
<div class="row" id="choose_color">
<input class="colorpre" type="color" id="colorpre" name="colorcode" value="#0062ff">  
 <input type="radio" name="color" id="choose_red" value="#DB2828" />
 <label for="choose_red"><span class="choose_red"></span></label>

 <input type="radio" name="color" id="choose_green" value="#21BA45"/>
 <label for="choose_green"><span class="choose_green"></span></label>

 <input type="radio" name="color" id="choose_yellow" value="#ebb30a"/>
 <label for="choose_yellow"><span class="choose_yellow"></span></label>

 <input type="radio" name="color" id="choose_black" value="#000000"/>
 <label for="choose_black"><span class="choose_black"></span></label>

 <input type="radio" name="color" id="choose_orange" value="#F2711C"/>
 <label for="choose_orange"><span class="choose_orange"></span></label>

 <input type="radio" name="color" id="choose_teal" value="#00B5AD"/>
 <label for="choose_teal"><span class="choose_teal"></span></label>

 <input type="radio" name="color" id="choose_blue" value="#0062ff"/>
 <label for="choose_blue"><span class="choose_blue"></span></label>

 <input type="radio" name="color" id="choose_violet" value="#6435C9"/>
 <label for="choose_violet"><span class="choose_violet"></span></label>

 <input type="radio" name="color" id="choose_purple" value="#A333C8"/>
 <label for="choose_purple"><span class="choose_purple"></span></label>

 <input type="radio" name="color" id="choose_pink" value="#E03997"/>
 <label for="choose_pink"><span class="choose_pink"></span></label>

</div>
</div>

<div class="form-group">
  <button type="submit" name="todoSub" id="todoSub" class="btn btn-info m-r-10"><i class="fa fa-check"></i>Add Task</button>
</div>
</form>
</div>
</div>
</div>
</div>


<div class="modal fade" id="addcatmodal" role="dialog">
 <div class="modal-dialog">

  <div class="modal-content" style="border-radius: 0px;border:0px;">
   <div class="modal-header" style="border-radius: 0px;padding: 1.5rem;background: #0062ff;">
    <label class="modal-title updatetitle" style="font-size: 20px;color: white">Add Category</label>
    <button type="button" class="close" data-dismiss="modal" style="color: white; font-size: 2.3125rem; line-height: 27px; opacity: 1; text-shadow: unset;">×</button>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" id="addcat" autocomplete="off" method="post">
     <div class="Loader"></div>
     <div class="form-group">
      <label for="todocat">
       <label style="font-size: 22px">Category Name</label>
     </label>
     <br>
     <input type="text" name= "todocat" id="todocat" placeholder="Enter category name...." class="form-control" value="">
   </div>
   <div class="form-group">
    <button type="submit" name="submit" id="" class="btn btn-info m-r-10"><i class="fa fa-check"></i>Add Category</button>
  </div>
</form>
</div>
</div>
</div>
</div>    




<div class="modal fade" id="viewmodal" role="dialog">
 <div class="modal-dialog" style="max-width: 700px;" >

  <div class="modal-content" style="border:0px;">
   <div class="modal-header" style="border-radius: 0px;padding: 1.5rem;">
    <label class="modal-title" style="font-size: 20px;color: white">Task Preview</label>
    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1; text-shadow: unset;">&times;</button>
  </div>
  <div class="modal-body" style="padding: 1.5rem;">
   
     <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
     <div class="Loader"></div>

     <div class="row">
       <div class="col-md-12" style="display: flex;">
        <span class="fa fa-credit-card" style="font-size: 20px;padding-right:20px"></span>
        <h3 id="vtitle_pre" style="color: black;word-break: break-all;width: 100%"></h3>
      </div>
      <div class="col-md-12" id="vititle" style="padding-left: 50px;">

        <textarea class="form-control" style="display:none;font-size: 22px;background: none;border: none;color: black;width: 94%;" id="vtitle"></textarea><br>
        <a href='#' class='vtitle green-button' id='vtitlesave' style='display:none;'><i class="fa fa-check" aria-hidden="true"></i></a> 
        <a href='#' class='vtitle' style='display:none;float:none;' id='vtitleclose' ><span class='fa fa-times red-close-button'></span></a>
      </div>
    </div>

    <hr>


    <div class="row">
     <div class="col-md-12" style="display: flex;">
      <span class="fa fa-file-text" style="font-size: 23px;padding-right:20px" ></span>
      <h3>Description <strong><a href="#" title="edit" class="fa fa-pencil-square vdescedit" ></a></strong></h3>
    </div>
    <div style="padding-left: 50px;" id="vdescarea" >
      <p id="vdesc" style="word-break: break-all;"></p>
      <div class="vtxtarea" style="display: none;" >
       <textarea class="textdesc form-control" style="width: 94%;" rows="8" name="vdesc"></textarea>
     </div>
     <br>
     <a href='#' class='vdesc green-button' id='vdescsave' style='display:none;'><i class="fa fa-check" aria-hidden="true"></i></a> 
     <a href='#' class='vdesc' style='display:none;float:none;' id='vdescclose' ><span class='fa fa-times red-close-button'></span></a>
   </div>
 </div>

 <hr>
 <div style="margin-bottom: inherit;" class="form-group row">
  <div class="col-md-6">
   <div style="display: flex;">
    <span class="fas fa-clock-o" style="font-size: 23px;padding-right:20px" ></span>
    <h3>Due Date 
      <strong><a href="#" title="edit" id='vddateedit' class="fa fa-pencil-square" ></a></strong>
    </h3>
  </div>
  <div style="padding-left: 40px;">

    <strong><h3 style="font-size: 25px;"  id="vddate" ></h3></strong>

    <input style="display:none;" type="text" class="form-control mb-2" placeholder="Start Date" name="selddate" id="selddate" />

    <br>
    <a href='#' class='vddate green-button' id='vddatesave' style='display:none;'><i class="fa fa-check" aria-hidden="true"></i></a> 
    <a href='#' class='vddate' style='display:none;float:none;' id='vddateclose' ><span class='fa fa-times red-close-button'></span></a>
  </div>
</div>

<div class="col-md-6">
 <div style="display: flex">
  <span class="fa fa-user-circle-o" style="font-size: 23px;padding-right:20px" ></span>
  <h3>Assigned User</h3>
</div>
<div id="assigntoUser" style="padding-left: 25px;display: flex;">
</div>
</div>
</div>

<hr>
<div style="margin-bottom: inherit;" class="form-group">

  <div class="row">
   <div class="col-md-12" style="display: flex;">
    <span class="fa fa-comments" style="font-size: 23px;padding-right:20px" ></span>
    <h3>Activity</h3>
  </div>

  <div class="col-md-12 row">
    <input type="hidden" name="tid" id="tid" value="">
    <div class="col-md-11" style="display: flex;">
      <img title="<?= $_SESSION["flname"]; ?> (<?= $_SESSION["UserName"]; ?>)" class="profile_img_self" style="border-radius: 50%;height: 36px;width: 36px;" src="">
      <textarea  id="comment" class="form-control" placeholder="Write a comment…" style="padding: 5px;margin-left: 8px;" name="comment"></textarea>
    </div>
    <div class="col-md-1">
      <button name="btncomment"  id="post" class="btn btn-info">Post</button>
      <label class="switch switch_status" style="float: none" title="Comment Sync"> 
        <input id="comment_sync" name="comment_sync" value="1" type="checkbox">
        <span class="slider round_switch"></span> 
      </label>
    </div>
  </div>

</div>

<div><div class="activeload" style="display: none"></div></div>
<div style="position: relative;">
  <div id="cmtbody"></div>
</div>
</div>


</div>
</div>
</div>
</div>




<div class="modal fade" id="closemodal" role="dialog">
	<div class="modal-dialog" style="max-width: 700px;" >

		
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Task Detail</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="padding: 1.5rem;">
				<form class="form-horizontal" autocomplete="off" method="post">
					<input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
					<div class="Loader"></div>
					<div style="margin-bottom: inherit;" class="form-group">
						<div class="row">
							<div>
								<span class="fa fa-credit-card" style="font-size: 20px;padding: 14px 7px 7px 7px" ></span>
							</div>
							<div id="vititle" style="width: 94%;">
								<h2  id="ctitle" ></h2>
							</div>
						</div>
						<br>
					</div>
					<div style="margin-bottom: inherit;" class="form-group">
						<div class="row">
							<div>
								<span class="fa fa-align-justify" style="font-size: 23px;padding: 7px 7px 7px 7px" ></span>
							</div>
							<div style="width: 94%;margin-top: 8px;" id="vdescarea" >
								<h3 style="font-size: 18px;">Description</h3>
								<p  id="cdesc" ></p>
							</div>
						</div>
						<br>
					</div>
					<div style="margin-bottom: inherit;" class="form-group">
						<div class="row">
							<div>
								<span class="fas fa-hourglass-start" style="font-size: 23px;padding: 7px 7px 7px 7px" ></span>
							</div>
							<div style="width: 94%;margin-top: 8px;" >
								<h3 style="font-size: 18px;">Due Date</h3>
								<strong><h2 style="font-size: 25px;"  id="cddate" ></h2></strong>
							</div>
						</div>
						<br>
					</div>
					<div style="margin-bottom: inherit;" class="form-group">
						<div class="row">
							<div>
								<span class="fa fa-tasks" style="font-size: 23px;padding: 7px 7px 7px 7px" ></span>
							</div>
							<div style="width: 94%;margin-top: 8px;" >
								<h3 style="font-size: 18px;">Activity</h3>
							</div><br><br>
						</div>
						<div><div class="activeload" ></div></div>
						<div>
							<div id="ccmtbody">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- footer -->
<!-- ============================================================== -->
<?php include 'footer.php'; ?>
<!-- ============================================================== -->
<!-- End footer -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->

<?php include 'scripts.php'; ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>
<script type="text/javascript">

  $("#todoDesc").ckeditor();
  $("textarea[name='vdesc']").ckeditor();

  function task_reload(){
    $("#tasks").html('<h3>Wait.....</h3>');
    $.ajax({
      url:'<?= base_url?>/todo_task?task_reload',
      success:function(data){
        $("#tasks").html(data);
        task_sort();

        var urlParams = new URLSearchParams(window.location.search);
        var taskid = urlParams.get("task");
        if(taskid){
          $("#"+taskid).find("#li").trigger("click");
        }

      }
    });  
  }

  function task_sort() {
    $('.sortable').sortable({
     connectWith: ".sortable",
     update: function (e, ui) {
      var cat_id = $(ui.item).parent(".sortable").data("id");
      var task_id = $(ui.item).data("id");

      var data=[];
      $(ui.item).parent(".sortable").children().each(function(){
        if($(this).attr("data-id")!= undefined){
          data.push($(this).attr("data-id"));
        }
      });

      $.ajax({
       url:'?catstatus',
       data:{"cat_id":cat_id,"task_id":task_id,"data":data},
       type:"post",
       dataType:"json",
       success:function(data){
        if(data.success == "0")
        {
         swal("Oops...",data.error, "error");
       }

     }
   });
    }

  }).disableSelection();

    $( ".sortable_row").sortable({ 
     forcePlaceholderSize: true,
     update: function(event, ui){
      var data=[];
      $("#tasks").children().each(function(){
       if($(this).attr("card-id")!= undefined){
        data.push($(this).attr("card-id"));
      }
    });

      $.ajax({
       url:'?update_card_position',
       data:{"data":data},
       type:"post",
       dataType:"json",
       success:function(data){


       }
     });



    }
  }).disableSelection();;

  } 

  $(document).ready(function() {

    task_reload();

    $("select").select2();

    $('#selddate,#dueDate').datepicker({
      dateFormat: "yy-mm-dd",
      minDate: 0
    });
    $(document).on("click",".SubmitButton",function(){
     id = $(this).attr("data-id");
     $.ajax({
      url:'?SubmitButton',
      data:{taskid:id},
      type:"post",
      dataType:"json",
      success:function(data){
       if(data.response){
        swal("Successful","Your task is successfully submited","success");
      }
      if(data.error)
      {
        swal("Oops...",data.error, "error");
      }
    }
  });            
   });

    $('.catCancel').on('click',function(){
     $('#addcat').trigger("reset");

   });


    $("#choose_color input[type='radio']").change(function(){
     color = $(this).val();
     $("#preview").css("background",color);
     $("#colorpre").val(color);
     $("#myModal .modal-header").attr("style","background:"+color+"!important");
   });


    $("input[name='colorcode']").on("change",function(){
     $("#preview").css("background",$(this).val());
     $("#myModal .modal-header").attr("style","background:"+$(this).val()+"!important");
     return false;
   });



    }); //remove

  $(window).bind("onload", function() {
    clear();
  });

  $(".profile_img_self").attr("src",$(".profile-pic img").attr("src"));


  // $('.textdesc').wysihtml5();

  $("#vddateedit").on("click",function(){
    $(this).hide();
    $("#vddate").hide();
    $("#selddate").val($("#vddate").attr("data-val"));
    $("#selddate").show();
    $(".vddate").show();
    return false;
  });

  $("#vddateclose").on("click",function(){
    $("#vddateedit").show();
    $("#vddate").show();
    $("#selddate").hide();
    $(".vddate").hide();
    return false;
  });

  $("#vddatesave").on("click",function(){
    var ddate = $("#selddate").val();
    var taskid = $(this).data("task");
    $.ajax({
     url:'<?php echo EXEC; ?>ExecTodo.php?updateinfo',
     data:{"dueDate":ddate,"id":taskid},
     type:"post",
     dataType:"json",
     success:function(data){
      if(data.error)
      {
       swal("Oops...",data.error, "error");
     }
     else if(data.resonse)
     {
       $("#vddate").attr("data-val",ddate);
       $("#vddate").html(moment(ddate).format('MMM D, YYYY'));
       $("#vddateedit").show();
       $("#vddate").show();
       $("#selddate").hide();
       $(".vddate").hide();
       return false;
     }
   }
 });
  });


  $("#vtitlesave").on("click",function(){
    var title = $("#vtitle").val();
    var taskid = $(this).data("task");
    $.ajax({
      url:'<?php echo EXEC; ?>ExecTodo.php?updateinfo',
      data:{"todoTitle":title,"id":taskid},
      type:"post",
      dataType:"json",
      success:function(data){
        if(data.error)
        {
          swal("Oops...",data.error, "error");
        }
        else if(data.resonse)
        {
          $(".vtitle").hide();
          $("textarea#vtitle").hide();
          $("#vtitle").val(title);
          $("#vtitle_pre").text(title);
          $("#vtitle_pre").show();
          $("textarea#vtitle").css("border","none");
          return false;
        }
      }
    });                                    
  });

  $(document).on("click","#vdescsave",function(){
    var desc = $("textarea[name='vdesc']").val();
    var taskid = $(this).data("task");
    $.ajax({
      url:'<?php echo EXEC; ?>ExecTodo.php?updateinfo',
      data:{"todoDesc":desc,"id":taskid},
      type:"post",
      dataType:"json",
      success:function(data){
        if(data.error)
        {
          swal("Oops...",data.error, "error");
        }
        else if(data.resonse)
        {
          $("#vdesc").html(desc);
          $(".vdescedit").show();
          $(".vdesc").hide();
          $(".vtxtarea").hide();
          $("#vdesc").show();
          return false;
        }
      }
    }); 
  });

  $('.vdescedit').on("click",function(){
    $(this).hide();
    $(".vtxtarea").show();
    $(".vdesc").show();
    var vdesc = $("#vdesc").html();
    $("#vdescclose").attr("vdesc",vdesc);
    $("textarea[name='vdesc']").val(vdesc);
    // $('iframe').contents().find('.wysihtml5-editor').html(vdesc);
    $("#vdesc").hide();
    return false;
  });

  $("#vdescclose").on("click",function(){
    $(".vdescedit").show();
    $(".vdesc").hide();
    $(".vtxtarea").hide();
    $("#vdesc").html($(this).data("vdesc"));
    $("#vdesc").show();
    return false;
  });



  $("#vtitle_pre").on("click",function(){

    if($("#vtitle_pre").attr("click-event")=="false"){
     return false;
   }

   $("textarea#vtitle").show();
   $("#vtitle_pre").hide();
   $("#vtitleclose").attr("data-title",$("#vtitle").val());
   $("textarea#vtitle").css("border","1px solid black");
   var id = $("textarea#vtitle").data("task");
   $(".vtitle").show();
   return false;
 });

  $("#vtitleclose").on("click",function(){
    $(".vtitle").hide();
    $("textarea#vtitle").hide();
    $("#vtitle_pre").show();
    $("textarea#vtitle").css("border","none");
    $("textarea#vtitle").val($(this).data("title"));
    return false;
  });

  $("#comment").on("keyup",function(){
    $("#post").show();
    if($(this).val() == "")
    {
     $("#post").hide();
   }
 });

  $("#post").on("click",function(){
    var cmt = $("#comment").val();
    var taskid = $("#tid").val();
    $.ajax({
     url:"?addcmt",
     type:"post",
     data:{"taskid":taskid,"cmt":cmt},
     dataType:"json",
     success:function(data){
      if(data.success == "0")
      {
       swal("Oops...",data.error, "error");
       return false;
     }
     else
     {
       $("#comment").val("");
       $("#post").hide();
       $(".activeload").show();
       ajaxcomment(data.cmtdata);
       $(".activeload").hide();
       return false;
     }
   }
 });
    return false;
  });

  var comment_sync;

   $('#viewmodal').on('hidden.bs.modal', function (e) {

    $('.del_comment').parents(".commentbody-rev").remove();
    $('.comment').removeClass('edit_comment');
    $('.commentbody-rev').removeClass('new_comment');
    clearInterval(comment_sync);
  });

  $('#cmtbody').on('click', function (e) {
    $('.del_comment').parents(".commentbody-rev").remove();
    $('.comment').removeClass('edit_comment');
    $('.commentbody-rev').removeClass('new_comment');
  })

  $("#comment_sync").click(function(){

    if($(this).prop('checked')==true){

      comment_sync = setInterval(function(){
        var taskid = $("#tid").val();
        var comment=[];
        $("#cmtbody .comment").each(function(){
          var id = $(this).attr('comment-id');
          comment.push(id);

        });
        comment = comment.toString();
        $.ajax({
          url:"?loadcmt",
          type:"post",
          data:{"taskid":taskid,"comment":comment},
          dataType:"json",
          success:function(data){

            if(data.cmtdata.length>0){

              ajaxcomment(data.cmtdata);
              setTimeout(function(){
                 $.each(data.cmtdata, function (index, val) {
                  $("textarea[comment-id='"+val.id+"']").parents(".commentbody-rev").addClass('new_comment');
                });
              },1000);
              
            }

            if(data.cmt_del_data.length>0){
             $.each(data.cmt_del_data, function (index, val) {
             if($("textarea[comment-id='"+val.id+"']").parents(".commentbody-rev").find('.del_comment').length<1){
              $("textarea[comment-id='"+val.id+"']").parents(".commentbody-rev").prepend('<div class="del_comment"></div>');
              }
            });
           }

           if(data.cmt_edit_data.length>0){
             $.each(data.cmt_edit_data, function (index, val) {
              $("textarea[comment-id='"+val.id+"']").text(val.comment);

              if($("textarea[comment-id='"+val.id+"']").parents(".commentbody-rev").hasClass('new_comment')!=true){
                $("textarea[comment-id='"+val.id+"']").addClass('edit_comment');
              }

            });
           }
           return false;

         }
       });
      },2000);

    }else{
      clearInterval(comment_sync);
    }
  });


  $(document).on("click","#editcat",function(){
        var parent = $(this).parents(".status-card");   //added
        var data = $(this).data("id");
        var span =parent.find(".card_header_text").text();      
        var trimStr = $.trim(span);
        $("textarea[name=catin"+data+"]").val(trimStr);
        // parent.find(".catname"+data).find("div").toggle();
        parent.find(".card_header_text").toggle();
        $("div[name=catin"+data+"]").toggleClass('show_cat_edit');
        // $("div[name=catin"+data+"]").toggle();
      });

  $(document).on("click","#delcat",function(){



    var ui = $(this).attr("data-id");


    swal({
     title: "Are you sure?",
     text: "Archive: 'Move all task to Archive List'\nDelete: 'Permanent delete can not be recover.",
     icon: "warning",
     buttons: {
      Cancel: 'Cancel',
      Archive:'Archive',
      Delete: 'Delete',
    },
  }).then((value)=>{
    switch (value) {

     case 'Archive':
   $.ajax({
       url:"?delcat",
       type:"post",
       data:{"catid":ui},
       dataType:"json",
       success:function(data){
        if(data.success == "0")
        {
         swal("Oops...",data.error, "error");
       }
       else
       {
        task_reload();
      }
    }
  });
     break;

     case 'Delete':
    $.ajax({
       url:"?delcat_all",
       type:"post",
       data:{"catid":ui},
       dataType:"json",
       success:function(data){
        if(data.success == "0")
        {
         swal("Oops...",data.error, "error");
       }
       else
       {
        task_reload();
      }
    }
  });
     break;

     case 'Cancel':
     return false;
     break;


   }
   

 });
 
 });

  $(document).on("click",".savebtn",function(){
    var parent = $(this).parents(".status-card")
    var input = parent.find("#catin").val();
    var id = $(this).data("id");
    $.ajax({
     url:"?updcatname",
     type:"post",
     data:{"newcat":input,"id":id},
     dataType:"json",
     success:function(data){
      if(data.success == "0")
      {
       swal("Oops...",data.error, "error");
     }
     else
     {  
       parent.find(".card_header_text").text(input);
       parent.find(".card_header_text").show();
       parent.find("div[name=catin"+id+"]").removeClass('show_cat_edit');
       // parent.find(".catname"+id).find("div").toggle();
     }
   }
 });
  });

  $(document).on('click','.todo_archive',function(){
    $("#historybody").html("");
    $(".hsy_sub").toggle(); 
    $.ajax({
     url:'?gethistory',
     dataType:"json",
     success:function(data){
      if(data.response.length > 0)
      {
        var html ='<div class="modal-header"><h4 class="modal-title">Todo Archive</h4><div><button type="button" class="close todo_archive" data-dismiss="modal">×</button><a href="#" title="Delete All" class="btn btn-sm btn-danger pull-left" id="del_archive_li"><i class="fa fa-trash"></i></a></div></div>';

        $.each(data.response, function (index, val) {

          if(val.userimg){
           dp = "<img src='<?= base_url?>/assets/userimage/"+val.userimg+"' title='"+ val.firstname+" "+val.lastname+"' style='font-size: 12px;border-radius: 50%;height: 32px;width: 32px;'>";
         }else{
           dp = "<img src='<?= base_url?>/assets/images/noimage.png' title='"+ val.firstname+" "+val.lastname+"' style='font-size: 12px;border-radius: 50%;height: 32px;width: 32px;'>";
           
         }

         html+= "<li class='todo_archive_li row'><div class='todo_archive_1' >"+dp+"<input class='todo_archive_checkbox' type='checkbox' value='"+val.id+"' ></div><div class='todo_archive_2'><span><strong>"+val.firstname+" "+val.lastname+"</strong> closed <a href='#' style='text-decoration:underline;word-break: break-all;' data-id='"+val.id+"' id='historytsk' >"+ val.todoTitle +"</a> from "+ val.catname+".</span><br><p style='font-size: 11px;text-align:right'>"+moment.utc(val.closeddate).local().format('MMM-D-YYYY')+" at "+moment.utc(val.closeddate).local().format('HH:mm')+"</p></div><div class='todo_archive_3'><a href='#' class='btn btn-sm btn-success restore_task' title='Restore' data-id='"+val.id+"' ><i class='fa fa-history'></i></a><a href='#' title='Delete' class='btn btn-sm btn-danger del_task' data-id='"+val.id+"'><i class='fa fa-trash'></i></a></div><hr></li>";
       });
        $("#historybody").append(html);
      }
      else
      {
       $("#historybody").html('<div style="text-align:center;font-weight:bold;padding: 20px;"><img class="img-fluid m-auto" src="https://mysunless.com/crm/assets/images/no_data.png"></div>');
     }
   }
 });
  });

  $(document).on('click','#del_archive_li',function(){

    swal({
     title: "Are you sure?",
     text: "All: 'Delete all tasks' \n Selected: 'Delete selected task.' ",
     icon: "warning",
     buttons: {
      Cancel: 'Cancel',
      DeleteAll:'All',
      Delete: 'Selected',
    },
  }).then((value)=>{
    switch (value) {

     case 'Delete':
     var del_id=[];
     $(".todo_archive_checkbox").each(function(){
      if($(this).is(':checked')){
        del_id.push($(this).val());
      }
    });

     if(del_id.length>0){
       $.ajax({
        url:'?del_task',
        data:{id:del_id.toString()},
        type:'post',
        dataType:'json',
        success:function(data){
          if(data.response){
            swal('Successful deleted.');
            $.each(del_id,function(k,v){
              $(".todo_archive_checkbox[value='"+v+"']").parents('li').delay('1000').fadeOut();
            });

          }else{
            swal('Something went wrong. Please try again.');
          }
        }

      });
     }else{
      swal('No task was selected.');
    }
    break;

    case 'DeleteAll':
    var del_id=[];
    $(".todo_archive_checkbox").each(function(){
      del_id.push($(this).val());
    });


    $.ajax({
      url:'?del_task',
      data:{id:del_id.toString()},
      type:'post',
      dataType:'json',
      success:function(data){
        if(data.response){
          swal('Successful deleted.');
          $("#historybody").html('<div style="text-align:center;font-weight:bold;padding: 20px;"><img class="img-fluid m-auto" src="https://mysunless.com/crm/assets/images/no_data.png"></div>');
        }else{
          swal('Something went wrong. Please try again.');
        }
      }

    });
    
    break;


    case 'Cancel':
    return false;
    break;

  }
});


});


  $(document).on('click','.restore_task',function(e){
    e.preventDefault();
    var div = $(this);
    var id = $(this).attr('data-id');
    $.ajax({
      url:'?restore_task',
      data:{id:id},
      type:'post',
      dataType:'json',
      success:function(data){
        if(data.response){
          swal('Successful Restored.');
          div.parents('li').delay('1000').fadeOut();
          task_reload();
        }else{
          swal('Something went wrong. Please try again.');
        }
      }

    });
  });

  $(document).on('click','.del_task',function(e){
    e.preventDefault();
    var div = $(this);
    var id = $(this).attr('data-id');

    swal({
     title: "Are you sure?",
     text: "Once deleted, it will permanently delete from Archive List",
     icon: "warning",
     buttons: true,
   }).then((willDelete)=>{
     if(willDelete)
     {
       $.ajax({
        url:'?del_task',
        data:{id:id},
        type:'post',
        dataType:'json',
        success:function(data){
          if(data.response){
            swal('Successful deleted.');
            div.parents('li').delay('1000').fadeOut();
          }else{
            swal('Something went wrong. Please try again.');
          }
        }

      });
     }

   });


 });


 

  $(document).on("click","#li,#historytsk",function(e){
   var div = $(this);

   $("#vtitleclose").trigger('click');
   $("#vdescclose").trigger('click');
   $("#vddateclose").trigger('click');

   $("#cmtbody").html("");
   $("#comment").val("");
   $("#post").hide();

   $("#comment_sync").parent().show();
   $("#comment_sync").prop('checked',false);
   clearInterval(comment_sync);

   $(".Loader").show();
   var id = $(this).data("id");
   $.ajax({
    url:"?ViewTodo",  
    method:"POST",  
    data:{id:id},  
    dataType:"json",  
    success:function(data){

     $("ul.wysihtml5-toolbar").css("width","94%");
     new_str = data.resonse.todoTitle.charAt(0).toUpperCase() + data.resonse.todoTitle.substr(1).toLowerCase();

     new_desc = data.resonse.todoDesc;

     var id = "<?= $_SESSION['UserID']; ?>";

     if(data.resonse.createdfk==id){
      $("#vddateedit").show();
    }else{
      $("#vddateedit").hide();
    }

    $('#vtitle').val(new_str);
    $('#vtitle_pre').text(new_str);
    $("#vtitle,#vdesc,#vddate").attr("data-task",data.resonse.id);
    $(".vtitle,.vdesc,.vddate").attr("data-task",data.resonse.id);
    $('#vdesc').html(new_desc);
    $('#vddate').html(moment(data.resonse.newduedate).format('MMM D, YYYY'));
    $("#vddate").attr("data-val",moment(data.resonse.newduedate).format('MM/D/YYYY'));
    $('#tid').val(data.resonse.id);
    $("#seldate").hide();

    if(data.assigntoUser){
      var users="";
      $.each(data.assigntoUser, function (index, val) {


        if(val.userimg){
          img= "<?= base_url?>/assets/userimage/"+val.userimg+"";
        }else{
         img= "<?= base_url?>/assets/images/noimage.png";
       }

       users+="<span style='display: flex;flex-direction: column;align-items: center;'><img src='"+img+"' style='border-radius: 50%;height: 50px;width: 50px;margin:5px'><span style='font-size: 10px;'>"+ val.firstname+" "+val.lastname+"</span></span>";
     });
      $("#assigntoUser").html(users);
    }

    if(data.cmtdata.length > 0)
    {

      ajaxcomment(data.cmtdata);
      $(".activeload").hide();
    }
    else
    {
     $("#cmtbody").html("");
   }

   if(e.target.id=="historytsk"){
     $(".vdescedit").hide();
     $("#vddateedit").hide();
     $("#comment").hide();
     $("#selddate").hide();
     $("#comment").prev("img").hide();
     $(".cmtdel").hide();
     $(".cmtedit").hide();
     $("#ccmtbody").html("<h4 style='margin-left: 26px;'>Nothing to show...</h4>");
     $("#vtitle_pre").attr("click-event","false");
     $("#comment_sync").parent().hide();
   }else{
     $("#comment_sync").prev().show();
     $(".vdescedit").show();
     $("#comment").show();
     $("#comment").prev("img").show();
     $(".cmtdel").show();
     $(".cmtedit").show();
     $("#vtitle_pre").attr("click-event","true");
   }

   $("#viewmodal").find(".modal-header").attr("style","background:"+data.resonse.colorcode+"!important");
   $('#viewmodal').modal('show');
   $(".Loader").hide();
   $(".comment").focus();
 }
});
 });


  function ajaxcomment(cmtdata){

   var id = "<?= $_SESSION['UserID']; ?>";

   $.each(cmtdata, function (index, val) {
    val.firstname = val.firstname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
    val.lastname = val.lastname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
    var dp = "";

    if(val.createdfk == id)
    {
      if(val.userimg){
        dp = "<img src='<?= base_url?>/assets/userimage/"+val.userimg+"' title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-right: 10px;width: 32px;'>";
      }else{
        dp = "<span title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-right: 10px;width: 32px;padding: 7px;background-color: #03a9f3;color: white;text-align:center;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span>";
      }



      var html = "<div class='commentbody'><div class='comment_head'>"+dp+"<div class='comment_user_detail'><strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment.utc(val.createddate).local().format('MMM-D-YYYY')+" at "+moment.utc(val.createddate).local().format('HH:mm')+"</span></div></div><div class='commentbox'><div class='commentarrow' style='margin-left: 10px;'></div><textarea comment-id='"+val.id+"' onkeyup='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+" </textarea><div style='width:10%;float:right;'><a href='#' class='cmtdel fa fa-trash-o text-danger m-1' id='cmtdel"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='font-size: 24px;'></a> <a href='#' class='cmtedit fa fa-pencil text-info m-1' id='cmtedit"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='font-size: 24px;'></a> <a href='#' class='cmtsave fa fa-floppy-o m-1 text-success' id='cmtsave"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='display:none;font-size: 26px;'></a> <a href='#' class='cmtclose fa fa-close fa-sm m-1' style='display:none;font-size:24px;' id='cmtclose"+val.id+"' data-id='"+val.id+"'></a></div></div></div></div>";
    }
    else
    {
      if(val.userimg){
        dp = "<img src='<?= base_url?>/assets/userimage/"+val.userimg+"' title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-left: 10px;width: 32px;'>";
      }else{
       dp = "<img src='<?= base_url?>/assets/images/noimage.png' title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-left: 10px;width: 32px;'>";
               
              }


              var html = "<div class='commentbody-rev'><div class='comment_head'>"+dp+"<div class='comment_user_detail'><strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment.utc(val.createddate).local().format('MMM-D-YYYY')+" at "+moment.utc(val.createddate).local().format('HH:mm')+"</span></div></div><div class='commentbox'><div class='commentarrow' style='margin-right: 15px;'></div><textarea comment-id='"+val.id+"' onkeyup='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+" </textarea></div></div>";
            }
            $("#cmtbody").prepend(html);
          });
 }



 function textAreaAdjust(o){
   o.style.height = "1px";
   o.style.height = (25+o.scrollHeight)+"px";
 }

 $(document).on("click",".cmtedit",function(){
   var cmt = $(this).data("id");
   $(this).hide();
   $("textarea#comment"+cmt).removeAttr("disabled");
   $("textarea#comment"+cmt).focus();
   var incmt = $("textarea#comment"+cmt).val();
   $("#cmtclose"+cmt).attr("data-cmt",incmt);
   $("#cmtsave"+cmt).show();
   $("#cmtclose"+cmt).show();
   return false;
 });

 $(document).on("click",".cmtclose",function(){
   var cmtid = $(this).data("id");
   $(this).hide();
   $("textarea#comment"+cmtid).attr("disabled","");
   $("textarea#comment"+cmtid).val($(this).data("cmt"));
   $("#cmtedit"+cmtid).show();
   $("#cmtsave"+cmtid).hide();
   $("#cmtclose"+cmtid).hide();
   return false;

 });

 $(document).on("click",".cmtsave",function(){
   var taskid = $(this).data("task");
   var cmtid = $(this).data("id");
   var cmt = $("textarea#comment" + cmtid).val();
   if(cmt == "")
   {

    swal("Please input something to save","", "error");
    return false;
  }
  else
  {
    $.ajax({
     url:"?cmtupdate",
     type:"post",
     data:{"cmtid":cmtid,"cmt":cmt},
     dataType:"json",
     success:function(data){
      if(data.success == "0")
      {
       swal("Oops...",data.error, "error");
     }
     else
     {
       $("textarea#comment"+cmtid).attr("disabled","");
       $("#cmtedit"+cmtid).show();
       $("#cmtsave"+cmtid).hide();
       $("#cmtclose"+cmtid).hide();
       return false;
     }
   }
 });

  }
});

 $(document).on('click','.cmtdel',function(e){
   e.preventDefault();
   var div = $(this);
   var id = $(this).data("id");
   var taskid = $(this).data("task");
   swal({
    title: "Are you sure?",
    text: "Once Deleted, you will lost this!",
    icon: "warning",
    buttons: true,
  }).then((willDelete)=>{
    if(willDelete)
    {
     $.ajax({
      url:"?delcmt",  
      method:"POST",  
      data:{id:id},  
      dataType:"json",  
      success:function(data){
       if(data.success == "0")
       {
        swal("Oops...",data.error, "error");
      }
      else
      {
       div.parents('.commentbody').remove();

       return false;
     }       
   }
 });
   }
   else
   {
     return false ;
   }
 });
});

 $(document).ready(function() {
   $(document).on('click','#addtask,#addtaskself',function(e){

    if(e.target.id=='addtaskself'){
     $("#asigntoself").show();
     $("#asignto").hide();
   }else{
     $("#asigntoself").hide();
     $("#asignto").show();
   }

   $("#todoSub").html("Add Task");
   $(".updatetitle").html("Add Task");
   $('#NewEvent')[0].reset();
   $('#id').val('new');
   var catstatus = $(this).data("id");
   $(".choose_blue").trigger('click');
   // $("ul.wysihtml5-toolbar").css("width","");
   $("#cid").val(catstatus);
 });


   $("#NewEvent").validate({
    ignore: ":hidden:not(textarea)",
    rules: {
     todoTitle: {
      required: true,},
      todoDesc: {
       required: true,},
       dueDate: {
        required: true,}
      },
      messages: {
        todoTitle: {
         required: "Please enter title"},
         todoDesc: {
          required: "Please enter description"},
          dueDate: {
           required: "Please select date"},
         },
         errorPlacement: function( label, element ) {
           if( element.attr( "name" ) === "todoDesc" )
           {
            element.parent().append( label );
          }
          else
          {
            label.insertAfter(element);
          }
        },
        submitHandler: function(){
         var data = $("#NewEvent").serialize();
         data= data + "&LoginAction=Login";
         jQuery.ajax({
          dataType:"json",
          type:"post",
          data:data,
          url:'<?php echo EXEC; ?>ExecTodo.php',
          success: function(data)
          {
           if(data.resonse)
           {
            $("#resonse").show();
            $('#resonsemsg').html('<span>'+data.resonse+'</span>');
            $( '#NewEvent' ).each(function(){
             this.reset();
           });
            $(".Loader").hide();
            $("#myModal").modal('hide');
            task_reload();
          }
          else if(data.error)
          {
            $("#error").show();
            $('#errormsg').html('<span>'+data.error+'</span>');
            $(".Loader").hide();
            swal("Oops...",data.error, "error");
          }


        }
      });
       }
     });


   $("#addcat").validate({
    rules: {
     todocat: {
      required: true,}
    },
    messages: {
      todocat: {
       required: "Please enter category name"}
     },
     submitHandler: function() {
       var data = $("#todocat").val();

       jQuery.ajax({
        dataType:"json",
        type:"post",
        data:{"catname":data},
        url:'?addcategory',
        success: function(data){
         if(data.success == "0")
         {
          swal("Oops...",data.error, "error");
        }
        else
        {
          $("#addcatmodal").modal("hide");
          task_reload();
        }
      }
    });
     }
   });

   $(document).on('click','.edit_data', function(e){


    if(e.target.id=='editbuttonself'){
     $("#asigntoself").show();
     $("#asignto").hide();
   }else{
     $("#asigntoself").hide();
     $("#asignto").show();
   }

   $("#todoSub").html("Update Task");
   $(".updatetitle").html("Update Task");
   var id = $(this).data("id")
   $.ajax({
     url:"?EditViewTodo",
     method:"POST",  
     data:{id:id},  
     dataType:"json",  
     success:function(data){
      var asignto = data.resonse.asignto.split(',');
      $("#asignto select").val(asignto).change();

      $('#todoTitle').val(data.resonse.todoTitle);
      $("#todoDesc").val(data.resonse.todoDesc);
      // $('iframe').contents().find('.wysihtml5-editor').html(data.resonse.todoDesc);
      $('#dueDate').val(data.resonse.newduedate);
      $('#id').val(data.resonse.id);
      $('#cid').val(data.resonse.catstatus);
      $("#colorpre").val(data.resonse.colorcode);
      $("#preview").css("background",data.resonse.colorcode);
      $("#myModal .modal-header").attr("style","background:"+data.resonse.colorcode+"!important");
      $('#myModal').modal('show');
    }
  });
 });

   $(document).on('click','.deleteButton',function(e){
    e.preventDefault();
    var id = $(this).data("id");

    swal({
     title: "Are you sure?",
     text: "Archive: 'Move to Archive List'\nDelete: 'Permanent delete can not be recover.",
     icon: "warning",
     buttons: {
      Cancel: 'Cancel',
      Archive:'Archive',
      Delete: 'Delete',
    },
  }).then((value)=>{
    switch (value) {

     case 'Archive':
     $.ajax({
       url:"?DelteViewTodo",  
       method:"POST",  
       data:{id:id},  
       dataType:"json",  
       success:function(data)
       {
        if(data.success == "0")
        {
         swal("Oops...",data.error, "error");
       }
       else
       {
         task_reload();
       }
     }
   });
     break;

     case 'Delete':
     $.ajax({
      url:'?del_task',
      data:{id:id},
      type:'post',
      dataType:'json',
      success:function(data){
        if(data.response){
          swal('Successful deleted.');
          task_reload();

        }else{
          swal('Something went wrong. Please try again.');
        }
      }

    });
     break;

     case 'Cancel':
     return false;
     break;


   }
   

 });


});

 });

</script>

</body>
</html>