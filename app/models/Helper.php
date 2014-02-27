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

    public static function checkQuizTaken($quizId, $postId)
    {
        $check = QuizTaker::where('quiz_id', '=', $quizId)
            ->where('post_id', '=', $postId)
            ->where(function($query) {
                $query->orWhere('status', '=', 'UNGRADED')
                    ->orWhere('status', '=', 'GRADED');
            })
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
                $query->orWhere('status', '=', 'PASSED')
                    ->orWhere('status', '=', 'GRADED');
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

    public static function checkUserOnline($timestamp)
    {
        $currentTime = time();
        // check if the user if the last online activity is 7 minutes ago
        if($currentTime - $timestamp < 420){
            return '<i class="fa fa-circle user-online pull-right"></i>';
        }
    }

    public static function chatTimestamp($timestamp)
    {
        $timenow = time();
        $diff = $timenow - $timestamp;

        switch(1){
            case ($diff < 86400):
                $string = date('h:i a', $timestamp);
                break;
            case ($diff >  86400 ) :
                $string = date('M d', $timestamp);
                break;
        }

        return $string;
    }

    public static function notificationDates($date)
    {
        if($date == date('Y-m-d')) {
            return 'Today';
        } else if($date == date('Y-m-d', strtotime('-1 day'))) {
            return 'Yesterday';
        } else if($date > date('Y-m-d', strtotime('-1 year'))) {
            return date('F d, Y', strtotime($date));
        } else {
            return date('F d', strtotime($date));
        }
    }

    public static function likes($postId)
    {
        $message = null;
        $counter = 0;
        // this will setup the like message
        $likers = Like::where('post_id', '=', $postId)
            ->leftJoin('users', 'likes.user_id', '=', 'users.id')
            ->get();
        // check first if the current user liked the post
        $userLike = Like::where('post_id', '=', $postId)
            ->where('user_id', '=', Auth::user()->id)
            ->first();
        $likeCount = $likers->count();
        $message = '<i class="fa fa-thumbs-up"></i> ';
        if(!empty($userLike)) {
            $message .= '<span class="you"><a href="#">You</a>'.
                (($likeCount >= 3) ? '<span>,</span>' : null).'</span> ';
        }

        // there be atleast 3 names to be shown
        // check how many likes
        if($likeCount >= 3) {
            foreach($likers as $liker) {
                if(Auth::user()->id == $liker->user_id) continue;
                if((empty($userLike) && $counter == 2) || (!empty($userLike) && $counter == 1)) {
                    $message .= '<span class="liker"><a href="#">'.$liker->name.'</a></span> ';
                    break;
                } else {
                    $message .= '<span class="liker"><a href="#">'.$liker->name.'</a><span>,</span></span> ';
                }

                $counter++;
            }

            $otherLikersCount = $likeCount - 3;
            $message .= (($otherLikersCount == 0) ?
                null : ' and <a href="#">'.$otherLikersCount.' others</a>').' like this';
        }

        if($likeCount < 3 && $likeCount != 0) {
            foreach($likers as $liker) {
                if(Auth::user()->id == $liker->user_id) continue;
                if(empty($userLike) && $counter == 0) {
                    $message .= '<span class="liker"><a href="#">'.$liker->name.'</a></span> ';
                }

                if((empty($userLike) && $counter == 1) || !empty($userLike)) {
                    $message .= '<span>and</span> <span class="liker"><a href="#">'.$liker->name.'</a></span>';
                }

                $counter++;
            }

            $message .= ' like this';
        }

        return $message;
    }

    public static function device()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browserName = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } else if (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'Mac OS X';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$userAgent) && !preg_match('/Opera/i',$userAgent)) {
            $browserName = 'Internet Explorer';
            $ub = "MSIE";
        } else if(preg_match('/Firefox/i',$userAgent)) {
            $browserName = 'Mozilla Firefox';
            $ub = "Firefox";
        } else if(preg_match('/Chrome/i',$userAgent)) {
            $browserName = 'Google Chrome';
            $ub = "Chrome";
        } else if(preg_match('/Safari/i',$userAgent)) {
            $browserName = 'Apple Safari';
            $ub = "Safari";
        } else if(preg_match('/Opera/i',$userAgent)) {
            $browserName = 'Opera';
            $ub = "Opera";
        } else if(preg_match('/Netscape/i',$userAgent)) {
            $browserName = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $userAgent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($userAgent,"Version") < strripos($userAgent,$ub)) {
                $version= $matches['version'][0];
            } else {
                $version= $matches['version'][1];
            }
        } else {
            $version= $matches['version'][0];
        }

        // check if we have a number
        if ($version==null || $version=="") {$version="?";}

        // get user ip
        //check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return array(
            'userAgent' => $userAgent,
            'name'      => $browserName,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'   => $pattern,
            'ip'        => $ip
        );
    }

    public static function drawCalendar($month,$year){
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $calendar.= '<td class="calendar-day">';
                /* add in the day number */
                $calendar.= '<div class="day-number">'.$list_day.'</div>';

                /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
                $calendar.= str_repeat('<p> </p>',2);

            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if($days_in_this_week < 8):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';

        /* all done, return result */
        return $calendar;
    }

    public static function confirmAccount()
    {
        if (Auth::user()->confirmed_account == 0) {
            return '<div class="confirm-account alert alert-info">'
                .'<strong>Please confirm your email.</strong>'
                .'<p><a href="#" class="send-confirmation">Just click this link'
                .'and we will send the confirmation mail.</a></p>'
                .'</div>';
        }
    }
}
