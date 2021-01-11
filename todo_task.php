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

function hex2rgba($color, $opacity = false) {

  $default = 'rgb(0,0,0)';

  if(empty($color))
    return $default; 

  if ($color[0] == '#' ) {
    $color = substr( $color, 1 );
  }

  if (strlen($color) == 6) {
    $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
  } elseif ( strlen( $color ) == 3 ) {
    $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
  } else {
    return $default;
  }

  $rgb =  array_map('hexdec', $hex);

  if($opacity){
    if(abs($opacity) > 1)
      $opacity = 1.0;
    $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
  } else {
    $output = 'rgb('.implode(",",$rgb).')';
  }

  return $output;
}

?>
<!-- Todo_task.php page -->

<div class="status-card sortable_row_connect" id="sort">
 <div class="card_header card-header">
  <span class="card-header-text">TODO (Self)</span>
  <div class="card_header_button"> 
    <span title="Add New Task" class="fa fa-plus m-2" id="addtaskself" data-toggle="modal" data-target="#myModal" data-id="1">
    </span>
  </div>
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
   <li style="background: <?= hex2rgba($taskRow['colorcode'],0.50); ?>" class="text-row ui-sortable-handle"  id="<?= $taskRow["id"]; ?>" data-id="<?= $taskRow["id"]; ?>"> 
    <i class="fa fa-link listarrow"></i>
    <div style="width: 10px;height: 100%;position: absolute;background: <?= $taskRow['colorcode']; ?> "></div>

    <div id="li" data-id="<?= $taskRow["id"]; ?>" style="color:<?= $taskRow['colorcode']; ?>;min-height: 80px;padding: 15px 10px;font-weight: 600;text-align: right;"><?= $taskRow["todoTitle"]; ?></div>


    <div class="list_edit_button">
     <span class="fa fa-close deleteButton m-2" title="Close Task" data-id="<?= $taskRow["id"]; ?>"></span>
     <span class="fa fa-edit edit_data m-2" title="Edit Details" id="editbuttonself" data-id="<?= $taskRow["id"]; ?>"></span>
   </div>

   <hr style="margin: 0px;">

   <div style="text-align: right;padding: 5px;" >
    <span title="Due Date" style="display: flex;align-items: flex-end;float:left;margin-left: 10px;color: <?= $taskRow['colorcode']; ?>;font-size: 14px;font-weight: 600;" ><?=  date('M d, Y',strtotime($taskRow["newduedate"])); ?></span>

    <span style="margin: 0 5px;color: white;font-size: 11px;" >
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
<!-- <div class="card-footer">
  <center>
    <div title="Add New Task" id="addtaskself" title="Add task" data-id="todo" data-toggle="modal" data-target="#myModal" >
      <i class="fa fa-plus-circle"></i>
    </div>
  </center>
</div> -->
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

      <span title="Add New Task" class="fa fa-plus m-2" data-id="<?= $keys['id'];  ?>" id="addtask" data-toggle="modal" data-target="#myModal">
      </span>
      <span class="fa fa-edit m-2" title="Edit Category" id="editcat" data-id="<?= $keys["id"]; ?>"></span>
      <span  class="fa fa-close m-2" title="Delete Category" id="delcat" data-id="<?= $keys["id"]; ?>"></span> 

    <?php } ?>
  </div>
</div>
<div class="catin" name="catin<?= $keys["id"] ?>" >


  <textarea type="text" id="catin" name="catin<?= $keys["id"] ?>" class="form-control"></textarea> 
  <div>
    <span style="margin-left: 7px;" class="fa fa-close catnameclose<?= $keys["id"]; ?>" title="close" id="editcat" data-id="<?= $keys["id"]; ?>"></span></span>
    <span data-id="<?= $keys["id"]; ?>" class="fa fa-check savebtn"></span>
  </div>

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
 <li style="background: <?= hex2rgba($key["colorcode"],0.50); ?>"  class="text-row ui-sortable-handle" id="<?= $key["id"]; ?>" data-id="<?= $key["id"]; ?>"> 
  <i class="fa fa-link listarrow"></i>
  <div style="width: 10px;height: 100%;position: absolute;background: <?= $key["colorcode"]; ?> "></div>
  <div id="li" data-id="<?= $key["id"]; ?>" style="color:<?= $key["colorcode"]; ?>;min-height: 80px;padding: 15px 10px;font-weight: 600;text-align: right;"><?= $key["todoTitle"]; ?></div>

  <div class="list_edit_button">
    <?php if($button=="SubmitButton"){ ?>

     <span class="fa fa-check SubmitButton m-2" title="Submit Task" data-id="<?= $key["id"]; ?>"></span>

   <?php } else{ ?>

     <span class="fa fa-close deleteButton m-2" title="Close Task" data-id="<?= $key["id"]; ?>"></span>

   <?php } ?>

   <span style="<?= $hide?>" class="fa fa-edit edit_data m-2" title="Edit Details" id="editbutton" data-id="<?= $key["id"]; ?>"></span>

 </div>

 <hr style="margin: 0px;">

 <div style="text-align: right;padding: 5px;display: flex;justify-content: space-between;<?= $style?>" >
   <span title="Due Date" style="display: flex;align-items: flex-end;margin-left: 10px;color: <?= $key['colorcode']; ?>;font-size: 14px;font-weight: 600;" ><?= date('M d, Y',strtotime($key["newduedate"])); ?></span>
   <span style="margin: 0px 5px;max-width: 50%;"><?= $users ;?></span>
 </div>

</li>      
<?php
}
}
?>
</ul>
<!-- <div class="card-footer">
 <center>
  <div title="Add New Task" data-id="<?= $keys['id'];  ?>" id="addtask" data-toggle="modal" data-target="#myModal">
    <i class="fa fa-plus-circle"></i>
  </div>
</center>
</div> -->
</div>
<?php
}
?>
</div>