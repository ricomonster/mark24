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
        // this will show all notifications
        $notifications = Notification::everything();

        return View::make('notifications.index')
            ->with('notifications', $notifications);
    }
}
