@extends('templates.master')

@section('title')
Assignment Sheet
@stop

@section('internalCss')
<style type="text/css">
.assignment-manager-wrapper .assignment-details-wrapper { padding: 0; }
.assignment-details-wrapper .assignment-details { margin: 0; padding: 15px; }
.assignment-details-wrapper .assignment-details .assignment-sheet-icon { color: #8c94a7; float: left; font-size: 24px; }
.assignment-details-wrapper .assignment-details .content-details { margin-left: 35px; }
.assignment-details-wrapper .assignment-details .content-details p { font-size: 16px; margin: 0; padding: 0; }
.assignment-details-wrapper .assignment-details .content-details .due-date {
    color: #839096;
    font-size: 13px;
}

.assignment-details-wrapper .show-type-holder { padding: 10px; }
.assignment-details-wrapper .show-type-holder .show-type { display: inline-block; width: 120px; }
.assignment-details-wrapper .student-lists ul .group-name a {
    background-color: #757f93;
    border-radius: 0;
    color: #ffffff;
    padding: 12px;
}

.assignment-details-wrapper .student-lists ul li .show-taker-details {
    background-color: #ffffff;
    border-radius: 0;
}

.assignment-details-wrapper .student-lists ul li.active .show-taker-details {
    background-color: #f3f5f7;
    color: #2a6496;
}

.assignment-manager-wrapper .assignment-manager-default { padding: 0; }
.assignment-manager-default .default-header { border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-manager-default .default-header .assignment-details h3 { margin: 0; padding: 0; }
.assignment-manager-default .assignment-recipients { background-color: #f3f5f7; border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-manager-default .assignment-contents { padding: 30px 15px; }
.assignment-manager-default .files-attached {
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

.assignment-manager-wrapper .assignment-submission-wrapper { display: none; padding: 0; }
.assignment-submission-wrapper .manager-header { border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-submission-wrapper .manager-header .taker-details { margin-left: 10px; }
.assignment-submission-wrapper .manager-header .taker-details h4 { margin: 0 0 5px; }
.assignment-submission-wrapper .manager-header .taker-details .response-status { color: #839096; }
.assignment-submission-wrapper .manager-header .taker-score { font-size: 16px; }
.assignment-submission-wrapper .manager-header .taker-score .no-score-set { }
.assignment-submission-wrapper .manager-header .taker-score form { margin: 0; }
.assignment-submission-wrapper .manager-header .taker-score form .form-group,
.assignment-submission-wrapper .manager-header .taker-score form input {
    display: inline-block;
    margin: 0;
    width: 50px;
}

.assignment-submission-wrapper .manager-header .taker-score .user-total-score { font-size: 32px; }

.assignment-submission-wrapper .manager-header .taker-score .no-score-set form button { margin-left: 10px; }

.assignment-submission-wrapper .no-submission { padding: 80px 0; text-align: center; }
.assignment-submission-wrapper .assignment-response { padding: 30px 15px; }
</style>
@stop

@section('content')
<div class="assignment-manager-wrapper">
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
                <div class="show-type-holder">
                    <span>Showing:</span>
                    <select class="show-type form-control">
                        <option value="all" selected>All</option>
                        <option value="ungraded">Ungraded</option>
                        <option value="graded">Graded</option>
                        <option value="not_turned_in">Not turned in</option>
                    </select>
                </div>
                <div class="student-lists">
                    <ul class="list-holder nav nav-pills nav-stacked">
                        @foreach($takers as $taker)
                        <li class="group-name"><a href="#">{{ $taker->group_name }}</a></li>
                        @if(isset($taker->members))
                        @foreach($taker->members as $member)
                        <li>
                            <a href="#" class="show-taker-details"
                            data-user-id="{{ $member->id }}"
                            data-assignment-id="{{ $assignment->assignment_id }}"
                            data-post-id="{{ $post->post_id }}">
                                {{ $member->firstname.' '.$member->lastname }}
                            </a>
                        </li>
                        @endforeach
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="assignment-manager-default well">
                <div class="default-header">
                    <div class="assignment-details pull-left">
                        <h3>{{ $assignment->title }}</h3>
                        <div class="assignment-manager-controls">
                            <a href="#">Assignment Overview</a>
                            <a href="">All Submissions</a>
                        </div>
                    </div>
                    <div class="assignment-options pull-right">
                        <a href="#">Assignment Options</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="assignment-recipients">
                    <span>Assigned to:</span>
                    <?php $groupCount = count($takers); ?>
                    @foreach($takers as $key => $taker)
                    @if($groupCount - 1 === $key)
                    <a href="/groups/{{ $taker->group_id }}">
                        {{ $taker->group_name }}
                    </a>
                    @endif
                    @if($groupCount - 1 !== $key)
                    <a href="/groups/{{ $taker->group_id }}">
                        {{ $taker->group_name }},
                    </a>
                    @endif
                    @endforeach
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
            <div class="assignment-submission-wrapper well"></div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="/assets/js/sitefunc/assignmentmanager.js"></script>
@stop
