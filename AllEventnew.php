<?php
require_once('function.php');
if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
    header("Location: index.php");die;
}

$db3=new db();
            if(isset($_SESSION['UserID']))
            {
            $id=$_SESSION['UserID'];
            $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $Country = $result['country'];
            @$schcreateprmistion=$result['SchedulesCreate'];
            $ClientsLimit=$result['ClientsLimit'];
            $sid=$result['sid'];
            $usertype=$_SESSION['usertype'];
           
       
            // $stmt2=$db3->prepare("SELECT * FROM `Service` WHERE createdfk=:id"); 

            $stmt2=$db3->prepare("SELECT Service.* FROM `Service` JOIN users ON (Service.createdfk=users.id OR Service.createdfk=users.adminid OR Service.createdfk=users.sid) WHERE users.id=:id GROUP BY Service.id"); 
            $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();  
            $result_event2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $result_noofserveiv=$stmt2->rowCount(); 
            


            $id=$_SESSION['UserID'];
            $stmt= $db3->prepare("SELECT * FROM `event_defult` WHERE UserID=:id"); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result_event = $stmt->fetch(PDO::FETCH_ASSOC);   
            $googlesync=$result_event['googlesync'];
        }

if($schcreateprmistion==0){
     header("Location: index.php");die;  
 }

 if(isset($_POST['curntuser']))
        {
             $db=new db();
            $id = $_POST['curntuser'];
            $eidtClient = $db->prepare("SELECT * from `clients` where createdfk=:id");
            $eidtClient->bindValue(":id",$id,PDO::PARAM_INT);
            $editfile=$eidtClient->execute();
            $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);
            

              if($editfile)
              {
                  echo  json_encode(["resonse"=>$all]);die;

              }
              else
              {
                  echo  json_encode(["error"=>"Sorry You No Client"]);die;              
              }
            
        }

    if(isset($_REQUEST['ClientsName']))
    {
        $db=new db();
       $ClientsName=$_POST['ClientsName']; 
      $eidtClient = $db->prepare("SELECT * from `clients` where id=:ClientsName");
      $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

      if($editfile)
      {
          echo  json_encode(["resonse"=>$all]);die;

      }
    }

    if(isset($_POST['cid']))
{
    $db=new db();
    $cid=$_POST['cid'];
    $FirstName=$_POST['FirstName'];
    $LastName=$_POST['LastName'];
    $Phone=$_POST['Phone'];
    $Email=$_POST['Email'];
    $Address=$_POST['Address'];
    $Zip=$_POST['Zip'];
    $City=$_POST['City'];
    $State=$_POST['State'];
    $country=$_POST['country'];

    $stmt=$db->prepare("UPDATE clients SET firstname=:FirstName, lastname=:LastName, email=:Email, Phone=:Phone, Address=:Address, Zip=:Zip, City=:City, State=:State, Country=:country  WHERE id=:cid");
            $stmt->bindparam(":FirstName",$FirstName);
            $stmt->bindparam(":LastName",$LastName);
            $stmt->bindparam(":Email",$Email);
            $stmt->bindparam(":Phone",$Phone);
            $stmt->bindparam(":Address",$Address);
            $stmt->bindparam(":Zip",$Zip);
            $stmt->bindparam(":City",$City);
            $stmt->bindparam(":State",$State);
            $stmt->bindparam(":country",$country);
            $stmt->bindparam(":cid",$cid);
            $stmt->execute();

            if($stmt)
            {
                echo json_encode(['resonse'=>'Client Profile has been Updated']);die;
            }
            else
            {
                echo json_encode(['error'=>'sorry something wrong']);die;
            }
}
    $db=new db();
    $id=$_SESSION['UserID'];
    $stmtp=$db->prepare("SELECT * FROM `MemberPackage` WHERE createdfk=:id");
    $stmtp->bindparam(":id",$id);
    $stmtp->execute();
    $member_packagelist = $stmtp->fetchAll(PDO::FETCH_ASSOC);

    $id= $_SESSION['UserID'];
    $user = $db->prepare("SELECT * FROM `users` WHERE `adminid` =:id AND `usertype`='employee'");
    $user->bindParam(':id', $id, PDO::PARAM_INT);
    $user->execute();
    $alluser=$user->fetchAll();


    $id= $_SESSION['UserID'];
    $user2 = $db->prepare("SELECT * FROM `users` WHERE `adminid` =:id");
    $user2->bindParam(':id', $id, PDO::PARAM_INT);
    $user2->execute();
    $alluser2=$user2->fetchAll();


