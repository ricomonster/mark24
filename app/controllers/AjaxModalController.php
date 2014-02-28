<?php

class AjaxModalController extends BaseController {

    protected $_errors = null;

    // Group Module Functions
    public function showCreateGroup() {
        return View::make('ajax.modal.group.creategroup');
    }

    public function createGroup() {
        if(Request::ajax()) {
            // validate data inserted
            $this->_validateGroupCreation();

            if(!empty($this->_errors)) {
                // return to page the errors via JSON
                $return['error']    = true;
                $return['messages'] = $this->_errors;

                return Response::json($return);
            }

            // save group to database
            $createGroup = new Group;
            $createGroup->owner_id = Auth::user()->id;
            $createGroup->group_code = $this->_generateGroupCode();
            $createGroup->group_name = Input::get('group-name');
            $createGroup->group_description = Input::get('group-description');
            $createGroup->group_size = Input::get('group-size');
            $createGroup->save();

            // add user as a member
            $addGroupMember = new GroupMember;
            $addGroupMember->group_member_id = Auth::user()->id;
            $addGroupMember->group_id = $createGroup->group_id;
            $addGroupMember->save();

            /*------------------------------------------------------------------
             Will automatically add the super admin!
            ------------------------------------------------------------------*/
            // get super admins
            $admins = User::where('flag', '=', 0)->get();
            foreach($admins as $admin) {
                $addAdmin = new GroupMember;
                $addAdmin->group_member_id = $admin->id;
                $addAdmin->group_id = $createGroup->group_id;
                $addAdmin->save();
            }

            // generate redirect link and send via JSON
            $return['error']    = false;
            $return['lz_link']  = sprintf(Request::root().'/groups/%s', $createGroup->group_id);

            return Response::json($return);
        }
    }

    public function showJoinGroup() {
        return View::make('ajax.modal.group.joingroup');
    }

    public function joinGroup() {
        $groupCode  = Input::get('group-code');
        $group      = Group::where('group_code', '=', $groupCode)->first();

        // validate group membership
        if(empty($groupCode)) {
            $error = 'Please provide a group code';
        } else if(empty($group)) {
            $error = 'Group does not exists.';
        } else {
            $groupMember = GroupMember::where('group_id', '=', $group->group_id)
                ->where('group_member_id', '=', Auth::user()->id)
                ->first();

            if(!empty($groupMember)) {
                $error = 'You are already a member of that group.';
            }
        }

        // check if there are errors detected
        if(!empty($error)) {
            $return['error']    = true;
            $return['message']  = $error;
        } else {
            // join the user to the group
            $addGroupMember = new GroupMember;
            $addGroupMember->group_member_id = Auth::user()->id;
            $addGroupMember->group_id = $group->group_id;
            $addGroupMember->save();

            // setup notification that the user joined the group
            Notification::setup('join_group', array(
                'involved_id' => $group->group_id));

            // set json shits
            $return['error'] = false;
            $return['lz_link']  = sprintf(Request::root().'/groups/%s', $group->group_id);
        }

        return Response::json($return);
    }

    public function showGroupSettings()
    {
        $groupId = Input::get('group_id');

        $group = Group::find($groupId);

        return View::make('ajax.modal.group.groupsettings')
            ->with('group', $group);
    }

    public function updateGroup()
    {
        $this->_validateGroupCreation();
        if(!empty($this->_errors)) {
            // return to page the errors via JSON
            $return['error']    = true;
            $return['messages'] = $this->_errors;

            return Response::json($return);
        }

        // no errors
        // update details
        $group = Group::find(Input::get('group-id'));
        $group->group_name = Input::get('group-name');
        $group->group_description = Input::get('group-description');
        $group->group_size = Input::get('group-size');
        $group->save();

        return Response::json(array('error' => false));
    }

    public function confirmGroupDelete()
    {
        $groupId = Input::get('group_id');
        // get group details
        $group = Group::find($groupId);

        return View::make('ajax.modal.group.confirmdeletegroup')
            ->with('group', $group);
    }

    public function deleteGroup()
    {
        $groupId = Input::get('group_id');

        // delete first the members
        GroupMember:: where('group_id', '=', $groupId)->delete();

        // delete posts for the group
        PostRecipient::where('recipient_id', '=', $groupId)
            ->where('recipient_type', '=', 'group')
            ->delete();

        // delete the group
        Group::where('group_id', '=', $groupId)->delete();

        return Response::json(array(
            'error' => false,
            'lz' => Request::root().'/home'));
    }

    public function confirmWithdrawGroup()
    {
        $groupId = Input::get('group_id');
        $group = Group::find($groupId);

        return View::make('ajax.modal.group.confirmleavegroup')
            ->with('group', $group);
    }

    public function withdrawGroup()
    {
        $groupId = Input::get('group_id');

        // delete first the posts for the group

        // remove from the group
    }

