<?php
	use App\config;

	$config = new config;
?>


@if(isset($daySummaries) && !empty($daySummaries))
	<div class="row col-sm-12">
		<h1>Overview of Days Worked</h1>

		<div class="col-sm-8 col-sm-offset-2 dashboard-table-holder">
			<table>
				<thead>
					<tr>
						<th>Name</th>
						<th>Date</th>
						<th>Day</th>
						<th>Time In</th>
						<th>Time Out</th>
						<th>Lunch</th>
						<th>Time Worked</th>
						<th>View Details</th>
					</tr>
				</thead>
				<tbody>
				@foreach($daySummaries->groupBy('week') as $week)
					@foreach($week as $day)
						<tr>
							<td>{{{  $day->user->name  }}}</td>
							<td>{{{  date('Y-m-d',$day->time_in_stamp)  }}}</td>
							<td>{{{  date('D',$day->time_in_stamp)  }}}</td>
							<td>{{{  date('H:i',$day->time_in_stamp)  }}}</td>
							<td>{{{  date('H:i',$day->time_out_stamp)  }}}</td>
							<td>{{{  $config->secondsToHoursAndMinsString($day->time_worked)  }}}</td>
							<td>{{{  $config->secondsToHoursAndMinsString($day->time_unproductive)  }}}</td>
							<td><div class="btn" method="daySummaryBreakdown" target="{{{ $day->id }}}"><</div></td>
						</tr>
					@endforeach
					<tr>
						<?php $weekSummary =$week->first()->weekSummary;  ?>
						<td colspan="8">End of week {{{$week->first()->week}}}</td>	
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>


@else
	<h2>No Info To Display</h2>
@endif