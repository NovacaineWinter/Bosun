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
}
