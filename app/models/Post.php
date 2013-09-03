<?php //-->

class Post extends Eloquent {
    protected $table        = 'posts';
    protected $primaryKey   = 'post_id';

    public static function getAllPosts() {
        // get first posts for the current user
        $groupIds = Group::getMyGroupsId();
        if(!empty($groupIds)) {
            $posts = PostRecipient::orWhere('posts.user_id', '=', Auth::user()->id)
                ->orWhere(function($query) {
                    $query->whereIn('post_recipients.recipient_id', Group::getMyGroupsId())
                        ->where('post_recipients.recipient_type', '=', 'group');
                })
                ->join('posts', 'post_recipients.post_id', '=', 'posts.post_id')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->groupBy('posts.post_id')
                ->orderBy('posts.post_id', 'DESC')
                ->get();

            return (strlen($posts) > 2) ? $posts : false;
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

        return (strlen($groupPosts) > 2) ? $groupPosts : false;
    }
}
