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

Route::group(['middleware' => ['web', 'antibot', 'contest']], function(){

    Route::get('/','HomeController@index');
    Route::get('ranklist', 'HomeController@ranklist');
    Route::get('sorry', 'HomeController@getSorry');
    Route::post('sorry', 'HomeController@postSorry');

    //TODO: change api
    Route::get('ide', 'HomeController@ide');
    Route::post('ide', 'HomeController@simpleJudge');

    Route::get('password/change', 'PasswordController@getChangePassword');
    Route::post('password/change', 'PasswordController@postChangePassword');

    Route::group(['prefix' => 'auth'], function(){
      Route::get('logout', 'Auth\LoginController@getLogout');
      Auth::routes();
    });

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
    Route::post('s/{psid}/virtual_participate', 'ProblemsetController@postVirtualParticipate')->middleware('auth');

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

    Route::get('/groups/{id}/homework', 'GroupController@getHomeworkStatus');

    Route::post('solutions/answerfile', 'SolutionController@postSubmitAnswerfile');
    Route::resource('solutions','SolutionController');

    Route::get('files/{name}','FileController@showfile')->where('name', '.*');
    Route::resource('files','FileController');

    Route::get('search', 'HomeController@search')->middleware('auth');

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

    Route::get('_captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
    Route::group(['prefix' => 'admin', 'middleware' => 'role:manager'], function(){
        Route::get('/', 'AdminHomeController@index');
        Route::post('options','AdminHomeController@postOptions');
        //appearance
        Route::group(['prefix' => 'appearance', 'middleware' => 'role:admin'], function(){
          Route::get('/', 'AdminAppearanceController@getAppearance');
          Route::post('sidebars', 'AdminAppearanceController@postSidebar');
          Route::put('sidebars/{id}', 'AdminAppearanceController@putSidebar');
          Route::delete('sidebars/{id}', 'AdminAppearanceController@deleteSidebar');

          Route::post('side-panels', 'AdminAppearanceController@postSidePanels');
          Route::get('side-panels/{id}', 'AdminAppearanceController@getSidePanels');
          Route::put('side-panels/{id}', 'AdminAppearanceController@putSidePanels');

          Route::post('diy-pages', 'AdminAppearanceController@postDiyPages');
          Route::get('diy-pages/{id}', 'AdminAppearanceController@getDiyPages');
          Route::put('diy-pages/{id}', 'AdminAppearanceController@putDiyPages');
        });
        //functions
        Route::group(['prefix' => 'functions', 'middleware' => 'role:admin'], function(){
          Route::get('/', 'AdminFunctionsController@getFunctions');
          Route::post('broadcast', 'AdminFunctionsController@postBroadcast');
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
          Route::get('/dataTablesAjax', 'AdminProblemController@getDataTablesAjax');
          Route::get('{id}', 'AdminProblemController@getProblems');
          Route::get('{id}/data', 'AdminProblemController@getProblemsData');
          Route::post('{id}/data', 'AdminProblemController@postProblemsData');
          Route::post('/', 'AdminProblemController@postProblems');
          Route::put('{id}', 'AdminProblemController@putProblemsId');
          Route::put('/', 'AdminProblemController@putProblems');
          Route::delete('{id}', 'AdminProblemController@deleteProblems');
        });

        Route::group(['middleware' => 'role:user_manager'], function(){
          //users
          Route::resource('users', 'AdminUserController');
        });

        Route::group(['middleware' => 'role:admin'], function(){

          //import problems
          Route::get('import-problems', 'AdminImportProblemsController@getImportProblems');
          Route::post('import-problems', 'AdminImportProblemsController@postImportProblems');

          //ProblemTags
          Route::get('problem-tags', 'AdminProblemTagController@index');
          Route::post('problem-tags', 'AdminProblemTagController@store');
          Route::put('problem-tags/hierarchy', 'AdminProblemTagController@updateHierarchy');
          Route::put('problem-tags/{id}', 'AdminProblemTagController@update');

          //problem rejudge
          Route::get('problem-rejudge', 'AdminProblemRejudgeController@getProblemRejudge');
          Route::post('problem-rejudge', 'AdminProblemRejudgeController@postProblemRejudge');
          Route::get('problem-rejudge/check', 'AdminProblemRejudgeController@getProblemRejudgeCheck');

          //invitations generate
          Route::get('invitations-generate', 'AdminInvitationsGenerateController@getIndex');
          Route::post('invitations-generate', 'AdminInvitationsGenerateController@postIndex');

          //accounts generate
          Route::resource('accounts-generate', 'AdminAccountsGenerateController');

          //judgers
          Route::resource('judgers', 'AdminJudgerController');

          //roles
          Route::get('roles', 'AdminRolesController@getIndex');
          Route::post('roles', 'AdminRolesController@postIndex');
          Route::delete('roles', 'AdminRolesController@deleteIndex');

          //update system
          Route::get('update-system', 'AdminUpdateSystemController@getUpdate');
          Route::post('update-system', 'AdminUpdateSystemController@postUpdate');

          //user logs
          Route::resource('user-logs', 'AdminUserLogController');
        });

        //ajax
        Route::group(['prefix' => 'ajax'], function(){
            Route::get('problemset-problems', 'AdminAjaxController@getProblemsetProblems');
        });
    });
    Route::get('{url}', 'HomeController@getDiyPage');
});

Route::group(['prefix' => 'judger'], function(){
    Route::get('index', 'JudgerController@getIndex');
    Route::get('pending-solutions', 'JudgerController@getPendingSolutions');
    Route::post('checkout', 'JudgerController@postCheckout');
    Route::get('solution', 'JudgerController@getSolution');
    Route::get('problem', 'JudgerController@getProblem');
    Route::post('update-ce', 'JudgerController@postUpdateCe');
    Route::post('update-solution', 'JudgerController@postUpdateSolution');
    Route::post('finish-judging', 'JudgerController@postFinishJudging');
    Route::get('get-answer', 'JudgerController@getGetAnswer');
    Route::get('get-sim-solutions', 'JudgerController@getGetSimSolutions');
    Route::post('update-sim', 'JudgerController@postUpdateSim');

    /*
    Route::post('solution', 'JudgerController@getSolution');
    Route::get('problem', 'JudgerController@getProblem');
    Route::post('list-testcases', 'JudgerController@postListTestcases');
    Route::post('compile-error', 'JudgerController@postCompileError');
    Route::post('testcase', 'JudgerController@postTestcase');
    */
});
//Route::controller('judger','JudgerController');
