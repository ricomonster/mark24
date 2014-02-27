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

Route::get('/', array('before' => 'logged-in', function()
{
    $loginError = Session::get('loginError');
    $loginError = (isset($loginError)) ? $loginError : null;

	return View::make('home.login')
        ->with('loginError', $loginError);
}));

Route::get('test', function() {
    // $code = '1234';
    // $email = 'riconess@gmail.com';
    // $name = 'Rico Maglayon';
    // $data = array('code' => $code, 'email' => $email);
    // $user = array('email' => $email, 'name' => $name);

    // $message = 'testing';
    // Mail::send('emails.confirmemail', $data, function($message) use ($user) {
    //    $message->to($user['email'], $user['name'])->subject('Welcome to eLinet!');
    // });

    // echo 'yes';
});

// AJAX Routes
// AJAX Assignment Manager Routes
Route::get('ajax/assignment-manager/get-takers', 'AjaxAssignmentManagerController@getTakers');
Route::get('ajax/assignment-manager/get-taker', 'AjaxAssignmentManagerController@getTakerDetails');

Route::post('ajax/assignment-manager/set-score', 'AjaxAssignmentManagerController@setTakerScore');

// AJAX Assignment Sheet Routes
Route::post('ajax/assignment-sheet/create-response', 'AjaxAssignmentSheetController@createResponse');

// AJAX Chat Routes
Route::get('ajax/chat/check-chat-details', 'AjaxChatController@chatDetails');
Route::get('ajax/chat/fetch-messages', 'AjaxChatController@getMessages');
Route::get('ajax/chat/check-online-users', 'AjaxChatController@checkUsersOnline');
Route::get('ajax/chat/check-status', 'AjaxChatController@checkConversationStatus');
Route::get('ajax/chat/last-conversation', 'AjaxChatController@getLatestConversation');
Route::get('ajax/chat/get-conversations', 'AjaxChatController@getArchivedConversation');

Route::post('ajax/chat/send-message', 'AjaxChatController@sendMessage');
Route::post('ajax/chat/stop-group-chat', 'AjaxChatController@stopGroupChat');

// AJAX CommentCreator Routes
Route::post('ajax/comment-creator/add-comment', 'AjaxCommentCreator@postCreateComment');

// AJAX File Controller
Route::get('ajax/thelibrary/get-library-view', 'AjaxFileController@getView');

Route::post('ajax/thelibrary/upload-file', 'AjaxFileController@uploadPost');

// AJAX Forum Routes
Route::post('ajax/the-forum/follow-thread', 'AjaxForumController@followThread');
Route::post('ajax/the-forum/unfollow-thread', 'AjaxForumController@unfollowThread');
Route::post('ajax/the-forum/update-thread', 'AjaxForumController@updateThread');
Route::post('ajax/the-forum/update-reply', 'AjaxForumController@updateThreadReply');

// AJAX Group Routes
Route::post('ajax/group/lock-group', 'AjaxGroupController@lockGroup');
Route::post('ajax/group/unlock-group', 'AjaxGroupController@changeGroupCode');
Route::post('ajax/group/reset-group-code', 'AjaxGroupController@changeGroupCode');
Route::post('ajax/group/join-the-user', 'AjaxGroupController@joinTheUser');

// AJAX Like Routes
Route::post('ajax/like/like-post', 'AjaxLikeController@likePost');
Route::post('ajax/like/unlike-post', 'AjaxLikeController@unlikePost');

// AJAX Modal Routes
Route::get('ajax/modal/show_create_group', 'AjaxModalController@showCreateGroup');
Route::get('ajax/modal/show_join_group', 'AjaxModalController@showJoinGroup');
Route::get('ajax/modal/show-add-forum-category', 'AjaxModalController@showAddCategory');
Route::get('ajax/modal/show-settings-group', 'AjaxModalController@showGroupSettings');
Route::get('ajax/modal/confirm-delete-group', 'AjaxModalController@confirmGroupDelete');
Route::get('ajax/modal/show-withdraw-group', 'AjaxModalController@confirmWithdrawGroup');
Route::get('ajax/modal/show-change-password', 'AjaxModalController@showChangePassword');
Route::get('ajax/modal/confirm-delete-post', 'AjaxModalController@confirmDeletePost');
Route::get('ajax/modal/link-post', 'AjaxModalController@showLinkToPost');
Route::get('ajax/modal/get-quiz-list', 'AjaxModalController@showQuizList');
Route::get('ajax/modal/show-confirm-chat', 'AjaxModalController@showStartGroupChat');
Route::get('ajax/modal/confirm-stop-chat', 'AjaxModalController@confirmStopGroupChat');
Route::get('ajax/modal/get-report-form', 'AjaxModalController@showReportProblemForm');
Route::get('ajax/modal/get-quiz-details', 'AjaxModalController@getQuizDetails');

Route::post('ajax/modal/create_group', 'AjaxModalController@createGroup');
Route::post('ajax/modal/join_group', 'AjaxModalController@joinGroup');
Route::post('ajax/modal/submit-new-category', 'AjaxModalController@addCategory');
Route::post('ajax/modal/submit-group-update', 'AjaxModalController@updateGroup');
Route::post('ajax/modal/delete-group', 'AjaxModalController@deleteGroup');
Route::post('ajax/modal/withdraw-group', 'AjaxModalController@withdrawGroup');
Route::post('ajax/modal/reset-password', 'AjaxModalController@resetPassword');
Route::post('ajax/modal/delete-post', 'AjaxModalController@deletePost');
Route::post('ajax/modal/start-group-chat', 'AjaxModalController@startGroupChat');
Route::post('ajax/modal/submit-problem', 'AjaxModalController@submitProblem');

