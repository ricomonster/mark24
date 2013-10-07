<?php

class ProfileController extends BaseController
{

    public function showIndex($user)
    {
        // check if the user var is an int
        if(is_int($user)) {
            // it's a ID of the user
            $user = User::find($user);
        }

        // user is a string and it's the username of the user
        if(!is_int($user)) {
            $user = User::where('username', '=', $user)
                ->first();
        }

        // check if the details is empty
        // most likely it's a false user
        if(empty($user)) {
            // show or redirect to 404
            echo '404';
            exit;
        }

        return View::make('profile.index')
            ->with('user', $user);
    }

}
