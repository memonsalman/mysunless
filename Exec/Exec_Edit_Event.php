<?php

require_once('Exec_Config.php');        


require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.event.php'); 
// require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/sms/twilio-php-master/Twilio/autoload.php');
require_once('Exec_Send_SMS.php');


$db3 = new db();
$decode_userid = 0;


if(isset($_REQUEST['DeleteEvent'])){

    $myevent = base64_decode($_REQUEST['dlink']);
    $date = date('Y-m-d h:i:s');
   $DeleteClient = $db->prepare("Update `event` set isactive=0,datelastupdated=:date where id=:myevent");
   $DeleteClient->bindValue(":myevent",$myevent);
   $DeleteClient->bindValue(":date",$date);
   $run = $DeleteClient->execute();

   if($run && $DeleteClient->rowCount()>0){

     $DeleteClientA = $db->prepare("DELETE from `CountActivites` where AppointmentCreate=:myevent");
     $DeleteClientA->bindValue(":myevent",$myevent,PDO::PARAM_INT);
     $DeleteClientA->execute();
     echo json_encode(['response'=>'Appointment successfully deleted']);die;
   }else{
     echo json_encode(['error'=>'Something went wrong.']);die;
   }
}

if(isset($_REQUEST['AppointmentRequest'])){

 @$selAppoEvent = new event();
 $getAllEvent = $selAppoEvent->selectNotAcceptAppointment();

 echo $getAllEvent;die;

}

if(isset($_GET['appoitHis']))
{
  $ueventid = $_GET['appoitHis'];
  
  @$selAppoHis = new event();
  $getAllAppHis = $selAppoHis->selectAppoHistory($ueventid);
  echo $getAllAppHis;die;
}



if(isset($_GET['noteHis']))
{
  $unoteid = $_GET['noteHis'];

  @$selNoteHis = new event();
  $getAllNoteHis = $selNoteHis->selectNoteHistory($unoteid);
  echo $getAllNoteHis;die;
}

if(isset($_GET['communicationHis']))
{
  $uComid = $_GET['communicationHis'];

  @$selComHis = new event();
  $getAllComHis = $selComHis->selectCommunicateHistory($uComid);

  echo $getAllComHis;die;
}



if(isset($_GET['orderHis']))
{
  $uOrderid = $_GET['orderHis'];

  @$selOrderHis = new event();
  $getAllOrderHis = $selOrderHis->selectOrderHistory($uOrderid);

  echo $getAllOrderHis;die;
}

if(isset($_GET['packageHis']))
{
  $uPackageid = $_GET['packageHis'];

  @$selPackageHis = new event();
  $getAllPackageHis = $selPackageHis->selectPackageHistory($uPackageid);

  echo $getAllPackageHis;die;
}


if(isset($_GET['fileuploadHis']))
{
  $uFileuploadId = $_GET['fileuploadHis'];

  @$selFileuploadHis = new event();
  $getAllFileuploadHis = $selFileuploadHis->selectFileUploadHistory($uFileuploadId);

  echo $getAllFileuploadHis;die;
}


if(isset($_GET['fileuploadDel']))
{
  $fileuploadDel = $_GET['fileuploadDel'];

  @$delFileuploadHis = new event();
  $getFileUploadDelete = $delFileuploadHis->fileUploadDelete($fileuploadDel);

  echo $getFileUploadDelete;die;
}


if(isset($_GET['printdata']))
{
  @$priEveId = $_GET['printdata'];

  @$printEvent = new event();
  $selPrintEventData = $printEvent->selectEventPrintInfo($priEveId);
  echo $selPrintEventData;die;
}


// if(isset($_POST['pending_appointment'])){

//     $pending_appointment = json_decode($_POST['pending_appointment']);
//     $db = new db();
//     $UserID = $_SESSION['UserID'];

//     //$query = $db->prepare("SELECT id as pending FROM `event` WHERE createdfk IN (select id from users where id=:id or adminid=:id or sid=:id) and eventstatus='pending' and Accepted='1' ");
//     $sub_query = '(select id from users where id=:id or adminid=:id or sid=:id)';
//     $query1 = $db->prepare("SELECT GROUP_CONCAT(id SEPARATOR ',') as pending_id FROM `event` WHERE (createdfk IN $sub_query or ServiceProvider IN $sub_query) and eventstatus='pending' and (Accepted='1' or Accepted='0') ");

//     $query1->bindValue(':id', $UserID, PDO::PARAM_INT);
//     $query1->execute();
//     $result1=$query1->fetch(PDO::FETCH_ASSOC);
//     $pending = explode(",",$result1['pending_id']);

//     if(count($pending_appointment)==0)
//     {
//         echo json_encode(["response"=>"diffent","data"=>$pending]);die;
//     }

//     if( count($pending)!=count($pending_appointment)){

//     $query2 = $db->prepare("select clients.ProfileImg ,event.* FROM `event` join clients on clients.id=event.cid WHERE (event.createdfk IN $sub_query or event.ServiceProvider IN $sub_query) and (event.eventstatus='confirmed' or event.eventstatus='canceled') and event.Accepted='1' and FIND_IN_SET(event.id,:pending_id) ");

//     $query2->bindValue(':id', $UserID, PDO::PARAM_INT);
//     $query2->bindValue(':pending_id', implode(",",$pending_appointment));
//     $query2->execute();
//     $result2=$query2->fetchAll();

//     if(count($pending) > count($pending_appointment)){

//     $diff = implode(',',array_diff($pending,$pending_appointment));

//     $query3 = $db->prepare("select clients.ProfileImg ,event.* FROM `event` join clients on clients.id=event.cid WHERE event.id IN ($diff) ");
//     $query3->execute();
//     foreach ($query3->fetchAll() as $value) {
//         array_push($result2,$value);
//     }
// }

//     echo json_encode(["response"=>"diffent","data"=>$pending,"user"=>$result2]);die;
//     }else{
//         echo json_encode(["response"=>"same"]);die;
//     }

// }




