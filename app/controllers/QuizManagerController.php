<?php //-->

class QuizManagerController extends BaseController
{
    public function index($id)
    {
        $quiz = Quiz::find($id);
        // get recipients of the quiz
        $takers = QuizTaker::getQuizRecipients($id);
        // get the questions
        $questions = QuestionList::getQuizQuestions($id);

        return View::make('quizmanager.index')
            ->with('quiz', $quiz)
            ->with('takers', $takers)
            ->with('questions', $questions);
    }
}