    public function showStartGroupChat()
    {
        $groupId = Input::get('group_id');
        $group = Group::find($groupId);

        return View::make('ajax.modal.group.confirmgroupchat')
            ->with('group', $group);
    }

    public function startGroupChat()
    {
        $groupId = Input::get('group_id');
        // create group chat!
        $conversation = new Conversation;
        $conversation->group_id = $groupId;
        $conversation->save();

        return Response::json(array(
            'error' => false,
            'lz' => Request::root().'/groups/'.$groupId.'/chat/'.$conversation->conversation_id));
    }

    public function confirmStopGroupChat()
    {
        $conversationId = Input::get('conversation_id');
        // get conversation details
        $conversation = Conversation::where('conversation_id', '=', $conversationId)
            ->leftJoin('groups', 'conversations.group_id', '=', 'groups.group_id')
            ->first();

        return View::make('ajax.modal.group.confirmstopgroupchat')
            ->with('conversation', $conversation);
    }

    public function showChangePassword()
    {
        $userId = Input::get('user_id');
        $user = User::find($userId);

        return View::make('ajax.modal.group.changepassword')
            ->with('user', $user);
    }

    public function resetPassword()
    {
        $userId = Input::get('user-id');
        $password = Input::get('reset-password');

        // look for the user data
        $user = User::find($userId);
        $user->password = Hash::make($password);
        $user->save();

        return Response::json(array('error' => false));
    }

    // End of Group Module Functions

    // Poststream Functions
    public function confirmDeletePost()
    {
        $postId = Input::get('post_id');
        // get details
        $post = Post::find($postId);

        return View::make('ajax.modal.poststream.confirmdeletepost')
            ->with('post', $post);
    }

    public function deletePost()
    {
        $postId = Input::get('post_id');
        // delete the post from the recipients
        PostRecipient::where('post_id', '=', $postId)->delete();
        // find the post
        $post = Post::find($postId);
        // check if there are files attached
        if($post->post_attached_files == 'true') {
            FileAttached::where('post_id', '=', $post->post_id)->delete();
        }

        // check the type of post
        switch($post->post_type) {
            case 'quiz' :
                // get quiz takers
                $takers = QuizTaker::where('post_id', '=', $post->post_id)
                    ->where('quiz_id', '=', $post->quiz_id)
                    ->get();
                if($takers->isEmpty()) continue;
                foreach($takers as $taker) {
                    // delete the answers
                    QuizAnswer::where('quiz_taker_id', '=', $taker->quiz_taker_id)
                        ->delete();
                    // delete notification
                    Notification::where('involved_id', '=', $taker->quiz_taker_id)
                        ->where(function($query) {
                            $query->where('notification_type', '=', 'quiz_submitted')
                                ->orWhere('notification_type', '=', 'quiz_graded');
                        })
                        ->delete();
                }

                break;
            case 'assignment' :
                // get assignment responses
                $responses = AssignmentResponse::where('post_id', '=', $post->post_id)
                    ->where('assignment_id', '=', $post->assignment_id)
                    ->get();
                if($responses->isEmpty()) continue;
                foreach($responses as $response) {
                    // delete notification
                    Notification::where('involved_id', '=', $response->assignment_response_id)
                        ->where(function($query) {
                            $query->where('notification_type', '=', 'quiz_submitted')
                                ->orWhere('notification_type', '=', 'quiz_graded');
                        })
                        ->delete();
                    $assignment = AssignmentResponse::find($response->assignment_response_id);
                    // delete
                    $assignment->delete();
                }

                break;
        }

        // check if there are comments
        $comments = Comment::where('post_id', '=', $post->post_id)
            ->first();
        // if there are comments, delete
        if(!empty($comments)) {
            // delete
            Comment::where('post_id', '=', $post->post_id)->delete();
        }

        // check if there are likes
        $likes = Like::where('post_id', '=', $post->post_id)
            ->first();
        // if there are likes, delete
        if(!empty($likes)) {
            // delete
            Like::where('post_id', '=', $post->post_id)->delete();
        }

        // delete also the notifications
        Notification::where('involved_id', '=', $post->post_id)
            ->where(function($query) {
                $query->where('notification_type', '=', 'posted')
                    ->orWhere('notification_type', '=', 'liked_post')
                    ->orWhere('notification_type', '=', 'commented');
            })
            ->delete();

        // set post to inactive
        $post->post_active = 0;
        $post->save();

        return Response::json(array('error' => false));
    }

    public function showLinkToPost()
    {
        $postId = Input::get('post_id');
        $post = Post::find($postId);

        return View::make('ajax.modal.poststream.linkpost')
            ->with('post', $post);
    }

    // End of Poststream functions

    // Forum Functions
    public function showAddCategory()
    {
        return View::make('ajax.modal.forum.addforumcategory');
    }

