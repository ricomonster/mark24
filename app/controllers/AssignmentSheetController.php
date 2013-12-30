<?php //-->

class AssignmentSheetController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('are-you-a-student');
    }

    public function index($assignmentId)
    {
        $assignment = Assignment::where('assignment_id', '=', $assignmentId)
            ->leftJoin('users', 'users.id', '=', 'assignments.user_id')
            ->first();

        // check if assignment exists
        if(empty($assignment)) return View::make('templates.fourohfour');
        // assignment details from the post
        $assignmentPost = Post::where('post_type', '=', 'assignment')
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->first();
        // check if user already turned in the assignment
        $userAssignment = AssignmentResponse::where('user_id', '=', Auth::user()->id)
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->first();

        return View::make('assignmentsheet.index')
            ->with('assignment', $assignment)
            ->with('userAssignment', $userAssignment)
            ->with('post', $assignmentPost);
    }
}
