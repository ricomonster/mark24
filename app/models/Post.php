<?php //-->

class Post extends Eloquent {
    protected $table        = 'posts';
    protected $primaryKey   = 'post_id';

    public static function getAllPosts() {
        // get first posts for the current user
        $details = null;
        $groupIds = Group::getMyGroupsId();
        if(!empty($groupIds)) {
            $posts = PostRecipient::orWhere('posts.user_id', '=', Auth::user()->id)
                ->orWhere(function($query) {
                    $query->whereIn('post_recipients.recipient_id', Group::getMyGroupsId())
                        ->where('post_recipients.recipient_type', '=', 'group');
                })
                ->orWhere(function($query) {
                    $query->where('post_recipients.recipient_id', '=', Auth::user()->id)
                        ->where('post_recipients.recipient_type', '=', 'user');
                })
                ->leftJoin('posts', 'post_recipients.post_id', '=', 'posts.post_id')
                ->groupBy('posts.post_id')
                ->orderBy('posts.post_id', 'DESC')
                ->get();

            $details = new StdClass();
            foreach($posts as $key => $post) {
                $details->$key = $post;
                $details->$key->recipients = PostRecipient::getRecipients($post->post_id);
                $details->$key->user = User::find($post['user_id']);
                // create object for the likes
                $likes = new StdClass();
                $likes->count = Like::where('post_id', '=', $post['post_id'])
                    ->get()->count();
                $likes->likers = Like::where('post_id', '=', $post['post_id'])
                    ->leftJoin('users', 'likes.user_id', '=', 'users.id')
                    ->get();
                // create object for the comments
                $comments = new StdClass();
                $comments = Comment::where('post_id', '=', $post['post_id'])
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->orderBy('comments.comment_id', 'ASC')
                    ->get();

                // assign the objects
                $details->$key->likes = $likes;
                $details->$key->comments = $comments;
            }

            return (empty($details)) ? null : $details;
        }

        return false;
    }

    public static function getGroupPosts($groupId) {
        $groupPosts = PostRecipient::where('post_recipients.recipient_id', '=', $groupId)
            ->where('post_recipients.recipient_type', '=', 'group')
            ->join('posts', 'post_recipients.post_id', '=', 'posts.post_id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->groupBy('posts.post_id')
            ->orderBy('posts.post_id', 'DESC')
            ->get();

        if(!empty($groupPosts)) {
            $details = new StdClass();
            foreach($groupPosts as $key => $post) {
                $details->$key = $post;
                $details->$key->recipients = PostRecipient::getRecipients($post->post_id);
                $details->$key->user = User::find($post['user_id']);
                // create object for the likes
                $likes = new StdClass();
                $likes->count = Like::where('post_id', '=', $post['post_id'])
                    ->get()->count();
                $likes->likers = Like::where('post_id', '=', $post['post_id'])
                    ->leftJoin('users', 'likes.user_id', '=', 'users.id')
                    ->get();
                // create object for the comments
                $comments = new StdClass();
                $comments = Comment::where('post_id', '=', $post['post_id'])
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->orderBy('comments.comment_id', 'ASC')
                    ->get();

                // assign the objects
                $details->$key->likes = $likes;
                $details->$key->comments = $comments;
            }

            return (empty($details)) ? null : $details;
        }

        return false;
    }
}
