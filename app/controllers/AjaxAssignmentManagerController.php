<?php //-->

class AjaxAssignmentManagerController extends BaseController
{
    public function getTakerDetails()
    {
        $userId = Input::get('user_id');
        $assignmentId = Input::get('assignment_id');

        // get user details
        $user = User::find($userId);
        $assignment = Assignment::find($assignmentId);
        // get user assignment response
        $response = AssignmentResponse::where('assignment_id', '=', $assignment->assignment_id)
            ->where('user_id', '=', $user->id)
            ->first();

        return View::make('ajax.assignmentmanager.takerdetails')
            ->with('user', $user)
            ->with('assignment', $assignment)
            ->with('response', $response);
    }

    public function setTakerScore()
    {
        $userId = Input::get('user-id');
        $assignmentId = Input::get('assignment-id');
        $assignmentResponseId = Input::get('assignment-response-id');
        $userScore = Input::get('user-score');
        $totalScore = Input::get('total-score');

        // update first the total score
        $assignmentUpdate = Assignment::find($assignmentId);
        $assignmentUpdate->total_score = $totalScore;
        $assignmentUpdate->save();

        // update the user score
        $taker = AssignmentResponse::find($assignmentResponseId);
        $taker->score = $userScore;
        $taker->status = 'GRADED';
        $taker->save();

        // return assignment response details
        return Response::json(array(
            'error' => false,
            'assignment' => $assignmentUpdate->toArray(),
            'assignment_response' => $taker->toArray()));
    }
}
