<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoggingInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);


        Schema::create('work_done', function($a) {
            $a->increments('id');
            $a->integer('user_id');
            $a->integer('project_id');
            $a->integer('task_id');
            $a->integer('time_worked');
            $a->integer('time_started');
            $a->integer('time_finished');
            $a->integer('day_summary_id');            
            $a->integer('previous_id');
            $a->integer('next_id')->nullable();
            $a->boolean('first')->nullable();
            $a->boolean('last')->nullable();
            $a->timestamps();
        });


        Schema::create('skills', function($b) {
            $b->increments('id');
            $b->string('name',50);
            $b->string('description',500);           
            $b->timestamps();
        });

        Schema::create('user_skills', function($t) {
            $t->increments('id');
            $t->integer('user_id');
            $t->boolean('skill_id');
            $t->timestamps();

        });

        DB::table('skills')->insert(
                array(
                    'name'=>'Lunch',
                    'description'=>'Non-productive time - to be logged as break',                    
                    )
            );
        DB::table('skills')->insert(
                array(
                    'name'=>'Generic Work',
                    'description'=>'Generic work on unspecified project and unspecified skill',                    
                    )
            );


        Schema::create('payslips', function($d) {
            $d->increments('id');
            $d->string('name',50);
            $d->integer('user_id');
            $d->integer('timestamp');
            $d->integer('payday_id');
            $d->timestamps();
        });

        Schema::create('payroll_runs', function($e) {
            $e->increments('id');
            $e->string('name');
            $e->integer('timestamp');
            $e->timestamps();
        });

        Schema::create('overtime_rates', function($f) {
            $f->increments('id');
            $f->decimal('multiplier',6,5);
            $f->string('to_display',10);
            $f->boolean('is_active');
            $f->timestamps();
        });

        Schema::create('day_summary', function($h) {
            $h->increments('id');
            $h->integer('user_id');
            $h->integer('time_in_stamp');
            $h->integer('time_out_stamp')->nullable();
            $h->integer('time_worked')->nullable();
            $h->integer('time_unproductive')->nullable();
            $h->integer('year');
            $h->integer('week');
            $h->integer('day');
            $h->integer('first_work_done_id')->nullable();
            $h->integer('last_work_done_id')->nullable();
            $h->integer('db_timestamp')->nullable();
            $h->integer('ot_worked')->nullable();
            $h->integer('bonus_time')->nullable();
            $h->integer('schedule_no')->nullable();
            $h->boolean('has_logged_in')->nullable();
            $h->boolean('is_timesheeted')->nullable();
            $h->timestamps();
        });

        Schema::create('day_info', function($i) {
            $i->increments('id');
            $i->integer('ot_rate_id');
            $i->integer('day_id');
            $i->timestamps();
        });        


        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->boolean('can_view_dashboard');
            $table->boolean('can_change_permissions');
            $table->boolean('can_edit_workers');
            $table->boolean('can_add_admins');
            $table->boolean('can_view_timesheets');
            $table->boolean('can_view_pay_details');
            $table->boolean('can_view_payslips');
            $table->boolean('can_edit_timesheets');
            $table->boolean('can_view_hours_statistics');
            $table->boolean('can_view_financial_statistics');
            $table->timestamps();
        });

        Schema::create('userBadges', function (Blueprint $x) {
            $x->increments('id');
            $x->integer('user_id');
            $x->string('badgeID');
            $x->timestamps();
        });
    

        DB::table('tasks')->insert(
            array(
                'name'=>'Lunch',
                'skill_id'=>1,
                'project_id'=>1,
                'description'=>'Lunch'
                )
        );

        DB::table('tasks')->insert(
            array(
                'name'=>'Generic Work',
                'skill_id'=>2,
                'project_id'=>2,
                'description'=>'General work task'
                )
        );

        Schema::table('projects', function (Blueprint $tbl) {
            $tbl->boolean('can_log_hours')->default(1);
            $tbl->integer('default_task')->nullable();  //needs to be nullable as the default task is created after the project is 
        });


        Schema::table('tasks', function (Blueprint $tb) {
            $tb->boolean('task_finished')->default(0);                       
        });

        Schema::table('users', function (Blueprint $table) {
            /* Custom Fields */

            $table->boolean('can_log_hours');

                /* Logging - dynamic fields */
            $table->boolean('logged_in')->nullable();
            $table->boolean('on_lunch')->nullable();
            $table->integer('time_change')->nullable();           
            $table->integer('task_id')->nullable();
            $table->integer('last_work_leger_id')->nullable();
            $table->integer('current_day_summary')->nullable();
            $table->boolean('first_activity_for_day')->nullable();

            /* General User info */
            $table->integer('role_id')->nullable(); 
            $table->string('fname',30)->nullable();
            $table->string('lname',30)->nullable();
            $table->decimal('rate',10,2)->nullable();



            $table->integer('start_hour')->nullable();
            $table->integer('start_min')->nullable();       /* Questions over these fields - maybe they want to be convered in the shift table */ 
            $table->integer('finish_hour')->nullable();
            $table->integer('finish_min')->nullable();
            $table->integer('lunch_start_hour')->nullable();
            $table->integer('lunch_start_min')->nullable();       /* Questions over these fields - maybe they want to be convered in the shift table */ 
            $table->integer('lunch_finish_hour')->nullable();
            $table->integer('lunch_finish_min')->nullable();

            $table->boolean('is_active')->nullable();
            $table->boolean('female')->nullable();
            $table->integer('dob_day')->nullable();
            $table->integer('dob_month')->nullable();
            $table->integer('dob_year')->nullable();
            $table->string('addr_line_one',100)->nullable();
            $table->string('addr_line_two',100)->nullable();
            $table->string('addr_line_three',100)->nullable(); 
            $table->string('postcode',10)->nullable();
            $table->string('ni_num',15)->nullable();
            $table->boolean('contractor')->nullable();
            $table->integer('employment_start_timestamp')->nullable();
            $table->boolean('student_loan')->nullable();
            $table->boolean('finish_studies_before_current_tax_year')->nullable(); 
            $table->string('contact_number',15)->nullable(); 
            $table->string('company_no',15)->nullable();
            $table->string('vat_number',15)->nullable();
            $table->string('ice_fullname',100)->nullable();
            $table->string('ice_contact_no',15)->nullable(); 
            $table->integer('remuneration_scheme_id')->nullable();
            $table->decimal('holiday_entitlement',6,3)->nullable();
            $table->decimal('holiday_taken',6,3)->nullable();
            $table->integer('leave_accrual_timestamp')->nullable();
            $table->integer('shift_type_id')->nullable(); 
            $table->boolean('bank_overtime')->nullable();                       
            $table->text('notes')->nullable(); 
            /* End of custom Fields */
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_done');  
        Schema::dropIfExists('skills');
        Schema::dropIfExists('user_skills');   
        Schema::dropIfExists('userBadges');     
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('overtime_rates');        
        Schema::dropIfExists('day_summary');
        Schema::dropIfExists('day_info');
        Schema::dropIfExists('roles');      

        Schema::table('projects', function (Blueprint $tbl) {
            $tbl->dropColumn('can_log_hours');
            $tbl->integer('default_task');
        });


        Schema::table('tasks', function (Blueprint $tb) {
            //$tb->dropColumn('task_finished');                       
        });


        Schema::table('users', function (Blueprint $table) {
            /* Custom Fields */

            $table->dropColumn('can_log_hours');
                /* Logging - dynamic fields */
            $table->dropColumn('logged_in');
            $table->dropColumn('on_lunch');
            $table->dropColumn('time_change');            
            $table->dropColumn('task_id');
            $table->dropColumn('last_work_leger_id');
            $table->dropColumn('current_day_summary');
            $table->dropColumn('first_activity_for_day');

            /* General User info */
            $table->dropColumn('role_id'); 
            $table->dropColumn('fname');
            $table->dropColumn('lname');
            $table->dropColumn('rate');



            $table->dropColumn('start_hour');
            $table->dropColumn('start_min');       /* Questions over these fields - maybe they want to be convered in the shift table */ 
            $table->dropColumn('finish_hour');
            $table->dropColumn('finish_min');
            $table->dropColumn('lunch_start_hour');
            $table->dropColumn('lunch_start_min');       /* Questions over these fields - maybe they want to be convered in the shift table */ 
            $table->dropColumn('lunch_finish_hour');
            $table->dropColumn('lunch_finish_min');

            $table->dropColumn('is_active');
            $table->dropColumn('female');
            $table->dropColumn('dob_day');
            $table->dropColumn('dob_month');
            $table->dropColumn('dob_year');
            $table->dropColumn('addr_line_one');
            $table->dropColumn('addr_line_two');
            $table->dropColumn('addr_line_three'); 
            $table->dropColumn('postcode');
            $table->dropColumn('ni_num');
            $table->dropColumn('contractor');
            $table->dropColumn('employment_start_timestamp');
            $table->dropColumn('student_loan');
            $table->dropColumn('finish_studies_before_current_tax_year'); 
            $table->dropColumn('contact_number'); 
            $table->dropColumn('company_no');
            $table->dropColumn('vat_number');
            $table->dropColumn('ice_fullname');
            $table->dropColumn('ice_contact_no'); 
            $table->dropColumn('remuneration_scheme_id');
            $table->dropColumn('holiday_entitlement');
            $table->dropColumn('holiday_taken');
            $table->dropColumn('leave_accrual_timestamp');
            $table->dropColumn('shift_type_id'); 
            $table->dropColumn('bank_overtime');                       
            $table->dropColumn('notes'); 
            /* End of custom Fields */
        });
    }
}
