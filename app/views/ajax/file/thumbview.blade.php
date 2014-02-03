<ul class="file-stream thumbnail-view clearfix">
    @foreach($files as $file)
    <li class="file-holder">
        <div class="thumbnail">
            @if($file->file_thumbnail == 'file.png' || $file->file_thumbnail == 'zip.png')
            <img src="/assets/defaults/icons/{{ $file->file_thumnail }}">
            @endif
            @if($file->file_thumbnail != 'file.png' || $file->file_thumbnail != 'zip.png')
            <img src="/assets/thelibrary/{{ $file->file_thumbnail }}" class="image">
            @endif
        </div>
        <p class="file-name">
            {{ (strlen($file->file_name) > 53) ?
            substr($file->file_name, 0, 50).'...' : $file->file_name; }}
        </p>
    </li>
    @endforeach
</ul>
