<?php //-->

class QuizResultController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('are-you-a-student');
    }

    public function index($quizId, $postId)
    {
        // get quiz details
        $quiz = Quiz::find($quizId);
        $post = Post::where('post_type', '=', 'quiz')
            ->where('post_id', '=', $postId)
            ->where('quiz_id', '=', $quiz->quiz_id)
            ->first();
        // check if the user already answered the quiz
        $alreadyTaken = QuizTaker::where('user_id', '=', Auth::user()->id)
            ->where('quiz_id', '=', $quiz->quiz_id)
            ->where(function($query) {
                $query->where('status', '=', 'PASSED')
                    ->orWhere('status', '=', 'GRADED');
            })
            ->first();

        // validate if quiz is empty
        if(empty($quiz) || empty($alreadyTaken)) {
            // redirect to 404
            return View::make('templates.fourohfour');
        }

        // get how many questions does the user taken
        $countAnswer = QuizAnswer::where('quiz_taker_id', '=', $alreadyTaken->quiz_taker_id)
            ->get()
            ->count();
        // count ungraded answers
        $countUngraded = QuizAnswer::where('quiz_taker_id', '=', $alreadyTaken->quiz_taker_id)
            ->where('is_correct', '=', '')
            ->get()
            ->count();

        // get the questions
        $questions = QuestionList::getQuizQuestions($quizId, $alreadyTaken->quiz_taker_id);
        // get the details of the user who assigned the quiz
        $assigned = User::find($quiz->user_id);

        // show quiz sheet page
        return View::make('quizresult.index')
            ->with('quiz', $quiz)
            ->with('takerDetails', $alreadyTaken)
            ->with('timeDetails', array(
                'limit' => $this->timeConverter($quiz->time_limit),
                'spent' => $this->timeConverter($quiz->time_limit - $alreadyTaken->time_remaining)))
            ->with('questions', $questions)
            ->with('assigned', $assigned)
            ->with('answerCount', $countAnswer)
            ->with('ungraded', $countUngraded);
    }

    protected function timeConverter($timeInSeconds)
    {
        $totalSeconds = $timeInSeconds;
        $hours = ( $totalSeconds / 3600 ) % 24;
        $minutes = ( $totalSeconds / 60 ) % 60;
        $seconds = $totalSeconds % 60;

        $result = ($hours < 10 ? "0".$hours : $hours).":"
            .($minutes < 10 ? "0".$minutes : $minutes).":"
            .($seconds  < 10 ? "0".$seconds : $seconds);

        return $result;
    }
}
