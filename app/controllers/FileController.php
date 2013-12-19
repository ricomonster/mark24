<?php //-->

class FileController extends BaseController
{
    public function downloadFile($fileId)
    {
        // get the file details from the database
        $file = FileLibrary::find($fileId);
        $file->download_count +=  1;
        $file->save();

        $filePath = public_path().'/assets/thelibrary/'.$file->file_path;
        return Response::download($filePath);
    }
}
