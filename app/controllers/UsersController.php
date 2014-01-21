<?php

class UsersController extends BaseController {

    public function getSignin() {
        $usernameEmail  = Input::get('signin-username-email');
        $password       = Input::get('signin-password');

        // check if credentials is empty
        if(empty($usernameEmail) || empty($password)) {
            // set error message and redirect
            Session::flash('loginError', 'Empty credentials');
            return Redirect::to('/');
        }

        // check if the usernameEmail is username or email
        if(filter_var($usernameEmail, FILTER_VALIDATE_EMAIL)) {
            // it's an email
            $credentials = array(
                'email'     => $usernameEmail,
                'password'  => $password);
        } else {
            // it's not an email
            $credentials = array(
                'username'     => $usernameEmail,
                'password'  => $password);
        }

        // credentials are present. validate if credentials exists
        // or match to any records in database
        if(Auth::attempt($credentials)) {
            // there's a chance that the super user admin will
            // login here
            if(Auth::user()->flag == 0) {
                return Redirect::to('control/choose-account-type');
            }

            return Redirect::to('home');
        }

        // credentials not found
        Session::flash('loginError', 'Incorrect username or password.');
        return Redirect::to('/');
    }

    public function getSignout() {
        Auth::logout();
        return Redirect::to('/');
    }

    public function test()
    {
        $test       = 'asdf';
        $testing    = 'asdf';
    }

}
