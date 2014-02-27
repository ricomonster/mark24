<?php //-->

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';

    public static function setup($type, $settings)
    {
        $time = time();
        $date = date('Y-m-d');
        switch($type) {
            // assignment
            case 'assignment_graded' :
                $assignmentResponseId   = $settings['assignment_response_id'];
                $assignmentId           = $settings['assignment_id'];
                // get the assignment details
                $assignment = Assignment::find($assignmentId);
                // get the taker details
                $taker = AssignmentResponse::find($assignmentResponseId);

                $notification                           = new Notification;
                $notification->receiver_id              = $taker->user_id;
                $notification->sender_id                = Auth::user()->id;
                $notification->notification_type        = 'assignment_graded';
                $notification->involved_id              = $taker->assignment_response_id;
                $notification->notification_timestamp   = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
            case 'assignment_submitted' :
                $assignmentId = $settings['assignment_id'];
                $involvedId = $settings['involved_id'];
                // get assignment details
                $assignment = Assignment::find($assignmentId);

                $notification = new Notification;
                $notification->receiver_id = $assignment->user_id;
                $notification->sender_id = Auth::user()->id;
                $notification->notification_type = 'assignment_submitted';
                $notification->involved_id = $involvedId;
                $notification->notification_timestamp = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
            // comments
            case 'commented' :
                // get first the necessary details
                $post = Post::find($settings['involved_id']);
                // get the comments
                $comments = Comment::where('post_id', '=', $post->post_id)
                    ->get();
                // get the recipients of the post
                $recipients = PostRecipient::where('post_id', '=', $post->post_id)
                    ->get();
                // check if the owner of the post is the current user
                if(Auth::user()->id != $post->user_id) {
                    // check for notifications
                    $exists = Notification::where('notification_type', '=', 'comment')
                        ->where('involved_id', '=', $post->post_id)
                        ->where('sender_id', '=', Auth::user()->id)
                        ->where('receiver_id', '=', $post->user_id)
                        ->first();
                    if(empty($exists)) {
                        // create the notification
                        $notification = new Notification;
                        $notification->receiver_id = $post->user_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'commented';
                        $notification->involved_id = $post->post_id;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                // extract the userid of the commenters
                foreach($comments as $comment) {
                    // check if the commenter is also the current user
                    if(Auth::user()->id == $comment->user_id) continue;
                    // check if there's already a notification for the user
                    $exists = Notification::where('notification_type', '=', 'comment')
                        ->where('involved_id', '=', $post->post_id)
                        ->where('receiver_id', '=', $comment->user_id)
                        ->first();

                    // notification not yet created
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->receiver_id = $comment->user_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'commented';
                        $notification->involved_id = $post->post_id;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                break;
            // direct message
            case 'direct_message' :
                $receiverId = $settings['receiver_id'];
                $senderId = $settings['sender_id'];
                $involvedId = $settings['involved_id'];
                // check if notification exists
                $exists = Notification::where('notification_type', '=', 'direct_message')
                    ->where('involved_id', '=', $involvedId)
                    ->where('sender_id', '=', $senderId)
                    ->where('receiver_id', '=', $receiverId)
                    ->first();
                if(empty($exists)) {
                    // create notification
                    $notification = new Notification;
                    $notification->receiver_id = $receiverId;
                    $notification->sender_id = $senderId;
                    $notification->notification_type = 'direct_message';
                    $notification->involved_id = $involvedId;
                    $notification->notification_timestamp = $time;
                    $notification->date_added = $date;
                    $notification->save();
                }

                // if already exists update settings
                if(!empty($exists)) {
                    $exists->seen = 0;
                    $exists->notification_timestamp = $time;
                    $exists->date_added = $date;
                    $exists->save();
                }

                break;
            // forum reply
            case 'forum_reply' :
                // get the thread id details
                $thread = ForumThread::find($settings['involved_id']);
                // get the users who commented to the thread
                $repliers = ForumThreadReply::where('forum_thread_id', '=', $thread->forum_thread_id)
                    ->get();
                // get the followers of the thread
                $followers = FollowedForumThread::where('forum_thread_id', '=', $thread->forum_thread_id)
                    ->get();
                // check if the current owner of the post is the user
                if(Auth::user()->id != $thread->user_id) {
                    // check if the notification exists
                    $exists = Notification::where('notification_type', '=', 'forum_reply')
                        ->where('involved_id', '=', $thread->forum_thread_id)
                        ->where('sender_id', '=', Auth::user()->id)
                        ->where('receiver_id', '=', $thread->user_id)
                        ->first();
                    // if empty, create
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->receiver_id = $thread->user_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'forum_reply';
                        $notification->involved_id = $thread->forum_thread_id;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                foreach($repliers as $replier) {
                    // check first the user
                    if(Auth::user()->id == $replier->user_id) continue;
                    // check if the notification already exists
                    $exists = Notification::where('notification_type', '=', 'forum_reply')
                        ->where('involved_id', '=', $thread->forum_thread_id)
                        ->where('sender_id', '=', Auth::user()->id)
                        ->where('receiver_id', '=', $replier->user_id)
                        ->first();
                    // if empty, create
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->receiver_id = $replier->user_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'forum_reply';
                        $notification->involved_id = $thread->forum_thread_id;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                foreach($followers as $follower) {
                    // check first the user
                    if(Auth::user()->id == $follower->user_id) continue;
                    // check if the notification already exists
                    $exists = Notification::where('notification_type', '=', 'forum_reply')
                        ->where('involved_id', '=', $thread->forum_thread_id)
                        ->where('sender_id', '=', Auth::user()->id)
                        ->where('receiver_id', '=', $follower->user_id)
                        ->first();
                    // if empty, create
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->receiver_id = $follower->user_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'forum_reply';
                        $notification->involved_id = $thread->forum_thread_id;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                break;
            // group notifications
            case 'join_group' :
                $groupId = $settings['involved_id'];
                // get the teacher members of the group
                $members = GroupMember::where('group_id', '=', $groupId)
                    ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                    ->where('users.account_type', '=', 1)
                    ->get();
                // extract
                foreach($members as $member) {
                    // check if the notification already exists
                    $exists = Notification::where('notification_type', '=', 'join_group')
                        ->where('involved_id', '=', $groupId)
                        ->where('sender_id', '=', Auth::user()->id)
                        ->where('receiver_id', '=', $member->group_member_id)
                        ->first();
                    // create notification
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->receiver_id = $member->group_member_id;
                        $notification->sender_id = Auth::user()->id;
                        $notification->notification_type = 'join_group';
                        $notification->involved_id = $groupId;
                        $notification->notification_timestamp = $time;
                        $notification->date_added = $date;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->date_added = $date;
                        $exists->save();
                    }
                }

                break;
            case 'left_group' :
                break;
            case 'request_join_group' :
                $involvedId = $settings['involved_id'];
                $senderId = $settings['sender_id'];
                // get the owner of the group
                $group = Group::find($involvedId);
                // create
                $notification = new Notification;
                $notification->receiver_id = $group->owner_id;
                $notification->sender_id = $senderId;
                $notification->notification_type = 'request_join_group';
                $notification->involved_id = $group->group_id;
                $notification->notification_timestamp = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
            case 'accepted_join_group' :
                $notification = new Notification;
                $notification->receiver_id = $settings['user_id'];
                $notification->sender_id = Auth::user()->id;
                $notification->notification_type = 'accepted_join_group';
                $notification->involved_id = $settings['group_id'];
                $notification->notification_timestamp = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
            // likes
            case 'liked_post' :
                // get the post
                $post = Post::find($settings['involved_id']);
                // check if the notifcation already exists
                $exists = Notification::where('notification_type', '=', 'liked_post')
                    ->where('involved_id', '=', $post->post_id)
                    ->where('sender_id', '=', Auth::user()->id)
                    ->where('receiver_id', '=', $post->user_id)
                    ->first();
                // notification doesn't exists
                if(empty($exists)) {
                    // create the notification
                    $notification = new Notification;
                    $notification->receiver_id = $post->user_id;
                    $notification->sender_id = Auth::user()->id;
                    $notification->notification_type = 'liked_post';
                    $notification->involved_id = $post->post_id;
                    $notification->notification_timestamp = $time;
                    $notification->date_added = $date;
                    $notification->save();
                }

                // notification exists, just update
                if(!empty($exists)) {
                    $exists->seen = 'false';
                    $exists->notification_timestamp = $time;
                    $exists->date_added = $date;
                    $exists->save();
                }

                break;
            // posts
            case 'posted' :
                $post = Post::find($settings['involved_id']);
                // get the post recipients
                $recipients = PostRecipient::where('post_id', '=', $post->post_id)
                    ->get();
                // extract the recipients
                foreach($recipients as $key => $recipient) {
                    // make sure the recipient type is group
                    if($recipient->recipient_type == 'group') {
                        // get the members of the group
                        $members = GroupMember::where('group_id', '=', $recipient->recipient_id)
                            ->get();
                        // extract the members of the group
                        foreach($members as $member) {
                            if(Auth::user()->id == $member->group_member_id) continue;

                            $exists = Notification::where('notification_type', '=', 'posted')
                                ->where('involved_id', '=', $post->post_id)
                                ->where('receiver_id', '=', $member->group_member_id)
                                ->first();

                            // notification not yet created
                            if(empty($exists)) {
                                $notification = new Notification;
                                $notification->receiver_id = $member->group_member_id;
                                $notification->sender_id = $post->user_id;
                                $notification->notification_type = 'posted';
                                $notification->involved_id = $post->post_id;
                                $notification->notification_timestamp = $time;
                                $notification->date_added = $date;
                                $notification->save();
                            }

                            // notification already present
                            if(!empty($exists)) {
                                $exists->seen = 0;
                                $exists->notification_timestamp = $time;
                                $exists->date_added = $date;
                                $exists->save();
                            }
                        }
                    }

                    if($recipient->recipient_type == 'user') {
                        if(Auth::user()->id != $recipient->recipient_id) {
                            Notification::setup('direct_message', array(
                                'receiver_id' => $recipient->recipient_id,
                                'sender_id' => Auth::user()->id,
                                'involved_id' => $post->post_id));
                        }
                    }
                }

                break;
            // quiz
            case 'quiz_graded' :
                $quizTakerId = $settings['involved_id'];
                // quiz taker details
                $taker = QuizTaker::find($quizTakerId);
                // get quiz details
                $quiz = Quiz::find($taker->quiz_id);
                // create notification
                $notification = new Notification;
                $notification->receiver_id = $taker->user_id;
                $notification->sender_id = $quiz->user_id;
                $notification->notification_type = 'quiz_graded';
                $notification->involved_id = $quizTakerId;
                $notification->notification_timestamp = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
            case 'quiz_submitted' :
                $quizTakerId = $settings['involved_id'];
                // quiz taker details
                $taker = QuizTaker::find($quizTakerId);
                // get quiz details
                $quiz = Quiz::find($taker->quiz_id);
                // create notification
                $notification = new Notification;
                $notification->receiver_id = $quiz->user_id;
                $notification->sender_id = Auth::user()->id;
                $notification->notification_type = 'quiz_submitted';
                $notification->involved_id = $quizTakerId;
                $notification->notification_timestamp = $time;
                $notification->date_added = $date;
                $notification->save();
                break;
        }
    }

    // summarized notifications
    public static function unread()
    {
        // get the notifications for this user
        $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->where('seen', '=', 0)
            ->groupBy('involved_id', 'notification_type')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();

        $lists = new StdClass();
        $message = null;
        $link = null;
        foreach($notifications as $key => $notification) {
            $lists->$key = new StdClass();
            // get the last user of the notification
            $last = Notification::where('involved_id', '=', $notification->involved_id)
                ->where('notification_type', '=', $notification->notification_type)
                ->where('sender_id', '!=', Auth::user()->id)
                ->where('seen', '=', 0)
                ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                ->first();
            // get all notification
            $all = Notification::where('involved_id', '=', $notification->involved_id)
                ->where('notification_type', '=', $notification->notification_type)
                ->where('sender_id', '!=', Auth::user()->id)
                ->where('seen', '=', 0)
                ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                ->get();

            switch($notification->notification_type) {
                // assignment
                case 'assignment_graded' :
                    // get the taker id details
                    $taker = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                        ->first();
                    // get the assignment
                    $assignment = Assignment::where('assignment_id', '=', $taker->assignment_id)
                        ->leftJoin('users', 'assignments.user_id', '=', 'users.id')
                        ->first();
                    $message = $assignment->salutation.$assignment->firstname.' '.$assignment->lastname.
                        ' already graded your assignment';
                    $link = '/assignment-sheet/'.$assignment->assignment_id.'/'.$taker->post_id;
                    $icon = 'fa-star';
                    break;
                case 'assignment_submitted' :
                    // get details of the assignment
                    $response = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                        ->leftJoin('users', 'assignment_responses.user_id', '=', 'users.id')
                        ->first();
                    $message = $response->firstname.' '.$response->lastname.' submitted an assignment.';
                    $link = '/assignment-manager/'.$response->assignment_id.'/'.$response->post_id;
                    $icon = 'fa-share';
                    break;
                // comments
                case 'commented' :
                    // get the post
                    $post = Post::find($notification->involved_id);
                    $ownership = ($post->user_id == Auth::user()->id) ?
                        'your post' : 'a post that you have also commented.';
                    // all comments from the post
                    $all = Comment::where('post_id', '=', $post->post_id)
                        ->where('user_id', '!=', Auth::user()->id)
                        ->groupBy('user_id')
                        ->get();

                    if($all->isEmpty() || (!$all->isEmpty() && $all->count() == 1)) {
                        $message = $last->name.' commented on '.$ownership;
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others commented on '.$ownership;
                    }

                    $link = '/post/'.$post->post_id;
                    $icon = 'fa-comment';
                    break;
                // direct message
                case 'direct_message' :
                    break;
                // forum reply
                case 'forum_reply' :
                    // get the thread
                    $thread = ForumThread::find($notification->involved_id);
                    $ownership = ($thread->user_id == Auth::user()->id) ? 'your' : 'a';
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' replied on '.$ownership.' thread.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others replied on '.$ownership.' thread.';
                    }

                    $link = '/the-forum/thread/'.$thread->seo_url.'/'.$thread->forum_thread_id;
                    $icon = 'fa-comments';
                    break;
                // group notifications
                case 'join_group' :
                    $group = Group::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' joined your group '.$group->name.'.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others joined your group '.$group->name.'.';
                    }

                    $link = '/groups/'.$group->group_id.'/members';
                    $icon = 'fa-plus';
                    break;
                case 'left_group' :
                    break;
                case 'request_join_group' :
                    $group = Group::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' wants to join your group.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' wants to join your group.';
                    }

                    $link = '/groups/'.$group->group_id.'/join-requests';
                    $icon = 'fa-group';
                    break;
                case 'accepted_join_group' :
                    // get the group details
                    $group = Group::find($notification->involved_id);
                    // get the sender details
                    $sender = User::find($notification->sender_id);
                    $message = $sender->salutation.' '.$sender->name.
                        ' accepted your request to join the group '.$group->group_name;
                    $link = '/groups/'.$group->group_id;
                    $icon = 'fa-check';
                    break;
                // likes
                case 'liked_post' :
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' liked your post';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' others liked your post.';
                    }

                    $link = '/post/'.$notification->involved_id;
                    $icon = 'fa-thumbs-up';
                    break;
                // posts
                case 'posted' :
                    $message = $last->name.' posted something on your group.';
                    $link = '/post/'.$notification->involved_id;
                    $icon = 'fa-pencil';
                    break;
                // quiz
                case 'quiz_graded' :
                    $taker = QuizTaker::find($notification->involved_id);
                    $message = $last->name.' already graded your quiz';
                    $link = '/quiz-result/'.$taker->quiz_id.'/'.$taker->post_id;
                    $icon = 'fa-star';
                    break;
                case 'quiz_submitted' :
                    $taker = QuizTaker::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' submitted a quiz.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' submitted a quiz.';
                    }

                    $link = '/quiz-manager/'.$taker->quiz_id.'/'.$taker->post_id;
                    $icon = 'fa-clipboard';
                    break;
            }

            $lists->$key->message = $message;
            $lists->$key->link = $link;
            $lists->$key->icon = $icon;
        }

        return ($notifications->isEmpty()) ? null : $lists;
    }

    public static function everything()
    {
        // get notifications per date
        $grouped = Notification::where('receiver_id', '=', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->groupBy('date_added')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();
        $lists = new StdClass();
        foreach($grouped as $key => $group) {
            // get the notifications for this user
            $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
                ->where('sender_id', '!=', Auth::user()->id)
                ->where('date_added', '=', $group->date_added)
                ->groupBy('involved_id', 'notification_type')
                ->orderBy('notification_timestamp', 'DESC')
                ->get();

            $alerts = new StdClass();
            $message = null;
            $link = null;
            foreach($notifications as $key2 => $notification) {
                $alerts->$key2 = new StdClass();
                // get the last user of the notification
                $last = Notification::where('involved_id', '=', $notification->involved_id)
                    ->where('notification_type', '=', $notification->notification_type)
                    ->where('sender_id', '!=', Auth::user()->id)
                    ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                    ->first();
                // get all notification
                $all = Notification::where('involved_id', '=', $notification->involved_id)
                    ->where('notification_type', '=', $notification->notification_type)
                    ->where('sender_id', '!=', Auth::user()->id)
                    ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                    ->get();

                switch($notification->notification_type) {
                    // assignment
                    case 'assignment_graded' :
                        // get the taker id details
                        $taker = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                            ->first();
                        // get the assignment
                        $assignment = Assignment::where('assignment_id', '=', $taker->assignment_id)
                            ->leftJoin('users', 'assignments.user_id', '=', 'users.id')
                            ->first();
                        $message = $assignment->salutation.$assignment->firstname.' '.$assignment->lastname.
                            ' already graded your assignment';
                        $link = '/assignment-sheet/'.$assignment->assignment_id.'/'.$taker->post_id;
                        $icon = 'fa-star';
                        break;
                    case 'assignment_submitted' :
                        // get details of the assignment
                        $response = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                            ->leftJoin('users', 'assignment_responses.user_id', '=', 'users.id')
                            ->first();
                        $name = $response->name;
                        $message = $name.' submitted an assignment.';
                        $link = '/assignment-manager/'.$response->assignment_id.'/'.$response->post_id;
                        $icon = 'fa-share';
                        break;
                    // comments
                    case 'commented' :
                        // get the post
                        $post = Post::find($notification->involved_id);
                        $ownership = ($post->user_id == Auth::user()->id) ?
                            'your post' : 'a post that you have also commented.';
                        // override the all
                        $all = Notification::where('involved_id', '=', $notification->involved_id)
                            ->where('notification_type', '=', $notification->notification_type)
                            ->where('sender_id', '!=', $last->id)
                            ->where('sender_id', '!=', Auth::user()->id)
                            ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                            ->get();

                        if($all->isEmpty() || (!$all->isEmpty() && $all->count() == 1)) {
                            $message = $last->name.' commented on '.$ownership;
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).
                                ' others commented on '.$ownership;
                        }

                        $link = '/post/'.$post->post_id;
                        $icon = 'fa-comment';
                        break;
                    // direct message
                    case 'direct_message' :
                        break;
                    // forum reply
                    case 'forum_reply' :
                        // get the thread
                        $thread = ForumThread::find($notification->involved_id);
                        $ownership = ($thread->user_id == Auth::user()->id) ? 'your' : 'a';
                        if(empty($all) || (!empty($all) && $all->count() == 1)) {
                            $message = $last->name.' replied on '.$ownership.' thread.';
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).
                                ' others replied on '.$ownership.' thread.';
                        }

                        $link = '/the-forum/thread/'.$thread->seo_url.'/'.$thread->forum_thread_id;
                        $icon = 'fa-comments';
                        break;
                    // group notifications
                    case 'join_group' :
                        $group = Group::find($notification->involved_id);
                        if(empty($all) || (!empty($all) && $all->count() == 1)) {
                            $message = $last->name.' joined your group '.$group->name.'.';
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).
                                ' others joined your group '.$group->name.'.';
                        }

                        $link = '/groups/'.$group->group_id.'/members';
                        $icon = 'fa-plus';
                        break;
                    case 'left_group' :
                        break;
                    case 'request_join_group' :
                        $group = Group::find($notification->involved_id);
                        if(empty($all) || (!empty($all) && $all->count() == 1)) {
                            $message = $last->name.' wants to join your group.';
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).' wants to join your group.';
                        }

                        $link = '/groups/'.$group->group_id.'/join-requests';
                        $icon = 'fa-group';
                        break;
                    case 'accepted_join_group' :
                        // get the group details
                        $group = Group::find($notification->involved_id);
                        // get the sender details
                        $sender = User::find($notification->sender_id);
                        $message = $sender->salutation.' '.$sender->name.
                            ' accepted your request to join the group '.$group->group_name;
                        $link = '/groups/'.$group->group_id;
                        $icon = 'fa-check';
                        break;
                    // likes
                    case 'liked_post' :
                        if(empty($all) || (!empty($all) && $all->count() == 1)) {
                            $message = $last->name.' liked your post';
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).' others liked your post.';
                        }

                        $link = '/post/'.$notification->involved_id;
                        $icon = 'fa-thumbs-up';
                        break;
                    // posts
                    case 'posted' :
                        $message = $last->name.' posted something on your group.';
                        $link = '/post/'.$notification->involved_id;
                        $icon = 'fa-pencil';
                        break;
                    // quiz
                    case 'quiz_graded' :
                        $taker = QuizTaker::find($notification->involved_id);
                        $message = $last->name.' already graded your quiz';
                        $link = '/quiz-result/'.$taker->quiz_id.'/'.$taker->post_id;
                        $icon = 'fa-star';
                        break;
                    case 'quiz_submitted' :
                        $taker = QuizTaker::find($notification->involved_id);
                        if(empty($all) || (!empty($all) && $all->count() == 1)) {
                            $message = $last->name.' submitted a quiz.';
                        }

                        if(!empty($all) && $all->count() > 1) {
                            // count the number of users who joined
                            $message = $last->name.' and '.($all->count() - 1).' submitted a quiz.';
                        }

                        $link = '/quiz-manager/'.$taker->quiz_id.'/'.$taker->post_id;
                        $icon = 'fa-clipboard';
                        break;
                }

                $alerts->$key2->message = $message;
                $alerts->$key2->link = $link;
                $alerts->$key2->icon = $icon;
            }

            $lists->$key = new StdClass();
            $lists->$key->date = $group->date_added;
            $lists->$key->notifications = $alerts;
        }

        return $lists;
    }

    public static function seen()
    {
        // get the notifications for this user
        $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->groupBy('involved_id', 'notification_type')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();

        $lists = new StdClass();
        $message = null;
        $link = null;
        foreach($notifications as $key => $notification) {
            $lists->$key = new StdClass();
            // get the last user of the notification
            $last = Notification::where('involved_id', '=', $notification->involved_id)
                ->where('notification_type', '=', $notification->notification_type)
                ->where('sender_id', '!=', Auth::user()->id)
                ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                ->first();
            // get all notification
            $all = Notification::where('involved_id', '=', $notification->involved_id)
                ->where('notification_type', '=', $notification->notification_type)
                ->where('sender_id', '!=', Auth::user()->id)
                ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
                ->get();

            switch($notification->notification_type) {
                // assignment
                case 'assignment_graded' :
                    // get the taker id details
                    $taker = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                        ->first();
                    // get the assignment
                    $assignment = Assignment::where('assignment_id', '=', $taker->assignment_id)
                        ->leftJoin('users', 'assignments.user_id', '=', 'users.id')
                        ->first();
                    $message = $assignment->salutation.$assignment->firstname.' '.$assignment->lastname.
                        ' already graded your assignment';
                    $link = '/assignment-sheet/'.$assignment->assignment_id.'/'.$taker->post_id;
                    $icon = 'fa-star';
                    break;
                case 'assignment_submitted' :
                    // get details of the assignment
                    $response = AssignmentResponse::where('assignment_response_id', '=', $notification->involved_id)
                        ->leftJoin('users', 'assignment_responses.user_id', '=', 'users.id')
                        ->first();
                    $message = $response->firstname.' '.$response->lastname.' submitted an assignment.';
                    $link = '/assignment-manager/'.$response->assignment_id.'/'.$response->post_id;
                    $icon = 'fa-share';
                    break;
                // comments
                case 'commented' :
                    // get the post
                    $post = Post::find($notification->involved_id);
                    $ownership = ($post->user_id == Auth::user()->id) ?
                        'your post' : 'a post that you have also commented.';
                    // all comments from the post
                    $all = Comment::where('post_id', '=', $post->post_id)
                        ->where('user_id', '!=', Auth::user()->id)
                        ->groupBy('user_id')
                        ->get();

                    if($all->isEmpty() || (!$all->isEmpty() && $all->count() == 1)) {
                        $message = $last->name.' commented on '.$ownership;
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others commented on '.$ownership;
                    }

                    $link = '/post/'.$post->post_id;
                    $icon = 'fa-comment';
                    break;
                // direct message
                case 'direct_message' :
                    break;
                // forum reply
                case 'forum_reply' :
                    // get the thread
                    $thread = ForumThread::find($notification->involved_id);
                    $ownership = ($thread->user_id == Auth::user()->id) ? 'your' : 'a';
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' replied on '.$ownership.' thread.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others replied on '.$ownership.' thread.';
                    }

                    $link = '/the-forum/thread/'.$thread->seo_url.'/'.$thread->forum_thread_id;
                    $icon = 'fa-comments';
                    break;
                // group notifications
                case 'join_group' :
                    $group = Group::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' joined your group '.$group->name.'.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).
                            ' others joined your group '.$group->name.'.';
                    }

                    $link = '/groups/'.$group->group_id.'/members';
                    $icon = 'fa-plus';
                    break;
                case 'left_group' :
                    break;
                case 'request_join_group' :
                    $group = Group::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' wants to join your group.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' wants to join your group.';
                    }

                    $link = '/groups/'.$group->group_id.'/join-requests';
                    $icon = 'fa-group';
                    break;
                case 'accepted_join_group' :
                    // get the group details
                    $group = Group::find($notification->involved_id);
                    // get the sender details
                    $sender = User::find($notification->sender_id);
                    $message = $sender->salutation.' '.$sender->name.
                        ' accepted your request to join the group '.$group->group_name;
                    $link = '/groups/'.$group->group_id;
                    $icon = 'fa-check';
                    break;
                // likes
                case 'liked_post' :
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' liked your post';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' others liked your post.';
                    }

                    $link = '/post/'.$notification->involved_id;
                    $icon = 'fa-thumbs-up';
                    break;
                // posts
                case 'posted' :
                    $message = $last->name.' posted something on your group.';
                    $link = '/post/'.$notification->involved_id;
                    $icon = 'fa-pencil';
                    break;
                // quiz
                case 'quiz_graded' :
                    $taker = QuizTaker::find($notification->involved_id);
                    $message = $last->name.' already graded your quiz';
                    $link = '/quiz-result/'.$taker->quiz_id.'/'.$taker->post_id;
                    $icon = 'fa-star';
                    break;
                case 'quiz_submitted' :
                    $taker = QuizTaker::find($notification->involved_id);
                    if(empty($all) || (!empty($all) && $all->count() == 1)) {
                        $message = $last->name.' submitted a quiz.';
                    }

                    if(!empty($all) && $all->count() > 1) {
                        // count the number of users who joined
                        $message = $last->name.' and '.($all->count() - 1).' submitted a quiz.';
                    }

                    $link = '/quiz-manager/'.$taker->quiz_id.'/'.$taker->post_id;
                    $icon = 'fa-clipboard';
                    break;
            }

            $lists->$key->message = $message;
            $lists->$key->link = $link;
            $lists->$key->icon = $icon;
        }

        return ($notifications->isEmpty()) ? null : $lists;
    }
}
