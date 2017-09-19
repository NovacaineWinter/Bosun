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
}
