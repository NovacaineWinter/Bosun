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
	</tr>

	@foreach($day->workDone as $work)
	<tr @if($work->task_id == $config->integer('lunchTaskID')) class="userOnLunch" @else class="userWorking" @endif>
		<td>{{{  $work->task->name  }}}</td>
		<td>
			<div class="col-sm-6" style="padding-right:0px;">
				<input type="number" class="updateablenumber" style="text-align:right;" min="0" max="23" value="{{{ date('G',$work->time_started) }}}"" readonly="readonly">
			</div>
			<div class="col-sm-6" style="padding-left:5px">
				<input type="number" class="updateablenumber" style="text-align:left;" min="0" max="59" value="{{{ date('i',$work->time_started) }}}" readonly="readonly">
			</div>
		</td>
		<td>
			<div class="col-sm-6" style="padding-right:0px;">
				<input type="number" class="updateablenumber" style="text-align:right;" min="0" max="23" value="{{{ date('G',$work->time_finished) }}}" readonly="readonly">
			</div>
			<div class="col-sm-6" style="padding-left:5px">
				<input type="number" class="updateablenumber" style="text-align:left;" min="0" max="59" value="{{{ date('i',$work->time_finished) }}}" readonly="readonly">
			</div>
		</td>
		<td>{{{   $config->secondsToHoursAndMinsString($work->time_worked)  }}}</td>
	</tr>
	@endforeach	
<tr><td colspan="4">&nbsp;</td></tr>