if(isset($_POST['myappid']))
{
    $decode_userid = base64_decode($_POST['myappid']);
    @$approveAppoEvent = new event();
    $statusEvent = '1';
    $getResEvent = $approveAppoEvent->approveAppointment($decode_userid,$statusEvent);
    

    if($getResEvent > 0)
    {

        $getAllAppoitment = $approveAppoEvent->selectSpecificAppointment($decode_userid);
        $status = "approved";
        $resMsg = sendAppointmentMailSMS($getAllAppoitment,$status);
        echo $resMsg;die;
    }
    
}


if(isset($_POST['deleteappid']))
{



    $decode_userid = base64_decode($_POST['deleteappid']);

    @$cancelEvent = new event();
    $statusEvent = '2';
    $getResEvent = $cancelEvent->approveAppointment($decode_userid,$statusEvent);
    

    if($getResEvent > 0)
    {

        $getAllAppoitment = $cancelEvent->selectSpecificAppointment($decode_userid);
        $status = "cancel";
        $resMsg = sendAppointmentMailSMS($getAllAppoitment,$status);
        echo $resMsg;die;
    }
    
}



function sendAppointmentMailSMS($getAllAppoitment,$status){
    // id: "532"
    // FirstName: "Maxine"
    // LastName: "Griffith"
    // Phone: "+1 (941) 816-5161"
    // Email: "sefiqucaj@mailinator.com"
    // EventDate: "2020-04-25 07:09pm"
    // eventstatus: "pending"
    // Address: ""
    // Zip: "87074"
    // City: "Obcaecati veniam vi"
    // State: "Connecticut"
    // country: "United States"
    // CostOfService: "32"
    // EmailInstruction: "demo"
    // datecreated: "2020-04-16 16:07:18"
    // datelastupdated: "2020-04-16 16:07:18"
    // createdfk: "666"
    // updatedfk: "666"
    // isactive: "1"
    // title: "demo"
    // start_date: null
    // end_date: "2020-04-25 11:09pm"
    // description: null
    // Appcanmsgfcus: null
    // UserID: "666"
    // ServiceName: "130"
    // ServiceProvider: "700"
    // cid: "657"
    // Location_radio: "Customer Location"
    // Accepted: "1"

        //print_r($getAllAppoitment);

    @$ServiceProvider = $getAllAppoitment[0]['ServiceProvider'];
    @$id=$_SESSION['UserID'];
    $db = new db();
    @$user = $_SESSION['UserName'];
    @$Email = $getAllAppoitment[0]['Email'];
    @$cid = $getAllAppoitment[0]['cid'];
    @$FirstName = $getAllAppoitment[0]['FirstName'];
    @$LastName = $getAllAppoitment[0]['LastName'];
    @$EmailInstruction = $getAllAppoitment[0]['EmailInstruction'];
    @$stat_time = $getAllAppoitment[0]['EventDate'];
    @$end_time = $getAllAppoitment[0]['end_date'];
    @$Address = $getAllAppoitment[0]['Address'];
    @$Price = $getAllAppoitment[0]['CostOfService'];
    @$Zip = $getAllAppoitment[0]['Zip'];
    @$City = $getAllAppoitment[0]['City'];
    @$State = $getAllAppoitment[0]['State'];
    @$country = $getAllAppoitment[0]['country'];
    @$eid = $getAllAppoitment[0]['id'];
    @$title = $getAllAppoitment[0]['title'];
    @$EventDate = $getAllAppoitment[0]['EventDate'];
    @$getdate=$getAllAppoitment[0]['EventDate'];
    @$stat_data2 = explode(" ",$getdate); 
    @$stat_data = $stat_data2[0];
    @$Phone = $getAllAppoitment[0]['Phone'];

    $query = $db->prepare("SELECT users.firstname,users.lastname,users.phonenumber,users.email FROM users WHERE users.id=:ServiceProvider");
    $query->bindValue(':ServiceProvider', $ServiceProvider, PDO::PARAM_INT);
    $query->execute();
    $res = $query->fetch(PDO::FETCH_ASSOC);

    
    $queryimag = $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=$id");
    $queryimag->execute();
    $resimag = $queryimag->fetch(PDO::FETCH_ASSOC);


    // $stCountry = $db->prepare("SELECT * FROM `countries` WHERE countries_name=:Country");
    // $stCountry->bindParam(':Country',$country, PDO::PARAM_STR);
    // $stCountry->execute();
    // $resCountry = $stCountry->fetch(PDO::FETCH_ASSOC);
    // $isdcode = $resCountry['countries_isd_code'];

    
    // End Edited

    

    if(isset($_SESSION["UserID"])){
        $appointment_id = date('Ymd').'-'.$eid;
        $other['--APP_NO--'] = $appointment_id;
        $other['--USERNAME--'] = $user;
        $other['--SERVICE--'] = $title;
        $other['--PRICE--'] = $Price;
        $other['--TITLE--'] = $res['firstname']." ".$res['lastname'];
        $other['--MESSAGE--']="";

        if($_SESSION['usertype']=='subscriber'){
         $userid = $_SESSION['UserID'];
     }else{
         $userid = $_SESSION['adminid'];
     }

     $bookurl = $other['--BOOKING_URL--'] = base_url."/Book-now?ref=".base64_encode($userid);


     $resimag['Phone'];

     $resimag['CompanyName'];


     if($resimag['compimg'])
     {
        $other['--IMG--'] = $resimag['compimg'];    
    }
    else
    {
        $other['--IMG--'] = 'maillogo.png';       
    }
    $other['--EMAIL--'] = $Email;
    $other['--FIRSTNAME--'] = $FirstName;
    $other['--LASTNAME--'] = $LastName;
    $other['--EI--'] = $EmailInstruction;
    $other['--EDATA--'] = $stat_time;
    $other['--ENDATA--'] = $end_time;
    $other['--ADD--'] = $Address;
    $other['--ZIP--'] = $Zip;
    $other['--CITY--'] = $City;
    $other['--STA--'] = $State;
    $other['--MAP--'] = str_replace(" ","+",$Address.",".$City.",".$State.",".$Zip.",".$country);
    $other['--EventId--'] = $eid;

    $sms="";

    $other['--ONLYDATE--'] = date("F-jS-Y", strtotime($stat_data));

    if($resimag['CompanyName'])
    {
        $other['--COMPNAME--'] = $resimag['CompanyName'];
    }
    else
    {
        $other['--COMPNAME--'] = '';
    }

    if($resimag['Phone'])
    {
        $other['--COMPNUMBER--'] = $resimag['Phone'];    
    }
    else
    {
        $other['--COMPNUMBER--'] = '';       
    }

    if($resimag['email'])
    {
        $other['--COMPEMAIL--'] = $resimag['email'];    
    }
    else
    {
        $other['--COMPEMAIL--'] = '';       
    }

    $headers = '';
    $message="Hi ";

    if($status == "cancel")
    {
     $other['--UserName--'] = $res['firstname']." ".$res['lastname'];;
     $other['--daate--'] = $getdate;
     $other['--phonenumber--'] = $res['phonenumber'];
     $other['--mymail--'] = $res['email'];


     $sms = "Hi, ".ucfirst($FirstName)." ".ucfirst($LastName)." your appointment($appointment_id) of '$title' has been canceled on $EventDate. \n\n Re-Book: $bookurl \n\n For further queries contact on ".$res['email']." or ".$res['phonenumber'];
     $Email_Response = sendEventMailForcanl($Email, "Appointment Cancellation!", "EventCan.php", $message, $headers, $other);    


     $Event_Response = 'Appointment has been successfully deleted';
 }
 else{

     $sms="Hi, ".ucfirst($FirstName)." ".ucfirst($LastName)." your appointment($appointment_id) of '$title' schedule on $EventDate .\n\n 1. Confirm: https://mysunless.com".ESUB."/allcamscriptcron.php?eidforyes=$eid \n\n 2. Cancel: https://mysunless.com".ESUB."/allcamscriptcron.php?eidforno=$eid \n\n 3. Reschedule: https://mysunless.com".ESUB."/allcamscriptcron.php?notify&eidforapply=$eid . \n\n Location: https://www.google.com/maps/place/".$other['--MAP--']." \n\n Check your inbox of registered mail for further information.";

     $Email_Response = sendEventMail($Email, "Appointment Invitation!", "Event.php", $message, $headers, $other);  
     $Event_Response = 'Appointment has been successfully approved';

 }

 if($Email_Response===true){
          $Email_Response = ' and Event Mail sent to Customer';
        }else{
          $Email_Response = ' but '.$Email_Response;
        }

$SMS_Response = SendSMS($Phone,$sms,$cid);
//  $ph=str_replace("(","",$Phone);
//  $ph2=str_replace(") ","",$ph);
//  $ph3=str_replace("-","",$ph2);
//  // $finalphone = '+91'.$ph3;
//  $finalphone = '+'.$isdcode.''.$ph3;


//  $id=$_SESSION['UserID'];
//  $SMS_CREATEDFK = $_SESSION['UserID'];
//  if($_SESSION['usertype']=='employee'){
//     $SMS_CREATEDFK = $_SESSION['adminid'];
// }
// $stmt2= $db->prepare("SELECT * FROM `smssetting` WHERE createdfk=:id"); 
// $stmt2->bindParam(':id', $SMS_CREATEDFK, PDO::PARAM_INT);
// $stmt2->execute();
// $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
// if(!empty($result2))
// {
//     $sid = $result2['sid'] ;
//     $token = $result2['token'];
//     $client = new Client($sid, $token);
//     @$Twillo_from= $result2['Twillo_from']; 
//     $Successfully_send = $client->messages->create($finalphone,array('from' =>$Twillo_from,'body' =>$sms));
//     $ssid = $Successfully_send->sid;
//     if(empty($ssid))
//     {
//         return json_encode(["error"=>'Event created but SMS not sent. please check you SMS setting.']);die;
//     }
//     else
//     {
//         $date = date('Y/m/d h:i:s', time());
//         $sql = $db->prepare("insert into smsdata(ssid,twillo_from,userid,sms,finalphone,createddate) values(:ssid,:twillo_from,:userid,:sms,:finalphone,:createddate)");
//         $sql->bindParam(":ssid",$ssid);
//         $sql->bindParam(":createddate",$date);
//         $sql->bindParam(":twillo_from",$Twillo_from);
//         $sql->bindParam(":userid",$id);
//         $sql->bindParam(":sms",$sms);
//         $sql->bindParam(":finalphone",$finalphone);
//         $sql->execute();

//         $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,cid,Createid,comtime) VALUES('sms',:message,:cid,:Createid,:comtime)");
//         $comtime = date("Y-m-d H:i:s");
//         $insert_data_fc->bindparam(":message",$sms);
//         $insert_data_fc->bindparam(":cid",$_POST["cid"]);
//         $insert_data_fc->bindparam(":Createid",$id);
//         $insert_data_fc->bindparam(":comtime",$comtime);
//         $insert_data_fc->execute(); 
//     }

// }
// else
// {   
//     @$TwilioSetupmessage= "\n Click on 'Set' button to Twilio setup for sending appointment SMS to client.";

//         //@$TwilioSetupmessage= '<h5>Set Twilio setup for sending appointment SMS to client.<a href="https://mysunless.com/SmsSendSetting"><u> click here for set Twilio setup </u></a></h5>';
// }


if(isset($_POST['id']) && $_POST['id']=="new")
{
    return json_encode(['response'=>$Event_Response.$Email_Response,'SMS_Response'=>@$SMS_Response ]);die;
}
else
{       
    return json_encode(['response'=>$Event_Response.$Email_Response,'SMS_Response'=>@$SMS_Response ]);die;    
}
}
else{
    return json_encode(['response'=>$Event_Response.$Email_Response ]);die;

}

}

