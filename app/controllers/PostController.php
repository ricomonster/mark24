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
        $post = Post::getPost($postId);

        if(empty($post)) {
            // redirec to 404 page
            return View::make('templates.fourohfour');
        }

        return View::make('post.showpost')
            ->with('post', $post);
    }
}
