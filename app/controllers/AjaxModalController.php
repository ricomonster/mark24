<?php

class AjaxModalController extends BaseController {

    protected $_errors = null;

    // Group Module Functions
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

    public function showJoinGroup() {
        return View::make('ajax.modal.joingroup');
    }

    public function joinGroup() {
        $groupCode  = Input::get('group-code');
        $group      = Group::where('group_code', '=', $groupCode)->first();

        // validate group membership
        if(empty($groupCode)) {
            $error = 'Please provide a group code';
        } else if(empty($group)) {
            $error = 'Group does not exists.';
        } else {
            $groupMember = GroupMember::where('group_id', '=', $group->group_id)
                ->where('group_member_id', '=', Auth::user()->id)
                ->first();

            if(!empty($groupMember)) {
                $error = 'You are already a member of that group.';
            }
        }

        // check if there are errors detected
        if(!empty($error)) {
            $return['error']    = true;
            $return['message']  = $error;
        } else {
            // join the user to the group
            $addGroupMember = new GroupMember;
            $addGroupMember->group_member_id = Auth::user()->id;
            $addGroupMember->group_id = $group->group_id;
            $addGroupMember->save();

            // set json shits
            $return['error'] = false;
            $return['lz_link']  = sprintf(Request::root().'/groups/%s', $group->group_id);
        }

        return Response::json($return);
    }

    // End of Group Module Functions

    // Forum Functions
    public function showAddCategory()
    {
        return View::make('ajax.modal.addforumcategory');
    }

    public function addCategory()
    {
        // validate first the form
        $this->_validateAddForumCategory();

        // check if there are errors stored
        if(!empty($this->_errors)) {
            $return = array(
                'error'     => true,
                'messages'  => $this->_errors);

            return Response::json($return);
        }

        // no errors
        // check first if there's already a general topic in the forum
        $general = ForumCategory::where('name', '=', 'General Discussion')->first();
        if(empty($general)) {
            $generalCategory = new ForumCategory;
            // add a general discussion category
            $generalCategory->category_name = 'General Discussion';
            $generalCategory->description = 'Let\'s talk everything under the sun. :)';
            $generalCategory->seo_name = Helper::seoFriendlyUrl('General Discussion');
            $generalCategory->save();
        }

        // save category name
        // and create seo friendly url
        $addCategory = new ForumCategory;

        $newCategoryUrl = Helper::seoFriendlyUrl(Input::get('category-name'));
        $addCategory->category_name = ucwords(Input::get('category-name'));
        $addCategory->description = Input::get('category-description');
        $addCategory->seo_name = $newCategoryUrl;
        $addCategory->save();

        // create redirect link
        $lzLink = Request::root().'/forum/'.$newCategoryUrl;
        // redirect to the category page
        return Response::json(array(
            'error' => false,
            'lz' => $lzLink));
    }

    // End of Forum Functions

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

    protected function _validateAddForumCategory()
    {
        $this->_errors = array();

        $name = Input::get('category-name');
        $description = Input::get('category-description');

        $nameExists = ForumCategory::where('category_name', '=', $name)->first();

        if(empty($name)) {
            $this->_errors['category_name'] = 'There should be a category name';
        } else if(!empty($name)) {
            // check if name already exists
            if(!empty($nameExists)) {
                $this->_errors['category_name'] = 'Category name '.$name.' already exists';
            }
        }

        if(empty($description)) {
            $this->_errors['category_description'] = 'Add a category description';
        }

        return empty($this->_errors);
    }
}
