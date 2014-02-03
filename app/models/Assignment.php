<?php //-->

class Assignment extends Eloquent
{
    protected $table = 'assignments';
    protected $primaryKey = 'assignment_id';

    public static function getAssignmentRecipients($assignmentId, $status = 'all')
    {
        // get first the post
        $post = Post::where('post_type', '=', 'assignment')
            ->where('assignment_id', '=', $assignmentId)
            ->first();
        // get the recipients
        $recipients = PostRecipient::where('post_id', '=', $post->post_id)
            ->get();
        // loop so we can get the members and details of the recipients
        $lists = new StdClass();
        foreach($recipients as $key => $recipient) {
            // check first the type of recipient
            if($recipient->recipient_type == 'group') {
                // get group details
                $group = Group::find($recipient->recipient_id);
                $lists->$key = $group;
                // check the status and get the
                // members who are under that status
                $members = GroupMember::where('group_id', '=', $group->group_id)
                    ->whereNotIn('users.account_type', array(0, 1))
                    ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
                    ->orderBy('users.lastname')
                    ->get();
                switch($status) {
                    case 'all' :
                        $lists->$key->members = $members;
                        break;
                    case 'ungraded' :
                        $ungradedMember = new StdClass();
                        foreach($members as $key2 => $member) {
                            // check each member if the member already
                            // submitted an assignment
                            $submitted = AssignmentResponse::where('assignment_id', '=', $assignmentId)
                                ->where('user_id', '=', $member->id)
                                ->where('status', '=', 'AWAITING GRADE')
                                ->first();
                            if(!empty($submitted)) {
                                $ungradedMember->$key = $member;
                            }
                        }

                        $lists->$key->members = $ungradedMember;
                        break;
                    case 'graded' :
                        $ungradedMember = new StdClass();
                        foreach($members as $key2 => $member) {
                            // check each member if the member already
                            // submitted an assignment
                            $submitted = AssignmentResponse::where('assignment_id', '=', $assignmentId)
                                ->where('user_id', '=', $member->id)
                                ->where('status', '=', 'GRADED')
                                ->first();
                            if(!empty($submitted)) {
                                $ungradedMember->$key = $member;
                            }
                        }

                        $lists->$key->members = $ungradedMember;
                        break;
                     case 'not_turned_in' :
                        $ungradedMember = new StdClass();
                        foreach($members as $key2 => $member) {
                            // check each member if the member already
                            // submitted an assignment
                            $submitted = AssignmentResponse::where('assignment_id', '=', $assignmentId)
                                ->where('user_id', '=', $member->id)
                                ->where(function($query) {
                                    $query->orWhere('status', '=', 'AWAITING GRADE')
                                        ->orWhere('status', '=', 'GRADED');
                                })
                                ->first();
                            if(empty($submitted)) {
                                $ungradedMember->$key = $member;
                                $ungradedMember->$key->response = $submitted;
                            }
                        }

                        $lists->$key->members = $ungradedMember;
                        break;
                }
            }
        }

        return $lists;
    }
}
