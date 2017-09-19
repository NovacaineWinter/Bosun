<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\day_sumamry;
use App\work_done;
use App\day_summamry;

class User extends Authenticatable
{
    use Notifiable;

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'password','role_id','badgeID','fname','lname','rate','status','time_change','project_id ','task_id',

        /**/'start_hour','start_min','finish_hour','finish_min',/**/

        'is_active','female','dob_day','dob_month','dob_year','addr_line_one','addr_line_two','addr_line_three','postcode',
        'ni_num','contractor','employment_start_timestamp','student_loan','finish_studies_before_current_tax_year','contact_number',
        'company_no','vat_number','ice_fullname','ice_contact_no','remuneration_scheme_id','holiday_entitlement','holiday_taken','leave_accrual_timestamp',
        'shift_type_id','bank_overtime','notes',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function role(){
        return $this->belongsTo('App\role','role_id');
    }


   

    public function may($responsibility){
        if($this->role->first()->$responsibility){
            return true;        
        }else{
            return false;

        }
    }

    public function change_activity($logged_in,$on_lunch,$project,$task){      
        if( $this->logged_in != $logged_in || $this->on_lunch != $on_lunch || $this->project_id !=$project || $this->task_id != $task){
            $now=time();
            if($this->logged_in){
                //have an activity already being carried out and as such need to record work done
                if($this->on_lunch){
                    //currently on lunch so let's record the task accordingly
                    $old_task=1;    //1 is the default task ID for lunch
                    $old_project=1; //1 is the default project for lunch
                }else{
                    $old_task =$this->task_id;
                    $old_project=$this->project_id;
                }
                

                $previous=work_done::find($this->last_work_leger_id);


                //this creates a new work done entry for the activity that has just finished
                $w=new work_done;
                $w->worker_id=$this->id;
                $w->project_id=$old_project;
                $w->task_id=$old_task;
                $w->time_worked=($now-($this->time_change));
                $w->time_started=$this->time_change;
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
                $this->project_id=$project;
                $this->task_id=$task;
                $this->time_change=$now;
                $this->save();


                if($previous){
                    $previous->next_id=$w_id;   
                }
                


            }else{
                //was logged off so no work to be recoreded
                    //this is now a log-on event, this is where the day summary entry is created for the worker
                        //this is the start of a work blockchain
                $day_summary=day_summary::where('worker_id','=',$this->id)
                    ->where('year','=',date("Y"))
                    ->where('week','=',date("W"))
                    ->where('day','=',date("d"))
                    ->first();

                $this->time_change=$now;
                $this->logged_in=1;
                $this->on_lunch=$on_lunch;
                $this->project_id=$project;
                $this->first_activity_for_day=1;
                $this->task_id=$task;
                //saving later - may need to change the value of first activity for day


                if(!empty($day_summary)){                
                    //we have re-logged in so let's remove the "last" parameter from the last work done entry and continue from there
                    $change_old=work_done::find($this->last_work_leger_id);
                    if($change_old){
                        $change_old->last=0;
                        $change_old->save();                    
                    }
                    $this->first_activity_for_day=0;
                    $sum_id=$day_summary->id;
                }else{

                    $day_summary= new day_summary;
                    $day_summary->worker_id=$this->id;
                    $day_summary->year=date('Y');
                    $day_summary->week=date('W');
                    $day_summary->day=date('d');
                    $day_summary->time_in_stamp=$now;
                    $day_summary->save();
                    $sum_id=$day_summary->id;

                }

                $this->current_day_summary=$sum_id;

                $this->save();
                
            }
        }
    }


}
