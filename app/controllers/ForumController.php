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

    public function showAddTopic()
    {
        // let's get the categories
        $categories = ForumCategory::all();

        return View::make('forums.addtopic')
            ->with('categories', $categories);
    }

    public function submitTopic()
    {
        // a new topic is submitted
        // let's validate first the form submitted
        $rules = array(
            'topic-title'       => 'required|min:6',
            'topic-category'    => 'required',
            'topic-description' => 'required|min:6');

        $messages = array(
            'topic-title.required' => 'Title is required.',
            'topic-title.min' => 'Title should be atleast 6+ characters long.',
            'topic-category.required' => 'Category is required',
            'topic-description.required' => 'Description is required.',
            'topic-description.min' => 'Description should be atleast 6+ characters long.');

        $validator = Validator::make(Input::all(), $rules, $messages);

        // there are errors
        if($validator->fails()) {
            return Redirect::to('the-forum/add-topic')
                ->withErrors($validator)
                ->withInput();
        }

        // no errors
        if(!$validator->fails()) {
            $seoUrl = Helper::seoFriendlyUrl(Input::get('topic-title'));
            // save topic
            $newThread              = new ForumThread;
            $newThread->user_id     = Auth::user()->id;
            $newThread->category_id = Input::get('topic-category');
            $newThread->title       = Input::get('topic-title');
            $newThread->description = Input::get('topic-description');
            $newThread->seo_url     = $seoUrl;
            $newThread->timestamp   = time();
            $newThread->save();

            // add topic to topics followed
            $addThread = new FollowedForumThread;
            $addThread->user_id = Auth::user()->id;
            $addThread->forum_thread_id = $newThread->forum_thread_id;
            $addThread->save();

            // update number of posts
            $updateCount = User::find(Auth::user()->id);
            $updateCount->forum_posts += 1;
            $updateCount->save();

            // redirect to topic page
            return Redirect::to('the-forum/topic/'.$seoUrl.'/'.$newThread->forum_topic_id);
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

    public function showTopic($slug, $id)
    {
        // get the details of the topic
        $topic = ForumThread::where('seo_url', '=', $slug)
            ->where('forum_thread_id', '=', $id)
            ->leftJoin('users', 'forum_threads.user_id', '=', 'users.id')
            ->first();

        // check if the topic exists
        if(empty($topic)) {
            // redirect to page not found
            return Redirect::to('page-not-found');
        }

        // get all categories
        $categories = ForumCategory::all();

        return View::make('forums.topic')
            ->with('topic', $topic)
            ->with('categories', $categories);
    }
}
