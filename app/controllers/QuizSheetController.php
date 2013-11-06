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

        // get the questions
        $questions = QuestionList::getQuizQuestions($quizId);
        // get the details of the user who assigned the quiz
        $assigned = User::find($quiz->user_id);

        // show quiz sheet page
        return View::make('quizsheet.index')
            ->with('quiz', $quiz)
            ->with('questions', $questions)
            ->with('assigned', $assigned);
    }
}
