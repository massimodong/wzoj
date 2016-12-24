<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/','HomeController@index');

// auth
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::post('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/register','Auth\AuthController@oj_getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


Route::get('users/{id}','UserController@getId');
Route::post('users/{id}','UserController@postId')->middleware('auth');

//problemsets
Route::get('s','ProblemsetController@getIndex');
Route::post('s','ProblemsetController@postNewProblemset');
Route::get('s/{psid}','ProblemsetController@getProblemset');
Route::get('s/{psid}/edit','ProblemsetController@getEditProblemset');
Route::put('s/{psid}','ProblemsetController@putProblemset');
Route::delete('s/{psid}','ProblemsetController@deleteProblemset');
	//problems
Route::get('s/{psid}/{pid}','ProblemsetController@getProblem');
Route::post('s/{psid}','ProblemsetController@postProblem');
Route::put('s/{psid}/{pid}','ProblemsetController@putProblem');
Route::delete('s/{psid}/{pid}','ProblemsetController@deleteProblem');
Route::get('s/{psid}/{pid}/submit','ProblemsetController@getSubmit');

Route::resource('solutions','SolutionController');

Route::controller('ajax','AjaxController');
Route::controller('judger','JudgerController');

Route::get('files/{user_id}/{name}','FileController@showfile');
Route::resource('files','FileController');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){
		Route::get('/', 'AdminHomeController@index');
		//groups
		Route::get('groups', 'AdminGroupController@getGroups');
		Route::get('groups/{id}', 'AdminGroupController@getGroups');
		Route::post('groups', 'AdminGroupController@postGroups');
		Route::post('groups/{id}', 'AdminGroupController@postGroups');
		Route::put('groups/{id}', 'AdminGroupController@putGroups');
		Route::delete('groups/{gid}', 'AdminGroupController@deleteGroups');
		Route::delete('groups/{gid}/{uid}', 'AdminGroupController@deleteGroups');

		//invitations
		Route::get('invitations', 'AdminInvitationController@getInvitations');
		Route::get('invitations/{id}', 'AdminInvitationController@getInvitations');
		Route::post('invitations', 'AdminInvitationController@postInvitations');
		Route::post('invitations/{id}', 'AdminInvitationController@postInvitations');
		Route::put('invitations/{id}', 'AdminInvitationController@putInvitations');
		Route::delete('invitations/{iid}/{gid}', 'AdminInvitationController@deleteInvitations');

		//problems
		Route::get('problems', 'AdminProblemController@getProblems');
		Route::get('problems/{id}', 'AdminProblemController@getProblems');
		Route::post('problems', 'AdminProblemController@postProblems');
		Route::put('problems/{id}', 'AdminProblemController@putProblems');
		Route::delete('problems/{id}', 'AdminProblemController@deleteProblems');

		//import problems
		Route::get('import-problems', 'AdminImportProblemsController@getImportProblems');
		Route::post('import-problems', 'AdminImportProblemsController@postImportProblems');
});
