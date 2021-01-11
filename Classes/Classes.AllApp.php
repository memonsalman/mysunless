<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class Display{

	public function AjaxAppDisplay()
	{

		$db=new db();
		$id=$_REQUEST['spid'];;
		$UpcomingRenewals=$_REQUEST['UpcomingRenewals'];
		$search = $_REQUEST['search'];
		if($UpcomingRenewals=='Last')
		{
			$LoginQuery= $db->prepare("SELECT event.*,users.username,clients.ProfileImg,clients.id as clientid FROM `event` JOIN clients  JOIN users ON users.id=event.ServiceProvider AND event.cid=clients.id WHERE ( event.FirstName like '%".$search."%' or event.LastName like '%".$search."%' or event.Email like '%".$search."%' or event.title like '%".$search."%' or event.id='".$search."' ) and event.Accepted = '1' and event.createdfk=:id AND event.EventDate<= NOW() AND event.EventDate>= NOW() - INTERVAL 30 DAY order by event.EventDate DESC"); 	
		}
		else if ($UpcomingRenewals=='Next') 
		{
			$LoginQuery= $db->prepare("SELECT event.*,users.username,clients.ProfileImg,clients.id as clientid FROM `event` JOIN clients  JOIN users ON users.id=event.ServiceProvider AND event.cid=clients.id WHERE ( event.FirstName like '%".$search."%' or event.LastName like '%".$search."%' or event.Email like '%".$search."%' or event.title like '%".$search."%' or event.id='".$search."' ) and event.Accepted = '1' and event.createdfk=:id AND event.EventDate>= NOW() AND event.EventDate<= NOW() + INTERVAL 30 DAY order by event.EventDate"); 	
		}
		else
		{	

			$LoginQuery= $db->prepare("SELECT event.*,users.username,clients.ProfileImg,clients.id as clientid FROM `event` JOIN clients JOIN users ON users.id=event.ServiceProvider AND event.cid=clients.id WHERE ( event.FirstName like '%".$search."%' or event.LastName like '%".$search."%' or event.Email like '%".$search."%' or event.title like '%".$search."%' or event.id='".$search."' ) and event.Accepted = '1' and event.createdfk=:id order by event.EventDate"); 		
		}

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);
		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		foreach ($result as $key => $rows) {

			$start = strtotime($rows['EventDate']) * 1000;
			$end = strtotime($rows['end_date']) * 1000; 
			$stime2=strtotime($rows['EventDate']);
			$stime=date("h:ia",$stime2);
			$onlystime=date("m/d",$stime2);
			$etime=date("h:ia",$stime2);

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
			$eventadders = $rows['Address'].', '.$rows['City'].', '.$rows['State'].', '.$rows['Zip'].', '.$rows['country'];
			$eid=$rows['id'];
			$cid=$rows['cid'];
			$calendar= array(
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
				'etime'=> "$etime",
				'username'=> $rows['username'],
				'eventadders'=>"https://maps.google.com/?q=".$eventadders."&output=embed",
				'onlyeventadders'=>$eventadders,
				'evnetloction'=>$rows['Location_radio'],
				'ServiceName'=>base64_encode($rows['ServiceName']),
				'ServiceProvider'=>base64_encode($rows['ServiceProvider']),
			);
			$result[$key]['calendar'] = [];
			$result[$key]['calendar']=$calendar;
		}

		if($result){
			echo json_encode($result);die;	
		}else{
			echo json_encode(false);die;	
		}
	}



}



?>