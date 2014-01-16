<?php //-->
class AjaxNotificationController extends BaseController
{
    public function fetch()
    {
        $notifications = Notification::unread();
        // no unread notifications
        if(empty($notifications)) {
            // fetch the previous notifications
            $notifications = null; // for the meantime, let's set it to null
        }

        // there are unread notifications
        if(!empty($notifications)) {
            // update seen notifications
            $seens = Notification::where('receiver_id', '=', Auth::user()->id)
                ->where('sender_id', '!=', Auth::user()->id)
                ->where('seen', '=', 0)
                ->get();
            // update!
            foreach($seens as $seen) {
                $unseen = Notification::find($seen->notification_id);
                $unseen->seen = 1;
                $unseen->save();
            }
        }

        return View::make('ajax.notifications.lists')
            ->with('notifications', $notifications);
    }

    public function fetchCount()
    {
        $notifications = Notification::where('receiver_id', '=', Auth::user()->id)
            ->where('sender_id', '!=', Auth::user()->id)
            ->where('seen', '=', 0)
            ->groupBy('involved_id', 'notification_type')
            ->orderBy('notification_timestamp', 'DESC')
            ->get();

        return Response::json(array('count' => $notifications->count()));
    }
}
