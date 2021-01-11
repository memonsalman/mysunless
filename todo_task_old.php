<?php
require_once('function.php');
$uid = $_SESSION["UserID"];

$adminsql  = $db->prepare("select adminid from users where id = :id");
$adminsql->bindParam(":id",$uid);
$adminsql->execute();
$admin = $adminsql->fetch();

$cat = $db->prepare("select * from todocat where (createdfk = :adminid OR createdfk = :id) AND status = '0' ORDER BY `position` ");
$cat->bindParam(":adminid",$admin['adminid']);
$cat->bindParam(":id",$uid);
$cat->execute();
$cats = $cat->fetchAll();

if($admin['adminid'] == "")
{
  $admin['adminid'] = $_SESSION['UserID'];   
}

$sql = $db->prepare("SELECT users.userimg,todo.*,DATE_FORMAT(todo.newduedate, '%b-%d-%Y') as newduedate FROM `todo` join users on todo.createdfk=users.id WHERE (todo.createdfk = :id or todo.asignto = :id) AND todo.catstatus = '1' AND todo.status = '0' ORDER BY todo.position ASC, todo.id DESC");
$sql->bindParam(":id",$uid);
$sql->execute();
$SelfTodo = $sql->fetchAll(PDO::FETCH_ASSOC);
$SelfTodo = array_filter($SelfTodo);

?>
<!-- Todo_task.php page -->

<div class="status-card sortable_row_connect" id="sort">
 <div class="card-header">
  <span class="card-header-text">TODO (Self)</span>
</div>
<ul id="sortable1" data-id="todo" class="sortable connectedSortable ui-sortable">
  <?php
  if (!empty($SelfTodo)) 
  {   
   foreach ($SelfTodo as $taskRow) 
   {    
    if(!empty($taskRow["userimg"])){
     $profile_img_self = base_url."/assets/userimage/".$taskRow["userimg"];
   }else{
     $profile_img_self = base_url."/assets/images/noimage.png";
   }  
   ?>
   <li style="background: <?= $taskRow["colorcode"]; ?>" class="text-row ui-sortable-handle"  id="<?= $taskRow["id"]; ?>" data-id="<?= $taskRow["id"]; ?>"> 

     <div id="li" data-id="<?= $taskRow["id"]; ?>" style="color:white;width: 80%;display: inline-block;padding: 15px 10px;"><?= $taskRow["todoTitle"]; ?></div>


     <span style="float:right;padding: 15px 10px 15px 0px;" class="fa fa-close deleteButton" title="Close Task" data-id="<?= $taskRow["id"]; ?>"></span>

     <span style="float:right;padding: 15px 7px 15px 0px;" class="fa fa-edit edit_data" title="Edit Details" id="editbuttonself" data-id="<?= $taskRow["id"]; ?>"></span>
     <hr style="margin: 0px;">

     <div style="text-align: right;padding: 3px;" >
      <span title="Due Date" style="float:left;margin-left: 10px;color: white;font-size: 11px;" ><strong><?=  date('M d, Y',strtotime($taskRow["newduedate"])); ?> </strong></span>

      <span style="margin-bottom: 0px;color: white;font-size: 11px;" >
       <img src="<?= $profile_img_self ?>" class="profile_img">
     </span>
   </div>

 </li>
 <?php
}
}
else
{

}
?>
</ul>
<div class="card-footer">
  <center>
    <div title="Add New Task" id="addtaskself" title="Add task" data-id="todo" data-toggle="modal" data-target="#myModal" >
      <i class="fa fa-plus-circle"></i>
    </div>
  </center>
</div>
</div>

