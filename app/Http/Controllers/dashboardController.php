<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\project;
use App\tasks;
use App\User;
use App\userBadge;

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


	 				$u = new User;

	 				$u->name 			=  $request->get('fname').' '.$request->get('lname');
	 				$u->email 			=  $request->get('fname').$request->get('lname').'@nottinghamboatco.com';
	 				$u->password 		=  Hash::make('EPIC1');
 					$u->fname 			=  $request->get('fname');
 					$u->lname 			=  $request->get('lname'); 					
 					$u->dob_day 		=  $request->get('dob_day');
 					$u->dob_month 		=  $request->get('dob_month');
 					$u->dob_year 		=  $request->get('dob_year');
 					$u->addr_line_one 	=  $request->get('addr_line_one');
 					$u->addr_line_two 	=  $request->get('addr_line_two');
 					$u->postcode 		=  $request->get('postcode');
 					$u->contact_number 	=  $request->get('contact_number'); 					
 					$u->ice_fullname	=  $request->get('ice_fullname');
 					$u->ice_contact_no 	=  $request->get('ice_contact_no');
 					$u->female 			=  $request->get('female');
 					$u->days_leave 		=  $request->get('days_leave');
 					$u->days_per_week 	=  $request->get('days_per_week');
 					$u->hours_per_week 	=  $request->get('hours_per_week');
 					$u->rate 			=  $request->get('hourlyrateid');
 					$u->shift_type_id 	=  $request->get('shift_select');
 					$u->contractor 		=  $request->get('contractor');
 					$u->vat_number 		=  $request->get('vat_number');
 					$u->company_no 		=  $request->get('company_no');
 					$u->days_leave 		=  $request->get('days_leave');
 					$u->days_per_week 	=  $request->get('days_per_week');
 					$u->hours_per_week 	=  $request->get('hours_per_week'); 
 					$u->is_active		=  1;
 					$u->can_log_hours	= 1;					

 					$u->employment_start_timestamp 	=  strtotime($request->get('employment_start_date'));

 					$u->save();

 					/*
					$u = User::create([
	 				'email' 			=>  $request->get('fname').$request->get('lname').'@nottinghamboatco.com',
	 				'password' 			=>  Hash::make('EPIC1'),
	 				'name'				=>  $request->get('fname').' '.$request->get('lname'),
 					'fname' 			=>  $request->get('fname'),
 					'lname' 			=>  $request->get('lname'), 					
 					'dob_day' 			=>  $request->get('dob_day'),
 					'dob_month' 		=>  $request->get('dob_month'),
 					'dob_year' 			=>  $request->get('dob_year'),
 					'addr_line_one' 	=>  $request->get('addr_line_one'),
 					'addr_line_two' 	=>  $request->get('addr_line_two'),
 					'postcode' 			=>  $request->get('postcode'),
 					'contact_number' 	=>  $request->get('contact_number'), 					
 					'ice_fullname'		=>  $request->get('ice_fullname'),
 					'ice_contact_no' 	=>  $request->get('ice_contact_no'),
 					'female' 			=>  $request->get('female'),
 					'days_leave' 		=>  $request->get('days_leave'),
 					'days_per_week' 	=>  $request->get('days_per_week'),
 					'hours_per_week' 	=>  $request->get('hours_per_week'),
 					'rate' 				=>  $request->get('hourlyrateid'),
 					'shift_type_id' 	=>  $request->get('shift_select'),
 					'contractor' 		=>  $request->get('contractor'),
 					'vat_number' 		=>  $request->get('vat_number'),
 					'company_no' 		=>  $request->get('company_no'),
 					'can_log_hours'		=>  1,

 					'employment_start_timestamp' 	=>  strtotime($request->get('employment_start_date')),
 					]);

 						*/				

 					$rfid = new userBadge;
 					$rfid->user_id = $u->id;
 					$rfid->badgeID = $request->get('rfid_field');
 					$rfid->save();
 	
 					return 'All sucessfull';
 					//return view('inside.dashboard.ajax.worker_creation_sucessfull')->with('user',$u);


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
