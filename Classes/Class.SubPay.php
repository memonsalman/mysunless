<?php

require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');



	class MembershipList{



		public function UpcomingRenewals($UpcomingRenewalsDays){

			$db= new db();
$stmt= $db->prepare("SELECT users.id as UserID,	users.username,users.userimg as userimg ,users.email,users.phonenumber,payments.PackageType,payments.packend AS package_ed,payments.paytime FROM `users` JOIN payments ON users.id=payments.userid WHERE payments.status='Active' and payments.packend>= NOW() AND payments.packend<= NOW() + INTERVAL :Days DAY");
			$stmt->bindValue(":Days",$UpcomingRenewalsDays,PDO::PARAM_INT);
			$stmt->execute();
			$UpcomingRenewals = $stmt-> fetchAll();
			echo json_encode($UpcomingRenewals);die;

		}

		public function CurrentAndPaid($CurrentAndPaidDays){

			$db= new db();
$stmt= $db->prepare("SELECT users.id as UserID,users.username,users.userimg as userimg ,users.email,users.phonenumber,payments.PackageType,payments.packend AS package_ed,payments.paytime FROM `users` JOIN payments ON users.id=payments.userid WHERE payments.status='Active' and payments.paytime<= NOW() AND payments.packend>= NOW() + INTERVAL :Days DAY");
			$stmt->bindValue(":Days",$CurrentAndPaidDays,PDO::PARAM_INT);
			$stmt->execute();
			$CurrentAndPaid = $stmt-> fetchAll();
			echo json_encode($CurrentAndPaid);die;

		}

		public function NotPaid($NotPaidDays){

			$db= new db();
			$stmt= $db->prepare("SELECT users.id as UserID,users.username,users.userimg as userimg ,users.email,users.phonenumber,payments.* FROM `users` JOIN payments ON users.id=payments.userid WHERE payments.id IN ( select MAX(id)from payments where  packend < CURDATE() and userid not in (Select userid from payments where status='Active' and packend>= NOW()) group by userid) order by packend");
			$stmt->execute();
			$NotPaid = $stmt-> fetchAll();
			echo json_encode($NotPaid);die;

		}

	}

?>