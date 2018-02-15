<div class="col-sm-12">

    <div class="btn btn-info btn-lg loggingbutton userWorking project-accordion" method="wait" projectID="{{{ $project->id }}}" id="log-on" folded="1">
        {{{ $project->name }}}
    </div>

   	<div class="row accordionhidden" id="{{{ 'task-accordion-folds-for-project-id-'.$project->id }}}">
		@foreach($project->tasks as $task)
			@if(!$task->task_finished && in_array($task->skill_id,$user->skills->pluck('id')->toArray()) && $task->task_active == 1 && $task->task_finished == 0 )
				@include('outside.logging.loop_templates.task_rows', array('task'=> $task,'user'=>$user))
			@endif
		@endforeach			
	</div>	
	
</div>


