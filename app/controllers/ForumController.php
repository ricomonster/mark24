<?php //-->

class ForumController extends BaseController
{
    public function index()
    {
        // let's get the categories
        $categories = ForumCategory::all();

        return View::make('forums.index')
            ->with('categories', $categories);
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
        // a new thread is submitted
        // let's validate first the form submitted
        $rules = array(
            'thread-title'       => 'required|min:6',
            'thread-category'    => 'required',
            'thread-description' => 'required|min:6');

        $messages = array(
            'thread-title.required' => 'Title is required.',
            'thread-title.min' => 'Title should be atleast 6+ characters long.',
            'thread-category.required' => 'Category is required',
            'thread-description.required' => 'Description is required.',
            'thread-description.min' => 'Description should be atleast 6+ characters long.');

        $validator = Validator::make(Input::all(), $rules, $messages);

        // there are errors
        if($validator->fails()) {
            return Redirect::to('the-forum/add-thread')
                ->withErrors($validator)
                ->withInput();
        }

        // no errors
        if(!$validator->fails()) {
            $seoUrl = Helper::seoFriendlyUrl(Input::get('thread-title'));
            // save thread
            $newThread              = new ForumThread;
            $newThread->user_id     = Auth::user()->id;
            $newThread->category_id = Input::get('thread-category');
            $newThread->title       = Input::get('thread-title');
            $newThread->description = Input::get('thread-description');
            $newThread->seo_url     = $seoUrl;
            $newThread->timestamp   = time();
            $newThread->save();

            // add thread to threads followed
            $addThread = new FollowedForumThread;
            $addThread->user_id = Auth::user()->id;
            $addThread->forum_thread_id = $newThread->forum_thread_id;
            $addThread->save();

            // update number of posts
            $updateCount = User::find(Auth::user()->id);
            $updateCount->forum_posts += 1;
            $updateCount->save();

            // redirect to thread page
            return Redirect::to('the-forum/thread/'.$seoUrl.'/'.$newThread->forum_thread_id);
        }
    }

    public function showCategory($category)
    {
        // check if category exists
        $category = ForumCategory::where('seo_name', '=', $category)->first();

        // if the category is empty
        if(empty($category)) {
            // redirect to page not found or show
            return Redirect::to('page-not-found');
        }

        echo $category;
    }

    public function showThread($slug, $id)
    {
        // get the details of the thread
        $thread = ForumThread::where('seo_url', '=', $slug)
            ->where('forum_thread_id', '=', $id)
            ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
            ->first();

        // check if the thread exists
        if(empty($thread)) {
            // redirect to page not found
            return Redirect::to('page-not-found');
        }

        // check also if the thread is being followed
        $followed = FollowedForumThread::where('user_id', '=', Auth::user()->id)
            ->where('forum_thread_id', '=', $thread->forum_thread_id)
            ->first();

        // get all categories
        $categories = ForumCategory::all();

        return View::make('forums.thread')
            ->with('thread', $thread)
            ->with('followed', $followed)
            ->with('categories', $categories);
    }
}
