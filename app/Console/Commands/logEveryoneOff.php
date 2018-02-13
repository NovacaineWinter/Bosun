<?php

use App\User;

namespace App\Console\Commands;

use Illuminate\Console\Command;



class logEveryoneOff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logEveryoneOff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures all users are logged off. If a user is not, it logs them off cleanly and sets their hours as needing admin attention.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $usersStillLoggedOn = \App\User::where('logged_in','=',1)->get();


        if($usersStillLoggedOn->count() > 0){

            foreach($usersStillLoggedOn as $user){

                $user->forceLogOff();

            }


        }
    }
}
