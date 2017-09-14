<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockCode extends Model
{
	protected $table = 'stockCodes';

 	public function item(){
    	return $this->belongsTo('App\stock', 'stock_id');
	}

	public function supplier(){
		return $this->belongsTo('App\supplier','supplier_id');
	}
}
