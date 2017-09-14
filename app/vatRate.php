<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vatRate extends Model
{
	protected $table = 'vat_rates';

 	public function items(){
    	return $this->hasMany('App\stock', 'vatRateID');
	}
}
