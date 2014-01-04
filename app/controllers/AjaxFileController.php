<?php //-->

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
}
