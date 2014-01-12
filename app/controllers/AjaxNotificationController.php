<?php //-->
class AjaxNotificationController extends BaseController
{
    public function fetch()
    {
        $notifications = Notification::summarized();
        return View::make('ajax.notifications.lists')
            ->with('notifications', $notifications);
    }

    public function fetchCount()
    {
        $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->groupBy('involved_id', 'notification_type')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();

        return Response::json(array('count' => $notifications->count()));
    }
}
