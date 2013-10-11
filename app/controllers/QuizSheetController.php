<?php //-->

class QuizSheetController extends BaseController
{
    public function index($quizId)
    {
        // get quiz details
        $quiz = Quiz::find($quizId);

        // validate if quiz is empty
        if(empty($quiz)) {
            // redirect to 404
            return Redirect::to('/pagenotfound');
        }

        // continue
        // show quiz sheet page
        return View::make('quizsheet.index');
    }
}
