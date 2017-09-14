<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
	protected $table = 'stock';

 	public function category(){
    	return $this->belongsTo('App\stockCategory', 'category_id');
	}

 	public function subCategory(){
    	return $this->belongsTo('App\stockSubcategory', 'subcategory_id');
	}

	public function supplierCodes(){
		return $this->hasMany('App\stockCode','stock_id');
	}

 	public function vatRate(){
    	return $this->belongsTo('App\vatRate', 'vatRateID');
	}

    public function bookedOutParts(){
    	return $this->hasMany('App\bookedOutPart', 'stock_id');
	}
	
}
