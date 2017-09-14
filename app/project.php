<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    public function work_done(){
    	return $this->hasMany('work_done','project_id');
    }

    public function tasks(){
    	return $this->hasMany('App\tasks','project_id');
    }

    public function bookedOutParts(){
    	return $this->hasMany('App\bookedOutPart', 'project_id');
	}
}
