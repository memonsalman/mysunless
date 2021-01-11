<?php
require_once('function.php');
require_once('global.php');
require_once($_SERVER["DOCUMENT_ROOT"].$SUB.'/assets/PHPEXCEL/Classes/PHPExcel.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$objExcel = new PHPExcel();
	$objExcel->setActiveSheetIndex(0);

	// $data = $_POST["ids"];
	// $ids = implode(',', $data);
		$ids = $_GET["ids"];

		if($ids == "" && $_SESSION["usertype"] == "Admin")
		{
			$clientsdata = $db->prepare("SELECT * FROM `clients`");
		}
		else if($ids == "" && $_SESSION["usertype"] != "Admin")
		{
				$clientsdata = $db->prepare("SELECT * FROM `clients` WHERE sid = :id");
				$clientsdata->bindParam(":id",$_SESSION["UserID"]);	
		}
		else
		{
			$clientsdata = $db->prepare("SELECT * FROM `clients` WHERE id IN($ids)");	
		}
$clientsdata->execute();
$objExcel->getActiveSheet()->setCellValue('A1','FirstName');
$objExcel->getActiveSheet()->setCellValue('B1','LastName');
$objExcel->getActiveSheet()->setCellValue('C1','Phone number');
$objExcel->getActiveSheet()->setCellValue('D1','EmailID');
$objExcel->getActiveSheet()->setCellValue('E1','Address');
$objExcel->getActiveSheet()->setCellValue('F1','Zip');
$objExcel->getActiveSheet()->setCellValue('G1','City');
$objExcel->getActiveSheet()->setCellValue('H1','State');
$objExcel->getActiveSheet()->setCellValue('I1','Country');

	$rowCount = 2;
	while ($cdata = $clientsdata->fetch(PDO::FETCH_ASSOC)) 
	{

			$objExcel->getActiveSheet()->setCellValue('A'.$rowCount,$cdata["FirstName"]);
			$objExcel->getActiveSheet()->setCellValue('B'.$rowCount,$cdata["LastName"]);
			$objExcel->getActiveSheet()->setCellValue('C'.$rowCount,$cdata["Phone"]);
			$objExcel->getActiveSheet()->setCellValue('D'.$rowCount,$cdata["email"]);
			$objExcel->getActiveSheet()->setCellValue('E'.$rowCount,$cdata["Address"]);
			$objExcel->getActiveSheet()->setCellValue('F'.$rowCount,$cdata["Zip"]);
			$objExcel->getActiveSheet()->setCellValue('G'.$rowCount,$cdata["City"]);
			$objExcel->getActiveSheet()->setCellValue('H'.$rowCount,$cdata["State"]);
			$objExcel->getActiveSheet()->setCellValue('I'.$rowCount,$cdata["Country"]);
			$rowCount++;
	}
	$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
	$filename = "mysunless.xlsx";
	header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
	header('Content-Type: application/vnd.ms-excel'); 
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');  
	$objWriter->save('php://output',"w");
	?>