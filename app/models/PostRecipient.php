<?php //-->

class PostRecipient extends Eloquent {
    protected $table = 'post_recipients';

    public static function getRecipients($postId) {
        $recipients = new StdClass();

        $recipientLists = PostRecipient::where('post_id', '=', $postId)
            ->get();

        foreach($recipientLists as $key => $recipientList) {
            if($recipientList->recipient_type == 'group') {
                // get group details
                $recipients->groups[] = Group::find($recipientList->recipient_id);
            } else {
                // get user details
                $recipients->users[] = User::find($recipientList->recipient_id);
            }
        }

        return $recipients;
    }
}
