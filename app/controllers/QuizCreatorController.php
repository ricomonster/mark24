<?php //-->

class QuizCreatorController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('are-you-a-teacher');
    }

    public function getIndex()
    {
        return View::make('quizcreator.index');
    }

    public function editQuiz($quizId)
    {
        // fetch and create a new set of quiz
        // check for existing quiz
        $existingQuiz = Quiz::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'ACTIVE')
            ->first();
        if(empty($existingQuiz)) {
            $this->createEditedQuiz($quizId);
        }

        return View::make('quizcreator.index');
    }

    protected function createEditedQuiz($quizId)
    {
        $quiz = Quiz::find($quizId);
        $lists = QuestionList::where('quiz_id', '=', $quiz->quiz_id)
            ->get();
        // create a new quiz
        $newQuiz = new Quiz;
        $newQuiz->user_id = Auth::user()->id;
        $newQuiz->title = 'Copy of '.$quiz->title;
        $newQuiz->description = $quiz->description;
        $newQuiz->time_limit = $quiz->time_limit;
        $newQuiz->total_score = $quiz->total_score;
        $newQuiz->save();
        // create question lists
        foreach($lists as $key => $list) {
            // create new question list
            $questionList = new QuestionList;
            $questionList->quiz_id = $newQuiz->quiz_id;
            $questionList->question_id = $list->question_id;
            $questionList->save();
        }

        return $newQuiz->quiz_id;
    }
}
