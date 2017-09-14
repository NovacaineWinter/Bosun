<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockCategory extends Model
{
	protected $table = 'stockCategories';

 	public function items(){
    	return $this->hasMany('App\stock', 'category_id');
	}

	public function subcategories(){
		return $this->hasMany('App\subCategory','category_id');
	}
}
