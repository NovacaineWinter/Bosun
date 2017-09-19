<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bookedOutPart extends Model
{
	protected $table = 'bookedOutStock';


    public function item(){
    	return $this->belongsTo('App\stock', 'stock_id');
	}

    public function project(){
    	return $this->belongsTo('App\stock', 'project_id');
	}
}
