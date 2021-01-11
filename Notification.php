<?php

if ($_SESSION['usertype']=="Admin")
{
	return false;  
}


?>
<script src="<?php echo base_url; ?>/assets/node_modules/moment/moment.js"></script>
<script>
	$(document).ready(function(){
		var sessionID = window.btoa('<?php echo $_SESSION['UserID'] ?>');
		var emptydata = {};
		var empty_todo_data = {todo:[],todocomment:[],todoremind:[]};
		var empty_backup = '';
		var set_event;
		var set_todo;
		var notification_switch_status;


		var notification_timer;

		$(window).focus(function() {
			notification_timer = setInterval(check_notification,4000);
		});

		$(window).blur(function() {
			clearInterval(notification_timer);
		});

		check_notification();
		// setInterval(check_notification,5000);

		
		window.addEventListener('storage',update_all_page_notification);

		// update_notification();

		function update_all_page_notification(){
			update_notification();
		}



		function check_notification(){

			if(localStorage.getItem("notification_switch") === null){
				$.ajax({
					url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?check_notification",
					dataType:'json',
					success:function(data){
						if(data.response){
							window.localStorage.setItem('notification_switch','true');
						}else{
							window.localStorage.setItem('notification_switch','false');
							return false;
						}
					}
				});
			}

			notification_switch_status = localStorage.getItem("notification_switch");

			if(notification_switch_status=='true'){
				backup_notification();
				event_notification();
				todo_notification();
				
				$(".notification_switch input").prop("checked",true);
				$("#show_notify").find('.badge').show();
				$("#show_notify").find('i').addClass('fa-bell');
				$("#show_notify").find('i').removeClass('fa-bell-slash-o');
			}else{
				$(".notification_switch input").prop("checked",false);
				$("#show_notify").find('.badge').hide();
				$("#show_notify").find('i').removeClass('fa-bell');
				$("#show_notify").find('i').addClass('fa-bell-slash-o');
				$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');
			}


		}

		
		$(".notification_switch").click(function(){
			
			if($(".notification_switch input").is(":checked")){
				window.localStorage.setItem('notification_switch','true');
				status = 1;
			}else if($(".notification_switch input").is(":not(:checked)")){
				window.localStorage.setItem('notification_switch','false');	
				status = '';
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?update_notification&status="+status,
				dataType:'json',
				success:function(data){
				}
			});

			check_notification();

		});

		$("#show_notify").click(function(){
			$("#notification_block").toggleClass('notification_visible');
			window.localStorage.setItem('notification_blink','false');
			disable_notification();
			count_blink();
		});

		$(document).on("click",".notification_link",function(e){
			$(".notification_link").parent('form').submit();
			$(this).parents(".toast").find(".toast__close").trigger("click");
		});

		$(document).on("click",".close_all",function(e){
			e.preventDefault();
			
			var del=[];
			$("#toast_notify .toast__close").each(function(){
				del.push($(this).attr('data-id'));
			});

			$(".notify_badge").text('0');
			$("#notification_block").removeClass("notification_visible");
			$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');

			var response = del_notification(del.toString());			
			window.localStorage.setItem('notification_data', JSON.stringify(emptydata));
			window.localStorage.setItem('todo', JSON.stringify(empty_todo_data));
			window.localStorage.setItem('backup_notification', empty_backup);
			$("#notification_block").toggleClass('notification_visible');
			update_notification();

		});

		$(document).on("click",".del_backup",function(e){
			e.preventDefault();
			var current = $(this);
			var parent = $(this).parent('.toast');
			var id = $(this).attr('data-id');

			parent.fadeOut("slow", function() { current.remove(); } );

			var response = del_notification(id);		
			window.localStorage.setItem('backup_notification', empty_backup);
			update_notification();
			
		});

		$(document).on("click",".del_event",function(e){
			// e.preventDefault();
			var current = $(this);
			var parent = $(this).parent('.toast');
			var id = $(this).attr('data-id');

			parent.fadeOut("slow", function() { current.remove(); } );

			var response = del_notification(id);

			var user = JSON.parse(window.localStorage.getItem('notification_data'));
			var data = [];
			for(i in user){
				if(user[i].nid!=id){
					data.push(user[i]);
				}
			}
			window.localStorage.setItem('notification_data',JSON.stringify(data));
			update_notification();
			
		});

		$(document).on("click",".del_todo",function(e){
			e.preventDefault();
			var current = $(this);
			var parent = $(this).parent('.toast');
			var id = $(this).attr('data-id');
			parent.fadeOut("slow", function() { current.remove(); } );

			var response = del_notification(id);

			

			var data = JSON.parse(window.localStorage.getItem('todo'));
			var newdata = empty_todo_data;

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
			for(i in data.todoremind){
				if(data.todoremind[i].nid!=id){
					newdata.todoremind.push(data.todoremind[i]);
				}
			}
			window.localStorage.setItem('todo',JSON.stringify(newdata));
			update_notification();
			
		});

		function event_notification(){
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("notification_data") === null || localStorage.getItem("notification_data") == ""){
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

					}else if(data.user.length!=0){

						for(i in notification_data){
							notification_data[i].curtime=data.user[i].curtime;
						}

						window.localStorage.setItem('notification_data', JSON.stringify(notification_data));
					}
					update_notification();

				}
			});
		}

		function todo_notification(){
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("todo") === null || localStorage.getItem("todo") == ""){
				window.localStorage.setItem('UserID', sessionID);
				window.localStorage.setItem('todo', JSON.stringify(empty_todo_data));
				window.localStorage.setItem('notification_blink','false');
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?todo_notification",
				success:function(data){
					var data = JSON.parse(data);
					var todo_data = JSON.parse(window.localStorage.getItem('todo'));

					if(todo_data.todo.length!=data.todo.length || todo_data.todocomment.length!=data.todocomment.length || todo_data.todoremind.length!=data.todoremind.length ){

						window.localStorage.setItem('todo', JSON.stringify(data));
						window.localStorage.setItem('notification_blink','true');

					}else if(data.todo.length!=0 || data.todocomment.length!=0 || data.todoremind.length!=0){

						for(i in todo_data.todo){
							todo_data.todo[i].curtime=data.todo[i].curtime;
						}

						for(i in todo_data.todocomment){
							todo_data.todocomment[i].curtime=data.todocomment[i].curtime;
						}

						for(i in todo_data.todoremind){
							todo_data.todoremind[i].curtime=data.todoremind[i].curtime;
						}

						window.localStorage.setItem('todo', JSON.stringify(todo_data));
					}
					// update_notification();

				}
			});
		}

		function backup_notification(){
			
			if(window.localStorage.getItem('UserID') != sessionID || localStorage.getItem("UserID") === null || localStorage.getItem("backup_notification") === null){
				window.localStorage.setItem('UserID', sessionID);
				window.localStorage.setItem('backup_notification', empty_backup);
				window.localStorage.setItem('notification_blink','false');
			}

			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?backup_notification",
				success:function(data){
					var backup_notification = window.localStorage.getItem('backup_notification');
					if(backup_notification!=data && data!=''){

						window.localStorage.setItem('backup_notification',data);
						window.localStorage.setItem('notification_blink','true');
						update_notification();


					}
				}
			});
		}

		function TimeFormat(time){

			var duration = moment.duration(time);
			var time = parseInt(duration.asMinutes());
			var text = 'min';
			if(time>60){
				time = parseInt(duration.asHours());
				text = 'h';

				if(time>24){
					time = parseInt(duration.asDays());
					text = 'D';
				}
			}

			return time+text+' ago';

		}

		function update_notification(){
			notification_switch_status = localStorage.getItem("notification_switch");
			if(notification_switch_status=='false'){ 
				return false;
			}

			var badge_number = count_blink();
			var event_data = JSON.parse(window.localStorage.getItem('notification_data'));
			var todo_data = JSON.parse(window.localStorage.getItem('todo'));
			var backup_notification = window.localStorage.getItem('backup_notification');
			var status = localStorage.getItem("notification_switch");

			if(status=='true'){
				$(".notification_switch input").prop("checked",true);
				$("#show_notify").find('.badge').show();
				$("#show_notify").find('i').addClass('fa-bell');
				$("#show_notify").find('i').removeClass('fa-bell-slash-o');
			}else{
				$(".notification_switch input").prop("checked",false);
				$("#show_notify").find('.badge').hide();
				$("#show_notify").find('i').removeClass('fa-bell');
				$("#show_notify").find('i').addClass('fa-bell-slash-o');
				$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');
				return false;
			}

			if(badge_number>0){

				if($("#toast_notify").length<1)
				{
					$("#app_notification").html('<div class="close_all" title="Clear All"><i class="fa fa-trash"></i></div><hr class="mt-1"><div id="toast_notify"><div class="toast_container"></div></div>');
				}
				$(".toast_container").html("");

				if(event_data.length>0){
					
					for(i in event_data){
						id = event_data[i].nid;
						eid = event_data[i].id;
						fname = event_data[i].FirstName;
						lname = event_data[i].LastName;
						clientname = fname.toUpperCase()+' '+lname.toUpperCase();
						title = event_data[i].title;
						EventDate = event_data[i].EventDate;
						eventstatus = event_data[i].type;
						

						notify_body="";
						notify_head="";

						// bottom_link = '<hr><form action="<?php echo $base_url ?>/AllEvent" method="post" target="_blank"><input type="hidden" name="SearchID" value="'+eid+'"><button type="submit" class="notification_link remove_btn_style"><div class="open_link">Open</div></button></form>';
						bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/EventList?SearchID='+btoa(eid)+'" target="_blank"><div class="open_link">Open</div></a>';
						
						if(eventstatus=="confirmed"){
							notify_color = 'toast--green';
						}else if(eventstatus=="canceled"){
							notify_color = 'toast--red';
						}else{
							notify_color = 'toast--yellow';
						}
						if(eventstatus=='pending'){

							eventstatus = 'requested';

							bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/dashboard?set_pending_app" target="_blank"><div class="open_link">Open</div></a>';

						}

						if(event_data[i].ProfileImg){
							ProfileImg="<?php echo $base_url ?>/assets/ProfileImages/"+event_data[i].ProfileImg;
						}else{
							ProfileImg="<?php echo $base_url ?>/assets/images/noimage.png";
						}

						message = '<b>'+clientname+'</b> has been '+eventstatus+' the '+title+' appointment.<br> @ '+EventDate;

						img = '<img src="'+ProfileImg+'" class="toast_img">';

						head = '<span><i class="fa fa-calendar-check-o toast_icon"></i>Appointment</span>';

						delete_button = '<div class="toast__close del_event" data-id="'+id+'">&times; </div>';

						curtime = '<span class="notify_time">'+TimeFormat(event_data[i].curtime)+'</span>';



						data = notification_format(notify_color,head,img,message,bottom_link,delete_button,curtime);


						$(".toast_container").append(data);

					}

					// $("#calendar_refresh").trigger("click");
					// if($("#myDropdown_cal2").hasClass("show_cal")){
					// 	$("#myDropdown_cal2").removeClass("show_cal");
					// }
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

						}else if(type=="update_title"){

							status = " has updated the task";
							notify_color = 'toast--green';

						}else if(type=="update_desc"){

							status = " has updated the task";
							notify_color = 'toast--green';

						}

						message = '<b>'+clientname+'</b>'+status+'<br> <b>TASK:</b> '+todoTitle;

						img = '<img src="'+userimg+'" class="toast_img">';

						head = '<span><i class="fa fa-sticky-note toast_icon"></i>ToDo</span>';

						bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/todo?task='+tid+'" target="_blank"><div class="open_link">Open</div></a>';

						delete_button = '<div class="toast__close del_todo" data-id="'+id+'">&times; </div>';

						curtime = '<span class="notify_time">'+TimeFormat(todo_data.todo[i].curtime)+'</span>';

						data = notification_format(notify_color,head,img,message,bottom_link,delete_button,curtime);


						$(".toast_container").append(data);

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
							status = " has commented on <b>"+todoTitle+"</b>";
						}

						notify_color = 'toast--green';

						message = '<b>'+clientname+'</b>'+status+'<br> <b>COMMENT:</b> '+comment;

						img = '<img src="'+userimg+'" class="toast_img">';

						head = '<span><i class="fa fa-sticky-note toast_icon"></i>ToDo</span>';

						bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/todo?task='+tid+'" target="_blank"><div class="open_link">Open</div></a>';

						delete_button = '<div class="toast__close del_todo" data-id="'+id+'">&times; </div>';

						// curtime = '<span class="notify_time">'+todo_data.todocomment.curtime+'</span>';
						curtime = '<span class="notify_time">'+TimeFormat(todo_data.todocomment[i].curtime)+'</span>';


						data = notification_format(notify_color,head,img,message,bottom_link,delete_button,curtime);


						$(".toast_container").append(data);

					}

				}

				if(todo_data.todoremind.length>0){

					for(i in todo_data.todoremind){
						id = todo_data.todoremind[i].nid;
						
						todoTitle = todo_data.todoremind[i].todoTitle;
						type = todo_data.todoremind[i].type;
						newduedate = moment(todo_data.todoremind[i].newduedate).format('MMM D, YYYY');
						tid = todo_data.todoremind[i].tid;

						
						if(type=="remained"){
							status = "'"+todoTitle+"' on "+newduedate;
						}


						notify_color = 'toast--yellow';

						bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/todo?task='+tid+'" target="_blank"><div class="open_link">Open</div></a>';

						message = '<b>Remainder</b><br>'+status;

						img = '<i class="fa fa-bullhorn toast_img" aria-hidden="true" style="font-size: 32px;color: #fec107;"></i>';

						head = '<span><i class="fa fa-sticky-note toast_icon"></i>ToDo</span>';

						delete_button = '<div class="toast__close del_todo" data-id="'+id+'">&times; </div>';

						curtime = '<span class="notify_time">'+TimeFormat(todo_data.todoremind[i].curtime)+'</span>';

						data = notification_format(notify_color,head,img,message,bottom_link,delete_button,curtime);


						$(".toast_container").append(data);
					}

				}

				if(backup_notification!=''){

					
					id = backup_notification;

					notify_color = 'toast--yellow';

					head = '<span><i class="fa fa-upload toast_icon"></i>Backup Remainder</span>';

					message = 'Take your contact backup.';

					img = '<i class="fa fa-clock-o toast_img" aria-hidden="true" style="font-size: 32px;color: #fec107;"></i>';


					bottom_link = '<hr><a class="notification_link" href="<?php echo $base_url ?>/MyBackup" target="_blank"><div class="open_link">Open</div></a>';

					delete_button = '<div class="toast__close del_backup" data-id="'+id+'">&times; </div>';


					data = notification_format(notify_color,head,img,message,bottom_link,delete_button);

					
					$(".toast_container").append(data);
					
				}

			}else{
				$(".notify_badge").text('0');
				$("#app_notification").html('<img src="<?= base_url?>/assets/images/no_notification.png">');
				disable_notification();
			}
		}


		function notification_format(notify_color='',head='',img='',message='',bottom_link='',delete_button='',curtime=''){

			data = '<div class="toast '+notify_color+'"><div class="toast__content"> <div class="toast__type">'+curtime+head+delete_button+'</div><hr><div class="toast__message">'+img+'<span>'+message+'<span></div>'+bottom_link+'</div>';

			return data;

		}

		function disable_notification(){
			if(notification_count()<1){
				if($("#notification_block").hasClass("notification_visible")){
					setTimeout(function(){
						$("#notification_block").removeClass("notification_visible")
					},3000);    
				}
			}	
		}

		function notification_count(){
			var data = JSON.parse(window.localStorage.getItem('todo'));
			var event_data = JSON.parse(window.localStorage.getItem('notification_data'));
			var backup_notification = window.localStorage.getItem('backup_notification');

			if(backup_notification!=""){
				backup_notification_count=1;
			}else{
				backup_notification_count=0;
			}

			var count = data.todo.length+data.todocomment.length+data.todoremind.length+event_data.length+backup_notification_count;
			return count;
		}

		function del_notification(id=""){
			$.ajax({
				url:"<?php echo base_url; ?>"+"/Exec/Exec_todo_notification?del_notification",
				type:"post",
				data:{id:id},
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
			if(window.localStorage.getItem('notification_blink')=='true' && badge_number>0 && notification_switch_status){
				$('.notify_badge').next('i').css({'animation-name': 'swing','animation-duration': '1s'});
			}else{
				$('.notify_badge').next('i').css({'animation-name': 'none'});
			}

			return badge_number;
		}

	});
</script>