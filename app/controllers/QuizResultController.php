<?php //-->

class QuizResultController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('are-you-a-student');
    }

    public function index($quizId)
    {
        // get quiz details
        $quiz = Quiz::find($quizId);
        // check if the user already answered the quiz
        $alreadyTaken = QuizTaker::where('user_id', '=', Auth::user()->id)
            ->where('quiz_id', '=', $quiz->quiz_id)
            ->where(function($query) {
                $query->where('status', '=', 'PASSED')
                    ->orWhere('status', '=', 'GRADED');
            })
            ->first();

        // validate if quiz is empty
        if(empty($quiz)) {
            // redirect to 404
            return View::make('templates.fourohfour');
        }

        // get the questions
        $questions = QuestionList::getQuizQuestions($quizId, $alreadyTaken->quiz_taker_id);
        // get the details of the user who assigned the quiz
        $assigned = User::find($quiz->user_id);

        // show quiz sheet page
        return View::make('quizresult.index')
            ->with('quiz', $quiz)
            ->with('takerDetails', $alreadyTaken)
            ->with('questions', $questions)
            ->with('assigned', $assigned);
    }
}
