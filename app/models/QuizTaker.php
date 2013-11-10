<?php //-->

class QuizTaker extends Eloquent
{
    protected $table = 'quiz_takers';
    protected $primaryKey = 'quiz_taker_id';
    
    public static function getQuizRecipients($quizId)
    {
        // get first the post row
        $post = Post::where('post_type', '=', 'quiz')
            ->where('quiz_id', '=', $quizId)
            ->first();
        
        // get the recipients of the quiz
        $recipients = PostRecipient::where('post_id', '=', $post->post_id)
            ->get()
            ->toArray();
        
        // extract the recipients
        foreach($recipients as $key => $recipient) {
            // check if the recipient is a group
            if($recipient['recipient_type'] === 'group') {
                // get the group details
                $group = Group::find($recipient['recipient_id'])->toArray();
                // get the group members
                $group['members'] = GroupMember::where('group_id', '=', $group['group_id'])
                    ->whereNotIn('users.account_type', array(0, 1))
                    ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                    ->orderBy('users.lastname')
                    ->get()
                    ->toArray();
            }
            
            $takers[] = $group;
        }
        
        return $takers;
    }
}
