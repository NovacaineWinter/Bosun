<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
	protected $table = 'suppliers';

 	public function codes(){
    	return $this->hasMany('App\stockCode', 'supplier_id');
	}

}
