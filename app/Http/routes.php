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

		//users
		Route::get('users/{id}','UserController@getId');
		Route::put('users', 'UserController@putUsers')->middleware('admin');
		Route::post('users/{id}','UserController@postId')->middleware('auth');

		//user files
		Route::get('users/{id}/files', 'UserController@getUserFiles')->middleware('auth');
		Route::post('users/{id}/files', 'UserController@postUserFiles')->middleware('auth');

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
		Route::post('s/{psid}/groups','ProblemsetController@postGroup');
		Route::delete('s/{psid}/groups/{gid}','ProblemsetController@deleteGroup');

		Route::post('solutions/answerfile', 'SolutionController@postSubmitAnswerfile');
		Route::resource('solutions','SolutionController');

		Route::get('files/{user_id}/{name}','FileController@showfile');
		Route::resource('files','FileController');

		Route::get('problem-search', 'HomeController@problemSearch')->middleware('auth');

		Route::get('source-compare', 'HomeController@sourceCompare')->middleware('admin');

		//forum
		Route::group(['prefix' => 'forum', 'middleware' => 'forum'], function(){
			Route::get('/', 'ForumController@getIndex');
			Route::post('/', 'ForumController@postIndex');
			Route::get('create', 'ForumController@getCreate')->middleware('auth');
			Route::get('{id}', 'ForumController@getTopic');
			Route::put('{id}', 'ForumController@putTopic')->middleware('auth');
			Route::delete('{id}', 'ForumController@deleteTopic')->middleware('auth');

			Route::put('replies/{id}', 'ForumController@putReply')->middleware('auth');
			Route::post('{id}', 'ForumController@postReply')->middleware('auth');
			Route::delete('replies/{id}', 'ForumController@deleteReply')->middleware('auth');

			Route::post('{id}/tags', 'ForumController@postTag')->middleware('auth');
			Route::delete('tags/{id}', 'ForumController@deleteTag')->middleware('auth');
		});

		get('_captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
		Route::group(['prefix' => 'admin', 'middleware' => 'role:manager'], function(){
				Route::get('/', 'AdminHomeController@index');
				Route::post('cache-clear', 'AdminHomeController@flushCache');
				Route::post('options','AdminHomeController@postOptions');
				//notice
				Route::get('notice', 'AdminNoticeController@getNotice')->middleware('role:admin');
				//appearance
				Route::group(['prefix' => 'appearance', 'middleware' => 'role:admin'], function(){
					Route::get('/', 'AdminAppearanceController@getAppearance');
					Route::post('sidebars', 'AdminAppearanceController@postSidebar');
					Route::put('sidebars/{id}', 'AdminAppearanceController@putSidebar');
					Route::delete('sidebars/{id}', 'AdminAppearanceController@deleteSidebar');

					Route::post('diy-pages', 'AdminAppearanceController@postDiyPages');
					Route::get('diy-pages/{id}', 'AdminAppearanceController@getDiyPages');
					Route::put('diy-pages/{id}', 'AdminAppearanceController@putDiyPages');
				});
				//groups
				Route::group(['prefix' => 'groups', 'middleware' => 'role:group_manager'], function(){
					Route::get('/', 'AdminGroupController@getGroups');
					Route::get('{id}', 'AdminGroupController@getGroups');
					Route::post('/', 'AdminGroupController@postGroups');
					Route::post('{id}/users', 'AdminGroupController@postUsers');
					Route::put('{id}', 'AdminGroupController@putGroups');
					Route::delete('{gid}', 'AdminGroupController@deleteGroups');
					Route::delete('{gid}/users', 'AdminGroupController@deleteUsers');
					Route::post('{gid}/homeworks', 'AdminGroupController@postHomeworks');
					Route::delete('{gid}/homeworks', 'AdminGroupController@deleteHomeworks');
				});
				//invitations
				Route::group(['prefix' => 'invitations', 'middleware' => 'role:admin'], function(){
					Route::get('/', 'AdminInvitationController@getInvitations');
					Route::get('{id}', 'AdminInvitationController@getInvitations');
					Route::post('/', 'AdminInvitationController@postInvitations');
					Route::post('{id}', 'AdminInvitationController@postInvitations');
					Route::put('{id}', 'AdminInvitationController@putInvitationsId');
					Route::put('/', 'AdminInvitationController@putInvitations');
					Route::delete('{iid}/{gid}', 'AdminInvitationController@deleteInvitations');
				});

				//problems
				Route::group(['prefix' => 'problems', 'middleware' => 'role:problem_manager'], function(){
					Route::get('/', 'AdminProblemController@getProblems');
					Route::get('{id}', 'AdminProblemController@getProblems');
					Route::get('{id}/data', 'AdminProblemController@getProblemsData');
					Route::post('{id}/data', 'AdminProblemController@postProblemsData');
					Route::post('/', 'AdminProblemController@postProblems');
					Route::put('{id}', 'AdminProblemController@putProblemsId');
					Route::put('/', 'AdminProblemController@putProblems');
					Route::delete('{id}', 'AdminProblemController@deleteProblems');
				});

				Route::group(['middleware' => 'role:admin'], function(){

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
					Route::get('problem-rejudge/check', 'AdminProblemRejudgeController@getProblemRejudgeCheck');

					//invitations generate
					Route::get('invitations-generate', 'AdminInvitationsGenerateController@getIndex');
					Route::post('invitations-generate', 'AdminInvitationsGenerateController@postIndex');

					//judgers
					Route::resource('judgers', 'AdminJudgerController');

					//roles
					Route::get('roles', 'AdminRolesController@getIndex');
					Route::post('roles', 'AdminRolesController@postIndex');
					Route::delete('roles', 'AdminRolesController@deleteIndex');

					//database backup
					Route::get('database-backup', 'AdminDatabaseBackupController@getIndex');
					Route::post('database-backup/restrict-size', 'AdminDatabaseBackupController@postRestrictSize');
					Route::delete('database-backup', 'AdminDatabaseBackupController@deleteBackup');

					//update system
					Route::get('update-system', 'AdminUpdateSystemController@getUpdate');
					Route::post('update-system', 'AdminUpdateSystemController@postUpdate');

					//advanced settings
					Route::get('advanced-settings', 'AdminAdvanced@getAdvanced');
					Route::post('advanced-settings', 'AdminAdvanced@postAdvanced');
				});

				//ajax
				Route::controller('ajax', 'AdminAjaxController');
		});
		Route::get('{url}', 'HomeController@getDiyPage');
});

Route::controller('judger','JudgerController');
Route::controller('ajax','AjaxController');
