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

                // check if the owner of the post is the current user
                if(Auth::user()->id != $post->user_id) {
                    // create the notification
                    $notification = new Notification;
                    $notification->recipient_id = $post->user_id;
                    $notification->notification_type = 'comment';
                    $notification->notification_reference_id = $post->post_id;
                    $notification->notification_timestamp = $time;
                    $notification->save();
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
                break;
            case 'join_group' :
                if(!is_array($notificationReferenceId)) continue;
                // extract first the contents
                $referenceId = $notificationReferenceId['reference_id'];
                $referralId = $notificationReferenceId['referral_id'];

                // get the teachers of the group
                $members = GroupMember::where('group_id', '=', $referralId)
                    ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                    ->get();
                // extract the members
                if($members as $member) {
                    if($member->account_type == 2) continue;
                    if($member->id != Auth::user()->id) continue;
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
                        $notification->referral_id = $referralId
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

    public function getNotifications()
    {
        $messages = array();

        $notifications = Notification::where('recipient_id', '=', Auth::user()->id)
            ->orderBy('notification_timestamp', 'DESC')
            ->get();
        // extract the notifications
    }
}
