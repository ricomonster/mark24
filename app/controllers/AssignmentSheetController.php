<?php //-->

class AssignmentSheetController extends BaseController
{
    public function index($assignmentId)
    {
        $assignment = Assignment::where('assignment_id', '=', $assignmentId)
            ->leftJoin('users', 'users.id', '=', 'assignments.user_id')
            ->first();

        return View::make('assignmentsheet.index')
            ->with('assignment', $assignment);
    }
}
