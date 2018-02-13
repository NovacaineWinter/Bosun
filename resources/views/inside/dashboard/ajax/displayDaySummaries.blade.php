<?php
	use App\config;

	$config = new config;
?>


@if(isset($daySummaries) && !empty($daySummaries))
	<div class="row col-sm-12">
		<h1>Overview of Days Worked</h1>
		<div class="dashboard-table-holder" style="max-width:1050px; margin:auto;">
			<table>
				<thead>
					<tr>
						<th>Day</th>
						<th>Date</th>						
						<th>Name</th>
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
		$(document).on('click','.daySummaryBreakdownBtn',function() {

			//get target and send ajax request
			target 	= $(this).attr('target');			
			method 	= $(this).attr('method');
			$.ajax({
                url: "{{url('/ajax')}}",
                method: 'GET',
                data: { 
                    ajaxmethod: method,			                    
                    target: target,
                },
                success: function(response) {

                    $('#day-summary-tbody-for-day-'+target).html(response);
                },
                error: function(response) {
                    console.log('There was an error - it was:');
                    console.dir(response);
                }
            });
		});





	$(document).on('dblclick','.updateablenumber',function() {
	    if($(this).attr('readonly')=='readonly'){
	        $(this).removeAttr('readonly');
	        
	    }                                
	});

	$(document).on('keypress','.updateablenumber',function(event) {
	    if (event.keyCode == 13) {                                    
	        event.preventDefault();

	        //detected enter on the input textbox
	            //send off an ajax request to update the model
	                //set input to readonly on success

	        value = $(this).val();
	        maxVal = parseInt($(this).attr('max'));
	        minVal = parseInt($(this).attr('min'));
	        dayid = $(this).attr('dayid');
	        method = $(this).attr('method');
	        target = $(this).attr('target');


	        if(value >= minVal && value <= maxVal){
		        $.ajax({
		            url: "{{url('/ajax')}}",
		            method: 'GET',
		            data: {
		                ajaxmethod: method,
		                target:   	target,
		                value:      value,

		            },
		            success: function(response) {
		                $('.updateablenumber').attr('readonly','readonly');
		                $('#day-summary-tbody-for-day-'+dayid).html(response);
		            },
		            
		            error: function(response) {
		                console.log('There was an error - it was:');
		                console.dir(response);
		            }
		        });

	        }else{
				$(this).val($(this).attr('currentval'));
				$(this).attr('readonly','readonly');
	        }
            
	    }
	});

	$(document).on('blur','.updateablenumber',function() {
		$(this).val($(this).attr('currentval'));
		$(this).attr('readonly','readonly');
	});






</script>


@else
	<h2>No Info To Display</h2>
@endif