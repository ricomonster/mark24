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
        $notifications = Notification::getNotifications();
        echo '<pre>';
        print_r($notifications);
        echo '</pre>';
    }
}
