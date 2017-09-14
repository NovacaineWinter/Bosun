<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class overtime_rate extends Model
{
    public function days(){
    	return $this->hasMany('App\overtime_rate', 'ot_rate_id');
	}
}
