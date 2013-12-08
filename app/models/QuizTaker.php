<?php //-->

class QuizTaker extends Eloquent
{
    protected $table = 'quiz_takers';
    protected $primaryKey = 'quiz_taker_id';

    public static function getQuizRecipients($quizId, $takerStatus)
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
                if($takerStatus == 'all') {
                    $group['members'] = GroupMember::where('group_id', '=', $group['group_id'])
                        ->whereNotIn('users.account_type', array(0, 1))
                        ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                        ->orderBy('users.lastname')
                        ->get()
                        ->toArray();
                }

                if($takerStatus != 'all') {
                    $members = GroupMember::where('group_id', '=', $group['group_id'])
                        ->whereNotIn('users.account_type', array(0, 1))
                        ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                        ->orderBy('users.lastname')
                        ->get()
                        ->toArray();

                    switch($takerStatus) {
                        case 'ungraded' :
                            foreach($members as $key => $member) {
                                $ungraded = QuizTaker::where('status', '=', 'UNGRADED')
                                    ->where('quiz_id', '=', $quizId)
                                    ->where('user_id', '=', $member['id'])
                                    ->first();
                                if(!empty($ungraded)) {
                                    $group['members'][$key] = $member;
                                }
                            }

                            break;
                        case 'graded' :
                            foreach($members as $key => $member) {
                                $graded = QuizTaker::where('status', '=', 'GRADED')
                                    ->where('quiz_id', '=', $quizId)
                                    ->where('user_id', '=', $member['id'])
                                    ->first();
                                if(!empty($graded)) {
                                    $group['members'][$key] = $member;
                                }
                            }

                            break;
                        case 'not_turned_in' :
                            foreach($members as $key => $member) {
                                $notTurnedIn = QuizTaker::where('quiz_id', '=', $quizId)
                                    ->where(function($query) {
                                        $query->orWhere('status', '=', 'UNGRADED')
                                            ->orWhere('status', '=', 'GRADED');
                                    })
                                    ->where('user_id', '=', $member['id'])
                                    ->first();

                                if(empty($notTurnedIn)) {
                                    $group['members'][$key] = $member;
                                }
                            }

                            break;
                    }
                }
            }

            $takers[] = $group;
        }

        return $takers;
    }
}
