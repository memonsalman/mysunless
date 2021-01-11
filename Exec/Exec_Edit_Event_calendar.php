<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('Exec_Config.php');        
require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.event.php'); 



//get_service_time
if(isset($_GET['get_calendar_service_time'])){

	if(isset($_POST['service_provider']) && $_POST['service_provider']!="" && isset($_POST['service_date']) && $_POST['service_date']!="" && $_POST['duration'] && $_POST['duration']!="" ){

		$ServiceProvider = json_decode($_POST['service_provider'],true);
		//$ServiceProvider = [0=>['id'=>$_POST['service_provider']]];
		
		$response = $ServiceProvider;

		


		foreach ($ServiceProvider as $service_provider_key => $service_provider) 
		{
		
		$service_provider_id = $service_provider['id'];


		$service = new db();
		$query = $service->prepare("Select timetable from users where id=?");
		$query->execute([$service_provider_id]);
		$result = $query->fetch(PDO::FETCH_ASSOC);
		if($result && ($query->rowCount() > 0))
		{
			$timetable = [];
			$timetable = json_decode($result["timetable"],true);

			$day_number = ["Monday"=>0,"Tuesday"=>1,"Wednesday"=>2,"Thursday"=>3,"Friday"=>4,"Saturday"=>5,"Sunday"=>6];

			// $start_date = date("Y-m-d",strtotime($_POST['service_date']));
			$startDate = date("Y-m-d",strtotime($_POST['service_date']));

			$endDate = date("Y-m-t", strtotime($startDate));

			$start_dates=[];

			while(date("Y-m-d",strtotime($startDate))<=date("Y-m-d",strtotime($endDate))){
				array_push($start_dates,$startDate);
				$startDate = date('Y-m-d', strtotime($startDate. ' + 1 days'));
			}

			foreach ($start_dates as $start_date) {

				$day = date("l", strtotime($start_date));


			if($timetable[$day_number[$day]][$day]){

				$starttime = $start_date." ".$timetable[$day_number[$day]]['starttime'];
				$endtime = $start_date." ".$timetable[$day_number[$day]]['endtime'];

				$duration = explode(" ",$_POST['duration']);
				$gap = "";
				if($duration[1]=="h"){
					$gap = "+".$duration[0]." hour";
				}else if($duration[1]=="Min"){
					$gap = "+".$duration[0]." minutes";
				}

				$time_slot = [];
				$convertedTime = date('H:i:s',strtotime($starttime));

					while(date('H:i:s',strtotime($convertedTime)) < date('H:i:s',strtotime($endtime)) ){

						array_push($time_slot, date('h:ia',strtotime($convertedTime))."-".date('h:ia',strtotime($gap,strtotime($convertedTime))));

						$convertedTime = date('H:i:s',strtotime($gap,strtotime($convertedTime)));
					}
				

				
				$query = $service->prepare("Select EventDate,end_date from event where Accepted <> '2' and  eventstatus <> 'Canceled' and ServiceProvider=? and DATE_FORMAT(EventDate, '%Y-%m-%d') IN ( DATE_FORMAT('$start_date', '%Y-%m-%d') ) ");

				$query->execute([$service_provider_id]);
				$result = $query->fetchAll(PDO::FETCH_ASSOC);


				if($result && ($query->rowCount() > 0)){

					foreach ($result as $value) {

						foreach ($time_slot as $key => $slot) {


							$slot = explode("-",$slot);

							$EventDate = date('H:i:s',strtotime($value['EventDate']));
							$end_date = date('H:i:s',strtotime($value['end_date']));
							$slot1 = date('H:i:s',strtotime($slot[0]));
							$slot2 = date('H:i:s',strtotime($slot[1]));

                            //if(($slot1 <= $EventDate && $slot2 > $EventDate) || ( $slot1 < $end_date && $slot2 > $end_date )){
							if( ($EventDate <= $slot1 && $end_date > $slot1) || ($EventDate < $slot2 && $end_date > $slot2) ){

                                // echo $time_slot[$key]."<br>";
								unset($time_slot[$key]);
							}

						}

					}
					$time_slot =  array_values($time_slot);

				}


				if($time_slot){
					
					$response[$service_provider_key]['time_slot'][date("j",strtotime($start_date))]=$time_slot;
					
				}
			
			}

				
				
			}
		}
		else
		{
			echo json_encode(["error"=>"Invalid Service Provider"]);die;
		}

		}
	}
	else{
		echo json_encode(["error"=>"Service Provider and Date are required."]);die;
	}

	echo json_encode($response);die;

}


?>