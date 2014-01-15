<?php //-->

class NotificationController extends BaseController
{
    /**
     * Shows all the notifications of the user
     *
     *
     */
    public function index()
    {
        // this will show the unsummarized version of the notifications
        $notifications = Notification::unread();
        echo '<pre>';
        print_r($notifications);
        echo '</pre>';
    }
}
