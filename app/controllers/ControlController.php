<?php //-->

class ControlController extends BaseController
{
    public function index()
    {
        // dashboard
        $sort = Input::get('sort');

        if(empty($sort)) {
            return $this->stats();
        }

        if(!empty($sort)) {
            switch (strtolower($sort)) {
                case 'stats':
                    return $this->stats();
                    break;
                case 'users':
                    return $this->users();
                    break;
                case 'groups':
                    return $this->_groups();
                    break;
                case 'posts':

                    break;
                case 'the-forum':

                    break;
                case 'the-library':

                    break;
                case 'reports':
                return $this->reports();
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

    protected function _groups()
    {
        // get all groups
        $groups = Group::orderBy('group_name', 'ASC')->get();
        return View::make('control.groups')
            ->with('groups', $groups);
    }

    protected function reports()
    {
        $viewReport = Input::get('view');
        // get reports
        $reports = Report::orderBy('report_timestamp', 'DESC')
            ->leftJoin('users', 'reports.user_id', '=', 'users.id')
            ->get();

        if(isset($viewReport)) {
            // get report details
            $report = Report::where('report_id', '=', $viewReport)
                ->leftJoin('users', 'reports.user_id', '=', 'users.id')
                ->first();

            return View::make('control.reports.view')
                ->with('report', $report);
        }

        return View::make('control.reports')
            ->with('reports', $reports);
    }
}
