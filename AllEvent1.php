<?php

require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB.'/function.php');


if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

if ($_SESSION['usertype']=="Admin")
{
  header("Location: ../index.php");die;    
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


  // $stmt2=$db3->prepare("SELECT Service.* FROM `Service` WHERE Service.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id ) or FIND_IN_SET($id,Service.Users)"); 



  // $stmt2=$db3->prepare("SELECT sv.* from `Service` AS sv JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) 
  // WHERE (users.id=:id or users.adminid=:id or users.sid=:id)  GROUP BY sv.id"); 



  $stmt2=$db3->prepare("SELECT sv.* from `Service` AS sv 
    JOIN users ON (sv.createdfk=users.id OR sv.createdfk=users.adminid OR sv.createdfk=users.sid) 
    WHERE sv.isactive=1 and sv.createdfk IN (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) GROUP BY sv.id"); 




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
  header("Location: ../index.php");die;  
}

// if(isset($_POST['curntuser']))
// {
//  $db=new db();
//  $id = $_POST['curntuser'];
//  $eidtClient = $db->prepare("Select clients.* from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
//  $eidtClient->bindValue(":id",$id,PDO::PARAM_INT);
//  $editfile=$eidtClient->execute();
//  $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);


//  if($editfile)
//  {
//    echo  json_encode(["resonse"=>$all]);die;

//  }
//  else
//  {
//    echo  json_encode(["error"=>"Sorry You No Client"]);die;              
//  }

// }

   /* if(isset($_REQUEST['ClientsName']))
    {
        $db=new db();
       $ClientsName=$_POST['ClientsName']; 
      $eidtClient = $db->prepare("SELECT * from `clients` where id=:ClientsName");
      $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

      $stmt_note= $db->prepare("SELECT * FROM `noteandclient` LEFT JOIN note ON noteandclient.noteid=note.id WHERE noteandclient.clientid=:ClientsName AND noteandclient.active='1' ORDER BY `note`.`datecreated` DESC limit 0,5"); 
      $stmt_note->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $stmt_note->execute();
      $result_note = $stmt_note->fetchAll();  

      if($editfile)
      {
          echo  json_encode(["resonse"=>$all,"resonse_note"=>$result_note]);die;

      }
  }*/

  // if(isset($_REQUEST['ClientsName']))
  // {
  //  $db=new db();
  //  $ClientsName=$_POST['ClientsName']; 
  //     // $eidtClient = $db->prepare("SELECT * from `clients` where id=:ClientsName");

  //  $eidtClient = $db->prepare("select clients.id as cid,clients.Address,clients.Phone,clients.FirstName,clients.LastName,clients.email,clients.Zip,clients.City,clients.Country,clients.State,clients.ProfileImg ,OrderMembership.*,MemberPackage.Name,OrderMembership.id as cpackagid from `OrderMembership` 
  //    JOIN MemberPackage ON OrderMembership.MembershipId=MemberPackage.id
  //    RIGHT JOIN clients ON OrderMembership.Cid=clients.id WHERE clients.id=:ClientsName");
  //  $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
  //  $editfile=$eidtClient->execute();
  //  $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);
  //  $stmt_note= $db->prepare("SELECT * FROM `noteandclient` LEFT JOIN note ON noteandclient.noteid=note.id WHERE noteandclient.clientid=:ClientsName AND noteandclient.active='1' ORDER BY `note`.`datecreated` DESC limit 0,5"); 
  //  $stmt_note->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
  //  $stmt_note->execute();
  //  $result_note = $stmt_note->fetchAll();
  //  if($editfile)
  //  {
  //    echo  json_encode(["resonse"=>$all,"resonse_note"=>$result_note]);die;
  //  }
  // }
  if(isset($_REQUEST['pakcagidc2']))
  {
    $pakcagidc2_id = $_REQUEST['pakcagidc2'];
    $eidtmembership2_id = $db->prepare("select MembershipId,Noofvisit from `OrderMembership` WHERE id=:pakcagidc2_id");
    $eidtmembership2_id->bindValue(":pakcagidc2_id",$pakcagidc2_id,PDO::PARAM_INT);
    $eidtmembershipfile2_id=$eidtmembership2_id->execute();
    $all_membership54564562_id=$eidtmembership2_id->fetch(PDO::FETCH_ASSOC);

    $pakcagidc2 = $all_membership54564562_id['MembershipId'];
    $eidtmembership2 = $db->prepare("select service from `MemberPackage` WHERE id=:pakcagidc2");
    $eidtmembership2->bindValue(":pakcagidc2",$pakcagidc2,PDO::PARAM_INT);
    $eidtmembershipfile2=$eidtmembership2->execute();
    $all_membership54564562=$eidtmembership2->fetch(PDO::FETCH_ASSOC);
    $listofservice = $all_membership54564562['service'];


    $eidtmembership3 = $db->prepare("select ServiceName,id from `Service` where id IN ($listofservice)");
      // $eidtmembership3->bindValue(":pakcagidc3",$pakcagidc3,PDO::PARAM_INT);
    $eidtmembershipfile3=$eidtmembership3->execute();
    $all_membership54564563=$eidtmembership3->fetchAll(PDO::FETCH_ASSOC);

    if($all_membership54564563)
    {
      echo  json_encode(["resonse"=>$all_membership54564562,"resonse2"=>$all_membership54564563,"resonse3"=>$all_membership54564562_id]);die;
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



  if(isset($_REQUEST['customersid2']))
  {
    $customersid2=$_POST['customersid2']; 
    $eidtClient2 = $db->prepare("select * from `clients` where id=:customersid2");
    $eidtClient2->bindValue(":customersid2",$customersid2,PDO::PARAM_INT);
    $editfile2=$eidtClient2->execute();
    $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
  /*if($editfile2)
  {*/
    echo  json_encode(["resonse"=>$all2]);die;
  //}

  }
 

  // if(isset($_REQUEST['useradd']))
  // {

  //  $UserName=$_POST['useradd']; 
  //  $eidtUserName2 = $db->prepare("select * from `users` where id=:UserName");
  //  $eidtUserName2->bindValue(":UserName",$UserName,PDO::PARAM_STR);
  //  $editfile3=$eidtUserName2->execute();
  //  $allua=$eidtUserName2->fetch(PDO::FETCH_ASSOC);

  //  if($editfile3)
  //  {
  //    $adminid = $allua['adminid'];
  //    if($adminid){ 
  //      $ownerquery = $db->prepare("SELECT * FROM `users` where id=:adminid");
  //      $ownerquery->bindValue(":adminid",$adminid,PDO::PARAM_STR);
  //      $ownerfile = $ownerquery->execute();
  //      $allowner = $ownerquery->fetch(PDO::FETCH_ASSOC);

  //      if($ownerfile)
  //      {
  //        echo  json_encode(["resonse"=>$allowner]);die;
  //      }
  //    }else{
  //      echo  json_encode(["resonse"=>$allua]);die;
  //    }
  //  }
  // }
  
  if(isset($_REQUEST['service_star_time']))
  {


    $service_star_time=$_POST['service_star_time'];
    $serivename=$_POST['serivename']; 


    $eidtClient = $db->prepare("select Duration from `Service` where id=:serivename");
    $eidtClient->bindValue(":serivename",$serivename,PDO::PARAM_STR);
    $editfile=$eidtClient->execute();
    $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
    $Duration=$all['Duration'];

    if($Duration=='0 Min')
    {
      $time = strtotime($service_star_time);
      $time = date("g:ia", strtotime('+0 minutes', $time));
      echo  json_encode(["resonse"=>$time]);die;
    }

    if($Duration=='15 Min')
    {
      $time = strtotime($service_star_time);
      $time = date("g:ia", strtotime('+15 minutes', $time));
      echo  json_encode(["resonse"=>$time]);die;
    }


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
    $eidtClient = $db->prepare("SELECT * FROM `countries` JOIN provinces ON countries.cid=provinces.country_id WHERE countries.countries_name=:CountrysName ORDER BY provinces.name");
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

  $listofeventforca= $db->prepare("SELECT clients.FirstName as cf,clients.LastName as cl, event.title,event.id as id,event.EventDate FROM `event` JOIN clients ON event.cid=clients.id WHERE event.createdfk=:id AND event.EventDate>= NOW() AND event.EventDate<= NOW() + INTERVAL 30 DAY  
    ORDER BY ABS( DATEDIFF( EventDate, NOW() ))");  
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
// if(isset($_POST['elink']))
// {

//  $myevent = base64_decode($_POST['elink']);
//    // $myevent = $_POST['elink'];

//  $EditEvent=$db->prepare("SELECT event.*,clients.ProfileImg FROM `event` LEFT JOIN clients ON event.cid=clients.id WHERE event.id=:myevent");
//  $EditEvent->bindValue(":myevent",$myevent, PDO::PARAM_INT);
//  $EditEvent->execute();
//  $result = $EditEvent->fetch();


//  echo json_encode(['resonse'=>$result]);die;
// }
/*  if(isset($_REQUEST['customersid']))
  {
   $customersid=$_POST['customersid']; 
   $eidtClient2 = $db->prepare("select * from `clients` where id=:customersid");
   $eidtClient2->bindValue(":customersid",$customersid,PDO::PARAM_INT);
   $editfile2=$eidtClient2->execute();
   $all2=$eidtClient2->fetch(PDO::FETCH_ASSOC);
   /*if($editfile)
   {
    echo  json_encode(["resonse"=>$all2]);die;
    echo "string";
  /*}

}*/

if(isset($_POST['serpro'])){
  $stmt = $db->prepare("SELECT * FROM `users` WHERE id=?");
  $stmt->execute([$_POST['serpro']]);
  $result = $stmt->fetch();

  echo json_encode(['resonse'=>$result]);die;
}
$db= new db();

$statement=$db->prepare("SELECT * FROM `countries`  ORDER BY `countries`.`countries_name` ASC ");

$statement->execute();

$countryList = $statement->fetchAll(PDO::FETCH_ASSOC);



$statement2=$db->prepare("SELECT * FROM `provinces`  ORDER BY `provinces`.`name`  ASC  ");

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

$button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C60'"); 
$button2->execute();
$all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
$B2=$all_button2['button_name'];

$button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C61'"); 
$button3->execute();
$all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
$B3=$all_button3['button_name'];

if(isset($_POST['newstat']))
{
  $newstat= $_POST['newstat'];
  $myeventid = $_POST['myeventid'];

  $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
  $stmt->bindparam(":newstat",$newstat);
  $stmt->bindparam(":myeventid",$myeventid);
  $stmt->execute();

  if($stmt)
  {
    echo json_encode(['resonse'=>'Appointment status successfully change']);die;
  }


}

if(isset($_POST['eventidd']))
{
  $myevent = $_POST['eventidd'];
  $EditEventprint=$db->prepare("SELECT event.title,event.CostOfService,event.FirstName,event.LastName,users.firstname as serprofir,users.lastname as serprolast,event.eventstatus,event.Phone,event.Email,event.EmailInstruction,event.EventDate FROM `event` LEFT JOIN users ON event.ServiceProvider=users.id WHERE event.id=:myevent");
  $EditEventprint->bindValue(":myevent",$myevent, PDO::PARAM_INT);
  $EditEventprint->execute();
  $resultprint = $EditEventprint->fetch();
  echo json_encode(['resonse'=>$resultprint]);die;

}

if(isset($_POST['commnetonet']))
{

  $commnetonet = $_POST['commnetonet'];
  $commnettwot = $_POST['commnettwot'];
  $myeventid = $_POST['myeventid'];
  $newstat = $_POST['newstat2'];


  $EditEventprint=$db->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.Email AS Email,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.phonenumber AS  phonenumber,users.email AS  mymail,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
    FROM `event` 
    JOIN users ON event.ServiceProvider=users.id
    JOIN clients ON event.cid=clients.id
    WHERE event.id=:myeventid");
  $EditEventprint->bindValue(":myeventid",$myeventid, PDO::PARAM_INT);
  $EditEventprint->execute();
  $resultprint = $EditEventprint->fetch();

  $Email = $resultprint['Email'];
  $FirstName = $resultprint['FirstName'];
  $LastName = $resultprint['LastName'];
  $username = $resultprint['username'];
  $daate = $resultprint['EventDate'];
  $phonenumber = $resultprint['phonenumber'];
  $mymail = $resultprint['mymail'];
  $other['--EMAIL--'] = $Email;
  $other['--FIRSTNAME--'] = $FirstName;
  $other['--LASTNAME--'] = $LastName;
  $other['--Comment--'] = $commnetonet;
  $other['--UserName--'] = $username;
  $other['--daate--'] = $daate;
  $other['--phonenumber--'] = $phonenumber;
  $other['--mymail--'] = $mymail;

  if($_SESSION['usertype']=='subscriber'){
    $userid = $_SESSION['UserID'];
  }else{
    $userid = $_SESSION['adminid'];
  }
  $query=$db->prepare("SELECT * from CompanyInformation where createdfk=:id");
  $query->bindValue(":id",$userid);
  $query->execute();
  $compinfo = $query->fetch();
  $other['--USERNAME--'] = $_SESSION['UserName'];
  $other['--COMPNAME--'] = $compinfo['CompanyName'];
  $other['--COMPNUMBER--'] = $compinfo['Phone'];
  $other['--COMPEMAIL--'] = $compinfo['email'];
  $other['--BOOKING_URL--'] = base_url."/Book-now?ref=".base64_encode($userid);

  $cancel_message = "";
  $commnetonet = $_POST['commnetonet'];
  $commnettwot = $_POST['commnettwot'];
  if(!empty($commnetonet)){
    $cancel_message.= '<li>Comment:<br>'.$commnetonet.'</li>';
  }
  if(!empty($commnettwot)){
    $cancel_message.= '<li>Message:<br>'.$commnettwot.'</li>';
  }

  if(!empty($commnetonet) || !empty($commnettwot) ){
    $other['--MESSAGE--'] = '<div style="background: #e4dfdf; padding: 5px; border-radius: 10px; "><ul>'.$cancel_message.'</ul></div>';
  }else{
    $other['--MESSAGE--'] = "";
  }

  if($_POST['eventcustohistory']=='yes')
  {

    $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat, description=:commnetonet,Appcanmsgfcus=:commnettwot WHERE id=:myeventid");
    $stmt->bindparam(":commnetonet",$commnetonet);
    $stmt->bindparam(":commnettwot",$commnettwot);
    $stmt->bindparam(":newstat",$newstat);
    $stmt->bindparam(":myeventid",$myeventid);
    $stmt->execute(); 

    if($stmt)
    {
      $message = '';
      $headers = '';
      sendEventMailForcanl($Email, "Appointment Cancellation!", "EventCan.php", $message, $headers, $other);    
      echo json_encode(['resonse'=>'Appointment status successfully change']);die;
    }
  }
  else
  {

    $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
    $stmt->bindparam(":newstat",$newstat);
    $stmt->bindparam(":myeventid",$myeventid);
    $stmt->execute(); 

    $message = '';
    $headers = '';
    sendEventMailForcanl($Email, "Appointment Cancellation!", "EventCan.php", $message, $headers, $other);    
    echo json_encode(['resonse'=>'Appointment status successfully change']);die;

  }



}


if(isset($_POST['commnetonet_1']))
{

  $commnetonet = $_POST['commnetonet_1'];
  $commnettwot = $_POST['commnettwot_1'];
  $myeventid = $_POST['myeventid_1'];
  $newstat = $_POST['newstat2_1'];


  if($_POST['eventcustohistory_1']=='yes')
  {

    $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat, description=:commnetonet,Appcanmsgfcus=:commnettwot WHERE id=:myeventid");
    $stmt->bindparam(":commnetonet",$commnetonet);
    $stmt->bindparam(":commnettwot",$commnettwot);
    $stmt->bindparam(":newstat",$newstat);
    $stmt->bindparam(":myeventid",$myeventid);
    $stmt->execute(); 

    if($stmt)
    {
      echo json_encode(['resonse'=>'Appointment status successfully change']);die;
    }
  }
  else
  {

    $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
    $stmt->bindparam(":newstat",$newstat);
    $stmt->bindparam(":myeventid",$myeventid);
    $stmt->execute(); 
    echo json_encode(['resonse'=>'Appointment status successfully change']);die;

  }



}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include 'head.php';
?>
<link href="<?php echo base_url; ?>/assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css">
<link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />

<!-- <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css"> -->
<link href="<?php echo base_url; ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">
<link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/pagination.css">

<style> 
  ul#clientnotelistes li{display: block;}
  #myModal_data{display: none; background: red; z-index: 9999; width: 500px; height: 500px;}
  span#cal-title-on-day{font-size: 11px;}
  div#cal-week-box{z-index: 9999;}
  ul#eventlist2{display: block !important; font-size: 10px;}
  div#cal-slide-box{background: #333!important;}
  .alleventlist {padding: 25px 0;}
  .eventlisttitle{background: #3cabe1;padding: 10px;}
  .eventlist{background: antiquewhite;  padding: 10px;}
  .eventlist2 a{    color: #333;   text-decoration: none;    text-transform: capitalize;}
  div#home2 {padding: 10px 0;}

  div#myModal_new2 .modal-body{overflow: scroll;  }
  div#myModal_new2{z-index: 9999999;}
  /* div#myModal_exit2 .modal-body{overflow: scroll;   height: 600px;}*/
  .eventStart1{width: 45%; float: left; padding: 0 10px;}
  span.month,span.year{padding: 5px 10px;}
  .table-condensed tr{text-align: center;}
  .dropbtn_cal { background-color: #dddddd;   color: black;   padding: 7px;   border-radius: 4px;   border: none;    cursor: pointer;}
  .dropdown_cal { position: relative;    display: inline-block; margin: 0 10px;}
  .dropdown-content_cal { display: none; width: 100%;position: absolute;left: 0px;top: 35px;z-index: 2;}
  .dropdown-content_cal button { color: black; text-decoration: none; display: block; height: 35px; width: 100%; margin: 0px 0;background-color: #f7f3f3 ;}
  .dropdown-content_cal button:hover {background-color: #dddddd;}
  .show_cal{display:inline-grid;}
  #myDropdown_cal3{ background: white;    width: 160px;    left: 10px;margin-top: 32px;}
  input.date.end.form-control{width: 49%;  padding: 0 5px;   }
  input.time.end.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;  }
  input.time.start.form-control.ui-timepicker-input{width: 49%;  padding: 0 5px;   }
  input.date.start.form-control{padding: 0 5px;  }
  #addnewappointment {
    border: 1px solid;
    border-bottom: 3px solid;
    color: #3cabe1;
    border-color: #3cabe1;
    background: white;
    border-radius: 20px;
    padding: 8px 14px!important;
  }
  #addnewappointment:hover {
    border-bottom: 1px solid;
  }
/*select.form-control:not([size]):not([multiple]),.select2-container--default .select2-selection--single{border:0; border-bottom: 1px solid #aaa!important; border-radius:0!important;}
select#eventstatus{ border-bottom: 1px solid #fff !important ; }*/

.exit-client-image{object-fit: cover;}
.pac-container.pac-logo{z-index: 99999;}
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
  background-color: yellow;
}
.event-red{
  background-color: #b51818;
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

@media screen and (max-device-width: 768px) { 
  .customtab{
    justify-content: center;
  }
}
.dh-event-red{background: red; color: white; border: red;}
.dh-event-red a {color: white;}
.dh-event-red:hover{color: white;}
span.btn.btn-print.fa.fa-print{    background: green;   color: white;   border-color: green;}
.pull-left.day-event.day-highlight{height: auto !important;}
.pull-left.day-event.day-highlight{width: 30 !important%}
td.disabled.day{background: #eeeeee !important;}



@media (max-width: 780px){
  #UpcomingRenewalsDays{width: 100% !important;}

}
#UpcomingRenewalsDays{width: 20%;}
th { font-weight: bold!important;color:#0b59a2!important;}


.select2-container--default .select2-selection--multiple:before {
  content: ' ';
  display: block;
  position: absolute;
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 5px 4px 0 4px;
  height: 0;
  right: 6px;
  margin-left: -4px;
  margin-top: -2px;top: 50%;
  width: 0;cursor: pointer
}

.select2-container--open .select2-selection--multiple:before {
  content: ' ';
  display: block;
  position: absolute;
  border-color: transparent transparent #888 transparent;
  border-width: 0 4px 5px 4px;
  height: 0;
  right: 6px;
  margin-left: -4px;
  margin-top: -2px;top: 50%;
  width: 0;cursor: pointer
}

</style>
<style type="text/css">

  @media screen and (max-width: 768px) { 
    .event_font_size{
      width: 90px!important;
      height: 90px!important;
    } 
    .event_font{
      font-size: 20px!important;
    }
  }

  .event_font_size{

    text-align: center;
    width: 110px;
    height: 110px;
    margin: 10px;
    border-radius: 5px;
    box-shadow: 3px 3px 2px 1px #0000003d;
  }

  .event_font{
    color: white;
    transition: transform .5s ease;
    font-size: 40px;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin: 0 auto;
  }

  .event_font:hover{
    transform: scale(1.25);
  }

  .radio-custom {
    opacity: 0;
    position: absolute;   
  }

  .radio-custom, .radio-custom-label {
    display: inline-block;
    vertical-align: middle;
    margin: 5px;
    cursor: pointer;
  }

  .radio-custom-label {
    position: relative;
  }

  .radio-custom + .radio-custom-label:before {
    content: '';
    background: #fff;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    width: 25px;
    height: 25px;
    padding: 2px;
    margin-right: 10px;
    text-align: center;
  }

  .radio-custom1 + .radio-custom-label1:before {
    background: yellow!important; 
    border: 2px solid yellow;
  }

  .radio-custom2 + .radio-custom-label2:before {
    background: #00d600!important; 
    border: 2px solid #00d600;
  }

  .radio-custom3 + .radio-custom-label3:before {
    background: #ff0000!important; 
    border: 2px solid #ff0000;
  }

  .radio-custom4 + .radio-custom-label4:before {
    background: #b51818!important; 
    border: 2px solid #b51818;
  }

  .radio-custom5 + .radio-custom-label5:before {
    background: #ffb100!important; 
    border: 2px solid #ffb100;
  }

  .radio-custom6 + .radio-custom-label6:before {
    background: black!important; 
    border: 2px solid black;
  }

  .checkbox-custom:checked + .checkbox-custom-label:before {
    content: "\f00c";
    font-family: 'FontAwesome';
    background: rebeccapurple;
    color: white !important;
  }

  .radio-custom + .radio-custom-label:before {
    border-radius: 50%;
  }

  .radio-custom:checked + .radio-custom-label:before {
    content: "\f00c";
    font-family: 'FontAwesome';
    color: white !important;
  }

  .radio-custom:focus + .radio-custom-label {
    outline: 1px solid #ddd; /* focus style */
  }

  #clinetdetails{
    align-items: center;
    background: aliceblue;
    padding: 15px 8px;
    border-radius: 10px;
  }
</style>
<style>
  #list_search{
    padding: 10px;
    position: relative;
      width: 250px;
  }
  #list_search+i{
    position: absolute;
  }
  #list_search{
    background-size: 0 2px,100% 1px;
    transition: background 0s ease-out;
    float: none;
    box-shadow: none;
    border-radius: 0;
    border: 0;
    background-repeat: no-repeat;
    background-position: center bottom,center calc(100% - 1px);
    background-color: transparent;
    background-image: linear-gradient(#3cabe1,#3cabe1),linear-gradient(#e9ecef,#e9ecef);
  }
  #list_search:focus {
    outline: 0;
    background-size: 100% 2px,100% 1px;
    box-shadow: none;
    transition-duration: .3s;
  }

  #myTable{
    margin-top:20px;
  }
  .client_event_time{
    padding: 0px;
  }
  .list {
    border: 1px solid #00000024;
    margin-bottom: 10px;
    border-radius: 2px;
    box-shadow: 1px 3px 2px 0px #0000001a;
  }
  .list .row{
    margin:0px !important;
    padding: 10px 16px;
    font-size: 15px;
  }
  .list .list_body {
    border-bottom: 1px solid #00000024;
  }
  .list .list_header{
    justify-content: space-between;
    background-color: #f7f8fa;
  }
  .list_header .list_day{
    font-weight: 600;
  }
  .list_body{
    text-align: center;
    align-items: center;
  }
  .list_body .client_status .client_status_dot{
    margin: auto;
    width: 15px;
    height: 15px;
    border-radius: 50%;
  }
  .list_body .client_profile{
    display: flex;
    flex-direction: column;
    text-align: center;
  }
  .list_body .client_contact{
    color: #3cabe1;
  }
  .list_body .client_contact span{
    color: black;
  }
</style>

<body class="skin-default fixed-layout mysunlessD">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url; ?>/assets/chartjs/Chart.css">
  <script type="text/javascript" src="<?php echo base_url; ?>/assets/chartjs/Chart.js"></script>
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
                    <a title="Add New Appointment" href="#" id="addnewappointment" class="nav-link" data-toggle="modal" data-target="#myModal_exit2">
                      <i class="fa fa-plus"></i>
                    </a>
                  </li>

                  <?php
                  $pending_app = "";
                  $active_app = "";
                  if(isset($_SESSION['not_approve_app'])){
                    $pending_app = 'active';
                    unset($_SESSION['not_approve_app']);
                  }else{
                    $active_app = 'active';
                  }
                  ?>

                  <li class="nav-item"> 
                    <a class="nav-link <?= $active_app ?>" data-toggle="tab" href="#home2" role="tab">
                      <span class="hidden-sm-up"><i class="fa fa-calendar"></i></span> 
                      <span class="hidden-xs-down">Calendar View</span>
                    </a> 
                  </li>
                  <li class="nav-item"> 
                    <a id="list_view" class="nav-link" data-toggle="tab" href="#profile2" role="tab">
                      <span class="hidden-sm-up"><i class="fa fa-list"></i></span> <span class="hidden-xs-down">List View</span>
                    </a> 
                  </li>
                  <li class="nav-item"> 
                    <a class="nav-link <?= $pending_app ?>" data-toggle="tab" href="#notAcceptAppo" role="tab">
                      <span class="hidden-sm-up"><i class="fa fa-calendar-times-o"  aria-hidden="true"></i></span> <span class="hidden-xs-down"> Requested Event List <span class="badge badge-warning totalCount" style="font-size: 12px;"></span> </span>
                    </a> 
                  </li>

                           <!-- <li class="nav-item"> <i class="fas fa-clipboard-list-check"></i>
                              <select class="calnder_option">
                                <option>Select Type</option>
                                <option>1</option>
                                <option>2</option>
                              </select>
                          </li>  -->

                      </ul>

                      <!-- Tab panes  -->
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
                                <!-- <p>Some text in the modal.</p>   -->

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

                                <!--  <button type="button" class="btn waves-effect waves-light btn-secondary myModal_new" data-toggle="modal" data-target="#myModal_new">New Client</button>  -->
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
                        <!--  Start Add new client modal  -->
                        <div class="modal fade" id="myModal_new2" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:1100">
                          <div class="modal-dialog modal-lg" style="max-width: 1100px;">

                            <!-- Modal content -->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Customer Details</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <form class="form-horizontal" action="" method="post" autocomplete="nope" id="NewClient">

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
                                  <div class="customersdetalisone">

                                    <div class="form-group">
                                      <label for="example-email">Profile Photo (jpg/jpeg)<span class="help"></span></label>
                                      <div class="card">
                                        <div class="card-body">
                                          <input type="file" id="ProfileImg" name="ProfileImg" class="dropify" data-allowed-file-extensions='["png", "jpg","jpeg"]'>
                                          <input type="hidden" name="ProfileImg"id="oldimage" value="">
                                          <input type="hidden" id="ProfileImg2" name="ProfileImg2" class="">
                                          <input type="hidden" id="ProfileImg3" name="ProfileImg3" class="">
                                        </div>
                                      </div>
                                    </div>
  <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  
    <img src="http://lorempixel.com/75/50/abstract/">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>   -->

    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%; margin-bottom: 20px;">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>


    <div class="dropdown-menu" style="width: 96%;">
      <?php
      $stmta= $db->prepare("SELECT * FROM `listofavtar` order by id DESC"); 
      $stmta->execute();
      $stmtall = $stmta->fetchAll(PDO::FETCH_ASSOC);
      foreach($stmtall as $row)
      {
        ?>
        <label style="padding: 5px;">
          <input type="radio" class="radio" name="ProfileImg" value="<?php echo $row['Name']; ?>" style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer; outline: 2px solid #f00;" >
          <img src="<?php echo base_url.'/assets/ProfileImages/'.$row['Name'];?>" width= "50px" height="50px" style="cursor: pointer;">
        </label>
        <?php

      }
      ?>
    </div>
  </div>

  <div class="customersdetalistwo">
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
      <input type="text" name="Phone" id="phonenumber" class="form-control" value="" autocomplete="nope"  placeholder="1234567890">
    </div>

    <div class="form-group">
      <label for="example-email">Email * <span class="help"></span></label>
      <input type="email" id="example-email" name="email" class="form-control" placeholder="Email" value="" autocomplete="nope" placeholder="exaple@gmial.com" maxlength="30">
    </div>
  </div>



  <div class="customersdetalistree">

    <div class="form-group">
      <label for="example-email">Address *</label>

      <input autocomplete="nope" type="text" name="Address" placeholder="Enter your address" id="Address" class="form-control" placeholder="Address" value="" >
    <!-- <input id="autocomplete" placeholder="Enter your address"  class="form-control" name="Address"  onFocus="geolocate()" type="text" value=""></input>
    <input type="hidden" class="" id="street_number" disabled="true"></input>
    <input type="hidden" class="" id="route" disabled="true"></input> -->
</div>




<!--   <div class="form-group">
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
</div> -->
<div class="form-group">
  <label for="country">Country *</label>
  <input type="hidden" id="country" name="Country" value="United States">
  <input type="text" disabled="" value="United States" class="form-control">
</div>

<div class="form-group">
  <label><span class="help">State *</span></label>
  <select class="form-control" id="administrative_area_level_1" name="State">
    <option value="">Select a State</option>
    <?php
    $newstate = "Alabama,Alaska,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,Florida,Georgia,Hawaii,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana,Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Tennessee,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming";
    $stateList = explode(',', $newstate);
    foreach($stateList as $value){
      if($value == $result['state'] ){
        echo "<option selected value='".$value."'>".$value."</option>";
      }
      else{
        echo "<option value='".$value."'>".$value."</option>";
      }
    }
    ?>
  </select>
</div>

<!--  <div class="form-group">
  <label><span class="help">State *</span></label>

  <select class="form-control" id="administrative_area_level_1" name="State">
    <option value="">Select a State</option>
    <?php
    foreach($stateList as $value){
      echo "<option value='".$value['name']."'>".$value['name']."</option>";
    }
    ?>
  </select>

</div> -->

<div class="cutomercityandzip">
  <div class="cumtercity">  
    <div class="form-group">
      <label for="example-email">City *</label>
      <input  id="locality" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
    </div>
  </div>

  <div class="cumterzip">
    <div class="form-group">
      <label for="example-email">Zip Code *</label>
      <input type="text"  id="postal_code" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="0123456" maxlength="10"></input>
    </div>
  </div>
</div>
</div> 
<!-- <a  class="btn btn-waves-effect waves-light btn-secondary back " id="back" ><?php echo $B20; ?></a>  -->



<div class="Loader"></div>
<div class="clearfix" style="clear: both;"></div>
</div>
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
<div class="modal-footer">
  <div class="form-group">
    <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" name="add-client" id="add-client"><i class="fa fa-check"></i> <?php echo $B18; ?></button>
  </div>
  <div class="form-group">
    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B19; ?></button>
  </div>
</div>
</form>
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

  <style>
    .page-header{
      text-align: center;
    }
    .page-header .btn-default{
      background-color: white;
      border: 1px solid #ebedf2;
    }
    .page-header .btn-default:hover{
      background-color: #6a7af9;
      color: white;
    }

    /*Pgination css start */

    #pagination-container{
      float: right;

    }

    /*Pagination css end*/

  </style>

  <!--  End Add appointment tab -->
  <!-- star Calendar View Tab -->
  <div class="tab-pane <?= $active_app ?>" id="home2" role="tabpanel">

    <div class="page-header" id="calendar_head">
      <div class="form-inline justify-content-center">
        <div class="btn-group">
          <button class="btn btn-default notave" data-calendar-nav="prev"><</button>
          <button class="btn btn-default notave" data-calendar-nav="next">></button>
        </div>
        <div class="btn-group ml-2">
          <button class="btn btn-default notave" data-calendar-nav="today">today</button>
        </div>
      </div>

      <h3></h3>

      <div class="form-inline justify-content-center">
        <div class="dropdown_cal">
          <img onclick="myFunction3()" class="dropdown-toggle" src="<?= base_url?>/assets/images/colorpicker.png" title="appointment status" width="30" height="30" id="" style="cursor: pointer; margin: 0 5px;"  /></button>
          <div id="myDropdown_cal3" class="dropdown-content_cal">
            <div class="btn-group"><span class="pull-left event event-grey"></span> : Pending <br></div>
            <div class="btn-group"><span class="pull-left event event-success"></span> : Confirmed <br></div>
            <div class="btn-group"><span class="pull-left event event-important"></span> : Canceled <br></div>
            <div class="btn-group"><span class="pull-left event event-red"></span> : Pending Payment <br></div>
            <div class="btn-group"><span class="pull-left event event-warning"></span> : In Progress <br></div>
            <div class="btn-group"><span class="pull-left event event-info"></span> : Completed <br></div>



          </div>
        </div>

        <img src="<?= base_url?>/assets/images/calendarbtn.png" data-toggle="tooltip" title="Month Calendar" width="37" height="35" id="monthbtn" style="cursor: pointer;" />

        <div class="dropdown_cal">
          <button onclick="myFunction2()" class="dropbtn_cal btn btn-default dropdown-toggle">Filter</button>
          <div id="myDropdown_cal2" class="dropdown-content_cal">
            <div class="btn-group"><button id="calendar_refresh" class="btn btn-default">Refresh</button></div>
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


            <div>
              <div class="btn-group">
                <button class="btn btn-default active notave calendar" id="notave" data-calendar-view="month">Month</button>
                <button class="btn btn-default calendar" data-calendar-view="week">Week</button>
                <button class="btn btn-default daydash calendar" data-calendar-view="day">Day</button>
                <button class="btn btn-default" id="Graph">Graph</button>
              </div>
            </div>

<!--    <div class="dropdown_cal">
     <button onclick="myFunction()" class="dropbtn_cal dropdown-toggle">Month</button>
     <div id="myDropdown_cal" style="margin: 35px 0px 0px -65px" class="dropdown-content_cal">
      <div class="btn-group"><button class="btn btn-default" data-calendar-view="year">Year</button></div>
      <div class="btn-group"><button class="btn btn-default active notave" id="notave" data-calendar-view="month">Month</button></div>
      <div class="btn-group"><button class="btn btn-default" data-calendar-view="week">Week</button></div>
      <div class="btn-group"><button class="btn btn-default daydash" data-calendar-view="day">Day</button></div>
    </div>
</div> -->

</div>

</div>

<style>
  #cal-day-panel::-webkit-scrollbar, .cal-week-box::-webkit-scrollbar {
    width: 10px;
    height: 10px;
  }


  /* Handle */
  #cal-day-panel::-webkit-scrollbar-thumb, .cal-week-box::-webkit-scrollbar-thumb {
    background: #3cabe1; 
    border-radius: 8px;
  }

  /* Handle on hover */
  #cal-day-panel::-webkit-scrollbar-thumb:hover, .cal-week-box::-webkit-scrollbar-thumb:hover {
    background: #4999bf; 
  }
