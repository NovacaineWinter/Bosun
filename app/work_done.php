<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class work_done extends Model
{
	protected $table = 'work_done';

	public function worker(){
		return $this->belongsTo('App\worker','worker_id');
	}

	public function project(){
		return $this->belongsTo('App\project','project_id');
	}

	public function day_summary(){
		return $this->belongsTo('App\day_summary','day_summary_id');
	}
}
