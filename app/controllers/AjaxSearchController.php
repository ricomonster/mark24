<?php //-->

class AjaxSearchController extends BaseController
{
   public function search()
   {
       $query = e(Input::get('q', ''));
       
        // check if the query is empty
        if(!$query && empty($query)) return Response::json(array(), 400);
        $results = array();
        // user (teacher and student)
        $users = $this->getUsers($query);
        // forum threads
        $forums = $this->getForums($query);
        
        $results = array_merge($users, $forums);
        
        return Response::json(array('data' => $results));
   } 
   
   protected function getUsers($query)
   {
       $users = User::select('id', 'name as content', 'salutation', 'username')
            ->where('name', 'LIKE', '%'.$query.'%')
            ->where('flag', '=', 1)
            ->get()
            ->toArray();
       $array = array();
       foreach($users as $key => $item) {
           $array[$key] = $item;
           $array[$key]['class'] = 'users';
            // set icons
           $array[$key]['url'] = '/profile/'.$item['username'];
           $array[$key]['icon'] = Helper::avatar(30, 'small', null, $item['id']);
       }
       
       return $array;
   }
   
   protected function appendPosts($query)
   {
       $notes = Post::select('post_id', 'note_content as content')
            ->where('note_content', 'LIKE', '%'.$query.'%')
            ->where('post_type', '=', 'note')
            ->where('post_active', '=', 1)
            ->get()
            ->toArray();
        $notesArray = array();
        foreach($notes as $key => $note) {
            $notesArray[$key] = $note;
            $notesArray[$key]['class'] = 'posts';
            $notesArray[$key]['url'] = '/posts/'.$note['post_id']; 
        }

        $alerts = Post::select('alert_content as content')
            ->where('alert_content', 'LIKE', '%'.$query.'%')
            ->where('post_type', '=', 'alert')
            ->where('post_active', '=', 1)
            ->get()
            ->toArray();
        $alertArray = array();
        foreach($alerts as $key => $alert) {
            $alertArray[$key] = $alert;
            $alertArray[$key]['class'] = 'posts';
            $alertArray[$key]['url'] = '/posts/'.$alert['post_id']; 
        }
        
        // get assignments
    }

    protected function getForums($query)
    {
        // let's search for forum titles
        $forums = ForumThread::select('forum_thread_id', 'title as content', 'seo_url')
            ->where('title', 'LIKE', '%'.$query.'%')
            ->orWhere('description', 'LIKE', '%'.$query.'%')
            ->get()
            ->toArray();
        $forumArray = array();
        foreach($forums as $key => $forum) {
            $forumArray[$key] = $forum;
            $forumArray[$key]['class'] = 'forums';
            $forumArray[$key]['url'] = '/the-forum/thread/'.
                $forum['seo_url'].'/'.$forum['forum_thread_id']; 
            $forumArray[$key]['icon'] = '<i class="fa fa-comment"></i>';
        }
        
        return $forumArray;
    }
}
