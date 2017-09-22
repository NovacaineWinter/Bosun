<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class work_done extends Model
{
    protected $table = 'work_done';

    public function task(){
    	return $this->belongsTo('App\task','task_id');
    }

    public function project(){
    	return $this->belongsTo('App\project','project_id');
    }


}
