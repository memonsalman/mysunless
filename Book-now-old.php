<?php 
ob_start();
require_once("global.php");
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

if(isset($_GET['ref']))
{
			$id = base64_decode($_GET['ref']);
			$stmt2=$db->prepare("SELECT Service.* FROM `Service` JOIN users ON (Service.createdfk=users.id OR Service.createdfk=users.adminid OR Service.createdfk=users.sid) WHERE users.id=:id GROUP BY Service.id"); 
      $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt2->execute();  
      $result_event2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);


		
      $UserName2 = $db->prepare("select username,id,firstname,lastname,city,state,userimg,timetable from `users` where id=:id");
      $UserName2->bindValue(":id",$id,PDO::PARAM_STR);
      $editfile2UserName2=$UserName2->execute();
      $all2userdeatils=$UserName2->fetch(PDO::FETCH_ASSOC);
      if(!$all2userdeatils)
      {
        echo "<script>alert('Url not valid')</script>";
         header("Location: https://mysunless.com");
         die();
      }

      $timetabel = $all2userdeatils['timetable'];
        @$asd= $timetabel;
      
        $asd= json_decode($timetabel, true);
        @$a=array();
        // print_r($asd);
        // die;
        foreach (@$asd as $key => $value) 
        {

            if(@$asd[0]['Monday']!=1)
            {
            array_push($a,1);      
            } 
            
            if(@$asd[1]['Tuesday']!=1)
            {
            array_push($a,2);      
            } 

            if(@$asd[2]['Wednesday']!=1)
            {
            array_push($a,3);      
            } 

            if(@$asd[3]['Thursday']!=1)
            {
            array_push($a,4);      
            } 
           
           if(@$asd[4]['Friday']!=1)
            {
            array_push($a,5);      
            } 
          
            if(@$asd[5]['Saturday']!=1)
            {
            array_push($a,6);      
            } 
          
          if(@$asd[6]['Sunday']!=1)
            {
            array_push($a,0);      
            } 
          
        }

        $daysarray= array_unique($a);
        $a=implode(',',$daysarray);
        


      $Companyimage = $db->prepare("select compimg from `CompanyInformation` where createdfk=:id");
      $Companyimage->bindValue(":id",$id,PDO::PARAM_STR);
      $Companyimage2=$Companyimage->execute();
      $Companyimagedeata=$Companyimage->fetch(PDO::FETCH_ASSOC);


      

}	
	
    if(isset($_REQUEST['Servicename']))
    {
      
       $Servicename=$_POST['Servicename']; 
      $eidtClient = $db->prepare("select ServiceName, Users,Info,Price,CommissionAmount,Duration from `Service` where id=:Servicename");
      $eidtClient->bindValue(":Servicename",$Servicename,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $all=$eidtClient->fetch(PDO::FETCH_ASSOC);

      
      if($editfile)
      {
          echo  json_encode(["resonse"=>$all]);die;

      }
    }


    if(isset($_REQUEST['employeename']))
    {
      
      $employeename=$_POST['employeename']; 
      $eidtClient = $db->prepare("select * from `users` where id=:employeename");
      $eidtClient->bindValue(":employeename",$employeename,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $elall=$eidtClient->fetch(PDO::FETCH_ASSOC);
        

         $timetabel = $elall['timetable'];
        

        $asd = json_decode($timetabel, true);
         
        $a=array();

        if(!empty($timetabel))
        {


        foreach ($asd as $key => $value) 
        {
         
            if($asd[0]['Monday']!=1)
            {
            array_push($a,1);      
            } 
            
            if($asd[1]['Tuesday']!=1)
            {
            array_push($a,2);      
            } 

            if($asd[2]['Wednesday']!=1)
            {
            array_push($a,3);      
            } 

            if($asd[3]['Thursday']!=1)
            {
            array_push($a,4);      
            } 
           
           if($asd[4]['Friday']!=1)
            {
            array_push($a,5);      
            } 
          
            if($asd[5]['Saturday']!=1)
            {
            array_push($a,6);      
            } 
          
          if($asd[6]['Sunday']!=1)
            {
            array_push($a,0);      
            } 
          
        }

        $daysarray= array_unique($a);
        $a=implode(',',$daysarray); 
      }
      else
      {
        $a='';
      }

        

      if($elall)
      {
          echo  json_encode(["resonse"=>$elall,"timetable"=>$a]);die;

      }
    }

    if(isset($_REQUEST['employeename2']))
    {
      
      $useravlidate = $_POST['useravlidate'];
      $employeename2=$_POST['employeename2']; 
      $eidtClient = $db->prepare("select timetable from `users` where id=:employeename2");
      $eidtClient->bindValue(":employeename2",$employeename2,PDO::PARAM_STR);
      $editfile=$eidtClient->execute();
      $elall=$eidtClient->fetch(PDO::FETCH_ASSOC);

      $timetabel = $elall['timetable'];
        

        $asd = json_decode($timetabel, true);

        $a=array();

        if(!empty($timetabel))
        {


        foreach ($asd as $key => $value) 
        {
          

            if($asd[0]['Monday']==1 && $useravlidate=='Monday')
            {
              
              $b['startime']=$asd[0]['starttime'];
              $c['endtime']=$asd[0]['endtime'];
             array_push($a,$b,$c);  

              echo  json_encode(["emoptime"=>$a]);die;
            } 
            
            
            if($asd[1]['Tuesday']==1 && $useravlidate=='Tuesday')
            {
              
               $b['startime']=$asd[1]['starttime'];
              $c['endtime']=$asd[1]['endtime'];
             array_push($a,$b,$c);         
             echo  json_encode(["emoptime"=>$a]);die;
            } 
            
            if($asd[2]['Wednesday']==1 && $useravlidate=='Wednesday')
            {

              $b['startime']=$asd[2]['starttime'];
              $c['endtime']=$asd[2]['endtime'];
              array_push($a,$b,$c);         
            echo  json_encode(["emoptime"=>$a]);die;
            } 
            

            if($asd[3]['Thursday']==1 && $useravlidate=='Thursday')
            {
              
              $b['startime']=$asd[3]['starttime'];
              $c['endtime']=$asd[3]['endtime'];
              array_push($a,$b,$c);         
            echo  json_encode(["emoptime"=>$a]);die;
            } 
            

           if($asd[4]['Friday']==1 && $useravlidate=='Friday')
            {
              $b['startime']=$asd[4]['starttime'];
              $c['endtime']=$asd[4]['endtime'];
              array_push($a,$b,$c);         
            echo  json_encode(["emoptime"=>$a]);die;
            } 
            
          
            if($asd[5]['Saturday']==1 && $useravlidate=='Saturday')
            {
              
              $b['startime']=$asd[5]['starttime'];
              $c['endtime']=$asd[5]['endtime'];
              array_push($a,$b,$c);
              echo  json_encode(["emoptime"=>$a]);die;
            } 
            
          
          if($asd[6]['Sunday']==1 && $useravlidate=='Sunday')
            {
              
              $b['startime']=$asd[6]['starttime'];
              $c['endtime']=$asd[6]['endtime'];
              array_push($a,$b,$c);   
            echo  json_encode(["emoptime"=>$a]);die;
            } 
            
          
        }

      }
      else
      {
        $a='';
      }


      if($elall)
      {
          echo  json_encode(["emoptime"=>$a]);die;

      }
    }

    if(isset($_REQUEST['UserName']))
    {
      
     $UserName=$_POST['UserName']; 
 	    $id=$_POST['UserID'];
      $eidtUserName = $db->prepare("select username,id,firstname,lastname,city,state,userimg from `users` where id IN ($UserName,$id)");
      $editfile2=$eidtUserName->execute();
      $all2=$eidtUserName->fetchAll(PDO::FETCH_ASSOC);


      if($editfile2)
      {
          echo  json_encode(["resonse"=>$all2]);die;

      }
      else
      {
      echo  json_encode(["resonse"=>'']);die;        
      }
    }  

    if(isset($_POST['LoginAction']))  
    {
     	$cfname = $_POST['FirstName'];
     	$clname = $_POST['LastName'];
		
	     $clinetname = $db->prepare("SELECT * FROM `clients` WHERE FirstName=:cfname AND LastName=:clname");
       $clinetname->bindValue(":cfname",$cfname,PDO::PARAM_STR);
       $clinetname->bindValue(":clname",$clname,PDO::PARAM_STR);
       $clinetname2=$clinetname->execute();
       $all2=$clinetname->fetch(PDO::FETCH_ASSOC);
          
        if($all2)
        {

          
        $cid =  $all2['id'];
         $FirstName =  $all2['FirstName'];
         $LastName = $all2['LastName'];
         $Phone = $all2['Phone'];
         $email = $all2['email']; 
         $EventDate = $_POST['EventDate'];
          $eventstatus = $_POST['eventstatus'];
          $Zip = $_POST['Zip'];
          $CostOfService = $_POST['CostOfService'];
          $EmailInstruction = $_POST['EmailInstruction'];
          $title = $_POST['title'];
          $end_date = $_POST['end_date'];
          $UserID = $_POST['createdfk'];
          $ServiceName = $_POST['ServiceName'];
          $ServiceProvider = $_POST['ServiceProvider'];
          $Location_radio = $_POST['Location_radio']; 
          $Address = $all2['Address']; 
          $Zip = $all2['Zip']; 
          $City = $all2['City']; 
          $State = $all2['State']; 
          $Country = $all2['Country']; 
          $datecreated = $_POST['datecreated'];
          $createdfk = $_POST['createdfk'];

        $clinetnameapp = $db->prepare("INSERT INTO event (FirstName,LastName,Phone,Email,EventDate,eventstatus,Address,Zip,City,State,country,CostOfService,EmailInstruction,datecreated,createdfk,title,end_date,UserID,ServiceName,ServiceProvider,cid,Location_radio) values(:FirstName,:LastName,:Phone,:email,:EventDate,:eventstatus,:Address,:Zip,:City,:State,:Country,:CostOfService,:EmailInstruction,:datecreated,:createdfk,:title,:end_date,:UserID,:ServiceName,:ServiceProvider,:cid,:Location_radio)");
        
        $clinetnameapp->bindValue(":FirstName",$FirstName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":LastName",$LastName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Phone",$Phone,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":email",$email,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":EventDate",$EventDate,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":eventstatus",$eventstatus,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Address",$Address,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Zip",$Zip,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":City",$City,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":State",$State,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Country",$Country,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":CostOfService",$CostOfService,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":EmailInstruction",$EmailInstruction,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":title",$title,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":end_date",$end_date,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":UserID",$UserID,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":ServiceName",$ServiceName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":ServiceProvider",$ServiceProvider,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":cid",$cid,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Location_radio",$Location_radio,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":datecreated",$datecreated,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":createdfk",$createdfk,PDO::PARAM_STR);
        $clinetnameapp2=$clinetnameapp->execute();

        if($clinetnameapp2)
      {
          echo  json_encode(["resonse"=>'Thank you your appointments has been booked']);die;

      }

       // $clinetname = $db->prepare("INSERT INTO event (FirstName,LastName,Email,EventDate,eventstatus,Address,Zip,City,State,country,CostOfService,EmailInstruction,datecreated,createdfk,title,end_date,UserID,ServiceName,ServiceProvider,cid,Location_radio) values()");
       // $clinetname->bindValue(":cfname",$cfname,PDO::PARAM_STR);
       // $clinetname->bindValue(":clname",$clname,PDO::PARAM_STR);
       // $clinetname2=$clinetname->execute() ;
        
        }
        else
        {
         

           $sid = $_POST['createdfk'];
          
           $FirstName = $_POST['FirstName'];
          
           $LastName = $_POST['LastName'];
          
           $Phone = $_POST['Phone'];
          
           $datecreated = $_POST['datecreated'];
          
           $createdfk = $_POST['createdfk'];
          
           $EventDate = $_POST['EventDate'];
          
           $eventstatus = $_POST['eventstatus'];
          
           $Zip = $_POST['Zip'];
          
           $CostOfService = $_POST['CostOfService'];
          
           $EmailInstruction = $_POST['EmailInstruction'];
          
           $title = $_POST['title'];
          
           $end_date = $_POST['end_date'];
          
           $UserID = $_POST['createdfk'];
          
           $ServiceName = $_POST['ServiceName'];
          
           $ServiceProvider = $_POST['ServiceProvider'];
          
           $Location_radio = $_POST['Location_radio'];
          


        $clinetdetalils = $db->prepare("INSERT INTO clients (sid,FirstName,LastName,Phone,datecreated,createdfk) values(:sid,:FirstName,:LastName,:Phone,:datecreated,:createdfk)");
        $clinetdetalils->bindValue(":sid",$sid,PDO::PARAM_STR);
        $clinetdetalils->bindValue(":FirstName",$FirstName,PDO::PARAM_STR);
        $clinetdetalils->bindValue(":LastName",$LastName,PDO::PARAM_STR);
        $clinetdetalils->bindValue(":Phone",$Phone,PDO::PARAM_STR);
        $clinetdetalils->bindValue(":datecreated",$datecreated,PDO::PARAM_STR);
        $clinetdetalils->bindValue(":createdfk",$createdfk,PDO::PARAM_STR);
        $clinetdetalils_re=$clinetdetalils->execute();
        $cid = $db->lastInsertId();


         $clinetnameapp = $db->prepare("INSERT INTO event (FirstName,LastName,Phone,Email,EventDate,eventstatus,Address,Zip,City,State,country,CostOfService,EmailInstruction,datecreated,createdfk,title,end_date,UserID,ServiceName,ServiceProvider,cid,Location_radio) values(:FirstName,:LastName,:Phone,'',:EventDate,:eventstatus,'','','','','',:CostOfService,:EmailInstruction,:datecreated,:createdfk,:title,:end_date,:UserID,:ServiceName,:ServiceProvider,:cid,:Location_radio)");
        
        $clinetnameapp->bindValue(":FirstName",$FirstName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":LastName",$LastName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Phone",$Phone,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":EventDate",$EventDate,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":eventstatus",$eventstatus,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":CostOfService",$CostOfService,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":EmailInstruction",$EmailInstruction,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":title",$title,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":end_date",$end_date,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":UserID",$UserID,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":ServiceName",$ServiceName,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":ServiceProvider",$ServiceProvider,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":cid",$cid,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":Location_radio",$Location_radio,PDO::PARAM_STR);

        $clinetnameapp->bindValue(":datecreated",$datecreated,PDO::PARAM_STR);
        $clinetnameapp->bindValue(":createdfk",$createdfk,PDO::PARAM_STR);
       $clinetnameapp2=$clinetnameapp->execute();

       if($clinetnameapp2)
      {
          echo  json_encode(["resonse"=>'Thank you your appointments has been booked']);die;

      }

        }
       
    }

?>

<!DOCTYPE html>
<html>
<?php
include 'head.php';
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<head>

	<title>mysunless</title>
	<style>

    /*===== //Loader ==========*/

.Loader{
            display:none;
            position:fixed;
            z-index:1000;
            top:0;
            left:0;
            height:100%;
            width:100%;
            background: rgba( 255, 255, 255, .8) url('<?php echo  base_url ?>/assets/images/ajax-loader.gif') 50% 50% no-repeat;
        }
        body.loading .Loader{
            overflow: hidden;
        }
        body.loading .Loader{
            display: block;
        }
        img.myimage {
    height: 100px;
    width: 100px;
    border-radius: 50%;
}

div#customerdetals{padding: 50px 0;}
.detialsopnne{ width: 10%;    float: left;}
.detialsopnne2{width: 12%;    float: left; padding: 25px 0;}
.selectstime{background-color: #155e8e; padding: 10px; margin: 10px; border-radius: 5px; color: white;}
.listofhr{margin: 25px 0;}
.padf,.bookevent_two,.bookevent_one{width: 48%; float: left;}

.secction1_one{padding: 20px 150px; display: flex; justify-content: space-between; align-items: flex-start;}
.secction1_two{margin: 20px 150px;}
.newlistofcatagory{width: 25%; padding: 15px 70px; border: 1px solid #cacaca; color: #000000;}
.listofcatagory3{padding: 15px 100px; border: 1px solid #cacaca; color: #000000; width: 30%;}
@media only screen and (max-width: 768px) 
{
  .secction1_one{padding: 20px !important;}
  .secction1_two{margin: 20px !important; }
  .newlistofcatagory{width: 100% !important;}
  .listofcatagory3{width: 100% !important;}
}

/*===== //Loader   ==========*/

		<link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
		*{
			font-family: 'Poppins', sans-serif;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		<link rel="stylesheet" href="https://slicezer.com/themes/wowonder/stylesheet/font-awesome-4.7.0/css/font-awesome.min.css">


	</style>

	<script src="https://kit.fontawesome.com/194dfc1fbb.js"></script>
</head>
<body style="margin: 0;">
	<!-- header -->
	<div style="border-bottom: 1px solid rgba(0,0,0,.2); display: flex; justify-content: space-between;">
		<div style="display: flex; align-items: center;">
			<img style="width: 15%; padding: 5px 10px 5px 10px;" src="<?php echo base_url;?>/assets/images/only_logo.png">
			<div style="border-left: 1px solid rgba(0,0,0,.1); position: relative;">
				<i style="position: absolute; top: 0; bottom: 0; margin: auto 0; height: fit-content; left: 10px; font-size: 24px;color: #adadad;" class="fas fa-search"></i> 
				<input style="border: none; font-size: 16px; height: 77px;padding-left: 42px; outline: none;" type="search" placeholder="Business Name">
			</div>
		</div>
		<div style="display: flex; align-items: center; color: #0a5aa2; font-weight: 600; display: none;">
			<div style="padding: 0 10px;">List Your Business</div>
			<div style="padding: 0 10px;">Daily Deals</div>
			<div style="padding: 0 10px;">Professionals</div>
			<div style="padding: 0 10px;">gallery</div>
			<div style="padding: 0 10px; padding-right: 20px;">Login</div>
		</div>
	</div>
    
    <div class="secction1">
    <!-- main -->
    <div class="secction1_one">
    	<div style="display: flex;">
    		<img style="width: 200px; border-radius: 5px;" src="<?php echo base_url;?>/assets/companyimage/<?php echo $Companyimagedeata['compimg']; ?>">
    		<div style="padding-left: 15px; display: flex; flex-direction: column;">
    			<h2 style="color: #333333; margin-bottom: 10px;" ><?php echo @$all2userdeatils['firstname'].' '.@$all2userdeatils['lastname']; ?> </h2>
    			<h6 style="margin: 0; color: #868686; letter-spacing: 0.7px; font-size: 14px;"><?php echo @$all2userdeatils['city'].' '.@$all2userdeatils['state']?></h6>
    			<a class="btn btn-primary" style="color: #ffffff; font-weight: 600; text-decoration: none; padding: 10px 48px; background-color: #155e8e;text-align: center; margin-top: 10px; border-radius: 5px;" href="#">Book Now</a>
    		</div>
    	</div>
    	<div>
    		<a style="display: none;" href="#"> Share</a>
    	</div>
    </div>
    <div class="secction1_two" >
    	<form>
		  <select style="width: 25%; padding: 15px 70px; border: 1px solid #cacaca; color: #000000;" name="newlistofcatagory" id="newlistofcatagory" class="newlistofcatagory" placeholder="select service">
		    <option>service</option>
        <?php 
        foreach($result_event2 as $row2)
        {
        ?>
      <option value="<?php echo $row2['id']; ?>"><?php echo $row2['ServiceName']; ?></option>
            <?php
            }
			?>
		  </select>

      <div class="Loader"></div>
		  <select style="" name="listofcatagory3" id="listofcatagory3" class="listofcatagory3">
		 	<option value=""> service provider</option>  
		  </select>

		  <!-- <p id="datepairExample"> -->
          <input type="text" class="date start form-control" style="padding: 15px 100px; border: 1px solid #cacaca; color: #000000; width: 30%;" placeholder="Start Date" name="sd" autocomplete="nope"  id="eventstardate" />
                    <!-- </p> -->
		  <!-- <select style="padding: 15px 128px; border: 1px solid #cacaca; color: #000000;" name="cars">
		    <option value="volvo">Volvo</option>
		    <option value="saab">Saab</option>
		    <option value="fiat">Fiat</option>
		    <option value="audi">Audi</option>
		  </select>  -->
		  <a style="background-color: #155e8e; padding: 15px 40px; text-decoration: none; color: white; border-radius: 5px;" href="#" id="addevent" class="addevent">Search</a>

      <div class="customerdetals" id="customerdetals" style="display: none; ">
        <div class="customerimage" id="customerimage" >
          <div class="detialsopnne">
          <img class="myimage" id="myimage" src="">
          </div>
          
          <div class="detialsopnne2">
          <span id="employeename" class="employeename"></span><br>
          <span id="servicprice" class="servicprice"></span><br>
          <span class="servicprname" id="servicprname"></span><br>
          <span id="serviduration"></span><br>
          </div>
          <div class="cler" style="clear: both;"></div>
        </div>
        <span id="finalstime" class="finalstime"></span>

         <div class="listofhr">
           
        </div>

      </div>
		</form>
    	
    </div>
    </div>
	

	<div class="secction2">


			<form class="form-horizontal" autocomplete="off" id="NewEvent" method="post">
			<input type="hidden" name="id" value="new">
			<!-- <input type="hidden" name="FirstName" id="FirstName" class="FirstName">
			<input type="hidden" name="LastName" id="LastName" class="LastName"> -->
			<!-- <input type="hidden" name="Phone" id="Phone" class="Phone"> -->
			<input type="hidden" name="EventDate" id="EventDate" class="EventDate">
			<input type="hidden" name="eventstatus" id="eventstatus" class="eventstatus" value="pending">
			<!-- <input type="text" name="Address" id="Address" class="Address"> -->
			<!-- <input type="text" name="Zip" id="Zip" class="Zip"> -->
			<!-- <input type="text" name="City" id="City" class="City"> -->
			<!-- <input type="text" name="State" id="State" class="State"> -->
			<!-- <input type="text" name="country" id="country" class="country">  -->
			<input type="hidden" name="CostOfService" id="CostOfService" class="CostOfService">
			<!-- <input type="text" name="EmailInstruction" id="EmailInstruction" class="EmailInstruction">  -->
			<input type="hidden" name="datecreated" id="datecreated" class="datecreated" value="<?php echo date("Y-m-d H:i:s"); ?>">
			<input type="hidden" name="createdfk" id="createdfk" class="createdfk" value="<?php echo base64_decode($_GET['ref']); ?>">
			<input type="hidden" name="title" id="title" class="title">
			<input type="hidden" name="end_date" id="end_date" class="end_date">
			<input type="hidden" name="Location_radio" id="Location_radio" class="Location_radio" value="Salon Location">
			<input type="hidden" name="ServiceName" id="ServiceName" class="ServiceName" value="">
			<input type="hidden" name="ServiceProvider" id="ServiceProvider" class="ServiceProvider" value="">
			<!-- <input type="text" name="cid" id="cid" class="cid" value=""> -->



		<div class="bookevent" style="padding: 20px 150px; display: flex; justify-content: space-between; align-items: flex-start;">
			<div class="bookevent_one">
				<h3>Salman Memon</h3>
				<hr>

				<div>
					<h4>About your appointmet</h4>
					<textarea name="EmailInstruction" id="EmailInstruction" class="EmailInstruction" style="width: 100%; height:100px;" placeholder="Do you have any special requests or idea to share with your service provider? (optional)"> </textarea>
					<input type="text" name="Phone" placeholder="Phone Number" style="width: 100%;">
				</div>


				<div style="padding: 10px 0;">
					<h4>This Business Requires a Card to Hold</h4>
					<p>A payment card is required to hold your appointment. You will not be charged right now. Payment is due when you arrive at the business.</p>
					<div><input type="" name="" placeholder="Card Number" style="width: 100%;"></div>
					<div style="padding: 10px 0;"><input type="text" name="" placeholder="MM/YY" style="width: 40%;">
					<input type="text" name="" placeholder="CVV" style="width: 18%;">
					<input type="text" name="Zip" id="Zip" class="Zip" placeholder="Billing Zip" style="width: 40%;"></div>

					<div style="padding: 10px 0;"><input type="text" value="" id="FirstName" name="FirstName" placeholder="First Name" style="width: 48%; float:left; margin: 0 5px;">
					<!-- <input type="text" name="" placeholder="MI" style="width: 18%;"> -->
					<input type="text" name="LastName" placeholder="Last Name" id="LastName" value="" style="width: 48%; float:left; margin: 0 5px;"></div>

					
				</div>
				<div style="width: 50%; float: left;"><span id="ferads"></span></div><div style="width: 50%; float: left;"><span style="width: 50%; float: left;" id="laseteror"></span></div>

				<div style="padding: 25px 0;">
					<h4>Cancellation Policy</h4>
					<p>We understand things happen, so if an emergency comes up its ok. Late notice canellations (within 48 hr of apt time), howere, will incur a fee of 50% service total. Missed appointments the day of or cancelled within 24 hours will incure a no show fee at the rate of total service missed.</p>
				</div>

				<div style="padding: 25px 0;">
					<h4>Fine Print</h4>
					<ul>
						<li> Request appointments are padding for service provider acceptance. </li>
						<li> prices and duration are starting point quotes. </li>
					</ul>
				</div>

				<div>
					<button name="backabutton" value="Back" class="backabutton btn btn-default">Back</button>
					<!-- <button type="submit"  name="donebutton"  class="donebutton btn btn-success">Request</button>  -->
					<button type="submit" class="donebutton btn btn-success" name="donebutton" id="donebutton">Request</button>

				</div>
			</div>

			<div class="bookevent_two">
					<div class="partopartwo">
						<div class="padf">
						<h4 class="finalstime" id="finalstime1"></h4>
					</div>

					<div class="padf">
						<h4 class="booktime" id="booktime1"></h4>
					</div>

					<div class="padf">
						<div class="detialsopnne_zxcvz" style="width: 25%; float: left; ">
          				<img id="myimage" class="myimage" src="" style="width: 50px; height: 50px; border-radius: 50%">
          				</div>

          				<div class="detialsopnne2_zxcv" style="width: 50%;    float: left;">
          					<span class="servicprname" id="servicprname"></span><br>
          					<span id="employeename" class="employeename"></span><br>
          				</div>
					</div>


					<div class="padf">
						<h4 class="servicprice"></h4>
					</div>

					</div>
					<div class="clear" style="clear: both;"></div>
					<hr>

					<div class="padf">
						<h4>Total</h4>
					</div>

					<div class="padf">
						<h4 class="servicprice"></h4>
					</div>


			</div>
		</div>

	
			
		</form>
	</div>

  <div class="secction3">
    <div style="text-align: center; margin: 25px 0;">
    <h4 class="finalstime" id="finalstime1"></h4> <h4 class="booktime" id="booktime1"></h4>

    <h2>Your appointment  is requested</h2>

    <p>Greate work. Now we're just waiting for <span class="servicprname">salman memon</span> to accept your <span class="employeename"></span></p>


  </div>
  </div>
	
</body>
</html>

<?php include 'scripts.php'; ?>

<script type="text/javascript" src="https://momentjs.com/downloads/moment.js"></script>

<script type="text/javascript">
  $(document).ready(function(){

   	$('.secction2').hide()
 $('.secction1').show()
  $('.secction3').hide()

	$('#newlistofcatagory').on('change',function(){
      // $('#customerdetals').html('');

      // 

      
      $('#eventstardate').val('');
      $('.selectstime').remove();
      
      $('#listofcatagory3').html('');
      $('.finalstime').text('');
      $(".Loader").show();   
      var listofcatagory=$(this).val();  
    		Servicename=$(this).val();
        $('#ServiceName').val(Servicename)

			var UserID = '<?php if(isset($_GET['ref'])) { echo base64_decode($_GET['ref']); }?>';
					
               $.ajax({

                   dataType:"json",
                   type:"post",
                    data: {'Servicename':Servicename},
                    url:'?action=editfile',
                    success: function(data)
                    {
                        if(data)
                {

                    
                    var listarray = data.resonse.Users
                         $.ajax({
                         type:"post",
                         data: {'UserName':listarray,'UserID':UserID },
                         url:'?action=editfile',
                         dataType: 'json',
                         success: function(data2)
                         {
                             if(data2)
                             {


                                $('.servicprice').text('$ '+data.resonse.Price)
                                $('#CostOfService').val(data.resonse.Price)
                                $('.servicprname').text(data.resonse.ServiceName)
                                $('#title').val(data.resonse.ServiceName)
                                
                                $('#serviduration').text(data.resonse.Duration)
                                

                                  $('#listofcatagory3').append('<option value="">select service provider</option>');        
                                $.each(data2.resonse, function (key, val) 
                                {

                                  if(val.id==$('#editServiceProvider').val())
                                  {
                                  $('#listofcatagory3').append('<option selected  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');      
                                  }
                                  else
                                  {
                                   $('#listofcatagory3').append('<option  value="'+val.id+'">'+ val.firstname + ' '+ val.lastname +'</option>');   
                                  }
                                
                                $(".Loader").hide();   
                                
                                });

                             }
                         }

                            });
                     }
                 else if(data.error)
                {
                    alert('ok');  
                    $(".Loader").hide();   
                }
                }
                })


             });



$(document).on("change","#listofcatagory3",function() {
  $(".Loader").show();   
      var employeename = $(this).val();
      $('#ServiceProvider').val(employeename)
$('#eventstardate').val('');
$('.selectstime').remove();
$('.finalstime').text('');

 $.ajax({
           
           type:"post",
          data: {'employeename':employeename},
          url:'?action=editfile',
          dataType: 'json',
          success: function(data2)
          {
          if(data2)
          {
                $(".Loader").hide();      
          emtimtable(data2.timetable)
          if(data2.resonse.userimg!='')
          {
          $('.myimage').attr("src", '<?php echo  base_url ?>/assets/userimage/'+data2.resonse.userimg);  
          }
          else
          {
           $('.myimage').attr("src", '<?php echo base_url ?>/assets/images/noimage.png');   
          }
          
            $('.employeename').text(data2.resonse.firstname+' '+data2.resonse.lastname)
          }
          }

            });

});

  function emtimtable(emtime){

   var emtime = emtime
   $('#eventstardate').datepicker('remove');
    $('#eventstardate').datepicker({
         format: 'DD, M-dd,yyyy',
          startDate: '-0d',
         daysOfWeekDisabled: emtime

    });


  }



$('#addevent').on('click',function(){

  var ServiceName =  $('#newlistofcatagory').val()

  if(ServiceName =='')
  {
    swal("Please select service");
  }
  else
  {
allthings()


  }

  });

function allthings()
{

      $("#customerdetals").show()
    var finaltime = $('#eventstardate').val()
    $('.finalstime').text(finaltime)

    var employeename2 = $('#listofcatagory3').val()
    var finaltime = finaltime.split(',');
    var useravlidate = finaltime[0]
    var serivcdur = $('#serviduration').text()
    var durtiontyep = serivcdur.split(' ');
      
        $.ajax({
           
           type:"post",
          data: {'employeename2':employeename2,"useravlidate":useravlidate},
          url:'?action=editfile',
          dataType: 'json',
          success: function(data2)
          {
          if(data2)
          {
            
            if(durtiontyep[1]=='Min')
            {
              
            var timediffr = ( new Date("1970-1-1 "+  data2.emoptime[1].endtime.slice(0,-2)) - new Date("1970-1-1 "+ data2.emoptime[0].startime.slice(0,-2)))/1000/60;              
            var numofsloa = timediffr/durtiontyep[0]

            var startimaae = data2.emoptime[0].startime.slice(0,-2)
            $('.listofhr').html('')
            // $('.listofhr').append('<button class="selectstime">'+startimaae+'</button>')
              var i;

              for (i = 0; i < numofsloa; i++) 
            { 
              
              var  startimaae= moment.utc(startimaae,'hh:mm').add(durtiontyep[0],'minutes').format('h:mm a');
               // console.log(i)
                $('.listofhr').append('<button value="'+startimaae+'" name="selectstime" class="selectstime">'+startimaae+'</button>')
               
            }

            
            }
            else
            {
              
                    var timediffr = ( new Date("1970-1-1 "+  data2.emoptime[1].endtime.slice(0,-2)) - new Date("1970-1-1 "+ data2.emoptime[0].startime.slice(0,-2)))/1000/60/60;              
                    var numofsloa = timediffr/durtiontyep[0]
                    var startimaae = data2.emoptime[0].startime.slice(0,-2)
                      $('.listofhr').html('')
                      $('.listofhr').append('<button value="'+startimaae+'" name="selectstime" class="selectstime">'+startimaae+'</button>')
                        var i;
                        for (i = 0; i < numofsloa; i++) 
                        { 
                         var  startimaae= moment.utc(startimaae,'hh:mm').add(durtiontyep[0],'hours').format('h:mm a');
                          $('.listofhr').append('<button value="'+startimaae+'" name="selectstime" class="selectstime">'+startimaae+'</button>')
                        }

            }
            

          }
          }

            });

}

$('#eventstardate').on('click',function(){

  var Serviceprovider =  $('#listofcatagory3').val()

  if(Serviceprovider =='')
  {
    swal("Please select service provider");
  }


  });


	$(document).on("click",".selectstime",function(event) {
	event.preventDefault();
	$('.booktime').text($(this).val())
	$('.secction2').show()
	$('.secction1').hide()
    var alert1 =  $('#finalstime1').text();
    var alert2 =  $('#booktime1').text();

    var neweewda = alert1+' '+alert2

    var first_date = moment(neweewda).format('YYYY-MM-DD hh:mma');
    $('#EventDate').val(first_date);
      
    var serivcdur = $('#serviduration').text()
    var durtiontyep = serivcdur.split(' ');
    if(durtiontyep[1]=='Min')
    {

      var first_datearr = first_date.split(' ');
       var  startimaae= moment.utc(first_datearr[1],'hh:mma').add(durtiontyep[0],'minutes').format('hh:mma');
        var end_date = first_datearr[0]+' '+startimaae;
        $('#end_date').val(end_date)
    }



  });


	$(document).on("click",".backabutton",function(event) {
	event.preventDefault();
	
	$('.secction2').hide()
	$('.secction1').show()

  });


	// $(document).on("click",".donebutton",function(event) {
	// event.preventDefault();
		


	 $("#NewEvent").validate({

            rules: {                
                FirstName: {required:true,},
                LastName: {required:true,},
                
            },
            

            messages: {             
                FirstName: {required:"Please enter first name"},
                LastName: {required:"Please enter last name"},
                
            },

            errorPlacement: function(error, element) {
    if (element.attr("name") == "FirstName" )
        error.insertAfter("#ferads");
    else if  (element.attr("name") == "LastName" )
        error.insertAfter("#laseteror");
    else
        error.insertAfter(element);
},
              
                submitHandler: function() {
                  $(".Loader").show();
                  	

                    var data = $("#NewEvent").serialize();
                    data= data + "&LoginAction=Login";
                
                    jQuery.ajax({
                        dataType:"json",
                        type:"post",
                        data:data,
                        url:'?LoginAction=Login',
                        success: function(data)
                        {
                            if(data.resonse)
                            {

                              $('.secction2').hide()
                              $('.secction3').show()
                                 
                            }
                            
                              
                      }
              
                    });
                }           
        });
	
  });

// });
</script>

     <style type="text/css">
       .disabled{background: #eeeeee !important; }
     </style>