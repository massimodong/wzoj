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
Route::get('s/{psid}','ProblemsetController@getProblemset');
Route::get('s/{psid}/edit','ProblemsetController@getEditProblemset');

Route::get('s/{psid}/{pid}','ProblemsetController@getProblem');
Route::post('s/{psid}','ProblemsetController@postProblem');
Route::put('s/{psid}/{pid}','ProblemsetController@putProblem');
Route::delete('s/{psid}/{pid}','ProblemsetController@deleteProblem');

Route::controller('admin','AdminController');
