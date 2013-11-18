<?php

class GroupsController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function showIndex($groupId) {
        // check first if groupId is valid
        $group = Group::find($groupId);
        if(!is_numeric($groupId) || empty($group)) {
            App::abort('404');
        }

        // get current user groups
        $groups = Group::getMyGroups();
        // get posts for the group
        $groupPosts = Post::getGroupPosts($groupId);

        // get number of group members
        $groupMembers = GroupMember::getGroupMembers($groupId)
            ->count();

        return View::make('group.index')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('posts', $groupPosts)
            ->with('memberCount', $groupMembers);
    }

    public function showMembers($groupId) {
        // check first if groupId is valid
        $group = Group::find($groupId);
        if(!is_numeric($groupId) || empty($group)) {
            App::abort('404');
        }

        // get group owner
        $owner = User::find($group->owner_id);
        // get current user groups
        $groups = Group::getMyGroups();
        // get group members
        $groupMembers = GroupMember::getGroupMembers($groupId);

        return View::make('group.members')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('ownerDetails', $owner)
            ->with('members', $groupMembers);
    }

    public function chat($groupId)
    {
         // check first if groupId is valid
        $group = Group::find($groupId);
        if(!is_numeric($groupId) || empty($group)) {
            App::abort('404');
        }

        // get current user groups
        $groups = Group::getMyGroups();

        // get number of group members
        $groupMembers = GroupMember::getGroupMembers($groupId);

        return View::make('group.chat')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('members', $groupMembers);
    }
}
