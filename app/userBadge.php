<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class staffBadge extends Model
{

	protected $table = 'userBadges';

	public function staff(){
		return $this->belongsTo('App\User','user_id');
	}
}
