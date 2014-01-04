<ul class="file-stream list-view clearfix">
    @foreach($files as $file)
    <li class="file-holder">
        <div class="image-thumbnail pull-left">
            @if($file->file_thumbnail == 'file.png' || $file->file_thumbnail == 'zip.png')
            <img src="/assets/defaults/icons/{{ $file->file_thumnail }}">
            @endif
            @if($file->file_thumbnail != 'file.png' || $file->file_thumbnail != 'zip.png')
            <img src="/assets/thelibrary/{{ $file->file_thumbnail }}" class="image">
            @endif
        </div>
        <div class="file-details">
            <p><a href="/file/{{ $file->file_library_id }}" class="file-name">
                {{ (strlen($file->file_name) > 73) ?
                substr($file->file_name, 0, 70).'...' : $file->file_name; }}
            </a></p>
            <p><a href="/file/{{ $file->file_library_id }}" data-toggle="tooltip"
            title="Download File" class="download-link">
                <i class="fa fa-download"></i>
            </a></p>
        </div>
        <div class="clearfix"></div>
    </li>
    @endforeach
</ul>
