<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\config;


class day_summary extends Model
{
    protected $table = 'day_summary';

 	public function workDone(){
 		return $this->hasMany('App\work_done','day_summary_id');
 	}


 	public function overtime(){
 		return $this->hasMany('App\overtime','day_summary_id');
 	}

 	public function user(){
 		return $this->belongsTo('App\User', 'user_id');
 	}

//belongs to payslip



public function summarise_day(){

	//check to see if we can actually perform the summary - if already timesheeted then no, we cannot
	$config = new config;

	if(!$this->is_timesheeted){

		$secondsWorked = 0;
		$secondsLunch = 0;
		$lunchTaskID=$config->integer('lunchTaskID');

		$workItems = $this->workDone;

		if(!empty($workItems)){
	
			foreach($workItems as $workItem){
				if($workItem->task_id!=$lunchTaskID){
					$secondsWorked = $secondsWorked + $workItem->time_worked;
				}else{
					$secondsLunch = $secondsLunch + $workItem->time_worked;
				}
			}	

			$this->has_logged_in = 1;	
			$this->time_worked = $secondsWorked;
			$this->time_unproductive = $secondsLunch;

			$firstWorkItem = $workItems->where('first','=',1)->first();
			if(!empty($firstWorkItem)){
				$this->first_work_done_id = $firstWorkItem->id;
			}else{
				$this->first_work_done_id = 0;
			}


			$lastWorkItem = $workItems->where('last','=',1)->first();
			if(!empty($lastWorkItem)){
				$this->last_work_done_id = $lastWorkItem->id;
			}else{
				$this->last_work_done_id = 0;
			}			

			$this->ot_worked = 0;	//these will be set once we summarise a week
			$this->bonus_time = 0;	// same

		}else{

			//set all the needed parameters to zero

			$this->has_logged_in = 0;	
			$this->time_worked = 0;
			$this->time_unproductive = 0;
			$this->first_work_done_id = 0;
			$this->last_work_done_id = 0;
			$this->ot_worked = 0;
			$this->bosun_time = 0;

		}

		$this->is_timesheeted = 0;

		$this->db_timestamp = time();
		$this->save();
	
	} //check if timesheeted
}

}
