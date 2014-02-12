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

    public function updateThread()
    {
        $input              = Input::all();
        $threadId           = $input['thread-id'];
        $threadTitle        = $input['thread-title'];
        $threadDescription  = $input['thread-description'];

        // save
        $thread                     = ForumThread::find($threadId);
        $thread->title              = $threadTitle;
        $thread->forum_description  = $threadDescription;
        $thread->save();

        return Response::json(array(
            'error' => false));
    }

    public function updateThreadReply()
    {
        $input              = Input::all();
        $replyId            = $input['reply-id'];
        $replyDescription   = $input['reply-description'];

        // save data
        $reply          = ForumThreadReply::find($replyId);
        $reply->reply   = $replyDescription;
        $reply->save();

        return Response::json(array('error' => false));
    }
}
