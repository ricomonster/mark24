<?php //-->

class ConfirmationController extends BaseController
{
    public function confirmMessageSuccessfull()
    {
        if(Auth::guest()) {
            return View::make('confirmation.confirmmessagesuccess');
        }

        return View::make('confirmation.loggedconfirmsuccess');
    }

    public function confirmedAccount()
    {
        $input = Input::all();
        // check the code and the key
        $confirmationExists = ConfirmationCode::where('confirmation_code_id', '=', $input['code_id'])
            ->where('confirmation_code', '=', $input['key'])
            ->where('confirmation_type', '=', 1)
            ->where('used', '=', 0)
            ->first();

        // get the user
        $user = User::where('id', '=', $confirmationExists->user_id)
            ->where('email', '=', $input['email_address'])
            ->first();

        if(empty($confirmationExists) || empty($user)) {
            // redirect to an error page
            echo 'yes';
            return View::make('templates.unauthorizedfourohfour');
        }

        $confirmationExists->used = 1;
        $confirmationExists->save();

        // update the user
        $user->confirmed_account = 1;
        $user->save();

        return View::make('confirmation.confirmedemail');
    }
}
