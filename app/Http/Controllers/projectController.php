<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\project;
use App\tasks;


class projectController extends Controller
{
	public function listProjects(Request $request){
		return view('inside.projects.list_projects')->with('request',$request);
	}

	public function seedProjects(){
		
		$projects = project::where('is_boat','=',1)->get();


		$tasks = [];

		$tasks[]=[
			'Name'	=>	'First Stage Fitout',
			'skill'	=>	3,
			'desc'	=>	'Everything up to and including sprayfoam. Cleaning shell, bitumen, battens, floors, ballast and cutting back spray foam',
		];

		$tasks[]=[
			'Name'	=>	'Exterior Paintwork',
			'skill'	=>	4,
			'desc'	=>	'Prep and Paint',
		];


		$tasks[]=[
			'Name'	=>	'Interior Paint and Varnish',
			'skill'	=>	5,
			'desc'	=>	'Both furniture and boards',
		];

		$tasks[]=[
			'Name'	=>	'Electrics',
			'skill'	=>	6,
			'desc'	=>	'All electrical Systems',
		];

		$tasks[]=[
			'Name'	=>	'Water, Heating and Gas',
			'skill'	=>	7,
			'desc'	=>	'All Plumbing',
		];

		$tasks[]=[
			'Name'	=>	'Engine and Mechanical Systems',
			'skill'	=>	8,
			'desc'	=>	'All systems',
		];

		$tasks[]=[
			'Name'	=>	'Second Stage Fitout',
			'skill'	=>	9,
			'desc'	=>	'Fitting panels and bulkheads',
		];

		$tasks[]=[
			'Name'	=>	'Final Fitout',
			'skill'	=>	10,
			'desc'	=>	'Fitting furniture and trimming',
		];

		$tasks[]=[
			'Name'	=>	'Boat Setup',
			'skill'	=>	11,
			'desc'	=>	'Moving and leveling the boat',
		];

		$tasks[]=[
			'Name'	=>	'Exterior Fittings',
			'skill'	=>	12,
			'desc'	=>	'Fender eyes, windows, bollards etc',
		];

		$tasks[]=[
			'Name'	=>	'Warranty Work',
			'skill'	=>	13,
			'desc'	=>	'any rework required',
		];

		foreach($projects as $project){
			
			$defaultTask = new tasks;
			$defaultTask->name = 'Default Task';
			$defaultTask->skill_id = 2;
			$defaultTask->project_id = $project->id;
			$defaultTask->description = 'Default task for this project';
			$defaultTask->save();
			$project->default_task = $defaultTask->id;

			foreach($tasks as $task){
				$loopTask = new tasks;
				$loopTask->name = $task['Name'];
				$loopTask->skill_id = $task['skill'];
				$loopTask->description = $task['desc'];
				$loopTask->project_id = $project->id;
				$loopTask->save();
			}

			$project->is_boat = 0;
			$project->save();
		}
	}
}
