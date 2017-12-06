<?php
	use App\work_done;
	$workDone = new work_done;
	$data=$workDone->onDayByUser(1,date('Y'),date('W'),date('N'));
?>


@extends('outside.logging.master_template')

@section('pagetitle')
	Logging Homepage
@stop




@section('page_content')

    	<div id="ajax-target" class="container">
	    	<div class="col-sm-10 userLoggedOff col-sm-offset-1">

				@include('outside.logging.ajax.loggingOffSummary',['data',$data]);

				<div class="row">
					<div class="col-sm-6">
						<div class="loggingbutton userOnLunch btn btn-lg" method="requestAmendment" daySummaryID="{{{ $data->first()->day_summary_id }}}">
							Request Amendment
						</div>
					</div>

					<div class="col-sm-6">
						<div class="loggingbutton userLoggedOff btn btn-lg" method="returnToLoggingHome">Log Off</div>
					</div>
				</div>
			</div>
		</div>

		<script>
		    $('.loggingbutton').click(function() {
		        ajaxMethod=$(this).attr('method');
		        if(ajaxMethod != 'wait'){
		            $.ajaxSetup({
		                headers: {
		                    'X-CSRF-TOKEN': '{{csrf_token() }}'
		                }
		            });

		            if(ajaxMethod=='requestAmendment'){

		                $.ajax({
		                    url: "{{url('/logging/ajax')}}",
		                    method: 'GET',
		                    data: {
		                        ajaxmethod: ajaxMethod,
		                        daySummaryID: $(this).attr('daySummaryID')
		                    },
		                    success: function(response) {
		                        $('#ajax-target').html(response);                                  

		                    },
		                    error: function(response) {
		                        console.log('There was an error - it was:');
		                        console.dir(response);
		                    }
		                });                

		            }else{


		                $.ajax({
		                    url: "{{url('/logging/ajax')}}",
		                    method: 'GET',
		                    data: {
		                        ajaxmethod: ajaxMethod,
		                    },
		                    success: function(response) {
		                        $('#ajax-target').html(response);                                  

		                    },
		                    error: function(response) {
		                        console.log('There was an error - it was:');
		                        console.dir(response);
		                    }
		                });

		            }
		        }
		    });
		</script>
@stop





@section('page_header_1')

	Day Summary

@stop