<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;

use App\project;
use App\tasks;

class DashboardController extends Controller
{
    public function index() {

    	if(Auth()->User()->may('can_view_dashboard')){
	    	$data="hello world";
	    	return view('inside.dashboard.index', compact('data'));
    	}else{
    		return view('inside.dashboard.not_permitted');	
    	}

    }


    public function ajax(Request $request){
    	if($request->has('ajaxmethod')){

	 		switch($request->input('ajaxmethod')){

 
/*Workers*/		case 'worker_detail':
	 				if($request->has('workerID')){

	 					/* Staff ID has been sent over AJAX so let's pass this info on to the view to render the information as requried */
	 					$staffID=$request->input('workerID');
	 					return view('inside.dashboard.ajax.staff_detail',compact('staffID'));

	 				}else{

	 					/* No staff ID has been passed meaning we're trying to make a new member of staff */
	 					$new_starter=true;
	 					return view('inside.dashboard.ajax.staff_detail',compact('new_starter'));
	 				}
	 				break;



	 			case 'project-summary':
	 				if($request->has('target')){
	 					return view('inside.dashboard.ajax.project_overview')->with('request',$request);
	 				}else{
	 					return view('inside.dashboard.overview');
	 				}
	 				break;

	 			case 'changedProjectDescription':
	 				if($request->has('target')&&$request->has('value')){
	 					$project = project::find($request->get('target'));
	 					$project->description = $request->get('value');
	 					$project->save();
	 				}
	 				return;


	 			case 'taskactive':
	 				if($request->has('target')&&$request->has('value')){
	 					$task = tasks::find($request->get('target'));
	 					$task->task_active = $request->get('value');
	 					
	 					if($request->get('value')){
		 					$task->task_finished = false;	 						
	 					}
	 					$task->save();
	 				}
	 				break;

	 			case 'taskfinished':
	 				if($request->has('target')&&$request->has('value')){
	 					$task = tasks::find($request->get('target'));
	 					$task->task_finished = $request->get('value');
	 					
	 					if($request->get('value')){
		 					$task->task_active = false;
	 					}	 	
	 					$task->save();
	 				}
	 				break;	 				





/*shifts*/		case 'shifts':
	 				return view('inside.dashboard.ajax.shifts');
	 				break;

/*projects*/ 	case 'projectsDashboard':
	 				return view('inside.dashboard.ajax.projects');
	 				break;

	 			case 'dashboardOverview':
	 				return view('inside.dashboard.overview');
	 				break;

	 			case 'realtimeDashboard':
	 				return view('inside.dashboard.ajax.realtime');

	 			case 'workersDashboard':
	 				return view('inside.dashboard.ajax.workers');
	 				break;
	 			default:
	 				return view('inside.dashboard.overview');
	 				break;
	 		}
	 	}else{
	 		return view('inside.dashboard.overview');
	 	}
    }
}