if(isset($_SESSION['UserID']) && !isset($_POST['bookout']))
{

    $id=$_SESSION['UserID'];

}
else
{
    if(isset($_POST['UserID'])){
        $id=$_POST['UserID'];
    }

}


if(!empty($id)){


    $stmt= $db3->prepare("SELECT * FROM `users` WHERE id=:id"); 
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    @$schcreateprmistion=$result['SchedulesCreate'];

    if($schcreateprmistion != 1)
    {
        echo  json_encode(["error"=>'You Don\'t Have A Permission To Book Appointment!']);die;
    }

}
if(isset($_GET['get_block_date'])){

    if(isset($_POST['service_provider']) && $_POST['service_provider']!="" && isset($_POST['service_date']) && $_POST['service_date']!=""){
        $block_date =[];
        $service = new db();
        $query = $service->prepare("Select timetable from users where id=?");
        $query->execute([$_POST['service_provider']]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result && ($query->rowCount() > 0)){
            $timetable = [];
            $timetable = json_decode($result["timetable"],true);

            $day_number = ["Monday"=>0,"Tuesday"=>1,"Wednesday"=>2,"Thursday"=>3,"Friday"=>4,"Saturday"=>5,"Sunday"=>6];

            $startDate = date("Y-m-d",strtotime($_POST['service_date']));

            $endDate = date("Y-m-t", strtotime($startDate));

            $start_dates=[];

            while(date("Y-m-d",strtotime($startDate))<=date("Y-m-d",strtotime($endDate)))
            {

                $day = date("l", strtotime($startDate));
                if(date("Y-m-d",strtotime($startDate))>date("Y-m-d")){
                    if($timetable[$day_number[$day]][$day]){

                    }else{
                        array_push($block_date,date("j", strtotime($startDate)));
                    }
                }else{
                    array_push($block_date,date("j", strtotime($startDate)));
                }
                $startDate = date('Y-m-d', strtotime($startDate. ' + 1 days'));
                
            }
            
            echo json_encode(['response'=>$block_date]);die;
        }
    }else{
        echo json_encode(['error'=>'Not sufficent data value.']);die;
    }
}

