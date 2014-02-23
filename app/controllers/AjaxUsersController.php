<?php //-->

class AjaxUsersController extends BaseController {

    const ALLOWED_SIZE = 25000000;

    protected $_user = null;
    protected $_errors = null;

    public function postUploadPhoto() {
        $imageExtensions    = array(
            'jpg',  'jpe',  'jpeg',
            'gif',  'png',  'bmp',
            'JPG',  'PNG',  'JPEG');
        $hashedId           = sha1(Auth::user()->id);
        $uploadPath         = public_path().'/assets/avatars/';

        $avatar = Input::file('avatar-file');
        $avatarExtensionName = $avatar->getClientOriginalExtension();

        // validate image
        if(!in_array($avatarExtensionName, $imageExtensions)) {
            $return['error']    = true;
            $return['message']  = 'File type is invalid.';
        } else if($avatar->getSize() > self::ALLOWED_SIZE) {
            $return['error']    = true;
            $return['message']  = 'Avatar image is too large';
        } else {
            // let's upload the image but first, let's check if
            // there's a hashed id stored in the database
            if(empty(Auth::user()->hashed_id)) {
                $user = User::find(Auth::user()->id);
                $user->hashed_id = $hashedId;
                $user->save();
            }

            // setup the final storage path
            $storagePath        = $uploadPath.$hashedId;
            $hashedAvatarName   = sha1($avatar->getClientOriginalName());
            $finalAvatarName    = $hashedAvatarName.'.'.$avatarExtensionName;

            // let's upload!!
            $uploaded = $avatar->move($storagePath, $finalAvatarName);
            // check if file is uploaded
            if(!Input::hasFile('avatar-file')) {
                $return['error'] = true;
                $return['message'] = 'There was a problem uploading your avatar. Please try again later.';
            }

            if(Input::hasFile('avatar-file')) {
                // setup the files for thumbnails
                // small thumbnail
                $smallThumbnailImage = $hashedAvatarName.'_small.'.$avatarExtensionName;
                Helper::thumbnailMaker($storagePath, $finalAvatarName, $smallThumbnailImage, 60);
                // normal thumbnail
                $normalThumbnailImage = $hashedAvatarName.'_normal.'.$avatarExtensionName;
                Helper::thumbnailMaker($storagePath, $finalAvatarName, $normalThumbnailImage, 100);
                // large thumbnail
                $largeThumbnailImage = $hashedAvatarName.'_large.'.$avatarExtensionName;
                Helper::thumbnailMaker($storagePath, $finalAvatarName, $largeThumbnailImage, 200);

                // update the user data
                $updateUser                 = User::find(Auth::user()->id);
                $updateUser->avatar         = $finalAvatarName;
                $updateUser->avatar_small   = $smallThumbnailImage;
                $updateUser->avatar_normal  = $normalThumbnailImage;
                $updateUser->avatar_large   = $largeThumbnailImage;
                $updateUser->save();

                // send the normal image url
                $return['error'] = false;
                $return['userAvatar'] = sprintf(Request::root().'/assets/avatars/%s/%s',
                    $hashedId,
                    $largeThumbnailImage);
            }
        }

        return Response::json($return);
    }

    public function updateUserAccountDetails() {
        if(Request::ajax()) {
            $email = Input::get('email');
            // check if email is changed
            if($email != Auth::user()->email) {
                // check if the new email already exists
                $emailExists = User::where('email', '=', $email)
                    ->first();
                if(!empty($emailExists)) {
                    $return['error']    = true;
                    $return['message']  = 'Email already exists';
                    $return['field']    = 'email';

                    return Response::json($return);
                }
            }

            // update user info in database
            $updateUser = User::find(Auth::user()->id);
            $updateUser->email = Input::get('email');
            $updateUser->save();

            $return['error'] = false;

            return Response::json($return);
        }
    }

