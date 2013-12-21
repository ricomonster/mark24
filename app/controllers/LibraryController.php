<?php //-->

class LibraryController extends BaseController
{
    public function __construct()
    {
        $this->beforeFilter('auth');
    }

    public function index()
    {
        return View::make('library.index');
    }

    public function folders()
    {
        return View::make('library.folder');
    }

    public function attachedFiles()
    {
        $files = FileAttached::where('file_attached.user_id', '=', Auth::user()->id)
            ->leftJoin('file_library', 'file_attached.file_id', '=', 'file_library.file_library_id')
            ->get();

        return View::make('library.attachedfiles')
            ->with('files', $files);
    }
}
