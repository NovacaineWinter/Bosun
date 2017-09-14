<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\project;
use App\tasks;
use Response;
use App;


/*
*	Need to check if a project has already been selected - if so then we need to filter the tasks for that project ID
*
*/


if(CONFIG['projects'] && CONFIG['workers_choose_project'] || CONFIG['projects'] && !CONFIG['tasks']){
	
}

$tasks=tasks::whereIn(
	'skill_id',
	$worker->skills->pluck('id')
)->get();


?>

@foreach($tasks as $task)



	{{{$task->name}}}
@endforeach
