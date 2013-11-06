<?php //-->

class QuizManagerController extends BaseController
{
    public function index($id)
    {
        $quiz = Quiz::find($id);
        // get recipients of the quiz
        $recipients = PostRecipient::where('post_id', '=', $quiz->quiz_id)
            ->get();

        return View::make('quizmanager.index')
            ->with('quiz', $quiz);
    }
}
