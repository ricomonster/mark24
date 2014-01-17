<?php //-->

class Group extends Eloquent {
    protected $table        = 'groups';
    protected $primaryKey   = 'group_id';

    public static function getMyGroups() {
        $groupMember = User::find(Auth::user()->id)->groupMember;

        foreach($groupMember as $member) {
            $group[] = Group::find($member->group_id);
        }

        return (empty($group)) ? false : $group;
    }

    public static function getMyGroupsId($user = null) {
        // check if theres a specific user
        if(is_null($user)) {
            $id = Auth::user()->id;
        }

        if(!is_null($user)) {
            $id = $user;
        }

        $groupMember = User::find($id)->groupMember;

        foreach($groupMember as $member) {
            $group = Group::find($member->group_id);
            $groupIds[] = $group->group_id;
        }

        return (empty($groupIds)) ? false : $groupIds;
    }

    public static function ongoingGroupChats()
    {
        $groupMember = User::find(Auth::user()->id)->groupMember;

        $chats = new StdClass();
        $counter = 0;
        foreach($groupMember as $member) {
            $group = Group::find($member->group_id);
            $ongoing = Conversation::where('group_id', '=', $group->group_id)
                ->where('status', '=', 'OPEN')
                ->first();
            if(!empty($ongoing)) {
                $chats->$counter = $group;
                $chats->$counter->conversation = $ongoing;
                $counter++;
            }
        }

        return $chats;
    }

}
