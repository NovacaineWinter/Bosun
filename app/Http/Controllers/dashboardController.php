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
use App\day_summary;
use App\work_done;
use App\config;
use App\user_skill;

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





	 			case 'worker_skills':
	 				return view('inside.dashboard.ajax.workerskills');
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
		 					$task->task_finished = 0;	 						
	 					}
	 					$task->save();
	 				}
	 				break;

	 			case 'taskfinished':
	 				if($request->has('target')&&$request->has('value')){
	 					$task = tasks::find($request->get('target'));
	 					$task->task_finished = $request->get('value');
	 					
	 					if($request->get('value')){
		 					$task->task_active = 0;
	 					}	 	
	 					$task->save();
	 				}
	 				break;	


	 			case 'worker_untimesheeted_day_summaries':
	 				if($request->has('target')){
	 					$daySummaries = day_summary::where('user_id','=',$request->get('target'))->where('is_timesheeted','!=',1)->get();
	 					if($daySummaries->count()<=0){
	 						$daySummaries = array();
	 					}
	 					return view('inside.dashboard.ajax.displayDaySummaries')->with('daySummaries',$daySummaries);
	 				}
	 				break; 				


	 			case 'daySummaryBreakdown':
	 				if($request->has('target')){
	 					$day = day_summary::find($request->get('target'));
	 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);
	 				}
	 				break;

	 			case 'daySummarySingleRow':

	 				if($request->has('target')){
	 					$day = day_summary::find($request->get('target'));
	 					return view('inside.dashboard.ajax.daySummaryRow')->with('day',$day)->with('detail',0);
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



	 			case 'workItemStartHourChange':
	 				if($request->has('target') && $request->has('value')){

	 					$work = work_done::find($request->get('target'));
	 					$day = $work->daySummary;

	 					//if($request->get('value') <= 23 && $request->get('value') <= 0){

		 					$midnight = strtotime(date('Y-m-d',$work->time_started));
		 					$minsandseconds = $work->time_started%3600;
		 					$newStartTime = $midnight + (3600 * $request->value) + $minsandseconds -(date('Z',$work->time_started));
		 					//this is midnight time, plus the number of hours, plus the mins and seconds subtract the difference due to timezone / daylight savings
		 						
		 					if($newStartTime<$work->time_finished){
		 						if(!$work->first){
		 							$previousWork = work_done::find($work->previous_id);
		 							if($previousWork->time_started < $newStartTime){
		 								//update the work done item that we are editing
		 								$work->time_started = $newStartTime;	
					 					$work->save();
					 					$work->recalculate();

					 					//update the previous work done item
					 					$previousWork->time_finished = $newStartTime;
					 					$previousWork->save();
					 					$previousWork->recalculate();
		 							}else{
		 								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set start time before previous work item start time')->with('problematic_work',$work->id);
		 							}

		 						}else{
		 							//this is first so just update this one and be done with it
									$work->time_started = $newStartTime;	
				 					$work->save();
				 					$work->recalculate(); 							
		 						}
		 					}else{
								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set start time after the finish time.')->with('problematic_work',$work->id);
							}

		 					$day = $work->daySummary;
		 					$day->summarise_day();
		 					$updatedDay = day_summary::find($day->id);

		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$updatedDay)->with('detail',1);

		 				/*}else{
		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);	
		 				}*/
	 				}
	 				break;



				case 'workItemStartMinChange':				
	 				if($request->has('target') && $request->has('value')){

	 					$work = work_done::find($request->get('target'));
	 					$day = $work->daySummary;

	 					//if($request->get('value') <= 59 && $request->get('value') <= 0){

							//calculate new timestamp

		 					$midnight = strtotime(date('Y-m-d',$work->time_started));
		 					$hours = date('H',$work->time_started);
		 					$seconds = $work->time_started%60;
		 					$newStartTime = $midnight + (3600 * $hours)+ ($request->get('value') * 60) + $seconds -(date('Z',$work->time_started));
		 					//this is midnight time, plus the number of hours, plus the mins and seconds subtract the difference due to timezone / daylight savings
		 						

		 					if($newStartTime<$work->time_finished){
		 						if(!$work->first){
		 							$previousWork = work_done::find($work->previous_id);
		 							if($previousWork->time_started < $newStartTime){
		 								//update the work done item that we are editing
		 								$work->time_started = $newStartTime;	
					 					$work->save();
					 					$work->recalculate();

					 					//update the previous work done item
					 					$previousWork->time_finished = $newStartTime;
					 					$previousWork->save();
					 					$previousWork->recalculate();
		 							}else{
		 								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set start time before previous work item start time')->with('problematic_work',$work->id);
		 							}

		 						}else{
		 							//this is first so just update this one and be done with it
									$work->time_started = $newStartTime;	
				 					$work->save();
				 					$work->recalculate(); 							
		 						}
		 					}else{
								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set start time after the finish time.')->with('problematic_work',$work->id);
							}

		 					$day = $work->daySummary;
		 					$day->summarise_day();
		 					$updatedDay = day_summary::find($day->id);

		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$updatedDay)->with('detail',1);
		 				/*}else{
		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);	
		 				}*/
	 				}
	 				break;	 			



	 			case 'workItemFinishHourChange':

	 				if($request->has('target') && $request->has('value')){

	 					$work = work_done::find($request->get('target'));
	 					$day = $work->daySummary;

	 					//if($request->get('value') <= 23 && $request->get('value') <= 0){


		 					$midnight = strtotime(date('Y-m-d',$work->time_finished));
		 					$minsandseconds = $work->time_finished%3600;
		 					$newFinishTime = $midnight + (3600 * $request->value) + $minsandseconds -(date('Z',$work->time_finished));
		 					//this is midnight time, plus the number of hours, plus the mins and seconds subtract the difference due to timezone / daylight savings
		 						
		 					if($newFinishTime > $work->time_started){

		 						if($work->last == 0){
		 							$nextWork = work_done::find($work->next_id);
		 							if($nextWork->time_finished > $newFinishTime){
		 								//update the work done item that we are editing
		 								$work->time_finished = $newFinishTime;	
					 					$work->save();
					 					$work->recalculate();

					 					//update the previous work done item
					 					$nextWork->time_started = $newFinishTime;
					 					$nextWork->save();
					 					$nextWork->recalculate();
		 							}else{
		 								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set finish time after next work item finish time')->with('problematic_work',$work->id);
		 							}

		 						}else{
		 							//this is first so just update this one and be done with it
									$work->time_finished = $newFinishTime;	
				 					$work->save();
				 					$work->recalculate(); 							
		 						}
		 					}else{
								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set finish time before the start time.')->with('problematic_work',$work->id);
							}

		 					$day = $work->daySummary;
		 					$day->summarise_day();
		 					$updatedDay = day_summary::find($day->id);

		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$updatedDay)->with('detail',1);

		 				/*}else{
		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);	
		 				}*/
	 				}
	 				break;



				case 'workItemFinishMinChange':

	 				if($request->has('target') && $request->has('value')){

	 					$work = work_done::find($request->get('target'));
	 					$day = $work->daySummary;

	 					//if($request->get('value') <= 23 && $request->get('value') <= 0){


		 					$midnight = strtotime(date('Y-m-d',$work->time_finished));		
		 					$hours = date('H',$work->time_finished);
		 					$seconds = $work->time_finished%60;
		 					$newFinishTime = $midnight + (3600 * $hours)+ ($request->get('value') * 60) + $seconds -(date('Z',$work->time_started));		 					
		 					//this is midnight time, plus the number of hours, plus the mins and seconds subtract the difference due to timezone / daylight savings
		 						
		 					if($newFinishTime > $work->time_started){

		 						if(!$work->last){
		 							$nextWork = work_done::find($work->next_id);
		 							if($nextWork->time_finished > $newFinishTime){
		 								//update the work done item that we are editing
		 								$work->time_finished = $newFinishTime;	
					 					$work->save();
					 					$work->recalculate();

					 					//update the previous work done item
					 					$nextWork->time_started = $newFinishTime;
					 					$nextWork->save();
					 					$nextWork->recalculate();
		 							}else{
		 								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set finish time after next work item finish time')->with('problematic_work',$work->id);
		 							}

		 						}else{
		 							//this is first so just update this one and be done with it
									$work->time_finished = $newFinishTime;	
				 					$work->save();
				 					$work->recalculate(); 							
		 						}
		 					}else{
								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','Cannot set finish time before the start time.')->with('problematic_work',$work->id);
							}

		 					$day = $work->daySummary;
		 					$day->summarise_day();
		 					$updatedDay = day_summary::find($day->id);

		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$updatedDay)->with('detail',1);

		 				/*}else{
		 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);	
		 				}*/
	 				}
	 				break;	 				



	 			case 'insertLunchBreak':
	 				$config = new config;
	 				if($request->has('target')){

	 					$day = day_summary::find($request->get('target'));

						$lunchTaskId = $config->integer('lunchTaskID');
						$lunchProjectId = $config->integer('lunchProjectId');

	 					$lunchStartHour 	= $config->integer('lunch_start_hour');
	 					$lunchStartMin  	= $config->integer('lunch_start_min');
	 					$lunchFinishHour 	= $config->integer('lunch_finish_hour');
	 					$lunchFinishMin  	= $config->integer('lunch_finish_min');

	 					$midnight = strtotime(date('Y-m-d',$day->time_in_stamp));

	 					$lunchStartStamp = $midnight + ($lunchStartHour * 3600) + ($lunchStartMin * 60) - date('Z',$day->time_in_stamp);
	 					$lunchFinishStamp = $midnight + ($lunchFinishHour * 3600) + ($lunchFinishMin * 60) - date('Z',$day->time_in_stamp);


	 					if($day->workDone->count()>0){
	 						foreach($day->workDone as $work){

	 							if($work->time_started < $lunchStartStamp && $lunchFinishStamp < $work->time_finished){
	 								//need to split the current work done item as it fully encapsulates the desired lunch period

	 								$timeToFinish = $work->time_finished;
	 								$isLast = $work->last;
	 								$theNext = $work->next_id;

	 								$work->time_finished = $lunchStartStamp;
	 								$work->last = 0;


	 								$lunchWork = new work_done;
	 								$lunchWork->user_id = $work->user_id;
	 								$lunchWork->time_worked = 0;//just so it has a value - to be calculated by recalculate
	 								$lunchWork->pay_earned = 0; //just so it has a value - to be calculated by recalculate
	 								$lunchWork->project_id = $lunchProjectId;
	 								$lunchWork->task_id = $lunchTaskId;
	 								$lunchWork->time_started = $lunchStartStamp;
	 								$lunchWork->time_finished = $lunchFinishStamp;
	 								$lunchWork->day_summary_id = $work->day_summary_id;
	 								$lunchWork->previous_id = $work->id;
	 								$lunchWork->base_hourly_rate	= 0;//$work->user->lunch_pay_rate;
	 								$lunchWork->first = 0;
	 								$lunchWork->last = 0;
	 								$lunchWork->overtime_multiplier = 1;
	 								$lunchWork->is_locked = 0;
	 								$lunchWork->save();

	 								$work->next_id = $lunchWork->id;
	 								$work->save();
	 								$work->recalculate();	 								

	 								$afterLunchWork = new work_done;
	 								$afterLunchWork->time_worked = 0;//just so it has a value - to be calculated by recalculate
	 								$afterLunchWork->pay_earned = 0;
	 								$afterLunchWork->user_id = $work->user_id;
	 								$afterLunchWork->project_id = $work->project_id;
	 								$afterLunchWork->task_id = $work->task_id;
	 								$afterLunchWork->time_started = $lunchFinishStamp;
	 								$afterLunchWork->time_finished = $timeToFinish;
	 								$afterLunchWork->day_summary_id = $work->day_summary_id;
	 								$afterLunchWork->previous_id = $lunchWork->id;
	 								$afterLunchWork->base_hourly_rate	= $work->base_hourly_rate;//$work->user->lunch_pay_rate;
	 								$afterLunchWork->next_id = $theNext;
	 								$afterLunchWork->first = 0;
	 								$afterLunchWork->last = $isLast;
	 								$afterLunchWork->overtime_multiplier = 1;
	 								$afterLunchWork->is_locked = 0;
	 								$afterLunchWork->save();

	 								$lunchWork->next_id = $afterLunchWork->id;
	 								$lunchWork->save();

	 								$lunchWork->recalculate();
	 								$afterLunchWork->recalculate();


	 								//check for the fringe case of editing the last thing the person actually did
	 								if($work->user->last_work_leger_id == $work->id){	 									 						
	 									$work->user->last_work_leger_id = $afterLunchWork->id;
	 									$work->user->save();
	 								}
	 								



	 							}elseif($work->time_started < $lunchStartStamp && $work->time_finished < $lunchFinishStamp){
	 								//finish of work item falls within lunch but the start does not

	 								//check the next work item extends past the point at which lunch ends 
	 								$nextWork = work_done::find($work->next_id);

	 								if($lunchFinishStamp < $nextWork->time_finished){
	 									//can insert a new record between this and next

		 								$lunchWork = new work_done;
		 								$lunchWork->time_worked = 0; //just so it has a value - to be calculated by recalculate
		 								$lunchWork->pay_earned = 0; //just so it has a value - to be calculated by recalculate
		 								$lunchWork->user_id 			= $work->user_id;
		 								$lunchWork->project_id 			= $lunchProjectId;
		 								$lunchWork->task_id 			= $lunchTaskId;
		 								$lunchWork->time_started 		= $lunchStartStamp;
		 								$lunchWork->time_finished 		= $lunchFinishStamp;
		 								$lunchWork->day_summary_id 		= $work->day_summary_id;
		 								$lunchWork->previous_id 		= $work->id;
		 								$lunchWork->next_id 			= $nextWork->id;
		 								$lunchWork->base_hourly_rate	= 0;//$work->user->lunch_pay_rate;
		 								$lunchWork->first 				= 0;
		 								$lunchWork->last 				= 0;
		 								$lunchWork->overtime_multiplier = 1;
		 								$lunchWork->is_locked 			= 0;
		 								$lunchWork->save();

		 								$work->time_finished 	= $lunchStartStamp;
		 								$work->next_id 			= $lunchWork->id;
		 								$work->save();
		 								$work->recalculate();


		 								$nextWork->time_started = $lunchFinishStamp;
		 								$nextWork->previous_id 	= $lunchWork->id;
		 								$nextWork->save();
		 								$nextWork->recalculate();

	 								}else{
	 									//do nothing as the next work item is encapsulated by the desired lunch period
	 									//Next time through the loop it will trigger the next elseif statement and throw the error
	 								}

	 										


	 							}elseif($lunchStartStamp < $work->time_started && $work->time_finished < $lunchFinishStamp){
	 								//work item itself is encapsulated by the desires lunch period, cannot process so throw an error message to the user 
	 								return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1)->with('error','This work item will be overwritten by adding in a lunch break, please adjust.')->with('problematic_work',$work->id);

	 							}else{
	 								//do nothing as current work item in no way interacts with the lunch period	 							
	 							}

	 						}
	 					}else{
	 						//no actual work done
	 					}
	 					
	 					$day->summarise_day();
	 				}
	 				break;




	 			case 'markAsAmended':
	 				if($request->has('target')){

	 					$day = day_summary::find($request->get('target'));
	 					$day->user_requested_amendment = 0;
	 					$day->comments = $day->comments.'     Amended at '.date('Y-m-d H:i');
	 					$day->save();

	 					return view('inside.dashboard.ajax.daySummaryDetail')->with('day',$day)->with('detail',1);
	 				}
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

	 			case 'updateWorkerSkill':
	 				if($request->has('target') && $request->has('skill') && $request->has('value')){

	 					$user = \App\User::find($request->get('target'));

	 					$skills = $user->skills->pluck('id')->toArray();

	 					if(in_array($request->get('skill'),$skills)){
	 						
	 						//already has skill
	 						if($request->get('value') == 'true'){
	 							//already has skill and should have the skill so do nothing

	 						}else{
	 							//currently has skill but shouldnt so lets delete it
	 							\DB::table('user_skills')->where('user_id','=',$request->get('target'))->where('skill_id','=',$request->get('skill'))->delete();
	 						}

	 					}else{

	 						//not got skill
	 						if($request->get('value') == 'true'){
	 							//Doesnts have skill but should so create it

	 							$skill = new user_skill;
	 							$skill->user_id = $request->get('target');
	 							$skill->skill_id = $request->get('skill');
	 							$skill->bosun_defined = 0;
	 							$skill->save();


	 						}else{
	 							//doenst have the skill and shouldnt have it, so do nothing

	 						}	 						

	 					}

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
