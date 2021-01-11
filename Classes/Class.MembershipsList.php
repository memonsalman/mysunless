<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



	class MembershipList{



		public function UpcomingRenewals($UpcomingRenewalsDays)
		{

			$db= new db();

			$id= $_SESSION['UserID'] ;

			// $stmt= $db->prepare("SELECT clients.id as cid,clients.FirstName,clients.ProfileImg,clients.LastName,clients.SelectPackage,clients.package_ed,clients.package_sd,users.UserName,MemberPackage.Name FROM `clients` INNER JOIN `users` ON clients.employeeSold = users.id INNER JOIN `MemberPackage` ON clients.SelectPackage = MemberPackage.id  WHERE clients.createdfk= :id AND (`package_ed` >= NOW() AND `package_ed` <= NOW() + INTERVAL :Days DAY)");

			$stmt= $db->prepare("SELECT OrderMembership.*,users.username as UserName,OrderMembership.Noofvisit,MemberPackage.Name,clients.id as cid,clients.FirstName,clients.ProfileImg,clients.LastName FROM `OrderMembership`
LEFT JOIN clients ON clients.id=OrderMembership.Cid
LEFT JOIN MemberPackage ON MemberPackage.id=OrderMembership.MembershipId
LEFT JOIN `users` ON MemberPackage.createdfk = users.id
WHERE OrderMembership.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id) AND OrderMembership.Active='1' AND (`package_expire_date` >= NOW() AND `package_expire_date` <= NOW() + INTERVAL :Days DAY)");

			$stmt->bindValue(":id",$id,PDO::PARAM_INT);

			$stmt->bindValue(":Days",$UpcomingRenewalsDays,PDO::PARAM_INT);

			$stmt->execute();

			$UpcomingRenewals = $stmt-> fetchAll();

			echo json_encode($UpcomingRenewals);die;

		}

		public function CurrentAndPaid($CurrentAndPaidDays){

			$db= new db();

			$id= $_SESSION['UserID'] ;

			// $stmt= $db->prepare("SELECT clients.id as cid,clients.FirstName,clients.ProfileImg,clients.LastName,clients.SelectPackage,clients.package_ed,clients.package_sd,users.UserName,MemberPackage.Name FROM `clients` INNER JOIN `users` ON clients.employeeSold = users.id INNER JOIN `MemberPackage` ON clients.SelectPackage = MemberPackage.id  WHERE clients.createdfk= :id AND (`package_ed` >= NOW() AND `package_ed` <= NOW() + INTERVAL :Days DAY)");

			$stmt= $db->prepare("SELECT OrderMembership.*,users.username as UserName,MemberPackage.Name,clients.id as cid,clients.FirstName,OrderMembership.Noofvisit,clients.ProfileImg,clients.LastName FROM `OrderMembership`
LEFT JOIN clients ON clients.id=OrderMembership.Cid
LEFT JOIN MemberPackage ON MemberPackage.id=OrderMembership.MembershipId
LEFT JOIN `users` ON MemberPackage.createdfk = users.id
WHERE OrderMembership.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id) AND OrderMembership.Active='1' AND ((`package_expire_date` >= NOW() AND `package_expire_date` <= NOW() + INTERVAL :Days DAY) OR `package_expire_date`='Never')");

			$stmt->bindValue(":id",$id,PDO::PARAM_INT);

			$stmt->bindValue(":Days",$CurrentAndPaidDays,PDO::PARAM_INT);

			$stmt->execute();
			
			$CurrentAndPaid = $stmt-> fetchAll();

			echo json_encode($CurrentAndPaid);die;

		}

		public function NotPaid($NotPaidDays){

			$db= new db();

			$id= $_SESSION['UserID'] ;

			$stmt= $db->prepare("SELECT OrderMembership.*,users.username as UserName,MemberPackage.Name,clients.id as cid,clients.FirstName,OrderMembership.Noofvisit,clients.ProfileImg,clients.LastName FROM `OrderMembership`
LEFT JOIN clients ON clients.id=OrderMembership.Cid
LEFT JOIN MemberPackage ON MemberPackage.id=OrderMembership.MembershipId
LEFT JOIN `users` ON MemberPackage.createdfk = users.id
WHERE OrderMembership.createdfk IN (select id from users where id=:id or adminid=:id or sid=:id) AND OrderMembership.Active='1' AND (((`package_expire_date` < CURDATE()) AND `package_expire_date`<>'Never'))");

			$stmt->bindValue(":id",$id,PDO::PARAM_INT);

			//$stmt->bindValue(":Days",$NotPaidDays,PDO::PARAM_INT);

			$stmt->execute();

			$NotPaid = $stmt-> fetchAll();

			echo json_encode($NotPaid);die;

		}

	}

?>