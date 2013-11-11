<?php //-->

class AjaxQuizManagerController extends BaseController
{
    public function takerDetails()
    {
        $questions = null;
        
        $quizId = Input::get('quiz_id');
        $takerId = Input::get('taker_id');
        
        $takerDetails = QuizTaker::where('user_id', '=', $takerId)
            ->where('quiz_id', '=', $quizId)
            ->first();
       
        if(!empty($takerDetails)) {
            // get the questions
            $questions = QuestionList::getQuizQuestions($quizId, $takerDetails->quiz_taker_id);
        }
        
        $userDetails = User::find($takerId);
        // get quiz details
        $quiz = Quiz::find($quizId);
        
        return View::make('ajax.quizmanager.takerdetails')
            ->with('userDetails', $userDetails)
            ->with('takerDetails', $takerDetails)
            ->with('questions', $questions)
            ->with('quiz', $quiz);
    }
}