<?php
$inc = 0;
foreach ($cats as $keys) 
{   

 $inc++;
 ?>
 <div class="status-card sortable_row_connect" card-id="<?= $keys['id'] ?>">
  <div class="card-header">
   <div class="card_header card-header-text catname<?= $keys["id"] ?>">
    <div class="card_header_text" title="<?= $keys["catname"]; ?>">
     <?= $keys["catname"]; ?> 
   </div>
   <div class="card_header_button"> 
     <?php if($keys['createdfk']==$_SESSION['UserID']){ ?>

      <span class="fa fa-edit" title="Edit Category" id="editcat" data-id="<?= $keys["id"]; ?>"></span>
      <span  class="fa fa-close" title="Delete Category" id="delcat" data-id="<?= $keys["id"]; ?>"></span> 

    <?php } ?>
  </div>
</div>
<div class="catin" name="catin<?= $keys["id"] ?>" style='display: none;'>


  <textarea type="text" id="catin" name="catin<?= $keys["id"] ?>" class="form-control"></textarea> 

  <span style="float:right;margin-left: 7px;" class="fa fa-close catnameclose<?= $keys["id"]; ?>" title="close" id="editcat" data-id="<?= $keys["id"]; ?>"></span></span>
  <span style="float:right;" data-id="<?= $keys["id"]; ?>" class="fa fa-check savebtn"></span>
</div>
</div>
<ul id="<?= $keys["id"];  ?>" data-id="<?= $keys['id'];  ?>" class="sortable connectedSortable ui-sortable">
 <?php
 $cat = $keys["id"];
 $sql = $db->prepare("SELECT *,DATE_FORMAT(newduedate, '%b-%d-%Y') as newduedate FROM `todo` WHERE (createdfk = :id or FIND_IN_SET(:id,asignto) ) AND catstatus = :cat AND status = '0' order by position");
 $sql->bindParam(":cat",$cat);
 $sql->bindParam(":id",$uid);
 $sql->bindParam(":admin",$admin['adminid']);
 $sql->execute();
 $data = $sql->fetchAll();
 if(!empty($data))
 {
  foreach ($data as $key) 
  {   
   $asignto = explode(',',$key["asignto"]);

   $button = "";
   $hide = "";

   if (in_array($_SESSION['UserID'], $asignto) && $key["createdfk"] != $_SESSION['UserID']){
    $id = $key["createdfk"];
    $title = "Assigned By";
    $style = "direction: rtl;";
    $button = "SubmitButton"; 
    $hide = "display:none;";                          
  }else if (in_array($_SESSION['UserID'], $asignto) || $key["createdfk"] == $_SESSION['UserID']){

    $id = $key["asignto"];
    $style = "direction: ltr;";
    $title = "";
    $button = "deleteButton"; 
  }else{
    $id = $key["createdfk"];
    $style = "direction: ltr;";
    $title = "(Self)";
    $button = "deleteButton"; 
  }



  $userdata = $db->prepare("SELECT id,firstname,lastname,username,userimg FROM users WHERE id in ($id)");
  $userdata->execute();
  $udatas = $userdata->fetchAll();    
  $users = "";
  foreach ($udatas as $udata) {

    if(!empty($udata["userimg"])){
     $profile_img = base_url."/assets/userimage/".$udata["userimg"];
   }else{
     $profile_img = base_url."/assets/images/noimage.png";
   }

   $users.= "<span title='".$title." ".$udata['firstname']." ".$udata['lastname']."' style='margin: 0px;color: white;font-size: 11px;'>
   <img src='".$profile_img."' class='profile_img'></span>";
 }

 ?>
 <li style="background: <?= $key["colorcode"]; ?>"  class="text-row ui-sortable-handle" id="<?= $key["id"]; ?>" data-id="<?= $key["id"]; ?>"> 
  <div id="li" data-id="<?= $key["id"]; ?>" style="color:white;width: 80%;display: inline-block;padding: 15px 10px;"><?= $key["todoTitle"]; ?></div>

  <?php if($button=="SubmitButton"){ ?>

   <span style="cursor:pointer;color:white;float:right;padding: 15px 10px 15px 0px;" class="fa fa-check SubmitButton" title="Submit Task" data-id="<?= $key["id"]; ?>"></span>

 <?php } else{ ?>

   <span style="float:right;padding: 15px 10px 15px 0px;" class="fa fa-close deleteButton" title="Close Task" data-id="<?= $key["id"]; ?>"></span>

 <?php } ?>

 <span style="float:right;padding: 15px 7px 15px 0px;<?= $hide?>" class="fa fa-edit edit_data" title="Edit Details" id="editbutton" data-id="<?= $key["id"]; ?>"></span>

 <hr style="margin: 0px;">

 <div style="text-align: right;padding: 3px;display: flex;justify-content: space-between;<?= $style?>" >
   <span title="Due Date" style="margin-left: 10px;color: white;font-size: 11px;" ><strong><?= date('M d, Y',strtotime($key["newduedate"])); ?> </strong></span>
   <span style="margin: 0px 5px;max-width: 25%;"><?= $users ;?></span>
 </div>

</li>      
<?php
}
}
?>
</ul>
<div class="card-footer">
 <center>
  <div title="Add New Task" data-id="<?= $keys['id'];  ?>" id="addtask" data-toggle="modal" data-target="#myModal">
    <i class="fa fa-plus-circle"></i>
  </div>
</center>
</div>
</div>
<?php
}
?>
</div>