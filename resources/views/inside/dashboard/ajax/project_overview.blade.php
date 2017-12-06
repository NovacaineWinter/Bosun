<?php
use App\project;
use App\config;
use App\skill;

$config= new config;

$skills = App\skill::where('bosun_defined','=',0)->get();

if($request->has('target')){
	$project = project::find($request->get('target'));	
}

?>
@if(!empty($project))

	


<h1>{{{  $project->name  }}}</h1>
<div class="col-sm-12 dashboard-table-holder">
    <div class="row col-lg-8 col-lg-offset-2">
    	<h3 class="pull-left">Description</h3>   

   		<textarea class="changeableTextArea col-xs-12" textareaParent="projectDescription" target="{{{$project->id}}}">{{{$project->description}}}</textarea>
    </div>   
   


    <div class="col-lg-8 col-lg-offset-2" id="project-info-holder">
    	<div class="row">
    		<div class="col-xs-3 project-info-heading nav-selected" style="border-top-left-radius:10px;"><h4>Overview</h4></div>
    		<div class="col-xs-3 project-info-heading"><h4>Parts</h4></div>
    		<div class="col-xs-3 project-info-heading"><h4>Labour</h4></div>
    		<div class="col-xs-3 project-info-heading" style="border-top-right-radius:10px;"><h4>History</h4></div>
    	</div>
	@if($config->boolean('tasks'))

		<div class="col-lg-12">			
		

			@if($project->tasks->count()== 0 || ($project->tasks->count()==1 && $project->tasks->first()->id == $project->default_task))

				<h3>This project currently has no tasks</h3>



			@else
				@foreach($project->tasks->where('id','!=',$project->default_task) as $task)
					<div id="taskrow-for-task-{{{$task->id}}}" class="taskrow col-sm-12">
						<h3>{{{  $task->name  }}}</h3>
						<div class="col-lg-6">		
							<table style="border:none">
								<tr  style="border:none">
									<th style="border:none">
										Skill:
										<select style="padding:5px;">											
											@foreach($skills as $skill)
												<option value="{{{ $skill->id }}}" @if($task->skill_id == $skill->id) selected @endif>{{{ $skill->name }}}</option>
											@endforeach
										</select>
									</th>
									<th class="ajaxcheckboxholder" style="border:none">
										<label>
											<input type="checkbox" id="activeCheckboxFor{{{$task->id}}}" class="ajaxcheckbox" method="taskactive" target="{{{ $task->id }}}" @if($task->task_active) checked @endif>
											<span>Active</span>
										</label>
									</th>
									<th class="ajaxcheckboxholder" style="border:none">
										<label>
											<input type="checkbox" id="finishCheckboxFor{{{$task->id}}}" class="ajaxcheckbox" method="taskfinished" target="{{{$task->id}}}" @if($task->task_finished) checked @endif>
											<span>Finished</span>
										</label>
									</th>
								</tr>
							</table>

						</div>
						<div class="col-lg-6">
							<div class="col-sm-8" style="border:1px solid #808080;border-radius:5px;">
								<div class="col-sm-3">Labour</div>
								<div class="col-sm-6">{{{  $task->totalHourSpend()  }}}</div>
								<div class="col-sm-3">&pound; {{{  $task->totalLabourCost()  }}}</div>
							</div>
							<div class="col-sm-4">
								<div class="col-sm-4">Parts</div>
								<div class="col-sm-8">&pound; 0</div>
							</div>
						</div>
					</div>
				@endforeach
			@endif

			<div class="col-sm-2 col-sm-offset-5 btn loggingbutton userOnLunch">+Add Task</div>

		</div>
	@endif

	</div>
</div>

@else
	Project Not Found
@endif

<script>
	$('.changeableTextArea').blur(function() {
		$(this).css('background-color','#fff');  
		 $.ajax({
		    url: "{{url('/ajax')}}",
		    method: 'GET',
		    data: {
		        ajaxmethod: 'changedProjectDescription',
		        target: $(this).attr('target'),
		        value: $(this).val(),
		    },
		    success: function(response) {
		              
		    },
		    error: function(response) {
		        console.log('There was an error - it was:  '+response);
		    }
		});
	});

	$('.changeableTextArea').focus(function() {
		$(this).css('background-color','#f1f1f1');
	});


	$('.ajaxcheckbox').change(function() {

		target = $(this).attr('target');
		method = $(this).attr('method');
		if($(this).is(':checked')){
			status = 1;
			switch(method){

				case 'taskactive':
					$('#finishCheckboxFor'+target).prop('checked',false);
					break;

				case 'taskfinished':
					$('#activeCheckboxFor'+target).prop('checked',false);
					break;
			}			
		}else{
			status = 0;
		}

		$.ajax({
		    url: "{{url('/ajax')}}",
		    method: 'GET',
		    data: {
		        ajaxmethod: method,
		        target: target,
		        value: status,
		    },
		    success: function(response) {

		    },
		    error: function(response) {
		        console.log('There was an error - it was:  '+response);
		    }
		});

	});

</script>