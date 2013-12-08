<?php //-->

class AjaxQuizManagerController extends BaseController
{
    public function takerDetails()
    {
        $questions = null;
        $timeRemained = 0;

        $quizId = Input::get('quiz_id');
        $takerId = Input::get('taker_id');

        $takerDetails = QuizTaker::where('user_id', '=', $takerId)
            ->where('quiz_id', '=', $quizId)
            ->first();

        $userDetails = User::find($takerId);
        // get quiz details
        $quiz = Quiz::find($quizId);

        if(!empty($takerDetails)) {
            // get the questions
            $questions = QuestionList::getQuizQuestions($quizId, $takerDetails->quiz_taker_id);
            // compute the time the user took the exam
            $timeRemained = $takerDetails->time_remaining;
        }

        $timeTaken = gmdate("H:i:s", ($quiz->time_limit - $timeRemained));

        return View::make('ajax.quizmanager.takerdetails')
            ->with('userDetails', $userDetails)
            ->with('takerDetails', $takerDetails)
            ->with('timeTaken', $timeTaken)
            ->with('questions', $questions)
            ->with('quiz', $quiz);
    }

    public function takerLists()
    {
        $type = Input::get('type');
        $quizId = Input::get('quiz_id');

        $takers = QuizTaker::getQuizRecipients($quizId, $type);

        return View::make('ajax.quizmanager.takerlists')
            ->with('takers', $takers);
    }

    public function updateUngraded()
    {
        $answerId = Input::get('answer_id');
        $point = Input::get('point');

        $state = Input::get('state');
        $totalPoint = Input::get('total_point');

        $answer = QuizAnswer::find($answerId);
        if(isset($point)) {
            // update the answer status
            if($point == 0) { $answer->is_correct = 'FALSE'; }
            if($point != 0) { $answer->is_correct = 'TRUE'; }

            $answer->points = $point;
            $answer->save();
        }

        if(isset($state)) {
            switch ($state) {
                case 'incorrect' :
                    $answer->is_correct = 'FALSE';
                    $answer->points = 0;
                    break;
                case 'correct' :
                    $answer->is_correct = 'TRUE';
                    $answer->points = $totalPoint;
                    break;
            }

            $answer->save();
        }

        // get the answers
        $answers = QuizAnswer::where('quiz_taker_id', '=', $answer->quiz_taker_id)
            ->get();

        $totalPoints = 0;
        $itemsCorrect = 0;
        foreach($answers as $answer) {
            // check the correct answers
            if($answer->is_correct == 'TRUE') {
                $itemsCorrect++;
            }

            // compute total score
            $totalPoints += $answer->points;
        }

        // check if there are unchecked questions
        $unchecked = QuizAnswer::where('quiz_taker_id', '=', $answer->quiz_taker_id)
            ->whereNull('is_correct', '=', '')
            ->first();

        // set the status of the taker
        $status = (empty($unchecked)) ? 'GRADED' : 'UNGRADED';

        // save data
        $taker = QuizTaker::find($answer->quiz_taker_id);
        $taker->status = $status;
        $taker->score = $totalPoints;
        $taker->no_items_correct = $itemsCorrect;
        $taker->save();
        
        return Response::json(array(
            'error' => false,
            'total_score' => $totalPoints));
    }
}
