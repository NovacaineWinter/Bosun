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


	 			case 'newMemberOfStaff':
	 			$userinfo = $request->get('data');
	 				if(!empty($userinfo)){
		 				$u = new App\User;
	 					$u->fname 			=  $request->get('userdata')['fname'];
	 					$u->lname 			=  $request->get('userdata')['lname'];
	 					$u->dob_day 		=  $request->get('userdata')['dob_day'];
	 					$u->dob_month 		=  $request->get('userdata')['dob_month'];
	 					$u->dob_year 		=  $request->get('userdata')['dob_year'];
	 					$u->addr_line_one 	=  $request->get('userdata')['addr_line_one'];
	 					$u->addr_line_two 	=  $request->get('userdata')['addr_line_two'];
	 					$u->postcode 		=  $request->get('userdata')['postcode'];
	 					$u->contact_number 	=  $request->get('userdata')['contact_number'];
	 					$u->email 			=  $request->get('userdata')['email'];
	 					$u->ice_fullname	=  $request->get('userdata')['ice_fullname'];
	 					$u->ice_contact_no 	=  $request->get('userdata')['ice_contact_no'];
	 					$u->female 			=  $request->get('userdata')['female'];
	 					$u->days_leave 		=  $request->get('userdata')['days_leave'];
	 					$u->days_per_week 	=  $request->get('userdata')['days_per_week'];
	 					$u->hours_per_week 	=  $request->get('userdata')['hours_per_week'];
	 					$u->rate 			=  $request->get('userdata')['hourlyrateid'];
	 					$u->shift_type_id 	=  $request->get('userdata')['shift_select'];
	 					$u->contractor 		=  $request->get('userdata')['contractor'];
	 					$u->vat_number 		=  $request->get('userdata')['vat_number'];
	 					$u->company_no 		=  $request->get('userdata')['company_no'];

	 					$u->employment_start_timestamp 	=  strtotime($request->get('userdata')['employment_start_date']);

	 					$u->save();

	 					$rfid = new App\userBadge;
	 					$rfid->user_id = $u->id;
	 					$rfid->badgeID = $request->get('userdata')['rfid_field'];
	 					$rfid->save();
	 					
	 					return 'All sucessfull';
	 					//return view('inside.dashboard.ajax.worker_creation_sucessfull')->with('user',$u);

	 				}else{
	 					return 'Not found the data attachement';
	 					//return view('inside.dashboard.ajax.staff_detail',compact('new_starter'));
	 				}

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
