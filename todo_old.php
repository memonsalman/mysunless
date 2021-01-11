<?php
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: ../index.php");die;
}
$uid = $_SESSION["UserID"];

$adminsql  = $db->prepare("select adminid from users where id = :id");
$adminsql->bindParam(":id",$uid);
$adminsql->execute();
$admin = $adminsql->fetch();

$cat = $db->prepare("select * from todocat where (createdfk = :adminid OR createdfk = :id) AND status = '0' ORDER BY `position` ");
// $cat = $db->prepare("select * from todocat where createdfk = :id AND status = '0' ORDER BY `position` ");
$cat->bindParam(":adminid",$admin['adminid']);
$cat->bindParam(":id",$uid);
$cat->execute();
$cats = $cat->fetchAll();

if($admin['adminid'] == "")
{
    $admin['adminid'] = $_SESSION['UserID'];   
}

$sql = $db->prepare("SELECT *,DATE_FORMAT(newduedate, '%b-%d-%Y') as newduedate FROM `todo` WHERE (createdfk = :id or asignto = :id) AND catstatus = '1' AND status = '0' order by id DESC");
$sql->bindParam(":id",$uid);
$sql->execute();
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
$data = array_filter($data);

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
    $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todo',:tid,'done',:createdfk)");
    $sql->bindParam(":tid",$_POST["taskid"]);
    $sql->bindParam(":createdfk",$_SESSION['UserID']);
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
    $status_id = $_POST["status_id"];
    if($status_id == "todo")
    {
        $status_id = "1";
    }
    $task_id = $_POST["task_id"];
    $sql = $db->prepare("update todo set catstatus = :status_id where id = :task_id");
    $sql->bindParam(":status_id",$status_id);
    $sql->bindParam(":task_id",$task_id);
    $run = $sql->execute();
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

    /*if(isset($_GET["delcat"]))
    {
        $date = date('Y/m/d h:i:s', time());
        $catid = $_POST["catid"];
        $type = "cat";
        $name = "trest";
                $catsql = $db->prepare("select catname from todocat where id = :id");
                $catsql->bindParam(":id",$catid);
                $catsql->execute();
                $catdata = $catsql->fetch(PDO::FETCH_ASSOC);
        $sql = $db->prepare("insert into todohistory(closedby,type,name,closeddate,taskid) values(:UserID,:type,:name,:closeddate,:taskid)");
        $sql->bindParam(":UserID",$uid);
        $sql->bindParam(":taskid",$catid);
        $sql->bindParam(":type",$type);
        $sql->bindParam(":name",$catdata["catname"]);
        $sql->bindParam(":closeddate",$date);
        $run = $sql->execute();
        if($run)
        {
            $taskdatasql = $db->prepare("select * from todo where catstatus = :id");
            $taskdatasql->bindParam(":id",$_POST["catid"]);
            $taskdatasql->execute();
            $data = $taskdatasql->fetchAll(PDO::FETCH_ASSOC);
            $row = count($data);
            if($row != 0)
            {
                foreach ($data as $key)
                {
                    $type = "task";
                    $tasksql = $db->prepare("insert into todohistory(closedby,type,name,closeddate,taskid) values(:UserID,:type,:name,:closeddate,:taskid)");
                    $tasksql->bindParam(":UserID",$uid);
                    $tasksql->bindParam(":type",$type);
                    $tasksql->bindParam(":name",$key["todoTitle"]);
                    $tasksql->bindParam(":closeddate",$date);
                    $tasksql->bindParam(":taskid",$key["id"]);
                    $run = $tasksql->execute();
                }
            }
        }

        $date = date('Y/m/d h:i:s', time());
        $taskid = $_POST["taskid"];
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

    }*/

    if(isset($_GET["delcat"]))
    {
        $date = date('Y/m/d h:i:s', time());
        $taskid = $_POST["taskid"];
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

    if(isset($_GET["addcmt"]))
    {
        $taskid = $_POST["taskid"];
        $cmt = $_POST["cmt"];
        $date = date('Y/m/d h:i:s', time());
        $sql = $db->prepare("insert into todocomment(comment,todoid,createddate,createdfk) values(:cmt,:taskid,:date,:uid)");
        $sql->bindParam(":cmt",$cmt);
        $sql->bindParam(":taskid",$taskid);
        $sql->bindParam(":date",$date);
        $sql->bindParam(":uid",$uid);
        $run = $sql->execute();
        $tid = $db->lastInsertId();


        $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('todocomment',:tid,'comment',:createdfk)");
        $sql->bindParam(":tid",$tid);
        $sql->bindParam(":createdfk",$_SESSION['UserID']);
        $run = $sql->execute();


        if($run)
        {
            echo json_encode(['success'=>"1","response"=>"Comment posted successfully."]);
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
        $sql = $db->prepare("update todocomment set comment = :cmt where id = :cmtid");
        $sql->bindParam(":cmt",$cmt);
        $sql->bindParam(":cmtid",$cmtid);
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

    if(isset($_GET["updateinfo"]))
    {
        $taskid = $_POST["taskid"];
        if(isset($_POST["vtitle"]))
        {
            $sql = $db->prepare("update todo set todoTitle = :vtitle where id = :id");
            $sql->bindParam(":vtitle",$_POST["vtitle"]);
        }
        else if(isset($_POST["vdesc"]))
        {
            $sql = $db->prepare("update todo set todoDesc = :vdesc where id = :id");   
            $sql->bindParam(":vdesc",$_POST["vdesc"]);
        }
        else if(isset($_POST['ddate']))
        {
            $sql = $db->prepare("update todo set newduedate = :ddate where id = :id");   
            $sql->bindParam(":ddate",$_POST["ddate"]);
        }
        $sql->bindParam(":id",$taskid);
        $run = $sql->execute();
        if($run)
        {
            echo json_encode(['success'=>"1","response"=>"Task information updated successfully."]);
            die();
        }
        else
        {
            echo json_encode(['success'=>"0","error"=>"Something went wrong please try again later"]);
            die();
        }

    }

    if(isset($_GET["gethistory"]))
    {
        $sql = $db->prepare("SELECT todo.*,users.firstname,users.lastname,users.UserName,todocat.catname FROM  todo JOIN users on users.id = todo.closedby JOIN todocat on todocat.id = todo.catstatus WHERE (todo.createdfk = :id or todo.asignto = :id) AND todo.status = '1' ORDER BY todo.closeddate DESC");
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
        //$total_todo = $db->prepare("SELECT todo.*,users.firstname as asigntofn,users.lastname as asigntoln FROM `todo` join users on users.id = todo.asignto WHERE todo.id=:myevent");
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

        $cmtsql = $db->prepare("SELECT todocomment.*,users.firstname,users.lastname,users.username FROM `todocomment` JOIN users on users.id = todocomment.createdfk where todoid = :id AND todocomment.status = '0' ORDER BY todocomment.createddate DESC");
        $cmtsql->bindParam(":id",$myevent);
        $cmtsql->execute();
        $cmtdata = $cmtsql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['resonse'=>$GetEvent,"cmtdata"=>$cmtdata]);die; 
    }

    // if(isset($_GET['DelteViewTodo']))
    // {
    //     $date = date('Y/m/d h:i:s', time());
    //     $myevent = $_POST["id"];

    //         $taskdatasql = $db->prepare("select * from todo where id = :id");
    //         $taskdatasql->bindParam(":id",$myevent);
    //         $taskdatasql->execute();
    //         $data = $taskdatasql->fetch(PDO::FETCH_ASSOC);

    //         $type = "task";
    //         $tasksql = $db->prepare("insert into todohistory(closedby,type,name,closeddate,taskid) values(:UserID,:type,:name,:closeddate,:taskid)");
    //         $tasksql->bindParam(":UserID",$uid);
    //         $tasksql->bindParam(":type",$type);
    //         $tasksql->bindParam(":name",$data["todoTitle"]);
    //         $tasksql->bindParam(":closeddate",$date);
    //         $tasksql->bindParam(":taskid",$data["id"]);
    //         $run = $tasksql->execute();

    //     $DeleteClient = $db->prepare("update todo set status = '1',closedby = :closedby,closeddate = :closeddate where id=:myevent");
    //     $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
    //     $DeleteClient->bindValue(":closedby",$uid);
    //     $DeleteClient->bindValue(":closeddate",$date);
    //     $DeleteClient->execute();
    //     echo json_encode(['resonse'=>'To-Do has been successfully closed']);die; 
    // }

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

    // update card position

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
    <link rel="stylesheet" href="trelo.css">
    <style type="text/css">
        .custbutton{margin: 5px 0;}
        th { font-weight: bold!important;color:#0b59a2!important;}
        .hsy_sub{
            position: absolute;
            background: #fff;
            top: 34px;
            right: 0;
            padding: 10px 10px;
            z-index: 2;
            width: 30%;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
            max-height: 430px;
            overflow: scroll;
        }
        .colorpre{
            width: 50px;
            margin-right: 11px;
            height: 50px;
            padding: 4px;
            border-radius: 3px;
        }
        li:hover{
            webkit-filter: hue-rotate(-70deg) saturate(1.5) !important;
        }

        /*Custom List*/
        .status-card .card-header{
            background-color: #3d94fb;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 15px;
            box-shadow: 0 2px 10px 0px #00000078;;
        }
        .status-card .card-footer{
            box-shadow: 0 0px 10px 1px #00000078;
            background-color: #3d94fb;
            color: #fff;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 5px;
        }

        .status-card .card-footer button{
            background: transparent;
        }

        .status-card .card-footer button span{
            color:white;
        }
        .profile_img{
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin: 0px 5px;
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
                                    <a style="float: right;" id="history" href="#">Task-History <i class="fa fa-angle-down"></i></a>
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
                                        <div id="tasks" class="task-board row sortable_row" >
                                            <div class="status-card sortable_row_connect" id="sort">
                                                <div class="card-header">
                                                    <span class="card-header-text">To-do</span>
                                                </div>
                                                <ul id="sortable1" data-id="todo" class="sortable connectedSortable ui-sortable">
                                                    <?php
                                                    if (!empty($data)) 
                                                    {
                                                        foreach ($data as $taskRow) 
                                                        {  
                                                            $button;
                                                            if ($taskRow["asignto"] == $_SESSION['UserID'] && $taskRow["createdfk"] != $_SESSION['UserID']){
                                                                $id = $taskRow["createdfk"];
                                                                $title = "Assigned By";
                                                                $style = "direction: rtl;";
                                                                $button = "SubmitButton";
                                                                $icon = "fa-check";
                                                            }else if ($taskRow["asignto"] != $_SESSION['UserID'] && $taskRow["createdfk"] == $_SESSION['UserID']){
                                                               $id = $taskRow["asignto"];
                                                               $style = "direction: ltr;";
                                                               $title = "Assigned to";
                                                               $button = "deleteButton";
                                                               $icon = "fa-close";
                                                           }else{
                                                               $button = "deleteButton";
                                                           }

                                                           if(!empty($taskRow["asignto"]))
                                                           {
                                                            $userdata = $db->prepare("SELECT id,firstname,lastname,username,userimg FROM users WHERE id = :id");
                                                            $userdata->bindParam(":id",$id);
                                                            $userdata->execute();
                                                            $udata = $userdata->fetch();  
                                                        }
                                                        ?>
                                                        <li style="background: <?= $taskRow["colorcode"]; ?>" class="text-row ui-sortable-handle"  id="<?= $taskRow["id"]; ?>" data-id="<?= $taskRow["id"]; ?>"> 

                                                            <div id="li" data-id="<?= $taskRow["id"]; ?>" style="color:white;width: 80%;display: inline-block;padding: 15px 10px;"><?= $taskRow["todoTitle"]; ?></div>

                                                            <?php if($button=="SubmitButton"){ ?>

                                                                <button style="float:right;padding: 15px 10px 15px 0px;" class="btn close SubmitButton" title="Submit Task" data-id="<?= $taskRow["id"]; ?>"><span class="fa fa-check fa-sm"></span></button>

                                                            <?php } else{ ?>

                                                                <button style="float:right;padding: 15px 10px 15px 0px;" class="btn close deleteButton" title="Close Task" data-id="<?= $taskRow["id"]; ?>"><span class="fa fa-close fa-sm"></span></button>

                                                            <?php } ?>

                                                            <button style="float:right;padding: 15px 7px 15px 0px;" class="btn close edit_data" title="Edit Details" id="editbutton" data-id="<?= $taskRow["id"]; ?>"><span class="fa fa-edit fa-sm"></span></button>
                                                            <hr style="margin: 0px;">
                                                            <?php 
                                                            if(!empty($taskRow["asignto"]))
                                                            {
                                                                if($taskRow["asignto"] != $_SESSION['UserID'] || $taskRow["createdfk"] != $_SESSION['UserID'])
                                                                {
                                                                    if(!empty($udata["userimg"])){
                                                                        $profile_img = base_url."/assets/userimage/".$udata["userimg"];
                                                                    }else{
                                                                        $profile_img = base_url."/assets/images/noimage.png";
                                                                    }
                                                                    ?>
                                                                    <div style="text-align: right;padding: 3px;display: flex;justify-content: space-between;<?= $style?>" >
                                                                        <span title="Due Date" style="color: white;font-size: 11px;" ><strong><?= $taskRow["newduedate"]; ?> </strong></span>

                                                                        <span title="<?= $title." ".$udata["firstname"]." ".$udata["lastname"]; ?>" style="margin-left: 10px;color: white;font-size: 11px;" ><strong><?=$udata["username"]; ?> </strong>
                                                                            <img src="<?= $profile_img?>" class="profile_img">
                                                                        </span>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                else
                                                                {

                                                                    ?>
                                                                    <div style="text-align: right;padding: 3px;" >
                                                                        <span title="Due Date" style="float:left;margin-left: 10px;color: white;font-size: 11px;" ><strong><?= $taskRow["newduedate"]; ?> </strong></span>

                                                                        <span style="margin-bottom: 0px;color: white;font-size: 11px;" ><strong>Self </strong>
                                                                            <img src="" class="profile_img profile_img_self">
                                                                        </span>
                                                                    </div>
                                                                    <?php   
                                                                }
                                                            }
                                                            ?>
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
                                                <center><button style="width: 100%;" class="btn btn-sm" id="addtask" title="Add task" data-id="todo" data-toggle="modal" data-target="#myModal"><span style="font-size: 13px;" class="">+ Add another task</span></button></center>
                                            </div>
                                        </div>
                                        <!-- <div class="sortablerow" style="cursor: all-scroll;display: contents;" > -->
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
                                                                <button style="float:right;margin-left: 7px;" class="btn close" title="Delete Category" id="delcat" data-id="<?= $keys["id"]; ?>"><span class="fa fa-close fa-sm"></span></button> 
                                                                <button style="float:right;" class="btn close" title="Edit Category" id="editcat" data-id="<?= $keys["id"]; ?>"><span class="fa fa-edit fa-sm"></span></button>

                                                            </div>
                                                        </div>
                                                        <div class="catin" name="catin<?= $keys["id"] ?>">
                                                            <!-- <input type="text" id="catin" name="catin<?= $keys["id"] ?>"> -->

                                                            <textarea type="text" id="catin" name="catin<?= $keys["id"] ?>" class="form-control"></textarea> 

                                                            <!-- <button style="background-color: green" data-id="<?= $keys["id"]; ?>" class="btn btn-success savebtn">Save</button> -->
                                                            <button style="float:right;margin-left: 7px;" class="btn close catnameclose<?= $keys["id"]; ?>" title="close" id="editcat" data-id="<?= $keys["id"]; ?>"><span class="fa fa-close fa-sm"></span></button>
                                                            <button style="float:right;" data-id="<?= $keys["id"]; ?>" class="btn close savebtn"><span class="fa fa-check fa-sm"></span></button>
                                                        </div>
                                                    </div>
                                                    <ul id="<?= $keys["id"];  ?>" data-id="<?= $keys['id'];  ?>" class="sortable connectedSortable ui-sortable">
                                                        <?php
                                                        $cat = $keys["id"];
                                                        $sql = $db->prepare("SELECT *,DATE_FORMAT(newduedate, '%b-%d-%Y') as newduedate FROM `todo` WHERE (createdfk = :id or asignto = :id) AND catstatus = :cat AND status = '0'");
                                                        $sql->bindParam(":cat",$cat);
                                                        $sql->bindParam(":id",$uid);
                                                        $sql->bindParam(":admin",$admin['adminid']);
                                                        $sql->execute();
                                                        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                                                        if(!empty($data))
                                                        {
                                                            foreach ($data as $key) 
                                                            {   
                                                                $button;
                                                                if ($key["asignto"] == $_SESSION['UserID'] && $key["createdfk"] != $_SESSION['UserID']){
                                                                    $id = $key["createdfk"];
                                                                    $title = "Assigned By";
                                                                    $style = "direction: rtl;";
                                                                    $button = "SubmitButton";                           
                                                                }else if ($key["asignto"] != $_SESSION['UserID'] && $key["createdfk"] == $_SESSION['UserID']){
                                                                   $id = $key["asignto"];
                                                                   $style = "direction: ltr;";
                                                                   $title = "Assigned to";
                                                                   $button = "deleteButton";                    
                                                               }else{
                                                                   $button = "deleteButton";                    
                                                               }

                                                               if(!empty($key["asignto"]))
                                                               {
                                                                $userdata = $db->prepare("SELECT id,firstname,lastname,username,userimg FROM users WHERE id = :id");
                                                                $userdata->bindParam(":id",$id);
                                                                $userdata->execute();
                                                                $udata = $userdata->fetch();    
                                                            }
                                                            if(!empty($udata["userimg"])){
                                                                $profile_img = base_url."/assets/userimage/".$udata["userimg"];
                                                            }else{
                                                                $profile_img = base_url."/assets/images/noimage.png";
                                                            }
                                                            ?>
                                                            <li style="background: <?= $key["colorcode"]; ?>"  class="text-row ui-sortable-handle" id="<?= $key["id"]; ?>" data-id="<?= $key["id"]; ?>"> 
                                                                <div id="li" data-id="<?= $key["id"]; ?>" style="color:white;width: 80%;display: inline-block;padding: 15px 10px;"><?= $key["todoTitle"]; ?></div>

                                                                <?php if($button=="SubmitButton"){ ?>

                                                                    <button style="float:right;padding: 15px 10px 15px 0px;" class="btn close SubmitButton" title="Submit Task" data-id="<?= $key["id"]; ?>"><span class="fa fa-check fa-sm"></span></button>

                                                                <?php } else{ ?>

                                                                    <button style="float:right;padding: 15px 10px 15px 0px;" class="btn close deleteButton" title="Close Task" data-id="<?= $key["id"]; ?>"><span class="fa fa-close fa-sm"></span></button>

                                                                <?php } ?>

                                                                <button style="float:right;padding: 15px 7px 15px 0px;" class="btn close edit_data" title="Edit Details" id="editbutton" data-id="<?= $key["id"]; ?>"><span class="fa fa-edit fa-sm"></span></button>
                                                                <hr style="margin: 0px;">
                                                                <?php 
                                                                if(!empty($key["asignto"]))
                                                                {
                                                                    if($key["asignto"] != $_SESSION['UserID'] || $key["createdfk"] != $_SESSION['UserID'])
                                                                    {

                                                                        ?>
                                                                        <div style="text-align: right;padding: 3px;display: flex;justify-content: space-between;<?= $style?>" >
                                                                            <span title="Due Date" style="margin-left: 10px;color: white;font-size: 11px;" ><strong><?= $key["newduedate"]; ?> </strong></span>
                                                                            <span title="<?= $title." ".$udata["firstname"]." ".$udata["lastname"]; ?>" style="margin: 0px;color: white;font-size: 11px;" ><strong><?= $udata["username"]; ?></strong>
                                                                                <img src="<?= $profile_img?>" class="profile_img">
                                                                            </span>
                                                                        </div>
                                                                        <?php   
                                                                    }
                                                                    else
                                                                    {
                                                                        ?>
                                                                        <div style="text-align: right;padding:3px;" >
                                                                            <span title="Due Date" style="float:left;margin-left: 10px;color: white;font-size: 11px;" ><strong><?= $key["newduedate"]; ?> </strong></span>
                                                                            <span style="margin-bottom: 0px;color: white;font-size: 11px;" ><strong>Self</strong>
                                                                                <img src="" class="profile_img profile_img_self">
                                                                            </span>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </li>      
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                                <div class="card-footer">
                                                    <center><button style="width: 100%;" class="btn btn-sm" title="Add task" data-id="<?= $keys['id'];  ?>" id="addtask" data-toggle="modal" data-target="#myModal"><span style="font-size: 13px;" class="">+ Add another task</span></button></center>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title updatetitle">Add Task</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal " autocomplete="off" id="NewEvent" method="post">
                            <!-- <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>"> -->
                            <input type="hidden" id="cid" name="cid" value="">
                            <div class="Loader"></div>
                            <input type="hidden" name="id" id="id" value="">
                            <div class="form-group">
                                <label for="todoTitle">
                                    <h3>Title *</h3>
                                </label>
                                <br>
                                <input type="text" name= "todoTitle" id="todoTitle" placeholder="Enter Task Title...." class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label for="todoDesc">
                                    <h3>Description *</h3>
                                </label>
                                <br>
                                <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Task Description..." id="todoDesc" name="todoDesc"></textarea>
                            </div>
                            <div class="row form-group" >
                                <div class=" col-md-6" id= "datetimepicker">
                                    <label for="dueDate">
                                        <h3>Due Date *</h3>
                                    </label>
                                    <br>
                                    <select name= "dueDate" class="form-control"  id="dueDate">
                                        <option value=""> Select due date </option>
                                        <?php
                                        $curretdate = date("Y-m-d");
                                        $curretdate = strtotime($curretdate);
                                        for ($x = 1; $x <= 30; $x++) 
                                        {
                                         $curretdate = strtotime('1 day', $curretdate);
                                         $newduedate=  date('Y-m-d', $curretdate);
                                         echo "<option value='$newduedate'>$newduedate - $x days</option>";
                                     }
                                     ?>
                                 </select>
                             </div>
                             <div class="form-group col-md-6">
                                <label for="Asign to">
                                    <h3>Asign to *</h3>
                                </label>
                                <br>
                                <select name= "asignto" class="form-control"  id="asignto">
                                    <option value="-1" selected>Select</option>
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
                            </div> 
                        </div>
                        <div class="form-group">
                            <label for="todoDesc">
                                <h3>Color Picker</h3>
                            </label>
                            <br>
                            <div class="row col-md-12 form-group" >
                                <div class="col-md-11" style="display: inline-flex;align-items: center;" >
                                    <input class="colorpre" type="color" id="colorpre" name="colorcode" value="#3d94fb">    
                                    <div>
                                        <li class="text-row ui-sortable-handle" id="preview" style="color:white;width: 297px;margin-left: 10px;margin: 0px;list-style: none;background: #3d94fb" > 
                                            <div  style="font-size: 15px;width: 80%;display: inline-block;padding: 15px 10px;">#preview</div>
                                            <div style="float:right;padding: 15px 10px 15px 0px;" class="close"><span class="fa fa-close fa-sm"></span></div>
                                            <div style="float:right;padding: 15px 7px 15px 0px;" class="close"><span class="fa fa-edit fa-sm"></span></div>
                                            <hr style="margin: 0px;">
                                            <div style="text-align: right;padding: 3px;">
                                                <span style="float:left;margin-bottom: 0px;color: grey;font-size: 11px;"><strong style="color:white">Jan-01-2020 </strong></span>
                                                <span style="margin-bottom: 0px;color: grey;font-size: 11px;"><strong style="color:white">username </strong>
                                                    <img src="https://mysunless.com/crm/assets/images/noimage.png" class="profile_img">
                                                </span>
                                            </div>
                                        </li>
                                    </div>
                                </div>
                            </div>
                            <style>
                             .choose_red{
                                background-color: #DB2828
                            }
                            .choose_orange{
                                background-color: #F2711C
                            }
                            .choose_yellow{
                                background-color: #FBBD08
                            }
                            .choose_olive{
                                background-color: #B5CC18
                            }
                            .choose_green{
                                background-color: #21BA45
                            }
                            .choose_teal{
                                background-color: #00B5AD
                            }
                            .choose_blue{
                                background-color: #3d94fb
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
                </style>
                <h4>Radio Color Picker</h4>
                <div class="row" id="choose_color">

                    <input type="radio" name="color" id="choose_red" value="#DB2828" />
                    <label for="choose_red"><span class="choose_red"></span></label>

                    <input type="radio" name="color" id="choose_green" value="#21BA45"/>
                    <label for="choose_green"><span class="choose_green"></span></label>

                    <input type="radio" name="color" id="choose_yellow" value="#FBBD08"/>
                    <label for="choose_yellow"><span class="choose_yellow"></span></label>

                    <input type="radio" name="color" id="choose_olive" value="#B5CC18"/>
                    <label for="choose_olive"><span class="choose_olive"></span></label>

                    <input type="radio" name="color" id="choose_orange" value="#F2711C"/>
                    <label for="choose_orange"><span class="choose_orange"></span></label>

                    <input type="radio" name="color" id="choose_teal" value="#00B5AD"/>
                    <label for="choose_teal"><span class="choose_teal"></span></label>

                    <input type="radio" name="color" id="choose_blue" value="#3d94fb"/>
                    <label for="choose_blue"><span class="choose_blue"></span></label>

                    <input type="radio" name="color" id="choose_violet" value="#6435C9"/>
                    <label for="choose_violet"><span class="choose_violet"></span></label>

                    <input type="radio" name="color" id="choose_purple" value="#A333C8"/>
                    <label for="choose_purple"><span class="choose_purple"></span></label>

                    <input type="radio" name="color" id="choose_pink" value="#E03997"/>
                    <label for="choose_pink"><span class="choose_pink"></span></label>

                </div>
            </div>
            <script type="text/javascript">
                $("#choose_color input[type='radio']").change(function(){
                    color = $(this).val();
                    $("#preview").css("background",color);
                    $("#colorpre").val(color);
                });


                $("input[name='colorcode']").on("change",function(){
                    $("#preview").css("background",$(this).val());
                    return false;
                });
            </script>
            <div class="form-group">
                <button type="submit" name="todoSub" id="todoSub" class="btn btn-info m-r-10"><i class="fa fa-check"></i>Add Task</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<!-- add category modal -->
<div class="modal fade" id="addcatmodal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="close catCancel" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="addcat" autocomplete="off" method="post">
                    <div class="Loader"></div>
                    <div class="form-group">
                        <label for="todocat">
                            <h3>Category Name</h3>
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
<!-- add category modal -->




<!--   view data modal -->
<div class="modal fade" id="viewmodal" role="dialog">
    <div class="modal-dialog" style="max-width: 700px;" >

      <!-- Modal content-->
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
                        <!-- <h2  id="vtitle" ></h2> -->  <!-- <strong><a href='' title='edit' class='fas fa-ellipsis-h' style='font-size: 20px;' ></a></strong> -->
                        <textarea style="font-size: 30px;background: none;font-weight: 600;border: none;color: black;width: 94%;" disabled="" id="vtitle"></textarea><br>
                        <a href='#' class='vtitle' id='vtitlesave' style='display:none;font-size: 12px;color: #5e6c84;text-decoration: underline;'>Save</a> 
                        <button class='btn close vtitle' style='display:none;float:none;font-size:15px;' id='vtitleclose' ><span class='fa fa-close fa-sm'></span></button>
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
                    <h3 style="font-size: 18px;">Description <strong><a href="#" title="edit" class="fas fa-ellipsis-h vdescedit" ></a></strong></h3>
                    <p  id="vdesc" ></p>
                    <div class="vtxtarea" style="display: none;" >
                        <textarea class="textdesc form-control" style="width: 94%;" rows="8"  id="todoDesc" name="vdesc"></textarea>
                    </div>
                    <br>
                    <a href='#' class='vdesc' id='vdescsave' style='display:none;font-size: 12px;color: #5e6c84;text-decoration: underline;'>Save</a> 
                    <button class='btn close vdesc' style='display:none;float:none;font-size:15px;' id='vdescclose' ><span class='fa fa-close fa-sm'></span></button>
                    <!-- <textarea rows="auto" id="vdesc" style="width: 90%" ></textarea> -->
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
                <h3 style="font-size: 18px;">Due Date <strong><a href="#" title="edit" id='vddateedit' class="fas fa-ellipsis-h" ></a></strong></h3>
                <strong><h2 style="font-size: 25px;"  id="vddate" ></h2></strong>
                <!-- <textarea rows="auto" id="vdesc" style="width: 90%" ></textarea> -->
                <select style="display:none;width: 94%" name= "selddate" class="form-control"  id="selddate">
                    <?php
                    $curretdate = date("Y-m-d");
                    $curretdate = strtotime($curretdate);
                    for ($x = 1; $x <= 30; $x++) 
                    {
                     $curretdate = strtotime('1 day', $curretdate);
                     $newduedate=  date('Y-m-d', $curretdate);
                     echo "<option value='$newduedate'>$newduedate - $x days</option>";
                 }
                 ?>
             </select><br>
             <a href='#' class='vddate' id='vddatesave' style='display:none;font-size: 12px;color: #5e6c84;text-decoration: underline;'>Save</a> 
             <button class='btn close vddate' style='display:none;float:none;font-size:15px;' id='vddateclose' ><span class='fa fa-close fa-sm'></span></button>
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
    <?php $uname = substr($_SESSION["fname"],0,1).substr($_SESSION["lname"],0,1);  ?>
    <span title="<?= $_SESSION["flname"]; ?> (<?= $_SESSION["UserName"]; ?>)" style="font-size: 13px;border-radius: 50%;height: 36px;margin-top: 10px;margin-left: 3px;width: 36px;padding: 9px;background-color: darkgray;" ><?= $uname; ?></span>
    <input type="hidden" name="tid" id="tid" value="">
    <textarea  id="comment" class="form-group" placeholder="Write a comment…" style="padding: 5px;width: 82%;margin-left: 8px;" name="comment"></textarea>
    <button style="background-color: green;border-color: green;margin-left: 5px;"name="btncomment"  id="post" class="btn form-group btn-info m-r-10">Post</button>
</div>
<div><div class="activeload" ></div></div>
<div>
    <div id="cmtbody">

    </div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- view data modal -->


<!--   close data modal -->
<div class="modal fade" id="closemodal" role="dialog">
    <div class="modal-dialog" style="max-width: 700px;" >

      <!-- Modal content-->
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
<!-- close data modal -->

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
<script type="text/javascript">
    $(document).ready(function() {

        $(".SubmitButton").click(function(){
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


            //   $(".openM").click(function(){
            //     $("#myModal").modal({
            //         backdrop: 'static',
            //         keyboard: false
            //     });
            // });

        // if($('#addcatmodal').css('display') == 'none')
        // {

        //         $('#addcat').trigger("reset");

        // }


        $('.textarea_editor').wysihtml5();
    });
    $(window).bind("onload", function() {
        clear();
    });

    $(".profile_img_self").attr("src",$(".profile-pic img").attr("src"));

    $( function() {
       var url = 'edit-status.php';
       $('.sortable').sortable({
           connectWith: ".sortable",
           receive: function (e, ui) {
               var status_id = $(ui.item).parent(".sortable").data("id");
               var task_id = $(ui.item).data("id");
               // console.log(status_id);
               $.ajax({
                url:'?catstatus',
                data:{"status_id":status_id,"task_id":task_id},
                type:"post",
                dataType:"json",
                success:function(data){
                    if(data.success == "0")
                    {
                        swal("Oops...",data.error, "error");
                    }
                    /*
                    else
                    {

                        swal("Success",data.response,"success");
                        setTimeout(function () { window.location.reload() }, 2000);
                    }*/
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
                    console.log(data);
                // if(data.success == "0")
                // {
                //     swal("Oops...",data.error, "error");
                // }
                /*
                else
                {

                    swal("Success",data.response,"success");
                    setTimeout(function () { window.location.reload() }, 2000);
                }*/
            }
        });
          // var listElements = $("#tasks").children();
          // var i;
          // var data=[];

          // for(i=0;i<listElements.length;i++)
          // {

          //   data.push(listElements[i].attr("card-id"));
          // }
          console.log(data);
          
      }
  }).disableSelection();;

   } );

 // $(function(){
 //    $( "#sortable1" ).sortable({
 //      connectWith: ".connectedSortable"
 //    }).disableSelection();

 //        $( ".sortable" ).droppable({
 //            drop: function( event, ui ) {
 //                var data = $(this).attr("data-id");
 //                console.log(data);
 //            }
 //        });

 //     $( ".sortable" ).sortable({ 
 //        forcePlaceholderSize: true,
 //        update: function(event, ui){
 //            var dataid = $(this).attr("data-id");
 //            // alert(dataid);
 //            // return false;
 //          var data = $(this).sortable("toArray");
 //          data = data.filter(Boolean);
 //          data = data.join();
 //          console.log(data);
 //          $.ajax({
 //            url:'?todolist',
 //            data: {"data":data},
 //            type:'post',
 //            success:function(data){
 //            }
 //          });
 //        }
 //      });
 //  });
</script>
<script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>
<script>

    $('.textdesc').wysihtml5();

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
       url:'?updateinfo',
       data:{"ddate":ddate,"taskid":taskid},
       type:"post",
       dataType:"json",
       success:function(data){
          if(data.success == "0")
          {
            swal("Oops...",data.error, "error");
        }
        else
        {
            $("#vddate").attr("data-val",ddate);
            $("#vddate").html(moment(ddate).format('MMM-D-YYYY'));
            $("#vddateedit").show();
            $("#vddate").show();
            $("#selddate").hide();
            $(".vddate").hide();
            return false;
            setTimeout(function () { window.location.reload();  }, 1000);
        }
    }
});
      return false;
  });

    $('.vdescedit').on("click",function(){
       $(this).hide();
       $(".vtxtarea").show();
       $(".vdesc").show();
       var vdesc = $("#vdesc").html();
       $("#vdescclose").attr("vdesc",vdesc);
       $('iframe').contents().find('.wysihtml5-editor').html(vdesc);
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

    $(document).on("click","#vdescsave",function(){
        var desc = $("textarea[name='vdesc']").val();
        var taskid = $(this).data("task");
        $.ajax({
           url:'?updateinfo',
           data:{"vdesc":desc,"taskid":taskid},
           type:"post",
           dataType:"json",
           success:function(data){
               if(data.success == "0")
               {
                   swal("Oops...",data.error, "error");
               }
               else
               {
                   $("#vdesc").html(desc);
                   $(".vdescedit").show();
                   $(".vdesc").hide();
                   $(".vtxtarea").hide();
                   $("#vdesc").show();
                   return false;
                   setTimeout(function () { $('div[data-id="'+taskid+'"]').trigger("click");  }, 50);
               }
           }
       }); 
    });

    $("div#vititle").on("click",function(){
        $("textarea#vtitle").removeAttr("disabled");
        $("#vtitleclose").attr("data-title",$("#vtitle").val());
        $("textarea#vtitle").css("border","1px solid black");
        var id = $("textarea#vtitle").data("task");
        $(".vtitle").show();
        return false;
    });

    $("#vtitleclose").on("click",function(){
        $(".vtitle").hide();
        $("textarea#vtitle").attr("disabled","");
        $("textarea#vtitle").css("border","none");
        $("textarea#vtitle").val($(this).data("title"));
        return false;
    });

    $("#vtitlesave").on("click",function(){
        var title = $("#vtitle").val();
        var taskid = $(this).data("task");
        $.ajax({
            url:'?updateinfo',
            data:{"vtitle":title,"taskid":taskid},
            type:"post",
            dataType:"json",
            success:function(data){
                if(data.success == "0")
                {
                    swal("Oops...",data.error, "error");
                }
                else
                {
                    $(".Loader").show();
                    $(".vtitle").hide();
                    $("textarea#vtitle").attr("disabled","");
                    $("textarea#vtitle").css("border","none");
                    setTimeout(function () { location.reload();  }, 50);
                    return false;
                }
            }
        });                                    
        return false;
    });

    $(document).ready(function(){
        $(".catin,#post").hide();
        $(".activeload").hide();
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
                $("#comment").val("");
                swal("Oops...",data.error, "error");
                return false;
            }
            else
            {
                $("#comment").val("");
                $("#viewmodal").modal("hide");
                setTimeout(function () { $('div[data-id="'+taskid+'"]').trigger("click");  }, 1000);
                return false;
            }
        }
    });
        return false;
    });

    $(document).on("click","#editcat",function(){
        var parent = $(this).parents(".status-card");   //added
        var data = $(this).data("id");
        //var span =$(this).closest("span").text();
        var span =parent.find(".card_header_text").text();      
        var trimStr = $.trim(span);
        $("textarea[name=catin"+data+"]").val(trimStr);
        // $(".catname"+data).toggle();
        parent.find(".catname"+data).find("div").toggle();
        $("div[name=catin"+data+"]").toggle();
    });

    $(document).on("click","#delcat",function(){
        var i;
        var arr = [];
        var ui = $(this).attr("data-id");
        var x = document.getElementById(ui).querySelectorAll(".text-row");
        for (i = 0; i < x.length; i++) {
            arr[i] = x[i].id;
        }
        var data = arr.join();
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will lost all tasks from here",
            icon: "warning",
            buttons: true,
        }).then((willDelete)=>{
            if(willDelete)
            {
                $.ajax({
                    url:"?delcat",
                    type:"post",
                    data:{"taskid":data,"catid":ui},
                    dataType:"json",
                    success:function(data){
                        if(data.success == "0")
                        {
                            swal("Oops...",data.error, "error");
                        }
                        else
                        {
                            setTimeout(function () { window.location.reload() },10);
                        }
                    }
                });
            }
            else
            {
               return false;
           }
       });
    });

    $('.savebtn').on("click",function(){
        //var input = $(this).closest("div").find("input").val();
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
                    parent.find("div[name=catin"+id+"]").toggle();
                    parent.find(".catname"+id).find("div").toggle();
                   // $(".catname"+id).html(input);
                    // setTimeout(function () { location.reload() }, 10);
                    // return false;
                }
            }
        });
    });

    $("#history").on("click",function(){
       $("#historybody").html("");
       $(".hsy_sub").toggle(); 
       $.ajax({
           url:'?gethistory',
           dataType:"json",
           success:function(data){
               if(data.response.length > 0)
               {
                   $.each(data.response, function (index, val) {
                       var html = "<li style='list-style: none;' class='row'><div class='col-md-1' ><span title='"+ val.firstname+" "+val.lastname+" ("+val.UserName +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-top: 2px;margin-left: 3px;width: 32px;padding: 7px;background-color: darkgray;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span></div><div class='col-md-10' style='margin-left: 10px;' ><span><strong>"+val.firstname+" "+val.lastname+"</strong> closed <a href='#' style='text-decoration:underline;' data-id='"+val.id+"' class='historytsk' >"+ val.todoTitle +"</a> from "+ val.catname+".</span><br><span style='font-size: 11px;'>"+moment(val.closeddate).format('MMM-D-YYYY')+" at "+moment(val.closeddate).format('HH:mm')+"</span></div></li><hr>";
                       $("#historybody").append(html);
                   });
               }
               else
               {
                   $("#historybody").html("<span style='text-align:center;font-weight:bold;' ><center>Nothing to show</center></span>");
               }
           }
       });
   });

    $(document).on("click",".historytsk",function(){
        var id = $(this).attr("data-id");
        $("#ccmtbody").html("");
        $.ajax({
            url:"?ViewTodo",  
            method:"POST",  
            data:{id:id},  
            dataType:"json",  
            success:function(data){
                var new_str = data.resonse.todoTitle.charAt(0).toUpperCase() + data.resonse.todoTitle.substr(1).toLowerCase();
                var new_desc = data.resonse.todoDesc.charAt(0).toUpperCase() + data.resonse.todoDesc.substr(1).toLowerCase();;
                var id = "<?= $_SESSION['UserID']; ?>";
                $('#ctitle').html(new_str);
                $("#ctitle,#cdesc,#cddate").attr("data-task",data.resonse.id);
                $(".vtitle,.vdesc,.vddate").attr("data-task",data.resonse.id);
                $('#cdesc').html(new_desc);
                $('#cddate').html(moment(data.resonse.newduedate).format('MMM-D-YYYY'));
                $("#cddate").attr("data-val",data.resonse.newduedate);
                $('#tid').val(data.resonse.id);
                if(data.cmtdata.length > 0)
                {
                    $.each(data.cmtdata, function (index, val) {
                        val.firstname = val.firstname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
                        val.lastname = val.lastname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
                        if(val.createdfk == id)
                        {
                            var html = "<span title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-top: 2px;margin-left: 3px;width: 32px;padding: 7px;background-color: darkgray;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span> <strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment(val.createddate).format('MMM-D-YYYY')+" at "+moment(val.createddate).format('HH:mm')+"</span><textarea onkeyup='textAreaAdjust(this)' onfocus='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+"  </textarea><br><br>";
                        }
                        else
                        {
                            var html = "<span title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-top: 2px;margin-left: 3px;width: 32px;padding: 7px;background-color: darkgray;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span> <strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment(val.createddate).format('MMM-D-YYYY')+" at "+moment(val.createddate).format('HH:mm')+"</span><textarea onkeyup='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+"  </textarea><br><br>";
                        }
                        $("#ccmtbody").append(html);
                    });
                    $(".activeload").hide();
                }
                else
                {
                    $("#ccmtbody").html("<h4 style='margin-left: 26px;'>Nothing to show...</h4>");
                }
                $('#closemodal').modal('show');
            }
        });
        return false;
    });

    $(document).on("click","#li",function(){
        $("#cmtbody").html("");
        $("#comment").val("");
        $("#post").hide();
        $(".Loader").show();
        var id = $(this).data("id");
        $.ajax({
            url:"?ViewTodo",  
            method:"POST",  
            data:{id:id},  
            dataType:"json",  
            success:function(data){
                $("#vddateclose,#vdescclose").trigger("click");
                $("ul.wysihtml5-toolbar").css("width","94%");
                new_str = data.resonse.todoTitle.charAt(0).toUpperCase() + data.resonse.todoTitle.substr(1).toLowerCase();
                // new_desc = data.resonse.todoDesc.charAt(0).toUpperCase() + data.resonse.todoDesc.substr(1).toLowerCase();
                new_desc = data.resonse.todoDesc;

                var id = "<?= $_SESSION['UserID']; ?>";
                $('#vtitle').html(new_str);
                $("#vtitle,#vdesc,#vddate").attr("data-task",data.resonse.id);
                $(".vtitle,.vdesc,.vddate").attr("data-task",data.resonse.id);
                $('#vdesc').html(new_desc);
                $('#vddate').html(moment(data.resonse.newduedate).format('MMM-D-YYYY'));
                $("#vddate").attr("data-val",data.resonse.newduedate);
                $('#tid').val(data.resonse.id);
                if(data.cmtdata.length > 0)
                {
                    $.each(data.cmtdata, function (index, val) {
                        val.firstname = val.firstname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
                        val.lastname = val.lastname.toLowerCase().replace(/\b[a-z]/g, function(txtVal) {return txtVal.toUpperCase();});
                        if(val.createdfk == id)
                        {
                            var html = "<span title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-top: 2px;margin-left: 3px;width: 32px;padding: 7px;background-color: darkgray;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span> <strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment(val.createddate).format('MMM-D-YYYY')+" at "+moment(val.createddate).format('HH:mm')+"</span><textarea onkeyup='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+"  </textarea><a href='#' class='cmtdel' id='cmtdel"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='margin-left: 45px;font-size: 12px;color: #5e6c84;text-decoration: underline;'>Delete</a> - <a href='#' class='cmtedit' id='cmtedit"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='font-size: 12px;color: #5e6c84;text-decoration: underline;'>Edit</a> <a href='#' class='cmtsave' id='cmtsave"+val.id+"' data-task='"+val.todoid+"' data-id='"+val.id+"' style='display:none;font-size: 12px;color: #5e6c84;text-decoration: underline;'>Save</a> <button class='btn close cmtclose' style='display:none;float:none;font-size:15px;' id='cmtclose"+val.id+"' data-id='"+val.id+"'><span class=' fa fa-close fa-sm'></span></button> <br><br>";
                        }
                        else
                        {
                            var html = "<span title='"+ val.firstname+" "+val.lastname+" ("+val.username +")' style='font-size: 12px;border-radius: 50%;height: 32px;margin-top: 2px;margin-left: 3px;width: 32px;padding: 7px;background-color: darkgray;'>"+val.firstname.charAt(0).toUpperCase()+val.lastname.charAt(0).toUpperCase() +"</span> <strong><span>"+val.firstname+" "+val.lastname+"</span></strong> <span style='font-size:12px;' >"+moment(val.createddate).format('MMM-D-YYYY')+" at "+moment(val.createddate).format('HH:mm')+"</span><textarea onkeyup='textAreaAdjust(this)' disabled onfocus='textAreaAdjust(this)' style='overflow:hidden;text-decoration:none;overflow-y:scroll;' id='comment"+val.id+"' class='comment'  >"+val.comment+"  </textarea><br><br>";
                        }
                        $("#cmtbody").append(html);
                    });
                    $(".activeload").hide();
                }
                else
                {
                    $("#cmtbody").html("");
                }
                $('#viewmodal').modal('show');
                $(".Loader").hide();
                $(".comment").focus();
            }
        });
});

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
                    setTimeout(function () { $('div[data-id="'+taskid+'"]').trigger("click");  }, 1000);
                }
            }
        });
        
    }
});

