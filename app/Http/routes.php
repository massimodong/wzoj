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

Route::get('forum/ajax-get-topics', 'ForumController@getAjaxTopics');

Route::group(['middleware' => ['encrypt_cookies', 'cookie', 'session', 'session_errors', 'csrf', 'antibot','contest']], function(){

		Route::get('/','HomeController@index');
		Route::get('faq', 'HomeController@faq');
		Route::get('ranklist', 'HomeController@ranklist');
		Route::get('sorry', 'HomeController@getSorry');
		Route::post('sorry', 'HomeController@postSorry');

		// auth
		Route::get('auth/login', 'Auth\AuthController@getLogin');
		Route::post('auth/login', 'Auth\AuthController@postLogin');

		Route::get('auth/logout', 'Auth\AuthController@getLogout');
		Route::post('auth/logout', 'Auth\AuthController@postLogout');

		Route::get('auth/register','Auth\AuthController@oj_getRegister');
		Route::post('auth/register', 'Auth\AuthController@postRegister');

		Route::get('password/change', 'Auth\PasswordController@getChangePassword');
		Route::post('password/change', 'Auth\PasswordController@postChangePassword');

		// Password reset link request routes...
		Route::get('password/email', 'Auth\PasswordController@getEmail');
		Route::post('password/email', 'Auth\PasswordController@postEmailWithCaptcha');

		// Password reset routes...
		Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
		Route::post('password/reset', 'Auth\PasswordController@postReset');


		Route::get('users/{id}','UserController@getId');
		Route::put('users', 'UserController@putUsers')->middleware('admin');
		Route::post('users/{id}','UserController@postId')->middleware('auth');

		//problemsets
		Route::get('s','ProblemsetController@getIndex');
		Route::post('s','ProblemsetController@postNewProblemset');
		Route::get('s/{psid}','ProblemsetController@getProblemset');
		Route::get('s/{psid}/ranklist','ProblemsetController@getRanklist');
		Route::get('s/{psid}/ranklist_csv','ProblemsetController@getRanklistCSV')->middleware('admin');
		Route::get('s/{psid}/edit','ProblemsetController@getEditProblemset')->middleware('auth');
		Route::put('s/{psid}','ProblemsetController@putProblemset');

		Route::get('contests','ProblemsetController@getContestsIndex');
		//problems
		Route::get('s/{psid}/{pid}','ProblemsetController@getProblem');
		Route::post('s/{psid}/problems','ProblemsetController@postProblem');
		Route::put('s/{psid}/problems','ProblemsetController@putProblem');
		Route::delete('s/{psid}/problems','ProblemsetController@deleteProblem');
		//Route::get('s/{psid}/{pid}/submit','ProblemsetController@getSubmit')->middleware('auth');
		//groups
		Route::post('s/{psid}/groups','ProblemsetController@postGroup')->middleware('admin');
		Route::delete('s/{psid}/groups/{gid}','ProblemsetController@deleteGroup')->middleware('admin');

		Route::post('solutions/answerfile', 'SolutionController@postSubmitAnswerfile');
		Route::resource('solutions','SolutionController');

		Route::get('files/{user_id}/{name}','FileController@showfile');
		Route::resource('files','FileController');

		Route::get('problem-search', 'HomeController@problemSearch')->middleware('auth');

		Route::get('source-compare', 'HomeController@sourceCompare')->middleware('admin');

		//forum
		Route::get('forum', 'ForumController@getIndex');
		Route::post('forum', 'ForumController@postIndex');
		Route::get('forum/create', 'ForumController@getCreate')->middleware('auth');
		Route::get('forum/{id}', 'ForumController@getTopic');
		Route::put('forum/{id}', 'ForumController@putTopic')->middleware('auth');
		Route::delete('forum/{id}', 'ForumController@deleteTopic')->middleware('auth');

		Route::put('forum/replies/{id}', 'ForumController@putReply')->middleware('auth');
		Route::post('forum/{id}', 'ForumController@postReply')->middleware('auth');
		Route::delete('forum/replies/{id}', 'ForumController@deleteReply')->middleware('auth');

		Route::post('forum/{id}/tags', 'ForumController@postTag')->middleware('auth');
		Route::delete('forum/tags/{id}', 'ForumController@deleteTag')->middleware('auth');
		//end forum

		get('_captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
		Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function(){
				Route::get('/', 'AdminHomeController@index');
				Route::post('options','AdminHomeController@postOptions');
				//notice
				Route::get('notice', 'AdminNoticeController@getNotice');
				//groups
				Route::get('groups', 'AdminGroupController@getGroups');
				Route::get('groups/{id}', 'AdminGroupController@getGroups');
				Route::post('groups', 'AdminGroupController@postGroups');
				Route::post('groups/{id}/users', 'AdminGroupController@postUsers');
				Route::put('groups/{id}', 'AdminGroupController@putGroups');
				Route::delete('groups/{gid}', 'AdminGroupController@deleteGroups');
				Route::delete('groups/{gid}/users', 'AdminGroupController@deleteUsers');
				Route::post('groups/{gid}/homeworks', 'AdminGroupController@postHomeworks');
				Route::delete('groups/{gid}/homeworks', 'AdminGroupController@deleteHomeworks');

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

				//ProblemTags
				Route::get('problem-tags', 'AdminProblemTagController@index');
				Route::post('problem-tags', 'AdminProblemTagController@store');
				Route::put('problem-tags/{id}', 'AdminProblemTagController@update');

				//problem rejudge
				Route::get('problem-rejudge', 'AdminProblemRejudgeController@getProblemRejudge');
				Route::post('problem-rejudge', 'AdminProblemRejudgeController@postProblemRejudge');

				//invitations generate
				Route::get('invitations-generate', 'AdminInvitationsGenerateController@getIndex');
				Route::post('invitations-generate', 'AdminInvitationsGenerateController@postIndex');

				//update system
				Route::get('update-system', 'AdminUpdateSystemController@getUpdate');
				Route::post('update-system', 'AdminUpdateSystemController@postUpdate');

				//advanced settings
				Route::get('advanced-settings', 'AdminAdvanced@getAdvanced');
				Route::post('advanced-settings', 'AdminAdvanced@postAdvanced');

				//ajax
				Route::controller('ajax', 'AdminAjaxController');
		});

});

Route::controller('judger','JudgerController');
Route::controller('ajax','AjaxController');
