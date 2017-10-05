<?php
use App\work_done;
use App\user;
$workDone=new work_done;
$worker = User::find($statusInfo['worker']->id);


$data=$workDone->onDayByUser($worker->id,date('Y'),date('W'),date('N'));


?>

@if($statusInfo['loggedIn']==0)

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


@elseif($statusInfo['onLunch']==1)
	<div class="col-sm-8 col-sm-offset-2">
		<div class="loggingbutton userOnLunch btn">
			<h3>{{{ $worker->fname }}}, You are now on lunch</h3>
		</div>
	</div>
@else
	<!-- Doing work - make short summary -->
	<div class="col-sm-8 col-sm-offset-2">
		<div class="loggingbutton userWorking btn">
			<h3>{{{ $worker->fname }}}, You are logged on to :</h3> 
			<h4>{{{ $worker->task->project->name }}} - {{{ $worker->task->name }}}</h4>
		</div>
	</div>
@endif


<script>
	
//setTimeout(window.location.replace(" {{{url('/logging')}}}"),5000);
</script>




