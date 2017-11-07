<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$stock_locations=array(
	'building'	=>1,
	'isle'		=>0,
	'side'		=>0,
	'bay'		=>1,
	'shelf'		=>1,
	'position'	=>0,
	);

define('CONFIG',array(
	'rfid'=>1,
	'projects'=>0,
	'tasks'=>1,
	'workers_choose_project'=>1,
	'stock_locations'=>$stock_locations
	));

define('TERMINAL_IDS_ARRAY',array(123));

Route::get('/',['middleware'=>'terminal', function () {
    return response()->view('welcome');
}]);



/*  Routes set up specifically for Logging  */
Route::get('logging', ['uses' => 'LoggingController@index']);

Route::get('logging/ajax',['uses' => 'LoggingController@ajax']);

Route::get('ajax',['uses' => 'DashboardController@ajax']);

Route::get('dashboard',['uses' =>'DashboardController@index'])->middleware('auth');



/* 
*
*	Routes specifically for stock control
*
*/

Route::get('stock/supplier/search',['uses'=>'stockController@searchSupplier']);
Route::get('stock',['uses'=>'stockController@stockHome']);
Route::get('stock/search',['uses'=>'stockController@searchStock']);
Route::get('stock/itemdetail',['uses'=>'stockController@stockItemDetail']);
Route::get('stock/update',['uses'=>'stockController@updateStockItem']);
Route::get('stock/modal',['uses'=>'stockController@modals']);
Route::get('stock/insert-select-menu',['uses'=>'stockController@generateSelects']);
Route::get('stock/booked-out-stock',['uses'=>'stockController@bookedOutStock']);
Route::get('stock/value',['uses'=>'stockController@stockValue']);

Route::get('stock/stockcheck',['uses'=>'stockController@stockCheck']);
Route::get('stock/check',['uses'=>'stockController@stockCheckHome']);
Route::get('stock/update',['uses'=>'stockController@updateForStockCheck']);



Route::get('projects',['uses'=>'projectController@listProjects']);

/*
Route::get('bugfix',['uses'=>'stockController@bugfix']);
Route::get('reprice',['uses'=>'stockController@repriceBookedOutStock']);
*/

/* Routes for authenticating users - Laravel defined ones */
/**/
/**/	Auth::routes();
/**/
/**/	Route::get('/home', 'HomeController@index')->name('home');
/**/
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


