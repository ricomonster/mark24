<?php //-->

class GroupMember extends Eloquent {
    protected $table = 'group_members';

    public function member() {
        return $this->belongsTo('User');
    }

    public static function getGroupMembers($groupId, $lastId = null) {
        if(!is_null($lastId)) {
            return GroupMember::leftJoin(
                    'users',
                    'group_members.group_member_id',
                    '=',
                    'users.id')
                ->where('group_id', '=', $groupId)
                ->where('users.flag', '=', 1)
                ->where('users.id', '>', $lastId)
                ->orderBy('users.account_type', 'ASC')
                ->orderBy('users.username', 'ASC')
                ->groupBy('users.id')
                // ->take(10)
                ->get();
        }

        return GroupMember::leftJoin(
                'users',
                'group_members.group_member_id',
                '=',
                'users.id')
            ->where('group_id', '=', $groupId)
            ->where('users.flag', '=', 1)
            ->orderBy('users.account_type', 'ASC')
            ->orderBy('users.username', 'ASC')
            ->groupBy('users.id')
            // ->take(10)
            ->get();
    }

    public static function getAllGroupMembers() {
        return DB::select('SELECT t3.id, t3.name, t3.firstname, t3.lastname
                            FROM group_members as t1,
                            (SELECT groups.group_id FROM groups
                            INNER JOIN group_members
                            ON groups.group_id = group_members.group_id
                            WHERE group_members.group_member_id = ?) as t2,
                            users as t3
                            WHERE t1.group_id = t2.group_id
                            AND t1.group_member_id = t3.id
                            AND t3.id != ?
                            AND t3.flag = 1
                            GROUP BY t1.group_member_id',
                            array(Auth::user()->id, Auth::user()->id));
    }

    public static function allGroupMembers($option = null)
    {
        // // get first the groups of the user
        // $members = GroupMember::whereIn('group_id', Group::getMyGroupsId())
        //     ->where('group_members.group_member_id', '!=', Auth::user()->id)
        //     ->leftJoin('users', 'group_members.group_member_id', '=', 'users.id')
        //     ->groupBy('group_member_id');
        // if(!empty($option) && $option == 'teachers') $members->where('users.account_type', '=', 1);
        // if(!empty($option) && $option == 'students') $members->where('users.account_type', '=', 2);

        // return $members->get();
    }
}
