<?php //-->

class ControlController extends BaseController
{
    public function index()
    {
        // login page
    }

    public function dashboard()
    {
        // dashboard
        $sort = Input::get('sort');

        if(empty($sort)) {
            return $this->stats();
        }

        if(!empty($sort)) {
            switch ($sort) {
                case 'stats':
                    return $this->stats();
                    break;
                case 'users':
                    return $this->users();
                    break;
                case 'groups':

                    break;
                case 'posts':

                    break;
                case 'the-forum':

                    break;
                case 'the-library':

                    break;
                default:
                    break;
            }
        }
    }

    protected function stats()
    {
        // setup the stats
        $stats = array(
            'users'         => User::where('account_type', '!=', 0)->get()->count(),
            'teachers'      => User::where('account_type', '=', '1')->get()->count(),
            'students'      => User::where('account_type', '=', '2')->get()->count(),
            'groups'        => Group::all()->count(),
            'notes'         => Post::where('post_type', '=', 'note')->get()->count(),
            'alerts'        => Post::where('post_type', '=', 'alert')->get()->count(),
            'assignments'   => Post::where('post_type', '=', 'assignment')->get()->count(),
            'quizzes'       => Post::where('post_type', '=', 'quiz')->get()->count(),
            'posts'         => Post::all()->count(),
            'threads'       => ForumThread::all()->count(),
            'replies'       => ForumThreadReply::all()->count());

        return View::make('control.dashboard')
            ->with('stats', $stats);
    }

    protected function users()
    {
        $users = User::where('account_type', '!=', 0)
            ->orderBy('lastname', 'ASC')
            ->get();

        return View::make('control.users')
            ->with('users', $users);
    }
}
