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

    protected function changeGroupCode()
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
