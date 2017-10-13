<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;

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


/*shifts*/		case 'shifts':
	 				return view('inside.dashboard.ajax.shifts');
	 				break;

/*projects*/ 	case 'projectsDashboard':
	 				return view('inside.dashboard.ajax.projects');
	 				break;

	 			case 'dashboardOverview':
	 				return view('inside.dashboard.overview');
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
