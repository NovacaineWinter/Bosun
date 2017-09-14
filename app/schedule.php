<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{

 	public function day_summaries(){
    	return $this->hasMany('App\day_summary', 'schedule_no');
	}

    public function worker(){
    	return $this->belongsTo('App\worker', 'worker_id');
	}

	public function payday(){
		return $this->belongsTo('App\payday', 'payday_id');
	}
}
