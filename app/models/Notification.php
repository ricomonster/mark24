<?php //-->

class Notification extends Eloquent
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    
    public static function createNotification($notificationReferenceId, $notificationType)
    {
        $recipients = array();
        
        switch($notificationType) {
            case 'comment' :
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
        
        return;
    }
}