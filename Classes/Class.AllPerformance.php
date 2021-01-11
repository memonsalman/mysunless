<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class User{

	public function UserDisplay()

	{

				

		$db=new db();

		$id=$_SESSION['UserID'];

		$LoginQuery = $db->prepare("select users.username,users.userimg, CountActivites.Createid, count(CountActivites.ClientCreate) AS ClientC,count(CountActivites.UserCreate) AS UserC,count(CountActivites.EmployeeCreate) AS EmployeeC,count(CountActivites.AppointmentCreate) AS AppointmentC,count(CountActivites.EmailCreate) AS EmailC,count(CountActivites.SmsCreate) AS SmsC,count(CountActivites.OredrCreate) AS OrderC from CountActivites

LEFT JOIN users ON users.id = CountActivites.Createid



WHERE ((users.adminid=:id)) AND ((CountActivites.ClientCreate IS NOT null) OR (CountActivites.UserCreate IS NOT null) OR (CountActivites.EmployeeCreate IS NOT null) OR (CountActivites.AppointmentCreate IS NOT null) OR (CountActivites.EmailCreate IS NOT null) OR (CountActivites.SmsCreate IS NOT null) OR (CountActivites.OredrCreate IS NOT null)) GROUP By users.username,users.userimg,CountActivites.Createid,CountActivites.ClientCreate,CountActivites.EmployeeCreate,CountActivites.UserCreate,CountActivites.AppointmentCreate,CountActivites.EmailCreate,CountActivites.SmsCreate,CountActivites.OredrCreate");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;	

	}


public function count_activity($daterange){

	$result = [];

	if($daterange){

		$selectdaterang = explode(' - ',$daterange);
		$fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
		$todate = date("Y-m-d", strtotime($selectdaterang[1]));
		
		$db=new db();

		$id=$_SESSION['UserID'];

		$query = $db->prepare("Select id from users where id=:id or adminid=:id or sid=:id");
		$query->bindParam(':id', $id);
		$query->execute();

		$users = $query->fetchAll();

		foreach ($users as $key => $user) {
		
		$query = $db->prepare("Select * from (
			(SELECT count(*) as email FROM `FullCom` where type='email' and Createid=:id and date_format(comtime,'%Y-%m-%d')>=:fromdate and date_format(comtime,'%Y-%m-%d')<=:todate) as email ,

			(SELECT count(*) as sms FROM `FullCom` where type='sms' and Createid=:id and date_format(comtime,'%Y-%m-%d')>=:fromdate and date_format(comtime,'%Y-%m-%d')<=:todate) as sms,

			(SELECT count(*) as app_book FROM event where createdfk=:id and Accepted<>'2' and date_format(datecreated,'%Y-%m-%d')>=:fromdate and date_format(datecreated,'%Y-%m-%d')<=:todate ) as app_book,

			(SELECT count(*) as app_confirm FROM event where createdfk=:id and Accepted='1' and (eventstatus='confirmed' or  eventstatus='completed') and date_format(datecreated,'%Y-%m-%d')>=:fromdate and date_format(datecreated,'%Y-%m-%d')<=:todate) as app_confirm,

			(SELECT count(*) as order_no FROM OrderMaster where createdfk=:id and date_format(datecreated,'%Y-%m-%d')>=:fromdate and date_format(datecreated,'%Y-%m-%d')<=:todate) as order_no,

			(SELECT username,userimg FROM users where id=:id) as users
		)");

		$query->bindParam(':id', $user['id']);

		$query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);

		$query->bindParam(':todate', $todate, PDO::PARAM_STR);

		$query->execute();

		$data = $query->fetch();

		array_push($result,$data);

		}

		echo json_encode($result);die;		

	}else{
		$result = [];

		$db=new db();

		$id=$_SESSION['UserID'];

		$query = $db->prepare("Select id from users where id=:id or adminid=:id or sid=:id");
		$query->bindParam(':id', $id);
		$query->execute();

		$users = $query->fetchAll();

		foreach ($users as $key => $user) {
		
		$query = $db->prepare("Select * from (
			(SELECT count(*) as email FROM `FullCom` where type='email' and Createid=:id) as email ,
			(SELECT count(*) as sms FROM `FullCom` where type='sms' and Createid=:id) as sms,
			(SELECT count(*) as app_book FROM event where createdfk=:id and Accepted<>'2' ) as app_book,
			(SELECT count(*) as app_confirm FROM event where createdfk=:id and Accepted='1' and (eventstatus='confirmed' or  eventstatus='completed') ) as app_confirm,
			(SELECT count(*) as order_no FROM OrderMaster where createdfk=:id ) as order_no,
			(SELECT username,userimg FROM users where id=:id) as UserC )");

		$query->bindParam(':id', $user['id']);

		$query->execute();

		$data = $query->fetch();

		array_push($result,$data);

		}

		echo json_encode($result);die;		

	}
}

public function particulerdate($daterange)

	{

		$selectdaterang = explode(' - ',$_GET['service_star_time']);
      $fromdate = date("Y-m-d", strtotime($selectdaterang[0]));
      $todate = date("Y-m-d", strtotime($selectdaterang[1]));

		

		$db=new db();

		$id=$_SESSION['UserID'];

			


// 		$LoginQuery = $db->prepare("select CountActivites.CreatedTime, users.username,users.userimg, CountActivites.Createid, count(CountActivites.ClientCreate) AS ClientC,count(CountActivites.UserCreate) AS UserC,count(CountActivites.EmployeeCreate) AS EmployeeC,count(CountActivites.AppointmentCreate) AS AppointmentC,count(CountActivites.EmailCreate) AS EmailC,count(CountActivites.SmsCreate) AS SmsC,count(CountActivites.OredrCreate) AS OrderC from CountActivites

// LEFT JOIN users ON users.id = CountActivites.Createid



// WHERE ((users.adminid=:id) AND (CountActivites.CreatedTime>=:fromdate AND CountActivites.CreatedTime<=:todate)) AND ((CountActivites.ClientCreate IS NOT null) OR (CountActivites.UserCreate IS NOT null) OR (CountActivites.EmployeeCreate IS NOT null) OR (CountActivites.AppointmentCreate IS NOT null) OR (CountActivites.EmailCreate IS NOT null) OR (CountActivites.SmsCreate IS NOT null) OR (CountActivites.OredrCreate IS NOT null))  GROUP By CountActivites.CreatedTime,users.username,users.userimg,CountActivites.Createid,CountActivites.ClientCreate,CountActivites.UserCreate,CountActivites.AppointmentCreate,CountActivites.EmailCreate,CountActivites.SmsCreate,CountActivites.OredrCreate");

		$LoginQuery = $db->prepare("Select CreatedTime,username,userimg,Createid,SUM(ClientC) as ClientC, SUM(UserC) as UserC, SUM(EmployeeC) as EmployeeC, SUM(AppointmentC) as AppointmentC, SUM(EmailC) as EmailC,SUM(SmsC) as SmsC, SUM(OrderC) as OrderC from (select CountActivites.CreatedTime, users.username,users.userimg, CountActivites.Createid, count(CountActivites.ClientCreate) AS ClientC,count(CountActivites.UserCreate) AS UserC,count(CountActivites.EmployeeCreate) AS EmployeeC,count(CountActivites.AppointmentCreate) AS AppointmentC,count(CountActivites.EmailCreate) AS EmailC,count(CountActivites.SmsCreate) AS SmsC,count(CountActivites.OredrCreate) AS OrderC from CountActivites

LEFT JOIN users ON users.id = CountActivites.Createid



WHERE ((users.adminid=:id) AND (CountActivites.CreatedTime>=:fromdate AND CountActivites.CreatedTime<=:todate)) AND ((CountActivites.ClientCreate IS NOT null) OR (CountActivites.UserCreate IS NOT null) OR (CountActivites.EmployeeCreate IS NOT null) OR (CountActivites.AppointmentCreate IS NOT null) OR (CountActivites.EmailCreate IS NOT null) OR (CountActivites.SmsCreate IS NOT null) OR (CountActivites.OredrCreate IS NOT null))  
GROUP By CountActivites.CreatedTime,users.username,users.userimg,CountActivites.Createid,CountActivites.ClientCreate,CountActivites.UserCreate,CountActivites.AppointmentCreate,CountActivites.EmailCreate,CountActivites.SmsCreate,CountActivites.OredrCreate) as table1 group by CreatedTime,username,userimg,Createid,ClientC,UserC,EmployeeC,AppointmentC,EmailC,SmsC,OrderC");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);

		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;		

			

	}


/*public function particulerdate($service_star_time,$service_end_time)

	{

		

		 $fromdate= date("Y-m-d", strtotime($service_star_time));

		 $todate=date("Y-m-d", strtotime($service_end_time));

		

		$db=new db();

		$id=$_SESSION['UserID'];

		$LoginQuery = $db->prepare("select CountActivites.CreatedTime, users.username,users.userimg, CountActivites.Createid, count(CountActivites.ClientCreate) AS ClientC,count(CountActivites.UserCreate) AS UserC,count(CountActivites.EmployeeCreate) AS EmployeeC,count(CountActivites.AppointmentCreate) AS AppointmentC,count(CountActivites.EmailCreate) AS EmailC,count(CountActivites.SmsCreate) AS SmsC,count(CountActivites.OredrCreate) AS OrderC from CountActivites

LEFT JOIN users ON users.id = CountActivites.Createid



WHERE ((users.adminid=:id) AND (CountActivites.CreatedTime>=:fromdate AND CountActivites.CreatedTime<=:todate)) AND ((CountActivites.ClientCreate IS NOT null) OR (CountActivites.UserCreate IS NOT null) OR (CountActivites.EmployeeCreate IS NOT null) OR (CountActivites.AppointmentCreate IS NOT null) OR (CountActivites.EmailCreate IS NOT null) OR (CountActivites.SmsCreate IS NOT null) OR (CountActivites.OredrCreate IS NOT null)) GROUP By CountActivites.Createid");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);

		$LoginQuery->bindParam(':todate', $todate, PDO::PARAM_STR);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();

		echo json_encode($result);die;		

			

	}*/

public function UpcomingRenewals($UpcomingRenewalsDays)

	{
			//echo $_SESSION['UserID'];die;
			$db= new db();

			$id=$_SESSION['UserID'];

		$LoginQuery = $db->prepare("select CountActivites.CreatedTime, users.username,users.userimg, CountActivites.Createid, count(CountActivites.ClientCreate) AS ClientC,count(CountActivites.UserCreate) AS UserC,count(CountActivites.EmployeeCreate) AS EmployeeC,count(CountActivites.AppointmentCreate) AS AppointmentC,count(CountActivites.EmailCreate) AS EmailC,count(CountActivites.SmsCreate) AS SmsC,count(CountActivites.OredrCreate) AS OrderC from CountActivites

LEFT JOIN users ON users.id = CountActivites.Createid



WHERE ((users.adminid=:id) AND (CountActivites.CreatedTime<= NOW() AND CountActivites.CreatedTime>= NOW() - INTERVAL :Days DAY )) AND ((CountActivites.ClientCreate IS NOT null) OR (CountActivites.UserCreate IS NOT null) OR (CountActivites.EmployeeCreate IS NOT null) OR (CountActivites.AppointmentCreate IS NOT null) OR (CountActivites.EmailCreate IS NOT null) OR (CountActivites.SmsCreate IS NOT null) OR (CountActivites.OredrCreate IS NOT null)) GROUP By CountActivites.CreatedTime,users.username,users.userimg,CountActivites.Createid,CountActivites.ClientCreate,CountActivites.UserCreate,CountActivites.AppointmentCreate,CountActivites.EmailCreate,CountActivites.SmsCreate,CountActivites.OredrCreate");

		$LoginQuery->bindParam(':id', $id, PDO::PARAM_INT);

		$LoginQuery->bindValue(":Days",$UpcomingRenewalsDays,PDO::PARAM_INT);

		$LoginQuery->execute();

		$result = $LoginQuery->fetchAll();


		
			return json_encode($result);
		

		}

	



}

?>