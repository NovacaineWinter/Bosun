<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class projectController extends Controller
{
public function listProjects(Request $request){
	return view('inside.projects.list_projects')->with('request',$request);
}
}
