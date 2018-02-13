<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\work_done;
use App\day_summary;
use App\config;


class User extends Authenticatable
{
    use Notifiable;

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'password'
    ];



/*
,'role_id','badgeID','fname','lname','rate','status','time_change','project_id ','task_id',

        'start_hour','start_min','finish_hour','finish_min',

        'is_active','female','dob_day','dob_month','dob_year','addr_line_one','addr_line_two','addr_line_three','postcode',
        'ni_num','contractor','employment_start_timestamp','student_loan','finish_studies_before_current_tax_year','contact_number',
        'company_no','vat_number','ice_fullname','ice_contact_no','remuneration_scheme_id','holiday_entitlement','holiday_taken','leave_accrual_timestamp',
        'shift_type_id','bank_overtime','notes',
*/



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
  


    public function cssStatus() {

        if($this->on_lunch == 1){

            return 'userOnLunch';

        }elseif($this->logged_in == 1){

            return 'userWorking';

        }else{

            return 'userLoggedOff';

        }       
    }


    public function task(){
        return $this->belongsTo('App\tasks','task_id');
    }

    public function workDone(){
        return $this->hasMany('App\work_done','user_id');
    }

    public function skills(){
        return $this->belongsToMany('App\skill','user_skills','user_id','skill_id');
    }


    public function role(){
        return $this->belongsTo('App\role','role_id');
    }


    public function badges(){
        return $this->hasMany('App\userBadge','user_id');
    }

   

    public function may($responsibility){
        if($this->role->$responsibility==1){
            return true;        
        }else{
            return false;

        }
    }


    public function todaysDaySummary(){
        return $this->belongsTo('App\day_summary','day_summary_id');
    }

    public function change_activity($logged_in,$on_lunch,$task){


        $config = new config;
        
        if( $this->logged_in != $logged_in || $this->on_lunch != $on_lunch || $this->task_id != $task){

            $now=time();
            if($this->logged_in){

                if($now > ($this->time_change + $config->integer('min_work_done_time'))){

                    //have an activity already being carried out and as such need to record work done
                    if($this->on_lunch){
                        //currently on lunch so let's record the task accordingly
                        $old_task=1;    //1 is the default task ID for lunch               
                    }else{
                        $old_task =$this->task_id;
                    }
                    

                    $previous=work_done::find($this->last_work_leger_id);


                    //this creates a new work done entry for the activity that has just finished
                    $w=new work_done;

                    $previousTask = tasks::find($old_task);

                    $w->user_id=$this->id;

                    $w->task_id=$old_task;

                    $w->project_id = $previousTask->project->id;

                    $w->time_worked=($now-($this->time_change));

                    $w->pay_earned = round($w->time_worked * ($this->rate / 3600),8);

                    $w->time_started=$this->time_change;

                    $w->base_hourly_rate = $this->rate;

                    $w->time_finished=$now;

                    $w->day_summary_id=$this->current_day_summary;

                    $w->previous_id=$this->last_work_leger_id;

                    $w->first=$this->first_activity_for_day;



                    if($logged_in){

                        //we're still logged in so set the last parameter to zero
                        $w->last=0;

                    }else{

                        //we've logged out so set the last paramater to true and summarise the day
                        $w->last=1;

                        $summary=day_summary::find($this->current_day_summary);
                        $summary->time_out_stamp=$now;
                        $summary->save();
                        $summary->summarise_day();         

                    }

                    $w->save();
                    $w_id=$w->id;
                    

                    //this updates the worker record with the information about the new status
                    $this->last_work_leger_id=$w_id;

                    if($this->first_activity_for_day){
                        //reset the first activity for day indicator as this is no longer true
                        $this->first_activity_for_day=0;
                    }

                    $this->logged_in=$logged_in;
                    $this->on_lunch=$on_lunch;
                    $this->task_id=$task;
                    $this->time_change=$now;
                    $this->save();


                    if($previous){
                        $previous->next_id=$w_id;  
                        $previous->save(); 
                    }

                }else{   //end of check if minimum time period has been reached to record work done

                    //min time period for work done has not been met, so don't record work done, just change the user's task etc as logging was likely a mistake
                    $this->logged_in=$logged_in;
                    $this->on_lunch=$on_lunch;
                    $this->task_id=$task;
                    $this->save();      //importantly, not changing the time_changed parameter

                }


            }else{

                //was logged off so no work to be recoreded
                    //this is now a log-on event, this is where the day summary entry is created for the worker
                        //this is the start of a work blockchain


                $day_summary=day_summary::where('user_id','=',$this->id)
                    ->where('year','=',date("Y"))
                    ->where('week','=',date("W"))
                    ->where('day','=',date("N"))
                    ->first();




                
                $this->logged_in=1;
                $this->on_lunch=$on_lunch;
                $this->first_activity_for_day=1;
                $this->task_id=$task;
                //saving later - may need to change the value of first activity for day


                if(!empty($day_summary)){   

                    //we have re-logged in so let's remove the "last" parameter from the last work done entry and continue from there
                    $change_old=work_done::find($this->last_work_leger_id);

                    if($change_old){

                        $change_old->last=0;
                         

                        $w=new work_done;

                        $w->user_id=$this->id;

                        $w->task_id=1;
                        $w->project_id = 1;

                        $w->time_worked=($now-($this->time_change));

                        $w->pay_earned = round($w->time_worked * ($this->rate / 3600),8);                    

                        $w->time_started=$this->time_change;

                        $w->time_finished=$now;

                        $w->day_summary_id=$day_summary->id;

                        $w->previous_id=$this->last_work_leger_id;

                        $w->base_hourly_rate = $this->rate;

                        $w->first=0;

                        $w->last=0;

                        $w->save();

                        $w_id=$w->id;

                        $change_old->next_id=$w_id;  

                        $this->last_work_leger_id = $w_id;
                        
                        $change_old->save();                 

                    }
                    $this->first_activity_for_day=0;
                    $sum_id=$day_summary->id;



                    //need to create a work done entry for this event to ensure continuity - set it as lunch

                }else{

                    $day_summary= new day_summary;
                    $day_summary->user_id=$this->id;
                    $day_summary->year=date('Y');
                    $day_summary->week=date('W');
                    $day_summary->day=date('N');
                    $day_summary->time_in_stamp = $now;
                    $day_summary->db_timestamp = $now;
                    $day_summary->save();
                    $sum_id=$day_summary->id;

                }


                //code to prevent logging on before start hour

                $startTimestamp = strtotime(date('Y-m-d'))+ (3600 * $this->start_hour) + (60 * $this->start_min);
                if($now < $startTimestamp){
                    $this->time_change=$startTimestamp;
                }else{

                    //logged in late - on a 15 min ratchet so lets sort out the logic for the start time                    
                    $lateness = $now - $startTimestamp;
                    $NumQuarterOfHoursLate = ceil($lateness/(15 * 60));
                    $penaltyStartTime = $startTimestamp + (15 * 60 * $NumQuarterOfHoursLate);
                    $this->time_change = $penaltyStartTime;
                }   

                
                $this->current_day_summary=$sum_id;

                $this->save();
                
            }
        }
    }

    public function hoursWorkedThisMonth(){
        $totalSeconds = $this->getHoursWorkedThisMonth();
        $hours = floor($totalSeconds/3600);
        $mins = floor(($totalSeconds%3600)/60);        
        echo $hours.'hrs '.$mins.' mins';
    }

    public function getHoursWorkedThisMonth(){
        $unattributedTime = day_summary::where('user_id','=',$this->id)->where('is_timesheeted','=',0)->get();
        $totalTime=0;
        foreach($unattributedTime as $timeToSum){
            $totalTime = $totalTime + $timeToSum->time_worked;
        }
        return $totalTime;
    }

    public function hoursOvertimeThisMonth(){
        $totalSeconds = $this->getHoursOvertimeThisMonth();
        $hours = floor($totalSeconds/3600);
        $mins = floor(($totalSeconds%3600)/60);        
        echo $hours.'hrs '.$mins.' mins';
    }

    public function getHoursOvertimeThisMonth(){
        $unattributedTime = day_summary::where('user_id','=',$this->id)->where('is_timesheeted','=',0)->get();
        $totalTime=0;
        foreach($unattributedTime as $timeToSum){
            $totalTime = $totalTime + $timeToSum->ot_worked;
        }
        return $totalTime;
    }



    public function getMoneyEarnedThisMonth(){
        $unattributedTime = day_summary::where('user_id','=',$this->id)->where('is_timesheeted','=',0)->get();
        $wages = 0;
        $overtime_suppliment = 0;

        foreach($unattributedTime as $time){

            //calculate regular hours
            if(count($time->work_done)>0){
                foreach($time->work_done as $wd){
                    $wages = $wages + ($wd->time_worked * $wd->base_pay_rate);
                }
            }            
            

            //find overtime and calculate if needed
            if(count($time->overtime_recorded)>0){
                foreach($time->overtime as $ot){
                    $overtime_suppliment = $overtime_suppliment  + ($ot->time * $ot->suppliment_multiplier * $ot->base_pay_rate );
                }
            }

            $earnings = $wages + $overtime_suppliment;

            return $earnings;

        }
    }


    public function moneyEarnedThisMonth(){
        echo '&pound;'.round($this->getMoneyEarnedThisMonth(),2);
    }



    public function forceLogOff(){   
    $ToAmend = day_summary::find($this->current_day_summary);    

        if($ToAmend){

            $ToAmend->comments ='User did not log off today, the system has automatically logged them off.';
            $ToAmend->user_requested_amendment =1;
            $ToAmend->save();

            $this->change_activity(0,0,1);
        }
    }
}
