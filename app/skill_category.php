<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class skill_category extends Model
{
	protected $table = 'skill_categories';

	public function skills(){
    	return $this->hasMany('skill','category_id');
    }
}
