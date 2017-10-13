<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialDatabaseRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            //create initial database records



        /*
        *   Create Superadmin Role in database
        */
       $superAdminRoleId = DB::table('roles')->insertGetId(
            array(
                'name'      =>  'Super Admin',
                'description'   =>  'User category for editing every setting.',
                'can_view_dashboard'=>true,
                'can_change_permissions'=>true,
                'can_edit_workers'=>true,
                'can_add_admins'=>true,
                'can_view_timesheets'=>true,
                'can_view_pay_details'=>true,
                'can_view_payslips'=>true,
                'can_edit_timesheets'=>true,
                'can_view_hours_statistics'=>true,
                'can_view_financial_statistics'=>true,
            )
        );




        /*
        *   Lunch Project ID 
        */
        $lunchProjectId = DB::table('projects')->insertGetId(
                array(
                    'name'=>'Lunch',
                    'is_finished'=>false,
                    'can_book_parts_to'=>false,
                    )
            );
        DB::table('config')->insert(
            array(
                'name'      =>  'lunchProjectId',
                'integer'   =>  $lunchProjectId

            )
        );






        /*
        *   Lunch Skill ID 
        */
        $lunchSkillId = DB::table('skills')->insertGetId(
            array(
                'name'=>'Lunch',
                'description'=>'Non-productive time - to be logged as break',                    
                )
        );    

       DB::table('config')->insert(
            array(
                'name'      =>  'lunchSkillId',
                'integer'   =>  $lunchSkillId

            )
        );



        /*
        *   Lunch Task ID 
        */
        $lunchTaskID = DB::table('tasks')->insertGetId(
            array(
                'name'=>'Lunch',
                'skill_id'=>$lunchSkillId,
                'project_id'=>$lunchProjectId,
                'description'=>'Lunch'
                )
        );  
              
        DB::table('config')->insert(
            array(
                'name'      =>  'lunchTaskID',
                'integer'   =>  $lunchTaskID

            )
        );





        /*
        *   Generic Work Project ID 
        */
        $genericWorkProjectId = DB::table('projects')->insertGetId(
            array(
                'name'=>'Generic Work',
                'is_finished'=>false,
                'can_book_parts_to'=>false,
                )
        );
        DB::table('config')->insert(
            array(
                'name'      =>  'genericWorkProjectId',
                'integer'   =>  $genericWorkProjectId

            )
        );



        /*
        *   Generic Work Skill ID 
        */
        $genericWorkSkillId = DB::table('skills')->insertGetId(
            array(
                'name'=>'Generic Work',
                'description'=>'Generic work on unspecified project and unspecified skill',                    
                )
        );

        DB::table('config')->insert(
            array(
                'name'      =>  'genericWorkSkillId',
                'integer'   =>  $genericWorkSkillId

            )
        );





        /*
        *   Generic Work Task ID 
        */
        $genericWorkTaskId = DB::table('tasks')->insertGetId(
            array(
                'name'=>'Generic Work',
                'skill_id'=>$genericWorkSkillId,
                'project_id'=>$genericWorkProjectId,
                'description'=>'General work task'
            )
        );

        DB::table('config')->insert(
            array(
                'name'      =>  'genericWorkTaskId',
                'integer'   =>  $genericWorkTaskId

            )
        );



   
        $user = new App\User();
        $user->password = Hash::make('TYWcroatia14');
        $user->email = 'matt.v.hartley@gmail.com';
        $user->name = 'Matt Hartley';        
        $user->can_log_hours=false;
        $user->role_id = $superAdminRoleId;      
        $user->is_active = true;
        $user->save();
 

        DB::table('config')->insert(
            array(
                'name'          =>  'location_building',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include a building identifier?',
                'group'         => 'stock'
            )
        );
        DB::table('config')->insert(
            array(
                'name'          =>  'location_isle',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include an isle identifier?',
                'group'         => 'stock'
            )
        );

        DB::table('config')->insert(
            array(
                'name'          =>  'location_side',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include an identifier for the side of an isle?',
                'group'         => 'stock'
            )
        );

        DB::table('config')->insert(
            array(
                'name'          =>  'location_bay',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include a bay identifier?',
                'group'         => 'stock'
            )
        );        
        DB::table('config')->insert(
            array(
                'name'          =>  'location_shelf',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include a shelf identifier?',
                'group'         => 'stock'
            )
        );  
        DB::table('config')->insert(
            array(
                'name'          =>  'location_position',
                'boolean'       =>  1,
                'description'   =>  'Does your location code include an identifier for the position on the shelf?',
                'group'         => 'stock'
            )
        ); 



        DB::table('config')->insert(
            array(
                'name'          =>  'rfid',
                'boolean'       =>  0,
                'description'   =>  'Does you want to use the RFID reader to identify workers?',
                'group'         => 'logging'
            )
        ); 
        DB::table('config')->insert(
            array(
                'name'          =>  'grid',
                'boolean'       =>  1,
                'description'   =>  'Do you want to identify workers by displaying their names in a grid?',
                'group'         => 'logging'
            )
        ); 
        DB::table('config')->insert(
            array(
                'name'          =>  'projects',
                'boolean'       =>  1,
                'description'   =>  'Do you want to use projects?',
                'group'         => 'logging'
            )
        ); 
        DB::table('config')->insert(
            array(
                'name'          =>  'tasks',
                'boolean'       =>  1,
                'description'   =>  'Do you want to use tasks within projects?',
                'group'         => 'logging'
            )
        ); 
        DB::table('config')->insert(
            array(
                'name'          =>  'workers_choose_project',
                'boolean'       =>  1,
                'description'   =>  'Do you want to allow workers to select which project they work on?',
                'group'         => 'logging'
            )
        ); 




        DB::table('config')->insert(
            array(
                'name'          =>  'has_stock_control',
                'boolean'       =>  1,
                'group'         => 'clientConfig'
            )
        ); 
        DB::table('config')->insert(
            array(
                'name'          =>  'has_logging',
                'boolean'       =>  1,
                'group'         => 'clientConfig'
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


    DB::table('projects')->where('name', '=', 'Lunch')->delete();
    DB::table('skills')->where('name', '=', 'Lunch')->delete();
    DB::table('tasks')->where('name', '=', 'Lunch')->delete();

    DB::table('projects')->where('name', '=', 'Generic Work')->delete();
    DB::table('skills')->where('name', '=', 'Generic Work')->delete();
    DB::table('tasks')->where('name', '=', 'Generic Work')->delete();




    DB::table('config')->where('name', '=', 'lunchProjectId')->delete();
    DB::table('config')->where('name', '=', 'lunchSkillId')->delete();
    DB::table('config')->where('name', '=', 'lunchTaskID')->delete();

    DB::table('config')->where('name', '=', 'genericWorkProjectId')->delete();
    DB::table('config')->where('name', '=', 'genericWorkSkillId')->delete();
    DB::table('config')->where('name', '=', 'genericWorkTaskId')->delete();
    }
}
