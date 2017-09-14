<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockSubcategory extends Model
{
	protected $table = 'stockSubcategories';

 	public function items(){
    	return $this->hasMany('App\stock', 'subcategory_id');
	}

	public function category(){
		return $this->belongsTo('App\Category','category_id');
	}
}
