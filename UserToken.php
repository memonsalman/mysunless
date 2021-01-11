<?php
require_once('function.php');


function GenerateUserToken($length = 20) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

function CreateRememberToken(){
	$db = new db();
	if(!empty($_SESSION['activeuserid'])){

		$id = $_SESSION['activeuserid'];
		$token = GenerateUserToken(20);
		$saved_users = ["TokenID"=>$id, "Token"=>$token];
		setcookie("REMEMBERSAVEDUSERS", json_encode($saved_users) , time() + (86400 * 30), "/");

		$stmt = $db->prepare("Update ActiveUser set UserToken=:UserToken where id=:id");
		$stmt->bindParam(":id",$id);
		$stmt->bindParam(":UserToken",$token);
		$stmt->execute();

	}

}

function CreateUserToken(){
	$db = new db();
	if(!empty($_SESSION['activeuserid'])){

		$id = $_SESSION['activeuserid'];
		$token = GenerateUserToken(20);
		$saved_users = ["TokenID"=>$id, "Token"=>$token];
		setcookie("SAVEDUSERS", json_encode($saved_users) , time() + (86400 * 30), "/");

		if(isset($_COOKIE['REMEMBERSAVEDUSERS'])){

			$RememberToken = json_decode($_COOKIE['REMEMBERSAVEDUSERS'],true);

			if($RememberToken['TokenID']==$saved_users['TokenID']){
				setcookie("REMEMBERSAVEDUSERS", json_encode($saved_users) , time() + (86400 * 30), "/");
			}

		}

		if(isset($_POST['RememberUser'])){
			setcookie("REMEMBERSAVEDUSERS", json_encode($saved_users) , time() + (86400 * 30), "/");
		}


		$stmt = $db->prepare("Update ActiveUser set UserToken=:UserToken where id=:id");
		$stmt->bindParam(":id",$id);
		$stmt->bindParam(":UserToken",$token);
		$stmt->execute();

	}

}

function CheckUserToken(){
	$db = new db();

	if(!empty($_SESSION['activeuserid']) && isset($_COOKIE["SAVEDUSERS"]) ){

		$id = $_SESSION['activeuserid'];

		$saved_users = json_decode($_COOKIE["SAVEDUSERS"],true);

		$current_token = $saved_users['Token'];
		

		$stmt = $db->prepare("Select UserToken from ActiveUser where id=:id and UserToken=:UserToken and HTTP_USER_AGENT=:HTTP_USER_AGENT and Validity='1' ");
		$stmt->bindParam(":id",$id);
		$stmt->bindParam(":UserToken",$current_token);
		$stmt->bindparam(":HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT']);
		$stmt->execute();


		if($stmt->rowCount()>0){
		 	return true;
		}else{
			return false;
		}

	}else if( isset($_REQUEST['LoginToken']) && !empty($_COOKIE["REMEMBERSAVEDUSERS"]) ){

		$saved_users = json_decode($_COOKIE["REMEMBERSAVEDUSERS"],true);

		$current_token = $saved_users['Token'];
		$id = $saved_users['TokenID'];
		

		$stmt = $db->prepare("Select UserId from ActiveUser where id=:id and UserToken=:UserToken and HTTP_USER_AGENT=:HTTP_USER_AGENT and Validity='1' ");
		$stmt->bindParam(":id",$id);
		$stmt->bindParam(":UserToken",$current_token);
		$stmt->bindparam(":HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT']);
		$stmt->execute();

		if($stmt->rowCount()>0){
			$result = $stmt->fetch();
			$UserId = $result['UserId'];
		 	return ['response'=>$UserId];
		}else{
			return ['error'=>'Invalid Token.'];
		}
	}
	else{
		return ['error'=>'Empty Token.'];
	}

}

function DestroyUserToken(){

	setcookie("RememberToken", 0 , time() - (86400 * 30), "/");
	setcookie("SAVEDUSERS", '' , time() - (86400 * 30), "/");
	setcookie("REMEMBERSAVEDUSERS", '' , time() - (86400 * 30), "/");

}



?>