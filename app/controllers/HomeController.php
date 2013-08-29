<?php

class HomeController extends BaseController {

	public function __construct() {
		$this->beforeFilter('auth');
	}

	public function showHome() {
		$groups 		= Group::getMyGroups();
		$groupMembers 	= GroupMember::getAllGroupMembers();

		$posts = Post::getAllPosts();

		return View::make('home.index')
			->with('groups', $groups)
			->with('groupMembers', $groupMembers)
			->with('posts', $posts);
	}

}
