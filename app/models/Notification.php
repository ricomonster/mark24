<?php //-->

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    
    public static function createNotification(
        $notificationReferenceId,
        $notificationType,
        $message)
    {
        $recipients = array();
        
        switch($notificationType) {
            case 'comment' :
                // get first the post owner
                $post = Post::find($notificationReferenceId);
                $recipients[] = $post->user_id;
                // check and get if there are also other commenters
                $commenters = Comment::where('post_id', '=', $post->post_id)
                    ->get();
                // extract the id of the users
                break;
            case 'direct_message' :
                break;
            case 'forum_reply' :
                break;
            case 'join_group' :
                break;
            case 'leave_group' :
                break;
            case 'post' :
                break;
            case 'quiz_graded' :
                break;
            case 'quiz_submitted' :
                break;
        }
        
        $notification                               = new Notification;
        $notification->user_id                      = $recipientId;
        $notification->notification_type            = $notificationType;
        $notification->notification_reference_id    = $notificationReferenceId;
        $notification->message                      = $message;
        $notification->notification_timestamp       = time();
        $notification->save();
    }
}