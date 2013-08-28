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
            $newNote                = new Post;
            $newNote->writer_id     = Auth::user()->id;
            $newNote->post_type     = 'note';
            $newNote->note_content  = $note;
            $newNote->save();

            // get the recipients.
            // will use for loop because it is stored in an array
            for($x = 0; $x < count($recipients); $x++) {
                // exploded the value to get the id and recipient type
                $exploded = explode('-', $recipients[$x]);
                // save to database
                $addRecipient = new PostRecipient;
                $addRecipient->post_id = $newNote->post_id;
                $addRecipient->recipient_id = $exploded[0];
                $addRecipient->recipient_type = $exploded[1];
                $addRecipient->save();
                // echo 'ID: '.$exploded[0].' Name:'.$exploded[1].'<br/>';
            }

            // return the HTML to show the newest post
            // to be loaded on the page
            echo 'SUCCESS!';
        }
    }

}
