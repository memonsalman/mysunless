<?php

include_once('function.php');

if(isset($_REQUEST['Timer'])){

	$stmt = $db->prepare("SELECT Maintenance FROM `users` where usertype='Admin' ");
	$stmt->execute();
	$MaintenanceResult = $stmt->fetch();
	if($MaintenanceResult['Maintenance']==2){

		$stmt = $db->prepare("SELECT * FROM `Post` where PostTitle='Maintenance' ");
		$stmt->execute();
		$Post = $stmt->fetch();

		echo json_encode(['response'=>true,'Post'=>$Post]);

	}else{
		echo json_encode(['response'=>false]);
	}

	die;

}


?>



<script src="<?php echo base_url; ?>/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script>
	$(document).ready(function(){
	 $.ajax({
            url:'<?= base_url ?>/MaintenanceTimer?Timer',
            dataType:'json',
            success:function(data){
                if(data.response){
                    MaintenanceTimer(data.Post);
                    $(".MaintenanceTime").show();
                }
            }
        });

        function MaintenanceTimer(Post){

          var EndTime = moment.utc(Post.PostDate).local().format("DD/MM/YYYY HH:mm:ss");

          var Timer = setInterval(function(){

            var ms = moment(EndTime,"DD/MM/YYYY HH:mm:ss").diff(moment());
            var d = moment.duration(ms);
            var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");

            if(s<='0:00:00'){
                $.ajax({
                    url:'<?= base_url ?>/Login_Check?SetMaintenance=1',
                    dataType:'json',
                    success:function(data){
                        if(data.response){
                            clearInterval(Timer);
                            location.reload();
                        }
                    }
                });
            }

                
            time = s.split(':');
            $(".MaintenanceTime").html('<span>'+time[0]+'</span><span>:</span><span>'+time[1]+'</span><span>:</span><span>'+time[2]+'</span>');
            $(".MaintenanceText").html(Post.PostDec);
        },1000);

      }

  });


</script>