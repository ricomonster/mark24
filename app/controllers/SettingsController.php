<?php //-->

class SettingsController extends BaseController {
    protected $errors = null;

    public function __construct() {
        $this->beforeFilter('auth');
    }

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
            if(!Auth::validate(array('username'=>Auth::user()->username, 'password'=>$currentPassword))) {
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

    protected function countries()
    {
        return array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
    }
}
