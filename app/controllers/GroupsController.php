<?php

class GroupsController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function showIndex($groupId) {
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(!is_numeric($groupId) || empty($group) || empty($member)) {
            return View::make('templates.fourohfour');
        }

        // get current user groups
        $groups = Group::getMyGroups();
        // get posts for the group
        $groupPosts = Post::getGroupPosts($groupId);

        // get number of group members
        $groupMembers = GroupMember::getGroupMembers($groupId)
            ->count();

        // check if there's an ongoing group chat
        $ongoing = Conversation::where('group_id', '=', $group->group_id)
            ->where('status', '=', 'OPEN')
            ->first();

        return View::make('group.index')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('posts', $groupPosts)
            ->with('memberCount', $groupMembers)
            ->with('ongoingGroupChat', $ongoing)
            ->with('groupChats', Group::ongoingGroupChats());
    }

    public function showMembers($groupId) {
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();
        if(!is_numeric($groupId) || empty($group) || empty($member)) {
            return View::make('templates.fourohfour');
        }

        // get group owner
        $owner = User::find($group->owner_id);
        // get current user groups
        $groups = Group::getMyGroups();
        // get group members
        $groupMembers = GroupMember::getGroupMembers($groupId);
        // check if there's an ongoing group chat
        $ongoing = Conversation::where('group_id', '=', $group->group_id)
            ->where('status', '=', 'OPEN')
            ->first();

        return View::make('group.members')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('ownerDetails', $owner)
            ->with('ongoingGroupChat', $ongoing)
            ->with('members', $groupMembers);
    }

    public function chat($groupId, $conversationId)
    {
         // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();
        if(!is_numeric($groupId) || empty($group) || empty($member)) {
            return View::make('templates.fourohfour');
        }

        // check if there's an existing converation
        $conversation = Conversation::where('conversation_id', '=', $conversationId)
            ->where('group_id', '=', $groupId)
            ->where('status', '=', 'OPEN')
            ->first();
        if(empty($conversation)) {
            // show 404 error
            return View::make('templates.fourohfour');
        }

        // get current user groups
        $groups = Group::getMyGroups();

        // get number of group members
        $groupMembers = GroupMember::getGroupMembers($groupId);

        return View::make('group.chat')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('members', $groupMembers)
            ->with('conversation', $conversation);
    }
}
