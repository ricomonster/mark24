<?php //-->

ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

class AjaxFileController extends BaseController
{
    public function getView()
    {
        $view = Input::get('view');
        $type = Input::get('type');

        // get the type of data to show
        switch($type) {
            case 'all' :
                // get all the files uploaded by the user
                $files = FileLibrary::where('user_id', '=', Auth::user()->id)
                    ->get();
                break;
            case 'attached' :
                // get all files that are attached to posts
                 $files = FileAttached::where('file_attached.user_id', '=', Auth::user()->id)
                    ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
                    ->get();
                break;
        }

        // what type of view to be shown
        switch ($view) {
            case 'thumb':
                return View::make('ajax.file.thumbview')
                    ->with('files', $files);
                break;
            case 'list' :
                return View::make('ajax.file.listview')
                    ->with('files', $files);
                break;
        }
    }

    public function uploadPost()
    {
        // prep some data
        $dropPoint = public_path().'/assets/thelibrary/'.sha1(Auth::user()->id);
        // print_r($_FILES);
        // exit;

        $file = Input::file('files');

        $fileName       = $file->getClientOriginalName();
        $fileExtension  = $file->getClientOriginalExtension();
        $mime           = $file->getMimeType();
        $fileSize       = $file->getSize();

        // check for the size of the file to uploaded
        // max size, 10 mb?
        if((655360) > $fileSize) {
            return Response::json(array(
                'error'     => true,
                'file_name' => $fileName,
                'message'   => 'File too large to upload.'));
        }

        // upload file
        $file->move($dropPoint, $fileName);

        // check if file is uploaded
        if(Input::hasFile('files')) {
            // create thumbnail
            if(substr($mime,0, 5) === "image") {
                $fileThumbnail = 'thumbnail_'.$fileName;
                Helper::thumbnailMaker($dropPoint, $fileName, $fileThumbnail, 150);
            }

            // save the file!
            $newFile = new FileLibrary;
            $newFile->user_id = Auth::user()->id;
            $newFile->file_name = $fileName;
            $newFile->file_storage_name = $fileName;
            $newFile->file_extension = $fileExtension;
            $newFile->mime_type = $mime;
            $newFile->file_path = sha1(Auth::user()->id).'/'.$fileName;
            $newFile->file_thumbnail = (isset($fileThumbnail)) ?
                sha1(Auth::user()->id).'/'.$fileThumbnail : 'txt.png';
            $newFile->save();

            $details = FileLibrary::find($newFile->file_library_id);

            return Response::json(array('error' => false, 'file' => $details->toArray()));
        }

        // file not uploaded
        if(!Input::hasFile('files')) {
            return Response::json(array(
                'error'     => true,
                'file_name' => $file->getClientOriginalName(),
                'message'   => "File '".$file->getClientOriginalName()."' could not be uploaded. Please try again later"));
        }

        $details = FileLibrary::find(5);

        return Response::json(array('error' => false, 'attached' => $details->toArray()));
    }
}
