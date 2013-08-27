<?php //-->

class GroupMember extends Eloquent {
    protected $table = 'group_members';

    public function member() {
        return $this->belongsTo('User');
    }

    public static function getGroupMembers($groupId) {
        $members = GroupMember::where('group_id', '=', $groupId)
            ->join('users', 'group_members.group_member_id', '=', 'users.id')
            ->get();

        return $members;
    }
}
