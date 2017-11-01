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


 	public function costOfStockBookedOutThisMonth(){
 		return '2235.43';
 	}

 	public function hourSpendThisMonth(){
 		return '345 Hrs 08 Mins';
 	}

 	public function labourCostThisMonth(){
 		return '4105.53';
 	}

 	public function totalCostOfStockBookedOut(){
 		return 'totalCostOfStock';
 	}

 	public function totalLabourCost(){
 		return 'totalLabourCost';
 	}

 	public function totalHourSpend(){
 		return 'totalHourSpend';
 	}
}
