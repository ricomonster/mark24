<?php //-->

class QuestionList extends Eloquent {
    protected $table        = 'question_lists';
    protected $primaryKey   = 'question_list_id';

    /**
     * Setting up the quiz with all of its details
     *
     * @return array
     * @author
     **/
    public static function getQuizQuestions($quizId)
    {
        $questions = null;

        $lists = QuestionList::where('quiz_id', '=', $quizId)
            ->get()->toArray();

        // get each of the list and get the question details
        foreach($lists as $key => $list) {
            $question = Question::find($list['question_id'])->toArray();

            // determine the question type of the quiz
            // and get the necessary responses
            switch($question['question_type']) {
                case 'MULTIPLE_CHOICE' :
                    // get choices
                    $response = MultipleChoice::where('question_id', '=', $question['question_id'])
                        ->get()->toArray();
                    break;
                case 'TRUE_FALSE' :
                    $response = TrueFalse::where('question_id', '=', $question['question_id'])
                        ->first()->toArray();
                    break;
                case 'SHORT_ANSWER' :
                    $response = null;
                    break;
                default :
                    $response = null;
                    break;
            }

            $questions['list'][$key] = $list;
            $questions['list'][$key]['question'] = $question;
            $questions['list'][$key]['question']['response'] = $response;
        }

        return $questions;
    }
}
