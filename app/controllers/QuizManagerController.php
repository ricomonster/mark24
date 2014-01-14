<?php //-->

class QuizManagerController extends BaseController
{
    public function __construct() {
        $this->beforeFilter('are-you-a-teacher');
    }

    public function index($id, $postId)
    {
        $quiz = Quiz::find($id);
        $post = Post::where('post_type', '=', 'quiz')
            ->where('post_id', '=', $postId)
            ->where('quiz_id', '=', $quiz->quiz_id)
            ->first();
        // get recipients of the quiz
        $takers = QuizTaker::getQuizRecipients($id, 'all');
        // get the questions
        $questions = QuestionList::getQuizQuestions($id);
        // get the details of the post

        // get the users with high scores
        $topnotchers = QuizTaker::where('quiz_id', '=', $quiz->quiz_id)
            ->leftJoin('users', 'quiz_takers.user_id', '=', 'users.id')
            ->orderBy('score', 'DESC')
            ->get();

        return View::make('quizmanager.index')
            ->with('post', $post)
            ->with('quiz', $quiz)
            ->with('takers', $takers)
            ->with('questions', $questions)
            ->with('topnotchers', $topnotchers);
    }
}