</style>
<div class="row"  style="width: 100%;margin: 0px;">
  <div id="showEventCalendar"></div>
  <div id="showEventCalendarEvents" style="display: none;width: 100%;">
    <div id="showEvent" style="display: none">
      <div class="EventClose">Close</div>
      <div class="divider"></div>
      <div id="showEventbody"></div>
    </div>
  </div>

  <div id="showEventGraph" style="display: none; width: 100%">
    <ul class="nav nav-tabs profile-tab" role="tablist">
      <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#appgraph" role="tab">Appointment Graph</a> </li>
      <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#sellgraph" role="tab" id="sellgraphTab">Sells Bar-Graph</a> </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="appgraph" role="tabpanel">
        <?php include('AppointmentGraph.php') ?>
      </div>
      <div class="tab-pane" id="sellgraph" role="tabpanel">
        <?php include('SellsGraph.php') ?>
      </div>
    </div>
  </div>



      <!-- <div class="col-md-4">
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
          if(!empty($result_listofeventforca))
          {
            foreach ($result_listofeventforca as $key => $value)
            {

              ?>

              <li><a class="EditButton" href="#" data-id="<?php echo base64_encode($value['id']); ?>"><b style="width: 33%; float: left;"><?php echo ucfirst($value['cf'].' '.$value['cl']) ?></b><b style="width: 33%;"><?php echo $value['title']; ?></b><b style="width: 33%; float: right;" ><?php echo date('m/d/y',strtotime($value['EventDate']));?></b></a></li>

              <?php
            }
          }
          else
          {
            echo "<li> No upcoming appointment </li>";
          }

          ?>    

        </ul>
      </div>

      </div>
  </div> -->
</div>
</div>

<!--  End Calendar View tab -->
<div id="myModal_data"></div>
<!--  Start List View tab -->
<div class="tab-pane  p-20" id="profile2" role="tabpanel">
  <div class="col-lg-12">
    <div style="position: relative;">
    
      <i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" style="font-size: 22px;float: right"></i>
      <div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width:400px">
        <div class="modal-header">
          <h4 class="modal-title">Event Filter</h4>
          <button type="button" class="close mysunless_filter_btn" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <div>
              <div id="EventDate" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <input type="text" id="EventDateRange" readonly style="width:90%;border:0" placeholder="Event Date">
                <i class="fa fa-caret-down"></i>
              </div>
            </div>
          </div>

            <div class="form-group">
                <select class="form-control select2" id="EventFilterUser" data-placeholder="Choose User" multiple="multiple">
                    <?php 

                    if($_SESSION['usertype']=='Admin'){

                        $query= $db->prepare("SELECT * from users where usertype='subscriber' and id in (875,666) ");
                        $query->execute();
                        $result = $query->fetchAll();
                        foreach ($result as $key => $value) {

                            echo '<optgroup label="'.$value["firstname"].' '.$value["lastname"].'">';

                            $query1= $db->prepare("SELECT * from users where id=:id or adminid=:id");
                        $query1->bindParam(':id',$value['id']); 
                        $query1->execute();
                        $result1 = $query1->fetchAll();
                        foreach ($result1 as $key1 => $value1) {
                            echo '<option value="'.$value1["id"].'" >'.$value1["firstname"].' '.$value1["lastname"].'</option>';
                        }

                            echo '</optgroup>';

                        }

                    }else{

                        $query= $db->prepare("SELECT * from users where id=:id or adminid=:id");
                        $query->bindParam(':id',$_SESSION['UserID']); 
                        $query->execute();
                        $result = $query->fetchAll();
                        foreach ($result as $key => $value) {
                            echo '<option value="'.$value["id"].'" >'.$value["firstname"].' '.$value["lastname"].'</option>';
                        }

                    }
                    ?>
                </select>
            </div>

         <div class="form-group">
          <select class="select2 form-control" id="EventFilterCustomer" name="selectcutomer[]"  data-placeholder="Choose Customer" multiple="multiple">
            <?php
            $eidtUserName3 = $db->prepare("select id,FirstName,LastName from clients where isactive=1 and createdfk in (Select DISTINCT(u2.id) from users u1 join users u2 on u1.id=u2.id or u1.adminid=u2.id or u1.id=u2.adminid where u1.id=:id) order by clients.FirstName");
            $eidtUserName3->bindValue(":id",$_SESSION['UserID']);
            $editfile4=$eidtUserName3->execute();
            $allua=$eidtUserName3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($allua as $key => $value) 
            {
              ?>
              <option value="<?php echo $value['id'] ?>"><?php echo $value['FirstName'].' '.$value['LastName']; ?></option>
              <?php
            }
            ?>
          </select>
        </div>


          <div class="form-group">
            <select data-placeholder="Filter by Event Status" class="form-control select2" id="EventFilterStatus" multiple="multiple"> 
              <option value="completed">Completed</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="canceled">Canceled</option>
              <option value="pending-payment">Pending Payment</option>
              <option value="in-progress">In-progress</option>
            </select>
          </div>

          <div class="form-group">
            <span id="EventDate_Submit" class="btn btn-sm btn-info">Submit</span>
            <span id="EventDate_clear" class="btn btn-sm btn-danger">Reset</span>
          </div>
        </div>
      </div>

      <div class="form-inline" style="justify-content: flex-end;">
        <i class="fa fa-question-circle m-1" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Search by Customer Name, Email and Event Name. If you search by Appointment ID then use last few digits after dash(-). Example: 20200804-123, Search: 123"></i>
        <input  id="list_search"type="text" name="list_search" value="" placeholder="Search...">
        <i class="fas fa-search search-icon"></i>

      </div>

    </div>      

    <div id="myTable"> </div>
    <div id="pagination-container"></div>
  </div>
</div>
<!--  End List View tab -->

<!-- Start List Not Accepted Appointment-->

<div class="tab-pane p-20 <?= $pending_app ?>" id="notAcceptAppo" role="tabpanel">
  <div class="col-lg-12">
<div class="table-responsive m-t-40 col-md-12">
  <table id="myTableNotAccepted" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
    <thead>
      <tr>
        <th>Title</th>
        <th>Service Provider</th>
        <th>Client Name</th>
        <th>Contact</th>
        <th>Appointment Time</th>
        <th>Action</th>
      </tr>
    </thead>
  </table>
</div>
</div>
</div>

<!-- End List Not Accepted Appointment -->


<div class="modal hide fade in" id="eventdetailmodel" role="dialog"  data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Event Detail</h4>
        <button type="button" class="close" onclick="$('.Loader').hide();" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
        <div>
          <style>
            .event_relation{
              display: flex;
              align-items: center;
              justify-content: space-around;
            }
            .event_relation_service_provider, .event_relation_customer{
              display: flex;
              flex-direction: column;
              text-align: center;
            }
            .event_relation_service_provider img, .event_relation_customer img{
              width: 100px;
              height: 100px;
            }
          </style>
          <div class="event_relation">
            <div class="event_relation_service_provider">
              <img class="img-circle ViewUserInfo" title="View Profile" src="">
              <label class="m-0">Service Provider</label>
              <strong class="evnetemployename"></strong>
            </div>
            <i class="fa fa-handshake-o" aria-hidden="true" style="font-size: 50px;"></i>
            <div class="event_relation_customer">
              <img class="img-circle viewInfo" title="View Profile" src="">
              <label class="m-0">Customer</label>
              <strong class="evnetcutomername"></strong>
            </div>
          </div>
          <hr>

          <div style="width: 250px;margin:0 auto;">
            <div class="row" style="justify-content: space-between;">
              <span>Appointment ID :</span> <span id="evnetid" class="evnetid"></span>
            </div>
            <div class="row" style="justify-content: space-between;">
              <span>Customer Name :</span> <span id="evnetcutomername" class="evnetcutomername"></span>
            </div>
            <div class="row" style="justify-content: space-between;">
              <span>Service : </span><span id="envetsername" class="envetsername"></span>
            </div>
            <div class="row" style="justify-content: space-between;">
              <span>Start Date/Time : </span><span id="evnetstartime" class="evnetstartime"></span>
            </div>
            <div class="row" style="justify-content: space-between;">
              <span>Service Provider : </span><span id="evnetemployename" class="evnetemployename"></span>
            </div>
            <div class="row" style="justify-content: space-between;">
              <span>Location : </span><span id="App_location" class="App_location"></span>
            </div>
          </div>
          <hr>

          <div class="row" style="justify-content: space-around;">
            <a href="#" class="appointmentwithnote clinetdetails_note_cid event_font_size" data-toggle="modal" data-target="#myModal_listofnote" style="color: black;" data-id=''><span data-toggle="tooltip" data-placement="bottom" title="Note" class="event_font event-grey fa fa-sticky-note" aria-hidden="true"></span></a>

            <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Edit appointment" id="ViewEvent" data-event-id="" class="EditButton event_font_size" style="color: black;" data-id=""><span class="event_font  fa fa-edit" style="background: #2a5ae2"></span></a>

            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Delete appointment" name="DeleteEvent" data-event-id="<%= event.id %>" style="color: black;" id="DeleteEvent" class=" event_font_size"><span class="event_font fa fa-trash" style="background: #DB2828"></span> </a>

            <a href="#" data-toggle="tooltip" data-placement="bottom" title="View customer " name="ViewCustomer" data-event-id="<%= event.cid %>" id="ViewCustomer" style="color: black;" class=" event_font_size" ><span class="event_font bg-primary fa fa-eye"></span></a> 

            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Print Ticket" class="printappoiment event_font_size" id="printappoiment" style="color: black;" data-id=''><span class="event_font fa fa-print" aria-hidden="true" style="background: #24a92a"></span> </a>

            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Directions to Appointment" class="cusomerlocation event_font_size" id="cusomerlocation" style="color: black;" data-id=''><span class=" event_font bg-warning fa fa-map-marker" aria-hidden="true"></span> </a>

            <a href="#" data-toggle="tooltip" target="_blank" data-placement="bottom" title="Checkout" onclick="statuschng()" class="checkout event_font_size" id="checkout" style="color: black;" data-id=''><span style="background: black;" class="event_font fa fa-shopping-cart" aria-hidden="true"></span> </a>

          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <div>
                <input id="radio-1" class="radio-custom radio-custom1" value="pending" name="radio-group" type="radio">
                <label for="radio-1" class="radio-custom-label radio-custom-label1">Pending</label>
              </div>
              <div>
                <input id="radio-2" class="radio-custom radio-custom2" name="radio-group" value="confirmed" type="radio">
                <label for="radio-2" class="radio-custom-label radio-custom-label2">Confirmed</label>
              </div>
              <div>
                <input id="radio-3" class="radio-custom radio-custom3" name="radio-group" type="radio" value="canceled">
                <label for="radio-3" class="radio-custom-label radio-custom-label3">Canceled</label>
              </div>
            </div>
            <div class="col-md-6">
              <div>
                <input id="radio-4" class="radio-custom radio-custom4" name="radio-group" type="radio" value="pending-payment">
                <label for="radio-4" class="radio-custom-label radio-custom-label4">Pending Payment</label>
              </div>

              <div>
                <input id="radio-5" class="radio-custom radio-custom5" name="radio-group" type="radio" value="in-progress">
                <label for="radio-5" class="radio-custom-label radio-custom-label5">In Progress</label>
              </div>

              <div>
                <input id="radio-6" class="radio-custom radio-custom6" name="radio-group" type="radio" value="completed">
                <label for="radio-6" class="radio-custom-label radio-custom-label6">Completed</label>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="$('.Loader').hide();" data-dismiss="modal">Close</button>
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
        <button type="button" class="close" onclick="$('.Loader').hide();" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">
        <div>

          <div class="map-box">
            <iframe id="eventaddermap" src="" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
          </div>      
<!-- <input class="form-control register-form__location-holder" type="text" id="user_location" name="user_location" placeholder="Enter place name" value="<?php echo $user_city ;?>" type="text"/>
<div style="width:100%;height:300px" id="register-form__map" class="register-form__map register-form__map--user"></div> 

-->

</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" onclick="$('.Loader').hide();" data-dismiss="modal">Close</button>
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

<?php include_once('BookEventModule.php'); ?>


<div class="modal fade" id="myModal_appoimentcenletion" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cancel Appointment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="Loader" style="display: none;"></div>
        Appointment ID : <span id="evnetid" class="evnetid"></span><br>
        Customer Name : <span id="evnetcutomername" class="evnetcutomername"></span><br>
        Service : <span id="envetsername" class="envetsername"></span><br>
        Start Date/Time : <span id="evnetstartime" class="evnetstartime"></span><br>
        Employee : <span id="evnetemployename" class="evnetemployename"></span><br><br><br>

        <div>
          <div class="form-group">
            <label for="todoDesc">Comment</label>
            <textarea class="textarea_editor form-control" placeholder="Enter Comment..." id="appointmentcancelcomment" name="appointmentcancelcomment"></textarea>
          </div>


          <div class="form-group">
            <label for="todoDesc">Message to customer</label>
            <textarea class="textarea_editor form-control" placeholder="Enter Task Description..." id="appointmentcancelcommentforcusteomr" name="appointmentcancelcommentforcusteomr"></textarea>
          </div>

          <div class="form-group">
            <p class="package_carfor_p" style=""><input type="checkbox" class="event_carryford" id="event_carryford" name="event_carryford" value="yes"> Add this to customer's cancellation history </p>
          </div>

          <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="Notifytocustomer"> Notify Customer</button>
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="dontNotifytocustomer"> Don't Notify Customer</button>
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"> Cancel</button>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_listofnote" role="dialog">
  <div class="modal-dialog modal-lg" style="max-width: 1000px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">List of Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
        <button  class="appointmentwithnote btn btn-info m-r-10 assignNewVal" data-toggle="modal" data-target="#appointmentwithnote" id="appointmentwithnote1"  data-id="">Add Note</button>
        <div class="table-responsive m-t-40 col-md-12">
          <table id="ClinetnoteTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal  -->
