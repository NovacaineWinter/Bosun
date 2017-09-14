<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payday extends Model
{
	public function days(){
		return $this->hasMany('App\day_summary', 'payday_id');
	}
}
