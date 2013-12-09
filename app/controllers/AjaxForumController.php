<?php //-->

class AjaxForumController extends BaseController
{
    public function followThread()
    {
        $threadId = Input::get('thread_id');

        // add the thread to the list of being followed
        $newFollowed = new FollowedForumThread;
        $newFollowed->user_id = Auth::user()->id;
        $newFollowed->forum_thread_id = $threadId;
        $newFollowed->save();

        return Response::json(array('error' => false));
    }

    public function unfollowThread()
    {
        $threadId = Input::get('thread_id');

        // look for the thread
        $thread = FollowedForumThread::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $threadId)
            ->delete();

        // return a response sir!
        return Response::json(array('error' => false));
    }
}
