<?php //-->

class AjaxQuizManagerController extends BaseController
{
    public function takerDetails()
    {
        $questions = null;
        $timeRemained = 0;

        $quizId = Input::get('quiz_id');
        $takerId = Input::get('taker_id');

        $takerDetails = QuizTaker::where('user_id', '=', $takerId)
            ->where('quiz_id', '=', $quizId)
            ->first();

        $userDetails = User::find($takerId);
        // get quiz details
        $quiz = Quiz::find($quizId);

        if(!empty($takerDetails)) {
            // get the questions
            $questions = QuestionList::getQuizQuestions($quizId, $takerDetails->quiz_taker_id);
            // compute the time the user took the exam
            $timeRemained = $takerDetails->time_remaining;
        }

        $timeTaken = gmdate("H:i:s", ($quiz->time_limit - $timeRemained));

        return View::make('ajax.quizmanager.takerdetails')
            ->with('userDetails', $userDetails)
            ->with('takerDetails', $takerDetails)
            ->with('timeTaken', $timeTaken)
            ->with('questions', $questions)
            ->with('quiz', $quiz);
    }

    public function takerLists()
    {
        $type = Input::get('type');
        $quizId = Input::get('quiz_id');

        $takers = QuizTaker::getQuizRecipients($quizId, $type);

        return View::make('ajax.quizmanager.takerlists')
            ->with('takers', $takers);
    }
}
