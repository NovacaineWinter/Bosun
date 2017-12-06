<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{

    public function tasks(){
    	return $this->hasMany('App\tasks','project_id');
    }

    public function bookedOutParts(){
    	return $this->hasMany('App\bookedOutPart', 'project_id');
	}

 	public function workDone(){
 		return $this->hasMany('App\work_done','project_id');
 	}





/*
*	The functions for "This Month"
*/
 	public function costOfStockBookedOutThisMonth(){
 		return round($this->getCostOfStockBookedOutThisMonth(),2);
 	}

 	public function getCostOfStockBookedOutThisMonth(){

		$projectTotalValue=0;
		
		$beginningOfMonth = sprintf('%d-%d-01 00:00:00',date('Y'),date('m'));

		$parts = $this->bookedOutParts->where('created_at','>',$beginningOfMonth);

		foreach($parts as $part){

			$toAdd = $part->qty * $part->exVatCost;
			$projectTotalValue = $projectTotalValue + $toAdd; 
		}
	
		return $projectTotalValue;
 	}



 	public function getHourSpendThisMonth(){
 		$project_id = $this->id;
 		$workDone = work_done::where('project_id', '=', $project_id)->whereHas('daySummary', function ($query) {
    				$query->where('is_timesheeted','=',0);	
				})->get();

		$time = 0;

 		if(count($workDone)>0){ 			
 			foreach($workDone as $workItem){
 				$time = $time + $workItem->time_worked;
 			}
 		}
 		return ($time);
 	}

 	public function hourSpendThisMonth(){
 		$time = $this->getHourSpendThisMonth();

        $hours = floor($time/3600);
        $mins = floor(($time%3600)/60);        
        return $hours.'hrs '.$mins.' mins';

 	}


 	public function labourCostThisMonth(){
 		//return '&pound'.$this->labourCostThisMonth();
 		return 0;
 	} 

 	public function getLabourCostsThisMonth(){
 		$project_id = $this->id;
 		$workDone = work_done::where('project_id', '=', $project_id)->whereHas('daySummary', function ($query) {
    				$query->where('is_timesheeted','=',0);	
				})->get();

		$pay = 0;
 		if(count($workDone)>0){ 			
 			foreach($workDone as $workItem){
 				$pay = $pay + $workItem->pay_earned;
 			}
 		}
 		return ($pay);
 	}


/*
*	The functions for info in total
*/

 	public function totalCostOfStockBookedOut(){
 		return round($this->getTotalCostOfStockBookedOut(),2);
 	}

 	public function getTotalCostOfStockBookedOut(){

		$projectTotalValue=0;

		$parts = $this->bookedOutParts;

		foreach($parts as $part){

			$toAdd = $part->qty * $part->exVatCost;
			$projectTotalValue = $projectTotalValue + $toAdd; 
		}
	
		return $projectTotalValue;
 	}



 	public function totalLabourCost(){
 		return round($this->getTotalLabourCost(),2);
 	}

 	public function getTotalLabourCost(){

 		$project_id = $this->id;
 		$workDone = work_done::where('project_id', '=', $project_id)->get();

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
 		$project_id = $this->id;
 		$workDone = work_done::where('project_id', '=', $project_id)->get();

		$time = 0;
 		if(count($workDone)>0){ 			
 			foreach($workDone as $workItem){
 				$time = $time + $workItem->time_worked;
 			}
 		}
 		return ($time);
 	}





	public function totalPartsCost(){
		$projectTotalValue=0;

		foreach($this->bookedOutParts as $part){
			$toAdd = $part->qty * $part->exVatCost;
			$projectTotalValue = $projectTotalValue + $toAdd; 
		}
		
		return $projectTotalValue;
	}	
}
