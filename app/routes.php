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

Route::get('test', function() {
    echo 'Get fucking lost.';
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

// AJAX CommentCreator Routes
Route::post('ajax/comment-creator/add-comment', 'AjaxCommentCreator@postCreateComment');

// AJAX QuizCreator Routes
Route::post('ajax/quiz-creator/create-new-quiz', 'AjaxQuizCreatorController@postCreateQuiz');
Route::post('ajax/quiz-creator/update-quiz', 'AjaxQuizCreatorController@postUpdateQuiz');
Route::post('ajax/quiz-creator/update-question', 'AjaxQuizCreatorController@postUpdateQuestion');
Route::post('ajax/quiz-creator/add-question', 'AjaxQuizCreatorController@postAddQuestion');
Route::post('ajax/quiz-creator/add-response', 'AjaxQuizCreatorController@postAddResponse');
Route::post('ajax/quiz-creator/remove-question', 'AjaxQuizCreatorController@postRemoveQuestion');
Route::post('ajax/quiz-creator/submit-quiz', 'AjaxQuizCreatorController@postSubmitQuiz');

Route::get('ajax/quiz-creator/check-active-quiz', 'AjaxQuizCreatorController@getCheckActiveQuiz');
Route::get('ajax/quiz-creator/get-question', 'AjaxQuizCreatorController@getQuestion');
Route::get('ajax/quiz-creator/get-questions', 'AjaxQuizCreatorController@getQuestions');
Route::get('ajax/quiz-creator/get-question-lists', 'AjaxQuizCreatorController@getQuestionLists');

// AJAX QuizSheet Routes
Route::get('ajax/the-quiz-sheet/check-quiz-taker', 'AjaxTheQuizSheetController@checkQuizTaker');
Route::get('ajax/the-quiz-sheet/get-questions', 'AjaxTheQuizSheetController@getQuestions');

Route::post('ajax/the-quiz-sheet/start-quiz', 'AjaxTheQuizSheetController@startQuiz');
Route::post('ajax/the-quiz-sheet/update-answer', 'AjaxTheQuizSheetController@updateAnswer');

// AJAX PostCreator Routes
Route::post('ajax/post_creator/create_note', 'AjaxPostCreatorController@createNote');
Route::post('ajax/post_creator/create_alert', 'AjaxPostCreatorController@createAlert');
Route::post('ajax/post_creator/create_quiz', 'AjaxPostCreatorController@postCreateQuiz');

// Group Routes
Route::get('groups/{groupId}', 'GroupsController@showIndex');
Route::get('groups/{groupId}/members', 'GroupsController@showMembers');

// Home Routes
Route::get('home', 'HomeController@showHome');

// Profile Routes
Route::get('profile/{user}', 'ProfileController@showIndex');

// Quiz Creator Routes
Route::get('quiz-creator', 'QuizCreatorController@getIndex');

// Quiz Sheet Routes
Route::get('quiz-sheet/{quizId}', 'QuizSheetController@index');

// Setting Routes
Route::get('settings', 'SettingsController@getIndex');
Route::get('settings/password', 'SettingsController@getPasswordPage');

// User Routes
Route::get('signout', 'UsersController@getSignout');

Route::post('users/validate_signin', 'UsersController@getSignin');
Route::post('users/validate_teacher_signup', 'UsersController@createTeacher');
Route::post('users/validate_student_signup', 'UsersController@createStudent');
