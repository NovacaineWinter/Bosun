<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\work_done;

class logging_ajax extends Controller
{
    function index(Request $request){
    	if($request->has('ajaxmethod')){

    		switch($request->get('ajaxmethod')){

        /* * * * * * * * * * */

    			case 'badgeSubmitted': 

                    $worker= User::where('badgeID','=',$request->get('badgeID'))->first();

                    if(empty($worker)){
                        App::abort(404,'ID not recognised');
                    }
                    $request->flashOnly('badgeID');

    				return view('outside.logging.ajax.user_home',['worker'=>$worker]);

    				break;


        /* * * * * * * * * * */
            
                case 'logon':

                    $worker= User::where('badgeID','=',$request->get('badgeID'))->first();
                    if(!empty($worker)){
                        if(!CONFIG['projects'] && !CONFIG['tasks']){                        
                        
                            $worker->change_activity(1,0,2,2);

                            return view('outside.logging.ajax.logging_complete'); 
                         
                        }elseif((!CONFIG['projects'] && CONFIG['tasks']) || (CONFIG['projects'] && CONFIG['tasks'] && !CONFIG['workers_choose_project'])){

                            return view('outside.logging.ajax.choose_task',['worker'=>$worker]); 


                        }elseif(CONFIG['projects'] && CONFIG['workers_choose_project'] || CONFIG['projects'] && !CONFIG['tasks']){

                            return view('outside.logging.ajax.choose_project',['worker'=>$worker]);

                        }
                    }else{
                        abort(404,'ID not recognised');
                    }

                    break;

        /* * * * * * * * * * */

                case 'logoff':
                    $worker= User::where('badgeID','=',$request->old('badgeID'))->first();
                    if($worker){
                        $worker->change_activity(0,0,0,0);
                        return view('outside.logging.ajax.logging_complete');  
                    }else{
                        abort(404,'ID not recognised');
                    }
                    break;

        /* * * * * * * * * * */

                case 'lunch':
                    $worker= User::where('badgeID','=',$request->old('badgeID'))->first();
                    if($worker){
                        $worker->change_activity(1,1,1,1);
                        return view('outside.logging.ajax.logging_complete');  
                    }else{
                        abort(404,'ID not recognised');
                    }
                    break;

        /* * * * * * * * * * */

                case 'selected_project':

                    $worker= User::where('badgeID','=',$request->old('badgeID'))->first();

                    if($worker){
                        if(CONFIG['tasks']){
                            $request->flashOnly('project_id');
                            return view('outside.logging.ajax.choose_task',['worker'=>$worker]);
                        }else{
                            $worker->change_activity(1,0,$request->get('project_id'),2);                       
                        }

                        return view('outside.logging.ajax.logging_complete');  
                    }else{
                        abort(404,'ID not recognised');
                    }
                    break;

        /* * * * * * * * * * */

                case 'selected_task':

                    $worker= User::where('badgeID','=',$request->old('badgeID'))->first();

                    if($worker){
                        if(CONFIG['projects']){
                            $pr=$request->old('project_id');
                        }else{
                            $pr=2;
                        }  
                        $worker->change_activity(1,0,$pr,$request->get('task_id')); 
                        return view('outside.logging.ajax.logging_complete'); 
                    }else{
                        abort(404,'ID not recognised');
                    } 
                    break;
    		}
    	}else{
    		echo '<script>window.location.replace("'.url("/logging").'");</script>';
    	}
    }
}


