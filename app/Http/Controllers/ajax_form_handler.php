<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ajax_form_handler extends Controller
{
 	public function index(Request $request) {
 		if($request->has('action')){
 			switch($request->input('action')){


 				case 'user-detail':
 					if ($request->has('user_id')) {
 						//update existing user
 						$user_for_form=App\User::find($request->input('user_id'));

 					}else{
 						//create new user
 						$user_for_form= new User();
 					}

 					//Got the user - now fill in the details from the request
 					$user_for_form->fill($request->all());
 				break;


 				
 			}
 		}
 	}
}
