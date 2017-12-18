<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class config extends Model
{
    protected $table = 'config';

    public function boolean($name){
    	$info = $this->where('name','=',$name)->first();
    	return $info->boolean;
    }

    public function integer($name){
    	$info = $this->where('name','=',$name)->first();
    	return $info->integer;
    }

    public function registeredTerminalIds(){
    	return array(123);

    	//for now, in the future this will need to pull a list of approved terminal ids from the database into an array and return that instead
    }

    public function secondsToHoursAndMinsString($seconds){
        if($seconds>0){
            $hours = floor($seconds/3600);
            $mins = floor(($seconds%3600)/60);

            return $hours.' hrs '.$mins.' mins';
        }else{
            return '0 hrs 0 mins';
        }
    }
}
