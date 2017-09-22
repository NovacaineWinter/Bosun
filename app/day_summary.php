<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day_summary extends Model
{
    protected $table = 'day_summary';

 	public function workDone(){
 		return $this->hasMany('App\work_done','day_summary_id');
 	}



//belongs to payslip



public function summarise_day(){
	$secondsWorked = 0;
	$secondsLunch = 0;
	foreach($this->workDone as $workItem){
		if($workItem->task_id!=1){
			$secondsWorked = $secondsWorked + $workItem->time_worked;
		}else{
			$secondsLunch = $secondsLunch + $workItem->time_worked;
		}
	}
}

}
