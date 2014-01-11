<?php //-->

class Post extends Eloquent {
    protected $table        = 'posts';
    protected $primaryKey   = 'post_id';

    public static function getPost($postId)
    {
        $post = Post::find($postId);

        $details = new StdClass();
        $details = $post;
        // get recipients
        $details->recipients = PostRecipient::getRecipients($post->post_id);
        // get user
        $details->user = User::find($post->user_id);
        // get likes
        $likes = new StdClass();
        $likeCount = Like::where('post_id', '=', $post->post_id)
            ->get()->count();
        $likes->count = $likeCount;
        if($likeCount != 0) {
            // get the list of likers
            $likes->likers = Helper::likes($post->post_id);
            // check if the current user liked the post
            $likes->user_liked_post = Like::where('post_id', '=', $post->post_id)
                ->where('user_id', '=', Auth::user()->id)
                ->first();
        }
        // check if the post is an assignment
        if($post->post_type == 'assignment') {
            $assignment = Assignment::find($post->assignment_id);
            $details->assignment = $assignment;
            // if the user is a instructor
            // get the number of users who submitted the quiz
            if(Auth::user()->account_type == 1) {
                $submittedAssignments = AssignmentResponse::where(
                    'assignment_id', '=', $post->assignment_id)
                    ->get()
                    ->count();
                $details->assignments_submitted = $submittedAssignments;
            }

            // if the user is a student
            // check if the student user already submitted the assignment
            if(Auth::user()->account_type == 2) {
                $assignmentSubmitted = AssignmentResponse::where('user_id', '=', Auth::user()->id)
                    ->where('assignment_id', '=', $post->assignment_id)
                    ->first();
                if(!empty($assignmentSubmitted)) {
                    $details->assignment_submitted = $assignmentSubmitted;
                }
            }
        }

        // create object for the comments
        $comments = new StdClass();
        $comments = Comment::where('post_id', '=', $post->post_id)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->orderBy('comments.comment_id', 'ASC')
            ->get();
        // create if there are files attached
        if($post->post_attached_files == 'true') {
            $details->files = FileAttached::where('post_id', '=', $post->post_id)
                ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                ->get();
        }

        // assign the objects
        $details->likes = $likes;
        $details->comments = $comments;

        return $details;
    }

    public static function getAllPosts() {
        // get first posts for the current user
        $groupIds = Group::getMyGroupsId();
        if(!empty($groupIds)) {
            $posts = PostRecipient::orWhere('posts.user_id', '=', Auth::user()->id)
                ->orWhere(function($query) {
                    $query->whereIn('post_recipients.recipient_id', Group::getMyGroupsId())
                        ->where('post_recipients.recipient_type', '=', 'group');
                })
                ->orWhere(function($query) {
                    $query->where('post_recipients.recipient_id', '=', Auth::user()->id)
                        ->where('post_recipients.recipient_type', '=', 'user');
                })
                ->leftJoin('posts', 'post_recipients.post_id', '=', 'posts.post_id')
                ->groupBy('posts.post_id')
                ->orderBy('posts.post_id', 'DESC')
                ->get();

            if(!$posts->isEmpty()) {
                $details = new StdClass();
                foreach($posts as $key => $post) {
                    $details->$key = $post;
                    $details->$key->recipients = PostRecipient::getRecipients($post->post_id);
                    $details->$key->user = User::find($post->user_id);
                    // check if the post is an assignment
                    if($post->post_type == 'assignment') {
                        $assignment = Assignment::find($post->assignment_id);
                        $details->$key->assignment = $assignment;
                        // if the user is a instructor
                        // get the number of users who submitted the quiz
                        if(Auth::user()->account_type == 1) {
                            $submittedAssignments = AssignmentResponse::where(
                                'assignment_id', '=', $post->assignment_id)
                                ->get()
                                ->count();
                            $details->$key->assignments_submitted = $submittedAssignments;
                        }

                        // if the user is a student
                        // check if the student user already submitted the assignment
                        if(Auth::user()->account_type == 2) {
                            $assignmentSubmitted = AssignmentResponse::where('user_id', '=', Auth::user()->id)
                                ->where('assignment_id', '=', $post->assignment_id)
                                ->first();
                            if(!empty($assignmentSubmitted)) {
                                $details->$key->assignment_submitted = $assignmentSubmitted;
                            }
                        }
                    }

                    // if the post is a quiz
                    if($post->post_type == 'quiz') {
                        $quiz = new StdClass();
                        $quiz->details = Helper::getQuizDetails($post->quiz_id);
                        // count number of questions
                        $count = QuestionList::where('quiz_id', '=', $post->quiz_id)
                            ->get()
                            ->count();
                        $quiz->question_count = ($count == 1) ?
                            $count.' question' : $count.' questions';

                        // check the account type of the user
                        if(Auth::user()->account_type == 1) {
                            // get details of the quiz
                            $turnedIn = new StdClass();
                            // get turned in stats
                            $turnedIn->takers = QuizTaker::where('quiz_id', '=', $post->quiz_id)
                                ->where(function($query) {
                                    $query->orWhere('status', '=', 'PASSED')
                                        ->orWhere('status', '=', 'GRADED');
                                })
                                ->get()
                                ->count();
                            $quiz->turned_in = $turnedIn;
                        }

                        // check if the user already take the quiz
                        if(Auth::user()->account_type == 2) {
                            // check if the user already took the quiz
                            $taken = Helper::checkQuizTaken($post->quiz_id);
                            $quiz->taken = (empty($taken)) ? null : $taken;
                        }

                        $details->$key->quiz = $quiz;
                    }

                    // create object for the likes
                    $likes = new StdClass();
                    $likeCount = Like::where('post_id', '=', $post->post_id)
                        ->get()->count();
                    $likes->count = $likeCount;
                    if($likeCount != 0) {
                        // get the list of likers
                        $likes->likers = Helper::likes($post->post_id);
                        // check if the current user liked the post
                        $likes->user_liked_post = Like::where('post_id', '=', $post->post_id)
                            ->where('user_id', '=', Auth::user()->id)
                            ->first();
                    }

                    // create object for the comments
                    $comments = new StdClass();
                    $comments = Comment::where('post_id', '=', $post->post_id)
                        ->join('users', 'comments.user_id', '=', 'users.id')
                        ->orderBy('comments.comment_id', 'ASC')
                        ->get();
                    // create if there are files attached
                    if($post->post_attached_files == 'true') {
                        $details->$key->files = FileAttached::where('post_id', '=', $post->post_id)
                            ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                            ->get();
                    }

                    // assign the objects
                    $details->$key->likes = $likes;
                    $details->$key->comments = $comments;
                }

                return (empty($details)) ? null : $details;
            }
        }

        return false;
    }

