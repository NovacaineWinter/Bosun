<?php
	use App\config;

	$config = new config;
?>


@include('inside.dashboard.ajax.daySummaryRow')



	<tr>
		<th>Task</th>
		<th>Time Start</th>
		<th>Time Finish</th>
		<th>Time Elapsed</th>
		<th>Project</th>		
	</tr>


	@foreach($day->workDone->sortBy('time_started') as $work)
	<tr @if($work->task_id == $config->integer('lunchTaskID')) class="userOnLunch" @else class="userWorking" @endif>
		<td>{{{  $work->task->name  }}}</td>
		<td>
			<div class="col-sm-6" style="padding-right:0px;">
				<input type="number" class="updateablenumber" method="workItemStartHourChange" target="{{{ $work->id }}}" dayid="{{{$day->id}}}" currentval="{{{ date('G',$work->time_started) }}}" style="text-align:right;" min="0" max="23" value="{{{ date('G',$work->time_started) }}}" readonly="readonly">
			</div>
			<div class="col-sm-6" style="padding-left:5px">
				<input type="number" class="updateablenumber" method="workItemStartMinChange" target="{{{ $work->id }}}" dayid="{{{$day->id}}}" style="text-align:left;" min="0" max="59" currentval="{{{ date('i',$work->time_started) }}}" value="{{{ date('i',$work->time_started) }}}" readonly="readonly">
			</div>
		</td>
		<td>
			<div class="col-sm-6" style="padding-right:0px;">
				<input type="number" class="updateablenumber" method="workItemFinishHourChange" target="{{{ $work->id }}}" dayid="{{{$day->id}}}" style="text-align:right;" min="0" max="23" currentval="{{{ date('G',$work->time_finished) }}}" value="{{{ date('G',$work->time_finished) }}}" readonly="readonly">
			</div>
			<div class="col-sm-6" style="padding-left:5px">
				<input type="number" class="updateablenumber" method="workItemFinishMinChange" target="{{{ $work->id }}}" dayid="{{{$day->id}}}" style="text-align:left;" min="0" max="59" currentval="{{{ date('i',$work->time_finished) }}}" value="{{{ date('i',$work->time_finished) }}}" readonly="readonly">
			</div>
		</td>
		<td>{{{   $config->secondsToHoursAndMinsString($work->time_worked)  }}}</td>

		@if(isset($error))
			@if($problematic_work == $work->id)
				<td colspan="4" style="background-color:#ffb3ba">{{{ $error }}}</td>
			@endif
		@endif
		<td>{{{  $work->task->project->name  }}}</td>
	</tr>
	@endforeach	

	
		<tr>
			<td colspan="4">
				<div class="col-sm-6 btn">
					<h3 style="font-size:22px; margin:5px;">Authorise Time</h3>
				</div>
				@if($day->user_requested_amendment ==1)
				<div class="ajax-clickable col-sm-6 btn btn-info" method="markAsAmended" target="{{{ $day->id }}}">
					<h3  style="color:#fafafa; font-weight: 600;font-size:22px; margin:5px;">Mark as Amended</h3>
				</div>
				@endif
			</td>
		</tr>
	
<tr><td colspan="4">{{$day->comments}}</td></tr>


<script>
	
	$(document).ready(function() {
		$('.ajax-clickable').click(function() {
			method = $(this).attr('method');
			target = $(this).attr('target');


			 $.ajax({
	                url: "{{url('ajax')}}",
	                method: 'GET',
	                data: {
	                    ajaxmethod: method,
	                    target: target,
	                },
	                success: function(response) {
	                	$('#highlighted-row-for-dayid-'+target).removeClass('userOnLunch');
	                    $('#day-summary-tbody-for-day-'+target).html(response);                                  

	                },
	                error: function(response) {
	                    console.log('There was an error - it was:');
	                    console.dir(response);
	                }
            });                
		});
	});
</script>
