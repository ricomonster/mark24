<?php

class AjaxModalController extends BaseController {

    protected $_errors = null;

    public function showCreateGroup() {
        return View::make('ajax.modal.creategroup');
    }

    public function createGroup() {
        if(Request::ajax()) {
            // validate data inserted
            $this->_validateGroupCreation();

            if(!empty($this->_errors)) {
                // return to page the errors via JSON
                $return['error']    = true;
                $return['messages'] = $this->_errors;

                return Response::json($return);
            }

            // save group to database
            $createGroup = new Group;
            $createGroup->owner_id = Auth::user()->id;
            $createGroup->group_code = $this->_generateGroupCode();
            $createGroup->group_name = Input::get('group-name');
            $createGroup->group_description = Input::get('group-description');
            $createGroup->group_size = Input::get('group-size');
            $createGroup->save();

            // add user as a member
            $addGroupMember = new GroupMember;
            $addGroupMember->group_member_id = Auth::user()->id;
            $addGroupMember->group_id = $createGroup->group_id;
            $addGroupMember->save();

            // generate redirect link and send via JSON
            $return['error']    = false;
            $return['lz_link']  = sprintf(Request::root().'/groups/%s', $createGroup->group_id);

            return Response::json($return);
        }
    }

    /* Protected Methods
    -------------------------------*/
    protected function _validateGroupCreation() {
        $this->_errors = array();

        $groupName          = Input::get('group-name');
        $groupSize          = Input::get('group-size');
        $groupDescription   = Input::get('group-description');

        if(empty($groupName)) {
            $this->_errors['groupName'] = 'You must enter a name for the group';
        }

        if(empty($groupSize)) {
            $this->_errors['groupSize'] = 'You must enter the expected group size of the group';
        }

        if(empty($groupDescription)) {
            $this->_errors['groupDescription'] = 'You must enter a description of the group';
        }

        return empty($this->_errors);
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
