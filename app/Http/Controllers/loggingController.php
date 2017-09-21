<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Cookie;


/*  This function lives here as I've currently got nowhere better to store it - it needs to be moved into its own included file i think  */
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}




class LoggingController extends Controller
{
    public function index($staffID='',$projectID='',$activityID='',Request $request) {

    	return view('outside.logging.index');	

    }



    public function setTerminalCookie(){
    	$response = new \Illuminate\Http\Response('Test');
		$response->withCookie(cookie('terminal_id', 123, 45000));
		return $response;
    }

	public function ajax(Request $request){
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

        /* * * * * * * * * * */
        		case 'userGridClicked':
        			if($request->has('user_id')){
        				$user = User::find($request->get('user_id'));
        				return view('outside.logging.ajax.user_home')->with('user',$user);
        			}
        			break;
    		}
    	}else{
    		echo '<script>window.location.replace("'.url("/logging").'");</script>';
    	}
	}

}

