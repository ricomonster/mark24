<?php //-->

class AjaxTheQuizSheetController extends BaseController
{
    public function checkQuizTaker()
    {
        // checks if the current user already took the
        // quiz and checks if the user already finished the quiz
        $quizId = Input::get('quiz_id');
        $postId = Input::get('post_id');

        $taker = QuizTaker::where('quiz_id', '=', $quizId)
            ->where('post_id', '=', $postId)
            ->where('status', '=', 'NOT YET PASSED')
            ->where('user_id', '=', Auth::user()->id)
            ->first();

        if(empty($taker)) {
            $return['taken'] = false;
        }

        if(!empty($taker)) {
            $return = array(
                'taken'     => true,
                'details'   => $taker->toArray());
        }

        return Response::json($return);
    }

    public function getQuestions()
    {
        $quizId         = Input::get('quiz_id');
        $quizTakerId    = Input::get('quiz_taker_id');

        $questions = QuestionList::getQuizQuestions($quizId);

        return View::make('ajax.quizsheet.questions')
            ->with('quizTakerId', $quizTakerId)
            ->with('questions', $questions);
    }

    public function startQuiz()
    {
        $quizId = Input::get('quiz_id');
        $postId = Input::get('post_id');
        // insert new quiz taker
        $taker = new QuizTaker;
        $taker->user_id = Auth::user()->id;
        $taker->post_id = $postId;
        $taker->quiz_id = $quizId;
        $taker->save();

        return Response::json(array(
            'taker_id' => $taker->quiz_taker_id));
    }

    public function updateAnswer()
    {
        $quizTakerId    = Input::get('quiz_taker_id');
        $questionId     = Input::get('question_id');

        $choiceId       = Input::get('choice_id');
        $trueFalse      = Input::get('true_false');
        $shortAnswer    = Input::get('short_answer');
        $identificationAnswer = Input::get('identification_text');

        // check first if there's already an existing row
        // for the answer
        $answer = QuizAnswer::where('quiz_taker_id', '=', $quizTakerId)
            ->where('question_id', '=',  $questionId)
            ->first();

        // get the question details to get the points
        $question = Question::find($questionId);

        // this means that the user hasn't yet answered the question
        if(empty($answer)) {
            // let's now create the answer for the user!
            $toAnswer                   = new QuizAnswer;
            $toAnswer->quiz_taker_id    = $quizTakerId;
            $toAnswer->question_id      = $questionId;

            // question is a multiple choice
            if(isset($choiceId)) {
                // determine if answer is correct
                $checkAnswer = MultipleChoice::where('question_id', '=', $questionId)
                    ->where('multiple_choice_id', '=', $choiceId)
                    ->first();

                switch ($checkAnswer->is_answer) {
                    case 'TRUE' :
                        $toAnswer->is_correct = 'TRUE';
                        $toAnswer->points = $question->question_point;
                        break;
                    case 'FALSE' :
                        $toAnswer->is_correct = 'FALSE';
                        $toAnswer->points = 0;
                        break;
                    default:
                        break;
                }

                $toAnswer->multiple_choice_answer = $choiceId;
            // question is a true false
            } else if(isset($trueFalse)) {
                // determine if the answer is correct
                $checkAnswer = TrueFalse::where('question_id', '=', $questionId)
                    ->first();

                if($checkAnswer->answer == $trueFalse) {
                    $toAnswer->is_correct = 'TRUE';
                    $toAnswer->points = $question->question_point;
                }

                if($checkAnswer->answer != $trueFalse) {
                    $toAnswer->is_correct = 'FALSE';
                    $toAnswer->points = 0;
                }

                $toAnswer->true_false_answer = $trueFalse;
            // question is a short answer
            } else if(isset($shortAnswer)) {
                $toAnswer->short_answer_text = $shortAnswer;
            } else if(isset($identificationAnswer)) {
                // get identification answer
                $thisIdentification = Identification::where('question_id', '=', $questionId)
                    ->first();
                if(strtolower($thisIdentification->answer) == strtolower($identificationAnswer)) {
                    $toAnswer->is_correct = 'TRUE';
                    $toAnswer->points = $question->question_point;
                }

                if(strtolower($thisIdentification->answer) != strtolower($identificationAnswer)) {
                    $toAnswer->is_correct = 'FALSE';
                    $toAnswer->points = 0;
                }
            }

            $toAnswer->save();
        }

        // user already answered the question
        if(!empty($answer)) {
            // let's update the answer
             // question is a multiple choice
            if(isset($choiceId)) {
                // determine if answer is correct
                $checkAnswer = MultipleChoice::where('question_id', '=', $questionId)
                    ->where('multiple_choice_id', '=', $choiceId)
                    ->first();

                switch ($checkAnswer->is_answer) {
                    case 'TRUE' :
                        $answer->is_correct = 'TRUE';
                        $answer->points = $question->question_point;
                        break;
                    case 'FALSE' :
                        $answer->is_correct = 'FALSE';
                        $answer->points = 0;
                        break;
                    default:
                        break;
                }

                $answer->multiple_choice_answer = $choiceId;
            // question is a true false
            } else if(isset($trueFalse)) {
                // determine if the answer is correct
                $checkAnswer = TrueFalse::where('question_id', '=', $questionId)
                    ->first();

                if($checkAnswer->answer == $trueFalse) {
                    $answer->is_correct = 'TRUE';
                    $answer->points = $question->question_point;
                }

                if($checkAnswer->answer != $trueFalse) {
                    $answer->is_correct = 'FALSE';
                    $answer->points = 0;
                }

                $answer->true_false_answer = $trueFalse;
            // question is a short answer
            } else if(isset($shortAnswer)) {
                $answer->short_answer_text = $shortAnswer;
            } else if(isset($identificationAnswer)) {
                $answer->identification_answer = $identificationAnswer;

                $thisIdentification = Identification::where('question_id', '=', $questionId)
                    ->first();
                if(strtolower($thisIdentification->answer) == strtolower($identificationAnswer)) {
                    $answer->is_correct = 'TRUE';
                    $answer->points = $question->question_point;
                }

                if(strtolower($thisIdentification->answer) != strtolower($identificationAnswer)) {
                    $answer->is_correct = 'FALSE';
                    $answer->points = 0;
                }
            }

            $answer->save();
        }

        return Response::json(array('error' => false));
    }