// AJAX Notification Routes
Route::get('ajax/notifications/fetch', 'AjaxNotificationController@fetch');
Route::get('ajax/notifications/check', 'AjaxNotificationController@fetchCount');

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

// AJAX QuizManager Routes
Route::get('ajax/quiz-manager/show-taker-details', 'AjaxQuizManagerController@takerDetails');
Route::get('ajax/quiz-manager/taker-lists', 'AjaxQuizManagerController@takerLists');

Route::post('ajax/quiz-manager/set-ungraded', 'AjaxQuizManagerController@updateUngraded');

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
Route::post('ajax/post_creator/create_assignment', 'AjaxPostCreatorController@postCreateAssignment');
Route::post('ajax/post_creator/update-post', 'AjaxPostCreatorController@updatePost');

// AJAX Search Route
Route::get('ajax/search', 'AjaxSearchController@search');

// AJAX User Routes
Route::post('ajax/users/upload-photo', 'AjaxUsersController@postUploadPhoto');
Route::post('ajax/users/validate-student', 'AjaxUsersController@validateStudentDetails');
Route::post('ajax/users/validate-teacher', 'AjaxUsersController@validateTeacherDetails');
Route::post('ajax/users/update-story', 'AjaxUsersController@updateUserStory');
Route::post('ajax/users/update-places', 'AjaxUsersController@updateUserPlaces');
Route::post('ajax/users/send-confirmation-mail', 'AjaxUsersController@sendMail');

Route::put('ajax/users/update-personal-info', 'AjaxUsersController@updateUserDetails');

// Assignment Manager Routes
Route::get('assignment-manager/{assignmentId}/{postId}', 'AssignmentManagerController@index');

// Assignment Sheet Routes
Route::get('assignment-sheet/{assignmentId}/{postId}', 'AssignmentSheetController@index');

// Confirmation Routes
Route::get('confirmation-message-sent', 'ConfirmationController@confirmMessageSuccessfull');
Route::get('confirm', 'ConfirmationController@confirmedAccount');

// Control Routes
// Route::get('control', 'ControlController@index');
Route::get('control', array(
    'before' => 'super-admin',
    'uses' => 'ControlController@index'));

// File Routes
Route::get('file/{fileId}', 'FileController@downloadFile');

// Forum Routes
Route::get('the-forum', 'ForumController@index');
Route::get('the-forum/add-thread', 'ForumController@showAddThread');
Route::get('the-forum/{category}', 'ForumController@showCategory');
Route::get('the-forum/thread/{slug}/{id}', 'ForumController@showThread');

Route::post('the-forum/submit-new-thread', 'ForumController@submitThread');
Route::post('the-forum/create-thread-reply', 'ForumController@submitReplyThread');

// Group Routes
Route::get('groups/{groupId}', 'GroupsController@showIndex')->where('groupId', '[0-9]+');
Route::get('groups/{groupId}/members', 'GroupsController@showMembers')->where('groupId', '[0-9]+');
Route::get('groups/{groupId}/chat/{conversationId}', 'GroupsController@chat')
    ->where('groupId', '[0-9]+');
Route::get('groups/{groupId}/join-requests', 'GroupsController@joinRequests')->where('groupId', '[0-9]+');
Route::get('groups/{groupId}/chat-archives', 'GroupsController@chatArchives')->where('groupId', '[0-9]+');

// Group Forum Routes
Route::get('groups/{groupId}/the-forum', 'GroupsController@forums');
Route::get('groups/{groupId}/the-forum/add-thread', 'GroupsController@showAddThread');
Route::get('groups/{groupId}/the-forum/thread/{slug}/{id}', 'GroupsController@showThread');

// Home Routes
Route::get('home', 'HomeController@showHome');

// Library Routes
Route::get('the-library', 'LibraryController@index');
Route::get('the-library/attached', 'LibraryController@attachedFiles');

// Notification Routes
Route::get('notifications', 'NotificationController@index');

// Post Routes
Route::get('post/{postId}', 'PostController@showPost');

// Planner Routes
Route::get('planner', 'PlannerController@index');

// Profile Routes
Route::get('profile/{user}', 'ProfileController@showIndex');
Route::get('profile/{user}/{action}', 'ProfileController@showActions');

// Quiz Creator Routes
Route::get('quiz-creator', 'QuizCreatorController@getIndex');
Route::get('quiz-creator/{quizId}/edit', 'QuizCreatorController@editQuiz');

// Quiz Manager Routes
Route::get('quiz-manager/{quizId}/{postId}', 'QuizManagerController@index');

// Quiz Result Routes
Route::get('quiz-result/{quizId}/{postId}', 'QuizResultController@index');

// Quiz Sheet Routes
Route::get('quiz-sheet/{quizId}/{postId}', 'QuizSheetController@index');

// Setting Routes
Route::get('settings', 'SettingsController@getIndex');
Route::get('settings/profile', 'SettingsController@getProfile');
// Route::get('settings/password', 'SettingsController@getPasswordPage');

Route::post('ajax/settings/change-password', 'SettingsController@changePassword');
Route::post('ajax/settings/predefined-avatar', 'SettingsController@predefinedAvatar');

// User Routes
Route::get('signout', 'UsersController@getSignout');

Route::post('users/validate_signin', 'UsersController@getSignin');
Route::post('users/validate_teacher_signup', 'UsersController@createTeacher');
Route::post('users/validate_student_signup', 'UsersController@createStudent');
