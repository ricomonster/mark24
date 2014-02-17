<?php

class GroupsController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function showIndex($groupId) {
        $this->checkGroupMember($groupId);
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

        // check if there's an ongoing group chat
        $ongoing = Conversation::where('group_id', '=', $group->group_id)
            ->where('status', '=', 'OPEN')
            ->first();

        return View::make('group.index')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('posts', $groupPosts)
            ->with('stats', $this->groupStats($group->group_id))
            ->with('ongoingGroupChat', $ongoing)
            ->with('groupChats', Group::ongoingGroupChats());
    }

    public function showMembers($groupId) {
        $this->checkGroupMember($groupId);
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
            ->with('stats', $this->groupStats($group->group_id))
            ->with('members', $groupMembers);
    }

    public function forums($groupId)
    {
        $this->checkGroupMember($groupId);
        // get group details
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(!is_numeric($groupId) || empty($group) || empty($member)) return View::make('templates.fourohfour');

        // get current user groups
        $groups = Group::getMyGroups();

        $sort = Input::get('sort');
        // let's get the categories
        $categories = ForumCategory::all();
        switch($sort) {
            case 'latest' :
                // get latest threads
                $threads = ForumThread::orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('sticky_post', 'DESC')
                    ->rightJoin('group_forum_threads', 'forum_threads.forum_thread_id', '=', 'group_forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->where('group_forum_threads.group_id', '=', $group->group_id)
                    ->get();
                break;
            case 'popular' :
                $threads = ForumThread::where('views', '!=', '0')
                    ->orderBy('views', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('replies', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'unanswered' :
                // get unanswered threads
                $threads = ForumThread::orderBy('thread_timestamp', 'DESC')
                    ->where('replies', '=', 0)
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'following' :
                $threads = FollowedForumThread::where('followed_forum_threads.user_id', '=', Auth::user()->id)
                    ->leftJoin('forum_threads',
                        'followed_forum_threads.forum_thread_id',
                        '=',
                        'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->orderBy('forum_threads.last_reply_timestamp', 'DESC')
                    ->orderBy('forum_threads.thread_timestamp', 'DESC')
                    ->get();
                break;
            case 'my-topics' :
                $threads = ForumThread::where('user_id', '=', Auth::user()->id)
                    ->orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'last-viewed' :
                $threads = ForumThreadView::where('forum_thread_views.user_id', '=', Auth::user()->id)
                    ->orderBy('forum_thread_views.view_timestamp', 'DESC')
                    ->leftJoin(
                        'forum_threads',
                        'forum_thread_views.forum_thread_id',
                        '=',
                        'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            default :
                // get latest threads
                $threads = ForumThread::orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('sticky_post', 'DESC')
                    ->leftJoin('group_forum_threads', 'forum_threads.forum_thread_id', '=', 'group_forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->where('group_forum_threads.group_id', '=', $group->group_id)
                    ->get();
                break;
        }

        return View::make('forums.index')
            ->with('categories', $categories)
            ->with('threads', $threads)
            ->with('sort', $sort)
            ->with('group', $group)
            ->with('groupLists', $groups)
            ->with('stats', $this->groupStats($group->group_id));
    }

    public function showAddThread($groupId)
    {
        $this->checkGroupMember($groupId);
        // get group details
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(!is_numeric($groupId) || empty($group) || empty($member)) return View::make('templates.fourohfour');

        // get current user groups
        $groups = Group::getMyGroups();

        // let's get the categories
        $categories = ForumCategory::all();
        return View::make('forums.addthread')
            ->with('categories', $categories)
            ->with('group', $group)
            ->with('groupLists', $groups)
            ->with('stats', $this->groupStats($group->group_id));
    }

    public function showThread($groupId, $slug, $id)
    {
        $this->checkGroupMember($groupId);
        $page = Input::get('page');
        // get group details
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

        // get the details of the thread
        $thread = ForumThread::where('seo_url', '=', $slug)
            ->where('forum_thread_id', '=', $id)
            ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
            ->where('thread_source', '=', 1)
            ->first();

        // check if the thread exists
        if(empty($thread)) {
            // redirect to page not found
            return View::make('templates.fourohfour');
        }

        // check if the user already viewed this thread
        $visit = ForumThreadView::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // if empty, the user viewed this page for the first time
        if(empty($visit)) {
            $visitor = new ForumThreadView;
            $visitor->user_id = Auth::user()->id;
            $visitor->forum_thread_id = $thread->forum_thread_id;
            $visitor->view_timestamp = time();
            $visitor->last_viewed = date('Y-m-d');
            $visitor->save();

            // increment the number of views of the thread
            $thread->views += 1;
            $thread->save();
        }

        // user already viewed this thread
        if(!empty($visit)) {
            // check if the last viewed date is the same from the
            // date today
            if(date('Y-m-d') != $visit->last_viewed) {
                $visit->view_timestamp = time();
                $visit->last_viewed = date('Y-m-d');
                $visit->save();

                // increment the number of views of the thread
                $thread->views += 1;
                $thread->save();
            }
        }

        // check also if the thread is being followed
        $followed = FollowedForumThread::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // get the thread replies
        $replies = ForumThreadReply::where('forum_thread_id', '=', $thread->forum_thread_id)
            ->leftJoin('users', 'forum_thread_replies.user_id', '=', 'users.id')
            ->orderBy('reply_timestamp', 'ASC')
            ->paginate(10);

        // get all categories
        $categories = ForumCategory::all();

        return View::make('forums.thread')
            ->with('thread', $thread)
            ->with('replies', $replies)
            ->with('followed', $followed)
            ->with('categories', $categories)
            ->with('page', $page)
            ->with('group', $group)
            ->with('groupLists', $groups)
            ->with('stats', $this->groupStats($group->group_id));
    }

    public function chat($groupId, $conversationId)
    {
        $this->checkGroupMember($groupId);
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
            ->with('stats', $this->groupStats($group->group_id))
            ->with('conversation', $conversation);
    }

    public function joinRequests($groupId)
    {
        $this->checkGroupMember($groupId);
        // get group details
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(!is_numeric($groupId) || empty($group) || empty($member) || Auth::user()->account_type != 1) {
            return View::make('templates.fourohfour');
        }

        // get current user groups
        $groups = Group::getMyGroups();
        
        // get lists of join requests
        $wannaBeMembers = Inquire::where('involved_id', '=', $group->group_id)
            ->where('type', '=', 'request_join_group')
            ->where('status', '=', 1)
            ->leftJoin('users', 'inquiries.inquirer_id', '=', 'users.id')
            ->orderBy('inquiry_id', 'DESC')
            ->get();

        return View::make('group.requests')
            ->with('groupDetails', $group)
            ->with('groups', $groups)
            ->with('members', $wannaBeMembers)
            ->with('stats', $this->groupStats($group->group_id));
    }

    protected function chatArchives($groupId)
    {
        $this->checkGroupMember($groupId);
        // get group details
        // check first if groupId is valid
        $group = Group::find($groupId);
        // check if the current user is a member of the group
        $member = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(!is_numeric($groupId) || empty($group) || empty($member) || Auth::user()->account_type != 1) {
            return View::make('templates.fourohfour');
        }

        // get current user groups
        $groups = Group::getMyGroups();
    }

    protected function checkGroupMember($groupId)
    {
        $isMember = GroupMember::where('group_member_id', '=', Auth::user()->id)
            ->where('group_id', '=', $groupId)
            ->first();

        if(empty($isMember)) return View::make('templates.fourohfour');
    }
    
    protected function groupStats($groupId)
    {
        $stats = array();
        // get the group details
        $group = Group::find($groupId);
        $stats['member_count'] = GroupMember::getGroupMembers($group->group_id)
            ->count();
        if(Auth::user()->account_type == 1) {
            // get the counts of join requests
            $stats['join_requests'] = Inquire::where('involved_id', '=', $group->group_id)
                ->where('type', '=', 'request_join_group')
                ->where('status', '=', 1)
                ->orderBy('inquiry_id', 'DESC')
                ->get()
                ->count();    
        }
        
        return $stats;
    }
}
