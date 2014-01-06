<?php //-->

class AjaxLikeController extends BaseController
{
    public function likePost()
    {
        $postId = Input::get('post_id');
        // insert to database
        $like = new Like;
        $like->user_id = Auth::user()->id;
        $like->post_id = $postId;
        $like->save();

        // get the likers
        $likers = Helper::likes($postId);

        return Response::json(array(
            'error' => false,
            'likers' => $likers));
    }

    public function unlikePost()
    {
        $postId = Input::get('post_id');
        // delete data from database
        Like::where('post_id', '=', $postId)
            ->where('user_id', '=', Auth::user()->id)
            ->first()
            ->delete();
        // get the like count
        $likeCount = Like::where('post_id', '=', $postId)
            ->get()
            ->count();
        // get the likers
        $likers = Helper::likes($postId);
        return Response::json(array(
            'error' => false,
            'like_count' => $likeCount,
            'likers' => $likers));
    }
}
