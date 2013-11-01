<?php //-->
/**
 * all requests should be in AJAX
 * return response can be either in JSON or HTML format
 *
 * @package default
 * @author
 **/

class AjaxPostCreatorController extends BaseController {

    public function createNote() {
        if(Request::ajax()) {
            $note       = Input::get('note-content');
            $recipients = Input::get('note-recipients');

            // save the note into the database
            $newNote                    = new Post;
            $newNote->user_id           = Auth::user()->id;
            $newNote->post_type         = 'note';
            $newNote->note_content      = $note;
            $newNote->post_timestamp    = time();
            $newNote->save();

            // get the recipients.
            // will use for loop because it is stored in an array
            for($x = 0; $x < count($recipients); $x++) {
                // exploded the value to get the id and recipient type
                $exploded = explode('-', $recipients[$x]);
                // save to database
                $addRecipient                   = new PostRecipient;
                $addRecipient->post_id          = $newNote->post_id;
                $addRecipient->recipient_id     = $exploded[0];
                $addRecipient->recipient_type   = $exploded[1];
                $addRecipient->save();
            }

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::where('post_id', '=', $newNote->post_id)
                    ->join('users', 'posts.user_id', '=', 'users.id')
                    ->first());
        }
    }

    public function createAlert() {
        if(Request::ajax()) {
            $alert          = Input::get('alert-content');
            $recipients     = Input::get('alert-recipients');

            // save the note into the database
            $newAlert                   = new Post;
            $newAlert->user_id          = Auth::user()->id;
            $newAlert->post_type        = 'alert';
            $newAlert->alert_content    = $alert;
            $newAlert->post_timestamp   = time();
            $newAlert->save();

            // get the recipients.
            // will use for loop because it is stored in an array
            for($x = 0; $x < count($recipients); $x++) {
                // exploded the value to get the id and recipient type
                $exploded = explode('-', $recipients[$x]);
                // save to database
                $addRecipient = new PostRecipient;
                $addRecipient->post_id = $newAlert->post_id;
                $addRecipient->recipient_id = $exploded[0];
                $addRecipient->recipient_type = $exploded[1];
                $addRecipient->save();
            }

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::where('post_id', '=', $newAlert->post_id)
                    ->join('users', 'posts.user_id', '=', 'users.id')
                    ->first());
        }
    }

    public function postCreateQuiz()
    {
        if(Request::ajax()) {
            $quizId         = Input::get('quiz-id');
            $quizDueDate    = Input::get('due-date');
            $recipients     = Input::get('quiz-recipients');

            // save the quiz into the database
            $newQuiz                    = new Post;
            $newQuiz->user_id           = Auth::user()->id;
            $newQuiz->post_type         = 'quiz';
            $newQuiz->quiz_id           = $quizId;
            $newQuiz->quiz_due_date     = $quizDueDate;
            $newQuiz->post_timestamp    = time();
            $newQuiz->save();

            // get the recipients.
            // will use for loop because it is stored in an array
            for($x = 0; $x < count($recipients); $x++) {
                // exploded the value to get the id and recipient type
                $exploded = explode('-', $recipients[$x]);
                // save to database
                $addRecipient = new PostRecipient;
                $addRecipient->post_id = $newQuiz->post_id;
                $addRecipient->recipient_id = $exploded[0];
                $addRecipient->recipient_type = $exploded[1];
                $addRecipient->save();
            }

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::where('post_id', '=', $newQuiz->post_id)
                    ->join('users', 'posts.user_id', '=', 'users.id')
                    ->first());
        }
    }

    public function updatePost()
    {
        if(Request::ajax()) {
            $postId = Input::get('post-id');
            $messagePost = Input::get('message-post');

            // check first what type of post is the post
            $post = Post::find($postId);
            switch ($post->post_type) {
                case 'note':
                    $post->note_content = $messagePost;
                    break;
                case 'alert':
                    $post->alert_content = $messagePost;
                    break;
            }

            $post->save();

            return Response::json(array('error' => false));
        }
    }

    public function uploadPost()
    {
        $files = Input::file('files');

        foreach($files as $key => $file) {
            $test[$key] = $file->getClientOriginalName();
        }

        return Response::json(array('result' => $test));
    }
}
