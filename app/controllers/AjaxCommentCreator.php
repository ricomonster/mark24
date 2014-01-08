<?php //-->

class AjaxCommentCreator extends BaseController
{
    public function postCreateComment()
    {
        $postId     = Input::get('post_id');
        $comment    = Input::get('comment');

        // save the comment
        $newComment                      = new Comment;
        $newComment->user_id            = Auth::user()->id;
        $newComment->post_id            = $postId;
        $newComment->comment            = $comment;
        $newComment->comment_timestamp  = time();
        $newComment->save();

        // create notification

        // get the details of the comment
        $comment = Comment::where('comment_id', '=', $newComment->comment_id)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->first();
        // return with the template
        return View::make('ajax.commentcreator.comment')
            ->with('comment', $comment);
    }
}
