<?php //-->

class AjaxUsersController extends BaseController {

    const ALLOWED_SIZE = 25000000;

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
                Helper::thumbnailMaker($storagePath, $finalAvatarName, $smallThumbnailImage, 45);
                // normal thumbnail
                $normalThumbnailImage = $hashedAvatarName.'_normal.'.$avatarExtensionName;
                Helper::thumbnailMaker($storagePath, $finalAvatarName, $normalThumbnailImage, 80);
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
}
