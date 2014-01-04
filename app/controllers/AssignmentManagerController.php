<?php //-->

class AssignmentManagerController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('are-you-a-teacher');
    }

    public function index($assignmentId)
    {
        $attached = null;
        // check if assignment exists
        $assignment = Assignment::find($assignmentId);
        // get the post
        $post = Post::where('post_type', '=', 'assignment')
            ->where('assignment_id', '=', $assignment->assignment_id)
            ->first();

        if(empty($assignment)) {
            return View::make('templates.fourohfour');
        }

        // check if the assignment has attached files
        if($post->post_attached_files == 'true') {
            // attached files details
            $attached = FileAttached::where('post_id', '=', $post->post_id)
                ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                ->get();
        }

        // get recipients of the assignment
        $lists = Assignment::getAssignmentRecipients($assignment->assignment_id, 'all');

        return View::make('assignmentmanager.index')
            ->with('assignment', $assignment)
            ->with('post', $post)
            ->with('takers', $lists)
            ->with('files', $attached);
    }
}
