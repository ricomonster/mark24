<?php //-->

class AssignmentManagerController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('are-you-a-teacher');
    }

    public function index($assignmentId)
    {
        // check if assignment exists
        $assignment = Assignment::find($assignmentId);
        // get the post
        $post = Post::where('post_type', '=', 'assignment')
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->first();

        if(empty($assignment)) {
            return View::make('templates.fourohfour');
        }

        // get recipients of the assignment
        $lists = Assignment::getAssignmentRecipients($assignment->assignment_id, 'all');

        return View::make('assignmentmanager.index')
            ->with('assignment', $assignment)
            ->with('post', $post)
            ->with('takers', $lists);
    }
}
