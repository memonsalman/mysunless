<?php

require_once('Exec_Config.php');        

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');

if(!empty($_POST['UserID'])){
    $id = base64_decode($_POST['UserID']);
    $sid = $_POST['UserID'];
}else{
    $id=$_SESSION['UserID'];
    $sid = base64_encode($id);
}


$db= new db();
// =============  for Today Activities ======================== //
if(isset($_POST["today"]))
{
    $query = " SELECT * FROM Activities JOIN users ON users.id=Activities.UserID  WHERE (Activities.createdtime >= CURDATE() AND Activities.createdtime < CURDATE() - 1) AND (users.id=:id) ORDER BY `Activities`.`createdtime` DESC LIMIT ".$_POST["startToday"].", ".$_POST["limitToday"]." ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    @$result_activiet = $stmt->fetchAll();

    if(count($result_activiet)>0){

        $img = "/assets/images/noimage.png";
        if(!empty($result_activiet[0]['userimg'])){
            $img = "/assets/userimage/".$result_activiet[0]['userimg'];
        }
        ?>

        <?php if($_POST['startToday']==0){ ?>

            <div class="activity_header"><img data-sid="<?= $sid ?>" class="ViewUserInfo" class="img-circle" src="<?= base_url.$img?>">
                <span style="font-size: 18px;word-break: break-all;"><?= ucfirst($result_activiet[0]['username']); ?></span>
            </div>
            <hr style="margin-top:0;">
        <?php } ?>

        <?php



        foreach ($result_activiet as $row) 
        {   

            ?>

            <div class="activity_body">
                <i class="fa fa-arrow-circle-o-right arrow"></i>
                <div class="timeline">
                    <?php 
                    $start  = date_create($row['createdtime']);
                    $end    = date_create();
                    $diff   = date_diff( $start, $end );
                    if($diff->i<60 && $diff->h==0 && $diff->d==0 && $diff->m==0 && $diff->y==0)
                    { 
                        echo $diff->i . ' Minutes ago ';
                    }
                    if($diff->i<60 && $diff->h>0 && $diff->d==0)
                    { 
                        echo $diff->h . ' Hours ago ';
                    }
                    if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m==0 && $diff->y==0)
                    { 
                        echo $diff->d . ' Days ago ';
                    }
                    if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m>0 && $diff->y==0)
                    { 
                        echo $diff->m . ' Months ago ';
                    }
                    if($diff->i>0 && $diff->h>0 && $diff->d>0 && $diff->m>0 && $diff->y>0)
                    { 
                        echo $diff->y . ' Years ago ';
                    }
                    ?>
                </div>
                <br>
                <div class="activity_title">
                 <?php echo $row['Titile']; ?>
             </div>
         </div>

     <?php  }  
 }
}
// =============  for Yesterday Activities ========================
if(isset($_POST["yesterday"]) )
{
    $query = " SELECT * FROM Activities JOIN users ON users.id=Activities.UserID WHERE (Activities.createdtime BETWEEN DATE_ADD(CURDATE(), INTERVAL -1 day) AND CURDATE()) AND (users.sid=:id OR users.id=:id) ORDER BY `Activities`.`createdtime` DESC LIMIT ".$_POST["startYesterday"].", ".$_POST["limitYesterday"]." ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    @$result_activiet_yesterday = $stmt->fetchAll();
    foreach (@$result_activiet_yesterday as $row) 
    {
        ?>
        <div class="activedatea">
            <div class="activeuserimage">
                <?php 
                $id=$row['UserID'];
                $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $allresult = $stmt->fetch(PDO::FETCH_ASSOC);
                $img=@$allresult['userimg'];
                $username=@$allresult['username'];
                if (empty($img)) 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="">
                    <?php
                } 
                elseif (file_exists(DOCUMENT_ROOT.'/assets/userimage/'.$img)) 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/userimage/
                    <?php echo @$allresult['userimg']; ?>" alt="user" class="" >
                    <?php
                }
                else 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" >
                    <?php
                }
                ?>
                <?php echo ucfirst($username); ?>
            </div>
            <div class="activeuserdetial">
                <?php echo $row['Titile']; ?></div>
                <?php 
                $start  = date_create($row['createdtime']);
        $end    = date_create(); // Current time and date
        $diff   = date_diff($start, $end );
        ?>
        <div class="activeusertime">
            <div class="badge badge-pill badge-primary">
                <?php 
                if($diff->i<60 && $diff->h==0 && $diff->d==0 && $diff->m==0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->i . ' Minutes ago ';
                }
                if($diff->i<60 && $diff->h>0 && $diff->d==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->h . ' Hours ago ';
                }
                if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m==0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->d . ' Days ago ';
                }
                if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m>0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->m . ' Months ago ';
                }
                if($diff->i>0 && $diff->h>0 && $diff->d>0 && $diff->m>0 && $diff->y>0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->y . ' Years ago ';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
<?php  } 
}
// =============  for All Activities ========================
if(isset($_POST["all"]))
{
    $query = " SELECT * FROM Activities JOIN users ON users.id=Activities.UserID WHERE (Activities.createdtime <= CURDATE() AND DATE(Activities.createdtime) < DATE(CURDATE() - INTERVAL 1 DAY) AND CURDATE()) AND (users.sid=:id OR users.id=:id) ORDER BY `Activities`.`createdtime` DESC LIMIT ".$_POST["startAll"].", ".$_POST["limitAll"]." ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    @$result_activiet_all = $stmt->fetchAll();
    foreach (@$result_activiet_all as $row) 
    {
        ?>
        <div class="activedatea">
            <div class="activeuserimage">
                <?php 
                $id=$row['UserID'];
                $stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $allresult = $stmt->fetch(PDO::FETCH_ASSOC);
                $img=@$allresult['userimg'];
                $username=@$allresult['username'];
                if (empty($img)) 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" >
                    <?php
                } 
                elseif (file_exists(DOCUMENT_ROOT.'/assets/userimage/'.$img)) 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/userimage/
                    <?php echo @$allresult['userimg']; ?>" alt="user" class="" >
                    <?php
                }
                else 
                {
                    ?>
                    <img data-sid="<?= $sid ?>" class="ViewUserInfo" src="
                    <?php echo base_url; ?>/assets/images/noimage.png" alt="user" class="" >
                    <?php
                }
                ?>
                <?php echo ucfirst($username); ?>
            </div>
            <div class="activeuserdetial">
                <?php echo $row['Titile']; ?></div>
                <?php 
                $start  = date_create($row['createdtime']);
        $end    = date_create(); // Current time and date
        $diff   = date_diff( $start, $end );
        ?>
        <div class="activeusertime">
            <div class="badge badge-pill badge-primary">
                <?php 
                if($diff->i<60 && $diff->h==0 && $diff->d==0 && $diff->m==0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->i . ' Minutes ago ';
                }
                if($diff->i<60 && $diff->h>0 && $diff->d==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->h . ' Hours ago ';
                }
                if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m==0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->d . ' Days ago ';
                }
                if($diff->i<60 && $diff->h<24 && $diff->d>0 && $diff->m>0 && $diff->y==0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->m . ' Months ago ';
                }
                if($diff->i>0 && $diff->h>0 && $diff->d>0 && $diff->m>0 && $diff->y>0)
                { 
                    echo '<i class="far fa-clock"></i> ' .  $diff->y . ' Years ago ';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
<?php  } 
}
?>