    public function updateUserDetails()
    {
        if(Request::ajax()) {
            $updateUser                 = User::find(Auth::user()->id);
            $updateUser->name           = ucwords(Input::get('firstname')).' '.ucwords(Input::get('lastname'));
            $updateUser->salutation     = (Auth::user()->account_type == 1) ?
                Input::get('salutation') : null;
            $updateUser->firstname      = ucwords(Input::get('firstname'));
            $updateUser->lastname       = ucwords(Input::get('lastname'));
            $updateUser->save();

            $return['error'] = false;

            return Response::json($return);
        }
    }

    public function updateUserStory()
    {
        if(Request::ajax()) {
            $input = Input::all();
            // update
            $user               = User::find(Auth::user()->id);
            $user->tagline      = $input['tagline'];
            $user->description  = $input['description'];
            $user->save();

            return Response::json(array('error' => false));
        }
    }

    public function updateUserPlaces()
    {
        if(Request::ajax()) {
            $input = Input::all();
            // update
            $user                   = User::find(Auth::user()->id);
            $user->country          = $input['country'];
            $user->hometown         = ucwords($input['hometown']);
            $user->current_place    = ucwords($input['current']);
            $user->save();

            return Response::json(array('error' => false));
        }
    }

    public function validateStudentDetails()
    {
        $this->_errors = array();

        $groupCode  = Input::get('student-group-code');
        $username   = Input::get('student-username');
        $password   = Input::get('student-password');
        $email      = Input::get('student-email');
        $firstname  = Input::get('student-firstname');
        $lastname   = Input::get('student-lastname');

        // validate group code
        if(!trim($groupCode)) {
            $this->_errors['student-group-code'] = 'Group code is required';
        } else if(trim($groupCode)) {
            // check if a group exists
            $groupExist = Group::where('group_code', '=', $groupCode)
                ->first();
            if(empty($groupExist)) {
                $this->_errors['student-group-code'] = 'Group doesn\'t exists';
            }
        }

        // validate username
        if(!trim($username)) {
            $this->_errors['student-username'] = 'Required';
        } else if(trim($username)) {
            // check if username exists
            $usernameExists = User::where('username', '=', $username)->first();
            if(!empty($usernameExists)) {
                $this->_errors['student-username'] = 'Username already exists';
            }
        }

        // validate password
        if(!trim($password)) {
            $this->_errors['student-password'] = 'Required';
        } else if(trim($password)) {
            // check the password length
            if(strlen($password) < 6) {
                $this->_errors['student-password'] = 'Password should be 6+ characters';
            }
        }

        // validate email
        if(!trim($email)) {
            $this->_errors['student-email'] = 'Required';
        } else if(trim($email)) {
            // check if the email format is valid
            // check if email already exists
            $emailExists = User::where('email', '=', $email)->first();
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->_errors['student-email'] = 'Format invalid';
            } else if(!empty($emailExists)) {
                $this->_errors['student-email'] = 'Email already exists';
            }
        }

        // validate first name
        if(!trim($firstname)) {
            $this->_errors['student-firstname'] = 'Required';
        }

        // validate last name
        if(!trim($lastname)) {
            $this->_errors['student-lastname'] = 'Required';
        }

        if(empty($this->_errors)) {
            $groupCode = Input::get('student-group-code');
            // validate first the form
            $group = Group::where('group_code', '=', $groupCode)->first();

            $password = Input::get('student-password');

            $studentUser = new User;
            $studentUser->account_type  = 2;
            $studentUser->name          = ucwords($firstname).' '.ucwords($lastname);
            $studentUser->firstname     = ucwords($firstname);
            $studentUser->lastname      = ucwords($lastname);
            $studentUser->username      = $username;
            $studentUser->email         = $email;
            $studentUser->password      = Hash::make($password);
            // save to database
            $studentUser->save();

            // set the Auth to login the user
            // Auth::loginUsingId($studentUser->id);

            // create request
            $request = new Inquire;
            $request->inquirer_id = $studentUser->id;
            $request->involved_id = $group->group_id;
            $request->type = 'request_join_group';
            $request->save();

            $this->_user = $studentUser;

            // send request to the owner of the group
            Notification::setup('request_join_group', array(
                'sender_id' => $studentUser->id,
                'involved_id' => $group->group_id));
            // send mail
            $this->sendMail();
            return Response::json(array(
                'error' => false,
                'lz'    => Request::root().'/confirmation-message-sent'));
        }

