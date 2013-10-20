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
            $newTopic = new ForumTopic;
            $newTopic->user_id = Auth::user()->id;
            $newTopic->category_id = Input::get('topic-category');
            $newTopic->title = Input::get('topic-title');
            $newTopic->description = Input::get('topic-description');
            $newTopic->timestamp = time();
            $newTopic->save();

            // redirect to topic page
            return Redirect::to('the-forum/topic/'.$seoUrl.'/'.$newTopic->forum_topic_id);
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
}
