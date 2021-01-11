<?php

if ($_SESSION['usertype']=="Admin")
{
    return false;  
}

$stmt= $db->prepare("SELECT * FROM `users` WHERE id=:id"); 
$stmt->bindParam(':id',$_SESSION['UserID']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if(empty($result['NotificationStatus'])){
	$query = $db->prepare("DELETE from Notification where createdfk=:id ");
	$query->bindParam(":id",$_SESSION['UserID']);
	$run = $query->execute();
	return false;
}


?>
<script>
	$(document).ready(function(){
		var sessionID = window.btoa('<?php echo $_SESSION['UserID'] ?>');
		var emptydata = {};
		var empty_todo_data = {todo:[],todocomment:[]};
		event_notification();
		todo_notification();
		update_notification();
		setInterval(event_notification,5000);
		setInterval(todo_notification,5000);
		window.addEventListener('storage',update_all_page_notification);

		/*var notification_timer;

		$(window).focus(function() {
			notification_timer = setInterval(event_notification,5000);
		});

		$(window).blur(function() {
			clearInterval(notification_timer);
		});*/
		function update_all_page_notification(){
			update_notification();
		}

		$("#show_notify").click(function(){
			$("#app_notification").toggleClass('notification_visible');
			window.localStorage.setItem('notification_blink','false');
			disable_notification();
			count_blink();
		});

		$(document).on("click",".notification_link",function(e){
			$(this).parents(".toast").find(".toast__close").trigger("click");;
		});

		$(document).on("click",".close_all",function(e){
			e.preventDefault();
			id = "<?= $_SESSION['UserID']?>";
			$(".notify_badge").text('0');
			$("#app_notification").removeClass("notification_visible");
			$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');

			var response = del_notification(id,'all');

			if(response=="true"){				
				window.localStorage.setItem('notification_data', JSON.stringify(emptydata));
				window.localStorage.setItem('todo', JSON.stringify(empty_todo_data));
				$("#app_notification").toggleClass('notification_visible');
				update_notification();
			}
		});

		$(document).on("click",".del_event",function(e){
			e.preventDefault();
			var current = $(this);
			var parent = $(this).parent('.toast');
			var id = $(this).attr('data-id');

			parent.fadeOut("slow", function() { current.remove(); } );

			var response = del_notification(id);
			if(response=="true"){				
				var user = JSON.parse(window.localStorage.getItem('notification_data'));
				var data = [];
				for(i in user){
					if(user[i].nid!=id){
						data.push(user[i]);
					}
				}
				window.localStorage.setItem('notification_data',JSON.stringify(data));
				update_notification();
			}
		});

		$(document).on("click",".del_todo",function(e){
			e.preventDefault();
			var current = $(this);
			var parent = $(this).parent('.toast');
			var id = $(this).attr('data-id');
			parent.fadeOut("slow", function() { current.remove(); } );

			var response = del_notification(id);

			if(response=="true"){
				
				var data = JSON.parse(window.localStorage.getItem('todo'));
				var newdata = {todo:[],todocomment:[]};

				for(i in data.todo){
					if(data.todo[i].nid!=id){
						newdata.todo.push(data.todo[i]);
					}
				}
				for(i in data.todocomment){
					if(data.todocomment[i].nid!=id){
						newdata.todocomment.push(data.todocomment[i]);
					}
				}
				window.localStorage.setItem('todo',JSON.stringify(newdata));
				update_notification();
			}
		});

		function event_notification(){
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("notification_data") === null){
				window.localStorage.setItem('UserID', sessionID);
				window.localStorage.setItem('notification_data', JSON.stringify(emptydata));
				window.localStorage.setItem('notification_blink','false');
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?event_notification",
				success:function(data){
					var data = JSON.parse(data);
					var notification_data = JSON.parse(window.localStorage.getItem('notification_data'));
					if(notification_data.length!=data.user.length){
						window.localStorage.setItem('notification_data', JSON.stringify(data.user));
						window.localStorage.setItem('notification_blink','true');
						update_notification();
					}
				}
			});
		}

		function todo_notification(){
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("todo") === null){
				window.localStorage.setItem('UserID', sessionID);
				window.localStorage.setItem('todo', JSON.stringify(empty_todo_data));
				window.localStorage.setItem('notification_blink','false');
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?todo_notification",
				success:function(data){
					var data = JSON.parse(data);
					var todo_data = JSON.parse(window.localStorage.getItem('todo'));
					if(todo_data.todo.length!=data.todo.length || todo_data.todocomment.length!=data.todocomment.length){
						window.localStorage.setItem('todo', JSON.stringify(data));
						window.localStorage.setItem('notification_blink','true');
						update_notification();
					}
				}
			});
		}

		function update_notification(){

			var badge_number = count_blink();
			var event_data = JSON.parse(window.localStorage.getItem('notification_data'));
			var todo_data = JSON.parse(window.localStorage.getItem('todo'));

			if(badge_number>0){

				if($("#toast_notify").length<1)
				{
					$("#app_notification").html('<div class="close_all" title="Clear All"><i class="fa fa-trash"></i></div><div id="toast_notify"><div class="toast_container"></div></div>');
				}
				$(".toast_container").html("");

				if(event_data.length>0){
					
					for(i in event_data){
						id = event_data[i].nid;
						fname = event_data[i].FirstName;
						lname = event_data[i].LastName;
						clientname = fname.toUpperCase()+' '+lname.toUpperCase();
						title = event_data[i].title;
						EventDate = event_data[i].EventDate;
						eventstatus = event_data[i].type;
						notify_body="";
						notify_head="";
						link = '<a class="notification_link" href="<?php echo $base_url ?>/AllEvent" target="_blank">View Appointment</a>';

						if(eventstatus=="confirmed"){
							notify_color = 'toast--green';
						}else if(eventstatus=="canceled"){
							notify_color = 'toast--red';
						}else{
							notify_color = 'toast--yellow';
						}
						if(eventstatus=='pending'){
							eventstatus = 'requested';
							link = '<a class="notification_link" href="<?php echo $base_url ?>/dashboard?set_pending_app" target="_blank">View Appointment</a>';
						}
						if(event_data[i].ProfileImg){
							ProfileImg="<?php echo $base_url ?>/assets/ProfileImages/"+event_data[i].ProfileImg;
						}else{
							ProfileImg="<?php echo $base_url ?>/assets/images/noimage.png";
						}

						

						$(".toast_container").append('<div class="toast '+notify_color+'"><img src="'+ProfileImg+'" class="toast__icon"><div class="toast__content"> <p class="toast__type">Appointment</p><hr><p class="toast__message"><b>'+clientname+'</b> has been '+eventstatus+' the '+title+' appointment.<br> @ '+EventDate+' <br>'+link+'</p> </div> <div class="toast__close del_event" data-id="'+id+'">&times; </div> </div>');
					}

					$("#calendar_refresh").trigger("click");
					if($("#myDropdown_cal2").hasClass("show_cal")){
						$("#myDropdown_cal2").removeClass("show_cal");
					}
				}

				if(todo_data.todo.length>0){

					for(i in todo_data.todo){
						id = todo_data.todo[i].nid;
						fname = todo_data.todo[i].firstname;
						lname = todo_data.todo[i].lastname;
						clientname = fname+" "+lname;
						todoTitle = todo_data.todo[i].todoTitle;
						userimg = todo_data.todo[i].userimg;
						type = todo_data.todo[i].type;
						tid = todo_data.todo[i].tid;

						if(userimg){
							userimg="<?php echo $base_url ?>/assets/userimage/"+userimg;
						}else{
							userimg="<?php echo $base_url ?>/assets/images/noimage.png";
						}

						if(type=="done"){
							status = " has been completed the task.";
							notify_color = 'toast--green';
						}else if(type=="assign"){
							status = " has given you a task.";
							notify_color = 'toast--yellow';
						}else if(type=="assign_event"){
							status = "";
							notify_color = 'toast--red';
						}

						link = '<a class="notification_link" href="<?php echo $base_url ?>/todo?task='+tid+'" target="_blank">View Task</a>';

						$(".toast_container").append('<div class="toast '+notify_color+'"><img src="'+userimg+'" class="toast__icon"><div class="toast__content"> <p class="toast__type">ToDo</p><hr><p class="toast__message"><b>'+clientname+'</b>'+status+'<br> <b>Task-Title:</b> '+todoTitle+' <br>'+link+'</p> </div> <div class="toast__close del_todo" data-id="'+id+'">&times; </div> </div>');
					}
				}

				if(todo_data.todocomment.length>0){

					for(i in todo_data.todocomment){
						id = todo_data.todocomment[i].nid;
						fname = todo_data.todocomment[i].firstname;
						lname = todo_data.todocomment[i].lastname;
						clientname = fname+" "+lname;
						todoTitle = todo_data.todocomment[i].todoTitle;
						userimg = todo_data.todocomment[i].userimg;
						type = todo_data.todocomment[i].type;
						comment = todo_data.todocomment[i].comment;
						tid = todo_data.todocomment[i].tid;

						if(userimg){
							userimg="<?php echo $base_url ?>/assets/userimage/"+userimg;
						}else{
							userimg="<?php echo $base_url ?>/assets/images/noimage.png";
						}

						if(type=="comment"){
							status = " has commented on "+todoTitle;
						}

						notify_color = 'toast--green';

						link = '<a class="notification_link" href="<?php echo $base_url ?>/todo?task='+tid+'" target="_blank">View Task</a>';

						$(".toast_container").append('<div class="toast '+notify_color+'"><img src="'+userimg+'" class="toast__icon"><div class="toast__content"> <p class="toast__type">ToDo</p><hr><p class="toast__message"><b>'+clientname+'</b>'+status+'<br> <b>Comment:</b> '+comment+' <br>'+link+'</p> </div> <div class="toast__close del_todo" data-id="'+id+'">&times; </div> </div>');
					}

				}

			}else{
				$(".notify_badge").text('0');
				$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');
				disable_notification();
			}
		}

		function disable_notification(){
			if(notification_count()<1){
				if($("#app_notification").hasClass("notification_visible")){
					setTimeout(function(){
						$("#show_notify").trigger("click");
					},1000);    
				}
			}	
		}

		function notification_count(){
			var data = JSON.parse(window.localStorage.getItem('todo'));
			var event_data = JSON.parse(window.localStorage.getItem('notification_data'));

			var count = data.todo.length+data.todocomment.length+event_data.length;
			return count;
		}

		function del_notification(id="",type=""){
			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?del_todo_notification",
				type:"post",
				data:{id:id,type:type},
				success:function(data){
					data = JSON.parse(data);
					if(data.error){
						swal("",data.error,"error");
						return false;
					}

					return true;
				}
			});
		}

		function count_blink(){
			var badge_number = notification_count();
			$(".notify_badge").text(badge_number); 
			if(window.localStorage.getItem('notification_blink')=='true' && badge_number>0){
				$('.notify_badge').css({'animation-name': 'pulse','animation-duration': '2s'});
			}else{
				$('.notify_badge').css({'animation-name': 'none'});
			}

			return badge_number;
		}

	});
</script>