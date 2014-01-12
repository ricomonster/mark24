<?php //-->

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';

    public static function setup($type, $settings)
    {
        $time = time();
        switch($type) {
            // assignment
            case 'assignment_graded' :
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
                // check if there's already an existing notification
                $exists = Notification::where('notification_type', '=', 'comment')
                    ->where('involved_id', '=', $post->post_id)
                    ->where('receiver_id', '=', Auth::user()->id)
                    ->first();

                // check if the owner of the post is the current user
                if(Auth::user()->id != $post->user_id && empty($exists)) {
                    // create the notification
                    $notification = new Notification;
                    $notification->receiver_id = Auth::user()->id;
                    $notification->sender_id = $post->user_id;
                    $notification->notification_type = 'commented';
                    $notification->involved_id = $post->post_id;
                    $notification->notification_timestamp = $time;
                    $notification->save();
                }

                if(!empty($exists)) {
                    // update the notification
                    $exists->seen = 0;
                    $exists->notification_timestamp = $time;
                    $exists->save();
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
                        $notification->sender_id = $post->user_id;
                        $notification->notification_type = 'commented';
                        $notification->involved_id = $post->post_id;
                        $notification->notification_timestamp = $time;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->save();
                    }
                }

                // extract the recipients
                foreach($recipients as $recipient) {
                    // check if the recipient is group
                    if($recipient->recipient_type != 'group') continue;
                    // get the group members
                    $members = GroupMember::where('group_id', '=', $recipient->recipient_id)
                        ->get();
                    // extract the members of the group
                    foreach($members as $member) {
                        if(Auth::user()->id == $member->group_member_id) continue;

                        $exists = Notification::where('notification_type', '=', 'commented')
                            ->where('involved_id', '=', $post->post_id)
                            ->where('receiver_id', '=', $member->group_member_id)
                            ->first();

                        // notification not yet created
                        if(empty($exists)) {
                            $notification = new Notification;
                            $notification->receiver_id = $member->group_member_id;
                            $notification->sender_id = $post->user_id;
                            $notification->notification_type = 'commented';
                            $notification->involved_id = $post->post_id;
                            $notification->notification_timestamp = $time;
                            $notification->save();
                        }

                        // notification already present
                        if(!empty($exists)) {
                            $exists->seen = 0;
                            $exists->notification_timestamp = $time;
                            $exists->save();
                        }
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
                    $notification->save();
                }

                // if already exists update settings
                if(!empty($exists)) {
                    $exists->seen = 0;
                    $exists->notification_timestamp = $time;
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
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
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
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
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
                        $notification->save();
                    }

                    // not empty, update
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
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
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 0;
                        $exists->notification_timestamp = $time;
                        $exists->save();
                    }
                }

                break;
            case 'left_group' :
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
                    $notification->save();
                }

                // notification exists, just update
                if(!empty($exists)) {
                    $notification->seen = 'false';
                    $notification->notification_timestamp = $time;
                    $notification->save();
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
                                $notification->save();
                            }

                            // notification already present
                            if(!empty($exists)) {
                                $exists->seen = 0;
                                $exists->notification_timestamp = $time;
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
                $quizId = $settings['quiz_id'];
                // get quiz details
                $quiz = Quiz::find($quizId);
                // create notification
                $notification = new Notification;
                $notification->receiver_id = $quiz->user_id;
                $notification->sender_id = Auth::user()->id;
                $notification->notification_type = 'quiz_graded';
                $notification->involved_id = $quizTakerId;
                $notification->notification_timestamp = $time;
                $notification->save();
                break;
            case 'quiz_submitted' :
                break;
        }
    }

    public static function lists()
    {
        // get the notifications for this user
        $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
            ->groupBy('involved_id')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();
        $lists = new StdClass();
        foreach($notifications as $notification) {
            switch($notification->notification_type) {
                // assignment
                case 'assignment_graded' :
                    break;
                case 'assignment_submitted' :
                    break;
                // comments
                case 'commented' :
                    break;
                // direct message
                case 'direct_message' :
                    break;
                // forum reply
                case 'forum_reply' :
                    break;
                // group notifications
                case 'join_group' :
                    break;
                case 'left_group' :
                    break;
                // likes
                case 'liked_post' :
                    break;
                // posts
                case 'posted' :
                    break;
                // quiz
                case 'quiz_graded' :
                    break;
                case 'quiz_submitted' :
                    break;
            }
        }
    }
}
