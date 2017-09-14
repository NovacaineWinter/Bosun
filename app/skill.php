<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class skill extends Model
{
    public function category(){
    	return $this->belongsTo('skill_category','category_id');
    }

    public function tasks(){
    	return $this->hasMany('App\tasks','skill_id');
    }

	public function skills() {
		return $this->belongsToMany('App\worker','workers_skills','skill_id','worker_id');
	}
}
