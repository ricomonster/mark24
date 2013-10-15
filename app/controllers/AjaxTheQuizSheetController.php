<?php //-->

class AjaxTheQuizSheetController extends BaseController
{
    public function startQuiz()
    {
        $quizId = Input::get('quiz_id');
        // insert new quiz taker
        $taker = new QuizTaker;
        $taker->user_id = Auth::user()->id;
        $taker->quiz_id = $quizId;
        $taker->save();

        return Response::json(array(
            'taker_id' => $taker->quiz_taker_id));
    }

    public function updateAnswer()
    {
        $questionId = Input::get('question_id');

        $choiceId = Input::get('choice_id');
        $trueFalse = Input::get('true_false');

        // check first if there's already an existing row
        // for the answer

    }
}
