<?php
require_once('global.php');
require_once($_SERVER['DOCUMENT_ROOT'].$SUB."/function.php");

?>

<div>
	<i class="fa fa-filter btn btn-warning btn-circle m-2 mysunless_filter_btn" data-toggle="tooltip" data-placement="bottom" title="Graph filter option" aria-hidden="true" style="font-size: 22px;float: right;"></i>
	<div class="modal-content mysunless_filter_pos_right" id="mysunless_filter_box" style="width: 400px">
		<div class="modal-header">
			<h4 class="modal-title">Appointment Filter</h4>
			<button type="button" class="close mysunless_filter_btn" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
			<form>
				<div class="form-group">
					<label><span class="help">Months:</span></label>
					<i class="fa fa-question-circle m-1" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Blank month field consider as 12 months"></i>
					<select class="select2 m-b-10 select2-multiple form-control chart_filter" id="event_month" data-placeholder="Select Months" multiple="multiple">
						<?php 
						for($i=1; $i<=12; $i++){
							$index = date('M',strtotime("2020-".$i."-01"));
							echo "<option value=".$index.">".$index."</option>";
						}
						?>
					</select>
				</div>
				<?php 
				if($_SESSION['usertype']!='Admin'){
					$query = $db->prepare("SELECT * FROM `Service` where isactive=1 and createdfk IN (select id from users where id=:id or adminid=:id or sid=:id)");  
					$query->bindValue(":id",$_SESSION['UserID']);
					$query->execute();
					$service = $query->fetchAll();
					?>
					<div class="form-group">
						<label><span class="help">Service:</span></label>
						<i class="fa fa-question-circle m-1" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Blank Service field gives combined result of all services."></i>
						<input type="hidden" name="newlistofSubscriber4" id="newlistofSubscriber4" value="<?= $_SESSION['UserID'] ?>">
						<select class="select2 m-b-10 select2-multiple form-control chart_filter" id="service_name" data-placeholder="Select Service" multiple="multiple">
							<option value="all">All</option>
							<?php 
							foreach ($service as $key => $value) {
								echo "<option value=".$value['id'].">".$value['ServiceName']."</option>";
							}
							?>
						</select>
					</div>
				<?php } else { 
					$stmtsub2=$db->prepare("SELECT * FROM `users` WHERE usertype='subscriber' "); 
					$stmtsub2->execute();
					$subs = $stmtsub2->fetchAll();

					?>
					<div class="form-group">
						<label><span class="help">Subscriber:</span></label>
						<i class="fa fa-question-circle m-1" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Blank Subscriber field gives combined result of all subscriber."></i>
						<select class="select2 m-b-10 select2-multiple form-control chart_filter" id="newlistofSubscriber4" data-placeholder="Select Subscriber Name" >
							<option value disabled="true" selected="selected">Select Subscriber</option>
							<?php 
							foreach($subs as $rowsub2)
							{
								?>
								<option value="<?php echo $rowsub2['id']; ?>"><?php echo $rowsub2['firstname'].' '.$rowsub2['lastname']; ?></option>
								<?php 
							} 
							?>
						</select>
					</div>
					<div class="form-group">
						<label><span class="help">Service:</span></label>
						<i class="fa fa-question-circle m-1" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Blank Service field gives combined result of all services."></i>
						<select class="select2 m-b-10 select2-multiple form-control chart_filter" id="service_name" data-placeholder="Select Service" multiple="multiple">
						</select>
					</div>
				<?php } ?>
				<span class="btn btn-info" id="event_month_form_submit">Filter</span>
			</form>
		</div></div>

		<div class="row">
			<div class="col">
				<div style="max-height: 400px; padding: 20px">
					<canvas id="EventGraph" width="200" height="200"></canvas>
				</div>
			</div>
			<div class="col">
				<div style="max-height: 400px; padding: 20px">
					<canvas id="EventPie" width="200" height="200"></canvas>
				</div>
			</div>
		</div>

	</div>

	<script>
		$(document).ready(function(){

			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			});

			$(".chart_filter").select2({
				allowClear: true
			});

				// $(".show_graph_filter").click(function(e){
				// 	e.preventDefault();
				// 	$("#event_month_form").toggleClass("show_event_filter");
				// });

				$("#event_month_form_submit,#appgraphTab").click(function(e){
					e.preventDefault();
					month = $("#event_month").val();
					service = $("#service_name").val();
					subscriber = $("#newlistofSubscriber4").val();
					if(service.length<1){
						service = ["all"];
					}
					filter = {month:month,subscriber:subscriber,service:service};
					event_chart(filter)
				});

				$('#service_name').on("select2:select", function (e) { 
					var data = e.params.data.text;
					if(data=='All'){
						$("#service_name").val(null).trigger("change");
						$("#service_name > option:not(:first-child)").prop("selected","selected");
						$("#service_name").trigger("change");
					}
				});

			});

		function getRandomRgb() {
			var num = Math.round(0xffffff * Math.random());
			var r = num >> 16;
			var g = num >> 8 & 255;
			var b = num & 255;
			return 'rgb(' + r + ', ' + g + ', ' + b + ')';
		}

		var EventGraph;
		var EventPie;


		function event_chart(filter={month:[],subscriber:"",service:['all']}){

			if(EventGraph){
				EventGraph.destroy();
				EventPie.destroy();
			}


			var randomScalingFactor = function() {
				return Math.round(Math.random() * 100);
			};


			filter = JSON.stringify(filter);
			var chart_data={};

			$.ajax({
				dataType:"json",
				type:"post",
				"url" : "<?php echo EXEC; ?>Exec_Chart.php?eventchart",
				data:{eventchartdata:filter},
				success: function(data)
				{
					if(data.response){
						var event="";
						var EventGraphDataset=[];
						var EventPieDataset=[];
						for(event in data.response){

							strokeStyle = getRandomRgb();

							temp1 = {
								label:'# '+event,
								data:data.response[event].count,
								borderColor: strokeStyle,
								borderWidth: 1
							};
							EventGraphDataset.push(temp1);


							temp2 = {
									data: data.response[event].EventCount,
									backgroundColor: [
									'#000000',
									'#00d600',
									'#ff8800',
									'#ffeb00',
									'#524caf',
									'#ff0000',
									],
									label: '# '+event
								}

							EventPieDataset.push(temp2);


						}


						Event_Graph_Data = {
							labels:data.response[event].month,
							datasets:EventGraphDataset
						};

						

						Event_Pie_Data = {
							labels: ['Completed','Confirmed','In Progress','Pending','Pending Payment','Canceled'],
							datasets:EventPieDataset
						};



						var ctx = document.getElementById('EventGraph').getContext('2d');
						EventGraph = new Chart(ctx, {
							type: 'line',
							data: Event_Graph_Data,
							options: {
								title: {
									display: true,
									text: 'No. of Appointments - Line Graph'
								},
								responsive: true,
								maintainAspectRatio: false,
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: true
										}
									}]
								}
							}
						});



						var config = {
							type: 'pie',
							data: Event_Pie_Data,
							options: {
								responsive: true,
								maintainAspectRatio: false,
							}
						};

						var ctx = document.getElementById('EventPie').getContext('2d');
						EventPie = new Chart(ctx, config);



					}else{
						swal("",data.error,"error");
					}
				}
			});

		}


	</script>