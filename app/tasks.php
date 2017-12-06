<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tasks extends Model
{
    public function project(){
    	return $this->belongsTo('App\project','project_id');
    }

    public function skills(){
    	return $this->belongsTo('App\skills','skill_id');
    }

 	public function workDone(){
 		return $this->hasMany('App\work_done','task_id');
 	}




 	public function totalLabourCost(){
 		return round($this->getTotalLabourCost(),2);
 	}

 	public function getTotalLabourCost(){

 		$task_id = $this->id;
 		$workDone = work_done::where('task_id', '=', $task_id)->get();

		$pay = 0;
 		if(count($workDone)>0){ 			
 			foreach($workDone as $workItem){
 				$pay = $pay + $workItem->pay_earned;
 			}
 		}
 		return ($pay);
 	}



 	public function totalHourSpend(){

 		$time = $this->getTotalHourSpend();

        $hours = floor($time/3600);
        $mins = floor(($time%3600)/60);        
        return $hours.'hrs '.$mins.' mins';
 	}

 	public function getTotalHourSpend(){
 		$task_id = $this->id;
 		$workDone = work_done::where('task_id', '=', $task_id)->get();

		$time = 0;
 		if(count($workDone)>0){ 			
 			foreach($workDone as $workItem){
 				$time = $time + $workItem->time_worked;
 			}
 		}
 		return ($time);
 	}





}