<div class="modal fade" id="appointmentwithnote" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Client Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
        <form class="form-horizontal " autocomplete="off" id="NewEvent_todo" method="post">
          <input type="hidden" class="editnoteid" name="id" id="id" value="new">
          <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
          <div class="form-group">
            <label><span class="help"> Note Title  *</span></label>
            <input type="text" name="noteTitle" id="noteTitle" value="" class="form-control" maxlength="30">
          </div>
          <div class="form-group">
            <label><span class="help"> Note Detail  *</span></label>
            <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Note Detail ..." name="noteDetail" id="noteDetail" >  </textarea>
          </div>
          <div class="form-group">

            <input type="hidden" name="noteRelated[]" id="noteRelated" value="" class="form-control">
          </div>

          <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="addNote"> <i class="fa fa-check">
            </i> Submit Note</button>
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">  Cancel Note</button>
          </div>
        </form>

        <div class="col-lg-12 col-md-12">
          <div class="alert alert-success" id="resonse" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-success">
              <i class="fa fa-check-circle">
              </i>
              Success
            </h3>
            <p id="resonsemsg">
            </p>
          </div>
          <div class="alert alert-danger" id="error" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-danger">
              <i class="fa fa-exclamation-circle">
              </i>
              Errors
            </h3>
            <p id="errormsg">
            </p>
          </div>

          <div class="alert alert-danger" id="csrf_error" style="display: none;">
            <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
            <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div class="modal fade" id="myModal_addmoreservice" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<?php include_once('EventTicketModel.php');?>
<?php include_once('viewclientdetail.php'); ?>
<?php include_once('viewuserdetail.php'); ?>


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
<script src="<?php echo base_url; ?>/assets/js/pagination.js" type="text/javascript"></script>



