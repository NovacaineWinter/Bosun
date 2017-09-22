
<div class="btn btn-info btn-lg loggingbutton taskbutton userWorking project-accordion" 
	method="setStatus" 
	projectID="{{{ $project->id }}}" 
	userID="{{{ $user->id }}}"
	lunch="0" 
	loggedIn="1"
	taskID="{{{  $task->id  }}}"
	id="log-on" >
    {{{ $task->name }}}
</div>

