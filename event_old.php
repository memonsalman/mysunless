<?php
require_once($_SERVER['DOCUMENT_ROOT']."/crm/function.php");

$db4=new db();
$sid=$_SESSION['UserID'];

 if($_SESSION['UserName']=="Admin")
 {
    $stmt= $db4->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
FROM `event` 
JOIN users ON event.ServiceProvider=users.id
JOIN clients ON event.cid=clients.id
 WHERE event.eventstatus!='canceled' ");   
 }
else if($_SESSION['UserName']!="Admin")
{

        if(!empty($_GET['myapp']))
        {
            $id=$_GET['myapp'];
            $stmt= $db4->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
        FROM `event` 
        JOIN users ON event.ServiceProvider=users.id
        JOIN clients ON event.cid=clients.id
        WHERE event.UserID=:id AND event.createdfk=:sid AND Accepted = '1' and  eventstatus <> 'Canceled'");   
        }
        else if(!empty($_GET['myappU']))
        {
            $id=$_GET['myappU'];
            $stmt= $db4->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
        FROM `event` LEFT JOIN users ON event.ServiceProvider=users.id JOIN clients ON event.cid=clients.id WHERE event.ServiceProvider=:id AND event.createdfk IN (select id from users where id=:sid or adminid=:sid or sid=:sid ) AND Accepted = '1' and  eventstatus <> 'Canceled' ");   
        }
        else if(!empty($_GET['myappS']))
        {
            $id=$_GET['myappS'];
            // $stmt= $db4->prepare("SELECT * FROM `event` WHERE ServiceName=:id AND createdfk=:sid");   
                 $stmt= $db4->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
                 FROM `event` JOIN users ON event.ServiceProvider=users.id JOIN clients ON event.cid=clients.id WHERE event.ServiceName=:id AND event.createdfk IN (select id from users where id=:sid or adminid=:sid or sid=:sid ) AND Accepted = '1' and  eventstatus <> 'Canceled' ");   
        }
        else
        {
            $id=$_SESSION['UserID'];
            // $stmt= $db4->prepare("SELECT * FROM `event` WHERE UserID=:id AND createdfk=:sid");   
            $stmt= $db4->prepare("SELECT event.FirstName AS FirstName ,event.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
        FROM `event` 
        JOIN users ON event.ServiceProvider=users.id
        JOIN clients ON event.cid=clients.id
        WHERE (event.createdfk IN (select id from users where id=:id or adminid=:id or sid=:sid ) or event.ServiceProvider=:id)AND Accepted = '1' and  eventstatus <> 'Canceled' ");   
        }
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':sid', $sid, PDO::PARAM_INT);
}
$stmt->execute();
$calendar = array();
while($rows=$stmt->fetch(PDO::FETCH_ASSOC)) {  

    $start = strtotime($rows['EventDate']) * 1000;
    $end = strtotime($rows['end_date']) * 1000; 

    $stime2=strtotime($rows['EventDate']);
    $stime=date("h:ia",$stime2);
    $onlystime=date("m/d",$stime2);
    $etime=date("h:ia",$stime2);

    if($rows['userimg']){
        $userimg = base_url.'/assets/userimage/'.$rows['userimg'];
    }else{
        $userimg = base_url.'/assets/images/noimage.png';
    }

    if($rows['ProfileImg']){
        $ProfileImg = base_url.'/assets/ProfileImages/'.$rows['ProfileImg'];
    }else{
        $ProfileImg = base_url.'/assets/images/noimage.png';
    }

    if($rows['eventstatus'] == 'pending')
    {
        $eventclass = "event-grey" ;
    }
    elseif ($rows['eventstatus'] == 'confirmed') 
    {
        $eventclass = "event-success" ;
    }
    elseif ($rows['eventstatus'] == 'canceled') 
    {
        $eventclass = "event-important" ;
    }
    elseif ($rows['eventstatus'] == 'pending-payment') 
    {
        $eventclass = "event-red" ;
    }
    elseif ($rows['eventstatus'] == 'in-progress') 
    {
        $eventclass = "event-warning" ;
    }
    elseif ($rows['eventstatus'] == 'completed') 
    {
        $eventclass = "event-info" ;
    }
    else
    {
        $eventclass = "event-inverse" ;
    }
    $clientname = ucfirst($rows['FirstName']).' '.ucfirst($rows['LastName']);
     $eventadders = $rows['Address'].', '.$rows['City'].', '.$rows['State'].', '.$rows['Zip'].', '.$rows['Country'];
    $eid=$rows['id'];
    $cid=$rows['cid'];
    $calendar[] = array(
        'id' =>$rows['id'],
        'encodeId' =>base64_encode ($eid),
        'title' => $rows['title'],
        'my' => $rows['EventDate'],
        'url' => base_url."/AddEvent.php?action=edit&id=".base64_encode ($eid),
        'Deleteurl' => base_url."/AddEvent.php?action=delete&id=".base64_encode ($eid),
        'viewcustomerurl' => base_url."/ViewClient?action=view&id=".base64_encode ($cid),
        'chekouturl' => base_url."/Order?action=appo&cid=".base64_encode ($cid),
        "class" => $eventclass,
        'start' => "$start",
        'stime' => "$stime",
        'end' => "$end",
        'onlystime'=> "$onlystime",
        'clientname'=> "$clientname",
        'clientID'=>base64_encode ($cid),
        'etime'=> "$etime",
        'username'=> $rows['username'],
        'eventadders'=>"https://maps.google.com/?q=".$eventadders."&output=embed",
        'onlyeventadders'=>$eventadders,
        'evnetloction'=>$rows['Location_radio'],
        'ServiceName'=>base64_encode($rows['ServiceName']),
        'ServiceProvider'=>base64_encode($rows['ServiceProvider']),
        'customerimg'=>$ProfileImg,
        'providerimg'=>$userimg

		
    );
}
$calendarData = array(
    "success" => 1, 
    "result"=>$calendar);
echo json_encode($calendarData);
// }
exit;
?>