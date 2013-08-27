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

}
