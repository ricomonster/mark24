<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{   
    $loginError = Session::get('loginError');
    $loginError = (isset($loginError)) ? $loginError : null;

	return View::make('home.login')
        ->with('loginError', $loginError);
});

// AJAX Routes
// AJAX Modal Routes
Route::get('ajax/modal/show_create_group', 'AjaxModalController@showCreateGroup');

Route::post('ajax/modal/create_group', 'AjaxModalController@createGroup');

// AJAX PostCreator Routes
Route::post('ajax/post_creator/create_note', 'AjaxPostCreatorController@createNote');

// Home Routes
Route::get('home', 'HomeController@showHome');

// Group Routes
Route::get('groups/{groupId}', 'GroupsController@showIndex');
Route::get('groups/{groupId}/members', 'GroupsController@showMembers');

// Profile Routes
Route::get('profile', 'ProfileController@showIndex');

// User Routes
Route::get('signout', 'UsersController@getSignout');

Route::post('users/validate_signin', 'UsersController@getSignin');
Route::post('users/validate_teacher_signup', 'UsersController@createTeacher');
Route::post('users/validate_student_signup', 'UsersController@createStudent');
