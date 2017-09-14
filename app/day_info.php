<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day_info extends Model
{
	protected $table = 'day_info';

    public function worker(){
    	return $this->belongsTo('App\overtime_rate', 'ot_rate_id');
	}
}
