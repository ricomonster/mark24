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

        // get the count of likes
        $likers = Like::where('post_id', '=', $postId)
            ->get()
            ->count();

        return Response::json(array(
            'error' => false,
            'like_count' => $likers));
    }
}