//get_service_time
if(isset($_GET['get_service_time'])){

    if(isset($_POST['service_provider']) && $_POST['service_provider']!="" && isset($_POST['service_date']) && $_POST['service_date']!="" && $_POST['duration'] && $_POST['duration']!="" ){

        if(isset($_POST['id']) && $_POST['id']!="new"){
            $id_string = " and id <> ".$_POST['id'];
        }else{
            $id_string = "";
        }

        $service = new db();
        $query = $service->prepare("Select timetable from users where id=?");
        $query->execute([$_POST['service_provider']]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if($result && ($query->rowCount() > 0)){
            $timetable = [];
            $timetable = json_decode($result["timetable"],true);

            $day_number = ["Monday"=>0,"Tuesday"=>1,"Wednesday"=>2,"Thursday"=>3,"Friday"=>4,"Saturday"=>5,"Sunday"=>6];

            $service_date_array = explode(",", $_POST['service_date']);
            
            $smallest_time = 0;
            $service_date_string = [];

            foreach ($service_date_array as $servicedate) {
                array_push($service_date_string, "DATE_FORMAT('$servicedate', '%Y-%m-%d')" );

                $day = date("l", strtotime($servicedate));
                
                if($timetable[$day_number[$day]][$day]){

                    $pass = 1;
                    $starttime = $timetable[$day_number[$day]]['starttime'];
                    $endtime = $timetable[$day_number[$day]]['endtime'];
                    $current_time = abs((strtotime($starttime) + 60*60)-(strtotime($endtime) + 60*60));
                    
                    if($smallest_time>$current_time || $smallest_time==0){
                        $smallest_time = $current_time;
                        $select_day = $day;
                    }

                }else{
                    $pass = 0;
                    break;
                }

            }


            if($pass){

                $starttime = "2020-01-01 ".$timetable[$day_number[$select_day]]['starttime'];
                $endtime = "2020-01-01 ".$timetable[$day_number[$select_day]]['endtime'];

                $duration = explode(" ",$_POST['duration']);
                $gap = "";
                if($duration[1]=="h"){
                    $gap = "+".$duration[0]." hour";
                }else if($duration[1]=="Min"){
                    $gap = "+".$duration[0]." minutes";
                }

                $time_slot = [];
                $convertedTime = date('Y-m-d H:i:s',strtotime($starttime));

                if(isset($_POST['service_time_start']) && $_POST['service_time_start']!="" )
                {

                    if( ( date('H:i:s',strtotime($_POST['service_time_start'])) >= date('H:i:s',strtotime($convertedTime)) ) and ( date('H:i:s',strtotime($_POST['service_time_start'])) <= date('H:i:s',strtotime($endtime)) ) )
                    {
                        array_push($time_slot, date('h:ia',strtotime($_POST['service_time_start']))."-".date('h:ia',strtotime($gap,strtotime($_POST['service_time_start']))));
                    }else{
                         echo json_encode("This slot is unavailable at this time. Please check out other service providers or choose another date.");die;
                    }

                }else{

                    while(date('Y-m-d H:i:s',strtotime($convertedTime)) < date('Y-m-d H:i:s',strtotime($endtime)) ){

                        array_push($time_slot, date('h:ia',strtotime($convertedTime))."-".date('h:ia',strtotime($gap,strtotime($convertedTime))));

                        $convertedTime = date('Y-m-d H:i:s',strtotime($gap,strtotime($convertedTime)));
                    }
                }

                $service_date_string = implode(',',$service_date_string);
                $query = $service->prepare("Select EventDate,end_date from event where Accepted <> '2' and  eventstatus <> 'Canceled' and ServiceProvider=? and DATE_FORMAT(EventDate, '%Y-%m-%d') IN ( $service_date_string ) $id_string ");
                
                $query->execute([$_POST['service_provider']]);
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                

                if($result && ($query->rowCount() > 0)){

                    foreach ($result as $value) {

                      // echo '<br> result: '.date('H:i:s',strtotime($value['EventDate'])).' - '.date('H:i:s',strtotime($value['end_date'])).'<br>';

                        foreach ($time_slot as $key => $slot) {


                            $slot = explode("-",$slot);

                            $EventDate = date('H:i:s',strtotime($value['EventDate']));
                            $end_date = date('H:i:s',strtotime($value['end_date']));
                            $slot1 = date('H:i:s',strtotime($slot[0]));
                            $slot2 = date('H:i:s',strtotime($slot[1]));

                            // echo $slot1.' - '.$slot2.'<br>';

                            // if(($slot1 <= $EventDate && $slot2 > $EventDate) || ( $slot1 < $end_date && $slot2 > $end_date )){

                            

                            if( ($EventDate >= $slot1 && $EventDate < $slot2) || ($end_date > $slot1 && $end_date < $slot2) || ($slot1 >= $EventDate && $slot1 < $end_date) || ( $slot2 > $EventDate && $slot2 < $end_date )){

                                // echo 'unset: '.$time_slot[$key]."<br>";
                                unset($time_slot[$key]);
                            }

                        }
                            // echo '<br> break result';
                    }
                    $time_slot =  array_values($time_slot);

                }
                

                if($time_slot){
                    if(isset($_POST['service_time_start']) && $_POST['service_time_start']!="" ){
                        echo json_encode(true);die;
                    }
                    echo json_encode(["response"=>$time_slot]);die;
                }else{
                    if(isset($_POST['service_time_start']) && $_POST['service_time_start']!="" ){
                        echo json_encode("All slots are booked. Please check out other service providers or choose another date.");die;
                    }
                    echo json_encode(["error"=>"All slots are booked. Please check out other service providers or choose another date."]);die;
                }

            }else{
                if(isset($_SESSION['UserID'])){

                   if(isset($_POST['service_time_start']) && $_POST['service_time_start']!="" ){
                        echo json_encode("Invalid Time! Please check Service Provider's working hour.");die;
                    }
                    echo json_encode(["error1"=>"Click 'OK' button to manage the working hours of the Service Provider."]);die;
                }else{
                    echo json_encode(["error"=>"Not available! Please check out other service providers or choose another date."]);die;
                }
            }
        }
        else
        {
            echo json_encode(["error"=>"Invalid Service Provider."]);die;
        }
    }
    else{
        echo json_encode(["error"=>"Service Provider and Date are required."]);die;
    }

}
// //check_service_time
// if(isset($_GET['check_service_time'])){
//     if(isset($_POST['service_provider']) && isset($_POST['service_date']) && isset($_POST['service_time_start']) && isset($_POST['service_time_end']) && $_POST['service_provider']!="" && $_POST['service_date']!="" && $_POST['service_time_start']!="" && $_POST['service_time_end']!=""){

//         if($_POST['id']!="new"){
//             $id_string = " and id <> ".$_POST['id'];
//         }else{
//             $id_string = "";
//         }

//         $service_date = explode("-", $_POST['service_date']);
//         $servicedate = $service_date[2]."-".$service_date[0]."-".$service_date[1]; 


//         $service = new db();
//         $query = $service->prepare("Select EventDate,end_date from event where Accepted <> '2' and ServiceProvider=? and eventstatus <> 'Canceled' and DATE_FORMAT(?, '%Y-%m-%d') = DATE_FORMAT(EventDate, '%Y-%m-%d') ".$id_string." order by EventDate");

//         $query->execute([$_POST['service_provider'],$servicedate]);
//         $result = $query->fetchAll(PDO::FETCH_ASSOC);

//         $response_time = [];
//         if($result && ($query->rowCount() > 0)){

//             $stime = date_format(date_create($_POST['service_time_start']),"H:i:s");
//             $etime = date_format(date_create($_POST['service_time_end']),"H:i:s");

//             $flag=0;
//             foreach ($result as $value) {

//                 $time1 = date_format(date_create($value['EventDate']),"H:i:s");

//                 $time2 = date_format(date_create($value['end_date']),"H:i:s");

//                 // if( ($EventDate <= $slot1 && $end_date > $slot1) || ($EventDate < $slot2 && $end_date > $slot2) ){

//                 // if( ($time1 <= $stime && $time2 > $stime) || ($time1 < $etime && $time2 > $etime)){
//                 //     $flag = 1;
//                 // }

//                 if(($stime <= $time1 && $etime > $time1) || ($stime < $time2 && $etime > $time2))
//                 {
//                     $flag=1;
//                 }

//                 array_push($response_time,$time1."-".$time2);
//             }
//             $response_time = implode(", ",$response_time);

//             if($flag){
//                 echo json_encode("The Service Provider has already booked in this periods:<br>".$response_time);die;
//             }else{
//                 echo json_encode(true);die;
//             }

//         }else{

//             echo json_encode(true);die;
//         }
//     }else{
//         echo json_encode("Set the value of Service, Service Provider and Appointment date");die;
//     }
// }




if(!empty($_POST['wdateshidden']))
{  


    // echo "string";   
    // die();
    $datesarray = explode(',', $_POST['wdateshidden']);

    for($dates=1;$dates<count($datesarray);$dates++)
    {
        @$myevent = new event($_POST["id"]);
        @$myevent->id = stripslashes(strip_tags($_POST["id"])); //$_POST["id"];

        if($_SESSION['UserName']=="Admin")
        {
            @$myevent->UserID= $_POST["newlistofSubscriber"];
        }
        else
        {
         @$myevent->UserID =$_POST["UserID"];    
     }
     @$title=$myevent->title =stripslashes(strip_tags($_POST["title"]));
     @$FirstName=$myevent->FirstName =stripslashes(strip_tags($_POST["FirstName"]));
     @$LastName=$myevent->LastName = stripslashes(strip_tags($_POST["LastName"]));
     @$Phone=$myevent->Phone =$_POST["Phone"];
     @$Email=$myevent->Email = $_POST["Email"];
     @$stat_data=$_POST["sd"];
     @$stat_time=$_POST["st"];
     @$end_data=$_POST["ed"];
     @$end_time=$_POST["et"];
     @$_SESSION["event-start-time"] = $stat_data.' '.$stat_time;
     @$_SESSION["event-end-time"] = $end_data.' '.$end_time;
     @$_SESSION["event-title"] = stripslashes(strip_tags($_POST["title"]));
     @$EventDate=$myevent->EventDate=$datesarray[$dates].' '.$stat_time;
     @$end_date=$myevent->end_date=$datesarray[$dates].' '.$end_time;
        // $EventDate=$myevent->EventDate = $_POST["EventDate"];
        // $end_date=$myevent->end_date = $_POST["end_date"];
        // $myevent->EventTime = $_POST["EventTime"];
        // $myevent->EventTime2 = $_POST["EventTime2"];


     if(empty($_POST["ServiceProvider"]))
     {

        @$ServiceProvider=$myevent->ServiceProvider =stripslashes(strip_tags($_POST["listofcatagory3"])); //$_POST["eventstatus"];    
    }   
    else
    {

        @$ServiceProvider=$myevent->ServiceProvider =stripslashes(strip_tags($_POST["ServiceProvider"])); //$_POST["eventstatus"];     
    }

    if($_POST["Location_radio"]=='Salon Location'){
     $addquery = $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=:UserID");
     $addquery->bindParam(":UserID",$_POST["UserID"]);
 }else{
     $addquery = $db->prepare("SELECT * FROM `clients` WHERE id=:cid");
     $addquery->bindParam(":cid",$_POST["cid"]);
 }

 $addquery->execute();
 $app_address = $addquery->fetch();
 $Event_Address = $app_address['Address'];
 $Event_Zip = $app_address['Zip'];
 $Event_City = $app_address['City'];
 $Event_State = $app_address['State'];
 $Event_Country = $app_address['Country'];

        @$eventstatus=$myevent->eventstatus =stripslashes(strip_tags($_POST["eventstatus"])); //$_POST["eventstatus"];
        @$Address=$myevent->Address =stripslashes(strip_tags($Event_Address));
        @$Zip=$myevent->Zip =stripslashes(strip_tags($Event_Zip));
        @$City=$myevent->City =$Event_City; 
        @$State=$myevent->State =stripslashes(strip_tags($Event_State)); 
        @$country=$myevent->country =$Event_Country; 
        @$CostOfService=$myevent->CostOfService = $_POST["CostOfService"];
        @$EmailInstruction=$myevent->EmailInstruction =$_POST["EmailInstruction"];
        @$ServiceName=$myevent->ServiceName =stripslashes(strip_tags($_POST["ServiceName"]));
        @$cid=$myevent->cid =$_POST["cid"];
        @$user=$_SESSION['UserName'];
        @$Location_radio=$myevent->Location_radio=$_POST["Location_radio"];

        if(empty($FirstName))
        {
            echo json_encode(['error_twilo'=>'Please fill all data first']);die;
        }
        $newevent=$myevent->commit($myevent->id);
    }
    @$EventDate=$datesarray[0].' '.$stat_time;
    @$end_date=$datesarray[0].' '.$end_time;
}
else
{
    @$myevent = new event($_POST["id"]);
    @$myevent->id = stripslashes(strip_tags($_POST["id"])); //$_POST["id"];
    // @$myevent->UserID =$_POST["UserID"]; 
    
    if(isset($_SESSION['usertype']))
    {


        if($_SESSION['usertype']=="Admin")
        {
            @$myevent->UserID= $_POST["newlistofSubscriber"];
        }
        else
        {
         @$myevent->UserID =$_POST["UserID"];    
     }
 }
 else
 {
     @$myevent->UserID = $_POST["UserID"];    

 }
 @$title=$myevent->title =stripslashes(strip_tags($_POST["title"]));
 @$FirstName=$myevent->FirstName =stripslashes(strip_tags($_POST["FirstName"]));
 @$LastName=$myevent->LastName = stripslashes(strip_tags($_POST["LastName"]));
 @$Phone=$myevent->Phone =$_POST["Phone"];
 @$Email=$myevent->Email = $_POST["Email"];
 @$getdate=$_POST["sd"];
 @$stat_data2 = explode("-",$getdate); 
 @$stat_data = $stat_data2[2].'-'.$stat_data2[0].'-'.$stat_data2[1];

 @$getdate2=$_POST["ed"];
 @$stat_data3 = explode("-",$getdate2); 

 @$end_data = $stat_data3[2].'-'.$stat_data3[0].'-'.$stat_data3[1];

 @$stat_time=$_POST["st"]; 

 if(isset($_POST["et"]) && empty($_POST["et"]))
 {

    if(isset($_REQUEST['st']))
    {

      $service_star_time=$_POST['st'];
      $serivename=$_POST['ServiceName']; 
      $eidtClient = $db->prepare("select Duration from `Service` where id=:serivename");
      $eidtClient->bindValue(":serivename",$serivename,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);
      $Duration=$all['Duration'];

      if($Duration=='0 Min')
      {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+0 minutes', $time));
        echo  json_encode(["response"=>$time]);die;
    }

    if($Duration=='15 Min')
    {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+15 minutes', $time));
        echo  json_encode(["response"=>$time]);die;
    }   

    if($Duration=='30 Min')
    {
        $time = strtotime($service_star_time);
        $time = date("g:ia", strtotime('+30 minutes', $time));

    }

    if($Duration=='1 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60;
        $time = date('g:ia',$timestamp);

    }

    if($Duration=='2 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*2;
        $time = date('g:ia',$timestamp);

    }

    if($Duration=='3 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*3;
        $time = date('g:ia',$timestamp);

    }

    if($Duration=='4 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*4;
        $time = date('g:ia',$timestamp);

    }

    if($Duration=='5 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*5;
        $time = date('g:ia',$timestamp);

    }
    if($Duration=='6 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*6;
        $time = date('g:ia',$timestamp);

    }
    if($Duration=='7 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*7;
        $time = date('g:ia',$timestamp);

    }  
    if($Duration=='8 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*8;
        $time = date('g:ia',$timestamp);

    }
    if($Duration=='9 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*9;
        $time = date('g:ia',$timestamp);

    }  
    if($Duration=='10 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*10;
        $time = date('g:ia',$timestamp);

    }
    if($Duration=='11 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*11;
        $time = date('g:ia',$timestamp);

    }
    if($Duration=='12 h')
    {
        $timestamp = strtotime($service_star_time) + 60*60*12;
        $time = date('g:ia',$timestamp);

    }

}
@$end_time=$time;     
}
else
{
   @$end_time=$_POST["et"]; 
}

@$_SESSION["event-start-time"] = $stat_data.' '.$stat_time;
@$_SESSION["event-end-time"] = $end_data.' '.$end_time;
@$_SESSION["event-title"] = stripslashes(strip_tags($_POST["title"]));
@$EventDate=$myevent->EventDate=$stat_data.' '.$stat_time;
@$end_date=$myevent->end_date=$end_data.' '.$end_time;



    // @$ServiceProvider=$myevent->ServiceProvider =stripslashes(strip_tags($_POST["ServiceProvider"])); //$_POST["eventstatus"];

if(empty($_POST["ServiceProvider"]))
{
        @$ServiceProvider=$myevent->ServiceProvider =stripslashes(strip_tags($_POST["listofcatagory3"])); //$_POST["eventstatus"];    
    }   
    else
    {
        @$ServiceProvider=$myevent->ServiceProvider =stripslashes(strip_tags($_POST["ServiceProvider"])); //$_POST["eventstatus"];     
    }
    
    if($_POST["Location_radio"]=='Salon Location'){
     $addquery = $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=:UserID");
     $addquery->bindParam(":UserID",$_POST["UserID"]);
 }else{
     $addquery = $db->prepare("SELECT * FROM `clients` WHERE id=:cid");
     $addquery->bindParam(":cid",$_POST["cid"]);
 }

 $addquery->execute();
 $app_address = $addquery->fetch();
 $Event_Address = $app_address['Address'];
 $Event_Zip = $app_address['Zip'];
 $Event_City = $app_address['City'];
 $Event_State = $app_address['State'];
 $Event_Country = $app_address['Country'];




    @$eventstatus=$myevent->eventstatus =stripslashes(strip_tags($_POST["eventstatus"])); //$_POST["eventstatus"];
    @$Address=$myevent->Address =stripslashes(strip_tags($Event_Address));
    @$Zip=$myevent->Zip =stripslashes(strip_tags($Event_Zip));
    @$City=$myevent->City =$Event_City; 
    @$State=$myevent->State =stripslashes(strip_tags($Event_State)); 
    @$country=$myevent->country =$Event_Country; 
    @$CostOfService=$myevent->CostOfService = $_POST["CostOfService"];
    @$EmailInstruction=$myevent->EmailInstruction =$_POST["EmailInstruction"];
    @$ServiceName=$myevent->ServiceName =stripslashes(strip_tags($_POST["ServiceName"]));
    @$cid=$myevent->cid =$_POST["cid"];
    @$user=$_SESSION['UserName'];
    @$Location_radio=$myevent->Location_radio=$_POST["Location_radio"];

    if(empty($FirstName))
    {
        echo json_encode(['error_twilo'=>'Please fill all data first']);die;
    }
    $newevent=$myevent->commit($myevent->id);
}


$eid =  $myevent->id;
// get isd code for country  
$db = new db();
$statement= $db->prepare("SELECT * FROM `countries` WHERE countries_name=:Country");
$statement->bindParam(':Country', $country, PDO::PARAM_STR, 12);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
$isdcode = $result['countries_isd_code'];


//msg limit cheking
$sql = $db->prepare("select count(*) as total from smsdata where userid = :id");
$sql->bindParam(":id",$_SESSION["UserID"]);
$sql->execute();
$data = $sql->fetch();
if($data["total"] > 1)
{
    //echo "IN";
}





if($myevent)
{
    $myactivite = new Activites();
    if($_POST['id']=="new")
    {
        $myevent->ActivitesCount($newevent); // This function for data insert in CountActivites.
        $Titile=$myactivite->Titile = 'Add new appointment '.$_POST["title"].' for '.$_POST["FirstName"].' '.$_POST["LastName"] ; 
    }
    else
    {
        $Titile=$myactivite->Titile = 'Update details of appointment '.$_POST["title"].' for '.$_POST["FirstName"].' '.$_POST["LastName"]  ;      
    }

    // Edited
    $query = $db->prepare("SELECT users.firstname,users.lastname FROM users WHERE users.id=:ServiceProvider");
    $query->bindValue(':ServiceProvider', $ServiceProvider, PDO::PARAM_INT);
    $query->execute();
    $res = $query->fetch(PDO::FETCH_ASSOC);

    
    $queryimag = $db->prepare("SELECT * FROM `CompanyInformation` WHERE createdfk=$id");
    $queryimag->execute();
    $resimag = $queryimag->fetch(PDO::FETCH_ASSOC);

    // End Edited

    $myactivite->commit_acitve($Titile);

    if(isset($_SESSION['UserID']) && !isset($_POST['bookout'])){
        $appointment_id = date('Ymd').'-'.$eid;
        $other['--APP_NO--'] = $appointment_id;
        $other['--USERNAME--'] = $user;
        $other['--SERVICE--'] = $title;
        $other['--PRICE--'] = $_POST["CostOfService"];
        $other['--TITLE--'] = $res['firstname']." ".$res['lastname']; // Edited  

        $resimag['Phone'];
        
        $resimag['CompanyName'];


        if($resimag['compimg'])
        {
            $other['--IMG--'] = $resimag['compimg'];    
        }
        else
        {
            $other['--IMG--'] = 'maillogo.png';       
        }
        $other['--EMAIL--'] = $Email;
        $other['--FIRSTNAME--'] = $FirstName;
        $other['--LASTNAME--'] = $LastName;
        $other['--EI--'] = $EmailInstruction;
        $other['--EDATA--'] = $stat_time;
        $other['--ENDATA--'] = $end_time;
        $other['--ADD--'] = $Address;
        $other['--ZIP--'] = $Zip;
        $other['--CITY--'] = $City;
        $other['--STA--'] = $State;

        $other['--MAP--'] = str_replace(" ","+",$Address.",".$City.",".$State.",".$Zip.",".$country);

        $other['--EventId--'] = $eid;
        $other['--ONLYDATE--'] = date("F-jS-Y", strtotime($stat_data));

        if($resimag['CompanyName'])
        {
            $other['--COMPNAME--'] = $resimag['CompanyName'];
        }
        else
        {
            $other['--COMPNAME--'] = '';
        }

        if($resimag['Phone'])
        {
            $other['--COMPNUMBER--'] = $resimag['Phone'];    
        }
        else
        {
            $other['--COMPNUMBER--'] = '';       
        }

        if($resimag['email'])
        {
            $other['--COMPEMAIL--'] = $resimag['email'];    
        }
        else
        {
            $other['--COMPEMAIL--'] = '';       
        }

        $headers = '';
        $message="Hi ";
        $Email_Response = sendEventMail($Email, "Appointment Invitation!", "Event.php", $message, $headers, $other);   

        if($Email_Response===true){
          $Email_Response = ' and Event Mail sent to Customer';
        }else{
          $Email_Response = ' but '.$Email_Response;
        }
        
        
        
        // $ph=str_replace("(","",$Phone);
        // $ph2=str_replace(") ","",$ph);
        // $ph3=str_replace("-","",$ph2);
        // // $finalphone = '+91'.$ph3;
        // $finalphone = '+'.$isdcode.''.$ph3;

        
        // $finale=date_create($EventDate);
        // $finaledate=date_format($finale,"d M Y h:i:sa");

        $sms="Hi, ".ucfirst($FirstName)." ".ucfirst($LastName)." your appointment '$title'($appointment_id) schedule on $EventDate .\n\n 1. Confirm: https://mysunless.com".ESUB."/allcamscriptcron.php?eidforyes=$eid \n\n 2. Cancel: https://mysunless.com".ESUB."/allcamscriptcron.php?eidforno=$eid \n\n 3. Reschedule: https://mysunless.com".ESUB."/allcamscriptcron.php?notify&eidforapply=$eid . \n\n Location: https://www.google.com/maps/place/".$other['--MAP--']." \n\n Check your inbox of registered mail for further information.";

        $SMS_Response = SendSMS($Phone,$sms,$cid);

        // $id=$_SESSION['UserID'];
        // $SMS_CREATEDFK = $_SESSION['UserID'];
        // if($_SESSION['usertype']=='employee'){
        //     $SMS_CREATEDFK = $_SESSION['adminid'];
        // }
        // $stmt2= $db->prepare("SELECT * FROM `smssetting` WHERE createdfk=:id"); 
        // $stmt2->bindParam(':id', $SMS_CREATEDFK, PDO::PARAM_INT);
        // $stmt2->execute();
        // $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        // if(!empty($result2))
        // {
        //     $sid = $result2['sid'] ;
        //     $token = $result2['token'];
        //     $client = new Client($sid, $token);
        //     @$Twillo_from= $result2['Twillo_from']; 
        //     $Successfully_send = $client->messages->create($finalphone,array('from' =>$Twillo_from,'body' =>$sms));
        //     $ssid = $Successfully_send->sid;
        //     if(empty($ssid))
        //     {
        //         echo json_encode(["error"=>'Event created but SMS not sent. please check you SMS setting.']);die;
        //     }
        //     else
        //     {
        //         $date = date('Y/m/d h:i:s', time());
        //         $sql = $db->prepare("insert into smsdata(ssid,twillo_from,userid,sms,finalphone,createddate) values(:ssid,:twillo_from,:userid,:sms,:finalphone,:createddate)");
        //         $sql->bindParam(":ssid",$ssid);
        //         $sql->bindParam(":createddate",$date);
        //         $sql->bindParam(":twillo_from",$Twillo_from);
        //         $sql->bindParam(":userid",$id);
        //         $sql->bindParam(":sms",$sms);
        //         $sql->bindParam(":finalphone",$finalphone);
        //         $sql->execute();

        //         $insert_data_fc=$db->prepare("INSERT INTO FullCom(type,message,cid,Createid,comtime) VALUES('sms',:message,:cid,:Createid,:comtime)");
        //         $comtime = date("Y-m-d H:i:s");
        //         $insert_data_fc->bindparam(":message",$sms);
        //         $insert_data_fc->bindparam(":cid",$_POST["cid"]);
        //         $insert_data_fc->bindparam(":Createid",$id);
        //         $insert_data_fc->bindparam(":comtime",$comtime);
        //         $insert_data_fc->execute(); 
        //     }

        // }
        // else
        // {
        //     @$TwilioSetupmessage= "\nClick on 'Set' button to Twilio setup for sending appointment SMS to client.";
        // }


        if(isset($_POST['id']) && $_POST['id']=="new")
        {
            echo json_encode(['response'=>'Appointment has been successfully approved '.$Email_Response,'SMS_Response'=>@$SMS_Response ]);die;    
        }
        else
        {       
            echo json_encode(['response'=>'Appointment has been successfully approved '.$Email_Response,'SMS_Response'=>@$SMS_Response ]);die;    
        }
    }
    else{

        if(isset($_POST['UserID'])){
            $id = $_POST['UserID'];
        }else{
            $id = $_SESSION['UserID'];
        }

        $tid = $newevent;
        $sql = $db->prepare("INSERT into Notification (table_name,tid,type,createdfk) values('event',:tid,'pending',:createdfk)");
        $sql->bindParam(":tid",$tid);
        $sql->bindParam(":createdfk",$id);
        $run = $sql->execute();

        echo json_encode(['response'=>'Appointment has been successfully Register. <br> Now its going to appove by admin' ]);die;

    }       
}
else
{
    echo json_encode(['error'=>'Something wrong']);die;
}
?>

