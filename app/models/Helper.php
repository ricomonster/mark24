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
}
