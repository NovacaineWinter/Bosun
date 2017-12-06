<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class work_done extends Model
{
    protected $table = 'work_done';

    public function task(){
    	return $this->belongsTo('App\tasks','task_id');
    }

    public function project(){
    	return $this->belongsTo('App\project','project_id');
    }

    public function daySummary(){
    	return $this->belongsTo('App\day_summary','day_summary_id');
    }


    /* * * * * * * * * */

    public function onDayByUser($userID,$year,$week,$day){

    	return $this->where('user_id','=',$userID)
    				->whereHas('daySummary',function($query) use ($year,$week,$day) {
    					$query->where('year','=',$year)
    						->where('week','=',$week) 
    						->where('day','=',$day);					
    				})
    				//->sortBy('time_started')
    				->get();
    }

    public function onDayOnProject($projectID,$year,$week,$day){

    }

    public function onDayOnTask($taskID,$year,$week,$day){

    }

    public function onDayForAllUsers($year,$week,$day){

    }

}
