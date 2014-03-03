<?php //-->

class AjaxGroupController extends BaseController
{
    public function lockGroup()
    {
        $groupId = Input::get('group_id');
        // find group
        $group = Group::find($groupId);
        $group->group_code = 'LOCKED';
        $group->save();

        return Response::json(array('error' => false));
    }

    public function changeGroupCode()
    {
        $groupId = Input::get('group_id');

        $newGroupCode = $this->_generateGroupCode();

        // find group
        $group = Group::find($groupId);
        $group->group_code = $newGroupCode;
        $group->save();

        return Response::json(array(
            'error'         => false,
            'group_code'    => $newGroupCode));
    }

    public function joinTheUser()
    {
        $input = Input::all();

        // check if the user is already part of the group
        $groupMember = GroupMember::where('group_id', '=', $input['group_id'])
            ->where('group_member_id', '=', $input['user_id'])
            ->first();
        if(!empty($groupMember)) {
            // unset the request
            $inquire = Inquire::where('type', '=', 'request_join_group')
                ->where('inquirer_id', '=', $input['user_id'])
                ->where('involved_id', '=', $input['group_id'])
                ->first();
            // update
            $inquire->status = 0;
            $inquire->save();

            return Response::json(array(
                'error'             => true,
                'nature_of_error'   => 'duplicate_member',
                'message'           => 'User is already member of the group.'));
        }

        // add the user to the group
        $member = new GroupMember;
        $member->group_id = $input['group_id'];
        $member->group_member_id = $input['user_id'];
        $member->save();

        // remove the request notification
        Notification::where('notification_type', '=', 'request_join_group')
           ->where('sender_id', '=', $input['user_id'])
           ->where('involved_id', '=', $input['group_id'])
           ->delete();

        // unset the request
        $inquire = Inquire::where('type', '=', 'request_join_group')
            ->where('inquirer_id', '=', $input['user_id'])
            ->where('involved_id', '=', $input['group_id'])
            ->first();
        // update
        $inquire->status = 0;
        $inquire->save();
        // create notification
        Notification::setup('accepted_join_group', array(
            'user_id'   => $input['user_id'],
            'group_id'  => $input['group_id']));
        // get user details
        $user = User::find($input['user_id']);

        return Response::json(array('error' => false, 'name' => $user->name));
    }

    public function getMoreMembers()
    {
        $groupId    = Input::get('group_id');
        $lastId     = Input::get('last_id');

        // group group details
        $group = Group::find($groupId);
        // get group owner
        $owner = User::find($group->owner_id);
        exit;
        return View::make('ajax.group.members')
            ->with('members', GroupMember::getGroupMembers($group->group_id, $lastId))
            ->with('ownerDetails', $owner);
    }

    protected function _generateGroupCode() {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

        $randomString = '';
        do {
           for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while(Group::where('group_code', '=', $randomString)->first());

        return $randomString;
    }
}
