<?php
require_once($_SERVER['DOCUMENT_ROOT']."/crm/function.php");

$db4=new db();

$add= "";


if(!empty($_GET['myapp']))
{
    if($_SESSION['usertype']!='Admin'){
        $id=$_GET['myapp'];
        $add = " AND event.ServiceProvider=$id ";
    }

}

if(!empty($_GET['myappU']))
{   
    if($_SESSION['usertype']!='Admin'){
        $id=$_GET['myappU'];
        $sid=$_SESSION['UserID'];
        $add = " AND event.ServiceProvider IN ($id) AND event.createdfk IN (select id from users where id IN ($sid) or adminid IN ($sid) or sid IN ($sid) ) ";
    }else{
        $id=$_GET['myappU'];
        $add = " AND event.ServiceProvider IN (select id from users where id IN ($id) or adminid IN ($id) or sid IN ($id) ) ";
    }
}

if(!empty($_GET['myappS']))
{
    if($_SESSION['usertype']!='Admin'){
        $id=$_GET['myappS'];
        $sid=$_SESSION['UserID'];
        $add .= " AND event.ServiceName IN ($id) AND event.createdfk IN (select id from users where id IN ($sid) or adminid IN ($sid) or sid IN ($sid) )  ";
    }else{
       $id=$_GET['myappS'];
       $add .= " AND event.ServiceName IN ($id) ";
    } 
}else{
    $id=$_SESSION['UserID'];
    if($_SESSION['usertype']!='Admin'){
        $add .= " AND (event.createdfk IN (select id from users where id IN ($id) or adminid IN ($id) or sid IN ($id) ) or event.ServiceProvider IN ($id))  ";
    }

}

// $stmt= $db4->prepare("SELECT clients.FirstName AS FirstName ,clients.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
//     FROM `event` 
//     JOIN users ON event.ServiceProvider=users.id
//     JOIN clients ON event.cid=clients.id
//     WHERE clients.isactive=1 and event.isactive=1 and event.eventstatus!='canceled' and Accepted = '1' ".$add);

$stmt= $db4->prepare("SELECT clients.FirstName AS FirstName ,clients.LastName AS LastName,event.id AS id, event.EventDate AS EventDate, event.end_date AS end_date, event.eventstatus AS eventstatus, event.title AS title,users.username AS  username,users.userimg,clients.ProfileImg,event.cid AS cid,event.Address AS Address,event.City AS City,event.State AS State,event.Zip AS Zip,event.Country AS Country,event.Location_radio AS Location_radio,event.ServiceName,event.ServiceProvider
    FROM `event` JOIN clients  JOIN users ON users.id=event.ServiceProvider AND event.cid=clients.id
    WHERE event.isactive=1 and event.Accepted = '1'  ".$add); 

$stmt->execute();
$calendar = array();
while($rows=$stmt->fetch(PDO::FETCH_ASSOC)) {  

    date_default_timezone_set("Asia/kolkata");
    $start = strtotime($rows['EventDate']) * 1000;
    $end = strtotime($rows['end_date']) * 1000; 
    date_default_timezone_set("UTC");    

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
        'Deleteurl' => base_url."/Exec/Exec_Edit_Event.php?DeleteEvent&dlink=".base64_encode ($eid),
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
        'providerimg'=>$userimg,
        'full_date'=>$rows['EventDate']

    );
}
$calendarData = array(
    "success" => 1, 
    "result"=>$calendar);
echo json_encode($calendarData);
// }
exit;
?>