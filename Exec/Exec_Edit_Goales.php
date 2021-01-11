<?php
    require_once('Exec_Config.php');        
	

require_once($_SERVER["DOCUMENT_ROOT"].ESUB.'/function.php');
require_once(Classes.'/Class.Goales.php'); 
$myevent = new event($_POST["id"]);
$myevent->id = $_POST["id"];
$myevent->UserID = $_POST["UserID"];
$MonthlyGoal=$myevent->MonthlyGoal = $_POST["MonthlyGoal"];
$YearlyGoal=$myevent->YearlyGoal = $_POST["YearlyGoal"];
// $Riminederdate=implode(',',$Riminederdate2);
$myevent->commit($myevent->id);
if($myevent)
{
    echo json_encode(['resonse'=>'Goal successfully add']);die;
}
else
{
    echo json_encode(['error'=>'sorry something wrong']);die;
}
?>