<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class skill extends Model
{
    protected $table = 'skills';

    public function usersWithThisSkill(){
        return $this->belongsToMany('App\skill','user_skills','skill_id','user_id');
    }
}