    public function addCategory()
    {
        // validate first the form
        $this->_validateAddForumCategory();

        // check if there are errors stored
        if(!empty($this->_errors)) {
            $return = array(
                'error'     => true,
                'messages'  => $this->_errors);

            return Response::json($return);
        }

        // no errors
        // check first if there's already a general topic in the forum
        $general = ForumCategory::where('category_name', '=', 'General Discussion')->first();
        if(empty($general)) {
            $generalCategory = new ForumCategory;
            // add a general discussion category
            $generalCategory->category_name = 'General Discussion';
            $generalCategory->description = 'Let\'s talk everything under the sun. :)';
            $generalCategory->seo_name = Helper::seoFriendlyUrl('General Discussion');
            $generalCategory->save();
        }

        // save category name
        // and create seo friendly url
        $addCategory = new ForumCategory;

        $newCategoryUrl = Helper::seoFriendlyUrl(Input::get('category-name'));
        $addCategory->category_name = ucwords(Input::get('category-name'));
        $addCategory->description = Input::get('category-description');
        $addCategory->seo_name = $newCategoryUrl;
        $addCategory->save();

        // create redirect link
        $lzLink = Request::root().'/the-forum/'.$newCategoryUrl;
        // redirect to the category page
        return Response::json(array(
            'error' => false,
            'lz' => $lzLink));
    }

    // End of Forum Functions

    public function showQuizList()
    {
        // get list of quiz that are ready
        $list = Quiz::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'READY')
            ->where('quiz_active', '=', 1)
            ->get();

        return View::make('ajax.modal.postcreator.quizlist')
            ->with('quizzes', $list);
    }

    public function getQuizDetails()
    {
        $quizId = Input::get('quiz_id');
        // get quiz details
        $quiz           = Quiz::find($quizId);
        // get the users groups
        $groups         = Group::getMyGroups();
        // get all group members of the groups
        $groupMembers   = GroupMember::getAllGroupMembers();

        return View::make('ajax.modal.postcreator.quizdetails')
            ->with('quiz', $quiz)
            ->with('groups', $groups)
            ->with('groupMembers', $groupMembers);
    }

    public function showReportProblemForm()
    {
        return View::make('ajax.modal.global.reportproblem');
    }

    public function submitProblem()
    {
        $details = Input::get('problem');
        $location = Input::get('location');
        $device = Helper::device();

        $report = new Report;
        $report->user_id = Auth::user()->id;
        $report->details = $details;
        $report->location = $location;
        $report->os = $device['platform'];
        $report->browser = $device['name'].' '.$device['version'];
        $report->ip = $device['ip'];
        $report->report_timestamp = time();
        $report->save();

        return Response::json(array('error' => false));
    }

    public function confirmDeleteQuiz()
    {
        $quizId = Input::get('quiz_id');
        return View::make('ajax.modal.postcreator.confirmdeletequiz')
            ->with('quizId', $quizId);
    }

    public function deleteQuiz()
    {
        $quizId = Input::get('quiz_id');

        $quiz = Quiz::find($quizId);

        $quiz->quiz_active = 0;
        $quiz->save();

        return Response::json(array('error' => false));
    }

    /*--------------------------------------------------------------------------
    Protected Methods
    --------------------------------------------------------------------------*/
    protected function _validateGroupCreation() {
        $this->_errors = array();

        $groupName          = Input::get('group-name');
        $groupSize          = Input::get('group-size');
        $groupDescription   = Input::get('group-description');

        if(empty($groupName)) {
            $this->_errors['groupName'] = 'You must enter a name for the group';
        } else if(!empty($groupName)) {
            // check if group already exists
            $exists = Group::where('group_name', '=', $groupName)
                ->where('owner_id', '=', Auth::user()->id)
                ->first();
            if(!empty($exists)) {
                $this->_errors['groupName'] = 'Group name already exists.';
            }
        }

        if(empty($groupSize)) {
            $this->_errors['groupSize'] = 'You must enter the expected group size of the group';
        }

        if(empty($groupDescription)) {
            $this->_errors['groupDescription'] = 'You must enter a description of the group';
        }

        return empty($this->_errors);
    }

    protected function _generateGroupCode() {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

        $randomString = '';
        do {
           for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while(Group::where('group_code', '=', $randomString)->first());

        return $randomString;
    }

    protected function _validateAddForumCategory()
    {
        $this->_errors = array();

        $name = Input::get('category-name');
        $description = Input::get('category-description');

        $nameExists = ForumCategory::where('category_name', '=', $name)->first();

        if(empty($name)) {
            $this->_errors['category_name'] = 'There should be a category name';
        } else if(!empty($name)) {
            // check if name already exists
            if(!empty($nameExists)) {
                $this->_errors['category_name'] = 'Category name '.$name.' already exists';
            }
        }

        if(empty($description)) {
            $this->_errors['category_description'] = 'Add a category description';
        }

        return empty($this->_errors);
    }
}
