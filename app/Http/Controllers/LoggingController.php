<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;


/*  This function lives here as I've currently got nowhere better to store it - it needs to be moved into its own included file i think  */
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}




class LoggingController extends Controller
{
    public function index($staffID='',$projectID='',$activityID='',Request $request) {

    	return view('outside.logging.index');	

    }
}
