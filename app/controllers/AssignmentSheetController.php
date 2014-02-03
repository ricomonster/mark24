<?php //-->

class AssignmentSheetController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('are-you-a-student');
    }

    public function index($assignmentId, $postId)
    {
        $attached = null;
        $assignment = Assignment::where('assignment_id', '=', $assignmentId)
            ->leftJoin('users', 'users.id', '=', 'assignments.user_id')
            ->first();
        // assignment details from the post
        $assignmentPost = Post::where('post_type', '=', 'assignment')
            ->where('post_id', '=', $postId)
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->first();

        // check if assignment exists
        if(empty($assignment) && empty($assignmentPost)) return View::make('templates.fourohfour');
        // check if the assignment has attached files
        if($assignmentPost->post_attached_files == 'true') {
            // attached files details
            $attached = FileAttached::where('post_id', '=', $assignmentPost->post_id)
                ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                ->get();
        }

        // check if user already turned in the assignment
        $userAssignment = AssignmentResponse::where('user_id', '=', Auth::user()->id)
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->where('post_id', '=', $assignmentPost->post_id)
            ->first();

        return View::make('assignmentsheet.index')
            ->with('assignment', $assignment)
            ->with('userAssignment', $userAssignment)
            ->with('post', $assignmentPost)
            ->with('files', $attached);
    }
}
