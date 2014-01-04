<?php //-->
/**
 * all requests should be in AJAX
 * return response can be either in JSON or HTML format
 *
 * @package default
 * @author
 **/

class AjaxPostCreatorController extends BaseController {
    protected $errors = null;

    public function createNote() {
        if(Request::ajax()) {
            $note           = Input::get('note-content');
            $recipients     = Input::get('note-recipients');
            $attachedFiles  = Input::get('attached-file-id');

            // save the note into the database
            $newNote                        = new Post;
            $newNote->user_id               = Auth::user()->id;
            $newNote->post_type             = 'note';
            $newNote->note_content          = $note;
            $newNote->post_attached_files   = (empty($attachedFiles)) ? 'false' : 'true';
            $newNote->post_timestamp        = time();
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

            // check if there are attached files
            if(!empty($attachedFiles)) {
                // loop to get the ids
                for($f = 0; $f < count($attachedFiles); $f++) {
                    $attach = new FileAttached;
                    $attach->post_id = $newNote->post_id;
                    $attach->user_id = Auth::user()->id;
                    $attach->file_id = $attachedFiles[$f];
                    $attach->save();
                }
            }

            // setup the notification
            Notification::createNotification($newNote->post_id, 'post');

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::getPost($newNote->post_id));
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

            // setup the notification
            Notification::createNotification($newAlert->post_id, 'post');

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::getPost($newAlert->post_id));
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

            // setup the notification
            Notification::createNotification($newQuiz->post_id, 'post');

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::getPost($newQuiz->post_id));
        }
    }

    public function postCreateAssignment()
    {
        if(Request::ajax()) {
            $assignmentTitle = Input::get('assignment-title');
            $dueDate = Input::get('due-date');
            $assignmentDescription = Input::get('assignment-description');
            $assignmentLock = Input::get('assignment-lock');
            $recipients = Input::get('assignment-recipients');
            $attachedFiles  = Input::get('attached-file-id');

            // insert assignment
            $assignment = new Assignment;
            $assignment->user_id = Auth::user()->id;
            $assignment->title = $assignmentTitle;
            $assignment->description = $assignmentDescription;
            $assignment->assignment_lock = (isset($assignmentLock)) ? $assignmentLock : 0;
            $assignment->save();

            // insert to posts
            $newAssignment = new Post;
            $newAssignment->user_id = Auth::user()->id;
            $newAssignment->post_type = 'assignment';
            $newAssignment->assignment_id = $assignment->assignment_id;
            $newAssignment->assignment_due_date = $dueDate;
            $newAssignment->post_attached_files = (empty($attachedFiles)) ? 'false' : 'true';
            $newAssignment->post_timestamp = time();
            $newAssignment->save();

            // get the recipients.
            // will use for loop because it is stored in an array
            for($x = 0; $x < count($recipients); $x++) {
                // exploded the value to get the id and recipient type
                $exploded = explode('-', $recipients[$x]);
                // save to database
                $addRecipient = new PostRecipient;
                $addRecipient->post_id = $newAssignment->post_id;
                $addRecipient->recipient_id = $exploded[0];
                $addRecipient->recipient_type = $exploded[1];
                $addRecipient->save();
            }

            // check if there are attached files
            if(!empty($attachedFiles)) {
                // loop to get the ids
                for($f = 0; $f < count($attachedFiles); $f++) {
                    $attach = new FileAttached;
                    $attach->post_id = $newAssignment->post_id;
                    $attach->user_id = Auth::user()->id;
                    $attach->file_id = $attachedFiles[$f];
                    $attach->save();
                }
            }

            // setup the notification
            Notification::createNotification($newAssignment->post_id, 'post');

            // return the HTML to show the newest post
            // to be loaded on the page
            return View::make('ajax.postcreator.postitem')
                ->with('post', Post::getPost($newAssignment->post_id));
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
}
