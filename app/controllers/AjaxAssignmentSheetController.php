<?php //-->

class AjaxAssignmentSheetController extends BaseController
{
    public function createResponse()
    {
        $assignmentId = Input::get('assignment-id');
        $response = Input::get('assignment-response');

        // save response
        $newResponse = new AssignmentResponse;
        $newResponse->assignment_id = $assignmentId;
        $newResponse->user_id = Auth::user()->id;
        $newResponse->response = $response;
        $newResponse->response_timestamp = time();
        $newResponse->save();

        // get the latest response
        $responseDetails = AssignmentResponse::find($newResponse->assignment_response_id);
        // create notification
        Notification::setup('assignment_submitted', array(
            'assignment_id' => $responseDetails->assignment_id,
            'involved_id' => $responseDetails->assignment_response_id));

        // return as json
        return Response::json(array(
            'response' => $responseDetails->toArray(),
            'formatted_status' => ucfirst(strtolower($responseDetails->status)),
            'parsed_date' => date('F d, Y h:i A', strtotime($responseDetails->created_at))));
    }
}
