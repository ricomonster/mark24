<?php //-->

class ForumController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('auth');
    }

    public function index()
    {
        $sort = Input::get('sort');

        // let's get the categories
        $categories = ForumCategory::all();

        switch($sort) {
            case 'latest' :
                // get latest threads
                $threads = ForumThread::where('thread_source', '=', 0)
                    ->orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('sticky_post', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'popular' :
                $threads = ForumThread::where('thread_source', '=', 0)
                    ->where('views', '!=', '0')
                    ->orderBy('views', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('replies', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'unanswered' :
                // get unanswered threads
                $threads = ForumThread::where('thread_source', '=', 0)
                    ->orderBy('thread_timestamp', 'DESC')
                    ->where('replies', '=', 0)
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'following' :
                $threads = FollowedForumThread::where('thread_source', '=', 0)
                    ->where('followed_forum_threads.user_id', '=', Auth::user()->id)
                    ->leftJoin('forum_threads', 'followed_forum_threads.forum_thread_id', '=', 'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->orderBy('forum_threads.last_reply_timestamp', 'DESC')
                    ->orderBy('forum_threads.thread_timestamp', 'DESC')
                    ->get();
                break;
            case 'my-topics' :
                $threads = ForumThread::where('thread_source', '=', 0)
                    ->where('user_id', '=', Auth::user()->id)
                    ->orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'last-viewed' :
                $threads = ForumThreadView::where('thread_source', '=', 0)
                    ->where('forum_thread_views.user_id', '=', Auth::user()->id)
                    ->orderBy('forum_thread_views.view_timestamp', 'DESC')
                    ->leftJoin(
                        'forum_threads',
                        'forum_thread_views.forum_thread_id',
                        '=',
                        'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            default :
                // get latest threads
                $threads = ForumThread::where('thread_source', '=', 0)
                    ->orderBy('forum_thread_id', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
        }
        // print_r($threads);

        return View::make('forums.index')
            ->with('categories', $categories)
            ->with('threads', $threads)
            ->with('sort', $sort);
    }

    public function showAddThread()
    {
        // let's get the categories
        $categories = ForumCategory::all();

        return View::make('forums.addthread')
            ->with('categories', $categories);
    }

    public function submitThread()
    {
        $groupId = Input::get('group-id');
        // a new thread is submitted
        // let's validate first the form submitted
        $rules = array(
            'thread-title'       => 'required|min:6',
            'thread-category'    => 'required',
            'thread-description' => 'required|min:6');

        $messages = array(
            'thread-title.required'         => 'Title is required.',
            'thread-title.min'              => 'Title should be atleast 6+ characters long.',
            'thread-category.required'      => 'Category is required',
            'thread-description.required'   => 'Description is required.',
            'thread-description.min'        => 'Description should be atleast 6+ characters long.');

        $validator = Validator::make(Input::all(), $rules, $messages);

        // there are errors
        if($validator->fails()) {
            if(isset($groupId)) {
                return Redirect::to('groups/'.$groupId.'/the-forum/add-thread')
                    ->withErrors($validator)
                    ->withInput();
            }

            return Redirect::to('the-forum/add-thread')
                ->withErrors($validator)
                ->withInput();
        }

        // no errors
        if(!$validator->fails()) {
            $stickyPost = Input::get('sticky-post');

            $seoUrl = Helper::seoFriendlyUrl(Input::get('thread-title'));
            // save thread
            $newThread                      = new ForumThread;
            $newThread->user_id             = Auth::user()->id;
            $newThread->category_id         = Input::get('thread-category');
            $newThread->title               = Input::get('thread-title');
            $newThread->description         = Input::get('thread-description');
            $newThread->seo_url             = $seoUrl;
            $newThread->sticky_post         = (empty($stickyPost)) ? 'FALSE' : 'TRUE';
            $newThread->thread_source       = (isset($groupId)) ? 1 : 0;
            $newThread->thread_timestamp    = time();
            $newThread->save();

            // set the group forum threads
            if(isset($groupId)) {
                $groupThread                    = new GroupForumThread;
                $groupThread->group_id          = $groupId;
                $groupThread->forum_thread_id   = $newThread->forum_thread_id;
                $groupThread->save();
            }

            // add thread to threads followed
            $addThread = new FollowedForumThread;
            $addThread->user_id = Auth::user()->id;
            $addThread->forum_thread_id = $newThread->forum_thread_id;
            $addThread->save();

            // update number of posts
            $updateCount = User::find(Auth::user()->id);
            $updateCount->forum_posts += 1;
            $updateCount->save();

            // redirect
            if(isset($groupId)) {
                return Redirect::to('groups/'.$groupId.'/the-forum/thread/'.$seoUrl.'/'.$newThread->forum_thread_id);
            }

            return Redirect::to('the-forum/thread/'.$seoUrl.'/'.$newThread->forum_thread_id);
        }
    }

    public function showCategory($category)
    {
        $sort = Input::get('sort');

        // check if category exists
        $category = ForumCategory::where('seo_name', '=', $category)->first();

        // if the category is empty
        if(empty($category)) {
            // redirect to page not found or show
            return View::make('templates.fourohfour');
        }

        $categories = ForumCategory::all();

        switch($sort) {
            case 'latest' :
                // get latest threads
                $threads = ForumThread::orderBy('forum_thread_id', 'DESC')
                    ->where('category_id', '=', $category->forum_category_id)
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('sticky_post', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'popular' :
                $threads = ForumThread::where('views', '!=', '0')
                    ->where('category_id', '=', $category->forum_category_id)
                    ->orderBy('views', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->orderBy('replies', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'unanswered' :
                // get unanswered threads
                $threads = ForumThread::orderBy('forum_thread_id', 'DESC')
                    ->where('category_id', '=', $category->forum_category_id)
                    ->where('replies', '=', 0)
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'following' :
                $threads = FollowedForumThread::where('followed_forum_threads.user_id', '=', Auth::user()->id)
                    ->where('forum_threads.category_id', '=', $category->forum_category_id)
                    ->leftJoin('forum_threads', 'followed_forum_threads.forum_thread_id', '=', 'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->orderBy('forum_threads.last_reply_timestamp', 'DESC')
                    ->orderBy('forum_threads.thread_timestamp', 'DESC')
                    ->get();
                break;
            case 'my-topics' :
                $threads = ForumThread::where('user_id', '=', Auth::user()->id)
                    ->where('forum_threads.category_id', '=', $category->forum_category_id)
                    ->orderBy('last_reply_timestamp', 'DESC')
                    ->orderBy('thread_timestamp', 'DESC')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            case 'last-viewed' :
                $threads = ForumThreadView::where('forum_thread_views.user_id', '=', Auth::user()->id)
                    ->where('forum_threads.category_id', '=', $category->forum_category_id)
                    ->orderBy('forum_thread_views.view_timestamp', 'DESC')
                    ->leftJoin(
                        'forum_threads',
                        'forum_thread_views.forum_thread_id',
                        '=',
                        'forum_threads.forum_thread_id')
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
            default :
                // get latest threads
                $threads = ForumThread::orderBy('forum_thread_id', 'DESC')
                    ->where('category_id', '=', $category->forum_category_id)
                    ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
                    ->leftJoin('forum_categories',
                        'forum_threads.category_id',
                        '=',
                        'forum_categories.forum_category_id')
                    ->get();
                break;
        }

        return View::make('forums.categorythread')
            ->with('categoryDetails', $category)
            ->with('categories', $categories)
            ->with('threads', $threads)
            ->with('sort', $sort);
    }

    public function showThread($slug, $id)
    {
        $page = Input::get('page');
        $thread = ForumThread::where('seo_url', '=', $slug)
            ->where('forum_thread_id', '=', $id)
            ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
            ->where('thread_source', '=', 0)
            ->first();

        // check if the thread exists
        if(empty($thread)) {
            // redirect to page not found
            return View::make('templates.fourohfour');
        }

        // check if the user already viewed this thread
        $visit = ForumThreadView::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // if empty, the user viewed this page for the first time
        if(empty($visit)) {
            $visitor = new ForumThreadView;
            $visitor->user_id = Auth::user()->id;
            $visitor->forum_thread_id = $thread->forum_thread_id;
            $visitor->view_timestamp = time();
            $visitor->last_viewed = date('Y-m-d');
            $visitor->save();

            // increment the number of views of the thread
            $thread->views += 1;
            $thread->save();
        }

        // user already viewed this thread
        if(!empty($visit)) {
            // check if the last viewed date is the same from the
            // date today
            if(date('Y-m-d') != $visit->last_viewed) {
                $visit->view_timestamp = time();
                $visit->last_viewed = date('Y-m-d');
                $visit->save();

                // increment the number of views of the thread
                $thread->views += 1;
                $thread->save();
            }
        }

        // check also if the thread is being followed
        $followed = FollowedForumThread::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // get the thread replies
        $replies = ForumThreadReply::where('forum_thread_id', '=', $thread->forum_thread_id)
            ->leftJoin('users', 'forum_thread_replies.user_id', '=', 'users.id')
            ->orderBy('reply_timestamp', 'ASC')
            ->paginate(10);

        // get all categories
        $categories = ForumCategory::all();

        return View::make('forums.thread')
            ->with('thread', $thread)
            ->with('replies', $replies)
            ->with('followed', $followed)
            ->with('categories', $categories)
            ->with('page', $page);
    }

    public function submitReplyThread()
    {
        $threadReply    = Input::get('thread-reply');
        $threadId       = Input::get('thread-id');
        $groupId        = Input::get('group-id');

        // get thread details
        $thread = ForumThread::find($threadId);

        // check first the user already follows the thread
        $following = FollowedForumThread::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // current user doesn't follow the thread
        if(empty($following)) {
            $addThread = new FollowedForumThread;
            $addThread->user_id = Auth::user()->id;
            $addThread->forum_thread_id = $thread->forum_thread_id;
            $addThread->save();
        }

        $time = time();

        // create the reply
        $reply                  = new ForumThreadReply;
        $reply->forum_thread_id = $thread->forum_thread_id;
        $reply->user_id         = Auth::user()->id;
        $reply->reply           = $threadReply;
        $reply->reply_timestamp = $time;
        $reply->save();

        // update the thread details
        $thread->replies += 1;
        $thread->last_reply_timestamp = $time;
        $thread->save();

        // update the user's forum post count
        $userPostCount = User::find(Auth::user()->id);
        $userPostCount->forum_posts += 1;
        $userPostCount->save();

        // check the number of replies of the thread
        $page = ForumThreadReply::where('forum_thread_id', '=', $threadId)
            ->get()
            ->count();
        $lastPage = ceil($page / 10);

        // set up the redirect
        if(is_null($groupId)) {
            if($lastPage <= 1) {
                $redirectUrl = 'the-forum/thread/'.$thread->seo_url.'/'.
                    $thread->forum_thread_id;
            }

            if($lastPage > 1) {
                $redirectUrl = 'the-forum/thread/'.$thread->seo_url.'/'.
                    $thread->forum_thread_id.'?page='.$lastPage;
            }
        } else if(!is_null($groupId)) {
            if($lastPage <= 1) {
                $redirectUrl = 'groups/'.$groupId.'/the-forum/thread/'.
                    $thread->seo_url.'/'.$thread->forum_thread_id;
            }

            if($lastPage > 1) {
                $redirectUrl = 'groups/'.$groupId.'/the-forum/thread/'.
                    $thread->seo_url.'/'.$thread->forum_thread_id.'?page='.$lastPage;
            }
        }

        // setup the notification
        Notification::setup('forum_reply', array(
            'involved_id' => $thread->forum_thread_id));

        // redirect to the page
        return Redirect::to($redirectUrl.'/#last');
    }
}
