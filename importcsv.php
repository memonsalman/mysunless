<?php

require_once('function.php');
require_once('global.php');

if(empty($_SESSION["UserID"]) && empty($_SESSION["UserID"])){
	header("Location: index.php");die;
}


if($_SESSION['usertype']=='Admin'){
	if(isset($_SESSION['SetUserForImport'])){
		$sid = $_SESSION['SetUserForImport'];
	}else{
		echo json_encode(['error'=>'Invalid User. Please try again.']);die;
	}
}else{
	$sid = $_SESSION['UserID'];	
}


if($_SESSION['usertype']!="subscriber"){
	$stmt= $db->prepare("SELECT ClientsLimit FROM `users` WHERE adminid=:id and ClientCreate='0'"); 
}else{
	$stmt= $db->prepare("SELECT ClientsLimit FROM `users` WHERE id=:id and ClientCreate='0' "); 
}
$stmt->bindParam(':id', $sid, PDO::PARAM_INT);
$stmt->execute();

if ( $stmt->rowCount() > 0 ){	
	echo json_encode(['error'=>'You do not have the permission of add clients.']);die;
}



require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/assets/PHPEXCEL/Classes/PHPExcel.php');

if(!empty($_FILES)){ 

	$mimes = ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

	if(in_array($_FILES["excelFileSelect"]["type"],$mimes)){
		$inputFileName = 'uploads/clients_import/'.basename($_FILES['excelFileSelect']['name']);
		move_uploaded_file($_FILES['excelFileSelect']['tmp_name'], $inputFileName);

		try{
			$inputFileType  =   PHPExcel_IOFactory::identify($inputFileName);
			$objReader      =   PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel    =   $objReader->load($inputFileName);
		}catch(Exception $e){
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sheet = $objPHPExcel->getActiveSheet(); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$response = array();
		$response['invalid_row'] = [];
		$response['already_email'] = [];
		//skip row 1 for heading
		for ($row = 2; $row <= $highestRow; $row++){ 
			$getRow = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
				NULL,
				TRUE,
				FALSE);

			$getData = $getRow[0];

			if (!isset($getData[0]) || !isset($getData[1]) || !isset($getData[2]) || !isset($getData[3]) || !isset($getData[4]) || !isset($getData[5]) || !isset($getData[6]) || !isset($getData[7]) || !isset($getData[8]) || preg_match("/^[a-zA-Z ]*$/",$getData[3])) 
			{
				array_push($response['invalid_row'],$row);
				continue;
			}

			$db= new db();
			$email = stripslashes(strip_tags($getData[3]));

			$Allusers= $db->prepare ("SELECT `email` FROM `clients` WHERE `email`=:email AND createdfk in (select u3.id from users u1 join users u2 join users u3 on (u1.id=u2.id or u1.adminid=u2.id) and (u2.id=u3.adminid or u2.id=u3.id) where u1.id=:id and isactive = '1' GROUP by u3.id)");
			$Allusers->bindparam(':email',$email, PDO::PARAM_STR);
			$Allusers->bindparam(':id',$sid);
			$Allusers->execute();

			if ( $Allusers->rowCount() > 0 ){
				array_push($response['already_email'],$row);
				continue;
			}



			$FirstName = stripslashes(strip_tags($getData[0]));
			$LastName = stripslashes(strip_tags($getData[1]));
			$Phone = stripslashes(strip_tags($getData[2]));
			$email = stripslashes(strip_tags($getData[3]));
			$Solution = "";
			$PrivateNotes = "";
			$Address = stripslashes(strip_tags($getData[4]));
			$Zip = stripslashes(strip_tags($getData[5]));
			$City = stripslashes(strip_tags($getData[6]));
			$State = stripslashes(strip_tags($getData[7]));
			$Country = stripslashes(strip_tags($getData[8]));
			$datecreated = date("Y-m-d H:i:s");
			$datelastupdated = date("Y-m-d H:i:s");
			$createdfk = $sid;
			$updatedfk = $sid;
			$isactive = 1;

			$db= new db();
			$stmt = $db->prepare("INSERT INTO `clients`(`sid`, `FirstName`, `LastName`, `Phone`, `email`, `Solution`, `PrivateNotes`, `Address`, `Zip`, `City`, `State`, `Country`, `datecreated`, `datelastupdated`, `createdfk`, `updatedfk`, `isactive`) VALUES (:sid, :FirstName, :LastName, :Phone, :email, :Solution, :PrivateNotes, :Address, :Zip, :City, :State, :Country, :datecreated, :datelastupdated, :createdfk, :updatedfk, :isactive)");

			$stmt->bindParam(':sid', $sid);
			$stmt->bindParam(':FirstName', $FirstName);
			$stmt->bindParam(':LastName', $LastName);
			$stmt->bindParam(':Phone', $Phone);
			$stmt->bindParam(':email',$email );
			$stmt->bindParam(':Solution',$Solution);
			$stmt->bindParam(':PrivateNotes',$PrivateNotes );
			$stmt->bindParam(':Address',$Address);
			$stmt->bindParam(':Zip',$Zip );
			$stmt->bindParam(':City',$City );
			$stmt->bindParam(':State',$State );
			$stmt->bindParam(':Country',$Country);
			$stmt->bindParam(':datecreated', $datecreated);
			$stmt->bindParam(':datelastupdated', $datelastupdated);
			$stmt->bindParam(':createdfk', $createdfk);
			$stmt->bindParam(':updatedfk', $updatedfk);
			$stmt->bindParam(':isactive', $isactive);
			$file_imported= $stmt->execute();

			if(!$file_imported){
				echo json_encode(['error'=>'Sorry Something Wrong']);die;  
			}
		}

		echo json_encode(['response'=>$response]);die;
	}
}

?>