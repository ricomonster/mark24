<?php //-->

class QuizManagerController extends BaseController
{
    public function index($id)
    {
        $quiz = Quiz::find($id);

        return View::make('quizmanager.index')
            ->with('quiz', $quiz);
    }
}