        return Response::json(array(
            'error'     => true,
            'messages'  => $this->_errors));
    }

    public function validateTeacherDetails()
    {
        $this->_errors = array();

        $salutation = Input::get('teacher-title');
        $firstname = Input::get('teacher-firstname');
        $lastname = Input::get('teacher-lastname');
        $username = Input::get('teacher-username');
        $email = Input::get('teacher-email');
        $password = Input::get('teacher-password');

        // validate details
        // validate salutation
        if(!trim($salutation)) {
            $this->_errors['teacher-title'] = 'Required';
        }

        // validate username
        if(!trim($username)) {
            $this->_errors['teacher-username'] = 'Required';
        } else if(trim($username)) {
            // check if username exists
            $usernameExists = User::where('username', '=', $username)->first();
            if(!empty($usernameExists)) {
                $this->_errors['teacher-username'] = 'Username already exists';
            }
        }

        // validate password
        if(!trim($password)) {
            $this->_errors['teacher-password'] = 'Required';
        } else if(trim($password)) {
            // check the password length
            if(strlen($password) < 6) {
                $this->_errors['teacher-password'] = 'Password should be 6+ characters';
            }
        }

        // validate email
        if(!trim($email)) {
            $this->_errors['teacher-email'] = 'Required';
        } else if(trim($email)) {
            // check if the email format is valid
            // check if email already exists
            $emailExists = User::where('email', '=', $email)->first();
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->_errors['teacher-email'] = 'Format invalid';
            } else if(!empty($emailExists)) {
                $this->_errors['teacher-email'] = 'Email already exists';
            }
        }

        // validate first name
        if(!trim($firstname)) {
            $this->_errors['teacher-firstname'] = 'Required';
        }

        // validate last name
        if(!trim($lastname)) {
            $this->_errors['teacher-lastname'] = 'Required';
        }

        if(empty($this->_errors)) {
            $teacherUser                = new User;
            $teacherUser->account_type  = 1;
            $teacherUser->name          = ucwords(Input::get('teacher-firstname')).' '
                .ucwords(Input::get('teacher-lastname'));
            $teacherUser->salutation    = Input::get('teacher-title');
            $teacherUser->firstname     = ucwords(Input::get('teacher-firstname'));
            $teacherUser->lastname      = ucwords(Input::get('teacher-lastname'));
            $teacherUser->username      = Input::get('teacher-username');
            $teacherUser->email         = Input::get('teacher-email');
            $teacherUser->password      = Hash::make($password);
            // save to database
            $teacherUser->save();
            // set the Auth to login the user
            // Auth::loginUsingId($teacherUser->id);
            $this->_user = $teacherUser;
            // send mail
            $this->sendMail();
            return Response::json(array(
                'error' => false,
                'lz'    => Request::root().'/confirmation-message-sent'));
        }

        return Response::json(array(
            'error'     => true,
            'messages'  => $this->_errors));
    }

    protected function sendMail()
    {
        $user = User::find($this->_user->id);
        $code = $this->generateCode();
        // create code
        $confirmation = new ConfirmationCode;
        $confirmation->user_id = $user->id;
        $confirmation->confirmation_code = $code;
        $confirmation->confirmation_type = 1;
        $confirmation->save();

        $details = array(
            'email' => $user->email,
            'name'  => $user->name);
        $data = array(
            'code'              => $code,
            'confirmationCode'  => $confirmation->confirmation_code_id,
            'email'             => $user->email);

        Mail::send('emails.confirmemail', $data, function($message) use ($details) {
           $message->to($details['email'], $details['name'])
                ->subject('Welcome to eLinet!');
        });
    }

    protected function generateCode()
    {
        mt_srand(microtime(true)*100000 + memory_get_usage(true));
        return base64_encode(uniqid(mt_rand(), true));
    }
}
