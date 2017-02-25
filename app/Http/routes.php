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
Route::put('users', 'UserController@putUsers')->middleware('admin');
Route::post('users/{id}','UserController@postId')->middleware('auth');

//problemsets
Route::get('s','ProblemsetController@getIndex');
Route::post('s','ProblemsetController@postNewProblemset');
Route::get('s/{psid}','ProblemsetController@getProblemset');
Route::get('s/{psid}/ranklist','ProblemsetController@getRanklist');
Route::get('s/{psid}/edit','ProblemsetController@getEditProblemset')->middleware('auth');
Route::put('s/{psid}','ProblemsetController@putProblemset');
Route::delete('s/{psid}','ProblemsetController@deleteProblemset');
	//problems
Route::get('s/{psid}/{pid}','ProblemsetController@getProblem');
Route::post('s/{psid}/problems','ProblemsetController@postProblem');
Route::put('s/{psid}/problems','ProblemsetController@putProblem');
Route::delete('s/{psid}/problems','ProblemsetController@deleteProblem');
//Route::get('s/{psid}/{pid}/submit','ProblemsetController@getSubmit')->middleware('auth');
	//groups
Route::post('s/{psid}/groups','ProblemsetController@postGroup');
Route::delete('s/{psid}/groups/{gid}','ProblemsetController@deleteGroup');

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
		Route::post('groups/{id}/users', 'AdminGroupController@postUsers');
		Route::put('groups/{id}', 'AdminGroupController@putGroups');
		Route::delete('groups/{gid}', 'AdminGroupController@deleteGroups');
		Route::delete('groups/{gid}/users', 'AdminGroupController@deleteUsers');

		//invitations
		Route::get('invitations', 'AdminInvitationController@getInvitations');
		Route::get('invitations/{id}', 'AdminInvitationController@getInvitations');
		Route::post('invitations', 'AdminInvitationController@postInvitations');
		Route::post('invitations/{id}', 'AdminInvitationController@postInvitations');
		Route::put('invitations/{id}', 'AdminInvitationController@putInvitationsId');
		Route::put('invitations', 'AdminInvitationController@putInvitations');
		Route::delete('invitations/{iid}/{gid}', 'AdminInvitationController@deleteInvitations');

		//problems
		Route::get('problems', 'AdminProblemController@getProblems');
		Route::get('problems/{id}', 'AdminProblemController@getProblems');
		Route::post('problems', 'AdminProblemController@postProblems');
		Route::put('problems/{id}', 'AdminProblemController@putProblemsId');
		Route::put('problems', 'AdminProblemController@putProblems');
		Route::delete('problems/{id}', 'AdminProblemController@deleteProblems');

		//import problems
		Route::get('import-problems', 'AdminImportProblemsController@getImportProblems');
		Route::post('import-problems', 'AdminImportProblemsController@postImportProblems');

		//problem rejudge
		Route::get('problem-rejudge', 'AdminProblemRejudgeController@getProblemRejudge');
		Route::post('problem-rejudge', 'AdminProblemRejudgeController@postProblemRejudge');

		//invitations generate
		Route::get('invitations-generate', 'AdminInvitationsGenerateController@getIndex');
		Route::post('invitations-generate', 'AdminInvitationsGenerateController@postIndex');

		//update system
		Route::get('update-system', 'AdminUpdateSystemController@getUpdate');
		Route::post('update-system', 'AdminUpdateSystemController@postUpdate');
});
