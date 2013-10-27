<?php //-->

class Helper
{

    public static function thumbnailMaker($directory, $image_to_thumbnail, $new_filename, $size)
    {
        $image_file = $new_filename;
        $image = $directory.'/'.$image_to_thumbnail;

        if (file_exists($image)) {

            $source_size = getimagesize($image);

            if ($source_size !== false) {

                $thumb_width = $size;
                $thumb_height = $size;

                switch($source_size['mime']) {
                    case 'image/jpeg':
                        $source = imagecreatefromjpeg($image);
                        break;
                    case 'image/png':
                        $source = imagecreatefrompng($image);
                        break;
                    case 'image/gif':
                        $source = imagecreatefromgif($image);
                        break;
                }

                $source_aspect = round(($source_size[0] / $source_size[1]), 1);
                $thumb_aspect = round(($thumb_width / $thumb_height), 1);

                if ($source_aspect < $thumb_aspect) {
                    $new_size = array($thumb_width, ($thumb_width / $source_size[0]) * $source_size[1]);
                    $source_pos = array(0, ($new_size[1] - $thumb_height) / 2);
                }else if ($source_aspect > $thumb_aspect) {
                    $new_size = array(($thumb_width / $source_size[1]) * $source_size[0], $thumb_height);
                    $source_pos = array(($new_size[0] - $thumb_width) / 2, 0);
                } else {
                    $new_size = array($thumb_width, $thumb_height);
                    $source_pos = array(0, 0);
                }

                if ($new_size[0] < 1) $new_size[0] = 1;
                if ($new_size[1] < 1) $new_size[1] = 1;

                $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
                    imagecopyresampled($thumb, $source, 0, 0, $source_pos[0], $source_pos[1], $new_size[0], $new_size[1], $source_size[0], $source_size[1]);

                switch($source_size['mime']) {
                    case 'image/jpeg':
                        imagejpeg($thumb, $directory.'/'.$image_file);
                        break;
                    case 'image/png':
                        imagepng($thumb, $directory.'/'.$image_file);
                        break;
                    case 'image/gif':
                        imagegif($thumb, $directory.'/'.$image_file);
                        break;
                }
            }
        }
    }

    // get responses for the question
    public static function getResponses($questionId, $questionType)
    {
        switch($questionType) {
            case 'MULTIPLE_CHOICE' :
                $response = MultipleChoice::where('question_id', '=', $questionId)
                    ->get();
                break;

            case 'TRUE_FALSE' :
                $response = TrueFalse::where('question_id', '=', $questionId)
                    ->first();
                break;
            default :
                $response = null;
                break;
        }

        return $response;
    }

    // get the comments of a post
    public static function getComments($postId)
    {
        $comments = Comment::where('post_id', '=', $postId)
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->orderBy('comments.comment_id', 'ASC')
            ->get();

        return $comments;
    }

    // gets the details of the quiz
    public static function getQuizDetails($quizId)
    {
        $details = Quiz::find($quizId);
        return $details;
    }

    // gets the answer of the question by the taker
    public static function getAnswer($quizTakerId, $questionId)
    {
        // check first if there's an answer
        $answer = QuizAnswer::where('quiz_taker_id', '=', $quizTakerId)
            ->where('question_id', '=', $questionId)
            ->first();

        return $answer;
    }

    public static function checkQuizTaken($quizId)
    {
        $check = QuizTaker::where('quiz_id', '=', $quizId)
            ->where('status', '=', 'PASSED')
            ->where('user_id', '=', Auth::user()->id)
            ->first();

        return $check;
    }

    // creates an seo friendly url by passing a string
    public static function seoFriendlyUrl($string)
    {
        $numwords = 10;
        $padding = null;

        $output = strtok($string, " \n");
        while(--$numwords > 0) $output .= " " . strtok(" \n");
        if($output != $string) $output .= $padding;

        return preg_replace('/[^a-z0-9_-]/i', '', strtolower(str_replace(' ', '-', trim($output))));
    }

    // creates a timestamp of a post
    public static function timestamp($timestamp)
    {
        $string = null;

        $timenow = time();
        $diff = $timenow - $timestamp;

        switch(1){
            case ($diff < 60):
                $count = $diff;
                if ($count == 0) {
                    $string = 'just now';
                }

                if($count != 0) {
                    $string = $count.' seconds ago';
                }

                break;

            case ($diff > 60 && $diff < 3600):
                $count = floor($diff/60);
                $ago = ($count == 1) ? ' minute ago' : ' minutes ago';
                $string = $count.$ago;
                break;

            case ($diff > 3600 && $diff < 86400):
                $count = floor($diff/3600);
                $ago = ($count == 1) ? ' hour ago' : ' hours ago';
                $string = $count.$ago;
                break;

            case ($diff >  86400 && $diff < 604800) :
                $count = floor($diff/86400);
                $ago = ($count == 1) ? ' day ago' : ' days ago';
                $string = $count.$ago;
                break;
            case ($diff > 604800) :
                $string = date('M d', $timestamp);
                break;
        }

        return $string;
    }

    public static function getTakenDetails($quizId)
    {
        $takers = QuizTaker::where('quiz_id', '=', $quizId)
            ->where(function($query) {
                $query->where('status', '=', 'PASSED')
                    ->where('status', '=', 'GRADED');
            })
            ->get()
            ->count();

        $count = QuestionList::where('quiz_id', '=', $quizId)
            ->get()
            ->count();

        return array(
            'takers'    => $takers,
            'count'     => ($count == 1) ? $count.' question' : $count.' questions');
    }

    public static function avatar($width, $type, $class = null, $user = null)
    {
        // check first there's a set user
        if(is_null($user)) {
            // we will use the current user
            $details = Auth::user();
        }

        if(!is_null($user)) {
            // fetch the data of the user
            $details = User::find($user);
        }

        // set up the img tag
        if($details->avatar == 'default_avatar.png') {
            $tag =
                '<img src="/assets/defaults/avatar/'.$details->avatar_normal.'"
                class="'.$class.'" width="'.$width.'">';
        }

        if($details->avatar != 'default_avatar.png') {
            switch($type) {
                case 'small' :
                    $avatar = $details->avatar_small;
                    break;
                case 'normal' :
                    $avatar = $details->avatar_normal;
                    break;
                case 'large' :
                    $avatar = $details->avatar_large;
                    break;
                default :
                    $avatar = $details->avatar_normal;
                    break;
            }

            $tag =
                '<img src="/assets/avatars/'.$details->hashed_id.'/'.$avatar.'"
                class="'.$class.'" width="'.$width.'">';
        }

        return $tag;
    }
}
