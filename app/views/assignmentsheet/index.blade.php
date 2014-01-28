@extends('templates.master')

@section('title')
Assignment Sheet
@stop

@section('internalCss')
<style type="text/css">
.assignment-sheet-wrapper .assignment-details-wrapper { padding: 0; }
.assignment-details-wrapper .assignment-details { margin: 0; padding: 15px; }
.assignment-details-wrapper .assignment-details .assignment-sheet-icon { color: #8c94a7; float: left; font-size: 24px; }
.assignment-details-wrapper .assignment-details .content-details { margin-left: 35px; }
.assignment-details-wrapper .assignment-details .content-details p { font-size: 16px; margin: 0; padding: 0; }
.assignment-details-wrapper .assignment-details .content-details .due-date {
    color: #839096;
    font-size: 13px;
}
.assignment-details-wrapper .assignment-status { padding: 15px; }

.assignment-sheet-wrapper .assignment-header { padding: 0; }
.assignment-header .assignment-title { margin: 0; padding: 15px; }
.assignment-header .assignment-title h3 { margin: 0; padding: 0; }
.assignment-header .assignment-contents { padding: 30px 15px; }
.assignment-header .files-attached {
    list-style: none;
    margin: 0;
    padding: 0;
}

.files-attached .file-holder {
    border-radius: 3px;
    border: 1px solid #dfe4e8;
    margin: 10px 0 0 0;
    padding: 10px;
}

.files-attached .file-holder .file-thumbnail img { border: 1px solid #dfe4e8; max-height: 60px; }
.files-attached .file-holder .file-details { margin-left: 10px; word-wrap: break-word; width: 84%; }
/*.files-attached .file-holder .file-details a {  }*/
.files-attached .file-holder .file-details .file-type { color: #839096; display: block; font-size: 13px; }
.files-attached .file-holder .file-details .file-attached-controls a {
    color: #839096;
    font-size: 18px;
    margin-right: 5px;
}

.files-attached .file-holder .file-details .file-attached-controls a:hover{ text-decoration: none; }

.assignment-sheet-wrapper .assignment-sheet-form-wrapper { padding: 0; }
.assignment-sheet-form-wrapper .assignment-user-header .user-details-wrapper .user-details { margin-left: 50px; }
.assignment-user-header .user-details-wrapper .user-details .user-name { margin: 0; }
.assignment-sheet-form-wrapper .assignment-user-header { margin: 0; padding: 15px; }
.assignment-sheet-form-wrapper .form-group { padding: 15px 15px 0; }
.assignment-sheet-form-wrapper .form-group textarea { min-height: 100px; resize: none; }
.assignment-sheet-form-wrapper .button-control { padding: 0 15px 15px; }

.assignment-sheet-form-wrapper .assignment-response-holder { display: none; min-height: 100px; padding: 15px; }
</style>
@stop

@section('content')
<div class="assignment-sheet-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="assignment-details-wrapper well">
                <div class="assignment-details page-header">
                    <i class="fa fa-clipboard assignment-sheet-icon"></i>
                    <div class="content-details">
                        <p>{{ $assignment->title }}</p>
                        <span class="due-date">
                            Due {{ date('M d, Y', strtotime($post->assignment_due_date)) }}
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="assignment-status">
                    @if(empty($userAssignment))
                    <span>Not turned in</span>
                    @endif
                    @if(!empty($userAssignment))
                    <span>{{ ucfirst(strtolower($userAssignment->status)) }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="assignment-header well">
                <div class="assignment-title page-header">
                    <h3>{{ $assignment->title }}</h3>
                </div>
                <div class="assignment-contents">
                    <div class="assignment-description">{{ $assignment->description }}</div>
                    @if(!empty($files))
                    <ul class="files-attached">
                        @foreach($files as $file)
                        <li class="file-holder clearfix">
                            <div class="file-thumbnail pull-left">
                                <a href="/file/{{ $file->file_library_id }}">
                                    @if(substr($file->mime_type, 0, 5) === 'image')
                                    <img src="/assets/thelibrary/{{ $file->file_thumbnail }}">
                                    @endif
                                    @if(substr($file->mime_type, 0, 5) !== 'image')
                                    <img src="/assets/defaults/icons/{{ $file->file_thumbnail }}">
                                    @endif
                                </a>
                            </div>
                            <div class="file-details pull-left">
                                <a href="/file/{{ $file->file_library_id }}">{{ $file->file_name }}</a>
                                <span class="file-type">
                                    {{ strtoupper($file->file_extension) }} File
                                </span>
                                <div class="file-attached-controls">
                                    <a href="#" data-toggle="tooltip" title="Add to The Library">
                                        <i class="fa fa-archive"></i>
                                    </a>
                                    <a href="/file/{{ $file->file_library_id }}" data-toggle="tooltip"
                                    title="Download File">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            <div class="assignment-sheet-form-wrapper well">
                <div class="assignment-user-header page-header">
                    <div class="user-details-wrapper">
                        {{ Helper::avatar(42, "normal", "img-rounded pull-left", Auth::user()->id) }}
                        <div class="user-details">
                            <h4 class="user-name">{{ Auth::user()->name }}</h4>
                            <div class="user-status text-muted">
                                @if(empty($userAssignment))
                                Not turned in
                                @endif
                                @if(!empty($userAssignment))
                                Submitted on {{ date('F d, Y h:i A', strtotime($userAssignment->created_at)) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                @if(empty($userAssignment))
                {{ Form::open(array('class' => 'assignment-sheet-form')) }}
                    <div class="form-group">
                        <textarea class="form-control assignment-response" name="assignment-response"
                        placeholder="Type your responses here..."></textarea>
                    </div>
                    <div class="button-control">
                        <input type="hidden" name="assignment-id"
                        value="{{ $assignment->assignment_id }}">
                        <input type="hidden" name="post-id"
                        value="{{ $post->post_id }}">
                        <button type="submit" class="btn btn-primary pull-right submit-response">
                            Turn in Assignment
                        </button>
                        <div class="clearfix"></div>
                    </div>
                {{ Form::close() }}
                @endif
                <div class="assignment-response-holder"
                <?php echo (empty($userAssignment)) ? null : ' style="display: block"'; ?>>
                    @if(!empty($userAssignment))
                    {{ $userAssignment->response }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/assignmentsheet.js"></script>
@stop
