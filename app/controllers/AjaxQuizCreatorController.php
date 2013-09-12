<?php //-->

class AjaxQuizCreatorController extends BaseController {

    public function getCheckActiveQuiz() {
        $existingQuiz = Quiz::where('user_id', '=', Auth::user()->id)
            ->where('status', '=', 'ACTIVE')
            ->first();

        if(!empty($existingQuiz)) {
            // get the question lists
        }

        return false;
    }

    public function postCreateQuiz() {
        $questionType   = Input::get('question_type');
        $quizTitle      = Input::get('quiz_title');
        $quizTimeLimit  = Input::get('quiz_time_limit');

        // create quiz
        $newQuiz                = new Quiz;
        $newQuiz->user_id       = Auth::user()->id;
        $newQuiz->title         = $quizTitle;
        $newQuiz->time_limit    = $quizTimeLimit;
        $newQuiz->total_score   = 1;
        // $newQuiz->save();

        // create question
        $newQuestion                = new Question;
        $newQuestion->question_type = $questionType;
        // $newQuestion->save();

        // add to question list
        $addToList              = new QuestionList;
        $addToList->quiz_id     = $newQuiz->quiz_id;;
        $addToList->question_id = $newQuestion->question_id;
        // $addToList->save();

        // create an answer field for the question type
        switch($questionType) {
            // multiple choice
            case 'MULTIPLE_CHOICE' :
                // create 2 choices. one is the correct answer
                // while the other one is just a choice
                $correctOption              = new MultipleChoice;
                $correctOption->question_id = $newQuestion->question_id;
                $correctOption->is_answer   = 'TRUE';
                // $correctOption->save();

                // create another option
                $anotherOption              = new MultipleChoice;
                $anotherOption->question_id = $newQuestion->question_id;
                // $anotherOption->save();

                break;
            // true or false
            case 'TRUE_FALSE' :
                // by default, the answer is true
                $addAnswer              = new TrueFalse;
                $addAnswer->question_id = $newQuestion->question_id;
                // $addAnswer->save();

                break;
            default :
                break;
        }

        // return awesome data
        // $return = array(
        //     'quiz_id'           => $newQuiz->quiz_id,
        //     'question_id'       => $newQuestion->question_id,
        //     'question_list_id'  => $addToList->question_list_id);
        $return = array(
            'quiz_id'           => 1,
            'question_id'       => 1,
            'question_list_id'  => 1);

        return Response::json($return);
    }
}
