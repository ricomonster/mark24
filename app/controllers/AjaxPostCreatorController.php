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
            $attachedFiles  = Input::get('attached-files');

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
                    $attach->file_id = $attachedFiles[$f];
                    $attach->save();
                }
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
        // prep some data
        // $dropPoint = public_path().'/assets/thelibrary/'.Auth::user()->hashed_id;

        // $file = Input::file('files');

        // $fileName       = $file->getClientOriginalName();
        // $fileExtension  = $file->getClientOriginalExtension();
        // $mime           = $file->getMimeType();
        // // upload file
        // $file->move($dropPoint, $fileName);

        // // check if file is uploaded
        // if(Input::hasFile('files')) {
        //     // save the file!
        //     $newFile = new FileLibrary;
        //     $newFile->user_id = Auth::user()->id;
        //     $newFile->file_name = $fileName;
        //     $newFile->file_extension = $fileExtension;
        //     $newFile->mime_type = $mime;
        //     $newFile->save();

        //     $details = FileLibrary::find($newFile->file_library_id);

        //     return Response::json(array('error' => false, 'file' => $details->toArray()));
        // }

        // // file not uploaded
        // if(!Input::hasFile('files')) {
        //     return Response::json(array(
        //         'error'     => true,
        //         'message'   => "File '".$file->getClientOriginalName()."' could not be uploaded. Please try again later"));
        // }
        $details = FileLibrary::find(5);

        return Response::json(array('error' => false, 'attached' => $details->toArray()));
    }
}
