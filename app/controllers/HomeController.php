<?php

class HomeController extends BaseController {

	public function __construct() {
		$this->beforeFilter('auth');
	}

	public function showHome() {
		$groups 		= Group::getMyGroups();
		$groupMembers 	= GroupMember::getAllGroupMembers();

		$posts = Post::getAllPosts();

		$quiz = Session::get('quiz_details');
	    $quiz = (isset($quiz)) ? $quiz : null;

		return View::make('home.index')
			->with('groups', $groups)
			->with('groupMembers', $groupMembers)
			->with('posts', $posts)
			->with('quiz', $quiz);
	}

}
