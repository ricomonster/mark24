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

});

// AJAX Routes
// AJAX User Routes
Route::post('ajax/users/upload-photo', 'AjaxUsersController@postUploadPhoto');

Route::put('ajax/users/update-personal-info', 'AjaxUsersController@putUserInfo');

// AJAX Modal Routes
Route::get('ajax/modal/show_create_group', 'AjaxModalController@showCreateGroup');
Route::get('ajax/modal/show_join_group', 'AjaxModalController@showJoinGroup');
Route::get('ajax/modal/show-add-forum-category', 'AjaxModalController@showAddCategory');
Route::get('ajax/modal/show-settings-group', 'AjaxModalController@showGroupSettings');
Route::get('ajax/modal/confirm-delete-group', 'AjaxModalController@confirmGroupDelete');
Route::get('ajax/modal/show-withdraw-group', 'AjaxModalController@confirmWithdrawGroup');
Route::get('ajax/modal/show-change-password', 'AjaxModalController@showChangePassword');

Route::post('ajax/modal/create_group', 'AjaxModalController@createGroup');
Route::post('ajax/modal/join_group', 'AjaxModalController@joinGroup');
Route::post('ajax/modal/submit-new-category', 'AjaxModalController@addCategory');
Route::post('ajax/modal/submit-group-update', 'AjaxModalController@updateGroup');
Route::post('ajax/modal/delete-group', 'AjaxModalController@deleteGroup');
Route::post('ajax/modal/withdraw-group', 'AjaxModalController@withdrawGroup');
Route::post('ajax/modal/reset-password', 'AjaxModalController@resetPassword');

// AJAX CommentCreator Routes
Route::post('ajax/comment-creator/add-comment', 'AjaxCommentCreator@postCreateComment');

// AJAX Forum Routes
Route::post('ajax/the-forum/follow-thread', 'AjaxForumController@followThread');
Route::post('ajax/the-forum/unfollow-thread', 'AjaxForumController@unfollowThread');

// AJAX Group Routes
Route::post('ajax/group/lock-group', 'AjaxGroupController@lockGroup');
Route::post('ajax/group/unlock-group', 'AjaxGroupController@changeGroupCode');
Route::post('ajax/group/reset-group-code', 'AjaxGroupController@changeGroupCode');

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
Route::post('ajax/the-quiz-sheet/time-remaining', 'AjaxTheQuizSheetController@timeRemaining');
Route::post('ajax/the-quiz-sheet/submit-quiz', 'AjaxTheQuizSheetController@submitQuiz');

// AJAX PostCreator Routes
Route::post('ajax/post_creator/create_note', 'AjaxPostCreatorController@createNote');
Route::post('ajax/post_creator/create_alert', 'AjaxPostCreatorController@createAlert');
Route::post('ajax/post_creator/create_quiz', 'AjaxPostCreatorController@postCreateQuiz');

// Forum Routes
Route::get('the-forum', 'ForumController@index');
Route::get('the-forum/add-thread', 'ForumController@showAddThread');
Route::get('the-forum/{category}', 'ForumController@showCategory');
Route::get('the-forum/thread/{slug}/{id}', 'ForumController@showThread');

Route::post('the-forum/submit-new-thread', 'ForumController@submitThread');
Route::post('the-forum/create-thread-reply', 'ForumController@submitReplyThread');

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

Route::post('ajax/settings/change-password', 'SettingsController@changePassword');

// User Routes
Route::get('signout', 'UsersController@getSignout');

Route::post('users/validate_signin', 'UsersController@getSignin');
Route::post('users/validate_teacher_signup', 'UsersController@createTeacher');
Route::post('users/validate_student_signup', 'UsersController@createStudent');
