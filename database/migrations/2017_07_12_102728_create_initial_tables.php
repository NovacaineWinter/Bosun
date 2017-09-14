<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
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
            $a->integer('worker_id');
            $a->integer('project_id');
            $a->integer('task_id');
            $a->integer('time_worked');
            $a->integer('time_started');
            $a->integer('time_finished');
            $a->integer('day_summary_id');
            //$a->boolean('is_summaried');  //not sure we need this now as a day is summarised on logout
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
            $b->integer('category_id');
            $b->timestamps();
        });

        Schema::create('workers_skills', function($t) {
            $t->increments('id');
            $t->integer('worker_id');
            $t->boolean('skill_id');
            $t->timestamps();

        });

        DB::table('skills')->insert(
                array(
                    'name'=>'Lunch',
                    'description'=>'Non-productive time - to be logged as break',
                    'category_id'=>1,
                    )
            );
        DB::table('skills')->insert(
                array(
                    'name'=>'Generic Work',
                    'description'=>'Generic work on unspecified project and unspecified skill',
                    'category_id'=>2,
                    )
            );

        Schema::create('skill_categories', function($c) {
            $c->increments('id');
            $c->string('description',500);
            $c->timestamps();
        });

        DB::table('skill_categories')->insert(
                array(
                    'description'=>'Unproductive'
                    )
            );
        DB::table('skill_categories')->insert(
                array(
                    'description'=>'Default Skills'
                    )
            );

        Schema::create('schedules', function($d) {
            $d->increments('id');
            $d->string('name',50);
            $d->integer('worker_id');
            $d->integer('timestamp');
            $d->integer('payday_id');
            $d->timestamps();
        });

        Schema::create('paydays', function($e) {
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

        Schema::create('errors', function($g) {
            $g->increments('id');
            $g->string('file',500);
            $g->string('function',100);
            $g->string('line',500);
            $g->string('description',5000);
            $g->string('user_msg',500);
            $g->integer('time');
            $g->integer('user_id');
            $g->timestamps();
        });

        Schema::create('day_summary', function($h) {
            $h->increments('id');
            $h->integer('worker_id');
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

        Schema::create('projects', function($j) {
            $j->increments('id');
            $j->string('name',50);
            $j->boolean('is_finished');
            $j->boolean('can_book_parts_to');
            $j->timestamps();
        });

        DB::table('projects')->insert(
                array(
                    'name'=>'Lunch',
                    'is_finished'=>0,
                    'can_book_parts_to'=>0
                    )
            );
        DB::table('projects')->insert(
                array(
                    'name'=>'Generic Work',
                    'is_finished'=>0,
                    'can_book_parts_to'=>0
                    )
            );

        DB::table('projects')->insert(
                array(
                    'name'=>'General Sales',
                    'is_finished'=>0,
                    'can_book_parts_to'=>1
                    )
            );



        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email',190)->unique();
            $table->string('password');
            $table->string('name');


            /* Custom Fields */

                /* Logging - dynamic fields */
            $table->boolean('logged_in')->nullable();
            $table->boolean('on_lunch')->nullable();
            $table->integer('time_change')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('task_id')->nullable();
            $table->integer('last_work_leger_id')->nullable();
            $table->integer('current_day_summary')->nullable();
            $table->boolean('first_activity_for_day')->nullable();

            /* General User info */
            $table->integer('role_id')->nullable(); 
            $table->string('badgeID',40)->nullable(); 
            $table->string('badgeID',40)->unique()->change();
            $table->string('badgeID',40)->default(NULL)->change();
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

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email',190)->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        Schema::create('remuneration_schemes', function($t) {
            //to be extended later on - the idea is to allow 4-weekly, monthly or even weekly pay schemes across the same organisation
            $t->increments('id');
            $t->string('name');
            $t->timestamps();
        });

        Schema::create('shifts', function($t) {
            //earlies / afters / nights etc  - baserate multiplier for a given shift pattern
            $t->increments('id');
            $t->string('name');
            $t->decimal('multiplier');
            $t->string('text_for_multiplier');
            $t->timestamps();
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

        

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('skill_id');
            $table->integer('project_id');
            $table->string('description')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('workers_skills');
        Schema::dropIfExists('skill_categories');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('paydays');
        Schema::dropIfExists('overtime_rates');
        Schema::dropIfExists('errors');
        Schema::dropIfExists('day_summary');
        Schema::dropIfExists('day_info');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');  
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('remuneration_schemes');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('tasks');
    }
}