    public static function getGroupPosts($groupId) {
        $groupPosts = PostRecipient::where('post_recipients.recipient_id', '=', $groupId)
            ->where('post_recipients.recipient_type', '=', 'group')
            ->join('posts', 'post_recipients.post_id', '=', 'posts.post_id')
            // ->join('users', 'posts.user_id', '=', 'users.id')
            ->groupBy('posts.post_id')
            ->orderBy('posts.post_id', 'DESC')
            ->get();

        if(!$groupPosts->isEmpty()) {
            $details = new StdClass();
            foreach($groupPosts as $key => $post) {
                $details->$key = $post;
                $details->$key->recipients = PostRecipient::getRecipients($post->post_id);
                $details->$key->user = User::find($post->user_id);
                // create object for the likes
                $likes = new StdClass();
                $likeCount = Like::where('post_id', '=', $post->post_id)
                    ->get()->count();
                $likes->count = $likeCount;
                if($likeCount != 0) {
                    // get the list of likers
                    $likes->likers = Helper::likes($post->post_id);
                    // check if the current user liked the post
                    $likes->user_liked_post = Like::where('post_id', '=', $post->post_id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->first();
                }
                // check if the post is an assignment
                if($post->post_type == 'assignment') {
                    $assignment = Assignment::find($post->assignment_id);
                    $details->$key->assignment = $assignment;
                    // if the user is a instructor
                    // get the number of users who submitted the quiz
                    if(Auth::user()->account_type == 1) {
                        $submittedAssignments = AssignmentResponse::where(
                            'assignment_id', '=', $post->assignment_id)
                            ->get()
                            ->count();
                        $details->$key->assignments_submitted = $submittedAssignments;
                    }

                    // if the user is a student
                    // check if the student user already submitted the assignment
                    if(Auth::user()->account_type == 2) {
                        $assignmentSubmitted = AssignmentResponse::where('user_id', '=', Auth::user()->id)
                            ->where('assignment_id', '=', $post->assignment_id)
                            ->first();
                        if(!empty($assignmentSubmitted)) {
                            $details->$key->assignment_submitted = $assignmentSubmitted;
                        }
                    }
                }

                // create object for the comments
                $comments = new StdClass();
                $comments = Comment::where('post_id', '=', $post->post_id)
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->orderBy('comments.comment_id', 'ASC')
                    ->get();
                // create if there are files attached
                if($post->post_attached_files == 'true') {
                    $details->$key->files = FileAttached::where('post_id', '=', $post->post_id)
                        ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                        ->get();
                }

                // assign the objects
                $details->$key->likes = $likes;
                $details->$key->comments = $comments;
            }

            return (empty($details)) ? null : $details;
        }

        return false;
    }
}
