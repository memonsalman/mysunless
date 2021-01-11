<?php
require_once("Class.Config.php");
require_once($_SERVER["DOCUMENT_ROOT"].SUB.'/function.php');

class  Activites{
    private function getUserIpAddr()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


	public function commit_acitve($Titile)
	{

		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) 
        $borser='Internet explorer';
		
		elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) 
        $borser='Mozilla Firefox';
        
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) 
        $borser='Google Chrome';

        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) 
        $borser='Opera';

    	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) 
        $borser='Safari';

    	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== FALSE) 
        $borser='Edge';

    	elseif(empty($borser)) 
        $borser='Private Browser';


		$db=new db();

		
            if(isset($_SESSION['UserID']))
             {
                   @$id=$_SESSION['UserID']; 
            }
            else
            {
                @$id=$_COOKIE["mycockid"];
            }



		$createdtime=date("Y-m-d H:i:s");
		$ip=$this->getUserIpAddr();
	
		
		 $insert_data=$db->prepare("INSERT INTO Activities(UserID,Titile,createdtime,ip,borser) VALUES(:id,:Titile,:createdtime,:ip,:borser)");
		// $insert_data=$db->prepare("INSERT INTO Activities(Titile,createdtime,ip,borser) VALUES(:Titile,:createdtime,:ip,:borser)");
		$insert_data->bindparam(":id",$id);
		$insert_data->bindparam(":ip",$ip);
		$insert_data->bindparam(":Titile",$Titile);
		$insert_data->bindparam(":createdtime",$createdtime);
		$insert_data->bindparam(":borser",$borser);
		// $insert_data->bindparam(":sid",$sid);
		$insert_data->execute();
        
        		

	}
}
?>