if(isset($_POST['selected_stat_Package']))
{
          $id = $_POST['selected_stat_Package'];
          $package_sd = $_POST['package_sd'];
          $LoginQuery = $db->prepare("SELECT Tracking FROM MemberPackage WHERE id=:id");
          $LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
          $LoginQuery->execute();
          $result = $LoginQuery->fetch(PDO::FETCH_ASSOC);
          @$days=$result['Tracking'];

          if($days=='Weekly')
        {
        $package_ed = strtotime($package_sd);
        $package_ed = strtotime("+7 day", $package_ed);
        $package_ed =date('Y-n-d', $package_ed);
        
        echo  json_encode(["resonse"=>$package_ed]);die;

        }
        elseif($days=='Bi-Weekly')
        {
        $package_ed = strtotime($package_sd);
        $package_ed = strtotime("+14 day", $package_ed);
        $package_ed =date('Y-n-d', $package_ed);       
        echo  json_encode(["resonse"=>$package_ed]);die;
        }
        elseif($days=='Monthly')
        {
         $package_ed = strtotime($package_sd);
        $package_ed = strtotime("+1 Months", $package_ed);
        $package_ed =date('Y-n-d', $package_ed);       
        echo  json_encode(["resonse"=>$package_ed]);die; 
        }
        else
        {
          $package_ed=$package_sd;
          echo  json_encode(["resonse"=>$package_ed]);die;
        }

}

    if(isset($_REQUEST['Servicename']))
    {
      
       $Servicename=$_POST['Servicename']; 
      $eidtClient = $db->prepare("select Users,Price,CommissionAmount,Duration from `Service` where id=:Servicename");
      $eidtClient->bindValue(":Servicename",$Servicename,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

      if($editfile)
      {
          echo  json_encode(["resonse"=>$all]);die;

      }
    }

     if(isset($_REQUEST['customersid2']))
    {
       $customersid2=$_POST['customersid2']; 
      $eidtClient2 = $db->prepare("select * from `clients` where id=:customersid2");
      $eidtClient2->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
      $editfile2=$eidtClient2->execute();
      $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
      if($editfile2)
      {
          echo  json_encode(["resonse"=>$all2]);die;
      }
      
    }

    if(isset($_REQUEST['UserName']))
    {
      
       $UserName=$_POST['UserName']; 
      $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id=:UserName OR id=:userid");
      $eidtUserName->bindValue(":UserName",$UserName,PDO::PARAM_STR);
      $eidtUserName->bindValue(":userid",$_SESSION['UserID'],PDO::PARAM_STR);
      $editfile2=$eidtUserName->execute();
      $all2=$eidtUserName->fetchAll(PDO::FETCH_ASSOC);

      if($editfile2)
      {
          echo  json_encode(["resonse"=>$all2]);die;

      }
    }    

 if(isset($_REQUEST['useradd']))
    {
      
      $UserName=$_POST['useradd']; 
      $eidtUserName2 = $db->prepare("select * from `users` where id=:UserName");
      $eidtUserName2->bindValue(":UserName",$UserName,PDO::PARAM_STR);
      $editfile3=$eidtUserName2->execute();
      $allua=$eidtUserName2->fetch(PDO::FETCH_ASSOC);

      if($editfile3)
      {
        $adminid = $allua['adminid'];
        if($adminid){
          $ownerquery = $db->prepare("SELECT * FROM `users` where id=:adminid");
          $ownerquery->bindValue(":adminid",$adminid,PDO::PARAM_STR);
          $ownerfile = $ownerquery->execute();
          $allowner = $ownerquery->fetch(PDO::FETCH_ASSOC);

          if($ownerfile)
          {
              echo  json_encode(["resonse"=>$allowner]);die;
          }
        }else{
          echo  json_encode(["resonse"=>$allua]);die;
        }
      }
    }
if(isset($_REQUEST['service_star_time']))
    {
      $service_star_time=$_POST['service_star_time'];
      $serivename=$_POST['serivename']; 
      $eidtClient = $db->prepare("select Duration from `Service` where id=:serivename");
      $eidtClient->bindValue(":serivename",$serivename,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
      $Duration=$all['Duration'];

        if($Duration=='30 Min')
        {
            $time = strtotime($service_star_time);
            $time = date("g:ia", strtotime('+30 minutes', $time));
           echo  json_encode(["resonse"=>$time]);die;
        }

        if($Duration=='1 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }

        if($Duration=='2 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*2;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }

        if($Duration=='3 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*3;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }

        if($Duration=='4 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*4;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }

        if($Duration=='5 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*5;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        if($Duration=='6 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*6;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        if($Duration=='7 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*7;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }  
        if($Duration=='8 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*8;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        if($Duration=='9 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*9;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }  
        if($Duration=='10 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*10;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        if($Duration=='11 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*11;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        if($Duration=='12 h')
        {
            $timestamp = strtotime($service_star_time) + 60*60*12;
            $time = date('g:ia',$timestamp);
           echo  json_encode(["resonse"=>$time]);die;
        }
        
    }
 if(isset($_POST['CountrysName']))
{
  $CountrysName=$_POST['CountrysName']; 
      $eidtClient = $db->prepare("SELECT * FROM `countries`JOIN provinces ON countries.cid=provinces.country_id WHERE countries.countries_name=:CountrysName ORDER BY provinces.name");
      $eidtClient->bindValue(":CountrysName",$CountrysName,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);

      if($editfile)
      {
          echo  json_encode(["resonse"=>$all]);die;

      }
}

$id=$_SESSION['UserID'];
$Findnotavi = $db->prepare("SELECT NotAvailable.id as notaviid,NotAvailable.OnDate,users.username FROM NotAvailable JOIN users ON NotAvailable.name=users.id WHERE createdfk=:id");
$Findnotavi->bindParam(':id', $id, PDO::PARAM_INT);
$Findnotavi->execute();
$resultFindnotavi =$Findnotavi->fetchAll();   

$listofeventforca= $db->prepare("SELECT title,id,EventDate FROM `event` WHERE event.createdfk=:id AND event.EventDate>= NOW() AND event.EventDate<= NOW() + INTERVAL 30 DAY");  
$listofeventforca->bindParam(':id', $id, PDO::PARAM_INT);
$listofeventforca->execute();
$result_listofeventforca = $listofeventforca->fetchAll();


if(isset($_POST['Nuid']))
{
  $Nuid=$_POST['Nuid'];
  $bdate=$_POST['bdate']; 
  $createdfk=$_SESSION['UserID'];   

  $db=new db();
  $insert_data=$db->prepare("INSERT INTO NotAvailable(Name,OnDate,createdfk) VALUES(:Nuid, :bdate, :createdfk)");
    $insert_data->bindparam(":Nuid",$Nuid);
    $insert_data->bindparam(":bdate",$bdate);
    $insert_data->bindparam(":createdfk",$createdfk);
    $insert_data->execute();
    if($insert_data)
    {
      $myactivite = new Activites(); // This function for data insert in Activities
      $Titile=$myactivite->Titile = 'Assigned Not Available Employee on '.$bdate;
      $myactivite->commit_acitve($Titile);
      echo json_encode(['resonse'=>'successfully ']);die;
    }

}

if(isset($_POST['dlink']))
{
  
  $myevent = base64_decode($_POST['dlink']);
  $DeleteClient = $db->prepare("DELETE from `event` where id=:myevent");
  $DeleteClient->bindValue(":myevent",$myevent,PDO::PARAM_INT);
  $DeleteClient->execute();

  $DeleteClientA = $db->prepare("DELETE from `CountActivites` where AppointmentCreate=:myevent");
  $DeleteClientA->bindValue(":myevent",$myevent,PDO::PARAM_INT);
  $DeleteClientA->execute();
    echo json_encode(['resonse'=>'Appointment successfully deleted']);die;

}
if(isset($_POST['elink']))
{
  $myevent = base64_decode($_POST['elink']);
  $EditEvent=$db->prepare("SELECT event.*,clients.ProfileImg FROM `event` LEFT JOIN clients ON event.cid=clients.id WHERE event.id=:myevent");
    $EditEvent->bindValue(":myevent",$myevent, PDO::PARAM_INT);
    $EditEvent->execute();
    $result = $EditEvent->fetch();

echo json_encode(['resonse'=>$result]);die;
}

if(isset($_POST['serpro'])){
  $stmt = $db->prepare("SELECT * FROM `users` WHERE id=?");
  $stmt->execute([$_POST['serpro']]);
  $result = $stmt->fetch();

  echo json_encode(['resonse'=>$result]);die;
}
$db= new db();

   $statement=$db->prepare("SELECT * FROM `countries` ");

   $statement->execute();

   $countryList = $statement->fetchAll(PDO::FETCH_ASSOC);

   

   $statement2=$db->prepare("SELECT * FROM `provinces` ");

   $statement2->execute();

   $stateList = $statement2->fetchAll(PDO::FETCH_ASSOC);
    

 $button5= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C5'"); 
    $button5->execute();
    $all_button5 = $button5->fetch(PDO::FETCH_ASSOC);
    $B5=$all_button5['button_name'];

    $button6= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C6'"); 
    $button6->execute();
    $all_button6 = $button6->fetch(PDO::FETCH_ASSOC);
    $B6=$all_button6['button_name'];   

    $button10= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C10'"); 
    $button10->execute();
    $all_button10 = $button10->fetch(PDO::FETCH_ASSOC);
    $B10=$all_button10['button_name'];

    $button11= $db->prepare("SELECT button_name FROM `ButtonSetting` where button_id='C11'"); 
    $button11->execute();
    $all_button11 = $button11->fetch(PDO::FETCH_ASSOC);
    $B11=$all_button11['button_name'];

    $button12= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C12'"); 
    $button12->execute();
    $all_button12 = $button12->fetch(PDO::FETCH_ASSOC);
    $B12=$all_button12['button_name'];

    $button13= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C13'"); 
    $button13->execute();
    $all_button13 = $button13->fetch(PDO::FETCH_ASSOC);
    $B13=$all_button13['button_name'];


    $button14= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C14'"); 
    $button14->execute();
    $all_button14 = $button14->fetch(PDO::FETCH_ASSOC);
    $B14=$all_button14['button_name'];


    $button15= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C15'"); 
    $button15->execute();
    $all_button15 = $button15->fetch(PDO::FETCH_ASSOC);
    $B15=$all_button15['button_name'];

    $button16= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C16'"); 
    $button16->execute();
    $all_button16 = $button16->fetch(PDO::FETCH_ASSOC);
    $B16=$all_button16['button_name'];

    $button17= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C17'"); 
    $button17->execute();
    $all_button17 = $button17->fetch(PDO::FETCH_ASSOC);
    $B17=$all_button17['button_name'];

    $button18= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C18'"); 
    $button18->execute();
    $all_button18 = $button18->fetch(PDO::FETCH_ASSOC);
    $B18=$all_button18['button_name'];

    $button19= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C19'"); 
    $button19->execute();
    $all_button19 = $button19->fetch(PDO::FETCH_ASSOC);
    $B19=$all_button19['button_name'];

    $button20= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C20'"); 
    $button20->execute();
    $all_button20 = $button20->fetch(PDO::FETCH_ASSOC);
    $B20=$all_button20['button_name'];

    $title1= $db->prepare("SELECT TitleName FROM `PageTitle` where id='4'"); 
    $title1->execute();
    $all_title1 = $title1->fetch(PDO::FETCH_ASSOC);
    $T1=$all_title1['TitleName'];


?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link href="../assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css">
<!-- <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css"> -->
<link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="../dist/css/lightbox.min.css">

<style>
  #myModal_data{display: none; background: red; z-index: 9999; width: 500px; height: 500px;}
span#cal-title-on-day{font-size: 10px;}
div#cal-week-box{z-index: 9999;}
ul#eventlist2{display: block !important;}
div#cal-slide-box{background: #333!important;}
.alleventlist {padding: 25px;}
.eventlisttitle{background: #3cabe1;padding: 10px;}
.eventlist{background: antiquewhite;  padding: 10px;}
.eventlist2 a{    color: #333;   text-decoration: none;    text-transform: capitalize;}
div#home2 {padding: 10px 0;}

div#myModal_new2 .modal-body{overflow: scroll;   height: 600px;}
/* div#myModal_exit2 .modal-body{overflow: scroll;   height: 600px;}*/
.eventStart1{width: 45%; float: left; padding: 0 10px;}
span.month,span.year{padding: 5px 10px;}
  .table-condensed tr{text-align: center;}
.dropbtn_cal { background-color: #dddddd;   color: black;   padding: 7px;   border-radius: 4px;   border: none;    cursor: pointer;}
.dropdown_cal { position: relative;    display: inline-block; margin: 0 10px;}
.dropdown-content_cal { display: none; position: absolute; z-index: 1;}
.dropdown-content_cal button { color: black; text-decoration: none; display: block; height: 35px; width: 100%; margin: 0px 0;background-color: #f7f3f3 ;}
.dropdown-content_cal button:hover {background-color: #dddddd;}
.show_cal{display:inline-grid;}
input.date.end.form-control{width: 49%;  padding: 0 5px;   }
 input.time.end.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;  }
 input.time.start.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;   }
 input.date.start.form-control{width: 49%;  padding: 0 5px;  }
/*select.form-control:not([size]):not([multiple]),.select2-container--default .select2-selection--single{border:0; border-bottom: 1px solid #aaa!important; border-radius:0!important;}
select#eventstatus{ border-bottom: 1px solid #fff !important ; }*/
.exit-client-image{object-fit: cover;}
.pac-container.pac-logo{z-index: 99999;}
.btn-success:hover {    color: #fff!important;    background-color: #288ebf!important;    border-color: #288ebf!important;}
.sada {padding: 20px 0 0 20px;}
html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .lb-details{display: none!important;}
      .pac-container.pac-logo{z-index: 99999;}
      .lb-container{    position: absolute;    right: 0;    left: 0; }
      .lb-outerContainer{width: 50%!important;}
  img.lb-image{margin: 0 auto!important;}
      .lb-dataContainer{width: 75%!important; margin: unset!important;}
      .lightbox{top: 150px!important;}

      @media only screen and (max-width: 768px) 
      {
    .lightbox{top: 500px!important}
    .lb-outerContainer{width: 100%!important;}
    .lb-dataContainer{width: 92%!important; margin: unset!important;}
    }
    
    .event-grey{
      background-color: grey;
    }
    .event-red{
      background-color: red;
    }
    .cal-day-hour-part{
      cursor: pointer;
    }
    .wactive{
    color: #fff;
    background-color: #3cabe1;
  }
  .repeat{
    padding: 14px;
  }
  @media (max-width: 1084px){
    .repeat{
      padding: 10px;
    }
  }
@media screen and (min-device-width: 768px) and (max-device-width: 768px) { 
     .repeat{
      padding: 3px;
    }
}
</style>

<body class="skin-default fixed-layout mysunlessD">
     <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php echo $_SESSION['UserName']; ?></p>
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
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor"><?php echo $T1; ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                       
                    </div>
                </div>
            </div>

            <div class="row">
               <div class="col-md-12">
                  <div class="card">
                     <div class="card-body">
                        <ul class="nav nav-tabs customtab" role="tablist">
                           <li class="nav-item">
                              <a href="#" id="addnewappointment" class="nav-link" data-toggle="modal" data-target="#myModal">
                                 <span class="hidden-sm-up"><i class="fa fa-plus"></i></span> 
                                 <span class="hidden-xs-down">Add Appointment</span>
                              </a>
                           </li>
                                 
                           <li class="nav-item"> 
                              <a class="nav-link active" data-toggle="tab" href="#home2" role="tab">
                                 <span class="hidden-sm-up"><i class="fa fa-calendar"></i></span> 
                                 <span class="hidden-xs-down">Calendar View</span>
                              </a> 
                           </li>
                           <li class="nav-item"> 
                              <a class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                                 <span class="hidden-sm-up"><i class="fa fa-list"></i></span> <span class="hidden-xs-down">List View</span>
                              </a> 
                           </li>

                           <!-- <li class="nav-item"> 
                              <select class="calnder_option">
                                <option>Select Type</option>
                                <option>1</option>
                                <option>2</option>
                              </select>
                           </li> -->
                                
                        </ul>
                                
                        <!-- Tab panes -->
                        <div class="tab-content tabcontent-border">
                        <!--  Start Add appointment tab -->
                           <!--  Start Service select modal -->
                           <div id="myModal" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                              <!-- Modal content-->
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h4 class="modal-title">Select Client</h4>
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                       <!-- <p>Some text in the modal.</p> -->

                                        <div class="Loader"></div>
                                       
                                       <?php
                                          $db5 = new db();
                                          $id=$_SESSION['UserID'];
                                          $total_user = $db5->prepare("SELECT sid FROM `clients` WHERE `createdfk`=:id");
                                          $total_user->bindParam(':id', $id, PDO::PARAM_INT);
                                          $total_user->execute();
                                          $all=$total_user->fetch(PDO::FETCH_ASSOC);
                                          $mysid=$all['sid'];

                                          if($mysid!=0)
                                          {
                                             $db5 = new db();
                                             $id=$_SESSION['UserID'];
                                             $total_user2 = $db5->prepare("SELECT * FROM `clients` WHERE `sid`=:mysid");
                                             $total_user2->bindParam(':mysid', $mysid, PDO::PARAM_INT);
                                             $total_user2->execute();
                                             $number_of_users = $total_user2->rowCount();
                                          }


                                           if(@$clientcreatex != 0){
                                          if($ClientsLimit=='full')
                                          {
                                       ?>
<button type="button" class="btn waves-effect waves-light btn-secondary myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new"><?php echo $B13; ?></button>           
                                       <?php
                                          }
                                          else
                                          {
                                             if(@$number_of_users >= @$ClientsLimit)
                                            
                                             {
                                       ?>
<button type="button" class="btn waves-effect waves-light btn-secondary ClientLimit" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="" data-target=""><?php echo $B13; ?></button>
                                       <?php
                                             }
                                             else
                                             {
                                       ?>
<button type="button" class="btn waves-effect waves-light btn-primary myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new"><?php echo $B13; ?></button>
                                       <?php
                                             }
                                          }
                                        }
                                       ?>
                                    
                                       <!-- <button type="button" class="btn waves-effect waves-light btn-secondary myModal_new" data-toggle="modal" data-target="#myModal_new">New Client</button> -->
<button type="submit" onclick="$('#NewEvent input[name=\'id\']').val('new');" class="btn waves-effect waves-light btn-primary" id="exit-client"><?php echo $B14; ?></button>
<?php 
if($result_noofserveiv<=0)
{
  ?>
<a href=" <?php echo base_url;  ?>/viewService.php" id="addUser" class="btn btn-info m-r-10 ">Add New Service</a>
<?php
}
?>

                                       <div style="padding: 5px 0;">
                                                
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!--  End Service select modal -->
                           <!--  Start Add new client modal -->
                           <div class="modal fade" id="myModal_new2" role="dialog" style="z-index:1100">
                              <div class="modal-dialog">
    
                              <!-- Modal content-->
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h4 class="modal-title">Add New Client</h4>
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                       <form class="form-horizontal" action="" method="post" autocomplete="nope" id="NewClient">
                                        <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
                                          <input type="hidden" name="id" id="id" value="new">
                                          <input type="hidden" name="clinetid" id="" value="">
                                          <?php 
                                             if($usertype=='subscriber')
                                             {
                                          ?>
                                             <input type="hidden" name="sid" id="sid" value="<?php echo $_SESSION['UserID'];?>">
                                          <?php
                                             }
                                             else
                                             {
                                          ?>
                                             <input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>">
                                          <?php
                                             }
                                          ?>
                                          <?php $_SESSION["ClientID"] = @$ClientId ;?>
                                             <div class="form-group">
                                                <label><span class="help"> First Name *</span></label>
                              <input type="text" name="FirstName" id="FirstNameC" class="form-control" placeholder="First Name" autocomplete="nope" value="" maxlength="10">
                                             </div> 
                                             <div class="form-group">
                                                <label><span class="help"> Last Name *</span></label>
                               <input type="text" name="LastName" id="LastNameC" class="form-control" value="" placeholder="Last Name" autocomplete="nope" maxlength="10">
                                             </div>
                                             <div class="form-group">
                                                <label><span class="help"> Phone Number*</span></label>
                           <input type="text" name="Phone" id="phonenumber" class="form-control" value="" autocomplete="nope"  placeholder="(123) 456-7890">
                                             </div>
                                             <div class="form-group">
                                                <label for="example-email">Email * <span class="help"></span></label>
<input type="email" id="example-email" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com" maxlength="30">
                                             </div>
                                   
    
                                                                                                        <div class="form-group">
        <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
        <div class="card">
        <div class="card-body">
<input type="file" id="ProfileImg" name="ProfileImg" class="dropify" >
<input type="hidden" name="ProfileImg"id="oldimage" value="">
<input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
<input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
        </div>
        </div>
         </div>
  <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  
      <img src="http://lorempixel.com/75/50/abstract/">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button> -->
      
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%; margin-bottom: 20px;">  
    Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>


<div class="dropdown-menu" style="width: 96%;">
<?php
  $stmta= $db->prepare("SELECT * FROM `listofavtar`"); 
  $stmta->execute();
  $stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
foreach($stmtall as $row)
{
  ?>
<label style="padding: 5px;">
  <input type="radio" name="ProfileImg" value="<?php echo $row['Name']; ?>" style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer; outline: 2px solid #f00;" >
  <img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px" style="cursor: pointer;">
</label>
<?php

}
?>
</div>
                    
                    <!-- ========================Crop image============================== -->
<div id="uploadimageModal" class="modal" role="dialog" style="z-index: 1101">
  <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Upload & Crop Image</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-md-12 text-center">
              <div id="image_demo"></div>
            </div>
            <div class="col-md-12" style="text-align: center;">
              <br />
              <br />
              <br/>
              <button class="btn btn-success crop_image" type="button">Crop & Upload Image</button>
              <button type="button" class="btn btn-default" id="uploadimageModalasdfdsf"> Skip </button>
          </div>
        </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>
<!-- ========================Crop image============================== --> 
                                             
                                            
                                                  <div class="form-group">
                                <label for="example-email">Address *</label>
 <input id="autocomplete" placeholder="Enter your address"  class="form-control" name="Address"  onFocus="geolocate()" type="text" value=""></input>

                                
<input type="hidden" class="" id="street_number" disabled="true"></input>
<input type="hidden" class="" id="route" disabled="true"></input>
                                </div>


                                <div class="form-group">
                                <label for="example-email">Zip Code *</label>
                                <input type="text"  id="postal_code" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="0123456" maxlength="10"></input>
                                </div>

                                <div class="form-group">
                                <label for="country">Country *</label>
                                <select class="form-control" id="country" name="Country">
                                <option value="">Select a Country</option>
                                          <?php
                                              foreach($countryList as $value)
                                              {
                                                
                                                
                                                   echo "<option value='".$value['countries_name']."'>".$value['countries_name']."</option>";
                                                }

                                             
                                            ?>
                                </select>
                                   </div>


                                     <div class="form-group">
                                          <label><span class="help">State *</span></label>
                                          <select class="form-control" id="administrative_area_level_1" name="State">
                                                <option value="">Select a State</option>
                                              <?php
                                                 foreach($stateList as $value){

                                                   echo "<option value='".$value['name']."'>".$value['name']."</option>";


                                             }

                                          ?>

                                      </select>
                                        </div>

                                    <div class="form-group">
                                    <label for="example-email">City *</label>
                                      <input  id="locality" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
                                    </div>



                                                <div class="form-group">

<button type="submit" class="btn waves-effect waves-light btn-info m-r-10" name="add-client" id="add-client"><i class="fa fa-check"></i> <?php echo $B18; ?></button>
<button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B19; ?></button>
                                                    <!-- <a  class="btn btn-waves-effect waves-light btn-secondary back " id="back" ><?php echo $B20; ?></a> -->
                                                   
                                                   <div class="col-lg-12 col-md-12" style="padding: 15px 0;">
                                                      <div class="alert alert-success" id="resonse" style="display: none;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                         <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonsemsg"></p>
                                                      </div>
                                                      <div class="alert alert-danger" id="error" style="display: none;">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                              <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="errormsg"></p>
                                                      </div>

                     <div class="alert alert-danger" id="csrf_error" style="display: none;">
                    <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
                    </div>

                                                   </div>
                                                </div>
                                                  <div class="Loader"></div>
                                             </form>
                                          </div>
                                          <div class="modal-footer">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--  Start Add new client modal -->
                                 <!-- Start Add appointment modal for new or existing client  -->
                                 
                                 <!--  End add new appointment modal -->

                                    
                                  <div class="modal fade" id="calmyModal" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                    
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h4 class="modal-title"><span class="today_date"></span></h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                            <button class="tohsedata btn btn-info m-r-10" data-current-cal-date=""><?php echo $B11; ?></button>
                                            <button class="addevet_tohsedata btn btn-info m-r-10"><?php echo $B12; ?></button>
                                              </div>
                                                    <div class="form-group">
                                                   <label>Not Available *</label>   
<select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Employee" id="NotAvailableEmp" name="NotAvailableEmp">
                                                    <option value="">Select Employee</option>   

                                                     <?php

                                                      $id= $_SESSION['UserID'];
                                                      $userb = $db->prepare("SELECT * FROM `users` WHERE `adminid` =:id AND usertype='employee'");
                                                      $userb->bindParam(':id', $id, PDO::PARAM_INT);  
                                                      $userb->execute();
                                                      $alluserb=$userb->fetchAll();
                                                        
                                                        foreach($alluserb as $value)
                                                         {?>
                                                           <option value="<?php echo $value['id']; ?>"><?php echo $value['username']; ?></option>
                                                              
                                                        <?php  
                                                           
                                                        }
                                                     ?>
                                                  </select>
                                              </div>
                                              <div class="Loader"></div> 



                                        </div>
                                      </div>
                                      
                                    </div>
                                  </div>


                              <!--  End Add appointment tab -->
                                 <!-- star Calendar View Tab -->
                                 <div class="tab-pane active" id="home2" role="tabpanel">

                                    <div class="page-header">

                                       <div class="pull-right form-inline">
                                        <img src="<?= base_url?>/assets/images/calendarbtn.png" data-toggle="tooltip" title="Month Calendar" width="37" height="35" id="monthbtn" style="cursor: pointer;" />
                                        <div class="dropdown_cal">
                                             <button onclick="myFunction2()" class="dropbtn_cal dropdown-toggle">Filter</button>
                                             <div id="myDropdown_cal2" class="dropdown-content_cal">
 <div class="btn-group"><button class="btn btn-default myapp notave" id="myapp" data-id="<?php echo $_SESSION['UserID']; ?>" >My Appointment</button></div>
                                                <div class="btn-group"><button class="btn btn-default byuser" id="byuser" >Filter By User</button></div>
                                                
                                                <div class="caluserlist" id="caluserlist" style="display: none;">
                                                  <select class="select2 m-b-10 select2-multiple notave" style="width: 100%"  data-placeholder="Choose User" id="listofcaluser" name="listofcaluser">
                                                     <option value="">Select Employee</option>
                                                     <?php
                                                        foreach($alluser2 as $value)
                                                         {?>
                                                           <option value="<?php echo $value['id']; ?>"><?php echo $value['username']; ?></option>
                                                              
                                                        <?php  
                                                           
                                                        }
                                                     ?>
                                                  </select>
                                                </div>

                                                <div class="btn-group">
                                                  <button class="btn btn-default byservice" id="byservice" >Filter By Service</button></div>
                                                
                                                <div class="calserlist" id="calserlist" style="display: none;">
                                                  <select class="select2 m-b-10 select2-multiple notave" style="width: 100%"  data-placeholder="Choose Service" id="listofcalser" name="listofcalser">
                                             <option value="">Select Service</option>
                                             <?php 
                                                foreach($result_event2 as $row2)
                                                {
                                             ?>
                                                <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                                             <?php
                                                }
                                             ?>
                                          </select>
                                                </div>
                                             </div>
                                          </div>


                                          <div class="btn-group">
                                             <button class="btn btn-primary notave" data-calendar-nav="prev"><< Prev</button>
                                             <button class="btn btn-default notave" data-calendar-nav="today">Today</button>
                                             <button class="btn btn-primary notave" data-calendar-nav="next">Next >></button>
                                          </div>
                                          <div class="dropdown_cal">
                                             <button onclick="myFunction()" class="dropbtn_cal dropdown-toggle">Month</button>
                                             <div id="myDropdown_cal" class="dropdown-content_cal">
                                                <div class="btn-group"><button class="btn btn-default" data-calendar-view="year">Year</button></div>
                                                <div class="btn-group"><button class="btn btn-default active notave" id="notave" data-calendar-view="month">Month</button></div>
                                                <div class="btn-group"><button class="btn btn-default" data-calendar-view="week">Week</button></div>
                                                <div class="btn-group"><button class="btn btn-default" data-calendar-view="day">Day</button></div>
                                             </div>
                                          </div>

                                       </div>
                                       <h3></h3>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-9">
                                        <div id="nycal" style="overflow-x: scroll;">
                                          <div id="showEventCalendar"></div>
                                          </div>
                                       </div>
                                       <div class="col-md-3">
                                         <div class="sada">
                                                <span class="pull-left event event-grey"></span> : Pending <br>
                                                <span class="pull-left event event-success"></span> : Confirmed <br>
                                                <span class="pull-left event event-important"></span> : Canceled <br>
                                                <span class="pull-left event event-red"></span> : No-show <br>
                                                <span class="pull-left event event-warning"></span> : In Progress <br>
                                                <span class="pull-left event event-info"></span> : Completed <br>
                                                
                                           </div>
                                          <div class="alleventlist">

                                             <div class="eventlisttitle"><h4 style="color: white;">All Appointment List</h4></div>
                                             <div class="eventlist" style="background-color: #80808014;">
                                              <ul id="eventlist2" class="nav nav-list">

                                              <?php 
                                              foreach ($result_listofeventforca as $key => $value)
                                               {
                                                
                                                ?>
                                                
                        <li><a class="EditButton" href="#" data-id="<?php echo base64_encode($value['id']); ?>"><?php echo $value['title']; ?><b class="pull-right"><?php echo date('d/m',strtotime($value['EventDate']));?></b></a></li>

                        <?php
                      }
                                              ?>    
                                              
                                             </ul>
                                         </div>

                                          </div>
                                       </div>
                                    </div>
                                 </div>

                                 <!--  End Calendar View tab -->
                                 <div id="myModal_data"></div>
                                 <!--  Start List View tab -->
                                 <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                                    <div class="col-lg-12">
                    <div class="d-flex align-items-center">
                                        <select class="custom-select w-25 ml-auto" id="UpcomingRenewalsDays">
                                        <option selected value="All">All</option>
                                        <option  value="Last">Last 30 Days </option>
                                        <option value="Next">Next 30 Days</option>
                                        </select>
                                            </div>      

                                       <div class="table-responsive m-t-40">
                                          <table id="myTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
                                             <thead>
                                                <tr>
                                                   <th>Title</th>
                                                   <th>Client Name</th>
                                                   <th>Contact</th>
                                                   <th>Status</th>
                                                   <th>Appointment Time</th>
                                                   <!-- <th>Event End</th> -->
                                                   <th>Action</th>
                                                </tr>
                                             </thead>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 <!--  End List View tab -->

<div class="modal fade" id="eventdetailmodel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Event Detail</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
          <div>
            Customer Name : <span id="evnetcutomername"></span><br>
            Service : <span id="envetsername"></span><br>
            Start Time : <span id="evnetstartime"></span><br>
            Employee : <span id="evnetemployename"></span><br><br><br>

   <a href="#"  id="ViewEvent" class="btn btn-info EditButton" data-id="" style="width: 100%;">Edit Appointment </a><br><br>

<a href="" name="DeleteEvent" data-event-id="<%= event.id %>" id="DeleteEvent" class="btn btn-danger" style="width: 100%;">Delete Appointment </a><br><br>

<a href="" style="width: 100%;" name="ViewCustomer" data-event-id="<%= event.cid %>" id="ViewCustomer" class="btn btn-success" >View Cutomer Profile </a><br><br>

<button type="button" class="btn btn-warning cusomerlocation" id="cusomerlocation" data-id='' style="width: 100%;"> Directions to Appointment </button>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

  <div class="modal fade" id="eventmap" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">

         <div class="modal-header">
        <h4 class="modal-title" id="onlyeventadders"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
        
        <div class="modal-body">
         <div>
     
     <div class="map-box">
     <iframe id="eventaddermap" src="" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
</div>      
<!-- <input class="form-control register-form__location-holder" type="text" id="user_location" name="user_location" placeholder="Enter place name" value="<?php echo $user_city ;?>" type="text"/>
<div style="width:100%;height:300px" id="register-form__map" class="register-form__map register-form__map--user"></div> -->

         </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


                        </div>
                     </div>
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

    <div class="modal fade" id="myModal_exit2"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="max-width: 1100px;">
        <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Book Appointment with</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
          <input type="hidden" name="id" class="id" id="id" value="new">
          <input type="hidden" name="Location_radio_value" id="evnet_Location_radio" value="Customer Location">
          <input type="hidden" name="UserID" id="UserID"  value="<?php echo $_SESSION['UserID']; ?>">
          <input type="hidden" name="csrf" value="<?php echo $_SESSION['csrf']; ?>">
          <input type="hidden" name="title" id="title" class="form-control" value="" placeholder="Appointment Title" autocomplete="nope" maxlength="20">
          <input type="hidden" name="cid" id="cid" value="">
          <input type="hidden" name="FirstName" id="FirstName" class="form-control" value="" placeholder="First Name" autocomplete="nope" maxlength="10">
          <input type="hidden" name="LastName" id="LastName" class="form-control" value="" placeholder="Last Name" autocomplete="nope" maxlength="10">
          <input type="hidden" name="Phone" id="Phone" class="form-control" value="" autocomplete="nope" placeholder="(123) 456-7890">
          <input type="hidden" id="Email" name="Email" class="form-control" value="" autocomplete="nope" placeholder="example@gmail.com" maxlength="30">
          <input type="hidden" name="Address" id="Address" class="form-control" value="" autocomplete="nope" placeholder="Enter your Address">
          <input type="hidden" name="Zip" id="Zip" class="form-control" autocomplete="nope" placeholder="Zip" value="" maxlength="10">
          <input type="hidden" name="country" id="newcountry" class="form-control" autocomplete="nope" placeholder="country" value="">
          <input type="hidden" name="State" id="State"  autocomplete="nope" class="form-control" placeholder="State" value="">
          <input type="hidden" name="City" id="City" autocomplete="nope" class="form-control" placeholder="City" value="">
          <input type="hidden" name="ServiceName" class="form-control" placeholder="ServiceName" id="ServiceName" autocomplete="nope" value="">
          <input type="hidden" name="ServiceProvider" class="form-control" placeholder="ServiceProvider" id="ServiceProvider" autocomplete="nope" value="">
          <input type="hidden" name="eventstatus" value="pending">
          <input type="hidden" name="wdayshidden" id="wdayshidden" value="">
          <input type="hidden" name="wdateshidden" id="wdateshidden" value="">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4 col-sm-12">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="listofcatagory" id="servicewith">Service *</label>
                        <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service" name="newlistofcatagory" id="newlistofcatagory">
                          <option value disabled="true" selected="selected">Select Service</option>
                          <?php 
                            foreach($result_event2 as $row2)
                              {
                          ?>
                          <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group serviceproviderblock">
                      <label for="listofcatagory3">Service Provider *</label>
                      <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Service Provider" id="listofcatagory3" name="listofcatagory3">
                        <option value="">Select Provider</option>
                      </select>
                      <span style="color: red" id="serviceprovider_error"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="example-email">Appointment Date/Time  *<span class="help"></span></label>
                  <p id="datepairExample">
                    <input type="text" class="date start form-control" placeholder="Start Date" name="sd" autocomplete="nope"  id="eventstardate" />
                    <input type="text" placeholder="Start Time" class= "time start form-control" name="st" autocomplete="nope" id="eventstartime" />

                    <input type="hidden" class="time end form-control" placeholder="End Time" name="et" autocomplete="nope" id="eventendtime"  />
                    <input type="hidden" class="date start form-control" placeholder="End Date" name="ed" autocomplete="nope"  id="eventenddate" />
                  </p>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Cost of Service: * </label> 
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" name="CostOfService" id="CostOfService" class="form-control" autocomplete="nope" placeholder="" value="<?php if(!empty($result_event['CostOfService'])) { echo $result_event['CostOfService'];}else{ echo @$CostOfService;} ?>" >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Duration: </label> 
                      <div class="input-group mb-3">
                        <input type="text" id="duration" readonly="true" class="form-control">
                        <div class="input-group-prepend">
                          <span class="input-group-text minhour"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label>Appointment Status  * </label>
                  <select name="eventstatus" id="eventstatus" class="form-control" >
                    <option value=""> Select Appointment current Status </option>
                    <option value="pending"> Pending </option>
                    <option value="confirmed"> Confirmed </option>
                    <option value="canceled"> Canceled </option>
                    <option value="no-show"> No-Show </option>
                    <option value="in-progress"> In Progress </option>
                    <option value="completed"> Completed </option>
                  </select>
                </div> -->
                <label>Location : *</label>
                <div class="row">
                  <div class="col-sm-6 col-md-6 pull-right">
                    <input type="radio" id="Location_radio" name="Location_radio" value="Salon Location" class=""> Salon Location
                  </div>
                  <div class="col-sm-6 col-md-6 pull-right">
                    <input type="radio" id="Location_radio" name="Location_radio" checked="true" value="Customer Location" class=""> Customer Location
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label>Appointment Note  * </label>
                    <textarea class="textarea_editor form-control" rows="4" placeholder="Enter note here ..." id="EmailInstruction" autocomplete="nope" name="EmailInstruction"><?php if(!empty($result_event['EmailInstruction'])) { echo $result_event['EmailInstruction'];}else{ echo @$EmailInstruction;} ?></textarea>
                </div>
                <div class="Loader"></div>
                
                <hr>
                <span id="editspan" style="display: none"></span>
                <div id="repeatdiv">
                  <label>Repeat :</label>
                  <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist" >
                    <li class="nav-item">
                      <a class="repeat nav-link active show" id="offrepeat" data-toggle="pill" href="#pills-off" role="tab" aria-controls="pills-home" aria-selected="true">Off</a>
                    </li>
                    <li class="nav-item">
                      <a class="repeat nav-link" id="daily" data-toggle="pill" href="#pills-daily" role="tab" aria-controls="pills-profile" aria-selected="false">Daily</a>
                    </li>
                    <li class="nav-item">
                      <a class="repeat nav-link" id="weekly" data-toggle="pill" href="#pills-weekly" role="tab" aria-controls="pills-contact" aria-selected="false">Weekly</a>
                    </li>
                    <li class="nav-item">
                      <a class="repeat nav-link" id="monthly" data-toggle="pill" href="#pills-monthly" role="tab" aria-controls="pills-contact" aria-selected="false">Monthly</a>
                    </li>
                    <li class="nav-item">
                      <a class="repeat nav-link" id="yearly" data-toggle="pill" href="#pills-yearly" role="tab" aria-controls="pills-contact" aria-selected="false">Yearly</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-off" role="tabpanel" aria-labelledby="pills-home-tab"></div>
                    <div class="tab-pane fade" id="pills-daily" role="tabpanel" aria-labelledby="pills-profile-tab">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="dendate">End Date *</label>
                            <input type="text" class="datepicker form-control" placeholder="End Date" name="dendate" autocomplete="nope"  id="dendate" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pills-weekly" role="tabpanel" aria-labelledby="pills-contact-tab">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-gorup">
                            <label for="wevery">Every: *</label>
                              <input type="number" class="form-control" id="every" value="1" min="1" max="100">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="dendate">End Date: *</label>
                            <input type="text" class="datepicker form-control" placeholder="End Date" name="wendate" autocomplete="nope"  id="wendate" />
                          </div>
                        </div>
                      </div>
                        <div class="form-group">
                          <label for="">Days: *</label>
                          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="border: 1px solid #dee0e4;border-radius: 5px;">
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-right-radius:0px;border-bottom-right-radius: 0px;display: block;">Sun</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Mon</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Tue</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Wed</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Thu</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-radius: 0px;display: block;">Fri</a>
                            </li>
                            <li class="nav-item text-center" style="width:14.28%">
                              <a class=" wdays" data-toggle="pill" href="#" role="tab"  style="padding: 10px 10px 10px 5px;border-top-left-radius: 0px;border-bottom-left-radius: 0px;display: block;">Sat</a>
                            </li>
                          </ul>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-monthly" role="tabpanel" aria-labelledby="pills-home-tab">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-gorup">
                            <label for="mday">Day: *</label> 
                              <input type="number" class="form-control" name="mday" id="mday" value="1" min="1" max="31">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="mendate">End Date: *</label>
                            <input type="text" class="datepicker form-control" placeholder="End Date" name="mendate" autocomplete="nope"  id="mendate" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-home-tab">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-gorup">
                            <label for="ymonth">Month:*</label>
                              <select name="ymonth" class="form-control" id="ymonth" style="width:100%;padding-left:2px" tabindex="0">
                                <option value="1">Jan</option>
                                <option value="2">Feb</option>
                                <option value="3">Mar</option>
                                <option value="4">Apr</option>
                                <option value="5">May</option>
                                <option value="6">Jun</option>
                                <option value="7">Jul</option>
                                <option value="8">Aug</option>
                                <option value="9">Sep</option>
                                <option value="10">Oct</option>
                                <option value="11">Nov</option>
                                <option value="12">Dec</option>
                              </select>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-gorup">
                            <label for="ydate">Day : *</label>
                              <input type="number" class="form-control" value="1" min="1" max="31" maxlength="2" name="ydate" id="ydate">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="yendate">End Date: *</label>
                            <input type="text" class="datepicker form-control" placeholder="End Date" name="yendate" autocomplete="nope"  id="yendate" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="customer">Customer : </label> <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right" id="editcustomer"><b>Edit</b></button>
                  <select class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Customer" name="newlistofclient" id="newlistofclient">
                  </select>
                </div>
                <div class="form-group">
                  <label for="emaillabel" id="newemail" style="text-decoration: underline;"></label>
                </div>
                <div class="row" id="clinetdetails">
                  <div class="col-md-4 col-lg-4 col-sm-12 col-xs-4">
                    <img src="" alt="No Image" id="clientimage" height="100px" width="100px"> 
                  </div>
    
                  <div class="col-md-8 col-lg-8 col-sm-12 col-xs-8">
                    <br>
                    <label id="newname"></label><br>
                    <label id="newphone"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="alert alert-success" id="resonseAddApp" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                  <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> <p id="resonseAddAppemsg"></p>
                </div>
                <div class="alert alert-danger" id="csrf_error2" style="display: none;">
                  <button type="button" data-dismiss="alert" class="close"> <span aria-hidden="true">&times;</span> </button>
                  <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg2"></p>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="form-group pull-right" style="margin-bottom: 0px;">
              <button type="submit" class="btn waves-effect waves-light btn-info m-r-10"><i class="fa fa-check"></i> <?php echo $B15; ?></button>
              <button type="button" class="btn waves-effect waves-light btn-danger" id="cancelappp" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B16; ?></button>  
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php include 'scripts.php'; ?>

<script src="<?php echo base_url; ?>/assets/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url; ?>/assets/js/buttons.print.min.js" type="text/javascript"></script>



<script>
$(document).ready(function() {
  var newdt = new Date();
  var today = new Date($.now());

  $('.events-list-div').each(function() {
    var newdt = new Date($(this).data('cal-start'));
    if(newdt.getDate() == today.getDate() && newdt.getMonth() == today.getMonth() && newdt.getFullYear() == today.getFullYear()){
      $(this).find('span').css('margin-top','0%');
    }
  });
  //1554834600000
  //1554874106298
 // alert($.now());
  // $('#ydate').datepicker({
  //    format: "mm",
  //   startView: "months", 
  //   minViewMode: "months"
  // });

 // $('#myModal_exit2').modal('toggle');
  $('.datepicker').datepicker();
  
  $('.wdays').click(function(){
    $(this).toggleClass('wactive');
    $('#wdayshidden').val('');
    var days = '';
    var dates = '';
    $('.wactive').each(function(){
      
      days = days +','+ $(this).text();

      var start = $('#eventstardate').val();
      var end = $('#eventenddate').val();

      var startDate = new Date(start);
      var endDate = new Date(end);
      
      var every = $('#every').val();
      for (var i = startDate; i <= endDate; ){
          if (i.getDay() == 0 && $(this).text()=='Sun'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 1 && $(this).text()=='Mon'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 2 && $(this).text()=='Tue'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 3 && $(this).text()=='Wed'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 4 && $(this).text()=='Thu'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 5 && $(this).text()=='Fri'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          if (i.getDay() == 6 && $(this).text()=='Sat'){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              var str = ''+year + '-' + month + '-' + day;
              dates = dates + ',' + str;
          }
          i.setTime(i.getTime() + (1000*60*60*24) * every );
          
     }

    });
    $('#wdayshidden').val(days);
    $('#wdateshidden').val(dates);

  });

  //$('#myModal_exit22').modal('toggle');
  $('#monthbtn').click(function(){
    $('#notave').trigger('click');
  });
  $('#clinetdetails').hide();
  var daf = Math.floor(Math.random() * 40) + 1  
    $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");

  dataTable('all');

  $('#UpcomingRenewalsDays').change(function(){
        var UpcomingRenewalsDays = (this).value ;
        dataTable(UpcomingRenewalsDays);
    });

  function dataTable(UpcomingRenewalsDays)
    {
   $('#myTable').DataTable({
      dom: 'Bfrtip',
         buttons: [{
            extend: 'csvHtml5',
            text: 'Export as CSV',
            exportOptions: {
               modifier: {
                    search: 'none'
               }
            } 
         }],
         "responsive": true,
         "processing" : true,
         "destroy": true,
         "ajax" : {
            "url" : "<?php echo EXEC; ?>ExecAllEvent.php?UpcomingRenewals="+UpcomingRenewalsDays,
            "type":'post',
            "dataSrc" : ''
         },
         "autoWidth": false,
        "columnDefs": [
          { "targets" : '_all'},
          { "width": "14%", "targets": 0, "className" : 'evnet_info1'},
          { "width": "20%", "targets": 1,"className" : 'evnet_info2' },
          { "width": "23%", "targets": 2,"className" : 'evnet_info3' },
          { "width": "15%", "targets": 3,"className" : 'evnet_info4' },
          { "width": "15%", "targets": 4,"className" : 'evnet_info5' },
          { "width": "12%", "targets": 5,"className" : 'evnet_info6' },
          // { "width": "10%", "targets": 6 },
        ],
         "columns" : [
         {
            "data" : "title"
         },
         {
            "data": {ProfileImg:"ProfileImg", FirstName:"FirstName", LastName:"LastName" },
            "render": function(data,type,row){
                if(data.ProfileImg!=''){
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'"" data-lightbox="example-'+data.ProfileImg+'"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span> </div></div>';    
                }
                else
                {
                  return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" data-lightbox="example-noimage.png"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span> </div></div>';       
                }
            }
         }, 
         {
            "data" : {Phone:"Phone",Email:"Email"},
            "render": function(data,type,row){
                return  '<span> Phone: '+ data.Phone +'</span><br><span> Email: '+ data.Email+'</span>';
            }
         },
         {
            "data" : "eventstatus"
         },
         {
            "data" : "EventDate"
         },
         // {
         //    "data" : "end_date"
         // }, 
         {
            "data": "id",
            "render": function(data, type, row) {
                var encodedId = window.btoa(data);
        return '<button class="btn btn-info btn-sm EditButton" title="Edit appointment" id="EditButton" data-id="'+encodedId+'" >' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-danger btn-sm" title="Delete Category" id="deleteButton" data-id="'+encodedId+'" >' + '<span class="fa fa-trash"><span>' + '</button>';
            }
        }]
   });
}
   $(document).on('click','#deleteButton',function(e){
      e.preventDefault();
      $(".Loader").show();
       var dlink = $(this).attr('data-id');

        swal({
                title: "Are you sure?",
                text: "Once deleted, you will lost all data of this Client account!",
                icon: "warning",
                buttons: true,
            }).then((willDelete)=>{   
                if (willDelete){
                                 $.ajax({
                                    dataType:"json",
                                    type:"post",
                                    data:{'dlink':dlink},
                                    url:'?action=deletefile',
                                    success: function(data)
                                    {
                                        if(data.resonse){
                                          $(".Loader").hide();
                                            swal(data.resonse)
                                            dataTable(UpcomingRenewalsDays)
                                        }
                                        else if(data.error){
                                            
                                         $(".Loader").hide();
                                            swal('Something is wrong please try agine')
                                            dataTable(UpcomingRenewalsDays)   
                                            // alert('<li>'+data.error+'</li>');
                                        }
                                    }
                          });
                }
                 else{
                  $(".Loader").hide();
                     return false ;
                 }
            });
   });

   $(document).on('click','.EditButton',function(e){
    $('#wdateshidden').val('');
    $('#repeatdiv').hide();
    $('#offf').trigger('click');
    $('#eventdetailmodel').modal('hide');
    $('.id').val('')
     $(".Loader").show();
      $('#clinetdetails').show();
    var elink = $(this).attr('data-id');
      $.ajax({
                dataType:"json",
                    type:"post",
                    data:{'elink':elink},
                    url:'?action=deletefile',
                    success: function(data)
                      {
                        if(data.resonse)
                        { 
                          if(data.resonse.ProfileImg!='')
                          {
                            $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
                          }
                          else if(data.resonse.ProfileImg=='')
                          {
                            $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
                          }
                          var serpro = data.resonse.ServiceProvider;
                          $.ajax({
                            url: '?action=deletefile',
                            type: 'POST',
                            dataType: 'json',
                            data: {'serpro': serpro},
                            success: function(serpro){
                               $('#myModal_exit2').find('h4').text('Book Appointment with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                               //$('#servicewith').text('Service with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                               $('#editspan').show();
                               $('#editspan').html('<b>Booked With:</b>'+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
                            }
                          });
                          
                          $('#editcustomer').val(btoa(data.resonse.cid));
                          $('.id').val(data.resonse.id);
                          $('#evnet_Location_radio').val(data.resonse.Location_radio);
                          $('#UserID').val(data.resonse.UserID);
                          $("#ServiceName").val(data.resonse.ServiceName);
                          $('#newlistofcatagory').val(data.resonse.ServiceName).trigger('change');
                          $("#title").val(data.resonse.title);
                          $("#cid").val(data.resonse.cid);
                          $('#FirstName').val(data.resonse.FirstName);
                          $('#LastName').val(data.resonse.LastName);
                          $('#Phone').val(data.resonse.Phone);
                          $('#Email').val(data.resonse.Email);
                          $('#Address').val(data.resonse.Address);
                          $('#Zip').val(data.resonse.Zip);
                          $('#City').val(data.resonse.City);
                          $('#State').val(data.resonse.State);
                          $('#newcountry').val(data.resonse.Country); 
                          $('#CostOfService').val(data.resonse.CostOfService);

                          $('#newname').html('<b>Name :</b>' + data.resonse.FirstName + ' ' + data.resonse.LastName);
                          $('#newphone').html('<b>Cell : </b>' + data.resonse.Phone);
                          $('#newemail').text(data.resonse.Email);

                          var arr = data.resonse.EventDate.split(' ');
                          $('#eventstardate').val(arr[0]);
                          $('#eventstartime').val(arr[1]);

                          var arr2 = data.resonse.end_date.split(' ');
                          $('#eventenddate').val(arr2[0]);
                          $('#eventendtime').val(arr2[1]);

                          $('#eventstatus').val(data.resonse.eventstatus);
                          $('#ServiceProvider').val(data.resonse.ServiceProvider);
                          
                          $('#EmailInstruction').val(data.resonse.EmailInstruction);
                          
                           var curntuser='<?php echo $_SESSION['UserID']; ?>';
                           $.ajax({
                                 dataType:"json",
                                 type:"post",
                                 data: {'curntuser':curntuser},
                                 url:'?action=editfile',
                                 success: function(data2)
                                 {
                                    if(data2)
                                    {       
                                       $('#newlistofclient').html("");
                                       //$('#newlistofclient').append('<option value="0">Select your Client</option>'); 
                                       $.each(data2.resonse, function( index, value ) {
                                          var sel ='';
                                          if(data.resonse.cid == value.id)
                                          {
                                            sel = 'selected="selected"';
                                          }
                                          $('#newlistofclient').append('<option '+ sel +' value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
                                       });
                                         $(".Loader").hide();
                                    }
                                    else if(data2.error)
                                    {
                                       $("#error").show();
                                       $('#errormsg').html('<span>'+data2.error+'</span>');
                                      $(".Loader").hide();
                                    } 
                                 }
                            });

                          $(".Loader").hide();
                          $('#myModal_exit2').modal('toggle');
                        }
                        else if(data.error)
                        {
                          $(".Loader").hide();
                          swal('Something is wrong please try agine');
                        }
                       }
                          });
    });

$('#country').on('change',function(){
         $(".Loader").show();
           CountrysName=$(this).val();

              $.ajax({

                  dataType:"json",
                  type:"post",
                   data: {'CountrysName':CountrysName},
                   url:'?action=editfile',
                   success: function(data)
                   {
                       if(data)
               {

                 $('#administrative_area_level_1').html('');


                   var i =0;
                   $.each(data.resonse, function(k,v)
                   {

               $('#administrative_area_level_1').append('<option value="'+v.name+'">'+ v.name +'</option>');
               });
                            $(".Loader").hide();

               }
               else if(data.error)
               {
                   alert('ok');
               }

                    }
               })

         });




});
    
</script>
<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js//underscore-min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/events.js"></script>

<script src="../assets/node_modules/moment/moment.js"></script>

<script src="../assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="../assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
    <script>
        $(document).ready(function() {
        // $('#EmailInstruction').wysihtml5();
        });
    </script>



<!-- <script type="text/javascript">
   $('#EventDate').bootstrapMaterialDatePicker({ 
        format : 'DD-MM-YYYY HH:mm',
         minDate : new Date() ,
        
     }).on('change', function(e, date)
    {
       $('#end_date').bootstrapMaterialDatePicker('setMinDate', date);

    });
     $('#end_date').bootstrapMaterialDatePicker({ format : 'DD-MM-YYYY HH:mm', minDate : new Date() });       

</script> -->


<script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
 <script>
        $(document).on('keyup','#CostOfService',function(){

               if (/\D/g.test(this.value))
               {
                 this.value = this.value.replace(/\D/g, '');
               }
        });
    </script>
<script>
    jQuery(document).ready(function() {

        // Radio Checked 

        $('input[type=radio][name="Location_radio"]').on('change',function () {
          //alert($("input[name='Location_radio']:checked").val());
          $('#evnet_Location_radio').val($("input[name='Location_radio']:checked").val());
          $('#newlistofclient').trigger('change');
        });

        // End Radio checked

        // Switchery
         $(".listofclientdiv").hide();
         var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
         $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
         });
        // For select 2
         $(".select2").select2();
          $(".select2").select2();
           $(".select2").select2();
       //  $('.selectpicker').selectpicker();
        //Bootstrap-select2
         $(".vertical-spin").select2({
            verticalbuttons: true,
            verticalupclass: 'ti-plus',
            verticaldownclass: 'ti-minus'
         });
         var vspinTrue = $(".vertical-spin").select2({
            verticalbuttons: true
         });
         if (vspinTrue) {
            $('.vertical-spin').prev('.bootstrap-select2-prefix').remove();
         }
         $("input[name='tch1']").select2({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
         });
         $("input[name='tch2']").select2({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
         });
         $("input[name='tch3']").select2();
         $("input[name='tch3_22']").select2({
            initval: 40
         });
         $("input[name='tch5']").select2({
            prefix: "pre",
            postfix: "post"
         });
         // For select2
          $('#pre-selected-options').select2();
         $('#optgroup').select2({
            selectableOptgroup: true
         });
         $('#public-methods').select2();
         $('#select-all').click(function() {
            $('#public-methods').select2('select_all');
            return false;
         });
         $('#deselect-all').click(function() {
            $('#public-methods').select2('deselect_all');
            return false;
         });
         $('#refresh').on('click', function() {
            $('#public-methods').select2('refresh');
            return false;
         });
         $('#add-option').on('click', function() {
            $('#public-methods').select2('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
         });
         $(".ajax").select2({
            ajax: {
               url: "https://api.github.com/search/repositories",
               dataType: 'json',
               delay: 250,
               data: function(params) {
                  return {
                        q: params.term, // search term
                        page: params.page
                  };
               },
               processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                     params.page = params.page || 1;
                     return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                     };
               },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
           // templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
</script>

<script src="<?php echo base_url; ?>/assets/js/dropify.min.js"></script>
<script>
   $(document).ready(function() {
        // Basic
      $('.dropify').dropify();

        // Translated
      $('.dropify-fr').dropify({
         messages: {
               default: 'Glissez-déposez un fichier ici ou cliquez',
               replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
               remove: 'Supprimer',
               error: 'Désolé, le fichier trop volumineux'
         }
      });

      // Used events
      var drEvent = $('#input-file-events').dropify();

      drEvent.on('dropify.beforeClear', function(event, element) {
         return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
      });

      drEvent.on('dropify.afterClear', function(event, element) {
         alert('File deleted');
      });

      drEvent.on('dropify.errors', function(event, element) {
         console.log('Has Errors');
      });

      var drDestroy = $('#input-file-to-destroy').dropify();
      drDestroy = drDestroy.data('dropify')
      $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
      })

   });
</script>
<script type="text/javascript">
   // $('#newlistofcatagory').on('change',function(){
   //    var listofcatagory=$(this).val();  
   //    $(".Loader").show();

   //    var appointmentTitle = $("#newlistofcatagory option:selected").text();
   //    $('#ServiceName').val(listofcatagory); 
   //    $('#title').val(appointmentTitle);

   //    Servicename=$(this).val();

   //    $.ajax({

   //       dataType:"json",
   //       type:"post",
   //        data: {'Servicename':Servicename},
   //        url:'?action=editfile',
   //        success: function(data)
   //        {
   //            if(data)
   //            {
   //              var dur = data.resonse.Duration.split(' ');
   //              $('#duration').val(dur[0]);
   //              $('.minhour').text(dur[1]); 
                
   //            }
   //            else
   //            {
   //              alert('Some Error Occured..refresh your page!');
   //            }
   //            $(".Loader").hide();
   //        }
   //    });

   //  });

   $('#listofcatagory3').on('change',function(){
    $('#myModal_exit2').find('h4').text('Book Appointment with '+ $("#listofcatagory3 option:selected").html());
    $('#newlistofclient').trigger('change');
   });

   $('#newlistofcatagory').on('change',function(){
      //$('#newlistofcatagory').val($(this).val()).trigger('change');
      var listofcatagory=$(this).val();  
      $(".Loader").show();

      var appointmentTitle = $("#newlistofcatagory option:selected").text();
      $('#ServiceName').val(listofcatagory); 
      $('#title').val(appointmentTitle);

                  Servicename=$(this).val();

               $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'Servicename':Servicename},
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                {
                  
                    var dur = data.resonse.Duration.split(' ');
                    $('#duration').val(dur[0]);
                    $('.minhour').text(dur[1]);
                    $('#CostOfService').val(data.resonse.Price); 
                    //$('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                   // $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
                    $('#listofcatagory3').html("");
                    $('#listofcatagory3').append('<option value disabled="true" selected="selected">Select Service Provider</option>'); 

                    var listarray = data.resonse.Users.split(',');

                    var i =0;
                    $.each(listarray, function(k,v) 
                    {
                      
                      $.ajax({
              
              dataType:"json",
                        type:"post",
                        data: {'UserName':v},
                        url:'?action=editfile',
                        success: function(data2)
                        {
                          if(data2)
                          { 
                            if(data2.resonse.id=='')
                            {
                              $(".serviceproviderblock").hide();
                            }
                            else
                            {

                                var myline = 'https://mysunless.com/assets/userimage/20180804105825.jpg';
                              
                                for ( var i = 0; i < data2.resonse.length; i++) {
                                  var obj = data2.resonse[i];
                                  var sel ="";
                                  if(obj.id == <?= $_SESSION['UserID']?>)
                                    sel = 'selected="selected"';

                                  var flag =0;
                                  $('#listofcatagory3 option').each(function(){
                                    if($(this).val() == obj.id){
                                      flag=1;
                                    }
                                  });
                                  if(flag==0){
                                   $('#listofcatagory3').append('<option  value="'+obj.id+'">'+ obj.firstname + ' '+ obj.lastname +'</option>');  
                                  }  

                           }
                              $(".Loader").hide();
                           }
                          }

                        }

                      });
                    
                    
                    });

                }
                else if(data.error)
                {
                    alert('ok');  
                }
                   
                     }
                })


   });

   // $('#listofcatagory3').on('change',function(){
   //    $('#ServiceProvider').val($(this).val());
   //    $('#myModal_exit2').find('h4').text('Book Appointment with '+ $("#listofcatagory3 option:selected").html());
   //    $('#myModal').modal('hide');
   //    $('#myModal_exit2').modal('toggle');
   // });

   $('input[type="radio"]').click(function(){
            if($(this).prop("checked") == true){
            var cjecl=$(this).val();            
            }
            
        });

 // New Client Button Click 
   $('.myModal_new').on('click',function(){

    $('#autocomplete').val('')
    var daf = Math.floor(Math.random() * 40) + 1;
    $( ".dropify-render img" ).first().remove();
    $('#autocomplete').val('');
    $("#ProfileImg3").val('Layer'+daf+'.png')
    $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");
    $('<img src="<?php echo base_url; ?>/assets/ProfileImages/Layer'+daf+'.png" id="pImage">').appendTo(".dropify-render");
    $('.dropify-filename-inner').text('noimage.png')

    $('#myModal').modal('hide');
    $('#myModal_new2').modal('toggle');

   });
// End NewClient Button click

    $('.back').on('click',function(){ 

        $('#myModal_new2').modal('hide');
         $('#myModal').modal('toggle');
  
   });

   $('#exit-client').on('click',function(){
      // $(".Loader").show();
      curntuser='<?php echo $_SESSION['UserID']; ?>';
      $.ajax({
          dataType:"json",
          type:"post",
          data: {'curntuser':curntuser},
          url:'?action=editfile',
          success: function(data)
          {
              if(data)
              {       
                
                $('#newlistofclient').html("");
                $('#newlistofclient').append('<option value disabled="true" selected="selected">Select your Client</option>'); 
                $.each(data.resonse, function( index, value ) {
                  // $('#listofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
                  $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
                });
                $(".Loader").hide();
                $('#myModal').modal('hide');
                $('#myModal_exit2').modal('toggle');
              }
              else if(data.error)
              {
                $("#error").show();
                $('#errormsg').html('<span>'+data.error+'</span>');
                $(".Loader").hide();
              } 
          }
      })
   });


$('#listofclient').on('change',function(){
     $('#myModal_exit2').find('h4').text('Book Appointment with '+ $("#listofcatagory3 option:selected").html());
     //$('#servicewith').text('Service with '+ $("#listofcatagory3 option:selected").html());
     $('#newlistofclient').val($(this).val()).trigger('change');
       $("#eventstartime").trigger('change');
     $(".Loader").show();   
   ClientsName=$(this).val();

   $('#myModal').modal('hide');
   $('#myModal_exit2').modal('toggle');
   var radioValue = $("input[name='Location_radio']:checked").val();
   $("#evnet_Location_radio").val(radioValue);
   if(radioValue=='Salon Location')
   {
    $(".ServiceLocation").hide();
   }

   $.ajax({

      dataType:"json",
      type:"post",
      data: {'ClientsName':ClientsName},
      url:'?action=editfile',
      success: function(data)
      {
         if(data)
          
         {    $(".Loader").hide();   
            $(".hidddeforfirst").show();
            $(".exit-client-image").show();
            $('#FirstName').val(data.resonse.FirstName);
            $('#LastName').val(data.resonse.LastName);
            $('#Phone').val(data.resonse.Phone);
            $('#Email').val(data.resonse.email);
            $('#clinetdetails').show();
            $('#newname').html('<b>Name :</b>' + data.resonse.FirstName + ' ' + data.resonse.LastName);
            $('#newphone').html('<b>Cell : </b>' + data.resonse.Phone);
            $('#newemail').text(data.resonse.email);
      if(radioValue=='Customer Location')
   {   

        var useradd = $('#listofcatagory3').val(); 
     if(useradd==0)
     {
      var useradd= '<?php echo $_SESSION['UserID']; ?>';
     }
     
      $("#ServiceProvider").val(useradd);
      $('#Address').val(data.resonse.Address);
      $('#Zip').val(data.resonse.Zip);
      $('#City').val(data.resonse.City);
      $('#State').val(data.resonse.State);
      $('#newcountry').val(data.resonse.Country);
      
    }
    else
    {
     var useradd = $('#listofcatagory3').val(); 
     if(useradd==0)
     {
      var useradd= '<?php echo $_SESSION['UserID']; ?>';
     }
     
          $.ajax({
              
              dataType:"json",
                        type:"post",
                        data: {'useradd':useradd},
                        url:'?action=editfile',
                        success: function(data3)
                        {
                          if(data3)
                          { 

                            $("#ServiceProvider").val(useradd);
                            $('#Address').val(data3.resonse.primaryaddress);
                            $('#Zip').val(data3.resonse.zipcode);
                            $('#City').val(data3.resonse.city);
                            $('#State').val(data3.resonse.state);
                            $('#newcountry').val(data3.resonse.country);
                           
                             $(".Loader").hide();
                           
                          }

                        }

                      });
     
    }

       
            $('#cid').val(data.resonse.id);
            $('#editcustomer').val(btoa(data.resonse.id));
            if(data.resonse.ProfileImg!='')
            {
               $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
            }
            else if(data.resonse.ProfileImg=='')
            {
               $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
            }
         }
         else if(data.error)
         {
            swal("Oops...", "Something went wrong!", "error");
         }
                   
      }
   })

});



$('#newlistofclient').on('change',function(){

  
     
     $('#clinetdetails').hide();
   var ClientsName=$(this).val();
   if(ClientsName == null){
    return;
   }
   $(".Loader").show();   

   if($('#NewEvent input[name="id"]').val() == 'new'){
      var radioValue = $("input[name='Location_radio']:checked").val();
   }else{
    var radioValue = $('#evnet_Location_radio').val();
    //alert(radioValue);
   }

   $.ajax({

      dataType:"json",
      type:"post",
      data: {'ClientsName':ClientsName},
      url:'?action=editfile',
      success: function(data)
      {
         if(data)
          
         {    
            $(".Loader").hide();   
            $('#FirstName').val(data.resonse.FirstName);
            $('#LastName').val(data.resonse.LastName);
            $('#Phone').val(data.resonse.Phone);
            $('#Email').val(data.resonse.email);
            $('#clinetdetails').show();
            $('#newname').html('<b>Name :</b>' + data.resonse.FirstName + ' ' + data.resonse.LastName);
            $('#newphone').html('<b>Cell : </b>' + data.resonse.Phone);
            $('#newemail').text(data.resonse.email);
          
            if(radioValue=='Customer Location')
            {   
              var useradd = $('#listofcatagory3').val(); 
              if(useradd==0)
              {
               var useradd= '<?php echo $_SESSION['UserID']; ?>';
              }
     
              $('#Address').val(data.resonse.Address);
              $('#Zip').val(data.resonse.Zip);
              $('#City').val(data.resonse.City);
              $('#State').val(data.resonse.State);
              $('#newcountry').val(data.resonse.Country);
         }
         else
         {
             if($('#NewEvent input[name="id"]').val() == 'new'){
              var useradd = $('#listofcatagory3').val(); 
              if(useradd==0)
              {
               var useradd= '<?php echo $_SESSION['UserID']; ?>';
              }
       
               $.ajax({
                      dataType:"json",
                      type:"post",
                      data: {'useradd':useradd},
                      url:'?action=editfile',
                      success: function(data3)
                      {
                        if(data3)
                        { 
                          $("#ServiceProvider").val(useradd);
                          $('#Address').val(data3.resonse.primaryaddress);
                          $('#Zip').val(data3.resonse.zipcode);
                          $('#City').val(data3.resonse.city);
                          $('#State').val(data3.resonse.state);
                          $('#newcountry').val(data3.resonse.country);
                          $(".Loader").hide();
                        }
                      }
              });
            }
          }
       
          $('#cid').val(data.resonse.id);
           $('#editcustomer').val(btoa(data.resonse.id));
          if(data.resonse.ProfileImg!='')
          {
             $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
          }
          else if(data.resonse.ProfileImg=='')
          {
             $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
          }
         }
         else if(data.error)
         {
            swal("Oops...", "Something went wrong!", "error");
         }
                   
      }
   })

});



</script>
<script type="text/javascript">
   $(document).ready(function(){

    $('#update-client').hide();

$(document).on("change","#FirstName,#LastName,#Phone,#Email",function() {
    
$('#update-client').show();      
        });

$(document).on("click","#cancelappp",function() {
    
$('#update-client').hide();      
        });


// $(document).on("change","",function() {
    
// $('#update-client').show();      
//         });

// $(document).on("change","",function() {
    
// $('#update-client').show();      
//         });

// $(document).on("change","",function() {
    
// $('#update-client').show();      
//         });

      // formatting phone number
      $('#Phone').keyup(function(e){
         var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
      });
      // end formatting phone number

        $("#NewEvent").validate({

            ignore: ":hidden:not(textarea)",
            rules: {                
                //title: {required: true,},
                //FirstName: {required: true,},
                //LastName: {required: true,},
                //Phone: {required: true,},
                newlistofcatagory: {required:true,},
                listofcatagory3: {required:true,},
                newlistofclient: {required:true,},
                sd: {required: true,},
                st: {required: true,},
                ed: {required: true,},
                et: {required: true,},
                eventstatus: {required:true,},
                dendate:{required:true,},
                wendate:{required:true,},
                mendate:{required:true,},
                yendate:{required:true,},
                mday:{required:true,},
                ydate:{required:true,},
                //Address: {required: true,},
                //Zip: {required: true,},
                //City: {required: true,},
                CostOfService: {required: true,number: true},
                EmailInstruction: {required: true,},
                
                //Email: {required: true,},
                //State: {required: true,},
                //country:"required",//
            },
            

            messages: {             
                //FirstName: {required: "Please enter first name"},
                //LastName: {required: "Please enter last name"},
                //Phone: {required: "Please enter phone number"},
                newlistofcatagory: {required:"Please select Service"},
                listofcatagory3: {required:"Please select Service Provider"},
                newlistofclient: {required:"Please select Customer"},
                sd: {required: "Select start date &nbsp"},
                ed: {required: "Select end date &nbsp"},
                st: {required: "Select start time &nbsp"},
                et: {required: "Select end time &nbsp"},
                eventstatus: {required: "Please select Appointment status"},
                dendate:{required: "Please select End Date"},
                wendate:{required: "Please select End Date"},
                mendate:{required: "Please select End Date"},
                yendate:{required: "Please select End Date"},
                mday:{required: "Please select Date"},
                ydate:{required: "Please select Date"},
                //Address: {required: "Please enter address"},
                //Zip: {required: "Please enter zip"},
                //City: {required: "Please enter city"},
                //State: {required: "Please enter state"},
                //country:"Please select country",
                CostOfService: {required: "Please enter cost of service",
                                number: "Please enter valid price",
                },
                EmailInstruction: {required: "Please enter Appointment Note"},
                //Email: {required: "Please enter email"},
                //title: {required: "Please enter appointment title"},
                //password:  "Please enter your password"
            },

              errorPlacement: function( label, element ) {
                    if( element.attr( "name" ) === "EmailInstruction" ) {
                        element.parent().append( label );
                    } else if ( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed" || element.attr( "name" ) === "st" || element.attr( "name" ) === "et" ) {
                         element.parent().parent().append( label );
                    }else {
                         label.insertAfter( element );
                    }
                },

                submitHandler: function() {
                  $(".Loader").show();
                  if($('#weekly').hasClass('active')){
                  var flag = 0;
                    $('.wdays').each(function(){
                      if($(this).hasClass('wactive')){
                         flag = 1;
                      }
                    });
                    if(flag==0){
                      $(".Loader").hide();
                      $("#csrf_error2").show();
                      $('#csrf_errormsg2').html('<span>Please Select Days!</span>');
                      setTimeout(function(){  $("#csrf_error2").hide(); },5000);
                      return;
                    }
                  }

                  if($('#weekly').hasClass('active') || $('#monthly').hasClass('active') || $('#yearly').hasClass('active')){
                      if($('#wdateshidden').val() == ''){
                      $(".Loader").hide();
                      $("#csrf_error2").show();
                      $('#csrf_errormsg2').html('<span>Couldn\'t assign any dates between Start date and End date related to your criteria!</span>');
                      setTimeout(function(){  $("#csrf_error2").hide(); },5000);
                      return;
                    }
                  }
                    
                
                    // alert(listofcatagory);
                      
                    var data = $("#NewEvent").serialize();
                    data= data + "&LoginAction=Login";
                    //alert( $("#NewEvent").serialize());
                    jQuery.ajax({

                        dataType:"json",
                        type:"post",
                        data:data,
                        url:'<?php echo EXEC; ?>Exec_Edit_Event.php',
                        success: function(data)
                        {
                            if(data.resonse)
                            {

                                $("#resonseAddApp").show();

                                $('#resonseAddAppemsg').html('<span>'+data.resonse+'</span>');  
                                $( '#NewEvent' ).each(function(){
                                   this.reset();
                                });
                                $(".Loader").hide();
                                var enewid = $("#id").val();
                                var googlesync = '<?php echo $googlesync; ?>';
                                if(enewid == "new" && googlesync==1)
                                {
                                window.location.href = "googlcel/home.php";
                                }
                              else
                              {
                                  setTimeout(function () { window.location.href = "AllEvent.php"; }, 2000);
                              }

                                
                            }
                            else if(data.error)
                            {
                                $("#error").show();
                                
                                  $('#errormsg').html('<span>'+data.error+'</span>');
                                
                                $(".Loader").hide();
                            // alert('<li>'+data.error+'</li>');
                            }
                            else if(data.resonse_phone)
                            {
                                swal('Appointment add but messages is not send because '+data.resonse_phone)
                                setTimeout(function () { window.location.reload() }, 2000);
                            }

                               else if(data.csrf_error)
                {
                  
                    $("#csrf_error2").show();
                    $('#csrf_errormsg2').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    setTimeout(function () { window.location.reload() }, 2000)
                }
                     
                              
                      }
              
                    });
                }           
        });

 });
</script>

<script>
     $(document).ready(function(){

        // formatting phone number
      $('#phonenumber').keyup(function(e){
        var ph = this.value.replace(/\D/g,'').substring(0,10);
          // Backspace and Delete keys
          var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
          var len = ph.length;
          if(len==0){
              ph=ph;
          }else if(len<3){
              ph='('+ph;
          }else if(len==3){
              ph = '('+ph + (deleteKey ? '' : ') ');
          }else if(len<6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
          }else if(len==6){
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
          }else{
              ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
          }
          this.value = ph;
    });
      // end formatting phone number

       // js for not shown due date if package not selected
      $('#datetimepicker').hide();
       var editpackage= "<?php echo @$SelectPackage ; ?>" ;
       if(editpackage !== 'NoPackage'){
         $('#datetimepicker').show();
      }  
      $('#SelectPackage').change(function(){
         var SelectPackage = this.value ;
          if(SelectPackage == '' || SelectPackage == 'NoPackage'){
            $('#datetimepicker').hide();
          }
          else{
            $('#datetimepicker').show();
          }
       });
      // end js 

      $(document).on('click','#editcustomer',function(){
      $('.dropify-render').text('')
      $('#clid').val('');
    $('.dropify-filename-inner').text('')
        $(".Loader").show();
       event.preventDefault();
     var customersid = $(this).val();
     var customersid2 = atob(customersid)

     $.ajax({
              dataType:"json",
              type:"post",
              data: {'customersid2':customersid2},
              url:'?action=editfile',
              success: function(data)
              {
                if(data.resonse)
                { 
                 
                   $('#FirstNameC').val(data.resonse.FirstName)
                   $('#id').val(data.resonse.id)
                   $('#LastNameC').val(data.resonse.LastName)
                   $('#phonenumber').val(data.resonse.Phone)
                   $('#example-email').val(data.resonse.email)
                   $('#autocomplete').val(data.resonse.Address)
                   $('#street_number').val(data.resonse.Address)
                   $('#postal_code').val(data.resonse.Zip)
                   $('#country').val(data.resonse.Country) 
                   $('#administrative_area_level_1').val(data.resonse.State)
                   $('#locality').val(data.resonse.City)
                   $('#oldimage').val(data.resonse.ProfileImg)
                    if(data.resonse.ProfileImg !== '')
                      {
                           
                          $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
                           $('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
                           $('.dropify-filename-inner').text(data.resonse.ProfileImg)
                        
                      }
                      else if(data.resonse.ProfileImg =='')
                      {
                           $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/images/noimage.png");
                           $('<img src="<?php echo base_url; ?>/assets/images/noimage.png" id="pImage">').appendTo(".dropify-render");
                           $('.dropify-filename-inner').text('noimage.png')
                      }

                   $("#myModal_new2").modal('show')
                    $(".Loader").hide();
                }
                else if(data.resonse==false)
                {
                $(".Loader").hide();
                  swal('No data found')
                 
                }
               }
                })

      });
     
        $("#NewClient").validate({
            rules: {                
                FirstName: "required",
                LastName: "required",
                Phone: {required: true, },
                email: "required",
                Address: "required",
                Zip: "required",
                City: "required",
                State: "required",
                Country:"required",
                // Solution: "required",
                // PrivateNotes: "required",
                // SelectPackage: "required",
               //employeeSold: "required",
               // sd: {required: true,},
               // ed: {required: true,},

                
            },
            messages: {             
                FirstName:  "Please enter first name",
                LastName:  "Please enter last name",
                Phone: {required : "Please enter phone number",} ,
                email:  "Please enter an email",
                Address:  "Please enter address",
                Zip:  "Please enter zipcode",
                City:  "Please enter city",
                State:  "Please enter state",
                // Solution:"Please Enter Solution Strength",
                // PrivateNotes:"Please Enter Private Notes",
                // Country:"Please Select Country",
                // SelectPackage :"Please Select Package",
               //employeeSold: "Please Select Employee Who Sold",
               // sd:"Please Select Starting Date &nbsp&nbsp&nbsp&nbsp",
               // ed:"&nbsp&nbsp&nbsp&nbsp Please Select Ending Date",

            },submitHandler: function() {
                $(".Loader").show();
            var form = $('#NewClient')[0];
            var data = new FormData(form);
            //var data = $("#NewClient").serialize();
               jQuery.ajax({

                   dataType:"json",
                   type:"post",
                    data:data,
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false,
                    url:'<?php echo EXEC; ?>Exec_Edit_Client.php',
                    success: function(data)
                {
                        if(data.resonse)
                {

                    $("#resonse").show();
                    
                      $('#resonsemsg').html('<span>'+data.resonse+'</span>');
                    
                    $( '#NewClient' ).each(function(){
                         this.reset();
                         });

                        var radioValue = $("input[name='Location_radio']:checked").val();
                        $("#evnet_Location_radio").val(radioValue)
                        if(radioValue=='Salon Location')
                        {
                        $(".ServiceLocation").hide();
                        }

                         if($('#id').val() == 'new'){
                          $('#listofclient').append('<option value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
                          $('#listofclient').select2(); 
                          $('#newlistofclient').append('<option selected="selected" value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
                         }

                      $('#FirstName').val(data.mydata.FirstName);
                      $('#LastName').val(data.mydata.LastName);
                      $('#newlistofclient option:selected').text(data.mydata.FirstName + ' ' + data.mydata.LastName).select2().trigger('change'); // new
                      $('#newlistofclient').select2(); 
                      $('#Phone').val(data.mydata.Phone);
                      $('#Email').val(data.mydata.email);
                      $('#newemail').text(data.mydata.email);
                      $('#newname').html('<b>Name :</b>' + data.mydata.FirstName + ' ' + data.mydata.LastName);
                      $('#newphone').html('<b>Cell : </b>' + data.mydata.Phone);
                      $('#cid').val(data.mydata.id);

                        if(data.mydata.ProfileImg!='')
                      {
                       $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.mydata.ProfileImg);
                    }
                    else if(data.mydata.ProfileImg=='')
                    {
                    $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
                    }

                         if(radioValue=='Customer Location')
                         {     
                        var useradd = $('#listofcatagory3').val(); 
                       if(useradd==0)
                       {
                        var useradd= '<?php echo $_SESSION['UserID']; ?>';
                       }
                        $("#ServiceProvider").val(useradd);
                        $('#Address').val(data.mydata.Address);
                        $('#Zip').val(data.mydata.Zip);
                        $('#City').val(data.mydata.City);
                        $('#State').val(data.mydata.State);
                        // $('#country').val(data.resonse.Country).attr("selected", "selected");
                        $('#newcountry').val(data.resonse.Country);
                  }
                      else
                        {
                          console.log("ds")
                         var useradd = $('#listofcatagory3').val(); 
                         if(useradd==0)
                         {
                          var useradd= '<?php echo $_SESSION['UserID']; ?>';
                         }
                              $.ajax({
                                  
                                  dataType:"json",
                                            type:"post",
                                            data: {'useradd':useradd},
                                            url:'?action=editfile',
                                            success: function(data3)
                                            {
                                              if(data3)
                                              { 
                                                console.log(data3)
                                                $("#ServiceProvider").val(useradd);
                                                $('#Address').val(data3.resonse.primaryaddress);
                                                $('#Zip').val(data3.resonse.zipcode);
                                                $('#City').val(data3.resonse.city);
                                                $('#State').val(data3.resonse.state);
                                                // alert(data3.resonse.country);
                                               // $('#country').val(data3.resonse.Country).attr("selected", "selected");
                                                $('#newcountry').val(data3.resonse.country);
                                               
                                                 $(".Loader").hide();
                                               
                                              }

                                            }

                                          });
                                  }

                    $(".hidddeforfirst").show();
                    $(".exit-client-image").show();
                      
                        $(".Loader").hide();
                         $('#myModal_new2').modal('toggle');  

                        if($('#id').val() == 'new'){
                          $('#exit-client').trigger('click');
                          $('#listofclient').val(data.mydata.id).trigger('change');
                          $('#listofclient').select2(); 
                        }else{

                        }

                    // location.reload();
                }
                else if(data.error)
                {
                    $("#error").show();
                    
                      $('#errormsg').html('<span>'+data.error+'</span>');
                    
                    $(".Loader").hide();
                    $('#myModal').modal('toggle');
                // alert('<li>'+data.error+'</li>');
                }

                   else if(data.csrf_error)
                {
                  
                    $("#csrf_error").show();
                    $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
                    $(".Loader").hide();
                    setTimeout(function () { window.location.reload() }, 2000)
                }
               
                     }
                    });
      
                } 

        });

    $('#update-client').on('click',function(){
       
      var cid=$('#listofclient').val();
      var FirstName=$('#FirstName').val();
      var LastName=$('#LastName').val();
      var Phone=$('#Phone').val();
      var Email=$('#Email').val();
      var Address=$('#Address').val();
      var Zip =$('#Zip').val();
      var City=$('#City').val();
      var State=$('#State').val();
      var country=$('#country').val();
      // $('#NewEvent').submit();
        $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'cid':cid,'FirstName':FirstName,'LastName':LastName,'Phone':Phone,'Email':Email,'Address':Address,'Zip':Zip,'City':City,'State':State,'country':country},
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                {
                    $('#NewEvent').submit();
          
                }
                else if(data.error)
                {
                    alert('ok');  
                }
                   
                     }
                })

       // $('#NewEvent').submit();
   
});

    $("#sd").change(function(){
            var package_sd = $(this).val(); 
            var selected_stat_Package  = $("#SelectPackage").val();
            if(selected_stat_Package=='')
   {    
       $('#listofcatagory_error2').text("Please Select Pakcage");
       $("#sd").val(''); 
   }
   else{
            $(".Loader").show();
                 $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'selected_stat_Package':selected_stat_Package,'package_sd':package_sd},
                    url:'?action=selected_stat_Package',
                    success: function(data)
                    {
                        if(data)
                {
                      $(".Loader").hide();
                  $("#ed").val(data.resonse);
                }
                else if(data.error)
                {
                    alert('ok');  
                }
                   
                     }
                })
         }   
         });

 });
</script>
<script type="text/javascript">
  function myFunction() {
    document.getElementById("myDropdown_cal").classList.toggle("show_cal");
}

function myFunction2() {
    document.getElementById("myDropdown_cal2").classList.toggle("show_cal");
}

// Close the dropdown menu if the user clicks outside of it.show
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn_cal')) {

    var dropdowns = document.getElementsByClassName("dropdown-content_cal");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

<script type="text/javascript">
    
$(document).ready(function() { 


        $(document).on('click','.ClientLimit',function(){
        
          swal("Your Client Limit is over!!", "For Upgrade your limit contact to Admin", "warning");
       });

    });
</script>

   
     <script>
   $('#datepairExample .time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia'
    });

    $('#datepairExample .date').datepicker({
        'format': 'yyyy-m-d',
        'autoclose': true
    });

    // initialize datepair
    $('#datepairExample').datepair();
</script>

<script type="text/javascript">
  $(document).ready(function(){

    $( "#autocomplete" ).focus(function() {
  $("input").attr("autocomplete","nope");
});
    
   $(document).on('click', '.cal-day-inmonth', function(e){
    e.preventDefault();
      
      if($(event.target).attr('class') =="NotAvidateUser")
      {
        return false;
      }

      if($(event.target).attr('class') =="evnetlistdid")
      {
        return false;
      }
      
    var today_date = $(this).find('span').data('cal-date');
    // console.log(test);      
    
     $(".today_date").text(today_date);
     $(".tohsedata").attr('data-current-cal-date',today_date);

         $('#eventstardate').val(today_date); 
         $('#eventenddate').val(today_date);

        $(this).trigger('dblclick');  
      
  });  
   $(document).on('click', '#addnewappointment', function(e){
       $('#editspan').hide();
      $('#repeatdiv').show();
   });
   
   $(document).on('click', '.cal-day-hour-part', function(e){
    $('#editspan').hide();
     $('#repeatdiv').show();
      var today_time = $(this).find('.span1').find('b').text();
      $('#eventstartime').val(today_time);
      $('#NewEvent input[name="id"]').val('new');
      $('#CostOfService').val('');
      $('#EmailInstruction').val('');
      $('#eventstatus option:nth-child(1)').attr('selected','selected');
      $('#myModal').modal('show');
   });

    $(document).on('click', '.tohsedata', function(){
        var current_pop_date = $(this).data('current-cal-date');
        $("[data-cal-date="+current_pop_date+"]").click();
       $('#calmyModal').modal('hide');
    });

    $(document).on('click', '.addevet_tohsedata', function(){

      $('#calmyModal').modal('hide');
    
    $('#myModal').modal('show');

    // $('#sd').val(today_date); 

});

     $('#offrepeat').click(function() {
       $("#eventstardate").trigger('change');
    });

    $('#daily').click(function() {
      $("#eventstardate").trigger('change');
    });

    $('#weekly').click(function() {
      $("#eventstardate").trigger('change');
    });

    $('#monthly').click(function() {
      $("#eventstardate").trigger('change');
    });

    $('#yearly').click(function() {
      $("#eventstardate").trigger('change');
    });

    $('#every').on('change',function() {
      emptydata();
    });

    $('#wendate').change(function() {
      var service_star_time = $(this).val(); 
      $("#eventenddate").val(service_star_time);

      // emptying data

      $('.wdays').each(function() {
        $(this).removeClass('wactive');
      });
      $('#wdayshidden').val('');
      $('#wdateshidden').val('');
    });

    $('#mendate').on('change',function() {
      var service_star_time = $(this).val(); 
      $("#eventenddate").val(service_star_time);
      findmdates();
    });

    $('#mday').on('change',function(){
      findmdates();
    });

    function findmdates(){
      var start = $('#eventstardate').val();
      var end = $('#eventenddate').val();

      var startDate = new Date(start);
      var endDate = new Date(end);
      var mday = $('#mday').val();
      // var totalSundays = 0;
      var str ='';
      for (var i = startDate; i <= endDate; ){
            if(parseInt(i.getDate()) == parseInt(mday)){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              str = str+','+year + '-' + month + '-' + day;
             } 
          i.setTime(i.getTime() + 1000*60*60*24);
      }
      $('#wdateshidden').val(str);
    }

    $('#ymonth').on('change',function(){
      findydates();
    });

    $('#ydate').on('change',function(){
      findydates();
    });

    $('#yendate').on('change',function(){
       var service_star_time = $(this).val(); 
      $("#eventenddate").val(service_star_time);
      findydates();
    });    

    function findydates(){
      var start = $('#eventstardate').val();
      var end = $('#eventenddate').val();

      var startDate = new Date(start);
      var endDate = new Date(end);
      var ydate = $('#ydate').val();
      var ymonth = $('#ymonth').val();
      // var totalSundays = 0;
      var str ='';
      for (var i = startDate; i <= endDate; ){
            if(parseInt(i.getDate()) == parseInt(ydate) && i.getMonth()+1 == ymonth){
              var day = i.getDate();
              var month = i.getMonth()+1;
              var year = i.getFullYear();
              str = str+','+year + '-' + month + '-' + day;
             } 
          i.setTime(i.getTime() + 1000*60*60*24);
      }
      $('#wdateshidden').val(str);
    }

    $("#dendate").change(function(){
            var service_star_time = $(this).val(); 
         $("#eventenddate").val(service_star_time);

          var start = $('#eventstardate').val();
          var end = $('#eventenddate').val();

          // // end - start returns difference in milliseconds 
          // var startdt = new Date(start);
          // var enddt = new Date(end);
          // var millisecondsPerDay = 1000 * 60 * 60 * 24;

          // var millisBetween = enddt.getTime() - startdt.getTime();
          // var days = millisBetween / millisecondsPerDay;

          var startDate = new Date(start);
          var endDate = new Date(end);
          // var totalSundays = 0;
          var str ='';
          for (var i = startDate; i <= endDate; ){
             
                  var day = i.getDate();
                  var month = i.getMonth()+1;
                  var year = i.getFullYear();
                  str = str+','+year + '-' + month + '-' + day;
                  

              i.setTime(i.getTime() + 1000*60*60*24);
              
          }
          $('#wdateshidden').val(str);

          //alert(totalSundays);
         });

    function emptydata(){
      $('#dendate').val('');
      $('#wendate').val('');
      $('#mendate').val('');
      $('#yendate').val('');
      $('.wdays').each(function() {
        $(this).removeClass('wactive');
      });
      $('#wdayshidden').val('');
      $('#wdateshidden').val('');

    }

     $("#eventstardate").change(function(){
            var service_star_time = $(this).val(); 
         $("#eventenddate").val(service_star_time);
         $("#eventstartime").trigger('change'); 
          emptydata();       
         });

 $("#eventstartime").change(function(){
            var service_star_time = $(this).val(); 
              var serivename=  $("#ServiceName").val();
              
            //$(".Loader").show();
                 $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'service_star_time':service_star_time,'serivename':serivename},
                    url:'?action=service_star_time',
                    success: function(data)
                    {
                        if(data)
                {
                 
                      //$(".Loader").hide();
                      //location.reload();
                  $("#eventendtime").val(data.resonse);
                }
                else if(data.error)
                {
                    alert('ok');  
                }
                   
                     }
                })
           
         });

$('#uploadimageModalasdfdsf').on('click',function(){
    $("#uploadimageModal").modal('hide');
    });

 $("#byuser").click(function(){
  $("#caluserlist").toggle()
  });  

$("#byservice").click(function(){
  $("#calserlist").toggle()
  });  

$('#NotAvailableEmp').on('change',function(){
      var Nuid=$(this).val(); 
      var bdate= $(".today_date").text();

            $(".Loader").show();
                 $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'Nuid':Nuid,'bdate':bdate},
                    url:'?action=Nuid',
                    success: function(data)
                    {
                        if(data)
                {
                  console.log(data)
                      $(".Loader").hide();
                      location.reload();
                  $("#eventendtime").val(data.resonse);
                }
                else if(data.error)
                {
                    alert('ok');  
                }
                   
                     }
                })


  });  


    ShowModal();
  
    $(document).on('click','.notave',function(){

      ShowModal();

     });

    $('.notave').on('change',function(){  
        ShowModal();
    });



     });  


function ShowModal(){
        
        var datearrayFromPHP = <?php echo json_encode($resultFindnotavi); ?>;
        
             $.each(datearrayFromPHP, function (k,v) {    
                    $('.cal-month-day').each(function() {
                  var NotAvidate = $(this).attr("data-id");

                    if (NotAvidate==v.OnDate) 
                    
                {   
                        
                    $(this).append("<span class='NotAvidateUser' data-namenotavi='"+v.username+"' data-notaviid='"+v.notaviid+"'> <i class='fa fa-window-close' aria-hidden='true' style='color:red;'></i>"+' | '+" "+v.username+""+' '+""+' Not Available '+"</span><br>");
                     //$(this).removeClass('cal-day-inmonth').addClass( "cal-day-outmonth" ).addClass( "bloackday" );
                }


                        }); 
                    
                }); 
 }

$(document).on('click','.evnetlistdid',function(e){
   e.preventDefault();
       $("#eventdetailmodel").modal('show');
       var customername = $(this).attr("data-clientname");
       $("#evnetcutomername").text(customername);
       
       var UserName = $(this).attr("data-username");
       $("#evnetemployename").text(UserName);
       
       var envettitle = $(this).attr("data-title");
       $("#envetsername").text(envettitle);
       
       var evnetstarttime = $(this).attr("data-startime");
       $("#evnetstartime").text(evnetstarttime);
       
       var evneturl = $(this).attr("data-eventurl");
       var evtd = evneturl.split('&');
       var evtid = evtd[1].split('=');
    $("#ViewEvent").attr("data-id",evtid[1])
       
       var customerurl = $(this).attr("data-viewclient");
       $("#ViewCustomer").attr("href",customerurl)

       var deleteevent = $(this).attr("data-deletevent");
       $("#DeleteEvent").attr("href",deleteevent)
        
        var eventadders = $(this).attr("data-eventadders");
        $("#eventaddermap").attr("src",eventadders)


        var onlyeventadders = $(this).attr("data-onlyeventadders");
        $("#onlyeventadders").text(onlyeventadders)
        

        var elocation = $(this).attr("data-location");

        if(elocation == 'Salon Location')
        {
          $(".cusomerlocation").hide();
        }
   
        
});

$(document).on('click','.cusomerlocation',function(e){
   e.preventDefault();
   $("#eventdetailmodel").modal('hide');
   $("#eventmap").modal('show');

});

$(document).on('click','.NotAvidateUser',function(e){

  var deletenotaviuse = $(this).attr("data-notaviid");
  var nameofnotavil = $(this).attr("data-namenotavi");
      
      if(deletenotaviuse!=0)
      {

               swal({
                title: nameofnotavil,
                text: "Is not available for taking clients this day.",
                icon: "warning",
                buttons: ["CLOSE", "DELETE FROM CALENDAR"],
            }).then((isConfirm)=>{ 
                if (isConfirm){ 
                    $.ajax({
                        type:"post",
                        data:{'Notaiv_id':deletenotaviuse},
                        url:'<?php echo base_url ; ?>/All_Script?page=Dashboard',
                        success: function(data) 
                        {
                           if(data.resonse)
                             {
                              location.reload();
                            // swal("","Selected employee available Successfully deleted!", "success");         
                             }
                             location.reload();
                       
                        }
                    });
                    
                }
                  else{
                      return;
                  }
            });
      }

      
     });

       $(document).on('click','body *',function(){
   
      var st = $("#street_number").val();
      var ad = $("#route").val();
      var fulladders = st+' '+ad;
      
      $("#autocomplete").val(fulladders);
});

</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GoogleApiKey; ?>&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
      // This example displays an address form, using the autocomplete feature
      // of the Google Places API to help users fill in the information.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var placeSearch, autocomplete;
      var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'long_name',
        postal_code: 'short_name'
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>

    <script src="<?php echo base_url; ?>/upload-and-crop-image/croppie.js"></script>
    <script>  
$(document).ready(function(){

  $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'circle' //circle or square
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#ProfileImg').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });

  $('.crop_image').click(function(event){
    $('.dropify-render').text('')
    $('.dropify-filename-inner').text('')

      $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url : "<?php echo base_url; ?>/upload-and-crop-image/upload.php",
        type: "POST",
        data:{"image": response},
        dataType:"json",
        success:function(data)
        {
          
          // $('#uploaded_image').html(data);
               $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/"+data.resonse+"");
          $('<img src="<?php echo base_url; ?>/upload-and-crop-image/CustomerTep/'+data.resonse+'" id="pImage">').appendTo(".dropify-render");
          //$( ".dropify-render img" ).first().css( "display", "none" );
          $('.dropify-filename-inner').text(data.resonse)
          $("#ProfileImg2").val(data.resonse)

      $('#uploadimageModal').modal('hide');
        }
      });
    })
  });


  $(function(){
  $('.dropdown-menu input[type="radio"]').click(function(){
    if ($(this).is(':checked'))
    {
      var myseletimage = $(this).val()
      var myseletimageurl = '<?php echo base_url; ?>/assets/ProfileImages/'+myseletimage
      
      if(myseletimageurl!='')
      {
      $image_crop.croppie('bind', {
        url: myseletimageurl
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    $('#uploadimageModal').modal('show');

    }
  });
});

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust()
           .responsive.recalc();
    });    

});  
</script>
    
</body>
</html>