<script>
  $(document).ready(function() {

    $(document).on('click','.more_event,.more_week_event',function(){
      $("#showEvent").show();
      $("#showEventbody").html($(this).next().html());
    });

    $(".EventClose").click(function(){
      $("#showEvent").hide();
    });

    $(".show_emp_btn").click(function(){
      $(".show_emp").toggleClass("show_emp_visible");
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });

    $(".chart_filter").select2({
      allowClear: true
    });

    $("#Graph").click(function(){
      $("#showEventGraph").show();
      $("#showEventCalendar").hide();
      $(".calendar_filter").hide();
      $("#calendar_head h3").text('Graph');
      event_chart();
    });

    $(".calendar").click(function(){
      $("#showEventGraph").hide();
      $("#showEventCalendar").show();
      $(".calendar_filter").show();
    });


    //active class
    setInterval(function(){$(".allevent").addClass("active");}, 10);

    var newdt = new Date();
    var today = new Date($.now());

    $('.events-list-div').each(function() {
      var newdt = new Date($(this).data('cal-start'));
      if(newdt.getDate() == today.getDate() && newdt.getMonth() == today.getMonth() && newdt.getFullYear() == today.getFullYear()){
        $(this).find('span').css('margin-top','0%');
      }
    });



    var userIdd = '<?php echo $_SESSION["UserID"]; ?>';



    var table = $('#myTableNotAccepted').DataTable({
      "language": {
        "emptyTable": "Not Available Appointment for Apporved / Canceled "
      },
      "processing": false,
      "order": [[ 4, "asc" ]],
      "responsive": true,
      "processing": true,
      "destroy": true,

      "ajax" : {
        "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?accept='+userIdd,
        "dataSrc" : ''
      },
      "autoWidth": false,
      "columns" : [ 
      {
        "data" : "title"
      },
      {
        "data" : "serviceprovider"
      },
      {
        "data" : "ClientName"
      }
      ,
      {
        "data" : "Phone",
      }
      ,
      {
        "data" : "EventDate",
      }, 
      {
        "data": "id",
        "render": function(data, type, row) {

          var encodedId = window.btoa(data);


          return '<button class="btn btn-info btn-sm edit_data custbutton edit_appo" id="edit_appo" title="Approve Appointment" data-id='+ encodedId +'>' + '<span class="fas fa-check"><span>' + '</button> <button class="btn btn-danger btn-sm custbutton" title="Cancelled Appointment" id="delete_appo" data-did='+ encodedId + '>' + '<span class="fas fa-times"><span>' + '</button>';
        }
      }
      ]
    });



    function selectAllNotAcceptedAppointment(table){
      table.ajax.reload(); 
        //$(myTableNotAccepted_processing)
        $("#myTableNotAccepted_processing").css("display", "none");

    }


    function getNotacceatAppointmentCount(userIdd){

      $.ajax({
        dataType:"json",
        type:"post",

        "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event?accept='+userIdd,
        success: function(data)
        {

          var count  = data.length;
          $('.totalCount').text(count);

        }

      });
    }


    getNotacceatAppointmentCount(userIdd);

    setInterval(function(){ 
      getNotacceatAppointmentCount(userIdd); 
      selectAllNotAcceptedAppointment(table);
    }, 15000);
  //1554834600000
  //1554874106298
 // alert($.now());
  // $('#ydate').datepicker({
  //    format: "mm",
  //   startView: "months", 
  //   minViewMode: "months"
  // });

 // $('#myModal_exit2').modal('toggle');

//  function getsponserid(mysid)
//  {

//    var newsponserid = mysid;
// $("#listofcatagory3").val(newsponserid).trigger('change');




  //------------------ Apporved appointment End


  $(document).on('click','#edit_appo',function(e){
    e.preventDefault();
    var myappid = $(this).data('id');

    $(".Loader").show();
    $.ajax({
      dataType:"json",
      type:"post",
      data:{'myappid':myappid},
      "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event',
      success: function(data)
      {
        $(".Loader").hide();

          //console.log(data);
          getNotacceatAppointmentCount(userIdd);

          if(data.resonse)
          {

            swal("Approved!", data.resonse, "success");

          }
          else if(data.error)
          {

            swal("Approved!", data.error, "success");

          }
          else if(data.resonse_phone){

            swal("Approved!", data.resonse_phone, "success");

          }
          else
          {
            swal("Approved!", "Appointment Approved Successfully", "success");

          }
          table.ajax.reload(); 

          $("#calendar_refresh").trigger("click");

          if($("#myDropdown_cal2").hasClass("show_cal")){
            $("#myDropdown_cal2").removeClass("show_cal"); 
          }

      }

  });
  });

//------------------ Apporved appointment End


//------------------ Cancel appointment start



$(document).on('click','#delete_appo',function(e){
  e.preventDefault();
  var deleteappid = $(this).data('did');

  $(".Loader").show();

  $.ajax({
    dataType:"json",
    type:"post",
    data:{'deleteappid':deleteappid},
    "url" : '<?php echo base_url; ?>/Exec/Exec_Edit_Event',
    success: function(data)
    {
      $(".Loader").hide();

          //console.log(data);
          getNotacceatAppointmentCount(userIdd);

          if(data.resonse)
          {

            swal("Canceled!", data.resonse, "success");

          }
          else if(data.error)
          {

            swal("Canceled!", data.error, "success");

          }
          else if(data.resonse_phone){

            swal("Canceled!", data.resonse_phone, "success");

          }
          else 
          {
            swal("Approved!", "Appointment Canceled Successfully", "success");

          }


          table.ajax.reload(); 



      }

  });
});

//------------------ Cancel  appointment End


//  }


$(document).on('click','.edit_note',function(e){

  e.preventDefault();
  var mynoteid = $(this).data('id');

  $.ajax({
    dataType:"json",
    type:"post",
    data:{'mynoteid':mynoteid},
    "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteid="+mynoteid,
    success: function(data)
    {
      if(data.resonse)
      {
        $('#noteTitle').val(data.resonse.noteTitle)
                      // $('#noteDetail').val(data.resonse.noteDetail)
                      $('#noteDetail').data("wysihtml5").editor.setValue(data.resonse.noteDetail);
                      $('.editnoteid').val(data.resonse.id)
                      // NotedataTable(data.resonse.noteRelated)
                      $('#appointmentwithnote').modal('show');
                  }
                  else if(data.error)
                  {

                    swal("Sorry something wrong please try agine")
                  }
              }
          });
});

$(document).on('click','#clinetdetails_note_cid,.clinetdetails_note_cid',function(e){
  e.preventDefault();


  var mycid = $(this).attr('value');

  NotedataTable(mycid)

  function NotedataTable(mycid)
  {


    $('#ClinetnoteTable').DataTable({
      "responsive": true,
      "processing": true,
      "destroy": true,

      "ajax" : {
        "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mycid="+mycid,
        "dataSrc" : ''
      }
      ,
      "autoWidth": false,
      "columns" : [ 
      {
        "data" : "noteTitle"
      },
      {
        "data" : "enterdate"
      }
      ,
      {
        "data" : "noteDetail",
      }
      , 
      {
        "data": "id",
        "render": function(data, type, row) {

          var encodedId = window.btoa(data);


          return '<button class="btn btn-info btn-sm edit_data custbutton edit_note" id="edit_note" title="Edit Event" data-id='+ encodedId +'>' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-danger btn-sm custbutton" title="Delete Event" id="delete_note" data-did='+ encodedId + '>' + '<span class="fa fa-trash"><span>' + '</button>';
        }
      }
      ]
    });

  }




  $(document).on('click','#delete_note',function(e){
    e.preventDefault();
    var mynoteiddid = $(this).data('did');
    $.ajax({
      dataType:"json",
      type:"post",
      data:{'mynoteiddid':mynoteiddid},
      "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteiddid="+mynoteiddid,
      success: function(data)
      {
        if(data.resonse)
        {
          swal(data.resonse)
          $("#clientnotelistes li a").each(function(){
            if($(this).attr("data-id")==mynoteiddid){
              $(this).remove();
            }
          });
          $('#myModal_listofnote').modal('hide');
        }
        else if(data.error)
        {

          swal("Sorry something wrong please try agine")
        }
      }
    });





  });



});


 
  $('#monthbtn').click(function(){
    $('#notave').trigger('click');
  });

  $('#clinetdetails').hide();
  $('#clinetdetails_note').hide();
  var daf = Math.floor(Math.random() * 40) + 1;

  $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");




  var EventFilter = {search:'',date:'',user:[],customer:[],status:[]};
  dataTable(EventFilter);

  <?php if(isset($_REQUEST['SearchID'] )){ ?>  //used on todo requested event
    var Search_by_ID = "<?= @$_REQUEST['SearchID'] ?>"; 

    if(Search_by_ID){
      $("#list_view").trigger('click');
      $("#list_search").val(Search_by_ID);
      setTimeout(function(){
        $("#list_search").trigger('keyup');
      },1000);
    }

    <?php  } ?>


        <?php if($_SESSION['usertype']=='Admin'){ ?>

            $("#EventFilterUser").change(function(){
                var id = $(this).val();
                $.ajax({
                    url:"?GET_CLIENT="+id,
                    dataType:'json',
                    success:function(data){

                        var CustomerHtml = "";
                        var Customer = data.Customer;
                        // CustomerHtml+='<option value="">All</option>';
                        for(i in Customer){
                            CustomerHtml+='<option value="'+Customer[i].id+'">'+Customer[i].FirstName+' '+Customer[i].LastName+'</option>';
                        }
                        $("#EventFilterCustomer").html(CustomerHtml);

                    }
                });

            });

            $('#EventFilterUser').select2({matcher: modelMatcher});

            function modelMatcher (params, data) {
              data.parentText = data.parentText || "";

              if ($.trim(params.term) === '') {
                return data;
            }

            if (data.children && data.children.length > 0) {
                var match = $.extend(true, {}, data);

                for (var c = data.children.length - 1; c >= 0; c--) {
                  var child = data.children[c];
                  child.parentText += data.parentText + " " + data.text;

                  var matches = modelMatcher(params, child);

                  if (matches == null) {
                    match.children.splice(c, 1);
                }
            }

            if (match.children.length > 0) {
              return match;
          }

          return modelMatcher(params, match);
      }

      var original = (data.parentText + ' ' + data.text).toUpperCase();
      var term = params.term.toUpperCase();


      if (original.indexOf(term) > -1) {
        return data;
    }

    return null;
}

        <?php } ?>  


  $(document).on('click','#EventDate_clear',function(){

     $("#list_search").val('');
     $("#EventDateRange").val('').change();
     $("#EventFilterUser").val('').change();
     $("#EventFilterCustomer").val('').change();
     $("#EventFilterStatus").val('').change();
    EventFilter = {search:'',date:'',user:[],customer:[],status:[]}
    dataTable(EventFilter);
  });

   $(document).on('click','#EventDate_Submit',function(){
    dataTable(EventFilter);
  });

 $(document).on('keyup','#list_search',function(){
    EventFilter.search = $(this).val();
    dataTable(EventFilter);
  });


    $(document).on('change', '#EventDateRange', function(){
      EventFilter.date = $(this).val();
  });


    $(document).on('change', '#EventFilterUser', function(){
      EventFilter.user = $(this).val();
  });


    $(document).on('change', '#EventFilterCustomer', function(){
      EventFilter.customer = $(this).val();
  });


    $(document).on('change', '#EventFilterStatus', function(){
      EventFilter.status = $(this).val();
  });


  $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
      $('#EventDateRange').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      $('#EventDateRange').trigger('change');
    }

    $('#EventDate').daterangepicker({
      startDate: start,
      endDate: end,
      maxDate: new Date(),
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Year to Date': [moment().subtract(1, 'year').startOf('day'), moment()]
      }
    }, cb);
    

  });




  function dataTable(EventFilter){

    EventFilter = JSON.stringify(EventFilter);

    $('#pagination-container').pagination({
      className: 'paginationjs-theme-blue paginationjs-big',
      dataSource: function(done){
        $.ajax({
          url: "<?php echo EXEC; ?>ExecAllEvent?EventFilter="+EventFilter,
          type :'post',
          success:function(data){
            $('#pagination-container').html("");
            data = JSON.parse(data);
            if(data){
              done(data);
            }else{
              $('#myTable').html('<img class="img-fluid m-auto" src="<?= base_url?>/assets/images/empty_client.jpg">');
            }
          }
        });
      },
      callback: function(data, pagination) {
        var html = RenderData(data);
        $('#myTable').html(html);
      }
    });

  }

  function RenderData(data){

    var check=[];
    parent = $("#myTable");
    parent.html("");
    for(var i=0;i<data.length;i++){


          var encodedId = btoa(data[i].clientid);
          var event_encode_id = btoa(data[i].id);

          var EventDate = data[i].EventDate.split(" ");
          var end_date = data[i].end_date.split(" ");


          var date = moment(EventDate[0]).format('MMMM DD,YYYY');
          var day = moment(EventDate[0]).format('dddd');
          var cdata = data[i].calendar;
          var date_check = moment(EventDate[0]).format('YYYYMMDD');

          if(data[i].ProfileImg!=""){
            ProfileImg = '<a class="example-image-link" href="<?php echo $base_url ?>/assets/ProfileImages/'+data[i].ProfileImg+'"" ><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data[i].ProfileImg+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" id="viewInfo"  data-cid="'+encodedId+'" title="View details" /></a>';
          }else{
            ProfileImg = '<a class="example-image-link" href="<?php echo $base_url ?>/assets/images/noimage.png" > <img src="<?php echo $base_url ?>/assets/images/noimage.png" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" id="viewInfo" ata-cid="'+encodedId+'"title="View details" /> </a>';
          }

          var eventstatus = data[i].eventstatus;

          if(eventstatus=="canceled"){
            event_class="event-important";
          }else if(eventstatus=="completed"){
            event_class="event-info";
          }else if(eventstatus=="confirmed"){
            event_class="event-success";
          }else if(eventstatus=="in-progress"){
            event_class="event-warning";
          }else if(eventstatus=="pending"){
            event_class="event-grey";
          }else if(eventstatus=="pending-payment"){
            event_class="event-red";
          }

          

          var Edit_Event = '<span title="Edit appointment" class="evnetlistdid btn btn-success btn-sm fa fa-edit" data-clientname="'+cdata["clientname"]+'" data-username="'+cdata["username"]+'" data-title="'+cdata["title"]+'" data-startime="'+cdata["stime"]+'" data-full_date="'+cdata["full_date"]+'" data-estatus="event-success" data-eventurl="'+cdata["url"]+'" data-viewclient="'+cdata["viewcustomerurl"]+'" data-location="'+cdata["evnetloction"]+'" data-deletevent="'+cdata["Deleteurl"]+'" data-eventadders="'+cdata["eventadders"]+'" data-onlyeventadders="'+cdata["onlyeventadders"]+'" data-myeventid="'+cdata["id"]+'" data-chekout="'+cdata["chekouturl"]+'" data-sercivename="'+cdata["ServiceName"]+'" data-serviceprovider="'+cdata["ServiceProvider"]+'" data-customerimg="'+cdata["customerimg"]+'" data-providerimg="'+cdata["providerimg"]+'" data-clientID="'+cdata["clientID"]+'" data-stime="'+cdata["stime"]+'"></span>';


          if(check.indexOf(date_check)<0){
            check.push(date_check);
            parent.append('<div class="list" id="'+date_check+'"> <div class="row list_header"><span class="list_date">'+date+'</span><span class="list_day">'+day+'</span> </div> <div class="row list_body"> <div class="col-md-2 client_event_time">'+EventDate[1]+' - '+end_date[1]+'</div> <div class="col-md-2 client_event_title text-overflow" title="'+data[i].title+' ('+data[i].username+' )'+'" >'+data[i].title+' <br>('+data[i].username+' )'+'</div><div class="col-md-2 client_profile" data-cid='+data[i].clientid+'>'+ProfileImg+'<a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " > <span style="text-transform: capitalize;" >'+data[i].client_firstname+' '+ data[i].client_Lastname +'</span> </a> </div> <div class="col-md-3 client_contact"><span class="client_contact_phone">'+data[i].client_phone+'</span> <br> <span class="client_contact_email">'+data[i].client_email+'</span> </div> <div class="col-md-1 client_status"><div title="'+eventstatus+'"class="client_status_dot '+event_class+'"></div></div> <div class="col-md-2">'+Edit_Event+'<button class="btn btn-danger btn-sm" title="Delete" id="deleteButton" data-id="'+event_encode_id+'"><span class="fa fa-trash"><span></button></div></div></div>');


          }else{
            $("#"+date_check).append('<div class="row list_body"> <div class="col-md-2 client_event_time">'+EventDate[1]+' - '+end_date[1]+'</div> <div class="col-md-2 client_event_title text-overflow"  title="'+data[i].title+' ('+data[i].username+' )'+'" >'+data[i].title+' <br>('+data[i].username+' )'+'</div><div class="col-md-2 client_profile" >'+ProfileImg+'<a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " > <span style="text-transform: capitalize;" >'+data[i].client_firstname+' '+ data[i].client_Lastname +'</span> </a> </div> <div class="col-md-3 client_contact"> <i class="fa fa-phone" aria-hidden="true"></i>: <span class="client_contact_phone">'+data[i].client_phone+'</span> <br> <i class="fa fa-envelope" aria-hidden="true"></i>: <span class="client_contact_email">'+data[i].client_email+'</span> </div> <div class="col-md-1 client_status"><div title="'+eventstatus+'"class="client_status_dot '+event_class+'"></div></div> <div class="col-md-2">'+Edit_Event+'<button class="btn btn-danger btn-sm" title="Delete" id="deleteButton" data-id="'+event_encode_id+'"><span class="fa fa-trash"><span></button></div></div>');
          }
      }
  }


  $(document).on('click','#deleteButton',function(e){
    e.preventDefault();
    $(".Loader").show();
    var dlink = $(this).attr('data-id');

    swal({
      title: "Temporary Delete?",
      text: "Once deleted, it will move to Archive list!",
      icon: "warning",
      buttons: true,
    }).then((willDelete)=>{   
      if (willDelete){
        $.ajax({
          dataType:"json",
          type:"post",
          data:{'dlink':dlink},
          url:'<?php echo EXEC; ?>/Exec_Edit_Event?DeleteEvent',
          success: function(data)
          {
            if(data.response){
              $(".Loader").hide();
              swal(data.response)
              $("#list_view").trigger('click');
            }
            else if(data.error){

              $(".Loader").hide();
              swal('Something is wrong please try agine')
              $("#list_view").trigger('click');  
              
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

  <?php if(isset($_POST['OpenEditEvent'])){ ?>

    setTimeout(function(){$(".EditButton").trigger('click');},1000);
    
    
  <?php } ?>

//   $(document).on('click','#EditButton,.EditButton',function(event){

//    event.preventDefault();
//    $('#wdateshidden').val('');
//    $('#repeatdiv').hide();
//    $('#offf').trigger('click');
//    $('#eventdetailmodel').modal('hide');
//    $('.id').val('')
//    $(".Loader").show();

//    $('#clinetdetails').show();

//    $('#clinetdetails_note').show();

//    <?php if(isset($_POST['OpenEditEvent'])){ ?>
//      var elink = '<?= $_POST['OpenEditEvent']; ?>';
//    <?php }else{ ?>
//      var elink = $(this).attr('data-id');
//    <?php } ?>


//    $.ajax({
//      dataType:"json",
//      type:"post",
//      data:{'elink':elink},
//      url:'?action=deletefile',
//      success: function(data)
//      {

//        if(data.resonse)
//        { 
//          if(data.resonse.ProfileImg)
//          {
//            $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
//          }
//          else
//          {
//            $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
//          }
//          var serpro = data.resonse.ServiceProvider;

//          $.ajax({
//            url: '?action=deletefile',
//            type: 'POST',
//            dataType: 'json',
//            data: {'serpro': serpro},
//            success: function(serpro){
//              $('#myModal_exit2').find('h4').text('Book Appointment with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
//                                //$('#servicewith').text('Service with '+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
//                                $('#editspan').show();
//                                $('#editspan').html('<b>Booked With:</b>'+ serpro.resonse.firstname + ' ' + serpro.resonse.lastname);
//                            }
//                        });

//          $('#editcustomer').val(data.resonse.cid);
//          myeventedit(data.resonse.cid);

//                             //console.log("------------------"  + data.resonse.cid);

//                             $("#clinetdetails_note_cid").attr("value",data.resonse.cid);
//                             $("#noteRelated").attr("value",data.resonse.cid);

//                             $('.id').val(data.resonse.id);
//                             $('#evnet_Location_radio').val(data.resonse.Location_radio);
//                             $('#UserID').val(data.resonse.UserID);
//                             $("#ServiceName").val(data.resonse.ServiceName);

//                             $('#newlistofcatagory').val(data.resonse.ServiceName).trigger('change');
//                             $("#title").val(data.resonse.title);
//                             $("#cid").val(data.resonse.cid);
//                             $('#FirstName').val(data.resonse.FirstName);
//                             $('#LastName').val(data.resonse.LastName);
//                             $('#Phone').val(data.resonse.Phone);
//                             $('#Email').val(data.resonse.Email);
//                             $('#Address').val(data.resonse.Address);
//                             $('#Zip').val(data.resonse.Zip);
//                             $('#City').val(data.resonse.City);
//                             $('#State').val(data.resonse.State);
//                             $('#newcountry').val(data.resonse.Country); 
//                             $('#CostOfService').val(data.resonse.CostOfService);

//                             $('#newname').text(data.resonse.FirstName + ' ' + data.resonse.LastName);
//                             $('#newphone').text(data.resonse.Phone);
//                             $('#newemail').text(data.resonse.Email);

//                             var arr = data.resonse.EventDate.split(' ');
//                             arr[0]= moment(arr[0]).format('MM-DD-YYYY');
//                             $('#eventstardate').val(arr[0]);
//                             $('#eventstartime').val(arr[1]);

//                             var arr2 = data.resonse.end_date.split(' ');
//                             arr2[0]= moment(arr2[0]).format('MM-DD-YYYY');
//                             $('#eventenddate').val(arr2[0]);
//                             $('#eventendtime').val(arr2[1]);

//                             $('#eventstatus').val(data.resonse.eventstatus);
//                             $('#ServiceProvider').val(data.resonse.ServiceProvider);
//                             $('#editServiceProvider').val(data.resonse.ServiceProvider);
//                           // $('#EmailInstruction').val(data.resonse.EmailInstruction);
//                           $('#EmailInstruction').data("wysihtml5").editor.setValue(data.resonse.EmailInstruction);

//                           if(data.resonse.Location_radio == 'Customer Location')
//                           {

//                            $(".locone2").prop("checked", true);
//                            $(".locone").prop("checked", false);
//                           }
//                           else
//                           {

//                            $(".locone2").prop("checked", false);
//                            $(".locone").prop("checked", true);

//                           }

//                           var curntuser='<?php echo $_SESSION['UserID']; ?>';
//                           $.ajax({
//                            dataType:"json",
//                            type:"post",
//                            data: {'curntuser':curntuser},
//                            url:'?action=editfile',
//                            success: function(data2)
//                            {
//                              if(data2)
//                              {       
//                                $('#newlistofclient').html("");
//                                        //$('#newlistofclient').append('<option value="0">Select your Client</option>'); 
//                                        $.each(data2.resonse, function( index, value ) {
//                                          var sel ='';
//                                          if(data.resonse.cid == value.id)
//                                          {
//                                            sel = 'selected="selected"';
//                                          }
//                                          $('#newlistofclient').append('<option '+ sel +' value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
//                                        });
//                                        $(".Loader").hide();
//                                    }
//                                    else if(data2.error)
//                                    {
//                                      $("#error").show();
//                                      $('#errormsg').html('<span>'+data2.error+'</span>');
//                                      $(".Loader").hide();
//                                    } 
//                                }
//                            }); 

//                           $(".Loader").hide();
//                           // getsponserid(serpro)
//                           $('#myModal_exit2').modal('toggle');
//                       }
//                       else if(data.error)
//                       {
//                        $(".Loader").hide();
//                        swal('Something is wrong please try again');
//                       }else{
//                        $(".Loader").hide();
//                        console.log(data.error);
//                        swal('No such data found');
//                       }
//                   }
//               });
// });

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
     //console.log(data.resonse);

     var i =0;
     if(data.resonse)
     {

      $(".Loader").hide();

     }

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


// $(document).on('click','#addmoreservie',function(e){
// $('#myModal_addmoreservice').show()
//   });    



});

</script>
<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js//underscore-min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/events.js"></script>

<script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>

<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
<script>
  $(document).ready(function() {
    $('#EmailInstruction').wysihtml5();
    $('#noteDetail').wysihtml5();

    $('.sada').hide()

    $("#statauslist").click(function(){
      $('.sada').toggle()  
    });
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

 <script>
  jQuery(document).ready(function() {

        // Radio Checked 

        // $('input[type=radio][name="Location_radio"]').on('change',function () {
        //   //alert($("input[name='Location_radio']:checked").val());
        //   $('#evnet_Location_radio').val($("input[name='Location_radio']:checked").val());
        //   $('#newlistofclient').trigger('change');


      });

        // End Radio checked

        // Switchery
        $(".listofclientdiv").hide();
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
          new Switchery($(this)[0], $(this).data());
        });
        // For select 2
        $(".select2").select2({
          allowClear: true
        });
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

      $(".dropify-clear").click(function(e){
        e.preventDefault();
        $(".dropify-preview").hide();

        var data1 = $("#id").val();

        data = data1 + "&action5=deleteimage";
        jQuery.ajax({
          dataType:"json",
          url:'<?php echo EXEC; ?>exec-edit-profile?action5',
          type:"post",
          data:{"cimyData2":data1},
          success: function(data) 
          {
            if(data.resonse)
            {
              swal("Client Image is deleted.");
              $("#oldimage").val('');
            }
            else if(data.error)
            {
              alert('somening worng')
            }
          }
        });
      });

  });
</script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/base64.js" ></script>
<script type="text/javascript">


  $('#listofcatagory3').on('change',function(e){
    e.preventDefault();
    $('#myModal_exit2').find('h4').text('Book Appointment with '+ $("#listofcatagory3 option:selected").html());
    $('#newlistofclient').trigger('change');
  });


  

  $('input[type="radio"]').click(function(){
    if($(this).prop("checked") == true){
      var cjecl=$(this).val();            
    }

  });

 // New Client Button Click 
 $('.myModal_new').on('click',function(event){
  event.preventDefault();
  var listofcatagory=$('#listofcatagory').val();
  $('#listofcatagory_error').text("");
  if(listofcatagory=='' || $('input[name=Location]:checked').length<=0)
  {
    $('#listofcatagory_error').text("Please select service and also location");
  }
  else if(listofcatagory!='' || $('input[name=Location]:checked').length>0)
  {
    console.log('test');
    $('#myModal').modal('hide');
    $('#myModal_new2').modal('toggle');
    $('#listofcatagory_error').hide();
  }
 });
// End NewClient Button click

$('.back').on('click',function(){ 

  $('#myModal_new2').modal('hide');
  $('#myModal').modal('toggle');

});

// $('#exit-client').on('click',function(){
//       // $(".Loader").show();
//       curntuser='<?php echo $_SESSION['UserID']; ?>';
//       $.ajax({
//        dataType:"json",
//        type:"post",
//        data: {'curntuser':curntuser},
//        url:'?action=editfile',
//        success: function(data)
//        {
//          if(data)
//          {       

//            $('#newlistofclient').html("");
//            $('#newlistofclient').append('<option value disabled="true" selected="selected">Select your Client</option>'); 
//            $.each(data.resonse, function( index, value ) {
//                   // $('#listofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
//                   $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
//               });
//            $(".Loader").hide();
//            $('#myModal').modal('hide');
//            $('#myModal_exit2').modal('toggle');
//          }
//          else if(data.error)
//          {
//            $("#error").show();
//            $('#errormsg').html('<span>'+data.error+'</span>');
//            $(".Loader").hide();
//          } 
//        }
//       })
//   });

$(document).on("click","#appointmentwithnote1",function(){
  $("#NewEvent_todo")[0].reset();
  $("#NewEvent_todo #id").val("new");
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
        {    

          $(".Loader").hide();   
          $(".hidddeforfirst").show();
          $(".exit-client-image").show();
          $('#FirstName').val(data.resonse.FirstName);
          $('#LastName').val(data.resonse.LastName);
          $('#Phone').val(data.resonse.Phone);
          $('#Email').val(data.resonse.email);
          $('#clinetdetails').show();
          $('#clinetdetails_note').show();
          $('#newname').text(data.resonse.FirstName + ' ' + data.resonse.LastName);
          $('#newphone').text(data.resonse.Phone);
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
          $('#editcustomer').val(data.resonse.id);

             //alert(data);
             //console.log("ediiiiii" + data.resonse.id);
             $("#clinetdetails_note_cid").attr("value",data.resonse.id)
             $("#noteRelated").attr("value",data.resonse.id);
             if(data.resonse.ProfileImg)
             {
              $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
             }
             else
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

// myeventedit(ClientsName)
// function myeventedit(ClientsName)
// {

//  $('ul#clientnotelistes').html("");
//  $('#clinetdetails').hide();
//  $('#clinetdetails_note').hide();
//  var ClientsName=ClientsName;

//  if(ClientsName == null){
//    return;
//  }
//  $(".Loader").show();   

//  if($('#NewEvent input[name="id"]').val() == 'new'){
//    var radioValue = $("input[name='Location_radio']:checked").val();
//  }else{
//    var radioValue = $('#evnet_Location_radio').val();
//  }

//  $.ajax({

//    dataType:"json",
//    type:"post",
//    data: {'ClientsName':ClientsName},
//    url:'?action=editfile',
//    success: function(data)
//    {
//      if(data)
//      { 
//        $(".Loader").hide();   
//        $('#FirstName').val(data.resonse[0].FirstName);
//        $('#LastName').val(data.resonse[0].LastName);
//        $('#Phone').val(data.resonse[0].Phone);
//        $('#Email').val(data.resonse[0].email);
//        $("#newcountry").val(data.resonse[0].Country);
//        $('#clinetdetails').show();
//        $('#clinetdetails_note').show();
//        $('#newname').text(data.resonse[0].FirstName + ' ' + data.resonse[0].LastName);
//        $('#newphone').text(data.resonse[0].Phone);
//        $('#newemail').text(data.resonse[0].email);

//        $('.SelectPackageliv').html('')
//        $.each(data.resonse, function( index, value ) 
//        {
//          if(value.Name!=null)
//          {
//            $('.SelectPackageliv').append('<button data-id="'+ value.cpackagid +'"  class="btn btn-info m-r-10 SelectPackage" value="'+value.Noofvisit+'" type="button" id="SelectPackage">'+value.Name +'</button>');  
//          }
//          else
//          {
//            $('.SelectPackage_not').show(); 
//            $('.SelectPackageliv').append('<span class=".SelectPackage_not">No package selected</span>')

//          }
//        });


//        if(radioValue=='Customer Location')
//        { 

//          var useradd = $('#listofcatagory3').val(); 

//          if(useradd == null)
//          {
//            var useradd= '<?php echo $_SESSION['UserID']; ?>';
//          }

//          $("#ServiceProvider").val(useradd);  
//          $('#AddressE').val(data.resonse[0].Address);
//          $('#Zip').val(data.resonse[0].Zip);
//          $('#City').val(data.resonse[0].City);
//          $('#State').val(data.resonse[0].State);
//          $('#newcountry').val(data.resonse[0].Country);

//          var full_address = data.resonse[0].Address+", "+data.resonse[0].City+", "+data.resonse[0].State+", "+data.resonse[0].Zip+"  "+data.resonse[0].Country;
//          $("#event_map").attr('src','https://maps.google.com/?q='+full_address+'&output=embed');
      
//        }
//        else
//        {

//          if($('#NewEvent input[name="id"]').val() == 'new'){
//            var useradd = $('#listofcatagory3').val(); 
//            if(useradd == null)
//            {
//              var useradd = '<?php echo $_SESSION['UserID']; ?>';
//            }

//            $.ajax({
//              dataType:"json",
//              type:"post",
//              data: {'useradd':useradd},
//              url:'?action=editfile',
//              success: function(data3)
//              {
//                if(data3)
//                { 
//                  $("#ServiceProvider").val(useradd);
//                  $('#AddressE').val(data3.resonse.primaryaddress);
//                  $('#Zip').val(data3.resonse.zipcode);
//                  $('#City').val(data3.resonse.city);
//                  $('#State').val(data3.resonse.state);
//                  $('#newcountry').val(data3.resonse.country);
//                  $(".Loader").hide();

//                  var full_address = data3.resonse.primaryaddress+", "+data3.resonse.city+", "+data3.resonse.state+", "+data3.resonse.zipcode+"  "+data3.resonse.country;
//                  $("#event_map").attr('src','https://maps.google.com/?q='+full_address+'&output=embed');
//                  console.log("else"+full_address);
//                }
//              }
//            });

//          }

//        }

//        $('#cid').val(data.resonse[0].cid);

//        $('#editcustomer').val(data.resonse[0].cid);
//            //console.log(data.resonse);
//            //console.log("nnnnnnnnnnn " + data.resonse[0].cid);
//            $("#clinetdetails_note_cid").attr("value",data.resonse[0].cid)
//            $("#noteRelated").attr("value",data.resonse[0].cid);

//            if(data.resonse[0].ProfileImg)
//            {

//              $("#clientimage").attr("src","<?php echo  base_url.'/assets/ProfileImages'?>/"+data.resonse[0].ProfileImg);
//            }
//            else
//            {
//              $("#clientimage").attr("src","<?php echo  base_url.'/assets/images/noimage.png'; ?>");   

//            }
//        }

//        if(data.resonse_note != [])
//        {

//          $.each( data.resonse_note, function( key, value ) {
//            $('.clientnotelistes').show();
//            $('ul#clientnotelistes').append("<li><a href='#' class='edit_note' id='edit_note' data-id="+btoa(value.id)+">"+value.noteTitle+"</a></li>")
//          });
//        }
//        else if(data.error)
//        {
//          swal("Oops...", "Something went wrong!", "error");
//        }

//    }
// })

// }

// $('#newlistofclient').on('change',function(){

//  var ClientsName=$(this).val();


//  myeventedit(ClientsName)

// });
$(document).on("click","#SelectPackage",function() {
  $("#listofavliaasfpackag2").html("");
  if(this.value == 0)
  {
    $("#remservice").html("NA");
    $("#remservice").attr("data-id","");

    $("#selser").fadeOut();
  }
  else
  {
    $("#remservice").html(this.value);
    $("#remservice").attr("data-id",$(this).attr("data-id"));
    $("#selser").fadeIn();
  }

  var pakcagidc2 = $(this).attr("data-id")
  $.ajax({
    url:'?action=editfile',
    type: "POST",
    data:{"pakcagidc2": pakcagidc2,},
    dataType:"json",
    success:function(data)
    {
      $('.listofavliaasfpackag2').append('<option value="">Selectect service</option>'); 
      $.each(data.resonse2, function( index, value ) 
      {

        $('.listofavliaasfpackag2').append('<option value='+value.id+'>'+value.ServiceName+'</option>');

      });

    }
  });


});

function cleardiv()
{
  $("#remservice").html("");
  $("#selser").hide();
  $("#listofavliaasfpackag2").html();

}
function redi()
{

    /*var client = $("#newlistofclient").val();
    var package = $('#remservice').attr('data-id');
    var service = $("#listofavliaasfpackag2").val();
    var sname = $('#select2-listofavliaasfpackag2-container').text();
    var remain = $("#remservice").html();*/
    var obj = { "client" : $("#newlistofclient").val() , "package" : $('#remservice').attr('data-id') , "service" : $("#listofavliaasfpackag2").val() , "sname" : $('#select2-listofavliaasfpackag2-container').text() , "remain" : $("#remservice").html()-1};
    var json=JSON.stringify(obj);
    var url = $.base64.encode(json);
    window.location.href = "https://mysunless.com/crm/Order?data="+url;
    // window.setInterval(function() {
    // }, 500);
}
function addbtn()
{
  if($('#select2-listofavliaasfpackag2-container').text() != "Choose service")
  {
    $("#addser").html('<button class="btn btn-info"  onclick="redi();"  >Checkout</button>');

      //$("#addser").html('<a class="btn btn-info"  href="javascript:redi();"  >Checkout</a>');
  }
}
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#selser").hide();
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
      // $('#Phone').keyup(function(e){
      //  var ph = this.value.replace(/\D/g,'').substring(0,10);
      //     // Backspace and Delete keys
      //     var deleteKey = (e.keyCode == 8 || e.keyCode == 46);
      //     var len = ph.length;
      //     if(len==0){
      //      ph=ph;
      //     }else if(len<3){
      //      ph='('+ph;
      //     }else if(len==3){
      //      ph = '('+ph + (deleteKey ? '' : ') ');
      //     }else if(len<6){
      //      ph='('+ph.substring(0,3)+') '+ph.substring(3,6);
      //     }else if(len==6){
      //      ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+ (deleteKey ? '' : '-');
      //     }else{
      //      ph='('+ph.substring(0,3)+') '+ph.substring(3,6)+'-'+ph.substring(6,10);
      //     }
      //     this.value = ph;
      // });
      // end formatting phone number

     

});
</script>

<script>
  $(document).ready(function(){


var windowSize = $(window).width(); // Could've done $(this).width()
if(windowSize<768)
{
  $('.daydash').trigger('click');
}

        // formatting phone number
        $('#phonenumber').keyup(function(e){
          var ph = this.value.replace(/\D/g,'').substring(0,10);
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
      $(document).on('click','.myModal_new',function(){
        $("#NewClient")[0].reset();
        $("#administrative_area_level_1").val("");
        $('.dropify-render').text('')
        $('#clid').val('');
        $('.dropify-filename-inner').text('')
        $("#resonse").hide();
        $("#error").hide();
        $("#csrf_error").hide();
        $("#add-client").text("Add Client");
      });

      $(document).on('click','#editcustomer',function(){
        $('.dropify-render').text('')
        $('#clid').val('');
        $('.dropify-filename-inner').text('')
        $(".Loader").show();
        $("#resonse").hide();
        $("#error").hide();
        $("#csrf_error").hide();
        $("#add-client").text("Update Client");

        event.preventDefault();
        var customersid = $(this).val();


        var customersid2 = customersid;

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
              $('#Address').val(data.resonse.Address);
              $('#example-email').val(data.resonse.email)
              $('#autocomplete').val(data.resonse.Address)
              $('#street_number').val(data.resonse.Address)
              $('#postal_code').val(data.resonse.Zip)
              $('#country').val(data.resonse.Country) 
              $('#administrative_area_level_1').val(data.resonse.State)
              $('#locality').val(data.resonse.City)
              $('#oldimage').val(data.resonse.ProfileImg)
              if(data.resonse.ProfileImg)
              {

                $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/"+data.resonse.ProfileImg+"");
                $('<img src="<?php echo base_url; ?>/assets/ProfileImages/'+data.resonse.ProfileImg+'" id="pImage">').appendTo(".dropify-render");
                $('.dropify-filename-inner').text(data.resonse.ProfileImg)

              }
              else
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

//       $("#NewClient").validate({
//        rules: {                
//          FirstName: "required",
//          LastName: "required",
//          Phone: {required: true, },
//          email: "required",
//          Address: "required",
//          Zip: "required",
//          City: "required",
//          State: "required",
//          Country:"required",
//                 // Solution: "required",
//                 // PrivateNotes: "required",
//                 // SelectPackage: "required",
//                //employeeSold: "required",
//                // sd: {required: true,},
//                // ed: {required: true,},


//            },
//            messages: {             
//              FirstName:  "Please enter first name",
//              LastName:  "Please enter last name",
//              Phone: {required : "Please enter phone number",} ,
//              email:  "Please enter an email",
//              Address:  "Please enter address",
//              Zip:  "Please enter zipcode",
//              City:  "Please enter city",
//              State:  "Please enter state",
//                 // Solution:"Please Enter Solution Strength",
//                 // PrivateNotes:"Please Enter Private Notes",
//                 // Country:"Please Select Country",
//                 // SelectPackage :"Please Select Package",
//                //employeeSold: "Please Select Employee Who Sold",
//                // sd:"Please Select Starting Date &nbsp&nbsp&nbsp&nbsp",
//                // ed:"&nbsp&nbsp&nbsp&nbsp Please Select Ending Date",

//            },submitHandler: function() {
//              $(".Loader").show();
//              var form = $('#NewClient')[0];
//              var data = new FormData(form);
//              jQuery.ajax({

//                dataType:"json",
//                type:"post",
//                data:data,
//                     contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
//                     processData: false,
//                     url:'<?php echo EXEC; ?>Exec_Edit_Client',
//                     success: function(data)
//                     {
//                       //console.log(data.resonse);
//                       if(data.resonse)
//                       {

//                        $("#resonse").show();

//                        $('#resonsemsg').html('<span>'+data.resonse+'</span>');

//                        $( '#NewClient' ).each(function(){
//                          this.reset();
//                        });

//                        var radioValue = $("input[name='Location_radio']:checked").val();
//                        $("#evnet_Location_radio").val(radioValue)
//                        if(radioValue=='Salon Location')
//                        {
//                          $(".ServiceLocation").hide();
//                        }

//                        if($('#id').val() == 'new')
//                        {
//                          $('#listofclient').append('<option value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
//                          $('#listofclient').select2(); 
//                          $('#newlistofclient').append('<option selected="selected" value="'+data.mydata.id+'">'+data.mydata.FirstName+' '+data.mydata.LastName+'</option>').trigger('change');
//                        }

//                        $('#FirstName').val(data.mydata.FirstName);
//                        $('#LastName').val(data.mydata.LastName);
//                       $('#newlistofclient option:selected').text(data.mydata.FirstName + ' ' + data.mydata.LastName).select2().trigger('change'); // new
//                       $('#newlistofclient').select2(); 
//                       $('#Phone').val(data.mydata.Phone);
//                       $('#Email').val(data.mydata.email);
//                       $('#newemail').text(data.mydata.email);
//                       $('#newname').text(data.mydata.FirstName + ' ' + data.mydata.LastName);
//                       $('#newphone').text(data.mydata.Phone);
//                       $('#cid').val(data.mydata.id);

//                       if(data.mydata.ProfileImg)
//                       {
//                        $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.mydata.ProfileImg);
//                       }
//                       else if(data.mydata.ProfileImg == '')
//                       {
//                        $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
//                       }else{
//                        $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   


//                       }

//                       if(radioValue=='Customer Location')
//                       {     
//                        var useradd = $('#listofcatagory3').val(); 
//                        if(useradd==0)
//                        {
//                          var useradd= '<?php echo $_SESSION['UserID']; ?>';
//                        }
//                        $("#ServiceProvider").val(useradd);
//                        $('#Address').val(data.mydata.Address);
//                        $('#Zip').val(data.mydata.Zip);
//                        $('#City').val(data.mydata.City);
//                        $('#State').val(data.mydata.State);
//                         // $('#country').val(data.resonse.Country).attr("selected", "selected");
//                         $('#newcountry').val(data.resonse.Country);
//                     }
//                     else
//                     {

//                      var useradd = $('#listofcatagory3').val(); 
//                      if(useradd==0)
//                      {
//                        var useradd= '<?php echo $_SESSION['UserID']; ?>';
//                      }
//                      $.ajax({

//                        dataType:"json",
//                        type:"post",
//                        data: {'useradd':useradd},
//                        url:'?action=editfile',
//                        success: function(data3)
//                        {
//                          if(data3)
//                          { 

//                            $("#ServiceProvider").val(useradd);
//                            $('#Address').val(data3.resonse.primaryaddress);
//                            $('#Zip').val(data3.resonse.zipcode);
//                            $('#City').val(data3.resonse.city);
//                            $('#State').val(data3.resonse.state);
//                                                 // alert(data3.resonse.country);
//                                                // $('#country').val(data3.resonse.Country).attr("selected", "selected");
//                                                $('#newcountry').val(data3.resonse.country);
                                               
//                                                $(".Loader").hide();
                                               
//                                            }

//                                        }

//                                    });
//                     }

//                     $(".hidddeforfirst").show();
//                     $(".exit-client-image").show();
//                     $(".Loader").hide();
//                     $('#myModal_new2').modal('toggle');  

//                     if($('#id').val() == 'new')
//                     {
//                           // $('#exit-client').trigger('click');
//                           // $('#listofclient').val(data.mydata.id).trigger('change');
//                           // $('#listofclient').select2(); 
//                       }

//                       else
//                       {

//                       }

//                     // location.reload();
//                 }
//                 else if(data.error)
//                 {
//                  $("#error").show();

//                  $('#errormsg').html('<span>'+data.error+'</span>');

//                  $(".Loader").hide();
//                     // $('#myModal').modal('toggle');
//                 // alert('<li>'+data.error+'</li>');
//             }

//             else if(data.csrf_error)
//             {

//              $("#csrf_error").show();
//              $('#csrf_errormsg').html('<span>'+data.csrf_error+'</span>');
//              $(".Loader").hide();
//              setTimeout(function () { window.location.reload() }, 2000)
//             }

//         }
//     });

// } 

// });


$("#NewEvent_todo").validate({
  rules: {
    noteTitle: {
      required: true,}
      ,
      noteDetail: {
        required: true,}
        ,
        "noteRelated[]": {
          required: true,}
          ,
        }
        ,
        messages: {
          noteTitle: {
            required: "Please enter note title"}
            ,
            noteDetail: {
              required: "Please enter note detail"}
              ,
              "noteRelated[]": {
                required: "Please relate the note to a contact"}
                ,
              }
              ,
              ignore: ":hidden:not(textarea)",
              errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "noteDetail"  || element.attr( "name" ) === "noteRelated[]") {
                  element.parent().append( label );
                }
                else {
                  label.insertAfter( element );
                }
              }
              ,
              submitHandler: function() {
                $(".Loader").show();
                var data = $("#NewEvent_todo").serialize();
                $data= data + "&Action=note";
                jQuery.ajax({
                  dataType:"json",
                  type:"post",
                  data:data,
                  url:'<?php echo EXEC; ?>Exec_Edit_Note',
                  success: function(data)
                  {
                    if(data.resonse)
                    {
                      $("#resonse_note").show();
                      $('#resonsemsg_note').html('<span>'+data.resonse+'</span>');
                      $( '#NewNote' ).each(function(){

                        this.reset();

                        $("#clientnotelistes").load(location.href + "#clientnotelistes");

                      });

                      if($("#clientnotelistes").find("a[data-id='"+btoa(data.mydata.id)+"']").length){

                        $($("#clientnotelistes").find("a[data-id='"+btoa(data.mydata.id)+"']")).text(data.mydata.noteTitle);
                      }else{
                        $('.clientnotelistes').show();

                        $('ul#clientnotelistes').append("<li><a href='#' class='edit_note' id='edit_note' data-id="+btoa(data.mydata.id)+">"+data.mydata.noteTitle+"</a></li>");
                      }



                      $(".Loader").hide();
                      $('#appointmentwithnote').modal('hide');
                      $('#myModal_listofnote').modal('hide');


                    }
                    else if(data.error)
                    {
                      $("#error_note").show();
                      $('#errormsg_note').html('<span>'+data.error+'</span>');
                      $(".Loader").hide();
                                                    // alert('<li>'+data.error+'</li>');
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

  function myFunction3() {
    document.getElementById("myDropdown_cal3").classList.toggle("show_cal");
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


    $('#newlistofcatagory').select2({
                    //alert("asdasd");

                          // "language": {
                          //     "noResults": function(){
                          //         return "No Results Found <a href='#' class='btn btn-danger'>Use it anyway</a>";
                          //     }
                          // },
                          //   escapeMarkup: function (markup) {
                          //       return markup;
                          //   }
                      });

    $(document).on('click','.ClientLimit',function(){

      swal("Your Client Limit is over!!", "For Upgrade your limit contact to Admin", "warning");
    });

  });
</script>

<script type='text/javascript'src='<?php echo base_url ?>/assets/js/timepicki.js'></script>
<script>
//  $('#datepairExample .time').timepicker({
//   'showDuration': true,
//   'timeFormat': 'g:ia'
// });

// $('#eventstartime').timepicki();

$('#eventstardate').datepicker({
  format: "mm-dd-yyyy",
  'autoclose': true,
  startDate: '-0d',
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

      var today_date = today_date.split('-');
      var today_date = today_date[1] + '-' + today_date[2] + '-' + today_date[0].slice();


      $(".today_date").text(today_date);
      $(".tohsedata").attr('data-current-cal-date',today_date);

      $('#eventstardate').val(today_date); 
      $('#eventenddate').val(today_date);

        //$(this).trigger('dblclick');  

    });  
    $(document).on('click', '#addnewappointment', function(e){
      $('#editspan').hide();
      $('#repeatdiv').show();

       //reset appointment form
       $('#myModal_exit2').find('h4').text('Book Appointment');
       $("#NewEvent")[0].reset();
       $("#select2-listofcatagory3-container").text("");
       $("#select2-newlistofcatagory-container").text("");
       $("#offrepeat").trigger('click');
       $("#clinetdetails").hide();
       $("#clinetdetails_note").hide();

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
              $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
            });
            $(".Loader").hide();
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

    $(document).on('click', '.cal-day-hour-part', function(e){
      $('#editspan').hide();
      $('#repeatdiv').show();
      var today_time = $(this).find('.span1').find('b').text();
      var today_time = today_time.toLowerCase()

      $('#eventstartime').val(today_time);
      $('#NewEvent input[name="id"]').val('new');
      $('#CostOfService').val('');
      $('#EmailInstruction').val('');
      $('#eventstatus option:nth-child(1)').attr('selected','selected');
      $('#myModal_exit2').modal('show');
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
              $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
            });
            $(".Loader").hide();
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

    //  $('#offrepeat').click(function() {
    //    $("#eventstardate").trigger('change');
    // });

    // $('#daily').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#weekly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#monthly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#yearly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

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
    emptydata();       
  });

    // $("#eventstartime").change(function(){
    //   var service_star_time = $(this).val(); 
    //   var serivename=  $("#ServiceName").val();

    //         //$(".Loader").show();
    //         $.ajax({

    //          dataType:"json",
    //          type:"post",
    //          data: {'service_star_time':service_star_time,'serivename':serivename},
    //          url:'?action=service_star_time',
    //          success: function(data)
    //          {
    //           if(data)
    //           {

    //                   //$(".Loader").hide();
    //                   //location.reload();
    //                   $("#eventendtime").val(data.resonse);
    //                 }
    //                 else if(data.error)
    //                 {
    //                   alert('ok');  
    //                 }

    //               }
    //             })

    //       });


    $(document).on("change","#eventstartime",function(){ 
      setTimeout(function() {
        var service_star_time = $("#eventstartime").val();
        var serivename=  $("#ServiceName").val();
        EventStartFunction(service_star_time,serivename);
      },100);

    });

    function EventStartFunction(service_star_time,serivename){
      $.ajax({

        dataType:"json",
        type:"post",
        data: {'service_star_time':service_star_time,'serivename':serivename},
        url:'?action=service_star_time',
        success: function(data)
        {
          if(data)
          {
          //console.log(data.resonse);
          $("#eventendtime").val(data.resonse);
      }
      else if(data.error)
      {
          //alert('ok');
          console.log("error in select end date");  
      }

  }
});
    }

    $(document).on("click","#myapp, #calendar_refresh",function(){
      document.getElementById("myDropdown_cal2").classList.toggle("show_cal");
      $("#caluserlist").hide();
      $("#calserlist").hide();
    });

    $("#byuser").click(function(){
      $("#caluserlist").toggle();
      $("#calserlist").hide();
      $("#listofcalser").val(null).trigger('change');
    });  

    $("#byservice").click(function(){
      $("#calserlist").toggle();
      $("#caluserlist").hide();
      $("#listofcaluser").val(null).trigger('change');
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

      /*$(document).ready(function(){
        var i = 0;
        console.log(i);
        i = parseInt(i)+1;
    });*/
     //counter for page  
    /*$(document).ready(function(){
      var counter = ;  
      var uname = "count";
      document.cookie = uname + "=" + counter + ";path=/";
      var x = document.cookie;
      var res =x.replace(" ","");
      var x1 = res.split(";");
      var final = x1[5].split("=");
      var counte = parseInt(final[1]) + 1;
      counter = counte;
      console.log(counte);
  });*/
  /*var counter=;*/
  $(document).ready(function(){
    var n = localStorage.getItem('pageloadcounter');
    if (n === null) {
      n = 0;
    }
    n++;
    localStorage.setItem("pageloadcounter", n);
      //console.log(n);
      /* I've made some changes into top navigation bar. this issue is only in top navigation bar if user selects side navigation bar from the settings then user will not find this issue in that.Issue is there is "" */
      /*var n = counter;
      if (n === null) {
        n = 0;
      }
      n++;
      var uname = "count";
      document.cookie = uname + "=" + n + ";path=/";
      var x = document.cookie;
      console.log(x);
      counter = n;*/
      /*var counter = parseInt(counter) + 1*/;
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

  function statuschng()
  {
    $("#radio-4").trigger('click')
    $("#radio-4").trigger('click')
  }

  $(document).on('click','.evnetlistdid',function(e){
    e.preventDefault();
    $('.Loader').show();
    $("#eventdetailmodel").modal('show');
    var eventid = $(this).attr('data-myeventid');
    $(".evnetid").text(eventid);

    var customerimg = $(this).attr('data-customerimg');
    $(".event_relation_customer img").attr('src',customerimg);

    var clientID = $(this).attr('data-clientID');
    $(".event_relation_customer img").attr('data-cid',clientID);
    $(".clinetdetails_note_cid").attr('value',atob(clientID));

    var providerimg = $(this).attr('data-providerimg');
    $(".event_relation_service_provider img").attr('src',providerimg);

    var ServiceProvider = $(this).attr("data-serviceprovider");
    $(".event_relation_service_provider img").attr('data-sid',ServiceProvider);

    var customername = $(this).attr("data-clientname");
    $(".evnetcutomername").text(customername);

    var UserName = $(this).attr("data-username");
    $(".evnetemployename").text(UserName);

    var envettitle = $(this).attr("data-title");
    $(".envetsername").text(envettitle);

    var evnetstarttime = $(this).attr("data-full_date");
    $(".evnetstartime").text(evnetstarttime);

    var evneturl = $(this).attr("data-eventurl");
    var evtd = evneturl.split('&');
    var evtid = evtd[1].split('=');
    $(".ViewEvent").attr("data-id",evtid[1])
    $(".EditButton").attr("data-id",evtid[1])


    var customerurl = $(this).attr("data-viewclient");
    var clitd = customerurl.split('&');
    var clitd = clitd[1].split('=');

    $("#noteRelated").attr("value",window.atob(clitd[1]))


    var customerurl = $(this).attr("data-viewclient");
    $("#ViewCustomer").attr("href",customerurl)


    var checkouturl = $(this).attr("data-chekout");
    var servicename = $(this).attr("data-sercivename");
    var stime = $(this).attr("data-stime");
    var ServiceProvider = $(this).attr("data-serviceprovider");

    var clitd2 = checkouturl.split('&');
    var clitd2 = clitd2[1].split('=');
    $("#noteRelated").attr("value",window.atob(clitd2[1]))

    $("#checkout").attr("href",checkouturl+'?&servicename='+servicename+'&stime='+window.btoa(stime)+'&ServiceProvider='+ServiceProvider+'&eventid='+window.btoa(eventid))


    var deleteevent = $(this).attr("data-deletevent");
     // $("#DeleteEvent").attr("href",deleteevent);

     $(document).on('click','#DeleteEvent',function(e){
      $('.Loader').show();
      e.preventDefault();
      swal({
        title: "Temporary Delete?",
        text: "Once deleted, it will move to Archive list!",
        icon: "warning",
        buttons: true,
      }).then((willDelete)=>{   
        if (willDelete){

          $.ajax({
            url:deleteevent,
            success:function(data){
              $('.Loader').hide();
              data = JSON.parse(data);
              if(data.response){
                
                swal("",data.response,"success");
                $("#calendar_refresh").trigger('click');
                if($("#myDropdown_cal2").hasClass("show_cal")){
                  $("#myDropdown_cal2").removeClass("show_cal"); 
                }
                $("#eventdetailmodel").modal('hide');
              }else{
                swal("","Something went wrong!","error");
              }
            }
          });
        }
        else{
          return false ;
        }
      });
     });

       // var editevent = $(this).attr("data-editevent");
       // $("#ViewEvent").attr("href",editevent)

       var eventadders = $(this).attr("data-eventadders");
       $("#eventaddermap").attr("src",eventadders)


       var onlyeventadders = $(this).attr("data-onlyeventadders");
       $("#onlyeventadders").text(onlyeventadders)

       var myeventid = $(this).attr("data-myeventid");

       $("#printappoiment").attr("data-id",myeventid);

       $(".radio-custom").attr("data-eventid",myeventid);  

       var estatus = $(this).attr("data-estatus");

       $('input[name="radio-group"]').each(function(){
        $(this).prop( "checked", false );
       });

       if(estatus=='event-grey')
       {
        $("#radio-1").prop( "checked", true );  
       }
       else if(estatus=='event-success')
       {
        $("#radio-2").prop( "checked", true );  
       }

       else if(estatus=='event-important')
       {
        $("#radio-3").prop( "checked", true );  
       }

       else if(estatus=='event-red')
       {
        $("#radio-4").prop( "checked", true );  
       }
       else if(estatus=='event-warning')
       {
        $("#radio-5").prop( "checked", true );  
       }
       else if(estatus=='event-info')
       {
        $("#radio-6").prop( "checked", true );  
       }


        // $("#onlyeventadders").text(estatus)
        

        var elocation = $(this).attr("data-location");
        $(".App_location").text(elocation);

        if(elocation == 'Salon Location')
        {
          $(".cusomerlocation").hide();
        }else{
          $(".cusomerlocation").show();
        }
        $('.Loader').hide();

    });


$('input[name="radio-group"]').click(function(){
  $(".Loader").show();
  if ($(this).is(':checked'))
  {
    var newstat = $(this).val();
    var myeventid = $(this).attr("data-eventid");

    if(newstat=='canceled')
    {
      $('#myModal_appoimentcenletion').modal('show')
      $(".Loader").hide();
    }
    else
    {
      $.ajax({
        async: true,
        dataType:"json",
        type:"post",
        data: {'newstat':newstat,'myeventid':myeventid},
        url:'?action=newstat',
        success: function(data)
        {
          if(data)
          {

            $(".Loader").hide();
            swal('Appointment status successfully has been change');

            $("#calendar_refresh").trigger("click");

            if($("#myDropdown_cal2").hasClass("show_cal")){
              $("#myDropdown_cal2").removeClass("show_cal"); 
            }

          }
          else if(data.error)
          {
            swal("",data.error,"error");  
          }

        }
      })
    }


  }
  $("#eventdetailmodel").modal('hide');
  $("#myModal_appoimentcenletion").modal('hide');
});

$(document).on('click','#Notifytocustomer',function(e){
  e.preventDefault();  
  $(".Loader").show();
  var commnetonet = $('#appointmentcancelcomment').val()
  var commnettwot =  $('#appointmentcancelcommentforcusteomr').val()  
  var myeventid = $('#radio-3').attr("data-eventid");  
  var newstat2 = $('#radio-3').val();

  if ($('input.event_carryford').is(':checked')) 
  {
    var eventcustohistory ='yes' 
  }
  else
  {
    var eventcustohistory ='no'  
  }


  $.ajax({
    async: true,
    dataType:"json",
    type:"post",
    data: {'commnetonet':commnetonet,'commnettwot':commnettwot,'myeventid':myeventid,'newstat2':newstat2,'eventcustohistory':eventcustohistory},
    url:'?action=newstat',
    success: function(data)
    {
      $(".Loader").hide();
      if(data)
      {
        swal('Appointment status successfully has been change');
        $("#calendar_refresh").trigger("click");
        if($("#myDropdown_cal2").hasClass("show_cal")){
          $("#myDropdown_cal2").removeClass("show_cal"); 
        }

      }
      else if(data.error)
      {
        swal("",data.error,"error");  

      }
      $("#eventdetailmodel").modal('hide');
      $("#myModal_appoimentcenletion").modal('hide');
    }
  })


});



$(document).on('click','#dontNotifytocustomer',function(e){
  e.preventDefault();  
  $(".Loader").show();
  var commnetonet_1 = $('#appointmentcancelcomment').val()
  var commnettwot_1 =  $('#appointmentcancelcommentforcusteomr').val()  
  var myeventid_1 = $('#radio-3').attr("data-eventid");  
  var newstat2_1 = $('#radio-3').val();

  if ($('input.event_carryford').is(':checked')) 
  {
    var eventcustohistory_1 ='yes' 
  }
  else
  {
    var eventcustohistory_1 ='no'  
  }


  $.ajax({
    async: true,
    dataType:"json",
    type:"post",
    data: {'commnetonet_1':commnetonet_1,'commnettwot_1':commnettwot_1,'myeventid_1':myeventid_1,'newstat2_1':newstat2_1,'eventcustohistory_1':eventcustohistory_1},
    url:'?action=newstat',
    success: function(data)
    {
      $(".Loader").hide();
      if(data)
      {
        swal('Appointment status successfully has been change');
        $("#calendar_refresh").trigger('click');
        if($("#myDropdown_cal2").hasClass("show_cal")){
          $("#myDropdown_cal2").removeClass("show_cal"); 
        }
      }
      else if(data.error)
      {
        swal("",data.error,"error");  

      }
      $("#eventdetailmodel").modal('hide');
      $("#myModal_appoimentcenletion").modal('hide');
    }
  })


});


$(document).on('click','.cusomerlocation',function(e){
  e.preventDefault();
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
        // autocomplete = new google.maps.places.Autocomplete(
        //   /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
        //   {types: ['geocode']});

        // // When the user selects an address from the dropdown, populate the address
        // // fields in the form.
        // autocomplete.addListener('place_changed', fillInAddress);
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


<?php

require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB.'/function.php');


if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
  header("Location: ../index.php");die;
}

if ($_SESSION['usertype']=="Admin")
{
  header("Location: ../index.php");die;    
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
 header("Location: ../index.php");die;  
}

if(isset($_POST['curntuser']))
{
 $db=new db();
 $id = $_POST['curntuser'];
 $eidtClient = $db->prepare("SELECT clients.* FROM `clients` JOIN users ON (clients.createdfk=users.id OR clients.createdfk=users.adminid OR clients.createdfk=users.sid) WHERE users.id=:id GROUP BY clients.id");
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

   /* if(isset($_REQUEST['ClientsName']))
    {
        $db=new db();
       $ClientsName=$_POST['ClientsName']; 
      $eidtClient = $db->prepare("SELECT * from `clients` where id=:ClientsName");
      $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

      $stmt_note= $db->prepare("SELECT * FROM `noteandclient` LEFT JOIN note ON noteandclient.noteid=note.id WHERE noteandclient.clientid=:ClientsName AND noteandclient.active='1' ORDER BY `note`.`datecreated` DESC limit 0,5"); 
      $stmt_note->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $stmt_note->execute();
      $result_note = $stmt_note->fetchAll();  

      if($editfile)
      {
          echo  json_encode(["resonse"=>$all,"resonse_note"=>$result_note]);die;

      }
    }*/

    if(isset($_REQUEST['ClientsName']))
    {
      $db=new db();
      $ClientsName=$_POST['ClientsName']; 
      // $eidtClient = $db->prepare("SELECT * from `clients` where id=:ClientsName");

      $eidtClient = $db->prepare("select clients.id as cid,clients.Phone,clients.FirstName,clients.LastName,clients.email,clients.Zip,clients.City,clients.Country,clients.State,clients.ProfileImg ,OrderMembership.*,MemberPackage.Name,OrderMembership.id as cpackagid from `OrderMembership` 
        JOIN MemberPackage ON OrderMembership.MembershipId=MemberPackage.id
        RIGHT JOIN clients ON OrderMembership.Cid=clients.id WHERE clients.id=:ClientsName");
      $eidtClient->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetchAll(PDO::FETCH_ASSOC);
      $stmt_note= $db->prepare("SELECT * FROM `noteandclient` LEFT JOIN note ON noteandclient.noteid=note.id WHERE noteandclient.clientid=:ClientsName AND noteandclient.active='1' ORDER BY `note`.`datecreated` DESC limit 0,5"); 
      $stmt_note->bindValue(":ClientsName",$ClientsName,PDO::PARAM_INT);
      $stmt_note->execute();
      $result_note = $stmt_note->fetchAll();
      if($editfile)
      {
       echo  json_encode(["resonse"=>$all,"resonse_note"=>$result_note]);die;
     }
   }
   if(isset($_REQUEST['pakcagidc2']))
   {
    $pakcagidc2_id = $_REQUEST['pakcagidc2'];
    $eidtmembership2_id = $db->prepare("select MembershipId,Noofvisit from `OrderMembership` WHERE id=:pakcagidc2_id");
    $eidtmembership2_id->bindValue(":pakcagidc2_id",$pakcagidc2_id,PDO::PARAM_INT);
    $eidtmembershipfile2_id=$eidtmembership2_id->execute();
    $all_membership54564562_id=$eidtmembership2_id->fetch(PDO::FETCH_ASSOC);

    $pakcagidc2 = $all_membership54564562_id['MembershipId'];
    $eidtmembership2 = $db->prepare("select service from `MemberPackage` WHERE id=:pakcagidc2");
    $eidtmembership2->bindValue(":pakcagidc2",$pakcagidc2,PDO::PARAM_INT);
    $eidtmembershipfile2=$eidtmembership2->execute();
    $all_membership54564562=$eidtmembership2->fetch(PDO::FETCH_ASSOC);
    $listofservice = $all_membership54564562['service'];


    $eidtmembership3 = $db->prepare("select ServiceName,id from `Service` where id IN ($listofservice)");
      // $eidtmembership3->bindValue(":pakcagidc3",$pakcagidc3,PDO::PARAM_INT);
    $eidtmembershipfile3=$eidtmembership3->execute();
    $all_membership54564563=$eidtmembership3->fetchAll(PDO::FETCH_ASSOC);

    if($all_membership54564563)
    {
      echo  json_encode(["resonse"=>$all_membership54564562,"resonse2"=>$all_membership54564563,"resonse3"=>$all_membership54564562_id]);die;
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
 $eidtClient = $db->prepare("select Users,Info,Price,CommissionAmount,Duration from `Service` where id=:Servicename");
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
  $id=$_SESSION['UserID'];
  $eidtUserName = $db->prepare("select username,id,firstname,lastname from `users` where id IN ($UserName,$id)");
      // $eidtUserName->bindValue(":UserName",$UserName,PDO::PARAM_STR);
      // $eidtUserName->bindValue(":id",$id,PDO::PARAM_STR) ;
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

  if($Duration=='0 Min')
  {
    $time = strtotime($service_star_time);
    $time = date("g:ia", strtotime('+0 minutes', $time));
    echo  json_encode(["resonse"=>$time]);die;
  }

  if($Duration=='15 Min')
  {
    $time = strtotime($service_star_time);
    $time = date("g:ia", strtotime('+15 minutes', $time));
    echo  json_encode(["resonse"=>$time]);die;
  }


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
  $eidtClient = $db->prepare("SELECT * FROM `countries` JOIN provinces ON countries.cid=provinces.country_id WHERE countries.countries_name=:CountrysName ORDER BY provinces.name");
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

$listofeventforca= $db->prepare("SELECT clients.FirstName as cf,clients.LastName as cl, event.title,event.id as id,event.EventDate FROM `event` JOIN clients ON event.cid=clients.id WHERE event.createdfk=:id AND event.EventDate>= NOW() AND event.EventDate<= NOW() + INTERVAL 30 DAY  
  ORDER BY ABS( DATEDIFF( EventDate, NOW() ))");  
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
   // $myevent = $_POST['elink'];

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

  $statement=$db->prepare("SELECT * FROM `countries`  ORDER BY `countries`.`countries_name` ASC ");

  $statement->execute();

  $countryList = $statement->fetchAll(PDO::FETCH_ASSOC);



  $statement2=$db->prepare("SELECT * FROM `provinces`  ORDER BY `provinces`.`name`  ASC  ");

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

  $button2= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C60'"); 
  $button2->execute();
  $all_button2 = $button2->fetch(PDO::FETCH_ASSOC);
  $B2=$all_button2['button_name'];

  $button3= $db->prepare("SELECT button_name FROM `ButtonSetting` where  button_id='C61'"); 
  $button3->execute();
  $all_button3 = $button3->fetch(PDO::FETCH_ASSOC);
  $B3=$all_button3['button_name'];

  if(isset($_POST['newstat']))
  {
    $newstat= $_POST['newstat'];
    $myeventid = $_POST['myeventid'];

    $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
    $stmt->bindparam(":newstat",$newstat);
    $stmt->bindparam(":myeventid",$myeventid);
    $stmt->execute();

    if($stmt)
    {
      echo json_encode(['resonse'=>'Appointment status successfully change']);die;
    }


  }

  if(isset($_POST['eventidd']))
  {
    $myevent = $_POST['eventidd'];
    $EditEventprint=$db->prepare("SELECT event.title,event.CostOfService,event.FirstName,event.LastName,users.firstname as serprofir,users.lastname as serprolast,event.eventstatus,event.Phone,event.Email,event.EmailInstruction,event.EventDate FROM `event` LEFT JOIN users ON event.ServiceProvider=users.id WHERE event.id=:myevent");
    $EditEventprint->bindValue(":myevent",$myevent, PDO::PARAM_INT);
    $EditEventprint->execute();
    $resultprint = $EditEventprint->fetch();
    echo json_encode(['resonse'=>$resultprint]);die;

  }

  if(isset($_POST['commnetonet']))
  {

    $commnetonet = $_POST['commnetonet'];
    $commnettwot = $_POST['commnettwot'];
    $myeventid = $_POST['myeventid'];
    $newstat = $_POST['newstat2'];


    $EditEventprint=$db->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.Email AS Email,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.phonenumber AS  phonenumber,users.email AS  mymail,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
      FROM `event` 
      JOIN users ON event.ServiceProvider=users.id
      JOIN clients ON event.cid=clients.id
      WHERE event.id=:myeventid");
    $EditEventprint->bindValue(":myeventid",$myeventid, PDO::PARAM_INT);
    $EditEventprint->execute();
    $resultprint = $EditEventprint->fetch();

    $Email = $resultprint['Email'];
    $FirstName = $resultprint['FirstName'];
    $LastName = $resultprint['LastName'];
    $username = $resultprint['username'];
    $daate = $resultprint['EventDate'];
    $phonenumber = $resultprint['phonenumber'];
    $mymail = $resultprint['mymail'];
    $other['--EMAIL--'] = $Email;
    $other['--FIRSTNAME--'] = $FirstName;
    $other['--LASTNAME--'] = $LastName;
    $other['--Comment--'] = $commnetonet;
    $other['--UserName--'] = $username;
    $other['--daate--'] = $daate;
    $other['--phonenumber--'] = $phonenumber;
    $other['--mymail--'] = $mymail;



    if($_POST['eventcustohistory']=='yes')
    {

      $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat, description=:commnetonet,Appcanmsgfcus=:commnettwot WHERE id=:myeventid");
      $stmt->bindparam(":commnetonet",$commnetonet);
      $stmt->bindparam(":commnettwot",$commnettwot);
      $stmt->bindparam(":newstat",$newstat);
      $stmt->bindparam(":myeventid",$myeventid);
      $stmt->execute(); 

      if($stmt)
      {
        $message = '';
        $headers = '';
        sendEventMailForcanl($Email, "Appointment Cancellation!", "EventCan.php", $message, $headers, $other);    
        echo json_encode(['resonse'=>'Appointment status successfully change']);die;
      }
    }
    else
    {

      $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
      $stmt->bindparam(":newstat",$newstat);
      $stmt->bindparam(":myeventid",$myeventid);
      $stmt->execute(); 

      $message = '';
      $headers = '';
      sendEventMailForcanl($Email, "Appointment Cancellation!", "EventCan.php", $message, $headers, $other);    
      echo json_encode(['resonse'=>'Appointment status successfully change']);die;

    }


    
  }


  if(isset($_POST['commnetonet_1']))
  {

    $commnetonet = $_POST['commnetonet_1'];
    $commnettwot = $_POST['commnettwot_1'];
    $myeventid = $_POST['myeventid_1'];
    $newstat = $_POST['newstat2_1'];


    $EditEventprint=$db->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
      FROM `event` 
      JOIN users ON event.ServiceProvider=users.id
      JOIN clients ON event.cid=clients.id
      WHERE event.id=:myeventid");
    $EditEventprint->bindValue(":myeventid",$myeventid, PDO::PARAM_INT);
    $EditEventprint->execute();
    $resultprint = $EditEventprint->fetch();

    $Email = $resultprint['Email'];
    $FirstName = $resultprint['FirstName'];
    $LastName = $resultprint['LastName'];
    $other['--EMAIL--'] = $Email;
    $other['--FIRSTNAME--'] = $FirstName;
    $other['--LASTNAME--'] = $LastName;
    $other['--Comment--'] = $commnetonet;

    if($_POST['eventcustohistory_1']=='yes')
    {

      $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat, description=:commnetonet,Appcanmsgfcus=:commnettwot WHERE id=:myeventid");
      $stmt->bindparam(":commnetonet",$commnetonet);
      $stmt->bindparam(":commnettwot",$commnettwot);
      $stmt->bindparam(":newstat",$newstat);
      $stmt->bindparam(":myeventid",$myeventid);
      $stmt->execute(); 

      if($stmt)
      {
        $message = '';
        $headers = '';
        echo json_encode(['resonse'=>'Appointment status successfully change']);die;
      }
    }
    else
    {

      $stmt=$db->prepare("UPDATE event SET eventstatus=:newstat WHERE id=:myeventid");
      $stmt->bindparam(":newstat",$newstat);
      $stmt->bindparam(":myeventid",$myeventid);
      $stmt->execute(); 

      $message = '';
      $headers = '';
      echo json_encode(['resonse'=>'Appointment status successfully change']);die;

    }


    
  }

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <?php
  include 'head.php';
  ?>
  <link href="<?php echo base_url; ?>/assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css">
  <link rel='stylesheet' type='text/css'href='<?php echo base_url ?>/assets/css/timepicki.css' />

  <!-- <link rel="stylesheet" href="<?php echo base_url; ?>/assets/css/calendar.css"> -->
  <link href="<?php echo base_url; ?>/assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url; ?>/upload-and-crop-image/croppie.css">
  <link rel="stylesheet" href="<?php echo base_url; ?>/dist/css/lightbox.min.css">

  <style>
    ul#clientnotelistes li{display: block;}
    #myModal_data{display: none; background: red; z-index: 9999; width: 500px; height: 500px;}
    span#cal-title-on-day{font-size: 11px;}
    div#cal-week-box{z-index: 9999;}
    ul#eventlist2{display: block !important; font-size: 10px;}
    div#cal-slide-box{background: #333!important;}
    .alleventlist {padding: 25px 0;}
    .eventlisttitle{background: #3cabe1;padding: 10px;}
    .eventlist{background: antiquewhite;  padding: 10px;}
    .eventlist2 a{    color: #333;   text-decoration: none;    text-transform: capitalize;}
    div#home2 {padding: 10px 0;}

    div#myModal_new2 .modal-body{overflow: scroll;  }
    div#myModal_new2{z-index: 9999999;}
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
    #myDropdown_cal3{ background: white;    width: 100px;    left: 10px;margin-top: 32px;}
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
  background-color: yellow;
}
.event-red{
  background-color: #b51818;
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

@media screen and (max-device-width: 768px) { 
 .customtab{
  justify-content: center;
}
}
.dh-event-red{background: red; color: white; border: red;}
.dh-event-red a {color: white;}
.dh-event-red:hover{color: white;}
span.btn.btn-print.fa.fa-print{    background: green;   color: white;   border-color: green;}
.pull-left.day-event.day-highlight{height: auto !important;}
.pull-left.day-event.day-highlight{width: 30 !important%}
td.disabled.day{background: #eeeeee !important;}



@media (max-width: 500px){
  #UpcomingRenewalsDays{width: 100% !important;}

}
#UpcomingRenewalsDays{width: 25%; float: right;}
th { font-weight: bold!important;color:#0b59a2!important;}
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
            <a href="#" id="addnewappointment" class="nav-link" data-toggle="modal" data-target="#myModal_exit2">
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
                            </li>  -->

                          </ul>

                          <!-- Tab panes  -->
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
                                   <!-- <p>Some text in the modal.</p>   -->

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

                                 <!--  <button type="button" class="btn waves-effect waves-light btn-secondary myModal_new" data-toggle="modal" data-target="#myModal_new">New Client</button>  -->
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
                       <!--  Start Add new client modal  -->
                       <div class="modal fade" id="myModal_new2" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="z-index:1100">
                        <div class="modal-dialog modal-lg" style="max-width: 1100px;">

                          <!-- Modal content -->
                          <div class="modal-content">
                            <div class="modal-header">
                             <h4 class="modal-title">Customer Details</h4>
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                           </div>
                           <div class="modal-body">
                             <form class="form-horizontal" action="" method="post" autocomplete="nope" id="NewClient">

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
                              <div class="customersdetalisone">

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
    <img src="http://lorempixel.com/75/50/abstract/">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>   -->

    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%; margin-bottom: 20px;">Select Avtar<span class="glyphicon glyphicon-chevron-down"></span></button>


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
  </div>

  <div class="customersdetalistwo">
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
 </div>


 
 <div class="customersdetalistree">

  <div class="form-group">
    <label for="example-email">Address *</label>

    <input autocomplete="nope" type="text" name="Address" placeholder="Enter your address" id="Address" class="form-control" placeholder="Address" value="" >
    <!-- <input id="autocomplete" placeholder="Enter your address"  class="form-control" name="Address"  onFocus="geolocate()" type="text" value=""></input>
    <input type="hidden" class="" id="street_number" disabled="true"></input>
    <input type="hidden" class="" id="route" disabled="true"></input> -->
  </div>




<!--   <div class="form-group">
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
 </div> -->
 <div class="form-group">
  <label for="country">Country *</label>
  <input type="hidden" id="country" name="Country" value="United States">
  <input type="text" disabled="" value="United States" class="form-control">
</div>

<div class="form-group">
  <label><span class="help">State *</span></label>
  <select class="form-control" id="administrative_area_level_1" name="State">
    <option value="">Select a State</option>
    <?php
    $newstate = "Alabama,Alaska,Arizona,Arkansas,California,Colorado,Connecticut,Delaware,Florida,Georgia,Hawaii,Idaho,Illinois,Indiana,Iowa,Kansas,Kentucky,Louisiana,Maine,Maryland,Massachusetts,Michigan,Minnesota,Mississippi,Missouri,Montana,Nebraska,Nevada,New Hampshire,New Jersey,New Mexico,New York,North Carolina,North Dakota,Ohio,Oklahoma,Oregon,Pennsylvania,Rhode Island,South Carolina,South Dakota,Tennessee,Texas,Utah,Vermont,Virginia,Washington,West Virginia,Wisconsin,Wyoming";
    $stateList = explode(',', $newstate);
    foreach($stateList as $value){
      if($value == $result['state'] ){
       echo "<option selected value='".$value."'>".$value."</option>";
     }
     else{
       echo "<option value='".$value."'>".$value."</option>";
     }
   }
   ?>
 </select>
</div>

<!--  <div class="form-group">
  <label><span class="help">State *</span></label>

  <select class="form-control" id="administrative_area_level_1" name="State">
    <option value="">Select a State</option>
    <?php
    foreach($stateList as $value){
      echo "<option value='".$value['name']."'>".$value['name']."</option>";
    }
    ?>
  </select>

</div> -->

<div class="cutomercityandzip">
  <div class="cumtercity">  
   <div class="form-group">
     <label for="example-email">City *</label>
     <input  id="locality" name="City" value="" class="form-control" autocomplete="nope" placeholder="City"></input>
   </div>
 </div>

 <div class="cumterzip">
   <div class="form-group">
    <label for="example-email">Zip Code *</label>
    <input type="text"  id="postal_code" name="Zip" value="" class="form-control" autocomplete="nope" placeholder="0123456" maxlength="10"></input>
  </div>
</div>
</div>
</div> 
<!-- <a  class="btn btn-waves-effect waves-light btn-secondary back " id="back" ><?php echo $B20; ?></a>  -->



<div class="Loader"></div>
<div class="clearfix" style="clear: both;"></div>
</div>
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
<div class="modal-footer">
  <div class="form-group">
    <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" name="add-client" id="add-client"><i class="fa fa-check"></i> <?php echo $B18; ?></button>
  </div>
  <div class="form-group">
    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $B19; ?></button>
  </div>
</div>
</form>
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

<style>
  .page-header{
    text-align: center;
  }
  .page-header .btn-default{
    background-color: white;
    border: 1px solid #ebedf2;
  }
  .page-header .btn-default:hover{
    background-color: #6a7af9;
    color: white;
  }


</style>

<!--  End Add appointment tab -->
<!-- star Calendar View Tab -->
<div class="tab-pane active" id="home2" role="tabpanel">

  <div class="page-header" id="calendar_head">
    <div class="form-inline justify-content-center">
      <div class="btn-group">
       <button class="btn btn-default notave" data-calendar-nav="prev"><</button>
       <button class="btn btn-default notave" data-calendar-nav="next">></button>
     </div>
     <div class="btn-group ml-2">
       <button class="btn btn-default notave" data-calendar-nav="today">today</button>
     </div>
   </div>

   <h3></h3>

   <div class="form-inline justify-content-center">
    <div class="dropdown_cal">
      <img onclick="myFunction3()" class="dropdown-toggle" src="<?= base_url?>/assets/images/colorpicker.png" title="appointment status" width="30" height="30" id="" style="cursor: pointer; margin: 0 5px;"  /></button>
      <div id="myDropdown_cal3" class="dropdown-content_cal">
       <div class="btn-group"><span class="pull-left event event-grey"></span> : Pending <br></div>
       <div class="btn-group"><span class="pull-left event event-success"></span> : Confirmed <br></div>
       <div class="btn-group"><span class="pull-left event event-important"></span> : Canceled <br></div>
       <div class="btn-group"><span class="pull-left event event-red"></span> : No-show <br></div>
       <div class="btn-group"><span class="pull-left event event-warning"></span> : In Progress <br></div>
       <div class="btn-group"><span class="pull-left event event-info"></span> : Completed <br></div>



     </div>
   </div>

   <img src="<?= base_url?>/assets/images/calendarbtn.png" data-toggle="tooltip" title="Month Calendar" width="37" height="35" id="monthbtn" style="cursor: pointer;" />

   <div class="dropdown_cal">
     <button onclick="myFunction2()" class="dropbtn_cal btn-default dropdown-toggle">Filter</button>
     <div id="myDropdown_cal2" style ="margin: 35px 0px 0 -60px;" class="dropdown-content_cal">
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


   <div>
     <div class="btn-group">
       <button class="btn btn-default active notave" id="notave" data-calendar-view="month">Month</button>
       <button class="btn btn-default" data-calendar-view="week">Week</button>
       <button class="btn btn-default daydash" data-calendar-view="day">Day</button>
     </div>
   </div>

<!--    <div class="dropdown_cal">
     <button onclick="myFunction()" class="dropbtn_cal dropdown-toggle">Month</button>
     <div id="myDropdown_cal" style="margin: 35px 0px 0px -65px" class="dropdown-content_cal">
      <div class="btn-group"><button class="btn btn-default" data-calendar-view="year">Year</button></div>
      <div class="btn-group"><button class="btn btn-default active notave" id="notave" data-calendar-view="month">Month</button></div>
      <div class="btn-group"><button class="btn btn-default" data-calendar-view="week">Week</button></div>
      <div class="btn-group"><button class="btn btn-default daydash" data-calendar-view="day">Day</button></div>
    </div>
  </div> -->

</div>

</div>

<style>
  #cal-day-panel::-webkit-scrollbar, .cal-week-box::-webkit-scrollbar {
    width: 10px;
    height: 10px;
  }


  /* Handle */
  #cal-day-panel::-webkit-scrollbar-thumb, .cal-week-box::-webkit-scrollbar-thumb {
    background: #3cabe1; 
    border-radius: 8px;
  }

  /* Handle on hover */
  #cal-day-panel::-webkit-scrollbar-thumb:hover, .cal-week-box::-webkit-scrollbar-thumb:hover {
    background: #4999bf; 
  }
</style>
<div class="row"  style="width: 100%;">
 <div class="col">
  <div id="nycal">
    <div id="showEventCalendar"></div>
  </div>
</div>


      <!-- <div class="col-md-4">
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
          if(!empty($result_listofeventforca))
          {
            foreach ($result_listofeventforca as $key => $value)
            {

              ?>

              <li><a class="EditButton" href="#" data-id="<?php echo base64_encode($value['id']); ?>"><b style="width: 33%; float: left;"><?php echo ucfirst($value['cf'].' '.$value['cl']) ?></b><b style="width: 33%;"><?php echo $value['title']; ?></b><b style="width: 33%; float: right;" ><?php echo date('m/d/y',strtotime($value['EventDate']));?></b></a></li>

              <?php
            }
          }
          else
          {
            echo "<li> No upcoming appointment </li>";
          }

          ?>    

        </ul>
      </div>

      </div>
    </div> -->
  </div>
</div>

<!--  End Calendar View tab -->
<div id="myModal_data"></div>
<!--  Start List View tab -->
<div class="tab-pane  p-20" id="profile2" role="tabpanel">
  <div class="col-lg-12">
    <div class="d-flex align-items-center">
      <h3 id="header_date" class="m-auto"></h3>
      <form id="list_search">
        <input type="text" name="list_search" value="" placeholder="Search...">
        <i class="fas fa-search search-icon" style="position: relative;right: 18px;"></i>
      </form>
      <select class="custom-select" id="UpcomingRenewalsDays" style="">
        <option selected value="All">All</option>
        <option  value="Last">Last 30 Days </option>
        <option value="Next">Next 30 Days</option>
      </select>
    </div>      
    <style>
      #list_search input[type="text"] {
        background-size: 0 2px,100% 1px;
        transition: background 0s ease-out;
        float: none;
        box-shadow: none;
        border-radius: 0;
        border: 0;
        background-repeat: no-repeat;
        background-position: center bottom,center calc(100% - 1px);
        background-color: transparent;
        background-image: linear-gradient(#3cabe1,#3cabe1),linear-gradient(#e9ecef,#e9ecef);
      }
      #list_search input[type="text"]:focus {
        outline: 0;
        background-size: 100% 2px,100% 1px;
        box-shadow: none;
        transition-duration: .3s;
      }
      #myTable{
        margin-top:20px;
      }
      .client_event_time{
        padding: 0px;
      }
      .list {
        border: 1px solid #00000024;
        margin-bottom: 10px;
        border-radius: 2px;
        box-shadow: 1px 3px 2px 0px #0000001a;
      }
      .list .row{
        margin:0px !important;
        padding: 10px 16px;
        font-size: 15px;
      }
      .list .list_body {
        border-bottom: 1px solid #00000024;
      }
      .list .list_header{
        justify-content: space-between;
        background-color: #f7f8fa;
      }
      .list_header .list_day{
        font-weight: 600;
      }
      .list_body{
        text-align: center;
        align-items: center;
      }
      .list_body .client_status .client_status_dot{
        margin: auto;
        width: 15px;
        height: 15px;
        border-radius: 50%;
      }
      .list_body .client_profile{
        display: flex;
        flex-direction: column;
        text-align: center;
      }
      .list_body .client_contact{
        color: #3cabe1;
      }
      .list_body .client_contact span{
        color: black;
      }
    </style>
    <div id="myTable"> </div>
  </div>
</div>
<!--  End List View tab -->

<div class="modal fade" id="eventdetailmodel" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Event Detail</h4>
        <button type="button" class="close" onclick="$('.Loader').hide();" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
        <div>
          Customer Name : <span id="evnetcutomername" class="evnetcutomername"></span><br>
          Service : <span id="envetsername" class="envetsername"></span><br>
          Start Time : <span id="evnetstartime" class="evnetstartime"></span><br>
          Employee : <span id="evnetemployename" class="evnetemployename"></span><br><br><br>

          <a href="#" class="appointmentwithnote1" data-toggle="modal" data-target="#appointmentwithnote" id="appointmentwithnote1" style="color: black;" data-id=''><span class=" btn event-grey fa fa-sticky-note" aria-hidden="true"></span> Note </a><br><br>

          <a href="#"  id="EditButton" data-event-id="" class="EditButton ViewEvent" style="color: black;" data-id=""><span class="btn btn-info fa fa-edit"></span> Edit appointment</a><br><br>

          <a href="#" name="DeleteEvent" data-event-id="<%= event.id %>" style="color: black;" id="DeleteEvent" class=""><span class="btn btn-danger fa fa-trash"></span> Delete appointment </a><br><br>

          <a href="#" name="ViewCustomer" data-event-id="<%= event.cid %>" id="ViewCustomer" style="color: black;" class="" ><span class="btn btn-success fa fa-eye"></span> View customer </a> <br><br>

          <a href="#" onclick="PrintDiv();" class="printappoiment" id="printappoiment" style="color: black;" data-id=''><span class=" btn btn-print fa fa-print" aria-hidden="true"></span> Print Ticket </a><br><br>

          <a href="#" class="cusomerlocation" id="cusomerlocation" style="color: black;" data-id=''><span class=" btn btn-warning fa fa-map-marker" aria-hidden="true"></span> Directions to Appointment </a><br><br>

          <a href="#" onclick="statuschng()" class="checkout" id="checkout" style="color: black;" data-id=''><span class=" btn fa fa-shopping-cart" aria-hidden="true"></span> Checkout </a><br><br>




          <style type="text/css">
            .radio-custom {
              opacity: 0;
              position: absolute;   
            }

            .radio-custom, .radio-custom-label {
              display: inline-block;
              vertical-align: middle;
              margin: 5px;
              cursor: pointer;
            }

            .radio-custom-label {
              position: relative;
            }

            .radio-custom + .radio-custom-label:before {
              content: '';
              background: #fff;
              border: 2px solid #ddd;
              display: inline-block;
              vertical-align: middle;
              width: 25px;
              height: 25px;
              padding: 2px;
              margin-right: 10px;
              text-align: center;
            }

            .radio-custom1 + .radio-custom-label1:before {
              background: yellow!important; 
              border: 2px solid yellow;
            }

            .radio-custom2 + .radio-custom-label2:before {
              background: #00d600!important; 
              border: 2px solid #00d600;
            }

            .radio-custom3 + .radio-custom-label3:before {
              background: #ff0000!important; 
              border: 2px solid #ff0000;
            }

            .radio-custom4 + .radio-custom-label4:before {
              background: #b51818!important; 
              border: 2px solid #b51818;
            }

            .radio-custom5 + .radio-custom-label5:before {
              background: #ffb100!important; 
              border: 2px solid #ffb100;
            }

            .radio-custom6 + .radio-custom-label6:before {
              background: black!important; 
              border: 2px solid black;
            }

            .checkbox-custom:checked + .checkbox-custom-label:before {
              content: "\f00c";
              font-family: 'FontAwesome';
              background: rebeccapurple;
              color: white !important;
            }

            .radio-custom + .radio-custom-label:before {
              border-radius: 50%;
            }

            .radio-custom:checked + .radio-custom-label:before {
              content: "\f00c";
              font-family: 'FontAwesome';
              color: white !important;
            }

            .radio-custom:focus + .radio-custom-label {
              outline: 1px solid #ddd; /* focus style */
            }
          </style>
          <div>
            <input id="radio-1" class="radio-custom radio-custom1" value="pending" name="radio-group" type="radio">
            <label for="radio-1" class="radio-custom-label radio-custom-label1">Pending</label>
          </div>
          <div>
            <input id="radio-2" class="radio-custom radio-custom2" name="radio-group" value="confirmed" type="radio">
            <label for="radio-2" class="radio-custom-label radio-custom-label2">Confirmed</label>
          </div>
          <div>
            <input id="radio-3" class="radio-custom radio-custom3" name="radio-group" type="radio" value="canceled">
            <label for="radio-3" class="radio-custom-label radio-custom-label3">Canceled</label>
          </div>

          <div>
            <input id="radio-4" class="radio-custom radio-custom4" name="radio-group" type="radio" value="no-show">
            <label for="radio-4" class="radio-custom-label radio-custom-label4">No-show</label>
          </div>

          <div>
            <input id="radio-5" class="radio-custom radio-custom5" name="radio-group" type="radio" value="in-progress">
            <label for="radio-5" class="radio-custom-label radio-custom-label5">In Progress</label>
          </div>

          <div>
            <input id="radio-6" class="radio-custom radio-custom6" name="radio-group" type="radio" value="completed">
            <label for="radio-6" class="radio-custom-label radio-custom-label6">Completed</label>
          </div>


        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="$('.Loader').hide();" data-dismiss="modal">Close</button>
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
      <button type="button" class="close" onclick="$('.Loader').hide();" data-dismiss="modal">&times;</button>

    </div>

    <div class="modal-body">
     <div>

       <div class="map-box">
         <iframe id="eventaddermap" src="" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
       </div>      
<!-- <input class="form-control register-form__location-holder" type="text" id="user_location" name="user_location" placeholder="Enter place name" value="<?php echo $user_city ;?>" type="text"/>
<div style="width:100%;height:300px" id="register-form__map" class="register-form__map register-form__map--user"></div> 

-->

</div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" onclick="$('.Loader').hide();" data-dismiss="modal">Close</button>
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
        <input type="hidden" name="editServiceProvider" class="form-control" placeholder="editServiceProvider" id="editServiceProvider" autocomplete="nope" value="">
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

                      if(count($result_event2) == 0)
                      {
                        ?>
                        <script>
                          $(document).on("click","#select2-newlistofcatagory-container",function(){ service(); });
                          $(document).on("click",$("#select2-newlistofcatagory-container").parent(),function(){ service(); });
                                    // $("#select2-newlistofcatagory-container").parent().on("click",function(){ service(); });

                                    // $(document).on("click","#select2-newlistofcatagory-container",function(){ $("#select2-newlistofcatagory-results li").html("<a href='https://mysunless.com/viewService'> + Add New Service</a>"); });
                                    function service(){
                                      var SUB = '<?= $SUB ?>';

                                      $("#select2-newlistofcatagory-results li").html("<a href='https://mysunless.com"+SUB+"/viewService'> + Add New Service</a>");
                                    }
                                    // $("#select2-newlistofcatagory-container").parent().on("click",function(){ $("#select2-newlistofcatagory-results li").html("<a href='https://mysunless.com/viewService'> + Add New Service</a>");});
                                  </script>
                                  <?php
                                }
                                else
                                { 
                                  foreach($result_event2 as $row2)
                                  {
                                    ?>
                                    <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
                                    <?php
                                  } 
                                }
                                ?>
                              </select>

                              <a href="#" id="addmoreservie" class="addmoreservie" style="display: none">+ Add other service </a>
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
                          <p id="datepairExample" style="display: flex;">
                            <input type="text" class="date start form-control mr-2" placeholder="Start Date" name="sd" autocomplete="nope1"  id="eventstardate" />
                            <!--  <input type="text" placeholder="Start Time" class= "time start form-control" name="st" autocomplete="nope" id="eventstartime" /> -->
                            <input id='eventstartime' type='text' placeholder="Click here to select Start Time" name='st' class="form-control eventTime eventChange start"  autocomplete="nope" readonly />

                            

                            <input type="hidden" class="date start form-control" placeholder="End Date" name="ed" autocomplete="nope"  id="eventenddate" /> 
                            <input type="hidden" class="time end form-control" placeholder="End Time" name="et" autocomplete="nope" id="eventendtime"  />
                          </p>
                        </div>
                        
                        <div class="row" id="time_slot"></div>
                        
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
                    <input type="radio" id="Location_radio" name="Location_radio" value="Salon Location" class="locone"> Salon Location
                  </div>
                  <div class="col-sm-6 col-md-6 pull-right">
                    <input type="radio" id="Location_radio" name="Location_radio" checked="true" value="Customer Location" class="locone2"> Customer Location
                  </div>
                </div>
                <div style="margin-top: 10px;">
                  <label>Package Purchased :</label>  
                  <li  style="list-style: none;margin-top: 3px;" class="SelectPackageliv"></li>

                </div>
                <div style="margin-top: 10px;" >
                  <label >Remaining Services : <b><p style="display: inline;" id="remservice" data-id="" ></p></b></label>
                </div>

                <div id="selser" class="col-md-8" >
                  <div style="margin-top: 5px;" class="row">
                    <select onchange="addbtn()" class="select2 m-b-10 select2-multiple listofavliaasfpackag2" style="width: 100%"  data-placeholder="Choose service" id="listofavliaasfpackag2" name="listofavliaasfpackag2">
                    </select>
                  </div>
                </div>
                <div id="addser" style="margin-top: 10px;" >

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
                   <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new2" ><b><?php echo $B13; ?></b></button>

                   <?php
                 }
                 else
                 {
                   if(@$number_of_users >= @$ClientsLimit)                                            
                   {
                     ?>
                     <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new2" ><b><?php echo $B13; ?></b></button>

                     <?php
                   }
                   else
                   {
                     ?>
                     <button style="background-color: transparent;border: none;cursor: pointer;" class="pull-right myModal_new" onclick="$('#NewEvent input[name=\'id\']').val('new');" data-toggle="modal" data-target="#myModal_new2" ><b><?php echo $B13; ?></b></button>                                       

                     <?php
                   }
                 }
               }
               ?> 

               <select onchange = "cleardiv()" class="select2 m-b-10 select2-multiple" style="width: 100%"  data-placeholder="Choose Customer" name="newlistofclient" id="newlistofclient">
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

            <div class="row" id="clinetdetails_note" style="padding: 10px; float: right;">
              <!-- <button style="background-color: transparent;border: none;cursor: pointer;">Add note</button> -->
              <button  id="clinetdetails_note_cid" name="clinetdetails_note_cid" class="btn btn-info m-r-10" data-toggle="modal" data-target="#myModal_listofnote">Note</button>
            </div>

            <div style="clear: both;"></div>
            <div class="clientnotelistes" style="display: none;">
              <h5>Recent Note : </h5>
              <ul id="clientnotelistes"></ul>
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
          <!-- <button type="button" class="btn waves-effect waves-light btn-light" id="resetappp"><i class="fa fa-refresh" aria-hidden="true"></i>Reset</button>    -->
        </div>
      </div>
    </form>
  </div>
</div>
</div>


<div class="modal fade" id="myModal_appoimentcenletion" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cancel Appointment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Customer Name : <span id="evnetcutomername" class="evnetcutomername"></span><br>
        Service : <span id="envetsername" class="envetsername"></span><br>
        Start Time : <span id="evnetstartime" class="evnetstartime"></span><br>
        Employee : <span id="evnetemployename" class="evnetemployename"></span><br><br><br>

        <div>
          <div class="form-group">
            <label for="todoDesc">Comment</label>
            <textarea class="textarea_editor form-control" placeholder="Enter Comment..." id="appointmentcancelcomment" name="appointmentcancelcomment"></textarea>
          </div>


          <div class="form-group">
            <label for="todoDesc">Message to customer</label>
            <textarea class="textarea_editor form-control" placeholder="Enter Task Description..." id="appointmentcancelcommentforcusteomr" name="appointmentcancelcommentforcusteomr"></textarea>
          </div>

          <div class="form-group">
            <p class="package_carfor_p" style=""><input type="checkbox" class="event_carryford" id="event_carryford" name="event_carryford" value="yes"> Add this to customer's cancellation history </p>
          </div>

          <div class="form-group">
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="Notifytocustomer"> Notify Customer</button>
            <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="dontNotifytocustomer"> Don't Notify Customer</button>
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal"> Cancel</button>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_listofnote" role="dialog">
  <div class="modal-dialog modal-lg" style="max-width: 1000px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">List of Note</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
        <button  class="appointmentwithnote btn btn-info m-r-10 assignNewVal" data-toggle="modal" data-target="#appointmentwithnote" id="appointmentwithnote1"  data-id="">Add Note</button>
        <div class="table-responsive m-t-40 col-md-12">
          <table id="ClinetnoteTable" class="table table-bordered table-striped dataTable no-footer" style="width: 100%;">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal  -->
<div class="modal fade" id="appointmentwithnote" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">To-Do</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
       <form class="form-horizontal " autocomplete="off" id="NewEvent_todo" method="post">
        <input type="hidden" class="editnoteid" name="id" id="id" value="new">
        <input type="hidden" name="UserID" id="UserID" value="<?php echo $_SESSION['UserID']; ?>">
        <div class="form-group">
          <label><span class="help"> Note Title  *</span></label>
          <input type="text" name="noteTitle" id="noteTitle" value="" class="form-control" maxlength="30">
        </div>
        <div class="form-group">
          <label><span class="help"> Note Detail  *</span></label>
          <textarea class="textarea_editor form-control" rows="10" placeholder="Enter Note Detail ..." name="noteDetail" id="noteDetail" >  </textarea>
        </div>
        <div class="form-group">

          <input type="hidden" name="noteRelated[]" id="noteRelated" value="" class="form-control">
        </div>

        <div class="form-group">
          <button type="submit" class="btn waves-effect waves-light btn-info m-r-10" id="addNote"> <i class="fa fa-check">
          </i> Submit Note</button>
          <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">  Cancel Note</button>
        </div>
      </form>

      <div class="col-lg-12 col-md-12">
        <div class="alert alert-success" id="resonse" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          <h3 class="text-success">
            <i class="fa fa-check-circle">
            </i>
            Success
          </h3>
          <p id="resonsemsg">
          </p>
        </div>
        <div class="alert alert-danger" id="error" style="display: none;">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
          <h3 class="text-danger">
            <i class="fa fa-exclamation-circle">
            </i>
            Errors
          </h3>
          <p id="errormsg">
          </p>
        </div>

        <div class="alert alert-danger" id="csrf_error" style="display: none;">
          <button type="button" class="close"> <span aria-hidden="true">&times;</span> </button>
          <h3 class="text-danger"><i class="fa fa-exclamation-circle"></i> Errors</h3><p id="csrf_errormsg"></p>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>

</div>
</div>



<div class="modal fade" id="myModal_addmoreservice" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="divToPrint2" style="display:none;">
  <div>



    <?php echo '<div class="servirprofname">
    <span style="font-weight: bold;">Service Provider: </span> <span class="printserviewprovider"></span>
    </div>
    <div class="col-lg-12" style="width:100%; padding: 20px 0; ">
    <table>
    <tr>
    <td>Customer: </td>
    <td id="printserviewcutomer"></td>
    </tr>

    <tr>
    <td>Cell: </td>
    <td id="printeventphone"></td>
    </tr>

    <tr>
    <td>Email: </td>
    <td id="printeventEmail"></td>
    </tr>

    </table>
    <h4>Appointment Summary</h4>
    </div>

    <div class="col-lg-12" style="width:100%;">
    <table border="1">
    <tr>
    <td>Date</td>
    <td>Status</td>
    <td>Service</td>
    <td>Service Provider</td>
    <td>Price</td>
    <td>Comment</td>
    </tr>

    <tr>
    <td id="printeventdate"></td>
    <td id="printeventstauts"></td>
    <td id="printserviewname">Test</td>
    <td class="printserviewprovider"></td>
    <td id="printservicecost">20</td>
    <td id="printservicecomment">test</td>
    </tr>

    </table>
    </div>


    <div class="last" style="text-align: center;"><h4>Thank you MySunless.</h4></div>';
    ?>  
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

    //reset appoinment form
    // $("#resetappp").click(function(){
    //   $("#NewEvent").trigger("reset");
    //   $("#NewEvent.select2-selection__rendered").text("");
    //   $("#offrepeat").trigger("click");
    //   $("#newemail").text("");
    // });


    //active class
    setInterval(function(){$(".allevent").addClass("active");}, 10);

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

//  function getsponserid(mysid)
//  {

//    var newsponserid = mysid;
// $("#listofcatagory3").val(newsponserid).trigger('change');



//  }
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

$(document).on('click','.edit_note',function(e){

  e.preventDefault();
  var mynoteid = $(this).data('id');

  $.ajax({
    dataType:"json",
    type:"post",
    data:{'mynoteid':mynoteid},
    "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteid="+mynoteid,
    success: function(data)
    {
      if(data.resonse)
      {
        $('#noteTitle').val(data.resonse.noteTitle)
                      // $('#noteDetail').val(data.resonse.noteDetail)
                      $('#noteDetail').data("wysihtml5").editor.setValue(data.resonse.noteDetail);
                      $('.editnoteid').val(data.resonse.id)
                      // NotedataTable(data.resonse.noteRelated)
                      $('#appointmentwithnote').modal('show');
                    }
                    else if(data.error)
                    {

                      swal("Sorry something wrong please try agine")
                    }
                  }
                });
});

$(document).on('click','#clinetdetails_note_cid',function(e){
 e.preventDefault();


 var mycid = $(this).val();
   //console.log("vvvvvvvvvvvvvvvvvvv" + mycid);

   NotedataTable(mycid)

   function NotedataTable(mycid)
   {


    $('#ClinetnoteTable').DataTable({
      "responsive": true,
      "processing": true,
      "destroy": true,

      "ajax" : {
        "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mycid="+mycid,
        "dataSrc" : ''
      }
      ,
      "autoWidth": false,
      "columns" : [ 
      {
        "data" : "noteTitle"
      },
      {
        "data" : "enterdate"
      }
      ,
      {
        "data" : "noteDetail",
      }
      , 
      {
        "data": "id",
        "render": function(data, type, row) {

          var encodedId = window.btoa(data);


          return '<button class="btn btn-info btn-sm edit_data custbutton edit_note" id="edit_note" title="Edit Event" data-id='+ encodedId +'>' + '<span class="fa fa-edit"><span>' + '</button> <button class="btn btn-danger btn-sm custbutton" title="Delete Event" id="delete_note" data-did='+ encodedId + '>' + '<span class="fa fa-trash"><span>' + '</button>';
        }
      }
      ]
    });

  }




  $(document).on('click','#delete_note',function(e){
    e.preventDefault();
    var mynoteiddid = $(this).data('did');
    $.ajax({
      dataType:"json",
      type:"post",
      data:{'mynoteiddid':mynoteiddid},
      "url" : "<?php echo base_url; ?>/All_Script.php?page=Dashboard&mynoteiddid="+mynoteiddid,
      success: function(data)
      {
        if(data.resonse)
        {
          swal(data.resonse)
          $("#clientnotelistes li a").each(function(){
            if($(this).attr("data-id")==mynoteiddid){
              $(this).remove();
            }
          });
          $('#myModal_listofnote').modal('hide');
        }
        else if(data.error)
        {

          swal("Sorry something wrong please try agine")
        }
      }
    });





  });



});


  //$('#myModal_exit22').modal('toggle');
  $('#monthbtn').click(function(){
    $('#notave').trigger('click');
  });
  $('#clinetdetails').hide();
  $('#clinetdetails_note').hide();
  var daf = Math.floor(Math.random() * 40) + 1  
  $("#ProfileImg").attr("data-default-file", "<?php echo base_url; ?>/assets/ProfileImages/Layer"+daf+".png");

  dataTable('all',"");

  $("#list_search").keyup(function(){
    // e.preventDefault();
    var UpcomingRenewalsDays = $("#UpcomingRenewalsDays").val() ;
    var search = $("#list_search input").val();
    dataTable(UpcomingRenewalsDays,search);
  });

  $('#UpcomingRenewalsDays').change(function(){
    var UpcomingRenewalsDays = (this).value ;
    var search = $("#list_search input").val();
    dataTable(UpcomingRenewalsDays,search);
       // dataTable(UpcomingRenewalsDays);
     });


  function dataTable(UpcomingRenewalsDays,search=""){
    // $(".Loader").show();
    $.ajax({
      url: "<?php echo EXEC; ?>ExecAllEvent.php?UpcomingRenewals="+UpcomingRenewalsDays+"&search="+search,
      type :'post',
      success:function(data){
        var data = JSON.parse(data);

        if(data=='false'){
          $(".Loader").hide();
          return false;
        }
        var header_date = moment(data[0].EventDate.split(" ")[0]).format('MMMM DD,YYYY')+" - "+moment(data[data.length-1].EventDate.split(" ")[0]).format('MMMM DD,YYYY');
        
        $("#header_date").text(header_date);


        var check=[];
        parent = $("#myTable");
        parent.html("");
        for(var i=0;i<data.length;i++){
          // console.log(data[i].EventDate); return false;
          //console.log(data);

          var encodedId = btoa(data[i].clientid);
          var event_encode_id = btoa(data[i].id);
          //console.log(event_encode_id);
          var EventDate = data[i].EventDate.split(" ");
          var end_date = data[i].end_date.split(" ");


          var date = moment(EventDate[0]).format('MMMM DD,YYYY');
          var day = moment(EventDate[0]).format('dddd');

          var date_check = moment(EventDate[0]).format('YYYYMMDD');

          if(data[i].ProfileImg!=""){
            ProfileImg = '<a class="example-image-link" href="<?php echo $base_url ?>/assets/ProfileImages/'+data[i].ProfileImg+'"" data-lightbox="example-'+data[i].ProfileImg+'"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data[i].ProfileImg+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a>';
          }else{
            ProfileImg = '<a class="example-image-link" href="<?php echo $base_url ?>/assets/images/noimage.png" data-lightbox="example-noimage.png"> <img src="<?php echo $base_url ?>/assets/images/noimage.png" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /> </a>';
          }
          var eventstatus = data[i].eventstatus;
          if(eventstatus=="canceled"){
            event_class="event-important";
          }else if(eventstatus=="completed"){
            event_class="event-info";
          }else if(eventstatus=="confirmed"){
            event_class="event-success";
          }else if(eventstatus=="in-progress"){
            event_class="event-warning";
          }else if(eventstatus=="pending"){
            event_class="event-grey";
          }else if(eventstatus=="completed"){
            event_class="event-important";
          }else if(eventstatus=="no-show"){
            event_class="event-red";
          }else{
            event_class="event-inverse";
          }


          
          
          if(check.indexOf(date_check)<0){
            check.push(date_check);
            parent.append('<div class="list" id="'+date_check+'"> <div class="row list_header"> <span class="list_day">'+day+'</span> <span class="list_date">'+date+'</span> </div> <div class="row list_body"> <div class="col-md-2 client_event_time">'+EventDate[1]+' - '+end_date[1]+'</div> <div class="col-md-2 client_event_title">'+data[i].title+'</div><div class="col-md-2 client_profile">'+ProfileImg+'<a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " > <span style="text-transform: capitalize;" >'+data[i].FirstName+ data[i].LastName +'</span> </a> </div> <div class="col-md-3 client_contact"> Phone: <span class="client_contact_phone">'+data[i].Phone+'</span> <br> Email: <span class="client_contact_email">'+data[i].Email+'</span> </div> <div class="col-md-1 client_status"><div title="'+eventstatus+'"class="client_status_dot '+event_class+'"></div></div> <div class="col-md-2"> <button class="btn btn-info btn-sm EditButton" title="Edit appointment" id="EditButton" data-id="'+event_encode_id+'" ><span class="fa fa-edit"><span></button> <button class="btn btn-danger btn-sm" title="Delete Category" id="deleteButton" data-id="'+event_encode_id+'"><span class="fa fa-trash"><span></button></div></div></div>');


          }else{
            $("#"+date_check).append('<div class="row list_body"> <div class="col-md-2 client_event_time">'+EventDate[1]+' - '+end_date[1]+'</div> <div class="col-md-2 client_event_title">'+data[i].title+'</div><div class="col-md-2 client_profile">'+ProfileImg+'<a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " > <span style="text-transform: capitalize;" >'+data[i].FirstName+ data[i].LastName +'</span> </a> </div> <div class="col-md-3 client_contact"> Phone: <span class="client_contact_phone">'+data[i].Phone+'</span> <br> Email: <span class="client_contact_email">'+data[i].Email+'</span> </div> <div class="col-md-1 client_status"><div title="'+eventstatus+'"class="client_status_dot '+event_class+'"></div></div> <div class="col-md-2"> <button class="btn btn-info btn-sm EditButton" title="Edit appointment" id="EditButton" data-id="'+event_encode_id+'" ><span class="fa fa-edit"><span></button> <button class="btn btn-danger btn-sm" title="Delete Category" id="deleteButton" data-id="'+event_encode_id+'"><span class="fa fa-trash"><span></button></div></div>');
          }


        }
        // $(".Loader").hide();
      }
    });
}

/*function dataTable(UpcomingRenewalsDays)
{
 $('#myTable').DataTable({
  dom: 'Bfrtip',
  "order": [[ 4, "desc" ]],
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
{ "width": "13%", "targets": 0, "className" : 'evnet_info1'},
{ "width": "14%", "targets": 1,"className" : 'evnet_info2' },
{ "width": "18%", "targets": 2,"className" : 'evnet_info3' },
{ "width": "11%", "targets": 3,"className" : 'evnet_info4' },
{ "width": "13%", "targets": 4,"className" : 'evnet_info5' },
{ "width": "11%", "targets": 5,"className" : 'evnet_info6' },
          // { "width": "10%", "targets": 6 },
          ],
          "columns" : [
          {
            "data" : "title"
          },
          {
            "data": {ProfileImg:"ProfileImg", FirstName:"FirstName", LastName:"LastName",clientid : "clinetid"},
            "render": function(data,type,row){
              if(data.ProfileImg!=''){
                var encodedId = btoa(data.clientid);
                return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'"" data-lightbox="example-'+data.ProfileImg+'"><img src="<?php echo $base_url ?>/assets/ProfileImages/'+data.ProfileImg+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " ><span style="text-transform: capitalize;"> '+ data.FirstName +' '+ data.LastName +'</span></a> </div></div>';    
              }
              else
              {
                return '<div class="row"><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a class="example-image-link" href="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" data-lightbox="example-noimage.png"><img src="<?php echo $base_url ?>/assets/images/'+'noimage.png'+'" class="img-circle example-image" style="height: 50px; width: 50px; vertical-align:middle ;" /></a></div><div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><a title="View Customer Profile" href="ViewClient.php?action=view&id='+ encodedId +' " ><span style="text-transform: capitalize;" > '+ data.FirstName +' '+ data.LastName +'</span> </a></div></div>';       
              }
            }
          }, 
          {
            "data" : {Phone:"Phone",Email:"Email"},
            "render": function(data,type,row){
              return  '<span class="pull-left" > <b>Phone:</b> '+ data.Phone +'</span><br><span class="pull-left" > <b style="float:left;" >Email:</b> '+ data.Email+'</span>';
            }
          },
          {
            "data" : {eventstatus:"eventstatus"},
            "render": function(data,type,row){
              return  data.eventstatus.charAt(0).toUpperCase() + data.eventstatus.substr(1).toLowerCase();
            }
          },
          {
            "data" : {EventDate : "EventDate"},
            "render": function(data,type,row){  
              var sp = data.EventDate.split(" ");
              var dat = moment(sp[0]).format('MMMM DD,YYYY');
              var day = moment(sp[0]).format('dddd');
                // var date = new Date(dat);
                // var daylist = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
                // var day = daylist[date.getDay()];
                // console.log(date.getMonth()+" "+date.getDate()+","+date.getFullYear());
                //return  day+">"+dat+" "+sp[1].toUpperCase();
                return  day+">"+dat;

              }
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
    }*/

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

    $(document).on('click','#EditButton',function(event){

     event.preventDefault();
     $('#wdateshidden').val('');
     $('#repeatdiv').hide();
     $('#offf').trigger('click');
     $('#eventdetailmodel').modal('hide');
     $('.id').val('')
     $(".Loader").show();

     $('#clinetdetails').show();
     
     $('#clinetdetails_note').show();
     var elink = $(this).attr('data-id');
     console.log(elink);
     $.ajax({
      dataType:"json",
      type:"post",
      data:{'elink':elink},
      url:'?action=deletefile',
      success: function(data)
      {

        if(data.resonse)
        { 
          //console.log(data.resonse);
          if(data.resonse.ProfileImg)
          {
            $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
          }
          else
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

          $('#editcustomer').val(data.resonse.cid);
          myeventedit(data.resonse.cid)

                            //console.log("------------------"  + data.resonse.cid);

                            $("#clinetdetails_note_cid").attr("value",data.resonse.cid);
                            $("#noteRelated").attr("value",data.resonse.cid);

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
                            arr[0]= moment(arr[0]).format('MM-DD-YYYY');
                            $('#eventstardate').val(arr[0]);
                            $('#eventstartime').val(arr[1]);

                            var arr2 = data.resonse.end_date.split(' ');
                            arr2[0]= moment(arr2[0]).format('MM-DD-YYYY');
                            $('#eventenddate').val(arr2[0]);
                            $('#eventendtime').val(arr2[1]);

                            $('#eventstatus').val(data.resonse.eventstatus);
                            $('#ServiceProvider').val(data.resonse.ServiceProvider);
                            $('#editServiceProvider').val(data.resonse.ServiceProvider);
                          // $('#EmailInstruction').val(data.resonse.EmailInstruction);
                          $('#EmailInstruction').data("wysihtml5").editor.setValue(data.resonse.EmailInstruction);

                          if(data.resonse.Location_radio == 'Customer Location')
                          {

                            $(".locone2").prop("checked", true);
                            $(".locone").prop("checked", false);
                          }
                          else
                          {

                           $(".locone2").prop("checked", false);
                           $(".locone").prop("checked", true);

                         }

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
                          // getsponserid(serpro)
                          $('#myModal_exit2').modal('toggle');
                        }
                        else if(data.error)
                        {
                          $(".Loader").hide();
                          swal('Something is wrong please try again');
                        }else{
                          $(".Loader").hide();
                          console.log(data.error);
                          swal('No such data found');
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
     //console.log(data.resonse);

     var i =0;
     if(data.resonse)
     {

      $(".Loader").hide();

    }

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


// $(document).on('click','#addmoreservie',function(e){
// $('#myModal_addmoreservice').show()
//   });    



});

</script>
<script src="<?php echo base_url; ?>/dist/js/lightbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js//underscore-min.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/calendar.js"></script>
<script type="text/javascript" src="<?php echo base_url; ?>/assets/js/events.js"></script>

<script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>

<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
<script src="<?php echo base_url; ?>/assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
<script>
  $(document).ready(function() {
   $('#EmailInstruction').wysihtml5();
   $('#noteDetail').wysihtml5();

   $('.sada').hide()

   $("#statauslist").click(function(){
    $('.sada').toggle()  
  });
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


   <script src="<?php echo base_url; ?>/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
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
  <script type="text/javascript" src="<?php echo base_url; ?>/assets/js/base64.js" ></script>
  <script type="text/javascript">


   $('#listofcatagory3').on('change',function(e){
     e.preventDefault();
     $('#myModal_exit2').find('h4').text('Book Appointment with '+ $("#listofcatagory3 option:selected").html());
     $('#newlistofclient').trigger('change');
   });


   $('#newlistofcatagory').on('change',function(){
    $('#listofcatagory3').html('');

    var listofcatagory=$(this).val();  
        // $("#addmoreservie").show();
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
            $('.serviceproviderblock').show()

            //console.log(data.resonse.Duration);
            var dur = data.resonse.Duration.split(' ');
            
            //console.log(dur);

            $('#duration').val(dur[0]);
            $('.minhour').text(dur[1]);
            $('#CostOfService').val(data.resonse.Price);          
            $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
            $('#sCommissionAmount').val(data.resonse.CommissionAmount); 
            $('#EmailInstruction').data("wysihtml5").editor.setValue(data.resonse.Info);
            var listarray = data.resonse.Users
            $.ajax({

              type:"post",
              data: {'UserName':listarray},
              url:'?action=editfile',
              dataType: 'json',
              success: function(data2)
              {
               if(data2)
               {

                $.each(data2.resonse, function (key, val) 
                {
                  if(val.id==$('#editServiceProvider').val())
                  {
                    $('#listofcatagory3').append('<option selected  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');      
                  }
                  else
                  {
                   $('#listofcatagory3').append('<option  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                 }

                 $(".Loader").hide();   

               });

                var service_star_time = $("#eventstartime").val();

                // $("#eventstartime").trigger("click");

                if(service_star_time!='')
                {
                  console.log("iinnnn all event2");
                  $.ajax({

                   dataType:"json",
                   type:"post",
                   data: {'service_star_time':service_star_time,'serivename':Servicename},
                   url:'?action=service_star_time',
                   success: function(data)
                   {
                    if(data)
                    {
                      $("#eventendtime").val(data.resonse);
                    }


                  }
                })
                }

              }
            }

          });
          }
          else if(data.error)
          {
            alert('ok');  
            $(".Loader").hide();   
          }
        }
      })


      });



   $('input[type="radio"]').click(function(){
    if($(this).prop("checked") == true){
      var cjecl=$(this).val();            
    }

  });

 // New Client Button Click 
 $('.myModal_new').on('click',function(event){
   event.preventDefault();
   var listofcatagory=$('#listofcatagory').val();
   $('#listofcatagory_error').text("");
   if(listofcatagory=='' || $('input[name=Location]:checked').length<=0)
   {
    $('#listofcatagory_error').text("Please select service and also location");
  }
  else if(listofcatagory!='' || $('input[name=Location]:checked').length>0)
  {
    $('#myModal').modal('hide');
    $('#myModal_new2').modal('toggle');
    $('#listofcatagory_error').hide();
  }
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
       {    

        $(".Loader").hide();   
        $(".hidddeforfirst").show();
        $(".exit-client-image").show();
        $('#FirstName').val(data.resonse.FirstName);
        $('#LastName').val(data.resonse.LastName);
        $('#Phone').val(data.resonse.Phone);
        $('#Email').val(data.resonse.email);
        $('#clinetdetails').show();
        $('#clinetdetails_note').show();
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
      $('#editcustomer').val(data.resonse.id);

             //alert(data);
             //console.log("ediiiiii" + data.resonse.id);
             $("#clinetdetails_note_cid").attr("value",data.resonse.id)
             $("#noteRelated").attr("value",data.resonse.id);
             if(data.resonse.ProfileImg)
             {
               $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.resonse.ProfileImg);
             }
             else
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

// myeventedit(ClientsName)
function myeventedit(ClientsName)
{

  $('ul#clientnotelistes').html("");
  $('#clinetdetails').hide();
  $('#clinetdetails_note').hide();
  var ClientsName=ClientsName;

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
      $('#FirstName').val(data.resonse[0].FirstName);
      $('#LastName').val(data.resonse[0].LastName);
      $('#Phone').val(data.resonse[0].Phone);
      $('#Email').val(data.resonse[0].email);
      $("#newcountry").val(data.resonse[0].Country);
      $('#clinetdetails').show();
      $('#clinetdetails_note').show();
      $('#newname').html('<b>Name :</b>' + data.resonse[0].FirstName + ' ' + data.resonse[0].LastName);
      $('#newphone').html('<b>Cell : </b>' + data.resonse[0].Phone);
      $('#newemail').text(data.resonse[0].email);

      $('.SelectPackageliv').html('')
      $.each(data.resonse, function( index, value ) 
      {
        if(value.Name!=null)
        {
          $('.SelectPackageliv').append('<button data-id="'+ value.cpackagid +'"  class="btn btn-info m-r-10 SelectPackage" value="'+value.Noofvisit+'" type="button" id="SelectPackage">'+value.Name +'</button>');  
        }
        else
        {
         $('.SelectPackage_not').show(); 
         $('.SelectPackageliv').append('<span class=".SelectPackage_not">No package selected</span>')

       }
     });


      if(radioValue=='Customer Location')
      { 

        var useradd = $('#listofcatagory3').val(); 

        if(useradd == null)
        {
         var useradd= '<?php echo $_SESSION['UserID']; ?>';
       }

       $("#ServiceProvider").val(useradd);  
       $('#Address').val(data.resonse[0].Address);
       $('#Zip').val(data.resonse[0].Zip);
       $('#City').val(data.resonse[0].City);
       $('#State').val(data.resonse[0].State);
       $('#newcountry').val(data.resonse[0].Country);

     }
     else
     {

       if($('#NewEvent input[name="id"]').val() == 'new'){
        var useradd = $('#listofcatagory3').val(); 
        if(useradd == null)
        {
         var useradd = '<?php echo $_SESSION['UserID']; ?>';
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

   $('#cid').val(data.resonse[0].cid);

   $('#editcustomer').val(data.resonse[0].cid);
           //console.log(data.resonse);
           //console.log("nnnnnnnnnnn " + data.resonse[0].cid);
           $("#clinetdetails_note_cid").attr("value",data.resonse[0].cid)
           $("#noteRelated").attr("value",data.resonse[0].cid);

           if(data.resonse[0].ProfileImg)
           {
            console.log(data.resonse.ProfileImg);
            $("#clientimage").attr("src","<?php echo  base_url.'/assets/ProfileImages'?>/"+data.resonse[0].ProfileImg);
          }
          else
          {
           $("#clientimage").attr("src","<?php echo  base_url.'/assets/images/noimage.png'; ?>");   

         }
       }

       if(data.resonse_note != '')
       {

        $.each( data.resonse_note, function( key, value ) {
          $('.clientnotelistes').show();
          $('ul#clientnotelistes').append("<li><a href='#' class='edit_note' id='edit_note' data-id="+btoa(value.id)+">"+value.noteTitle+"</a></li>")
        });
      }
      else if(data.error)
      {
        swal("Oops...", "Something went wrong!", "error");
      }

    }
  })

}

$('#newlistofclient').on('change',function(){

 var ClientsName=$(this).val();


 myeventedit(ClientsName)

});
$(document).on("click","#SelectPackage",function() {
  $("#listofavliaasfpackag2").html("");
  if(this.value == 0)
  {
    $("#remservice").html("NA");
    $("#remservice").attr("data-id","");

    $("#selser").fadeOut();
  }
  else
  {
    $("#remservice").html(this.value);
    $("#remservice").attr("data-id",$(this).attr("data-id"));
    $("#selser").fadeIn();
  }

  var pakcagidc2 = $(this).attr("data-id")
  $.ajax({
    url:'?action=editfile',
    type: "POST",
    data:{"pakcagidc2": pakcagidc2,},
    dataType:"json",
    success:function(data)
    {
      $('.listofavliaasfpackag2').append('<option value="">Selectect service</option>'); 
      $.each(data.resonse2, function( index, value ) 
      {

        $('.listofavliaasfpackag2').append('<option value='+value.id+'>'+value.ServiceName+'</option>');

      });

    }
  });


});

function cleardiv()
{
  $("#remservice").html("");
  $("#selser").hide();
  $("#listofavliaasfpackag2").html();

}
function redi()
{
    /*var client = $("#newlistofclient").val();
    var package = $('#remservice').attr('data-id');
    var service = $("#listofavliaasfpackag2").val();
    var sname = $('#select2-listofavliaasfpackag2-container').text();
    var remain = $("#remservice").html();*/
    var obj = { "client" : $("#newlistofclient").val() , "package" : $('#remservice').attr('data-id') , "service" : $("#listofavliaasfpackag2").val() , "sname" : $('#select2-listofavliaasfpackag2-container').text() , "remain" : $("#remservice").html()-1};
    var json=JSON.stringify(obj);
    var url = $.base64.encode(json);
    window.setInterval(function() {
      window.location.href = "https://mysunless.com/Order?data="+url;
    }, 500);
  }
  function addbtn()
  {
    if($('#select2-listofavliaasfpackag2-container').text() != "Choose service")
    {
      $("#addser").html('<a class="btn btn-info"  href="javascript:redi();"  >Checkout</a>');
    }
  }
</script>
<script type="text/javascript">
 $(document).ready(function(){
  $("#selser").hide();
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

      jQuery.validator.addMethod("time_valid", function (value, element) { 
        var date=$("#eventstardate").val();
        if(date==moment().format("MM-DD-YYYY")){
          current_time = moment(moment().format("hh:mma"), 'hh:mma').diff(moment().startOf('day'), 'seconds');
          set_time = moment(value, 'hh:mma').diff(moment().startOf('day'), 'seconds');
          if(set_time>=current_time){
            return true;
          }else{
            return false
          }
        }else{
         return true;
       }
     }, "Set time after the current time.");


      $(document).mouseup(function(e) 
      {
        var container = $("#time_slot");

        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
          container.hide();
          if($("#eventstartime").val()==""){
            $("#eventstartime").attr("placeholder","Click here to select Start Time");
          }
        }
      });

      $(document).on("click","#time_slot .close, #newlistofcatagory, #listofcatagory3, #eventstardate",function(){
        $("#time_slot").hide();
        $("#eventstartime").val("");
        $("#eventstartime").attr("placeholder","Click here to select Start Time");
      });

      $("#eventstartime").click(function(){
        $("#time_slot").hide();
        
        $("#eventstartime").val("");
        $("#eventstartime").attr("placeholder","Loading available time...");

        $.ajax({
          url:'<?php echo EXEC; ?>Exec_Edit_Event.php?get_service_time',
          type:"post",
          data:{
            service_provider: $("#listofcatagory3").val(),
            service_date: $("#eventstardate").val(),
            duration: $("#duration").val()+" "+$('.minhour').text()
          },
          success:function(data){
            $("#time_slot").html("");
            data = JSON.parse(data);
            if(data.response){
              $("#time_slot").append("<label class='time_slot_header'><a href='#' class='close' aria-label='close'>&times;</a><h4>Available Time</h4></label>");
              
              var service_date = $("#eventstardate").val();

              for(i=0;i<data.response.length;i++){

                time = data.response[i].split("-");
                start = time[0];
                end = time[1];

                if(service_date==moment().format("MM-DD-YYYY")){

                  current_time = moment(moment().format("hh:mma"), 'hh:mma').diff(moment().startOf('day'), 'seconds');

                  set_time = moment(start, 'hh:mma').diff(moment().startOf('day'), 'seconds');

                  if(set_time>=current_time){
                    $("#time_slot").append("<div class='col-md-3 time_slot_box' data-id='"+end+"'>"+start+"</div>");
                  }

                }else{
                 $("#time_slot").append("<div class='col-md-3 time_slot_box' data-id='"+end+"'>"+start+"</div>");
               }
             }
           }else if(data.error){
            $("#time_slot").html(data.error);
            $("#eventstartime").val("");
          }else{

          }
          $("#time_slot").css('display','flex');
        }
      });
      });

      $(document).on("click",".time_slot_box",function(){
        console.log("run");
        $("#eventstartime").val($(this).text());
        $("#eventendtime").val($(this).attr("data-id"));
        $("#time_slot").hide();
        $("#time_slot").html("");
      }); 

      $("#NewEvent").validate({

        ignore: ":hidden:not(textarea)",
        rules: {                

          newlistofcatagory: {required:true,},
          listofcatagory3: {required:true,},
          newlistofclient: {required:true,},
          sd: {required: true,},
          st: {required: true,
            time_valid: true
            ,remote:{
              url:'<?php echo EXEC; ?>Exec_Edit_Event.php?check_service_time',
              type:"post",
              data:{
                id:function(){
                  return $("#NewEvent #id").val();
                },
                service_provider: function() {
                  return $("#listofcatagory3").val();
                },
                service_date: function() {
                  return $("#eventstardate").val();
                },
                service_time_start: function() {
                  return $("#eventstartime").val();
                },
                service_time_end: function() {
                  return $("#eventendtime").val();
                }
              }
            }
          },
          ed: {required: true,},
          et: {required: true,},
          eventstatus: {required:true,},
          dendate:{required:true,},
          wendate:{required:true,},
          mendate:{required:true,},
          yendate:{required:true,},
          mday:{required:true,},
          ydate:{required:true,},
          CostOfService: {required: true,number: true},
          EmailInstruction: {required: true,},

        },


        messages: {             

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

          CostOfService: {required: "Please enter cost of service",
          number: "Please enter valid price",
        },
        EmailInstruction: {required: "Please enter Appointment Note"},

      },

      errorPlacement: function( label, element ) {
        if( element.attr( "name" ) === "EmailInstruction" ) {
          element.parent().append( label );
        } else if ( element.attr( "name" ) === "sd" || element.attr( "name" ) === "ed"  || element.attr( "name" ) === "et" ) {
         element.parent().parent().append( label );
       }else if(element.attr( "name" ) === "st"){
         element.parent().parent().append( label );

       }else{
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

      var data = $("#NewEvent").serialize();
      data= data + "&LoginAction=Login";
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
           // $(".Loader").hide();
           var enewid = $("#id").val();
           var googlesync = '<?php echo $googlesync; ?>';
           if(enewid == "new" && googlesync==1)
           {
            window.location.href = "<?= base_url?>"+"/googlcel/home.php";
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


var windowSize = $(window).width(); // Could've done $(this).width()
if(windowSize<768)
{
  $('.daydash').trigger('click');
}

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


        var customersid2 = customersid;

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
             $('#Address').val(data.resonse.Address);
             $('#example-email').val(data.resonse.email)
             $('#autocomplete').val(data.resonse.Address)
             $('#street_number').val(data.resonse.Address)
             $('#postal_code').val(data.resonse.Zip)
             $('#country').val(data.resonse.Country) 
             $('#administrative_area_level_1').val(data.resonse.State)
             $('#locality').val(data.resonse.City)
             $('#oldimage').val(data.resonse.ProfileImg)
             if(data.resonse.ProfileImg !== '' && data.response.ProfileImg == null)
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
              console.log(data);   
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
                      //console.log(data.resonse);
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

                        if($('#id').val() == 'new')
                        {
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

                      if(data.mydata.ProfileImg)
                      {
                       $("#clientimage").attr("src","<?= base_url.'/assets/ProfileImages'?>/"+data.mydata.ProfileImg);
                     }
                     else if(data.mydata.ProfileImg == '')
                     {
                      $("#clientimage").attr("src","<?= base_url.'/assets/images/noimage.png';?>");   
                    }else{
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

                    if($('#id').val() == 'new')
                    {
                          // $('#exit-client').trigger('click');
                          // $('#listofclient').val(data.mydata.id).trigger('change');
                          // $('#listofclient').select2(); 
                        }

                        else
                        {

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


$("#NewEvent_todo").validate({
  rules: {
    noteTitle: {
      required: true,}
      ,
      noteDetail: {
        required: true,}
        ,
        "noteRelated[]": {
          required: true,}
          ,
        }
        ,
        messages: {
          noteTitle: {
            required: "Please enter note title"}
            ,
            noteDetail: {
              required: "Please enter note detail"}
              ,
              "noteRelated[]": {
                required: "Please relate the note to a contact"}
                ,
              }
              ,
              ignore: ":hidden:not(textarea)",
              errorPlacement: function( label, element ) {
                if( element.attr( "name" ) === "noteDetail"  || element.attr( "name" ) === "noteRelated[]") {
                  element.parent().append( label );
                }
                else {
                  label.insertAfter( element );
                }
              }
              ,
              submitHandler: function() {
                $(".Loader").show();
                var data = $("#NewEvent_todo").serialize();
                $data= data + "&Action=note";
                jQuery.ajax({
                  dataType:"json",
                  type:"post",
                  data:data,
                  url:'<?php echo EXEC; ?>Exec_Edit_Note.php',
                  success: function(data)
                  {
                    if(data.resonse)
                    {
                      $("#resonse_note").show();
                      $('#resonsemsg_note').html('<span>'+data.resonse+'</span>');
                      $( '#NewNote' ).each(function(){

                        this.reset();
                        $("#clientnotelistes").load(location.href + "#clientnotelistes");

                      });
                      
                      if($("#clientnotelistes").find("a[data-id='"+btoa(data.mydata.id)+"']").length){
                        $($("#clientnotelistes").find("a[data-id='"+btoa(data.mydata.id)+"']")).text(data.mydata.noteTitle);
                      }else{
                        $('ul#clientnotelistes').append("<li><a href='#' class='edit_note' id='edit_note' data-id="+btoa(data.mydata.id)+">"+data.mydata.noteTitle+"</a></li>");
                      }
                      

                      
                      $(".Loader").hide();
                      $('#appointmentwithnote').modal('hide');
                      $('#myModal_listofnote').modal('hide');


                    }
                    else if(data.error)
                    {
                      $("#error_note").show();
                      $('#errormsg_note').html('<span>'+data.error+'</span>');
                      $(".Loader").hide();
                                                    // alert('<li>'+data.error+'</li>');
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

  function myFunction3() {
    document.getElementById("myDropdown_cal3").classList.toggle("show_cal");
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


    $('#newlistofcatagory').select2({
                    //alert("asdasd");

                          // "language": {
                          //     "noResults": function(){
                          //         return "No Results Found <a href='#' class='btn btn-danger'>Use it anyway</a>";
                          //     }
                          // },
                          //   escapeMarkup: function (markup) {
                          //       return markup;
                          //   }
                        });

    $(document).on('click','.ClientLimit',function(){

      swal("Your Client Limit is over!!", "For Upgrade your limit contact to Admin", "warning");
    });

  });
</script>

<script type='text/javascript'src='<?php echo base_url ?>/assets/js/timepicki.js'></script>
<script>
//  $('#datepairExample .time').timepicker({
//   'showDuration': true,
//   'timeFormat': 'g:ia'
// });

// $('#eventstartime').timepicki();

$('#eventstardate').datepicker({
  format: "mm-dd-yyyy",
  'autoclose': true,
  startDate: '-0d',
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

        var today_date = today_date.split('-');
        var today_date = today_date[1] + '-' + today_date[2] + '-' + today_date[0].slice();
        

        $(".today_date").text(today_date);
        $(".tohsedata").attr('data-current-cal-date',today_date);

        $('#eventstardate').val(today_date); 
        $('#eventenddate').val(today_date);

        //$(this).trigger('dblclick');  

      });  
      $(document).on('click', '#addnewappointment', function(e){
       $('#editspan').hide();
       $('#repeatdiv').show();


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
              $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
            });
            $(".Loader").hide();
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

      $(document).on('click', '.cal-day-hour-part', function(e){
        $('#editspan').hide();
        $('#repeatdiv').show();
        var today_time = $(this).find('.span1').find('b').text();
        var today_time = today_time.toLowerCase()
        
        $('#eventstartime').val(today_time);
        $('#NewEvent input[name="id"]').val('new');
        $('#CostOfService').val('');
        $('#EmailInstruction').val('');
        $('#eventstatus option:nth-child(1)').attr('selected','selected');
        $('#myModal_exit2').modal('show');
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
                $('#newlistofclient').append('<option value="'+value.id+'">'+value.FirstName+' '+value.LastName +'</option>');
              });
              $(".Loader").hide();
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

    //  $('#offrepeat').click(function() {
    //    $("#eventstardate").trigger('change');
    // });

    // $('#daily').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#weekly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#monthly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

    // $('#yearly').click(function() {
    //   $("#eventstardate").trigger('change');
    // });

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
      emptydata();       
    });

    // $("#eventstartime").change(function(){
    //   var service_star_time = $(this).val(); 
    //   var serivename=  $("#ServiceName").val();

    //         //$(".Loader").show();
    //         $.ajax({

    //          dataType:"json",
    //          type:"post",
    //          data: {'service_star_time':service_star_time,'serivename':serivename},
    //          url:'?action=service_star_time',
    //          success: function(data)
    //          {
    //           if(data)
    //           {

    //                   //$(".Loader").hide();
    //                   //location.reload();
    //                   $("#eventendtime").val(data.resonse);
    //                 }
    //                 else if(data.error)
    //                 {
    //                   alert('ok');  
    //                 }

    //               }
    //             })

    //       });


    /*$(document).on("change","#eventstartime",function(){ 
      setTimeout(function() {
        var service_star_time = $("#eventstartime").val();
        var serivename=  $("#ServiceName").val();
        EventStartFunction(service_star_time,serivename);
      },100);

    });*/

    $(document).on("click","#eventstartime",function(){

    });

    function EventStartFunction(service_star_time,serivename){
      $.ajax({

       dataType:"json",
       type:"post",
       data: {'service_star_time':service_star_time,'serivename':serivename},
       url:'?action=service_star_time',
       success: function(data)
       {
        if(data)
        {
          //console.log(data.resonse);
          $("#eventendtime").val(data.resonse);
        }
        else if(data.error)
        {
          //alert('ok');
          console.log("error in select end date");  
        }

      }
    });
    }


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

      /*$(document).ready(function(){
        var i = 0;
        console.log(i);
        i = parseInt(i)+1;
      });*/
     //counter for page  
    /*$(document).ready(function(){
      var counter = ;  
      var uname = "count";
      document.cookie = uname + "=" + counter + ";path=/";
      var x = document.cookie;
      var res =x.replace(" ","");
      var x1 = res.split(";");
      var final = x1[5].split("=");
      var counte = parseInt(final[1]) + 1;
      counter = counte;
      console.log(counte);
    });*/
    /*var counter=;*/
    $(document).ready(function(){
      var n = localStorage.getItem('pageloadcounter');
      if (n === null) {
        n = 0;
      }
      n++;
      localStorage.setItem("pageloadcounter", n);
      console.log(n);
      /* I've made some changes into top navigation bar. this issue is only in top navigation bar if user selects side navigation bar from the settings then user will not find this issue in that.Issue is there is "" */
      /*var n = counter;
      if (n === null) {
        n = 0;
      }
      n++;
      var uname = "count";
      document.cookie = uname + "=" + n + ";path=/";
      var x = document.cookie;
      console.log(x);
      counter = n;*/
      /*var counter = parseInt(counter) + 1*/;
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
     $('.Loader').show();
     $("#eventdetailmodel").modal('show');
     var customername = $(this).attr("data-clientname");
     $(".evnetcutomername").text(customername);

     var UserName = $(this).attr("data-username");
     $(".evnetemployename").text(UserName);

     var envettitle = $(this).attr("data-title");
     $(".envetsername").text(envettitle);

     var evnetstarttime = $(this).attr("data-startime");
     $(".evnetstartime").text(evnetstarttime);

     var evneturl = $(this).attr("data-eventurl");
     var evtd = evneturl.split('&');
     var evtid = evtd[1].split('=');
     $(".ViewEvent").attr("data-id",evtid[1])


     var customerurl = $(this).attr("data-viewclient");
     var clitd = customerurl.split('&');
     var clitd = clitd[1].split('=');

     $("#noteRelated").attr("value",window.atob(clitd[1]))


     var customerurl = $(this).attr("data-viewclient");
     $("#ViewCustomer").attr("href",customerurl)


     var checkouturl = $(this).attr("data-chekout");
     var servicename = $(this).attr("data-sercivename");
     var stime = $(this).attr("data-stime");
     var ServiceProvider = $(this).attr("data-serviceprovider");

     var clitd2 = checkouturl.split('&');
     var clitd2 = clitd2[1].split('=');
     $("#noteRelated").attr("value",window.atob(clitd2[1]))

     $("#checkout").attr("href",checkouturl+'?&servicename='+servicename+'&stime='+window.btoa(stime)+'&ServiceProvider='+ServiceProvider)


     var deleteevent = $(this).attr("data-deletevent");
     // $("#DeleteEvent").attr("href",deleteevent);

     $(document).on('click','#DeleteEvent',function(e){
      e.preventDefault();
      swal({
        title: "Are you sure?",
        text: "Once deleted, you will lost all data of this Client account!",
        icon: "warning",
        buttons: true,
      }).then((willDelete)=>{   
        if (willDelete){
          window.location = deleteevent;
        }
        else{
          return false ;
        }
      });
    });

       // var editevent = $(this).attr("data-editevent");
       // $("#ViewEvent").attr("href",editevent)

       var eventadders = $(this).attr("data-eventadders");
       $("#eventaddermap").attr("src",eventadders)


       var onlyeventadders = $(this).attr("data-onlyeventadders");
       $("#onlyeventadders").text(onlyeventadders)

       var myeventid = $(this).attr("data-myeventid");

       $("#printappoiment").attr("data-id",myeventid);

       $(".radio-custom").attr("data-eventid",myeventid);  

       var estatus = $(this).attr("data-estatus");

       if(estatus=='event-grey')
       {
         $("#radio-1").attr("checked","checked");  
       }
       else if(estatus=='event-success')
       {
         $("#radio-2").attr("checked","checked");  
       }

       else if(estatus=='event-important')
       {
         $("#radio-3").attr("checked","checked");  
       }

       else if(estatus=='event-red')
       {
         $("#radio-4").attr("checked","checked");  
       }
       else if(estatus=='event-warning')
       {
         $("#radio-5").attr("checked","checked");  
       }
       else if(estatus=='event-info')
       {
         $("#radio-6").attr("checked","checked");  
       }


        // $("#onlyeventadders").text(estatus)
        

        var elocation = $(this).attr("data-location");

        if(elocation == 'Salon Location')
        {
          $(".cusomerlocation").hide();
        }
        $('.Loader').hide();
        
      });


    $('input[name="radio-group"]').click(function(){
      $(".Loader").show();
      if ($(this).is(':checked'))
      {
        var newstat = $(this).val();
        var myeventid = $(this).attr("data-eventid");
        
        if(newstat=='canceled')
        {
          $('#myModal_appoimentcenletion').modal('show')
          $(".Loader").hide();
        }
        else
        {
         $.ajax({
          async: true,
          dataType:"json",
          type:"post",
          data: {'newstat':newstat,'myeventid':myeventid},
          url:'?action=newstat',
          success: function(data)
          {
            if(data)
            {

              $(".Loader").hide();
              swal('Appointment status successfully has been change');
              setTimeout(function () { window.location.reload() }, 3000);
            }
            else if(data.error)
            {
              alert('ok');  
            }

          }
        })
       }


     }
   });

    $(document).on('click','#Notifytocustomer',function(e){
     e.preventDefault();  

     var commnetonet = $('#appointmentcancelcomment').val()
     var commnettwot =  $('#appointmentcancelcommentforcusteomr').val()  
     var myeventid = $('#radio-3').attr("data-eventid");  
     var newstat2 = $('#radio-3').val();

     if ($('input.event_carryford').is(':checked')) 
     {
       var eventcustohistory ='yes' 
     }
     else
     {
       var eventcustohistory ='no'  
     }


     $.ajax({
      async: true,
      dataType:"json",
      type:"post",
      data: {'commnetonet':commnetonet,'commnettwot':commnettwot,'myeventid':myeventid,'newstat2':newstat2,'eventcustohistory':eventcustohistory},
      url:'?action=newstat',
      success: function(data)
      {
        if(data)
        {

          $(".Loader").hide();
          swal('Appointment status successfully has been change');
          setTimeout(function () { window.location.reload() }, 3000);
        }
        else if(data.error)
        {
          alert('ok');  
        }

      }
    })


   });



    $(document).on('click','#dontNotifytocustomer',function(e){
     e.preventDefault();  

     var commnetonet_1 = $('#appointmentcancelcomment').val()
     var commnettwot_1 =  $('#appointmentcancelcommentforcusteomr').val()  
     var myeventid_1 = $('#radio-3').attr("data-eventid");  
     var newstat2_1 = $('#radio-3').val();

     if ($('input.event_carryford').is(':checked')) 
     {
       var eventcustohistory_1 ='yes' 
     }
     else
     {
       var eventcustohistory_1 ='no'  
     }


     $.ajax({
      async: true,
      dataType:"json",
      type:"post",
      data: {'commnetonet_1':commnetonet_1,'commnettwot_1':commnettwot_1,'myeventid_1':myeventid_1,'newstat2_1':newstat2_1,'eventcustohistory_1':eventcustohistory_1},
      url:'?action=newstat',
      success: function(data)
      {
        if(data)
        {

          $(".Loader").hide();
          swal('Appointment status successfully has been change');
          setTimeout(function () { window.location.reload() }, 3000);
        }
        else if(data.error)
        {
          alert('ok');  
        }

      }
    })


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