    public function timeRemaining()
    {
        $quizTakerId    = Input::get('quiz_taker_id');
        $time           = Input::get('time');

        $taker = QuizTaker::find($quizTakerId);
        $taker->time_remaining = $time;
        $taker->save();

        return Response::json(array('error' => false));
    }

    public function submitQuiz()
    {
        $itemsCorrect   = 0;
        $totalPoints    = 0;

        $quizId         = Input::get('quiz_id');
        $quizTakerId    = Input::get('quiz_taker_id');
        $timeRemaining  = Input::get('time_remaining');

        // get the answers
        $answers = QuizAnswer::where('quiz_taker_id', '=', $quizTakerId)
            ->get();

        foreach($answers as $answer) {
            // check the correct answers
            if($answer->is_correct == 'TRUE') {
                $itemsCorrect++;
            }

            // compute total score
            $totalPoints += $answer->points;
        }

        // check if there are unchecked questions
        $unchecked = QuizAnswer::where('quiz_taker_id', '=', $quizTakerId)
            ->where('is_correct', '=', '')
            ->first();

        $status = (empty($unchecked)) ? 'GRADED' : 'UNGRADED';

        // save data
        $taker = QuizTaker::find($quizTakerId);
        $taker->status = $status;
        $taker->score = $totalPoints;
        $taker->no_items_correct = $itemsCorrect;
        $taker->time_remaining = $timeRemaining;
        $taker->save();

        Notification::setup('quiz_submitted', array(
            'involved_id' => $quizTakerId));

        // create notification
        if(empty($unchecked)) {
            Notification::setup('quiz_graded', array(
                'involved_id' => $quizTakerId));
        }

        // return redirect url
        return Response::json(array('lz' => Request::root().'/home'));
    }
}
