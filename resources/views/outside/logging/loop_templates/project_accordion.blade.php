<div class="col-sm-12">

    <div class="btn btn-info btn-lg loggingbutton userWorking project-accordion" method="wait" projectID="{{{ $project->id }}}" id="log-on" >
        {{{ $project->name }}}
    </div>

   	<div class="row accordionhidden" id="{{{ 'task-accordion-folds-for-project-id-'.$project->id }}}">
		@foreach($project->tasks as $task)
			@if(!$task->task_finished && in_array($task->skill_id,$user->skills->pluck('id')->toArray()))
				@include('outside.logging.loop_templates.task_rows', array('task'=> $task))
			@endif
		@endforeach			
	</div>	
	
</div>


