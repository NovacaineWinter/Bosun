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
				
				@foreach($daySummaries->groupBy('week') as $week)
					@foreach($week as $day)
						<tbody id="day-summary-tbody-for-day-{{{ $day->id }}}">
							<?php $detail = false; ?>
							@include('inside.dashboard.ajax.daySummaryRow')
						</tbody>
					@endforeach
					<tbody id="week-summary-tbody-for-week-{{{$week->first()->week}}}">
						<tr>
							<?php $weekSummary =$week->first()->weekSummary;  ?>
							<td colspan="8">End of week {{{$week->first()->week}}}</td>	
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
					</tbody>
				@endforeach
				
			</table>
		</div>
	</div>

<script>
	//$(document).ready(function() {
		$(document).on('click','.daySummaryBreakdownBtn',function() {
			//get target and send ajax request

			target = $(this).attr('target');
			method = $(this).attr('method');

			$.ajax({
                url: "{{url('/ajax')}}",
                method: 'GET',
                data: {
                    ajaxmethod: method,			                    
                    target: target,
                },
                success: function(response) {
/*
                	document.getElementById('day-summary-tbody-for-day-'+target).innerHTML = '';
                	z = document.createElement('div');
                	z.innerHTML = response;
                	document.getElementById('day-summary-tbody-for-day-'+target).appendChild(z);*/

                    $('#day-summary-tbody-for-day-'+target).html(response);
                },
                error: function(response) {
                    console.log('There was an error - it was:');
                    console.dir(response);
                }
            });
		});
	//});
</script>


@else
	<h2>No Info To Display</h2>
@endif