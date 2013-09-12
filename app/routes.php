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
// AJAX User Routes
Route::post('ajax/users/upload-photo', 'AjaxUsersController@postUploadPhoto');

Route::put('ajax/users/update-personal-info', 'AjaxUsersController@putUserInfo');

// AJAX Modal Routes
Route::get('ajax/modal/show_create_group', 'AjaxModalController@showCreateGroup');
Route::get('ajax/modal/show_join_group', 'AjaxModalController@showJoinGroup');

Route::post('ajax/modal/create_group', 'AjaxModalController@createGroup');
Route::post('ajax/modal/join_group', 'AjaxModalController@joinGroup');

// AJAX QuizCreator Routes
Route::post('ajax/quiz-creator/create-new-quiz', 'AjaxQuizCreatorController@postCreateQuiz');

// AJAX PostCreator Routes
Route::post('ajax/post_creator/create_note', 'AjaxPostCreatorController@createNote');
Route::post('ajax/post_creator/create_alert', 'AjaxPostCreatorController@createAlert');

// Group Routes
Route::get('groups/{groupId}', 'GroupsController@showIndex');
Route::get('groups/{groupId}/members', 'GroupsController@showMembers');

// Home Routes
Route::get('home', 'HomeController@showHome');

// Profile Routes
Route::get('profile', 'ProfileController@showIndex');

// Quiz Creator Routes
Route::get('quiz-creator', 'QuizCreatorController@getIndex');

// Setting Routes
Route::get('settings', 'SettingsController@getIndex');
Route::get('settings/password', 'SettingsController@getPasswordPage');

// User Routes
Route::get('signout', 'UsersController@getSignout');

Route::post('users/validate_signin', 'UsersController@getSignin');
Route::post('users/validate_teacher_signup', 'UsersController@createTeacher');
Route::post('users/validate_student_signup', 'UsersController@createStudent');
