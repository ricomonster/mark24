<?php //-->

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';

    public static function createNotification($notificationReferenceId, $notificationType)
    {
        $recipients = array();
        $time = time();

        switch($notificationType) {
            case 'comment' :
                // get first the necessary details
                $post = Post::find($notificationReferenceId);
                // get the comments
                $comments = Comment::where('post_id', '=', $post->post_id)
                    ->get();
                // get the recipients of the post
                $recipients = PostRecipient::where('post_id', '=', $post->post_id)
                    ->get();
                // check if there's already an existing notification
                $exists = Notification::where('notification_type', '=', 'comment')
                    ->where('notification_reference_id', '=', $post->post_id)
                    ->where('recipient_id', '=', Auth::user()->id)
                    ->first();

                // check if the owner of the post is the current user
                if(Auth::user()->id != $post->user_id && empty($exists)) {
                    // create the notification
                    $notification = new Notification;
                    $notification->recipient_id = $post->user_id;
                    $notification->notification_type = 'comment';
                    $notification->notification_reference_id = $post->post_id;
                    $notification->notification_timestamp = $time;
                    $notification->save();
                }

                if(!empty($exists)) {
                    // update the notification
                    $exists->seen = 'false';
                    $exists->notification_timestamp = $time;
                    $exists->save();
                }

                // extract the userid of the commenters
                foreach($comments as $comment) {
                    // check if the commenter is also the current user
                    if(Auth::user()->id == $comment->user_id) continue;
                    // check if there's already a notification for the user
                    $exists = Notification::where('notification_type', '=', 'comment')
                        ->where('notification_reference_id', '=', $post->post_id)
                        ->where('recipient_id', '=', $comment->user_id)
                        ->first();

                    // notification not yet created
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->recipient_id = $comment->user_id;
                        $notification->notification_type = 'comment';
                        $notification->notification_reference_id = $post->post_id;
                        $notification->notification_timestamp = $time;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 'false';
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

                        $exists = Notification::where('notification_type', '=', 'comment')
                            ->where('notification_reference_id', '=', $post->post_id)
                            ->where('recipient_id', '=', $member->group_member_id)
                            ->first();

                        // notification not yet created
                        if(empty($exists)) {
                            $notification = new Notification;
                            $notification->recipient_id = $member->group_member_id;
                            $notification->notification_type = 'comment';
                            $notification->notification_reference_id = $post->post_id;
                            $notification->notification_timestamp = $time;
                            $notification->save();
                        }

                        // notification already present
                        if(!empty($exists)) {
                            $exists->seen = 'false';
                            $exists->notification_timestamp = $time;
                            $exists->save();
                        }
                    }
                }

                break;
            case 'direct_message' :
                break;
            case 'forum_reply' :
                // get the forum thread
                $thread = ForumThread::find($notificationReferenceId);
                // get the commenters of the thread
                $replies = ForumThreadReply::where('forum_thread_id', '=', $thread->forum_thread_id)->get();
                // check if there's already an existing notification
                $exists = Notification::where('notification_type', '=', 'forum_reply')
                    ->where('notification_reference_id', '=', $thread->forum_thread_id)
                    ->where('recipient_id', '=', $thread->user_id)
                    ->first();
                // check if the owner of the post is the current user
                if(Auth::user()->id != $thread->user_id && empty($exists)) {
                    // create the notification to notify the thread owner
                    // that somebody replied to the created the user created
                    $notification = new Notification;
                    $notification->recipient_id = $thread->user_id;
                    $notification->notification_type = 'comment';
                    $notification->notification_reference_id = $thread->forum_thread_id;
                    $notification->notification_timestamp = $time;
                    $notification->save();
                }

                if(!empty($exists)) {
                    // update the notification
                    $exists->seen = 'false';
                    $exists->notification_timestamp = $time;
                    $exists->save();
                }

                // get the users who replied to the thread

                break;
            case 'join_group' :
                if(!is_array($notificationReferenceId)) continue;
                // extract first the contents
                $referenceId = $notificationReferenceId['reference_id'];
                $referralId = $notificationReferenceId['referral_id'];

                // get the teachers of the group
                $members = GroupMember::where('group_id', '=', $referralId)
                    ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                    ->where('users.account_type', '=', 1)
                    ->get();
                // extract the members
                foreach($members as $member) {
                    // check if the notification already exists
                    $exists = Notification::where('notification_type', '=', 'join_group')
                        ->where('notification_reference_id', '=', $referenceId)
                        ->where('referral_id', '=', $referralId)
                        ->where('recipient_id', '=', $member->group_member_id)
                        ->first();

                    // notification not yet created
                    if(empty($exists)) {
                        $notification = new Notification;
                        $notification->recipient_id = $member->group_member_id;
                        $notification->notification_type = 'join_group';
                        $notification->notification_reference_id = $referenceId;
                        $notification->referral_id = $referralId;
                        $notification->notification_timestamp = $time;
                        $notification->save();
                    }

                    // notification already present
                    if(!empty($exists)) {
                        $exists->seen = 'false';
                        $exists->notification_timestamp = $time;
                        $exists->save();
                    }
                }

                break;
            case 'leave_group' :
                break;
            case 'post' :
                // get the post first
                $post = Post::find($notificationReferenceId);
                // get the post recipients
                $recipients = PostRecipient::where('post_id', '=', $post->post_id)
                    ->get();
                // extract the recipients
                foreach($recipients as $key => $recipient) {
                    // make sure the recipient type is group
                    if($recipient->recipient_type != 'group') continue;
                    // get the members of the group
                    $members = GroupMember::where('group_id', '=', $recipient->recipient_id)
                        ->get();
                    // extract the members of the group
                    foreach($members as $member) {
                        if(Auth::user()->id == $member->group_member_id) continue;

                        $exists = Notification::where('notification_type', '=', 'post')
                            ->where('notification_reference_id', '=', $post->post_id)
                            ->where('recipient_id', '=', $member->group_member_id)
                            ->first();

                        // notification not yet created
                        if(empty($exists)) {
                            $notification = new Notification;
                            $notification->recipient_id = $member->group_member_id;
                            $notification->notification_type = 'post';
                            $notification->notification_reference_id = $post->post_id;
                            $notification->notification_timestamp = $time;
                            $notification->save();
                        }

                        // notification already present
                        if(!empty($exists)) {
                            $exists->seen = 'false';
                            $exists->notification_timestamp = $time;
                            $exists->save();
                        }
                    }
                }

                break;
            case 'quiz_graded' :
                break;
            case 'quiz_submitted' :
                break;
        }

        return;
    }

    public static function getNotifications()
    {
        $messages = array();

        $notifications = Notification::where('recipient_id', '=', Auth::user()->id)
            ->orderBy('notification_timestamp', 'ASC')
            ->get();
        // extract the notifications
        foreach($notifications as $key => $notification) {
            switch($notification->notification_type) {
                case 'comment' :
                    // get the post
                    $post = Post::find($notification->notification_reference_id);
                    // get the commenters
                    $commenters = Comment::where('post_id', '=', $post->post_id)
                        ->leftJoin('users', 'comments.user_id', '=', 'users.id')
                        ->groupBy('comments.user_id')
                        ->orderBy('comments.comment_timestamp', 'ASC')
                        ->get();
                    $commenterCount = $commenters->count();
                    // extract the commenters
                    foreach($commenters as $key2 => $commenter) {
                        // check if there is only one commenter
                        if($commenterCount == 1) {
                            $user = $commenter;
                            // prep the message
                            $message = $user->salutation.$user->name
                                .' commented on %s post.';
                        }

                        // commenters are more than one
                        // get the last one who commented except the current user
                        if($commenterCount != 1 || $commenterCount == $key2 - 1) {
                            $user = $commenter;
                            // prep the message
                            $commenters = $user->salutation.$user->name.' and '
                                .($commenterCount - 1);
                        }
                    }

                    // set up the message
                    $messages[$key]['message'] = sprintf(
                        '%s commented on %s post',
                        $commenters,
                        (($post->user_id == Auth::user()->id) ? 'your' : 'on'));
                    // set up the url of the notification
                    $messages[$key]['link'] = '/post/'.$post->post_id;
                    break;
                case 'direct_message' :
                    break;
                case 'forum_reply' :
                    break;
                case 'join_group' :
                    // get the user
                    $user = User::find($notification->notification_reference_id);
                    // get the group
                    $group = Group::find($notification->referral_id);
                    $messages[$key]['message'] = $user->salutation.$user->name
                        .' joined '.$group->group_name.'.';
                    $messages[$key]['link'] = null;
                    break;
                case 'leave_group' :
                    break;
                case 'post' :
                    // get the post
                    $post = Post::find($notification->notification_reference_id);
                    $user = User::find($post->user_id);

                    $messages[$key]['message'] = $user->salutation.$user->name
                        .' posted something on your group.';
                    // set up the url of the notification
                    $messages[$key]['link'] = '/post/'.$post->post_id;
                    break;
                case 'quiz_graded' :
                    break;
                case 'quiz_submitted' :
                    break;
            }
        }

        return $messages;
    }
}
