<?php //-->

class PostController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('auth');
    }

    /**
     * This will show the post
     *
     * @param int
     * @return Eloquent Object
     */
    public function showPost($postId)
    {
        // check if the postId is valid or existing
        $post = Post::where('post_id', '=', $postId)
            ->leftJoin('users', 'posts.user_id', '=', 'users.id')
            ->first();

        if(empty($post)) {
            // redirec to 404 page
            return View::make('templates.fourohfour');
        }

        // get comments
        $comments = Comment::where('post_id', '=', $post->post_id)
            ->get();

        return View::make('post.showpost')
            ->with('post', $post)
            ->with('comments', $comments);
    }
}
