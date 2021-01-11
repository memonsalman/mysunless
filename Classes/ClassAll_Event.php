<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');
require(Classes.'/Class.Datatable.php'); //Used on Server-Side Datatable

class Display{

	public function AjaxDisplay()
	{

		$db=new db();

		if($_SESSION['usertype']!='Admin'){
			$id = $_SESSION['UserID'];
			$id=" Select id from users where id=$id or adminid=$id ";
			$id = "and event.createdfk IN ($id)";
		}else{
			$id = "";
		}


		$data = json_decode($_REQUEST['EventFilter']);

		$search = $data->search;
		$date = $data->date;
		$user = implode(',',$data->user);
		$customer = implode(',',$data->customer);
		$status = implode(',',$data->status);
		

		if(!empty($search)){
			$search = " and ( clients.FirstName like '%".$search."%' or clients.LastName like '%".$search."%' or clients.email like '%".$search."%' or event.title like '%".$search."%' or event.id='".$search."' ) ";
		}

		if(!empty($date)){

			$date =explode(' - ',$date);
			$fromdate = date("Y-m-d", strtotime($date[0]));
			$todate = date("Y-m-d", strtotime($date[1]));

			$date = " AND  DATE_FORMAT(event.EventDate, '%Y-%m-%d')>='$fromdate' 
			AND DATE_FORMAT(event.EventDate, '%Y-%m-%d')<='$todate' ";

		}

		if(!empty($status)){

			// $status = "'".implode("','",$status)."'";
			$status = " AND FIND_IN_SET(event.eventstatus,'$status') ";
		}else{
			$status = "";
		}

		if(!empty($user)){
			
			$user = " AND event.ServiceProvider IN ($user) ";
		}

		if(!empty($customer)){
			
			$customer = " AND event.cid IN ($customer) ";
		}

		$OrderString = " order by event.EventDate DESC";

		//Server Side datatable

		if(isset($_REQUEST['start']) && isset($_REQUEST['length'])){

			$start = $_REQUEST['start'];
			$length = $_REQUEST['length'];

			$Limit = " LIMIT $start,$length ";
		}else{
			$Limit = "";
		}

        
        
        if(!empty($_REQUEST['order'])){
            $OrderString = DT_OrderBy($_REQUEST['order']);
        }

        $SearchString = "";
        if(!empty($_REQUEST['search']['value'])){

            $SearchString = ' where '.DT_Search($_REQUEST['search']['value']);
        }


		$Query = "SELECT * from (
			SELECT event.*,clients.Phone as client_phone,users.phonenumber,users.username,users.userimg,users.firstname as User_firstname,users.lastname as User_lastname,clients.ProfileImg,clients.FirstName as client_firstname,clients.LastName as client_Lastname,clients.email as client_email,clients.id as clientid, OrderMaster.id as OrderID,OrderMaster.InvoiceNumber FROM `event` JOIN clients  JOIN users ON users.id=event.ServiceProvider AND event.cid=clients.id left join OrderMaster on OrderMaster.eid=event.id and OrderMaster.payment_status='CAPTURED' WHERE event.isactive=1 and event.Accepted = '1' ".$id.$search.$date.$user.$customer.$status.$OrderString.") as EventList";


		if(isset($_REQUEST['EventDatatable'])){
	        $response = DT_SQL($Query,'',$SearchString,$Limit);
	        echo $response; die;
		}


		$query= $db->prepare($Query); 

		$query->execute();

		$result = $query->fetchAll();

		foreach ($result as $key => $rows) {

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
			$clientname = ucfirst($rows['client_firstname']).' '.ucfirst($rows['client_Lastname']);
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
				'clientID'=>base64_encode ($cid),
				'customerimg'=>$ProfileImg,
		        'providerimg'=>$userimg,
		        'full_date'=>$rows['EventDate']
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