$(document).on('click','.cmtdel',function(e){
    e.preventDefault();
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
                        $("#viewmodal").modal("hide");
                        $(".Loader").show();
                        setTimeout(function () { $('div[data-id="'+taskid+'"]').trigger("click");  }, 1000);
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
    $(document).on('click','#addtask',function(){
        $("#todoSub").html("Add Task");
        $(".updatetitle").html("Add Task");
        $('#NewEvent')[0].reset();
        $('#id').val('new');
        var catstatus = $(this).data("id");
        $("ul.wysihtml5-toolbar").css("width","");
        // $("#preview").css("background","white");
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
                                                setTimeout(function(){ location.reload(); }, 10);
                                            }
                                            else if(data.error)
                                            {
                                                $("#error").show();
                                                $('#errormsg').html('<span>'+data.error+'</span>');
                                                $(".Loader").hide();
                                                swal("Oops...",data.error, "error");
                                            }

                                // if(data.resonse)
                                // {
                                //     $("#resonse").show();
                                //     $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                                //     $( '#NewEvent' ).each(function(){
                                //         this.reset();
                                //     });
                                //     $(".Loader").hide();
                                //     $("#myModal").modal('hide');
                                //      swal("Success",data.resonse,"success");
                                //    setTimeout(function () { window.location.reload() }, 2000)
                                // }
                                // else if(data.error)
                                // {
                                //     $("#error").show();
                                //     $('#errormsg').html('<span>'+data.error+'</span>');
                                //     $(".Loader").hide();
                                //     swal("Oops...",data.error, "error");
                                // }
                                // else if(data.csrf_error)
                                // {
                                // $("#csrf_error").show();
                                // $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                                // $(".Loader").hide();
                                // setTimeout(function () { window.location.reload() }, 2000)
                                // }
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
                            setTimeout(function () { window.location.reload() }, 10)
                        }
                    }
                });
            }
        });

