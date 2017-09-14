<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day_summary extends Model
{
	protected $table = 'day_summary';

    public function worker(){
    	return $this->belongsTo('App\worker', 'worker_id');
	}

    public function schedule(){
    	return $this->belongsTo('App\schedule', 'schedule_no');
	}

	public function work_done_entries(){
		return $this->hasMany('App\work_done','day_summary_id');
	}

	public function summarise_day(){
		foreach($this->work_done_entries as $wd){
			$productive=0;
			$unproductive=0;
			if($wd->task_id==0){
				$unproductive=$unproductive+$wd->time_worked;
			}else{
				$productive=$productive+$wd->time_worked;
			}
		}
	}
}
