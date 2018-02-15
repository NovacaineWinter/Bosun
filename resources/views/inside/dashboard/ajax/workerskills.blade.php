<?php

use App\User;
use App\skill;

$skills = skill::where('bosun_defined','=',0)->get();
$workers= User::where('can_log_hours','=',1)->where('is_active','=',1)->get();

?>



<table id="workers-skill-table">
	<thead>
		<tr>
			<th>Name</th>
			@foreach($skills as $skill)
				<th>{{{ $skill->name }}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($workers as $worker)
		<tr>
			<td>{{{ $worker->fname.' '.$worker->lname }}}</td>
			@foreach($skills as $skill)
				@if(in_array($skill->id,$worker->skills->pluck('id')->toArray()))
					<td><input type="checkbox" skill="{{{$skill->id}}}" worker="{{{$worker->id}}}" onChange="skillChange($(this));" checked></td> 
				@else 
					<td><input type="checkbox" skill="{{{$skill->id}}}" worker="{{{$worker->id}}}" onChange="skillChange($(this));"></td>
				@endif
			@endforeach
		</tr>
		@endforeach

	</tbody>
</table>


<script>
function skillChange(target){

	if(target.is(':checked')){
		value = 'true';
	}else{
		value = 'false';
	}

	$.ajax({
	    url: "{{url('/ajax')}}",
	    method: 'GET',
	    data: {
	        ajaxmethod: 'updateWorkerSkill',
	        target: target.attr('worker'),
	       	skill: target.attr('skill'),
	       	value: value, 
	    },
	    success: function(response) {	
       
	    },
	    error: function(response) {
	        console.log('There was an error - it was:  '+response);
	    }
	});	

}	
</script>


<style>
	#workers-skill-table th{
		max-width:100px;
		text-align: center;
		box-sizing: border-box;
		padding:10px;
	}
	#workers-skill-table td{
		text-align: center;
	}
</style>