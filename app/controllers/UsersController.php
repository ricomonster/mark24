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
            return Redirect::to('home');
        }

        // credentials not found
        Session::flash('loginError', 'Incorrect username or password.');
        return Redirect::to('/');
    }

    public function createTeacher() {
        $password = Input::get('teacher-password');

        $teacherUser                = new User;
        $teacherUser->account_type  = 1;
        $teacherUser->name          = ucwords(Input::get('teacher-firstname')).' '.ucwords(Input::get('teacher-lastname'));
        $teacherUser->salutation    = Input::get('teacher-title');
        $teacherUser->firstname     = ucwords(Input::get('teacher-firstname'));
        $teacherUser->lastname      = ucwords(Input::get('teacher-lastname'));
        $teacherUser->username      = Input::get('teacher-username');
        $teacherUser->email         = Input::get('teacher-email');
        $teacherUser->password      = Hash::make($password);
        // save to database
        $teacherUser->save();
        // set the Auth to login the user
        Auth::loginUsingId($teacherUser->id);

        return Redirect::to('home');

    }

    public function createStudent() {
        $groupCode = Input::get('student-group-code');
        // validate first the form
        $group = Group::where('group_code', '=', $groupCode)->first();

        $password = Input::get('student-password');

        $studentUser = new User;
        $studentUser->account_type  = 2;
        $studentUser->name          = ucwords(Input::get('student-firstname')).' '.ucwords(Input::get('student-lastname'));
        $studentUser->firstname     = ucwords(Input::get('student-firstname'));
        $studentUser->lastname      = ucwords(Input::get('student-lastname'));
        $studentUser->username      = Input::get('student-username');
        $studentUser->email         = Input::get('student-email');
        $studentUser->password      = Hash::make($password);
        // save to database
        $studentUser->save();

        // add student to group as a member
        $addMember                  = new GroupMember;
        $addMember->group_member_id = $studentUser->id;
        $addMember->group_id        = $group->group_id;
        $addMember->save();

        // set the Auth to login the user
        Auth::loginUsingId($studentUser->id);

        return Redirect::to('home');
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
