<?php //-->

class SettingsController extends BaseController {
    protected $errors = null;

    public function getIndex() {
        return View::make('settings.index');
    }

    public function getPasswordPage() {
        return View::make('settings.password');
    }

    public function changePassword()
    {
        // validate the request
        $this->validateChangePassword();

        if(empty($this->errors)) {
            // update the new password
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make(Input::get('new-password'));
            $user->save();

            return Response::json(array('error' => false));
        }

        if(!empty($this->errors)) {
            // return the errors
            return Response::json(array(
                'error'     => true,
                'messages'  => $this->errors));
        }
    }

    public function predefinedAvatar()
    {
        $avatar = Input::get('avatar');

        switch ($avatar) {
            case '1':
                $avatar = 'default_avatar.png';
                break;
            case '2':
                $avatar = 'default_avatar_2.png';
                break;
            case '3':
                $avatar = 'default_avatar_3.png';
                break;
            case '4':
                $avatar = 'default_avatar_4.png';
                break;
            default:
                $avatar = 'default_avatar.png';
                break;
        }

        // update
        $user = User::find(Auth::user()->id);
        $user->avatar = 'default_avatar.png';
        $user->avatar_small = $avatar;
        $user->avatar_normal = $avatar;
        $user->avatar_large = $avatar;
        $user->save();

        // prep the image url
        $url = Request::root().'/assets/defaults/avatar/'.$avatar;

        return Response::json(array(
            'error'     => false,
            'avatar'    => $url));
    }

    protected function validateChangePassword()
    {
        $this->errors = array();

        $currentPassword    = Input::get('current-password');
        $newPassword        = Input::get('new-password');
        $confirmPassword    = Input::get('confirm-new-password');

        // check first the current password
        // check if it's empty
        if(empty($currentPassword)) {
            $this->errors['current_password'] = 'This is not your current password';
        }

        // if it's not empty, check if the password is the same
        // to the current one
        if(!empty($currentPassword)) {
            if(!Auth::validate(['username'=>Auth::user()->username, 'password'=>$currentPassword])) {
                $this->errors['current_password'] = 'This is not your current password';
            }
        }

        // validate new password
        if(empty($newPassword)) {
            $this->errors['new_password'] = 'What is your new password?';
        }

        if(!empty($newPassword)) {
            // length should be atleast 6 characters
            if(strlen($newPassword) < 6) {
                $this->errors['new_password'] = 'Must be 6+ characters';
            } else if($newPassword != $confirmPassword) {
                // check if both passwords are the same
                $this->errors['confirm_password'] = 'Both passwords should be the same.';
            }
        }

        return $this;
    }
}