$(document).on('click','.edit_data', function(){
    $("#todoSub").html("Update Task");
    $(".updatetitle").html("Update Task");
    var id = $(this).data("id")
    $.ajax({
        url:"?EditViewTodo",
        method:"POST",  
        data:{id:id},  
        dataType:"json",  
        success:function(data){
            $('#todoTitle').val(data.resonse.todoTitle);
                        // $('#todoDesc').val(data.resonse.todoDesc);
                        $('iframe').contents().find('.wysihtml5-editor').html(data.resonse.todoDesc);
                        $('#dueDate').val(data.resonse.newduedate);
                        $('#id').val(data.resonse.id);
                        $('#cid').val(data.resonse.catstatus);
                        $("#asignto").val(data.resonse.asignto);
                        $("#colorpre").val(data.resonse.colorcode);
                        $("#preview").css("background",data.resonse.colorcode);
                        $('#myModal').modal('show');
                    }
                });
});

$(document).on('click','.deleteButton',function(e){
  e.preventDefault();
  var id = $(this).data("id");
  swal({
    title: "Are you sure?",
    text: "Once closed, you will lost this from here",
    icon: "warning",
    buttons: true,
}).then((willDelete)=>{
    if(willDelete)
    {
        $.ajax({
            url:"?DelteViewTodo",  
            method:"POST",  
            data:{ id:id},  
            dataType:"json",  
            success:function(data)
            {
                if(data.success == "0")
                {
                    swal("Oops...",data.error, "error");
                }
                else
                {
                    setTimeout(function () { location.reload() }, 10)
                }
            }
        });
    }
    else
    {
        return false;
    }
});
});
});
</script>
<script>
    
var urlParams = new URLSearchParams(window.location.search);
var taskid = urlParams.get("task");
if(taskid){
    $("#"+taskid).find("div").trigger("click");
}

</script>
</body>
</html>