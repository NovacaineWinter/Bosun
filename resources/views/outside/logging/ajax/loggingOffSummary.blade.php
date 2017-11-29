<?php

use App\config;
$config = new config;

?>


<?php if($data->isNotEmpty()){

	$totalTimeWorked = 0;
	$totalTimeLunch = 0;
	$timestampStart = time();
	$timestampFinish= time();


	foreach($data as $entry){

		if($entry->first){
			$timestampStart = $entry->time_started;
		}

		if($entry->last){
			$timestampFinish = $entry->time_finished;
		}

		if($entry->task_id != $config->integer('lunchTaskID')){
			$totalTimeWorked = $totalTimeWorked + $entry->time_worked;
		}else{
			$totalTimeLunch = $totalTimeLunch + $entry->time_worked;
		}
	}

	$tstart = date('H:i',$timestampStart);
	$tfinish = date('H:i',$timestampFinish);

	$hoursLunch=floor($totalTimeLunch/3600);
	$minsOnLunch=floor(($totalTimeLunch%3600)/60);

	$hoursWorked=floor($totalTimeWorked/3600);
	$minsWorked=floor(($totalTimeWorked%3600)/60);



	if($hoursLunch!=0){
		$timeOnLunch = $hoursLunch.'hr '.$minsOnLunch.' min';
	}else{
		$timeOnLunch = $minsOnLunch.' min';
	}


	if($hoursWorked!=0){
		$timeWorked = $hoursWorked.'hr '.$minsWorked.' min';
	}else{
		$timeWorked = $minsWorked.' min';
	}
	?>




		<table class="timesummarytable">
			<tr>
				<th>Project</th>
				<th>Task</th>
				<th>Time Started</th>
				<th>Time Finished</th>
			</tr>
			@foreach($data as $entry)
				<tr>
					<td>{{{ $entry->project->name }}}</td>
					<td>{{{ $entry->task->name }}}</td>
					<td>{{{ date('H:i',$entry->time_started) }}}</td>
					<td>{{{ date('H:i',$entry->time_finished) }}}</td>
				</tr>

			@endforeach
		</table>

		<div class="row userWorking btn loggingbutton" method="wait" style="cursor:initial;margin-top:30px;margin-bottom:30px;">
			<h3>Totals For the day</h3>

			<table class="timesummarytable">
				<thead>
					<tr>
						<th>Started</th>
						<th>Finished</th>
						<th>Lunch</th>
						<th>Hours Worked</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{{ $tstart }}}</td>
						<td>{{{ $tfinish }}}</td>
						<td>{{{ $timeOnLunch }}}</td>
						<td>{{{ $timeWorked }}}</td>
					</tr>
				</tbody>
			</table>
		</div>


<?php }else{ ?>
	<h3>You have no recorded activity for today</h3>
<?php } ?>
