<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ajaxController extends Controller
{
 	public function index(Request $request) {

 		if($request->has('ajaxmethod')){

	 		switch($request->input('ajaxmethod')){


/*Workers*/		case 'workers':
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

/*projects*/ 	case 'projects':
	 				return view('inside.dashboard.ajax.projects');
	 				break;

	 			default:
	 				return view('inside.dashboard.ajax.index');
	 				break;
	 		}
	 	}else{
	 		return view('inside.dashboard.ajax.index');
	 	}

    }
}
