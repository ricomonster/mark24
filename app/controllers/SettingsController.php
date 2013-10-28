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
